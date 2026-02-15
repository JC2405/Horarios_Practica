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
              data-id="${item.idAmbiente || item.codigo}"
              data-codigo="${item.codigo}"
              data-numero="${item.numero}"
              data-capacidad="${item.capacidad}"
              data-ubicacion="${item.ubicacion}"
              data-estado="${item.estado}"
              data-descripcion="${item.descripcion || ''}">
              <i class="bi bi-pen"></i>
            </button>
          `;
          objBotones += "</div>";

          dataSet.push([
            item.codigo,
            item.numero,
            item.capacidad,
            item.ubicacion,
            item.estado,
            item.descripcion  || "N/A",
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


      


    // ========== REGISTRAR AMBIENTE POR SEDE ==========
    registrarAmbientePorSede(){
        let objData = new FormData();
        objData.append("registrarAmbientePorSede", "ok");

        objData.append("codigo", document.getElementById("codigoAgregar").value);
        objData.append("numero", document.getElementById("numeroAgregar").value);
        objData.append("descripcion", document.getElementById("descripcionAgregar").value);
        objData.append("capacidad", document.getElementById("capacidadAgregar").value);
        objData.append("ubicacion", document.getElementById("ubicacionAgregar").value);
        objData.append("estado", document.getElementById("estadoAgregar").value);
        objData.append("idSede", document.getElementById("idSedeAgregar").value);

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

    formData.append("idAmbiente", document.getElementById("idAmbienteEdit").value);
    formData.append("codigo", document.getElementById("codigoEdit").value);
    formData.append("numero", document.getElementById("numeroEdit").value);
    formData.append("descripcion", document.getElementById("descripcionEdit").value);
    formData.append("capacidad", document.getElementById("capacidadEdit").value);
    formData.append("ubicacion", document.getElementById("ubicacionEdit").value);
    formData.append("estado", document.getElementById("estadoEdit").value);

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