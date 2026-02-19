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
        <i class="bi bi-plus-lg"></i>
        Nueva Sede
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











  <!-- =====================================================
     PANEL: TABLA AMBIENTES POR SEDE
     ===================================================== -->
<div id="panelAmbientesSede" style="display:none;">

  <div class="header-section">
    <div class="header-content">
      <div class="title-wrapper">
        <div class="title-icon">
          <i class="bi bi-door-open"></i>
        </div>
        <div>
          <h2 class="section-title">Ambientes</h2>
          <p class="section-subtitle">
            Sede: <strong id="nombreSedeActualListado">—</strong>
          </p>
        </div>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button id="btnRegresarSedesDesdeAmbientes" type="button" class="btn-back">
        <i class="bi bi-arrow-left"></i> Regresar a Sedes
      </button>
      <button id="btnNuevoAmbiente" type="button" class="btn-add">
        <i class="bi bi-plus-lg"></i> Nuevo Ambiente
      </button>
    </div>
  </div>

  <!-- Campo oculto para guardar idSede activo -->
  <input type="hidden" id="idSedeActualAmbientes" />

  <div class="table-wrapper">
    <table id="tablaAmbientesSede" class="ultra-modern-table">
      <thead>
        <tr>
          <th><div class="th-wrap"><i class="bi bi-upc"></i><span>Código</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-hash"></i><span>Número</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-tag"></i><span>Nombre</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-people"></i><span>Capacidad</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-building"></i><span>Bloque</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-grid"></i><span>Tipo Ambiente</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-toggle-on"></i><span>Estado</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-card-text"></i><span>Descripción</span></div></th>
          <th><div class="th-wrap"><i class="bi bi-sliders"></i><span>Acciones</span></div></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>


<!-- =====================================================
     PANEL: FORMULARIO AGREGAR AMBIENTE
     ===================================================== -->
<div id="panelFormularioAgregarAmbienteSede" style="display:none;">
  <div style="max-width: 860px; margin: 0 auto;">
    <div class="form-card">

      <!-- Cabecera -->
      <div class="form-card-header">
        <button id="btnRegresarAmbientes" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-plus-circle"></i>
          </div>
          <div>
            <h2 class="form-title-text">Nuevo Ambiente</h2>
            <p class="form-subtitle-text">
              Sede: <strong id="nombreSedeActual">—</strong>
            </p>
          </div>
        </div>
      </div>

      <!-- Formulario -->
      <form id="formAgregarAmbientePorSede" class="needs-validation" novalidate>

        <!-- Campo oculto idSede -->
        <input type="hidden" id="idSedeAgregar" />

        <div class="row g-3">

          <!-- Código -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-upc me-1"></i> Código <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              id="codigoAgregar"
              class="form-control form-control-soft"
              placeholder="Ej: A101"
              required
            />
            <div class="invalid-feedback">El código es obligatorio.</div>
          </div>

          <!-- Número -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-hash me-1"></i> Número <span class="text-danger">*</span>
            </label>
            <input
              type="number"
              id="numeroAgregar"
              class="form-control form-control-soft"
              placeholder="Ej: 101"
              min="1"
              required
            />
            <div class="invalid-feedback">El número es obligatorio.</div>
          </div>

          <!-- Nombre -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-tag me-1"></i> Nombre Area
            </label>
           <div class="select-wrapper">
           <select class="form-select form-select-lg" id="selectAreas" required>
             <option value="">Cargando Areas...</option>
           </select>
         </div>
         <input type="hidden" id="idArea">
       </div>

          <!-- Capacidad -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-people me-1"></i> Capacidad <span class="text-danger">*</span>
            </label>
            <input
              type="number"
              id="capacidadAgregar"
              class="form-control form-control-soft"
              placeholder="Ej: 30"
              min="1"
              required
            />
            <div class="invalid-feedback">La capacidad es obligatoria.</div>
          </div>

          <!-- Bloque (antes Ubicación) -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-building me-1"></i> Bloque <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              id="bloqueAgregar"
              class="form-control form-control-soft"
              placeholder="Ej: Bloque A"
              required
            />
            <div class="invalid-feedback">El bloque es obligatorio.</div>
          </div>

          <!-- Tipo Ambiente -->
         <div class="mb-3">
          <label class="form-label fw-bold" for="tipoAmbienteAgregar">
            <i class="bi bi-tag me-1"></i> Tipo de Ambiente <span class="text-danger">*</span>
          </label>
          <select id="tipoAmbienteAgregar" name="tipoAmbiente"
                  class="form-control form-control-soft" required>
            <option value="" disabled selected>— Seleccione tipo —</option>
            <option value="Formacion">Formación</option>
            <option value="Bilinguismo">Bilingüismo</option>
            <option value="Taller">Taller</option>
          </select>
          <div class="invalid-feedback">Seleccione un tipo de ambiente.</div>
        </div>

          <!-- Estado -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-toggle-on me-1"></i> Estado <span class="text-danger">*</span>
            </label>
            <select id="estadoAgregar" class="form-select form-control-soft" required>
              <option value="">Seleccione...</option>
              <option value="ACTIVO">ACTIVO</option>
              <option value="INACTIVO">INACTIVO</option>
            </select>
            <div class="invalid-feedback">Seleccione un estado.</div>
          </div>

          <!-- Descripción -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-card-text me-1"></i> Descripción
            </label>
            <input
              type="text"
              id="descripcionAgregar"
              class="form-control form-control-soft"
              placeholder="Descripción opcional..."
            />
          </div>

        </div><!-- /row -->

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="button" id="btnCancelarAgregarAmbiente" class="btn btn-light">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Guardar Ambiente
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


<!-- =====================================================
     PANEL: FORMULARIO EDITAR AMBIENTE
     ===================================================== -->
<div id="panelFormularioEditarAmbienteSede" style="display:none;">
  <div style="max-width: 860px; margin: 0 auto;">
    <div class="form-card">

      <!-- Cabecera -->
      <div class="form-card-header">
        <button id="btnRegresarEditarAmbiente" type="button" class="btn-back">
          <i class="bi bi-arrow-left"></i> Regresar
        </button>
        <div class="form-title">
          <div class="form-title-icon">
            <i class="bi bi-pencil-square"></i>
          </div>
          <div>
            <h2 class="form-title-text">Editar Ambiente</h2>
            <p class="form-subtitle-text">Actualiza la información del ambiente</p>
          </div>
        </div>
      </div>

      <!-- Formulario -->
      <form id="formEditarAmbientePorSede" class="needs-validation" novalidate>

        <!-- Campos ocultos -->
        <input type="hidden" id="idAmbienteEdit" />

        <div class="row g-3">

          <!-- Código -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-upc me-1"></i> Código <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              id="codigoEdit"
              class="form-control form-control-soft"
              placeholder="Ej: A101"
              required
            />
            <div class="invalid-feedback">El código es obligatorio.</div>
          </div>

          <!-- Número -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-hash me-1"></i> Número <span class="text-danger">*</span>
            </label>
            <input
              type="number"
              id="numeroEdit"
              class="form-control form-control-soft"
              placeholder="Ej: 101"
              min="1"
              required
            />
            <div class="invalid-feedback">El número es obligatorio.</div>
          </div>

        <div class="col-md-10">
          <label class="form-label">
            <i class="bi bi-tag me-1"></i> Área
          </label>

          <div class="select-wrapper">
            <select class="form-select form-select-lg" id="selectAreasEdit" required>
              <option value="">Cargando áreas...</option>
            </select>
          </div>
        </div>

          <!-- Capacidad -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-people me-1"></i> Capacidad <span class="text-danger">*</span>
            </label>
            <input
              type="number"
              id="capacidadEdit"
              class="form-control form-control-soft"
              placeholder="Ej: 30"
              min="1"
              required
            />
            <div class="invalid-feedback">La capacidad es obligatoria.</div>
          </div>

          <!-- Bloque (antes Ubicación) -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-building me-1"></i> Bloque <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              id="bloqueEdit"
              class="form-control form-control-soft"
              placeholder="Ej: Bloque A"
              required
            />
            <div class="invalid-feedback">El bloque es obligatorio.</div>
          </div>

          <!-- Tipo Ambiente -->
          <div class="mb-3">
          <label class="form-label fw-bold" for="tipoAmbienteEdit">
            <i class="bi bi-tag me-1"></i> Tipo de Ambiente <span class="text-danger">*</span>
          </label>
          <select id="tipoAmbienteEdit" name="tipoAmbiente"
                  class="form-control form-control-soft" required>
            <option value="" disabled>— Seleccione tipo —</option>
            <option value="Formacion">Formación</option>
            <option value="Bilinguismo">Bilingüismo</option>
            <option value="Taller">Taller</option>
          </select>
          <div class="invalid-feedback">Seleccione un tipo de ambiente.</div>
        </div>






          <!-- Estado -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-toggle-on me-1"></i> Estado <span class="text-danger">*</span>
            </label>
            <select id="estadoEdit" class="form-select form-control-soft" required>
              <option value="">Seleccione...</option>
              <option value="ACTIVO">ACTIVO</option>
              <option value="INACTIVO">INACTIVO</option>
            </select>
            <div class="invalid-feedback">Seleccione un estado.</div>
          </div>

          <!-- Descripción -->
          <div class="col-md-10">
            <label class="form-label">
              <i class="bi bi-card-text me-1"></i> Descripción
            </label>
            <input
              type="text"
              id="descripcionEdit"
              class="form-control form-control-soft"
              placeholder="Descripción opcional..."
            />
          </div>

        </div><!-- /row -->

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="button" id="btnCancelarEditarAmbiente" class="btn btn-light">
            <i class="bi bi-x-circle me-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Guardar Cambios
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

</div>



<!-- Cargar primero la clase, luego los eventos -->

