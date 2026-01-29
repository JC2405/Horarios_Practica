<?php 

session_start();
include_once "vista/modulos/cabecera.php";

if (isset($_SESSION['rol'])) {

  // Abre sidebar+topbar+<main>
  include_once "vista/modulos/inicio.php";

  // Carga módulo dentro del <main>
  if (isset($_GET["ruta"])) {
    if (
        $_GET["ruta"] == "area" ||
         $_GET["ruta"] == "listarFichas"
    ) {
      include_once "vista/modulos/" . $_GET["ruta"] . ".php";
    } else {
      include_once "vista/modulos/404.php";
    }
  } else {
    include_once "vista/modulos/pagina.php"; // o inicio dashboard real
  }

  // pie.php ahora Cierra </main></div> + scripts
  include_once "vista/modulos/pie.php";

} else {
  include_once "vista/modulos/login.php";
  include_once "vista/modulos/pie.php";
}