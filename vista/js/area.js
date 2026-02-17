document.addEventListener('DOMContentLoaded',function(){

    listarTablaAreas();

    function listarTablaAreas(){
    
        let objData = {listarArea  : "ok" };
        let objListarAreas = new area(objData);
        objListarAreas.listarArea();
    }


});