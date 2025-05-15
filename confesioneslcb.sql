CREATE DATABASE confesioneslcb;
-- DROP DATABASE confesioneslcb;

USE confesioneslcb;

# Tabla de usuarios
CREATE TABLE
    `usuarios` (
        `user_id` INT PRIMARY KEY AUTO_INCREMENT,
        `email` VARCHAR(100) UNIQUE NOT NULL,
        `password` VARCHAR(50) NOT NULL,
        `nombre` VARCHAR(50) NOT NULL,
        `apellido` VARCHAR(50) NOT NULL,
        `fecha_nacimiento` DATE NOT NULL,
        `verificado` TINYINT(1) DEFAULT 0,
        `token_activacion` VARCHAR(255),
        `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP
        `actualizado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAM   
        `genero_id` INT NOT NULL,
        `universidad_id` INT NOT NULL,
        CONSTRAINT fk_usuario_genero FOREIGN KEY (genero_id) REFERENCES generos (genero_id) ON UPDATE CASCADE ON DELETE RESTRICT,
        CONSTRAINT fk_usuario_universidad FOREIGN KEY (universidad_id) REFERENCES universidades (universidad_id) ON UPDATE CASCADE ON DELETE RESTRICT
    );

# Tabla de géneros
CREATE TABLE
    `generos` (
        `genero_id` INT PRIMARY KEY AUTO_INCREMENT,
        `genero` VARCHAR(10)
    );

# Tabla de universidades
CREATE TABLE
    `universidades` (
        `universidad_id` INT PRIMARY KEY AUTO_INCREMENT,
        `universidad` VARCHAR(255),
        `ciudad` VARCHAR(50),
        `pais` VARCHAR(50)
    );

# Tabla de publicaciones
CREATE TABLE
    `publicaciones` (
        `publicacion_id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,                           -- Del creador de la publicacion
        `contenido` TEXT,
        `fecha_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `actualizado_en` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_publicaciones_usuarios FOREIGN KEY (user_id) REFERENCES usuarios (user_id) ON UPDATE CASCADE ON DELETE CASCADE
    );

# Tabla de notificaciones
CREATE TABLE `notificaciones` (
    `notificacion_id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(255) NOT NULL,
    `contenido` TEXT,
    `estado` TINYINT DEFAULT 0,
    `fecha_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `publicacion_id` INT,
    `tipo_notificacion_id` INT NOT NULL,
    CONSTRAINT fk_notificacion_publicacion FOREIGN KEY (publicacion_id) REFERENCES publicaciones(publicacion_id) ON UPDATE CASCADE ON DELETE CASCADE, -- Eliminar notificaciones al eliminar publicación
    CONSTRAINT fk_notificacion_tipo FOREIGN KEY (tipo_notificacion_id) REFERENCES tipos_notificaciones (tipo_notificacion_id) ON UPDATE CASCADE ON DELETE RESTRICT -- No eliminar tipos de notificación si existen notificaciones
);

# Tabla tipos de notificaciones
CREATE TABLE `tipos_notificaciones`(
    `tipo_notificacion_id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre_tipo` VARCHAR(255),
    `descripcion` VARCHAR(255) 
);

# Tabla de comentarios
CREATE TABLE `comentarios` (
    `comentario_id` INT AUTO_INCREMENT PRIMARY KEY,
    `publicacion_id` INT NOT NULL,
    `user_id` INT NOT NULL,                            -- Del que crea el comentario, puede ser el autor o una persona X
    `contenido` TEXT,
    `fecha_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT comentario_publicacion FOREIGN KEY (publicacion_id) REFERENCES publicaciones(publicacion_id) ON UPDATE CASCADE ON DELETE CASCADE, -- Eliminar comentarios al eliminar publicación
    CONSTRAINT comentario_user FOREIGN KEY (user_id) REFERENCES usuarios(user_id) ON UPDATE CASCADE ON DELETE CASCADE -- Eliminar comentarios al eliminar usuario
);

# Tabla de contraseñas reseteadas
CREATE TABLE `password_resets` (
    `password_res_id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `used` TINYINT(1) DEFAULT 0
);

# Tabla de reportes
CREATE TABLE `reportes` (
    `reporte_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_reportador_id` INT NOT NULL, -- quien reporta
    `user_reportado_id` INT NOT NULL, -- a quien están reportando
    `publicacion_id` INT, -- sobre qué publicación es el reporte
    `fecha_reporte` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `motivo_id` INT NOT NULL,
    CONSTRAINT fk_reportador_user FOREIGN KEY (user_reportador_id) REFERENCES usuarios(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reportado_user FOREIGN KEY (user_reportado_id) REFERENCES usuarios(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reportar_publicacion FOREIGN KEY (publicacion_id) REFERENCES publicaciones(publicacion_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reportar_motivo FOREIGN KEY (motivo_id) REFERENCES motivos(motivo_id) ON UPDATE CASCADE ON RESTRICT
);

# Tabla de motivos
CREATE TABLE `motivos` (
    `motivo_id` INT AUTO_INCREMENT PRIMARY KEY,
    `motivo` VARCHAR(255)
    );
----------------------------------------------------------------------------------
# Insertar datos de catalogo    

INSERT INTO
    generos (`genero`)
VALUES
    ('Mujer'),
    ('Hombre'),
    ('Otro');

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
    tipos_notificaciones (`nombre_tipo`, `descripcion`)
VALUES
    ('Like', 'El usuario ha dado like a tu publicación'),
    ('Dislike', 'El usuario no le ha gustado tu publicación'),
    ('Comentario', 'El usuario ha comentado en tu publicación'),
    ('Reporte', 'El usuario ha reportado tu publicación');

INSERT INTO
    motivos (`motivo`)
VALUES
    ('Contenido ofensivo'),
    ('Contenido sexual'),
    ('Contenido violento'),
    ('Contenido spam'),
    ('Contenido falso'),
    ('Contenido inapropiado'),
    ('Otro');
----------------------------------------------------------------------------------