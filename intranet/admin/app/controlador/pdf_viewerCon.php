<?php
/**
 * Visor de PDF - Sirve PDFs de forma segura
 * Uso: ?pagina=pdf_viewer&file=nombre_archivo.pdf
 * 
 * La sesión y configuración ya están iniciadas por index.php
 */

// Verificar sesión
if (!isset($_SESSION['id_geofal']) && empty($_SESSION['id_geofal'])) {
    if (isset($_COOKIE['id_geofal'])) {
        $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
    }
}

if (!isset($_SESSION['id_geofal'])) {
    http_response_code(403);
    die('Acceso denegado');
}

// Obtener nombre del archivo
$filename = isset($_GET['file']) ? basename($_GET['file']) : '';

if (empty($filename)) {
    http_response_code(400);
    die('Nombre de archivo no especificado');
}

// Validar que sea un PDF
if (pathinfo($filename, PATHINFO_EXTENSION) !== 'pdf') {
    http_response_code(400);
    die('Solo se permiten archivos PDF');
}

// Construir ruta del archivo
// __DIR__ = intranet/admin/app/controlador/
// Necesitamos: intranet/publico/img_data/
$base_dir = dirname(dirname(dirname(__DIR__))); // intranet/
$file_path = $base_dir . '/publico/img_data/' . $filename;

// Si no existe, buscar en ubicación antigua
if (!file_exists($file_path)) {
    $base_dir_old = dirname($base_dir); // public_html/
    $file_path = $base_dir_old . '/publico/img_data/' . $filename;
}

// Verificar que el archivo existe
if (!file_exists($file_path)) {
    http_response_code(404);
    die('Archivo no encontrado: ' . $filename);
}

// Verificar que es un archivo (no directorio)
if (!is_file($file_path)) {
    http_response_code(400);
    die('Ruta inválida');
}

// Enviar headers para PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Leer y enviar el archivo
readfile($file_path);
exit;

