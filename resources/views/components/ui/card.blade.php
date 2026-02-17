{{-- ============================================================
    Componente: x-ui.card
    Uso: <x-ui.card title="Mi Título" icon="ri-user-line">
             ...contenido...
         </x-ui.card>
    
    Genera una tarjeta Velzon con header, borde punteado
    y slot para contenido dinámico.
    ============================================================ --}}

@props([
    'title' => '',          {{-- Título del header --}}
    'icon'  => '',          {{-- Clase del icono Remix (opcional) --}}
])

<div class="card">
    {{-- Header con borde inferior punteado (estilo Velzon) --}}
    @if($title)
        <div class="card-header border-bottom-dashed">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">
                    @if($icon)
                        <i class="{{ $icon }} me-1 align-bottom"></i>
                    @endif
                    {{ $title }}
                </h5>
                {{-- Slot para botones de acción en el header --}}
                @if(isset($actions))
                    <div class="flex-shrink-0 d-flex gap-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
