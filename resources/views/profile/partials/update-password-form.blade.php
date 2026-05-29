<p class="text-muted small mb-3">
    Asegúrate de usar una contraseña larga y única.
</p>

<form method="post" action="{{ route('password.update') }}" class="needs-validation" novalidate>
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">
            {{ __('Contraseña actual') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-lock-line"></i></span>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password"
                required
            >
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">
            {{ __('Nueva contraseña') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password"
                minlength="8"
                required
            >
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">
            {{ __('Confirmar nueva contraseña') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password"
                minlength="8"
                required
            >
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
        @if (session('status') === 'password-updated')
            <span
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="text-success small"
            >
                <i class="ri-check-double-line"></i> Contraseña actualizada
            </span>
        @endif
        <button type="submit" class="btn btn-profile-save is-password">
            <i class="ri-save-line me-1"></i>{{ __('Cambiar contraseña') }}
        </button>
    </div>
</form>
