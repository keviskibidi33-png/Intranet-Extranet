<?php
/**
 * Visor de PDF para Extranet - Sirve PDFs de forma segura
 * Uso: ?pagina=pdf_viewer&file=nombre_archivo.pdf
 * 
 * La sesión y configuración ya están iniciadas por index.php
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

// Incluir modelo para usar la conexión
include("app/modelo/inicioModelo.php");
$inicio = new Inicio();

// Verificar sesión del cliente
if (!isset($_SESSION['id_geo']) && empty($_SESSION['id_geo'])) {
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
// __DIR__ = intranet/app/controlador/
// Necesitamos: intranet/publico/img_data/ o public_html/publico/img_data/
$base_dir = dirname(dirname(__DIR__)); // intranet/
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

// Verificar que el PDF pertenece al cliente logueado usando el modelo
$pdfs = $inicio->consultas("SELECT id FROM pdf WHERE pdf = '" . addslashes($filename) . "' AND id_user = '" . intval($_SESSION['id_geo']) . "'");

if (empty($pdfs) || count($pdfs) == 0) {
    http_response_code(403);
    die('No tienes permiso para ver este archivo');
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

