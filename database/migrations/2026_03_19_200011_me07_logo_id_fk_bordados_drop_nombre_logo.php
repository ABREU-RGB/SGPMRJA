<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * ME-07: Reemplazar nombre_logo (texto libre) por logo_id (FK) en tablas de bordado.
 *
 * 1. Agrega logo_id FK a detalle_pedido_bordado y detalle_cotizacion_bordado.
 * 2. Data migration: matchea nombre_logo_aplicado existente → logo.name.
 * 3. Elimina nombre_logo de detalle_pedido y detalle_cotizacion (campo redundante).
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Agregar columna logo_id a tablas de bordado ──
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->unsignedBigInteger('logo_id')->nullable()->after('ubicacion_bordado_id');
            $table->foreign('logo_id')->references('id')->on('logo')->nullOnDelete();
        });

        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->unsignedBigInteger('logo_id')->nullable()->after('ubicacion_bordado_id');
            $table->foreign('logo_id')->references('id')->on('logo')->nullOnDelete();
        });

        // ── 2. Data migration: matchear texto existente → logo_id ──

        // 2a. Match exacto (case-sensitive)
        DB::statement("
            UPDATE detalle_pedido_bordado dpb
            JOIN logo l ON dpb.nombre_logo_aplicado = l.name AND l.deleted_at IS NULL
            SET dpb.logo_id = l.id
            WHERE dpb.nombre_logo_aplicado IS NOT NULL
              AND dpb.nombre_logo_aplicado != ''
        ");

        DB::statement("
            UPDATE detalle_cotizacion_bordado dcb
            JOIN logo l ON dcb.nombre_logo_aplicado = l.name AND l.deleted_at IS NULL
            SET dcb.logo_id = l.id
            WHERE dcb.nombre_logo_aplicado IS NOT NULL
              AND dcb.nombre_logo_aplicado != ''
        ");

        // 2b. Match parcial para casos conocidos (solo donde logo_id sigue NULL)
        $partialMatches = [
            'Los Caminos'  => 'Los Caminos Hacienda',
            'Paica'        => 'PAICA Alimentos',
        ];

        foreach ($partialMatches as $texto => $logoName) {
            $logoId = DB::table('logo')
                ->where('name', $logoName)
                ->whereNull('deleted_at')
                ->value('id');

            if ($logoId) {
                DB::table('detalle_pedido_bordado')
                    ->whereNull('logo_id')
                    ->where('nombre_logo_aplicado', $texto)
                    ->update(['logo_id' => $logoId]);

                DB::table('detalle_cotizacion_bordado')
                    ->whereNull('logo_id')
                    ->where('nombre_logo_aplicado', $texto)
                    ->update(['logo_id' => $logoId]);
            }
        }

        // ── 3. Eliminar nombre_logo de tablas padre (campo redundante) ──
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropColumn('nombre_logo');
        });

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropColumn('nombre_logo');
        });
    }

    public function down(): void
    {
        // Restaurar nombre_logo en tablas padre
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->string('nombre_logo', 191)->nullable()->after('lleva_bordado');
        });

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->string('nombre_logo', 191)->nullable()->after('lleva_bordado');
        });

        // Repoblar nombre_logo padre desde bordados (resumen)
        DB::statement("
            UPDATE detalle_pedido dp
            JOIN (
                SELECT detalle_pedido_id, GROUP_CONCAT(DISTINCT nombre_logo_aplicado SEPARATOR ', ') as logos
                FROM detalle_pedido_bordado
                WHERE nombre_logo_aplicado IS NOT NULL AND nombre_logo_aplicado != ''
                GROUP BY detalle_pedido_id
            ) sub ON dp.id = sub.detalle_pedido_id
            SET dp.nombre_logo = LEFT(sub.logos, 191)
        ");

        DB::statement("
            UPDATE detalle_cotizacion dc
            JOIN (
                SELECT detalle_cotizacion_id, GROUP_CONCAT(DISTINCT nombre_logo_aplicado SEPARATOR ', ') as logos
                FROM detalle_cotizacion_bordado
                WHERE nombre_logo_aplicado IS NOT NULL AND nombre_logo_aplicado != ''
                GROUP BY detalle_cotizacion_id
            ) sub ON dc.id = sub.detalle_cotizacion_id
            SET dc.nombre_logo = LEFT(sub.logos, 191)
        ");

        // Eliminar logo_id FK de tablas de bordado
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->dropForeign(['logo_id']);
            $table->dropColumn('logo_id');
        });

        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->dropForeign(['logo_id']);
            $table->dropColumn('logo_id');
        });
    }
};
