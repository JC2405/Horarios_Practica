<?php
// Obtener parámetros de la URL
$idSede = isset($_GET['idSede']) ? $_GET['idSede'] : '';
$nombreSede = isset($_GET['sede']) ? urldecode($_GET['sede']) : 'Sede';
$ciudad = isset($_GET['ciudad']) ? urldecode($_GET['ciudad']) : '';
$idAmbiente = isset($_GET['idAmbiente']) ? $_GET['idAmbiente'] : '';
$ambiente = isset($_GET['ambiente']) ? urldecode($_GET['ambiente']) : 'Ambiente';
?>

<link href="vista/css/styles.css" rel="stylesheet">

<div class="container-fluid py-4">
    <!-- Header con información del ambiente -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-custom text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-door-closed me-2"></i>
                                Paso 3: Asignar Jornadas - <?php echo htmlspecialchars($ambiente); ?>
                            </h4>
                            <p class="mb-0 mt-2 opacity-75 small">
                                📍 <?php echo htmlspecialchars($nombreSede); ?> - <?php echo htmlspecialchars($ciudad); ?>
                            </p>
                        </div>
                        <a href="eleccionAmbiente?idSede=<?php echo $idSede; ?>&sede=<?php echo urlencode($nombreSede); ?>&ciudad=<?php echo urlencode($ciudad); ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Cambiar Ambiente
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
            <li class="breadcrumb-item"><a href="eleccionAmbiente?idSede=<?php echo $idSede; ?>&sede=<?php echo urlencode($nombreSede); ?>&ciudad=<?php echo urlencode($ciudad); ?>">2. Ambientes</a></li>
            <li class="breadcrumb-item active" aria-current="page">3. Asignar Jornada</li>
            <li class="breadcrumb-item text-muted">4. Visualizar Fichas</li>
            <li class="breadcrumb-item text-muted">5. Transversales</li>
        </ol>
    </nav>

    <!-- Información del Ambiente -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-12">
                        <strong><i class="bi bi-info-circle me-2"></i>Información del Ambiente:</strong>
                        <span id="ambienteInfo">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Jornadas (Swipeable) -->
    <ul class="nav nav-pills nav-fill mb-4" id="jornadaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manana-tab" data-bs-toggle="pill" data-bs-target="#manana" type="button" role="tab">
                <i class="bi bi-sunrise me-2"></i>
                <strong>MAÑANA</strong>
                <br><small class="opacity-75">6:00 AM - 12:00 PM</small>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tarde-tab" data-bs-toggle="pill" data-bs-target="#tarde" type="button" role="tab">
                <i class="bi bi-sun me-2"></i>
                <strong>TARDE</strong>
                <br><small class="opacity-75">12:00 PM - 6:00 PM</small>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="noche-tab" data-bs-toggle="pill" data-bs-target="#noche" type="button" role="tab">
                <i class="bi bi-moon-stars me-2"></i>
                <strong>NOCHE</strong>
                <br><small class="opacity-75">6:00 PM - 10:00 PM</small>
            </button>
        </li>
    </ul>

    <!-- Contenido de las Jornadas -->
    <div class="tab-content" id="jornadaTabContent">
        
        <!-- MAÑANA -->
        <div class="tab-pane fade show active" id="manana" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-sunrise text-warning me-2"></i>
                        Asignación Jornada Mañana
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-video3 me-1"></i> Seleccionar Instructor
                            </label>
                            <select class="form-select" id="instructorManana" data-jornada="manana">
                                <option value="">-- Seleccione un instructor --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-clipboard-check me-1"></i> Seleccionar Ficha
                            </label>
                            <select class="form-select" id="fichaManana" data-jornada="manana">
                                <option value="">-- Seleccione una ficha --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success btn-sm float-end" onclick="guardarAsignacion('manana')">
                                <i class="bi bi-check-circle me-1"></i> Guardar Asignación
                            </button>
                        </div>
                    </div>

                    <!-- Vista previa de asignaciones guardadas -->
                    <div class="mt-4" id="asignacionesManana">
                        <h6 class="text-muted">Asignaciones guardadas:</h6>
                        <div id="listaAsignacionesManana" class="alert alert-secondary">
                            No hay asignaciones guardadas para esta jornada
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TARDE -->
        <div class="tab-pane fade" id="tarde" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-sun text-info me-2"></i>
                        Asignación Jornada Tarde
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-video3 me-1"></i> Seleccionar Instructor
                            </label>
                            <select class="form-select" id="instructorTarde" data-jornada="tarde">
                                <option value="">-- Seleccione un instructor --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-clipboard-check me-1"></i> Seleccionar Ficha
                            </label>
                            <select class="form-select" id="fichaTarde" data-jornada="tarde">
                                <option value="">-- Seleccione una ficha --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success btn-sm float-end" onclick="guardarAsignacion('tarde')">
                                <i class="bi bi-check-circle me-1"></i> Guardar Asignación
                            </button>
                        </div>
                    </div>

                    <div class="mt-4" id="asignacionesTarde">
                        <h6 class="text-muted">Asignaciones guardadas:</h6>
                        <div id="listaAsignacionesTarde" class="alert alert-secondary">
                            No hay asignaciones guardadas para esta jornada
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOCHE -->
        <div class="tab-pane fade" id="noche" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-moon-stars text-primary me-2"></i>
                        Asignación Jornada Noche
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-person-video3 me-1"></i> Seleccionar Instructor
                            </label>
                            <select class="form-select" id="instructorNoche" data-jornada="noche">
                                <option value="">-- Seleccione un instructor --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-clipboard-check me-1"></i> Seleccionar Ficha
                            </label>
                            <select class="form-select" id="fichaNoche" data-jornada="noche">
                                <option value="">-- Seleccione una ficha --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-success btn-sm float-end" onclick="guardarAsignacion('noche')">
                                <i class="bi bi-check-circle me-1"></i> Guardar Asignación
                            </button>
                        </div>
                    </div>

                    <div class="mt-4" id="asignacionesNoche">
                        <h6 class="text-muted">Asignaciones guardadas:</h6>
                        <div id="listaAsignacionesNoche" class="alert alert-secondary">
                            No hay asignaciones guardadas para esta jornada
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para crear horario -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <button class="btn btn-primary-custom btn-lg" onclick="irACrearHorario()">
                <i class="bi bi-calendar-plus me-2"></i>
                CREAR HORARIO
            </button>
        </div>
    </div>
</div>

<script>
(function(){
    console.log('🎯 Módulo: Asignación por Jornada');
    
    const idSede = <?php echo json_encode($idSede); ?>;
    const nombreSede = <?php echo json_encode($nombreSede); ?>;
    const ciudad = <?php echo json_encode($ciudad); ?>;
    const idAmbiente = <?php echo json_encode($idAmbiente); ?>;
    const ambiente = <?php echo json_encode($ambiente); ?>;
    
    let instructores = [];
    let fichas = [];
    let asignaciones = {
        manana: null,
        tarde: null,
        noche: null
    };

    // Cargar datos iniciales
    cargarAmbienteInfo();
    cargarInstructores();
    cargarFichas();
    cargarAsignacionesExistentes();

    // Función para cargar información del ambiente
    function cargarAmbienteInfo(){
        fetch("controlador/ambienteControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `listarAmbientesPorSede=ok&idSede=${idSede}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.codigo === "200"){
                const amb = data.ambientes.find(a => a.idAmbiente == idAmbiente);
                if(amb){
                    document.getElementById('ambienteInfo').innerHTML = `
                        <strong>Código:</strong> ${amb.codigo} | 
                        <strong>Número:</strong> ${amb.numero} | 
                        <strong>Capacidad:</strong> ${amb.capacidad} personas | 
                        <strong>Descripción:</strong> ${amb.descripcion}
                    `;
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Función para cargar instructores
    function cargarInstructores(){
        fetch("controlador/instructorControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "listarInstructor=ok"
        })
        .then(response => response.json())
        .then(data => {
            if(data.codigo === "200"){
                instructores = data.listarInstructor;
                renderizarInstructores();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function renderizarInstructores(){
        const selectores = ['instructorManana', 'instructorTarde', 'instructorNoche'];
        
        selectores.forEach(id => {
            const select = document.getElementById(id);
            select.innerHTML = '<option value="">-- Seleccione un instructor --</option>';
            
            instructores.forEach(instructor => {
                const option = document.createElement('option');
                option.value = instructor.idFuncionario || instructor.idInstructor;
                option.textContent = `${instructor.nombre} - ${instructor.nombreArea || 'Sin área'}`;
                option.dataset.nombre = instructor.nombre;
                select.appendChild(option);
            });
        });
    }

    // Función para cargar fichas
    function cargarFichas(){
        fetch("controlador/fichaControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "listarTecnologos=ok"
        })
        .then(response => response.json())
        .then(data => {
            if(data.codigo === "200"){
                fichas = data.listarTecnologos;
                renderizarFichas();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function renderizarFichas(){
        const selectores = ['fichaManana', 'fichaTarde', 'fichaNoche'];
        
        selectores.forEach(id => {
            const select = document.getElementById(id);
            select.innerHTML = '<option value="">-- Seleccione una ficha --</option>';
            
            fichas.forEach(ficha => {
                const option = document.createElement('option');
                option.value = ficha.idFicha;
                option.textContent = `${ficha.codigoFicha} - ${ficha.programa}`;
                option.dataset.codigo = ficha.codigoFicha;
                select.appendChild(option);
            });
        });
    }

    // Función para cargar asignaciones existentes
    function cargarAsignacionesExistentes(){
        fetch("controlador/asignacionControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `listarAsignaciones=ok`
        })
        .then(response => response.json())
        .then(data => {
            if(data.codigo === "200"){
                // Filtrar asignaciones de este ambiente
                const asignacionesAmbiente = data.asignaciones.filter(a => a.idAmbiente == idAmbiente);
                
                asignacionesAmbiente.forEach(asig => {
                    asignaciones[asig.jornada] = asig;
                    mostrarAsignacionGuardada(asig.jornada, asig);
                });
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Función global: Guardar asignación
    window.guardarAsignacion = function(jornada){
        const instructorSelect = document.getElementById(`instructor${capitalize(jornada)}`);
        const fichaSelect = document.getElementById(`ficha${capitalize(jornada)}`);
        
        const idInstructor = instructorSelect.value;
        const idFicha = fichaSelect.value;
        
        if(!idInstructor || !idFicha){
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Debes seleccionar un instructor y una ficha'
            });
            return;
        }

        const datos = new URLSearchParams({
            crearAsignacion: 'ok',
            idAmbiente: idAmbiente,
            idFicha: idFicha,
            idInstructor: idInstructor,
            jornada: jornada
        });

        fetch("controlador/asignacionControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if(data.codigo === "200"){
                Swal.fire({
                    icon: 'success',
                    title: '¡Guardado!',
                    text: 'Asignación guardada correctamente',
                    timer: 2000
                });
                
                // Guardar en estado local
                asignaciones[jornada] = {
                    idAmbiente: idAmbiente,
                    idFicha: idFicha,
                    idInstructor: idInstructor,
                    jornada: jornada,
                    nombreInstructor: instructorSelect.options[instructorSelect.selectedIndex].dataset.nombre,
                    codigoFicha: fichaSelect.options[fichaSelect.selectedIndex].dataset.codigo
                };
                
                mostrarAsignacionGuardada(jornada, asignaciones[jornada]);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.mensaje || 'Error al guardar la asignación'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al servidor'
            });
        });
    };

    function mostrarAsignacionGuardada(jornada, asignacion){
        const contenedor = document.getElementById(`listaAsignaciones${capitalize(jornada)}`);
        contenedor.className = 'alert alert-success';
        contenedor.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-check-circle me-2"></i>Asignación activa:</strong><br>
                    <small>
                        👨‍🏫 Instructor: ${asignacion.nombreInstructor || 'N/A'}<br>
                        📋 Ficha: ${asignacion.codigoFicha || 'N/A'}
                    </small>
                </div>
                <button class="btn btn-sm btn-danger" onclick="eliminarAsignacion('${jornada}', ${asignacion.idAsignacion || 'null'})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }

    // Función global: Eliminar asignación
    window.eliminarAsignacion = function(jornada, idAsignacion){
        if(!idAsignacion){
            return;
        }

        Swal.fire({
            title: '¿Eliminar asignación?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("controlador/asignacionControlador.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `eliminarAsignacion=ok&idAsignacion=${idAsignacion}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.codigo === "200"){
                        asignaciones[jornada] = null;
                        const contenedor = document.getElementById(`listaAsignaciones${capitalize(jornada)}`);
                        contenedor.className = 'alert alert-secondary';
                        contenedor.innerHTML = 'No hay asignaciones guardadas para esta jornada';
                        
                        Swal.fire('Eliminado', 'La asignación ha sido eliminada', 'success');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    };

    // Función global: Ir a crear horario
    window.irACrearHorario = function(){
        // Verificar que haya al menos una asignación
        const hayAsignaciones = Object.values(asignaciones).some(a => a !== null);
        
        if(!hayAsignaciones){
            Swal.fire({
                icon: 'warning',
                title: 'Sin asignaciones',
                text: 'Debes guardar al menos una asignación de jornada antes de crear el horario'
            });
            return;
        }

        // Redirigir a crear horario con parámetros
        const params = new URLSearchParams({
            idSede: idSede,
            sede: nombreSede,
            ciudad: ciudad,
            idAmbiente: idAmbiente,
            ambiente: ambiente
        });
        
        window.location.href = `crearHorario?${params.toString()}`;
    };

    function capitalize(str){
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

})();
</script>

<style>
.breadcrumb {
    background: white;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(124, 107, 255, 0.1);
}

.breadcrumb-item.active {
    color: var(--primary);
    font-weight: 600;
}

.breadcrumb-item a {
    color: var(--primary);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, var(--primary) 0%, #9d8fff 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(124, 107, 255, 0.35);
}

.nav-pills .nav-link:hover:not(.active) {
    background: var(--primary-light);
    color: var(--primary);
}
</style>
