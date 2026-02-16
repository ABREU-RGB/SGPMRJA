{{-- ============================================================
    Componente: x-forms.input
    Uso básico:
        <x-forms.input name="email" label="Correo" type="email" required />
    
    Uso con grupo (prefijo/sufijo):
        <x-forms.input name="precio" label="Precio" type="number" prepend="$" />
    
    Maneja automáticamente:
        - Label con asterisco rojo si es required
        - Clase 'is-invalid' ante errores de validación
        - Mensaje de error (@error) debajo del input
        - Valor antiguo (old()) para preservar datos al recargar
        - Soporte para input-group con prepend/append
    ============================================================ --}}

@props([
    'name'        => '',
    'label'       => '',
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'disabled'    => false,
    'readonly'    => false,
    'value'       => null,
    'prepend'     => null,     {{-- Texto o HTML para el prefijo del input-group --}}
    'append'      => null,     {{-- Texto o HTML para el sufijo del input-group --}}
    'hint'        => null,     {{-- Texto de ayuda debajo del campo --}}
    'maxlength'   => null,
    'min'         => null,
    'max'         => null,
    'step'        => null,
])

@php
    // Determinar el ID del campo a partir del name (reemplazar puntos y corchetes)
    $id = 'field-' . str_replace(['.', '[', ']'], ['-', '-', ''], $name);
    
    // Valor: prioridad old() > valor explícito > vacío
    $fieldValue = old($name, $value);
@endphp

<div class="mb-3">
    {{-- Label con asterisco rojo si es required --}}
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    {{-- Envoltura condicional: input-group solo si hay prepend o append --}}
    @if($prepend || $append)
        <div class="input-group">
            @if($prepend)
                <span class="input-group-text">{!! $prepend !!}</span>
            @endif
    @endif

    {{-- El input principal --}}
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $fieldValue }}"
        placeholder="{{ $placeholder ?: $label }}"
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        @if($step) step="{{ $step }}" @endif
    />

    @if($prepend || $append)
            @if($append)
                <span class="input-group-text">{!! $append !!}</span>
            @endif
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
