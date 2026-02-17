@extends('admin.layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mt-5 pt-5">
                <div class="mb-4">
                    <lord-icon src="https://cdn.lordicon.com/spxnqpau.json" trigger="loop"
                        colors="primary:#405189,secondary:#0ab39c" style="width:120px;height:120px"></lord-icon>
                </div>
                <h1 class="display-1 fw-bold text-warning">404</h1>
                <h3 class="text-uppercase mb-3">Página No Encontrada</h3>
                <p class="text-muted mb-4 fs-15">La página que buscas no existe o ha sido movida.<br>
                    Verifica la URL o regresa al inicio.</p>
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                    <i class="ri-home-4-line me-1"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </div>
@endsection