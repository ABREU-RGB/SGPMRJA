<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'persona_id',
        'avatar',
        'role',
        'email',
        'password',
        'estado',
    ];

    /**
     * Relación con Persona (datos personales normalizados)
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Nombre completo desde la relación persona
     */
    public function getNombreCompletoAttribute()
    {
        return $this->persona ? $this->persona->nombre_completo : $this->name;
    }

    /**
     * Teléfono principal desde la relación persona
     */
    public function getTelefonoAttribute()
    {
        return $this->persona ? $this->persona->telefono_principal : null;
    }

    /**
     * Dirección principal desde la relación persona
     */
    public function getDireccionAttribute()
    {
        return $this->persona ? $this->persona->direccion_principal : null;
    }

    public function ordenesCreadas()
    {
        return $this->hasMany(OrdenProduccion::class, 'created_by');
    }

    public function movimientosRegistrados()
    {
        return $this->hasMany(MovimientoInsumo::class, 'created_by');
    }

    public function pedidosCreados()
    {
        return $this->hasMany(Pedido::class, 'user_id');
    }

    // Métodos para verificar roles
    public function isAdmin()
    {
        return $this->role === 'Administrador';
    }

    public function isSupervisor()
    {
        return $this->role === 'Supervisor';
    }


    // Método para verificar múltiples roles
    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


}
