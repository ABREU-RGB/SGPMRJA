{{-- ============================================================
    Componente: x-ui.button-save
    Uso básico:
        <x-ui.button-save />
    
    Uso con texto y loading personalizado:
        <x-ui.button-save text="Actualizar" loading-text="Actualizando..." />
    
    Características:
        - Estilo btn-success con icono ri-save-line
        - Estado de carga (loading) controlado por JavaScript
        - Spinner y texto cambian al activar data-loading="true"
        - Al desactivar loading, restaura el estado original
    ============================================================ --}}

@props([
    'text'        => 'Guardar',
    'loadingText' => 'Guardando...',
    'type'        => 'submit',
    'icon'        => 'ri-save-line',
])

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn btn-success']) }}
    data-loading-text="{{ $loadingText }}"
>
    {{-- Icono normal (visible cuando NO está cargando) --}}
    <i class="{{ $icon }} align-bottom me-1 btn-icon"></i>
    {{-- Spinner (oculto por defecto, visible al cargar) --}}
    <span class="spinner-border spinner-border-sm align-middle me-1 d-none btn-spinner"></span>
    {{-- Texto del botón --}}
    <span class="btn-text">{{ $text }}</span>
</button>

{{-- ============================================================
    Script inline para manejar el estado de carga.
    Se ejecuta una sola vez gracias al flag en window.
    
    Uso desde JavaScript:
        const btn = document.querySelector('.btn-success[type="submit"]');
        btn.setAttribute('data-loading', 'true');  // Activar loading
        btn.removeAttribute('data-loading');        // Desactivar loading
    ============================================================ --}}
@once
@push('scripts')
<script>
    // Observador de mutaciones para detectar cambios en data-loading
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-loading') {
                    const btn = mutation.target;
                    const isLoading = btn.hasAttribute('data-loading') 
                                      && btn.getAttribute('data-loading') !== 'false';
                    const icon    = btn.querySelector('.btn-icon');
                    const spinner = btn.querySelector('.btn-spinner');
                    const text    = btn.querySelector('.btn-text');

                    if (isLoading) {
                        // Activar estado de carga
                        btn.disabled = true;
                        if (icon)    icon.classList.add('d-none');
                        if (spinner) spinner.classList.remove('d-none');
                        if (text)    text.textContent = btn.dataset.loadingText || 'Guardando...';
                    } else {
                        // Restaurar estado normal
                        btn.disabled = false;
                        if (icon)    icon.classList.remove('d-none');
                        if (spinner) spinner.classList.add('d-none');
                        // Restaurar texto original desde el HTML renderizado
                        if (text) text.textContent = '{{ $text }}';
                    }
                }
            });
        });

        // Observar todos los botones que tengan data-loading-text
        document.querySelectorAll('[data-loading-text]').forEach(function(btn) {
            observer.observe(btn, { attributes: true, attributeFilter: ['data-loading'] });
        });
    });
</script>
@endpush
@endonce
