<!-- FullCalendar CSS -->
  <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="vista/css/styles.css">


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
            <th><i class="bi bi-file-earmark-text me-1"></i>Ficha / Programa</th>
            <th><i class="bi bi-sun me-1"></i>Jornada</th>
            <th><i class="bi bi-mortarboard me-1"></i>Tipo Programa</th>
            <th><i class="bi bi-door-open me-1"></i>Ambiente</th>
            <th><i class="bi bi-clock-history me-1"></i>Horarios</th>
            <th><i class="bi bi-gear-fill me-1"></i>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbodyHorarios"></tbody>
        </table>
      </div>

    </div>
  </div>
