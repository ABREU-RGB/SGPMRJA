@props([
    'name',
    'label'       => null,
    'value'       => null,
    'required'    => false,
    'placeholder' => '',
    'rows'        => 3,
    'hint'        => null,
    'disabled'    => false,
    'readonly'    => false,
    'id'          => null,
])

@php
    $id = $id ?: 'field-' . str_replace(['.', '[', ']'], ['-', '-', ''], $name);
    $fieldValue = old($name, $value);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')
        ]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
    >{{ $fieldValue }}</textarea>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    @if($hint)
        <div class="form-text text-muted">{{ $hint }}</div>
    @endif
</div>
