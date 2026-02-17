{{-- ============================================================
    Componente: x-forms.select
    Uso básico:
        <x-forms.select name="role_id" label="Rol" :options="$roles" required />
    
    Uso con placeholder personalizado:
        <x-forms.select name="estado" label="Estado" :options="$estados"
                        placeholder="Seleccione un estado..." />
    
    Uso con botón "agregar on the fly":
        <x-forms.select name="departamento" label="Departamento"
                        :options="$departamentos"
                        add-button-target="#addDepartamentoModal" />
    
    Maneja automáticamente:
        - Label con asterisco rojo si es required
        - Clase 'is-invalid' ante errores de validación
        - Mensaje de error (@error)
        - Valor antiguo (old()) para preservar selección
        - Opciones desde array o colección Eloquent (id => nombre)
        - Botón '+' opcional para abrir modal de creación rápida
    ============================================================ --}}

@props([
    'name'            => '',
    'label'           => '',
    'options'         => [],
    'required'        => false,
    'disabled'        => false,
    'placeholder'     => 'Seleccione...',
    'value'           => null,
    'addButtonTarget' => null,  {{-- Selector CSS del modal para agregar "on the fly" --}}
    'hint'            => null,
    'id'              => null,  {{-- ID personalizado (si se omite, se genera como field-{name}) --}}
])

@php
    $id = $id ?: 'field-' . str_replace(['.', '[', ']'], ['-', '-', ''], $name);
    $selectedValue = old($name, $value);
@endphp

<div class="mb-3">
    {{-- Label --}}
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    {{-- Input group condicional: solo si hay botón de agregar --}}
    @if($addButtonTarget)
        <div class="input-group">
    @endif

    {{-- El select principal --}}
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
    >
        {{-- Opción por defecto (placeholder) --}}
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        {{-- Renderizar opciones --}}
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}"
                @selected($selectedValue == $optionValue)
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    {{-- Botón "+" para agregar on the fly --}}
    @if($addButtonTarget)
            <button type="button"
                    class="btn btn-outline-success"
                    data-bs-toggle="modal"
                    data-bs-target="{{ $addButtonTarget }}"
                    title="Agregar nuevo">
                <i class="ri-add-line"></i>
            </button>
        </div>
    @endif

    {{-- Texto de ayuda (hint) --}}
    @if($hint)
        <div class="form-text text-muted">{{ $hint }}</div>
    @endif

    {{-- Mensaje de error de validación --}}
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
