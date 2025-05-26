<?php

function nombre_usuario(): void
{
    global $conexion_bbdd;
    echo $_SESSION['nombre'];
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
    $resultado = $conexion_bbdd->query(query: "SELECT COUNT(*) as total FROM reacciones WHERE user_id = $user_id 
    AND tipo = '$tipo'");
    if (!$resultado) {
        return 0; // Si no hay resultados, retornamos 0
    }
    $resultado = $resultado->fetch_assoc();
    return (int)$resultado['total']; // Aseguramos que el resultado sea un entero
}
function total_publicaciones_user(int $user_id,): int
{
    global $conexion_bbdd;
    $resultado = $conexion_bbdd->query(query: "SELECT COUNT(*) as total FROM publicaciones WHERE user_id = $user_id");    
    if (!$resultado) {
        return 0; # Si no hay resultados, retornamos 0
    }
    $resultado = $resultado->fetch_assoc();
    return (int)$resultado['total']; # Aseguramos que el resultado sea un entero
}