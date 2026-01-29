(function (){

listarTipoFormacion();

function listarTipoFormacion(){
    let objData = { listarTipoFormacion : "ok" };
    let objListarTipoFormacion = new tipoFormacion(objData);
    objListarTipoFormacion.listarTipoFormacion();
}


})();