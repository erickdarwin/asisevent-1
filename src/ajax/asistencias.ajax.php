<?php
    require_once "../Modelos/GruposModel.php";
    require_once "../Modelos/AsistenciasModel.php";
    session_start();
/*=============================================
MOSTRAR GRUPO AL SELECCIONAR PERIODO
=============================================*/	
class AjaxMostrarGrupos{
    
	public $periodoId;
	public function ajaxGruposporPeriodo(){
		$valor = $this->periodoId;
        $editComent = new GruposModel();
        $html="<option value='' >Seleccione Grupo</option>";
        $respuesta = $editComent->grupos_por_periodo($valor);
        foreach ($respuesta as $key => $value) {          
          $html.='<option value="'.$value["id"].'">'.$value["comentario"].'</option>';    
        }
		echo $html;
	}
}

if(isset($_POST["PeriodoId"])){
	$selec = new AjaxMostrarGrupos();
	$selec -> periodoId = $_POST["PeriodoId"];
	$selec -> ajaxGruposporPeriodo();
}

/*=============================================
ENVIAR DATOS POR POST PARA CARGAR LAS ASISTENCIAS
=============================================*/	
if(isset($_POST["PeriodoId"]) and isset($_POST["GrupoId"])){
    
    $idP = $_POST["PeriodoId"];
    $_SESSION['PerId']=$idP;
    $idG = $_POST["GrupoId"];
    $_SESSION['GrupId']=$idG;
}

/*=============================================
CAMBIAR CONDICION DE ASISTENCIAS
=============================================*/	
class AjaxSaveComment{
    
	public $idCommentar;
	public $idIdC;
	public function ajaxCrearComentario(){
		$valor1 = $this->idCommentar;
		$valor2 = $this->idIdC;
        $saveComentar = new AsistenciasModel();
        $respuesta = $saveComentar->mdlJustificar($valor1,$valor2);
		echo json_encode($respuesta);
	}
	
}

if(isset($_POST["comentarioG"])){
	$editar = new AjaxSaveComment();
	$editar -> idCommentar = $_POST["comentarioG"];
	$editar -> idIdC = $_POST["idCome"];
	$editar -> ajaxCrearComentario();
}

/*=============================================
EDITAR COMENTARIO
=============================================*/	
class AjaxEditComment{
    
	public $idComment;
	public function ajaxEditarComentario(){
		$valor = $this->idComment;
        $editComent = new AsistenciasModel();
        $respuesta = $editComent->mdlEditarComentar($valor);
		echo json_encode($respuesta);
	}
	
}

if(isset($_POST["idAsis"])){
	$editar = new AjaxEditComment();
	$editar -> idComment = $_POST["idAsis"];
	$editar -> ajaxEditarComentario();
}
/*=============================================
MOSTRAR GRUPO AL SELECCIONAR PERIODO
=============================================*/	
class AjaxModificarCondicion{
    
	public $condicionM;
	public $idAsistencia;
    
	public function ajaxCambiarCondicion(){
		$condi = $this->condicionM;
		$idA = $this->idAsistencia;
        $cambiar = new AsistenciasModel();
        $cambiar->modificarCondicion($condi,$idA);
	}
}
if(isset($_POST["condicion"])){
    $modificarCondicion = new AjaxModificarCondicion();
	$modificarCondicion -> condicionM = $_POST["condicion"];
	$modificarCondicion -> idAsistencia = $_POST["idAsistencia"];
	$modificarCondicion -> ajaxCambiarCondicion();
}



