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



    if (isset($_POST["eliminar"]) and $_POST["eliminar"] == "eliminar_pdf") {

      $inicio->eliminar_pdf();

    }

    // Procesar agregar PDF
    if (isset($_POST["for_pdf_agregar"]) and $_POST["for_pdf_agregar"] == "ok") {
      $inicio->for_pdf_agregar();
    }

    // Procesar editar PDF
    if (isset($_POST["for_pdf_editar"]) and $_POST["for_pdf_editar"] == "ok") {
      $inicio->for_pdf_editar();
    }

    if (!empty($_GET['id']) and isset($_GET['id'])) {

        $id = $_GET['id'];

    } else {

        $id = '0';

    }



    if (!empty($_GET['b']) and isset($_GET['b'])) {

        $get_b = $_GET['b'];

        $get_b2 = $_GET['b'];

    } else {

        $get_b = '0';

        $get_b2 = '';

    }

   

    $mostrar_pdf_pagi = $inicio->mostrar_pdf_pagi($get_b ,$id );

    // Obtener razón social del cliente para mostrar en la vista
    $razon_social = '';
    if (!empty($id) && $id != '0') {
      $cliente_info = $inicio->mostrar_clientes_id($id);
      if (!empty($cliente_info) && isset($cliente_info[0]['razon_social'])) {
        $razon_social = $cliente_info[0]['razon_social'];
      }
    }

    //--------------------------------------//	

    require_once("app/vista/pdf.phtml");

}
