<?php

namespace App\Services;

use App\Models\Direccion;
use App\Models\Persona;
use App\Models\Proveedor;
use App\Models\Telefono;
use Illuminate\Support\Facades\DB;

class ProveedorService
{
    /**
     * Crear un proveedor natural (persona física).
     */
    public function crearNatural(array $data): Proveedor
    {
        return DB::transaction(function () use ($data) {
            $persona = Persona::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'tipo_documento' => $data['tipo_documento'],
                'documento_identidad' => $data['documento_identidad'],
                'email' => $data['email'],
            ]);

            Telefono::create([
                'persona_id' => $persona->id,
                'numero' => $data['telefono'],
                'tipo' => 'movil',
                'es_principal' => true,
            ]);

            if (!empty($data['direccion'])) {
                Direccion::create([
                    'persona_id' => $persona->id,
                    'direccion' => $data['direccion'],
                    'ciudad' => $data['ciudad'] ?? null,
                    'estado' => $data['estado_territorial'] ?? null,
                    'tipo' => 'trabajo',
                    'es_principal' => true,
                ]);
            }

            return Proveedor::create([
                'tipo_proveedor' => 'natural',
                'persona_id' => $persona->id,
                'estado' => $data['estado'] ?? true,
            ]);
        });
    }

    /**
     * Crear un proveedor jurídico (empresa).
     */
    public function crearJuridico(array $data): Proveedor
    {
        return Proveedor::create([
            'tipo_proveedor' => 'juridico',
            'razon_social' => $data['razon_social'],
            'rif' => $data['rif'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'contacto' => $data['contacto'] ?? null,
            'telefono_contacto' => $data['telefono_contacto'] ?? null,
            'estado' => $data['estado'] ?? true,
        ]);
    }

    /**
     * Actualizar un proveedor natural existente.
     */
    public function actualizarNatural(Proveedor $proveedor, array $data): void
    {
        DB::transaction(function () use ($proveedor, $data) {
            if ($proveedor->persona_id && $proveedor->persona) {
                // Actualizar persona existente
                $proveedor->persona->update([
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'tipo_documento' => $data['tipo_documento'],
                    'documento_identidad' => $data['documento_identidad'],
                    'email' => $data['email'],
                ]);

                $this->actualizarTelefono($proveedor->persona, $data['telefono']);
                $this->actualizarDireccion($proveedor->persona, $data);
            } else {
                // Convertir de jurídico a natural: crear persona
                $persona = Persona::create([
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'tipo_documento' => $data['tipo_documento'],
                    'documento_identidad' => $data['documento_identidad'],
                    'email' => $data['email'],
                ]);

                Telefono::create([
                    'persona_id' => $persona->id,
                    'numero' => $data['telefono'],
                    'tipo' => 'movil',
                    'es_principal' => true,
                ]);

                if (!empty($data['direccion'])) {
                    Direccion::create([
                        'persona_id' => $persona->id,
                        'direccion' => $data['direccion'],
                        'ciudad' => $data['ciudad'] ?? null,
                        'estado' => $data['estado_territorial'] ?? null,
                        'tipo' => 'trabajo',
                        'es_principal' => true,
                    ]);
                }

                $proveedor->persona_id = $persona->id;
            }

            $proveedor->tipo_proveedor = 'natural';
            $proveedor->estado = $data['estado'] ?? true;
            $proveedor->save();
        });
    }

    /**
     * Actualizar un proveedor jurídico existente.
     */
    public function actualizarJuridico(Proveedor $proveedor, array $data): void
    {
        $proveedor->update([
            'tipo_proveedor' => 'juridico',
            'razon_social' => $data['razon_social'],
            'rif' => $data['rif'],
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'contacto' => $data['contacto'] ?? null,
            'telefono_contacto' => $data['telefono_contacto'] ?? null,
            'estado' => $data['estado'] ?? true,
        ]);
    }

    private function actualizarTelefono(Persona $persona, string $numero): void
    {
        $telefonoPrincipal = $persona->telefonos()->where('es_principal', true)->first();
        if ($telefonoPrincipal) {
            $telefonoPrincipal->update(['numero' => $numero]);
        } else {
            Telefono::create([
                'persona_id' => $persona->id,
                'numero' => $numero,
                'tipo' => 'movil',
                'es_principal' => true,
            ]);
        }
    }

    private function actualizarDireccion(Persona $persona, array $data): void
    {
        if (!empty($data['direccion'])) {
            $direccionPrincipal = $persona->direcciones()->where('es_principal', true)->first();
            if ($direccionPrincipal) {
                $direccionPrincipal->update([
                    'direccion' => $data['direccion'],
                    'ciudad' => $data['ciudad'] ?? null,
                    'estado' => $data['estado_territorial'] ?? null,
                ]);
            } else {
                Direccion::create([
                    'persona_id' => $persona->id,
                    'direccion' => $data['direccion'],
                    'ciudad' => $data['ciudad'] ?? null,
                    'estado' => $data['estado_territorial'] ?? null,
                    'tipo' => 'trabajo',
                    'es_principal' => true,
                ]);
            }
        }
    }
}
