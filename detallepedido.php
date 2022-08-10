<?php
session_start();
require(".../db.php");

$idUsuarioWeb = $_SESSION['id_Operador'];
$idEvento = $_GET['idpedido'];

$idTipoUsuario = $_SESSION['id_TipoUsuario'];
 
$query = "exec sp_get_PedidosProveedor 0,'','',".$idEvento;

$rowdata = mssql_query($query);
$row = mssql_fetch_array($rowdata);

$idStatus = $row['id_Status'];
 
if (isset($_POST['id_StatusPedido']) ){
	$idStatuspedido = $_POST['id_StatusPedido'];
	$txtComentario = trim($_POST['txt_comentario']);
	 
	$query = "exec sp_updatePedidoStatusComentario ".$idEvento.",".$idStatuspedido.",'".$txtComentario."',".$idUsuarioWeb;

	if($idStatuspedido == 3){
		$queryCostosUpdate = "UPDATE tbl_pedidosOpticaProveedor SET st_CostoArmazon = '".$_POST['armazon']."', st_CostoBicel = '".$_POST['bicel']."',  
							 st_CostoMaterial = '".$_POST['material']."' WHERE id_EventoConcepto = ".$idEvento;
		$resCostosUpdate = mssql_query($queryCostosUpdate);
	}
   
  	$res = mssql_query($query);
	$rowdata = mssql_fetch_array($res);
	if ($rowdata['Error']==0) { 
	  	?>
	  	<script>
			alert('Se actualiz\u00f3 con \u00e9xito el pedido')
			window.opener.location.reload();
			window.close();		
		</script>
		<?php
	  } else {
	  	?>
	  	<script>
			alert('Ocurri\u00f3  un error en la actualizaci\u00f3n, int\u00e9ntalo nuevamente!')
			window.opener.location.reload();
			window.close();		
		</script>
		<?php
	  }   
	
}
 
?>
<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="estilos/styles.Validate.css" media="screen" />
<script type="text/javascript" src="js/jquery.min.js"></script>	
<script type="text/javascript">
	function muestraCostos(valor){
		if(valor == 3){
			document.getElementById('costos').style.display="block";
		}
		else{
			document.getElementById('costos').style.display="none";
		}
	}

	function soloNumeros(e){
		 key = e.keyCode || e.which;
		 tecla = String.fromCharCode(key).toLowerCase();
		 letras = " 0123456789";
		 especiales = [8,9,37,39,46];

		 tecla_especial = false
		 for(var i in especiales){
			 if(key == especiales[i]){
		  tecla_especial = true;
		  break;
					} 
		 }
 
		if(letras.indexOf(tecla)==-1 && !tecla_especial)
			return false;
	 }

	 function sumaTotal(){
	 	$("#total").val( parseFloat(parseFloat($("#armazon").val()) + parseFloat($("#bicel").val()) + parseFloat($("#material").val())) +0.0);
	 }
</script>
</head>
<body>
	<form name="datosComple" id="datosComple" action="" method="post">
		
	
	<div id="">
		
		<div id="">
			<table cellspacing="1" cellpadding="2" border="1">
				<th>Datos del pedido</th>
				<tr>
					<td bgcolor="#0066FF">Ticket</td><td bgcolor="#FAFAD2"><?=$row['ticket']?></td>
					<td bgcolor="#0066FF">Sucursal</td><td bgcolor="#FAFAD2"><?=$row['Sucursal']?></td>
					<td bgcolor="#0066FF">Paciente</td><td bgcolor="#FAFAD2"><?=$row['Nombre']?></td>
					<td bgcolor="#0066FF">Fecha Pedido</td><td bgcolor="#FAFAD2"><?=$row['fecha compra']?></td>
				</tr>
				<tr>
					<td bgcolor="#0066FF">Folio</td><td bgcolor="#FAFAD2"><?=$row['st_Folio']?></td>
					<td bgcolor="#0066FF">Sobre</td><td bgcolor="#FAFAD2"><?=$row['st_Sobre']?></td>
					<td bgcolor="#0066FF">Esfera Der.</td><td bgcolor="#FAFAD2"><?=$row['st_EsferaDer']?></td>
					<td bgcolor="#0066FF">Esfera Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_EsferaIzq']?></td>
				</tr>
				<tr>
					<td bgcolor="#0066FF">Cilindro Der.</td><td bgcolor="#FAFAD2"><?=$row['st_CilindroDer']?></td>
					<td bgcolor="#0066FF">Cilindro Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_CilindroIzq']?></td>
					<td bgcolor="#0066FF">Eje Der.</td><td bgcolor="#FAFAD2"><?=$row['st_EjeIzq']?></td>
					<td bgcolor="#0066FF">Eje Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_AO']?></td>
				</tr>
				<tr>
					<td bgcolor="#0066FF">AO</td><td bgcolor="#FAFAD2"><?=$row['st_DI']?></td>
					<td bgcolor="#0066FF">DI</td><td bgcolor="#FAFAD2"><?=$row['st_EjeDer']?></td>
					<td bgcolor="#0066FF">ADD</td><td bgcolor="#FAFAD2"><?=$row['st_ADD']?></td>
					<td bgcolor="#0066FF">Material</><td bgcolor="#FAFAD2"><?=$row['st_Material']?></td>
				</tr>
				<tr>
					<td bgcolor="#0066FF">Armazon</td><td bgcolor="#FAFAD2"><?=$row['st_Armazon']?></td>
					<td bgcolor="#0066FF">Paquete</td><td bgcolor="#FAFAD2"><?=$row['st_Paquete']?></td>
					<td bgcolor="#0066FF">Status</td><td bgcolor="#FAFAD2"><?=$row['st_StatusPedidoOptica']?></td>
					<td bgcolor="#0066FF">Producto</td><td bgcolor="#FAFAD2"><?=$row['Producto']?></td>
				</tr>
			</table>
			<table>
				<th>Comentarios</th> 
				<tr>
					<td>Fecha:</td>
					<td>Usuario</td>
					<td>Comentario:</td>
				</tr>
				<?php
					mssql_data_seek($rowdata, 0);
					while ($rowdataC = mssql_fetch_array($rowdata)) { ?>
						<tr>	
							<td><?=$rowdataC['fechaComentario']?></td>
							<td><?=$rowdataC['usuarioComentario']?></td>
							<td><?=$rowdataC['st_Comentario']?></td>
						</tr>
						<?php						
					}
				
				mssql_close();
				?>
			</table>			
		</div>
		<div>
			<div>
				Ingresa un comentario:
				<textarea id="txt_comentario" name="txt_comentario" rows="10" cols="40" ></textarea>
			</div>
			<div>
				Cambiar estatus al pedido:
				<?php
				if ($idTipoUsuario == 16) {
					$queryCat = "select id_StatusPedidoOptica id_Status, st_StatusPedidoOptica st_Status, st_BackColor 
								from cat_StatusPedidoOptica 
								where id_Status = 1 and b_proveedor = 1
								order by 1
							";
				}
				if ($idTipoUsuario == 14) {
					$queryCat = "select id_StatusPedidoOptica id_Status, st_StatusPedidoOptica st_Status, st_BackColor 
								from cat_StatusPedidoOptica 
								where id_Status = 1 and b_proveedor = 0
								order by 1
							";
				}
				
				$res = mssql_query($queryCat);
				?>
				<select name="id_StatusPedido" id="id_StatusPedido" onChange="javascript: muestraCostos(this.value);">					
				<?php
				if ($idStatus == 1) { ?>
					<option selected id="0" disabled>Selecciona</option>
				<?php	
				}				
				while($rowCat = mssql_fetch_array($res)){ 
					if (($rowCat['id_Status'] < $idStatus ) && ($idStatus <> 6)){ ?>
						<option disabled value="<?=$rowCat['id_Status']?>"><?=$rowCat['st_Status']?></option>
						<?php
					} elseif ($rowCat['id_Status']==$idStatus){ ?>
							<option selected value="<?=$rowCat['id_Status']?>"><?=$rowCat['st_Status']?></option>
						<?php
						}else{						
						
						?>
						<option value="<?=$rowCat['id_Status']?>"><?=$rowCat['st_Status']?></option>
						<?php
					}
					
				} ?>
				</select>
<?	
	$statusterminado;

	if($idStatus == 3){
		$statusterminado = "block";
	}
	else{
		$statusterminado = "none";
	}
?>				
				<div id="costos" style="display:<?=$statusterminado?>">
					<table>
						<tr><td>Costo del armazon:</td><td><input type="text" id="armazon" name="armazon" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoArmazon']?>"></td></tr>
						<tr><td>Bicel: </td><td><input type="text" id="bicel" name="bicel" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoBicel']?>"></td></tr>
						<tr><td>Material: </td><td><input type="text" id="material" name="material" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoMaterial']?>"></td></tr>
						<tr><td>Total:</td><td><input type="text" id="total" name="total" readonly value="<?=floatval($row['st_CostoArmazon']+$row['st_CostoBicel']+$row['st_CostoMaterial'])?>"></td></tr>
					</table>
				</div>
			</div>
			<div>
				<input type="submit" id="btn_update" name="btn_update" value="Actualizar">
			</div>
		</div>
	</div>
	
	</form>
</body>
	
</html>


