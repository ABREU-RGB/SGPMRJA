# 🔍 Auditoría Total del Sistema SGPMRJA

**Fecha:** 16 de Febrero, 2026  
**Sistema:** SGPMRJA — Sistema de Gestión de Producción para Manufactura RJA (Atlántico)  
**Stack:** Laravel 10 · PHP 8.1 · MySQL · Velzon Admin Template · Bootstrap 5 · DataTables

---

## 1. Resumen Ejecutivo

SGPMRJA es un sistema ERP de producción con los módulos: **Clientes, Productos, Proveedores, Empleados, Cotizaciones, Pedidos, Órdenes de Producción, Inventario de Insumos, Producción Diaria y Reportes**. Está construido sobre Laravel 10 con el template admin Velzon.

| Métrica                   | Valor                             |
| ------------------------- | --------------------------------- |
| Modelos                   | 20                                |
| Controladores             | 19 + 9 Auth                       |
| Middleware personalizados | 2 (`CheckRole`, `CheckAdminRole`) |
| Migraciones               | 55                                |
| Seeders                   | 10                                |
| Form Requests             | 2 (solo Auth y Profile)           |
| Service Classes           | 1 (`TasaBcvService`)              |
| Tests Feature             | 8                                 |
| Tests Unit                | 1 (ExampleTest)                   |

---

## 2. Lo que Funciona Bien ✅

### Arquitectura de datos normalizada

La normalización `Persona → Cliente/Empleado`, con tablas separadas para `Telefono` y `Direccion`, es un diseño sólido y flexible que permite múltiples teléfonos/direcciones por persona.

### Uso consistente de SoftDeletes

Los modelos principales (`Pedido`, `Producto`, `Cliente`, `OrdenProduccion`, `Insumo`, `Persona`, `Proveedor`) implementan SoftDeletes, protegiendo contra eliminaciones accidentales.

### Validación frontend + backend

Los controladores incluyen validación de servidor con reglas detalladas y mensajes personalizados en español.

### Transacciones de base de datos

Los controladores de `Cliente` y `Cotizacion` usan `DB::transaction()` para operaciones que afectan múltiples tablas, garantizando integridad.

### Roles básicos funcionales

El sistema de roles (`Administrador`, `Supervisor`) con middleware `CheckRole` funciona correctamente para separar permisos de lectura vs escritura.

### DataTables para listados

Uso consistente de `yajra/laravel-datatables` para paginación eficiente del lado del servidor.

### Generación de PDFs

Los reportes PDF con DomPDF están implementados en múltiples módulos (Clientes, Pedidos, Cotizaciones, etc.).

---

## 3. Problemas Críticos 🔴

### 3.1 Seguridad

#### 🔴 Credenciales expuestas en `.env`

```
DB_USERNAME=root
DB_PASSWORD=root
MAIL_USERNAME=f62a219290d742
MAIL_PASSWORD=c34bceef8ad7e7
```

> Las credenciales de Mailtrap están expuestas. En producción, usar contraseñas fuertes y nunca usar `root/root` para MySQL.

#### 🔴 `APP_DEBUG=true` en producción

```
APP_DEBUG=true
```

> Con debug activado, los stacktraces Laravel exponen rutas del servidor, contraseñas de DB, y variables de entorno. **DESACTIVAR para producción.**

#### 🔴 `APP_NAME=Laravel` — Sin personalizar

El nombre de la aplicación sigue siendo `"Laravel"` por defecto, afectando correos, títulos y sesiones.

#### 🔴 Posible SQL Injection en búsqueda

En [ClienteController.php](file:///home/emmanuel/SGPMRJA/app/Http/Controllers/ClienteController.php#L343-L368):

```php
$q->where('documento_identidad', 'LIKE', "%{$query}%");
```

> Aunque Laravel parametriza el `where()`, la concatenación directa con `%{$query}%` en el `LIKE` puede provocar ataques de wildcard si el usuario introduce `%` o `_`. **Recomendación:** Escapar caracteres especiales del input:

```php
$escaped = str_replace(['%', '_'], ['\%', '\_'], $query);
$q->where('documento_identidad', 'LIKE', "%{$escaped}%");
```

#### 🔴 Subida de archivos sin validación de contenido

En [UserController.php](file:///home/emmanuel/SGPMRJA/app/Http/Controllers/UserController.php#L22-L33), los avatares se mueven directamente a `public/`:

```php
$file->move(public_path($directory), $filename);
```

> Los archivos subidos al directorio `public/` son accesibles directamente vía URL. Se pueden subir shells PHP disfrazados de imágenes. **Recomendación:** Usar `Storage::disk('public')` con `store()` y validar el MIME type real del archivo.

#### 🔴 Falta verificación de email

```php
// use Illuminate\Contracts\Auth\MustVerifyEmail;
```

> La verificación de email está comentada en `User.php`. Los usuarios pueden registrarse con emails falsos.

#### 🔴 Sin rate limiting en rutas web

Las rutas web no tienen throttle middleware, permitiendo ataques de fuerza bruta al login y DDOS a endpoints AJAX.

---

### 3.2 Bugs Activos

#### 🔴 División por cero en ReportesController

En [ReportesController.php](file:///home/emmanuel/SGPMRJA/app/Http/Controllers/ReportesController.php#L37-L55):

```php
$eficiencia = ($item->total_producido - $item->total_defectuoso)
              / $item->total_producido * 100;
```

> Si `total_producido` es 0, esto produce un `DivisionByZeroError`. La función `empleados()` ya tiene la protección `$item->total_producido > 0`, pero `eficiencia()` **NO**.

#### 🔴 Inconsistencia en tabla pivote de Insumo

En [Insumo.php](file:///home/emmanuel/SGPMRJA/app/Models/Insumo.php#L40):

```php
return $this->belongsToMany(OrdenProduccion::class, 'detalle_orden_insumos')
```

Pero en [OrdenProduccion.php](file:///home/emmanuel/SGPMRJA/app/Models/OrdenProduccion.php#L44):

```php
return $this->belongsToMany(Insumo::class, 'detalle_orden_insumo')
```

> ¡Un modelo usa `detalle_orden_insumos` (plural) y el otro `detalle_orden_insumo` (singular)! Uno de los dos debe fallar en runtime.

#### 🔴 Ruta conflictiva de reporte PDF

```php
Route::get('pedidos/reporte/pdf', ...)->name('pedidos.reporte.pdf');
Route::get('pedidos/{pedido}', ...)->name('pedidos.show');
```

> La ruta `pedidos/reporte` podría ser capturada por `pedidos/{pedido}` con `{pedido}=reporte`, dependiendo del orden de definición.

---

## 4. Problemas Importantes ⚠️

### 4.1 Arquitectura y Código

#### ⚠️ Controladores "gordos" — Falta de Service Layer

Los controladores más grandes tienen hasta **529 líneas** (`CotizacionController`). Toda la lógica de negocio (validación, transformación, persistencia, reportes) está en el controlador.

| Controlador          | Líneas | Complejidad |
| -------------------- | ------ | ----------- |
| CotizacionController | 529    | Muy alta    |
| ClienteController    | 405    | Alta        |
| EmpleadoController   | ~400   | Alta        |
| ProveedorController  | ~350   | Alta        |
| PedidoController     | 346    | Alta        |

> **Recomendación:** Crear Services/Actions para extraer la lógica:
>
> - `App\Services\CotizacionService` → crear, convertir a pedido
> - `App\Services\PedidoService` → crear pedido con inventario
> - `App\Services\ClienteService` → CRUD con Persona/Telefono/Direccion

#### ⚠️ Ausencia casi total de Form Requests

Solo 2 FormRequests existen (Auth y Profile). Los otros **17 controladores** hacen validación inline. Esto:

- Duplica reglas de validación entre `store()` y `update()`
- Hace los controladores innecesariamente largos
- Dificulta reusar validaciones

> **Recomendación:** Crear `StoreClienteRequest`, `UpdateClienteRequest`, `StorePedidoRequest`, etc.

#### ⚠️ Duplicación de grupos de middleware en rutas

En [web.php](file:///home/emmanuel/SGPMRJA/routes/web.php):

```php
Route::middleware('role:Administrador')->group(function () { ... }); // Línea 52
Route::middleware('role:Administrador')->group(function () { ... }); // Línea 79
Route::middleware('role:Administrador')->group(function () { ... }); // Línea 90
Route::middleware('role:Administrador')->group(function () { ... }); // Línea 101
```

> 4 bloques separados con el mismo middleware `role:Administrador`. Deberían consolidarse en un solo grupo.

#### ⚠️ Binding de ruta inconsistente

```php
Route::put('proveedores/{proveedore}', ...);   // "proveedore" (mal)
Route::delete('proveedores/{proveedore}', ...); // debería ser {proveedor}
```

> El parámetro `{proveedore}` es un error gramatical y no coincide con el nombre del modelo para Route Model Binding.

#### ⚠️ Modelo User sin relación a Persona

```php
// Nota: Si necesitas relacionar usuarios con personas, debes crear
// una migración que agregue la columna persona_id a la tabla user
```

> El modelo `User` no tiene la relación `persona()`, a pesar de que existe una migración `add_persona_id_to_user_table`. Esto deja la arquitectura incompleta.

### 4.2 Rendimiento

#### ⚠️ N+1 Query en accessors de Persona

Los accessors `getTelefonoPrincipalAttribute()` y `getDireccionPrincipalAttribute()` ejecutan **queries independientes** cada vez que se acceden:

```php
public function getTelefonoPrincipalAttribute() {
    $telefonoPrincipal = $this->telefonos()->where('es_principal', true)->first();
    ...
}
```

> Cuando se listan 100 clientes, esto genera **200 queries adicionales**. **Recomendación:** Usar eager loading y filtrar en memoria, o crear un relationship:

```php
public function telefonoPrincipal() {
    return $this->hasOne(Telefono::class)->where('es_principal', true);
}
```

#### ⚠️ `Cliente::with(...)->get()` sin paginación

En [ClienteController::getClientes()](file:///home/emmanuel/SGPMRJA/app/Http/Controllers/ClienteController.php#L22-L46):

```php
$clientes = Cliente::with([...])->get();
```

> Carga **TODOS** los clientes en memoria antes de pasarlos a DataTables. Con miles de registros, esto consume memoria excesiva. DataTables de Yajra soporta Eloquent directo con paginación del lado del servidor.

#### ⚠️ Dashboard con múltiples consultas simples

```php
$totalClientes = Cliente::count();
$totalProductos = Producto::count();
$totalEmpleados = Empleado::count();
$totalProveedores = Proveedor::count();
Pedido::where('estado', 'Pendiente')->count(); // x4 estados
```

> Son **8 queries** para el dashboard. Podrían reducirse con un solo raw SQL o caché.

---

## 5. Mejoras Sugeridas 💡

### 5.1 Testing (Prioridad Alta)

| Estado actual                         | Propuesta                               |
| ------------------------------------- | --------------------------------------- |
| 8 Feature Tests (solo Auth + Profile) | Agregar tests para CRUD de cada módulo  |
| 1 Unit Test (ExampleTest)             | Agregar unit tests para Services/Models |
| 0% cobertura de lógica de negocio     | Al menos 60% cobertura                  |

> **Recomendación mínima:** Un test de integración por controlador que pruebe `store`, `update` y `destroy`.

### 5.2 Gestión de errores

- No existe un `Handler.php` personalizado para formatear errores JSON de forma consistente
- Los errores 403/404/500 no tienen vistas personalizadas en español
- Falta logging estructurado para acciones críticas (conversión cotización→pedido, eliminaciones)

### 5.3 Módulos incompletos (TODOs en sidebar)

El propio sidebar declara funcionalidades NO implementadas:

```html
{{-- TODO: Crear ruta y controlador para Control de Calidad --}} {{-- TODO:
Crear ruta y controlador para Garantías --}} {{-- TODO: Crear módulo de Roles y
Permisos --}} {{-- TODO: Crear módulo de Ajustes del Sistema --}} {{-- TODO:
Crear vista de Reportes Generales unificada --}}
```

> Son **5 funcionalidades** visibles en el menú pero sin implementar. Deberían ocultarse hasta estar listas, o implementarse.

### 5.4 Base de datos

#### Exceso de migraciones destructivas

55 migraciones incluyendo muchos `add_`, `remove_`, `rename_`, `drop_` y `force_update_` indican refactoring constante.

> **Recomendación para producción:** Consolidar migraciones en un schema limpio y usar `squash`.

#### Falta de índices documentados

No se observaron índices compuestos en columnas frecuentemente consultadas (`estado`, `fecha_pedido`, `cliente_id` en pedidos, etc.)

### 5.5 Frontend

#### Template Velzon no personalizado

- El configurador de temas (Theme Customizer) sigue mostrando el botón **"Buy Now"** de Envato en producción
- Textos del template en inglés ("Theme Customizer", "Choose Light or Dark Scheme", etc.)
- Idioma mixto español/inglés en la interfaz

#### Falta de internacionalización (i18n)

Todo el texto está hardcodeado en Blade. No se usan archivos `lang/` ni `__()` / `@lang()` de Laravel.

### 5.6 Estructura de archivos

#### Archivos no necesarios en raíz del proyecto

| Archivo                    | Problema                           |
| -------------------------- | ---------------------------------- |
| `check_columns.php`        | Script utilitario suelto           |
| `create_database.php`      | Should be a migration or seeder    |
| `convertir_md_html.py`     | Script Python suelto               |
| `diff_dump.txt` (307KB)    | Dump de desarrollo                 |
| `error_log.txt`            | Log manual fuera de `storage/logs` |
| `Detener sistema.bat/.txt` | Scripts Windows duplicados         |
| `Iniciar SGPMRJA.bat/.txt` | Scripts Windows duplicados         |
| `Recuerda`                 | Archivo personal                   |
| `---.txt`                  | Archivo vacío sin propósito        |

> Limpiar la raíz del proyecto y mover scripts a `scripts/` o eliminarlos.

---

## 6. Tabla Resumen de Prioridades

| #   | Hallazgo                             | Severidad     | Esfuerzo |
| --- | ------------------------------------ | ------------- | -------- |
| 1   | `APP_DEBUG=true` para producción     | 🔴 Crítico    | 1 min    |
| 2   | Credenciales débiles en `.env`       | 🔴 Crítico    | 5 min    |
| 3   | División por cero en `eficiencia()`  | 🔴 Crítico    | 2 min    |
| 4   | Inconsistencia tabla pivote insumos  | 🔴 Crítico    | 2 min    |
| 5   | Upload de archivos inseguro          | 🔴 Crítico    | 30 min   |
| 6   | Sin rate limiting en login           | 🔴 Crítico    | 10 min   |
| 7   | Controladores gordos → Service layer | ⚠️ Importante | 2-3 días |
| 8   | Crear Form Requests                  | ⚠️ Importante | 1 día    |
| 9   | N+1 queries en accessors             | ⚠️ Importante | 2h       |
| 10  | Consolidar rutas duplicadas          | ⚠️ Importante | 30 min   |
| 11  | Binding `{proveedore}` incorrecto    | ⚠️ Importante | 5 min    |
| 12  | TODOs visibles en sidebar            | ⚠️ Media      | 15 min   |
| 13  | Tests de lógica de negocio           | ⚠️ Media      | 2-3 días |
| 14  | Personalizar template Velzon         | 💡 Mejora     | 1h       |
| 15  | Consolidar migraciones               | 💡 Mejora     | 2h       |
| 16  | Limpiar archivos raíz                | 💡 Mejora     | 15 min   |
| 17  | Implementar i18n                     | 💡 Mejora     | 1-2 días |

---

## 7. Próximos Pasos Recomendados

1. **Inmediato (hoy):** Corregir items 1-6 (segurizad y bugs críticos)
2. **Corto plazo (1 semana):** Items 7-12 (refactoring de arquitectura)
3. **Mediano plazo (2-3 semanas):** Items 13-17 (calidad y polish)

> [!IMPORTANT]
> Los items 1-6 son **imprescindibles** antes de desplegar en producción. El item 3 (división por cero) ya está generando errores en tiempo real si hay datos sin producción.
