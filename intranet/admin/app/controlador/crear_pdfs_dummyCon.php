<?php
/**
 * Script para crear PDFs dummy para pruebas Q/A
 * Crea PDFs con diferentes fechas de eliminación para probar el sistema de notificaciones
 * 
 * Uso: Acceder desde el navegador cuando estés logueado como admin
 * URL: ?pagina=crear_pdfs_dummy
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

// Verificar sesión
if (!isset($_SESSION['id_geofal']) && empty($_SESSION['id_geofal'])) {
    if (isset($_COOKIE['id_geofal'])) {
        $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
    }
}

if (!isset($_SESSION['id_geofal'])) {
    die('Debes estar logueado como administrador para usar este script.');
}

// Obtener el primer cliente disponible para asignar los PDFs
$clientes = $inicio->consultas("SELECT id, razon_social FROM clientes LIMIT 1");
if (empty($clientes)) {
    die('No hay clientes en la base de datos. Crea al menos un cliente primero.');
}
$id_cliente = $clientes[0]['id'];
$razon_social = $clientes[0]['razon_social'];

// Directorio donde se guardan los PDFs
$base_dir = dirname(dirname(dirname(__DIR__))); // intranet/
$pdf_dir = $base_dir . '/publico/img_data/';

// Crear directorio si no existe
if (!is_dir($pdf_dir)) {
    mkdir($pdf_dir, 0755, true);
}

// Definir PDFs dummy con diferentes fechas de eliminación
$pdfs_dummy = [
    [
        'titulo' => 'PDF DUMMY - Eliminación HOY',
        'fecha_eliminacion' => date('Y-m-d'), // Hoy
        'descripcion' => 'Este PDF se eliminará hoy (para pruebas urgentes)'
    ],
    [
        'titulo' => 'PDF DUMMY - Eliminación MAÑANA',
        'fecha_eliminacion' => date('Y-m-d', strtotime('+1 day')), // Mañana
        'descripcion' => 'Este PDF se eliminará mañana'
    ],
    [
        'titulo' => 'PDF DUMMY - Eliminación en 3 días',
        'fecha_eliminacion' => date('Y-m-d', strtotime('+3 days')), // 3 días
        'descripcion' => 'Este PDF se eliminará en 3 días'
    ],
    [
        'titulo' => 'PDF DUMMY - Eliminación en 7 días',
        'fecha_eliminacion' => date('Y-m-d', strtotime('+7 days')), // 7 días (límite de notificaciones)
        'descripcion' => 'Este PDF se eliminará en 7 días (límite de notificaciones)'
    ],
    [
        'titulo' => 'PDF DUMMY - Eliminación en 2 meses',
        'fecha_eliminacion' => date('Y-m-d', strtotime('+2 months')), // 2 meses
        'descripcion' => 'Este PDF se eliminará en 2 meses (eliminación automática)'
    ],
    [
        'titulo' => 'PDF DUMMY - Eliminación en 60 días',
        'fecha_eliminacion' => date('Y-m-d', strtotime('+60 days')), // 60 días
        'descripcion' => 'Este PDF se eliminará en 60 días'
    ]
];

$resultados = [];
$errores = [];

foreach ($pdfs_dummy as $index => $pdf_dummy) {
    try {
        // Crear un PDF dummy simple
        $pdf_filename = 'dummy_test_' . time() . '_' . $index . '.pdf';
        $pdf_path = $pdf_dir . $pdf_filename;
        
        // Crear contenido PDF básico
        $pdf_content = "%PDF-1.4\n";
        $pdf_content .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pdf_content .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $pdf_content .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> >>\nendobj\n";
        $pdf_content .= "4 0 obj\n<< /Length 100 >>\nstream\nBT\n/F1 12 Tf\n100 700 Td\n(" . $pdf_dummy['titulo'] . ") Tj\nET\nendstream\nendobj\n";
        $pdf_content .= "xref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000300 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n400\n%%EOF";
        
        // Guardar archivo PDF
        file_put_contents($pdf_path, $pdf_content);
        
        // Insertar en la base de datos usando el método público insertRow
        $query = "
            INSERT INTO pdf (pdf, id_user, titulo, vista, estado, fecha_eliminacion, fecha_subida) 
            VALUES (?, ?, ?, '0', '0', ?, NOW())
        ";
        
        $params = [
            $pdf_filename,
            $id_cliente,
            $pdf_dummy['titulo'],
            $pdf_dummy['fecha_eliminacion']
        ];
        
        $inicio->insertRow($query, $params);
        
        $resultados[] = [
            'titulo' => $pdf_dummy['titulo'],
            'fecha_eliminacion' => $pdf_dummy['fecha_eliminacion'],
            'archivo' => $pdf_filename,
            'estado' => 'OK'
        ];
        
    } catch (Exception $e) {
        $errores[] = [
            'titulo' => $pdf_dummy['titulo'],
            'error' => $e->getMessage()
        ];
    }
}

// Mostrar resultados
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDFs Dummy Creados - Pruebas Q/A</title>
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
        tr:hover {
            background: #f9f9f9;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
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
        .btn:hover {
            background: #d43e1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 PDFs Dummy Creados para Pruebas Q/A</h1>
        
        <div class="info-box">
            <strong>Cliente asignado:</strong> <?php echo htmlspecialchars($razon_social); ?> (ID: <?php echo $id_cliente; ?>)<br>
            <strong>Total creados:</strong> <?php echo count($resultados); ?> PDFs<br>
            <strong>Errores:</strong> <?php echo count($errores); ?>
        </div>
        
        <?php if (!empty($resultados)): ?>
            <div class="success">
                <strong>✅ Éxito:</strong> Se crearon <?php echo count($resultados); ?> PDFs dummy correctamente.
            </div>
            
            <h2>PDFs Creados:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha Eliminación</th>
                        <th>Días Restantes</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $resultado): 
                        $fecha_elim = new DateTime($resultado['fecha_eliminacion']);
                        $fecha_hoy = new DateTime();
                        $dias_restantes = $fecha_hoy->diff($fecha_elim)->days;
                        $clase_urgencia = $dias_restantes <= 1 ? 'badge-danger' : ($dias_restantes <= 7 ? 'badge-warning' : 'badge-success');
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($resultado['titulo']); ?></strong></td>
                            <td><?php echo date('d/m/Y', strtotime($resultado['fecha_eliminacion'])); ?></td>
                            <td>
                                <span class="badge <?php echo $clase_urgencia; ?>">
                                    <?php 
                                    if ($dias_restantes == 0) echo 'HOY';
                                    elseif ($dias_restantes == 1) echo 'MAÑANA';
                                    else echo $dias_restantes . ' días';
                                    ?>
                                </span>
                            </td>
                            <td><code><?php echo htmlspecialchars($resultado['archivo']); ?></code></td>
                            <td><span class="badge badge-success">✅ Creado</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <?php if (!empty($errores)): ?>
            <div class="error">
                <strong>❌ Errores:</strong>
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><strong><?php echo htmlspecialchars($error['titulo']); ?>:</strong> <?php echo htmlspecialchars($error['error']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px;">
            <h2>Próximos Pasos para Pruebas:</h2>
            <ol>
                <li><strong>Verificar Notificaciones:</strong> 
                    <a href="<?php echo ruta; ?>?pagina=pdf&id=<?php echo $id_cliente; ?>" class="btn">Ver PDFs del Cliente</a>
                    Abre el dropdown de notificaciones (campana) en el header para ver las alertas.
                </li>
                <li><strong>Probar Eliminación Automática:</strong> 
                    Los PDFs con fecha de eliminación pasada se eliminarán automáticamente cuando se ejecute el script de limpieza.
                </li>
                <li><strong>Verificar Alertas por Urgencia:</strong>
                    <ul>
                        <li>Rojo: Eliminación HOY o MAÑANA (urgente)</li>
                        <li>Amarillo: Eliminación en 2-7 días (advertencia)</li>
                        <li>Azul: Eliminación en más de 7 días (información)</li>
                    </ul>
                </li>
            </ol>
        </div>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="<?php echo ruta; ?>?pagina=pdf&id=<?php echo $id_cliente; ?>" class="btn">Ver PDFs del Cliente</a>
            <a href="<?php echo ruta; ?>?pagina=clientes" class="btn">Volver a Clientes</a>
        </div>
    </div>
</body>
</html>

