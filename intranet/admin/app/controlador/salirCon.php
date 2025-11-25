<?php

session_start();

session_destroy();

setcookie('id_geofal','',time() + (200 * 24 * 60 * 60),'/');

header("Location:".ruta.'login/');
?>