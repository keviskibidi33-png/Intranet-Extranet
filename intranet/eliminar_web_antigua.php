<?php
/**
 * Script para eliminar la web antigua de forma segura
 * Verifica que no afecte el flujo del intranet/extranet
 */

echo "<h1>🗑️ ELIMINAR WEB ANTIGUA</h1>";
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

// Archivos y carpetas a eliminar (solo web antigua)
$eliminar = [
    'publico/' => 'Carpeta completa de páginas públicas (web antigua)',
];

// Archivos compartidos que SOLO usa la web antigua (verificar antes de eliminar)
$archivos_compartidos = [
    'app/vista/head.php' => 'Head compartido (usado por web antigua)',
    'app/vista/header.php' => 'Header compartido (usado por web antigua)',
    'app/vista/footer.php' => 'Footer compartido (usado por web antigua)',
    'app/vista/carusel.php' => 'Carrusel (usado por web antigua)',
    'app/vista/carusel_2.php' => 'Carrusel 2 (usado por web antigua)',
];

// Verificar si estos archivos se usan en el flujo de clientes
echo "<div class='info'>";
echo "<h2>🔍 Verificación de Archivos</h2>";
echo "</div>";

$archivos_usados_por_clientes = [];
$archivos_solo_web_antigua = [];

foreach ($archivos_compartidos as $archivo => $descripcion) {
    $ruta_completa = $base_dir . '/' . $archivo;
    
    // Buscar referencias en app/vista/ y app/controlador/ (flujo clientes)
    // El flujo de clientes usa head_2.php y header_2.php, NO usa head.php, header.php, footer.php, carusel.php
    $buscar_en = [
        $base_dir . '/app/vista/login.phtml',
        $base_dir . '/app/vista/ordenes_2.phtml',
        $base_dir . '/app/vista/ordenes.phtml',
        $base_dir . '/app/controlador/loginCon.php',
        $base_dir . '/app/controlador/ordenesCon.php',
    ];
    
    $usado_por_clientes = false;
    $nombre_archivo = basename($archivo);
    
    // Verificar si el archivo existe y buscar referencias
    if (file_exists($ruta_completa)) {
        foreach ($buscar_en as $archivo_buscar) {
            if (file_exists($archivo_buscar)) {
                $contenido = file_get_contents($archivo_buscar);
                // Buscar el nombre exacto del archivo (con comillas o sin)
                if (preg_match('/["\']' . preg_quote($nombre_archivo, '/') . '["\']/', $contenido)) {
                    $usado_por_clientes = true;
                    break;
                }
            }
        }
    }
    
    if ($usado_por_clientes) {
        $archivos_usados_por_clientes[$archivo] = $descripcion;
        echo "<div class='warning'>⚠️ <code>{$archivo}</code> - Usado por flujo clientes (NO eliminar)</div>";
    } else {
        $archivos_solo_web_antigua[$archivo] = $descripcion;
        echo "<div class='success'>✓ <code>{$archivo}</code> - Solo usado por web antigua (se puede eliminar)</div>";
    }
}

// Verificar index.php
echo "<h3>📋 Verificación de index.php</h3>";
$index_content = file_get_contents($base_dir . '/index.php');
if (strpos($index_content, 'publico/controlador') !== false) {
    echo "<div class='warning'>⚠️ <code>index.php</code> busca controladores en <code>publico/controlador/</code> (necesita actualización)</div>";
} else {
    echo "<div class='success'>✓ <code>index.php</code> no busca en <code>publico/controlador/</code></div>";
}

if (isset($_POST['eliminar'])) {
    echo "<div class='warning'>";
    echo "<h2>🗑️ Eliminando Web Antigua...</h2>";
    echo "</div>";
    
    $eliminados = 0;
    $errores = 0;
    
    // Función recursiva para eliminar directorios
    function eliminarDirectorio($dir) {
        if (!is_dir($dir)) {
            return false;
        }
        
        $archivos = array_diff(scandir($dir), array('.', '..'));
        foreach ($archivos as $archivo) {
            $ruta = $dir . '/' . $archivo;
            if (is_dir($ruta)) {
                eliminarDirectorio($ruta);
            } else {
                unlink($ruta);
            }
        }
        return rmdir($dir);
    }
    
    // Eliminar carpeta publico/
    foreach ($eliminar as $ruta => $descripcion) {
        $ruta_completa = $base_dir . '/' . $ruta;
        if (is_dir($ruta_completa)) {
            if (eliminarDirectorio($ruta_completa)) {
                echo "<div class='success'>✓ Eliminado: <code>{$ruta}</code> - {$descripcion}</div>";
                $eliminados++;
            } else {
                echo "<div class='error'>✗ Error al eliminar: <code>{$ruta}</code></div>";
                $errores++;
            }
        } else {
            echo "<div class='warning'>⚠️ No existe: <code>{$ruta}</code></div>";
        }
    }
    
    // Eliminar archivos compartidos que solo usa web antigua
    foreach ($archivos_solo_web_antigua as $archivo => $descripcion) {
        $ruta_completa = $base_dir . '/' . $archivo;
        if (file_exists($ruta_completa)) {
            if (unlink($ruta_completa)) {
                echo "<div class='success'>✓ Eliminado: <code>{$archivo}</code> - {$descripcion}</div>";
                $eliminados++;
            } else {
                echo "<div class='error'>✗ Error al eliminar: <code>{$archivo}</code></div>";
                $errores++;
            }
        }
    }
    
    // Actualizar index.php para quitar búsqueda en publico/
    $index_path = $base_dir . '/index.php';
    $index_content = file_get_contents($index_path);
    $index_original = $index_content;
    
    // Remover búsqueda en publico/controlador/
    $index_content = preg_replace(
        '/elseif\(is_file\("publico\/controlador\/"\.\$pagina\."Con\.php"\)\)\s*\{[^}]*\}/',
        '',
        $index_content
    );
    
    if ($index_content !== $index_original) {
        if (file_put_contents($index_path, $index_content)) {
            echo "<div class='success'>✓ Actualizado: <code>index.php</code> - Removida búsqueda en publico/controlador/</div>";
        } else {
            echo "<div class='error'>✗ Error al actualizar: <code>index.php</code></div>";
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Elementos eliminados:</strong> {$eliminados}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
    if ($errores == 0) {
        echo "<div class='success'>";
        echo "<h2>✅ Web Antigua Eliminada Exitosamente</h2>";
        echo "<p>La web antigua ha sido eliminada sin afectar el flujo del intranet/extranet.</p>";
        echo "<p><strong>Estructura final:</strong></p>";
        echo "<ul>";
        echo "<li>✓ <code>app/</code> - Flujo clientes (intacto)</li>";
        echo "<li>✓ <code>admin/</code> - Panel administración (intacto)</li>";
        echo "<li>✓ <code>config/</code> - Configuración (intacto)</li>";
        echo "<li>✗ <code>publico/</code> - Web antigua (eliminada)</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h2>⚠️ Eliminación con Errores</h2>";
        echo "<p>Algunos elementos no se pudieron eliminar. Revisa los errores arriba.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Acción Requerida</h2>";
    echo "<p>Este script eliminará la web antigua de forma segura:</p>";
    echo "<p><strong>Se eliminará:</strong></p>";
    echo "<ul>";
    echo "<li>Carpeta <code>publico/</code> completa (controladores, vistas, imágenes, PDFs)</li>";
    foreach ($archivos_solo_web_antigua as $archivo => $descripcion) {
        echo "<li><code>{$archivo}</code> - {$descripcion}</li>";
    }
    echo "</ul>";
    echo "<p><strong>NO se eliminará (flujo intranet):</strong></p>";
    echo "<ul>";
    echo "<li>Carpeta <code>app/</code> - Flujo clientes</li>";
    echo "<li>Carpeta <code>admin/</code> - Panel administración</li>";
    echo "<li>Carpeta <code>config/</code> - Configuración</li>";
    foreach ($archivos_usados_por_clientes as $archivo => $descripcion) {
        echo "<li><code>{$archivo}</code> - {$descripcion}</li>";
    }
    echo "</ul>";
    echo "<p><strong>También se actualizará:</strong></p>";
    echo "<ul>";
    echo "<li><code>index.php</code> - Remover búsqueda en publico/controlador/</li>";
    echo "</ul>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='eliminar' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;' onclick='return confirm(\"¿Estás seguro de eliminar la web antigua? Esta acción no se puede deshacer.\");'>🗑️ Eliminar Web Antigua</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

