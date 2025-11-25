<?php
/**
 * Script para validar la reorganización de páginas públicas
 * Verifica que todo esté en orden y funcionando
 */

echo "<h1>✅ VALIDACIÓN DE REORGANIZACIÓN</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .ok { color: #28a745; font-weight: bold; }
    .error-text { color: #dc3545; font-weight: bold; }
</style>";

$base_dir = __DIR__;

// Páginas públicas esperadas
$paginas_publicas = [
    'contacto',
    'nosotros',
    'galeria',
    'inicio',
    'estudio-de-suelos',
    'laboratorio-de-suelo-concreto-pavimento-y-albanileria',
    'control-de-calidad-de-obras-civiles',
    'evaluacion-estructural-e-ingenieria'
];

// Archivos del flujo clientes (NO deben estar en publico/)
$archivos_clientes = [
    'login',
    'ordenes',
    'cliente',
    'salir'
];

echo "<div class='info'>";
echo "<h2>📋 Validación de Estructura</h2>";
echo "</div>";

// Validar controladores públicos
echo "<h3>✅ Controladores Públicos</h3>";
$controladores_ok = 0;
$controladores_error = 0;

foreach ($paginas_publicas as $pagina) {
    $controlador_publico = $base_dir . '/publico/controlador/' . $pagina . 'Con.php';
    $controlador_app = $base_dir . '/app/controlador/' . $pagina . 'Con.php';
    
    if (file_exists($controlador_publico)) {
        echo "<div class='success'>✓ <code>{$pagina}Con.php</code> está en <code>publico/controlador/</code></div>";
        $controladores_ok++;
        
        // Verificar que NO esté en app/controlador/
        if (file_exists($controlador_app)) {
            echo "<div class='error'>⚠️ <code>{$pagina}Con.php</code> también existe en <code>app/controlador/</code> (duplicado)</div>";
        }
    } else {
        echo "<div class='error'>✗ <code>{$pagina}Con.php</code> NO está en <code>publico/controlador/</code></div>";
        $controladores_error++;
    }
}

// Validar vistas públicas
echo "<h3>✅ Vistas Públicas</h3>";
$vistas_ok = 0;
$vistas_error = 0;

foreach ($paginas_publicas as $pagina) {
    $vista_publico = $base_dir . '/publico/vista/' . $pagina . '.phtml';
    $vista_app = $base_dir . '/app/vista/' . $pagina . '.phtml';
    
    if (file_exists($vista_publico)) {
        echo "<div class='success'>✓ <code>{$pagina}.phtml</code> está en <code>publico/vista/</code></div>";
        $vistas_ok++;
        
        // Verificar que NO esté en app/vista/ (excepto si es compartida)
        if (file_exists($vista_app) && !in_array($pagina, ['index'])) {
            echo "<div class='warning'>⚠️ <code>{$pagina}.phtml</code> también existe en <code>app/vista/</code> (puede ser compartida)</div>";
        }
    } else {
        echo "<div class='error'>✗ <code>{$pagina}.phtml</code> NO está en <code>publico/vista/</code></div>";
        $vistas_error++;
    }
}

// Validar que archivos de clientes NO estén en publico/
echo "<h3>✅ Archivos de Flujo Clientes (NO deben estar en publico/)</h3>";
$clientes_ok = 0;

foreach ($archivos_clientes as $archivo) {
    $controlador_publico = $base_dir . '/publico/controlador/' . $archivo . 'Con.php';
    $vista_publico = $base_dir . '/publico/vista/' . $archivo . '.phtml';
    
    if (!file_exists($controlador_publico) && !file_exists($vista_publico)) {
        echo "<div class='success'>✓ <code>{$archivo}</code> NO está en <code>publico/</code> (correcto)</div>";
        $clientes_ok++;
    } else {
        echo "<div class='error'>✗ <code>{$archivo}</code> está en <code>publico/</code> (debería estar solo en app/)</div>";
    }
}

// Validar index.php
echo "<h3>✅ Configuración de index.php</h3>";
$index_content = file_get_contents($base_dir . '/index.php');

if (strpos($index_content, 'publico/controlador') !== false) {
    echo "<div class='success'>✓ <code>index.php</code> busca controladores en <code>publico/controlador/</code></div>";
} else {
    echo "<div class='error'>✗ <code>index.php</code> NO busca controladores en <code>publico/controlador/</code></div>";
}

// Resumen final
echo "<div class='info'>";
echo "<h2>📊 Resumen de Validación</h2>";
echo "<table>";
echo "<tr><th>Elemento</th><th>Estado</th></tr>";
echo "<tr><td>Controladores públicos</td><td><span class='ok'>{$controladores_ok} OK</span> / <span class='error-text'>{$controladores_error} Error</span></td></tr>";
echo "<tr><td>Vistas públicas</td><td><span class='ok'>{$vistas_ok} OK</span> / <span class='error-text'>{$vistas_error} Error</span></td></tr>";
echo "<tr><td>Archivos clientes (separados)</td><td><span class='ok'>{$clientes_ok} OK</span></td></tr>";
echo "</table>";
echo "</div>";

if ($controladores_error == 0 && $vistas_error == 0) {
    echo "<div class='success'>";
    echo "<h2>✅ Reorganización Completada Correctamente</h2>";
    echo "<p>La estructura está en orden. Las páginas públicas están separadas del flujo de clientes.</p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Hay Errores en la Reorganización</h2>";
    echo "<p>Revisa los errores arriba y corrígelos.</p>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

