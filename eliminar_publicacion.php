<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo']) && $_POST['tipo'] === 'eliminar') {
    $publicacion_id = intval($_POST['publicacion_id'] ?? 0);
    $usuario_id = $_SESSION['user_id'] ?? null;

    if ($usuario_id && $publicacion_id) {
        // Verifica si la publicación pertenece al usuario actual
        $stmt = $conexion_bbdd->prepare("SELECT user_id FROM publicaciones WHERE publicacion_id = ?");
        $stmt->bind_param("i", $publicacion_id);
        $stmt->execute();
        $stmt->bind_result($autor_id);
        $stmt->fetch();
        $stmt->close();

        if ($autor_id == $usuario_id) {
            // Eliminar publicación
            $stmt = $conexion_bbdd->prepare("DELETE FROM publicaciones WHERE publicacion_id = ?");
            $stmt->bind_param("i", $publicacion_id);
            if ($stmt->execute()) {
                $_SESSION["publicacion_err"] = 0;
                header("location=home.php");
                exit();
            } else {
                $_SESSION["publicacion_err"] = 1;
                header("location=home.php");
                $error = "Error al eliminar la publicación.";
            }
        } else {
            $_SESSION["publicacion_err"] = 2;
            header("location=home.php");
            $error = "No tienes permiso para eliminar esta publicación.";
        }
    } else {
        $_SESSION["publicacion_err"] = 3;
        header("location=home.php");
        $error = "Datos inválidos para eliminación.";
    }
}
