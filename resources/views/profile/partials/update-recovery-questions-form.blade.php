@php
    $configured = $user->hasRecoveryQuestionsConfigured();
    $mustReset  = (bool) $user->recovery_must_reset_questions;
    $existing   = $user->recoveryQuestions;
@endphp

<section>
    <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h2 class="h4 text-dark mb-1">{{ __('Preguntas de seguridad') }}</h2>
            <p class="text-muted mb-0">
                {{ __('Configura 3 preguntas para recuperar tu contraseña sin necesidad de internet.') }}
            </p>
        </div>
        <div>
            @if ($mustReset)
                <span class="badge bg-soft-danger text-danger fs-6">
                    <i class="ri-alert-line me-1"></i> Requieren actualización
                </span>
            @elseif ($configured)
                <span class="badge bg-soft-success text-success fs-6">
                    <i class="ri-shield-check-line me-1"></i> Configuradas
                </span>
            @else
                <span class="badge bg-soft-warning text-warning fs-6">
                    <i class="ri-error-warning-line me-1"></i> No configuradas
                </span>
            @endif
        </div>
    </div>

    @if ($mustReset)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <i class="ri-error-warning-line fs-5"></i>
            <span>Por seguridad, debes actualizar tus preguntas tras la recuperación reciente.</span>
        </div>
    @endif

    @if (session('warning_recovery'))
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
            <i class="ri-error-warning-line fs-5"></i>
            <span>{{ session('warning_recovery') }}</span>
        </div>
    @endif

    @if ($errors->any() && (old('preguntas') || old('respuestas')))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('profile.recovery-questions.update') }}" id="recoveryQuestionsForm" novalidate>
        @csrf
        @method('patch')

        @for ($i = 0; $i < 3; $i++)
            @php
                $orden       = $i + 1;
                $existingRow = $existing->firstWhere('orden', $orden);
                $selected    = old('preguntas.' . $i, $existingRow->pregunta_id ?? '');
            @endphp
            <div class="row g-2 mb-3 align-items-start recovery-question-row" data-index="{{ $i }}">
                <div class="col-md-6">
                    <label class="form-label">Pregunta {{ $orden }} <span class="text-danger">*</span></label>
                    <select name="preguntas[]" class="form-select recovery-question-select" required>
                        <option value="">Selecciona una pregunta...</option>
                        @foreach ($recoveryQuestions as $id => $texto)
                            <option value="{{ $id }}" @if ((string) $selected === (string) $id) selected @endif>
                                {{ $texto }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Respuesta {{ $orden }} <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="respuestas[]"
                        class="form-control"
                        value="{{ old('respuestas.' . $i, '') }}"
                        minlength="3"
                        maxlength="255"
                        autocomplete="off"
                        required
                        placeholder="{{ $configured && !old('respuestas.' . $i) ? '••••••••' : '' }}"
                    >
                </div>
            </div>
        @endfor

        <p class="text-muted small mb-3">
            <i class="ri-information-line me-1"></i>
            Mínimo 3 caracteres por respuesta. Las 3 respuestas deben ser distintas entre sí.
            No son sensibles a mayúsculas, tildes ni espacios extra. Se almacenan cifradas y nadie
            (ni el administrador) puede verlas.
            @if ($configured)
                <strong>Si guardas, las preguntas y respuestas anteriores se reemplazan.</strong>
            @endif
        </p>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary me-3">
                <i class="ri-save-line me-1"></i>{{ __('Guardar preguntas') }}
            </button>

            @if (session('status') === 'recovery-questions-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-success"
                >
                    {{ __('Preguntas actualizadas correctamente.') }}
                </div>
            @endif
        </div>
    </form>
</section>

<script>
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
