<!-- Modal — Seleccionar pedido / línea para producir -->
<div class="modal fade atlantico-modal atlantico-modal--op" id="seleccionarPedidoModal" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Pedido para Producir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Buscador --}}
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background: rgba(6,78,59,0.08); border-color: #0ab39c;">
                            <i class="ri-search-line" style="color: #0ab39c;"></i>
                        </span>
                        <input type="text" id="buscarPedidoOrden" class="form-control"
                            placeholder="Buscar por cliente, documento o número de pedido..."
                            style="border-color: #0ab39c;">
                    </div>
                </div>

                {{-- Contenedor de pedidos --}}
                <div id="pedidos-orden-container" style="max-height: 460px; overflow-y: auto;"></div>

                {{-- Estado vacío --}}
                <div id="pedidos-orden-empty" class="text-center py-5" style="display: none;">
                    <i class="ri-inbox-line" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">No hay pedidos disponibles para producir</p>
                    <small class="text-muted">Los pedidos cancelados o completados no aparecen aquí</small>
                </div>

                {{-- Loading --}}
                <div id="pedidos-orden-loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Cargando pedidos...</p>
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
