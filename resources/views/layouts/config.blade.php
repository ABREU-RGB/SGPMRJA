<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
</div>

<!-- 
    Theme Customizer Structure (Hidden)
    Restored to prevent JS errors in assets/js/layout.js which expects these elements to exist.
    The customizer is hidden via d-none on the trigger.
-->
<div class="customizer-setting d-none">
    <div class="btn-info rounded-pill shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas"
        data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
        <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
    </div>
</div>

<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
    <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
        <h5 class="m-0 me-2 text-white">Theme Customizer</h5>
        <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn"
            data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <!-- Inputs expected by layout.js -->
        <div style="display:none;">
            <input id="customizer-layout01" name="data-layout" type="radio" value="vertical" class="form-check-input">
            <input id="customizer-layout02" name="data-layout" type="radio" value="horizontal" class="form-check-input">
            <input id="customizer-layout03" name="data-layout" type="radio" value="twocolumn" class="form-check-input">
            <input id="customizer-layout04" name="data-layout" type="radio" value="semibox" class="form-check-input">
        </div>
    </div>
</div>