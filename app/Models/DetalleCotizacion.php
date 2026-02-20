<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    use HasFactory;

    protected $table = 'detalle_cotizacion';

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'descripcion',
        'lleva_bordado',
        'nombre_logo',
        'ubicacion_logo',
        'cantidad_logo',
        'color',
        'talla',
        'precio_unitario',
    ];

    protected $casts = [
        'lleva_bordado' => 'boolean',
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
    ];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
