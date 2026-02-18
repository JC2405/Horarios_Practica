(function(){

    listarTablaAreas();

    function listarTablaAreas(){
    
        let objData = {listarArea  : "ok" };
        let objListarAreas = new area(objData);
        objListarAreas.listarArea();
    }

    document.getElementById("agregarArea")?.addEventListener("click", function(){
      $("#panelTablaArea").hide();
      $("#panelFormularioAgregarArea").show();
    });

    // Volver desde Agregar
    $("#btnVolverTablaAreaAgregar, #btnCancelarAreaAgregar").on("click", function(){
      $("#panelFormularioAgregarArea").hide();
      $("#panelTablaArea").show();
      $("#formRegistrarArea").removeClass("was-validated")[0]?.reset();
    });

    // Volver desde Editar
    $("#btnVolverTablaAreaEditar, #btnCancelarAreaEditar").on("click", function(){
      $("#panelFormularioEditarArea").hide();
      $("#panelTablaArea").show();
      $("#formEditarArea").removeClass("was-validated");
    });



  


    // Validación formulario agregar
    const formAgregar = document.getElementById("formRegistrarArea");
    if(formAgregar) {
        formAgregar.addEventListener("submit", function(event) {
            event.preventDefault();
            if (!formAgregar.checkValidity()) {
                event.stopPropagation();
                formAgregar.classList.add('was-validated');
            } else {
                const objArea = new area({});
                objArea.registrarArea();
            }
        }, false);
    }







    //EDITAR
    $(document).on("click",".btnEditarArea", function(e){
        e.preventDefault();
        e.stopPropagation();

        const idArea = $(this).data("id");
        const nombreArea = $(this).data("tipo")


        
        document.getElementById("idAreaEdit").value = idArea;
        document.getElementById("nombreAreaEdit").value = nombreArea;


        $("#panelTablaArea").hide();
        $("#panelFormularioEditarArea").show();
    })


    const formEditar = document.getElementById("formEditarArea");
    if(formEditar){
        formEditar.addEventListener("submit",function(event){
            event.preventDefault();
            if (!formEditar.checkVisibility()) {
                event.stopPropagation();
                formEditar.classList.add('was-validated');
            } else {
                const objArea = new area({});
                objArea.editarArea();
            }
        },false);
    }
})();