class sede {

    constructor(objData){
        this._objData = objData;
    }


    listarSede(){
        let objData = new FormData();
        objData.append("listarSede",this._objData.listarSede)

        fetch("controlador/sedeControlador.php",{
            method:"POST",
            body:objData
        }).then(response => response.json()).catch(error=>{
            console.log(error);
        })
        .then(response=>{
            console.log(response);
        
        if (response["codigo"] == "200") {
            let dataSet = [];

            response ["listarSedes"].forEach(item =>{

                let objBotones = '<div class="btn-group" role="group">';
                objBotones += '<button type="button" class="btn btn-info btnEditarSede" ' +
                  'data-idsede="' + item.idSede + '" ' +
                  'data-nombre="' + item.nombre + '" ' +
                  'data-direccion="' + item.direccion + '" ' +
                  'data-descripcion="' + item.descripcion + '" ' +
                  'data-estado="' + item.estado + '" ' +
                  'data-idmunicipio="' + (item.idMunicipio ?? "") + '" ' +
                '><i class="bi bi-pen"></i></button>';
                            
                 objBotones += '<button type="button" class="btn btn-success btnAmbientesSede" ' +
                  'data-idsede="' + item.idSede + '" ' +
                  'data-nombre="' + item.nombre + '" ' +
                '><i class="bi bi-building"></i></button>';

                     objBotones += '</div>';

                     dataSet.push([    
                       item.nombre,
                       item.direccion,
                       item.descripcion,
                       item.estado,
                       item.nombreMunicipio,
                       objBotones
                     ]);
                   });
              $("#tablaSede").DataTable({
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

    agregarSede(){
    let objData = new FormData();
    objData.append("agregarSede", "ok");

    objData.append("nombre", document.getElementById("nombreSede").value);
    objData.append("direccion", document.getElementById("direccionSede").value);
    objData.append("descripcion", document.getElementById("descripcionSede").value);
    objData.append("estado", document.getElementById("estadoSede").value);
    objData.append("idMunicipio", document.getElementById("idMunicipioSede").value);

    fetch("controlador/sedeControlador.php",{
      method:"POST",
      body: objData
    })
    .then(r => r.json())
    .then(response => {
      console.log(response);

      if (response["codigo"] == "200") {
        // volver a la tabla y recargar
        $("#panelFormularioSede").hide();
        $("#panelTablaSede").show();
        document.getElementById("formAgregarSede").reset();
        $("#formAgregarSede").removeClass('was-validated');

        // recargar tabla
        let objListar = new sede({ listarSede: "ok" });
        objListar.listarSede();
      } else {
        alert(response["mensaje"]);
      }
    })
    .catch(err => console.log(err));
  }

      editarSede(){
      let objData = new FormData();
      objData.append("editarSede", "ok");

      objData.append("idSede", document.getElementById("idSedeEdit").value);
      objData.append("nombre", document.getElementById("nombreSedeEdit").value);
      objData.append("direccion", document.getElementById("direccionSedeEdit").value);
      objData.append("descripcion", document.getElementById("descripcionSedeEdit").value);
      objData.append("estado", document.getElementById("estadoSedeEdit").value);
      objData.append("idMunicipio", document.getElementById("idMunicipioSedeEdit").value);

      fetch("controlador/sedeControlador.php",{
        method:"POST",
        body: objData
      })
      .then(r => r.json())
      .then(response => {
        console.log(response);

        if (response["codigo"] == "200") {
          $("#panelFormularioEditarSede").hide();
          $("#panelTablaSede").show();
          $("#formEditarSede").removeClass("was-validated");

          // recargar tabla
          new sede({ listarSede: "ok" }).listarSede();
        } else {
          alert(response["mensaje"]);
        }
      })
      .catch(err => console.log(err));
    }
  
}