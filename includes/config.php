<?php
# Configuración global 
mysqli_report(flags: MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  # Configuramos los errores y las excepciones.

$servidor = "localhost:3307";
$usuario = "root";
$password = "";
$nombre_bbdd = "confesioneslcb";
try {
    $conexion_bbdd = new mysqli(hostname: $servidor, username: $usuario, password: $password, database: $nombre_bbdd);
    $conexion_bbdd->set_charset(charset: "utf8mb4");  # Configuramos la codificación de caracteres a utf8mb4
    if ($conexion_bbdd->connect_error) {
        echo 'Conexión fallida: ' . $conexion_bbdd->connect_error;  
    }
} catch (Throwable $t) {
    echo 'Error grave: ' . $t->getMessage();
}
