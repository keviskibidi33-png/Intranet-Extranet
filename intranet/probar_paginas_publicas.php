<?php
/**
 * Script para probar las páginas públicas
 * Verifica que todas las rutas funcionen correctamente
 */

echo "<h1>🧪 PRUEBA DE PÁGINAS PÚBLICAS</h1>";
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
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>";

// Incluir configuración para obtener ruta
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

$base_dir = __DIR__;
$base_url = ruta;

// Páginas públicas a probar
$paginas_publicas = [
    'inicio' => 'Página de inicio',
    'contacto' => 'Página de contacto',
    'nosotros' => 'Página nosotros',
    'galeria' => 'Galería',
    'estudio-de-suelos' => 'Estudio de suelos',
    'laboratorio-de-suelo-concreto-pavimento-y-albanileria' => 'Laboratorio',
    'control-de-calidad-de-obras-civiles' => 'Control de calidad',
    'evaluacion-estructural-e-ingenieria' => 'Evaluación estructural'
];

echo "<div class='info'>";
echo "<h2>📋 Páginas Públicas a Probar</h2>";
echo "<p><strong>URL Base:</strong> " . htmlspecialchars($base_url) . "</p>";
echo "</div>";

echo "<h3>🔗 Enlaces para Probar</h3>";
echo "<table>";
echo "<tr><th>Página</th><th>URL</th><th>Estado</th></tr>";

foreach ($paginas_publicas as $pagina => $descripcion) {
    $url = $base_url . "?pagina=" . urlencode($pagina);
    $controlador = $base_dir . '/publico/controlador/' . $pagina . 'Con.php';
    $vista = $base_dir . '/publico/vista/' . $pagina . '.phtml';
    
    $controlador_existe = file_exists($controlador);
    $vista_existe = file_exists($vista);
    
    if ($controlador_existe && $vista_existe) {
        $estado = "<span class='ok'>✓ Listo</span>";
    } else {
        $estado = "<span class='error-text'>✗ Faltan archivos</span>";
    }
    
    echo "<tr>";
    echo "<td><strong>{$descripcion}</strong></td>";
    echo "<td><a href='" . htmlspecialchars($url) . "' target='_blank'>" . htmlspecialchars($url) . "</a></td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}

echo "</table>";

// Verificar archivos necesarios
echo "<h3>📁 Verificación de Archivos</h3>";

$archivos_necesarios = [
    'head.php' => $base_dir . '/app/vista/head.php',
    'header.php' => $base_dir . '/app/vista/header.php',
    'footer.php' => $base_dir . '/app/vista/footer.php',
    'carusel.php' => $base_dir . '/app/vista/carusel.php',
    'inicioModelo.php' => $base_dir . '/app/modelo/inicioModelo.php',
    'keys.php' => $base_dir . '/app/controlador/keys.php'
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Ruta</th><th>Estado</th></tr>";

foreach ($archivos_necesarios as $nombre => $ruta) {
    $existe = file_exists($ruta);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error-text'>✗ No existe</span>";
    echo "<tr>";
    echo "<td><strong>{$nombre}</strong></td>";
    echo "<td><code>" . htmlspecialchars(str_replace($base_dir, '.', $ruta)) . "</code></td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}

echo "</table>";

// Verificar rutas en controladores
echo "<h3>🔍 Verificación de Rutas en Controladores</h3>";

$controladores = glob($base_dir . '/publico/controlador/*Con.php');
$rutas_correctas = 0;
$rutas_incorrectas = 0;

foreach ($controladores as $controlador_path) {
    $nombre = basename($controlador_path);
    $contenido = file_get_contents($controlador_path);
    
    // Verificar que use __DIR__ para rutas relativas
    $tiene_dir = strpos($contenido, '__DIR__') !== false;
    $tiene_app_modelo = strpos($contenido, '../../app/modelo/') !== false || strpos($contenido, '__DIR__ . "/../../app/modelo/') !== false;
    $tiene_publico_vista = strpos($contenido, '../vista/') !== false || strpos($contenido, '__DIR__ . "/../vista/') !== false;
    
    if ($tiene_dir && ($tiene_app_modelo || $tiene_publico_vista)) {
        echo "<div class='success'>✓ <code>{$nombre}</code> - Rutas correctas</div>";
        $rutas_correctas++;
    } else {
        echo "<div class='warning'>⚠️ <code>{$nombre}</code> - Revisar rutas</div>";
        $rutas_incorrectas++;
    }
}

// Resumen
echo "<div class='info'>";
echo "<h2>📊 Resumen</h2>";
echo "<p><strong>Controladores con rutas correctas:</strong> {$rutas_correctas}</p>";
echo "<p><strong>Controladores con rutas a revisar:</strong> {$rutas_incorrectas}</p>";
echo "</div>";

if ($rutas_incorrectas == 0) {
    echo "<div class='success'>";
    echo "<h2>✅ Todo Listo para Probar</h2>";
    echo "<p>Haz clic en los enlaces de arriba para probar cada página pública.</p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Revisar Rutas</h2>";
    echo "<p>Algunos controladores pueden necesitar corrección de rutas.</p>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

