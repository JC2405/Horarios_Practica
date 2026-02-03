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
                            <div id="infoFicha" class="alert alert-info mb-0 py-2">
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
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rango del Horario:</label>
                            <div class="input-group">
                                <input type="date" id="fechaInicio" class="form-control">
                                <span class="input-group-text">hasta</span>
                                <input type="date" id="fechaFin" class="form-control">
                            </div>
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
                    <!-- Buscador -->
                    <div class="mb-3">
                        <input type="text" id="buscarInstructor" class="form-control" 
                               placeholder="Buscar instructor...">
                    </div>
                    <!-- Lista de instructores arrastrables -->
                    <div id="listaInstructores" class="element-list">
                        <!-- Se llena con JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Sección de Ambientes -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-door-open me-2"></i>Ambientes</h5>
                    <span class="badge bg-light text-success" id="countAmbientes">0</span>
                </div>
                <div class="card-body">
                    <!-- Buscador de ambientes -->
                    <div class="mb-3">
                        <input type="text" id="buscarAmbiente" class="form-control" 
                               placeholder="Buscar ambiente...">
                    </div>
                    <!-- Lista de ambientes arrastrables -->
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

/* Colores para diferentes instructores */
.instructor-item[data-color-index="1"] { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.instructor-item[data-color-index="2"] { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.instructor-item[data-color-index="3"] { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.instructor-item[data-color-index="4"] { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.instructor-item[data-color-index="5"] { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.instructor-item[data-color-index="6"] { background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%); }
.instructor-item[data-color-index="7"] { background: linear-gradient(135deg, #45b7d1 0%, #96c93d 100%); }
.instructor-item[data-color-index="8"] { background: linear-gradient(135deg, #f7797d 0%, #fbd786 100%); }

/* Colores para ambientes */
.ambiente-item[data-sede="CAGE"] { border-left: 4px solid #667eea; }
.ambiente-item[data-sede="CASD"] { border-left: 4px solid #43e97b; }
.ambiente-item[data-sede="COMFAMA"] { border-left: 4px solid #f093fb; }

#calendario {
    min-height: 600px;
}

/* Estilo para eventos del calendario */
.fc-event {
    cursor: pointer;
    border-radius: 4px;
}

.fc-event.event-instructor {
    border-left: 4px solid #667eea;
}

.fc-event.event-ambiente {
    border-left: 4px solid #43e97b;
}
</style>

<!-- CSS de FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />

<!-- Scripts -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>

<script>
// ====== VERIFICAR SI HAY FICHA POR PARÁMETRO URL ======
function obtenerParametroUrl(nombre) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(nombre);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando sistema de calendario avanzado...');
    
    // Verificar si hay una ficha seleccionada por URL
    const fichaParam = obtenerParametroUrl('ficha');
    if (fichaParam) {
        console.log('Ficha recibida por URL:', fichaParam);
    }
    
    // Validación de FullCalendar
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar no está cargado');
        return;
    }
    
    // Variables globales
    let calendario;
    let instructores = [];
    let ambientes = [];
    let fichaSeleccionada = null;
    let ambienteSeleccionado = null;
    const colores = ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#fa709a', '#ff6b6b', '#4ecdc4', '#45b7d1'];
    const coloresAmbiente = ['#43e97b', '#38f9d7', '#4facfe', '#667eea', '#f093fb'];
    
    // ====== CARGAR FICHA DESDE URL Y MOSTRAR INFORMACIÓN ======
    function cargarFichaDesdeUrl() {
        const fichaParam = obtenerParametroUrl('ficha');
        if (!fichaParam) {
            console.log('No hay ficha en la URL');
            return Promise.resolve();
        }
        
        return new Promise((resolve, reject) => {
            console.log('Buscando ficha:', fichaParam);
            fetch('controlador/fichaControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarTecnologos=ok'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta de fichas:', data);
                if (data.codigo === "200") {
                    // Buscar la ficha que coincida con el código
                    const fichaEncontrada = data.listarTecnologos.find(f => f.codigoFicha === fichaParam);
                    if (fichaEncontrada) {
                        // 🔥 CORRECCIÓN: Guardar el ID correcto de la ficha
                        fichaSeleccionada = {
                            id: fichaEncontrada.idFicha || null, // ID numérico de la tabla ficha
                            codigo: fichaEncontrada.codigoFicha, // Código de texto
                            programa: fichaEncontrada.programa,
                            jornada: fichaEncontrada.jornada,
                            municipio: fichaEncontrada.municipio,
                            sede: fichaEncontrada.sede,
                            idSede: fichaEncontrada.idSede
                        };
                        
                        // Mostrar información de la ficha
                        document.getElementById('infoFicha').style.display = 'block';
                        document.getElementById('fichaCodigo').textContent = fichaSeleccionada.codigo;
                        document.getElementById('fichaCiudad').textContent = fichaSeleccionada.municipio;
                        document.getElementById('fichaSede').textContent = fichaSeleccionada.sede || 'No especificada';
                        document.getElementById('fichaPrograma').textContent = fichaSeleccionada.programa;
                        document.getElementById('fichaJornada').textContent = fichaSeleccionada.jornada;
                        
                        console.log('✅ Ficha cargada correctamente:', fichaSeleccionada);
                        
                        // Cargar ambientes de la sede de la ficha
                        cargarAmbientesPorSede(fichaSeleccionada.idSede);
                        
                        resolve();
                    } else {
                        console.error('❌ No se encontró la ficha:', fichaParam);
                        reject('Ficha no encontrada');
                    }
                } else {
                    reject('Error al cargar fichas');
                }
            })
            .catch(error => {
                console.error('Error al cargar ficha:', error);
                reject(error);
            });
        });
    }
    
    // ====== CARGAR INSTRUCTORES ======
    function cargarInstructores() {
        return new Promise((resolve, reject) => {
            console.log('Cargando instructores...');
            fetch('controlador/instructorControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarInstructor=ok'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Datos instructores:', data);
                if (data.codigo === "200") {
                    instructores = data.listarInstructor;
                    renderizarInstructores(instructores);
                    inicializarDraggable();
                    resolve();
                } else {
                    reject('Error al cargar instructores: ' + data.mensaje);
                }
            })
            .catch(error => {
                console.error('Error al cargar instructores:', error);
                reject(error);
            });
        });
    }
    
    // ====== CARGAR AMBIENTES POR SEDE ======
    function cargarAmbientesPorSede(idSede) {
        console.log('Cargando ambientes para sede:', idSede);
        fetch('controlador/ambienteControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'listarAmbientesPorSede=ok&idSede=' + encodeURIComponent(idSede)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Datos ambientes:', data);
            if (data.codigo === "200") {
                ambientes = data.ambientes;
                renderizarAmbientes(ambientes);
                inicializarDraggable();
            } else {
                ambientes = [];
                document.getElementById('listaAmbientes').innerHTML = `
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error: ${data.mensaje || 'No hay ambientes disponibles en esta sede'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error al cargar ambientes:', error);
            document.getElementById('listaAmbientes').innerHTML = `
                <div class="text-muted text-center py-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al cargar ambientes: ${error.message}
                </div>
            `;
        });
    }
    
    // ====== RENDERIZAR LISTA DE INSTRUCTORES ======
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
    
    // ====== RENDERIZAR LISTA DE AMBIENTES ======
    function renderizarAmbientes(lista) {
        const contenedor = document.getElementById('listaAmbientes');
        contenedor.innerHTML = '';
        
        if (lista.length === 0) {
            contenedor.innerHTML = `
                <div class="text-muted text-center py-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    No hay ambientes disponibles
                </div>
            `;
            return;
        }
        
        lista.forEach((ambiente, index) => {
            const div = document.createElement('div');
            div.className = 'ambiente-item';
            div.setAttribute('data-type', 'ambiente');
            div.setAttribute('data-id', ambiente.idAmbiente);
            div.setAttribute('data-nombre', `Ambiente ${ambiente.codigo}`);
            div.setAttribute('data-sede', ambiente.sedeNombre || 'General');
            div.setAttribute('data-sede-code', ambiente.sedeMunicipio || 'GEN');
            div.innerHTML = `
                <i class="fas fa-door-open me-2"></i>
                <strong>${ambiente.codigo}</strong>
                <br><small>${ambiente.descripcion || ''}</small>
                <br><small class="text-dark"><i class="fas fa-map-marker-alt me-1"></i>${ambiente.sedeNombre || ''}</small>
            `;
            // Click para seleccionar ambiente
            div.addEventListener('click', function() {
                // Remover selección anterior
                document.querySelectorAll('.ambiente-item').forEach(item => item.classList.remove('selected'));
                // Seleccionar este ambiente
                div.classList.add('selected');
                ambienteSeleccionado = {
                    idAmbiente: ambiente.idAmbiente,
                    codigo: ambiente.codigo,
                    nombre: `Ambiente ${ambiente.codigo}`
                };
                console.log('✅ Ambiente seleccionado:', ambienteSeleccionado);
            });
            contenedor.appendChild(div);
        });
        
        document.getElementById('countAmbientes').textContent = lista.length;
    }
    
    // ====== INICIALIZAR DRAGGABLE ======
    function inicializarDraggable() {
        const contenedorInstructores = document.getElementById('listaInstructores');
        
        // Drag SOLO para instructores (ambientes son seleccionables con clic)
        new FullCalendar.Draggable(contenedorInstructores, {
            itemSelector: '.instructor-item',
            eventData: function(eventEl) {
                return {
                    title: eventEl.getAttribute('data-nombre'),
                    backgroundColor: eventEl.getAttribute('data-color'),
                    borderColor: eventEl.getAttribute('data-color'),
                    extendedProps: {
                        type: 'instructor',
                        idFuncionario: eventEl.getAttribute('data-id'),
                        idAmbiente: ambienteSeleccionado?.idAmbiente || null,
                        idFicha: fichaSeleccionada?.id || null, // 🔥 USAR EL ID NUMÉRICO
                        nombreInstructor: eventEl.getAttribute('data-nombre')
                    }
                };
            }
        });
    }
    
    // ====== BUSCAR INSTRUCTOR ======
    document.getElementById('buscarInstructor').addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.instructor-item');
        
        items.forEach(item => {
            const nombre = item.getAttribute('data-nombre').toLowerCase();
            item.classList.toggle('oculto', !nombre.includes(termino));
        });
    });
    
    // ====== BUSCAR AMBIENTE ======
    document.getElementById('buscarAmbiente').addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.ambiente-item');
        
        items.forEach(item => {
            const nombre = item.getAttribute('data-nombre').toLowerCase();
            item.classList.toggle('oculto', !nombre.includes(termino));
        });
    });
    
    // ====== INICIALIZAR CALENDARIO ======
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
        
        // Cuando se suelta un instructor/ambiente en el calendario
        eventReceive: function(info) {
            console.log('📅 Evento recibido en el calendario:', info.event);
            // Verificar que haya una ficha seleccionada
            if (!fichaSeleccionada) {
                info.event.remove();
                mostrarNotificacion('Por favor selecciona una ficha primero', 'warning');
                return;
            }
            guardarHorario(info.event);
        },
        
        // Cuando se arrastra un evento existente
        eventDrop: function(info) {
            actualizarHorario(info.event);
        },
        
        // Cuando se redimensiona un evento
        eventResize: function(info) {
            actualizarHorario(info.event);
        },
        
        // Click en un evento para ver detalles
        eventClick: function(info) {
            mostrarDetalleHorario(info.event);
        },
        
        // Cargar eventos existentes
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('controlador/horarioControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'listarHorarios=ok'
            })
            .then(response => response.json())
            .then(data => {
                if (data.codigo === "200") {
                    const eventos = data.horarios.map(h => ({
                        id: h.idHorario,
                        title: h.instructorNombre || `Ambiente ${h.ambienteNumero || ''}` || 'Evento',
                        start: h.fecha_inicioClase,
                        end: h.hora_finClase,
                        backgroundColor: '#3788d8',
                        borderColor: '#3788d8',
                        extendedProps: {
                            idFuncionario: h.idFuncionario,
                            idAmbiente: h.idAmbiente,
                            idFicha: h.idFicha,
                            tipo: h.instructorNombre ? 'instructor' : 'ambiente',
                            instructor: h.instructorNombre,
                            ambiente: h.ambienteNumero,
                            ambienteDescripcion: h.ambienteDescripcion,
                            codigoFicha: h.codigoFicha
                        }
                    }));
                    successCallback(eventos);
                } else {
                    successCallback([]);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                failureCallback(error);
            });
        }
    });
    
    // ====== GUARDAR HORARIO ======
    function guardarHorario(evento) {
        // Verificar que haya una ficha seleccionada
        if (!fichaSeleccionada || !fichaSeleccionada.id) {
            evento.remove();
            mostrarNotificacion('Por favor selecciona una ficha primero', 'warning');
            return;
        }
        
        // 🔥 CORRECCIÓN: Usar formato correcto para MySQL datetime
        const horaInicio = formatearFechaMySQL(evento.start);
        const horaFin = evento.end ? formatearFechaMySQL(evento.end) : formatearFechaMySQL(new Date(evento.start.getTime() + 60*60*1000));
        
        // Obtener fechas del rango del horario
        const fechaInicioRange = document.getElementById('fechaInicio').value || null;
        const fechaFinRange = document.getElementById('fechaFin').value || null;
        
        console.log('💾 Guardando horario:');
        console.log('- Instructor ID:', evento.extendedProps.idFuncionario);
        console.log('- Ambiente ID:', evento.extendedProps.idAmbiente);
        console.log('- Ficha ID (numérico):', fichaSeleccionada.id);
        console.log('- Hora inicio clase:', horaInicio);
        console.log('- Hora fin clase:', horaFin);
        console.log('- Fecha inicio rango:', fechaInicioRange);
        console.log('- Fecha fin rango:', fechaFinRange);
        
        const datos = new URLSearchParams({
            crearHorario: 'ok',
            idFuncionario: evento.extendedProps.idFuncionario || '',
            idAmbiente: evento.extendedProps.idAmbiente || '',
            idFicha: fichaSeleccionada.id, // 🔥 USAR ID NUMÉRICO
            hora_inicioClase: horaInicio, // 🔥 NOMBRE CORRECTO
            hora_finClase: horaFin,
            fecha_inicioHorario: fechaInicioRange || '',
            fecha_finHorario: fechaFinRange || ''
        });

        fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            console.log('📬 Respuesta del servidor:', data);
            if (data.codigo === "200") {
                evento.setProp('id', data.idHorario);
                mostrarNotificacion('✅ Horario creado correctamente', 'success');
            } else {
                evento.remove();
                mostrarNotificacion('❌ Error al crear horario: ' + (data.mensaje || 'Error desconocido'), 'error');
                console.error('Error del servidor:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error de red:', error);
            evento.remove();
            mostrarNotificacion('Error de conexión al crear horario', 'error');
        });
    }
    
    // ====== FUNCIÓN AUXILIAR PARA FORMATEAR FECHAS ======
    function formatearFechaMySQL(fecha) {
        const year = fecha.getFullYear();
        const month = String(fecha.getMonth() + 1).padStart(2, '0');
        const day = String(fecha.getDate()).padStart(2, '0');
        const hours = String(fecha.getHours()).padStart(2, '0');
        const minutes = String(fecha.getMinutes()).padStart(2, '0');
        const seconds = String(fecha.getSeconds()).padStart(2, '0');
        
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
    
    // ====== ACTUALIZAR HORARIO ======
    function actualizarHorario(evento) {
        const horaInicio = formatearFechaMySQL(evento.start);
        const horaFin = evento.end ? formatearFechaMySQL(evento.end) : formatearFechaMySQL(new Date(evento.start.getTime() + 60*60*1000));
        
        const fechaInicioRange = document.getElementById('fechaInicio').value || null;
        const fechaFinRange = document.getElementById('fechaFin').value || null;
        
        console.log('🔄 Actualizando horario:', evento.id);
        
        const datos = new URLSearchParams({
            actualizarHorario: 'ok',
            idHorario: evento.id,
            idAmbiente: evento.extendedProps.idAmbiente || '',
            hora_inicioClase: horaInicio,
            hora_finClase: horaFin,
            fecha_inicioHorario: fechaInicioRange || '',
            fecha_finHorario: fechaFinRange || ''
        });

        fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.codigo === "200") {
                mostrarNotificacion('✅ Horario actualizado', 'success');
            } else {
                calendario.refetchEvents();
                mostrarNotificacion('❌ Error al actualizar: ' + data.mensaje, 'error');
            }
        });
    }
    
    // ====== ELIMINAR HORARIO ======
    function eliminarHorario(evento) {
        const datos = new URLSearchParams({
            eliminarHorario: 'ok',
            idHorario: evento.id
        });

        fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.codigo === "200") {
                evento.remove();
                mostrarNotificacion('✅ Horario eliminado', 'success');
            } else {
                mostrarNotificacion('❌ Error al eliminar: ' + data.mensaje, 'error');
            }
        });
    }
    
    // ====== MOSTRAR DETALLE DEL HORARIO ======
    function mostrarDetalleHorario(evento) {
        const props = evento.extendedProps;
        let contenido = `
            <div class="mb-3">
                <strong><i class="fas fa-heading me-2"></i>Evento:</strong> ${evento.title}
            </div>
            <div class="mb-3">
                <strong><i class="fas fa-clock me-2"></i>Fecha Inicio:</strong> ${evento.start.toLocaleString('es-CO')}
            </div>
            <div class="mb-3">
                <strong><i class="fas fa-clock me-2"></i>Fecha Fin:</strong> ${evento.end ? evento.end.toLocaleString('es-CO') : 'No definida'}
            </div>
        `;
        
        if (props.tipo === 'instructor') {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-user me-2"></i>Instructor:</strong> ${props.instructor || 'No asignado'}
                </div>
            `;
        }
        
        if (props.tipo === 'ambiente' || props.ambiente) {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-door-open me-2"></i>Ambiente:</strong> ${props.ambiente || 'No asignado'}
                </div>
            `;
        }
        
        if (props.codigoFicha) {
            contenido += `
                <div class="mb-3">
                    <strong><i class="fas fa-id-card me-2"></i>Ficha:</strong> ${props.codigoFicha}
                </div>
            `;
        }
        
        document.getElementById('detalleHorarioContent').innerHTML = contenido;
        
        const footer = document.querySelector('#modalDetalleHorario .modal-footer');
        footer.innerHTML = `
            <button type="button" class="btn btn-danger" onclick="eliminarEvento('${evento.id}')">
                <i class="fas fa-trash me-2"></i>Eliminar
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        `;
        
        window.eventoActual = evento;
        
        new bootstrap.Modal(document.getElementById('modalDetalleHorario')).show();
    }
    
    // ====== NOTIFICACIONES ======
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
    
    // ====== INICIALIZACIÓN ======
    calendario.render();
    
    cargarInstructores().then(() => {
        cargarFichaDesdeUrl();
    });
    
    window.eliminarEvento = function(eventId) {
        const evento = calendario.getEventById(eventId);
        if (evento) {
            if (confirm('¿Desea eliminar este horario?')) {
                eliminarHorario(evento);
                bootstrap.Modal.getInstance(document.getElementById('modalDetalleHorario')).hide();
            }
        }
    };
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>