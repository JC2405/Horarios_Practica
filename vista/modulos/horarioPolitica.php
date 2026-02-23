<!-- FullCalendar CSS -->
  <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="vista/css/styles.css">


  <div class="horario-wrap">

    <!-- ══════════════════════════════════════════════════
        PANEL: TABLA LISTADO
    ══════════════════════════════════════════════════ -->
    <div id="panelTablaHorarioPolitica">

        <div class="ph-header">
        <div class="ph-header-left">
          <div class="ph-icon"><i class="bi bi-calendar-week-fill"></i></div>
          <div>
            <h1 class="ph-title">Gestión de Transversales</h1>
            <p class="ph-subtitle">Administra los horarios de formación por ficha/Sede</p>
          </div>
        </div>
      </div>

      <div class="ph-table-card">
        <table id="tablaHorarioPolitica" class="w-100">
          <thead>
            <tr>
                 <th><i class="bi bi-building me-1"></i>Sede</th>
            <th><i class="bi bi-diagram-3 me-1"></i>Área</th>
            <th><i class="bi bi-file-earmark-text me-1"></i>Ficha / Programa</th>
            <th><i class="bi bi-sun me-1"></i>Jornada</th>
            <th><i class="bi bi-mortarboard me-1"></i>Tipo Programa</th>
            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbodyHorarios"></tbody>
        </table>
      </div>

    </div><!-- /panelTablaHorario -->






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
          <div id="calLeyendaInstructores"></div>  

        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cerrar-modal" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i> Cerrar
          </button>
        </div>

      </div>
    </div>
   </div>
  </div>
