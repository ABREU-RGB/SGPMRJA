@extends('admin.layouts.app')

@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Cotizaciones</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión Operativa</a></li>
                        <li class="breadcrumb-item active">Cotizaciones</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <style>
        .card-body {
            overflow-x: auto;
        }

        /* ── DataTable — Estándar Atlántico Operativo ── */
        #cotizaciones-table {
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px;
        }

        #cotizaciones-table th,
        #cotizaciones-table td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Anchos de columna (suman 100%) */
        #cotizaciones-table th:nth-child(1) { width: 5%; }   /* Nro.     */
        #cotizaciones-table th:nth-child(2) { width: 30%; }  /* Cliente  */
        #cotizaciones-table th:nth-child(3) { width: 13%; }  /* Fecha    */
        #cotizaciones-table th:nth-child(4) { width: 13%; }  /* Total    */
        #cotizaciones-table th:nth-child(5) { width: 15%; text-align: center; } /* Estado */
        #cotizaciones-table th:nth-child(6) { width: 24%; text-align: center; } /* Acciones */

        #cotizaciones-table td:last-child {
            text-align: center;
            overflow: visible;
        }

        /* Backdrop más oscuro y difuminado para modal de cliente */
        #modalAddCliente~.modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7) !important;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
        }

        /* Mejorar visibilidad del modal de cliente */
        #modalAddCliente .modal-content {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }

        /* ── Backdrop difuminado para el modal de logos (Nivel 3) ─────────────
                                                       Mismo patrón que #modalAddCliente: selector de hermano CSS.
                                                       Al cerrarse el modal, Bootstrap elimina su backdrop →
                                                       el selector queda sin nodo al que aplicarse → sin efectos secundarios.
                                                    ──────────────────────────────────────────────────────────────────────── */
        #logoSearchModal~.modal-backdrop {
            background-color: rgba(0, 0, 0, 0.75) !important;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        /* ── FORCED DARK NAVY HEADER — Modales utilitarios de búsqueda ──────────
                                               Usamos el selector ID + .modal-header para superar la especificidad de
                                               Bootstrap (.modal-header, .bg-light, etc.) y forzar el color #132649.
                                               Aplica a TODOS los modales utilitarios del sistema.
                                            ───────────────────────────────────────────────────────────────────────── */
        #productosModalCotizacion .modal-header,
        #productosModalCotizacion-header,
        #colorCatalogoModal .modal-header,
        #tallaCatalogoModal .modal-header,
        #ubicacionCatalogoModal .modal-header {
            background-color: #132649 !important;
            /* = var(--vz-header-bg) con data-topbar=dark, app.css:3873 */
            background-image: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        #logoSearchModal .modal-header,
        #logoSearchModal-header {
            background-color: #132649 !important;
            /* mismo color exacto del navbar */
            background-image: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        /* Texto blanco en el título */
        #productosModalCotizacion .modal-header .modal-title,
        #logoSearchModal .modal-header .modal-title,
        #colorCatalogoModal .modal-header .modal-title,
        #tallaCatalogoModal .modal-header .modal-title,
        #ubicacionCatalogoModal .modal-header .modal-title {
            color: #ffffff !important;
        }

        /* ── Botón de cierre blanco puro via filter ──────────────────────────────
                                           .btn-close-white → opacity: 0.5 por defecto → casi invisible en #0d1f3c
                                           filter: brightness(0) invert(1) → convierte el SVG mask a #ffffff puro
                                           a opacity:1, sin depender de variables CSS ni del tema Bootstrap.
                                        ───────────────────────────────────────────────────────────────────────── */
        .utility-modal-close {
            filter: brightness(0) invert(1) !important;
            opacity: 1 !important;
            transition: transform 0.15s ease, filter 0.15s ease !important;
        }

        .utility-modal-close:hover {
            transform: scale(1.15);
            filter: brightness(0) invert(1) drop-shadow(0 0 5px rgba(0, 217, 165, 0.8)) !important;
            opacity: 1 !important;
        }

        /* ── Botón de selección de marca — "Atlántico Brand" ─────────────────────
                                   Base: #132649 (mismo color del navbar) → consistencia de marca total
                                   Hover: #00d9a5 (cyan de acento) → retroalimentación visual inmediata
                                   Icono: blanco puro en ambos estados
                                ───────────────────────────────────────────────────────────────────────── */
        .btn-atlantico-brand {
            background-color: #132649;
            border-color: #132649;
            color: #ffffff;
            transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.12s ease;
        }

        .btn-atlantico-brand:hover,
        .btn-atlantico-brand:focus {
            background-color: #00d9a5;
            border-color: #00c49a;
            color: #ffffff;
            transform: scale(1.08);
            box-shadow: 0 2px 8px rgba(0, 217, 165, 0.4);
        }

        .btn-atlantico-brand i {
            color: #ffffff !important;
        }

        #viewModal .rounded-circle.me-2.d-flex.align-items-center.justify-content-center {
            background: rgba(30, 60, 114, 0.12) !important;
        }

        #viewModal .rounded-circle.me-2.d-flex.align-items-center.justify-content-center i {
            color: #1e3c72 !important;
        }

        /* ── Input "Nombre del logo" — foco estilo Navy/Cyan ─────────────────────
                               Bootstrap usa --bs-primary (morado) como color de foco por defecto.
                               Al sobreescribir box-shadow y border-color con !important sobre las
                               clases .nombre-logo-input y .ubicacion-logo-input (aplicadas a TODOS
                               los rows dinámicos), cada fila nueva hereda el override sin JS extra.
                            ───────────────────────────────────────────────────────────────────────── */
        .nombre-logo-input:focus,
        .ubicacion-logo-input:focus,
        .cantidad-logo-input:focus {
            border-color: #132649 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 217, 165, 0.25) !important;
            outline: none !important;
        }

        /* El input-group que contiene el buscar-logo-trigger:
                               cuando el input adyacente tiene foco, alinear el borde del botón */
        .nombre-logo-input:focus+.buscar-logo-trigger {
            border-color: #00d9a5 !important;
        }

        .ubicacion-logo-input:focus+.configurar-bordados-trigger {
            border-color: #00d9a5 !important;
        }

        /* ── Swatch Grid ─────────────────────────────────────────────────────
                           Grid de círculos de color con nombre comercial.
                           Se adapta a cualquier ancho del modal via flex-wrap.
                        ───────────────────────────────────────────────────────────────────── */
        .color-grupo-header {
            font-size: 0.72rem;
            font-weight: 700;
            color: #1e3c72;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 0 4px;
            margin-bottom: 4px;
            border-bottom: 1px solid rgba(30, 60, 114, 0.1);
        }

        .color-swatch-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.1s ease;
            font-size: 0.82rem;
            user-select: none;
        }

        .color-swatch-item:hover {
            background-color: rgba(0, 217, 165, 0.08);
            transform: translateX(2px);
        }

        .color-swatch-circle {
            width: 22px;
            height: 22px;
            min-width: 22px;
            border-radius: 50%;
            border: 2px solid rgba(0, 0, 0, 0.12);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .color-swatch-item:hover .color-swatch-circle {
            transform: scale(1.15);
            box-shadow: 0 0 0 3px rgba(0, 217, 165, 0.3);
        }

        .talla-chip-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 54px;
            padding: 7px 12px;
            margin: 0 8px 8px 0;
            border-radius: 8px;
            border: 1px solid rgba(30, 60, 114, 0.25);
            background: #ffffff;
            color: #1e3c72;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.15s ease, border-color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
        }

        .talla-chip-item:hover {
            background: rgba(0, 217, 165, 0.12);
            border-color: #00d9a5;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 217, 165, 0.18);
        }

        /* Dot indicator inside the readonly input */
        .color-dot-indicator {
            width: 14px;
            height: 14px;
            min-width: 14px;
            border-radius: 50%;
            border: 1.5px solid rgba(0, 0, 0, 0.15);
            display: inline-block;
            vertical-align: middle;
            margin-right: 4px;
        }

        /* Chevron rotation for collapsible cards */
        [aria-expanded="true"] .notas-chevron {
            transform: rotate(180deg);
        }

        /* ── "Deep Floating" effect — triple layer de sombras ──────────────────
                                                   Capa 1 (wide glow):  elevación difusa a 60px — transmite profundidad
                                                   Capa 2 (crisp drop): sombra nítida a 24px — ancla visualmente el modal
                                                   Capa 3 (brand ring): ring de 1px con el azul de marca — enmarca como entidad de nivel superior
                                                ──────────────────────────────────────────────────────────────────────── */
        #logoSearchModal .modal-content {
            box-shadow:
                0 24px 60px rgba(0, 0, 0, 0.55),
                0 8px 24px rgba(0, 0, 0, 0.35),
                0 0 0 1px rgba(30, 60, 114, 0.22);
            border-top: 3px solid #00d9a5;
            /* acento cyan de marca — diferencia el nivel */
        }

        /* ── Filas de la tabla de logos ──────────────────────────────────────── */
        #logoSearchModalTable tbody tr {
            cursor: pointer;
            transition: background-color 0.18s ease, transform 0.1s ease;
        }

        /* Hover con verde de marca — muy sutil, no compite con el texto */
        #logoSearchModalTable tbody tr:hover {
            background-color: rgba(0, 217, 165, 0.08) !important;
        }

        /* Micro elevación del ícono de logo al hacer hover en la fila */
        #logoSearchModalTable tbody tr:hover .logo-row-icon {
            color: #00d9a5 !important;
            transform: scale(1.15);
            transition: color 0.15s, transform 0.15s;
        }

        .logo-row-icon {
            color: #adb5bd;
            font-size: 1rem;
            transition: color 0.15s, transform 0.15s;
            display: inline-block;
        }

        /* ── Buscador premium — borde activo y shadow al focus ───────────────── */
        #buscarLogoModal {
            border-color: #a8c4e8;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        #buscarLogoModal:focus {
            border-color: #1e3c72 !important;
            box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.15), 0 2px 6px rgba(30, 60, 114, 0.1) !important;
            outline: none;
        }

        /* Estilos para modal de selección de productos */
        .producto-selector-btn {
            cursor: pointer;
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 38px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .producto-selector-btn:hover {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        #productosModalCotizacionTable tbody tr {
            cursor: pointer;
            transition: background-color 0.15s;
        }

        #productosModalCotizacionTable tbody tr:hover {
            background-color: #e3f2fd !important;
        }

        #productosModalCotizacionTable tbody tr.selected {
            background-color: #bbdefb !important;
        }

        .producto-img-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-transactional">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Cotizaciones</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar cotización...">
                                <i class="ri-search-line search-icon"></i>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Agregar Cotización
                                </button>
                            @endif
                            <a href="{{ route('cotizaciones.reporte.pdf') }}" target="_blank" class="btn btn-danger ms-2">
                                <i class="ri-file-pdf-line align-bottom me-1"></i> Exportar PDF
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="cotizaciones-table" class="table table-bordered table-striped table-sm align-middle dt-transactional">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.cotizaciones.modals')
@endsection

@push('scripts')
    <!-- DataTables desde CDN, después de jQuery -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('/assets/js/municipios-venezuela.js') }}"></script>
    @include('admin.cotizaciones.scripts.main')
@endpush