class ficha {

    constructor(objData){
        this._objData = objData;
    }

     listarFicha() {
        let objData = new FormData();
        objData.append("listarFichas", this._objData.listarFichas)


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
                    
                response["listarFichas"].forEach(item => {
                
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


   listarFichaHorario(){
        let objData = new FormData();
        objData.append("listarFichaHorario", this._objData.listarFichaHorario)

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        }).then(response => response.json()).catch(error => {
            console.log(error);
        })
        .then(response => {
            console.log(response)
            
            if (response["codigo"] == "200") {
                let dataSet = [];
                
                response["listarFichaHorario"].forEach(item => {
                    let objBotones = '<div class="btn-group" role="group">';
                    objBotones += '<button id="btnVerHorario" type="button" class="btn btn-primary" codigoficha="' + item.codigoFicha + '"><i class="bi bi-clock"></i></button>';
                    objBotones += '<button id="btnAsignarInstructor" type="button" class="btn btn-success" codigoficha="' + item.codigoFicha + '"><i class="bi bi-person-plus"></i></button>';
                    objBotones += '</div>';
                    
                    dataSet.push([
                        item.codigoFicha,
                        item.programa,
                        item.duracion,
                        item.jornada,
                        item.municipio,
                        objBotones
                    ]);
                });
                
                $("#tablaFichaHorario").DataTable({
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
                console.log("Error al cargar fichas con horario");
            }
        })

    }

    listarTecnologos() {
        let objData = new FormData();
        objData.append("listarTecnologos", this._objData.listarTecnologos)

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        }).then(response => response.json()).catch(error => {
            console.log(error);
        })
        .then(response => {
            console.log(response)
            
            if (response["codigo"] == "200") {
                let dataSet = [];
                
                response["listarTecnologos"].forEach(item => {
                    let objBotones = '<div class="btn-group" role="group">';
                    objBotones += '<button href="" id="btnCrearHorario" type="button" class="btn btn-primary" codigoficha="' + item.codigoFicha + '"><i class="bi bi-clock"></i></button>';
                    objBotones += '</div>';
                    
                    dataSet.push([
                        item.codigoFicha,
                        item.programa,
                        item.duracion,
                        item.jornada,
                        item.municipio,
                        objBotones
                    ]);
                });
                
                $("#tablaTecnologos").DataTable({
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
                console.log("Error al cargar tecnologos");
            }
        })

    }
}
