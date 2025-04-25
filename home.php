<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
echo "<h1>Bienvenido a la pagina de inicio</h1>";
echo "<h2>Hola " . $_SESSION['user_id'] . "</h2>";
echo "<h2>Hola " . $_SESSION['nombre'] . "</h2>";
echo "<button><a href='./auth/logout.php'>Cerrar sesion</a></button>";
?>
