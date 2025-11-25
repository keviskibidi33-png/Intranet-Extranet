<?php
/**
 * Script temporal para verificar la ruta del logo
 */

if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

echo "<h1>🔍 VERIFICACIÓN DE LOGO</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    img { max-width: 200px; border: 2px solid #ddd; padding: 10px; background: white; margin: 10px 0; }
</style>";

echo "<div class='info'>";
echo "<h2>📋 Información de Rutas</h2>";
echo "<p><strong>Ruta base (ruta):</strong> " . ruta . "</p>";
echo "<p><strong>Ruta completa del logo:</strong> " . ruta . "assets/images/geofal.png</p>";
echo "</div>";

$rutas_logo = [
    'assets/images/geofal.png',
    'include/images/logo.png',
    'include/images/logo2.png',
    '../assets/images/geofal.png',
    '../../geofal.png'
];

echo "<div class='info'>";
echo "<h2>🔍 Verificando Rutas de Logo</h2>";
foreach ($rutas_logo as $ruta_logo) {
    $ruta_completa = __DIR__ . '/' . $ruta_logo;
    $ruta_completa = str_replace('\\', '/', $ruta_completa);
    
    if (file_exists($ruta_completa)) {
        echo "<div class='success'>";
        echo "✓ <strong>{$ruta_logo}</strong> existe<br>";
        echo "Ruta física: {$ruta_completa}<br>";
        echo "Tamaño: " . filesize($ruta_completa) . " bytes<br>";
        echo "URL: <a href='" . ruta . $ruta_logo . "' target='_blank'>" . ruta . $ruta_logo . "</a><br>";
        echo "<img src='" . ruta . $ruta_logo . "' alt='Logo'><br>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "✗ <strong>{$ruta_logo}</strong> NO existe<br>";
        echo "Ruta física buscada: {$ruta_completa}<br>";
        echo "</div>";
    }
}
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

