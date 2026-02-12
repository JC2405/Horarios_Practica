(function(){

// ====== CLICK: botón "Ambientes" desde la tabla de sedes ======
$(document).on("click", ".btnAmbientesSede", function (e) {
  e.preventDefault();
  e.stopPropagation();

  const idSede = $(this).data("idsede");
  const nombreSede = $(this).data("nombre");

  // guardar sede actual
  $("#idSedeActualAmbientes").val(idSede);
  $("#nombreSedeActualListado").text(nombreSede);

  // ocultar sedes / forms
  $("#panelTablaSede").hide();
  $("#panelFormularioSede").hide();
  $("#panelFormularioEditarSede").hide();

  // mostrar listado de ambientes
  $("#panelAmbientesSede").show();
  $("#panelFormularioAgregarAmbienteSede").hide();

  // listar ambientes de esa sede
  listarAmbientesPorSede(idSede);
});

// ====== BOTÓN: Nuevo Ambiente ======
$("#btnNuevoAmbiente").on("click", function(e){
  e.preventDefault();

  const idSede = $("#idSedeActualAmbientes").val();
  const nombreSede = $("#nombreSedeActualListado").text();

  // setear hidden del form
  $("#idSedeAgregar").val(idSede);
  $("#nombreSedeActual").text(nombreSede);

  $("#panelAmbientesSede").hide();
  $("#panelFormularioAgregarAmbienteSede").show();

  $("#formAgregarAmbientePorSede").removeClass("was-validated");
  document.getElementById("formAgregarAmbientePorSede")?.reset();
});

// ====== Volver de Ambientes a Sedes ======
$("#btnRegresarSedesDesdeAmbientes").on("click", function(e){
  e.preventDefault();
  $("#panelAmbientesSede").hide();
  $("#panelFormularioAgregarAmbienteSede").hide();
  $("#panelTablaSede").show();
});

// ====== Cancelar / Regresar del FORM a listado de Ambientes ======
$("#btnCancelarAgregarAmbiente, #btnRegresarAmbientes").on("click", function(e){
  e.preventDefault();
  $("#panelFormularioAgregarAmbienteSede").hide();
  $("#panelAmbientesSede").show();
  $("#formAgregarAmbientePorSede").removeClass("was-validated");
  document.getElementById("formAgregarAmbientePorSede")?.reset();
});





 'use strict';

 const formsAgregarAmbientePorSede = document.querySelectorAll("#formAgregarAmbientePorSede");
 Array.from(formsAgregarAmbientePorSede).forEach(form =>{
     form.addEventListener("submit", event => {
            event.preventDefault();
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                const objAmbiente = new Ambiente({});
                objAmbiente.registrarAmbientePorSede();
                listarAmbiente();
            }
        }, false);
    })



})();