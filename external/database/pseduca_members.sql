
USE `pseduca`;

START TRANSACTION;

-- Modificamos la tabla miembros para que los campos email, descripcion, link_aportaciones_externo e imagen puedan ser nulos:
ALTER TABLE `miembros`
    CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    CHANGE `descripcion` `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    CHANGE `link_aportaciones_externo` `link_aportaciones_externo` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    CHANGE `imagen` `imagen` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

-- Insertar la tabla necesaria para formación
CREATE TABLE `pseduca`.`items_formacion` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `titulo` VARCHAR(255) NOT NULL ,
    `descripcion` VARCHAR(1023) NOT NULL ,
    `imagen` VARCHAR(255) NULL ,
    `link_externo` VARCHAR(255) NOT NULL ,
    `anho_inicio` INT NOT NULL ,
    `anho_fin` INT NULL ,
    `tipo` ENUM('MASTER','CURSO','DOCTORADO') NOT NULL ,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


COMMIT;

-- Insertamos los miembros que nos han enviado:
INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Ángeles Conde Rodriguez', 'angelesconde@uvigo.gal', 'Doctora en Psicopedagogía y Máster en Educación y TIC. Profesora titular de Psicología y de la Educación. Coordinadora del Máster en Necesidades Específicas de Apoyo Educativo. Actualmente participa en investigaciones del grupo HI9- GiPEDUvi sobre procesos cognitivo-motivacionales y habilidades instrumentales y sobre expectativas y vivencias de estudiantes universitarios en colaboración con universidades portuguesas.', 'https://portalcientifico.uvigo.gal/investigadores/277897/detalle', '8b2ff576-1abd-4371-9a69-59df0d6b8ecc.png');

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Valentín Iglesias Sarmiento', 'visarmiento@uvigo.gal', 'Valentín Iglesias-Sarmiento, profesor en la Universidade de Vigo, es doctor en Psicología y máster en Intervención Psicológica en Desarrollo y Educación. Su investigación se centra en los precursores del rendimiento académico, la cognición matemática, el desarrollo típico y atípico en el aula y las dificultades de aprendizaje. Ha liderado y participado activamente en diferentes proyectos competitivos y cuenta con diversas publicaciones en revistas de impacto.', 'https://portalcientifico.uvigo.gal/investigadores/277754/detalle', 'd7a90f96-25ff-4acd-94d1-d7b580e1d26b.png');

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Salvador G. González González', 'salva@uvigo.gal', 'Doctor en Psicología. Profesor Titular en el área de Psicología Evolutiva y de la Educación. Su actividad investigadora comienza con una serie de publicaciones en torno al ámbito de la educación para la salud y la prevención de la iniciación en la conducta de fumar y de otras drogodependencias en el contexto de la educación obligatoria. En una segunda etapa participa en  trabajos y proyectos sobre la motivación escolar y estrategias de aprendizaje, sobre los que se han realizado una serie de publicaciones de diverso impacto.', 'https://portalcientifico.uvigo.gal/investigadores/277632/detalle',  null);

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Mar García Señorán', 'msenoran@uvigo.gal', 'Doctora en Psicología. Profesora Titular en el área de Psicología Evolutiva y de la Educación. Coordinadora del Máster en Dificultades de Aprendizaje y Procesos Cognitivas y del Grupo de Investigación HI9- GiPEDUvi. Actualmente su investigación se centra en los procesos cognitivo-motivaciones implicados en el aprendizaje escolar. También participa en el estudio de las expectativas de estudiantes universitarios en colaboración con universidades portuguesas.', 'https://portalcientifico.uvigo.gal/investigadores/277356/detalle', 'c3336430-b211-46a4-98b3-6509d487c308.png');

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Martina Ares Ferreirós', 'mares@uvigo.gal', 'Martina Ares-Ferreirós es profesora ayudante doctora en la Universidad de Vigo en el Departamento de Psicología Evolutiva y Comunicación. Imparte docencia en los grados de educación infantil y primaria, y el los Masters en Necesidades Específicas de Apoyo Educativo y del profesorado. En el campo de la investigación se ha centrado en el estudio de los procesos cognitivos relacionados con el aprendizaje de la lectura e investigaciones en el campo de la innovación docente a través de la metodología cooperativa.', 'https://orcid.org/0000-0003-3871-1644', null);

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Mónica Rodríguez Enríquez', 'monica.rodriguez.enriquez@uvigo.gal', 'Psicóloga clínica (vía PIR) y doctora en Salud Pública, con experiencia en intervención infantojuvenil, docencia e investigación. Ha trabajado en hospitales referentes como Sant Joan de Déu, actualmente es docente en la Universidad de Vigo e investiga sobre acoso escolar.', 'https://portalcientifico.uvigo.gal/investigadores/874995/detalle', 'e9bf3b7a-c7bb-40e1-b4dc-d608a712cb60.png');

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Sonia Alfonso Gil', 'soalgi@uvigo.gal', 'Doctora en Psicopedagogía. Profesora en la Universidad de Vigo, Departamento de Psicología Evolutiva y Comunicación, área de Psicología de la Educación y miembro del Grupo de Investigación HI9-GiPEDUvi. Desde 2009 su investigación se centra en la evaluación e intervención de los trastornos del aprendizaje y del desarrollo en niños y niñas en edad escolar. Desde 2011, en colaboración con las Universidades de Minho y Évora (Portugal), estudia y analiza variables personales y contextuales relacionadas con el éxito y abandono académico en estudiantes universitarios de primer año.', 'https://portalcientifico.uvigo.gal/investigadores/277814/detalle', 'c1c8b56a-ad0d-4899-90ad-8a0092309530.png');

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Fernando Tellado González', 'ftellado@uvigo.gal', 'Doctor en Psicopedagogía. Profesor Titular en el área de Psicología Evolutiva y de la Educación y miembro del Grupo de Investigación HI9-GiPEDUvi en el que desarrolla líneas de investigación relacionadas con la evaluación e intervención cognitiva en las dificultades de aprendizaje lógico-matemátíco.', 'https://portalcientifico.uvigo.gal/investigadores/277629/detalle', null);

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Juan Luis Rodríguez Rodríguez', 'juanluisrr@uvigo.gal', 'Profesor Asociado en la Universidad de Vigo, Departamento de Psicología Evolutiva y Comunicación, área de Psicología de la Educación y miembro del Grupo de Investigación HI9-GiPEDUvi en el que desarrolla líneas de investigación relacionadas con los procesos cognitivos, atención a la diversidad y orientación educativa. Compagina su labor como Orientador Educativo en las etapas de Educación Infantil, Primaria y Secundaria.', 'https://orcid.org/0000-0003-4505-5278', null);

INSERT INTO pseduca.miembros (nombre, email, descripcion, link_aportaciones_externo, imagen)
VALUES ('Joaquín Dosil Díaz', 'jdosil@uvigo.gal', 'Doctor en Psicología. Profesor en la Universidad de Vigo. Ha publicado 21 libros de referencia internacional en “Psicología de la actividad física y del deporte” y más de 100 artículos científicos y capítulos de libro. Ha dirigido más de 20 tesis doctorales. Ha sido director de varios Proyectos de Investigación con el Ministerio de Cultura y Deporte. Ha asesorado a varios campeones del mundo, campeones olímpicos y campeones de Europa de varias disciplinas.', 'https://portalcientifico.uvigo.gal/investigadores/277553/detalle', '2e75ccd3-e610-4a55-bd04-3e8bc3745991.jpg');