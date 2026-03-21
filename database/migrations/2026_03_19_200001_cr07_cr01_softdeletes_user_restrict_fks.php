<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CR-07: Agregar SoftDeletes a tabla user (columna deleted_at)
 * CR-01: Cambiar ON DELETE CASCADE → RESTRICT en cotizacion.user_id y pedido.user_id
 *
 * Amenaza #1 del schema: borrar un usuario destruía en cascada todas
 * sus cotizaciones y pedidos. RESTRICT bloquea el borrado si tiene registros asociados.
 */
return new class extends Migration
{
    public function up(): void
    {
        // CR-07: SoftDeletes en user
        Schema::table('user', function (Blueprint $table) {
            $table->softDeletes();
        });

        // CR-01: cotizacion.user_id CASCADE → RESTRICT
        Schema::table('cotizacion', function (Blueprint $table) {
            $table->dropForeign('cotizaciones_user_id_foreign');
            $table->foreign('user_id')
                  ->references('id')->on('user')
                  ->onDelete('restrict');
        });

        // CR-01: pedido.user_id CASCADE → RESTRICT
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign('pedidos_user_id_foreign');
            $table->foreign('user_id')
                  ->references('id')->on('user')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        // Revertir pedido.user_id → CASCADE
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id', 'pedidos_user_id_foreign')
                  ->references('id')->on('user')
                  ->onDelete('cascade');
        });

        // Revertir cotizacion.user_id → CASCADE
        Schema::table('cotizacion', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id', 'cotizaciones_user_id_foreign')
                  ->references('id')->on('user')
                  ->onDelete('cascade');
        });

        // Revertir SoftDeletes
        Schema::table('user', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
