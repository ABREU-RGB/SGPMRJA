<x-guest-layout title="Acceso bloqueado" icon="bx-lock">

    @php
        $type   = session('lock_type', 'soft');
        $until  = session('until');
        $isHard = $type === 'hard';
    @endphp

    <div class="recovery-lock-icon {{ $isHard ? 'recovery-lock-icon--hard' : 'recovery-lock-icon--soft' }} mb-4">
        <i class="bx {{ $isHard ? 'bx-lock' : 'bx-time-five' }}"></i>
    </div>

    <div class="recovery-lock-card {{ $isHard ? 'recovery-lock-card--hard' : 'recovery-lock-card--soft' }} mb-4">
        @if ($isHard)
            <div class="recovery-lock-title">Recuperación bloqueada</div>
            <p class="recovery-lock-msg">
                Tu cuenta excedió el número máximo de intentos fallidos de recuperación.
                Contacta al <strong>administrador del sistema</strong> para desbloquearla.
            </p>
        @else
            <div class="recovery-lock-title">Demasiados intentos</div>
            <p class="recovery-lock-msg">
                Por seguridad, hemos bloqueado temporalmente la recuperación de esta cuenta.
                @if ($until)
                    Inténtalo de nuevo después de las
                    <strong>{{ \Carbon\Carbon::parse($until)->format('H:i') }}</strong>.
                @else
                    Inténtalo de nuevo más tarde.
                @endif
            </p>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="btn btn-atlantico">
            <i class="bx bx-arrow-back me-1"></i>Volver al inicio de sesión
        </a>
    </div>

    @push('scripts')
    <style>
        .recovery-lock-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 72px; height: 72px;
            border-radius: 50%;
            font-size: 2.2rem;
            margin: 0 auto;
            animation: lockPulse 2.2s ease-in-out infinite;
        }
        .recovery-lock-icon--soft {
            --lock-rgb: 245,158,11;
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
        }
        .recovery-lock-icon--hard {
            --lock-rgb: 220,53,69;
            background: rgba(220, 53, 69, 0.10);
            color: #dc3545;
        }
        @keyframes lockPulse {
            0%   { box-shadow: 0 0 0 0    rgba(var(--lock-rgb), 0.4); }
            70%  { box-shadow: 0 0 0 14px rgba(var(--lock-rgb), 0);   }
            100% { box-shadow: 0 0 0 0    rgba(var(--lock-rgb), 0);   }
        }

        .recovery-lock-card {
            padding: 1rem 1.1rem;
            border-radius: 8px;
        }
        .recovery-lock-card--soft {
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-left: 3px solid #f59e0b;
            background: rgba(245, 158, 11, 0.06);
        }
        .recovery-lock-card--hard {
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-left: 3px solid #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }
        [data-bs-theme="dark"] .recovery-lock-card--soft {
            border-color: rgba(251, 191, 36, 0.2);
            border-left-color: #fbbf24;
            background: rgba(251, 191, 36, 0.05);
        }
        [data-bs-theme="dark"] .recovery-lock-card--hard {
            border-color: rgba(248, 113, 113, 0.2);
            border-left-color: #f87171;
            background: rgba(248, 113, 113, 0.05);
        }

        .recovery-lock-title {
            font-weight: 700;
            font-size: .95rem;
            margin-bottom: .35rem;
            color: #1f2937;
        }
        [data-bs-theme="dark"] .recovery-lock-title { color: #e5e7eb; }

        .recovery-lock-msg {
            font-size: .875rem;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
        }
        [data-bs-theme="dark"] .recovery-lock-msg { color: #9ca3af; }
    </style>
    @endpush

</x-guest-layout>
