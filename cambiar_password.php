<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
if (isset($_POST['actualizar'])) {
    $password_usuario = password_usuario();
    # Prevenir inyecciones SQL
    if (password_verify(password: $_POST['password_usuario'], hash: $password_usuario)) {
        $consulta = $conexion_bbdd->prepare("UPDATE usuarios SET nombre=?,apellido=?,fecha_nacimiento=?,universidad_id=? WHERE user_id=?");
        $consulta->bind_param("ssssi", $_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $_POST['universidad'], $_SESSION['user_id']);
        $consulta->execute();
        $consulta->close();
        $_SESSION['nombre'] = $_POST['nombre'];
        $_SESSION['apellido'] = $_POST['apellido'];
        $_SESSION['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
        $_SESSION['universidad_id'] = $_POST['universidad'];
        header(header: "Location:./home.php");
        exit();
    } else {
        header(header: "Location:./editar_perfil.php?errPassw=0");
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
                    if ($_GET['errPassw'] == 0) {
                        echo "<div class='alert alert-danger'>Contraseña incorrecta, no se actualiza perfil.</div>";
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