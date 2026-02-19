document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     ESTADO LOCAL
  ============================================================ */
  let horarioDataTable = null;
  let diasDB = [];
  let todasLasFichas = [];
  let todosLosInstructores = []; // FIX 3: Cache de instructores para filtrar por área

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
  cargarTodasLasFichas();

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
     FIX 1 + FIX 3: CASCADA SEDE → AMBIENTES + FICHAS + INSTRUCTORES RESET
  ============================================================ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    const idSede = this.value;
    const selAmb = document.getElementById('selectAmbienteHorario');
    const selFicha = document.getElementById('selectFichaHorario');

    resetSelect(selAmb, '— Seleccione ambiente —');

    if (selFicha) {
      selFicha.innerHTML = '<option value="">— Seleccione sede primero —</option>';
      selFicha.disabled = true;
    }

    // Resetear instructores al estado completo cuando cambia la sede
    renderInstructores(todosLosInstructores, null, null);

    if (!idSede) return;

    cargarAmbientesPorSede(idSede, 'selectAmbienteHorario');

    const jornadaSeleccionada = document.getElementById('selectJornadaHorario')?.value || '';
    filtrarFichasPorSedeYJornada(idSede, jornadaSeleccionada);
  });

  /* ============================================================
     FIX 3: AMBIENTE → FILTRAR INSTRUCTORES POR ÁREA
  ============================================================ */
  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const idArea    = opt?.dataset.idarea   || '';
    const areaNombre = opt?.dataset.area    || '';

    actualizarPreview();

    if (idArea) {
      filtrarInstructoresPorArea(idArea, areaNombre);
    } else {
      // Sin ambiente seleccionado → mostrar todos
      renderInstructores(todosLosInstructores, null, null);
    }
  });

  /* ============================================================
     FIX 1: JORNADA → FILTRA FICHAS POR SEDE + JORNADA
  ============================================================ */
  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    const jornada = this.value;
    const idSede = document.getElementById('selectSedeHorario')?.value || '';

    if (!idSede) {
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

  /* ============================================================
     FIX 2: FICHA → AUTO-RELLENAR TIPO PROGRAMA
  ============================================================ */
  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const tipoprograma = opt?.dataset.tipoprograma || opt?.dataset.tipoformacion || '';
    const inputTipo = document.getElementById('inputTipoPrograma');
    if (inputTipo) {
      inputTipo.value = tipoprograma;
      // Efecto visual para confirmar que se cargó
      inputTipo.classList.add('input-filled');
      setTimeout(() => inputTipo.classList.remove('input-filled'), 800);
    }
    actualizarPreview();
  });

  ['horaInicioHorario', 'horaFinHorario', 'fechaInicioHorario', 'fechaFinHorario',
   'selectInstructorHorario', 'selectSedeHorario'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', actualizarPreview);
  });

  /* ============================================================
     FIX 3: BUSQUEDA DE INSTRUCTOR POR NOMBRE (input de búsqueda)
  ============================================================ */
  document.getElementById('inputBuscarInstructor')?.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    if (!query) {
      // Si borra la búsqueda, volver al estado previo (filtrado por área si hay ambiente)
      const selAmb = document.getElementById('selectAmbienteHorario');
      const opt = selAmb?.options[selAmb.selectedIndex];
      const idArea = opt?.dataset.idarea || '';
      const areaNombre = opt?.dataset.area || '';
      if (idArea) {
        filtrarInstructoresPorArea(idArea, areaNombre);
      } else {
        renderInstructores(todosLosInstructores, null, null);
      }
      return;
    }

    const filtrados = todosLosInstructores.filter(i =>
      i.nombre.toLowerCase().includes(query)
    );
    renderInstructores(filtrados, null, null, true);
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
     LISTAR HORARIOS
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

          const instructorHtml = `
            <div class="instructor-cell">
              <div class="instructor-avatar">${iniciales}</div>
              <span style="font-size:12px;font-weight:600;">${nombre}</span>
            </div>`;

          const jornadaBadge = inferirJornadaBadge(item.hora_inicioClase);
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

  /* ============================================================
     FIX 3: CARGAR INSTRUCTORES — guarda en cache todosLosInstructores
     El backend debe devolver idArea por cada instructor
  ============================================================ */
  function cargarInstructores() {
    const fd = new FormData();
    fd.append('listarInstructor', 'ok');
    fetch('controlador/instructorControlador.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        todosLosInstructores = resp.listarInstructor || [];
        renderInstructores(todosLosInstructores, null, null);
      })
      .catch(console.error);
  }

  /* ============================================================
     FIX 3: RENDER DE INSTRUCTORES EN EL SELECT
     Soporta: todos, filtrados por área (con grupo), o búsqueda libre
  ============================================================ */
  function renderInstructores(instructores, idAreaActiva, areaNombre, esBusqueda = false) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;

    sel.innerHTML = '<option value="">— Seleccione instructor —</option>';

    if (esBusqueda) {
      // Modo búsqueda: lista plana sin grupos
      if (instructores.length === 0) {
        sel.innerHTML += '<option disabled>— Sin resultados —</option>';
        return;
      }
      instructores.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.idFuncionario;
        opt.textContent = item.nombre;
        sel.appendChild(opt);
      });
      return;
    }

    if (idAreaActiva) {
      // Modo área: primero del área, luego el resto
      const delArea  = instructores.filter(i => String(i.idArea) === String(idAreaActiva));
      const delResto = instructores.filter(i => String(i.idArea) !== String(idAreaActiva));

      if (delArea.length > 0) {
        const grpArea = document.createElement('optgroup');
        grpArea.label = `📍 Área: ${areaNombre} (${delArea.length})`;
        delArea.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.idFuncionario;
          opt.textContent = item.nombre;
          grpArea.appendChild(opt);
        });
        sel.appendChild(grpArea);
      }

      if (delResto.length > 0) {
        const grpResto = document.createElement('optgroup');
        grpResto.label = '── Otros instructores ──';
        delResto.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.idFuncionario;
          opt.textContent = item.nombre;
          grpResto.appendChild(opt);
        });
        sel.appendChild(grpResto);
      }
    } else {
      // Sin área activa: lista plana con todos
      instructores.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.idFuncionario;
        opt.textContent = item.nombre;
        sel.appendChild(opt);
      });
    }
  }

  /* ============================================================
     FIX 3: FILTRAR INSTRUCTORES POR ÁREA DEL AMBIENTE
  ============================================================ */
  function filtrarInstructoresPorArea(idArea, areaNombre) {
    renderInstructores(todosLosInstructores, idArea, areaNombre);

    // Mostrar/actualizar el hint de búsqueda
    const hint = document.getElementById('instructorAreaHint');
    if (hint) {
      const count = todosLosInstructores.filter(i => String(i.idArea) === String(idArea)).length;
      hint.textContent = count > 0
        ? `${count} instructor${count > 1 ? 'es' : ''} del área "${areaNombre}" aparecen primero`
        : `Sin instructores en el área "${areaNombre}" — mostrando todos`;
      hint.style.display = 'block';
    }
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
     FIX 1 + FIX 2: FILTRAR FICHAS POR SEDE + JORNADA
     Mapea todos los posibles nombres del campo tipo programa
  ============================================================ */
  function filtrarFichasPorSedeYJornada(idSede, jornada) {
    const sel = document.getElementById('selectFichaHorario');
    if (!sel) return;

    let fichasFiltradas = todasLasFichas.filter(f => String(f.idSede) === String(idSede));

    if (jornada) {
      fichasFiltradas = fichasFiltradas.filter(f =>
        f.jornada && f.jornada.toUpperCase() === jornada.toUpperCase()
      );
    }

    if (fichasFiltradas.length === 0) {
      sel.innerHTML = `<option value="">— Sin fichas para esta sede${jornada ? '/jornada' : ''} —</option>`;
      sel.disabled = true;
      const inputTipo = document.getElementById('inputTipoPrograma');
      if (inputTipo) inputTipo.value = '';
      return;
    }

    sel.innerHTML = '<option value="">— Seleccione ficha —</option>';
    fichasFiltradas.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.idFicha;
      opt.textContent = `${f.codigoFicha} — ${f.programa || f.programaNombre || ''}`;

      // FIX 2: Mapear todos los posibles nombres del campo tipo programa
      const tipoVal = f.tipoPrograma || f.tipoprograma || f.tipoFormacion || f.tipoformacion || '';
      opt.dataset.tipoprograma  = tipoVal;
      opt.dataset.tipoformacion = tipoVal;
      opt.dataset.jornada       = f.jornada || '';
      sel.appendChild(opt);
    });
    sel.disabled = false;
  }

  /* ============================================================
     FIX 1: CARGAR AMBIENTES POR SEDE
     Muestra: código — No. número | Área
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

          // FIX 1: Incluir el área en el texto del option
          const areaTxt = amb.nombreArea ? ` | ${amb.nombreArea}` : '';
          opt.textContent = `${amb.codigo} — No. ${amb.numero}${areaTxt}`;

          opt.dataset.area   = amb.nombreArea || '—';
          opt.dataset.idarea = amb.idArea     || '';
          sel.appendChild(opt);
        });
        sel.disabled = false;

        // Resetear hint de instructor al cambiar ambientes disponibles
        const hint = document.getElementById('instructorAreaHint');
        if (hint) hint.style.display = 'none';
      })
      .catch(console.error);
  }

  function cargarAmbientesPorSedeEdit(idSede, idAmbActual) {
    cargarAmbientesPorSede(idSede, 'selectAmbienteEdit');
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
      const colIdx = parseInt(th.dataset.dia);
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
    const fichaSelect = document.getElementById('selectFichaHorario');
    const fichaNombre = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '—';
    const instrSelect = document.getElementById('selectInstructorHorario');
    const instructorNombre = instrSelect?.options[instrSelect?.selectedIndex]?.text || '—';

    const previewHora = document.getElementById('previewHora');
    const previewFicha = document.getElementById('previewFicha');
    const previewInstructor = document.getElementById('previewInstructor');

    if (previewHora && horaInicio) previewHora.textContent = `${horaInicio} - ${horaFin}`;
    if (previewFicha) previewFicha.textContent = (fichaNombre && fichaNombre !== '— Seleccione ficha —') ? fichaNombre : '—';
    if (previewInstructor) previewInstructor.textContent = (instructorNombre && instructorNombre !== '— Seleccione instructor —') ? instructorNombre : '—';

    actualizarPreviewCalendario();
  }

  function actualizarPreviewCalendario() {
    const activos = document.querySelectorAll('.dia-header.dia-activo');
    const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
    const fichaSelect = document.getElementById('selectFichaHorario');
    const fichaTxt = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '';

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

    // Limpiar búsqueda de instructor y resetear select
    const buscar = document.getElementById('inputBuscarInstructor');
    if (buscar) buscar.value = '';
    renderInstructores(todosLosInstructores, null, null);

    const hint = document.getElementById('instructorAreaHint');
    if (hint) hint.style.display = 'none';
  }

});