<?php
/**
 * ANÁLISIS DE OPTIMIZACIÓN
 * Identifica redundancias y oportunidades de mejora
 */

echo "<h1>🔍 ANÁLISIS DE OPTIMIZACIÓN - ¿ESTÁ AL REVÉS?</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .section { background: white; border: 2px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 8px; }
    .redundante { background: #fff3cd; border-left: 5px solid #ffc107; padding: 15px; margin: 10px 0; }
    .necesario { background: #d4edda; border-left: 5px solid #28a745; padding: 15px; margin: 10px 0; }
    .publico { background: #e7f3ff; border-left: 5px solid #007bff; padding: 15px; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
    .code { background: #f4f4f4; padding: 2px 5px; font-family: monospace; }
    .ok { color: #28a745; }
    .error { color: #dc3545; }
    .warning { color: #ffc107; }
</style>";

$baseDir = __DIR__;

// Analizar controladores de app/
$controladoresApp = glob($baseDir . '/app/controlador/*Con.php');
$controladoresAdmin = glob($baseDir . '/admin/app/controlador/*Con.php');

echo "<div class='section'>";
echo "<h2>📊 ESTADÍSTICAS DE ARCHIVOS</h2>";

echo "<table>";
echo "<tr><th>Sistema</th><th>Controladores</th><th>Funcionalidad</th><th>¿Es excesivo?</th></tr>";
echo "<tr>";
echo "<td><strong>Extranet Clientes</strong></td>";
echo "<td>" . count($controladoresApp) . " archivos</td>";
echo "<td>Solo ver documentos (4 funciones)</td>";
echo "<td><span class='error'>❌ SÍ, excesivo</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><strong>Panel Admin</strong></td>";
echo "<td>" . count($controladoresAdmin) . " archivos</td>";
echo "<td>CRUD completo (8+ funciones)</td>";
echo "<td><span class='ok'>✓ Apropiado</span></td>";
echo "</tr>";
echo "</table>";

echo "</div>";

// ============================================
// ANÁLISIS: CONTROLADORES DE CLIENTES
// ============================================
echo "<div class='section'>";
echo "<h2>👤 ANÁLISIS: CONTROLADORES DEL FLUJO CLIENTES</h2>";

$controladoresClientes = [
    ['archivo' => 'loginCon.php', 'tipo' => 'necesario', 'descripcion' => 'Login de clientes - NECESARIO'],
    ['archivo' => 'ordenesCon.php', 'tipo' => 'necesario', 'descripcion' => 'Ver documentos PDF - NECESARIO'],
    ['archivo' => 'clienteCon.php', 'tipo' => 'necesario', 'descripcion' => 'Punto de entrada (redirige a login) - NECESARIO'],
    ['archivo' => 'salirCon.php', 'tipo' => 'necesario', 'descripcion' => 'Cerrar sesión - NECESARIO'],
    ['archivo' => 'contactoCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública de contacto - NO es del flujo clientes'],
    ['archivo' => 'nosotrosCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública "Nosotros" - NO es del flujo clientes'],
    ['archivo' => 'galeriaCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública galería - NO es del flujo clientes'],
    ['archivo' => 'inicioCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública inicio - NO es del flujo clientes'],
    ['archivo' => 'estudio-de-suelosCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública servicio - NO es del flujo clientes'],
    ['archivo' => 'evaluacion-estructural-e-ingenieriaCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública servicio - NO es del flujo clientes'],
    ['archivo' => 'laboratorio-de-suelo-concreto-pavimento-y-albanileriaCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública servicio - NO es del flujo clientes'],
    ['archivo' => 'control-de-calidad-de-obras-civilesCon.php', 'tipo' => 'publico', 'descripcion' => 'Página pública servicio - NO es del flujo clientes'],
];

echo "<h3>🔍 Clasificación de Controladores:</h3>";

$necesarios = [];
$publicos = [];

foreach ($controladoresClientes as $ctrl) {
    if ($ctrl['tipo'] == 'necesario') {
        $necesarios[] = $ctrl;
    } else {
        $publicos[] = $ctrl;
    }
}

echo "<div class='necesario'>";
echo "<h4>✅ Controladores NECESARIOS para el flujo de clientes (4 archivos):</h4>";
echo "<ul>";
foreach ($necesarios as $ctrl) {
    echo "<li><code>{$ctrl['archivo']}</code> - {$ctrl['descripcion']}</li>";
}
echo "</ul>";
echo "</div>";

echo "<div class='publico'>";
echo "<h4>🌐 Controladores de PÁGINAS PÚBLICAS (8+ archivos):</h4>";
echo "<ul>";
foreach ($publicos as $ctrl) {
    echo "<li><code>{$ctrl['archivo']}</code> - {$ctrl['descripcion']}</li>";
}
echo "</ul>";
echo "<p><strong>⚠️ Estos NO son parte del flujo de clientes, son páginas del sitio web público.</strong></p>";
echo "</div>";

echo "</div>";

// ============================================
// ANÁLISIS: CONTROLADORES DE ADMIN
// ============================================
echo "<div class='section'>";
echo "<h2>👨‍💼 ANÁLISIS: CONTROLADORES DEL FLUJO ADMIN</h2>";

$controladoresAdminList = [
    ['archivo' => 'loginCon.php', 'funcionalidad' => 'Login admin', 'crud' => 'No'],
    ['archivo' => 'inicioCon.php', 'funcionalidad' => 'Dashboard principal', 'crud' => 'No'],
    ['archivo' => 'clientesCon.php', 'funcionalidad' => 'Listar clientes', 'crud' => 'READ'],
    ['archivo' => 'clientes_agregarCon.php', 'funcionalidad' => 'Crear cliente', 'crud' => 'CREATE'],
    ['archivo' => 'clientes_editarCon.php', 'funcionalidad' => 'Editar cliente', 'crud' => 'UPDATE'],
    ['archivo' => 'pdfCon.php', 'funcionalidad' => 'Listar PDFs', 'crud' => 'READ'],
    ['archivo' => 'pdf_agregarCon.php', 'funcionalidad' => 'Subir PDF', 'crud' => 'CREATE'],
    ['archivo' => 'pdf_editarCon.php', 'funcionalidad' => 'Editar PDF', 'crud' => 'UPDATE'],
    ['archivo' => 'ordenesCon.php', 'funcionalidad' => 'Ver órdenes', 'crud' => 'READ'],
    ['archivo' => 'perfilCon.php', 'funcionalidad' => 'Perfil usuario', 'crud' => 'UPDATE'],
    ['archivo' => 'salirCon.php', 'funcionalidad' => 'Cerrar sesión', 'crud' => 'No'],
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Funcionalidad</th><th>CRUD</th></tr>";
foreach ($controladoresAdminList as $ctrl) {
    $crudColor = $ctrl['crud'] == 'No' ? 'gray' : ($ctrl['crud'] == 'CREATE' ? 'green' : ($ctrl['crud'] == 'UPDATE' ? 'blue' : ($ctrl['crud'] == 'READ' ? 'orange' : 'red')));
    echo "<tr>";
    echo "<td><code>{$ctrl['archivo']}</code></td>";
    echo "<td>{$ctrl['funcionalidad']}</td>";
    echo "<td style='color: {$crudColor};'><strong>{$ctrl['crud']}</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<div class='necesario'>";
echo "<p><strong>Total:</strong> " . count($controladoresAdminList) . " controladores</p>";
echo "<p><strong>CRUD completo:</strong> CREATE, READ, UPDATE, DELETE para clientes y PDFs</p>";
echo "<p><strong>Conclusión:</strong> <span class='ok'>✓ Estructura apropiada para la funcionalidad</span></p>";
echo "</div>";

echo "</div>";

// ============================================
// CONCLUSIÓN Y RECOMENDACIONES
// ============================================
echo "<div class='section' style='background: #fff3cd; border-color: #ffc107;'>";
echo "<h2>🎯 CONCLUSIÓN: ¿ESTÁ AL REVÉS?</h2>";

echo "<div class='redundante'>";
echo "<h3>❌ PROBLEMA IDENTIFICADO:</h3>";
echo "<p><strong>El flujo de clientes tiene MUCHOS archivos pero NO todos son del flujo de clientes.</strong></p>";
echo "<ul>";
echo "<li><strong>Archivos REALES del flujo clientes:</strong> Solo 4 (login, órdenes, cliente, salir)</li>";
echo "<li><strong>Archivos de páginas públicas:</strong> 8+ (contacto, nosotros, galería, servicios, etc.)</li>";
echo "<li><strong>Problema:</strong> Están mezclados en la misma carpeta <code>app/controlador/</code></li>";
echo "</ul>";
echo "</div>";

echo "<div class='necesario'>";
echo "<h3>✅ FLUJO ADMIN ESTÁ BIEN:</h3>";
echo "<p><strong>El flujo admin tiene la cantidad apropiada de archivos para su funcionalidad.</strong></p>";
echo "<ul>";
echo "<li><strong>Total controladores:</strong> " . count($controladoresAdminList) . "</li>";
echo "<li><strong>Funcionalidad:</strong> CRUD completo + gestión completa</li>";
echo "<li><strong>Conclusión:</strong> Estructura apropiada, no está al revés</li>";
echo "</ul>";
echo "</div>";

echo "<h3>📋 RECOMENDACIONES DE OPTIMIZACIÓN:</h3>";
echo "<ol>";
echo "<li><strong>Separar páginas públicas:</strong> Mover controladores de páginas públicas (contacto, nosotros, etc.) a una carpeta separada como <code>publico/controlador/</code></li>";
echo "<li><strong>Mantener flujo clientes limpio:</strong> Solo dejar en <code>app/controlador/</code> los 4 archivos del flujo real de clientes</li>";
echo "<li><strong>Flujo admin:</strong> Está bien estructurado, no necesita cambios</li>";
echo "</ol>";

echo "<h3>📊 RESUMEN:</h3>";
echo "<table>";
echo "<tr><th>Aspecto</th><th>Flujo Clientes</th><th>Flujo Admin</th></tr>";
echo "<tr><td><strong>Archivos totales</strong></td><td>12+ archivos</td><td>" . count($controladoresAdminList) . " archivos</td></tr>";
echo "<tr><td><strong>Archivos REALES del flujo</strong></td><td><span class='ok'>4 archivos</span></td><td><span class='ok'>" . count($controladoresAdminList) . " archivos</span></td></tr>";
echo "<tr><td><strong>Archivos públicos mezclados</strong></td><td><span class='error'>8+ archivos</span></td><td><span class='ok'>0 archivos</span></td></tr>";
echo "<tr><td><strong>Funcionalidad</strong></td><td>4 funciones básicas</td><td>8+ funciones + CRUD</td></tr>";
echo "<tr><td><strong>¿Está optimizado?</strong></td><td><span class='error'>❌ NO (mezclado con páginas públicas)</span></td><td><span class='ok'>✓ SÍ (bien estructurado)</span></td></tr>";
echo "</table>";

echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

