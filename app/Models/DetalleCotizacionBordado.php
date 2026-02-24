<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacionBordado extends Model
{
    use HasFactory;

    protected $table = 'detalle_cotizacion_bordado';

    protected $fillable = [
        'detalle_cotizacion_id',
        'ubicacion_bordado_id',
        'nombre_aplicado',
        'nombre_logo_aplicado',
        'es_personalizada',
        'cantidad',
        'precio_aplicado',
        'orden',
    ];

    protected $casts = [
        'es_personalizada' => 'boolean',
        'cantidad' => 'integer',
        'precio_aplicado' => 'decimal:2',
        'orden' => 'integer',
    ];

    public function detalleCotizacion()
    {
        return $this->belongsTo(DetalleCotizacion::class, 'detalle_cotizacion_id');
    }

    public function ubicacionBordado()
    {
        return $this->belongsTo(BordadoUbicacion::class, 'ubicacion_bordado_id');
    }
}
