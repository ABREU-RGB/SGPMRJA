<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="flex-grow-1" style="min-width: 250px;">
        <p class="mb-1 fw-semibold text-danger">
            <i class="ri-error-warning-line me-1"></i>Esta acción es irreversible
        </p>
        <p class="text-muted small mb-0">
            Una vez eliminada la cuenta, todos los recursos y datos asociados se borran permanentemente.
            Descarga cualquier información que necesites antes de continuar.
        </p>
    </div>
    <button
        type="button"
        class="btn btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmUserDeletionModal"
    >
        <i class="ri-delete-bin-line me-1"></i>{{ __('Eliminar mi cuenta') }}
    </button>
</div>

<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true"
    x-data="{ show: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }"
    x-init="() => {
        if (show) {
            new bootstrap.Modal(document.getElementById('confirmUserDeletionModal')).show();
        }
    }"
    data-bs-backdrop="static"
>
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content danger-modal needs-validation" id="deleteAccountForm" novalidate>
            @csrf
            @method('delete')

            <div class="modal-header danger-modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="confirmUserDeletionModalLabel">
                    <i class="ri-error-warning-line"></i>Confirmar eliminación de cuenta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Ícono dramático + título --}}
                <div class="text-center mb-3">
                    <div class="danger-icon-circle mx-auto mb-3">
                        <i class="ri-delete-bin-2-line"></i>
                    </div>
                    <h5 class="mb-1">¿Estás seguro?</h5>
                    <p class="text-muted small mb-0">
                        Esta acción es <strong class="text-danger">irreversible</strong>. No podrás recuperar tu cuenta.
                    </p>
                </div>

                {{-- Lista de lo que se pierde --}}
                <div class="danger-loss-box mb-4">
                    <p class="fw-semibold text-danger mb-2 small">
                        <i class="ri-information-line me-1"></i>Al continuar perderás permanentemente:
                    </p>
                    <ul class="text-muted small mb-0 ps-4">
                        <li>Tu acceso al sistema con esta cuenta</li>
                        <li>Tu información personal y de contacto</li>
                        <li>Tus preguntas de seguridad configuradas</li>
                        <li>Todo historial y registro vinculado a tu usuario</li>
                    </ul>
                </div>

                {{-- Type-to-confirm --}}
                <div class="mb-3">
                    <label for="confirm_word" class="form-label small mb-1">
                        Para confirmar, escribe <code class="danger-confirm-word">ELIMINAR</code> en mayúsculas <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="confirm_word"
                        class="form-control text-center fw-semibold"
                        autocomplete="off"
                        placeholder="ELIMINAR"
                        spellcheck="false"
                    >
                </div>

                {{-- Password --}}
                <div class="mb-0">
                    <label for="password" class="form-label small mb-1">{{ __('Contraseña actual') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>{{ __('Cancelar') }}
                </button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="ri-delete-bin-line me-1"></i>{{ __('Eliminar permanentemente') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    'use strict';

    // Validación Bootstrap nativa
    window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);

    // Type-to-confirm + password requeridos para habilitar el botón
    var confirmInput = document.getElementById('confirm_word');
    var passwordInput = document.getElementById('password');
    var deleteBtn = document.getElementById('confirmDeleteBtn');
    var modalEl = document.getElementById('confirmUserDeletionModal');

    if (!confirmInput || !passwordInput || !deleteBtn) return;

    function refresh() {
        var matches = confirmInput.value === 'ELIMINAR';
        var hasPassword = passwordInput.value.length > 0;
        deleteBtn.disabled = !(matches && hasPassword);

        // Feedback visual sobre el campo de confirmación
        confirmInput.classList.remove('is-valid', 'is-invalid');
        if (confirmInput.value.length > 0) {
            confirmInput.classList.add(matches ? 'is-valid' : 'is-invalid');
        }
    }

    confirmInput.addEventListener('input', refresh);
    passwordInput.addEventListener('input', refresh);

    // Reset al cerrar modal
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            confirmInput.value = '';
            passwordInput.value = '';
            confirmInput.classList.remove('is-valid', 'is-invalid');
            deleteBtn.disabled = true;
        });
    }
})();
</script>
