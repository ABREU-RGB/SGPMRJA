<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * CR-05 — orden_produccion.logo (texto libre) → logo_id FK a logos
 *
 * Mapeo de datos existentes:
 *  'Los Caminos'        → #3 'Los Caminos Hacienda' (match parcial)
 *  'Asoportuguesa Corp' → #2 (match exacto)
 *  'Logo UPTP'          → NULL (no existe en tabla logos)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columna logo_id
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->unsignedBigInteger('logo_id')->nullable()->after('logo');
        });

        // 2. Mapear datos existentes
        DB::statement("
            UPDATE orden_produccion op
            JOIN logos l ON l.name = op.logo
            SET op.logo_id = l.id
            WHERE op.logo IS NOT NULL AND op.logo != ''
        ");
        // Match parcial para 'Los Caminos' → 'Los Caminos Hacienda'
        DB::statement("
            UPDATE orden_produccion op
            JOIN logos l ON l.name LIKE CONCAT(op.logo, '%')
            SET op.logo_id = l.id
            WHERE op.logo IS NOT NULL AND op.logo != '' AND op.logo_id IS NULL
        ");

        // 3. Drop columna texto, agregar FK
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->foreign('logo_id')->references('id')->on('logos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropForeign(['logo_id']);
            $table->string('logo', 191)->nullable()->after('costo_estimado');
        });

        // Restaurar nombres de logo desde la tabla logos
        DB::statement("
            UPDATE orden_produccion op
            JOIN logos l ON l.id = op.logo_id
            SET op.logo = l.name
            WHERE op.logo_id IS NOT NULL
        ");

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropColumn('logo_id');
        });
    }
};
