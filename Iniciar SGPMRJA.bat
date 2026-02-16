@echo off
TITLE Sistema SGPMRJA - Panel de Control 🚀
COLOR 0A

:: --- RUTAS ---
set XAMPP_DIR=C:\xampp
set PROYECTO_DIR=C:\Users\Santi\Project\SGPMRJA
:: -------------

echo ==========================================
echo    INICIANDO SISTEMA (Santi)
echo ==========================================
echo.

:: 0. LIMPIEZA DE SESIONES (Logout Forzado)
echo [0/5] 🧹 Borrando sesiones anteriores...
:: Esto borra los archivos de sesion para obligar a loguearse de nuevo
del /q "%PROYECTO_DIR%\storage\framework\sessions\*" 2>nul
:: (El comando 2>nul oculta el error si la carpeta ya esta vacia)
echo       Sesiones purgadas.

:: 1. ENCENDER MOTORES (XAMPP)
echo [1/5] 🔧 Encendiendo XAMPP...
cd /d "%XAMPP_DIR%"
:: Usamos start simple, luego el script de detener se encargara de matarlos todos
start /MIN apache_start.bat
start /MIN mysql_start.bat

echo.
echo ⏳ Esperando 5 segundos...
timeout /t 5 >nul

:: 2. IR AL PROYECTO
cd /d "%PROYECTO_DIR%"

:: 3. MANTENIMIENTO
echo [2/5] 🔄 Git Pull...
call git pull origin main >nul 2>&1
echo [3/5] 📦 Composer...
call composer install --no-interaction --prefer-dist >NUL 2>&1

:: 4. ARRANQUE FINAL
echo.
echo [4/5] 🚀 ESTADO: LISTO. Abriendo navegador...
start http://127.0.0.1:8000

echo.
echo    ---------------------------------------
echo    SERVIDOR LARAVEL ACTIVO
echo    ---------------------------------------
echo.

php artisan serve