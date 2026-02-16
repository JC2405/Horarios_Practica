(function(){


listarInstructor();

function listarInstructor(){
    let objData = {listarInstructor : "ok"};
    let objListarInstructores = new instructor(objData);
    objListarInstructores.listarInstructor()
}


// BOTÓN AGREGAR
    document.getElementById("agregarInstructor").addEventListener("click", function(){
        $("#panelListarInstructor").hide();
        $("#panelFormularioInstructor").show();
    });

    // VALIDACIÓN AGREGAR
    const formAgregar = document.getElementById("formAgregarInstructor");
    if(formAgregar){
        formAgregar.addEventListener("submit", function(e){
            e.preventDefault();
            if(!formAgregar.checkValidity()){
                e.stopPropagation();
                formAgregar.classList.add("was-validated");
            }else{
                const obj = new instructor({});
                obj.agregarInstructor();
            }
        });
    }

    // EVENTO EDITAR
    $(document).on("click", ".btnEditarInstructor", function(){

        document.getElementById("idInstructorEdit").value = $(this).data("id");
        document.getElementById("nombreInstructorEdit").value = $(this).data("nombre");
        document.getElementById("correoInstructorEdit").value = $(this).data("correo");
        document.getElementById("telefonoInstructorEdit").value = $(this).data("telefono");
        document.getElementById("estadoInstructorEdit").value = $(this).data("estado");

        $("#panelListarInstructor").hide();
        $("#panelFormularioEditarInstructor").show();
    });





})();