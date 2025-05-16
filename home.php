<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <?php
    require_once './includes/nav.php'; // Incluye el encabezado
    ?>
    <article>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
echo "<h1>Bienvenido a la pagina de inicio</h1>";
echo "<h2>Hola " . $_SESSION['user_id'] . "</h2>";
echo "<h2>Hola " . $_SESSION['nombre'] . "</h2>";
echo "<button><a href='./auth/logout.php'>Cerrar sesion</a></button>";
?>