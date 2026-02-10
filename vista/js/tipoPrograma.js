(function (){

listarTipoPrograma();

function listarTipoPrograma(){
    let objData = { listarTipoPrograma : "ok" };
    let objListarTipoPrograma = new tipoPrograma(objData);
    objListarTipoPrograma.listarTipoPrograma();
}

 let btnAgregarTipoPrograma = document.getElementById("agregarTipoPrograma")
 btnAgregarTipoPrograma.addEventListener("click",()=>{
    $("#panelTablaTipoPrograma").hide();
    $("#panelFormularioTipoPrograma").show();
 })
})();