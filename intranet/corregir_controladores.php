<?php
/**
 * Script para agregar la verificación de Conectar en todos los controladores
 * Ejecutar una vez y luego eliminar
 */

$baseDir = __DIR__ . '/app/controlador';
$controladores = glob($baseDir . '/*Con.php');

$codigoVerificacion = '
// Asegurar que sistema.php esté incluido antes del modelo
if (!class_exists(\'Conectar\')) {
    // Intentar incluir sistema.php desde diferentes rutas
    $rutasSistema = [
        __DIR__ . \'/../../config/sistema.php\',
        \'config/sistema.php\',
        \'../config/sistema.php\'
    ];
    
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            break;
        }
    }
}
';

$corregidos = 0;
$errores = [];

foreach ($controladores as $archivo) {
    $contenido = file_get_contents($archivo);
    
    // Solo corregir si incluye un modelo y no tiene ya la verificación
    if ((strpos($contenido, 'include') !== false || strpos($contenido, 'require') !== false) &&
        (strpos($contenido, 'Modelo.php') !== false || strpos($contenido, 'loginModelo') !== false) &&
        strpos($contenido, 'class_exists(\'Conectar\')') === false) {
        
        // Buscar la primera línea después de <?php
        if (preg_match('/^<\?php\s*\n/', $contenido)) {
            $nuevoContenido = preg_replace(
                '/^(<\?php\s*\n)/',
                '$1' . $codigoVerificacion . "\n",
                $contenido,
                1
            );
            
            if (file_put_contents($archivo, $nuevoContenido)) {
                $corregidos++;
                echo "✅ Corregido: " . basename($archivo) . "\n";
            } else {
                $errores[] = basename($archivo);
            }
        }
    }
}

echo "\n📊 Resumen:\n";
echo "✅ Controladores corregidos: $corregidos\n";
if (!empty($errores)) {
    echo "❌ Errores: " . implode(', ', $errores) . "\n";
}

echo "\n⚠️ IMPORTANTE: Elimina este archivo después de usarlo.\n";
?>

