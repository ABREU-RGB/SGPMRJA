<div class="modal fade" id="seleccionarCotizacionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Seleccionar Cotización Aprobada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Buscador -->
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background: rgba(30, 60, 114, 0.1); border-color: #1e3c72;">
                            <i class="ri-search-line" style="color: #1e3c72;"></i>
                        </span>
                        <input type="text" id="buscarCotizacion" class="form-control"
                            placeholder="Buscar por cliente, documento o número de cotización..."
                            style="border-color: #1e3c72;">
                    </div>
                </div>

                <!-- Contenedor de cotizaciones -->
                <div id="cotizaciones-container" style="max-height: 450px; overflow-y: auto;">
                    <!-- Las cotizaciones se cargarán aquí dinámicamente como cards -->
                </div>

                <!-- Estado vacío -->
                <div id="empty-state" class="text-center py-5" style="display: none;">
                    <i class="ri-file-forbid-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">No hay cotizaciones aprobadas disponibles</p>
                    <small class="text-muted">Las cotizaciones ya convertidas a pedidos no aparecen aquí</small>
                </div>

                <!-- Loading state -->
                <div id="loading-state" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Cargando cotizaciones...</p>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Estilos en public/assets/css/custom.css — sección "MODAL SELECCIONAR COTIZACIÓN" --}}
