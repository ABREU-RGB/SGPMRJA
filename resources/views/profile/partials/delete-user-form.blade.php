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
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content needs-validation" novalidate>
            @csrf
            @method('delete')

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                    <i class="ri-error-warning-line me-2"></i>Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-3">
                    Ingresa tu contraseña para confirmar. Esta acción no se puede deshacer.
                </p>

                <div class="mb-0">
                    <label for="password" class="form-label">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Tu contraseña actual"
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
                <button type="submit" class="btn btn-danger">
                    <i class="ri-delete-bin-line me-1"></i>{{ __('Eliminar permanentemente') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
