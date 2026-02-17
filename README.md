# 🧵 Sistema de Gestión para Pedidos en Manufacturas R.J. Atlántico C.A.

> **Sistema web integral para la gestión textil, desarrollado con Laravel 10.**

Este proyecto es una solución tecnológica desarrollada por el **Grupo Textil de la Sección 536 del PNF en Informática de la UPTP "JJ Montilla"**, como parte del Proyecto Socio-Tecnológico III. Su objetivo es automatizar y optimizar los procesos operativos, administrativos y de producción de la empresa **Manufacturas R.J. Atlántico C.A.**

---

## 📘 Descripción General

El sistema permite la administración eficiente de todo el ciclo de vida de la producción textil, desde la gestión de clientes y pedidos hasta el control de inventario y la supervisión de la producción diaria. Implementando una arquitectura **MVC (Modelo-Vista-Controlador)**, garantiza un código organizado, escalable y mantenible.

### 🌟 Características Principales

#### 📦 Gestión Comercial
*   **Clientes**: Registro, edición y consulta de clientes con historial de pedidos.
*   **Pedidos**: Flujo completo desde la solicitud hasta la entrega. Control de estados (Pendiente, En Proceso, Completado).
*   **Cotizaciones**: Generación de presupuestos profesionales exportables a PDF.

#### 🏭 Gestión de Producción
*   **Órdenes de Producción**: Planificación y asignación de tareas a operarios.
*   **Producción Diaria**: Registro de avances por operario y control de eficiencia.
*   **Control de Calidad**: Monitoreo de estándares en cada etapa de fabricación.

#### 🧰 Gestión de Inventario
*   **Insumos**: Catálogo de materiales (telas, hilos, etc.) con control de costos.
*   **Movimientos**: Registro detallado de entradas y salidas de almacén.
*   **Alertas de Stock**: Notificaciones automáticas cuando los insumos alcanzan el nivel mínimo.

#### 📊 Reportes y Análisis
*   **Dashboard Interactivo**: KPIs en tiempo real (Stock, Órdenes en proceso, Producción total).
*   **Reportes PDF**: Generación de informes de eficiencia, consumo de materiales y rendimiento de operarios.

---

## 🛠️ Tecnologías Utilizadas

El sistema está construido sobre un stack moderno y robusto:

### Backend
*   **Laravel 10**: Framework PHP principal.
*   **PHP 8.1+**: Lenguaje de servidor.
*   **MySQL**: Base de datos relacional.
*   **Composer**: Gestión de dependencias PHP.

### Frontend
*   **Blade**: Motor de plantillas.
*   **Velzon Admin Template**: Interfaz de usuario profesional (basada en Bootstrap/Tailwind).
*   **Tailwind CSS**: Estilos modernos y responsivos.
*   **Vite**: Empaquetador de assets de alto rendimiento.
*   **JavaScript**: Interactividad y gráficos (ApexCharts, Chart.js).

---

## 👥 Roles y Permisos

El sistema cuenta con un control de acceso basado en roles (RBAC):

1.  **Administrador**: Control total del sistema, gestión de usuarios y configuraciones globales.
2.  **Supervisor**: Gestión de inventario, aprobación de órdenes de producción y monitoreo de reportes.
3.  **Operario**: Acceso limitado para registrar su producción diaria y visualizar tareas asignadas.

---

## 🚀 Instalación y Puesta en Marcha

Sigue estos pasos para desplegar el proyecto en un entorno local:

### Requisitos Previos
*   **XAMPP** (o cualquier servidor web con Apache y MySQL).
*   **Composer** instalado.
*   **Node.js** y **NPM** instalados.
*   **Git** (opcional).

### Pasos de Instalación

1.  **Clonar/Descargar el repositorio**:
    Copia los archivos del proyecto en tu directorio de servidor web (ej. `C:\xampp\htdocs\sistema-atlantico`).

2.  **Instalar dependencias de PHP**:
    ```bash
    composer install
    ```

3.  **Instalar dependencias de JavaScript**:
    ```bash
    npm install
    ```

4.  **Configurar el entorno**:
    *   Duplica el archivo `.env.example` y renómbralo a `.env`.
    *   Configura las credenciales de base de datos en el archivo `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=atlantico_db
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Generar clave de aplicación**:
    ```bash
    php artisan key:generate
    ```

6.  **Base de Datos**:
    *   Crea una base de datos vacía llamada `atlantico_db` en tu gestor MySQL (phpMyAdmin, etc.).
    *   Ejecuta las migraciones y seeders:
        ```bash
        php artisan migrate --seed
        ```
    *   *Alternativa*: Puedes importar el archivo SQL incluido en `database/atlantico_db.sql` si prefieres una base de datos pre-cargada.

7.  **Ejecutar el proyecto**:
    En una terminal:
    ```bash
    php artisan serve
    ```
    En otra terminal (para los estilos y scripts):
    ```bash
    npm run dev
    ```

8.  **Acceder**:
    Abre tu navegador en `http://127.0.0.1:8000`.

---

## 👨‍💻 Equipo de Desarrollo

**PNF Informática - UPTP "JJ Montilla" (Sección 536)**

*   **Emmanuel Arroyo** - *Desarrollador*
*   **Santiago Mendoza** - *Desarrollador*
*   **Johiner Orellana** - *Analista*
*   **Luis Rodriguez** - *Analista*
*   **Vanessa Lopez** - *Desarrolladora*
*   **Isabella Colmenarez** - *Analista*
*   **Alejandro Adam** - *Analista*

**Asesor Académico**: Juan Esteller
**Comunidad**: Manufacturas R.J. Atlántico C.A. (Acarigua, Edo. Portuguesa)

---

## 📄 Licencia

Este proyecto se alinea con el Plan de Desarrollo Económico de la Nación (Motor N.º 13 de Telecomunicaciones e Informática).

Licenciado bajo **Creative Commons Atribución – No Comercial – Compartir Igual 4.0 Internacional**.
Consulta los términos en: [creativecommons.org](https://creativecommons.org/licenses/by-nc-sa/4.0/deed.es)

---

## 📚 Documentación del Proyecto

Para evitar duplicidad y versiones inconsistentes:

- La documentación **oficial y vigente** está en `docs/`.
- `Documentacion/` se conserva como **legado/histórico**.
- Punto de entrada recomendado: `docs/README.md`.