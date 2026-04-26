<x-guest-layout>

    <p class="text-muted mb-4" style="font-size: 0.875rem;">
        Elige cómo quieres recuperar tu contraseña.
    </p>

    <div class="d-grid gap-3 mb-4">
        <a href="{{ route('password.request') }}" class="btn btn-outline-primary text-start p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bx bx-envelope fs-2"></i>
                <div>
                    <div class="fw-semibold">Por correo electrónico</div>
                    <small class="text-muted">Te enviaremos un enlace para restablecerla. Requiere internet.</small>
                </div>
            </div>
        </a>

        <a href="{{ route('recovery.email.show') }}" class="btn btn-outline-success text-start p-3">
            <div class="d-flex align-items-center gap-3">
                <i class="bx bx-shield-quarter fs-2"></i>
                <div>
                    <div class="fw-semibold">Por preguntas de seguridad</div>
                    <small class="text-muted">Responde tus 3 preguntas configuradas. Funciona sin internet.</small>
                </div>
            </div>
        </a>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="link-atlantico">
            <i class="bx bx-arrow-back me-1"></i>Volver al inicio de sesión
        </a>
    </div>

</x-guest-layout>
