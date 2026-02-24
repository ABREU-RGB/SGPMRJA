<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use App\Models\DetallePedidoBordado;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoService
{
    public function __construct(
        private BordadoPricingService $bordadoPricingService
    ) {
    }

    /**
     * Crear un pedido con sus detalles.
     */
    public function crear(array $data): Pedido
    {
        $pedido = DB::transaction(function () use ($data) {
            $cotizacion = $this->validarCotizacionParaCrearPedido($data['cotizacion_id'] ?? null);
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

            if ($cotizacion) {
                $cotizacion->update(['estado' => 'Convertida']);
            }

            return $pedido;
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
                'banco_transferencia_id' => $data['banco_transferencia_id'] ?? null,
                'banco_pago_movil_id' => $data['banco_pago_movil_id'] ?? null,
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
            $producto = Producto::find($item['producto_id']);
            $precioBase = isset($item['precio_unitario'])
                ? (float) $item['precio_unitario']
                : (float) ($producto->precio_base ?? 0);

            $bordados = $this->bordadoPricingService->normalizeBordados($item);
            $precioUnitarioFinal = $this->bordadoPricingService->calcularPrecioUnitarioFinal($precioBase, $bordados);

            $total += $precioUnitarioFinal * (int) $item['cantidad'];
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
            $producto = Producto::find($item['producto_id']);
            $precioBase = isset($item['precio_unitario'])
                ? (float) $item['precio_unitario']
                : (float) ($producto->precio_base ?? 0);

            $bordados = $this->bordadoPricingService->normalizeBordados($item);
            $precioUnitarioFinal = $this->bordadoPricingService->calcularPrecioUnitarioFinal($precioBase, $bordados);

            $detalle = DetallePedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'] ?? null,
                'lleva_bordado' => $item['lleva_bordado'] ?? false,
                'nombre_logo' => $this->bordadoPricingService->resolverNombreLogoDetalle($item, $bordados),
                'color' => $item['color'] ?? null,
                'talla' => $item['talla'] ?? null,
                'precio_unitario' => $precioUnitarioFinal,
            ]);

            foreach ($bordados as $bordado) {
                DetallePedidoBordado::create([
                    'detalle_pedido_id' => $detalle->id,
                    'ubicacion_bordado_id' => $bordado['ubicacion_bordado_id'] ?? null,
                    'nombre_aplicado' => trim((string) ($bordado['nombre_aplicado'] ?? '')),
                    'nombre_logo_aplicado' => trim((string) ($bordado['nombre_logo'] ?? $item['nombre_logo'] ?? '')),
                    'es_personalizada' => (bool) ($bordado['es_personalizada'] ?? false),
                    'cantidad' => max(1, (int) ($bordado['cantidad'] ?? 1)),
                    'precio_aplicado' => (float) ($bordado['precio_aplicado'] ?? 0),
                    'orden' => (int) ($bordado['orden'] ?? 0),
                ]);
            }
        }
    }

    private function validarCotizacionParaCrearPedido(?int $cotizacionId): ?Cotizacion
    {
        if (!$cotizacionId) {
            return null;
        }

        $cotizacion = Cotizacion::lockForUpdate()->findOrFail($cotizacionId);

        if ($cotizacion->estado !== 'Aprobada') {
            throw new \InvalidArgumentException('La cotización seleccionada no está aprobada para crear pedido.');
        }

        $pedidoExistente = Pedido::where('cotizacion_id', $cotizacionId)->lockForUpdate()->exists();
        if ($pedidoExistente) {
            throw new \InvalidArgumentException('Esta cotización ya tiene un pedido asociado.');
        }

        return $cotizacion;
    }
}
