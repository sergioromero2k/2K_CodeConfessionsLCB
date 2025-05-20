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
                        <select name="universidad_publi" id="universidad_publi">
                            <?php
                            universidades();
                            ?>
                        </select><br><br>
                        <textarea name="publicacion" id="publicacion" placeholder="¿Sobre qué quiere hablar?" cols="30" rows="5" style="width: 100%;"></textarea><br><br>
                        <input type="submit" value="Publicar" name="publicar">
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

                <!-- Publicación -->
                <div class="flex" style="display: flex; background-color: green; padding: 10px; border-radius: 5px;">
                    <div style="background-color: red; width: 15%; text-align: center;">
                        <img src="./assets/images/profile-default.png" alt="Foto de perfil" style="width: 100%; border-radius: 50%;">
                    </div>
                    <div style="background-color: blue; width: 85%; padding-left: 10px; color: white;">
                        <h3 style="margin: 0;"> <?php
                                                nombre_usuario();
                                                ?></h3>
                        <p style="margin: 0;"><b> <?php
                                                    universidad_usuario();
                                                    ?></b></p>
                        <p style="background-color: aqua; color: black; padding: 5px; border-radius: 4px;">Hola, ¿cómo están?</p>
                        <div class="flex flex-around" style="display: flex; justify-content: space-around; margin-top: 10px;">
                            <button><i class="fa-solid fa-thumbs-up"></i> Me gusta</button>
                            <button><i class="fa-solid fa-thumbs-down"></i> No me gusta</button>
                            <button><i class="fa-solid fa-comment"></i> Comentar</button>
                            <button><i class="fa-solid fa-ban"></i> Reportar</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>