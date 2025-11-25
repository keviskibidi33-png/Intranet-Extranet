<?php

include("app/modelo/inicioModelo.php");

$inicio = new Inicio();

///////////////////////////////////////////////////////////
if (!isset($_SESSION['id_geofal']) and empty($_SESSION['id_geofal'])) {
  if (isset($_COOKIE['id_geofal'])) {
    $_SESSION['id_geofal'] = $_COOKIE['id'];
  }
}
///////////////////////////////////////////////////////////
if (!isset($_SESSION['id_geofal'])) {
  header("Location:" . ruta . 'login');
} else {
  //--------------------------------------//	


  if (isset($_POST["for_pdf_agregar"]) and $_POST["for_pdf_agregar"] == "ok") {
    $inicio->for_pdf_agregar();
  }


  //--------------------------------------//
  require_once("app/vista/pdf_agregar.phtml");
}
