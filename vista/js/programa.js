(function (){

listarPrograma();

function listarPrograma(){
    let objData = { listarPrograma : "ok" };
    let objListarPrograma = new Programa(objData);
    objListarPrograma.listarPrograma();
}

})();