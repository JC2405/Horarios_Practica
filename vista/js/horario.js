/**
 * horario.js
 * Sigue el patrón de ficha.js, sede.js, instructor.js
 * del proyecto sena_formacion.
 *
 * Flujo principal:
 * 1. Listar horarios en tabla
 * 2. Crear horario → selector-bar + calendario visual
 * 3. Editar / Eliminar
 */

document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     ESTADO LOCAL
  ============================================================ */
  let horarioDataTable = null;
  let diasDB = [];          // días de la BD [{idDia, diasSemanales}]

  // Mapa nombre→idDia (se llena cuando llegan los días de la BD)
  const DIAS_MAP = {
    'Lunes': 1, 'Martes': 2, 'Miércoles': 3, 'Miercoles': 3,
    'Jueves': 4, 'Viernes': 5, 'Sábado': 6, 'Sabado': 6, 'Domingo': 7
  };

  // Nombre de columna calendario → id del día BD
  const COL_DIA = { 1: null, 2: null, 3: null, 4: null, 5: null, 6: null };

  /* ============================================================
     INICIALIZACIÓN
  ============================================================ */
  listarHorarios();
  cargarDiasDB();
  cargarInstructores();
  cargarSedes();
  cargarFichas();

  /* ============================================================
     NAVEGACIÓN PANELES
  ============================================================ */
  function mostrarPanel(id) {
    ['panelTablaHorario', 'panelFormularioHorario', 'panelEditarHorario']
      .forEach(p => {
        const el = document.getElementById(p);
        if (el) el.style.display = p === id ? 'block' : 'none';
      });
  }

  // Botón Nuevo
  document.getElementById('btnNuevoHorario')?.addEventListener('click', () => {
    resetFormCrear();
    mostrarPanel('panelFormularioHorario');
  });

  // Regresar desde Crear
  document.getElementById('btnRegresarTablaHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));

  // Regresar desde Editar
  document.getElementById('btnRegresarTablaHorarioEdit')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarEditarHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));

  /* ============================================================
     CASCADA SEDE → AMBIENTES (formulario crear)
  ============================================================ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    const idSede = this.value;
    const selAmb = document.getElementById('selectAmbienteHorario');
    const selArea = document.getElementById('selectAreaAmbiente');

    resetSelect(selAmb, '—');
    resetSelect(selArea, '—');
    selArea.disabled = true;

    if (!idSede) return;

    cargarAmbientesPorSede(idSede, 'selectAmbienteHorario');
  });

  // Cuando cambia el ambiente → actualizar campo área (read-only visual)
  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const area = opt?.dataset.area || '—';
    const selArea = document.getElementById('selectAreaAmbiente');
    selArea.innerHTML = `<option value="${opt?.dataset.idarea || ''}">${area}</option>`;
    actualizarPreview();
  });

  /* ============================================================
     CASCADA JORNADA → FICHAS (filtrar fichas por jornada)
  ============================================================ */
  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    filtrarFichasPorJornada(this.value);
    actualizarPreview();
  });

  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('inputTipoPrograma').value = opt?.dataset.tipoprograma || '';
    actualizarPreview();
  });

  // Otros campos que afectan el preview
  ['horaInicioHorario', 'horaFinHorario', 'fechaInicioHorario', 'fechaFinHorario',
   'selectInstructorHorario', 'selectSedeHorario'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', actualizarPreview);
  });

  /* ============================================================
     CALENDARIO — clic en cabecera del día (toggle)
  ============================================================ -->*/
  document.querySelectorAll('.dia-header').forEach(th => {
    th.addEventListener('click', function () {
      const dia = parseInt(this.dataset.dia);
      this.classList.toggle('dia-activo');

      // Marcar / desmarcar celda
      const celda = document.querySelector(`.cal-preview-row .cal-cell[data-dia="${dia}"]`);
      if (celda) celda.classList.toggle('dia-seleccionado', this.classList.contains('dia-activo'));

      actualizarPreviewCalendario();
    });
  });

  /* ============================================================
     SUBMIT CREAR HORARIO
  ============================================================ */
  document.getElementById('formCrearHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const diasSeleccionados = getDiasSeleccionadosCalendario();
    if (diasSeleccionados.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'Días requeridos',
        text: 'Haz clic en al menos un día del calendario.',
        confirmButtonColor: '#7c6bff'
      });
      return;
    }

    crearHorario(diasSeleccionados);
  });

  /* ============================================================
     SUBMIT EDITAR HORARIO
  ============================================================ */
  document.getElementById('formEditarHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const dias = getDiasEditSeleccionados();
    editarHorario(dias);
  });

  /* ============================================================
     CLICK EDITAR (delegado desde tabla)
  ============================================================ */
  $(document).on('click', '.btnEditarHorario', function (e) {
    e.preventDefault();
    e.stopPropagation();

    document.getElementById('idHorarioEdit').value  = $(this).data('id');
    document.getElementById('horaInicioEdit').value  = $(this).data('hora-inicio') || '';
    document.getElementById('horaFinEdit').value     = $(this).data('hora-fin')    || '';
    document.getElementById('fechaInicioEdit').value = $(this).data('fecha-inicio')|| '';
    document.getElementById('fechaFinEdit').value    = $(this).data('fecha-fin')   || '';

    const idSede = $(this).data('id-sede') || '';
    const idAmb  = $(this).data('id-ambiente') || '';
    if (idSede) cargarAmbientesPorSedeEdit(idSede, idAmb);

    // Marcar días en el edit
    const diasStr = String($(this).data('dias') || '');
    const diasArr = diasStr ? diasStr.split(',').map(d => d.trim()) : [];
    marcarDiasEdit(diasArr);

    mostrarPanel('panelEditarHorario');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* ============================================================
     CLICK ELIMINAR (delegado desde tabla)
  ============================================================ */
  $(document).on('click', '.btnEliminarHorario', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const idHorario = $(this).data('id');
    Swal.fire({
      title: '¿Eliminar horario?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (!result.isConfirmed) return;
      const fd = new FormData();
      fd.append('eliminarHorario', 'ok');
      fd.append('idHorario', idHorario);

      fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(resp => {
          if (resp.codigo === '200') {
            Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1500, showConfirmButton: false });
            listarHorarios();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: resp.mensaje });
          }
        });
    });
  });

  /* ============================================================
     FUNCIONES CRUD
  ============================================================ */

  /** Listar horarios y construir DataTable */
  function listarHorarios() {
    const fd = new FormData();
    fd.append('listarHorarios', 'ok');

    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .catch(err => console.error('listarHorarios:', err))
      .then(response => {
        if (!response || response.codigo !== '200') {
          document.getElementById('tbodyHorarios').innerHTML =
            `<tr><td colspan="13">${emptyState()}</td></tr>`;
          return;
        }

        if ($.fn.DataTable.isDataTable('#tablaHorarios')) {
          $('#tablaHorarios').DataTable().clear().destroy();
        }

        const horarios = response.horarios || [];
        const dataSet  = [];

        horarios.forEach(item => {
          const nombre   = item.instructorNombre || '—';
          const iniciales = nombre !== '—'
            ? nombre.trim().split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
            : '?';

          const instructorHtml = `
            <div class="instructor-cell">
              <div class="instructor-avatar">${iniciales}</div>
              <span style="font-size:12px;font-weight:600;">${nombre}</span>
            </div>`;

          const jornadaBadge = inferirJornadaBadge(item.hora_inicioClase);
          const diasNombres  = item.diasNombres ? item.diasNombres.split(',') : [];
          const diasHtml     = diasNombres.map(d =>
            `<span class="dia-chip">${abreviarDia(d.trim())}</span>`).join('');

          const botones = `
            <div class="action-group">
              <button type="button" class="btn btn-info btnEditarHorario"
                data-id="${item.idHorario}"
                data-hora-inicio="${item.hora_inicioClase || ''}"
                data-hora-fin="${item.hora_finClase || ''}"
                data-fecha-inicio="${item.fecha_inicioHorario || ''}"
                data-fecha-fin="${item.fecha_finHorario || ''}"
                data-id-ambiente="${item.idAmbiente || ''}"
                data-id-sede=""
                data-dias="${item.dias || ''}">
                <i class="bi bi-pen"></i>
              </button>
              <button type="button" class="btn btn-danger btnEliminarHorario" data-id="${item.idHorario}">
                <i class="bi bi-trash"></i>
              </button>
            </div>`;

          dataSet.push([
            item.ambienteDescripcion || '—',
            `<strong>${item.ambienteNumero || '—'}</strong>`,
            '—',
            jornadaBadge,
            item.codigoFicha || '—',
            '—',
            instructorHtml,
            `<span class="hora-display">${item.hora_inicioClase || '—'}</span>`,
            `<span class="hora-display">${item.hora_finClase || '—'}</span>`,
            formatFecha(item.fecha_inicioHorario),
            formatFecha(item.fecha_finHorario),
            `<div class="dias-badge">${diasHtml || '—'}</div>`,
            botones
          ]);
        });

        horarioDataTable = $('#tablaHorarios').DataTable({
          buttons: [
            { extend: 'colvis', text: 'Columnas' },
            'excel', 'pdf', 'print'
          ],
          dom: 'Bfrtip',
          responsive: true,
          destroy: true,
          data: dataSet,
          language: {
            emptyTable: '— Sin horarios registrados —',
            search: 'Buscar:',
            paginate: { next: 'Sig.', previous: 'Ant.' }
          }
        });
      });
  }

  /** Crear horario */
  function crearHorario(diasSeleccionados) {
    Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const fd = new FormData();
    fd.append('crearHorario',        'ok');
    fd.append('idFuncionario',       document.getElementById('selectInstructorHorario').value);
    fd.append('idAmbiente',          document.getElementById('selectAmbienteHorario').value);
    fd.append('idFicha',             document.getElementById('selectFichaHorario').value);
    fd.append('hora_inicioClase',    document.getElementById('horaInicioHorario').value);
    fd.append('hora_finClase',       document.getElementById('horaFinHorario').value);
    fd.append('fecha_inicioHorario', document.getElementById('fechaInicioHorario').value);
    fd.append('fecha_finHorario',    document.getElementById('fechaFinHorario').value);

    // días como array numérico (IDs reales de la BD)
    diasSeleccionados.forEach(idDia => fd.append('dias[]', idDia));

    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({
            icon: 'success', title: '¡Horario creado!',
            text: resp.mensaje, timer: 1800, showConfirmButton: false
          });
          mostrarPanel('panelTablaHorario');
          listarHorarios();
          resetFormCrear();
        } else {
          Swal.fire({ icon: 'error', title: 'Error', html: resp.mensaje, confirmButtonColor: '#7c6bff' });
        }
      })
      .catch(err => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error de conexión', text: String(err) });
      });
  }

  /** Editar horario */
  function editarHorario(diasSeleccionados) {
    Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    const fd = new FormData();
    fd.append('actualizarHorario',   'ok');
    fd.append('idHorario',           document.getElementById('idHorarioEdit').value);
    fd.append('idAmbiente',          document.getElementById('selectAmbienteEdit').value);
    fd.append('hora_inicioClase',    document.getElementById('horaInicioEdit').value);
    fd.append('hora_finClase',       document.getElementById('horaFinEdit').value);
    fd.append('fecha_inicioHorario', document.getElementById('fechaInicioEdit').value);
    fd.append('fecha_finHorario',    document.getElementById('fechaFinEdit').value);
    diasSeleccionados.forEach(idDia => fd.append('dias[]', idDia));

    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({ icon: 'success', title: 'Actualizado', timer: 1600, showConfirmButton: false });
          mostrarPanel('panelTablaHorario');
          listarHorarios();
        } else {
          Swal.fire({ icon: 'error', title: 'Error', html: resp.mensaje, confirmButtonColor: '#7c6bff' });
        }
      })
      .catch(err => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error de conexión', text: String(err) });
      });
  }

  /* ============================================================
     CARGAR DATOS PARA SELECTS
  ============================================================ */

  function cargarDiasDB() {
    const fd = new FormData();
    fd.append('listarDias', 'ok');
    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo === '200') {
          diasDB = resp.dias || [];
          // Mapear nombre a ID para el calendario
          diasDB.forEach(d => {
            const nombre = d.diasSemanales;
            if (DIAS_MAP[nombre] !== undefined) {
              COL_DIA[DIAS_MAP[nombre]] = parseInt(d.idDia);
            }
          });
        }
      });
  }

  function cargarInstructores() {
    const fd = new FormData();
    fd.append('listarInstructor', 'ok');
    fetch('controlador/instructorControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        const sel = document.getElementById('selectInstructorHorario');
        if (!sel) return;
        sel.innerHTML = '<option value="">Nombre / Área</option>';
        resp.listarInstructor.forEach(i => {
          const opt = document.createElement('option');
          opt.value = i.idFuncionario;
          opt.textContent = `${i.nombre}${i.nombreArea ? ' — ' + i.nombreArea : ''}`;
          opt.dataset.area = i.nombreArea || '';
          sel.appendChild(opt);
        });
      });
  }

  function cargarSedes() {
    const fd = new FormData();
    fd.append('listarSede', 'ok');
    fetch('controlador/sedeControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        const sel = document.getElementById('selectSedeHorario');
        if (!sel) return;
        sel.innerHTML = '<option value="">Nombre / Ciudad</option>';
        (resp.listarSedes || []).forEach(s => {
          sel.innerHTML += `<option value="${s.idSede}">${s.nombre} — ${s.nombreMunicipio || ''}</option>`;
        });
      });
  }

  function cargarAmbientesPorSede(idSede, selectId) {
    const sel = document.getElementById(selectId);
    if (!sel) return;
    sel.innerHTML = '<option value="">Cargando...</option>';
    sel.disabled = true;

    const fd = new FormData();
    fd.append('listarAmbientesPorSede', 'ok');
    fd.append('idSede', idSede);

    fetch('controlador/ambienteControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        sel.innerHTML = '<option value="">—</option>';
        if (resp.codigo === '200') {
          (resp.ambientes || []).forEach(a => {
            const opt = document.createElement('option');
            opt.value       = a.idAmbiente;
            opt.textContent = `${a.codigo} — N°${a.numero}`;
            opt.dataset.area    = a.nombreArea || '';
            opt.dataset.idarea  = a.idArea || '';
            sel.appendChild(opt);
          });
        }
        sel.disabled = false;
      });
  }

  function cargarAmbientesPorSedeEdit(idSede, idAmbActual) {
    const sel = document.getElementById('selectAmbienteEdit');
    if (!sel) return;
    sel.innerHTML = '<option value="">Cargando...</option>';
    sel.disabled = true;

    const fd = new FormData();
    fd.append('listarAmbientesPorSede', 'ok');
    fd.append('idSede', idSede);

    fetch('controlador/ambienteControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        sel.innerHTML = '<option value="">Seleccione...</option>';
        if (resp.codigo === '200') {
          (resp.ambientes || []).forEach(a => {
            sel.innerHTML += `<option value="${a.idAmbiente}">${a.codigo} — N°${a.numero}</option>`;
          });
          if (idAmbActual) sel.value = idAmbActual;
        }
        sel.disabled = false;
      });
  }

  // Fichas completas (se filtran en el cliente por jornada)
  let todasLasFichas = [];

  function cargarFichas() {
    const fd = new FormData();
    fd.append('listarFicha', 'ok');
    fetch('controlador/fichaControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo === '200') {
          todasLasFichas = resp.listarFicha || [];
          renderFichasEnSelect(todasLasFichas);
        }
      });
  }

  function renderFichasEnSelect(fichas) {
    const sel = document.getElementById('selectFichaHorario');
    if (!sel) return;
    sel.innerHTML = '<option value="">—</option>';
    fichas.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.idFicha;
      opt.textContent = `${f.codigoFicha} — ${f.programa || ''} (${f.jornada})`;
      opt.dataset.jornada      = f.jornada || '';
      opt.dataset.tipoprograma = f.tipoFormacion || f.programa || '';
      sel.appendChild(opt);
    });
  }

  function filtrarFichasPorJornada(jornada) {
    const filtradas = jornada
      ? todasLasFichas.filter(f => f.jornada === jornada)
      : todasLasFichas;
    renderFichasEnSelect(filtradas);
  }

  /* ============================================================
     CALENDARIO — PREVIEW
  ============================================================ */

  /** Obtener IDs-BD de los días marcados en el calendario crear */
  function getDiasSeleccionadosCalendario() {
    const activos = document.querySelectorAll('.dia-header.dia-activo');
    const ids = [];
    activos.forEach(th => {
      const col  = parseInt(th.dataset.dia);  // 1=Lunes…6=Sábado
      const idDB = COL_DIA[col];
      if (idDB) ids.push(idDB);
    });
    // Si el mapa aún no está listo, usar el número de columna como fallback
    if (ids.length === 0) {
      activos.forEach(th => ids.push(parseInt(th.dataset.dia)));
    }
    return ids;
  }

  /** Días del formulario editar */
  function getDiasEditSeleccionados() {
    return Array.from(
      document.querySelectorAll('#formEditarHorario .dia-header.dia-activo')
    ).map(th => {
      const col  = parseInt(th.dataset.dia);
      const idDB = COL_DIA[col];
      return idDB || col;
    });
  }

  /** Marcar días en el panel editar (recibe array de nombres o números) */
  function marcarDiasEdit(diasArr) {
    // Desmarcar todos
    document.querySelectorAll('#formEditarHorario .dia-header').forEach(th => {
      th.classList.remove('dia-activo');
    });
    document.querySelectorAll('#formEditarHorario .cal-cell').forEach(td => {
      td.classList.remove('dia-seleccionado');
    });

    diasArr.forEach(d => {
      const col = parseInt(d);
      if (!isNaN(col)) {
        const th = document.querySelector(`#formEditarHorario .dia-header[data-dia="${col}"]`);
        if (th) th.classList.add('dia-activo');
        const td = document.querySelector(`#formEditarHorario .cal-cell[data-dia="${col}"]`);
        if (td) td.classList.add('dia-seleccionado');
      }
    });
  }

  /** Actualizar la vista previa dentro de las celdas del calendario */
  function actualizarPreviewCalendario() {
    const horaInicio  = document.getElementById('horaInicioHorario').value;
    const horaFin     = document.getElementById('horaFinHorario').value;
    const fichaOpt    = document.getElementById('selectFichaHorario');
    const codigoFicha = fichaOpt.options[fichaOpt.selectedIndex]?.textContent?.split('—')[0]?.trim() || '';
    const instrOpt    = document.getElementById('selectInstructorHorario');
    const instructor  = instrOpt.options[instrOpt.selectedIndex]?.text?.split('—')[0]?.trim() || '';

    // Limpiar todas las celdas
    document.querySelectorAll('#calendarioSemanal .cal-cell-inner').forEach(ci => {
      ci.innerHTML = '';
    });

    if (!horaInicio || !horaFin) return;

    // Pintar en cada día activo
    document.querySelectorAll('#calendarioSemanal .dia-header.dia-activo').forEach(th => {
      const col   = th.dataset.dia;
      const celda = document.querySelector(`#calendarioSemanal .cal-cell[data-dia="${col}"] .cal-cell-inner`);
      if (!celda) return;

      celda.innerHTML = `
        <div class="horario-cal-card">
          <div class="hc-hora"><i class="bi bi-clock-fill" style="font-size:9px"></i> ${horaInicio} – ${horaFin}</div>
          ${codigoFicha ? `<div class="hc-ficha"><i class="bi bi-journals" style="font-size:9px"></i> ${codigoFicha}</div>` : ''}
          ${instructor  ? `<div class="hc-instructor"><i class="bi bi-person-fill" style="font-size:9px"></i> ${instructor}</div>` : ''}
        </div>`;
    });
  }

  /** Actualizar panel de chips de preview inferior */
  function actualizarPreview() {
    actualizarPreviewCalendario();

    const horaInicio = document.getElementById('horaInicioHorario').value;
    const horaFin    = document.getElementById('horaFinHorario').value;
    const fichaOpt   = document.getElementById('selectFichaHorario');
    const sedeOpt    = document.getElementById('selectSedeHorario');
    const instrOpt   = document.getElementById('selectInstructorHorario');

    const fichaText  = fichaOpt.options[fichaOpt.selectedIndex]?.textContent  || '';
    const sedeText   = sedeOpt.options[sedeOpt.selectedIndex]?.textContent    || '';
    const instrText  = instrOpt.options[instrOpt.selectedIndex]?.textContent  || '';
    const jornada    = document.getElementById('selectJornadaHorario')?.value || '';

    if (!horaInicio) {
      document.getElementById('horarioPreviewCard').style.display = 'none';
      return;
    }

    document.getElementById('horarioPreviewCard').style.display = 'block';
    document.getElementById('previewInfo').innerHTML = `
      ${sedeText    ? chipHtml('bi-geo-alt',        sedeText.split('—')[0].trim())  : ''}
      ${fichaText   ? chipHtml('bi-journals',        fichaText.split('—')[0].trim()) : ''}
      ${jornada     ? chipHtml('bi-sun',             jornada)                        : ''}
      ${horaInicio  ? chipHtml('bi-clock',           horaInicio + ' – ' + (horaFin || '?')) : ''}
      ${instrText   ? chipHtml('bi-person-badge',    instrText.split('—')[0].trim()) : ''}
    `;
  }

  function chipHtml(icon, texto) {
    return `<span class="preview-chip"><i class="bi ${icon}"></i>${texto}</span>`;
  }

  /* ============================================================
     HELPERS
  ============================================================ */

  function resetFormCrear() {
    document.getElementById('formCrearHorario')?.reset();
    // Desmarcar días
    document.querySelectorAll('#formCrearHorario .dia-header, #calendarioSemanal .dia-header').forEach(th => {
      th.classList.remove('dia-activo');
    });
    document.querySelectorAll('#calendarioSemanal .cal-cell').forEach(td => {
      td.classList.remove('dia-seleccionado');
    });
    document.querySelectorAll('#calendarioSemanal .cal-cell-inner').forEach(ci => {
      ci.innerHTML = '';
    });
    document.getElementById('horarioPreviewCard').style.display = 'none';
    document.getElementById('previewInfo').innerHTML = '';
    document.getElementById('inputTipoPrograma').value = '';

    // Deshabilitar selects en cascada
    const selAmb  = document.getElementById('selectAmbienteHorario');
    const selArea = document.getElementById('selectAreaAmbiente');
    if (selAmb)  { selAmb.innerHTML  = '<option value="">—</option>'; selAmb.disabled  = true; }
    if (selArea) { selArea.innerHTML = '<option value="">—</option>'; selArea.disabled = true; }
  }

  function resetSelect(sel, placeholder) {
    if (!sel) return;
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled  = true;
  }

  function abreviarDia(nombre) {
    const mapa = {
      'Lunes':'LUN','Martes':'MAR','Miércoles':'MIÉ','Miercoles':'MIE',
      'Jueves':'JUE','Viernes':'VIE','Sábado':'SAB','Sabado':'SAB','Domingo':'DOM'
    };
    return mapa[nombre] || nombre.substring(0, 3).toUpperCase();
  }

  function formatFecha(fecha) {
    if (!fecha) return '—';
    try {
      return new Date(fecha + 'T00:00:00').toLocaleDateString('es-CO', {
        day: '2-digit', month: 'short', year: 'numeric'
      });
    } catch { return fecha; }
  }

  function inferirJornadaBadge(horaInicio) {
    if (!horaInicio) return '<span class="badge-jornada">—</span>';
    const h = parseInt(horaInicio.split(':')[0]);
    if (h >= 6 && h < 12)  return '<span class="badge-jornada badge-manana">🌅 Mañana</span>';
    if (h >= 12 && h < 18) return '<span class="badge-jornada badge-tarde">☀️ Tarde</span>';
    return '<span class="badge-jornada badge-noche">🌙 Noche</span>';
  }

  function emptyState() {
    return `<div class="horario-empty">
      <i class="bi bi-calendar3"></i>
      <div style="font-weight:700;color:#1e293b;margin-bottom:4px;">Sin horarios registrados</div>
      <div style="font-size:12px;">Crea el primer horario con el botón <strong>+ Nuevo Horario</strong></div>
    </div>`;
  }

});