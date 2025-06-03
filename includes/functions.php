<?php

// Eliminar
function nombre_usuario(): void
{
    global $conexion_bbdd;
    echo $_SESSION['nombre'] . " " . $_SESSION['apellido'];
}

function nombres_usuario_get(int $user_id): string
{
    global $conexion_bbdd;
    $consulta = "SELECT nombre, apellido FROM usuarios WHERE user_id = $user_id";
    $resultado = $conexion_bbdd->query(query: $consulta);
    if ($resultado) {
        $fila = $resultado->fetch_assoc();
        return $fila['nombre'] . " " . $fila['apellido'];
    }
    return '';
}
// Eliminar
function universidad_usuario_id(): mixed
{
    global $conexion_bbdd;
    $universidad_id = "SELECT universidad_id FROM usuarios WHERE user_id = $_SESSION[user_id]";
    $universidad_id = $conexion_bbdd->query(query: $universidad_id);
    $universidad_id = $universidad_id->fetch_assoc();
    return $universidad_id['universidad_id'];
}

function universidad_usuario_id_get($user_id): mixed
{
    global $conexion_bbdd;
    $universidad_id = "SELECT universidad_id FROM usuarios WHERE user_id = $user_id";
    $universidad_id = $conexion_bbdd->query(query: $universidad_id);
    $universidad_id = $universidad_id->fetch_assoc();
    return $universidad_id['universidad_id'];
}
function universidad_usuario(): void
{
    $universidad_id = universidad_usuario_id();
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT universidad FROM universidades WHERE universidad_id = $universidad_id");
    $resultado = $resultado->fetch_assoc();
    echo $resultado['universidad'];
}
function universidad_usuario_get($user_id): string
{
    $universidad_id = universidad_usuario_id_get($user_id);
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT universidad FROM universidades WHERE universidad_id = $universidad_id");
    $resultado = $resultado->fetch_assoc();
    return $resultado['universidad'];
}
function universidades(): void
{
    global $conexion_bbdd;
    $universidad = "SELECT * FROM universidades";
    $universidad = $conexion_bbdd->query(query: $universidad);
    while ($fila = $universidad->fetch_assoc()) {
        echo "<option value='$fila[universidad_id]'>$fila[universidad]</option>";
    }
}
function generos(): void
{
    global $conexion_bbdd;
    $genero = "SELECT genero FROM generos";
    $genero = $conexion_bbdd->query(query: $genero);
    while ($fila = $genero->fetch_assoc()) {
        echo "<input type='radio' name='genero' class='genero' value='$fila[genero_id]'>$fila[genero]</input>";
    }
}
function fecha_nacimiento_usuario(): void
{
    global $conexion_bbdd;
    $fecha_nacimiento = "SELECT fecha_nacimiento FROM usuarios WHERE user_id = $_SESSION[user_id]";
    $fecha_nacimiento = $conexion_bbdd->query(query: $fecha_nacimiento);
    $fecha_nacimiento = $fecha_nacimiento->fetch_assoc();
    echo $fecha_nacimiento['fecha_nacimiento'];
}

function password_usuario(): mixed
{
    global $conexion_bbdd;
    $password = "SELECT password FROM usuarios WHERE user_id = $_SESSION[user_id]";
    $password = $conexion_bbdd->query(query: $password);
    $password = $password->fetch_assoc();
    return $password['password'];
}

function mostrar_dato(string $dato, string $tabla, string $where, int $user_id): mixed
{
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT $dato FROM $tabla WHERE $where =" . $user_id);
    $resultado = $resultado->fetch_assoc();
    return $resultado[$dato];
}
function mostrar_reaccion(int $publicacion_id, $tipo): int
{
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT COUNT(*) as total FROM reacciones WHERE publicacion_id = $publicacion_id 
    AND tipo = '$tipo'");
    if (!$resultado) {
        return 0; // Si no hay resultados, retornamos 0
    }
    $resultado = $resultado->fetch_assoc();
    return (int)$resultado['total']; // Aseguramos que el resultado sea un entero
}
function total_reacciones_user(int $user_id, $tipo): int
{
    global $conexion_bbdd;
    $cuenta_publicaciones = "SELECT publicacion_id as total FROM publicaciones WHERE user_id = $user_id";
    $cuenta_publicaciones = $conexion_bbdd->query(query: $cuenta_publicaciones);
    $reacciones = array();
    while ($fila = $cuenta_publicaciones->fetch_assoc()) {
        $publicacion_id = $fila['total'];
        $resultado = $conexion_bbdd->query(query: "SELECT count(*) as total FROM reacciones WHERE tipo = '$tipo' AND publicacion_id=$publicacion_id");
        array_push($reacciones, $resultado->fetch_assoc()['total']);
    }
    $total_reacciones = array_sum($reacciones); // Suma todas las reacciones
    return (int) $total_reacciones; // Aseguramos que el resultado sea un entero
}
function total_publicaciones_user(int $user_id): int
{
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT COUNT(*) as total FROM publicaciones WHERE user_id = $user_id");
    if (!$resultado) {
        return 0; # Si no hay resultados, retornamos 0
    }
    $resultado = $resultado->fetch_assoc();
    return (int)$resultado['total']; # Aseguramos que el resultado sea un entero
}

function procesamiento_foto_pefil($ruta_dir, $foto_defecto, $name, $error_formato): string
{
    // Crear una carperta si no existe
    if (!is_dir($ruta_dir)) {
        mkdir(directory: $ruta_dir, permissions: 0755, recursive: true);
    }
    // Verificar si se ha subido una imagen
    if (isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK) {
        $tmp_archivo = $_FILES[$name]['tmp_name'];
        $nombre_archivo = basename($_FILES[$name]['name']);
        $extension_archivo = strtolower(pathinfo(path: $nombre_archivo, flags: PATHINFO_EXTENSION));

        $permitidos = ['jpg', 'jpeg', 'png'];
        // Verificar la extensión del archivo
        if (!in_array(needle: $extension_archivo, haystack: $permitidos)) {
            header(header: "Location:$error_formato");
            exit();
        }
        // Generar un nombre único para la imagen
        $nuevo_nombre_archivo = uniqid('profile_', true) . '.' . $extension_archivo;
        $ruta_completa = $ruta_dir . $nuevo_nombre_archivo;

        // Mover el archivo subido a la carpeta de destino
        move_uploaded_file(from: $tmp_archivo, to: $ruta_completa);
        // Actualizar la ruta relativa de la imagen en la base de datos
        return $nuevo_nombre_archivo;  // Retorna la ruta relativa de la imagen subida
    } else {
        return $foto_defecto; // Si no se subió una imagen, retornar la imagen por defecto
    }
}
function mostrar_foto_perfil(int $user_id, $ruta_imagen, $imagen_defecto): string
{
    global $conexion_bbdd;
    $consulta = $conexion_bbdd->query(query: "SELECT profile_image FROM usuarios WHERE user_id = $user_id");
    if ($consulta) {
        $resultado = $consulta->fetch_assoc();
        if ($resultado && !empty($resultado['profile_image'])) {
            $ruta_imagen_perfil = $ruta_imagen . $resultado['profile_image'];
            // Verificar si el archivo existe
            if (file_exists(filename: $ruta_imagen_perfil)) {
                return $ruta_imagen_perfil; // Retorna la ruta de la imagen del perfil
            }
        }
    }
    // Si no se encuentra la imagen del perfil o no existe, retornar la imagen por defecto
    return  $ruta_imagen . $imagen_defecto; // Retorna la imagen por defecto si no se encuentra
}

// Muestra las publicaciones de un usuario específico o todos
function mostrar_publicaciones($user_id = null)
{
    global $conexion_bbdd;

    $user_id = isset($user_id) ? intval($user_id) : null;

    // Consulta para el total
    $consulta_total = $user_id !== null
        ? "SELECT * FROM publicaciones WHERE user_id = $user_id"
        : "SELECT * FROM publicaciones";

    $resultado_total = $conexion_bbdd->query($consulta_total);
    $total_publicaciones = $resultado_total->num_rows;

    // Consulta con límite inicial de 5
    $consulta_limite = $user_id !== null
        ? "SELECT * FROM publicaciones WHERE user_id = $user_id ORDER BY fecha_en DESC LIMIT 5"
        : "SELECT * FROM publicaciones ORDER BY fecha_en DESC LIMIT 5";

    $resultado = $conexion_bbdd->query($consulta_limite);

    if ($resultado) {
        if ($user_id !== null) {
            echo "<input type='hidden' name='user_id' id='user_id' value='" . htmlspecialchars($user_id) . "'>";
        }
        echo "<input type='hidden' name='total_publicaciones' id='total_publicaciones' value='" . htmlspecialchars($total_publicaciones) . "'>";

        while ($fila = $resultado->fetch_assoc()) {
            $nombre_usuario = mostrar_dato('nombre', 'usuarios', 'user_id', $fila['user_id']);
            $nombre_universidad = mostrar_dato('universidad', 'universidades', 'universidad_id', $fila['universidad_id']);
            $foto_perfil = './public/uploads/profile_pics/' . mostrar_dato('profile_image', 'usuarios', 'user_id', $fila['user_id']);
?>
            <div class="timeline-post mb-3 item-publicacion p-3 border rounded" id="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex">
                        <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                            <img src="<?php echo htmlspecialchars($foto_perfil); ?>" class="profile-pic-publi me-3" alt="Foto de perfil">
                        </a>
                        <div>
                            <h6 class="mb-0">
                                <a href="mi_perfil.php?id=<?= $fila['user_id'] ?>">
                                    <?php echo htmlspecialchars($nombre_usuario); ?>
                                </a>
                            </h6>
                            <small class="text-muted"><b><?php echo htmlspecialchars($nombre_universidad); ?></b></small>
                            <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>">
                                <p class="mt-2 mb-1"><?php echo htmlspecialchars($fila['contenido']); ?></p>
                            </a>
                            <form action="home.php" method="post" class="reaction-buttons d-flex gap-2 flex-wrap mt-2">
                                <input type="hidden" name="publicacion_id" value="<?php echo htmlspecialchars($fila['publicacion_id']); ?>">
                                <button class="btn btn-outline-success btn-sm" name="tipo" value="like">
                                    <i class="fa-solid fa-thumbs-up"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "like")); ?>
                                </button>
                                <button class="btn btn-outline-danger btn-sm" name="tipo" value="dislike">
                                    <i class="fa-solid fa-thumbs-down"></i> <?php echo htmlspecialchars(mostrar_reaccion($fila['publicacion_id'], "dislike")); ?>
                                </button>
                                <a href="comentar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>" class="btn btn-outline-primary btn-sm" title="Comentar Publicación"><i class="fa-solid fa-comment"></i> Comentar</a>
                                <a href="reportar_publicacion.php?publicacion_id=<?= $fila['publicacion_id']; ?>" class="btn btn-outline-warning btn-sm" title="Reporta Publicación" style="margin-left:3px"><i class="fa-regular fa-flag"></i> Reportar</a>
                            </form>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $fila['user_id']): ?>
                        <form action="eliminar_publicacion.php" method="post">
                            <input type="hidden" name="publicacion_id" value="<?= $fila['publicacion_id']; ?>">
                            <input type="hidden" name="tipo" value="eliminar">
                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta publicación?')" title="Eliminar publicación">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        <?php
        }
        ?>
        <div class="col-md-12 col-sm-12">
            <div class="ajax-loader text-center" style="display: none;">
                <img src="./assets/images/fonts/loading.gif" alt="Cargando... " class="img-fluid" style="width: 50px; height: 50px;">
                <br><strong>Cargando más Registros...</strong>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                pageScroll();
            });

            function pageScroll() {
                let cargando = false;

                $(window).on("scroll", function() {
                    if (!cargando && $(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
                        if ($(".item-publicacion").length < $("#total_publicaciones").val()) {
                            cargando = true;

                            var ultimo_id = $(".item-publicacion:last").attr("id");
                            var user_id = $("#user_id").val() || '';

                            $.ajax({
                                url: "obteniendo_publicaciones.php",
                                type: "GET",
                                data: {
                                    ultimo_id: encodeURIComponent(ultimo_id),
                                    user_id: encodeURIComponent(user_id)
                                },
                                beforeSend: function() {
                                    $(".ajax-loader").show();
                                },
                                success: function(data) {
                                    setTimeout(function() {
                                        $(".ajax-loader").hide();
                                        $("#lista-publicaciones").append(data);
                                        cargando = false;
                                    }, 1000);
                                },
                                error: function() {
                                    $(".ajax-loader").hide();
                                    cargando = false;
                                    console.error("Error al cargar más publicaciones.");
                                }
                            });
                        }
                    }
                });
            }
        </script>
    <?php
    }
}


function mostrar_perfil($user_id, $ruta_foto)
{
    $foto_perfil = mostrar_foto_perfil(user_id: $user_id, ruta_imagen: $ruta_foto, imagen_defecto: 'profile-default.png');
    $nombre_usuario = nombres_usuario_get(user_id: $user_id);
    $nombre_universidad = universidad_usuario_get($user_id);

    $total_publicaciones = total_publicaciones_user($user_id);
    $total_likes = total_reacciones_user($user_id, "like");
    $total_dislikes = total_reacciones_user($user_id, "dislike");
    ?>
    <!-- Perfil -->
    <div class="card col-md-12 mx-auto">
        <div>
            <img src="<?php echo htmlspecialchars($foto_perfil); ?>" class="profile-pic-seg me-3" alt="Foto de perfil">
        </div>
        <div class="d-flex align-items-center">
            <div class="info-perfil">
                <h2 class="mb-0"><?php echo htmlspecialchars($nombre_usuario); ?></h2>
                <h5><?php echo htmlspecialchars($nombre_universidad); ?></h5>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-around text-center">
            <div>
                <div>Publicaciones</div>
                <strong><?php echo htmlspecialchars($total_publicaciones); ?></strong>
            </div>
            <div>
                <div>Me gustas</div>
                <strong><?php echo htmlspecialchars($total_likes); ?></strong>
            </div>
            <div>
                <div>No Me gustas</div>
                <strong><?php echo htmlspecialchars($total_dislikes); ?></strong>
            </div>
        </div>
        <hr>
    </div>
<?php
}
