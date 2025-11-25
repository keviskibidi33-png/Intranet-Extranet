<?php

include("app/modelo/inicioModelo.php");

$inicio = new Inicio();

///////////////////////////////////////////////////////////
if (!isset($_SESSION['id_geofal']) and empty($_SESSION['id_geofal'])) {
  if (isset($_COOKIE['id_geofal'])) {
    $_SESSION['id_geofal'] = $_COOKIE['id_geofal'];
  }
}
///////////////////////////////////////////////////////////
if (!isset($_SESSION['id_geofal'])) {
  header("Location:" . ruta . 'login');
} else {
  //--------------------------------------//	



  if (isset($_POST["for_pdf_editar"]) and $_POST["for_pdf_editar"] == "ok") {
    $inicio->for_pdf_editar();
  }

  $mostrar_pdf_id = $inicio->mostrar_pdf_id($_GET['id']);
 

  //--------------------------------------//
  require_once("app/vista/pdf_editar.phtml");
}
