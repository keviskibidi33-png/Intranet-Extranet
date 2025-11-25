<?php
/**
 * Script para analizar dependencias del sistema admin
 * Identifica qué es necesario y qué se puede eliminar
 */

echo "<h1>🔍 ANÁLISIS DE DEPENDENCIAS - ADMIN</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .necesario { color: #28a745; font-weight: bold; }
    .opcional { color: #ffc107; font-weight: bold; }
    .basura { color: #dc3545; font-weight: bold; }
</style>";

$base_dir = __DIR__;

echo "<div class='info'>";
echo "<h2>📋 ANÁLISIS DE DEPENDENCIAS</h2>";
echo "</div>";

// Analizar PHPMailer
echo "<h3>1️⃣ PHPMailer (include/phpmiler/)</h3>";

$phpmailer_usado = false;
$archivos_php = glob($base_dir . '/app/modelo/*.php');
$archivos_controlador = glob($base_dir . '/app/controlador/*.php');

foreach (array_merge($archivos_php, $archivos_controlador) as $archivo) {
    $contenido = file_get_contents($archivo);
    if (strpos($contenido, 'PHPMailer') !== false || strpos($contenido, 'phpmiler') !== false) {
        $phpmailer_usado = true;
        echo "<div class='success'>✓ <strong>NECESARIO</strong> - Usado en: <code>" . basename($archivo) . "</code></div>";
        break;
    }
}

if ($phpmailer_usado) {
    echo "<div class='info'>";
    echo "<p><strong>Función:</strong> Envía emails cuando se crea un nuevo cliente</p>";
    echo "<p><strong>Ubicación:</strong> <code>include/phpmiler/</code></p>";
    echo "<p><strong>Recomendación:</strong> <span class='necesario'>MANTENER</span> - Es necesario para el envío de emails</p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<p><strong>Estado:</strong> No se encontró uso en el código</p>";
    echo "<p><strong>Recomendación:</strong> <span class='opcional'>VERIFICAR</span> - Puede que se use en otros archivos</p>";
    echo "</div>";
}

// Analizar vendors
echo "<h3>2️⃣ Vendors (include/vendors/)</h3>";

$vendors_usados = [];
$archivos_vista = glob($base_dir . '/app/vista/*.php');
$archivos_vista_phtml = glob($base_dir . '/app/vista/*.phtml');

foreach (array_merge($archivos_vista, $archivos_vista_phtml) as $archivo) {
    $contenido = file_get_contents($archivo);
    
    // Buscar referencias a vendors
    if (preg_match_all('/vendors\/([^\/"\']+)/', $contenido, $matches)) {
        foreach ($matches[1] as $vendor) {
            if (!in_array($vendor, $vendors_usados)) {
                $vendors_usados[] = $vendor;
            }
        }
    }
}

echo "<table>";
echo "<tr><th>Vendor</th><th>Estado</th><th>Uso</th></tr>";

$vendors_importantes = [
    'jquery' => 'NECESARIO - Base de JavaScript',
    'bootstrap' => 'NECESARIO - Framework CSS/JS',
    'font-awesome' => 'NECESARIO - Iconos',
    'popper.js' => 'NECESARIO - Requerido por Bootstrap',
    'themify-icons' => 'USADO - Iconos del admin',
    'flag-icon-css' => 'USADO - Banderas de países',
    'selectFX' => 'USADO - Selectores personalizados',
    'jqvmap' => 'USADO - Mapas (si se usan)',
    'chart.js' => 'OPCIONAL - Gráficos (si se usan)',
    'datatables' => 'OPCIONAL - Tablas avanzadas (si se usan)',
    'animate.css' => 'OPCIONAL - Animaciones',
    'chosen' => 'OPCIONAL - Selectores',
    'flot' => 'OPCIONAL - Gráficos',
    'gaugejs' => 'OPCIONAL - Medidores',
    'gmaps' => 'OPCIONAL - Google Maps',
    'jquery-validation' => 'OPCIONAL - Validación de formularios',
    'peity' => 'OPCIONAL - Gráficos pequeños',
    'pdfmake' => 'OPCIONAL - Generación de PDFs',
    'jszip' => 'OPCIONAL - Compresión ZIP',
];

foreach ($vendors_importantes as $vendor => $descripcion) {
    $usado = in_array($vendor, $vendors_usados) || in_array(str_replace('.js', '', $vendor), $vendors_usados);
    $estado = '';
    
    if (strpos($descripcion, 'NECESARIO') !== false) {
        $estado = "<span class='necesario'>✓ NECESARIO</span>";
    } elseif (strpos($descripcion, 'USADO') !== false) {
        $estado = "<span class='necesario'>✓ USADO</span>";
    } elseif (strpos($descripcion, 'OPCIONAL') !== false) {
        $estado = "<span class='opcional'>? OPCIONAL</span>";
    }
    
    echo "<tr>";
    echo "<td><code>{$vendor}</code></td>";
    echo "<td>{$estado}</td>";
    echo "<td>{$descripcion}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<div class='info'>";
echo "<h3>📊 Resumen de Vendors</h3>";
echo "<p><strong>NECESARIOS (no eliminar):</strong></p>";
echo "<ul>";
echo "<li><code>jquery</code> - Base de JavaScript</li>";
echo "<li><code>bootstrap</code> - Framework CSS/JS</li>";
echo "<li><code>font-awesome</code> - Iconos</li>";
echo "<li><code>popper.js</code> - Requerido por Bootstrap</li>";
echo "</ul>";
echo "<p><strong>USADOS (verificar antes de eliminar):</strong></p>";
echo "<ul>";
echo "<li><code>themify-icons</code> - Iconos del admin</li>";
echo "<li><code>flag-icon-css</code> - Banderas</li>";
echo "<li><code>selectFX</code> - Selectores</li>";
echo "<li><code>jqvmap</code> - Mapas (si se usan)</li>";
echo "</ul>";
echo "<p><strong>OPCIONALES (se pueden eliminar si no se usan):</strong></p>";
echo "<ul>";
echo "<li>chart.js, datatables, animate.css, chosen, flot, gaugejs, gmaps, jquery-validation, peity, pdfmake, jszip</li>";
echo "</ul>";
echo "</div>";

// Verificar imágenes
echo "<h3>3️⃣ Imágenes (include/images/)</h3>";

$imagenes = glob($base_dir . '/include/images/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
$imagenes_necesarias = ['geofal.png'];

echo "<table>";
echo "<tr><th>Imagen</th><th>Estado</th></tr>";

foreach ($imagenes as $imagen) {
    $nombre = basename($imagen);
    if (in_array($nombre, $imagenes_necesarias)) {
        echo "<tr><td><code>{$nombre}</code></td><td><span class='necesario'>✓ NECESARIA</span></td></tr>";
    } else {
        echo "<tr><td><code>{$nombre}</code></td><td><span class='basura'>✗ INNECESARIA</span></td></tr>";
    }
}

echo "</table>";

echo "<div class='warning'>";
echo "<h3>⚠️ RECOMENDACIONES</h3>";
echo "<ol>";
echo "<li><strong>PHPMailer:</strong> <span class='necesario'>MANTENER</span> - Necesario para enviar emails</li>";
echo "<li><strong>Vendors esenciales:</strong> <span class='necesario'>MANTENER</span> - jQuery, Bootstrap, Font Awesome, Popper.js</li>";
echo "<li><strong>Vendors opcionales:</strong> <span class='opcional'>VERIFICAR</span> - Eliminar solo si no se usan</li>";
echo "<li><strong>Imágenes:</strong> <span class='basura'>ELIMINAR</span> - Dejar solo geofal.png</li>";
echo "</ol>";
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

