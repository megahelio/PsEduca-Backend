
USE `pseduca`;

START TRANSACTION;

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

COMMIT;