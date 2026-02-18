<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">

  <!-- =========================
       PANEL TABLA
  ========================== -->
  <div id="panelTablaArea">
    <div class="header-section">
      <div class="header-content">
        <div class="title-wrapper">
          <div class="title-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <div>
            <h2 class="section-title">Área</h2>
            <p class="section-subtitle">Administra las áreas del sistema</p>
          </div>
        </div>
      </div>

      <button id="agregarArea" class="btn-add" type="button">
        <i class="bi bi-plus-lg"></i>
        Nueva Área
      </button>
    </div>

    <div class="table-wrapper">
      <table id="tablaArea" class="ultra-modern-table">
        <thead>
          <tr>
            <th>
              <div class="th-wrap">
                <i class="bi bi-tag"></i>
                <span>Área</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-sliders"></i>
                <span>Acciones</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- =========================
       PANEL AGREGAR (CORREGIDO)
  ========================== -->
  <div id="panelFormularioAgregarArea" style="display:none;">
    
    <!-- IMPORTANTE: este wrapper limita el ancho (más delgado) -->
    <div style="max-width: 760px; margin: 0 auto;">

      <div class="form-card">

        <div class="form-card-header">
          <button id="btnVolverTablaAreaAgregar" type="button" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Regresar
          </button>

          <div class="form-title">
            <div class="form-title-icon">
              <i class="bi bi-plus-circle"></i>
            </div>

            <div>
              <h2 class="form-title-text">Nueva Área</h2>
              <p class="form-subtitle-text">Registra una nueva área</p>
            </div>
          </div>
        </div>

        <form id="formRegistrarArea" class="needs-validation" novalidate>
          <div class="mb-3">
            <label class="form-label">Nombre del Área</label>
            <input
              type="text"
              id="nombre_area"
              class="form-control-soft w-100"
              placeholder="Ej: Sistemas"
              required
            />
            <div class="invalid-feedback">Por favor escribe el nombre del área</div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="button" id="btnCancelarAreaAgregar" class="btn btn-light">
              <i class="bi bi-x-circle me-1"></i> Cancelar
            </button>

            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-1"></i> Guardar
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- =========================
       PANEL EDITAR (CORREGIDO)
  ========================== -->
  <div id="panelFormularioEditarArea" style="display:none;">
    
    <!-- IMPORTANTE: este wrapper limita el ancho (más delgado) -->
    <div style="max-width: 760px; margin: 0 auto;">

      <div class="form-card">

        <div class="form-card-header">
          <button id="btnVolverTablaAreaEditar" type="button" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Regresar
          </button>

          <div class="form-title">
            <div class="form-title-icon">
              <i class="bi bi-pencil-square"></i>
            </div>

            <div>
              <h2 class="form-title-text">Editar Área</h2>
              <p class="form-subtitle-text">Actualiza la información del área</p>
            </div>
          </div>
        </div>

        <form id="formEditarArea" class="needs-validation" novalidate>
          <input type="hidden" id="idAreaEdit" />

          <div class="mb-3">
            <label class="form-label">Nombre del Área</label>
            <input
              type="text"
              id="nombreAreaEdit"
              class="form-control-soft w-100"
              placeholder="Ej: Sistemas"
              required
            />
            <div class="invalid-feedback">Por favor escribe el nombre del área</div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="button" id="btnCancelarAreaEditar" class="btn btn-light">
              <i class="bi bi-x-circle me-1"></i> Cancelar
            </button>

            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-1"></i> Guardar cambios
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
