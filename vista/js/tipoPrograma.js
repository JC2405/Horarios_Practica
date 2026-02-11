(function (){

    listarTipoPrograma();

    function listarTipoPrograma(){
        let objData = { listarTipoPrograma : "ok" };
        let objListarTipoPrograma = new tipoPrograma(objData);
        objListarTipoPrograma.listarTipoPrograma();
    }

    // Botón agregar
    document.getElementById("agregarTipoPrograma").addEventListener("click", function(){
        $("#panelTablaTipoPrograma").hide();
        $("#panelFormularioTipoPrograma").show();
    });

    // Validación formulario agregar
    const formAgregar = document.getElementById("formAgregarTipoPrograma");
    if(formAgregar) {
        formAgregar.addEventListener("submit", function(event) {
            event.preventDefault();
            if (!formAgregar.checkValidity()) {
                event.stopPropagation();
                formAgregar.classList.add('was-validated');
            } else {
                const objTipoPrograma = new tipoPrograma({});
                objTipoPrograma.agregarTipoPrograma();
            }
        }, false);
    }

    // Botones cancelar/regresar formulario agregar
    $("#btnCancelarTipoPrograma, #btnRegresarTablaTipoPrograma").on("click", function(e) {
        e.preventDefault();
        $("#panelFormularioTipoPrograma").hide();
        $("#panelTablaTipoPrograma").show();
        document.getElementById("formAgregarTipoPrograma").reset();
        $("#formAgregarTipoPrograma").removeClass('was-validated');
    });

    // ===== EVENTO EDITAR =====
    $(document).on("click", ".btnEditarTipoPrograma", function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log("Click detectado en botón editar");
        
        const idTipoPrograma = $(this).data("id");
        const tipo = $(this).data("tipo");
        const duracion = $(this).data("duracion");
        
        console.log("ID:", idTipoPrograma);
        console.log("Tipo:", tipo);
        console.log("Duración:", duracion);
        
        // Cargar datos directamente sin fetch adicional
        document.getElementById("idTipoProgramaEdit").value = idTipoPrograma;
        document.getElementById("tipoFormacionEdit").value = tipo;
        document.getElementById("duracionEdit").value = duracion;

        $("#panelTablaTipoPrograma").hide();
        $("#panelFormularioEditarTipoPrograma").show();
    });

    // Validación formulario editar
    const formEditar = document.getElementById("formEditarTipoPrograma");
    if(formEditar) {
        formEditar.addEventListener("submit", function(event) {
            event.preventDefault();
            if (!formEditar.checkValidity()) {
                event.stopPropagation();
                formEditar.classList.add('was-validated');
            } else {
                const objTipoPrograma = new tipoPrograma({});
                objTipoPrograma.editarTipoPrograma();
            }
        }, false);
    }

    // Botones cancelar/regresar formulario editar
    $("#btnCancelarEditarTipoPrograma, #btnRegresarTablaTipoProgramaEdit").on("click", function(e) {
        e.preventDefault();
        $("#panelFormularioEditarTipoPrograma").hide();
        $("#panelTablaTipoPrograma").show();
        $("#formEditarTipoPrograma").removeClass('was-validated');
    });

})();