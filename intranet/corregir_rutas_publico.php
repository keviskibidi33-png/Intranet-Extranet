<?php
/**
 * Script para corregir rutas en controladores públicos
 * Actualiza las rutas de modelos y vistas para que funcionen desde publico/
 */

echo "<h1>🔧 CORRECCIÓN DE RUTAS EN CONTROLADORES PÚBLICOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .code { background: #f4f4f4; padding: 3px 8px; border-radius: 3px; font-family: monospace; }
</style>";

$base_dir = __DIR__;
$publico_controlador = $base_dir . '/publico/controlador/';

// Obtener todos los controladores públicos
$controladores = glob($publico_controlador . '*Con.php');

if (empty($controladores)) {
    echo "<div class='error'>No se encontraron controladores en publico/controlador/</div>";
    exit;
}

echo "<div class='info'>";
echo "<h2>📋 Controladores encontrados: " . count($controladores) . "</h2>";
echo "</div>";

if (isset($_POST['corregir'])) {
    echo "<div class='warning'>";
    echo "<h2>🔄 Corrigiendo Rutas...</h2>";
    echo "</div>";
    
    $corregidos = 0;
    $errores = 0;
    
    foreach ($controladores as $controlador_path) {
        $nombre_archivo = basename($controlador_path);
        $contenido = file_get_contents($controlador_path);
        $contenido_original = $contenido;
        
        // Obtener nombre de la página (sin Con.php)
        $pagina = str_replace('Con.php', '', $nombre_archivo);
        
        // Correcciones necesarias:
        // 1. app/modelo/inicioModelo.php → ../../app/modelo/inicioModelo.php
        // 2. app/vista/{pagina}.phtml → ../vista/{pagina}.phtml
        // 3. keys.php → ../../app/controlador/keys.php (si existe)
        
        // Corregir ruta del modelo
        $contenido = preg_replace(
            '/include\s*\(["\']app\/modelo\/inicioModelo\.php["\']\);/',
            'include(__DIR__ . "/../../app/modelo/inicioModelo.php");',
            $contenido
        );
        
        // Corregir ruta de la vista
        $contenido = preg_replace(
            '/require_once\s*\(["\']app\/vista\/' . preg_quote($pagina, '/') . '\.phtml["\']\);/',
            'require_once(__DIR__ . "/../vista/' . $pagina . '.phtml");',
            $contenido
        );
        
        // Corregir keys.php si existe
        if (strpos($contenido, 'keys.php') !== false) {
            $contenido = preg_replace(
                '/include\s*\(["\']keys\.php["\']\);/',
                'if (file_exists(__DIR__ . "/../../app/controlador/keys.php")) { include(__DIR__ . "/../../app/controlador/keys.php"); }',
                $contenido
            );
        }
        
        // Solo escribir si hubo cambios
        if ($contenido !== $contenido_original) {
            if (file_put_contents($controlador_path, $contenido)) {
                echo "<div class='success'>✓ Corregido: <code>{$nombre_archivo}</code></div>";
                $corregidos++;
            } else {
                echo "<div class='error'>✗ Error al escribir: <code>{$nombre_archivo}</code></div>";
                $errores++;
            }
        } else {
            echo "<div class='info'>○ Sin cambios: <code>{$nombre_archivo}</code> (ya está correcto o no necesita corrección)</div>";
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Archivos corregidos:</strong> {$corregidos}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
    if ($errores == 0 && $corregidos > 0) {
        echo "<div class='success'>";
        echo "<h2>✅ Corrección Completada</h2>";
        echo "<p>Las rutas en los controladores públicos han sido actualizadas.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='info'>";
    echo "<h2>📋 Controladores a Corregir</h2>";
    echo "<table>";
    echo "<tr><th>Archivo</th><th>Estado</th></tr>";
    
    foreach ($controladores as $controlador_path) {
        $nombre_archivo = basename($controlador_path);
        $contenido = file_get_contents($controlador_path);
        $pagina = str_replace('Con.php', '', $nombre_archivo);
        
        $necesita_correccion = false;
        $problemas = [];
        
        // Verificar problemas
        if (preg_match('/include\s*\(["\']app\/modelo\/inicioModelo\.php["\']\);/', $contenido)) {
            $necesita_correccion = true;
            $problemas[] = "Ruta modelo incorrecta";
        }
        
        if (preg_match('/require_once\s*\(["\']app\/vista\/' . preg_quote($pagina, '/') . '\.phtml["\']\);/', $contenido)) {
            $necesita_correccion = true;
            $problemas[] = "Ruta vista incorrecta";
        }
        
        if ($necesita_correccion) {
            echo "<tr><td><code>{$nombre_archivo}</code></td><td><span style='color: orange;'>⚠️ " . implode(', ', $problemas) . "</span></td></tr>";
        } else {
            echo "<tr><td><code>{$nombre_archivo}</code></td><td><span style='color: green;'>✓ OK</span></td></tr>";
        }
    }
    
    echo "</table>";
    echo "</div>";
    
    echo "<div class='warning'>";
    echo "<h2>⚠️ Acción Requerida</h2>";
    echo "<p>Este script corregirá las rutas en los controladores públicos para que funcionen correctamente desde la carpeta <code>publico/</code>.</p>";
    echo "<p><strong>Cambios que se harán:</strong></p>";
    echo "<ul>";
    echo "<li><code>app/modelo/inicioModelo.php</code> → <code>../../app/modelo/inicioModelo.php</code></li>";
    echo "<li><code>app/vista/{pagina}.phtml</code> → <code>../vista/{pagina}.phtml</code></li>";
    echo "<li><code>keys.php</code> → <code>../../app/controlador/keys.php</code></li>";
    echo "</ul>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='corregir' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>Corregir Rutas</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

