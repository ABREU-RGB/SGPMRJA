<!-- Modal para crear/editar orden de producción -->
<div class="modal fade atlantico-modal atlantico-modal--op" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nueva Orden de Producción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ordenForm" novalidate>
                @csrf
                <div class="modal-body">
                    {{-- id de la orden (modo edición) --}}
                    <input type="hidden" id="id-field" />
                    {{-- línea del pedido que produce la orden (de aquí salen producto + cantidad en el backend) --}}
                    <input type="hidden" id="detalle-pedido-id-field" name="detalle_pedido_id" />
                    {{-- referencias de solo lectura (sin name → no se envían) --}}
                    <input type="hidden" id="pedido-id-hidden-field" />
                    <input type="hidden" id="producto-id-field" />

                    {{-- ── Línea del pedido (solo lectura) ───────────────────── --}}
                    <div class="border rounded p-3 bg-light mb-3" id="orden-linea-panel">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-info-subtle text-info" id="orden-linea-pedido">Pedido #—</span>
                            <span class="text-muted fs-12" id="orden-linea-cliente"></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h6 class="mb-1" id="orden-linea-producto">—</h6>
                                <div class="d-flex flex-wrap gap-2 fs-12 text-muted" id="orden-linea-meta"></div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-18" id="orden-linea-cantidad">0</div>
                                <small class="text-muted">unidades a producir</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── Empleado + Estado ─────────────────────────────────── --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="empleado-id-field" class="form-label required">Empleado asignado</label>
                            <select id="empleado-id-field" name="empleado_id" class="form-select" required>
                                <option value="">Seleccione empleado...</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="estado-container" style="display: none;">
                            <label for="estado-field" class="form-label">Estado</label>
                            <select id="estado-field" name="estado" class="form-control">
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Finalizado">Finalizado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    {{-- ── Fechas ────────────────────────────────────────────── --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha-inicio-field" class="form-label required">Fecha de Inicio</label>
                            <input type="date" id="fecha-inicio-field" name="fecha_inicio" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label for="fecha-fin-estimada-field" class="form-label required">Fecha Fin Estimada</label>
                            <input type="date" id="fecha-fin-estimada-field" name="fecha_fin_estimada" class="form-control" required />
                        </div>
                    </div>

                    {{-- ── Costo ─────────────────────────────────────────────── --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="costo-estimado-field" class="form-label required">Costo Estimado</label>
                            <input type="number" id="costo-estimado-field" name="costo_estimado" class="form-control" step="0.01" min="0" required />
                        </div>
                    </div>

                    {{-- ── Insumos requeridos ────────────────────────────────── --}}
                    <div class="mb-3">
                        <label class="form-label">Insumos Requeridos</label>
                        <div id="insumos-container">
                            <div class="row insumo-row">
                                <div class="col-md-6">
                                    <select name="insumos[0][id]" class="form-control insumo-select" required>
                                        <option value="">Seleccione insumo...</option>
                                        @foreach($insumos as $insumo)
                                            <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad_medida }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="insumos[0][cantidad_estimada]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" required />
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-insumo"><i class="ri-delete-bin-line"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-info" id="add-insumo-btn">
                                <i class="ri-add-line"></i> Agregar Insumo
                            </button>
                        </div>
                    </div>

                    {{-- ── Notas ─────────────────────────────────────────────── --}}
                    <div class="mb-3">
                        <label for="notas-field" class="form-label">Notas</label>
                        <textarea id="notas-field" name="notas" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" id="add-btn">
                            <i class="ri-add-line me-1"></i>Crear Orden
                        </button>
                        <button type="submit" class="btn btn-success" id="edit-btn" style="display: none;">
                            <i class="ri-save-line me-1"></i>Actualizar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
