/**
 * Sistema de Gestión de Horarios - Ambiente Sede Medellín
 * vista/js/ambienteMedellin.js
 */

// ========================================
// VARIABLES GLOBALES
// ========================================

let horarios = {}; // Estructura: horarios[idAmbiente][idFranja] = { instructor, ficha, transversales }
let draggedElement = null;
let currentTransversalData = null; // Para guardar datos del modal de transversales

// Datos cargados desde BD
let ambientes = [];
let instructores = [];
let fichas = [];
const SEDE_MEDELLIN = 1; // ID de la sede Medellín

const FRANJAS = [
    { idFranja: 1, nombre: "Mañana", hora_inicio: "06:00", hora_fin: "12:00", icono: "☀️" },
    { idFranja: 2, nombre: "Tarde", hora_inicio: "12:00", hora_fin: "18:00", icono: "🌆" },
    { idFranja: 3, nombre: "Noche", hora_inicio: "18:00", hora_fin: "22:00", icono: "🌙" },
    { idFranja: 4, nombre: "Sábado", hora_inicio: "08:00", hora_fin: "12:00", icono: "📅" }
];

// ========================================
// INICIALIZACIÓN
// ========================================

$(document).ready(function() {
    console.log('🚀 Iniciando sistema de gestión de horarios - Medellín');
    inicializarAplicacion();
});

function inicializarAplicacion() {
    // Cargar datos iniciales
    cargarInstructores();
    cargarFichas();
    cargarAmbientesMedellin();
    
    // Event listeners
    $('#filtroDisponible').on('change', aplicarFiltros);
    $('#buscarAmbiente').on('input', aplicarFiltros);
    $('#filtroJornada').on('change', aplicarFiltros);
    $('#buscarInstructor').on('input', filtrarInstructores);
    $('#buscarFicha').on('input', filtrarFichas);
}

// ========================================
// CARGA DE DATOS DESDE BD
// ========================================

function cargarInstructores() {
    $.ajax({
        url: 'controlador/instructorControlador.php',
        method: 'POST',
        data: { listarInstructores: true },
        dataType: 'json',
        success: function(response) {
            instructores = response;
            renderizarInstructores();
            console.log('✅ Instructores cargados:', instructores.length);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error al cargar instructores:', error);
            $('#instructoresList').html(`
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> Error al cargar instructores
                </div>
            `);
        }
    });
}

function renderizarInstructores() {
    const container = $('#instructoresList');
    container.empty();
    
    if (instructores.length === 0) {
        container.html('<div class="alert alert-warning">No hay instructores disponibles</div>');
        return;
    }
    
    instructores.forEach(instructor => {
        const div = $(`
            <div class="drag-item instructor" 
                 draggable="true" 
                 data-id="${instructor.idFuncionario}" 
                 data-type="instructor"
                 data-nombre="${instructor.nombre}"
                 data-area="${instructor.nombreArea || 'Sin área'}">
                <div class="instructor-info">
                    <div class="instructor-name">
                        <i class="fa fa-user"></i> ${instructor.nombre}
                    </div>
                    <div class="instructor-area">
                        📚 ${instructor.nombreArea || 'Sin área'}
                    </div>
                    <div class="instructor-contrato">
                        <small class="badge badge-info">${instructor.nombreTipoContrato || 'Contratista'}</small>
                    </div>
                </div>
            </div>
        `);
        
        // Eventos drag
        div.on('dragstart', handleDragStart);
        div.on('dragend', handleDragEnd);
        
        container.append(div);
    });
}

function filtrarInstructores() {
    const busqueda = $('#buscarInstructor').val().toLowerCase();
    
    $('.drag-item.instructor').each(function() {
        const nombre = $(this).data('nombre').toLowerCase();
        const area = $(this).data('area').toLowerCase();
        const mostrar = nombre.includes(busqueda) || area.includes(busqueda);
        $(this).toggle(mostrar);
    });
}

function cargarFichas() {
    $.ajax({
        url: 'controlador/fichaControlador.php',
        method: 'POST',
        data: { listarFichaHorario: true },
        dataType: 'json',
        success: function(response) {
            fichas = response;
            renderizarFichas();
            console.log('✅ Fichas cargadas:', fichas.length);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error al cargar fichas:', error);
            $('#fichasList').html(`
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> Error al cargar fichas
                </div>
            `);
        }
    });
}

function renderizarFichas() {
    const container = $('#fichasList');
    container.empty();
    
    if (fichas.length === 0) {
        container.html('<div class="alert alert-warning">No hay fichas disponibles</div>');
        return;
    }
    
    fichas.forEach(ficha => {
        const div = $(`
            <div class="drag-item ficha" 
                 draggable="true" 
                 data-id="${ficha.idFicha}" 
                 data-type="ficha"
                 data-codigo="${ficha.codigoFicha}"
                 data-programa="${ficha.nombrePrograma || 'Sin programa'}">
                <div class="ficha-info">
                    <div class="ficha-code">
                        <i class="fa fa-clipboard"></i> ${ficha.codigoFicha}
                    </div>
                    <div class="ficha-program">
                        🎓 ${ficha.nombrePrograma || 'Sin programa'}
                    </div>
                    <div class="ficha-nivel">
                        <small class="badge badge-success">${ficha.nivelFormacion || 'Tecnólogo'}</small>
                    </div>
                </div>
            </div>
        `);
        
        // Eventos drag
        div.on('dragstart', handleDragStart);
        div.on('dragend', handleDragEnd);
        
        container.append(div);
    });
}

function filtrarFichas() {
    const busqueda = $('#buscarFicha').val().toLowerCase();
    
    $('.drag-item.ficha').each(function() {
        const codigo = $(this).data('codigo').toString().toLowerCase();
        const programa = $(this).data('programa').toLowerCase();
        const mostrar = codigo.includes(busqueda) || programa.includes(busqueda);
        $(this).toggle(mostrar);
    });
}

function cargarAmbientesMedellin() {
    $.ajax({
        url: 'controlador/ambienteControlador.php',
        method: 'POST',
        data: { 
            listarAmbientesPorSede: true,
            idSede: SEDE_MEDELLIN
        },
        dataType: 'json',
        success: function(response) {
            ambientes = response;
            renderizarAmbientes();
            actualizarEstadisticas();
            console.log('✅ Ambientes cargados:', ambientes.length);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error al cargar ambientes:', error);
            $('#ambientesGrid').html(`
                <div class="alert alert-danger text-center">
                    <i class="fa fa-exclamation-triangle fa-3x mb-3"></i>
                    <p>Error al cargar los ambientes de Medellín</p>
                </div>
            `);
        }
    });
}

function renderizarAmbientes() {
    const container = $('#ambientesGrid');
    container.empty();
    
    if (ambientes.length === 0) {
        container.html(`
            <div class="alert alert-warning text-center">
                <i class="fa fa-info-circle fa-3x mb-3"></i>
                <p>No hay ambientes registrados para Medellín</p>
            </div>
        `);
        return;
    }
    
    ambientes.forEach(ambiente => {
        const card = crearAmbienteCard(ambiente);
        container.append(card);
    });
}

function crearAmbienteCard(ambiente) {
    const disponible = esAmbienteDisponible(ambiente.idAmbiente);
    
    const card = $(`
        <div class="ambiente-card ${disponible ? 'disponible' : ''}" 
             data-id-ambiente="${ambiente.idAmbiente}"
             data-codigo="${ambiente.codigo}"
             data-numero="${ambiente.numero}"
             data-descripcion="${ambiente.descripcion}">
             
            <button class="btn btn-sm btn-info ver-detalle-btn" 
                    onclick="verDetalleAmbiente(${ambiente.idAmbiente})">
                <i class="fa fa-info-circle"></i>
            </button>
            
            <div class="ambiente-header">
                <div class="ambiente-nombre">
                    ${ambiente.codigo} - ${ambiente.numero}
                    ${disponible ? 
                        '<span class="badge badge-success">Disponible</span>' : 
                        '<span class="badge badge-danger">Ocupado</span>'}
                </div>
                <div class="ambiente-detalles">
                    ${ambiente.descripcion} | 👥 ${ambiente.capacidad} personas | 📍 ${ambiente.ubicacion}
                </div>
            </div>
            
            ${FRANJAS.map(franja => crearFranjaHTML(ambiente.idAmbiente, franja)).join('')}
        </div>
    `);
    
    return card;
}

function crearFranjaHTML(idAmbiente, franja) {
    const franjaClass = franja.nombre.toLowerCase().replace(/\s/g, '-');
    
    return `
        <div class="franja-container franja-${franjaClass}">
            <div class="franja-header">
                <span>${franja.icono} ${franja.nombre}</span>
                <span>${franja.hora_inicio} - ${franja.hora_fin}</span>
            </div>
            
            <span class="drop-zone-label">👨‍🏫 Instructor:</span>
            <div class="drop-zone" 
                 data-ambiente="${idAmbiente}" 
                 data-franja="${franja.idFranja}"
                 data-tipo="instructor">
            </div>
            
            <span class="drop-zone-label">📋 Ficha:</span>
            <div class="drop-zone" 
                 data-ambiente="${idAmbiente}" 
                 data-franja="${franja.idFranja}"
                 data-tipo="ficha">
            </div>
            
            <div class="transversales-container" 
                 data-ambiente="${idAmbiente}" 
                 data-franja="${franja.idFranja}">
                <div class="sin-transversales">
                    <i class="fa fa-clock"></i> Sin transversales configuradas
                </div>
            </div>
        </div>
    `;
}

// ========================================
// DRAG & DROP
// ========================================

function handleDragStart(e) {
    draggedElement = e.target;
    $(e.target).css('opacity', '0.4');
    
    e.originalEvent.dataTransfer.effectAllowed = 'move';
    e.originalEvent.dataTransfer.setData('text/html', e.target.innerHTML);
}

function handleDragEnd(e) {
    $(e.target).css('opacity', '1');
}

// Configurar drop zones dinámicamente
$(document).on('dragover', '.drop-zone', function(e) {
    e.preventDefault();
    
    if (!draggedElement) return;
    
    const dropZone = $(this);
    const tipo = dropZone.data('tipo');
    const dragType = $(draggedElement).data('type');
    
    if (dragType === tipo) {
        dropZone.addClass('drag-over');
        e.originalEvent.dataTransfer.dropEffect = 'move';
    }
});

$(document).on('dragleave', '.drop-zone', function(e) {
    $(this).removeClass('drag-over');
});

$(document).on('drop', '.drop-zone', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const dropZone = $(this);
    dropZone.removeClass('drag-over');
    
    if (!draggedElement) return;
    
    const tipo = dropZone.data('tipo');
    const dragType = $(draggedElement).data('type');
    
    if (dragType !== tipo) {
        Swal.fire({
            icon: 'warning',
            title: 'Tipo incorrecto',
            text: `Solo puedes soltar ${tipo === 'instructor' ? 'instructores' : 'fichas'} aquí`,
            timer: 2000,
            showConfirmButton: false
        });
        return false;
    }
    
    const idAmbiente = dropZone.data('ambiente');
    const idFranja = dropZone.data('franja');
    const itemId = $(draggedElement).data('id');
    
    // Limpiar la zona
    dropZone.empty();
    
    // Crear elemento soltado
    const droppedItem = $(`
        <div class="dropped-item ${tipo === 'ficha' ? 'ficha-dropped' : ''}">
            ${draggedElement.innerHTML}
            <button class="remove-btn" 
                    onclick="removerItem(this, ${idAmbiente}, ${idFranja}, '${tipo}')">
                <i class="fa fa-times"></i>
            </button>
        </div>
    `);
    
    dropZone.append(droppedItem);
    
    // Guardar en estado
    if (!horarios[idAmbiente]) horarios[idAmbiente] = {};
    if (!horarios[idAmbiente][idFranja]) horarios[idAmbiente][idFranja] = {};
    
    if (tipo === 'instructor') {
        const instructor = instructores.find(i => i.idFuncionario == itemId);
        horarios[idAmbiente][idFranja].instructor = instructor;
        
        // Mostrar notificación
        showToast('success', `Instructor ${instructor.nombre} asignado`);
    } else {
        const ficha = fichas.find(f => f.idFicha == itemId);
        horarios[idAmbiente][idFranja].ficha = ficha;
        
        // Mostrar botón para gestionar transversales
        mostrarBotonTransversales(idAmbiente, idFranja);
        
        // Mostrar notificación
        showToast('success', `Ficha ${ficha.codigoFicha} asignada`);
    }
    
    actualizarEstadisticas();
    aplicarFiltros();
    
    return false;
});

function removerItem(btn, idAmbiente, idFranja, tipo) {
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
            $(btn).parent().remove();
            
            if (horarios[idAmbiente] && horarios[idAmbiente][idFranja]) {
                delete horarios[idAmbiente][idFranja][tipo];
                
                if (tipo === 'ficha') {
                    // Limpiar transversales
                    delete horarios[idAmbiente][idFranja].transversales;
                    $(`.transversales-container[data-ambiente="${idAmbiente}"][data-franja="${idFranja}"]`)
                        .html('<div class="sin-transversales"><i class="fa fa-clock"></i> Sin transversales configuradas</div>');
                }
            }
            
            actualizarEstadisticas();
            aplicarFiltros();
            
            showToast('success', 'Asignación eliminada');
        }
    });
}

// ========================================
// GESTIÓN DE TRANSVERSALES (MANUAL)
// ========================================

function mostrarBotonTransversales(idAmbiente, idFranja) {
    const container = $(`.transversales-container[data-ambiente="${idAmbiente}"][data-franja="${idFranja}"]`);
    
    if (horarios[idAmbiente][idFranja].transversales) {
        // Ya hay transversales, mostrarlas
        mostrarTransversalesExistentes(idAmbiente, idFranja);
    } else {
        // Mostrar botón para configurar
        container.html(`
            <div class="sin-transversales">
                <i class="fa fa-clock"></i> Sin transversales configuradas
                <button class="btn btn-sm btn-warning btn-gestionar-transversales" 
                        onclick="abrirModalTransversales(${idAmbiente}, ${idFranja})">
                    <i class="fa fa-plus"></i> Configurar Transversales
                </button>
            </div>
        `);
    }
}

function mostrarTransversalesExistentes(idAmbiente, idFranja) {
    const trans = horarios[idAmbiente][idFranja].transversales;
    const franja = FRANJAS.find(f => f.idFranja == trans.idFranja);
    const competencias = trans.competencias ? trans.competencias.split('\n').filter(c => c.trim()) : [];
    
    const container = $(`.transversales-container[data-ambiente="${idAmbiente}"][data-franja="${idFranja}"]`);
    
    const html = `
        <div class="transversales-info">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><i class="fa fa-book"></i> Transversales Programadas</strong>
                <button class="btn btn-sm btn-outline-warning" 
                        onclick="abrirModalTransversales(${idAmbiente}, ${idFranja})">
                    <i class="fa fa-edit"></i> Editar
                </button>
            </div>
            <div class="mb-1">
                <strong>Jornada:</strong> 
                <span class="jornada-badge jornada-${franja.nombre.toLowerCase()}">
                    ${franja.icono} ${franja.nombre}
                </span>
            </div>
            <div class="mb-1">
                <strong>Trimestre:</strong> 
                <span class="badge badge-info">T${trans.trimestre || '1'}</span>
            </div>
            ${competencias.length > 0 ? `
                <div>
                    <strong>Competencias:</strong><br>
                    ${competencias.map(c => `<span class="competencia-tag">${c.trim()}</span>`).join('')}
                </div>
            ` : ''}
            ${trans.observaciones ? `
                <div class="mt-2">
                    <small><i class="fa fa-comment"></i> ${trans.observaciones}</small>
                </div>
            ` : ''}
        </div>
    `;
    
    container.html(html);
}

function abrirModalTransversales(idAmbiente, idFranja) {
    const asignacion = horarios[idAmbiente][idFranja];
    
    if (!asignacion || !asignacion.ficha) {
        Swal.fire('Error', 'Primero debes asignar una ficha', 'error');
        return;
    }
    
    const ambiente = ambientes.find(a => a.idAmbiente == idAmbiente);
    const franja = FRANJAS.find(f => f.idFranja == idFranja);
    
    // Guardar contexto para el guardado
    currentTransversalData = { idAmbiente, idFranja };
    
    // Llenar modal
    $('#fichaModalCodigo').text(asignacion.ficha.codigoFicha);
    $('#infoJornadaPrincipal').text(`${ambiente.codigo} - ${franja.nombre} (${franja.hora_inicio} - ${franja.hora_fin})`);
    
    // Si ya hay transversales, cargar datos
    if (asignacion.transversales) {
        $('#jornadaTransversal').val(asignacion.transversales.idFranja);
        $('#competenciasTransversal').val(asignacion.transversales.competencias || '');
        $('#trimestreTransversal').val(asignacion.transversales.trimestre || '1');
        $('#observacionesTransversal').val(asignacion.transversales.observaciones || '');
    } else {
        // Limpiar campos
        $('#jornadaTransversal').val('');
        $('#competenciasTransversal').val('');
        $('#trimestreTransversal').val('1');
        $('#observacionesTransversal').val('');
    }
    
    $('#modalTransversales').modal('show');
}

function guardarTransversales() {
    if (!currentTransversalData) return;
    
    const { idAmbiente, idFranja } = currentTransversalData;
    
    const idFranjaTransversal = $('#jornadaTransversal').val();
    const competencias = $('#competenciasTransversal').val();
    const trimestre = $('#trimestreTransversal').val();
    const observaciones = $('#observacionesTransversal').val();
    
    if (!idFranjaTransversal) {
        Swal.fire('Error', 'Debes seleccionar una jornada para las transversales', 'error');
        return;
    }
    
    // Guardar en estado
    horarios[idAmbiente][idFranja].transversales = {
        idFranja: parseInt(idFranjaTransversal),
        competencias: competencias,
        trimestre: trimestre,
        observaciones: observaciones
    };
    
    // Actualizar vista
    mostrarTransversalesExistentes(idAmbiente, idFranja);
    
    // Cerrar modal
    $('#modalTransversales').modal('hide');
    
    showToast('success', 'Transversales configuradas correctamente');
}

// ========================================
// FILTROS Y BÚSQUEDA
// ========================================

function aplicarFiltros() {
    const filtro = $('#filtroDisponible').val();
    const busqueda = $('#buscarAmbiente').val().toLowerCase();
    const filtroJornada = $('#filtroJornada').val();
    const cards = $('.ambiente-card');
    
    cards.each(function() {
        const card = $(this);
        const idAmbiente = card.data('id-ambiente');
        const codigo = card.data('codigo').toLowerCase();
        const numero = card.data('numero').toString().toLowerCase();
        const descripcion = card.data('descripcion').toLowerCase();
        
        let mostrar = true;
        
        // Filtro por disponibilidad
        if (filtro === 'disponibles' && !esAmbienteDisponible(idAmbiente)) {
            mostrar = false;
        } else if (filtro === 'ocupados' && esAmbienteDisponible(idAmbiente)) {
            mostrar = false;
        }
        
        // Búsqueda por texto
        if (busqueda) {
            if (!codigo.includes(busqueda) && !numero.includes(busqueda) && !descripcion.includes(busqueda)) {
                mostrar = false;
            }
        }
        
        // Filtro por jornada
        if (filtroJornada !== 'todas') {
            const tieneFranja = horarios[idAmbiente] && horarios[idAmbiente][filtroJornada] && 
                               (horarios[idAmbiente][filtroJornada].instructor || horarios[idAmbiente][filtroJornada].ficha);
            if (!tieneFranja) {
                mostrar = false;
            }
        }
        
        card.toggle(mostrar);
    });
}

function esAmbienteDisponible(idAmbiente) {
    if (!horarios[idAmbiente]) return true;
    
    for (let franja in horarios[idAmbiente]) {
        if (horarios[idAmbiente][franja].instructor || horarios[idAmbiente][franja].ficha) {
            return false;
        }
    }
    
    return true;
}

// ========================================
// ESTADÍSTICAS
// ========================================

function actualizarEstadisticas() {
    const total = ambientes.length;
    let ocupados = 0;
    let asignaciones = 0;
    
    ambientes.forEach(ambiente => {
        if (!esAmbienteDisponible(ambiente.idAmbiente)) {
            ocupados++;
        }
        
        if (horarios[ambiente.idAmbiente]) {
            Object.keys(horarios[ambiente.idAmbiente]).forEach(franja => {
                if (horarios[ambiente.idAmbiente][franja].instructor) asignaciones++;
                if (horarios[ambiente.idAmbiente][franja].ficha) asignaciones++;
            });
        }
    });
    
    const disponibles = total - ocupados;
    
    $('#statAmbientes').text(total);
    $('#statDisponibles').text(disponibles);
    $('#statOcupados').text(ocupados);
    $('#statAsignaciones').text(asignaciones);
}

// ========================================
// DETALLE DE AMBIENTE
// ========================================

function verDetalleAmbiente(idAmbiente) {
    const ambiente = ambientes.find(a => a.idAmbiente == idAmbiente);
    const asignaciones = horarios[idAmbiente] || {};
    
    let html = `
        <div class="ambiente-detalle-header">
            <h5>${ambiente.codigo} - ${ambiente.numero}</h5>
            <p class="text-muted">${ambiente.descripcion}</p>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Capacidad:</strong> ${ambiente.capacidad} personas
            </div>
            <div class="col-md-6">
                <strong>Ubicación:</strong> ${ambiente.ubicacion}
            </div>
        </div>
        <h6>Asignaciones por Jornada:</h6>
    `;
    
    FRANJAS.forEach(franja => {
        const asig = asignaciones[franja.idFranja];
        html += `
            <div class="card mb-2">
                <div class="card-body">
                    <h6 class="card-title">${franja.icono} ${franja.nombre} (${franja.hora_inicio} - ${franja.hora_fin})</h6>
                    ${asig && asig.instructor ? `
                        <p class="mb-1"><strong>Instructor:</strong> ${asig.instructor.nombre}</p>
                    ` : '<p class="text-muted mb-1">Sin instructor asignado</p>'}
                    ${asig && asig.ficha ? `
                        <p class="mb-1"><strong>Ficha:</strong> ${asig.ficha.codigoFicha} - ${asig.ficha.nombrePrograma}</p>
                    ` : '<p class="text-muted mb-1">Sin ficha asignada</p>'}
                    ${asig && asig.transversales ? `
                        <div class="alert alert-info mt-2 mb-0">
                            <strong>Transversales:</strong> ${FRANJAS.find(f => f.idFranja == asig.transversales.idFranja).nombre} - Trimestre ${asig.transversales.trimestre}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    });
    
    $('#modalDetalleAmbienteBody').html(html);
    $('#modalDetalleAmbiente').modal('show');
}

// ========================================
// GUARDAR Y EXPORTAR
// ========================================

function guardarHorarios() {
    if (Object.keys(horarios).length === 0) {
        Swal.fire('Sin datos', 'No hay horarios para guardar', 'warning');
        return;
    }
    
    Swal.fire({
        title: '¿Guardar todos los horarios?',
        text: `Se guardarán ${$('#statAsignaciones').text()} asignaciones`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            realizarGuardado();
        }
    });
}

function realizarGuardado() {
    Swal.fire({
        title: 'Guardando...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Preparar datos
    const datosGuardar = [];
    
    for (let idAmbiente in horarios) {
        for (let idFranja in horarios[idAmbiente]) {
            const asignacion = horarios[idAmbiente][idFranja];
            
            if (asignacion.instructor && asignacion.ficha) {
                datosGuardar.push({
                    idAmbiente: idAmbiente,
                    idFranja: idFranja,
                    idFuncionario: asignacion.instructor.idFuncionario,
                    idFicha: asignacion.ficha.idFicha,
                    transversales: asignacion.transversales || null
                });
            }
        }
    }
    
    $.ajax({
        url: 'controlador/horarioControlador.php',
        method: 'POST',
        data: {
            guardarHorariosCompleto: true,
            horarios: JSON.stringify(datosGuardar)
        },
        dataType: 'json',
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: '¡Guardado exitoso!',
                text: `Se guardaron ${datosGuardar.length} asignaciones correctamente`,
                timer: 3000
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar:', error);
            Swal.fire('Error', 'No se pudieron guardar los horarios', 'error');
        }
    });
}

function exportarExcel() {
    if (Object.keys(horarios).length === 0) {
        Swal.fire('Sin datos', 'No hay horarios para exportar', 'warning');
        return;
    }
    
    const datosExportar = [];
    
    for (let idAmbiente in horarios) {
        const ambiente = ambientes.find(a => a.idAmbiente == idAmbiente);
        
        for (let idFranja in horarios[idAmbiente]) {
            const franja = FRANJAS.find(f => f.idFranja == idFranja);
            const asignacion = horarios[idAmbiente][idFranja];
            
            datosExportar.push({
                'Ambiente': `${ambiente.codigo} - ${ambiente.numero}`,
                'Descripción': ambiente.descripcion,
                'Jornada': franja.nombre,
                'Horario': `${franja.hora_inicio} - ${franja.hora_fin}`,
                'Instructor': asignacion.instructor ? asignacion.instructor.nombre : '',
                'Ficha': asignacion.ficha ? asignacion.ficha.codigoFicha : '',
                'Programa': asignacion.ficha ? asignacion.ficha.nombrePrograma : '',
                'Transversales': asignacion.transversales ? 
                    `${FRANJAS.find(f => f.idFranja == asignacion.transversales.idFranja).nombre} - T${asignacion.transversales.trimestre}` : ''
            });
        }
    }
    
    exportarCSV(datosExportar, 'horarios_medellin.csv');
}

function exportarCSV(datos, filename) {
    const headers = Object.keys(datos[0]);
    let csv = headers.join(',') + '\n';
    
    datos.forEach(row => {
        csv += headers.map(h => `"${row[h] || ''}"`).join(',') + '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    
    showToast('success', 'Archivo exportado correctamente');
}

function limpiarTodo() {
    Swal.fire({
        title: '¿Limpiar todos los horarios?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            horarios = {};
            $('.drop-zone').empty();
            $('.transversales-container').html('<div class="sin-transversales"><i class="fa fa-clock"></i> Sin transversales configuradas</div>');
            actualizarEstadisticas();
            aplicarFiltros();
            
            showToast('success', 'Todos los horarios han sido limpiados');
        }
    });
}

// ========================================
// UTILIDADES
// ========================================

function showToast(type, message) {
    const bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
    
    Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: bgColor,
        stopOnFocus: true
    }).showToast();
}

console.log('✅ Sistema de gestión de horarios Medellín cargado');

// ========================================
// MÓDULO: SELECCIÓN DE AMBIENTES - SEDE MEDELLIN
// ========================================

/**
 * Funcionalidad para la selección de ambientes en la sede Medellín
 * Este módulo maneja la carga, filtrado y selección de ambientes
 */

(function() {
    console.log('🏢 Módulo: Elección de Ambientes');
    
    // Variables del módulo
    let idSede = null;
    let nombreSede = null;
    let ciudad = null;
    let tablaAmbientes = null;
    let ambientesData = [];
    
    // Inicializar con datos del PHP
    function inicializarModulo(datosSede) {
        idSede = datosSede.idSede;
        nombreSede = datosSede.nombreSede;
        ciudad = datosSede.ciudad;
        
        // Cargar ambientes al iniciar
        if (idSede) {
            cargarAmbientes();
        } else {
            mostrarError('No se especificó una sede válida');
        }
    }
    
    // Exponer función de inicialización globalmente
    window.inicializarAmbienteSedeMedellin = inicializarModulo;
    
    function cargarAmbientes(){
        fetch("controlador/ambienteControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `listarAmbientesPorSede=ok&idSede=${idSede}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('📦 Ambientes recibidos:', data);
            
            if(data.codigo === "200"){
                ambientesData = data.ambientes;
                renderizarTabla(ambientesData);
            } else {
                mostrarError('Error al cargar ambientes: ' + (data.mensaje || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            mostrarError('Error de conexión al servidor');
        });
    }

    function renderizarTabla(ambientes){
        const dataSet = ambientes.map(amb => {
            const capacidadBadge = getCapacidadBadge(amb.capacidad);
            const estadoBadge = amb.estado === 'activo' ? 
                '<span class="badge bg-success">Activo</span>' : 
                '<span class="badge bg-secondary">Inactivo</span>';
            
            const botones = `
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info" onclick="verDetalleAmbiente(${amb.idAmbiente})" title="Ver detalles">
                        <i class="bi bi-info-circle"></i>
                    </button>
                    <button class="btn btn-sm btn-primary-custom" 
                            onclick="seleccionarAmbiente(${amb.idAmbiente}, '${amb.codigo}', '${amb.numero}')" 
                            title="Asignar a este ambiente">
                        <i class="bi bi-arrow-right-circle me-1"></i> Asignar
                    </button>
                </div>
            `;

            return [
                amb.codigo || '-',
                `${amb.numero || '-'} ${estadoBadge}`,
                capacidadBadge,
                amb.descripcion || '-',
                amb.ubicacion || '-',
                botones
            ];
        });

        // Destruir tabla existente si existe
        if($.fn.DataTable.isDataTable('#tablaAmbienteMedellin')){
            $('#tablaAmbienteMedellin').DataTable().destroy();
        }

        // Crear DataTable
        tablaAmbientes = $('#tablaAmbienteMedellin').DataTable({
            data: dataSet,
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            order: [[0, 'asc']],
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer me-1"></i> Imprimir',
                    className: 'btn btn-secondary btn-sm'
                }
            ]
        });

        configurarFiltros();
    }

    function getCapacidadBadge(capacidad){
        const cap = parseInt(capacidad) || 0;
        let colorClass = 'bg-secondary';
        let icon = '👥';
        
        if(cap <= 20) {
            colorClass = 'bg-info';
            icon = '👥';
        } else if(cap <= 30) {
            colorClass = 'bg-primary';
            icon = '👥👥';
        } else if(cap <= 40) {
            colorClass = 'bg-warning';
            icon = '👥👥👥';
        } else {
            colorClass = 'bg-danger';
            icon = '👥👥👥👥';
        }
        
        return `<span class="badge ${colorClass}">${icon} ${cap} personas</span>`;
    }

    function configurarFiltros(){
        // Filtro de capacidad personalizado
        $('#filtroCapacidad').on('change', function(){
            const valor = $(this).val();
            if(!valor){
                tablaAmbientes.column(2).search('').draw();
                return;
            }
            
            const filtroAmbientes = ambientesData.filter(amb => {
                const cap = parseInt(amb.capacidad) || 0;
                if(valor === '1-20') return cap >= 1 && cap <= 20;
                if(valor === '21-30') return cap >= 21 && cap <= 30;
                if(valor === '31-40') return cap >= 31 && cap <= 40;
                if(valor === '41+') return cap >= 41;
                return true;
            });
            
            renderizarTabla(filtroAmbientes);
        });

        // Búsqueda general
        $('#buscarAmbiente').on('keyup', function(){
            tablaAmbientes.search(this.value).draw();
        });

        // Filtro de estado
        $('#filtroEstado').on('change', function(){
            const valor = $(this).val();
            tablaAmbientes.column(1).search(valor, true, false).draw();
        });
    }

    // Función global: Seleccionar ambiente y avanzar
    window.seleccionarAmbiente = function(idAmbiente, codigo, numero){
        console.log('✅ Ambiente seleccionado:', idAmbiente, codigo, numero);
        
        // Redirigir a la siguiente pantalla con los parámetros necesarios
        const params = new URLSearchParams({
            idSede: idSede,
            sede: nombreSede,
            ciudad: ciudad,
            idAmbiente: idAmbiente,
            ambiente: `${codigo} - ${numero}`
        });
        
        window.location.href = `asignacionJornada?${params.toString()}`;
    };

    // Función global: Ver detalle
    window.verDetalleAmbiente = function(idAmbiente){
        const ambiente = ambientesData.find(a => a.idAmbiente == idAmbiente);
        if(!ambiente){
            alert('Ambiente no encontrado');
            return;
        }

        const contenido = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Código:</strong> ${ambiente.codigo || '-'}</p>
                    <p><strong>Número:</strong> ${ambiente.numero || '-'}</p>
                    <p><strong>Capacidad:</strong> ${ambiente.capacidad || '-'} personas</p>
                    <p><strong>Estado:</strong> 
                        <span class="badge ${ambiente.estado === 'activo' ? 'bg-success' : 'bg-secondary'}">
                            ${ambiente.estado || '-'}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Descripción:</strong> ${ambiente.descripcion || '-'}</p>
                    <p><strong>Ubicación:</strong> ${ambiente.ubicacion || '-'}</p>
                    <p><strong>Sede:</strong> ${ambiente.sedeNombre || '-'}</p>
                    <p><strong>Ciudad:</strong> ${ambiente.sedeMunicipio || '-'}</p>
                </div>
            </div>
        `;

        document.getElementById('contenidoModalAmbiente').innerHTML = contenido;
        new bootstrap.Modal(document.getElementById('modalInfoAmbiente')).show();
    };

    // Función global: Limpiar filtros
    window.limpiarFiltros = function(){
        $('#filtroCapacidad').val('');
        $('#buscarAmbiente').val('');
        $('#filtroEstado').val('');
        renderizarTabla(ambientesData);
    };

    function mostrarError(mensaje){
        const tbody = document.querySelector('#tablaAmbienteMedellin tbody');
        if(tbody){
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger py-5">
                        <i class="bi bi-exclamation-octagon me-2" style="font-size: 2rem;"></i>
                        <br>${mensaje}
                    </td>
                </tr>
            `;
        }
    }

    console.log('✅ Módulo de selección de ambientes cargado');
})();
