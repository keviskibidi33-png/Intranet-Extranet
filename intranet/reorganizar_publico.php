<?php
/**
 * Script para reorganizar páginas públicas
 * Mueve controladores y vistas de páginas públicas a publico/
 */

echo "<h1>📁 REORGANIZACIÓN DE PÁGINAS PÚBLICAS</h1>";
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

// Páginas públicas identificadas (NO son del flujo de clientes)
$paginas_publicas = [
    'contacto',
    'nosotros',
    'galeria',
    'servicios',
    'inicio', // Página de inicio pública
    'estudio-de-suelos',
    'laboratorio-de-suelo-concreto-pavimento-y-albanileria',
    'control-de-calidad-de-obras-civiles',
    'evaluacion-estructural-e-ingenieria'
];

// Archivos del flujo REAL de clientes (NO mover)
$archivos_clientes = [
    'login',
    'ordenes',
    'cliente',
    'salir'
];

$base_dir = __DIR__;
$controlador_origen = $base_dir . '/app/controlador/';
$vista_origen = $base_dir . '/app/vista/';
$publico_controlador = $base_dir . '/publico/controlador/';
$publico_vista = $base_dir . '/publico/vista/';

// Crear carpetas si no existen
if (!is_dir($publico_controlador)) {
    mkdir($publico_controlador, 0755, true);
    echo "<div class='success'>✓ Carpeta creada: <code>publico/controlador/</code></div>";
}

if (!is_dir($publico_vista)) {
    mkdir($publico_vista, 0755, true);
    echo "<div class='success'>✓ Carpeta creada: <code>publico/vista/</code></div>";
}

echo "<div class='info'>";
echo "<h2>📋 Plan de Reorganización</h2>";
echo "<p><strong>Páginas públicas a mover:</strong> " . count($paginas_publicas) . "</p>";
echo "<ul>";
foreach ($paginas_publicas as $pagina) {
    echo "<li><code>{$pagina}Con.php</code> → <code>publico/controlador/</code></li>";
    echo "<li><code>{$pagina}.phtml</code> → <code>publico/vista/</code></li>";
}
echo "</ul>";
echo "</div>";

if (isset($_POST['ejecutar'])) {
    echo "<div class='warning'>";
    echo "<h2>🔄 Ejecutando Reorganización...</h2>";
    echo "</div>";
    
    $movidos = 0;
    $errores = 0;
    
    foreach ($paginas_publicas as $pagina) {
        $controlador_archivo = $controlador_origen . $pagina . 'Con.php';
        $vista_archivo = $vista_origen . $pagina . '.phtml';
        
        // Mover controlador
        if (file_exists($controlador_archivo)) {
            $destino_controlador = $publico_controlador . $pagina . 'Con.php';
            if (rename($controlador_archivo, $destino_controlador)) {
                echo "<div class='success'>✓ Movido: <code>{$pagina}Con.php</code></div>";
                $movidos++;
            } else {
                echo "<div class='error'>✗ Error al mover: <code>{$pagina}Con.php</code></div>";
                $errores++;
            }
        }
        
        // Mover vista
        if (file_exists($vista_archivo)) {
            $destino_vista = $publico_vista . $pagina . '.phtml';
            if (rename($vista_archivo, $destino_vista)) {
                echo "<div class='success'>✓ Movido: <code>{$pagina}.phtml</code></div>";
                $movidos++;
            } else {
                echo "<div class='error'>✗ Error al mover: <code>{$pagina}.phtml</code></div>";
                $errores++;
            }
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Archivos movidos:</strong> {$movidos}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
    if ($errores == 0) {
        echo "<div class='success'>";
        echo "<h2>✅ Reorganización Completada</h2>";
        echo "<p>Ahora necesitas actualizar <code>index.php</code> para que busque controladores en ambas carpetas.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Acción Requerida</h2>";
    echo "<p>Este script moverá los archivos de páginas públicas a la carpeta <code>publico/</code>.</p>";
    echo "<p><strong>Archivos que NO se moverán (flujo de clientes):</strong></p>";
    echo "<ul>";
    foreach ($archivos_clientes as $archivo) {
        echo "<li><code>{$archivo}Con.php</code> y <code>{$archivo}.phtml</code></li>";
    }
    echo "</ul>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='ejecutar' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>Ejecutar Reorganización</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

