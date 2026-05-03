<p class="text-muted small mb-3">
    Actualiza tu nombre y correo electrónico.
</p>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Nombre') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
            <input
                id="name"
                name="name"
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Correo electrónico') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-mail-line"></i></span>
            <input
                id="email"
                name="email"
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-2 mb-0 py-2 px-3" style="font-size: 0.875rem;">
                <i class="ri-error-warning-line me-1"></i>
                {{ __('Tu correo no está verificado.') }}
                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline" style="font-size: inherit;">
                    {{ __('Reenviar verificación') }}
                </button>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-success small mt-1"><i class="ri-check-line me-1"></i>Enlace enviado.</div>
                @endif
            </div>
        @endif
    </div>

    <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
        @if (session('status') === 'profile-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="text-success small"
            >
                <i class="ri-check-double-line"></i> Guardado
            </span>
        @endif
        <button type="submit" class="btn btn-profile-save is-info">
            <i class="ri-save-line me-1"></i>{{ __('Guardar cambios') }}
        </button>
    </div>
</form>
