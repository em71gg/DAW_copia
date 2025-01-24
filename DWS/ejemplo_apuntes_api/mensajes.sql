-- Adminer 4.8.1 MySQL 10.11.6-MariaDB-0+deb12u1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `mensaje` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mensajes` (`id`, `nombre`, `email`, `mensaje`) VALUES
(1,	'nombre01',	'correo01@email.com',	'Hola mundo'),
(2,	'nombre01',	'correo01@email.com',	'Adios'),
(3,	'Otro Nombre',	'otrocorreo@email.com',	'El cuerpo de otro mensaje.'),
(5,	'Nombre modificado',	'miemail@email.com',	'El cuerpo del mensaje.');

-- 2025-01-16 20:36:52
