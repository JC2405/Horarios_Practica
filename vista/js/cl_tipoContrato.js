class tipoContrato {

    constructor(objData) {
        this._objData = objData;
    }

    listarTipoContrato(){

        let objData = new FormData();
        objData.append("listarTipoContrato", this._objData.listarTipoContrato);


        fetch("controlador/tipoContratoControlador.php",{
            method:"POST",
            body: objData
        })

            .then(response => response.json()).catch(error => {
                console.log(error);
            })

            .then(response => {
                
                console.log(response);

               if (response["codigo"] == "200") {
                    
                     let dataSet = [];

                    response["listarTipoContrato"].forEach(item => {
                    
                    
                    let objBotones = `
                    <div class="btn-group" role="group" aria-label="Acciones">
                    <button type="button" class="btn btn-info btnEditarArea"
                    data-idTipoContrato="${item.idTipoContrato}"
                    data-tipo_Contrato="${item.tipo_Contrato}">
                    <i class="bi bi-pen"></i>
                    </button>
                    <button type="button" class="btn btn-danger btnEliminarArea"
                    data-idTipoContrato="${item.idTipoContrato}">
                    <i class="bi bi-x"></i>
                    </button>
                    </div>
                    `;
                     dataSet.push([item.tipo_Contrato, objBotones]);

                    });
                    
                    $('#listarTipoContrato').DataTable({
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
                        data: dataSet
                    });
                } else {
                    console.log("error")
                    
                }


                })
    }
}