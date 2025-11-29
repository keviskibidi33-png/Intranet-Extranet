<?php



/*include ("app/modelo/inicioModelo.php");

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



  require_once("app/vista/ordenes.phtml");



}else{



  header("Location:" . ruta . 'inicio');



}*/



?>

<?php



include ("app/modelo/inicioModelo.php");

include("keys.php");

  $inicio = new Inicio();



  





  if(!isset($_SESSION['id_geo'])){

    //header("Location:".ruta.'login');

    require_once("app/vista/login_2.phtml");

  }else{

    header("Location:" . ruta . 'ordenes');	

  }


?>
