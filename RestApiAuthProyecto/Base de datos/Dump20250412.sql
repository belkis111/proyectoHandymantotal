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
-- Table structure for table `administrador`
--

DROP TABLE IF EXISTS `administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrador` (
  `Id_admin` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Direccion` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Telefono` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Correo_electronico` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Contrasena` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`Id_admin`),
  UNIQUE KEY `Correo_electronico` (`Correo_electronico`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrador`
--

LOCK TABLES `administrador` WRITE;
/*!40000 ALTER TABLE `administrador` DISABLE KEYS */;
INSERT INTO `administrador` VALUES (1,'Albeiro Tellez','Bogota','3109876554','alveiro@gmail.com','$2y$10$nX5hMJX8CZNc/tpE9zSwX.LMaEQfJ1uWDB6Ps/wiCsJIwTsekTG.m'),(2,'Isabela Umaña','bogota','3214567899','isabela@gmaill.com','$2y$10$RBXNS8fW8GbwpQpAM3POk.IV5.2h5fjOn3EbaRAtCCmvLZirtwEtO'),(3,'Antonio Torres','bogota','3245677777','antonio@gmail.com','$2y$10$rABPpbepnev3J5daSKirx.b/xmz4GFGtLvLnhcSGIStx1HBzupObW'),(4,'Miguel Morales','bogota','3214567700','miguel@gmail.com','$2y$10$9TFUkawcOBfaQGYM4ZYdRe0kvooXoIsKDqT1DGqyvZNPxGBaSfmxG'),(5,'Alberto Salazar','Bogota','3134542294','alberto@gmail.com','$2y$10$ALOmRs4tTy9Jy.Fl7dSYBeMilSARilZxKAR2SEoIC.zyvjdRy2M3y'),(6,'Yurany Medina','la belleza','3142456712','yurany@gmail.com','$2y$10$HR3fAgzvrB9zw5y20zAE0.AipGXaTYfqTsHJHvYKWghvX74jlSgQS'),(7,'Yuli Camacho','bogota','3111234567','yuli@gmail.com','$2y$10$KeZexw94Wd0kf2AsBB5F/.C2GntPmXiTqn5VmlyxWbqGMCo920qlC'),(10,'Edward Gomez','Bogota','3174542294','edward@gmail.com','$2y$10$O5qt1PhUlDycOSRGhwy2bewqTKT38bs6xSaC.7s/.MJ6YuXtWUFWK');
/*!40000 ALTER TABLE `administrador` ENABLE KEYS */;
UNLOCK TABLES;

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
  `Correo_electronico` varchar(50) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`Id_Cliente`),
  UNIQUE KEY `Correo_electronico` (`Correo_electronico`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (31,'mariela rojas','la belleza','345678','mariela@gmail.com','$2y$10$tDpVhch7PBfcuxjs.o8cxebYbZkv69IPApLNcb2Cc4l70iHKnxRCm'),(32,'Belkis Medina Sarmiento','La Belleza Santander','3508358974','belkyz.yms@gmail.com','$2y$10$JX2jH9repXOVDiLYLaC5e.yUykT1M9gc6xCb1K6SsBkOuKEU.lYwy'),(33,'Karen Medina','cra 801 n 79 35','3214567890','karen@gmail.com','$2y$10$bQ2cJw9hdwFpoIX1jpGxNegMLwTXuefqVlmCiZp7qUtd9SCSzN8Om'),(34,'sonia lopez','bogota','3132456789','sonia@gmail.com','$2y$10$uK7OYYWji7dbFKHKKU04/.0pX2/vXjPq.z5Ky74CSBM8a2NkrVHoS'),(49,'luna Medina','la belleza','32145678922','luna@gmail.com','$2y$10$RHVszZgbgtxTnRiPcTvTE.TsnxMRUt5xde/zE0vp57WOGBbKRxfDu'),(50,'carmen sanchez','bogota','35098763344','carmen@gmail.com','$2y$10$msydgWKvC4bvYgVYgU/Y.eGx52ezvKu/dYug1urnpBZEa5P91ef/2'),(51,'carol pineda','bogota','5334342','carol@gmail.com','$2y$10$bSjoMG1cssv62r7xJDXBSuSnPI303egXU3.stKQojSolm.YbHicqK'),(53,'sandra ','cra 9 a n 34 21','312789065333','sandra@gmail.com','$2y$10$sfOe8hk6T4THJ.HNIaVf7ObkOrXNdZZw6t.hZX/pisImbivZRUGKm'),(54,'manuela gomez','bogota','35098765432','manuela@gmail.com','$2y$10$wL/jwqzwwnj89EANOHZyh.XLyOl5GccIw9/cO8TxJyrpTyMl8y42C'),(55,'Camila Romero ','cra 87 a n 14 21','3134356788','camila@gmail.com','$2y$10$VX3dSFm3RHUWoe2TftdJxusTX4tzdakO8Zq8AdG9e8jIpwYK42tLq'),(58,'cesar gomez','bogota','4357890','cesar@gmail.com','$2y$10$MMYGbZNxtl0Tl.BuglgieOQ6CwzButdaHiqDBSWi/b6xZ8AD/1V2y'),(60,'Carolina Quiñones ','cra 12 a n 14 21','432122123','carolina@gmail.com','$2y$10$0/pXL8RQsj4MP8FF1dNj6.pufOcnG2/rmBn.ZZ0Ja9qgFUoU5abIm'),(62,'Vanesa Ramirez ','cra 54 a n 6 21','3215467788','vanesa@gmail.com','$2y$10$88PYPIezQdJJTeE3ztiE1uj/7a3w6EFWM7XRwbiUnVM.LpROJGVoG'),(63,'Eliseo','Rojas','3214567788','eliseo@gmail.com','$2y$10$REdc1HmZllkWyt6lOJqYJO0NJ14O8XrWS8JCtuyJ4dqUJh.SvoFdm'),(64,'Santiago Jimenez ','cra 22 a n 89 11','31323411122','santiago@gmail.com','$2y$10$l40UfcdVAB401CpbnID/iuvFLnTxyUeVMjEJiPoq3M6uEclqbReqm'),(73,'sara','bogota','685757','sara@gmail.com','$2y$10$PyTHLX.FGtUumwLVUTP1A.PitJTxxNvLDYy6F1DrdEEWI7cdAt/nO'),(76,'pablito','bogota','321456677','pablito@gmail.com','$2y$10$VtaQ.s4as.4vjGwyg2gKl.2UmEnEkSfGwCGFDQkMm14PuoyqXxxh.'),(78,'Uriel Alfonso Medina Rueda','bogota','3106778738','uriel@gmail.com','$2y$10$a35ixqefOmh8sxs6.6oZduv5IXVQyq48p9eTAHx1uhzg/agIxM.Gu'),(79,'rosa','bogota','685757','rosa@gmail.com','$2y$10$0EKwhIjO2WbwIQKWYDfPCO9EWxrPwjuLET.fu8lW.doUdT0kuVFY.'),(80,'Lucia Pinto','Bogota','3134567878','lucia@gmail.com','$2y$10$llF5uzZ18zBQXyyQEW/ujO/sMd89om0Z3S6ohSZs2DUolSeqwnH9K'),(83,'ana','bogota','321321321','ana@gmail.com','$2y$10$Wsd3YXOhdVSR.t2lBT0yHOyySjKFkX8HX8tzVqk/Nmv4hP2OiDYSi'),(84,'Rodolfo','bogota','42424','rodolfo@gmail.com','$2y$10$HvELuLBuEoWC62ebZvSjjub.mXiyDvhUT0c6cfrnAZ66FZ3YpS47O'),(85,'Pedro Mateus','la belleza','685757','pedro@gmail.com','$2y$10$9stDuEoeFZjcrFFNz5HfP.hG/XaQ8jh0ncOtTa588U1aOdlhKmc.e'),(86,'Juan Pérez','Calle 123, Ciudad','3001234567','juanperez@example.com','$2y$10$POTILzOOBOdX22rlT6159eogl.EKQ.Nb09RFKF9spWNLUZrhVTWsy'),(87,'alirio gonzales','la belleza','3509876540','alirio@gmail.com','$2y$10$oN3/sQmOtrS/4m1DvDuCVOwkNl.aZcHhfIBhgtI2zeN1NCpqo8If2'),(89,'anita','bogota','321','anita@gmail.com','$2y$10$lDhJyJ3LYAa45vWTqgYSme0SGa95qipSWKCty4aB9J6BYJaA9ZvJ.'),(93,'orfeli tellez','bogota','685757','orfeli@gmail.com','$2y$10$tY18B4OBM4MStGACHLK1VuoaPIixmFs2Lbf6us6g4Htq0vs975qZO'),(97,'Carlos Pérez','Calle 123','3001234567','carlos@example.com','$2y$10$uPE6py6L2fv4GCuPQMr8A.H1dR1z0Xjv.NBG1UzCgK1qnwHbIqh.K'),(99,'Aurora Benavidez','bogota','3214556543','aurora@gmail.com','aurora'),(101,'Alejandro Rojas','bogota','3214543211','alejandro@gmail.com','$2y$10$kQQN5K5OXfyLkeVvl2l9meDYgtiN.BXxrxcnU57cYhfyGdUu22P6W'),(106,'Yeimi Gaona','Calle 43 n 76 56','3132156788','yeimi@gmail.com','$2y$10$muH5dd95k9l1X/Bjf1kcL.s8bqNWRI3wiVF4sTpOGGQvI4264PJMO'),(107,'Flor Tellez','bogota','345654321','flor@gmail.com','$2y$10$jVJCCc3xCvCXqLQt1/N5pu8RPVzKP996ZELnc/FE.QQTKwCvUsV6C');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contratista`
--

DROP TABLE IF EXISTS `contratista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contratista` (
  `Id_contratista` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(60) NOT NULL,
  `Direccion` varchar(45) NOT NULL,
  `Telefono` varchar(12) NOT NULL,
  `Correo_electronico` varchar(50) NOT NULL,
  `Especialidad` varchar(30) NOT NULL,
  `Contrasena` varchar(250) NOT NULL,
  PRIMARY KEY (`Id_contratista`),
  UNIQUE KEY `Correo_electronico` (`Correo_electronico`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contratista`
--

LOCK TABLES `contratista` WRITE;
/*!40000 ALTER TABLE `contratista` DISABLE KEYS */;
INSERT INTO `contratista` VALUES (16,'SOFIA PEÑA','bogota','7857565','sofia@gmaill.com','Carpintero','$2y$10$bzQMgxRXtD5ntr2XkCplouy.migMxXHg4W0PXzfKYVNpwEVy/ARVW');
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
  PRIMARY KEY (`Id_servicios`)
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
  `Id_Usuario` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `TipoUsuario` enum('cliente','contratista','administrador') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Id_Sesion`),
  KEY `Id_Cliente` (`Id_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sesiones`
--

LOCK TABLES `sesiones` WRITE;
/*!40000 ALTER TABLE `sesiones` DISABLE KEYS */;
INSERT INTO `sesiones` VALUES (1,31,'1a8071be9c38f490ff494ec3e51d00fa7ed8a88af464584abfa07083348a9fa3','2025-03-22 03:06:08','cliente'),(2,32,'5b82546f6e99fff6ee1e6832b75f691fb811bde143bf69dfebb81ed3b79abf88','2025-03-25 16:10:21','cliente'),(3,32,'fc5bec9ba58d879a37620f1ae8fc0659efb89208dc97196e6716920e262de5dd','2025-03-25 17:02:00','cliente'),(4,32,'000c99dc6260723e8224ca427fdfb5d0aaf86ce539b6c13b21cb65e3e8bb6f21','2025-03-25 19:03:31','cliente'),(5,32,'f2bc6554fbd9e4b5d41293fc2914c2a0d45cd7b9126af161122212e2f1a62f4c','2025-03-25 20:07:46','cliente'),(6,34,'4aa6d91236ae9fd4a2d6941b48908910bbd252af4a83cf3d94968b728477b35f','2025-03-25 20:08:54','cliente'),(7,32,'555887ff84a56f8fc9c06df036c10be4883b8ef5f15c965a7a431ad82ece2f1b','2025-03-25 22:10:19','cliente'),(9,32,'99db9221299f0a0f375a5ea4d05bafef4ebfb2c05405b1dd84d547c3166f239d','2025-03-27 13:39:14','cliente'),(10,32,'bf5cece3c4b4931676764b93fd7f9aabcc85d0fd6807893d046f6671e39d6f22','2025-03-27 13:39:27','cliente'),(11,49,'b1e1d32c78f39a3976a3be8d2f47dd355080a3ce3142f533d78317e720ed6d64','2025-03-27 13:40:24','cliente'),(12,32,'dcba1fe94e07a537ce0bd43a7f48f2ef9ef0c760623bf053a577b1209f12f340','2025-03-27 13:43:33','cliente'),(13,50,'f1e19c4e7d435fdb02577dfbe4154832422e3931807453de547de700aa6e2602','2025-03-27 13:47:58','cliente'),(14,32,'1a619cf8b30a6e24405d8a58633ab0c7c6a4c13fad53724a112ce6b056ec4da4','2025-03-27 14:30:37','cliente'),(15,51,'7799b08978428da73f551f82fd048976e7a52ad0f7311ead0cbbd96bbf1ebb5b','2025-03-27 14:31:37','cliente'),(16,32,'35369a43f4df155b2abc398eadaf655899b44df42be201628fdebfbd18577bd3','2025-03-27 14:43:26','cliente'),(17,32,'0c1a6e7dbe7511cc8bc7d734d47435e1aad734451065a409523f862586521540','2025-03-27 15:14:35','cliente'),(18,33,'4464607cff66bce1b44b863e0f74624a9ceae5343684a9eb227e0413ce4def9f','2025-03-27 15:15:28','cliente'),(20,32,'87061da9e70007cf744221f5034fb11fe467d0aa9127fd9440add5056598fa14','2025-03-27 21:10:52','cliente'),(21,32,'6e894c2c7c8061204df3c354cb846576af7dcef53c71115f7ca79a4f494fde26','2025-03-27 21:11:42','cliente'),(22,32,'d044c2ac7fc8a144679e99bbc783960ee9150f49baff889a7ece0b2971125e2f','2025-03-27 21:41:03','cliente'),(23,54,'1c6da4cab9ffce6d55f6258458f0733f89844384111088d3fc2b88f74568b26b','2025-03-27 21:41:54','cliente'),(24,55,'dae517476ec583d1b03e843e00adb07e57f5975093d2ee9555878168000c3656','2025-03-27 21:44:10','cliente'),(25,31,'bd127d754eb9f7af1aa73dc630cfe0cca8bb84a826e0c3b97e10d7e55dcce062','2025-03-27 21:53:03','cliente'),(26,55,'a0e3af5275bf92ca2ed2076c08961ccfa30922f61ef10729e235f102d5f12515','2025-03-27 21:58:01','cliente'),(27,32,'cd0e9bf5ccf54e3a7e11676892ed2ce4104fd1fb144c13fc3fa5e57dc15fb029','2025-03-27 21:58:12','cliente'),(28,32,'1ccf21f9c9a1c5bb4aaf4829c861618b5213728ff612fe155fa182460cb7ca42','2025-03-27 22:00:00','cliente'),(29,32,'10e46b164db3ebedb101cce5b40bbdff5743cba559c4583b701a63d84bce702f','2025-03-27 22:00:54','cliente'),(30,58,'200401799c0bf4bd1af8c33060317a7c18d824ce5b78cc946d1095891c8c1faa','2025-03-27 22:02:23','cliente'),(31,32,'e431fef81f5c97e827b333ea6fbd5831457c87d5a8f0ea99a79c56ec3fe84556','2025-03-27 23:09:39','cliente'),(32,55,'6f3c04475de46a749f382154042a41d15e111572ab3b35fdeab591089b2ddb3d','2025-03-27 23:35:37','cliente'),(33,55,'9f3c9a1703a6a427ee91cfc2d6499a0d74fc61cc6d40b87af438df4e92eb2201','2025-03-27 23:35:39','cliente'),(34,32,'53780e9eb471beb60b353a2b0b3c0566396c56fbfd3e299cfba11da12f460bb7','2025-03-27 23:36:15','cliente'),(35,32,'6923fe57ad1fa009bed730ed5e7c61dad51b699fefedf177cd2d0c170a229b12','2025-03-27 23:36:36','cliente'),(36,62,'c1d3c51a686466953564a4418bedd87f82362addcbbbaf4095278cd861c1c2fa','2025-03-28 01:35:53','cliente'),(37,32,'b3af381c8bd42e568ad7febdfeba864ff24841df6b4fd0a92375c073f21cfa19','2025-03-30 13:16:47','cliente'),(38,63,'4b2848c34223928387774b46eeeba3feb8922e5e8d31b3956f7ad173fb356f36','2025-03-30 13:17:31','cliente'),(39,32,'002014d419683a2c35d39bd5be9e4f5ed12cd29a8da0612b1e33102fe8ac275b','2025-03-30 13:18:20','cliente'),(40,64,'b63dd4ad73b191a67bf3a168175760c4d6a76c9d7763906ee40fd89a50ea4546','2025-03-30 13:19:04','cliente'),(41,64,'f7e2ff914676bf3ab99a3fcf1d93de794d7ea172e6bb9143fad8d6155a9533ca','2025-03-30 16:01:54','cliente'),(42,64,'131ebb8ffeb9df987e5a5e2f8cf85a486861930fffc6a0ae5a9067e29b143907','2025-03-30 17:13:59','cliente'),(43,53,'d69209d2553ce28e5cc574efe39660a802bb7b29ebc61f2cad971b1c8f4bb262','2025-03-30 17:14:38','cliente'),(46,53,'f3edbd68a0477ebab0d0157105b8dbedd687407712ad9d05dc3a05f3ec0ed216','2025-03-30 23:01:07','cliente'),(47,78,'89926cf583e437e0093f6a9819cefa760ae73e0a2b0c22f00d41aecfbf853766','2025-03-31 19:49:10','cliente'),(48,32,'05d78230e60f07689ccbe06a88d798470aeb4fb53bc7f38ca983ed9e9395746f','2025-03-31 21:38:48','cliente'),(49,80,'38fde7ee416881368a48d233f8d8ad06dea5fe720e863d85b05eb33bc352ee5c','2025-03-31 21:39:20','cliente'),(50,32,'6464423ac157ffc14606c1743f7ef0abbf391475f7a787e41282011e05999b08','2025-03-31 22:03:31','cliente'),(51,32,'eba574da4e3d81bf52a5de08127e4d7990c38190b5b9a7da6deeea74a52670bc','2025-03-31 22:25:43','cliente'),(52,32,'dbde21bbc59608504393c266e930abe851981ef8b22bfee74657a3bbeb512e83','2025-03-31 22:27:22','cliente'),(53,32,'f2fc72af202e68c6f499c7638f483be4b59250ad42b1e35980a3f2832190f97e','2025-03-31 22:57:38','cliente'),(54,83,'6f8f6a18b2351306c1b9a1c9ebcc51065512470b6a585b930311b10f14bdf75c','2025-03-31 22:58:21','cliente'),(55,32,'5d6d3733fdc202b14a9fa4c84114bb0746bc2aef6c69c877aaf3854fcaf8b3a7','2025-03-31 23:18:26','cliente'),(56,87,'12bfac2fb1aa183bf359ebe16dcb70fb48d235390d3c739387798f565013eab8','2025-03-31 23:21:48','cliente'),(57,32,'39ac6edc72a75348d3739d51228a9b4baac8fcd7a4f613815ca4631420dc22e9','2025-04-01 13:59:49','cliente'),(58,89,'d925acc16ab986ce7e5956da5453353beccdb1140f5517dc1715900eb133c139','2025-04-01 14:00:54','cliente'),(59,90,'a72815c4cfd9eae0ea67cbb25f7d357f8dc7790664476efa55da30362a71524d','2025-04-01 14:10:03','cliente'),(60,53,'053afb2f0a54d51b32d67565c9eae8f26807009b84e53710c89e7cd5c38c6fb9','2025-04-01 14:19:23','cliente'),(61,32,'09aec541ab327a8aceb70cd6b059e13ea1b2e94c8f71c04c1613caa1367673dc','2025-04-01 14:27:01','cliente'),(62,53,'b9e03e2b1e7207e4fdbb385b7b39c7fc0d4dc4a1fc8b3c6397651412dd9a4786','2025-04-01 15:47:49','cliente'),(63,32,'8649a4ccec199ab48641f3fb3c1c8774cc8c78d256ac010ae475fae082757319','2025-04-01 15:48:10','cliente'),(64,32,'a8f823716e75166bed36ed30904b02c06a2d10d5988e896765bc23081d8e0fb9','2025-04-01 15:56:31','cliente'),(65,32,'f62860d10f0ecbf35a0df7b72036c2ee17370f3e464d1109f9a95653866851b3','2025-04-01 16:42:54','cliente'),(66,32,'1687fd360aa6fd2c071bd2fb73a2a6ef8b003fb33b671f47a74af5a487d6596b','2025-04-01 19:13:55','cliente'),(67,53,'952b08685fe18c96bd502d460d27b9ebe5fcb2f6e5faaaae7124f18a27057d94','2025-04-01 19:21:58','cliente'),(68,32,'0845fb6ea744a9442a3e2272c3bb2e626593bedde2f4c17920f5ecdd06ab9e43','2025-04-01 20:26:25','cliente'),(69,32,'116ca63a4fba82430a0850ed207b20d6ca0caab306d401abb0a300e5269460af','2025-04-01 20:57:35','cliente'),(71,32,'cf16c2a46a8cfca29ef128c049441cbccc54db190884b991c0922ba34f2ea50d','2025-04-01 21:23:29','cliente'),(72,32,'12f0adbb9cb59d09251999a8b4af0a901457c7d829a26024615ee1191d6eea06','2025-04-01 21:24:36','cliente'),(73,32,'59be1de0ed18ad10d447bd713537597dd2101fbd8b2f43e5a39ccd1b9f32ed1f','2025-04-01 21:28:40','cliente'),(74,32,'9d430136eeaca25c17109b3b17489cf70b6a1654884b21ecdc09902d7a3787c6','2025-04-01 22:59:27','cliente'),(75,4,'93b84deef8677b16c72f4e10d08ce052e97c71c919d0d0f7cd05884282b523cb','2025-04-01 22:59:45','cliente'),(76,32,'618d45c613b78ae6d3a51025ebb60548830f1fb484df93794bccfc73b416b889','2025-04-01 22:59:55','cliente'),(77,32,'d50ffdf349bf0575a9aecb85148d7ab24e834a543e290c2f2fc7476799d41653','2025-04-01 23:00:05','cliente'),(78,32,'5af7c752b1c35b29be86ea49e9dab74d35d8d888bd8599129c0d084f86919150','2025-04-01 23:03:55','cliente'),(79,4,'c0592c1fbd661d19209ca72761a1cdc4f4d5923a7b4ebef19aaad779e903023b','2025-04-01 23:04:22','cliente'),(80,32,'02bb2d483cec36875ce344bdd295a98aba2a805ef4e32d1526cc93cc266278e1','2025-04-01 23:31:09','cliente'),(81,4,'e87b4d633dba75249f1d499192112f1f051aa6cf9567126c25ecd148283934f8','2025-04-01 23:31:18','contratista'),(82,4,'764a9380f5f3a046d0425338a6ebe93bafd947a2939d9213e6e7f623c90cc7dc','2025-04-01 23:31:38','contratista'),(83,32,'b6b432d31019671fe0da3ef18f56c8ad9542f2a0b69329a2f2ace5e4ab828d9c','2025-04-01 23:36:02','cliente'),(84,32,'3a2ef2eb66437a8dd527d1009aeb630f2624659ec976ae729338d4948fb58e0b','2025-04-01 23:42:48','cliente'),(85,4,'7a5574f245dc42e9cd39d492d82f003136a86d526bece01842fa3fb42380b7b4','2025-04-01 23:44:32','contratista'),(86,32,'dc1338f2732f141ce92f0273f07940231c1891d5a11f00b30c67e9dc52e7eb3d','2025-04-01 23:50:08','cliente'),(87,4,'1c0bbbd01ce3cc82741a5bffc9cdf2cbf98f3bb71ca7b1a52661a0025a3c9c98','2025-04-01 23:50:34','contratista'),(88,32,'3510aacd933e5773f30828dcc0a841639c5815569b4deece58a07dd34a6df33c','2025-04-01 23:57:50','cliente'),(89,4,'435c3dceaea4a48ae83b34de308e261557286b20e2817380e6b824f2d6c9381c','2025-04-02 00:02:02','contratista'),(90,4,'f0ab2806a6247387977a51d08846e1573053c64f24580b013af6a63b1a47231f','2025-04-02 00:02:06','contratista'),(91,32,'8b57bbf5bcc971efe74991656011efab657a1fb7d5746c51c16ff45c3c15cad1','2025-04-02 00:14:21','cliente'),(92,4,'7a2e3b07321f5760e2f330a76a26d4e2f27d483e3b6449c04300c3b6e15a5ad3','2025-04-02 00:14:50','contratista'),(93,4,'066747fb89199cb64df3dcc0c71a7e1fbe66e0e5a80180c81bf3708e3b078472','2025-04-02 00:16:49','contratista'),(94,5,'b96685687c0834301b690bd81f2fa82ca8abb6858ed5a787ea1e72a74cef4216','2025-04-02 00:18:18','contratista'),(95,32,'4149ffe74d71aacbef17474707571072d7b8cde9d180269058ed6b4ae794d3d6','2025-04-02 19:21:50','cliente'),(96,4,'71e8ff9e1fd33a8dc0bd51055ab806aba9a666390bef465750e7169a9250c360','2025-04-02 19:24:06','contratista'),(97,101,'7313fb8c47cdb0aa5932708084b8e6268ac261d3bc389668533c2472438c4b0b','2025-04-02 21:48:53','cliente'),(98,6,'79b13023a9516df4bcc86ea833b84afd3a45e964cc9037cda2039fdcd3696039','2025-04-02 21:50:07','contratista'),(102,1,'0dfa3af8221eeafc9f5a43d5cdc0b7b30f2ef96808eff14690f995b4799876ca','2025-04-02 23:45:35','administrador'),(103,1,'c62cde611b56373af0d68d170d208d7dbb0d1c09432921142bbc0ae70f932879','2025-04-02 23:46:33','administrador'),(104,4,'2f24da7b6542848faf0dd4972b5c770fb3a254f7e0b64d4d39270524740c7fe6','2025-04-02 23:54:27','administrador'),(105,4,'9623027fccd1d2ce6eb7d23d58ab0480fc2f3dc0bcb7722985179dbd7c44c2da','2025-04-03 00:27:41','administrador'),(106,2,'f0429756662e7711694b424db31bb528466d82041734512de2c8339355c7efce','2025-04-03 13:16:21','administrador'),(107,1,'c3b5587200e789188a22569f50ab1d50e9d3d68656e4e71be0eef63df7b6de92','2025-04-03 17:13:58','administrador'),(108,32,'40cc1e5a440ad6d9a8e888260aac87f9d98f08cb1ef512973cf7bf622df63c49','2025-04-03 17:15:12','cliente'),(109,6,'0ce858443bae0a60b2c390e254200879a17316117b39b06b6dcceffedaaf525f','2025-04-03 17:16:13','contratista'),(110,107,'c773f799f89e5e236ad95cce070f0714009e2fb39327c650effbe9fa77e4f962','2025-04-03 19:55:51','cliente'),(111,101,'647ac04687e9b29f05d3916935f5eca56cd2ab3c80150c6d8be8cce9fa70e3c1','2025-04-03 19:56:45','cliente'),(112,2,'2e63505bc6c4cca11b5a05f1ff5eb5180edf9d532fe7f5fd4ea00680651fd77c','2025-04-03 19:59:34','administrador'),(113,4,'8b8f3b1dc7daf4e35fed8732ee30e318e3c7f14fdaa9c167cf4331bbf3db4a81','2025-04-03 19:59:58','contratista'),(114,10,'6a920c8042fccf9c0ea6587ec6b56894b98ba375f0c10b4a67bbed4e4992fb16','2025-04-03 20:04:42','contratista'),(115,6,'ff8e58ba225b7e906fb8d3657454310bd9859e4cc90b41f4f2a7ef11dfd86cfc','2025-04-03 20:05:57','administrador'),(116,32,'817fad7d0fa635f546e56c2dde47a4e324c4880d5669b4f40aa3dc8cb13b84e4','2025-04-03 20:23:18','cliente'),(117,10,'0d3930d17bc11f0560ae7af273d15030179d90e0ca00a4d268e55d16d5f8ac9e','2025-04-03 20:24:28','contratista'),(118,5,'0c762493ac6de3131dba299ead24c31d53e82e60ba50e0cbe042c66d219c0382','2025-04-03 20:25:04','administrador'),(119,7,'7d6754d4ca721d4432fd05b9904e940f8f1f12ca1c12f18f8794b93bf5237d68','2025-04-03 20:26:26','administrador'),(120,6,'a8347e156363bec53c48a9f143dbad1b900cfd62476afb5675dcbee560588aec','2025-04-04 00:07:05','contratista'),(121,32,'e237daf4508859f15551f3de12239b40194e689474c2b2e50aa5a39bcb1d6850','2025-04-04 00:07:11','cliente'),(122,1,'f8becc7bfd2edbbb8850463d17c6ec8e099302c019958c6fed256b867a47e7fe','2025-04-04 00:07:26','administrador'),(123,1,'c655436763f939c8420c82dae67b530c7d42582170f6cbffd55b2f5749f23047','2025-04-04 13:43:03','administrador'),(124,32,'18dacb2d2a02ef01d32a471455087377566d3e49dfb32d1f10af3c3d45f20cbe','2025-04-04 13:45:30','cliente'),(125,6,'61156c410d405e4a1f175b051f595e6472b373cad6f4f161a4015923428b37d4','2025-04-04 13:45:35','contratista'),(126,32,'4083694c9966de73ffb134d7b15d4e897513955f56edb2c14adbc8b814a56857','2025-04-04 13:46:53','cliente'),(127,10,'e2b89fab93138c2c0940d6ef4443b7ec140ca98662aacc21e8e33199d6cac37f','2025-04-04 13:47:09','contratista'),(128,32,'d452f16ccd1473732b47b53282748f4374420663bed8ec9cc02129f1a498e80e','2025-04-04 15:17:56','cliente'),(129,2,'5dc82b1001f9cb39dd9f167329329108a50111332d2dd37b7251f44ffd848a54','2025-04-08 14:15:33','administrador'),(130,2,'2d16ac7f88102bac3d032e9e5b8259d1d757637e23fc88064602feef0f1e635f','2025-04-08 14:38:02','administrador'),(131,32,'a347197f6bb3bc564bb7ce21c290979c220c1bea9c717465d3ef5cb952197545','2025-04-09 13:07:17','cliente'),(132,2,'de8a792b3fd469cfe893403e7b9a2c71fc4806e4f7946972d554ea16cc6f21ab','2025-04-09 13:07:43','administrador'),(133,2,'d6e469ee8ed6c528a899dfa6505b906a1fd62327f6d5dad681afd368da61efca','2025-04-09 13:54:08','administrador'),(134,2,'24e10e4cc224cdc472ee09b244c484a5d6c14860368813610377e9dd62b9b291','2025-04-09 17:14:50','administrador'),(135,32,'fd8d4b97af1d0dd75b548fb05f4e6168ec0f186fe18588c53dcf7226a7ca5932','2025-04-09 18:56:58','cliente'),(136,2,'94a402d73b8419f60328189b73d030684d708736ec7270d616b7a939d0a067bc','2025-04-09 18:57:19','administrador'),(137,2,'b8784eaa6b7840b3479b214c1c490b5dbd501fc1c89595a0364ff8fc07862874','2025-04-09 19:37:59','administrador'),(138,2,'5ff411329e0db798a07d1d25ca3528a679e089bc40b87dbce658dfe571fb2582','2025-04-10 14:14:06','administrador'),(139,2,'12c47c3bd4d21c078c18257b337e74bc5429a8623eb871410ea969ffa3cd5c80','2025-04-10 14:27:41','administrador'),(140,2,'f335e5d767cf1dd4669400a22be3cab2c1e9e6d610b0255ad8aeb7a17344f01b','2025-04-10 14:36:35','administrador'),(141,32,'1b52a1317d8be57c34d23bd6d22120c682bb44c1dfcb0254c71b0f2b31e676a4','2025-04-10 14:40:31','cliente'),(142,2,'c3e992b2c25cda8dc71c63aae7049b47bb2a9deb3ee26e5a6f68fc4cc3b833d7','2025-04-10 14:41:44','administrador'),(143,2,'274386e5ebd50649a898cb84219dc9790f9ca20f830d9a46232d7e1feb377999','2025-04-10 14:50:20','administrador'),(144,2,'f9ca70936e762cb81d2892d226dd08a196f6ed6a50368202b5283756f47dbae9','2025-04-10 15:15:31','administrador'),(145,32,'cf22348f5d476dc0e6251aeb05e43d7f73d686e6a4f67b3455080cf8e7a349af','2025-04-10 15:15:50','cliente'),(146,2,'4333f8690bfc3040f189dbb7c4ef91db5f21cb17f2cf61925c52de5e2bdad7bb','2025-04-10 15:16:04','administrador'),(147,2,'57f71c5a49f9eedb1c6fbbc6b7d0b18ddab51cf25d097a08bf0fe62ff28add8d','2025-04-10 15:16:38','administrador'),(148,2,'b25b2212c3539e2cdd1a42fa0606b80d5137165ab99d40630cd832cf955e2239','2025-04-10 15:24:20','administrador'),(149,2,'72323e84c067bd81e208831e1e8eb31a7d73b07f0e88fff276564ce80d332a8e','2025-04-10 15:28:04','administrador'),(150,2,'ffe258cca3fe5ed7d945fd64b465a1773cbb8850f604b643c8bcdc483af2e659','2025-04-10 15:28:30','administrador'),(151,2,'f725928b52af80c002d5e00094a758230ce4cff889a963eaf1201fdd3537ca85','2025-04-10 15:28:41','administrador'),(152,2,'68e38a8b34adb45f972db138c7101f908caad053efe924ee4ea93b12774ee57d','2025-04-10 15:30:56','administrador'),(153,2,'f62cc537cf28b318423dd081f9bbbe2a48cad51a3e0ce1f59bf5b08d578ef8fc','2025-04-10 15:31:29','administrador'),(154,2,'a77b62371b82f0c50f0d3bf6b52917b5212463ca062f4a29256d6f40d4220abc','2025-04-10 15:45:51','administrador'),(155,2,'bf403b382dceec62d20ebb9169404055011c92f9b09a739022786324d547f8a1','2025-04-10 15:46:20','administrador'),(156,2,'06645044c48134a322089135bc53be19398e574b75717fb97bbc08ad4e083beb','2025-04-10 15:46:50','administrador'),(157,32,'f6357cb7e8c7e99f091070165486b2c4e14510939e7b3a36e633a6783f50362c','2025-04-10 15:47:26','cliente'),(158,2,'30213e708ea33891e605b0e25be92a71fdb822ad8c3a216f0627a4eb4658b499','2025-04-10 15:47:38','administrador'),(159,2,'3db5e97bd259c0e6a7b474176917ac3d48f393f6e78a241eede24344ceeb55f6','2025-04-10 15:48:35','administrador'),(160,2,'c3f223dbda7da423501e8596ddb3f9d52a8bb8a9472305d75b7fa0317b93aeb5','2025-04-10 15:50:19','administrador'),(161,2,'614ad3d65dd75c3f02983de1717a7abfce93c15cf5fef9e93733947c5bda95ad','2025-04-10 15:51:28','administrador'),(162,2,'2d1995572b9b2899939e8d67e348edc66a3b39564933cd62636a5174e881ec6d','2025-04-10 15:56:37','administrador'),(163,2,'331072a3296816c7980c2bcaac73eaa3aac2eb1e16befdf9b8377662656b5c0a','2025-04-10 16:01:08','administrador'),(164,2,'7a086510b5066e4ecdc6fb59f137bd062fe6b47e03c3b7c7bd73749332e2241c','2025-04-10 16:02:10','administrador'),(165,2,'4d43316c1ebd5c929c74c8cf0328994eb7e00bd9d0da399491b0c431a0e67963','2025-04-10 16:18:18','administrador'),(166,2,'d228f659be8076865b2a5176554404f71e34b75d60b1b9b6cdfaa4013a2cf839','2025-04-10 16:21:17','administrador'),(167,2,'b4f43475f3f2cbc877b3abdb7fb19df87f5631b484f0adda762f572e5b8779e2','2025-04-10 16:21:45','administrador'),(168,2,'574ba9725928499641086d0550b18f075ceba4bd3b66fc02f76d1ad98c90551a','2025-04-10 16:22:01','administrador'),(169,2,'d32b20fd5bf69d047272dbf34a9bff4bb7ca57ac5322c9fa22767984665dce67','2025-04-10 16:22:12','administrador'),(170,2,'d2dce0868b8a38eb0aa5c8c9c619fc011b3bd72e65d3d6058bd5d3fcf4049630','2025-04-10 16:30:35','administrador'),(171,2,'0e7047cc724cfe08955d16c1bab45d798b59df6134576b788261eac735a40c90','2025-04-10 16:38:47','administrador'),(172,2,'e3f8551747f31a422a44e1d4fd0813cfb08f69c12a7b99bbd6e6e1f7aec47646','2025-04-10 16:39:22','administrador'),(173,2,'fc17d649b8afc1840908f30d22d3ba9cef4a15788831f4f9242a6a2039ae11dc','2025-04-10 16:39:56','administrador'),(174,2,'3611211bd65617b619f2162567d342135d926185f8fc72962c41c9b0f878bb04','2025-04-10 16:48:15','administrador'),(175,2,'0a8056efd590a5a1a6d396f2337d21bdb47db58c5f981eba7b7110089975366e','2025-04-10 16:48:40','administrador'),(176,2,'f51e238a9d920f2350e01a79739b7a9769d19b49bcbf1bee5e4d688e5e3de1a0','2025-04-10 17:04:31','administrador'),(177,2,'8c2e203647b51f070e45f191acf6a1d9a2b4ffc8309a1ea9ca1c7e339a9e44a1','2025-04-10 17:04:50','administrador'),(178,2,'7f08c302a4fbe515227bc80f945d90c97c9d5e6e62c19a131de7b461313c919e','2025-04-10 17:12:58','administrador'),(179,2,'39e6c0ebbe9ce77573bb992c31cbeccbf117c083f2536982d315068fd8937e5b','2025-04-10 17:13:38','administrador'),(180,2,'5222ca589dbef586cde2929049bbdb162ac997c052581b7be77caff23bb05a43','2025-04-10 17:25:26','administrador'),(181,2,'d80714a40ff5a09bc748d2095ea5a3d80191f3d4167230edb96865df6f542a7e','2025-04-10 17:27:14','administrador'),(182,2,'c7a23ac870deb21d51d561964eb284e83bbe8400fe8ff7aeb3e8da09d0bc2e1a','2025-04-10 17:28:28','administrador'),(183,2,'f2ae986b04fc8a0590d5c0faa5638cc87c4eca458797916839e7956df41bdec0','2025-04-10 17:33:56','administrador'),(184,2,'713f25592187fb62146f9b175d477c9b28724904b9c5f990ef07976ab527a51f','2025-04-10 17:37:55','administrador'),(185,2,'5607d77b25f71f7d5abb957c7556a9c21ef85f7369fa9077e1ff18dc709c8640','2025-04-10 17:42:02','administrador'),(186,2,'9f73b71c36a10415bf143cb711c5a3e7b760c84b52d7dedc3d853a0a65bfb018','2025-04-10 17:56:34','administrador'),(187,2,'ed83e7e5e397f5985b9633f89312f9075d2f33588b89f36e4510938aff22a3c5','2025-04-10 17:57:58','administrador'),(188,2,'c0b66abc4e72371a97dbe8e3815c8e3af86693477ca8412209847305b48caae1','2025-04-10 18:54:31','administrador'),(189,2,'42b83361575172a267feaf7b0d1c0a2a058367314f01476c8a2065a3ae3ff2bf','2025-04-10 18:57:01','administrador'),(190,2,'d938e41f8b969f3e80ccc9cf29f239bdc8356f653f57a8138bf58755bed8413f','2025-04-10 18:58:24','administrador'),(191,2,'c6577e261eb6c38c6e2823f2c3bc23fa92ef90016b5e098d396b603897eaa587','2025-04-10 18:59:01','administrador'),(192,2,'1d29c001ae1ac6bb89391852944e2c9310c5e91d6a4f90b2857b1b51d6688382','2025-04-10 19:00:27','administrador'),(193,2,'40f48ec6fc401f67468ed40f63b99d752d7dc1930377078991457c5510ccfbe6','2025-04-10 19:23:59','administrador'),(194,2,'b7f40cd0b0b2505c02a2149876d2493f1b1910e924f3f3a8d4ee6fd3b1fc6587','2025-04-10 19:28:04','administrador'),(195,2,'9e13d9341db455bc3dde0364726fe23c552ee9af4feb8285141b198a25cb894c','2025-04-10 19:46:56','administrador'),(196,2,'f1a4e6eb17348e73dbc445a3b48eb2dca662ab8aade5453e776a4a012be24dad','2025-04-10 20:18:02','administrador'),(197,2,'a254faf903d70afab2ac8f104a49ae851e2f4846b35bb98bea5bf808fb0f3dac','2025-04-10 22:14:46','administrador'),(198,2,'135a7d63180bc2969aba1978b818b5f84becef60165526939dc06be40af048f2','2025-04-10 22:16:28','administrador'),(199,2,'15c095c3334e9740c66cf954bbb3c3bcd25be8f2b9a9ec75794b31ce40fec090','2025-04-10 22:19:17','administrador'),(200,32,'a6add06b878ab1b322e07f199c07f1e156228b746857627c6bbc9dd3823fad4e','2025-04-10 22:45:49','cliente'),(201,2,'a8bbee04cd6884a3de8f153ad54fb477427bc5bd099403d9c14e83dd920e2bd4','2025-04-10 22:46:08','administrador'),(202,2,'c1f072ea86ff721f15e8082bda7cfbe0d8aca0ca0d571bfbf198554906f5384a','2025-04-10 22:50:05','administrador'),(203,2,'a924274ca670f08eedf3346f59440710232fa890bf196873213b18edf917c808','2025-04-10 22:53:35','administrador'),(204,2,'0307cc795776c976e256499364cb3647de81451d0f13edc58d343b80e704675f','2025-04-10 22:56:38','administrador'),(205,2,'fcbbddab0d92e0df1318a9d4b96b92fef6a44e4555f2261bd9f886a3bf75952e','2025-04-10 22:59:46','administrador'),(206,2,'63484063698821c596d78a3dba7b9266372e27f69173bb8c20cf8622d06c649c','2025-04-10 23:22:15','administrador'),(207,2,'556e8a3bc124c4e10135e1f3673b5bd919c1ee4ce1526869620bcd244817154b','2025-04-10 23:22:41','administrador'),(208,2,'eefe631654aad14a4f41194032702d534ea56657dd7144fcfc89da530ecee604','2025-04-10 23:34:06','administrador'),(209,2,'1e6a3e022f73c9170a3e7a976b6c3b0fca962849e0e070590df335706be2ab37','2025-04-10 23:45:20','administrador'),(210,2,'1978e6c56e780c5839041a44fff35b43fc668d391cca021f103ca9329bde1af5','2025-04-11 00:11:15','administrador'),(211,2,'864cdda7df2734608ed06a5c2ee6f93171382c6fd14ccd62ea47e0d8bd335954','2025-04-11 00:36:46','administrador'),(212,32,'9fc7943f28a1fc5d93ebaee097ea8ece7e7229683d5b697ae14e206a16657ccf','2025-04-11 00:49:01','cliente'),(213,2,'b4f7b82a75ef2b654cbeb0ff663d8a7d6f417f6625fe37c92537c506f246947c','2025-04-11 00:49:17','administrador'),(214,12,'0223dbdbeb31745f780e1a68b6d33b8aa30db2038fc0f842909fbe34569e38b9','2025-04-11 00:52:12','contratista'),(215,13,'24f251f046850f92fcf6a0b116c405dc6d0ca631199b3e07480b74c015d9da14','2025-04-11 00:53:59','contratista'),(216,2,'f4b1287aff341e1d7dee5bcfe83afc3470a503fb321e2885c2a797b16204a2ed','2025-04-11 00:54:15','administrador'),(217,2,'bb678ec5bbe44efc4d62c06185c9e4a49397ab0453ccabcb8401885127d10d5d','2025-04-11 00:54:28','administrador'),(218,2,'85dcfb2f51c444243fa0bcdc1d0c05a1edcc78228153d429acee15d2a9951339','2025-04-11 13:13:52','administrador'),(219,2,'f0f57828cbcc8e711be3ac68265547ab4c9f21bf1b3672d7b4f67fe0e65178c1','2025-04-11 13:20:16','administrador'),(220,2,'9224c3620780d0ff8f0c6f3b350a41f98f15b4bee4979106eab42d063b01b072','2025-04-11 13:23:44','administrador'),(221,2,'4baf9720a76c0fb63b1343d4826b42a8b31b0b3db778879995aa9f786f6c059d','2025-04-11 14:43:36','administrador'),(222,2,'798185e3d07af3b3d6d98f1d2435fd10215282de3a334346de409577d1533ff7','2025-04-11 14:49:32','administrador'),(223,2,'b0bb38e4e6e63968afd542c56de259253400cc40ebf83e4c36d79fad22728f11','2025-04-11 14:56:41','administrador'),(224,2,'bbc3d1632b70e78c60912bd0a9830c5615767a0418227a6f98bedd8801881802','2025-04-11 15:13:33','administrador'),(225,2,'94b1608624a66833721a3127a8709e8f0e65d8cd7a4e93379f920356a61c3de4','2025-04-11 15:15:04','administrador'),(226,2,'9acea1937e3e23bc875c046d011e667014ed8ddfa407d153d3543c9a99e5ef29','2025-04-11 15:28:13','administrador'),(227,2,'facaeac695233737a8a631498aa63689433866f7366503558e22f78797e0a6ae','2025-04-11 15:30:31','administrador'),(228,2,'54f10e8aaa5001887bd5679b90a1d032c100c3e94a6fea47839373929901195d','2025-04-11 15:39:47','administrador'),(229,2,'957affe949b1c7b9c25741ef4841e8b6f35ecc1c5e84c13b3287b8994eb5bc98','2025-04-11 15:42:41','administrador'),(230,2,'8356dab07755e7e790bccaa908fc840b4c17d5725be7bb08f488bee5a8c17b7d','2025-04-11 17:49:56','administrador'),(231,2,'873bb5b4ddf5254e7dad63ff5ab895bc155fe8a5fd49e34ed9cf03bb10875efa','2025-04-11 17:50:40','administrador'),(232,2,'b65f79b927740445c0703dc40008c31847ecce4cef213fbfded1efc7802f79f1','2025-04-11 17:52:08','administrador'),(233,2,'794a4f045684b4587aa69827a063657fb6994ba08b43816d6554e22f1477246d','2025-04-11 18:38:26','administrador'),(234,2,'3e490df08f39a40c6c70a3531f6dff68b1920ddb46bbc6e4d4911d7068fae209','2025-04-11 19:04:24','administrador'),(235,2,'fca1cdd12e4d1d61db04abbec0b209108ca7a9e7ebfa168b6d0bf3a4b5ed9adb','2025-04-11 19:14:18','administrador'),(236,2,'459e8e428787b42e7eb811c89a8e2a6145130d04abd2e3ba3b8de90df8c75a81','2025-04-11 19:25:01','administrador'),(237,2,'5f933ffad3dac77a5b9a4181d0aa39ba5aadc7f01e8db578f79a7bf8fd354672','2025-04-11 19:36:12','administrador'),(238,2,'c67ad89da9492343e497814825883d8386a0580058eaa69e91e919bad3f8f770','2025-04-11 19:42:00','administrador'),(239,2,'39b906e31f15bf68d95e2911b7199b13c1efd95655d40a32490cd454f66e7991','2025-04-11 19:48:42','administrador'),(240,2,'e7b89c3e5b2cd963c545fe0b17d6890deae2944dc7432b0e3de6ebf3f282ee4e','2025-04-11 20:36:17','administrador'),(241,2,'a634e7d1b8dd1ff51c6249cd32b513acbbb23e4a9bc9de1043154363fcec4bcb','2025-04-11 20:37:25','administrador'),(242,2,'9bafeec47e415ffebb3bc8bb3c4fcbcc934a47a7dc34fef3a809f5bac7e3c5bb','2025-04-11 20:39:03','administrador'),(243,2,'700465260bf9a58c3eb80aa7f7d4aac5d2f233823c302cb00fbe32515a755a3c','2025-04-11 20:46:44','administrador'),(244,2,'e7821bc2136359cee23792f100e1b313846910dfabf5e2ca7400a4a98f413290','2025-04-11 20:50:17','administrador'),(245,2,'ffb036f336098f3855955f35d23642ebf1a418ae9ce5369b7fe434d1449f0a63','2025-04-11 20:57:33','administrador'),(246,2,'2b91cfc3a70f19c8d10f894d2e7de54b72b1c627b3a556fa9ad5487ac28f1ff6','2025-04-11 21:32:08','administrador'),(247,2,'cf461f56da703d05f3b1fd0f4f07073a87f41870819d7cfdabea0aa2e0147373','2025-04-11 21:45:00','administrador'),(248,2,'304449151ac48405a89859d8d92b7f53c079981cbe15a78c0abf5ae429fccf6a','2025-04-11 21:48:50','administrador'),(249,2,'0c0ea4278986d4f1f57243beee9e5472a43f2c837f06a67368d55029736f3f4f','2025-04-11 22:02:11','administrador'),(250,2,'f57a582e58c39c4aee671957eee4d5d199fe45a1f1c8882589b698d1c9e32e61','2025-04-11 22:02:52','administrador'),(251,2,'5979d4dbb553ba3a2fdd91740179bec3b6861da74fe60a73862196790ce925ca','2025-04-11 22:52:29','administrador'),(252,2,'27122b3e3df14784cbbdcdf067e8b1af5ce0de68db9abdacd1eb66541647758b','2025-04-12 02:51:00','administrador'),(253,2,'f6c9a61cf679701c48b6ba077d7d47288cad23951882b9b8380e64dbc068de76','2025-04-12 02:53:50','administrador'),(254,2,'38e89ac8b829bad6ced292b18cbf75a0d173e4539a30f94e22deaeddb156fc3e','2025-04-12 03:25:22','administrador'),(255,2,'655417f583ab3afb0f411709f65adbfcf08f829087ad06b49dacb6d9de9a9780','2025-04-12 03:41:42','administrador'),(256,2,'a7a3bd36044631cf4b179de4e4669a4a6e5fe0ec17b7c74dc0ad1e0707098b60','2025-04-12 04:02:28','administrador'),(257,2,'d5b56d978588ea782e2ec276d31a21c5b724d046270775cc161ec8d12b733ab4','2025-04-12 04:02:52','administrador'),(258,2,'b44584696e417394aca33f7023fe4dce939af2d557f1905944e3b6343e345afe','2025-04-12 12:51:09','administrador'),(259,2,'e2a9c25b75132e6991e4a42feed497402f5c3f3aacff04960db1530a215e0dd6','2025-04-12 12:55:55','administrador'),(260,2,'bf78a0fa9dbfe954a2b1e6e545c0371db73902196cd4904998da3df34b532881','2025-04-12 12:56:51','administrador'),(261,2,'bd825b24c8d7a400b44c9e59b4f9df0d9267c5dff3890539c7be18cbe5e89862','2025-04-12 12:58:04','administrador'),(262,2,'0dab46f30f24dcd1cfb7c68fba32cf6117d88680982a54345c676ce5317e66e6','2025-04-12 13:07:04','administrador'),(263,2,'63c92dcb52f12abb0ff08bc6c1ff12f4bb5b8834621074d2e095088c48f1c4eb','2025-04-12 13:12:27','administrador'),(264,2,'cd5df0c8467ee99d8d43c864703d8faa603eeded5dce2df04a8d6a6697823cd8','2025-04-12 13:14:07','administrador'),(265,2,'b8e64ab8ec5813deb18d4630fc38d112b482e8531a9939984cd1972ba37703a3','2025-04-12 13:51:42','administrador'),(266,2,'2c6d8adef9940ecd4fc197a0cc77db1f72a36e2370d38939cbb60a7ffd0bea9e','2025-04-12 13:53:54','administrador'),(267,2,'da4b9ee21666ebe5a757080a9ccebd655fe9c33bc05e125b2fe1695d250e1f7c','2025-04-12 14:01:27','administrador'),(268,2,'03e39a17e0d62a42b56e17519ce81931bd776a39be8c500df706aeb8875eabbd','2025-04-12 14:09:57','administrador'),(269,2,'1bf0dd9178f752888199762af9246c77e8dc891cb97b9149c400f94f71ba458d','2025-04-12 14:11:31','administrador'),(270,2,'92e1a856fc0e924143845007188dc4c59cef6fbc63baa456d32d854cbd013f37','2025-04-12 14:12:43','administrador'),(271,2,'3364c6ded8bbb45c50399852a126908f6a5a1badc501bf464d0c0a6c0aa06457','2025-04-12 14:38:51','administrador'),(272,2,'2a0555c8177aabe9987727814bd6f2041e851d0058970d5f9010010e03d885d9','2025-04-12 14:39:20','administrador'),(273,2,'59f79623d94eb4d6c0cb8acc7ba7f9e6cb60f39ee795a6fab6b4c5debbd2f223','2025-04-12 14:53:56','administrador'),(274,2,'1e5841301def1fdfe480161d7bebec0ddb01350161d6d3885df9b67964bbc1e9','2025-04-12 15:04:10','administrador'),(275,2,'e60c99764c79417173c39ec16828a95d8f5a99e1eb5d22799824d93cc4bdd114','2025-04-12 15:10:28','administrador'),(276,2,'e6579d1f60befa912736900b59cf891b43ec83bcccd7330999a999eea2a73bad','2025-04-12 15:39:35','administrador'),(277,2,'76630f9c1695867b67ab75c3b19204803cbeadad30c0e612b73aef9b951c49cf','2025-04-12 15:39:52','administrador'),(278,2,'e521317f3cb9dce0ff47cbfe39afb614f0b02a743c468d0899a3c5cb3920559f','2025-04-12 15:47:01','administrador'),(279,2,'993d0aee17af9d4a7f0f084eaddc2b69cc55b0e761090cd6b8129f87979ffb8c','2025-04-12 15:50:02','administrador'),(280,2,'5fda4cec0e157f23ca6ee01a6b56b6cff0f69fc3c093b82054b5c413e4de9b98','2025-04-12 15:52:56','administrador'),(281,2,'3117484d73facdb30f7883c7ccf4699d7fad9f630648183af5da08246b041d35','2025-04-12 15:59:29','administrador'),(282,2,'c4f1c31e326e4d43b5c1bc782dea9923bf5e694981c1ddd29ddb1d3f4d10b475','2025-04-12 16:00:54','administrador'),(283,2,'cfe69af4d504ce6636ca8bf4e71b83b6ce58d5d6b00c9ef74b8aa740266cef91','2025-04-12 16:02:31','administrador'),(284,2,'f895e48189943e905b6831f293a596f4de69555462fbf5a91a86b0d233c3e103','2025-04-12 16:02:57','administrador'),(285,2,'ca86c202fe79fd6584d1be1c6b954f516c473d58e13192d42ad96ee4c6264463','2025-04-12 16:10:25','administrador'),(286,32,'2e4785af24e94944ba04bcb101af3c431453a4b1a70e53ff929fce8645423a75','2025-04-12 16:17:26','cliente'),(287,2,'518fdfaa43413f8a95bcb20dd6b6c568bdec4c7e988870ba30e342b716345fd5','2025-04-12 16:17:38','administrador'),(288,2,'2141dff642b9d5a7ffca676dd5077c295fef7718c75cf9c69e2ee8323c17da9d','2025-04-12 16:17:56','administrador'),(289,2,'a6bb65b7f05c4984bc044beb6f48fdefc632f124c1244e86c9e89196af51e22b','2025-04-12 16:19:46','administrador'),(290,2,'6d3abf4794906ab08258bb977bc7ee6e530194a9302c9200dbb165f1119831fe','2025-04-12 16:20:13','administrador');
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

--
-- Dumping routines for database 'handymantotal'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-12 11:31:48
