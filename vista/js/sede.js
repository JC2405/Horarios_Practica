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
  listarMunicipios(); // ✅ llena el select al cargar

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



})();