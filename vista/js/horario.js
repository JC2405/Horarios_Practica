document.addEventListener('DOMContentLoaded', function () {

  /* ══════════════════════════════════════════════════
     ESTADO
  ══════════════════════════════════════════════════ */
  let todasLasFichas       = [];
  let todosLosInstructores = [];
  const COL_DIA = { 1:null, 2:null, 3:null, 4:null, 5:null, 6:null };
  const DIAS_MAP = { 'Lunes':1,'Martes':2,'Miércoles':3,'Miercoles':3,'Jueves':4,'Viernes':5,'Sábado':6,'Sabado':6 };

  /* ══════════════════════════════════════════════════
     INIT
  ══════════════════════════════════════════════════ */
  listarHorarios();
  cargarDiasDB();
  cargarSedes();
  cargarTodasLasFichas();
  cargarInstructoresTodos();

  /* ══════════════════════════════════════════════════
     PANELES
  ══════════════════════════════════════════════════ */
  const mostrarPanel = id =>
    ['panelTablaHorario','panelFormularioHorario'].forEach(p => {
      const el = document.getElementById(p);
      if (el) el.style.display = p === id ? 'block' : 'none';
    });

  document.getElementById('btnNuevoHorario')?.addEventListener('click', () => {
    resetFormCrear(); mostrarPanel('panelFormularioHorario');
  });
  ['btnRegresarTablaHorario','btnCancelarHorario'].forEach(id =>
    document.getElementById(id)?.addEventListener('click', () => mostrarPanel('panelTablaHorario'))
  );

  /* ══════════════════════════════════════════════════
     EVENTOS — FORMULARIO
  ══════════════════════════════════════════════════ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    resetSelect('selectAmbienteHorario', '— Seleccione ambiente —');
    resetSelect('selectFichaHorario',    '— Seleccione sede primero —');
    renderInstructoresLista(todosLosInstructores);
    ocultarHint();
    if (!this.value) return;
    cargarAmbientesPorSede(this.value);
    filtrarFichasPorSedeYJornada(this.value, document.getElementById('selectJornadaHorario')?.value || '');
  });

  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    actualizarPreview();
    const idArea = opt?.dataset.idarea;
    idArea ? fetchInstructoresPorArea(idArea, opt.dataset.area || '') : renderInstructoresLista(todosLosInstructores);
  });

  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    const idSede = document.getElementById('selectSedeHorario')?.value || '';
    idSede ? filtrarFichasPorSedeYJornada(idSede, this.value) : resetSelect('selectFichaHorario', '— Seleccione sede primero —');
    actualizarPreview();
  });

  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const tv = this.options[this.selectedIndex]?.dataset.tipoprograma || '';
    const inp = document.getElementById('inputTipoPrograma');
    if (inp) { inp.value = tv; inp.classList.add('input-filled'); setTimeout(() => inp.classList.remove('input-filled'), 800); }
    actualizarPreview();
  });

  ['horaInicioHorario','horaFinHorario','fechaInicioHorario','fechaFinHorario','selectInstructorHorario','selectSedeHorario']
    .forEach(id => document.getElementById(id)?.addEventListener('change', actualizarPreview));

  document.getElementById('inputBuscarInstructor')?.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    if (!q) { renderInstructoresLista(todosLosInstructores); return; }
    renderInstructoresLista(todosLosInstructores.filter(i =>
      (i.nombre||'').toLowerCase().includes(q) || (i.nombreArea||'').toLowerCase().includes(q)
    ));
  });


  document.getElementById('formCrearHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const dias = getDiasSeleccionados();
    if (!dias.length) {
      Swal.fire({ icon:'warning', title:'Días requeridos', text:'Selecciona al menos un día.', confirmButtonColor:'#7c6bff' });
      return;
    }
    crearHorario(dias);
  });

  /* ══════════════════════════════════════════════════
     DELEGACIÓN — TABLA
  ══════════════════════════════════════════════════ */
  $(document).on('click', '.btnVerHorario', function (e) {
    e.preventDefault();
    HorarioCalendar.abrirModal($(this).data('id-ficha'), {
      ficha:   $(this).data('ficha')   || '—',
      sede:    $(this).data('sede')    || '—',
      area:    $(this).data('area')    || '—',
      jornada: $(this).data('jornada') || '—',
      tipo:    $(this).data('tipo')    || '—',
    });
  });

  $(document).on('click', '.btnEliminarHorariosFicha', function (e) {
    e.preventDefault();
    abrirModalEliminar($(this).data('id-ficha'), $(this).data('ficha') || '—');
  });

  $(document).on('click', '.btnConfirmarEliminarHorario', function () {
    const { id: idHorario, idFicha, ficha } = $(this).data();
    Swal.fire({
      title:'¿Eliminar este horario?', icon:'warning', showCancelButton:true,
      confirmButtonColor:'#ef4444', confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar'
    }).then(r => {
      if (!r.isConfirmed) return;
      postJSON('horarioControlador.php', { eliminarHorario:'ok', idHorario }).then(resp => {
        if (resp.codigo === '200') {
          Swal.fire({ icon:'success', title:'Eliminado', timer:1400, showConfirmButton:false });
          listarHorarios();
          setTimeout(() => {
            postJSON('horarioControlador.php', { listarHorariosPorFicha:'ok', idFicha }).then(r2 =>
              r2.horarios?.length
                ? abrirModalEliminar(idFicha, ficha)
                : bootstrap.Modal.getInstance(document.getElementById('modalEliminarHorarios'))?.hide()
            );
          }, 1500);
        } else {
          Swal.fire({ icon:'error', title:'Error', text:resp.mensaje });
        }
      });
    });
  });

  /* ══════════════════════════════════════════════════
     LISTAR HORARIOS (tabla principal)
  ══════════════════════════════════════════════════ */
  function listarHorarios() {
    postJSON('horarioControlador.php', { listarFichasConHorario:'ok' }).then(resp => {
      if (!resp || resp.codigo !== '200') return;
      if ($.fn.DataTable.isDataTable('#tablaHorarios')) $('#tablaHorarios').DataTable().clear().destroy();

      const data = (resp.horarios || []).map(item => {
        const j = (item.jornada || '').toUpperCase();
        const badge =
          j === 'MAÑANA' ? '<span class="badge-jornada badge-manana">🌅 Mañana</span>' :
          j === 'TARDE'  ? '<span class="badge-jornada badge-tarde">☀️ Tarde</span>'   :
          j === 'NOCHE'  ? '<span class="badge-jornada badge-noche">🌙 Noche</span>'   :
          `<span class="badge-jornada">${item.jornada||'—'}</span>`;

        return [
          item.sedeNombre  || '—',
          item.areaNombre  || '—',
          `<strong>${item.codigoFicha||'—'}</strong><br><small class="text-muted">${item.nombrePrograma||''}</small>`,
          badge,
          item.tipoPrograma || '—',
          item.ambienteNombre || '—',
          `<span class="badge bg-primary rounded-pill">${item.totalHorarios||0} horario${item.totalHorarios==1?'':'s'}</span>`,
          `<div class="action-group">
            <button class="btn btn-ver btnVerHorario"
              data-id-ficha="${item.idFicha||''}" data-ficha="${item.codigoFicha||'—'}"
              data-sede="${item.sedeNombre||'—'}" data-area="${item.areaNombre||'—'}"
              data-jornada="${item.jornada||'—'}" data-tipo="${item.tipoPrograma||'—'}">
              <i class="bi bi-eye-fill"></i></button>
            <button class="btn btn-danger btnEliminarHorariosFicha"
              data-id-ficha="${item.idFicha||''}" data-ficha="${item.codigoFicha||'—'}">
              <i class="bi bi-trash"></i></button>
          </div>`
        ];
      });

      $('#tablaHorarios').DataTable({
        buttons:[{extend:'colvis',text:'Columnas'},'excel','pdf','print'],
        dom:'Bfrtip', responsive:true, destroy:true, data,
        language:{ emptyTable:'— Sin fichas con horarios —', search:'Buscar:', paginate:{next:'Sig.',previous:'Ant.'} }
      });
    });
  }

  /* ══════════════════════════════════════════════════
     MODAL ELIMINAR
  ══════════════════════════════════════════════════ */
  function abrirModalEliminar(idFicha, codigoFicha) {
    if (!document.getElementById('modalEliminarHorarios')) {
      document.body.insertAdjacentHTML('beforeend', `
        <div class="modal fade" id="modalEliminarHorarios" tabindex="-1">
          <div class="modal-dialog modal-md modal-dialog-centered"><div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Eliminar — Ficha <span id="elimModal_ficha"></span></h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="elimModal_body"></div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></div>
          </div></div>
        </div>`);
    }
    document.getElementById('elimModal_ficha').textContent = codigoFicha;
    document.getElementById('elimModal_body').innerHTML = '<div class="text-center py-3"><i class="bi bi-hourglass-split"></i> Cargando...</div>';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEliminarHorarios')).show();

    postJSON('horarioControlador.php', { listarHorariosPorFicha:'ok', idFicha }).then(resp => {
      const body = document.getElementById('elimModal_body');
      if (resp.codigo !== '200' || !resp.horarios?.length) {
        body.innerHTML = '<p class="text-muted text-center py-3">No hay horarios para esta ficha.</p>'; return;
      }
      body.innerHTML = '<p class="text-muted mb-3" style="font-size:13px;">Selecciona el horario a eliminar:</p>' +
        resp.horarios.map(h => `
          <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2">
            <div style="font-size:13px;line-height:1.6">
              <div><i class="bi bi-person-fill text-primary me-1"></i><strong>${h.instructorNombre||'—'}</strong></div>
              <div><i class="bi bi-clock me-1 text-secondary"></i>${h.hora_inicioClase||'?'} – ${h.hora_finClase||'?'}</div>
              <div><i class="bi bi-calendar-week me-1 text-secondary"></i>${h.diasNombres||'—'}</div>
              <div><i class="bi bi-door-open me-1 text-secondary"></i>${h.ambienteNombre||'—'}</div>
            </div>
            <button class="btn btn-danger btn-sm btnConfirmarEliminarHorario ms-3"
              data-id="${h.idHorario}" data-id-ficha="${idFicha}" data-ficha="${codigoFicha}">
              <i class="bi bi-trash"></i></button>
          </div>`).join('');
    });
  }

  /* ══════════════════════════════════════════════════
     CREAR HORARIO
  ══════════════════════════════════════════════════ */
  function crearHorario(dias) {
    Swal.fire({ title:'Guardando...', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
    const g  = id => document.getElementById(id)?.value || '';
    const fd = new FormData();
    Object.entries({
      crearHorario:'ok', idFuncionario:g('selectInstructorHorario'),
      idAmbiente:g('selectAmbienteHorario'), idFicha:g('selectFichaHorario'),
      hora_inicioClase:g('horaInicioHorario'), hora_finClase:g('horaFinHorario'),
      fecha_inicioHorario:g('fechaInicioHorario'), fecha_finHorario:g('fechaFinHorario')
    }).forEach(([k,v]) => fd.append(k, v));
    dias.forEach(id => fd.append('dias[]', id));

    fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({ icon:'success', title:'¡Horario creado!', timer:1800, showConfirmButton:false });
          mostrarPanel('panelTablaHorario'); listarHorarios(); resetFormCrear();
        } else {
          Swal.fire({ icon:'error', title:'Error', html:resp.mensaje, confirmButtonColor:'#7c6bff' });
        }
      })
      .catch(err => { Swal.close(); Swal.fire({ icon:'error', title:'Error de conexión', text:String(err) }); });
  }

  /* ══════════════════════════════════════════════════
     INSTRUCTORES
  ══════════════════════════════════════════════════ */
  function cargarInstructoresTodos() {
    postJSON('instructorControlador.php', { listarInstructor:'ok' }).then(resp => {
      if (resp.codigo === '200') { todosLosInstructores = resp.listarInstructor || []; renderInstructoresLista(todosLosInstructores); }
    });
  }

  function fetchInstructoresPorArea(idArea, areaNombre) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;
    sel.innerHTML = '<option value="">⏳ Buscando...</option>'; sel.disabled = true;
    postJSON('instructorControlador.php', { listarInstructoresPorArea:'ok', idArea }).then(resp => {
      sel.disabled = false;
      if (resp.codigo !== '200') { renderInstructoresLista(todosLosInstructores); mostrarHint(`Sin filtro para "${areaNombre}"`, 'warn'); return; }
      sel.innerHTML = '<option value="">— Seleccione instructor —</option>';
      const addGroup = (label, items) => {
        if (!items.length) return;
        const grp = document.createElement('optgroup'); grp.label = label;
        items.forEach(i => { const o = document.createElement('option'); o.value = i.idFuncionario; o.textContent = `${i.nombre} [${i.nombreArea||areaNombre}]`; grp.appendChild(o); });
        sel.appendChild(grp);
      };
      addGroup(`📍 ${areaNombre} (${resp.totalDelArea||0})`, resp.instructoresDelArea||[]);
      addGroup('── Otros instructores ──', resp.instructoresResto||[]);
      const t = resp.totalDelArea||0;
      mostrarHint(t > 0 ? `${t} instructor${t>1?'es':''} del área <strong>"${areaNombre}"</strong> primero` : `Sin instructores en <strong>"${areaNombre}"</strong>`, t > 0 ? 'ok' : 'warn');
    }).catch(() => { sel.disabled = false; renderInstructoresLista(todosLosInstructores); });
  }

  const renderInstructoresLista = ins =>
    poblarSelect('selectInstructorHorario', ins, '— Seleccione instructor —',
      i => ({ value: i.idFuncionario, text: `${i.nombre}${i.nombreArea ? ' ['+i.nombreArea+']' : ''}` }));

  const mostrarHint = (html, tipo) => {
    const el = document.getElementById('instructorAreaHint');
    if (el) { el.innerHTML = html; el.className = `ph-instructor-hint${tipo==='warn'?' ph-hint-warn':''}`; el.style.display = 'flex'; }
  };
  const ocultarHint = () => { const el = document.getElementById('instructorAreaHint'); if (el) el.style.display = 'none'; };

  /* ══════════════════════════════════════════════════
     SEDES / AMBIENTES / FICHAS / DÍAS
  ══════════════════════════════════════════════════ */
  function cargarSedes() {
    postJSON('sedeControlador.php', { listarSede:'ok' }).then(resp => {
      if (resp.codigo === '200')
        poblarSelect('selectSedeHorario', resp.listarSedes||[], '— Seleccione sede —', s => ({ value:s.idSede, text:s.nombre }));
    });
  }

  function cargarTodasLasFichas() {
    postJSON('fichaControlador.php', { listarFicha:'ok' }).then(resp => {
      if (resp.codigo === '200') todasLasFichas = resp.listarFicha || [];
    });
  }

  function filtrarFichasPorSedeYJornada(idSede, jornada) {
    const sel = document.getElementById('selectFichaHorario'); if (!sel) return;
    let fichas = todasLasFichas.filter(f => String(f.idSede) === String(idSede));
    if (jornada) fichas = fichas.filter(f => f.jornada?.toUpperCase() === jornada.toUpperCase());
    if (!fichas.length) { resetSelect('selectFichaHorario', `— Sin fichas${jornada?'/jornada':''} —`); return; }
    sel.innerHTML = '<option value="">— Seleccione ficha —</option>';
    fichas.forEach(f => {
      const o = document.createElement('option');
      o.value = f.idFicha;
      o.textContent = `${f.codigoFicha} — ${f.programa||f.programaNombre||''}`;
      o.dataset.tipoprograma = f.tipoPrograma||f.tipoprograma||f.tipoFormacion||'';
      o.dataset.jornada = f.jornada || '';
      sel.appendChild(o);
    });
    sel.disabled = false;
  }

  function cargarAmbientesPorSede(idSede) {
    const sel = document.getElementById('selectAmbienteHorario'); if (!sel) return;
    sel.innerHTML = '<option value="">⏳ Cargando ambientes...</option>'; sel.disabled = true;
    postJSON('ambienteControlador.php', { listarAmbientesPorSede:'ok', idSede }).then(resp => {
      if (resp.codigo !== '200') { sel.innerHTML = '<option value="">— Sin ambientes —</option>'; return; }
      sel.innerHTML = '<option value="">— Seleccione ambiente —</option>';
      (resp.ambientes||[]).forEach(amb => {
        const o = document.createElement('option');
        o.value = amb.idAmbiente;
        o.textContent = `${amb.codigo} — No. ${amb.numero}${amb.nombreArea?' | '+amb.nombreArea:''}`;
        o.dataset.area = amb.nombreArea||''; o.dataset.idarea = amb.idArea||'';
        sel.appendChild(o);
      });
      sel.disabled = false; ocultarHint();
    });
  }

  function cargarDiasDB() {
    postJSON('horarioControlador.php', { listarDias:'ok' }).then(resp => {
      if (resp.codigo !== '200') return;
      (resp.dias||[]).forEach(d => { if (DIAS_MAP[d.diasSemanales] !== undefined) COL_DIA[DIAS_MAP[d.diasSemanales]] = parseInt(d.idDia); });
    });
  }

  /* ══════════════════════════════════════════════════
     PREVIEW CALENDARIO FORM
  ══════════════════════════════════════════════════ */
  function actualizarPreview() {
    const g = id => document.getElementById(id);
    const horaInicio = g('horaInicioHorario')?.value || '';
    const horaFin    = g('horaFinHorario')?.value    || '';
    const fichaText  = g('selectFichaHorario')?.options[g('selectFichaHorario')?.selectedIndex]?.text || '—';
    const instrText  = g('selectInstructorHorario')?.options[g('selectInstructorHorario')?.selectedIndex]?.text || '—';
    if (g('previewHora') && horaInicio) g('previewHora').textContent = `${horaInicio} - ${horaFin}`;
    if (g('previewFicha'))       g('previewFicha').textContent       = fichaText !== '— Seleccione ficha —'       ? fichaText  : '—';
    if (g('previewInstructor'))  g('previewInstructor').textContent  = instrText !== '— Seleccione instructor —'  ? instrText  : '—';
    actualizarPreviewCalendario();
  }

  function actualizarPreviewCalendario() {
    const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
    const fichaTxt   = document.getElementById('selectFichaHorario')?.options[document.getElementById('selectFichaHorario')?.selectedIndex]?.text || '';
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
    if (!horaInicio) return;
    document.querySelectorAll('.dia-header.dia-activo').forEach(th => {
      const celda = document.querySelector(`.cal-cell-inner[data-dia="${th.dataset.dia}"]`);
      if (celda) celda.innerHTML = `<div class="horario-cal-card"><div class="hc-hora">${horaInicio} – ${horaFin}</div><div class="hc-ficha">${fichaTxt.substring(0,25)||'—'}</div></div>`;
    });
  }

  /* ══════════════════════════════════════════════════
     HELPERS
  ══════════════════════════════════════════════════ */
  function postJSON(controlador, data) {
    const fd = new FormData();
    Object.entries(data).forEach(([k,v]) => fd.append(k, v));
    return fetch(`controlador/${controlador}`, { method:'POST', body:fd }).then(r => r.json()).catch(console.error);
  }

  function poblarSelect(id, items, placeholder, mapper) {
    const sel = document.getElementById(id); if (!sel) return;
    sel.disabled = false;
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
      const { value, text } = mapper(item);
      const o = document.createElement('option'); o.value = value; o.textContent = text; sel.appendChild(o);
    });
  }

  function resetSelect(id, placeholder) {
    const sel = document.getElementById(id); if (!sel) return;
    sel.innerHTML = `<option value="">${placeholder}</option>`; sel.disabled = true;
  }

  function getDiasSeleccionados() {
    return [...document.querySelectorAll('.dia-header.dia-activo')]
      .map(th => COL_DIA[parseInt(th.dataset.dia)]).filter(Boolean);
  }

  function resetFormCrear() {
    document.getElementById('formCrearHorario')?.reset();
    document.querySelectorAll('.dia-header').forEach(th => th.classList.remove('dia-activo'));
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
    resetSelect('selectAmbienteHorario', '— Seleccione sede primero —');
    resetSelect('selectFichaHorario',    '— Seleccione sede primero —');
    const it = document.getElementById('inputTipoPrograma'); if (it) it.value = '';
    const b  = document.getElementById('inputBuscarInstructor'); if (b) b.value = '';
    renderInstructoresLista(todosLosInstructores);
    ocultarHint();
  }

});

function toggleDia(el) {
  el.classList.toggle('dia-activo');

  const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
  const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
  const fichaEl    = document.getElementById('selectFichaHorario');
  const fichaTxt   = fichaEl?.options[fichaEl?.selectedIndex]?.text || '';

  document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
  if (!horaInicio) return;

  document.querySelectorAll('.dia-header.dia-activo').forEach(th => {
    const celda = document.querySelector(`.cal-cell-inner[data-dia="${th.dataset.dia}"]`);
    if (celda) celda.innerHTML = `<div class="horario-cal-card">
      <div class="hc-hora">${horaInicio} – ${horaFin}</div>
      <div class="hc-ficha">${fichaTxt.substring(0,25)||'—'}</div>
    </div>`;
  });
}