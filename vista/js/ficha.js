(function(){

    listarTecnologos();

    // Event listener para el botón crear horario (funciona con elementos dinámicos)
 // ✅ CÓDIGO CORRECTO
        $(document).on('click', '.btn-crear-horario', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var codigoFicha = $(this).data('codigoficha');
            console.log('Código de ficha seleccionada:', codigoFicha);
            
            window.location.href = 'crearHorario?ficha=' + codigoFicha;
        });

    function listarTecnologos(){
        let objData = { listarTecnologos : "ok"};
        let objListarTecnologos = new ficha(objData);
        objListarTecnologos.listarTecnologos();
    }

})();

