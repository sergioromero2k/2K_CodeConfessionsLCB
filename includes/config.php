<?php
# Configuración global 
$servidor = "localhost:3307";
$usuario = "root";
$password = "";
$nombre_bbdd = "confesioneslcb";
try {
    $conexion_bbdd = new mysqli(hostname: $servidor, username: $usuario, password: $password, database: $nombre_bbdd);
    if ($conexion_bbdd->connect_error) {
        echo 'Conexión fallida: ' . $conexion_bbdd->connect_error;
    }
} catch (Throwable $t) {
    echo 'Error grave: ' . $t->getMessage();
}
