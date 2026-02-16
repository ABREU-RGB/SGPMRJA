@echo off
TITLE Deteniendo Sistema SGPMRJA 🛑
COLOR 0C

:: --- CONFIGURACION ---
set PROYECTO_DIR=C:\Users\Santi\Project\SGPMRJA
:: ---------------------

echo ==========================================
echo    PROTOCOLO DE APAGADO OPTIMIZADO
echo    (Limpieza Profunda + Logout Seguro)
echo ==========================================
echo.

:: ---------------------------------------------------------
:: FASE 1: LIMPIEZA INTERNA (La forma elegante)
:: ---------------------------------------------------------
echo [1/4] 🧹 Ejecutando limpieza de Artisan...
cd /d "%PROYECTO_DIR%"

:: optimize:clear vacía caché de vistas, rutas y configs.
:: Esto asegura que al volver a iniciar, el sistema cargue cambios nuevos.
call php artisan optimize:clear >nul 2>&1
echo       - Cache del sistema purgada.

:: ---------------------------------------------------------
:: FASE 2: DETENER MOTORES
:: ---------------------------------------------------------
echo [2/4] 🛑 Deteniendo servicios...
taskkill /F /IM httpd.exe /T >nul 2>&1
taskkill /F /IM mysqld.exe /T >nul 2>&1
taskkill /F /IM php.exe /T >nul 2>&1
echo       - Servicios detenidos.

:: ---------------------------------------------------------
:: FASE 3: LOGOUT FORZADO (Quirúrgico)
:: ---------------------------------------------------------
echo [3/4] 🔐 Cerrando sesiones de usuario...
cd "%PROYECTO_DIR%\storage\framework\sessions"

:: Borramos todos los archivos PERO conservamos el .gitignore
:: Esto es lo que hace un profesional para cuidar su repositorio Git.
for %%i in (*) do if not "%%i"==".gitignore" del /q "%%i" >nul 2>&1

echo       - Archivos de sesion eliminados (Git protegido).

:: ---------------------------------------------------------
:: FASE 4: LIMPIEZA FINAL DE CONSOLAS
:: ---------------------------------------------------------
echo [4/4] 🚪 Cerrando entorno...
timeout /t 2 >nul

:: Matamos todas las consolas CMD para dejar el escritorio limpio
taskkill /F /IM cmd.exe >nul 2>&1