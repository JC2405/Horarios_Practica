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

  // ====== BOTÓN: Nuevo Ambiente ======
  $(document).on("click", "#btnNuevoAmbiente", function (e) {
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

  // ====== CANCELAR / REGRESAR (AGREGAR) ======
  $("#btnCancelarAgregarAmbiente, #btnRegresarAmbientes").on("click", function (e) {
    e.preventDefault();

    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelAmbientesSede").show();

    document.getElementById("formAgregarAmbientePorSede").reset();
    $("#formAgregarAmbientePorSede").removeClass("was-validated");
  });

  // ====== SUBMIT AGREGAR (validación bootstrap igual a tu estilo) ======
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

  // ====== CLICK: EDITAR AMBIENTE (igual estilo Programa) ======
  $(document).on("click", ".btnEditarAmbiente", function (e) {
    e.preventDefault();
    e.stopPropagation();

    document.getElementById("idAmbienteEdit").value    = $(this).data("id");
    document.getElementById("codigoEdit").value        = $(this).data("codigo");
    document.getElementById("numeroEdit").value        = $(this).data("numero");
    document.getElementById("capacidadEdit").value     = $(this).data("capacidad");
    document.getElementById("bloqueEdit").value        = $(this).data("bloque");        
    document.getElementById("estadoEdit").value        = $(this).data("estado");
    document.getElementById("descripcionEdit").value   = $(this).data("descripcion");
    document.getElementById("nombreEdit").value        = $(this).data("nombre");        
    document.getElementById("tipoAmbienteEdit").value  = $(this).data("tipoambiente"); 

    $("#panelAmbientesSede").hide();
    $("#panelFormularioEditarAmbienteSede").show();
    $("#formEditarAmbientePorSede").removeClass("was-validated");
});

  // ====== SUBMIT EDITAR (validación bootstrap igual) ======
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

  // ====== CANCELAR / REGRESAR (EDITAR) ======
  $("#btnCancelarEditarAmbiente, #btnRegresarEditarAmbiente").on("click", function (e) {
    e.preventDefault();

    $("#panelFormularioEditarAmbienteSede").hide();
    $("#panelAmbientesSede").show();

    $("#formEditarAmbientePorSede").removeClass("was-validated");
  });

  // ====== VOLVER DE AMBIENTES A SEDES ======
  $("#btnRegresarSedesDesdeAmbientes").on("click", function (e) {
    e.preventDefault();
    $("#panelAmbientesSede").hide();
    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelFormularioEditarAmbienteSede").hide();
    $("#panelTablaSede").show();
  });

});
