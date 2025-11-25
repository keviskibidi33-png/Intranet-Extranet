<?php
/**
 * Controlador de Login - Panel Administrativo
 * 
 * Maneja el proceso de autenticación de usuarios administradores
 */

// Asegurar que sistema.php esté incluido antes del modelo
if (!class_exists('Conectar')) {
    $rutasSistema = [
        __DIR__ . '/../../config/sistema.php',
        'config/sistema.php',
        '../config/sistema.php'
    ];
    
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            break;
        }
    }
}

require_once("app/modelo/loginModelo.php");

$logininicio = new Logininicio();

/**
 * Restaurar sesión desde cookie si existe
 */
if (!isset($_SESSION['id_geofal']) || empty($_SESSION['id_geofal'])) {
    if (isset($_COOKIE['id_geofal']) && !empty($_COOKIE['id_geofal'])) {
        $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
    }
}

/**
 * Procesar intento de login
 */
if (isset($_POST["login"]) && $_POST["login"] === "ok") {
    $logininicio->login();
    // Si llegamos aquí, el login falló (login() debería redirigir)
    // Redirigir con error por seguridad
    header("Location:" . ruta . 'login?error=1');
    exit;
}

/**
 * Si el usuario ya está logueado, redirigir al panel
 */
if (isset($_SESSION['id_geofal']) && !empty($_SESSION['id_geofal'])) {
    header("Location:" . ruta . 'inicio');
    exit;
}

/**
 * Mostrar formulario de login
 */
require_once("app/vista/login.phtml");
?>
