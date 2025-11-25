<?php
/**
 * Script para analizar y mapear la estructura del sistema
 * Ejecutar desde: http://localhost/public_html/intranet/analizar_estructura.php
 */

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Análisis de Estructura - GEOFAL</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #ff4400; border-bottom: 3px solid #ff4400; padding-bottom: 10px; }
        h2 { color: #333; margin-top: 30px; border-left: 4px solid #ff4400; padding-left: 10px; }
        .sistema { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #4CAF50; }
        .sistema.duplicado { border-left-color: #f44336; background: #ffebee; }
        .sistema.principal { border-left-color: #2196F3; background: #e3f2fd; }
        .archivos { margin-left: 20px; font-size: 0.9em; color: #666; }
        .archivo { padding: 5px; margin: 3px 0; }
        .archivo.importante { font-weight: bold; color: #ff4400; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-box { background: #e3f2fd; padding: 15px; border-radius: 5px; flex: 1; text-align: center; }
        .stat-number { font-size: 2em; font-weight: bold; color: #2196F3; }
        .stat-label { color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #ff4400; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f5f5f5; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 0.8em; font-weight: bold; }
        .badge.activo { background: #4CAF50; color: white; }
        .badge.duplicado { background: #f44336; color: white; }
        .badge.admin { background: #2196F3; color: white; }
        .badge.cliente { background: #FF9800; color: white; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Análisis de Estructura - Sistema GEOFAL</h1>";

// Función para contar archivos
function countFiles($dir, $ext = null) {
    $count = 0;
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    $count += countFiles($path, $ext);
                } elseif (!$ext || pathinfo($file, PATHINFO_EXTENSION) == $ext) {
                    $count++;
                }
            }
        }
    }
    return $count;
}

// Función para listar archivos importantes
function listImportantFiles($dir, $baseDir = '') {
    $files = [];
    if (is_dir($dir)) {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item != '.' && $item != '..') {
                $path = $dir . '/' . $item;
                $relativePath = str_replace($baseDir, '', $path);
                if (is_dir($path)) {
                    $files = array_merge($files, listImportantFiles($path, $baseDir));
                } else {
                    $ext = pathinfo($item, PATHINFO_EXTENSION);
                    if (in_array($ext, ['php', 'phtml', 'js', 'css']) || 
                        in_array($item, ['index.php', 'config.php', 'sistema.php'])) {
                        $files[] = $relativePath;
                    }
                }
            }
        }
    }
    return $files;
}

$baseDir = __DIR__;

echo "<div class='stats'>
    <div class='stat-box'>
        <div class='stat-number'>" . countFiles($baseDir . '/app', 'php') . "</div>
        <div class='stat-label'>Archivos PHP (app)</div>
    </div>
    <div class='stat-box'>
        <div class='stat-number'>" . countFiles($baseDir . '/admin', 'php') . "</div>
        <div class='stat-label'>Archivos PHP (admin)</div>
    </div>
    <div class='stat-box'>
        <div class='stat-number'>" . countFiles($baseDir . '/publico/img_data', 'pdf') . "</div>
        <div class='stat-label'>PDFs almacenados</div>
    </div>
</div>";

// Mapeo de sistemas
$sistemas = [
    [
        'nombre' => 'EXTRANET DE CLIENTES',
        'ruta' => 'app/',
        'tipo' => 'cliente',
        'descripcion' => 'Área donde los clientes ven sus documentos',
        'login' => 'RUC + clave (tabla: clientes)',
        'sesion' => '$_SESSION[\'id_geo\']',
        'archivos_importantes' => [
            'app/controlador/clienteCon.php',
            'app/controlador/ordenesCon.php',
            'app/controlador/loginCon.php',
            'app/modelo/inicioModelo.php',
            'app/vista/ordenes_2.phtml'
        ]
    ],
    [
        'nombre' => 'PANEL ADMINISTRACIÓN',
        'ruta' => 'admin/',
        'tipo' => 'admin',
        'descripcion' => 'Área donde vendedores/admin gestionan todo',
        'login' => 'DNI + clave (tabla: usuarios)',
        'sesion' => '$_SESSION[\'id_geofal\']',
        'archivos_importantes' => [
            'admin/index.php',
            'admin/app/controlador/clientesCon.php',
            'admin/app/controlador/pdfCon.php',
            'admin/app/controlador/ordenesCon.php',
            'admin/app/modelo/inicioModelo.php'
        ]
    ],
    [
        'nombre' => 'PHPMaker (Alternativo)',
        'ruta' => 'admin2/',
        'tipo' => 'duplicado',
        'descripcion' => 'Sistema generado con PHPMaker - Duplicado de admin',
        'login' => 'Usuarios (tabla: usuarios)',
        'sesion' => 'Sesión PHPMaker',
        'archivos_importantes' => [
            'admin2/index.php',
            'admin2/clienteslist.php',
            'admin2/pdflist.php',
            'admin2/usuarioslist.php'
        ]
    ],
    [
        'nombre' => 'COPIA DUPLICADA',
        'ruta' => 'intranet2/',
        'tipo' => 'duplicado',
        'descripcion' => 'Copia completa de intranet/ - DUPLICADO',
        'login' => 'Mismo que intranet/',
        'sesion' => 'Mismo que intranet/',
        'archivos_importantes' => ['intranet2/ (copia completa)']
    ]
];

echo "<h2>📂 Sistemas Identificados</h2>";

foreach ($sistemas as $sistema) {
    $class = $sistema['tipo'] == 'duplicado' ? 'duplicado' : ($sistema['tipo'] == 'admin' ? 'principal' : '');
    $badge = $sistema['tipo'] == 'duplicado' ? 'duplicado' : ($sistema['tipo'] == 'admin' ? 'admin' : 'cliente');
    
    echo "<div class='sistema $class'>
        <h3>{$sistema['nombre']} <span class='badge $badge'>" . strtoupper($sistema['tipo']) . "</span></h3>
        <p><strong>Ruta:</strong> <code>{$sistema['ruta']}</code></p>
        <p><strong>Descripción:</strong> {$sistema['descripcion']}</p>
        <p><strong>Login:</strong> {$sistema['login']}</p>
        <p><strong>Sesión:</strong> <code>{$sistema['sesion']}</code></p>
        <div class='archivos'>
            <strong>Archivos importantes:</strong>
            <ul>";
    foreach ($sistema['archivos_importantes'] as $archivo) {
        $exists = file_exists($baseDir . '/' . $archivo) ? '✅' : '❌';
        echo "<li class='archivo importante'>$exists <code>$archivo</code></li>";
    }
    echo "</ul></div></div>";
}

echo "<h2>📊 Comparación de Sistemas</h2>
<table>
    <tr>
        <th>Sistema</th>
        <th>Tipo</th>
        <th>Usuarios</th>
        <th>Tabla BD</th>
        <th>Estado</th>
    </tr>";

foreach ($sistemas as $sistema) {
    $estado = $sistema['tipo'] == 'duplicado' ? '⚠️ Duplicado' : '✅ Activo';
    $tipo = ucfirst($sistema['tipo']);
    echo "<tr>
        <td><strong>{$sistema['nombre']}</strong></td>
        <td>$tipo</td>
        <td>{$sistema['login']}</td>
        <td>" . ($sistema['tipo'] == 'cliente' ? 'clientes' : 'usuarios') . "</td>
        <td>$estado</td>
    </tr>";
}

echo "</table>";

echo "<h2>💡 Recomendaciones</h2>
<div class='sistema'>
    <h3>1. Limpieza Inmediata</h3>
    <ul>
        <li>❌ <strong>Eliminar:</strong> <code>intranet2/</code> (copia completa duplicada)</li>
        <li>❓ <strong>Decidir:</strong> ¿Mantener <code>admin/</code> o <code>admin2/</code>? (recomiendo admin/)</li>
    </ul>
    
    <h3>2. Estructura Final Recomendada</h3>
    <ul>
        <li>✅ <code>intranet/app/</code> - Extranet clientes (MANTENER)</li>
        <li>✅ <code>intranet/admin/</code> - Panel administración (MANTENER)</li>
        <li>❌ <code>intranet/admin2/</code> - Eliminar (duplicado)</li>
        <li>❌ <code>intranet/intranet2/</code> - Eliminar (duplicado)</li>
    </ul>
</div>";

echo "<p style='margin-top: 30px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107;'>
    <strong>⚠️ Nota:</strong> Este análisis se basa en la estructura de archivos. 
    Revisa cada sistema manualmente antes de eliminar duplicados.
</p>";

echo "</div></body></html>";
?>

