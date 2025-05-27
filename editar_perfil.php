<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos  
require_once './includes/functions.php'; // Funciones auxiliares
$ruta_defecto = './public/uploads/profile_pics/profile-default.png';
if (isset($_POST['actualizar'])) {
    $password_usuario = password_usuario();
    # Prevenir inyecciones SQL
    if (password_verify(password: $_POST['password_usuario'], hash: $password_usuario)) {
        $ruta_relativa = procesamiento_foto_pefil("./public/uploads/profile_pics/", $ruta_defecto, "profile_pic", "./editar_perfil.php?errFrom=0");
        $consulta = $conexion_bbdd->prepare("UPDATE usuarios SET nombre=?,apellido=?,fecha_nacimiento=?,profile_image=?,universidad_id=? WHERE user_id=?");
        $consulta->bind_param("sssssi", $_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $ruta_relativa, $_POST['universidad'], $_SESSION['user_id']);
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
            <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
                <div>
                    <h3>Perfil</h3>
                    <p>Cambia tu foto de perfil y edita tu información personal.</p>
                </div>

                <div>
                    <div><img src="<?php echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], imagen_defecto: $ruta_defecto) ?>" width="30%" alt="Foto de perfil"></div>
                    <div>
                        <input type="file" name="profile_pic">
                        <p>JPG o PNG.</p>
                        TU foto se recortará automaticamente.
                    </div>
                    <?php
                    if (isset($_GET['errFrom'])) {
                        if ($_GET['errFrom'] == 0) {
                            echo "<div class='alert alert-danger'>Error al cargar la foto de perfil, no se actualiza perfil.</div>";
                        }
                    }
                    ?>
                </div>
                <div style="background-color: white;">
                    <label for="nombre">Nombres</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre']; ?>"><br>
                    <label for="apellido">Apellidos</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido']; ?>"><br>
                    <label for="fecha_nacimiento">Fecha Nacimiento</label><br>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php fecha_nacimiento_usuario(); ?>"><br>
                    <label for="universidad">Universidad o Instituto</label><br>
                    <select name="universidad" id="universidad">
                        <option value="<?php echo universidad_usuario_id() ?>" selected><?php universidad_usuario() ?></option>
                        <?php
                        universidades();
                        ?>
                    </select><br>
                    <label for="password_usuario">Introduce tu contraseña</label><br>
                    <input type="password" name="password_usuario" id="password_usuario" placeholder="Contreseña" required><br>
                    <input type="submit" value="Actualizar perfil" name="actualizar">

                    <?php
                    if (isset($_GET['errPassw'])) {
                        if ($_GET['errPassw'] == 0) {
                            echo "<div class='alert alert-danger'>Contraseña incorrecta, no se actualiza perfil.</div>";
                        }
                    }
                    ?>
                </div>
            </form>

        </div>


    </section>


    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>