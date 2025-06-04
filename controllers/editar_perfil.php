<?php
require_once '../auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares

$foto_defecto = 'profile-default.png';
$ruta_foto = '../public/uploads/profile_pics/';
if (isset($_POST['actualizar'])) {
    $password_usuario = password_usuario();
    # Prevenir inyecciones SQL
    if (password_verify(password: $_POST['password_usuario'], hash: $password_usuario)) {
        $ruta_relativa = procesamiento_foto_pefil("../public/uploads/profile_pics/", $foto_defecto, "profile_pic", "../editar_perfil.php?errFrom=0");
        $consulta = $conexion_bbdd->prepare("UPDATE usuarios SET nombre=?,apellido=?,fecha_nacimiento=?,profile_image=?,universidad_id=? WHERE user_id=?");
        $consulta->bind_param("sssssi", $_POST['nombre'], $_POST['apellido'], $_POST['fecha_nacimiento'], $ruta_relativa, $_POST['universidad'], $_SESSION['user_id']);
        $consulta->execute();
        $consulta->close();
        // Actualizar la sesión con los nuevos datos

        $_SESSION['nombre'] = $_POST['nombre'];
        $_SESSION['apellido'] = $_POST['apellido'];
        $_SESSION['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
        $_SESSION['universidad_id'] = $_POST['universidad'];
        header(header: "Location:../views/home.php");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/editar_perfil.css">
</head>

<body>
    <?php
    require_once '../includes/nav.php'; // Incluye el encabezado
    nav(ruta_home: "../views/home.php", ruta_sobreNoso: "../views/sobre_nosotros.php", ruta_notificaciones: "../notificaciones/notificaciones.php", ruta_perfil: "../views/mi_perfil.php", editar_perfil: "./editar_perfil.php", cambiar_password: "cambiar_password.php", eliminar_cuenta: "./eliminar_cuenta.php");
    ?>
    <section class="h-100 d-flex justify-content-center align-items-center">
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <div class="card-form">
                <div>
                    <div>
                        <h3>Perfil</h3>
                        <p>Cambia tu foto de perfil y edita tu información personal.</p>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 20px;">
                        <div><img src="<?php echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png') ?>" class="profile-pic2 me-2" alt="Foto de perfil"></div>
                        <div>
                            <div class="upload-container">
                                <label class="upload-button" for="profile_pic">Seleccionar imagen</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_GET['errFrom'])) {
                    if ($_GET['errFrom'] == 0) {
                        echo "<div class='alert alert-danger'>Error al cargar la foto de perfil, no se actualiza perfil.</div>";
                    }
                }
                ?>
                <div class="form-group">
                    <label for="nombre">Nombres</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre']; ?>">
                </div>
                <div class="form-group">
                    <label for="apellido">Apellidos</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido']; ?>">
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha Nacimiento</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php fecha_nacimiento_usuario(); ?>">
                </div>
                <div class="form-group">
                    <label for="universidad">Universidad o Instituto</label>
                    <select name="universidad" id="universidad" class="custom-select custom-select-lg mb-3">
                        <option value="<?php echo universidad_usuario_id() ?>" selected><?php universidad_usuario() ?></option>
                        <?php
                        // Función para mostrar las universidades
                        universidades();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password_usuario">Introduce tu contraseña</label>
                    <input type="password" class="form-control" name="password_usuario" id="password_usuario" placeholder="Contreseña" required>
                </div>
                <div style="text-align: center;">
                    <input type="submit" class="btn btn-primary" value="Actualizar perfil" name="actualizar">
                </div>

            </div>

        </form>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>