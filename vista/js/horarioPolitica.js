(function () {

    listarHorarioPolitica();

    function listarHorarioPolitica() {
        const obj = new horarioPolitica({ listarFichasConHorario: "ok" });
        obj.listarHorarioPolitica();
    }

    /* Ver calendario — usa el módulo global HorarioCalendar */
    $(document).on('click', '.btnVerHorario', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // evitar doble disparo si hay otro listener

        const idFicha = $(this).data('id-ficha');
        console.log('[horarioPolitica] btnVerHorario idFicha =', idFicha);

        HorarioCalendar.abrirModal(idFicha, {
            ficha:   $(this).data('ficha')   || '—',
            sede:    $(this).data('sede')    || '—',
            area:    $(this).data('area')    || '—',
            jornada: $(this).data('jornada') || '—',
            tipo:    $(this).data('tipo')    || '—'
        });
    });

})();