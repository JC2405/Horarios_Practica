document.addEventListener('DOMContentLoaded',function(){



    listarTipoContrato();


    function listarTipoContrato(){
        let objData = { listarTipoContrato : "ok"};
        let objDataTipoContrato = new tipoContrato(objData);
        objDataTipoContrato.listarTipoContrato();
    }


        document.getElementById("agregarTipoContrato").addEventListener("click",function(){
            $("#panelListar").hide();
            $("#panelFormularioTipoContrato").show();
        })
        

        //Validacion AGREGAR
        const formAgregar = document.getElementById("formAgregarTipoContrato");
        if(formAgregar) {
            formAgregar.addEventListener("submit", function(event) {
                event.preventDefault();
                if (!formAgregar.checkValidity()) {
                    event.stopPropagation();
                    formAgregar.classList.add('was-validated');
                } else {
                    const objTipoContrato = new tipoContrato({});
                    objTipoContrato.agregarTipoContrato();
                }
            }, false);
        }

        $("#btnCancelarTipoContrato , #btnRegresarTablaTipoContrato").on("click",function(e){
            e.preventDefault();
            $("#panelFormularioTipoContrato").hide();
            $("#panelListar").show();
            document.getElementById("formAgregarTipoContrato").reset();
            $("#formAgregarTipoContrato").removeClass('was-validated');
        });


        //EDITAR
       $(document).on("click", ".btnEditarTipoContrato", function () {
          $("#panelListar").hide();
          $("#panelFormularioEditarTipoContrato").show();

          $("#idTipoCintratoEdit").val($(this).data("id"));
          $("#tipoContratoEdit").val($(this).data("tipo"));
        });


        const formEditar = document.getElementById("formEditarTipoContrato");
        if(formEditar) {
            formEditar.addEventListener("submit", function(event) {
                event.preventDefault();
                if (!formEditar.checkValidity()) {
                    event.stopPropagation();
                    formEditar.classList.add('was-validated');
                } else {
                    const objTipoContrato = new tipoContrato({});
                    objTipoContrato.editarTipoContrato();
                }
            }, false);
        }

          $("#btnCancelarTipoContratoEdit , #btnRegresarTablaTipoContratoEdit").on("click",function(e){
            e.preventDefault();
            $("#panelFormularioEditarTipoContrato").hide();
            $("#panelListar").show();
            document.getElementById("formEditarTipoContrato").reset();
            $("#formEditarTipoContrato").removeClass('was-validated');
        });
});