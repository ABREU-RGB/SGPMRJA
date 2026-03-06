<!-- Modal — Detalles de Orden de Producción -->
<style>
    /* ── Underline tabs ──────────────────────────────────────────── */
    #viewModalTabs {
        border-bottom: 2px solid #e9ecef;
        gap: 0 !important;
        flex-wrap: nowrap;
    }
    #viewModalTabs .nav-link {
        border-radius: 0;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 500;
        background-color: transparent !important;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: color .15s, border-color .15s;
    }
    #viewModalTabs .nav-link.active {
        color: #10b981;
        border-bottom-color: #10b981;
        font-weight: 600;
    }
    #viewModalTabs .nav-link:not(.active) {
        color: #6c757d;
    }
    #viewModalTabs .nav-link:not(.active):hover {
        color: #10b981;
        border-bottom-color: rgba(16, 185, 129, 0.35);
    }

    /* ── Dark mode ───────────────────────────────────────────────── */
    [data-bs-theme="dark"] #viewModal .modal-content {
        background-color: var(--vz-body-bg) !important;
    }
    [data-bs-theme="dark"] #viewModal .view-plate {
        background-color: var(--vz-card-bg) !important;
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModal .kpi-sep {
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModal .modal-header {
        background-color: var(--vz-card-bg) !important;
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModal .modal-footer {
        background-color: var(--vz-card-bg) !important;
        border-color: var(--vz-border-color) !important;
    }
    [data-bs-theme="dark"] #viewModalTabs {
        border-bottom-color: var(--vz-border-color);
    }
    [data-bs-theme="dark"] #viewModalTabs .nav-link:not(.active) {
        color: #adb5bd;
    }
    [data-bs-theme="dark"] #viewModal .kpi-number {
        color: var(--vz-body-color) !important;
    }
</style>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:900px;">
        <div class="modal-content" style="background:#f6f7f9;">

            <!-- ══ CAPA 1: Encabezado estático ════════════════════════ -->
            <div class="modal-header view-plate py-2 px-4"
                style="background:#fff; border-bottom:1px solid #e9ecef;">
                <h6 class="modal-title fw-semibold text-muted mb-0" style="font-size:13px; letter-spacing:.2px;">
                    <i class="ri-list-check-2 me-2 opacity-50"></i>Detalles de la Orden de Producción
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- ══ CAPA 1: Zona de KPIs (placa blanca) ════════════════ -->
            <div class="view-plate px-4 pt-3 pb-3"
                style="background:#fff; border-bottom:1px solid #e9ecef;">

                <!-- Identidad: Producto + Estado + Metadata -->
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h5 class="fw-bold mb-0" id="view-producto" style="font-size:16px;"></h5>
                            <div id="view-estado"></div>
                        </div>
                        <p class="text-muted mb-0 fs-12 d-flex align-items-center flex-wrap gap-1">
                            <span id="view-pedido-info"></span>
                            <span class="px-1 opacity-50">·</span>
                            <i class="ri-user-line opacity-50"></i>
                            <span id="view-creado-por"></span>
                            <span class="px-1 opacity-50">·</span>
                            <i class="ri-time-line opacity-50"></i>
                            <span id="view-created"></span>
                        </p>
                    </div>
                </div>

                <!-- KPI Strip — jerarquía tipográfica pura -->
                <div class="row g-0 mb-3">
                    <div class="col-4 kpi-sep pe-4" style="border-right:1px solid #e9ecef;">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.7px;">Solicitada</p>
                        <p class="mb-0 fw-bold kpi-number"
                            style="font-size:26px; line-height:1; color:#212529;"
                            id="view-cantidad-solicitada"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">unidades</p>
                    </div>
                    <div class="col-4 kpi-sep px-4" style="border-right:1px solid #e9ecef;">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.7px;">Producida</p>
                        <p class="mb-0 fw-bold kpi-number"
                            style="font-size:26px; line-height:1; color:#212529;"
                            id="view-cantidad-producida"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">unidades</p>
                    </div>
                    <div class="col-4 kpi-sep ps-4">
                        <p class="text-uppercase text-muted mb-1"
                            style="font-size:10px; font-weight:600; letter-spacing:.7px;">Costo Estimado</p>
                        <p class="mb-0 fw-bold kpi-number"
                            style="font-size:22px; line-height:1; color:#212529;"
                            id="view-costo-estimado"></p>
                        <p class="text-muted mb-0" style="font-size:11px;">estimado</p>
                    </div>
                </div>

                <!-- Barra de progreso — único acento teal -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-uppercase text-muted"
                            style="font-size:10px; font-weight:600; letter-spacing:.5px;">Progreso de producción</span>
                        <span class="fw-semibold" style="font-size:12px; color:#10b981;">
                            <span id="view-progreso-pct">0</span>% completado
                        </span>
                    </div>
                    <div class="progress" style="height:6px; border-radius:4px; background:#eef0f2;">
                        <div id="view-progreso" class="progress-bar" role="progressbar"
                            style="width:0%; background:linear-gradient(90deg,#10b981,#059669); border-radius:4px;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

            </div>

            <!-- ══ CAPA 0: Lienzo gris — Tab Nav + Content ═══════════ -->
            <div class="modal-body p-3" style="background:#f6f7f9;">

                <!-- Tabs nav — pertenece al lienzo, no a la placa -->
                <ul class="nav mb-3" id="viewModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-detalles-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-detalles" type="button" role="tab"
                            aria-selected="true">
                            <i class="ri-calendar-2-line me-1"></i>Cronograma & Detalles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-insumos-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-insumos" type="button" role="tab"
                            aria-selected="false">
                            <i class="ri-box-3-line me-1"></i>Insumos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-avances-btn"
                            data-bs-toggle="pill" data-bs-target="#tab-avances" type="button" role="tab"
                            aria-selected="false">
                            <i class="ri-bar-chart-grouped-line me-1"></i>Avances
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="viewModalTabContent">

                    <!-- ─ TAB 1: Cronograma & Detalles ─────────────── -->
                    <div class="tab-pane fade show active" id="tab-detalles" role="tabpanel"
                        aria-labelledby="tab-detalles-btn">
                        <div class="row g-3">

                            <!-- Timeline -->
                            <div class="col-md-5">
                                <div class="card border-0 shadow-sm h-100 mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-3"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-calendar-2-line me-1" style="color:#10b981;"></i>Cronograma
                                        </p>
                                        <!-- Inicio -->
                                        <div class="d-flex gap-3 mb-0">
                                            <div class="d-flex flex-column align-items-center flex-shrink-0">
                                                <div class="rounded-circle flex-shrink-0"
                                                    style="width:10px;height:10px;background:#10b981;margin-top:4px;"></div>
                                                <div style="width:1px;flex:1;background:#dee2e6;margin:4px 0;min-height:30px;"></div>
                                            </div>
                                            <div class="pb-3">
                                                <p class="text-muted mb-0" style="font-size:10px;">Fecha de inicio</p>
                                                <span id="view-fecha-inicio" class="fw-semibold"
                                                    style="font-size:13px;"></span>
                                            </div>
                                        </div>
                                        <!-- Fin estimado -->
                                        <div class="d-flex gap-3 mb-0">
                                            <div class="d-flex flex-column align-items-center flex-shrink-0">
                                                <div class="rounded-circle flex-shrink-0"
                                                    style="width:10px;height:10px;background:#f7b84b;margin-top:4px;"></div>
                                                <div style="width:1px;flex:1;background:#dee2e6;margin:4px 0;min-height:30px;"></div>
                                            </div>
                                            <div class="pb-3">
                                                <p class="text-muted mb-0" style="font-size:10px;">Fecha fin estimada</p>
                                                <span id="view-fecha-fin-estimada" class="fw-semibold"
                                                    style="font-size:13px;"></span>
                                            </div>
                                        </div>
                                        <!-- Fin real -->
                                        <div class="d-flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle border flex-shrink-0"
                                                    style="width:10px;height:10px;margin-top:4px;border-color:#dee2e6 !important;background:transparent;"></div>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-0 fst-italic" style="font-size:10px;">
                                                    Fin de producción</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Diseño + Notas -->
                            <div class="col-md-7 d-flex flex-column gap-3">
                                <div class="card border-0 shadow-sm mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-2"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-paint-brush-line me-1" style="color:#10b981;"></i>Diseño / Bordado
                                        </p>
                                        <div class="fs-13" id="view-logo" style="min-height:32px;"></div>
                                    </div>
                                </div>
                                <div class="card border-0 shadow-sm mb-0">
                                    <div class="card-body p-3">
                                        <p class="text-uppercase text-muted mb-2"
                                            style="font-size:10px; font-weight:600; letter-spacing:.6px;">
                                            <i class="ri-sticky-note-line me-1" style="color:#10b981;"></i>Notas
                                        </p>
                                        <p class="text-muted mb-0 fs-13" id="view-notas"
                                            style="min-height:32px;"></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ─ TAB 2: Insumos ────────────────────────────── -->
                    <div class="tab-pane fade" id="tab-insumos" role="tabpanel"
                        aria-labelledby="tab-insumos-btn">
                        <div class="card border-0 shadow-sm mb-0">
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

                        <div class="card border-0 shadow-sm mb-0">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-nowrap table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:15%;">Fecha</th>
                                                <th style="width:28%;">Empleado</th>
                                                <th class="text-center" style="width:15%;">Producido</th>
                                                <th class="text-center" style="width:15%;">Defectuoso</th>
                                                <th style="width:27%;">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-avances">
                                            <tr id="avances-empty-row">
                                                <td colspan="5" class="text-center text-muted py-3 fs-12">
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

            <!-- ══ Footer ════════════════════════════════════════════ -->
            <div class="modal-footer view-plate py-2 px-4"
                style="background:#fff; border-top:1px solid #e9ecef;">
                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
