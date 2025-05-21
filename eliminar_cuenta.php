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
                header(header: "Location:./eliminar_cuenta.php?errPassw=0");
                exit();
            }
        } else {
            header(header: "Location:./eliminar_cuenta.php?errPassw=1");
            exit();
        }
    } else {
        header(header: "Location:./eliminar_cuenta.php?errPassw=2");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <nav>
        <div class="flex-container-nav ">
            <div class="flex-container-nav-right flex-container-border">
                <div><a href="home.php">LOGO LCB</a></div>
                <div><i class="fa-solid fa-user"></i>
                    <?php
                    nombre_usuario();
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <section class="flex-container-editar-perfil">
        <div class="item">
            <div>
                <div><img src="./assets/images/profile-default.png" width="30%" alt="Foto de perfil"></div>
            </div>
            <div>
                ¿Eliminar tu cuenta?
            </div>
            <div>
                <ul>
                    <li>Una vez eliminada, no podrás recuperar nada de lo que había en tu cuenta.</li>
                    <li>Asegúrate de haber descargado o respaldado cualquier contenido o información importante antes de eliminar tu cuenta.</li>
                    <li>La eliminación de la cuenta es permanente y no se puede deshacer.</li>
                </ul>

            </div>
            <div style="background-color: white;">
                <form action="eliminar_cuenta.php" method="POST">
                    <label for="password_usuario">Contraseña</label><br>
                    <input type="password" name="password_usuario" id="password_usuario" required><br>
                    <label for="password_confirmar">Confirmar contraseña</label><br>
                    <input type="password" name="password_confirmar" id="password_confirmar" required><br><br>
                    <label for="seguro">¿Estás seguro?</label>
                    <input type="radio" name="seguro" id="seguro_no" value="Si" required>Si</input>
                    <input type="radio" name="seguro" id="seguro_si" value="No" required>No</input><br>
                    <input type="submit" value="Eliminar cuenta" name="Eliminar">
                </form>
                <?php
                if (isset($_GET['errPassw'])) {
                    switch ($_GET['errPassw']) {
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
                }
                ?>
            </div>
        </div>


    </section>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>