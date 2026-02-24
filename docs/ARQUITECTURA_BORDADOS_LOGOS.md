# Arquitectura de Bordados y Logos (SGPMRJA)

## 1) Objetivo del modelo

El objetivo del rediseño fue pasar de un modelo simple (1 logo + 1 ubicación por producto) a un modelo real de negocio:

- Un producto puede llevar múltiples bordados.
- Cada bordado puede tener ubicación distinta.
- Cada bordado puede usar logo distinto.
- Cada bordado puede tener tarifa propia (incluyendo override manual).
- Los datos históricos deben quedar congelados aunque cambie el catálogo en el futuro.

Por eso se separó el dato en tablas de cabecera/detalle y subdetalle (líneas de bordado).

---

## 2) Tablas involucradas y propósito

### 2.1 Tabla de catálogo de ubicaciones
- Tabla: `bordado_ubicaciones`
- Definida en el dump: `database/sistema_atlantico4_2026-02-24_105836.sql` (línea aprox. 50)
- Campos clave:
  - `nombre`: nombre de ubicación (único)
  - `grupo`: agrupación visual (Frontal, Mangas, etc.)
  - `precio_base`: tarifa sugerida por catálogo
  - `activo`: habilita/deshabilita opción en UI

Esta tabla existe para estandarizar ubicaciones y sus precios de referencia.

### 2.2 Detalle de cotización (nivel producto)
- Tabla: `detalle_cotizacion`
- Dump: línea aprox. 172
- Campos relevantes:
  - `lleva_bordado` (boolean)
  - `nombre_logo` (string)
  - `precio_unitario`

`detalle_cotizacion` representa la línea de producto (cantidad, talla, color, precio final unitario).

### 2.3 Subdetalle de bordado en cotización (nivel bordado)
- Tabla: `detalle_cotizacion_bordado`
- Dump: línea aprox. 208
- Campos relevantes:
  - `detalle_cotizacion_id` (FK al producto de cotización)
  - `ubicacion_bordado_id` (FK opcional al catálogo)
  - `nombre_aplicado` (nombre de ubicación aplicado en ese momento)
  - `nombre_logo_aplicado` (logo aplicado en esa línea)
  - `cantidad`
  - `precio_aplicado`
  - `es_personalizada`

Esta es la tabla más importante del rediseño: cada fila es un bordado real.

### 2.4 Detalle de pedido (nivel producto)
- Tabla: `detalle_pedido`
- Dump: línea aprox. 274
- Campos relevantes:
  - `lleva_bordado`
  - `nombre_logo`
  - `precio_unitario`

### 2.5 Subdetalle de bordado en pedido (nivel bordado)
- Tabla: `detalle_pedido_bordado`
- Dump: línea aprox. 310
- Campos relevantes:
  - `detalle_pedido_id`
  - `ubicacion_bordado_id`
  - `nombre_aplicado`
  - `nombre_logo_aplicado`
  - `cantidad`
  - `precio_aplicado`
  - `es_personalizada`

El pedido replica la estructura de cotización para no perder granularidad al convertir.

### 2.6 Catálogo de logos empresariales
- Tabla: `logos`
- Dump: línea aprox. 502
- Campos:
  - `name`
  - `original_filename`

Esta tabla es catálogo UI/operativo para seleccionar logos disponibles. No guarda la relación transaccional final por FK; la transacción guarda snapshot en texto (`nombre_logo_aplicado`).

---

## 3) ¿Por qué aparecen atributos “repetidos”?

Esta es la parte clave para explicarlo a un profesor.

## 3.1 `nombre_logo` en `detalle_cotizacion` y `detalle_pedido`

No es redundancia accidental; cumple rol de compatibilidad y resumen:

- Compatibilidad con vistas/consultas legacy que esperaban un solo logo por producto.
- Resumen rápido del producto sin abrir sublíneas.
- Se calcula/deriva desde sublíneas (`nombre_logo_aplicado`) cuando hay múltiples logos.

En el modelo nuevo, la verdad de negocio fina está en `detalle_*_bordado`.

## 3.2 `nombre_logo_aplicado` en `detalle_*_bordado`

Este sí es el dato atómico de negocio:

- Define qué logo se bordó en esa ubicación específica.
- Permite múltiples logos por un mismo producto.
- Es snapshot histórico (si cambian logos/catálogos después, el histórico no se altera).

## 3.3 `lleva_bordado`

Es un flag de alto nivel por línea de producto:

- Permite filtros y decisiones rápidas en backend/frontend.
- Evita recorrer sublíneas para casos obvios de “sin bordado”.
- Controla reglas de validación (`required_if` para arreglo de bordados).

## 3.4 `logos` (catálogo) vs `nombre_logo_aplicado` (transacción)

- `logos`: catálogo vivo de la empresa (UI de búsqueda).
- `nombre_logo_aplicado`: snapshot transaccional congelado por línea.

Esto separa “maestro configurable” de “histórico de operación”, que es una práctica correcta de auditoría.

---

## 4) Relaciones y cardinalidad

A nivel conceptual:

- `cotizacion` 1:N `detalle_cotizacion`
- `detalle_cotizacion` 1:N `detalle_cotizacion_bordado`
- `bordado_ubicaciones` 1:N `detalle_cotizacion_bordado` (opcional por personalizadas)

- `pedido` 1:N `detalle_pedido`
- `detalle_pedido` 1:N `detalle_pedido_bordado`
- `bordado_ubicaciones` 1:N `detalle_pedido_bordado` (opcional)

Y al convertir:

- `cotizacion` 1:1 `pedido` (flujo de negocio)
- Se copian líneas de producto y sublíneas de bordado, incluyendo snapshots.

---

## 5) Flujo funcional completo

## 5.1 Frontend (cotizaciones)

Archivo clave: `resources/views/admin/cotizaciones/scripts/main.blade.php`

1. Usuario marca “Servicio de Bordado”.
2. Abre modal de configuración de servicio.
3. Por cada ubicación seleccionada define:
   - logo
   - ubicación
   - cantidad
   - precio aplicado
4. El frontend serializa en campos ocultos `productos[i][bordados][j][...]`.
5. Se calcula en vivo:
   - recargo unitario
   - precio final unitario
   - total de línea

## 5.2 Backend (cotizaciones)

- Controller: `app/Http/Controllers/CotizacionController.php`
- Service: `app/Services/CotizacionService.php`

Validación:
- `productos.*.bordados` requerido si `lleva_bordado=true`
- cada bordado requiere `nombre_aplicado`, `nombre_logo`, `precio_aplicado`, etc.

Persistencia:
- crea `detalle_cotizacion`
- crea N filas en `detalle_cotizacion_bordado`
- guarda snapshot en `nombre_logo_aplicado` y `precio_aplicado`

## 5.3 Conversión a pedido

En `CotizacionService::convertirAPedido`:
- crea `detalle_pedido`
- copia cada sublínea a `detalle_pedido_bordado`
- preserva los mismos snapshots

## 5.4 Backend (pedidos)

- Requests: `app/Http/Requests/StorePedidoRequest.php`, `UpdatePedidoRequest.php`
- Service: `app/Services/PedidoService.php`

Misma lógica: producto + sublíneas de bordado.

---

## 6) Cálculo de precios

Para cada línea de producto:

- Recargo unitario de bordado = suma de todas las sublíneas:
  - `precio_aplicado * cantidad` por sublínea
- Precio final unitario = `precio_base + recargo_unitario`
- Total de línea = `precio_final_unitario * cantidad_prendas`

Esto permite pricing compuesto y transparente por ubicación/logo.

---

## 7) Integridad y decisiones técnicas

- FK de sublíneas al detalle padre con `ON DELETE CASCADE`.
- FK a ubicación con `ON DELETE SET NULL` para no romper histórico si se desactiva/elimina ubicación del catálogo.
- Índices en campos FK para consultas eficientes.
- Snapshot de nombre/precio aplicado para auditoría histórica.

---

## 8) Respuesta corta para defensa oral

Si te preguntan “¿por qué hay `nombre_logo` y también `nombre_logo_aplicado`?”:

- `nombre_logo_aplicado` es el dato atómico por cada bordado (logo real en ubicación real).
- `nombre_logo` en detalle producto es resumen/compatibilidad legacy y lectura rápida.
- El catálogo `logos` sirve para selección operativa, pero la transacción guarda snapshot textual para conservar histórico.

Si te preguntan “¿por qué tantas tablas?”:

- Porque el negocio pasó de 1 bordado por producto a N bordados por producto.
- Eso requiere estructura padre-hijo para mantener normalización, trazabilidad y cálculo correcto.

---

## 9) Referencias del código

- Base de datos real (dump): `database/sistema_atlantico4_2026-02-24_105836.sql`
- Controlador cotizaciones: `app/Http/Controllers/CotizacionController.php`
- Servicio cotizaciones: `app/Services/CotizacionService.php`
- Servicio pedidos: `app/Services/PedidoService.php`
- Requests de pedido: `app/Http/Requests/StorePedidoRequest.php`, `app/Http/Requests/UpdatePedidoRequest.php`
- Frontend cotizaciones: `resources/views/admin/cotizaciones/scripts/main.blade.php`
- Modelos detalle/subdetalle:
  - `app/Models/DetalleCotizacion.php`
  - `app/Models/DetalleCotizacionBordado.php`
  - `app/Models/DetallePedido.php`
  - `app/Models/DetallePedidoBordado.php`
