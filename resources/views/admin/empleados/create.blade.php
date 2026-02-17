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
                {{-- Fila 1: Cargo, Departamento, Fecha ingreso --}}
                <div class="col-md-4">
                    <x-forms.input name="cargo" label="Cargo" placeholder="Ej: Operario" required />
                </div>
                <div class="col-md-4">
                    <x-forms.select name="departamento" label="Departamento"
                        :options="$departamentos ?? ['Administracion' => 'Administración', 'Produccion' => 'Producción']"
                        add-button-target="#addDepartamentoModal" required />
                </div>
                <div class="col-md-4">
                    <x-forms.input name="fecha_ingreso" label="Fecha de Ingreso" type="date" required />
                </div>

                {{-- Fila 2: Dirección completa --}}
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
</div>
@endsection
