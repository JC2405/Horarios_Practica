class horario {

    constructor(objData) {
        this._objData = objData;
    }


  
    /* ══════════════════════════════════════════════════
       LISTAR HORARIOS
    ══════════════════════════════════════════════════ */
    listarHorarios() {
        const formData = new FormData();
        formData.append("listarHorarios", "ok");

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .catch(error => console.error(error))
        .then(response => {
        

            console.log(response);

            const dataSet = [];


                const botones = `
                    <div class="action-group">
                        <button type="button" class="btn btn-ver btnVerHorario" title="Ver calendario de la ficha"
                            data-id-ficha="${item.idFicha}"
                            data-ficha="${item.codigoFicha}"
                            data-sede="${item.sedeNombre}"
                            data-area="${item.areaNombre}"
                            data-jornada="${item.jornada}"
                            data-tipo="${item.tipoPrograma || item.tipoprograma || "—"}">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        
                        <button type="button" class="btn btn-danger btnEliminarHorario" data-id="${item.idHorario}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;

                dataSet.push([
                    item.sedeNombre || item.sede || "—",
                    item.areaNombre || item.area || "—",
                    `<strong>${item.codigoFicha || "—"}</strong>`,
                    this._inferirJornadaBadge(item.hora_inicioClase),
                    item.tipoPrograma || item.tipoprograma || "/",
                    botones
                ]);
            });

            $("#tablaHorarios").DataTable({
                buttons: [{ extend: "colvis", text: "Columnas" }, "excel", "pdf", "print"],
                dom: "Bfrtip",
                responsive: true,
                destroy: true,
                data: dataSet,
                language: {
                    emptyTable: "— Sin horarios registrados —",
                    search: "Buscar:",
                    paginate: { next: "Sig.", previous: "Ant." }
                }
            });
        
    }

    


    /* ══════════════════════════════════════════════════
       LISTAR HORARIOS POR FICHA (para el calendario modal)
    ══════════════════════════════════════════════════ */
    listarHorariosPorFicha(idFicha, onSuccess, onError) {
        const formData = new FormData();
        formData.append("listarHorariosPorFicha", "ok");
        formData.append("idFicha", idFicha);

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.codigo !== "200" || !response.horarios || response.horarios.length === 0) {
                if (onError) onError();
                return;
            }
            if (onSuccess) onSuccess(response.horarios);
        })
        .catch(error => {
            console.error("listarHorariosPorFicha:", error);
            if (onError) onError();
        });
    }

    /* ══════════════════════════════════════════════════
       CREAR HORARIO
    ══════════════════════════════════════════════════ */
    crearHorario(datos, diasSeleccionados) {
        Swal.fire({ title: "Guardando...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        const formData = new FormData();
        formData.append("crearHorario",       "ok");
        formData.append("idFuncionario",       datos.idFuncionario);
        formData.append("idAmbiente",          datos.idAmbiente);
        formData.append("idFicha",             datos.idFicha);
        formData.append("hora_inicioClase",    datos.hora_inicioClase);
        formData.append("hora_finClase",       datos.hora_finClase);
        formData.append("fecha_inicioHorario", datos.fecha_inicioHorario);
        formData.append("fecha_finHorario",    datos.fecha_finHorario);
        diasSeleccionados.forEach(id => formData.append("dias[]", id));

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            Swal.close();
            if (response.codigo === "200") {
                Swal.fire({ icon: "success", title: "¡Horario creado!", text: response.mensaje, timer: 1800, showConfirmButton: false });
                document.dispatchEvent(new CustomEvent("horarioCreado"));
            } else {
                Swal.fire({ icon: "error", title: "Error", html: response.mensaje, confirmButtonColor: "#7c6bff" });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({ icon: "error", title: "Error de conexión", text: String(error) });
        });
    }

    /* ══════════════════════════════════════════════════
       ELIMINAR HORARIO
    ══════════════════════════════════════════════════ */
    eliminarHorario(idHorario) {
        const formData = new FormData();
        formData.append("eliminarHorario", "ok");
        formData.append("idHorario", idHorario);

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.codigo === "200") {
                Swal.fire({ icon: "success", title: "Eliminado", timer: 1500, showConfirmButton: false });
                document.dispatchEvent(new CustomEvent("horarioEliminado"));
            } else {
                Swal.fire({ icon: "error", title: "Error", text: response.mensaje });
            }
        })
        .catch(error => console.error(error));
    }

    /* ══════════════════════════════════════════════════
       LISTAR DÍAS
    ══════════════════════════════════════════════════ */
    listarDias(onSuccess) {
        const formData = new FormData();
        formData.append("listarDias", "ok");

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.codigo !== "200") return;
            if (onSuccess) onSuccess(response.dias || []);
        })
        .catch(error => console.error(error));
    }

    /* ══════════════════════════════════════════════════
       HELPERS PRIVADOS
    ══════════════════════════════════════════════════ */
    _inferirJornadaBadge(horaInicio) {
        if (!horaInicio) return '<span class="badge-jornada">—</span>';
        const h = parseInt(horaInicio.split(":")[0]);
        if (h < 12) return '<span class="badge-jornada badge-manana">🌅 Mañana</span>';
        if (h < 18) return '<span class="badge-jornada badge-tarde">☀️ Tarde</span>';
        return '<span class="badge-jornada badge-noche">🌙 Noche</span>';
    }

    _emptyState() {
        return `<div class="horario-empty"><i class="bi bi-calendar-x"></i><p>No hay horarios registrados</p></div>`;
    }
}