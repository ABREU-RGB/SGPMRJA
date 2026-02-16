@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mt-5 pt-5">
                <div class="mb-4">
                    <lord-icon src="https://cdn.lordicon.com/etwtznjn.json" trigger="loop"
                        colors="primary:#405189,secondary:#0ab39c" style="width:120px;height:120px"></lord-icon>
                </div>
                <h1 class="display-1 fw-bold text-danger">403</h1>
                <h3 class="text-uppercase mb-3">Acceso Denegado</h3>
                <p class="text-muted mb-4 fs-15">No tienes permisos para acceder a esta página.<br>
                    Si crees que esto es un error, contacta al administrador del sistema.</p>
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    <i class="ri-home-4-line me-1"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
@endsection