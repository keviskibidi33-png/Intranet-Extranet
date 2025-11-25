<?php
/**
 * Script para crear usuario de prueba automáticamente
 * Ejecutar una vez desde el navegador: http://localhost/public_html/intranet/crear_usuario_prueba.php
 * Luego eliminar este archivo por seguridad
 */

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

require_once("config/sistema.php");

try {
    // Crear conexión PDO directamente (ya que $datab es protected)
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8", USER, PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Verificar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE ruc = ?");
    $stmt->bindValue(1, 'admin', PDO::PARAM_STR);
    $stmt->execute();
    $usuario_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_existente) {
        // Actualizar contraseña si existe
        $stmt = $pdo->prepare("UPDATE clientes SET clave = ? WHERE ruc = 'admin'");
        $stmt->bindValue(1, 'admin123', PDO::PARAM_STR);
        $stmt->execute();
        echo "<h2>✓ Usuario 'admin' actualizado correctamente</h2>";
        echo "<p>Contraseña: <strong>admin123</strong></p>";
    } else {
        // Crear nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO clientes (ruc, razon_social, telefono, direccion, representante, clave, correo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, 'admin', PDO::PARAM_STR);
        $stmt->bindValue(2, 'Usuario Administrador', PDO::PARAM_STR);
        $stmt->bindValue(3, '999999999', PDO::PARAM_STR);
        $stmt->bindValue(4, 'Dirección de prueba', PDO::PARAM_STR);
        $stmt->bindValue(5, 'Admin', PDO::PARAM_STR);
        $stmt->bindValue(6, 'admin123', PDO::PARAM_STR);
        $stmt->bindValue(7, 'admin@geofal.com.pe', PDO::PARAM_STR);
        $stmt->execute();
        echo "<h2>✓ Usuario 'admin' creado correctamente</h2>";
        echo "<p>Usuario: <strong>admin</strong></p>";
        echo "<p>Contraseña: <strong>admin123</strong></p>";
    }
    
    echo "<hr>";
    echo "<p><a href='index.php'>← Volver al inicio</a></p>";
    echo "<p style='color: red;'><strong>IMPORTANTE:</strong> Elimina este archivo (crear_usuario_prueba.php) después de usarlo por seguridad.</p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Error al crear usuario</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Verifica que:</p>";
    echo "<ul>";
    echo "<li>La base de datos existe y está configurada correctamente</li>";
    echo "<li>La tabla 'clientes' existe</li>";
    echo "<li>Los campos requeridos están presentes en la tabla</li>";
    echo "</ul>";
}
?>

