<?php
/**
 * Script para eliminar carpetas de imágenes duplicadas e innecesarias
 * Mantiene solo: admin/publico/img/ y admin/include/images/geofal.png
 */

echo "<h1>🗑️ ELIMINAR IMÁGENES DUPLICADAS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .warning { background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin: 10px 0; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
</style>";

$base_dir = __DIR__;

// Carpetas a ELIMINAR (duplicadas/innecesarias)
$carpetas_eliminar = [
    'include/images/' => 'Imágenes generales duplicadas (no se usan)',
    'admin/assets/images/' => 'Assets admin duplicados (solo geofal.png, ya está en include/images/)',
];

// Carpetas a MANTENER
$carpetas_mantener = [
    'admin/publico/img/' => 'Imágenes funcionales principales (logo, check, user, etc.)',
    'admin/include/images/' => 'Logo admin login (geofal.png)',
];

echo "<div class='info'>";
echo "<h2>📋 CARPETAS DE IMÁGENES</h2>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>✅ MANTENER (Necesarias):</h3>";
echo "<ul>";
foreach ($carpetas_mantener as $carpeta => $descripcion) {
    $ruta_completa = $base_dir . '/' . $carpeta;
    $existe = is_dir($ruta_completa);
    $archivos = $existe ? count(glob($ruta_completa . '*.*')) : 0;
    $estado = $existe ? "✓ Existe ({$archivos} archivos)" : "✗ No existe";
    echo "<li><code>{$carpeta}</code> - {$descripcion} - {$estado}</li>";
}
echo "</ul>";
echo "</div>";

echo "<div class='error'>";
echo "<h3>🗑️ ELIMINAR (Duplicadas/Innecesarias):</h3>";
echo "<ul>";
foreach ($carpetas_eliminar as $carpeta => $descripcion) {
    $ruta_completa = $base_dir . '/' . $carpeta;
    $existe = is_dir($ruta_completa);
    if ($existe) {
        $archivos = glob($ruta_completa . '**/*', GLOB_BRACE);
        $total_archivos = count(array_filter($archivos, 'is_file'));
        $total_carpetas = count(array_filter($archivos, 'is_dir'));
        echo "<li><code>{$carpeta}</code> - {$descripcion} - <strong>{$total_archivos} archivos, {$total_carpetas} carpetas</strong></li>";
    } else {
        echo "<li><code>{$carpeta}</code> - {$descripcion} - <strong>Ya no existe</strong></li>";
    }
}
echo "</ul>";
echo "</div>";

if (isset($_POST['eliminar']) && isset($_POST['confirmar']) && $_POST['confirmar'] === 'ELIMINAR') {
    echo "<div class='warning'>";
    echo "<h2>🗑️ Eliminando Carpetas Duplicadas...</h2>";
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
                @unlink($ruta);
            }
        }
        return @rmdir($dir);
    }
    
    foreach ($carpetas_eliminar as $carpeta => $descripcion) {
        $ruta_completa = $base_dir . '/' . $carpeta;
        if (is_dir($ruta_completa)) {
            if (eliminarDirectorio($ruta_completa)) {
                echo "<div class='success'>✓ Eliminado: <code>{$carpeta}</code> - {$descripcion}</div>";
                $eliminados++;
            } else {
                echo "<div class='error'>✗ Error al eliminar: <code>{$carpeta}</code></div>";
                $errores++;
            }
        } else {
            echo "<div class='info'>○ No existe: <code>{$carpeta}</code> (ya eliminado o nunca existió)</div>";
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Carpetas eliminadas:</strong> {$eliminados}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
    if ($errores == 0) {
        echo "<div class='success'>";
        echo "<h2>✅ Limpieza Completada</h2>";
        echo "<p>Las carpetas duplicadas han sido eliminadas. Solo quedan las carpetas necesarias:</p>";
        echo "<ul>";
        foreach ($carpetas_mantener as $carpeta => $descripcion) {
            echo "<li><code>{$carpeta}</code> - {$descripcion}</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ ACCIÓN REQUERIDA</h2>";
    echo "<p>Este script eliminará las carpetas de imágenes duplicadas e innecesarias.</p>";
    echo "<p><strong>Se eliminarán:</strong></p>";
    echo "<ul>";
    foreach ($carpetas_eliminar as $carpeta => $descripcion) {
        $ruta_completa = $base_dir . '/' . $carpeta;
        if (is_dir($ruta_completa)) {
            echo "<li><code>{$carpeta}</code> - {$descripcion}</li>";
        }
    }
    echo "</ul>";
    echo "<p><strong>Se mantendrán:</strong></p>";
    echo "<ul>";
    foreach ($carpetas_mantener as $carpeta => $descripcion) {
        echo "<li><code>{$carpeta}</code> - {$descripcion}</li>";
    }
    echo "</ul>";
    echo "<p><strong>⚠️ Confirmación:</strong> Escribe <code>ELIMINAR</code> para confirmar</p>";
    echo "<form method='POST'>";
    echo "<input type='text' name='confirmar' placeholder='Escribe ELIMINAR para confirmar' required style='padding: 5px; margin: 10px 0; width: 300px;'>";
    echo "<br>";
    echo "<button type='submit' name='eliminar' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;'>🗑️ Eliminar Carpetas Duplicadas</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

