@extends('admin.layouts.app')

@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Producción Diaria</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión Operativa</a></li>
                        <li class="breadcrumb-item active">Producción Diaria</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-transactional">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Registro de Producción Diaria</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                data-bs-target="#showModal">
                                <i class="ri-add-line align-bottom me-1"></i> Registrar Producción
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="advanced-filters-wrapper navy-theme" id="advanced-filters">
                        <div class="navy-filter-header is-collapsed">
                            <div class="navy-header-search">
                                <i class="ri-search-line"></i>
                                <input type="text" class="navy-search-input" id="custom-search-input"
                                    placeholder="Buscar registro..." autocomplete="off">
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
                                        <label class="navy-filter-label" for="filter-empleado">
                                            <i class="ri-user-line"></i> Empleado
                                        </label>
                                        <select class="form-select navy-filter-select" id="filter-empleado">
                                            <option value="">Todos</option>
                                            @foreach ($empleados as $empleado)
                                                <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="navy-filter-label" for="filter-fecha-desde">
                                            <i class="ri-calendar-line"></i> Desde
                                        </label>
                                        <input type="date" class="form-control navy-filter-select" id="filter-fecha-desde">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="navy-filter-label" for="filter-fecha-hasta">
                                            <i class="ri-calendar-2-line"></i> Hasta
                                        </label>
                                        <input type="date" class="form-control navy-filter-select" id="filter-fecha-hasta">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-link" id="btn-clear-filters">Limpiar filtros</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="produccion-table"
                        class="table table-bordered dt-responsive nowrap table-striped align-middle dt-transactional table-operativa">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Orden</th>
                                <th>Empleado</th>
                                <th>Cant. Producida</th>
                                <th>Cant. Defectuosa</th>
                                <th>Eficiencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.produccion.diaria.modals.create')
    @include('admin.produccion.diaria.modals.view')
    @include('admin.produccion.diaria.modals.edit')
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Incluir Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    @include('admin.produccion.diaria.scripts.main')
@endpush