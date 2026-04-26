<x-guest-layout>

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Identidad verificada. Establece tu nueva contraseña.
    </p>

    <form method="POST" action="{{ route('recovery.reset.process') }}" id="recoveryResetForm" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bx bx-lock-alt"></i>
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    placeholder="Mínimo 8 caracteres"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bx bx-lock"></i>
                </span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required
                    autocomplete="new-password"
                    minlength="8"
                    placeholder="Repite la contraseña"
                >
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center">
            <button type="submit" class="btn btn-atlantico" id="submitBtn">
                <span id="submitText">
                    <i class="bx bx-save me-1"></i>Guardar nueva contraseña
                </span>
                <span id="submitSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Guardando…
                </span>
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.getElementById('recoveryResetForm').addEventListener('submit', function () {
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
