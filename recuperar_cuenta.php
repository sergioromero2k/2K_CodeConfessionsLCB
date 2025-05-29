<?php
require_once 'includes/functions.php';
require_once 'includes/config.php';

$token = $_GET['token'];;
session_start();
if (isset($token) && !empty($token)) {
    $consulta = $conexion_bbdd->prepare("SELECT * FROM password_resets WHERE token_activacion=? and used=0");
    $consulta->bind_param("s", $token);
    $consulta->execute();
    $resultado = $consulta->get_result();
    if ($resultado->num_rows > 0) {
        $consulta = $conexion_bbdd->prepare("UPDATE password_resets SET used=1, token_activacion=NULL WHERE token_activacion=?");
        $consulta->bind_param("s", $token);
        if ($consulta->execute()) {
            $_SESSION['mensaje_recup_cuenta'] = 0; // Indica que la recuperación fue exitosa
        } else {
            $_SESSION['mensaje_recup_cuenta'] = 1; // Error al actualizar el estado del token
        }
        header(header: "Location: index.php");
        exit();
    } else {
        $_SESSION['mensaje_recup_cuenta'] = 2; // Indica que el token es inválido o ya ha sido usado
        header(header: "Location: index.php");
        exit();
    }
} else {
    $_SESSION['mensaje_recup_cuenta'] = 3; // Indica que el token no fue proporcionado o está vacío
    header(header: "Location: index.php");
    exit();
}
