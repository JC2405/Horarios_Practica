<?php

include_once "../modelo/programaModelo.php";


class ProgramaControlador {

    public $idPrograma;
    public $nombre;
    public $codigo;
    public $idTipoFormacion;
    public $version;
    public $estado;


    public function crtListarPrograma(){
        $objRespuesta = programaModelo::mdlListarPrograma();
        echo json_encode($objRespuesta);
    }
}


if (isset($_POST["listarPrograma"])) {
    $objRespuesta = new ProgramaControlador();
    $objRespuesta -> crtListarPrograma();
}