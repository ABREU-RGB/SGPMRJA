@php
    $configured = $user->hasRecoveryQuestionsConfigured();
    $mustReset  = (bool) $user->recovery_must_reset_questions;
    $existing   = $user->recoveryQuestions;
    $lastUpdate = $existing->max('updated_at');
@endphp

<section>
    @if (!$configured)
        <p class="text-muted small mb-3">
            Configura 3 preguntas y respuestas para recuperar tu contraseña incluso sin internet.
        </p>
    @else
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <p class="text-muted small mb-0">
                Edita las preguntas o respuestas que quieras cambiar. Las que dejes intactas se conservan.
            </p>
            @if ($lastUpdate)
                <span class="text-muted small">
                    <i class="ri-time-line me-1"></i>Última actualización: {{ $lastUpdate->format('d/m/Y H:i') }}
                </span>
            @endif
        </div>
    @endif

    @if ($mustReset)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <i class="ri-error-warning-line fs-5"></i>
            <span>Por seguridad, debes actualizar tus preguntas tras la recuperación reciente.</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('profile.recovery-questions.update') }}" id="recoveryQuestionsForm" novalidate autocomplete="off">
        @csrf
        @method('patch')

        {{-- Decoys para neutralizar autofill agresivo del navegador --}}
        <input type="text" name="autofill_decoy_user" autocomplete="username" tabindex="-1" aria-hidden="true" style="position:absolute;opacity:0;width:0;height:0;pointer-events:none;">
        <input type="password" name="autofill_decoy_pass" autocomplete="current-password" tabindex="-1" aria-hidden="true" style="position:absolute;opacity:0;width:0;height:0;pointer-events:none;">

        @for ($i = 0; $i < 3; $i++)
            @php
                $orden       = $i + 1;
                $existingRow = $existing->firstWhere('orden', $orden);
                $hadOldEdit  = old("cambios.$i.editing") === '1';
                $hasError    = $errors->has("cambios.$i.respuesta") || $errors->has("cambios.$i.pregunta_id");
                // Modo edición inicial:
                //   - Sin pregunta previa (configuración inicial) → siempre edit
                //   - O hubo error/old en este bloque → reabrir en edit
                //   - O todo el set debe resetearse (must_reset) → edit
                $startInEdit = !$existingRow || $hadOldEdit || $hasError || $mustReset;
                $selectedId  = old("cambios.$i.pregunta_id", $existingRow->pregunta_id ?? '');
                $oldAnswer   = old("cambios.$i.respuesta", '');
            @endphp

            <div class="recovery-question-block" data-index="{{ $i }}" data-orden="{{ $orden }}"
                x-data="recoveryBlock({{ $startInEdit ? 'true' : 'false' }})">
                <input type="hidden" name="cambios[{{ $i }}][editing]" :value="editing ? '1' : '0'">

                <div class="recovery-question-number">{{ $orden }}</div>

                <div class="recovery-question-fields">
                    {{-- VIEW MODE (solo cuando hay pregunta previa) --}}
                    @if ($existingRow)
                        <div x-show="!editing" class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-1">
                            <div class="flex-grow-1" style="min-width: 200px;">
                                <p class="mb-1 fw-medium small">{{ $existingRow->pregunta_texto }}</p>
                                <p class="text-muted small mb-0">
                                    <span class="font-monospace">●●●●●●●●</span>
                                    <span class="ms-2 fst-italic">— Respuesta cifrada</span>
                                </p>
                            </div>
                            <button type="button" class="btn btn-sm btn-recovery-edit" @click="editing = true">
                                <i class="ri-edit-2-line me-1"></i>Cambiar
                            </button>
                        </div>
                    @endif

                    {{-- EDIT MODE --}}
                    <div x-show="editing">
                        <label class="form-label small text-muted mb-1">
                            <i class="ri-question-line me-1 text-success"></i>Pregunta
                        </label>
                        <select name="cambios[{{ $i }}][pregunta_id]"
                                class="form-select recovery-question-select mb-2"
                                data-original-value="{{ $existingRow->pregunta_id ?? '' }}">
                            <option value="">Selecciona una pregunta...</option>
                            @foreach ($recoveryQuestions as $id => $texto)
                                <option value="{{ $id }}" @if ((string) $selectedId === (string) $id) selected @endif>
                                    {{ $texto }}
                                </option>
                            @endforeach
                        </select>

                        <label class="form-label small text-muted mb-1">
                            <i class="ri-chat-3-line me-1 text-success"></i>Respuesta
                        </label>
                        <input
                            type="text"
                            name="cambios[{{ $i }}][respuesta]"
                            class="form-control recovery-answer-input"
                            value="{{ $oldAnswer }}"
                            minlength="3"
                            maxlength="255"
                            autocomplete="new-password"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            placeholder="Tu respuesta…"
                        >

                        @if ($existingRow)
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-sm btn-recovery-cancel"
                                        @click="cancel()">
                                    <i class="ri-close-line me-1"></i>Cancelar este cambio
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endfor

        @if ($configured)
            {{-- Confirmación de identidad: contraseña actual --}}
            <div class="recovery-password-confirm mt-3 mb-3">
                <p class="small mb-2 fw-semibold">
                    <i class="ri-shield-keyhole-line me-1"></i>Confirma tu identidad
                </p>
                <p class="text-muted small mb-2">
                    Por seguridad, ingresa tu contraseña actual para autorizar los cambios.
                </p>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                    <input
                        type="password"
                        name="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        autocomplete="new-password"
                        placeholder="Tu contraseña actual"
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif

        <div class="recovery-tip-box mb-0">
            <i class="ri-information-line"></i>
            <div>
                <p class="mb-1 fw-semibold small">Sobre tus respuestas</p>
                <p class="text-muted small mb-0">
                    Mínimo 3 caracteres y las 3 deben ser distintas entre sí. No importan mayúsculas,
                    tildes ni espacios extra. Se almacenan cifradas — ni el administrador puede verlas.
                </p>
            </div>
        </div>
    </form>
</section>

<script>
// Componente Alpine para cada bloque de pregunta — registrado globalmente
document.addEventListener('alpine:init', () => {
    Alpine.data('recoveryBlock', (startInEdit) => ({
        editing: !!startInEdit,
        cancel() {
            const block = this.$el;
            const sel = block.querySelector('select.recovery-question-select');
            const inp = block.querySelector('input.recovery-answer-input');
            if (sel) {
                const original = sel.dataset.originalValue || '';
                sel.value = original;
                sel.dispatchEvent(new Event('change', { bubbles: true }));
            }
            if (inp) {
                inp.value = '';
                inp.classList.remove('is-invalid', 'is-valid');
            }
            this.editing = false;
        },
    }));
});

(function () {
    'use strict';

    var form = document.getElementById('recoveryQuestionsForm');
    if (!form) return;

    var selects = form.querySelectorAll('.recovery-question-select');

    function refreshOptions() {
        var selectedValues = Array.from(selects).map(function (s) { return s.value; });
        selects.forEach(function (select) {
            Array.from(select.options).forEach(function (opt) {
                if (!opt.value) return;
                var usedElsewhere = selectedValues.includes(opt.value) && opt.value !== select.value;
                opt.disabled = usedElsewhere;
            });
        });
    }

    selects.forEach(function (s) { s.addEventListener('change', refreshOptions); });
    refreshOptions();
})();
</script>
