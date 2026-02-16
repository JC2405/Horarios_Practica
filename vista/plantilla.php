  <?php 

  session_start();
  include_once "vista/modulos/cabecera.php";

  if (isset($_SESSION['rol'])) {

  
    include_once "vista/modulos/inicio.php";
    include_once "vista/modulos/menu.php";

    
    if (isset($_GET["ruta"])) {
      if (
          $_GET["ruta"] == "area" ||
          $_GET["ruta"] == "ficha" ||
          $_GET["ruta"] == "crearHorario" ||
          $_GET["ruta"] == "programa" ||
          $_GET["ruta"] == "sede" ||
          $_GET["ruta"] == "tipoPrograma" ||
          $_GET["ruta"] == "tipoContrato" ||
          $_GET["ruta"] == "instructor" || 
          $_GET["ruta"] == "" ||
          $_GET["ruta"] == "" ||
          $_GET["ruta"] == "" ||
          $_GET["ruta"] == ""
    
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
