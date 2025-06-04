<?php
require_once '../auth/checkAuth.php'; // Verifica si el usuario está autenticado
require_once '../includes/config.php'; // Configuración de la base de datos
require_once '../includes/functions.php'; // Funciones auxiliares

if (!isset($_SESSION['user_id'])) {
    die("Debes iniciar sesión para reportar.");
}

$user_reportador_id = $_SESSION['user_id'];
$publicacion_id = isset($_GET['publicacion_id']) ? intval($_GET['publicacion_id']) : 0;

if ($publicacion_id <= 0) {
    die("ID de publicación inválido.");
}

// Obtener info de la publicación para mostrar (opcional)
$query = $conexion_bbdd->prepare("SELECT user_id, contenido FROM publicaciones WHERE publicacion_id = ?");
$query->bind_param("i", $publicacion_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("La publicación no existe.");
}

$publicacion = $result->fetch_assoc();
$user_reportado_id = $publicacion['user_id'];
$contenido = $publicacion['contenido'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario
    $motivo_id = isset($_POST['motivo_id']) ? intval($_POST['motivo_id']) : 0;
    $confirmacion = isset($_POST['confirmacion']) ? $_POST['confirmacion'] : '';

    if ($motivo_id <= 0) {
        $error = "Por favor, selecciona un motivo.";
    } elseif ($confirmacion !== 'sí') {
        $error = "Debes confirmar que estás seguro de reportar.";
    } else {
        // Insertar el reporte
        $insert = $conexion_bbdd->prepare("INSERT INTO reportes (user_reportador_id, user_reportado_id, publicacion_id, motivo_id) VALUES (?, ?, ?, ?)");
        $insert->bind_param("iiii", $user_reportador_id, $user_reportado_id, $publicacion_id, $motivo_id);

        if ($insert->execute()) {
            $mensaje = "Reporte enviado correctamente.";
            // Opcional: redirigir o mostrar link para volver
        } else {
            $error = "Error al enviar el reporte.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reportar publicación</title>
    <meta name="author" content="Sergio Alejandro Romero López" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../assets/css/inicio.css" />
    <link rel="stylesheet" href="../assets/css/nav.css" />
    <link rel="stylesheet" href="../assets/css/publicacion.css" />
    <link rel="stylesheet" href="../assets/css/reportar.css" />

</head>

<body>
    <div class="container-center">
        <div class="card-report">
            <h1>Reportar</h1>
            <h4 class="mb-4">¿Por qué quieres reportar esta publicación?</h4>
            <p class="mb-4 text-left text-muted">
                Tu reporte es anónimo, a menos que reportes una infracción de la propiedad intelectual.<br />
                Si alguien se encuentra en peligro inminente, llama a los servicios de emergencias locales. No esperes.
            </p>

            <?php
            if (isset($mensaje)) {
                echo "<p class='text-success text-center font-weight-bold'>$mensaje</p>";
                echo '<p class="text-center"><a href="home.php" class="btn btn-primary mt-3">Volver al inicio</a></p>';
                exit;
            }
            if (isset($error)) {
                echo "<p class='text-danger text-center font-weight-bold'>$error</p>";
            }
            ?>

            <form action="reportar_publicacion.php?publicacion_id=<?= $publicacion_id ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="motivo_id">Selecciona el motivo del reporte:</label>
                    <select name="motivo_id" id="motivo_id" class="form-control" required>
                        <option value="">-- Selecciona un motivo --</option>
                        <option value="1">Contenido ofensivo</option>
                        <option value="2">Contenido sexual</option>
                        <option value="3">Contenido violento</option>
                        <option value="4">Contenido spam</option>
                        <option value="5">Contenido falso</option>
                        <option value="6">Contenido inapropiado</option>
                        <option value="7">Otro</option>
                    </select>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" name="confirmacion" value="sí" class="form-check-input" id="confirmacion" required>
                    <label class="form-check-label" for="confirmacion">Estoy seguro de querer reportar esta publicación</label>
                </div>
                <button type="submit" class="btn btn-warning btn-block">Enviar reporte</button>
            </form>

            <p class="mt-3 text-center"><a href="home.php">Cancelar y volver</a></p>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>

</body>

</html>