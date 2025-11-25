<?php
/**
 * Script para corregir TODAS las rutas en controladores públicos
 * Actualiza automáticamente las rutas de modelos y vistas
 */

$base_dir = __DIR__;
$publico_controlador = $base_dir . '/publico/controlador/';

// Obtener todos los controladores públicos
$controladores = glob($publico_controlador . '*Con.php');

if (empty($controladores)) {
    die("No se encontraron controladores en publico/controlador/\n");
}

echo "🔧 Corrigiendo rutas en " . count($controladores) . " controladores...\n\n";

$corregidos = 0;

foreach ($controladores as $controlador_path) {
    $nombre_archivo = basename($controlador_path);
    $contenido = file_get_contents($controlador_path);
    $contenido_original = $contenido;
    
    // Obtener nombre de la página (sin Con.php)
    $pagina = str_replace('Con.php', '', $nombre_archivo);
    
    // 1. Corregir ruta del modelo (flexible con espacios)
    $contenido = preg_replace(
        '/include\s*\(\s*["\']app\/modelo\/inicioModelo\.php["\']\s*\);/',
        'include(__DIR__ . "/../../app/modelo/inicioModelo.php");',
        $contenido
    );
    
    // 2. Corregir ruta de keys.php
    $contenido = preg_replace(
        '/include\s*\(\s*["\']keys\.php["\']\s*\);/',
        'if (file_exists(__DIR__ . "/../../app/controlador/keys.php")) { include(__DIR__ . "/../../app/controlador/keys.php"); }',
        $contenido
    );
    
    // 3. Corregir ruta de la vista (flexible con espacios y require_once/require)
    $contenido = preg_replace(
        '/require(?:_once)?\s*\(\s*["\']app\/vista\/' . preg_quote($pagina, '/') . '\.phtml["\']\s*\);/',
        'require_once(__DIR__ . "/../vista/' . $pagina . '.phtml");',
        $contenido
    );
    
    // Solo escribir si hubo cambios
    if ($contenido !== $contenido_original) {
        if (file_put_contents($controlador_path, $contenido)) {
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

