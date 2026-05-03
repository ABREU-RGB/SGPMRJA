<!-- Modal para ver detalles de Cotización -->
<div class="modal fade atlantico-modal atlantico-modal--op" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Columna Izquierda -->
                    <div class="col-lg-6">
                        <!-- Card Datos del Cliente -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                <h6 class="mb-0" style="color: #1e3c72;">
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
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-user-follow-line" style="color: #1e3c72;"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Apellido</small>
                                                <span class="fw-semibold" id="view-cliente-apellido">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-bank-card-line" style="color: #1e3c72;"></i>
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
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-phone-line" style="color: #1e3c72;"></i>
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
                            <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                                <h6 class="mb-0" style="color: #1e3c72;">
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
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-calendar-check-line" style="color: #1e3c72;"></i>
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
                                                style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                                <i class="ri-flag-line" style="color: #1e3c72;"></i>
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
                                            id="view-total">$0.00</span>
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
<!-- ════════════════════════════════════════════════════════════════════
     Modal Cotización — Wizard 3 pasos (Cliente · Productos · Resumen)
     IDs de campos preservados para compatibilidad con scripts/main.blade.php
     ════════════════════════════════════════════════════════════════════ -->
<div class="modal fade atlantico-modal atlantico-modal--op cot-wizard-modal" id="showModal" tabindex="-1"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Stepper visual --}}
            <div class="cot-stepper-wrapper">
                <div class="cot-stepper" role="tablist" aria-label="Pasos de la cotización">
                    <button type="button" class="cot-step-marker is-active" data-step="1" role="tab" aria-selected="true">
                        <span class="cot-step-dot">1</span>
                        <span class="cot-step-label">Cliente</span>
                    </button>
                    <span class="cot-step-line"><span class="cot-step-line-fill" data-line="1"></span></span>
                    <button type="button" class="cot-step-marker" data-step="2" role="tab" aria-selected="false">
                        <span class="cot-step-dot">2</span>
                        <span class="cot-step-label">Productos</span>
                    </button>
                    <span class="cot-step-line"><span class="cot-step-line-fill" data-line="2"></span></span>
                    <button type="button" class="cot-step-marker" data-step="3" role="tab" aria-selected="false">
                        <span class="cot-step-dot">3</span>
                        <span class="cot-step-label">Resumen</span>
                    </button>
                </div>
            </div>

            <form id="cotizacionForm" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" id="id-field" />
                <input type="hidden" id="cliente-id-field" name="cliente_id" />

                <div class="modal-body p-0 cot-wizard-body">

                    {{-- ═════════════════════════ PASO 1 — CLIENTE ═════════════════════════ --}}
                    <section class="cot-step-content is-active" id="cot-step-1" data-step="1">
                        <div class="cot-step-header">
                            <span class="cot-step-tag">Paso 1 de 3</span>
                            <h4 class="cot-step-title">Cliente y datos generales</h4>
                            <p class="cot-step-desc">Busca el cliente por su documento o créalo en línea, luego define las fechas y la prioridad.</p>
                        </div>

                        <div class="row g-3">
                            {{-- Card Cliente --}}
                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-user-3-line me-2"></i>Datos del Cliente
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            {{-- Documento --}}
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
                                                            placeholder="Buscar persona por documento..."
                                                            autocomplete="off" required />
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
                                            {{-- Natural: Nombre + Apellido --}}
                                            <div class="col-md-6" id="block-cot-nombre">
                                                <label for="cliente-nombre-field"
                                                    class="form-label small fw-semibold mb-1">Nombre</label>
                                                <input type="text" id="cliente-nombre-field" name="cliente_nombre"
                                                    class="form-control form-control-sm bg-light" readonly
                                                    style="cursor: not-allowed;" />
                                            </div>
                                            <div class="col-md-6" id="block-cot-apellido">
                                                <label for="cliente-apellido-field"
                                                    class="form-label small fw-semibold mb-1">Apellido</label>
                                                <input type="text" id="cliente-apellido-field" name="cliente_apellido"
                                                    class="form-control form-control-sm bg-light" readonly
                                                    style="cursor: not-allowed;" />
                                            </div>
                                            {{-- Jurídico/Gubernamental: Razón Social --}}
                                            <div class="col-12 d-none" id="block-cot-razon-social">
                                                <label for="cliente-razon-social-display"
                                                    class="form-label small fw-semibold mb-1">Razón Social</label>
                                                <input type="text" id="cliente-razon-social-display"
                                                    class="form-control form-control-sm bg-light" readonly
                                                    style="cursor: not-allowed;" />
                                            </div>
                                            {{-- Teléfono + Email --}}
                                            <div class="col-md-6">
                                                <label for="cliente-telefono-field"
                                                    class="form-label small fw-semibold mb-1">Teléfono</label>
                                                <input type="text" id="cliente-telefono-field" name="cliente_telefono"
                                                    class="form-control form-control-sm bg-light" readonly
                                                    style="cursor: not-allowed;" />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="cliente-email-field"
                                                    class="form-label small fw-semibold mb-1">Email</label>
                                                <input type="email" id="cliente-email-field" name="cliente_email"
                                                    class="form-control form-control-sm bg-light" readonly
                                                    style="cursor: not-allowed;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Detalles + Estado --}}
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-calendar-event-line me-2"></i>Detalles de la cotización
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label for="fecha-cotizacion-field"
                                                    class="form-label small fw-semibold mb-1">
                                                    Fecha emisión <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" id="fecha-cotizacion-field" name="fecha_cotizacion"
                                                    class="form-control form-control-sm" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fecha-validez-field"
                                                    class="form-label small fw-semibold mb-1">
                                                    Fecha validez
                                                </label>
                                                <input type="date" id="fecha-validez-field" name="fecha_validez"
                                                    class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-12">
                                                <label for="prioridad-field"
                                                    class="form-label small fw-semibold mb-1">
                                                    Prioridad
                                                </label>
                                                <select id="prioridad-field" name="prioridad"
                                                    class="form-select form-select-sm">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Alta">Alta</option>
                                                    <option value="Urgente">Urgente</option>
                                                </select>
                                                <small class="text-muted d-block mt-1" style="font-size:0.7rem;">
                                                    <i class="ri-information-line me-1"></i>Se hereda al pedido al convertir.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Estado (solo edición) --}}
                                <div class="card border-0 shadow-sm" id="estado-field-wrapper" style="display: none;">
                                    <div class="card-header border-0 bg-soft-secondary">
                                        <h6 class="mb-0 text-atlantico-green">
                                            <i class="ri-flag-line me-2"></i>Estado de la cotización
                                        </h6>
                                    </div>
                                    <div class="card-body py-2">
                                        <select id="estado-field" name="estado" class="form-select form-select-sm">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Aprobada">Aprobada</option>
                                            <option value="Cancelada">Cancelada</option>
                                        </select>
                                        <small class="text-muted d-block mt-1">
                                            <i class="ri-information-line me-1"></i>"Vencida" se asigna automáticamente al pasar la fecha.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ═════════════════════════ PASO 2 — PRODUCTOS ═════════════════════════ --}}
                    <section class="cot-step-content" id="cot-step-2" data-step="2" hidden>
                        <div class="cot-step-header">
                            <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                                <div>
                                    <span class="cot-step-tag">Paso 2 de 3</span>
                                    <h4 class="cot-step-title">Productos de la cotización</h4>
                                    <p class="cot-step-desc">Explora el catálogo y configura cada prenda con sus colores, tallas y bordados.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-atlantico-brand" id="btn-explorar-catalogo" disabled
                                        title="Disponible en la siguiente fase">
                                        <i class="ri-layout-grid-line me-1"></i>Explorar catálogo
                                    </button>
                                    <button type="button" class="btn btn-soft-success" id="add-producto-item">
                                        <i class="ri-add-line me-1"></i>Agregar producto
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- KPIs --}}
                        <div class="cot-kpi-grid mb-3">
                            <div class="cot-kpi">
                                <span class="cot-kpi-label">Líneas</span>
                                <span class="cot-kpi-value" id="cot-kpi-items">0</span>
                            </div>
                            <div class="cot-kpi">
                                <span class="cot-kpi-label">Subtotal</span>
                                <span class="cot-kpi-value" id="cot-kpi-subtotal">$0,00</span>
                            </div>
                            <div class="cot-kpi cot-kpi--total">
                                <span class="cot-kpi-label">Total</span>
                                <span class="cot-kpi-value" id="cot-kpi-total">$0,00</span>
                                <input type="hidden" id="total-display-field" />
                                <span id="total-display-value" hidden>$0,00</span>
                            </div>
                        </div>

                        {{-- Empty state --}}
                        <div class="cot-empty-state" id="cot-empty-state">
                            <div class="cot-empty-icon">
                                <i class="ri-shopping-bag-3-line"></i>
                            </div>
                            <h5 class="cot-empty-title">Aún no hay productos</h5>
                            <p class="cot-empty-desc">Comienza agregando una prenda. Pronto podrás explorar el catálogo completo.</p>
                            <button type="button" class="btn btn-atlantico-brand" onclick="document.getElementById('add-producto-item').click()">
                                <i class="ri-add-line me-1"></i>Agregar primer producto
                            </button>
                        </div>

                        {{-- Container de productos (gestionado por scripts/main.blade.php) --}}
                        <div id="productos-container"></div>
                    </section>

                    {{-- ═════════════════════════ PASO 3 — RESUMEN ═════════════════════════ --}}
                    <section class="cot-step-content" id="cot-step-3" data-step="3" hidden>
                        <div class="cot-step-header">
                            <span class="cot-step-tag">Paso 3 de 3</span>
                            <h4 class="cot-step-title">Resumen y notas</h4>
                            <p class="cot-step-desc">Revisa el desglose, agrega condiciones y finaliza.</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-7">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-file-text-line me-2"></i>Notas y condiciones
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <textarea id="notas-field" name="notas" class="form-control"
                                            rows="5"
                                            placeholder="Condiciones generales, observaciones, términos especiales..."
                                            style="resize: vertical; min-height: 110px;"></textarea>
                                        <small class="text-muted d-block mt-1">
                                            <i class="ri-information-line me-1"></i>Opcional · máx. 2000 caracteres
                                        </small>
                                        {{-- Wrapper legacy preservado para compatibilidad de IDs --}}
                                        <div id="notasCollapseBody" class="d-none"></div>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm mt-3">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-list-check-2 me-2"></i>Líneas incluidas
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive cot-resumen-tabla-wrap">
                                            <table class="table table-sm mb-0 cot-resumen-tabla">
                                                <thead>
                                                    <tr>
                                                        <th style="width:46%">Producto</th>
                                                        <th class="text-center" style="width:14%">Cant.</th>
                                                        <th class="text-end" style="width:18%">Unit.</th>
                                                        <th class="text-end" style="width:22%">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cot-resumen-lineas">
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3 small">
                                                            Sin productos agregados
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="cot-resumen-card">
                                    <div class="cot-resumen-card-header">
                                        <i class="ri-money-dollar-circle-line"></i>
                                        <span>Resumen final</span>
                                    </div>
                                    <div class="cot-resumen-card-body">
                                        <div class="cot-resumen-row">
                                            <span class="cot-resumen-row-label">Subtotal</span>
                                            <span class="cot-resumen-row-value" id="cot-resumen-subtotal">$0,00</span>
                                        </div>
                                        <div class="cot-resumen-row">
                                            <span class="cot-resumen-row-label">IVA (16%)</span>
                                            <span class="cot-resumen-row-value" id="cot-resumen-iva">$0,00</span>
                                        </div>
                                        <div class="cot-resumen-divider"></div>
                                        <div class="cot-resumen-row cot-resumen-row--total">
                                            <span class="cot-resumen-row-label">Total</span>
                                            <span class="cot-resumen-row-value" id="cot-resumen-total">$0,00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>{{-- /modal-body --}}

                <div class="modal-footer cot-wizard-footer">
                    <div class="cot-wizard-footer-info">
                        <span class="cot-wizard-step-info">
                            <span id="cot-step-current">1</span> de 3
                        </span>
                    </div>
                    <div class="cot-wizard-footer-actions">
                        <button type="button" class="btn btn-light cot-wizard-btn-prev" id="btn-cot-prev"
                            style="display:none;">
                            <i class="ri-arrow-left-line me-1"></i>Anterior
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="button" class="btn btn-atlantico-brand cot-wizard-btn-next" id="btn-cot-next">
                            Continuar<i class="ri-arrow-right-line ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-success cot-wizard-btn-submit" id="add-btn"
                            style="display:none;">
                            <i class="ri-check-line me-1"></i>Crear cotización
                        </button>
                        <button type="submit" class="btn btn-success cot-wizard-btn-submit" id="edit-btn"
                            style="display:none;">
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
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
            <div class="modal-header utility-modal-header p-3" id="productosModalCotizacion-header">
                <h5 class="modal-title" id="productosModalCotizacionLabel">
                    <i class="ri-search-line me-2" style="opacity: 0.7;"></i>Buscar y Seleccionar Producto
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Estilos en public/assets/css/custom.css — sección "MÓDULO COTIZACIONES" --}}

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
                            <table class="table table-hover mb-0 table-seleccionable" id="productosModalCotizacionTable">
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

@include('admin.partials.catalog_modals')