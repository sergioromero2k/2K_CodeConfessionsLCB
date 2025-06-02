<?php
require_once './auth/checkAuth.php';
require_once './includes/config.php';
require_once './includes/functions.php';

$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$filtro = isset($_POST['filtro']) ? $_POST['filtro'] : 'tabla1'; // tabla1 = usuarios, tabla2 = universidades

if ($q === '') {
    echo '<p>Por favor ingresa un término para buscar.</p>';
    exit;
}

$search = "%$q%";

if ($filtro === 'tabla1') {
    // Ya tienes este código para buscar usuarios, lo dejo resumido
    $consulta = $conexion_bbdd->prepare("SELECT user_id, universidad_id FROM usuarios WHERE nombre LIKE ? OR apellido LIKE ?;");
    $consulta->bind_param("ss", $search, $search);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 0) {
        echo '<p>No hay resultados para "' . htmlspecialchars($q) . '" en usuarios.</p>';
    } else {
        while ($fila = $resultado->fetch_assoc()) {
            $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']) . ' ' . mostrar_dato('apellido', 'usuarios', 'user_id', $fila['user_id']);
            $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
            $foto_perfil = './public/uploads/profile_pics/' . mostrar_dato('profile_image', 'usuarios', 'user_id', $fila['user_id']);
?>
            <div class="timeline-post mb-3 bord" id="user-<?php echo htmlspecialchars($fila['user_id']); ?>">
                <div class="d-flex align-items-start">
                    <div>
                        <a href="mi_perfil.php?id=<?php echo htmlspecialchars($fila['user_id']); ?>">
                            <img src="<?php echo htmlspecialchars($foto_perfil); ?>" class="profile-pic-publi me-3" alt="Foto de perfil">
                        </a>
                    </div>
                    <div>
                        <h6 class="mb-0">
                            <a href="mi_perfil.php?id=<?php echo htmlspecialchars($fila['user_id']); ?>">
                                <?php echo htmlspecialchars($nombre_usuario); ?>
                            </a>
                        </h6>
                        <small class="text-muted"><b><?php echo htmlspecialchars($nombre_universidad); ?></b></small>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    $consulta->close();
} elseif ($filtro === 'tabla2') {
    // Buscar universidades que coincidan con la búsqueda
    $consulta_uni = $conexion_bbdd->prepare("SELECT universidad_id, universidad, ciudad, pais FROM universidades WHERE universidad LIKE ?;");
    $consulta_uni->bind_param("s", $search);
    $consulta_uni->execute();
    $resultado_uni = $consulta_uni->get_result();

    if ($resultado_uni->num_rows === 0) {
        echo '<p>No hay universidades que coincidan con "' . htmlspecialchars($q) . '".</p>';
    } else {
        while ($uni = $resultado_uni->fetch_assoc()) {
            echo '<div class="universidad mb-4">';
            echo '<h5>' . htmlspecialchars($uni['universidad']) . ' (' . htmlspecialchars($uni['ciudad']) . ', ' . htmlspecialchars($uni['pais']) . ')</h5>';

            // Buscar publicaciones relacionadas a esta universidad
            $consulta_publi = $conexion_bbdd->prepare("SELECT publicacion_id, contenido, user_id, fecha_en FROM publicaciones WHERE universidad_id = ? ORDER BY fecha_en DESC;");
            $consulta_publi->bind_param("i", $uni['universidad_id']);
            $consulta_publi->execute();
            $resultado_publi = $consulta_publi->get_result();

            if ($resultado_publi->num_rows === 0) {
                echo '<p class="text-muted">No hay publicaciones relacionadas con esta universidad.</p>';
            } else {
                while ($publi = $resultado_publi->fetch_assoc()) {
                    // Puedes obtener el nombre del usuario que publicó
                    $autor_nombre = mostrar_dato('nombre', 'usuarios', 'user_id', $publi['user_id']) . ' ' . mostrar_dato('apellido', 'usuarios', 'user_id', $publi['user_id']);
                    $fecha = date('d/m/Y H:i', strtotime($publi['fecha_en']));
            ?>
                    <div class="publicacion border p-3 mb-2">
                        <p><?php echo nl2br(htmlspecialchars($publi['contenido'])); ?></p>
                        <small class="text-muted">Publicado por <strong><?php echo htmlspecialchars($autor_nombre); ?></strong> el <?php echo $fecha; ?></small>
                    </div>
<?php
                }
            }

            echo '</div>';
            $consulta_publi->close();
        }
    }
    $consulta_uni->close();
}

$conexion_bbdd->close();
?>