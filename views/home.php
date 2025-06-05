<?php
require_once '../auth/checkAuth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (isset($_POST['publicar_publicacion']) && !empty($_POST['contenido'])) {
    $insertar = $conexion_bbdd->prepare("INSERT INTO publicaciones (user_id, universidad_id, contenido) VALUES (?, ?, ?)");
    $insertar->bind_param("iis", $_SESSION['user_id'], $_POST['universidad_a_publicar'], $_POST['contenido']);
    $insertar->execute();
    $insertar->close();
    header("Location: home.php");
    exit();
}

if (isset($_POST['publicacion_id'])) {
    $publicacion_id = $_POST['publicacion_id'];
    $tipo = $_POST['tipo'] ?? '';

    if ($tipo === 'like' || $tipo === 'dislike') {
        $reaccion = $conexion_bbdd->prepare("INSERT INTO reacciones (user_id, publicacion_id, tipo) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE tipo = VALUES(tipo), fecha_en = NOW()");
        $reaccion->bind_param("iis", $_SESSION['user_id'], $publicacion_id, $tipo);
        $reaccion->execute();
        $reaccion->close();

        $stmt_public = $conexion_bbdd->prepare("SELECT user_id FROM publicaciones WHERE publicacion_id = ?");
        $stmt_public->bind_param("i", $publicacion_id);
        $stmt_public->execute();
        $resultado = $stmt_public->get_result();
        $dueño_id = $resultado->fetch_assoc()["user_id"];

        if ($dueño_id != $_SESSION['user_id']) {
            $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $_SESSION['user_id']);
            $mensaje = "$nombre_usuario ha dado $tipo a tu publicación.";
            $tipo_notificacion_id = ($tipo === 'like') ? 1 : 2;

            $stmt_notif = $conexion_bbdd->prepare("INSERT INTO notificaciones (nombre, contenido, publicacion_id, tipo_notificacion_id) VALUES (?, ?, ?, ?)");
            $stmt_notif->bind_param("ssii", $nombre_usuario, $mensaje, $publicacion_id, $tipo_notificacion_id);
            $stmt_notif->execute();
            $stmt_notif->close();
        }

        header("Location: home.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página inicial LCB</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/inicio.css" />
    <link rel="stylesheet" href="../assets/css/nav.css" />
    <link rel="stylesheet" href="../assets/css/publicacion.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once '../includes/nav.php';
    nav(
        ruta_home: "home.php",
        ruta_sobreNoso: "sobre_nosotros.php",
        ruta_notificaciones: "../notificaciones/notificaciones.php",
        ruta_buscar: "../controllers/buscar.php",
        ruta_perfil: "./mi_perfil.php",
        editar_perfil: "../controllers/editar_perfil.php",
        cambiar_password: "../controllers/cambiar_password.php",
        eliminar_cuenta: "../controllers/eliminar_cuenta.php"
    );
    ?>

    <section class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 mb-4¡">
                <!-- Perfil -->
                <div class="card">
                    <div class="d-flex align-items-center">
                        <div>
                            <a href="mi_perfil.php?id=<?php echo $_SESSION['user_id'] ?>">
                                <img src="<?php echo mostrar_foto_perfil($_SESSION['user_id'], '../public/uploads/profile_pics/', 'profile-default.png'); ?>" class="profile-pic-seg" alt="Foto de perfil" />
                            </a>
                        </div>
                        <div>
                            <h4 class="mb-0"><?php nombre_usuario(); ?></h4>
                            <p><strong><?php universidad_usuario(); ?></strong></p>
                        </div>
                    </div>
                    <hr />
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <div>Publicaciones</div>
                            <strong><?php echo total_publicaciones_user($_SESSION['user_id']); ?></strong>
                        </div>
                        <div>
                            <div>Me gustas</div>
                            <strong><?php echo total_reacciones_user($_SESSION['user_id'], "like"); ?></strong>
                        </div>
                        <div>
                            <div>No Me gustas</div>
                            <strong><?php echo total_reacciones_user($_SESSION['user_id'], "dislike"); ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Crear Publicación -->
                <div class="card mt-3">
                    <h5>Crear Publicación</h5>
                    <form action="home.php" method="post">
                        <div class="mb-2">
                            <label for="mensaje">Universidad o Instituto</label><br />
                            <select name="universidad_a_publicar" id="mensaje" class="form-control" required>
                                <?php universidades(); ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="contenido">Mensaje</label>
                            <textarea name="contenido" id="contenido" class="form-control" rows="4" placeholder="¿Sobre qué quiere hablar?" required></textarea>
                        </div>
                        <div class="text-end" style="text-align: right;">
                            <button type="reset" class="btn btn-secondary">Limpiar</button>
                            <button type="submit" name="publicar_publicacion" class="btn btn-primary">Publicar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="col-md-8">
                <div class="card">
                    <h4>Línea de tiempo</h4>
                    <hr />
                    <div id="lista-publicaciones">
                        <?php mostrar_publicaciones(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>