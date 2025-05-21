<?php
require_once './includes/config.php';
require_once './includes/functions.php'; // Funciones auxiliares
require_once './auth/checkAuth.php'; // Verifica si el usuario está autenticado
if (isset($_POST['publicar_publicacion'])) {
    $insertar = $conexion_bbdd->prepare(query: "INSERT INTO publicaciones ( user_id,universidad_id, contenido) VALUES (?, ?, ?)");
    $insertar->bind_param("iis", $_SESSION['user_id'], $_POST['universidad_a_publicar'], $_POST['contenido']);
    $insertar->execute();
    $insertar->close();
    header(header: "Location: home.php");
    exit();
}
?>
<section>
    <div class="flex-container-section" style="display: flex; justify-content: space-between;">
        <!-- Sidebar -->
        <div style="width: 30%;">
            <!-- Perfil -->
            <div class="item" style="background-color: green; padding: 10px;">
                <div class="flex" style="display: flex;">
                    <div style="background-color: red; width: 25%; text-align: center;">
                        <img src="./assets/images/profile-default.png" alt="Foto de perfil" style="width: 100%; border-radius: 50%;">
                    </div>
                    <div style="background-color: blue; width: 75%; padding-left: 10px;">
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
                    <div>Publicaciones</div>
                    <div>Me gustas</div>
                    <div>No Me gustas</div>
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
                        <textarea name="contenido" id="contenido" placeholder="¿Sobre qué quiere hablar?" cols="30" rows="5" style="width: 100%;"></textarea><br><br>
                        <input type="submit" value="Publicar" name="publicar_publicacion">
                    </form>
                </div>
            </div>
        </div>
        <?php
        require_once './includes/article.php'; // Muestra las publicaciones
        ?>

    </div>
</section>