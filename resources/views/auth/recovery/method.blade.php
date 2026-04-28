<x-guest-layout title="Recuperar contraseña" icon="bx-lock-open-alt">

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Elige cómo quieres recuperar tu contraseña.
    </p>

    <div class="d-grid gap-3 mb-4">
        <a href="{{ route('password.request') }}" class="recovery-option-card">
            <div class="recovery-option-icon recovery-option-icon--navy">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="recovery-option-body">
                <div class="recovery-option-title">Correo electrónico</div>
                <div class="recovery-option-desc"><strong style="color:#111827;">Te enviaremos un enlace para restablecerla.</strong><br><strong style="display:block; margin-top:.25rem;">Requiere conexión a internet.</strong></div>
            </div>
            <i class="bx bx-chevron-right recovery-option-arrow"></i>
        </a>

        <a href="{{ route('recovery.email.show') }}" class="recovery-option-card recovery-option-card--emerald">
            <div class="recovery-option-icon recovery-option-icon--emerald">
                <i class="bx bx-shield-quarter"></i>
            </div>
            <div class="recovery-option-body">
                <div class="recovery-option-title">Preguntas de seguridad</div>
                <div class="recovery-option-desc"><strong style="color:#111827;">Responde tus 3 preguntas configuradas.</strong><br><strong style="display:block; margin-top:.25rem;">No requiere conexión a internet.</strong></div>
            </div>
            <i class="bx bx-chevron-right recovery-option-arrow"></i>
        </a>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="link-atlantico">
            <i class="bx bx-arrow-back me-1"></i>Volver al inicio de sesión
        </a>
    </div>

    @push('scripts')
    <style>
        .recovery-option-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .9rem 1rem;
            border: 1px solid rgba(30, 60, 114, 0.12);
            border-left: 3px solid #1e3c72;
            border-radius: 8px;
            background: rgba(30, 60, 114, 0.025);
            text-decoration: none;
            color: inherit;
            transition: border-color .2s, background .2s, box-shadow .2s, transform .15s;
        }
        .recovery-option-card:hover {
            border-color: rgba(30, 60, 114, 0.4);
            background: rgba(30, 60, 114, 0.045);
            box-shadow: 0 3px 14px rgba(30, 60, 114, 0.12);
            transform: translateY(-1px);
            color: inherit;
        }
        .recovery-option-card--emerald {
            border-left-color: #10b981;
            background: rgba(16, 185, 129, 0.025);
        }
        .recovery-option-card--emerald:hover {
            border-color: rgba(16, 185, 129, 0.4);
            background: rgba(16, 185, 129, 0.045);
            box-shadow: 0 3px 14px rgba(16, 185, 129, 0.12);
        }

        .recovery-option-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px; height: 44px;
            border-radius: 10px;
            flex-shrink: 0;
            font-size: 1.5rem;
        }
        .recovery-option-icon--navy {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            box-shadow: 0 3px 8px rgba(30, 60, 114, 0.3);
        }
        .recovery-option-icon--emerald {
            background: linear-gradient(135deg, #064e3b, #10b981);
            color: #fff;
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
        }

        .recovery-option-body { flex: 1; }
        .recovery-option-title {
            font-weight: 600;
            font-size: .925rem;
            color: #111827;
            margin-bottom: .15rem;
        }
        .recovery-option-desc {
            font-size: .8rem;
            color: #6b7280;
            line-height: 1.35;
        }

        .recovery-option-arrow {
            font-size: 1.2rem;
            color: #9ca3af;
            transition: transform .2s, color .2s;
        }
        .recovery-option-card:hover .recovery-option-arrow {
            transform: translateX(3px);
            color: #6b7280;
        }

        [data-bs-theme="dark"] .recovery-option-card {
            border-color: rgba(96, 165, 250, 0.15);
            border-left-color: #60a5fa;
            background: rgba(96, 165, 250, 0.04);
        }
        [data-bs-theme="dark"] .recovery-option-card:hover {
            border-color: rgba(96, 165, 250, 0.40);
            background: rgba(96, 165, 250, 0.07);
        }
        [data-bs-theme="dark"] .recovery-option-card--emerald {
            border-left-color: #34d399;
            background: rgba(52, 211, 153, 0.04);
        }
        [data-bs-theme="dark"] .recovery-option-card--emerald:hover {
            border-color: rgba(52, 211, 153, 0.35);
            background: rgba(52, 211, 153, 0.07);
        }
        [data-bs-theme="dark"] .recovery-option-title { color: #e5e7eb; }
        [data-bs-theme="dark"] .recovery-option-desc  { color: #9ca3af; }
        [data-bs-theme="dark"] .recovery-option-arrow { color: #4b5568; }
        [data-bs-theme="dark"] .recovery-option-card:hover .recovery-option-arrow { color: #9ca3af; }
    </style>
    @endpush

</x-guest-layout>
