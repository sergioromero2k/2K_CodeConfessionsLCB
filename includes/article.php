<?php
require_once './includes/config.php';
require_once './includes/functions.php'; // Funciones auxiliares
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
?>
<!-- Timeline -->
<div style="width: 65%;height: 100%;">
    <div style="padding: 10px;">
        <p><strong>Timeline</strong></p>
        <form action="home.php" method="post">
            <select name="universidad_timeline" id="universidad_timeline">
                <?php
                universidades();
                ?>
            </select>
        </form>
        <hr>

        <?php
        // Muestra las publicaciones
        $consulta = "SELECT * FROM publicaciones ORDER BY fecha_en DESC";
        $resultado = $conexion_bbdd->query($consulta);

        while ($fila = $resultado->fetch_assoc()) {
            // Obtener el nombre del usuario
            $resultado_usuario = $conexion_bbdd->query("SELECT nombre FROM usuarios WHERE user_id = " . $fila['user_id']);
            $resultado_usuario = $resultado_usuario->fetch_assoc();
            $resultado_usuario = $resultado_usuario['nombre'];

            // Obtener el nombre de la universidad de la publicación
            $resultado_universidad = $conexion_bbdd->query("SELECT universidad FROM universidades WHERE universidad_id = " . $fila['universidad_id']);
            $resultado_universidad = $resultado_universidad->fetch_assoc();
            $resultado_universidad = $resultado_universidad['universidad'];

            echo "<div class='flex' style='display: flex; background-color: green; padding: 10px; border-radius: 5px;'>";
            echo "<div style='background-color: red; width: 15%; text-align: center;'>";
            echo "<img src='./assets/images/profile-default.png' alt='Foto de perfil' style='width: 100%; border-radius: 50%;'>";
            echo "</div>";
            echo "<div style='background-color: blue; width: 85%; padding-left: 10px; color: white;'>";
            echo "<h3 style='margin: 0;'>" . $resultado_usuario . "</h3><br>";
            
            echo "<p style='margin: 0;'><b>" . $resultado_universidad . "</b></p>";
            echo "<p style='background-color: aqua; color: black; padding: 5px; border-radius: 4px;'>" . $fila['contenido'] . "</p>";
            echo "<div class='flex flex-around' style='display: flex; justify-content: space-around; margin-top: 10px;'>";
            echo "<button><i class='fa-solid fa-thumbs-up'></i> Me gusta</button>";
            echo "<button><i class='fa-solid fa-thumbs-down'></i> No me gusta</button>";
            echo "<button><i class='fa-solid fa-comment'></i> Comentar</button>";
            echo "<button><i class='fa-solid fa-ban'></i> Reportar</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
        <!-- Publicación -->


    </div>
</div>