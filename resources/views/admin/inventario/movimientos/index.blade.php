@extends('admin.layouts.app')

@section('title', 'Control de Inventario')
@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <style>
        /* Ocultar modal temporalmente cuando hay otro modal encima */
        .modal-hidden-temp {
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* ── DataTable — Estándar Atlántico Operativo ── */
        .card-body {
            overflow-x: auto;
        }

        /* Ocultar buscador por defecto de DataTables */
        .dataTables_filter {
            display: none;
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

        #movimientos-table {
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px;
        }

        #movimientos-table th,
        #movimientos-table td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Anchos de columna (suman 100%) */
        #movimientos-table th:nth-child(1) { width: 30%; }                      /* Insumo          */
        #movimientos-table th:nth-child(2) { width: 18%; text-align: center; } /* Tipo Movimiento */
        #movimientos-table th:nth-child(3) { width: 12%; text-align: center; } /* Cantidad        */
        #movimientos-table th:nth-child(4) { width: 12%; text-align: center; } /* Stock Nuevo     */
        #movimientos-table th:nth-child(5) { width: 14%; }                      /* Fecha           */
        #movimientos-table th:nth-child(6) { width: 14%; text-align: center; } /* Acciones        */

        /* Tipo, Cantidad, Stock Nuevo y Acciones: overflow visible para badges y botones */
        #movimientos-table td:nth-child(2),
        #movimientos-table td:nth-child(3),
        #movimientos-table td:nth-child(4),
        #movimientos-table td:last-child {
            overflow: visible;
            text-overflow: clip;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Control de Inventario</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión Operativa</a></li>
                            <li class="breadcrumb-item active">Movimientos</li>
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
                            <h5 class="card-title mb-0 flex-grow-1">Movimientos de Inventario</h5>
                            <div class="flex-shrink-0 d-flex align-items-center gap-3">
                                <!-- Buscador Personalizado -->
                                <div class="search-box">
                                    <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                        placeholder="Buscar movimiento...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Registrar Movimiento
                                    </button>
                                    <a href="{{ route('inventario.alertas') }}" class="btn btn-warning">
                                        <i class="ri-alert-line align-bottom me-1"></i> Alertas de Stock
                                    </a>
                                    <a href="{{ route('inventario.reporte') }}" class="btn btn-danger">
                                        <i class="ri-file-list-3-line align-bottom me-1"></i> Reporte de Inventario
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="movimientos-table" class="table table-bordered table-striped align-middle dt-transactional">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Tipo Movimiento</th>
                                    <th>Cantidad</th>
                                    <th>Stock Nuevo</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables llenará esto -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.inventario.movimientos.modals.create')
    @include('admin.inventario.movimientos.modals.view')
    @include('admin.inventario.movimientos.modals.create_insumo')

@endsection

@push('scripts')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    @include('admin.inventario.movimientos.scripts.main')
@endpush