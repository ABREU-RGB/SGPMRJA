<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BordadoUbicacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bordado_ubicacion';

    protected $fillable = [
        'nombre',
        'grupo',
        'precio_base',
        'orden',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'orden' => 'integer',
        'activo' => 'boolean',
    ];

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
