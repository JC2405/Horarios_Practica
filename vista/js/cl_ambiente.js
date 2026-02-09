class ambiente {

  constructor(objData){
    this._objData = objData;
  }

  listarAmbienteMedellin(){
    let objData = new FormData();
    objData.append("listarAmbienteMedellin", this._objData.listarAmbienteMedellin);

    fetch("controlador/ambienteControlador.php",{
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

       
        response["mensaje"].forEach(item => {


          let objBotones = '<div class="btn-group" role="group">';
          objBotones += '<button type="button" class="btn btn-info btnEditarAmbiente" ' +
            'idAmbiente="' + item.idAmbiente + '" ' +
            'codigo="' + item.codigo + '" ' +
            'capacidad="' + item.capacidad + '" ' +
            'numero="' + item.numero + '" ' +
            'descripcion="' + item.descripcion + '" ' +
            'ubicacion="' + item.ubicacion + '" ' +
            'estado="' + item.estado + '" ' +
            'idSede="' + item.idSede + '"' +
          '><i class="bi bi-pen"></i></button>';


          objBotones += '</div>';

          // ✅ Columnas reales de tu tabla ambiente
          dataSet.push([    
            item.codigo,
            item.capacidad,
            item.numero,
            item.descripcion,
            item.ubicacion,
            item.estado,
            objBotones
          ]);
        });

        $("#tablaAmbienteMedellin").DataTable({
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
        });

      } else {
        console.log("error");
      }
    });
  }
}


(() => {
  let objData = { listarAmbienteMedellin: "ok" };
  let objListar = new ambiente(objData);
  objListar.listarAmbienteMedellin();
})();
