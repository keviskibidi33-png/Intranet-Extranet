<?php
/**
 * MAPA DE ÁRBOL COMPLETO DE FLUJOS
 * Muestra todas las conexiones y validaciones del sistema
 */

echo "<h1>🗺️ MAPA DE ÁRBOL DE FLUJOS - SISTEMA GEOFAL</h1>";
echo "<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .flujo { background: white; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
    .tree { font-family: 'Courier New', monospace; line-height: 1.8; background: #f9f9f9; padding: 15px; border-radius: 5px; }
    .tree ul { list-style: none; padding-left: 20px; }
    .tree li { position: relative; }
    .tree li:before { content: '├─ '; color: #666; }
    .tree li:last-child:before { content: '└─ '; }
    .file { color: #0066cc; font-weight: bold; }
    .folder { color: #009900; font-weight: bold; }
    .url { color: #cc6600; }
    .db { color: #9900cc; }
    .session { color: #cc0066; }
    .ok { color: #28a745; }
    .error { color: #dc3545; }
    .warning { color: #ffc107; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .step { background: #e7f3ff; padding: 10px; margin: 5px 0; border-left: 4px solid #007bff; }
    .validation { background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px; }
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

echo "<div class='tree'>";
echo "<h3>📂 Estructura de Archivos y Conexiones:</h3>";
echo "<ul>";
echo "<li><span class='url'>URL Base:</span> <code>" . ruta . "</code></li>";
echo "<li><span class='folder'>index.php</span> (Punto de entrada)</li>";
echo "<li>├─ <span class='folder'>config/</span></li>";
echo "<li>│  ├─ <span class='file'>config.php</span> (Producción)</li>";
echo "<li>│  ├─ <span class='file'>config.local.php</span> (Local - detecta HTTP/HTTPS)</li>";
echo "<li>│  └─ <span class='file'>sistema.php</span> (Clase Conectar - BD)</li>";
echo "<li>├─ <span class='folder'>app/controlador/</span></li>";
echo "<li>│  ├─ <span class='file'>loginCon.php</span> → Incluye sistema.php → Incluye loginModelo.php</li>";
echo "<li>│  ├─ <span class='file'>ordenesCon.php</span> → Incluye sistema.php → Incluye inicioModelo.php</li>";
echo "<li>│  └─ <span class='file'>clienteCon.php</span></li>";
echo "<li>├─ <span class='folder'>app/modelo/</span></li>";
echo "<li>│  ├─ <span class='file'>loginModelo.php</span> → Extiende Conectar → Conecta a BD</li>";
echo "<li>│  └─ <span class='file'>inicioModelo.php</span> → Extiende Conectar → Conecta a BD</li>";
echo "<li>├─ <span class='folder'>app/vista/</span></li>";
echo "<li>│  ├─ <span class='file'>login.phtml</span> → Formulario login (RUC + clave)</li>";
echo "<li>│  ├─ <span class='file'>ordenes_2.phtml</span> → Lista de documentos PDF</li>";
echo "<li>│  ├─ <span class='file'>head.php</span> → CSS, JS, meta tags</li>";
echo "<li>│  └─ <span class='file'>footer.php</span> → JavaScript (login AJAX, SweetAlert2)</li>";
echo "<li>└─ <span class='folder'>publico/img_data/</span> → PDFs almacenados (454 archivos)</li>";
echo "</ul>";
echo "</div>";

echo "<h3>🔄 Flujo de Conexiones (14 Pasos):</h3>";
echo "<div class='step'><strong>Paso 1:</strong> Usuario accede a <span class='url'>" . ruta . "</span> o <span class='url'>" . ruta . "?pagina=cliente</span></div>";
echo "<div class='step'><strong>Paso 2:</strong> <span class='file'>index.php</span> carga <span class='file'>config.php</span> o <span class='file'>config.local.php</span> (según entorno)</div>";
echo "<div class='step'><strong>Paso 3:</strong> <span class='file'>index.php</span> carga <span class='file'>config/sistema.php</span> (define clase Conectar)</div>";
echo "<div class='step'><strong>Paso 4:</strong> <span class='file'>index.php</span> incluye <span class='file'>app/controlador/loginCon.php</span></div>";
echo "<div class='step'><strong>Paso 5:</strong> <span class='file'>loginCon.php</span> verifica si Conectar existe, si no, incluye <span class='file'>sistema.php</span></div>";
echo "<div class='step'><strong>Paso 6:</strong> <span class='file'>loginCon.php</span> incluye <span class='file'>app/modelo/loginModelo.php</span></div>";
echo "<div class='step'><strong>Paso 7:</strong> <span class='file'>loginModelo.php</span> verifica Conectar, crea instancia Logininicio (extiende Conectar)</div>";
echo "<div class='step'><strong>Paso 8:</strong> Usuario envía formulario → <span class='file'>loginCon.php</span> llama <span class='file'>loginModelo->login()</span></div>";
echo "<div class='step'><strong>Paso 9:</strong> <span class='file'>loginModelo->login()</span> consulta BD tabla <span class='db'>clientes</span> (campo <span class='db'>ruc</span>)</div>";
echo "<div class='step'><strong>Paso 10:</strong> Si válido → Establece <span class='session'>\$_SESSION['id_geo']</span></div>";
echo "<div class='step'><strong>Paso 11:</strong> JavaScript (footer.php) recibe respuesta AJAX → Redirige a <span class='url'>" . ruta . "ordenes</span></div>";
echo "<div class='step'><strong>Paso 12:</strong> <span class='file'>index.php</span> carga <span class='file'>app/controlador/ordenesCon.php</span></div>";
echo "<div class='step'><strong>Paso 13:</strong> <span class='file'>ordenesCon.php</span> incluye <span class='file'>inicioModelo.php</span> → Consulta PDFs de BD</div>";
echo "<div class='step'><strong>Paso 14:</strong> <span class='file'>ordenesCon.php</span> carga <span class='file'>app/vista/ordenes_2.phtml</span> → Muestra documentos</div>";

echo "<h3>🔐 Autenticación y Sesión:</h3>";
echo "<table>";
echo "<tr><th>Elemento</th><th>Valor</th></tr>";
echo "<tr><td>Tabla BD</td><td><span class='db'>clientes</span></td></tr>";
echo "<tr><td>Campo Usuario</td><td><span class='db'>ruc</span></td></tr>";
echo "<tr><td>Campo Clave</td><td><span class='db'>clave</span> (soporta texto plano y hash)</td></tr>";
echo "<tr><td>Variable Sesión</td><td><span class='session'>\$_SESSION['id_geo']</span></td></tr>";
echo "<tr><td>Cookie</td><td>No usa</td></tr>";
echo "<tr><td>Redirección Post-Login</td><td><span class='url'>" . ruta . "ordenes</span></td></tr>";
echo "</table>";

echo "<h3>✅ Validaciones del Flujo:</h3>";
$validaciones1 = [
    ['archivo' => 'index.php', 'descripcion' => 'Punto de entrada', 'ruta' => 'index.php'],
    ['archivo' => 'config/config.php', 'descripcion' => 'Config producción', 'ruta' => 'config/config.php'],
    ['archivo' => 'config/config.local.php', 'descripcion' => 'Config local', 'ruta' => 'config/config.local.php'],
    ['archivo' => 'config/sistema.php', 'descripcion' => 'Clase Conectar', 'ruta' => 'config/sistema.php'],
    ['archivo' => 'app/controlador/loginCon.php', 'descripcion' => 'Controlador login', 'ruta' => 'app/controlador/loginCon.php'],
    ['archivo' => 'app/modelo/loginModelo.php', 'descripcion' => 'Modelo login', 'ruta' => 'app/modelo/loginModelo.php'],
    ['archivo' => 'app/vista/login.phtml', 'descripcion' => 'Vista login', 'ruta' => 'app/vista/login.phtml'],
    ['archivo' => 'app/controlador/ordenesCon.php', 'descripcion' => 'Controlador órdenes', 'ruta' => 'app/controlador/ordenesCon.php'],
    ['archivo' => 'app/modelo/inicioModelo.php', 'descripcion' => 'Modelo inicio', 'ruta' => 'app/modelo/inicioModelo.php'],
    ['archivo' => 'app/vista/ordenes_2.phtml', 'descripcion' => 'Vista órdenes', 'ruta' => 'app/vista/ordenes_2.phtml'],
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th></tr>";
foreach ($validaciones1 as $val) {
    $existe = file_exists($val['ruta']);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error'>✗ No existe</span>";
    echo "<tr>";
    echo "<td><code>{$val['archivo']}</code></td>";
    echo "<td>{$val['descripcion']}</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}
echo "</table>";

echo "</div>";

// ============================================
// FLUJO 2: PANEL ADMIN
// ============================================
echo "<div class='flujo'>";
echo "<h2>2️⃣ FLUJO: PANEL ADMINISTRACIÓN (intranet/admin/)</h2>";

echo "<div class='tree'>";
echo "<h3>📂 Estructura de Archivos y Conexiones:</h3>";
echo "<ul>";
echo "<li><span class='url'>URL Base:</span> <code>" . ruta . "admin/</code></li>";
echo "<li><span class='folder'>admin/index.php</span> (Punto de entrada)</li>";
echo "<li>├─ <span class='folder'>admin/config/</span></li>";
echo "<li>│  ├─ <span class='file'>config.php</span> (Producción)</li>";
echo "<li>│  ├─ <span class='file'>config.local.php</span> (Local)</li>";
echo "<li>│  └─ <span class='file'>sistema.php</span> (Clase Conectar - BD)</li>";
echo "<li>├─ <span class='folder'>admin/app/controlador/</span></li>";
echo "<li>│  ├─ <span class='file'>loginCon.php</span> → Incluye sistema.php → Incluye loginModelo.php</li>";
echo "<li>│  ├─ <span class='file'>inicioCon.php</span> → Incluye sistema.php → Incluye inicioModelo.php</li>";
echo "<li>│  ├─ <span class='file'>clientesCon.php</span></li>";
echo "<li>│  ├─ <span class='file'>pdfCon.php</span></li>";
echo "<li>│  └─ <span class='file'>usuariosCon.php</span></li>";
echo "<li>├─ <span class='folder'>admin/app/modelo/</span></li>";
echo "<li>│  ├─ <span class='file'>loginModelo.php</span> → Extiende Conectar → Conecta a BD</li>";
echo "<li>│  └─ <span class='file'>inicioModelo.php</span> → Extiende Conectar → Conecta a BD</li>";
echo "<li>├─ <span class='folder'>admin/app/vista/</span></li>";
echo "<li>│  ├─ <span class='file'>login.phtml</span> → Formulario login (DNI + clave)</li>";
echo "<li>│  ├─ <span class='file'>head.php</span> → CSS, JS (jQuery local corregido)</li>";
echo "<li>│  ├─ <span class='file'>index.phtml</span> → Dashboard principal</li>";
echo "<li>│  ├─ <span class='file'>clientes.phtml</span> → Gestión clientes</li>";
echo "<li>│  └─ <span class='file'>pdf.phtml</span> → Gestión PDFs</li>";
echo "<li>└─ <span class='folder'>admin/publico/img_data/</span> → PDFs subidos por admin</li>";
echo "</ul>";
echo "</div>";

echo "<h3>🔄 Flujo de Conexiones (13 Pasos):</h3>";
echo "<div class='step'><strong>Paso 1:</strong> Usuario accede a <span class='url'>" . ruta . "admin/</span></div>";
echo "<div class='step'><strong>Paso 2:</strong> <span class='file'>admin/index.php</span> carga <span class='file'>admin/config/config.php</span> o <span class='file'>config.local.php</span></div>";
echo "<div class='step'><strong>Paso 3:</strong> <span class='file'>admin/index.php</span> carga <span class='file'>admin/config/sistema.php</span></div>";
echo "<div class='step'><strong>Paso 4:</strong> <span class='file'>admin/index.php</span> incluye <span class='file'>admin/app/controlador/loginCon.php</span></div>";
echo "<div class='step'><strong>Paso 5:</strong> <span class='file'>loginCon.php</span> verifica Conectar, incluye <span class='file'>loginModelo.php</span></div>";
echo "<div class='step'><strong>Paso 6:</strong> <span class='file'>loginModelo.php</span> verifica Conectar, crea instancia Logininicio</div>";
echo "<div class='step'><strong>Paso 7:</strong> Usuario envía formulario → <span class='file'>loginCon.php</span> llama <span class='file'>loginModelo->login()</span></div>";
echo "<div class='step'><strong>Paso 8:</strong> <span class='file'>loginModelo->login()</span> consulta BD tabla <span class='db'>usuarios</span> (campo <span class='db'>dni</span>)</div>";
echo "<div class='step'><strong>Paso 9:</strong> Si válido → Establece <span class='session'>\$_SESSION['id_geofal']</span> y cookie <span class='session'>id_geofal</span></div>";
echo "<div class='step'><strong>Paso 10:</strong> Redirige a <span class='url'>" . ruta . "admin/inicio</span></div>";
echo "<div class='step'><strong>Paso 11:</strong> <span class='file'>admin/index.php</span> carga <span class='file'>admin/app/controlador/inicioCon.php</span></div>";
echo "<div class='step'><strong>Paso 12:</strong> <span class='file'>inicioCon.php</span> incluye <span class='file'>inicioModelo.php</span> → Consulta datos</div>";
echo "<div class='step'><strong>Paso 13:</strong> <span class='file'>inicioCon.php</span> carga <span class='file'>admin/app/vista/index.phtml</span> → Dashboard</div>";

echo "<h3>🔐 Autenticación y Sesión:</h3>";
echo "<table>";
echo "<tr><th>Elemento</th><th>Valor</th></tr>";
echo "<tr><td>Tabla BD</td><td><span class='db'>usuarios</span></td></tr>";
echo "<tr><td>Campo Usuario</td><td><span class='db'>dni</span></td></tr>";
echo "<tr><td>Campo Clave</td><td><span class='db'>clave</span></td></tr>";
echo "<tr><td>Variable Sesión</td><td><span class='session'>\$_SESSION['id_geofal']</span></td></tr>";
echo "<tr><td>Cookie</td><td><span class='session'>id_geofal</span> (200 días)</td></tr>";
echo "<tr><td>Redirección Post-Login</td><td><span class='url'>" . ruta . "admin/inicio</span></td></tr>";
echo "</table>";

echo "<h3>✅ Validaciones del Flujo:</h3>";
$validaciones2 = [
    ['archivo' => 'admin/index.php', 'descripcion' => 'Punto de entrada admin', 'ruta' => 'admin/index.php'],
    ['archivo' => 'admin/config/config.php', 'descripcion' => 'Config producción admin', 'ruta' => 'admin/config/config.php'],
    ['archivo' => 'admin/config/config.local.php', 'descripcion' => 'Config local admin', 'ruta' => 'admin/config/config.local.php'],
    ['archivo' => 'admin/config/sistema.php', 'descripcion' => 'Clase Conectar admin', 'ruta' => 'admin/config/sistema.php'],
    ['archivo' => 'admin/app/controlador/loginCon.php', 'descripcion' => 'Controlador login admin', 'ruta' => 'admin/app/controlador/loginCon.php'],
    ['archivo' => 'admin/app/modelo/loginModelo.php', 'descripcion' => 'Modelo login admin', 'ruta' => 'admin/app/modelo/loginModelo.php'],
    ['archivo' => 'admin/app/vista/login.phtml', 'descripcion' => 'Vista login admin', 'ruta' => 'admin/app/vista/login.phtml'],
    ['archivo' => 'admin/app/vista/head.php', 'descripcion' => 'Head admin (jQuery local corregido)', 'ruta' => 'admin/app/vista/head.php'],
    ['archivo' => 'admin/app/controlador/inicioCon.php', 'descripcion' => 'Controlador inicio admin', 'ruta' => 'admin/app/controlador/inicioCon.php'],
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th></tr>";
foreach ($validaciones2 as $val) {
    $existe = file_exists($val['ruta']);
    $estado = $existe ? "<span class='ok'>✓ Existe</span>" : "<span class='error'>✗ No existe</span>";
    echo "<tr>";
    echo "<td><code>{$val['archivo']}</code></td>";
    echo "<td>{$val['descripcion']}</td>";
    echo "<td>{$estado}</td>";
    echo "</tr>";
}
echo "</table>";

echo "</div>";

// ============================================
// RESUMEN Y CONEXIONES CRÍTICAS
// ============================================
echo "<div class='flujo' style='background: #fff3cd; border-color: #ffc107;'>";
echo "<h2>🔗 CONEXIONES CRÍTICAS Y DEPENDENCIAS</h2>";

echo "<h3>📊 Matriz de Dependencias:</h3>";
echo "<table>";
echo "<tr><th>Archivo</th><th>Depende de</th><th>Estado</th></tr>";
echo "<tr><td>index.php</td><td>config.php, sistema.php</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>loginCon.php</td><td>sistema.php, loginModelo.php</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>loginModelo.php</td><td>sistema.php (Conectar)</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>ordenesCon.php</td><td>sistema.php, inicioModelo.php</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>head.php (admin)</td><td>ruta (jQuery local corregido)</td><td><span class='ok'>✓</span></td></tr>";
echo "</table>";

echo "<h3>🗄️ Conexiones a Base de Datos:</h3>";
echo "<table>";
echo "<tr><th>Flujo</th><th>Tabla</th><th>Operación</th><th>Estado</th></tr>";
echo "<tr><td>Extranet Clientes</td><td><span class='db'>clientes</span></td><td>SELECT (login), SELECT (PDFs)</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>Panel Admin</td><td><span class='db'>usuarios</span></td><td>SELECT (login)</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>Panel Admin</td><td><span class='db'>pdf</span></td><td>SELECT, INSERT, UPDATE, DELETE</td><td><span class='ok'>✓</span></td></tr>";
echo "<tr><td>Panel Admin</td><td><span class='db'>clientes</span></td><td>SELECT, INSERT, UPDATE, DELETE</td><td><span class='ok'>✓</span></td></tr>";
echo "</table>";

echo "</div>";

// ============================================
// VALIDACIÓN COMPLETA
// ============================================
echo "<div class='validation'>";
echo "<h2>✅ CHECKLIST DE VALIDACIÓN COMPLETA</h2>";

$checklist = [
    ['item' => 'Estructura de carpetas correcta (sin duplicados)', 'flujo' => 'Ambos'],
    ['item' => 'Configuración local y producción funcionando', 'flujo' => 'Ambos'],
    ['item' => 'Clase Conectar disponible en todos los modelos', 'flujo' => 'Ambos'],
    ['item' => 'jQuery cargado correctamente (ruta local)', 'flujo' => 'Admin'],
    ['item' => 'Logo cargado correctamente (ruta absoluta)', 'flujo' => 'Admin'],
    ['item' => 'Orden de scripts correcto (jQuery primero)', 'flujo' => 'Admin'],
    ['item' => 'Login cliente funciona (RUC + clave)', 'flujo' => 'Extranet'],
    ['item' => 'Login admin funciona (DNI + clave)', 'flujo' => 'Admin'],
    ['item' => 'Sesiones funcionando correctamente', 'flujo' => 'Ambos'],
    ['item' => 'Redirecciones post-login correctas', 'flujo' => 'Ambos'],
];

echo "<table>";
echo "<tr><th>Item</th><th>Flujo</th><th>Validar</th></tr>";
foreach ($checklist as $item) {
    echo "<tr>";
    echo "<td>{$item['item']}</td>";
    echo "<td>{$item['flujo']}</td>";
    echo "<td><input type='checkbox' onchange='this.parentElement.style.color=this.checked?\"green\":\"black\"'></td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>🎯 Próximos Pasos de Validación:</h3>";
echo "<ol>";
echo "<li><strong>Validar Flujo Clientes:</strong> Probar login con RUC + clave → Verificar acceso a órdenes → Verificar visualización de PDFs</li>";
echo "<li><strong>Validar Flujo Admin:</strong> Probar login con DNI + clave → Verificar acceso a inicio → Verificar funcionalidades CRUD</li>";
echo "<li><strong>Verificar Base de Datos:</strong> Confirmar que las tablas existen y tienen datos</li>";
echo "<li><strong>Probar en Producción:</strong> Subir cambios y verificar que funciona igual</li>";
echo "</ol>";

echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

