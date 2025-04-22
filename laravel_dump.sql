
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('shipping','billing','both') NOT NULL DEFAULT 'both',
  `is_default_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_billing` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-11 09:31:34','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (2,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-11 09:35:44','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (3,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-11 09:40:02','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (4,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-11 10:18:18','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (5,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-11 10:22:27','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (6,NULL,4,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',1,'shipping',0,0,'2025-04-11 10:40:50','2025-04-11 10:40:50');
INSERT INTO `addresses` VALUES (7,NULL,3,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',0,'shipping',0,0,'2025-04-14 10:08:23','2025-04-14 10:08:23');
INSERT INTO `addresses` VALUES (8,'test',6,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816878',0,'shipping',0,0,'2025-04-15 12:49:47','2025-04-15 19:24:31');
INSERT INTO `addresses` VALUES (10,'test',6,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','0467816872',1,'shipping',0,0,'2025-04-15 19:24:31','2025-04-15 19:24:31');
INSERT INTO `addresses` VALUES (14,'test',1,'125 rue theodore verhaegen',NULL,'Bruxelles','Bruxelles','1060','Belgique','04678168788',1,'shipping',0,0,'2025-04-18 06:39:07','2025-04-18 06:39:07');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (3,2,1,1,1499.00,'2025-04-11 05:54:53','2025-04-11 05:54:53');
INSERT INTO `cart_items` VALUES (4,2,10,1,1555.99,'2025-04-11 06:03:30','2025-04-11 06:03:30');
INSERT INTO `cart_items` VALUES (5,2,3,1,1655.99,'2025-04-11 06:10:19','2025-04-11 06:10:19');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,1,NULL,'2025-04-11 05:13:05','2025-04-11 05:13:05');
INSERT INTO `carts` VALUES (2,2,NULL,'2025-04-11 05:54:53','2025-04-11 05:54:53');
INSERT INTO `carts` VALUES (3,4,NULL,'2025-04-11 06:19:21','2025-04-11 06:19:21');
INSERT INTO `carts` VALUES (4,3,NULL,'2025-04-14 10:08:00','2025-04-14 10:08:00');
INSERT INTO `carts` VALUES (5,6,NULL,'2025-04-15 12:49:35','2025-04-15 12:49:35');
INSERT INTO `carts` VALUES (7,NULL,'MoY6EP3UaaCmL9N7hN2srNHXWwBHOkvfUx0A7Ls1','2025-04-18 07:07:47','2025-04-18 07:07:47');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `parent_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Électronique','electronique',NULL,NULL,1,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `categories` VALUES (2,'Smartphones','smartphones',NULL,1,1,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `categories` VALUES (3,'Ordinateurs','ordinateurs',NULL,1,1,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `categories` VALUES (4,'Accessoires','accessoires',NULL,1,1,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `categories` VALUES (5,'Vêtements','vetements',NULL,NULL,0,'2025-04-09 09:08:44','2025-04-18 05:49:04');
INSERT INTO `categories` VALUES (6,'Hommes','hommes',NULL,5,0,'2025-04-09 09:08:44','2025-04-18 05:48:10');
INSERT INTO `categories` VALUES (7,'Femmes','femmes',NULL,5,0,'2025-04-09 09:08:44','2025-04-18 05:48:03');
INSERT INTO `categories` VALUES (8,'Enfants','enfants',NULL,5,0,'2025-04-09 09:08:44','2025-04-18 05:47:56');
INSERT INTO `categories` VALUES (9,'Maison','maison',NULL,NULL,0,'2025-04-09 09:08:44','2025-04-18 05:47:48');
INSERT INTO `categories` VALUES (10,'Décoration','decoration',NULL,9,0,'2025-04-09 09:08:44','2025-04-18 05:47:36');
INSERT INTO `categories` VALUES (11,'Cuisine','cuisine',NULL,9,0,'2025-04-09 09:08:44','2025-04-18 05:47:27');
INSERT INTO `categories` VALUES (12,'Jardin','jardin',NULL,9,0,'2025-04-09 09:08:44','2025-04-18 09:12:14');
INSERT INTO `categories` VALUES (13,'Test','test',NULL,NULL,0,'2025-04-18 06:25:14','2025-04-22 16:20:31');
INSERT INTO `categories` VALUES (14,'Test 1','test-1',NULL,NULL,0,'2025-04-18 08:55:25','2025-04-18 09:12:30');
INSERT INTO `categories` VALUES (16,'Test 2','test-2',NULL,NULL,0,'2025-04-18 09:04:21','2025-04-22 16:20:23');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"55de4446-94f2-4d9d-b782-f2bc01a919b6\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:21;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"f90250cf-c3eb-42b1-b317-287f12f087d9\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1744966118,1744966118);
INSERT INTO `jobs` VALUES (2,'default','{\"uuid\":\"2ca13950-b790-415b-8ed2-0cb32f01687d\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"731937cd-a022-432d-a270-c3e319b5a4d0\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1744966309,1744966309);
INSERT INTO `jobs` VALUES (3,'default','{\"uuid\":\"b39c5e60-44b9-47dc-b2b2-0d3c9d62cc4d\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"a304fe31-a0d4-4cec-9adf-bed47fe1f5d6\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1744972588,1744972588);
INSERT INTO `jobs` VALUES (4,'default','{\"uuid\":\"237edadb-a936-4c52-8fec-c25f9d033b77\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"425e499c-36ec-4bff-8f86-a9531021ec8f\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1744974771,1744974771);
INSERT INTO `jobs` VALUES (5,'default','{\"uuid\":\"01d59a23-f6f3-4d0e-ab0a-0c853eaac375\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:24;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"aa2c9ea0-f0c5-41e0-9609-2dbcc0fb048a\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1745314625,1745314625);
INSERT INTO `jobs` VALUES (6,'default','{\"uuid\":\"d687be67-be72-4979-bd97-487dcf1c898f\",\"displayName\":\"App\\\\Notifications\\\\OrderStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:6;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:36:\\\"App\\\\Notifications\\\\OrderStatusUpdated\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:25;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"06e3749a-b902-4fc6-985f-40f474399185\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1745318019,1745318019);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2025_04_09_091032_create_permission_tables',1);
INSERT INTO `migrations` VALUES (5,'2025_04_09_091742_create_products_table',1);
INSERT INTO `migrations` VALUES (6,'2025_04_09_101058_create_carts_table',1);
INSERT INTO `migrations` VALUES (7,'2025_04_09_101108_create_cart_items_table',1);
INSERT INTO `migrations` VALUES (8,'2025_04_09_110146_create_categories_table',1);
INSERT INTO `migrations` VALUES (9,'2025_04_09_110157_update_products_table',1);
INSERT INTO `migrations` VALUES (10,'2025_04_09_110726_create_addresses_table',1);
INSERT INTO `migrations` VALUES (11,'2025_04_09_110736_create_orders_table',1);
INSERT INTO `migrations` VALUES (12,'2025_04_09_110742_create_order_items_table',1);
INSERT INTO `migrations` VALUES (13,'2025_04_10_080902_create_wishlist_items_table',2);
INSERT INTO `migrations` VALUES (14,'2025_04_11_115531_create_notifications_table',3);
INSERT INTO `migrations` VALUES (15,'2025_04_15_211516_add_name_to_addresses_table',4);
INSERT INTO `migrations` VALUES (16,'2025_04_15_211935_add_phone_to_users_table',5);
INSERT INTO `migrations` VALUES (17,'2025_04_15_212304_add_default_flags_to_addresses_table',6);
INSERT INTO `migrations` VALUES (18,'2025_04_17_073555_add_subtotal_to_orders_table',7);
INSERT INTO `migrations` VALUES (19,'2025_04_17_080259_remove_total_amount_from_orders_table',8);
INSERT INTO `migrations` VALUES (20,'2025_04_18_082138_update_is_active_default_on_categories_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1);
INSERT INTO `model_has_roles` VALUES (2,'App\\Models\\User',2);
INSERT INTO `model_has_roles` VALUES (3,'App\\Models\\User',3);
INSERT INTO `model_has_roles` VALUES (4,'App\\Models\\User',4);
INSERT INTO `model_has_roles` VALUES (4,'App\\Models\\User',5);
INSERT INTO `model_has_roles` VALUES (4,'App\\Models\\User',6);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('16b23e75-8a19-4399-b9b2-17ae7aa41d85','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":12,\"order_number\":\"ORD-R9GHSD2NFS\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 06:27:30','2025-04-17 06:27:30');
INSERT INTO `notifications` VALUES ('17706cc9-1b4f-4733-b4f5-86a69c2b988a','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":25,\"order_number\":\"ORD-VE7D47FKSH\",\"user_name\":\"prince Aman\",\"total\":364.1979}',NULL,'2025-04-22 08:32:16','2025-04-22 08:32:16');
INSERT INTO `notifications` VALUES ('1936860e-5905-406a-a8f8-8c5f49aba541','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":22,\"order_number\":\"ORD-ZYCNU1ZSIZ\",\"user_name\":\"Javed Ur Rehman\",\"total\":5448.5937}',NULL,'2025-04-18 06:39:59','2025-04-18 06:39:59');
INSERT INTO `notifications` VALUES ('1d7631b8-c32a-4ff9-a340-c2726f2fb0de','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":27,\"order_number\":\"ORD-EBVO7SAO3O\",\"user_name\":\"prince Aman\",\"total\":1092.5937}','2025-04-22 09:01:12','2025-04-22 09:00:41','2025-04-22 09:01:12');
INSERT INTO `notifications` VALUES ('234b1bb1-a419-4fd9-a440-c04d2f9b420f','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":26,\"order_number\":\"ORD-TNSHONLIHN\",\"user_name\":\"Super Admin\",\"total\":787.6979}','2025-04-22 08:55:24','2025-04-22 08:55:21','2025-04-22 08:55:24');
INSERT INTO `notifications` VALUES ('250bb5c5-fcdd-486e-a59b-8e123fceaa7c','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":25,\"order_number\":\"ORD-VE7D47FKSH\",\"user_name\":\"prince Aman\",\"total\":364.1979}','2025-04-22 08:32:55','2025-04-22 08:32:16','2025-04-22 08:32:55');
INSERT INTO `notifications` VALUES ('27caa48c-6ee1-432e-9e13-8717b364da8b','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":13,\"order_number\":\"ORD-HSBATZFBWQ\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 06:36:19','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('27e3bc66-73b7-487f-97b1-e30232f92217','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":11,\"order_number\":\"ORD-T1C2UQXFSI\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 06:05:30','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('27f03ce1-58be-4d2a-846a-0fd0608614ac','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":21,\"order_number\":\"ORD-3H3ZBCLJMO\",\"user_name\":\"prince Aman\",\"total\":2003.7479}',NULL,'2025-04-18 06:04:22','2025-04-18 06:04:22');
INSERT INTO `notifications` VALUES ('2da59ee2-c78f-4e84-9445-d9616665a3df','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":7,\"order_number\":\"ORD-5ES4LIUHS7\",\"user_name\":\"prince\",\"total\":\"1500.99\"}','2025-04-15 12:51:48','2025-04-15 12:50:09','2025-04-15 12:51:48');
INSERT INTO `notifications` VALUES ('33a23999-9746-475e-be4a-00275d0efe12','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":6,\"order_number\":\"ORD-J2TTC0WQ8C\",\"user_name\":\"Abdul Muqeet\",\"total\":\"3001.98\"}',NULL,'2025-04-14 10:09:29','2025-04-14 10:09:29');
INSERT INTO `notifications` VALUES ('40d068f2-0a0b-4b51-bfd5-dd845a5094a7','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":27,\"order_number\":\"ORD-EBVO7SAO3O\",\"user_name\":\"prince Aman\",\"total\":1092.5937}',NULL,'2025-04-22 09:00:41','2025-04-22 09:00:41');
INSERT INTO `notifications` VALUES ('434626ee-c960-4d33-b185-eb6aa850c57e','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":18,\"order_number\":\"ORD-6P46MY2TRV\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:08:46','2025-04-17 07:08:46');
INSERT INTO `notifications` VALUES ('44ef1b32-3cc2-4b78-a9f2-d73887c25546','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":24,\"order_number\":\"ORD-6MZAIBNHI3\",\"user_name\":\"prince Aman\",\"total\":1816.1979000000001}',NULL,'2025-04-22 07:35:51','2025-04-22 07:35:51');
INSERT INTO `notifications` VALUES ('46491fa6-8632-4ea3-bfad-47bda537abd1','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":5,\"order_number\":\"ORD-OJUFCNPH0L\",\"user_name\":\"Naghman Ali\",\"total\":\"1500.99\"}','2025-04-15 12:44:54','2025-04-11 11:10:47','2025-04-15 12:44:54');
INSERT INTO `notifications` VALUES ('46b4742c-2672-4645-bfa7-70eb0852d85d','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":10,\"order_number\":\"ORD-P1EX5H6CC7\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 06:04:42','2025-04-17 06:04:42');
INSERT INTO `notifications` VALUES ('4afbe652-0bc9-49af-b704-baaadecc4653','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":15,\"order_number\":\"ORD-JOUXYSI93H\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:00:23','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('504d9083-1711-4ff6-818c-12c8bab49fcc','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":23,\"order_number\":\"ORD-QDM9YF5VLG\",\"user_name\":\"prince Aman\",\"total\":424.6979}',NULL,'2025-04-18 08:34:35','2025-04-18 08:34:35');
INSERT INTO `notifications` VALUES ('5b2e2e5f-f4bd-4b58-b283-737745c103f7','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":8,\"order_number\":\"ORD-GTKNHPC8TH\",\"user_name\":\"prince\",\"total\":\"1500.99\"}','2025-04-16 04:58:28','2025-04-15 19:13:41','2025-04-16 04:58:28');
INSERT INTO `notifications` VALUES ('5f1ea0ee-36c3-43b5-a5b4-6db8b54b0e8a','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":8,\"order_number\":\"ORD-GTKNHPC8TH\",\"user_name\":\"prince\",\"total\":\"1500.99\"}',NULL,'2025-04-15 19:13:41','2025-04-15 19:13:41');
INSERT INTO `notifications` VALUES ('614e4c37-ffe8-4c3e-9969-518b900dc06d','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":4,\"order_number\":\"ORD-9RTJSARIPZ\",\"user_name\":\"Naghman Ali\",\"total\":\"1500.99\"}',NULL,'2025-04-11 10:02:46','2025-04-11 10:02:46');
INSERT INTO `notifications` VALUES ('70ba14b7-f15a-4635-bac1-eebcecb4a239','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":12,\"order_number\":\"ORD-R9GHSD2NFS\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 06:27:30','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('7ba57aa5-0e76-4526-84d9-28891d1de118','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":6,\"order_number\":\"ORD-J2TTC0WQ8C\",\"user_name\":\"Abdul Muqeet\",\"total\":\"3001.98\"}','2025-04-15 12:44:54','2025-04-14 10:09:29','2025-04-15 12:44:54');
INSERT INTO `notifications` VALUES ('83110f06-f374-467a-bd97-ef2c5dbb7da8','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":11,\"order_number\":\"ORD-T1C2UQXFSI\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 06:05:30','2025-04-17 06:05:30');
INSERT INTO `notifications` VALUES ('85ea2350-2e1d-400a-bebd-50afe71c983e','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":17,\"order_number\":\"ORD-RMQBRCRSEL\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:05:25','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('8c1daa90-19e1-421e-a6c9-d50e863cdf38','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":24,\"order_number\":\"ORD-6MZAIBNHI3\",\"user_name\":\"prince Aman\",\"total\":1816.1979000000001}','2025-04-22 07:36:27','2025-04-22 07:35:51','2025-04-22 07:36:27');
INSERT INTO `notifications` VALUES ('a508cfb4-2029-4a12-834d-8c0d2cf5b87d','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":21,\"order_number\":\"ORD-3H3ZBCLJMO\",\"user_name\":\"prince Aman\",\"total\":2003.7479}','2025-04-18 06:29:07','2025-04-18 06:04:22','2025-04-18 06:29:07');
INSERT INTO `notifications` VALUES ('b257df0d-0e21-4406-a57f-85fa42db1441','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":14,\"order_number\":\"ORD-DVIGFS1OJX\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 06:56:50','2025-04-17 06:56:50');
INSERT INTO `notifications` VALUES ('b6607e6f-bc2d-49ad-892d-658ae4c6c7c8','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":4,\"order_number\":\"ORD-9RTJSARIPZ\",\"user_name\":\"Naghman Ali\",\"total\":\"1500.99\"}','2025-04-11 11:05:41','2025-04-11 10:02:46','2025-04-11 11:05:41');
INSERT INTO `notifications` VALUES ('b89d8de8-a34f-472b-ac4e-873cb7bb21f3','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":16,\"order_number\":\"ORD-IHANTS1BLT\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:03:28','2025-04-17 07:03:28');
INSERT INTO `notifications` VALUES ('ba6af9e5-6a67-435b-b973-76c3ed541cf4','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":19,\"order_number\":\"ORD-PLW1SNULQC\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:15:21','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('baf92d3e-c3b2-4f70-8078-dfa65d8a97a5','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":13,\"order_number\":\"ORD-HSBATZFBWQ\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 06:36:19','2025-04-17 06:36:19');
INSERT INTO `notifications` VALUES ('c3e19fe4-53f3-4708-ab53-7300a8490c19','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":26,\"order_number\":\"ORD-TNSHONLIHN\",\"user_name\":\"Super Admin\",\"total\":787.6979}',NULL,'2025-04-22 08:55:21','2025-04-22 08:55:21');
INSERT INTO `notifications` VALUES ('c55b3839-847e-4da4-9408-3370a700ae77','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":7,\"order_number\":\"ORD-5ES4LIUHS7\",\"user_name\":\"prince\",\"total\":\"1500.99\"}',NULL,'2025-04-15 12:50:09','2025-04-15 12:50:09');
INSERT INTO `notifications` VALUES ('ca346dc8-4628-46ee-bb0f-f0fe8c10b014','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":15,\"order_number\":\"ORD-JOUXYSI93H\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:00:24','2025-04-17 07:00:24');
INSERT INTO `notifications` VALUES ('d255206a-9e3e-4cc8-86c7-5ae67a642e97','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":22,\"order_number\":\"ORD-ZYCNU1ZSIZ\",\"user_name\":\"Javed Ur Rehman\",\"total\":5448.5937}','2025-04-18 06:40:06','2025-04-18 06:39:59','2025-04-18 06:40:06');
INSERT INTO `notifications` VALUES ('d53c1e90-d32b-4b98-a577-1d32a4e75354','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":9,\"order_number\":\"ORD-ZNNNNKH9M5\",\"user_name\":\"prince Aman\",\"total\":\"1250.99\"}','2025-04-18 04:36:29','2025-04-16 10:40:41','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('d90da5c6-d732-4ed5-83f8-8f175a17c076','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":20,\"order_number\":\"ORD-QHHZENKTRJ\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:19:58','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('ded1398f-5da2-448b-b4ad-bff6f0606d2a','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":5,\"order_number\":\"ORD-OJUFCNPH0L\",\"user_name\":\"Naghman Ali\",\"total\":\"1500.99\"}',NULL,'2025-04-11 11:10:47','2025-04-11 11:10:47');
INSERT INTO `notifications` VALUES ('df10a718-5a36-482a-b4f2-218298e7330a','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":18,\"order_number\":\"ORD-6P46MY2TRV\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:08:46','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('e133d995-1a2d-433f-880c-36b63865e897','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":20,\"order_number\":\"ORD-QHHZENKTRJ\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:19:58','2025-04-17 07:19:58');
INSERT INTO `notifications` VALUES ('e7cbdfc8-081f-4757-a2ca-d995e8a6979d','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":17,\"order_number\":\"ORD-RMQBRCRSEL\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:05:25','2025-04-17 07:05:25');
INSERT INTO `notifications` VALUES ('e80a02fd-2f02-4f39-b845-b482559a1c01','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":19,\"order_number\":\"ORD-PLW1SNULQC\",\"user_name\":\"prince Aman\",\"total\":null}',NULL,'2025-04-17 07:15:21','2025-04-17 07:15:21');
INSERT INTO `notifications` VALUES ('ee8f237f-cfed-401a-976d-cbdb6dd2d3ab','App\\Notifications\\NewOrderNotification','App\\Models\\User',2,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":9,\"order_number\":\"ORD-ZNNNNKH9M5\",\"user_name\":\"prince Aman\",\"total\":\"1250.99\"}',NULL,'2025-04-16 10:40:41','2025-04-16 10:40:41');
INSERT INTO `notifications` VALUES ('f05ed3f5-6dae-432e-9ab5-b29576993035','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":16,\"order_number\":\"ORD-IHANTS1BLT\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 07:03:28','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('f83f929f-2a4b-4602-87d4-9925c761e50f','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":10,\"order_number\":\"ORD-P1EX5H6CC7\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 06:04:42','2025-04-18 04:36:29');
INSERT INTO `notifications` VALUES ('f9d939ff-429a-4c5a-9e24-51c3ede8d374','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":23,\"order_number\":\"ORD-QDM9YF5VLG\",\"user_name\":\"prince Aman\",\"total\":424.6979}','2025-04-18 08:35:13','2025-04-18 08:34:35','2025-04-18 08:35:13');
INSERT INTO `notifications` VALUES ('fecd1735-b7ea-4c2d-9095-1789a290a0fe','App\\Notifications\\NewOrderNotification','App\\Models\\User',1,'{\"title\":\"Nouvelle commande re\\u00e7ue\",\"order_id\":14,\"order_number\":\"ORD-DVIGFS1OJX\",\"user_name\":\"prince Aman\",\"total\":null}','2025-04-18 04:36:29','2025-04-17 06:56:50','2025-04-18 04:36:29');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,10,'AirPods Pro 2',1500.99,1,'2025-04-11 09:40:44','2025-04-11 09:40:44');
INSERT INTO `order_items` VALUES (2,2,4,'Asus ROG Zephyrus G14',1250.99,3,'2025-04-11 09:46:20','2025-04-11 09:46:20');
INSERT INTO `order_items` VALUES (3,3,10,'AirPods Pro 2',1500.99,1,'2025-04-11 09:53:01','2025-04-11 09:53:01');
INSERT INTO `order_items` VALUES (4,4,10,'AirPods Pro 2',1500.99,1,'2025-04-11 10:02:43','2025-04-11 10:02:43');
INSERT INTO `order_items` VALUES (5,5,10,'AirPods Pro 2',1500.99,1,'2025-04-11 11:10:47','2025-04-11 11:10:47');
INSERT INTO `order_items` VALUES (6,6,10,'AirPods Pro 2',1500.99,2,'2025-04-14 10:09:15','2025-04-14 10:09:15');
INSERT INTO `order_items` VALUES (7,7,10,'AirPods Pro 2',1500.99,1,'2025-04-15 12:50:01','2025-04-15 12:50:01');
INSERT INTO `order_items` VALUES (8,8,10,'AirPods Pro 2',1500.99,1,'2025-04-15 19:13:38','2025-04-15 19:13:38');
INSERT INTO `order_items` VALUES (9,9,4,'Asus ROG Zephyrus G14',1250.99,1,'2025-04-16 10:40:25','2025-04-16 10:40:25');
INSERT INTO `order_items` VALUES (10,10,10,'AirPods Pro 2',1500.99,1,'2025-04-17 06:04:26','2025-04-17 06:04:26');
INSERT INTO `order_items` VALUES (11,11,10,'AirPods Pro 2',1500.99,1,'2025-04-17 06:05:30','2025-04-17 06:05:30');
INSERT INTO `order_items` VALUES (12,12,1,'iPhone 15 Pro Max 256GB',1499.00,1,'2025-04-17 06:27:29','2025-04-17 06:27:29');
INSERT INTO `order_items` VALUES (13,12,10,'AirPods Pro 2',1500.99,1,'2025-04-17 06:27:29','2025-04-17 06:27:29');
INSERT INTO `order_items` VALUES (14,13,10,'AirPods Pro 2',1500.99,1,'2025-04-17 06:36:19','2025-04-17 06:36:19');
INSERT INTO `order_items` VALUES (15,13,9,'Xbox Series X',350.99,1,'2025-04-17 06:36:19','2025-04-17 06:36:19');
INSERT INTO `order_items` VALUES (16,14,10,'AirPods Pro 2',1500.99,2,'2025-04-17 06:56:33','2025-04-17 06:56:33');
INSERT INTO `order_items` VALUES (17,15,9,'Xbox Series X',350.99,2,'2025-04-17 07:00:23','2025-04-17 07:00:23');
INSERT INTO `order_items` VALUES (18,16,9,'Xbox Series X',350.99,1,'2025-04-17 07:03:28','2025-04-17 07:03:28');
INSERT INTO `order_items` VALUES (19,17,6,'Apple Watch Ultra 2',350.99,1,'2025-04-17 07:05:25','2025-04-17 07:05:25');
INSERT INTO `order_items` VALUES (20,18,6,'Apple Watch Ultra 2',350.99,1,'2025-04-17 07:08:46','2025-04-17 07:08:46');
INSERT INTO `order_items` VALUES (21,19,8,'PlayStation 5 Slim',455.99,1,'2025-04-17 07:15:21','2025-04-17 07:15:21');
INSERT INTO `order_items` VALUES (22,20,6,'Apple Watch Ultra 2',350.99,1,'2025-04-17 07:19:56','2025-04-17 07:19:56');
INSERT INTO `order_items` VALUES (23,21,3,'MacBook Pro M3 14\"',1655.99,1,'2025-04-18 06:04:07','2025-04-18 06:04:07');
INSERT INTO `order_items` VALUES (24,22,10,'AirPods Pro 2',1500.99,3,'2025-04-18 06:39:52','2025-04-18 06:39:52');
INSERT INTO `order_items` VALUES (25,23,9,'Xbox Series X',350.99,1,'2025-04-18 08:34:31','2025-04-18 08:34:31');
INSERT INTO `order_items` VALUES (26,24,10,'AirPods Pro 2',1500.99,1,'2025-04-22 07:35:47','2025-04-22 07:35:47');
INSERT INTO `order_items` VALUES (27,25,8,'PlayStation 5 Slim',300.99,1,'2025-04-22 08:32:14','2025-04-22 08:32:14');
INSERT INTO `order_items` VALUES (28,26,5,'Sony WH-1000XM5',650.99,1,'2025-04-22 08:55:19','2025-04-22 08:55:19');
INSERT INTO `order_items` VALUES (29,27,8,'PlayStation 5 Slim',300.99,3,'2025-04-22 09:00:40','2025-04-22 09:00:40');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','processing','completed','declined') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `shipping_address_id` bigint unsigned NOT NULL,
  `billing_address_id` bigint unsigned NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`),
  CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-SVFQPURJ94',4,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',3,3,NULL,'2025-04-11 09:40:44','2025-04-11 09:40:44');
INSERT INTO `orders` VALUES (2,'ORD-XPQZHAYFUU',4,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',3,3,NULL,'2025-04-11 09:46:20','2025-04-11 09:46:20');
INSERT INTO `orders` VALUES (3,'ORD-VMED4VZROR',4,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',3,3,NULL,'2025-04-11 09:53:01','2025-04-11 09:53:01');
INSERT INTO `orders` VALUES (4,'ORD-9RTJSARIPZ',4,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',3,3,NULL,'2025-04-11 10:02:43','2025-04-11 10:02:43');
INSERT INTO `orders` VALUES (5,'ORD-OJUFCNPH0L',4,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',6,6,NULL,'2025-04-11 11:10:47','2025-04-11 11:10:47');
INSERT INTO `orders` VALUES (6,'ORD-J2TTC0WQ8C',3,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',7,7,NULL,'2025-04-14 10:09:15','2025-04-14 10:09:15');
INSERT INTO `orders` VALUES (7,'ORD-5ES4LIUHS7',6,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',8,8,NULL,'2025-04-15 12:50:01','2025-04-15 12:50:01');
INSERT INTO `orders` VALUES (8,'ORD-GTKNHPC8TH',6,0.00,0.00,0.00,0.00,0.00,'pending','card','pending',8,8,NULL,'2025-04-15 19:13:38','2025-04-15 19:13:38');
INSERT INTO `orders` VALUES (9,'ORD-ZNNNNKH9M5',6,0.00,0.00,0.00,0.00,0.00,'declined','card','cancelled',10,10,NULL,'2025-04-16 10:40:25','2025-04-18 05:41:34');
INSERT INTO `orders` VALUES (10,'ORD-P1EX5H6CC7',6,1500.99,315.21,5.00,0.00,1821.20,'pending','card','pending',10,10,NULL,'2025-04-17 06:04:26','2025-04-17 06:04:26');
INSERT INTO `orders` VALUES (11,'ORD-T1C2UQXFSI',6,1500.99,315.21,5.00,0.00,1821.20,'pending','card','pending',10,10,NULL,'2025-04-17 06:05:30','2025-04-17 06:05:30');
INSERT INTO `orders` VALUES (12,'ORD-R9GHSD2NFS',6,2999.99,630.00,5.00,0.00,3634.99,'pending','card','pending',10,10,NULL,'2025-04-17 06:27:29','2025-04-17 06:27:29');
INSERT INTO `orders` VALUES (13,'ORD-HSBATZFBWQ',6,1851.98,388.92,5.00,0.00,2245.90,'pending','card','pending',10,10,NULL,'2025-04-17 06:36:19','2025-04-17 06:36:19');
INSERT INTO `orders` VALUES (14,'ORD-DVIGFS1OJX',6,3001.98,630.42,5.00,0.00,3637.40,'pending','card','pending',10,10,NULL,'2025-04-17 06:56:33','2025-04-17 06:56:33');
INSERT INTO `orders` VALUES (15,'ORD-JOUXYSI93H',6,701.98,147.42,5.00,0.00,854.40,'pending','card','pending',10,10,NULL,'2025-04-17 07:00:23','2025-04-17 07:00:23');
INSERT INTO `orders` VALUES (16,'ORD-IHANTS1BLT',6,350.99,73.71,5.00,0.00,429.70,'pending','card','pending',10,10,NULL,'2025-04-17 07:03:28','2025-04-17 07:03:28');
INSERT INTO `orders` VALUES (17,'ORD-RMQBRCRSEL',6,350.99,73.71,5.00,0.00,429.70,'completed','card','paid',10,10,NULL,'2025-04-17 07:05:25','2025-04-18 06:51:48');
INSERT INTO `orders` VALUES (18,'ORD-6P46MY2TRV',6,350.99,73.71,0.00,0.00,424.70,'completed','card','paid',10,10,NULL,'2025-04-17 07:08:46','2025-04-18 05:25:13');
INSERT INTO `orders` VALUES (19,'ORD-PLW1SNULQC',6,455.99,95.76,0.00,0.00,551.75,'processing','card','pending',10,10,NULL,'2025-04-17 07:15:21','2025-04-18 05:24:29');
INSERT INTO `orders` VALUES (20,'ORD-QHHZENKTRJ',6,350.99,73.71,0.00,0.00,424.70,'completed','card','paid',10,10,NULL,'2025-04-17 07:19:56','2025-04-18 05:12:48');
INSERT INTO `orders` VALUES (21,'ORD-3H3ZBCLJMO',6,1655.99,347.76,0.00,0.00,2003.75,'completed','card','paid',10,10,NULL,'2025-04-18 06:04:07','2025-04-18 06:48:38');
INSERT INTO `orders` VALUES (22,'ORD-ZYCNU1ZSIZ',1,4502.97,945.62,0.00,0.00,5448.59,'completed','card','paid',14,14,NULL,'2025-04-18 06:39:52','2025-04-18 09:12:51');
INSERT INTO `orders` VALUES (23,'ORD-QDM9YF5VLG',6,350.99,73.71,0.00,0.00,424.70,'completed','card','paid',10,10,NULL,'2025-04-18 08:34:31','2025-04-18 08:36:28');
INSERT INTO `orders` VALUES (24,'ORD-6MZAIBNHI3',6,1500.99,315.21,0.00,0.00,1816.20,'processing','card','pending',10,10,'Le livreur peut me contacter sur ce numéro!!','2025-04-22 07:35:47','2025-04-22 07:37:05');
INSERT INTO `orders` VALUES (25,'ORD-VE7D47FKSH',6,300.99,63.21,0.00,0.00,364.20,'completed','card','paid',10,10,NULL,'2025-04-22 08:32:14','2025-04-22 08:33:39');
INSERT INTO `orders` VALUES (26,'ORD-TNSHONLIHN',1,650.99,136.71,0.00,0.00,787.70,'pending','card','pending',14,14,NULL,'2025-04-22 08:55:19','2025-04-22 08:55:19');
INSERT INTO `orders` VALUES (27,'ORD-EBVO7SAO3O',6,902.97,189.62,0.00,0.00,1092.59,'pending','card','pending',10,10,NULL,'2025-04-22 09:00:40','2025-04-22 09:00:40');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('amanprince005@gmail.com','$2y$12$oeEFR2jZ6lxW27U6UD0j9.I7Ig5403fXvJNmJgdb/eiUqRGlW..FG','2025-04-18 07:03:27');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'create-role','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (2,'edit-role','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (3,'delete-role','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (4,'create-user','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (5,'edit-user','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (6,'delete-user','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (7,'view-product','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (8,'create-product','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (9,'edit-product','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (10,'delete-product','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (11,'view-category','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (12,'create-category','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (13,'edit-category','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (14,'delete-category','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (15,'view-order','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (16,'process-order','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (17,'cancel-order','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (18,'view-customer','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (19,'edit-customer','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `permissions` VALUES (20,'access-reports','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'iPhone 15 Pro Max 256GB','L\'iPhone 15 Pro Max 256GB représente le summum de la technologie mobile d\'Apple. Conçu avec un design en titane léger et des bords contournés, il offre une prise en main confortable et une durabilité accrue. Son écran Super Retina XDR de 6,7 pouces avec la technologie ProMotion assure une expérience visuelle fluide avec des taux de rafraîchissement adaptatifs jusqu\'à 120 Hz. Sous le capot, la puce A17 Pro offre des performances de pointe, idéales pour les applications exigeantes et les jeux de nouvelle génération. Le nouveau bouton Action permet un accès rapide aux fonctionnalités essentielles, améliorant l\'ergonomie de l\'appareil. Côté photographie, le système de caméra avancé comprend un objectif principal de 48 MP, un ultra grand-angle de 12 MP et un téléobjectif de 12 MP avec un zoom optique 5x, permettant des prises de vue détaillées même à distance. De plus, l\'intégration du port USB-C facilite des transferts de données rapides et une compatibilité étendue avec divers accessoires.','2025-04-10 07:23:36','2025-04-17 06:27:29',NULL,1499.00,NULL,0,'products/fEsqk8m6ZHAYLcKKLIdj3FoFqJjMydHqouI633mv.webp','iphone-15-pro-max-256gb','L\'iPhone 15 Pro Max 256GB représente le summum de la technologie mobile d\'Apple. Conçu avec un design en titane léger et des bords contournés, il offre une prise en main confortable et une durabilité accrue. Son écran Super Retina XDR de 6,7 pouces avec la technologie ProMotion assure une expérience visuelle fluide avec des taux de rafraîchissement adaptatifs jusqu\'à 120 Hz. Sous le capot, la puce A17 Pro offre des performances de pointe, idéales pour les applications exigeantes et les jeux de nouvelle génération. Le nouveau bouton Action permet un accès rapide aux fonctionnalités essentielles, améliorant l\'ergonomie de l\'appareil. Côté photographie, le système de caméra avancé comprend un objectif principal de 48 MP, un ultra grand-angle de 12 MP et un téléobjectif de 12 MP avec un zoom optique 5x, permettant des prises de vue détaillées même à distance. De plus, l\'intégration du port USB-C facilite des transferts de données rapides et une compatibilité étendue avec divers accessoires.',1);
INSERT INTO `products` VALUES (2,'Samsung Galaxy S24 Ultra','Le Samsung Galaxy S24 Ultra est le fleuron de la gamme Galaxy, offrant une combinaison impressionnante de design et de performance. Son écran Dynamic AMOLED 2X de 6,8 pouces offre des couleurs éclatantes et une clarté exceptionnelle, idéal pour le streaming et les jeux. Le cadre en titane confère à l\'appareil une robustesse accrue tout en conservant une esthétique élégante. Sous le capot, le Galaxy S24 Ultra est équipé du processeur Snapdragon 8 Gen 3, garantissant une réactivité et une fluidité remarquables. Le système de caméra est particulièrement notable, avec un capteur principal de 200 MP qui capture des images d\'une netteté exceptionnelle, même en basse lumière. Les fonctionnalités d\'intelligence artificielle intégrées améliorent l\'expérience utilisateur, notamment avec la traduction en temps réel des appels et des capacités de retouche photo avancées.','2025-04-10 07:25:22','2025-04-11 06:09:52',2,1255.99,NULL,10,'products/murmWPZmnGlnOLPywQY1Y71UrhH1sRnCwnQLoHrq.jpg','samsung-galaxy-s24-ultra','Le Samsung Galaxy S24 Ultra est le fleuron de la gamme Galaxy, offrant une combinaison impressionnante de design et de performance. Son écran Dynamic AMOLED 2X de 6,8 pouces offre des couleurs éclatantes et une clarté exceptionnelle, idéal pour le streaming et les jeux. Le cadre en titane confère à l\'appareil une robustesse accrue tout en conservant une esthétique élégante. Sous le capot, le Galaxy S24 Ultra est équipé du processeur Snapdragon 8 Gen 3, garantissant une réactivité et une fluidité remarquables. Le système de caméra est particulièrement notable, avec un capteur principal de 200 MP qui capture des images d\'une netteté exceptionnelle, même en basse lumière. Les fonctionnalités d\'intelligence artificielle intégrées améliorent l\'expérience utilisateur, notamment avec la traduction en temps réel des appels et des capacités de retouche photo avancées.',1);
INSERT INTO `products` VALUES (3,'MacBook Pro M3 14\"','Le MacBook Pro 14 pouces équipé de la puce M3 Max est conçu pour les professionnels exigeants. Son écran Retina offre une résolution exceptionnelle, rendant chaque image et vidéo incroyablement détaillée. La puce M3 Max, dotée d\'un CPU à 8 cœurs et d\'un GPU à 10 cœurs, assure des performances de pointe pour les tâches les plus gourmandes en ressources, telles que le montage vidéo 4K, la modélisation 3D et le développement logiciel. Le MacBook Pro dispose également d\'une autonomie impressionnante, permettant de travailler toute la journée sans interruption. Les options de connectivité incluent plusieurs ports Thunderbolt, un port HDMI et un lecteur de carte SDXC, offrant une flexibilité maximale pour les professionnels.','2025-04-10 07:26:33','2025-04-18 06:04:07',3,1655.99,NULL,44,'products/fFVRYKhpOdzibjUiQfnvfGenbFwJl0WRuagsOCbF.webp','macbook-pro-m3-14','Le MacBook Pro 14 pouces équipé de la puce M3 Max est conçu pour les professionnels exigeants. Son écran Retina offre une résolution exceptionnelle, rendant chaque image et vidéo incroyablement détaillée. La puce M3 Max, dotée d\'un CPU à 8 cœurs et d\'un GPU à 10 cœurs, assure des performances de pointe pour les tâches les plus gourmandes en ressources, telles que le montage vidéo 4K, la modélisation 3D et le développement logiciel. Le MacBook Pro dispose également d\'une autonomie impressionnante, permettant de travailler toute la journée sans interruption. Les options de connectivité incluent plusieurs ports Thunderbolt, un port HDMI et un lecteur de carte SDXC, offrant une flexibilité maximale pour les professionnels.',1);
INSERT INTO `products` VALUES (4,'Asus ROG Zephyrus G14','L\'Asus ROG Zephyrus G14 est un ordinateur portable gaming qui allie puissance et portabilité. Équipé d\'un processeur AMD Ryzen 9 et d\'une carte graphique Radeon RX 6800S, il offre des performances exceptionnelles pour les jeux les plus récents et les applications gourmandes en ressources. Son écran de 14 pouces avec un taux de rafraîchissement élevé assure une expérience visuelle fluide et immersive. Le design compact et léger du Zephyrus G14 le rend facile à transporter, tandis que son système de refroidissement avancé maintient des performances optimales même lors de sessions de jeu prolongées. De plus, il dispose d\'un clavier rétroéclairé, d\'un son de haute qualité et d\'une autonomie de batterie respectable pour un ordinateur portable gaming.','2025-04-10 07:27:41','2025-04-11 06:08:41',3,1250.99,NULL,13,'products/cuqiGoh3TArgU4S8yxVu3jGylh2yEeO59wWoIjGy.jpg','asus-rog-zephyrus-g14','L\'Asus ROG Zephyrus G14 est un ordinateur portable gaming qui allie puissance et portabilité. Équipé d\'un processeur AMD Ryzen 9 et d\'une carte graphique Radeon RX 6800S, il offre des performances exceptionnelles pour les jeux les plus récents et les applications gourmandes en ressources. Son écran de 14 pouces avec un taux de rafraîchissement élevé assure une expérience visuelle fluide et immersive. Le design compact et léger du Zephyrus G14 le rend facile à transporter, tandis que son système de refroidissement avancé maintient des performances optimales même lors de sessions de jeu prolongées. De plus, il dispose d\'un clavier rétroéclairé, d\'un son de haute qualité et d\'une autonomie de batterie respectable pour un ordinateur portable gaming.',1);
INSERT INTO `products` VALUES (5,'Sony WH-1000XM5','Le Sony WH-1000XM5 est un casque audio haut de gamme conçu pour les audiophiles exigeants. Doté de la technologie de réduction de bruit active la plus avancée de Sony, il bloque efficacement les bruits extérieurs pour une immersion totale dans la musique. Son design élégant et léger, associé à des coussinets en mousse à mémoire de forme, offre un confort optimal même après plusieurs heures d’écoute. L’autonomie peut atteindre jusqu’à 30 heures, et seulement 3 minutes de charge offrent 3 heures d’écoute. Grâce aux 8 micros et au processeur V1, il assure également une excellente qualité d’appel. Compatible avec les assistants vocaux et l’app Sony Headphones Connect pour un réglage personnalisé.','2025-04-10 07:29:44','2025-04-22 08:55:19',1,785.99,650.99,9,'products/nLVSIe2fHFtBzyvvUNSFYo7HMeUnQjyXpEsxlQDW.jpg','sony-wh-1000xm5','Le Sony WH-1000XM5 est un casque audio haut de gamme conçu pour les audiophiles exigeants. Doté de la technologie de réduction de bruit active la plus avancée de Sony, il bloque efficacement les bruits extérieurs pour une immersion totale dans la musique. Son design élégant et léger, associé à des coussinets en mousse à mémoire de forme, offre un confort optimal même après plusieurs heures d’écoute. L’autonomie peut atteindre jusqu’à 30 heures, et seulement 3 minutes de charge offrent 3 heures d’écoute. Grâce aux 8 micros et au processeur V1, il assure également une excellente qualité d’appel. Compatible avec les assistants vocaux et l’app Sony Headphones Connect pour un réglage personnalisé.',1);
INSERT INTO `products` VALUES (6,'Apple Watch Ultra 2','L’Apple Watch Ultra 2 est la montre la plus robuste et la plus performante jamais conçue par Apple. Elle est dotée d’un boîtier en titane, d’un verre saphir et d’une étanchéité jusqu’à 100 mètres. L’écran Retina de 3 000 nits est le plus lumineux jamais vu sur une Apple Watch, idéal pour une visibilité optimale en plein soleil. Grâce à sa puce S9 SiP, elle permet des interactions plus fluides et un contrôle gestuel innovant. Son GPS double fréquence est ultra précis, parfait pour les randonneurs, alpinistes ou marins. Sa batterie offre jusqu’à 36 heures d\'autonomie en usage normal, et jusqu’à 72 heures en mode économie d\'énergie.','2025-04-10 07:30:52','2025-04-17 07:19:56',1,350.99,NULL,22,'products/grbOpJrv9LSvVLTnfR6i2z0CVfxhTEz5FGG1S1Re.jpg','apple-watch-ultra-2','L’Apple Watch Ultra 2 est la montre la plus robuste et la plus performante jamais conçue par Apple. Elle est dotée d’un boîtier en titane, d’un verre saphir et d’une étanchéité jusqu’à 100 mètres. L’écran Retina de 3 000 nits est le plus lumineux jamais vu sur une Apple Watch, idéal pour une visibilité optimale en plein soleil. Grâce à sa puce S9 SiP, elle permet des interactions plus fluides et un contrôle gestuel innovant. Son GPS double fréquence est ultra précis, parfait pour les randonneurs, alpinistes ou marins. Sa batterie offre jusqu’à 36 heures d\'autonomie en usage normal, et jusqu’à 72 heures en mode économie d\'énergie.',1);
INSERT INTO `products` VALUES (7,'DJI Mini 4 Pro Drone','Le DJI Mini 4 Pro est un drone ultra-léger (moins de 250g) qui embarque une caméra 4K HDR avec stabilisation sur 3 axes. Conçu pour capturer des images aériennes fluides et professionnelles, il dispose de la détection d’obstacles omnidirectionnelle, offrant une sécurité accrue lors des vols. Son autonomie atteint 34 minutes par batterie, extensible jusqu\'à 45 min avec l\'option batterie longue durée. Il prend en charge le suivi de sujet, les panoramas automatisés et la transmission vidéo jusqu’à 20 km en 1080p. Idéal pour les débutants comme les vidéastes confirmés.','2025-04-10 07:32:03','2025-04-11 06:06:35',1,1355.99,1000.99,15,'products/RpchIJU9fW9UbZ6uohOTFzZTJRuv50mcUKbQQ9uz.jpg','dji-mini-4-pro-drone','Le DJI Mini 4 Pro est un drone ultra-léger (moins de 250g) qui embarque une caméra 4K HDR avec stabilisation sur 3 axes. Conçu pour capturer des images aériennes fluides et professionnelles, il dispose de la détection d’obstacles omnidirectionnelle, offrant une sécurité accrue lors des vols. Son autonomie atteint 34 minutes par batterie, extensible jusqu\'à 45 min avec l\'option batterie longue durée. Il prend en charge le suivi de sujet, les panoramas automatisés et la transmission vidéo jusqu’à 20 km en 1080p. Idéal pour les débutants comme les vidéastes confirmés.',1);
INSERT INTO `products` VALUES (8,'PlayStation 5 Slim','La PlayStation 5 Slim offre toutes les performances de la PS5 dans un châssis plus compact et plus économe en énergie. Elle intègre un SSD ultra-rapide de 1 To pour des chargements quasi-instantanés, et prend en charge le ray tracing, les graphismes en 4K, ainsi que le retour haptique immersif via les manettes DualSense. Grâce à son design revisité, elle s’intègre facilement dans tous les salons sans compromis sur la puissance. Compatible avec des centaines de jeux PS5 et PS4, elle constitue le cœur d’un écosystème gaming moderne et fluide.','2025-04-10 07:33:01','2025-04-22 09:00:40',1,455.99,300.99,20,'products/TnYgiEknDs3CsSt1Q044trPdCUpzrDIz25FKZXcp.webp','playstation-5-slim','La PlayStation 5 Slim offre toutes les performances de la PS5 dans un châssis plus compact et plus économe en énergie. Elle intègre un SSD ultra-rapide de 1 To pour des chargements quasi-instantanés, et prend en charge le ray tracing, les graphismes en 4K, ainsi que le retour haptique immersif via les manettes DualSense. Grâce à son design revisité, elle s’intègre facilement dans tous les salons sans compromis sur la puissance. Compatible avec des centaines de jeux PS5 et PS4, elle constitue le cœur d’un écosystème gaming moderne et fluide.',1);
INSERT INTO `products` VALUES (9,'Xbox Series X','La Xbox Series X est la console la plus puissante jamais créée par Microsoft. Elle est dotée d’un processeur 8 cœurs custom Zen 2, d’un GPU RDNA 2 et de 12 TFLOPS de puissance graphique, permettant un rendu en 4K natif à 60 fps (jusqu’à 120 fps selon les jeux). Son SSD NVMe de 1 To offre des chargements ultra-rapides et une fluidité de jeu inégalée. Le Quick Resume permet de passer instantanément d’un jeu à l’autre. Elle est compatible avec tous les jeux Xbox One, ainsi que de nombreux jeux Xbox 360 et Xbox originaux.','2025-04-10 07:33:53','2025-04-22 09:04:08',1,450.00,350.99,15,'products/ofjztO1czvNIqgUGDmHbm1Zs0SLVMxF75qV32hhV.jpg','xbox-series-x','La Xbox Series X est la console la plus puissante jamais créée par Microsoft. Elle est dotée d’un processeur 8 cœurs custom Zen 2, d’un GPU RDNA 2 et de 12 TFLOPS de puissance graphique, permettant un rendu en 4K natif à 60 fps (jusqu’à 120 fps selon les jeux). Son SSD NVMe de 1 To offre des chargements ultra-rapides et une fluidité de jeu inégalée. Le Quick Resume permet de passer instantanément d’un jeu à l’autre. Elle est compatible avec tous les jeux Xbox One, ainsi que de nombreux jeux Xbox 360 et Xbox originaux.',1);
INSERT INTO `products` VALUES (10,'AirPods Pro 2','Les AirPods Pro 2 intègrent la puce H2 d’Apple pour une qualité sonore améliorée, une réduction active du bruit encore plus performante, et un mode transparence adaptatif qui s’ajuste automatiquement à l’environnement sonore. Le nouveau boîtier MagSafe dispose d’une fonction de localisation via l’app Localiser, d’un haut-parleur intégré et d’un crochet pour dragonne. L’autonomie a été améliorée jusqu’à 6 heures d’écoute continue, et jusqu’à 30 heures avec le boîtier. Compatibles avec l’audio spatial personnalisé et les commandes tactiles sur les tiges.','2025-04-10 07:34:51','2025-04-22 09:03:42',1,1500.99,NULL,15,'products/81aWOa2n9vuyVED0HAyD1f1L5XhwrlKRqgir02s5.jpg','airpods-pro-2','Les AirPods Pro 2 intègrent la puce H2 d’Apple pour une qualité sonore améliorée, une réduction active du bruit encore plus performante, et un mode transparence adaptatif qui s’ajuste automatiquement à l’environnement sonore. Le nouveau boîtier MagSafe dispose d’une fonction de localisation via l’app Localiser, d’un haut-parleur intégré et d’un crochet pour dragonne. L’autonomie a été améliorée jusqu’à 6 heures d’écoute continue, et jusqu’à 30 heures avec le boîtier. Compatibles avec l’audio spatial personnalisé et les commandes tactiles sur les tiges.',1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (4,2);
INSERT INTO `role_has_permissions` VALUES (5,2);
INSERT INTO `role_has_permissions` VALUES (6,2);
INSERT INTO `role_has_permissions` VALUES (8,2);
INSERT INTO `role_has_permissions` VALUES (9,2);
INSERT INTO `role_has_permissions` VALUES (10,2);
INSERT INTO `role_has_permissions` VALUES (11,2);
INSERT INTO `role_has_permissions` VALUES (12,2);
INSERT INTO `role_has_permissions` VALUES (13,2);
INSERT INTO `role_has_permissions` VALUES (14,2);
INSERT INTO `role_has_permissions` VALUES (15,2);
INSERT INTO `role_has_permissions` VALUES (16,2);
INSERT INTO `role_has_permissions` VALUES (17,2);
INSERT INTO `role_has_permissions` VALUES (18,2);
INSERT INTO `role_has_permissions` VALUES (19,2);
INSERT INTO `role_has_permissions` VALUES (20,2);
INSERT INTO `role_has_permissions` VALUES (8,3);
INSERT INTO `role_has_permissions` VALUES (9,3);
INSERT INTO `role_has_permissions` VALUES (10,3);
INSERT INTO `role_has_permissions` VALUES (11,3);
INSERT INTO `role_has_permissions` VALUES (12,3);
INSERT INTO `role_has_permissions` VALUES (13,3);
INSERT INTO `role_has_permissions` VALUES (7,4);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `roles` VALUES (2,'Admin','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `roles` VALUES (3,'Product Manager','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
INSERT INTO `roles` VALUES (4,'User','web','2025-04-09 09:08:43','2025-04-09 09:08:43');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('77tQTg38Uao4z4FusGmxVRkeBnBYha28ieR5fFaa',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYnl5R3RiTXkyZGNISDhyS1V3MUZsR01QaVNtV29DY2N4RlVlaDJSTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzNDU5NDY7fX0=',1745345992);
INSERT INTO `sessions` VALUES ('8FvhgOwNj3zu6QjpXjfL69QODmSqWojWm94wpNmS',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOFNsdUJQQUNmekgya1VDc1pZeVMyRVRCT3JkZjRYeGZqUWlwVWNZRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=',1745346348);
INSERT INTO `sessions` VALUES ('dezucl10RJORcxeyiEw2NzPXZAjpw0ERxZLlKVmK',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiT0ZuOFQzR0UyVG9SNEk1Znh3NDVIQ2FOMmZsZXBlMXQ3bW9nbzRnVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVja291dC9zdWNjZXNzLzI1Ijt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzQ1MzE1NDgxO319',1745317936);
INSERT INTO `sessions` VALUES ('G3uezbH9gJt0d1MshzKUwGv7vLDCC7BxlhLqtD0d',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoid2xHaDRyNlJUR21KTVhvQ05DQ2hBM0ljRTJ1S3FEd1ZCUU9EaVJBTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzMTQ0NjI7fX0=',1745314484);
INSERT INTO `sessions` VALUES ('gyQdPV08Jki4QJI8ZRqqzX4aLprUGmBdU9Wqv7hz',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoibmRRbDduTFhIOWUxNnl0cmFKeUFmNGpJMUlrMXJHM1J2UjU1Z0MyciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzNDYyOTI7fX0=',1745346342);
INSERT INTO `sessions` VALUES ('m0FGj6EfVjfTQy2o6cL4m1UjJj5N4G9BAWAAJskD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHhiRWVCTDdqSjY0V3BrRUhUSUtOMzRaYWVMUmNnZzVHUFpVbEQyUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=',1745319856);
INSERT INTO `sessions` VALUES ('Nan9P3Cjs5HNP86RZmQnclGsAvLYUM2PQqZUUp1O',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRGxrbjRvdThXcFBsdXNqY2JRWTBDQXRwakRwSXUzcDNCYk1EY3N1aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzMTQ1ODc7fX0=',1745315074);
INSERT INTO `sessions` VALUES ('OVKYbmSry2SEt6rIVNAylqeB6HZTyGF3CS3aPKKe',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoieGw0NmxyNWRST2pKcGE1SmcxTm54WWZQVFREeXczRmhFNEVDWmRDbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVja291dC9zdWNjZXNzLzI0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzMTQ0OTc7fX0=',1745314552);
INSERT INTO `sessions` VALUES ('UIiqNT6G4sVaDMV25dbyec90Ad2XOaT0ZgHYkgIl',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicnZnU0lMaTFkSWR4ZTdnYk4wb0hJWEtvbTFYR0pFUDAyR2x6b1dvOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzMTk2NzE7fX0=',1745319854);
INSERT INTO `sessions` VALUES ('XPAzGflNakStxCAw4GUrhxpFYmHdt6IGQv1zjSuF',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaHF0U3ZwOW5rdFBlWklXaUIyclJSbnRJVHFlV0xTUDlnRXBaM3ZqSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0L3BsYXlzdGF0aW9uLTUtc2xpbSI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc0NTMxOTQ2Nzt9fQ==',1745319657);
INSERT INTO `sessions` VALUES ('xSwhRcRXz3QJkrHYuukBEbgfN1T8LiPPofOkg0lL',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicWhrYkRtM2IyeXF5SDR2NnNhUzl1NGVPNXRGUXkzOUpoRVNWV2lYWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzMTc5NzU7fX0=',1745319448);
INSERT INTO `sessions` VALUES ('ZzZ08TO7EfIAtykP6bRrSdx3AKKlTRyqY9yQ8qDg',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicmJSRjVHa2JSbG1YSm5NckNVYkIzUllBdFBrT0RQTUNieW1DMktqRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDUzNDYwMDg7fX0=',1745346036);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','princeaman635@gmail.com','0467620878',NULL,'$2y$12$Xi9d1WGKV3eG.bERoFvZ0e6S6H2AchPUTdWqTOmTAUudUmoYcxlzO','u3076ONOpaMBWaKvHQmrnVfFz3fH1TZ4Tpf09Lc1ywF63lhiYpDFcU4SkxBs','2025-04-09 09:08:44','2025-04-18 07:07:12');
INSERT INTO `users` VALUES (2,'Syed Ahsan Kamal','ahsan@allphptricks.com',NULL,NULL,'$2y$12$2dbxR179jEWqY5E0rf01f.gdzcb5IpNqqBfUCNoWw2i3XRNS1xa1.',NULL,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `users` VALUES (3,'Abdul Muqeet','muqeet@allphptricks.com',NULL,NULL,'$2y$12$8hTggqgX0nb3grrUdSGZsu8p5IVbq2De5xkH.XyGcB0jYUUO2pVCu',NULL,'2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `users` VALUES (4,'Naghman Ali','naghman@allphptricks.com',NULL,NULL,'$2y$12$suMVE14/SvBzToEFb834Eu46NiY7lNUmdwCcmJX5g7L0piVUx8SDe','5ow62bYVvZByFQMOFudU5isTBRWif9ZRLSKWYOqPMuiai4MGU3zc79Hd5vkp','2025-04-09 09:08:44','2025-04-09 09:08:44');
INSERT INTO `users` VALUES (5,'User','user@gmail.com',NULL,NULL,'$2y$12$PWlwwE3IS6I.F36p.t17O.JXQoJVnpgEc.MOX2PBAS5UMDS1ngkga',NULL,'2025-04-15 12:45:36','2025-04-15 12:45:36');
INSERT INTO `users` VALUES (6,'prince Aman','amanprince005@gmail.com','0467620878',NULL,'$2y$12$fWOW5Ec4u9yYvSecF1n6Y.T8npsvNdqT4xrNDYXO1kfdIsHXWxV9q',NULL,'2025-04-15 12:49:29','2025-04-15 19:20:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_items_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlist_items_product_id_foreign` (`product_id`),
  CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
INSERT INTO `wishlist_items` VALUES (14,6,10,'2025-04-16 06:58:48','2025-04-16 06:58:48');
INSERT INTO `wishlist_items` VALUES (15,1,10,'2025-04-18 06:39:21','2025-04-18 06:39:21');
INSERT INTO `wishlist_items` VALUES (17,1,9,'2025-04-22 09:01:57','2025-04-22 09:01:57');
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

