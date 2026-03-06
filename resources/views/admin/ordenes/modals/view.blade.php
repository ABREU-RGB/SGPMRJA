<!-- Modal — Detalles de Orden de Producción -->
<style>
    #viewModalTabs .nav-link.active {
        background-color: #10b981;
        color: #fff;
    }
    #viewModalTabs .nav-link:not(.active) {
        color: #6c757d;
    }
    #viewModalTabs .nav-link:not(.active):hover {
        background-color: rgba(16, 185, 129, 0.08);
        color: #10b981;
    }
    [data-bs-theme="dark"] #viewModalTabs .nav-link:not(.active) {
        color: #adb5bd;
    }
    [data-bs-theme="dark"] #viewModal .view-header-block {
        background-color: var(--vz-card-bg) !important;
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModal .kpi-divider {
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModal .tab-nav-bar {
        border-color: var(--vz-border-color) !important;
        background-color: var(--vz-card-bg) !important;
    }
</style>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:900px;">
        <div class="modal-content">

            <!-- ══ HEADER LIMPIO ══════════════════════════════════════ -->
            <div class="view-header-block px-4 pt-4 pb-0"
                style="background:#fff; border-bottom: 1px solid #e9ecef;">

                <!-- Fila 1: Identidad + botón cerrar -->
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h5 class="fw-bold mb-0" id="view-producto" style="font-size:16px;"></h5>
                            <div id="view-estado"></div>
                        </div>
                        <p class="text-muted mb-0 fs-12 d-flex align-items-center flex-wrap gap-1">
                            <span id="view-pedido-info" class="text-muted"></span>
                            <span class="px-1">·</span>
                            <i class="ri-user-line opacity-50"></i>
                            <span id="view-creado-por"></span>
                            <span class="px-1">·</span>
                            <i class="ri-time-line opacity-50"></i>
                            <span id="view-created"></span>
                        </p>
                    </div>
                    <button type="button" class="btn-close flex-shrink-0 ms-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Fila 2: KPI Strip — jerarquía tipográfica pura -->
                <div class="row g-0 mb-3">
                    <div class="col-4 kpi-divider pe-3"
                        style="border-right: 1px solid #e9ecef;">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">Solicitada</p>
                        <p class="mb-0 fw-bold" style="font-size:22px; line-height:1; color:#212529;"
                            id="view-cantidad-solicitada"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">unidades</p>
                    </div>
                    <div class="col-4 kpi-divider px-3"
                        style="border-right: 1px solid #e9ecef;">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">Producida</p>
                        <p class="mb-0 fw-bold" style="font-size:22px; line-height:1; color:#212529;"
                            id="view-cantidad-producida"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">unidades</p>
                    </div>
                    <div class="col-4 kpi-divider ps-3">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">Costo Est.</p>
                        <p class="mb-0 fw-bold" style="font-size:20px; line-height:1; color:#212529;"
                            id="view-costo-estimado"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">estimado</p>
                    </div>
                </div>

                <!-- Fila 3: Barra de progreso — único acento teal -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-uppercase text-muted"
                            style="font-size:10px; font-weight:600; letter-spacing:.5px;">Progreso de producción</span>
                        <span class="fw-semibold" style="font-size:12px; color:#10b981;">
                            <span id="view-progreso-pct">0</span>% completado
                        </span>
                    </div>
                    <div class="progress" style="height:6px; border-radius:4px; background:#f0f0f0;">
                        <div id="view-progreso" class="progress-bar" role="progressbar"
                            style="width:0%; background:linear-gradient(90deg,#10b981,#059669); border-radius:4px;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Tabs nav pegados al borde del header -->
                <ul class="nav nav-pills gap-1 tab-nav-bar" id="viewModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1 fs-12 fw-medium" id="tab-detalles-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-detalles" type="button" role="tab"
                            aria-selected="true">
                            <i class="ri-calendar-2-line me-1"></i>Cronograma & Detalles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1 fs-12 fw-medium" id="tab-insumos-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-insumos" type="button" role="tab"
                            aria-selected="false">
                            <i class="ri-box-3-line me-1"></i>Insumos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1 fs-12 fw-medium" id="tab-avances-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-avances" type="button" role="tab"
                            aria-selected="false">
                            <i class="ri-bar-chart-grouped-line me-1"></i>Avances
                        </button>
                    </li>
                </ul>
            </div>

            <!-- ══ TAB CONTENT ════════════════════════════════════════ -->
            <div class="modal-body p-3">
                <div class="tab-content" id="viewModalTabContent">

                    <!-- ─ TAB 1: Cronograma & Detalles ─────────────── -->
                    <div class="tab-pane fade show active" id="tab-detalles" role="tabpanel"
                        aria-labelledby="tab-detalles-btn">
                        <div class="row g-3">

                            <!-- Cronograma: timeline -->
                            <div class="col-md-5">
                                <div class="card border shadow-sm h-100 mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-3"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-calendar-2-line me-1" style="color:#10b981;"></i>Cronograma
                                        </p>
                                        <!-- Inicio -->
                                        <div class="d-flex gap-3 mb-0">
                                            <div class="d-flex flex-column align-items-center flex-shrink-0">
                                                <div class="rounded-circle"
                                                    style="width:10px;height:10px;background:#10b981;margin-top:4px;flex-shrink:0;"></div>
                                                <div style="width:1px;flex:1;background:#dee2e6;margin:4px 0;min-height:28px;"></div>
                                            </div>
                                            <div class="pb-3">
                                                <p class="text-muted mb-0" style="font-size:10px;">Fecha de inicio</p>
                                                <span id="view-fecha-inicio" class="fw-semibold" style="font-size:13px;"></span>
                                            </div>
                                        </div>
                                        <!-- Fin estimado -->
                                        <div class="d-flex gap-3 mb-0">
                                            <div class="d-flex flex-column align-items-center flex-shrink-0">
                                                <div class="rounded-circle"
                                                    style="width:10px;height:10px;background:#f7b84b;margin-top:4px;flex-shrink:0;"></div>
                                                <div style="width:1px;flex:1;background:#dee2e6;margin:4px 0;min-height:28px;"></div>
                                            </div>
                                            <div class="pb-3">
                                                <p class="text-muted mb-0" style="font-size:10px;">Fecha fin estimada</p>
                                                <span id="view-fecha-fin-estimada" class="fw-semibold" style="font-size:13px;"></span>
                                            </div>
                                        </div>
                                        <!-- Fin real (placeholder) -->
                                        <div class="d-flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle border"
                                                    style="width:10px;height:10px;margin-top:4px;border-color:#dee2e6 !important;background:transparent;"></div>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-0 fst-italic" style="font-size:10px;">
                                                    Fin de producción
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Diseño + Notas -->
                            <div class="col-md-7 d-flex flex-column gap-3">
                                <div class="card border shadow-sm mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-2"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-paint-brush-line me-1" style="color:#10b981;"></i>Diseño / Bordado
                                        </p>
                                        <div class="fs-13" id="view-logo" style="min-height:32px;"></div>
                                    </div>
                                </div>
                                <div class="card border shadow-sm mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-2"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-sticky-note-line me-1" style="color:#10b981;"></i>Notas
                                        </p>
                                        <p class="text-muted mb-0 fs-13" id="view-notas" style="min-height:32px;"></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ─ TAB 2: Insumos ────────────────────────────── -->
                    <div class="tab-pane fade" id="tab-insumos" role="tabpanel"
                        aria-labelledby="tab-insumos-btn">
                        <div class="card border shadow-sm mb-0">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-nowrap table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:38%;">Insumo</th>
                                                <th class="text-center" style="width:20%;">Est.</th>
                                                <th class="text-center" style="width:20%;">Utilizado</th>
                                                <th style="width:22%;">Progreso</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-insumos"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ─ TAB 3: Avances ────────────────────────────── -->
                    <div class="tab-pane fade" id="tab-avances" role="tabpanel"
                        aria-labelledby="tab-avances-btn">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="text-muted fs-12 mb-0">
                                Historial de avances diarios registrados para esta orden.
                            </p>
                            <button type="button" class="btn btn-success btn-sm" id="btn-toggle-avance-form"
                                style="display:none;">
                                <i class="ri-add-line me-1"></i>Registrar Avance
                            </button>
                        </div>

                        <!-- Formulario inline -->
                        <div id="avance-form-container" class="card border shadow-sm mb-3"
                            style="display:none;">
                            <div class="card-body p-3">
                                <input type="hidden" id="avance-orden-id">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label form-label-sm fw-medium mb-1">Operario</label>
                                        <select id="avance-operario-id" class="form-select form-select-sm">
                                            <option value="">Seleccione operario...</option>
                                            @foreach($operarios as $op)
                                                <option value="{{ $op->id }}">{{ $op->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label form-label-sm fw-medium mb-1">Producida</label>
                                        <input type="number" id="avance-cantidad-producida"
                                            class="form-control form-control-sm" min="1" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label form-label-sm fw-medium mb-1">Defectuosa</label>
                                        <input type="number" id="avance-cantidad-defectuosa"
                                            class="form-control form-control-sm" min="0" value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label form-label-sm fw-medium mb-1">Observaciones</label>
                                        <input type="text" id="avance-observaciones"
                                            class="form-control form-control-sm" placeholder="Opcional...">
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2 justify-content-end">
                                    <button type="button" class="btn btn-sm btn-light border" id="btn-cancel-avance">
                                        <i class="ri-close-line me-1"></i>Cancelar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success" id="btn-save-avance">
                                        <i class="ri-save-line me-1"></i>Guardar Avance
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla historial -->
                        <div class="card border shadow-sm mb-0">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-nowrap table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:14%;">Fecha</th>
                                                <th style="width:23%;">Operario</th>
                                                <th class="text-center" style="width:13%;">Producido</th>
                                                <th class="text-center" style="width:13%;">Defectuoso</th>
                                                <th style="width:27%;">Observaciones</th>
                                                <th class="text-center" style="width:10%;">Acc.</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-avances">
                                            <tr id="avances-empty-row">
                                                <td colspan="6" class="text-center text-muted py-3 fs-12">
                                                    <i class="ri-inbox-line me-1"></i>Sin registros de avance
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ══ FOOTER ═════════════════════════════════════════════ -->
            <div class="modal-footer py-2" style="border-top:1px solid #e9ecef; background:#fff;">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
