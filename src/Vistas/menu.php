<?php

$user = $_SESSION['user'];
$menu = $_SESSION['menu'];

$rolActual=$_SESSION['rolActual'];

?>

<div class="btn-group">
	<form method="POST" name="cambiarRol" id="cambiarRol" action="../../src/Controladores/UsersControlador.php"> 
		<select name="nuevoRol" class="form-control" onchange="this.form.submit()">
			<?php
			foreach($menu as $key => $value) {
				foreach($value as $key=>$value) {
					if ($key=='rol'){
						if($value==$rolActual) echo "<option selected>".$value."</option>";
						else echo "<option>".$value."</option>";
					}
				}
			}
			?>
		</select>
		<input type="hidden" name="control" id="control" value="cambiarRol" >
	</form> 
</div>



<?php
for($i=0;$i<count($menu);$i++){
	if($menu[$i]['rol']==$rolActual){
		
		for($j=0;$j<count($menu[$i]['modulos']);$j++){
?>
			<div class="btn-group">
				<button type="button" class="btn btn-primary"><?=$menu[$i]['modulos'][$j]['modulo'] ?></button>
			 
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
			 		<span class="sr-only">Desplegar menú</span>
			  	</button>
			 
				<ul class="dropdown-menu" role="menu">
			
<?php				for($k=0;$k<count($menu[$i]['modulos'][$j]['opcions']);$k++){
					
					echo "<li><a href='#' onclick='javascript:document.".$menu[$i]['modulos'][$j]['opcions'][$k]['opcion'].".submit()'>".$menu[$i]['modulos'][$j]['opcions'][$k]['opcion']."</a></li>";

					echo "<form method='POST' name='".$menu[$i]['modulos'][$j]['opcions'][$k]['opcion']."' id='".$menu[$i]['modulos'][$j]['opcions'][$k]['opcion']."' action='../Controladores/".$menu[$i]['modulos'][$j]['modulo']."Control.php'>";

					echo "<input type='hidden' name='control' id='control' value='".$menu[$i]['modulos'][$j]['opcions'][$k]['opcion']."'>";

					echo "</form> ";
					
			}
?>			

				</ul>
			</div>

<?php

		}
	}
}

?>




<div class="btn-group">
  <button type="button" class="btn btn-primary">Académico</button>
 
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Desplegar menú</span>
  </button>
 
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Acción #1</a></li>
    <li><a href="#">Acción #2</a></li>
    <li><a href="#">Acción #3</a></li>
    <li class="divider"></li>
    <li><a href="#">Acción #4</a></li>
  </ul>
</div>

<div class="btn-group">
	<a href="#" onclick="javascript:document.salir.submit()">
		<button type="button" class="btn btn-primary">Salir</button>
	</a>
 
	<form method="POST" name="salir" id="salir" action="../../src/Controladores/UsersControl.php"> 
		<input type="hidden" name="control" id="control" value="salir" >
	</form> 
	
</div>

<div class="btn-group">
	<p class="navbar-text">Usuario: <?=$user[0]['username']	?></p>
	<p class="navbar-text">PA: <?php if(isset($_SESSION['standAbiertos'])) if(count($_SESSION['standAbiertos'])==1) echo $_SESSION['standAbiertos'][0]['punto_de_venta']; else echo"CERRADO"; else echo"CERRADO";	?></p>
	<p class="navbar-text">Fecha: <strong><?php echo $_SESSION['fechaSistema'] ?></strong></p>
</div>
