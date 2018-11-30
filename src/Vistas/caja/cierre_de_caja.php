<div name="menu">
	<ul class="nav nav-tabs nav-justified">
		<li class='active'><a href='#' >CIERRE DE CAJA</a></li>
	</ul>
	
</div>

<?php 
date_default_timezone_set('America/Lima');
setlocale(LC_TIME, "Spanish");		

$hora=strftime("%H:%M");

$stands_pendientes = $_SESSION['stands_pendientes'];
//print_r($_SESSION['boletas_pendientes']);

?>

<div class="container">

	<hr/>
	<table>
		<tr>
			<td>
				<form action='../Controladores/CajaControl.php' method="POST" name="form_stands" id="form_stands" class="form-inline" role="form" >
					<label>Punto de Atención<span class="badge pull-right"><?=count($stands_pendientes) ?></span></label>
					<select class="form-control" name="stand_id" id="stand_id" onchange="this.form.submit()">
						<option value="" disabled="true" <?php if(!isset($_SESSION['stand_id'])) echo "selected='true'"; ?> >Elija un Punto de Atención</option>
	<?php
						if( isset($_SESSION['stands_pendientes']) ){
							$stands_pendientes=$_SESSION['stands_pendientes'];
							for ($i=0; $i < count($stands_pendientes); $i++) { 
								$value="";
								if(isset($_SESSION['stand_id'])) {
									if($_SESSION['stand_id']==$stands_pendientes[$i]['id']) {
										$value="selected='true'";
									}
								}
								echo "<option value='".$stands_pendientes[$i]['id']."' ".$value.">".$stands_pendientes[$i]['punto_de_venta']."</option>";
							}
						}
	?>
					</select>
					<input type='hidden' name='control' id='control' value='Cierre'>
					<input type='hidden' name='subcontrol' id='subcontrol' value='Stand'>
				</form>
			</td>
			<td>
				<form action='../Controladores/CajaControl.php' method="POST" name="form_fecha" id="form_fecha" class="form-inline" role="form" >
					<label>Fecha<span class="badge pull-right"><?php if(isset($_SESSION['fechas_pendientes'])) echo count($_SESSION['fechas_pendientes']); ?></span></label>
					<select class="form-control" name="fecha_id" id="fecha_id" onchange="this.form.submit()">
						<option value="" disabled="true" selected="true">Seleccione Fecha</option>
	<?php
						if( isset($_SESSION['fechas_pendientes']) ){
							$fechas=$_SESSION['fechas_pendientes'];
							for ($i=0; $i < count($fechas); $i++) { 
								$value="";
								if(isset($_SESSION['fecha_id'])) {
									if($_SESSION['fecha_id']==$fechas[$i]['fecha']) {
										$value="selected='true'";
									}
								}
								echo "<option value='".$fechas[$i]['fecha']."' ".$value.">".$fechas[$i]['fecha']."</option>";
							}
						}
	?>
					</select>
					<input type='hidden' name='control' id='control' value='Cierre'>
					<input type='hidden' name='subcontrol' id='subcontrol' value='Fecha'>
				</form>
			</td>
			<td>
				<form action='../Controladores/CajaControl.php' method="POST" name="form_usuario" id="form_usuario" class="form-inline" role="form" >
					<label>Usuario<span class="badge pull-right"><?php if(isset($_SESSION['usuarios_pendientes'])) echo count($_SESSION['usuarios_pendientes']); ?></span></label>
					<select class="form-control" name="usuario_id" id="usuario_id" onchange="this.form.submit()">
						<option value="" disabled="true" selected="true">Seleccione Usuario</option>
	<?php
						if( isset($_SESSION['usuarios_pendientes']) ){
							$usuarios=$_SESSION['usuarios_pendientes'];
							for ($i=0; $i < count($usuarios); $i++) { 
								$value="";
								if(isset($_SESSION['usuario_id'])) {
									if($_SESSION['usuario_id']==$usuarios[$i]['username']) {
										$value="selected='true'";
									}
								}
								echo "<option value='".$usuarios[$i]['username']."' ".$value.">".$usuarios[$i]['username']."</option>";
							}
						}
	?>
					</select>
					<input type='hidden' name='control' id='control' value='Cierre'>
					<input type='hidden' name='subcontrol' id='subcontrol' value='Usuario'>
				</form>
			</td>
		</tr>
	</table>

	<hr />
	
	<div class="table-responsive">
		
<?php
$subtotal=-1;
		if(isset($_SESSION['boletas_pendientes'])){
			$boletas = $_SESSION['boletas_pendientes'];
			$subtotal=$j=0;
?>
				<h4>BOLETAS PENDIENTES: <small><?=count($boletas) ?> encontradas. </small></h4>
				<table class='table table-hover'>
					<thead>
						<tr>
							<td align='center'><strong>#</strong></td>
							<td align='center'><strong>NRO. BOLETA</strong></td>
							<td align='center'><strong>FECHA</strong></td>
							<td align='center'><strong>DETALLE</strong></td>
							<td align='center'><strong>MONTO</strong></td>
							<td align='center'><strong>DESCUENTO</strong></td>
							<td align='center'><strong>TOTAL</strong></td>
							<td align='center' colspan="2"><strong>OPCIONES</strong></td>
						</tr>
					</thead>
					<tbody>
<?php					for ($i=0; $i < count($boletas) ; $i++) {
							$j++; 
							if ($boletas[$i]['anulado']==0) { //si la boleta no esta anulada
								$subtotal+=$boletas[$i]['total'];	?>
									<tr>
										<td><?=$j ?></td>
										<td><?=$boletas[$i]['serie']."-".$boletas[$i]['correlativo'] ?></td>
										<td><?=$boletas[$i]['fecha'] ?></td>
										<td><?=$boletas[$i]['detalle'] ?></td>
										<td align='right'><?=$boletas[$i]['monto'] ?></td>	
										<td align='right'><?=$boletas[$i]['descuento'] ?></td>
										<td align='right'><?=$boletas[$i]['total'] ?></td>
										<td align='center'><a href='./caja/reimprimir.php?orden=<?=$i ?>' target="_blank">Imprimir</a></td>
										<td align='center'><a href='#' onclick='javascript:document.elegir<?=$i?>.submit()'>Anular</a>
											<form method='POST' name='elegir<?=$i?>' id='elegir<?=$i?>' action='../Controladores/CajaControl.php'>
												<input type='hidden' name='idBoleta' id='idBoleta' value='<?=$boletas[$i]['id']?>'>
												<input type='hidden' name='idDeuda' id='idDeuda' value='<?=$boletas[$i]['deuda_id']?>'>
												<input type='hidden' name='control' id='control' value='Cierre'>
												<input type='hidden' name='subcontrol' id='subcontrol' value='AnularBoleta'>
											</form>
										</td>
										
									</tr>
<?php							} else { //si la boleta está anulada.	?>
									<tr>
										<td><s><?=$j ?></s></td>
										<td><s><?=$boletas[$i]['serie']."-".$boletas[$i]['correlativo']?></s></td>
										<td><s><?=$boletas[$i]['fecha']?></s></td>
										<td><s><?=$boletas[$i]['detalle']?></s></td>
										<td align='right'><s><?=$boletas[$i]['monto']?></s></td>
										<td align='right'><s><?=$boletas[$i]['descuento']?></s></td>
										<td align='right'><s><?=$boletas[$i]['total']?></s></td>
										<td align='center'><s><a href='./caja/reimprimir.php?orden=<?=$i ?>' target="_blank">Imprimir</a></s></td>
									</tr>
<?php							}

							}		?>
						<tr>
							<td colspan='6'></td>
							<td align='right'><?=$subtotal?></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				

<?php	}  else {
				echo "<h5><b>BOLETAS PENDIENTES: </b><small> 0 Boletas - Parámetros incompletos.</small></h5>";
		}	?>
	</div>

	<hr />

	<h5><b>DATOS DE CIERRE: </b>
		<small>
			<?php
			if(isset($_SESSION['usuario_id']) AND isset($_SESSION['fecha_id']) AND $subtotal>=0){
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;USUARIO: ".$_SESSION['usuario_id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FECHA DE EMISIÓN: ".$_SESSION['fecha_id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EFECTIVO: ".$subtotal;
				$listo = FALSE;
			} else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;USUARIO: NO DEFINIDO  "."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FECHA DE EMISIÓN: NO DEFINIDO "."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EFECTIVO: NO DEFINIDO";
				$listo = TRUE;
			}
			?>
		</small>
	</h5>
	<form action='../Controladores/CajaControl.php' method="POST" class="form-inline" role="form" onsubmit="return validacion()" name="cierre">
		<div class="form-group">
	    	<label for="Monto">Efectivo Total</label>
	    	<input type="number" step="any" class="form-control" name="monto" id="monto" placeholder="Introduce total de efectivo">
		</div>
		<div class="form-group">
			<label for="comentario">Comentario Adicional</label>
			<input type="text" class="form-control" name="comentario" id="comentario" placeholder="Comentario adicional">
		</div>

		<input type='hidden' name='subtotal' id='subtotal' value='<?=$subtotal ?>'>
		<input type='hidden' name='username_cajero' id='username_cajero' value='<?=$_SESSION['usuario_id'] ?>'>
		<input type='hidden' name='stand_id' id='stand_id' value='<?=$_SESSION['stand_id'] ?>'>
		<input type='hidden' name='fecha' id='fecha' value='<?=$_SESSION['fecha_id'] ?>'>
		<input type='hidden' name='hora' id='hora' value='<?=$hora ?>'>
		<input type='hidden' name='subcontrol' id='subcontrol' value='Cerrar'>
		<input type='hidden' name='control' id='control' value='Cierre'>
		
		<button type="submit" class="btn btn-default" <?php if($listo) echo "disabled='true'" ?> >Guardar</button>
	</form>


</div>

<script type="text/javascript">
	function validacion() {
		subtotal = document.getElementById("subtotal").value;
		monto = document.getElementById("monto").value;
		if( monto<subtotal ) {
			alert("MONTO NO VÁLIDO");
			document.getElementById("monto").value='';
			document.getElementById("monto").focus();
			return false;
		}
		if (confirm('¿ESTA SEGURO DE REGISTRAR CIERRE DE CAJA?')){ 
			document.cierre.submit() 
		} else {
			document.getElementById("monto").focus();
			return false;
		}
	}
</script>