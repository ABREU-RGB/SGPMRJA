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
                            {{-- Implementar en TASK-011 --}}
                            <div class="col-12 text-center text-muted py-5">
                                <i class="ri-user-3-line fs-1 opacity-25"></i>
                                <p class="mt-2 mb-0 small">Campos del cliente y fechas — se implementan en TASK-011</p>
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
     Modal Seleccionar Cotización
     Abre desde el paso 2 del wizard para importar productos
     ═══════════════════════════════════════════════════════════════════ --}}
@include('admin.pedidos.modals.seleccionar_cotizacion')
