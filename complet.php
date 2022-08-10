<?php
session_start();
require(".../db.php");

$idUsuarioWeb = $_SESSION['id_Operador'];
$idEvento = $_GET['id_Evento'];
 
 $query = 'select id_Cliente, st_Folio, st_Sobre, st_EsferaIzq, st_EsferaDer, st_CilindroDer,st_CilindroIzq, 
 			st_EjeIzq, st_EjeDer,st_AO, st_DI, st_ADD, st_Material, st_Armazon, st_Paquete, st_Observaciones,
 			id_Status 
			from tbl_pedidosOpticaProveedor
			where id_EventoConcepto = '.$idEvento;

$rowdata = mssql_query($query);
$row = mssql_fetch_array($rowdata);


if ($row['id_Status'] > 1){
	$disabled = 'disabled';
}else{
	$disabled = '';
}
 
if (isset($_POST['txt_Folio']) ){
	$folio = $_POST['txt_Folio'];
	$sobre = $_POST['txt_Sobre'];
	$esferaI = $_POST['txt_EsferaIzq'];
	$esferaD = $_POST['txt_EsferaDer'];
	$cilindroI = $_POST['txt_CilindroI'];
	$cilindroD = $_POST['txt_CilindroD'];
	$EjeD = $_POST['txt_EJEDer'];
	$EjeI = $_POST['txt_EJEIzq'];
	$ao = $_POST['txt_AO'];
	$di = $_POST['txt_DI'];
	$ADD = $_POST['txt_ADD'];
	$material = $_POST['txt_Material'];
	$armazon = $_POST['txt_Armazon'];
	$paquete = $_POST['txt_Paquete'];
	$obs = $_POST['txt_Observaciones'];
	$idCliente = $_POST['id_Cliente']; 
	 $query = "exec sp_pedidos ".$idEvento.",".$idCliente.",'".$folio."','".$sobre."','".$esferaI."','".$esferaD."','".$cilindroI."','".$cilindroD."','".$EjeI."','".$ao."','".$EjeD."','".$di."','".$ADD."','".$material."','".$armazon."','".$paquete."','".$obs."',".$idUsuarioWeb;
   
  	$res = mssql_query($query);
	 
	$rowdata = mssql_fetch_array($res);
	 if ($rowdata['Error']==0) { 
	  	?>
	  	<script>
			alert('Se actualiz\u00f3 con \u00e9xito el registro')
			window.opener.location.reload();
			window.close();		
		</script>
		<?php
	  } else {
	  	?>
	  	<script>
			alert('Ocurri\u00f3  un error en el registro, int\u00e9ntalo nuevamente!')
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

</head>
<body>
	<form name="datosComple" id="datosComple" action="" method="post">
		
	
	<table id="div_datosComple">
		<tr id="group">
			<td>Proveedor:</td>
			<td id="input">
				 <select <?=$disabled?> id="id_Cliente" name="id_Cliente">
					<option value="99999">Selecciona</option>
					  <?
					   
						  $queryselec = "
							select id_ClienteOptica, st_Cliente 
							from tbl_ProveedorOptica 
							where id_Status = 1
							order by 1";
					   $rqueryselec = mssql_query($queryselec);
					 while ($rowdata = mssql_fetch_array($rqueryselec)) {
			  			if ($rowdata['id_ClienteOptica'] == $row['id_Cliente']){ ?>
			  				 <option selected value="<?=$rowdata['id_ClienteOptica']?>"><?=htmlentities($rowdata['st_Cliente'])?></option>
							
			  			<?php }else { ?>
			  			    <option value="<?=$rowdata['id_ClienteOptica']?>"><?=htmlentities($rowdata['st_Cliente'])?></option>			  				
			  			<?php }
					   
					  } 
					  ?>   
				</select> 		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Folio:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_Folio" name="txt_Folio" placeholder="Ingresa el folio" value="<?=$row['st_Folio']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Sobre:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_Sobre" name="txt_Sobre" placeholder="Ingresa el no. de sobre" value="<?=$row['st_Sobre']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Esfera Der:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_EsferaDer" name="txt_EsferaDer" placeholder="Ingresa Esfera Der" value="<?=$row['st_EsferaDer']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Cilindro Der:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_CilindroD" name="txt_CilindroD" placeholder="Ingresa Cilindro Der" value="<?=$row['st_CilindroDer']?>" />		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Eje Der:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_EJEDer" name="txt_EJEDer" placeholder="Ingresa Eje Der"  value="<?=$row['st_AO']?>" />		
			</td>	
		</tr>
		<tr id="group">
			<td id="label">Esfera Izq:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_EsferaIzq" name="txt_EsferaIzq" placeholder="Ingresa Esfera Izq" value="<?=$row['st_EsferaIzq']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Cilindro Izq:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_CilindroI" name="txt_CilindroI" placeholder="Ingresa Cilindro Izq" value="<?=$row['st_CilindroIzq']?>" />		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Eje Izq:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_EJEIzq" name="txt_EJEIzq" placeholder="Ingresa Eje Izq" value="<?=$row['st_EjeIzq']?>" />		
			</td>	
		</tr>
		<tr id="group">
			<td id="label">AO:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_AO" name="txt_AO" placeholder="Ingresa AO" value="<?=$row['st_EjeDer']?>" />		
			</td>
		</tr> 
		<tr id="group">
			<td id="label">DI:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_DI" name="txt_DI" placeholder="Ingresa DI" value="<?=$row['st_DI']?>" />		
			</td>
		</tr>
		<tr id="group">
			<td id="label">ADD:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_ADD" name="txt_ADD" placeholder="Ingresa ADD" value="<?=$row['st_ADD']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Material:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_Material" name="txt_Material" placeholder="Ingresa Material" value="<?=$row['st_Material']?>"/>		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Armazon:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_Armazon" name="txt_Armazon" placeholder="Ingresa Armazon" value="<?=$row['st_Armazon']?>" />		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Paquete:</td>
			<td id="input">
				<input <?=$disabled?> type="text" id="txt_Paquete" name="txt_Paquete" placeholder="Ingresa Paquete" value="<?=$row['st_Paquete']?>" />		
			</td>
		</tr>
		<tr id="group">
			<td id="label">Observaciones:</td>
			<td id="input"> 
				<textarea <?=$disabled?> id="txt_Observaciones" placeholder="Ingresa Observaciones" name="txt_Observaciones" ><?=$row['st_Observaciones']?></textarea>		
			</td>
        </tr>
		<tr id="group"> 
			<td id="label">.</td>
			<td id="input"> 
				<?php
				if ($row['id_Status'] < 2){		 ?>			
					<input type="submit" id="btn_enviar" name="btn_enviar" value="Actualizar Informaci&oacute;n" />
					<?php
				} 
				?>
				
			</td>
		</tr>
	</table>
	
	</form>
</body>
	
</html>