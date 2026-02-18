<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">

<div id="panelListar">

  <div class="header-section">
  <div class="header-content">
    <div class="title-wrapper">
      <div class="title-icon">
        <i class="bi bi-diagram-3"></i>
      </div>
      <div>
        <h2 class="section-title">Tipo Contrato</h2>
        <p class="section-subtitle">Administra los tipos de contrato</p>
      </div>
    </div>
  </div>

  <button id="agregarTipoContrato" class="btn-add" type="button">
    <i class="bi bi-plus-lg"></i>
    Nuevo Tipo
  </button>
</div>


  <div class="table-wrapper">
    <table id="tablaTipoContrato" class="ultra-modern-table">
      <thead>
        <tr>

          <th>
            <div class="th-wrap">
              <i class="bi bi-tag"></i>
              <span>Tipo Contrato</span>
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
  <!-- PANEL FORMULARIO AGREGAR -->
<div id="panelFormularioTipoContrato" style="display:none;">

  <div class="form-card">

    <!-- Header del formulario -->
    <div class="form-card-header">

      <!-- Regresar -->
      <button id="btnRegresarTablaTipoContrato" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <!-- Título -->
      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-plus-circle"></i>
        </div>
        <div>
          <h3 class="form-title-text">Nuevo Tipo de Contrato</h3>
          <p class="form-subtitle-text">Completa el formulario para registrar un tipo de contrato</p>
        </div>
      </div>

    </div>

    <!-- Form -->
    <form id="formAgregarTipoContrato" class="needs-validation" novalidate>
      <div class="row g-3">

        <div class="col-12">
          <label for="tipo_contrato" class="form-label">
            Tipo Contrato <span class="text-danger">*</span>
          </label>

          <input
            type="text"
            class="form-control form-control-soft"
            id="tipo_contrato"
            name="tipoContrato"
            placeholder="Ej: Término indefinido"
            required
            minlength="3"
            maxlength="80"
            autocomplete="off"
          />

          <div class="invalid-feedback">
            Por favor ingresa un tipo de contrato (mínimo 3 caracteres).
          </div>
        </div>

        <!-- Botones -->
        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
          <button id="btnCancelarTipoContrato" type="button" class="btn btn-light">
            <i class="bi bi-x-circle"></i>
            Cancelar
          </button>

          <button type="submit" class="btn btn-primary" id="btn">
            <i class="bi bi-save2"></i>
            Guardar
          </button>
        </div>

      </div>
    </form>

  </div>
</div>

<!-- PANEL FORMULARIO EDITAR -->
<div id="panelFormularioEditarTipoContrato" style="display:none;">

  <div class="form-card">

    <!-- Header del formulario -->
    <div class="form-card-header">

      <!-- Regresar -->
      <button id="btnRegresarTablaTipoContratoEdit" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <!-- Título -->
      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-pencil-square"></i>
        </div>
        <div>
          <h3 class="form-title-text">Editar Tipo de Contrato</h3>
          <p class="form-subtitle-text">Actualiza la información y guarda los cambios</p>
        </div>
      </div>

    </div>

    <!-- Form -->
    <form id="formEditarTipoContrato" class="needs-validation" novalidate>
      <div class="row g-3">

        <!-- ID oculto -->
        <input type="hidden" id="idTipoCintratoEdit" name="idTipoContrato">

        <div class="col-12">
          <label for="tipoContratoEdit" class="form-label">
            Tipo Contrato <span class="text-danger">*</span>
          </label>

          <input
            type="text"
            class="form-control form-control-soft"
            id="tipoContratoEdit"
            name="tipoContrato"
            placeholder="Ej: Término fijo"
            required
            minlength="3"
            maxlength="80"
            autocomplete="off"
          />

          <div class="invalid-feedback">
            Por favor ingresa un tipo de contrato (mínimo 3 caracteres).
          </div>
        </div>

        <!-- Botones -->
        <div class="col-12 d-flex justify-content-end gap-2 mt-2">

          <button id="btnCancelarTipoContratoEdit" type="button" class="btn btn-light">
            <i class="bi bi-x-circle"></i>
            Cancelar
          </button>

          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save2"></i>
            Guardar cambios
          </button>

        </div>

      </div>
    </form>

  </div>
</div>


</div>  