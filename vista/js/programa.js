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

 // ====== BOTONES CANCELAR / REGRESAR (AGREGAR) ======
$("#btnCancelarPrograma, #btnRegresarTablaPrograma").on("click", function(e){
  e.preventDefault();

  // ocultar formulario agregar y volver a tabla
  $("#panelFormularioPrograma").hide();
  $("#panelTablaPrograma").show();

  // reset form + quitar validación
  document.getElementById("formAgregarPrograma").reset();
  $("#formAgregarPrograma").removeClass("was-validated");
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





    $(document).on("click", ".btnEditarPrograma", function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        
        const idPrograma = $(this).data("id");
        const nombre = $(this).data("nombre");
        const codigo = $(this).data("codigo");
        const idTipoFormacion = $(this).data("idTipoFormacion");
        const version = $(this).data("version");
        const estado = $(this).data("estado");
        
        
        
        document.getElementById("idProgramaEdit").value = idPrograma;
        document.getElementById("nombreFormacionEdit").value = nombre;
        document.getElementById("codigoEdit").value = codigo;
        document.getElementById("idTipoFormacionEdit").value = idTipoFormacion ;
        document.getElementById("versionEdit").value = version;
        document.getElementById("estadoEdit").value = estado;

        const objPrograma = new Programa({});
        objPrograma.cargarTiposFormacionEnSelectEdit(idTipoFormacion);

        $("#panelTablaPrograma").hide();
        $("#panelFormularioEditarPrograma").show();
    });

    // Validación formulario editar
    const formEditar = document.getElementById("formEditarPrograma");
    if(formEditar) {
        formEditar.addEventListener("submit", function(event) {
            event.preventDefault();
            if (!formEditar.checkValidity()) {
                event.stopPropagation();
                formEditar.classList.add('was-validated');
            } else {
                const objTipoPrograma = new Programa({});
                objTipoPrograma.editarPrograma();
            }
        }, false);
    }

    // Botones cancelar/regresar formulario editar
    $("#btnCancelarEditarPrograma, #btnRegresarTablaProgramaEdit").on("click", function(e) {
      e.preventDefault();
      $("#panelFormularioEditarPrograma").hide();
      $("#panelTablaPrograma").show();
      $("#formEditarPrograma").removeClass('was-validated');
    });
    
        
})();