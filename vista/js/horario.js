document.addEventListener('DOMContentLoaded', function () {

  /* ============================================================
     ESTADO LOCAL
  ============================================================ */
  let horarioDataTable     = null;
  let diasDB               = [];
  let todasLasFichas       = [];
  let todosLosInstructores = []; // cache para búsqueda por nombre

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
  cargarInstructoresTodos();   // llena el cache y el select inicial
  cargarSedes();
  cargarTodasLasFichas();

  /* ============================================================
     NAVEGACIÓN PANELES
  ============================================================ */
  function mostrarPanel(id) {
    ['panelTablaHorario','panelFormularioHorario','panelEditarHorario'].forEach(p => {
      const el = document.getElementById(p);
      if (el) el.style.display = (p === id) ? 'block' : 'none';
    });
  }

  document.getElementById('btnNuevoHorario')?.addEventListener('click', () => {
    resetFormCrear();
    mostrarPanel('panelFormularioHorario');
  });
  document.getElementById('btnRegresarTablaHorario')?.addEventListener('click',     () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarHorario')?.addEventListener('click',          () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnRegresarTablaHorarioEdit')?.addEventListener('click', () => mostrarPanel('panelTablaHorario'));
  document.getElementById('btnCancelarEditarHorario')?.addEventListener('click',    () => mostrarPanel('panelTablaHorario'));

  /* ============================================================
     CASCADA: SEDE → AMBIENTES + FICHAS
  ============================================================ */
  document.getElementById('selectSedeHorario')?.addEventListener('change', function () {
    const idSede  = this.value;
    const selAmb  = document.getElementById('selectAmbienteHorario');
    const selFicha = document.getElementById('selectFichaHorario');

    resetSelect(selAmb, '— Seleccione ambiente —');
    if (selFicha) {
      selFicha.innerHTML = '<option value="">— Seleccione sede primero —</option>';
      selFicha.disabled  = true;
    }

    // Al cambiar sede: reset instructor a lista completa con área visible
    renderInstructoresLista(todosLosInstructores);
    ocultarHint();

    if (!idSede) return;

    cargarAmbientesPorSede(idSede, 'selectAmbienteHorario');
    filtrarFichasPorSedeYJornada(idSede, document.getElementById('selectJornadaHorario')?.value || '');
  });

  /* ============================================================
     AMBIENTE → CONSULTAR INSTRUCTORES POR ÁREA AL BACKEND
     Aquí está el fix principal: se hace un fetch real con idArea
  ============================================================ */
  document.getElementById('selectAmbienteHorario')?.addEventListener('change', function () {
    const opt       = this.options[this.selectedIndex];
    const idArea    = opt?.dataset.idarea  || '';
    const areaNombre = opt?.dataset.area   || '';

    actualizarPreview();

    if (idArea) {
      // Llamada real al backend filtrada por área
      fetchInstructoresPorArea(idArea, areaNombre);
    } else {
      renderInstructoresLista(todosLosInstructores);
      ocultarHint();
    }
  });

  /* ============================================================
     JORNADA → FILTRA FICHAS
  ============================================================ */
  document.getElementById('selectJornadaHorario')?.addEventListener('change', function () {
    const jornada = this.value;
    const idSede  = document.getElementById('selectSedeHorario')?.value || '';
    if (!idSede) {
      const selFicha = document.getElementById('selectFichaHorario');
      if (selFicha) {
        selFicha.innerHTML = '<option value="">— Seleccione sede primero —</option>';
        selFicha.disabled  = true;
      }
      actualizarPreview();
      return;
    }
    filtrarFichasPorSedeYJornada(idSede, jornada);
    actualizarPreview();
  });

  /* ============================================================
     FICHA → AUTO-RELLENAR TIPO PROGRAMA
  ============================================================ */
  document.getElementById('selectFichaHorario')?.addEventListener('change', function () {
    const opt     = this.options[this.selectedIndex];
    const tipoVal = opt?.dataset.tipoprograma || opt?.dataset.tipoformacion || '';
    const inputTipo = document.getElementById('inputTipoPrograma');
    if (inputTipo) {
      inputTipo.value = tipoVal;
      inputTipo.classList.add('input-filled');
      setTimeout(() => inputTipo.classList.remove('input-filled'), 800);
    }
    actualizarPreview();
  });

  ['horaInicioHorario','horaFinHorario','fechaInicioHorario','fechaFinHorario',
   'selectInstructorHorario','selectSedeHorario'].forEach(id => {
    document.getElementById(id)?.addEventListener('change', actualizarPreview);
  });

  /* ============================================================
     BÚSQUEDA DE INSTRUCTOR POR NOMBRE (filtro local sobre cache)
  ============================================================ */
  document.getElementById('inputBuscarInstructor')?.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();

    if (!q) {
      // Sin texto: restaurar estado según ambiente actual
      const selAmb  = document.getElementById('selectAmbienteHorario');
      const opt     = selAmb?.options[selAmb.selectedIndex];
      const idArea  = opt?.dataset.idarea  || '';
      const areaNombre = opt?.dataset.area || '';
      if (idArea) {
        fetchInstructoresPorArea(idArea, areaNombre);
      } else {
        renderInstructoresLista(todosLosInstructores);
        ocultarHint();
      }
      return;
    }

    // Filtrar cache por nombre O por nombreArea
    const filtrados = todosLosInstructores.filter(i =>
      (i.nombre     || '').toLowerCase().includes(q) ||
      (i.nombreArea || '').toLowerCase().includes(q)
    );
    renderInstructoresBusqueda(filtrados);
  });

  /* ============================================================
     CALENDARIO — clic en cabecera
  ============================================================ */
  document.querySelectorAll('.dia-header').forEach(th => {
    th.addEventListener('click', function () {
      this.classList.toggle('dia-activo');
      actualizarPreviewCalendario();
    });
  });

  /* ============================================================
     SUBMIT CREAR
  ============================================================ */
  document.getElementById('formCrearHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const diasSeleccionados = getDiasSeleccionadosCalendario();
    if (diasSeleccionados.length === 0) {
      Swal.fire({ icon:'warning', title:'Días requeridos',
        text:'Haz clic en al menos un día del calendario.', confirmButtonColor:'#7c6bff' });
      return;
    }
    crearHorario(diasSeleccionados);
  });

  /* ============================================================
     SUBMIT EDITAR
  ============================================================ */
  document.getElementById('formEditarHorario')?.addEventListener('submit', function (e) {
    e.preventDefault();
    editarHorario(getDiasEditSeleccionados());
  });

  /* ============================================================
     CLICK EDITAR (delegado)
  ============================================================ */
  $(document).on('click', '.btnEditarHorario', function (e) {
    e.preventDefault(); e.stopPropagation();
    document.getElementById('idHorarioEdit').value   = $(this).data('id');
    document.getElementById('horaInicioEdit').value  = $(this).data('hora-inicio') || '';
    document.getElementById('horaFinEdit').value     = $(this).data('hora-fin')    || '';
    document.getElementById('fechaInicioEdit').value = $(this).data('fecha-inicio')|| '';
    document.getElementById('fechaFinEdit').value    = $(this).data('fecha-fin')   || '';
    const idSede = $(this).data('id-sede')     || '';
    const idAmb  = $(this).data('id-ambiente') || '';
    if (idSede) cargarAmbientesPorSedeEdit(idSede, idAmb);
    const diasStr = String($(this).data('dias') || '');
    marcarDiasEdit(diasStr ? diasStr.split(',').map(d => d.trim()) : []);
    mostrarPanel('panelEditarHorario');
    window.scrollTo({ top:0, behavior:'smooth' });
  });

  /* ============================================================
     CLICK ELIMINAR (delegado)
  ============================================================ */
  $(document).on('click', '.btnEliminarHorario', function (e) {
    e.preventDefault(); e.stopPropagation();
    const idHorario = $(this).data('id');
    Swal.fire({
      title:'¿Eliminar horario?', text:'Esta acción no se puede deshacer.', icon:'warning',
      showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#6c757d',
      confirmButtonText:'Sí, eliminar', cancelButtonText:'Cancelar'
    }).then(r => {
      if (!r.isConfirmed) return;
      const fd = new FormData();
      fd.append('eliminarHorario','ok');
      fd.append('idHorario', idHorario);
      fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
        .then(r => r.json())
        .then(resp => {
          if (resp.codigo === '200') {
            Swal.fire({ icon:'success', title:'Eliminado', timer:1500, showConfirmButton:false });
            listarHorarios();
          } else {
            Swal.fire({ icon:'error', title:'Error', text:resp.mensaje });
          }
        });
    });
  });

  /* ============================================================
     LISTAR HORARIOS
  ============================================================ */
  function listarHorarios() {
    const fd = new FormData();
    fd.append('listarHorarios','ok');
    fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .catch(err => console.error('listarHorarios:', err))
      .then(response => {
        if (!response || response.codigo !== '200') {
          const tbody = document.getElementById('tbodyHorarios');
          if (tbody) tbody.innerHTML = `<tr><td colspan="7">${emptyState()}</td></tr>`;
          return;
        }
        if ($.fn.DataTable.isDataTable('#tablaHorarios'))
          $('#tablaHorarios').DataTable().clear().destroy();

        const dataSet = [];
        (response.horarios || []).forEach(item => {
          const nombre    = item.instructorNombre || '—';
          const iniciales = nombre !== '—'
            ? nombre.trim().split(' ').map(w => w[0]).slice(0,2).join('').toUpperCase() : '?';
          const instructorHtml = `
            <div class="instructor-cell">
              <div class="instructor-avatar">${iniciales}</div>
              <span style="font-size:12px;font-weight:600;">${nombre}</span>
            </div>`;
          const botones = `
            <div class="action-group">
              <button type="button" class="btn btn-info btnEditarHorario"
                data-id="${item.idHorario}"
                data-hora-inicio="${item.hora_inicioClase    || ''}"
                data-hora-fin="${item.hora_finClase          || ''}"
                data-fecha-inicio="${item.fecha_inicioHorario|| ''}"
                data-fecha-fin="${item.fecha_finHorario      || ''}"
                data-id-ambiente="${item.idAmbiente          || ''}"
                data-id-sede="${item.idSede                  || ''}"
                data-dias="${item.dias                       || ''}">
                <i class="bi bi-pen"></i>
              </button>
              <button type="button" class="btn btn-danger btnEliminarHorario" data-id="${item.idHorario}">
                <i class="bi bi-trash"></i>
              </button>
            </div>`;
          dataSet.push([
            item.sedeNombre  || item.sede || '—',
            item.areaNombre  || item.area || '—',
            `<strong>${item.codigoFicha || '—'}</strong>`,
            inferirJornadaBadge(item.hora_inicioClase),
            item.tipoPrograma || item.tipoprograma || '—',
            instructorHtml,
            botones
          ]);
        });
        horarioDataTable = $('#tablaHorarios').DataTable({
          buttons: [{ extend:'colvis', text:'Columnas' }, 'excel','pdf','print'],
          dom:'Bfrtip', responsive:true, destroy:true, data:dataSet,
          language:{ emptyTable:'— Sin horarios registrados —', search:'Buscar:',
            paginate:{ next:'Sig.', previous:'Ant.' } }
        });
      });
  }

  /* ============================================================
     CREAR / EDITAR
  ============================================================ */
  function crearHorario(diasSeleccionados) {
    Swal.fire({ title:'Guardando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
    const fd = new FormData();
    fd.append('crearHorario',        'ok');
    fd.append('idFuncionario',       document.getElementById('selectInstructorHorario').value);
    fd.append('idAmbiente',          document.getElementById('selectAmbienteHorario').value);
    fd.append('idFicha',             document.getElementById('selectFichaHorario').value);
    fd.append('hora_inicioClase',    document.getElementById('horaInicioHorario').value);
    fd.append('hora_finClase',       document.getElementById('horaFinHorario').value);
    fd.append('fecha_inicioHorario', document.getElementById('fechaInicioHorario').value);
    fd.append('fecha_finHorario',    document.getElementById('fechaFinHorario').value);
    diasSeleccionados.forEach(id => fd.append('dias[]', id));
    fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({ icon:'success', title:'¡Horario creado!', text:resp.mensaje,
            timer:1800, showConfirmButton:false });
          mostrarPanel('panelTablaHorario');
          listarHorarios();
          resetFormCrear();
        } else {
          Swal.fire({ icon:'error', title:'Error', html:resp.mensaje, confirmButtonColor:'#7c6bff' });
        }
      })
      .catch(err => { Swal.close();
        Swal.fire({ icon:'error', title:'Error de conexión', text:String(err) }); });
  }

  function editarHorario(diasSeleccionados) {
    Swal.fire({ title:'Guardando...', allowOutsideClick:false, didOpen:() => Swal.showLoading() });
    const fd = new FormData();
    fd.append('actualizarHorario',   'ok');
    fd.append('idHorario',           document.getElementById('idHorarioEdit').value);
    fd.append('idAmbiente',          document.getElementById('selectAmbienteEdit').value);
    fd.append('hora_inicioClase',    document.getElementById('horaInicioEdit').value);
    fd.append('hora_finClase',       document.getElementById('horaFinEdit').value);
    fd.append('fecha_inicioHorario', document.getElementById('fechaInicioEdit').value);
    fd.append('fecha_finHorario',    document.getElementById('fechaFinEdit').value);
    diasSeleccionados.forEach(id => fd.append('dias[]', id));
    fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        Swal.close();
        if (resp.codigo === '200') {
          Swal.fire({ icon:'success', title:'Actualizado', timer:1600, showConfirmButton:false });
          mostrarPanel('panelTablaHorario');
          listarHorarios();
        } else {
          Swal.fire({ icon:'error', title:'Error', html:resp.mensaje, confirmButtonColor:'#7c6bff' });
        }
      })
      .catch(err => { Swal.close();
        Swal.fire({ icon:'error', title:'Error de conexión', text:String(err) }); });
  }

  /* ============================================================
     CARGAR INSTRUCTORES — CACHE GLOBAL
     Guarda en todosLosInstructores[] para búsqueda libre
     La query en el modelo ya trae idArea y nombreArea
  ============================================================ */
  function cargarInstructoresTodos() {
    const fd = new FormData();
    fd.append('listarInstructor','ok');
    fetch('controlador/instructorControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        todosLosInstructores = resp.listarInstructor || [];
        renderInstructoresLista(todosLosInstructores);
      })
      .catch(console.error);
  }

  /* ============================================================
     FETCH INSTRUCTORES POR ÁREA — CONSULTA REAL AL BACKEND
     Llama a instructorControlador.php?listarInstructoresPorArea
     Backend usa tabla funcionarioarea para el filtro
  ============================================================ */
  function fetchInstructoresPorArea(idArea, areaNombre) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;

    // Estado de carga
    sel.innerHTML = '<option value="">⏳ Buscando instructores del área...</option>';
    sel.disabled  = true;

    const fd = new FormData();
    fd.append('listarInstructoresPorArea','ok');
    fd.append('idArea', idArea);

    fetch('controlador/instructorControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        sel.disabled = false;

        if (resp.codigo !== '200') {
          console.error('fetchInstructoresPorArea error:', resp.mensaje);
          renderInstructoresLista(todosLosInstructores);
          mostrarHint(`No se pudo filtrar el área "${areaNombre}"`, 'warn');
          return;
        }

        const delArea  = resp.instructoresDelArea || [];
        const delResto = resp.instructoresResto   || [];
        const total    = resp.totalDelArea        || 0;

        sel.innerHTML = '<option value="">— Seleccione instructor —</option>';

        // ── Grupo 1: instructores del área seleccionada ──
        if (delArea.length > 0) {
          const grp = document.createElement('optgroup');
          grp.label = `📍 ${areaNombre}  (${total})`;
          delArea.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.idFuncionario;
            // Área visible al frente del nombre
            opt.textContent = `${item.nombre}  [${item.nombreArea || areaNombre}]`;
            grp.appendChild(opt);
          });
          sel.appendChild(grp);
        }

        // ── Grupo 2: el resto de instructores ──
        if (delResto.length > 0) {
          const grpResto = document.createElement('optgroup');
          grpResto.label = '── Otros instructores ──';
          delResto.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.idFuncionario;
            const areaTxt = item.nombreArea ? `  [${item.nombreArea}]` : '';
            opt.textContent = `${item.nombre}${areaTxt}`;
            grpResto.appendChild(opt);
          });
          sel.appendChild(grpResto);
        }

        // Hint informativo debajo del select
        if (total > 0) {
          mostrarHint(
            `${total} instructor${total > 1 ? 'es' : ''} del área <strong>"${areaNombre}"</strong> aparecen primero`,
            'ok'
          );
        } else {
          mostrarHint(
            `Sin instructores registrados en <strong>"${areaNombre}"</strong> — mostrando todos`,
            'warn'
          );
        }
      })
      .catch(err => {
        console.error('fetchInstructoresPorArea:', err);
        sel.disabled = false;
        renderInstructoresLista(todosLosInstructores);
        mostrarHint('Error al consultar instructores del área', 'warn');
      });
  }

  /* ============================================================
     RENDER: lista plana desde cache (muestra área entre corchetes)
  ============================================================ */
  function renderInstructoresLista(instructores) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;
    sel.disabled  = false;
    sel.innerHTML = '<option value="">— Seleccione instructor —</option>';
    instructores.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.idFuncionario;
      // Muestra área entre corchetes si viene en la respuesta
      const areaTxt = item.nombreArea ? `  [${item.nombreArea}]` : '';
      opt.textContent = `${item.nombre}${areaTxt}`;
      sel.appendChild(opt);
    });
  }

  /* ============================================================
     RENDER: resultados de búsqueda por nombre (lista plana)
  ============================================================ */
  function renderInstructoresBusqueda(instructores) {
    const sel = document.getElementById('selectInstructorHorario');
    if (!sel) return;
    sel.innerHTML = '<option value="">— Seleccione instructor —</option>';
    if (instructores.length === 0) {
      const opt = document.createElement('option');
      opt.disabled     = true;
      opt.textContent  = '— Sin resultados —';
      sel.appendChild(opt);
      return;
    }
    instructores.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.idFuncionario;
      const areaTxt = item.nombreArea ? `  [${item.nombreArea}]` : '';
      opt.textContent = `${item.nombre}${areaTxt}`;
      sel.appendChild(opt);
    });
  }

  /* ============================================================
     HINT: mensaje debajo del selector de instructor
  ============================================================ */
  function mostrarHint(html, tipo) {
    const hint = document.getElementById('instructorAreaHint');
    if (!hint) return;
    hint.innerHTML    = html;
    hint.className    = `ph-instructor-hint${tipo === 'warn' ? ' ph-hint-warn' : ''}`;
    hint.style.display = 'flex';
  }

  function ocultarHint() {
    const hint = document.getElementById('instructorAreaHint');
    if (hint) hint.style.display = 'none';
  }

  /* ============================================================
     CARGAR SEDES
  ============================================================ */
  function cargarSedes() {
    const fd = new FormData();
    fd.append('listarSede','ok');
    fetch('controlador/sedeControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        const sel = document.getElementById('selectSedeHorario');
        if (!sel) return;
        sel.innerHTML = '<option value="">— Seleccione sede —</option>';
        (resp.listarSedes || []).forEach(item => {
          const opt = document.createElement('option');
          opt.value       = item.idSede;
          opt.textContent = item.nombre;
          sel.appendChild(opt);
        });
      })
      .catch(console.error);
  }

  /* ============================================================
     CARGAR FICHAS (cache local)
  ============================================================ */
  function cargarTodasLasFichas() {
    const fd = new FormData();
    fd.append('listarFicha','ok');
    fetch('controlador/fichaControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo === '200') todasLasFichas = resp.listarFicha || [];
      })
      .catch(console.error);
  }

  /* ============================================================
     FILTRAR FICHAS POR SEDE + JORNADA
  ============================================================ */
  function filtrarFichasPorSedeYJornada(idSede, jornada) {
    const sel = document.getElementById('selectFichaHorario');
    if (!sel) return;

    let fichas = todasLasFichas.filter(f => String(f.idSede) === String(idSede));
    if (jornada) {
      fichas = fichas.filter(f => f.jornada && f.jornada.toUpperCase() === jornada.toUpperCase());
    }

    if (fichas.length === 0) {
      sel.innerHTML = `<option value="">— Sin fichas para esta sede${jornada ? '/jornada' : ''} —</option>`;
      sel.disabled  = true;
      const inputTipo = document.getElementById('inputTipoPrograma');
      if (inputTipo) inputTipo.value = '';
      return;
    }

    sel.innerHTML = '<option value="">— Seleccione ficha —</option>';
    fichas.forEach(f => {
      const opt = document.createElement('option');
      opt.value = f.idFicha;
      opt.textContent = `${f.codigoFicha} — ${f.programa || f.programaNombre || ''}`;
      // Mapear todos los posibles nombres del campo tipo programa
      const tipoVal = f.tipoPrograma || f.tipoprograma || f.tipoFormacion || f.tipoformacion || '';
      opt.dataset.tipoprograma  = tipoVal;
      opt.dataset.tipoformacion = tipoVal;
      opt.dataset.jornada       = f.jornada || '';
      sel.appendChild(opt);
    });
    sel.disabled = false;
  }

  /* ============================================================
     CARGAR AMBIENTES POR SEDE
     Texto: código — No. número | NombreÁrea
  ============================================================ */
  function cargarAmbientesPorSede(idSede, selectId) {
    const sel = document.getElementById(selectId);
    if (!sel) return;
    sel.innerHTML = '<option value="">⏳ Cargando ambientes...</option>';
    sel.disabled  = true;

    const fd = new FormData();
    fd.append('listarAmbientesPorSede','ok');
    fd.append('idSede', idSede);

    fetch('controlador/ambienteControlador.php', { method:'POST', body:fd })
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
          // Área visible en el texto del option
          const areaTxt = amb.nombreArea ? ` | ${amb.nombreArea}` : '';
          opt.textContent    = `${amb.codigo} — No. ${amb.numero}${areaTxt}`;
          opt.dataset.area   = amb.nombreArea || '';
          opt.dataset.idarea = amb.idArea     || '';
          sel.appendChild(opt);
        });
        sel.disabled = false;
        ocultarHint();
      })
      .catch(console.error);
  }

  function cargarAmbientesPorSedeEdit(idSede, idAmbActual) {
    cargarAmbientesPorSede(idSede, 'selectAmbienteEdit');
    setTimeout(() => {
      const sel = document.getElementById('selectAmbienteEdit');
      if (sel && idAmbActual) sel.value = idAmbActual;
    }, 700);
  }

  /* ============================================================
     CARGAR DÍAS DB
  ============================================================ */
  function cargarDiasDB() {
    const fd = new FormData();
    fd.append('listarDias','ok');
    fetch('controlador/horarioControlador.php', { method:'POST', body:fd })
      .then(r => r.json())
      .then(resp => {
        if (resp.codigo !== '200') return;
        diasDB = resp.dias || [];
        diasDB.forEach(d => {
          const nombre = d.diasSemanales;
          if (DIAS_MAP[nombre] !== undefined)
            COL_DIA[DIAS_MAP[nombre]] = parseInt(d.idDia);
        });
      });
  }

  /* ============================================================
     HELPERS — CALENDARIO
  ============================================================ */
  function getDiasSeleccionadosCalendario() {
    const ids = [];
    document.querySelectorAll('.dia-header.dia-activo').forEach(th => {
      const idDia = COL_DIA[parseInt(th.dataset.dia)];
      if (idDia) ids.push(idDia);
    });
    return ids;
  }

  function getDiasEditSeleccionados() {
    const ids = [];
    document.querySelectorAll('.dia-toggle-edit:checked').forEach(cb => ids.push(parseInt(cb.value)));
    return ids;
  }

  function marcarDiasEdit(diasIds) {
    document.querySelectorAll('.dia-toggle-edit').forEach(cb => {
      cb.checked = diasIds.includes(String(cb.value)) || diasIds.includes(cb.value);
    });
  }

  /* ============================================================
     PREVIEW
  ============================================================ */
  function actualizarPreview() {
    const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
    const fichaSelect = document.getElementById('selectFichaHorario');
    const instrSelect = document.getElementById('selectInstructorHorario');

    const fichaNombre = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '—';
    const instrNombre = instrSelect?.options[instrSelect?.selectedIndex]?.text || '—';

    const previewHora = document.getElementById('previewHora');
    const previewFicha = document.getElementById('previewFicha');
    const previewInstructor = document.getElementById('previewInstructor');

    if (previewHora && horaInicio) previewHora.textContent = `${horaInicio} - ${horaFin}`;
    if (previewFicha)
      previewFicha.textContent = (fichaNombre && fichaNombre !== '— Seleccione ficha —') ? fichaNombre : '—';
    if (previewInstructor)
      previewInstructor.textContent = (instrNombre && instrNombre !== '— Seleccione instructor —') ? instrNombre : '—';

    actualizarPreviewCalendario();
  }

  function actualizarPreviewCalendario() {
    const horaInicio = document.getElementById('horaInicioHorario')?.value || '';
    const horaFin    = document.getElementById('horaFinHorario')?.value    || '';
    const fichaSelect = document.getElementById('selectFichaHorario');
    const fichaTxt   = fichaSelect?.options[fichaSelect?.selectedIndex]?.text || '';

    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');
    if (!horaInicio) return;

    document.querySelectorAll('.dia-header.dia-activo').forEach(th => {
      const dia   = parseInt(th.dataset.dia);
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
     HELPERS — FORMATOS / UTILIDADES
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
    document.querySelectorAll('.cal-cell-inner').forEach(ci => ci.innerHTML = '');

    resetSelect(document.getElementById('selectAmbienteHorario'), '— Seleccione sede primero —');
    resetSelect(document.getElementById('selectFichaHorario'),    '— Seleccione sede primero —');

    const inputTipo = document.getElementById('inputTipoPrograma');
    if (inputTipo) inputTipo.value = '';

    const buscar = document.getElementById('inputBuscarInstructor');
    if (buscar) buscar.value = '';

    renderInstructoresLista(todosLosInstructores);
    ocultarHint();
  }

});