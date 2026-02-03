<div class="container-fluid py-4">
    <!-- Panel de selección de ficha y configuración -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Configuración del Horario</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <!-- Información de la ficha -->
                        <div class="col-md-12">
                            <div id="infoFicha" class="alert alert-info mb-3 py-2">
                                <div class="row">
                                    <div class="col-md-3"><small><strong>Código:</strong> <span id="fichaCodigo"></span></small></div>
                                    <div class="col-md-3"><small><strong>Ciudad:</strong> <span id="fichaCiudad"></span></small></div>
                                    <div class="col-md-3"><small><strong>Sede:</strong> <span id="fichaSede"></span></small></div>
                                    <div class="col-md-3"><small><strong>Programa:</strong> <span id="fichaPrograma"></span></small></div>
                                    <div class="col-md-3"><small><strong>Jornada:</strong> <span id="fichaJornada"></span></small></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rango de fechas -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Rango del Horario:</label>
                            <div class="input-group">
                                <input type="date" id="fechaInicio" class="form-control">
                                <span class="input-group-text">hasta</span>
                                <input type="date" id="fechaFin" class="form-control">
                            </div>
                        </div>

                        <!-- 🔥 NUEVO: Selector de días de la semana -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Días de la Semana:</label>
                            <div id="selectorDias" class="dias-selector">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="col-md-4 text-end mb-3">
                            <button type="button" class="btn btn-success btn-lg" id="btnGuardarHorario">
                                <i class="fas fa-save me-2"></i>Guardar Horario
                            </button>
                            <button type="button" class="btn btn-danger btn-lg ms-2" id="btnLimpiarHorario">
                                <i class="fas fa-trash me-2"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panel lateral con Instructores y Ambientes -->
        <div class="col-md-3">
            <!-- Sección de Instructores -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Instructores</h5>
                    <span class="badge bg-light text-primary" id="countInstructores">0</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="buscarInstructor" class="form-control" 
                               placeholder="Buscar instructor...">
                    </div>
                    <div id="listaInstructores" class="element-list"></div>
                </div>
            </div>

            <!-- Sección de Ambientes -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-door-open me-2"></i>Ambientes</h5>
                    <span class="badge bg-light text-success" id="countAmbientes">0</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="buscarAmbiente" class="form-control" 
                               placeholder="Buscar ambiente...">
                    </div>
                    <div id="listaAmbientes" class="element-list">
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Selecciona una ficha para ver los ambientes disponibles
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Contador de eventos pendientes -->
                    <div class="alert alert-warning mb-3" id="alertEventosPendientes" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Eventos pendientes:</strong> <span id="countEventosPendientes">0</span> horarios sin guardar
                    </div>
                    <div id="calendario"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles del horario -->
<div class="modal fade" id="modalDetalleHorario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalle del Horario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detalleHorarioContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos -->
<style>
/* ========== SELECTOR DE DÍAS ========== */
.dias-selector {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.dia-checkbox {
    display: none;
}

.dia-label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.3s ease;
    background: white;
    color: #6c757d;
    user-select: none;
}

.dia-label:hover {
    border-color: #0d6efd;
    color: #0d6efd;
    transform: scale(1.05);
}

.dia-checkbox:checked + .dia-label {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
}

.dia-checkbox:disabled + .dia-label {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ========== RESTO DE ESTILOS ========== */
.element-list {
    max-height: 300px;
    overflow-y: auto;
}

.instructor-item, .ambiente-item {
    padding: 10px 15px;
    margin-bottom: 8px;
    border-radius: 8px;
    cursor: grab;
    transition: transform 0.2s, box-shadow 0.2s;
    font-weight: 500;
}

.instructor-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.ambiente-item {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: #1a1a2e;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ambiente-item.selected {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: 3px solid #1a1a2e;
    transform: scale(1.02);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.instructor-item:hover, .ambiente-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.instructor-item:active, .ambiente-item:active {
    cursor: grabbing;
}

.instructor-item.oculto, .ambiente-item.oculto {
    display: none;
}

.instructor-item[data-color-index="1"] { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.instructor-item[data-color-index="2"] { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.instructor-item[data-color-index="3"] { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.instructor-item[data-color-index="4"] { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.instructor-item[data-color-index="5"] { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.instructor-item[data-color-index="6"] { background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%); }
.instructor-item[data-color-index="7"] { background: linear-gradient(135deg, #45b7d1 0%, #96c93d 100%); }
.instructor-item[data-color-index="8"] { background: linear-gradient(135deg, #f7797d 0%, #fbd786 100%); }

#calendario {
    min-height: 600px;
}

.fc-event {
    cursor: pointer;
    border-radius: 4px;
    opacity: 0.9;
}

.fc-event.pendiente {
    border-left: 4px solid #ffc107 !important;
}
</style>

<!-- CSS de FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<!-- Scripts -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>

<script>
function obtenerParametroUrl(nombre) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(nombre);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Iniciando sistema de horarios con días de la semana...');
    
    if (typeof FullCalendar === 'undefined') {
        console.error('❌ FullCalendar no está cargado');
        return;
    }
    
    // ========== VARIABLES GLOBALES ==========
    let calendario;
    let instructores = [];
    let ambientes = [];
    let diasSemana = [];
    let fichaSeleccionada = null;
    let ambienteSeleccionado = null;
    let diasSeleccionados = []; // 🔥 NUEVO: Días seleccionados
    let eventosPendientes = [];
    let draggableInstance = null;
    const colores = ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#fa709a', '#ff6b6b', '#4ecdc4', '#45b7d1'];
    
    // ========== CARGAR DÍAS DE LA SEMANA ==========
    function cargarDiasSemana() {
        return new Promise((resolve, reject) => {
            fetch('controlador/horarioControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarDias=ok'
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    diasSemana = data.dias;
                    renderizarSelectorDias(diasSemana);
                    resolve();
                } else {
                    reject('Error al cargar días');
                }
            })
            .catch(error => {
                console.error('❌ Error al cargar días:', error);
                reject(error);
            });
        });
    }
    
    // ========== RENDERIZAR SELECTOR DE DÍAS ==========
    function renderizarSelectorDias(dias) {
        const contenedor = document.getElementById('selectorDias');
        contenedor.innerHTML = '';
        
        // Mapeo de nombres cortos
        const nombresCortos = {
            'Lunes': 'L',
            'Martes': 'M',
            'Miércoles': 'X',
            'Miercoles': 'X',
            'Jueves': 'J',
            'Viernes': 'V',
            'Sábado': 'S',
            'Sabado': 'S',
            'Domingo': 'D'
        };
        
        dias.forEach(dia => {
            const nombreCorto = nombresCortos[dia.diasSemanales] || dia.diasSemanales.charAt(0);
            
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <input type="checkbox" 
                       class="dia-checkbox" 
                       id="dia-${dia.idDia}" 
                       value="${dia.idDia}">
                <label class="dia-label" 
                       for="dia-${dia.idDia}" 
                       title="${dia.diasSemanales}">
                    ${nombreCorto}
                </label>
            `;
            
            contenedor.appendChild(wrapper);
            
            // Event listener para actualizar diasSeleccionados
            const checkbox = wrapper.querySelector('.dia-checkbox');
            checkbox.addEventListener('change', function() {
                actualizarDiasSeleccionados();
            });
        });
    }
    
    // ========== ACTUALIZAR DÍAS SELECCIONADOS ==========
    function actualizarDiasSeleccionados() {
        diasSeleccionados = Array.from(document.querySelectorAll('.dia-checkbox:checked'))
            .map(cb => parseInt(cb.value));
        
        console.log('📅 Días seleccionados:', diasSeleccionados);
    }
    
    // ========== CARGAR FICHA DESDE URL ==========
    function cargarFichaDesdeUrl() {
        const fichaParam = obtenerParametroUrl('ficha');
        if (!fichaParam) {
            return Promise.resolve();
        }
        
        return new Promise((resolve, reject) => {
            fetch('controlador/fichaControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarTecnologos=ok'
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    const fichaEncontrada = data.listarTecnologos.find(f => f.codigoFicha === fichaParam);
                    if (fichaEncontrada) {
                        fichaSeleccionada = {
                            id: fichaEncontrada.idFicha,
                            codigo: fichaEncontrada.codigoFicha,
                            programa: fichaEncontrada.programa,
                            jornada: fichaEncontrada.jornada,
                            municipio: fichaEncontrada.municipio,
                            sede: fichaEncontrada.sede,
                            idSede: fichaEncontrada.idSede
                        };
                        
                        document.getElementById('infoFicha').style.display = 'block';
                        document.getElementById('fichaCodigo').textContent = fichaSeleccionada.codigo;
                        document.getElementById('fichaCiudad').textContent = fichaSeleccionada.municipio;
                        document.getElementById('fichaSede').textContent = fichaSeleccionada.sede || 'No especificada';
                        document.getElementById('fichaPrograma').textContent = fichaSeleccionada.programa;
                        document.getElementById('fichaJornada').textContent = fichaSeleccionada.jornada;
                        
                        cargarAmbientesPorSede(fichaSeleccionada.idSede);
                        resolve();
                    } else {
                        reject('Ficha no encontrada');
                    }
                } else {
                    reject('Error al cargar fichas');
                }
            })
            .catch(reject);
        });
    }
    
    // ========== CARGAR INSTRUCTORES ==========
    function cargarInstructores() {
        return new Promise((resolve, reject) => {
            fetch('controlador/instructorControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarInstructor=ok'
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    instructores = data.listarInstructor;
                    renderizarInstructores(instructores);
                    inicializarDraggable();
                    resolve();
                } else {
                    reject('Error al cargar instructores');
                }
            })
            .catch(reject);
        });
    }
    
    // ========== CARGAR AMBIENTES POR SEDE ==========
    function cargarAmbientesPorSede(idSede) {
        fetch('controlador/ambienteControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'listarAmbientesPorSede=ok&idSede=' + encodeURIComponent(idSede)
        })
        .then(response => response.json())
        .then(data => {
            if (data.codigo === "200") {
                ambientes = data.ambientes;
                renderizarAmbientes(ambientes);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // ========== RENDERIZAR INSTRUCTORES ==========
    function renderizarInstructores(lista) {
        const contenedor = document.getElementById('listaInstructores');
        contenedor.innerHTML = '';
        
        lista.forEach((instructor, index) => {
            const colorIndex = (index % 8) + 1;
            const div = document.createElement('div');
            div.className = 'instructor-item fc-event';
            div.setAttribute('data-type', 'instructor');
            div.setAttribute('data-id', instructor.idFuncionario || instructor.idInstructor);
            div.setAttribute('data-nombre', instructor.nombre);
            div.setAttribute('data-color', colores[index % colores.length]);
            div.setAttribute('data-color-index', colorIndex);
            div.innerHTML = `<i class="fas fa-user-tie me-2"></i>${instructor.nombre}`;
            contenedor.appendChild(div);
        });
        
        document.getElementById('countInstructores').textContent = lista.length;
    }
    
    // ========== RENDERIZAR AMBIENTES ==========
    function renderizarAmbientes(lista) {
        const contenedor = document.getElementById('listaAmbientes');
        contenedor.innerHTML = '';
        
        if (lista.length === 0) {
            contenedor.innerHTML = '<div class="text-muted text-center py-3">No hay ambientes disponibles</div>';
            return;
        }
        
        lista.forEach((ambiente, index) => {
            const div = document.createElement('div');
            div.className = 'ambiente-item';
            div.setAttribute('data-id', ambiente.idAmbiente);
            div.setAttribute('data-nombre', `Ambiente ${ambiente.codigo}`);
            div.innerHTML = `
                <i class="fas fa-door-open me-2"></i>
                <strong>${ambiente.codigo}</strong>
                <br><small>${ambiente.descripcion || ''}</small>
            `;
            
            div.addEventListener('click', function() {
                document.querySelectorAll('.ambiente-item').forEach(item => item.classList.remove('selected'));
                div.classList.add('selected');
                ambienteSeleccionado = {
                    idAmbiente: ambiente.idAmbiente,
                    codigo: ambiente.codigo
                };
            });
            contenedor.appendChild(div);
        });
        
        document.getElementById('countAmbientes').textContent = lista.length;
    }
    
    // ========== INICIALIZAR DRAGGABLE ==========
    function inicializarDraggable() {
        if (draggableInstance) {
            draggableInstance.destroy();
            draggableInstance = null;
        }
        
        const contenedorInstructores = document.getElementById('listaInstructores');
        
        draggableInstance = new FullCalendar.Draggable(contenedorInstructores, {
            itemSelector: '.instructor-item',
            eventData: function(eventEl) {
                return {
                    title: eventEl.getAttribute('data-nombre'),
                    backgroundColor: eventEl.getAttribute('data-color'),
                    borderColor: eventEl.getAttribute('data-color'),
                    duration: '01:00',
                    extendedProps: {
                        type: 'instructor',
                        idFuncionario: eventEl.getAttribute('data-id'),
                        idAmbiente: ambienteSeleccionado?.idAmbiente || null,
                        idFicha: fichaSeleccionada?.id || null,
                        nombreInstructor: eventEl.getAttribute('data-nombre'),
                        dias: [...diasSeleccionados], // 🔥 Copiar días seleccionados
                        pendiente: true
                    }
                };
            }
        });
    }
    
    // ========== BUSCAR INSTRUCTOR ==========
    document.getElementById('buscarInstructor').addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase();
        document.querySelectorAll('.instructor-item').forEach(item => {
            const nombre = item.getAttribute('data-nombre').toLowerCase();
            item.classList.toggle('oculto', !nombre.includes(termino));
        });
    });
    
    // ========== BUSCAR AMBIENTE ==========
    document.getElementById('buscarAmbiente').addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase();
        document.querySelectorAll('.ambiente-item').forEach(item => {
            const nombre = item.getAttribute('data-nombre').toLowerCase();
            item.classList.toggle('oculto', !nombre.includes(termino));
        });
    });
    
    // ========== ACTUALIZAR CONTADOR PENDIENTES ==========
    function actualizarContadorPendientes() {
        const contador = eventosPendientes.length;
        document.getElementById('countEventosPendientes').textContent = contador;
        document.getElementById('alertEventosPendientes').style.display = contador > 0 ? 'block' : 'none';
    }
    
    // ========== INICIALIZAR CALENDARIO ==========
    const calendarEl = document.getElementById('calendario');
    calendario = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día'
        },
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: false,
        editable: true,
        droppable: true,
        selectable: true,
        selectMirror: true,
        nowIndicator: true,
        slotDuration: '00:30:00',
        weekends: true,
        
        eventReceive: function(info) {
            if (!fichaSeleccionada) {
                info.event.remove();
                mostrarNotificacion('Selecciona una ficha primero', 'warning');
                return;
            }
            
            // 🔥 Validar que haya días seleccionados
            if (!info.event.extendedProps.dias || info.event.extendedProps.dias.length === 0) {
                info.event.remove();
                mostrarNotificacion('Debes seleccionar al menos un día de la semana', 'warning');
                return;
            }
            
            const eventData = {
                tempId: 'temp_' + Date.now(),
                title: info.event.title,
                start: info.event.start,
                end: info.event.end,
                extendedProps: info.event.extendedProps,
                backgroundColor: info.event.backgroundColor,
                borderColor: info.event.borderColor
            };
            
            eventosPendientes.push(eventData);
            info.event.setProp('classNames', ['pendiente']);
            info.event.setExtendedProp('tempId', eventData.tempId);
            actualizarContadorPendientes();
            
            console.log('✅ Evento agregado. Días:', info.event.extendedProps.dias);
        },
        
        eventDrop: function(info) {
            if (info.event.extendedProps.pendiente) {
                const index = eventosPendientes.findIndex(e => e.tempId === info.event.extendedProps.tempId);
                if (index !== -1) {
                    eventosPendientes[index].start = info.event.start;
                    eventosPendientes[index].end = info.event.end;
                }
            }
        },
        
        eventResize: function(info) {
            if (info.event.extendedProps.pendiente) {
                const index = eventosPendientes.findIndex(e => e.tempId === info.event.extendedProps.tempId);
                if (index !== -1) {
                    eventosPendientes[index].end = info.event.end;
                }
            }
        },
        
        eventClick: function(info) {
            mostrarDetalleHorario(info.event);
        },
        
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('controlador/horarioControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarHorarios=ok'
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    const eventos = data.horarios.map(h => {
                        // Convertir string de IDs a array
                        const diasArray = h.dias ? h.dias.split(',').map(d => parseInt(d)) : [];
                        
                        return {
                            id: h.idHorario,
                            title: h.instructorNombre || `Ambiente ${h.ambienteNumero}` || 'Evento',
                            start: h.fecha_inicioClase || h.hora_inicioClase,
                            end: h.hora_finClase,
                            backgroundColor: '#3788d8',
                            borderColor: '#3788d8',
                            extendedProps: {
                                idFuncionario: h.idFuncionario,
                                idAmbiente: h.idAmbiente,
                                idFicha: h.idFicha,
                                instructor: h.instructorNombre,
                                ambiente: h.ambienteNumero,
                                codigoFicha: h.codigoFicha,
                                dias: diasArray,
                                diasNombres: h.diasNombres,
                                pendiente: false
                            }
                        };
                    });
                    successCallback(eventos);
                } else {
                    successCallback([]);
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                failureCallback(error);
            });
        }
    });
    
    // ========== BOTÓN GUARDAR HORARIO ==========
    document.getElementById('btnGuardarHorario').addEventListener('click', function() {
        if (eventosPendientes.length === 0) {
            mostrarNotificacion('No hay eventos para guardar', 'warning');
            return;
        }
        
        if (!confirm(`¿Guardar ${eventosPendientes.length} horarios?`)) {
            return;
        }
        
        const fechaInicio = document.getElementById('fechaInicio').value || null;
        const fechaFin = document.getElementById('fechaFin').value || null;
        
        let guardados = 0;
        let errores = 0;
        
        const promesas = eventosPendientes.map(eventData => {
            const horaInicio = formatearHoraMySQL(new Date(eventData.start));
            const horaFin = eventData.end ? formatearHoraMySQL(new Date(eventData.end)) : null;
            
            // 🔥 IMPORTANTE: Enviar días como JSON
            const datos = new URLSearchParams({
                crearHorario: 'ok',
                idFuncionario: eventData.extendedProps.idFuncionario || '',
                idAmbiente: eventData.extendedProps.idAmbiente || '',
                idFicha: fichaSeleccionada.id,
                hora_inicioClase: horaInicio,
                hora_finClase: horaFin,
                fecha_inicioHorario: fechaInicio || '',
                fecha_finHorario: fechaFin || '',
                dias: JSON.stringify(eventData.extendedProps.dias) // 🔥 Array de días
            });

            return fetch('controlador/horarioControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    guardados++;
                    const eventoCalendario = calendario.getEvents().find(e => 
                        e.extendedProps.tempId === eventData.tempId
                    );
                    if (eventoCalendario) {
                        eventoCalendario.setProp('id', data.idHorario);
                        eventoCalendario.setExtendedProp('pendiente', false);
                        eventoCalendario.setProp('classNames', []);
                    }
                    return { success: true };
                } else {
                    errores++;
                    console.error('❌ Error:', data.mensaje);
                    return { success: false };
                }
            })
            .catch(error => {
                errores++;
                console.error('❌ Error de red:', error);
                return { success: false };
            });
        });
        
        Promise.all(promesas).then(() => {
            eventosPendientes = [];
            actualizarContadorPendientes();
            
            if (errores === 0) {
                mostrarNotificacion(`✅ ${guardados} horarios guardados`, 'success');
            } else {
                mostrarNotificacion(`⚠️ ${guardados} guardados, ${errores} errores`, 'warning');
            }
            
            calendario.refetchEvents();
        });
    });
    
    // ========== BOTÓN LIMPIAR ==========
    document.getElementById('btnLimpiarHorario').addEventListener('click', function() {
        if (eventosPendientes.length === 0) {
            mostrarNotificacion('No hay eventos para limpiar', 'warning');
            return;
        }
        
        if (!confirm(`¿Eliminar ${eventosPendientes.length} eventos pendientes?`)) {
            return;
        }
        
        eventosPendientes.forEach(eventData => {
            const eventoCalendario = calendario.getEvents().find(e => 
                e.extendedProps.tempId === eventData.tempId
            );
            if (eventoCalendario) {
                eventoCalendario.remove();
            }
        });
        
        eventosPendientes = [];
        actualizarContadorPendientes();
        mostrarNotificacion('🗑️ Eventos eliminados', 'success');
    });
    
    // ========== FORMATEAR HORA MYSQL ==========
    function formatearHoraMySQL(fecha) {
        const hours = String(fecha.getHours()).padStart(2, '0');
        const minutes = String(fecha.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}:00`;
    }
    
    // ========== MOSTRAR DETALLE HORARIO ==========
    function mostrarDetalleHorario(evento) {
        const props = evento.extendedProps;
        let contenido = `
            <div class="mb-3">
                <strong><i class="fas fa-heading me-2"></i>Evento:</strong> ${evento.title}
            </div>
            <div class="mb-3">
                <strong><i class="fas fa-clock me-2"></i>Hora:</strong> 
                ${evento.start.toLocaleTimeString('es-CO', {hour: '2-digit', minute: '2-digit'})} - 
                ${evento.end ? evento.end.toLocaleTimeString('es-CO', {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
            </div>
        `;
        
        // 🔥 MOSTRAR DÍAS DE LA SEMANA
        if (props.diasNombres) {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-calendar-week me-2"></i>Días:</strong> 
                    ${props.diasNombres}
                </div>
            `;
        }
        
        if (props.pendiente) {
            contenido += `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Este evento está <strong>pendiente de guardar</strong>
                </div>
            `;
        }
        
        if (props.instructor) {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-user me-2"></i>Instructor:</strong> ${props.instructor}
                </div>
            `;
        }
        
        if (props.ambiente) {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-door-open me-2"></i>Ambiente:</strong> ${props.ambiente}
                </div>
            `;
        }
        
        document.getElementById('detalleHorarioContent').innerHTML = contenido;
        new bootstrap.Modal(document.getElementById('modalDetalleHorario')).show();
    }
    
    // ========== NOTIFICACIONES ==========
    function mostrarNotificacion(mensaje, tipo) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${tipo === 'success' ? 'success' : tipo === 'warning' ? 'warning' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideIn 0.3s ease;';
        toast.innerHTML = `
            <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'warning' ? 'exclamation-triangle' : 'times-circle'} me-2"></i>
            ${mensaje}
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
    
    // ========== INICIALIZACIÓN ==========
    calendario.render();
    
    // 🔥 CARGAR DÍAS PRIMERO
    cargarDiasSemana().then(() => {
        return cargarInstructores();
    }).then(() => {
        return cargarFichaDesdeUrl();
    }).catch(error => {
        console.error('❌ Error en inicialización:', error);
    });
});
</script>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>