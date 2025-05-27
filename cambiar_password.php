<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
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
                header(header: "Location:./home.php");
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
                Ingresa tu nueva contraseña despues
            </div>
            <div style="background-color: white;">
                <form action="cambiar_password.php" method="POST">
                    <label for="password_usuario">Antigua contraseña</label><br>
                    <input type="password" name="password_usuario" id="password_usuario" required><br>
                    <label for="password_nueva">Nueva contraseña</label><br>
                    <input type="password" name="password_nueva" id="password_nueva" required><br>
                    <label for="password_confirmar">Confirmar contraseña</label><br>
                    <input type="password" name="password_confirmar" id="password_confirmar" required><br><br>
                    <div style="width: 350px;">Te recomendamos elegir una nueva contraseña con al menos 8 caracteres, que combine letras mayúsculas, minúsculas, números y símbolos para asegurar la protección de tu cuenta. Evita usar contraseñas comunes o fáciles de adivinar, como fechas de nacimiento o palabras relacionadas contigo.</div><br>
                    <input type="submit" value="Cambiar contraseña" name="actualizar">
                </form>
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


    </section>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>