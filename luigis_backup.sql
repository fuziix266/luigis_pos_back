-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: luigis
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'clasica','ClÔö£├¡sica',1),(2,'especial','Especial',2),(3,'premium','Premium',3),(4,'custom','Personalizada',4);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_zones`
--

DROP TABLE IF EXISTS `delivery_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_zones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lat_threshold` decimal(10,6) NOT NULL,
  `extra_charge` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_zones`
--

LOCK TABLES `delivery_zones` WRITE;
/*!40000 ALTER TABLE `delivery_zones` DISABLE KEYS */;
INSERT INTO `delivery_zones` VALUES (1,'Zona Norte Lejano',-18.425874,1000,1),(2,'Zona Norte',-18.481000,500,2),(3,'Zona Base (Sur)',-90.000000,0,3);
/*!40000 ALTER TABLE `delivery_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drinks`
--

DROP TABLE IF EXISTS `drinks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drinks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `price` int(11) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drinks`
--

LOCK TABLES `drinks` WRITE;
/*!40000 ALTER TABLE `drinks` DISABLE KEYS */;
INSERT INTO `drinks` VALUES (1,'Coca Cola',2500,1,1),(2,'Coca Cola Zero',2500,1,2),(3,'Fanta',2500,1,3),(4,'Inca Cola',2500,1,4),(5,'Sprite',2500,1,5);
/*!40000 ALTER TABLE `drinks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (1,'Aceitunas',1,1),(2,'Albahaca',1,2),(3,'Camarones',1,3),(4,'Carne',1,4),(5,'Champinon',1,5),(6,'Choclo',1,6),(7,'Chorizo Espanol',1,7),(8,'Crema',1,8),(9,'Jamon',1,9),(10,'Jamon Serrano',1,10),(11,'Pimenton',1,11),(12,'Pina',1,12),(13,'Pollo',1,13),(14,'Pepperoni',1,14),(15,'Queso',1,15),(16,'Queso Parmesano',1,16),(17,'Salame',1,17),(18,'Salsa Barbecue',1,18),(19,'Tocino',1,19),(20,'Tomate Cherry',1,20),(21,'Salsa de tomate',1,21),(22,'Oregano',1,22);
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item_extras`
--

DROP TABLE IF EXISTS `order_item_extras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_item_extras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` int(10) unsigned NOT NULL,
  `ingredient_id` int(10) unsigned DEFAULT NULL,
  `ingredient_name` varchar(60) NOT NULL,
  `extra_price` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `ingredient_id` (`ingredient_id`),
  CONSTRAINT `order_item_extras_ibfk_1` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_item_extras_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item_extras`
--

LOCK TABLES `order_item_extras` WRITE;
/*!40000 ALTER TABLE `order_item_extras` DISABLE KEYS */;
INSERT INTO `order_item_extras` VALUES (1,25,NULL,'Jamon',2000),(4,33,NULL,'Choclo',2000),(5,40,NULL,'Queso',2000),(6,40,NULL,'Aceitunas',2000);
/*!40000 ALTER TABLE `order_item_extras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `item_type` enum('pizza','promo','drink','side','delivery_fee') NOT NULL DEFAULT 'pizza',
  `item_name` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `removed_ingredients` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` int(11) NOT NULL DEFAULT 0,
  `total_price` int(11) NOT NULL DEFAULT 0,
  `comments` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (3,2,'promo','Promo del Día','Barbecue (Sin: Salsa Barbecue) x2 | Palitos de ajo | Coca Cola 1.5L','[\"Salsa Barbecue (Pizza 1)\",\"Salsa Barbecue (Pizza 2)\"]',1,17000,17000,'Barbecue (Sin: Salsa Barbecue) x2 | Palitos de ajo | Coca Cola 1.5L',0),(4,3,'promo','Promo del Día','Barbecue x2 | Palitos de ajo | Coca Cola 1.5L',NULL,1,17000,17000,'Barbecue x2 | Palitos de ajo | Coca Cola 1.5L',0),(5,1,'promo','Promo 1','Salame x2 | Pepperoni | Jamon','[]',1,24000,24000,'Salame x2 | Pepperoni | Jamon',0),(23,4,'pizza','Barbecue (Familiar)',NULL,NULL,1,11000,11000,NULL,0),(24,4,'pizza','Napoles (Familiar)',NULL,NULL,1,10000,10000,NULL,1),(25,4,'pizza','Clasica (Familiar)','Salame. (+ Jamon)','[]',1,9000,9000,'Salame. (+ Jamon)',2),(26,5,'promo','Promo del Día','Barbecue x2 | Palitos de ajo | Coca Cola 1.5L',NULL,1,17000,17000,'Barbecue x2 | Palitos de ajo | Coca Cola 1.5L',0),(29,6,'promo','Promo del Día','Barbecue | Napoles [Es una Napolitana xd] | Palitos de ajo | Coca Cola 1.5L','[]',1,17000,17000,'Barbecue | Napoles [Es una Napolitana xd] | Palitos de ajo | Coca Cola 1.5L',0),(30,6,'delivery_fee','Envío',NULL,'[]',1,3000,3000,NULL,1),(32,8,'pizza','Napoles (Familiar)','Sin: Crema','[\"Crema\"]',1,10000,10000,'Sin: Crema',0),(33,9,'pizza','Napolitana (Familiar)','(+ Choclo)','[]',1,11000,11000,'(+ Choclo)',0),(34,10,'pizza','Napolitana (Familiar)',NULL,'[]',1,9000,9000,NULL,0),(38,7,'side','Palitos de Ajo',NULL,NULL,1,3000,3000,NULL,0),(39,7,'side','Palitos Parmesano',NULL,NULL,1,3500,3500,NULL,1),(40,7,'pizza','Luigi\'s (Familiar)','(+ Queso, Aceitunas)','[]',1,15000,15000,'(+ Queso, Aceitunas)',2),(41,11,'promo','Promo del Día','Barbecue [carne] x2 | Palitos de ajo | Coca Cola 1.5L','[]',1,17000,17000,'Barbecue [carne] x2 | Palitos de ajo | Coca Cola 1.5L',0),(42,12,'promo','Promo 1','Champinon | Salame',NULL,1,12000,12000,'Champinon | Salame',0),(43,13,'pizza','Clasica (Familiar)','Jamon','[]',1,7000,7000,'Jamon',0),(44,14,'promo','Promo 1','Salame | Champinon',NULL,1,12000,12000,'Salame | Champinon',0),(45,15,'pizza','Clasica (Familiar)','Jamon','[]',1,7000,7000,'Jamon',0),(46,16,'pizza','Espanola (Familiar)',NULL,'[]',1,10000,10000,NULL,0),(48,18,'promo','Promo 1','Jamon | Champinon',NULL,1,12000,12000,'Jamon | Champinon',0),(49,19,'promo','Promo 2','Napolitana (Pimenton -> Champinon) x2 | Palitos de ajo | Inca Cola','[\"Pimenton -> Champinon (Pizza 1)\",\"Pimenton -> Champinon (Pizza 2)\"]',1,16000,16000,'Napolitana (Pimenton -> Champinon) x2 | Palitos de ajo | Inca Cola',0),(50,17,'promo','Promo 1','Pepperoni | Jamon',NULL,1,12000,12000,'Pepperoni | Jamon',0),(51,20,'promo','Promo 1','Pepperoni | Salame',NULL,1,12000,12000,'Pepperoni | Salame',0),(52,20,'delivery_fee','Envío',NULL,NULL,1,3000,3000,NULL,1),(53,21,'promo','Promo 2','Napolitana x2 | Palitos de ajo | Coca Cola 1.5L',NULL,1,16000,16000,'Napolitana x2 | Palitos de ajo | Coca Cola 1.5L',0),(58,23,'promo','Promo 1','Salame | Jamon',NULL,1,12000,12000,'Salame | Jamon',0),(59,22,'pizza','Espanola (Familiar)',NULL,'[]',1,10000,10000,NULL,0),(60,22,'pizza','Vegetariana (Familiar)',NULL,'[]',1,11000,11000,NULL,1),(61,22,'drink','Coca Cola',NULL,'[]',1,2500,2500,NULL,2),(62,22,'delivery_fee','Envío',NULL,'[]',1,3000,3000,NULL,3),(64,24,'side','Palitos Parmesano',NULL,'[]',1,3500,3500,NULL,0),(65,25,'pizza','Mediterranea (Familiar)',NULL,'[]',1,11000,11000,NULL,0),(67,27,'pizza','Arma Tu Pizza (Mediana)','(Pepperoni | Chorizo Espanol | Queso)','[]',1,9500,9500,'(Pepperoni | Chorizo Espanol | Queso)',0),(68,26,'promo','Promo 1','Salame | Pepperoni','[]',1,12000,12000,'Salame | Pepperoni',0);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(20) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `delivery_type` enum('Local','Retiro','Delivery','PedidosYa','UberEats') DEFAULT NULL,
  `payment_method` enum('Efectivo','Transferencia','Tarjeta','Debito','Credito') DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `address_detail` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `status` enum('NUEVO','PREP','ARMADO','HORNO','LISTO','RETIRADO','EN_CAMINO','ENTREGADO','ELIMINADO') NOT NULL DEFAULT 'NUEVO',
  `subtotal` int(11) NOT NULL DEFAULT 0,
  `delivery_fee` int(11) NOT NULL DEFAULT 0,
  `total_amount` int(11) NOT NULL DEFAULT 0,
  `activation_time` datetime DEFAULT NULL,
  `time_created` datetime NOT NULL DEFAULT current_timestamp(),
  `time_prep` datetime DEFAULT NULL,
  `time_armado` datetime DEFAULT NULL,
  `time_entered_oven` datetime DEFAULT NULL,
  `time_completed` datetime DEFAULT NULL,
  `time_pickup` datetime DEFAULT NULL,
  `time_delivered` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `sort_position` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_delivery_type` (`delivery_type`),
  KEY `idx_time_created` (`time_created`),
  KEY `idx_activation` (`activation_time`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'001',NULL,'Juan','Retiro','Tarjeta','',NULL,'','ENTREGADO',24000,0,24000,NULL,'2026-02-22 19:15:42','2026-02-22 19:18:53',NULL,'2026-02-22 19:27:12','2026-02-22 19:42:42',NULL,'2026-02-22 19:51:29',0,NULL,0),(2,'002',NULL,'Andrea','Retiro','Tarjeta','',NULL,'','ENTREGADO',17000,0,17000,NULL,'2026-02-22 19:18:19','2026-02-22 19:36:29',NULL,'2026-02-22 19:45:36','2026-02-22 19:56:15',NULL,'2026-02-22 19:56:27',0,NULL,1),(3,'003',NULL,'Noel',NULL,NULL,'',NULL,'','ENTREGADO',17000,0,17000,NULL,'2026-02-22 19:39:43','2026-02-22 19:57:12','2026-02-22 19:56:20','2026-02-22 20:09:50','2026-02-22 20:12:48',NULL,'2026-02-22 20:18:43',0,NULL,2),(4,'004',NULL,'Kris','Retiro','Efectivo','',NULL,'','ENTREGADO',30000,0,17000,NULL,'2026-02-22 19:53:51','2026-02-22 20:09:52',NULL,'2026-02-22 20:12:51','2026-02-22 20:27:55',NULL,'2026-02-22 20:27:58',0,NULL,3),(5,'005',NULL,'Anita',NULL,NULL,'',NULL,'','ENTREGADO',17000,0,17000,NULL,'2026-02-22 20:21:04','2026-02-22 20:32:00',NULL,NULL,'2026-02-22 20:45:51',NULL,'2026-02-22 20:47:14',0,NULL,4),(6,'006',NULL,'','Delivery','Transferencia','brest 681',NULL,'+56 9 5680 7174','ENTREGADO',20000,3000,23000,NULL,'2026-02-22 20:29:41',NULL,NULL,'2026-02-22 20:52:10','2026-02-22 20:59:48',NULL,'2026-02-22 20:59:28',0,NULL,0),(7,'007',NULL,'Juan',NULL,NULL,'',NULL,'','ENTREGADO',21500,0,21500,NULL,'2026-02-22 20:33:52','2026-02-22 20:52:13',NULL,NULL,'2026-02-22 21:07:06',NULL,'2026-02-22 21:11:17',0,NULL,1),(8,'008',NULL,'Omar',NULL,NULL,'',NULL,'','ENTREGADO',10000,0,0,NULL,'2026-02-22 20:38:24',NULL,'2026-02-22 21:09:03',NULL,'2026-02-22 21:14:51',NULL,'2026-02-22 21:15:32',0,NULL,0),(9,'009',NULL,'Ramon',NULL,NULL,'',NULL,'','ENTREGADO',11000,0,11000,NULL,'2026-02-22 20:39:07','2026-02-22 21:09:07','2026-02-22 21:11:47','2026-02-22 21:15:40','2026-02-22 21:19:14',NULL,'2026-02-22 21:19:46',0,NULL,1),(10,'010',NULL,'Paulina',NULL,NULL,'',NULL,'','ENTREGADO',9000,0,9000,NULL,'2026-02-22 20:42:18','2026-02-22 21:11:49','2026-02-22 21:15:39','2026-02-22 21:19:52','2026-02-22 21:24:31',NULL,'2026-02-22 21:26:21',0,NULL,2),(11,'011',NULL,'Carla',NULL,'Efectivo','',NULL,'','ENTREGADO',17000,0,17000,NULL,'2026-02-22 21:02:10','2026-02-22 21:15:42',NULL,'2026-02-22 21:34:14','2026-02-22 21:40:38',NULL,'2026-02-22 21:48:46',0,NULL,3),(12,'012',NULL,'Camila',NULL,NULL,'',NULL,'','ENTREGADO',12000,0,12000,NULL,'2026-02-22 21:06:36','2026-02-22 21:34:01','2026-02-22 21:38:13','2026-02-22 21:40:41','2026-02-22 21:49:47',NULL,'2026-02-22 21:53:01',0,NULL,5),(13,'013',NULL,'Cintia','Local','Efectivo','',NULL,'','ENTREGADO',7000,0,7000,NULL,'2026-02-22 21:08:03',NULL,NULL,'2026-02-22 21:30:31','2026-02-22 21:33:55',NULL,'2026-02-22 21:35:36',0,NULL,4),(14,'014',NULL,'Andres',NULL,NULL,'',NULL,'','ENTREGADO',12000,0,12000,NULL,'2026-02-22 21:22:31','2026-02-22 21:38:15','2026-02-22 21:42:25','2026-02-22 21:49:50','2026-02-22 21:58:43',NULL,'2026-02-22 21:59:19',0,NULL,6),(15,'015',NULL,'Luis',NULL,NULL,'',NULL,'','ENTREGADO',7000,0,7000,NULL,'2026-02-22 21:29:41','2026-02-22 21:42:27','2026-02-22 21:55:35','2026-02-22 21:58:45','2026-02-22 22:03:13',NULL,'2026-02-22 22:10:17',0,NULL,7),(16,'016',NULL,'LJubica',NULL,NULL,'',NULL,'','ENTREGADO',10000,0,10000,NULL,'2026-02-22 21:32:46','2026-02-22 21:55:37',NULL,'2026-02-22 22:03:15','2026-02-22 22:09:39',NULL,'2026-02-22 22:10:13',0,NULL,8),(17,'017',NULL,'Diego','Retiro','Transferencia','',NULL,'','ENTREGADO',12000,0,12000,NULL,'2026-02-22 21:50:37','2026-02-22 22:03:20',NULL,'2026-02-22 22:09:42','2026-02-22 22:18:39',NULL,'2026-02-22 22:21:17',0,NULL,9),(18,'018',NULL,'Rufino',NULL,NULL,'',NULL,'','ENTREGADO',12000,0,12000,NULL,'2026-02-22 21:55:17',NULL,NULL,'2026-02-22 22:18:41','2026-02-22 22:32:02',NULL,'2026-02-22 22:32:37',0,NULL,10),(19,'019',NULL,'Francisco','Retiro',NULL,'',NULL,'','ENTREGADO',16000,0,16000,NULL,'2026-02-22 21:57:47','2026-02-22 22:18:42',NULL,NULL,'2026-02-22 22:41:19',NULL,'2026-02-22 22:46:12',0,NULL,11),(20,'020',NULL,'','Delivery',NULL,'Marcos Maturana 2401',NULL,'','ENTREGADO',15000,3000,18000,NULL,'2026-02-22 22:11:45','2026-02-22 22:41:21',NULL,'2026-02-22 22:47:00','2026-02-22 22:50:48',NULL,'2026-02-22 22:56:41',0,NULL,0),(21,'021',NULL,'Andres Vargas','Local','Tarjeta','',NULL,'','ENTREGADO',16000,0,16000,NULL,'2026-02-22 22:15:55','2026-02-22 22:47:14',NULL,NULL,'2026-02-22 22:57:51',NULL,'2026-02-22 23:03:56',0,NULL,1),(22,'022',NULL,'','Delivery','Efectivo','Guillermo Sanchez 670 e12',NULL,'979775767','ENTREGADO',26500,3000,29500,NULL,'2026-02-22 22:23:37','2026-02-22 22:58:39',NULL,NULL,'2026-02-22 23:14:20',NULL,'2026-02-22 23:14:30',0,NULL,2),(23,'023',NULL,'Misael','Local','Efectivo','',NULL,'','ENTREGADO',12000,0,12000,NULL,'2026-02-22 22:24:06',NULL,NULL,'2026-02-22 23:21:19','2026-02-22 23:27:44',NULL,'2026-02-22 23:27:54',0,NULL,0),(24,'024',NULL,'Jorge','Local','Tarjeta','',NULL,'','ENTREGADO',3500,0,3500,NULL,'2026-02-22 22:32:30',NULL,NULL,'2026-02-22 23:15:30','2026-02-22 23:21:13',NULL,'2026-02-22 23:33:22',0,NULL,2),(25,'025',NULL,'','Retiro',NULL,'',NULL,'','ELIMINADO',11000,0,11000,NULL,'2026-02-22 22:35:09',NULL,NULL,NULL,NULL,NULL,'2026-02-22 22:35:50',1,NULL,17),(26,'026',NULL,'Darwin','Delivery','Efectivo','Los ciruelos 2634',NULL,'999290598','ENTREGADO',12000,0,12000,NULL,'2026-02-22 22:37:00',NULL,NULL,'2026-02-22 23:37:57','2026-02-22 23:42:39',NULL,'2026-02-22 23:46:36',0,NULL,3),(27,'027',NULL,'Karina','Local','Efectivo','',NULL,'','ENTREGADO',9500,0,9500,NULL,'2026-02-22 22:39:40',NULL,NULL,'2026-02-22 23:27:06','2026-02-22 23:27:48',NULL,'2026-02-22 23:27:58',0,NULL,1);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_ingredients`
--

DROP TABLE IF EXISTS `pizza_ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizza_ingredients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pizza_id` int(10) unsigned NOT NULL,
  `ingredient_id` int(10) unsigned NOT NULL,
  `is_base` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pizza_ing` (`pizza_id`,`ingredient_id`),
  KEY `ingredient_id` (`ingredient_id`),
  CONSTRAINT `pizza_ingredients_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizzas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pizza_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_ingredients`
--

LOCK TABLES `pizza_ingredients` WRITE;
/*!40000 ALTER TABLE `pizza_ingredients` DISABLE KEYS */;
INSERT INTO `pizza_ingredients` VALUES (1,1,15,1),(2,1,21,1),(3,1,22,1),(4,2,17,1),(5,2,15,1),(6,2,21,1),(7,2,22,1),(8,3,9,1),(9,3,15,1),(10,3,21,1),(11,3,22,1),(12,4,5,1),(13,4,15,1),(14,4,21,1),(15,4,22,1),(16,5,14,1),(17,5,15,1),(18,5,21,1),(19,5,22,1),(20,6,21,1),(21,6,9,1),(22,6,11,1),(23,6,1,1),(24,6,15,1),(25,6,22,1),(26,7,21,1),(27,7,13,1),(28,7,11,1),(29,7,6,1),(30,7,15,1),(31,7,22,1),(32,8,21,1),(33,8,9,1),(34,8,1,1),(35,8,12,1),(36,8,15,1),(37,8,22,1),(38,9,21,1),(39,9,9,1),(40,9,11,1),(41,9,7,1),(42,9,20,1),(43,9,15,1),(44,9,22,1),(45,10,21,1),(46,10,19,1),(47,10,5,1),(48,10,8,1),(49,10,15,1),(50,10,22,1),(51,11,21,1),(52,11,2,1),(53,11,6,1),(54,11,1,1),(55,11,11,1),(56,11,5,1),(57,11,15,1),(58,11,22,1),(59,12,21,1),(60,12,4,1),(61,12,13,1),(62,12,19,1),(63,12,18,1),(64,12,15,1),(65,12,22,1),(66,13,21,1),(67,13,10,1),(68,13,16,1),(69,13,20,1),(70,13,2,1),(71,13,15,1),(72,13,22,1),(73,14,21,1),(74,14,3,1),(75,14,7,1),(76,14,13,1),(77,14,11,1),(78,14,5,1),(79,14,6,1),(80,14,15,1),(81,14,22,1),(82,15,21,1),(83,15,15,1),(84,15,22,1);
/*!40000 ALTER TABLE `pizza_ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_prices`
--

DROP TABLE IF EXISTS `pizza_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizza_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pizza_id` int(10) unsigned NOT NULL,
  `size_id` int(10) unsigned NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pizza_size` (`pizza_id`,`size_id`),
  KEY `size_id` (`size_id`),
  CONSTRAINT `pizza_prices_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizzas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pizza_prices_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `pizza_sizes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_prices`
--

LOCK TABLES `pizza_prices` WRITE;
/*!40000 ALTER TABLE `pizza_prices` DISABLE KEYS */;
INSERT INTO `pizza_prices` VALUES (1,1,1,5000),(2,1,2,6000),(3,1,3,7000),(4,2,1,5000),(5,2,2,6000),(6,2,3,7500),(7,3,1,5000),(8,3,2,6000),(9,3,3,7500),(10,4,1,5000),(11,4,2,6000),(12,4,3,7500),(13,5,1,5000),(14,5,2,6000),(15,5,3,7500),(16,6,1,6000),(17,6,2,7500),(18,6,3,9000),(19,7,1,7000),(20,7,2,8500),(21,7,3,10000),(22,8,1,7000),(23,8,2,8500),(24,8,3,10000),(25,9,1,7000),(26,9,2,8500),(27,9,3,10000),(28,10,1,7000),(29,10,2,8500),(30,10,3,10000),(31,11,1,8000),(32,11,2,9500),(33,11,3,11000),(34,12,1,8000),(35,12,2,9500),(36,12,3,11000),(37,13,1,8000),(38,13,2,9500),(39,13,3,11000),(40,14,1,8000),(41,14,2,9500),(42,14,3,11000),(43,15,1,8000),(44,15,2,9500),(45,15,3,11000);
/*!40000 ALTER TABLE `pizza_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizza_sizes`
--

DROP TABLE IF EXISTS `pizza_sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizza_sizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `extra_price` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizza_sizes`
--

LOCK TABLES `pizza_sizes` WRITE;
/*!40000 ALTER TABLE `pizza_sizes` DISABLE KEYS */;
INSERT INTO `pizza_sizes` VALUES (1,'small','Chica',900,1),(2,'medium','Mediana',1300,2),(3,'large','Familiar',2000,3);
/*!40000 ALTER TABLE `pizza_sizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pizzas`
--

DROP TABLE IF EXISTS `pizzas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pizzas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `is_customizable` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `pizzas_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pizzas`
--

LOCK TABLES `pizzas` WRITE;
/*!40000 ALTER TABLE `pizzas` DISABLE KEYS */;
INSERT INTO `pizzas` VALUES (1,'Clasica',1,1,0,1),(2,'Clasica Salame',1,1,0,2),(3,'Clasica Jamon',1,1,0,3),(4,'Clasica Champinon',1,1,0,4),(5,'Clasica Pepperoni',1,1,0,5),(6,'Napolitana',2,1,0,6),(7,'Di\'Pollo',2,1,0,7),(8,'Hawaiana',2,1,0,8),(9,'Espanola',2,1,0,9),(10,'Napoles',2,1,0,10),(11,'Vegetariana',3,1,0,11),(12,'Barbecue',3,1,0,12),(13,'Mediterranea',3,1,0,13),(14,'Luigi\'s',3,1,0,14),(15,'Arma Tu Pizza',4,1,1,15);
/*!40000 ALTER TABLE `pizzas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_day_config`
--

DROP TABLE IF EXISTS `promo_day_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_day_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day_of_week` tinyint(4) NOT NULL,
  `pizza_id` int(10) unsigned DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_day` (`day_of_week`),
  KEY `pizza_id` (`pizza_id`),
  CONSTRAINT `promo_day_config_ibfk_1` FOREIGN KEY (`pizza_id`) REFERENCES `pizzas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_day_config`
--

LOCK TABLES `promo_day_config` WRITE;
/*!40000 ALTER TABLE `promo_day_config` DISABLE KEYS */;
INSERT INTO `promo_day_config` VALUES (1,0,12,0),(2,1,7,0),(3,2,10,0),(4,3,NULL,1),(5,4,9,0),(6,5,8,0),(7,6,11,0);
/*!40000 ALTER TABLE `promo_day_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promos`
--

DROP TABLE IF EXISTS `promos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `code` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promos`
--

LOCK TABLES `promos` WRITE;
/*!40000 ALTER TABLE `promos` DISABLE KEYS */;
INSERT INTO `promos` VALUES (1,'Promo 1','promo_1','2 Pizzas Clasicas (opcion 3ra pizza por $18.000)',12000,1,1,'2026-02-14 15:16:48'),(2,'Promo 2','promo_2','2 Pizzas + Palitos Ajo + Bebida 1.5L',16000,1,2,'2026-02-14 15:16:48'),(3,'Promo del Dia','promo_day',NULL,17000,1,3,'2026-02-14 15:16:48');
/*!40000 ALTER TABLE `promos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `side_dishes`
--

DROP TABLE IF EXISTS `side_dishes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `side_dishes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `price` int(11) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `side_dishes`
--

LOCK TABLES `side_dishes` WRITE;
/*!40000 ALTER TABLE `side_dishes` DISABLE KEYS */;
INSERT INTO `side_dishes` VALUES (1,'Palitos de Ajo',3000,1,1),(2,'Palitos Parmesano',3500,1,2);
/*!40000 ALTER TABLE `side_dishes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_config`
--

DROP TABLE IF EXISTS `system_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_key` varchar(50) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_config`
--

LOCK TABLES `system_config` WRITE;
/*!40000 ALTER TABLE `system_config` DISABLE KEYS */;
INSERT INTO `system_config` VALUES (1,'delivery_base_fee','3000','Tarifa base de delivery en CLP'),(2,'oven_chambers','1','CÔö£├¡maras activas del horno (1 o 2)'),(3,'store_city','Arica','Ciudad del local'),(4,'store_country','Chile','PaÔö£┬ís del local'),(5,'daily_order_counter','0','Contador diario de pedidos'),(6,'store_name','Luigi\'s Pizza','Nombre del local'),(7,'notification_sound_enabled','1','Sonido de notificaciÔö£Ôöén');
/*!40000 ALTER TABLE `system_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','cajero','cocina','delivery') NOT NULL DEFAULT 'cajero',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrador','admin',1,'2026-02-14 15:16:46',NULL),(2,'cajero','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Cajero 1','cajero',1,'2026-02-14 15:16:46',NULL),(3,'cocina','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Cocina 1','cocina',1,'2026-02-14 15:16:46',NULL),(4,'delivery','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Delivery 1','delivery',1,'2026-02-14 15:16:46',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-23  2:24:28
