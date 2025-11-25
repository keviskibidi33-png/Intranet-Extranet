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

use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\SMTP;

use PHPMailer\PHPMailer\Exception;



error_reporting(E_ALL);

ini_set('display_errors', '1');

class Inicio extends Conectar

{



  ///////////////////////////////////////////////////////////////////////

  public  function __construct()

  {

    parent::__construct();

  }











  ///////////////////////////////////////////////////////////////////////

  public function pro_visto()

  {

    $sql  = $this->consultas("select * from pdf where id ='" . $_POST['id'] . "'  ");



    $pdf=$sql[0]['pdf'];



    if ($sql[0]['vista'] == '1') {

    } else {

      $v = '1';

      $stmt = $this->datab->prepare("UPDATE pdf set 

		vista=? 

		where id='" . $_POST['id'] . "'	

		");

      $stmt->bindValue(1, $v);

      $stmt->execute();

  



    ///correo de pdf visto



    require 'include/phpmiler/vendor/autoload.php';

    $correo_desde = 'oficinatecnica@geofal.com.pe'; //desde

    $correo_destino = 'oficinatecnica@geofal.com.pe';

    $correo_respuesta = 'oficinatecnica@geofal.com.pe';



    $nombrex = 'Geofal';

    $mail = new PHPMailer;

    $mail->CharSet = "UTF-8";

    $mail->setFrom($correo_desde, $nombrex);

    $mail->addReplyTo($correo_respuesta, $nombrex);

    $mail->addAddress($correo_destino, $nombrex);

    $mail->Subject = 'PDF Visualizado - Página Web';



    $mail->Body = "<div style='width:800;font-family:Century Gothic; margin:0 auto;'><center><img src='https://geofal.com.pe/include/images/logo-dark.png' width='300'></center></div><div style=' width: 800; font-family: Century Gothic; margin: 0 auto; padding: 15px; border: solid 1px #eee; border-radius: 5px;'>Estimado,<br>Se le comunica que el <a href='" . ruta . "publico/img_data/" . $pdf . "'>PDF</a> fue visualizado

    </div>";

    $mail->IsHTML(true);

    $mail->Send();









    exit; 

  

  }

  }





  ///////////////////////////////////////////////////////////////////////

  public function pro_estado()

  {

    $sql  = $this->consultas("select * from pdf where id ='" . $_POST['id'] . "'  ");



    $pdf=$sql[0]['pdf'];





    $e = $_POST['estado'];



    if($e=='1'){

      $es='APROBADO';

    }else{

      $es='OBSERVADO';

    }



    $stmt = $this->datab->prepare("UPDATE pdf set 

      estado=? 

      where id='" . $_POST['id'] . "'	

      ");

    $stmt->bindValue(1, $e);

    $stmt->execute();



    ///correo de tipo de estado APROBADO o OBSERVADO





    require 'include/phpmiler/vendor/autoload.php';

    $correo_desde = 'oficinatecnica@geofal.com.pe'; //desde

    $correo_destino = 'oficinatecnica@geofal.com.pe';

    $correo_respuesta = 'oficinatecnica@geofal.com.pe';



    $nombrex = 'Geofal';

    $mail = new PHPMailer;

    $mail->CharSet = "UTF-8";

    $mail->setFrom($correo_desde, $nombrex);

    $mail->addReplyTo($correo_respuesta, $nombrex);

    $mail->addAddress($correo_destino, $nombrex);

    $mail->Subject = 'Estado de PDF - Página Web';



    $mail->Body = "<div style='width:800;font-family:Century Gothic; margin:0 auto;'><center><img src='https://geofal.com.pe/include/images/logo-dark.png' width='300'></center></div><div style=' width: 800; font-family: Century Gothic; margin: 0 auto; padding: 15px; border: solid 1px #eee; border-radius: 5px;'>Estimado,<br>Se le comunica que el <a href='" . ruta . "publico/img_data/" . $pdf . "'>PDF</a> fue <b>" . $es . "</b> </div>";

    $mail->IsHTML(true);

    $mail->Send();

   





    exit;

  }





  ///////////////////////////////////////////////////////////////////////

  public function mostrar()

  {

    $sql  = $this->consultas("select * from prueba ");

    return $sql;

  }





  ///////////////////////////////////////////////////////////////////////

  public function mostrar_ordenes()

  {

    $sql  = $this->consultas("select * from pdf where id_user ='" . $_SESSION['id_geo'] . "'  order by id desc");

    return $sql;

  }

}

