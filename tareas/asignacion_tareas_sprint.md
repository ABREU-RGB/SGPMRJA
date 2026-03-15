<h1 align="center">📋 DOCUMENTO DE ASIGNACIÓN DE TAREAS — SGPMRJA</h1>
<h3 align="center">Sprint de Calidad y Funcionalidades Pendientes</h3>
<p align="center"><strong>Fecha:</strong> 15 de marzo de 2026 &nbsp;|&nbsp; <strong>Tech Lead:</strong> Emmanuel</p>
<p align="center">
  <img src="https://img.shields.io/badge/Vanessa-Frontend%20%26%20UI-9333ea?style=for-the-badge" alt="Vanessa"/>
  &nbsp;
  <img src="https://img.shields.io/badge/Santiago-Backend%20%26%20L%C3%B3gica-16a34a?style=for-the-badge" alt="Santiago"/>
</p>

---

> ⚠️ **REGLA DE ORO:** Antes de escribir una sola línea de código, debes pedirle a tu agente de IA que escanee y te explique los estándares globales del sistema (UI, CSS, DataTables, Reportes). Todo código nuevo debe ser 100% consistente con la arquitectura existente.

---

## 🌳 Estrategia de Ramas (Git Flow)

La rama de integración de este sprint es **`enmanuel`**. Ningún developer hace push directo a `enmanuel` ni a `main`. Todo trabajo ocurre en ramas de feature personales.

### 📐 Nomenclatura de Ramas

El formato es estricto y **no negociable**:

```
feat/<tu-nombre>/<id-tarea>-<descripcion-corta>
```

**Ejemplos:**
```bash
# Vanessa
feat/vanessa/v-01-paginacion-datatables
feat/vanessa/v-04-badge-juridico-dark-mode
feat/vanessa/v-10-fullscreen-persistence

# Santiago
feat/santiago/s-01-estandar-geografico
feat/santiago/s-03-encoding-utf8-pedidos
feat/santiago/s-08-abono-metodo-pago
```

### 🔄 Proceso de Trabajo Paso a Paso

**1. Crear tu rama de feature a partir de `enmanuel`:**
```bash
git checkout enmanuel
git pull origin enmanuel          # Siempre sincroniza antes de crear
git checkout -b feat/vanessa/v-04-badge-juridico-dark-mode
```

**2. Desarrollar, hacer commits descriptivos:**
```bash
# Formato de commit: tipo(ID): descripción en español
git add public/assets/css/custom.css
git commit -m "fix(V-04): agregar override dark mode para badge-tipo-juridico en custom.css"
```

**3. Subir tu rama al remoto:**
```bash
git push -u origin feat/vanessa/v-04-badge-juridico-dark-mode
```

**4. Crear el Pull Request hacia `enmanuel` (NO hacia `main`):**
```bash
gh pr create \
  --base enmanuel \
  --title "[V-04] Fix badge Jurídico en modo oscuro" \
  --body "## Cambios\n- Override dark mode en custom.css\n\n## Probado\n- Modo claro ✅\n- Modo oscuro ✅"
```

### ⚡ Reglas de Oro del Git

| Regla | Descripción |
|---|---|
| 🚫 **Nunca push directo** | Jamás hacer `git push` a `enmanuel` o `main` directamente |
| 🔄 **Sincronizar antes de empezar** | `git pull origin enmanuel` antes de crear cualquier rama |
| 🎯 **Una rama por tarea** | Cada ID de tarea = una rama = un PR |
| 📝 **Commits descriptivos** | Usar el formato `tipo(ID): descripción` |
| ✅ **PR no se auto-aprueba** | El Tech Lead (Emmanuel) revisa y hace el merge |
| 🔗 **Respetar dependencias** | No crear PR de una tarea bloqueada hasta que el prerequisito esté merged en `enmanuel` |

---

## 📊 Índice General de Tareas

<table>
<thead>
<tr>
<th>ID</th><th>Desarrollador</th><th>Módulo</th><th>Título</th><th>Complejidad</th>
</tr>
</thead>
<tbody>
<tr style="background:#faf5ff"><td><strong>V-01</strong></td><td>🟣 Vanessa</td><td>Global</td><td>Simplificación del Texto de Paginación</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-02</strong></td><td>🟣 Vanessa</td><td>Dashboard/Nav</td><td>Homepage: Navbar + Timeout de Sesión</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-03</strong></td><td>🟣 Vanessa</td><td>Dashboard</td><td>Refinamiento de Paleta de Colores</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-04</strong></td><td>🟣 Vanessa</td><td>Clientes</td><td>Fix Badge "Jurídico" en Modo Oscuro</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-05</strong></td><td>🟣 Vanessa</td><td>Productos</td><td>Imagen Opcional en Edición</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-06</strong></td><td>🟣 Vanessa</td><td>Productos</td><td>Badges de Estado Activo/Inactivo</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-07</strong></td><td>🟣 Vanessa</td><td>Productos</td><td>Reposición del Switch de Estatus</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-08</strong></td><td>🟣 Vanessa</td><td>Cotizaciones</td><td>Reubicación del Botón "+ Agregar Producto"</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-09</strong></td><td>🟣 Vanessa</td><td>Clientes</td><td>Fix Visual: Ficha de Detalles ⚠️ Espera S-01</td><td>🟢 Baja</td></tr>
<tr style="background:#faf5ff"><td><strong>V-10</strong></td><td>🟣 Vanessa</td><td>Global</td><td>Persistencia de Pantalla Completa</td><td>🟡 Media</td></tr>
<tr><td colspan="5"></td></tr>
<tr style="background:#f0fdf4"><td><strong>S-01</strong></td><td>🟢 Santiago</td><td>Clientes/Proveedores</td><td>⚠️ [PREREQUISITO] Estándar Geográfico</td><td>🟡 Media</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-02</strong></td><td>🟢 Santiago</td><td>Pedidos</td><td>Eliminar Botón "Agregar Cliente"</td><td>🟢 Baja</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-03</strong></td><td>🟢 Santiago</td><td>Pedidos</td><td>Corrección de Encoding UTF-8 en SweetAlert</td><td>🟢 Baja</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-04</strong></td><td>🟢 Santiago</td><td>Empleados</td><td>Fix Bug: Modal de Edición + Error de Estatus</td><td>🟡 Media</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-05</strong></td><td>🟢 Santiago</td><td>Proveedores</td><td>Unificación de Formulario ⚠️ Espera S-01</td><td>🟡 Media</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-06</strong></td><td>🟢 Santiago</td><td>Global</td><td>SoftDeletes: Inhabilitación Lógica + Historial</td><td>🔴 Alta</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-07</strong></td><td>🟢 Santiago</td><td>Clientes → Global</td><td>Filtros y Búsqueda Avanzada por Columna</td><td>🔴 Alta</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-08</strong></td><td>🟢 Santiago</td><td>Pedidos</td><td>Lógica de Abono por Método de Pago ⚠️ Espera S-02, S-03</td><td>🔴 Alta</td></tr>
<tr style="background:#f0fdf4"><td><strong>S-09</strong></td><td>🟢 Santiago</td><td>Global</td><td>Estándar Empresarial de Reportes PDF</td><td>🔴 Alta</td></tr>
</tbody>
</table>

---

## 🗺️ Mapa de Interdependencias

```
S-01 — Estándar Geográfico  (PREREQUISITO BLOQUEANTE)
  ├──→ V-09 — Fix Visual Clientes     ← Vanessa NO puede empezar hasta que S-01 esté en enmanuel
  └──→ S-05 — Unificación Proveedores ← Santiago NO puede empezar hasta que S-01 esté en enmanuel

S-02 — Eliminar Botón Cliente
  └──→ S-03 — Encoding UTF-8
        └──→ S-08 — Abono por Método de Pago  ← Tarea más compleja de Pedidos

Tareas completamente independientes (pueden ejecutarse en cualquier momento):
  Vanessa → V-01, V-02, V-03, V-04, V-05, V-06, V-07, V-08, V-10
  Santiago → S-04, S-06, S-07, S-09
```

---

<br>

<h1 style="background: linear-gradient(135deg, #9333ea, #c026d3); color: white; padding: 20px 24px; border-radius: 10px; margin: 0;">
🟣 BLOQUE 1 — VANESSA: Frontend &amp; UI/UX
</h1>

<p style="color: #9333ea; font-weight: bold; font-size: 1.1em; margin-top: 8px;">
10 Tareas · Enfoque: Interfaz de usuario, experiencia visual, correcciones CSS/JS en el frontend.
</p>

<p><strong>Orden de ejecución recomendado:</strong><br>
<code>V-01 → V-02 → V-03 → V-04 → V-05 → V-06 → V-07 → V-08 → V-10 → V-09 (última, espera S-01)</code>
</p>

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-01 · Simplificación del Texto de Paginación en DataTables
</h2>

**Rama:** `feat/vanessa/v-01-paginacion-datatables` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado
Los DataTables del sistema generan automáticamente el texto verboso: _"Mostrando registros del 1 al 10 de un total de 47 registros (filtrados de 47 registros totales)"_. DataTables permite personalizar esto directamente con la opción `language.info`.

### 🎯 Objetivo
Cambiar el texto informativo de paginación en **todos** los DataTables del sistema a un formato conciso: _"Mostrando 1–10 de 47 registros"_.

### 📁 Archivos clave
- Todos los archivos `*/scripts/main.blade.php` que inicializan DataTables.
- Buscar configuración global de idioma con: `grep -r "language:" resources/views/admin`

### ✅ Criterios de Aceptación
- [ ] El texto en **todos** los módulos muestra el formato `"Mostrando X–Y de Z registros"`.
- [ ] El texto de búsqueda vacía dice `"No se encontraron resultados"`.
- [ ] La solución está centralizada — si hay inicialización global de DataTables, modificarla en un solo lugar; si no, crear un objeto de configuración `window.dtLang` compartido e importarlo.
- [ ] Ningún módulo quedó con el texto antiguo verboso.

### 🤖 Prompt para tu agente de IA
> "Escanea todos los archivos en `resources/views/admin/` que inicializan DataTables buscando `DataTable({`. Identifica si existe una configuración de idioma centralizada o si cada módulo la tiene por separado. Propón la solución más limpia para cambiar el texto de paginación `language.info` de todos los DataTables a `'Mostrando _START_–_END_ de _TOTAL_ registros'` sin duplicar código."

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-02 · Homepage: Correcciones de Navbar y Timeout de Sesión
</h2>

**Rama:** `feat/vanessa/v-02-navbar-session-timeout` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado
- **Timeout de sesión:** `config/session.php` línea 34 → `'lifetime' => env('SESSION_LIFETIME', 120)` — **120 minutos (2 horas)**. Demasiado extenso para un sistema con datos sensibles.
- **Navbar:** El logo `atlantico-logo-wide.png` se renderiza en `header.blade.php` líneas 9–14 y colisiona visualmente con el botón "Inicio".
- **Botón Cerrar Sesión:** Puede estar desproporcional en el dropdown de usuario del header.

### 🎯 Objetivo
1. Reducir el timeout a **30 minutos** cambiando `SESSION_LIFETIME=30` en el archivo `.env`.
2. Corregir el espaciado/layout del navbar para que el logo no colisione con el botón "Inicio".
3. Ajustar el tamaño del botón "Cerrar Sesión" si es desproporcional.

### 📁 Archivos clave
- `.env` — variable `SESSION_LIFETIME`
- `resources/views/admin/layouts/header.blade.php`
- `resources/views/admin/layouts/sidebar.blade.php`
- `public/assets/css/custom.css` — ajuste de tamaño del botón (todos los cambios CSS van aquí)

### ✅ Criterios de Aceptación
- [ ] La sesión expira a los 30 minutos de inactividad y redirige al login.
- [ ] El logo y el botón "Inicio" tienen separación visual adecuada en desktop y tablet.
- [ ] El botón "Cerrar Sesión" es proporcional al resto de controles del header.
- [ ] Layout correcto en modo claro y oscuro.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-03 · Refinamiento de Paleta de Colores del Dashboard
</h2>

**Rama:** `feat/vanessa/v-03-dashboard-paleta-colores` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado
La auditoría de `resources/views/dashboard.blade.php` revela que los widgets KPI usan clases Bootstrap del tema (`bg-soft-primary`, `bg-soft-success`, `bg-soft-info`, `bg-soft-warning`). Estas generan tonos pasteles del framework que no armonizan con el Azul Marino Institucional `#1e3c72` del sistema.

### 🎯 Objetivo
Oscurecer y armonizar los colores de las tarjetas KPI para que sean consistentes con la identidad visual del sistema. **Importante:** solo ajustar los que visualmente no armonizan. No rediseñar el dashboard completo.

### 📁 Archivos clave
- `resources/views/dashboard.blade.php` — para entender qué clases se usan
- `public/assets/css/custom.css` — **ÚNICO archivo donde deben ir los overrides** (jamás en el blade)

### ✅ Criterios de Aceptación
- [ ] Las tarjetas KPI del dashboard armonizan visualmente con `#1e3c72`.
- [ ] Todos los cambios de color están en `custom.css`, no en el blade.
- [ ] El dashboard se ve correctamente en modo claro y oscuro.
- [ ] No se afecta ningún otro módulo del sistema.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-04 · Fix Badge "Jurídico" en Modo Oscuro
</h2>

**Rama:** `feat/vanessa/v-04-badge-juridico-dark-mode` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Bug exacto encontrado en auditoría
En `resources/views/admin/clientes/index.blade.php`, **líneas 193–200**, el badge jurídico está definido en un `<style>` inline del blade:

```css
.badge-tipo-juridico {
    background-color: rgba(111, 66, 193, 0.15);
    color: #6f42c1;  /* ← Purple puro: casi invisible sobre fondo oscuro */
}
```

En modo oscuro (`[data-bs-theme="dark"]`), `#6f42c1` sobre fondo oscuro tiene contraste insuficiente. El sistema YA tiene el patrón correcto para esto: overrides en `public/assets/css/custom.css` bajo el selector `[data-bs-theme="dark"]`. Solo hay que seguirlo.

### 🎯 Objetivo
Agregar un override **exclusivamente en `custom.css`** que mejore el contraste del badge jurídico en dark mode sin tocar el blade ni afectar el modo claro.

**Tono sugerido para dark mode:** `#c4b5fd` (Violet-300 — el mismo que ya usa el sistema para `badge-soft-info` en dark mode, según el estándar existente).

### 📁 Archivos clave
- `public/assets/css/custom.css` — **ÚNICO archivo a modificar**
- `resources/views/admin/clientes/index.blade.php` líneas 188–201 — solo lectura, para entender las clases

### ✅ Criterios de Aceptación
- [ ] En modo oscuro: badge "Jurídico" es claramente legible.
- [ ] En modo claro: badge se ve igual que antes (sin regresión).
- [ ] El selector en `custom.css` es: `[data-bs-theme="dark"] .badge-tipo-juridico`.
- [ ] También verificar y corregir si `badge-tipo-natural` y `badge-tipo-gubernamental` tienen problema de contraste en dark mode.

### 🤖 Prompt para tu agente de IA
> "Lee el bloque de estilos `[data-bs-theme='dark']` en `public/assets/css/custom.css`. Ese es el patrón que usa el sistema para overrides de badges en dark mode. Ahora lee las líneas 188–201 de `resources/views/admin/clientes/index.blade.php` para ver los colores actuales de `.badge-tipo-juridico`. Siguiendo exactamente el mismo patrón del sistema, dime qué líneas agregar a `custom.css` para que el badge 'Jurídico' tenga buen contraste en dark mode usando Violet-300 (#c4b5fd)."

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-05 · Imagen Opcional en Edición de Producto
</h2>

**Rama:** `feat/vanessa/v-05-producto-imagen-opcional` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Bug localizado
La auditoría de `ProductoController::update()` (`app/Http/Controllers/ProductoController.php`, línea 110) confirma que el **backend ya es correcto**:
```php
'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
```
El backend NO requiere imagen en edición. El bug está en el **frontend**: el modal de edición probablemente tiene `<input type="file" required>` en el HTML, o el formulario no envía `enctype="multipart/form-data"` correctamente.

### 🎯 Objetivo
1. Verificar y eliminar el atributo `required` del `input[type="file"]` en el modal de edición.
2. Mostrar una preview de la imagen actual junto al campo de subida.
3. Garantizar que el formulario guarda sin necesidad de subir nueva imagen.

### 📁 Archivos clave
- `resources/views/admin/productos/index.blade.php` — contiene el modal de edición

### ✅ Criterios de Aceptación
- [ ] Editar un producto sin cambiar la imagen → guarda exitosamente sin errores.
- [ ] La imagen existente permanece tras editar sin subir nueva.
- [ ] El modal de edición muestra una preview de la imagen actual.
- [ ] Al subir una nueva imagen → reemplaza correctamente la anterior.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-06 · Badges de Estado Activo/Inactivo en Listado de Productos
</h2>

**Rama:** `feat/vanessa/v-06-producto-badges-estado` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado
El modelo `Producto` tiene el campo `estado` (boolean, `app/Models/Producto.php` línea 27). El DataTable de productos no tiene diferenciación visual de estado mediante badges, a diferencia de otros módulos que sí los tienen.

### 🎯 Objetivo
Agregar badges visuales de estado en la columna del DataTable de productos:
- **Activo** → Badge verde (seguir el estándar `status-aprobada`: `#6ee7b7` dark mode, verde suave light mode).
- **Inactivo** → Badge gris (seguir el estándar `status-cancelado`).

### 📁 Archivos clave
- `resources/views/admin/productos/index.blade.php` — render de columna en DataTable
- `public/assets/css/custom.css` — reutilizar las clases de badge existentes si ya están definidas

### ✅ Criterios de Aceptación
- [ ] La columna de estado muestra badge verde "Activo" o gris "Inactivo".
- [ ] Los badges se ven correctamente en modo claro y oscuro.
- [ ] Se reutilizan las clases de badge del sistema (no crear nuevas clases si ya existen).

### 🤖 Prompt para tu agente de IA
> "Lee el bloque de estilos de badges de estado en `public/assets/css/custom.css` (busca `status-aprobada`, `status-cancelado`). Luego léeme cómo se renderiza la columna de estado en el DataTable de otro módulo que ya tenga badges (por ejemplo, el de Cotizaciones). Quiero aplicar el mismo patrón exacto en `resources/views/admin/productos/index.blade.php` para el campo booleano `estado`."

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-07 · Reposición del Switch de Estatus al Final del Formulario
</h2>

**Rama:** `feat/vanessa/v-07-producto-switch-estatus` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado
En UX, el control "Estatus/Activar" debe estar al final del formulario: el usuario primero completa todos los datos y luego decide si activa el registro. En el formulario actual de Productos el switch puede estar en posición intermedia, lo que es anti-intuitivo.

### 🎯 Objetivo
Mover el control de **Estatus** (toggle/checkbox) al final de los formularios de Creación y Edición de Productos, justo antes de los botones de acción.

### 📁 Archivos clave
- `resources/views/admin/productos/index.blade.php` — formularios de los modales de Crear y Editar

### ✅ Criterios de Aceptación
- [ ] El campo "Estatus" es el último campo visible antes de los botones Guardar/Cancelar.
- [ ] Aplica tanto en el formulario de Crear como en el de Editar.
- [ ] La funcionalidad del switch no cambia, solo su posición visual.
- [ ] Verificar si el mismo problema existe en otros formularios del sistema y documentarlo.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-08 · Cotizaciones: Reubicación del Botón "+ Agregar Producto"
</h2>

**Rama:** `feat/vanessa/v-08-cotizaciones-boton-agregar` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Ubicación exacta del bug
La auditoría de `resources/views/admin/cotizaciones/modals.blade.php`, **línea 411**, confirma que el botón está en el `card-header`:

```html
<div class="card-header ... d-flex align-items-center justify-content-between">
    <h6 ...>2. Productos de la Cotización</h6>
    <button type="button" class="btn btn-sm btn-success px-3" id="add-producto-item">
        <i class="ri-add-line me-1"></i>Agregar Producto
    </button>
</div>
```

El botón debe estar **después** de la lista de productos, no antes. Es anti-UX que el usuario vea el botón "Agregar" antes de ver el área donde se agregan los ítems.

### 🎯 Objetivo
Mover el botón `#add-producto-item` del `card-header` al final del `card-body`, debajo del contenedor `#productos-container`. El comportamiento funcional no debe cambiar.

### 📁 Archivos clave
- `resources/views/admin/cotizaciones/modals.blade.php` — líneas 401–422 (mover el botón)
- `resources/views/admin/cotizaciones/scripts/main.blade.php` — verificar que el event listener `$('#add-producto-item').on('click', ...)` usa ID y no depende de la posición DOM

### ✅ Criterios de Aceptación
- [ ] El botón "+ Agregar Producto" está al final del listado de productos, no en el header.
- [ ] El botón sigue funcionando correctamente (agrega filas de producto).
- [ ] El modal de cotización sigue el flujo lógico de datos.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-09 · Fix Visual Módulo Clientes: Ficha de Detalles
</h2>

**Rama:** `feat/vanessa/v-09-clientes-ficha-detalles` | **Complejidad:** 🟢 Baja

> ⚠️ **BLOQUEADA — Esperar que S-01 esté mergeado en `enmanuel` antes de iniciar esta tarea.**

### 📋 Contexto auditado
- El `#viewModal` del módulo Clientes muestra `id="view-ciudad"` con label incorrecto. El estándar correcto (definido en S-01) usa "Municipio", no "Ciudad".
- El badge de "Estatus" aparece tanto en el badge de color como en texto explícito dentro de la ficha — redundancia visual innecesaria.

### 🎯 Objetivo
1. Aplicar el estándar geográfico de S-01 en los labels de la vista de detalles (`#viewModal`).
2. Eliminar el texto redundante "Estatus" de la ficha, dejando solo el badge de color.

### 📁 Archivos clave
- `resources/views/admin/clientes/index.blade.php` — sección del `#viewModal`

### ✅ Criterios de Aceptación
- [ ] La ficha de detalles muestra el label "Municipio:" con el valor correcto del campo `ciudad`.
- [ ] El texto redundante "Estatus" fue eliminado; el badge de color es auto-descriptivo.
- [ ] La ficha se ve correctamente en modo claro y oscuro.

---

<h2 style="color: #9333ea; border-left: 5px solid #9333ea; padding-left: 12px;">
🎨 Tarea V-10 · Persistencia de Pantalla Completa (Fullscreen)
</h2>

**Rama:** `feat/vanessa/v-10-fullscreen-persistence` | **Complejidad:** 🟡 Media | **Bloqueos:** Ninguno

### 📋 Contexto auditado
El header del sistema (`resources/views/admin/layouts/header.blade.php`, **línea 200**) tiene:
```html
<button type="button" ... data-toggle="fullscreen">
    <i class='bx bx-fullscreen fs-22'></i>
</button>
```
El sistema YA implementa persistencia de tema oscuro/claro via `localStorage` en `admin/layouts/app.blade.php` líneas 24–39. No existe un mecanismo equivalente para el estado fullscreen. Al navegar entre módulos, el modo pantalla completa se pierde.

### 🎯 Objetivo
Implementar persistencia del estado fullscreen usando `localStorage`, siguiendo **exactamente** el mismo patrón que el sistema usa para el tema oscuro/claro. No inventar nueva arquitectura.

### 📁 Archivos clave
- `resources/views/admin/layouts/header.blade.php` — contiene el botón (línea 200)
- `resources/views/admin/layouts/app.blade.php` — **patrón de `localStorage` a imitar** (líneas 24–39)
- `public/assets/js/` — verificar si el toggle fullscreen del layout está definido aquí

### ✅ Criterios de Aceptación
- [ ] Activar fullscreen → navegar a otro módulo → el sistema permanece en fullscreen.
- [ ] Desactivar fullscreen → navegar → el sistema permanece en modo normal.
- [ ] El estado persiste al recargar la página (F5).
- [ ] El ícono cambia correctamente entre `bx-fullscreen` y `bx-exit-fullscreen`.
- [ ] No se rompe ningún otro componente del layout.

### 🤖 Prompt para tu agente de IA
> "Lee las líneas 24–39 de `resources/views/admin/layouts/app.blade.php`. Ese es el código que persiste el tema oscuro usando `localStorage`. Ahora lee la línea 200 de `header.blade.php` para ver el botón de fullscreen. Siguiendo exactamente ese mismo patrón de `localStorage`, implementa la persistencia del estado fullscreen. No uses librerías externas, solo el JavaScript nativo que ya existe en el proyecto."

---

<br>

<h1 style="background: linear-gradient(135deg, #16a34a, #15803d); color: white; padding: 20px 24px; border-radius: 10px; margin: 0;">
🟢 BLOQUE 2 — SANTIAGO: Backend &amp; Lógica
</h1>

<p style="color: #16a34a; font-weight: bold; font-size: 1.1em; margin-top: 8px;">
9 Tareas · Enfoque: Lógica de negocio, corrección de bugs de backend, modelos, controladores y arquitectura de datos.
</p>

<p><strong>Orden de ejecución recomendado:</strong><br>
<code>S-01 → S-02 → S-03 → S-04 → S-05 (espera S-01) → S-06 → S-07 → S-08 (espera S-02, S-03) → S-09</code>
</p>

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-01 · [PREREQUISITO BLOQUEANTE] Estándar de Ubicación Geográfica
</h2>

**Rama:** `feat/santiago/s-01-estandar-geografico` | **Complejidad:** 🟡 Media

> ⚠️ **Esta tarea bloquea a: V-09 (Vanessa) y S-05 (Santiago). Completarla primero y mergear a `enmanuel` lo antes posible.**

### 📋 Contexto auditado — Inconsistencia severa detectada
La auditoría revela una inconsistencia crítica en la nomenclatura geográfica:

| Ubicación en el código | Label mostrado | Campo en DB |
|---|---|---|
| `clientes/index.blade.php` línea 774 (formulario) | **"Municipio"** | `ciudad` |
| `clientes/index.blade.php` línea 571 (modal ver) | **"Ciudad"** | `ciudad` |
| `clientes/reporte_pdf.blade.php` línea 318 (PDF) | **"Ciudad"** | `ciudad` |
| `app/Models/Direccion.php` | campo: `ciudad` | `ciudad` |
| `Proveedor` jurídico | campo directo: `direccion` (sin estado/ciudad) | `direccion` |

El estándar venezolano usa **Estado + Municipio**. El campo `ciudad` en DB almacena un municipio (cargado por `municipios-venezuela.js`). La solución **NO requiere migración de base de datos**; solo estandarizar los labels de la UI.

### 🎯 Objetivo
Definir y aplicar el estándar geográfico del sistema:
1. Campo `ciudad` en DB → label **"Municipio"** en toda la UI.
2. Campo `estado` → label **"Estado"** en toda la UI.
3. Unificar labels en formularios (crear/editar) y vistas de detalle del módulo Clientes.
4. Actualizar el header de la columna en el reporte PDF de Clientes de "Ciudad" a "Municipio".
5. Agregar un comentario de documentación en `public/assets/js/municipios-venezuela.js` explicando el estándar.

### 📁 Archivos clave
- `resources/views/admin/clientes/index.blade.php` — formularios y vistas
- `resources/views/admin/clientes/reporte_pdf.blade.php` — encabezado de tabla (línea 318)
- `public/assets/js/municipios-venezuela.js` — agregar comentario de documentación
- `app/Models/Direccion.php` — modelo de referencia (NO modificar)

### ✅ Criterios de Aceptación
- [ ] Formulario crear/editar Clientes: labels "Estado" y "Municipio" correctos.
- [ ] Modal de detalles de Cliente: muestra "Estado:" y "Municipio:" (coordinar con Vanessa para V-09).
- [ ] Reporte PDF de Clientes: encabezado de columna dice "Municipio" (no "Ciudad").
- [ ] El JS `municipios-venezuela.js` sigue funcionando correctamente (no cambiar lógica, solo documentar).
- [ ] Ninguna funcionalidad se rompe.

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-02 · Pedidos: Eliminar Botón "Agregar Cliente"
</h2>

**Rama:** `feat/santiago/s-02-pedidos-eliminar-boton-cliente` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Líneas exactas del bug
En `resources/views/admin/pedidos/index.blade.php`, **líneas 916–919**:

```html
<button type="button" class="btn btn-outline-success"
    id="open-add-cliente-modal" title="Agregar nuevo cliente">
    <i class="ri-user-add-line"></i>
</button>
```

También hay código JavaScript asociado en **líneas 2673–2714** del mismo archivo. Por flujo de negocio, los clientes solo se crean/seleccionan desde el módulo de Cotizaciones.

**Nota adicional:** Existen archivos residuales en el directorio que deben ser eliminados:
- `resources/views/admin/pedidos/index.blade.php.bak`
- `resources/views/admin/pedidos/index.blade.php.utf8`

### 🎯 Objetivo
1. Eliminar el botón `#open-add-cliente-modal` (líneas 916–919).
2. Eliminar el JavaScript asociado a ese botón (líneas 2673–2714).
3. Eliminar los archivos residuales `.bak` y `.utf8`.

### 📁 Archivos clave
- `resources/views/admin/pedidos/index.blade.php` — líneas 916–919 y 2673–2714
- `resources/views/admin/pedidos/index.blade.php.bak` — **eliminar**
- `resources/views/admin/pedidos/index.blade.php.utf8` — **eliminar**

### ✅ Criterios de Aceptación
- [ ] El formulario de Pedidos ya no muestra el botón "Agregar Cliente".
- [ ] El autocomplete de búsqueda de cliente existente sigue funcionando perfectamente.
- [ ] No queda código JavaScript huérfano relacionado con el botón eliminado.
- [ ] Los archivos `.bak` y `.utf8` han sido eliminados del directorio.

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-03 · Pedidos: Corrección de Encoding UTF-8 en SweetAlert
</h2>

**Rama:** `feat/santiago/s-03-pedidos-encoding-utf8` | **Complejidad:** 🟢 Baja | **Bloqueos:** Ninguno — hacer antes de S-08

### 📋 Contexto auditado — Bug exacto confirmado
En `resources/views/admin/pedidos/index.blade.php`, **línea 2479**, se encontró:

```javascript
// ❌ Actualmente (caracteres corruptos):
text: "¿No podrás revertir esto!",      // aparece: "?No podr?s revertir esto!"
confirmButtonText: 'Sí, eliminarlo!',    // aparece: "S?, eliminarlo!"

// ✅ Debe verse así:
text: "¡No podrás revertir esto!",
confirmButtonText: 'Sí, eliminarlo!',
```

La presencia de los archivos `.bak` y `.utf8` (limpiados en S-02) sugiere intentos previos fallidos de corrección. El `<meta charset="UTF-8">` ya existe en `app.blade.php` — el problema es el encoding del archivo en sí.

### 🎯 Objetivo
Corregir todas las strings de SweetAlert con caracteres especiales del español en el archivo `pedidos/index.blade.php`. La solución más segura es localizar y reescribir directamente las strings afectadas.

### 📁 Archivos clave
- `resources/views/admin/pedidos/index.blade.php` — buscar con `grep -n "podr\|Swal\|eliminarlo\|seguro" ` para localizar todos los textos afectados

### ✅ Criterios de Aceptación
- [ ] Los mensajes SweetAlert del módulo muestran correctamente: `¿Estás seguro?`, `¡No podrás revertir esto!`, `Sí, eliminarlo!`.
- [ ] No hay símbolos `?` en rombo negro en ningún texto del módulo.
- [ ] El `<meta charset="UTF-8">` sigue presente en el layout (ya está — no tocar).

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-04 · Empleados: Fix del Modal de Edición + Error de Estatus
</h2>

**Rama:** `feat/santiago/s-04-empleados-fix-modal-edicion` | **Complejidad:** 🟡 Media | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Causa raíz del bug
La auditoría de `EmpleadoController::update()` (líneas 161–209) revela que los campos `documento_identidad`, `tipo_documento`, `cargo`, `departamento` y `estado` son **todos `required`** en la validación. El error _"El documento de identidad es obligatorio (and 1 more error)"_ ocurre porque el **modal de edición no pre-llena** estos campos al recibir el JSON del endpoint `edit()`.

El `EmpleadoController::edit()` (líneas 140–159) **sí devuelve** todos los datos correctamente en JSON (incluye `telefono`, `direccion`, `ciudad` normalizados). El bug está en el **JavaScript del modal** que no mapea correctamente los campos recibidos a los `id` de los inputs.

Adicionalmente hay un **layout desalineado** en la sección "Contacto y Ubicación" del modal de detalles.

### 🎯 Objetivo
1. Identificar qué campos del JSON recibido no se mapean al formulario de edición.
2. Corregir el JavaScript para que `estado`, `documento_identidad`, `tipo_documento`, `cargo` y `departamento` se poblen correctamente.
3. Corregir el layout desalineado en la sección "Contacto y Ubicación" del modal de detalles.

### 📁 Archivos clave
- `resources/views/admin/empleados/index.blade.php` — modal de edición + JS de carga de datos
- `app/Http/Controllers/EmpleadoController.php` — método `edit()` (líneas 140–159) y `update()` (líneas 161–209)

### ✅ Criterios de Aceptación
- [ ] Editar un empleado existente: **todos** los campos se pre-llenan correctamente.
- [ ] El formulario de edición se envía sin errores de validación cuando los datos son válidos.
- [ ] El estatus del empleado se carga y muestra correctamente en el select/toggle.
- [ ] La sección "Contacto y Ubicación" del modal de detalles está visualmente alineada.

### 🤖 Prompt para tu agente de IA
> "Lee el método `edit()` del `EmpleadoController` (líneas 140–159) y el modal de edición en `empleados/index.blade.php`. Analiza el código JavaScript que toma la respuesta JSON del endpoint `/empleados/{id}/edit` y rellena el formulario. El error de validación dice 'El documento de identidad es obligatorio'. Busca el campo con nombre `documento_identidad` en el formulario y verifica si su valor se setea en el callback AJAX. Identifica todos los campos del JSON que no se mapean correctamente."

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-05 · Proveedores: Unificación de Formulario y Campo Geográfico
</h2>

**Rama:** `feat/santiago/s-05-proveedores-unificacion-form` | **Complejidad:** 🟡 Media

> ⚠️ **BLOQUEADA — Esperar que S-01 esté mergeado en `enmanuel` antes de iniciar.**

### 📋 Contexto auditado
El modelo `Proveedor` (`app/Models/Proveedor.php`) tiene dos tipos con lógica distinta:
- **Natural**: usa relación con `Persona` → datos normalizados (igual que Cliente).
- **Jurídico**: usa campos directos en tabla `proveedor` (`razon_social`, `rif`, `direccion`, `telefono`, `email`) → **NO usa el modelo `Direccion`** → no tiene `estado` territorial ni `ciudad`/municipio separados.

### 🎯 Objetivo
1. Unificar la nomenclatura del formulario de Proveedores con la de Clientes:
   - Sección "Datos Personales" → **"Datos del Proveedor"**
   - Mismos bloques: **Identificación | Contacto | Ubicación**
2. Para proveedores **jurídicos**: agregar campos **Estado** y **Municipio** al formulario y vista de detalles. Verificar primero si la tabla `proveedor` ya tiene columnas para esto; si no, crear una migración.
3. Aplicar el estándar geográfico de S-01 en este módulo.

### 📁 Archivos clave
- `resources/views/admin/proveedores/index.blade.php`
- `app/Models/Proveedor.php`
- `app/Http/Controllers/ProveedorController.php`
- `database/migrations/` — si se requiere nueva migración: `php artisan make:migration add_ubicacion_to_proveedor_table`

### ✅ Criterios de Aceptación
- [ ] El formulario de Proveedores tiene los mismos bloques de sección que el de Clientes.
- [ ] Proveedores jurídicos tienen campos de Estado y Municipio en formulario y vista de detalles.
- [ ] La nomenclatura de secciones es idéntica a Clientes (Identificación / Contacto / Ubicación).
- [ ] Si hay migración: está correctamente versionada y ejecutada con `php artisan migrate`.

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-06 · SoftDeletes: Inhabilitación Lógica + Historial
</h2>

**Rama:** `feat/santiago/s-06-softdeletes-inhabilitacion-ui` | **Complejidad:** 🔴 Alta | **Bloqueos:** Ninguno

### 📋 Contexto auditado — Verdad auditada (muy importante)
La auditoría confirma que **SoftDeletes YA ESTÁ IMPLEMENTADO** en el backend de TODOS los modelos principales:

| Modelo | `use SoftDeletes` | Campo `deleted_at` |
|---|---|---|
| `Cliente` | ✅ SÍ | ✅ SÍ |
| `Proveedor` | ✅ SÍ | ✅ SÍ |
| `Producto` | ✅ SÍ | ✅ SÍ |
| `Pedido` | ✅ SÍ | ✅ SÍ |
| `Insumo` | ✅ SÍ | ✅ SÍ |
| `Empleado` | ✅ SÍ | ✅ SÍ |
| `OrdenProduccion` | ✅ SÍ | ✅ SÍ |
| `Cotizacion` | ✅ SÍ | ✅ SÍ |

**Lo que NO existe (y hay que construir):**
1. Interfaz para ver registros soft-deleted (historial/inactivos).
2. Semántica clara de "Inhabilitar" vs "Eliminar" en la UI.
3. Protección en frontend para evitar eliminaciones físicas accidentales.

### 🎯 Objetivo
1. **Cambiar la semántica del botón "Eliminar"** en Clientes, Proveedores y Productos → renombrarlo a **"Inhabilitar"** con ícono apropiado (`ri-user-unfollow-line` o similar).
2. Al "Inhabilitar": el registro NO desaparece de la tabla; en cambio, su badge cambia a "Inactivo" y el campo `estatus` se pone en `'inactivo'`.
3. **Agregar un toggle de vista** en la tabla: "Solo Activos" (default) ↔ "Ver Historial (Inactivos)".
4. Los registros en historial son de **solo lectura**: sin botones Editar ni Inhabilitar, solo "Ver".

### 📁 Archivos clave
- `resources/views/admin/clientes/index.blade.php`
- `resources/views/admin/proveedores/index.blade.php`
- `resources/views/admin/productos/index.blade.php`
- `app/Http/Controllers/ClienteController.php`
- `app/Http/Controllers/ProveedorController.php`
- `app/Http/Controllers/ProductoController.php`
- `routes/web.php` — verificar rutas de destroy/restore

### ✅ Criterios de Aceptación
- [ ] El botón "Eliminar" está renombrado a "Inhabilitar" en los 3 módulos.
- [ ] Al Inhabilitar un cliente, el registro muestra badge "Inactivo" en la tabla.
- [ ] Existe un control toggle "Activos / Historial" funcional.
- [ ] Los registros en historial no tienen botones de editar o inhabilitar (solo Ver).
- [ ] El comportamiento es idéntico en Clientes, Proveedores y Productos.

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-07 · Filtros y Búsqueda Avanzada por Columna
</h2>

**Rama:** `feat/santiago/s-07-clientes-filtros-busqueda` | **Complejidad:** 🔴 Alta | **Bloqueos:** Ninguno

### 📋 Contexto auditado
Los módulos usan el buscador global de DataTables (un solo campo que filtra todas las columnas). No existe filtrado por columna específica. Esto impide al usuario final buscar eficientemente sin conocer el nombre exacto del registro.

### 🎯 Objetivo
Implementar filtros específicos en el módulo de **Clientes** (primera iteración, luego se replica el patrón). Los filtros requeridos:
- **Tipo de Cliente** (Natural / Jurídico / Gubernamental) → `<select>`
- **Estatus** (Activo / Inactivo) → `<select>`
- **Estado Territorial** (Venezuela) → `<select>` cargado desde `municipios-venezuela.js`
- **Cédula/RIF** → campo de texto para búsqueda directa

### 📁 Archivos clave
- `resources/views/admin/clientes/index.blade.php` — UI de filtros + inicialización DataTable
- `app/Http/Controllers/ClienteController.php` — evaluar si se necesita filtrado server-side
- `public/assets/css/custom.css` — estilos de los controles de filtro (Estándar Maestros Navy `#1e3c72`)

### ✅ Criterios de Aceptación
- [ ] Los filtros están visualmente integrados encima de la tabla (no interfieren con el layout).
- [ ] Seleccionar "Jurídico" filtra la tabla solo a clientes jurídicos.
- [ ] Seleccionar "Inactivo" filtra solo inactivos.
- [ ] Los filtros son combinables (Tipo AND Estatus simultáneamente).
- [ ] Un botón "Limpiar filtros" restaura la tabla completa.
- [ ] El diseño de los controles sigue el Estándar Maestros Navy del sistema.

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-08 · Pedidos: Lógica de Abono por Método de Pago
</h2>

**Rama:** `feat/santiago/s-08-pedidos-abono-metodo-pago` | **Complejidad:** 🔴 Alta

> ⚠️ **BLOQUEADA — Esperar que S-02 y S-03 estén mergeados en `enmanuel` antes de iniciar.**

### 📋 Contexto auditado — Hallazgo crítico: el modelo ya está listo
La auditoría de `app/Models/Pedido.php` (líneas 22–33) revela que el backend **YA tiene la arquitectura de datos completa** para manejar abonos por método de pago:

```php
protected $fillable = [
    'abono',                        // campo legado/general (mantener por compatibilidad)
    'efectivo_pagado',              // abono específico en efectivo
    'transferencia_pagado',         // abono específico en transferencia
    'pago_movil_pagado',            // abono específico en pago móvil
    'referencia_transferencia',     // referencia bancaria transferencia
    'referencia_pago_movil',        // referencia bancaria pago móvil
    'banco_transferencia_id',       // FK → tabla banco
    'banco_pago_movil_id',          // FK → tabla banco
];
```

El problema es **exclusivamente de frontend**: el formulario de completar/editar pedido solo muestra un campo `abono` general.

### 🎯 Objetivo
Actualizar el formulario de edición/completar pedido para que el usuario especifique el monto abonado **por cada método de pago activo**.

**Mockup lógico del formulario a implementar:**
```
┌─────────────────────────────────────────────────────┐
│ 💵 [✓] Efectivo        │ Monto: [___________]       │
│ 🏦 [✓] Transferencia   │ Banco: [select▼]           │
│                        │ Referencia: [___________]  │
│                        │ Monto: [___________]       │
│ 📱 [✓] Pago Móvil      │ Banco: [select▼]           │
│                        │ Referencia: [___________]  │
│                        │ Monto: [___________]       │
├─────────────────────────────────────────────────────┤
│ Total abonado (calculado automáticamente): Bs. X,XX │
└─────────────────────────────────────────────────────┘
```

### 📁 Archivos clave
- `resources/views/admin/pedidos/index.blade.php` — formulario de edición/completar pedido
- `app/Http/Controllers/PedidoController.php` — métodos `update()` y `store()`
- `app/Models/Pedido.php` — referencia (ya tiene los campos — NO modificar estructura)
- `app/Models/Banco.php` — para poblar el `<select>` de bancos

### ✅ Criterios de Aceptación
- [ ] El formulario de completar/editar pedido muestra secciones por método de pago.
- [ ] Cada método puede activarse/desactivarse (checkbox o toggle).
- [ ] El total abonado se calcula automáticamente (JavaScript) sumando los tres métodos.
- [ ] Al guardar: `efectivo_pagado`, `transferencia_pagado`, `pago_movil_pagado` persisten correctamente en BD.
- [ ] La vista de detalles del pedido muestra el desglose por método (los pills de pago ya existentes deben mostrar el monto).
- [ ] Funciona correctamente usando un solo método (los otros quedan en 0).
- [ ] El campo `abono` legacy se calcula como suma de los tres para compatibilidad.

### 🤖 Prompt para tu agente de IA
> "Lee el modelo `Pedido.php` completo y el método `update()` del `PedidoController.php`. Luego busca en `pedidos/index.blade.php` el formulario de edición de pedido. El modelo YA tiene los campos `efectivo_pagado`, `transferencia_pagado`, `pago_movil_pagado`, `banco_transferencia_id`, `banco_pago_movil_id`. El problema es que el formulario solo tiene un campo `abono` general. Diseña los cambios necesarios en el formulario HTML (con el mockup de 3 métodos de pago) y en el controller para guardar los abonos por método. Verifica si el controller ya procesa esos campos en `update()` o si hay que agregarlo."

---

<h2 style="color: #16a34a; border-left: 5px solid #16a34a; padding-left: 12px;">
⚙️ Tarea S-09 · Estándar Empresarial de Reportes PDF
</h2>

**Rama:** `feat/santiago/s-09-estandar-reportes-pdf` | **Complejidad:** 🔴 Alta | **Bloqueos:** Ninguno (puede ejecutarse en cualquier momento)

### 📋 Contexto auditado — Estándar canónico ya definido
El sistema tiene el estándar completamente implementado en `resources/views/admin/clientes/reporte_pdf.blade.php`. Las especificaciones exactas son:

| Propiedad | Valor exacto |
|---|---|
| Fuente base | `'DejaVu Sans', sans-serif` |
| Tamaño base | `10px` |
| Color corporativo | `#1e3c72` (Azul Marino Institucional) |
| Border del header del doc | `border-bottom: 3px solid #1e3c72` |
| Nombre empresa | `font-size: 16px; font-weight: bold; color: #1e3c72` |
| Título del reporte | `font-size: 13px; font-weight: bold; color: #1e3c72; text-transform: uppercase; letter-spacing: 1px; text-align: center` |
| Layout encabezado | Logo (izq.) + Info empresa (centro) + Metadata (der.) |
| Reset CSS | `* { margin: 0; padding: 0; box-sizing: border-box; }` |

**Reportes que AÚN NO siguen este estándar (targets):**
- `resources/views/admin/empleados/reporte_pdf.blade.php`
- `resources/views/admin/insumos/reporte_pdf.blade.php`
- `resources/views/admin/pedidos/reporte_pdf.blade.php`
- `resources/views/admin/productos/reporte_pdf.blade.php`
- `resources/views/admin/proveedores/reporte_pdf.blade.php`
- `resources/views/admin/cotizaciones/reporte_pdf.blade.php`

### 🎯 Objetivo
Aplicar la estructura, estilos y diseño del reporte de Clientes a los 6 reportes restantes, adaptando únicamente los campos de datos (columnas, totales, métricas) propios de cada módulo.

### 📁 Archivos clave
- **Referencia (NO modificar):** `resources/views/admin/clientes/reporte_pdf.blade.php`
- **Targets:** Los 6 archivos listados arriba.
- Controladores de cada módulo — para saber qué variables de Blade están disponibles en la vista.

### ✅ Criterios de Aceptación
- [ ] Todos los reportes PDF tienen el header de 3 columnas (logo / empresa / metadata).
- [ ] Todos usan DejaVu Sans y la paleta `#1e3c72`.
- [ ] Todos incluyen sección de resumen/estadísticas donde aplique.
- [ ] Los reportes se generan sin errores desde los controladores existentes.
- [ ] La coherencia visual es total al ver todos los PDFs juntos.

### 🤖 Prompt para tu agente de IA
> "Lee completamente `resources/views/admin/clientes/reporte_pdf.blade.php`. Es el estándar canónico de reportes PDF del sistema. Ahora léeme `resources/views/admin/empleados/reporte_pdf.blade.php` y compara ambos estructura por estructura. Dame un plan de migración detallado para llevar el reporte de empleados al nuevo estándar, conservando sus datos propios. Luego haremos el mismo proceso para cada uno de los demás módulos."

---

<br>

## ✅ Checklist de Entrega por Tarea

Antes de abrir el Pull Request, el desarrollador debe confirmar:

- [ ] 🤖 **Código revisado por el agente de IA** antes del primer commit.
- [ ] 🧪 **Prueba manual documentada:** descripción paso a paso del flujo probado.
- [ ] 🌙 **Modo oscuro verificado** para cualquier tarea que toque la UI.
- [ ] 🔗 **Sin regresiones:** los módulos adyacentes no se rompieron.
- [ ] 📝 **Commits con formato correcto:** `tipo(ID): descripción en español`.
- [ ] 🔀 **PR apuntando a `enmanuel`** (NO a `main`) con el ID de tarea en el título.
- [ ] 🚫 **No hay archivos de debug, `.bak`, `.log` o credenciales** incluidos en el commit.

---

*Documento generado mediante auditoría automatizada del codebase SGPMRJA · rama `enmanuel` · 15/03/2026*
