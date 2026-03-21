<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CR-02: detalle_cotizacion.producto_id y detalle_pedido.producto_id CASCADE → RESTRICT
 * CR-03: movimiento_insumo.insumo_id CASCADE → RESTRICT
 * ME-03: detalle_orden_insumo.insumo_id CASCADE → RESTRICT
 *
 * Protege historial comercial y trazabilidad de inventario contra borrado destructivo.
 */
return new class extends Migration
{
    public function up(): void
    {
        // CR-02: detalle_cotizacion.producto_id
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizaciones_producto_id_foreign');
            $table->foreign('producto_id', 'detalle_cotizaciones_producto_id_foreign')
                  ->references('id')->on('producto')
                  ->onDelete('restrict');
        });

        // CR-02: detalle_pedido.producto_id
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedidos_producto_id_foreign');
            $table->foreign('producto_id', 'detalle_pedidos_producto_id_foreign')
                  ->references('id')->on('producto')
                  ->onDelete('restrict');
        });

        // CR-03: movimiento_insumo.insumo_id
        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropForeign('movimientos_insumos_insumo_id_foreign');
            $table->foreign('insumo_id', 'movimientos_insumos_insumo_id_foreign')
                  ->references('id')->on('insumo')
                  ->onDelete('restrict');
        });

        // ME-03: detalle_orden_insumo.insumo_id
        Schema::table('detalle_orden_insumo', function (Blueprint $table) {
            $table->dropForeign('detalle_orden_insumos_insumo_id_foreign');
            $table->foreign('insumo_id', 'detalle_orden_insumos_insumo_id_foreign')
                  ->references('id')->on('insumo')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_orden_insumo', function (Blueprint $table) {
            $table->dropForeign('detalle_orden_insumos_insumo_id_foreign');
            $table->foreign('insumo_id', 'detalle_orden_insumos_insumo_id_foreign')
                  ->references('id')->on('insumo')
                  ->onDelete('cascade');
        });

        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropForeign('movimientos_insumos_insumo_id_foreign');
            $table->foreign('insumo_id', 'movimientos_insumos_insumo_id_foreign')
                  ->references('id')->on('insumo')
                  ->onDelete('cascade');
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedidos_producto_id_foreign');
            $table->foreign('producto_id', 'detalle_pedidos_producto_id_foreign')
                  ->references('id')->on('producto')
                  ->onDelete('cascade');
        });

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizaciones_producto_id_foreign');
            $table->foreign('producto_id', 'detalle_cotizaciones_producto_id_foreign')
                  ->references('id')->on('producto')
                  ->onDelete('cascade');
        });
    }
};
