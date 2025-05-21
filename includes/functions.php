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
    $universidad_usuario_id = universidad_usuario_id();
    $universidad = "SELECT * FROM universidades";
    $universidad = $conexion_bbdd->query(query: $universidad);
    while ($fila = $universidad->fetch_assoc()) {
        $selected = "";
        if ($fila['universidad_id'] == $universidad_usuario_id) {
            $selected = "selected";
        }
        echo "<option value='$fila[universidad_id]' $selected >$fila[universidad]</option>";
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
