<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares

if (!isset($_GET['ultimo_id'])) {
    exit('Falta el parámetro requerido.');
}

$ultimo_id = intval($_GET['ultimo_id']);
$user_id = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? intval($_GET['user_id']) : null;

$conexion_bbdd->set_charset("utf8");

$sql = "SELECT * FROM publicaciones ";
if ($user_id !== null) {
    $sql .= "WHERE user_id = $user_id AND publicacion_id < $ultimo_id ";
} else {
    $sql .= "WHERE publicacion_id < $ultimo_id ";
}
$sql .= "ORDER BY fecha_en DESC LIMIT 5";

$resultado = $conexion_bbdd->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
        $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
        $foto_perfil = './public/uploads/profile_pics/' . mostrar_dato('profile_image', 'usuarios', 'user_id', $fila['user_id']);
?>
        <div class="timeline-post mb-3 item-publicacion" id="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
            <div class="d-flex align-items-start">
                <div>
                    <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                        <img src="<?php echo htmlspecialchars($foto_perfil); ?>" class="profile-pic-publi me-3" alt="Foto de perfil">
                    </a>
                </div>
                <div>
                    <h6 class="mb-0">
                        <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                            <?php echo htmlspecialchars($nombre_usuario); ?>
                        </a>
                    </h6>
                    <small class="text-muted"><b><?php echo htmlspecialchars($nombre_universidad); ?></b></small>
                    <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>">
                        <p class="mt-2"><?php echo htmlspecialchars($fila['contenido']); ?></p>
                    </a>
                    <form action="home.php" method="post" class="reaction-buttons d-flex gap-2 flex-wrap">
                        <input type="hidden" name="publicacion_id" value="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
                        <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                            <i class="fa-solid fa-thumbs-up"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "like")); ?>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                            <i class="fa-solid fa-thumbs-down"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "dislike")); ?>
                        </button>
                        <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>" class="btn btn-outline-primary btn-sm">💬 Comentar</a>
                        <a href="reportar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>" class="btn btn-outline-warning btn-sm">🚫 Reportar</a>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="col-md-12 col-sm-12">
        <div class="ajax-loader text-center" style="display: none;">
            <img src="./assets/images/fonts/loading.gif" alt="Cargando... " class="img-fluid" style="width: 50px; height: 50px;">
            <br><strong>Cargando más Registros...</strong>
        </div>
    </div>
<?php
} else {
    echo "<p class='text-center'>No hay más publicaciones para mostrar.</p>";
    exit();
}
?>