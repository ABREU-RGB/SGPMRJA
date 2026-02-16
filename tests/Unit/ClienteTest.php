<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\Direccion;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    private function crearClienteConPersona(array $personaData = [], array $telefonos = [], array $direcciones = []): Cliente
    {
        $persona = new Persona(array_merge([
            'nombre' => 'María',
            'apellido' => 'González',
            'tipo_documento' => 'V-',
            'documento_identidad' => '98765432',
            'email' => 'maria@test.com',
        ], $personaData));

        $persona->setRelation('telefonos', collect(array_map(
            fn($t) => new Telefono($t),
            $telefonos
        )));
        $persona->setRelation('direcciones', collect(array_map(
            fn($d) => new Direccion($d),
            $direcciones
        )));

        $cliente = new Cliente(['tipo_cliente' => 'natural', 'estatus' => 1]);
        $cliente->setRelation('persona', $persona);

        return $cliente;
    }

    /** @test */
    public function accessors_delegan_a_persona_correctamente()
    {
        $cliente = $this->crearClienteConPersona();

        $this->assertEquals('María', $cliente->nombre);
        $this->assertEquals('González', $cliente->apellido);
        $this->assertEquals('maria@test.com', $cliente->email);
        $this->assertEquals('V-98765432', $cliente->documento);
    }

    /** @test */
    public function telefono_delega_a_persona_telefono_principal()
    {
        $cliente = $this->crearClienteConPersona([], [
            ['numero' => '0424-7777777', 'es_principal' => true],
        ]);

        $this->assertEquals('0424-7777777', $cliente->telefono);
    }

    /** @test */
    public function direccion_delega_a_persona_direccion_principal()
    {
        $cliente = $this->crearClienteConPersona([], [], [
            ['direccion' => 'Av. Bolívar 123', 'ciudad' => 'Caracas', 'estado' => 'Miranda', 'es_principal' => true],
        ]);

        $this->assertEquals('Av. Bolívar 123', $cliente->direccion);
        $this->assertEquals('Caracas', $cliente->ciudad);
        $this->assertEquals('Miranda', $cliente->estado_territorial);
    }

    /** @test */
    public function accessors_retornan_null_sin_persona()
    {
        $cliente = new Cliente(['tipo_cliente' => 'natural', 'estatus' => 1]);
        $cliente->setRelation('persona', null);

        $this->assertNull($cliente->nombre);
        $this->assertNull($cliente->apellido);
        $this->assertNull($cliente->email);
        $this->assertNull($cliente->telefono);
        $this->assertNull($cliente->documento);
        $this->assertNull($cliente->direccion);
    }
}
