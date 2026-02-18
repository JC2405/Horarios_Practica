document.addEventListener('DOMContentLoaded',function () {

  $(document).on("click", ".btnAmbientesSede", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const idSede = $(this).data("idsede");
    const nombreSede = $(this).data("nombre");

    // guardar sede actual
    $("#idSedeActualAmbientes").val(idSede);
    $("#nombreSedeActualListado").text(nombreSede);

    // paneles
    $("#panelTablaSede").hide();
    $("#panelFormularioSede").hide();
    $("#panelFormularioEditarSede").hide();
    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelFormularioEditarAmbienteSede").hide();

    $("#panelAmbientesSede").show();

    // listar ambientes
    const objAmbiente = new Ambiente({});
    objAmbiente.listarAmbientesPorSede(idSede);
  });






  

  //BOTÓN: Nuevo Ambiente 
  $(document).on("click", "#btnNuevoAmbiente", function (e) {
     cargarAreas();
    e.preventDefault();

    const idSede = $("#idSedeActualAmbientes").val();
    const nombreSede = $("#nombreSedeActualListado").text();

    if (!idSede) {
      Swal.fire({ icon: "warning", title: "Atención", text: "Selecciona una sede primero" });
      return;
    }

    $("#idSedeAgregar").val(idSede);
    $("#nombreSedeActual").text(nombreSede);

    $("#panelAmbientesSede").hide();
    $("#panelFormularioEditarAmbienteSede").hide();
    $("#panelFormularioAgregarAmbienteSede").show();

    document.getElementById("formAgregarAmbientePorSede").reset();
    $("#formAgregarAmbientePorSede").removeClass("was-validated");

    // restaurar idSede luego del reset
    $("#idSedeAgregar").val(idSede);
  });






  // CANCELAR / REGRESAR (AGREGAR) 
  $("#btnCancelarAgregarAmbiente, #btnRegresarAmbientes").on("click", function (e) {
    e.preventDefault();

    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelAmbientesSede").show();

    document.getElementById("formAgregarAmbientePorSede").reset();
    $("#formAgregarAmbientePorSede").removeClass("was-validated");
  });








  // SUBMIT AGREGAR 
  const formAgregar = document.getElementById("formAgregarAmbientePorSede");
  if (formAgregar) {
    formAgregar.addEventListener("submit", function (event) {
      event.preventDefault();

      if (!formAgregar.checkValidity()) {
        event.stopPropagation();
        formAgregar.classList.add("was-validated");
      } else {
        const objAmbiente = new Ambiente({});
        objAmbiente.registrarAmbientePorSede();
      }
    }, false);
  }






  //FUNCIONES PARA CARGAR/RENDERIZAR AREAS

  function cargarAreas(){
    fetch("controlador/ambienteControlador.php",{
      method:"POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "listarAreas=ok",
    })
     .then((r) => r.json())
      .then((response) => {
        if (response.codigo === "200") {
        areas = response.listarAreas;
          renderizarAreas();
        }
      });
  }


  function renderizarAreas(){
    const select = document.getElementById("selectAreas");
    if(!select) return;

    select.innerHTML = '<option value="">Seleccione un Area...</option>';

    areas.forEach((mun) => {
      const option = document.createElement("option");
      option.value = mun.idArea;
      option.textContent = mun.nombreArea;
      select.appendChild(option);
    });
    select.onchange = onAreasChange;
  }

  function onAreasChange(e){
    const idArea = e.target.value;
    document.getElementById("idArea").value = idArea;

    if (idArea) {
      const area = areas.find((a) => a.idArea == idArea);
      document.getElementById("summaryArea").textContent = area.nombreArea;
      document.getElementById("summaryArea").classList.remove("text-muted");


    } else {
      console.error("Ups Algo salio mal");
    }
  }








//CARGAR AREAS EDITAR(AMBIENTE)
  function cargarAreasEdit(idAreaActual){
  const select = document.getElementById("selectAreasEdit");
  if(!select) return;

  select.innerHTML = "<option value=''>Cargando áreas...</option>";
  select.disabled = true;

  fetch("controlador/ambienteControlador.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "listarAreas=ok"
  })
  .then(r => r.json())
  .then(resp => {
    if(resp.codigo === "200"){
      select.innerHTML = "<option value=''>Seleccione un área...</option>";

      resp.listarAreas.forEach(a => {
        const opt = document.createElement("option");
        opt.value = a.idArea;
        opt.textContent = a.nombreArea;
        select.appendChild(opt);
      });

      select.disabled = false;

   
      if(idAreaActual){
        select.value = idAreaActual;
      }
    } else {
      select.innerHTML = "<option value=''>No hay áreas</option>";
      select.disabled = true;
    }
  })
  .catch(err => {
    console.error("Error cargando áreas:", err);
    select.innerHTML = "<option value=''>Error al cargar</option>";
    select.disabled = true;
  });
}








  //  CLICK EDITAR AMBIENTE 
  $(document).on("click", ".btnEditarAmbiente", function (e) {
  e.preventDefault();
  e.stopPropagation();

  document.getElementById("idAmbienteEdit").value= $(this).data("id");
  document.getElementById("codigoEdit").value= $(this).data("codigo");
  document.getElementById("numeroEdit").value= $(this).data("numero");
  document.getElementById("capacidadEdit").value= $(this).data("capacidad");
  document.getElementById("bloqueEdit").value= $(this).data("bloque");
  document.getElementById("estadoEdit").value= $(this).data("estado");
  document.getElementById("descripcionEdit").value= $(this).data("descripcion") || "";
  document.getElementById("tipoAmbienteEdit").value= $(this).data("tipoambiente") || "";

  
  const idAreaActual = $(this).data("idarea");
  cargarAreasEdit(idAreaActual);

  $("#panelAmbientesSede").hide();
  $("#panelFormularioEditarAmbienteSede").show();
  $("#formEditarAmbientePorSede").removeClass("was-validated");
});








  // SUBMIT EDITAR 
  const formEditar = document.getElementById("formEditarAmbientePorSede");
  if (formEditar) {
    formEditar.addEventListener("submit", function (event) {
      event.preventDefault();

      if (!formEditar.checkValidity()) {
        event.stopPropagation();
        formEditar.classList.add("was-validated");
      } else {
        const objAmbiente = new Ambiente({});
        objAmbiente.editarAmbientePorSede();
      }
    }, false);
  }







  

  // CANCELAR / REGRESAR (EDITAR)
  $("#btnCancelarEditarAmbiente, #btnRegresarEditarAmbiente").on("click", function (e) {
    e.preventDefault();

    $("#panelFormularioEditarAmbienteSede").hide();
    $("#panelAmbientesSede").show();

    $("#formEditarAmbientePorSede").removeClass("was-validated");
  });







  //VOLVER DE AMBIENTES A SEDES
  $("#btnRegresarSedesDesdeAmbientes").on("click", function (e) {
    e.preventDefault();
    $("#panelAmbientesSede").hide();
    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelFormularioEditarAmbienteSede").hide();
    $("#panelTablaSede").show();
  });

});
