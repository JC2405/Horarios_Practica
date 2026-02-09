  <?php 

  session_start();
  include_once "vista/modulos/cabecera.php";

  if (isset($_SESSION['rol'])) {

  
    include_once "vista/modulos/inicio.php";
    include_once "vista/modulos/menu.php";

    
    if (isset($_GET["ruta"])) {
      if (
          $_GET["ruta"] == "area" ||
          $_GET["ruta"] == "listarFichas" ||
          $_GET["ruta"] == "listarHorarios" ||
          $_GET["ruta"] == "sedeVista" ||
          $_GET["ruta"] == "fichasInstructor" ||
          $_GET["ruta"] == "crearFicha" ||
          $_GET["ruta"] == "aprendicesCarga" ||
          $_GET["ruta"] == "areas" ||
          $_GET["ruta"] == "crearHorario" ||
          $_GET["ruta"] == "miHorario" ||
          $_GET["ruta"] == "moduloHorarios"||
          $_GET["ruta"] == "tipoFormacionCrearHorario" ||
          $_GET["ruta"] == "listarFichaHorarios" ||
          $_GET["ruta"] == "ambienteSedeMedellin" || 
          $_GET["ruta"] == "eleccionSede" ||
          $_GET["ruta"] == "eleccionAmbiente" ||
          $_GET["ruta"] == "asignacionJornada" ||
          $_GET["ruta"] == "visualizacionFichas" ||
          $_GET["ruta"] == "transversales"
    
      ) {
        include_once "vista/modulos/" . $_GET["ruta"] . ".php";
      } else {
        include_once "vista/modulos/pagina.php"; 
      }
    } else {
      include_once "vista/modulos/pagina.php"; 
    }

  
    include_once "vista/modulos/pie.php";

  } else {
    include_once "vista/modulos/login.php";
    include_once "vista/modulos/pie.php";
  }
