-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 26, 2025 at 05:16 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsc_mantenimiento`
--

-- --------------------------------------------------------

--
-- Table structure for table `analisis`
--

DROP TABLE IF EXISTS `analisis`;
CREATE TABLE IF NOT EXISTS `analisis` (
  `id_analisis` int NOT NULL AUTO_INCREMENT,
  `id_diagnostico` int NOT NULL,
  `analisis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT 'Estos detalles los escribe el analista de vibracion',
  `recomendaciones` text,
  PRIMARY KEY (`id_analisis`),
  KEY `id_diagnostico` (`id_diagnostico`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `analisis`
--

INSERT INTO `analisis` (`id_analisis`, `id_diagnostico`, `analisis`, `recomendaciones`) VALUES
(10, 81, '.- Bajos niveles de lubricación en los puntos del motor.\r\n.- Se aprecian daños severos en los rodamientos de la bomba.', '.- Planificar de inmediato la intervención del conjunto.'),
(11, 82, '.- Bajos niveles de lubricación en los puntos del motor. ', '.- Ejecutar la rutina de lubricación.\r\n.- Verificar el lubricante de la bomba.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(12, 83, '.- Bajos niveles de lubricación en los puntos del motor.\r\n.- Se evidencian daños moderados en los rodamientos de la bomba. ', '.- Ejecutar rutina de lubricación al conjunto motor-bomba.\r\n.- Planificar la intervención del conjunto motor-bomba.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.');

-- --------------------------------------------------------

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `accion` text NOT NULL COMMENT 'Ej: Creó, Editó, Eliminó',
  `tabla_afectada` text NOT NULL,
  `id_registro_afectado` int NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`id_auditoria`),
  KEY `id_usuario` (`id_usuario`),
  KEY `fecha_hora` (`fecha_hora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `codigo_cliente` varchar(50) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `rif_ci` varchar(20) NOT NULL,
  `domicilio_fiscal` text NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `codigo_cliente` (`codigo_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `codigo_cliente`, `nombre_cliente`, `rif_ci`, `domicilio_fiscal`) VALUES
(1, 'SBV-100', 'A. S. 24 Valencia C. A.', 'J-29506300-8', 'Av. 04 C.C. Sambil Valencia, Nivel Mañongo OF Administrativas Urb. Ciudad Jardín Mañongo, Telf. 0241-841.17.26');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosticos`
--

DROP TABLE IF EXISTS `diagnosticos`;
CREATE TABLE IF NOT EXISTS `diagnosticos` (
  `id_diagnostico` int NOT NULL AUTO_INCREMENT,
  `id_inspeccion` int NOT NULL,
  `prioridad` int NOT NULL COMMENT '1 Mas alta, 4 Mas baja',
  `nivel_vibracion` enum('Severo','Alto','Moderado','Bajo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Severo, Alto, Moderado, Bajo',
  PRIMARY KEY (`id_diagnostico`),
  KEY `id_inspeccion` (`id_inspeccion`),
  KEY `prioridad` (`prioridad`,`nivel_vibracion`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diagnosticos`
--

INSERT INTO `diagnosticos` (`id_diagnostico`, `id_inspeccion`, `prioridad`, `nivel_vibracion`) VALUES
(81, 95, 1, 'Severo'),
(82, 96, 3, 'Moderado'),
(83, 97, 2, 'Alto');

-- --------------------------------------------------------

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
CREATE TABLE IF NOT EXISTS `documentos` (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `id_equipo` int DEFAULT NULL,
  `archivo` longblob,
  PRIMARY KEY (`id_documento`),
  KEY `id_equipo` (`id_equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
CREATE TABLE IF NOT EXISTS `equipos` (
  `id_equipo` int NOT NULL AUTO_INCREMENT,
  `tag_numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tipo_equipo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `id_cliente` int NOT NULL,
  PRIMARY KEY (`id_equipo`),
  UNIQUE KEY `tag_numero` (`tag_numero`),
  KEY `tipo_equipo` (`tipo_equipo`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `tag_numero`, `tipo_equipo`, `ubicacion`, `id_cliente`) VALUES
(112, 'BAH-001', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(113, 'BAC-001', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(114, 'BAH-002', 'Bomba', 'Sala de Chillers - Planta Valencia', 1);

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE IF NOT EXISTS `eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `id_equipo` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `eventos_ibfk_1` (`id_equipo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id`, `fecha`, `titulo`, `id_equipo`, `created_at`, `updated_at`) VALUES
(14, '2025-03-12', 'Test 2', NULL, '2025-03-11 22:01:45', '2025-03-11 22:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `inspecciones`
--

DROP TABLE IF EXISTS `inspecciones`;
CREATE TABLE IF NOT EXISTS `inspecciones` (
  `id_inspeccion` int NOT NULL AUTO_INCREMENT,
  `id_equipo` int NOT NULL,
  `temperatura_motor_1` float DEFAULT NULL COMMENT 'Motor, punto 1',
  `temperatura_motor_2` float DEFAULT NULL COMMENT 'Motor, punto 2',
  `temperatura_bomba_1` float DEFAULT NULL COMMENT 'Bomba, punto 1',
  `temperatura_bomba_2` float DEFAULT NULL COMMENT 'Bomba, punto 2',
  `fecha_inspeccion` date NOT NULL,
  PRIMARY KEY (`id_inspeccion`),
  KEY `id_equipo` (`id_equipo`),
  KEY `fecha_inspeccion` (`fecha_inspeccion`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspecciones`
--

INSERT INTO `inspecciones` (`id_inspeccion`, `id_equipo`, `temperatura_motor_1`, `temperatura_motor_2`, `temperatura_bomba_1`, `temperatura_bomba_2`, `fecha_inspeccion`) VALUES
(95, 112, 40, 45, 32, 25, '2009-10-03'),
(96, 113, 32, 58, 46, 39, '2009-10-03'),
(97, 114, 32, 38, 32, 27, '2009-10-03');

-- --------------------------------------------------------

--
-- Table structure for table `inspeccion_fallas`
--

DROP TABLE IF EXISTS `inspeccion_fallas`;
CREATE TABLE IF NOT EXISTS `inspeccion_fallas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_inspeccion` int NOT NULL,
  `id_falla` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `inspeccion_fallas_ibfk_1` (`id_inspeccion`),
  KEY `id_falla` (`id_falla`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspeccion_hallazgos`
--

DROP TABLE IF EXISTS `inspeccion_hallazgos`;
CREATE TABLE IF NOT EXISTS `inspeccion_hallazgos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_inspeccion` int NOT NULL,
  `id_hallazgo` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_inspeccion` (`id_inspeccion`,`id_hallazgo`),
  KEY `id_hallazgo` (`id_hallazgo`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspeccion_hallazgos`
--

INSERT INTO `inspeccion_hallazgos` (`id`, `id_inspeccion`, `id_hallazgo`) VALUES
(25, 95, 2),
(26, 95, 27),
(27, 96, 2),
(28, 97, 27);

-- --------------------------------------------------------

--
-- Table structure for table `lista_fallas`
--

DROP TABLE IF EXISTS `lista_fallas`;
CREATE TABLE IF NOT EXISTS `lista_fallas` (
  `id_falla` int NOT NULL AUTO_INCREMENT,
  `falla` varchar(150) NOT NULL,
  PRIMARY KEY (`id_falla`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_fallas`
--

INSERT INTO `lista_fallas` (`id_falla`, `falla`) VALUES
(1, 'Desbalance'),
(2, 'Desalineación'),
(3, 'Holgura Mecánica'),
(4, 'Rodamientos'),
(5, 'Base'),
(6, 'Chumaceras'),
(7, 'Lubricación'),
(8, 'Engranajes'),
(9, 'Excentricidad'),
(10, 'Eje Doblado'),
(11, 'Correas/Poleas'),
(12, 'Motor Eléctrico'),
(13, 'Cavitación'),
(14, 'Roce'),
(15, 'Alabes'),
(16, 'Cojinetes'),
(17, 'Resonancia'),
(37, 'Ninguna');

-- --------------------------------------------------------

--
-- Table structure for table `lista_hallazgos`
--

DROP TABLE IF EXISTS `lista_hallazgos`;
CREATE TABLE IF NOT EXISTS `lista_hallazgos` (
  `id_hallazgo` int NOT NULL AUTO_INCREMENT,
  `hallazgo` varchar(150) NOT NULL,
  PRIMARY KEY (`id_hallazgo`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_hallazgos`
--

INSERT INTO `lista_hallazgos` (`id_hallazgo`, `hallazgo`) VALUES
(1, 'Fuga de grasa o aceite lubricante'),
(2, 'Bajo nivel de grasa o aceite lubricante'),
(3, 'Alta temperatura en los sellos'),
(4, 'Fuga de producto en los sellos'),
(5, 'Fuga de producto en la tubería'),
(6, 'Carcaza con corrosión'),
(7, 'Tubería con corrosión'),
(8, 'Fuga en la tubería'),
(9, 'Grasa o aceite en la base'),
(10, 'Producto en la base'),
(11, 'Basura en el área'),
(12, 'Instrumentos de medición no funcionan'),
(13, 'Faltan tuercas, tornillos, arandelas, tapas de aceite'),
(14, 'Falta tag de identificación'),
(15, 'Correas defectuosas'),
(16, 'Pintura en mal estado'),
(17, 'Problemas eléctricos (aislamiento, conductividad)'),
(18, 'Cavitación'),
(19, 'Switch de arranque funciona mal'),
(20, 'Protector de coupling dañado'),
(21, 'Alto ruido en el equipo'),
(27, 'Daño en Rodamientos');

-- --------------------------------------------------------

--
-- Table structure for table `mantenimiento`
--

DROP TABLE IF EXISTS `mantenimiento`;
CREATE TABLE IF NOT EXISTS `mantenimiento` (
  `id_mantenimiento` int NOT NULL AUTO_INCREMENT,
  `id_equipo` int NOT NULL,
  `descripcion_reparacion` text NOT NULL,
  `costo` int NOT NULL,
  `repuestos_utilizados` text NOT NULL,
  `fecha_mantenimiento` date NOT NULL,
  PRIMARY KEY (`id_mantenimiento`),
  KEY `id_equipo` (`id_equipo`),
  KEY `fecha_mantenimiento` (`fecha_mantenimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `rol` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analisis`
--
ALTER TABLE `analisis`
  ADD CONSTRAINT `analisis_ibfk_1` FOREIGN KEY (`id_diagnostico`) REFERENCES `diagnosticos` (`id_diagnostico`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnosticos`
--
ALTER TABLE `diagnosticos`
  ADD CONSTRAINT `diagnosticos_ibfk_1` FOREIGN KEY (`id_inspeccion`) REFERENCES `inspecciones` (`id_inspeccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE;

--
-- Constraints for table `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspecciones`
--
ALTER TABLE `inspecciones`
  ADD CONSTRAINT `inspecciones_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspeccion_fallas`
--
ALTER TABLE `inspeccion_fallas`
  ADD CONSTRAINT `inspeccion_fallas_ibfk_1` FOREIGN KEY (`id_inspeccion`) REFERENCES `inspecciones` (`id_inspeccion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inspeccion_fallas_ibfk_2` FOREIGN KEY (`id_falla`) REFERENCES `lista_fallas` (`id_falla`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspeccion_hallazgos`
--
ALTER TABLE `inspeccion_hallazgos`
  ADD CONSTRAINT `inspeccion_hallazgos_ibfk_1` FOREIGN KEY (`id_hallazgo`) REFERENCES `lista_hallazgos` (`id_hallazgo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inspeccion_hallazgos_ibfk_2` FOREIGN KEY (`id_inspeccion`) REFERENCES `inspecciones` (`id_inspeccion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mantenimiento`
--
ALTER TABLE `mantenimiento`
  ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
