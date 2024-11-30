-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2024 a las 18:54:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pseduca`
--
CREATE DATABASE IF NOT EXISTS `pseduca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pseduca`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE IF NOT EXISTS `miembros` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nombre` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `descripcion` text NOT NULL,
    `link_aportaciones_externo` varchar(255) NOT NULL,
    `imagen` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nombre_usuario` varchar(255) NOT NULL,
    `nombre_completo` varchar(255) DEFAULT NULL,
    `contrasenha` varchar(255) NOT NULL,
    `rol` enum('ADMIN_GLOBAL','GESTOR_CATALOGO','USUARIO_PYP') NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `usuarios_pk` (`nombre_usuario`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE user 'pseduca'@'%' IDENTIFIED BY 'pseduca';
GRANT ALL PRIVILEGES ON `pseduca`.* TO 'pseduca'@'%';


-- Creamos un usuario de prueba para poder acceder (usuario: root, contraseña: root):
INSERT INTO pseduca.usuarios (nombre_usuario, nombre_completo, contrasenha, rol, ultima_modificacion_contrasenha)
VALUES ('root', 'root', '$2y$10$3dwzAqQpFH9ccBgbo63ovemm0L2GXesMveo4lm4l6Q6ZD59ZURRG.', 'ADMIN_GLOBAL', '2024-11-29 01:02:07');