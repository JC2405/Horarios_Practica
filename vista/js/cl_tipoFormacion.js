class tipoFormacion {

    constructor(objData){
        this._objData = objData;
    }

    listarTipoFormacion(){
        let objData = new FormData();
        objData.append("listarTipoFormacion",this._objData.listarTipoFormacion);


        fetch("controlador/tipoFormacionControlador.php",{

        }).then(response=>response.json()).catch(error=>{
            console.log(error)
        })

        .then(response=>{
            console.log(response);
        })
    }


}
