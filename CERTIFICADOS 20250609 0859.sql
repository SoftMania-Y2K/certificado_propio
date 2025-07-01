-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema gestor_certificados
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ gestor_certificados;
USE gestor_certificados;

--
-- Table structure for table `gestor_certificados`.`certificados_asociados`
--

DROP TABLE IF EXISTS `certificados_asociados`;
CREATE TABLE `certificados_asociados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_vencimiento` datetime NOT NULL,
  `nombre_asociado` varchar(255) NOT NULL,
  `navegador` varchar(50) NOT NULL,
  `fecha_asociacion` datetime NOT NULL,
  `fingerprint` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gestor_certificados`.`certificados_asociados`
--

/*!40000 ALTER TABLE `certificados_asociados` DISABLE KEYS */;
INSERT INTO `certificados_asociados` (`id`,`filename`,`common_name`,`email`,`fecha_creacion`,`fecha_vencimiento`,`nombre_asociado`,`navegador`,`fecha_asociacion`,`fingerprint`) VALUES 
 (3,'cert_9f5596b52e2e8ca32c257a68444c4fa9.pem','localhost','admin@example.com','2025-06-08 22:10:46','2026-06-08 22:10:46','pc Caja','chrome','2025-06-09 01:31:55','e2cf4db8952abf55bacde6254ba7329ad88cb2a424b2542d53b9e6e2af635e2b');
/*!40000 ALTER TABLE `certificados_asociados` ENABLE KEYS */;


--
-- Table structure for table `gestor_certificados`.`certificados_disponibles`
--

DROP TABLE IF EXISTS `certificados_disponibles`;
CREATE TABLE `certificados_disponibles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_vencimiento` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gestor_certificados`.`certificados_disponibles`
--

/*!40000 ALTER TABLE `certificados_disponibles` DISABLE KEYS */;
INSERT INTO `certificados_disponibles` (`id`,`filename`,`common_name`,`email`,`fecha_creacion`,`fecha_vencimiento`) VALUES 
 (4,'cert_923a20a694eacef71ef9c2b1e5337e4d.pem','localhost','admin@example.com','2025-06-08 22:10:49','2026-06-08 22:10:49'),
 (5,'cert_4b55993acf19279f52cb6a36348ed7f9.pem','localhost','admin@example.com','2025-06-08 20:57:13','2026-06-08 20:57:13'),
 (6,'cert_4614f1c819bd50d8be5e84b56aa37a6e.pem','localhost','admin@example.com','2025-06-08 22:10:37','2026-06-08 22:10:37');
/*!40000 ALTER TABLE `certificados_disponibles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
