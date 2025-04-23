<?php
require_once '../includes/config.php';
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['Enviar'])) {
            $email = htmlspecialchars($_POST['email_usuario']);
            $password = htmlspecialchars($_POST['password_usuario']);
            if (!empty($email) && !empty($password)) {
                $consulta = $conexion_bbdd->query("SELECT * FROM usuarios WHERE email='$email' AND password='$password';");
                if ($fila = $consulta->fetch_assoc()) {
                    echo $fila['nombre'];
                } else {
                    header(header: "Location:../index.php?errAuth=1");
                }
            } else {
                echo "Holaaaaaaa";
            }
        } else {
            echo "Dale a enviar";
        }
    }
} catch (Throwable $t) {
    echo 'Ha ocurrido un error ' . $t->getMessage();
    exit();
}
