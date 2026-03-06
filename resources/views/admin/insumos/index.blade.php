@extends('admin.layouts.app')

@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style>
        .card-body {
            overflow-x: auto;
        }

        .stock-bajo {
            color: #dc3545;
        }

        .stock-medio {
            color: #ffc107;
        }

        .stock-normal {
            color: #198754;
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
        #insumos-table {
            width: 100% !important;
            font-size: 13px;
            table-layout: fixed;
        }

        #insumos-table th,
        #insumos-table td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Anchos de columna balanceados (100%) */
        #insumos-table th:nth-child(1) {
            width: 20%;
        }

        #insumos-table th:nth-child(2) {
            width: 13%;
        }

        #insumos-table th:nth-child(3) {
            width: 11%;
        }

        #insumos-table th:nth-child(4) {
            width: 14%;
        }

        #insumos-table th:nth-child(5) {
            width: 28%;
            min-width: 220px;
        }

        #insumos-table th:nth-child(6) {
            width: 14%;
            text-align: center;
        }

        #insumos-table td:last-child {
            text-align: center;
            overflow: visible;
        }

        #insumos-table thead th {
            background: #1e3c72 !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-color: #2a5298 !important;
        }

        #insumos-table td:nth-child(1),
        #insumos-table td:nth-child(5) {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #insumos-table.dataTable tbody tr {
            transition: background-color 0.16s ease;
        }

        #insumos-table.dataTable tbody tr td {
            border-top: 1px solid rgba(30, 60, 114, 0.07);
            border-bottom: 1px solid rgba(255, 255, 255, 0.7);
            background-clip: padding-box;
        }

        #insumos-table.dataTable tbody tr.odd td {
            background-color: #ffffff;
        }

        #insumos-table.dataTable tbody tr.even td {
            background-color: rgba(30, 60, 114, 0.065);
        }

        #insumos-table.dataTable tbody tr:hover td {
            background-color: rgba(30, 60, 114, 0.14) !important;
        }

        [data-bs-theme="dark"] #insumos-table.dataTable tbody tr td {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
        }

        [data-bs-theme="dark"] #insumos-table.dataTable tbody tr.odd td {
            background-color: rgba(255, 255, 255, 0.015);
        }

        [data-bs-theme="dark"] #insumos-table.dataTable tbody tr.even td {
            background-color: rgba(42, 82, 152, 0.2);
        }

        [data-bs-theme="dark"] #insumos-table.dataTable tbody tr:hover td {
            background-color: rgba(42, 82, 152, 0.34) !important;
        }

        /* Badges de tipo de insumo */
        .badge-tipo {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .badge-tipo-tela {
            background-color: rgba(111, 66, 193, 0.15);
            color: #6f42c1;
        }

        .badge-tipo-hilo {
            background-color: rgba(41, 156, 219, 0.15);
            color: #299cdb;
        }

        .badge-tipo-boton {
            background-color: rgba(247, 184, 75, 0.18);
            color: #e0a800;
        }

        .badge-tipo-cierre {
            background-color: rgba(0, 217, 165, 0.15);
            color: #00d9a5;
        }

        .badge-tipo-etiqueta {
            background-color: rgba(233, 30, 99, 0.15);
            color: #e91e63;
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
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Insumos</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión General</a></li>
                        <li class="breadcrumb-item active">Insumos</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-maestros">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Insumos</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar insumo...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Agregar Insumo
                                </button>
                                <a href="{{ route('insumos.reporte.pdf') }}" class="btn btn-danger" target="_blank">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="insumos-table" class="table table-bordered table-striped table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Stock Actual</th>
                                    <th>Costo Unit.</th>
                                    <th>Proveedor</th>
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
    </div>

    <!-- Modal para ver detalles -->
    <div class="modal fade atlantico-modal" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detalles del Insumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Nombre:</strong>
                        <p id="view-nombre" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Tipo:</strong>
                        <p id="view-tipo" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Unidad de Medida:</strong>
                        <p id="view-unidad-medida" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Stock Actual:</strong>
                        <p id="view-stock-actual" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Stock Mínimo:</strong>
                        <p id="view-stock-minimo" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Costo Unitario:</strong>
                        <p id="view-costo-unitario" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Proveedor:</strong>
                        <p id="view-proveedor" class="text-muted mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha de Registro:</strong>
                        <p id="view-created" class="text-muted mb-0"></p>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="modalTitle">Agregar Insumo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="insumoForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />

                        <div class="modal-form-section">
                            <div class="modal-form-section-title"><i class="ri-box-3-line"></i>Datos del Insumo</div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <x-forms.input name="nombre" label="Nombre" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.select name="tipo" label="Tipo" required
                                        :options="['Tela' => 'Tela', 'Hilo' => 'Hilo', 'Botón' => 'Botón', 'Cierre' => 'Cierre', 'Etiqueta' => 'Etiqueta']" />
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <x-forms.input name="unidad_medida" label="Unidad de Medida" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.select name="proveedor_id" label="Proveedor" required
                                        :options="$proveedores->pluck('razon_social', 'id')->toArray()" />
                                </div>
                            </div>
                        </div>

                        <div class="modal-form-section mb-0">
                            <div class="modal-form-section-title"><i class="ri-bar-chart-grouped-line"></i>Control de Inventario y Costo</div>

                            <div class="row mb-0">
                                <div class="col-md-4">
                                    <x-forms.input name="stock_actual" label="Stock Actual" type="number" step="0.01" min="0" value="0"
                                        required />
                                </div>
                                <div class="col-md-4">
                                    <x-forms.input name="stock_minimo" label="Stock Mínimo" type="number" step="0.01" min="0"
                                        required />
                                </div>
                                <div class="col-md-4">
                                    <x-forms.input name="costo_unitario" label="Costo Unitario" type="number" step="0.01" min="0"
                                        required />
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <x-forms.select name="estado" label="Estado" required
                                        :options="['1' => 'Activo', '0' => 'Inactivo']" placeholder="" value="1" />
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
                            <x-ui.button-save id="edit-btn" text="Actualizar" icon="ri-save-line"
                                loading-text="Actualizando..." style="display: none;" />
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
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            function generateButtons(insumoId) {
                return '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + insumoId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-success edit-item-btn" data-id="' + insumoId + '" title="Editar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-pencil-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-danger remove-item-btn" data-id="' + insumoId + '" title="Eliminar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-delete-bin-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '</div>';
            }

            function renderEllipsis(value) {
                if (!value) return '<span class="text-muted">—</span>';
                return '<span title="' + value + '" style="cursor:default;">' + value + '</span>';
            }

            var table = $('#insumos-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: false,
                ajax: "{{ route('insumos.data') }}",
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'csv',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'excel',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'print',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    }
                ],
                columns: [
                    {
                        data: 'nombre',
                        name: 'nombre',
                        width: '20%',
                        render: function (data) {
                            return renderEllipsis(data);
                        }
                    },
                    {
                        data: 'tipo',
                        name: 'tipo',
                        width: '13%',
                        render: function (data) {
                            var tipos = {
                                'Tela': '<span class="badge-tipo badge-tipo-tela"><i class="ri-t-shirt-line"></i> Tela</span>',
                                'Hilo': '<span class="badge-tipo badge-tipo-hilo"><i class="ri-links-line"></i> Hilo</span>',
                                'Botón': '<span class="badge-tipo badge-tipo-boton"><i class="ri-radio-button-line"></i> Botón</span>',
                                'Cierre': '<span class="badge-tipo badge-tipo-cierre"><i class="ri-lock-line"></i> Cierre</span>',
                                'Etiqueta': '<span class="badge-tipo badge-tipo-etiqueta"><i class="ri-price-tag-3-line"></i> Etiqueta</span>'
                            };
                            return tipos[data] || '<span class="badge-tipo"><i class="ri-more-line"></i> ' + data + '</span>';
                        }
                    },
                    {
                        data: 'stock_actual',
                        name: 'stock_actual',
                        width: '11%',
                        render: function (data, type, row) {
                            var stockClass = 'stock-' + row.stock_status;
                            return `<span class="${stockClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'costo_unitario',
                        name: 'costo_unitario',
                        width: '14%',
                        render: function (data) {
                            return '$/ ' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'proveedor_nombre',
                        name: 'proveedor.razon_social',
                        width: '28%',
                        render: function (data) {
                            return renderEllipsis(data);
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        width: '14%',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return generateButtons(data);
                        }
                    }
                ],
                order: [[0, 'desc']],
                language: lenguajeData
            });

            // Buscador personalizado
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Ver detalles
            $(document).on('click', '.view-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('insumos.show', ':id') }}".replace(':id', id), function (data) {
                    $("#view-nombre").text(data.nombre);
                    $("#view-tipo").text(data.tipo);
                    $("#view-unidad-medida").text(data.unidad_medida);
                    $("#view-stock-actual").text(data.stock_actual);
                    $("#view-stock-minimo").text(data.stock_minimo);
                    $("#view-costo-unitario").text('$/ ' + parseFloat(data.costo_unitario).toFixed(2));
                    $("#view-proveedor").text(data.proveedor ? data.proveedor.razon_social : 'Sin proveedor');
                    $("#view-created").text(data.created_at);
                    $("#viewModal").modal('show');
                });
            });

            // Editar
            $(document).on('click', '.edit-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('insumos.show', ':id') }}".replace(':id', id), function (data) {
                    $("#modalTitle").text("Editar Insumo");
                    $("#id-field").val(data.id);
                    $("#field-nombre").val(data.nombre);
                    $("#field-tipo").val(data.tipo);
                    $("#field-unidad_medida").val(data.unidad_medida);
                    $("#field-stock_actual").val(data.stock_actual);
                    $("#field-stock_minimo").val(data.stock_minimo);
                    $("#field-costo_unitario").val(data.costo_unitario);
                    $("#field-proveedor_id").val(data.proveedor_id);
                    $("#field-estado").val(data.estado ? '1' : '0');

                    $("#add-btn").hide();
                    $("#edit-btn").show();
                    $("#showModal").modal('show');
                });
            });

            // Enviar formulario
            $("#insumoForm").on("submit", function (e) {
                e.preventDefault();

                if (!validator.validateAll()) {
                    return;
                }
                var id = $("#id-field").val();
                var url = id ? "{{ route('insumos.update', ':id') }}".replace(':id', id) : "{{ route('insumos.store') }}";
                var method = id ? "PUT" : "POST";

                var formData = new FormData(this);
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
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs me-2',
                                cancelButton: 'btn btn-danger w-xs'
                            },
                            buttonsStyling: false,
                            showCloseButton: true,
                            timer: 1500
                        });
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
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
                    buttonsStyling: false,
                    showCloseButton: true
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('insumos.destroy', ':id') }}".replace(':id', id),
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
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true,
                                    timer: 1500
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo eliminar el insumo',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });
                            }
                        });
                    }
                });
            });

            // Limpiar modal al cerrar
            $("#showModal").on("hidden.bs.modal", function () {
                $("#modalTitle").text("Agregar Insumo");
                $("#insumoForm")[0].reset();
                $("#id-field").val("");
                $("#add-btn").show();
                $("#add-btn").show();
                $("#edit-btn").hide();
                validator.resetValidation();
            });

            const validator = new FormValidator('insumoForm');
        });
    </script>
@endpush