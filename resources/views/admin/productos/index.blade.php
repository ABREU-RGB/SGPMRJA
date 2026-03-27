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
                <h4 class="mb-sm-0">Gestión de Productos</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gestión General</a></li>
                        <li class="breadcrumb-item active">Productos</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Estilos en public/assets/css/custom.css — sección "MÓDULO MAESTROS — Productos" --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-maestros">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Productos</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Toggle Historial -->
                            @if($historial)
                                <a href="{{ route('productos.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-list-check align-bottom me-1"></i> Solo Activos
                                </a>
                            @else
                                <a href="{{ route('productos.index', ['historial' => true]) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="ri-history-line align-bottom me-1"></i> Ver Historial (Inactivos)
                                </a>
                            @endif
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar producto...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            @if(!$historial)
                            <div class="d-flex gap-2 align-items-center">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#tiposModal">
                                    <i class="ri-settings-3-line align-bottom me-1"></i> Gestionar Tipos
                                </button>
                                <button type="button" class="btn btn-success add-btn ms-2" data-bs-toggle="modal"
                                    id="create-btn" data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Agregar Producto
                                </button>
                                <a href="{{ route('productos.reporte.pdf') }}" target="_blank" class="btn btn-danger ms-2">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                                </a>
                            </div>
                            @else
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('productos.reporte.pdf') }}" target="_blank" class="btn btn-danger">
                                    <i class="ri-file-pdf-fill align-bottom me-1"></i> Exportar PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="productos-table" class="table table-bordered table-striped table-sm align-middle table-operativa table-maestro">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Imagen</th>
                                <th>Tipo</th>
                                <th>Modelo</th>
                                <th>Precio Base</th>
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

    <!-- Modal para ver detalles del Producto -->
    <div class="modal fade atlantico-modal" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Imagen del Producto centrada -->
                    <div class="card border-0 shadow-sm mb-4" id="producto-imagen-container" style="display: none;">
                        <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                            <h6 class="mb-0" style="color: #1e3c72;">
                                <i class="ri-image-line me-2"></i>Vista del Producto
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="rounded mx-auto d-inline-block p-2" style="background: rgba(30, 60, 114, 0.05);">
                                <img id="producto-imagen" src="" alt="Imagen del producto" class="rounded"
                                    style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <!-- Card Información del Producto -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                            <h6 class="mb-0" style="color: #1e3c72;">
                                <i class="ri-information-line me-2"></i>Información del Producto
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center producto-info-item">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-price-tag-3-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Nombre</small>
                                            <span class="fw-semibold" id="view-nombre">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center producto-info-item">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-hashtag" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Modelo</small>
                                            <span class="fw-semibold" id="view-modelo">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center producto-info-item">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-money-dollar-circle-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Precio Base</small>
                                            <span class="fw-semibold" id="view-precio">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center producto-info-item">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-calendar-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Fecha de Creación</small>
                                            <span class="fw-semibold" id="view-created">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-start producto-info-item">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-file-text-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Descripción</small>
                                            <span class="fw-semibold" id="view-descripcion">-</span>
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
                    <h5 class="modal-title" id="modalTitle">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="modal-form-section">
                                    <div class="modal-form-section-title"><i class="ri-price-tag-3-line"></i>Identificación
                                        del Producto</div>

                                    {{-- Tipo de Producto — mantiene HTML custom por data-prefijo + botón "+" --}}
                                    <div class="mb-3">
                                        <label for="tipo-producto-field" class="form-label">Tipo de Producto <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select id="tipo-producto-field" name="tipo_producto_id" class="form-select"
                                                required>
                                                <option value="">Seleccione un tipo...</option>
                                                @foreach($tiposProducto as $tipo)
                                                    <option value="{{ $tipo->id }}" data-prefijo="{{ $tipo->codigo_prefijo }}">
                                                        {{ $tipo->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#addTipoModal" title="Agregar nuevo tipo">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <x-forms.input name="modelo" label="Modelo"
                                        placeholder="Ej: Polo Clásica, Cuello V, Drill Industrial" required
                                        id="modelo-field" />
                                    <x-forms.input name="codigo" label="Código" readonly class="bg-light"
                                        placeholder="Se genera automáticamente"
                                        hint="El código se genera al seleccionar el tipo de producto" id="codigo-field" />

                                    <div class="mb-0">
                                        <label for="descripcion-field" class="form-label">Descripción <span
                                                class="text-danger">*</span></label>
                                        <textarea id="descripcion-field" name="descripcion" class="form-control" rows="3"
                                            placeholder="Descripción adicional del producto" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-form-section mb-0">
                                    <div class="modal-form-section-title"><i class="ri-money-dollar-circle-line"></i>Precio,
                                        Imagen y Estado</div>

                                    <x-forms.input name="precio_base" label="Precio Base ($)" type="number" step="0.01"
                                        min="0" placeholder="0.00" required id="precio-base-field" />

                                    {{-- Imagen — mantiene HTML nativo por preview --}}
                                    <div class="mb-3">
                                        <label for="imagen-field" class="form-label">Imagen <span
                                                class="text-danger" id="imagen-required-star">*</span></label>
                                        <input type="file" id="imagen-field" name="imagen" class="form-control"
                                            accept="image/*" required />
                                        <div id="imagen-preview" class="mt-2 text-center" style="display: none;">
                                            <img src="" alt="Vista previa de la imagen" class="img-fluid"
                                                style="max-width: 200px;">
                                        </div>
                                    </div>

                                    {{-- Switch de Estado sincronizado con hidden input --}}
                                    <div class="mb-3">
                                        <label class="form-label mb-2">Estado <span class="text-danger">*</span></label>
                                        <div class="form-check form-switch form-switch-success form-switch-md" dir="ltr">
                                            <input type="checkbox" class="form-check-input" id="estado-switch" checked>
                                            <label class="form-check-label fw-medium" for="estado-switch" id="estado-label">Activo</label>
                                        </div>
                                        <input type="hidden" name="estado" id="estado-hidden-field" value="1">
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
                            <x-ui.button-save id="edit-btn" text="Actualizar" icon="ri-save-line"
                                loading-text="Actualizando..." style="display: none;" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar Tipos de Producto -->
    <div class="modal fade atlantico-modal" id="tiposModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Gestionar Tipos de Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tipos-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Prefijo</th>
                                    <th>Productos</th>
                                    <th width="100">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tipos-tbody">
                                <!-- Se llena con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTipoModal">
                        <i class="ri-add-line me-1"></i>Agregar Tipo
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar Tipo de Producto -->
    <div class="modal fade atlantico-modal" id="addTipoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="tipoModalTitle">Agregar Tipo de Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="tipoForm">
                    <div class="modal-body">
                        <input type="hidden" id="tipo-id-field" />
                        <div class="modal-form-section mb-0">
                            <div class="modal-form-section-title"><i class="ri-list-settings-line"></i>Datos del Tipo de
                                Producto</div>

                            <div class="mb-3">
                                <label for="tipo-nombre-field" class="form-label required">Nombre del Tipo</label>
                                <input type="text" id="tipo-nombre-field" name="nombre" class="form-control"
                                    placeholder="Ej: Chemise, Franela, Pantalón" required />
                                <div id="tipo-nombre-error" class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="tipo-prefijo-field" class="form-label required">Prefijo de Código</label>
                                <input type="text" id="tipo-prefijo-field" name="codigo_prefijo" class="form-control"
                                    placeholder="Ej: CHM, FRN, PNT (máx 5 letras)" maxlength="5" required
                                    style="text-transform: uppercase;" />
                                <div id="tipo-prefijo-error" class="invalid-feedback"></div>
                                <small class="text-muted">Se usará para generar códigos como CHM-001</small>
                            </div>
                            <div class="mb-0">
                                <label for="tipo-descripcion-field" class="form-label required">Descripción</label>
                                <textarea id="tipo-descripcion-field" name="descripcion" class="form-control" rows="2"
                                    placeholder="Descripción opcional" required></textarea>
                                <div id="tipo-descripcion-error" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success" id="save-tipo-btn">
                            <i class="ri-save-line me-1"></i>Guardar
                        </button>
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
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        // Configurar pdfMake para evitar errores de fuentes
        if (typeof pdfMake !== 'undefined' && typeof pdfFonts !== 'undefined') {
            pdfMake.vfs = pdfFonts.pdfMake.vfs;
        }

        // Configuración alternativa para evitar errores de fuentes
        if (typeof pdfMake !== 'undefined') {
            pdfMake.fonts = {
                Roboto: {
                    normal: 'Roboto-Regular.ttf',
                    bold: 'Roboto-Medium.ttf',
                    italics: 'Roboto-Italic.ttf',
                    bolditalics: 'Roboto-MediumItalic.ttf'
                }
            };
        }

        $(document).ready(function () {
            var esHistorial = {{ $historial ? 'true' : 'false' }};

            function generateButtons(productoId, isTrashed) {
                // Si el registro está inhabilitado (trashed), solo mostrar botón "Ver"
                if (isTrashed) {
                    return '<div class="d-flex gap-1 justify-content-center">' +
                        '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + productoId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                        '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                        '</button>' +
                        '</div>';
                }
                return '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + productoId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-success edit-item-btn" data-id="' + productoId + '" title="Editar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-pencil-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-danger remove-item-btn" data-id="' + productoId + '" title="Inhabilitar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-forbid-line" style="font-size:13px;"></i>' +
                    '</button>' +
                    '</div>';
            }

            function renderEllipsis(value) {
                if (!value) return '<span class="text-muted">—</span>';
                return '<span title="' + value + '" style="cursor:default;">' + value + '</span>';
            }

            var table = $('#productos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('productos.data') }}" + (esHistorial ? '?historial=true' : ''),
                columns: [
                    {
                        data: 'codigo',
                        name: 'codigo',
                        render: function (data) {
                            return data ? '<span class="badge bg-dark">' + data + '</span>' : '-';
                        }
                    },
                    {
                        data: 'imagen',
                        name: 'imagen',
                        render: function (data) {
                            return data ? '<img src="' + data + '" alt="Imagen del producto" class="img-thumbnail" width="44" style="height:44px; object-fit:cover;">' : '<span class="text-muted">Sin imagen</span>';
                        }
                    },
                    {
                        data: 'tipo_nombre',
                        name: 'tipo_nombre',
                        render: function (data) {
                            if (!data) return '<span class="text-muted">—</span>';
                            return '<span class="badge badge-tipo badge-tipo-producto" title="' + data + '"><i class="ri-price-tag-3-line"></i> ' + data + '</span>';
                        }
                    },
                    {
                        data: 'modelo',
                        name: 'modelo',
                        render: function (data) {
                            return renderEllipsis(data);
                        }
                    },
                    {
                        data: 'precio_base',
                        name: 'precio_base',
                        render: function (data) {
                            return '$ ' + parseFloat(data).toFixed(2);
                        }
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        render: function (data, type, row) {
                            // Si está en historial (trashed), mostrar badge "Inactivo"
                            if (row.trashed) {
                                return '<span class="badge badge-status status-inactivo"><i class="ri-close-circle-line"></i> Inactivo</span>';
                            }
                            return data ? '<span class="badge badge-status status-activo"><i class="ri-checkbox-circle-line"></i> Activo</span>' : '<span class="badge badge-status status-inactivo"><i class="ri-close-circle-line"></i> Inactivo</span>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return generateButtons(data, row.trashed);
                        }
                    }
                ],
                order: [[1, 'desc']], // Cambiar el índice de ordenamiento (ahora la columna "Nombre" es la índice 1)
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4] // Excluir la columna de acciones (índice 5)
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4] // Excluir la columna de imagen (índice 0) y de acciones (índice 5)
                        }
                    }
                ],
                language: lenguajeData
            });

            // Buscador personalizado
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Sincronizar switch de estado con hidden input
            $("#estado-switch").on('change', function() {
                var isChecked = $(this).is(':checked');
                $("#estado-hidden-field").val(isChecked ? '1' : '0');
                $("#estado-label").text(isChecked ? 'Activo' : 'Inactivo');
            });

            // Vista previa de imagen
            $("#imagen-field").change(function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#imagen-preview img").attr('src', e.target.result);
                        $("#imagen-preview").show();
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Ver detalles
            $(document).on('click', '.view-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('productos.show', ':id') }}".replace(':id', id), function (data) {
                    // Mostrar imagen solo si existe
                    if (data.imagen) {
                        $("#producto-imagen").attr('src', data.imagen);
                        $("#producto-imagen-container").show();
                    } else {
                        $("#producto-imagen-container").hide();
                    }

                    $("#view-nombre").text(data.nombre);
                    $("#view-descripcion").text(data.descripcion || 'Sin descripción');
                    $("#view-modelo").text(data.modelo);
                    $("#view-precio").text('$ ' + parseFloat(data.precio_base).toFixed(2));
                    $("#view-created").text(data.created_at);
                    $("#viewModal").modal('show');
                });
            });

            // Editar
            $(document).on('click', '.edit-item-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('productos.show', ':id') }}".replace(':id', id), function (data) {
                    $("#modalTitle").text("Editar Producto");
                    $("#id-field").val(data.id);
                    $("#tipo-producto-field").val(data.tipo_producto_id);
                    $("#codigo-field").val(data.codigo);
                    $("#descripcion-field").val(data.descripcion);
                    $("#modelo-field").val(data.modelo);
                    $("#precio-base-field").val(data.precio_base);
                    var isActivo = data.estado ? true : false;
                    $("#estado-switch").prop('checked', isActivo);
                    $("#estado-hidden-field").val(isActivo ? '1' : '0');
                    $("#estado-label").text(isActivo ? 'Activo' : 'Inactivo');

                    if (data.imagen) {
                        $("#imagen-preview img").attr('src', data.imagen);
                        $("#imagen-preview").show();
                        // Al editar con imagen existente, no es obligatorio subir una nueva
                        $("#imagen-field").prop('required', false);
                        $("#imagen-required-star").addClass('d-none');
                    } else {
                        // Si no tiene imagen (caso raro), pedimos una
                        $("#imagen-field").prop('required', true);
                        $("#imagen-required-star").removeClass('d-none');
                    }

                    $("#add-btn").hide();
                    $("#edit-btn").show();
                    $("#showModal").modal('show');
                });
            });

            // Generar código automático al seleccionar tipo o escribir modelo
            function actualizarCodigoPreview() {
                var tipoId = $("#tipo-producto-field").val();
                var modelo = $("#modelo-field").val();
                var isEditing = $("#id-field").val() !== "";

                if (tipoId && !isEditing) {
                    $.get("{{ url('tipo-productos') }}/" + tipoId + "/proximo-codigo", { modelo: modelo }, function (response) {
                        $("#codigo-field").val(response.codigo);
                    });
                } else if (!tipoId) {
                    $("#codigo-field").val("");
                }
            }

            $("#tipo-producto-field").on("change", actualizarCodigoPreview);

            // Actualizar código cuando el usuario escribe el modelo (con delay)
            var modeloTimer;
            $("#modelo-field").on("keyup", function () {
                clearTimeout(modeloTimer);
                modeloTimer = setTimeout(actualizarCodigoPreview, 500);
            });

            // Enviar formulario
            $("#productoForm").on("submit", function (e) {
                e.preventDefault();

                if (!validator.validateAll()) {
                    return;
                }
                var id = $("#id-field").val();
                var url = id ? "{{ route('productos.update', ':id') }}".replace(':id', id) : "{{ route('productos.store') }}";
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
                    text: "El producto será inhabilitado y moverá al historial.",
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
                            url: "{{ route('productos.destroy', ':id') }}".replace(':id', id),
                            method: "DELETE",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Inhabilitado!',
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
                                    text: 'No se pudo inhabilitar el producto',
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
                $("#modalTitle").text("Agregar Producto");
                $("#productoForm")[0].reset();
                $("#id-field").val("");
                $("#codigo-field").val("");
                $("#imagen-preview").hide();
                // Para nuevo producto, la imagen es obligatoria
                $("#imagen-field").prop('required', true);
                $("#imagen-required-star").removeClass('d-none');
                // Reset switch de estado a Activo por defecto
                $("#estado-switch").prop('checked', true);
                $("#estado-hidden-field").val("1");
                $("#estado-label").text("Activo");
                $("#add-btn").show();
                $("#edit-btn").hide();
                validator.resetValidation();
                tipoValidator.resetValidation();
            });

            const validator = new FormValidator('productoForm');
            const tipoValidator = new FormValidator('tipoForm');

            // ===============================
            // Funciones para Tipos de Producto
            // ===============================

            // Cargar tipos al abrir modal de gestión
            $("#tiposModal").on("show.bs.modal", function () {
                cargarTipos();
            });

            function cargarTipos() {
                $.get("{{ route('tipo-productos.index') }}", function (tipos) {
                    var tbody = $("#tipos-tbody");
                    tbody.empty();

                    tipos.forEach(function (tipo) {
                        tbody.append(`
                                            <tr>
                                                <td>${tipo.nombre}</td>
                                                <td><span class="badge bg-secondary">${tipo.codigo_prefijo}</span></td>
                                                <td><span class="badge bg-info">${tipo.productos_count}</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary edit-tipo-btn" 
                                                        data-id="${tipo.id}" 
                                                        data-nombre="${tipo.nombre}" 
                                                        data-prefijo="${tipo.codigo_prefijo}"
                                                        data-descripcion="${tipo.descripcion || ''}">
                                                        <i class="ri-pencil-line"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-tipo-btn" data-id="${tipo.id}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        `);
                    });
                });
            }

            // Validaciones AJAX onblur para Tipos de Producto

            // 1. Nombre
            $('#tipo-nombre-field').on('blur', function () {
                let value = $(this).val();
                let $input = $(this);
                let $error = $('#tipo-nombre-error');
                let isEdit = $('#tipo-id-field').val() !== '';

                if (value.length > 0 && !isEdit) {
                    $.get("{{ route('tipo-productos.check-nombre') }}", { nombre: value }, function (res) {
                        if (res.exists) {
                            $input.addClass('is-invalid');
                            $error.text('Este nombre ya existe').show();
                            $('#save-tipo-btn').prop('disabled', true);
                        } else {
                            $input.removeClass('is-invalid').addClass('is-valid');
                            $error.hide();
                            $('#save-tipo-btn').prop('disabled', false);
                        }
                    });
                }
            });

            // 2. Prefijo
            $('#tipo-prefijo-field').on('blur', function () {
                let value = $(this).val();
                let $input = $(this);
                let $error = $('#tipo-prefijo-error');
                let isEdit = $('#tipo-id-field').val() !== '';

                if (value.length > 0 && !isEdit) {
                    $.get("{{ route('tipo-productos.check-codigo') }}", { codigo: value }, function (res) {
                        if (res.exists) {
                            $input.addClass('is-invalid');
                            $error.text('Este prefijo ya existe').show();
                            $('#save-tipo-btn').prop('disabled', true);
                        } else {
                            $input.removeClass('is-invalid').addClass('is-valid');
                            $error.hide();
                            $('#save-tipo-btn').prop('disabled', false);
                        }
                    });
                }
            });

            // Limpiar validaciones al cerrar modal de tipo
            $("#addTipoModal").on("hidden.bs.modal", function () {
                $('#tipoForm')[0].reset();
                $('#tipo-id-field').val('');
                $('#tipoModalTitle').html('<i class="ri-add-line me-2"></i>Agregar Tipo de Producto');
                $('.is-invalid').removeClass('is-invalid');
                $('.is-valid').removeClass('is-valid');
                $('.invalid-feedback').hide();
                $('#save-tipo-btn').prop('disabled', false);
            });

            // Editar tipo
            $(document).on("click", ".edit-tipo-btn", function () {
                var id = $(this).data("id");
                var nombre = $(this).data("nombre");
                var prefijo = $(this).data("prefijo");
                var descripcion = $(this).data("descripcion");

                $("#tipo-id-field").val(id);
                $("#tipo-nombre-field").val(nombre);
                $("#tipo-prefijo-field").val(prefijo);
                $("#tipo-descripcion-field").val(descripcion);
                $("#tipoModalTitle").html('<i class="ri-pencil-line me-2"></i>Editar Tipo de Producto');

                $("#tiposModal").modal('hide');
                $("#addTipoModal").modal('show');
            });

            // Eliminar tipo
            $(document).on("click", ".delete-tipo-btn", function () {
                var id = $(this).data("id");

                Swal.fire({
                    title: '¿Eliminar tipo?',
                    text: "Solo se puede eliminar si no tiene productos asociados",
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
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('tipo-productos') }}/" + id,
                            method: "DELETE",
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                cargarTipos();
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: response.message,
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    icon: 'error',
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

            // Guardar tipo
            $("#tipoForm").on("submit", function (e) {
                e.preventDefault();

                if (!tipoValidator.validateAll()) {
                    return;
                }

                var id = $("#tipo-id-field").val();
                var url = id ? "{{ url('tipo-productos') }}/" + id : "{{ route('tipo-productos.store') }}";
                var method = id ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        nombre: $("#tipo-nombre-field").val(),
                        codigo_prefijo: $("#tipo-prefijo-field").val().toUpperCase(),
                        descripcion: $("#tipo-descripcion-field").val()
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        $("#addTipoModal").modal('hide');

                        // Actualizar select de tipos
                        actualizarSelectTipos();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
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
                        var errors = xhr.responseJSON.errors || {};
                        var message = xhr.responseJSON.message || 'Error al guardar';
                        Swal.fire({
                            title: 'Error',
                            text: message,
                            icon: 'error',
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

            // Actualizar select de tipos después de agregar uno nuevo
            function actualizarSelectTipos() {
                $.get("{{ route('tipo-productos.index') }}", function (tipos) {
                    var select = $("#tipo-producto-field");
                    select.find("option:not(:first)").remove();

                    tipos.forEach(function (tipo) {
                        select.append(`<option value="${tipo.id}" data-prefijo="${tipo.codigo_prefijo}">${tipo.nombre}</option>`);
                    });
                });
            }

            // Limpiar modal de tipo al cerrar
            $("#addTipoModal").on("hidden.bs.modal", function () {
                $("#tipoForm")[0].reset();
                $("#tipo-id-field").val("");
                $("#tipoModalTitle").html('<i class="ri-add-line me-2"></i>Agregar Tipo de Producto');
            });
        });
    </script>
@endpush