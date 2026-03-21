<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * BA-05: Corregir ENUM 'Cancelado' → 'Cancelada' en cotizacion.estado
 * (femenino: "cotización cancelada")
 */
return new class extends Migration
{
    public function up(): void
    {
        // Primero ampliar el ENUM para incluir ambos valores temporalmente
        DB::statement("ALTER TABLE `cotizacion` MODIFY COLUMN `estado`
            ENUM('Pendiente','Aprobada','Cancelado','Cancelada','Convertida','Vencida')
            NOT NULL DEFAULT 'Pendiente'");

        // Migrar datos existentes
        DB::statement("UPDATE `cotizacion` SET `estado` = 'Cancelada' WHERE `estado` = 'Cancelado'");

        // Eliminar el valor viejo del ENUM
        DB::statement("ALTER TABLE `cotizacion` MODIFY COLUMN `estado`
            ENUM('Pendiente','Aprobada','Cancelada','Convertida','Vencida')
            NOT NULL DEFAULT 'Pendiente'");
    }

    public function down(): void
    {
        DB::statement("UPDATE `cotizacion` SET `estado` = 'Cancelado' WHERE `estado` = 'Cancelada'");

        DB::statement("ALTER TABLE `cotizacion` MODIFY COLUMN `estado`
            ENUM('Pendiente','Aprobada','Cancelado','Convertida','Vencida')
            NOT NULL DEFAULT 'Pendiente'");
    }
};
