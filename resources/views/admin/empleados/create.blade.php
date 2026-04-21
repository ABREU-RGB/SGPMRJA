{{-- ============================================================
    Vista: Crear Empleado (Demo de Blade Components)
    Demuestra el uso de x-ui.card, x-forms.input,
    x-forms.select y x-ui.button-save como piezas de Lego.
    ============================================================ --}}

@extends('admin.layouts.app')
@section('title', 'Crear Empleado')

@section('content')
<div class="container-fluid">

    {{-- Breadcrumb --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Registrar Empleado</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('empleados.index') }}">Empleados</a></li>
                        <li class="breadcrumb-item active">Crear</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf

        {{-- ===================== DATOS PERSONALES ===================== --}}
        <x-ui.card title="Datos Personales" icon="ri-user-line">

            <div class="row">
                {{-- Fila 1: Nombre y Apellido --}}
                <div class="col-md-6">
                    <x-forms.input name="nombre" label="Nombre" placeholder="Ej: Santiago" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="apellido" label="Apellido" placeholder="Ej: García" required />
                </div>

                {{-- Fila 2: Cédula, Teléfono, Email --}}
                <div class="col-md-4">
                    <x-forms.input name="documento_identidad" label="Cédula" placeholder="12345678"
                        prepend='<select name="tipo_documento" class="form-select" style="max-width:80px">
                                    <option value="V-">V-</option>
                                    <option value="E-">E-</option>
                                 </select>'
                        required />
                </div>
                <div class="col-md-4">
                    <x-forms.input name="telefono" label="Teléfono" placeholder="1234567"
                        prepend='<select name="prefijo_telefono" class="form-select" style="max-width:100px">
                                    <option value="0412">0412</option>
                                    <option value="0414">0414</option>
                                    <option value="0416">0416</option>
                                    <option value="0424">0424</option>
                                    <option value="0426">0426</option>
                                 </select>'
                        maxlength="7" />
                </div>
                <div class="col-md-4">
                    <x-forms.input name="email" label="Email" type="email" placeholder="correo@ejemplo.com" />
                </div>
            </div>

        </x-ui.card>

        {{-- ===================== DATOS LABORALES ===================== --}}
        <x-ui.card title="Datos Laborales" icon="ri-briefcase-line">

            <div class="row">
                {{-- Departamento --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="field-departamento_id" class="form-label">Departamento <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select id="field-departamento_id" name="departamento_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($departamentos as $id => $nombre)
                                    <option value="{{ $id }}" @selected(old('departamento_id') == $id)>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-success"
                                data-bs-toggle="modal" data-bs-target="#addDepartamentoCreateModal"
                                title="Agregar departamento">
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                        @error('departamento_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Cargo (cascading: depende de departamento) --}}
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="field-cargo_id" class="form-label">Cargo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select id="field-cargo_id" name="cargo_id" class="form-select" required disabled>
                                <option value="">Elija un departamento</option>
                            </select>
                            <button type="button" class="btn btn-outline-success" id="add-cargo-btn-create"
                                data-bs-toggle="modal" data-bs-target="#addCargoCreateModal"
                                title="Agregar cargo" disabled>
                                <i class="ri-add-line"></i>
                            </button>
                        </div>
                        @error('cargo_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Fecha de ingreso --}}
                <div class="col-md-4">
                    <x-forms.input name="fecha_ingreso" label="Fecha de Ingreso" type="date" required />
                </div>

                {{-- Dirección --}}
                <div class="col-md-12">
                    <x-forms.input name="direccion" label="Dirección" placeholder="Dirección completa del empleado" />
                </div>
            </div>

        </x-ui.card>

        {{-- Botón Guardar --}}
        <div class="text-end mb-4">
            <a href="{{ route('empleados.index') }}" class="btn btn-light me-2">
                <i class="ri-arrow-left-line me-1"></i>Cancelar
            </a>
            <x-ui.button-save text="Guardar Empleado" loading-text="Registrando..." />
        </div>

    </form>

    {{-- Mini-Modal: Nuevo Departamento --}}
    <div class="modal fade atlantico-modal" id="addDepartamentoCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Nuevo Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label required">Nombre</label>
                        <input type="text" id="create-nuevo-departamento" class="form-control"
                            placeholder="Ej: Logística" maxlength="100" />
                        <div id="create-depto-error" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="create-guardar-departamento-btn">
                        <i class="ri-check-line me-1"></i>Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mini-Modal: Nuevo Cargo --}}
    <div class="modal fade atlantico-modal" id="addCargoCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Nuevo Cargo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2">
                        Departamento: <strong id="create-cargo-depto-label">—</strong>
                    </p>
                    <div class="mb-0">
                        <label class="form-label required">Nombre del Cargo</label>
                        <input type="text" id="create-nuevo-cargo" class="form-control"
                            placeholder="Ej: Costurera" maxlength="100" />
                        <div id="create-cargo-error" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="create-guardar-cargo-btn">
                        <i class="ri-check-line me-1"></i>Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {
    var $deptoSelect = $('#field-departamento_id');
    var $cargoSelect = $('#field-cargo_id');
    var $addCargoBtn = $('#add-cargo-btn-create');

    // Cascading: al cambiar departamento, cargar cargos vía AJAX
    $deptoSelect.on('change', function () {
        var deptoId = $(this).val();
        $cargoSelect.empty().prop('disabled', true);
        $addCargoBtn.prop('disabled', true);

        if (!deptoId) {
            $cargoSelect.append('<option value="">Elija un departamento</option>');
            return;
        }

        $cargoSelect.append('<option value="">Cargando...</option>');
        $.get("{{ route('empleados.get-cargos') }}", { departamento_id: deptoId }, function (cargos) {
            $cargoSelect.empty().prop('disabled', false);
            $addCargoBtn.prop('disabled', false);
            if (cargos.length === 0) {
                $cargoSelect.append('<option value="">Sin cargos — agregue uno con +</option>');
            } else {
                $cargoSelect.append('<option value="">Seleccione...</option>');
                cargos.forEach(function (c) {
                    $cargoSelect.append('<option value="' + c.id + '">' + c.nombre + '</option>');
                });
            }
        }).fail(function () {
            $cargoSelect.empty().append('<option value="">Error al cargar cargos</option>');
        });
    });

    // Modal departamento: limpiar al abrir
    $('#addDepartamentoCreateModal').on('shown.bs.modal', function () {
        $('#create-nuevo-departamento').val('').removeClass('is-invalid').focus();
        $('#create-depto-error').hide();
    });

    $('#create-guardar-departamento-btn').click(function () {
        var nombre = $('#create-nuevo-departamento').val().trim();
        if (nombre.length < 3) {
            $('#create-nuevo-departamento').addClass('is-invalid');
            $('#create-depto-error').text('Mínimo 3 caracteres.').show();
            return;
        }
        var $btn = $(this).prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Guardando...');
        $.ajax({
            url: "{{ route('departamentos.store') }}",
            method: 'POST',
            data: { nombre: nombre, _token: '{{ csrf_token() }}' },
            success: function (resp) {
                var dep = resp.departamento;
                $deptoSelect.append('<option value="' + dep.id + '">' + dep.nombre + '</option>');
                $deptoSelect.val(dep.id).trigger('change');
                $('#addDepartamentoCreateModal').modal('hide');
                Swal.fire({ icon: 'success', title: '¡Listo!', text: 'Departamento agregado.', showConfirmButton: false, timer: 1500 });
            },
            error: function (xhr) {
                $('#create-nuevo-departamento').addClass('is-invalid');
                $('#create-depto-error').text(xhr.responseJSON?.message || 'Error al guardar.').show();
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="ri-check-line me-1"></i>Guardar');
            }
        });
    });

    // Modal cargo: mostrar nombre de departamento al abrir
    $('#addCargoCreateModal').on('shown.bs.modal', function () {
        var deptoNombre = $deptoSelect.find('option:selected').text();
        $('#create-cargo-depto-label').text(deptoNombre);
        $('#create-nuevo-cargo').val('').removeClass('is-invalid').focus();
        $('#create-cargo-error').hide();
    });

    $('#create-guardar-cargo-btn').click(function () {
        var nombre   = $('#create-nuevo-cargo').val().trim();
        var deptoId  = $deptoSelect.val();
        if (nombre.length < 3) {
            $('#create-nuevo-cargo').addClass('is-invalid');
            $('#create-cargo-error').text('Mínimo 3 caracteres.').show();
            return;
        }
        var $btn = $(this).prop('disabled', true).html('<i class="ri-loader-4-line ri-spin me-1"></i>Guardando...');
        $.ajax({
            url: "{{ route('cargos.store') }}",
            method: 'POST',
            data: { nombre: nombre, departamento_id: deptoId, _token: '{{ csrf_token() }}' },
            success: function (resp) {
                var cargo = resp.cargo;
                $cargoSelect.append('<option value="' + cargo.id + '">' + cargo.nombre + '</option>');
                $cargoSelect.val(cargo.id);
                $('#addCargoCreateModal').modal('hide');
                Swal.fire({ icon: 'success', title: '¡Listo!', text: 'Cargo agregado.', showConfirmButton: false, timer: 1500 });
            },
            error: function (xhr) {
                $('#create-nuevo-cargo').addClass('is-invalid');
                $('#create-cargo-error').text(xhr.responseJSON?.message || 'Error al guardar.').show();
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="ri-check-line me-1"></i>Guardar');
            }
        });
    });
});
</script>
@endpush
