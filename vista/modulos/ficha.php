<!-- CSS Consolidado -->
<!--<link href="vista/css/styles.css" rel="stylesheet">-->
<link href="vista/css/crearFicha.css" rel="stylesheet">



<div class="container">

  <!-- ========================= -->
  <!-- PANEL TABLA FICHAS -->
  <!-- ========================= -->
  <div id="panelTablaFichas">

    <div class="header-section">
      <div class="header-content">
        <div class="title-wrapper">
          <div class="title-icon">
            <i class="bi bi-journal-text"></i>
          </div>
          <div>
            <h2 class="section-title">Fichas</h2>
            <p class="section-subtitle">Administra las fichas registradas</p>
          </div>
        </div>
      </div>

      <button id="btnCrearFicha" class="btn-add" type="button">
        <span class="btn-glow"></span>
        <i class="bi bi-plus-lg"></i>
        <span>Nueva Ficha</span>
      </button>
    </div>

    <div class="table-wrapper">
      <table id="tablaFichas" class="ultra-modern-table">
        <thead>
          <tr>
            <th>
                <div class="th-wrap">
                    <i class="bi bi-hash"></i>
                    <span>Código</span>
                </div>
            </th>
            <th><div class="th-wrap"><i class="bi bi-book"></i><span>Programa</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-door-open"></i><span>Ambiente</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-clock"></i><span>Jornada</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-circle-fill"></i><span>Estado</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-calendar-event"></i><span>Inicio</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-calendar-check"></i><span>Fin</span></div></th>
            <th><div class="th-wrap"><i class="bi bi-sliders"></i><span>Acciones</span></div></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

  </div>




<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            
            <!-- Header con Steps Visuales -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-primary fw-bold">
                        <i class="bi bi-journal-plus me-2"></i>
                        Crear Nueva Ficha
                    </h2>
                    
                    <!-- Progress Steps -->
                    <div class="steps-container">
                        <div class="step active" id="step1">
                            <div class="step-icon">
                                <i class="bi bi-1-circle-fill"></i>
                            </div>
                            <div class="step-label">Información Básica</div>
                        </div>
                        
                        <div class="step-line"></div>
                        
                        <div class="step" id="step2">
                            <div class="step-icon">
                                <i class="bi bi-2-circle"></i>
                            </div>
                            <div class="step-label">Ubicación</div>
                        </div>
                        
                        <div class="step-line"></div>
                        
                        <div class="step" id="step3">
                            <div class="step-icon">
                                <i class="bi bi-3-circle"></i>
                            </div>
                            <div class="step-label">Programa</div>
                        </div>
                        
                        <div class="step-line"></div>
                        
                        <div class="step" id="step4">
                            <div class="step-icon">
                                <i class="bi bi-4-circle"></i>
                            </div>
                            <div class="step-label">Fechas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario Principal -->
            <form id="formCrearFicha">
                <div class="row g-4">
                    
                    <!-- Panel Izquierdo: Campos del Formulario -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                
                                <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
                                <div class="form-section active" id="section1">
                                    <h5 class="section-title">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Información Básica
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="codigo" class="form-label">
                                                <i class="bi bi-hash me-1"></i>
                                                Código de la Ficha
                                            </label>
                                            <input 
                                                type="text" 
                                                class="form-control form-control-lg" 
                                                id="codigo" 
                                                placeholder="Ej: 2866432" 
                                                required
                                            >
                                            <div class="form-text">Código único de identificación</div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="jornada" class="form-label">
                                                <i class="bi bi-clock me-1"></i>
                                                Jornada
                                            </label>
                                            <select class="form-select form-select-lg" id="jornada" required>
                                                <option value="">Seleccionar jornada...</option>
                                                <option value="MAÑANA">🌅 Mañana (6:00 AM - 12:00 PM)</option>
                                                <option value="TARDE">☀️ Tarde (12:00 PM - 6:00 PM)</option>
                                                <option value="NOCHE">🌙 Noche (6:00 PM - 10:00 PM)</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary btn-lg" onclick="nextSection(2)">
                                            Siguiente
                                            <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- SECCIÓN 2: UBICACIÓN -->
                                <div class="form-section" id="section2">
                                    <h5 class="section-title">
                                        <i class="bi bi-geo-alt me-2"></i>
                                        Ubicación
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <!-- Municipio -->
                                        <div class="col-12">
                                            <label class="form-label">
                                                <i class="bi bi-map me-1"></i>
                                                Municipio
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select form-select-lg" id="selectMunicipio" required>
                                                    <option value="">Cargando municipios...</option>
                                                </select>
                                                <input type="hidden" id="idMunicipio" required />
                                            </div>
                                        </div>
                                        
                                        <!-- Sede -->
                                        <div class="col-12">
                                            <label class="form-label">
                                                <i class="bi bi-building me-1"></i>
                                                Sede
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select form-select-lg" id="selectSede" disabled required>
                                                    <option value="">Primero seleccione un municipio</option>
                                                </select>
                                                <input type="hidden" id="idSede" required />
                                            </div>
                                        </div>
                                        
                                        <!-- Ambiente -->
                                        <div class="col-12">
                                            <label class="form-label">
                                                <i class="bi bi-door-open me-1"></i>
                                                Ambiente
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select form-select-lg" id="selectAmbiente" disabled required>
                                                    <option value="">Primero seleccione una sede</option>
                                                </select>
                                                <input type="hidden" id="idAmbiente" required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevSection(1)">
                                            <i class="bi bi-arrow-left me-2"></i>
                                            Anterior
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg" onclick="nextSection(3)">
                                            Siguiente
                                            <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- SECCIÓN 3: PROGRAMA -->
                                <div class="form-section" id="section3">
                                    <h5 class="section-title">
                                        <i class="bi bi-mortarboard me-2"></i>
                                        Programa de Formación
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">
                                                <i class="bi bi-book me-1"></i>
                                                Programa
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select form-select-lg" id="selectPrograma" required>
                                                    <option value="">Cargando programas...</option>
                                                </select>
                                                <input type="hidden" id="idPrograma" required />
                                                <input type="hidden" id="duracionMeses" />
                                            </div>
                                        </div>
                                        
                                        <!-- Info de duración (se muestra al seleccionar programa) -->
                                        <div class="col-12" id="infoDuracion" style="display:none;">
                                            <div class="alert alert-info d-flex align-items-center">
                                                <i class="bi bi-info-circle fs-4 me-3"></i>
                                                <div>
                                                    <strong>Duración del programa:</strong>
                                                    <span id="duracionValue" class="ms-2"></span> meses
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevSection(2)">
                                            <i class="bi bi-arrow-left me-2"></i>
                                            Anterior
                                        </button>
                                        <button type="button" class="btn btn-primary btn-lg" onclick="nextSection(4)">
                                            Siguiente
                                            <i class="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- SECCIÓN 4: FECHAS -->
                                <div class="form-section" id="section4">
                                    <h5 class="section-title">
                                        <i class="bi bi-calendar-range me-2"></i>
                                        Fechas de Vigencia
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="fecha_inicio" class="form-label">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                Fecha de Inicio
                                            </label>
                                            <input 
                                                type="date" 
                                                class="form-control form-control-lg" 
                                                id="fecha_inicio" 
                                                required
                                            >
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="fecha_fin" class="form-label">
                                                <i class="bi bi-calendar-check me-1"></i>
                                                Fecha de Fin
                                                <span class="badge bg-info ms-2">Automática</span>
                                            </label>
                                            <input 
                                                type="date" 
                                                class="form-control form-control-lg" 
                                                id="fecha_fin" 
                                                readonly
                                                required
                                            >
                                            <div class="form-text">Se calcula automáticamente según la duración del programa</div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="prevSection(3)">
                                            <i class="bi bi-arrow-left me-2"></i>
                                            Anterior
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-check-circle me-2"></i>
                                            Crear Ficha
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Panel Derecho: Resumen -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye me-2"></i>
                                    Resumen de la Ficha
                                </h5>
                            </div>
                            <div class="card-body">
                               
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <i class="bi bi-map me-1"></i>
                                        Municipio
                                    </div>
                                    <div class="summary-value" id="summaryMunicipio" class="text-muted">No especificado</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <i class="bi bi-building me-1"></i>
                                        Sede
                                    </div>
                                    <div class="summary-value" id="summarySede" class="text-muted">No especificado</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <i class="bi bi-door-open me-1"></i>
                                        Ambiente
                                    </div>
                                    <div class="summary-value" id="summaryAmbiente" class="text-muted">No especificado</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <i class="bi bi-book me-1"></i>
                                        Programa
                                    </div>
                                    <div class="summary-value" id="summaryPrograma" class="text-muted">No especificado</div>
                                </div>
                                
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <i class="bi bi-calendar-range me-1"></i>
                                        Fechas
                                    </div>
                                    <div class="summary-value" id="summaryFechas" class="text-muted">No especificado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
            
        </div>
    </div>
    </div>
</div>
</div>