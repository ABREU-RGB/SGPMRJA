<script>
    // Validación onblur: fecha_fin_estimada debe ser posterior a fecha_inicio
    $(document).on('blur', '#fecha-fin-estimada-field', function () {
        let finVal = $(this).val();
        let inicioVal = $('#fecha-inicio-field').val();
        if (finVal && inicioVal) {
            if (finVal <= inicioVal) {
                marcarInvalido($(this), 'La fecha fin estimada debe ser posterior a la fecha de inicio.');
            } else {
                marcarValido($(this));
            }
        } else if (finVal) {
            marcarValido($(this));
        }
    });

    // Validación onblur: fecha_inicio — obligatoria
    $(document).on('blur', '#fecha-inicio-field', function () {
        if (!$(this).val()) {
            marcarInvalido($(this), 'La fecha de inicio es obligatoria.');
        } else {
            marcarValido($(this));
        }
        let $fin = $('#fecha-fin-estimada-field');
        if ($fin.val()) { $fin.trigger('blur'); }
    });

    // Validación onblur: costo_estimado — mayor a cero
    $(document).on('blur', '#costo-estimado-field', function () {
        let val = parseFloat($(this).val());
        if ($(this).val() === '' || isNaN(val)) {
            marcarInvalido($(this), 'El costo estimado es obligatorio.');
        } else if (val <= 0) {
            marcarInvalido($(this), 'El costo estimado debe ser mayor a cero.');
        } else {
            marcarValido($(this));
        }
    });

    // Validación al cerrar Select2 — insumo obligatorio
    $(document).on('select2:close', '.insumo-select', function () {
        if (!$(this).val()) {
            marcarInvalido($(this), 'Seleccione un insumo.');
        } else {
            marcarValido($(this));
        }
    });

    // Validación onblur: cantidad_estimada por fila — mayor a 0
    $(document).on('blur', 'input[name*="[cantidad_estimada]"]', function () {
        let val = parseFloat($(this).val());
        if (isNaN(val) || val <= 0) {
            marcarInvalido($(this), 'La cantidad debe ser mayor a cero.');
        } else {
            marcarValido($(this));
        }
    });

    // Validación onblur: avanceModal — cantidad_producida (máx = piezas restantes)
    $(document).on('blur', '#am-cantidad-producida', function () {
        let producida = parseFloat($(this).val());
        let restante  = parseInt($('#am-restante').val()) || 0;
        if (isNaN(producida) || producida < 1) {
            marcarInvalido($(this), 'La cantidad producida debe ser al menos 1.');
        } else if (restante > 0 && producida > restante) {
            marcarInvalido($(this), 'No puede superar las ' + restante + ' piezas restantes de la orden.');
        } else {
            marcarValido($(this));
        }
        if ($('#am-cantidad-defectuosa').val() !== '') {
            $('#am-cantidad-defectuosa').trigger('blur');
        }
    });

    // Validación onblur: avanceModal — cantidad_defectuosa ≤ cantidad_producida
    $(document).on('blur', '#am-cantidad-defectuosa', function () {
        let defectuosa = parseFloat($(this).val());
        let producida  = parseFloat($('#am-cantidad-producida').val());
        if (isNaN(defectuosa) || defectuosa < 0) {
            marcarInvalido($(this), 'La cantidad defectuosa no puede ser negativa.');
        } else if (!isNaN(producida) && defectuosa > producida) {
            marcarInvalido($(this), 'La cantidad defectuosa no puede superar la cantidad producida (' + producida + ').');
        } else {
            marcarValido($(this));
        }
    });

    $(document).ready(function () {
        // ══════════════════════════════════════════════════════
        // Helpers
        // ══════════════════════════════════════════════════════
        function initializeSelect2(selector) {
            $(selector).select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione insumo...',
                width: '100%',
                dropdownParent: $('#showModal')
            });
        }
        initializeSelect2('.insumo-select');

        function fmtMoneda(n) {
            return Number(n || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        const hoyISO = () => new Date().toISOString().split('T')[0];
        const formatDateForInput = (dateString) => {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        };

        // Plantilla de fila de insumo vacía
        function insumoRowHtml(index) {
            return `
                <div class="row insumo-row mt-2">
                    <div class="col-md-6">
                        <select name="insumos[${index}][id]" class="form-control insumo-select" required>
                            <option value="">Seleccione insumo...</option>
                            @foreach($insumos as $insumo)
                                <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad_medida }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="insumos[${index}][cantidad_estimada]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" required />
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-insumo"><i class="ri-delete-bin-line"></i></button>
                    </div>
                </div>`;
        }
        function resetInsumos() {
            $('#insumos-container').html(insumoRowHtml(0).replace('insumo-row mt-2', 'insumo-row'));
            initializeSelect2('.insumo-select');
        }

        // ══════════════════════════════════════════════════════
        // SELECCIÓN DE PEDIDO / LÍNEA (modal de cards)
        // ══════════════════════════════════════════════════════
        let pedidosOrdenData = [];

        $(document).on('shown.bs.modal', '#seleccionarPedidoModal', function () {
            cargarPedidosDisponibles();
        });

        function cargarPedidosDisponibles() {
            const $cont = $('#pedidos-orden-container');
            const $empty = $('#pedidos-orden-empty');
            const $loading = $('#pedidos-orden-loading');

            $cont.hide().empty();
            $empty.hide();
            $loading.show();

            $.ajax({
                url: '{{ route("ordenes.pedidos-disponibles") }}',
                method: 'GET',
                success: function (data) {
                    $loading.hide();
                    pedidosOrdenData = data || [];
                    if (!pedidosOrdenData.length) { $empty.show(); return; }
                    renderPedidosOrden(pedidosOrdenData);
                    $cont.show();
                },
                error: function () {
                    $loading.hide();
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los pedidos disponibles.' });
                }
            });
        }

        function escHtml(s) {
            return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function renderPedidosOrden(pedidos) {
            const $cont = $('#pedidos-orden-container');
            $cont.empty();

            pedidos.forEach(function (p) {
                const lineasHtml = p.lineas.map(function (l) {
                    const meta = [
                        l.cantidad + ' u',
                        l.color || 'Sin color',
                        l.talla || 'Talla única'
                    ].join(' · ');
                    const bordadoBadge = l.lleva_bordado
                        ? `<span class="badge bg-info-subtle text-info ms-1"><i class="ri-scissors-cut-line"></i> ${l.bordados_count} bordado(s)</span>`
                        : '';
                    const accion = l.orden_id
                        ? `<span class="badge bg-secondary"><i class="ri-check-line"></i> Orden #${l.orden_id}</span>`
                        : `<button type="button" class="btn btn-sm btn-success crear-orden-linea-btn"
                              data-pedido-id="${p.id}" data-detalle-id="${l.detalle_id}">
                              <i class="ri-add-line"></i> Crear orden
                           </button>`;
                    return `
                        <div class="list-group-item d-flex justify-content-between align-items-center gap-2 flex-wrap">
                            <div>
                                <div class="fw-semibold">${escHtml(l.producto_nombre)}${bordadoBadge}</div>
                                <small class="text-muted">${escHtml(meta)}</small>
                            </div>
                            <div>${accion}</div>
                        </div>`;
                }).join('');

                const card = `
                    <div class="cotizacion-card" data-pedido-id="${p.id}">
                        <div class="cotizacion-header">
                            <span class="cotizacion-numero"><i class="ri-shopping-bag-line"></i> Pedido #${p.id}</span>
                            <span class="badge ${p.lineas_pendientes > 0 ? 'bg-success-subtle text-success' : 'bg-secondary'}">
                                ${p.lineas_pendientes} de ${p.total_lineas} sin orden
                            </span>
                        </div>
                        <div class="cotizacion-info">
                            <div class="cotizacion-info-item"><i class="ri-user-line"></i><span>${escHtml(p.cliente_nombre)}</span></div>
                            <div class="cotizacion-info-item"><i class="ri-bank-card-line"></i><span>${escHtml(p.cliente_documento)}</span></div>
                            <div class="cotizacion-info-item"><i class="ri-calendar-line"></i><span>${p.fecha_pedido || 'N/A'}</span></div>
                            <div class="cotizacion-info-item"><i class="ri-bar-chart-line"></i><span>Progreso ${p.progreso}%</span></div>
                        </div>
                        <div class="list-group mt-2">${lineasHtml}</div>
                    </div>`;
                $cont.append(card);
            });
        }

        // Búsqueda
        $('#buscarPedidoOrden').on('keyup', function () {
            const term = $(this).val().toLowerCase();
            if (!term) { renderPedidosOrden(pedidosOrdenData); return; }
            const filtradas = pedidosOrdenData.filter(function (p) {
                return (p.cliente_nombre || '').toLowerCase().includes(term) ||
                    (p.cliente_documento || '').toLowerCase().includes(term) ||
                    String(p.id).includes(term);
            });
            renderPedidosOrden(filtradas);
        });

        // Click en "Crear orden" de una línea → hidratar y abrir el form
        $(document).on('click', '.crear-orden-linea-btn', function () {
            const pedidoId  = $(this).data('pedido-id');
            const detalleId = $(this).data('detalle-id');
            const pedido = pedidosOrdenData.find(p => p.id == pedidoId);
            if (!pedido) return;
            const linea = pedido.lineas.find(l => l.detalle_id == detalleId);
            if (!linea) return;

            $('#seleccionarPedidoModal').modal('hide');
            setTimeout(function () { ordenAbrirDesdeLinea(pedido, linea); }, 300);
        });

        // ══════════════════════════════════════════════════════
        // HIDRATAR FORM DESDE UNA LÍNEA (modo crear)
        // ══════════════════════════════════════════════════════
        function llenarPanelLinea(d) {
            $('#orden-linea-pedido').text('Pedido #' + d.pedido_id);
            $('#orden-linea-cliente').text(d.cliente_nombre || '');
            $('#orden-linea-producto').text(d.producto_nombre || '—');
            $('#orden-linea-cantidad').text(d.cantidad != null ? d.cantidad : 0);

            const chips = [];
            chips.push('<span><i class="ri-palette-line"></i> ' + escHtml(d.color || 'Sin color') + '</span>');
            chips.push('<span><i class="ri-ruler-line"></i> ' + escHtml(d.talla || 'Talla única') + '</span>');
            if (d.lleva_bordado) {
                chips.push('<span class="text-info"><i class="ri-scissors-cut-line"></i> ' + (d.bordados_count || 0) + ' bordado(s)</span>');
            }
            $('#orden-linea-meta').html(chips.join(''));
        }

        function ordenAbrirDesdeLinea(pedido, linea) {
            // Modo crear
            $('#id-field').val('');
            $('#modalTitle').text('Nueva Orden de Producción');
            $('#estado-container').hide();
            $('#add-btn').show();
            $('#edit-btn').hide();

            // Hidden
            $('#detalle-pedido-id-field').val(linea.detalle_id);
            $('#pedido-id-hidden-field').val(pedido.id);
            $('#producto-id-field').val(linea.producto_id);

            // Panel solo lectura
            llenarPanelLinea({
                pedido_id: pedido.id,
                cliente_nombre: pedido.cliente_nombre,
                producto_nombre: linea.producto_nombre,
                cantidad: linea.cantidad,
                color: linea.color,
                talla: linea.talla,
                lleva_bordado: linea.lleva_bordado,
                bordados_count: linea.bordados_count
            });

            // Empleado
            $('#empleado-id-field').val('');

            // Fechas sugeridas
            $('#fecha-inicio-field').val(hoyISO());
            if (pedido.fecha_entrega) {
                const fe = new Date(pedido.fecha_entrega);
                fe.setDate(fe.getDate() - 2); // 2 días antes de la entrega
                $('#fecha-fin-estimada-field').val(fe.toISOString().split('T')[0]);
            } else {
                $('#fecha-fin-estimada-field').val('');
            }

            // Costo sugerido = subtotal de la línea
            $('#costo-estimado-field').val(Number(linea.subtotal || 0).toFixed(2));

            // Insumos + notas
            resetInsumos();
            $('#notas-field').val('');

            // Limpiar validaciones
            $('#ordenForm').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');

            $('#showModal').modal('show');
        }

        // ══════════════════════════════════════════════════════
        // DataTable
        // ══════════════════════════════════════════════════════
        function debounce(func, wait) {
            let timeout;
            return function () {
                const context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function updateFilterBadge() {
            let count = 0;
            const ordenValue = $('#filter-orden').val();
            if ($('#filter-estado').val()) count++;
            if ($('#filter-fecha-desde').val()) count++;
            if ($('#filter-fecha-hasta').val()) count++;
            if (ordenValue && ordenValue !== 'recientes') count++;
            $('#active-filter-count').text(count).toggleClass('d-none', count === 0);
        }

        var table = $('#ordenes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ordenes.data') }}",
                data: function (d) {
                    d.filter_estado = $('#filter-estado').val();
                    d.filter_fecha_desde = $('#filter-fecha-desde').val();
                    d.filter_fecha_hasta = $('#filter-fecha-hasta').val();
                    d.filter_orden = $('#filter-orden').val();
                }
            },
            dom: 'rtip',
            columns: [
                { data: 'id', name: 'id', className: 'align-middle text-center', width: '7%' },
                { data: 'pedido_info', name: 'pedido.id', className: 'align-middle text-center', orderable: false, width: '9%' },
                { data: 'producto_info', name: 'producto.nombre', className: 'align-middle', orderable: false, searchable: false, width: '23%' },
                { data: 'cantidad_solicitada', name: 'cantidad_solicitada', className: 'align-middle text-center', width: '9%' },
                {
                    data: null, className: 'align-middle', width: '16%',
                    render: function (data) {
                        let porcentaje = data.cantidad_solicitada > 0
                            ? (data.cantidad_producida / data.cantidad_solicitada * 100).toFixed(2) : '0.00';
                        return `<div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: ${porcentaje}%"
                                aria-valuenow="${porcentaje}" aria-valuemin="0" aria-valuemax="100">${porcentaje}%</div>
                        </div>`;
                    }
                },
                {
                    data: 'fecha_fin_estimada', name: 'fecha_fin_estimada', className: 'align-middle', width: '12%',
                    render: function (data) {
                        let fecha = new Date(data);
                        return `<div class="text-nowrap"><div class="fw-medium">${fecha.toLocaleDateString('es-ES')}</div></div>`;
                    }
                },
                {
                    data: 'estado', className: 'align-middle text-center', width: '10%',
                    render: function (data) {
                        let clases = {
                            'Pendiente': 'status-pendiente badge-soft-warning',
                            'En Proceso': 'status-procesando badge-soft-info',
                            'Finalizado': 'status-finalizado badge-soft-success',
                            'Cancelado': 'status-cancelado badge-soft-danger'
                        };
                        let badgeClass = clases[data] || 'badge-soft-secondary';
                        return `<span class="badge badge-status ${badgeClass} rounded-pill">${data}</span>`;
                    }
                },
                {
                    data: 'id', name: 'actions', orderable: false, searchable: false,
                    className: 'align-middle text-center', width: '14%',
                    render: function (data, type, row) {
                        const estadoActivo = ['Pendiente', 'En Proceso'].includes(row.estado);
                        const avanceBtn = estadoActivo
                            ? `<button class="btn btn-sm btn-soft-warning avance-btn" data-id="${data}" title="Registrar Avance"><i class="ri-add-circle-line"></i></button>`
                            : '';
                        return `
                            <div class="d-flex gap-1 justify-content-center">
                                ${avanceBtn}
                                <button class="btn btn-sm btn-soft-info view-btn" data-id="${data}" title="Ver"><i class="ri-eye-fill"></i></button>
                                <button class="btn btn-sm btn-soft-success edit-btn" data-id="${data}" title="Editar"><i class="ri-pencil-fill"></i></button>
                                <button class="btn btn-sm btn-soft-danger remove-btn" data-id="${data}" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>
                            </div>`;
                    }
                }
            ],
            order: [],
            ordering: false,
            autoWidth: false,
            responsive: false,
            buttons: [
                { extend: 'copy',  exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } },
                { extend: 'csv',   exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } },
                { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } },
                { extend: 'pdf',   exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } },
                { extend: 'print', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] } }
            ],
            language: lenguajeData
        });

        $('#filters-collapse-body')
            .on('show.bs.collapse', function () { $('.navy-filter-header').removeClass('is-collapsed'); })
            .on('hidden.bs.collapse', function () { $('.navy-filter-header').addClass('is-collapsed'); });

        $('#custom-search-input').on('input', debounce(function () {
            table.search(this.value).draw();
        }, 300));

        $('.navy-filter-select').on('change', function () {
            table.ajax.reload();
            updateFilterBadge();
        });

        $('#btn-clear-filters').on('click', function () {
            $('#filter-estado').val('');
            $('#filter-fecha-desde').val('');
            $('#filter-fecha-hasta').val('');
            $('#filter-orden').val('recientes');
            $('.navy-filter-select').trigger('change');
            $('#custom-search-input').val('');
            table.search('').draw();
            updateFilterBadge();
        });

        updateFilterBadge();

        // ══════════════════════════════════════════════════════
        // Insumos: agregar / quitar fila
        // ══════════════════════════════════════════════════════
        $('#add-insumo-btn').click(function () {
            let index = $('.insumo-row').length;
            $('#insumos-container').append(insumoRowHtml(index));
            initializeSelect2('.insumo-select:last');
        });
        $(document).on('click', '.remove-insumo', function () {
            $(this).closest('.insumo-row').remove();
        });

        // ══════════════════════════════════════════════════════
        // VALIDACIÓN AL SUBMIT
        // ══════════════════════════════════════════════════════
        function validarFormularioOrden() {
            let esValido = true;

            // Línea del pedido seleccionada (solo en creación)
            let isEdit = $('#id-field').val() !== '';
            if (!isEdit && !$('#detalle-pedido-id-field').val()) {
                Swal.fire({ icon: 'warning', title: 'Sin pedido', text: 'Selecciona un pedido y producto antes de crear la orden.' });
                return false;
            }

            // Empleado — obligatorio
            let $emp = $('#empleado-id-field');
            if (!$emp.val()) { marcarInvalido($emp, 'Selecciona el empleado asignado.'); esValido = false; }
            else { marcarValido($emp); }

            // Fecha Inicio
            let $inicio = $('#fecha-inicio-field');
            if (!$inicio.val()) { marcarInvalido($inicio, 'La fecha de inicio es obligatoria.'); esValido = false; }
            else { marcarValido($inicio); }

            // Fecha Fin Estimada
            let $fin = $('#fecha-fin-estimada-field');
            if (!$fin.val()) { marcarInvalido($fin, 'La fecha fin estimada es obligatoria.'); esValido = false; }
            else if ($inicio.val() && $fin.val() <= $inicio.val()) { marcarInvalido($fin, 'La fecha fin estimada debe ser posterior a la fecha de inicio.'); esValido = false; }
            else { marcarValido($fin); }

            // Costo
            let $costo = $('#costo-estimado-field');
            let costoVal = parseFloat($costo.val());
            if ($costo.val() === '' || isNaN(costoVal)) { marcarInvalido($costo, 'El costo estimado es obligatorio.'); esValido = false; }
            else if (costoVal <= 0) { marcarInvalido($costo, 'El costo estimado debe ser mayor a cero.'); esValido = false; }
            else { marcarValido($costo); }

            // Insumos
            let insumoValido = true;
            $('#insumos-container .insumo-row').each(function () {
                let $select = $(this).find('.insumo-select');
                let $cantidad = $(this).find('input[name*="[cantidad_estimada]"]');
                if (!$select.val()) { marcarInvalido($select, 'Seleccione un insumo.'); insumoValido = false; }
                else { marcarValido($select); }
                let cantVal = parseFloat($cantidad.val());
                if (isNaN(cantVal) || cantVal <= 0) { marcarInvalido($cantidad, 'La cantidad debe ser mayor a cero.'); insumoValido = false; }
                else { marcarValido($cantidad); }
            });
            if (!insumoValido) esValido = false;

            return esValido;
        }

        // Crear / actualizar orden
        $('#ordenForm').on('submit', function (e) {
            e.preventDefault();
            if (!validarFormularioOrden()) return;

            let formData = new FormData(this);
            let editId = $('#id-field').val();
            let url = editId
                ? "{{ route('ordenes.update', ':id') }}".replace(':id', editId)
                : "{{ route('ordenes.store') }}";
            if (editId) { formData.append('_method', 'PUT'); }

            let $btn = $(editId ? '#edit-btn' : '#add-btn').prop('disabled', true);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $btn.prop('disabled', false);
                    $('#showModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Éxito', text: response.message, timer: 2000, showConfirmButton: false });
                },
                error: function (xhr) {
                    $btn.prop('disabled', false);
                    let msg = 'Ocurrió un error al procesar la solicitud.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(v => Array.isArray(v) ? v[0] : v).join('\n');
                        } else if (xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        });

        // ══════════════════════════════════════════════════════
        // Editar orden
        // ══════════════════════════════════════════════════════
        $(document).on('click', '.edit-btn', function () {
            let id = $(this).data('id');
            $.get("{{ route('ordenes.edit', ':id') }}".replace(':id', id), function (data) {
                $('#modalTitle').text('Editar Orden de Producción');
                $('#id-field').val(data.id);
                $('#detalle-pedido-id-field').val(data.detalle_pedido_id || '');
                $('#pedido-id-hidden-field').val(data.pedido_id || '');
                $('#producto-id-field').val(data.producto_id || '');

                const det = data.detalle_pedido || {};
                llenarPanelLinea({
                    pedido_id: data.pedido_id || '—',
                    cliente_nombre: '',
                    producto_nombre: data.producto ? data.producto.nombre : ('Producto #' + data.producto_id),
                    cantidad: data.cantidad_solicitada,
                    color: det.color ? det.color.nombre : null,
                    talla: det.talla ? (det.talla.etiqueta || det.talla.nombre) : null,
                    lleva_bordado: !!(det.bordados && det.bordados.length),
                    bordados_count: det.bordados ? det.bordados.length : 0
                });

                $('#empleado-id-field').val(data.empleado_id || '');
                $('#fecha-inicio-field').val(formatDateForInput(data.fecha_inicio));
                $('#fecha-fin-estimada-field').val(formatDateForInput(data.fecha_fin_estimada));
                $('#costo-estimado-field').val(data.costo_estimado);
                $('#estado-field').val(data.estado);
                $('#notas-field').val(data.notas);

                // Insumos
                $('#insumos-container').empty();
                (data.insumos || []).forEach((insumo, index) => {
                    $('#add-insumo-btn').click();
                    setTimeout(() => {
                        $(`select[name="insumos[${index}][id]"]`).val(insumo.id).trigger('change');
                        $(`input[name="insumos[${index}][cantidad_estimada]"]`).val(insumo.pivot.cantidad_estimada);
                    }, 100);
                });
                if (!data.insumos || !data.insumos.length) { resetInsumos(); }

                $('#estado-container').show();
                $('#add-btn').hide();
                $('#edit-btn').show();
                $('#ordenForm').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                $('#showModal').modal('show');
            });
        });

        // ══════════════════════════════════════════════════════
        // Ver orden
        // ══════════════════════════════════════════════════════
        $(document).on('click', '.view-btn', function () {
            let id = $(this).data('id');
            $.get("{{ route('ordenes.show', ':id') }}".replace(':id', id), function (data) {
                const estadoClases = {
                    'Pendiente':  'status-pendiente badge-soft-warning',
                    'En Proceso': 'status-procesando badge-soft-info',
                    'Finalizado': 'status-finalizado badge-soft-success',
                    'Cancelado':  'status-cancelado badge-soft-danger'
                };

                $('#view-producto').text(data.producto ? data.producto.nombre : 'N/A');
                $('#view-cantidad-solicitada').text(data.cantidad_solicitada);
                $('#view-cantidad-producida').text(data.cantidad_producida);

                let porcentaje = data.cantidad_solicitada > 0
                    ? (data.cantidad_producida / data.cantidad_solicitada * 100).toFixed(1) : '0.0';
                $('#view-progreso').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
                $('#view-progreso-pct').text(porcentaje);

                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                };

                $('#view-fecha-inicio').text(formatDate(data.fecha_inicio));
                $('#view-fecha-fin-estimada').text(formatDate(data.fecha_fin_estimada));
                $('#view-estado').html(`<span class="badge badge-status ${estadoClases[data.estado] || 'badge-soft-secondary'} rounded-pill">${data.estado}</span>`);
                $('#view-costo-estimado').text('$/ ' + fmtMoneda(data.costo_estimado));
                $('#view-creado-por').text(data.creado_por ? data.creado_por.name : 'Sin especificar');
                $('#view-empleado').text(
                    data.empleado && data.empleado.persona ? data.empleado.persona.nombre_completo : 'Sin asignar'
                );
                $('#view-pedido-info').text(data.pedido_id ? 'Pedido #' + data.pedido_id : 'Orden Manual');

                // Diseño / Bordado — desde la línea del pedido (los bordados se definen por producto)
                const bordados = (data.detalle_pedido && data.detalle_pedido.bordados) || [];
                let disenoHtml;
                if (!bordados.length) {
                    disenoHtml = `<span class="text-muted fst-italic">Producto sin bordado / diseño.</span>`;
                } else {
                    disenoHtml = bordados.map(function (b) {
                        const logoName = b.logo ? b.logo.name : (b.nombre_logo_aplicado || 'Logo');
                        return `<div class="mb-2 pb-2 border-bottom">
                            <div class="d-flex gap-1 mb-1"><span class="text-muted fs-11">Aplicación:</span><span class="fw-medium fs-12">${escHtml(b.nombre_aplicado || '—')}</span></div>
                            <div class="d-flex gap-1 mb-1"><span class="text-muted fs-11">Logo:</span><span class="fw-medium fs-12">${escHtml(logoName)}</span></div>
                            <div class="d-flex gap-1"><span class="text-muted fs-11">Cantidad:</span><span class="fw-medium fs-12">${b.cantidad || 1}</span></div>
                        </div>`;
                    }).join('');
                }
                $('#view-logo').html(disenoHtml);

                // Insumos
                $('#view-insumos').empty();
                (data.insumos || []).forEach(insumo => {
                    let pct = (insumo.pivot.cantidad_utilizada / insumo.pivot.cantidad_estimada * 100).toFixed(2);
                    $('#view-insumos').append(`
                        <tr>
                            <td><h6 class="fs-13 mb-0">${escHtml(insumo.nombre)}</h6></td>
                            <td class="text-center">${insumo.pivot.cantidad_estimada} ${insumo.unidad_medida}</td>
                            <td class="text-center">${insumo.pivot.cantidad_utilizada} ${insumo.unidad_medida}</td>
                            <td>
                                <div class="progress animated-progress custom-progress progress-sm">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ${pct}%"
                                        aria-valuenow="${pct}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted fs-11 mt-1 text-center">${pct}%</div>
                            </td>
                        </tr>`);
                });

                $('#view-notas').text(data.notas || 'Sin notas adicionales.');
                const formatDateTime = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };
                $('#view-created').text(formatDateTime(data.created_at));

                new bootstrap.Tab(document.getElementById('tab-detalles-btn')).show();
                $('#viewModal').modal('show');
            });
        });

        // ══════════════════════════════════════════════════════
        // Eliminar orden
        // ══════════════════════════════════════════════════════
        $(document).on('click', '.remove-btn', function () {
            let id = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                backdrop: true,
                allowOutsideClick: true,
                customClass: { confirmButton: 'btn btn-primary w-xs me-2', cancelButton: 'btn btn-danger w-xs', container: 'swal2-container' },
                buttonsStyling: false,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('ordenes.destroy', ':id') }}".replace(':id', id),
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            table.ajax.reload();
                            Swal.fire('Eliminado', response.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'Ocurrió un error', 'error');
                        }
                    });
                }
            });
        });

        // ══════════════════════════════════════════════════════
        // Avance de Producción (acumula sobre la orden)
        // ══════════════════════════════════════════════════════
        $(document).on('click', '.avance-btn', function () {
            const id = $(this).data('id');
            $.get("{{ route('ordenes.show', ':id') }}".replace(':id', id), function (data) {
                const restante = data.cantidad_solicitada - data.cantidad_producida;
                $('#am-orden-id').val(data.id);
                $('#am-restante').val(restante);
                $('#am-orden-info').text(`${data.producto ? data.producto.nombre : 'Orden'} · ${restante} piezas restantes`);
                $('#am-restante-hint').text(`(máx. ${restante})`);
                $('#am-cantidad-producida').attr('max', restante);
                $('#avanceModal').modal('show');
            });
        });

        $('#am-btn-save').on('click', function () {
            const ordenId    = $('#am-orden-id').val();
            const producida  = $('#am-cantidad-producida').val();
            const defectuosa = $('#am-cantidad-defectuosa').val();
            const restante   = parseInt($('#am-restante').val()) || 0;

            if (!producida || parseInt(producida) < 1) {
                Swal.fire({ icon: 'warning', title: 'Cantidad requerida', text: 'Ingresa una cantidad producida válida.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                return;
            }
            if (parseInt(producida) > restante) {
                Swal.fire({ icon: 'warning', title: 'Cantidad excedida', text: `Solo quedan ${restante} piezas por producir en esta orden.`, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
                return;
            }
            if (defectuosa && parseInt(defectuosa) > parseInt(producida)) {
                Swal.fire({ icon: 'warning', title: 'Defectuosos inválidos', text: 'No pueden superar la cantidad producida.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3500 });
                return;
            }

            $.ajax({
                url: "{{ route('ordenes.avance', ':id') }}".replace(':id', ordenId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    cantidad_producida: producida,
                    cantidad_defectuosa: defectuosa || 0
                },
                success: function () {
                    $('#avanceModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Avance registrado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'No se pudo guardar el avance.' });
                }
            });
        });

        $('#avanceModal').on('hidden.bs.modal', function () {
            $('#am-orden-id').val('');
            $('#am-restante').val('');
            $('#am-orden-info').text('');
            $('#am-cantidad-producida').val('');
            $('#am-cantidad-defectuosa').val('0');
        });

        $('#viewModal').on('hidden.bs.modal', function () {
            new bootstrap.Tab(document.getElementById('tab-detalles-btn')).show();
        });

        // ══════════════════════════════════════════════════════
        // Reset del form al cerrar
        // ══════════════════════════════════════════════════════
        $('#showModal').on('hidden.bs.modal', function () {
            $('#ordenForm')[0].reset();
            $('#id-field').val('');
            $('#detalle-pedido-id-field').val('');
            $('#pedido-id-hidden-field').val('');
            $('#producto-id-field').val('');
            $('#orden-linea-pedido').text('Pedido #—');
            $('#orden-linea-cliente').text('');
            $('#orden-linea-producto').text('—');
            $('#orden-linea-cantidad').text('0');
            $('#orden-linea-meta').empty();
            $('#modalTitle').text('Nueva Orden de Producción');
            $('#estado-container').hide();
            $('#add-btn').show();
            $('#edit-btn').hide();
            $('#ordenForm').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            resetInsumos();
        });
    });
</script>
