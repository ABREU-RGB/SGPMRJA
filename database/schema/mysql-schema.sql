/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `banco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banco` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bancos_nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tipo_cliente` enum('natural','juridico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'natural',
  `estatus` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cliente_persona_id_foreign` (`persona_id`),
  CONSTRAINT `cliente_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotizacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `fecha_cotizacion` date NOT NULL,
  `fecha_validez` date DEFAULT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `user_id` bigint unsigned NOT NULL,
  `prioridad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizaciones_user_id_foreign` (`user_id`),
  KEY `cotizacion_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cotizacion_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `cotizaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detalle_cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_cotizacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `lleva_bordado` tinyint(1) NOT NULL DEFAULT '0',
  `nombre_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubicacion_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad_logo` int DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talla` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_cotizaciones_cotizacion_id_foreign` (`cotizacion_id`),
  KEY `detalle_cotizaciones_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_cotizaciones_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizacion` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_cotizaciones_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detalle_orden_insumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_orden_insumo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orden_produccion_id` bigint unsigned NOT NULL,
  `insumo_id` bigint unsigned NOT NULL,
  `cantidad_estimada` decimal(10,2) NOT NULL,
  `cantidad_utilizada` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_orden_insumos_orden_produccion_id_foreign` (`orden_produccion_id`),
  KEY `detalle_orden_insumos_insumo_id_foreign` (`insumo_id`),
  CONSTRAINT `detalle_orden_insumos_insumo_id_foreign` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_orden_insumos_orden_produccion_id_foreign` FOREIGN KEY (`orden_produccion_id`) REFERENCES `orden_produccion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detalle_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `lleva_bordado` tinyint(1) NOT NULL DEFAULT '0',
  `nombre_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `talla` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubicacion_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad_logo` int DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_pedidos_pedido_id_foreign` (`pedido_id`),
  KEY `detalle_pedidos_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_pedidos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pedidos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detalle_pedido_insumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedido_insumo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `detalle_pedido_id` bigint unsigned NOT NULL,
  `insumo_id` bigint unsigned NOT NULL,
  `cantidad_estimada` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `detalle_pedido_insumo_detalle_pedido_id_insumo_id_unique` (`detalle_pedido_id`,`insumo_id`),
  KEY `detalle_pedido_insumo_insumo_id_foreign` (`insumo_id`),
  CONSTRAINT `detalle_pedido_insumo_detalle_pedido_id_foreign` FOREIGN KEY (`detalle_pedido_id`) REFERENCES `detalle_pedido` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pedido_insumo_insumo_id_foreign` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direccion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint unsigned NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Estado/Territorio geográfico',
  `tipo` enum('casa','trabajo','envio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'casa',
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `direccion_persona_id_index` (`persona_id`),
  CONSTRAINT `direccion_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint unsigned NOT NULL,
  `codigo_empleado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empleado_persona_id_unique` (`persona_id`),
  UNIQUE KEY `empleado_codigo_empleado_unique` (`codigo_empleado`),
  CONSTRAINT `empleado_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `insumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('Tela','Hilo','Botón','Cierre','Etiqueta','Otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidad_medida` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL,
  `stock_minimo` decimal(10,2) NOT NULL,
  `proveedor_id` bigint unsigned DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insumos_proveedor_id_foreign` (`proveedor_id`),
  CONSTRAINT `insumos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movimiento_insumo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_insumo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `insumo_id` bigint unsigned NOT NULL,
  `tipo_movimiento` enum('Entrada','Salida') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_anterior` decimal(10,2) NOT NULL,
  `stock_nuevo` decimal(10,2) NOT NULL,
  `motivo` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_insumos_insumo_id_foreign` (`insumo_id`),
  KEY `movimientos_insumos_created_by_foreign` (`created_by`),
  CONSTRAINT `movimientos_insumos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `movimientos_insumos_insumo_id_foreign` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orden_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_produccion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned DEFAULT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad_solicitada` int NOT NULL,
  `cantidad_producida` int NOT NULL DEFAULT '0',
  `fecha_inicio` date NOT NULL,
  `fecha_fin_estimada` date NOT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizado','Cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `costo_estimado` decimal(12,2) NOT NULL DEFAULT '0.00',
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ordenes_produccion_producto_id_foreign` (`producto_id`),
  KEY `ordenes_produccion_created_by_foreign` (`created_by`),
  KEY `orden_produccion_pedido_id_foreign` (`pedido_id`),
  CONSTRAINT `orden_produccion_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE SET NULL,
  CONSTRAINT `ordenes_produccion_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  CONSTRAINT `ordenes_produccion_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint unsigned DEFAULT NULL,
  `cliente_id` bigint unsigned DEFAULT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `estado` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `prioridad` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `abono` decimal(10,2) NOT NULL DEFAULT '0.00',
  `efectivo_pagado` tinyint(1) NOT NULL DEFAULT '0',
  `transferencia_pagado` tinyint(1) NOT NULL DEFAULT '0',
  `pago_movil_pagado` tinyint(1) NOT NULL DEFAULT '0',
  `referencia_transferencia` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco_transferencia_id` bigint unsigned DEFAULT NULL,
  `referencia_pago_movil` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco_pago_movil_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `banco_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedidos_user_id_foreign` (`user_id`),
  KEY `pedidos_banco_id_foreign` (`banco_id`),
  KEY `pedido_cliente_id_foreign` (`cliente_id`),
  KEY `pedido_cotizacion_id_foreign` (`cotizacion_id`),
  CONSTRAINT `pedido_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedido_cotizacion_id_foreign` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizacion` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_banco_id_foreign` FOREIGN KEY (`banco_id`) REFERENCES `banco` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento_identidad` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('V-','E-','J-','G-') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'V-',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_persona` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','Otro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persona_documento_identidad_unique` (`documento_identidad`),
  UNIQUE KEY `persona_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produccion_diaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produccion_diaria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `orden_id` bigint unsigned NOT NULL,
  `operario_id` bigint unsigned NOT NULL,
  `cantidad_producida` int NOT NULL,
  `cantidad_defectuosa` int NOT NULL DEFAULT '0',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produccion_diaria_orden_id_foreign` (`orden_id`),
  KEY `produccion_diaria_operario_id_foreign` (`operario_id`),
  CONSTRAINT `produccion_diaria_operario_id_foreign` FOREIGN KEY (`operario_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produccion_diaria_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `orden_produccion` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_producto_id` bigint unsigned DEFAULT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `modelo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `imagen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `producto_codigo_unique` (`codigo`),
  KEY `producto_tipo_producto_id_foreign` (`tipo_producto_id`),
  CONSTRAINT `producto_tipo_producto_id_foreign` FOREIGN KEY (`tipo_producto_id`) REFERENCES `tipo_producto` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedor` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_proveedor` enum('natural','juridico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'juridico',
  `persona_id` bigint unsigned DEFAULT NULL,
  `razon_social` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rif` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_contacto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_ruc_unique` (`rif`),
  KEY `proveedor_persona_id_foreign` (`persona_id`),
  CONSTRAINT `proveedor_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tasa_cambio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tasa_cambio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(12,4) NOT NULL,
  `fecha_bcv` date NOT NULL,
  `fuente` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BCV',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tasa_cambio_moneda_fecha_bcv_unique` (`moneda`,`fecha_bcv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telefono`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telefono` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint unsigned NOT NULL,
  `numero` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('movil','casa','trabajo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'movil',
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `telefono_persona_id_index` (`persona_id`),
  CONSTRAINT `telefono_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tipo_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_producto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_prefijo` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `contador` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo_producto_codigo_prefijo_unique` (`codigo_prefijo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Administrador','Supervisor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `user_persona_id_unique` (`persona_id`),
  CONSTRAINT `user_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_03_01_000000_create_sistema_produccion_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_06_14_091624_create_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_06_14_091726_create_detalle_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_06_14_094205_add_fecha_entrega_estimada_to_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_06_14_100214_add_rif_to_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_06_14_102229_remove_unique_rif_from_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_06_14_103232_rename_rif_to_ci_rif_in_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_06_14_112859_add_description_and_logo_fields_to_detalle_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_06_14_114729_add_talla_and_color_to_detalle_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_06_14_115649_update_talla_enum_in_detalle_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_06_14_123551_force_update_talla_enum_in_detalle_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_06_14_210039_create_detalle_pedido_insumo_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_06_15_191252_create_bancos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_06_15_191339_add_payment_fields_to_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_06_19_143226_create_clientes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_06_19_143359_add_cliente_id_to_pedidos_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_06_20_000001_create_cotizaciones_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_06_20_000002_create_detalle_cotizaciones_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_06_21_112333_add_deleted_at_to_clientes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_06_26_221106_remove_prioridad_column_from_cotizaciones_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_12_04_134221_update_user_role_enum',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_12_04_150028_rename_all_tables_to_singular_final',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_12_04_153326_add_missing_columns_to_cliente_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_12_04_154406_create_persona_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_12_04_154408_create_empleado_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_12_04_154409_add_persona_id_to_user_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_12_04_154448_migrate_users_to_persona',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_12_04_154449_create_empleados_from_supervisores',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_12_05_165423_rename_ruc_to_rif_in_proveedor_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_12_08_151400_add_cliente_id_to_cotizacion',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_12_08_153400_normalize_cotizacion_remove_redundant_cliente_fields',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_12_08_154900_normalize_cliente_with_persona',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_12_09_170406_remove_payment_columns_from_cotizacion_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_12_10_143835_create_telefono_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_12_10_144011_create_direccion_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_12_10_144137_migrate_telefono_direccion_data_from_persona',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_12_10_164505_remove_telefono_direccion_ciudad_from_persona_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_12_10_173653_add_cliente_id_to_pedido_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_12_10_194225_remove_legacy_cliente_columns_from_pedido_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_12_15_150500_make_color_nullable_in_producto_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_12_15_155200_make_material_talla_nullable_in_producto_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_12_15_160400_drop_material_talla_from_producto_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_12_15_164400_create_tasa_cambio_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_12_16_134300_create_tipo_producto_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_12_16_134400_add_tipo_and_codigo_to_producto_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_12_18_134800_add_pedido_id_and_logo_to_orden_produccion',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_12_19_152000_add_cotizacion_id_to_pedido',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2026_01_17_225337_rename_estado_to_estatus_and_add_estado_territorial',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2026_01_18_160000_add_tipo_proveedor_and_persona_id_to_proveedor',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2026_01_18_162000_make_proveedor_fields_nullable',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2026_01_19_145036_add_separate_bank_fields_to_pedidos_table',24);
