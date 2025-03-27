-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2025 at 09:17 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `analisis`
--

INSERT INTO `analisis` (`id_analisis`, `id_diagnostico`, `analisis`, `recomendaciones`) VALUES
(10, 81, '.- Bajos niveles de lubricación en los puntos del motor.\r\n.- Se aprecian daños severos en los rodamientos de la bomba.', '.- Planificar de inmediato la intervención del conjunto.'),
(11, 82, '.- Bajos niveles de lubricación en los puntos del motor. ', '.- Ejecutar la rutina de lubricación.\r\n.- Verificar el lubricante de la bomba.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(12, 83, '.- Bajos niveles de lubricación en los puntos del motor.\r\n.- Se evidencian daños moderados en los rodamientos de la bomba. ', '.- Ejecutar rutina de lubricación al conjunto motor-bomba.\r\n.- Planificar la intervención del conjunto motor-bomba.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(13, 84, '.- Bajos niveles de lubricación en los puntos del motor. ', '.- Ejecutar la rutina de lubricación.\r\n.- Verificar el lubricante de la bomba.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(14, 85, '.- Bajos niveles de lubricación en los puntos del motor.\r\n.- Se aprecian daños moderados en los rodamientos del motor.\r\n.- Se aprecian soltura y daños severos en los rodamientos de la bomba.', '.- Planificar de inmediato la intervención del conjunto.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(15, 86, '.- Bajos niveles de lubricación en los puntos del motor y de la bomba.\r\n.- Se evidencian daños moderados en los rodamientos del motor.', '.- Ejecutar rutina de lubricación al conjunto motor-bomba.\r\n.- Planificar la intervención del conjunto.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(16, 87, '.- Se aprecian daños moderados en los rodamientos del motor.\r\n.- Se aprecian daños severos en los rodamientos de la bomba.\r\n.- Se aprecia altos niveles de ruido en el acople.', '.- Planificar de inmediato la intervención del conjunto.\r\n.- Verificar condiciones del acople y ensamblaje del protector. \r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(17, 88, '.- Se aprecian daños moderados en los rodamientos del motor.\r\n.- Se aprecian daños severos en los rodamientos de la bomba.', '.- Planificar de inmediato la intervención del conjunto.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(18, 89, '.- Se evidencian signos de desalineación en el conjunto (motor-bomba).\r\n.- Se evidencian daños moderados en los rodamientos del motor y de la bomba.  ', '.- Planificar de la alineación laser del conjunto (motor-bomba).\r\n.- Planificar la intervención del conjunto.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(19, 90, '.- Daño severo en el caracol de la bomba.', '.- Planificar de inmediato la intervención del conjunto.'),
(20, 91, '.- Incremento en los niveles de vibración en el motor.\r\n.- Niveles de daño moderados en los rodamientos del motor.\r\n.- Posible desalineación del conjunto motor-caja reductora.', '.- Planificar la intervención del conjunto.\r\n.- Detener y revisar la limpieza de las aspas del ventilador. \r\n.- Revisar las condiciones de los bujes del acople.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(21, 92, '.- Motor en condiciones de trabajo estable.\r\n.- Posible desalineación del conjunto motor-caja reductora.', '.- Verificar alineación del conjunto motor-caja reductora.\r\n.- Realizar monitoreo de temperatura.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(22, 93, '.- Motor en condiciones de trabajo estable.\r\n.- Posible desalineación del conjunto motor-caja reductora.', '.- Verificar alineación del conjunto motor-caja reductora.\r\n.- Realizar monitoreo de temperatura.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(23, 94, '.- Se mantienen los daños moderados en los rodamientos del motor.\r\n.- Presenta alta vibración por carencia de anclajes en el equipo. ', '.- Planificar la intervención del conjunto motor-bomba.\r\n.- Anclar adecuadamente el equipo.\r\n.- Verificar tensión en las tuberías.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(24, 95, '.- Se mantienen los daños moderados en los rodamientos del motor.\r\n.- Presenta alta vibración por carencia de anclajes en el equipo. ', '.- Planificar la intervención del conjunto motor-bomba.\r\n.- Anclar adecuadamente el equipo.\r\n.- Verificar tensión en las tuberías.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(25, 96, 'FUERA DE SERVICIO - MANTENIMIENTO', ''),
(26, 97, '.- Se evidencian daños moderados en los rodamientos del motor.\r\n.- Presenta alta vibración por carencia de anclajes en la bomba. ', '.- Planificar la intervención del conjunto motor-bomba.\r\n.- Anclar adecuadamente el motor.\r\n.- Verificar tensión en las tuberías.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(27, 98, '.- Se evidencian daños iniciales en los rodamientos del motor.\r\n.- Se aprecian bajos niveles de lubricación en los puntos del motor.', '.- Anclar adecuadamente el equipo.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\n overall.'),
(28, 99, ' .- Se aprecian bajos niveles de lubricación en los puntos del motor.', '.- Anclar adecuadamente el equipo.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\n overall.'),
(29, 100, '.- Se presentan daños y soltura en los rodamientos del motor.\r\n.- Se evidencian condiciones de soltura mecánica en el conjunto.\r\n.- Se evidencian signos de desbalance.', '.- Verificar anclajes (soportes) del ventilador.\r\n.- Verificar los tornillos de las chumaceras. \r\n.- Verificar las aspas del ventilador,  la separación del rodete con el aro de succión.\r\n.- Verificar balanceo del ventilador.\r\n\r\nESTE EQUIPO NO ESTA EN CONDICIONES OPERATIVAS'),
(30, 101, '.- El motor trabaja en condiciones severas de vibración que se refleja desde el ventilador.\r\n.- Se evidencian condiciones de soltura mecánica en el conjunto.\r\n.- Falta una (01) correa.\r\n.- Se evidencian signos de desbalance. ', '.- Verificar anclajes (soportes) del ventilador.\n.- Verificar los tornillos de las chumaceras. \n.- Verificar las aspas del ventilador,  la separación del rodete con el aro de succión.\n.- Verificar balanceo del ventilador.\n\nESTE EQUIPO NO ESTA EN CONDICIONES OPERATIVAS'),
(31, 102, 'EQUIPO FUERA DE SERVICIO – PROBLEMA EN CONTACTOR', '.- Verificar instalación eléctrica del conjunto.\r\n.- Realizar rutina de monitoreo de vibración.'),
(32, 103, 'EQUIPO FUERA DE SERVICIO – PROBLEMA EN CONTACTOR', '.- Verificar instalación eléctrica del conjunto.\r\n.- Realizar rutina de monitoreo de vibración.'),
(33, 104, '.- Se evidencia baja lubricación en los rodamientos del motor.\r\n.- Se evidencia vibración axial en el ventilador, posible desalineación del conjunto motor-\r\nventilador.', '.- Realizar rutina de lubricación.\r\n.- Verificar la alineación de las poleas del conjunto motor-ventilador.\r\n.- Verificar el balanceo del ventilador.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.'),
(34, 105, '.- Se evidencia baja lubricación en los rodamientos del motor.\r\n.- Se evidencia vibración axial en el ventilador, posible desalineación del conjunto motor-\r\nventilador.', '.- Realizar rutina de lubricación.\r\n.- Verificar la alineación de las poleas del conjunto motor-ventilador.\r\n.- Verificar el balanceo del ventilador.\r\n.- Mantener en observación y monitorear vibración al menos con equipo colector de valores\r\noverall.');

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
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diagnosticos`
--

INSERT INTO `diagnosticos` (`id_diagnostico`, `id_inspeccion`, `prioridad`, `nivel_vibracion`) VALUES
(81, 95, 1, 'Severo'),
(82, 96, 3, 'Moderado'),
(83, 97, 2, 'Alto'),
(84, 98, 3, 'Moderado'),
(85, 99, 2, 'Alto'),
(86, 100, 2, 'Alto'),
(87, 101, 1, 'Severo'),
(88, 102, 2, 'Severo'),
(89, 103, 2, 'Moderado'),
(90, 104, 1, 'Severo'),
(91, 105, 2, 'Alto'),
(92, 106, 3, 'Bajo'),
(93, 107, 3, 'Bajo'),
(94, 108, 2, 'Alto'),
(95, 109, 2, 'Alto'),
(96, 110, 5, 'Bajo'),
(97, 111, 2, 'Moderado'),
(98, 112, 3, 'Moderado'),
(99, 113, 3, 'Moderado'),
(100, 114, 1, 'Severo'),
(101, 115, 1, 'Severo'),
(102, 116, 5, 'Bajo'),
(103, 117, 5, 'Severo'),
(104, 118, 3, 'Moderado'),
(105, 119, 3, 'Moderado');

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
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `tag_numero`, `tipo_equipo`, `ubicacion`, `id_cliente`) VALUES
(112, 'BAH-001', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(113, 'BAC-001', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(114, 'BAH-002', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(115, 'BAC-004', 'Bomba', 'Sala de Chillers - Planta Valencia', 1),
(116, 'BAH-004', 'Bomba', 'Montante No. 3 - Planta Valencia', 1),
(117, 'BAH-003', 'Bomba', 'Montante No. 3 - Planta Valencia', 1),
(118, 'TOR-001', 'Motor', 'Torres de Enfriamiento - Planta Valencia', 1),
(119, 'TOR-002', 'Motor', 'Torres de Enfriamiento - Planta Valencia', 1),
(120, 'TOR-003', 'Motor', 'Torres de Enfriamiento - Planta Valencia', 1),
(121, 'BAH-0001', 'Bomba', 'Sistema Hidroneumático 1 - Planta Valencia', 1),
(122, 'BAH-0002', 'Bomba', 'Sistema Hidroneumático 1 - Planta Valencia', 1),
(123, 'BAH-0003', 'Bomba', 'Sistema Hidroneumático 1 - Planta Valencia', 1),
(124, 'BAH-0004', 'Bomba', 'Sistema Hidroneumático 1 - Planta Valencia', 1),
(125, 'BMH-001', 'Bomba', 'Sistema Hidroneumático 2 - Planta Valencia', 1),
(126, 'BMH-002', 'Bomba', 'Sistema Hidroneumático 2 - Planta Valencia', 1),
(127, 'EXT-002', 'Turbina', 'Extractores - Planta Valencia', 1),
(128, 'EXT-003', 'Turbina', 'Extractores - Planta Valencia', 1),
(129, 'Inyector-A', 'Turbina', 'Cuarto de Inyector 1 - Planta Valencia', 1),
(130, 'Inyector-B', 'Turbina', 'Cuarto de Inyector 1 - Planta Valencia', 1),
(131, 'INY-A', 'Turbina', 'Cuarto de Inyector 3 - Planta Valencia', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspecciones`
--

INSERT INTO `inspecciones` (`id_inspeccion`, `id_equipo`, `temperatura_motor_1`, `temperatura_motor_2`, `temperatura_bomba_1`, `temperatura_bomba_2`, `fecha_inspeccion`) VALUES
(95, 112, 40, 45, 32, 25, '2009-10-03'),
(96, 113, 32, 58, 46, 39, '2009-10-03'),
(97, 114, 32, 38, 32, 27, '2009-10-03'),
(98, 115, 34, 45, 51, 41, '2009-10-03'),
(99, 116, 33, 31, 44, 34, '2009-10-03'),
(100, 112, 36, 35, 25, 22, '2009-10-03'),
(101, 117, 29, 26, 22, 19, '2009-10-03'),
(102, 114, 31, 26, 28, 24, '2009-10-03'),
(103, 112, 36, 34, 37, 26, '2009-10-03'),
(104, 114, 0, 0, 0, 0, '2009-10-03'),
(105, 118, 38, 41, 0, 0, '2009-10-03'),
(106, 119, 38, 40, 0, 0, '2009-10-03'),
(107, 120, 39, 43, 0, 0, '2009-10-03'),
(108, 121, 40, 32, 0, 0, '2009-10-03'),
(109, 122, 43, 37, 0, 0, '2009-10-03'),
(110, 123, 0, 0, 0, 0, '2009-10-03'),
(111, 124, 42, 37, 0, 0, '2009-10-03'),
(112, 125, 32, 27, 0, 0, '2009-10-03'),
(113, 126, 32, 26, 0, 0, '2009-10-03'),
(114, 127, 0, 0, 0, 0, '2009-10-03'),
(115, 128, 0, 0, 0, 0, '2009-10-03'),
(116, 129, 0, 0, 0, 0, '2009-10-03'),
(117, 130, 0, 0, 0, 0, '2009-10-03'),
(118, 131, 31, 55, 29, 30, '2009-10-03'),
(119, 131, 31, 55, 29, 30, '2009-10-03');

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
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspeccion_fallas`
--

INSERT INTO `inspeccion_fallas` (`id`, `id_inspeccion`, `id_falla`) VALUES
(106, 100, 4),
(107, 101, 4),
(108, 102, 4),
(109, 103, 2),
(110, 103, 4),
(111, 105, 2),
(112, 105, 4),
(113, 106, 2),
(114, 107, 2),
(115, 108, 4),
(116, 108, 5),
(117, 108, 38),
(118, 109, 4),
(119, 109, 5),
(120, 109, 38),
(121, 111, 4),
(122, 111, 5),
(123, 111, 38),
(124, 112, 4),
(125, 112, 5),
(126, 113, 4),
(127, 113, 5),
(128, 114, 1),
(129, 114, 3),
(130, 114, 4),
(131, 114, 5),
(132, 114, 6),
(133, 115, 3),
(134, 115, 4),
(135, 115, 5),
(136, 117, 39),
(137, 118, 2),
(138, 119, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspeccion_hallazgos`
--

INSERT INTO `inspeccion_hallazgos` (`id`, `id_inspeccion`, `id_hallazgo`) VALUES
(25, 95, 2),
(26, 95, 27),
(27, 96, 2),
(28, 97, 27),
(29, 98, 2),
(30, 99, 2),
(31, 99, 27),
(32, 100, 2),
(33, 100, 21),
(34, 100, 27),
(35, 101, 2),
(36, 101, 21),
(37, 101, 27),
(38, 102, 27),
(39, 103, 2),
(40, 103, 27),
(41, 104, 28),
(42, 105, 27),
(43, 108, 27),
(44, 109, 27),
(45, 111, 27),
(46, 112, 2),
(47, 112, 27),
(48, 113, 2),
(49, 113, 27),
(50, 114, 27),
(51, 115, 27),
(52, 118, 2),
(53, 119, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lista_fallas`
--

DROP TABLE IF EXISTS `lista_fallas`;
CREATE TABLE IF NOT EXISTS `lista_fallas` (
  `id_falla` int NOT NULL AUTO_INCREMENT,
  `falla` varchar(150) NOT NULL,
  PRIMARY KEY (`id_falla`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(37, 'Ninguna'),
(38, 'Tensión en tuberías'),
(39, 'Problema en Contactor');

-- --------------------------------------------------------

--
-- Table structure for table `lista_hallazgos`
--

DROP TABLE IF EXISTS `lista_hallazgos`;
CREATE TABLE IF NOT EXISTS `lista_hallazgos` (
  `id_hallazgo` int NOT NULL AUTO_INCREMENT,
  `hallazgo` varchar(150) NOT NULL,
  PRIMARY KEY (`id_hallazgo`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(27, 'Daño en Rodamientos'),
(28, 'Daño en el Caracol');

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
  `contrasena` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` varchar(50) NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contrasena`, `rol`) VALUES
(1, 'Andres Gutierrez', 'contacto.elsirguti@gmail.com', 'Agt190604', 'Super'),
(3, 'Andres Gutierrez', 'elpepe@gmail.com', '352ec8ce29d24f02789a0f6699b7caebe5ba444c232ce1fad01e74595e6b718e', 'Administrador'),
(4, 'Andres Gutierrez', 'etesech@gmail.com', '6eae25564d99bec83e94b45480f34ef29b748e5e1ffb61ed84384969678609c6', 'Tecnico'),
(6, 'Andres Gutierrez', 'test@test.com', '532eaabd9574880dbf76b9b8cc00832c20a6ec113d682299550d7a6e0f345e25', 'Administrador');

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
