class Programa {

    constructor (objData){
        this._objData = objData;
    }

    listarPrograma(){
        let objData = new FormData();
        objData.append("listarPrograma",this._objData.listarPrograma)

        fetch("controlador/programaControlador.php",{
            method:"POST",
            body:objData

        }).then(response => response.json()).catch(error=>{
            console.log(error);
        })

        .then(response=>{
            console.log(response)

            if (response["codigo"]== "200"){

                let dataSet = [];

                response ["listarPrograma"].forEach(item=>{

                       let objBotones = '<div class="btn-group" role="group">';
                     objBotones += '<button type="button" class="btn btn-info btnEditarAmbiente" ' +
                       'idPrograma="' + item.idPrograma + '" ' +
                       'nombre="' + item.nombre + '" ' +
                       'codigo="' + item.codigo + '" ' +
                       'version="' + item.version + '" ' +
                       'estado="' + item.estado + '" ' +
                       'tipoFormacion="' + item.tipoFormacion + '" ' +
                       'duracion="' + item.duracion + '" ' +
                     '><i class="bi bi-pen"></i></button>';


                     objBotones += '</div>';

                     dataSet.push([    
                       item.nombre,
                       item.codigo,
                       item.version,
                       item.estado,
                       item.tipoFormacion,
                       item.duracion,
                       objBotones
                     ]);
                   });
              $("#tablaPrograma").DataTable({
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
