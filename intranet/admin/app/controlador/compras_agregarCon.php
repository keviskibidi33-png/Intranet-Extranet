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


  if (isset($_POST["for_compras_agregar"]) and $_POST["for_compras_agregar"] == "ok") {
    $inicio->for_compras_agregar();
  }


  //--------------------------------------//
  require_once("app/vista/compras_agregar.phtml");
}
