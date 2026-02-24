(function () {

  // ── ESTADO GLOBAL ──────────────────────────────────────────
  let duracionMesesSeleccionado = null;
  let municipios = [], sedes = [], ambientes = [], programas = [];
  let currentSection = 1;

  // ── HELPERS ────────────────────────────────────────────────
  const el      = (id) => document.getElementById(id);
  const setVal  = (id, val) => { const e = el(id); if(e) e.value = val; };
  const setTxt  = (id, val) => { const e = el(id); if(e) e.textContent = val; };
  const setMuted = (id, txt) => { const e = el(id); if(!e) return; e.textContent = txt; e.classList.add("text-muted"); };
  const unMuted  = (id, txt) => { const e = el(id); if(!e) return; e.textContent = txt; e.classList.remove("text-muted"); };

  function api(body){
    return fetch("controlador/fichaControlador.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body
    }).then(r => r.json());
  }

  function swal(opts){ return Swal.fire({ confirmButtonColor:"#7c6bff", ...opts }); }

  function selectError(id, msg){
    const s = el(id); if(!s) return;
    s.innerHTML = `<option value="">${msg}</option>`;
    s.disabled = true;
  }

  function llenarSelect(id, items, valueProp, textFn, placeholder = "Seleccione..."){
    const s = el(id); if(!s) return;
    s.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
      const opt = document.createElement("option");
      opt.value = item[valueProp];
      opt.textContent = textFn(item);
      s.appendChild(opt);
    });
    s.disabled = false;
  }

  // ── INIT ───────────────────────────────────────────────────
  listarFicha();

  document.addEventListener("DOMContentLoaded", () => {
    cargarMunicipios();
    cargarProgramas();
    configurarPaneles();
    configurarEventListeners();
  });

  // ── LISTAR FICHA ───────────────────────────────────────────
  function listarFicha(){
    new ficha({ listarFicha:"ok" }).listarFicha();
  }

  // ── PANELES ────────────────────────────────────────────────
  function configurarPaneles(){
    const btnCrear  = el("btnCrearFicha");
    const btnVolver = el("btnVolverTabla");

    if(btnCrear) btnCrear.addEventListener("click", () => {
      el("panelTablaFichas").style.display = "none";
      el("panelCrearFicha").style.display  = "block";
    });

    if(btnVolver) btnVolver.addEventListener("click", () => {
      el("panelCrearFicha").style.display  = "none";
      el("panelTablaFichas").style.display = "block";
      const f = el("formCrearFicha"); if(f) f.reset();
      resetSteps();
      resetResumen();
      resetearSedes();
      resetearAmbientes();
      duracionMesesSeleccionado = null;
      currentSection = 1;
    });

    // Cancelar edición
    $("#btnCancelarEditarFicha, #btnVolverTablaEdit").on("click", e => {
      e.preventDefault();
      $("#panelEditarFicha").hide();
      $("#panelTablaFichas").show();
      $("#formEditarFicha").removeClass("was-validated");
    });
  }

  function resetSteps(){
    document.querySelectorAll(".form-section").forEach(s => s.classList.remove("active"));
    document.querySelectorAll(".step").forEach(s => s.classList.remove("active","completed"));
    el("section1")?.classList.add("active");
    el("step1")?.classList.add("active");
  }

  function resetResumen(){
    ["summaryCodigo","summaryJornada","summaryMunicipio",
     "summarySede","summaryAmbiente","summaryPrograma","summaryFechas"]
      .forEach(id => setMuted(id, "No especificado"));
    const inf = el("infoDuracion"); if(inf) inf.style.display = "none";
  }

  // ── EVENT LISTENERS ────────────────────────────────────────
  function configurarEventListeners(){
    el("codigo")?.addEventListener("input",   actualizarResumen);
    el("jornada")?.addEventListener("change", actualizarResumen);
    el("selectMunicipio")?.addEventListener("change", e => onCascadaChange(e, "municipio"));
    el("selectSede")?.addEventListener("change",      e => onCascadaChange(e, "sede"));
    el("selectAmbiente")?.addEventListener("change",  e => onCascadaChange(e, "ambiente"));
    el("selectPrograma")?.addEventListener("change",  e => onCascadaChange(e, "programa"));
    el("fecha_inicio")?.addEventListener("change", calcularFechaFin);
    el("formCrearFicha")?.addEventListener("submit", onSubmit);

    const formEditar = el("formEditarFicha");
    if(formEditar) formEditar.addEventListener("submit", e => {
      e.preventDefault(); e.stopPropagation();
      if(!formEditar.checkValidity()){
        formEditar.classList.add("was-validated");
        swal({ icon:"warning", title:"Campos incompletos", text:"Por favor completa todos los campos." });
      } else {
        new ficha({}).editarFicha();
      }
    });
  }

  // ── CASCADA DE SELECTS ─────────────────────────────────────
  function onCascadaChange(e, tipo){
    const val = e.target.value;
    switch(tipo){
      case "municipio":
        setVal("idMunicipio", val);
        if(val){ unMuted("summaryMunicipio", municipios.find(m=>m.idMunicipio==val)?.nombreMunicipio); cargarSedes(val); }
        else   { resetearSedes(); resetearAmbientes(); }
        break;
      case "sede":
        setVal("idSede", val);
        if(val){ unMuted("summarySede", sedes.find(s=>s.idSede==val)?.nombre); cargarAmbientes(val); }
        else   { resetearAmbientes(); }
        break;
      case "ambiente":
        setVal("idAmbiente", val);
        const amb = ambientes.find(a=>a.idAmbiente==val);
        amb ? unMuted("summaryAmbiente", `${amb.codigo} - #${amb.numero}`)
            : setMuted("summaryAmbiente", "No especificado");
        break;
      case "programa":
        setVal("idPrograma", val);
        const prog = programas.find(p=>p.idPrograma==val);
        if(prog){
          unMuted("summaryPrograma", prog.nombre);
          duracionMesesSeleccionado = parseInt(prog.duracion);
          setVal("duracionMeses", duracionMesesSeleccionado);
          setTxt("duracionValue", duracionMesesSeleccionado);
          const inf = el("infoDuracion"); if(inf) inf.style.display = "block";
          calcularFechaFin();
        } else {
          setMuted("summaryPrograma", "No especificado");
          const inf = el("infoDuracion"); if(inf) inf.style.display = "none";
          duracionMesesSeleccionado = null;
        }
        break;
    }
  }

  // ── CARGAR DATOS ───────────────────────────────────────────
  function cargarMunicipios(){
    api("listarMunicipios=ok").then(r => {
      if(r.codigo==="200"){ municipios = r.listarMunicipios; llenarSelect("selectMunicipio", municipios, "idMunicipio", m => m.nombreMunicipio, "Seleccione un municipio..."); }
    });
  }

  function cargarSedes(idMunicipio){
    const s = el("selectSede"); if(!s) return;
    s.innerHTML = "<option value=''>Cargando...</option>"; s.disabled = true;
    api(`listarSedesPorMunicipio=ok&idMunicipio=${idMunicipio}`)
      .then(r => r.codigo==="200" ? (sedes=r.listarSedes, llenarSelect("selectSede", sedes, "idSede", s=>s.nombre, "Seleccione una sede..."))
                                  : selectError("selectSede","No hay sedes disponibles"))
      .catch(() => selectError("selectSede","Error al cargar sedes"));
  }

  function cargarAmbientes(idSede){
    const s = el("selectAmbiente"); if(!s) return;
    s.innerHTML = "<option value=''>Cargando...</option>"; s.disabled = true;
    api(`listarAmbientesPorSede=ok&idSede=${idSede}`)
      .then(r => r.codigo==="200" ? (ambientes=r.listarAmbientes, llenarSelect("selectAmbiente", ambientes, "idAmbiente", a=>`${a.codigo} - Número: ${a.numero} - Area: ${a.nombreArea}`, "Seleccione un ambiente..."))
                                  : selectError("selectAmbiente","No hay ambientes disponibles"))
      .catch(() => selectError("selectAmbiente","Error al cargar ambientes"));
  }

  function cargarProgramas(){
    api("listarProgramas=ok").then(r => {
      if(r.codigo==="200"){ programas = r.listarProgramas; llenarSelect("selectPrograma", programas, "idPrograma", p=>`${p.nombre} (${p.tipoFormacion} - ${p.duracion} meses)`, "Seleccione un programa..."); }
    });
  }

  // ── RESUMEN Y FECHAS ───────────────────────────────────────
  function actualizarResumen(){
    const cod = el("codigo")?.value.trim();
    const jor = el("jornada")?.value;
    cod ? unMuted("summaryCodigo", cod) : setMuted("summaryCodigo","No especificado");
    const map = { "MAÑANA":"🌅 Mañana","TARDE":"☀️ Tarde","NOCHE":"🌙 Noche" };
    jor ? unMuted("summaryJornada", map[jor]||jor) : setMuted("summaryJornada","No especificado");
  }

  function calcularFechaFin(){
    const inicio = el("fecha_inicio")?.value;
    if(!inicio || !duracionMesesSeleccionado) return;
    const f = new Date(inicio+"T00:00:00");
    f.setMonth(f.getMonth() + duracionMesesSeleccionado);
    const fin = `${f.getFullYear()}-${String(f.getMonth()+1).padStart(2,"0")}-${String(f.getDate()).padStart(2,"0")}`;
    setVal("fecha_fin", fin);
    unMuted("summaryFechas", `${new Date(inicio).toLocaleDateString("es-CO")} - ${new Date(fin).toLocaleDateString("es-CO")}`);
  }

  // ── RESET CASCADA ──────────────────────────────────────────
  function resetearSedes(){
    const s = el("selectSede"); if(!s) return;
    s.innerHTML = "<option value=''>Primero seleccione un municipio</option>"; s.disabled = true;
    setVal("idSede",""); setMuted("summarySede","No especificado");
    resetearAmbientes();
  }

  function resetearAmbientes(){
    const s = el("selectAmbiente"); if(!s) return;
    s.innerHTML = "<option value=''>Primero seleccione una sede</option>"; s.disabled = true;
    setVal("idAmbiente",""); setMuted("summaryAmbiente","No especificado");
  }

  // ── NAVEGACIÓN STEPS ───────────────────────────────────────
  const VALIDACIONES = {
    1: () => { const c=el("codigo")?.value.trim(), j=el("jornada")?.value; return !c?"Ingrese el código":!j?"Seleccione una jornada":null; },
    2: () => { const m=el("idMunicipio")?.value, s=el("idSede")?.value, a=el("idAmbiente")?.value; return !m?"Seleccione un municipio":!s?"Seleccione una sede":!a?"Seleccione un ambiente":null; },
    3: () => el("idPrograma")?.value ? null : "Seleccione un programa"
  };

  window.nextSection = function(next){
    const err = VALIDACIONES[currentSection]?.();
    if(err){ swal({ icon:"warning", title:"Campos incompletos", text:err }); return; }
    el(`section${currentSection}`)?.classList.remove("active");
    const stepEl = el(`step${currentSection}`);
    stepEl?.classList.replace("active","completed");
    const icon = stepEl?.querySelector(".step-icon i");
    if(icon) icon.className = "bi bi-check-circle-fill";
    currentSection = next;
    el(`section${next}`)?.classList.add("active","next");
    el(`step${next}`)?.classList.add("active");
    setTimeout(() => el(`section${next}`)?.classList.remove("next"), 400);
    window.scrollTo({ top:0, behavior:"smooth" });
  };

  window.prevSection = function(prev){
    el(`section${currentSection}`)?.classList.remove("active");
    el(`step${currentSection}`)?.classList.remove("active");
    currentSection = prev;
    el(`section${prev}`)?.classList.add("active","prev");
    el(`step${prev}`)?.classList.add("active");
    el(`step${prev}`)?.classList.remove("completed");
    const icon = el(`step${prev}`)?.querySelector(".step-icon i");
    if(icon) icon.className = `bi bi-${prev}-circle-fill`;
    setTimeout(() => el(`section${prev}`)?.classList.remove("prev"), 400);
    window.scrollTo({ top:0, behavior:"smooth" });
  };

  // ── SUBMIT CREAR ───────────────────────────────────────────
  function onSubmit(event){
    event.preventDefault(); event.stopPropagation();
    const campos = { codigo:el("codigo")?.value.trim(), idPrograma:el("idPrograma")?.value, idAmbiente:el("idAmbiente")?.value, jornada:el("jornada")?.value, fechaInicio:el("fecha_inicio")?.value, fechaFin:el("fecha_fin")?.value };
    if(Object.values(campos).some(v=>!v)){ swal({ icon:"error", title:"Campos incompletos", text:"Complete todos los campos." }); return; }

    Swal.fire({ title:"Creando ficha...", html:"Por favor espere", allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

    const fd = new FormData();
    fd.append("registrarFicha","ok");
    Object.entries({ codigoFicha:campos.codigo, idPrograma:campos.idPrograma, idAmbiente:campos.idAmbiente, estado:"Activo", jornada:campos.jornada, fechaInicio:campos.fechaInicio, fechaFin:campos.fechaFin })
      .forEach(([k,v]) => fd.append(k,v));

    fetch("controlador/fichaControlador.php",{ method:"POST", body:fd })
      .then(r=>r.json())
      .then(data => { Swal.close(); manejarRespuesta(data, ()=>window.location.reload()); })
      .catch(()  => { Swal.close(); swal({ icon:"error", title:"Error de conexión", text:"No se pudo conectar." }); });
  }

  // ── RESPUESTA REUTILIZABLE ─────────────────────────────────
  function manejarRespuesta(data, onSuccess){
    if(data.codigo==="200"){
      swal({ icon:"success", title:"¡Éxito!", text:data.mensaje }).then(onSuccess);
    } else if(data.codigo==="409"){
      swal({ icon:"error", title:"Conflicto de Jornada", html:`<p>${data.mensaje}</p><div style="background:#fff3cd;border-left:4px solid #ffc107;padding:12px;border-radius:8px"><strong>💡 Solución:</strong><br>• Cambia el ambiente<br>• Cambia la jornada<br>• Cambia las fechas</div>`, confirmButtonText:"Entendido" });
    } else {
      swal({ icon:"error", title:"Error", text:data.mensaje||"Error desconocido" });
    }
  }

  // ── EDITAR FICHA ───────────────────────────────────────────
  function cargarAmbientesEdit(idSede, idAmbienteActual){
    const s = el("selectAmbienteEdit"); if(!s) return;
    s.innerHTML = "<option value=''>Cargando...</option>"; s.disabled = true;
    api(`listarAmbientesPorSede=ok&idSede=${idSede}`)
      .then(r => {
        if(r.codigo==="200"){
          llenarSelect("selectAmbienteEdit", r.listarAmbientes, "idAmbiente", a=>`${a.codigo} - Número: ${a.numero}`, "Seleccione un ambiente...");
          if(idAmbienteActual) el("selectAmbienteEdit").value = idAmbienteActual;
        } else { selectError("selectAmbienteEdit","No hay ambientes disponibles"); }
      })
      .catch(() => selectError("selectAmbienteEdit","Error al cargar"));
  }

  $(document).on("click", ".btnEditarFicha", function(e){
    e.preventDefault(); e.stopPropagation();
    const d = $(this).data();
    const jornadaIcons = { MAÑANA:"🌅 Mañana", TARDE:"☀️ Tarde", NOCHE:"🌙 Noche" };

    ["idFichaEdit","jornadaEdit","idSedeEdit","estadoEdit","fechaInicioEdit","fechaFinEdit"]
      .forEach(id => setVal(id, d[id.replace("Edit","").replace("id","id").toLowerCase()] ?? d[{idFichaEdit:"idficha",jornadaEdit:"jornada",idSedeEdit:"idsede",estadoEdit:"estado",fechaInicioEdit:"fechainicio",fechaFinEdit:"fechafin"}[id]]));

    setTxt("codigoEditDisplay",   d.codigo);
    setTxt("programaEditDisplay", d.programa);
    setTxt("sedeEditDisplay",     d.sede);
    setTxt("jornadaEditDisplay",  jornadaIcons[d.jornada] || d.jornada);

    if(d.idsede) cargarAmbientesEdit(d.idsede, d.idambiente);
    else swal({ icon:"error", title:"Error", text:"Falta información de la sede." });

    $("#panelTablaFichas, #panelCrearFicha").hide();
    $("#panelEditarFicha").show();
    $("#formEditarFicha").removeClass("was-validated");
    window.scrollTo({ top:0, behavior:"smooth" });
  });

})();