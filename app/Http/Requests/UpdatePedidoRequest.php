<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:cliente,id',
            'fecha_pedido' => 'required|date',
            'fecha_entrega_estimada' => 'nullable|date|after_or_equal:fecha_pedido',
            'estado' => 'required|in:Pendiente,Procesando,Completado,Cancelado',
            'abono' => 'required|numeric|min:0',
            'efectivo_pagado' => 'boolean',
            'transferencia_pagado' => 'boolean',
            'pago_movil_pagado' => 'boolean',
            'referencia_transferencia' => 'nullable|string|max:255|required_if:transferencia_pagado,true',
            'referencia_pago_movil' => 'nullable|string|max:255|required_if:pago_movil_pagado,true',
            'banco_id' => 'nullable|exists:banco,id|required_if:transferencia_pagado,true|required_if:pago_movil_pagado,true',
            'prioridad' => 'required|in:Normal,Alta,Urgente',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:producto,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.descripcion' => 'nullable|string|max:500',
            'productos.*.lleva_bordado' => 'nullable|boolean',
            'productos.*.nombre_logo' => 'nullable|string|max:100',
            'productos.*.bordados' => 'nullable|array|required_if:productos.*.lleva_bordado,true|min:1',
            'productos.*.bordados.*.ubicacion_bordado_id' => 'nullable|exists:bordado_ubicaciones,id',
            'productos.*.bordados.*.nombre_aplicado' => 'required|string|max:120',
            'productos.*.bordados.*.nombre_logo' => 'required|string|max:120',
            'productos.*.bordados.*.es_personalizada' => 'nullable|boolean',
            'productos.*.bordados.*.precio_aplicado' => 'required|numeric|min:0',
            'productos.*.bordados.*.cantidad' => 'nullable|integer|min:1',
            'productos.*.color' => 'nullable|string|max:50',
            'productos.*.talla' => [
                'nullable',
                Rule::exists('tallas', 'nombre')->where(function ($query) {
                    $query->where('activo', true);
                }),
            ],
        ];
    }
}
