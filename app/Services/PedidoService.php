<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoService
{
    /**
     * Crear un pedido con sus detalles.
     */
    public function crear(array $data): Pedido
    {
        $pedido = null;

        DB::transaction(function () use ($data, &$pedido) {
            $total_pedido = $this->calcularTotal($data['productos']);

            $pedido = Pedido::create([
                'cotizacion_id' => $data['cotizacion_id'] ?? null,
                'cliente_id' => $data['cliente_id'],
                'fecha_pedido' => $data['fecha_pedido'],
                'fecha_entrega_estimada' => $data['fecha_entrega_estimada'] ?? null,
                'estado' => 'Pendiente',
                'total' => $total_pedido,
                'user_id' => Auth::id(),
                'abono' => $data['abono'],
                'efectivo_pagado' => $data['efectivo_pagado'] ?? false,
                'transferencia_pagado' => $data['transferencia_pagado'] ?? false,
                'pago_movil_pagado' => $data['pago_movil_pagado'] ?? false,
                'referencia_transferencia' => $data['referencia_transferencia'] ?? null,
                'referencia_pago_movil' => $data['referencia_pago_movil'] ?? null,
                'banco_id' => $data['banco_id'] ?? null,
                'banco_transferencia_id' => $data['banco_transferencia_id'] ?? null,
                'banco_pago_movil_id' => $data['banco_pago_movil_id'] ?? null,
                'prioridad' => $data['prioridad'],
            ]);

            $this->crearDetalles($pedido, $data['productos']);

            // Si viene de cotización aprobada, marcarla como convertida
            if (!empty($data['cotizacion_id'])) {
                $cotizacion = Cotizacion::find($data['cotizacion_id']);
                if ($cotizacion && $cotizacion->estado === 'Aprobada') {
                    $cotizacion->update(['estado' => 'Convertida']);
                }
            }
        });

        Log::info('Pedido creado', [
            'pedido_id' => $pedido->id,
            'cliente_id' => $pedido->cliente_id,
            'total' => $pedido->total,
            'cotizacion_id' => $pedido->cotizacion_id,
            'user_id' => Auth::id(),
        ]);

        return $pedido;
    }

    /**
     * Actualizar un pedido existente y sincronizar sus detalles.
     */
    public function actualizar(Pedido $pedido, array $data): void
    {
        DB::transaction(function () use ($data, $pedido) {
            // Eliminar detalles anteriores
            $pedido->productos()->delete();

            $total_pedido = $this->calcularTotal($data['productos']);

            $pedido->update([
                'cliente_id' => $data['cliente_id'],
                'fecha_pedido' => $data['fecha_pedido'],
                'fecha_entrega_estimada' => $data['fecha_entrega_estimada'] ?? null,
                'estado' => $data['estado'],
                'total' => $total_pedido,
                'abono' => $data['abono'],
                'efectivo_pagado' => $data['efectivo_pagado'] ?? false,
                'transferencia_pagado' => $data['transferencia_pagado'] ?? false,
                'pago_movil_pagado' => $data['pago_movil_pagado'] ?? false,
                'referencia_transferencia' => $data['referencia_transferencia'] ?? null,
                'referencia_pago_movil' => $data['referencia_pago_movil'] ?? null,
                'banco_id' => $data['banco_id'] ?? null,
                'prioridad' => $data['prioridad'],
            ]);

            $this->crearDetalles($pedido, $data['productos']);
        });

        Log::info('Pedido actualizado', [
            'pedido_id' => $pedido->id,
            'estado' => $data['estado'],
            'total' => $pedido->fresh()->total,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Calcular el total del pedido basado en precio base × cantidad.
     */
    private function calcularTotal(array $productos): float
    {
        $total = 0;
        foreach ($productos as $item) {
            $producto = Producto::find($item['producto_id']);
            $total += $producto->precio_base * $item['cantidad'];
        }
        return $total;
    }

    /**
     * Crear los detalles (líneas) de un pedido.
     */
    private function crearDetalles(Pedido $pedido, array $productos): void
    {
        foreach ($productos as $item) {
            $producto = Producto::find($item['producto_id']);
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'] ?? null,
                'lleva_bordado' => $item['lleva_bordado'] ?? false,
                'nombre_logo' => $item['nombre_logo'] ?? null,
                'ubicacion_logo' => $item['ubicacion_logo'] ?? null,
                'cantidad_logo' => $item['cantidad_logo'] ?? null,
                'color' => $item['color'] ?? null,
                'talla' => $item['talla'] ?? null,
                'precio_unitario' => $producto->precio_base,
            ]);
        }
    }
}
