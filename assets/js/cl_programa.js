class Programa{

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
                    objBotones += `
                      <button type="button" class="btn btn-info btnEditarPrograma"
                        data-id="${item.idPrograma}"
                        data-nombre="${item.nombre}"
                        data-codigo="${item.codigo}"
                        data-id-tipo-formacion="${item.idTipoFormacion}"
                        data-version="${item.version}"
                        data-estado="${item.estado}">
                        <i class="bi bi-pen"></i>
                      </button>
                    `;
                    objBotones += '</div>';



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



    agregarPrograma(){
        const formData = new FormData();
        formData.append("agregarPrograma", "ok");
        formData.append("nombre",document.getElementById("nombrePrograma").value);
        formData.append("codigo",document.getElementById("codigo_Programa").value);
        formData.append("idTipoFormacion",document.getElementById("id_tipoFormacion").value);
        formData.append("version",document.getElementById("version_programa").value);
        formData.append("estado",document.getElementById("estado_programa").value);

        fetch("controlador/programaControlador.php",{
            method:"POST",
            body:formData

        }).then(response => response.json())
        .then(response => {
            if (response.codigo == "200") {
                $("#panelFormularioPrograma").hide();
                $("#panelTablaPrograma").show();
                this.listarPrograma();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                document.getElementById("formAgregarPrograma").reset();
            } else {
                Swal.fire(response.mensaje);
            }
        })
    }
    
         cargarTiposFormacionEnSelect() {
              const formData = new FormData();
              formData.append("listarTipoPrograma", "ok");
                
              fetch("controlador/tipoProgramaControlador.php", {
                method: "POST",
                body: formData
              })
                .then(r => r.json())
                .then(response => {
                  if (response.codigo !== "200") {
                    console.log("Error cargando tipos:", response);
                    return;
                  }
              
                  const select = document.getElementById("id_tipoFormacion");
                   select.innerHTML = '<option value="" disabled selected>Seleccione...</option>';

            
            response.listarTipoPrograma.forEach(item => {

                select.innerHTML += `
                    <option value="${item.idTipoPrograma}">
                        ${item.tipoFormacion}
                    </option>
                    `;
                  });
                })
                .catch(err => console.log(err));
            }



            cargarTiposFormacionEnSelectEdit(idSeleccionado) {
              const formData = new FormData();
              formData.append("listarTipoPrograma", "ok");

              fetch("controlador/tipoProgramaControlador.php", {
                method: "POST",
                body: formData
              })
                .then(r => r.json())
                .then(response => {
                  if (response.codigo !== "200") return;
                
                  const select = document.getElementById("idTipoFormacionEdit");
                  select.innerHTML = '<option value="" disabled>Seleccione...</option>';
                
                  response.listarTipoPrograma.forEach(item => {
                    const selected = String(item.idTipoPrograma) === String(idSeleccionado) ? "selected" : "";
                    select.innerHTML += `
                      <option value="${item.idTipoPrograma}" ${selected}>
                        ${item.tipoFormacion}
                      </option>
                    `;
                  });
                })
                .catch(err => console.log(err));
            }


         
    editarPrograma(){
        const formData = new FormData();
         formData.append("editarPrograma", "ok");
            formData.append("idPrograma", document.getElementById("idProgramaEdit").value);
            formData.append("nombre", document.getElementById("nombreFormacionEdit").value);
            formData.append("codigo", document.getElementById("codigoEdit").value);
            formData.append("idTipoFormacion", document.getElementById("idTipoFormacionEdit").value);
            formData.append("version", document.getElementById("versionEdit").value);
            formData.append("estado", document.getElementById("estadoEdit").value);


                fetch("controlador/programaControlador.php", {
                method: "POST",
                body: formData
              })
                .then(r => r.json())
                .then(response => { 

                      if (response.codigo == "200") {
                    $("#panelFormularioEditarPrograma").hide();
                    $("#panelTablaPrograma").show();

                    this.listarPrograma();

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: response.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.mensaje
                    });
                }
            })
            .catch(error => {
                console.error("Error en la petición:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al actualizar"
                })
                });
    }        
}
