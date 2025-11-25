<?php
/**
 * CONFIGURACIÓN LOCAL PARA DESARROLLO
 * 
 * Este archivo es solo para uso local en tu computadora.
 * NO subir este archivo al servidor.
 */

// Detectar automáticamente el protocolo (HTTP o HTTPS) para evitar problemas de contenido mixto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';

// Configuración de rutas - Para desarrollo local (detecta automáticamente HTTP/HTTPS)
define('ruta', $protocol . '://localhost/public_html/intranet/');

// Configuración de Base de Datos LOCAL
define('HOST', 'localhost');
define('USER', 'root');  // Usuario por defecto de XAMPP/WAMP
define('PASSWORD', '');  // Contraseña por defecto (vacía en XAMPP)
define('DB_NAME', 'intranet_dev'); // Base de datos local

