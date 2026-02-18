class tipoContrato{

    constructor(objData){
        this._objData = objData;
    }

    listarTipoContrato(){
        let objData = new FormData();
        objData.append("listarTipoContrato",this._objData.listarTipoContrato);


        fetch("controlador/tipoContratoControlador.php",{
            method:"POST",
            body:objData
        })
        .then(response=>response.json()).catch(error=>{
            console.log(error);
        })
        .then(response =>{
            console.log(response);

            if (response["codigo"]=="200") {
                let dataSet = [];


            response["listarTipoContrato"].forEach(item =>{

                let objBotones = '<div class="btn-group" role="group">';
                               objBotones += '<button type="button" class="btn btn-info btnEditarTipoContrato" ' +
                                   'data-id="' + item.idTipoContrato + '" ' +
                                   'data-tipo="' + item.tipoContrato + '" ' +
                               '><i class="bi bi-pen"></i></button>';
                               objBotones += '</div>';


                              objBotones += '</div>';

                              
                              dataSet.push([    
                                item.tipoContrato,
                                objBotones
                              ]);
                            });
                        
                            $("#tablaTipoContrato").DataTable({
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


    agregarTipoContrato(){
        const formData = new FormData();
        formData.append("agregarTipoContrato" , "ok");
        formData.append("tipoContrato",document.getElementById("tipo_contrato").value);


        fetch("controlador/tipoContratoControlador.php",{
            method:"POST",
            body:formData
        })
        
        .then(response => response.json())
        .then(response => {
            if(response.codigo == "200"){
                $("#panelFormularioTipoContrato").hide();
                $("#panelListar").show();
                this.listarTipoContrato();
                Swal.fire({
                    position: "Center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("formAgregarTipoContrato").reset();
            } else {
                Swal.fire(response.mensaje);
            }
        })
    }


        editarTipoContrato(){
            const formData = new FormData();
            formData.append("editarTipoContrato" , "ok");
            formData.append("idTipoContrato",document.getElementById("idTipoCintratoEdit").value);
            formData.append("tipoContrato",document.getElementById("tipoContratoEdit").value);


            fetch("controlador/tipoContratoControlador.php",{
                method:"POST",
                body:formData

            })
            .then(response => response.json())
            .then(response => {
                if(response.codigo == "200"){

                    $("#panelListar").show();
                    $("#panelFormularioEditarTipoContrato").hide();

                
                    this.listarTipoContrato();
                    Swal.fire({
                        position: "Center",
                        icon: "success",
                        title: response.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    document.getElementById("formEditarTipoContrato").reset();
                } else {
                    Swal.fire(response.mensaje);
                }
            })      

        }

}