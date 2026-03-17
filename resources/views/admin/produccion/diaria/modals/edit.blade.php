<!-- Modal para editar registro -->
<div class="modal fade atlantico-modal atlantico-modal--op" id="editModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Registro de Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Información de la Orden</h6>
                        <p class="mb-1"><strong>Orden:</strong> #<span id="edit_orden_id"></span></p>
                        <p class="mb-1"><strong>Producto:</strong> <span id="edit_producto"></span></p>
                        <p class="mb-1"><strong>Empleado:</strong> <span id="edit_operario"></span></p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="cantidad_producida" label="Cantidad Producida" type="number" min="1" required id="edit_cantidad_producida" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="cantidad_defectuosa" label="Cantidad Defectuosa" type="number" min="0" required id="edit_cantidad_defectuosa" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="edit_observaciones" name="observaciones" rows="3"
                            maxlength="500" placeholder="Ingrese observaciones sobre el proceso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <x-ui.button-save id="update-btn" text="Actualizar" icon="ri-save-line" loading-text="Actualizando..." />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>