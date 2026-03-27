@extends('admin.layouts.app')

@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Clientes</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión General</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Estilos en public/assets/css/custom.css — sección "MÓDULO MAESTROS — Clientes" --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-maestros">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Clientes</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Toggle Historial -->
                            @if($historial)
                                <a href="{{ route('clientes.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-list-check align-bottom me-1"></i> Solo Activos
                                </a>
                            @else
                                <a href="{{ route('clientes.index', ['historial' => true]) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="ri-history-line align-bottom me-1"></i> Ver Historial (Inactivos)
                                </a>
                            @endif
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar cliente...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            @if(!$historial)
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Agregar Cliente
                                </button>
                                <a href="{{ route('clientes.reporte.pdf') }}" class="btn btn-danger" target="_blank">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                            @else
                            <div class="d-flex gap-2">
                                <a href="{{ route('clientes.reporte.pdf') }}" class="btn btn-danger" target="_blank">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- ============================================================
                         FILTROS — Patrón Maestro S-07 (Colapsable)
                         Copiar este bloque completo a Proveedores, Productos, etc.
                         Solo ajustar las opciones de cada <select> y los data-col-index.
                         CSS genérico en custom.css: .navy-filter-*
                         ============================================================ --}}
                    <div class="advanced-filters-wrapper navy-theme" id="advanced-filters">
                        {{-- Header: siempre visible, actúa como trigger del collapse --}}
                        <button class="navy-filter-toggle collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filters-collapse-body"
                            aria-expanded="false" aria-controls="filters-collapse-body">
                            <div class="navy-filter-title">
                                <i class="ri-filter-3-line"></i>
                                <span>Filtros</span>
                            </div>
                            <i class="ri-arrow-down-s-line navy-filter-chevron"></i>
                        </button>
                        {{-- Body: colapsable, oculto por defecto --}}
                        <div class="collapse" id="filters-collapse-body">
                            <div class="navy-filter-body">
                                <div class="row g-2 align-items-end">
                                    {{-- Filtro 1: Tipo de Cliente --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label class="navy-filter-label" for="filter-tipo-cliente">
                                            <i class="ri-user-settings-line"></i> Tipo de Cliente
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-tipo-cliente" data-col-index="6">
                                            <option value="">Todos</option>
                                            <option value="natural">Natural</option>
                                            <option value="juridico">Jurídico</option>
                                            <option value="gubernamental">Gubernamental</option>
                                        </select>
                                    </div>
                                    {{-- Filtro 2: Estatus (Activo = normal, Inactivo = trashed / SoftDelete) --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label class="navy-filter-label" for="filter-estatus">
                                            <i class="ri-shield-check-line"></i> Estatus
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-estatus" data-col-index="7">
                                            <option value="">Todos</option>
                                            <option value="1" selected>Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                    {{-- Filtro 3: Estado Territorial (Venezuela) --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label class="navy-filter-label" for="filter-estado-territorial">
                                            <i class="ri-map-pin-line"></i> Estado
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-estado-territorial" data-col-index="8">
                                            <option value="">Todos</option>
                                            <option value="Amazonas">Amazonas</option>
                                            <option value="Anzoátegui">Anzoátegui</option>
                                            <option value="Apure">Apure</option>
                                            <option value="Aragua">Aragua</option>
                                            <option value="Barinas">Barinas</option>
                                            <option value="Bolívar">Bolívar</option>
                                            <option value="Carabobo">Carabobo</option>
                                            <option value="Cojedes">Cojedes</option>
                                            <option value="Delta Amacuro">Delta Amacuro</option>
                                            <option value="Distrito Capital">Distrito Capital</option>
                                            <option value="Falcón">Falcón</option>
                                            <option value="Guárico">Guárico</option>
                                            <option value="La Guaira">La Guaira</option>
                                            <option value="Lara">Lara</option>
                                            <option value="Mérida">Mérida</option>
                                            <option value="Miranda">Miranda</option>
                                            <option value="Monagas">Monagas</option>
                                            <option value="Nueva Esparta">Nueva Esparta</option>
                                            <option value="Portuguesa">Portuguesa</option>
                                            <option value="Sucre">Sucre</option>
                                            <option value="Táchira">Táchira</option>
                                            <option value="Trujillo">Trujillo</option>
                                            <option value="Yaracuy">Yaracuy</option>
                                            <option value="Zulia">Zulia</option>
                                        </select>
                                    </div>
                                    {{-- Filtro 4: Búsqueda por Cédula/RIF --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label class="navy-filter-label" for="filter-documento">
                                            <i class="ri-bank-card-line"></i> Cédula / RIF
                                        </label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control navy-filter-input" id="filter-documento"
                                                data-col-index="0" placeholder="Ej: V-12345678" autocomplete="off">
                                            <i class="ri-search-line navy-filter-input-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                {{-- Botón limpiar: dentro del body colapsable --}}
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-navy-outline btn-sm" id="btn-clear-filters">
                                        <i class="ri-refresh-line me-1"></i>Limpiar filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- FIN FILTROS --}}

                    <table id="clientes-table" class="table table-bordered table-striped table-sm align-middle table-operativa table-maestro">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Cliente</th>
                                <th>Tipo</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles del Cliente -->
    <div class="modal fade atlantico-modal" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detalles del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Columna Izquierda: Datos Personales -->
                        <div class="col-lg-6">
                            <!-- Card Datos Personales -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-user-line me-2"></i>Información Personal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        {{-- Cliente Natural: Nombre + Apellido en 2 columnas --}}
                                        <div class="col-6" id="view-block-nombre">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Nombre</small>
                                                    <span class="fw-semibold" id="view-nombre">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6" id="view-block-apellido">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-follow-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Apellido</small>
                                                    <span class="fw-semibold" id="view-apellido">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Cliente Jurídico/Gubernamental: Razón Social en ancho completo --}}
                                        <div class="col-12 d-none" id="view-block-razon-social">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-building-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Razón Social</small>
                                                    <span class="fw-semibold" id="view-razon-social">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-bank-card-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Documento</small>
                                                    <span class="fw-semibold" id="view-documento">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-settings-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Tipo Cliente</small>
                                                    <span class="fw-semibold" id="view-tipo_cliente">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Contacto -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-contacts-line me-2"></i>Información de Contacto
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-mail-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Email</small>
                                                    <span class="fw-semibold" id="view-email">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-phone-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Teléfono</small>
                                                    <span class="fw-semibold" id="view-telefono">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Ubicación y Estado -->
                        <div class="col-lg-6">
                            <!-- Card Ubicación -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-map-pin-line me-2"></i>Ubicación
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-home-4-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Dirección</small>
                                                    <span class="fw-semibold" id="view-direccion">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-government-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Estado</small>
                                                    <span class="fw-semibold" id="view-estado-territorial">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-building-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Municipio</small>
                                                    <span class="fw-semibold" id="view-ciudad">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Estatus -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-information-line me-2"></i>Estatus del Cliente
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-checkbox-circle-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold" id="view-estatus">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-calendar-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Fecha Registro</small>
                                                    <span class="fw-semibold" id="view-created">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar -->
    <div class="modal fade atlantico-modal" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="clienteForm" class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="modalTitle">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id-field" />

                    <div class="modal-form-section">
                        <div class="section-header-compact">
                            <div class="modal-form-section-title"><i class="ri-fingerprint-line"></i>Identificación</div>
                            <div class="status-inline">
                                <span class="status-inline-label">Estatus</span>
                                <div class="form-check form-switch form-switch-success">
                                    <input type="hidden" name="estatus" value="0" />
                                    <input class="form-check-input" type="checkbox" role="switch" id="estatus-field"
                                        name="estatus" value="1" checked />
                                    <label class="form-check-label" for="estatus-field" id="estatus-label">Activo</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-0">
                            <div class="col-md-6">
                                <x-forms.input name="documento_number" label="Documento (Cédula o RIF)"
                                    id="documento-number-field" required maxlength="10" placeholder="Nro. documento"
                                    prependRaw="true">
                                    <x-slot:prepend>
                                        <select class="form-select" id="documento-prefix-field" style="max-width: 70px;">
                                            <option value="V-">V-</option>
                                            <option value="J-">J-</option>
                                            <option value="E-">E-</option>
                                            <option value="G-">G-</option>
                                        </select>
                                    </x-slot:prepend>
                                </x-forms.input>
                                <input type="hidden" id="documento-field" name="documento" />
                                <small class="text-muted"
                                    style="margin-top: -6px; display: block; margin-bottom: 6px;">Máximo
                                    10 dígitos</small>
                                <div id="documento-error" class="invalid-feedback" style="display: none;"></div>
                            </div>
                            <div class="col-md-6">
                                <x-forms.select name="tipo_cliente" label="Tipo de Cliente" required id="tipo_cliente-field"
                                    :options="['natural' => 'Natural', 'juridico' => 'Jurídico', 'gubernamental' => 'Gubernamental']" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-form-section">
                        <div class="modal-form-section-title"><i class="ri-user-3-line"></i>Datos del Cliente</div>

                        <div id="campos-persona-natural" class="row g-2 mb-0">
                            <div class="col-md-6">
                                <x-forms.input name="nombre" label="Nombre" placeholder="Nombre" maxlength="100" required
                                    id="nombre-field" />
                            </div>
                            <div class="col-md-6">
                                <x-forms.input name="apellido" label="Apellido" placeholder="Apellido" maxlength="100"
                                    required id="apellido-field" />
                            </div>
                        </div>

                        <div id="campos-razon-social" class="row g-2 mb-0 d-none">
                            <div class="col-12">
                                <x-forms.input name="nombre" label="Razón Social" placeholder="Razón Social de la empresa"
                                    maxlength="200" id="razon-social-field" hint="Se almacenará como nombre del cliente" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-form-section">
                        <div class="modal-form-section-title"><i class="ri-contacts-book-2-line"></i>Contacto</div>

                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <x-forms.input name="email" label="Email" type="email" placeholder="correo@ejemplo.com"
                                    id="email-field" />
                            </div>
                            <div class="col-md-6">
                                <x-forms.input name="telefono_number" label="Teléfono" id="telefono-number-field" required
                                    maxlength="7" placeholder="1234567" prependRaw="true">
                                    <x-slot:prepend>
                                        <select class="form-select" id="telefono-prefix-field"
                                            style="max-width: 100px; min-width: 100px;">
                                            <option value="0412">0412</option>
                                            <option value="0422">0422</option>
                                            <option value="0414">0414</option>
                                            <option value="0424" selected>0424</option>
                                            <option value="0416">0416</option>
                                            <option value="0426">0426</option>
                                        </select>
                                    </x-slot:prepend>
                                </x-forms.input>
                                <input type="hidden" id="telefono-field" name="telefono" />
                                <div id="telefono-error" class="invalid-feedback" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="row g-2 mb-0">
                            <div class="col-12">
                                <x-forms.textarea name="direccion" label="Dirección" placeholder="Dirección completa"
                                    maxlength="500" required id="direccion-field" rows="2" />
                            </div>
                        </div>
                    </div>

                    <div class="modal-form-section mb-0">
                        <div class="modal-form-section-title"><i class="ri-map-pin-2-line"></i>Ubicación</div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="estado_territorial-field" class="form-label required">Estado</label>
                                <select name="estado_territorial" id="estado_territorial-field" class="form-select"
                                    required>
                                    <option value="">Seleccione estado</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Anzoátegui">Anzoátegui</option>
                                    <option value="Apure">Apure</option>
                                    <option value="Aragua">Aragua</option>
                                    <option value="Barinas">Barinas</option>
                                    <option value="Bolívar">Bolívar</option>
                                    <option value="Carabobo">Carabobo</option>
                                    <option value="Cojedes">Cojedes</option>
                                    <option value="Delta Amacuro">Delta Amacuro</option>
                                    <option value="Distrito Capital">Distrito Capital</option>
                                    <option value="Falcón">Falcón</option>
                                    <option value="Guárico">Guárico</option>
                                    <option value="La Guaira">La Guaira</option>
                                    <option value="Lara">Lara</option>
                                    <option value="Mérida">Mérida</option>
                                    <option value="Miranda">Miranda</option>
                                    <option value="Monagas">Monagas</option>
                                    <option value="Nueva Esparta">Nueva Esparta</option>
                                    <option value="Portuguesa">Portuguesa</option>
                                    <option value="Sucre">Sucre</option>
                                    <option value="Táchira">Táchira</option>
                                    <option value="Trujillo">Trujillo</option>
                                    <option value="Yaracuy">Yaracuy</option>
                                    <option value="Zulia">Zulia</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="ciudad-field" class="form-label required">Municipio</label>
                                <select name="ciudad" id="ciudad-field" class="form-select" required>
                                    <option value="">Primero seleccione un estado</option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer bg-light border-0">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <x-ui.button-save id="add-btn" text="Agregar" icon="ri-add-line" loading-text="Agregando..." />
                        <x-ui.button-save id="edit-btn" text="Actualizar" icon="ri-save-line" loading-text="Actualizando..."
                            style="display: none;" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/municipios-venezuela.js') }}"></script>
    <script>
        $(function () {
            // Activa tooltips para todos los elementos con atributo title
            $(document).on('mouseenter', '[title]', function () {
                $(this).tooltip({ container: 'body' }).tooltip('show');
            });
            $(document).on('mouseleave', '[title]', function () {
                $(this).tooltip('dispose');
            });
        });

        // Validación y formato para el campo de teléfono
        $(document).on('input', '#telefono-field', function () {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 4) {
                value = value.slice(0, 4) + '-' + value.slice(4, 11);
            }
            this.value = value.slice(0, 12);
        });

        // === Capitalizar solo la primera letra del campo dirección ===
        $(document).on('blur', '#direccion-field', function () {
            var val = $(this).val();
            if (val && val.length > 0) {
                $(this).val(val.charAt(0).toUpperCase() + val.slice(1));
            }
        });

        // Validación en tiempo real para nombre (solo letras y espacios)
        $(document).on('input', '#nombre-field', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Validación en tiempo real para apellido (solo letras y espacios)
        $(document).on('input', '#apellido-field', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Validación en tiempo real para documento (solo números, maxlength dinámico)
        $(document).on('input', '#documento-number-field', function () {
            var maxLen = getDocMaxLength();
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, maxLen);
        });

        // Validación onblur para nombre
        $(document).on('blur', '#nombre-field', function () {
            let value = $(this).val().trim();
            if (value.length < 2) {
                $(this).addClass('is-invalid');
                $('#nombre-error').text('El nombre debe tener al menos 2 caracteres.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#nombre-error').hide();
            }
        });

        // Validación onblur para apellido
        $(document).on('blur', '#apellido-field', function () {
            let value = $(this).val().trim();
            if (value.length > 0 && value.length < 2) {
                $(this).addClass('is-invalid');
                $('#apellido-error').text('El apellido debe tener al menos 2 caracteres.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#apellido-error').hide();
            }
        });

        // Validación onblur para documento
        // Validación onblur para documento
        $(document).on('blur', '#documento-number-field', function () {
            let value = $(this).val().trim();
            let $input = $(this);
            let $error = $('#documento-error');
            let isEditMode = $('#id-field').val() !== ''; // Comprobar si estamos en edición

            if (value.length < 6) {
                $input.addClass('is-invalid');
                var maxLen = getDocMaxLength();
                $error.text('El documento debe tener entre 6 y ' + maxLen + ' dígitos.').show();
            } else {
                // Si la longitud es válida y NO estamos en edición, verificamos duplicados
                if (!isEditMode) {
                    $.ajax({
                        url: "{{ route('clientes.check-documento') }}",
                        method: 'GET',
                        data: { numero: value },
                        success: function (response) {
                            if (response.exists) {
                                $input.addClass('is-invalid');
                                $error.text('Este cliente ya se encuentra registrado.').show();
                                // Opcional: Deshabilitar el botón de agregar
                                $('#add-btn').prop('disabled', true);
                            } else {
                                $input.removeClass('is-invalid').addClass('is-valid');
                                $error.hide();
                                $('#add-btn').prop('disabled', false);
                            }
                        },
                        error: function () {
                            console.error('Error al verificar documento');
                        }
                    });
                } else {
                    $input.removeClass('is-invalid').addClass('is-valid');
                    $error.hide();
                }
            }
        });

        // Validación onblur para teléfono
        $(document).on('blur', '#telefono-field', function () {
            let value = $(this).val().trim();
            let regex = /^[0-9]{4}-[0-9]{7}$/;
            if (!regex.test(value)) {
                $(this).addClass('is-invalid');
                $('#telefono-error').text('El teléfono debe tener el formato 0424-1234567.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#telefono-error').hide();
            }
        });

        // Validación onblur para email
        $(document).on('blur', '#email-field', function () {
            let value = $(this).val().trim();
            let $input = $(this);
            let $error = $('#email-error');
            let isEditMode = $('#id-field').val() !== '';
            let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value.length > 0) {
                if (!regex.test(value)) {
                    $input.addClass('is-invalid');
                    $error.text('Ingrese un email válido.').show();
                } else {
                    // Si formato es válido y NO es edición, verificar duplicado
                    if (!isEditMode) {
                        $.ajax({
                            url: "{{ route('clientes.check-email') }}",
                            method: 'GET',
                            data: { email: value },
                            success: function (response) {
                                if (response.exists) {
                                    $input.addClass('is-invalid');
                                    $error.text('Este correo ya está registrado.').show();
                                    $('#add-btn').prop('disabled', true);
                                } else {
                                    $input.removeClass('is-invalid').addClass('is-valid');
                                    $error.hide();
                                    $('#add-btn').prop('disabled', false);
                                }
                            },
                            error: function () {
                                console.error('Error al verificar email');
                            }
                        });
                    } else {
                        // En modo edición no validamos duplicado (limitación por now)
                        $input.removeClass('is-invalid').addClass('is-valid');
                        $error.hide();
                    }
                }
            } else {
                // Si está vacío, quitar clases (o mostrar error si required)
                // Es opcional en el html? No tiene "required" en el html form, pero tiene validator?
                // En el HTML no tiene 'required'.
                $input.removeClass('is-invalid').removeClass('is-valid');
                $error.hide();
            }
        });

        // Limpiar validaciones al abrir modal
        $('#showModal').on('show.bs.modal', function () {
            $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            $('.invalid-feedback').hide();
        });

        // === Lógica dinámica: Natural vs Jurídico/Gubernamental ===
        function toggleClienteFields() {
            var tipo = $('#tipo_cliente-field').val();
            var esNatural = (tipo === 'natural' || tipo === '');
            var $prefixSelect = $('#documento-prefix-field');
            var $docInput = $('#documento-number-field');

            if (esNatural) {
                // Mostrar Nombre + Apellido, ocultar Razón Social
                $('#campos-persona-natural').removeClass('d-none');
                $('#nombre-field').prop('required', true).prop('disabled', false);
                $('#apellido-field').prop('required', true).prop('disabled', false);

                $('#campos-razon-social').addClass('d-none');
                $('#razon-social-field').prop('required', false).prop('disabled', true).val('');

                // Prefijos: V- y E- para Natural
                $prefixSelect.html('<option value="V-">V-</option><option value="E-">E-</option>');
                $prefixSelect.prop('disabled', false);
                // Maxlength: 8 dígitos para cédula
                $docInput.attr('maxlength', '8');
                // Truncar si el valor actual excede el nuevo máximo
                if ($docInput.val().length > 8) {
                    $docInput.val($docInput.val().slice(0, 8));
                }

            } else if (tipo === 'juridico') {
                // Ocultar Nombre + Apellido, mostrar Razón Social
                $('#campos-persona-natural').addClass('d-none');
                $('#nombre-field').prop('required', false).prop('disabled', true).val('');
                $('#apellido-field').prop('required', false).prop('disabled', true).val('');

                $('#campos-razon-social').removeClass('d-none');
                $('#razon-social-field').prop('required', true).prop('disabled', false);

                // Prefijo: solo J- (bloqueado)
                $prefixSelect.html('<option value="J-">J-</option>');
                $prefixSelect.prop('disabled', true);
                // Maxlength: 9 dígitos para RIF
                $docInput.attr('maxlength', '9');
                if ($docInput.val().length > 9) {
                    $docInput.val($docInput.val().slice(0, 9));
                }

            } else if (tipo === 'gubernamental') {
                // Ocultar Nombre + Apellido, mostrar Razón Social
                $('#campos-persona-natural').addClass('d-none');
                $('#nombre-field').prop('required', false).prop('disabled', true).val('');
                $('#apellido-field').prop('required', false).prop('disabled', true).val('');

                $('#campos-razon-social').removeClass('d-none');
                $('#razon-social-field').prop('required', true).prop('disabled', false);

                // Prefijo: solo G- (bloqueado)
                $prefixSelect.html('<option value="G-">G-</option>');
                $prefixSelect.prop('disabled', true);
                // Maxlength: 9 dígitos para RIF gubernamental
                $docInput.attr('maxlength', '9');
                if ($docInput.val().length > 9) {
                    $docInput.val($docInput.val().slice(0, 9));
                }
            }
        }

        // Obtener maxlength dinámico según prefijo actual
        function getDocMaxLength() {
            var prefix = $('#documento-prefix-field').val();
            return (prefix === 'J-' || prefix === 'G-') ? 9 : 8;
        }

        // Disparar al cambiar el tipo de cliente
        $(document).on('change', '#tipo_cliente-field', function () {
            toggleClienteFields();
        });

        // Estado inicial al cargar la página
        toggleClienteFields();

        // Validación onblur para Razón Social (cuando visible)
        $(document).on('blur', '#razon-social-field', function () {
            let value = $(this).val().trim();
            let $input = $(this);
            let $error = $('#razon-social-error');
            if (value.length > 0 && value.length < 3) {
                $input.addClass('is-invalid');
                $error.text('La razón social debe tener al menos 3 caracteres.').show();
            } else {
                $input.removeClass('is-invalid').addClass('is-valid');
                $error.hide();
            }
        });
    </script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
            function generateButtons(clienteId, isTrashed) {
                // Si el registro está inhabilitado (trashed), solo mostrar botón "Ver"
                if (isTrashed) {
                    return '<div class="d-flex gap-1 justify-content-center">' +
                        '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + clienteId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                        '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                        '</button>' +
                        '</div>';
                }
                return '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + clienteId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-success edit-item-btn" data-id="' + clienteId + '" title="Editar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-pencil-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-danger remove-item-btn" data-id="' + clienteId + '" title="Inhabilitar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-forbid-line" style="font-size:13px;"></i>' +
                    '</button>' +
                    '</div>';
            }

            function formatDate(dateStr) {
                if (!dateStr) return 'N/A';

                if (typeof dateStr === 'string') {
                    var parts = dateStr.trim().split(' ');
                    var datePart = parts[0] || '';
                    if (/^\d{2}\/\d{2}\/\d{4}$/.test(datePart)) {
                        return datePart;
                    }
                }

                var date = new Date(dateStr);
                if (isNaN(date.getTime())) return dateStr;

                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var year = date.getFullYear();

                return day + '/' + month + '/' + year;
            }

            var table = $('#clientes-table').DataTable({
                ajax: {
                    url: "{{ route('clientes.data') }}",
                    dataSrc: 'data',
                    data: function (d) {
                        // ── Filtros avanzados: enviar valores al server ──
                        d.filter_tipo_cliente       = $('#filter-tipo-cliente').val();
                        d.filter_estatus            = $('#filter-estatus').val();
                        d.filter_estado_territorial  = $('#filter-estado-territorial').val();
                        d.filter_documento          = $('#filter-documento').val();
                    }
                },
                columns: [
                    { data: 'documento' },                           // col 0
                    {                                                 // col 1
                        data: null,
                        render: function (data, type, row) {
                            if (row.tipo_cliente === 'juridico' || row.tipo_cliente === 'gubernamental') {
                                return row.nombre || 'N/A';
                            }
                            return (row.nombre || '') + ' ' + (row.apellido || '');
                        }
                    },
                    {                                                 // col 2
                        data: 'tipo_cliente', render: function (data) {
                            if (data === 'natural') return '<span class="badge-tipo badge-tipo-natural"><i class="ri-user-line"></i> Natural</span>';
                            if (data === 'juridico') return '<span class="badge-tipo badge-tipo-juridico"><i class="ri-building-line"></i> Jurídico</span>';
                            if (data === 'gubernamental') return '<span class="badge-tipo badge-tipo-gubernamental"><i class="ri-government-line"></i> Gubernamental</span>';
                            return data;
                        }
                    },
                    { data: 'telefono' },                             // col 3
                    {                                                 // col 4
                        data: 'email',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">—</span>';
                            return '<span title="' + data + '" style="cursor:default;">' + data + '</span>';
                        }
                    },
                    { data: null, orderable: false, render: function (data, type, row) { return generateButtons(row.id, row.trashed); } }
                ],
                order: [[0, 'asc']],
                dom: 'rtip',
                buttons: [
                    { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4] } },
                    { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4] } }
                ],
                language: lenguajeData
            });

            // Buscador personalizado (búsqueda global)
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // ══════════════════════════════════════════════════════
            // FILTROS AVANZADOS — Lógica JS (Patrón Maestro S-07)
            // Para replicar: copiar este bloque y ajustar los IDs
            // de los selectores (#filter-xxx) y sus data-col-index.
            // ══════════════════════════════════════════════════════

            // Aplicar filtros al cambiar cualquier select
            $('.navy-filter-select').on('change', function () {
                table.ajax.reload();
            });

            // Aplicar filtro de documento con debounce (300ms)
            var filterDocTimeout = null;
            $('#filter-documento').on('keyup', function () {
                clearTimeout(filterDocTimeout);
                filterDocTimeout = setTimeout(function () {
                    table.ajax.reload();
                }, 300);
            });

            // Si se llegó por toggle historial (?historial=true), pre-seleccionar "Inactivo"
            @if($historial)
                $('#filter-estatus').val('0');
                table.ajax.reload();
            @endif

            // Botón limpiar filtros
            $('#btn-clear-filters').on('click', function () {
                $('#filter-tipo-cliente').val('');
                $('#filter-estatus').val('');
                $('#filter-estado-territorial').val('');
                $('#filter-documento').val('');
                table.ajax.reload();
            });


            // Ajustar columnas cuando se redimensiona la ventana
            $(window).on('resize', function () {
                table.columns.adjust();
            });
            // Ajustar después de carga inicial
            setTimeout(function () {
                table.columns.adjust();
            }, 100);
            function resetForm() {
                $("#clienteForm").trigger("reset");
                $("#id-field").val("");
                $("#modalTitle").text("Agregar Cliente");
                $("#add-btn").show();
                $("#edit-btn").hide();
                $("#documento-prefix-field").val("V-");
                $("#documento-prefix-field").prop('disabled', false).removeClass('campo-protegido');
                $("#documento-number-field").val("");
                $("#documento-number-field").prop('disabled', false).removeClass('campo-protegido');
                // Reset teléfono
                $("#telefono-prefix-field").val("0424");
                $("#telefono-number-field").val("");
                // Resetear tipo cliente a Natural y actualizar campos
                $("#tipo_cliente-field").val("");
                $("#razon-social-field").val("");
                toggleClienteFields();
            }
            function setEditMode() {
                $("#modalTitle").text("Actualizar Cliente");
                $("#add-btn").hide();
                $("#edit-btn").show();
                // Bloquear edición de documento
                $("#documento-prefix-field").prop('disabled', true).addClass('campo-protegido');
                $("#documento-number-field").prop('disabled', true).addClass('campo-protegido');
            }
            $("#create-btn").click(function () { resetForm(); });
            $("#showModal").on('hidden.bs.modal', function () { resetForm(); });

            // Listener para actualizar label del checkbox de estatus
            $("#estatus-field").on('change', function () {
                if ($(this).is(':checked')) {
                    $("#estatus-label").text('Activo');
                } else {
                    $("#estatus-label").text('Inactivo');
                }
            });

            // Dropdown dependiente: Poblar municipios cuando cambia el estado
            $("#estado_territorial-field").on('change', function () {
                const estado = $(this).val();
                const municipios = getMunicipios(estado);
                const ciudadSelect = $("#ciudad-field");

                // Limpiar opciones anteriores
                ciudadSelect.empty();

                if (estado === '') {
                    ciudadSelect.append('<option value="">Primero seleccione un estado</option>');
                } else {
                    ciudadSelect.append('<option value="">Seleccione municipio</option>');
                    municipios.forEach(function (municipio) {
                        ciudadSelect.append('<option value="' + municipio + '">' + municipio + '</option>');
                    });
                }
            });

            const validator = new FormValidator('clienteForm');

            $('#add-btn').click(function (e) {
                e.preventDefault();

                // Validar formulario antes de enviar
                if (!validator.validateAll()) {
                    return;
                }

                // Se deshabilita el botón para evitar múltiples envíos
                $(this).prop('disabled', true);
                $("#clienteForm").submit();
            });

            $("#clienteForm").on("submit", function (e) {
                e.preventDefault();
                var id = $("#id-field").val();
                var url = id ? "{{ route('clientes.update', ':id') }}".replace(':id', id) : "{{ route('clientes.store') }}";
                var method = id ? "PUT" : "POST";
                var documentoCompleto = $("#documento-prefix-field").val() + $("#documento-number-field").val();
                $("#documento-field").val(documentoCompleto);
                // Concatenar teléfono: prefijo-número
                var telefonoCompleto = $("#telefono-prefix-field").val() + "-" + $("#telefono-number-field").val();
                $("#telefono-field").val(telefonoCompleto);
                var formData = $(this).serialize();
                if (method === 'PUT') { formData += '&_method=PUT'; }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        $("#showModal").modal("hide");
                        $("#clienteForm").trigger("reset");
                        table.ajax.reload();
                        Swal.fire({ icon: 'success', title: '¡Éxito!', text: response.message, showConfirmButton: false, timer: 2000 });
                        $('#add-btn').prop('disabled', false); // Re-enable button on success
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON.message });
                        $('#add-btn').prop('disabled', false); // Re-enable button on error
                    }
                });
            });
            $(document).on("click", ".view-item-btn", function () {
                var id = $(this).data("id");
                $.get("{{ route('clientes.show', '') }}/" + id, function (data) {
                    $("#viewModal").modal("show");
                    var tipoTexto = data.tipo_cliente === 'natural' ? 'Natural' : (data.tipo_cliente === 'juridico' ? 'Jurídico' : 'Gubernamental');
                    if (data.tipo_cliente === 'natural') {
                        $("#view-block-nombre").removeClass('d-none');
                        $("#view-block-apellido").removeClass('d-none');
                        $("#view-block-razon-social").addClass('d-none');
                        $("#view-nombre").text(data.nombre || 'N/A');
                        $("#view-apellido").text(data.apellido || 'N/A');
                    } else {
                        $("#view-block-nombre").addClass('d-none');
                        $("#view-block-apellido").addClass('d-none');
                        $("#view-block-razon-social").removeClass('d-none');
                        $("#view-razon-social").text(data.nombre || 'N/A');
                    }
                    $("#view-tipo_cliente").text(tipoTexto);
                    $("#view-email").text(data.email || 'N/A');
                    $("#view-telefono").text(data.telefono || 'N/A');
                    $("#view-documento").text(data.documento || 'N/A');
                    $("#view-direccion").text(data.direccion || 'N/A');
                    $("#view-estado-territorial").text(data.estado_territorial || 'N/A');
                    $("#view-ciudad").text(data.ciudad || 'N/A');
                    $("#view-estatus").html(data.estatus == 1 ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>');
                    $("#view-created").text(formatDate(data.created_at));
                });
            });
            $(document).on("click", ".edit-item-btn", function () {
                var id = $(this).data("id");
                $.get("{{ route('clientes.edit', ':id') }}".replace(':id', id), function (data) {
                    setEditMode();
                    $("#id-field").val(data.id);
                    $("#nombre-field").val(data.nombre || '');
                    $("#apellido-field").val(data.apellido || '');
                    $("#tipo_cliente-field").val(data.tipo_cliente);
                    // Actualizar visibilidad de campos según tipo
                    toggleClienteFields();
                    // Si es Jurídico/Gubernamental, llenar Razón Social con nombre
                    if (data.tipo_cliente === 'juridico' || data.tipo_cliente === 'gubernamental') {
                        $("#razon-social-field").val(data.nombre || '');
                    }
                    $("#email-field").val(data.email || '');
                    // Separar teléfono en prefijo y número
                    if (data.telefono && data.telefono.includes('-')) {
                        var telParts = data.telefono.split('-');
                        $("#telefono-prefix-field").val(telParts[0]);
                        $("#telefono-number-field").val(telParts[1]);
                    } else if (data.telefono) {
                        // Si no tiene guión, asumir formato 0424XXXXXXX
                        $("#telefono-prefix-field").val(data.telefono.slice(0, 4));
                        $("#telefono-number-field").val(data.telefono.slice(4));
                    }
                    if (data.documento) {
                        $("#documento-prefix-field").val(data.documento.slice(0, 2));
                        $("#documento-number-field").val(data.documento.slice(2));
                    }
                    $("#direccion-field").val(data.direccion || '');

                    // Primero establecer el estado
                    $("#estado_territorial-field").val(data.estado_territorial || '');

                    // Poblar los municipios del estado seleccionado
                    const estado = data.estado_territorial || '';
                    const municipios = getMunicipios(estado);
                    const ciudadSelect = $("#ciudad-field");
                    ciudadSelect.empty();
                    if (estado === '') {
                        ciudadSelect.append('<option value="">Primero seleccione un estado</option>');
                    } else {
                        ciudadSelect.append('<option value="">Seleccione municipio</option>');
                        municipios.forEach(function (municipio) {
                            ciudadSelect.append('<option value="' + municipio + '">' + municipio + '</option>');
                        });
                    }

                    // Ahora seleccionar el municipio guardado
                    $("#ciudad-field").val(data.ciudad || '');
                    // Manejar checkbox de estatus
                    if (data.estatus == 1) {
                        $("#estatus-field").prop('checked', true);
                        $("#estatus-label").text('Activo');
                    } else {
                        $("#estatus-field").prop('checked', false);
                        $("#estatus-label").text('Inactivo');
                    }
                    $("#showModal").modal("show");
                });
            });
            $(document).on("click", ".remove-item-btn", function () {
                var id = $(this).data("id");
                window.scrollTo(0, 0);
                document.activeElement.blur();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "El cliente será inhabilitado y moverá al historial.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, inhabilitar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2',
                        cancelButton: 'btn btn-danger w-xs'
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('clientes.destroy', ':id') }}".replace(':id', id),
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name=\'csrf-token\']').attr('content')
                            },
                            success: function (response) {
                                table.ajax.reload();
                                // Mostrar mensaje con warning si el cliente tenía relaciones
                                if (response.warning) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Cliente Inhabilitado',
                                        html: '<p>' + response.message + '</p><p class="text-muted small">' + response.warning + '</p>'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Inhabilitado',
                                        text: response.message
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON.message || 'Ocurrió un error al inhabilitar el cliente'
                                });
                            }
                        });
                    }
                });
            });
            $("#create-btn").click(function () {
                $('#id-field').val('');
                $('#clienteForm')[0].reset();
                $('#modalTitle').text('Agregar Cliente');
                $('#add-btn').show();
                $('#edit-btn').hide();
                validator.resetValidation();
            });
            $("#edit-btn").on("click", function () { $("#clienteForm").submit(); });
        });
    </script>
@endpush