<x-guest-layout>

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Responde las 3 preguntas configuradas para verificar tu identidad.
        <span class="text-secondary">No importan mayúsculas, tildes ni espacios extra.</span>
    </p>

    @if ($errors->has('respuestas'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bx bx-error-circle fs-5"></i>
            <span>{{ $errors->first('respuestas') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('recovery.questions.validate') }}" id="recoveryAnswersForm" novalidate autocomplete="off">
        @csrf

        {{-- Decoys anti-autofill --}}
        <input type="text" name="autofill_decoy_user" autocomplete="username" tabindex="-1" aria-hidden="true" style="position:absolute;opacity:0;width:0;height:0;pointer-events:none;">
        <input type="password" name="autofill_decoy_pass" autocomplete="current-password" tabindex="-1" aria-hidden="true" style="position:absolute;opacity:0;width:0;height:0;pointer-events:none;">

        @foreach ($questions as $q)
            <div class="recovery-q-block">
                <div class="recovery-q-header">
                    <span class="recovery-q-num">{{ $q['orden'] }}</span>
                    <label for="respuesta_{{ $q['id'] }}" class="recovery-q-text mb-0">
                        {{ $q['pregunta'] }}
                    </label>
                </div>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bx bx-message-rounded-dots"></i>
                    </span>
                    <input
                        id="respuesta_{{ $q['id'] }}"
                        type="text"
                        name="respuestas[{{ $q['id'] }}]"
                        class="form-control"
                        required
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        maxlength="255"
                        placeholder="Tu respuesta…"
                    >
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('recovery.method') }}" class="link-atlantico">
                <i class="bx bx-arrow-back me-1"></i>Cancelar
            </a>

            <button type="submit" class="btn btn-atlantico" id="submitBtn">
                <span id="submitText">
                    <i class="bx bx-check-circle me-1"></i>Verificar respuestas
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
        /* Bloque de pregunta */
        .recovery-q-block {
            margin-bottom: 1rem;
            padding: .85rem 1rem 1rem;
            border: 1px solid rgba(30, 60, 114, 0.12);
            border-left: 3px solid #1e3c72;
            border-radius: 8px;
            background: rgba(30, 60, 114, 0.025);
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
        }
        .recovery-q-block:focus-within {
            border-color: rgba(30, 60, 114, 0.40);
            background: rgba(30, 60, 114, 0.04);
            box-shadow: 0 2px 12px rgba(30, 60, 114, 0.10);
        }

        [data-bs-theme="dark"] .recovery-q-block {
            border-color: rgba(96, 165, 250, 0.15);
            border-left-color: #60a5fa;
            background: rgba(96, 165, 250, 0.04);
        }
        [data-bs-theme="dark"] .recovery-q-block:focus-within {
            border-color: rgba(96, 165, 250, 0.45);
            background: rgba(96, 165, 250, 0.07);
        }

        /* Header del bloque */
        .recovery-q-header {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: .5rem;
        }

        /* Número en círculo */
        .recovery-q-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(30,60,114,0.30);
        }

        /* Texto de la pregunta */
        .recovery-q-text {
            font-weight: 500;
            font-size: .9rem;
            color: #1f2937;
            line-height: 1.35;
        }
        [data-bs-theme="dark"] .recovery-q-text { color: #e5e7eb; }
    </style>
    <script>
        document.getElementById('recoveryAnswersForm').addEventListener('submit', function () {
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
