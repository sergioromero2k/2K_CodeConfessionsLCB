<?php
require_once '../auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares


if (isset($_GET['notificacion_id'])) {
    $notificacion_id = intval($_GET['notificacion_id']);

    $stmt = $conexion_bbdd->prepare("UPDATE notificaciones SET estado = 1 WHERE notificacion_id = ? AND estado = 0");
    $stmt->bind_param('i', $notificacion_id);
    $stmt->execute();
    $stmt->close();
}

$ruta_defecto = '../public/uploads/profile_pics/profile-default.png';
$ruta_foto = '../public/uploads/profile_pics/';

if (!isset($_GET['publicacion_id']) || empty($_GET['publicacion_id'])) {
    echo "<p>Publicación no especificada.</p>";
    exit();
}

$publicacion_id = intval($_GET['publicacion_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comentario'])) {
    $user_id = $_SESSION['user_id'];
    $contenido_comentario = trim($_POST['comentario']);
    if (!empty($contenido_comentario)) {
        $stmt = $conexion_bbdd->prepare("INSERT INTO comentarios (publicacion_id, user_id, contenido) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error en prepare para comentarios: " . $conexion_bbdd->error);
        }
        $stmt->bind_param("iis", $publicacion_id, $user_id, $contenido_comentario);
        if ($stmt->execute()) {
            $stmt->close();

            $stmt_pub = $conexion_bbdd->prepare("SELECT user_id FROM publicaciones WHERE publicacion_id = ?");
            if (!$stmt_pub) {
                die("Error en prepare para obtener dueño: " . $conexion_bbdd->error);
            }

            $stmt_pub->bind_param("i", $publicacion_id);
            $stmt_pub->execute();
            $resultado_pub = $stmt_pub->get_result();
            $dueño_id = $resultado_pub->fetch_assoc()["user_id"];
            $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $user_id);

            $stmt_pub->close();

            if ($dueño_id != $user_id) {
                $nombre_notificacion = $nombre_usuario;
                $contenido_notificacion = $nombre_usuario . " comentó tu publicación";
                $estado = 0;
                $fecha_en = date('Y-m-d H:i:s');
                $tipo_notificacion_id = 3;

                $stmt_notif = $conexion_bbdd->prepare(
                    "INSERT INTO notificaciones (nombre, contenido, estado, fecha_en, publicacion_id, tipo_notificacion_id) VALUES (?, ?, ?, ?, ?, ?)"
                );

                if (!$stmt_notif) {
                    die("Error en prepare para notificaciones: " . $conexion_bbdd->error);
                }

                $stmt_notif->bind_param("ssisii", $nombre_notificacion, $contenido_notificacion, $estado, $fecha_en, $publicacion_id, $tipo_notificacion_id);

                if (!$stmt_notif->execute()) {
                    die("Error al insertar notificación: " . $stmt_notif->error);
                }
                $stmt_notif->close();
            }
            header("Location: ../controllers/comentar_publicacion.php?publicacion_id=$publicacion_id");
            exit();
        } else {
            $error_comentario = "Error al guardar el comentario: " . $stmt->error;
        }
    } else {
        $error_comentario = "El comentario no puede estar vacío.";
    }
}

$consulta = $conexion_bbdd->prepare("SELECT * FROM publicaciones WHERE publicacion_id = ?");
if (!$consulta) {
    die("Error en prepare para obtener publicación: " . $conexion_bbdd->error);
}
$consulta->bind_param("i", $publicacion_id);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    echo "<p>No se encontró la publicación.</p>";
    exit();
}

$fila = $resultado->fetch_assoc();
$nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
$nombre_universidad = universidad_usuario_get($fila['user_id']);
$foto_perfil = mostrar_foto_perfil($fila['user_id'], $ruta_foto, 'profile-default.png');

$stmt_comentarios = $conexion_bbdd->prepare("
    SELECT c.*, u.nombre FROM comentarios c 
    JOIN usuarios u ON c.user_id = u.user_id 
    WHERE c.publicacion_id = ? ORDER BY c.fecha_en DESC
");
if (!$stmt_comentarios) {
    die("Error en prepare para comentarios: " . $conexion_bbdd->error);
}
$stmt_comentarios->bind_param("i", $publicacion_id);
$stmt_comentarios->execute();
$result_comentarios = $stmt_comentarios->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Comentar publicación</title>
    <meta name="author" content="Sergio Alejandro Romero López" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/comentarios.css" />
    <link rel="stylesheet" href="../assets/css/nav.css" />
    <link rel="stylesheet" href="../assets/css/publicacion.css" />
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once '../includes/nav.php';
    nav(ruta_home: "../views/home.php", ruta_sobreNoso: "../views/sobre_nosotros.php", ruta_notificaciones: "../notificaciones/notificaciones.php", ruta_perfil: "../views/mi_perfil.php", editar_perfil: "./editar_perfil.php", cambiar_password: "cambiar_password.php", eliminar_cuenta: "./eliminar_cuenta.php");
    ?>

    <section class="d-flex justify-content-center" style="min-height: 100vh; margin: 20px;">
        <div class="col-md-8">
            <div class="timeline-post mb-3 item-publicacion p-3 border rounded" id="<?= htmlspecialchars($fila['publicacion_id']) ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex">
                        <a href="../views/mi_perfil.php?id=<?= $fila['user_id'] ?>">
                            <img src="<?= htmlspecialchars($foto_perfil) ?>" class="profile-pic-publi mr-3" alt="Foto de perfil">
                        </a>
                        <div>
                            <h6 class="mb-0">
                                <a href="../views/mi_perfil.php?id=<?= $fila['user_id'] ?>">
                                    <?= htmlspecialchars($nombre_usuario) ?>
                                </a>
                            </h6>
                            <small class="text-muted"><b><?= htmlspecialchars($nombre_universidad) ?></b></small>
                            <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id'] ?>">
                                <p class="mt-2 mb-1"><?= htmlspecialchars($fila['contenido']) ?></p>
                            </a>
                            <form action="../views/home.php" method="post" class="reaction-buttons d-flex gap-2 flex-wrap mt-2">
                                <input type="hidden" name="publicacion_id" value="<?= htmlspecialchars($fila['publicacion_id']) ?>">
                                <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                    <i class="fa-solid fa-thumbs-up"></i> <?= htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "like")) ?>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                    <i class="fa-solid fa-thumbs-down"></i> <?= htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "dislike")) ?>
                                </button>
                                <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id'] ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fa-solid fa-comment"></i> Comentar
                                </a>
                                <a href="reportar_publicacion.php?publicacion_id=<?= $fila['publicacion_id'] ?>" class="btn btn-outline-warning btn-sm ml-2">
                                    <i class="fa-regular fa-flag"></i> Reportar
                                </a>
                            </form>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $fila['user_id']): ?>
                        <form action="eliminar_publicacion.php" method="post">
                            <input type="hidden" name="publicacion_id" value="<?= $fila['publicacion_id'] ?>">
                            <input type="hidden" name="tipo" value="eliminar">
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta publicación?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <form action="" method="post" class="mb-4">
                <input type="hidden" name="publicacion_id" value="<?= htmlspecialchars($fila['publicacion_id']) ?>" />
                <div class="form-group">
                    <label for="comentario">Agregar comentario:</label>
                    <textarea name="comentario" id="comentario" class="form-control" rows="3" required></textarea>
                </div>
                <?php if (!empty($error_comentario)): ?>
                    <div class="alert alert-danger mt-2"><?= htmlspecialchars($error_comentario) ?></div>
                <?php endif; ?>
                <button type="submit" name="submit_comentario" class="btn btn-primary mt-2">Comentar</button>
            </form>

            <div class="comentarios mt-4">
                <h5>Comentarios (<?= $result_comentarios->num_rows ?>)</h5>
                <?php if ($result_comentarios->num_rows > 0): ?>
                    <?php while ($comentario = $result_comentarios->fetch_assoc()): ?>
                        <div class="comentario d-flex align-items-start mb-3">
                            <a href="../views/mi_perfil.phpid=<?= $comentario['user_id'] ?>">
                                <img src="<?= htmlspecialchars(mostrar_foto_perfil($comentario['user_id'], $ruta_foto, 'profile-default.png')) ?>"
                                    class="rounded-circle mr-3"
                                    alt="Foto de perfil"
                                    style="width: 45px; height: 45px; object-fit: cover;">
                            </a>
                            <div class="p-2 border rounded w-100">
                                <a href="mi_perfil.php?id=<?= $comentario['user_id'] ?>">
                                    <strong><?= htmlspecialchars($comentario['nombre']) ?></strong>
                                </a>
                                <small class="text-muted float-right"><?= htmlspecialchars($comentario['fecha_en']) ?></small>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hay comentarios aún. ¡Sé el primero!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>