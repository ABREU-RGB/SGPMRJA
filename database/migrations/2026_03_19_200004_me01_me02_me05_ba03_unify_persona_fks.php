<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ME-01: Unificar user.persona_id y empleado.persona_id de CASCADE → RESTRICT
 * ME-02: Cambiar proveedor.persona_id de SET NULL → RESTRICT
 * ME-05: Hacer explícito ON DELETE RESTRICT en orden_produccion.created_by
 * BA-03: Hacer explícito ON DELETE RESTRICT en produccion_diaria.empleado_id
 */
return new class extends Migration
{
    public function up(): void
    {
        // ME-01: user.persona_id CASCADE → RESTRICT
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('user_persona_id_foreign');
            $table->foreign('persona_id', 'user_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('restrict');
        });

        // ME-01: empleado.persona_id CASCADE → RESTRICT
        Schema::table('empleado', function (Blueprint $table) {
            $table->dropForeign('empleado_persona_id_foreign');
            $table->foreign('persona_id', 'empleado_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('restrict');
        });

        // ME-02: proveedor.persona_id SET NULL → RESTRICT
        Schema::table('proveedor', function (Blueprint $table) {
            $table->dropForeign('proveedor_persona_id_foreign');
            $table->foreign('persona_id', 'proveedor_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('restrict');
        });

        // ME-05: orden_produccion.created_by — hacer explícito RESTRICT
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropForeign('ordenes_produccion_created_by_foreign');
            $table->foreign('created_by', 'ordenes_produccion_created_by_foreign')
                  ->references('id')->on('user')
                  ->onDelete('restrict');
        });

        // BA-03: produccion_diaria.empleado_id — hacer explícito RESTRICT
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_empleado_id_foreign');
            $table->foreign('empleado_id', 'produccion_diaria_empleado_id_foreign')
                  ->references('id')->on('empleado')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_empleado_id_foreign');
            $table->foreign('empleado_id', 'produccion_diaria_empleado_id_foreign')
                  ->references('id')->on('empleado');
        });

        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropForeign('ordenes_produccion_created_by_foreign');
            $table->foreign('created_by', 'ordenes_produccion_created_by_foreign')
                  ->references('id')->on('user');
        });

        Schema::table('proveedor', function (Blueprint $table) {
            $table->dropForeign('proveedor_persona_id_foreign');
            $table->foreign('persona_id', 'proveedor_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('set null');
        });

        Schema::table('empleado', function (Blueprint $table) {
            $table->dropForeign('empleado_persona_id_foreign');
            $table->foreign('persona_id', 'empleado_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('cascade');
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('user_persona_id_foreign');
            $table->foreign('persona_id', 'user_persona_id_foreign')
                  ->references('id')->on('persona')
                  ->onDelete('cascade');
        });
    }
};
