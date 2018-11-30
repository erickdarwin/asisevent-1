<?php  
require_once "Model.php";

class DeudasModel extends Model {
	     
    public function __construct(){ 
        parent::__construct(); 
    } 

    public function deudas_por_grupo_y_usuario($user_id, $grupo_id){
		$sql = "SELECT D.*
				FROM deudas D, subcuentas SC, grupos_subcuentas GSC, grupos G 
				WHERE
					D.user_id=$user_id AND
				    D.subcuenta_id=SC.id AND
					SC.activo=1 AND
				    SC.id=GSC.subcuenta_id AND
				    GSC.activo=1 AND
				    GSC.grupo_id=G.id AND
				    G.id=$grupo_id
				ORDER BY
					D.fecha_de_cobro";
        $result = $this->conexion->query($sql); 
        $deudas = $result->fetch_all(MYSQLI_ASSOC); 
        parent::cerrar();
        return $deudas; 
    }

    public function deudas_pendientes_por_usuario($user_id){
		$sql = "SELECT detalle, fecha_vencimiento FROM deudas WHERE user_id=$user_id AND activo=1 AND cancelado=0 ORDER BY fecha_vencimiento";
        $result = $this->conexion->query($sql); 
        $deudas = $result->fetch_all(MYSQLI_ASSOC); 
        parent::cerrar();
        return $deudas; 
    }

	public function insertar($user_id, $pago, $username, $matricula_id, $comentario){

		$cuenta=$pago['cuenta'];
		$subcuenta_id=$pago['subcuenta_id'];
		$detalle=$pago['detalle'];
		$fecha_de_cobro=$pago['fecha_de_cobro'];
		$monto=$pago['monto'];
		$fecha_vencimiento=$pago['fecha_vencimiento'];
		$descuento_pago_oportuno=$pago['descuento_pago_oportuno'];

		$sql ="INSERT INTO deudas (user_id, cuenta, subcuenta_id, detalle, fecha_de_cobro, monto, fecha_vencimiento, descuento_pago_oportuno, activo, cancelado, username, created, modified, matricula_id, comentario ) 
		VALUES ($user_id, '$cuenta', $subcuenta_id, '$detalle', '$fecha_de_cobro', $monto, '$fecha_vencimiento', $descuento_pago_oportuno, 1, 0, '$username', NOW(), NOW(), $matricula_id, '$comentario') ";

		$insert = $this->conexion->query($sql); 
		if($insert) return true;
		else return false;
		parent::cerrar();
	}

	public function buscar_id($user_id, $pago, $username, $matricula_id){

		$cuenta=$pago['cuenta'];
		$detalle=$pago['detalle'];
		$fecha_de_cobro=$pago['fecha_de_cobro'];
		$monto=$pago['monto'];
		$fecha_vencimiento=$pago['fecha_vencimiento'];
		$descuento_pago_oportuno=$pago['descuento_pago_oportuno'];

		$sql ="SELECT id 
				FROM deudas 
				WHERE user_id=$user_id AND
					  cuenta='$cuenta' AND
					  detalle='$detalle' AND
					  fecha_de_cobro='$fecha_de_cobro' AND
					  monto=$monto AND
					  fecha_vencimiento='$fecha_vencimiento' AND
					  descuento_pago_oportuno=$descuento_pago_oportuno AND
					  activo=1 AND
					  cancelado=0 AND
					  matricula_id=$matricula_id AND
					  username='$username'";

        $result = $this->conexion->query($sql); 
        $id = $result->fetch_all(MYSQLI_ASSOC); 
        parent::cerrar();
        return $id; 
	}

    public function deuda_por_usuario($user_id){
		$sql = "SELECT * FROM deudas WHERE user_id=$user_id AND activo=1 ORDER BY fecha_vencimiento";
        $result = $this->conexion->query($sql); 
        $deudas = $result->fetch_all(MYSQLI_ASSOC); 
        parent::cerrar();
        return $deudas; 
    }
	
	public function cancelar_deuda($deuda_id, $username){
		$sql ="UPDATE deudas SET cancelado=1, modified=now(), username='$username' WHERE id=$deuda_id ";
		$update = $this->conexion->query($sql); 
		if($update) return true;
		else return false;
		parent::cerrar();
	} 

	public function anular_cancelado($deuda_id, $username){
		$sql ="UPDATE deudas SET cancelado=0, modified=now(), username='$username' WHERE id=$deuda_id ";
		$update = $this->conexion->query($sql); 
		if($update) return true;
		else return false;
		parent::cerrar();
	} 
}
?> 