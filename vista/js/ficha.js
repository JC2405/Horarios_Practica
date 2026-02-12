(function(){

    // ✅ prueba inicial al cargar la vista
    listarMunicipios();
    listarProgramas();

    function listarMunicipios(){
        let objData = { "listarMunicipios" : "ok" };
        let objFicha = new ficha(objData);
        objFicha.listarMunicipios();
    }

    function listarProgramas(){
        let objData = { "listarProgramas" : "ok" };
        let objFicha = new ficha(objData);
        objFicha.listarProgramas();
    }


     const btnMunicipio = document.getElementById("btnMunicipio");

        if(btnMunicipio){
          btnMunicipio.addEventListener("click", function(){
            // 1) abrir panel (cambia títulos)
            openPanel("municipio");
            
            // 2) cargar municipios y pintarlos
            let objFicha = new ficha({});
            objFicha.listarMunicipiosPanel();
          });
        }
  


    const btnSede = document.getElementById("btnSede");
    const btnAmbiente = document.getElementById("btnAmbiente");
    const btnPrograma = document.getElementById("btnPrograma");

    
    if(btnMunicipio){
        btnMunicipio.addEventListener("click", function(){
            console.log("🟣 Click MUNICIPIO -> consultando municipios...");
            listarMunicipios();
        });
    }

   
    if(btnSede){
        btnSede.addEventListener("click", function(){
            let idMunicipio = document.getElementById("idMunicipio").value;

            if(!idMunicipio){
                console.warn("⚠️ No hay idMunicipio aún. Selecciona municipio primero.");
                return;
            }

            console.log("🟢 Click SEDE -> consultando sedes del municipio:", idMunicipio);

            let objData = {
                "listarSedesPorMunicipio": "ok",
                "idMunicipio": idMunicipio
            };

            let objFicha = new ficha(objData);
            objFicha.listarSedesPorMunicipio();
        });
    }

    
    if(btnAmbiente){
        btnAmbiente.addEventListener("click", function(){
            let idSede = document.getElementById("idSede").value;

            if(!idSede){
                console.warn("⚠️ No hay idSede aún. Selecciona sede primero.");
                return;
            }

            console.log("🟠 Click AMBIENTE -> consultando ambientes de la sede:", idSede);

            let objData = {
                "listarAmbientesPorSede": "ok",
                "idSede": idSede
            };

            let objFicha = new ficha(objData);
            objFicha.listarAmbientesPorSede();
        });
    }

   

    if(btnPrograma){
        btnPrograma.addEventListener("click", function(){
            console.log("🔵 Click PROGRAMA -> consultando programas...");
            listarProgramas();
        });
    }

})();
