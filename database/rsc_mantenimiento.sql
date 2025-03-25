-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 25, 2025 at 06:22 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `analisis`
--

INSERT INTO `analisis` (`id_analisis`, `id_diagnostico`, `analisis`, `recomendaciones`) VALUES
(2, 73, 'drzfgfg', 'dhfxggfvb'),
(3, 74, 'wefdewd', 'acefca'),
(4, 75, 'xcbgxbf ', 'xcbgbx');

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
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diagnosticos`
--

INSERT INTO `diagnosticos` (`id_diagnostico`, `id_inspeccion`, `prioridad`, `nivel_vibracion`) VALUES
(2, 16, 1, 'Alto'),
(3, 21, 1, 'Severo'),
(4, 23, 1, 'Alto'),
(5, 29, 1, 'Severo'),
(6, 33, 1, 'Alto'),
(7, 39, 1, 'Severo'),
(8, 43, 1, 'Alto'),
(9, 51, 1, 'Severo'),
(10, 53, 1, 'Alto'),
(11, 13, 2, 'Alto'),
(12, 17, 2, 'Moderado'),
(13, 22, 2, 'Alto'),
(14, 24, 2, 'Moderado'),
(15, 30, 2, 'Alto'),
(16, 34, 2, 'Moderado'),
(17, 40, 2, 'Alto'),
(18, 44, 2, 'Moderado'),
(19, 52, 2, 'Alto'),
(20, 54, 2, 'Moderado'),
(21, 14, 3, 'Moderado'),
(22, 18, 3, 'Bajo'),
(23, 25, 3, 'Moderado'),
(24, 26, 3, 'Bajo'),
(25, 35, 3, 'Moderado'),
(26, 36, 3, 'Bajo'),
(27, 45, 3, 'Moderado'),
(28, 46, 3, 'Bajo'),
(29, 55, 3, 'Moderado'),
(30, 56, 3, 'Bajo'),
(31, 15, 4, 'Bajo'),
(32, 19, 4, 'Bajo'),
(33, 27, 4, 'Bajo'),
(34, 28, 4, 'Bajo'),
(35, 37, 4, 'Bajo'),
(36, 38, 4, 'Bajo'),
(37, 47, 4, 'Bajo'),
(38, 48, 4, 'Bajo'),
(39, 57, 4, 'Bajo'),
(40, 58, 4, 'Bajo'),
(41, 59, 1, 'Severo'),
(42, 60, 2, 'Alto'),
(43, 61, 3, 'Moderado'),
(45, 63, 1, 'Alto'),
(46, 64, 2, 'Moderado'),
(47, 65, 3, 'Bajo'),
(48, 66, 4, 'Bajo'),
(50, 68, 2, 'Alto'),
(51, 69, 3, 'Moderado'),
(52, 70, 4, 'Bajo'),
(54, 16, 3, 'Bajo'),
(55, 21, 4, 'Bajo'),
(56, 23, 1, 'Severo'),
(57, 29, 2, 'Alto'),
(58, 33, 3, 'Moderado'),
(59, 39, 4, 'Bajo'),
(60, 43, 1, 'Alto'),
(61, 51, 2, 'Moderado'),
(62, 53, 3, 'Bajo'),
(63, 59, 4, 'Bajo'),
(64, 60, 1, 'Severo'),
(65, 61, 2, 'Alto'),
(67, 63, 4, 'Bajo'),
(68, 64, 1, 'Alto'),
(69, 65, 2, 'Moderado'),
(70, 66, 3, 'Bajo'),
(73, 84, 4, 'Bajo'),
(74, 88, 2, 'Moderado'),
(75, 89, 2, 'Alto');

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
  `hp` int DEFAULT NULL,
  `sistema` varchar(100) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_equipo`),
  UNIQUE KEY `tag_numero` (`tag_numero`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `tag_numero`, `hp`, `sistema`, `ubicacion`, `descripcion`) VALUES
(3, 'BOM-002', 25, 'Hidráulico', 'Planta 2', 'Bomba centrífuga de aceite'),
(4, 'CMP-003', 100, 'Neumático', 'Sala de compresores', 'Compresor de aire de tornillo'),
(5, 'TRB-004', 200, 'Vapor', 'Generación', 'Turbina de vapor de alta presión'),
(6, 'MTR-005', 30, 'Eléctrico', 'Planta 3', 'Motor transportador de banda'),
(7, 'BOM-006', 15, 'Químico', 'Laboratorio', 'Bomba dosificadora de líquidos'),
(8, 'CMP-007', 75, 'Gas', 'Planta de gas', 'Compresor de gas natural'),
(9, 'TRB-008', 150, 'Hidráulico', 'Central hidroeléctrica', 'Turbina hidráulica Francis'),
(10, 'MTR-009', 60, 'Eléctrico', 'Planta 4', 'Motor mezclador industrial'),
(11, 'BOM-010', 40, 'Petróleo', 'Refinería', 'Bomba de crudo'),
(12, 'CMP-011', 120, 'Refrigeración', 'Almacén frigorífico', 'Compresor de refrigeración'),
(13, 'TRB-012', 300, 'Gas', 'Planta de ciclo combinado', 'Turbina de gas'),
(14, 'MTR-013', 20, 'Eléctrico', 'Taller', 'Motor de taladro de banco'),
(15, 'BOM-014', 10, 'Alimentos', 'Planta de alimentos', 'Bomba sanitaria de líquidos'),
(16, 'CMP-015', 50, 'Vacío', 'Laboratorio de vacío', 'Compresor de vacío'),
(17, 'TRB-016', 80, 'Eólica', 'Parque eólico', 'Turbina eólica'),
(18, 'MTR-017', 80, 'Eléctrico', 'Planta 5', 'Motor de grúa puente'),
(19, 'BOM-018', 35, 'Agua', 'Estación de bombeo', 'Bomba de agua potable'),
(20, 'CMP-019', 90, 'Nitrógeno', 'Planta de nitrógeno', 'Compresor de nitrógeno'),
(21, 'TRB-020', 250, 'Geotérmica', 'Central geotérmica', 'Turbina geotérmica'),
(22, 'MTR-021', 45, 'Eléctrico', 'Planta 6', 'Motor de ventilador industrial'),
(23, 'BOM-022', 20, 'Lodos', 'Planta de tratamiento de aguas residuales', 'Bomba de lodos'),
(24, 'CMP-023', 60, 'Oxígeno', 'Planta de oxígeno', 'Compresor de oxígeno'),
(25, 'TRB-024', 120, 'Solar', 'Central termosolar', 'Turbina de vapor solar'),
(26, 'MTR-025', 70, 'Eléctrico', 'Planta 7', 'Motor de extrusora de plástico'),
(27, 'BOM-026', 30, 'Papel', 'Planta de papel', 'Bomba de pulpa de papel'),
(28, 'CMP-027', 80, 'Helio', 'Planta de helio', 'Compresor de helio'),
(29, 'TRB-028', 180, 'Biomasa', 'Central de biomasa', 'Turbina de biomasa'),
(30, 'MTR-029', 55, 'Eléctrico', 'Planta 8', 'Motor de sierra circular'),
(31, 'BOM-030', 28, 'Pintura', 'Planta de pintura', 'Bomba de pintura'),
(32, 'CMP-031', 70, 'Argón', 'Planta de argón', 'Compresor de argón'),
(33, 'TRB-032', 160, 'Mareomotriz', 'Central mareomotriz', 'Turbina mareomotriz'),
(34, 'MTR-033', 65, 'Eléctrico', 'Planta 9', 'Motor de prensa hidráulica'),
(35, 'BOM-034', 22, 'Cemento', 'Planta de cemento', 'Bomba de lechada de cemento'),
(36, 'CMP-035', 65, 'Dióxido de carbono', 'Planta de CO2', 'Compresor de CO2'),
(37, 'TRB-036', 140, 'Olas', 'Central undimotriz', 'Turbina undimotriz'),
(38, 'MTR-037', 40, 'Eléctrico', 'Planta 10', 'Motor de torno industrial'),
(39, 'BOM-038', 18, 'Azúcar', 'Planta de azúcar', 'Bomba de jarabe de azúcar'),
(40, 'CMP-039', 55, 'Metano', 'Planta de biogás', 'Compresor de metano'),
(41, 'TRB-040', 100, 'Nuclear', 'Central nuclear', 'Turbina de vapor nuclear'),
(42, 'MTR-041', 75, 'Eléctrico', 'Planta 11', 'Motor de molino de bolas'),
(43, 'BOM-042', 38, 'Aceite', 'Planta de aceite', 'Bomba de aceite vegetal'),
(44, 'CMP-043', 85, 'Butano', 'Planta de butano', 'Compresor de butano'),
(45, 'TRB-044', 220, 'Combustión interna', 'Generador diésel', 'Turbina de combustión interna'),
(46, 'MTR-076', 60, 'Eléctrico', 'Planta 13', 'Motor de laminador'),
(47, 'BOM-077', 29, 'Farmacéutico', 'Laboratorio', 'Bomba de líquidos estériles'),
(48, 'CMP-078', 84, 'Gases industriales', 'Planta de gases', 'Compresor de gases mixtos'),
(49, 'TRB-079', 205, 'Aeronáutica', 'Taller de aviones', 'Turbina de helicóptero'),
(50, 'MTR-080', 51, 'Eléctrico', 'Planta de madera', 'Motor de cepilladora'),
(51, 'BOM-081', 23, 'Textil', 'Planta textil', 'Bomba de tintes'),
(53, 'TRB-083', 165, 'Marina', 'Astillero', 'Turbina de barco pesquero'),
(54, 'MTR-084', 71, 'Eléctrico', 'Planta de papel', 'Motor de bobinadora'),
(55, 'BOM-085', 31, 'Químico', 'Planta de químicos', 'Bomba de ácidos'),
(56, 'CMP-086', 92, 'Gases raros', 'Planta de gases', 'Compresor de gas kriptón'),
(58, 'MTR-088', 46, 'Eléctrico', 'Planta de plásticos', 'Motor de extrusora'),
(59, 'BOM-089', 27, 'Alimentos', 'Planta de alimentos', 'Bomba de jarabe'),
(60, 'CMP-090', 63, 'Gases especiales', 'Laboratorio', 'Compresor de gas deuterio'),
(61, 'TRB-091', 145, 'Cogeneración', 'Planta de cogeneración', 'Turbina de ciclo combinado'),
(62, 'MTR-092', 66, 'Eléctrico', 'Planta de cemento', 'Motor de molino'),
(63, 'BOM-093', 34, 'Pintura', 'Planta de pintura', 'Bomba de pigmentos'),
(64, 'CMP-094', 86, 'Gases nobles', 'Planta de gases', 'Compresor de gas neón'),
(65, 'TRB-095', 175, 'Desalación', 'Planta desalinizadora', 'Turbina de osmosis inversa'),
(66, 'MTR-096', 56, 'Eléctrico', 'Planta de caucho', 'Motor de vulcanizadora'),
(67, 'BOM-097', 25, 'Adhesivos', 'Planta de adhesivos', 'Bomba de resinas'),
(68, 'CMP-098', 74, 'Gases industriales', 'Laboratorio', 'Compresor de gas argón'),
(69, 'TRB-099', 180, 'Ciclo orgánico Rankine', 'Planta de ORC', 'Turbina de expansión'),
(70, 'MTR-100', 69, 'Eléctrico', 'Planta de cerámica', 'Motor de esmaltadora'),
(98, 'TAG-Prueba', 15, 'Sistema Prueba', 'Ubicacion Prueba', 'xdfhgbfxgvb'),
(106, 'BMB-348493489', 5, 'Electrico', 'Sambil Valencia', 'ejhsfjhkfsf');

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
(1, '2025-03-11', 'Hola', 7, '2025-03-11 20:02:51', '2025-03-11 20:02:51'),
(3, '2025-03-11', 'Prueba', 47, '2025-03-11 20:08:15', '2025-03-11 20:08:15'),
(4, '2025-03-11', 'Prueba', 3, '2025-03-11 20:12:00', '2025-03-11 20:12:00'),
(5, '2025-03-11', 'Prueba', 7, '2025-03-11 20:12:00', '2025-03-11 20:12:00'),
(14, '2025-03-12', 'Test 2', NULL, '2025-03-11 22:01:45', '2025-03-11 22:01:45'),
(15, '2025-03-13', 'afsd', 106, '2025-03-24 21:12:58', '2025-03-24 21:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `inspecciones`
--

DROP TABLE IF EXISTS `inspecciones`;
CREATE TABLE IF NOT EXISTS `inspecciones` (
  `id_inspeccion` int NOT NULL AUTO_INCREMENT,
  `id_equipo` int NOT NULL,
  `tipo_equipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Motor, bomba, compresor, turbina',
  `orientacion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Vertical, horizontal',
  `vibracion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Se pudo tomar mediciones de vibracion?',
  `en_servicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Al momento de la inspeccion el equipo estaba en servicio',
  `presion_succion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Se pudo tomar mediciones de presion (succion)?',
  `presion_descarga` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Se pudo tomar mediciones de presion (descarga)?',
  `temperatura_operacion` int DEFAULT NULL COMMENT 'Fahrenheit',
  `hallazgos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `fecha_inspeccion` date NOT NULL,
  PRIMARY KEY (`id_inspeccion`),
  KEY `id_equipo` (`id_equipo`),
  KEY `fecha_inspeccion` (`fecha_inspeccion`),
  KEY `tipo_equipo` (`tipo_equipo`,`orientacion`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspecciones`
--

INSERT INTO `inspecciones` (`id_inspeccion`, `id_equipo`, `tipo_equipo`, `orientacion`, `vibracion`, `en_servicio`, `presion_succion`, `presion_descarga`, `temperatura_operacion`, `hallazgos`, `fecha_inspeccion`) VALUES
(13, 3, 'bomba', 'vertical', 'Si', 'Si', '6.0', '13.5', 115, 'Sin hallazgos.', '2023-10-26'),
(14, 4, 'compresor', 'horizontal', 'Si', 'Si', '8.5', '112.0', 225, 'Ruido dentro de los límites aceptables.', '2023-10-26'),
(15, 5, 'turbina', 'vertical', 'Si', 'Si', '155.0', '510.0', 760, 'Inspección visual sin problemas.', '2023-10-26'),
(16, 6, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 190, 'Vibración leve en el rodamiento.', '2023-10-27'),
(17, 7, 'bomba', 'vertical', 'Si', 'Si', '6.2', '13.8', 118, 'Pequeña fuga en el sello.', '2023-10-27'),
(18, 8, 'compresor', 'horizontal', 'Si', 'Si', '8.8', '115.0', 228, 'Temperatura ligeramente elevada.', '2023-10-27'),
(19, 9, 'turbina', 'vertical', 'Si', 'Si', '160.0', '520.0', 770, 'Sin anomalías detectadas.', '2023-10-27'),
(20, 10, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 188, 'Funcionamiento estable.', '2023-10-28'),
(21, 11, 'bomba', 'vertical', 'Si', 'Si', '6.1', '13.6', 116, 'Sin problemas aparentes.', '2023-10-28'),
(22, 12, 'turbina', 'vertical', 'Si', 'No', NULL, NULL, 765, 'Equipo fuera de servicio.', '2023-10-26'),
(23, 13, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 187, 'Sin datos de presión.', '2023-10-27'),
(24, 14, 'bomba', 'vertical', 'No', 'Si', NULL, '13.5', 117, 'Medición de vibración no realizada.', '2023-10-27'),
(25, 15, 'compresor', 'horizontal', 'Si', 'No', NULL, NULL, 226, 'Equipo en mantenimiento.', '2023-10-28'),
(26, 16, 'turbina', 'vertical', 'Si', 'Si', '158.0', '515.0', NULL, 'Temperatura no registrada.', '2023-10-28'),
(27, 17, 'motor', 'horizontal', 'No', 'Si', NULL, NULL, 189, 'Vibración no medida.', '2023-10-29'),
(28, 18, 'bomba', 'vertical', 'Si', 'No', NULL, NULL, 117, 'Fuera de servicio.', '2023-10-29'),
(29, 19, 'compresor', 'horizontal', 'Si', 'Si', '8.6', '113.0', NULL, 'Temperatura no disponible.', '2023-10-30'),
(30, 20, 'turbina', 'vertical', 'No', 'Si', '156.0', '512.0', 768, 'Vibración no registrada.', '2023-10-30'),
(31, 21, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 200, 'Vibración excesiva en el rodamiento trasero.', '2023-10-26'),
(32, 22, 'bomba', 'vertical', 'Si', 'Si', '7.0', '15.0', 130, 'Fuga de aceite significativa.', '2023-10-26'),
(33, 23, 'compresor', 'horizontal', 'Si', 'Si', '9.5', '120.0', 240, 'Ruido anormal y temperatura elevada.', '2023-10-27'),
(34, 24, 'turbina', 'vertical', 'Si', 'Si', '170.0', '530.0', 780, 'Desgaste en las paletas.', '2023-10-27'),
(35, 25, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 210, 'Desalineación del eje.', '2023-10-28'),
(36, 26, 'bomba', 'vertical', 'Si', 'Si', '7.5', '16.0', 140, 'Cavitación detectada.', '2023-10-28'),
(37, 27, 'compresor', 'horizontal', 'Si', 'Si', '10.0', '125.0', 250, 'Fugas de aire en las juntas.', '2023-10-29'),
(38, 28, 'turbina', 'vertical', 'Si', 'Si', '180.0', '540.0', 790, 'Corrosión en la carcasa.', '2023-10-29'),
(39, 29, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 220, 'Rodamiento dañado.', '2023-10-30'),
(40, 30, 'bomba', 'vertical', 'Si', 'Si', '8.0', '17.0', 150, 'Desgaste en el impulsor.', '2023-10-30'),
(41, 31, 'motor', 'vertical', 'Si', 'Si', NULL, NULL, 195, 'Funcionamiento normal.', '2023-10-26'),
(42, 32, 'bomba', 'horizontal', 'Si', 'Si', '6.5', '14.0', 120, 'Sin hallazgos.', '2023-10-26'),
(43, 33, 'compresor', 'vertical', 'Si', 'Si', '9.0', '118.0', 235, 'Ruido moderado.', '2023-10-27'),
(44, 34, 'turbina', 'horizontal', 'Si', 'Si', '165.0', '525.0', 775, 'Inspección visual completa.', '2023-10-27'),
(45, 35, 'motor', 'vertical', 'Si', 'Si', NULL, NULL, 205, 'Vibración en la base.', '2023-10-28'),
(46, 36, 'bomba', 'horizontal', 'Si', 'Si', '6.8', '14.5', 125, 'Pequeña fuga en la conexión.', '2023-10-28'),
(47, 37, 'compresor', 'vertical', 'Si', 'Si', '9.3', '122.0', 238, 'Temperatura estable.', '2023-10-29'),
(48, 38, 'turbina', 'horizontal', 'Si', 'Si', '175.0', '535.0', 785, 'Sin problemas aparentes.', '2023-10-29'),
(49, 39, 'motor', 'vertical', 'Si', 'Si', NULL, NULL, 198, 'Funcionamiento sin problemas.', '2023-10-30'),
(50, 40, 'bomba', 'horizontal', 'Si', 'Si', '6.6', '14.2', 122, 'Inspección de rutina.', '2023-10-30'),
(51, 41, 'compresor', 'vertical', 'Si', 'Si', '9.1', '120.0', 236, 'Sin hallazgos.', '2023-10-25'),
(52, 42, 'turbina', 'horizontal', 'Si', 'Si', '170.0', '530.0', 780, 'Inspección visual.', '2023-10-24'),
(53, 43, 'motor', 'vertical', 'Si', 'Si', NULL, NULL, 202, 'Vibración leve.', '2023-10-23'),
(54, 44, 'bomba', 'horizontal', 'Si', 'Si', '6.7', '14.3', 123, 'Sin problemas.', '2023-10-22'),
(55, 45, 'compresor', 'vertical', 'Si', 'Si', '9.2', '121.0', 237, 'Ruido normal.', '2023-10-21'),
(56, 46, 'turbina', 'horizontal', 'Si', 'Si', '172.0', '532.0', 782, 'Inspección completa.', '2023-10-20'),
(57, 47, 'motor', 'vertical', 'Si', 'Si', NULL, NULL, 204, 'Funcionamiento estable.', '2023-10-19'),
(58, 48, 'bomba', 'horizontal', 'Si', 'Si', '6.9', '14.6', 124, 'Sin hallazgos.', '2023-10-18'),
(59, 49, 'compresor', 'vertical', 'Si', 'Si', '9.4', '123.0', 239, 'Temperatura constante.', '2023-10-17'),
(60, 50, 'turbina', 'horizontal', 'Si', 'Si', '174.0', '534.0', 784, 'Inspección de rutina.', '2023-10-16'),
(61, 51, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 250, 'Sobrecalentamiento.', '2023-10-26'),
(63, 53, 'compresor', 'horizontal', 'Si', 'Si', '12.0', '150.0', 300, 'Ruido excesivo y vibración.', '2023-10-27'),
(64, 54, 'turbina', 'vertical', 'Si', 'Si', '200.0', '600.0', 850, 'Fugas de vapor.', '2023-10-27'),
(65, 55, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 150, 'Funcionamiento inestable.', '2023-10-28'),
(66, 56, 'bomba', 'vertical', 'Si', 'Si', '3.0', '8.0', 80, 'Baja presión y temperatura.', '2023-10-28'),
(68, 58, 'turbina', 'vertical', 'Si', 'Si', '100.0', '400.0', 600, 'Funcionamiento normal en condiciones bajas.', '2023-10-29'),
(69, 59, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 300, 'Parada por sobretemperatura.', '2023-10-30'),
(70, 60, 'bomba', 'vertical', 'Si', 'Si', '15.0', '25.0', 200, 'Presión y temperatura muy altas.', '2023-10-30'),
(71, 61, 'compresor', 'horizontal', 'No', 'Si', '8.9', '117.0', 224, 'Vibración no medida debido a la ubicación.', '2023-10-31'),
(72, 62, 'turbina', 'vertical', 'Si', 'No', '168.0', '528.0', 778, 'Equipo en parada programada.', '2023-11-01'),
(73, 63, 'motor', 'horizontal', 'Si', 'Si', NULL, NULL, 197, 'Ruido de rodamiento leve.', '2023-11-02'),
(74, 64, 'bomba', 'vertical', 'Si', 'Si', '6.4', '14.1', 121, 'Pequeña fuga en la brida.', '2023-11-03'),
(75, 65, 'compresor', 'horizontal', 'Si', 'Si', '9.6', '124.0', 241, 'Filtro de aire sucio.', '2023-11-04'),
(76, 66, 'turbina', 'vertical', 'Si', 'Si', '176.0', '536.0', 786, 'Inspección visual y audible.', '2023-11-05'),
(77, 67, 'motor', 'horizontal', 'No', 'Si', NULL, NULL, 203, 'Vibración no medida por seguridad.', '2023-11-06'),
(78, 68, 'bomba', 'vertical', 'Si', 'Si', '7.1', '14.8', 126, 'Sellado de la bomba en buen estado.', '2023-11-07'),
(79, 69, 'compresor', 'horizontal', 'Si', 'No', '9.8', '126.0', 243, 'Equipo en mantenimiento correctivo.', '2023-11-08'),
(80, 70, 'turbina', 'vertical', 'Si', 'Si', '178.0', '538.0', 788, 'Inspección con termografía.', '2023-11-09'),
(84, 98, 'Motor', 'Horizontal', 'Si', 'Si', 'Si', 'Si', 54, 'fuga_producto_tuberia, basura_area, dfhggbhcv', '2025-03-10'),
(88, 106, 'Bomba', 'Horizontal', 'Si', 'Si', 'Si', 'Si', 343, NULL, '2025-03-23'),
(89, 98, 'Motor', 'Horizontal', 'Si', 'Si', 'Si', 'Si', 55, NULL, '2025-03-24');

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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspeccion_fallas`
--

INSERT INTO `inspeccion_fallas` (`id`, `id_inspeccion`, `id_falla`) VALUES
(96, 88, 2),
(97, 88, 3),
(98, 88, 4),
(99, 89, 2),
(100, 89, 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inspeccion_hallazgos`
--

INSERT INTO `inspeccion_hallazgos` (`id`, `id_inspeccion`, `id_hallazgo`) VALUES
(13, 88, 1),
(14, 88, 2),
(15, 88, 3),
(16, 89, 16),
(17, 89, 17);

-- --------------------------------------------------------

--
-- Table structure for table `lista_fallas`
--

DROP TABLE IF EXISTS `lista_fallas`;
CREATE TABLE IF NOT EXISTS `lista_fallas` (
  `id_falla` int NOT NULL AUTO_INCREMENT,
  `falla` varchar(150) NOT NULL,
  PRIMARY KEY (`id_falla`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(35, 'testing'),
(36, 'test 2');

-- --------------------------------------------------------

--
-- Table structure for table `lista_hallazgos`
--

DROP TABLE IF EXISTS `lista_hallazgos`;
CREATE TABLE IF NOT EXISTS `lista_hallazgos` (
  `id_hallazgo` int NOT NULL AUTO_INCREMENT,
  `hallazgo` varchar(150) NOT NULL,
  PRIMARY KEY (`id_hallazgo`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(22, 'testing'),
(26, 'test 2');

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
