<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CR-06 — movimiento_insumo.created_by: hacer ON DELETE RESTRICT explícito
 * InnoDB asume NO ACTION (equivalente a RESTRICT) pero la auditoría
 * requiere que sea explícito para claridad.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropForeign('movimientos_insumos_created_by_foreign');
            $table->foreign('created_by', 'movimientos_insumos_created_by_foreign')
                ->references('id')->on('user')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('movimiento_insumo', function (Blueprint $table) {
            $table->dropForeign('movimientos_insumos_created_by_foreign');
            $table->foreign('created_by', 'movimientos_insumos_created_by_foreign')
                ->references('id')->on('user');
        });
    }
};
