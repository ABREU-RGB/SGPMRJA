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
<div class="page-content">
    <div class="container-fluid">

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
                                <h4 class="mb-1 text-white">{{ $user->name }}</h4>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información personal + Contraseña --}}
        <div class="row g-3">
            <div class="col-xxl-6 col-lg-6">
                <div class="card profile-section-card section-info h-100">
                    <div class="card-header">
                        <span class="section-icon"><i class="ri-user-3-line"></i></span>
                        <div>
                            <h5 class="card-title">Información personal</h5>
                            <p class="card-subtitle">Nombre y correo electrónico</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-xxl-6 col-lg-6">
                <div class="card profile-section-card section-password h-100">
                    <div class="card-header">
                        <span class="section-icon"><i class="ri-lock-2-line"></i></span>
                        <div>
                            <h5 class="card-title">Seguridad de acceso</h5>
                            <p class="card-subtitle">Cambia tu contraseña</p>
                        </div>
                    </div>
                    <div class="card-body">
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
