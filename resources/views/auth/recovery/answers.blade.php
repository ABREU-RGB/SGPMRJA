<x-guest-layout>

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Responde las 3 preguntas de seguridad para verificar tu identidad. Las respuestas no son sensibles a mayúsculas.
    </p>

    @if ($errors->has('respuestas'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bx bx-error-circle fs-5"></i>
            {{ $errors->first('respuestas') }}
        </div>
    @endif

    <form method="POST" action="{{ route('recovery.questions.validate') }}" id="recoveryAnswersForm" novalidate>
        @csrf

        @foreach ($questions as $q)
            <div class="mb-3">
                <label for="respuesta_{{ $q['id'] }}" class="form-label">
                    <span class="badge bg-soft-primary text-primary me-1">{{ $q['orden'] }}</span>
                    {{ $q['pregunta'] }}
                </label>
                <input
                    id="respuesta_{{ $q['id'] }}"
                    type="text"
                    name="respuestas[{{ $q['id'] }}]"
                    class="form-control"
                    required
                    autocomplete="off"
                    maxlength="255"
                >
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
