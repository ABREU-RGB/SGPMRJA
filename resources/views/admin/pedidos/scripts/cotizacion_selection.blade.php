<script>
    // ============================================
    // FUNCIONALIDAD DE SELECCIÓN DE COTIZACIONES
    // (Flujo unificado: usa convertirAPedido atómico)
    // ============================================

    let cotizacionesDisponibles = [];

    // Cargar cotizaciones cuando se abre el modal
    $(document).on('shown.bs.modal', '#seleccionarCotizacionModal', function () {
        cargarCotizacionesDisponibles();
    });

    // Función para cargar cotizaciones disponibles
    function cargarCotizacionesDisponibles() {
        const container = $('#cotizaciones-container');
        const emptyState = $('#empty-state');
        const loadingState = $('#loading-state');

        // Mostrar loading
        container.hide();
        emptyState.hide();
        loadingState.show();

        $.ajax({
            url: '{{ route("pedidos.cotizacionesDisponibles") }}',
            method: 'GET',
            success: function (data) {
                loadingState.hide();

                if (data.length === 0) {
                    emptyState.show();
                    return;
                }

                cotizacionesDisponibles = data;
                renderCotizaciones(data);
                container.show();
            },
            error: function (xhr) {
                loadingState.hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar las cotizaciones disponibles',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        });
    }

    // Renderizar cotizaciones como cards
    function renderCotizaciones(cotizaciones) {
        const container = $('#cotizaciones-container');
        container.empty();

        cotizaciones.forEach(function (cotizacion) {
            const card = `
                <div class="cotizacion-card" data-cotizacion-id="${cotizacion.id}">
                    <div class="cotizacion-header">
                        <span class="cotizacion-numero">
                            <i class="ri-file-text-line"></i> Cotización #${cotizacion.id}
                        </span>
                        <span class="cotizacion-total">$${cotizacion.total}</span>
                    </div>
                    <div class="cotizacion-info">
                        <div class="cotizacion-info-item">
                            <i class="ri-user-line"></i>
                            <span>${cotizacion.cliente_nombre}</span>
                        </div>
                        <div class="cotizacion-info-item">
                            <i class="ri-calendar-line"></i>
                            <span>${cotizacion.fecha_cotizacion}</span>
                        </div>
                        <div class="cotizacion-info-item">
                            <i class="ri-bank-card-line"></i>
                            <span>${cotizacion.cliente_documento}</span>
                        </div>
                        <div class="cotizacion-info-item">
                            <i class="ri-shopping-bag-line"></i>
                            <span>${cotizacion.cantidad_productos} producto(s)</span>
                        </div>
                    </div>
                    <div class="cotizacion-footer">
                        <button type="button" class="btn btn-sm btn-success seleccionar-cotizacion-btn">
                            <i class="ri-check-line"></i> Seleccionar Cotización
                        </button>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    // Búsqueda de cotizaciones
    $('#buscarCotizacion').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();

        if (searchTerm === '') {
            renderCotizaciones(cotizacionesDisponibles);
            return;
        }

        const filtradas = cotizacionesDisponibles.filter(function (cot) {
            return cot.cliente_nombre.toLowerCase().includes(searchTerm) ||
                cot.cliente_documento.toLowerCase().includes(searchTerm) ||
                cot.id.toString().includes(searchTerm);
        });

        renderCotizaciones(filtradas);
    });

    // Handler para seleccionar cotización — usa conversión atómica
    $(document).on('click', '.seleccionar-cotizacion-btn', function () {
        const card = $(this).closest('.cotizacion-card');
        const cotizacionId = card.data('cotizacion-id');

        // Encontrar datos de la cotización seleccionada
        const cotData = cotizacionesDisponibles.find(c => c.id == cotizacionId);
        const clienteNombre = cotData ? cotData.cliente_nombre : 'el cliente';
        const totalDisplay = cotData ? cotData.total : '0.00';

        // Cerrar modal de selección
        $('#seleccionarCotizacionModal').modal('hide');

        // Confirmar conversión
        setTimeout(function () {
            Swal.fire({
                title: '¿Convertir a Pedido?',
                html: '<p>Se creará un nuevo pedido con los datos de la <strong>Cotización #' + cotizacionId + '</strong>:</p>' +
                    '<div class="text-start mt-3 mb-3">' +
                    '<div class="d-flex align-items-center mb-2"><i class="ri-user-line me-2 text-primary"></i><span><strong>Cliente:</strong> ' + clienteNombre + '</span></div>' +
                    '<div class="d-flex align-items-center mb-2"><i class="ri-money-dollar-circle-line me-2 text-success"></i><span><strong>Total:</strong> $' + totalDisplay + '</span></div>' +
                    '</div>' +
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
                    convertirCotizacionAPedido(cotizacionId);
                }
            });
        }, 300);
    });

    // Función que llama al endpoint atómico de conversión
    function convertirCotizacionAPedido(cotizacionId) {
        // Mostrar loading
        Swal.fire({
            title: 'Convirtiendo...',
            text: 'Creando pedido desde la cotización',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

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
                    // Recargar la tabla de pedidos
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }

                    if (result.isConfirmed) {
                        // Redirigir al módulo de pedidos para editar
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
</script>