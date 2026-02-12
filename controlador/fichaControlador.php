    <?php

    include_once "../modelo/fichaModelo.php";

    class fichaControlador {

        public $idFicha;
        public $codigoFicha;
        public $idPrograma;
        public $idAmbiente;
        public $estado;
        public $jornada;
        public $fechaInicio;
        public $fechaFin;
        public $idMunicipio;
        public $idSede;

        
        public function ctrListarFicha(){
            $objRespuesta = fichaModelo::mdlListarFicha();
            echo json_encode($objRespuesta);
        }

    
        public function ctrListarMunicipios(){
            $objRespuesta = fichaModelo::mdlListarMunicipios();
            echo json_encode($objRespuesta);
        }


        public function ctrListarSedesPorMunicipio(){
            $objRespuesta = fichaModelo::mdlListarSedesPorMunicipio($this->idMunicipio);
            echo json_encode($objRespuesta);
        }

    
        public function ctrListarAmbientesPorSede(){
            $objRespuesta = fichaModelo::mdlListarAmbientesPorSede($this->idSede);
            echo json_encode($objRespuesta);
        }

    
        public function ctrListarProgramas(){
            $objRespuesta = fichaModelo::mdlListarProgramas();
            echo json_encode($objRespuesta);
        }


        public function ctrRegistrarFicha(){
        $objRespuesta = fichaModelo::mdlRegistrarFicha(
            $this->codigoFicha,$this->idPrograma,$this->idAmbiente,$this->estado,$this->jornada,$this->fechaInicio,$this->fechaFin);
        echo json_encode($objRespuesta);
        }
        
    }



    // Listar fichas
    if (isset($_POST["listarFicha"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->ctrListarFicha();
    }

    // Listar municipios
    if (isset($_POST["listarMunicipios"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->ctrListarMunicipios();
    }


    if (isset($_POST["listarSedesPorMunicipio"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->idMunicipio = $_POST["idMunicipio"];
        $objRespuesta->ctrListarSedesPorMunicipio();
    }


    if (isset($_POST["listarAmbientesPorSede"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->idSede = $_POST["idSede"];
        $objRespuesta->ctrListarAmbientesPorSede();
    }


    if (isset($_POST["listarProgramas"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->ctrListarProgramas();
    }   


    if (isset($_POST["registrarFicha"])) {
        $objRespuesta = new fichaControlador();
        $objRespuesta->codigoFicha = $_POST["codigoFicha"];
        $objRespuesta->idPrograma = $_POST["idPrograma"];
        $objRespuesta->idAmbiente = $_POST["idAmbiente"];
        $objRespuesta->estado = $_POST["estado"];
        $objRespuesta->jornada = $_POST["jornada"];
        $objRespuesta->fechaInicio = $_POST["fechaInicio"];
        $objRespuesta->fechaFin = $_POST["fechaFin"];
        $objRespuesta->ctrRegistrarFicha();
    }