<?php
require_once '../auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares
if (isset($_POST['actualizar'])) {
    $password_usuario = password_usuario();
    # Prevenir inyecciones SQL
    if (password_verify(password: $_POST['password_usuario'], hash: $password_usuario)) {
        $password_nueva = $_POST['password_nueva'];
        $password_confirmar = $_POST['password_confirmar'];
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'; // Expresión regular para validar la contraseña
        if (!preg_match(pattern: $pattern, subject: $password_nueva)) {
            header(header: "Location:./cambiar_password.php?errPassw=2");
            exit();
        } else {
            if ($_POST['password_nueva'] == $_POST['password_confirmar']) {
                $password_nueva = password_hash($_POST['password_nueva'], PASSWORD_ARGON2I);
                $consulta = $conexion_bbdd->prepare(query: "UPDATE usuarios SET password=? WHERE user_id=?");
                $consulta->bind_param("si", $password_nueva, $_SESSION['user_id']);
                $consulta->execute();
                $consulta->close();
                header(header: "Location:../views/home.php");
                exit();
            } else {
                header(header: "Location:./cambiar_password.php?errPassw=0");
                exit();
            }
        }
    } else {
        header(header: "Location:./cambiar_password.php?errPassw=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/cambiar_pass.css">
</head>

<body>
    <?php
    require_once '../includes/nav.php'; // Incluye el encabezado
    nav(ruta_home: "../views/home.php", ruta_sobreNoso: "../views/sobre_nosotros.php", ruta_notificaciones: "../notificaciones/notificaciones.php", ruta_perfil: "../views/mi_perfil.php", editar_perfil: "./editar_perfil.php", cambiar_password: "cambiar_password.php", eliminar_cuenta: "./eliminar_cuenta.php");
    ?>
    <section class="h-100 d-flex justify-content-center align-items-center">
        <form action="cambiar_password.php" method="POST">
            <div style="text-align: center;">
                <div tex><img src="../assets/images/fonts/logo_LCB_sinU.png" class="profile-pic2 me-2" alt="Foto de perfil"></div>
                <p>Ingresa tu nueva contraseña despues</p>
            </div>

            <div class="card-form">
                <div class="form-group">
                    <label for="password_usuario">Antigua contraseña</label>
                    <input type="password" class="form-control" name="password_usuario" id="password_usuario" required>
                </div>
                <div class="form-group">
                    <label for="password_nueva">Nueva contraseña</label>
                    <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." name="password_nueva" id="password_nueva" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmar">Confirmar contraseña</label>
                    <input type="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo." name="password_confirmar" id="password_confirmar" required>
                </div>
                <div>Te recomendamos elegir una nueva contraseña con al menos 8 caracteres, que combine letras mayúsculas, minúsculas, números y símbolos para asegurar la protección de tu cuenta. Evita usar contraseñas comunes o fáciles de adivinar, como fechas de nacimiento o palabras relacionadas contigo.</div>
                <br>
                <div style="text-align: center;"><input type="submit" class="btn btn-primary" value="Cambiar contraseña" name="actualizar"></div>
                <?php
                if (isset($_GET['errPassw'])) {
                    switch ($_GET['errPassw']) {
                        case 0:
                            echo "<div class='alert alert-danger' role='alert'>Las contraseñas nuevas no coinciden</div>";
                            break;
                        case 1:
                            echo "<div class='alert alert-danger' role='alert'>La contraseña antigua es incorrecta</div>";
                            break;
                        case 2:
                            echo "<div class='alert alert-danger' role='alert'>La nueva contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.</div>";
                            break;
                    }
                }
                ?>
            </div>
            </div>
        </form>
    </section>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>