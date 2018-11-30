<?php
require_once "../Modelos/HorariosModel.php";
require_once "../Modelos/AsistenciasModel.php";

class ControladorReportes{
    
    /*=============================================
    MOSTRAR HORARIOS Y NIVELES
    =============================================*/
     public function ctrVerHorasAsistencia($dni,$apeP,$apeM,$nombre,$fechainicio,$fechafin){
      
        $objHoras = new AsistenciasModel();
        $resultadoHoras = $objHoras->mdlVerHorasAsistencia($dni,$apeP,$apeM,$nombre,$fechainicio,$fechafin);    
	   return $resultadoHoras;
  
     }
    /*=============================================
    JUSTIFICAR
    =============================================*/
     public function ctrJustificar(){
      
        if(isset($_POST["justificacion"])){
            
          $justificacion= $_POST['justificacion'];
          
          $objJustificar = new AsistenciasModel();
          $mensaje=$objJustificar->mdlJustificar($justificacion); 
            
          if($mensaje){
                    
              echo'<script>

				swal({
                  type: "success",
				  title: "El alumno fue justificado existosamente",
				  showConfirmButton: true,
				  confirmButtonText: "Cerrar"
				  }).then((result) => {
				    if (result.value) {
                        }
				    })

               </script>';

            }else{
                    
              echo'<script>

				swal({
				  type: "error",
				  title: "El alumno no puede ser justificado",
                  text: "Asegurese que pueda justicar al alumno",
				  showConfirmButton: true,
				  confirmButtonText: "Cerrar",
				  closeOnConfirm: false
				  }).then((result) => {
				    if (result.value) {
				        }
                    })

			   </script>';
                    
            }
		}
  
     }
    
    
}