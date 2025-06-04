<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header(header: "Location:./views/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <article class="h-100 d-flex justify-content-center align-items-center article-caja">
        <div>
            <img src="./assets/images/fonts/logo_LCB.png" alt="logo_LCB" class="img-fluid">
        </div>
        <div class="card-form">
            <form action="./auth/validar.php" method="post" enctype="application/x-www-form-urlencoded">
                <h1>Iniciar sesión</h1>
                <p>¿Es tú primera vez?<a href="./views/register.php"> Registrarse</a></p>
                <div class="form-group">
                    <input type="email" class="form-control" name="email_usuario" id="email_usuario" aria-describedby="emailHelp" placeholder="Correo Electrónico" required><br>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password_usuario" id="password_usuario" placeholder="Contreseña" required><br>
                    <a href="./controllers/olvidaste_password.php">¿Olvidó la contraseña?</a>
                    <hr>
                </div>

                <input type="submit" value="Iniciar Sesión" class="btn btn-primary" name="Enviar">
                <input type="reset" value="Limpiar" class="btn btn-secondary"> <br>
                <?php
                if (isset($_SESSION['errAuth'])) {
                    switch ($_SESSION['errAuth']) {
                        case 0:
                            echo "<br> <div class='alert alert-danger'>Contraseña incorrecta</div>";
                            break;
                        case 1:
                            echo "<br> <div class='alert alert-danger'>Usuario no verificado</div>";
                            break;
                        case 2:
                            echo "<br> <div class='alert alert-danger'>El correo electrónico que ingresaste no está conectado a una cuenta.</div>";
                            break;
                        case 3:
                            echo "<br> <div class='alert alert-danger'>Rellenar los campos solicitados</div>";
                            break;
                    }
                    unset($_SESSION['errAuth']); // Limpiar la variable de sesión después de mostrar el mensaje 
                }
                if (isset($_SESSION['activacion'])) {
                    switch ($_SESSION['activacion']) {
                        case 0:
                            echo "<br> <div class='alert alert-success'>Cuenta activada correctamente, ya puedes iniciar sesión.</div>";
                            break;
                        case 1:
                            echo "<br> <div class='alert alert-danger'>Error al activar la cuenta, por favor intente de nuevo.</div>";
                            break;
                        case 2:
                            echo "<br> <div class='alert alert-danger'>La cuenta ya esta activada.</div>";
                            break;
                        case 3:
                            echo "<br> <div class='alert alert-danger'>Token inválido o no existe.</div>";
                            break;
                    }
                    unset($_SESSION['activacion']); // Limpiar la variable de sesión después de mostrar el mensaje
                }
                if (isset($_SESSION['mensaje_recup_cuenta'])) {
                    switch ($_SESSION['mensaje_recup_cuenta']) {
                        case 0:
                            echo "<br> <div class='alert alert-success'>Recuperación de cuenta exitosa.</div>";
                            break;
                        case 1:
                            echo "<br> <div class='alert alert-danger'>Error al actualizar el estado del token.</div>";
                            break;
                        case 2:
                            echo "<br> <div class='alert alert-danger'>Token inválido o ya ha sido usado.</div>";
                            break;
                        case 3:
                            echo "<br> <div class='alert alert-danger'>Token no proporcionado o está vacío.</div>";
                            break;
                    }
                    unset($_SESSION['mensaje_recup_cuenta']); // Limpiar la variable de sesión después de mostrar el mensaje
                }
                if (isset($_SESSION['nuevaPass'])) {
                    switch ($_SESSION['nuevaPass']) {
                        case 0:
                            echo "<br> <div class='alert alert-danger'>La contraseña ya fue cambiada.</div>";
                            break;
                        case 1:
                            echo "<br> <div class='alert alert-danger'>Token inválido, vuelve a intentarlo luego.</div>";
                            break;
                        case 2:
                            echo "<br> <div class='alert alert-success'>Se cambio la contraseña correctamente, ya puedes iniciar sesión.</div>";
                            break;
                    }
                    unset($_SESSION['mensaje_recup_cuenta']); // Limpiar la variable de sesión después de mostrar el mensaje
                }
                ?>
            </form>
        </div>
    </article>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>