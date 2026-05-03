<x-guest-layout>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bx bx-check-circle fs-5"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
        @csrf

        <!-- Email -->
        <div class="mb-3">
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

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bx bx-lock-alt"></i>
                </span>
                <div class="pass-wrapper flex-grow-1">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    <button class="btn-show-pass" type="button" id="togglePassword" tabindex="-1" aria-label="Mostrar contraseña">
                        <i class="bx bx-hide" id="toggleIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Remember me -->
        <div class="mb-4 form-check">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label">Recuérdame</label>
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('recovery.method'))
                <a href="{{ route('recovery.method') }}" class="link-atlantico">
                    <i class="bx bx-help-circle me-1"></i>¿Olvidaste tu contraseña?
                </a>
            @endif

            <button type="submit" class="btn btn-atlantico" id="submitBtn">
                <span id="submitText">
                    <i class="bx bx-log-in-circle me-1"></i>Iniciar Sesión
                </span>
                <span id="submitSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Ingresando…
                </span>
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Show/hide password
        document.getElementById('togglePassword').addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon  = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bx-hide', 'bx-show');
            } else {
                input.type = 'password';
                icon.classList.replace('bx-show', 'bx-hide');
            }
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function () {
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
