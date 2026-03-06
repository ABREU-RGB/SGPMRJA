@extends('admin.layouts.app')

@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        .progress {
            height: 20px;
        }

        .estado-Pendiente {
            background-color: #ffc107;
        }

        .estado-EnProceso {
            background-color: #0dcaf0;
        }

        .estado-Finalizado {
            background-color: #198754;
        }

        .estado-Cancelado {
            background-color: #dc3545;
        }

        .select2-container {
            width: 100% !important;
        }

        #insumos-container .row {
            margin-bottom: 10px;
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

        /* ── DataTable — Estándar Atlántico Operativo ── */
        .card-body {
            overflow-x: auto;
        }

        /* Ocultar buscador por defecto de DataTables */
        .dataTables_filter {
            display: none;
        }

        #ordenes-table {
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px;
        }

        #ordenes-table th,
        #ordenes-table td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Anchos de columna (suman 100%) */
        #ordenes-table th:nth-child(1) { width: 8%;  text-align: center; } /* Nro. Orden         */
        #ordenes-table th:nth-child(2) { width: 8%;  text-align: center; } /* Nro. Pedido        */
        #ordenes-table th:nth-child(3) { width: 12%; text-align: center; } /* Cant. Solicitada   */
        #ordenes-table th:nth-child(4) { width: 18%; }                      /* Progreso           */
        #ordenes-table th:nth-child(5) { width: 15%; }                      /* Fecha Fin Estimada */
        #ordenes-table th:nth-child(6) { width: 14%; text-align: center; } /* Estado             */
        #ordenes-table th:nth-child(7) { width: 25%; text-align: center; } /* Acciones           */

        /* Estado y Acciones: overflow visible para dropdowns y botones */
        #ordenes-table td:nth-child(6),
        #ordenes-table td:last-child {
            overflow: visible;
            text-overflow: clip;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Órdenes de Producción</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión Operativa</a></li>
                        <li class="breadcrumb-item active">Órdenes</li>
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
                        <h5 class="card-title mb-0 flex-grow-1">Órdenes de Producción</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar orden...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Nueva Orden
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="ordenes-table" class="table table-bordered table-striped align-middle dt-transactional">
                        <thead>
                            <tr>
                                <th>Nro. Orden</th>
                                <th>Nro. Pedido</th>
                                <th>Cant. Solicitada</th>
                                <th>Progreso</th>
                                <th>Fecha Fin Estimada</th>
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

    @include('admin.ordenes.modals.create')
    @include('admin.ordenes.modals.view')
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @include('admin.ordenes.scripts.main')
@endpush