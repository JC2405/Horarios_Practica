(function(){

    console.log('🚀 Módulo ficha.js iniciado');

    // ========== VARIABLES GLOBALES ==========
    let duracionMesesSeleccionado = null;
    let municipios = [];
    let sedes = [];
    let ambientes = [];
    let programas = [];

    // ========== CARGAR DATOS INICIALES ==========
    cargarMunicipios();
    cargarProgramas();

    // ========== CARGAR MUNICIPIOS ==========
    function cargarMunicipios(){
        let objData = new FormData();
        objData.append("listarMunicipios", "ok");

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            if(response.codigo === "200"){
                municipios = response.listarMunicipios;
                console.log('✅ Municipios cargados:', municipios.length);
            }
        })
        .catch(err => console.error('❌ Error cargando municipios:', err));
    }

    // ========== CARGAR PROGRAMAS ==========
    function cargarProgramas(){
        let objData = new FormData();
        objData.append("listarProgramas", "ok");

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            if(response.codigo === "200"){
                programas = response.listarProgramas;
                console.log('✅ Programas cargados:', programas.length);
            }
        })
        .catch(err => console.error('❌ Error cargando programas:', err));
    }

    // ========== ABRIR PANEL ==========
    window.openPanel = function(tipo){
        const panel = document.getElementById('panel');
        const panelTitle = document.getElementById('panelTitle');
        const panelSubtitle = document.getElementById('panelSubtitle');
        const panelList = document.getElementById('panelList');
        const panelSearch = document.getElementById('panelSearch');

        panel.style.display = 'block';
        panelList.innerHTML = '<div class="empty">Cargando...</div>';
        panelSearch.value = ''; // Limpiar búsqueda

        switch(tipo){
            case 'municipio':
                panelTitle.textContent = 'Seleccionar Municipio';
                panelSubtitle.textContent = 'Elige el municipio de la sede';
                renderizarMunicipios();
                break;

            case 'sede':
                const municipioNombre = document.getElementById('txtMunicipio').textContent;
                panelTitle.textContent = 'Seleccionar Sede';
                panelSubtitle.textContent = municipioNombre !== 'Seleccionar municipio…' 
                    ? `Sedes en ${municipioNombre}` 
                    : 'Elige la sede';
                cargarSedes();
                break;

            case 'ambiente':
                const sedeNombre = document.getElementById('txtSede').textContent;
                panelTitle.textContent = 'Seleccionar Ambiente';
                panelSubtitle.textContent = sedeNombre !== 'Seleccionar sede…' 
                    ? `Ambientes en ${sedeNombre}` 
                    : 'Elige el ambiente';
                cargarAmbientes();
                break;

            case 'programa':
                panelTitle.textContent = 'Seleccionar Programa';
                panelSubtitle.textContent = 'Elige el programa de formación';
                renderizarProgramas();
                break;
        }
    };

    // ========== CERRAR PANEL ==========
    window.closePanel = function(){
        const panel = document.getElementById('panel');
        panel.style.display = 'none';
    };

    // ========== FILTRAR PANEL ==========
    window.filterPanel = function(){
        const termino = document.getElementById('panelSearch').value.toLowerCase();
        const opciones = document.querySelectorAll('.option');
        
        opciones.forEach(opt => {
            // No filtrar el botón de atrás
            if(opt.classList.contains('btn-atras')){
                return;
            }
            
            const texto = opt.textContent.toLowerCase();
            opt.style.display = texto.includes(termino) ? 'block' : 'none';
        });
    };

    // ========== RENDERIZAR MUNICIPIOS ==========
    function renderizarMunicipios(){
        const panelList = document.getElementById('panelList');
        panelList.innerHTML = '';

        if(municipios.length === 0){
            panelList.innerHTML = '<div class="empty">No hay municipios disponibles</div>';
            return;
        }

        municipios.forEach(mun => {
            const div = document.createElement('div');
            div.className = 'option';
            div.textContent = mun.nombreMunicipio;
            div.addEventListener('click', () => seleccionarMunicipio(mun));
            panelList.appendChild(div);
        });

        console.log('✅ Municipios renderizados:', municipios.length);
    }

    // ========== SELECCIONAR MUNICIPIO ==========
    function seleccionarMunicipio(mun){
        console.log('📍 Municipio seleccionado:', mun.nombreMunicipio);
        
        // Guardar selección
        document.getElementById('idMunicipio').value = mun.idMunicipio;
        document.getElementById('txtMunicipio').textContent = mun.nombreMunicipio;
        document.getElementById('hintMunicipio').textContent = '✓ Seleccionado';
        
        // Habilitar botón de sede
        document.getElementById('btnSede').disabled = false;
        document.getElementById('hintSede').textContent = 'Click para buscar';
        
        // Reset campos posteriores
        resetearAmbiente();
        
        // 🔥 CARGAR SEDES AUTOMÁTICAMENTE EN EL MISMO PANEL
        const panelTitle = document.getElementById('panelTitle');
        const panelSubtitle = document.getElementById('panelSubtitle');
        const panelList = document.getElementById('panelList');
        const panelSearch = document.getElementById('panelSearch');
        
        panelTitle.textContent = 'Seleccionar Sede';
        panelSubtitle.textContent = `Sedes disponibles en ${mun.nombreMunicipio}`;
        panelList.innerHTML = `
            <div class="empty">
                <i class="bi bi-hourglass-split"></i> 
                Cargando sedes de ${mun.nombreMunicipio}...
            </div>
        `;
        
        panelSearch.value = ''; // Limpiar búsqueda
        
        // Cargar sedes del municipio seleccionado
        cargarSedes();
    }

    // ========== CARGAR SEDES ==========
    function cargarSedes(){
        const idMunicipio = document.getElementById('idMunicipio').value;
        
        if(!idMunicipio){
            console.warn('⚠️ No hay municipio seleccionado');
            const panelList = document.getElementById('panelList');
            panelList.innerHTML = `
                <div class="empty" style="color: #f59e0b;">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Primero selecciona un municipio
                </div>
            `;
            return;
        }

        console.log('🔄 Cargando sedes del municipio:', idMunicipio);

        let objData = new FormData();
        objData.append("listarSedesPorMunicipio", "ok");
        objData.append("idMunicipio", idMunicipio);

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            console.log('📦 Respuesta sedes:', response);
            
            if(response.codigo === "200"){
                sedes = response.listarSedes;
                renderizarSedes();
            } else {
                console.error('❌ Error:', response.mensaje);
                const panelList = document.getElementById('panelList');
                panelList.innerHTML = `
                    <div class="empty" style="color: #ef4444;">
                        <i class="bi bi-x-circle"></i> 
                        Error al cargar sedes
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('❌ Error en petición:', err);
            const panelList = document.getElementById('panelList');
            panelList.innerHTML = `
                <div class="empty" style="color: #ef4444;">
                    <i class="bi bi-wifi-off"></i> 
                    Error de conexión
                </div>
            `;
        });
    }

    // ========== RENDERIZAR SEDES ==========
    function renderizarSedes(){
        const panelList = document.getElementById('panelList');
        panelList.innerHTML = '';

        // 🔥 BOTÓN ATRÁS - Volver a municipios
        const btnAtras = document.createElement('div');
        btnAtras.className = 'option btn-atras';
        btnAtras.style.cssText = `
            background: linear-gradient(135deg, #f0f2ff 0%, #e0e4ff 100%);
            color: #7c6bff;
            font-weight: 700;
            border-left: 4px solid #7c6bff;
            cursor: pointer;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        `;
        btnAtras.innerHTML = '<i class="bi bi-arrow-left"></i> Volver a municipios';
        btnAtras.addEventListener('click', () => {
            console.log('⬅️ Volviendo a municipios...');
            openPanel('municipio');
        });
        panelList.appendChild(btnAtras);

        // Verificar si hay sedes
        if(sedes.length === 0){
            const divEmpty = document.createElement('div');
            divEmpty.className = 'empty';
            divEmpty.style.cssText = 'padding: 30px 20px; color: #6c757d;';
            divEmpty.innerHTML = `
                <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                No hay sedes disponibles en este municipio
            `;
            panelList.appendChild(divEmpty);
            console.log('⚠️ No hay sedes en este municipio');
            return;
        }

        // Renderizar sedes
        sedes.forEach(sede => {
            const div = document.createElement('div');
            div.className = 'option';
            div.innerHTML = `
                <strong>${sede.nombre}</strong>
                ${sede.direccion ? `<br><small style="color: #6c757d;">${sede.direccion}</small>` : ''}
            `;
            div.addEventListener('click', () => seleccionarSede(sede));
            panelList.appendChild(div);
        });

        console.log('✅ Sedes renderizadas:', sedes.length);
    }

    // ========== SELECCIONAR SEDE ==========
    function seleccionarSede(sede){
    console.log('🏢 Sede seleccionada:', sede.nombre);

    document.getElementById('idSede').value = sede.idSede;
    document.getElementById('txtSede').textContent = sede.nombre;
    document.getElementById('hintSede').textContent = '✓ Seleccionado';

    // Reset SOLO valores del ambiente (sin deshabilitar)
    document.getElementById('idAmbiente').value = '';
    document.getElementById('txtAmbiente').textContent = 'Seleccionar ambiente…';
    document.getElementById('hintAmbiente').textContent = 'Click para buscar';

    // Habilitar ambiente al final
    document.getElementById('btnAmbiente').disabled = false;

    closePanel();
}

    // ========== CARGAR AMBIENTES ==========
    function cargarAmbientes(){
        const idSede = document.getElementById('idSede').value;
        
        if(!idSede){
            console.warn('⚠️ No hay sede seleccionada');
            const panelList = document.getElementById('panelList');
            panelList.innerHTML = `
                <div class="empty" style="color: #f59e0b;">
                    <i class="bi bi-exclamation-triangle"></i> 
                    Primero selecciona una sede
                </div>
            `;
            return;
        }

        console.log('🔄 Cargando ambientes de la sede:', idSede);

        let objData = new FormData();
        objData.append("listarAmbientesPorSede", "ok");
        objData.append("idSede", idSede);

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            console.log('📦 Respuesta ambientes:', response);
            
            if(response.codigo === "200"){
                ambientes = response.listarAmbientes;
                renderizarAmbientes();
            } else {
                console.error('❌ Error:', response.mensaje);
                const panelList = document.getElementById('panelList');
                panelList.innerHTML = `
                    <div class="empty" style="color: #ef4444;">
                        <i class="bi bi-x-circle"></i> 
                        Error al cargar ambientes
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('❌ Error en petición:', err);
            const panelList = document.getElementById('panelList');
            panelList.innerHTML = `
                <div class="empty" style="color: #ef4444;">
                    <i class="bi bi-wifi-off"></i> 
                    Error de conexión
                </div>
            `;
        });
    }

    // ========== RENDERIZAR AMBIENTES ==========
    function renderizarAmbientes(){
        const panelList = document.getElementById('panelList');
        panelList.innerHTML = '';

        if(ambientes.length === 0){
            const divEmpty = document.createElement('div');
            divEmpty.className = 'empty';
            divEmpty.style.cssText = 'padding: 30px 20px; color: #6c757d;';
            divEmpty.innerHTML = `
                <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                No hay ambientes disponibles en esta sede
            `;
            panelList.appendChild(divEmpty);
            console.log('⚠️ No hay ambientes en esta sede');
            return;
        }

        ambientes.forEach(amb => {
            const div = document.createElement('div');
            div.className = 'option';
            div.innerHTML = `
                <strong style="color: #7c6bff;">${amb.codigo}</strong> - Número: ${amb.numero}
                ${amb.capacidad ? `<br><small style="color: #6c757d;">Capacidad: ${amb.capacidad} personas</small>` : ''}
            `;
            div.addEventListener('click', () => seleccionarAmbiente(amb));
            panelList.appendChild(div);
        });

        console.log('✅ Ambientes renderizados:', ambientes.length);
    }

    // ========== SELECCIONAR AMBIENTE ==========
    function seleccionarAmbiente(amb){
        console.log('🚪 Ambiente seleccionado:', amb.codigo);
        
        document.getElementById('idAmbiente').value = amb.idAmbiente;
        document.getElementById('txtAmbiente').textContent = `${amb.codigo} - #${amb.numero}`;
        document.getElementById('hintAmbiente').textContent = '✓ Seleccionado';
        
        closePanel();
    }

    // ========== RENDERIZAR PROGRAMAS ==========
    function renderizarProgramas(){
        const panelList = document.getElementById('panelList');
        panelList.innerHTML = '';

        if(programas.length === 0){
            panelList.innerHTML = `
                <div class="empty" style="padding: 30px 20px; color: #6c757d;">
                    <i class="bi bi-info-circle" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                    No hay programas disponibles
                </div>
            `;
            return;
        }

        programas.forEach(prog => {
            const div = document.createElement('div');
            div.className = 'option';
            div.innerHTML = `
                <strong style="color: #7c6bff;">${prog.nombre}</strong>
                <br>
                <small style="color: #6c757d;">
                    <i class="bi bi-bookmark"></i> ${prog.tipoFormacion} | 
                    <i class="bi bi-clock"></i> ${prog.duracion} meses
                </small>
            `;
            div.addEventListener('click', () => seleccionarPrograma(prog));
            panelList.appendChild(div);
        });

        console.log('✅ Programas renderizados:', programas.length);
    }

    // ========== SELECCIONAR PROGRAMA ==========
    function seleccionarPrograma(prog){
        console.log('📚 Programa seleccionado:', prog.nombre);
        
        document.getElementById('idPrograma').value = prog.idPrograma;
        document.getElementById('txtPrograma').textContent = prog.nombre;
        document.getElementById('hintPrograma').textContent = '✓ Seleccionado';
        
        // Guardar duración
        duracionMesesSeleccionado = parseInt(prog.duracion);
        document.getElementById('duracionMeses').value = duracionMesesSeleccionado;
        document.getElementById('duracionValue').textContent = duracionMesesSeleccionado;
        document.getElementById('duracionLabel').style.display = 'block';
        
        // Calcular fecha fin si hay fecha inicio
        calcularFechaFin();
        
        closePanel();
    }

    // ========== CALCULAR FECHA FIN ==========
    function calcularFechaFin(){
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
        
        console.log('   Fecha fin:', fechaFin);
    }

    // Event listener para fecha inicio
    const fechaInicioInput = document.getElementById('fecha_inicio');
    if(fechaInicioInput){
        fechaInicioInput.addEventListener('change', calcularFechaFin);
    }

    // ========== RESET FUNCIONES ==========
    function resetearSede(){
        document.getElementById('idSede').value = '';
        document.getElementById('txtSede').textContent = 'Seleccionar sede…';
        document.getElementById('hintSede').textContent = 'Primero elige un municipio';
        document.getElementById('btnSede').disabled = true;
    }

    function resetearAmbiente(){
        document.getElementById('idAmbiente').value = '';
        document.getElementById('txtAmbiente').textContent = 'Seleccionar ambiente…';
        document.getElementById('hintAmbiente').textContent = 'Primero elige una sede';
        document.getElementById('btnAmbiente').disabled = true;
    }

    // ========== RESET COMPLETO DEL FORMULARIO ==========
    window.resetFicha = function(){
        console.log('🔄 Reseteando formulario...');
        
        document.getElementById('codigo').value = '';
        
        document.getElementById('idMunicipio').value = '';
        document.getElementById('txtMunicipio').textContent = 'Seleccionar municipio…';
        document.getElementById('hintMunicipio').textContent = 'Click para buscar';
        
        resetearSede();
        resetearAmbiente();
        
        document.getElementById('idPrograma').value = '';
        document.getElementById('txtPrograma').textContent = 'Seleccionar programa…';
        document.getElementById('hintPrograma').textContent = 'Click para buscar';
        
        document.getElementById('jornada').value = '';
        document.getElementById('fecha_inicio').value = '';
        document.getElementById('fecha_fin').value = '';
        
        duracionMesesSeleccionado = null;
        document.getElementById('duracionLabel').style.display = 'none';
        
        console.log('✅ Formulario reseteado');
    };

    console.log('✅ Módulo ficha.js configurado completamente');

    
  // ========== SUBMIT FORMULARIO CREAR FICHA ==========
const formCrearFicha = document.getElementById('formCrearFicha');
if(formCrearFicha){
    formCrearFicha.addEventListener('submit', function(event){
        event.preventDefault(); // ← Evita envío default
        event.stopPropagation();

        // ✅ PASO 1: Validar formulario HTML5
        if(!formCrearFicha.checkValidity()){
            formCrearFicha.classList.add('was-validated');
            
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor completa todos los campos requeridos'
            });
            return; // ← Detiene si hay errores
        }

        // ✅ PASO 2: Validar campos OCULTOS manualmente
        const codigo = document.getElementById('codigo').value;
        const idMunicipio = document.getElementById('idMunicipio').value;
        const idSede = document.getElementById('idSede').value;
        const idAmbiente = document.getElementById('idAmbiente').value;
        const idPrograma = document.getElementById('idPrograma').value;
        const jornada = document.getElementById('jornada').value;
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;

        // Verificar que TODOS tengan valor
        if(!codigo || !idMunicipio || !idSede || !idAmbiente || 
           !idPrograma || !jornada || !fechaInicio || !fechaFin){
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: 'Todos los campos son obligatorios. Verifica municipio, sede, ambiente y programa.'
            });
            return;
        }

        // ✅ PASO 3: Mostrar loading
        Swal.fire({
            title: 'Guardando ficha...',
            html: 'Por favor espera',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // ✅ PASO 4: Crear FormData correctamente
        const formData = new FormData();
        formData.append('registrarFicha', 'ok'); // ← Trigger del controlador
        formData.append('codigoFicha', codigo);
        formData.append('idPrograma', idPrograma);
        formData.append('idAmbiente', idAmbiente);
        formData.append('estado', 'Activo');
        formData.append('jornada', jornada);
        formData.append('fechaInicio', fechaInicio);
        formData.append('fechaFin', fechaFin);

        // ✅ PASO 5: Enviar al servidor
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
                    timer: 3000
                }).then(() => {
                    resetFicha(); // Limpiar formulario
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.mensaje
                });
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor.'
            });
        });
    }, false);
    }
})();