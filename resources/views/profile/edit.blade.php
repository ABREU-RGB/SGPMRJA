@extends('admin.layouts.app')

@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* ==== Profile — Hero ==== */
        .profile-hero {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            border: 0;
            border-radius: .5rem;
            overflow: hidden;
        }
        .profile-hero .card-body { padding: 1.75rem; }
        .profile-hero .avatar-xl {
            width: 88px; height: 88px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.35);
            object-fit: cover;
            background: #fff;
        }
        .profile-hero .role-badge {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.30);
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }
        .profile-hero .meta-pill {
            background: rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.92);
            padding: .25rem .65rem;
            border-radius: 6px;
            font-size: .75rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }
        .btn-hero-edit {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
            font-weight: 500;
            padding: .5rem 1rem;
            border-radius: 6px;
            transition: background .2s ease, border-color .2s ease, transform .15s ease;
        }
        .btn-hero-edit:hover,
        .btn-hero-edit:focus {
            background: #fff;
            border-color: #fff;
            color: #1e3c72;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-hero-edit i { transition: transform .2s ease; }
        .btn-hero-edit:hover i { transform: rotate(-12deg); }

        /* ==== Profile — Modal eliminar cuenta (danger) ==== */
        .danger-modal {
            border: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(220,53,69,0.25);
        }
        .danger-modal-header {
            background: linear-gradient(135deg, #7f1d1d 0%, #dc3545 100%);
            color: #fff;
            border-bottom: 0;
            padding: .9rem 1.25rem;
            display: flex;
            align-items: center;
            min-height: 58px;
        }
        .danger-modal-header .modal-title {
            color: #fff;
            font-weight: 600;
            margin: 0;
            line-height: 1.4;
            flex: 1;
        }
        .danger-modal-header .modal-title i {
            color: #fff;
            font-size: 1.25rem;
            line-height: 1;
        }
        .danger-modal-header .btn-close {
            margin: 0;
            padding: .5rem;
            flex-shrink: 0;
            opacity: .85;
            transition: opacity .15s ease;
        }
        .danger-modal-header .btn-close:hover { opacity: 1; }

        .danger-icon-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(220,53,69,0.10), rgba(220,53,69,0.20));
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            border: 2px dashed rgba(220,53,69,0.40);
            animation: danger-pulse 2s ease-in-out infinite;
        }
        @keyframes danger-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220,53,69,0.20); }
            50%      { box-shadow: 0 0 0 10px rgba(220,53,69,0); }
        }

        .danger-loss-box {
            background: rgba(220,53,69,0.05);
            border-left: 3px solid #dc3545;
            border-radius: 6px;
            padding: .75rem 1rem;
        }
        [data-bs-theme="dark"] .danger-loss-box {
            background: rgba(220,53,69,0.10);
        }

        .danger-confirm-word {
            background: rgba(220,53,69,0.10);
            color: #dc3545;
            padding: .15rem .45rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: .9em;
            letter-spacing: .05em;
        }
        [data-bs-theme="dark"] .danger-confirm-word {
            background: rgba(220,53,69,0.20);
        }

        /* Botón eliminar deshabilitado: pierde brillo */
        .danger-modal #confirmDeleteBtn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        /* ==== Profile — icono circular para sección de seguridad (resumen) ==== */
        .security-icon-circle {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(12,74,110,0.12), rgba(14,165,233,0.18));
            color: #0c4a6e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            border: 1px solid rgba(14,165,233,0.20);
        }
        [data-bs-theme="dark"] .security-icon-circle {
            background: linear-gradient(135deg, rgba(12,74,110,0.30), rgba(14,165,233,0.25));
            color: #7dd3fc;
            border-color: rgba(14,165,233,0.40);
        }

        /* ==== Profile — Cards ==== */
        .profile-section-card { border: 1px solid rgba(0,0,0,0.06); }
        .profile-section-card > .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: .9rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .profile-section-card > .card-header .section-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .profile-section-card.section-info > .card-header .section-icon { background: linear-gradient(135deg,#1e3c72,#2a5298); }
        .profile-section-card.section-password > .card-header .section-icon { background: linear-gradient(135deg,#0c4a6e,#0ea5e9); }
        .profile-section-card.section-questions > .card-header .section-icon { background: linear-gradient(135deg,#064e3b,#10b981); }
        .profile-section-card.section-danger > .card-header .section-icon { background: linear-gradient(135deg,#7f1d1d,#dc3545); }

        .profile-section-card > .card-header .card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }
        .profile-section-card > .card-header .card-subtitle {
            font-size: .75rem;
            color: #64748b;
            margin: 0;
        }

        .profile-section-card.section-danger {
            border: 1px solid rgba(220,53,69,0.25);
            background: rgba(220,53,69,0.02);
        }
        .profile-section-card.section-danger > .card-header {
            background: rgba(220,53,69,0.04);
            border-bottom-color: rgba(220,53,69,0.15);
        }

        /* Dark mode */
        [data-bs-theme="dark"] .profile-section-card { border-color: rgba(255,255,255,0.08); }
        [data-bs-theme="dark"] .profile-section-card > .card-header {
            background: var(--vz-card-bg, #1f2937);
            border-bottom-color: rgba(255,255,255,0.08);
        }
        [data-bs-theme="dark"] .profile-section-card > .card-header .card-title { color: #e5e7eb; }
        [data-bs-theme="dark"] .profile-section-card.section-danger > .card-header { background: rgba(220,53,69,0.10); }

        /* ==== Profile — Botones de acción primaria por sección ==== */
        .btn-profile-save {
            color: #fff !important;
            border: 0;
            font-weight: 500;
            transition: filter .15s ease, transform .12s ease, box-shadow .15s ease;
        }
        .btn-profile-save:hover,
        .btn-profile-save:focus {
            color: #fff !important;
            filter: brightness(1.08);
            transform: translateY(-1px);
        }
        .btn-profile-save:active { transform: translateY(0); filter: brightness(.95); }
        .btn-profile-save i { color: #fff !important; }

        .btn-profile-save.is-info     { background: #1e3c72; box-shadow: 0 1px 2px rgba(30,60,114,.25); }
        .btn-profile-save.is-info:hover     { box-shadow: 0 4px 12px rgba(30,60,114,.35); }

        .btn-profile-save.is-password { background: #0c4a6e; box-shadow: 0 1px 2px rgba(12,74,110,.25); }
        .btn-profile-save.is-password:hover { box-shadow: 0 4px 12px rgba(12,74,110,.35); }

        .btn-profile-save.is-questions { background: #064e3b; box-shadow: 0 1px 2px rgba(6,78,59,.25); }
        .btn-profile-save.is-questions:hover { box-shadow: 0 4px 12px rgba(6,78,59,.35); }

        /* ==== Profile — Bloques de pregunta de seguridad ==== */
        .recovery-question-block {
            display: flex;
            align-items: stretch;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(16,185,129,0.20);
            border-left: 4px solid #10b981;
            border-radius: 8px;
            background: rgba(16,185,129,0.03);
            margin-bottom: .85rem;
            transition: box-shadow .2s ease, border-color .2s ease, background .2s ease;
        }
        .recovery-question-block:last-child { margin-bottom: 1.25rem; }
        .recovery-question-block:hover {
            border-color: rgba(16,185,129,0.45);
            background: rgba(16,185,129,0.05);
            box-shadow: 0 2px 8px rgba(16,185,129,0.10);
        }
        .recovery-question-block:focus-within {
            border-color: #10b981;
            background: rgba(16,185,129,0.06);
            box-shadow: 0 0 0 .15rem rgba(16,185,129,0.18);
        }
        .recovery-question-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #064e3b, #10b981);
            color: #fff;
            border-radius: 50%;
            font-size: .95rem;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(6,78,59,0.30);
            flex-shrink: 0;
            align-self: center; /* centrado vertical con los inputs ahora más bajos */
        }
        .recovery-question-fields {
            flex: 1;
            min-width: 0;
        }
        .recovery-question-fields .input-group-text {
            background: rgba(16,185,129,0.10);
            border-color: rgba(16,185,129,0.30);
            color: #064e3b;
        }
        [data-bs-theme="dark"] .recovery-question-fields .input-group-text {
            background: rgba(16,185,129,0.18);
            border-color: rgba(16,185,129,0.40);
            color: #6ee7b7;
        }

        /* Dark mode */
        [data-bs-theme="dark"] .recovery-question-block {
            background: rgba(16,185,129,0.06);
            border-color: rgba(16,185,129,0.25);
        }
        [data-bs-theme="dark"] .recovery-question-block:hover {
            background: rgba(16,185,129,0.10);
            border-color: rgba(16,185,129,0.50);
        }
        [data-bs-theme="dark"] .recovery-question-fields .form-label i { color: #6ee7b7; }

        /* Mobile: stack vertical */
        @media (max-width: 575.98px) {
            .recovery-question-block {
                flex-direction: column;
                gap: .5rem;
            }
            .recovery-question-number {
                margin-top: 0;
                align-self: flex-start;
            }
        }

        /* Status badge for recovery questions */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 500;
        }
        .status-pill.ok      { background: rgba(16,185,129,0.12); color: #047857; }
        .status-pill.warn    { background: rgba(245,158,11,0.12); color: #b45309; }
        .status-pill.danger  { background: rgba(220,53,69,0.12); color: #b91c1c; }
        [data-bs-theme="dark"] .status-pill.ok     { background: rgba(16,185,129,0.18); color: #6ee7b7; }
        [data-bs-theme="dark"] .status-pill.warn   { background: rgba(245,158,11,0.20); color: #fbbf24; }
        [data-bs-theme="dark"] .status-pill.danger { background: rgba(220,53,69,0.22); color: #fca5a5; }
    </style>
@endpush

@section('content')
@php
    $configured  = $user->hasRecoveryQuestionsConfigured();
    $mustReset   = (bool) $user->recovery_must_reset_questions;
    $avatarUrl   = $user->avatar
        ? asset('storage/' . $user->avatar)
        : asset('assets/images/users/user-dummy-img.jpg');
    $roleLabel   = $user->role ?? '—';
    $roleIcon    = $user->isAdmin() ? 'ri-shield-star-line' : 'ri-shield-user-line';
    $createdAt   = $user->created_at?->format('d/m/Y');
@endphp

        {{-- Page header --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Mi perfil') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Mi perfil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero del usuario --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card profile-hero">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="avatar-xl flex-shrink-0">
                            <div class="flex-grow-1" style="min-width: 200px;">
                                <h4 class="mb-1 text-white d-flex align-items-center gap-2">
                                    {{ $user->name }}
                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <span class="meta-pill" title="Correo no verificado" style="background: rgba(245,158,11,0.25); border: 1px solid rgba(245,158,11,0.5);">
                                            <i class="ri-error-warning-line"></i>Sin verificar
                                        </span>
                                    @endif
                                </h4>
                                <div class="text-white-50 mb-2">
                                    <i class="ri-mail-line me-1"></i>{{ $user->email }}
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="role-badge">
                                        <i class="{{ $roleIcon }}"></i>{{ $roleLabel }}
                                    </span>
                                    @if ($user->estado)
                                        <span class="meta-pill">
                                            <i class="ri-checkbox-circle-line"></i>Cuenta activa
                                        </span>
                                    @endif
                                    @if ($createdAt)
                                        <span class="meta-pill">
                                            <i class="ri-calendar-line"></i>Miembro desde {{ $createdAt }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <button type="button" class="btn btn-hero-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="ri-pencil-line me-1"></i>Editar perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Seguridad de acceso (full width — resumen + botón) --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card profile-section-card section-password">
                    <div class="card-header">
                        <span class="section-icon"><i class="ri-lock-2-line"></i></span>
                        <div>
                            <h5 class="card-title">Seguridad de acceso</h5>
                            <p class="card-subtitle">Tu contraseña protege el acceso a la cuenta</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="flex-grow-1 d-flex align-items-center gap-3" style="min-width: 250px;">
                                <div class="security-icon-circle">
                                    <i class="ri-shield-keyhole-line"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-semibold">Contraseña</p>
                                    <p class="text-muted small mb-0">
                                        Te recomendamos cambiarla periódicamente y no reutilizarla en otros sitios.
                                    </p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-profile-save is-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="ri-key-2-line me-1"></i>Cambiar contraseña
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Editar información personal --}}
        @php
            $hasProfileErrors  = $errors->has('name') || $errors->has('email');
            $hasPasswordErrors = $errors->updatePassword->isNotEmpty();
        @endphp
        <div class="modal fade atlantico-modal" id="editProfileModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            x-data="{ show: {{ $hasProfileErrors ? 'true' : 'false' }} }"
            x-init="() => { if (show) { new bootstrap.Modal(document.getElementById('editProfileModal')).show(); } }"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-user-settings-line me-2"></i>Editar información personal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Cambiar contraseña --}}
        <div class="modal fade atlantico-modal" id="changePasswordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            x-data="{ show: {{ $hasPasswordErrors ? 'true' : 'false' }} }"
            x-init="() => { if (show) { new bootstrap.Modal(document.getElementById('changePasswordModal')).show(); } }"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-key-2-line me-2"></i>Cambiar contraseña
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- Preguntas de seguridad --}}
        <div class="row g-3 mt-0">
            <div class="col-12">
                <div class="card profile-section-card section-questions" id="recovery-questions-card">
                    <div class="card-header">
                        <span class="section-icon"><i class="ri-shield-keyhole-line"></i></span>
                        <div class="flex-grow-1">
                            <h5 class="card-title">Preguntas de seguridad</h5>
                            <p class="card-subtitle">Recuperación de contraseña sin internet</p>
                        </div>
                        @if ($mustReset)
                            <span class="status-pill danger">
                                <i class="ri-alert-line"></i>Requieren actualización
                            </span>
                        @elseif ($configured)
                            <span class="status-pill ok">
                                <i class="ri-shield-check-line"></i>Configuradas
                            </span>
                        @else
                            <span class="status-pill warn">
                                <i class="ri-error-warning-line"></i>No configuradas
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-recovery-questions-form')
                    </div>
                </div>
            </div>
        </div>

        {{-- Zona de peligro --}}
        <div class="row g-3 mt-0">
            <div class="col-12">
                <div class="card profile-section-card section-danger">
                    <div class="card-header">
                        <span class="section-icon"><i class="ri-error-warning-line"></i></span>
                        <div>
                            <h5 class="card-title text-danger">Zona de peligro</h5>
                            <p class="card-subtitle">Acciones irreversibles</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

@if (session('status') === 'profile-updated')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Información actualizada',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    });
</script>
@endif

@if (session('status') === 'password-updated')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Contraseña actualizada',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    });
</script>
@endif

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
