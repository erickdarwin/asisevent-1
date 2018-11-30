<?php
if($_POST['control'] != ''){
	
	switch ($_POST['control']) {

		case 'validar':
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			require_once "../Modelos/UsersModel.php";
			$usuario = new UsersModel();
			$usuarioValidado = $usuario->validar($username,$password);
			if(count($usuarioValidado)==1){
				session_start();
				$vista = "../../src/Vistas/Users/index.php";
				$_SESSION['user'] = $usuarioValidado;
				$_SESSION['vista'] = $vista;
				
				require_once "../Modelos/RolsModel.php";
				require_once "../Modelos/ModulosModel.php";
				require_once "../Modelos/OpcionsModel.php";

				$roles = new RolsModel();
				$rolesUsuario = $roles->get_rols($usuarioValidado['0']['id']);

				if(count($rolesUsuario)>0){
					for($i=0;$i<count($rolesUsuario);$i++){
						$modulos = new ModulosModel();
						$modulosRol = $modulos->get_modulos($rolesUsuario[$i]['id'],$usuarioValidado['0']['id']);
						if(count($modulosRol>0)){
							for($j=0;$j<count($modulosRol);$j++){
								$opcions = new OpcionsModel();
								$opcionsModulo = $opcions->get_opcions($modulosRol[$j]['id'],$usuarioValidado['0']['id']);
								if(count($opcionsModulo)==0){
									$opcionsModulo = array ();
								}
								$modulosRol[$j]= array ( 'id' => $modulosRol[$j]['id'], 'modulo' => $modulosRol[$j]['modulo'], 'opcions' => $opcionsModulo);
							}
						} else {
							$modulosRol = array ();
						}
						$menu[$i] = array('id' => $rolesUsuario[$i]['id'], 'rol' => $rolesUsuario[$i]['rol'], 'modulos' => $modulosRol);
					}
				} else {
					$menu=array();
				}
				$_SESSION['menu'] = $menu;
				$_SESSION['rolActual'] = $rolesUsuario[0]['rol'];
				//INICIALIZANDO FECHA DE SISTEMA
				date_default_timezone_set('America/Lima');
				setlocale(LC_TIME, "Spanish");		
				$hoy = strftime("%Y-%m-%d"); // FECHA PARA EL CALCULO DE TODAS LAS OPERACIONES
				$_SESSION['fechaSistema'] = $hoy;
				
			} else {
				$mensaje= "Usuario o Contraseña no válidos";
				session_destroy();
				header("Location: ../../index.php?mensaje=".$mensaje);
				break;
			}

			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'salir':
			session_start();
			if(isset($_SESSION['standAbiertos'])){
				if(count($_SESSION['standAbiertos'])==1){
					require_once "../Modelos/StandsModel.php";
					$objStand = new StandsModel();
					$objStand->cerrar_stand($_SESSION['standAbiertos'][0]['id'],$_SESSION['user'][0]['username']);
				}
			}
			session_destroy();
			header("Location: ../../index.php");
			break;

		case 'cambiarRol':
			$nuevoRol = $_POST['nuevoRol'];
			session_start();
			$_SESSION['rolActual'] = $nuevoRol;
			$vista = "../../src/Vistas/Users/index.php";
			$_SESSION['vista'] = $vista;
			header("Location: ../../src/Vistas/vista.php");
			
			break;

		default:
			session_start();
			$_SESSION = array();
			session_destroy();
			header("Location: ../");
			break;
	}
}
?>