<?php
if($_POST['control'] != ''){
	
	switch ($_POST['control']) {

		case 'Cuentas_por_grupo':
			$vista = "../../src/Vistas/caja/cuentas_por_grupo.php";
			session_start();
			$_SESSION['vista'] = $vista;
			
			if(isset($_POST['accion']))
				$accion = $_POST['accion'];
			else
				$accion = "Periodos";
			
			//BUSCAMOS PERIODOS ACTIVOS
			if($accion=="Periodos"){
				require_once "../Modelos/PeriodosModel.php";
				$objPeriodos = new PeriodosModel();
				$periodos = $objPeriodos->get_periodos();
				$_SESSION['periodos']= $periodos;
				$_SESSION['periodo_id']=null;
				$_SESSION['grupos']=null;
				$_SESSION['grupo_id']=null;
				$_SESSION['alumnos']=null;
			}
			
			//BUSCAMOS GRUPOS QUE CORRESPONDAN AL PERIODO SELECCIONADO
			if($accion=="Grupos"){
				$periodo_id = $_POST['periodo_id'];
				require_once "../Modelos/GruposModel.php";
				$objGrupos = new GruposModel();
				$grupos = $objGrupos->grupos_por_periodo($periodo_id);
				$_SESSION['periodo_id']= $periodo_id;
				$_SESSION['grupos']= $grupos;
				$_SESSION['grupo_id']=null;
				$_SESSION['alumnos']=null;
			}

			//BUSCAMOS ESTUDIANTES QUE CORRESPONDAN AL PERIODO Y GRUPO SELECCIONADO
			if($accion=="Listado"){
				$grupo_id = $_POST['grupo_id'];

				//BUSCAMOS LAS SUBCUENTAS DESIGNADAS PARA EL GRUPO
				require_once "../Modelos/GruposSubcuentasModel.php";
				$objGrupoSubcuenta = new GruposSubcuentasModel();
				$subCuentas = $objGrupoSubcuenta->subcuentas_por_grupo($grupo_id);

				//BUSCAMOS ESTUDIANTES DEL GRUPO
				require_once "../Modelos/UsersModel.php";
				$objUser = new UsersModel();
				$alumnos = $objUser->buscar_por_grupo($grupo_id,"Alumno",4);
				
				//BUSCAMOS LAS DEUDAS DE CADA ESTUDIANTE
				require_once "../Modelos/DeudasModel.php";
				for ($i=0; $i < count($alumnos); $i++) { 
					$objDeuda = new DeudasModel();
					$alumnos[$i]['deudas']=$objDeuda->deudas_por_grupo_y_usuario($alumnos[$i]['id'], $grupo_id);
				}

				//SUBIMOS TODOS LOS DATOS A SESSION
				$_SESSION['subCuentas']=$subCuentas;
				$_SESSION['grupo_id']= $grupo_id;
				$_SESSION['alumnos']=$alumnos;


			}
			
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'Cierre':
			$vista = "../../src/Vistas/caja/cierre_de_caja.php";
			session_start();
			$_SESSION['vista'] = $vista;

			//FUNCIONA EN BASE A LA VARIABLE subcontrol
			$subcontrol = "NoDefinido";
			if(isset($_POST['subcontrol'])){
				$subcontrol=$_POST['subcontrol'];
			}

			//BUSCAMOS STANDS PENDIENTES DE CIERRE
			if ($subcontrol=="NoDefinido"){
				require_once "../Modelos/StandsModel.php";
				$objStands = new StandsModel();
				$stands_pendientes=$objStands->stands_pendientes_de_cierre();
				$_SESSION['stands_pendientes'] = $stands_pendientes;
				//eliminamos datos temporales
				$_SESSION['stand_id']=null;
				$_SESSION['fechas_pendientes']=null;
				$_SESSION['fecha_id']=null;
				$_SESSION['usuarios_pendientes']=null;
				$_SESSION['usuario_id']=null;
				$_SESSION['boletas_pendientes']=null;
				
			} elseif ($subcontrol=="Stand") {
				//BUSCAMOS FECHAS PENDIENTES DE CIERRE PARA EL STAND ELEGIDO
				$stand_id = $_POST['stand_id'];
				require_once "../Modelos/BoletasModel.php";
				$objBoletas = new BoletasModel();
				$fechas_pendientes=$objBoletas->fechas_pendientes_cierre_por_stand($stand_id);
				$_SESSION['stand_id'] = $stand_id;
				$_SESSION['fechas_pendientes'] = $fechas_pendientes;
				
				//eliminamos datos temporales
				$_SESSION['fecha_id']=null;
				$_SESSION['usuarios_pendientes']=null;
				$_SESSION['usuario_id']=null;
				$_SESSION['boletas_pendientes']=null;

			} elseif ($subcontrol=="Fecha") {
				//BUSCAMOS FECHAS PENDIENTES DE CIERRE PARA EL STAND ELEGIDO
				$fecha_id = $_POST['fecha_id'];
				require_once "../Modelos/BoletasModel.php";
				$objBoletas = new BoletasModel();
				$usuarios_pendientes=$objBoletas->usuarios_pendientes_por_stand_y_fecha($_SESSION['stand_id'], $fecha_id);
				$_SESSION['fecha_id'] = $fecha_id;
				$_SESSION['usuarios_pendientes'] = $usuarios_pendientes;
				//eliminamos datos temporales
				$_SESSION['usuario_id']=null;
				$_SESSION['boletas_pendientes']=null;

			
			} elseif ($subcontrol=="Usuario") {
				//BUSCAMOS BOLETAS PENDIENTES DE CIERRE PARA EL STAND, FECHA Y USUARIO ELEGIDO
				$stand_id = $_SESSION['stand_id'];
				$fecha_id = $_SESSION['fecha_id'];
				$usuario_id = $_POST['usuario_id'];
				require_once "../Modelos/BoletasModel.php";
				$objBoletas = new BoletasModel();
				$boletas_pendientes=$objBoletas->boletas_pendientes_stand_fecha_usuario($stand_id, $fecha_id, $usuario_id);
				$_SESSION['usuario_id'] = $usuario_id;
				$_SESSION['boletas_pendientes'] = $boletas_pendientes;
			
			} elseif ($subcontrol=="Cerrar") {
				//EFECTUAMOS CIERRE DE CAJA
				$cierre['username_cajero'] = $_POST['username_cajero'];
				$cierre['stand_id'] = $_POST['stand_id'];
				$cierre['fecha'] = $_POST['fecha'];
				$cierre['hora'] = $_POST['hora'];
				$cierre['monto'] = $_POST['monto'];
				$cierre['comentario'] = $_POST['comentario'];
				$cierre['username'] = $username = $_SESSION['user'][0]['username'];
				
				require_once "../Modelos/CierresModel.php";
				$objCierre = new CierresModel();
				if($objCierre->guardar_cierre($cierre)){
					$id = $objCierre->ultimo_id();
					if(count($id)==1){
						require_once "../Modelos/BoletasModel.php";
						$objBoletas = new BoletasModel();
						for ($i=0; $i < count($_SESSION['boletas_pendientes']); $i++) { 
							$objBoletas->cerrar_boleta($_SESSION['boletas_pendientes'][$i]['id'],$id[0]['id']);
						}
						//eliminamos datos temporales
						$_SESSION['stand_id']=null;
						$_SESSION['fechas_pendientes']=null;
						$_SESSION['fecha_id']=null;
						$_SESSION['usuarios_pendientes']=null;
						$_SESSION['usuario_id']=null;
						$_SESSION['boletas_pendientes']=null;
					}
				}

			} elseif ($subcontrol=="AnularBoleta") {
				//ANULAMOS BOLETA
				$idBoleta = $_POST['idBoleta'];
				$idDeuda = $_POST['idDeuda'];
				$username = $_SESSION['user'][0]['username'];
				require_once "../Modelos/BoletasModel.php";
				$objBoletas = new BoletasModel();
				if($objBoletas->anular_boleta_por_id($idBoleta)){
					//SI SE ANULÓ LA BOLETA ENTONCES PROCEDEMOS A CAMBIAR EL ESTADO DE LA DEUDA: CANCELADO A 0
					require_once "../Modelos/DeudasModel.php";
					$objDeudas = new DeudasModel();
					$objDeudas->anular_cancelado($idDeuda, $username);
				}
				//ACTUALIZAMOS LISTA DE BOLETAS
				$boletas_pendientes=$objBoletas->boletas_pendientes_stand_fecha_usuario($_SESSION['stand_id'], $_SESSION['fecha_id'], $_SESSION['usuario_id']);
				$_SESSION['boletas_pendientes'] = $boletas_pendientes;
				
			}

			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'AnularBoleta':
			$vista = "../../src/Vistas/caja/resumen_diario.php";
			session_start();
			$_SESSION['vista'] = $vista;

			$idBoleta = $_POST['idBoleta'];
			$idDeuda = $_POST['idDeuda'];
			$username = $_SESSION['user'][0]['username'];
			
			//ANULAMOS LA BOLETA
			require_once "../Modelos/BoletasModel.php";
			$objBoletas = new BoletasModel();
			if($objBoletas->anular_boleta_por_id($idBoleta)){
				//SI SE ANULÓ LA BOLETA ENTONCES PROCEDEMOS A CAMBIAR EL ESTADO DE LA DEUDA: CANCELADO A 0
				require_once "../Modelos/DeudasModel.php";
				$objDeudas = new DeudasModel();
				$objDeudas->anular_cancelado($idDeuda, $username);
			}
			
			//VOLVEMOS A CARGAR LOS DATOS DE LOS STANDS Y BOLETAS
			$fecha=$_SESSION['fechaSistema'];
			require_once "../Modelos/StandsModel.php";
			$objStands = new StandsModel();
			$stands = $objStands->stands_por_fecha_usuario($fecha, $username);
			$_SESSION['stands_por_fecha']=$stands;
			require_once "../Modelos/BoletasModel.php";
			$objBoletas = new BoletasModel();
			$boletas = $objBoletas->boletas_por_fecha_y_usuario($fecha, $username);
			$_SESSION['boletas_por_fecha']=$boletas;

			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'Diario':
			$vista = "../../src/Vistas/caja/resumen_diario.php";
			session_start();
			$_SESSION['vista'] = $vista;
			$fecha=$_SESSION['fechaSistema'];
			$username=$_SESSION['user'][0]['username'];

			require_once "../Modelos/StandsModel.php";
			$objStands = new StandsModel();
			$stands = $objStands->stands_por_fecha_usuario($fecha, $username);
			$_SESSION['stands_por_fecha']=$stands;

			require_once "../Modelos/BoletasModel.php";
			$objBoletas = new BoletasModel();
			$boletas = $objBoletas->boletas_por_fecha_y_usuario($fecha, $username);
			$_SESSION['boletas_por_fecha']=$boletas;

			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'FijarFecha':
			$vista = "../../src/Vistas/caja/buscar.php";
			session_start();
			$_SESSION['vista'] = $vista;

			$fecha_boleta = $_POST['fecha_boleta'];
			$_SESSION['fechaSistema'] = $fecha_boleta;
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'Emitir':
			$vista = "../../src/Vistas/caja/buscar.php";
			session_start();
			$_SESSION['vista'] = $vista;

			if (isset($_SESSION['standAbiertos'])){
				if (count($_SESSION['standAbiertos'])==0){
					$_SESSION['mensaje'] = "ERROR";
					$_SESSION['comentario'] = "Caja Cerrada. No se emitirán comprobantes.";
				}
				if (count($_SESSION['standAbiertos'])>1){
					$_SESSION['mensaje'] = "ERROR";
					$_SESSION['comentario'] = "Conflicto con los puntos de atención. No se emitirán comprobantes.";
				}
			} else {
				$_SESSION['mensaje'] = "ERROR";
				$_SESSION['comentario'] = "Caja cerrada.";
			}

			if(!isset($_POST['condicion'])){
				require_once "../Modelos/PeriodosModel.php";
				$objPeriodos = new PeriodosModel();
				$periodos = $objPeriodos->get_periodos();
				$_SESSION['condicion']="por_dni";
				$_SESSION['periodos']= $periodos;
				$_SESSION['periodo_id']=null;
				$_SESSION['grupos']=null;
				$_SESSION['grupo_id']=null;
				$_SESSION['alumnosCaja']=null;

			}elseif($_POST['condicion']=='por_dni'){
				require_once "../Modelos/UsersModel.php";
				$objUsers= new UsersModel();
				$alumnosCaja= $objUsers->buscar_por_dni_rol($_POST['dni'],"Alumno");
				$_SESSION['condicion']="por_dni";
				$_SESSION['alumnosCaja']= $alumnosCaja;
				$_SESSION['periodo_id']=null;
				$_SESSION['grupos']=null;
				$_SESSION['grupo_id']=null;

			}elseif($_POST['condicion']=='por_nombres'){
				require_once "../Modelos/UsersModel.php";
				$objUsers= new UsersModel();
				$alumnosCaja = $objUsers->buscar_por_nombres_y_role($_POST['apellido_paterno'],$_POST['apellido_materno'],$_POST['nombres'],"Alumno");
				$_SESSION['condicion']="por_nombre";
				$_SESSION['alumnosCaja']= $alumnosCaja;
				$_SESSION['periodo_id']=null;
				$_SESSION['grupos']=null;
				$_SESSION['grupo_id']=null;

			}elseif($_POST['condicion']=='por_periodo'){
				require_once "../Modelos/GruposModel.php";
				$objGrupos = new GruposModel();
				$grupos = $objGrupos->grupos_por_periodo($_POST['periodo_id']);
				$_SESSION['condicion']="por_periodo";
				$_SESSION['grupos']= $grupos;
				$_SESSION['periodo_id']= $_POST['periodo_id'];
				$_SESSION['grupo_id']=null;
				$_SESSION['alumnosCaja']=null;

			}elseif($_POST['condicion']=='por_grupo'){
				$_SESSION['grupo_id']= $_POST['grupo_id'];
				require_once "../Modelos/UsersModel.php";
				$objUsers = new UsersModel();
				$alumnosCaja = $objUsers->buscar_por_grupo_rol($_POST['grupo_id'],"Alumno");
				$_SESSION['condicion']="por_periodo";
				$_SESSION['alumnosCaja']= $alumnosCaja;
				
			}
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'CambiarVista':
			$vista = "../../src/Vistas/caja/".$_POST['vista'];
			session_start();
			$_SESSION['vista'] = $vista;
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'EstadoDeCuenta':
			$vista = "../../src/Vistas/caja/estado_de_cuenta.php";
			session_start();
			$_SESSION['vista'] = $vista;

			require_once "../Modelos/CuentasModel.php";
			$objCuentas = new CuentasModel();
			$otraCuentas = $objCuentas->cuentas_sin_grupo();
			
			$llave = $_POST['llave'];
			require_once "../Modelos/DeudasModel.php";
			$objDeudas = new DeudasModel();
			$deudas = $objDeudas->deuda_por_usuario($_SESSION['alumnosCaja'][$llave]['id']);

			require_once "../Modelos/BoletasModel.php";
			for ($i=0; $i < count($deudas); $i++) { 
				$objBoletas = new BoletasModel();
				$boletas = $objBoletas->boletas_por_deuda_y_usuario($deudas[$i]['id'], $_SESSION['alumnosCaja'][$llave]['id']);
				$deudas[$i]['boletas']=$boletas;
			}
			
			$_SESSION['llave'] = $llave;
			$_SESSION['deudas'] = $deudas;
			$_SESSION['otrasCuentas'] = $otraCuentas;
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'PagosVarios':
			$vista = "../../src/Vistas/caja/estado_de_cuenta.php";
			session_start();
			$_SESSION['vista'] = $vista;
			$accion = $_POST['accion'];

			if($accion=="SubCuentas"){
				$id_otra_cuenta = $_POST['id_otra_cuenta'];
				require_once "../Modelos/SubCuentasModel.php";
				$objSubCuentas = new SubCuentasModel();
				$OtrasSubCuentas = $objSubCuentas->subcuentas_por_cuenta($id_otra_cuenta);
				$_SESSION['id_otra_cuenta'] = $id_otra_cuenta;
				$_SESSION['OtrasSubCuentas'] = $OtrasSubCuentas;
			}
			if($accion=="Monto"){
				$id_otra_subcuenta=$_POST['id_otra_subcuenta'];
				$_SESSION['id_otra_subcuenta'] = $id_otra_subcuenta;
			}
			header("Location: ../../src/Vistas/vista.php");
			break;

		case 'GuardarBoleta':
			$vista = "../../src/Vistas/caja/emitir_boleta.php";
			session_start();
			$_SESSION['vista'] = $vista;
			
			$llave = $_SESSION['llave'];
			$llave_deuda = $_POST['llave_deuda'];
			$cobro = $_POST['cobro'];
			$descuento = $_POST['descuento'];
			$totalBoletas = $_POST['total_boletas'];
			$efectivo = $_POST['efectivo'];
			$saldo = $_POST['saldo'];
			
			$boleta['stand_id']=$_SESSION['standAbiertos'][0]['id'];
			$boleta['user_id']=$_SESSION['alumnosCaja'][$llave]['id'];
			$boleta['serie']=$_SESSION['standAbiertos'][0]['serie'];
			$boleta['correlativo']=$_SESSION['standAbiertos'][0]['numero'];
			$boleta['fecha']=$_POST['fecha'];
			$boleta['hora']=$_POST['hora'];
			$boleta['deuda_id']=$_SESSION['deudas'][$llave_deuda]['id'];
			if($efectivo>=$saldo){
				$boleta['monto']=$cobro - $totalBoletas;
				$boleta['descuento']=$descuento;
				$boleta['cancelado']=1; // para actualizar el estado de la DEUDA
			}
			if($efectivo<$saldo){
				$boleta['monto']=$efectivo;
				$boleta['descuento']=0;
				$boleta['cancelado']=0; // para actualizar el estado de la DEUDA
			}
			$boleta['total']=$boleta['monto']-$boleta['descuento'];
			$boleta['efectivo']=$efectivo;
			$boleta['vuelto']=$boleta['efectivo']-$boleta['total'];
			$boleta['username']=$_SESSION['user'][0]['username'];
			$boleta['comentario']=$_SESSION['deudas'][$llave_deuda]['comentario'];;
			$boleta['anulado']=0;
			$boleta['observacion']="Ninguno";
			$boleta['detalle']=$_SESSION['deudas'][$llave_deuda]['detalle'];
			
			//GUARDAMOS LA BOLETA
			require_once "../Modelos/BoletasModel.php";
			$objBoletas = new BoletasModel();
			if($objBoletas->guardar_boleta($boleta)){
				//ACTUALIZA NUMERO CORRELATIVO PARA LA SIGUIENTE BOLETA EN LA SESSION Y BD.
				require_once "../Modelos/StandsModel.php";
				$objStands = new StandsModel();
				if( $objStands->incrementar_numeracion($boleta['stand_id'],$boleta['username']) ){
					$objStand1 = new StandsModel();
					$nuevoStand = $objStand1->buscar_numero($boleta['stand_id']);
					$_SESSION['standAbiertos'][0]['numero']=$nuevoStand[0]['numero'];
				}

				//ACTUALIZO ESTADO DE LA DEUDA: SE CANCELO O NO $BOLETA['CANCELADO']
				require_once "../Modelos/DeudasModel.php";
				if($boleta['cancelado']==1){
					$objDeuda = new DeudasModel();
					$objDeuda->cancelar_deuda($boleta['deuda_id'], $boleta['username']);
					
					//SI LA CUENTA QUE SE CANCELO ES DE UNA MATRICULA ENTONCES ACTUALIZAMOS EL ESTADO DE LA MATRICULA A 4
					if ($_SESSION['deudas'][$llave_deuda]['cuenta']=="Matricula") {
						require_once "../Modelos/MatriculasModel.php";
						$objMatricula = new MatriculasModel();
						$objMatricula->actualizar_paso_matricula(4, $_SESSION['deudas'][$llave_deuda]['matricula_id']);
					}
				}
				
				//ACTUALIZO ESTADO DE CUENTA DEL ESTUDIANTE
				$objDeudas = new DeudasModel();
				$deudas = $objDeudas->deuda_por_usuario($_SESSION['alumnosCaja'][$llave]['id']);
	
				for ($i=0; $i < count($deudas); $i++) { 
					$objBoletas1 = new BoletasModel();
					$boletas = $objBoletas1->boletas_por_deuda_y_usuario($deudas[$i]['id'], $boleta['user_id']);
					$deudas[$i]['boletas']=$boletas;
				}
				$_SESSION['deudas'] = $deudas;
				$_SESSION['boleta'] = $boleta;
			} else echo "NO SE GUARDO";
			
			header("Location: ../../src/Vistas/vista.php"); 
			break;

		case 'GuardarOtrosPagos':
			$vista = "../../src/Vistas/caja/emitir_boleta.php";
			session_start();
			$_SESSION['vista'] = $vista;
			
			//GUARDAMOS LA NUEVA DEUDA ADQUIRIDA
			$llave = $_SESSION['llave'];
			$username = $_SESSION['user'][0]['username'];
			$otrasCuentas=$_SESSION['otrasCuentas'];
			for ($i=0; $i < count($otrasCuentas); $i++) {
				if($otrasCuentas[$i]['id']==$_SESSION['id_otra_cuenta'])
					$deuda['cuenta']=$otrasCuentas[$i]['cuenta'];
			}
			$OtrasSubCuentas=$_SESSION['OtrasSubCuentas'];
			for ($i=0; $i < count($OtrasSubCuentas); $i++) {
				if($OtrasSubCuentas[$i]['id']==$_SESSION['id_otra_subcuenta']) {
					$deuda['subcuenta_id']=$OtrasSubCuentas[$i]['id'];
					$deuda['detalle']=$OtrasSubCuentas[$i]['subcuenta'];
					$deuda['monto']=$OtrasSubCuentas[$i]['monto'];
				}
			}
			$deuda['fecha_de_cobro']=$_POST['fecha'];
			$deuda['fecha_vencimiento']=$_POST['fecha'];
			$deuda['descuento_pago_oportuno']=0;
			
			require_once "../Modelos/DeudasModel.php";
			$objDeudas = new DeudasModel();
			// INSERTAMOS EL VALOR DE 0 PARA matricula_id INDICANDO QUE NO PERTENECE A UNA CUENTA DE GRUPO SINO CUENTAS LIBRES
			if ($objDeudas->insertar($_SESSION['alumnosCaja'][$llave]['id'], $deuda, $username,0,"")){
				$objDeudas1 = new DeudasModel();
				$deudaId=$objDeudas1->buscar_id($_SESSION['alumnosCaja'][$llave]['id'], $deuda, $username,0);
				if(count($deudaId)==1){
					$deuda['id']=$deudaId[0]['id'];

					//GUARDAMOS LA BOLETA
					$efectivo = $_POST['efectivo_otro_pago'];
					
					$boleta['stand_id']=$_SESSION['standAbiertos'][0]['id'];
					$boleta['user_id']=$_SESSION['alumnosCaja'][$llave]['id'];
					$boleta['serie']=$_SESSION['standAbiertos'][0]['serie'];
					$boleta['correlativo']=$_SESSION['standAbiertos'][0]['numero'];
					$boleta['fecha']=$_POST['fecha'];
					$boleta['hora']=$_POST['hora'];
					$boleta['deuda_id']=$deuda['id'];
					$boleta['monto']=$deuda['monto'];
					$boleta['descuento']=$deuda['descuento_pago_oportuno'];
					$boleta['cancelado']=1; // para actualizar el estado de la DEUDA
					$boleta['total']=$boleta['monto']-$boleta['descuento'];
					$boleta['efectivo']=$efectivo;
					$boleta['vuelto']=$boleta['efectivo']-$boleta['total'];
					$boleta['username']=$_SESSION['user'][0]['username'];
					$boleta['comentario']="";
					$boleta['anulado']=0;
					$boleta['observacion']="Ninguno";
					$boleta['detalle']=$deuda['detalle'];
					
					//GUARDAMOS LA BOLETA
					require_once "../Modelos/BoletasModel.php";
					$objBoletas = new BoletasModel();
					if($objBoletas->guardar_boleta($boleta)){
						//ACTUALIZA NUMERO CORRELATIVO PARA LA SIGUIENTE BOLETA EN LA SESSION Y BD.

						require_once "../Modelos/StandsModel.php";
						$objStands = new StandsModel();
						if( $objStands->incrementar_numeracion($boleta['stand_id'], $boleta['username']) ){
							$objStand1 = new StandsModel();
							$nuevoStand = $objStand1->buscar_numero($boleta['stand_id']);
							$_SESSION['standAbiertos'][0]['numero']=$nuevoStand[0]['numero'];
						}
		
						//ACTUALIZO ESTADO DE LA DEUDA: SE CANCELO O NO $BOLETA['CANCELADO']
						require_once "../Modelos/DeudasModel.php";
						if($boleta['cancelado']==1){
							$objDeuda = new DeudasModel();
							$objDeuda->cancelar_deuda($boleta['deuda_id'],$boleta['username']);
						}
						
						//ACTUALIZO ESTADO DE CUENTA DEL ESTUDIANTE
						$objDeudas = new DeudasModel();
						$deudas = $objDeudas->deuda_por_usuario($_SESSION['alumnosCaja'][$llave]['id']);
			
						for ($i=0; $i < count($deudas); $i++) { 
							$objBoletas1 = new BoletasModel();
							$boletas = $objBoletas1->boletas_por_deuda_y_usuario($deudas[$i]['id'], $boleta['user_id']);
							$deudas[$i]['boletas']=$boletas;
						}
						$_SESSION['deudas'] = $deudas;
						$_SESSION['boleta'] = $boleta;
					} else echo "NO SE GUARDO";
				}
			}
			header("Location: ../../src/Vistas/vista.php");  
			break;

		case 'Abrir_Cerrar':
			$vista = "../../src/Vistas/caja/abrir_cerrar.php";
			session_start();
			$_SESSION['vista'] = $vista;
			
			require_once "../Modelos/StandsModel.php";
			
			if(isset($_POST['accion'])){
				if($_POST['accion']=="Abrir"){
					$objStand = new StandsModel();
					if($objStand->abrir_stand($_POST['stand_id'],$_SESSION['user'][0]['username'])){
						echo "abierto";
					} else {
						echo "no se pudo";
					}
					
				}
				if($_POST['accion']=="Cerrar"){
					$objStand = new StandsModel();
					$objStand->cerrar_stand($_POST['stand_id'],$_SESSION['user'][0]['username']);

					//RESETEAMOS LA FECHA DEL SISTEMA
					date_default_timezone_set('America/Lima');
					setlocale(LC_TIME, "Spanish");		
					$hoy = strftime("%Y-%m-%d"); // FECHA PARA EL CALCULO DE TODAS LAS OPERACIONES
					$_SESSION['fechaSistema'] = $hoy;
				}
			}
			$objStandA = new StandsModel();
			$objStandC = new StandsModel();
			$standCerrados = $objStandA->stands_cerrados();
			$standAbiertos = $objStandC->stands_abiertos();
			$_SESSION['standAbiertos'] = $standAbiertos;
			$_SESSION['standCerrados'] = $standCerrados;
			
			header("Location: ../../src/Vistas/vista.php");
			break;

		default:
			session_start();
			session_destroy();
			header("Location: ../");
			break;
	}
}
?>