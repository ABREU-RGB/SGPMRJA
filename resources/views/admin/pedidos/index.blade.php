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
    <!-- Autocorrecci�n y Autocompletado -->
    <link href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
    {{-- Estilos en public/assets/css/custom.css — sección "MÓDULO PEDIDOS" --}}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Pedidos</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión Operativa</a></li>
                        <li class="breadcrumb-item active">Pedidos</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Estilos en public/assets/css/custom.css — secciones "UTILIDADES GLOBALES" y "MÓDULO PEDIDOS" --}}

    <!-- Modal para seleccionar producto -->
    <div class="modal fade" id="productosModal" tabindex="-1" aria-labelledby="productosModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Header — Nivel 2: Modal utilitario de búsqueda (Pedidos) -->
                <div class="modal-header utility-modal-header p-3" id="productosModal-header">
                    <h5 class="modal-title" id="productosModalLabel">
                        <i class="ri-search-line me-2" style="opacity:0.7;"></i>Buscar y Seleccionar Producto
                    </h5>
                    <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Card de Filtros -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header border-0" style="background: rgba(0, 217, 165, 0.1);">
                            <h6 class="mb-0" style="color: #00d9a5;">
                                <i class="ri-filter-3-line me-2"></i>Filtros de BÃºsqueda
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <span class="input-group-text"
                                            style="background: rgba(30, 60, 114, 0.1); border-color: #1e3c72;">
                                            <i class="ri-search-line" style="color: #1e3c72;"></i>
                                        </span>
                                        <input type="text" id="buscarProductoModal" class="form-control"
                                            placeholder="Buscar por c�digo, tipo o modelo..."
                                            style="border-color: #1e3c72;">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <select id="filtroTipoProducto" class="form-select" style="border-color: #00d9a5;">
                                        <option value="">?? Todos los tipos</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card de Tabla de Productos -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                            <h6 class="mb-0" style="color: #1e3c72;">
                                <i class="ri-store-2-line me-2"></i>Cat�logo de Productos
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 350px;">
                                <table class="table table-hover mb-0 table-seleccionable" id="productosModalTable">
                                    <thead style="position: sticky; top: 0; z-index: 10;">
                                        <tr>
                                            <th style="width:8%;  border:none;" class="text-white text-center"><i
                                                    class="ri-image-line"></i></th>
                                            <th style="width:20%; border:none;" class="text-white"><i
                                                    class="ri-barcode-line me-1"></i>Código</th>
                                            <th style="width:20%; border:none;" class="text-white"><i
                                                    class="ri-folder-line me-1"></i>Tipo</th>
                                            <th style="width:28%; border:none;" class="text-white"><i
                                                    class="ri-t-shirt-line me-1"></i>Modelo</th>
                                            <th style="width:16%; border:none;" class="text-white"><i
                                                    class="ri-money-dollar-circle-line me-1"></i>Precio
                                            </th>
                                            <th style="width:8%;  border:none;" class="text-white text-center"><i
                                                    class="ri-check-line"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="productosModalBody">
                                        <!-- Se llena con JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card card-transactional">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Pedidos</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar pedido...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <!-- Botones de Acción -->
                            <div class="d-flex gap-2">
                                @if(Auth::user()->isAdmin())
                                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                        data-bs-target="#seleccionarCotizacionModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Agregar Pedido
                                    </button>
                                @endif
                                <a href="{{ route('pedidos.reporte.pdf') }}" target="_blank" class="btn btn-danger">
                                    <i class="ri-file-pdf-line align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="pedidos-table" class="table table-bordered table-striped table-sm align-middle dt-transactional table-operativa">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Cliente</th>
                                <th>Fecha Entrega</th>
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

    @include('admin.pedidos.modals.seleccionar_cotizacion')
    @include('admin.partials.catalog_modals')
    <!-- Modal para ver detalles del Pedido -->
    <div class="modal fade atlantico-modal atlantico-modal--op" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Columna Izquierda -->
                        <div class="col-lg-6">
                            <!-- Card Datos del Cliente -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0 bg-soft-primary">
                                    <h6 class="mb-0 text-atlantico-dark">
                                        <i class="ri-user-star-line me-2"></i>Información del Cliente
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Cliente</small>
                                                    <span class="fw-semibold" id="view-cliente-nombre">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(46, 204, 113, 0.1);">
                                                    <i class="ri-bank-card-line" style="color: #2ecc71;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Documento</small>
                                                    <span class="fw-semibold" id="view-ci-rif">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(0, 217, 165, 0.1);">
                                                    <i class="ri-mail-line" style="color: #00d9a5;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Email</small>
                                                    <span class="fw-semibold" id="view-cliente-email">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-phone-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Teléfono</small>
                                                    <span class="fw-semibold" id="view-cliente-telefono">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Datos del Pedido -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0 bg-soft-primary">
                                    <h6 class="mb-0 text-atlantico-dark">
                                        <i class="ri-calendar-todo-line me-2"></i>Datos del Pedido
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
                                                    <small class="text-muted d-block">Fecha Pedido</small>
                                                    <span class="fw-semibold" id="view-fecha-pedido">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-calendar-check-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Entrega Estimada</small>
                                                    <span class="fw-semibold" id="view-fecha-entrega-estimada">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-flag-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Prioridad</small>
                                                    <span class="fw-semibold" id="view-prioridad">-</span>
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
                                                    <small class="text-muted d-block">Estado</small>
                                                    <span class="fw-semibold" id="view-estado">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-user-settings-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Creado por</small>
                                                    <span class="fw-semibold" id="view-usuario-creador">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Datos del Pago -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-wallet-line me-2"></i>Datos del Pago
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-lg-6">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-bank-card-2-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Método Pago</small>
                                                    <div class="d-flex flex-wrap gap-1 mt-1" id="view-metodo-pago">-</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-hand-coin-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Abono</small>
                                                    <span class="fw-semibold" id="view-abono">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                    <i class="ri-wallet-2-line" style="color: #1e3c72;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Restante</small>
                                                    <span class="fw-semibold" id="view-restante">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-1">
                                        <div class="col-12 col-md-6" id="view-bloque-transferencia-container"
                                            style="display:none;">
                                            <div class="rounded p-2"
                                                style="background: rgba(30, 60, 114, 0.06); border: 1px dashed rgba(30, 60, 114, 0.2);">
                                                <span class="fw-bold d-block mb-2" style="font-size:0.9rem; color:#1e3c72;">
                                                    <i class="ri-bank-card-line me-1"
                                                        style="font-size:0.9rem;"></i>Transferencia
                                                </span>
                                                <div class="mb-1">
                                                    <small class="text-muted d-block">Banco</small>
                                                    <span class="fw-semibold" id="view-banco-transferencia">-</span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Referencia</small>
                                                    <span class="fw-semibold" id="view-referencia-transferencia">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6" id="view-bloque-pago-movil-container"
                                            style="display:none;">
                                            <div class="rounded p-2"
                                                style="background: rgba(30, 60, 114, 0.06); border: 1px dashed rgba(30, 60, 114, 0.2);">
                                                <span class="fw-bold d-block mb-2" style="font-size:0.9rem; color:#1e3c72;">
                                                    <i class="ri-smartphone-line me-1" style="font-size:0.9rem;"></i>Pago
                                                    Móvil
                                                </span>
                                                <div class="mb-1">
                                                    <small class="text-muted d-block">Banco</small>
                                                    <span class="fw-semibold" id="view-banco-pago-movil">-</span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Referencia</small>
                                                    <span class="fw-semibold" id="view-referencia-pago-movil">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0"
                                style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:38px;height:38px;background:rgba(255,255,255,0.15);">
                                                <i class="ri-money-dollar-circle-line text-white fs-5"></i>
                                            </div>
                                            <div>
                                                <span class="text-white-50 d-block"
                                                    style="font-size:0.7rem;letter-spacing:0.06em;text-transform:uppercase;">Total
                                                    Pedido</span>
                                                <small class="text-white-50" style="font-size:0.68rem;">
                                                    <i class="ri-refresh-line me-1"></i>Se actualiza automáticamente
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-white" style="font-size:1.8rem;line-height:1;"
                                                id="view-total-resumen">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Productos -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                    <h6 class="mb-0" style="color: #1e3c72;">
                                        <i class="ri-shopping-bag-3-line me-2"></i>Productos del Pedido
                                    </h6>
                                </div>
                                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                    <div id="view-productos-container">
                                        <!-- Productos se cargan dinámicamente -->
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
    <div class="modal fade atlantico-modal atlantico-modal--op" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Agregar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="pedidoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-3">
                        <input type="hidden" id="id-field" />
                        <input type="hidden" id="cliente-id-field" name="cliente_id" />
                        <div class="row g-3 align-items-start">
                            <div class="col-lg-5"
                                style="background: rgba(30, 60, 114, 0.025); border-radius: 8px; padding-top: 4px; padding-bottom: 4px;">

                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <span
                                                style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#132649;color:#fff;font-size:0.65rem;font-weight:700;margin-right:8px;">1</span>
                                            <i class="ri-user-3-line me-2"></i>Datos del Cliente
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label for="cliente-nombre-field" class="form-label small fw-semibold mb-1">
                                                    Nombre del cliente <span class="text-danger">*</span>
                                                </label>
                                                <div class="position-relative">
                                                    <div class="input-group">
                                                        <input type="text" id="cliente-nombre-field" name="cliente_nombre"
                                                            class="form-control" placeholder="Buscar cliente por nombre..."
                                                            autocomplete="off" required />
                                                        <button type="button" class="btn btn-outline-success"
                                                            id="open-add-cliente-modal" title="Agregar nuevo cliente">
                                                            <i class="ri-user-add-line"></i>
                                                        </button>
                                                    </div>
                                                    <div id="cliente-autocomplete-list"
                                                        class="list-group position-absolute w-100"
                                                        style="z-index: 1050; top: 100%;"></div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="ci-rif-number-field" class="form-label small fw-semibold mb-1">
                                                    Documento de identidad <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <select class="form-select" id="ci-rif-prefix-field"
                                                        name="ci_rif_prefix" style="max-width: 70px;">
                                                        <option value="V-">V-</option>
                                                        <option value="J-">J-</option>
                                                        <option value="E-">E-</option>
                                                        <option value="G-">G-</option>
                                                    </select>
                                                    <input type="text" id="ci-rif-number-field" name="ci_rif_number"
                                                        class="form-control" placeholder="Buscar cliente por documento..."
                                                        required />
                                                </div>
                                                <input type="hidden" id="ci-rif-full-field" name="ci_rif" />
                                            </div>

                                            <div class="col-6">
                                                <label for="cliente-email-field"
                                                    class="form-label small fw-semibold mb-1">Email</label>
                                                <input type="email" id="cliente-email-field" name="cliente_email"
                                                    class="form-control form-control-sm" placeholder="Correo del cliente" />
                                            </div>

                                            <div class="col-6">
                                                <label for="cliente-telefono-field"
                                                    class="form-label small fw-semibold mb-1">Teléfono</label>
                                                <input type="text" id="cliente-telefono-field" name="cliente_telefono"
                                                    class="form-control form-control-sm" placeholder="Número de teléfono" />
                                            </div>

                                            <div class="col-6">
                                                <label for="fecha-pedido-field"
                                                    class="form-label small fw-semibold mb-1">Fecha Pedido <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" id="fecha-pedido-field" name="fecha_pedido"
                                                    class="form-control form-control-sm" required />
                                            </div>

                                            <div class="col-6">
                                                <label for="fecha-entrega-estimada-field"
                                                    class="form-label small fw-semibold mb-1">Fecha Entrega Estimada</label>
                                                <input type="date" id="fecha-entrega-estimada-field"
                                                    name="fecha_entrega_estimada" class="form-control form-control-sm" />
                                            </div>

                                            <div class="col-12">
                                                <label for="prioridad-field"
                                                    class="form-label small fw-semibold mb-1">Prioridad</label>
                                                <select id="prioridad-field" name="prioridad"
                                                    class="form-select form-select-sm" required>
                                                    <option value="Normal">Normal</option>
                                                    <option value="Alta">Alta</option>
                                                    <option value="Urgente">Urgente</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm mb-3" id="estado-field-wrapper" style="display: none;">
                                    <div class="card-header border-0 bg-soft-secondary">
                                        <h6 class="mb-0 text-atlantico-cyan">
                                            <i class="ri-flag-line me-2"></i>Estado del Pedido
                                        </h6>
                                    </div>
                                    <div class="card-body py-2">
                                        <select id="estado-field" name="estado" class="form-select form-select-sm">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Procesando">Procesando</option>
                                            <option value="Completado">Completado</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm mb-2">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-wallet-line me-2"></i>Pago y Saldo
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label for="total-display-field"
                                                    class="form-label small fw-semibold mb-1">Total del Pedido
                                                    ($)</label>
                                                <div class="pago-kpi-box">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" id="total-display-field" class="form-control"
                                                            readonly />
                                                    </div>
                                                </div>
                                                <small class="text-muted d-block mt-1" style="font-size:0.68rem;">
                                                    <i class="ri-refresh-line me-1"></i>Se actualiza automáticamente
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="abono-field" class="form-label small fw-semibold mb-1">Abono
                                                    ($)</label>
                                                <div class="pago-kpi-box">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" id="abono-field" name="abono"
                                                            class="form-control" step="0.01" min="0" value="0" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="restante-display-field"
                                                    class="form-label small fw-semibold mb-1">Restante ($)</label>
                                                <div class="pago-kpi-box">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" id="restante-display-field" class="form-control"
                                                            readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label small fw-semibold mb-1">Método de Pago</label>
                                            <div class="metodo-toggle-group mt-1">
                                                <div class="form-check metodo-toggle">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="efectivo-pagado-field" name="efectivo_pagado" value="1">
                                                    <label class="form-check-label" for="efectivo-pagado-field">
                                                        <i class="ri-money-dollar-circle-line"></i>Efectivo
                                                    </label>
                                                </div>
                                                <div class="form-check metodo-toggle">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="transferencia-pagado-field" name="transferencia_pagado"
                                                        value="1">
                                                    <label class="form-check-label" for="transferencia-pagado-field">
                                                        <i class="ri-bank-card-line"></i>Transferencia
                                                    </label>
                                                </div>
                                                <div class="form-check metodo-toggle">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="pago-movil-pagado-field" name="pago_movil_pagado" value="1">
                                                    <label class="form-check-label" for="pago-movil-pagado-field">
                                                        <i class="ri-smartphone-line"></i>Pago Móvil
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2" id="referencia-transferencia-container">
                                            <div class="metodo-form-block">
                                                <div class="metodo-form-title"><i
                                                        class="ri-bank-card-line"></i>Transferencia</div>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label for="banco-transferencia-id-field"
                                                            class="form-label small fw-semibold mb-1"><i
                                                                class="ri-bank-line align-bottom me-1"></i>Banco
                                                            Transferencia</label>
                                                        <select id="banco-transferencia-id-field"
                                                            name="banco_transferencia_id"
                                                            class="form-select form-select-sm">
                                                            <option value="">Seleccione un banco</option>
                                                            @foreach($bancos as $banco)
                                                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="referencia-transferencia-field"
                                                            class="form-label small fw-semibold mb-1"><i
                                                                class="ri-numbers-line align-bottom me-1"></i>Referencia
                                                            Transferencia</label>
                                                        <input type="text" id="referencia-transferencia-field"
                                                            name="referencia_transferencia"
                                                            class="form-control form-control-sm"
                                                            placeholder="Número de referencia" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2" id="referencia-pago-movil-container">
                                            <div class="metodo-form-block">
                                                <div class="metodo-form-title"><i class="ri-smartphone-line"></i>Pago Móvil
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label for="banco-pago-movil-id-field"
                                                            class="form-label small fw-semibold mb-1"><i
                                                                class="ri-bank-line align-bottom me-1"></i>Banco
                                                            Pago Móvil</label>
                                                        <select id="banco-pago-movil-id-field" name="banco_pago_movil_id"
                                                            class="form-select form-select-sm">
                                                            <option value="">Seleccione un banco</option>
                                                            @foreach($bancos as $banco)
                                                                <option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="referencia-pago-movil-field"
                                                            class="form-label small fw-semibold mb-1"><i
                                                                class="ri-numbers-line align-bottom me-1"></i>Referencia
                                                            Pago Móvil</label>
                                                        <input type="text" id="referencia-pago-movil-field"
                                                            name="referencia_pago_movil"
                                                            class="form-control form-control-sm"
                                                            placeholder="Número de referencia" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm h-100">
                                    <div
                                        class="card-header border-0 bg-soft-primary d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <span
                                                style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#132649;color:#fff;font-size:0.65rem;font-weight:700;margin-right:8px;">2</span>
                                            <i class="ri-shopping-bag-3-line me-2"></i>Productos del Pedido
                                        </h6>
                                        <span class="badge bg-soft-info text-atlantico-dark"><i
                                                class="ri-link me-1"></i>Desde
                                            Cotización</span>
                                    </div>
                                    <div class="card-body p-2"
                                        style="max-height: 490px; overflow-y: auto; overflow-x: hidden;">
                                        <div id="productos-container">
                                            <!-- Los productos se cargan automáticamente desde la cotización -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0" style="background: #f8f9fa;">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal"
                                style="border:1px solid #dee2e6;">
                                <i class="ri-close-line me-1"></i>Cerrar
                            </button>
                            <button type="submit" class="btn btn-success px-4" id="add-btn">
                                <i class="ri-add-line me-1"></i>Agregar
                            </button>
                            <button type="submit" class="btn btn-success px-4" id="edit-btn" style="display: none;">
                                <i class="ri-save-line me-1"></i>Actualizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar/Editar Cliente (reutilizado) -->
    <div class="modal fade atlantico-modal atlantico-modal--op" id="modalAddCliente" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClienteTitle">Agregar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="clienteFormPedido">
                    <div class="modal-body">
                        <input type="hidden" id="id-field-cliente" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre-field-cliente" class="form-label">Nombre</label>
                                    <input type="text" id="nombre-field-cliente" name="nombre" class="form-control"
                                        placeholder="Nombre" required />
                                </div>
                                <div class="mb-3">
                                    <label for="tipo_cliente-field-cliente" class="form-label">Tipo de Cliente</label>
                                    <select id="tipo_cliente-field-cliente" name="tipo_cliente" class="form-control"
                                        required>
                                        <option value="">Seleccione tipo</option>
                                        <option value="natural">Natural</option>
                                        <option value="juridico">Jurídico</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="email-field-cliente" class="form-label">Email</label>
                                    <input type="email" id="email-field-cliente" name="email" class="form-control"
                                        placeholder="Email" />
                                </div>
                                <div class="mb-3">
                                    <label for="telefono-field-cliente" class="form-label">Teléfono</label>
                                    <input type="text" id="telefono-field-cliente" name="telefono" class="form-control"
                                        placeholder="Teléfono" required />
                                </div>
                                <div class="mb-3">
                                    <label for="documento-field-cliente" class="form-label">Documento (Cédula o
                                        RIF)</label>
                                    <input type="text" id="documento-field-cliente" name="documento" class="form-control"
                                        placeholder="Cédula o RIF" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion-field-cliente" class="form-label">Dirección</label>
                                    <input type="text" id="direccion-field-cliente" name="direccion" class="form-control"
                                        placeholder="Dirección" required />
                                </div>
                                <div class="mb-3">
                                    <label for="ciudad-field-cliente" class="form-label">Ciudad</label>
                                    <input type="text" id="ciudad-field-cliente" name="ciudad" class="form-control"
                                        placeholder="Ciudad" required />
                                </div>
                                <div class="mb-3">
                                    <label for="estado-field-cliente" class="form-label">Estado</label>
                                    <select name="estado" id="estado-field-cliente" class="form-control form-select">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
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
                            <button type="button" class="btn btn-success" id="add-btn-cliente">
                                <i class="ri-add-line me-1"></i>Agregar
                            </button>
                            <button type="button" class="btn btn-success" id="edit-btn-cliente" style="display: none;">
                                <i class="ri-save-line me-1"></i>Actualizar
                            </button>
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
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Librerías para Autocorrección y Autocompletado -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.6.2/dist/fuse.min.js"></script>

    <script>
        // === SISTEMA DE AUTOCORRECCIÓN Y AUTOCOMPLETADO ===

        // Diccionarios de datos comunes para autocorrección
        const commonNames = [
            'María', 'José', 'Ana', 'Carlos', 'Luis', 'Carmen', 'Antonio', 'Francisco', 'Manuel', 'Jesús',
            'David', 'Daniel', 'Miguel', 'Rafael', 'Pedro', 'Ángel', 'Alejandro', 'Diego', 'Andrés', 'Pablo',
            'Juan', 'Javier', 'Fernando', 'Sergio', 'Adrián', 'Alberto', 'Eduardo', 'Iván', 'Roberto', 'Rubén',
            'Ramón', 'Raúl', 'Enrique', 'Víctor', 'Jorge', 'Mario', 'Arturo', 'Ricardo', 'Emilio', 'Gonzalo',
            'Laura', 'Marta', 'Elena', 'Cristina', 'Pilar', 'Dolores', 'Isabel', 'Antonia', 'Teresa', 'Rosa',
            'Lucía', 'Sofía', 'Paula', 'Natalia', 'Silvia', 'Beatriz', 'Mónica', 'Raquel', 'Susana', 'Patricia'
        ];

        const commonCities = [
            'Caracas', 'Maracaibo', 'Valencia', 'Barquisimeto', 'Maracay', 'Ciudad Guayana', 'San Cristóbal',
            'Maturín', 'Ciudad Bolívar', 'Cumaná', 'Mérida', 'Barcelona', 'Turmero', 'Cabimas', 'Coro',
            'Punto Fijo', 'Los Teques', 'Guanare', 'Acarigua', 'Araure', 'El Tigre', 'Anaco', 'Puerto La Cruz',
            'Porlamar', 'La Guaira', 'Catia La Mar', 'Guarenas', 'Guatire', 'Charallave', 'Cúa', 'Ocumare del Tuy'
        ];

        const commonMaterials = [
            'Algodón', 'Poliéster', 'Seda', 'Lino', 'Denim', 'Lycra', 'Spandex', 'Nylon', 'Rayón', 'Viscosa',
            'Lana', 'Cashmere', 'Mohair', 'Alpaca', 'Bambú', 'Modal', 'Tencel', 'Microfibra', 'Polar', 'Franela',
            'Gabardina', 'Drill', 'Popelina', 'Oxford', 'Chambray', 'Crepe', 'Chiffon', 'Tafetán', 'Organza', 'Tul'
        ];

        const commonColors = [
            'Blanco', 'Negro', 'Azul', 'Rojo', 'Verde', 'Amarillo', 'Rosa', 'Morado', 'Naranja', 'Gris',
            'Marrón', 'Beige', 'Crema', 'Dorado', 'Plateado', 'Turquesa', 'Coral', 'Lavanda', 'Menta', 'Salmón',
            'Azul Marino', 'Verde Oliva', 'Rojo Vino', 'Rosa Pálido', 'Gris Claro', 'Gris Oscuro', 'Azul Cielo',
            'Verde Esmeralda', 'Púrpura', 'Magenta', 'Cian', 'Lima', 'Índigo', 'Violeta', 'Carmesí', 'Escarlata'
        ];

        // función para calcular distancia de Levenshtein (similitud entre strings)
        function levenshteinDistance(str1, str2) {
            const matrix = [];
            for (let i = 0; i <= str2.length; i++) {
                matrix[i] = [i];
            }
            for (let j = 0; j <= str1.length; j++) {
                matrix[0][j] = j;
            }
            for (let i = 1; i <= str2.length; i++) {
                for (let j = 1; j <= str1.length; j++) {
                    if (str2.charAt(i - 1) === str1.charAt(j - 1)) {
                        matrix[i][j] = matrix[i - 1][j - 1];
                    } else {
                        matrix[i][j] = Math.min(
                            matrix[i - 1][j - 1] + 1,
                            matrix[i][j - 1] + 1,
                            matrix[i - 1][j] + 1
                        );
                    }
                }
            }
            return matrix[str2.length][str1.length];
        }

        // función para encontrar sugerencias similares
        function findSimilarSuggestions(input, dictionary, threshold = 2) {
            if (!input || input.length < 2) return [];

            const suggestions = [];
            const inputLower = input.toLowerCase().trim();

            dictionary.forEach(word => {
                const wordLower = word.toLowerCase();
                const distance = levenshteinDistance(inputLower, wordLower);
                const similarity = 1 - (distance / Math.max(inputLower.length, wordLower.length));

                if (distance <= threshold && similarity > 0.6 && wordLower !== inputLower) {
                    suggestions.push({
                        word: word,
                        distance: distance,
                        similarity: similarity
                    });
                }
            });

            return suggestions.sort((a, b) => b.similarity - a.similarity).slice(0, 3);
        }

        // función para mostrar sugerencia de autocorrección
        function showAutocorrectSuggestion(field, suggestions) {
            hideAutocorrectSuggestion(field);

            if (suggestions.length === 0) return;

            const $field = $(field);
            const fieldOffset = $field.offset();
            const suggestion = suggestions[0]; // Tomar la mejor sugerencia

            const suggestionHtml = `
                                                                                                                                                                                                                                                                                                                                                                                        <div class="autocorrect-suggestion" data-field-id="${field.id}">
                                                                                                                                                                                                                                                                                                                                                                                            <div class="suggestion-text">
                                                                                                                                                                                                                                                                                                                                                                                                ¿Quisiste decir "<strong>${suggestion.word}</strong>"?
                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                            <div class="suggestion-buttons">
                                                                                                                                                                                                                                                                                                                                                                                                <button type="button" class="btn btn-sm btn-warning btn-suggestion accept-suggestion" data-suggestion="${suggestion.word}">
                                                                                                                                                                                                                                                                                                                                                                                                    Sí
                                                                                                                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                                                                                                                                <button type="button" class="btn btn-sm btn-secondary btn-suggestion ignore-suggestion">
                                                                                                                                                                                                                                                                                                                                                                                                    No
                                                                                                                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                   </d                                                                                                                                                                                                iv>
                                                                                                                                                                                                                                                                                                                                                    `;

            $('bo            dy').append(suggestionHtml);

            const $suggestionBox = $('.autocorrect-suggestion[data-field-id="' + field.id + '"]');
            $suggestionBox.css({
                top: fieldOffset.top + $field.outerHeight() + 5,
                left: fieldOffset.left
            });

            $field.addClass('field-with-suggestion');

            // Auto-hide después de 10 segundos
            setTimeout(() => {
                hideAutocorrectSuggestion(field);
            }, 10000);
        }

        // función para ocultar sugerencia de autocorrección
        function hideAutocorrectSuggestion(field) {
            $(field).removeClass('field-with-suggestion');
            $('.autocorrect-suggestion[data-field-id="' + field.id + '"]').remove();
        }

        // Eventos para manejar sugerencias
        $(document).on('click', '.accept-suggestion', function () {
            const suggestion = $(this).data('suggestion');
            const fieldId = $(this).closest('.autocorrect-suggestion').data('field-id');
            const field = document.getElementById(fieldId);

            $(field).val(suggestion).trigger('change');
            hideAutocorrectSuggestion(field);
        });

        $(document).on('click', '.ignore-suggestion', function () {
            const fieldId = $(this).closest('.autocorrect-suggestion').data('field-id');
            const field = document.getElementById(fieldId);
            hideAutocorrectSuggestion(field);
        });

        // función para aplicar autocorrección a un campo
        function applyAutocorrection(field, dictionary) {
            $(field).on('blur', function () {
                const value = $(this).val().trim();
                if (value.length >= 2) {
                    const suggestions = findSimilarSuggestions(value, dictionary);
                    if (suggestions.length > 0) {
                        showAutocorrectSuggestion(this, suggestions);
                    }
                }
            });
        }

        // función para aplicar autocompletado avanzado
        function applyAdvancedAutocomplete(field, dictionary, options = {}) {
            $(field).autocomplete({
                source: function (request, response) {
                    const fuse = new Fuse(dictionary, {
                        threshold: 0.4,
                        includeScore: true,
                        minMatchCharLength: 2
                    });

                    const results = fuse.search(request.term);
                    const suggestions = results.slice(0, 8).map(result => ({
                        label: result.item,
                        value: result.item,
                        score: result.score
                    }));

                    response(suggestions);
                },
                minLength: 2,
                delay: 300,
                appendTo: options.appendTo || 'body',
                select: function (event, ui) {
                    $(this).val(ui.item.value);
                    return false;
                }
            });
        }

        // función para normalizar texto (capitalizar nombres)
        function normalizeText(text, type = 'name') {
            if (!text) return text;

            switch (type) {
                case 'name':
                    return text.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
                case 'email':
                    return text.toLowerCase().trim();
                case 'city':
                    return text.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
                default:
                    return text.trim();
            }
        }

        // función para validar formato de email
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // función para validar formato de teléfono venezolano
        function validateVenezuelanPhone(phone) {
            const phoneRegex = /^(0414|0424|0412|0416|0426|0212|0241|0243|0244|0245|0246|0247|0248|0249|0251|0252|0253|0254|0255|0256|0257|0258|0259|0261|0262|0263|0264|0265|0266|0267|0268|0269|0271|0272|0273|0274|0275|0276|0277|0278|0279|0281|0282|0283|0284|0285|0286|0287|0288|0289|0291|0292|0293|0294|0295)\d{7}$/;
            return phoneRegex.test(phone.replace(/\D/g, ''));
        }

        // función para formatear teléfono venezolano
        function formatVenezuelanPhone(phone) {
            const cleaned = phone.replace(/\D/g, '');
            if (cleaned.length === 11) {
                return cleaned.replace(/(\d{4})(\d{3})(\d{4})/, '$1-$2-$3');
            }
            return phone;
        }

        // función para sugerir corrección de email
        function suggestEmailCorrection(email) {
            const commonDomains = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com', 'cantv.net'];
            const parts = email.split('@');

            if (parts.length !== 2) return null;

            const [username, domain] = parts;
            const suggestions = findSimilarSuggestions(domain, commonDomains, 1);

            if (suggestions.length > 0) {
                return `${username}@${suggestions[0].word}`;
            }

            return null;
        }

        $(document).ready(function () {
            // === INICIALIZACIÓN DEL SISTEMA DE AUTOCORRECCIÓN ===
            // Autocorrector desactivado por solicitud del usuario. Todas las funciónes de autocorrección han sido eliminadas/comentadas.
            // Puedes reactivar la autocorrección restaurando las llamadas a applyAutocorrection y showAutocorrectSuggestion si lo deseas en el futuro.
            // función para mostrar sugerencia de email
            function showEmailSuggestion(field, suggestion) {
                hideAutocorrectSuggestion(field);

                const $field = $(field);
                const fieldOffset = $field.offset();

                const suggestionHtml = `
                                                                                                                                                                                                                                                                                                                                                                                            <div class="autocorrect-suggestion" data-field-id="${field.id}">
                                                                                                                                                                                                                                                                                                                                                                                                <div class="suggestion-text">
                                                                                                                                                                                                                                                                                                                                                                                                    ¿Quisiste decir "<strong>${suggestion}</strong>"?
                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                <div class="suggestion-buttons">
                                                                                                                                                                                                                                                                                                                                                                                                    <button type="button" class="btn btn-sm btn-warning btn-suggestion accept-suggestion" data-suggestion="${suggestion}">
                                                                                                                                                                                                                                                                                                                                                                                                        Sí
                                                                                                                                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                                                                                                                                    <button type="button" class="btn btn-sm btn-secondary btn-suggestion ignore-suggestion">
                                                                                                                                                                                                                                                                                                                                                                                                        No
                                                                                                                                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                        `;

                $('body').append(suggestionHtml);

                const $suggestionBox = $('.autocorrect-suggestion[data-field-id="' + field.id + '"]');
                $suggestionBox.css({
                    top: fieldOffset.top + $field.outerHeight() + 5,
                    left: fieldOffset.left
                });

                $field.addClass('field-with-suggestion');

                setTimeout(() => {
                    hideAutocorrectSuggestion(field);
                }, 10000);
            }

            // función para mostrar error de formato de email
            function showEmailFormatError(field) {
                $(field).addClass('is-invalid');
                let feedback = $(field).siblings('.invalid-feedback');
                if (feedback.length === 0) {
                    $(field).after('<div class="invalid-feedback">Por favor ingrese un email válido</div>');
                } else {
                    feedback.text('Por favor ingrese un email válido');
                }
            }

            // función para mostrar error de formato de teléfono
            function showPhoneFormatError(field) {
                $(field).addClass('is-invalid');
                let feedback = $(field).siblings('.invalid-feedback');
                if (feedback.length === 0) {
                    $(field).after('<div class="invalid-feedback">Formato de teléfono inválido. Ejemplo: 0414-123-4567</div>');
                } else {
                    feedback.text('Formato de teléfono inválido. Ejemplo: 0414-123-4567');
                }
            }

            // Autocorrector desactivado por solicitud del usuario. No se inicializa autocorrección.
            // Limpiar sugerencias al cerrar modales
            $('#showModal, #modalAddCliente').on('hidden.bs.modal', function () {
                $('.autocorrect-suggestion').remove();
                $('.field-with-suggestion').removeClass('field-with-suggestion');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            var table = $('#pedidos-table').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: "{{ route('pedidos.data') }}",
                columns: [
                    { data: 'id', name: 'id', title: 'Pedido', className: 'text-center', width: '8%' },
                    { data: 'cliente_nombre_display', name: 'cliente_nombre_display', defaultContent: 'N/A', width: '30%' },
                    { data: 'fecha_entrega_estimada', name: 'fecha_entrega_estimada', width: '14%' },
                    {
                        data: 'total',
                        name: 'total',
                        width: '12%',
                        render: $.fn.dataTable.render.number(',', '.', 2, '$')
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        className: 'text-center',
                        width: '14%',
                        render: function (data, type, row) {
                            var estadoClasses = {
                                'Pendiente': 'status-pendiente',
                                'Procesando': 'status-procesando',
                                'Completado': 'status-completado',
                                'Cancelado': 'status-cancelado'
                            };
                            var estadoIcons = {
                                'Pendiente': 'ri-time-line',
                                'Procesando': 'ri-loader-4-line',
                                'Completado': 'ri-check-double-line',
                                'Cancelado': 'ri-close-circle-line'
                            };
                            var badgeClass = estadoClasses[data] || '';
                            var icon = estadoIcons[data] || 'ri-question-line';
                            return '<span class="badge badge-status ' + badgeClass + ' rounded-pill"><i class="' + icon + ' me-1"></i>' + data + '</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        width: '22%',
                        render: function (data, type, row) {
                            var isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
                            var editDelete = '';
                            if (isAdmin && row.estado !== 'Completado' && row.estado !== 'Cancelado') {
                                editDelete = `
                                                                                                                                            <button class="btn btn-sm btn-soft-success edit-btn" data-id="${data}" title="Editar">
                                                                                                                                                <i class="ri-pencil-fill"></i>
                                                                                                                                            </button>
                                                                                                                                            <button class="btn btn-sm btn-soft-danger remove-btn" data-id="${data}" title="Eliminar">
                                                                                                                                                <i class="ri-delete-bin-fill"></i>
                                                                                                                                            </button>`;
                            }
                            return `
                                                                                                                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                                                                                                                            <button class="btn btn-sm btn-soft-info view-btn" data-id="${data}" title="Ver">
                                                                                                                                                <i class="ri-eye-fill"></i>
                                                                                                                                            </button>
                                                                                                                                            ${editDelete}
                                                                                                                                            <a class="btn btn-sm btn-soft-secondary" href="/pedidos/${data}/pdf" target="_blank" title="PDF">
                                                                                                                                                <i class="ri-file-pdf-line"></i>
                                                                                                                                            </a>
                                                                                                                                        </div>
                                                                                                                                    `;
                        }
                    }
                ],
                order: [[0, 'desc']],
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    },
                    "buttons": {
                        "copy": "Copiar",
                        "copyTitle": "Copiado al portapapeles",
                        "copySuccess": {
                            "1": "1 fila copiada al portapapeles",
                            "_": "%d filas copiadas al portapapeles"
                        },
                        "csv": "CSV",
                        "excel": "Excel",
                        "print": "Imprimir",
                        "colvis": "Visibilidad de Columna"
                    }
                }
            });

            // Vincular buscador personalizado al DataTable
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            window.products = @json($productos);
            var tallasCatalogo = @json($tallas->mapWithKeys(function ($talla) {
                return [$talla->nombre => ($talla->etiqueta ?: $talla->nombre)];
            }));

            function getTallaLabel(talla) {
                if (!talla) return 'N/A';
                return tallasCatalogo[talla] || talla;
            }

            window.productItemIndex = 0;
            var productItemIndex = window.productItemIndex;

            window.addProductItem = function addProductDisplayCard(productoId = '', cantidad = '', precioUnitario = '', descripcion = '', llevaBordado = false, nombreLogo = '', color = '', talla = '', productInsumos = [], ubicacionLogo = '', cantidadLogo = '', bordados = []) {
                // Obtener nombre del producto seleccionado
                var productoText = 'Producto sin nombre';
                if (productoId) {
                    var productoEncontrado = products.find(p => p.id == productoId);
                    if (productoEncontrado) {
                        var tipoNombre = productoEncontrado.tipo_producto ? productoEncontrado.tipo_producto.nombre : 'Sin tipo';
                        productoText = (productoEncontrado.codigo || '') + ' - ' + tipoNombre + ' ' + productoEncontrado.modelo;
                    }
                }

                var bordadosArray = Array.isArray(bordados) ? bordados : [];

                if (llevaBordado && bordadosArray.length === 0 && (ubicacionLogo || nombreLogo)) {
                    bordadosArray.push({
                        ubicacion_bordado_id: null,
                        nombre_aplicado: ubicacionLogo || 'Ubicación legacy',
                        nombre_logo: nombreLogo || '',
                        es_personalizada: true,
                        cantidad: parseInt(cantidadLogo || 1, 10) || 1,
                        precio_aplicado: 0,
                        orden: 0,
                    });
                }

                var recargoUnitario = bordadosArray.reduce(function (sum, b) {
                    var cantidadB = Math.max(1, parseInt(b.cantidad || 1, 10));
                    var precioB = parseFloat(b.precio_aplicado || 0);
                    return sum + (precioB * cantidadB);
                }, 0);

                var precioFinalUnitario = parseFloat(precioUnitario) || 0;
                var precioBaseEstimado = Math.max(0, precioFinalUnitario - recargoUnitario);
                var subtotal = (parseFloat(cantidad) || 0) * precioFinalUnitario;

                function escAttr(value) {
                    return String(value ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                }

                var ubicacionLogoLegacy = ubicacionLogo || bordadosArray.map(function (b) {
                    return String(b.nombre_aplicado || '').trim();
                }).filter(Boolean).join(', ');

                var cantidadLogoLegacy = cantidadLogo || bordadosArray.reduce(function (acc, b) {
                    return acc + Math.max(1, parseInt(b.cantidad || 1, 10));
                }, 0);

                var bordadosHtml = '';
                var hiddenBordadosHtml = '';

                if (llevaBordado) {
                    if (bordadosArray.length > 0) {
                        bordadosHtml = bordadosArray.map(function (bordado, idx) {
                            var nombreAplicado = escAttr(bordado.nombre_aplicado || 'Ubicación');
                            var logoAplicado = escAttr(bordado.nombre_logo || bordado.nombre_logo_aplicado || nombreLogo || 'Sin logo');
                            var cantidadAplicada = Math.max(1, parseInt(bordado.cantidad || 1, 10));

                            return `
                                        <div class="pb-1 mb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);">
                                            <span class="fw-semibold" style="font-size:0.84rem;color:#1e3c72;">${logoAplicado} → ${nombreAplicado} x${cantidadAplicada}</span>
                                        </div>
                                    `;
                        }).join('');
                    } else {
                        bordadosHtml = `<div class="pb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);"><span class="fw-semibold" style="font-size:0.84rem;color:#1e3c72;">${escAttr(nombreLogo || 'Sin logo')} → ${escAttr(ubicacionLogo || 'Sin ubicación')} x${Math.max(1, parseInt(cantidadLogo || 1, 10))}</span></div>`;
                    }

                    hiddenBordadosHtml = bordadosArray.map(function (bordado, idx) {
                        return `
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][ubicacion_bordado_id]" value="${escAttr(bordado.ubicacion_bordado_id || '')}" />
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][nombre_aplicado]" value="${escAttr(bordado.nombre_aplicado || '')}" />
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][nombre_logo]" value="${escAttr(bordado.nombre_logo || bordado.nombre_logo_aplicado || nombreLogo || '')}" />
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][es_personalizada]" value="${bordado.es_personalizada ? 1 : 0}" />
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][cantidad]" value="${Math.max(1, parseInt(bordado.cantidad || 1, 10))}" />
                                    <input type="hidden" name="productos[${productItemIndex}][bordados][${idx}][precio_aplicado]" value="${parseFloat(bordado.precio_aplicado || 0)}" />
                                `;
                    }).join('');
                }

                var itemHtml = `
                                                                                                                <div class="card border-0 shadow-sm mb-3 product-item" style="border-left: 4px solid #00d9a5 !important;" data-product-index="${productItemIndex}">
                                                                                                                    <div class="card-body p-3">
                                                                                                                        <!-- Header del Producto -->
                                                                                                                        <div class="d-flex align-items-center mb-3">
                                                                                                                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                                                                                                style="width: 45px; height: 45px; background: #1e3c72;">
                                                                                                                                <i class="ri-t-shirt-2-line text-white fs-5"></i>
                                                                                                                            </div>
                                                                                                                            <div class="flex-grow-1">
                                                                                                                                <h6 class="mb-0 fw-bold" style="color: #1e3c72;">${productoText}</h6>
                                                                                                                                <small class="text-muted">Producto #${productItemIndex + 1}</small>
                                                                                                                            </div>
                                                                                                                            <div>
                                                                                                                                <span class="badge" style="background: #00d9a5; font-size: 0.9rem;">
                                                                                                                                    $${subtotal.toFixed(2)}
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                        </div>

                                                                                                                        <!-- Detalles del Producto -->
                                                                                                                        <div class="row g-2 mb-2">
                                                                                                                            <div class="col-6 col-md-3">
                                                                                                                                <div class="d-flex align-items-center">
                                                                                                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                        style="width: 28px; height: 28px; background: rgba(46, 204, 113, 0.15);">
                                                                                                                                        <i class="ri-stack-line" style="color: #2ecc71; font-size: 0.85rem;"></i>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Cantidad</small>
                                                                                                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${cantidad}</span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="col-6 col-md-3">
                                                                                                                                <div class="d-flex align-items-center">
                                                                                                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                                                                                                        <i class="ri-t-shirt-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Talla</small>
                                                                                                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${getTallaLabel(talla)}</span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="col-6 col-md-3">
                                                                                                                                <div class="d-flex align-items-center">
                                                                                                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                        style="width: 28px; height: 28px; background: rgba(108, 92, 231, 0.15);">
                                                                                                                                        <i class="ri-palette-line" style="color: #6c5ce7; font-size: 0.85rem;"></i>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Color</small>
                                                                                                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${color || 'N/A'}</span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <div class="col-6 col-md-3">
                                                                                                                                <div class="d-flex align-items-center">
                                                                                                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                        style="width: 28px; height: 28px; background: rgba(46, 204, 113, 0.15);">
                                                                                                                                        <i class="ri-money-dollar-circle-line" style="color: #2ecc71; font-size: 0.85rem;"></i>
                                                                                                                                    </div>
                                                                                                                                    <div>
                                                                                                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">P. Unitario</small>
                                                                                                                                        <span class="fw-semibold" style="font-size: 0.85rem;">$${precioFinalUnitario.toFixed(2)}</span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>

                                                                                                                        ${llevaBordado ? `
                                                                                                                        <!-- Logos -->
                                                                                                                        <div class="rounded p-2 mb-2" style="background: rgba(30, 60, 114, 0.08);">
                                                                                                                            <div class="d-flex align-items-center mb-2">
                                                                                                                                <i class="ri-scissors-cut-line me-2" style="color: #1e3c72;"></i>
                                                                                                                                <span class="fw-semibold" style="color: #1e3c72; font-size: 0.85rem;">Logos</span>
                                                                                                                            </div>
                                                                                                                            <small class="text-muted d-block mb-1" style="font-size: 0.72rem;">Aplicaciones</small>
                                                                                                                            ${bordadosHtml}
                                                                                                                        </div>
                                                                                                                        ` : ''}

                                                                                                                        ${descripcion ? `
                                                                                                                        <!-- Descripción -->
                                                                                                                        <div class="rounded p-2" style="background: rgba(30, 60, 114, 0.05);">
                                                                                                                            <div class="d-flex align-items-start">
                                                                                                                                <i class="ri-file-text-line me-2 mt-1" style="color: #1e3c72;"></i>
                                                                                                                                <div>
                                                                                                                                    <small class="text-muted d-block">Descripción</small>
                                                                                                                                    <span style="font-size: 0.85rem;">${descripcion}</span>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        ` : ''}

                                                                                                                        <!-- Hidden inputs para enviar datos al backend -->
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][producto_id]" class="producto-id-input" value="${productoId}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][cantidad]" class="cantidad-input" value="${cantidad}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][precio_unitario]" class="precio-unitario-input" value="${precioFinalUnitario}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][color]" value="${color}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][talla]" value="${talla}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][descripcion]" value="${descripcion}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][lleva_bordado]" value="${llevaBordado ? 1 : 0}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][nombre_logo]" value="${nombreLogo}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][ubicacion_logo]" value="${ubicacionLogoLegacy}" />
                                                                                                                        <input type="hidden" name="productos[${productItemIndex}][cantidad_logo]" value="${cantidadLogoLegacy || 1}" />
                                                                                                                        ${hiddenBordadosHtml}
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                `;
                $('#productos-container').append(itemHtml);
                window.productItemIndex++;
            }
            // funciÃ³nes para manejar los nuevos campos de pago y prioridad
            let currentPedidoTotal = 0; // Para almacenar el total del pedido, ya sea del backend o calculado

            function calculateProductTotals() {
                let sum = 0;
                $('#productos-container .product-item').each(function () {
                    let quantity = parseFloat($(this).find('.cantidad-input').val()) || 0;
                    let price = parseFloat($(this).find('.precio-unitario-input').val()) || 0;
                    sum += (quantity * price);
                });
                currentPedidoTotal = sum;
                $('#total-display-field').val(currentPedidoTotal.toFixed(2));
                updateRemaining();
            }

            function updateRemaining() {
                let total = parseFloat($('#total-display-field').val()) || 0;
                let abono = parseFloat($('#abono-field').val()) || 0;
                let restante = total - abono;
                $('#restante-display-field').val(restante.toFixed(2));
            }

            function togglePaymentFieldsVisibility() {
                let transferenciaChecked = $('#transferencia-pagado-field').is(':checked');
                let pagoMovilChecked = $('#pago-movil-pagado-field').is(':checked');

                // Referencia y Banco Transferencia
                if (transferenciaChecked) {
                    $('#referencia-transferencia-container').show();
                    $('#referencia-transferencia-field').prop('required', true);
                    $('#banco-transferencia-id-field').prop('required', true);
                } else {
                    $('#referencia-transferencia-container').hide();
                    $('#referencia-transferencia-field').val('').prop('required', false);
                    $('#banco-transferencia-id-field').val('').prop('required', false);
                }

                // Referencia y Banco Pago Móvil
                if (pagoMovilChecked) {
                    $('#referencia-pago-movil-container').show();
                    $('#referencia-pago-movil-field').prop('required', true);
                    $('#banco-pago-movil-id-field').prop('required', true);
                } else {
                    $('#referencia-pago-movil-container').hide();
                    $('#referencia-pago-movil-field').val('').prop('required', false);
                    $('#banco-pago-movil-id-field').val('').prop('required', false);
                }
            }

            // Eventos para los nuevos campos de pago y prioridad
            $('#abono-field').on('change keyup', updateRemaining);
            $('#efectivo-pagado-field, #transferencia-pagado-field, #pago-movil-pagado-field').on('change', togglePaymentFieldsVisibility);
            $('#productos-container').on('change', '.cantidad-input', calculateProductTotals); // Recalcular total cuando cambia la cantidad

            $('#create-btn').on('click', function () {
                $('#modalTitle').text('Agregar Pedido');
                $('#pedidoForm')[0].reset();
                $('#id-field').val('');
                $('#cliente-id-field').val('').prop('disabled', false).removeClass('campo-protegido'); // Limpiar cliente_id para nuevo pedido y habilitar
                $('#fecha-pedido-field').prop('readonly', false).removeClass('campo-protegido'); // Habilitar fecha pedido
                $('#add-btn').show();
                $('#edit-btn').hide();
                $('#estado-field-wrapper').hide(); // Ocultar estado en agregar
                $('#productos-container').empty(); // Limpiar productos existentes
                // Los productos se cargan automáticamente desde la cotización

                // Resetear y ocultar nuevos campos de pago/prioridad
                $('#abono-field').val(0);
                $('#efectivo-pagado-field').prop('checked', false);
                $('#transferencia-pagado-field').prop('checked', false);
                $('#pago-movil-pagado-field').prop('checked', false);
                $('#referencia-transferencia-field').val('');
                $('#referencia-pago-movil-field').val('');
                $('#banco-transferencia-id-field').val('');
                $('#banco-pago-movil-id-field').val('');
                $('#prioridad-field').val('Normal'); // Valor por defecto
                currentPedidoTotal = 0; // Resetear total para un nuevo pedido
                calculateProductTotals(); // Calcular el total inicial (que ser� 0)
                togglePaymentFieldsVisibility(); // Ocultar referencias y banco inicialmente
            });



            // Evento para remover producto
            $('#productos-container').on('click', '.remove-producto-item', function () {
                $(this).closest('.card').remove();
            });

            // funciÃ³n para combinar el prefijo y el Numero del RIF/CI
            function updateRifFullField() {
                let prefix = $('#ci-rif-prefix-field').val();
                let number = $('#ci-rif-number-field').val();
                $('#ci-rif-full-field').val(prefix + number);
            }

            // Escuchar cambios en el prefijo y el Numero del RIF/CI
            $('#ci-rif-prefix-field, #ci-rif-number-field').on('change keyup', updateRifFullField);

            // Actualizar precio_unitario oculto cuando se selecciona un producto
            $('#productos-container').on('change', '.product-select', function () {
                var selectedOption = $(this).find('option:selected');
                var precio = selectedOption.data('precio');
                $(this).closest('.card').find('.precio-unitario-input').val(precio);
                // Mostrar el precio en el span a la derecha
                $(this).siblings('.precio-producto-span').text(precio ? '$' + parseFloat(precio).toFixed(2) : '');
                calculateProductTotals(); // Recalcular total cuando cambia el producto
            });

            $('#pedidoForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                var id = $('#id-field').val();
                var url = id ? '/pedidos/' + id : '/pedidos';

                const clienteId = $('#cliente-id-field').val();
                if (!formData.get('cliente_id') && clienteId) {
                    formData.set('cliente_id', clienteId);
                }


                // A�adir el token CSRF y el m�todo HTTP manualmente para PUT/DELETE
                if (id) {
                    formData.append('_method', 'PUT');
                }
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: url,
                    method: 'POST', // Siempre POST para Laravel con _method para PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs me-2',
                            buttonsStyling: false,
                            showCloseButton: true
                        })
                        $('#showModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        var errorMessage = '';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            for (var key in errors) {
                                errorMessage += errors[key][0] + '\n';
                            }
                        } else {
                            errorMessage = 'OcurriÃ³ un error inesperado.';
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs me-2',
                            buttonsStyling: false,
                            showCloseButton: true
                        })
                    }
                });
            });

            $('#pedidos-table').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $('#modalTitle').text('Editar Pedido');
                $('#id-field').val(id);
                $('#add-btn').hide();
                $('#edit-btn').show();
                $('#estado-field-wrapper').hide(); // Estado es automático, no se edita manualmente
                $('#productos-container').empty(); // Limpiar productos existentes

                $.ajax({
                    url: '/pedidos/' + id,
                    method: 'GET',
                    success: function (data) {
                        // Cargar cliente y bloquear campos visualmente
                        $('#cliente-id-field').val(data.cliente_id || '').prop('disabled', false).addClass('campo-protegido');

                        $('#cliente-nombre-field').val(data.cliente_nombre_completo || data.cliente_nombre || '')
                            .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');

                        $('#cliente-email-field').val(data.cliente_email_normalizado || data.cliente_email || '')
                            .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');

                        $('#cliente-telefono-field').val(data.cliente_telefono_normalizado || data.cliente_telefono || '')
                            .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');

                        // Detectar prefijo del documento (usa cliente_documento del accessor)
                        var ciRif = data.cliente_documento || '';
                        var prefix = ciRif.match(/^(V-|J-|E-|G-)/);

                        $('#ci-rif-prefix-field').val(prefix ? prefix[0] : 'V-')
                            .prop('disabled', true).addClass('bg-light').css('cursor', 'not-allowed');

                        $('#ci-rif-number-field').val(ciRif.replace(/^(V-|J-|E-|G-)/, ''))
                            .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');

                        $('#ci-rif-full-field').val(ciRif);
                        $('#fecha-pedido-field').val(data.fecha_pedido || '').prop('readonly', true).addClass('campo-protegido');
                        $('#fecha-entrega-estimada-field').val(data.fecha_entrega_estimada || '');
                        $('#estado-field').val(data.estado);

                        // Cargar nuevos campos de pago y prioridad
                        $('#abono-field').val(data.abono);
                        $('#efectivo-pagado-field').prop('checked', data.efectivo_pagado);
                        $('#transferencia-pagado-field').prop('checked', data.transferencia_pagado);
                        $('#pago-movil-pagado-field').prop('checked', data.pago_movil_pagado);
                        $('#referencia-transferencia-field').val(data.referencia_transferencia || '');
                        $('#referencia-pago-movil-field').val(data.referencia_pago_movil || '');
                        $('#banco-id-field').val(data.banco_id).trigger('change'); // Usar .val() y trigger('change') para Select2
                        $('#prioridad-field').val(data.prioridad);

                        currentPedidoTotal = parseFloat(data.total); // Cargar el total del backend
                        $('#total-display-field').val(currentPedidoTotal.toFixed(2));

                        // Llenar productos del pedido
                        if (data.productos && data.productos.length > 0) {
                            data.productos.forEach(function (item) {
                                // Transformar insumos a la forma esperada por addProductItem
                                let insumosTransformados = [];
                                if (item.insumos && item.insumos.length > 0) {
                                    insumosTransformados = item.insumos.map(function (insumo) {
                                        return {
                                            insumo_id: insumo.id,
                                            cantidad_estimada: insumo.pivot.cantidad_estimada
                                        };
                                    });
                                }
                                addProductItem(
                                    item.producto_id,
                                    item.cantidad,
                                    item.precio_unitario,
                                    item.descripcion,
                                    item.lleva_bordado,
                                    item.nombre_logo,
                                    item.color,
                                    item.talla,
                                    insumosTransformados,
                                    item.ubicacion_logo || '',
                                    item.cantidad_logo || 1,
                                    item.bordados || []
                                );
                            });
                            calculateProductTotals();
                        } else {
                            // Si no hay productos, mostrar mensaje
                            $('#productos-container').html('<div class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block mb-2"></i>Sin productos asociados</div>');
                            calculateProductTotals();
                        }

                        togglePaymentFieldsVisibility(); // Aplicar visibilidad basada en los datos cargados
                        updateRemaining(); // Calcular el restante

                        $('#showModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se pudo cargar los datos del pedido.',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs me-2',
                            buttonsStyling: false,
                            showCloseButton: true
                        })
                    }
                });
            });

            $('#pedidos-table').on('click', '.view-btn', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '/pedidos/' + id,
                    method: 'GET',
                    success: function (data) {
                        // Función para formatear fechas YYYY-MM-DD o ISO a dd/mm/yyyy
                        function formatDate(dateStr) {
                            if (!dateStr) return 'N/A';
                            var date = new Date(dateStr);
                            if (isNaN(date.getTime())) return dateStr;
                            var day = String(date.getDate()).padStart(2, '0');
                            var month = String(date.getMonth() + 1).padStart(2, '0');
                            var year = date.getFullYear();
                            return day + '/' + month + '/' + year;
                        }
                        $('#view-cliente-nombre').text(data.cliente_nombre_completo);
                        $('#view-cliente-email').text(data.cliente_email_normalizado || 'N/A');
                        $('#view-cliente-telefono').text(data.cliente_telefono_normalizado || 'N/A');
                        $('#view-ci-rif').text(data.cliente_documento || 'N/A');
                        $('#view-fecha-pedido').text(formatDate(data.fecha_pedido));
                        $('#view-fecha-entrega-estimada').text(formatDate(data.fecha_entrega_estimada));

                        // Mostrar estado con badge
                        let estadoBadgeClass = '';
                        switch (data.estado) {
                            case 'Pendiente':
                                estadoBadgeClass = 'bg-info';
                                break;
                            case 'Procesando':
                                estadoBadgeClass = 'bg-warning';
                                break;
                            case 'Completado':
                                estadoBadgeClass = 'bg-success';
                                break;
                            case 'Cancelado':
                                estadoBadgeClass = 'bg-danger';
                                break;
                            default:
                                estadoBadgeClass = 'bg-secondary';
                        }
                        $('#view-estado').html(`<span class="badge ${estadoBadgeClass}">${data.estado}</span>`);

                        var totalFormatted = parseFloat(data.total).toFixed(2);
                        $('#view-total-resumen').text('$' + totalFormatted);
                        $('#view-usuario-creador').text(data.user ? data.user.name : 'N/A');

                        // Cargar y mostrar nuevos campos de pago y prioridad
                        $('#view-abono').text('$' + parseFloat(data.abono).toFixed(2));
                        let restante = parseFloat(data.total) - parseFloat(data.abono);
                        $('#view-restante').text('$' + restante.toFixed(2));

                        let metodosPago = [];
                        if (data.efectivo_pagado) metodosPago.push('<span class="metodo-pago-pill metodo-pago-pill--efectivo"><i class="ri-money-dollar-circle-line"></i>Efectivo</span>');
                        if (data.transferencia_pagado) metodosPago.push('<span class="metodo-pago-pill metodo-pago-pill--transferencia"><i class="ri-bank-card-line"></i>Transferencia</span>');
                        if (data.pago_movil_pagado) metodosPago.push('<span class="metodo-pago-pill metodo-pago-pill--pago-movil"><i class="ri-smartphone-line"></i>Pago Móvil</span>');
                        $('#view-metodo-pago').html(metodosPago.join('') || '<span class="metodo-pago-pill metodo-pago-pill--none">Sin método</span>');

                        const referenciaTransferencia = data.referencia_transferencia || '';
                        const bancoTransferenciaNombre = data.banco_transferencia?.nombre || data.banco?.nombre || '';
                        const mostrarBloqueTransferencia = Boolean(data.transferencia_pagado || referenciaTransferencia || bancoTransferenciaNombre);

                        $('#view-referencia-transferencia').text(referenciaTransferencia || 'Sin referencia');
                        $('#view-banco-transferencia').text(bancoTransferenciaNombre || 'Sin banco');
                        $('#view-bloque-transferencia-container').toggle(mostrarBloqueTransferencia);

                        const referenciaPagoMovil = data.referencia_pago_movil || '';
                        const bancoPagoMovilNombre = data.banco_pago_movil?.nombre || data.banco?.nombre || '';
                        const mostrarBloquePagoMovil = Boolean(data.pago_movil_pagado || referenciaPagoMovil || bancoPagoMovilNombre);

                        $('#view-referencia-pago-movil').text(referenciaPagoMovil || 'Sin referencia');
                        $('#view-banco-pago-movil').text(bancoPagoMovilNombre || 'Sin banco');
                        $('#view-bloque-pago-movil-container').toggle(mostrarBloquePagoMovil);

                        // Mostrar prioridad con badge
                        let prioridadBadgeClass = '';
                        switch (data.prioridad) {
                            case 'Normal':
                                prioridadBadgeClass = 'bg-primary';
                                break;
                            case 'Alta':
                                prioridadBadgeClass = 'bg-warning';
                                break;
                            case 'Urgente':
                                prioridadBadgeClass = 'bg-danger';
                                break;
                            default:
                                prioridadBadgeClass = 'bg-secondary';
                        }
                        $('#view-prioridad').html(`<span class="badge ${prioridadBadgeClass}">${data.prioridad || 'N/A'}</span>`);

                        // Llenar productos del pedido en la vista
                        var productosBody = $('#view-productos-container');
                        productosBody.empty();
                        if (data.productos && data.productos.length > 0) {
                            data.productos.forEach(function (item, index) {
                                var subtotal = item.cantidad * item.precio_unitario;
                                var colorDisplay = String(item.color || item.color_nombre || item.color_aplicado || '').trim();
                                var colorHtml = colorDisplay
                                    ? `<span class="fw-semibold" style="font-size: 0.85rem;">${colorDisplay}</span>`
                                    : `<span class="badge bg-soft-warning text-atlantico-dark" style="font-size:0.68rem;">Sin color</span>`;
                                var tallaLabel = getTallaLabel(item.talla);
                                var tallaHtml = (item.talla && tallaLabel !== 'N/A')
                                    ? `<span class="fw-semibold" style="font-size: 0.85rem;">${tallaLabel}</span>`
                                    : `<span class="badge bg-soft-primary text-atlantico-dark" style="font-size:0.68rem;">Sin talla</span>`;
                                productosBody.append(`
                                                                                                        <div class="card border-0 shadow-sm mb-3" style="border-left: 4px solid #00d9a5 !important;">
                                                                                                            <div class="card-body p-3">
                                                                                                                <!-- Header del Producto -->
                                                                                                                <div class="d-flex align-items-center mb-3">
                                                                                                                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                                                                                        style="width: 45px; height: 45px; background: #1e3c72;">
                                                                                                                        <i class="ri-shopping-bag-line text-white fs-5"></i>
                                                                                                                    </div>
                                                                                                                    <div>
                                                                                                                        <h6 class="mb-0 fw-bold" style="color: #1e3c72;">${item.producto.codigo || ''} - ${item.producto.tipo_producto ? item.producto.tipo_producto.nombre : 'Sin tipo'} ${item.producto.modelo}</h6>
                                                                                                                        <small class="text-muted">Producto #${index + 1}</small>
                                                                                                                    </div>
                                                                                                                    <div class="ms-auto">
                                                                                                                        <span class="badge" style="background: #00d9a5; font-size: 0.9rem;">
                                                                                                                            $${subtotal.toFixed(2)}
                                                                                                                        </span>
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                                <!-- Detalles del Producto -->
                                                                                                                <div class="row g-2 mb-3">
                                                                                                                    <div class="col-6 col-md-3">
                                                                                                                        <div class="d-flex align-items-center">
                                                                                                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                                                                                                <i class="ri-stack-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                                                                                            </div>
                                                                                                                            <div>
                                                                                                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Cantidad</small>
                                                                                                                                <span class="fw-semibold" style="font-size: 0.85rem;">${item.cantidad}</span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-6 col-md-3">
                                                                                                                        <div class="d-flex align-items-center">
                                                                                                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                                                                                                <i class="ri-palette-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                                                                                            </div>
                                                                                                                            <div>
                                                                                                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Color</small>
                                                                                                                                ${colorHtml}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-6 col-md-3">
                                                                                                                        <div class="d-flex align-items-center">
                                                                                                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                                                                                                <i class="ri-t-shirt-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                                                                                            </div>
                                                                                                                            <div>
                                                                                                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Talla</small>
                                                                                                                                ${tallaHtml}
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="col-6 col-md-3">
                                                                                                                        <div class="d-flex align-items-center">
                                                                                                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                                                                                style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                                                                                                <i class="ri-money-dollar-circle-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                                                                                            </div>
                                                                                                                            <div>
                                                                                                                                <small class="text-muted d-block" style="font-size: 0.7rem;">P. Unitario</small>
                                                                                                                                <span class="fw-semibold" style="font-size: 0.85rem;">$${parseFloat(item.precio_unitario).toFixed(2)}</span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                                <!-- Bordado/Logo si aplica -->
                                                                                                                ${item.lleva_bordado ? (() => {
                                        const bordados = Array.isArray(item.bordados) ? item.bordados : [];
                                        const bordadosHtml = bordados.length
                                            ? bordados.map((bordado) => {
                                                const nombreAplicado = bordado.nombre_aplicado || 'Ubicación';
                                                const logoAplicado = bordado.nombre_logo_aplicado || bordado.nombre_logo || item.nombre_logo || 'Sin logo';
                                                const cantidadAplicada = Math.max(1, parseInt(bordado.cantidad || 1, 10));
                                                return `
                                                                                                                                        <div class="pb-1 mb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);">
                                                                                                                                        <span class="fw-semibold" style="font-size:0.84rem;color:#1e3c72;">${logoAplicado} → ${nombreAplicado} x${cantidadAplicada}</span>
                                                                                                                                </div>
                                                                                                                            `;
                                            }).join('')
                                            : `<div class="pb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);"><span class="fw-semibold" style="font-size:0.84rem;color:#1e3c72;">${item.nombre_logo || 'Sin logo'} → ${item.ubicacion_logo || 'Sin ubicación'} x${item.cantidad_logo || 1}</span></div>`;

                                        return `
                                                                                                                        <div class="rounded p-2 mb-3" style="background: rgba(30, 60, 114, 0.08);">
                                                                                                                            <div class="d-flex align-items-center mb-2">
                                                                                                                                <i class="ri-scissors-cut-line me-2" style="color: #1e3c72;"></i>
                                                                                                                                <span class="fw-semibold" style="color: #1e3c72; font-size: 0.85rem;">Logos</span>
                                                                                                                            </div>
                                                                                                                            ${bordadosHtml}
                                                                                                                        </div>
                                                                                                                    `;
                                    })() : ''}

                                                                                                                <!-- Descripción -->
                                                                                                                ${item.descripcion ? `
                                                                                                                    <div class="rounded p-2 mb-3" style="background: rgba(30, 60, 114, 0.05);">
                                                                                                                        <div class="d-flex align-items-start">
                                                                                                                            <i class="ri-file-text-line me-2 mt-1" style="color: #1e3c72;"></i>
                                                                                                                            <div>
                                                                                                                                <small class="text-muted d-block">Descripción</small>
                                                                                                                                <span style="font-size: 0.85rem;">${item.descripcion}</span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    ` : ''}

                                                                                                                <!-- Insumos -->
                                                                                                                ${item.insumos && item.insumos.length > 0 ? `
                                                                                                                    <div class="rounded p-2" style="background: rgba(46, 204, 113, 0.08);">
                                                                                                                        <div class="d-flex align-items-center mb-2">
                                                                                                                            <i class="ri-tools-line me-2" style="color: #2ecc71;"></i>
                                                                                                                            <span class="fw-semibold" style="color: #2ecc71; font-size: 0.85rem;">Insumos Requeridos</span>
                                                                                                                        </div>
                                                                                                                        <div class="d-flex flex-wrap gap-2">
                                                                                                                            ${item.insumos.map(insumo => `
                                                                                                                                <span class="badge" style="background: rgba(46, 204, 113, 0.2); color: #1e3c72;">
                                                                                                                                    ${insumo.nombre}
                                                                                                                                    <span class="badge bg-primary ms-1">${parseFloat(insumo.pivot.cantidad_estimada).toFixed(2)} ${insumo.unidad_medida}</span>
                                                                                                                                </span>
                                                                                                                            `).join('')}
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    ` : ''}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        `);
                            });
                        } else {
                            productosBody.append('<p class="text-muted text-center py-4"><i class="ri-shopping-bag-line fs-1 d-block mb-2"></i>No hay productos en este pedido.</p>');
                        }

                        $('#viewModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'No se pudo cargar los detalles del pedido.',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs me-2',
                                cancelButton: 'btn btn-danger w-xs'
                            },
                            buttonsStyling: false,
                            showCloseButton: true
                        })
                    }
                });
            });

            $('#pedidos-table').on('click', '.remove-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: '�Est�s seguro?',
                    text: "�No podr�s revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'S�, eliminarlo!',
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
                            url: '/pedidos/' + id,
                            method: 'POST', // Usamos POST y _method para simular DELETE
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: '�Eliminado!',
                                    text: response.success,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true,
                                    timer: 1500
                                })
                                table.ajax.reload();
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'No se pudo eliminar el pedido.',
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                })
                            }
                        });
                    }
                });
            });

            // funciÃ³n para inicializar Select2
            function initializeSelect2(selector) {
                $(selector).select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Seleccione insumo...',
                    width: '100%',
                    dropdownParent: $('#showModal .modal-body')
                });
            }

            // Reset form on modal close
            $('#showModal').on('hidden.bs.modal', function () {
                // Limpiar campos y restaurar estado editable
                $('#id-field').val('');
                $('#cliente-id-field').val('');
                $('#cliente-nombre-field').val('').prop('readonly', false).removeClass('bg-light').css('cursor', '');
                $('#cliente-email-field').val('').prop('readonly', false).removeClass('bg-light').css('cursor', '');
                $('#cliente-telefono-field').val('').prop('readonly', false).removeClass('bg-light').css('cursor', '');
                $('#ci-rif-prefix-field').val('V-').prop('disabled', false).removeClass('bg-light').css('cursor', '');
                $('#ci-rif-number-field').val('').prop('readonly', false).removeClass('bg-light').css('cursor', '');
                $('#ci-rif-full-field').val('');

                // ... resto de limpieza ...
                $('#pedidoForm')[0].reset();
                $('#id-field').val('');
                $('#modalTitle').text('Agregar Pedido');
                $('#estado-field-wrapper').hide(); // Ocultar estado en agregar
                $('#productos-container').empty(); // Limpiar productos existentes
                // Los productos se cargan automáticamente desde la cotización

                // Resetear y ocultar nuevos campos de pago/prioridad
                $('#abono-field').val(0);
                $('#efectivo-pagado-field').prop('checked', false);
                $('#transferencia-pagado-field').prop('checked', false);
                $('#pago-movil-pagado-field').prop('checked', false);
                $('#referencia-transferencia-field').val('');
                $('#referencia-pago-movil-field').val('');
                $('#banco-id-field').val('');
                $('#prioridad-field').val('Normal'); // Valor por defecto
                currentPedidoTotal = 0; // Resetear total
                calculateProductTotals(); // Recalcular el total inicial (que ser� 0)
                togglePaymentFieldsVisibility(); // Ocultar referencias y banco

                // Reiniciar los campos del CI/RIF a sus valores por defecto al resetear el formulario
                $('#ci-rif-prefix-field').val('V-');
                $('#ci-rif-number-field').val('');
                updateRifFullField();
            });

            // --- AUTOCOMPLETADO DE CLIENTE ---
            let clienteSeleccionado = null;
            let autocompleteTimeout = null;

            $('#cliente-nombre-field').on('input', function () {
                const query = $(this).val();
                clearTimeout(autocompleteTimeout);
                if (query.length < 2) {
                    $('#cliente-autocomplete-list').empty().hide();
                    return;
                }
                autocompleteTimeout = setTimeout(function () {
                    $.ajax({
                        url: '/clientes-search',
                        data: { q: query },
                        success: function (clientes) {
                            let html = '';
                            if (clientes.length > 0) {
                                clientes.forEach(function (cliente) {
                                    html += `<button type="button" class="list-group-item list-group-item-action cliente-autocomplete-item" data-id="${cliente.id}" data-nombre="${cliente.nombre}" data-email="${cliente.email || ''}" data-telefono="${cliente.telefono || ''}" data-documento="${cliente.documento || ''}">${cliente.nombre} <small class='text-muted'>${cliente.documento || 'Sin documento'} - ${cliente.email || 'Sin email'}</small></button>`;
                                });
                            } else {
                                html = '<div class="list-group-item disabled">No se encontraron clientes</div>';
                            }
                            $('#cliente-autocomplete-list').html(html).show();
                        }
                    });
                }, 300);
            });

            // Selecci�n de cliente de la lista
            $(document).on('click', '.cliente-autocomplete-item', function () {
                const $this = $(this);
                const clienteId = $this.data('id'); // ID del cliente seleccionado
                const nombre = $this.data('nombre') || '';
                const email = $this.data('email') || '';
                const telefono = $this.data('telefono') || '';
                const documento = $this.data('documento');

                // Llenar cliente_id (FK normalizada)
                $('#cliente-id-field').val(clienteId);

                // Llenar campos b�sicos
                $('#cliente-nombre-field').val(nombre);
                $('#cliente-email-field').val(email);
                $('#cliente-telefono-field').val(telefono);

                // Procesar documento - Convertir siempre a string primero
                let prefix = 'V-';
                let number = '';

                // Convertir documento a string de forma segura
                let docString = '';
                if (documento !== undefined && documento !== null && documento !== '') {
                    docString = String(documento).trim();
                }

                if (docString.length > 0) {
                    // Si el documento ya tiene formato V- o J-
                    if (docString.startsWith('V-') || docString.startsWith('J-') || docString.startsWith('E-') || docString.startsWith('G-')) {
                        prefix = docString.substring(0, 2);
                        number = docString.substring(2);
                    } else {
                        // Si no tiene prefijo, determinar autom�ticamente
                        number = docString;
                        // L�gica para determinar si es V- o J-
                        if (docString.length >= 8 && /^[2-9]/.test(docString)) {
                            prefix = 'J-';
                        } else {
                            prefix = 'V-';
                        }
                    }
                }

                // Establecer valores en los campos
                $('#ci-rif-prefix-field').val(prefix);
                $('#ci-rif-number-field').val(number);
                updateRifFullField();

                $('#cliente-autocomplete-list').empty().hide();
                clienteSeleccionado = true;
            });

            // Ocultar lista al hacer click fuera
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#cliente-nombre-field, #cliente-autocomplete-list').length) {
                    $('#cliente-autocomplete-list').empty().hide();
                }
            });

            // --- MODAL AGREGAR CLIENTE DESDE PEDIDO ---
            $('#open-add-cliente-modal').on('click', function () {
                $('#clienteFormPedido')[0].reset();
                $('#id-field-cliente').val('');
                $('#modalClienteTitle').text('Agregar Cliente');
                $('#add-btn-cliente').show();
                $('#edit-btn-cliente').hide();
                $('#modalAddCliente').modal('show');
            });

            // Guardar nuevo cliente desde el modal
            $('#add-btn-cliente').on('click', function () {
                $('#clienteFormPedido').submit();
            });
            $('#clienteFormPedido').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '/clientes',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: '��Â¡Ã‰xito!',
                            text: response.message,
                            showConfirmButton: false,
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs me-2',
                                cancelButton: 'btn btn-danger w-xs'
                            },
                            buttonsStyling: false,
                            showCloseButton: true,
                            timer: 2000
                        });
                        $('#modalAddCliente').modal('hide');

                        // Capturar el cliente_id del cliente reci�n creado
                        if (response.cliente_id) {
                            $('#cliente-id-field').val(response.cliente_id);
                        }

                        // Rellenar los campos del pedido con el nuevo cliente
                        const nombre = $('#nombre-field-cliente').val();
                        const email = $('#email-field-cliente').val();
                        const telefono = $('#telefono-field-cliente').val();
                        const documento = $('#documento-field-cliente').val();

                        $('#cliente-nombre-field').val(nombre);
                        $('#cliente-email-field').val(email || '');
                        $('#cliente-telefono-field').val(telefono || '');

                        // Separar prefijo y Numero del documento
                        let prefix = 'V-'; // Valor por defecto
                        let number = '';

                        if (documento) {
                            // Si el documento ya tiene formato V- o J-
                            if (documento.startsWith('V-') || documento.startsWith('J-')) {
                                prefix = documento.substring(0, 2);
                                number = documento.substring(2);
                            } else {
                                // Si el documento no tiene prefijo, asumimos que es solo el Numero
                                number = documento;
                                // Determinar el prefijo basado en la longitud o primer d�gito
                                if (documento.length >= 8 && ['2', '3', '4', '5', '6', '7', '8', '9'].includes(documento.charAt(0))) {
                                    prefix = 'J-';
                                } else {
                                    prefix = 'V-';
                                }
                            }
                        }

                        $('#ci-rif-prefix-field').val(prefix);
                        $('#ci-rif-number-field').val(number);
                        $('#ci-rif-full-field').val(prefix + number);
                        $('#cliente-autocomplete-list').empty().hide();
                    },
                    error: function (xhr) {
                        let errorMsg = 'Ocurri� un error al agregar el cliente.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg,
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs me-2',
                                cancelButton: 'btn btn-danger w-xs'
                            },
                            buttonsStyling: false,
                            showCloseButton: true
                        });
                    }
                });
            });

            $(`#insumos - container - ${currentProductItemIndex} .insumo - select`).last().on('change', function () {
                const selected = $(this).find('option:selected');
                const stock = parseFloat(selected.data('stock'));
                const stockMin = parseFloat(selected.data('stock-minimo'));
                const unidad = selected.data('unidad') || '';
                let color = stock <= stockMin ? 'red' : 'green';
                let text = '';
                if (!isNaN(stock)) {
                    text = `<span style="color:${color};font-weight:bold;">Stock actual: ${stock.toFixed(2)} ${unidad}</span>`;
                }
                const infoId = $(this).data('insumo-index');
                $(`#stock - info - ${infoId}`).html(text);
            }).trigger('change');
        });

        // ================================================
        // MODAL DE SELECCI�N DE PRODUCTOS
        // ================================================
        var currentProductIndex = null;
        var productosModal = null;

        $(document).ready(function () {
            productosModal = new bootstrap.Modal(document.getElementById('productosModal'));
            cargarTiposEnFiltro();

            $('#buscarProductoModal').on('keyup', function () {
                renderizarProductosModal($(this).val(), $('#filtroTipoProducto').val());
            });
            $('#filtroTipoProducto').on('change', function () {
                renderizarProductosModal($('#buscarProductoModal').val(), $(this).val());
            });
        });

        function cargarTiposEnFiltro() {
            var tipos = [];
            if (typeof products !== 'undefined') {
                products.forEach(function (p) {
                    if (p.tipo_producto && !tipos.find(t => t.id === p.tipo_producto.id)) {
                        tipos.push(p.tipo_producto);
                    }
                });
            }
            var select = $('#filtroTipoProducto');
            select.find('option:not(:first)').remove();
            tipos.forEach(function (tipo) {
                select.append('<option value="' + tipo.id + '">' + tipo.nombre + '</option>');
            });
        }

        function renderizarProductosModal(filtro, tipoId) {
            filtro = filtro || '';
            tipoId = tipoId || '';
            var tbody = $('#productosModalBody');
            tbody.empty();

            if (typeof products === 'undefined') return;

            var productosFiltrados = products.filter(function (p) {
                var matchFiltro = true;
                var matchTipo = true;
                if (filtro) {
                    var BÃºsqueda = filtro.toLowerCase();
                    var codigo = (p.codigo || '').toLowerCase();
                    var modelo = (p.modelo || '').toLowerCase();
                    var tipo = p.tipo_producto ? p.tipo_producto.nombre.toLowerCase() : '';
                    matchFiltro = codigo.includes(BÃºsqueda) || modelo.includes(BÃºsqueda) || tipo.includes(BÃºsqueda);
                }
                if (tipoId) {
                    matchTipo = p.tipo_producto && p.tipo_producto.id == tipoId;
                }
                return matchFiltro && matchTipo;
            });

            if (productosFiltrados.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted">No se encontraron productos</td></tr>');
                return;
            }

            productosFiltrados.forEach(function (p) {
                var tipoNombre = p.tipo_producto ? p.tipo_producto.nombre : 'Sin tipo';
                var imgHtml = p.imagen ? '<img src="' + p.imagen + '" class="producto-img-thumb" alt="">' : '<span class="text-muted">-</span>';
                var row = '<tr data-producto-id="' + p.id + '" data-precio="' + p.precio_base + '">' +
                    '<td>' + imgHtml + '</td>' +
                    '<td><span class="badge bg-dark">' + (p.codigo || '-') + '</span></td>' +
                    '<td><span class="badge bg-primary">' + tipoNombre + '</span></td>' +
                    '<td>' + p.modelo + '</td>' +
                    '<td class="text-success fw-bold">$ ' + parseFloat(p.precio_base).toFixed(2) + '</td>' +
                    '<td><button type="button" class="btn btn-sm btn-atlantico-brand select-producto-btn" data-id="' + p.id + '"><i class="ri-check-line"></i></button></td>' +
                    '</tr>';
                tbody.append(row);
            });
        }

        $(document).on('click', '.producto-selector-btn', function () {
            currentProductIndex = $(this).data('product-index');
            $('#buscarProductoModal').val('');
            $('#filtroTipoProducto').val('');
            renderizarProductosModal('', '');
            if (productosModal) productosModal.show();
        });

        $(document).on('click', '.select-producto-btn', function () {
            seleccionarProducto($(this).data('id'));
        });

        $(document).on('dblclick', '#productosModalTable tbody tr', function () {
            var productoId = $(this).data('producto-id');
            if (productoId) seleccionarProducto(productoId);
        });

        function seleccionarProducto(productoId) {
            if (typeof products === 'undefined') return;
            var producto = products.find(function (p) { return p.id == productoId; });
            if (!producto || currentProductIndex === null) return;

            var card = $('.card[data-product-index="' + currentProductIndex + '"]');
            var tipoNombre = producto.tipo_producto ? producto.tipo_producto.nombre : 'Sin tipo';
            var displayName = (producto.codigo || '') + ' - ' + tipoNombre + ' ' + producto.modelo;

            card.find('.producto-id-input').val(productoId);
            card.find('.producto-text').text(displayName).removeClass('placeholder-text');
            card.find('.precio-producto-span').text('$ ' + parseFloat(producto.precio_base).toFixed(2));
            card.find('.precio-unitario-input').val(producto.precio_base);

            if (productosModal) productosModal.hide();
            currentProductIndex = null;
        }
    </script>

    <script>
        // === CARGA DE PEDIDO DESDE COTIZACI�N ===
        $(document).ready(function () {
            // Verificar si venimos desde una cotizaci�n
            var urlParams = new URLSearchParams(window.location.search);
            var cotizacionId = urlParams.get('desde_cotizacion');
            var editarPedidoId = urlParams.get('editar');

            // Limpiar los parámetros de la URL sin recargar
            if (cotizacionId || editarPedidoId) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Si venimos con parámetro editar, abrir el modal de edición automáticamente
            if (editarPedidoId) {
                // Usar un timeout de 2 segundos para asegurar que todo esté completamente cargado
                setTimeout(function () {
                    console.log('Procesando pedido para editar:', editarPedidoId);

                    // Verificar que las funciones necesarias existan
                    if (typeof window.addProductItem !== 'function') {
                        console.error('window.addProductItem no está disponible');
                        return;
                    }

                    // El toast se muestra después de que el modal se abra (ver abajo)

                    // Configurar el modal para edición
                    $('#modalTitle').text('Completar Pedido #' + editarPedidoId);
                    $('#id-field').val(editarPedidoId);
                    $('#add-btn').hide();
                    $('#edit-btn').show();
                    $('#estado-field-wrapper').hide();
                    $('#productos-container').empty();

                    // Cargar datos del pedido
                    $.ajax({
                        url: '/pedidos/' + editarPedidoId,
                        method: 'GET',
                        success: function (data) {
                            console.log('Datos del pedido cargados:', data);

                            // Cargar cliente y bloquear campos visualmente
                            $('#cliente-id-field').val(data.cliente_id || '');
                            $('#cliente-nombre-field').val(data.cliente_nombre_completo || data.cliente_nombre || '')
                                .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');
                            $('#cliente-email-field').val(data.cliente_email_normalizado || data.cliente_email || '')
                                .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');
                            $('#cliente-telefono-field').val(data.cliente_telefono_normalizado || data.cliente_telefono || '')
                                .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');

                            // Detectar prefijo del documento (usa cliente_documento del accessor)
                            var ciRif = data.cliente_documento || '';
                            console.log('Documento del cliente:', ciRif);
                            var prefix = ciRif.match(/^(V-|J-|E-|G-)/);

                            $('#ci-rif-prefix-field').val(prefix ? prefix[0] : 'V-')
                                .prop('disabled', true).addClass('bg-light').css('cursor', 'not-allowed');
                            $('#ci-rif-number-field').val(ciRif.replace(/^(V-|J-|E-|G-)/, ''))
                                .prop('readonly', true).addClass('bg-light').css('cursor', 'not-allowed');
                            $('#ci-rif-full-field').val(ciRif);

                            // Cargar fechas
                            $('#fecha-pedido-field').val(data.fecha_pedido || '');
                            $('#fecha-entrega-estimada-field').val(data.fecha_entrega_estimada || '');
                            $('#estado-field').val(data.estado);

                            // Cargar campos de pago y prioridad
                            $('#abono-field').val(data.abono || 0);
                            $('#efectivo-pagado-field').prop('checked', data.efectivo_pagado);
                            $('#transferencia-pagado-field').prop('checked', data.transferencia_pagado);
                            $('#pago-movil-pagado-field').prop('checked', data.pago_movil_pagado);
                            $('#referencia-transferencia-field').val(data.referencia_transferencia || '');
                            $('#referencia-pago-movil-field').val(data.referencia_pago_movil || '');
                            if (data.banco_id) {
                                $('#banco-id-field').val(data.banco_id).trigger('change');
                            }
                            $('#prioridad-field').val(data.prioridad || 'Normal');

                            if (typeof currentPedidoTotal !== 'undefined') {
                                currentPedidoTotal = parseFloat(data.total) || 0;
                            }
                            $('#total-display-field').val((parseFloat(data.total) || 0).toFixed(2));

                            // Llenar productos del pedido
                            if (data.productos && data.productos.length > 0) {
                                data.productos.forEach(function (item) {
                                    var insumosTransformados = [];
                                    if (item.insumos && item.insumos.length > 0) {
                                        insumosTransformados = item.insumos.map(function (insumo) {
                                            return {
                                                insumo_id: insumo.id,
                                                cantidad_estimada: insumo.pivot ? insumo.pivot.cantidad_estimada : 0
                                            };
                                        });
                                    }
                                    window.addProductItem(
                                        item.producto_id,
                                        item.cantidad,
                                        item.precio_unitario,
                                        item.descripcion || '',
                                        item.lleva_bordado || false,
                                        item.nombre_logo || '',
                                        item.color || '',
                                        item.talla || '',
                                        insumosTransformados,
                                        item.ubicacion_logo || '',
                                        item.cantidad_logo || 1,
                                        item.bordados || []
                                    );
                                });
                                if (typeof calculateProductTotals === 'function') {
                                    calculateProductTotals();
                                }
                            } else {
                                // Sin productos - mostrar placeholder
                                $('#productos-container').html('<div class="text-center text-muted py-3"><i class="ri-inbox-line fs-3 d-block mb-2"></i>Sin productos asociados</div>');
                                if (typeof calculateProductTotals === 'function') {
                                    calculateProductTotals();
                                }
                            }

                            if (typeof togglePaymentFieldsVisibility === 'function') {
                                togglePaymentFieldsVisibility();
                            }
                            if (typeof updateRemaining === 'function') {
                                updateRemaining();
                            }

                            // Abrir el modal
                            console.log('Abriendo modal...');
                            $('#showModal').modal('show');

                            // Mostrar Toast DESPUÉS de que el modal esté completamente abierto
                            $('#showModal').one('shown.bs.modal', function () {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Pedido #' + editarPedidoId + ' creado desde cotización',
                                    showConfirmButton: false,
                                    timer: 4000,
                                    timerProgressBar: true,
                                    backdrop: false,
                                });
                            });
                        },
                        error: function (xhr) {
                            console.error('Error cargando pedido:', xhr);
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo cargar los datos del pedido.',
                                icon: 'error'
                            });
                        }
                    });
                }, 2000); // 2 segundos de espera
            }
        });
    </script>
    @include('admin.pedidos.scripts.cotizacion_selection')
@endpush