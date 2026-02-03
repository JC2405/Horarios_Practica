<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Fichas Disponibles para Crear Horarios</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaTecnologos" class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Código Ficha</th>
                                    <th scope="col">Programa</th>
                                    <th scope="col">Duración (Meses)</th>
                                    <th scope="col">Jornada</th>
                                    <th scope="col">Ciudad</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llena con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    console.log('🚀 Cargando módulo listarFichaHorarios...');
    
    // Cargar tecnólogos al iniciar
    listarTecnologos();

    // Event listener para el botón crear horario
    $(document).on('click', '.btn-crear-horario', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var codigoFicha = $(this).data('codigoficha');
        console.log('📋 Código de ficha seleccionada:', codigoFicha);
        
        window.location.href = 'crearHorario?ficha=' + codigoFicha;
    });

    function listarTecnologos(){
        console.log('📊 Solicitando lista de tecnólogos...');
        
        let objData = new FormData();
        objData.append("listarTecnologos", "ok");

        fetch("controlador/fichaControlador.php", {
            method: "POST",
            body: objData
        })
        .then(response => {
            console.log('📡 Respuesta recibida, status:', response.status);
            return response.json();
        })
        .catch(error => {
            console.error('❌ Error en la petición:', error);
            mostrarError('Error de conexión al servidor');
            throw error;
        })
        .then(response => {
            console.log('📦 Datos recibidos:', response);
            
            if (response && response.codigo === "200") {
                let dataSet = [];
                
                if (!response.listarTecnologos || response.listarTecnologos.length === 0) {
                    console.warn('⚠️ No hay fichas de tecnólogos disponibles');
                    mostrarError('No hay fichas de tecnólogos disponibles en la base de datos');
                    return;
                }
                
                response.listarTecnologos.forEach(item => {
                    console.log('Procesando ficha:', item);
                    
                    let objBotones = `
                        <div class="btn-group" role="group">
                            <a class="btn btn-primary btn-sm btn-crear-horario"
                               href="crearHorario?ficha=${item.codigoFicha}"
                               data-codigoficha="${item.codigoFicha}">
                               <i class="bi bi-clock-fill me-1"></i>Crear Horario
                            </a>
                        </div>
                    `;

                    dataSet.push([
                        item.codigoFicha,
                        item.programa,
                        item.duracion,
                        item.jornada,
                        item.municipio,
                        objBotones
                    ]);
                });
                
                console.log('✅ DataSet preparado con', dataSet.length, 'filas');
                
                // Destruir tabla existente si existe
                if ($.fn.DataTable.isDataTable('#tablaTecnologos')) {
                    console.log('🗑️ Destruyendo tabla existente...');
                    $('#tablaTecnologos').DataTable().destroy();
                }
                
                // Crear nueva tabla
                console.log('🎨 Inicializando DataTable...');
                $('#tablaTecnologos').DataTable({
                    buttons: [
                        {
                            extend: "colvis",
                            text: "Columnas"
                        },
                        "excel",
                        "pdf",
                        "print"
                    ],
                    dom: "Bfrtip",
                    responsive: true,
                    destroy: true,
                    data: dataSet,
                    language: {
                        "decimal": "",
                        "emptyTable": "No hay fichas de tecnólogos disponibles",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ fichas",
                        "infoEmpty": "Mostrando 0 a 0 de 0 fichas",
                        "infoFiltered": "(filtrado de _MAX_ fichas totales)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ fichas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "No se encontraron fichas",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    }
                });
                
                console.log('✅ Tabla inicializada correctamente');
            } else {
                console.error('❌ Error en los datos:', response);
                mostrarError('Error al cargar las fichas: ' + (response?.mensaje || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('❌ Error procesando respuesta:', error);
        });
    }

    function mostrarError(mensaje) {
        const tbody = document.querySelector('#tablaTecnologos tbody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${mensaje}
                    </td>
                </tr>
            `;
        }
    }

})();
</script>

<style>
.btn-crear-horario {
    transition: all 0.3s ease;
}

.btn-crear-horario:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.table-hover tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
}
</style>