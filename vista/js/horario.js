document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     ESTADO LOCAL
  ============================================================ */
  let horarioDataTable = null;
  let diasDB = [];
  let todasLasFichas = []; // Cache de todas las fichas para filtrar

  const DIAS_MAP = {
    'Lunes': 1, 'Martes': 2, 'Miércoles': 3, 'Miercoles': 3,
    'Jueves': 4, 'Viernes': 5, 'Sábado': 6, 'Sabado': 6, 'Domingo': 7
  };

  const COL_DIA = { 1: null, 2: null, 3: null, 4: null, 5: null, 6: null };

  /* ============================================================
     INICIALIZACIÓN
  ============================================================ */
  listarHorarios();
  cargarDiasDB();
  cargarInstructores();
  cargarSedes();
  cargarTodasLasFichas(); // Carga todas las fichas al inicio y las guarda en cache

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

  document.getElementById('btnNuevoHorario')?.addEventListener('click', () => {
    resetFormCrear();
    mostrarPanel('panelFormularioHorario');
  });

  document.getElementById('btnRegresarTablaHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnRegresarTablaHorarioEdit')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarEditarHorario')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));

  /* ============================================================
     FIX 1: CASCADA SEDE → AMBIENTES + FICHAS FILTRADAS POR SEDE
     Cuando cambia la sede, se cargan ambientes Y se re-filtran las fichas
  ============================================================ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    const idSede = this.value;
    const selAmb = document.getElementById('selectAmbienteHorario');
    const selFicha = document.getElementById('selectFichaHorario');

    resetSelect(selAmb, '— Seleccione ambiente —');

    // Resetear ficha cuando cambia la sede
    if (selFicha) {
      selFicha.innerHTML = '<option value="">— Seleccione sede primero —</option>';
      selFicha.disabled = true;
    }

    if (!idSede) return;

    cargarAmbientesPorSede(idSede, 'selectAmbienteHorario');

    // Filtrar fichas por sede (y jornada si ya está seleccionada)
    const jornadaSeleccionada = document.getElementById('selectJornadaHorario')?.value || '';
    filtrarFichasPorSedeYJornada(idSede, jornadaSeleccionada);
  });

  // Cuando cambia ambiente → actualizar preview
  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    actualizarPreview();
  });

  /* ============================================================
     FIX 1: JORNADA → FILTRA FICHAS POR SEDE + JORNADA
     Antes filtraba todas las fichas de la jornada sin importar sede
  ============================================================ */
  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    const jornada = this.value;
    const idSede = document.getElementById('selectSedeHorario')?.value || '';

    if (!idSede) {
      // Si no hay sede seleccionada, avisar
      const selFicha = document.getElementById('selectFichaHorario');
      if (selFicha) {
        selFicha.innerHTML = '<option value="">— Seleccione sede primero —</option>';
        selFicha.disabled = true;
      }
      actualizarPreview();
      return;
    }

    filtrarFichasPorSedeYJornada(idSede, jornada);
    actualizarPreview();
  });

  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('inputTipoPrograma').value = opt?.dataset.tipoprograma || '';
    actualizarPreview();
  });

  ['horaInicioHorario', 'horaFinHorario', 'fechaInicioHorario', 'fechaFinHorario',
   'selectInstructorHorario', 'selectSedeHorario'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', actualizarPreview);
  });

  /* ============================================================
     CALENDARIO — clic en cabecera del día
  ============================================================ */
  document.querySelectorAll('.dia-header').forEach(th => {
    th.addEventListener('click', function () {
      const dia = parseInt(this.dataset.dia);
      this.classList.toggle('dia-activo');

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
     CLICK EDITAR (delegado)
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

    const diasStr = String($(this).data('dias') || '');
    const diasArr = diasStr ? diasStr.split(',').map(d => d.trim()) : [];
    marcarDiasEdit(diasArr);

    mostrarPanel('panelEditarHorario');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  /* ============================================================
     CLICK ELIMINAR (delegado)
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
     FIX 3: LISTAR HORARIOS — tabla compacta
     Columnas: Sede | Área | Ficha | Jornada | Tipo Programa | Instructor | Acciones
  ============================================================ */
  function listarHorarios() {
    const fd = new FormData();
    fd.append('listarHorarios', 'ok');

    fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .catch(err => console.error('listarHorarios:', err))
      .then(response => {
        if (!response || response.codigo !== '200') {
          const tbody = document.getElementById('tbodyHorarios');
          if (tbody) tbody.innerHTML = `<tr><td colspan="7">${emptyState()}</td></tr>`;
          return;
        }

        if ($.fn.DataTable.isDataTable('#tablaHorarios')) {
          $('#tablaHorarios').DataTable().clear().destroy();
        }

        const horarios = response.horarios || [];
        const dataSet  = [];

        horarios.forEach(item => {
          const nombre    = item.instructorNombre || '—';
          const iniciales = nombre !== '—'
            ? nombre.trim().split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
            : '?';

          // Instructor con avatar
          const instructorHtml = `
            <div class="instructor-cell">
              <div class="instructor-avatar">${iniciales}</div>
              <span style="font-size:12px;font-weight:600;">${nombre}</span>
            </div>`;

          // Jornada badge inferida de la hora
          const jornadaBadge = inferirJornadaBadge(item.hora_inicioClase);

          // Tipo programa (puede venir como campo o necesita join — mostramos lo disponible)
          const tipoPrograma = item.tipoPrograma || item.tipoprograma || '—';
          const sedeNombre   = item.sedeNombre   || item.sede         || '—';
          const areaNombre   = item.areaNombre   || item.area         || '—';

          const botones = `
            <div class="action-group">
              <button type="button" class="btn btn-info btnEditarHorario"
                data-id="${item.idHorario}"
                data-hora-inicio="${item.hora_inicioClase || ''}"
                data-hora-fin="${item.hora_finClase || ''}"
                data-fecha-inicio="${item.fecha_inicioHorario || ''}"
                data-fecha-fin="${item.fecha_finHorario || ''}"
                data-id-ambiente="${item.idAmbiente || ''}"
                data-id-sede="${item.idSede || ''}"
                data-dias="${item.dias || ''}">
                <i class="bi bi-pen"></i>
              </button>
              <button type="button" class="btn btn-danger btnEliminarHorario" data-id="${item.idHorario}">
                <i class="bi bi-trash"></i>
              </button>
            </div>`;

          // FIX 3: Solo 7 columnas: Sede, Área, Ficha, Jornada, Tipo Programa, Instructor, Acciones
          dataSet.push([
            sedeNombre,
            areaNombre,
            `<strong>${item.codigoFicha || '—'}</strong>`,
            jornadaBadge,
            tipoPrograma,
            instructorHtml,
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

  /* ============================================================
     CREAR / EDITAR HORARIO
  ============================================================ */
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
    diasSeleccionados.forEach(idDia => fd.append('dias[]', idDia));

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
      .catch(err => {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error de conexión', text: String(err) });
      });
  }

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
        sel.innerHTML = '<option value="">— Seleccione instructor —</option>';
        (resp.listarInstructor || []).forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.idFuncionario;
          opt.textContent = item.nombre;
          sel.appendChild(opt);
        });
      })
      .catch(console.error);
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
        sel.innerHTML = '<option value="">— Seleccione sede —</option>';
        (resp.listarSedes || []).forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.idSede;
          opt.textContent = item.nombre;
          sel.appendChild(opt);
        });
      })
      .catch(console.error);
  }

  /* ============================================================
     FIX 1: CARGAR TODAS LAS FICHAS EN CACHE
     Las guardamos para filtrar localmente por sede + jornada
  ============================================================ */
  function cargarTodasLasFichas() {
    const fd = new FormData();
    fd.append('listarFicha', 'ok');
    fetch('controlador/fichaControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo === '200') {
          todasLasFichas = resp.listarFicha || [];
        }
      })
      .catch(console.error);
  }

  /* ============================================================
     FIX 1: FILTRAR FICHAS POR SEDE + JORNADA
     La ficha está amarrada a ambiente → ambiente está amarrado a sede
     Por eso filtramos por idSede que viene en el listado de fichas
  ============================================================ */
  function filtrarFichasPorSedeYJornada(idSede, jornada) {
    const sel = document.getElementById('selectFichaHorario');
    if (!sel) return;

    // Filtrar fichas que pertenecen a la sede seleccionada
    let fichasFiltradas = todasLasFichas.filter(f => String(f.idSede) === String(idSede));

    // Si además hay jornada seleccionada, filtrar también por jornada
    if (jornada) {
      fichasFiltradas = fichasFiltradas.filter(f =>
        f.jornada && f.jornada.toUpperCase() === jornada.toUpperCase()
      );
    }

    if (fichasFiltradas.length === 0) {
      sel.innerHTML = `<option value="">— Sin fichas para esta sede${jornada ? '/jornada' : ''} —</option>`;
      sel.disabled = true;
      return;
    }

    sel.innerHTML = '<option value="">— Seleccione ficha —</option>';
    fichasFiltradas.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.idFicha;
      opt.textContent = `${f.codigoFicha} — ${f.programa}`;
      opt.dataset.tipoprograma = f.tipoPrograma || f.tipoprograma || '';
      opt.dataset.jornada = f.jornada || '';
      sel.appendChild(opt);
    });
    sel.disabled = false;
  }

  /* ============================================================
     CARGAR AMBIENTES POR SEDE
  ============================================================ */
  function cargarAmbientesPorSede(idSede, selectId) {
    const sel = document.getElementById(selectId);
    if (!sel) return;
    sel.innerHTML = '<option value="">Cargando ambientes...</option>';
    sel.disabled = true;

    const fd = new FormData();
    fd.append('listarAmbientesPorSede', 'ok');
    fd.append('idSede', idSede);

    fetch('controlador/ambienteControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') {
          sel.innerHTML = '<option value="">— Sin ambientes —</option>';
          return;
        }
        sel.innerHTML = '<option value="">— Seleccione ambiente —</option>';
        (resp.ambientes || []).forEach(amb => {
          const opt = document.createElement('option');
          opt.value = amb.idAmbiente;
          opt.textContent = `${amb.codigo} — No. ${amb.numero}`;
          opt.dataset.area    = amb.nombreArea || '—';
          opt.dataset.idarea  = amb.idArea || '';
          sel.appendChild(opt);
        });
        sel.disabled = false;
      })
      .catch(console.error);
  }

  function cargarAmbientesPorSedeEdit(idSede, idAmbActual) {
    cargarAmbientesPorSede(idSede, 'selectAmbienteEdit');
    // Después de cargar, seleccionar el actual
    setTimeout(() => {
      const sel = document.getElementById('selectAmbienteEdit');
      if (sel && idAmbActual) sel.value = idAmbActual;
    }, 600);
  }

  /* ============================================================
     HELPERS — CALENDARIO
  ============================================================ */
  function getDiasSeleccionadosCalendario() {
    const activos = document.querySelectorAll('.dia-header.dia-activo');
    const ids = [];
    activos.forEach(th => {
      const colIdx = parseInt(th.dataset.dia); // 1=Lun, 2=Mar...
      const idDia  = COL_DIA[colIdx];
      if (idDia) ids.push(idDia);
    });
    return ids;
  }

  function getDiasEditSeleccionados() {
    const activos = document.querySelectorAll('.dia-toggle-edit:checked');
    const ids = [];
    activos.forEach(cb => ids.push(parseInt(cb.value)));
    return ids;
  }

  function marcarDiasEdit(diasIds) {
    document.querySelectorAll('.dia-toggle-edit').forEach(cb => {
      cb.checked = diasIds.includes(String(cb.value)) || diasIds.includes(cb.value);
    });
  }

  function actualizarPreview() {
    const horaInicio  = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin     = document.getElementById('horaFinHorario')?.value    || '';
    const fichaNombre = document.getElementById('selectFichaHorario')?.options[
      document.getElementById('selectFichaHorario')?.selectedIndex
    ]?.text || '—';
    const instructorNombre = document.getElementById('selectInstructorHorario')?.options[
      document.getElementById('selectInstructorHorario')?.selectedIndex
    ]?.text || '—';

    const previewHora = document.getElementById('previewHora');
    const previewFicha = document.getElementById('previewFicha');
    const previewInstructor = document.getElementById('previewInstructor');

    if (previewHora && horaInicio) previewHora.textContent = `${horaInicio} - ${horaFin}`;
    if (previewFicha) previewFicha.textContent = fichaNombre !== '— Seleccione ficha —' ? fichaNombre : '—';
    if (previewInstructor) previewInstructor.textContent = instructorNombre !== '— Seleccione instructor —' ? instructorNombre : '—';

    actualizarPreviewCalendario();
  }

  function actualizarPreviewCalendario() {
    const activos = document.querySelectorAll('.dia-header.dia-activo');
    const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
    const fichaTxt   = document.getElementById('selectFichaHorario')?.options[
      document.getElementById('selectFichaHorario')?.selectedIndex
    ]?.text || '';

    document.querySelectorAll('.cal-cell-inner').forEach(ci => {
      ci.innerHTML = '';
    });

    if (!horaInicio) return;

    activos.forEach(th => {
      const dia = parseInt(th.dataset.dia);
      const celda = document.querySelector(`.cal-cell-inner[data-dia="${dia}"]`);
      if (!celda) return;
      celda.innerHTML = `
        <div class="horario-cal-card">
          <div class="hc-hora">${horaInicio} – ${horaFin}</div>
          <div class="hc-ficha">${fichaTxt.substring(0, 25) || '—'}</div>
        </div>`;
    });
  }

  /* ============================================================
     HELPERS — FORMATOS
  ============================================================ */
  function inferirJornadaBadge(horaInicio) {
    if (!horaInicio) return '<span class="badge-jornada">—</span>';
    const h = parseInt(horaInicio.split(':')[0]);
    if (h < 12) return '<span class="badge-jornada badge-manana">🌅 Mañana</span>';
    if (h < 18) return '<span class="badge-jornada badge-tarde">☀️ Tarde</span>';
    return '<span class="badge-jornada badge-noche">🌙 Noche</span>';
  }

  function abreviarDia(nombre) {
    const mapa = {
      'Lunes': 'Lun', 'Martes': 'Mar', 'Miércoles': 'Mié', 'Miercoles': 'Mié',
      'Jueves': 'Jue', 'Viernes': 'Vie', 'Sábado': 'Sáb', 'Sabado': 'Sáb', 'Domingo': 'Dom'
    };
    return mapa[nombre] || nombre.substring(0, 3);
  }

  function formatFecha(fecha) {
    if (!fecha) return '—';
    try {
      return new Date(fecha + 'T00:00:00').toLocaleDateString('es-CO', { day: '2-digit', month: '2-digit', year: 'numeric' });
    } catch {
      return fecha;
    }
  }

  function emptyState() {
    return `<div class="horario-empty">
      <i class="bi bi-calendar-x"></i>
      <p>No hay horarios registrados</p>
    </div>`;
  }

  function resetSelect(sel, placeholder) {
    if (!sel) return;
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled  = true;
  }

  function resetFormCrear() {
    document.getElementById('formCrearHorario')?.reset();
    document.querySelectorAll('.dia-header').forEach(th => th.classList.remove('dia-activo'));
    document.querySelectorAll('.cal-cell').forEach(c => c.classList.remove('dia-seleccionado'));
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');

    const selAmb = document.getElementById('selectAmbienteHorario');
    resetSelect(selAmb, '— Seleccione sede primero —');

    const selFicha = document.getElementById('selectFichaHorario');
    resetSelect(selFicha, '— Seleccione sede primero —');

    const inputTipo = document.getElementById('inputTipoPrograma');
    if (inputTipo) inputTipo.value = '';
  }

});