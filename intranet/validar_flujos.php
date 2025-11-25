<?php
/**
 * Script para validar todos los flujos de usuarios del sistema
 * Ejecutar desde: http://localhost/public_html/intranet/validar_flujos.php
 */

echo "<h1>🔍 VALIDACIÓN DE FLUJOS DE USUARIOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .ok { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
</style>";

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}
require_once("config/sistema.php");

$resultados = [];

// ============================================
// FLUJO 1: EXTRANET CLIENTES (intranet/app/)
// ============================================
echo "<div class='section'>";
echo "<h2>1️⃣ EXTRANET CLIENTES (intranet/app/)</h2>";

$flujo1 = [
    'nombre' => 'Extranet Clientes',
    'url_base' => ruta,
    'url_login' => ruta . '?pagina=login',
    'url_ordenes' => ruta . 'ordenes',
    'archivos_clave' => [
        'index.php' => file_exists('index.php'),
        'app/controlador/loginCon.php' => file_exists('app/controlador/loginCon.php'),
        'app/modelo/loginModelo.php' => file_exists('app/modelo/loginModelo.php'),
        'app/vista/login.phtml' => file_exists('app/vista/login.phtml'),
        'app/controlador/ordenesCon.php' => file_exists('app/controlador/ordenesCon.php'),
        'app/vista/ordenes_2.phtml' => file_exists('app/vista/ordenes_2.phtml'),
    ],
    'tabla_bd' => 'clientes',
    'campo_usuario' => 'ruc',
    'campo_clave' => 'clave',
    'sesion' => 'id_geo',
    'estado' => 'PENDIENTE'
];

// Verificar tabla en BD
try {
    $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8", USER, PASSWORD);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conexion->query("SHOW TABLES LIKE 'clientes'");
    $flujo1['tabla_existe'] = $stmt->rowCount() > 0;
    
    if ($flujo1['tabla_existe']) {
        $stmt = $conexion->query("SELECT COUNT(*) as total FROM clientes");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $flujo1['usuarios_totales'] = $result['total'];
    }
} catch (PDOException $e) {
    $flujo1['error_bd'] = $e->getMessage();
}

$todos_archivos_ok = true;
foreach ($flujo1['archivos_clave'] as $archivo => $existe) {
    if (!$existe) $todos_archivos_ok = false;
}

$flujo1['estado'] = ($todos_archivos_ok && isset($flujo1['tabla_existe']) && $flujo1['tabla_existe']) ? 'OK' : 'ERROR';

echo "<table>";
echo "<tr><th>Elemento</th><th>Estado</th><th>Detalles</th></tr>";
echo "<tr><td>Archivos principales</td><td class='" . ($todos_archivos_ok ? 'ok' : 'error') . "'>" . ($todos_archivos_ok ? '✓' : '✗') . "</td><td>";
foreach ($flujo1['archivos_clave'] as $archivo => $existe) {
    echo ($existe ? '✓' : '✗') . " $archivo<br>";
}
echo "</td></tr>";
echo "<tr><td>Tabla BD (clientes)</td><td class='" . (isset($flujo1['tabla_existe']) && $flujo1['tabla_existe'] ? 'ok' : 'error') . "'>" . (isset($flujo1['tabla_existe']) && $flujo1['tabla_existe'] ? '✓' : '✗') . "</td><td>";
if (isset($flujo1['tabla_existe']) && $flujo1['tabla_existe']) {
    echo "Tabla existe. Usuarios: " . (isset($flujo1['usuarios_totales']) ? $flujo1['usuarios_totales'] : 'N/A');
} else {
    echo "Tabla no encontrada o error: " . (isset($flujo1['error_bd']) ? $flujo1['error_bd'] : 'N/A');
}
echo "</td></tr>";
echo "<tr><td>URL Login</td><td>-</td><td><a href='" . $flujo1['url_login'] . "' target='_blank'>" . $flujo1['url_login'] . "</a></td></tr>";
echo "<tr><td>Estado General</td><td class='" . ($flujo1['estado'] == 'OK' ? 'ok' : 'error') . "'><strong>" . $flujo1['estado'] . "</strong></td><td>-</td></tr>";
echo "</table>";
echo "</div>";

$resultados[] = $flujo1;

// ============================================
// FLUJO 2: PANEL ADMIN (intranet/admin/)
// ============================================
echo "<div class='section'>";
echo "<h2>2️⃣ PANEL ADMINISTRACIÓN (intranet/admin/)</h2>";

$flujo2 = [
    'nombre' => 'Panel Admin',
    'url_base' => ruta . 'admin/',
    'url_login' => ruta . 'admin/login',
    'url_inicio' => ruta . 'admin/inicio',
    'archivos_clave' => [
        'admin/index.php' => file_exists('admin/index.php'),
        'admin/app/controlador/loginCon.php' => file_exists('admin/app/controlador/loginCon.php'),
        'admin/app/modelo/loginModelo.php' => file_exists('admin/app/modelo/loginModelo.php'),
        'admin/app/vista/login.phtml' => file_exists('admin/app/vista/login.phtml'),
        'admin/app/controlador/inicioCon.php' => file_exists('admin/app/controlador/inicioCon.php'),
    ],
    'tabla_bd' => 'usuarios',
    'campo_usuario' => 'dni',
    'campo_clave' => 'clave',
    'sesion' => 'id_geofal',
    'estado' => 'PENDIENTE'
];

// Verificar tabla en BD
try {
    $stmt = $conexion->query("SHOW TABLES LIKE 'usuarios'");
    $flujo2['tabla_existe'] = $stmt->rowCount() > 0;
    
    if ($flujo2['tabla_existe']) {
        $stmt = $conexion->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $flujo2['usuarios_totales'] = $result['total'];
    }
} catch (PDOException $e) {
    $flujo2['error_bd'] = $e->getMessage();
}

$todos_archivos_ok2 = true;
foreach ($flujo2['archivos_clave'] as $archivo => $existe) {
    if (!$existe) $todos_archivos_ok2 = false;
}

$flujo2['estado'] = ($todos_archivos_ok2 && isset($flujo2['tabla_existe']) && $flujo2['tabla_existe']) ? 'OK' : 'ERROR';

echo "<table>";
echo "<tr><th>Elemento</th><th>Estado</th><th>Detalles</th></tr>";
echo "<tr><td>Archivos principales</td><td class='" . ($todos_archivos_ok2 ? 'ok' : 'error') . "'>" . ($todos_archivos_ok2 ? '✓' : '✗') . "</td><td>";
foreach ($flujo2['archivos_clave'] as $archivo => $existe) {
    echo ($existe ? '✓' : '✗') . " $archivo<br>";
}
echo "</td></tr>";
echo "<tr><td>Tabla BD (usuarios)</td><td class='" . (isset($flujo2['tabla_existe']) && $flujo2['tabla_existe'] ? 'ok' : 'error') . "'>" . (isset($flujo2['tabla_existe']) && $flujo2['tabla_existe'] ? '✓' : '✗') . "</td><td>";
if (isset($flujo2['tabla_existe']) && $flujo2['tabla_existe']) {
    echo "Tabla existe. Usuarios: " . (isset($flujo2['usuarios_totales']) ? $flujo2['usuarios_totales'] : 'N/A');
} else {
    echo "Tabla no encontrada o error: " . (isset($flujo2['error_bd']) ? $flujo2['error_bd'] : 'N/A');
}
echo "</td></tr>";
echo "<tr><td>URL Login</td><td>-</td><td><a href='" . $flujo2['url_login'] . "' target='_blank'>" . $flujo2['url_login'] . "</a></td></tr>";
echo "<tr><td>Estado General</td><td class='" . ($flujo2['estado'] == 'OK' ? 'ok' : 'error') . "'><strong>" . $flujo2['estado'] . "</strong></td><td>-</td></tr>";
echo "</table>";
echo "</div>";

$resultados[] = $flujo2;

// ============================================
// FLUJO 3: PHPMaker (intranet/admin2/)
// ============================================
echo "<div class='section'>";
echo "<h2>3️⃣ PHPMaker (intranet/admin2/) - ⚠️ POSIBLE DUPLICADO</h2>";

$flujo3 = [
    'nombre' => 'PHPMaker Admin2',
    'url_base' => ruta . 'admin2/',
    'url_login' => ruta . 'admin2/login.php',
    'archivos_clave' => [
        'admin2/index.php' => file_exists('admin2/index.php'),
        'admin2/login.php' => file_exists('admin2/login.php'),
        'admin2/clienteslist.php' => file_exists('admin2/clienteslist.php'),
        'admin2/pdflist.php' => file_exists('admin2/pdflist.php'),
    ],
    'estado' => 'PENDIENTE'
];

$todos_archivos_ok3 = true;
foreach ($flujo3['archivos_clave'] as $archivo => $existe) {
    if (!$existe) $todos_archivos_ok3 = false;
}

$flujo3['estado'] = $todos_archivos_ok3 ? 'OK (DUPLICADO?)' : 'ERROR';

echo "<table>";
echo "<tr><th>Elemento</th><th>Estado</th><th>Detalles</th></tr>";
echo "<tr><td>Archivos principales</td><td class='" . ($todos_archivos_ok3 ? 'warning' : 'error') . "'>" . ($todos_archivos_ok3 ? '✓' : '✗') . "</td><td>";
foreach ($flujo3['archivos_clave'] as $archivo => $existe) {
    echo ($existe ? '✓' : '✗') . " $archivo<br>";
}
echo "</td></tr>";
echo "<tr><td>Observación</td><td class='warning'>⚠️</td><td>Este sistema parece duplicar funcionalidades de admin/. Verificar si se usa en producción.</td></tr>";
echo "<tr><td>URL Login</td><td>-</td><td><a href='" . $flujo3['url_login'] . "' target='_blank'>" . $flujo3['url_login'] . "</a></td></tr>";
echo "<tr><td>Estado General</td><td class='warning'><strong>" . $flujo3['estado'] . "</strong></td><td>-</td></tr>";
echo "</table>";
echo "</div>";

$resultados[] = $flujo3;

// ============================================
// FLUJO 4: intranet2/ (DUPLICADO COMPLETO)
// ============================================
echo "<div class='section'>";
echo "<h2>4️⃣ intranet2/ - ⚠️ DUPLICADO COMPLETO</h2>";

$flujo4 = [
    'nombre' => 'Intranet2 (Duplicado)',
    'url_base' => ruta . 'intranet2/',
    'archivos_clave' => [
        'intranet2/index.php' => file_exists('intranet2/index.php'),
        'intranet2/admin/index.php' => file_exists('intranet2/admin/index.php'),
        'intranet2/admin2/index.php' => file_exists('intranet2/admin2/index.php'),
    ],
    'estado' => 'DUPLICADO'
];

$todos_archivos_ok4 = true;
foreach ($flujo4['archivos_clave'] as $archivo => $existe) {
    if (!$existe) $todos_archivos_ok4 = false;
}

echo "<table>";
echo "<tr><th>Elemento</th><th>Estado</th><th>Detalles</th></tr>";
echo "<tr><td>Archivos principales</td><td class='warning'>⚠️</td><td>";
foreach ($flujo4['archivos_clave'] as $archivo => $existe) {
    echo ($existe ? '✓' : '✗') . " $archivo<br>";
}
echo "</td></tr>";
echo "<tr><td>Observación</td><td class='error'>❌</td><td><strong>Esta carpeta parece ser una copia completa de intranet/. Recomendación: ELIMINAR si no se usa.</strong></td></tr>";
echo "<tr><td>Estado General</td><td class='error'><strong>DUPLICADO - ELIMINAR</strong></td><td>-</td></tr>";
echo "</table>";
echo "</div>";

$resultados[] = $flujo4;

// ============================================
// RESUMEN FINAL
// ============================================
echo "<div class='section' style='background-color: #f0f0f0;'>";
echo "<h2>📊 RESUMEN Y RECOMENDACIONES</h2>";

echo "<table>";
echo "<tr><th>Sistema</th><th>Estado</th><th>Acción Recomendada</th></tr>";
foreach ($resultados as $flujo) {
    $color = ($flujo['estado'] == 'OK') ? 'ok' : (strpos($flujo['estado'], 'DUPLICADO') !== false ? 'error' : 'warning');
    $accion = ($flujo['estado'] == 'OK') ? 'MANTENER' : (strpos($flujo['estado'], 'DUPLICADO') !== false ? 'ELIMINAR' : 'REVISAR');
    echo "<tr>";
    echo "<td><strong>" . $flujo['nombre'] . "</strong></td>";
    echo "<td class='$color'><strong>" . $flujo['estado'] . "</strong></td>";
    echo "<td><strong>" . $accion . "</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>🎯 Plan de Acción:</h3>";
echo "<ol>";
echo "<li><strong>Validar Flujo 1 (Extranet Clientes):</strong> Probar login con RUC + clave → Verificar acceso a órdenes</li>";
echo "<li><strong>Validar Flujo 2 (Panel Admin):</strong> Probar login con DNI + clave → Verificar acceso a inicio</li>";
echo "<li><strong>Revisar Flujo 3 (admin2/):</strong> Confirmar si se usa en producción. Si no, ELIMINAR</li>";
echo "<li><strong>Eliminar Flujo 4 (intranet2/):</strong> Es un duplicado completo. ELIMINAR</li>";
echo "</ol>";

echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (validar_flujos.php) después de usarlo por seguridad.</p>";
?>

