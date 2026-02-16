@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mt-5 pt-5">
                <div class="mb-4">
                    <lord-icon src="https://cdn.lordicon.com/vyukcgvf.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:120px;height:120px"></lord-icon>
                </div>
                <h1 class="display-1 fw-bold text-danger">500</h1>
                <h3 class="text-uppercase mb-3">Error del Servidor</h3>
                <p class="text-muted mb-4 fs-15">Ocurrió un error interno en el servidor.<br>
                    El equipo técnico ha sido notificado. Por favor, intenta de nuevo más tarde.</p>
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    <i class="ri-home-4-line me-1"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
@endsection