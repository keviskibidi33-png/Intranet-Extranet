<?php
/**
 * Script para verificar errores en los sistemas app/ y admin/
 * Ejecutar desde: http://localhost/public_html/intranet/verificar_errores.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Verificación de Errores - GEOFAL</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #ff4400; }
        h2 { color: #333; margin-top: 30px; border-left: 4px solid #ff4400; padding-left: 10px; }
        .error { background: #ffebee; padding: 10px; margin: 10px 0; border-left: 4px solid #f44336; }
        .ok { background: #e8f5e9; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .warning { background: #fff3e0; padding: 10px; margin: 10px 0; border-left: 4px solid #ff9800; }
        code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }
        ul { margin: 10px 0; }
        li { margin: 5px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Verificación de Errores - Sistemas app/ y admin/</h1>";

$baseDir = __DIR__;
$errores = [];
$advertencias = [];
$exitos = [];

// ========== VERIFICAR SISTEMA app/ ==========
echo "<h2>1. Sistema app/ (Extranet Clientes)</h2>";

// Verificar archivos principales
$archivosApp = [
    'app/controlador/clienteCon.php',
    'app/controlador/ordenesCon.php',
    'app/controlador/loginCon.php',
    'app/modelo/inicioModelo.php',
    'app/modelo/loginModelo.php',
    'app/vista/ordenes_2.phtml',
    'app/vista/head_2.php',
    'app/vista/header_2.php',
    'app/controlador/keys.php'
];

foreach ($archivosApp as $archivo) {
    $ruta = $baseDir . '/' . $archivo;
    if (file_exists($ruta)) {
        $exitos[] = "✅ $archivo existe";
    } else {
        $errores[] = "❌ $archivo NO existe";
    }
}

// Verificar configuración
if (file_exists($baseDir . '/config/config.local.php')) {
    $exitos[] = "✅ config/config.local.php existe";
} else {
    $advertencias[] = "⚠️ config/config.local.php no existe (usará config.php de producción)";
}

// Verificar que keys.php sea accesible
if (file_exists($baseDir . '/app/controlador/keys.php')) {
    try {
        include($baseDir . '/app/controlador/keys.php');
        if (defined('SITE_KEY')) {
            $exitos[] = "✅ keys.php se puede incluir correctamente";
        } else {
            $errores[] = "❌ keys.php no define SITE_KEY";
        }
    } catch (Exception $e) {
        $errores[] = "❌ Error al incluir keys.php: " . $e->getMessage();
    }
}

// Verificar rutas en ordenesCon.php
$ordenesCon = file_get_contents($baseDir . '/app/controlador/ordenesCon.php');
if (strpos($ordenesCon, 'include("keys.php")') !== false) {
    $exitos[] = "✅ ordenesCon.php incluye keys.php";
} else {
    $errores[] = "❌ ordenesCon.php no incluye keys.php";
}

// ========== VERIFICAR SISTEMA admin/ ==========
echo "<h2>2. Sistema admin/ (Panel Administración)</h2>";

// Verificar archivos principales
$archivosAdmin = [
    'admin/index.php',
    'admin/config/config.php',
    'admin/config/config.local.php',
    'admin/config/sistema.php',
    'admin/app/controlador/loginCon.php',
    'admin/app/controlador/clientesCon.php',
    'admin/app/controlador/pdfCon.php',
    'admin/app/controlador/ordenesCon.php',
    'admin/app/modelo/inicioModelo.php',
    'admin/app/modelo/loginModelo.php'
];

foreach ($archivosAdmin as $archivo) {
    $ruta = $baseDir . '/' . $archivo;
    if (file_exists($ruta)) {
        $exitos[] = "✅ $archivo existe";
    } else {
        if ($archivo == 'admin/config/config.local.php') {
            $advertencias[] = "⚠️ $archivo no existe (acabo de crearlo)";
        } else {
            $errores[] = "❌ $archivo NO existe";
        }
    }
}

// Verificar configuración de admin
if (file_exists($baseDir . '/admin/config/config.local.php')) {
    $exitos[] = "✅ admin/config/config.local.php existe";
} else {
    $advertencias[] = "⚠️ admin/config/config.local.php no existe";
}

// Verificar que admin/index.php detecte config.local.php
$adminIndex = file_get_contents($baseDir . '/admin/index.php');
if (strpos($adminIndex, 'config.local.php') !== false) {
    $exitos[] = "✅ admin/index.php detecta config.local.php";
} else {
    $errores[] = "❌ admin/index.php NO detecta config.local.php";
}

// ========== VERIFICAR RUTAS ==========
echo "<h2>3. Verificación de Rutas</h2>";

// Verificar que las rutas estén bien configuradas
if (file_exists($baseDir . '/config/config.local.php')) {
    require_once($baseDir . '/config/config.local.php');
    if (defined('ruta')) {
        $exitos[] = "✅ Variable ruta definida: " . ruta;
        if (strpos(ruta, 'localhost') !== false || strpos(ruta, '127.0.0.1') !== false) {
            $exitos[] = "✅ Ruta configurada para local";
        } else {
            $advertencias[] = "⚠️ Ruta parece ser de producción: " . ruta;
        }
    } else {
        $errores[] = "❌ Variable ruta NO definida";
    }
}

// ========== VERIFICAR BASE DE DATOS ==========
echo "<h2>4. Verificación de Base de Datos</h2>";

if (file_exists($baseDir . '/config/config.local.php')) {
    require_once($baseDir . '/config/config.local.php');
    if (defined('HOST') && defined('USER') && defined('DB_NAME')) {
        $exitos[] = "✅ Constantes de BD definidas";
        $exitos[] = "   - HOST: " . HOST;
        $exitos[] = "   - USER: " . USER;
        $exitos[] = "   - DB_NAME: " . DB_NAME;
        
        // Intentar conexión
        try {
            $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASSWORD);
            $exitos[] = "✅ Conexión a BD exitosa";
        } catch (PDOException $e) {
            $errores[] = "❌ Error de conexión a BD: " . $e->getMessage();
        }
    } else {
        $errores[] = "❌ Constantes de BD NO definidas";
    }
}

// ========== MOSTRAR RESULTADOS ==========
echo "<h2>📊 Resumen de Verificación</h2>";

if (!empty($exitos)) {
    echo "<div class='ok'><strong>✅ Éxitos (" . count($exitos) . "):</strong><ul>";
    foreach ($exitos as $exito) {
        echo "<li>$exito</li>";
    }
    echo "</ul></div>";
}

if (!empty($advertencias)) {
    echo "<div class='warning'><strong>⚠️ Advertencias (" . count($advertencias) . "):</strong><ul>";
    foreach ($advertencias as $advertencia) {
        echo "<li>$advertencia</li>";
    }
    echo "</ul></div>";
}

if (!empty($errores)) {
    echo "<div class='error'><strong>❌ Errores (" . count($errores) . "):</strong><ul>";
    foreach ($errores as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
} else {
    echo "<div class='ok'><strong>✅ No se encontraron errores críticos</strong></div>";
}

echo "<h2>💡 Recomendaciones</h2>
<div class='warning'>
    <ul>
        <li>Si hay errores de 'archivo no existe', verifica que las rutas sean correctas</li>
        <li>Si hay errores de conexión a BD, verifica las credenciales en config.local.php</li>
        <li>Si hay errores de rutas, verifica que config.local.php tenga la ruta correcta con /public_html/</li>
        <li>Revisa los logs de error de PHP para más detalles</li>
    </ul>
</div>";

echo "</div></body></html>";
?>

