(function(){

    listarTipoContrato();


    function listarTipoContrato(){
        let objData = {"listarTipoContrato" : "ok"};
        let objTablaListarTipoContrato = new tipoContrato(objData);
        objTablaListarTipoContrato.listarTipoContrato();
    }


})();