<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Extraer número de documento para validación custom
        $documento = $this->documento;
        $numeroDocumento = $documento;
        if (preg_match('/^(V-|J-|E-|G-)(.+)$/', $documento, $matches)) {
            $numeroDocumento = $matches[2];
        }

        return [
            'nombre' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/',
            'tipo_cliente' => 'required|in:natural,juridico',
            'email' => 'nullable|string|email:rfc,dns|max:255|unique:persona,email',
            'telefono' => 'required|string|regex:/^[0-9]{4}-[0-9]{7}$/',
            'documento' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($numeroDocumento) {
                    if (!preg_match('/^[0-9]+$/', $numeroDocumento)) {
                        $fail('El número de documento solo puede contener números.');
                    }
                    if (strlen($numeroDocumento) < 6) {
                        $fail('El documento debe tener al menos 6 dígitos.');
                    }
                    $exists = \App\Models\Persona::where('documento_identidad', $numeroDocumento)->exists();
                    if ($exists) {
                        $fail('Este documento ya está registrado en el sistema.');
                    }
                },
            ],
            'direccion' => 'nullable|string|max:500',
            'estado_territorial' => 'nullable|string|max:50',
            'ciudad' => 'nullable|string|max:100',
            'estatus' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.max' => 'El apellido no puede exceder los 100 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'tipo_cliente.required' => 'Debe seleccionar el tipo de cliente.',
            'tipo_cliente.in' => 'El tipo de cliente debe ser Natural o Jurídico.',
            'email.email' => 'El email debe ser una dirección de correo válida.',
            'email.unique' => 'Este email ya está registrado en el sistema.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe tener el formato 0424-1234567.',
            'documento.required' => 'El documento de identidad es obligatorio.',
            'direccion.max' => 'La dirección no puede exceder los 500 caracteres.',
            'estado_territorial.max' => 'El estado territorial no puede exceder los 50 caracteres.',
            'ciudad.max' => 'La ciudad no puede exceder los 100 caracteres.',
            'estatus.required' => 'Debe seleccionar el estatus del cliente.',
            'estatus.in' => 'El estatus debe ser Activo o Inactivo.',
        ];
    }
}
