{{-- ═══════════════════════════════════════════════════════════════════
     WIZARD PEDIDO — showModal
     Modos: crear nuevo · completar desde cotización · editar
     Lógica JS en: pedidos/scripts/main.blade.php (TASK-011+)
     ═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade atlantico-modal atlantico-modal--op wiz-modal" id="showModal" tabindex="-1"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nuevo Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            {{-- Stepper visual — 4 pasos --}}
            <div class="wiz-stepper-wrapper">
                <div class="wiz-stepper" role="tablist" aria-label="Pasos del pedido">
                    <button type="button" class="wiz-step-marker is-active" data-step="1"
                        role="tab" aria-selected="true" aria-controls="ped-wiz-step-1">
                        <span class="wiz-step-dot">1</span>
                        <span class="wiz-step-label">Cliente</span>
                    </button>
                    <span class="wiz-step-line"><span class="wiz-step-line-fill" data-line="1"></span></span>
                    <button type="button" class="wiz-step-marker" data-step="2"
                        role="tab" aria-selected="false" aria-controls="ped-wiz-step-2">
                        <span class="wiz-step-dot">2</span>
                        <span class="wiz-step-label">Productos</span>
                    </button>
                    <span class="wiz-step-line"><span class="wiz-step-line-fill" data-line="2"></span></span>
                    <button type="button" class="wiz-step-marker" data-step="3"
                        role="tab" aria-selected="false" aria-controls="ped-wiz-step-3">
                        <span class="wiz-step-dot">3</span>
                        <span class="wiz-step-label">Pago</span>
                    </button>
                    <span class="wiz-step-line"><span class="wiz-step-line-fill" data-line="3"></span></span>
                    <button type="button" class="wiz-step-marker" data-step="4"
                        role="tab" aria-selected="false" aria-controls="ped-wiz-step-4">
                        <span class="wiz-step-dot">4</span>
                        <span class="wiz-step-label">Resumen</span>
                    </button>
                </div>
            </div>

            <form id="pedidoForm" novalidate>
                @csrf
                <input type="hidden" id="ped-wiz-id-field" />
                <input type="hidden" id="ped-wiz-cotizacion-id-field" name="cotizacion_id" />
                <input type="hidden" id="ped-wiz-cliente-id-field" name="cliente_id" />

                <div class="modal-body p-0 wiz-wizard-body">

                    {{-- ════════════════════════ PASO 1 — CLIENTE ════════════════════════ --}}
                    <section class="wiz-step-content is-active" id="ped-wiz-step-1" data-step="1">
                        <div class="wiz-step-header">
                            <span class="wiz-step-tag">Paso 1 de 4</span>
                            <h4 class="wiz-step-title">Cliente y datos del pedido</h4>
                            <p class="wiz-step-desc">Busca el cliente por su documento, define las fechas y la prioridad del pedido.</p>
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
                                        {{-- Buscador de documento --}}
                                        <label for="ped-ci-rif-number-field" class="form-label small fw-semibold mb-1">
                                            Documento de identidad <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative cot-search-doc-wrap">
                                            <div class="input-group cot-search-doc-group">
                                                <span class="input-group-text cot-search-doc-icon">
                                                    <i class="ri-search-2-line"></i>
                                                </span>
                                                <select class="form-select" id="ped-ci-rif-prefix-field"
                                                    name="rif_prefix" style="max-width: 70px;">
                                                    <option value="V-">V-</option>
                                                    <option value="J-">J-</option>
                                                    <option value="E-">E-</option>
                                                    <option value="G-">G-</option>
                                                </select>
                                                <input type="text" id="ped-ci-rif-number-field" name="rif_number"
                                                    class="form-control"
                                                    placeholder="Escribí el documento para buscar..."
                                                    autocomplete="off" />
                                                <input type="hidden" id="ped-ci-rif-full-field" name="ci_rif" />
                                            </div>
                                            <div id="ped-cliente-autocomplete-list"
                                                class="list-group position-absolute w-100"
                                                style="z-index: 1050; top: 100%;"></div>
                                        </div>

                                        {{-- Empty state --}}
                                        <div class="cot-cliente-empty" id="ped-cliente-empty">
                                            <div class="cot-cliente-empty-icon">
                                                <i class="ri-user-search-line"></i>
                                            </div>
                                            <p class="cot-cliente-empty-title">Buscá el cliente o creá uno nuevo</p>
                                            <p class="cot-cliente-empty-desc">
                                                Escribí el documento arriba para buscar entre clientes, empleados y proveedores existentes.
                                            </p>
                                            <button type="button" class="btn btn-outline-success cot-btn-create-cliente"
                                                id="ped-open-add-cliente-modal">
                                                <i class="ri-user-add-line me-1"></i>Crear cliente nuevo
                                            </button>
                                        </div>

                                        {{-- Loading state (skeleton) --}}
                                        <div class="cot-cliente-loading" id="ped-cliente-loading" hidden>
                                            <div class="cot-skeleton cot-skeleton-circle"></div>
                                            <div class="flex-grow-1">
                                                <div class="cot-skeleton cot-skeleton-line cot-skeleton-line-md"></div>
                                                <div class="cot-skeleton cot-skeleton-line cot-skeleton-line-sm"></div>
                                            </div>
                                        </div>

                                        {{-- Cliente card (seleccionado) --}}
                                        <div class="cot-cliente-card" id="ped-cliente-card" hidden>
                                            <div class="cot-cliente-avatar" id="ped-cliente-avatar"
                                                data-color-key="default">—</div>
                                            <div class="cot-cliente-info flex-grow-1">
                                                <div class="cot-cliente-name-row">
                                                    <h5 class="cot-cliente-name" id="ped-cliente-name-display">—</h5>
                                                    <span class="cot-cliente-roles" id="ped-cliente-roles"></span>
                                                </div>
                                                <p class="cot-cliente-doc">
                                                    <i class="ri-bank-card-line"></i>
                                                    <span id="ped-cliente-doc-display">—</span>
                                                </p>
                                                <div class="cot-cliente-contact-row">
                                                    <span class="cot-cliente-contact-item" id="ped-cliente-tel-wrap">
                                                        <i class="ri-phone-line"></i>
                                                        <span id="ped-cliente-tel-display">—</span>
                                                    </span>
                                                    <span class="cot-cliente-contact-item" id="ped-cliente-email-wrap">
                                                        <i class="ri-mail-line"></i>
                                                        <span id="ped-cliente-email-display">—</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <button type="button"
                                                class="btn btn-link btn-sm cot-cliente-change-btn"
                                                id="ped-cliente-change-btn" title="Cambiar cliente">
                                                <i class="ri-refresh-line me-1"></i>Cambiar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Detalles + Estado --}}
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header border-0 bg-soft-primary">
                                        <h6 class="mb-0 text-atlantico-dark">
                                            <i class="ri-calendar-event-line me-2"></i>Detalles del pedido
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label for="ped-fecha-pedido-field"
                                                    class="form-label small fw-semibold mb-1">
                                                    Fecha del pedido <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" id="ped-fecha-pedido-field" name="fecha_pedido"
                                                    class="form-control form-control-sm" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="ped-fecha-entrega-field"
                                                    class="form-label small fw-semibold mb-1">
                                                    Entrega estimada
                                                </label>
                                                <input type="date" id="ped-fecha-entrega-field"
                                                    name="fecha_entrega_estimada"
                                                    class="form-control form-control-sm" />
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label class="form-label small fw-semibold mb-1">Prioridad</label>
                                                <div class="cot-priority-chips" role="radiogroup"
                                                    aria-label="Prioridad">
                                                    <button type="button"
                                                        class="cot-priority-chip ped-priority-chip cot-priority-chip--normal is-active"
                                                        data-value="Normal" role="radio" aria-checked="true">
                                                        <span class="cot-priority-dot"></span>
                                                        <span>Normal</span>
                                                    </button>
                                                    <button type="button"
                                                        class="cot-priority-chip ped-priority-chip cot-priority-chip--alta"
                                                        data-value="Alta" role="radio" aria-checked="false">
                                                        <span class="cot-priority-dot"></span>
                                                        <span>Alta</span>
                                                    </button>
                                                    <button type="button"
                                                        class="cot-priority-chip ped-priority-chip cot-priority-chip--urgente"
                                                        data-value="Urgente" role="radio" aria-checked="false">
                                                        <span class="cot-priority-dot"></span>
                                                        <span>Urgente</span>
                                                    </button>
                                                </div>
                                                <select id="ped-prioridad-field" name="prioridad"
                                                    class="d-none" tabindex="-1">
                                                    <option value="Normal" selected>Normal</option>
                                                    <option value="Alta">Alta</option>
                                                    <option value="Urgente">Urgente</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Estado (solo en modo edición) --}}
                                <div class="card border-0 shadow-sm" id="ped-estado-field-wrapper"
                                    style="display: none;">
                                    <div class="card-header border-0 bg-soft-secondary">
                                        <h6 class="mb-0 text-atlantico-green">
                                            <i class="ri-flag-line me-2"></i>Estado del pedido
                                        </h6>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="cot-estado-chips" role="radiogroup" aria-label="Estado">
                                            <button type="button"
                                                class="cot-estado-chip ped-estado-chip cot-estado-chip--pendiente is-active"
                                                data-value="Pendiente" role="radio" aria-checked="true">Pendiente</button>
                                            <button type="button"
                                                class="cot-estado-chip ped-estado-chip cot-estado-chip--aprobada"
                                                data-value="Aprobado" role="radio" aria-checked="false">Aprobado</button>
                                            <button type="button"
                                                class="cot-estado-chip ped-estado-chip cot-estado-chip--cancelada"
                                                data-value="Cancelado" role="radio" aria-checked="false">Cancelado</button>
                                            <button type="button"
                                                class="cot-estado-chip ped-estado-chip"
                                                data-value="Entregado" role="radio" aria-checked="false">Entregado</button>
                                        </div>
                                        <select id="ped-estado-field" name="estado" class="d-none" tabindex="-1">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Aprobado">Aprobado</option>
                                            <option value="Cancelado">Cancelado</option>
                                            <option value="Entregado">Entregado</option>
                                        </select>
                                        <small class="text-muted d-block mt-2">
                                            <i class="ri-information-line me-1"></i>Cambia el estado actual del pedido.
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    {{-- ════════════════════════ PASO 2 — PRODUCTOS ════════════════════════ --}}
                    <section class="wiz-step-content" id="ped-wiz-step-2" data-step="2" hidden>
                        <div class="wiz-step-header">
                            <span class="wiz-step-tag">Paso 2 de 4</span>
                            <h4 class="wiz-step-title">Productos del pedido</h4>
                            <p class="wiz-step-desc">Agrega los productos manualmente o importa desde una cotización existente.</p>
                        </div>
                        <div class="row g-3">
                            {{-- Implementar en TASK-012 --}}
                            <div class="col-12 text-center text-muted py-5">
                                <i class="ri-shopping-bag-3-line fs-1 opacity-25"></i>
                                <p class="mt-2 mb-0 small">Grilla de productos e importación — se implementan en TASK-012</p>
                            </div>
                        </div>
                    </section>

                    {{-- ════════════════════════ PASO 3 — PAGO ════════════════════════ --}}
                    <section class="wiz-step-content" id="ped-wiz-step-3" data-step="3" hidden>
                        <div class="wiz-step-header">
                            <span class="wiz-step-tag">Paso 3 de 4</span>
                            <h4 class="wiz-step-title">Pago</h4>
                            <p class="wiz-step-desc">Registra el abono, el restante y el método de pago del pedido.</p>
                        </div>
                        <div class="row g-3">
                            {{-- Implementar en TASK-013 --}}
                            <div class="col-12 text-center text-muted py-5">
                                <i class="ri-wallet-3-line fs-1 opacity-25"></i>
                                <p class="mt-2 mb-0 small">Abono, restante y métodos de pago — se implementan en TASK-013</p>
                            </div>
                        </div>
                    </section>

                    {{-- ════════════════════════ PASO 4 — RESUMEN ════════════════════════ --}}
                    <section class="wiz-step-content" id="ped-wiz-step-4" data-step="4" hidden>
                        <div class="wiz-step-header">
                            <span class="wiz-step-tag">Paso 4 de 4</span>
                            <h4 class="wiz-step-title">Resumen del pedido</h4>
                            <p class="wiz-step-desc">Revisa toda la información antes de guardar el pedido.</p>
                        </div>
                        <div class="row g-3">
                            {{-- Implementar en TASK-014 --}}
                            <div class="col-12 text-center text-muted py-5">
                                <i class="ri-file-text-line fs-1 opacity-25"></i>
                                <p class="mt-2 mb-0 small">Resumen consolidado y submit — se implementan en TASK-014</p>
                            </div>
                        </div>
                    </section>

                </div>{{-- /modal-body --}}

                <div class="modal-footer wiz-wizard-footer">
                    <div class="wiz-wizard-footer-info">
                        <span class="wiz-wizard-step-info">
                            Paso <span id="ped-step-current">1</span> de 4
                        </span>
                    </div>
                    <div class="wiz-wizard-footer-actions">
                        <button type="button" class="btn btn-light wiz-wizard-btn-prev" id="btn-ped-prev"
                            style="display:none;">
                            <i class="ri-arrow-left-line me-1"></i>Anterior
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="button" class="btn btn-atlantico-brand wiz-wizard-btn-next" id="btn-ped-next">
                            Continuar<i class="ri-arrow-right-line ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-success wiz-wizard-btn-submit" id="ped-wiz-add-btn"
                            style="display:none;">
                            <i class="ri-check-line me-1"></i>Guardar Pedido
                        </button>
                        <button type="submit" class="btn btn-success wiz-wizard-btn-submit" id="ped-wiz-edit-btn"
                            style="display:none;">
                            <i class="ri-save-line me-1"></i>Actualizar Pedido
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     VIEW MODAL — Detalles del Pedido (read-only)
     Copiado de pedidos/index.blade.php — se elimina de allí en TASK-017
     ═══════════════════════════════════════════════════════════════════ --}}
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
                                                <i class="ri-bank-card-line me-1" style="font-size:0.9rem;"></i>Transferencia
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
                                                <i class="ri-smartphone-line me-1" style="font-size:0.9rem;"></i>Pago Móvil
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

{{-- ═══════════════════════════════════════════════════════════════════
     Modal Agregar Cliente — inline desde paso 1 del wizard
     IDs idénticos al modal de cotizaciones; callbacks distintos en scripts/main.blade.php
     ═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade atlantico-modal" id="modalAddCliente" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="clienteFormCotizacion" class="modal-content" novalidate>
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="modalClienteTitle">Agregar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id-field-cliente" />

                <div class="modal-form-section">
                    <div class="section-header-compact">
                        <div class="modal-form-section-title">
                            <i class="ri-fingerprint-line"></i>Identificación
                        </div>
                    </div>
                    <div class="row g-2 mb-0">
                        <div class="col-md-6">
                            <label for="documento-number-field-cliente" class="form-label required">
                                Documento (Cédula o RIF)
                            </label>
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
                            <small class="text-muted d-block mt-1 mb-1">Máximo 10 dígitos</small>
                            <div id="documento-error-cliente" class="invalid-feedback" style="display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_cliente-field-cliente" class="form-label required">Tipo de Cliente</label>
                            <select id="tipo_cliente-field-cliente" name="tipo_cliente" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="natural">Natural</option>
                                <option value="juridico">Jurídico</option>
                                <option value="gubernamental">Gubernamental</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-form-section">
                    <div class="modal-form-section-title">
                        <i class="ri-user-3-line"></i>Datos del Cliente
                    </div>
                    <div id="campos-persona-natural-cliente" class="row g-2 mb-0">
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
                    <div id="campos-razon-social-cliente" class="row g-2 mb-0 d-none">
                        <div class="col-12">
                            <label for="razon-social-field-cliente" class="form-label">Razón Social</label>
                            <input type="text" id="razon-social-field-cliente" name="nombre" class="form-control"
                                placeholder="Razón Social de la empresa" maxlength="200" />
                        </div>
                    </div>
                </div>

                <div class="modal-form-section">
                    <div class="modal-form-section-title">
                        <i class="ri-contacts-book-2-line"></i>Contacto
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label for="email-field-cliente" class="form-label">Email</label>
                            <input type="email" id="email-field-cliente" name="email" class="form-control"
                                placeholder="correo@ejemplo.com" />
                            <div id="email-error-cliente" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono-number-field-cliente" class="form-label required">Teléfono</label>
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
                            <div id="telefono-error-cliente" class="invalid-feedback" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="row g-2 mb-0">
                        <div class="col-12">
                            <label for="direccion-field-cliente" class="form-label required">Dirección</label>
                            <textarea id="direccion-field-cliente" name="direccion" class="form-control"
                                placeholder="Dirección completa" maxlength="500" rows="2" required></textarea>
                            <div id="direccion-error-cliente" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-form-section">
                    <div class="modal-form-section-title">
                        <i class="ri-map-pin-2-line"></i>Ubicación
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="estado_territorial-field-cliente" class="form-label required">Estado</label>
                            <select name="estado_territorial" id="estado_territorial-field-cliente"
                                class="form-select" required>
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

                <div class="modal-form-section mb-0">
                    <div class="modal-form-section-title">
                        <i class="ri-shield-check-line"></i>Estatus
                    </div>
                    <div class="form-check form-switch form-switch-success">
                        <input type="hidden" name="estatus" value="0" />
                        <input class="form-check-input" type="checkbox" role="switch" id="estatus-field-cliente"
                            name="estatus" value="1" checked />
                        <label class="form-check-label" for="estatus-field-cliente"
                            id="estatus-label-cliente">Activo</label>
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
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     Modal Seleccionar Cotización
     Abre desde el paso 2 del wizard para importar productos
     ═══════════════════════════════════════════════════════════════════ --}}
@include('admin.pedidos.modals.seleccionar_cotizacion')
