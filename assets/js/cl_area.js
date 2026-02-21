class area{

    constructor (objData){
        this._objData = objData;
    }


    listarArea(){
        let objData = new FormData();
        objData.append("listarArea",this._objData.listarArea)



        fetch("controlador/areaControlador.php",{
            method:"POST",
            body:objData
        })
        .then(response => response.json()).catch(error=>{
            console.log(error);
        })
        .then(response=>{
            console.log(response)


            if (response["codigo"]=="200") {
                let dataSet = [];


                response["listarArea"].forEach(item=>{

                     let objBotones = '<div class="btn-group" role="group">';
                               objBotones += '<button type="button" class="btn btn-info btnEditarArea" ' +
                                   'data-id="' + item.idArea + '" ' +
                                   'data-tipo="' + item.nombreArea + '" ' +
                               '><i class="bi bi-pen"></i></button>';
                               objBotones += '</div>';


                              objBotones += '</div>';

                              
                              dataSet.push([    
                                item.nombreArea,
                                objBotones
                              ]);
                            });
                        
                            $("#tablaArea").DataTable({
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


    registrarArea(){
        const formData = new FormData();
        formData.append("registrarArea" , "ok");
        formData.append("nombreArea",document.getElementById("nombre_area").value);


        fetch("controlador/areaControlador.php", {
            method:"POST",
            body:formData
        })

        .then(response => response.json())
        .then(response => {
            if(response.codigo == "200"){

                $("#panelTablaArea").show();
                $("#panelFormularioAgregarArea").hide();

              
                this.listarArea();
                Swal.fire({
                    position: "Center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("formRegistrarArea").reset();
            } else {
                Swal.fire(response.mensaje);
            }
        })
    }

    editarArea(){
        const formData = new FormData();
        formData.append("editarArea" , "ok");
        formData.append("idArea",document.getElementById("idAreaEdit").value);
        formData.append("nombreArea",document.getElementById("nombreAreaEdit").value);


        fetch("controlador/areaControlador.php",{
            method:"POST",
            body:formData
        })
         .then(response => response.json())
        .then(response => {
            if(response.codigo == "200"){

                $("#panelTablaArea").show();
                $("#panelFormularioEditarArea").hide();

              
                this.listarArea();
                Swal.fire({
                    position: "Center",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("formRegistrarArea").reset();
            } else {
                Swal.fire(response.mensaje);
            }
        })      

    }

}