<?php
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once './includes/config.php'; // Configuración de la base de datos
require_once './includes/functions.php'; // Funciones auxiliares

$ruta_defecto = './public/uploads/profile_pics/profile-default.png';
$ruta_foto = './public/uploads/profile_pics/';

if (!isset($_GET['publicacion_id']) || empty($_GET['publicacion_id'])) {
    echo "<p>Publicación no especificada.</p>";
    exit();
}

$publicacion_id = intval($_GET['publicacion_id']);

// Procesar comentario enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comentario'])) {
    $user_id = $_SESSION['user_id']; // Asumiendo sesión con user_id
    $contenido = trim($_POST['comentario']);

    if (!empty($contenido)) {
        $stmt = $conexion_bbdd->prepare("INSERT INTO comentarios (publicacion_id, user_id, contenido) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $publicacion_id, $user_id, $contenido);
        if ($stmt->execute()) {
            // Redirigir para evitar reenvío del formulario
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            $error_comentario = "Error al guardar el comentario.";
        }
    } else {
        $error_comentario = "El comentario no puede estar vacío.";
    }
}

// Obtener publicación
$consulta = $conexion_bbdd->prepare("SELECT * FROM publicaciones WHERE publicacion_id = ?");
$consulta->bind_param("i", $publicacion_id);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows === 0) {
    echo "<p>No se encontró la publicación.</p>";
    exit();
}

$fila = $resultado->fetch_assoc();

// Datos usuario publicación
$nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
$nombre_universidad = universidad_usuario_get($fila['user_id']);
$foto_perfil = mostrar_foto_perfil(user_id: $fila['user_id'], ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png');

// Obtener comentarios de la publicación
$stmt_comentarios = $conexion_bbdd->prepare("SELECT c.*, u.nombre FROM comentarios c JOIN usuarios u ON c.user_id = u.user_id WHERE c.publicacion_id = ? ORDER BY c.fecha_en DESC");
$stmt_comentarios->bind_param("i", $publicacion_id);
$stmt_comentarios->execute();
$result_comentarios = $stmt_comentarios->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pagina incial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/comentarios.css" />
    <link rel="stylesheet" href="./assets/css/nav.css" />
    <link rel="stylesheet" href="assets/css/publicacion.css" />
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php require_once './includes/nav.php'; ?>

    <section class="d-flex justify-content-center" style="min-height: 100vh; margin: 20px;">
        <div class="col-md-8">

            <div class="timeline-post mb-3">
                <div class="d-flex align-items-start">
                    <div>
                        <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                            <img src="<?= htmlspecialchars($foto_perfil) ?>" class="profile-pic-publi me-3" alt="Foto de perfil" />
                        </a>
                    </div>
                    <div>
                        <h6 class="mb-0">
                            <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                                <?= htmlspecialchars($nombre_usuario) ?>
                            </a>
                        </h6>
                        <small class="text-muted"><b><?= htmlspecialchars($nombre_universidad) ?></b></small>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($fila['contenido'])) ?></p>

                        <form action="home.php" method="post" class="reaction-buttons d-flex mb-3">
                            <input type="hidden" name="publicacion_id" value="<?= htmlspecialchars($fila['publicacion_id']) ?>" />
                            <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                <i class="fa-solid fa-thumbs-up"></i> <?= htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "like")) ?>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                <i class="fa-solid fa-thumbs-down"></i> <?= htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "dislike")) ?>
                            </button>
                            <a href="comentar_publicacion.php" class="btn btn-outline-primary btn-sm ml-2">💬 Comentar</a>
                            <a href="comentar_publicacion.php" class="btn btn-outline-warning btn-sm ml-2">🚫 Reportar</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario para agregar comentario -->
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

            <!-- Mostrar comentarios -->
            <div class="comentarios mt-4">
                <h5>Comentarios (<?= $result_comentarios->num_rows ?>)</h5>
                <?php if ($result_comentarios->num_rows > 0): ?>
                    <?php while ($comentario = $result_comentarios->fetch_assoc()): ?>
                        <div class="comentario d-flex align-items-start mb-3">
                            <!-- Imagen del usuario -->
                            <a href="mi_perfil.php?id=<?= $comentario['user_id'] ?>">
                                <img src="<?= htmlspecialchars(mostrar_foto_perfil(user_id: $comentario['user_id'], ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png')) ?>"
                                    class="rounded-circle mr-3"
                                    alt="Foto de perfil"
                                    style="width: 45px; height: 45px; object-fit: cover;">
                            </a>

                            <!-- Contenido del comentario -->
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