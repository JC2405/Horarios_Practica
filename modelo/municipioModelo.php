<?php
include_once "conexion.php";

class municipioModelo {


   /* ══════════════════════════════════════════════════════════
    MODELO LISTAR MUNICIPIOS REGISTRADOS SIN DEPARTAMENTOS ("FALTANTE A VER SI SE NECESITA DEPARTAMENTAL")
    ══════════════════════════════════════════════════════════ */
  public static function mdlListarMunicipios(){
    $mensaje = array();

    try {
      $objRespuesta = Conexion::Conectar()->prepare("
        SELECT idMunicipio, nombreMunicipio
        FROM municipio
        ORDER BY nombreMunicipio ASC
      ");
      $objRespuesta->execute();
      $listar = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
      $objRespuesta = null;

      $mensaje = array("codigo"=>"200", "listarMunicipios"=>$listar);

    } catch (Exception $e) {
      $mensaje = array("codigo"=>"400", "mensaje"=>$e->getMessage());
    }

    return $mensaje;
  }
}
