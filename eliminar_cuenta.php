<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
if (isset($_POST['Eliminar'])) {
    $password_usuario = password_usuario();
    # Prevenir inyecciones SQL
    if ($_POST['password_usuario'] == $_POST['password_confirmar']) {
        if (password_verify(password: $_POST['password_usuario'], hash: $password_usuario)) {
            if (strtoupper($_POST['seguro']) == "SI") {
                $consulta = $conexion_bbdd->prepare(query: "DELETE FROM usuarios WHERE user_id = ?");
                $consulta->bind_param("i", $_SESSION['user_id']);
                $consulta->execute();
                $consulta->close();
                header(header: "Location:./auth/logout.php");
                exit();
            } else {
                session_start();
                $_SESSION['errPassw'] = 0; // No está seguro de eliminar la cuenta
                header(header: "Location:./eliminar_cuenta.php");
                exit();
            }
        } else {
            session_start();
            $_SESSION['errPassw'] = 1; // Contraseña incorrecta
            header(header: "Location:./eliminar_cuenta.php");
            exit();
        }
    } else {
        session_start();
        $_SESSION['errPassw'] = 2; // Las contraseñas no coinciden
        header(header: "Location:./eliminar_cuenta.php");
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
    <link rel="stylesheet" href="./assets/css/eliminar_cuenta.css">
</head>

<body>
    <?php
    require_once './includes/nav.php'; // Incluye el encabezado
    ?>

    <section class="h-100 d-flex justify-content-center align-items-center">
        <form action="eliminar_cuenta.php" method="POST">
            <div style="text-align: center;">
                <div tex><img src="./assets/images/fonts/logo_LCB_sinU.png" class="profile-pic2 me-2" alt="Foto de perfil"></div>
                <h3>¿Eliminar tu cuenta?</h3>
            </div>

            <div class="card-form">
                <div>
                    <ul>
                        <li>Una vez eliminada, <b>no podrás recuperar nada</b> de lo que había en tu cuenta.</li>
                        <li>Asegúrate de haber descargado o respaldado cualquier contenido o información importante antes de eliminar tu cuenta.</li>
                        <li>La eliminación de la cuenta es <b>permanente</b> y no se puede deshacer.</li>
                    </ul>

                </div>
                <div style="background-color: white;">
                    <div class="form-group">
                        <label for="password_usuario">Contraseña</label>
                        <input type="password" class="form-control" name="password_usuario" id="password_usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmar">Confirmar contraseña</label>
                        <input type="password" class="form-control" name="password_confirmar" id="password_confirmar" required>
                    </div>
                    <div class="form-group">
                        <label for="seguro">¿Estás seguro?</label>
                        <input type="radio" name="seguro" id="seguro_no" value="Si" required>Si</input>
                        &nbsp;<input type="radio" name="seguro" id="seguro_si" value="No" required>No</input>
                    </div>
                    <div style="text-align: center;"><input type="submit" class="alert alert-danger" value="Eliminar cuenta" name="Eliminar"></div>

                </div>
                <?php
                if (isset($_SESSION['errPassw'])) {
                    switch ($_SESSION['errPassw']) {
                        case 0:
                            echo "<div class='alert alert-danger' role='alert'>No se elimina la cuenta, por que no estas seguro.</div>";
                            break;
                        case 1:
                            echo "<div class='alert alert-danger' role='alert'>La contraseña proporcionada, no es la contraseña de la cuenta.</div>";
                            break;
                        case 2:
                            echo "<div class='alert alert-danger' role='alert'>Las contraseñas no coinciden.</div>";
                            break;
                    }
                    unset($_SESSION['errPassw']); // Limpiar la variable de sesión después de mostrar el mensaje
                }
                ?>
            </div>

        </form>

    </section>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>