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
    <link rel="stylesheet" href="./assets/css/mi_perfil.css">
    <link rel="stylesheet" href="./assets/css/nav.css">


</head>

<body>
    <?php
    require_once './includes/nav.php'; // Navegación
    ?>
    <section class="flex-container-editar-perfil">
        <div class="item">
            <div>
                <div><img src="./assets/images/profile-default.png" width="30%" alt="Foto de perfil"></div>
            </div>
            <div>
                <h1><?php nombre_usuario(); ?></h1>
                <p><strong><?php universidad_usuario() ?></strong></p>
                <hr>

            </div>
            <div class="flex flex-around" style="display: flex; justify-content: space-around;">
                <div>Publicaciones</div>
                <div>Me gustas</div>
                <div>No Me gustas</div>
            </div>
            <hr>
            <div class="col-md-8">
                <?php

                $consulta = "SELECT * FROM publicaciones WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY fecha_en DESC";
                $resultado = $conexion_bbdd->query($consulta);

                while ($fila = $resultado->fetch_assoc()) {
                    $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
                    $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
                    $foto_perfil = './public/uploads/profile_pics/' . mostrar_Dato('profile_image', 'usuarios', 'user_id', $fila['user_id']);
                ?>
                    <div class="timeline-post mb-3">
                        <div class="d-flex align-items-start">
                            <div><img src="<?php echo $foto_perfil ?>" class="profile-pic-publi me-3" alt="Foto de perfil"></div>
                            <div>
                                <h6 class="mb-0"><?php echo $nombre_usuario; ?></h6>
                                <small class="text-muted"><b><?php echo $nombre_universidad; ?></b></small>
                                <p class="mt-2"><?php echo $fila['contenido']; ?></p>

                                <form action="home.php" method="post" class="reaction-buttons d-flex">
                                    <input type="hidden" name="publicacion_id" value="<?php echo $fila['publicacion_id']; ?>">
                                    <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                        <i class="fa-solid fa-thumbs-up"></i> <?php echo mostrar_reaccion($fila['publicacion_id'], "like"); ?>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                        <i class="fa-solid fa-thumbs-down"></i> <?php echo mostrar_reaccion($fila['publicacion_id'], "dislike"); ?>
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm" name="tipo" value="comentario">💬 Comentar</button>
                                    <button class="btn btn-outline-warning btn-sm" name="tipo" value="reportar">🚫 Reportar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>

        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>