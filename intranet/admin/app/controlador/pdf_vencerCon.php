<?php
/**
 * Controlador para la página de gestión de PDFs próximos a vencer
 * Muestra todos los PDFs con fecha_eliminacion y su información
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
    header("Location:" . ruta . 'login');
    exit;
}

// Obtener todos los PDFs con fecha_eliminacion (próximos a vencer)
$pdfs_vencer = $inicio->consultas("
    SELECT 
        p.id,
        p.titulo,
        p.pdf,
        p.fecha_subida,
        p.fecha_eliminacion,
        p.id_user,
        c.razon_social,
        c.ruc
    FROM pdf p
    LEFT JOIN clientes c ON p.id_user = c.id
    WHERE p.fecha_eliminacion IS NOT NULL
    ORDER BY p.fecha_eliminacion ASC
");

// Calcular días restantes y clasificar por urgencia
$pdfs_procesados = [];
foreach ($pdfs_vencer as $pdf) {
    $fecha_eliminacion = new DateTime($pdf['fecha_eliminacion']);
    $fecha_hoy = new DateTime();
    $dias_restantes = $fecha_hoy->diff($fecha_eliminacion)->days;
    
    // Determinar urgencia
    if ($dias_restantes <= 0) {
        $urgencia = 'vencido';
        $clase_urgencia = 'danger';
    } elseif ($dias_restantes <= 1) {
        $urgencia = 'hoy';
        $clase_urgencia = 'urgent';
    } elseif ($dias_restantes <= 3) {
        $urgencia = 'corto';
        $clase_urgencia = 'warning';
    } elseif ($dias_restantes <= 7) {
        $urgencia = 'medio';
        $clase_urgencia = 'info';
    } else {
        $urgencia = 'normal';
        $clase_urgencia = 'success';
    }
    
    $pdfs_procesados[] = [
        'id' => $pdf['id'],
        'titulo' => $pdf['titulo'],
        'pdf' => $pdf['pdf'],
        'fecha_subida' => $pdf['fecha_subida'],
        'fecha_eliminacion' => $pdf['fecha_eliminacion'],
        'dias_restantes' => $dias_restantes,
        'urgencia' => $urgencia,
        'clase_urgencia' => $clase_urgencia,
        'razon_social' => $pdf['razon_social'] ?? 'Sin empresa',
        'ruc' => $pdf['ruc'] ?? '',
        'id_user' => $pdf['id_user']
    ];
}

require_once("app/vista/pdf_vencer.phtml");

