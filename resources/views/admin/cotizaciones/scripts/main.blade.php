<style>
    /* Paleta de colores marca Atlántico */
    :root {
        --atlantico-dark-blue: #1e3c72;
        --atlantico-green: #2ecc71;
        --atlantico-cyan: #00d9a5;
        --atlantico-light-cyan: #38ef7d;
    }

    /* Estilos para dropdowns de estado - Paleta Atlántico sobria */
    .estado-pendiente {
        background-color: rgba(30, 60, 114, 0.15) !important;
        border-color: #1e3c72 !important;
        color: #1e3c72 !important;
    }

    .estado-aprobada {
        background-color: rgba(0, 217, 165, 0.15) !important;
        border-color: #00d9a5 !important;
        color: #006b52 !important;
    }

    .estado-rechazada {
        background-color: rgba(139, 58, 58, 0.15) !important;
        border-color: #8b3a3a !important;
        color: #8b3a3a !important;
    }

    /* Soft background colors for icons - Paleta Atlántico */
    .bg-soft-primary {
        background-color: rgba(30, 60, 114, 0.1) !important;
        /* azul oscuro */
    }

    .bg-soft-secondary {
        background-color: rgba(46, 204, 113, 0.15) !important;
        /* verde */
    }

    .bg-soft-success {
        background-color: rgba(0, 217, 165, 0.1) !important;
        /* cyan */
    }

    .bg-soft-info {
        background-color: rgba(56, 239, 125, 0.1) !important;
        /* verde claro */
    }

    .bg-soft-warning {
        background-color: rgba(46, 204, 113, 0.2) !important;
        /* verde más intenso */
    }

    .bg-soft-danger {
        background-color: rgba(30, 60, 114, 0.15) !important;
        /* azul oscuro más intenso */
    }

    /* Colores de texto personalizados */
    .text-atlantico-dark {
        color: #1e3c72 !important;
    }

    .text-atlantico-green {
        color: #2ecc71 !important;
    }

    .text-atlantico-cyan {
        color: #00d9a5 !important;
    }

    /* Estilo para buscador personalizado */
    .search-box {
        position: relative;
    }

    .search-box .search-icon {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #878a99;
    }

    .search-box input {
        padding-left: 30px;
    }

    .lleva-bordado-checkbox {
        border-color: #1e3c72 !important;
    }

    .lleva-bordado-checkbox:checked {
        background-color: var(--atlantico-cyan) !important;
        border-color: var(--atlantico-cyan) !important;
    }

    .bordado-label {
        color: #6c757d;
        transition: color 0.15s ease;
    }

    .bordado-label.active {
        color: var(--atlantico-dark-blue);
    }

    .bordado-resumen-box {
        border: 1px dashed rgba(30, 60, 114, 0.25);
        border-radius: 8px;
        background: rgba(30, 60, 114, 0.06);
    }

    .bordado-resumen-title {
        font-size: 0.72rem;
        color: #5a6a85;
        font-weight: 600;
    }

    .bordado-resumen-value {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--atlantico-dark-blue);
    }

    .bordado-resumen-estado {
        font-size: 0.73rem;
    }

    .bordado-linea-total-value.total-updated {
        color: #00a884;
        text-shadow: 0 0 4px rgba(0, 168, 132, 0.18);
    }

    .ubicacion-std-row,
    .ubicacion-personalizada-row {
        transition: border-color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
    }

    .ubicacion-row-disabled {
        background: rgba(30, 60, 114, 0.03);
        border-color: rgba(30, 60, 114, 0.16) !important;
    }

    .ubicacion-row-pending {
        background: rgba(46, 204, 113, 0.06);
        border-color: rgba(46, 204, 113, 0.32) !important;
    }

    .ubicacion-row-complete {
        background: rgba(0, 217, 165, 0.08);
        border-color: rgba(0, 217, 165, 0.4) !important;
        box-shadow: inset 0 0 0 1px rgba(0, 217, 165, 0.16);
    }

    .ubicacion-mini-field {
        width: 95px;
    }

    .ubicacion-mini-field.is-cantidad {
        width: 75px;
    }

    .ubicacion-mini-label {
        display: block;
        font-size: 0.66rem;
        color: #6c7a94;
        font-weight: 700;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .ubicacion-estado-ayuda {
        font-size: 0.72rem;
        line-height: 1.2;
    }
</style>
<script>
    $(document).ready(function () {
        // === FUNCIÓN GLOBAL: Capitalizar solo la primera letra ===
        function capitalizeFirstLetter(str) {
            if (!str) return str;
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function formatMoney(value) {
            var amount = Number(value || 0);
            return '$' + amount.toLocaleString('es-VE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Aplicar a campos de dirección al perder foco
        $(document).on('blur', '#direccion-field, #direccion-field-cliente, [name="direccion"]', function () {
            var val = $(this).val();
            if (val && val.length > 0) {
                $(this).val(capitalizeFirstLetter(val));
            }
        });
        var table = $('#cotizaciones-table').DataTable({
            responsive: true,
            dom: 'rtip', /* Ocultar buscador (f) y selector de longitud (l) para máxima limpieza */
            processing: true,
            serverSide: true,
            ajax: "{{ route('cotizaciones.data') }}",
            columns: [
                { data: 'id', name: 'id', title: 'Nro.', width: '6%' },
                { data: 'cliente_nombre', name: 'cliente_nombre', width: '25%' },
                { data: 'fecha_cotizacion', name: 'fecha_cotizacion', width: '14%' },
                {
                    data: 'total',
                    name: 'total',
                    width: '14%',
                    render: $.fn.dataTable.render.number(',', '.', 2, '$')
                },
                {
                    data: 'estado',
                    name: 'estado',
                    width: '12%',
                    className: 'text-center',
                    render: function (data, type, row) {
                        var estadoClasses = {
                            'Pendiente': 'status-pendiente',
                            'Aprobada': 'status-aprobada',
                            'Convertida': 'badge-soft-info',
                            'Cancelado': 'status-cancelado',
                            'Vencida': 'status-cancelado'
                        };
                        var estadoIcons = {
                            'Pendiente': 'ri-time-line',
                            'Aprobada': 'ri-check-double-line',
                            'Convertida': 'ri-exchange-line',
                            'Cancelado': 'ri-close-circle-line',
                            'Vencida': 'ri-alarm-warning-line'
                        };
                        var badgeClass = estadoClasses[data] || '';
                        var icon = estadoIcons[data] || 'ri-question-line';

                        var badge = '<span class="badge badge-status ' + badgeClass + ' rounded-pill"><i class="' + icon + ' me-1"></i>' + data + '</span>';

                        var isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};

                        // Si no es admin o ya está convertida, solo mostrar badge
                        if (!isAdmin || data === 'Convertida') {
                            return badge;
                        }

                        // Construir opciones del dropdown
                        var opciones = '';

                        if (data === 'Pendiente') {
                            opciones += '<li><a class="dropdown-item change-status-btn" href="#" data-id="' + row.id + '" data-status="Aprobada"><i class="ri-check-double-line text-success me-2"></i>Aprobar</a></li>';
                            opciones += '<li><a class="dropdown-item change-status-btn" href="#" data-id="' + row.id + '" data-status="Cancelado"><i class="ri-close-circle-line text-danger me-2"></i>Cancelar</a></li>';
                        } else if (data === 'Aprobada') {
                            opciones += '<li><a class="dropdown-item change-status-btn" href="#" data-id="' + row.id + '" data-status="Pendiente"><i class="ri-time-line text-warning me-2"></i>Volver a Pendiente</a></li>';
                            opciones += '<li><a class="dropdown-item change-status-btn" href="#" data-id="' + row.id + '" data-status="Cancelado"><i class="ri-close-circle-line text-danger me-2"></i>Cancelar</a></li>';
                        } else if (data === 'Cancelado' || data === 'Vencida') {
                            opciones += '<li><a class="dropdown-item change-status-btn" href="#" data-id="' + row.id + '" data-status="Pendiente"><i class="ri-time-line text-warning me-2"></i>Reactivar (Pendiente)</a></li>';
                        }

                        if (opciones === '') return badge;

                        return `
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink${row.id}" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                                    ${badge} <i class="ri-arrow-down-s-line ms-1 text-muted"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink${row.id}">
                                    ${opciones}
                                </ul>
                            </div>
                        `;
                    }
                },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        var isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};

                        // Botón Convertir a Pedido (solo si está Aprobada)
                        var convertBtn = '';
                        if (row.estado === 'Aprobada' && isAdmin) {
                            convertBtn = `
                                <button class="btn btn-sm btn-soft-primary convert-to-pedido-btn" data-id="${data}" title="Convertir a Pedido">
                                    <i class="ri-exchange-line"></i>
                                </button>
                            `;
                        }

                        // Botones Editar y Eliminar (solo si NO está Convertida ni Cancelado)
                        var editDelete = '';
                        if (isAdmin && row.estado !== 'Convertida' && row.estado !== 'Cancelado' && row.estado !== 'Vencida') {
                            editDelete = `
                            <button class="btn btn-sm btn-soft-success edit-btn" data-id="${data}" title="Editar">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger remove-btn" data-id="${data}" title="Eliminar">
                                <i class="ri-delete-bin-fill"></i>
                            </button>`;
                        }

                        // Layout horizontal compacto con gap-1
                        return `
                            <div class="d-flex gap-1 justify-content-center align-items-center flex-wrap">
                                <button class="btn btn-sm btn-soft-info view-btn" data-id="${data}" title="Ver">
                                    <i class="ri-eye-fill"></i>
                                </button>
                                ${convertBtn}
                                ${editDelete}
                                <a class="btn btn-sm btn-soft-secondary" href="/cotizaciones/${data}/pdf" target="_blank" title="PDF">
                                    <i class="ri-file-pdf-line"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            language: {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pdf": "PDF",
                    "print": "Imprimir",
                    "colvis": "Visibilidad de Columna"
                }
            }
        });

        // Buscador personalizado
        $('#custom-search-input').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Ajustar columnas cuando se redimensiona la ventana
        $(window).on('resize', function () {
            table.columns.adjust();
        });
        // Ajustar después de carga inicial
        setTimeout(function () {
            table.columns.adjust();
        }, 100);

        // === Lógica de productos (Adaptada de Pedidos) ===
        var products = @json($productos);
        var productItemIndex = 0;
        var productosModalCotizacion = null;
        var currentProductIndex = null; // Para saber qué items editar si fuera el caso

        // Inicializar Modal y cargar datos
        try {
            productosModalCotizacion = new bootstrap.Modal(document.getElementById('productosModalCotizacion'));
            cargarTiposEnFiltroCotizacion();
        } catch (e) {
            console.error('Error inicializando modal cotización:', e);
        }

        // Eventos del Modal
        $('#buscarProductoModalCotizacion').on('keyup', function () {
            renderizarProductosModalCotizacion($(this).val(), $('#filtroTipoProductoCotizacion').val());
        });
        $('#filtroTipoProductoCotizacion').on('change', function () {
            renderizarProductosModalCotizacion($('#buscarProductoModalCotizacion').val(), $(this).val());
        });

        // Botón "Agregar Otro Producto" (Añade tarjeta vacía)
        $('#add-producto-item').on('click', function () {
            addProductItem(); // Agrega tarjeta vacía
            // Opcional: Scrollear hacia el nuevo item
            // var newDetails = $('#productos-container').children().last();
            // if(newDetails.length) newDetails[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        function cargarTiposEnFiltroCotizacion() {
            var tipos = [];
            if (typeof products !== 'undefined' && products) {
                products.forEach(function (p) {
                    if (p.tipo_producto && !tipos.find(t => t.id === p.tipo_producto.id)) {
                        tipos.push(p.tipo_producto);
                    }
                });
            }
            var select = $('#filtroTipoProductoCotizacion');
            select.find('option:not(:first)').remove();
            tipos.forEach(function (tipo) {
                select.append('<option value="' + tipo.id + '">' + tipo.nombre + '</option>');
            });
        }

        function renderizarProductosModalCotizacion(filtro, tipoId) {
            filtro = filtro || '';
            tipoId = tipoId || '';
            var tbody = $('#productosModalCotizacionBody');
            tbody.empty();

            if (typeof products === 'undefined' || !products) {
                tbody.append('<tr><td colspan="6" class="text-center text-danger py-4"><i class="ri-error-warning-line fs-2 d-block mb-2"></i>Error: No se pudieron cargar los productos.</td></tr>');
                return;
            }

            if (products.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted py-4"><i class="ri-inbox-line fs-2 d-block mb-2"></i>No hay productos registrados en el sistema.</td></tr>');
                return;
            }

            var productosFiltrados = products.filter(function (p) {
                var matchFiltro = true;
                var matchTipo = true;
                if (filtro) {
                    var busqueda = filtro.toLowerCase();
                    var codigo = (p.codigo || '').toLowerCase();
                    var modelo = (p.modelo || '').toLowerCase();
                    var tipo = p.tipo_producto ? p.tipo_producto.nombre.toLowerCase() : '';
                    matchFiltro = codigo.includes(busqueda) || modelo.includes(busqueda) || tipo.includes(busqueda);
                }
                if (tipoId) {
                    matchTipo = p.tipo_producto && p.tipo_producto.id == tipoId;
                }
                return matchFiltro && matchTipo;
            });

            if (productosFiltrados.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center text-muted py-4"><i class="ri-search-2-line fs-2 d-block mb-2"></i>No se encontraron coincidencias.</td></tr>');
                return;
            }

            productosFiltrados.forEach(function (p) {
                var tipoNombre = p.tipo_producto ? p.tipo_producto.nombre : 'Sin tipo';
                var imgHtml = p.imagen ? '<img src="' + p.imagen + '" class="producto-img-thumb" alt="">' : '<span class="text-muted text-center d-block" style="width:40px; font-size:10px;">Sin IMG</span>';
                var row = '<tr data-producto-id="' + p.id + '" data-precio="' + p.precio_base + '">' +
                    '<td>' + imgHtml + '</td>' +
                    '<td><span class="badge bg-dark">' + (p.codigo || '-') + '</span></td>' +
                    '<td><span class="badge bg-primary">' + tipoNombre + '</span></td>' +
                    '<td>' + p.modelo + '</td>' +
                    '<td class="text-success fw-bold text-end">' + formatMoney(parseFloat(p.precio_base || 0)) + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-sm btn-atlantico-brand select-producto-btn-cotizacion" data-id="' + p.id + '"><i class="ri-check-line"></i></button></td>' +
                    '</tr>';
                tbody.append(row);
            });
        }

        // Asegurar renderizado al abrir el modal por cualquier vía
        document.getElementById('productosModalCotizacion').addEventListener('shown.bs.modal', function () {
            renderizarProductosModalCotizacion($('#buscarProductoModalCotizacion').val(), $('#filtroTipoProductoCotizacion').val());
        });

        // Ajuste inteligente de backdrop: Solo limpiar si no queda otro modal abierto
        document.getElementById('productosModalCotizacion').addEventListener('hidden.bs.modal', function () {
            // Si el modal principal de cotización está abierto, NO quitar la clase modal-open del body
            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                // Bootstrap debería encargarse de quitar el backdrop del modal hijo automáticamente.
                // Si quedaran backdrops residuales, eliminamos solo los extra (manteniendo 1)
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    // Dejar solo uno
                    backdrops.not(backdrops.first()).remove();
                }
            } else {
                // Si no hay modal padre, limpiar todo
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('body').css('overflow', 'auto');
                $('body').css('padding-right', '');
            }
        });

        // ============================================================
        // === LÓGICA DEL MODAL DE LOGOS (Catálogo de Logos) ===
        // ============================================================

        // Logos cargados desde servidor (mismo patrón que products)
        var logos = @json($logos);
        var logoModal = null;
        var currentLogoInput = null; // Referencia al nombre-logo-input de la fila activa

        // Inicializar modal de logos
        try {
            logoModal = new bootstrap.Modal(document.getElementById('logoSearchModal'));
        } catch (e) {
            console.error('Error inicializando modal de logos:', e);
        }

        // Filtrar y renderizar logos en la tabla del modal
        function renderizarLogosModal(filtro) {
            filtro = (filtro || '').toLowerCase().trim();
            var tbody = $('#logoModalBody');
            tbody.empty();

            if (typeof logos === 'undefined' || !logos) {
                tbody.append('<tr><td colspan="3" class="text-center text-danger py-4"><i class="ri-error-warning-line fs-2 d-block mb-2"></i>Error: No se pudieron cargar los logos.</td></tr>');
                $('#logoModalCount').text('0 logos');
                return;
            }

            if (logos.length === 0) {
                tbody.append('<tr><td colspan="3" class="text-center text-muted py-4"><i class="ri-inbox-line fs-2 d-block mb-2"></i>No hay logos registrados en el sistema.<br><small>Ejecute el LogoSeeder para cargar el catálogo.</small></td></tr>');
                $('#logoModalCount').text('0 logos');
                return;
            }

            var logosFiltrados = logos.filter(function (l) {
                if (!filtro) return true;
                return (l.name || '').toLowerCase().includes(filtro) ||
                    (l.original_filename || '').toLowerCase().includes(filtro);
            });

            $('#logoModalCount').text(logosFiltrados.length + ' logo' + (logosFiltrados.length !== 1 ? 's' : ''));

            if (logosFiltrados.length === 0) {
                tbody.append('<tr><td colspan="3" class="text-center text-muted py-4"><i class="ri-search-2-line fs-2 d-block mb-2"></i>No se encontraron coincidencias.</td></tr>');
                return;
            }

            logosFiltrados.forEach(function (l) {
                var safeName = $('<div>').text(l.name).html();
                var safeFile = $('<div>').text(l.original_filename).html();
                var row = '<tr data-logo-id="' + l.id + '" data-logo-name="' + safeName + '">' +
                    '<td style="width:65%">' +
                    '<i class="ri-threads-line logo-row-icon me-2"></i>' +
                    '<span class="fw-semibold">' + safeName + '</span>' +
                    '</td>' +
                    '<td class="logo-filename-cell" style="width:25%" title="' + safeFile + '">' + safeFile + '</td>' +
                    '<td class="text-center" style="width:10%">' +
                    '<button type="button" class="btn btn-sm btn-atlantico-brand select-logo-btn px-2 py-1"' +
                    ' data-logo-id="' + l.id + '" data-logo-name="' + safeName + '">' +
                    '<i class="ri-check-line"></i>' +
                    '</button>' +
                    '</td>' +
                    '</tr>';
                tbody.append(row);
            });
        }

        // Preparar capas antes de animar el modal de logos (evita parpadeos)
        document.getElementById('logoSearchModal').addEventListener('show.bs.modal', function () {
            var zIndex = 1085;
            if ($('#ubicacionCatalogoModal').hasClass('show')) {
                zIndex = 1105;
            } else if ($('#showModal').hasClass('show')) {
                zIndex = 1095;
            }

            $('#logoSearchModal').css('z-index', zIndex);
            window.requestAnimationFrame(function () {
                var $lastBackdrop = $('.modal-backdrop').last();
                if ($lastBackdrop.length) {
                    $lastBackdrop.css('z-index', zIndex - 1).addClass('logo-modal-backdrop');
                }
            });
        });

        // Renderizar al abrir el modal de logos
        document.getElementById('logoSearchModal').addEventListener('shown.bs.modal', function () {

            $('#buscarLogoModal').val('').focus();
            renderizarLogosModal('');
        });

        // Limpieza de backdrop al cerrar el modal de logos (mismo patrón que productos)
        document.getElementById('logoSearchModal').addEventListener('hidden.bs.modal', function () {
            $('#logoSearchModal').css('z-index', '');
            $('.modal-backdrop.logo-modal-backdrop').removeClass('logo-modal-backdrop').css('z-index', '');

            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops.not(backdrops.first()).remove();
                }
            } else {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('body').css('overflow', 'auto');
                $('body').css('padding-right', '');
            }
        });

        // Filtrado en tiempo real al escribir
        $('#buscarLogoModal').on('keyup', function () {
            renderizarLogosModal($(this).val());
        });

        // Abrir modal de logos desde inputs de bordado (y compatibilidad legacy)
        $(document).on('click', '.buscar-logo-trigger, .bordado-logo-picker', function (e) {
            e.preventDefault();
            var $targetGroup = $(this).closest('.input-group');
            currentLogoInput = $targetGroup.find('.nombre-logo-input, .bordado-logo-input').first();
            if (!currentLogoInput || !currentLogoInput.length || currentLogoInput.prop('disabled')) {
                currentLogoInput = null;
                return;
            }
            if (logoModal) logoModal.show();
        });

        // Seleccionar logo por clic en botón ✓
        $(document).on('click', '.select-logo-btn', function (e) {
            e.preventDefault();
            var logoName = $(this).data('logo-name');
            seleccionarLogo(logoName);
        });

        // Seleccionar logo por doble clic en fila
        $(document).on('dblclick', '#logoSearchModalTable tbody tr', function (e) {
            e.preventDefault();
            var logoName = $(this).data('logo-name');
            if (logoName) seleccionarLogo(logoName);
        });

        // Función central de selección de logo
        function seleccionarLogo(logoName) {
            if (!logoName || !currentLogoInput) return;

            currentLogoInput.val(logoName);

            var ubicacionRow = currentLogoInput.closest('.ubicacion-std-row, .ubicacion-personalizada-row');
            if (ubicacionRow && ubicacionRow.length) {
                if (ubicacionRow.hasClass('ubicacion-std-row')) {
                    actualizarEstadoUbicacionStdRow(ubicacionRow);
                } else {
                    actualizarEstadoUbicacionPersonalizadaRow(ubicacionRow);
                }
                actualizarResumenRecargoModal();
            }

            // Cerrar modal de logos
            if (logoModal) logoModal.hide();

            // Mantener modal padre abierto (mismo patrón que productos)
            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops.not(backdrops.first()).remove();
                }
            }

            currentLogoInput = null;
        }

        // ============================================================
        // === FIN LÓGICA MODAL DE LOGOS ===
        // ============================================================

        // ============================================================
        // === INICIO LÓGICA MODAL DE COLORES ===
        // ============================================================
        var coloresArray = [];
        var colorModal = null;
        var currentColorInput = null;  // referencia al input que disparó el modal

        // Inicializar modal de colores
        if (document.getElementById('colorCatalogoModal')) {
            colorModal = new bootstrap.Modal(document.getElementById('colorCatalogoModal'));
        }

        // Cargar colores via AJAX al iniciar
        $.get("{{ route('colores.data') }}", function (data) {
            coloresArray = data;
        });

        // Renderizar swatch grid agrupado por categoría
        function renderizarColoresModal(filtro) {
            var grid = $('#coloresSwatchGrid');
            grid.empty();
            filtro = (filtro || '').toLowerCase().trim();

            // Agrupar colores por grupo
            var grupos = {};
            coloresArray.forEach(function (c) {
                if (filtro && c.nombre.toLowerCase().indexOf(filtro) === -1) return;
                var g = c.grupo || 'Otros';
                if (!grupos[g]) grupos[g] = [];
                grupos[g].push(c);
            });

            if (Object.keys(grupos).length === 0) {
                grid.html('<div class="text-center text-muted py-4"><i class="ri-palette-line d-block" style="font-size:2rem;opacity:0.3;"></i><small>No se encontraron colores</small></div>');
                return;
            }

            // Renderizar cada grupo
            Object.keys(grupos).forEach(function (grupoNombre) {
                grid.append('<div class="color-grupo-header">' + grupoNombre + '</div>');
                var items = grupos[grupoNombre];
                items.forEach(function (c) {
                    // Bordes especiales para blanco/crema (se pierden contra fondo blanco)
                    var borderStyle = (c.hex_referencial === '#FFFFFF' || c.hex_referencial === '#FFFDD0')
                        ? 'border: 2px solid #ddd;'
                        : 'border: 2px solid rgba(0,0,0,0.12);';
                    grid.append(
                        '<div class="color-swatch-item select-color-btn" data-color-nombre="' + c.nombre + '" data-color-hex="' + c.hex_referencial + '">' +
                        '  <span class="color-swatch-circle" style="background-color:' + c.hex_referencial + '; ' + borderStyle + '"></span>' +
                        '  <span class="fw-medium">' + c.nombre + '</span>' +
                        '</div>'
                    );
                });
            });
        }

        // Abrir modal y focus en el buscador
        document.getElementById('colorCatalogoModal').addEventListener('shown.bs.modal', function () {
            $('#buscarColorModal').val('').trigger('focus');
            renderizarColoresModal('');
        });

        // Limpiar backdrop al cerrar
        document.getElementById('colorCatalogoModal').addEventListener('hidden.bs.modal', function () {
            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops.not(backdrops.first()).remove();
                }
            }
        });

        // Filtrado en tiempo real
        $('#buscarColorModal').on('keyup', function () {
            renderizarColoresModal($(this).val());
        });

        // Abrir modal de colores desde la fila del producto
        $('#productos-container').on('click', '.buscar-color-trigger', function (e) {
            e.preventDefault();
            currentColorInput = $(this).closest('.input-group').find('.color-input');
            if (colorModal) colorModal.show();
        });

        // Seleccionar color por clic en swatch
        $(document).on('click', '.select-color-btn', function (e) {
            e.preventDefault();
            var nombre = $(this).data('color-nombre');
            var hex = $(this).data('color-hex');
            seleccionarColor(nombre, hex);
        });

        // Función central de selección de color
        function seleccionarColor(nombre, hex) {
            if (!nombre || !currentColorInput) return;

            // Llenar el input con el nombre del color
            currentColorInput.val(nombre);

            // Actualizar el dot indicador con el hex del color
            var dotSpan = currentColorInput.closest('.input-group').find('.color-dot-indicator');
            if (dotSpan.length) {
                dotSpan.css({
                    'background-color': hex,
                    'border': (hex === '#FFFFFF' || hex === '#FFFDD0')
                        ? '1.5px solid #ddd' : '1.5px solid rgba(0,0,0,0.15)'
                });
            }

            // Cerrar modal
            if (colorModal) colorModal.hide();

            // Mantener modal padre abierto
            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops.not(backdrops.first()).remove();
                }
            }

            currentColorInput = null;
        }

        // ============================================================
        // === FIN LÓGICA MODAL DE COLORES ===
        // ============================================================

        // ============================================================
        // === INICIO LÓGICA MODAL DE TALLAS ===
        // ============================================================
        var tallaModal = null;
        var tallaModalEl = document.getElementById('tallaCatalogoModal');
        var currentTallaInput = null;
        var currentTallaValueInput = null;

        if (tallaModalEl) {
            tallaModal = new bootstrap.Modal(tallaModalEl);
        }

        // Abrir modal desde un item existente (EDITAR/CAMBIAR PRODUCTO)
        $('#productos-container').on('click', '.producto-selector-trigger', function () {
            currentProductIndex = $(this).closest('.product-item').data('product-index');
            $('#buscarProductoModalCotizacion').val('');
            $('#filtroTipoProductoCotizacion').val('');
            renderizarProductosModalCotizacion('', '');
            productosModalCotizacion.show();
        });

        // Seleccionar producto desde la tabla (click botón o doble click fila)
        $(document).on('click', '.select-producto-btn-cotizacion', function () {
            // Prevenir comportamiento de submit si lo hubiera
            seleccionarProductoCotizacion($(this).data('id'));
        });

        $(document).on('dblclick', '#productosModalCotizacionTable tbody tr', function (e) {
            e.preventDefault(); // Prevenir cualquier acción por defecto
            var productoId = $(this).data('producto-id');
            if (productoId) seleccionarProductoCotizacion(productoId);
        });

        function seleccionarProductoCotizacion(productoId) {
            var producto = products.find(function (p) { return p.id == productoId; });
            if (!producto) return;

            // Función helper para cerrar modal de forma segura respetando modal padre
            var cerrarModalSeguro = function () {
                if (productosModalCotizacion) productosModalCotizacion.hide();

                // Si el modal padre está abierto, mantener su estado
                if ($('#showModal').hasClass('show')) {
                    $('body').addClass('modal-open');
                    var backdrops = $('.modal-backdrop');
                    if (backdrops.length > 1) {
                        backdrops.not(backdrops.first()).remove();
                    }
                } else {
                    // Limpieza total si no hay padre
                    $('body').removeClass('modal-open');
                    $('body').css('overflow', '');
                    $('body').css('padding-right', '');
                    $('.modal-backdrop').remove();
                }
            };

            if (currentProductIndex !== null) {
                // EDITAR ITEM EXISTENTE
                var card = $(`.product-item[data-product-index="${currentProductIndex}"]`);
                var tipoNombre = producto.tipo_producto ? producto.tipo_producto.nombre : 'Sin tipo';
                var displayName = (producto.codigo || '') + ' - ' + tipoNombre + ' ' + producto.modelo;

                // Actualizar valores visuales y ocultos
                card.find('.producto-text-display').val(displayName);
                card.find('.producto-text-display').css('font-weight', '600').css('color', '#212529');

                card.find('.producto-id-input').val(producto.id);
                card.find('.precio-unitario-input').val(producto.precio_base);

                // Cerrar modal
                cerrarModalSeguro();
                calculateCotizacionTotals();

            } else {
                // NUEVO ITEM
                // Cerrar modal antes de agregar
                cerrarModalSeguro();
                // Agregar item
                addProductItem(producto.id, 1, producto.precio_base, '', false, '', '', '', []);
                // Recalcular
                calculateCotizacionTotals();
            }
        }

        var tallasArray = [];

        function cargarTallasCatalogo(callback) {
            $.get("{{ route('tallas.data') }}", function (data) {
                tallasArray = Array.isArray(data) ? data : [];
                if (typeof callback === 'function') callback();
            });
        }

        // Cargar tallas via AJAX al iniciar (patrón análogo a colores)
        cargarTallasCatalogo();

        function getTallaLabel(tallaValue) {
            if (!tallaValue) return '';
            var tallaItem = tallasArray.find(function (t) { return t.nombre === tallaValue; });
            if (tallaItem) return tallaItem.etiqueta || tallaItem.nombre;
            return tallaValue === 'Talla Unica' ? 'Única' : tallaValue;
        }

        function getTallaGroup(tallaValue) {
            if (tallaValue === 'Talla Unica') return 'Única';
            if (/^\d+$/.test(tallaValue)) return 'Numéricas';
            return 'Letras';
        }

        function renderizarTallasModal(filtro) {
            var grid = $('#tallasCatalogoGrid');
            grid.empty();
            filtro = (filtro || '').toLowerCase().trim();

            var grupos = {};
            tallasArray.forEach(function (item) {
                var value = item.nombre;
                var label = item.etiqueta || item.nombre;
                var labelLc = String(label).toLowerCase();
                var valueLc = String(value).toLowerCase();
                if (filtro && labelLc.indexOf(filtro) === -1 && valueLc.indexOf(filtro) === -1) return;

                var groupName = item.grupo || getTallaGroup(value);
                if (!grupos[groupName]) grupos[groupName] = [];
                grupos[groupName].push({ value: value, label: label });
            });

            var groupOrder = ['Única', 'Numéricas', 'Letras'];
            var rendered = false;

            groupOrder.forEach(function (groupName) {
                var items = grupos[groupName];
                if (!items || !items.length) return;

                rendered = true;
                grid.append('<div class="color-grupo-header">' + groupName + '</div>');
                var chipsContainer = $('<div class="mb-2"></div>');

                items.forEach(function (t) {
                    chipsContainer.append(
                        '<button type="button" class="talla-chip-item select-talla-btn" data-talla-value="' + t.value + '">' + t.label + '</button>'
                    );
                });

                grid.append(chipsContainer);
            });

            if (!rendered) {
                grid.html('<div class="text-center text-muted py-4"><i class="ri-t-shirt-line d-block" style="font-size:2rem;opacity:0.3;"></i><small>No se encontraron tallas</small></div>');
            }
        }

        if (tallaModalEl) {
            tallaModalEl.addEventListener('shown.bs.modal', function () {
                $('#buscarTallaModal').val('').trigger('focus');

                if (!tallasArray.length) {
                    $('#tallasCatalogoGrid').html('<div class="text-center text-muted py-4"><small>Cargando tallas...</small></div>');
                    cargarTallasCatalogo(function () {
                        renderizarTallasModal('');
                    });
                    return;
                }

                renderizarTallasModal('');
            });

            tallaModalEl.addEventListener('hidden.bs.modal', function () {
                if ($('#showModal').hasClass('show')) {
                    $('body').addClass('modal-open');
                    var backdrops = $('.modal-backdrop');
                    if (backdrops.length > 1) {
                        backdrops.not(backdrops.first()).remove();
                    }
                }
            });
        }

        $('#buscarTallaModal').on('keyup', function () {
            renderizarTallasModal($(this).val());
        });

        $('#productos-container').on('click', '.buscar-talla-trigger', function (e) {
            e.preventDefault();
            var group = $(this).closest('.input-group');
            currentTallaInput = group.find('.talla-input-display');
            currentTallaValueInput = group.find('.talla-input-value');
            if (tallaModal) tallaModal.show();
        });

        $(document).on('click', '.select-talla-btn', function (e) {
            e.preventDefault();
            seleccionarTalla($(this).data('talla-value'));
        });

        function seleccionarTalla(tallaValue) {
            if (!tallaValue || !currentTallaInput || !currentTallaValueInput) return;

            currentTallaInput.val(getTallaLabel(tallaValue));
            currentTallaValueInput.val(tallaValue).trigger('change');

            if (tallaModal) tallaModal.hide();

            if ($('#showModal').hasClass('show')) {
                $('body').addClass('modal-open');
                var backdrops = $('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops.not(backdrops.first()).remove();
                }
            }

            currentTallaInput = null;
            currentTallaValueInput = null;
        }

        // ============================================================
        // === FIN LÓGICA MODAL DE TALLAS ===
        // ============================================================

        // ============================================================
        // === INICIO LÓGICA MODAL DE UBICACIÓN DE BORDADO ===
        // ============================================================
        var ubicacionModal = null;
        var ubicacionModalEl = document.getElementById('ubicacionCatalogoModal');
        var currentBordadoCard = null;
        var ubicacionesBordadoArray = [];

        if (ubicacionModalEl) {
            ubicacionModal = new bootstrap.Modal(ubicacionModalEl);
        }

        function cargarUbicacionesBordado(callback) {
            $.get("{{ route('cotizaciones.ubicacionesBordado.data') }}", function (data) {
                ubicacionesBordadoArray = Array.isArray(data) ? data : [];
                if (typeof callback === 'function') callback();
            });
        }

        cargarUbicacionesBordado();

        function getCardBordados($card) {
            var bordados = $card.data('bordados');
            return Array.isArray(bordados) ? bordados : [];
        }

        function setCardBordados($card, bordados) {
            var normalized = Array.isArray(bordados) ? bordados.map(function (item) {
                return {
                    ubicacion_bordado_id: item.ubicacion_bordado_id || null,
                    nombre_aplicado: item.nombre_aplicado || '',
                    nombre_logo: item.nombre_logo || item.nombre_logo_aplicado || '',
                    es_personalizada: !!item.es_personalizada,
                    precio_aplicado: parseFloat(item.precio_aplicado || 0),
                    cantidad: Math.max(1, parseInt(item.cantidad || 1, 10))
                };
            }) : [];

            $card.data('bordados', normalized);
        }

        function calcularRecargoUnitarioBordadoDesdeLista(bordados) {
            if (!Array.isArray(bordados) || !bordados.length) return 0;
            return bordados.reduce(function (acc, item) {
                var precio = parseFloat(item.precio_aplicado) || 0;
                var cantidad = Math.max(1, parseInt(item.cantidad || 1, 10));
                return acc + (precio * cantidad);
            }, 0);
        }

        function sincronizarCamposOcultosBordados($card) {
            var container = $card.find('.bordados-hidden-fields');
            container.empty();

            var bordados = getCardBordados($card);
            var logosUnicos = [];
            bordados.forEach(function (item, idx) {
                var logoNombre = String(item.nombre_logo || '').trim();
                if (logoNombre && logosUnicos.indexOf(logoNombre) === -1) {
                    logosUnicos.push(logoNombre);
                }

                var fields = [
                    { key: 'ubicacion_bordado_id', value: item.ubicacion_bordado_id || '' },
                    { key: 'nombre_aplicado', value: item.nombre_aplicado || '' },
                    { key: 'nombre_logo', value: logoNombre },
                    { key: 'es_personalizada', value: item.es_personalizada ? 1 : 0 },
                    { key: 'precio_aplicado', value: parseFloat(item.precio_aplicado || 0).toFixed(2) },
                    { key: 'cantidad', value: Math.max(1, parseInt(item.cantidad || 1, 10)) }
                ];

                fields.forEach(function (field) {
                    container.append(
                        '<input type="hidden" name="productos[' + $card.data('product-index') + '][bordados][' + idx + '][' + field.key + ']" value="' + field.value + '">' 
                    );
                });
            });

            $card.find('.nombre-logo-legacy-input').val(logosUnicos.join(', '));
        }

        function actualizarResumenBordadosEnCard($card) {
            var bordados = getCardBordados($card);
            var resumenInput = $card.find('.bordados-summary-input');

            if (!bordados.length) {
                resumenInput.val('Sin configuración de bordado');
            } else {
                var resumenItems = bordados.map(function (item) {
                    var cantidad = Math.max(1, parseInt(item.cantidad || 1, 10));
                    var precio = formatMoney(parseFloat(item.precio_aplicado || 0));
                    var logo = String(item.nombre_logo || '').trim();
                    var logoTexto = logo ? (logo + ' → ') : '';
                    return logoTexto + item.nombre_aplicado + ' x' + cantidad + ' (' + precio + ')';
                });

                var resumen = resumenItems.length > 2
                    ? resumenItems.slice(0, 2).join(' · ') + ' · +' + (resumenItems.length - 2) + ' más'
                    : resumenItems.join(' · ');

                resumenInput.val(resumen);
            }

            var recargo = calcularRecargoUnitarioBordadoDesdeLista(bordados);
            var base = parseFloat($card.find('.precio-unitario-input').val()) || 0;
            var finalUnit = base + recargo;
            var llevaBordado = parseInt($card.find('.lleva-bordado-value').val() || 0, 10) === 1;
            actualizarPanelResumenMonetario($card, recargo, finalUnit, llevaBordado);
            sincronizarCamposOcultosBordados($card);
        }

        function actualizarPanelResumenMonetario($card, recargo, finalUnit, llevaBordado) {
            var cantidad = parseFloat($card.find('.cantidad-input').val()) || 0;
            var totalLinea = finalUnit * cantidad;
            var $totalLineaEl = $card.find('.bordado-linea-total-value');
            var totalAnterior = parseFloat(($totalLineaEl.data('total-prev') || 0));

            $card.find('.bordado-recargo-value').text(formatMoney(recargo));
            $card.find('.bordado-final-value').text(formatMoney(finalUnit));
            $totalLineaEl.text(formatMoney(totalLinea));

            if (Math.abs(totalLinea - totalAnterior) > 0.0001) {
                $totalLineaEl.addClass('total-updated');
                setTimeout(function () {
                    $totalLineaEl.removeClass('total-updated');
                }, 380);
            }

            $totalLineaEl.data('total-prev', totalLinea);

            var bordadosCount = getCardBordados($card).length;
            var estadoVisible = !llevaBordado || bordadosCount > 0;
            var estadoTexto = !llevaBordado
                ? 'Servicio de bordado inactivo'
                : (bordadosCount > 0 ? 'Configuración aplicada' : '');
            $card.find('.bordado-resumen-estado')
                .text(estadoTexto)
                .toggleClass('d-none', !estadoVisible);
        }

        function renderizarUbicacionesModal(filtro) {
            var grid = $('#ubicacionesCatalogoGrid');
            grid.empty();
            filtro = (filtro || '').toLowerCase().trim();

            var bordadosActivos = currentBordadoCard ? getCardBordados(currentBordadoCard) : [];
            var grouped = {};
            ubicacionesBordadoArray.forEach(function (item) {
                var nombre = String(item.nombre || '').trim();
                var grupo = String(item.grupo || 'General').trim();
                if (!nombre) return;
                if (filtro && nombre.toLowerCase().indexOf(filtro) === -1 && grupo.toLowerCase().indexOf(filtro) === -1) return;

                if (!grouped[grupo]) grouped[grupo] = [];
                grouped[grupo].push(item);
            });

            var nombresGrupos = Object.keys(grouped);
            if (!nombresGrupos.length) {
                grid.html('<div class="text-center text-muted py-4"><i class="ri-map-pin-line d-block" style="font-size:2rem;opacity:0.3;"></i><small>No se encontraron ubicaciones</small></div>');
                return;
            }

            nombresGrupos.sort(function (a, b) { return a.localeCompare(b, 'es', { sensitivity: 'base' }); });
            nombresGrupos.forEach(function (grupo) {
                grid.append('<div class="color-grupo-header">' + grupo + '</div>');

                grouped[grupo].forEach(function (item) {
                    var found = bordadosActivos.find(function (b) {
                        return !b.es_personalizada && String(b.ubicacion_bordado_id) === String(item.id);
                    });

                    var checked = !!found;
                    var precio = found ? parseFloat(found.precio_aplicado || item.precio_base || 0) : parseFloat(item.precio_base || 0);
                    var cantidad = found ? Math.max(1, parseInt(found.cantidad || 1, 10)) : 1;
                    var logoNombre = found ? String(found.nombre_logo || '').trim() : '';

                    grid.append(
                        '<div class="mb-2 border rounded px-2 py-2 ubicacion-std-row" style="border-color:rgba(30,60,114,0.18)!important;">' +
                        '  <div class="d-flex align-items-start gap-2">' +
                        '    <input type="checkbox" class="form-check-input mt-0 ubicacion-std-check" data-id="' + item.id + '" data-nombre="' + item.nombre + '" ' + (checked ? 'checked' : '') + '>' +
                        '    <div class="flex-grow-1">' +
                        '      <div class="d-flex align-items-center justify-content-between gap-2">' +
                        '        <div class="fw-semibold" style="font-size:0.82rem;color:#1e3c72;">' + item.nombre + '</div>' +
                        '        <span class="badge rounded-pill bg-soft-primary text-atlantico-dark ubicacion-estado-badge">No incluida</span>' +
                        '      </div>' +
                        '      <small class="text-muted">Base catálogo: $' + parseFloat(item.precio_base || 0).toFixed(2) + '</small>' +
                        '    </div>' +
                        '    <div class="ubicacion-mini-field">' +
                        '      <span class="ubicacion-mini-label">Precio</span>' +
                        '      <input type="number" class="form-control form-control-sm ubicacion-std-precio" step="0.01" min="0" value="' + precio.toFixed(2) + '">' +
                        '    </div>' +
                        '    <div class="ubicacion-mini-field is-cantidad">' +
                        '      <span class="ubicacion-mini-label">Cant.</span>' +
                        '      <input type="number" class="form-control form-control-sm ubicacion-std-cantidad text-center" step="1" min="1" value="' + cantidad + '">' +
                        '    </div>' +
                        '  </div>' +
                        '  <div class="input-group input-group-sm mt-2">' +
                        '    <input type="text" class="form-control bordado-logo-input ubicacion-std-logo" placeholder="Logo para esta ubicación" value="' + logoNombre + '" readonly autocomplete="off" style="background-color:#fff;cursor:default;" ' + (checked ? '' : 'disabled') + '>' +
                        '    <button type="button" class="btn btn-sm btn-atlantico-brand bordado-logo-picker" ' + (checked ? '' : 'disabled') + '>' +
                        '      <i class="ri-search-line" style="color:#fff;"></i>' +
                        '    </button>' +
                        '  </div>' +
                        '  <small class="text-muted d-block mt-1 ubicacion-estado-ayuda"></small>' +
                        '</div>'
                    );
                });
            });

            actualizarResumenRecargoModal();
            actualizarEstadosUbicacionesModal();
        }

        function renderizarUbicacionesPersonalizadasModal() {
            var container = $('#ubicacionesPersonalizadasContainer');
            container.empty();

            if (!currentBordadoCard) return;

            var customItems = getCardBordados(currentBordadoCard).filter(function (item) {
                return !!item.es_personalizada;
            });

            if (!customItems.length) {
                container.html('<small class="text-muted">No hay ubicaciones personalizadas.</small>');
                return;
            }

            customItems.forEach(function (item) {
                container.append(crearFilaUbicacionPersonalizada(item.nombre_aplicado, item.precio_aplicado, item.cantidad, item.nombre_logo));
            });

            actualizarEstadosUbicacionesModal();
        }

        function crearFilaUbicacionPersonalizada(nombre, precio, cantidad, logoNombre) {
            return '<div class="d-flex flex-column gap-2 ubicacion-personalizada-row border rounded p-2" style="border-color:rgba(30,60,114,0.18)!important;">' +
                '  <div class="d-flex align-items-center justify-content-between">' +
                '    <small class="text-muted fw-semibold">Ubicación personalizada</small>' +
                '    <span class="badge rounded-pill bg-soft-warning text-atlantico-dark ubicacion-estado-badge">Incompleta</span>' +
                '  </div>' +
                '  <div class="d-flex align-items-center gap-2">' +
                '    <input type="text" class="form-control form-control-sm ubicacion-personalizada-nombre" placeholder="Ubicación especial..." value="' + (nombre || '') + '">' +
                '    <input type="number" class="form-control form-control-sm ubicacion-personalizada-precio" style="max-width:100px;" step="0.01" min="0" value="' + (parseFloat(precio || 0)).toFixed(2) + '">' +
                '    <input type="number" class="form-control form-control-sm ubicacion-personalizada-cantidad text-center" style="max-width:75px;" step="1" min="1" value="' + (Math.max(1, parseInt(cantidad || 1, 10))) + '">' +
                '    <button type="button" class="btn btn-sm btn-outline-danger eliminar-ubicacion-personalizada-btn"><i class="ri-delete-bin-line"></i></button>' +
                '  </div>' +
                '  <div class="input-group input-group-sm">' +
                '    <input type="text" class="form-control form-control-sm bordado-logo-input ubicacion-personalizada-logo" placeholder="Logo para esta ubicación" value="' + (logoNombre || '') + '" readonly autocomplete="off" style="background-color:#fff;cursor:default;">' +
                '    <button type="button" class="btn btn-sm btn-atlantico-brand bordado-logo-picker"><i class="ri-search-line" style="color:#fff;"></i></button>' +
                '  </div>' +
                '  <small class="text-muted ubicacion-estado-ayuda"></small>' +
                '</div>';
        }

        function aplicarEstadoVisualUbicacion($row, state, text, helpText) {
            if (!$row || !$row.length) return;

            var $badge = $row.find('.ubicacion-estado-badge').first();
            var $help = $row.find('.ubicacion-estado-ayuda').first();

            $row.removeClass('ubicacion-row-disabled ubicacion-row-pending ubicacion-row-complete');
            $badge.removeClass('bg-soft-primary bg-soft-warning bg-soft-success text-atlantico-dark');

            if (state === 'disabled') {
                $row.addClass('ubicacion-row-disabled');
                $badge.addClass('bg-soft-primary text-atlantico-dark');
            } else if (state === 'complete') {
                $row.addClass('ubicacion-row-complete');
                $badge.addClass('bg-soft-success text-atlantico-dark');
            } else {
                $row.addClass('ubicacion-row-pending');
                $badge.addClass('bg-soft-warning text-atlantico-dark');
            }

            $badge.text(text || '');
            $help.text(helpText || '');
        }

        function actualizarEstadoUbicacionStdRow($row) {
            if (!$row || !$row.length) return;

            var checked = $row.find('.ubicacion-std-check').is(':checked');
            var logo = String($row.find('.ubicacion-std-logo').val() || '').trim();

            if (!checked) {
                aplicarEstadoVisualUbicacion($row, 'disabled', 'No incluida', 'Actívala para sumar esta ubicación al bordado.');
                return;
            }

            if (!logo) {
                aplicarEstadoVisualUbicacion($row, 'pending', 'Falta logo', 'Selecciona un logo para completar esta ubicación.');
                return;
            }

            aplicarEstadoVisualUbicacion($row, 'complete', 'Completa', 'Ubicación lista para aplicar en esta prenda.');
        }

        function actualizarEstadoUbicacionPersonalizadaRow($row) {
            if (!$row || !$row.length) return;

            var nombre = String($row.find('.ubicacion-personalizada-nombre').val() || '').trim();
            var logo = String($row.find('.ubicacion-personalizada-logo').val() || '').trim();

            if (!nombre && !logo) {
                aplicarEstadoVisualUbicacion($row, 'pending', 'Sin definir', 'Completa nombre y logo para usar esta ubicación personalizada.');
                return;
            }

            if (!nombre) {
                aplicarEstadoVisualUbicacion($row, 'pending', 'Falta nombre', 'Define el nombre de la ubicación personalizada.');
                return;
            }

            if (!logo) {
                aplicarEstadoVisualUbicacion($row, 'pending', 'Falta logo', 'Selecciona el logo de esta ubicación personalizada.');
                return;
            }

            aplicarEstadoVisualUbicacion($row, 'complete', 'Completa', 'Ubicación personalizada lista para aplicar.');
        }

        function actualizarEstadosUbicacionesModal() {
            $('#ubicacionesCatalogoGrid .ubicacion-std-row').each(function () {
                actualizarEstadoUbicacionStdRow($(this));
            });

            $('#ubicacionesPersonalizadasContainer .ubicacion-personalizada-row').each(function () {
                actualizarEstadoUbicacionPersonalizadaRow($(this));
            });
        }

        function actualizarResumenRecargoModal() {
            var recargo = 0;

            $('#ubicacionesCatalogoGrid .ubicacion-std-check:checked').each(function () {
                var row = $(this).closest('.ubicacion-std-row');
                var precio = parseFloat(row.find('.ubicacion-std-precio').val()) || 0;
                var cantidad = Math.max(1, parseInt(row.find('.ubicacion-std-cantidad').val() || 1, 10));
                recargo += (precio * cantidad);
            });

            $('#ubicacionesPersonalizadasContainer .ubicacion-personalizada-row').each(function () {
                var nombre = String($(this).find('.ubicacion-personalizada-nombre').val() || '').trim();
                if (!nombre) return;
                var precio = parseFloat($(this).find('.ubicacion-personalizada-precio').val()) || 0;
                var cantidad = Math.max(1, parseInt($(this).find('.ubicacion-personalizada-cantidad').val() || 1, 10));
                recargo += (precio * cantidad);
            });

            $('#resumenRecargoBordadoModal').text(formatMoney(recargo));
        }

        function aplicarBordadosDesdeModal() {
            if (!currentBordadoCard) return;

            var bordados = [];
            var erroresLogo = [];

            $('#ubicacionesCatalogoGrid .ubicacion-std-check:checked').each(function () {
                var row = $(this).closest('.ubicacion-std-row');
                var ubicacionId = $(this).data('id');
                var nombre = $(this).data('nombre');
                var precio = parseFloat(row.find('.ubicacion-std-precio').val()) || 0;
                var cantidad = Math.max(1, parseInt(row.find('.ubicacion-std-cantidad').val() || 1, 10));
                var logo = String(row.find('.ubicacion-std-logo').val() || '').trim();

                if (!logo) {
                    erroresLogo.push('Asigna un logo para: ' + nombre);
                    return;
                }

                bordados.push({
                    ubicacion_bordado_id: ubicacionId,
                    nombre_aplicado: nombre,
                    nombre_logo: logo,
                    es_personalizada: false,
                    precio_aplicado: precio,
                    cantidad: cantidad
                });
            });

            $('#ubicacionesPersonalizadasContainer .ubicacion-personalizada-row').each(function () {
                var nombre = String($(this).find('.ubicacion-personalizada-nombre').val() || '').trim();
                if (!nombre) return;
                var precio = parseFloat($(this).find('.ubicacion-personalizada-precio').val()) || 0;
                var cantidad = Math.max(1, parseInt($(this).find('.ubicacion-personalizada-cantidad').val() || 1, 10));
                var logo = String($(this).find('.ubicacion-personalizada-logo').val() || '').trim();

                if (!logo) {
                    erroresLogo.push('Asigna un logo para ubicación personalizada: ' + nombre);
                    return;
                }

                bordados.push({
                    ubicacion_bordado_id: null,
                    nombre_aplicado: nombre,
                    nombre_logo: logo,
                    es_personalizada: true,
                    precio_aplicado: precio,
                    cantidad: cantidad
                });
            });

            if (erroresLogo.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Logo requerido',
                    text: erroresLogo[0],
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2',
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                });
                return;
            }

            setCardBordados(currentBordadoCard, bordados);
            actualizarResumenBordadosEnCard(currentBordadoCard);
            calculateCotizacionTotals();

            if (ubicacionModal) ubicacionModal.hide();
        }

        if (ubicacionModalEl) {
            ubicacionModalEl.addEventListener('shown.bs.modal', function () {
                $('#buscarUbicacionModal').val('').trigger('focus');

                if (!ubicacionesBordadoArray.length) {
                    $('#ubicacionesCatalogoGrid').html('<div class="text-center text-muted py-4"><small>Cargando ubicaciones...</small></div>');
                    cargarUbicacionesBordado(function () {
                        renderizarUbicacionesPersonalizadasModal();
                        renderizarUbicacionesModal('');
                    });
                    return;
                }

                renderizarUbicacionesPersonalizadasModal();
                renderizarUbicacionesModal('');
            });

            ubicacionModalEl.addEventListener('hidden.bs.modal', function () {
                if ($('#showModal').hasClass('show')) {
                    $('body').addClass('modal-open');
                    var backdrops = $('.modal-backdrop');
                    if (backdrops.length > 1) {
                        backdrops.not(backdrops.first()).remove();
                    }
                }
            });
        }

        $('#buscarUbicacionModal').on('keyup', function () {
            renderizarUbicacionesModal($(this).val());
        });

        $(document).on('change', '.ubicacion-std-check', function () {
            var row = $(this).closest('.ubicacion-std-row');
            var enabled = $(this).is(':checked');
            row.find('.ubicacion-std-logo, .bordado-logo-picker').prop('disabled', !enabled);
            if (!enabled) {
                row.find('.ubicacion-std-logo').val('');
            }
            actualizarEstadoUbicacionStdRow(row);
            actualizarResumenRecargoModal();
        });

        $('#productos-container').on('click', '.configurar-bordados-trigger', function (e) {
            e.preventDefault();
            currentBordadoCard = $(this).closest('.product-item');
            if (ubicacionModal) ubicacionModal.show();
        });

        $('#productos-container').on('click', '.bordados-summary-input', function () {
            currentBordadoCard = $(this).closest('.product-item');
            if (ubicacionModal) ubicacionModal.show();
        });

        $(document).on('input change', '.ubicacion-std-check, .ubicacion-std-precio, .ubicacion-std-cantidad, .ubicacion-personalizada-precio, .ubicacion-personalizada-cantidad, .ubicacion-personalizada-nombre', function () {
            var row = $(this).closest('.ubicacion-std-row, .ubicacion-personalizada-row');
            if (row.hasClass('ubicacion-std-row')) {
                actualizarEstadoUbicacionStdRow(row);
            } else if (row.hasClass('ubicacion-personalizada-row')) {
                actualizarEstadoUbicacionPersonalizadaRow(row);
            }
            actualizarResumenRecargoModal();
        });

        $('#agregarUbicacionPersonalizadaBtn').on('click', function () {
            var container = $('#ubicacionesPersonalizadasContainer');
            if (container.find('small.text-muted').length) container.empty();
            container.append(crearFilaUbicacionPersonalizada('', 0, 1, ''));
            actualizarEstadosUbicacionesModal();
        });

        $(document).on('click', '.eliminar-ubicacion-personalizada-btn', function () {
            $(this).closest('.ubicacion-personalizada-row').remove();
            if (!$('#ubicacionesPersonalizadasContainer .ubicacion-personalizada-row').length) {
                $('#ubicacionesPersonalizadasContainer').html('<small class="text-muted">No hay ubicaciones personalizadas.</small>');
            }
            actualizarResumenRecargoModal();
        });

        $('#aplicarUbicacionesBordadoBtn').on('click', function () {
            aplicarBordadosDesdeModal();
        });

        // ============================================================
        // === FIN LÓGICA MODAL DE UBICACIÓN DE BORDADO ===
        // ============================================================

        function addProductItem(productoId = '', cantidad = '', precioUnitario = '', descripcion = '', llevaBordado = false, nombreLogo = '', color = '', talla = '', bordados = []) {
            var productoDisplay = 'Clic para buscar producto...';
            var textClass = 'text-muted';
            var cardVariant = productItemIndex % 2;
            var cardBorderColor = cardVariant === 0 ? 'var(--atlantico-cyan)' : 'var(--atlantico-dark-blue)';
            var cardHeaderBg = cardVariant === 0 ? '#f0f4f8' : '#edf2f9';
            var tallaLabel = getTallaLabel(talla);
            var llevaBordadoActivo = (llevaBordado === true || llevaBordado === 1 || llevaBordado === '1');

            if (productoId) {
                var p = products.find(prod => prod.id == productoId);
                if (p) {
                    var tipoNombre = p.tipo_producto ? p.tipo_producto.nombre : 'Sin tipo';
                    productoDisplay = (p.codigo || '') + ' - ' + tipoNombre + ' ' + p.modelo;
                    precioUnitario = precioUnitario || p.precio_base;
                    textClass = 'text-dark fw-semibold';
                }
            }

            var itemHtml = `
            <div class="card border-0 shadow-sm mb-3 product-item" data-product-index="${productItemIndex}"
                style="border-left: 3px solid ${cardBorderColor} !important; overflow: hidden;">
                <!-- Mini Header Bar -->
                <div class="d-flex align-items-center justify-content-between px-3 py-2"
                    style="background: ${cardHeaderBg}; border-bottom: 1px solid #e9ecef;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="d-flex align-items-center justify-content-center flex-shrink-0 fw-bold product-badge"
                            title="Producto #${productItemIndex + 1}"
                            style="min-width:22px;height:20px;padding:0 6px;background:var(--atlantico-dark-blue);color:#fff;font-size:0.65rem;border-radius:5px;border:1px solid var(--atlantico-cyan);">
                            ${productItemIndex + 1}
                        </span>
                        <span class="product-label" style="font-size:0.75rem;color:#5a6a85;font-weight:500;">Producto</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-producto-item p-0 d-flex align-items-center justify-content-center"
                        title="Eliminar producto" style="width:24px;height:24px;border-radius:6px;font-size:0.7rem;">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <!-- Card Body -->
                <div class="card-body p-3">

                    <!-- Fila 1: Buscador de producto -->
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div class="input-group input-group-sm flex-grow-1">
                            <input type="text"
                                class="form-control form-control-sm producto-text-display"
                                value="${productoId ? productoDisplay : ''}"
                                placeholder="Clic para buscar producto..."
                                readonly autocomplete="off"
                                style="background-color: #fff;" />
                            <button type="button"
                                class="btn btn-sm btn-atlantico-brand producto-selector-trigger"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Buscar">
                                <i class="ri-search-line" style="color:#fff;"></i>
                            </button>
                        </div>
                        <input type="hidden" name="productos[${productItemIndex}][producto_id]" class="producto-id-input" value="${productoId}" required />
                    </div>

                    <!-- Fila 2: Color + Talla + Cantidad + Precio Unitario -->
                    <div class="row g-2 mb-2">
                        <div class="col-6 col-md-4">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;"><i class="ri-palette-line me-1"></i>Color</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text color-dot-display"
                                    style="background: #fff; padding: 0 6px;">
                                    <span class="color-dot-indicator"
                                        style="background-color: ${color ? '#ccc' : 'transparent'}; ${!color ? 'border:1.5px dashed #ccc;' : ''}"></span>
                                </span>
                                <input type="text" name="productos[${productItemIndex}][color]"
                                    class="form-control form-control-sm color-input"
                                    placeholder="Seleccionar color..." value="${color}" required
                                    readonly autocomplete="off"
                                    style="background-color: #fff !important;" />
                                <button type="button"
                                    class="btn btn-sm btn-atlantico-brand buscar-color-trigger"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Catálogo">
                                    <i class="ri-palette-line" style="color:#fff;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;"><i class="ri-t-shirt-line me-1"></i>Talla</label>
                            <div class="input-group input-group-sm">
                                <input type="text"
                                    class="form-control form-control-sm text-center talla-input-display"
                                    value="${tallaLabel}"
                                    placeholder="Seleccionar..."
                                    required readonly autocomplete="off"
                                    style="background-color: #fff !important; cursor:text;" />
                                <input type="hidden" name="productos[${productItemIndex}][talla]"
                                    class="talla-input-value" value="${talla}" />
                                <button type="button"
                                    class="btn btn-sm btn-atlantico-brand buscar-talla-trigger px-2"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Tallas" aria-label="Abrir catálogo de tallas">
                                    <i class="ri-t-shirt-line" style="color:#fff;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;">Cant.</label>
                            <input type="number" name="productos[${productItemIndex}][cantidad]"
                                class="form-control form-control-sm text-center cantidad-input"
                                placeholder="0" min="1" value="${cantidad}" required />
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;">Precio Base ($)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text text-success"
                                    style="background: rgba(46,204,113,0.1); border-color: #2ecc71;">$</span>
                                <input type="number" name="productos[${productItemIndex}][precio_unitario]"
                                    class="form-control precio-unitario-input"
                                    placeholder="0.00" step="0.01" min="0" value="${precioUnitario}" required
                                    style="border-color: #2ecc71;" />
                            </div>
                        </div>
                    </div>

                    <!-- Fila 3: Notas + Servicio de Bordado -->
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;"><i class="ri-file-text-line me-1"></i>Notas del producto</label>
                            <textarea name="productos[${productItemIndex}][descripcion]"
                                class="form-control form-control-sm"
                                placeholder="Notas u observaciones (opcional)"
                                rows="1" style="resize: none;">${descripcion}</textarea>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="w-100">
                                <label class="form-label mb-1 small fw-medium" style="color:#495057;"><i class="ri-scissors-cut-line me-1"></i>Servicio</label>
                                <input type="hidden" name="productos[${productItemIndex}][lleva_bordado]"
                                    class="lleva-bordado-value" value="${llevaBordadoActivo ? 1 : 0}">
                                <div class="form-check d-flex align-items-center mb-0 mt-1">
                                    <input class="form-check-input lleva-bordado-checkbox" type="checkbox"
                                        id="lleva-bordado-${productItemIndex}"
                                        ${llevaBordadoActivo ? 'checked' : ''}
                                        style="width:1.05rem;height:1.05rem;cursor:pointer; margin-top:0;">
                                    <label class="form-check-label ms-2 small fw-semibold mb-0 bordado-label ${llevaBordadoActivo ? 'active' : ''}" for="lleva-bordado-${productItemIndex}"
                                        style="cursor:pointer; user-select:none;">
                                        Servicio de Bordado
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenedor Servicio de Bordado (condicional) -->
                    <div class="row g-2 mt-1 nombre-logo-container" style="display: ${llevaBordadoActivo ? 'flex' : 'none'}">
                        <div class="col-12">
                            <label class="form-label mb-1 small fw-medium" style="color:#495057;"><i class="ri-map-pin-line me-1"></i>Configuración del servicio</label>
                            <div class="input-group input-group-sm">
                                <input type="text"
                                    class="form-control form-control-sm ubicacion-logo-input bordados-summary-input"
                                    placeholder="Configurar servicio de bordado completo..."
                                    value=""
                                    readonly autocomplete="off"
                                    style="background-color: #fff !important;" />
                                <button type="button"
                                    class="btn btn-sm btn-atlantico-brand configurar-bordados-trigger"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Configurar servicio">
                                    <i class="ri-settings-3-line" style="color:#fff;"></i>
                                </button>
                            </div>
                            <input type="hidden" name="productos[${productItemIndex}][nombre_logo]" class="nombre-logo-legacy-input" value="${nombreLogo || ''}" />
                            <div class="bordados-hidden-fields"></div>
                            <small class="text-muted">Define logos, ubicaciones, cantidades y tarifas del servicio de bordado.</small>
                            <div class="bordado-resumen-box p-2 mt-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="bordado-resumen-title">Recargo unitario por bordado</span>
                                    <span class="badge bg-soft-info text-atlantico-dark bordado-recargo-value">$0.00</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <span class="bordado-resumen-title">Precio final unitario</span>
                                    <span class="bordado-resumen-value bordado-final-value">$0.00</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <span class="bordado-resumen-title">Total de la línea</span>
                                    <span class="bordado-resumen-value bordado-linea-total-value">$0.00</span>
                                </div>
                                <div class="text-muted mt-1 bordado-resumen-estado d-none"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            `;
            $('#productos-container').append(itemHtml);
            // Inicializar tooltips de Bootstrap en la fila recién insertada
            $('#productos-container .card').last().find('[data-bs-toggle="tooltip"]').each(function () {
                new bootstrap.Tooltip(this, { trigger: 'hover' });
            });

            var $newCard = $('#productos-container .card').last();
            setCardBordados($newCard, Array.isArray(bordados) ? bordados : []);
            $newCard.find('.bordados-summary-input').val('Sin configuración de bordado');
            actualizarResumenBordadosEnCard($newCard);

            productItemIndex++;
            reindexProductItems(); // Re-secuenciar tras agregar
        }

        // ══════════════════════════════════════════════════════════════════════
        // Re-indexar filas de productos tras agregar/eliminar
        // ══════════════════════════════════════════════════════════════════════
        // Itera TODOS los .product-item en orden DOM y re-calcula:
        //   1. Badge visual (el círculo con el número)
        //   2. data-product-index en la card
        //   3. name="productos[X][campo]" en TODOS los inputs, selects y textareas
        //   4. Sincronización del contador global
        //
        // ¿Por qué es seguro?
        //   - Solo se mutan ATRIBUTOS HTML (name, id, for, data-*, title, textContent)
        //   - NUNCA se reemplaza/recrea un nodo DOM, por lo tanto el .value que el
        //     usuario ya escribió permanece intacto (jQuery .val() lee .value, no el
        //     atributo HTML "value").
        // ══════════════════════════════════════════════════════════════════════
        function reindexProductItems() {
            $('#productos-container .product-item').each(function (i) {
                var $card = $(this);
                var cardVariant = i % 2;
                var cardBorderColor = cardVariant === 0 ? 'var(--atlantico-cyan)' : 'var(--atlantico-dark-blue)';
                var cardHeaderBg = cardVariant === 0 ? '#f0f4f8' : '#edf2f9';

                // 1. Actualizar data-product-index
                $card.attr('data-product-index', i);
                $card.css('border-left', '3px solid ' + cardBorderColor);
                $card.find('> .d-flex').first().css('background', cardHeaderBg);

                // 2. Actualizar badge visual y label en el header bar
                var $badge = $card.find('.product-badge').first();
                $badge.text(i + 1);
                $badge.attr('title', 'Producto #' + (i + 1));
                $card.find('.product-label').first().text('Producto');

                // 3. Re-numerar TODOS los name="productos[X][campo]"
                $card.find('input, select, textarea').each(function () {
                    var name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/productos\[\d+\]/, 'productos[' + i + ']'));
                    }
                });

                var $checkbox = $card.find('.lleva-bordado-checkbox').first();
                if ($checkbox.length) {
                    var newId = 'lleva-bordado-' + i;
                    $checkbox.attr('id', newId);
                    $card.find('label[for^="lleva-bordado-"]').first().attr('for', newId);
                }

            });

            // Sincronizar el contador global con la cantidad real de filas
            productItemIndex = $('#productos-container .product-item').length;
        }

        // Evento para remover producto
        $('#productos-container').on('click', '.remove-producto-item', function () {
            $(this).closest('.card').remove();
            reindexProductItems();        // Re-secuenciar tras eliminar
            calculateCotizacionTotals();  // Recalcular totales
        });

        // Mostrar/ocultar bloque de logo usando check moderno (0/1)
        $('#productos-container').on('change', '.lleva-bordado-checkbox', function () {
            var $checkbox = $(this);
            var $card = $checkbox.closest('.product-item');
            var selectedValue = $checkbox.is(':checked') ? 1 : 0;
            $card.find('.bordado-label').toggleClass('active', selectedValue === 1);

            $card.find('.lleva-bordado-value').val(selectedValue).trigger('change');

            var container = $card.find('.nombre-logo-container');
            if (selectedValue === 1) {
                container.show();
            } else {
                container.hide();
                container.find('.nombre-logo-legacy-input').val('');
                setCardBordados($card, []);
                actualizarResumenBordadosEnCard($card);
                $card.find('.lleva-bordado-value').val(0);
            }

            calculateCotizacionTotals();
        });

        // Calcular el total de la cotización y el restante
        function calculateCotizacionTotals() {
            let sum = 0;
            $('#productos-container .product-item').each(function () {
                var $card = $(this);
                let quantity = parseFloat($card.find('.cantidad-input').val()) || 0;
                let basePrice = parseFloat($card.find('.precio-unitario-input').val()) || 0;
                let llevaBordado = parseInt($card.find('.lleva-bordado-value').val() || 0, 10) === 1;
                let recargoBordado = llevaBordado ? calcularRecargoUnitarioBordadoDesdeLista(getCardBordados($card)) : 0;
                let finalUnitPrice = basePrice + recargoBordado;

                actualizarPanelResumenMonetario($card, recargoBordado, finalUnitPrice, llevaBordado);
                sum += (quantity * finalUnitPrice);
            });
            $('#total-display-field').val(sum.toFixed(2));
            $('#total-display-value').text(formatMoney(sum));
            updateCotizacionRemaining();
        }
        function updateCotizacionRemaining() {
            let abono = parseFloat($('#abono-field').val()) || 0;
            let total = parseFloat($('#total-display-field').val()) || 0;
            let restante = total - abono;
            $('#restante-display-field').val(restante.toFixed(2));
        }
        // Recalcular total cuando cambia la cantidad o el precio negociado
        $('#productos-container').on('change keyup', '.cantidad-input, .precio-unitario-input', calculateCotizacionTotals);
        $('#productos-container').on('change', '.product-select', function () {
            var selectedOption = $(this).find('option:selected');
            var precio = selectedOption.data('precio');
            var spanPrecio = $(this).closest('.product-item').find('.precio-producto-span');

            $(this).closest('.card').find('.precio-unitario-input').val(precio);

            if (precio) {
                spanPrecio.text(formatMoney(parseFloat(precio)));
            } else {
                spanPrecio.text('');
            }

            calculateCotizacionTotals();
        });
        $('#abono-field').on('change keyup', updateCotizacionRemaining);
        // Inicializar con un producto vacío al abrir el modal de creación
        $('#create-btn').on('click', function () {
            $('#modalTitle').text('Agregar Cotización');
            $('#cotizacionForm')[0].reset();
            $('#id-field').val('');
            $('#cliente-id-field').val('').prop('disabled', false).removeClass('campo-protegido');
            $('#fecha-cotizacion-field').val('').prop('readonly', false).removeClass('campo-protegido');
            $('#add-btn').show();
            $('#edit-btn').hide();
            $('#estado-field-wrapper').hide();
            $('#productos-container').empty();
            addProductItem();
            calculateCotizacionTotals();
        });



        // Envío del formulario de cotización (crear/editar)
        $('#cotizacionForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            var id = $('#id-field').val();
            var url = id ? '/cotizaciones/' + id : '/cotizaciones';

            const clienteId = $('#cliente-id-field').val();
            if (!formData.get('cliente_id') && clienteId) {
                formData.set('cliente_id', clienteId);
            }

            if (id) {
                formData.append('_method', 'PUT');
            }
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: response.success,
                        icon: 'success',
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs me-2',
                            cancelButton: 'btn btn-danger w-xs'
                        },
                        buttonsStyling: false,
                        showCloseButton: true,
                        timer: 1500
                    })
                    $('#showModal').modal('hide');
                    table.ajax.reload();
                },
                error: function (xhr) {
                    var errorMessage = '';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else {
                        errorMessage = 'Ocurrió un error inesperado.';
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs me-2',
                            cancelButton: 'btn btn-danger w-xs'
                        },
                        buttonsStyling: false,
                        showCloseButton: true
                    })
                }
            });
        });

        // Botón de editar
        $('#cotizaciones-table').on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            $('#modalTitle').text('Editar Cotización');
            $('#id-field').val(id);
            $('#add-btn').hide();
            $('#edit-btn').show();
            $('#estado-field-wrapper').show();
            $('#productos-container').empty();
            $.ajax({
                url: '/cotizaciones/' + id,
                method: 'GET',
                success: function (data) {
                    $('#cliente-id-field').val(data.cliente_id || '').prop('disabled', false).addClass('campo-protegido');
                    // Obtener datos del cliente desde la relación
                    if (data.cliente) {
                        $('#cliente-nombre-field').val(data.cliente.nombre);
                        $('#cliente-apellido-field').val(data.cliente.apellido || '');
                        $('#cliente-email-field').val(data.cliente.email || '');
                        $('#cliente-telefono-field').val(data.cliente.telefono || '');
                        var documento = data.cliente.documento || '';
                        if (documento) {
                            var prefix = documento.substring(0, 2);
                            var number = documento.substring(2);
                            $('#ci-rif-prefix-field').val(prefix);
                            $('#ci-rif-number-field').val(number);
                            $('#ci-rif-full-field').val(documento);
                        }
                    }
                    // Formatear fechas para input date (YYYY-MM-DD)
                    var fechaCotizacion = data.fecha_cotizacion ? data.fecha_cotizacion.split('T')[0] : '';
                    var fechaValidez = data.fecha_validez ? data.fecha_validez.split('T')[0] : '';

                    $('#fecha-cotizacion-field').val(fechaCotizacion).prop('readonly', true).addClass('campo-protegido');
                    $('#fecha-validez-field').val(fechaValidez);
                    $('#estado-field').val(data.estado);
                    $('#notas-field').val(data.notas || '');
                    // Cargar productos existentes
                    $('#productos-container').empty();
                    if (data.productos && data.productos.length > 0) {
                        productItemIndex = 0;
                        data.productos.forEach(function (detalle) {
                            var recargoUnitario = (detalle.bordados || []).reduce(function (acc, bordado) {
                                var precio = parseFloat(bordado.precio_aplicado || 0);
                                var cantidad = Math.max(1, parseInt(bordado.cantidad || 1, 10));
                                return acc + (precio * cantidad);
                            }, 0);
                            var precioBase = Math.max(0, (parseFloat(detalle.precio_unitario || 0) - recargoUnitario));

                            addProductItem(
                                detalle.producto_id,
                                detalle.cantidad,
                                precioBase,
                                detalle.descripcion,
                                detalle.lleva_bordado,
                                detalle.nombre_logo,
                                detalle.color || '',
                                detalle.talla,
                                detalle.bordados || []
                            );
                        });
                    }

                    // Esperar un momento para que todos los elementos se rendericen
                    setTimeout(function () {
                        calculateCotizacionTotals();

                        // Intentar inicializar Select2, pero no detener la ejecución si falla
                        try {
                            // Destruir y reinicializar Select2 en todos los selectores de productos
                            $('.product-select').each(function () {
                                if ($(this).hasClass("select2-hidden-accessible")) {
                                    $(this).select2('destroy');
                                }
                                $(this).select2({
                                    theme: 'bootstrap-5',
                                    placeholder: '🔍 Buscar producto...',
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#showModal'),
                                    language: {
                                        noResults: function () {
                                            return 'No se encontraron productos';
                                        },
                                        searching: function () {
                                            return 'Buscando...';
                                        }
                                    }
                                });
                            });
                        } catch (e) {
                            console.error('Error inicializando Select2:', e);
                        }

                        // Mostrar el modal siempre, independientemente de errores en Select2
                        $('#showModal').modal('show');
                    }, 200);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo cargar la cotización para editar',
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs me-2',
                            cancelButton: 'btn btn-danger w-xs'
                        },
                        buttonsStyling: false,
                        showCloseButton: true
                    });
                }
            });
        });

        // Botón de ver detalles
        $('#cotizaciones-table').on('click', '.view-btn', function () {
            var id = $(this).data('id');
            $.ajax({
                url: '/cotizaciones/' + id,
                method: 'GET',
                success: function (data) {
                    // Obtener datos del cliente desde la relación
                    if (data.cliente) {
                        // Mostrar nombre con badge si cliente fue eliminado
                        var nombreHtml = data.cliente.nombre || 'N/A';
                        if (data.cliente.eliminado) {
                            nombreHtml += ' <span class="badge bg-danger ms-1" title="Este cliente fue eliminado">Eliminado</span>';
                        }
                        $('#view-cliente-nombre').html(nombreHtml);

                        // Si cliente eliminado, mostrar datos con estilo atenuado
                        var mutedClass = data.cliente.eliminado ? 'text-muted' : '';
                        $('#view-cliente-apellido').html(data.cliente.eliminado
                            ? '<span class="text-muted">' + (data.cliente.apellido || '') + '</span>'
                            : (data.cliente.apellido || ''));
                        $('#view-cliente-email').html(data.cliente.eliminado
                            ? '<span class="text-muted">' + (data.cliente.email || 'N/A') + '</span>'
                            : (data.cliente.email || 'N/A'));
                        $('#view-cliente-telefono').html(data.cliente.eliminado
                            ? '<span class="text-muted">' + (data.cliente.telefono || 'N/A') + '</span>'
                            : (data.cliente.telefono || 'N/A'));
                        $('#view-ci-rif').html(data.cliente.eliminado
                            ? '<span class="text-muted">' + (data.cliente.documento || 'N/A') + '</span>'
                            : (data.cliente.documento || 'N/A'));
                    } else {
                        $('#view-cliente-nombre').html('<span class="text-danger">Cliente no encontrado</span>');
                        $('#view-cliente-apellido').text('');
                        $('#view-cliente-email').text('N/A');
                        $('#view-cliente-telefono').text('N/A');
                        $('#view-ci-rif').text('N/A');
                    }
                    // Función para formatear fechas YYYY-MM-DD o ISO a dd/mm/yyyy
                    function formatDate(dateStr) {
                        if (!dateStr) return 'N/A';
                        var date = new Date(dateStr);
                        if (isNaN(date.getTime())) return dateStr;
                        var day = String(date.getDate()).padStart(2, '0');
                        var month = String(date.getMonth() + 1).padStart(2, '0');
                        var year = date.getFullYear();
                        return day + '/' + month + '/' + year;
                    }
                    $('#view-fecha-cotizacion').text(formatDate(data.fecha_cotizacion));
                    $('#view-fecha-validez').text(formatDate(data.fecha_validez));
                    // Mostrar estado con diseño unificado (usando clases CSS globales)
                    var estadoClasses = {
                        'Pendiente': 'status-pendiente',
                        'Aprobada': 'status-aprobada',
                        'Convertida': 'badge-soft-info',
                        'Cancelado': 'status-cancelado',
                        'Vencida': 'status-cancelado'
                    };
                    var estadoIcons = {
                        'Pendiente': 'ri-time-line',
                        'Aprobada': 'ri-check-double-line',
                        'Convertida': 'ri-exchange-line',
                        'Cancelado': 'ri-close-circle-line',
                        'Vencida': 'ri-alarm-warning-line'
                    };
                    var badgeClass = estadoClasses[data.estado] || '';
                    var icon = estadoIcons[data.estado] || 'ri-question-line';
                    $('#view-estado').html('<span class="badge badge-status ' + badgeClass + ' rounded-pill"><i class="' + icon + ' me-1"></i>' + data.estado + '</span>');
                    $('#view-usuario-creador').text(data.user ? data.user.name : '');
                    // Mostrar productos de la cotización con el mismo diseño que pedidos
                    var productosBody = $('#view-productos-container');
                    productosBody.empty();
                    if (data.productos && data.productos.length > 0) {
                        data.productos.forEach(function (item, index) {
                            var subtotal = item.cantidad * item.precio_unitario;
                            productosBody.append(`
                                <div class="card border-0 shadow-sm mb-3" style="border-left: 4px solid #00d9a5 !important;">
                                    <div class="card-body p-3">
                                        <!-- Header del Producto -->
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                style="width: 45px; height: 45px; background: #1e3c72;">
                                                <i class="ri-t-shirt-2-line text-white fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold" style="color: #1e3c72;">${item.producto ? (item.producto.nombre_completo || item.producto.modelo || 'Producto') : 'Sin producto'}</h6>
                                                <small class="text-muted">Producto #${index + 1}</small>
                                            </div>
                                            <div class="ms-auto">
                                                <span class="badge" style="background: #00d9a5; font-size: 0.9rem;">
                                                    $${subtotal.toFixed(2)}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Detalles del Producto -->
                                        <div class="row g-2 mb-3">
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                        <i class="ri-stack-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Cantidad</small>
                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${item.cantidad}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                        <i class="ri-t-shirt-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Talla</small>
                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${item.talla || 'N/A'}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                        <i class="ri-palette-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Color</small>
                                                        <span class="fw-semibold" style="font-size: 0.85rem;">${item.color || 'N/A'}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                        <i class="ri-money-dollar-circle-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">P. Unitario</small>
                                                        <span class="fw-semibold" style="font-size: 0.85rem;">$${parseFloat(item.precio_unitario).toFixed(2)}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 28px; height: 28px; background: rgba(30, 60, 114, 0.15);">
                                                        <i class="ri-scissors-cut-line" style="color: #1e3c72; font-size: 0.85rem;"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Bordado</small>
                                                        <span class="badge ${item.lleva_bordado ? 'bg-success' : 'bg-secondary'}" style="font-size: 0.75rem;">${item.lleva_bordado ? 'Sí' : 'No'}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Logos si aplica -->
                                        ${item.lleva_bordado ? `
                                        <div class="rounded p-2 mb-3" style="background: rgba(30, 60, 114, 0.08);">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ri-scissors-cut-line me-2" style="color: #1e3c72;"></i>
                                                <span class="fw-semibold" style="color: #1e3c72; font-size: 0.85rem;">Logos</span>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Aplicaciones</small>
                                                    <div class="d-flex flex-column" style="font-size: 0.85rem;">${(item.bordados && item.bordados.length)
                                                        ? item.bordados.map(function (b) {
                                                            return '<div class="pb-1 mb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);">' +
                                                                '<span class="fw-semibold">' +
                                                                    (b.nombre_logo || b.nombre_logo_aplicado || 'Logo') +
                                                                    ' → ' + (b.nombre_aplicado || 'Ubicación') +
                                                                    ' x' + (b.cantidad || 1) +
                                                                '</span>' +
                                                                '</div>';
                                                        }).join('')
                                                        : '<div class="pb-1" style="border-bottom:1px dashed rgba(30,60,114,0.2);">' +
                                                            '<span class="fw-semibold">' +
                                                                (item.nombre_logo || 'Sin logo') +
                                                                ' → ' + (item.ubicacion_logo || 'Sin ubicación') +
                                                                ' x' + (item.cantidad_logo || 1) +
                                                            '</span>' +
                                                            '</div>'
                                                    }</div>
                                                </div>
                                            </div>
                                        </div>
                                        ` : ''}
                                        
                                        <!-- Descripción -->
                                        ${item.descripcion ? `
                                        <div class="rounded p-2" style="background: rgba(30, 60, 114, 0.05);">
                                            <div class="d-flex align-items-start">
                                                <i class="ri-file-text-line me-2 mt-1" style="color: #1e3c72;"></i>
                                                <div>
                                                    <small class="text-muted d-block">Descripción</small>
                                                    <span style="font-size: 0.85rem;">${item.descripcion}</span>
                                                </div>
                                            </div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        productosBody.append('<p class="text-muted text-center py-4"><i class="ri-file-list-3-line fs-1 d-block mb-2"></i>No hay productos en esta cotización.</p>');
                    }
                    // Formatear el total
                    $('#view-total').text(formatMoney(parseFloat(data.total || 0)));
                    // Establecer enlace PDF
                    $('#view-pdf-btn').attr('href', '/cotizaciones/' + id + '/pdf');
                    $('#viewModal').modal('show');
                }
            });
        });

        // Botón de eliminar
        $('#cotizaciones-table').on('click', '.remove-btn', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2',
                    cancelButton: 'btn btn-danger w-xs'
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/cotizaciones/' + id,
                        method: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                title: '¡Eliminado!',
                                text: response.success,
                                icon: 'success',
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs me-2',
                                    cancelButton: 'btn btn-danger w-xs'
                                },
                                buttonsStyling: false,
                                showCloseButton: true,
                                timer: 1500
                            })
                            table.ajax.reload();
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'No se pudo eliminar la cotización.',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs me-2',
                                    cancelButton: 'btn btn-danger w-xs'
                                },
                                buttonsStyling: false,
                                showCloseButton: true
                            })
                        }
                    });
                }
            });
        });

        // === CAMBIO DE ESTADO VIA DROPDOWN ===
        $('#cotizaciones-table').on('click', '.change-status-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');

            Swal.fire({
                title: '¿Cambiar estado?',
                text: "El estado cambiará a: " + status,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2',
                    cancelButton: 'btn btn-danger w-xs'
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/cotizaciones/' + id + '/estado',
                        method: 'PUT',
                        data: {
                            estado: status,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                title: '¡Actualizado!',
                                text: response.success,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        },
                        error: function (xhr) {
                            var msg = 'No se pudo actualizar el estado.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                msg = xhr.responseJSON.error;
                            }
                            Swal.fire({
                                title: 'Error',
                                text: msg,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // === CONVERTIR COTIZACIÓN A PEDIDO ===
        $('#cotizaciones-table').on('click', '.convert-to-pedido-btn', function () {
            var cotizacionId = $(this).data('id');

            Swal.fire({
                title: '¿Convertir a Pedido?',
                html: '<p>Se creará un nuevo pedido con los datos de esta cotización:</p>' +
                    '<ul class="text-start"><li>Cliente</li><li>Productos</li><li>Precios</li></ul>' +
                    '<small class="text-muted">Después podrá editar el pedido para agregar fecha de entrega, abono y método de pago.</small>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="ri-exchange-line me-1"></i> Sí, convertir',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-success w-xs me-2',
                    cancelButton: 'btn btn-light w-xs'
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Convirtiendo...',
                        text: 'Creando pedido desde la cotización',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Llamar al endpoint para convertir
                    $.ajax({
                        url: '/cotizaciones/' + cotizacionId + '/convertir-a-pedido',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Pedido Creado!',
                                html: '<p>' + response.message + '</p>' +
                                    '<p class="mt-2">¿Desea ir al pedido creado para completar los datos?</p>',
                                showCancelButton: true,
                                confirmButtonText: '<i class="ri-edit-line me-1"></i> Editar Pedido',
                                cancelButtonText: 'Quedarme aquí',
                                customClass: {
                                    confirmButton: 'btn btn-primary me-2',
                                    cancelButton: 'btn btn-light'
                                },
                                buttonsStyling: false
                            }).then((result) => {
                                // Recargar la tabla para mostrar el estado actualizado
                                table.ajax.reload();

                                if (result.isConfirmed) {
                                    // Redirigir al módulo de pedidos
                                    window.location.href = '/pedidos?editar=' + response.pedido_id;
                                }
                            });
                        },
                        error: function (xhr) {
                            var errorMsg = 'No se pudo convertir la cotización.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg,
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });

        // === AUTOCOMPLETADO DE CLIENTE ===
        let clienteSeleccionado = null;
        let autocompleteTimeout = null;

        $('#ci-rif-number-field').on('input', function () {
            const query = $(this).val();
            clearTimeout(autocompleteTimeout);
            // Cambiar a 6 caracteres mínimos para búsqueda de documento
            if (query.length < 6) {
                $('#cliente-autocomplete-list').empty().hide();
                return;
            }
            autocompleteTimeout = setTimeout(function () {
                $.ajax({
                    url: '/clientes-search',
                    data: { q: query },
                    success: function (clientes) {
                        let html = '';
                        if (clientes.length > 0) {
                            clientes.forEach(function (cliente) {
                                const nombreCompleto = cliente.apellido ? `${cliente.nombre} ${cliente.apellido}` : cliente.nombre;
                                html += `<button type="button" class="list-group-item list-group-item-action cliente-autocomplete-item" data-id="${cliente.id}" data-nombre="${cliente.nombre}" data-apellido="${cliente.apellido || ''}" data-email="${cliente.email || ''}" data-telefono="${cliente.telefono || ''}" data-documento="${cliente.documento || ''}">${cliente.documento || 'Sin documento'} - ${nombreCompleto} <small class='text-muted'>${cliente.email || 'Sin email'}</small></button>`;
                            });
                        } else {
                            html = '<div class="list-group-item disabled">No se encontraron clientes</div>';
                        }
                        $('#cliente-autocomplete-list').html(html).show();
                    }
                });
            }, 300);
        });

        // Selección de cliente de la lista
        $(document).on('click', '.cliente-autocomplete-item', function () {
            const $this = $(this);
            const clienteId = $this.data('id');
            const nombre = $this.data('nombre') || '';
            const apellido = $this.data('apellido') || '';
            const email = $this.data('email') || '';
            const telefono = $this.data('telefono') || '';
            const documento = $this.data('documento');

            // Guardar cliente_id
            $('#cliente-id-field').val(clienteId);

            // Llenar campos básicos
            $('#cliente-nombre-field').val(nombre);
            $('#cliente-apellido-field').val(apellido);
            $('#cliente-email-field').val(email);
            $('#cliente-telefono-field').val(telefono);

            // Procesar documento - Convertir siempre a string primero
            let prefix = 'V-';
            let number = '';
            let docString = '';
            if (documento !== undefined && documento !== null && documento !== '') {
                docString = String(documento).trim();
            }
            if (docString.length > 0) {
                if (docString.startsWith('V-') || docString.startsWith('J-') || docString.startsWith('E-') || docString.startsWith('G-')) {
                    prefix = docString.substring(0, 2);
                    number = docString.substring(2);
                } else {
                    number = docString;
                    if (docString.length >= 8 && /^[2-9]/.test(docString)) {
                        prefix = 'J-';
                    } else {
                        prefix = 'V-';
                    }
                }
            }
            $('#ci-rif-prefix-field').val(prefix);
            $('#ci-rif-number-field').val(number);
            $('#ci-rif-full-field').val(prefix + number);
            $('#cliente-autocomplete-list').empty().hide();
            clienteSeleccionado = true;
        });

        // Ocultar lista al hacer click fuera
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#ci-rif-number-field, #cliente-autocomplete-list').length) {
                $('#cliente-autocomplete-list').empty().hide();
            }
        });

        // --- MODAL AGREGAR CLIENTE DESDE COTIZACIÓN ---

        // Validación en tiempo real para nombre (solo letras y espacios)
        $(document).on('input', '#nombre-field-cliente', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Validación en tiempo real para apellido (solo letras y espacios)
        $(document).on('input', '#apellido-field-cliente', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Validación en tiempo real para documento (solo números)
        $(document).on('input', '#documento-number-field-cliente', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Validación en tiempo real para teléfono (solo números)
        $(document).on('input', '#telefono-number-field-cliente', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);
        });

        // Validación onblur para nombre
        $(document).on('blur', '#nombre-field-cliente', function () {
            let value = $(this).val().trim();
            if (value.length < 2) {
                $(this).addClass('is-invalid');
                $('#nombre-error-cliente').text('El nombre debe tener al menos 2 caracteres.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#nombre-error-cliente').hide();
            }
        });

        // Validación onblur para apellido
        $(document).on('blur', '#apellido-field-cliente', function () {
            let value = $(this).val().trim();
            if (value.length < 2) {
                $(this).addClass('is-invalid').removeClass('is-valid');
                $('#apellido-error-cliente').text('El apellido debe tener al menos 2 caracteres.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#apellido-error-cliente').hide();
            }
        });

        // Validación onblur para documento
        $(document).on('blur', '#documento-number-field-cliente', function () {
            let value = $(this).val().trim();
            if (value.length < 6) {
                $(this).addClass('is-invalid');
                $('#documento-error-cliente').text('El documento debe tener al menos 6 dígitos.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#documento-error-cliente').hide();
            }
        });

        // Validación onblur para teléfono
        $(document).on('blur', '#telefono-number-field-cliente', function () {
            let value = $(this).val().trim();
            if (value.length < 7) {
                $(this).addClass('is-invalid');
                $('#telefono-error-cliente').text('El teléfono debe tener 7 dígitos.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#telefono-error-cliente').hide();
            }
        });

        // Validación onblur para email
        $(document).on('blur', '#email-field-cliente', function () {
            let value = $(this).val().trim();
            let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value.length > 0 && !regex.test(value)) {
                $(this).addClass('is-invalid');
                $('#email-error-cliente').text('Ingrese un email válido.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#email-error-cliente').hide();
            }
        });

        // Validación onblur para dirección (capitalizar primera letra + validar obligatorio)
        $(document).on('blur', '#direccion-field-cliente', function () {
            let value = $(this).val().trim();
            // Capitalizar primera letra
            if (value.length > 0) {
                $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
            }
            // Validar mínimo 5 caracteres
            if (value.length < 5) {
                $(this).addClass('is-invalid').removeClass('is-valid');
                $('#direccion-error-cliente').text('La dirección debe tener al menos 5 caracteres.').show();
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $('#direccion-error-cliente').hide();
            }
        });

        // Listener para actualizar label del checkbox de estatus
        $(document).on('change', '#estatus-field-cliente', function () {
            if ($(this).is(':checked')) {
                $('#estatus-label-cliente').text('Activo');
            } else {
                $('#estatus-label-cliente').text('Inactivo');
            }
        });

        // Dropdown dependiente: Poblar municipios cuando cambia el estado
        $(document).on('change', '#estado_territorial-field-cliente', function () {
            const estado = $(this).val();
            const municipios = getMunicipios(estado);
            const ciudadSelect = $('#ciudad-field-cliente');

            // Limpiar opciones anteriores
            ciudadSelect.empty();

            if (estado === '') {
                ciudadSelect.append('<option value="">Primero seleccione un estado</option>');
            } else {
                ciudadSelect.append('<option value="">Seleccione municipio</option>');
                municipios.forEach(function (municipio) {
                    ciudadSelect.append('<option value="' + municipio + '">' + municipio + '</option>');
                });
            }
        });

        // Limpiar validaciones al abrir modal
        $('#modalAddCliente').on('show.bs.modal', function () {
            $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            $('.invalid-feedback').hide();
        });

        // Abrir modal de agregar cliente
        $('#open-add-cliente-modal').on('click', function () {
            $('#clienteFormCotizacion')[0].reset();
            $('#id-field-cliente').val('');
            $('#modalClienteTitle').text('Agregar Cliente');
            $('#add-btn-cliente').show();
            $('#edit-btn-cliente').hide();
            // Reset valores por defecto
            $('#documento-prefix-field-cliente').val('V-');
            $('#telefono-prefix-field-cliente').val('0424');
            $('#estatus-field-cliente').prop('checked', true);
            $('#estatus-label-cliente').text('Activo');
            $('#ciudad-field-cliente').html('<option value="">Primero seleccione un estado</option>');
            $('#modalAddCliente').modal('show');
        });

        // Submit del formulario de cliente
        $('#add-btn-cliente').off('click').on('click', function (e) {
            e.preventDefault();

            // Concatenar documento completo
            var documentoCompleto = $('#documento-prefix-field-cliente').val() + $('#documento-number-field-cliente').val();
            $('#documento-field-cliente').val(documentoCompleto);

            // Concatenar teléfono completo
            var telefonoCompleto = $('#telefono-prefix-field-cliente').val() + '-' + $('#telefono-number-field-cliente').val();
            $('#telefono-field-cliente').val(telefonoCompleto);

            // Validar campo apellido explícitamente
            var apellido = $('#apellido-field-cliente').val().trim();
            if (apellido.length < 2) {
                $('#apellido-field-cliente').addClass('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'El campo Apellido es obligatorio (mínimo 2 caracteres)'
                });
                return;
            }
            $('#apellido-field-cliente').removeClass('is-invalid');

            // Validar campo dirección explícitamente
            var direccion = $('#direccion-field-cliente').val().trim();
            if (!direccion || direccion.length < 5) {
                $('#direccion-field-cliente').addClass('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'El campo Dirección es obligatorio (mínimo 5 caracteres)'
                });
                return;
            }
            $('#direccion-field-cliente').removeClass('is-invalid');

            // Validar formulario antes de enviar
            var form = document.getElementById('clienteFormCotizacion');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Deshabilitar botón para evitar múltiples envíos
            $(this).prop('disabled', true);

            // Enviar por AJAX
            var formData = $('#clienteFormCotizacion').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/clientes',
                type: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Cliente creado exitosamente',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    $('#modalAddCliente').modal('hide');

                    // Obtener el cliente_id del response
                    const clienteId = response.cliente_id;

                    // Rellenar los campos de la cotización con el nuevo cliente
                    const nombre = $('#nombre-field-cliente').val();
                    const apellido = $('#apellido-field-cliente').val();
                    const email = $('#email-field-cliente').val();
                    const telefono = telefonoCompleto;
                    const documento = documentoCompleto;

                    // Actualizar campo cliente_id en formulario de cotización
                    $('#cliente-id-field').val(clienteId);

                    // Rellenar campos visibles
                    $('#cliente-nombre-field').val(nombre);
                    $('#cliente-apellido-field').val(apellido || '');
                    $('#cliente-email-field').val(email || '');
                    $('#cliente-telefono-field').val(telefono || '');

                    // Separar prefijo y número del documento
                    let prefix = 'V-';
                    let number = '';
                    if (documento) {
                        if (documento.startsWith('V-') || documento.startsWith('J-') || documento.startsWith('E-') || documento.startsWith('G-')) {
                            prefix = documento.substring(0, 2);
                            number = documento.substring(2);
                        } else {
                            number = documento;
                            if (documento.length >= 8 && ['2', '3', '4', '5', '6', '7', '8', '9'].includes(documento.charAt(0))) {
                                prefix = 'J-';
                            } else {
                                prefix = 'V-';
                            }
                        }
                    }
                    $('#ci-rif-prefix-field').val(prefix);
                    $('#ci-rif-number-field').val(number);
                    $('#ci-rif-full-field').val(prefix + number);

                    // Re-habilitar botón
                    $('#add-btn-cliente').prop('disabled', false);
                },
                error: function (xhr) {
                    var errorMessage = '';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        for (var key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else {
                        errorMessage = 'Ocurrió un error al crear el cliente.';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });

                    // Re-habilitar botón
                    $('#add-btn-cliente').prop('disabled', false);
                }
            });
        });
    });
</script>