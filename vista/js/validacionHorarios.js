// ================================================================
// MEJORAS JAVASCRIPT PARA VALIDACIÓN DE CONFLICTOS
// Archivo: vista/js/validacion_horarios.js
// ================================================================

// ========== 1. FUNCIÓN PARA VALIDAR CONFLICTOS EN EL CLIENTE (ANTES DE ENVIAR) ==========

function validarConflictosLocal(nuevoHorario, horariosExistentes) {
    console.log('🔍 Validando conflictos localmente...');
    
    const conflictos = [];
    
    horariosExistentes.forEach(horario => {
        // Verificar si es el mismo instructor
        if (nuevoHorario.idFuncionario && 
            horario.idFuncionario === nuevoHorario.idFuncionario) {
            
            // Verificar si hay días en común
            const diasComunes = nuevoHorario.dias.filter(dia => 
                horario.dias.includes(dia)
            );
            
            if (diasComunes.length > 0) {
                // Verificar superposición de horarios
                if (horariosSeSuperpo(
                    nuevoHorario.hora_inicioClase,
                    nuevoHorario.hora_finClase,
                    horario.hora_inicioClase,
                    horario.hora_finClase
                )) {
                    conflictos.push({
                        tipo: 'instructor',
                        mensaje: `El instructor ya tiene clase de ${horario.hora_inicioClase} a ${horario.hora_finClase} en la ficha ${horario.codigoFicha}`,
                        horario: horario
                    });
                }
            }
        }
        
        // Verificar si es el mismo ambiente
        if (nuevoHorario.idAmbiente && 
            horario.idAmbiente === nuevoHorario.idAmbiente) {
            
            const diasComunes = nuevoHorario.dias.filter(dia => 
                horario.dias.includes(dia)
            );
            
            if (diasComunes.length > 0) {
                if (horariosSeSuperpo(
                    nuevoHorario.hora_inicioClase,
                    nuevoHorario.hora_finClase,
                    horario.hora_inicioClase,
                    horario.hora_finClase
                )) {
                    conflictos.push({
                        tipo: 'ambiente',
                        mensaje: `El ambiente ${horario.ambienteNumero} ya está ocupado de ${horario.hora_inicioClase} a ${horario.hora_finClase} por ${horario.instructorNombre}`,
                        horario: horario
                    });
                }
            }
        }
    });
    
    return conflictos;
}

// ========== 2. FUNCIÓN AUXILIAR: Verificar si dos horarios se superponen ==========

function horariosSeSuperpo(inicio1, fin1, inicio2, fin2) {
    // Convertir strings de tiempo a minutos para comparar
    const minutosInicio1 = tiempoAMinutos(inicio1);
    const minutosFin1 = tiempoAMinutos(fin1);
    const minutosInicio2 = tiempoAMinutos(inicio2);
    const minutosFin2 = tiempoAMinutos(fin2);
    
    // Los horarios se superponen si:
    // inicio1 < fin2 Y fin1 > inicio2
    return minutosInicio1 < minutosFin2 && minutosFin1 > minutosInicio2;
}

// ========== 3. FUNCIÓN AUXILIAR: Convertir tiempo HH:MM:SS a minutos ==========

function tiempoAMinutos(tiempo) {
    if (!tiempo) return 0;
    const partes = tiempo.split(':');
    return parseInt(partes[0]) * 60 + parseInt(partes[1]);
}

// ========== 4. MOSTRAR CONFLICTOS CON SWEETALERT2 ==========

function mostrarDialogoConflictos(conflictos) {
    let html = '<div class="lista-conflictos">';
    
    conflictos.forEach((conflicto, index) => {
        const icono = conflicto.tipo === 'instructor' ? 
            '<i class="fas fa-user-tie"></i>' : 
            '<i class="fas fa-door-open"></i>';
        
        html += `
            <div class="conflicto-item ${conflicto.tipo}">
                <div class="conflicto-header">
                    ${icono}
                    <strong>Conflicto ${index + 1}</strong>
                </div>
                <div class="conflicto-mensaje">
                    ${conflicto.mensaje}
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    Swal.fire({
        icon: 'warning',
        title: '⚠️ Conflictos de Horario Detectados',
        html: html,
        showCancelButton: true,
        confirmButtonText: 'Entendido',
        cancelButtonText: 'Ver Calendario',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        customClass: {
            popup: 'popup-conflictos'
        }
    });
}

// ========== 5. VALIDAR ANTES DE GUARDAR (INTEGRACIÓN CON EVENTO DE GUARDADO) ==========

// Modificar el evento del botón guardar en crearHorario.php

document.getElementById('btnGuardarHorario').addEventListener('click', async function() {
    if (eventosPendientes.length === 0) {
        mostrarNotificacion('No hay eventos para guardar', 'warning');
        return;
    }
    
    // 🔥 VALIDACIÓN LOCAL ANTES DE ENVIAR
    console.log('🔍 Ejecutando validación previa...');
    
    // Obtener horarios existentes del calendario
    const horariosExistentes = calendario.getEvents()
        .filter(e => !e.extendedProps.pendiente)
        .map(e => ({
            idFuncionario: e.extendedProps.idFuncionario,
            idAmbiente: e.extendedProps.idAmbiente,
            hora_inicioClase: formatearHoraMySQL(e.start),
            hora_finClase: formatearHoraMySQL(e.end),
            dias: e.extendedProps.dias || [],
            codigoFicha: e.extendedProps.codigoFicha,
            instructorNombre: e.extendedProps.nombreInstructor,
            ambienteNumero: e.extendedProps.ambiente
        }));
    
    // Validar cada evento pendiente
    let todosLosConflictos = [];
    
    for (const eventData of eventosPendientes) {
        const nuevoHorario = {
            idFuncionario: eventData.extendedProps.idFuncionario,
            idAmbiente: eventData.extendedProps.idAmbiente,
            hora_inicioClase: formatearHoraMySQL(new Date(eventData.start)),
            hora_finClase: formatearHoraMySQL(new Date(eventData.end)),
            dias: eventData.extendedProps.dias || []
        };
        
        const conflictos = validarConflictosLocal(nuevoHorario, horariosExistentes);
        todosLosConflictos = todosLosConflictos.concat(conflictos);
    }
    
    // Si hay conflictos, mostrar y no continuar
    if (todosLosConflictos.length > 0) {
        console.warn('⚠️ Conflictos detectados:', todosLosConflictos);
        mostrarDialogoConflictos(todosLosConflictos);
        return;
    }
    
    // Si no hay conflictos, continuar con el guardado
    if (!confirm(`¿Guardar ${eventosPendientes.length} horarios?`)) {
        return;
    }
    
    // ... resto del código de guardado
});

// ========== 6. RESALTAR EVENTOS CON POTENCIAL CONFLICTO ==========

function resaltarEventosConflictivos(evento) {
    const eventos = calendario.getEvents();
    
    eventos.forEach(e => {
        // Resetear estilos
        e.setProp('classNames', e.extendedProps.pendiente ? ['pendiente'] : []);
    });
    
    // Buscar eventos que podrían tener conflicto
    eventos.forEach(e => {
        if (e.id === evento.id) return;
        
        // Mismo instructor
        if (evento.extendedProps.idFuncionario && 
            e.extendedProps.idFuncionario === evento.extendedProps.idFuncionario) {
            
            // Verificar días comunes
            const diasComunes = (evento.extendedProps.dias || []).filter(dia => 
                (e.extendedProps.dias || []).includes(dia)
            );
            
            if (diasComunes.length > 0) {
                if (horariosSeSuperpo(
                    formatearHoraMySQL(evento.start),
                    formatearHoraMySQL(evento.end),
                    formatearHoraMySQL(e.start),
                    formatearHoraMySQL(e.end)
                )) {
                    e.setProp('classNames', ['conflicto-instructor']);
                }
            }
        }
        
        // Mismo ambiente
        if (evento.extendedProps.idAmbiente && 
            e.extendedProps.idAmbiente === evento.extendedProps.idAmbiente) {
            
            const diasComunes = (evento.extendedProps.dias || []).filter(dia => 
                (e.extendedProps.dias || []).includes(dia)
            );
            
            if (diasComunes.length > 0) {
                if (horariosSeSuperpo(
                    formatearHoraMySQL(evento.start),
                    formatearHoraMySQL(evento.end),
                    formatearHoraMySQL(e.start),
                    formatearHoraMySQL(e.end)
                )) {
                    e.setProp('classNames', ['conflicto-ambiente']);
                }
            }
        }
    });
}

// ========== 7. CSS ADICIONAL PARA CONFLICTOS ==========

const estilosConflictos = `
<style>
/* Eventos con conflicto */
.fc-event.conflicto-instructor {
    border-left: 5px solid #ff4444 !important;
    opacity: 0.85;
    animation: pulso-conflicto 2s infinite;
}

.fc-event.conflicto-ambiente {
    border-left: 5px solid #ffaa00 !important;
    opacity: 0.85;
    animation: pulso-conflicto 2s infinite;
}

@keyframes pulso-conflicto {
    0%, 100% { opacity: 0.85; }
    50% { opacity: 1; }
}

/* Diálogo de conflictos */
.lista-conflictos {
    max-height: 400px;
    overflow-y: auto;
    text-align: left;
}

.conflicto-item {
    margin: 12px 0;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid;
}

.conflicto-item.instructor {
    background: #ffebee;
    border-left-color: #f44336;
}

.conflicto-item.ambiente {
    background: #fff3e0;
    border-left-color: #ff9800;
}

.conflicto-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-size: 14px;
    color: #333;
}

.conflicto-header i {
    font-size: 18px;
}

.conflicto-item.instructor .conflicto-header i {
    color: #f44336;
}

.conflicto-item.ambiente .conflicto-header i {
    color: #ff9800;
}

.conflicto-mensaje {
    font-size: 13px;
    color: #555;
    line-height: 1.5;
}

/* Popup personalizado */
.popup-conflictos {
    width: 600px !important;
}

.popup-conflictos .swal2-title {
    font-size: 22px;
}
</style>
`;

// Insertar estilos en el documento
document.head.insertAdjacentHTML('beforeend', estilosConflictos);

// ========== 8. EVENTO HOVER PARA MOSTRAR INFORMACIÓN ==========

// Agregar tooltip al pasar el mouse sobre un evento
function agregarTooltipEvento(info) {
    const props = info.event.extendedProps;
    
    let tooltipContent = `
        <div class="tooltip-horario">
            <strong>${info.event.title}</strong><br>
            <small>
                ${info.event.start.toLocaleTimeString('es-CO', {hour: '2-digit', minute: '2-digit'})} - 
                ${info.event.end ? info.event.end.toLocaleTimeString('es-CO', {hour: '2-digit', minute: '2-digit'}) : 'N/A'}
            </small>
    `;
    
    if (props.diasNombres) {
        tooltipContent += `<br><small>📅 ${props.diasNombres}</small>`;
    }
    
    if (props.ambiente) {
        tooltipContent += `<br><small>🚪 Ambiente ${props.ambiente}</small>`;
    }
    
    if (props.pendiente) {
        tooltipContent += `<br><span style="color: #ff9800;">⚠️ Pendiente de guardar</span>`;
    }
    
    tooltipContent += '</div>';
    
    // Usar Tippy.js o simplemente title nativo
    info.el.setAttribute('title', tooltipContent);
}

// ========== 9. FUNCIÓN DE REPORTE DE DISPONIBILIDAD ==========

async function consultarDisponibilidad(idFuncionario, dia, horaInicio, horaFin) {
    try {
        const response = await fetch('controlador/horarioControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                consultarDisponibilidad: 'ok',
                idFuncionario: idFuncionario,
                dia: dia,
                horaInicio: horaInicio,
                horaFin: horaFin
            })
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error al consultar disponibilidad:', error);
        return { codigo: "500", disponible: false };
    }
}

// ========== 10. EXPORT PARA USO EN OTROS ARCHIVOS ==========

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validarConflictosLocal,
        horariosSeSuperpo,
        mostrarDialogoConflictos,
        resaltarEventosConflictivos,
        consultarDisponibilidad
    };
}