<?php
/**
 * Script para crear ZIP de la web antigua (páginas públicas)
 * Esta web ya no se usa porque ahora se accede por WordPress/Elementor
 */

// Aumentar límites de PHP para archivos grandes
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', '600'); // 10 minutos
ini_set('max_input_time', '600');

// Si se solicita descarga directa del ZIP
if (isset($_GET['descargar']) && !empty($_GET['descargar'])) {
    $zip_file = __DIR__ . '/' . basename($_GET['descargar']);
    if (file_exists($zip_file) && pathinfo($zip_file, PATHINFO_EXTENSION) === 'zip') {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zip_file) . '"');
        header('Content-Length: ' . filesize($zip_file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        readfile($zip_file);
        exit;
    } else {
        die("Archivo no encontrado o inválido.");
    }
}

echo "<h1>📦 CREAR ZIP DE WEB ANTIGUA</h1>";
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

// Verificar que la extensión ZIP esté disponible
if (!extension_loaded('zip')) {
    die("<div class='error'>❌ La extensión ZIP de PHP no está disponible. No se puede crear el archivo ZIP.</div>");
}

$base_dir = __DIR__;
$zip_filename = 'web_antigua_geofal_' . date('Y-m-d_His') . '.zip';
$zip_path = $base_dir . '/' . $zip_filename;

// Opción para excluir PDFs (hace el ZIP mucho más pequeño)
$excluir_pdfs = isset($_POST['excluir_pdfs']) && $_POST['excluir_pdfs'] === '1';

// Archivos y carpetas a incluir en el ZIP
$archivos_incluir = [
    // Carpeta completa de páginas públicas (con o sin PDFs según opción)
    'publico/' => 'publico/',
    
    // Archivos compartidos que usa la web antigua
    'app/vista/head.php' => 'app/vista/head.php',
    'app/vista/header.php' => 'app/vista/header.php',
    'app/vista/footer.php' => 'app/vista/footer.php',
    'app/vista/carusel.php' => 'app/vista/carusel.php',
    'app/vista/carusel_2.php' => 'app/vista/carusel_2.php',
    
    // Modelo que usa
    'app/modelo/inicioModelo.php' => 'app/modelo/inicioModelo.php',
    
    // Keys.php si existe
    'app/controlador/keys.php' => 'app/controlador/keys.php',
    
    // Configuración (solo para referencia, sin datos sensibles)
    'config/config.php' => 'config/config.php',
    'config/sistema.php' => 'config/sistema.php',
];

echo "<div class='info'>";
echo "<h2>📋 Archivos a Incluir en el ZIP</h2>";
echo "<ul>";
foreach ($archivos_incluir as $origen => $destino) {
    $ruta_completa = $base_dir . '/' . $origen;
    if (is_dir($ruta_completa) || file_exists($ruta_completa)) {
        echo "<li>✓ <code>{$origen}</code> → <code>{$destino}</code></li>";
    } else {
        echo "<li>⚠️ <code>{$origen}</code> (no existe, se omitirá)</li>";
    }
}
echo "</ul>";
echo "</div>";

if (isset($_POST['crear_zip'])) {
    echo "<div class='warning'>";
    echo "<h2>🔄 Creando ZIP...</h2>";
    echo "</div>";
    
    $zip = new ZipArchive();
    
    if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        die("<div class='error'>❌ No se pudo crear el archivo ZIP: {$zip_filename}</div>");
    }
    
    $archivos_agregados = 0;
    $errores = 0;
    
    // Función recursiva para agregar directorios
    function agregarDirectorio($zip, $dir, $base_dir, $destino_base, $excluir_pdfs = false) {
        $archivos = 0;
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            
            $ruta_completa = $dir . '/' . $file;
            $ruta_relativa = str_replace($base_dir . '/', '', $ruta_completa);
            $ruta_destino = $destino_base . '/' . $file;
            
            // Excluir PDFs si se solicita
            if ($excluir_pdfs && strpos($ruta_completa, 'img_data') !== false && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
                continue; // Saltar PDFs en img_data
            }
            
            if (is_dir($ruta_completa)) {
                // Agregar directorio vacío
                $zip->addEmptyDir($ruta_destino);
                // Recursión
                $archivos += agregarDirectorio($zip, $ruta_completa, $base_dir, $ruta_destino, $excluir_pdfs);
            } else {
                if ($zip->addFile($ruta_completa, $ruta_destino)) {
                    $archivos++;
                }
            }
        }
        
        return $archivos;
    }
    
    // Agregar archivos y carpetas
    foreach ($archivos_incluir as $origen => $destino) {
        $ruta_completa = $base_dir . '/' . $origen;
        
        if (is_dir($ruta_completa)) {
            // Es un directorio
            $zip->addEmptyDir($destino);
            $archivos_en_dir = agregarDirectorio($zip, $ruta_completa, $base_dir, $destino, $excluir_pdfs);
            $archivos_agregados += $archivos_en_dir;
            echo "<div class='success'>✓ Agregado directorio: <code>{$origen}</code> ({$archivos_en_dir} archivos)" . ($excluir_pdfs && strpos($origen, 'publico') !== false ? " <small>(PDFs excluidos)</small>" : "") . "</div>";
        } elseif (file_exists($ruta_completa)) {
            // Es un archivo
            if ($zip->addFile($ruta_completa, $destino)) {
                $archivos_agregados++;
                echo "<div class='success'>✓ Agregado archivo: <code>{$origen}</code></div>";
            } else {
                $errores++;
                echo "<div class='error'>✗ Error al agregar: <code>{$origen}</code></div>";
            }
        } else {
            echo "<div class='warning'>⚠️ No existe: <code>{$origen}</code> (omitido)</div>";
        }
    }
    
    // Agregar README explicativo
    $readme_content = "# WEB ANTIGUA GEOFAL - ARCHIVO\n\n";
    $readme_content .= "Este ZIP contiene la web antigua de Geofal que ya no se usa.\n\n";
    $readme_content .= "## 📋 Contenido\n\n";
    $readme_content .= "- **publico/**: Páginas públicas (contacto, nosotros, galería, etc.)\n";
    $readme_content .= "- **app/vista/**: Archivos compartidos (head.php, header.php, footer.php, carusel.php)\n";
    $readme_content .= "- **app/modelo/**: Modelo inicioModelo.php usado por las páginas públicas\n";
    $readme_content .= "- **config/**: Archivos de configuración (solo referencia)\n\n";
    $readme_content .= "## ⚠️ IMPORTANTE\n\n";
    $readme_content .= "Esta web antigua ya NO se usa porque:\n";
    $readme_content .= "- Ahora se accede directamente por WordPress/Elementor\n";
    $readme_content .= "- El intranet y extranet son sistemas separados\n";
    $readme_content .= "- Las páginas públicas están obsoletas\n\n";
    $readme_content .= "## 📅 Fecha de Archivado\n\n";
    $readme_content .= date('Y-m-d H:i:s') . "\n\n";
    $readme_content .= "## 🔄 Uso\n\n";
    $readme_content .= "Este ZIP es solo para referencia histórica. No restaurar en producción.\n";
    
    $zip->addFromString('README.md', $readme_content);
    
    $zip->close();
    
    $tamaño = filesize($zip_path);
    $tamaño_mb = round($tamaño / 1024 / 1024, 2);
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Archivos agregados:</strong> {$archivos_agregados}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "<p><strong>Tamaño del ZIP:</strong> {$tamaño_mb} MB</p>";
    echo "</div>";
    
    if ($errores == 0) {
        echo "<div class='success'>";
        echo "<h2>✅ ZIP Creado Exitosamente</h2>";
        echo "<p><strong>Archivo:</strong> <code>{$zip_filename}</code></p>";
        echo "<p><strong>Ubicación:</strong> <code>" . htmlspecialchars($zip_path) . "</code></p>";
        echo "<p><strong>Tamaño:</strong> {$tamaño_mb} MB</p>";
        echo "<p><strong>Archivos incluidos:</strong> {$archivos_agregados}</p>";
        if ($excluir_pdfs) {
            echo "<p><strong>⚠️ Nota:</strong> Los PDFs de <code>img_data/</code> fueron excluidos para reducir el tamaño.</p>";
        }
        echo "<p><a href='?descargar=" . urlencode($zip_filename) . "' style='background: #28a745; color: white; padding: 15px 30px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 10px; font-size: 18px; font-weight: bold;'>⬇️ Descargar ZIP ({$tamaño_mb} MB)</a></p>";
        echo "<p><small>Si la descarga no funciona, copia la ruta del archivo y ábrelo directamente desde el explorador de archivos.</small></p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h2>⚠️ ZIP Creado con Errores</h2>";
        echo "<p>Algunos archivos no se pudieron agregar. Revisa los errores arriba.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Acción Requerida</h2>";
    echo "<p>Este script creará un archivo ZIP con toda la web antigua (páginas públicas) para archivarla.</p>";
    echo "<p><strong>Incluirá:</strong></p>";
    echo "<ul>";
    echo "<li>Carpeta <code>publico/</code> completa (controladores, vistas, imágenes)</li>";
    echo "<li>Archivos compartidos: <code>head.php</code>, <code>header.php</code>, <code>footer.php</code>, <code>carusel.php</code></li>";
    echo "<li>Modelo: <code>inicioModelo.php</code></li>";
    echo "<li>Configuración (solo referencia)</li>";
    echo "</ul>";
    echo "<div class='warning'>";
    echo "<p><strong>⚠️ Tamaño del ZIP:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Con PDFs:</strong> ~607 MB (454 archivos PDF en <code>img_data/</code>)</li>";
    echo "<li><strong>Sin PDFs:</strong> ~50-100 MB (solo código, imágenes y estructura)</li>";
    echo "</ul>";
    echo "<p><strong>Recomendación:</strong> Excluir PDFs para descarga más rápida. Los PDFs se pueden archivar por separado si es necesario.</p>";
    echo "</div>";
    echo "<form method='POST'>";
    echo "<div style='margin: 15px 0;'>";
    echo "<label style='display: flex; align-items: center; cursor: pointer;'>";
    echo "<input type='checkbox' name='excluir_pdfs' value='1' checked style='margin-right: 10px; width: 20px; height: 20px;'>";
    echo "<span><strong>Excluir PDFs de img_data/</strong> (recomendado - reduce el tamaño de ~607 MB a ~50-100 MB)</span>";
    echo "</label>";
    echo "</div>";
    echo "<button type='submit' name='crear_zip' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>Crear ZIP de Web Antigua</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de crear el ZIP.</p>";
?>

