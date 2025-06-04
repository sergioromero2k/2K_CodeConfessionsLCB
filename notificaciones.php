<?php
require_once './includes/config.php';
require_once './auth/checkAuth.php';
require_once './includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Si se pasa una notificación por GET, la marcamos como leída y redirigimos
if (isset($_GET['id'])) {
    $notif_id = intval($_GET['id']);

    // Obtener la publicación asociada antes de actualizar (para no perder el dato)
    $redirigir = $conexion_bbdd->prepare("SELECT publicacion_id FROM notificaciones WHERE notificacion_id = ?");
    $redirigir->bind_param("i", $notif_id);
    $redirigir->execute();
    $redirigir->bind_result($pub_id);
    $redirigir->fetch();
    $redirigir->close();

    if ($pub_id) {
        // Marcar como leída
        $marcar = $conexion_bbdd->prepare("UPDATE notificaciones SET estado = 1 WHERE notificacion_id = ?");
        $marcar->bind_param("i", $notif_id);
        $marcar->execute();
        $marcar->close();

        // Redirigir a la publicación con la notificación
        header("Location: comentar_publicacion.php?publicacion_id=$pub_id&notificacion_id=$notif_id");
        exit();
    }
}

// Obtener todas las notificaciones del usuario, filtrando por publicaciones del usuario
$stmt = $conexion_bbdd->prepare("
    SELECT n.notificacion_id, n.nombre, n.contenido, n.fecha_en, n.estado, n.publicacion_id
    FROM notificaciones n
    INNER JOIN publicaciones p ON n.publicacion_id = p.publicacion_id
    WHERE p.user_id = ?
    ORDER BY n.fecha_en DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$notificaciones = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Notificaciones</title>
    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="./assets/css/inicio.css" />
    <link rel="stylesheet" href="./assets/css/nav.css" />
    <link rel="stylesheet" href="./assets/css/publicacion.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    <style>
        .no-leida {
            font-weight: bold;
            background-color: #f0f8ff;
        }

        .notificacion {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .notificacion:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include './includes/nav.php'; ?>

    <div class="container mt-4">
        <h3>Notificaciones</h3>
        <div class="list-group">
            <?php if (empty($notificaciones)): ?>
                <p class="text-muted">No tienes notificaciones.</p>
            <?php else: ?>
                <?php foreach ($notificaciones as $notif): ?>
                    <a href="notificaciones.php?id=<?= $notif['notificacion_id'] ?>"
                        class="list-group-item notificacion <?= $notif['estado'] == 0 ? 'no-leida' : '' ?>">
                        <?= htmlspecialchars($notif['contenido']) ?>
                        <br>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($notif['fecha_en'])) ?></small>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- Scripts Bootstrap y otros -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>