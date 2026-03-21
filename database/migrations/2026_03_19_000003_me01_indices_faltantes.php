<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->index('estado', 'idx_pedido_estado');
            $table->index('fecha_pedido', 'idx_pedido_fecha');
        });

        Schema::table('cotizacion', function (Blueprint $table) {
            $table->index('estado', 'idx_cotizacion_estado');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->index('estado', 'idx_orden_estado');
            $table->index('fecha_fin_estimada', 'idx_orden_fecha_fin');
        });

        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->index('created_at', 'idx_mov_created_at');
        });

        Schema::table('tasa_cambio', function (Blueprint $table) {
            $table->index('fecha_bcv', 'idx_tasa_fecha');
        });

        Schema::table('insumo', function (Blueprint $table) {
            $table->index('stock_actual', 'idx_insumo_stock');
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropIndex('idx_pedido_estado');
            $table->dropIndex('idx_pedido_fecha');
        });

        Schema::table('cotizacion', function (Blueprint $table) {
            $table->dropIndex('idx_cotizacion_estado');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropIndex('idx_orden_estado');
            $table->dropIndex('idx_orden_fecha_fin');
        });

        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropIndex('idx_mov_created_at');
        });

        Schema::table('tasa_cambio', function (Blueprint $table) {
            $table->dropIndex('idx_tasa_fecha');
        });

        Schema::table('insumo', function (Blueprint $table) {
            $table->dropIndex('idx_insumo_stock');
        });
    }
};
