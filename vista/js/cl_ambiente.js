class Ambiente {



    registrarAmbientePorSede(){
        let objData = new FormData();
        objData.append("registrarAmbientePorSede", "ok");

        objData.append("codigo",document.getElementById("codigoAgregar").value);
        objData.append("numero",document.getElementById("numeroAgregar").value);
        objData.append("descripcion",document.getElementById("descripcionAgregar").value);
        objData.append("capacidad",document.getElementById("capacidadAgregar").value);
        objData.append("ubicacion",document.getElementById("ubicacionAgregar").value);
        objData.append("estado",document.getElementById("estadoAgregar").value);
        objData.append("idSede",document.getElementById("idSedeAgregar").value);


                 fetch("controlador/ambienteControlador.php",{
               method:"POST",
               body: objData
                     })
                     .then(r => r.json())
                     .then(response => {
                       console.log(response);
                    
                       if (response["codigo"] == "200") {
                         // volver a la tabla y recargar
                    
                         $("#panelFormularioAgregarAmbienteSede").hide();
                         $("#panelAmbientesSede").show();

         document.getElementById("formAgregarAmbientePorSede").reset();
            $("#formAgregarAmbientePorSede").removeClass('was-validated');
    
            // recargar tabla
            //let objListar = new sede({ listarSede: "ok" });
            //objListar.listarSede();
          } else {
           // alert(response["mensaje"]);
          }
        })
        //.catch(err => console.log(err));
      }


}