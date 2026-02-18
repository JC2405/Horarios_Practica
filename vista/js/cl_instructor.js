class instructor{


    constructor(objData){
        this._objData = objData;

    }

    

    listarInstructor(){
        let objData = new FormData();
        objData.append("listarInstructor",this._objData.listarInstructor);


        fetch("controlador/instructorControlador.php",{
            method:"POST",
            body:objData

        }).then(response=>response.json()).catch(error=>{
            console.log(error)
        }).then(response=>{
            console.log(response)
     

            if (response["codigo"] == "200") {
            

                let dataSet = [];


                response ["listarInstructor"].forEach(item => {


                            let objBotones = '<div class="btn-group" role="group">';
                               objBotones += '<button type="button" class="btn btn-info btnEditarInstructor" ' +
                                   'data-id="' + item.idFuncionario + '" ' +
                                   'data-nombre="' + item.nombre + '" ' +
                                   'data-correo="' + item.correo + '" ' +
                                   'data-telefono="' + item.telefono + '" ' +
                                   'data-estado="' + item.estado + '" ' +
                                   'data-correo="' + item.correo + '" ' +
                                    'data-nombre-area="' + item.nombreArea + '" ' +
                                    'data-tipo-contrato="' + item.tipoContrato + '" ' +
                                   'data-nombreRol="' + item.nombreRol + '" ' +

                               '><i class="bi bi-pen"></i></button>';
                               objBotones += '</div>';


                              objBotones += '</div>';

                              
                              dataSet.push([    
                                item.nombre,
                                item.correo,
                                item.telefono,
                                item.estado,
                                item.correo,
                                item.nombreArea,
                                item.tipoContrato,
                                item.nombreRol,
                                objBotones
                              ]);
                            });
                        
                            $("#tablaInstructores").DataTable({
                              buttons: [{
                                extend: "colvis",
                                text: "Columnas"
                              },
                              "excel",
                              "pdf",
                              "print"
                              ],
                              dom: "Bfrtip",
                              responsive: true,
                              destroy: true,
                              data: dataSet, 
              })               
            }
        })
    }

   

    
    agregarInstructor(){

        const formData = new FormData();
        formData.append("agregarInstructor", "ok");
        formData.append("nombre", document.getElementById("nombreInstructor").value);
        formData.append("correo", document.getElementById("correoInstructor").value);
        formData.append("telefono", document.getElementById("telefonoInstructor").value);
        formData.append("estado", document.getElementById("estadoInstructor").value);
        formData.append("idArea", document.getElementById("idAreaInstructor").value);
        formData.append("idTipoContrato", document.getElementById("idTipoContratoInstructor").value);
        formData.append("password", document.getElementById("passwordInstructor").value);
        

        fetch("controlador/instructorControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {

            if(response.codigo == "200"){

                $("#panelFormularioInstructor").hide();
                $("#panelListarInstructor").show();

                this.listarInstructor();

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });

                document.getElementById("formAgregarInstructor").reset();

            } else {
                Swal.fire(response.mensaje);
            }

        });
    }

   




    editarInstructor(){

        const formData = new FormData();
        formData.append("editarInstructor", "ok");
        formData.append("idFuncionario", document.getElementById("idInstructorEdit").value);
        formData.append("nombre", document.getElementById("nombreInstructorEdit").value);
        formData.append("correo", document.getElementById("correoInstructorEdit").value);
        formData.append("telefono", document.getElementById("telefonoInstructorEdit").value);
        formData.append("estado", document.getElementById("estadoInstructorEdit").value);
        formData.append("idArea", document.getElementById("idAreaInstructorEdit").value);
        formData.append("idTipoContrato", document.getElementById("idTipoContratoInstructorEdit").value);

        fetch("controlador/instructorControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {

            if(response.codigo == "200"){

                $("#panelFormularioEditarInstructor").hide();
                $("#panelListarInstructor").show();

                this.listarInstructor();

                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });

            } else {
                Swal.fire(response.mensaje);
            }

        });
    }



    
}