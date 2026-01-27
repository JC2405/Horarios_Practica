<?php
session_start();

include_once "vista/modulos/cabecera.php";

if(isset($_GET["ruta"])){
    if(
        $_GET["ruta"] == "area"
    ) {
        include_once "vista/modulos/" . $_GET["ruta"] . ".php";
    } else {
        include_once "vista/modulos/404.php";
     }
} elseif(isset($_SESSION['rol'])) {
    include_once "vista/modulos/inicio.php";
} else {
    include_once "vista/modulos/login.php";
}

include_once "vista/modulos/pie.php";
