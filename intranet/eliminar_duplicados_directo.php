<?php
/**
 * Script para eliminar duplicados DIRECTAMENTE
 * ⚠️ ADVERTENCIA: Este script elimina las carpetas sin confirmación adicional
 * 
 * Ejecutar desde: http://localhost/public_html/intranet/eliminar_duplicados_directo.php?confirmar=SI
 */

$confirmar = isset($_GET['confirmar']) && strtoupper($_GET['confirmar']) === 'SI';

echo "<h1>🗑️ ELIMINACIÓN DIRECTA DE DUPLICADOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .danger { background: #f8d7da; border: 2px solid #dc3545; padding: 20px; margin: 10px 0; border-radius: 5px; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 20px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 20px; margin: 10px 0; border-radius: 5px; }
    .url { background: #e9ecef; padding: 5px; font-family: monospace; }
</style>";

$baseDir = __DIR__;

$carpetasEliminar = [
    [
        'ruta' => $baseDir . DIRECTORY_SEPARATOR . 'intranet2',
        'nombre' => 'intranet2/',
        'razon' => 'Copia completa duplicada de intranet/'
    ],
    [
        'ruta' => $baseDir . DIRECTORY_SEPARATOR . 'admin2',
        'nombre' => 'admin2/',
        'razon' => 'Sistema PHPMaker obsoleto con errores de PHP y credenciales incorrectas'
    ]
];

// Función recursiva para eliminar directorio
function eliminarDirectorio($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    
    // Intentar cambiar permisos si es necesario
    if (!is_writable($dir)) {
        chmod($dir, 0777);
    }
    
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            eliminarDirectorio($path);
        } else {
            // Intentar cambiar permisos del archivo
            if (!is_writable($path)) {
                chmod($path, 0666);
            }
            @unlink($path);
        }
    }
    return @rmdir($dir);
}

if (!$confirmar) {
    echo "<div class='warning'>";
    echo "<h2>⚠️ ADVERTENCIA</h2>";
    echo "<p>Este script eliminará las siguientes carpetas duplicadas:</p>";
    echo "<ul>";
    foreach ($carpetasEliminar as $carpeta) {
        $existe = is_dir($carpeta['ruta']);
        $estado = $existe ? "<span style='color: green;'>✓ Existe</span>" : "<span style='color: gray;'>No existe</span>";
        echo "<li><strong>{$carpeta['nombre']}</strong> - {$carpeta['razon']} - {$estado}</li>";
    }
    echo "</ul>";
    echo "<p><strong>Esta acción NO se puede deshacer.</strong></p>";
    echo "<p>Para confirmar la eliminación, agrega <code>?confirmar=SI</code> a la URL:</p>";
    echo "<p class='url'>" . $_SERVER['REQUEST_URI'] . "?confirmar=SI</p>";
    echo "<p><a href='?confirmar=SI' style='background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>🗑️ CONFIRMAR ELIMINACIÓN</a></p>";
    echo "</div>";
} else {
    echo "<div class='danger'>";
    echo "<h2>🗑️ ELIMINANDO CARPETAS...</h2>";
    
    $eliminadas = [];
    $errores = [];
    $noExisten = [];
    
    foreach ($carpetasEliminar as $carpeta) {
        if (!is_dir($carpeta['ruta'])) {
            $noExisten[] = $carpeta['nombre'];
            echo "<p style='color: gray;'>⊘ No existe: <strong>{$carpeta['nombre']}</strong> (ya fue eliminada o no existe)</p>";
            continue;
        }
        
        echo "<p>Eliminando: <strong>{$carpeta['nombre']}</strong>...</p>";
        echo "<p style='color: #666;'>Ruta: <code>{$carpeta['ruta']}</code></p>";
        
        if (eliminarDirectorio($carpeta['ruta'])) {
            $eliminadas[] = $carpeta['nombre'];
            echo "<p style='color: green; font-weight: bold;'>✓ Eliminada exitosamente: {$carpeta['nombre']}</p>";
        } else {
            $errores[] = $carpeta['nombre'];
            echo "<p style='color: red; font-weight: bold;'>✗ Error al eliminar: {$carpeta['nombre']}</p>";
            echo "<p style='color: #666;'>Posibles causas: permisos insuficientes, archivos en uso, o carpeta protegida.</p>";
        }
        echo "<hr>";
    }
    
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Eliminadas exitosamente:</strong> " . count($eliminadas) . "</p>";
    if (!empty($eliminadas)) {
        echo "<ul>";
        foreach ($eliminadas as $elim) {
            echo "<li style='color: green;'>✓ {$elim}</li>";
        }
        echo "</ul>";
    }
    
    if (!empty($noExisten)) {
        echo "<p><strong>No existían:</strong> " . count($noExisten) . "</p>";
        echo "<ul>";
        foreach ($noExisten as $no) {
            echo "<li style='color: gray;'>⊘ {$no}</li>";
        }
        echo "</ul>";
    }
    
    if (!empty($errores)) {
        echo "<p style='color: red;'><strong>Errores:</strong> " . count($errores) . "</p>";
        echo "<ul>";
        foreach ($errores as $err) {
            echo "<li style='color: red;'>✗ {$err}</li>";
        }
        echo "</ul>";
        echo "<p><strong>Sugerencia:</strong> Intenta eliminar manualmente desde el explorador de archivos o verifica los permisos.</p>";
    }
    
    echo "</div>";
    
    if (count($eliminadas) > 0 || count($noExisten) > 0) {
        echo "<div class='success'>";
        echo "<h3>✅ Proceso completado</h3>";
        echo "<p>Las carpetas duplicadas han sido eliminadas o no existían.</p>";
        echo "<p><a href='mapeo_flujos_completo.php'>← Ver mapeo de flujos</a></p>";
        echo "<p><a href='validar_flujos.php'>← Validar flujos</a></p>";
        echo "</div>";
    }
}

echo "<div class='info' style='margin-top: 30px;'>";
echo "<h3>📝 Notas:</h3>";
echo "<ul>";
echo "<li>Este script elimina <strong>permanentemente</strong> las carpetas duplicadas.</li>";
echo "<li>Si hay errores de permisos, puede ser necesario ejecutar como administrador o cambiar permisos manualmente.</li>";
echo "<li>Después de eliminar, ejecuta <code>mapeo_flujos_completo.php</code> para verificar los flujos válidos.</li>";
echo "</ul>";
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (eliminar_duplicados_directo.php) después de usarlo por seguridad.</p>";
?>

