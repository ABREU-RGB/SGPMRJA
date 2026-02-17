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
        if ($cotizacion->estado !== 'Aprobada') {
            throw new \InvalidArgumentException('Solo se pueden convertir cotizaciones con estado Aprobada.');
        }

        $pedidoExistente = Pedido::where('cotizacion_id', $cotizacion->id)->first();
        if ($pedidoExistente) {
            throw new \InvalidArgumentException('Esta cotización ya fue convertida a pedido anteriormente.');
        }

        $pedido = null;

        DB::transaction(function () use ($cotizacion, &$pedido) {
            $pedido = Pedido::create([
                'cotizacion_id' => $cotizacion->id,
                'cliente_id' => $cotizacion->cliente_id,
                'fecha_pedido' => now(),
                'fecha_entrega_estimada' => now()->addDays(7),
                'total' => $cotizacion->total,
                'abono' => 0,
                'efectivo_pagado' => 0,
                'transferencia_pagado' => 0,
                'pago_movil_pagado' => 0,
                'prioridad' => 'Normal',
                'estado' => 'Pendiente',
                'user_id' => Auth::id(),
            ]);

            foreach ($cotizacion->productos as $detalleCotizacion) {
                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $detalleCotizacion->producto_id,
                    'cantidad' => $detalleCotizacion->cantidad,
                    'precio_unitario' => $detalleCotizacion->precio_unitario,
                    'descripcion' => $detalleCotizacion->descripcion,
                    'lleva_bordado' => $detalleCotizacion->lleva_bordado ?? false,
                    'nombre_logo' => $detalleCotizacion->nombre_logo,
                    'ubicacion_logo' => $detalleCotizacion->ubicacion_logo,
                    'cantidad_logo' => $detalleCotizacion->cantidad_logo,
                    'talla' => $detalleCotizacion->talla,
                    'color' => null,
                ]);
            }

            $cotizacion->update(['estado' => 'Convertida']);
        });

        Log::info('Cotización convertida a pedido', [
            'cotizacion_id' => $cotizacion->id,
            'pedido_id' => $pedido->id,
            'total' => $pedido->total,
            'user_id' => Auth::id(),
        ]);

        return $pedido;
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
