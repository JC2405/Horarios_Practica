(function(){

console.log('✅ Módulo ambiente.js iniciado');

// ====== CLICK: botón "Ambientes" desde la tabla de sedes ======
$(document).on("click", ".btnAmbientesSede", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const idSede = $(this).data("idsede");
    const nombreSede = $(this).data("nombre");

    console.log('🏢 Abriendo ambientes de sede:', nombreSede, 'ID:', idSede);

    // Guardar sede actual en campos ocultos
    $("#idSedeActualAmbientes").val(idSede);
    $("#nombreSedeActualListado").text(nombreSede);

    // Ocultar todos los paneles
    $("#panelTablaSede").hide();
    $("#panelFormularioSede").hide();
    $("#panelFormularioEditarSede").hide();
    $("#panelFormularioAgregarAmbienteSede").hide();

    // Mostrar panel de ambientes
    $("#panelAmbientesSede").show();

    // Listar ambientes de esa sede usando la clase
    const objAmbiente = new Ambiente({});
    objAmbiente.listarAmbientesPorSede(idSede);
});

// ====== BOTÓN: Nuevo Ambiente ======
$(document).on("click", "#btnNuevoAmbiente", function(e) {
    e.preventDefault();

    const idSede = $("#idSedeActualAmbientes").val();
    const nombreSede = $("#nombreSedeActualListado").text();

    console.log('➕ Nuevo ambiente para sede:', nombreSede, 'ID:', idSede);

    // Validar que haya una sede seleccionada
    if (!idSede) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Por favor, selecciona una sede primero'
        });
        return;
    }

    // Setear datos en el formulario
    $("#idSedeAgregar").val(idSede);
    $("#nombreSedeActual").text(nombreSede);

    // Cambiar de panel
    $("#panelAmbientesSede").hide();
    $("#panelFormularioAgregarAmbienteSede").show();

    // Limpiar formulario
    $("#formAgregarAmbientePorSede").removeClass("was-validated");
    document.getElementById("formAgregarAmbientePorSede")?.reset();
    
    // Restaurar el idSede después del reset
    $("#idSedeAgregar").val(idSede);
});

// ====== BOTÓN: Volver de Ambientes a Sedes ======
$(document).on("click", "#btnRegresarSedesDesdeAmbientes", function(e) {
    e.preventDefault();
    console.log('⬅️ Regresando a tabla de sedes');
    
    $("#panelAmbientesSede").hide();
    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelTablaSede").show();
});

// ====== BOTONES: Cancelar / Regresar del FORM a listado de Ambientes ======
$(document).on("click", "#btnCancelarAgregarAmbiente, #btnRegresarAmbientes", function(e) {
    e.preventDefault();
    console.log('⬅️ Regresando a lista de ambientes');
    
    $("#panelFormularioAgregarAmbienteSede").hide();
    $("#panelAmbientesSede").show();
    $("#formAgregarAmbientePorSede").removeClass("was-validated");
    document.getElementById("formAgregarAmbientePorSede")?.reset();
});

// ====== SUBMIT: Formulario Agregar Ambiente ======
const formAgregarAmbiente = document.getElementById("formAgregarAmbientePorSede");
if (formAgregarAmbiente) {
    formAgregarAmbiente.addEventListener("submit", function(event) {
        event.preventDefault();
        event.stopPropagation();
        
        // Validar formulario
        if (!formAgregarAmbiente.checkValidity()) {
            formAgregarAmbiente.classList.add('was-validated');
            return;
        }

        // Validar que exista idSede
        const idSede = document.getElementById("idSedeAgregar").value;
        if (!idSede) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se ha seleccionado una sede. Por favor, vuelve atrás y selecciona una sede.'
            });
            return;
        }

        console.log('💾 Guardando ambiente para sede:', idSede);

        // Llamar al método de la clase Ambiente
        const objAmbiente = new Ambiente({});
        objAmbiente.registrarAmbientePorSede();
        
    }, false);
}

console.log('✅ Eventos de ambiente.js configurados');

})();