<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_producto';

    protected $fillable = [
        'nombre',
        'prefijo',
        'descripcion',
        'precio_confeccion',
        'requiere_tela',
    ];

    protected $casts = [
        'precio_confeccion' => 'decimal:2',
        'requiere_tela' => 'boolean',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Atributos de confección que aplican a este tipo.
     * Pivot: tipo_producto_atributo (es_obligatorio, orden).
     */
    public function atributos()
    {
        return $this->belongsToMany(Atributo::class, 'tipo_producto_atributo')
            ->withPivot(['es_obligatorio', 'orden'])
            ->withTimestamps()
            ->orderBy('tipo_producto_atributo.orden');
    }
}
