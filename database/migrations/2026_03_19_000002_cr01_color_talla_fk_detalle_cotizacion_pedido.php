<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columnas FK (nullable) en ambas tablas
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('nombre_logo');
            $table->unsignedBigInteger('talla_id')->nullable()->after('color_id');
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('nombre_logo');
            $table->unsignedBigInteger('talla_id')->nullable()->after('color_id');
        });

        // 2. Migrar datos existentes: texto → id (best-effort, NULL si no hay match exacto)
        DB::statement("
            UPDATE detalle_cotizacion dc
            JOIN colores c ON c.nombre = dc.color
            SET dc.color_id = c.id
            WHERE dc.color IS NOT NULL AND dc.color != ''
        ");

        DB::statement("
            UPDATE detalle_cotizacion dc
            JOIN tallas t ON t.nombre = dc.talla
            SET dc.talla_id = t.id
            WHERE dc.talla IS NOT NULL AND dc.talla != ''
        ");

        DB::statement("
            UPDATE detalle_pedido dp
            JOIN colores c ON c.nombre = dp.color
            SET dp.color_id = c.id
            WHERE dp.color IS NOT NULL AND dp.color != ''
        ");

        DB::statement("
            UPDATE detalle_pedido dp
            JOIN tallas t ON t.nombre = dp.talla
            SET dp.talla_id = t.id
            WHERE dp.talla IS NOT NULL AND dp.talla != ''
        ");

        // 3. Eliminar columnas de texto antiguas
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropColumn(['color', 'talla']);
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropColumn(['color', 'talla']);
        });

        // 4. Agregar FK constraints
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreign('color_id')->references('id')->on('colores')->nullOnDelete();
            $table->foreign('talla_id')->references('id')->on('tallas')->nullOnDelete();
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('color_id')->references('id')->on('colores')->nullOnDelete();
            $table->foreign('talla_id')->references('id')->on('tallas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropForeign(['talla_id']);
            $table->dropColumn(['color_id', 'talla_id']);
            $table->string('color', 50)->nullable();
            $table->string('talla', 191)->nullable();
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropForeign(['talla_id']);
            $table->dropColumn(['color_id', 'talla_id']);
            $table->string('color', 50)->nullable();
            $table->string('talla', 50)->nullable();
        });
    }
};
