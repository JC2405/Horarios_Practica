<?php
include_once "../modelo/municipioModelo.php";

class municipioControlador {

  public function ctrListarMunicipios(){
    $objRespuesta = municipioModelo::mdlListarMunicipios();
    echo json_encode($objRespuesta);
  }
}

if(isset($_POST["listarMunicipios"])){
  $obj = new municipioControlador();
  $obj->ctrListarMunicipios();
}
