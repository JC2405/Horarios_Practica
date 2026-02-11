(function (){

listarPrograma();

function listarPrograma(){
    let objData = { listarPrograma : "ok" };
    let objListarPrograma = new Programa(objData);
    objListarPrograma.listarPrograma();
}

  let btnAgregarTipoPrograma = document.getElementById("agregarPrograma");
btnAgregarTipoPrograma.addEventListener("click", () => {
  $("#panelTablaPrograma").hide();
  $("#panelFormularioPrograma").show();

  const objPrograma = new Programa({});
  objPrograma.cargarTiposFormacionEnSelect();

  
  document.getElementById("formAgregarPrograma").reset();
  document.getElementById("formAgregarPrograma").classList.remove("was-validated");
});



 'use strict';

 const formsAgregarPrograma = document.querySelectorAll("#formAgregarPrograma");
 Array.from(formsAgregarPrograma).forEach(form =>{
     form.addEventListener("submit", event => {
            event.preventDefault();
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                const objPrograma = new Programa({});
                objPrograma.agregarPrograma();
                listarPrograma();
            }
        }, false);
    })
})();