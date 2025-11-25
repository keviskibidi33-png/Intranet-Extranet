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

    if (isset($_POST["eliminar"]) and $_POST["eliminar"] == "eliminar_clientes") {
      $inicio->eliminar_clientes();
    }
  

    if (!empty($_GET['p']) and isset($_GET['p'])) {
        $get_p = $_GET['p'];
    } else {
        $get_p = '0';
    }

    if (!empty($_GET['b']) and isset($_GET['b'])) {
        $get_b = $_GET['b'];
        $get_b2 = $_GET['b'];
    } else {
        $get_b = '0';
        $get_b2 = '';
    }
    $per_page = 24;
    $adjacents  = 4;
    $menu_clientes = '1';
    $contar_clientes_pagi = $inicio->contar_clientes_pagi($get_b);
    $page = (isset($_REQUEST['p']) && !empty($_REQUEST['p'])) ? (int)$_REQUEST['p'] : 1;
    $offset = ($page - 1) * $per_page;
    $numrows = $contar_clientes_pagi;
    $total_pages = ceil($numrows / $per_page);
    $reload = '';

    $mostrar_clientes_pagi = $inicio->mostrar_clientes_pagi($offset, $per_page, $get_b);





    //--------------------------------------//	
    require_once("app/vista/clientes.phtml");
}
