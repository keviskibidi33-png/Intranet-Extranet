<?php

include("app/modelo/inicioModelo.php");

$inicio = new Inicio();

///////////////////////////////////////////////////////////
if (!isset($_SESSION['id_geofal']) and empty($_SESSION['id_geofal'])) {
    if (isset($_COOKIE['id'])) {
        $_SESSION['id_geofal'] = $_COOKIE['id'];
    }
}
///////////////////////////////////////////////////////////


if (!isset($_SESSION['id_geofal'])) {
    header("Location:" . ruta . 'login');
} else {
    //--------------------------------------//


    if (isset($_POST["for_perfil_editar"]) and $_POST["for_perfil_editar"] == "ok") {
      $inicio->for_perfil_editar();
    }
  
    

 
    $mostrar_user = $inicio->mostrar_user();





    //--------------------------------------//	
    require_once("app/vista/perfil.phtml");
}
