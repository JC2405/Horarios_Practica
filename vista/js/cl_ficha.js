class ficha {

    constructor(objData){
        this._objData = objData;
    }



   listarFicha(){
  let objData = new FormData();
  objData.append("listarFicha", this._objData.listarFicha);

  fetch("controlador/fichaControlador.php",{
    method:"POST",
    body:objData
  })
  .then(response => response.json())
  .catch(error => console.log(error))
  .then(response => {

    console.log(response);

    if (response["codigo"] == "200") {

      let dataSet = [];

      response["listarFicha"].forEach(item => {

        let objBotones = '<div class="btn-group" role="group">';
               objBotones +=
            '<button type="button" class="btn btn-info btnEditarFicha" ' +
              'data-idficha="' + item.idFicha + '" ' +
              'data-codigo="' + item.codigoFicha + '" ' +
              'data-programa="' + item.programa + '" ' +
              'data-sede="' + item.sede + '" ' +
              'data-idsede="' + item.idSede + '" ' +              
              'data-idambiente="' + item.idAmbiente + '" ' +
              'data-numeroambiente="' + item.numeroAmbiente + '" ' + 
              'data-estado="' + item.estado + '" ' +
              'data-jornada="' + item.jornada + '" ' +
              'data-fechainicio="' + item.fechaInicio + '" ' +
              'data-fechafin="' + item.fechaFin + '" ' +
            '><i class="bi bi-pen"></i></button>';
          objBotones += '</div>';

        dataSet.push([
          item.codigoFicha,
          item.programa,
          item.sede,
          item.numeroAmbiente,
          item.jornada,
          item.estado,
          item.fechaInicio,
          item.fechaFin,
          objBotones
        ]);
      });

      $("#tablaFichas").DataTable({
        buttons: [
          { extend: "colvis", text: "Columnas" },
          "excel",
          "pdf",
          "print"
        ],
        dom: "Bfrtip",
        responsive: true,
        destroy: true,
        data: dataSet
      });

    }

  });
}

    // LISTAR MUNICIPIOS
    listarMunicipios(){
        let objData = new FormData();
        objData.append("listarMunicipios", this._objData.listarMunicipios);

        fetch("controlador/fichaControlador.php",{
            method:"POST",
            body: objData
        })
        .then(r => r.json())
        .catch(err => console.log(err))
        .then(response => {
            console.log("✅ listarMunicipios response:", response);
        });
    }

    // LISTAR SEDES POR MUNICIPIO
    listarSedesPorMunicipio(){
        let objData = new FormData();
        objData.append("listarSedesPorMunicipio", this._objData.listarSedesPorMunicipio);
        objData.append("idMunicipio", this._objData.idMunicipio);

        fetch("controlador/fichaControlador.php",{
            method:"POST",
            body: objData
        })
        .then(r => r.json())
        .catch(err => console.log(err))
        .then(response => {
            console.log("✅ listarSedesPorMunicipio response:", response);
        });
    }

    // LISTAR AMBIENTES POR SEDE
    listarAmbientesPorSede(){
        let objData = new FormData();
        objData.append("listarAmbientesPorSede", this._objData.listarAmbientesPorSede);
        objData.append("idSede", this._objData.idSede);

        fetch("controlador/fichaControlador.php",{
            method:"POST",
            body: objData
        })
        .then(r => r.json())
        .catch(err => console.log(err))
        .then(response => {
            console.log("✅ listarAmbientesPorSede response:", response);
        });
    }

    // LISTAR PROGRAMAS
    listarProgramas(){
        let objData = new FormData();
        objData.append("listarProgramas", this._objData.listarProgramas);

        fetch("controlador/fichaControlador.php",{
            method:"POST",
            body: objData
        })
        .then(r => r.json())
        .catch(err => console.log(err))
        .then(response => {
            console.log("✅ listarProgramas response:", response);
        });
    }



  // ====== PINTAR LISTA EN PANEL ======
  renderPanelList(items, type){
    const panelList = document.getElementById("panelList");
    panelList.innerHTML = "";

    if(!items || items.length === 0){
      panelList.innerHTML = `<div class="empty">No hay resultados</div>`;
      return;
    }

    items.forEach(item => {
      const div = document.createElement("div");
      div.className = "option";

      // Nombre a mostrar
      let nombre = "";
      let id = "";

      if(type === "municipio"){
        id = item.idMunicipio;
        nombre = item.nombreMunicipio;
      }

      div.textContent = nombre;

      div.addEventListener("click", () => {
        if(type === "municipio"){
          // set values
          document.getElementById("idMunicipio").value = id;
          document.getElementById("txtMunicipio").textContent = nombre;

          // habilitar sede
          document.getElementById("btnSede").disabled = false;
          document.getElementById("hintSede").textContent = "Click para buscar";

          // reset sede y ambiente
          document.getElementById("idSede").value = "";
          document.getElementById("txtSede").textContent = "Seleccionar sede…";
          document.getElementById("btnAmbiente").disabled = true;
          document.getElementById("idAmbiente").value = "";
          document.getElementById("txtAmbiente").textContent = "Seleccionar ambiente…";
        }

        // cerrar panel opcional
        closePanel();
      });

      panelList.appendChild(div);
    });
  }

  // ====== LISTAR MUNICIPIOS Y MOSTRAR EN PANEL ======
  listarMunicipiosPanel(){
    let objData = new FormData();
    objData.append("listarMunicipios", "ok");

    fetch("controlador/fichaControlador.php",{
      method:"POST",
      body: objData
    })
    .then(r => r.json())
    .catch(err => console.log(err))
    .then(response => {
      console.log("✅ listarMunicipiosPanel:", response);

      if(response.codigo === "200"){
        this.renderPanelList(response.listarMunicipios, "municipio");
      } else {
        console.warn("⚠️", response.mensaje);
      }
    });
  }

 editarFicha(){
  console.log("💾 Ejecutando editarFicha()");

  const formData = new FormData();
  formData.append("editarFicha", "ok");
  formData.append("idFicha", document.getElementById("idFichaEdit").value);
  formData.append("idAmbiente", document.getElementById("selectAmbienteEdit").value);
  formData.append("estado", document.getElementById("estadoEdit").value);
  formData.append("fechaInicio", document.getElementById("fechaInicioEdit").value);
  formData.append("fechaFin", document.getElementById("fechaFinEdit").value);
  formData.append("jornada", document.getElementById("jornadaEdit").value);

  // 🔍 Log para debugging
  console.log("📤 Datos enviados:", {
    idFicha: document.getElementById("idFichaEdit").value,
    idAmbiente: document.getElementById("selectAmbienteEdit").value,
    estado: document.getElementById("estadoEdit").value,
    fechaInicio: document.getElementById("fechaInicioEdit").value,
    fechaFin: document.getElementById("fechaFinEdit").value,
    jornada: document.getElementById("jornadaEdit").value
  });

  // Mostrar loading
  Swal.fire({
    title: 'Guardando cambios...',
    html: 'Por favor espere',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  fetch("controlador/fichaControlador.php", {
    method: "POST",
    body: formData
  })
  .then(r => r.json())
  .then(resp => {
    console.log("📨 Respuesta del servidor:", resp);

    // Cerrar loading
    Swal.close();

    if(resp.codigo === "200"){
      $("#panelEditarFicha").hide();
      $("#panelTablaFichas").show();
      this.listarFicha();

      Swal.fire({
        position: "center",
        icon: "success",
        title: resp.mensaje,
        showConfirmButton: false,
        timer: 1500
      });

    } else if(resp.codigo === "409"){
      Swal.fire({
        icon: "error",
        title: "Conflicto de Jornada",
        html: `<p style="text-align: center; margin-bottom: 15px;">${resp.mensaje}</p>
               <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; text-align: left; border-radius: 8px;">
                   <strong>💡 Solución:</strong><br>
                   • Cambia el ambiente<br>
                   • Cambia la jornada<br>
                   • Cambia las fechas
               </div>`,
        confirmButtonColor: "#7c6bff",
      });

    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: resp.mensaje || "Error desconocido",
      });
    }

  })
  .catch(err => {
    console.error("❌ Error de red:", err);
    Swal.close();
    
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "Hubo un problema al actualizar. Verifica tu conexión."
    });
  });
 }

}
