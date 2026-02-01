<div class="container-fluid py-4">
    <div class="row">
        <!-- Panel lateral de instructores -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Instructores</h5>
                </div>
                <div class="card-body">
                    <!-- Buscador -->
                    <div class="mb-3">
                        <input type="text" id="buscarInstructor" class="form-control" 
                               placeholder="Buscar instructor...">
                    </div>
                    <!-- Lista de instructores arrastrables -->
                    <div id="listaInstructores" class="instructor-list">
                        <!-- Se llena con JavaScript -->
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

<!-- Estilos -->
<style>
.instructor-list {
    max-height: 500px;
    overflow-y: auto;
}

.instructor-item {
    padding: 10px 15px;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    cursor: grab;
    transition: transform 0.2s, box-shadow 0.2s;
    font-weight: 500;
}

.instructor-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.instructor-item:active {
    cursor: grabbing;
}

.instructor-item.oculto {
    display: none;
}

#calendario {
    min-height: 600px;
}

/* Colores para diferentes instructores */
.instructor-item[data-color="1"] { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.instructor-item[data-color="2"] { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.instructor-item[data-color="3"] { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.instructor-item[data-color="4"] { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.instructor-item[data-color="5"] { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
</style>

<!-- Scripts de FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.20/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    let calendario;
    let instructores = [];
    const colores = ['#667eea', '#f5576c', '#4facfe', '#43e97b', '#fa709a', '#ff6b6b', '#4ecdc4', '#45b7d1'];

    // ====== CARGAR INSTRUCTORES ======
    function cargarInstructores() {
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
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // ====== RENDERIZAR LISTA DE INSTRUCTORES ======
    function renderizarInstructores(lista) {
        const contenedor = document.getElementById('listaInstructores');
        contenedor.innerHTML = '';

        lista.forEach((instructor, index) => {
            const color = colores[index % colores.length];
            const div = document.createElement('div');
            div.className = 'instructor-item fc-event';
            div.setAttribute('data-id', instructor.idInstructor);
            div.setAttribute('data-nombre', instructor.nombre);
            div.setAttribute('data-color', color);
            div.setAttribute('data-color-index', (index % 5) + 1);
            div.innerHTML = `<i class="fas fa-user-tie me-2"></i>${instructor.nombre}`;
            contenedor.appendChild(div);
        });
    }

    // ====== INICIALIZAR DRAGGABLE ======
    function inicializarDraggable() {
        const contenedorInstructores = document.getElementById('listaInstructores');
        
        new FullCalendar.Draggable(contenedorInstructores, {
            itemSelector: '.instructor-item',
            eventData: function(eventEl) {
                return {
                    title: eventEl.getAttribute('data-nombre'),
                    backgroundColor: eventEl.getAttribute('data-color'),
                    borderColor: eventEl.getAttribute('data-color'),
                    extendedProps: {
                        idInstructor: eventEl.getAttribute('data-id')
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
            if (nombre.includes(termino)) {
                item.classList.remove('oculto');
            } else {
                item.classList.add('oculto');
            }
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

        // Cuando se suelta un instructor en el calendario
        eventReceive: function(info) {
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

        // Click en un evento para eliminar
        eventClick: function(info) {
            if (confirm('¿Desea eliminar este horario de ' + info.event.title + '?')) {
                eliminarHorario(info.event);
            }
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
                        title: h.instructorNombre,
                        start: h.fechaInicio,
                        end: h.fechaFin,
                        backgroundColor: h.color,
                        borderColor: h.color,
                        extendedProps: {
                            idInstructor: h.idInstructor
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

    calendario.render();
    cargarInstructores();

    // ====== GUARDAR HORARIO ======
    function guardarHorario(evento) {
        const datos = new URLSearchParams({
            crearHorario: 'ok',
            titulo: evento.title,
            idInstructor: evento.extendedProps.idInstructor,
            fechaInicio: evento.start.toISOString(),
            fechaFin: evento.end ? evento.end.toISOString() : new Date(evento.start.getTime() + 60*60*1000).toISOString(),
            color: evento.backgroundColor
        });

        fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.codigo === "200") {
                evento.setProp('id', data.idHorario);
                mostrarNotificacion('Horario creado correctamente', 'success');
            } else {
                evento.remove();
                mostrarNotificacion('Error al crear horario', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            evento.remove();
        });
    }

    // ====== ACTUALIZAR HORARIO ======
    function actualizarHorario(evento) {
        const datos = new URLSearchParams({
            actualizarHorario: 'ok',
            idHorario: evento.id,
            fechaInicio: evento.start.toISOString(),
            fechaFin: evento.end ? evento.end.toISOString() : new Date(evento.start.getTime() + 60*60*1000).toISOString()
        });

        fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.codigo === "200") {
                mostrarNotificacion('Horario actualizado', 'success');
            } else {
                calendario.refetchEvents();
                mostrarNotificacion('Error al actualizar', 'error');
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
                mostrarNotificacion('Horario eliminado', 'success');
            } else {
                mostrarNotificacion('Error al eliminar', 'error');
            }
        });
    }

    // ====== NOTIFICACIONES ======
    function mostrarNotificacion(mensaje, tipo) {
        // Puedes usar SweetAlert2 o tu sistema de notificaciones
        const toast = document.createElement('div');
        toast.className = `alert alert-${tipo === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
        toast.innerHTML = mensaje;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>