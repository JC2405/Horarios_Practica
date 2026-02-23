class horarioPolitica {

    constructor(objData) {
        this._objData = objData;
    }

    listarHorarioPolitica() {

        const fd = new FormData();
        fd.append("listarFichasConHorario", "ok"); // ✅ valor fijo, no this._objData.X

        fetch("controlador/horarioControlador.php", {
            method: "POST",
            body: fd
        })
        .then(function(response) { return response.json(); })
        .catch(function(error)   { console.error("listarHorarioPolitica error:", error); })
        .then(function(response) {

            console.log("listarHorarioPolitica:", response);

            if (!response || response["codigo"] !== "200") {
                console.warn("Sin datos:", response);
                return;
            }

            const dataSet = [];

            response["horarios"].forEach(function(item) {

                // Badge jornada
                const j = (item.jornada || '').toUpperCase();
                let jornadaBadge;
                if      (j === 'MAÑANA') jornadaBadge = '<span class="badge-jornada badge-manana">🌅 Mañana</span>';
                else if (j === 'TARDE')  jornadaBadge = '<span class="badge-jornada badge-tarde">☀️ Tarde</span>';
                else if (j === 'NOCHE')  jornadaBadge = '<span class="badge-jornada badge-noche">🌙 Noche</span>';
                else                     jornadaBadge = '<span class="badge-jornada">'+(item.jornada||'—')+'</span>';

                // Botones
                let botones = '<div class="action-group">';

                botones += '<button type="button" class="btn btn-ver btnVerHorario" '
                    + 'data-id-ficha="'  + (item.idFicha      || '') + '" '
                    + 'data-ficha="'     + (item.codigoFicha  || '—') + '" '
                    + 'data-sede="'      + (item.sedeNombre   || '—') + '" '
                    + 'data-area="'      + (item.areaNombre   || '—') + '" '
                    + 'data-jornada="'   + (item.jornada      || '—') + '" '
                    + 'data-tipo="'      + (item.tipoPrograma || item.tipoprograma || '—') + '" '
                    + 'title="Ver calendario">'
                    + '<i class="bi bi-eye-fill"></i></button>';

                botones += '<button type="button" class="btn btn-info btnCrearHorarioPolitica" '
                    + 'data-id-ficha="' + (item.idFicha  || '') + '" '
                    + 'data-id-sede="'  + (item.idSede   || '') + '" '
                    + 'title="Asignar transversal">'
                    + '<i class="bi bi-pen"></i></button>';

                botones += '</div>';

                dataSet.push([
                    item.sedeNombre  || '—',
                    item.areaNombre  || '—',
                    '<strong>' + (item.codigoFicha || '—') + '</strong>'
                        + '<br><small class="text-muted">' + (item.nombrePrograma || '') + '</small>',
                    jornadaBadge,
                    item.tipoPrograma || item.tipoprograma || '—',
                    botones
                ]);
            });

            if ($.fn.DataTable.isDataTable("#tablaHorarioPolitica")) {
                $("#tablaHorarioPolitica").DataTable().clear().destroy();
            }

            $("#tablaHorarioPolitica").DataTable({
                buttons: [{ extend: "colvis", text: "Columnas" }, "excel", "pdf", "print"],
                dom: "Bfrtip",
                responsive: true,
                destroy: true,
                data: dataSet,
                language: {
                    emptyTable: "— Sin horarios registrados —",
                    search:     "Buscar:",
                    paginate:   { next: "Sig.", previous: "Ant." }
                }
            });
        });
    }

    listarHorariosPorFicha(idFicha, onSuccess, onError) {
        const fd = new FormData();
        fd.append("listarHorariosPorFicha", "ok");
        fd.append("idFicha", idFicha);

        fetch("controlador/horarioControlador.php", { method: "POST", body: fd })
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                if (resp.codigo !== "200" || !resp.horarios || !resp.horarios.length) {
                    if (onError) onError();
                    return;
                }
                if (onSuccess) onSuccess(resp.horarios);
            })
            .catch(function(err) {
                console.error("listarHorariosPorFicha:", err);
                if (onError) onError();
            });
    }
}