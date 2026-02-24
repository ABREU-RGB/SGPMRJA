<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Models\DetallePedidoBordado;
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
     * Calcular el total del pedido.
     * Usa precio_unitario del request si está disponible (ej: viene de una cotización),
     * de lo contrario usa precio_base del catálogo.
     */
    private function calcularTotal(array $productos): float
    {
        $total = 0;
        foreach ($productos as $item) {
            if (!empty($item['precio_unitario'])) {
                $precio = (float) $item['precio_unitario'];
            } else {
                $producto = Producto::find($item['producto_id']);
                $precio = $producto->precio_base;

                if (!empty($item['lleva_bordado']) && is_array($item['bordados'] ?? null)) {
                    $precio += collect($item['bordados'])->sum(function ($bordado) {
                        $cantidad = max(1, (int) ($bordado['cantidad'] ?? 1));
                        return ((float) ($bordado['precio_aplicado'] ?? 0)) * $cantidad;
                    });
                }
            }
            $total += $precio * $item['cantidad'];
        }
        return $total;
    }

    /**
     * Crear los detalles (líneas) de un pedido.
     * Usa precio_unitario del request si disponible, sinó precio_base del catálogo.
     */
    private function crearDetalles(Pedido $pedido, array $productos): void
    {
        foreach ($productos as $item) {
            if (!empty($item['precio_unitario'])) {
                $precioUnitario = (float) $item['precio_unitario'];
            } else {
                $producto = Producto::find($item['producto_id']);
                $precioUnitario = $producto->precio_base;
            }

            $bordados = is_array($item['bordados'] ?? null) ? $item['bordados'] : [];

            $detalle = DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'] ?? null,
                'lleva_bordado' => $item['lleva_bordado'] ?? false,
                'nombre_logo' => $this->resolverNombreLogoDetalle($item, $bordados),
                'color' => $item['color'] ?? null,
                'talla' => $item['talla'] ?? null,
                'precio_unitario' => $precioUnitario,
            ]);

            foreach ($bordados as $index => $bordado) {
                DetallePedidoBordado::create([
                    'detalle_pedido_id' => $detalle->id,
                    'ubicacion_bordado_id' => $bordado['ubicacion_bordado_id'] ?? null,
                    'nombre_aplicado' => trim((string) ($bordado['nombre_aplicado'] ?? '')),
                    'nombre_logo_aplicado' => trim((string) ($bordado['nombre_logo'] ?? $item['nombre_logo'] ?? '')),
                    'es_personalizada' => (bool) ($bordado['es_personalizada'] ?? false),
                    'cantidad' => max(1, (int) ($bordado['cantidad'] ?? 1)),
                    'precio_aplicado' => (float) ($bordado['precio_aplicado'] ?? 0),
                    'orden' => (int) $index,
                ]);
            }
        }
    }

    private function resolverNombreLogoDetalle(array $item, array $bordados): ?string
    {
        $logos = collect($bordados)
            ->map(fn($bordado) => trim((string) ($bordado['nombre_logo'] ?? '')))
            ->filter()
            ->unique()
            ->values();

        if ($logos->isNotEmpty()) {
            return mb_substr($logos->implode(', '), 0, 100);
        }

        $legacy = trim((string) ($item['nombre_logo'] ?? ''));
        return $legacy !== '' ? mb_substr($legacy, 0, 100) : null;
    }
}
