<!-- Modal para ver detalles -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detalles de la Orden de Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Encabezado del Producto y Estado -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="min-w-fit mb-1" id="view-producto"></h5>
                        <p class="text-muted mb-0">Creado por: <span id="view-creado-por" class="fw-medium"></span></p>
                    </div>
                    <div id="view-estado"></div>
                </div>

                <!-- Tarjetas de Métricas -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-0">
                            <div class="card-body py-3">
                                <h6 class="text-muted text-uppercase fs-11 mb-2">Cantidad Solicitada</h6>
                                <h4 class="mb-0" id="view-cantidad-solicitada"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-0">
                            <div class="card-body py-3">
                                <h6 class="text-muted text-uppercase fs-11 mb-2">Cantidad Producida</h6>
                                <h4 class="mb-0" id="view-cantidad-producida"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-none mb-0">
                            <div class="card-body py-3">
                                <h6 class="text-muted text-uppercase fs-11 mb-2">Costo Estimado</h6>
                                <h4 class="mb-0" id="view-costo-estimado"></h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Columna Izquierda: Detalles de Fechas y Progreso -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Progreso de Producción</h6>
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="progress animated-progress custom-progress progress-label">
                                        <div id="view-progreso" class="progress-bar bg-success" role="progressbar"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                            <div class="label"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Cronograma</h6>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-muted">Fecha de Inicio:</span>
                                <span id="view-fecha-inicio" class="fw-medium"></span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-muted">Fecha Fin Estimada:</span>
                                <span id="view-fecha-fin-estimada" class="fw-medium"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Fecha de Creación:</span>
                                <span id="view-created" class="fw-medium"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Detalles Adicionales -->
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Detalles de Diseño</h6>
                            <div class="border rounded p-3">
                                <p class="text-muted mb-1">Logo / Bordado:</p>
                                <div id="view-logo"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Notas Adicionales</h6>
                            <div class="alert alert-light border shadow-sm mb-0" role="alert">
                                <p id="view-notas" class="mb-0 text-muted"></p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sección de Insumos -->
                <div class="mt-4">
                    <h6 class="text-muted text-uppercase fs-12 mb-3">Insumos Requeridos</h6>
                    <div class="border rounded">
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 35%;">Insumo</th>
                                        <th scope="col" class="text-center" style="width: 20%;">Cant. Estimada</th>
                                        <th scope="col" class="text-center" style="width: 20%;">Cant. Utilizada</th>
                                        <th scope="col" style="width: 25%;">Progreso</th>
                                    </tr>
                                </thead>
                                <tbody id="view-insumos">
                                </tbody>
                            </table>
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