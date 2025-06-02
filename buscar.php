<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pagina inicial LCB - Buscar</title>
    <meta name="author" content="Sergio Alejandro Romero López" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="./assets/css/buscar_publicacion.css" />
    <link rel="stylesheet" href="./assets/css/nav.css" />
    <link rel="stylesheet" href="./assets/css/buscar.css" />
    <link rel="stylesheet" href="./assets/css/publicacion.css">

    <!-- jQuery COMPLETO para AJAX -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once './includes/nav.php';
    $q = '';
    if (isset($_POST['q'])) {
        $q = trim($_POST['q']);
    } elseif (isset($_GET['q'])) {
        $q = trim($_GET['q']);
    } ?>

    <div class="container mt-4">
        <h1 class="mb-4">Buscar publicaciones</h1>

        <!-- Formulario de búsqueda y filtro -->
        <form id="formBuscar" class="form-inline mb-4">
            <input
                class="form-control mr-2"
                type="search"
                name="q"
                id="inputBuscar"
                placeholder="Buscar..."
                aria-label="Buscar" 
                value="<?php echo htmlspecialchars($q); ?>"/>
            <select class="custom-select mr-2" id="filtro" name="filtro">
                <option value="tabla1" selected>Usuarios</option>
                <option value="tabla2">Unersidades</option>
            </select>
            <button class="btn btn-outline-success" type="submit" title="Buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <!-- Contenedor donde se mostrarán las tablas -->
        <div id="resultado-tablas">
            <!-- Aquí se cargarán los resultados AJAX -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Función para cargar resultados por AJAX
            function cargarResultados() {
                const query = $('#inputBuscar').val().trim();
                const filtro = $('#filtro').val();

                // Enviar datos por POST a filtrar.php
                $.ajax({
                    url: 'filtrar.php',
                    method: 'POST',
                    data: {
                        q: query,
                        filtro: filtro
                    },
                    success: function(response) {
                        $('#resultado-tablas').html(response);
                    },
                    error: function() {
                        $('#resultado-tablas').html('<div class="alert alert-danger">Error al cargar los resultados.</div>');
                    }
                });
            }

            // Cargar resultados al enviar el formulario
            $('#formBuscar').submit(function(e) {
                e.preventDefault(); // evitar recargar la página
                cargarResultados();
            });

            // Cargar resultados iniciales con filtro por defecto y sin búsqueda
            cargarResultados();
        });
    </script>
    <!-- Scripts Bootstrap y otros -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>