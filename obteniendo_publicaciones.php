<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares
sleep(1); // Simula un retraso de 1 segundo para la carga de la página
$ultimo_id = isset($_GET['ultimo_id']) ? intval($_GET['ultimo_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$limite = 5;
global $conexion_bbdd;
// Si se proporciona un user_id válido, mostrar solo sus publicaciones
if ($user_id !== null) {
    $user_id = intval($user_id);
    $consulta_limite = "SELECT * FROM publicaciones WHERE user_id = $user_id AND publicacion_id < $ultimo_id ORDER BY fecha_en DESC LIMIT 5";
} else {
    // Si no se proporciona user_id, mostrar todas las publicaciones
    $consulta_limite = "SELECT * FROM publicaciones WHERE publicacion_id < $ultimo_id ORDER BY fecha_en DESC LIMIT 5";
}
$resultado = $conexion_bbdd->query($consulta_limite);
if ($resultado) {
    // Verificar si hay publicaciones
    $total_publicaciones = $resultado->num_rows;
    echo "<input type='hidden' name='total_publicaciones' id='total_publicaciones' value='.echo $total_publicaciones.'>";  // Agregar un campo oculto para el total de publicaciones
    while ($fila = $resultado->fetch_assoc()) {
        $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
        $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
        $foto_perfil = './public/uploads/profile_pics/' . mostrar_dato('profile_image', 'usuarios', 'user_id', $fila['user_id']);
?>
        <div class="timeline-post mb-3 item-publicacion" id="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
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
                    <a href="comentar_publicacion.php?publicacion_id=<?php echo $fila['publicacion_id']; ?>">
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
    <?php
    }
    if ($total_publicaciones < $limite) {
        echo "<p class='text-center'>No hay más publicaciones para mostrar.</p>";
    } else {
    ?>
        <div class="col-md-12 col-sm-12">
            <div class="ajax-loader text-center">
                <img src="./assets/images/fonts/loading.gif" alt="Cargando... " class="img-fluid" style="width: 50px; height: 50px;">
                <br>
                <strong>Cargando más Registros...</strong>
            </div>
        </div>
<?php
    }
} 
?>