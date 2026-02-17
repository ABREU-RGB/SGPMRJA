<!-- Modal para registrar producción -->
<div class="modal fade" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="modalTitle">Registrar Producción Diaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="produccionForm" method="POST">
                @csrf
                <div class="modal-body">
                    @php
                        $ordenOptions = [];
                        foreach($ordenes as $orden) {
                            $ordenOptions[$orden->id] = '#' . $orden->id . ' - ' . ($orden->producto->nombre ?? 'N/A') . ' (' . $orden->cantidad_producida . '/' . $orden->cantidad_solicitada . ')';
                        }
                        $operarioOptions = $operarios->pluck('name', 'id')->toArray();
                    @endphp
                    <x-forms.select name="orden_id" label="Orden de Producción" required
                        :options="$ordenOptions" placeholder="Seleccione una orden..." />

                    <x-forms.select name="operario_id" label="Empleado" required
                        :options="$operarioOptions" placeholder="Seleccione un operario..." />

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="cantidad_producida" label="Cantidad Producida" type="number" min="1" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="cantidad_defectuosa" label="Cantidad Defectuosa" type="number" min="0" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="field-observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="field-observaciones" name="observaciones" rows="3" maxlength="500"
                            placeholder="Ingrese observaciones sobre el proceso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <x-ui.button-save id="add-btn" text="Registrar" icon="ri-add-line" loading-text="Registrando..." />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>