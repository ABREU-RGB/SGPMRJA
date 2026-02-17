# Análisis Técnico Exhaustivo de SGPMRJA

Fecha: 2026-02-17

## 1) Propósito principal del sistema

SGPMRJA es un sistema web de gestión integral para una empresa textil (Manufacturas R.J. Atlántico C.A.) orientado al ciclo completo comercial-operativo:

- Gestión de clientes, empleados, proveedores y usuarios.
- Gestión comercial de cotizaciones y pedidos.
- Gestión de catálogo de productos y tipos de producto.
- Gestión de producción (órdenes de producción + producción diaria).
- Gestión de inventario de insumos y movimientos (entradas/salidas).
- Reportería operativa y exportación PDF.

Evidencias principales:

- README: define explícitamente el alcance del sistema textil y módulos funcionales.
- Rutas de negocio en routes/web.php: pedidos, cotizaciones, proveedores, productos, insumos, órdenes, producción diaria, inventario y reportes.
- Modelos de dominio en app/Models con relaciones entre comercial, producción e inventario.

## 2) Arquitectura de base de datos (relaciones clave)

Observación estructural importante:

- `database/migrations` está vacío (solo `.gitkeep`).
- El esquema fuente actual está en `database/schema/mysql-schema.sql` (DDL + FKs + tabla `migrations` histórica).

### Núcleo de identidad/personas

- `persona` (entidad base de datos personales).
- `cliente.persona_id -> persona.id` (FK).
- `empleado.persona_id -> persona.id` (FK, único por empleado).
- `user.persona_id -> persona.id` (FK, único por usuario).
- `telefono.persona_id -> persona.id` y `direccion.persona_id -> persona.id` (normalización de contacto).

### Núcleo comercial

- `cotizacion.cliente_id -> cliente.id`
- `cotizacion.user_id -> user.id`
- `detalle_cotizacion.cotizacion_id -> cotizacion.id`
- `detalle_cotizacion.producto_id -> producto.id`

- `pedido.cliente_id -> cliente.id` (nullable)
- `pedido.cotizacion_id -> cotizacion.id` (nullable)
- `pedido.user_id -> user.id`
- `pedido.banco_id -> banco.id` (legacy/general)

- `detalle_pedido.pedido_id -> pedido.id`
- `detalle_pedido.producto_id -> producto.id`

### Núcleo de catálogo/producto

- `producto.tipo_producto_id -> tipo_producto.id` (nullable)
- `tipo_producto` define prefijo para codificación de productos.

### Núcleo de inventario

- `insumo.proveedor_id -> proveedor.id` (ON DELETE SET NULL)
- `movimiento_insumo.insumo_id -> insumo.id`
- `movimiento_insumo.created_by -> user.id`

### Núcleo de producción

- `orden_produccion.pedido_id -> pedido.id` (nullable)
- `orden_produccion.producto_id -> producto.id`
- `orden_produccion.created_by -> user.id`

- `detalle_orden_insumo.orden_produccion_id -> orden_produccion.id`
- `detalle_orden_insumo.insumo_id -> insumo.id`

- `produccion_diaria.orden_id -> orden_produccion.id`
- `produccion_diaria.operario_id -> user.id` (según SQL actual)

### Finanzas locales

- `tasa_cambio(moneda, valor, fecha_bcv)` con unique compuesto `(moneda, fecha_bcv)`.
- `banco` como catálogo para pagos.

## 3) Flujo de una petición: de routes/web.php hasta vista

### Flujo general

1. Request entra por `public/index.php`.
2. Bootstrap Laravel en `bootstrap/app.php`.
3. Se ejecuta `app/Http/Kernel.php` (middleware global y grupo `web`).
4. Resolución de ruta en `routes/web.php`.
5. Se aplican middleware de seguridad (`auth`, `throttle`, `role`).
6. Controlador procesa request y coordina validación + servicio + modelo.
7. Respuesta como:
   - Blade view (`resources/views/...`),
   - JSON (DataTables/AJAX),
   - PDF (DomPDF),
   - redirect/flash.

### Ejemplo real A: Pedido (vista)

- Ruta: `GET /pedidos` -> `PedidoController@index`.
- Middleware: `auth` + `role:Administrador,Supervisor`.
- Controlador carga `Producto`, `Insumo`, `Banco`.
- Renderiza `resources/views/admin/pedidos/index.blade.php`.

### Ejemplo real B: Pedido (persistencia)

- Ruta: `POST /pedidos` -> `PedidoController@store`.
- Validación: `StorePedidoRequest`.
- Servicio: `PedidoService::crear()`.
- Persistencia transaccional:
  - crea `pedido`,
  - crea `detalle_pedido`,
  - si viene de cotización aprobada, marca cotización como convertida.
- Respuesta: JSON success.

### Ejemplo real C: Pública (landing)

- Ruta: `GET /` -> `PagesController@home`.
- Renderiza `resources/views/welcome.blade.php`.

## 4) Módulos detectados y relación funcional

1. Seguridad y acceso
   - Usuarios, autenticación, middleware de rol.

2. Personas y maestros
   - Persona, cliente, empleado, proveedor, teléfono, dirección.

3. Comercial
   - Cotización y pedido, con conversión cotización -> pedido.

4. Producto
   - Tipo de producto + código de producto (prefijo-abreviatura-contador).

5. Producción
   - Orden de producción alimentada desde pedido o manual.
   - Registro de producción diaria por operario.

6. Inventario
   - Insumos + movimientos + alertas de stock.
   - Insumos planificados por orden de producción.

7. Reportes
   - Reportes PDF y tableros de estado.

Relación macro:

Cliente -> Cotización -> Pedido -> Orden de Producción -> Producción Diaria
                                      \-> Detalles de Pedido -> Insumos estimados
Insumo -> Movimientos de inventario -> Control de stock

## 5) Hallazgos técnicos y puntos de mejora

### A. Riesgos de consistencia esquema-código

1. Inconsistencia en `produccion_diaria.operario_id`:
   - SQL: FK a `user.id`.
   - Código:
     - `ProduccionDiariaController@store` valida `exists:empleado,id`.
     - `ProduccionDiaria` model relaciona `operario()` con `Empleado`.
   -> Riesgo de fallo de integridad o datos incompatibles según ambiente.

2. Inconsistencia de estados de pedido:
   - Dashboard y otros módulos usan `En Proceso`.
   - `UpdatePedidoRequest` valida `Procesando` (sin espacio).
   -> Riesgo de rechazar estados válidos o desalinear reportes.

3. Campos de banco en actualización de pedido:
   - `StorePedidoRequest` usa `banco_transferencia_id` y `banco_pago_movil_id`.
   - `UpdatePedidoRequest` usa solo `banco_id` para validación.
   -> Riesgo funcional en actualización de métodos de pago.

### B. Deuda técnica de migraciones

4. Falta de migraciones versionadas en repositorio:
   - `database/migrations` vacío.
   - Esquema real centralizado en SQL dump.
   -> Dificulta CI/CD, reproducibilidad de ambientes y rollback por cambios.

### C. Desalineación documentación vs implementación

5. Roles:
   - Requerimientos funcionales: Administrador, Supervisor, Operario, Almacenero.
   - `user.role` enum en SQL/código: solo Administrador y Supervisor.
   -> Documento funcional y versión implementada no están alineados.

### D. Señales de archivos potencialmente incompletos/temporales

6. Backups de vistas dentro de código productivo:
   - `resources/views/admin/empleados/index.blade.php.backup`
   - `resources/views/admin/pedidos/index.blade.php.bak`
   - `resources/views/admin/pedidos/index.blade.php.utf8`
   -> Conviene mover a carpeta de respaldo fuera de `resources/views` o eliminar.

7. Dependencias/librerías frontend masivas en `public/assets/libs`:
   - Posible sobrecarga de activos no usados.
   -> Recomendada auditoría de bundle/uso para rendimiento y mantenimiento.

## 6) Recomendaciones priorizadas

Prioridad alta (estabilidad):

1. Corregir inconsistencia `operario_id` (definir si referencia `user` o `empleado`) y alinear:
   - esquema SQL,
   - validaciones de controller/request,
   - relación Eloquent.

2. Unificar vocabulario de estado de pedido (`En Proceso` vs `Procesando`) en:
   - requests,
   - controladores,
   - dashboard/reportes,
   - catálogos frontend.

3. Homologar campos de pago/banco en creación y actualización de pedido.

Prioridad media (mantenibilidad):

4. Regenerar y versionar migraciones reales desde el esquema actual.
5. Limpiar archivos backup en vistas y estandarizar estrategia de respaldo.
6. Actualizar documentación funcional para reflejar rolado real o implementar roles faltantes.

Prioridad continua:

7. Incrementar pruebas de integración para flujos críticos:
   - cotización -> pedido,
   - pedido -> orden,
   - producción diaria -> actualización de orden,
   - inventario (movimientos y alertas).

## 7) Estado de memoria para consultas futuras

Como agente, no dispongo de memoria persistente garantizada entre sesiones. Para conservar este análisis de forma durable se guarda en este archivo:

- `docs/SGPMRJA_ANALISIS_TECNICO_2026-02-17.md`

Este documento puede usarse como referencia base en próximas iteraciones.
