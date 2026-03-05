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
                <h4 class="mb-sm-0">Gestión de Proveedores</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión General</a></li>
                        <li class="breadcrumb-item active">Proveedores</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <style>
        .card-body {
            overflow-x: auto;
        }

        /* Estilo para buscador personalizado */
        .search-box {
            position: relative;
        }

        .search-box .search-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #878a99;
        }

        .search-box input {
            padding-left: 30px;
        }

        /* ========== DATATABLE — ESTÁNDAR ATLÁNTICO ========== */
        #proveedores-table {
            width: 100% !important;
            font-size: 13px;
            table-layout: fixed;
        }

        #proveedores-table th,
        #proveedores-table td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Anchos de columna balanceados (100%) */
        #proveedores-table th:nth-child(1) {
            width: 14%;
        }

        #proveedores-table th:nth-child(2) {
            width: 26%;
        }

        #proveedores-table th:nth-child(3) {
            width: 12%;
        }

        #proveedores-table th:nth-child(4) {
            width: 14%;
        }

        #proveedores-table th:nth-child(5) {
            width: 20%;
        }

        #proveedores-table th:nth-child(6) {
            width: 14%;
            text-align: center;
        }

        #proveedores-table td:last-child {
            text-align: center;
            overflow: visible;
        }

        #proveedores-table thead th {
            background: #1e3c72 !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-color: #2a5298 !important;
        }

        #proveedores-table td:nth-child(5) {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #proveedores-table.dataTable tbody tr {
            transition: background-color 0.16s ease;
        }

        #proveedores-table.dataTable tbody tr td {
            border-top: 1px solid rgba(30, 60, 114, 0.07);
            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
            background-clip: padding-box;
        }

        #proveedores-table.dataTable tbody tr.odd td {
            background-color: #ffffff;
        }

        #proveedores-table.dataTable tbody tr.even td {
            background-color: rgba(30, 60, 114, 0.065);
        }

        #proveedores-table.dataTable tbody tr:hover td {
            background-color: rgba(30, 60, 114, 0.14) !important;
        }

        [data-bs-theme="dark"] #proveedores-table.dataTable tbody tr td {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        }

        [data-bs-theme="dark"] #proveedores-table.dataTable tbody tr.odd td {
            background-color: rgba(255, 255, 255, 0.015);
        }

        [data-bs-theme="dark"] #proveedores-table.dataTable tbody tr.even td {
            background-color: rgba(42, 82, 152, 0.2);
        }

        [data-bs-theme="dark"] #proveedores-table.dataTable tbody tr:hover td {
            background-color: rgba(42, 82, 152, 0.34) !important;
        }

        /* Badges de tipo de proveedor */
        .badge-tipo {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .badge-tipo-natural {
            background-color: rgba(41, 156, 219, 0.15);
            color: #299cdb;
        }
        .badge-tipo-juridico {
            background-color: rgba(111, 66, 193, 0.15);
            color: #6f42c1;
        }

        /* Campo protegido (readonly en edición) */
        .campo-protegido {
            background-color: #f0f0f0 !important;
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
        }

        .atlantico-modal .modal-content {
            border: 1px solid rgba(30, 60, 114, 0.16);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 14px 34px rgba(15, 35, 70, 0.16);
        }

        .atlantico-modal .modal-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
            border-bottom: 0;
            padding: 0.85rem 1rem !important;
        }

        .atlantico-modal .modal-header .modal-title {
            color: #fff;
            font-weight: 700;
        }

        .atlantico-modal .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.85;
        }

        .atlantico-modal .modal-header .btn-close:hover {
            opacity: 1;
        }

        .atlantico-modal .modal-body {
            padding: 1.2rem;
        }

        .atlantico-modal .modal-footer {
            background: #f8f9fa;
            border-top: 1px solid rgba(30, 60, 114, 0.08) !important;
            padding: 0.75rem 1rem;
        }

        .modal-form-section {
            border: 1px solid rgba(30, 60, 114, 0.12);
            border-radius: 0.65rem;
            padding: 0.9rem;
            background: rgba(30, 60, 114, 0.025);
            margin-bottom: 0.9rem;
        }

        .modal-form-section-title {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Proveedores</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar proveedor...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <div class="d-flex gap-2">
                                @if(Auth::user()->isAdmin())
                                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                        data-bs-target="#showModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Agregar Proveedor
                                    </button>
                                @endif
                                <a href="{{ route('proveedores.reporte.pdf') }}" class="btn btn-danger" target="_blank">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="proveedores-table" class="table table-bordered table-striped table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre/Razón Social</th>
                                <th>Tipo</th>
                                <th>Teléfono</th>
                                <th>Email</th>
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

    <!-- Modal para ver detalles -->
    <div class="modal fade atlantico-modal" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detalles del Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Columna Izquierda: Datos del Proveedor -->
                        <div class="col-lg-6">
                            <!-- Card Datos del Proveedor -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-store-2-line me-2"></i>Datos del Proveedor
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        {{-- Proveedor Natural: Nombre + Apellido en 2 columnas --}}
                                        <div class="col-6" id="view-block-prov-nombre">
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
                                        <div class="col-6" id="view-block-prov-apellido">
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
                                        {{-- Proveedor Jurídico: Razón Social en ancho completo --}}
                                        <div class="col-12 d-none" id="view-block-prov-razon-social">
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
                                        {{-- Tipo: siempre visible --}}
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-settings-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Tipo</small>
                                                    <span class="fw-semibold" id="view-tipo">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Documento: label dinámico (Cédula / RIF) --}}
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-bank-card-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block" id="view-label-documento">Documento</small>
                                                    <span class="fw-semibold" id="view-documento">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Ubicación -->
                            <div class="card border-0 shadow-sm">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Contacto y Estado -->
                        <div class="col-lg-6">
                            <!-- Card Contacto -->
                            <div class="card border-0 shadow-sm mb-4">
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
                                        <div class="col-12" id="view-contacto-section">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-follow-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Persona de Contacto</small>
                                                    <span class="fw-semibold" id="view-contacto">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12" id="view-telefono-contacto-section">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-smartphone-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Teléfono de Contacto</small>
                                                    <span class="fw-semibold" id="view-telefono-contacto">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Estado -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-information-line me-2"></i>Registro
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-calendar-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Fecha de Registro</small>
                                                    <span class="fw-semibold" id="view-created">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-checkbox-circle-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Estatus</small>
                                                    <span class="fw-semibold" id="view-estatus">-</span>
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
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="modalTitle">Agregar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="proveedorForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />

                        <div class="modal-form-section">
                            <div class="modal-form-section-title"><i class="ri-fingerprint-line"></i>Identificación</div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <x-forms.select name="tipo_proveedor" label="Tipo de Proveedor" required id="tipo-proveedor-field"
                                        :options="['juridico' => 'Jurídico (Empresa)', 'natural' => 'Natural (Persona)']"
                                        placeholder="" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block">Estatus</label>
                                    <div class="form-check form-switch form-switch-success mt-2">
                                        <input type="hidden" name="estado" value="0" />
                                        <input class="form-check-input" type="checkbox" role="switch" id="estado-field"
                                            name="estado" value="1" checked />
                                        <label class="form-check-label" for="estado-field" id="estado-label">Activo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CAMPOS PARA PROVEEDOR JURÍDICO (EMPRESA) -->
                        <div id="campos-juridico">
                            <div class="modal-form-section">
                                <div class="modal-form-section-title"><i class="ri-building-line"></i>Datos Empresariales</div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="rif_number" label="RIF" id="rif-number-field" placeholder="Ej: 123456789" maxlength="9" required prependRaw="true">
                                            <x-slot:prepend>
                                                <select class="form-select" id="rif-prefix-field" style="max-width: 80px;">
                                                    <option value="J-">J-</option>
                                                    <option value="G-">G-</option>
                                                </select>
                                            </x-slot:prepend>
                                        </x-forms.input>
                                        <input type="hidden" id="rif-field" name="rif" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="razon_social" label="Razón Social" maxlength="200" placeholder="Nombre de la empresa" id="razon-social-field" />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <x-forms.input name="direccion" label="Dirección" maxlength="500" placeholder="Dirección de la empresa" id="direccion-jur-field" />
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="telefono_jur_number" label="Teléfono" id="telefono-jur-number-field" maxlength="7" placeholder="1234567" required prependRaw="true">
                                            <x-slot:prepend>
                                                <select class="form-select" id="telefono-jur-prefix-field"
                                                    style="max-width: 100px; min-width: 100px;">
                                                    <option value="0212">0212</option>
                                                    <option value="0251">0251</option>
                                                    <option value="0241">0241</option>
                                                    <option value="0255">0255</option>
                                                    <option value="0412">0412</option>
                                                    <option value="0414">0414</option>
                                                    <option value="0424" selected>0424</option>
                                                    <option value="0416">0416</option>
                                                    <option value="0426">0426</option>
                                                </select>
                                            </x-slot:prepend>
                                        </x-forms.input>
                                        <input type="hidden" id="telefono-jur-field" name="telefono" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="email" label="Email" type="email" placeholder="correo@empresa.com" id="email-jur-field" />
                                    </div>
                                </div>
                            </div>

                            <div class="modal-form-section">
                                <div class="modal-form-section-title"><i class="ri-user-follow-line"></i>Contacto Secundario</div>

                                <div class="row mb-0">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="contacto" label="Persona de Contacto" maxlength="100" placeholder="Nombre del contacto" id="contacto-field" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="telefono_contacto_number" label="Teléfono de Contacto" id="telefono-contacto-number-field" maxlength="7" placeholder="1234567" prependRaw="true">
                                            <x-slot:prepend>
                                                <select class="form-select" id="telefono-contacto-prefix-field"
                                                    style="max-width: 100px; min-width: 100px;">
                                                    <option value="0412">0412</option>
                                                    <option value="0414">0414</option>
                                                    <option value="0424" selected>0424</option>
                                                    <option value="0416">0416</option>
                                                    <option value="0426">0426</option>
                                                </select>
                                            </x-slot:prepend>
                                        </x-forms.input>
                                        <input type="hidden" id="telefono-contacto-field" name="telefono_contacto" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CAMPOS PARA PROVEEDOR NATURAL (PERSONA) -->
                        <div id="campos-natural" style="display: none;">
                            <div class="modal-form-section">
                                <div class="modal-form-section-title"><i class="ri-user-3-line"></i>Datos Personales</div>

                                <div class="row mb-0">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="nombre" label="Nombre" maxlength="100" placeholder="Nombre" id="nombre-field" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="apellido" label="Apellido" maxlength="100" placeholder="Apellido" id="apellido-field" />
                                    </div>
                                </div>
                            </div>

                            <div class="modal-form-section">
                                <div class="modal-form-section-title"><i class="ri-contacts-book-line"></i>Contacto</div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="documento_identidad_number" label="Documento de Identidad" id="documento-identidad-field" maxlength="8" placeholder="Ej: 12345678" required prependRaw="true">
                                            <x-slot:prepend>
                                                <select class="form-select" id="tipo-documento-field" name="tipo_documento"
                                                    style="max-width: 80px;">
                                                    <option value="V-">V-</option>
                                                    <option value="E-">E-</option>
                                                </select>
                                            </x-slot:prepend>
                                        </x-forms.input>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-forms.input name="telefono_nat_number" label="Teléfono" id="telefono-nat-number-field" maxlength="7" placeholder="1234567" required prependRaw="true">
                                            <x-slot:prepend>
                                                <select class="form-select" id="telefono-nat-prefix-field"
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
                                        <input type="hidden" id="telefono-nat-field" name="telefono" />
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <x-forms.input name="email" label="Email" type="email" placeholder="correo@email.com" id="email-nat-field" />
                                </div>

                                <div class="mb-0">
                                    <x-forms.input name="direccion" label="Dirección" maxlength="255" placeholder="Dirección completa" id="direccion-nat-field" />
                                </div>
                            </div>

                            <div class="modal-form-section mb-0">
                                <div class="modal-form-section-title"><i class="ri-map-pin-2-line"></i>Ubicación</div>

                                <div class="row mb-0">
                                    <div class="col-md-6 mb-3">
                                        <label for="estado-territorial-field" class="form-label">Estado</label>
                                        <select id="estado-territorial-field" name="estado_territorial" class="form-select">
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
                                    <div class="col-md-6 mb-3">
                                        <label for="ciudad-field" class="form-label">Municipio</label>
                                        <select id="ciudad-field" name="ciudad" class="form-select">
                                            <option value="">Primero seleccione un estado</option>
                                        </select>
                                    </div>
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
                            <x-ui.button-save id="edit-btn" text="Actualizar" icon="ri-save-line" loading-text="Actualizando..." style="display: none;" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/municipios-venezuela.js') }}"></script>

    <script>
        $(document).ready(function () {
            function generateButtons(proveedorId) {
                var isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
                var editDeleteBtns = isAdmin
                    ? '<button class="btn btn-sm btn-soft-success edit-item-btn" data-id="' + proveedorId + '" title="Editar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-pencil-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-danger remove-item-btn" data-id="' + proveedorId + '" title="Eliminar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-delete-bin-fill" style="font-size:13px;"></i>' +
                    '</button>'
                    : '';

                return '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + proveedorId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    editDeleteBtns +
                    '</div>';
            }

            // Toggle campos según tipo de proveedor
            function toggleCampos() {
                var tipo = $('#tipo-proveedor-field').val();
                if (tipo === 'natural') {
                    $('#campos-juridico').hide();
                    $('#campos-natural').show();
                    // Limpiar campos jurídicos
                    $('#rif-number-field, #razon-social-field, #direccion-jur-field, #telefono-jur-field, #email-jur-field, #contacto-field, #telefono-contacto-field').val('');
                } else {
                    $('#campos-juridico').show();
                    $('#campos-natural').hide();
                    // Limpiar campos naturales
                    $('#nombre-field, #apellido-field, #documento-identidad-field, #telefono-nat-field, #email-nat-field, #direccion-nat-field, #ciudad-field, #estado-territorial-field').val('');
                }
            }

            $('#tipo-proveedor-field').on('change', toggleCampos);

            // Listener para actualizar label del checkbox de estatus
            $("#estado-field").on('change', function () {
                if ($(this).is(':checked')) {
                    $("#estado-label").text('Activo');
                } else {
                    $("#estado-label").text('Inactivo');
                }
            });

            // Dropdown dependiente: Poblar municipios cuando cambia el estado
            $("#estado-territorial-field").on('change', function () {
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

            var table = $('#proveedores-table').DataTable({
                ajax: { url: "{{ route('proveedores.data') }}", dataSrc: 'data' },
                columns: [
                    { data: 'documento_display', name: 'rif' },
                    { data: 'nombre_display', name: 'razon_social' },
                    {
                        data: 'tipo_display',
                        name: 'tipo_proveedor',
                        render: function (data, type, row) {
                            if (row.tipo_proveedor === 'natural') {
                                return '<span class="badge-tipo badge-tipo-natural"><i class="ri-user-line"></i> Natural</span>';
                            }
                            return '<span class="badge-tipo badge-tipo-juridico"><i class="ri-building-line"></i> Jurídico</span>';
                        }
                    },
                    { data: 'telefono_display', name: 'telefono' },
                    {
                        data: 'email_display',
                        name: 'email',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">—</span>';
                            return '<span title="' + data + '" style="cursor:default;">' + data + '</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return generateButtons(data);
                        }
                    }
                ],
                order: [[0, 'asc']],
                dom: 'rtip',
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                }
            });

            // Buscador personalizado
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Ver detalles
            $(document).on('click', '.view-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('proveedores.show', ':id') }}".replace(':id', id), function (data) {
                    var tipoText = data.tipo_proveedor === 'natural' ? 'Natural (Persona)' : 'Jurídico (Empresa)';
                    $("#view-tipo").text(tipoText);

                    // Layout dinámico según tipo de proveedor
                    if (data.tipo_proveedor === 'natural') {
                        $("#view-block-prov-nombre").removeClass('d-none');
                        $("#view-block-prov-apellido").removeClass('d-none');
                        $("#view-block-prov-razon-social").addClass('d-none');
                        $("#view-nombre").text(data.nombre || 'N/A');
                        $("#view-apellido").text(data.apellido || 'N/A');
                        $("#view-label-documento").text('Documento de Identidad');
                        $("#view-documento").text(data.documento_display || data.documento_identidad || 'N/A');
                    } else {
                        $("#view-block-prov-nombre").addClass('d-none');
                        $("#view-block-prov-apellido").addClass('d-none');
                        $("#view-block-prov-razon-social").removeClass('d-none');
                        $("#view-razon-social").text(data.razon_social || 'N/A');
                        $("#view-label-documento").text('RIF');
                        $("#view-documento").text(data.rif || 'N/A');
                    }

                    $("#view-telefono").text(data.telefono);
                    $("#view-email").text(data.email || 'No especificado');
                    $("#view-direccion").text(data.direccion || 'No especificada');
                    $("#view-estatus").html(data.estado == 1 ?
                        '<span class="badge bg-success">Activo</span>' :
                        '<span class="badge bg-danger">Inactivo</span>');

                    // Mostrar/ocultar campos de contacto según tipo
                    if (data.tipo_proveedor === 'juridico') {
                        $("#view-contacto-section").show();
                        $("#view-telefono-contacto-section").show();
                        $("#view-contacto").text(data.contacto || 'No especificado');
                        $("#view-telefono-contacto").text(data.telefono_contacto || 'No especificado');
                    } else {
                        $("#view-contacto-section").hide();
                        $("#view-telefono-contacto-section").hide();
                    }

                    $("#view-created").text(data.created_at);
                    $("#viewModal").modal('show');
                });
            });

            // Editar
            $(document).on('click', '.edit-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('proveedores.show', ':id') }}".replace(':id', id), function (data) {
                    $("#modalTitle").text("Editar Proveedor");
                    $("#id-field").val(data.id);
                    $("#tipo-proveedor-field").val(data.tipo_proveedor || 'juridico');
                    $("#estado-field").val(data.estado ? '1' : '0');

                    toggleCampos();

                    if (data.tipo_proveedor === 'natural') {
                        // Cargar datos de persona natural
                        $("#nombre-field").val(data.nombre);
                        $("#apellido-field").val(data.apellido);
                        $("#tipo-documento-field").val(data.tipo_documento || 'V-');
                        $("#documento-identidad-field").val(data.documento_identidad);

                        // Separar teléfono en prefijo y número
                        var telefono = data.telefono || '';
                        var telMatch = telefono.match(/^(0412|0422|0414|0424|0416|0426)-(.+)$/);
                        if (telMatch) {
                            $("#telefono-nat-prefix-field").val(telMatch[1]);
                            $("#telefono-nat-number-field").val(telMatch[2]);
                        } else {
                            $("#telefono-nat-prefix-field").val('0424');
                            $("#telefono-nat-number-field").val(telefono.replace(/^0\d{3}-?/, ''));
                        }

                        $("#email-nat-field").val(data.email);
                        $("#direccion-nat-field").val(data.direccion);

                        // Cargar estado y luego municipio
                        if (data.estado_territorial) {
                            $("#estado-territorial-field").val(data.estado_territorial);
                            // Disparar evento change para cargar municipios
                            $("#estado-territorial-field").trigger('change');
                            // Esperar a que se carguen los municipios y seleccionar el correcto
                            setTimeout(function () {
                                $("#ciudad-field").val(data.ciudad);
                            }, 100);
                        }
                    } else {
                        // Cargar datos de empresa jurídica
                        var rif = data.rif || '';
                        var rifMatch = rif.match(/^(V-|J-|E-|G-)(.+)$/);
                        if (rifMatch) {
                            $("#rif-prefix-field").val(rifMatch[1]);
                            $("#rif-number-field").val(rifMatch[2]);
                        } else {
                            $("#rif-prefix-field").val('J-');
                            $("#rif-number-field").val(rif);
                        }
                        $("#razon-social-field").val(data.razon_social);
                        $("#direccion-jur-field").val(data.direccion);

                        // Separar teléfono principal en prefijo y número
                        var telJur = data.telefono || '';
                        var telJurMatch = telJur.match(/^(0212|0251|0241|0255|0412|0414|0424|0416|0426)-(.+)$/);
                        if (telJurMatch) {
                            $("#telefono-jur-prefix-field").val(telJurMatch[1]);
                            $("#telefono-jur-number-field").val(telJurMatch[2]);
                        } else {
                            $("#telefono-jur-prefix-field").val('0424');
                            $("#telefono-jur-number-field").val(telJur.replace(/^0\d{3}-?/, ''));
                        }

                        $("#email-jur-field").val(data.email);
                        $("#contacto-field").val(data.contacto);

                        // Separar teléfono de contacto en prefijo y número
                        var telContacto = data.telefono_contacto || '';
                        var telContactoMatch = telContacto.match(/^(0412|0414|0424|0416|0426)-(.+)$/);
                        if (telContactoMatch) {
                            $("#telefono-contacto-prefix-field").val(telContactoMatch[1]);
                            $("#telefono-contacto-number-field").val(telContactoMatch[2]);
                        } else {
                            $("#telefono-contacto-prefix-field").val('0424');
                            $("#telefono-contacto-number-field").val(telContacto.replace(/^0\d{3}-?/, ''));
                        }
                    }

                    $("#add-btn").hide();
                    $("#edit-btn").show();

                    // Bloquear edición de documento y tipo de proveedor
                    $("#tipo-proveedor-field").prop('disabled', true).addClass('campo-protegido');
                    if (data.tipo_proveedor === 'natural') {
                        $("#tipo-documento-field").prop('disabled', true).addClass('campo-protegido');
                        $("#documento-identidad-field").prop('disabled', true).addClass('campo-protegido');
                    } else {
                        $("#rif-prefix-field").prop('disabled', true).addClass('campo-protegido');
                        $("#rif-number-field").prop('disabled', true).addClass('campo-protegido');
                    }

                    $("#showModal").modal('show');
                });
            });

            // Enviar formulario
            $("#proveedorForm").on("submit", function (e) {
                e.preventDefault();

                var id = $("#id-field").val();
                var url = id ? "{{ route('proveedores.update', ':id') }}".replace(':id', id) : "{{ route('proveedores.store') }}";
                var method = id ? "PUT" : "POST";
                var tipo = $('#tipo-proveedor-field').val();

                var formData = new FormData(this);
                formData.set('tipo_proveedor', tipo);

                // Preparar datos según tipo
                if (tipo === 'juridico') {
                    var rifPrefix = $('#rif-prefix-field').val();
                    var rifNumber = $('#rif-number-field').val();
                    formData.set('rif', rifPrefix + rifNumber);

                    // Concatenar teléfono principal: prefijo-número
                    var telefonoJurCompleto = $('#telefono-jur-prefix-field').val() + '-' + $('#telefono-jur-number-field').val();
                    formData.set('telefono', telefonoJurCompleto);

                    formData.set('email', $('#email-jur-field').val());
                    formData.set('direccion', $('#direccion-jur-field').val());

                    // Concatenar teléfono de contacto
                    var telefonoContactoCompleto = $('#telefono-contacto-prefix-field').val() + '-' + $('#telefono-contacto-number-field').val();
                    formData.set('telefono_contacto', telefonoContactoCompleto);
                } else {
                    // Concatenar teléfono: prefijo-número
                    var telefonoCompleto = $('#telefono-nat-prefix-field').val() + '-' + $('#telefono-nat-number-field').val();
                    formData.set('telefono', telefonoCompleto);
                    formData.set('email', $('#email-nat-field').val());
                    formData.set('direccion', $('#direccion-nat-field').val());
                }

                if (method === "PUT") {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $("#showModal").modal('hide');
                        table.draw();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON?.errors || {};
                        var errorMessage = xhr.responseJSON?.error || '';
                        if (Object.keys(errors).length > 0) {
                            errorMessage = '';
                            $.each(errors, function (key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage || 'Ocurrió un error al procesar la solicitud'
                        });
                    }
                });
            });

            // Eliminar
            $(document).on("click", ".remove-item-btn", function () {
                var id = $(this).data("id");
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2',
                        cancelButton: 'btn btn-danger w-xs'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('proveedores.destroy', ':id') }}".replace(':id', id),
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: response.success,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo eliminar el proveedor'
                                });
                            }
                        });
                    }
                });
            });

            // Limpiar modal al cerrar
            $("#showModal").on("hidden.bs.modal", function () {
                $("#modalTitle").text("Agregar Proveedor");
                $("#proveedorForm")[0].reset();
                $("#id-field").val("");
                $("#tipo-proveedor-field").val("juridico");
                toggleCampos();
                $("#add-btn").show();
                $("#edit-btn").hide();
                // Desbloquear campos de documento
                $("#tipo-proveedor-field").prop('disabled', false).removeClass('campo-protegido');
                $("#rif-prefix-field").prop('disabled', false).removeClass('campo-protegido');
                $("#rif-number-field").prop('disabled', false).removeClass('campo-protegido');
                $("#tipo-documento-field").prop('disabled', false).removeClass('campo-protegido');
                $("#documento-identidad-field").prop('disabled', false).removeClass('campo-protegido');
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
                $('.invalid-feedback').hide();
                $('#add-btn').prop('disabled', false);
            });

            // Validaciones AJAX onblur

            // 1. RIF (Juridicio)
            $('#rif-number-field').on('blur', function () {
                let number = $(this).val();
                let prefix = $('#rif-prefix-field').val();
                let fullRif = prefix + number;
                let $input = $(this);
                let isEdit = $('#id-field').val() !== '';

                if (number.length > 5 && !isEdit) {
                    $.get("{{ route('proveedores.check-rif') }}", { rif: fullRif }, function (res) {
                        if (res.exists) {
                            $input.addClass('is-invalid');
                            // Agregar feedback si no existe
                            if ($input.next('.invalid-feedback').length === 0) {
                                $input.parent().after('<div class="invalid-feedback d-block">Este RIF ya está registrado</div>');
                            } else {
                                $input.next('.invalid-feedback').text('Este RIF ya está registrado').show();
                            }
                            $('#add-btn').prop('disabled', true);
                        } else {
                            $input.removeClass('is-invalid').addClass('is-valid');
                            $input.next('.invalid-feedback').hide();
                            $input.parent().next('.invalid-feedback').hide();
                            $('#add-btn').prop('disabled', false);
                        }
                    });
                }
            });

            // 2. Documento (Natural)
            $('#documento-identidad-field').on('blur', function () {
                let val = $(this).val();
                let $input = $(this);
                let isEdit = $('#id-field').val() !== '';

                if (val.length > 5 && !isEdit) {
                    $.get("{{ route('proveedores.check-documento') }}", { numero: val }, function (res) {
                        if (res.exists) {
                            $input.addClass('is-invalid');
                            if ($input.parent().next('.invalid-feedback').length === 0) {
                                $input.parent().after('<div class="invalid-feedback d-block">Este documento ya está registrado</div>');
                            } else {
                                $input.parent().next('.invalid-feedback').text('Este documento ya está registrado').show();
                            }
                            $('#add-btn').prop('disabled', true);
                        } else {
                            $input.removeClass('is-invalid').addClass('is-valid');
                            $input.parent().next('.invalid-feedback').hide();
                            $('#add-btn').prop('disabled', false);
                        }
                    });
                }
            });

            // 3. Email (Ambos)
            $('#email-jur-field, #email-nat-field').on('blur', function () {
                let val = $(this).val();
                let $input = $(this);
                let isEdit = $('#id-field').val() !== '';

                if (val.includes('@') && !isEdit) {
                    $.get("{{ route('proveedores.check-email') }}", { email: val }, function (res) {
                        if (res.exists) {
                            $input.addClass('is-invalid');
                            if ($input.next('.invalid-feedback').length === 0) {
                                $input.after('<div class="invalid-feedback">Este correo ya está registrado</div>');
                            } else {
                                $input.next('.invalid-feedback').text('Este correo ya está registrado').show();
                            }
                            $('#add-btn').prop('disabled', true);
                        } else {
                            $input.removeClass('is-invalid').addClass('is-valid');
                            $input.next('.invalid-feedback').hide();
                            $('#add-btn').prop('disabled', false);
                        }
                    });
                }
            });

        });
    </script>
@endpush