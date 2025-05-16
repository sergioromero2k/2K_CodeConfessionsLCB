<?php
require_once '../includes/config.php';

try {
    if (!empty($_POST['email_usuario']) && !empty($_POST['password_usuario'])) {
        # Prevenir inyecciones SQL
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $consulta = $conexion_bbdd->prepare(query: "SELECT * FROM  usuarios WHERE email=?;");
            $consulta->bind_param("s", $_POST['email_usuario']);
            $consulta->execute();
            $resultado = $consulta->get_result();
            if ($fila = $resultado->fetch_assoc()) {
                if($fila['verificado'] == 1) {
                    if (password_verify(password: $_POST['password_usuario'], hash: $fila['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $fila['user_id'];
                        $_SESSION['nombre'] = $fila['nombre'];
                        $_SESSION['apellido'] = $fila['apellido'];
                        $_SESSION['email'] = $fila['email'];
                        header(header: "Location:../home.php"); # Redirigir a la página de inicio   
                    } else {
                        header(header: "Location:../index.php?errAuth=0");  # Contraseña incorrecta

                    }
                } else {
                    header(header: "Location:../index.php?errAuth=1"); # Usuario no verificado
                }
            } else {
                header(header: "Location:../index.php?errAuth=2");  # Usuario no existe
            }
        }
    } else {
        header(header: "Location:../index.php?errAuth=3"); # Campos vacíos
    }
} catch (Throwable $t) {
    echo 'Ha ocurrido un error: ' . $t->getMessage();
    exit();
}
