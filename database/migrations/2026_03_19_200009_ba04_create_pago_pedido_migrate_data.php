<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * BA-04: Normalizar campos de pago flat en pedido → tabla pago_pedido
 *
 * Fase 1: Crear tabla + migrar datos históricos.
 * Las 7 columnas viejas se mantienen temporalmente hasta la Fase 4.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Crear tabla pago_pedido ──────────────────────────────
        Schema::create('pago_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')
                  ->constrained('pedido')
                  ->onDelete('cascade');
            $table->enum('metodo', ['efectivo', 'transferencia', 'pago_movil']);
            $table->decimal('monto', 10, 2)->default(0.00);
            $table->foreignId('banco_id')
                  ->nullable()
                  ->constrained('banco')
                  ->onDelete('set null');
            $table->string('referencia', 255)->nullable();
            $table->timestamps();

            $table->index(['pedido_id', 'metodo'], 'idx_pago_pedido_metodo');
        });

        // ── Data migration ──────────────────────────────────────
        $pedidos = DB::table('pedido')
            ->where(function ($q) {
                $q->where('abono', '>', 0)
                  ->orWhere('efectivo_pagado', true)
                  ->orWhere('transferencia_pagado', true)
                  ->orWhere('pago_movil_pagado', true);
            })
            ->get();

        $now = now();

        foreach ($pedidos as $pedido) {
            $metodos = [];

            if ($pedido->transferencia_pagado) {
                $metodos[] = [
                    'pedido_id'  => $pedido->id,
                    'metodo'     => 'transferencia',
                    'monto'      => 0,
                    'banco_id'   => $pedido->banco_transferencia_id,
                    'referencia' => $pedido->referencia_transferencia,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($pedido->pago_movil_pagado) {
                $metodos[] = [
                    'pedido_id'  => $pedido->id,
                    'metodo'     => 'pago_movil',
                    'monto'      => 0,
                    'banco_id'   => $pedido->banco_pago_movil_id,
                    'referencia' => $pedido->referencia_pago_movil,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($pedido->efectivo_pagado) {
                $metodos[] = [
                    'pedido_id'  => $pedido->id,
                    'metodo'     => 'efectivo',
                    'monto'      => 0,
                    'banco_id'   => null,
                    'referencia' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Asignar el monto del abono al primer método
            // Prioridad: transferencia > pago_movil > efectivo
            if (!empty($metodos) && $pedido->abono > 0) {
                $metodos[0]['monto'] = $pedido->abono;
            }

            if (!empty($metodos)) {
                DB::table('pago_pedido')->insert($metodos);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_pedido');
    }
};
