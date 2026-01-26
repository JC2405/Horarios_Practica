class area {

    constructor(objData) {
        this._objData = objData;
    }

    listarTablaAreas(){
        let objData = new FormData();
        objData.append("listarArea", this._objData.listarTablaAreas)


        fetch("controlador/areaControlador.php", {
            method: "POST",
            body: objData
        })
            .then(response => response.json()).catch(error => {
                console.log(error); 
            })
            .then(response => {

             console.log(response);

                if (response ["codigo"]=="200"){
                    
                    let dataSet = [];

                    response["listarArea"].forEach(item => {
                    
                        
                    let objBotones = `
                    <div class="btn-group" role="group" aria-label="Acciones">
                    <button type="button" class="btn btn-info btnEditarArea"
                    data-idarea="${item.idArea}"
                    data-nombrearea="${item.nombreArea}">
                    <i class="bi bi-pen"></i>
                    </button>
                    <button type="button" class="btn btn-danger btnEliminarArea"
                    data-idarea="${item.idArea}">
                    <i class="bi bi-x"></i>
                    </button>
                    </div>
                    `;
                     dataSet.push([item.nombreArea, objBotones]);

                    });
                    
                    $('#listarTablaAreas').DataTable({
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