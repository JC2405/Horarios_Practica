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



<!-- FORMULARIO AGREGAR -->
<div id="panelFormularioInstructor" style="display:none;">
  <div style="max-width:760px; margin:0 auto;">
    <div class="form-card">
      <div class="form-card-header">
        <button id="btnRegresarTablaInstructor" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon"><i class="bi bi-person-plus"></i></div>
          <div>
            <h2 class="form-title-text">Nuevo Instructor</h2>
            <p class="form-subtitle-text">Registra un nuevo instructor</p>
          </div>
        </div>
      </div>

      <form id="formAgregarInstructor" novalidate>
        <div class="row g-3">

          <div class="col-md-10">
            <label class="form-label">Nombre completo</label>
            <input type="text" id="nombreInstructor" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Correo electrónico</label>
            <input type="email" id="correoInstructor" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Correo válido requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Teléfono</label>
            <input type="text" id="telefonoInstructor" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Área</label>
            <select id="idAreaInstructor" class="form-control-soft w-100" required>
              <option value="" disabled selected>Cargando...</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Tipo de Contrato</label>
            <select id="idTipoContratoInstructor" class="form-control-soft w-100" required>
              <option value="" disabled selected>Cargando...</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Contraseña</label>
            <input type="password" id="passwordInstructor" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="col-md-10">
            <label class="form-label">Estado</label>
            <select id="estadoInstructor" class="form-control-soft w-100" required>
              <option value="" disabled selected>Seleccione...</option>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
          <button type="button" id="btnCancelarInstructor" class="btn btn-light">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- FORMULARIO EDITAR -->
<div id="panelFormularioEditarInstructor" style="display:none;">
  <div style="max-width:760px; margin:0 auto;">
    <div class="form-card">
      <div class="form-card-header">
        <button id="btnRegresarTablaInstructorEdit" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon"><i class="bi bi-pencil-square"></i></div>
          <div>
            <h2 class="form-title-text">Editar Instructor</h2>
            <p class="form-subtitle-text">Actualiza la información</p>
          </div>
        </div>
      </div>

      <form id="formEditarInstructor" novalidate>
        <input type="hidden" id="idInstructorEdit">
        <div class="row g-3">
          <div class="col-md-10">
            <label class="form-label">Nombre completo</label>
            <input type="text" id="nombreInstructorEdit" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Correo electrónico</label>
            <input type="email" id="correoInstructorEdit" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Correo válido requerido</div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Teléfono</label>
            <input type="text" id="telefonoInstructorEdit" class="form-control-soft w-100" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Estado</label>
            <select id="estadoInstructorEdit" class="form-control-soft w-100" required>
              <option value="" disabled selected>Seleccione...</option>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Área</label>
            <select id="idAreaInstructorEdit" class="form-control-soft w-100" required>
              <option value="" disabled selected>Cargando...</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>
          <div class="col-md-10">
            <label class="form-label">Tipo de Contrato</label>
            <select id="idTipoContratoInstructorEdit" class="form-control-soft w-100" required>
              <option value="" disabled selected>Cargando...</option>
            </select>
            <div class="invalid-feedback">Campo requerido</div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-4 gap-2">
          <button type="button" id="btnCancelarEditarInstructor" class="btn btn-light">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>



</div>
