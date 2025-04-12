## Lógica base de datos
---

1. [x] **PASO 1: Entidades**
2. [x] **PASO 2: Atributos**
3. [x] **PASO 3: PK & FK**
4. [x] **PASO 4: Nomenclatura**
6. [x] **PASO 6: Entidades de datos 🆗 & Pivote ☑️**
7. [x] **PASO 7: Entidades Cátalogo ✅**

    -   **usuarios**        **ED** 🆗
        - email             ***PK***        VARCHAR(255) 
        - password                          VARCHAR(255)
        - nombre                            VARCHAR(50)
        - apellido                          VARCHAR(50)
        - fecha_nacimiento                  VARCHAR(50)
        - genero_id         ***FK***        DATE
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
        - publicacion_id    ***PK***        INT
        - publicacion                       TEXT
        - email             ***FK***        VARCHAR(255) 

    -   **notificaciones**    **ED** 🆗
        - notificacion_id   ***PK***        INT
        - publicacion_id    ***FK***        INT

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
