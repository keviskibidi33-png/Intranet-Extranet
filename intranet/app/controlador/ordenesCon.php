<?php

// Asegurar que sistema.php esté incluido antes del modelo
if (!class_exists('Conectar')) {
    // Intentar incluir sistema.php desde diferentes rutas
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

include ("app/modelo/inicioModelo.php");

include("keys.php");

$inicio = new Inicio();



if(isset($_SESSION['id_geo'])){





 

  if(isset($_POST["pro_visto"]) and $_POST["pro_visto"]=="ok"){

    $inicio->pro_visto();

    }

    

     

  if(isset($_POST["pro_estado"]) and $_POST["pro_estado"]=="ok"){

    $inicio->pro_estado();

    }

    

    





$mostrar_ordenes=$inicio->mostrar_ordenes();

// Obtener información del cliente logueado para mostrar su razón social
$razon_social = '';
if (isset($_SESSION['id_geo']) && !empty($_SESSION['id_geo'])) {
  $cliente_info = $inicio->consultas("SELECT razon_social FROM clientes WHERE id = '" . $_SESSION['id_geo'] . "'");
  if (!empty($cliente_info) && isset($cliente_info[0]['razon_social'])) {
    $razon_social = $cliente_info[0]['razon_social'];
  }
}

require_once("app/vista/ordenes_2.phtml");





}else{



  header("Location:" . ruta . 'cliente');



}



?>