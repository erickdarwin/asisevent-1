<div name="menu">
	<ul class="nav nav-tabs nav-justified">
		<li class='active'><a href='#' >DIARIO</a></li>
	</ul>
	
</div>
<?php 
$stands = $_SESSION['stands_por_fecha'];
$boletas = $_SESSION['boletas_por_fecha'];
?>

<div class="container">

	<div class="table-responsive">
	<?php
		if(count($stands)>0){

			for ($k=0; $k < count($stands); $k++) {
				$subtotal=$j=0;
	?>
				<h3>PUNTO DE ATENCIÓN: <small><?=$stands[$k]['punto_de_venta'] ?> </small></h3>
				<table class='table table-hover'>
					<thead>
						<tr>
							<td align='center'><strong>#</strong></td>
							<td align='center'><strong>NRO. BOLETA</strong></td>
							<td align='center'><strong>DETALLE</strong></td>
							<td align='center'><strong>MONTO</strong></td>
							<td align='center'><strong>DESCUENTO</strong></td>
							<td align='center'><strong>TOTAL</strong></td>
							<td align='center'><strong>OPCIONES</strong></td>
						</tr>
					</thead>
					<tbody>
	<?php					for ($i=0; $i < count($boletas) ; $i++) {
								if ($boletas[$i]['stand_id']==$stands[$k]['id']) {
									$j++; 
									if ($boletas[$i]['anulado']==0) { //si la boleta no esta anulada
										$subtotal+=$boletas[$i]['total'];;
										echo "<tr>";
										echo "<td>".$j."</td>"; 
										echo "<td>".$boletas[$i]['serie']."-".$boletas[$i]['correlativo']."</td>";
										echo "<td>".$boletas[$i]['detalle']."</td>";
										echo "<td align='right'>".$boletas[$i]['monto']."</td>";	
										echo "<td align='right'>".$boletas[$i]['descuento']."</td>";
										echo "<td align='right'>".$boletas[$i]['total']."</td>";
										echo "<td align='center'><a href='#' onclick='javascript:document.elegir".$i.".submit()'>Anular</a>";
											echo "<form method='POST' name='elegir".$i."' id='elegir".$i."' action='../Controladores/CajaControl.php'>";
											echo "<input type='hidden' name='idBoleta' id='idBoleta' value='".$boletas[$i]['id']."'>";
											echo "<input type='hidden' name='idDeuda' id='idDeuda' value='".$boletas[$i]['deuda_id']."'>";
											echo "<input type='hidden' name='control' id='control' value='AnularBoleta'>";
											echo "</form> ";
										echo "</td>";
										echo "</tr>";
									} else { //si la boleta está anulada.
										echo "<tr>";
										echo "<td><s>".$j."</s></td>"; 
										echo "<td><s>".$boletas[$i]['serie']."-".$boletas[$i]['correlativo']."</s></td>";
										echo "<td><s>".$boletas[$i]['detalle']."</s></td>";
										echo "<td align='right'><s>".$boletas[$i]['monto']."</s></td>";	
										echo "<td align='right'><s>".$boletas[$i]['descuento']."</s></td>";
										echo "<td align='right'><s>".$boletas[$i]['total']."</s></td>";
										echo "<td align='center'></td>";
										echo "</tr>";
									}
								}
							}
						echo "<tr>";
							echo "<td colspan='5'></td>"; 
							echo "<td align='right'>".$subtotal."</td>";
							echo "<td></td>"; 
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			}
		}  else {
				echo "<h3>Se encontraron: <small>".count($stands)." filas encontradas</small></h3>";
		}	?>
	</div>

</div>
