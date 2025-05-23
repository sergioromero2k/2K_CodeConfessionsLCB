<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <?php
    require_once './includes/nav.php'; // Navegación
    ?>
    <section class="flex-container-editar-perfil">
        <div class="item">
            <div>
                <div><img src="./assets/images/profile-default.png" width="30%" alt="Foto de perfil"></div>
            </div>
            <div>
                <h1><?php nombre_usuario(); ?></h1>
                <p><strong><?php universidad_usuario() ?></strong></p>
                <hr>

            </div>
            <div class="flex flex-around" style="display: flex; justify-content: space-around;">
                <div>Publicaciones</div>
                <div>Me gustas</div>
                <div>No Me gustas</div>
            </div>
            <hr>
            <div>
                <?php
                // Muestra las publicaciones
                $consulta = "SELECT * FROM publicaciones WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY fecha_en DESC";
                $resultado = $conexion_bbdd->query($consulta);

                while ($fila = $resultado->fetch_assoc()) {
                    // Obtener el nombre del usuario
                    $resultado_usuario = mostrar_dato(dato: 'nombre', tabla: 'usuarios', where: 'user_id', user_id: $fila['user_id']);
                    $resultado_universidad = mostrar_dato(dato: 'universidad', tabla: 'universidades', where: 'universidad_id', user_id: $fila['universidad_id']);
                    echo "<div class='flex' style='display: flex; background-color: green; padding: 10px; border-radius: 5px;'>";
                    echo "<div style='background-color: red; width: 15%; text-align: center;'>";
                    echo "<img src='./assets/images/profile-default.png' alt='Foto de perfil' style='width: 100%; border-radius: 50%;'>";
                    echo "</div>";
                    echo "<div style='background-color: blue; width: 85%; padding-left: 10px; color: white;'>";
                    echo "<h3 style='margin: 0;'>" . $resultado_usuario . "</h3><br>";

                    echo "<p style='margin: 0;'><b>" . $resultado_universidad . "</b></p>";
                    echo "<p style='background-color: aqua; color: black; padding: 5px; border-radius: 4px;'>" . $fila['contenido'] . "</p>";
                    echo "<div class='flex flex-around' style='display: flex; justify-content: space-around; margin-top: 10px;'>";
                    echo "<button><i class='fa-solid fa-thumbs-up'></i> Me gusta</button>";
                    echo "<button><i class='fa-solid fa-thumbs-down'></i> No me gusta</button>";
                    echo "<button><i class='fa-solid fa-comment'></i> Comentar</button>";
                    echo "<button><i class='fa-solid fa-ban'></i> Reportar</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>

        </div>


    </section>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>