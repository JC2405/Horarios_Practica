<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">

<div id="panelListarInstructor">
<div class="header-section">
  <div class="header-content">
    <div class="title-wrapper">
      <div class="title-icon">
        <i class="bi bi-person-badge"></i>
      </div>
      <div>
        <h2 class="section-title">Instructores</h2>
        <p class="section-subtitle">Administra los instructores</p>
      </div>
    </div>
  </div>

  <button id="agregarInstructor" class="btn-add" type="button">
    <i class="bi bi-plus-lg"></i>
    Nuevo Instructor
  </button>
</div>


  <div class="table-wrapper">
    <table id="tablaInstructores" class="ultra-modern-table">
      <thead>
        <tr>

          <th>
            <div class="th-wrap">
              <i class="bi bi-person"></i>
              <span>Nombre</span>
            </div>
          </th>

          <th>
            <div class="th-wrap">
              <i class="bi bi-envelope"></i>
              <span>Correo</span>
            </div>
          </th>

          <th>
            <div class="th-wrap">
              <i class="bi bi-telephone"></i>
              <span>Teléfono</span>
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
              <i class="bi bi-circle-fill"></i>
              <span>Email</span>
            </div>
          </th>  

          <th>
            <div class="th-wrap">
              <i class="bi bi-building"></i>
              <span>Área</span>
            </div>
          </th>

          <th>
            <div class="th-wrap">
              <i class="bi bi-briefcase"></i>
              <span>Tipo Contrato</span>
            </div>
          </th>

          <th>
            <div class="th-wrap">
              <i class="bi bi-diagram-3"></i>
              <span>Rol</span>
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



<!-- ========================= -->
<!-- PANEL FORMULARIO AGREGAR -->
<!-- ========================= -->
<div id="panelFormularioInstructor" class="form-card" style="display:none;">

  <div class="form-card-header">
    <div class="form-title">
      <div class="form-title-icon">
        <i class="bi bi-person-plus"></i>
      </div>
      <div>
        <h3 class="form-title-text">Nuevo Instructor</h3>
        <p class="form-subtitle-text">Registra la información del instructor</p>
      </div>
    </div>
  </div>

  <form id="formAgregarInstructor" class="row g-3 needs-validation" novalidate>

    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input type="text" id="nombreInstructor" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el nombre</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Correo</label>
      <input type="email" id="correoInstructor" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el correo</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Teléfono</label>
      <input type="text" id="telefonoInstructor" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el teléfono</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Estado</label>
      <select id="estadoInstructor" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>
      <div class="invalid-feedback">Seleccione el estado</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Área</label>
      <select id="idAreaInstructor" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
      </select>
      <div class="invalid-feedback">Seleccione el área</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Tipo Contrato</label>
      <select id="idTipoContratoInstructor" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
      </select>
      <div class="invalid-feedback">Seleccione el tipo contrato</div>
    </div>

    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
      <button type="button" id="btnCancelarInstructor" class="btn btn-light btn-soft">
        Cancelar
      </button>
      <button class="btn btn-primary btn-soft-primary" type="submit">
        <i class="bi bi-save2 me-2"></i> Guardar
      </button>
    </div>

  </form>

</div>


<!-- ========================= -->
<!-- PANEL FORMULARIO EDITAR -->
<!-- ========================= -->
<div id="panelFormularioEditarInstructor" class="form-card" style="display:none;">

  <div class="form-card-header">
    <button id="btnRegresarInstructorEdit" type="button" class="btn-back">
      <i class="bi bi-arrow-left"></i> Regresar
    </button>

    <div class="form-title">
      <div class="form-title-icon">
        <i class="bi bi-pencil-square"></i>
      </div>
      <div>
        <h3 class="form-title-text">Editar Instructor</h3>
        <p class="form-subtitle-text">Actualiza la información</p>
      </div>
    </div>
  </div>

  <form id="formEditarInstructor" class="row g-3 needs-validation" novalidate>

    <input type="hidden" id="idInstructorEdit">

    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input type="text" id="nombreInstructorEdit" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el nombre</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Correo</label>
      <input type="email" id="correoInstructorEdit" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el correo</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Teléfono</label>
      <input type="text" id="telefonoInstructorEdit" class="form-control form-control-soft" required>
      <div class="invalid-feedback">Ingrese el teléfono</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Estado</label>
      <select id="estadoInstructorEdit" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>
      <div class="invalid-feedback">Seleccione el estado</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Área</label>
      <select id="idAreaInstructorEdit" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
      </select>
      <div class="invalid-feedback">Seleccione el área</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">Tipo Contrato</label>
      <select id="idTipoContratoInstructorEdit" class="form-select form-control-soft" required>
        <option value="">Seleccione</option>
      </select>
      <div class="invalid-feedback">Seleccione el tipo contrato</div>
    </div>

    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
      <button type="button" id="btnCancelarEditarInstructor" class="btn btn-light btn-soft">
        Cancelar
      </button>
      <button class="btn btn-primary btn-soft-primary" type="submit">
        <i class="bi bi-save2 me-2"></i> Guardar cambios
      </button>
    </div>

  </form>

</div>



</div>
