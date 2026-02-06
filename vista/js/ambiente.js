(function(){

listarAmbiente();

function listarAmbiente(){

    let objData = {"listarAmbiente":"ok"};
    let objListarAmbiente = new ambiente(objData)
    objListarAmbiente.listarAmbiente();
}


listarAmbienteMedellin()

function listarAmbienteMedellin(){
    let objData = {"listarAmbienteMedellin" : "ok" };
    let objListarAmbienteMedellin = new ambiente(objData)
    objListarAmbienteMedellin.listarAmbienteMedellin();
}

})();