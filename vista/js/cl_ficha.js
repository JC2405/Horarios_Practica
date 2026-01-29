class ficha {

    constructor(objData){
        this._objData = objData;
    }

     listarFicha() {
        let objData = new FormData();
        objData.append("listarFicha", this._objData.listarFicha)


        fetch ("controlador/fichaControlador.php",{
            method:"POST",
            body:objData

        }).then(response=>response.json()).catch(error=>{
            console.log(error);
        })

          .then(response=>{
            console.log(response)
         
                    if (response["codigo"] == "200") {
                let dataSet = [];
                    
                response["listarFicha"].forEach(item => {
                
                    let objBotones = '<div class="btn-group" role="group">';
                    objBotones += '<button id="btnEditar" type="button" class="btn btn-info" codigo="' + item.codigo + '" nombre="' + item.nombre + '" duracion="' + item.duracion + '" jornada="' + item.jornada + '" codigoFicha="' + item.codigoFicha + '"><i class="bi bi-pen"></i></button>';
                    objBotones += '<button id="btnEliminar" type="button" class="btn btn-danger" codigo="' + item.codigo + '"><i class="bi bi-x"></i></button>';
                    objBotones += '</div>';
                
                    dataSet.push([
                        item.codigo,
                        item.nombre,
                        item.duracion,
                        item.jornada,
                        item.codigoFicha,
                        objBotones
                    ]);
                });
            
                $("#tablaProgramaFicha").DataTable({
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
                  console.log("error");
                }
           })  
    }
}
