<?php
require_once './includes/config.php';
require_once './includes/functions.php'; // Funciones auxiliares
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado

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
                <div class="d-flex align-items-center">
                    <div><img src="<?php echo mostrar_foto_perfil(user_id: $_SESSION['user_id'], imagen_defecto: $ruta_defecto) ?>" class="profile-pic2 me-3"alt="Foto de perfil"></div>
                    <div>
                        <h5 class="mb-0"><?php nombre_usuario(); ?></h5>
                        <small><b><?php universidad_usuario(); ?></b></small>
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
                        <button type="reset" class="btn btn-secondary" >Limpiar</button>
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
                $consulta = "SELECT * FROM publicaciones ORDER BY fecha_en DESC";
                $resultado = $conexion_bbdd->query($consulta);

                while ($fila = $resultado->fetch_assoc()) {
                    $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
                    $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
                ?>
                    <div class="timeline-post mb-3">
                        <div class="d-flex align-items-start">
                            <img src="./assets/images/profile-default.png" class="profile-pic me-3" alt="Foto perfil">
                            <div>
                                <h6 class="mb-0"><?php echo $nombre_usuario; ?></h6>
                                <small class="text-muted"><b><?php echo $nombre_universidad; ?></b></small>
                                <p class="mt-2"><?php echo $fila['contenido']; ?></p>

                                <form action="home.php" method="post" class="reaction-buttons d-flex">
                                    <input type="hidden" name="publicacion_id" value="<?php echo $fila['publicacion_id']; ?>">
                                    <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                        👍 <?php echo mostrar_reaccion($fila['publicacion_id'], "like"); ?>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                        👎 <?php echo mostrar_reaccion($fila['publicacion_id'], "dislike"); ?>
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
    </div>
</section>