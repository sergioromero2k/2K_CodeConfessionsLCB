CREATE DATABASE confesioneslcb;

USE confesioneslcb;

CREATE TABLE
    `usuarios` (
        `email` VARCHAR(255) PRIMARY KEY,
        `password` VARCHAR(50) NOT NULL,
        `nombre` VARCHAR(50) NOT NULL,
        `apellido` VARCHAR(50) NOT NULL,
        `fecha_nacimiento` DATE NOT NULL,
        FOREIGN KEY (genero_id) REFERENCES generos (genero_id) ON UPDATE CASCADE ON DELETE RESTRICT,
        FOREIGN KEY (universidad_id) REFERENCES universidades (universidad_id) ON UPDATE CASCADE ON DELETE RESTRICT
    )
CREATE TABLE
    `generos` (
        `genero_id` INT PRIMARY KEY AUTO_INCREMENT,
        `genero` VARCHAR(10)
    )
CREATE TABLE
    `universidades` (
        `universidad_id` INT PRIMARY KEY AUTO_INCREMENT,
        `universidad` VARCHAR(255),
        `ciudad` VARCHAR(50),
        `pais` VARCHAR(50),
    )
CREATE TABLE
    `publicaciones` (
        `publicacion_id` INT PRIMARY KEY,
        `publicacion` TEXT,
        FOREIGN KEY (email) REFERENCES usuarios (email) ON UPDATE CASCADE ON DELETE CASCADE
    )
CREATE TABLE
    `notificaciones` (
        `notificacion_id` INT PRIMARY KEY,
        FOREIGN KEY (publicacion_id) REFERENCES publicaciones (publicacion_id) ON UPDATE CASCADE ON DELETE CASCADE
    )

    