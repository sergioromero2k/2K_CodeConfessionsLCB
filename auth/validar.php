<?php
try {
    require_once '../includes/config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = htmlspecialchars($_POST['email_usuario']);
        $password = htmlspecialchars($_POST['password_usuario']);
        if (!empty($email) && !empty($password)) {
            $consulta = $conexion_bbdd->query(query: "SELECT * FROM usuarios WHERE email='$email' AND password='$password';");
            if ($fila = $consulta->fetch_assoc()) {
                session_start();
                $_SESSION['user_id'] = $fila['user_id'];
                $_SESSION['nombre'] = $fila['nombre'];
                header(header: "Location:../home.php");
            } else {
                header(header: "Location:../index.php?errAuth=1");
            }
        }
    }
} catch (Throwable $t) {
    echo 'Ha ocurrido un error ' . $t->getMessage();
    exit();
}
