<?php
/**
 * Script para verificar que solo quedan los flujos activos (intranet y extranet)
 */

echo "<h1>✅ VERIFICACIÓN DE FLUJOS ACTIVOS</h1>";
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
</style>";

$base_dir = __DIR__;

// Incluir configuración para obtener ruta
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

echo "<div class='info'>";
echo "<h2>📋 FLUJOS ACTIVOS DEL SISTEMA</h2>";
echo "</div>";

// Verificar Flujo 1: EXTRANET (Clientes)
echo "<h3>1️⃣ FLUJO EXTRANET (Clientes)</h3>";
$extranet_ok = true;

$extranet_archivos = [
    'app/controlador/loginCon.php' => 'Controlador de login clientes',
    'app/controlador/ordenesCon.php' => 'Controlador de órdenes',
    'app/controlador/clienteCon.php' => 'Controlador de cliente',
    'app/controlador/salirCon.php' => 'Controlador de salir',
    'app/modelo/loginModelo.php' => 'Modelo de login',
    'app/modelo/inicioModelo.php' => 'Modelo de inicio',
    'app/vista/login.phtml' => 'Vista de login',
    'app/vista/ordenes_2.phtml' => 'Vista de órdenes',
    'app/vista/head_2.php' => 'Head del flujo clientes',
    'app/vista/header_2.php' => 'Header del flujo clientes',
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th></tr>";

foreach ($extranet_archivos as $archivo => $descripcion) {
    $ruta = $base_dir . '/' . $archivo;
    $existe = file_exists($ruta);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error-text'>✗ No existe</span>";
    if (!$existe) $extranet_ok = false;
    echo "<tr><td><code>{$archivo}</code></td><td>{$descripcion}</td><td>{$estado}</td></tr>";
}

echo "</table>";

if ($extranet_ok) {
    echo "<div class='success'>✓ Flujo EXTRANET (Clientes) está completo y funcional</div>";
} else {
    echo "<div class='error'>✗ Flujo EXTRANET (Clientes) tiene archivos faltantes</div>";
}

// Verificar Flujo 2: INTRANET (Administración)
echo "<h3>2️⃣ FLUJO INTRANET (Administración)</h3>";
$intranet_ok = true;

$intranet_archivos = [
    'admin/index.php' => 'Punto de entrada admin',
    'admin/app/controlador/loginCon.php' => 'Controlador de login admin',
    'admin/app/controlador/inicioCon.php' => 'Controlador de inicio admin',
    'admin/app/modelo/loginModelo.php' => 'Modelo de login admin',
    'admin/app/vista/login.phtml' => 'Vista de login admin (rediseñada)',
    'admin/app/vista/index.phtml' => 'Vista de dashboard admin',
    'admin/app/css/login-profesional.css' => 'Estilos del login admin',
    'admin/include/images/geofal.png' => 'Logo Geofal',
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th></tr>";

foreach ($intranet_archivos as $archivo => $descripcion) {
    $ruta = $base_dir . '/' . $archivo;
    $existe = file_exists($ruta);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error-text'>✗ No existe</span>";
    if (!$existe) $intranet_ok = false;
    echo "<tr><td><code>{$archivo}</code></td><td>{$descripcion}</td><td>{$estado}</td></tr>";
}

echo "</table>";

if ($intranet_ok) {
    echo "<div class='success'>✓ Flujo INTRANET (Administración) está completo y funcional</div>";
} else {
    echo "<div class='error'>✗ Flujo INTRANET (Administración) tiene archivos faltantes</div>";
}

// Verificar que la web antigua NO existe
echo "<h3>3️⃣ VERIFICACIÓN: Web Antigua Eliminada</h3>";

$web_antigua_eliminada = true;
$archivos_web_antigua = [
    'publico/' => 'Carpeta de páginas públicas',
    'app/vista/head.php' => 'Head de web antigua',
    'app/vista/header.php' => 'Header de web antigua',
    'app/vista/footer.php' => 'Footer de web antigua',
    'app/vista/carusel.php' => 'Carrusel de web antigua',
];

echo "<table>";
echo "<tr><th>Archivo/Carpeta</th><th>Estado</th></tr>";

foreach ($archivos_web_antigua as $archivo => $descripcion) {
    $ruta = $base_dir . '/' . $archivo;
    $existe = file_exists($ruta) || is_dir($ruta);
    $estado = $existe ? "<span class='error-text'>✗ Aún existe (debe eliminarse)</span>" : "<span class='ok'>✓ Eliminado</span>";
    if ($existe) $web_antigua_eliminada = false;
    echo "<tr><td><code>{$archivo}</code></td><td>{$estado}</td></tr>";
}

echo "</table>";

if ($web_antigua_eliminada) {
    echo "<div class='success'>✓ Web antigua completamente eliminada</div>";
} else {
    echo "<div class='warning'>⚠️ Algunos archivos de la web antigua aún existen</div>";
}

// Verificar index.php
echo "<h3>4️⃣ VERIFICACIÓN: index.php</h3>";
$index_content = file_get_contents($base_dir . '/index.php');

if (strpos($index_content, 'publico/controlador') !== false) {
    echo "<div class='error'>✗ <code>index.php</code> aún busca en <code>publico/controlador/</code></div>";
} else {
    echo "<div class='success'>✓ <code>index.php</code> solo busca en <code>app/controlador/</code> (correcto)</div>";
}

// Resumen final
echo "<div class='info'>";
echo "<h2>📊 RESUMEN FINAL</h2>";
echo "<table>";
echo "<tr><th>Flujo</th><th>URL</th><th>Estado</th></tr>";
echo "<tr><td><strong>EXTRANET (Clientes)</strong></td><td><code>" . htmlspecialchars(ruta) . "</code></td><td>" . ($extranet_ok ? "<span class='ok'>✓ Activo</span>" : "<span class='error-text'>✗ Con errores</span>") . "</td></tr>";
echo "<tr><td><strong>INTRANET (Admin)</strong></td><td><code>" . htmlspecialchars(ruta) . "admin/</code></td><td>" . ($intranet_ok ? "<span class='ok'>✓ Activo</span>" : "<span class='error-text'>✗ Con errores</span>") . "</td></tr>";
echo "<tr><td><strong>Web Antigua</strong></td><td>-</td><td>" . ($web_antigua_eliminada ? "<span class='ok'>✓ Eliminada</span>" : "<span class='error-text'>✗ Aún existe</span>") . "</td></tr>";
echo "</table>";
echo "</div>";

if ($extranet_ok && $intranet_ok && $web_antigua_eliminada) {
    echo "<div class='success'>";
    echo "<h2>✅ SISTEMA LIMPIO Y FUNCIONAL</h2>";
    echo "<p>El sistema ahora solo maneja los <strong>2 flujos activos</strong>:</p>";
    echo "<ol>";
    echo "<li><strong>EXTRANET</strong> - Clientes ven sus documentos (RUC + Clave)</li>";
    echo "<li><strong>INTRANET</strong> - Administradores gestionan todo (DNI + Clave)</li>";
    echo "</ol>";
    echo "<p>La web antigua ha sido completamente eliminada.</p>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h2>⚠️ REVISAR CONFIGURACIÓN</h2>";
    echo "<p>Algunos elementos necesitan atención. Revisa los detalles arriba.</p>";
    echo "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

