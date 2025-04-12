CREATE DATABASE  IF NOT EXISTS `handymantotal` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `handymantotal`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: handymantotal
-- ------------------------------------------------------
-- Server version	8.0.37

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
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `Id_Cliente` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  `Direccion` varchar(45) NOT NULL,
  `Telefono` varchar(45) NOT NULL,
  `Correo_electronico` varchar(45) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`Id_Cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (2,'hector','Panama','507 62800542','hector01@gmail.com','1234abcd'),(3,'pedro','Panama','507 62800543','paty01@gmail.com','12345abcd'),(4,'juan','calle 22  casa193R','7128847','Wh@gmail.com','123'),(5,'camilo','calle 22  casa193R','67128847','Whitney@gmail.com','123456'),(6,'jose','calle 33 casa 167','739303836','juan@gmail.com','1234567'),(7,'pablo','CALLE44 CASA 150','7635393763','MIAMI@GMAIL.COM','ABCD'),(8,'diego','CALLE44 CASA 150','7635393763','MIAMI@GMAIL.COM','ABCD'),(9,'pedro perez','calle 25 casa 30','23345667767','hkjd@gmail.com','jegyerr'),(10,'omar diaz','heleno manzur','7749874894','helman@gmail.com','98765'),(11,'hugo baena','calle 22 casa11','76353673','hg@gmail.com','00123'),(12,'hector billete','cas de la moneda','5555588899','money@gmail.com','7849489'),(14,'pablo Paramo','el bosque','64777387373','pablo@gmail.com','22723838'),(15,'pablo','el bosqu','7843747','pablo@gmail.com','3677'),(16,'Henry Garzon','SENA bogota','5555588899','hgrz@gmail.com','********'),(17,'luciana quitian','cra 8  #9-67, Bogota','3508907654','luciana@gmail.com','90'),(19,'Andres Lopez','cra. 5 numero 32 1','320987654','lopez5@gmail.com','andres123'),(24,'sarita','bogota','685757','sarita@gmail.com','hhgv'),(25,'Alfonso Mateus','cra 73 c n 34 32 ','685757675757','mateus@gmail.com','jgkutu76'),(26,'Raul Gonzales','cra4 n 87 6','6532573572','raul@gmail.com','jwfwjWHSK'),(27,'Gabriela Quitian','cra 49 n 87 65','32456780100','gabriela@gmail.com','dgfhdfhaw'),(28,'Prueba','Calle 123','123456789','prueba@email.com','12345'),(30,'leandro','bogota','123456','leandro@gmail.com','$2y$10$XJ0kI66YD7HEh064s1z3WOGVCWJLUIgeXnHP5xFCCkHKBgqNBjqfy'),(31,'mariela rojas','la belleza','345678','mariela@gmail.com','$2y$10$tDpVhch7PBfcuxjs.o8cxebYbZkv69IPApLNcb2Cc4l70iHKnxRCm'),(32,'Belkis Medina Sarmiento','La Belleza Santander','3508358974','belkyz.yms@gmail.com','$2y$10$JX2jH9repXOVDiLYLaC5e.yUykT1M9gc6xCb1K6SsBkOuKEU.lYwy');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contratista`
--

DROP TABLE IF EXISTS `contratista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contratista` (
  `Id_Contratista` int NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `Especialidad` varchar(30) NOT NULL,
  `Telefono` varchar(12) NOT NULL,
  `Correo_electronico` varchar(50) NOT NULL,
  `Tipo_servicio` varchar(45) NOT NULL,
  `Contrasena` varchar(45) NOT NULL,
  PRIMARY KEY (`Id_Contratista`),
  CONSTRAINT `FK_contratista_Id_contratista INT` FOREIGN KEY (`Id_Contratista`) REFERENCES `zona` (`Id_zona`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contratista`
--

LOCK TABLES `contratista` WRITE;
/*!40000 ALTER TABLE `contratista` DISABLE KEYS */;
/*!40000 ALTER TABLE `contratista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `Id_servicios` int NOT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Tipo_servicio` varchar(45) NOT NULL,
  PRIMARY KEY (`Id_servicios`),
  CONSTRAINT `contratista_id_contrtista int` FOREIGN KEY (`Id_servicios`) REFERENCES `contratista` (`Id_Contratista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sesiones`
--

DROP TABLE IF EXISTS `sesiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sesiones` (
  `Id_Sesion` int NOT NULL AUTO_INCREMENT,
  `Id_Cliente` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_Sesion`),
  KEY `Id_Cliente` (`Id_Cliente`),
  CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`Id_Cliente`) REFERENCES `cliente` (`Id_Cliente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
INSERT INTO `sesiones` VALUES (1,31,'1a8071be9c38f490ff494ec3e51d00fa7ed8a88af464584abfa07083348a9fa3','2025-03-22 03:06:08'),(2,32,'5b82546f6e99fff6ee1e6832b75f691fb811bde143bf69dfebb81ed3b79abf88','2025-03-25 16:10:21');
/*!40000 ALTER TABLE `sesiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitud_servicios`
--

DROP TABLE IF EXISTS `solicitud_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitud_servicios` (
  `Id_solicitud_servicios` int NOT NULL AUTO_INCREMENT,
  `Fecha_ inicio` date NOT NULL,
  `Fecha_finalizacion` date NOT NULL,
  `Estado` varchar(45) NOT NULL,
  PRIMARY KEY (`Id_solicitud_servicios`),
  CONSTRAINT `FK_Cliente_id_Cliente INT` FOREIGN KEY (`Id_solicitud_servicios`) REFERENCES `cliente` (`Id_Cliente`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `servicios_id_servicios_contratista INT` FOREIGN KEY (`Id_solicitud_servicios`) REFERENCES `servicios` (`Id_servicios`),
  CONSTRAINT `servicios_id_serviciosINT` FOREIGN KEY (`Id_solicitud_servicios`) REFERENCES `servicios` (`Id_servicios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitud_servicios`
--

LOCK TABLES `solicitud_servicios` WRITE;
/*!40000 ALTER TABLE `solicitud_servicios` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitud_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicacion`
--

DROP TABLE IF EXISTS `ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicacion` (
  `Id_ubicacion` int NOT NULL,
  `Direccion_exacta` varchar(100) DEFAULT NULL,
  KEY `zona-_id_zona INT_idx` (`Id_ubicacion`),
  CONSTRAINT `zona-_id_zona INT` FOREIGN KEY (`Id_ubicacion`) REFERENCES `zona` (`Id_zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicacion`
--

LOCK TABLES `ubicacion` WRITE;
/*!40000 ALTER TABLE `ubicacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `ubicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona`
--

DROP TABLE IF EXISTS `zona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona` (
  `Id_zona` int NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Tipo` varchar(12) NOT NULL,
  `Densidad_poblacion` int NOT NULL DEFAULT '1000',
  PRIMARY KEY (`Id_zona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona`
--

LOCK TABLES `zona` WRITE;
/*!40000 ALTER TABLE `zona` DISABLE KEYS */;
/*!40000 ALTER TABLE `zona` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-25 11:12:31
