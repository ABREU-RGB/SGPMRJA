<x-guest-layout>

    @php
        $type  = session('lock_type', 'soft');
        $until = session('until');
    @endphp

    <div class="text-center mb-4">
        <i class="bx bx-lock-alt text-danger" style="font-size: 3rem;"></i>
    </div>

    @if ($type === 'hard')
        <h5 class="text-center mb-3">Recuperación bloqueada</h5>
        <p class="text-muted text-center mb-4" style="font-size: 0.875rem;">
            Por seguridad, tu cuenta excedió el número máximo de intentos fallidos de recuperación.
            Contacta al administrador del sistema para que la desbloquee.
        </p>
    @else
        <h5 class="text-center mb-3">Demasiados intentos</h5>
        <p class="text-muted text-center mb-4" style="font-size: 0.875rem;">
            Por seguridad, hemos bloqueado temporalmente la recuperación de esta cuenta.
            @if ($until)
                Inténtalo de nuevo después de
                <strong>{{ \Carbon\Carbon::parse($until)->format('H:i') }}</strong>.
            @else
                Inténtalo de nuevo más tarde.
            @endif
        </p>
    @endif

    <div class="text-center">
        <a href="{{ route('login') }}" class="btn btn-atlantico">
            <i class="bx bx-arrow-back me-1"></i>Volver al inicio de sesión
        </a>
    </div>

</x-guest-layout>
