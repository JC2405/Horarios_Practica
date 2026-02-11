
<!-- CSS Consolidado -->
<link href="vista/css/styles.css" rel="stylesheet">

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
    <button id="agregarSede" class="btn-add">
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
    </table>
  </div>
</div>
  <div id="panelFormularioSede" class="form-card" style="display: none;">
  <div class="form-card-header">
    <button id="btnRegresarTablaSede" type="button" class="btn-back">
      <a class="bi bi-arrow-left" href="sede"></a>
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
        <!-- aquí cargas los municipios desde BD -->
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

</div>