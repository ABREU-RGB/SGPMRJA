<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Corregir dato sucio: 'Aprobado' → 'Aprobada' (1 registro)
        DB::table('cotizacion')->where('estado', 'Aprobado')->update(['estado' => 'Aprobada']);

        DB::statement("ALTER TABLE pedido
            MODIFY COLUMN estado ENUM('Pendiente','Procesando','Completado','Cancelado') NOT NULL DEFAULT 'Pendiente',
            MODIFY COLUMN prioridad ENUM('Normal','Alta','Urgente') NOT NULL DEFAULT 'Normal'
        ");

        DB::statement("ALTER TABLE cotizacion
            MODIFY COLUMN estado ENUM('Pendiente','Aprobada','Cancelado','Convertida','Vencida') NOT NULL DEFAULT 'Pendiente',
            MODIFY COLUMN prioridad ENUM('Normal','Alta','Urgente') NOT NULL DEFAULT 'Normal'
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedido
            MODIFY COLUMN estado VARCHAR(50) NOT NULL DEFAULT 'Pendiente',
            MODIFY COLUMN prioridad VARCHAR(50) NOT NULL DEFAULT 'Normal'
        ");

        DB::statement("ALTER TABLE cotizacion
            MODIFY COLUMN estado VARCHAR(50) NOT NULL DEFAULT 'Pendiente',
            MODIFY COLUMN prioridad VARCHAR(50) NOT NULL DEFAULT 'Normal'
        ");
    }
};
