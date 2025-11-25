<?php

// Asegurar que sistema.php esté incluido (contiene la clase Conectar)
if (!class_exists('Conectar')) {
    $rutasSistema = [
        __DIR__ . '/../../config/sistema.php',
        __DIR__ . '/../../../config/sistema.php'
    ];
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            break;
        }
    }
    if (!class_exists('Conectar')) {
        die('Error: No se pudo encontrar config/sistema.php. La clase Conectar no está disponible.');
    }
}

class Logininicio extends Conectar

{

  public function __construct()

  {

    parent::__construct();

  }





  /**
   * Procesa el login del usuario administrador
   * 
   * @return void Redirige según el resultado del login
   */
  public function login()
  {
    try {
      // Validar que los campos no estén vacíos
      $dni = isset($_POST['dni']) ? trim($_POST['dni']) : '';
      $clave = isset($_POST['clave']) ? trim($_POST['clave']) : '';
      
      if (empty($dni) || empty($clave)) {
        header("Location:" . ruta . 'login?error=2');
        exit;
      }
      
      // Buscar usuario por DNI
      $stmt = $this->datab->prepare("SELECT * FROM usuarios WHERE dni = ? LIMIT 1");
      $stmt->bindValue(1, htmlspecialchars($dni, ENT_QUOTES, 'UTF-8'), PDO::PARAM_STR);
      $stmt->execute();
      $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
      
      // Verificar si el usuario existe
      if (!$usuario) {
        header("Location:" . ruta . 'login?error=1');
        exit;
      }
      
      // Verificar contraseña (soporta texto plano y hash)
      $clave_valida = false;
      
      // Si la contraseña en BD está encriptada (hash bcrypt/argon2)
      if (function_exists('password_verify') && password_verify($clave, $usuario['clave'])) {
        $clave_valida = true;
      }
      // Si la contraseña está en texto plano (compatibilidad con datos antiguos)
      elseif ($usuario['clave'] === $clave) {
        $clave_valida = true;
      }
      
      // Si la contraseña es válida, iniciar sesión
      if ($clave_valida) {
        $_SESSION['id_geofal'] = $usuario['id'];
        setcookie('id_geofal', $usuario['id'], time() + (200 * 24 * 60 * 60), '/');
        header("Location:" . ruta . 'inicio');
        exit;
      } else {
        // Contraseña incorrecta
        header("Location:" . ruta . 'login?error=1');
        exit;
      }
      
    } catch (PDOException $e) {
      // Error de base de datos
      $mensaje_error = 'Error de conexión. Por favor, intenta nuevamente.';
      header("Location:" . ruta . 'login?error=1&msg=' . urlencode($mensaje_error));
      exit;
    }
  }

}

