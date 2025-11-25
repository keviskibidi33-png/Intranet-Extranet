<?php
/**
 * Script de depuración temporal para el login admin
 * Eliminar después de usar
 */

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

include ("config/sistema.php");

echo "<h1>🔍 DEBUG LOGIN ADMIN</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .section { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; border: 2px solid #007bff; }
    .success { color: #28a745; }
    .error { color: #dc3545; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
</style>";

echo "<div class='section'>";
echo "<h2>📋 Información de Configuración</h2>";
echo "<p><strong>Ruta:</strong> " . ruta . "</p>";
echo "<p><strong>Base de Datos:</strong> " . DB_NAME . "</p>";
echo "<p><strong>Host:</strong> " . HOST . "</p>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>🔐 Verificar Usuario en Base de Datos</h2>";

try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, USER, PASSWORD, $options);
    
    // Buscar usuario admin
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
    $stmt->bindValue(1, 'admin', PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "<p class='success'>✓ Usuario 'admin' encontrado</p>";
        echo "<table>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        foreach ($usuario as $campo => $valor) {
            if ($campo == 'clave') {
                echo "<tr><td><strong>{$campo}</strong></td><td>*** (oculto) - Longitud: " . strlen($valor) . "</td></tr>";
            } else {
                echo "<tr><td><strong>{$campo}</strong></td><td>{$valor}</td></tr>";
            }
        }
        echo "</table>";
        
        // Probar validación de contraseña
        echo "<h3>🧪 Prueba de Validación:</h3>";
        $clave_test = 'admin123';
        $clave_valida = false;
        
        if (function_exists('password_verify') && password_verify($clave_test, $usuario['clave'])) {
            echo "<p class='success'>✓ Contraseña válida (hash verificado)</p>";
            $clave_valida = true;
        } elseif ($usuario['clave'] === $clave_test) {
            echo "<p class='success'>✓ Contraseña válida (texto plano)</p>";
            $clave_valida = true;
        } else {
            echo "<p class='error'>✗ Contraseña NO válida</p>";
            echo "<p>Contraseña en BD: " . substr($usuario['clave'], 0, 20) . "...</p>";
            echo "<p>Contraseña probada: {$clave_test}</p>";
        }
        
    } else {
        echo "<p class='error'>✗ Usuario 'admin' NO encontrado</p>";
        echo "<p>Ejecuta primero: <a href='crear_usuario_admin_prueba.php'>crear_usuario_admin_prueba.php</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "</div>";

echo "<div class='section'>";
echo "<h2>📝 Información de Sesión</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Status:</strong> " . (session_status() === PHP_SESSION_ACTIVE ? 'Activa' : 'Inactiva') . "</p>";
if (isset($_SESSION['id_geofal'])) {
    echo "<p class='success'><strong>✓ Sesión iniciada:</strong> id_geofal = " . $_SESSION['id_geofal'] . "</p>";
} else {
    echo "<p class='error'><strong>✗ No hay sesión iniciada</strong></p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>🌐 Información de Request</h2>";
echo "<p><strong>Método:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>URL:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<h3>POST Data:</h3>";
if (!empty($_POST)) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "<p>No hay datos POST</p>";
}
echo "<h3>GET Data:</h3>";
if (!empty($_GET)) {
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";
} else {
    echo "<p>No hay datos GET</p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>🔗 URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='" . ruta . "login'>Login Admin</a></li>";
echo "<li><a href='" . ruta . "inicio'>Panel Admin (requiere login)</a></li>";
echo "<li><a href='crear_usuario_admin_prueba.php'>Crear Usuario Admin</a></li>";
echo "</ul>";
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usar.</p>";
?>

