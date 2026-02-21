class tipoPrograma {

    constructor(objData){
        this._objData = objData;
    }

    listarTipoPrograma(){
        let objData = new FormData();
        objData.append("listarTipoPrograma",this._objData.listarTipoPrograma);


        fetch("controlador/tipoProgramaControlador.php",{
        
        method: "POST", 
        body: objData  

        }).then(response=>response.json()).catch(error=>{
            console.log(error)
        })

        .then(response=>{
            console.log(response);


            if (response["codigo"]== "200"){
                
                
                let dataSet = [];


                response ["listarTipoPrograma"].forEach(item => {


                            let objBotones = '<div class="btn-group" role="group">';
                               objBotones += '<button type="button" class="btn btn-info btnEditarTipoPrograma" ' +
                                   'data-id="' + item.idTipoPrograma + '" ' +
                                   'data-tipo="' + item.tipoFormacion + '" ' +
                                   'data-duracion="' + item.duracion + '" ' +
                               '><i class="bi bi-pen"></i></button>';
                               objBotones += '</div>';


                              objBotones += '</div>';

                              
                              dataSet.push([    
                                item.tipoFormacion,
                                item.duracion,
                                objBotones
                              ]);
                            });
                        
                            $("#tablaTipoPrograma").DataTable({
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

    agregarTipoPrograma(){
        const formData = new FormData();
        formData.append("agregarTipoPrograma" , "ok");
        formData.append("tipoFormacion",document.getElementById("tipo_Formacion").value);
        formData.append("duracion",document.getElementById("duracionMeses").value);


        fetch("controlador/tipoProgramaControlador.php",{
        
        method: "POST", 
        body: formData

        })

        .then(response => response.json())
        .then(response => {
            if(response.codigo == "200"){
                $("#panelFormularioTipoPrograma").hide();
                $("#panelTablaTipoPrograma").show();
                this.listarTipoPrograma();
                Swal.fire({
                    position: "Center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("formAgregarTipoPrograma").reset();
            } else {
                Swal.fire(response.mensaje);
            }
        })
    }





           editarTipoPrograma(){
            const formData = new FormData();
            formData.append("editarTipoPrograma", "ok");
            formData.append("idTipoPrograma", document.getElementById("idTipoProgramaEdit").value);
            formData.append("tipoFormacion", document.getElementById("tipoFormacionEdit").value);
            formData.append("duracion", document.getElementById("duracionEdit").value);
        
        
            fetch("controlador/tipoProgramaControlador.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(response => {

                if (response.codigo == "200") {
                    $("#panelFormularioEditarTipoPrograma").hide();
                    $("#panelTablaTipoPrograma").show();
                    this.listarTipoPrograma();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.mensaje
                    });
                }
            })
            .catch(error => {
                console.error("Error en la petición:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al actualizar"
                });
            });
        }
           

}
