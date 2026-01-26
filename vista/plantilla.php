<?php

include_once "vista/modulos/cabecera.php";

if(isset($_GET["ruta"])){
    if(
        $_GET["ruta"] == "area"
    ) {
        include_once "vista/modulos/" . $_GET["ruta"] . ".php";
    } else { 
        include_once "vista/modulos/404.php";
     }
} else {
    include_once "vista/modulos/pagina.php";
}

include_once "vista/modulos/pie.php";
