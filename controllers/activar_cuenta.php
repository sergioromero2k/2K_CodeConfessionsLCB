<?php
require_once '../auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares

$token = $_GET['token'];;
session_start();
if (isset($token) && !empty($token)) {
    $consulta = $conexion_bbdd->prepare("SELECT * FROM usuarios WHERE token_activacion=? and verificado=0");
    $consulta->bind_param("s", $token);
    $consulta->execute();
    $resultado = $consulta->get_result();
    if ($resultado->num_rows > 0) {
        $consulta = $conexion_bbdd->prepare("UPDATE usuarios SET verificado=1, token_activacion=NULL WHERE token_activacion=?");
        $consulta->bind_param("s", $token);
        if ($consulta->execute()) {
            $_SESSION['activacion'] = 0; // Indica que la activación fue exitosa
        } else {
            $_SESSION['activacion'] = 1;
        }
        header(header: "Location: ../index.php");
        exit();
    } else {
        $_SESSION['activacion'] = 2; // La cuenta ya esta activada
        header(header: "Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['activacion'] = 3; // Indica que el token es inválido o no existe
    header(header: "Location: ../index.php");
    exit();
}
