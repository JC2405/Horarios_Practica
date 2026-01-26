(function(){

    listarSede();

    function listarSede(){
        let objData = { "listarSede" : "ok"};
        let objListarSede = new sede(objData);
        objListarSede.listarSede();
    }

})();