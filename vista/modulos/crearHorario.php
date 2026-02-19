<?php
/**
 * crearHorario.php
 *
 * Layout 3×2 según imagen:
 *  Fila 1: [Sede]      [Ficha (Jornada · Código · Tipo Prog)]   [Hora inicio/fin]
 *  Fila 2: [Ambiente]  [Instructor]                              [Fecha inicio/fin]
 *
 * - Ambiente: SIN selector de área (se eliminó)
 * - Headers de listar y crear con diseño mejorado
 */
?>
<link rel="stylesheet" href="vista/css/horario.css">

<div class="horario-wrap">

  <!-- ══════════════════════════════════════════════════
       PANEL: TABLA LISTADO
  ══════════════════════════════════════════════════ -->
  <div id="panelTablaHorario">

    <!-- HEADER LISTADO -->
    <div class="ph-header">
      <div class="ph-header-left">
        <div class="ph-icon"><i class="bi bi-calendar-week-fill"></i></div>
        <div>
          <h1 class="ph-title">Gestión de Horarios</h1>
          <p class="ph-subtitle">Administra los horarios de formación por sede</p>
        </div>
      </div>
      <button id="btnNuevoHorario" class="ph-btn-add">
        <i class="bi bi-plus-circle-fill"></i> Nuevo Horario
      </button>
    </div>

    <!-- TABLA 7 columnas: Sede | Área | Ficha | Jornada | Tipo Programa | Instructor | Acciones -->
    <div class="ph-table-card">
      <table id="tablaHorarios" class="table table-hover w-100">
        <thead>
          <tr>
            <th><i class="bi bi-building me-1"></i>Sede</th>
            <th><i class="bi bi-diagram-3 me-1"></i>Área</th>
            <th><i class="bi bi-file-earmark-text me-1"></i>Ficha</th>
            <th><i class="bi bi-sun me-1"></i>Jornada</th>
            <th><i class="bi bi-mortarboard me-1"></i>Tipo Programa</th>
            <th><i class="bi bi-person-fill me-1"></i>Instructor</th>
            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbodyHorarios"></tbody>
      </table>
    </div>

  </div><!-- /panelTablaHorario -->


  <!-- ══════════════════════════════════════════════════
       PANEL: CREAR HORARIO
  ══════════════════════════════════════════════════ -->
  <div id="panelFormularioHorario" style="display:none;">

    <!-- HEADER CREAR -->
    <div class="ph-crear-header">
      <button type="button" id="btnRegresarTablaHorario" class="ph-btn-back">
        <i class="bi bi-arrow-left"></i> Regresar
      </button>
      <div class="ph-crear-title">
        <div class="ph-icon ph-icon-sm"><i class="bi bi-calendar-plus-fill"></i></div>
        <div>
          <h2 class="ph-title">Nuevo Horario</h2>
          <p class="ph-subtitle">Configura los datos del horario y selecciona los días</p>
        </div>
      </div>
    </div>

    <div class="ph-form-card">
      <form id="formCrearHorario" novalidate>

        <!-- ═══════════ GRID 3×2 ═══════════ -->
        <div class="ph-grid">

          <!-- ── FILA 1 ── -->

          <!-- 1/A  SEDE -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-building"></i> SEDE</div>
            <select id="selectSedeHorario" class="ph-sel" required>
              <option value="">— Seleccione sede —</option>
            </select>
          </div>

          <!-- 1/B  FICHA (span 1 columna extra en el grid para que sea más ancha) -->
          <div class="ph-block ph-block-ficha">
            <div class="ph-block-label"><i class="bi bi-file-earmark-person"></i> FICHA</div>
            <div class="ph-ficha-row">
              <div class="ph-ficha-col">
                <span class="ph-sublabel">JORNADA</span>
                <select id="selectJornadaHorario" class="ph-sel" required>
                  <option value="">— Jornada —</option>
                  <option value="MAÑANA">🌅 Mañana</option>
                  <option value="TARDE">☀️ Tarde</option>
                  <option value="NOCHE">🌙 Noche</option>
                  <option value="SABADO">📅 Sábado</option>
                </select>
              </div>
              <div class="ph-ficha-col ph-ficha-grow">
                <span class="ph-sublabel">CÓDIGO FICHA</span>
                <select id="selectFichaHorario" class="ph-sel" disabled required>
                  <option value="">— Seleccione sede primero —</option>
                </select>
              </div>
              <div class="ph-ficha-col">
                <span class="ph-sublabel">TIPO PROGRAMA</span>
                <input id="inputTipoPrograma" class="ph-sel" readonly placeholder="Auto">
              </div>
            </div>
          </div>

          <!-- 1/C  HORA INICIO / FIN -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-clock"></i> HORA INICIO / FIN</div>
            <div class="ph-stack">
              <div class="ph-stack-item">
                <span class="ph-sublabel">INICIO</span>
                <input type="time" id="horaInicioHorario" class="ph-sel" required>
              </div>
              <div class="ph-stack-item">
                <span class="ph-sublabel">FIN</span>
                <input type="time" id="horaFinHorario" class="ph-sel" required>
              </div>
            </div>
          </div>

          <!-- ── FILA 2 ── -->

          <!-- 2/A  AMBIENTE (SIN área) -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-door-open"></i> AMBIENTE</div>
            <select id="selectAmbienteHorario" class="ph-sel" disabled required>
              <option value="">— Seleccione sede primero —</option>
            </select>
          </div>

          <!-- 2/B  INSTRUCTOR -->
          <div class="ph-block ph-block-ficha">
            <div class="ph-block-label"><i class="bi bi-person-badge"></i> INSTRUCTOR</div>
            <select id="selectInstructorHorario" class="ph-sel" required>
              <option value="">— Seleccione instructor —</option>
            </select>
          </div>

          <!-- 2/C  FECHA INICIO / FIN -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-calendar-range"></i> FECHA INICIO / FIN</div>
            <div class="ph-stack">
              <div class="ph-stack-item">
                <span class="ph-sublabel">INICIO</span>
                <input type="date" id="fechaInicioHorario" class="ph-sel">
              </div>
              <div class="ph-stack-item">
                <span class="ph-sublabel">FIN</span>
                <input type="date" id="fechaFinHorario" class="ph-sel">
              </div>
            </div>
          </div>

        </div><!-- /ph-grid -->


        <!-- ═══════════ CALENDARIO SEMANAL ═══════════ -->
        <div class="ph-calendario">
          <div class="ph-cal-header">
            <span class="ph-cal-title"><i class="bi bi-grid-3x2-gap"></i> Días de la semana</span>
            <span class="ph-cal-hint">Haz clic en un día para activarlo</span>
          </div>
          <div class="ph-cal-scroll">
            <table class="ph-cal-table">
              <thead>
                <tr>
                  <?php
                  $dias  = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                  $abrev = ['Lun',  'Mar',   'Mié',      'Jue',   'Vie',    'Sáb'];
                  foreach ($dias as $i => $dia): ?>
                  <th class="dia-header" data-dia="<?= $i+1 ?>">
                    <div class="dia-header-inner">
                      <span class="dia-nombre"><?= $dia ?></span>
                      <span class="dia-abrev"><?= $abrev[$i] ?></span>
                    </div>
                  </th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php for ($i = 1; $i <= 6; $i++): ?>
                  <td class="cal-cell" data-dia="<?= $i ?>">
                    <div class="cal-cell-inner" data-dia="<?= $i ?>"></div>
                  </td>
                  <?php endfor; ?>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="ph-preview">
            <div class="ph-preview-label"><i class="bi bi-eye"></i> Vista previa</div>
            <div class="ph-preview-chips">
              <span class="ph-chip"><i class="bi bi-clock"></i> <span id="previewHora">—</span></span>
              <span class="ph-chip"><i class="bi bi-file-text"></i> <span id="previewFicha">—</span></span>
              <span class="ph-chip"><i class="bi bi-person"></i> <span id="previewInstructor">—</span></span>
            </div>
          </div>
        </div>

        <!-- Acciones -->
        <div class="ph-actions">
          <button type="button" id="btnCancelarHorario" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Guardar Horario
          </button>
        </div>

      </form>
    </div><!-- /ph-form-card -->
  </div><!-- /panelFormularioHorario -->


  <!-- ══════════════════════════════════════════════════
       PANEL: EDITAR HORARIO
  ══════════════════════════════════════════════════ -->
  <div id="panelEditarHorario" style="display:none;">

    <div class="ph-crear-header">
      <button type="button" id="btnRegresarTablaHorarioEdit" class="ph-btn-back">
        <i class="bi bi-arrow-left"></i> Regresar
      </button>
      <div class="ph-crear-title">
        <div class="ph-icon ph-icon-sm"><i class="bi bi-calendar-check-fill"></i></div>
        <div>
          <h2 class="ph-title">Editar Horario</h2>
          <p class="ph-subtitle">Modifica los datos del horario seleccionado</p>
        </div>
      </div>
    </div>

    <div class="ph-form-card">
      <form id="formEditarHorario" novalidate>
        <input type="hidden" id="idHorarioEdit">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Ambiente</label>
            <select id="selectAmbienteEdit" class="form-control" required>
              <option value="">— Seleccione —</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Hora inicio</label>
            <input type="time" id="horaInicioEdit" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Hora fin</label>
            <input type="time" id="horaFinEdit" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Fecha inicio</label>
            <input type="date" id="fechaInicioEdit" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold">Fecha fin</label>
            <input type="date" id="fechaFinEdit" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Días de clase</label>
            <div class="ph-dias-group">
              <?php foreach ([
                ['Lunes',1],['Martes',2],['Miércoles',3],
                ['Jueves',4],['Viernes',5],['Sábado',6]
              ] as [$label, $id]): ?>
              <label class="ph-dia-chip">
                <input type="checkbox" class="dia-toggle-edit" value="<?= $id ?>">
                <span><?= $label ?></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="ph-actions">
          <button type="button" id="btnCancelarEditarHorario" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Actualizar
          </button>
        </div>
      </form>
    </div>

  </div><!-- /panelEditarHorario -->

</div><!-- /horario-wrap -->

<script src="vista/js/cl_ambiente.js"></script>
<script src="vista/js/horario.js"></script>