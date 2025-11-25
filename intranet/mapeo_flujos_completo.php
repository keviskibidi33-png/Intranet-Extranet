<?php
/**
 * MAPEO COMPLETO DE FLUJOS DE USUARIOS
 * Este script mapea y valida todos los flujos del sistema
 */

echo "<h1>🗺️ MAPEO COMPLETO DE FLUJOS DE USUARIOS</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .flujo { background: white; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
    .flujo h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    .ok { color: #28a745; font-weight: bold; }
    .error { color: #dc3545; font-weight: bold; }
    .warning { color: #ffc107; font-weight: bold; }
    .info-box { background: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .step { background: #f8f9fa; padding: 10px; margin: 5px 0; border-left: 3px solid #007bff; }
    .url { background: #e9ecef; padding: 5px; font-family: monospace; }
</style>";

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}
require_once("config/sistema.php");

// ============================================
// FLUJO 1: EXTRANET CLIENTES
// ============================================
echo "<div class='flujo'>";
echo "<h2>1️⃣ FLUJO: EXTRANET CLIENTES (intranet/app/)</h2>";

$flujo1 = [
    'nombre' => 'Extranet Clientes',
    'descripcion' => 'Sistema para que los clientes accedan a sus documentos PDF',
    'usuario_tipo' => 'Clientes',
    'url_base' => ruta,
    'url_login' => ruta . '?pagina=login',
    'url_ordenes' => ruta . 'ordenes',
    'tabla_bd' => 'clientes',
    'campo_usuario' => 'ruc',
    'campo_clave' => 'clave',
    'sesion' => 'id_geo',
    'cookie' => null,
    'archivos' => [
        'index.php' => ['ruta' => 'index.php', 'descripcion' => 'Punto de entrada principal'],
        'loginCon.php' => ['ruta' => 'app/controlador/loginCon.php', 'descripcion' => 'Controlador de login'],
        'loginModelo.php' => ['ruta' => 'app/modelo/loginModelo.php', 'descripcion' => 'Modelo de login'],
        'login.phtml' => ['ruta' => 'app/vista/login.phtml', 'descripcion' => 'Vista de login'],
        'ordenesCon.php' => ['ruta' => 'app/controlador/ordenesCon.php', 'descripcion' => 'Controlador de órdenes'],
        'ordenes_2.phtml' => ['ruta' => 'app/vista/ordenes_2.phtml', 'descripcion' => 'Vista de órdenes/documentos'],
    ],
    'funcionalidades' => [
        'Ver documentos PDF asociados',
        'Marcar documentos como "visto"',
        'Ver estado de documentos (APROBADO/OBSERVADO)',
        'Recibir notificaciones por email'
    ]
];

echo "<div class='info-box'>";
echo "<h3>📋 Información General</h3>";
echo "<p><strong>Descripción:</strong> {$flujo1['descripcion']}</p>";
echo "<p><strong>Tipo de Usuario:</strong> {$flujo1['usuario_tipo']}</p>";
echo "<p><strong>URL Base:</strong> <span class='url'>{$flujo1['url_base']}</span></p>";
echo "</div>";

echo "<h3>🔐 Autenticación</h3>";
echo "<table>";
echo "<tr><th>Elemento</th><th>Valor</th></tr>";
echo "<tr><td>Tabla BD</td><td>{$flujo1['tabla_bd']}</td></tr>";
echo "<tr><td>Campo Usuario</td><td>{$flujo1['campo_usuario']}</td></tr>";
echo "<tr><td>Campo Clave</td><td>{$flujo1['campo_clave']}</td></tr>";
echo "<tr><td>Variable Sesión</td><td>\$_SESSION['{$flujo1['sesion']}']</td></tr>";
echo "<tr><td>Cookie</td><td>" . ($flujo1['cookie'] ?: 'No usa cookie') . "</td></tr>";
echo "</table>";

echo "<h3>🛣️ Rutas del Flujo</h3>";
echo "<div class='step'>";
echo "<strong>Paso 1 - Acceso:</strong> Usuario accede a <span class='url'>{$flujo1['url_base']}</span> o <span class='url'>{$flujo1['url_base']}?pagina=cliente</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 2 - Login:</strong> Usuario ingresa RUC y clave en <span class='url'>{$flujo1['url_login']}</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 3 - Validación:</strong> Sistema valida en tabla <code>{$flujo1['tabla_bd']}</code> con campo <code>{$flujo1['campo_usuario']}</code>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 4 - Sesión:</strong> Si es válido, se establece <code>\$_SESSION['{$flujo1['sesion']}']</code>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 5 - Redirección:</strong> Usuario es redirigido a <span class='url'>{$flujo1['url_ordenes']}</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 6 - Dashboard:</strong> Usuario ve sus documentos PDF en <code>ordenes_2.phtml</code>";
echo "</div>";

echo "<h3>📁 Archivos Clave</h3>";
echo "<table>";
echo "<tr><th>Archivo</th><th>Ruta</th><th>Descripción</th><th>Estado</th></tr>";
foreach ($flujo1['archivos'] as $key => $archivo) {
    $existe = file_exists($archivo['ruta']);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error'>✗ No existe</span>";
    echo "<tr>";
    echo "<td><strong>{$key}</strong></td>";
    echo "<td><code>{$archivo['ruta']}</code></td>";
    echo "<td>{$archivo['descripcion']}</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>⚙️ Funcionalidades</h3>";
echo "<ul>";
foreach ($flujo1['funcionalidades'] as $func) {
    echo "<li>{$func}</li>";
}
echo "</ul>";

echo "</div>";

// ============================================
// FLUJO 2: PANEL ADMINISTRACIÓN
// ============================================
echo "<div class='flujo'>";
echo "<h2>2️⃣ FLUJO: PANEL ADMINISTRACIÓN (intranet/admin/)</h2>";

$flujo2 = [
    'nombre' => 'Panel Administración',
    'descripcion' => 'Sistema para que vendedores/administradores gestionen clientes, PDFs y usuarios',
    'usuario_tipo' => 'Vendedores/Administradores',
    'url_base' => ruta . 'admin/',
    'url_login' => ruta . 'admin/login',
    'url_inicio' => ruta . 'admin/inicio',
    'tabla_bd' => 'usuarios',
    'campo_usuario' => 'dni',
    'campo_clave' => 'clave',
    'sesion' => 'id_geofal',
    'cookie' => 'id_geofal',
    'archivos' => [
        'index.php' => ['ruta' => 'admin/index.php', 'descripcion' => 'Punto de entrada admin'],
        'loginCon.php' => ['ruta' => 'admin/app/controlador/loginCon.php', 'descripcion' => 'Controlador de login admin'],
        'loginModelo.php' => ['ruta' => 'admin/app/modelo/loginModelo.php', 'descripcion' => 'Modelo de login admin'],
        'login.phtml' => ['ruta' => 'admin/app/vista/login.phtml', 'descripcion' => 'Vista de login admin'],
        'inicioCon.php' => ['ruta' => 'admin/app/controlador/inicioCon.php', 'descripcion' => 'Controlador de inicio/dashboard'],
    ],
    'funcionalidades' => [
        'Gestionar clientes (CRUD completo)',
        'Subir/editar/eliminar PDFs',
        'Gestionar usuarios/vendedores',
        'Ver todas las órdenes',
        'Cambiar estados de documentos',
        'Enviar notificaciones por email'
    ]
];

echo "<div class='info-box'>";
echo "<h3>📋 Información General</h3>";
echo "<p><strong>Descripción:</strong> {$flujo2['descripcion']}</p>";
echo "<p><strong>Tipo de Usuario:</strong> {$flujo2['usuario_tipo']}</p>";
echo "<p><strong>URL Base:</strong> <span class='url'>{$flujo2['url_base']}</span></p>";
echo "</div>";

echo "<h3>🔐 Autenticación</h3>";
echo "<table>";
echo "<tr><th>Elemento</th><th>Valor</th></tr>";
echo "<tr><td>Tabla BD</td><td>{$flujo2['tabla_bd']}</td></tr>";
echo "<tr><td>Campo Usuario</td><td>{$flujo2['campo_usuario']}</td></tr>";
echo "<tr><td>Campo Clave</td><td>{$flujo2['campo_clave']}</td></tr>";
echo "<tr><td>Variable Sesión</td><td>\$_SESSION['{$flujo2['sesion']}']</td></tr>";
echo "<tr><td>Cookie</td><td>\$_COOKIE['{$flujo2['cookie']}'] (200 días)</td></tr>";
echo "</table>";

echo "<h3>🛣️ Rutas del Flujo</h3>";
echo "<div class='step'>";
echo "<strong>Paso 1 - Acceso:</strong> Usuario accede a <span class='url'>{$flujo2['url_base']}</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 2 - Login:</strong> Usuario ingresa DNI y clave en <span class='url'>{$flujo2['url_login']}</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 3 - Validación:</strong> Sistema valida en tabla <code>{$flujo2['tabla_bd']}</code> con campo <code>{$flujo2['campo_usuario']}</code>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 4 - Sesión y Cookie:</strong> Si es válido, se establece <code>\$_SESSION['{$flujo2['sesion']}']</code> y cookie <code>{$flujo2['cookie']}</code>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 5 - Redirección:</strong> Usuario es redirigido a <span class='url'>{$flujo2['url_inicio']}</span>";
echo "</div>";
echo "<div class='step'>";
echo "<strong>Paso 6 - Dashboard:</strong> Usuario accede al panel de administración completo";
echo "</div>";

echo "<h3>📁 Archivos Clave</h3>";
echo "<table>";
echo "<tr><th>Archivo</th><th>Ruta</th><th>Descripción</th><th>Estado</th></tr>";
foreach ($flujo2['archivos'] as $key => $archivo) {
    $existe = file_exists($archivo['ruta']);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error'>✗ No existe</span>";
    echo "<tr>";
    echo "<td><strong>{$key}</strong></td>";
    echo "<td><code>{$archivo['ruta']}</code></td>";
    echo "<td>{$archivo['descripcion']}</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>⚙️ Funcionalidades</h3>";
echo "<ul>";
foreach ($flujo2['funcionalidades'] as $func) {
    echo "<li>{$func}</li>";
}
echo "</ul>";

echo "</div>";

// ============================================
// RESUMEN Y COMPARACIÓN
// ============================================
echo "<div class='flujo' style='background: #fff3cd; border-color: #ffc107;'>";
echo "<h2>📊 RESUMEN Y COMPARACIÓN DE FLUJOS</h2>";

echo "<table>";
echo "<tr><th>Característica</th><th>Extranet Clientes</th><th>Panel Admin</th></tr>";
echo "<tr><td><strong>URL Base</strong></td><td>{$flujo1['url_base']}</td><td>{$flujo2['url_base']}</td></tr>";
echo "<tr><td><strong>Tabla BD</strong></td><td>{$flujo1['tabla_bd']}</td><td>{$flujo2['tabla_bd']}</td></tr>";
echo "<tr><td><strong>Campo Usuario</strong></td><td>{$flujo1['campo_usuario']}</td><td>{$flujo2['campo_usuario']}</td></tr>";
echo "<tr><td><strong>Sesión</strong></td><td>\$_SESSION['{$flujo1['sesion']}']</td><td>\$_SESSION['{$flujo2['sesion']}']</td></tr>";
echo "<tr><td><strong>Cookie</strong></td><td>No</td><td>Sí ({$flujo2['cookie']})</td></tr>";
echo "<tr><td><strong>Redirección Post-Login</strong></td><td>{$flujo1['url_ordenes']}</td><td>{$flujo2['url_inicio']}</td></tr>";
echo "</table>";

echo "<h3>✅ Flujos Válidos y Activos</h3>";
echo "<ul>";
echo "<li><span class='ok'>✓ Flujo 1 (Extranet Clientes):</span> Sistema principal para clientes - <strong>MANTENER</strong></li>";
echo "<li><span class='ok'>✓ Flujo 2 (Panel Admin):</span> Sistema principal para administradores - <strong>MANTENER</strong></li>";
echo "</ul>";

echo "<h3>❌ Flujos Duplicados/Obsoletos</h3>";
echo "<ul>";
echo "<li><span class='error'>✗ admin2/:</span> PHPMaker obsoleto con errores - <strong>ELIMINAR</strong></li>";
echo "<li><span class='error'>✗ intranet2/:</span> Copia completa duplicada - <strong>ELIMINAR</strong></li>";
echo "</ul>";

echo "</div>";

echo "<div style='background: #d4edda; border: 2px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
echo "<h2>🎯 Próximos Pasos</h2>";
echo "<ol>";
echo "<li><strong>Validar Flujo 1:</strong> Probar login cliente con RUC + clave → Verificar acceso a órdenes</li>";
echo "<li><strong>Validar Flujo 2:</strong> Probar login admin con DNI + clave → Verificar acceso a inicio</li>";
echo "<li><strong>Eliminar Duplicados:</strong> Ejecutar <code>eliminar_duplicados.php</code> para limpiar admin2/ e intranet2/</li>";
echo "<li><strong>Optimizar:</strong> Mejorar rendimiento de admin/login (último paso)</li>";
echo "</ol>";
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (mapeo_flujos_completo.php) después de usarlo por seguridad.</p>";
?>

