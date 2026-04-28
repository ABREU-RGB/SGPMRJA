<x-guest-layout title="Buscar cuenta" icon="bx-search-alt-2">

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Ingresa tu correo electrónico. Si tienes preguntas de seguridad configuradas, podrás responderlas para recuperar tu contraseña.
    </p>

    <form method="POST" action="{{ route('recovery.email.process') }}" id="recoveryEmailForm" novalidate>
        @csrf

        <div class="recovery-field-block @error('email') recovery-field-block--error @enderror mb-3">
            <div class="recovery-field-header">
                <span class="recovery-field-icon"><i class="bx bx-envelope"></i></span>
                <label for="email" class="recovery-field-label mb-0">Correo electrónico</label>
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="bx bx-at"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="correo@empresa.com"
                >
            </div>
        </div>

        @error('email')
            <div class="recovery-info-notice mb-4">
                <span class="recovery-info-notice__icon"><i class="bx bx-info-circle"></i></span>
                <p class="recovery-info-notice__msg mb-0">{{ $message }}</p>
            </div>
        @enderror

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('recovery.method') }}" class="link-atlantico">
                <i class="bx bx-arrow-back me-1"></i>Volver
            </a>

            <button type="submit" class="btn btn-atlantico" id="submitBtn">
                <span id="submitText">
                    <i class="bx bx-right-arrow-alt me-1"></i>Continuar
                </span>
                <span id="submitSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Verificando…
                </span>
            </button>
        </div>
    </form>

    @push('scripts')
    <style>
        .recovery-field-block {
            padding: .85rem 1rem 1rem;
            border: 1px solid rgba(30, 60, 114, 0.12);
            border-left: 3px solid #1e3c72;
            border-radius: 8px;
            background: rgba(30, 60, 114, 0.025);
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
        }
        .recovery-field-block:focus-within {
            border-color: rgba(30, 60, 114, 0.40);
            background: rgba(30, 60, 114, 0.04);
            box-shadow: 0 2px 12px rgba(30, 60, 114, 0.10);
        }
        [data-bs-theme="dark"] .recovery-field-block {
            border-color: rgba(96, 165, 250, 0.15);
            border-left-color: #60a5fa;
            background: rgba(96, 165, 250, 0.04);
        }
        [data-bs-theme="dark"] .recovery-field-block:focus-within {
            border-color: rgba(96, 165, 250, 0.45);
            background: rgba(96, 165, 250, 0.07);
        }

        .recovery-field-header {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: .5rem;
        }
        .recovery-field-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            font-size: .8rem;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(30,60,114,0.30);
        }
        .recovery-field-label {
            font-weight: 600;
            font-size: .875rem;
            color: #374151;
        }
        [data-bs-theme="dark"] .recovery-field-label { color: #c8cbd0; }

        /* Error state del bloque */
        .recovery-field-block--error {
            border-left-color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.04);
        }
        [data-bs-theme="dark"] .recovery-field-block--error {
            border-left-color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.25);
            background: rgba(251, 191, 36, 0.04);
        }

        /* Bloque informativo de mensaje */
        .recovery-info-notice {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            padding: .8rem 1rem;
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-left: 3px solid #f59e0b;
            border-radius: 8px;
            background: rgba(245, 158, 11, 0.06);
        }
        [data-bs-theme="dark"] .recovery-info-notice {
            border-color: rgba(251, 191, 36, 0.2);
            border-left-color: #fbbf24;
            background: rgba(251, 191, 36, 0.05);
        }
        .recovery-info-notice__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            color: #fff;
            font-size: .95rem;
            flex-shrink: 0;
            margin-top: .05rem;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.35);
        }
        .recovery-info-notice__msg {
            font-size: .85rem;
            color: #92400e;
            line-height: 1.5;
        }
        [data-bs-theme="dark"] .recovery-info-notice__msg { color: #fde68a; }
    </style>
    <script>
        document.getElementById('recoveryEmailForm').addEventListener('submit', function () {
            var btn     = document.getElementById('submitBtn');
            var text    = document.getElementById('submitText');
            var spinner = document.getElementById('submitSpinner');
            btn.disabled = true;
            text.classList.add('d-none');
            spinner.classList.remove('d-none');
        });
    </script>
    @endpush

</x-guest-layout>
