CREATE DATABASE confesioneslcb;

DROP DATABASE confesioneslcb;

USE confesioneslcb;

CREATE TABLE
    `usuarios` (
        `user_id` INT PRIMARY KEY AUTO_INCREMENT,
        `email` VARCHAR(100) UNIQUE NOT NULL,
        `password` VARCHAR(50) NOT NULL,
        `nombre` VARCHAR(50) NOT NULL,
        `apellido` VARCHAR(50) NOT NULL,
        `fecha_nacimiento` DATE NOT NULL,
        `genero_id` INT NOT NULL,
        `universidad_id` INT NOT NULL,
        CONSTRAINT fk_usuario_genero FOREIGN KEY (genero_id) REFERENCES generos (genero_id) ON UPDATE CASCADE ON DELETE RESTRICT,
        CONSTRAINT fk_usuario_universidad FOREIGN KEY (universidad_id) REFERENCES universidades (universidad_id) ON UPDATE CASCADE ON DELETE RESTRICT
    );

CREATE TABLE
    `generos` (
        `genero_id` INT PRIMARY KEY AUTO_INCREMENT,
        `genero` VARCHAR(10)
    );

CREATE TABLE
    `universidades` (
        `universidad_id` INT PRIMARY KEY AUTO_INCREMENT,
        `universidad` VARCHAR(255),
        `ciudad` VARCHAR(50),
        `pais` VARCHAR(50)
    );

CREATE TABLE
    `publicaciones` (
        `publicacion_id` INT PRIMARY KEY,
        `publicacion` TEXT,
        `user_id` INT NOT NULL,
        CONSTRAINT fk_publicaciones_usuarios FOREIGN KEY (user_id) REFERENCES usuarios (user_id) ON UPDATE CASCADE ON DELETE CASCADE
    );

CREATE TABLE
    `notificaciones` (
        `notificacion_id` INT PRIMARY KEY,
        `publicacion_id` INT NOT NULL,
        CONSTRAINT fk_notificacion_publicacion FOREIGN KEY (publicacion_id) REFERENCES publicaciones (publicacion_id) ON UPDATE CASCADE ON DELETE CASCADE
    );

INSERT INTO
    universidades (`universidad`, `ciudad`, `pais`)
VALUES
    (
        'Universidad de Alcalá (UAH)',
        'Alcalá de Henares',
        'España'
    ),
    (
        'Universidad Autónoma de Madrid (UAM)',
        'Madrid',
        'España'
    ),
    (
        'Universidad Carlos III de Madrid (UC3M)',
        'Getafe, Leganés, Colmenar Viejo',
        'España'
    ),
    (
        'Universidad Complutense de Madrid (UCM)',
        'Madrid',
        'España'
    ),
    (
        'Universidad Politécnica de Madrid (UPM)',
        'Madrid',
        'España'
    ),
    (
        'Universidad Rey Juan Carlos (URJC)',
        'Móstoles, Alcorcón, Fuenlabrada, Aranjuez',
        'España'
    ),
    (
        'Universidad Nacional de Educación a Distancia (UNED)',
        'Madrid',
        'España'
    ),
    (
        'Universidad Alfonso X el Sabio (UAX)',
        'Villanueva de la Cañada',
        'España'
    ),
    (
        'Universidad Antonio de Nebrija',
        'Madrid',
        'España'
    ),
    (
        'Universidad Camilo José Cela (UCJC)',
        'Madrid',
        'España'
    ),
    ('Universidad CEU San Pablo', 'Madrid', 'España'),
    (
        'Universidad Europea de Madrid (UEM)',
        'Villaviciosa de Odón',
        'España'
    ),
    (
        'Universidad Francisco de Vitoria (UFV)',
        'Pozuelo de Alarcón',
        'España'
    ),
    (
        'Universidad de Diseño, Innovación y Tecnología (UDIT)',
        'Madrid',
        'España'
    ),
    (
        'Universidad Internacional Villanueva',
        'Madrid',
        'España'
    ),
    (
        'Universidad Pontificia Comillas',
        'Madrid',
        'España'
    );


INSERT INTO
    generos (`genero`)
VALUES
    ('Mujer'),
    ('Hombre'),
    ('Otro');

INSERT INTO
    usuarios (
        `email`,
        `password`,
        `nombre`,
        `apellido`,
        `fecha_nacimiento`,
        `genero_id`,
        `universidad_id`
    )
VALUES
    (
        'sergioromero2k@gmail.com',
        'pepe2321',
        'Sergio Alejandro',
        'Romero López',
        '2000-11-10',
        2,
        6
    );

