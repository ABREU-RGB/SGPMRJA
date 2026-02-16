<!-- Modal Agregar Insumo Rápido -->
<div class="modal fade" id="modalAddInsumo" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Agregar Nuevo Insumo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="insumoFormMovimiento">
                <div class="modal-body">
                    <!-- Nombre y Tipo -->
                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="nombre" label="Nombre del Insumo" placeholder="Ej: Tela Oxford" required id="nombre-field-insumo" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="tipo" label="Tipo" required id="tipo-field-insumo"
                                :options="['Tela' => 'Tela', 'Hilo' => 'Hilo', 'Botón' => 'Botón', 'Cierre' => 'Cierre', 'Etiqueta' => 'Etiqueta', 'Otro' => 'Otro']"
                                placeholder="Seleccione tipo..." />
                        </div>
                    </div>

                    <!-- Unidad de Medida y Costo -->
                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="unidad_medida" label="Unidad de Medida" placeholder="Ej: Metros, Unidades, Kg" required id="unidad-medida-field-insumo" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="costo_unitario" label="Costo Unitario ($)" type="number" step="0.01" min="0" placeholder="0.00" required prepend="$" id="costo-unitario-field-insumo" />
                        </div>
                    </div>

                    <!-- Stock Actual y Mínimo -->
                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="stock_actual" label="Stock Inicial" type="number" step="0.01" min="0" value="0" placeholder="0" required hint="Cantidad inicial en inventario" id="stock-actual-field-insumo" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="stock_minimo" label="Stock Mínimo" type="number" step="0.01" min="0" placeholder="0" required hint="Nivel para alertas de reabastecimiento" id="stock-minimo-field-insumo" />
                        </div>
                    </div>

                    <!-- Proveedor -->
                    <div class="row">
                        <div class="col-12">
                            <x-forms.select name="proveedor_id" label="Proveedor" required id="proveedor-id-field-insumo"
                                :options="$proveedores->pluck('razon_social', 'id')->toArray()"
                                placeholder="Seleccione proveedor..." />
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success" id="add-btn-insumo">
                        <i class="ri-add-line me-1"></i>Agregar Insumo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>