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

include("app/modelo/loginModelo.php");

$logininicio = new Logininicio();

 



 

if(isset($_POST["login"]) and $_POST["login"]=="ok"){

  $logininicio->login();

  }

  

  

  

  

  

  if(isset($_POST["registro"]) and $_POST["registro"]=="ok"){

  $logininicio->registro();

  }

   



 

// Solo redirigir si el usuario ya está logueado (no en petición AJAX de login)
if(isset($_SESSION['id_geo']) and !empty($_SESSION['id_geo']) and !isset($_POST["login"])){
  header("Location:" . ruta . 'ordenes');
  exit;
}

 

  //--------------------------------------//







  //--------------------------------------//	

  require_once("app/vista/login.phtml");



?>