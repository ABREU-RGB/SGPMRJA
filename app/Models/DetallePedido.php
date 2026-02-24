<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedido';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'descripcion',
        'lleva_bordado',
        'nombre_logo',
        'color',
        'talla',
        'precio_unitario',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'lleva_bordado' => 'boolean',
    ];

    protected $appends = [
        'ubicacion_logo',
        'cantidad_logo',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'detalle_pedido_insumo', 'detalle_pedido_id', 'insumo_id')
            ->withPivot('cantidad_estimada');
    }

    public function bordados()
    {
        return $this->hasMany(DetallePedidoBordado::class, 'detalle_pedido_id')->orderBy('orden')->orderBy('id');
    }

    public function getUbicacionLogoAttribute()
    {
        if (!$this->lleva_bordado) {
            return null;
        }

        return $this->bordados->pluck('nombre_aplicado')->implode(', ') ?: null;
    }

    public function getCantidadLogoAttribute()
    {
        if (!$this->lleva_bordado) {
            return null;
        }

        $cantidad = $this->bordados->sum(function ($item) {
            return (int) ($item->cantidad ?? 1);
        });

        return $cantidad ?: null;
    }

    public function getNombreLogoAttribute($value)
    {
        $logos = $this->relationLoaded('bordados')
            ? $this->bordados->pluck('nombre_logo_aplicado')
            : $this->bordados()->pluck('nombre_logo_aplicado');

        $logos = collect($logos)
            ->map(fn($logo) => trim((string) $logo))
            ->filter()
            ->unique()
            ->values();

        if ($logos->isNotEmpty()) {
            return $logos->implode(', ');
        }

        return $value;
    }
}
