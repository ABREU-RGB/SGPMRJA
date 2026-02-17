# Arquitectura del Sistema SGPMRJA

## Propósito

SGPMRJA es un sistema de gestión integral para manufactura textil que cubre el ciclo comercial y operativo:

- Clientes, proveedores, empleados y usuarios.
- Cotizaciones y pedidos.
- Producción (órdenes y producción diaria).
- Inventario de insumos y movimientos.
- Reportes operativos en PDF y paneles.

## Arquitectura de aplicación

- Framework: Laravel 10 (MVC).
- Backend: PHP 8.1+, Eloquent ORM, validaciones por Request, servicios de negocio.
- Frontend: Blade + assets compilados.
- Persistencia: MySQL.

Capas principales:

1. **Rutas** (`routes/web.php`): entrada HTTP y control por middleware.
2. **Middleware** (`auth`, `role`, `throttle`): control de acceso y seguridad.
3. **Controladores** (`app/Http/Controllers`): orquestación de casos de uso.
4. **Servicios** (`app/Services`): lógica de negocio transaccional.
5. **Modelos** (`app/Models`): entidades y relaciones Eloquent.
6. **Vistas** (`resources/views`): renderizado de UI y plantillas PDF.

## Arquitectura de datos

Fuente canónica de esquema:

- `database/schema/mysql-schema.sql`

Documentación canónica del modelo de datos:

- `docs/DICCIONARIO_DATOS.md`

Relaciones macro del dominio:

- Cliente -> Cotización -> Pedido -> Orden de Producción -> Producción Diaria
- Pedido -> DetallePedido -> estimación de insumos
- Insumo -> MovimientoInsumo (entradas/salidas)
- Producto y TipoProducto gobiernan catálogo y codificación

## Módulos funcionales

1. **Acceso y seguridad**
   - Usuarios, autenticación, autorización por rol.

2. **Maestros de negocio**
   - Persona, Cliente, Empleado, Proveedor, Teléfono, Dirección.

3. **Comercial**
   - Cotizaciones, conversión a pedido, seguimiento de estados y pagos.

4. **Producción**
   - Órdenes de producción, consumo estimado de insumos, registro diario.

5. **Inventario**
   - Catálogo de insumos, movimientos, alertas de stock.

6. **Reportería**
   - Reportes operativos y documentos PDF por módulo.

## Flujo típico de petición

1. Request entra por `public/index.php`.
2. Kernel aplica middleware global y de grupo web.
3. Router resuelve endpoint en `routes/web.php`.
4. Middleware de autenticación/rol valida acceso.
5. Controlador valida y delega en servicios.
6. Servicios persisten vía modelos Eloquent.
7. Respuesta en vista, JSON o PDF.

## Riesgos técnicos abiertos (resumen)

- Desalineación entre esquema y código en `produccion_diaria.operario_id` (user vs empleado).
- Estados de pedido no unificados (`En Proceso` vs `Procesando`).
- Campo bancario de pago no homogéneo entre creación y edición de pedido.
- Uso de esquema SQL como fuente principal sin migraciones versionadas activas.

## Estado documental

Este archivo reemplaza como fuente canónica los análisis heredados en `Documentacion/ANALISIS_COMPLETO_SISTEMA*.md`.
