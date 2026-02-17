<?php

namespace App\Services;

use App\Models\Direccion;
use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Telefono;
use Illuminate\Support\Facades\DB;

class EmpleadoService
{
    /**
     * Crear un nuevo empleado con persona, teléfono y dirección normalizados.
     */
    public function crear(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $persona = Persona::create([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'documento_identidad' => $data['documento_identidad'],
                'tipo_documento' => $data['tipo_documento'],
                'email' => $data['email'] ?? null,
                'estado_persona' => $data['estado_persona'] ?? null,
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'genero' => $data['genero'] ?? null,
            ]);

            $this->crearTelefono($persona->id, $data);
            $this->crearDireccion($persona->id, $data);

            // Auto-generar código de empleado si no se proporciona
            $codigoEmpleado = $data['codigo_empleado'] ?? null;
            if (!$codigoEmpleado) {
                $ultimoCodigo = Empleado::max('codigo_empleado');
                $numero = $ultimoCodigo ? ((int) substr($ultimoCodigo, 4) + 1) : 1;
                $codigoEmpleado = 'EMP-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            }

            $empleado = Empleado::create([
                'persona_id' => $persona->id,
                'codigo_empleado' => $codigoEmpleado,
                'fecha_ingreso' => $data['fecha_ingreso'],
                'cargo' => $data['cargo'],
                'departamento' => $data['departamento'],
                'estado' => $data['estado'],
            ]);

            return $empleado->id;
        });
    }

    /**
     * Actualizar un empleado existente con persona, teléfono y dirección.
     */
    public function actualizar(Empleado $empleado, array $data): void
    {
        DB::transaction(function () use ($empleado, $data) {
            $persona = $empleado->persona;

            $persona->update([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'documento_identidad' => $data['documento_identidad'],
                'tipo_documento' => $data['tipo_documento'],
                'email' => $data['email'] ?? null,
                'estado_persona' => $data['estado_persona'] ?? null,
                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                'genero' => $data['genero'] ?? null,
            ]);

            $this->actualizarTelefono($persona, $data);
            $this->actualizarDireccion($persona, $data);

            $empleado->update([
                'codigo_empleado' => $data['codigo_empleado'],
                'fecha_ingreso' => $data['fecha_ingreso'],
                'cargo' => $data['cargo'],
                'departamento' => $data['departamento'],
                'estado' => $data['estado'],
            ]);
        });
    }

    private function crearTelefono(int $personaId, array $data): void
    {
        if (!empty($data['telefono'])) {
            Telefono::create([
                'persona_id' => $personaId,
                'numero' => $data['telefono'],
                'tipo' => 'movil',
                'es_principal' => true,
            ]);
        }
    }

    private function crearDireccion(int $personaId, array $data): void
    {
        if (!empty($data['direccion']) || !empty($data['ciudad'])) {
            Direccion::create([
                'persona_id' => $personaId,
                'direccion' => $data['direccion'] ?? '',
                'ciudad' => $data['ciudad'] ?? null,
                'tipo' => 'casa',
                'es_principal' => true,
            ]);
        }
    }

    private function actualizarTelefono(Persona $persona, array $data): void
    {
        if (!empty($data['telefono'])) {
            $telefonoPrincipal = $persona->telefonos()->where('es_principal', true)->first();
            if ($telefonoPrincipal) {
                $telefonoPrincipal->update(['numero' => $data['telefono']]);
            } else {
                Telefono::create([
                    'persona_id' => $persona->id,
                    'numero' => $data['telefono'],
                    'tipo' => 'movil',
                    'es_principal' => true,
                ]);
            }
        }
    }

    private function actualizarDireccion(Persona $persona, array $data): void
    {
        if (!empty($data['direccion']) || !empty($data['ciudad'])) {
            $direccionPrincipal = $persona->direcciones()->where('es_principal', true)->first();
            if ($direccionPrincipal) {
                $direccionPrincipal->update([
                    'direccion' => $data['direccion'] ?? '',
                    'ciudad' => $data['ciudad'] ?? null,
                ]);
            } else {
                Direccion::create([
                    'persona_id' => $persona->id,
                    'direccion' => $data['direccion'] ?? '',
                    'ciudad' => $data['ciudad'] ?? null,
                    'tipo' => 'casa',
                    'es_principal' => true,
                ]);
            }
        }
    }
}
