<?php
// vista/modulos/crearHorario.php
?>

<link href="vista/css/styles.css" rel="stylesheet">
<link href="vista/css/tablaCompacta.css" rel="stylesheet">
<link href="vista/css/horario.css" rel="stylesheet">

<div class="horario-wrap">

  <!-- ============================================================
       PANEL PRINCIPAL - TABLA DE HORARIOS CREADOS
  ============================================================ -->
  <div id="panelTablaHorario">

    <div class="header-section">
      <div class="header-content">
        <div class="title-wrapper">
          <div class="title-icon">
            <i class="bi bi-calendar3-week"></i>
          </div>
          <div>
            <h2 class="section-title">Horarios</h2>
            <p class="section-subtitle">Gestiona los horarios de clase</p>
          </div>
        </div>
      </div>
      <button id="btnNuevoHorario" class="btn-add" type="button">
        <i class="bi bi-plus-lg"></i>
        Nuevo Horario
      </button>
    </div>

    <!-- TABLA DE HORARIOS REGISTRADOS -->
    <div class="table-wrapper">
      <table id="tablaHorarios" class="ultra-modern-table">
        <thead>
          <tr>
            <th><div class="th-wrap"><i class="bi bi-geo-alt"></i><span>Sede</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-door-open"></i><span>Cód. Ambiente</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-diagram-3"></i><span>Área</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-sun"></i><span>Jornada</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-file-earmark-text"></i><span>Cód. Ficha</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-mortarboard"></i><span>Tipo Programa</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-person-badge"></i><span>Instructor</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-clock"></i><span>Hora Inicio</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-clock-history"></i><span>Hora Fin</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-calendar-event"></i><span>F. Inicio</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-calendar-x"></i><span>F. Fin</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-calendar-week"></i><span>Días</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-sliders"></i><span>Acciones</span></div></th>
          </tr>
        </thead>
        <tbody id="tbodyHorarios">
          <tr>
            <td colspan="13" class="text-center py-4 text-muted" style="font-size:13px;">
              <i class="bi bi-calendar3" style="font-size:1.8rem;display:block;margin-bottom:8px;opacity:.3"></i>
              Cargando horarios...
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div><!-- /panelTablaHorario -->


  <!-- ============================================================
       PANEL CREAR HORARIO - Layout según mockup
  ============================================================ -->
  <div id="panelFormularioHorario" style="display:none;">

    <div class="form-card">

      <!-- Header -->
      <div class="form-card-header">
        <button id="btnRegresarTablaHorario" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-calendar-plus"></i>
          </div>
          <div>
            <h2 class="form-title-text">Crear Horario</h2>
            <p class="form-subtitle-text">Configura los datos del nuevo horario</p>
          </div>
        </div>
      </div>

      <form id="formCrearHorario" novalidate>

        <!-- =====================================================
             FILA SUPERIOR DE FILTROS / SELECTORES
             Orden: SEDE | AMBIENTE(Codi+Area) | FICHA(Jornada+CodFicha+TipProgra) | INSTRUCTORES | HORA INICIO/FIN | FECHA INICIO/FIN
        ====================================================== -->
        <div class="selector-bar">

          <!-- 1. SEDE -->
          <div class="selector-group selector-sede">
            <div class="selector-label">
              <i class="bi bi-geo-alt-fill"></i> SEDE
            </div>
            <div class="selector-inner">
              <select id="selectSedeHorario" class="sel-input sel-full" required>
                <option value="">Nombre / Ciudad</option>
              </select>
            </div>
          </div>

          <!-- 2. AMBIENTE (Codi + Area) -->
          <div class="selector-group selector-ambiente">
            <div class="selector-label">
              <i class="bi bi-door-open"></i> AMBIENTE
            </div>
            <div class="selector-inner sel-row">
              <div class="sel-col">
                <span class="sel-sublabel">CODI</span>
                <select id="selectAmbienteHorario" class="sel-input" required>
                  <option value="">—</option>
                </select>
              </div>
              <div class="sel-col">
                <span class="sel-sublabel">ÁREA</span>
                <select id="selectAreaAmbiente" class="sel-input" disabled>
                  <option value="">—</option>
                </select>
              </div>
            </div>
          </div>

          <!-- 3. FICHA (Jornada + CodFicha + TipPrograma) -->
          <div class="selector-group selector-ficha">
            <div class="selector-label">
              <i class="bi bi-journals"></i> FICHA
            </div>
            <div class="selector-inner sel-row">
              <div class="sel-col">
                <span class="sel-sublabel">JORNADA</span>
                <select id="selectJornadaHorario" class="sel-input" required>
                  <option value="">—</option>
                  <option value="MAÑANA">Mañana</option>
                  <option value="TARDE">Tarde</option>
                  <option value="NOCHE">Noche</option>
                </select>
              </div>
              <div class="sel-col">
                <span class="sel-sublabel">COD.FICHA</span>
                <select id="selectFichaHorario" class="sel-input" required>
                  <option value="">—</option>
                </select>
              </div>
              <div class="sel-col">
                <span class="sel-sublabel">TIP.PROGRA</span>
                <input id="inputTipoPrograma" class="sel-input" type="text" readonly placeholder="—">
              </div>
            </div>
          </div>

          <!-- 4. INSTRUCTORES -->
          <div class="selector-group selector-instructor">
            <div class="selector-label">
              <i class="bi bi-person-badge"></i> INSTRUCTORES
            </div>
            <div class="selector-inner">
              <select id="selectInstructorHorario" class="sel-input sel-full" required>
                <option value="">Nombre / Área</option>
              </select>
              <div class="instructor-hint">
                <span>IGUAL ÁREA</span>
                <span>AMBIENTE</span>
              </div>
            </div>
          </div>

          <!-- 5. HORA INICIO / FIN -->
          <div class="selector-group selector-horas">
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-clock"></i> HORA INICIO</div>
              <input type="time" id="horaInicioHorario" class="sel-input hora-input" required>
            </div>
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-clock-history"></i> HORA FIN</div>
              <input type="time" id="horaFinHorario" class="sel-input hora-input" required>
            </div>
          </div>

          <!-- 6. FECHA INICIO / FIN -->
          <div class="selector-group selector-fechas">
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-calendar-event"></i> FECHA INICIO</div>
              <input type="date" id="fechaInicioHorario" class="sel-input hora-input">
            </div>
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-calendar-x"></i> FECHA FIN</div>
              <input type="date" id="fechaFinHorario" class="sel-input hora-input">
            </div>
          </div>

        </div><!-- /selector-bar -->


        <!-- =====================================================
             CALENDARIO SEMANAL
        ====================================================== -->
        <div class="calendario-wrapper">
          <div class="calendario-header-bar">
            <span class="cal-title"><i class="bi bi-calendar3-week"></i> Días de clase — selecciona los días en los que aplica este horario</span>
            <span class="cal-hint">Haz clic en una celda para marcarla</span>
          </div>

          <div class="calendario-table-wrap">
            <table class="calendario-table" id="calendarioSemanal">
              <thead>
                <tr>
                  <th data-dia="1" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Lunes</span>
                      <span class="dia-abrev">LUN</span>
                      <input type="checkbox" class="dia-toggle" data-dia="1" id="chkLunes">
                    </div>
                  </th>
                  <th data-dia="2" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Martes</span>
                      <span class="dia-abrev">MAR</span>
                      <input type="checkbox" class="dia-toggle" data-dia="2" id="chkMartes">
                    </div>
                  </th>
                  <th data-dia="3" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Miércoles</span>
                      <span class="dia-abrev">MIÉ</span>
                      <input type="checkbox" class="dia-toggle" data-dia="3" id="chkMiercoles">
                    </div>
                  </th>
                  <th data-dia="4" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Jueves</span>
                      <span class="dia-abrev">JUE</span>
                      <input type="checkbox" class="dia-toggle" data-dia="4" id="chkJueves">
                    </div>
                  </th>
                  <th data-dia="5" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Viernes</span>
                      <span class="dia-abrev">VIE</span>
                      <input type="checkbox" class="dia-toggle" data-dia="5" id="chkViernes">
                    </div>
                  </th>
                  <th data-dia="6" class="dia-header">
                    <div class="dia-header-inner">
                      <span class="dia-nombre">Sábado</span>
                      <span class="dia-abrev">SAB</span>
                      <input type="checkbox" class="dia-toggle" data-dia="6" id="chkSabado">
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="cal-preview-row">
                  <td class="cal-cell" data-dia="1"><div class="cal-cell-inner" id="preview-lunes"></div></td>
                  <td class="cal-cell" data-dia="2"><div class="cal-cell-inner" id="preview-martes"></div></td>
                  <td class="cal-cell" data-dia="3"><div class="cal-cell-inner" id="preview-miercoles"></div></td>
                  <td class="cal-cell" data-dia="4"><div class="cal-cell-inner" id="preview-jueves"></div></td>
                  <td class="cal-cell" data-dia="5"><div class="cal-cell-inner" id="preview-viernes"></div></td>
                  <td class="cal-cell" data-dia="6"><div class="cal-cell-inner" id="preview-sabado"></div></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Preview del horario en el calendario -->
          <div class="horario-preview-card" id="horarioPreviewCard" style="display:none;">
            <div class="preview-badge"><i class="bi bi-eye"></i> Vista previa del horario</div>
            <div class="preview-info" id="previewInfo"></div>
          </div>
        </div><!-- /calendario-wrapper -->


        <!-- BOTONES ACCIÓN -->
        <div class="form-actions">
          <button type="button" id="btnCancelarHorario" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary" id="btnGuardarHorario">
            <i class="bi bi-check-lg"></i> Guardar Horario
          </button>
        </div>

      </form>
    </div>
  </div><!-- /panelFormularioHorario -->


  <!-- ============================================================
       PANEL EDITAR HORARIO
  ============================================================ -->
  <div id="panelEditarHorario" style="display:none;">
    <div class="form-card">

      <div class="form-card-header">
        <button id="btnRegresarTablaHorarioEdit" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon"><i class="bi bi-calendar-check"></i></div>
          <div>
            <h2 class="form-title-text">Editar Horario</h2>
            <p class="form-subtitle-text">Modifica el horario seleccionado</p>
          </div>
        </div>
      </div>

      <form id="formEditarHorario" novalidate>
        <input type="hidden" id="idHorarioEdit">

        <div class="selector-bar">

          <!-- AMBIENTE -->
          <div class="selector-group selector-ambiente">
            <div class="selector-label"><i class="bi bi-door-open"></i> AMBIENTE</div>
            <div class="selector-inner">
              <select id="selectAmbienteEdit" class="sel-input sel-full" required>
                <option value="">Seleccione...</option>
              </select>
            </div>
          </div>

          <!-- HORAS -->
          <div class="selector-group selector-horas">
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-clock"></i> HORA INICIO</div>
              <input type="time" id="horaInicioEdit" class="sel-input hora-input" required>
            </div>
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-clock-history"></i> HORA FIN</div>
              <input type="time" id="horaFinEdit" class="sel-input hora-input" required>
            </div>
          </div>

          <!-- FECHAS -->
          <div class="selector-group selector-fechas">
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-calendar-event"></i> FECHA INICIO</div>
              <input type="date" id="fechaInicioEdit" class="sel-input hora-input">
            </div>
            <div class="hora-field">
              <div class="selector-label"><i class="bi bi-calendar-x"></i> FECHA FIN</div>
              <input type="date" id="fechaFinEdit" class="sel-input hora-input">
            </div>
          </div>
        </div>

        <!-- Días edit -->
        <div class="calendario-wrapper" style="margin-top:16px;">
          <div class="calendario-table-wrap">
            <table class="calendario-table">
              <thead>
                <tr>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Lunes</span><input type="checkbox" class="dia-toggle-edit" data-dia="1"></div></th>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Martes</span><input type="checkbox" class="dia-toggle-edit" data-dia="2"></div></th>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Miércoles</span><input type="checkbox" class="dia-toggle-edit" data-dia="3"></div></th>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Jueves</span><input type="checkbox" class="dia-toggle-edit" data-dia="4"></div></th>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Viernes</span><input type="checkbox" class="dia-toggle-edit" data-dia="5"></div></th>
                  <th class="dia-header"><div class="dia-header-inner"><span class="dia-nombre">Sábado</span><input type="checkbox" class="dia-toggle-edit" data-dia="6"></div></th>
                </tr>
              </thead>
              <tbody>
                <tr class="cal-preview-row">
                  <td class="cal-cell" data-dia="1"><div class="cal-cell-inner"></div></td>
                  <td class="cal-cell" data-dia="2"><div class="cal-cell-inner"></div></td>
                  <td class="cal-cell" data-dia="3"><div class="cal-cell-inner"></div></td>
                  <td class="cal-cell" data-dia="4"><div class="cal-cell-inner"></div></td>
                  <td class="cal-cell" data-dia="5"><div class="cal-cell-inner"></div></td>
                  <td class="cal-cell" data-dia="6"><div class="cal-cell-inner"></div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" id="btnCancelarEditarHorario" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Guardar Cambios
          </button>
        </div>
      </form>
    </div>
  </div>

</div><!-- /horario-wrap -->

<script src="vista/js/horario.js"></script>