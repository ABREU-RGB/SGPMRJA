<script>
$(document).ready(function () {

    // ╔══════════════════════════════════════════════════════════════════
    // ║ PEDIDO WIZARD — Scaffold de navegación (4 pasos)
    // ║ showStep, validarStep, siguiente, anterior, stepper visual
    // ╚══════════════════════════════════════════════════════════════════
    (function () {
        'use strict';

        var TOTAL_STEPS = 4;
        var currentStep = 1;

        function isEditMode() { return !!$('#ped-wiz-id-field').val(); }

        function showStep(n) {
            n = Math.max(1, Math.min(TOTAL_STEPS, n));
            currentStep = n;

            // Mostrar sección activa, ocultar el resto
            document.querySelectorAll('#ped-wiz-step-1, #ped-wiz-step-2, #ped-wiz-step-3, #ped-wiz-step-4').forEach(function (sec) {
                var step = parseInt(sec.dataset.step, 10);
                var active = step === n;
                sec.classList.toggle('is-active', active);
                sec.hidden = !active;
            });

            // Markers del stepper (dentro del wizard de pedidos)
            document.querySelectorAll('#pedidoForm').forEach(function () {
                document.querySelectorAll('.wiz-step-marker[data-step]').forEach(function (mk) {
                    var step = parseInt(mk.dataset.step, 10);
                    mk.classList.toggle('is-active', step === n);
                    mk.classList.toggle('is-complete', step < n);
                    mk.setAttribute('aria-selected', step === n ? 'true' : 'false');
                });
            });

            // Líneas de progreso
            document.querySelectorAll('.wiz-step-line-fill[data-line]').forEach(function (lf) {
                var line = parseInt(lf.dataset.line, 10);
                lf.style.width = (line < n) ? '100%' : '0%';
            });

            // Contador de paso
            var ind = document.getElementById('ped-step-current');
            if (ind) ind.textContent = String(n);

            // Visibilidad de botones de footer
            var $prev  = $('#btn-ped-prev');
            var $next  = $('#btn-ped-next');
            var $add   = $('#ped-wiz-add-btn');
            var $edit  = $('#ped-wiz-edit-btn');

            $prev.toggle(n > 1);

            if (n === TOTAL_STEPS) {
                $next.hide();
                if (isEditMode()) { $add.hide(); $edit.show(); }
                else              { $edit.hide(); $add.show(); }
            } else {
                $next.show();
                $add.hide(); $edit.hide();
            }
        }

        function validateStep(n) {
            if (n === 1) {
                var clienteId = $('#ped-wiz-cliente-id-field').val();
                var fecha     = $('#ped-fecha-pedido-field').val();

                if (!clienteId) {
                    Swal.fire({
                        icon: 'warning', title: 'Cliente requerido',
                        text: 'Debes seleccionar o crear un cliente antes de continuar.',
                        timer: 2200, showConfirmButton: false
                    });
                    $('#ped-ci-rif-number-field').trigger('focus');
                    return false;
                }
                if (!fecha) {
                    Swal.fire({
                        icon: 'warning', title: 'Fecha requerida',
                        text: 'La fecha del pedido es obligatoria.',
                        timer: 2200, showConfirmButton: false
                    });
                    $('#ped-fecha-pedido-field').trigger('focus');
                    return false;
                }
                var entrega = $('#ped-fecha-entrega-field').val();
                if (entrega && entrega < fecha) {
                    Swal.fire({
                        icon: 'warning', title: 'Fechas inconsistentes',
                        text: 'La fecha de entrega no puede ser anterior a la del pedido.',
                        timer: 2400, showConfirmButton: false
                    });
                    $('#ped-fecha-entrega-field').trigger('focus');
                    return false;
                }
                var $invalid = $('#ped-wiz-step-1 .is-invalid:visible');
                if ($invalid.length) {
                    Swal.fire({
                        icon: 'warning', title: 'Corrige los errores',
                        text: 'Revisa los campos marcados en rojo antes de continuar.',
                        timer: 2200, showConfirmButton: false
                    });
                    $invalid.first().trigger('focus');
                    return false;
                }
                return true;
            }
            if (n === 2) {
                var items = (window.pedProdState && window.pedProdState.items) || [];
                if (!items.length) {
                    Swal.fire({
                        icon: 'warning', title: 'Sin productos',
                        text: 'Debes agregar al menos un producto antes de continuar.',
                        timer: 2200, showConfirmButton: false
                    });
                    return false;
                }
                return true;
            }
            // Pasos 3–4 se implementan en TASK-013/014
            return true;
        }

        function nextStep() {
            if (currentStep < TOTAL_STEPS) {
                if (!validateStep(currentStep)) return;
                showStep(currentStep + 1);
            }
        }
        function prevStep() {
            if (currentStep > 1) showStep(currentStep - 1);
        }

        // Botones de footer
        $('#btn-ped-next').on('click', nextStep);
        $('#btn-ped-prev').on('click', prevStep);

        // Click en marker del stepper: retroceder libre, avanzar con validación
        $(document).on('click', '.wiz-step-marker[data-step]', function () {
            // Actuar solo cuando el wizard de pedidos está visible
            if (!$('#ped-wiz-step-1').length) return;
            var target = parseInt(this.dataset.step, 10);
            if (target === currentStep) return;
            if (target < currentStep) { showStep(target); return; }
            for (var s = currentStep; s < target; s++) {
                if (!validateStep(s)) return;
            }
            showStep(target);
        });

        // Lifecycle del modal
        var $wizModal = $(document).find('#pedidoForm').closest('.modal');
        if (!$wizModal.length) $wizModal = $('#showModal');

        $wizModal.on('show.bs.modal', function () { showStep(1); });
        $wizModal.on('hidden.bs.modal', function () { currentStep = 1; });

        // API global
        window.pedWizard = { show: showStep, next: nextStep, prev: prevStep };
    })();


    // ╔══════════════════════════════════════════════════════════════════
    // ║ PEDIDO WIZARD — PASO 1: Cliente y datos
    // ║ Autocomplete personas-search, tarjeta cliente, chips prioridad/estado
    // ╚══════════════════════════════════════════════════════════════════
    (function () {
        'use strict';

        // Estado de autocompletado
        var pedClienteSeleccionado = false;
        var pedAutocompleteTimeout = null;

        // === Avatar: iniciales + color ===================================
        var AVATAR_COLORS = [
            { bg: '#1e3c72', fg: '#ffffff' },
            { bg: '#00b88c', fg: '#ffffff' },
            { bg: '#0c4a6e', fg: '#ffffff' },
            { bg: '#6b21a8', fg: '#ffffff' },
            { bg: '#b45309', fg: '#ffffff' },
            { bg: '#be123c', fg: '#ffffff' },
            { bg: '#0e7490', fg: '#ffffff' },
            { bg: '#365314', fg: '#ffffff' }
        ];
        function hashStr(s) {
            var h = 0;
            for (var i = 0; i < s.length; i++) { h = ((h << 5) - h) + s.charCodeAt(i); h |= 0; }
            return Math.abs(h);
        }
        function buildIniciales(persona) {
            if (!persona) return '—';
            var docPrefix = String(persona.tipo_documento || '').toUpperCase();
            var esJuridico = docPrefix === 'J-' || docPrefix === 'G-';
            if (esJuridico && persona.razon_social) {
                var rs = persona.razon_social.trim();
                var parts = rs.split(/\s+/).filter(Boolean);
                return parts.length >= 2 ? (parts[0][0] + parts[1][0]).toUpperCase() : rs.substring(0, 2).toUpperCase();
            }
            var n = (persona.nombre || '').trim();
            var a = (persona.apellido || '').trim();
            if (n && a) return (n[0] + a[0]).toUpperCase();
            if (n) return n.substring(0, 2).toUpperCase();
            return '—';
        }
        function pickAvatarColor(key) {
            return AVATAR_COLORS[hashStr(String(key || 'default')) % AVATAR_COLORS.length];
        }

        // === Roles → badges ==============================================
        var ROLE_LABELS = {
            cliente:            { label: 'Cliente',   cls: 'cot-role-pill cot-role-cliente' },
            empleado:           { label: 'Empleado',  cls: 'cot-role-pill cot-role-empleado' },
            proveedor_natural:  { label: 'Proveedor', cls: 'cot-role-pill cot-role-proveedor' },
            proveedor_juridico: { label: 'Proveedor', cls: 'cot-role-pill cot-role-proveedor' }
        };
        function buildRolesBadges(roles) {
            if (!Array.isArray(roles) || !roles.length) return '';
            var seen = {};
            return roles.map(function (r) {
                var meta = ROLE_LABELS[r];
                if (!meta || seen[meta.label]) return '';
                seen[meta.label] = true;
                return '<span class="' + meta.cls + '">' + meta.label + '</span>';
            }).filter(Boolean).join('');
        }

        // Badges para la lista de resultados del autocomplete
        var AC_ROLE_BADGES = {
            cliente:            { label: 'Cliente',   cls: 'bg-success-subtle text-success' },
            empleado:           { label: 'Empleado',  cls: 'bg-info-subtle text-info' },
            proveedor_natural:  { label: 'Proveedor', cls: 'bg-warning-subtle text-warning' },
            proveedor_juridico: { label: 'Proveedor', cls: 'bg-warning-subtle text-warning' }
        };
        function renderAcRoleBadges(roles) {
            var seen = new Set();
            return (roles || []).map(function (r) {
                var meta = AC_ROLE_BADGES[r];
                if (!meta || seen.has(meta.label)) return '';
                seen.add(meta.label);
                return '<span class="badge ' + meta.cls + ' ms-1">' + meta.label + '</span>';
            }).join('');
        }
        function rolesLabelLegible(roles) {
            var labels = [], seen = new Set();
            (roles || []).forEach(function (r) {
                var meta = AC_ROLE_BADGES[r];
                if (meta && !seen.has(meta.label)) { seen.add(meta.label); labels.push(meta.label); }
            });
            if (!labels.length) return '';
            if (labels.length === 1) return labels[0];
            return labels.slice(0, -1).join(', ') + ' y ' + labels[labels.length - 1];
        }

        // === Tarjeta del cliente seleccionado ============================
        window.pedMostrarTarjetaCliente = function (persona, clienteId) {
            if (!persona) return;
            var docPrefix  = String(persona.tipo_documento || '').toUpperCase();
            var esJuridico = docPrefix === 'J-' || docPrefix === 'G-';
            var nombre     = esJuridico && persona.razon_social
                ? persona.razon_social
                : (persona.apellido ? persona.nombre + ' ' + persona.apellido : (persona.nombre || ''));

            var iniciales = buildIniciales(persona);
            var color     = pickAvatarColor(persona.documento || persona.persona_id || nombre);

            $('#ped-cliente-empty').hide();
            $('#ped-cliente-loading').attr('hidden', true);
            $('#ped-cliente-card').removeAttr('hidden').show();

            var $av = $('#ped-cliente-avatar');
            $av.text(iniciales).css({ background: color.bg, color: color.fg });

            $('#ped-cliente-name-display').text(nombre || '—');
            $('#ped-cliente-doc-display').text(persona.documento || '—');

            var tel   = persona.telefono || '';
            var email = persona.email || '';
            $('#ped-cliente-tel-wrap').toggle(!!tel);
            $('#ped-cliente-tel-display').text(tel || '—');
            $('#ped-cliente-email-wrap').toggle(!!email);
            $('#ped-cliente-email-display').text(email || '—');

            $('#ped-cliente-roles').html(buildRolesBadges(persona.roles));
        };

        window.pedResetearCliente = function () {
            $('#ped-cliente-card').attr('hidden', true).hide();
            $('#ped-cliente-loading').attr('hidden', true).hide();
            $('#ped-cliente-empty').show();
            $('#ped-wiz-cliente-id-field').val('');
            pedClienteSeleccionado = false;
        };

        window.pedShowLoading = function (show) {
            if (show) {
                if ($('#ped-wiz-cliente-id-field').val()) return;
                $('#ped-cliente-empty').hide();
                $('#ped-cliente-loading').removeAttr('hidden').show();
            } else {
                $('#ped-cliente-loading').attr('hidden', true).hide();
                if (!$('#ped-wiz-cliente-id-field').val()) $('#ped-cliente-empty').show();
            }
        };

        // === Aplicar persona al formulario de pedido =====================
        function pedAplicarPersonaAPedido(persona, clienteId) {
            $('#ped-wiz-cliente-id-field').val(clienteId || '');

            var docString = String(persona.documento || '').trim();
            var prefix = 'V-', number = '';
            if (docString) {
                if (/^[VJEG]-/.test(docString)) {
                    prefix = docString.substring(0, 2);
                    number = docString.substring(2);
                } else {
                    number = docString;
                    prefix = (docString.length >= 8 && /^[2-9]/.test(docString)) ? 'J-' : 'V-';
                }
            }
            $('#ped-ci-rif-prefix-field').val(prefix);
            $('#ped-ci-rif-number-field').val(number);
            $('#ped-ci-rif-full-field').val(prefix + number);
            $('#ped-cliente-autocomplete-list').empty().hide();
            pedClienteSeleccionado = true;

            if (typeof window.pedMostrarTarjetaCliente === 'function') {
                window.pedMostrarTarjetaCliente(persona, clienteId);
            }
        }
        // Exponer para el handler del modal de agregar cliente
        window.pedAplicarPersonaAPedido = pedAplicarPersonaAPedido;

        // === Autocomplete por documento ==================================
        $('#ped-ci-rif-number-field').on('input', function () {
            var query = $(this).val();
            clearTimeout(pedAutocompleteTimeout);
            if (query.length < 6) {
                $('#ped-cliente-autocomplete-list').empty().hide();
                window.pedShowLoading(false);
                return;
            }
            if (!$('#ped-wiz-cliente-id-field').val()) window.pedShowLoading(true);

            pedAutocompleteTimeout = setTimeout(function () {
                $.ajax({
                    url: '/personas-search',
                    data: { q: query },
                    complete: function () { window.pedShowLoading(false); },
                    success: function (personas) {
                        var html = '';
                        if (personas.length) {
                            personas.forEach(function (p, idx) {
                                var isJuridico     = p.tipo_documento === 'J-' || p.tipo_documento === 'G-';
                                var nombreCompleto = isJuridico && p.razon_social
                                    ? p.razon_social
                                    : (p.apellido ? p.nombre + ' ' + p.apellido : p.nombre);
                                var badges = renderAcRoleBadges(p.roles);
                                html += '<button type="button" class="list-group-item list-group-item-action ped-persona-ac-item" data-idx="' + idx + '">' +
                                    '<div class="d-flex justify-content-between align-items-center flex-wrap gap-1">' +
                                    '<div><span class="fw-semibold">' + (p.documento || 'Sin documento') + '</span>' +
                                    '<span class="text-muted"> — ' + nombreCompleto + '</span>' +
                                    '<small class="text-muted d-block">' + (p.email || 'Sin email') + '</small></div>' +
                                    '<div>' + badges + '</div></div></button>';
                            });
                            $('#ped-cliente-autocomplete-list').data('personas', personas);
                        } else {
                            html = '<div class="list-group-item disabled">No se encontraron registros</div>';
                            $('#ped-cliente-autocomplete-list').removeData('personas');
                        }
                        $('#ped-cliente-autocomplete-list').html(html).show();
                    }
                });
            }, 300);
        });

        // Selección de persona de la lista
        $(document).on('click', '.ped-persona-ac-item', function () {
            var idx     = $(this).data('idx');
            var personas = $('#ped-cliente-autocomplete-list').data('personas') || [];
            var persona = personas[idx];
            if (!persona) return;

            // Caso 1: ya es cliente
            if (persona.cliente_id) {
                pedAplicarPersonaAPedido(persona, persona.cliente_id);
                return;
            }

            // Caso 2: existe en sistema pero no es cliente → confirmar
            var rolesTexto     = rolesLabelLegible(persona.roles);
            var nombreMostrar  = (persona.tipo_documento === 'J-' || persona.tipo_documento === 'G-') && persona.razon_social
                ? persona.razon_social
                : (persona.nombre + ' ' + (persona.apellido || '')).trim();

            Swal.fire({
                title: '¿Crear cliente con datos existentes?',
                html: '<strong>' + nombreMostrar + '</strong> está registrado como <strong>' + rolesTexto +
                      '</strong> pero aún no es cliente.<br><br>¿Deseas crear el cliente reutilizando estos datos?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="ri-check-line me-1"></i>Sí, crear cliente',
                cancelButtonText: 'Cancelar',
                customClass: { confirmButton: 'btn btn-success w-xs me-2', cancelButton: 'btn btn-light w-xs' },
                buttonsStyling: false
            }).then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: '/clientes/from-persona/' + persona.persona_id,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (resp) {
                        if (resp.success && resp.cliente_id) {
                            pedAplicarPersonaAPedido(persona, resp.cliente_id);
                            Swal.fire({
                                icon: 'success',
                                title: resp.reused ? '¡Listo!' : 'Cliente creado',
                                text: resp.message,
                                showConfirmButton: false,
                                timer: 1600
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: resp.message || 'No se pudo crear el cliente.' });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({ icon: 'error', title: 'Error', text: (xhr.responseJSON && xhr.responseJSON.message) || 'Error al crear el cliente.' });
                    }
                });
            });
        });

        // Ocultar lista al hacer click fuera
        $(document).on('click', function (e) {
            if (!$(e.target).closest('#ped-ci-rif-number-field, #ped-cliente-autocomplete-list').length) {
                $('#ped-cliente-autocomplete-list').empty().hide();
            }
        });

        // Botón "Cambiar cliente"
        $(document).on('click', '#ped-cliente-change-btn', function (e) {
            e.preventDefault();
            window.pedResetearCliente();
            $('#ped-ci-rif-number-field').val('').trigger('focus');
        });

        // === Chips de prioridad ==========================================
        $(document).on('click', '.ped-priority-chip', function () {
            var $b = $(this), val = $b.data('value');
            $('.ped-priority-chip').removeClass('is-active').attr('aria-checked', 'false');
            $b.addClass('is-active').attr('aria-checked', 'true');
            $('#ped-prioridad-field').val(val).trigger('change');
        });
        $(document).on('change', '#ped-prioridad-field', function () {
            var val = $(this).val() || 'Normal';
            $('.ped-priority-chip').removeClass('is-active').attr('aria-checked', 'false');
            $('.ped-priority-chip[data-value="' + val + '"]').addClass('is-active').attr('aria-checked', 'true');
        });

        // === Chips de estado (solo modo edición) ========================
        $(document).on('click', '.ped-estado-chip', function () {
            var $b = $(this), val = $b.data('value');
            $('.ped-estado-chip').removeClass('is-active').attr('aria-checked', 'false');
            $b.addClass('is-active').attr('aria-checked', 'true');
            $('#ped-estado-field').val(val).trigger('change');
        });
        $(document).on('change', '#ped-estado-field', function () {
            var val = $(this).val();
            if (!val) return;
            $('.ped-estado-chip').removeClass('is-active').attr('aria-checked', 'false');
            $('.ped-estado-chip[data-value="' + val + '"]').addClass('is-active').attr('aria-checked', 'true');
        });

        // === Reset al abrir en modo crear ================================
        var $wizModal = $(document).find('#pedidoForm').closest('.modal');
        if (!$wizModal.length) $wizModal = $('#showModal');

        $wizModal.on('show.bs.modal', function () {
            if (!$('#ped-wiz-id-field').val()) {
                window.pedResetearCliente();
                var todayVal = new Date().toISOString().slice(0, 10);
                $('#ped-fecha-pedido-field').val(todayVal);
                $('#ped-fecha-entrega-field').val('');
                $('.ped-priority-chip').removeClass('is-active').attr('aria-checked', 'false');
                $('.ped-priority-chip[data-value="Normal"]').addClass('is-active').attr('aria-checked', 'true');
                $('#ped-prioridad-field').val('Normal');
            }
        });

        // === Abrir modal crear cliente ====================================
        $(document).on('click', '#ped-open-add-cliente-modal', function () {
            document.getElementById('clienteFormCotizacion').reset();
            $('#id-field-cliente').val('');
            $('#modalClienteTitle').text('Crear Cliente');
            $('#add-btn-cliente').show();
            $('#documento-prefix-field-cliente').val('V-');
            $('#telefono-prefix-field-cliente').val('0424');
            $('#estatus-field-cliente').prop('checked', true);
            $('#estatus-label-cliente').text('Activo');
            $('#ciudad-field-cliente').html('<option value="">Primero seleccione un estado</option>');
            $('#tipo_cliente-field-cliente').val('');
            pedToggleClienteFields();
            $('#modalAddCliente').modal('show');
        });

        // === Lógica Natural vs Jurídico/Gubernamental ====================
        function pedToggleClienteFields() {
            var tipo        = $('#tipo_cliente-field-cliente').val();
            var $prefix     = $('#documento-prefix-field-cliente');
            var $docInput   = $('#documento-number-field-cliente');

            if (tipo === 'juridico') {
                $('#campos-persona-natural-cliente').addClass('d-none');
                $('#nombre-field-cliente').prop('required', false).prop('disabled', true).val('');
                $('#apellido-field-cliente').prop('required', false).prop('disabled', true).val('');
                $('#campos-razon-social-cliente').removeClass('d-none');
                $('#razon-social-field-cliente').prop('required', true).prop('disabled', false);
                $prefix.html('<option value="J-">J-</option>').prop('disabled', true);
                $docInput.attr('maxlength', '9');
            } else if (tipo === 'gubernamental') {
                $('#campos-persona-natural-cliente').addClass('d-none');
                $('#nombre-field-cliente').prop('required', false).prop('disabled', true).val('');
                $('#apellido-field-cliente').prop('required', false).prop('disabled', true).val('');
                $('#campos-razon-social-cliente').removeClass('d-none');
                $('#razon-social-field-cliente').prop('required', true).prop('disabled', false);
                $prefix.html('<option value="G-">G-</option>').prop('disabled', true);
                $docInput.attr('maxlength', '9');
            } else {
                $('#campos-persona-natural-cliente').removeClass('d-none');
                $('#nombre-field-cliente').prop('required', true).prop('disabled', false);
                $('#apellido-field-cliente').prop('required', true).prop('disabled', false);
                $('#campos-razon-social-cliente').addClass('d-none');
                $('#razon-social-field-cliente').prop('required', false).prop('disabled', true).val('');
                $prefix.html('<option value="V-">V-</option><option value="E-">E-</option>').prop('disabled', false);
                $docInput.attr('maxlength', '8');
            }
        }
        $(document).on('change', '#tipo_cliente-field-cliente', pedToggleClienteFields);

        // Dropdown municipios
        $(document).on('change', '#estado_territorial-field-cliente', function () {
            var estado      = $(this).val();
            var municipios  = typeof getMunicipios === 'function' ? getMunicipios(estado) : [];
            var $ciudad     = $('#ciudad-field-cliente');
            $ciudad.empty();
            if (!estado) {
                $ciudad.append('<option value="">Primero seleccione un estado</option>');
            } else {
                $ciudad.append('<option value="">Seleccione municipio</option>');
                municipios.forEach(function (m) {
                    $ciudad.append('<option value="' + m + '">' + m + '</option>');
                });
            }
        });

        // Limpiar validaciones al abrir el modal de cliente
        $('#modalAddCliente').on('show.bs.modal', function () {
            $(this).find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
            $(this).find('.invalid-feedback').hide();
        });

        // Validaciones en tiempo real del formulario de cliente
        $(document).on('input', '#nombre-field-cliente', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });
        $(document).on('input', '#apellido-field-cliente', function () {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });
        $(document).on('input', '#documento-number-field-cliente', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });
        $(document).on('input', '#telefono-number-field-cliente', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);
        });
        $(document).on('change', '#estatus-field-cliente', function () {
            $('#estatus-label-cliente').text($(this).is(':checked') ? 'Activo' : 'Inactivo');
        });

        // === Guardar cliente nuevo desde el wizard de pedidos ============
        $(document).on('click', '#add-btn-cliente', function (e) {
            e.preventDefault();

            // Concatenar campos compuestos antes de validar
            var documentoCompleto = $('#documento-prefix-field-cliente').val() + $('#documento-number-field-cliente').val();
            var telefonoCompleto  = $('#telefono-prefix-field-cliente').val() + '-' + $('#telefono-number-field-cliente').val();
            $('#documento-field-cliente').val(documentoCompleto);
            $('#telefono-field-cliente').val(telefonoCompleto);

            // Validar apellido explícitamente
            var tipo     = $('#tipo_cliente-field-cliente').val();
            var esNatural = tipo === 'natural' || tipo === '';
            if (esNatural) {
                var apellido = $('#apellido-field-cliente').val().trim();
                if (apellido.length < 2) {
                    $('#apellido-field-cliente').addClass('is-invalid');
                    Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'El apellido es obligatorio (mínimo 2 caracteres).' });
                    return;
                }
                $('#apellido-field-cliente').removeClass('is-invalid');
            }

            // Validar dirección
            var direccion = $('#direccion-field-cliente').val().trim();
            if (direccion.length < 5) {
                $('#direccion-field-cliente').addClass('is-invalid');
                Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'La dirección es obligatoria (mínimo 5 caracteres).' });
                return;
            }
            $('#direccion-field-cliente').removeClass('is-invalid');

            // Validar usando checkValidity nativo
            var form = document.getElementById('clienteFormCotizacion');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            $(this).prop('disabled', true);

            var formData = $('#clienteFormCotizacion').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/clientes',
                type: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success', title: '¡Éxito!',
                        text: response.message || 'Cliente creado exitosamente.',
                        showConfirmButton: false, timer: 1800
                    });
                    $('#modalAddCliente').modal('hide');
                    $('#add-btn-cliente').prop('disabled', false);

                    // Construir objeto persona a partir de los campos del formulario
                    var esJuridico = tipo === 'juridico' || tipo === 'gubernamental';
                    var personaCreada = {
                        documento:       documentoCompleto,
                        tipo_documento:  $('#documento-prefix-field-cliente').val(),
                        nombre:          esJuridico ? '' : $('#nombre-field-cliente').val(),
                        apellido:        esJuridico ? '' : $('#apellido-field-cliente').val(),
                        razon_social:    esJuridico ? ($('#razon-social-field-cliente').val()) : '',
                        email:           $('#email-field-cliente').val().trim(),
                        telefono:        telefonoCompleto,
                        roles:           ['cliente'],
                        persona_id:      response.persona_id || null
                    };
                    var clienteId = response.cliente_id || response.id || null;
                    pedAplicarPersonaAPedido(personaCreada, clienteId);
                },
                error: function (xhr) {
                    $('#add-btn-cliente').prop('disabled', false);
                    var msg = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errs = xhr.responseJSON.errors;
                        msg = Object.values(errs).map(function (v) { return v[0]; }).join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else {
                        msg = 'Ocurrió un error al crear el cliente.';
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        });

    })();


    // ╔══════════════════════════════════════════════════════════════════
    // ║ PEDIDO WIZARD — PASO 2: Productos
    // ║ Estado en memoria, CRUD de productos, importar desde cotización
    // ╚══════════════════════════════════════════════════════════════════
    (function () {
        'use strict';

        var pedProdItems = [];
        var pedProdSearchTimeout = null;
        var pedProdCatalogo = window.products || [];

        var pedTallasCatalogo = @json($tallas->mapWithKeys(function ($t) {
            return [$t->id => ($t->etiqueta ?: $t->nombre)];
        }));
        var pedColoresCatalogo = @json($colores->mapWithKeys(function ($c) {
            return [$c->id => $c->nombre];
        }));

        function pedFmt(n) {
            return '$' + parseFloat(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function pedRecalcularSubtotal() {
            var cant   = parseFloat($('#ped-prod-cantidad-field').val()) || 0;
            var precio = parseFloat($('#ped-prod-precio-field').val()) || 0;
            $('#ped-prod-subtotal-display').text(pedFmt(cant * precio));
        }

        function pedRecalcularTotal() {
            var total = pedProdItems.reduce(function (acc, it) { return acc + (it.subtotal || 0); }, 0);
            $('#ped-kpi-lineas').text(pedProdItems.length);
            $('#ped-kpi-total').text(pedFmt(total));
            window.pedProdState = { items: pedProdItems, total: total };
        }

        function pedRenderProductos() {
            var $list  = $('#ped-productos-list');
            var $empty = $('#ped-productos-empty');
            var $row   = $('#ped-agregar-prod-row');

            $list.empty();
            pedRecalcularTotal();

            if (!pedProdItems.length) {
                $empty.show();
                $row.attr('hidden', true);
                return;
            }
            $empty.hide();
            $row.removeAttr('hidden');

            pedProdItems.forEach(function (item, idx) {
                var badge = item.heredado_cotizacion_id
                    ? '<span class="ped-inherited-badge ms-1">Heredado de #' + item.heredado_cotizacion_id + '</span>'
                    : '';
                var tallaAttr = item.talla_label
                    ? '<span class="ped-prod-attr"><i class="ri-ruler-2-line me-1"></i>' + item.talla_label + '</span>'
                    : '';
                var colorAttr = item.color_label
                    ? '<span class="ped-prod-attr"><i class="ri-palette-line me-1"></i>' + item.color_label + '</span>'
                    : '';
                $list.append(
                    '<div class="ped-prod-card">' +
                    '<div class="ped-prod-icon"><i class="ri-shopping-bag-3-line"></i></div>' +
                    '<div class="ped-prod-meta">' +
                    '<p class="ped-prod-nombre mb-0">' + item.nombre + badge + '</p>' +
                    '<div class="ped-prod-attrs mt-1">' +
                    '<span class="ped-prod-attr">Cant: ' + item.cantidad + '</span>' +
                    tallaAttr + colorAttr +
                    '<span class="ped-prod-attr">' + pedFmt(item.precio_unitario) + ' c/u</span>' +
                    '</div></div>' +
                    '<div class="ped-prod-subtotal">' + pedFmt(item.subtotal) + '</div>' +
                    '<div class="ped-prod-actions">' +
                    '<button type="button" class="btn btn-outline-primary btn-sm ped-prod-edit-btn" data-idx="' + idx + '" title="Editar"><i class="ri-pencil-line"></i></button>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm ped-prod-del-btn" data-idx="' + idx + '" title="Eliminar"><i class="ri-delete-bin-line"></i></button>' +
                    '</div></div>'
                );
            });
        }

        function pedAbrirModalProducto(idx) {
            var item = (idx !== null && idx !== undefined && pedProdItems[idx]) ? pedProdItems[idx] : null;
            $('#ped-prod-edit-idx').val(idx !== null && idx !== undefined ? idx : '');
            if (item) {
                $('#ped-agregar-prod-title').text('Editar Producto');
                $('#ped-prod-search-input').val(item.nombre);
                $('#ped-prod-id-field').val(item.producto_id || '');
                $('#ped-prod-nombre-field').val(item.nombre);
                $('#ped-prod-cantidad-field').val(item.cantidad);
                $('#ped-prod-talla-field').val(item.talla_id || '');
                $('#ped-prod-color-field').val(item.color_id || '');
                $('#ped-prod-precio-field').val(item.precio_unitario);
                pedRecalcularSubtotal();
            } else {
                $('#ped-agregar-prod-title').text('Agregar Producto');
                $('#ped-prod-search-input').val('');
                $('#ped-prod-id-field').val('');
                $('#ped-prod-nombre-field').val('');
                $('#ped-prod-cantidad-field').val(1);
                $('#ped-prod-talla-field').val('');
                $('#ped-prod-color-field').val('');
                $('#ped-prod-precio-field').val('');
                $('#ped-prod-subtotal-display').text('$0.00');
            }
            $('#ped-prod-search-list').empty().hide();
            $('#ped-agregar-producto-modal').modal('show');
        }

        // Autocomplete de productos del catálogo (window.products)
        $('#ped-prod-search-input').on('input', function () {
            var q = $(this).val().toLowerCase().trim();
            $('#ped-prod-id-field').val('');
            $('#ped-prod-nombre-field').val('');
            clearTimeout(pedProdSearchTimeout);
            if (!q) { $('#ped-prod-search-list').empty().hide(); return; }
            pedProdSearchTimeout = setTimeout(function () {
                var matches = pedProdCatalogo.filter(function (p) {
                    return (p.nombre || '').toLowerCase().indexOf(q) !== -1 ||
                           (p.codigo || '').toLowerCase().indexOf(q) !== -1;
                }).slice(0, 10);
                var html = '';
                if (matches.length) {
                    matches.forEach(function (p) {
                        html += '<button type="button" class="list-group-item list-group-item-action ped-prod-ac-item"' +
                            ' data-id="' + p.id + '"' +
                            ' data-nombre="' + String(p.nombre || '').replace(/"/g, '&quot;') + '"' +
                            ' data-precio="' + (p.precio_base || 0) + '">' +
                            '<span class="fw-semibold">' + (p.nombre || '') + '</span>' +
                            (p.codigo ? '<small class="text-muted ms-2">' + p.codigo + '</small>' : '') +
                            '</button>';
                    });
                } else {
                    html = '<div class="list-group-item disabled text-muted small">Sin resultados</div>';
                }
                $('#ped-prod-search-list').html(html).show();
            }, 200);
        });

        $(document).on('click', '.ped-prod-ac-item', function () {
            var nombre = $(this).data('nombre');
            var precio = $(this).data('precio');
            $('#ped-prod-id-field').val($(this).data('id'));
            $('#ped-prod-nombre-field').val(nombre);
            $('#ped-prod-search-input').val(nombre);
            if (!$('#ped-prod-precio-field').val()) {
                $('#ped-prod-precio-field').val(parseFloat(precio).toFixed(2));
            }
            $('#ped-prod-search-list').empty().hide();
            pedRecalcularSubtotal();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('#ped-prod-search-input, #ped-prod-search-list').length) {
                $('#ped-prod-search-list').empty().hide();
            }
        });

        // Recalcular subtotal al cambiar cantidad o precio
        $(document).on('input', '#ped-prod-cantidad-field, #ped-prod-precio-field', pedRecalcularSubtotal);

        // Guardar producto (agregar o editar)
        $(document).on('click', '#ped-prod-guardar-btn', function () {
            var nombre   = $('#ped-prod-nombre-field').val() || $('#ped-prod-search-input').val().trim();
            var cantidad = parseInt($('#ped-prod-cantidad-field').val(), 10);
            var precio   = parseFloat($('#ped-prod-precio-field').val());
            var tallaId  = $('#ped-prod-talla-field').val() || null;
            var colorId  = $('#ped-prod-color-field').val() || null;

            if (!nombre) {
                Swal.fire({ icon: 'warning', title: 'Producto requerido',
                    text: 'Selecciona un producto del catálogo.', timer: 2000, showConfirmButton: false });
                $('#ped-prod-search-input').trigger('focus');
                return;
            }
            if (!cantidad || cantidad < 1) {
                Swal.fire({ icon: 'warning', title: 'Cantidad inválida',
                    text: 'La cantidad debe ser al menos 1.', timer: 2000, showConfirmButton: false });
                return;
            }
            if (isNaN(precio) || precio < 0) {
                Swal.fire({ icon: 'warning', title: 'Precio inválido',
                    text: 'Ingresa un precio unitario válido.', timer: 2000, showConfirmButton: false });
                return;
            }

            var item = {
                producto_id:  $('#ped-prod-id-field').val() || null,
                nombre:       nombre,
                cantidad:     cantidad,
                talla_id:     tallaId ? parseInt(tallaId, 10) : null,
                talla_label:  tallaId ? (pedTallasCatalogo[tallaId] || '') : '',
                color_id:     colorId ? parseInt(colorId, 10) : null,
                color_label:  colorId ? (pedColoresCatalogo[colorId] || '') : '',
                precio_unitario: precio,
                subtotal:     cantidad * precio,
                heredado_cotizacion_id: null
            };

            var idxStr = $('#ped-prod-edit-idx').val();
            var idx    = (idxStr !== '') ? parseInt(idxStr, 10) : null;
            if (idx !== null && pedProdItems[idx]) {
                item.heredado_cotizacion_id = pedProdItems[idx].heredado_cotizacion_id;
                pedProdItems[idx] = item;
            } else {
                pedProdItems.push(item);
            }

            pedRenderProductos();
            $('#ped-agregar-producto-modal').modal('hide');
        });

        // Editar / eliminar producto desde la lista
        $(document).on('click', '.ped-prod-edit-btn', function () {
            pedAbrirModalProducto(parseInt($(this).data('idx'), 10));
        });
        $(document).on('click', '.ped-prod-del-btn', function () {
            var idx = parseInt($(this).data('idx'), 10);
            pedProdItems.splice(idx, 1);
            pedRenderProductos();
        });

        // Abrir modal agregar
        $(document).on('click', '#ped-btn-agregar-prod, #ped-btn-agregar-prod-empty', function () {
            pedAbrirModalProducto(null);
        });

        // Importar: activar flag y abrir modal de selección de cotización
        $(document).on('click', '#ped-btn-importar-cotizacion', function () {
            window.pedWizardImportMode = true;
            $('#seleccionarCotizacionModal').modal('show');
        });

        // Hidratar productos desde cotización seleccionada
        window.pedHidratarDesde = function (cotizacionId) {
            window.pedWizardImportMode = false;
            $.ajax({
                url: '{{ route("cotizaciones.datosParaPedido", ":id") }}'.replace(':id', cotizacionId),
                method: 'GET',
                success: function (data) {
                    if (!data || !data.productos || !data.productos.length) {
                        Swal.fire({ icon: 'info', title: 'Sin productos',
                            text: 'La cotización no tiene productos para importar.',
                            timer: 2400, showConfirmButton: false });
                        return;
                    }
                    data.productos.forEach(function (p) {
                        var cant    = parseInt(p.cantidad, 10);
                        var precio  = parseFloat(p.precio_unitario);
                        pedProdItems.push({
                            producto_id:  p.producto_id,
                            nombre:       p.producto_nombre,
                            cantidad:     cant,
                            talla_id:     p.talla_id || null,
                            talla_label:  p.talla_id ? (pedTallasCatalogo[p.talla_id] || '') : '',
                            color_id:     p.color_id || null,
                            color_label:  p.color_id ? (pedColoresCatalogo[p.color_id] || '') : '',
                            precio_unitario: precio,
                            subtotal:     cant * precio,
                            heredado_cotizacion_id: cotizacionId
                        });
                    });
                    pedRenderProductos();
                    Swal.fire({
                        icon: 'success', title: 'Productos importados',
                        text: data.productos.length + ' producto(s) importados desde cotización #' + cotizacionId + '.',
                        timer: 2200, showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    window.pedWizardImportMode = false;
                    var msg = (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message))
                        || 'No se pudo obtener los datos de la cotización.';
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                }
            });
        };

        // Reset al abrir el wizard en modo crear
        var $wizModal = $(document).find('#pedidoForm').closest('.modal');
        if (!$wizModal.length) $wizModal = $('#showModal');
        $wizModal.on('show.bs.modal', function () {
            if (!$('#ped-wiz-id-field').val()) {
                pedProdItems = [];
                pedRenderProductos();
            }
        });

        // Estado global expuesto para paso 3 y validateStep
        window.pedProdState = { items: pedProdItems, total: 0 };

    })();

});
</script>
