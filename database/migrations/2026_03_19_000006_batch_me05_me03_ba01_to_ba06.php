<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Batch de correcciones menores de auditoría:
 *
 * ME-05 — Drop pedido.banco_id (campo legacy muerto)
 * ME-03 — Drop tipo_producto.contador (dato derivado 3NF)
 * BA-01 — Renombrar índice produccion_diaria_operario_id_foreign
 * BA-02 — insumo.tipo ENUM 'Botón' → 'Boton'
 * BA-04 — user.avatar TEXT → VARCHAR(255)
 * BA-05 — password_resets: agregar PK compuesto
 * BA-06 — banco.nombre VARCHAR(191) → VARCHAR(100)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ME-05: Drop legacy banco_id de pedido
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign('pedidos_banco_id_foreign');
            $table->dropColumn('banco_id');
        });

        // ME-03: Drop tipo_producto.contador
        Schema::table('tipo_producto', function (Blueprint $table) {
            $table->dropColumn('contador');
        });

        // BA-01: Renombrar índice operario → empleado
        // El índice es usado por FK produccion_diaria_empleado_id_foreign,
        // hay que drop FK → drop index → recrear index → recrear FK
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_empleado_id_foreign');
        });
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropIndex('produccion_diaria_operario_id_foreign');
            $table->index('empleado_id', 'produccion_diaria_empleado_id_index');
            $table->foreign('empleado_id', 'produccion_diaria_empleado_id_foreign')
                ->references('id')->on('empleado');
        });

        // BA-02: Quitar tilde de 'Botón' en insumo.tipo
        DB::statement("UPDATE insumo SET tipo = 'Boton' WHERE tipo = 'Botón'");
        DB::statement("ALTER TABLE insumo MODIFY COLUMN tipo ENUM('Tela','Hilo','Boton','Cierre','Etiqueta','Otro') NOT NULL");

        // BA-04: user.avatar TEXT → VARCHAR(255)
        DB::statement("ALTER TABLE `user` MODIFY COLUMN `avatar` VARCHAR(255) NULL DEFAULT NULL");

        // BA-05: password_resets — agregar PK
        Schema::table('password_resets', function (Blueprint $table) {
            $table->primary('email');
        });

        // BA-06: banco.nombre VARCHAR(191) → VARCHAR(100)
        DB::statement("ALTER TABLE `banco` MODIFY COLUMN `nombre` VARCHAR(100) NOT NULL");
    }

    public function down(): void
    {
        // BA-06 revert
        DB::statement("ALTER TABLE `banco` MODIFY COLUMN `nombre` VARCHAR(191) NOT NULL");

        // BA-05 revert
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropPrimary('email');
        });

        // BA-04 revert
        DB::statement("ALTER TABLE `user` MODIFY COLUMN `avatar` TEXT NULL DEFAULT NULL");

        // BA-02 revert
        DB::statement("ALTER TABLE insumo MODIFY COLUMN tipo ENUM('Tela','Hilo','Botón','Cierre','Etiqueta','Otro') NOT NULL");
        DB::statement("UPDATE insumo SET tipo = 'Botón' WHERE tipo = 'Boton'");

        // BA-01 revert
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_empleado_id_foreign');
            $table->dropIndex('produccion_diaria_empleado_id_index');
            $table->index('empleado_id', 'produccion_diaria_operario_id_foreign');
            $table->foreign('empleado_id', 'produccion_diaria_empleado_id_foreign')
                ->references('id')->on('empleado');
        });

        // ME-03 revert
        Schema::table('tipo_producto', function (Blueprint $table) {
            $table->integer('contador')->default(0)->after('descripcion');
        });

        // ME-05 revert
        Schema::table('pedido', function (Blueprint $table) {
            $table->unsignedBigInteger('banco_id')->nullable()->after('referencia_pago_movil');
            $table->foreign('banco_id', 'pedidos_banco_id_foreign')->references('id')->on('banco')->nullOnDelete();
        });
    }
};
