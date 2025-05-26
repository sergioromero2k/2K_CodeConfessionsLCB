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
<section>
    <div class="flex-container-section" style="display: flex; justify-content: space-between;">
        <!-- Sidebar -->
        <div style="width: 25%;">
            <!-- Perfil -->
            <div class="item" style="background-color: green; padding: 10px;">
                <div class="flex" style="display: flex;">
                    <div style="background-color: red; width: 25%; text-align: center;">
                        <img src="./assets/images/profile-default.png" alt="Foto de perfil" style="width: 100%; border-radius: 50%;">
                    </div>
                    <div style="background-color: blue; width: 65%; padding-left: 10px;">
                        <h4 style="margin: 0;">
                            <?php
                            nombre_usuario();
                            ?>
                        </h4>
                        <p style="margin: 0;"><b>
                                <?php
                                universidad_usuario();
                                ?>
                            </b></p>
                    </div>
                </div>
                <hr>
                <div class="flex flex-around" style="display: flex; justify-content: space-around;">
                    <div>
                        <div>Publicaciones</div>
                        <div><?php echo total_publicaciones_user(user_id: $_SESSION['user_id']); ?></div>
                    </div>
                    <div>
                        <div>Me gustas</div>
                        <div><?php echo total_reacciones_user(user_id: $_SESSION['user_id'], tipo: "like"); ?></div>
                    </div>
                    <div>
                        <div>No Me gustas</div>
                        <div><?php echo total_reacciones_user(user_id: $_SESSION['user_id'], tipo: "dislike"); ?></div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Crear publicación -->
        <div class="item" style="background-color: pink; padding: 10px; margin-top: 10px;">
            <div><strong>Crear Publicación</strong></div>
            <hr>
            <div>
                <h5>Mensaje</h5>
                <form action="home.php" method="post">
                    <select name="universidad_a_publicar" id="universidad_a_publicar">
                        <?php
                        universidades();
                        ?>
                    </select><br><br>
                    <textarea name="contenido" id="contenido" placeholder="¿Sobre qué quiere hablar?" cols="30" rows="5" style="width: 100%;" required></textarea><br><br>
                    <input type="submit" value="Publicar" name="publicar_publicacion">
                </form>
            </div>
        </div>
    </div>
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
                $resultado_usuario = mostrar_dato(dato: 'nombre', tabla: 'usuarios', where: 'user_id', user_id: $fila['user_id']);
                $resultado_universidad = mostrar_dato(dato: 'universidad', tabla: 'universidades', where: 'universidad_id', user_id: $fila['universidad_id']);
                echo "<div class='flex' style='display: flex; background-color: green; padding: 10px; border-radius: 5px;'>";
                echo "<div style='background-color: red; width: 15%; text-align: center;'>";
                echo "<img src='./assets/images/profile-default.png' alt='Foto de perfil' style='width: 100%; border-radius: 50%;'>";
                echo "</div>";
                echo "<div style='background-color: blue; width: 85%; padding-left: 10px; color: white;'>";
                echo "<h3 style='margin: 0;'>" . $resultado_usuario . "</h3><br>";

                echo "<p style='margin: 0;'><b>" . $resultado_universidad . "</b></p>";
                echo "<p style='background-color: aqua; color: black; padding: 5px; border-radius: 4px;'>" . $fila['contenido'] . "</p>";
                echo "<div class='flex flex-around' style='display: flex; justify-content: space-around; margin-top: 10px;'>";

                echo "<form action='home.php' method='post'>";
                echo "<input type='hidden' name='publicacion_id' value='" . $fila['publicacion_id'] . "'>"; #  Obtener el ID de la publicación
                echo "<button name='tipo' value='like'><i class='fa-solid fa-thumbs-up'></i> Me gusta</button>" . mostrar_reaccion(publicacion_id: $fila['publicacion_id'], tipo: "like");
                echo "<button name='tipo' value='dislike'><i class='fa-solid fa-thumbs-down'></i> No me gusta</button>" . mostrar_reaccion(publicacion_id: $fila['publicacion_id'], tipo: "dislike");
                echo "<button name='tipo' value='comentario'><i class='fa-solid fa-comment'></i> Comentar</button>";
                echo "<button name='tipo' value='reportar'><i class='fa-solid fa-ban'></i> Reportar</button>";
                echo "</form>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <!-- Publicación -->


        </div>
    </div>

    </div>
</section>