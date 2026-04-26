-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: all_age_care
-- ------------------------------------------------------
-- Server version	8.4.5

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `customers_id` int DEFAULT NULL,
  `caregivers_id` int DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(8,2) DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `customers_id` (`customers_id`),
  KEY `caregivers_id` (`caregivers_id`),
  KEY `service_id` (`service_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`customers_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`caregivers_id`) REFERENCES `caregivers` (`caregivers_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL,
  CONSTRAINT `chk_booking_times` CHECK ((`end_time` > `start_time`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caregivers`
--

DROP TABLE IF EXISTS `caregivers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caregivers` (
  `caregivers_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `price_per_hour` decimal(10,2) DEFAULT NULL,
  `bio` text,
  `national_id` char(14) NOT NULL,
  `national_id_image` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `experience_years` int DEFAULT '0',
  `availability_status` enum('available','busy','off') DEFAULT 'available',
  `location_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`caregivers_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `national_id` (`national_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `caregivers_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `chk_caregiver_nat_id_len` CHECK ((length(`national_id`) = 14))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caregivers`
--

LOCK TABLES `caregivers` WRITE;
/*!40000 ALTER TABLE `caregivers` DISABLE KEYS */;
INSERT INTO `caregivers` VALUES (6,'Mohaned','mohanedehab42@gmail.com','+201007545813','$2y$10$tXgoYq2BjGnE4D94RV4q9uLAK98cVcRNFq2PqeXWOVOc8ii9MJLA2',45.00,'Experienced','12345678910123','uploads/id_photos/69edfbbf6892a_1777204159.jpg','uploads/profile_pics/69edfbbf68281_1777204159.jpg',2,'available',1,'2026-04-26 11:49:19','2026-04-26 11:49:19',0);
/*!40000 ALTER TABLE `caregivers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customers_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text,
  `password_hash` varchar(255) NOT NULL,
  `location_id` int DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `national_id` char(14) NOT NULL,
  `national_id_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`customers_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `national_id` (`national_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE SET NULL,
  CONSTRAINT `chk_customer_nat_id_len` CHECK ((length(`national_id`) = 14))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (3,'7ambolla','mohanedehab44@gmail.com','+201007545813','I love family','$2y$10$tdHO9Ymeq0mWeNV9bKVpDuCDHiQiUcYsExAFGaetjZhJeyDzc1vte',1,'uploads/profile_pics/69ee0269ef8e6_1777205865.jpg','12345678912348','uploads/id_photos/69ee0269f0212_1777205865.jpg','2026-04-26 12:17:46','2026-04-26 12:17:46',0);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `location` (
  `location_id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'Alexandria','');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `customers_id` int DEFAULT NULL,
  `caregivers_id` int DEFAULT NULL,
  `review_text` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `booking_id` (`booking_id`),
  KEY `customers_id` (`customers_id`),
  KEY `caregivers_id` (`caregivers_id`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`customers_id`) ON DELETE SET NULL,
  CONSTRAINT `review_ibfk_3` FOREIGN KEY (`caregivers_id`) REFERENCES `caregivers` (`caregivers_id`) ON DELETE SET NULL,
  CONSTRAINT `review_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) NOT NULL,
  `base_price_per_hour` decimal(8,2) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_type` enum('customer','caregiver') NOT NULL,
  `customer_id` int DEFAULT NULL,
  `caregiver_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `customer_id` (`customer_id`),
  KEY `caregiver_id` (`caregiver_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customers_id`) ON DELETE CASCADE,
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`caregiver_id`) REFERENCES `caregivers` (`caregivers_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,'mohanedehab42@gmail.com','$2y$10$tXgoYq2BjGnE4D94RV4q9uLAK98cVcRNFq2PqeXWOVOc8ii9MJLA2','caregiver',NULL,6,'2026-04-26 11:49:19'),(9,'mohanedehab44@gmail.com','$2y$10$tdHO9Ymeq0mWeNV9bKVpDuCDHiQiUcYsExAFGaetjZhJeyDzc1vte','customer',3,NULL,'2026-04-26 12:17:46');
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

-- Dump completed on 2026-04-26 16:46:41
