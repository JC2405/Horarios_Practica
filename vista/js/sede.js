(function(){

    listarSede();

    function listarSede(){
        let objData = { "listarSede" : "ok"};
        let objListarSede = new sede(objData);
        objListarSede.listarSede();
    }

    let btnAgregarTipoPrograma = document.getElementById("agregarSede")
    btnAgregarTipoPrograma.addEventListener("click",()=>{
    $("#panelTablaSede").hide();
    $("#panelFormularioTipoPrograma").show();
 })


})();