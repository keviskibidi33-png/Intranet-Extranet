<?php

// Detectar automáticamente el protocolo (HTTP o HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';

// Limpiar HTTP_HOST para remover public_html si está presente
$host = $_SERVER["HTTP_HOST"];
$host = str_replace('/public_html', '', $host);
$host = rtrim($host, '/');

define('ruta', $protocol . '://' . $host . '/intranet/');

define('HOST', 'localhost');



define('USER', 'grersced_geo');

define('PASSWORD', 'sOWr;@]#UCk2');

define('DB_NAME', 'grersced_geo');