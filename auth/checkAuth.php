<?php
session_start();

// Tiempo límite de inactividad en segundos (por ejemplo, 15 minutos)
$tiempo_limite = 900; // 15 * 60

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

// Verificar el tiempo de inactividad
if (isset($_SESSION['ultima_actividad']) && (time() - $_SESSION['ultima_actividad'] > $tiempo_limite)) {
    // Tiempo de inactividad excedido, cerrar sesión
    session_unset();
    session_destroy();
    header("Location: ./index.php?session_expired=1");
    exit();
}

// Actualizar la última actividad
$_SESSION['ultima_actividad'] = time();
