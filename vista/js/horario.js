document.addEventListener('DOMContentLoaded', function () {

  /* ── ESTADO GLOBAL ── */
  let diasDB               = [];
  let todasLasFichas       = [];
  let todosLosInstructores = [];
  let calendarInstance     = null;

  const DIAS_MAP  = { 'Lunes':1,'Martes':2,'Miércoles':3,'Miercoles':3,'Jueves':4,'Viernes':5,'Sábado':6,'Sabado':6,'Domingo':7 };
  const COL_DIA   = { 1:null,2:null,3:null,4:null,5:null,6:null };
  const DIA_TO_DOW= { 'Domingo':0,'Lunes':1,'Martes':2,'Miércoles':3,'Miercoles':3,'Jueves':4,'Viernes':5,'Sábado':6,'Sabado':6 };
  const COLORES   = ['#7c6bff','#f59e0b','#10b981','#ef4444','#3b82f6','#8b5cf6','#ec4899','#14b8a6','#f97316'];

  /* ── INIT ── */
  listarHorarios();
  cargarDiasDB();
  cargarInstructoresTodos();
  cargarSedes();
  cargarTodasLasFichas();

  /* ══════════════════════════════════════════════════
     PANELES
  ══════════════════════════════════════════════════ */
  const PANELS = ['panelTablaHorario', 'panelFormularioHorario'];
  const mostrarPanel = id => PANELS.forEach(p => {
    const el = document.getElementById(p);
    if (el) el.style.display = (p === id) ? 'block' : 'none';
  });

  document.getElementById('btnNuevoHorario')?.addEventListener('click', () => {
    resetFormCrear(); mostrarPanel('panelFormularioHorario');
  });
  ['btnRegresarTablaHorario', 'btnCancelarHorario'].forEach(id =>
    document.getElementById(id)?.addEventListener('click', () => mostrarPanel('panelTablaHorario'))
  );

  /* ══════════════════════════════════════════════════
     EVENT LISTENERS — FORMULARIO CREAR
  ══════════════════════════════════════════════════ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    const idSede = this.value;
    resetSelect(document.getElementById('selectAmbienteHorario'), '— Seleccione ambiente —');
    resetSelect(document.getElementById('selectFichaHorario'), '— Seleccione sede primero —');
    renderInstructoresLista(todosLosInstructores);
    ocultarHint();
    if (!idSede) return;
    cargarAmbientesPorSede(idSede, 'selectAmbienteHorario');
    filtrarFichasPorSedeYJornada(idSede, document.getElementById('selectJornadaHorario')?.value || '');
  });

  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    actualizarPreview();
    const idArea = opt?.dataset.idarea;
    if (idArea) fetchInstructoresPorArea(idArea, opt.dataset.area || '');
    else { renderInstructoresLista(todosLosInstructores); ocultarHint(); }
  });

  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    const idSede = document.getElementById('selectSedeHorario')?.value || '';
    if (!idSede) resetSelect(document.getElementById('selectFichaHorario'), '— Seleccione sede primero —');
    else filtrarFichasPorSedeYJornada(idSede, this.value);
    actualizarPreview();
  });

  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const tv = this.options[this.selectedIndex]?.dataset.tipoprograma || '';
    const input = document.getElementById('inputTipoPrograma');
    if (input) { input.value = tv; input.classList.add('input-filled'); setTimeout(() => input.classList.remove('input-filled'), 800); }
    actualizarPreview();
  });

  ['horaInicioHorario','horaFinHorario','fechaInicioHorario','fechaFinHorario','selectInstructorHorario','selectSedeHorario']
    .forEach(id => document.getElementById(id)?.addEventListener('change', actualizarPreview));

  document.getElementById('inputBuscarInstructor')?.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    if (!q) {
      const opt = document.getElementById('selectAmbienteHorario')?.options[document.getElementById('selectAmbienteHorario')?.selectedIndex];
      const idArea = opt?.dataset.idarea;
      if (idArea) fetchInstructoresPorArea(idArea, opt.dataset.area || '');
      else { renderInstructoresLista(todosLosInstructores); ocultarHint(); }
      return;
    }
    renderInstructoresBusqueda(todosLosInstructores.filter(i =>
      (i.nombre||'').toLowerCase().includes(q) || (i.nombreArea||'').toLowerCase().includes(q)
    ));
  });

  document.querySelectorAll('.dia-header').forEach(th =>
    th.addEventListener('click', function () { this.classList.toggle('dia-activo'); actualizarPreviewCalendario(); })
  );

  document.getElementById('formCrearHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const dias = getDiasSeleccionadosCalendario();
    if (!dias.length) {
      Swal.fire({ icon:'warning', title:'Días requeridos', text:'Haz clic en al menos un día.', confirmButtonColor:'#7c6bff' });
      return;
    }
    crearHorario(dias);
  });

  /* ══════════════════════════════════════════════════
     DELEGACIÓN DE EVENTOS — TABLA
  ══════════════════════════════════════════════════ */

  // Botón VER — abre FullCalendar con todos los horarios de la ficha
  $(document).on('click', '.btnVerHorario', function (e) {
    e.preventDefault();
    abrirModalCalendarioPorFicha($(this).data('id-ficha'), {
      ficha:   $(this).data('ficha')   || '—',
      sede:    $(this).data('sede')    || '—',
      area:    $(this).data('area')    || '—',
      jornada: $(this).data('jornada') || '—',
      tipo:    $(this).data('tipo')    || '—',
    });
  });

  // Botón ELIMINAR — abre modal listado de horarios de esa ficha
  $(document).on('click', '.btnEliminarHorariosFicha', function (e) {
    e.preventDefault();
    abrirModalEliminarHorarios($(this).data('id-ficha'), $(this).data('ficha') || '—');
  });

  /* ══════════════════════════════════════════════════
     TABLA: LISTAR FICHAS CON HORARIO
  ══════════════════════════════════════════════════ */
  function listarHorarios() {
    postJSON('horarioControlador.php', { listarFichasConHorario: 'ok' }).then(response => {
      if (!response || response.codigo !== '200') {
        const tbody = document.getElementById('tbodyHorarios');
        if (tbody) tbody.innerHTML = `<tr><td colspan="7">${emptyState()}</td></tr>`;
        return;
      }

      if ($.fn.DataTable.isDataTable('#tablaHorarios')) $('#tablaHorarios').DataTable().clear().destroy();

      const dataSet = (response.horarios || []).map(item => {

        // Badge jornada
        const jornadaBadge = (() => {
          const j = (item.jornada || '').toUpperCase();
          if (j === 'MAÑANA') return '<span class="badge-jornada badge-manana">🌅 Mañana</span>';
          if (j === 'TARDE')  return '<span class="badge-jornada badge-tarde">☀️ Tarde</span>';
          if (j === 'NOCHE')  return '<span class="badge-jornada badge-noche">🌙 Noche</span>';
          return `<span class="badge-jornada">${item.jornada || '—'}</span>`;
        })();

        // Badge cantidad de horarios
        const totalBadge = `<span class="badge bg-primary rounded-pill">${item.totalHorarios || 0} horario${item.totalHorarios == 1 ? '' : 's'}</span>`;

        // Botones: Ver + Eliminar (sin editar)
        const botones = `
          <div class="action-group">
            <button type="button" class="btn btn-ver btnVerHorario" title="Ver calendario"
              data-id-ficha="${item.idFicha       || ''}"
              data-ficha="${item.codigoFicha      || '—'}"
              data-sede="${item.sedeNombre        || '—'}"
              data-area="${item.areaNombre        || '—'}"
              data-jornada="${item.jornada        || '—'}"
              data-tipo="${item.tipoPrograma      || '—'}">
              <i class="bi bi-eye-fill"></i>
            </button>
            <button type="button" class="btn btn-danger btnEliminarHorariosFicha" title="Eliminar horario"
              data-id-ficha="${item.idFicha       || ''}"
              data-ficha="${item.codigoFicha      || '—'}">
              <i class="bi bi-trash"></i>
            </button>
          </div>`;

        return [
          item.sedeNombre     || '—',
          item.areaNombre     || '—',
          `<strong>${item.codigoFicha || '—'}</strong><br><small class="text-muted">${item.nombrePrograma || ''}</small>`,
          jornadaBadge,
          item.tipoPrograma   || '—',
          item.ambienteNombre || '—',
          totalBadge,
          botones
        ];
      });

      $('#tablaHorarios').DataTable({
        buttons: [{ extend: 'colvis', text: 'Columnas' }, 'excel', 'pdf', 'print'],
        dom: 'Bfrtip', responsive: true, destroy: true, data: dataSet,
        language: {
          emptyTable: '— Sin fichas con horarios registrados —',
          search: 'Buscar:',
          paginate: { next: 'Sig.', previous: 'Ant.' }
        }
      });
    });
  }

  /* ══════════════════════════════════════════════════
     MODAL VER — FULLCALENDAR
  ══════════════════════════════════════════════════ */
  function abrirModalCalendarioPorFicha(idFicha, info) {
    const setTxt = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    setTxt('calModal_ficha',   info.ficha);
    setTxt('calModal_sede',    info.sede);
    setTxt('calModal_area',    info.area);
    setTxt('calModal_jornada', info.jornada);
    setTxt('calModal_tipo',    info.tipo);
    setTxt('calModal_instructor', '— Múltiples —');
    setTxt('calModal_hora',   '— Ver calendario —');
    setTxt('calModal_fechas', '— Ver calendario —');

    const calEl = document.getElementById('horarioCalendar');
    if (calEl) calEl.innerHTML = '<div class="cal-loading"><i class="bi bi-hourglass-split"></i> Cargando horarios...</div>';

    const modalEl = document.getElementById('modalVerHorario');
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
    modalEl.addEventListener('shown.bs.modal', function handler() {
      modalEl.removeEventListener('shown.bs.modal', handler);
      cargarHorariosPorFicha(idFicha);
    });
  }

  function cargarHorariosPorFicha(idFicha) {
    const calEl = document.getElementById('horarioCalendar');
    postJSON('horarioControlador.php', { listarHorariosPorFicha: 'ok', idFicha })
      .then(resp => {
        if (resp.codigo !== '200' || !resp.horarios?.length) {
          if (calEl) calEl.innerHTML = '<div class="cal-empty"><i class="bi bi-calendar-x"></i><p>Sin horarios registrados para esta ficha</p></div>';
          return;
        }
        inicializarCalendarioConHorarios(resp.horarios);
      })
      .catch(() => {
        if (calEl) calEl.innerHTML = '<div class="cal-empty"><i class="bi bi-exclamation-circle"></i><p>Error al cargar horarios</p></div>';
      });
  }

  function inicializarCalendarioConHorarios(horarios) {
    const calEl = document.getElementById('horarioCalendar');
    if (!calEl) return;
    calEl.innerHTML = '';
    if (calendarInstance) { calendarInstance.destroy(); calendarInstance = null; }

    const mapaColores = {};
    let colorIdx = 0;
    const events = [];

    horarios.forEach(item => {
      const key = String(item.idFuncionario || item.instructorNombre || 'sin');
      if (!mapaColores[key]) mapaColores[key] = COLORES[colorIdx++ % COLORES.length];

      const daysOfWeek = [...new Set(
        (item.diasNombres || '').split(',').map(d => DIA_TO_DOW[d.trim()]).filter(d => d !== undefined)
      )];

      daysOfWeek.forEach(dow => {
        const ev = {
          title:      item.instructorNombre || 'Sin instructor',
          daysOfWeek: [dow],
          startTime:  item.hora_inicioClase || '08:00',
          endTime:    item.hora_finClase    || '10:00',
          color:      mapaColores[key],
          textColor:  '#fff',
          extendedProps: {
            instructor: item.instructorNombre || '—',
            area:       item.areaNombre       || '—',
            ambiente:   item.ambienteNombre   || '—',
            horarioId:  item.idHorario
          }
        };
        if (item.fecha_inicioHorario) ev.startRecur = item.fecha_inicioHorario;
        if (item.fecha_finHorario)    ev.endRecur   = item.fecha_finHorario;
        events.push(ev);
      });
    });

    renderLeyendaInstructores(mapaColores, horarios);

    calendarInstance = new FullCalendar.Calendar(calEl, {
      initialView: 'timeGridWeek', locale: 'es',
      headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
      buttonText: { today: 'Hoy' },
      height: 520, allDaySlot: false,
      slotMinTime: '05:00:00', slotMaxTime: '23:00:00',
      slotDuration: '00:30:00', slotLabelInterval: '01:00:00',
      expandRows: true, nowIndicator: true,
      events,
      eventContent: ({ event }) => ({
        html: `<div class="fc-ev-inner">
          <div class="fc-ev-hora"><i class="bi bi-clock-fill"></i> ${event.startStr?.slice(11,16)||''} – ${event.endStr?.slice(11,16)||''}</div>
          <div class="fc-ev-name">${event.title}</div></div>`
      }),
      eventDidMount: ({ event, el }) => {
        const p = event.extendedProps;
        el.title = `Instructor: ${p.instructor}\nÁrea: ${p.area}\nAmbiente: ${p.ambiente}`;
      }
    });
    calendarInstance.render();
  }

  function renderLeyendaInstructores(mapaColores, horarios) {
    const el = document.getElementById('calLeyendaInstructores');
    if (!el) return;
    const names = Object.fromEntries(horarios.map(h => [
      String(h.idFuncionario || h.instructorNombre || 'sin'),
      h.instructorNombre || 'Sin instructor'
    ]));
    el.innerHTML = '<div class="cal-leyenda">' +
      Object.entries(mapaColores).map(([key, color]) =>
        `<span class="cal-leyenda-item">
          <span class="cal-leyenda-dot" style="background:${color}"></span>
          <span class="cal-leyenda-nombre">${names[key] || key}</span>
        </span>`
      ).join('') + '</div>';
  }

  /* ══════════════════════════════════════════════════
     MODAL ELIMINAR — Lista horarios de la ficha
  ══════════════════════════════════════════════════ */
  function abrirModalEliminarHorarios(idFicha, codigoFicha) {
    // Crear el modal si no existe
    if (!document.getElementById('modalEliminarHorarios')) {
      const div = document.createElement('div');
      div.innerHTML = `
        <div class="modal fade" id="modalEliminarHorarios" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                  <i class="bi bi-trash me-2"></i>Eliminar horario — Ficha <span id="elimModal_ficha"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body" id="elimModal_body">
                <div class="text-center py-3"><i class="bi bi-hourglass-split"></i> Cargando...</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>`;
      document.body.appendChild(div);
    }

    document.getElementById('elimModal_ficha').textContent = codigoFicha;
    document.getElementById('elimModal_body').innerHTML =
      '<div class="text-center py-3"><i class="bi bi-hourglass-split"></i> Cargando horarios...</div>';

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEliminarHorarios')).show();

    postJSON('horarioControlador.php', { listarHorariosPorFicha: 'ok', idFicha })
      .then(resp => {
        const body = document.getElementById('elimModal_body');
        if (resp.codigo !== '200' || !resp.horarios?.length) {
          body.innerHTML = '<p class="text-muted text-center py-3">No hay horarios para esta ficha.</p>';
          return;
        }
        body.innerHTML = `
          <p class="text-muted mb-3" style="font-size:13px;">
            Selecciona el horario que deseas eliminar:
          </p>` +
          resp.horarios.map(h => `
            <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2">
              <div style="font-size:13px; line-height:1.5">
                <div><i class="bi bi-person-fill text-primary me-1"></i><strong>${h.instructorNombre || '—'}</strong></div>
                <div><i class="bi bi-clock me-1 text-secondary"></i>${h.hora_inicioClase || '?'} – ${h.hora_finClase || '?'}</div>
                <div><i class="bi bi-calendar-week me-1 text-secondary"></i>${h.diasNombres || '—'}</div>
                <div><i class="bi bi-door-open me-1 text-secondary"></i>${h.ambienteNombre || '—'}</div>
              </div>
              <button type="button"
                class="btn btn-danger btn-sm btnConfirmarEliminarHorario ms-3 flex-shrink-0"
                data-id="${h.idHorario}"
                data-id-ficha="${idFicha}"
                data-ficha="${codigoFicha}">
                <i class="bi bi-trash"></i>
              </button>
            </div>`).join('');
      })
      .catch(() => {
        document.getElementById('elimModal_body').innerHTML =
          '<p class="text-danger text-center py-3">Error al cargar los horarios.</p>';
      });
  }

  // Confirmar eliminación de un horario individual
  $(document).on('click', '.btnConfirmarEliminarHorario', function () {
    const idHorario   = $(this).data('id');
    const idFicha     = $(this).data('id-ficha');
    const codigoFicha = $(this).data('ficha');

    Swal.fire({
      title: '¿Eliminar este horario?', text: 'Esta acción no se puede deshacer.',
      icon: 'warning', showCancelButton: true,
      confirmButtonColor: '#ef4444', cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
    }).then(r => {
      if (!r.isConfirmed) return;
      postJSON('horarioControlador.php', { eliminarHorario: 'ok', idHorario }).then(resp => {
        if (resp.codigo === '200') {
          Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1400, showConfirmButton: false });
          listarHorarios(); // refrescar tabla principal
          // refrescar lista del modal o cerrarlo si ya no hay horarios
          setTimeout(() => {
            postJSON('horarioControlador.php', { listarHorariosPorFicha: 'ok', idFicha }).then(r2 => {
              if (!r2.horarios?.length) {
                bootstrap.Modal.getInstance(document.getElementById('modalEliminarHorarios'))?.hide();
              } else {
                abrirModalEliminarHorarios(idFicha, codigoFicha);
              }
            });
          }, 1500);
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: resp.mensaje });
        }
      });
    });
  });

  /* ══════════════════════════════════════════════════
     CREAR HORARIO
  ══════════════════════════════════════════════════ */
  function crearHorario(diasSeleccionados) {
    Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    const g  = id => document.getElementById(id)?.value;
    const fd = new FormData();
    Object.entries({
      crearHorario:        'ok',
      idFuncionario:       g('selectInstructorHorario'),
      idAmbiente:          g('selectAmbienteHorario'),
      idFicha:             g('selectFichaHorario'),
      hora_inicioClase:    g('horaInicioHorario'),
      hora_finClase:       g('horaFinHorario'),
      fecha_inicioHorario: g('fechaInicioHorario'),
      fecha_finHorario:    g('fechaFinHorario')
    }).forEach(([k, v]) => fd.append(k, v));
    diasSeleccionados.forEach(id => fd.append('dias[]', id));

    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({ icon: 'success', title: '¡Horario creado!', text: resp.mensaje, timer: 1800, showConfirmButton: false });
          mostrarPanel('panelTablaHorario');
          listarHorarios();
          resetFormCrear();
        } else {
          Swal.fire({ icon: 'error', title: 'Error', html: resp.mensaje, confirmButtonColor: '#7c6bff' });
        }
      })
      .catch(err => { Swal.close(); Swal.fire({ icon: 'error', title: 'Error de conexión', text: String(err) }); });
  }

  /* ══════════════════════════════════════════════════
     INSTRUCTORES
  ══════════════════════════════════════════════════ */
  function cargarInstructoresTodos() {
    postJSON('instructorControlador.php', { listarInstructor: 'ok' }).then(resp => {
      if (resp.codigo !== '200') return;
      todosLosInstructores = resp.listarInstructor || [];
      renderInstructoresLista(todosLosInstructores);
    });
  }

  function fetchInstructoresPorArea(idArea, areaNombre) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;
    sel.innerHTML = '<option value="">⏳ Buscando instructores del área...</option>';
    sel.disabled = true;
    postJSON('instructorControlador.php', { listarInstructoresPorArea: 'ok', idArea }).then(resp => {
      sel.disabled = false;
      if (resp.codigo !== '200') {
        renderInstructoresLista(todosLosInstructores);
        mostrarHint(`No se pudo filtrar el área "${areaNombre}"`, 'warn');
        return;
      }
      sel.innerHTML = '<option value="">— Seleccione instructor —</option>';
      const addGroup = (label, items) => {
        if (!items.length) return;
        const grp = document.createElement('optgroup');
        grp.label = label;
        items.forEach(i => {
          const o = document.createElement('option');
          o.value = i.idFuncionario;
          o.textContent = `${i.nombre}  [${i.nombreArea || areaNombre}]`;
          grp.appendChild(o);
        });
        sel.appendChild(grp);
      };
      addGroup(`📍 ${areaNombre}  (${resp.totalDelArea || 0})`, resp.instructoresDelArea || []);
      addGroup('── Otros instructores ──', resp.instructoresResto || []);
      const total = resp.totalDelArea || 0;
      mostrarHint(
        total > 0
          ? `${total} instructor${total > 1 ? 'es' : ''} del área <strong>"${areaNombre}"</strong> aparecen primero`
          : `Sin instructores en <strong>"${areaNombre}"</strong> — mostrando todos`,
        total > 0 ? 'ok' : 'warn'
      );
    }).catch(err => {
      console.error(err);
      sel.disabled = false;
      renderInstructoresLista(todosLosInstructores);
      mostrarHint('Error al consultar instructores del área', 'warn');
    });
  }

  const renderInstructoresLista    = ins => poblarSelect('selectInstructorHorario', ins, '— Seleccione instructor —', i => ({ value: i.idFuncionario, text: `${i.nombre}${i.nombreArea ? '  [' + i.nombreArea + ']' : ''}` }));
  const renderInstructoresBusqueda = ins => poblarSelect('selectInstructorHorario', ins, '— Seleccione instructor —', i => ({ value: i.idFuncionario, text: `${i.nombre}${i.nombreArea ? '  [' + i.nombreArea + ']' : ''}` }), ins.length === 0);
  const mostrarHint = (html, tipo) => { const el = document.getElementById('instructorAreaHint'); if (el) { el.innerHTML = html; el.className = `ph-instructor-hint${tipo === 'warn' ? ' ph-hint-warn' : ''}`; el.style.display = 'flex'; } };
  const ocultarHint = () => { const el = document.getElementById('instructorAreaHint'); if (el) el.style.display = 'none'; };

  /* ══════════════════════════════════════════════════
     SEDES / FICHAS / AMBIENTES
  ══════════════════════════════════════════════════ */
  function cargarSedes() {
    postJSON('sedeControlador.php', { listarSede: 'ok' }).then(resp => {
      if (resp.codigo !== '200') return;
      poblarSelect('selectSedeHorario', resp.listarSedes || [], '— Seleccione sede —', s => ({ value: s.idSede, text: s.nombre }));
    });
  }

  function cargarTodasLasFichas() {
    postJSON('fichaControlador.php', { listarFicha: 'ok' }).then(resp => {
      if (resp.codigo === '200') todasLasFichas = resp.listarFicha || [];
    });
  }

  function filtrarFichasPorSedeYJornada(idSede, jornada) {
    const sel = document.getElementById('selectFichaHorario');
    if (!sel) return;
    let fichas = todasLasFichas.filter(f => String(f.idSede) === String(idSede));
    if (jornada) fichas = fichas.filter(f => f.jornada?.toUpperCase() === jornada.toUpperCase());
    if (!fichas.length) {
      resetSelect(sel, `— Sin fichas para esta sede${jornada ? '/jornada' : ''} —`);
      const it = document.getElementById('inputTipoPrograma');
      if (it) it.value = '';
      return;
    }
    sel.innerHTML = '<option value="">— Seleccione ficha —</option>';
    fichas.forEach(f => {
      const o = document.createElement('option');
      o.value = f.idFicha;
      o.textContent = `${f.codigoFicha} — ${f.programa || f.programaNombre || ''}`;
      const tv = f.tipoPrograma || f.tipoprograma || f.tipoFormacion || f.tipoformacion || '';
      o.dataset.tipoprograma = tv;
      o.dataset.jornada = f.jornada || '';
      sel.appendChild(o);
    });
    sel.disabled = false;
  }

  function cargarAmbientesPorSede(idSede, selectId) {
    const sel = document.getElementById(selectId);
    if (!sel) return;
    sel.innerHTML = '<option value="">⏳ Cargando ambientes...</option>';
    sel.disabled = true;
    postJSON('ambienteControlador.php', { listarAmbientesPorSede: 'ok', idSede }).then(resp => {
      if (resp.codigo !== '200') { sel.innerHTML = '<option value="">— Sin ambientes —</option>'; return; }
      sel.innerHTML = '<option value="">— Seleccione ambiente —</option>';
      (resp.ambientes || []).forEach(amb => {
        const o = document.createElement('option');
        o.value = amb.idAmbiente;
        o.textContent = `${amb.codigo} — No. ${amb.numero}${amb.nombreArea ? ' | ' + amb.nombreArea : ''}`;
        o.dataset.area   = amb.nombreArea || '';
        o.dataset.idarea = amb.idArea     || '';
        sel.appendChild(o);
      });
      sel.disabled = false;
      ocultarHint();
    });
  }

  function cargarDiasDB() {
    postJSON('horarioControlador.php', { listarDias: 'ok' }).then(resp => {
      if (resp.codigo !== '200') return;
      diasDB = resp.dias || [];
      diasDB.forEach(d => {
        if (DIAS_MAP[d.diasSemanales] !== undefined) COL_DIA[DIAS_MAP[d.diasSemanales]] = parseInt(d.idDia);
      });
    });
  }

  /* ══════════════════════════════════════════════════
     PREVIEW CALENDARIO FORM
  ══════════════════════════════════════════════════ */
  function actualizarPreview() {
    const g = id => document.getElementById(id);
    const horaInicio  = g('horaInicioHorario')?.value || '';
    const horaFin     = g('horaFinHorario')?.value    || '';
    const fichaSelect = g('selectFichaHorario');
    const instrSelect = g('selectInstructorHorario');
    const fichaNombre = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '—';
    const instrNombre = instrSelect?.options[instrSelect?.selectedIndex]?.text || '—';
    const ph = g('previewHora'), pf = g('previewFicha'), pi = g('previewInstructor');
    if (ph && horaInicio) ph.textContent = `${horaInicio} - ${horaFin}`;
    if (pf) pf.textContent = (fichaNombre && fichaNombre !== '— Seleccione ficha —') ? fichaNombre : '—';
    if (pi) pi.textContent = (instrNombre && instrNombre !== '— Seleccione instructor —') ? instrNombre : '—';
    actualizarPreviewCalendario();
  }

  function actualizarPreviewCalendario() {
    const horaInicio  = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin     = document.getElementById('horaFinHorario')?.value    || '';
    const fichaSelect = document.getElementById('selectFichaHorario');
    const fichaTxt    = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '';
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
    if (!horaInicio) return;
    document.querySelectorAll('.dia-header.dia-activo').forEach(th => {
      const celda = document.querySelector(`.cal-cell-inner[data-dia="${th.dataset.dia}"]`);
      if (celda) celda.innerHTML = `<div class="horario-cal-card"><div class="hc-hora">${horaInicio} – ${horaFin}</div><div class="hc-ficha">${fichaTxt.substring(0, 25) || '—'}</div></div>`;
    });
  }

  /* ══════════════════════════════════════════════════
     HELPERS
  ══════════════════════════════════════════════════ */
  function postJSON(controlador, data) {
    const fd = new FormData();
    Object.entries(data).forEach(([k, v]) => fd.append(k, v));
    return fetch(`controlador/${controlador}`, { method: 'POST', body: fd }).then(r => r.json()).catch(console.error);
  }

  function poblarSelect(id, items, placeholder, mapper, showEmpty = false) {
    const sel = document.getElementById(id);
    if (!sel) return;
    sel.disabled = false;
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    if (showEmpty) {
      const o = document.createElement('option'); o.disabled = true; o.textContent = '— Sin resultados —'; sel.appendChild(o); return;
    }
    items.forEach(item => {
      const { value, text } = mapper(item);
      const o = document.createElement('option'); o.value = value; o.textContent = text; sel.appendChild(o);
    });
  }

  const getDiasSeleccionadosCalendario = () =>
    [...document.querySelectorAll('.dia-header.dia-activo')].map(th => COL_DIA[parseInt(th.dataset.dia)]).filter(Boolean);

  const emptyState  = () => `<div class="horario-empty"><i class="bi bi-calendar-x"></i><p>No hay horarios registrados</p></div>`;
  const resetSelect = (sel, placeholder) => { if (!sel) return; sel.innerHTML = `<option value="">${placeholder}</option>`; sel.disabled = true; };

  function resetFormCrear() {
    document.getElementById('formCrearHorario')?.reset();
    document.querySelectorAll('.dia-header').forEach(th => th.classList.remove('dia-activo'));
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
    resetSelect(document.getElementById('selectAmbienteHorario'), '— Seleccione sede primero —');
    resetSelect(document.getElementById('selectFichaHorario'),    '— Seleccione sede primero —');
    const it = document.getElementById('inputTipoPrograma'); if (it) it.value = '';
    const b  = document.getElementById('inputBuscarInstructor'); if (b) b.value = '';
    renderInstructoresLista(todosLosInstructores);
    ocultarHint();
  }

});