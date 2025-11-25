<?php
/**
 * Script para unificar todas las rutas de imágenes y eliminar duplicados
 * Unifica todo a: admin/publico/img/
 */

echo "<h1>🧹 LIMPIEZA Y UNIFICACIÓN DE IMÁGENES</h1>";
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

// Carpeta destino única para todas las imágenes
$destino_unificado = $base_dir . '/admin/publico/img/';

// Carpetas de imágenes a analizar
$carpetas_imagenes = [
    'include/images/' => 'Imágenes generales (intranet/include/images/)',
    'admin/include/images/' => 'Imágenes admin (intranet/admin/include/images/)',
    'admin/publico/img/' => 'Imágenes funcionales (intranet/admin/publico/img/) - DESTINO',
    'admin/assets/images/' => 'Imágenes assets admin (intranet/admin/assets/images/)',
];

// Archivos que se usan realmente (según el análisis)
$imagenes_usadas = [
    // Flujo clientes (app/)
    'logo.png' => 'admin/publico/img/logo.png', // Header clientes
    'check.png' => 'admin/publico/img/check.png', // Check verde
    'check_x.jpg' => 'admin/publico/img/check_x.jpg', // Check rojo/X
    
    // Admin
    'geofal.png' => 'admin/include/images/geofal.png', // Logo admin login
    'user.png' => 'admin/publico/img/user.png', // Avatar usuario
    'foto.jpg' => 'admin/publico/img/foto.jpg', // Foto ayuda
    
    // Otras que pueden estar en uso
    'inacal.jpg' => 'include/images/project/inacal.jpg', // Logo INACAL
];

echo "<div class='info'>";
echo "<h2>📋 ANÁLISIS DE IMÁGENES</h2>";
echo "<p><strong>Carpeta destino unificada:</strong> <code>admin/publico/img/</code></p>";
echo "</div>";

// Analizar qué imágenes existen en cada carpeta
$imagenes_por_carpeta = [];
foreach ($carpetas_imagenes as $carpeta => $descripcion) {
    $ruta_completa = $base_dir . '/' . $carpeta;
    if (is_dir($ruta_completa)) {
        $archivos = glob($ruta_completa . '*.{png,jpg,jpeg,gif,svg}', GLOB_BRACE);
        $imagenes_por_carpeta[$carpeta] = array_map('basename', $archivos);
        echo "<div class='info'>";
        echo "<h3>📁 {$descripcion}</h3>";
        echo "<p><strong>Archivos encontrados:</strong> " . count($imagenes_por_carpeta[$carpeta]) . "</p>";
        if (count($imagenes_por_carpeta[$carpeta]) > 0) {
            echo "<ul>";
            foreach (array_slice($imagenes_por_carpeta[$carpeta], 0, 10) as $img) {
                echo "<li><code>{$img}</code></li>";
            }
            if (count($imagenes_por_carpeta[$carpeta]) > 10) {
                echo "<li>... y " . (count($imagenes_por_carpeta[$carpeta]) - 10) . " más</li>";
            }
            echo "</ul>";
        }
        echo "</div>";
    }
}

// Buscar referencias en el código
echo "<div class='warning'>";
echo "<h2>🔍 REFERENCIAS EN EL CÓDIGO</h2>";

$referencias_encontradas = [];

// Buscar en app/
$archivos_app = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base_dir . '/app'),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($archivos_app as $archivo) {
    if ($archivo->isFile() && preg_match('/\.(php|phtml|js)$/i', $archivo->getFilename())) {
        $contenido = @file_get_contents($archivo->getPathname());
        if ($contenido) {
            // Buscar referencias a imágenes
            if (preg_match_all('/(include\/images\/|admin\/publico\/img\/|publico\/img\/|publico\/images\/)([^\s"\'\)]+\.(png|jpg|jpeg|gif|svg))/i', $contenido, $matches)) {
                foreach ($matches[0] as $match) {
                    $ruta_relativa = str_replace($base_dir . '/', '', $archivo->getPathname());
                    if (!isset($referencias_encontradas[$match])) {
                        $referencias_encontradas[$match] = [];
                    }
                    $referencias_encontradas[$match][] = $ruta_relativa;
                }
            }
        }
    }
}

// Buscar en admin/app/
$archivos_admin = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base_dir . '/admin/app'),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($archivos_admin as $archivo) {
    if ($archivo->isFile() && preg_match('/\.(php|phtml|js)$/i', $archivo->getFilename())) {
        $contenido = @file_get_contents($archivo->getPathname());
        if ($contenido) {
            if (preg_match_all('/(include\/images\/|admin\/publico\/img\/|publico\/img\/|publico\/images\/)([^\s"\'\)]+\.(png|jpg|jpeg|gif|svg))/i', $contenido, $matches)) {
                foreach ($matches[0] as $match) {
                    $ruta_relativa = str_replace($base_dir . '/', '', $archivo->getPathname());
                    if (!isset($referencias_encontradas[$match])) {
                        $referencias_encontradas[$match] = [];
                    }
                    $referencias_encontradas[$match][] = $ruta_relativa;
                }
            }
        }
    }
}

echo "<table>";
echo "<tr><th>Ruta Referenciada</th><th>Archivos que la usan</th><th>Acción</th></tr>";

foreach ($referencias_encontradas as $ruta => $archivos) {
    $ruta_unificada = preg_replace('/(include\/images\/|admin\/publico\/img\/|publico\/img\/|publico\/images\/)/', 'admin/publico/img/', $ruta);
    $accion = $ruta !== $ruta_unificada ? "<span style='color: #ffc107;'>CORREGIR</span>" : "<span style='color: #28a745;'>OK</span>";
    
    echo "<tr>";
    echo "<td><code>{$ruta}</code></td>";
    echo "<td>" . count($archivos) . " archivo(s)</td>";
    echo "<td>{$accion} → <code>{$ruta_unificada}</code></td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

// Proponer limpieza
if (isset($_POST['unificar'])) {
    echo "<div class='warning'>";
    echo "<h2>🔄 UNIFICANDO RUTAS...</h2>";
    echo "</div>";
    
    $corregidos = 0;
    $errores = 0;
    
    // Corregir referencias en archivos
    $archivos_a_corregir = array_merge(
        glob($base_dir . '/app/**/*.{php,phtml,js}', GLOB_BRACE),
        glob($base_dir . '/admin/app/**/*.{php,phtml,js}', GLOB_BRACE)
    );
    
    foreach ($archivos_a_corregir as $archivo) {
        $contenido = @file_get_contents($archivo);
        if (!$contenido) continue;
        
        $contenido_original = $contenido;
        
        // Reemplazar rutas antiguas por la unificada
        $contenido = preg_replace(
            '/(["\'])(include\/images\/|publico\/img\/|publico\/images\/)([^"\']+\.(png|jpg|jpeg|gif|svg))/i',
            '$1admin/publico/img/$3',
            $contenido
        );
        
        // También corregir rutas con ruta variable
        $contenido = preg_replace(
            '/(\?=?\s*ruta\s*\?>?)(include\/images\/|publico\/img\/|publico\/images\/)([^\s"\'\)]+\.(png|jpg|jpeg|gif|svg))/i',
            '$1admin/publico/img/$3',
            $contenido
        );
        
        if ($contenido !== $contenido_original) {
            if (file_put_contents($archivo, $contenido)) {
                echo "<div class='success'>✓ Corregido: <code>" . str_replace($base_dir . '/', '', $archivo) . "</code></div>";
                $corregidos++;
            } else {
                echo "<div class='error'>✗ Error en: <code>" . str_replace($base_dir . '/', '', $archivo) . "</code></div>";
                $errores++;
            }
        }
    }
    
    echo "<div class='info'>";
    echo "<h3>📊 Resumen:</h3>";
    echo "<p><strong>Archivos corregidos:</strong> {$corregidos}</p>";
    echo "<p><strong>Errores:</strong> {$errores}</p>";
    echo "</div>";
    
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ ACCIÓN REQUERIDA</h2>";
    echo "<p>Este script unificará todas las rutas de imágenes a: <code>admin/publico/img/</code></p>";
    echo "<p><strong>Se corregirán:</strong></p>";
    echo "<ul>";
    echo "<li><code>include/images/</code> → <code>admin/publico/img/</code></li>";
    echo "<li><code>publico/img/</code> → <code>admin/publico/img/</code></li>";
    echo "<li><code>publico/images/</code> → <code>admin/publico/img/</code></li>";
    echo "</ul>";
    echo "<p><strong>Nota:</strong> Las imágenes físicas NO se moverán, solo se corregirán las rutas en el código.</p>";
    echo "<form method='POST'>";
    echo "<button type='submit' name='unificar' style='background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>🔄 Unificar Rutas de Imágenes</button>";
    echo "</form>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

