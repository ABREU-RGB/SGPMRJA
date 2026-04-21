<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cargo';

    protected $fillable = ['nombre', 'departamento_id', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
