/**
 * HorarioCalendar — módulo global reutilizable
 * Uso: HorarioCalendar.abrirModal(idFicha, { ficha, sede, area, jornada, tipo })
 */
const HorarioCalendar = (function () {

    const COLORES = [
        '#7c6bff','#f59e0b','#10b981','#ef4444',
        '#3b82f6','#8b5cf6','#ec4899','#14b8a6','#f97316'
    ];

    const DIA_TO_DOW = {
        'Domingo':0,'Lunes':1,'Martes':2,
        'Miércoles':3,'Miercoles':3,
        'Jueves':4,'Viernes':5,'Sábado':6,'Sabado':6
    };

    let calendarInstance = null;

    /* ── El modal ya existe en el HTML, no lo creamos dinámicamente ── */
    function _llenarChips(info) {
        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val || '—';
        };
        set('calModal_ficha',   info.ficha);
        set('calModal_sede',    info.sede);
        set('calModal_area',    info.area);
        set('calModal_jornada', info.jornada);
        set('calModal_tipo',    info.tipo);
        // compatibilidad con chips alternativos que existen en algunas vistas
        set('calModal_instructor', '—');
        set('calModal_hora',       '—');
        set('calModal_fechas',     '—');
    }

    function _destruirCalendario() {
        if (calendarInstance) {
            try { calendarInstance.destroy(); } catch(e) {}
            calendarInstance = null;
        }
        const el  = document.getElementById('horarioCalendar');
        const ley = document.getElementById('calLeyendaInstructores');
        if (el)  el.innerHTML  = '';
        if (ley) ley.innerHTML = '';
    }

    function _buildEvents(horarios) {
        const coloresMap = {};
        let colorIdx = 0;
        const events = [];

        horarios.forEach(h => {
            const nombre = h.instructorNombre || '—';
            if (!coloresMap[nombre]) {
                coloresMap[nombre] = COLORES[colorIdx % COLORES.length];
                colorIdx++;
            }
            const color = coloresMap[nombre];
            const dias  = (h.diasNombres || '').split(',').map(d => d.trim()).filter(Boolean);

            dias.forEach(dia => {
                const dow = DIA_TO_DOW[dia];
                if (dow === undefined) return;
                events.push({
                    title:      nombre,
                    startTime:  h.hora_inicioClase || '00:00',
                    endTime:    h.hora_finClase    || '00:00',
                    daysOfWeek: [dow],
                    color,
                    extendedProps: {
                        ambiente:   h.ambienteNombre || '—',
                        horaInicio: h.hora_inicioClase,
                        horaFin:    h.hora_finClase,
                    }
                });
            });
        });

        return { events, coloresMap };
    }

    function _renderLeyenda(coloresMap) {
        const ley = document.getElementById('calLeyendaInstructores');
        if (!ley || !Object.keys(coloresMap).length) return;
        ley.innerHTML =
            '<div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">' +
            Object.entries(coloresMap).map(([nombre, color]) =>
                `<span style="display:inline-flex;align-items:center;gap:6px;font-size:12px;
                    background:${color}22;border:1px solid ${color};color:${color};
                    padding:3px 10px;border-radius:20px;font-weight:600;">
                    <span style="width:8px;height:8px;border-radius:50%;
                        background:${color};display:inline-block;"></span>
                    ${nombre}
                </span>`
            ).join('') + '</div>';
    }

    function _iniciarCalendario(events) {
        const el = document.getElementById('horarioCalendar');
        if (!el) { console.error('[HorarioCalendar] No existe #horarioCalendar en el DOM'); return; }
        if (typeof FullCalendar === 'undefined') { console.error('[HorarioCalendar] FullCalendar no está cargado'); return; }

        calendarInstance = new FullCalendar.Calendar(el, {
            initialView:   'timeGridWeek',
            locale:        'es',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'timeGridWeek,timeGridDay'
            },
            allDaySlot:  false,
            slotMinTime: '06:00:00',
            slotMaxTime: '24:00:00',
            height:      420,
            events,
            eventDidMount(info) {
                const p = info.event.extendedProps;
                info.el.setAttribute('title',
                    `${info.event.title}\n${p.horaInicio} – ${p.horaFin}\n${p.ambiente}`);
            }
        });
        calendarInstance.render();
    }

    /* ══════════════════════════════════════════════════
       API PÚBLICA
    ══════════════════════════════════════════════════ */
    function abrirModal(idFicha, info) {
        info = info || {};

        _destruirCalendario();
        _llenarChips(info);

        /* spinner */
        const calEl = document.getElementById('horarioCalendar');
        if (calEl) calEl.innerHTML =
            '<div style="text-align:center;padding:40px;color:#888;">' +
            '<i class="bi bi-hourglass-split" style="font-size:2rem;"></i>' +
            '<p style="margin-top:10px;">Cargando horarios...</p></div>';

        /* abrir modal Bootstrap */
        const modalEl = document.getElementById('modalVerHorario');
        if (!modalEl) { console.error('[HorarioCalendar] No existe #modalVerHorario en el DOM'); return; }
        bootstrap.Modal.getOrCreateInstance(modalEl).show();

        console.log('[HorarioCalendar] abrirModal idFicha =', idFicha);

        /* fetch */
        const fd = new FormData();
        fd.append('listarHorariosPorFicha', 'ok');
        fd.append('idFicha', idFicha);

        fetch('controlador/horarioControlador.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                console.log('[HorarioCalendar] resp =', resp);

                if (resp.codigo !== '200' || !resp.horarios || !resp.horarios.length) {
                    if (calEl) calEl.innerHTML =
                        '<div style="text-align:center;padding:40px;color:#888;">' +
                        '<i class="bi bi-calendar-x" style="font-size:2rem;"></i>' +
                        '<p style="margin-top:10px;">No hay horarios para esta ficha.</p></div>';
                    return;
                }

                var built = _buildEvents(resp.horarios);
                var events     = built.events;
                var coloresMap = built.coloresMap;

                function render() {
                    _destruirCalendario();
                    _iniciarCalendario(events);
                    _renderLeyenda(coloresMap);
                }

                /* si el modal ya está visible renderizar inmediatamente con delay mínimo,
                   si no, esperar el evento shown.bs.modal */
                if (modalEl.classList.contains('show')) {
                    setTimeout(render, 100);
                } else {
                    modalEl.addEventListener('shown.bs.modal', render, { once: true });
                }
            })
            .catch(function(err) {
                console.error('[HorarioCalendar] fetch error:', err);
                if (calEl) calEl.innerHTML =
                    '<div style="text-align:center;padding:40px;color:#c00;">' +
                    '<i class="bi bi-exclamation-triangle" style="font-size:2rem;"></i>' +
                    '<p style="margin-top:10px;">Error al cargar los horarios.</p></div>';
            });
    }

    return { abrirModal: abrirModal };

})();