<?php



// Detectar automáticamente el protocolo (HTTP o HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';

define('ruta', $protocol . '://' . $_SERVER["HTTP_HOST"] . '/intranet/admin/');
define('ruta0', $protocol . '://' . $_SERVER["HTTP_HOST"] . '/');

define('HOST', 'localhost');



define('USER', 'grersced_geo');

define('PASSWORD', 'sOWr;@]#UCk2');

define('DB_NAME', 'grersced_geo');





