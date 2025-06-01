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
    <link rel="stylesheet" href="./assets/css/inicio.css">
    <link rel="stylesheet" href="./assets/css/nav.css">
    <link rel="stylesheet" href="./assets/css/publicacion.css">
</head>

<body>
    <?php
    require_once './includes/nav.php'; // Incluye el encabezado
    $ruta_defecto = './public/uploads/profile_pics/profile-default.png';
    $ruta_foto = './public/uploads/profile_pics/';
    if (isset($_POST['publicar_publicacion']) and !empty($_POST['contenido'])) {
        $insertar = $conexion_bbdd->prepare(query: "INSERT INTO publicaciones ( user_id,universidad_id, contenido) VALUES (?, ?, ?)");
        $insertar->bind_param("iis", $_SESSION['user_id'], $_POST['universidad_a_publicar'], $_POST['contenido']);
        $insertar->execute();
        $insertar->close();
        header(header: "Location: home.php");
        exit();
    }
    if (isset($_POST['publicacion_id'])) {
        $publicacion_id = $_POST['publicacion_id'];
        $tipo = $_POST['tipo'];
        if ($tipo == 'like' || $tipo == 'dislike') {
            if (isset($_POST['publicacion_id'])) {
                $publicacion_id = $_POST['publicacion_id'];
                $tipo = $_POST['tipo'];

                if ($tipo == 'like' || $tipo == 'dislike') {
                    $reaccion = $conexion_bbdd->prepare(query: "INSERT INTO reacciones (user_id, publicacion_id, tipo) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE tipo = VALUES(tipo), fecha_en = NOW()");
                    $reaccion->bind_param("iis", $_SESSION['user_id'], $publicacion_id, $tipo);
                    $reaccion->execute();
                    header("Location: home.php");
                    exit();
                }
            }
        }
        exit();
    }
    ?>
    <section class="container-fluid mt-4" style="margin: 20px;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Perfil -->
                <div class="card">
                    <div class="d-flex align-items-center hola">
                        <div><img src="<?php echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png') ?>" class="profile-pic-seg me-3" alt="Foto de perfil"></div>
                        <div>
                            <h2 class="mb-0"><?php nombre_usuario(); ?></h2>
                            <h5> <?php universidad_usuario(); ?></h5>
                        </div>
                    </div>
                    <hr>
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
                            <label for="mensaje">Universidad o Instituto</label><br>
                            <select name="universidad_a_publicar" id="mensaje" class="form-select" required>
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
                    <form action="home.php" method="post" class="mb-3">
                        <label for="universidad_timeline">Filtrar por universidad</label>
                        <select name="universidad_timeline" id="universidad_timeline" class="form-select">
                            <?php universidades(); ?>
                        </select>
                    </form>

                    <?php
                    mostrar_publicaciones(); // Mostrar todas las publicaciones
                    ?>
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