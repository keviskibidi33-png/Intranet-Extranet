<?php
/**
 * Script para eliminar carpetas duplicadas de forma segura
 * IMPORTANTE: Revisar antes de ejecutar
 * 
 * Ejecutar desde: http://localhost/public_html/intranet/eliminar_duplicados.php
 */

echo "<h1>🗑️ ELIMINACIÓN DE DUPLICADOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .danger { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    button { padding: 10px 20px; margin: 5px; cursor: pointer; font-size: 16px; }
    .btn-danger { background: #dc3545; color: white; border: none; border-radius: 5px; }
    .btn-success { background: #28a745; color: white; border: none; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
</style>";

// Función para calcular tamaño de directorio
function getDirectorySize($dir) {
    $size = 0;
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
    }
    return $size;
}

// Función para formatear bytes
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Función para contar archivos
function countFiles($dir) {
    $count = 0;
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $count++;
            }
        }
    }
    return $count;
}

$baseDir = __DIR__;

// Carpetas a eliminar (duplicados confirmados)
$carpetasEliminar = [
    [
        'ruta' => $baseDir . '/intranet2',
        'nombre' => 'intranet2/',
        'razon' => 'Copia completa duplicada de intranet/',
        'tamaño' => 0,
        'archivos' => 0,
        'existe' => is_dir($baseDir . '/intranet2')
    ],
    [
        'ruta' => $baseDir . '/admin2',
        'nombre' => 'admin2/',
        'razon' => 'Sistema PHPMaker obsoleto con errores de PHP y credenciales incorrectas',
        'tamaño' => 0,
        'archivos' => 0,
        'existe' => is_dir($baseDir . '/admin2')
    ]
];

// Calcular tamaños y archivos
foreach ($carpetasEliminar as &$carpeta) {
    if ($carpeta['existe']) {
        $carpeta['tamaño'] = getDirectorySize($carpeta['ruta']);
        $carpeta['archivos'] = countFiles($carpeta['ruta']);
    }
}

// Si se envió el formulario de confirmación
if (isset($_POST['confirmar']) && $_POST['confirmar'] == 'si') {
    echo "<div class='danger'>";
    echo "<h2>⚠️ ELIMINANDO CARPETAS...</h2>";
    
    $eliminadas = [];
    $errores = [];
    
    foreach ($carpetasEliminar as $carpeta) {
        if ($carpeta['existe'] && isset($_POST['eliminar_' . md5($carpeta['nombre'])])) {
            echo "<p>Eliminando: <strong>{$carpeta['nombre']}</strong>...</p>";
            
            // Función recursiva para eliminar
            function eliminarDirectorio($dir) {
                if (!is_dir($dir)) {
                    return false;
                }
                $files = array_diff(scandir($dir), array('.', '..'));
                foreach ($files as $file) {
                    $path = $dir . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($path)) {
                        eliminarDirectorio($path);
                    } else {
                        unlink($path);
                    }
                }
                return rmdir($dir);
            }
            
            if (eliminarDirectorio($carpeta['ruta'])) {
                $eliminadas[] = $carpeta['nombre'];
                echo "<p style='color: green;'>✓ Eliminada: {$carpeta['nombre']}</p>";
            } else {
                $errores[] = $carpeta['nombre'];
                echo "<p style='color: red;'>✗ Error al eliminar: {$carpeta['nombre']}</p>";
            }
        }
    }
    
    echo "<hr>";
    echo "<h3>Resumen:</h3>";
    echo "<p><strong>Eliminadas:</strong> " . count($eliminadas) . "</p>";
    if (!empty($eliminadas)) {
        echo "<ul>";
        foreach ($eliminadas as $elim) {
            echo "<li>{$elim}</li>";
        }
        echo "</ul>";
    }
    
    if (!empty($errores)) {
        echo "<p style='color: red;'><strong>Errores:</strong> " . count($errores) . "</p>";
        echo "<ul>";
        foreach ($errores as $err) {
            echo "<li>{$err}</li>";
        }
        echo "</ul>";
    }
    
    echo "</div>";
    
    echo "<div class='success'>";
    echo "<h3>✅ Proceso completado</h3>";
    echo "<p><a href='validar_flujos.php'>← Volver a validación de flujos</a></p>";
    echo "<p style='color: red;'><strong>IMPORTANTE:</strong> Elimina este archivo (eliminar_duplicados.php) después de usarlo.</p>";
    echo "</div>";
    
} else {
    // Mostrar información y formulario de confirmación
    echo "<div class='warning'>";
    echo "<h2>⚠️ ADVERTENCIA</h2>";
    echo "<p>Este script eliminará las siguientes carpetas duplicadas. <strong>Esta acción NO se puede deshacer.</strong></p>";
    echo "<p>Por favor, revisa cuidadosamente antes de confirmar.</p>";
    echo "</div>";
    
    echo "<table>";
    echo "<tr><th>Carpeta</th><th>Tamaño</th><th>Archivos</th><th>Razón</th><th>Estado</th><th>Eliminar</th></tr>";
    
    foreach ($carpetasEliminar as $carpeta) {
        $estado = $carpeta['existe'] ? '<span style="color: green;">✓ Existe</span>' : '<span style="color: gray;">No existe</span>';
        $checkbox = $carpeta['existe'] ? 
            "<input type='checkbox' name='eliminar_" . md5($carpeta['nombre']) . "' checked>" : 
            "<span style='color: gray;'>N/A</span>";
        
        echo "<tr>";
        echo "<td><strong>{$carpeta['nombre']}</strong></td>";
        echo "<td>" . ($carpeta['existe'] ? formatBytes($carpeta['tamaño']) : 'N/A') . "</td>";
        echo "<td>" . ($carpeta['existe'] ? number_format($carpeta['archivos']) : 'N/A') . "</td>";
        echo "<td>{$carpeta['razon']}</td>";
        echo "<td>{$estado}</td>";
        echo "<td>{$checkbox}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    $totalTamaño = array_sum(array_column($carpetasEliminar, 'tamaño'));
    $totalArchivos = array_sum(array_column($carpetasEliminar, 'archivos'));
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen Total:</h3>";
    echo "<p><strong>Espacio a liberar:</strong> " . formatBytes($totalTamaño) . "</p>";
    echo "<p><strong>Archivos a eliminar:</strong> " . number_format($totalArchivos) . "</p>";
    echo "</div>";
    
    echo "<form method='POST' onsubmit='return confirm(\"¿Estás seguro de eliminar estas carpetas? Esta acción NO se puede deshacer.\");'>";
    echo "<input type='hidden' name='confirmar' value='si'>";
    echo "<button type='submit' class='btn-danger'>🗑️ CONFIRMAR ELIMINACIÓN</button>";
    echo "<a href='validar_flujos.php'><button type='button' class='btn-success'>← Cancelar</button></a>";
    echo "</form>";
    
    echo "<div class='info'>";
    echo "<h3>📝 Notas:</h3>";
    echo "<ul>";
    echo "<li><strong>intranet2/</strong>: Es una copia completa duplicada. No se usa en producción.</li>";
    echo "<li><strong>admin2/</strong>: Sistema PHPMaker obsoleto con errores de compatibilidad PHP y credenciales incorrectas.</li>";
    echo "<li>Después de eliminar, ejecuta <code>validar_flujos.php</code> para verificar que todo funciona correctamente.</li>";
    echo "</ul>";
    echo "</div>";
}
?>

