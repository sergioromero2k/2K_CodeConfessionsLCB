## Lógica base de datos
---
10. [] **PASO 10: Tipo de datos de los Atributos**
11. [] **PASO 11: Identificar los Atributos que pueden ser únicos**
12. [] **PASO 12: Identificar las reglas de negocio(Operaciones CRUD) del sistema**

1. [x] **PASO 1: Entidades**
2. [x] **PASO 2: Atributos**
3. [x] **PASO 3: PK & FK**
4. [x] **PASO 4: Nomenclatura**
6. [x] **PASO 6: Entidades de datos 🆗 & Pivote ☑️**
7. [x] **PASO 7: Entidades Cátalogo ✅**

    -   **usuarios**        **ED** 🆗
        - email             ***PK***
        - password
        - nombre
        - apellido
        - fecha_nacimiento
        - genero_id         ***FK***    
        - universidad_id    ***FK***

    -   **generos**         **EC** ✅
        - genero_id         ***PK***
        - genero
    
    -   **universidades**   **EC** ✅
        - universidad_id    ***PK***
        - universidad
        - ciudad
        - pais

    -   **publicaciones**   **ED** 🆗
        - publicacion_id    ***PK***
        - publicacion
        - email             ***FK***

    -   **notificacion**    **ED** 🆗
        - notificacion_id   ***PK***
        - publicacion_id    ***FK***

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
9. [] **PASO 9: Modelo Relacional**

