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
    <!-- Autocorrección y Autocompletado -->
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-transactional">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Pedidos</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <div class="d-flex gap-2">
                                @if(Auth::user()->isAdmin())
                                    {{-- Un pedido solo nace de una cotización aprobada: abre el selector --}}
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
                    <div class="advanced-filters-wrapper emerald-theme" id="advanced-filters">
                        <div class="navy-filter-header is-collapsed">
                            <div class="navy-header-search">
                                <i class="ri-search-line"></i>
                                <input type="text" class="navy-search-input" id="custom-search-input"
                                    placeholder="Buscar pedido..." autocomplete="off">
                            </div>
                            <div class="navy-header-divider"></div>
                            <button class="navy-filter-btn collapsed" type="button"
                                data-bs-toggle="collapse" data-bs-target="#filters-collapse-body"
                                aria-expanded="false" aria-controls="filters-collapse-body">
                                <i class="ri-filter-3-line"></i>
                                <span>Filtros</span>
                                <span class="navy-filter-badge d-none" id="active-filter-count"></span>
                                <i class="ri-arrow-down-s-line navy-filter-chevron"></i>
                            </button>
                        </div>
                        <div class="collapse" id="filters-collapse-body">
                            <div class="navy-filter-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="navy-filter-label" for="filter-estado">
                                            <i class="ri-shield-check-line"></i> Estado
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-estado">
                                            <option value="">Todos los estados</option>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Procesando">Procesando</option>
                                            <option value="Completado">Completado</option>
                                            <option value="Cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="navy-filter-label" for="filter-fecha-entrega">
                                            <i class="ri-calendar-event-line"></i> Fecha de Entrega
                                        </label>
                                        <input type="date" class="form-control navy-filter-select" id="filter-fecha-entrega">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="navy-filter-label" for="filter-orden">
                                            <i class="ri-sort-desc"></i> Ordenar Por
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-orden">
                                            <option value="recientes">Recientes</option>
                                            <option value="monto_desc">Mayor monto</option>
                                            <option value="entrega_asc">Entrega mas proxima</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-link" id="btn-clear-filters">Limpiar filtros</button>
                                </div>
                            </div>
                        </div>
                    </div>
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

    {{-- #seleccionarCotizacionModal lo incluye admin.pedidos.modals (evita ID duplicado) --}}
    @include('admin.partials.catalog_modals')
    @include('admin.pedidos.modals')
@endsection

@push('scripts')
    <!-- DataTables desde CDN, después de jQuery -->
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
    <script src="{{ URL::asset('/assets/js/municipios-venezuela.js') }}"></script>

    @include('admin.pedidos.scripts.listado')
    @include('admin.pedidos.scripts.cotizacion_selection')
    @include('admin.pedidos.scripts.main')
@endpush
