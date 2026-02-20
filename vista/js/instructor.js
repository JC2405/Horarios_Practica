document.addEventListener('DOMContentLoaded', function(){

    listarInstructor();
    cargarAreas();
    cargarTiposContrato();

    function listarInstructor(){
        let objData = {listarInstructor : "ok"};
        new instructor(objData).listarInstructor();
    }

    

    document.getElementById("agregarInstructor").addEventListener("click", function(){
        $("#panelListarInstructor").hide();
        $("#panelFormularioInstructor").show();
        document.getElementById("formAgregarInstructor").reset();
        $("#formAgregarInstructor").removeClass("was-validated");
    });



    $("#btnCancelarInstructor, #btnRegresarTablaInstructor").on("click", function(e){
        e.preventDefault();
        $("#panelFormularioInstructor").hide();
        $("#panelListarInstructor").show();
        document.getElementById("formAgregarInstructor").reset();
        $("#formAgregarInstructor").removeClass("was-validated");
    });


    
    $("#btnCancelarEditarInstructor, #btnRegresarTablaInstructorEdit").on("click", function(e){
        e.preventDefault();
        $("#panelFormularioEditarInstructor").hide();
        $("#panelListarInstructor").show();
        $("#formEditarInstructor").removeClass("was-validated");
    });

    const formAgregar = document.getElementById("formAgregarInstructor");
    if(formAgregar){
        formAgregar.addEventListener("submit", function(e){
            e.preventDefault();
            if(!formAgregar.checkValidity()){
                e.stopPropagation();
                formAgregar.classList.add("was-validated");
            } else {
                new instructor({}).agregarInstructor();
            }
        });
    }

    const formEditar = document.getElementById("formEditarInstructor");
    if(formEditar){
        formEditar.addEventListener("submit", function(e){
            e.preventDefault();
            if(!formEditar.checkValidity()){
                e.stopPropagation();
                formEditar.classList.add("was-validated");
            } else {
                new instructor({}).editarInstructor();
            }
        });
    }

    $(document).on("click", ".btnEditarInstructor", function(){
        document.getElementById("idInstructorEdit").value       = $(this).data("id");
        document.getElementById("nombreInstructorEdit").value   = $(this).data("nombre");
        document.getElementById("correoInstructorEdit").value   = $(this).data("correo");
        document.getElementById("telefonoInstructorEdit").value = $(this).data("telefono");
        document.getElementById("estadoInstructorEdit").value   = $(this).data("estado");

        // Esperar a que los selects estén cargados y luego seleccionar
        setTimeout(() => {
                const areaData = $(this).data("nombre-area");
                const contratoData = $(this).data("tipo-contrato");


            // Buscar por texto si no tienes el ID directo
            $("#idAreaInstructorEdit option").filter(function(){
                return $(this).text() == areaData;
            }).prop("selected", true);  

            $("#idTipoContratoInstructorEdit option").filter(function(){
                return $(this).text() == contratoData;
            }).prop("selected", true);
        }, 300);

        $("#panelListarInstructor").hide();
        $("#panelFormularioEditarInstructor").show();
        $("#formEditarInstructor").removeClass("was-validated");
    });
});