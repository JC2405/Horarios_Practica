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
}