
USE `pseduca`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Apartado divulgación

CREATE TABLE `pseduca`.`items_divulgacion` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `titulo` VARCHAR(255) NOT NULL ,
    `descripcion` VARCHAR(1023) NOT NULL ,
    `ultima_actualizacion` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `imagen` VARCHAR(255) NULL ,
    `tipo_item` ENUM('PAGINA_INTERNA','FICHERO_INTERNO','LINK_EXTERNO') NOT NULL ,
    `link_externo` VARCHAR(1023) NULL ,
    `fichero` VARCHAR(255) NULL ,
    `pagina_detalle` TEXT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Apartado catálogo

CREATE TABLE `aplicacion_items_catalogo` (
                                             `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `areas_items_catalogo` (
                                        `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `enlaces_items_catalogo` (
                                          `id` int(11) NOT NULL,
                                          `id_item_catalogo` int(11) NOT NULL,
                                          `nombre` varchar(255) DEFAULT NULL,
                                          `enlace` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `etiquetas_items_catalogo` (
                                            `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ficheros_items_catalogo` (
                                           `id` int(11) NOT NULL,
                                           `id_item_catalogo` int(11) NOT NULL,
                                           `nombre` varchar(255) DEFAULT NULL,
                                           `nombre_fichero` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `formatos_items_catalogo` (
                                          `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `items_catalogo` (
                                  `id` int(11) NOT NULL,
                                  `acronimo` varchar(255) NOT NULL,
                                  `nombre` varchar(1023) NOT NULL,
                                  `imagen` varchar(255) DEFAULT NULL,
                                  `edad_mes_min` int(11) NOT NULL,
                                  `edad_anho_min` int(11) NOT NULL,
                                  `edad_mes_max` int(11) NOT NULL,
                                  `edad_anho_max` int(11) NOT NULL,
                                  `autores` varchar(255) NOT NULL,
                                  `duracion` int(11) NOT NULL,
                                  `descripcion` text NOT NULL,
                                  `observacion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relacion_catalogo_aplicacion` (
                                                `nombre` varchar(50) NOT NULL,
                                                `id_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relacion_catalogo_areas` (
                                           `nombre` varchar(50) NOT NULL,
                                           `id_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relacion_catalogo_etiquetas` (
                                               `nombre` varchar(50) NOT NULL,
                                               `id_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relacion_catalogo_formatos` (
                                              `nombre` varchar(50) NOT NULL,
                                              `id_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `relacion_catalogo_tipos_recurso` (
                                                   `nombre` varchar(50) NOT NULL,
                                                   `id_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tipos_recurso_items_catalogo` (
                                                `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `aplicacion_items_catalogo`
    ADD PRIMARY KEY (`nombre`);

ALTER TABLE `areas_items_catalogo`
    ADD PRIMARY KEY (`nombre`);

ALTER TABLE `enlaces_items_catalogo`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_item_catalogo` (`id_item_catalogo`);

ALTER TABLE `etiquetas_items_catalogo`
    ADD PRIMARY KEY (`nombre`);

ALTER TABLE `ficheros_items_catalogo`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_item_catalogo` (`id_item_catalogo`);

ALTER TABLE formatos_items_catalogo
    ADD PRIMARY KEY (`nombre`);

ALTER TABLE `items_catalogo`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `relacion_catalogo_aplicacion`
    ADD PRIMARY KEY (`nombre`,`id_item`),
    ADD KEY `id_item` (`id_item`);

ALTER TABLE `relacion_catalogo_areas`
    ADD PRIMARY KEY (`nombre`,`id_item`),
    ADD KEY `id_item` (`id_item`);

ALTER TABLE `relacion_catalogo_etiquetas`
    ADD PRIMARY KEY (`nombre`,`id_item`),
    ADD KEY `id_item` (`id_item`);

ALTER TABLE `relacion_catalogo_formatos`
    ADD PRIMARY KEY (`nombre`,`id_item`),
    ADD KEY `id_item` (`id_item`);

ALTER TABLE `relacion_catalogo_tipos_recurso`
    ADD PRIMARY KEY (`nombre`,`id_item`),
    ADD KEY `id_item` (`id_item`);

ALTER TABLE `tipos_recurso_items_catalogo`
    ADD PRIMARY KEY (`nombre`);


ALTER TABLE `enlaces_items_catalogo`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ficheros_items_catalogo`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `items_catalogo`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `enlaces_items_catalogo`
    ADD CONSTRAINT `enlaces_items_catalogo_ibfk_1` FOREIGN KEY (`id_item_catalogo`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ficheros_items_catalogo`
    ADD CONSTRAINT `ficheros_items_catalogo_ibfk_1` FOREIGN KEY (`id_item_catalogo`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `relacion_catalogo_aplicacion`
    ADD CONSTRAINT `relacion_catalogo_aplicacion_ibfk_1` FOREIGN KEY (`nombre`) REFERENCES `aplicacion_items_catalogo` (`nombre`),
    ADD CONSTRAINT `relacion_catalogo_aplicacion_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `relacion_catalogo_areas`
    ADD CONSTRAINT `fk_relacion_catalogo_areas_areas_items_catalogo` FOREIGN KEY (`nombre`) REFERENCES `areas_items_catalogo` (`nombre`),
    ADD CONSTRAINT `relacion_catalogo_areas_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `relacion_catalogo_etiquetas`
    ADD CONSTRAINT `fk_relacion_catalogo_etiquetas_etiquetas_items_catalogo` FOREIGN KEY (`nombre`) REFERENCES `etiquetas_items_catalogo` (`nombre`),
    ADD CONSTRAINT `relacion_catalogo_etiquetas_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `relacion_catalogo_formatos`
    ADD CONSTRAINT `relacion_catalogo_formatos_ibfk_1` FOREIGN KEY (`nombre`) REFERENCES formatos_items_catalogo (`nombre`),
    ADD CONSTRAINT `relacion_catalogo_formatos_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `relacion_catalogo_tipos_recurso`
    ADD CONSTRAINT `relacion_catalogo_tipos_recurso_ibfk_1` FOREIGN KEY (`nombre`) REFERENCES `tipos_recurso_items_catalogo` (`nombre`),
    ADD CONSTRAINT `relacion_catalogo_tipos_recurso_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `items_catalogo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Inserción de datos iniciales en las tablas anexas a catálogo

INSERT INTO `areas_items_catalogo` (`nombre`) VALUES ('Aprendizaje');
INSERT INTO `areas_items_catalogo` (`nombre`) VALUES ('Desarrollo');
INSERT INTO `areas_items_catalogo` (`nombre`) VALUES ('NEAE (Necesidades Específicas de Apoyo Educativo)');

INSERT INTO `tipos_recurso_items_catalogo` (`nombre`) VALUES ('Evaluación');
INSERT INTO `tipos_recurso_items_catalogo` (`nombre`) VALUES ('Intervención');

INSERT INTO formatos_items_catalogo (`nombre`) VALUES ('Papel');
INSERT INTO formatos_items_catalogo (`nombre`) VALUES ('Online');

INSERT INTO `aplicacion_items_catalogo` (`nombre`) VALUES ('Individual');
INSERT INTO `aplicacion_items_catalogo` (`nombre`) VALUES ('Colectiva');

-- Apartado Pruebas y programas (PyP)

CREATE TABLE `items_pyp` (
                             `id` int(11) NOT NULL,
                             `titulo` varchar(255) NOT NULL,
                             `descripcion` text DEFAULT NULL,
                             `imagen` varchar(255) DEFAULT NULL,
                             `link_externo` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `items_pyp`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `items_pyp`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `autorizaciones_usuarios_items_pyp` (
                                                     `id_usuario` int(11) NOT NULL,
                                                     `id_item_pyp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `autorizaciones_usuarios_items_pyp`
    ADD PRIMARY KEY (`id_usuario`,`id_item_pyp`),
    ADD KEY `id_item_pyp` (`id_item_pyp`);

ALTER TABLE `autorizaciones_usuarios_items_pyp`
    ADD CONSTRAINT `autorizaciones_usuarios_items_pyp_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `autorizaciones_usuarios_items_pyp_ibfk_2` FOREIGN KEY (`id_item_pyp`) REFERENCES `items_pyp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;