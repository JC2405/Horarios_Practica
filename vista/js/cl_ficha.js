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
    .catch(error => {
      console.log(error);
    })
    .then(response => {
      console.log(response);

      if (response["codigo"] == "200") {

        let dataSet = [];

        response["listarFicha"].forEach(item => {

          let objBotones = '<div class="btn-group" role="group">';
          objBotones += 
            '<button type="button" class="btn btn-info btnEditarFicha" ' +
              'data-codigo="' + item.codigoFicha + '" ' +
              'data-programa="' + item.programa + '" ' +
              'data-ambiente="' + item.ambiente + '" ' +
              'data-numero="' + item.numeroAmbiente + '" ' +
              'data-jornada="' + item.jornada + '" ' +
              'data-estado="' + item.estado + '" ' +
              'data-fechainicio="' + item.fechaInicio + '" ' +
              'data-fechafin="' + item.fechaFin + '" ' +
            '><i class="bi bi-pen"></i></button>';
          objBotones += '</div>';

          dataSet.push([
            item.codigoFicha,
            item.programa,
            item.ambiente + " - " + item.numeroAmbiente,
            item.jornada,
            item.estado,
            item.fechaInicio,
            item.fechaFin,
            objBotones
          ]);

        });

        $("#tablaFichas").DataTable({
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

  
}
