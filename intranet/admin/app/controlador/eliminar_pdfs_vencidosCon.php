<?php
/**
 * Script para eliminar PDFs automáticamente cuando su fecha_eliminacion ha pasado
 * Este script debe ejecutarse periódicamente (cron job) o manualmente
 * 
 * Uso desde línea de comandos:
 * php eliminar_pdfs_vencidos.php
 * 
 * O desde navegador (requiere sesión de admin):
 * ?pagina=eliminar_pdfs_vencidos
 */

// Asegurar que sistema.php esté incluido
if (!class_exists('Conectar')) {
    $rutasSistema = [
        __DIR__ . '/../../config/sistema.php',
        'config/sistema.php',
        '../config/sistema.php'
    ];
    
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            break;
        }
    }
}

include("app/modelo/inicioModelo.php");
$inicio = new Inicio();

// Si se ejecuta desde navegador, verificar sesión
if (php_sapi_name() !== 'cli') {
    if (!isset($_SESSION['id_geofal']) && empty($_SESSION['id_geofal'])) {
        if (isset($_COOKIE['id_geofal'])) {
            $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
        }
    }
    
    if (!isset($_SESSION['id_geofal'])) {
        die('Debes estar logueado como administrador para usar este script.');
    }
}

// Fecha de hoy
$fecha_hoy = date('Y-m-d');

// Buscar PDFs con fecha_eliminacion pasada
$pdfs_vencidos = $inicio->consultas("
    SELECT id, pdf, titulo, id_user, fecha_eliminacion 
    FROM pdf 
    WHERE fecha_eliminacion IS NOT NULL 
    AND fecha_eliminacion < '$fecha_hoy'
");

$eliminados = [];
$errores = [];
$archivos_eliminados = 0;

foreach ($pdfs_vencidos as $pdf) {
    try {
        $pdf_filename = $pdf['pdf'];
        
        // Eliminar de la base de datos usando el método público deleteRow
        $inicio->deleteRow("DELETE FROM pdf WHERE id = ?", [$pdf['id']]);
        
        // Intentar eliminar el archivo físico
        $base_dir = dirname(dirname(dirname(__DIR__))); // intranet/
        $file_path = $base_dir . '/publico/img_data/' . $pdf_filename;
        
        if (file_exists($file_path)) {
            if (@unlink($file_path)) {
                $archivos_eliminados++;
            }
        }
        
        // También buscar en ubicación antigua
        $file_path_old = dirname($base_dir) . '/publico/img_data/' . $pdf_filename;
        if (file_exists($file_path_old)) {
            @unlink($file_path_old);
        }
        
        $eliminados[] = [
            'id' => $pdf['id'],
            'titulo' => $pdf['titulo'],
            'fecha_eliminacion' => $pdf['fecha_eliminacion'],
            'archivo' => $pdf_filename
        ];
        
    } catch (Exception $e) {
        $errores[] = [
            'id' => $pdf['id'],
            'titulo' => $pdf['titulo'],
            'error' => $e->getMessage()
        ];
    }
}

// Si se ejecuta desde CLI, mostrar resultados en consola
if (php_sapi_name() === 'cli') {
    echo "=== ELIMINACIÓN AUTOMÁTICA DE PDFs VENCIDOS ===\n";
    echo "Fecha de ejecución: " . date('Y-m-d H:i:s') . "\n";
    echo "PDFs encontrados: " . count($pdfs_vencidos) . "\n";
    echo "PDFs eliminados: " . count($eliminados) . "\n";
    echo "Archivos físicos eliminados: " . $archivos_eliminados . "\n";
    echo "Errores: " . count($errores) . "\n\n";
    
    if (!empty($eliminados)) {
        echo "PDFs Eliminados:\n";
        foreach ($eliminados as $elim) {
            echo "  - ID: {$elim['id']}, Título: {$elim['titulo']}, Fecha: {$elim['fecha_eliminacion']}\n";
        }
    }
    
    if (!empty($errores)) {
        echo "\nErrores:\n";
        foreach ($errores as $err) {
            echo "  - ID: {$err['id']}, Título: {$err['titulo']}, Error: {$err['error']}\n";
        }
    }
    
    exit(0);
}

// Si se ejecuta desde navegador, mostrar HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminación Automática de PDFs Vencidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #f34d23;
            border-bottom: 3px solid #f34d23;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f34d23;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #f34d23;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗑️ Eliminación Automática de PDFs Vencidos</h1>
        
        <div class="info">
            <strong>Fecha de ejecución:</strong> <?php echo date('d/m/Y H:i:s'); ?><br>
            <strong>Fecha de corte:</strong> <?php echo date('d/m/Y'); ?> (PDFs con fecha anterior serán eliminados)<br>
            <strong>PDFs encontrados:</strong> <?php echo count($pdfs_vencidos); ?><br>
            <strong>PDFs eliminados:</strong> <?php echo count($eliminados); ?><br>
            <strong>Archivos físicos eliminados:</strong> <?php echo $archivos_eliminados; ?><br>
            <strong>Errores:</strong> <?php echo count($errores); ?>
        </div>
        
        <?php if (!empty($eliminados)): ?>
            <div class="success">
                <strong>✅ Éxito:</strong> Se eliminaron <?php echo count($eliminados); ?> PDFs correctamente.
            </div>
            
            <h2>PDFs Eliminados:</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Fecha Eliminación</th>
                        <th>Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eliminados as $elim): ?>
                        <tr>
                            <td><?php echo $elim['id']; ?></td>
                            <td><?php echo htmlspecialchars($elim['titulo']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($elim['fecha_eliminacion'])); ?></td>
                            <td><code><?php echo htmlspecialchars($elim['archivo']); ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="info">
                <strong>ℹ️ No hay PDFs vencidos para eliminar.</strong>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errores)): ?>
            <div class="error">
                <strong>❌ Errores:</strong>
                <ul>
                    <?php foreach ($errores as $err): ?>
                        <li><strong>ID <?php echo $err['id']; ?> (<?php echo htmlspecialchars($err['titulo']); ?>):</strong> <?php echo htmlspecialchars($err['error']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="<?php echo ruta; ?>?pagina=clientes" class="btn">Volver a Clientes</a>
        </div>
    </div>
</body>
</html>

