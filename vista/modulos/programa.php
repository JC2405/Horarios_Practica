
<!-- CSS Consolidado -->
<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">
      <div id="panelTablaPrograma">
        <div class="header-section">
          <div class="header-content">
            <div class="title-wrapper">
              <div class="title-icon">
                <i class="bi bi-journal-text"></i>
              </div>
              <div>
                <h2 class="section-title">Programas</h2>
                <p class="section-subtitle">Gestiona tus programas de formación</p>
              </div>
            </div>
          </div>

          <button id="agregarPrograma" class="btn-add">
            <span class="btn-glow"></span>
            <i class="bi bi-plus-lg"></i>
            <span>Nuevo Programa</span>
          </button>
        </div>

        <div class="table-wrapper">
          <table id="tablaPrograma" class="ultra-modern-table">
            <thead>
              <tr>
                <th>
                  <div class="th-wrap">
                    <i class="bi bi-tag"></i>
                    <span>Nombre</span>
                  </div>
                </th>

                <th>
                  <div class="th-wrap">
                    <i class="bi bi-upc-scan"></i>
                    <span>Código</span>
                  </div>
                </th>

                <th>
                  <div class="th-wrap">
                    <i class="bi bi-layers"></i>
                    <span>Versión</span>
                  </div>
                </th>

                <th>
                  <div class="th-wrap">
                    <i class="bi bi-circle-fill"></i>
                    <span>Estado</span>
                  </div>
                </th>

                <th>
                  <div class="th-wrap">
                    <i class="bi bi-diagram-3"></i>
                    <span>Tipo</span>
                  </div>
                </th>

                <th>
                  <div class="th-wrap">
                    <i class="bi bi-clock"></i>
                    <span>Duración</span>
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

            <tbody>
            </tbody>
          </table>
        </div>
      </div>







          <div id="panelFormularioPrograma" class="form-card" style="display: none;">
      <div class="form-card-header">
        <button id="btnRegresarTablaPrograma" type="button" class="btn-back">
          Regresar
        </button>

        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <div>
            <h3 class="form-title-text">Nuevo Programa</h3>
            <p class="form-subtitle-text">Registra el programa y sus datos</p>
          </div>
        </div>
      </div>

      <form id="formAgregarPrograma" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
          <label for="nombrePrograma" class="form-label">Nombre</label>
          <input type="text" class="form-control form-control-soft" id="nombrePrograma" placeholder="Ej: Análisis y Desarrollo de Software" required>
          <div class="invalid-feedback">Ingrese el nombre del programa</div>
        </div>

        <div class="col-md-6">
          <label for="codigo_Programa" class="form-label">Código</label>
          <input type="text" class="form-control form-control-soft" id="codigo_Programa" placeholder="Ej: 228106" required>
          <div class="invalid-feedback">Ingrese el código del programa</div>
        </div>

        <div class="col-md-6">
          <label for="id_tipoFormacion" class="form-label">Tipo de Formación</label>
          <select class="form-select form-control-soft" id="id_tipoFormacion" required>
            <option value="" selected disabled>Seleccione...</option>
            <!-- aquí cargas los tipos desde BD -->
          </select>
          <div class="invalid-feedback">Seleccione el tipo de formación</div>
        </div>

        <div class="col-md-3">
          <label for="version_programa" class="form-label">Versión</label>
          <input type="number" class="form-control form-control-soft" id="version_programa" placeholder="Ej: 1" min="1" required>
          <div class="invalid-feedback">Ingrese la versión</div>
        </div>

        <div class="col-md-3">
          <label for="estado_programa" class="form-label">Estado</label>
          <select class="form-select form-control-soft" id="estado_programa" required>
            <option value="" selected disabled>Seleccione...</option>
            <option value="Activo">activo</option>
            <option value="Inactivo">inactivo</option>
          </select> 
          <div class="invalid-feedback">Seleccione el estado</div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <button href="panel" type="button" id="btnCancelarPrograma" class="btn btn-light btn-soft">
            Cancelar
          </button>
          <button class="btn btn-primary btn-soft-primary" type="submit">
            <i class="bi bi-save2 me-2"></i> Guardar
          </button>
        </div>
      </form>
    </div>



      <div id="panelFormularioEditarPrograma" class="form-card" style="display: none;">
      <div class="form-card-header">
        <button id="btnRegresarTablaProgramaEdit" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i>
          Regresar
        </button>

        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-pencil-square"></i>
          </div>
          <div>
            <h3 class="form-title-text">Editar Programa</h3>
            <p class="form-subtitle-text">Actualiza la información del programa</p>
          </div>
        </div>
      </div>

      <form id="formEditarPrograma" class="row g-3 needs-validation" novalidate>

        <!-- ID OCULTO -->
        <input type="hidden" id="idProgramaEdit">

        <div class="col-md-6">
          <label for="nombreFormacionEdit" class="form-label">Nombre</label>
          <input
            type="text"
            class="form-control form-control-soft"
            id="nombreFormacionEdit"
            placeholder="Ej: Análisis y Desarrollo de Software"
            required
          >
          <div class="invalid-feedback">Ingrese el nombre del programa</div>
        </div>

        <div class="col-md-6">
          <label for="codigoEdit" class="form-label">Código</label>
          <input
            type="text"
            class="form-control form-control-soft"
            id="codigoEdit"
            placeholder="Ej: ADSO-123"
            required
          >
          <div class="invalid-feedback">Ingrese el código</div>
        </div>

        <div class="col-md-6">
          <label for="idTipoFormacionEdit" class="form-label">Tipo de formación</label>
          <select id="idTipoFormacionEdit" class="form-select form-control-soft" required>
            <option value="">Seleccione...</option>
            <!-- aquí llenas opciones por JS o PHP -->
          </select>
          <div class="invalid-feedback">Seleccione el tipo de formación</div>
        </div>

        <div class="col-md-3">
          <label for="versionEdit" class="form-label">Versión</label>
          <input
            type="text"
            class="form-control form-control-soft"
            id="versionEdit"
            placeholder="Ej: 1"
            required
          >
          <div class="invalid-feedback">Ingrese la versión</div>
        </div>

        <div class="col-md-3">
          <label for="estadoEdit" class="form-label">Estado</label>
          <select id="estadoEdit" class="form-select form-control-soft" required>
            <option value="">Seleccione...</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
          <div class="invalid-feedback">Seleccione el estado</div>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <button type="button" id="btnCancelarEditarPrograma" class="btn btn-light btn-soft">
            Cancelar
          </button>
          <button class="btn btn-primary btn-soft-primary" type="submit">
            <i class="bi bi-save2 me-2"></i> Guardar cambios
          </button>
        </div>
      </form>
    </div>

  

</div>