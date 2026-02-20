<?php
/**
 * crearHorario.php
 * - Tabla listado con botón ojo → modal FullCalendar
 * - Panel crear (grid 3x2)
 * - Panel editar
 * - Modal visualización con FullCalendar
 */
?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.css' rel='stylesheet' />
<link rel="stylesheet" href="vista/css/horario.css">

<style>
/* ── Botón ojo ── */
.btn-ver {
  background: linear-gradient(135deg, #7c6bff 0%, #9d8fff 100%);
  border: none;
  color: #fff;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  cursor: pointer;
  transition: all .22s ease;
  box-shadow: 0 3px 10px rgba(124,107,255,.3);
}
.btn-ver:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(124,107,255,.45);
  background: linear-gradient(135deg, #6b5ce7 0%, #8a7aee 100%);
  color: #fff;
}

/* ── Modal ── */
#modalVerHorario .modal-dialog { max-width: 860px; }
#modalVerHorario .modal-content {
  border: none;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 24px 64px rgba(124,107,255,.2);
}
#modalVerHorario .modal-header {
  background: linear-gradient(135deg, #7c6bff 0%, #9d8fff 100%);
  border: none;
  padding: 18px 24px;
}
#modalVerHorario .modal-title {
  color: #fff;
  font-weight: 700;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
}
#modalVerHorario .btn-close { filter: brightness(0) invert(1); opacity: .85; }

/* ── Info chips ── */
.cal-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
  gap: 10px;
  margin-bottom: 20px;
}
.cal-info-chip {
  background: #f5f3ff;
  border: 1px solid #ddd6fe;
  border-radius: 10px;
  padding: 10px 14px;
}
.cal-info-chip .chip-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: #8b7bef;
  margin-bottom: 3px;
}
.cal-info-chip .chip-value {
  font-size: 13px;
  font-weight: 600;
  color: #1e1b4b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* ── FullCalendar wrapper ── */
#horarioCalendar {
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e8e4ff;
}
#horarioCalendar .fc-toolbar-title {
  font-size: 15px !important;
  font-weight: 700 !important;
  color: #1e1b4b !important;
}
#horarioCalendar .fc-button-primary {
  background: linear-gradient(135deg, #7c6bff, #9d8fff) !important;
  border: none !important;
  border-radius: 8px !important;
  font-size: 12px !important;
  font-weight: 600 !important;
  box-shadow: 0 2px 8px rgba(124,107,255,.25) !important;
  transition: all .2s !important;
}
#horarioCalendar .fc-button-primary:hover {
  background: linear-gradient(135deg, #6b5ce7, #8a7aee) !important;
  transform: translateY(-1px) !important;
}
#horarioCalendar .fc-button-primary:not(:disabled).fc-button-active {
  background: #5b4fcc !important;
}
#horarioCalendar .fc-col-header-cell {
  background: #f5f3ff;
  font-size: 11px;
  font-weight: 700;
  color: #6b5ce7;
  text-transform: uppercase;
  letter-spacing: .4px;
}
#horarioCalendar .fc-daygrid-event {
  border-radius: 6px !important;
  border: none !important;
  padding: 2px 6px !important;
  font-size: 11px !important;
  font-weight: 600 !important;
}
#horarioCalendar .fc-day-today { background: #faf8ff !important; }
#horarioCalendar .fc-day-today .fc-daygrid-day-number {
  background: #7c6bff;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 11px;
}

/* ── Modal footer ── */
#modalVerHorario .modal-footer { border-top: 1px solid #ede9ff; padding: 14px 20px; }
.btn-cerrar-modal {
  background: #fff;
  border: 2px solid rgba(124,107,255,.4);
  color: #7c6bff;
  font-weight: 600;
  border-radius: 10px;
  padding: 8px 22px;
  cursor: pointer;
  transition: all .2s;
}
.btn-cerrar-modal:hover { background: #f5f3ff; border-color: #7c6bff; }

/* ── Paleta morada botones forms ── */
#formCrearHorario .btn-primary,
#formEditarHorario .btn-primary,
.ph-actions .btn-primary {
  background: linear-gradient(135deg, #7c6bff 0%, #9d8fff 100%) !important;
  border: none !important;
  color: #fff !important;
  font-weight: 600;
  border-radius: 10px;
  box-shadow: 0 6px 18px rgba(124,107,255,.28);
  transition: all .25s;
}
#formCrearHorario .btn-primary:hover, .ph-actions .btn-primary:hover {
  background: linear-gradient(135deg, #6b5ce7 0%, #8a7aee 100%) !important;
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(124,107,255,.38);
}
#formCrearHorario .btn-secondary, .ph-actions .btn-secondary {
  background: #fff !important;
  border: 2px solid rgba(124,107,255,.4) !important;
  color: #7c6bff !important;
  font-weight: 600;
  border-radius: 10px;
}
#formCrearHorario .btn-secondary:hover, .ph-actions .btn-secondary:hover {
  background: #f5f3ff !important;
}

/* ── Hint instructor ── */
.ph-instructor-hint {
  display: none; align-items: center; gap: 7px;
  font-size: 11.5px; font-weight: 500; padding: 6px 12px;
  border-radius: 8px; margin-bottom: 6px;
  background: #f0eeff; border: 1px solid #c4b5fd; color: #5b3fd8;
}
.ph-instructor-hint.ph-hint-warn { background:#fffbeb; border-color:#fcd34d; color:#92400e; }
.ph-instructor-search { margin-bottom: 6px; }
.ph-search-input {
  width: 100%; font-size: 12.5px !important; padding: 7px 12px !important;
  border-radius: 8px; border: 1px solid #e5e7eb !important;
  transition: border-color .2s, box-shadow .2s;
}
.ph-search-input:focus {
  border-color: #7c6bff !important;
  box-shadow: 0 0 0 3px rgba(124,107,255,.12) !important;
  outline: none;
}
</style>

<div class="horario-wrap">

  <!-- ══════════════════════════════════════════════════
       PANEL: TABLA LISTADO
  ══════════════════════════════════════════════════ -->
  <div id="panelTablaHorario">

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

    <div class="ph-table-card">
      <table id="tablaHorarios" class="w-100">
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

        <div class="ph-grid">

          <!-- SEDE -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-building"></i> SEDE</div>
            <select id="selectSedeHorario" class="ph-sel" required>
              <option value="">— Seleccione sede —</option>
            </select>
          </div>

          <!-- FICHA -->
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
                </select>
              </div>
              <div class="ph-ficha-col ph-ficha-grow">
                <span class="ph-sublabel">CÓDIGO FICHA</span>
                <select id="selectFichaHorario" class="ph-sel" disabled required>
                  <option value="">— Seleccione sede primero —</option>
                </select>
              </div>
            </div>
          </div>

          <!-- HORA -->
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

          <!-- AMBIENTE -->
          <div class="ph-block">
            <div class="ph-block-label"><i class="bi bi-door-open"></i> AMBIENTE</div>
            <select id="selectAmbienteHorario" class="ph-sel" disabled required>
              <option value="">— Seleccione sede primero —</option>
            </select>
          </div>

          <!-- INSTRUCTOR -->
          <div class="ph-block ph-block-ficha">
            <div class="ph-block-label"><i class="bi bi-person-badge"></i> INSTRUCTOR</div>
            <div id="instructorAreaHint" class="ph-instructor-hint"></div>
            <div class="ph-instructor-search">
              <input type="text" id="inputBuscarInstructor"
                class="ph-sel ph-search-input"
                placeholder="Buscar instructor por nombre...">
            </div>
            <select id="selectInstructorHorario" class="ph-sel" required>
              <option value="">— Seleccione instructor —</option>
            </select>
          </div>

          <!-- FECHA -->
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

        <!-- CALENDARIO SEMANAL -->
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
                  $abrev = ['Lun','Mar','Mié','Jue','Vie','Sáb'];
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

        <div class="ph-actions">
          <button type="button" id="btnCancelarHorario" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Guardar Horario
          </button>
        </div>

      </form>
    </div>
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


<!-- ════════════════════════════════════════════════════════════
     MODAL: VER HORARIO EN FULLCALENDAR
════════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalVerHorario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-calendar3"></i> Vista de Horario en Calendario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4">

        <!-- Chips de información -->
        <div class="cal-info-grid">
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-file-earmark-text me-1"></i>Ficha</div>
            <div class="chip-value" id="calModal_ficha">—</div>
          </div>
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-person-badge me-1"></i>Instructor</div>
            <div class="chip-value" id="calModal_instructor">—</div>
          </div>
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-building me-1"></i>Sede</div>
            <div class="chip-value" id="calModal_sede">—</div>
          </div>
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-diagram-3 me-1"></i>Área</div>
            <div class="chip-value" id="calModal_area">—</div>
          </div>
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-clock me-1"></i>Horario</div>
            <div class="chip-value" id="calModal_hora">—</div>
          </div>
          <div class="cal-info-chip">
            <div class="chip-label"><i class="bi bi-calendar-range me-1"></i>Vigencia</div>
            <div class="chip-value" id="calModal_fechas">—</div>
          </div>
        </div>

        <!-- FullCalendar se renderiza aquí -->
        <div id="horarioCalendar"></div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cerrar-modal" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i> Cerrar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- FullCalendar scripts (core + daygrid + timegrid) -->
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.20/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.20/index.global.min.js'></script>

<script src="vista/js/cl_ambiente.js"></script>
<script src="vista/js/horario.js"></script>