<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * BA-03 — Renombrar persona.estado_persona → estado_geografico
 * El campo almacena estado geográfico ('Portuguesa', 'Texas'),
 * no el estado activo/inactivo de la persona.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `persona` CHANGE COLUMN `estado_persona` `estado_geografico` VARCHAR(100) NULL DEFAULT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `persona` CHANGE COLUMN `estado_geografico` `estado_persona` VARCHAR(100) NULL DEFAULT NULL");
    }
};
