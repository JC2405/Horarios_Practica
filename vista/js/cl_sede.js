class sede {

    constructor(objData){
        this._objData = objData;
    }

    listarSede(){
        let objData = new FormData();
        objData.append("listarSede",this._objData.listarSede);
    
        fetch("controlador/sedeControlador.php",{
            method:"POST",
            body:objData
        })

        .then(response => response.json()).catch(error =>{
            console.log(error);
        })

        .then(response =>{
            console.log(response)
        })
    }

}