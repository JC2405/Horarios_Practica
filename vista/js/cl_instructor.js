class instructor {

    constructor(objData){
        this._objData = objData;
    }

    listarInstructor(){
        let objData = new FormData();
        objData.append("listarInstructor",this._objData.listarInstructor);

        fetch("controlador/instructorControlador.php",{
            method : "POST",
            body : objData
        })

            .then(response => response.json()).catch(error =>{
                console.log(error);
            })

            .then(response => {
                console.log(response)
            })
    }
}