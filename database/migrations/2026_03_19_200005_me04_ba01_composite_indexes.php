<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ME-04: Índices compuestos para queries de alto tráfico
 * BA-01: Eliminar índice redundante en password_resets
 */
return new class extends Migration
{
    public function up(): void
    {
        // ME-04: Índices compuestos
        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->index(['insumo_id', 'created_at'], 'idx_mov_insumo_created');
        });

        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->index(['orden_id', 'fecha_produccion'], 'idx_prod_diaria_orden_fecha');
        });

        Schema::table('pedido', function (Blueprint $table) {
            $table->index(['cliente_id', 'estado'], 'idx_pedido_cliente_estado');
        });

        Schema::table('cotizacion', function (Blueprint $table) {
            $table->index(['cliente_id', 'estado'], 'idx_cotizacion_cliente_estado');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->index(['estado', 'fecha_fin_estimada'], 'idx_orden_estado_fecha_fin');
        });

        // BA-01: Eliminar índice redundante (PK en email ya cubre)
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropIndex('password_resets_email_index');
        });
    }

    public function down(): void
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->index('email', 'password_resets_email_index');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropIndex('idx_orden_estado_fecha_fin');
        });

        Schema::table('cotizacion', function (Blueprint $table) {
            $table->dropIndex('idx_cotizacion_cliente_estado');
        });

        Schema::table('pedido', function (Blueprint $table) {
            $table->dropIndex('idx_pedido_cliente_estado');
        });

        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropIndex('idx_prod_diaria_orden_fecha');
        });

        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropIndex('idx_mov_insumo_created');
        });
    }
};
