class sede {
  constructor(objData){
    this._objData = objData;
  }

  listarSede(){
    let objData = new FormData();
    objData.append("listarSede", this._objData.listarSede);

    fetch("controlador/sedeControlador.php", {
      method: "POST",
      body: objData
    })
    .then(response => response.json())
    .then(response => {
      // response = { codigo: "200", listarSedes: [...] }
      if (response.codigo !== "200") {
        console.error("Error:", response.mensaje);
        return;
      }

      const contenedor = document.getElementById("contenedorSedes");
      contenedor.innerHTML = ""; // limpiar

      response.listarSedes.forEach(s => {
        contenedor.innerHTML += this._cardSede(s);
      });
    })
    .catch(error => console.log(error));
  }

  _cardSede(s){
    // Ajusta nombres de campos según tu BD:
    // Ej: s.idSede, s.nombre, s.direccion, s.municipio
    return `
      <div class="col-md-4 mb-3">
        <div class="card card-custom">
          <img src="/Horarios_Practica/img/sena.jpg" class="card-img-top img-uniform" alt="Sede">
          <div class="card-body">
            <h5 class="card-title card-title-custom">${s.nombre ?? "Sin nombre"}</h5>
            <p class="card-text text-muted">
            <b>Dirección:</b> ${s.direccion ?? "N/A"}<br>
            <b>Municipio:</b> ${s.nombreMunicipio ?? "Sin municipio"}
</p>
            <a 
              href="horarios.php?idMunicipio=${s.idMunicipio}" 
              class="btn btn-primary-custom"
            >
          </div>
        </div>
      </div>
    `;
  }
}

// USO:
const obj = new sede({ listarSede: "ok" });
obj.listarSede();
