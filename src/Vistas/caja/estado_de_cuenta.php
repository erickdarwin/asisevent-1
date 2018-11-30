<div name="menu">
	<ul class="nav nav-tabs nav-justified">
		<li><a href='#' onclick='javascript:document.form_fecha.submit()'>Definir Fecha<?php if(isset($_SESSION['p3fecha'])) echo "<span class='glyphicon glyphicon-ok'></span>"?></a></li>
		<li><a href='#' onclick='javascript:document.form_alumnos.submit()'>Seleccionar Estudiante<?php if(isset($_SESSION['llave'])) echo "<span class='glyphicon glyphicon-ok'></span>"?></a></li>
		<li class='active'><a href='#' onclick='javascript:document.form_estado_cuenta.submit()'>Estado de Cuenta<?php if(isset($_SESSION['boleta'])) echo "<span class='glyphicon glyphicon-ok'></span>"?></a></li>
		<li><a href='#' onclick='javascript:document.form_emitir_boleta.submit()'>Emitir Boleta</a></li>
	</ul>
	
	<form method='POST' name='form_fecha' id='form_fecha' action='../Controladores/CajaControl.php'>
		<input type='hidden' name='control' id='control' value='CambiarVista'>
		<input type='hidden' name='vista' id='vista' value='fecha.php'>
	</form>
	<form method='POST' name='form_alumnos' id='form_alumnos' action='../Controladores/CajaControl.php'>
		<input type='hidden' name='control' id='control' value='CambiarVista'>
		<input type='hidden' name='vista' id='vista' value='buscar.php'>
	</form>
	<form method='POST' name='form_estado_cuenta' id='form_estado_cuenta' action='../Controladores/CajaControl.php'>
		<input type='hidden' name='control' id='control' value='CambiarVista'>
		<input type='hidden' name='vista' id='vista' value='estado_de_cuenta.php'>
	</form>
	<form method='POST' name='form_emitir_boleta' id='form_emitir_boleta' action='../Controladores/CajaControl.php'>
		<input type='hidden' name='control' id='control' value='CambiarVista'>
		<input type='hidden' name='vista' id='vista' value='emitir_boleta.php'>
	</form>
</div>
</br>
<?php 
date_default_timezone_set('America/Lima');
setlocale(LC_TIME, "Spanish");		

$hora=strftime("%H:%M");
$estilo = "style='color:#456789;font-size:80%;FONT-FAMILY:Arial,Helvetica,sans-serif'";

if ( isset($_SESSION['alumnosCaja']) and isset($_SESSION['llave']) ){
	$llave = $_SESSION['llave'];
	$alumno = $_SESSION['alumnosCaja'][$llave];
} else $alumno = "NO DEFINIDO";

$hoy = $_SESSION['fechaSistema']; // FECHA PARA EL CALCULO DE TODAS LAS OPERACIONES
$monto=0; //PARA CONDICIONAR EL ENVIÓ DE BOLETA POR PAGOS VARIOS

?>

<div class="container">

	<ol>
		<li><strong>DATOS DEL ESTUDIANTE</strong></li>
			<ul class="list-unstyled">
				<table class="table table-hover table-condensed">
					<tr>
						<td <?=$estilo?> >APELLIDOS Y NOMBRES</td>
						<td <?=$estilo?> ><?php if ($alumno!="NO DEFINIDO") echo $alumno['apellido_paterno']." ".$alumno['apellido_materno'].", ".$alumno['nombres']; ?></td>
					</tr>
					<tr>
						<td <?=$estilo?> >DNI</td>
						<td <?=$estilo?> ><?php if ($alumno!="NO DEFINIDO") echo $alumno['dni']; ?></td>
					</tr>
					<tr>
						<td <?=$estilo?> >DIRECCIÓN</td>
						<td <?=$estilo?> ><?php if ($alumno!="NO DEFINIDO") echo $alumno['direccion']; ?></td>
					</tr>
					<tr>
						<td <?=$estilo?> >TELÉFONO(S)</td>
						<td <?=$estilo?> ><?php if ($alumno!="NO DEFINIDO") echo $alumno['movistar']."  ".$alumno['rpm']."  ".$alumno['claro']."  ".$alumno['otro']."  ".$alumno['fijo']; ?></td>
					</tr>
				</table>
			</ul>

		<li><strong>PAGOS VARIOS</strong></li>
			<table>
				<tr>
					<td <?=$estilo?> >
						<form action='../Controladores/CajaControl.php' method="POST" name="form_cuenta" id="form_cuenta" class="form-inline" role="form" >
							<label>Cuenta</label>
							<select class="form-control" name="id_otra_cuenta" id="id_otra_cuenta" onchange="this.form.submit()">
								<option value="" disabled="true" <?php if(!isset($_SESSION['llave_cuenta_id'])) echo "selected='true'"; ?> >Elija una cuenta</option>
			<?php
								if( isset($_SESSION['otrasCuentas']) ){
									$otrasCuentas=$_SESSION['otrasCuentas'];
									for ($i=0; $i < count($otrasCuentas); $i++) {
										$value=""; 
										if(isset($_SESSION['id_otra_cuenta'])) {
											if($otrasCuentas[$i]['id']==$_SESSION['id_otra_cuenta']) {
												$value="selected='true'";
											}
										}
										echo "<option value='".$otrasCuentas[$i]['id']."' ".$value.">".$otrasCuentas[$i]['cuenta']."</option>";
									}
								}
			?>
							</select>
							<input type='hidden' name='accion' id='accion' value='SubCuentas'>
							<input type='hidden' name='control' id='control' value='PagosVarios'>
						</form>
					</td>
					<td <?=$estilo?> >
						<form action='../Controladores/CajaControl.php' method="POST" name="form_subcuenta" id="form_sub_cuenta" class="form-inline" role="form" >
							<label>Sub Cuenta</label>
							<select class="form-control" name="id_otra_subcuenta" id="id_otra_subcuenta" onchange="this.form.submit()">
								<option value="" disabled="true" selected="true">Elija una Sub Cuenta</option>
			<?php
								if( isset($_SESSION['OtrasSubCuentas']) ){
									$OtrasSubCuentas=$_SESSION['OtrasSubCuentas'];
									for ($i=0; $i < count($OtrasSubCuentas); $i++) {
										$value=""; 
										if(isset($_SESSION['id_otra_subcuenta'])){
											if($OtrasSubCuentas[$i]['id']==$_SESSION['id_otra_subcuenta']) {
												$value="selected='true'";
												$monto=$OtrasSubCuentas[$i]['monto'];
											}
										}
										echo "<option value='".$OtrasSubCuentas[$i]['id']."' ".$value.">".$OtrasSubCuentas[$i]['subcuenta']."</option>";
									}
								}
			?>
							</select>
							<input type='hidden' name='accion' id='accion' value='Monto'>
							<input type='hidden' name='control' id='control' value='PagosVarios'>
						</form>
					</td>
					<td <?=$estilo?> ><?php if(isset($_SESSION['id_otra_subcuenta'])) echo "Monto a Pagar: S/. ".$monto.".00"; else echo "Monto a Pagar: NO DEFINIDO"; ?></td>
					<td <?=$estilo?> >	
						<form method="POST" action="../../src/Controladores/CajaControl.php" class="form-inline" role="form" onsubmit="return validarOtroPago()" name="boleta">
							<div class="col-xs-9">
								<input type="number" class="form-control input-sm" name="efectivo_otro_pago" id="efectivo_otro_pago" placeholder="Efectivo" required="true" >
							</div>
							<input type='hidden' name='fecha' id='fecha' value='<?=$hoy ?>'>
							<input type='hidden' name='hora' id='hora' value='<?=$hora ?>'>
							<input type='hidden' name='control' id='control' value='GuardarOtrosPagos'>
							<button type="submit" class="btn btn-primary btn-sm" <?php if($monto == 0) echo "disabled='disabled'";?> >Boleta</button>
						</form>
					</td>
				</tr>
			</table>
			</br>
			
		<li><strong>ESTADO DE CUENTA: <?="Calculado el: ".$hoy." - ".$hora?> </strong></li>
			<ul class="list-unstyled">

				<table class="table table-condensed table-striped" >
					<tr>
						<td rowspan="2" <?=$estilo?> ><strong>#</strong></td>
						<td colspan="5" <?=$estilo?> ><strong>COBROS</strong></td>
						<td colspan="5" <?=$estilo?> ><strong>PAGOS</strong></td>
						<td rowspan="2" <?=$estilo?> ><strong>SALDO</strong></td>
						<td rowspan="2" <?=$estilo?> ><strong>EMITIR BOLETA</strong></td>
					</tr>
					<tr>
						<td <?=$estilo?> ><strong>Detalle</strong></td>
						<td <?=$estilo?> ><strong>Cobro</strong></td>
						<td <?=$estilo?> ><strong>Vencimiento</strong></td>
						<td <?=$estilo?> ><strong>Monto</strong></td>
						<td <?=$estilo?> ><strong>Descuento</strong></td>
						<td <?=$estilo?> ><strong>Boleta</strong></td>
						<td <?=$estilo?> ><strong>Fecha</strong></td>
						<td <?=$estilo?> ><strong>Monto</strong></td>
						<td <?=$estilo?> ><strong>Descuento</strong></td>
						<td <?=$estilo?> ><strong>Total</strong></td>
					</tr>
<?php 			if (isset($_SESSION['deudas'])) {
					$deudas = $_SESSION['deudas'];
					$formulario = TRUE; //para ver que se imprima un solo formulario de pago

					for ($i=0; $i < count($deudas); $i++) {
						
						//PARA SABER CUANTAS BOLETAS YA SE HAN EMITIDO POR ESA DEUDA
						$nroBoletas = count($deudas[$i]['boletas']); 
						$rowspan=0; $totalBoletas=0; $totalDescuentosBoletas=0;
						if ($nroBoletas==0){
							$rowspan=1;
							$totalBoletas=0;
							$repeticiones=1;
							$totalDescuentosBoletas=0;
						} elseif ($nroBoletas>0){
							$repeticiones = $nroBoletas;
							for ($j=0; $j < $nroBoletas; $j++) { 
								$rowspan++; // calculo del numero de filas a juntar
								$totalDescuentosBoletas+=$deudas[$i]['boletas'][$j]['descuento'];
								$totalBoletas+=$deudas[$i]['boletas'][$j]['total']; // suma de los totales de las boletas
							}
						}
						
						// si se paso la fecha de vencimiento entonces descuento 0 sino no se venció descuento = descuento_pago_oportuno
						$vencimiento = $deudas[$i]['fecha_vencimiento'];
						$cobro = $deudas[$i]['monto'];
						
						if($hoy > $vencimiento) { $descuento = 0; $vencido=true; } 
						else { $descuento = $deudas[$i]['descuento_pago_oportuno']; $vencido=false; } 

						if ($deudas[$i]['cancelado']==1) $saldo=$cobro-$totalBoletas-$totalDescuentosBoletas;	
						else $saldo=$cobro-$descuento-$totalBoletas-$totalDescuentosBoletas;
						
						$repeticion=0;
						while ($repeticion < $repeticiones) {
?>
							<tr>
<?php							if ($repeticion==0){		?>
	 								<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?=$i+1 ?></td>
									<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?=$deudas[$i]['detalle'] ?></td>
									<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?=$deudas[$i]['fecha_de_cobro'] ?></td>
									<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?=$deudas[$i]['fecha_vencimiento'] ?></td>
									<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?="S/. ".$deudas[$i]['monto'] ?></td>
									<td rowspan="<?=$rowspan ?>" <?=$estilo?> ><?php if($vencido) echo "<span class='glyphicon glyphicon-remove'></span>"; else echo "<span class='glyphicon glyphicon-ok'></span>"; ?>	<?="S/. ".$deudas[$i]['descuento_pago_oportuno'] ?></td>
<?php							}
								if ($nroBoletas==0){	?>
									<td <?=$estilo?> ></td>
									<td <?=$estilo?> ></td>
									<td <?=$estilo?> ></td>
									<td <?=$estilo?> ></td>
									<td <?=$estilo?> ></td>
<?php							} //echo "// deuda:".$i." a:".$repeticion." pasada:".$repeticiones."//";
								if($nroBoletas>0) {	?>
									<td <?=$estilo?> ><?=str_pad($deudas[$i]['boletas'][$repeticion]['serie'],3,"0",STR_PAD_LEFT)."-".str_pad($deudas[$i]['boletas'][$repeticion]['correlativo'],6,"0",STR_PAD_LEFT) ?></td>
									<td <?=$estilo?> ><?=$deudas[$i]['boletas'][$repeticion]['fecha'] ?></td>
									<td <?=$estilo?> ><?=$deudas[$i]['boletas'][$repeticion]['monto'] ?></td>
									<td <?=$estilo?> ><?=$deudas[$i]['boletas'][$repeticion]['descuento'] ?></td>
									<td <?=$estilo?> ><?=$deudas[$i]['boletas'][$repeticion]['total'] ?></td>
<?php							}	
								if($repeticion==0){	?>
									<td  rowspan="<?=$rowspan ?>" <?=$estilo?> ><?="S/. ".$saldo?></td>
									<td  rowspan="<?=$rowspan ?>" <?=$estilo?> >
<?php 									if($deudas[$i]['cancelado']==FALSE){
											if($formulario){
?>
												<form method="POST" action="../../src/Controladores/CajaControl.php" class="form-inline" role="form" onsubmit="return validacion()" name="boleta">
													<div class="col-xs-8">
														<input type="number" step="any" class="form-control input-sm" name="efectivo" id="efectivo" placeholder="Efectivo" required="true" >
													</div>
													<input type='hidden' name='fecha' id='fecha' value='<?=$hoy ?>'>
													<input type='hidden' name='hora' id='hora' value='<?=$hora ?>'>
													<input type='hidden' name='llave_deuda' id='llave_deuda' value='<?=$i?>'>
													<input type='hidden' name='cobro' id='cobro' value='<?=$cobro?>'>
													<input type='hidden' name='descuento' id='descuento' value='<?=$descuento?>'>
													<input type='hidden' name='total_boletas' id='total_boletas' value='<?=$totalBoletas?>'>
													<input type='hidden' name='saldo' id='saldo' value='<?=$saldo?>'>
													<input type='hidden' name='control' id='control' value='GuardarBoleta'>
													<button type="submit" class="btn btn-primary btn-sm" >Boleta</button>
												</form>
<?php 										}
											$formulario = FALSE;
										}	?>
									</td>
<?php 							}	?>
							</tr>
<?php					$repeticion++;
						}
					}		
				}			?>
			
				</table>
			</ul>
	</ol>
</div>
<script type="text/javascript">
	function validarOtroPago() {
		efectivo = document.getElementById("efectivo_otro_pago").value;
		monto = <?=$monto?>;
		if( efectivo<monto) {
			alert("Monto ingresado menor al requerido");
			document.getElementById("efectivo_otro_pago").value='';
			document.getElementById("efectivo_otro_pago").focus();
			return false;
		}
		if (confirm('¿ESTA SEGURO DE EMITIR BOLETA?')){ 
			document.boleta.submit() 
		} else {
			document.getElementById("efectivo_otro_pago").focus();
			return false;
		}
	}
	function validacion() {
		valor = document.getElementById("efectivo").value;
		if( valor<0  ) {
			alert("MONTO NO VÁLIDO");
			document.getElementById("efectivo").value='';
			document.getElementById("efectivo").focus();
			return false;
		}
		if (confirm('¿ESTA SEGURO DE EMITIR BOLETA?')){ 
			document.boleta.submit() 
		} else {
			document.getElementById("efectivo").focus();
			return false;
		}
	}
</script>