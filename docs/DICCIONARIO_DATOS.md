# Diccionario de Datos (Generado desde esquema SQL)

**Sistema**: SGPMRJA  
**Fuente**: `database/schema/mysql-schema.sql`  
**Fecha de generación**: 2026-02-17

---

## Resumen

- Tablas detectadas: **25**
- Este documento es canónico y está sincronizado con el esquema SQL versionado.

---

## Tabla: banco

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| nombre | varchar(191) | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - bancos_nombre_unique: (`nombre`)
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: cliente

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| persona_id | bigint unsigned | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |
| tipo_cliente | enum('natural','juridico') | NOT NULL, DEFAULT 'natural' |
| estatus | tinyint(1) | NOT NULL, DEFAULT '1' |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - cliente_persona_id_foreign: (`persona_id`)
- **Foreign Keys**:
  - persona_id -> persona.id (-)

---

## Tabla: cotizacion

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| cliente_id | bigint unsigned | NOT NULL |
| fecha_cotizacion | date | NOT NULL |
| fecha_validez | date | DEFAULT NULL |
| estado | varchar(191) | NOT NULL, DEFAULT 'Pendiente' |
| total | decimal(10,2) | NOT NULL, DEFAULT '0.00' |
| user_id | bigint unsigned | NOT NULL |
| prioridad | varchar(191) | NOT NULL, DEFAULT 'Normal' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - cotizaciones_user_id_foreign: (`user_id`)
  - cotizacion_cliente_id_foreign: (`cliente_id`)
- **Foreign Keys**:
  - cliente_id -> cliente.id (-)
  - user_id -> user.id (ON DELETE CASCADE)

---

## Tabla: detalle_cotizacion

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| cotizacion_id | bigint unsigned | NOT NULL |
| producto_id | bigint unsigned | NOT NULL |
| cantidad | int | NOT NULL |
| descripcion | text | - |
| lleva_bordado | tinyint(1) | NOT NULL, DEFAULT '0' |
| nombre_logo | varchar(191) | DEFAULT NULL |
| ubicacion_logo | varchar(191) | DEFAULT NULL |
| cantidad_logo | int | DEFAULT NULL |
| color | varchar(50) | DEFAULT NULL |
| talla | varchar(191) | DEFAULT NULL |
| precio_unitario | decimal(10,2) | NOT NULL, DEFAULT '0.00' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - detalle_cotizaciones_cotizacion_id_foreign: (`cotizacion_id`)
  - detalle_cotizaciones_producto_id_foreign: (`producto_id`)
- **Foreign Keys**:
  - cotizacion_id -> cotizacion.id (ON DELETE CASCADE)
  - producto_id -> producto.id (ON DELETE CASCADE)

---

## Tabla: detalle_orden_insumo

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| orden_produccion_id | bigint unsigned | NOT NULL |
| insumo_id | bigint unsigned | NOT NULL |
| cantidad_estimada | decimal(10,2) | NOT NULL |
| cantidad_utilizada | decimal(10,2) | NOT NULL, DEFAULT '0.00' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - detalle_orden_insumos_orden_produccion_id_foreign: (`orden_produccion_id`)
  - detalle_orden_insumos_insumo_id_foreign: (`insumo_id`)
- **Foreign Keys**:
  - insumo_id -> insumo.id (ON DELETE CASCADE)
  - orden_produccion_id -> orden_produccion.id (ON DELETE CASCADE)

---

## Tabla: detalle_pedido

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| pedido_id | bigint unsigned | NOT NULL |
| producto_id | bigint unsigned | NOT NULL |
| cantidad | int | NOT NULL |
| descripcion | text | - |
| lleva_bordado | tinyint(1) | NOT NULL, DEFAULT '0' |
| nombre_logo | varchar(191) | DEFAULT NULL |
| color | varchar(50) | DEFAULT NULL |
| talla | varchar(50) | DEFAULT NULL |
| ubicacion_logo | varchar(191) | DEFAULT NULL |
| cantidad_logo | int | DEFAULT NULL |
| precio_unitario | decimal(10,2) | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - detalle_pedidos_pedido_id_foreign: (`pedido_id`)
  - detalle_pedidos_producto_id_foreign: (`producto_id`)
- **Foreign Keys**:
  - pedido_id -> pedido.id (ON DELETE CASCADE)
  - producto_id -> producto.id (ON DELETE CASCADE)

---

## Tabla: detalle_pedido_insumo

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| detalle_pedido_id | bigint unsigned | NOT NULL |
| insumo_id | bigint unsigned | NOT NULL |
| cantidad_estimada | decimal(8,2) | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - detalle_pedido_insumo_detalle_pedido_id_insumo_id_unique: (`detalle_pedido_id`,`insumo_id`)
- **Índices**:
  - detalle_pedido_insumo_insumo_id_foreign: (`insumo_id`)
- **Foreign Keys**:
  - detalle_pedido_id -> detalle_pedido.id (ON DELETE CASCADE)
  - insumo_id -> insumo.id (ON DELETE CASCADE)

---

## Tabla: direccion

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| persona_id | bigint unsigned | NOT NULL |
| direccion | varchar(255) | NOT NULL |
| ciudad | varchar(100) | DEFAULT NULL |
| estado | varchar(50) | DEFAULT NULL |
| tipo | enum('casa','trabajo','envio') | NOT NULL, DEFAULT 'casa' |
| es_principal | tinyint(1) | NOT NULL, DEFAULT '0' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - direccion_persona_id_index: (`persona_id`)
- **Foreign Keys**:
  - persona_id -> persona.id (ON DELETE CASCADE)

---

## Tabla: empleado

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| persona_id | bigint unsigned | NOT NULL |
| codigo_empleado | varchar(50) | NOT NULL |
| fecha_ingreso | date | NOT NULL |
| cargo | varchar(100) | NOT NULL |
| departamento | varchar(100) | NOT NULL |
| salario | decimal(10,2) | DEFAULT NULL |
| estado | tinyint(1) | NOT NULL, DEFAULT '1' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - empleado_persona_id_unique: (`persona_id`)
  - empleado_codigo_empleado_unique: (`codigo_empleado`)
- **Índices**: -
- **Foreign Keys**:
  - persona_id -> persona.id (ON DELETE CASCADE)

---

## Tabla: failed_jobs

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| uuid | varchar(191) | NOT NULL |
| connection | text | NOT NULL |
| queue | text | NOT NULL |
| payload | longtext | NOT NULL |
| exception | longtext | NOT NULL |
| failed_at | timestamp | NOT NULL, DEFAULT CURRENT_TIMESTAMP |

- **Primary Key**: (`id`)
- **Uniques**:
  - failed_jobs_uuid_unique: (`uuid`)
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: insumo

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| nombre | varchar(100) | NOT NULL |
| tipo | enum('Tela','Hilo','Botón','Cierre','Etiqueta','Otro') | NOT NULL |
| unidad_medida | varchar(20) | NOT NULL |
| costo_unitario | decimal(10,2) | NOT NULL |
| stock_actual | decimal(10,2) | NOT NULL |
| stock_minimo | decimal(10,2) | NOT NULL |
| proveedor_id | bigint unsigned | DEFAULT NULL |
| estado | tinyint(1) | NOT NULL, DEFAULT '1' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - insumos_proveedor_id_foreign: (`proveedor_id`)
- **Foreign Keys**:
  - proveedor_id -> proveedor.id (ON DELETE SET NULL)

---

## Tabla: migrations

| Campo | Tipo | Restricciones |
|---|---|---|
| id | int unsigned | NOT NULL, AUTO_INCREMENT |
| migration | varchar(191) | NOT NULL |
| batch | int | NOT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: movimiento_insumo

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| insumo_id | bigint unsigned | NOT NULL |
| tipo_movimiento | enum('Entrada','Salida') | NOT NULL |
| cantidad | decimal(10,2) | NOT NULL |
| stock_anterior | decimal(10,2) | NOT NULL |
| stock_nuevo | decimal(10,2) | NOT NULL |
| motivo | text | - |
| created_by | bigint unsigned | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - movimientos_insumos_insumo_id_foreign: (`insumo_id`)
  - movimientos_insumos_created_by_foreign: (`created_by`)
- **Foreign Keys**:
  - created_by -> user.id (-)
  - insumo_id -> insumo.id (ON DELETE CASCADE)

---

## Tabla: orden_produccion

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| pedido_id | bigint unsigned | DEFAULT NULL |
| producto_id | bigint unsigned | NOT NULL |
| cantidad_solicitada | int | NOT NULL |
| cantidad_producida | int | NOT NULL, DEFAULT '0' |
| fecha_inicio | date | NOT NULL |
| fecha_fin_estimada | date | NOT NULL |
| estado | enum('Pendiente','En Proceso','Finalizado','Cancelado') | NOT NULL, DEFAULT 'Pendiente' |
| costo_estimado | decimal(12,2) | NOT NULL, DEFAULT '0.00' |
| logo | varchar(191) | DEFAULT NULL |
| notas | text | - |
| created_by | bigint unsigned | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - ordenes_produccion_producto_id_foreign: (`producto_id`)
  - ordenes_produccion_created_by_foreign: (`created_by`)
  - orden_produccion_pedido_id_foreign: (`pedido_id`)
- **Foreign Keys**:
  - pedido_id -> pedido.id (ON DELETE SET NULL)
  - created_by -> user.id (-)
  - producto_id -> producto.id (ON DELETE CASCADE)

---

## Tabla: password_resets

| Campo | Tipo | Restricciones |
|---|---|---|
| email | varchar(191) | NOT NULL |
| token | varchar(191) | NOT NULL |
| created_at | timestamp | DEFAULT NULL |

- **Primary Key**: -
- **Uniques**: -
- **Índices**:
  - password_resets_email_index: (`email`)
- **Foreign Keys**: -

---

## Tabla: pedido

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| cotizacion_id | bigint unsigned | DEFAULT NULL |
| cliente_id | bigint unsigned | DEFAULT NULL |
| fecha_pedido | date | NOT NULL |
| fecha_entrega_estimada | date | DEFAULT NULL |
| estado | varchar(191) | NOT NULL, DEFAULT 'Pendiente' |
| prioridad | varchar(191) | NOT NULL, DEFAULT 'Normal' |
| total | decimal(10,2) | NOT NULL, DEFAULT '0.00' |
| abono | decimal(10,2) | NOT NULL, DEFAULT '0.00' |
| efectivo_pagado | tinyint(1) | NOT NULL, DEFAULT '0' |
| transferencia_pagado | tinyint(1) | NOT NULL, DEFAULT '0' |
| pago_movil_pagado | tinyint(1) | NOT NULL, DEFAULT '0' |
| referencia_transferencia | varchar(191) | DEFAULT NULL |
| banco_transferencia_id | bigint unsigned | DEFAULT NULL |
| referencia_pago_movil | varchar(191) | DEFAULT NULL |
| banco_pago_movil_id | bigint unsigned | DEFAULT NULL |
| user_id | bigint unsigned | NOT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |
| banco_id | bigint unsigned | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - pedidos_user_id_foreign: (`user_id`)
  - pedidos_banco_id_foreign: (`banco_id`)
  - pedido_cliente_id_foreign: (`cliente_id`)
  - pedido_cotizacion_id_foreign: (`cotizacion_id`)
- **Foreign Keys**:
  - cliente_id -> cliente.id (ON DELETE SET NULL)
  - cotizacion_id -> cotizacion.id (ON DELETE SET NULL)
  - banco_id -> banco.id (ON DELETE SET NULL)
  - user_id -> user.id (ON DELETE CASCADE)

---

## Tabla: persona

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| nombre | varchar(100) | NOT NULL |
| apellido | varchar(100) | NOT NULL |
| documento_identidad | varchar(50) | NOT NULL |
| tipo_documento | enum('V-','E-','J-','G-') | NOT NULL, DEFAULT 'V-' |
| email | varchar(255) | DEFAULT NULL |
| estado_persona | varchar(100) | DEFAULT NULL |
| fecha_nacimiento | date | DEFAULT NULL |
| genero | enum('M','F','Otro') | DEFAULT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - persona_documento_identidad_unique: (`documento_identidad`)
  - persona_email_unique: (`email`)
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: personal_access_tokens

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| tokenable_type | varchar(191) | NOT NULL |
| tokenable_id | bigint unsigned | NOT NULL |
| name | varchar(191) | NOT NULL |
| token | varchar(64) | NOT NULL |
| abilities | text | - |
| last_used_at | timestamp | DEFAULT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - personal_access_tokens_token_unique: (`token`)
- **Índices**:
  - personal_access_tokens_tokenable_type_tokenable_id_index: (`tokenable_type`,`tokenable_id`)
- **Foreign Keys**: -

---

## Tabla: produccion_diaria

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| orden_id | bigint unsigned | NOT NULL |
| operario_id | bigint unsigned | NOT NULL |
| cantidad_producida | int | NOT NULL |
| cantidad_defectuosa | int | NOT NULL, DEFAULT '0' |
| observaciones | text | - |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - produccion_diaria_orden_id_foreign: (`orden_id`)
  - produccion_diaria_operario_id_foreign: (`operario_id`)
- **Foreign Keys**:
  - operario_id -> user.id (ON DELETE CASCADE)
  - orden_id -> orden_produccion.id (ON DELETE CASCADE)

---

## Tabla: producto

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| tipo_producto_id | bigint unsigned | DEFAULT NULL |
| codigo | varchar(20) | DEFAULT NULL |
| descripcion | text | - |
| modelo | varchar(50) | NOT NULL |
| precio_base | decimal(10,2) | NOT NULL |
| imagen | varchar(191) | DEFAULT NULL |
| estado | tinyint(1) | NOT NULL, DEFAULT '1' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - producto_codigo_unique: (`codigo`)
- **Índices**:
  - producto_tipo_producto_id_foreign: (`tipo_producto_id`)
- **Foreign Keys**:
  - tipo_producto_id -> tipo_producto.id (ON DELETE SET NULL)

---

## Tabla: proveedor

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| tipo_proveedor | enum('natural','juridico') | NOT NULL, DEFAULT 'juridico' |
| persona_id | bigint unsigned | DEFAULT NULL |
| razon_social | varchar(100) | DEFAULT NULL |
| rif | varchar(15) | DEFAULT NULL |
| direccion | varchar(191) | DEFAULT NULL |
| telefono | varchar(20) | DEFAULT NULL |
| email | varchar(191) | DEFAULT NULL |
| contacto | varchar(100) | DEFAULT NULL |
| telefono_contacto | varchar(20) | DEFAULT NULL |
| estado | tinyint(1) | NOT NULL, DEFAULT '1' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - proveedores_ruc_unique: (`rif`)
- **Índices**:
  - proveedor_persona_id_foreign: (`persona_id`)
- **Foreign Keys**:
  - persona_id -> persona.id (ON DELETE SET NULL)

---

## Tabla: tasa_cambio

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| moneda | varchar(10) | NOT NULL |
| valor | decimal(12,4) | NOT NULL |
| fecha_bcv | date | NOT NULL |
| fuente | varchar(191) | NOT NULL, DEFAULT 'BCV' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - tasa_cambio_moneda_fecha_bcv_unique: (`moneda`,`fecha_bcv`)
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: telefono

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| persona_id | bigint unsigned | NOT NULL |
| numero | varchar(20) | NOT NULL |
| tipo | enum('movil','casa','trabajo') | NOT NULL, DEFAULT 'movil' |
| es_principal | tinyint(1) | NOT NULL, DEFAULT '0' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |
| deleted_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**: -
- **Índices**:
  - telefono_persona_id_index: (`persona_id`)
- **Foreign Keys**:
  - persona_id -> persona.id (ON DELETE CASCADE)

---

## Tabla: tipo_producto

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| nombre | varchar(100) | NOT NULL |
| codigo_prefijo | varchar(5) | NOT NULL |
| descripcion | text | - |
| contador | int | NOT NULL, DEFAULT '0' |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - tipo_producto_codigo_prefijo_unique: (`codigo_prefijo`)
- **Índices**: -
- **Foreign Keys**: -

---

## Tabla: user

| Campo | Tipo | Restricciones |
|---|---|---|
| id | bigint unsigned | NOT NULL, AUTO_INCREMENT |
| persona_id | bigint unsigned | DEFAULT NULL |
| name | varchar(191) | NOT NULL |
| email | varchar(191) | NOT NULL |
| role | enum('Administrador','Supervisor') | NOT NULL |
| email_verified_at | timestamp | DEFAULT NULL |
| password | varchar(191) | NOT NULL |
| avatar | text | - |
| estado | tinyint(1) | NOT NULL, DEFAULT '1' |
| remember_token | varchar(100) | DEFAULT NULL |
| created_at | timestamp | DEFAULT NULL |
| updated_at | timestamp | DEFAULT NULL |

- **Primary Key**: (`id`)
- **Uniques**:
  - users_email_unique: (`email`)
  - user_persona_id_unique: (`persona_id`)
- **Índices**: -
- **Foreign Keys**:
  - persona_id -> persona.id (ON DELETE CASCADE)

---

