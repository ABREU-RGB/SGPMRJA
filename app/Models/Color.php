<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colores';

    protected $fillable = [
        'nombre',
        'hex_referencial',
        'grupo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Scope: solo colores activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
