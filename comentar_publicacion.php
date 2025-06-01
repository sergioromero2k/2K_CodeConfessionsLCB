<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/comentarios.css">
    <link rel="stylesheet" href="./assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/publicacion.css">

</head>

<body>
    <?php
    require_once './includes/nav.php'; // Incluye el encabezado
    $ruta_defecto = './public/uploads/profile_pics/profile-default.png';
    $ruta_foto = './public/uploads/profile_pics/';
    if (isset($_GET['publicacion_id']) && !empty($_GET['publicacion_id'])) {
        $publicacion_id = $_GET['publicacion_id'];
        $consulta = $conexion_bbdd->prepare($consulta = "SELECT * FROM publicaciones WHERE publicacion_id = ?");
        $consulta->bind_param("i", $publicacion_id);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            // Obtener el nombre de usuario y universidad
            $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
            $nombre_universidad = universidad_usuario_get($fila['user_id']);
            $foto_perfil = mostrar_foto_perfil(user_id: $fila['user_id'], ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png');
        } else {
            echo "<p>No se encontró la publicación.</p>";
            exit();
        }
    } else {
        echo "<p>Publicación no especificada.</p>";
        exit();
    }
    ?>
    <section class="d-flex  justify-content-center" style="min-height: 100vh;margin: 20px;">
        <div class="col-md-8">
            <div class="timeline-post mb-3">
                <div class="d-flex align-items-start">
                    <div>
                        <div><a href="mi_perfil.php?id=<?= $fila['user_id'] ?>"><img src="<?php echo $foto_perfil ?>" class="profile-pic-publi me-3" alt="Foto de perfil"></a></div>
                    </div>
                    <div>
                        <!-- Enlace al perfil del usuario -->
                        <h6 class="mb-0">
                            <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                                <?php echo htmlspecialchars($nombre_usuario); ?>
                            </a>
                        </h6>
                        <small class="text-muted"><b><?php echo htmlspecialchars($nombre_universidad); ?></b></small>
                        <a href="comentar_publicacion.php">
                            <p class="mt-2"><?php echo $fila['contenido']; ?></p>
                        </a>

                        <form action="home.php" method="post" class="reaction-buttons d-flex">
                            <input type="hidden" name="publicacion_id" value="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
                            <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                <i class="fa-solid fa-thumbs-up"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "like")); ?>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                <i class="fa-solid fa-thumbs-down"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "dislike")); ?>
                            </button>
                            <a href="http:comentar_publicacion.php"><button class="btn btn-outline-primary btn-sm" name="tipo" value="comentario">💬 Comentar</button></a>
                            <a href="http:comentar_publicacion.php"><button class="btn btn-outline-warning btn-sm" name="tipo" value="reportar">🚫 Reportar</button></a>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>