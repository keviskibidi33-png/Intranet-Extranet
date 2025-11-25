<?php

// Detectar si estamos en entorno local y usar configuración apropiada
if (file_exists("config/config.local.php") && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')) {
    require_once("config/config.local.php");
} else {
    require_once("config/config.php");
}

include ("config/sistema.php");



if(isset($_GET["pagina"]))

{

    $pagina=$_GET["pagina"];

}else

{

    $pagina="inicio";

}



if(is_file("app/controlador/".$pagina."Con.php"))

{

    require_once("app/controlador/".$pagina."Con.php");

}else

{

    require_once("app/controlador/errorCon.php");

}



?>