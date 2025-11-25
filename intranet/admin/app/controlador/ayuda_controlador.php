<?php
include ("modelo/inicioModelo.php");

$inicio = new Inicio();

$mostrar_noticias=$inicio->mostrar_noticias();  

if(isset($_POST["pasarela"]) and $_POST["pasarela"]=="ok"){
$inicio->agregar_();
 }
 

if (Conectar::valida_rutx($r)) {
echo "rut valido";
}else{
echo "rut invalido";
}


require_once("vista/index.phtml");
?>