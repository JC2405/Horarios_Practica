document.addEventListener('DOMContentLoaded', function(){

    listarTipoPrograma();

    function listarTipoPrograma(){
        let objData = { listarTipoPrograma : "ok" };
        new tipoPrograma(objData).listarTipoPrograma();
    }

    // Botón agregar
    const btnAgregar = document.getElementById("agregarTipoPrograma"); // ← corregido el ID
    if(btnAgregar){
        btnAgregar.addEventListener("click", function(){
            $("#panelTablaTipoPrograma").hide();
            $("#panelFormularioTipoPrograma").show();
        });
    }

    // Validación formulario agregar
    const formAgregar = document.getElementById("formAgregarTipoPrograma");
    if(formAgregar){
        formAgregar.addEventListener("submit", function(event){
            event.preventDefault();
            if(!formAgregar.checkValidity()){
                event.stopPropagation();
                formAgregar.classList.add('was-validated');
            } else {
                new tipoPrograma({}).agregarTipoPrograma();
            }
        }, false);
    }

    // Botones cancelar/regresar formulario agregar
    $("#btnCancelarTipoPrograma, #btnRegresarTablaTipoPrograma").on("click", function(e){
        e.preventDefault();
        $("#panelFormularioTipoPrograma").hide();
        $("#panelTablaTipoPrograma").show();
        const form = document.getElementById("formAgregarTipoPrograma");
        if(form) form.reset();
        $("#formAgregarTipoPrograma").removeClass('was-validated');
    });

    // Evento editar
    $(document).on("click", ".btnEditarTipoPrograma", function(e){
        e.preventDefault();
        e.stopPropagation();

        const idTipoProgramaVal = $(this).data("id");
        const tipo     = $(this).data("tipo");
        const duracion = $(this).data("duracion");

        const idField      = document.getElementById("idTipoProgramaEdit");
        const tipoField    = document.getElementById("tipoFormacionEdit");
        const duracionField= document.getElementById("duracionEdit");

        if(idField)       idField.value       = idTipoProgramaVal;
        if(tipoField)     tipoField.value     = tipo;
        if(duracionField) duracionField.value = duracion;

        $("#panelTablaTipoPrograma").hide();
        $("#panelFormularioEditarTipoPrograma").show();
    });

    // Validación formulario editar
    const formEditar = document.getElementById("formEditarTipoPrograma");
    if(formEditar){
        formEditar.addEventListener("submit", function(event){
            event.preventDefault();
            if(!formEditar.checkValidity()){
                event.stopPropagation();
                formEditar.classList.add('was-validated');
            } else {
                new tipoPrograma({}).editarTipoPrograma();
            }
        }, false);
    }

    // Botones cancelar/regresar formulario editar
    $("#btnCancelarEditarTipoPrograma, #btnRegresarTablaTipoProgramaEdit").on("click", function(e){
        e.preventDefault();
        $("#panelFormularioEditarTipoPrograma").hide();
        $("#panelTablaTipoPrograma").show();
        $("#formEditarTipoPrograma").removeClass('was-validated');
    });

});