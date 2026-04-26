<x-guest-layout>

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Ingresa tu correo electrónico. Si tienes preguntas de seguridad configuradas, podrás responderlas para recuperar tu contraseña.
    </p>

    <form method="POST" action="{{ route('recovery.email.process') }}" id="recoveryEmailForm" novalidate>
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bx bx-envelope"></i>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="correo@empresa.com"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

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
