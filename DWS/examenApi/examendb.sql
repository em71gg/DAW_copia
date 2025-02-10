-- Adminer 4.8.1 MySQL 10.11.6-MariaDB-0+deb12u1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `edad` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `alumnos` (`id`, `nombre`, `apellidos`, `edad`) VALUES
(1,	'María',	'López Hernández',	10),
(2,	'Mónica',	'Fernández Paniagua',	11),
(3,	'Beatriz',	'Fernández León',	12),
(4,	'Rosario',	'Pérez Alonso',	10),
(5,	'Carmen',	'García Moran',	10),
(6,	'Nuria',	'Pastor Meneses',	10),
(7,	'Elena',	'Montaño Moreno',	11),
(8,	'Daniel',	'Hernández Muñoz',	11),
(9,	'Pedro',	'García Hidalgo',	13),
(10,	'Francisco',	'Cabrera González',	13),
(11,	'Ángel',	'Álvarez González',	13),
(12,	'Francisca',	'López Barroso',	10),
(13,	'Mireia',	'Alarcón Quero',	13),
(14,	'María José',	'Fernández Moreno',	13),
(15,	'Juan',	'Rodríguez García',	10),
(16,	'Estibaliz',	'Mirón Márquez',	12),
(17,	'Ana María',	'Tapia Valera',	10),
(18,	'Vicente',	'Gutiérrez Fernández',	11),
(19,	'Francisca',	'Linares Martí',	10),
(20,	'María',	'López Afonso',	10),
(21,	'María Carmen',	'Vázquez San Martin',	12),
(22,	'Julia',	'Jiménez Fernández',	13),
(23,	'Antonia',	'Andrade Fernández',	11),
(24,	'Andrés',	'Cervantes Santos',	12),
(25,	'Sergio',	'Rodríguez Ros',	10),
(26,	'Iván',	'Aguilar Bermúdez',	13),
(27,	'Juan',	'Aranda Riera',	12),
(28,	'Francisca',	'Martin Castro',	12),
(29,	'Antonio',	'Barrera Fernández',	11),
(30,	'Maider',	'Pujol Yepes',	12),
(31,	'Antonia',	'Fernández Castillo',	10),
(32,	'Rosa María',	'Manrique Montero',	11),
(33,	'Ricardo',	'Cañada Fernández',	12),
(34,	'Manuel',	'Viñas Carrera',	11),
(35,	'Juan José',	'Quesada García',	10),
(36,	'Paula',	'Arredondo Carrasco',	13),
(37,	'David',	'Sánchez Martin',	12),
(38,	'Raúl',	'Martínez Uceda',	10),
(39,	'Antonia',	'Rubio Hernández',	13),
(40,	'Carlos',	'Carmona Garate',	11),
(41,	'María Teresa',	'Lozano Ramón',	11),
(42,	'Alberto',	'Alvarado Ruiz',	11),
(43,	'Javier',	'Sánchez Mesas',	12),
(44,	'Manuel',	'Álvarez Gallardo',	12),
(45,	'Sergio',	'Valls Espinoza',	12),
(46,	'Ramón',	'Muñoz Rodríguez',	13),
(47,	'Joaquín',	'Maldonado Neira',	13),
(48,	'Manuel',	'Fernández Rojo',	11),
(49,	'Josefa',	'Rivas Fernández',	13),
(50,	'María Carmen',	'Hernández Galán',	12);

DROP TABLE IF EXISTS `clases`;
CREATE TABLE `clases` (
  `extraescolar_id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  UNIQUE KEY `extraescolar_id` (`extraescolar_id`,`alumno_id`),
  KEY `alumno_id` (`alumno_id`),
  CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`),
  CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`extraescolar_id`) REFERENCES `extraescolar` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `clases` (`extraescolar_id`, `alumno_id`) VALUES
(1,	1),
(1,	5),
(1,	6),
(1,	21),
(1,	22),
(1,	26),
(1,	47),
(2,	2),
(2,	7),
(2,	9),
(2,	18),
(2,	19),
(2,	20),
(2,	23),
(2,	27),
(2,	42),
(2,	48),
(3,	4),
(3,	10),
(3,	13),
(3,	24),
(3,	30),
(3,	33),
(3,	39),
(3,	41),
(3,	45),
(3,	46),
(4,	3),
(4,	8),
(4,	12),
(4,	16),
(4,	25),
(4,	37),
(4,	38),
(4,	40),
(4,	43),
(4,	44),
(4,	49),
(6,	15),
(6,	28),
(6,	32),
(6,	35),
(6,	50);

DROP TABLE IF EXISTS `extraescolar`;
CREATE TABLE `extraescolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `plazas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `extraescolar` (`id`, `nombre`, `plazas`) VALUES
(1,	'Robótica',	8),
(2,	'Baloncesto',	10),
(3,	'Inglés B2',	12),
(4,	'Inglés A2',	12),
(5,	'Manualidades',	10),
(6,	'Yoga',	10),
(7,	'Informática',	12);

DROP TABLE IF EXISTS `ubicacion`;
CREATE TABLE `ubicacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `extraescolar_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `extraescolar_id` (`extraescolar_id`),
  CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`extraescolar_id`) REFERENCES `extraescolar` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ubicacion` (`id`, `nombre`, `extraescolar_id`) VALUES
(1,	'Laboratorio',	1),
(2,	'Pabellón',	2),
(3,	'P035',	3),
(4,	'P120',	4),
(5,	'Taller',	5);

-- 2025-02-10 16:10:05
