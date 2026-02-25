

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


            <!-- AMBIENTE -->
            <div class="ph-block">
              <div class="ph-block-label"><i class="bi bi-door-open"></i> AMBIENTE</div>
              <select id="selectAmbienteHorario" class="ph-sel" disabled required>
                <option value="">— Seleccione sede primero —</option>
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
                    <th class="dia-header" data-dia="<?= $i+1 ?>" onclick="toggleDia(this)">
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

  <!-- FullCalendar scripts (core + daygrid + timegrid) -->
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.20/index.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.20/index.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.20/index.global.min.js'></script>

  