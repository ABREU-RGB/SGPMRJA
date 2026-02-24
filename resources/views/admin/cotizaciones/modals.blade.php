<!-- Modal para ver detalles de Cotización -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detalles de la Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Columna Izquierda -->
                    <div class="col-lg-6">
                        <!-- Card Datos del Cliente -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header border-0" style="background: rgba(0, 217, 165, 0.1);">
                                <h6 class="mb-0" style="color: #00d9a5;">
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
                                                <small class="text-muted d-block">Nombre</small>
                                                <span class="fw-semibold" id="view-cliente-nombre">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: rgba(0, 217, 165, 0.1);">
                                                <i class="ri-user-follow-line" style="color: #00d9a5;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Apellido</small>
                                                <span class="fw-semibold" id="view-cliente-apellido">-</span>
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
                                                <i class="ri-phone-line" style="color: #00d9a5;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Teléfono</small>
                                                <span class="fw-semibold" id="view-cliente-telefono">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-mail-line" style="color: #1e3c72;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Email</small>
                                                <span class="fw-semibold" id="view-cliente-email">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Datos de la Cotización -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header border-0" style="background: rgba(46, 204, 113, 0.1);">
                                <h6 class="mb-0" style="color: #2ecc71;">
                                    <i class="ri-calendar-todo-line me-2"></i>Datos de la Cotización
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
                                                <small class="text-muted d-block">Fecha Cotización</small>
                                                <span class="fw-semibold" id="view-fecha-cotizacion">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: rgba(0, 217, 165, 0.1);">
                                                <i class="ri-calendar-check-line" style="color: #00d9a5;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Fecha Validez</small>
                                                <span class="fw-semibold" id="view-fecha-validez">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: rgba(46, 204, 113, 0.1);">
                                                <i class="ri-flag-line" style="color: #2ecc71;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Estado</small>
                                                <span class="fw-semibold" id="view-estado">-</span>
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
                                                <small class="text-muted d-block">Creado por</small>
                                                <span class="fw-semibold" id="view-usuario-creador">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Total -->
                        <div class="card border-0 shadow-sm"
                            style="background: linear-gradient(135deg, #1e3c72 0%, #00d9a5 100%);">
                            <div class="card-body text-center py-4">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="ri-money-dollar-circle-line text-white me-3" style="font-size: 3rem;"></i>
                                    <div class="text-start">
                                        <small class="text-white-50 d-block text-uppercase">Total de la
                                            Cotización</small>
                                        <h2 class="text-white mb-0 fw-bold" id="view-total">$0.00</h2>
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
                                    <i class="ri-shopping-bag-3-line me-2"></i>Productos de la Cotización
                                </h6>
                            </div>
                            <div class="card-body" style="max-height: 450px; overflow-y: auto;">
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
                <a href="#" id="view-pdf-btn" class="btn btn-warning" target="_blank">
                    <i class="ri-file-pdf-line me-1"></i>Descargar PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar Cotización -->
<div class="modal fade" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-3" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <h5 class="modal-title text-white" id="modalTitle">Agregar Cotización</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="cotizacionForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-3">
                    <input type="hidden" id="id-field" />
                    <input type="hidden" id="cliente-id-field" name="cliente_id" />
                    <div class="row g-3 align-items-start">

                        <!-- Columna Izquierda: Datos del Cliente + Estado -->
                        <div class="col-lg-5"
                            style="background: rgba(30, 60, 114, 0.025); border-radius: 8px; padding-top: 4px; padding-bottom: 4px;">

                            <!-- Card Cliente -->
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
                                        <!-- Documento -->
                                        <div class="col-12">
                                            <label for="ci-rif-number-field" class="form-label small fw-semibold mb-1">
                                                Documento de identidad <span class="text-danger">*</span>
                                            </label>
                                            <div class="position-relative">
                                                <div class="input-group">
                                                    <select class="form-select" id="ci-rif-prefix-field"
                                                        name="rif_prefix" style="max-width: 70px;">
                                                        <option value="V-">V-</option>
                                                        <option value="J-">J-</option>
                                                        <option value="E-">E-</option>
                                                        <option value="G-">G-</option>
                                                    </select>
                                                    <input type="text" id="ci-rif-number-field" name="rif_number"
                                                        class="form-control"
                                                        placeholder="Buscar cliente por documento..." autocomplete="off"
                                                        required />
                                                    <input type="hidden" id="ci-rif-full-field" name="ci_rif" />
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
                                        <!-- Nombre + Apellido -->
                                        <div class="col-6">
                                            <label for="cliente-nombre-field"
                                                class="form-label small fw-semibold mb-1">Nombre</label>
                                            <input type="text" id="cliente-nombre-field" name="cliente_nombre"
                                                class="form-control form-control-sm bg-light" readonly
                                                style="cursor: not-allowed;" />
                                        </div>
                                        <div class="col-6">
                                            <label for="cliente-apellido-field"
                                                class="form-label small fw-semibold mb-1">Apellido</label>
                                            <input type="text" id="cliente-apellido-field" name="cliente_apellido"
                                                class="form-control form-control-sm bg-light" readonly
                                                style="cursor: not-allowed;" />
                                        </div>
                                        <!-- Teléfono + Email -->
                                        <div class="col-6">
                                            <label for="cliente-telefono-field"
                                                class="form-label small fw-semibold mb-1">Teléfono</label>
                                            <input type="text" id="cliente-telefono-field" name="cliente_telefono"
                                                class="form-control form-control-sm bg-light" readonly
                                                style="cursor: not-allowed;" />
                                        </div>
                                        <div class="col-6">
                                            <label for="cliente-email-field"
                                                class="form-label small fw-semibold mb-1">Email</label>
                                            <input type="email" id="cliente-email-field" name="cliente_email"
                                                class="form-control form-control-sm bg-light" readonly
                                                style="cursor: not-allowed;" />
                                        </div>
                                        <!-- Fechas -->
                                        <div class="col-6">
                                            <label for="fecha-cotizacion-field"
                                                class="form-label small fw-semibold mb-1">
                                                Fecha Cotización <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" id="fecha-cotizacion-field" name="fecha_cotizacion"
                                                class="form-control form-control-sm" required />
                                        </div>
                                        <div class="col-6">
                                            <label for="fecha-validez-field" class="form-label small fw-semibold mb-1">
                                                Fecha Validez
                                            </label>
                                            <input type="date" id="fecha-validez-field" name="fecha_validez"
                                                class="form-control form-control-sm" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Estado (solo visible en edición) -->
                            <div class="card border-0 shadow-sm mb-3" id="estado-field-wrapper" style="display: none;">
                                <div class="card-header border-0 bg-soft-secondary">
                                    <h6 class="mb-0 text-atlantico-green">
                                        <i class="ri-flag-line me-2"></i>Estado de la Cotización
                                    </h6>
                                </div>
                                <div class="card-body py-2">
                                    <select id="estado-field" name="estado" class="form-select form-select-sm">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Aprobada">Aprobada</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>
                                    <small class="text-muted d-block mt-1">
                                        <i class="ri-information-line me-1"></i>El estado "Vencida" se asigna
                                        automáticamente al pasar la fecha de vencimiento
                                    </small>
                                </div>
                            </div>

                            <!-- Card Notas generales / Condiciones (Collapsible) -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header border-0 bg-soft-primary" style="cursor: pointer;"
                                    data-bs-toggle="collapse" data-bs-target="#notasCollapseBody" aria-expanded="false"
                                    aria-controls="notasCollapseBody">
                                    <h6
                                        class="mb-0 text-atlantico-dark d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="ri-file-text-line me-2"></i>Notas generales / Condiciones
                                        </span>
                                        <i class="ri-arrow-down-s-line notas-chevron"
                                            style="font-size:1.1rem; transition: transform 0.3s ease;"></i>
                                    </h6>
                                </div>
                                <div class="collapse" id="notasCollapseBody">
                                    <div class="card-body pb-2">
                                        <textarea id="notas-field" name="notas" class="form-control form-control-sm"
                                            rows="4" placeholder="Condiciones, forma de pago, tiempo de entrega..."
                                            style="resize: vertical; min-height: 80px;"></textarea>
                                        <small class="text-muted d-block mt-1">
                                            <i class="ri-information-line me-1"></i>Opcional · máx. 2000 caracteres
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen Total -->
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
                                                    Cotización</span>
                                                <small class="text-white-50" style="font-size:0.68rem;">
                                                    <i class="ri-refresh-line me-1"></i>Se actualiza automáticamente
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-white" style="font-size:1.8rem;line-height:1;"
                                                id="total-display-value">$0,00</span>
                                            <input type="hidden" id="total-display-field" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Productos -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm h-100">
                                <div
                                    class="card-header border-0 bg-soft-primary d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 text-atlantico-dark">
                                        <span
                                            style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#132649;color:#fff;font-size:0.65rem;font-weight:700;margin-right:8px;">2</span>
                                        <i class="ri-shopping-bag-3-line me-2"></i>Productos de la Cotización
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-success px-3" id="add-producto-item">
                                        <i class="ri-add-line me-1"></i>Agregar Producto
                                    </button>
                                </div>
                                <div class="card-body p-2"
                                    style="max-height: 490px; overflow-y: auto; overflow-x: hidden;">
                                    <div id="productos-container">
                                        <!-- Productos dinámicos -->
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
                        <button type="submit" class="btn btn-success px-4" id="edit-btn" style="display:none;">
                            <i class="ri-save-line me-1"></i>Actualizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Agregar/Editar Cliente (reutilizado) -->
<div class="modal fade" id="modalAddCliente" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="modalClienteTitle">Agregar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="clienteFormCotizacion">
                <div class="modal-body">
                    <input type="hidden" id="id-field-cliente" />

                    <!-- Fila 1: Documento + Tipo Cliente + Estatus -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="documento-field-cliente" class="form-label required">Documento (Cédula o
                                RIF)</label>
                            <div class="input-group">
                                <select class="form-select" id="documento-prefix-field-cliente"
                                    style="max-width: 70px;">
                                    <option value="V-">V-</option>
                                    <option value="J-">J-</option>
                                    <option value="E-">E-</option>
                                    <option value="G-">G-</option>
                                </select>
                                <input type="text" id="documento-number-field-cliente" class="form-control"
                                    placeholder="Nro. documento" maxlength="10" required />
                            </div>
                            <input type="hidden" id="documento-field-cliente" name="documento" />
                            <small class="text-muted">Máximo 10 dígitos</small>
                            <div id="documento-error-cliente" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo_cliente-field-cliente" class="form-label required">Tipo de Cliente</label>
                            <select id="tipo_cliente-field-cliente" name="tipo_cliente" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="natural">Natural</option>
                                <option value="juridico">Jurídico</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-block">Estatus</label>
                            <div class="form-check form-switch form-switch-success mt-2">
                                <input type="hidden" name="estatus" value="0" />
                                <input class="form-check-input" type="checkbox" role="switch" id="estatus-field-cliente"
                                    name="estatus" value="1" checked />
                                <label class="form-check-label" for="estatus-field-cliente"
                                    id="estatus-label-cliente">Activo</label>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 2: Nombre + Apellido -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre-field-cliente" class="form-label required">Nombre</label>
                            <input type="text" id="nombre-field-cliente" name="nombre" class="form-control"
                                placeholder="Nombre" maxlength="100" required />
                            <div id="nombre-error-cliente" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido-field-cliente" class="form-label required">Apellido</label>
                            <input type="text" id="apellido-field-cliente" name="apellido" class="form-control"
                                placeholder="Apellido" maxlength="100" required />
                            <div id="apellido-error-cliente" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Fila 3: Email + Teléfono -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email-field-cliente" class="form-label">Email</label>
                            <input type="email" id="email-field-cliente" name="email" class="form-control"
                                placeholder="correo@ejemplo.com" />
                            <div id="email-error-cliente" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono-field-cliente" class="form-label required">Teléfono</label>
                            <div class="input-group">
                                <select class="form-select" id="telefono-prefix-field-cliente"
                                    style="max-width: 100px; min-width: 100px;">
                                    <option value="0412">0412</option>
                                    <option value="0422">0422</option>
                                    <option value="0414">0414</option>
                                    <option value="0424" selected>0424</option>
                                    <option value="0416">0416</option>
                                    <option value="0426">0426</option>
                                </select>
                                <input type="text" id="telefono-number-field-cliente" class="form-control"
                                    placeholder="1234567" maxlength="7" required />
                            </div>
                            <input type="hidden" id="telefono-field-cliente" name="telefono" />
                            <div id="telefono-error-cliente" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Fila 4: Dirección -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="direccion-field-cliente" class="form-label required">Dirección</label>
                            <input type="text" id="direccion-field-cliente" name="direccion" class="form-control"
                                placeholder="Dirección completa" maxlength="500" required />
                            <div id="direccion-error-cliente" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Fila 5: Estado (Territorio) + Ciudad -->
                    <div class="row">
                        <div class="col-md-6">
                            <label for="estado_territorial-field-cliente" class="form-label required">Estado</label>
                            <select name="estado_territorial" id="estado_territorial-field-cliente" class="form-select"
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
                            <label for="ciudad-field-cliente" class="form-label required">Municipio</label>
                            <select name="ciudad" id="ciudad-field-cliente" class="form-select" required>
                                <option value="">Primero seleccione un estado</option>
                            </select>
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

<!-- Modal para seleccionar producto (Movido al final para z-index) -->
<div class="modal fade" id="productosModalCotizacion" tabindex="-1" aria-labelledby="productosModalCotizacionLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header — Nivel 2: Modal utilitario de búsqueda -->
            <div class="modal-header p-3" id="productosModalCotizacion-header"
                style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="productosModalCotizacionLabel">
                    <i class="ri-search-line me-2" style="opacity: 0.7;"></i>Buscar y Seleccionar Producto
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Estilos específicos para este modal -->
                <style>
                    #productosModalCotizacionTable thead tr th {
                        background: #1e3c72 !important;
                        /* Fallback */
                        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
                        color: white !important;
                        border: none !important;
                    }

                    #productosModalCotizacionTable tbody tr:hover {
                        background-color: #f1faff !important;
                    }

                    /* Asegurar que texto en tabla sea visible */
                    #productosModalCotizacionTable tbody td {
                        color: #333 !important;
                        vertical-align: middle;
                    }
                </style>

                <!-- Card de Filtros -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header border-0 bg-soft-success">
                        <h6 class="mb-0 text-atlantico-cyan">
                            <i class="ri-filter-3-line me-2"></i>Filtros de búsqueda
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-soft-primary border-primary text-primary">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" id="buscarProductoModalCotizacion"
                                        class="form-control border-primary"
                                        placeholder="Buscar por código, tipo o modelo...">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <select id="filtroTipoProductoCotizacion"
                                    class="form-select border-success text-success">
                                    <option value="">📁 Todos los tipos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Tabla de Productos -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-soft-primary">
                        <h6 class="mb-0 text-atlantico-dark">
                            <i class="ri-store-2-line me-2"></i>Catálogo de Productos
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 350px;">
                            <table class="table table-hover mb-0" id="productosModalCotizacionTable">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width:8%;  border:none;" class="text-center"><i
                                                class="ri-image-line"></i></th>
                                        <th style="width:20%; border:none;"><i class="ri-barcode-line me-1"></i>Código
                                        </th>
                                        <th style="width:20%; border:none;"><i class="ri-folder-line me-1"></i>Tipo</th>
                                        <th style="width:28%; border:none;"><i class="ri-t-shirt-line me-1"></i>Modelo
                                        </th>
                                        <th style="width:16%; border:none;"><i
                                                class="ri-money-dollar-circle-line me-1"></i>Precio</th>
                                        <th style="width:8%;  border:none;" class="text-center"><i
                                                class="ri-check-line"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="productosModalCotizacionBody">
                                    <!-- Se llena con JavaScript -->
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Cargando productos...</td>
                                    </tr>
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

<!-- Modal para seleccionar Logo (Catálogo de Logos) -->
<div class="modal fade" id="logoSearchModal" tabindex="-1" aria-labelledby="logoSearchModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1110;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Header — Nivel 3: Modal utilitario de catálogo de logos -->
            <div class="modal-header p-3" id="logoSearchModal-header" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="logoSearchModalLabel">
                    <i class="ri-image-line me-2" style="opacity: 0.7;"></i>Buscar y Seleccionar Logo
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Estilos específicos para este modal -->
                <style>
                    /*
                     * ROW-LEVEL APPROACH — más robusto que cell-level
                     *
                     * Bootstrap 5 usa la regla:
                     *   .table > :not(caption) > * > *  { background: var(--bs-table-bg); ... }
                     * que se aplica a cada <th> individualmente y puede sobreponerse
                     * a un `background !important` en el <th>, causando el "bloque oscuro".
                     *
                     * Solución: poner el gradiente en <thead> y <tr> (Bootstrap NO aplica
                     * sus variables CSS a <thead> ni a <tr>), y forzar cada <th> a
                     * transparent para que el fondo del row se vea sin interferencia.
                     */

                    /* 1) Gradiente en el contenedor: thead y el tr */
                    #logoSearchModalTable thead,
                    #logoSearchModalTable thead tr {
                        background: #1e3c72;
                        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                    }

                    /* 2) Celdas completamente transparentes — heredan el gradiente del <tr> */
                    #logoSearchModalTable thead tr th {
                        background: transparent !important;
                        background-color: transparent !important;

                        /* Reset de variables CSS de Bootstrap 5 que causan el glitch */
                        --bs-table-bg: transparent;
                        --bs-table-accent-bg: transparent;
                        --bs-table-striped-bg: transparent;
                        --bs-table-hover-bg: transparent;
                        --bs-table-active-bg: transparent;

                        color: white !important;
                        border: none !important;
                        border-top: none !important;
                        border-bottom: none !important;
                        border-left: none !important;
                        border-right: none !important;

                        font-size: 0.78rem;
                        letter-spacing: 0.04em;
                        text-transform: uppercase;
                        font-weight: 600;
                    }

                    #logoSearchModalTable tbody td {
                        color: #333 !important;
                        vertical-align: middle;
                    }

                    /* Columna archivo — quieta, secundaria */
                    #logoSearchModalTable .logo-filename-cell {
                        font-size: 0.74rem;
                        color: #9099a8 !important;
                        font-style: italic;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        max-width: 160px;
                    }
                </style>

                <!-- Card de Filtros -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header border-0 bg-soft-success">
                        <h6 class="mb-0 text-atlantico-cyan">
                            <i class="ri-filter-3-line me-2"></i>Búsqueda de logo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-soft-primary border-primary text-primary">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" id="buscarLogoModal" class="form-control border-primary"
                                placeholder="Buscar por nombre del logo...">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="ri-information-line me-1"></i>Puede hacer doble clic en una fila para seleccionar
                            el logo
                        </small>
                    </div>
                </div>

                <!-- Card de Tabla de Logos -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-soft-primary">
                        <h6 class="mb-0 text-atlantico-dark">
                            <i class="ri-folder-image-line me-2"></i>Catálogo de Logos
                            <span class="badge bg-secondary ms-2" id="logoModalCount">0 logos</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 350px;">
                            <table class="table table-hover mb-0" id="logoSearchModalTable">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width:65%; border:none;"><i class="ri-image-line me-1"></i>Nombre del
                                            Logo</th>
                                        <th style="width:25%; border:none;"><i class="ri-file-line me-1"></i>Archivo
                                            Original</th>
                                        <th style="width:10%; border:none;" class="text-center"><i
                                                class="ri-check-line"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="logoModalBody">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Cargando logos...</td>
                                    </tr>
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

<!-- ═══════════════════════════════════════════════════════════════════════════
     MODAL: Catálogo de Colores
     Nivel 2 — Modal utilitario de selección (sobre #showModal)
     Patrón idéntico a #logoSearchModal y #productosModalCotizacion
═══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="colorCatalogoModal" tabindex="-1" aria-labelledby="colorCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <!-- Header — Atlántico Brand -->
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="colorCatalogoModalLabel">
                    <i class="ri-palette-line me-2" style="opacity:0.7;"></i>Catálogo de Colores
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <!-- Disclaimer -->
                <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-start"
                    style="font-size: 0.78rem; background: rgba(241, 196, 15, 0.12); border-color: rgba(241, 196, 15, 0.3); color: #7d6608;">
                    <i class="ri-information-line me-2 mt-1 flex-shrink-0" style="font-size: 1rem;"></i>
                    <span><strong>Nota:</strong> Los colores mostrados son meramente referenciales.
                        El tono físico del material o tela puede variar.</span>
                </div>

                <!-- Buscador -->
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarColorModal" class="form-control" placeholder="Buscar color..."
                        style="border-color: #1e3c72;">
                </div>

                <!-- Grid de Swatches — renderizado por JS -->
                <div id="coloresSwatchGrid" style="max-height: 360px; overflow-y: auto; overflow-x: hidden;">
                    <!-- Se llena dinámicamente -->
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

<!-- ═══════════════════════════════════════════════════════════════════════════
     MODAL: Catálogo de Tallas
     Nivel 2 — Modal utilitario de selección (sobre #showModal)
═══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="tallaCatalogoModal" tabindex="-1" aria-labelledby="tallaCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="tallaCatalogoModalLabel">
                    <i class="ri-t-shirt-line me-2" style="opacity:0.7;"></i>Catálogo de Tallas
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarTallaModal" class="form-control" placeholder="Buscar talla..."
                        style="border-color: #1e3c72;">
                </div>

                <div id="tallasCatalogoGrid" style="max-height: 360px; overflow-y: auto; overflow-x: hidden;">
                    <!-- Se llena dinámicamente -->
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

<!-- ═══════════════════════════════════════════════════════════════════════════
    MODAL: Configuración de Bordados por Producto
     Nivel 2 — Modal utilitario de selección (sobre #showModal)
═══════════════════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="ubicacionCatalogoModal" tabindex="-1" aria-labelledby="ubicacionCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="ubicacionCatalogoModalLabel">
                    <i class="ri-map-pin-line me-2" style="opacity:0.7;"></i>Configurar Servicio de Bordado
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarUbicacionModal" class="form-control" placeholder="Buscar ubicación..."
                        style="border-color: #1e3c72;">
                </div>

                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.78rem;">
                    Asigna logo por cada ubicación, ajusta precio por ubicación y cantidad de bordados por prenda.
                </div>

                <div id="ubicacionesCatalogoGrid" style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                    <!-- Se llena dinámicamente -->
                </div>

                <hr class="my-3">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0" style="font-size:0.85rem;color:#1e3c72;">
                        <i class="ri-edit-2-line me-1"></i>Ubicaciones personalizadas
                    </h6>
                    <button type="button" class="btn btn-sm btn-atlantico-brand" id="agregarUbicacionPersonalizadaBtn">
                        <i class="ri-add-line"></i> Agregar
                    </button>
                </div>

                <div id="ubicacionesPersonalizadasContainer" class="d-flex flex-column gap-2">
                    <!-- Se llena dinámicamente -->
                </div>

                <div class="rounded p-2 mt-3" style="background: rgba(30,60,114,0.06);">
                    <div class="small text-muted">Recargo unitario del producto</div>
                    <div class="fw-bold" id="resumenRecargoBordadoModal" style="color:#1e3c72;">$0.00</div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-success px-3" id="aplicarUbicacionesBordadoBtn">
                    <i class="ri-check-line me-1"></i>Aplicar configuración
                </button>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>