<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            // Eliminar FKs de banco antes de eliminar columnas
            // Usamos try/catch porque el nombre del FK puede variar
            try {
                $table->dropForeign(['banco_transferencia_id']);
            } catch (\Exception $e) {
                // FK ya no existe o tiene otro nombre
            }
            try {
                $table->dropForeign(['banco_pago_movil_id']);
            } catch (\Exception $e) {
                // FK ya no existe o tiene otro nombre
            }

            // Eliminar las 7 columnas flat de pago
            $table->dropColumn([
                'efectivo_pagado',
                'transferencia_pagado',
                'pago_movil_pagado',
                'referencia_transferencia',
                'referencia_pago_movil',
                'banco_transferencia_id',
                'banco_pago_movil_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->boolean('efectivo_pagado')->default(false)->after('abono');
            $table->boolean('transferencia_pagado')->default(false)->after('efectivo_pagado');
            $table->boolean('pago_movil_pagado')->default(false)->after('transferencia_pagado');
            $table->string('referencia_transferencia')->nullable()->after('pago_movil_pagado');
            $table->string('referencia_pago_movil')->nullable()->after('referencia_transferencia');
            $table->unsignedBigInteger('banco_transferencia_id')->nullable()->after('referencia_pago_movil');
            $table->unsignedBigInteger('banco_pago_movil_id')->nullable()->after('banco_transferencia_id');

            $table->foreign('banco_transferencia_id')->references('id')->on('banco')->nullOnDelete();
            $table->foreign('banco_pago_movil_id')->references('id')->on('banco')->nullOnDelete();
        });
    }
};
