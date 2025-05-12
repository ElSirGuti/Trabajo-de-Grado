<?php
session_start();

// Función para verificar si el usuario está autenticado
function estaAutenticado() {
    return isset($_SESSION['rol']);
}

// Función para verificar el rol y redirigir si no tiene permiso
function verificarRol($rolesPermitidos) {
    if (!estaAutenticado() || !in_array($_SESSION['rol'], $rolesPermitidos)) {
        header('Location: error.php'); // Redirige a la página de error
        exit();
    }
}
?>