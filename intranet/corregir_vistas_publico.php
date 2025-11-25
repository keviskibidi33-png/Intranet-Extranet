<?php
/**
 * Script para corregir rutas en vistas públicas
 * Actualiza las rutas de includes (head.php, header.php, footer.php)
 */

$base_dir = __DIR__;
$publico_vista = $base_dir . '/publico/vista/';

// Obtener todas las vistas públicas
$vistas = glob($publico_vista . '*.phtml');

if (empty($vistas)) {
    die("No se encontraron vistas en publico/vista/\n");
}

echo "🔧 Corrigiendo rutas en " . count($vistas) . " vistas...\n\n";

$corregidos = 0;

foreach ($vistas as $vista_path) {
    $nombre_archivo = basename($vista_path);
    $contenido = file_get_contents($vista_path);
    $contenido_original = $contenido;
    
    // Corregir rutas de includes comunes
    // head.php, header.php, footer.php, carusel.php están en app/vista/
    
    // head.php
    $contenido = preg_replace(
        '/include\s*\(\s*["\']head\.php["\']\s*\);/',
        'include(__DIR__ . "/../../app/vista/head.php");',
        $contenido
    );
    
    // header.php
    $contenido = preg_replace(
        '/include\s*\(\s*["\']header\.php["\']\s*\);/',
        'include(__DIR__ . "/../../app/vista/header.php");',
        $contenido
    );
    
    // footer.php
    $contenido = preg_replace(
        '/include\s*\(\s*["\']footer\.php["\']\s*\);/',
        'include(__DIR__ . "/../../app/vista/footer.php");',
        $contenido
    );
    
    // carusel.php
    $contenido = preg_replace(
        '/include\s*\(\s*["\']carusel\.php["\']\s*\);/',
        'include(__DIR__ . "/../../app/vista/carusel.php");',
        $contenido
    );
    
    // Solo escribir si hubo cambios
    if ($contenido !== $contenido_original) {
        if (file_put_contents($vista_path, $contenido)) {
            echo "✓ Corregido: {$nombre_archivo}\n";
            $corregidos++;
        } else {
            echo "✗ Error al escribir: {$nombre_archivo}\n";
        }
    } else {
        echo "○ Sin cambios: {$nombre_archivo}\n";
    }
}

echo "\n✅ Proceso completado. {$corregidos} archivos corregidos.\n";
?>

