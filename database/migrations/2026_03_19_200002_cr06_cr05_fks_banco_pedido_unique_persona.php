<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CR-06: Agregar FK constraints faltantes en pedido.banco_transferencia_id y banco_pago_movil_id
 * CR-05: Cambiar UNIQUE de persona.documento_identidad a compuesto (tipo_documento, documento_identidad)
 */
return new class extends Migration
{
    public function up(): void
    {
        // CR-06: FKs de banco en pedido
        Schema::table('pedido', function (Blueprint $table) {
            $table->foreign('banco_transferencia_id')
                  ->references('id')->on('banco')
                  ->onDelete('set null');

            $table->foreign('banco_pago_movil_id')
                  ->references('id')->on('banco')
                  ->onDelete('set null');
        });

        // CR-05: UNIQUE compuesto tipo_documento + documento_identidad
        Schema::table('persona', function (Blueprint $table) {
            $table->dropUnique('persona_documento_identidad_unique');
            $table->unique(
                ['tipo_documento', 'documento_identidad'],
                'persona_tipo_doc_documento_unique'
            );
        });
    }

    public function down(): void
    {
        // Revertir CR-05
        Schema::table('persona', function (Blueprint $table) {
            $table->dropUnique('persona_tipo_doc_documento_unique');
            $table->unique('documento_identidad', 'persona_documento_identidad_unique');
        });

        // Revertir CR-06
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign(['banco_pago_movil_id']);
            $table->dropForeign(['banco_transferencia_id']);
        });
    }
};
