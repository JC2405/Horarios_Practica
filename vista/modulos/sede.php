<!-- CSS Consolidado -->
<link href="vista/css/styles.css" rel="stylesheet">

<div class="container">

  <!-- ===================== PANEL TABLA SEDE ===================== -->
  <div id="panelTablaSede">
    <div class="header-section">
      <div class="header-content">
        <div class="title-wrapper">
          <div class="title-icon">
            <i class="bi bi-building"></i>
          </div>
          <div>
            <h2 class="section-title">Sedes</h2>
            <p class="section-subtitle">Gestiona tus ubicaciones</p>
          </div>
        </div>
      </div>
      <button id="agregarSede" class="btn-add" type="button">
        <span class="btn-glow"></span>
        <i class="bi bi-plus-lg"></i>
        <span>Nueva Sede</span>
      </button>
    </div>

    <div class="table-wrapper">
      <table id="tablaSede" class="ultra-modern-table">
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
                <i class="bi bi-geo-alt"></i>
                <span>Dirección</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-file-text"></i>
                <span>Descripción</span>
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
                <i class="bi bi-map"></i>
                <span>Municipio</span>
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

  <!-- ===================== PANEL FORMULARIO AGREGAR SEDE ===================== -->
  <div id="panelFormularioSede" class="form-card" style="display: none;">
    <div class="form-card-header">
      <button id="btnRegresarTablaSede" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-building"></i>
        </div>
        <div>
          <h3 class="form-title-text">Nueva Sede</h3>
          <p class="form-subtitle-text">Registra la sede y sus datos</p>
        </div>
      </div>
    </div>

    <form id="formAgregarSede" class="row g-3 needs-validation" novalidate>
      <div class="col-md-6">
        <label for="nombreSede" class="form-label">Nombre</label>
        <input type="text" class="form-control form-control-soft" id="nombreSede"
               placeholder="Ej: Sede Central" required>
        <div class="invalid-feedback">Ingrese el nombre de la sede</div>
      </div>

      <div class="col-md-6">
        <label for="idMunicipioSede" class="form-label">Municipio</label>
        <select class="form-select form-control-soft" id="idMunicipioSede" required>
          <option value="" selected disabled>Seleccione...</option>
        </select>
        <div class="invalid-feedback">Seleccione el municipio</div>
      </div>

      <div class="col-md-6">
        <label for="direccionSede" class="form-label">Dirección</label>
        <input type="text" class="form-control form-control-soft" id="direccionSede"
               placeholder="Ej: Calle 45 #10-20" required>
        <div class="invalid-feedback">Ingrese la dirección</div>
      </div>

      <div class="col-md-6">
        <label for="estadoSede" class="form-label">Estado</label>
        <select class="form-select form-control-soft" id="estadoSede" required>
          <option value="" selected disabled>Seleccione...</option>
          <option value="Activo">Activo</option>
          <option value="Inactivo">Inactivo</option>
        </select>
        <div class="invalid-feedback">Seleccione el estado</div>
      </div>

      <div class="col-md-12">
        <label for="descripcionSede" class="form-label">Descripción</label>
        <textarea class="form-control form-control-soft" id="descripcionSede" rows="3"
                  placeholder="Ej: Oficina principal / atención al público" required></textarea>
        <div class="invalid-feedback">Ingrese la descripción</div>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <button type="button" id="btnCancelarSede" class="btn btn-light btn-soft">
          Cancelar
        </button>
        <button class="btn btn-primary btn-soft-primary" type="submit">
          <i class="bi bi-save2 me-2"></i> Guardar
        </button>
      </div>
    </form>
  </div>

  <!-- ===================== PANEL FORMULARIO EDITAR SEDE ===================== -->
  <div id="panelFormularioEditarSede" class="form-card" style="display: none;">
    <div class="form-card-header">
      <button id="btnRegresarTablaSedeEdit" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-pencil-square"></i>
        </div>
        <div>
          <h3 class="form-title-text">Editar Sede</h3>
          <p class="form-subtitle-text">Actualiza la información de la sede</p>
        </div>
      </div>
    </div>

    <form id="formEditarSede" class="row g-3 needs-validation" novalidate>
      <input type="hidden" id="idSedeEdit">

      <div class="col-md-6">
        <label for="nombreSedeEdit" class="form-label">Nombre</label>
        <input type="text" class="form-control form-control-soft" id="nombreSedeEdit"
               placeholder="Ej: Sede Central" required>
        <div class="invalid-feedback">Ingrese el nombre</div>
      </div>

      <div class="col-md-6">
        <label for="idMunicipioSedeEdit" class="form-label">Municipio</label>
        <select class="form-select form-control-soft" id="idMunicipioSedeEdit" required>
          <option value="" disabled>Seleccione...</option>
        </select>
        <div class="invalid-feedback">Seleccione el municipio</div>
      </div>

      <div class="col-md-6">
        <label for="direccionSedeEdit" class="form-label">Dirección</label>
        <input type="text" class="form-control form-control-soft" id="direccionSedeEdit"
               placeholder="Ej: Calle 45 #10-20" required>
        <div class="invalid-feedback">Ingrese la dirección</div>
      </div>

      <div class="col-md-6">
        <label for="estadoSedeEdit" class="form-label">Estado</label>
        <select class="form-select form-control-soft" id="estadoSedeEdit" required>
          <option value="">Seleccione...</option>
          <option value="Activo">Activo</option>
          <option value="Inactivo">Inactivo</option>
        </select>
        <div class="invalid-feedback">Seleccione el estado</div>
      </div>

      <div class="col-md-12">
        <label for="descripcionSedeEdit" class="form-label">Descripción</label>
        <textarea class="form-control form-control-soft" id="descripcionSedeEdit" rows="3"
                  placeholder="Ej: Oficina principal / atención al público" required></textarea>
        <div class="invalid-feedback">Ingrese la descripción</div>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <button type="button" id="btnCancelarEditarSede" class="btn btn-light btn-soft">
          Cancelar
        </button>
        <button class="btn btn-primary btn-soft-primary" type="submit">
          <i class="bi bi-save2 me-2"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>

  <!-- ===================== PANEL AMBIENTES DE LA SEDE ===================== -->
  <div id="panelAmbientesSede" style="display:none;">
    <div class="header-section">
      <div class="header-content">
        <div class="title-wrapper">
          <div class="title-icon"><i class="bi bi-door-open"></i></div>
          <div>
            <h2 class="section-title">Ambientes</h2>
            <p class="section-subtitle">
              Sede: <strong id="nombreSedeActualListado">---</strong>
            </p>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button id="btnRegresarSedesDesdeAmbientes" class="btn btn-light btn-soft" type="button">
          <i class="bi bi-arrow-left"></i> Volver
        </button>

        <button id="btnNuevoAmbiente" class="btn-add" type="button">
          <span class="btn-glow"></span>
          <i class="bi bi-plus-lg"></i>
          <span>Nuevo Ambiente</span>
        </button>
      </div>
    </div>

    <input type="hidden" id="idSedeActualAmbientes">

    <div class="table-wrapper">
      <table id="tablaAmbientesSede" class="ultra-modern-table">
        <thead>
          <tr>
            <th>
              <div class="th-wrap">
                <i class="bi bi-upc-scan"></i>
                <span>Código</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-hash"></i>
                <span>Número</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-people"></i>
                <span>Capacidad</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-geo-alt"></i>
                <span>Ubicación</span>
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
                <i class="bi bi-file-text"></i>
                <span>Descripción</span>
              </div>
            </th>
            <th>
              <div class="th-wrap">
                <i class="bi bi-gear"></i>
                <span>Acciones</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div> <!-- ✅ cierre correcto panelAmbientesSede -->

  <!-- ===================== PANEL FORMULARIO AGREGAR AMBIENTE ===================== -->
  <div id="panelFormularioAgregarAmbienteSede" class="form-card" style="display:none;">
    <div class="form-card-header">
      <button id="btnRegresarAmbientes" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-door-open"></i>
        </div>
        <div>
          <h3 class="form-title-text">Nuevo Ambiente</h3>
          <p class="form-subtitle-text">
            Sede: <strong id="nombreSedeActual">---</strong>
          </p>
        </div>
      </div>
    </div>

    <form id="formAgregarAmbientePorSede" class="row g-3 needs-validation" novalidate>
      <input type="hidden" id="idSedeAgregar" required>

      <div class="col-md-4">
        <label for="codigoAgregar" class="form-label">Código</label>
        <input type="text" class="form-control form-control-soft" id="codigoAgregar"
               placeholder="Ej: A101" required>
        <div class="invalid-feedback">Ingrese el código</div>
      </div>

      <div class="col-md-4">
        <label for="numeroAgregar" class="form-label">Número</label>
        <input type="number" class="form-control form-control-soft" id="numeroAgregar"
               placeholder="Ej: 101" required>
        <div class="invalid-feedback">Ingrese el número</div>
      </div>

      <div class="col-md-4">
        <label for="capacidadAgregar" class="form-label">Capacidad</label>
        <input type="number" class="form-control form-control-soft" id="capacidadAgregar"
               placeholder="Ej: 30" required>
        <div class="invalid-feedback">Ingrese la capacidad</div>
      </div>

      <div class="col-md-6">
        <label for="ubicacionAgregar" class="form-label">Ubicación</label>
        <input type="text" class="form-control form-control-soft" id="ubicacionAgregar"
               placeholder="Ej: Bloque A" required>
        <div class="invalid-feedback">Ingrese la ubicación</div>
      </div>

      <div class="col-md-6">
        <label for="estadoAgregar" class="form-label">Estado</label>
        <select class="form-select form-control-soft" id="estadoAgregar" required>
          <option value="" selected disabled>Seleccione...</option>
          <option value="ACTIVO">ACTIVO</option>
          <option value="INACTIVO">INACTIVO</option>
        </select>
        <div class="invalid-feedback">Seleccione el estado</div>
      </div>

      <div class="col-md-12">
        <label for="descripcionAgregar" class="form-label">Descripción</label>
        <textarea class="form-control form-control-soft" id="descripcionAgregar" rows="3"
                  placeholder="Ej: Laboratorio / Aula / Sala..." required></textarea>
        <div class="invalid-feedback">Ingrese la descripción</div>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <button type="button" id="btnCancelarAgregarAmbiente" class="btn btn-light btn-soft">
          Cancelar
        </button>
        <button class="btn btn-primary btn-soft-primary" type="submit">
          <i class="bi bi-save2 me-2"></i> Guardar
        </button>
      </div>
    </form>
  </div>

  <!-- ===================== PANEL FORMULARIO EDITAR AMBIENTE ===================== -->
  <div id="panelFormularioEditarAmbienteSede" class="form-card" style="display:none;">
    <div class="form-card-header">
      <button id="btnRegresarEditarAmbiente" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i>
        Regresar
      </button>

      <div class="form-title">
        <div class="form-title-icon">
          <i class="bi bi-pencil-square"></i>
        </div>
        <div>
          <h3 class="form-title-text">Editar Ambiente</h3>    
        </div>
      </div>
    </div>

    <form id="formEditarAmbientePorSede" class="row g-3 needs-validation" novalidate>
      <input type="hidden" id="idAmbienteEdit" required>

      <div class="col-md-4">
        <label for="codigoEdit" class="form-label">Código</label>
        <input type="text" class="form-control form-control-soft" id="codigoEdit" required>
        <div class="invalid-feedback">Ingrese el código</div>
      </div>

      <div class="col-md-4">
        <label for="numeroEdit" class="form-label">Número</label>
        <input type="number" class="form-control form-control-soft" id="numeroEdit" required>
        <div class="invalid-feedback">Ingrese el número</div>
      </div>

      <div class="col-md-4">
        <label for="capacidadEdit" class="form-label">Capacidad</label>
        <input type="number" class="form-control form-control-soft" id="capacidadEdit" required>
        <div class="invalid-feedback">Ingrese la capacidad</div>
      </div>

      <div class="col-md-6">
        <label for="ubicacionEdit" class="form-label">Ubicación</label>
        <input type="text" class="form-control form-control-soft" id="ubicacionEdit" required>
        <div class="invalid-feedback">Ingrese la ubicación</div>
      </div>

      <div class="col-md-6">
        <label for="estadoEdit" class="form-label">Estado</label>
        <select class="form-select form-control-soft" id="estadoEdit" required>
          <option value="" disabled>Seleccione...</option>
          <option value="ACTIVO">ACTIVO</option>
          <option value="INACTIVO">INACTIVO</option>
        </select>
        <div class="invalid-feedback">Seleccione el estado</div>
      </div>

      <div class="col-md-12">
        <label for="descripcionEdit" class="form-label">Descripción</label>
        <textarea class="form-control form-control-soft" id="descripcionEdit" rows="3" required></textarea>
        <div class="invalid-feedback">Ingrese la descripción</div>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mt-2">
        <button type="button" id="btnCancelarEditarAmbiente" class="btn btn-light btn-soft">
          Cancelar
        </button>
        <button class="btn btn-primary btn-soft-primary" type="submit">
          <i class="bi bi-save2 me-2"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>

</div>



<!-- Cargar primero la clase, luego los eventos -->

