<?php
require_once './auth/checkAuth.php';
require_once './includes/config.php';
require_once './includes/functions.php';
$ruta_defecto = './public/uploads/profile_pics/profile-default.png';
$ruta_foto = './public/uploads/profile_pics/';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial LCB</title>
    <meta name="author" content="Sergio Alejandro Romero López">

    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/perfil.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/publicacion.css">

    <!-- jQuery COMPLETO para AJAX -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script></head>

<body>
    <?php require_once './includes/nav.php'; ?>

    <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-md-8" id="lista-publicaciones">
            <?php
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $user_id = $_GET['id'];
                mostrar_perfil($user_id, $ruta_foto);
                echo '<hr><div>';
                mostrar_publicaciones($user_id);
                echo '</div>';
            } else {
                $user_id = $_SESSION['user_id'];
                mostrar_perfil($user_id, $ruta_foto);
                echo '<hr><div>';
                mostrar_publicaciones($user_id);
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Scripts Bootstrap y otros -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6b5d7e1dcc.js" crossorigin="anonymous"></script>
</body>

</html>
