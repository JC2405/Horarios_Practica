<link href="vista/css/styles.css" rel="stylesheet">

<div class="ficha-wrap">
  <div class="ficha-card">
    <!-- COLUMNA IZQUIERDA -->
    <div class="ficha-left">
      <h3 class="title">Crear Ficha</h3>

      <form id="formCrearFicha">
        <!-- CÓDIGO -->
        <div class="field">
          <label for="codigo">Código</label>
          <input id="codigo" name="codigo" type="text" class="input" placeholder="Ej: FIC-00123" required />
        </div>

        <!-- MUNICIPIO -->
        <div class="field">
          <label>Municipio</label>
          <button type="button" class="pick" id="btnMunicipio" onclick="openPanel('municipio')">
            <span id="txtMunicipio">Seleccionar municipio…</span>
            <small id="hintMunicipio">Click para buscar</small>
          </button>
          <input type="hidden" id="idMunicipio" name="idMunicipio" required />
        </div>

        <!-- SEDE -->
        <div class="field">
          <label>Sede</label>
          <button type="button" class="pick" id="btnSede" onclick="openPanel('sede')" disabled>
            <span id="txtSede">Seleccionar sede…</span>
            <small id="hintSede">Primero elige un municipio</small>
          </button>
          <input type="hidden" id="idSede" name="idSede" required />
        </div>

        <!-- AMBIENTE -->
        <div class="field">
          <label>Ambiente</label>
          <button type="button" class="pick" id="btnAmbiente" onclick="openPanel('ambiente')" disabled>
            <span id="txtAmbiente">Seleccionar ambiente…</span>
            <small id="hintAmbiente">Primero elige una sede</small>
          </button>
          <input type="hidden" id="idAmbiente" name="idAmbiente" required />
        </div>

        <!-- JORNADA -->
        <div class="field">
          <label for="jornada">Jornada</label>
          <select id="jornada" name="jornada" class="input" required>
            <option value="">Seleccionar…</option>
            <option value="MANANA">Mañana</option>
            <option value="TARDE">Tarde</option>
            <option value="NOCHE">Noche</option>
          </select>
        </div>

        <!-- PROGRAMA -->
        <div class="field">
          <label>Programa</label>
          <button type="button" class="pick" id="btnPrograma" onclick="openPanel('programa')">
            <span id="txtPrograma">Seleccionar programa…</span>
            <small id="hintPrograma">Click para buscar</small>
          </button>
          <input type="hidden" id="idPrograma" name="idPrograma" required />
          <input type="hidden" id="duracionMeses" name="duracionMeses" />
          <div class="mini" id="duracionLabel" style="display:none;">Duración: <b id="duracionValue"></b> meses</div>
        </div>

        <!-- FECHAS -->
        <div class="row">
          <div class="field">
            <label for="fecha_inicio">Fecha inicio</label>
            <input id="fecha_inicio" name="fecha_inicio" type="date" class="input" required />
          </div>

          <div class="field">
            <label for="fecha_fin">Fecha fin (auto)</label>
            <input id="fecha_fin" name="fecha_fin" type="date" class="input" readonly />
          </div>
        </div>

        <!-- Acciones -->
        <div class="actions">
          <button type="button" class="btn secondary" onclick="resetFicha()">Limpiar</button>
          <button type="submit" class="btn primary">Guardar ficha</button>
        </div>
      </form>
    </div>

    <!-- COLUMNA DERECHA: PANEL -->
    <div class="ficha-right">
      <div class="panel" id="panel" style="display:none;">
        <div class="panel-head">
          <div>
            <h4 id="panelTitle">Panel</h4>
            <small id="panelSubtitle">Selecciona una opción</small>
          </div>
          <button type="button" class="icon" onclick="closePanel()">✕</button>
        </div>

        <div class="panel-search">
          <input type="text" id="panelSearch" class="input" placeholder="Buscar…" oninput="filterPanel()" />
        </div>

        <div class="panel-list" id="panelList">
          <div class="empty">Haz click en un campo para cargar opciones…</div>
        </div>
      </div>
    </div>
  </div>
</div>

