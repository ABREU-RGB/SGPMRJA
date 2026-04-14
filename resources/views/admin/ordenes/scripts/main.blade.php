<script>
    // Validación onblur: fecha_fin_estimada debe ser posterior a fecha_inicio
    $(document).on('blur', '#fecha-fin-estimada-field, input[name="fecha_fin_estimada"]', function () {
        let finVal = $(this).val();
        let inicioVal = $('#fecha-inicio-field').val() || $('input[name="fecha_inicio"]').val();
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
        // Re-validar fecha_fin si ya tiene valor
        let $fin = $('#fecha-fin-estimada-field');
        if ($fin.val()) {
            $fin.trigger('blur');
        }
    });

    // Re-validar fecha_fin cuando cambia fecha_inicio (compatibilidad)
    $(document).on('blur', 'input[name="fecha_inicio"]', function () {
        let $fin = $('#fecha-fin-estimada-field');
        if ($fin.val()) {
            $fin.trigger('blur');
        }
    });

    // Validación onblur: costo_estimado — no negativo
    $(document).on('blur', '#costo-estimado-field', function () {
        let val = parseFloat($(this).val());
        if ($(this).val() === '' || isNaN(val)) {
            marcarInvalido($(this), 'El costo estimado es obligatorio.');
        } else if (val < 0) {
            marcarInvalido($(this), 'El costo estimado no puede ser negativo.');
        } else {
            marcarValido($(this));
        }
    });

    // Validación onblur: insumo select — obligatorio
    $(document).on('blur', '.insumo-select', function () {
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

        // Re-validar defectuosa si ya tiene valor
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
        // Función para inicializar Select2
        function initializeSelect2(selector) {
            $(selector).select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione insumo...',
                width: '100%',
                dropdownParent: $('#showModal')
            });
        }

        // Inicializar Select2 para los selectores existentes
        initializeSelect2('.insumo-select');

        // Manejar selección de pedido
        $('#pedido-id-field').on('change', function () {
            const pedidoId = $(this).val();

            if (pedidoId) {
                // Establecer el pedido_id en el campo oculto
                $('#pedido-id-hidden-field').val(pedidoId);

                // Mostrar información básica del pedido
                const selectedOption = $(this).find('option:selected');
                $('#info-cliente').text(selectedOption.data('cliente'));
                $('#info-fecha-pedido').text(selectedOption.data('fecha'));
                $('#info-fecha-entrega').text(selectedOption.data('entrega'));
                $('#pedido-info').show();

                // Obtener datos completos del pedido
                $.ajax({
                    url: `/pedidos/${pedidoId}/data-for-orden`,
                    method: 'GET',
                    success: function (pedido) {

                        // Mostrar productos del pedido
                        $('#info-total-productos').text(pedido.productos.length);

                        let productosHtml = '';
                        let totalCosto = 0;

                        pedido.productos.forEach(function (detalle, index) {
                            const subtotal = detalle.cantidad * detalle.precio_unitario;
                            totalCosto += subtotal;

                            productosHtml += `
                                <div class="card mb-2">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="mb-1">${detalle.producto && detalle.producto.nombre ? detalle.producto.nombre : 'Sin nombre'}</h6>
                                                <small class="text-muted">
                                                    Cantidad: ${detalle.cantidad} | 
                                                    Precio: ${detalle.precio_unitario}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="text-end">
                                                    <strong>Subtotal: ${subtotal.toFixed(2)}</strong>
                                                </div>
                                                ${detalle.lleva_bordado ? `<small class="text-info"><i class="ri-brush-line"></i> Logo: ${detalle.nombre_logo || 'N/A'}<br>Ubicación: ${detalle.ubicacion_logo || 'N/A'}<br>Cantidad: ${detalle.cantidad_logo || 'N/A'}</small>` : ''}
                                                ${detalle.color ? `<br><small><i class="ri-palette-line"></i> Color: ${detalle.color}</small>` : ''}
                                                ${detalle.talla ? `<small> | <i class="ri-t-shirt-line"></i> Talla: ${detalle.talla}</small>` : ''}
                                                ${detalle.descripcion ? `<br><small class="text-muted">${detalle.descripcion}</small>` : ''}
                                                ${detalle.insumos && detalle.insumos.length > 0 ? `<br><small class="text-success"><i class="ri-tools-line"></i> ${detalle.insumos.length} insumo(s)</small>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        $('#productos-container').html(productosHtml);
                        $('#productos-pedido').show();

                        // Llenar campos automáticamente
                        // Usar el primer producto como referencia (o se puede modificar para manejar múltiples productos)
                        if (pedido.productos.length > 0) {
                            const primerProducto = pedido.productos[0];
                            $('#producto-id-field').val(primerProducto.producto_id);

                            // Sumar todas las cantidades si hay múltiples productos del mismo tipo
                            const cantidadTotal = pedido.productos.reduce((sum, p) => sum + p.cantidad, 0);
                            $('#cantidad-solicitada-field').val(cantidadTotal);
                        }

                        // Establecer fechas sugeridas
                        const hoy = new Date();
                        $('#fecha-inicio-field').val(hoy.toISOString().split('T')[0]);

                        if (pedido.fecha_entrega_estimada) {
                            const fechaEntrega = new Date(pedido.fecha_entrega_estimada);
                            // Sugerir fecha fin 2 días antes de la entrega
                            fechaEntrega.setDate(fechaEntrega.getDate() - 2);
                            $('#fecha-fin-estimada-field').val(fechaEntrega.toISOString().split('T')[0]);
                        }

                        // Establecer costo estimado basado en el total del pedido
                        $('#costo-estimado-field').val(totalCosto.toFixed(2));

                        // Llenar insumos si están disponibles
                        $('#insumos-container').empty();
                        let insumoIndex = 0;
                        let insumosAgregados = new Map(); // Para evitar duplicados

                        pedido.productos.forEach(function (detalle) {
                            if (detalle.insumos && detalle.insumos.length > 0) {
                                detalle.insumos.forEach(function (insumo) {

                                    const cantidadEstimada = insumo.pivot ? insumo.pivot.cantidad_estimada : 1;
                                    const cantidadTotal = cantidadEstimada * detalle.cantidad;

                                    // Verificar si ya agregamos este insumo
                                    if (insumosAgregados.has(insumo.id)) {
                                        // Si ya existe, sumar la cantidad
                                        const existingIndex = insumosAgregados.get(insumo.id);
                                        const currentValue = parseFloat($(`input[name="insumos[${existingIndex}][cantidad_estimada]"]`).val()) || 0;
                                        $(`input[name="insumos[${existingIndex}][cantidad_estimada]"]`).val(currentValue + cantidadTotal);
                                    } else {
                                        // Agregar nuevo insumo
                                        let newRow = `
                                            <div class="row insumo-row mt-2">
                                                <div class="col-md-6">
                                                    <select name="insumos[${insumoIndex}][id]" class="form-control insumo-select" required>
                                                        <option value="">Seleccione insumo...</option>
                                                        @foreach($insumos as $insumoOption)
                                                            <option value="{{ $insumoOption->id }}" ${insumo.id == '{{ $insumoOption->id }}' ? 'selected' : ''}>{{ $insumoOption->nombre }} ({{ $insumoOption->unidad_medida }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" name="insumos[${insumoIndex}][cantidad_estimada]" class="form-control" placeholder="Cantidad" step="0.01" min="0.01" value="${cantidadTotal.toFixed(2)}" required />
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-insumo"><i class="ri-delete-bin-line"></i></button>
                                                </div>
                                            </div>
                                        `;
                                        $('#insumos-container').append(newRow);

                                        // Establecer el valor del select después de un pequeño delay
                                        setTimeout(() => {
                                            $(`select[name="insumos[${insumoIndex}][id]"]`).val(insumo.id);
                                            initializeSelect2(`select[name="insumos[${insumoIndex}][id]"]`);
                                        }, 100);

                                        insumosAgregados.set(insumo.id, insumoIndex);
                                        insumoIndex++;
                                    }
                                });
                            }
                        });

                        // Si no hay insumos, agregar una fila vacía
                        if (insumoIndex === 0) {
                            $('#add-insumo-btn').click();
                        }

                        // Llenar logo si está disponible
                        const productosConLogo = pedido.productos.filter(p => p.lleva_bordado && p.nombre_logo);

                        if (productosConLogo.length > 0) {
                            // Si hay múltiples logos, combinarlos
                            const logos = productosConLogo.map(p => p.nombre_logo).filter(logo => logo);
                            const logosUnicos = [...new Set(logos)]; // Eliminar duplicados
                            const logoFinal = logosUnicos.join(', ');

                            $('#logo-field').val(logoFinal);
                        } else {
                            $('#logo-field').val('');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error al obtener datos del pedido:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los datos del pedido'
                        });
                    }
                });
            } else {
                // Limpiar el pedido_id
                $('#pedido-id-hidden-field').val('');

                // Ocultar información si no hay pedido seleccionado
                $('#pedido-info').hide();
                $('#productos-pedido').hide();

                // Limpiar campos
                $('#producto-id-field').val('');
                $('#cantidad-solicitada-field').val('');
                $('#fecha-inicio-field').val('');
                $('#fecha-fin-estimada-field').val('');
                $('#costo-estimado-field').val('');
                $('#logo-field').val('');

                // Resetear insumos
                $('#insumos-container').html(`
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
                `);
                initializeSelect2('.insumo-select');
            }
        });

        // DataTable
        var table = $('#ordenes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ordenes.data') }}",
            dom: 'rtip',
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    className: 'align-middle text-center',
                    width: '8%'
                },
                {
                    data: 'pedido_info',
                    name: 'pedido.id',
                    className: 'align-middle text-center',
                    orderable: false,
                    width: '10%'
                },
                {
                    data: 'cantidad_solicitada',
                    name: 'cantidad_solicitada',
                    className: 'align-middle text-center',
                    width: '12%'
                },
                {
                    data: null,
                    className: 'align-middle',
                    width: '18%',
                    render: function (data) {
                        let porcentaje = (data.cantidad_producida / data.cantidad_solicitada * 100).toFixed(2);
                        return `<div class="progress" style="height: 15px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: ${porcentaje}%"
                                aria-valuenow="${porcentaje}" aria-valuemin="0" aria-valuemax="100">
                                ${porcentaje}%
                            </div>
                        </div>`;
                    }
                },
                {
                    data: 'fecha_fin_estimada',
                    name: 'fecha_fin_estimada',
                    className: 'align-middle',
                    width: '14%',
                    render: function (data) {
                        let fecha = new Date(data);
                        return `<div class="text-nowrap">
                            <div class="fw-medium">${fecha.toLocaleDateString('es-ES')}</div>
                        </div>`;
                    }
                },
                {
                    data: 'estado',
                    className: 'align-middle text-center',
                    width: '12%',
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
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                    width: '16%',
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
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
            autoWidth: false,
            responsive: false,
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'csv',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'excel',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'pdf',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                },
                {
                    extend: 'print',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                }
            ],
            language: lenguajeData
        });

        // Buscador personalizado
        $('#custom-search-input').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Agregar fila de insumo
        $('#add-insumo-btn').click(function () {
            let index = $('.insumo-row').length;
            let newRow = `
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
                </div>
            `;
            $('#insumos-container').append(newRow);
            // Inicializar Select2 para el nuevo selector
            initializeSelect2('.insumo-select:last');
        });

        // Remover fila de insumo
        $(document).on('click', '.remove-insumo', function () {
            $(this).closest('.insumo-row').remove();
        });

        // ══════════════════════════════════════════════════════
        // VALIDACIÓN AL SUBMIT
        // ══════════════════════════════════════════════════════
        function validarFormularioOrden() {
            let esValido = true;

            // Pedido — obligatorio (solo en creación, en edición está bloqueado)
            let isEdit = $('#id-field').val() !== '';
            if (!isEdit) {
                let $pedido = $('#pedido-id-field');
                if (!$pedido.val()) {
                    marcarInvalido($pedido, 'Debe seleccionar un pedido.');
                    esValido = false;
                } else {
                    marcarValido($pedido);
                }
            }

            // Fecha Inicio — obligatoria
            let $inicio = $('#fecha-inicio-field');
            if (!$inicio.val()) {
                marcarInvalido($inicio, 'La fecha de inicio es obligatoria.');
                esValido = false;
            } else {
                marcarValido($inicio);
            }

            // Fecha Fin Estimada — obligatoria y posterior a inicio
            let $fin = $('#fecha-fin-estimada-field');
            if (!$fin.val()) {
                marcarInvalido($fin, 'La fecha fin estimada es obligatoria.');
                esValido = false;
            } else if ($inicio.val() && $fin.val() <= $inicio.val()) {
                marcarInvalido($fin, 'La fecha fin estimada debe ser posterior a la fecha de inicio.');
                esValido = false;
            } else {
                marcarValido($fin);
            }

            // Costo Estimado — no negativo
            let $costo = $('#costo-estimado-field');
            let costoVal = parseFloat($costo.val());
            if ($costo.val() === '' || isNaN(costoVal)) {
                marcarInvalido($costo, 'El costo estimado es obligatorio.');
                esValido = false;
            } else if (costoVal < 0) {
                marcarInvalido($costo, 'El costo estimado no puede ser negativo.');
                esValido = false;
            } else {
                marcarValido($costo);
            }

            // Insumos — al menos 1 fila, todos con insumo seleccionado y cantidad > 0
            let insumoValido = true;
            $('#insumos-container .insumo-row').each(function () {
                let $select = $(this).find('.insumo-select');
                let $cantidad = $(this).find('input[name*="[cantidad_estimada]"]');
                if (!$select.val()) {
                    marcarInvalido($select, 'Seleccione un insumo.');
                    insumoValido = false;
                } else {
                    marcarValido($select);
                }
                let cantVal = parseFloat($cantidad.val());
                if (isNaN(cantVal) || cantVal <= 0) {
                    marcarInvalido($cantidad, 'La cantidad debe ser mayor a cero.');
                    insumoValido = false;
                } else {
                    marcarValido($cantidad);
                }
            });
            if (!insumoValido) esValido = false;

            return esValido;
        }

        // Crear orden
        $('#ordenForm').on('submit', function (e) {
            e.preventDefault();
            if (!validarFormularioOrden()) return;
            let formData = new FormData(this);
            // Los campos disabled no se incluyen en FormData — agregarlos manualmente
            if ($('#producto-id-field').prop('disabled')) {
                formData.append('producto_id', $('#producto-id-field').val());
            }
            if ($('#pedido-id-field').prop('disabled') && $('#pedido-id-hidden-field').val()) {
                formData.append('pedido_id', $('#pedido-id-hidden-field').val());
            }
            let url = $('#id-field').val() ?
                "{{ route('ordenes.update', ':id') }}".replace(':id', $('#id-field').val()) :
                "{{ route('ordenes.store') }}";

            // Siempre usar POST y agregar _method para actualización
            if ($('#id-field').val()) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                url: url,
                method: 'POST', // Siempre usar POST
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#showModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Ocurrió un error al procesar la solicitud'
                    });
                }
            });
        });

        // Editar orden
        $(document).on('click', '.edit-btn', function () {
            let id = $(this).data('id');
            $.get("{{ route('ordenes.edit', ':id') }}".replace(':id', id), function (data) {
                $('#modalTitle').text('Editar Orden de Producción');
                $('#id-field').val(data.id);
                $('#producto-id-field').val(data.producto_id).trigger('change').prop('disabled', true).addClass('campo-protegido');
                $('#cantidad-solicitada-field').val(data.cantidad_solicitada).prop('readonly', true).addClass('campo-protegido');
                $('#pedido-id-field').prop('disabled', true).addClass('campo-protegido');

                const formatDateForInput = (dateString) => {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toISOString().split('T')[0];
                };

                $('#fecha-inicio-field').val(formatDateForInput(data.fecha_inicio));
                $('#fecha-fin-estimada-field').val(formatDateForInput(data.fecha_fin_estimada));
                $('#costo-estimado-field').val(data.costo_estimado);
                $('#estado-field').val(data.estado);
                $('#logo-field').val(data.logo_id || '');
                $('#notas-field').val(data.notas);

                // Limpiar y agregar insumos
                $('#insumos-container').empty();
                data.insumos.forEach((insumo, index) => {
                    $('#add-insumo-btn').click();
                    // Pequeña pausa para asegurar que Select2 se inicialice correctamente
                    setTimeout(() => {
                        $(`select[name="insumos[${index}][id]"]`).val(insumo.id).trigger('change');
                        $(`input[name="insumos[${index}][cantidad_estimada]"]`).val(insumo.pivot.cantidad_estimada);
                    }, 100);
                });

                $('#estado-container').show();
                $('#add-btn').hide();
                $('#edit-btn').show();
                $('#showModal').modal('show');
            });
        });

        // Ver orden
        $(document).on('click', '.view-btn', function () {
            let id = $(this).data('id');
            $.get("{{ route('ordenes.show', ':id') }}".replace(':id', id), function (data) {
                const estadoClases = {
                    'Pendiente':  'status-pendiente badge-soft-warning',
                    'En Proceso': 'status-procesando badge-soft-info',
                    'Finalizado': 'status-finalizado badge-soft-success',
                    'Cancelado':  'status-cancelado badge-soft-danger'
                };

                $('#view-producto').text(data.producto.nombre);
                $('#view-cantidad-solicitada').text(data.cantidad_solicitada);
                $('#view-cantidad-producida').text(data.cantidad_producida);

                let porcentaje = data.cantidad_solicitada > 0
                    ? (data.cantidad_producida / data.cantidad_solicitada * 100).toFixed(1)
                    : '0.0';
                $('#view-progreso').css('width', porcentaje + '%').attr('aria-valuenow', porcentaje);
                $('#view-progreso-pct').text(porcentaje);

                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };

                $('#view-fecha-inicio').text(formatDate(data.fecha_inicio));
                $('#view-fecha-fin-estimada').text(formatDate(data.fecha_fin_estimada));
                $('#view-estado').html(`<span class="badge badge-status ${estadoClases[data.estado] || 'badge-soft-secondary'} rounded-pill">${data.estado}</span>`);
                $('#view-costo-estimado').text(
                    '$/ ' + Number(data.costo_estimado).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                );
                $('#view-creado-por').text(data.creado_por ? data.creado_por.name : 'Sin especificar');
                $('#view-pedido-info').text(data.pedido_id ? 'Pedido #' + data.pedido_id : 'Orden Manual');

                let logoName = data.logo ? data.logo.name : null;
                let logoHtml = logoName
                    ? `<div class="d-flex align-items-start gap-2"><i class="ri-image-line text-muted mt-1 flex-shrink-0"></i><span>${logoName}</span></div>`
                    : `<span class="text-muted fst-italic">Sin logo registrado</span>`;
                if (data.pedido && data.pedido.productos) {
                    data.pedido.productos.forEach(function (detalle) {
                        if (detalle.lleva_bordado) {
                            logoHtml += `<div class="mt-2 pt-2 border-top">
                                <div class="d-flex gap-1 mb-1"><span class="text-muted fs-11">Logo:</span><span class="fw-medium fs-12">${detalle.nombre_logo || 'N/A'}</span></div>
                                <div class="d-flex gap-1 mb-1"><span class="text-muted fs-11">Ubicación:</span><span class="fw-medium fs-12">${detalle.ubicacion_logo || 'N/A'}</span></div>
                                <div class="d-flex gap-1"><span class="text-muted fs-11">Cantidad:</span><span class="fw-medium fs-12">${detalle.cantidad_logo || 'N/A'}</span></div>
                            </div>`;
                        }
                    });
                }
                $('#view-logo').html(logoHtml);

                // Insumos
                $('#view-insumos').empty();
                data.insumos.forEach(insumo => {
                    let porcentajeInsumo = (insumo.pivot.cantidad_utilizada / insumo.pivot.cantidad_estimada * 100).toFixed(2);
                    $('#view-insumos').append(`
                        <tr>
                            <td>
                                <h6 class="fs-13 mb-0">${insumo.nombre}</h6>
                            </td>
                            <td class="text-center">${insumo.pivot.cantidad_estimada} ${insumo.unidad_medida}</td>
                            <td class="text-center">${insumo.pivot.cantidad_utilizada} ${insumo.unidad_medida}</td>
                            <td>
                                <div class="progress animated-progress custom-progress progress-sm">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: ${porcentajeInsumo}%"
                                        aria-valuenow="${porcentajeInsumo}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="text-muted fs-11 mt-1 text-center">${porcentajeInsumo}%</div>
                            </td>
                        </tr>
                    `);
                });

                $('#view-notas').text(data.notas || 'Sin notas adicionales.');
                const formatDateTime = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };
                $('#view-created').text(formatDateTime(data.created_at));

                // ── Avances (historial lectura) ───────────────────────
                renderAvances(data.produccion_diaria || []);

                // Pestaña por defecto: Avances si activa, Detalles si finalizada
                const estadoActivo = ['Pendiente', 'En Proceso'].includes(data.estado);
                new bootstrap.Tab(document.getElementById(estadoActivo ? 'tab-avances-btn' : 'tab-detalles-btn')).show();

                $('#viewModal').modal('show');
            });
        });

        // Eliminar orden
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
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2',
                    cancelButton: 'btn btn-danger w-xs',
                    container: 'swal2-container'
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('ordenes.destroy', ':id') }}".replace(':id', id),
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            table.ajax.reload();
                            Swal.fire('Eliminado', response.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Error', xhr.responseJSON.message || 'Ocurrió un error', 'error');
                        }
                    });
                }
            });
        });

        // ── Avance de Producción — helpers y handlers ─────────────────

        function renderAvances(avances) {
            const tbody = $('#view-avances');
            tbody.empty();
            if (!avances.length) {
                tbody.html('<tr id="avances-empty-row"><td colspan="6" class="text-center text-muted py-3"><i class="ri-inbox-line me-1"></i>Sin registros de avance</td></tr>');
                return;
            }
            avances.forEach(function (a) {
                const empleadoNombre = a.empleado && a.empleado.persona
                    ? a.empleado.persona.nombre_completo
                    : 'N/A';
                const fecha = a.created_at
                    ? new Date(a.created_at).toLocaleDateString('es-ES')
                    : 'N/A';
                tbody.append(`
                    <tr>
                        <td class="text-nowrap">${fecha}</td>
                        <td>${empleadoNombre}</td>
                        <td class="text-center fw-medium">${a.cantidad_producida}</td>
                        <td class="text-center ${a.cantidad_defectuosa > 0 ? 'text-danger fw-medium' : ''}">${a.cantidad_defectuosa}</td>
                        <td class="text-muted">${a.observaciones || '—'}</td>
                    </tr>
                `);
            });
        }

        // Abrir modal de avance desde la tabla
        $(document).on('click', '.avance-btn', function () {
            const id = $(this).data('id');
            $.get("{{ route('ordenes.show', ':id') }}".replace(':id', id), function (data) {
                const restante = data.cantidad_solicitada - data.cantidad_producida;
                $('#am-orden-id').val(data.id);
                $('#am-restante').val(restante);
                $('#am-orden-info').text(`${data.producto.nombre} · ${restante} piezas restantes`);
                $('#am-restante-hint').text(`(máx. ${restante})`);
                $('#am-cantidad-producida').attr('max', restante);
                $('#avanceModal').modal('show');
            });
        });

        // Guardar avance
        $('#am-btn-save').on('click', function () {
            const ordenId       = $('#am-orden-id').val();
            const empleadoId    = $('#am-empleado-id').val();
            const producida     = $('#am-cantidad-producida').val();
            const defectuosa    = $('#am-cantidad-defectuosa').val();
            const observaciones = $('#am-observaciones').val();
            const restante      = parseInt($('#am-restante').val()) || 0;

            if (!empleadoId || !producida || parseInt(producida) < 1) {
                Swal.fire({ icon: 'warning', title: 'Datos incompletos', text: 'Selecciona el empleado e ingresa una cantidad producida válida.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                return;
            }
            if (parseInt(producida) > restante) {
                Swal.fire({ icon: 'warning', title: 'Cantidad excedida', text: `Solo quedan ${restante} piezas por producir en esta orden.`, toast: true, position: 'top-end', showConfirmButton: false, timer: 4000 });
                return;
            }

            $.ajax({
                url: "{{ route('produccion.diaria.store') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orden_id: ordenId,
                    empleado_id: empleadoId,
                    cantidad_producida: producida,
                    cantidad_defectuosa: defectuosa || 0,
                    observaciones: observaciones
                },
                success: function () {
                    $('#avanceModal').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Avance registrado', toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.error || 'No se pudo guardar el avance.' });
                }
            });
        });

        // Reset al cerrar avanceModal
        $('#avanceModal').on('hidden.bs.modal', function () {
            $('#am-orden-id').val('');
            $('#am-restante').val('');
            $('#am-orden-info').text('');
            $('#am-empleado-id').val('');
            $('#am-cantidad-producida').val('');
            $('#am-cantidad-defectuosa').val('0');
            $('#am-observaciones').val('');
        });

        // Reset al cerrar viewModal
        $('#viewModal').on('hidden.bs.modal', function () {
            new bootstrap.Tab(document.getElementById('tab-detalles-btn')).show();
            $('#view-avances').html('<tr id="avances-empty-row"><td colspan="5" class="text-center text-muted py-3 fs-12"><i class="ri-inbox-line me-1"></i>Sin registros de avance</td></tr>');
        });

        // Reset form on modal close
        $('#showModal').on('hidden.bs.modal', function () {
            $('#ordenForm')[0].reset();
            $('#id-field').val('');
            $('#pedido-id-hidden-field').val('');
            // Resetear y habilitar campos protegidos
            $('#pedido-id-field').val('').prop('disabled', false).removeClass('campo-protegido');
            $('#producto-id-field').val('').prop('disabled', false).removeClass('campo-protegido');
            $('#cantidad-solicitada-field').prop('readonly', false).removeClass('campo-protegido');
            $('#modalTitle').text('Nueva Orden de Producción');
            $('#estado-container').hide();
            $('#add-btn').show();
            $('#edit-btn').hide();

            // Ocultar secciones de pedido
            $('#pedido-info').hide();
            $('#productos-pedido').hide();

            // Resetear insumos
            $('#insumos-container').html(`
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
            `);
            // Reinicializar Select2 después de resetear el formulario
            initializeSelect2('.insumo-select');
        });
    });
</script>