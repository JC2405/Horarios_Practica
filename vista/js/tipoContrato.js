(function(){



    listarTipoContrato();


    function listarTipoContrato(){
        let objData = { listarTipoContrato : "ok"};
        let objDataTipoContrato = new tipoContrato(objData);
        objDataTipoContrato.listarTipoContrato();
    }

})();