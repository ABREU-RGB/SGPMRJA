<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ME-06: Renombrar tablas plurales a singular para consistencia con el resto del schema.
 *
 * colores → color (2 FKs)
 * tallas  → talla (2 FKs)
 * logos   → logo  (1 FK)
 * bordado_ubicaciones → bordado_ubicacion (2 FKs)
 *
 * Estrategia: drop FK → rename table → recrear FK apuntando a tabla nueva.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── colores → color ──────────────────────────────────────
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_color_id_foreign');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_color_id_foreign');
        });

        Schema::rename('colores', 'color');

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreign('color_id', 'detalle_cotizacion_color_id_foreign')
                  ->references('id')->on('color')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('color_id', 'detalle_pedido_color_id_foreign')
                  ->references('id')->on('color')
                  ->onDelete('set null');
        });

        // ── tallas → talla ──────────────────────────────────────
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_talla_id_foreign');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_talla_id_foreign');
        });

        Schema::rename('tallas', 'talla');

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreign('talla_id', 'detalle_cotizacion_talla_id_foreign')
                  ->references('id')->on('talla')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('talla_id', 'detalle_pedido_talla_id_foreign')
                  ->references('id')->on('talla')
                  ->onDelete('set null');
        });

        // ── logos → logo ────────────────────────────────────────
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropForeign('orden_produccion_logo_id_foreign');
        });

        Schema::rename('logos', 'logo');

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->foreign('logo_id', 'orden_produccion_logo_id_foreign')
                  ->references('id')->on('logo')
                  ->onDelete('set null');
        });

        // ── bordado_ubicaciones → bordado_ubicacion ─────────────
        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_bordado_ubicacion_bordado_id_foreign');
        });
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_bordado_ubicacion_bordado_id_foreign');
        });

        Schema::rename('bordado_ubicaciones', 'bordado_ubicacion');

        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->foreign('ubicacion_bordado_id', 'detalle_cotizacion_bordado_ubicacion_bordado_id_foreign')
                  ->references('id')->on('bordado_ubicacion')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->foreign('ubicacion_bordado_id', 'detalle_pedido_bordado_ubicacion_bordado_id_foreign')
                  ->references('id')->on('bordado_ubicacion')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // ── bordado_ubicacion → bordado_ubicaciones ─────────────
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_bordado_ubicacion_bordado_id_foreign');
        });
        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_bordado_ubicacion_bordado_id_foreign');
        });

        Schema::rename('bordado_ubicacion', 'bordado_ubicaciones');

        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->foreign('ubicacion_bordado_id', 'detalle_cotizacion_bordado_ubicacion_bordado_id_foreign')
                  ->references('id')->on('bordado_ubicaciones')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->foreign('ubicacion_bordado_id', 'detalle_pedido_bordado_ubicacion_bordado_id_foreign')
                  ->references('id')->on('bordado_ubicaciones')
                  ->onDelete('set null');
        });

        // ── logo → logos ────────────────────────────────────────
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropForeign('orden_produccion_logo_id_foreign');
        });

        Schema::rename('logo', 'logos');

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->foreign('logo_id', 'orden_produccion_logo_id_foreign')
                  ->references('id')->on('logos')
                  ->onDelete('set null');
        });

        // ── talla → tallas ──────────────────────────────────────
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_talla_id_foreign');
        });
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_talla_id_foreign');
        });

        Schema::rename('talla', 'tallas');

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreign('talla_id', 'detalle_cotizacion_talla_id_foreign')
                  ->references('id')->on('tallas')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('talla_id', 'detalle_pedido_talla_id_foreign')
                  ->references('id')->on('tallas')
                  ->onDelete('set null');
        });

        // ── color → colores ─────────────────────────────────────
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropForeign('detalle_pedido_color_id_foreign');
        });
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropForeign('detalle_cotizacion_color_id_foreign');
        });

        Schema::rename('color', 'colores');

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->foreign('color_id', 'detalle_cotizacion_color_id_foreign')
                  ->references('id')->on('colores')
                  ->onDelete('set null');
        });
        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->foreign('color_id', 'detalle_pedido_color_id_foreign')
                  ->references('id')->on('colores')
                  ->onDelete('set null');
        });
    }
};
