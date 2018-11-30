<?php  
require_once "Model.php"; 

class RolsModel extends Model 
{
	     
    public function __construct() 
    { 
        parent::__construct(); 
    } 

    public function get_rols($user_id){
        	 
        $result = $this->conexion->query("SELECT r.id, r.rol FROM rols_users ru, rols r WHERE ru.user_id='$user_id' and ru.rol_id=r.id"); 
         
        $rols = $result->fetch_all(MYSQLI_ASSOC); 
         
        parent::cerrar();
        
        return $rols; 
    }

} 


?> 

