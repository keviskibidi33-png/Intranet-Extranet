<?php
/**
 * Script para actualizar la contraseña del usuario admin
 * Ejecutar desde: http://localhost/public_html/intranet/admin/actualizar_contraseña_admin.php
 * Luego eliminar este archivo por seguridad
 */

echo "<h1>🔐 ACTUALIZAR CONTRASEÑA ADMIN</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 5px; }
    form { background: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
    input[type='text'], input[type='password'] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
    button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background: #0056b3; }
</style>";

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

// Si se envió el formulario
if (isset($_POST['actualizar'])) {
    $dni = $_POST['dni'] ?? 'admin';
    $nueva_clave = $_POST['nueva_clave'] ?? '';
    
    if (empty($nueva_clave)) {
        echo "<div class='error'>❌ Por favor ingresa una contraseña</div>";
    } else {
        try {
            $dsn = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, USER, PASSWORD, $options);
            
            // Actualizar contraseña
            $stmt = $pdo->prepare("UPDATE usuarios SET clave = ? WHERE dni = ?");
            $stmt->bindValue(1, $nueva_clave, PDO::PARAM_STR);
            $stmt->bindValue(2, $dni, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                echo "<div class='success'>";
                echo "<h2>✅ Contraseña actualizada correctamente</h2>";
                echo "<p><strong>Usuario:</strong> {$dni}</p>";
                echo "<p><strong>Nueva contraseña:</strong> {$nueva_clave}</p>";
                echo "<p><a href='" . ruta . "login'>← Ir al login</a></p>";
                echo "</div>";
            } else {
                echo "<div class='error'>❌ No se encontró el usuario '{$dni}'</div>";
            }
            
        } catch (PDOException $e) {
            echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Mostrar formulario
echo "<div class='info'>";
echo "<h2>📋 Actualizar Contraseña</h2>";
echo "<p>Este script te permite cambiar la contraseña del usuario admin.</p>";
echo "</div>";

echo "<form method='POST'>";
echo "<label><strong>DNI/Usuario:</strong></label>";
echo "<input type='text' name='dni' value='admin' required>";
echo "<label><strong>Nueva Contraseña:</strong></label>";
echo "<input type='password' name='nueva_clave' placeholder='Ingresa la nueva contraseña' required>";
echo "<button type='submit' name='actualizar'>Actualizar Contraseña</button>";
echo "</form>";

// Mostrar usuarios existentes
try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, USER, PASSWORD, $options);
    
    $stmt = $pdo->query("SELECT dni, id FROM usuarios LIMIT 10");
    $usuarios = $stmt->fetchAll();
    
    if (!empty($usuarios)) {
        echo "<div class='info'>";
        echo "<h3>👥 Usuarios existentes:</h3>";
        echo "<ul>";
        foreach ($usuarios as $usuario) {
            echo "<li><strong>{$usuario['dni']}</strong> (ID: {$usuario['id']})</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Error al conectar: " . $e->getMessage() . "</div>";
}

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo.</p>";
?>

