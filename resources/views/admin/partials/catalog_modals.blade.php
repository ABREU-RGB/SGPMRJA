<!-- Modal para seleccionar Logo (Catálogo de Logos) -->
<div class="modal fade" id="logoSearchModal" tabindex="-1" aria-labelledby="logoSearchModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1110;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header p-3" id="logoSearchModal-header" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="logoSearchModalLabel">
                    <i class="ri-image-line me-2" style="opacity: 0.7;"></i>Buscar y Seleccionar Logo
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <style>
                    #logoSearchModalTable thead,
                    #logoSearchModalTable thead tr {
                        background: #1e3c72;
                        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                    }

                    #logoSearchModalTable thead tr th {
                        background: transparent !important;
                        background-color: transparent !important;
                        --bs-table-bg: transparent;
                        --bs-table-accent-bg: transparent;
                        --bs-table-striped-bg: transparent;
                        --bs-table-hover-bg: transparent;
                        --bs-table-active-bg: transparent;
                        color: white !important;
                        border: none !important;
                        border-top: none !important;
                        border-bottom: none !important;
                        border-left: none !important;
                        border-right: none !important;
                        font-size: 0.78rem;
                        letter-spacing: 0.04em;
                        text-transform: uppercase;
                        font-weight: 600;
                    }

                    #logoSearchModalTable tbody td {
                        color: #333 !important;
                        vertical-align: middle;
                    }

                    #logoSearchModalTable .logo-filename-cell {
                        font-size: 0.74rem;
                        color: #9099a8 !important;
                        font-style: italic;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        max-width: 160px;
                    }
                </style>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header border-0 bg-soft-success">
                        <h6 class="mb-0 text-atlantico-cyan">
                            <i class="ri-filter-3-line me-2"></i>Búsqueda de logo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <span class="input-group-text bg-soft-primary border-primary text-primary">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" id="buscarLogoModal" class="form-control border-primary"
                                placeholder="Buscar por nombre del logo...">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="ri-information-line me-1"></i>Puede hacer doble clic en una fila para seleccionar
                            el logo
                        </small>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-soft-primary">
                        <h6 class="mb-0 text-atlantico-dark">
                            <i class="ri-folder-image-line me-2"></i>Catálogo de Logos
                            <span class="badge bg-secondary ms-2" id="logoModalCount">0 logos</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 350px;">
                            <table class="table table-hover mb-0" id="logoSearchModalTable">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th style="width:65%; border:none;"><i class="ri-image-line me-1"></i>Nombre del
                                            Logo</th>
                                        <th style="width:25%; border:none;"><i class="ri-file-line me-1"></i>Archivo
                                            Original</th>
                                        <th style="width:10%; border:none;" class="text-center"><i
                                                class="ri-check-line"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="logoModalBody">
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Cargando logos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="colorCatalogoModal" tabindex="-1" aria-labelledby="colorCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="colorCatalogoModalLabel">
                    <i class="ri-palette-line me-2" style="opacity:0.7;"></i>Catálogo de Colores
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <div class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-start"
                    style="font-size: 0.78rem; background: rgba(241, 196, 15, 0.12); border-color: rgba(241, 196, 15, 0.3); color: #7d6608;">
                    <i class="ri-information-line me-2 mt-1 flex-shrink-0" style="font-size: 1rem;"></i>
                    <span><strong>Nota:</strong> Los colores mostrados son meramente referenciales.
                        El tono físico del material o tela puede variar.</span>
                </div>

                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarColorModal" class="form-control" placeholder="Buscar color..."
                        style="border-color: #1e3c72;">
                </div>

                <div id="coloresSwatchGrid" style="max-height: 360px; overflow-y: auto; overflow-x: hidden;">
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tallaCatalogoModal" tabindex="-1" aria-labelledby="tallaCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="tallaCatalogoModalLabel">
                    <i class="ri-t-shirt-line me-2" style="opacity:0.7;"></i>Catálogo de Tallas
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarTallaModal" class="form-control" placeholder="Buscar talla..."
                        style="border-color: #1e3c72;">
                </div>

                <div id="tallasCatalogoGrid" style="max-height: 360px; overflow-y: auto; overflow-x: hidden;">
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubicacionCatalogoModal" tabindex="-1" aria-labelledby="ubicacionCatalogoModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index: 1070;">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content"
            style="box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 8px 24px rgba(0,0,0,0.35), 0 0 0 1px rgba(30,60,114,0.22); border-top: 3px solid #00d9a5;">
            <div class="modal-header p-3" style="background-color: #132649 !important;">
                <h5 class="modal-title" style="color: #ffffff !important;" id="ubicacionCatalogoModalLabel">
                    <i class="ri-map-pin-line me-2" style="opacity:0.7;"></i>Configurar Servicio de Bordado
                </h5>
                <button type="button" class="btn-close utility-modal-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3" style="overflow-x: hidden;">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" style="background: rgba(30,60,114,0.1); border-color: #1e3c72;">
                        <i class="ri-search-line" style="color: #1e3c72;"></i>
                    </span>
                    <input type="text" id="buscarUbicacionModal" class="form-control" placeholder="Buscar ubicación..."
                        style="border-color: #1e3c72;">
                </div>

                <div class="alert alert-info py-2 px-3 mb-3" style="font-size:0.78rem;">
                    Asigna logo por cada ubicación, ajusta precio por ubicación y cantidad de bordados por prenda.
                </div>

                <div id="ubicacionesCatalogoGrid" style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                </div>

                <hr class="my-3">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0" style="font-size:0.85rem;color:#1e3c72;">
                        <i class="ri-edit-2-line me-1"></i>Ubicaciones personalizadas
                    </h6>
                    <button type="button" class="btn btn-sm btn-atlantico-brand" id="agregarUbicacionPersonalizadaBtn">
                        <i class="ri-add-line"></i> Agregar
                    </button>
                </div>

                <div id="ubicacionesPersonalizadasContainer" class="d-flex flex-column gap-2">
                </div>

                <div class="rounded p-2 mt-3" style="background: rgba(30,60,114,0.06);">
                    <div class="small text-muted">Recargo unitario del producto</div>
                    <div class="fw-bold" id="resumenRecargoBordadoModal" style="color:#1e3c72;">$0.00</div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-sm btn-success px-3" id="aplicarUbicacionesBordadoBtn">
                    <i class="ri-check-line me-1"></i>Aplicar configuración
                </button>
                <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
