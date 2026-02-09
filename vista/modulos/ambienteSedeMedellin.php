<?php
// Obtener parámetros de la URL
$idSede = isset($_GET['idSede']) ? $_GET['idSede'] : '';
$nombreSede = isset($_GET['sede']) ? urldecode($_GET['sede']) : 'Sede';
$ciudad = isset($_GET['ciudad']) ? urldecode($_GET['ciudad']) : '';
?>

<!-- Estilos específicos para selección de ambientes -->
<link href="vista/css/ambienteMedellin.css" rel="stylesheet">

<div class="container-fluid py-4">
    <!-- Header con información de la sede -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-building me-2"></i>
                                Paso 2: Selecciona el Ambiente
                            </h4>
                            <p class="mb-0 mt-2 opacity-75 small">
                                📍 <?php echo htmlspecialchars($nombreSede); ?> - <?php echo htmlspecialchars($ciudad); ?>
                            </p>
                        </div>
                        <a href="sedeVista" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Cambiar Sede
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="eleccionSede">1. Seleccionar Sede</a></li>
            <li class="breadcrumb-item active" aria-current="page">2. Ambientes</li>
            <li class="breadcrumb-item text-muted">3. Asignar Jornada</li>
            <li class="breadcrumb-item text-muted">4. Visualizar Fichas</li>
            <li class="breadcrumb-item text-muted">5. Transversales</li>
        </ol>
    </nav>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="bi bi-funnel me-1"></i> Filtrar por Capacidad
            </label>
            <select id="filtroCapacidad" class="form-select">
                <option value="">Todas las capacidades</option>
                <option value="1-20">1 - 20 personas</option>
                <option value="21-30">21 - 30 personas</option>
                <option value="31-40">31 - 40 personas</option>
                <option value="41+">Más de 40 personas</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="bi bi-search me-1"></i> Buscar
            </label>
            <input type="text" id="buscarAmbiente" class="form-control" placeholder="Código, número o descripción...">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">
                <i class="bi bi-info-circle me-1"></i> Estado
            </label>
            <select id="filtroEstado" class="form-select">
                <option value="">Todos</option>
                <option value="activo">Activos</option>
                <option value="inactivo">Inactivos</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-secondary-custom w-100" onclick="limpiarFiltros()">
                <i class="bi bi-x-circle me-1"></i> Limpiar Filtros
            </button>
        </div>
    </div>

    <!-- Tabla de Ambientes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaAmbienteMedellin" class="table table-hover table-striped" style="width:100%">
                            <thead class="table-custom-header">
                                <tr>
                                    <th>CÓDIGO</th>
                                    <th>CAPACIDAD</th>
                                    <th>AMBIENTE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>NÚMERO</th>
                                    <th>ESTADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llena dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Información del Ambiente -->
<div class="modal fade" id="modalInfoAmbiente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-custom text-white">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>
                    Información del Ambiente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoModalAmbiente">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para selección de ambientes -->
<script>
// Inicializar el módulo de selección de ambientes con datos PHP
document.addEventListener('DOMContentLoaded', function() {
    inicializarAmbienteSedeMedellin({
        idSede: <?php echo json_encode($idSede); ?>,
        nombreSede: <?php echo json_encode($nombreSede); ?>,
        ciudad: <?php echo json_encode($ciudad); ?>
    });
});
</script>
