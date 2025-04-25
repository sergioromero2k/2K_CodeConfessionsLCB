<?php
session_start();
$_SESSION = array(); // Limpiar la variable de sesión
session_destroy();
header("Location:../index.php"); // Redirigir al usuario a la página de inicio
exit(); // Asegurarse de que el script se detenga después de la redirección
