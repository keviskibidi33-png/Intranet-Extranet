<?php
/**
 * Controlador de Notificaciones
 * Endpoint AJAX para obtener notificaciones de PDFs próximos a eliminar
 * 
 * La sesión y configuración ya están iniciadas por index.php
 */

include("app/modelo/inicioModelo.php");

$inicio = new Inicio();

// Verificar sesión
if (!isset($_SESSION['id_geofal']) && empty($_SESSION['id_geofal'])) {
    if (isset($_COOKIE['id_geofal'])) {
        $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
    }
}

if (!isset($_SESSION['id_geofal'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['estado' => '0', 'mensaje' => 'No autorizado']);
    exit;
}

// Obtener PDFs próximos a eliminar
$pdfs_proximos = $inicio->obtener_pdfs_proximos_eliminar();

// Formatear respuesta
$notificaciones = [];
foreach ($pdfs_proximos as $pdf) {
    $fecha_eliminacion = new DateTime($pdf['fecha_eliminacion']);
    $fecha_hoy = new DateTime();
    $dias_restantes = $fecha_hoy->diff($fecha_eliminacion)->days;
    
    $notificaciones[] = [
        'id' => $pdf['id'],
        'titulo' => $pdf['titulo'],
        'razon_social' => $pdf['razon_social'] ?? 'Sin empresa',
        'ruc' => $pdf['ruc'] ?? '',
        'fecha_eliminacion' => date('d/m/Y', strtotime($pdf['fecha_eliminacion'])),
        'dias_restantes' => $dias_restantes,
        'url' => ruta . '?pagina=pdf&id=' . $pdf['id_user']
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'estado' => '1',
    'total' => count($notificaciones),
    'notificaciones' => $notificaciones
]);
exit;

