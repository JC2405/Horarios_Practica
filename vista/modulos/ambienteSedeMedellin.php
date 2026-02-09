<?php
require_once __DIR__ . '/menu.php';
?>

<!-- vista/modulos/ambienteSedeMedellin.php -->
<div class="container-fluid p-4">
    <div class="page-header mb-4">
        <h1><i class="fa fa-building"></i> Ambientes Sede Medellín</h1>
        <p class="subtitle">Asigna instructores y fichas a cada ambiente por jornada</p>
    </div>

    <!-- PANEL DE CONTROL -->
    <div class="control-panel">
        <div class="control-group">
            <label for="filtroDisponible"><i class="fa fa-filter"></i> Filtrar Ambientes</label>
            <select id="filtroDisponible" class="form-control">
                <option value="todos">Todos los ambientes</option>
                <option value="disponibles">Solo disponibles</option>
                <option value="ocupados">Solo ocupados</option>
            </select>
        </div>

        <div class="control-group">
            <label for="buscarAmbiente"><i class="fa fa-search"></i> Buscar Ambiente</label>
            <input type="text" id="buscarAmbiente" class="form-control" placeholder="Código, número o descripción...">
        </div>

        <div class="control-group">
            <label for="filtroJornada"><i class="fa fa-clock"></i> Filtrar por Jornada</label>
            <select id="filtroJornada" class="form-control">
                <option value="todas">Todas las jornadas</option>
                <option value="1">Mañana</option>
                <option value="2">Tarde</option>
                <option value="3">Noche</option>
                <option value="4">Sábado</option>
            </select>
        </div>

        <div class="control-group align-self-end">
            <button class="btn btn-success" onclick="guardarHorarios()">
                <i class="fa fa-save"></i> Guardar Horarios
            </button>
            <button class="btn btn-info" onclick="exportarExcel()">
                <i class="fa fa-file-excel"></i> Exportar
            </button>
            <button class="btn btn-secondary" onclick="limpiarTodo()">
                <i class="fa fa-trash"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="stats-panel">
        <div class="stat-card">
            <div class="stat-value" id="statAmbientes">0</div>
            <div class="stat-label">Ambientes Totales</div>
        </div>
        <div class="stat-card stat-disponibles">
            <div class="stat-value" id="statDisponibles">0</div>
            <div class="stat-label">Disponibles</div>
        </div>
        <div class="stat-card stat-ocupados">
            <div class="stat-value" id="statOcupados">0</div>
            <div class="stat-label">Ocupados</div>
        </div>
        <div class="stat-card stat-asignaciones">
            <div class="stat-value" id="statAsignaciones">0</div>
            <div class="stat-label">Asignaciones</div>
        </div>
    </div>

    <!-- LAYOUT PRINCIPAL -->
    <div class="main-layout">
        <!-- SIDEBAR - LISTAS PARA DRAG -->
        <div class="sidebar">
            <!-- INSTRUCTORES -->
            <div class="drag-list-container">
                <h3><i class="fa fa-user-tie"></i> Instructores Disponibles</h3>
                <div class="buscar-instructor mb-2">
                    <input type="text" id="buscarInstructor" class="form-control form-control-sm" 
                           placeholder="Buscar instructor...">
                </div>
                <div id="instructoresList" class="drag-list">
                    <div class="loading">
                        <i class="fa fa-spinner fa-spin"></i> Cargando instructores...
                    </div>
                </div>
            </div>

            <!-- FICHAS -->
            <div class="drag-list-container">
                <h3><i class="fa fa-clipboard-list"></i> Fichas Disponibles</h3>
                <div class="buscar-ficha mb-2">
                    <input type="text" id="buscarFicha" class="form-control form-control-sm" 
                           placeholder="Buscar ficha...">
                </div>
                <div id="fichasList" class="drag-list">
                    <div class="loading">
                        <i class="fa fa-spinner fa-spin"></i> Cargando fichas...
                    </div>
                </div>
            </div>
        </div>

        <!-- ÁREA DE AMBIENTES -->
        <div class="ambientes-area">
            <div id="ambientesGrid" class="ambientes-grid">
                <div class="loading-ambientes">
                    <i class="fa fa-spinner fa-spin fa-3x mb-3"></i>
                    <p>Cargando ambientes de Medellín...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA GESTIONAR TRANSVERSALES MANUALMENTE -->
<div class="modal fade" id="modalTransversales" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fa fa-book"></i> Gestionar Transversales - Ficha <span id="fichaModalCodigo"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Las transversales se programan manualmente según el trimestre y disponibilidad.
                </div>

                <div class="form-group">
                    <label><strong>Ambiente y Jornada Principal:</strong></label>
                    <p id="infoJornadaPrincipal" class="form-control-plaintext"></p>
                </div>

                <div class="form-group">
                    <label for="jornadaTransversal">
                        <i class="fa fa-clock"></i> Jornada para Transversales:
                    </label>
                    <select class="form-control" id="jornadaTransversal">
                        <option value="">-- Seleccionar jornada --</option>
                        <option value="1">Mañana (6:00 - 12:00)</option>
                        <option value="2">Tarde (12:00 - 18:00)</option>
                        <option value="3">Noche (18:00 - 22:00)</option>
                        <option value="4">Sábado (8:00 - 12:00)</option>
                    </select>
                    <small class="form-text text-muted">
                        Generalmente en contra-jornada, pero puedes ajustarlo según necesidad
                    </small>
                </div>

                <div class="form-group">
                    <label for="competenciasTransversal">
                        <i class="fa fa-list"></i> Competencias Transversales:
                    </label>
                    <textarea class="form-control" id="competenciasTransversal" rows="6" 
                              placeholder="Ejemplo:&#10;- Inglés&#10;- Ética&#10;- Cultura Física&#10;- Emprendimiento"></textarea>
                    <small class="form-text text-muted">
                        Ingresa las competencias que se verán en este trimestre, una por línea
                    </small>
                </div>

                <div class="form-group">
                    <label for="trimestreTransversal">
                        <i class="fa fa-calendar"></i> Trimestre:
                    </label>
                    <select class="form-control" id="trimestreTransversal">
                        <option value="1">Trimestre 1</option>
                        <option value="2">Trimestre 2</option>
                        <option value="3">Trimestre 3</option>
                        <option value="4">Trimestre 4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="observacionesTransversal">
                        <i class="fa fa-comment"></i> Observaciones:
                    </label>
                    <textarea class="form-control" id="observacionesTransversal" rows="2" 
                              placeholder="Observaciones adicionales o cambios especiales..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarTransversales()">
                    <i class="fa fa-save"></i> Guardar Transversales
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PARA VER DETALLES DEL AMBIENTE -->
<div class="modal fade" id="modalDetalleAmbiente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fa fa-door-open"></i> Detalle del Ambiente
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalDetalleAmbienteBody">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>


