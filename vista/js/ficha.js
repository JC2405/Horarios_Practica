(function(){
    console.log('🚀 Módulo crearFicha_mejorado.js iniciado');

    // ========== VARIABLES GLOBALES ==========
    let duracionMesesSeleccionado = null;
    let municipios = [];
    let sedes = [];
    let ambientes = [];
    let programas = [];
    let currentSection = 1;

    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        cargarMunicipios();
        cargarProgramas();
        configurarEventListeners();
    });

    // ========== CONFIGURAR EVENT LISTENERS ==========
    function configurarEventListeners() {
        // Cambios en selects
        document.getElementById('codigo').addEventListener('input', actualizarResumen);
        document.getElementById('jornada').addEventListener('change', actualizarResumen);
        document.getElementById('selectMunicipio').addEventListener('change', onMunicipioChange);
        document.getElementById('selectSede').addEventListener('change', onSedeChange);
        document.getElementById('selectAmbiente').addEventListener('change', onAmbienteChange);
        document.getElementById('selectPrograma').addEventListener('change', onProgramaChange);
        document.getElementById('fecha_inicio').addEventListener('change', calcularFechaFin);
        
        // Submit del formulario
        document.getElementById('formCrearFicha').addEventListener('submit', onSubmit);
    }

    // ========== NAVEGACIÓN ENTRE SECCIONES ==========
    window.nextSection = function(sectionNumber) {
        // Validar sección actual antes de avanzar
        if (!validateSection(currentSection)) {
            return;
        }
        
        // Ocultar sección actual
        document.getElementById(`section${currentSection}`).classList.remove('active');
        document.getElementById(`step${currentSection}`).classList.remove('active');
        document.getElementById(`step${currentSection}`).classList.add('completed');
        
        // Cambiar iconos
        const currentIcon = document.querySelector(`#step${currentSection} .step-icon i`);
        currentIcon.className = 'bi bi-check-circle-fill';
        
        // Mostrar nueva sección
        currentSection = sectionNumber;
        const newSection = document.getElementById(`section${sectionNumber}`);
        newSection.classList.add('active', 'next');
        document.getElementById(`step${sectionNumber}`).classList.add('active');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        setTimeout(() => {
            newSection.classList.remove('next');
        }, 400);
    };

    window.prevSection = function(sectionNumber) {
        // Ocultar sección actual
        document.getElementById(`section${currentSection}`).classList.remove('active');
        document.getElementById(`step${currentSection}`).classList.remove('active');
        
        // Mostrar sección anterior
        currentSection = sectionNumber;
        const prevSectionEl = document.getElementById(`section${sectionNumber}`);
        prevSectionEl.classList.add('active', 'prev');
        document.getElementById(`step${sectionNumber}`).classList.add('active');
        document.getElementById(`step${sectionNumber}`).classList.remove('completed');
        
        // Restaurar icono
        const icon = document.querySelector(`#step${sectionNumber} .step-icon i`);
        icon.className = `bi bi-${sectionNumber}-circle-fill`;
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        setTimeout(() => {
            prevSectionEl.classList.remove('prev');
        }, 400);
    };

    // ========== VALIDACIÓN DE SECCIONES ==========
    function validateSection(sectionNumber) {
        let isValid = true;
        let errorMessage = '';
        
        switch(sectionNumber) {
            case 1:
                const codigo = document.getElementById('codigo').value.trim();
                const jornada = document.getElementById('jornada').value;
                
                if (!codigo) {
                    errorMessage = 'Por favor ingrese el código de la ficha';
                    isValid = false;
                } else if (!jornada) {
                    errorMessage = 'Por favor seleccione una jornada';
                    isValid = false;
                }
                break;
                
            case 2:
                const idMunicipio = document.getElementById('idMunicipio').value;
                const idSede = document.getElementById('idSede').value;
                const idAmbiente = document.getElementById('idAmbiente').value;
                
                if (!idMunicipio) {
                    errorMessage = 'Por favor seleccione un municipio';
                    isValid = false;
                } else if (!idSede) {
                    errorMessage = 'Por favor seleccione una sede';
                    isValid = false;
                } else if (!idAmbiente) {
                    errorMessage = 'Por favor seleccione un ambiente';
                    isValid = false;
                }
                break;
                
            case 3:
                const idPrograma = document.getElementById('idPrograma').value;
                
                if (!idPrograma) {
                    errorMessage = 'Por favor seleccione un programa';
                    isValid = false;
                }
                break;
        }
        
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: errorMessage,
                confirmButtonColor: '#7c6bff'
            });
        }
        
        return isValid;
    }

    // ========== CARGAR MUNICIPIOS ==========
    function cargarMunicipios() {
        fetch("controlador/fichaControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "listarMunicipios=ok"
        })
        .then(r => r.json())
        .then(response => {
            if(response.codigo === "200"){
                municipios = response.listarMunicipios;
                renderizarMunicipios();
                console.log('✅ Municipios cargados:', municipios.length);
            } else {
                console.error('❌ Error:', response.mensaje);
            }
        })
        .catch(err => {
            console.error('❌ Error cargando municipios:', err);
            mostrarError('selectMunicipio', 'Error al cargar municipios');
        });
    }

    function renderizarMunicipios() {
        const select = document.getElementById('selectMunicipio');
        select.innerHTML = '<option value="">Seleccione un municipio...</option>';
        
        municipios.forEach(mun => {
            const option = document.createElement('option');
            option.value = mun.idMunicipio;
            option.textContent = mun.nombreMunicipio;
            select.appendChild(option);
        });
    }

    function onMunicipioChange(e) {
        const idMunicipio = e.target.value;
        document.getElementById('idMunicipio').value = idMunicipio;
        
        if (idMunicipio) {
            const municipio = municipios.find(m => m.idMunicipio == idMunicipio);
            document.getElementById('summaryMunicipio').textContent = municipio.nombreMunicipio;
            document.getElementById('summaryMunicipio').classList.remove('text-muted');
            
            cargarSedes(idMunicipio);
        } else {
            resetearSedes();
            resetearAmbientes();
        }
    }

    // ========== CARGAR SEDES ==========
    function cargarSedes(idMunicipio) {
        const selectSede = document.getElementById('selectSede');
        selectSede.innerHTML = '<option value="">Cargando sedes...</option>';
        selectSede.disabled = true;
        
        fetch("controlador/fichaControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `listarSedesPorMunicipio=ok&idMunicipio=${idMunicipio}`
        })
        .then(r => r.json())
        .then(response => {
            console.log('📦 Respuesta sedes:', response);
            
            if(response.codigo === "200"){
                sedes = response.listarSedes;
                renderizarSedes();
                console.log('✅ Sedes cargadas:', sedes.length);
            } else {
                console.error('❌ Error:', response.mensaje);
                mostrarError('selectSede', 'No hay sedes disponibles');
            }
        })
        .catch(err => {
            console.error('❌ Error cargando sedes:', err);
            mostrarError('selectSede', 'Error al cargar sedes');
        });
    }

    function renderizarSedes() {
        const select = document.getElementById('selectSede');
        
        if(sedes.length === 0){
            select.innerHTML = '<option value="">No hay sedes disponibles</option>';
            select.disabled = true;
            return;
        }
        
        select.innerHTML = '<option value="">Seleccione una sede...</option>';
        
        sedes.forEach(sede => {
            const option = document.createElement('option');
            option.value = sede.idSede;
            option.textContent = sede.nombre;
            select.appendChild(option);
        });
        
        select.disabled = false;
    }

    function onSedeChange(e) {
        const idSede = e.target.value;
        document.getElementById('idSede').value = idSede;
        
        if (idSede) {
            const sede = sedes.find(s => s.idSede == idSede);
            document.getElementById('summarySede').textContent = sede.nombre;
            document.getElementById('summarySede').classList.remove('text-muted');
            
            cargarAmbientes(idSede);
        } else {
            resetearAmbientes();
        }
    }

    // ========== CARGAR AMBIENTES ==========
    function cargarAmbientes(idSede) {
        const selectAmbiente = document.getElementById('selectAmbiente');
        selectAmbiente.innerHTML = '<option value="">Cargando ambientes...</option>';
        selectAmbiente.disabled = true;
        
        console.log('🔄 Cargando ambientes para sede:', idSede);
        
        fetch("controlador/fichaControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `listarAmbientesPorSede=ok&idSede=${idSede}`
        })
        .then(r => r.json())
        .then(response => {
            console.log('📦 Respuesta ambientes:', response);
            
            if(response.codigo === "200"){
                ambientes = response.listarAmbientes;
                renderizarAmbientes();
                console.log('✅ Ambientes cargados:', ambientes.length);
            } else {
                console.error('❌ Error:', response.mensaje);
                mostrarError('selectAmbiente', 'No hay ambientes disponibles');
            }
        })
        .catch(err => {
            console.error('❌ Error cargando ambientes:', err);
            mostrarError('selectAmbiente', 'Error al cargar ambientes');
        });
    }

    function renderizarAmbientes() {
        const select = document.getElementById('selectAmbiente');
        
        if(ambientes.length === 0){
            select.innerHTML = '<option value="">No hay ambientes disponibles</option>';
            select.disabled = true;
            return;
        }
        
        select.innerHTML = '<option value="">Seleccione un ambiente...</option>';
        
        ambientes.forEach(amb => {
            const option = document.createElement('option');
            option.value = amb.idAmbiente;
            option.textContent = `${amb.codigo} - Número: ${amb.numero} (Capacidad: ${amb.capacidad || 'N/A'})`;
            select.appendChild(option);
        });
        
        select.disabled = false;
    }

    function onAmbienteChange(e) {
        const idAmbiente = e.target.value;
        document.getElementById('idAmbiente').value = idAmbiente;
        
        if (idAmbiente) {
            const ambiente = ambientes.find(a => a.idAmbiente == idAmbiente);
            document.getElementById('summaryAmbiente').textContent = `${ambiente.codigo} - #${ambiente.numero}`;
            document.getElementById('summaryAmbiente').classList.remove('text-muted');
        } else {
            document.getElementById('summaryAmbiente').textContent = 'No especificado';
            document.getElementById('summaryAmbiente').classList.add('text-muted');
        }
    }

    // ========== CARGAR PROGRAMAS ==========
    function cargarProgramas() {
        fetch("controlador/fichaControlador.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "listarProgramas=ok"
        })
        .then(r => r.json())
        .then(response => {
            if(response.codigo === "200"){
                programas = response.listarProgramas;
                renderizarProgramas();
                console.log('✅ Programas cargados:', programas.length);
            } else {
                console.error('❌ Error:', response.mensaje);
            }
        })
        .catch(err => {
            console.error('❌ Error cargando programas:', err);
            mostrarError('selectPrograma', 'Error al cargar programas');
        });
    }

    function renderizarProgramas() {
        const select = document.getElementById('selectPrograma');
        select.innerHTML = '<option value="">Seleccione un programa...</option>';
        
        programas.forEach(prog => {
            const option = document.createElement('option');
            option.value = prog.idPrograma;
            option.textContent = `${prog.nombre} (${prog.tipoFormacion} - ${prog.duracion} meses)`;
            option.dataset.duracion = prog.duracion;
            select.appendChild(option);
        });
    }

    function onProgramaChange(e) {
        const idPrograma = e.target.value;
        document.getElementById('idPrograma').value = idPrograma;
        
        if (idPrograma) {
            const programa = programas.find(p => p.idPrograma == idPrograma);
            document.getElementById('summaryPrograma').textContent = programa.nombre;
            document.getElementById('summaryPrograma').classList.remove('text-muted');
            
            // Guardar duración
            duracionMesesSeleccionado = parseInt(programa.duracion);
            document.getElementById('duracionMeses').value = duracionMesesSeleccionado;
            document.getElementById('duracionValue').textContent = duracionMesesSeleccionado;
            document.getElementById('infoDuracion').style.display = 'block';
            
            // Recalcular fecha fin si hay fecha inicio
            calcularFechaFin();
        } else {
            document.getElementById('summaryPrograma').textContent = 'No especificado';
            document.getElementById('summaryPrograma').classList.add('text-muted');
            document.getElementById('infoDuracion').style.display = 'none';
            duracionMesesSeleccionado = null;
        }
    }

    // ========== CALCULAR FECHA FIN ==========
    function calcularFechaFin() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        
        if(!fechaInicio || !duracionMesesSeleccionado){
            return;
        }
        
        console.log('📅 Calculando fecha fin...');
        console.log('   Fecha inicio:', fechaInicio);
        console.log('   Duración:', duracionMesesSeleccionado, 'meses');
        
        const fecha = new Date(fechaInicio + 'T00:00:00');
        fecha.setMonth(fecha.getMonth() + duracionMesesSeleccionado);
        
        const year = fecha.getFullYear();
        const month = String(fecha.getMonth() + 1).padStart(2, '0');
        const day = String(fecha.getDate()).padStart(2, '0');
        
        const fechaFin = `${year}-${month}-${day}`;
        document.getElementById('fecha_fin').value = fechaFin;
        
        // Actualizar resumen
        const fechaInicioFormat = new Date(fechaInicio).toLocaleDateString('es-CO');
        const fechaFinFormat = new Date(fechaFin).toLocaleDateString('es-CO');
        document.getElementById('summaryFechas').textContent = `${fechaInicioFormat} - ${fechaFinFormat}`;
        document.getElementById('summaryFechas').classList.remove('text-muted');
        
        console.log('   Fecha fin:', fechaFin);
    }

    // ========== ACTUALIZAR RESUMEN ==========
    function actualizarResumen() {
        const codigo = document.getElementById('codigo').value.trim();
        const jornada = document.getElementById('jornada').value;
        
        if (codigo) {
            document.getElementById('summaryCodigo').textContent = codigo;
            document.getElementById('summaryCodigo').classList.remove('text-muted');
        } else {
            document.getElementById('summaryCodigo').textContent = 'No especificado';
            document.getElementById('summaryCodigo').classList.add('text-muted');
        }
        
        if (jornada) {
            const jornadaTexts = {
                'MAÑANA': '🌅 Mañana',
                'TARDE': '☀️ Tarde',
                'NOCHE': '🌙 Noche'
            };
            document.getElementById('summaryJornada').textContent = jornadaTexts[jornada] || jornada;
            document.getElementById('summaryJornada').classList.remove('text-muted');
        } else {
            document.getElementById('summaryJornada').textContent = 'No especificado';
            document.getElementById('summaryJornada').classList.add('text-muted');
        }
    }

    // ========== RESETEAR CAMPOS ==========
    function resetearSedes() {
        const select = document.getElementById('selectSede');
        select.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        select.disabled = true;
        document.getElementById('idSede').value = '';
        document.getElementById('summarySede').textContent = 'No especificado';
        document.getElementById('summarySede').classList.add('text-muted');
    }

    function resetearAmbientes() {
        const select = document.getElementById('selectAmbiente');
        select.innerHTML = '<option value="">Primero seleccione una sede</option>';
        select.disabled = true;
        document.getElementById('idAmbiente').value = '';
        document.getElementById('summaryAmbiente').textContent = 'No especificado';
        document.getElementById('summaryAmbiente').classList.add('text-muted');
    }

    function mostrarError(selectId, mensaje) {
        const select = document.getElementById(selectId);
        select.innerHTML = `<option value="">${mensaje}</option>`;
        select.disabled = true;
    }

    // ========== SUBMIT FORMULARIO ==========
    function onSubmit(event) {
        event.preventDefault();
        event.stopPropagation();
        
        // Validar todos los campos
        const codigo = document.getElementById('codigo').value.trim();
        const idPrograma = document.getElementById('idPrograma').value;
        const idAmbiente = document.getElementById('idAmbiente').value;
        const jornada = document.getElementById('jornada').value;
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        if (!codigo || !idPrograma || !idAmbiente || !jornada || !fechaInicio || !fechaFin) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos del formulario',
                confirmButtonColor: '#7c6bff'
            });
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Creando ficha...',
            html: 'Por favor espere',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Crear FormData
        const formData = new FormData();
        formData.append('registrarFicha', 'ok');
        formData.append('codigoFicha', codigo);
        formData.append('idPrograma', idPrograma);
        formData.append('idAmbiente', idAmbiente);
        formData.append('estado', 'Activo');
        formData.append('jornada', jornada);
        formData.append('fechaInicio', fechaInicio);
        formData.append('fechaFin', fechaFin);
        
        // Enviar al servidor
        fetch('controlador/fichaControlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('📨 Respuesta:', data);
            
            if(data.codigo === "200"){
                Swal.fire({
                    icon: 'success',
                    title: '¡Ficha creada!',
                    text: data.mensaje,
                    confirmButtonColor: '#7c6bff'
                }).then(() => {
                    // Recargar página o redirigir
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.mensaje || 'Error al crear la ficha',
                    confirmButtonColor: '#7c6bff'
                });
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor',
                confirmButtonColor: '#7c6bff'
            });
        });
    }

    console.log('✅ Módulo crearFicha_mejorado.js configurado completamente');

})();