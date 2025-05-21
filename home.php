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
    require_once './includes/nav.php'; // Incluye el encabezado
    require_once './includes/section.php'; // Incluye la sección principal
    ?>
    <button><a href="editar_perfil.php">Editar perfil</a></button><br>
    <button><a href="cambiar_password.php">Cambiar Contraseña</a></button><br>
    <button><a href="eliminar_cuenta.php">Eliminar Cuenta</a></button><br>
    <?php
    echo "<button><a href='./auth/logout.php'>Cerrar sesion</a></button><br>";
    ?>
    <button><a href="https://linktr.ee/sergioromero2k">Sobre el creador</a></button>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>