<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

session_start();
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $consulta = $conexion_bbdd->prepare("SELECT * FROM password_resets WHERE token=? AND used=0");
    $consulta->bind_param("s", $token);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        $user_email = $resultado->fetch_assoc()["email"];
        if (isset($_POST['cambiar'])) {
            $password_nueva = $_POST['password_nueva'];
            $password_confirmar = $_POST['password_confirmar'];
            $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

            if (!preg_match($pattern, $password_nueva)) {
                header("Location: ./recuperar_cuenta.php?errPassw=1&token=$token");
                exit();
            }

            if ($password_nueva === $password_confirmar) {
                $password_hash = password_hash($password_nueva, PASSWORD_ARGON2I);

                $consulta = $conexion_bbdd->prepare("UPDATE usuarios SET password=? WHERE email=?");
                $consulta->bind_param("ss", $password_hash, $user_email);
                $consulta->execute();
                $consulta->close();

                $stmt = $conexion_bbdd->prepare("UPDATE password_resets SET used=1, token=NULL WHERE token=?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->close();

                $_SESSION['nuevaPass'] = 2;
                header("Location: ../index.php");
                exit();
            } else {
                header("Location: ./recuperar_cuenta.php?errPassw=0&token=$token");
                exit();
            }
        }

        // Marcar usuario como verificado (opcional según tu lógica)
        $verificar = $conexion_bbdd->prepare("UPDATE usuarios SET verificado=1, token_activacion=NULL WHERE token_activacion=?");
        $verificar->bind_param("s", $token);
        $verificar->execute();
        $verificar->close();
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cambiar contraseña - LCB</title>
            <meta name="author" content="Sergio Alejandro Romero López">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" crossorigin="anonymous">
            <link rel="stylesheet" href="../assets/css/cambiar_pass.css">
        </head>

        <body>
            <section class="h-100 d-flex justify-content-center align-items-center">
                <form action="recuperar_cuenta.php?token=<?= htmlspecialchars($token) ?>" method="POST">
                    <div style="text-align: center;">
                        <img src="../assets/images/fonts/logo_LCB_sinU.png" class="profile-pic2 me-2" alt="Logo de LCB">
                        <p>Ingresa tu nueva contraseña</p>
                    </div>
                    <div class="card-form">
                        <div class="form-group">
                            <label for="password_nueva">Nueva contraseña</label>
                            <input type="password" class="form-control" name="password_nueva" id="password_nueva"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmar">Confirmar contraseña</label>
                            <input type="password" class="form-control" name="password_confirmar" id="password_confirmar"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." required>
                        </div>
                        <div class="text-muted">
                            Te recomendamos elegir una contraseña segura que combine mayúsculas, minúsculas, números y símbolos.
                        </div>
                        <br>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="Cambiar contraseña" name="cambiar">
                        </div>
                        <?php
                        if (isset($_GET['errPassw'])) {
                            switch ($_GET['errPassw']) {
                                case 0:
                                    echo "<div class='alert alert-danger mt-3'>Las contraseñas nuevas no coinciden.</div>";
                                    break;
                                case 1:
                                    echo "<div class='alert alert-danger mt-3'>La nueva contraseña no cumple con los requisitos de seguridad.</div>";
                                    break;
                            }
                        }
                        ?>
                    </div>
                </form>
            </section>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        </body>

        </html>
<?php
    } else {
        $_SESSION['nuevaPass'] = 0;
        header("Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['nuevaPass'] = 1;
    header("Location: ../index.php");
    exit();
}
?>