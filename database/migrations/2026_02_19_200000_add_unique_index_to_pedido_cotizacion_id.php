<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration {
    public function up(): void
    {
        // Verificar si hay cotizaciones duplicadas en pedidos
        $duplicados = DB::table('pedido')
            ->select('cotizacion_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('cotizacion_id')
            ->groupBy('cotizacion_id')
            ->having('total', '>', 1)
            ->get();

        if ($duplicados->isNotEmpty()) {
            foreach ($duplicados as $dup) {
                Log::warning("⚠️ Cotización #{$dup->cotizacion_id} tiene {$dup->total} pedidos asociados. Se debe resolver manualmente.");
            }
            // Mantener solo el pedido más reciente para cada cotización duplicada
            foreach ($duplicados as $dup) {
                $pedidoIds = DB::table('pedido')
                    ->where('cotizacion_id', $dup->cotizacion_id)
                    ->orderBy('id', 'desc')
                    ->pluck('id');

                // Desasociar todos excepto el más reciente
                $idsParaDesasociar = $pedidoIds->slice(1);
                if ($idsParaDesasociar->isNotEmpty()) {
                    DB::table('pedido')
                        ->whereIn('id', $idsParaDesasociar->toArray())
                        ->update(['cotizacion_id' => null]);
                    Log::warning("⚠️ Pedidos desasociados de cotización #{$dup->cotizacion_id}: " . $idsParaDesasociar->implode(', '));
                }
            }
        }

        // Eliminar la FK constraint y su índice, luego crear UNIQUE
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign(['cotizacion_id']);
        });

        Schema::table('pedido', function (Blueprint $table) {
            $table->unique('cotizacion_id', 'pedido_cotizacion_id_unique');
            $table->foreign('cotizacion_id')
                ->references('id')
                ->on('cotizacion')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            $table->dropForeign(['cotizacion_id']);
            $table->dropUnique('pedido_cotizacion_id_unique');
        });

        Schema::table('pedido', function (Blueprint $table) {
            $table->foreign('cotizacion_id')
                ->references('id')
                ->on('cotizacion')
                ->onDelete('set null');
        });
    }
};
