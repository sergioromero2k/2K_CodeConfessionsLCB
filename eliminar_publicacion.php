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
        $result = $stmt->get_result();
        $autor_id = $result->fetch_assoc()["user_id"] ?? null;
        $stmt->close();

        if ($autor_id == $usuario_id) {
            // Eliminar publicación
            $stmt = $conexion_bbdd->prepare("DELETE FROM publicaciones WHERE publicacion_id = ?");
            $stmt->bind_param("i", $publicacion_id);
            if ($stmt->execute()) {
                $_SESSION["publicacion_err"] = 0;
            } else {
                $_SESSION["publicacion_err"] = 1;
            }
            $stmt->close();
            header("Location: home.php");
            exit();
        } else {
            $_SESSION["publicacion_err"] = 2;
            header("Location: home.php");
            exit();
        }
    } else {
        $_SESSION["publicacion_err"] = 3;
        header("Location: home.php");
        exit();
    }
}

header("Location: home.php");
exit();
