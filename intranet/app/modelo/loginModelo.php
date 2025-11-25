<?php

// Asegurar que sistema.php esté incluido (contiene la clase Conectar)
if (!class_exists('Conectar')) {
    // Intentar incluir desde diferentes rutas posibles
    $rutasSistema = [
        __DIR__ . '/../../config/sistema.php',  // Desde app/modelo/ hacia config/
        __DIR__ . '/../../../config/sistema.php' // Si está más profundo
    ];
    
    $sistemaIncluido = false;
    foreach ($rutasSistema as $ruta) {
        if (file_exists($ruta)) {
            require_once($ruta);
            $sistemaIncluido = true;
            break;
        }
    }
    
    if (!$sistemaIncluido && !class_exists('Conectar')) {
        die('Error: No se pudo encontrar config/sistema.php. La clase Conectar no está disponible.');
    }
}

class Logininicio extends Conectar

{

  public function __construct()

  {

    parent::__construct();

  }



 



  public function login()

  {





    try {



      if (empty($_POST['ruc']) and empty($_POST['clave'])) {

        $djs = array('es' => '2');

        echo json_encode($djs);

        exit;

      }



      // Buscar usuario por RUC
      $stmt = $this->datab->prepare("SELECT * FROM clientes WHERE ruc=?");
      $stmt->bindValue(1, htmlentities($_POST['ruc']), PDO::PARAM_STR);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($data) {
          // Verificar contraseña (soporta texto plano y hash)
          $clave_valida = false;
          
          // Si la contraseña en BD está encriptada (empieza con $2y$ o similar)
          if (password_verify($_POST['clave'], $data['clave'])) {
              $clave_valida = true;
          }
          // Si la contraseña está en texto plano
          elseif ($data['clave'] === $_POST['clave']) {
              $clave_valida = true;
          }

          if ($clave_valida) {
              $_SESSION['id_geo'] = $data['id'];
              echo json_encode(['es' => '1']);
              exit;
          } else {
              echo json_encode(['es' => '2']);
              exit;
          }
      } else {
          echo json_encode(['es' => '2']);
          exit;
      }

      // if ($count != '') {



      //   $_SESSION['id_geo'] = $data['id']; 



      //   $djs = array('es' => '1');

      //   echo json_encode($djs);

      //   exit;

      // } else {

      //   $djs = array('es' => '2');

      //   echo json_encode($djs);

      //   exit;

      // }

    } catch (PDOException $e) {

      echo '{"error":{"text":' . $e->getMessage() . '}}';

    }

  }

}

