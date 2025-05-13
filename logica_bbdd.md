## Lógica base de datos
---

1. [x] **PASO 1: Entidades**
2. [x] **PASO 2: Atributos**
3. [x] **PASO 3: PK & FK**
4. [x] **PASO 4: Nomenclatura**
6. [x] **PASO 6: Entidades de datos 🆗 & Pivote ☑️**
7. [x] **PASO 7: Entidades Cátalogo ✅**

    -   **usuarios**        **ED** 🆗
        - user_id           ***PK***        INT
        - email                             VARCHAR(255) UNIQUE NOT NULL
        - password                          VARCHAR(255) NOT NULL
        - nombre                            VARCHAR(50) NOT NULL
        - apellido                          VARCHAR(50) NOT NULL
        - fecha_nacimiento                  DATE NOT NULL
        - verificacion                      TINYINT(1) DEFAULT 0
        - token_activacion                  VARCHAR(255)
        - creado_en                         DATETIME CURRENT_TIMESTAMP
        - actualizado_en                    DATETIME CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        - genero_id         ***FK***        INT
        - universidad_id    ***FK***        INT
        
    -   **generos**         **EC** ✅
        - genero_id         ***PK***        INT 
        - genero                            VARCHAR(10)
    
    -   **universidades**   **EC** ✅
        - universidad_id    ***PK***        INT
        - universidad                       VARCHAR(255)
        - ciudad                            VARCHAR(50)
        - pais                              VARCHAR(50)

    -   **publicaciones**   **ED** 🆗
        - publicacion_id     ***PK***       INT
        - user_id           ***FK***        INT             # Del creador de la publicacion
        - contenido                         TEXT
        - fecha_en                          DATETIME DEFAULT CURRENT_TIMESTAMP
        - actualizado_en                    DATETIME CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP


    -   **notificaciones**    **ED** 🆗
        - notificacion_id         ***PK***    INT
        - tipo_notificacion_id    ***FK***    INT
        - nombre                              VARCHAR(255) NOT NULL
        - contenido                           TEXT
        - estado                              TINYINT DEFAULT 0
        - fecha_en                            DATETIME DEFAULT CURRENT_TIMESTAMP
        - publicacion_id        ***FK***      INT

    -   **tipos_notificaciones**    **EC** ✅
        - tipo_notificacion_id       ***PK***  INT
        - nombre_tipo                          VARCHAR(255)
        - descripcion                          VARCHAR(255)

     -   **Comentarios**    **ED** 🆗
        - comentario_id         ***PK***        INT
        - publicacion_id        ***FK***        INT
        - user_id               ***FK***        INT          # Del que crea el comentario
        - contenido                             TEXT
        - fecha_en                              DATETIME DEFAULT CURRENT_TIMESTAMP
        - actualizado_en                        DATETIME CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

    -   **password_resets**    **ED** 🆗
        - password_res_id   ***PK***        INT
        - email                             VARCHAR(255) NOT NULL
        - token                             VARCHAR(255) NOT NULL
        - creado_en                         DATETIME    NOT NULL
        - usado                             TINYINT(1) DEFAULT 0

-   **reportes**    **ED** 🆗
        - reporte_id            ***PK***        INT AUTO_INCREMENT PRIMARY KEY,
        - user_reportar_id      ***FK***        INT NOT NULL            # quien reporta
        - user_reportado_id     ***FK***        INT NOT NULL            # A quien están reportando
        - publicacion_id        ***FK***        INT
        - motivo_id             ***FK***        INT
        - fecha_reporte                         DATETIME DEFAULT CURRENT_TIMESTAMP

    **motivos**         **EC** ✅
        - motivo_id     ***PK***                INT AUTO_INCREMENT PRIMARY KEY,
        - motivo                                VARCHAR(255)

5. [x] **PASO 5: Tipo de Relación** 
        - Un usuario(1) pertenece a (1)universidad.
        - Una universidad(1) pertenece (N) usuarios.

        - Un usuario(1) puede hacer (1) o (M) publicaciones.
        - Una publicación(1) pertenece a (1) usuario.

        - Un usuario(1) puede recibir (N) notificación.
        - Una notificación(1) pertenece a (1) usuario.

        - Un usuario(1) tiene (1) genero.
        - Un genero(1) tiene (1) genero.

        - Una publicación(1) puede generar (1) o (N) notificaciones.
        - Una notificación(1) está asociada a (1) publicación.

8. [x] **PASO 8: Modelo ER**
9. [x] **PASO 9: Modelo Relacional**
10. [x] **PASO 10: Tipo de datos de los Atributos**
11. [x] **PASO 11: Identificar los Atributos que pueden ser únicos**
12. [] **PASO 12: Identificar las reglas de negocio(Operaciones CRUD) del sistema**
