<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CotizacionService
{
    /**
     * Crear una nueva cotización con sus detalles.
     */
    public function crear(array $data): Cotizacion
    {
        $cotizacion = null;

        DB::transaction(function () use ($data, &$cotizacion) {
            $total = $this->calcularTotal($data['productos']);

            $cotizacion = Cotizacion::create([
                'cliente_id' => $data['cliente_id'],
                'fecha_cotizacion' => $data['fecha_cotizacion'],
                'fecha_validez' => $data['fecha_validez'] ?? null,
                'estado' => 'Pendiente',
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            $this->crearDetalles($cotizacion, $data['productos']);
        });

        Log::info('Cotización creada', [
            'cotizacion_id' => $cotizacion->id,
            'cliente_id' => $cotizacion->cliente_id,
            'total' => $cotizacion->total,
            'user_id' => Auth::id(),
        ]);

        return $cotizacion;
    }

    /**
     * Actualizar una cotización existente con sus detalles.
     */
    public function actualizar(Cotizacion $cotizacion, array $data): void
    {
        DB::transaction(function () use ($cotizacion, $data) {
            // Eliminar detalles existentes
            $cotizacion->productos()->delete();

            $total = $this->calcularTotal($data['productos']);

            $cotizacion->update([
                'cliente_id' => $data['cliente_id'],
                'fecha_cotizacion' => $data['fecha_cotizacion'],
                'fecha_validez' => $data['fecha_validez'] ?? null,
                'estado' => $data['estado'],
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            $this->crearDetalles($cotizacion, $data['productos']);
        });

        Log::info('Cotización actualizada', [
            'cotizacion_id' => $cotizacion->id,
            'total' => $cotizacion->total,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Convertir una cotización aprobada a pedido.
     *
     * @return Pedido El pedido creado
     * @throws \Exception Si la cotización no puede ser convertida
     */
    public function convertirAPedido(Cotizacion $cotizacion): Pedido
    {
        return DB::transaction(function () use ($cotizacion) {
            // 1. Re-obtener la cotización con bloqueo pesimista (SELECT ... FOR UPDATE)
            $cotizacion = Cotizacion::lockForUpdate()->findOrFail($cotizacion->id);

            // 2. Validar estado DENTRO del bloqueo (previene race conditions)
            if ($cotizacion->estado !== 'Aprobada') {
                throw new \InvalidArgumentException('Solo se pueden convertir cotizaciones con estado Aprobada.');
            }

            // 3. Verificar que no exista ya un pedido asociado (doble protección + índice único en BD)
            if ($cotizacion->yaFueConvertida()) {
                throw new \InvalidArgumentException('Esta cotización ya fue convertida a pedido anteriormente.');
            }

            // 4. Eager-load detalles y validar que no estén vacíos
            $cotizacion->load('productos');
            if ($cotizacion->productos->isEmpty()) {
                throw new \RuntimeException('La cotización no tiene productos. No se puede crear un pedido vacío.');
            }

            // 5. Crear el Pedido con datos de la cotización
            $pedido = Pedido::create([
                'cotizacion_id' => $cotizacion->id,
                'cliente_id' => $cotizacion->cliente_id,
                'fecha_pedido' => now(),
                'total' => $cotizacion->total,
                'abono' => 0,
                'efectivo_pagado' => 0,
                'transferencia_pagado' => 0,
                'pago_movil_pagado' => 0,
                'prioridad' => $cotizacion->prioridad ?? 'Normal',
                'estado' => 'Pendiente',
                'user_id' => Auth::id(),
            ]);

            // 6. Transferir TODOS los detalles (incluyendo color, talla, precio cotizado)
            foreach ($cotizacion->productos as $detalle) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $detalle->producto_id,
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_unitario,
                    'descripcion' => $detalle->descripcion,
                    'lleva_bordado' => $detalle->lleva_bordado ?? false,
                    'nombre_logo' => $detalle->nombre_logo,
                    'ubicacion_logo' => $detalle->ubicacion_logo,
                    'cantidad_logo' => $detalle->cantidad_logo,
                    'color' => $detalle->color,
                    'talla' => $detalle->talla,
                ]);
            }

            // 7. Marcar como convertida (dentro de la misma transacción)
            $cotizacion->update(['estado' => 'Convertida']);

            Log::info('Cotización convertida a pedido', [
                'cotizacion_id' => $cotizacion->id,
                'pedido_id' => $pedido->id,
                'total' => $pedido->total,
                'productos' => $cotizacion->productos->count(),
                'user_id' => Auth::id(),
            ]);

            return $pedido;
        });
    }

    /**
     * Calcular total de la cotización sumando precio_base × cantidad.
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
     * Crear los detalles de cotización (líneas de producto).
     */
    private function crearDetalles(Cotizacion $cotizacion, array $productos): void
    {
        foreach ($productos as $item) {
            $producto = Producto::find($item['producto_id']);
            DetalleCotizacion::create([
                'cotizacion_id' => $cotizacion->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'descripcion' => $item['descripcion'] ?? null,
                'lleva_bordado' => $item['lleva_bordado'] ?? false,
                'nombre_logo' => $item['nombre_logo'] ?? null,
                'ubicacion_logo' => $item['ubicacion_logo'] ?? null,
                'cantidad_logo' => $item['cantidad_logo'] ?? null,
                'talla' => $item['talla'] ?? null,
                'precio_unitario' => $producto->precio_base,
            ]);
        }
    }
}
