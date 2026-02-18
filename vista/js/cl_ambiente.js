class Ambiente {

    constructor(objData){
        this._objData = objData;
    }

    // ========== LISTAR AMBIENTES POR SEDE ==========
    listarAmbientesPorSede(idSede) {
  let objData = new FormData();
  objData.append("listarAmbientesPorSede", "ok");
  objData.append("idSede", idSede);

  fetch("controlador/ambienteControlador.php", {
    method: "POST",
    body: objData
  })
    .then(response => response.json())
    .catch(error => {
      console.log(error);
    })
    .then(response => {
      console.log("📦 Respuesta ambientes:", response);

      if (response["codigo"] == "200") {
        let dataSet = [];

       response["ambientes"].forEach(item => {
    let objBotones = '<div class="btn-group" role="group">';
    objBotones += `
        <button type="button" class="btn btn-info btnEditarAmbiente"
            data-id="${item.idAmbiente}"
            data-codigo="${item.codigo}"
            data-numero="${item.numero}"
            data-capacidad="${item.capacidad}"
            data-bloque="${item.bloque}"
            data-estado="${item.estado}"
            data-descripcion="${item.descripcion || ''}"
            data-idarea="${item.idArea || ''}"
            data-nombreArea="${item.nombreArea || ''}"
            data-tipoambiente="${item.tipoAmbiente || ''}">
            <i class="bi bi-pen"></i>
        </button>
    `;
    objBotones += "</div>";

    dataSet.push([
        item.codigo,
        item.numero,
        item.nombreArea,   
        item.capacidad,
        item.bloque,               
        item.tipoAmbiente,
        item.estado,
        item.descripcion,
        objBotones
          ]);
      });
         $("#tablaAmbientesSede").DataTable({
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


      
    // Listar Areas 
    listarAreas(){
      let objData = new FormData();
      objData.append("listarAreas",this._objData.listarAreas);

      fetch("controlador/ambienteControlador.php",{
        method:"POST",
        body: objData
      })
      .then(r => r.json())
      .catch(err => console.log(err))
      .then(response => {
        console.log("listarAreas",response);
      })
    }


    // ========== REGISTRAR AMBIENTE POR SEDE ==========
    registrarAmbientePorSede(){
        let objData = new FormData();
         objData.append("registrarAmbientePorSede", "ok");
         objData.append("codigo",document.getElementById("codigoAgregar").value);
         objData.append("numero",document.getElementById("numeroAgregar").value);
         objData.append("descripcion",document.getElementById("descripcionAgregar").value);
         objData.append("capacidad",document.getElementById("capacidadAgregar").value);
         objData.append("bloque",document.getElementById("bloqueAgregar").value);      // antes: ubicacion
         objData.append("estado",document.getElementById("estadoAgregar").value);
         objData.append("idSede",document.getElementById("idSedeAgregar").value);
         objData.append("idArea",document.getElementById("selectAreas").value);       // NUEVO
         objData.append("tipoAmbiente",document.getElementById("tipoAmbienteAgregar").value); // NUEVO

        fetch("controlador/ambienteControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            console.log('📨 Respuesta guardar:', response);

            if (response.codigo == "200") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.mensaje,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Volver a la tabla y recargar
                $("#panelFormularioAgregarAmbienteSede").hide();
                $("#panelAmbientesSede").show();

                document.getElementById("formAgregarAmbientePorSede").reset();
                $("#formAgregarAmbientePorSede").removeClass('was-validated');

                // Recargar tabla de ambientes
                const idSede = document.getElementById("idSedeActualAmbientes").value;
                const objAmbiente = new Ambiente({});
                objAmbiente.listarAmbientesPorSede(idSede);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje || 'Error al registrar el ambiente'
                });
            }
        })
        .catch(err => {
            console.error('❌ Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al servidor'
            });
        });
    }


      editarAmbientePorSede() {
     const formData = new FormData();
    formData.append("editarAmbientePorSede", "ok");
    formData.append("idAmbiente",document.getElementById("idAmbienteEdit").value);
    formData.append("codigo",document.getElementById("codigoEdit").value);
    formData.append("numero",document.getElementById("numeroEdit").value);
    formData.append("descripcion",document.getElementById("descripcionEdit").value);
    formData.append("capacidad",document.getElementById("capacidadEdit").value);
    formData.append("bloque",document.getElementById("bloqueEdit").value);         // antes: ubicacion
    formData.append("estado",document.getElementById("estadoEdit").value);
    formData.append("idArea", document.getElementById("selectAreasEdit").value);          // NUEVO
    formData.append("tipoAmbiente",document.getElementById("tipoAmbienteEdit").value);   // NUEVO
    formData.append("idSede",       document.getElementById("idSedeActualAmbientes").value);

    // importante para tu PHP (como lo hicimos)
    formData.append("idSede", document.getElementById("idSedeActualAmbientes").value);

    fetch("controlador/ambienteControlador.php", {
      method: "POST",
      body: formData
    })
      .then(r => r.json())
      .then(response => {

        if (response.codigo == "200") {
          $("#panelFormularioEditarAmbienteSede").hide();
          $("#panelAmbientesSede").show();

          const idSede = document.getElementById("idSedeActualAmbientes").value;
          this.listarAmbientesPorSede(idSede);

          Swal.fire({
            position: "center",
            icon: "success",
            title: response.mensaje,
            showConfirmButton: false,
            timer: 1500
          });

        } else {
          Swal.fire({ icon: "error", title: "Error", text: response.mensaje || "No se pudo actualizar" });
        }
      })
      .catch(error => {
        console.error("Error en la petición:", error);
        Swal.fire({ icon: "error", title: "Error", text: "Hubo un problema al actualizar" });
      });
  }

}