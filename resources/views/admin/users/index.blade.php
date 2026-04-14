@extends('admin.layouts.app')

@push('styles')
    <!-- Sweet Alert css-->
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Se eliminó la referencia a los estilos de botones -->
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Usuarios</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Seguridad</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- Estilos en public/assets/css/custom.css — sección "MÓDULO MAESTROS — Usuarios" --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-maestros">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Listado de Usuarios</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-3">
                            <!-- Buscador Personalizado -->
                            <div class="search-box">
                                <input type="text" class="form-control form-control-sm" id="custom-search-input"
                                    placeholder="Buscar usuario...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Agregar Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="users-table" class="table table-bordered table-striped table-sm align-middle table-operativa table-maestro">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles del Usuario -->
    <div class="modal fade atlantico-modal" id="viewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Detalles del Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Avatar centrado -->
                    <div class="text-center mb-4" id="user-avatar-container">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px; background: linear-gradient(135deg, #1e3c72 0%, #00d9a5 100%); padding: 3px;">
                            <img id="user-avatar" src="/assets/images/users/user-dummy-img.jpg" alt="Avatar del usuario"
                                class="rounded-circle bg-white" style="width: 94px; height: 94px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Card Información del Usuario -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0" style="background: rgba(30, 60, 114, 0.1);">
                            <h6 class="mb-0" style="color: #1e3c72;">
                                <i class="ri-information-line me-2"></i>Información del Usuario
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-user-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Nombre</small>
                                            <span class="fw-semibold" id="view-name">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-mail-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Email</small>
                                            <span class="fw-semibold" id="view-email">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-shield-user-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Rol</small>
                                            <span class="fw-semibold" id="view-role">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background: rgba(30, 60, 114, 0.1);">
                                            <i class="ri-calendar-line" style="color: #1e3c72;"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Fecha Registro</small>
                                            <span class="fw-semibold" id="view-created">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar/editar -->
    <div class="modal fade atlantico-modal" id="showModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="modalTitle">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" id="id-field" />

                        <div class="modal-form-section">
                            <div class="modal-form-section-title"><i class="ri-shield-keyhole-line"></i>Credenciales de Acceso</div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-forms.input name="name" label="Nombre" placeholder="Nombre completo" required />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input name="email" label="Email" type="email" placeholder="correo@ejemplo.com" required />
                                </div>
                            </div>

                            <div class="row mb-0" id="password-group">
                                <div class="col-md-6">
                                    <x-forms.input name="password" label="Contraseña" type="password"
                                        placeholder="Contraseña"
                                        hint="Dejar en blanco para mantener la actual al editar" />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.input name="password_confirmation" label="Confirmar Contraseña" type="password"
                                        placeholder="Confirmar Contraseña" required />
                                </div>
                            </div>
                        </div>

                        <div class="modal-form-section mb-0">
                            <div class="modal-form-section-title"><i class="ri-user-settings-line"></i>Perfil de Usuario</div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <x-forms.select name="role" label="Rol" required
                                        :options="['Administrador' => 'Administrador', 'Supervisor' => 'Supervisor']"
                                        placeholder="Seleccione un rol" />
                                </div>
                                <div class="col-md-6">
                                    <x-forms.select name="estado" label="Estado" :options="['1' => 'Activo', '0' => 'Inactivo']"
                                        placeholder="" value="1" />
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <label for="field-avatar" class="form-label">Avatar</label>
                                    <input type="file" id="field-avatar" name="avatar" class="form-control"
                                        accept="image/*" />
                                    <div id="avatar-preview" class="mt-2 text-center" style="display: none;">
                                        <img src="" alt="Vista previa del avatar" class="img-fluid rounded-circle"
                                            style="max-width: 100px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light border-0">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Cerrar
                            </button>
                            <x-ui.button-save id="add-btn" text="Agregar" icon="ri-add-line" loading-text="Agregando..." />
                            <x-ui.button-save id="edit-btn" text="Actualizar" icon="ri-save-line"
                                loading-text="Actualizando..." style="display: none;" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function generateButtons(userId) {
                return '<div class="d-flex gap-1 justify-content-center">' +
                    '<button class="btn btn-sm btn-soft-secondary view-item-btn" data-id="' + userId + '" title="Ver" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-eye-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-success edit-item-btn" data-id="' + userId + '" title="Editar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-pencil-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '<button class="btn btn-sm btn-soft-danger remove-item-btn" data-id="' + userId + '" title="Eliminar" style="padding:0.2rem 0.45rem;">' +
                    '<i class="ri-delete-bin-fill" style="font-size:13px;"></i>' +
                    '</button>' +
                    '</div>';
            }

            function renderEllipsis(value) {
                if (!value) return '<span class="text-muted">—</span>';
                return '<span title="' + value + '" style="cursor:default;">' + value + '</span>';
            }

            var table = $('#users-table').DataTable({
                ajax: {
                    url: "{{ route('users.data') }}",
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: 'name',
                        render: function (data, type, row) {
                            return `
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <div class="avatar-xs">
                                                                            <img src="${row.avatar || '/assets/images/users/user-dummy-img.jpg'}" alt="Avatar" class="img-fluid rounded-circle">
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 text-truncate" title="${data || ''}">${data || '—'}</div>
                                                                </div>
                                                            `;
                        }
                    },
                    {
                        data: 'email',
                        render: function (data) {
                            return renderEllipsis(data);
                        }
                    },
                    {
                        data: 'role',
                        render: function (data) {
                            if (data === 'Administrador') {
                                return '<span class="badge-tipo badge-rol-admin"><i class="ri-shield-star-line"></i> Administrador</span>';
                            } else if (data === 'Supervisor') {
                                return '<span class="badge-tipo badge-rol-supervisor"><i class="ri-shield-user-line"></i> Supervisor</span>';
                            }
                            return data || 'Sin rol';
                        }
                    },
                    {
                        data: 'estado',
                        render: function (data, type, row) {
                            return data == 1
                                ? '<span class="badge-status badge-status-activo"><i class="ri-checkbox-circle-line"></i> Activo</span>'
                                : '<span class="badge-status badge-status-inactivo"><i class="ri-close-circle-line"></i> Inactivo</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return generateButtons(row.id);
                        }
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                dom: 'rtip',
                responsive: false,
                language: lenguajeData
            });

            // Buscador personalizado
            $('#custom-search-input').on('keyup', function () {
                table.search(this.value).draw();
            });

            function validarFormularioUsuario() {
                let esValido = true;
                let esCreacion = $('#id-field').val() === '';

                let $nombre = $('#field-name');
                let nombreVal = $nombre.val().trim();
                if (!nombreVal) {
                    marcarInvalido($nombre, 'El nombre es obligatorio.');
                    esValido = false;
                } else if (nombreVal.length < 2) {
                    marcarInvalido($nombre, 'El nombre debe tener al menos 2 caracteres.');
                    esValido = false;
                } else {
                    marcarValido($nombre);
                }

                let $email = $('#field-email');
                let emailVal = $email.val().trim();
                let emailRegex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
                if (!emailVal) {
                    marcarInvalido($email, 'El email es obligatorio.');
                    esValido = false;
                } else if (!emailRegex.test(emailVal)) {
                    marcarInvalido($email, 'Ingrese un email válido (ej: usuario@dominio.com).');
                    esValido = false;
                } else {
                    marcarValido($email);
                }

                let $role = $('#field-role');
                if (!$role.val()) {
                    marcarInvalido($role, 'El rol es obligatorio.');
                    esValido = false;
                } else {
                    marcarValido($role);
                }

                if (esCreacion) {
                    let $pass = $('#field-password');
                    let passVal = $pass.val();
                    if (passVal.length === 0) {
                        marcarInvalido($pass, 'La contraseña es obligatoria.');
                        esValido = false;
                    } else {
                        let errorContrasena = validarContrasena(passVal);
                        if (errorContrasena) {
                            marcarInvalido($pass, errorContrasena);
                            esValido = false;
                        } else {
                            marcarValido($pass);
                        }
                    }

                    let $confirm = $('#field-password_confirmation');
                    let confirmVal = $confirm.val();
                    if (!confirmVal) {
                        marcarInvalido($confirm, 'La confirmación de contraseña es obligatoria.');
                        esValido = false;
                    } else if (confirmVal !== passVal) {
                        marcarInvalido($confirm, 'Las contraseñas no coinciden.');
                        esValido = false;
                    } else {
                        marcarValido($confirm);
                    }
                }

                return esValido;
            }

            function resetForm() {
                $('#modalTitle').text('Agregar Usuario');
                $('#userForm')[0].reset();
                $('#userForm input[type="hidden"]').val('');
                $('#avatar-preview').hide().find('img').attr('src', '');
                $('#add-btn').show();
                $('#edit-btn').hide();
                $('#password-group').show();
                $('#field-password').prop('required', true); // Requerir contraseña al crear
                $('#field-password_confirmation').prop('required', true);

                // Reiniciar validaciones
                $('#userForm').find('input, select, textarea').removeClass('is-invalid is-valid');
                $('#userForm').find('.invalid-feedback').hide();
            }

            function setEditMode() {
                $("#modalTitle").text("Actualizar Usuario");
                $("#add-btn").hide();
                $("#edit-btn").show();
                $('#password-group').hide(); // Ocultar campo de contraseña al editar
                $('#field-password').prop('required', false); // No requerir contraseña al editar
                $('#field-password_confirmation').prop('required', false);
            }

            // Función para mostrar vista previa de imágenes
            function readURL(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(previewId).find('img').attr('src', e.target.result);
                        $(previewId).show();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Vista previa de imágenes al seleccionarlas
            $('#field-avatar').change(function () {
                readURL(this, '#avatar-preview');
            });

            $("#create-btn").click(function () {
                resetForm();
                // Ocultar vista previa
                $('#avatar-preview').hide();
            });

            $("#showModal").on('hidden.bs.modal', function () {
                resetForm();
            });

            $('#add-btn').click(function (e) {
                e.preventDefault();

                if (!validarFormularioUsuario()) {
                    return;
                }

                $("#userForm").submit();
            });

            $("#userForm").on("submit", function (e) {
                e.preventDefault();
                var id = $("#id-field").val();
                var url = id ? "{{ route('users.update', ':id') }}".replace(':id', id) : "{{ route('users.store') }}";
                var method = id ? "PUT" : "POST";

                var formData = new FormData(this);
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("#showModal").modal("hide");
                        $("#userForm").trigger("reset");
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs me-2',
                                cancelButton: 'btn btn-danger w-xs'
                            },
                            buttonsStyling: false,
                            showCloseButton: true,
                            showConfirmButton: true,
                            timer: 2000
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message,
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

            $(document).on("click", ".view-item-btn", function () {
                var id = $(this).data("id");
                $.get("{{ route('users.show', '') }}/" + id, function (data) {
                    $("#viewModal").modal("show");
                    $("#view-name").text(data.name);
                    $("#view-email").text(data.email);
                    $("#view-role").text(data.role || 'Sin rol');
                    $("#view-created").text(data.created_at);

                    // Mostrar avatar
                    if (data.avatar) {
                        $("#user-avatar").attr("src", data.avatar);
                    } else {
                        $("#user-avatar").attr("src", "/assets/images/users/user-dummy-img.jpg");
                    }


                });
            });

            $(document).on("click", ".edit-item-btn", function () {
                var id = $(this).data("id");

                $.get("{{ route('users.edit', ':id') }}".replace(':id', id), function (data) {
                    setEditMode();
                    $("#id-field").val(data.id);
                    $("#field-name").val(data.name);
                    $("#field-email").val(data.email);
                    $("#field-role").val(data.role);

                    // Mostrar las imágenes existentes si las hay
                    if (data.avatar) {
                        $("#avatar-preview img").attr('src', data.avatar);
                        $("#avatar-preview").show();
                    }


                    $("#showModal").modal("show");
                });
            });

            $(document).on("click", ".remove-item-btn", function () {
                var id = $(this).data("id");
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
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
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('users.destroy', '') }}/" + id,
                            type: "DELETE",
                            success: function (response) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: response.message,
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true,
                                    timer: 2000
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                    customClass: {
                                        confirmButton: 'btn btn-primary w-xs me-2',
                                        cancelButton: 'btn btn-danger w-xs'
                                    },
                                    buttonsStyling: false,
                                    showCloseButton: true
                                });
                            }
                        });
                    }
                });
            });

            $("#create-btn").click(function () {
                $("#id-field").val("");
                $("#userForm").trigger("reset");
                $(".modal-title").text("Agregar Usuario");
                $("#add-btn").show();
                $("#edit-btn").hide();
            });

            $("#edit-btn").on("click", function () {
                $("#userForm").submit();
            });

            // Validación onblur para nombre
            $(document).on('blur', '#field-name', function () {
                let value = $(this).val().trim();
                if (value.length === 0) {
                    marcarInvalido($(this), 'El nombre es obligatorio.');
                } else if (value.length < 2) {
                    marcarInvalido($(this), 'El nombre debe tener al menos 2 caracteres.');
                } else {
                    marcarValido($(this));
                }
            });

            // Validación onblur para email con verificación de duplicado
            $(document).on('blur', '#field-email', function () {
                let value = $(this).val().trim();
                let $input = $(this);
                let emailRegex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;

                if (value.length === 0) {
                    limpiarValidacion($input);
                    return;
                }

                if (!emailRegex.test(value)) {
                    marcarInvalido($input, 'Ingrese un email válido (ej: usuario@dominio.com).');
                    return;
                }

                let excludeId = $('#id-field').val();
                $.ajax({
                    url: "{{ route('users.check-email') }}",
                    type: "GET",
                    data: { email: value, exclude_id: excludeId },
                    success: function (response) {
                        if (response.exists) {
                            marcarInvalido($input, 'Este correo ya está registrado.');
                            $('#add-btn').prop('disabled', true);
                        } else {
                            marcarValido($input);
                            $('#add-btn').prop('disabled', false);
                        }
                    },
                    error: function () {
                        console.error('Error al verificar email');
                    }
                });
            });
        });
    </script>
@endpush