## Lógica base de datos
---

8. [] **PASO 8: Modelo ER**
9. [] **PASO 9: Modelo Relacional**
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
        - nombre
        - apellido
        - edad
        - password
        - genero
        - nickname
        - universidad_id    ***PK***
    
    -   **universidades**   **EC** ✅
        - universidad_id    
        - universidad

    -   **publicaciones**   **ED** 🆗
        - publicacion_id    ***PK***
        - publicacion
        - email             ***FK***

    -   **notificacion**    **ED** 🆗
        - notificacion_id   ***PK***
        - publicacion_id    ***FK***

5. [] **PASO 5: Tipo de Relación**