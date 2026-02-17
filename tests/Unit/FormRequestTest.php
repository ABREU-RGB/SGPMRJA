<?php

namespace Tests\Unit;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\UpdatePedidoRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Tests\TestCase;

class FormRequestTest extends TestCase
{
    /** @test */
    public function store_cliente_request_tiene_reglas_requeridas()
    {
        $request = new StoreClienteRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('nombre', $rules);
        $this->assertArrayHasKey('tipo_cliente', $rules);
        $this->assertArrayHasKey('telefono', $rules);
        $this->assertArrayHasKey('documento', $rules);
        $this->assertArrayHasKey('estatus', $rules);
        $this->assertStringContainsString('required', $rules['nombre']);
        $this->assertStringContainsString('required', $rules['telefono']);
    }

    /** @test */
    public function store_cliente_request_tiene_mensajes_personalizados()
    {
        $request = new StoreClienteRequest();
        $messages = $request->messages();

        $this->assertNotEmpty($messages);
        $this->assertArrayHasKey('nombre.required', $messages);
        $this->assertArrayHasKey('telefono.regex', $messages);
    }

    /** @test */
    public function update_cliente_request_no_valida_documento()
    {
        $request = new UpdateClienteRequest();
        $rules = $request->rules();

        $this->assertArrayNotHasKey('documento', $rules);
    }

    /** @test */
    public function store_pedido_request_valida_productos_como_array()
    {
        $request = new StorePedidoRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('productos', $rules);
        $this->assertStringContainsString('required', $rules['productos']);
        $this->assertStringContainsString('array', $rules['productos']);
        $this->assertStringContainsString('min:1', $rules['productos']);
        $this->assertArrayHasKey('productos.*.producto_id', $rules);
        $this->assertArrayHasKey('productos.*.cantidad', $rules);
    }

    /** @test */
    public function update_pedido_request_incluye_campo_estado()
    {
        $request = new UpdatePedidoRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('estado', $rules);
        $this->assertStringContainsString('Pendiente', $rules['estado']);
        $this->assertStringContainsString('Cancelado', $rules['estado']);
    }

    /** @test */
    public function store_user_request_requiere_password_confirmado()
    {
        $request = new StoreUserRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('password', $rules);
        $this->assertStringContainsString('confirmed', $rules['password']);
        $this->assertStringContainsString('min:8', $rules['password']);
    }

    /** @test */
    public function update_user_request_no_requiere_password()
    {
        $request = new UpdateUserRequest();
        $rules = $request->rules();

        $this->assertArrayNotHasKey('password', $rules);
    }

    /** @test */
    public function store_pedido_request_valida_tallas_correctas()
    {
        $request = new StorePedidoRequest();
        $rules = $request->rules();

        $tallaRule = $rules['productos.*.talla'];
        $this->assertStringContainsString('Talla Unica', $tallaRule);
        $this->assertStringContainsString('XS', $tallaRule);
        $this->assertStringContainsString('XXL', $tallaRule);
    }

    /** @test */
    public function all_form_requests_authorize_returns_true()
    {
        $requests = [
            new StoreClienteRequest(),
            new UpdateClienteRequest(),
            new StorePedidoRequest(),
            new UpdatePedidoRequest(),
            new StoreUserRequest(),
            new UpdateUserRequest(),
        ];

        foreach ($requests as $request) {
            $this->assertTrue($request->authorize(), get_class($request) . '::authorize() debería retornar true');
        }
    }
}
