(function () {

  // =========================
  // 1) LISTAR FICHAS (si tu clase ficha existe)
  // =========================
  listarFicha();

  function listarFicha() {
    let objData = { listarFicha: "ok" };
    let objListarFicha = new ficha(objData);
    objListarFicha.listarFicha();
  }

  // =========================
  // 2) VARIABLES GLOBALES
  // =========================
  let duracionMesesSeleccionado = null;
  let municipios = [];
  let sedes = [];
  let ambientes = [];
  let programas = [];
  let currentSection = 1;

  // =========================
  // 3) INICIALIZACIÓN (CUANDO EL DOM YA EXISTE)
  // =========================
  document.addEventListener("DOMContentLoaded", function () {

    // Cargar data inicial
    cargarMunicipios();
    cargarProgramas();

    // Configurar listeners
    configurarEventListeners();

    // Configurar toggle de paneles (ANTES te fallaba porque estaba fuera del DOMContentLoaded)
    configurarPaneles();

    console.log("✅ Módulo crearFicha_mejorado.js configurado completamente");
  });

  // =========================
  // 4) MOSTRAR / OCULTAR PANELES (ARREGLADO)
  // =========================
  function configurarPaneles() {
    const panelTabla = document.getElementById("panelTablaFichas");
    const panelCrear = document.getElementById("panelCrearFicha");
    const btnCrearFicha = document.getElementById("btnCrearFicha");
    const btnVolverTabla = document.getElementById("btnVolverTabla");

    // Validaciones rápidas por si el HTML no está (evita que "explote")
    if (!panelTabla || !panelCrear || !btnCrearFicha || !btnVolverTabla) {
      console.error("❌ No se encontraron elementos del DOM para paneles. Revisa IDs:", {
        panelTabla, panelCrear, btnCrearFicha, btnVolverTabla
      });
      return;
    }

    // Botón: Crear Ficha (Mostrar formulario)
    btnCrearFicha.addEventListener("click", function () {
      panelTabla.style.display = "none";
      panelCrear.style.display = "block";
      console.log("✅ Panel de crear ficha activado");
    });

    // Botón: Volver a Tabla (Ocultar formulario)
    btnVolverTabla.addEventListener("click", function () {
      panelCrear.style.display = "none";
      panelTabla.style.display = "block";
      console.log("✅ Panel de tabla activado");

      // Reset visual del formulario/steps
      const form = document.getElementById("formCrearFicha");
      if (form) form.reset();

      document.querySelectorAll(".form-section").forEach(s => s.classList.remove("active"));
      const s1 = document.getElementById("section1");
      if (s1) s1.classList.add("active");

      document.querySelectorAll(".step").forEach(s => s.classList.remove("active", "completed"));
      const st1 = document.getElementById("step1");
      if (st1) st1.classList.add("active");

      // Reset resumen
      resetResumen();
      resetearSedes();
      resetearAmbientes();
      duracionMesesSeleccionado = null;
      currentSection = 1;
    });
  }

  function resetResumen() {
    const setMuted = (id, txt) => {
      const el = document.getElementById(id);
      if (!el) return;
      el.textContent = txt;
      el.classList.add("text-muted");
    };

    setMuted("summaryCodigo", "No especificado");
    setMuted("summaryJornada", "No especificado");
    setMuted("summaryMunicipio", "No especificado");
    setMuted("summarySede", "No especificado");
    setMuted("summaryAmbiente", "No especificado");
    setMuted("summaryPrograma", "No especificado");
    setMuted("summaryFechas", "No especificado");

    const infoDuracion = document.getElementById("infoDuracion");
    if (infoDuracion) infoDuracion.style.display = "none";
  }

  // =========================
  // 5) CONFIGURAR EVENT LISTENERS
  // =========================
  function configurarEventListeners() {
    // Cambios en selects
    document.getElementById("codigo")?.addEventListener("input", actualizarResumen);
    document.getElementById("jornada")?.addEventListener("change", actualizarResumen);

    document.getElementById("selectMunicipio")?.addEventListener("change", onMunicipioChange);
    document.getElementById("selectSede")?.addEventListener("change", onSedeChange);
    document.getElementById("selectAmbiente")?.addEventListener("change", onAmbienteChange);
    document.getElementById("selectPrograma")?.addEventListener("change", onProgramaChange);

    document.getElementById("fecha_inicio")?.addEventListener("change", calcularFechaFin);

    // Submit del formulario
    document.getElementById("formCrearFicha")?.addEventListener("submit", onSubmit);
  }

  // =========================
  // 6) NAVEGACIÓN ENTRE SECCIONES
  // =========================
  window.nextSection = function (sectionNumber) {
    if (!validateSection(currentSection)) return;

    document.getElementById(`section${currentSection}`)?.classList.remove("active");
    document.getElementById(`step${currentSection}`)?.classList.remove("active");
    document.getElementById(`step${currentSection}`)?.classList.add("completed");

    const currentIcon = document.querySelector(`#step${currentSection} .step-icon i`);
    if (currentIcon) currentIcon.className = "bi bi-check-circle-fill";

    currentSection = sectionNumber;
    const newSection = document.getElementById(`section${sectionNumber}`);
    if (newSection) newSection.classList.add("active", "next");

    document.getElementById(`step${sectionNumber}`)?.classList.add("active");

    window.scrollTo({ top: 0, behavior: "smooth" });

    setTimeout(() => {
      newSection?.classList.remove("next");
    }, 400);
  };

  window.prevSection = function (sectionNumber) {
    document.getElementById(`section${currentSection}`)?.classList.remove("active");
    document.getElementById(`step${currentSection}`)?.classList.remove("active");

    currentSection = sectionNumber;
    const prevSectionEl = document.getElementById(`section${sectionNumber}`);
    if (prevSectionEl) prevSectionEl.classList.add("active", "prev");

    document.getElementById(`step${sectionNumber}`)?.classList.add("active");
    document.getElementById(`step${sectionNumber}`)?.classList.remove("completed");

    const icon = document.querySelector(`#step${sectionNumber} .step-icon i`);
    if (icon) icon.className = `bi bi-${sectionNumber}-circle-fill`;

    window.scrollTo({ top: 0, behavior: "smooth" });

    setTimeout(() => {
      prevSectionEl?.classList.remove("prev");
    }, 400);
  };

  // =========================
  // 7) VALIDACIÓN DE SECCIONES
  // =========================
  function validateSection(sectionNumber) {
    let isValid = true;
    let errorMessage = "";

    switch (sectionNumber) {
      case 1: {
        const codigo = document.getElementById("codigo")?.value.trim();
        const jornada = document.getElementById("jornada")?.value;

        if (!codigo) {
          errorMessage = "Por favor ingrese el código de la ficha";
          isValid = false;
        } else if (!jornada) {
          errorMessage = "Por favor seleccione una jornada";
          isValid = false;
        }
        break;
      }

      case 2: {
        const idMunicipio = document.getElementById("idMunicipio")?.value;
        const idSede = document.getElementById("idSede")?.value;
        const idAmbiente = document.getElementById("idAmbiente")?.value;

        if (!idMunicipio) {
          errorMessage = "Por favor seleccione un municipio";
          isValid = false;
        } else if (!idSede) {
          errorMessage = "Por favor seleccione una sede";
          isValid = false;
        } else if (!idAmbiente) {
          errorMessage = "Por favor seleccione un ambiente";
          isValid = false;
        }
        break;
      }

      case 3: {
        const idPrograma = document.getElementById("idPrograma")?.value;
        if (!idPrograma) {
          errorMessage = "Por favor seleccione un programa";
          isValid = false;
        }
        break;
      }
    }

    if (!isValid) {
      Swal.fire({
        icon: "warning",
        title: "Campos incompletos",
        text: errorMessage,
        confirmButtonColor: "#7c6bff",
      });
    }

    return isValid;
  }

  // =========================
  // 8) CARGAR MUNICIPIOS
  // =========================
  function cargarMunicipios() {
    fetch("controlador/fichaControlador.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "listarMunicipios=ok",
    })
      .then((r) => r.json())
      .then((response) => {
        if (response.codigo === "200") {
          municipios = response.listarMunicipios;
          renderizarMunicipios();
        }
      });
  }

  function renderizarMunicipios() {
    const select = document.getElementById("selectMunicipio");
    if (!select) return;

    select.innerHTML = '<option value="">Seleccione un municipio...</option>';

    municipios.forEach((mun) => {
      const option = document.createElement("option");
      option.value = mun.idMunicipio;
      option.textContent = mun.nombreMunicipio;
      select.appendChild(option);
    });
  }

  function onMunicipioChange(e) {
    const idMunicipio = e.target.value;
    document.getElementById("idMunicipio").value = idMunicipio;

    if (idMunicipio) {
      const municipio = municipios.find((m) => m.idMunicipio == idMunicipio);
      document.getElementById("summaryMunicipio").textContent = municipio.nombreMunicipio;
      document.getElementById("summaryMunicipio").classList.remove("text-muted");

      cargarSedes(idMunicipio);
    } else {
      resetearSedes();
      resetearAmbientes();
    }
  }

  // =========================
  // 9) CARGAR SEDES
  // =========================
  function cargarSedes(idMunicipio) {
    const selectSede = document.getElementById("selectSede");
    if (!selectSede) return;

    selectSede.innerHTML = "<option value=''>Cargando sedes...</option>";
    selectSede.disabled = true;

    fetch("controlador/fichaControlador.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `listarSedesPorMunicipio=ok&idMunicipio=${idMunicipio}`,
    })
      .then((r) => r.json())
      .then((response) => {
        console.log("📦 Respuesta sedes:", response);

        if (response.codigo === "200") {
          sedes = response.listarSedes;
          renderizarSedes();
          console.log("✅ Sedes cargadas:", sedes.length);
        } else {
          console.error("❌ Error:", response.mensaje);
          mostrarError("selectSede", "No hay sedes disponibles");
        }
      })
      .catch((err) => {
        console.error("❌ Error cargando sedes:", err);
        mostrarError("selectSede", "Error al cargar sedes");
      });
  }

  function renderizarSedes() {
    const select = document.getElementById("selectSede");
    if (!select) return;

    if (sedes.length === 0) {
      select.innerHTML = '<option value="">No hay sedes disponibles</option>';
      select.disabled = true;
      return;
    }

    select.innerHTML = '<option value="">Seleccione una sede...</option>';

    sedes.forEach((sede) => {
      const option = document.createElement("option");
      option.value = sede.idSede;
      option.textContent = sede.nombre;
      select.appendChild(option);
    });

    select.disabled = false;
  }

  function onSedeChange(e) {
    const idSede = e.target.value;
    document.getElementById("idSede").value = idSede;

    if (idSede) {
      const sede = sedes.find((s) => s.idSede == idSede);
      document.getElementById("summarySede").textContent = sede.nombre;
      document.getElementById("summarySede").classList.remove("text-muted");

      cargarAmbientes(idSede);
    } else {
      resetearAmbientes();
    }
  }

  // =========================
  // 10) CARGAR AMBIENTES
  // =========================
  function cargarAmbientes(idSede) {
    const selectAmbiente = document.getElementById("selectAmbiente");
    if (!selectAmbiente) return;

    selectAmbiente.innerHTML = "<option value=''>Cargando ambientes...</option>";
    selectAmbiente.disabled = true;

    console.log("🔄 Cargando ambientes para sede:", idSede);

    fetch("controlador/fichaControlador.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `listarAmbientesPorSede=ok&idSede=${idSede}`,
    })
      .then((r) => r.json())
      .then((response) => {
        console.log("📦 Respuesta ambientes:", response);

        if (response.codigo === "200") {
          ambientes = response.listarAmbientes;
          renderizarAmbientes();
          console.log("✅ Ambientes cargados:", ambientes.length);
        } else {
          console.error("❌ Error:", response.mensaje);
          mostrarError("selectAmbiente", "No hay ambientes disponibles");
        }
      })
      .catch((err) => {
        console.error("❌ Error cargando ambientes:", err);
        mostrarError("selectAmbiente", "Error al cargar ambientes");
      });
  }

  function renderizarAmbientes() {
    const select = document.getElementById("selectAmbiente");
    if (!select) return;

    if (ambientes.length === 0) {
      select.innerHTML = '<option value="">No hay ambientes disponibles</option>';
      select.disabled = true;
      return;
    }

    select.innerHTML = '<option value="">Seleccione un ambiente...</option>';

    ambientes.forEach((amb) => {
      const option = document.createElement("option");
      option.value = amb.idAmbiente;
      option.textContent = `${amb.codigo} - Número: ${amb.numero}`;
      select.appendChild(option);
    });

    select.disabled = false;
  }

  function onAmbienteChange(e) {
    const idAmbiente = e.target.value;
    document.getElementById("idAmbiente").value = idAmbiente;

    if (idAmbiente) {
      const ambiente = ambientes.find((a) => a.idAmbiente == idAmbiente);
      document.getElementById("summaryAmbiente").textContent = `${ambiente.codigo} - #${ambiente.numero}`;
      document.getElementById("summaryAmbiente").classList.remove("text-muted");
    } else {
      document.getElementById("summaryAmbiente").textContent = "No especificado";
      document.getElementById("summaryAmbiente").classList.add("text-muted");
    }
  }

  // =========================
  // 11) CARGAR PROGRAMAS
  // =========================
  function cargarProgramas() {
    fetch("controlador/fichaControlador.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "listarProgramas=ok",
    })
      .then((r) => r.json())
      .then((response) => {
        if (response.codigo === "200") {
          programas = response.listarProgramas;
          renderizarProgramas();
          console.log("✅ Programas cargados:", programas.length);
        } else {
          console.error("❌ Error:", response.mensaje);
        }
      })
      .catch((err) => {
        console.error("❌ Error cargando programas:", err);
        mostrarError("selectPrograma", "Error al cargar programas");
      });
  }

  function renderizarProgramas() {
    const select = document.getElementById("selectPrograma");
    if (!select) return;

    select.innerHTML = '<option value="">Seleccione un programa...</option>';

    programas.forEach((prog) => {
      const option = document.createElement("option");
      option.value = prog.idPrograma;
      option.textContent = `${prog.nombre} (${prog.tipoFormacion} - ${prog.duracion} meses)`;
      option.dataset.duracion = prog.duracion;
      select.appendChild(option);
    });
  }

  function onProgramaChange(e) {
    const idPrograma = e.target.value;
    document.getElementById("idPrograma").value = idPrograma;

    if (idPrograma) {
      const programa = programas.find((p) => p.idPrograma == idPrograma);

      document.getElementById("summaryPrograma").textContent = programa.nombre;
      document.getElementById("summaryPrograma").classList.remove("text-muted");

      duracionMesesSeleccionado = parseInt(programa.duracion);
      document.getElementById("duracionMeses").value = duracionMesesSeleccionado;

      document.getElementById("duracionValue").textContent = duracionMesesSeleccionado;
      document.getElementById("infoDuracion").style.display = "block";

      calcularFechaFin();
    } else {
      document.getElementById("summaryPrograma").textContent = "No especificado";
      document.getElementById("summaryPrograma").classList.add("text-muted");
      document.getElementById("infoDuracion").style.display = "none";
      duracionMesesSeleccionado = null;
    }
  }

  // =========================
  // 12) CALCULAR FECHA FIN
  // =========================
  function calcularFechaFin() {
    const fechaInicio = document.getElementById("fecha_inicio")?.value;

    if (!fechaInicio || !duracionMesesSeleccionado) return;

    const fecha = new Date(fechaInicio + "T00:00:00");
    fecha.setMonth(fecha.getMonth() + duracionMesesSeleccionado);

    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, "0");
    const day = String(fecha.getDate()).padStart(2, "0");

    const fechaFin = `${year}-${month}-${day}`;
    document.getElementById("fecha_fin").value = fechaFin;

    const fechaInicioFormat = new Date(fechaInicio).toLocaleDateString("es-CO");
    const fechaFinFormat = new Date(fechaFin).toLocaleDateString("es-CO");

    document.getElementById("summaryFechas").textContent = `${fechaInicioFormat} - ${fechaFinFormat}`;
    document.getElementById("summaryFechas").classList.remove("text-muted");
  }

  // =========================
  // 13) ACTUALIZAR RESUMEN
  // =========================
  function actualizarResumen() {
    const codigo = document.getElementById("codigo").value.trim();
    const jornada = document.getElementById("jornada").value;

    if (codigo) {
      document.getElementById("summaryCodigo").textContent = codigo;
      document.getElementById("summaryCodigo").classList.remove("text-muted");
    } else {
      document.getElementById("summaryCodigo").textContent = "No especificado";
      document.getElementById("summaryCodigo").classList.add("text-muted");
    }

    if (jornada) {
      const jornadaTexts = {
        "MAÑANA": "🌅 Mañana",
        "TARDE": "☀️ Tarde",
        "NOCHE": "🌙 Noche",
      };
      document.getElementById("summaryJornada").textContent = jornadaTexts[jornada] || jornada;
      document.getElementById("summaryJornada").classList.remove("text-muted");
    } else {
      document.getElementById("summaryJornada").textContent = "No especificado";
      document.getElementById("summaryJornada").classList.add("text-muted");
    }
  }

  // =========================
  // 14) RESETEAR CAMPOS
  // =========================
  function resetearSedes() {
    const select = document.getElementById("selectSede");
    if (!select) return;
    select.innerHTML = "<option value=''>Primero seleccione un municipio</option>";
    select.disabled = true;
    document.getElementById("idSede").value = "";
    document.getElementById("summarySede").textContent = "No especificado";
    document.getElementById("summarySede").classList.add("text-muted");
  }

  function resetearAmbientes() {
    const select = document.getElementById("selectAmbiente");
    if (!select) return;
    select.innerHTML = "<option value=''>Primero seleccione una sede</option>";
    select.disabled = true;
    document.getElementById("idAmbiente").value = "";
    document.getElementById("summaryAmbiente").textContent = "No especificado";
    document.getElementById("summaryAmbiente").classList.add("text-muted");
  }

  function mostrarError(selectId, mensaje) {
    const select = document.getElementById(selectId);
    if (!select) return;
    select.innerHTML = `<option value="">${mensaje}</option>`;
    select.disabled = true;
  }

  // =========================
  // 15) SUBMIT FORMULARIO
  // =========================
  function onSubmit(event) {
    event.preventDefault();
    event.stopPropagation();

    const codigo = document.getElementById("codigo").value.trim();
    const idPrograma = document.getElementById("idPrograma").value;
    const idAmbiente = document.getElementById("idAmbiente").value;
    const jornada = document.getElementById("jornada").value;
    const fechaInicio = document.getElementById("fecha_inicio").value;
    const fechaFin = document.getElementById("fecha_fin").value;

    if (!codigo || !idPrograma || !idAmbiente || !jornada || !fechaInicio || !fechaFin) {
      Swal.fire({
        icon: "error",
        title: "Campos incompletos",
        text: "Por favor complete todos los campos del formulario",
        confirmButtonColor: "#7c6bff",
      });
      return;
    }

    Swal.fire({
      title: "Creando ficha...",
      html: "Por favor espere",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const formData = new FormData();
    formData.append("registrarFicha", "ok");
    formData.append("codigoFicha", codigo);
    formData.append("idPrograma", idPrograma);
    formData.append("idAmbiente", idAmbiente);
    formData.append("estado", "Activo");
    formData.append("jornada", jornada);
    formData.append("fechaInicio", fechaInicio);
    formData.append("fechaFin", fechaFin);

    // En ficha.js - dentro de la función onSubmit (cerca de la línea donde hace el fetch)

fetch("controlador/fichaControlador.php", {
    method: "POST",
    body: formData,
})
.then((response) => response.json())
.then((data) => {
    console.log("📨 Respuesta:", data);
    
    // ✅ CERRAR EL SPINNER SIEMPRE, aunque sea error
    Swal.close();
    
    if (data.codigo === "200") {
        Swal.fire({
            icon: "success",
            title: "¡Ficha creada!",
            text: data.mensaje,
            confirmButtonColor: "#7c6bff",
        }).then(() => window.location.reload());
        
    } else if (data.codigo === "409") {
        // ⚠️ ERROR DE CONFLICTO DE JORNADA
        Swal.fire({
            icon: "error",
            title: "Conflicto de Jornada",
            html: `<p style="text-align: center; margin-bottom: 15px;">${data.mensaje}</p>
                   <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; text-align: left; border-radius: 8px;">
                       <strong>💡 Solución:</strong><br>
                       • Cambia el ambiente<br>
                       • Cambia la jornada (Mañana/Tarde/Noche)<br>
                       • Cambia las fechas de inicio/fin
                   </div>`,
            confirmButtonColor: "#7c6bff",
            confirmButtonText: "Entendido"
        });
        
    } else {
        // ❌ OTROS ERRORES
        Swal.fire({
            icon: "error",
            title: "Error al crear ficha",
            text: data.mensaje || "Error desconocido al crear la ficha",
            confirmButtonColor: "#7c6bff",
        });
    }
})
.catch((error) => {
    console.error("❌ Error:", error);
    
    // ✅ CERRAR SPINNER TAMBIÉN EN CASO DE ERROR DE RED
    Swal.close();
    
    Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor. Verifica tu conexión.",
        confirmButtonColor: "#7c6bff",
    });
    });


}   

// ========== VALIDACIÓN Y SUBMIT DEL FORMULARIO DE EDICIÓN ==========
const formEditarFicha = document.getElementById("formEditarFicha");
if(formEditarFicha){
  formEditarFicha.addEventListener("submit", function(event){
    event.preventDefault();
    event.stopPropagation();

    console.log("🔄 Submit del formulario de edición activado");

    if(!formEditarFicha.checkValidity()){
      formEditarFicha.classList.add("was-validated");
      
      Swal.fire({
        icon: 'warning',
        title: 'Campos incompletos',
        text: 'Por favor completa todos los campos obligatorios',
        confirmButtonColor: '#7c6bff'
      });
      
    } else {
      // ✅ Formulario válido, proceder a guardar
      const obj = new ficha({});
      obj.editarFicha();
    }
  }, false);
}

// ========== FUNCIÓN CORREGIDA: CARGAR AMBIENTES EN EDICIÓN ==========
function cargarAmbientesEdit(idSede, idAmbienteActual){
  const select = document.getElementById("selectAmbienteEdit");
  if(!select) return;

  select.innerHTML = "<option value=''>Cargando ambientes...</option>";
  select.disabled = true;

  console.log("🔄 Cargando ambientes para sede:", idSede, "| Actual:", idAmbienteActual);

  fetch("controlador/fichaControlador.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `listarAmbientesPorSede=ok&idSede=${idSede}`,
  })
  .then(r => r.json())
  .then(resp => {
    console.log("📦 Respuesta ambientes:", resp);
    
    if(resp.codigo === "200"){
      select.innerHTML = "<option value=''>Seleccione un ambiente...</option>";

      resp.listarAmbientes.forEach(amb => {
        const opt = document.createElement("option");
        opt.value = amb.idAmbiente;
        opt.textContent = `${amb.codigo} - Número: ${amb.numero}`;
        select.appendChild(opt);
      });

      select.disabled = false;
      
      // ✅ Seleccionar el ambiente actual
      if(idAmbienteActual){
        select.value = idAmbienteActual;
      }
      
    } else {
      select.innerHTML = "<option value=''>No hay ambientes disponibles</option>";
      select.disabled = true;
    }
  })
  .catch(err => {
    console.error("❌ Error cargando ambientes:", err);
    select.innerHTML = "<option value=''>Error al cargar</option>";
    select.disabled = true;
  });
}

// ========== EVENTO EDITAR FICHA (CORREGIDO) ==========
$(document).on("click", ".btnEditarFicha", function(e){
  e.preventDefault();
  e.stopPropagation();

  const idFicha = $(this).data("idficha");
  const codigo = $(this).data("codigo");
  const programa = $(this).data("programa");
  const sede = $(this).data("sede");
  const idSede = $(this).data("idsede");
  const idAmbiente = $(this).data("idambiente");
  const numeroAmbiente = $(this).data("numeroambiente");
  const estado = $(this).data("estado");
  const jornada = $(this).data("jornada");
  const fechaInicio = $(this).data("fechainicio");
  const fechaFin = $(this).data("fechafin");

  console.log("✏️ Editando ficha:", {idFicha, codigo, idSede, idAmbiente});

  // ========== CAMPOS OCULTOS ==========
  document.getElementById("idFichaEdit").value = idFicha;
  document.getElementById("jornadaEdit").value = jornada;
  document.getElementById("idSedeEdit").value = idSede;

  // ========== PANEL DE INFORMACIÓN (NO EDITABLE) ==========
  document.getElementById("codigoEditDisplay").textContent = codigo;
  document.getElementById("programaEditDisplay").textContent = programa;
  document.getElementById("sedeEditDisplay").textContent = sede;
  
  const jornadaIcons = {
    'MAÑANA': '🌅 Mañana',
    'TARDE': '☀️ Tarde',
    'NOCHE': '🌙 Noche'
  };
  document.getElementById("jornadaEditDisplay").textContent = jornadaIcons[jornada] || jornada;

  // ========== CAMPOS EDITABLES ==========
  document.getElementById("estadoEdit").value = estado;
  document.getElementById("fechaInicioEdit").value = fechaInicio;
  document.getElementById("fechaFinEdit").value = fechaFin;

  // ========== CARGAR AMBIENTES Y SELECCIONAR ACTUAL ==========
  if(idSede){
    cargarAmbientesEdit(idSede, idAmbiente);
  } else {
    console.error("❌ No se encontró idSede para cargar ambientes");
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo cargar los ambientes. Falta información de la sede.',
      confirmButtonColor: '#7c6bff'
    });
  }

  // ========== MOSTRAR PANEL ==========
  $("#panelTablaFichas").hide();
  $("#panelCrearFicha").hide();
  $("#panelEditarFicha").show();
  
  // Quitar validación previa si existe
  $("#formEditarFicha").removeClass("was-validated");
  
  // Scroll al inicio
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ========== BOTONES CANCELAR/VOLVER (EDITANDO) ==========
$("#btnCancelarEditarFicha, #btnVolverTablaEdit").on("click", function(e){
  e.preventDefault();
  $("#panelEditarFicha").hide();
  $("#panelTablaFichas").show();
  $("#formEditarFicha").removeClass("was-validated");
});

})();