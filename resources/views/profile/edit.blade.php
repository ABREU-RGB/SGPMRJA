@extends('admin.layouts.app')

@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Perfil') }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-xxl-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Actualizar información de perfil</h4>
                                    </div>
                                    <div class="card-body">
                                        @include('profile.partials.update-profile-information-form')
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Actualizar contraseña</h4>
                                    </div>
                                    <div class="card-body">
                                        @include('profile.partials.update-password-form')
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-md-12">
                                <div class="card" id="recovery-questions-card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Preguntas de seguridad</h4>
                                    </div>
                                    <div class="card-body">
                                        @include('profile.partials.update-recovery-questions-form')
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-md-12">
                                <div class="card border-danger">
                                    <div class="card-header bg-soft-danger">
                                        <h4 class="card-title mb-0 text-danger">Eliminar cuenta</h4>
                                    </div>
                                    <div class="card-body">
                                        @include('profile.partials.delete-user-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
@if (session('warning_recovery'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Configuración de seguridad pendiente',
            html: '{!! addslashes(session('warning_recovery')) !!}<br><br><small class="text-muted">Te llevamos a la sección de preguntas de seguridad.</small>',
            confirmButtonText: 'Ir a configurar',
            customClass: { confirmButton: 'btn btn-warning w-xs' },
            buttonsStyling: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(function () {
            var card = document.getElementById('recovery-questions-card');
            if (!card) return;
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            card.classList.add('border-warning');
            card.style.transition = 'box-shadow 0.4s ease';
            card.style.boxShadow = '0 0 0 0.25rem rgba(255,193,7,.35)';
            setTimeout(function () {
                card.style.boxShadow = '';
                card.classList.remove('border-warning');
                var firstSelect = card.querySelector('select');
                if (firstSelect) firstSelect.focus();
            }, 2500);
        });
    });
</script>
@endif
@endpush
