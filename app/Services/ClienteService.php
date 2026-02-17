<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\Direccion;
use Illuminate\Support\Facades\DB;

class ClienteService
{
    /**
     * Crear un cliente con su persona, teléfono y dirección normalizados.
     *
     * @return int ID del cliente creado
     */
    public function crear(array $data): int
    {
        $clienteId = null;

        DB::transaction(function () use ($data, &$clienteId) {
            // Extraer prefijo y número del documento
            $documento = $data['documento'];
            $tipoDocumento = 'V-';
            $numeroDocumento = $documento;

            if (preg_match('/^(V-|J-|E-|G-)(.+)$/', $documento, $matches)) {
                $tipoDocumento = $matches[1];
                $numeroDocumento = $matches[2];
            }

            // Crear persona
            $persona = Persona::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'] ?? '',
                'documento_identidad' => $numeroDocumento,
                'tipo_documento' => $tipoDocumento,
                'email' => $data['email'] ?? null,
            ]);

            // Crear teléfono principal
            if (!empty($data['telefono'])) {
                Telefono::create([
                    'persona_id' => $persona->id,
                    'numero' => $data['telefono'],
                    'tipo' => 'movil',
                    'es_principal' => true,
                ]);
            }

            // Crear dirección principal
            if (!empty($data['direccion']) || !empty($data['ciudad']) || !empty($data['estado_territorial'])) {
                Direccion::create([
                    'persona_id' => $persona->id,
                    'direccion' => $data['direccion'] ?? '',
                    'estado' => $data['estado_territorial'] ?? null,
                    'ciudad' => $data['ciudad'] ?? null,
                    'tipo' => 'casa',
                    'es_principal' => true,
                ]);
            }

            // Crear cliente
            $cliente = Cliente::create([
                'persona_id' => $persona->id,
                'tipo_cliente' => $data['tipo_cliente'],
                'estatus' => $data['estatus'],
            ]);

            $clienteId = $cliente->id;
        });

        return $clienteId;
    }

    /**
     * Actualizar un cliente y sus datos normalizados.
     */
    public function actualizar(Cliente $cliente, array $data): void
    {
        DB::transaction(function () use ($data, $cliente) {
            // Actualizar persona (sin documento)
            if ($cliente->persona) {
                $cliente->persona->update([
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'] ?? '',
                    'email' => $data['email'] ?? null,
                ]);

                // Actualizar o crear teléfono principal
                if (!empty($data['telefono'])) {
                    $telefonoPrincipal = $cliente->persona->telefonos()->where('es_principal', true)->first();
                    if ($telefonoPrincipal) {
                        $telefonoPrincipal->update(['numero' => $data['telefono']]);
                    } else {
                        Telefono::create([
                            'persona_id' => $cliente->persona->id,
                            'numero' => $data['telefono'],
                            'tipo' => 'movil',
                            'es_principal' => true,
                        ]);
                    }
                }

                // Actualizar o crear dirección principal
                if (!empty($data['direccion']) || !empty($data['ciudad']) || !empty($data['estado_territorial'])) {
                    $direccionPrincipal = $cliente->persona->direcciones()->where('es_principal', true)->first();
                    if ($direccionPrincipal) {
                        $direccionPrincipal->update([
                            'direccion' => $data['direccion'] ?? '',
                            'estado' => $data['estado_territorial'] ?? null,
                            'ciudad' => $data['ciudad'] ?? null,
                        ]);
                    } else {
                        Direccion::create([
                            'persona_id' => $cliente->persona->id,
                            'direccion' => $data['direccion'] ?? '',
                            'estado' => $data['estado_territorial'] ?? null,
                            'ciudad' => $data['ciudad'] ?? null,
                            'tipo' => 'casa',
                            'es_principal' => true,
                        ]);
                    }
                }
            }

            // Actualizar cliente
            $cliente->update([
                'tipo_cliente' => $data['tipo_cliente'],
                'estatus' => $data['estatus'],
            ]);
        });
    }
}
