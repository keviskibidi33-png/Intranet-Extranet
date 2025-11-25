<?php
/**
 * Script completo para analizar dependencias del sistema admin
 * Identifica qué se usa realmente y qué se puede eliminar
 * Acceder desde: http://localhost/public_html/intranet/admin/analizar_dependencias_completo.php
 */

echo "<h1>🔍 ANÁLISIS COMPLETO DE DEPENDENCIAS</h1>";
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
    .code { background: #f4f4f4; padding: 3px 8px; border-radius: 3px; font-family: monospace; }
</style>";

$base_dir = __DIR__;

// Función para buscar referencias en archivos
function buscarReferencias($directorio, $patron) {
    $resultados = [];
    $archivos = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directorio),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($archivos as $archivo) {
        if ($archivo->isFile() && preg_match('/\.(php|phtml|js|css)$/i', $archivo->getFilename())) {
            $contenido = @file_get_contents($archivo->getPathname());
            if ($contenido && preg_match($patron, $contenido)) {
                $resultados[] = str_replace($directorio . '/', '', $archivo->getPathname());
            }
        }
    }
    
    return $resultados;
}

echo "<div class='info'>";
echo "<h2>📋 ANÁLISIS DE DEPENDENCIAS DEL SISTEMA ADMIN</h2>";
echo "<p>Este análisis identifica qué dependencias se usan realmente en el código.</p>";
echo "</div>";

// ============================================
// 1. PHPMailer
// ============================================
echo "<h3>1️⃣ PHPMailer (include/phpmiler/)</h3>";

$phpmailer_referencias = buscarReferencias(
    $base_dir . '/app',
    '/phpmiler|PHPMailer/i'
);

if (!empty($phpmailer_referencias)) {
    echo "<div class='success'>";
    echo "<p><strong>Estado:</strong> <span class='necesario'>✓ NECESARIO</span></p>";
    echo "<p><strong>Usado en:</strong></p>";
    echo "<ul>";
    foreach ($phpmailer_referencias as $ref) {
        echo "<li><code>{$ref}</code></li>";
    }
    echo "</ul>";
    echo "<p><strong>Función:</strong> Envía emails automáticamente cuando se crea un nuevo cliente</p>";
    echo "<p><strong>Recomendación:</strong> <span class='necesario'>MANTENER</span> - Es obligatorio para el sistema</p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<p><strong>Estado:</strong> No se encontraron referencias directas</p>";
    echo "<p><strong>Recomendación:</strong> Verificar manualmente antes de eliminar</p>";
    echo "</div>";
}

// ============================================
// 2. Vendors - Análisis detallado
// ============================================
echo "<h3>2️⃣ Vendors (include/vendors/)</h3>";

$vendors_dir = $base_dir . '/include/vendors';
$vendors_existentes = [];
if (is_dir($vendors_dir)) {
    $dirs = scandir($vendors_dir);
    foreach ($dirs as $dir) {
        if ($dir != '.' && $dir != '..' && is_dir($vendors_dir . '/' . $dir)) {
            $vendors_existentes[] = $dir;
        }
    }
}

$vendors_usados = [];
$vendors_no_usados = [];

// Vendors críticos (siempre necesarios)
$vendors_criticos = ['jquery', 'bootstrap', 'font-awesome', 'popper.js'];

foreach ($vendors_existentes as $vendor) {
    $vendor_normalizado = strtolower($vendor);
    $vendor_normalizado = str_replace(['-', '_', '.js', '.css'], ['', '', '', ''], $vendor_normalizado);
    
    // Buscar referencias
    $patron = '/' . preg_quote($vendor, '/') . '/i';
    $referencias = buscarReferencias($base_dir . '/app', $patron);
    
    // También buscar en include/assets
    $referencias_assets = buscarReferencias($base_dir . '/include/assets', $patron);
    
    $total_referencias = count($referencias) + count($referencias_assets);
    
    if (in_array($vendor_normalizado, $vendors_criticos) || $total_referencias > 0) {
        $vendors_usados[$vendor] = [
            'referencias' => array_merge($referencias, $referencias_assets),
            'total' => $total_referencias,
            'critico' => in_array($vendor_normalizado, $vendors_criticos)
        ];
    } else {
        $vendors_no_usados[] = $vendor;
    }
}

echo "<table>";
echo "<tr><th>Vendor</th><th>Estado</th><th>Referencias</th><th>Acción</th></tr>";

// Mostrar vendors usados
foreach ($vendors_usados as $vendor => $info) {
    $estado = $info['critico'] ? "<span class='necesario'>✓ CRÍTICO</span>" : "<span class='necesario'>✓ USADO</span>";
    $referencias_texto = $info['total'] > 0 ? $info['total'] . " archivo(s)" : "Crítico";
    $accion = $info['critico'] ? "<span class='necesario'>MANTENER</span>" : "<span class='opcional'>VERIFICAR</span>";
    
    echo "<tr>";
    echo "<td><code>{$vendor}</code></td>";
    echo "<td>{$estado}</td>";
    echo "<td>{$referencias_texto}</td>";
    echo "<td>{$accion}</td>";
    echo "</tr>";
}

// Mostrar vendors no usados
foreach ($vendors_no_usados as $vendor) {
    echo "<tr>";
    echo "<td><code>{$vendor}</code></td>";
    echo "<td><span class='basura'>✗ NO USADO</span></td>";
    echo "<td>0 archivos</td>";
    echo "<td><span class='basura'>ELIMINAR</span></td>";
    echo "</tr>";
}

echo "</table>";

// Detalles de vendors críticos
echo "<div class='info'>";
echo "<h4>📌 Vendors Críticos (NUNCA eliminar):</h4>";
echo "<ul>";
echo "<li><code>jquery</code> - Base de JavaScript (usado en todo el admin)</li>";
echo "<li><code>bootstrap</code> - Framework CSS/JS (usado en todo el admin)</li>";
echo "<li><code>font-awesome</code> - Iconos (usado en todo el admin)</li>";
echo "<li><code>popper.js</code> - Requerido por Bootstrap (dropdowns, tooltips)</li>";
echo "</ul>";
echo "</div>";

// ============================================
// 3. Imágenes
// ============================================
echo "<h3>3️⃣ Imágenes (include/images/)</h3>";

$imagenes_dir = $base_dir . '/include/images';
$imagenes_existentes = [];
if (is_dir($imagenes_dir)) {
    $archivos = glob($imagenes_dir . '/*.{png,jpg,jpeg,gif}', GLOB_BRACE);
    foreach ($archivos as $archivo) {
        $imagenes_existentes[] = basename($archivo);
    }
}

$imagenes_usadas = [];
$imagenes_no_usadas = [];

foreach ($imagenes_existentes as $imagen) {
    $patron = '/' . preg_quote($imagen, '/') . '/i';
    $referencias = buscarReferencias($base_dir . '/app', $patron);
    
    if (count($referencias) > 0 || $imagen === 'geofal.png') {
        $imagenes_usadas[$imagen] = $referencias;
    } else {
        $imagenes_no_usadas[] = $imagen;
    }
}

echo "<table>";
echo "<tr><th>Imagen</th><th>Estado</th><th>Referencias</th><th>Acción</th></tr>";

foreach ($imagenes_usadas as $imagen => $refs) {
    $estado = "<span class='necesario'>✓ USADA</span>";
    $referencias_texto = count($refs) > 0 ? count($refs) . " archivo(s)" : "Logo principal";
    $accion = "<span class='necesario'>MANTENER</span>";
    
    echo "<tr>";
    echo "<td><code>{$imagen}</code></td>";
    echo "<td>{$estado}</td>";
    echo "<td>{$referencias_texto}</td>";
    echo "<td>{$accion}</td>";
    echo "</tr>";
}

foreach ($imagenes_no_usadas as $imagen) {
    echo "<tr>";
    echo "<td><code>{$imagen}</code></td>";
    echo "<td><span class='basura'>✗ NO USADA</span></td>";
    echo "<td>0 archivos</td>";
    echo "<td><span class='basura'>ELIMINAR</span></td>";
    echo "</tr>";
}

echo "</table>";

// ============================================
// 4. Resumen y recomendaciones
// ============================================
echo "<div class='warning'>";
echo "<h3>📊 RESUMEN Y RECOMENDACIONES</h3>";

$total_vendors = count($vendors_existentes);
$vendors_usados_count = count($vendors_usados);
$vendors_no_usados_count = count($vendors_no_usados);

$total_imagenes = count($imagenes_existentes);
$imagenes_usadas_count = count($imagenes_usadas);
$imagenes_no_usadas_count = count($imagenes_no_usadas);

echo "<table>";
echo "<tr><th>Categoría</th><th>Total</th><th>Usados</th><th>No Usados</th></tr>";
echo "<tr><td><strong>Vendors</strong></td><td>{$total_vendors}</td><td><span class='necesario'>{$vendors_usados_count}</span></td><td><span class='basura'>{$vendors_no_usados_count}</span></td></tr>";
echo "<tr><td><strong>Imágenes</strong></td><td>{$total_imagenes}</td><td><span class='necesario'>{$imagenes_usadas_count}</span></td><td><span class='basura'>{$imagenes_no_usadas_count}</span></td></tr>";
echo "</table>";

echo "<h4>✅ MANTENER (Obligatorio):</h4>";
echo "<ul>";
echo "<li><strong>PHPMailer</strong> - Envío de emails</li>";
echo "<li><strong>jQuery, Bootstrap, Font Awesome, Popper.js</strong> - Base del sistema</li>";
echo "<li><strong>geofal.png</strong> - Logo del sistema</li>";
echo "</ul>";

if ($vendors_no_usados_count > 0) {
    echo "<h4>🗑️ ELIMINAR (No usados):</h4>";
    echo "<ul>";
    foreach ($vendors_no_usados as $vendor) {
        echo "<li><code>{$vendor}</code></li>";
    }
    echo "</ul>";
}

if ($imagenes_no_usadas_count > 0) {
    echo "<h4>🗑️ ELIMINAR (No usadas):</h4>";
    echo "<ul>";
    foreach ($imagenes_no_usadas as $imagen) {
        echo "<li><code>{$imagen}</code></li>";
    }
    echo "</ul>";
}

echo "</div>";

// ============================================
// 5. Script de limpieza automática
// ============================================
if (isset($_POST['limpiar']) && isset($_POST['confirmar']) && $_POST['confirmar'] === 'SI') {
    echo "<div class='warning'>";
    echo "<h2>🗑️ Ejecutando Limpieza...</h2>";
    echo "</div>";
    
    $eliminados = 0;
    $errores = 0;
    
    // Eliminar vendors no usados
    if (isset($_POST['eliminar_vendors'])) {
        foreach ($vendors_no_usados as $vendor) {
            $ruta = $vendors_dir . '/' . $vendor;
            if (is_dir($ruta)) {
                function eliminarDirectorio($dir) {
                    if (!is_dir($dir)) return false;
                    $archivos = array_diff(scandir($dir), array('.', '..'));
                    foreach ($archivos as $archivo) {
                        $ruta = $dir . '/' . $archivo;
                        is_dir($ruta) ? eliminarDirectorio($ruta) : unlink($ruta);
                    }
                    return rmdir($dir);
                }
                
                if (eliminarDirectorio($ruta)) {
                    echo "<div class='success'>✓ Eliminado vendor: <code>{$vendor}</code></div>";
                    $eliminados++;
                } else {
                    echo "<div class='error'>✗ Error al eliminar: <code>{$vendor}</code></div>";
                    $errores++;
                }
            }
        }
    }
    
    // Eliminar imágenes no usadas
    if (isset($_POST['eliminar_imagenes'])) {
        foreach ($imagenes_no_usadas as $imagen) {
            $ruta = $imagenes_dir . '/' . $imagen;
            if (file_exists($ruta)) {
                if (unlink($ruta)) {
                    echo "<div class='success'>✓ Eliminada imagen: <code>{$imagen}</code></div>";
                    $eliminados++;
                } else {
                    echo "<div class='error'>✗ Error al eliminar: <code>{$imagen}</code></div>";
                    $errores++;
                }
            }
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen de Limpieza:</h3>";
    echo "<p><strong>Elementos eliminados:</strong> {$eliminados}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
} else {
    echo "<div class='warning'>";
    echo "<h3>⚠️ LIMPIEZA AUTOMÁTICA (Opcional)</h3>";
    echo "<p>Puedes eliminar automáticamente los elementos no usados:</p>";
    echo "<form method='POST'>";
    
    if ($vendors_no_usados_count > 0) {
        echo "<div style='margin: 10px 0;'>";
        echo "<label><input type='checkbox' name='eliminar_vendors' value='1'> Eliminar <strong>{$vendors_no_usados_count}</strong> vendors no usados</label>";
        echo "</div>";
    }
    
    if ($imagenes_no_usadas_count > 0) {
        echo "<div style='margin: 10px 0;'>";
        echo "<label><input type='checkbox' name='eliminar_imagenes' value='1'> Eliminar <strong>{$imagenes_no_usadas_count}</strong> imágenes no usadas</label>";
        echo "</div>";
    }
    
    if ($vendors_no_usados_count > 0 || $imagenes_no_usadas_count > 0) {
        echo "<p><strong>⚠️ Confirmación:</strong> Escribe <code>SI</code> para confirmar</p>";
        echo "<input type='text' name='confirmar' placeholder='Escribe SI para confirmar' required style='padding: 5px; margin: 10px 0;'>";
        echo "<br>";
        echo "<button type='submit' name='limpiar' style='background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;'>🗑️ Ejecutar Limpieza</button>";
    } else {
        echo "<p><strong>✓ No hay elementos para eliminar</strong></p>";
    }
    
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

