(function(){

    listarTecnologos();

    // Event listener para el botón crear horario (funciona con elementos dinámicos)
    $(document).on('click', '#tablaTecnologos .btn-crear-horario', function(e) {
        e.preventDefault();
        var codigoFicha = $(this).data('codigoficha');
        window.location.href = '?ruta=crearHorario&ficha=' + codigoFicha;
    });

    function listarTecnologos(){
        let objData = { listarTecnologos : "ok"};
        let objListarTecnologos = new ficha(objData);
        objListarTecnologos.listarTecnologos();
    }

})();
