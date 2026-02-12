class Ambiente {

    constructor(objData){
        this._objData = objData;
    }

    // ========== LISTAR AMBIENTES POR SEDE ==========
    listarAmbientesPorSede(idSede){
        let objData = new FormData();
        objData.append("listarAmbientesPorSede", "ok");
        objData.append("idSede", idSede);

        fetch("controlador/ambienteControlador.php", {
            method: "POST",
            body: objData
        })
        .then(response => response.json())
        .then(response => {
            console.log('📦 Respuesta ambientes:', response);
            
            if (response.codigo === "200") {
                let dataSet = [];
                
                response.ambientes.forEach(item => {
                    dataSet.push([
                        item.codigo,
                        item.numero,
                        item.capacidad,
                        item.ubicacion,
                        item.estado,
                        item.descripcion || 'N/A'
                    ]);
                });
                
                // Destruir tabla si existe
                if ($.fn.DataTable.isDataTable('#tablaAmbientesSede')) {
                    $('#tablaAmbientesSede').DataTable().destroy();
                }
                
                // Crear nueva tabla
                $('#tablaAmbientesSede').DataTable({
                    buttons: [
                        { extend: "colvis", text: "Columnas" },
                        "excel",
                        "pdf",
                        "print"
                    ],
                    dom: "Bfrtip",
                    responsive: true,
                    destroy: true,
                    data: dataSet,
                    language: {
                        "emptyTable": "No hay ambientes registrados para esta sede",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ ambientes",
                        "infoEmpty": "Mostrando 0 a 0 de 0 ambientes",
                        "infoFiltered": "(filtrado de _MAX_ ambientes totales)",
                        "lengthMenu": "Mostrar _MENU_ ambientes",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "No se encontraron ambientes",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    }
                });
                
                console.log('✅ Tabla de ambientes cargada');
            } else {
                console.error('❌ Error:', response.mensaje);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje || 'Error al cargar ambientes'
                });
            }
        })
        .catch(error => {
            console.error('❌ Error en petición:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al servidor'
            });
        });
    }

    // ========== REGISTRAR AMBIENTE POR SEDE ==========
    registrarAmbientePorSede(){
        let objData = new FormData();
        objData.append("registrarAmbientePorSede", "ok");

        objData.append("codigo", document.getElementById("codigoAgregar").value);
        objData.append("numero", document.getElementById("numeroAgregar").value);
        objData.append("descripcion", document.getElementById("descripcionAgregar").value);
        objData.append("capacidad", document.getElementById("capacidadAgregar").value);
        objData.append("ubicacion", document.getElementById("ubicacionAgregar").value);
        objData.append("estado", document.getElementById("estadoAgregar").value);
        objData.append("idSede", document.getElementById("idSedeAgregar").value);

        fetch("controlador/ambienteControlador.php", {
            method: "POST",
            body: objData
        })
        .then(r => r.json())
        .then(response => {
            console.log('📨 Respuesta guardar:', response);

            if (response.codigo == "200") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.mensaje,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Volver a la tabla y recargar
                $("#panelFormularioAgregarAmbienteSede").hide();
                $("#panelAmbientesSede").show();

                document.getElementById("formAgregarAmbientePorSede").reset();
                $("#formAgregarAmbientePorSede").removeClass('was-validated');

                // Recargar tabla de ambientes
                const idSede = document.getElementById("idSedeActualAmbientes").value;
                const objAmbiente = new Ambiente({});
                objAmbiente.listarAmbientesPorSede(idSede);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje || 'Error al registrar el ambiente'
                });
            }
        })
        .catch(err => {
            console.error('❌ Error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al servidor'
            });
        });
    }
}