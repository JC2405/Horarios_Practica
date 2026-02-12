(function(){

    listarSede();
     

    function listarSede(){
        let objData = { "listarSede" : "ok"};
        let objListarSede = new sede(objData);
        objListarSede.listarSede();
    }

    let btnAgregarTipoPrograma = document.getElementById("agregarSede")
    btnAgregarTipoPrograma.addEventListener("click",()=>{
    $("#panelTablaSede").hide();
    $("#panelFormularioSede").show();
 })

  listarSede();
  listarMunicipios(); 

  function listarSede(){
    let objData = { listarSede: "ok" };
    new sede(objData).listarSede();
  }

  function listarMunicipios(){
    let fd = new FormData();
    fd.append("listarMunicipios","ok");

    fetch("controlador/municipioControlador.php", {
      method: "POST",
      body: fd
    })
    .then(r => r.json())
    .then(response => {
      console.log("municipios:", response);

      if(response.codigo == "200"){
        const select = document.getElementById("idMunicipioSede");
        select.innerHTML = `<option value="" selected disabled>Seleccione...</option>`;

        response.listarMunicipios.forEach(m => {
          select.innerHTML += `<option value="${m.idMunicipio}">${m.nombreMunicipio}</option>`;
        });
      } else {
        console.log(response.mensaje);
      }
    })
    .catch(err => console.log(err));
  }


  function cargarMunicipiosEnSelectEdit(idMunicipioSeleccionado){
  let fd = new FormData();
  fd.append("listarMunicipios","ok");

  fetch("controlador/municipioControlador.php", {
    method: "POST",
    body: fd
  })
  .then(r => r.json())
  .then(response => {
    if(response.codigo == "200"){
      const select = document.getElementById("idMunicipioSedeEdit");
      select.innerHTML = `<option value="" disabled>Seleccione...</option>`;

      response.listarMunicipios.forEach(m => {
        select.innerHTML += `<option value="${m.idMunicipio}">${m.nombreMunicipio}</option>`;
      });

      // seleccionar el municipio actual
      if(idMunicipioSeleccionado){
        select.value = idMunicipioSeleccionado;
      }
    } else {
      console.log(response.mensaje);
    }
  })
  .catch(err => console.log(err));
}

  // botón mostrar formulario
  document.getElementById("agregarSede").addEventListener("click", () => {
    $("#panelTablaSede").hide();
    $("#panelFormularioSede").show();
  });


  // validación y submit formulario agregar
  const formAgregarSede = document.getElementById("formAgregarSede");
  if(formAgregarSede){
    formAgregarSede.addEventListener("submit", function(event){
      event.preventDefault();
      if(!formAgregarSede.checkValidity()){
        event.stopPropagation();
        formAgregarSede.classList.add("was-validated");
      } else {
        const objSede = new sede({});
        objSede.agregarSede();
      }
    }, false);
  }

  // cancelar / regresar
  $("#btnCancelarSede, #btnRegresarTablaSede").on("click", function(e){
    e.preventDefault();
    $("#panelFormularioSede").hide();
    $("#panelTablaSede").show();
    document.getElementById("formAgregarSede").reset();
    $("#formAgregarSede").removeClass("was-validated");
  });




 $(document).on("click", ".btnAmbientesSede", function (e) {
  e.preventDefault();
  e.stopPropagation();

  $("#panelTablaSede").hide();
  $("#panelFormularioAgregarAmbienteSede").show();
  });




  
  $(document).on("click", ".btnEditarSede", function(e){
  e.preventDefault();
  e.stopPropagation();

  const idSede = $(this).data("idsede");
  const nombre = $(this).data("nombre");
  const direccion = $(this).data("direccion");
  const descripcion = $(this).data("descripcion");
  const estado = $(this).data("estado");
  const idMunicipio = $(this).data("idmunicipio");

  // set inputs
  document.getElementById("idSedeEdit").value = idSede;
  document.getElementById("nombreSedeEdit").value = nombre;
  document.getElementById("direccionSedeEdit").value = direccion;
  document.getElementById("descripcionSedeEdit").value = descripcion;
  document.getElementById("estadoSedeEdit").value = estado;

  // cargar municipios y dejar seleccionado el actual
  cargarMunicipiosEnSelectEdit(idMunicipio);

  // mostrar panel editar
  $("#panelTablaSede").hide();
  $("#panelFormularioEditarSede").show();

  // quitar validación previa
  $("#formEditarSede").removeClass("was-validated");
});


// ===== VALIDACIÓN FORM EDITAR =====
const formEditarSede = document.getElementById("formEditarSede");
if(formEditarSede){
  formEditarSede.addEventListener("submit", function(event){
    event.preventDefault();

    if(!formEditarSede.checkValidity()){
      event.stopPropagation();
      formEditarSede.classList.add("was-validated");
    } else {
      const objSede = new sede({});
      objSede.editarSede();
    }
  }, false);
}

// ===== BOTONES CANCELAR / REGRESAR EDITAR =====
$("#btnCancelarEditarSede, #btnRegresarTablaSedeEdit").on("click", function(e){
  e.preventDefault();
  $("#panelFormularioEditarSede").hide();
  $("#panelTablaSede").show();
  $("#formEditarSede").removeClass("was-validated");
});

})();