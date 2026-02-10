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


                response ["listarTipoFormacion"].forEach(item => {


                            let objBotones = '<div class="btn-group" role="group">';
                              objBotones += '<button type="button" class="btn btn-info btnEditarTipoPrograma" ' +
                                'idTipoPrograma="' + item.idTipoPrograma + '" ' +
                                'tipo ="' + item.tipoFormacion + '" ' +
                                'duracion="' + item.duracion + '" ' +
                              '><i class="bi bi-pen"></i></button>';


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


}
