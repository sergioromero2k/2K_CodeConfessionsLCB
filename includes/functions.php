<?php

function nombre_usuario(): void
{
    global $conexion_bbdd;
    echo $_SESSION['nombre'] . " " . $_SESSION['apellido'];
}

function universidad_usuario_id(): mixed
{
    global $conexion_bbdd;
    $universidad_id = "SELECT universidad_id FROM usuarios WHERE user_id = $_SESSION[user_id]";
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
        return $ruta_completa; // Retorna la ruta completa de la imagen guardada
    } else {
        return $foto_defecto; // Si no se subió una imagen, usar la imagen por defecto
    }
}
function mostrar_foto_perfil(int $user_id, $imagen_defecto): string
{
    global $conexion_bbdd;
    $consulta = $conexion_bbdd->query(query: "SELECT profile_image FROM usuarios WHERE user_id = $user_id");
    if ($consulta) {
        $resultado = $consulta->fetch_assoc();
        // Verificar si la imagen existe y no es nula
        if (!empty($resultado['profile_image']) && file_exists($resultado['profile_image'])) {
            return $resultado['profile_image']; // Retorna la ruta de la imagen del perfil
        }
    }
    // Si no se encuentra la imagen del perfil o no existe, retornar la imagen por defecto
    return  $imagen_defecto; // Retorna la imagen por defecto si no se encuentra
}

