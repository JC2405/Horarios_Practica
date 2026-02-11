
<!-- CSS Consolidado -->
<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">

<div id="panelTablaTipoPrograma">

  <div class="header-section">
    <div class="header-content">
      <div class="title-wrapper">
        <div class="title-icon">
          <i class="bi bi-diagram-3"></i>
        </div>
        <div>
          <h2 class="section-title">Tipos de Programa</h2>
          <p class="section-subtitle">Administra los tipos y su duración</p>
        </div>
      </div>
    </div>

    <button id="agregarTipoPrograma" class="btn-add">
      <span class="btn-glow"></span>
      <i class="bi bi-plus-lg"></i>
      <span>Nuevo Tipo</span>
    </button>
  </div>

  <div class="table-wrapper">
    <table id="tablaTipoPrograma" class="ultra-modern-table">
      <thead>
        <tr>

          <th>
            <div class="th-wrap">
              <i class="bi bi-tag"></i>
              <span>Tipo</span>
            </div>
          </th>

          <th>
            <div class="th-wrap">
              <i class="bi bi-clock"></i>
              <span>Duración Meses</span>
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

           <div id="panelFormularioTipoPrograma" class="form-card" style="display: none;">
          <div class="form-card-header">

            <div class="form-title">
              <div class="form-title-icon">
                <i class="bi bi-diagram-3"></i>
              </div>
              <div>
                <h3 class="form-title-text">Nuevo Tipo de Programa</h3>
                <p class="form-subtitle-text">Registra el tipo y su duración</p>
              </div>
            </div>
          </div>

          <form id="formAgregarTipoPrograma" class="row g-3 needs-validation" novalidate>
            <div class="col-md-6">
              <label for="tipoFormacion" class="form-label">Tipo</label>
              <input type="text" class="form-control form-control-soft" id="tipo_Formacion" placeholder="Ej: Tecnólogo" required>
              <div class="invalid-feedback">Ingrese el tipo de programa</div>
            </div>

            <div class="col-md-6">
              <label for="duracion" class="form-label">Duración (meses)</label>
              <input type="number" class="form-control form-control-soft" id="duracionMeses" placeholder="Ej: 24" min="1" required>
              <div class="invalid-feedback">Ingrese la duración</div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
              <button type="button" id="btnCancelarTipoPrograma" class="btn btn-light btn-soft">
                Cancelar
              </button>
              <button class="btn btn-primary btn-soft-primary" type="submit">
                <i class="bi bi-save2 me-2"></i> Guardar
              </button>
            </div>
          </form>
        </div>






          
    <div id="panelFormularioEditarTipoPrograma" class="form-card" style="display: none;">
      <div class="form-card-header">
        <button id="btnRegresarTablaTipoProgramaEdit" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i>
          Regresar
        </button>
    
        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-pencil-square"></i>
          </div>
          <div>
            <h3 class="form-title-text">Editar Tipo de Programa</h3>
            <p class="form-subtitle-text">Actualiza el tipo y su duración</p>
          </div>
        </div>
      </div>
    
      <form id="formEditarTipoPrograma" class="row g-3 needs-validation" novalidate>
        
        <!-- ID OCULTO -->
        <input type="hidden" id="idTipoProgramaEdit">
    
        <div class="col-md-6">
          <label for="tipoFormacionEdit" class="form-label">Tipo</label>
          <input
            type="text"
            class="form-control form-control-soft"
            id="tipoFormacionEdit"
            placeholder="Ej: Tecnólogo"
            required
          >
          <div class="invalid-feedback">Ingrese el tipo de programa</div>
        </div>
    
        <div class="col-md-6">
          <label for="duracionEdit" class="form-label">Duración (meses)</label>
          <input
            type="number"
            class="form-control form-control-soft"
            id="duracionEdit"
            placeholder="Ej: 24"
            min="1"
            required
          >
          <div class="invalid-feedback">Ingrese la duración</div>
        </div>
    
        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <button type="button" id="btnCancelarEditarTipoPrograma" class="btn btn-light btn-soft">
            Cancelar
          </button>
          <button class="btn btn-primary btn-soft-primary" type="submit">
            <i class="bi bi-save2 me-2" id= "guardarCambios"></i> Guardar cambios
          </button>
        </div>
      </form>
    </div>


</div>
