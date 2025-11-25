<?php
/**
 * Script para crear usuario admin de prueba
 * Ejecutar desde: http://localhost/public_html/intranet/admin/crear_usuario_admin_prueba.php
 * Luego eliminar este archivo por seguridad
 */

echo "<h1>👨‍💼 CREAR USUARIO ADMIN DE PRUEBA</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { background: #d4edda; border: 2px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .error { background: #f8d7da; border: 2px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .info { background: #d1ecf1; border: 2px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; }
    th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #007bff; color: white; }
</style>";

// Incluir configuración
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

try {
    // Establecer conexión PDO directamente
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, USER, PASSWORD, $options);
    
    echo "<div class='info'>";
    echo "<h2>📋 Información de Configuración</h2>";
    echo "<p><strong>Base de Datos:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Host:</strong> " . HOST . "</p>";
    echo "</div>";
    
    // Verificar si la tabla usuarios existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    $tablaExiste = $stmt->rowCount() > 0;
    
    if (!$tablaExiste) {
        echo "<div class='error'>";
        echo "<h2>❌ Error: Tabla 'usuarios' no existe</h2>";
        echo "<p>La tabla <code>usuarios</code> no existe en la base de datos <code>" . DB_NAME . "</code></p>";
        echo "<p><strong>Acción requerida:</strong> Crear la tabla usuarios primero.</p>";
        echo "</div>";
    } else {
        echo "<div class='success'>";
        echo "<p>✓ Tabla 'usuarios' existe</p>";
        echo "</div>";
        
        // Verificar estructura de la tabla
        $stmt = $pdo->query("DESCRIBE usuarios");
        $columnas = $stmt->fetchAll();
        
        echo "<div class='info'>";
        echo "<h3>📊 Estructura de la tabla 'usuarios':</h3>";
        echo "<table>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columnas as $col) {
            echo "<tr>";
            echo "<td><strong>{$col['Field']}</strong></td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
        
        // Verificar si el usuario admin ya existe
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
        $stmt->bindValue(1, 'admin', PDO::PARAM_STR);
        $stmt->execute();
        $usuario_existente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario_existente) {
            echo "<div class='info'>";
            echo "<h2>ℹ️ Usuario 'admin' ya existe</h2>";
            echo "<table>";
            echo "<tr><th>Campo</th><th>Valor</th></tr>";
            foreach ($usuario_existente as $campo => $valor) {
                if ($campo == 'clave') {
                    echo "<tr><td><strong>{$campo}</strong></td><td>*** (oculto por seguridad)</td></tr>";
                } else {
                    echo "<tr><td><strong>{$campo}</strong></td><td>{$valor}</td></tr>";
                }
            }
            echo "</table>";
            echo "</div>";
            
            // Actualizar contraseña
            $stmt = $pdo->prepare("UPDATE usuarios SET clave = ? WHERE dni = 'admin'");
            $stmt->bindValue(1, 'admin123', PDO::PARAM_STR);
            $stmt->execute();
            
            echo "<div class='success'>";
            echo "<h2>✅ Contraseña actualizada</h2>";
            echo "<p><strong>Usuario:</strong> admin</p>";
            echo "<p><strong>Contraseña:</strong> admin123</p>";
            echo "</div>";
        } else {
            // Crear nuevo usuario
            // Primero verificar qué campos tiene la tabla
            $camposDisponibles = array_column($columnas, 'Field');
            
            $dni = 'admin';
            $clave = 'admin123';
            
            // Construir query según campos disponibles
            if (in_array('dni', $camposDisponibles) && in_array('clave', $camposDisponibles)) {
                $sql = "INSERT INTO usuarios (dni, clave";
                $valores = "VALUES (?, ?";
                $params = [$dni, $clave];
                
                // Agregar campos opcionales si existen
                if (in_array('nombre', $camposDisponibles)) {
                    $sql .= ", nombre";
                    $valores .= ", ?";
                    $params[] = 'Administrador';
                }
                if (in_array('apellido', $camposDisponibles)) {
                    $sql .= ", apellido";
                    $valores .= ", ?";
                    $params[] = 'Sistema';
                }
                if (in_array('correo', $camposDisponibles)) {
                    $sql .= ", correo";
                    $valores .= ", ?";
                    $params[] = 'admin@geofal.com.pe';
                }
                
                $sql .= ") " . $valores . ")";
                
                $stmt = $pdo->prepare($sql);
                for ($i = 0; $i < count($params); $i++) {
                    $stmt->bindValue($i + 1, $params[$i], PDO::PARAM_STR);
                }
                $stmt->execute();
                
                echo "<div class='success'>";
                echo "<h2>✅ Usuario 'admin' creado correctamente</h2>";
                echo "<p><strong>Usuario (DNI):</strong> admin</p>";
                echo "<p><strong>Contraseña:</strong> admin123</p>";
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "<h2>❌ Error: Campos requeridos no encontrados</h2>";
                echo "<p>La tabla 'usuarios' no tiene los campos 'dni' o 'clave'</p>";
                echo "<p><strong>Campos disponibles:</strong> " . implode(', ', $camposDisponibles) . "</p>";
                echo "</div>";
            }
        }
        
        // Mostrar todos los usuarios existentes
        $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 10");
        $usuarios = $stmt->fetchAll();
        
        if (!empty($usuarios)) {
            echo "<div class='info'>";
            echo "<h3>👥 Usuarios existentes en la tabla:</h3>";
            echo "<table>";
            echo "<tr>";
            foreach (array_keys($usuarios[0]) as $campo) {
                if ($campo != 'clave') {
                    echo "<th>{$campo}</th>";
                }
            }
            echo "</tr>";
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                foreach ($usuario as $campo => $valor) {
                    if ($campo != 'clave') {
                        echo "<td>{$valor}</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }
    
    echo "<div class='success'>";
    echo "<h2>🔐 Credenciales para Login Admin:</h2>";
    echo "<p><strong>DNI/Usuario:</strong> <code>admin</code></p>";
    echo "<p><strong>Contraseña:</strong> <code>admin123</code></p>";
    echo "<p><strong>URL Login:</strong> <a href='" . ruta . "login' target='_blank'>" . ruta . "login</a></p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<h2>❌ Error de conexión a la base de datos</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Verifica:</strong></p>";
    echo "<ul>";
    echo "<li>Que la base de datos <code>" . DB_NAME . "</code> exista</li>";
    echo "<li>Que el usuario <code>" . USER . "</code> tenga permisos</li>";
    echo "<li>Que la contraseña sea correcta</li>";
    echo "<li>Que MySQL esté corriendo</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<div class='info' style='margin-top: 30px;'>";
echo "<h3>📝 Notas:</h3>";
echo "<ul>";
echo "<li>Este script crea/actualiza un usuario de prueba con DNI='admin' y clave='admin123'</li>";
echo "<li>Después de probar, puedes cambiar la contraseña desde el panel admin</li>";
echo "<li><strong>IMPORTANTE:</strong> Elimina este archivo después de usarlo por seguridad</li>";
echo "</ul>";
echo "</div>";

echo "<p style='color: red; margin-top: 30px;'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo (crear_usuario_admin_prueba.php) después de usarlo por seguridad.</p>";
?>

