<?php
session_start();
require("../db.php");
$ruta2index = "../../";
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" href="../styles/style.css" type="text/css">
<link rel="stylesheet" type="text/css" href="estilos/styles.Validate.css" media="screen" />
<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/botones.css" type="text/css">
<link href="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
<link href="<?=$ruta2index?>utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>	
<?
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
			$('#refreshPagina', window.parent.document).val(1);	 	
		</script>
		<?php
	  } else {
	  	?>
	  	<script>
			alert('Ocurri\u00f3  un error en la actualizaci\u00f3n, int\u00e9ntalo nuevamente!')
			$('#refreshPagina', window.parent.document).val(1);	 	
		</script>
		<?
	  }   
	
}
?>
<script type="text/javascript">
	function muestraCostos(valor){
		if(valor == 3){
			document.getElementById('costos').style.display="block";
		}else{
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
<style>
.borde{		border:1px solid #000;	}
</style>
</head>
<body>
	<form name="datosComple" id="datosComple" action="" method="post">			
	<div id="">		
		<div id="">
			<table cellspacing="1" cellpadding="2" width="780px" class="borde">
				<th colspan="8">Datos del pedido</th>
				<tr>
					<td bgcolor="#5DA2CE">Ticket</td><td bgcolor="#FAFAD2"><?=$row['ticket']?></td>
					<td bgcolor="#5DA2CE">Sucursal</td><td bgcolor="#FAFAD2"><?=$row['Sucursal']?></td>
					<td bgcolor="#5DA2CE">Paciente</td><td bgcolor="#FAFAD2"><?=$row['Nombre']?></td>
					<td bgcolor="#5DA2CE">Fecha Pedido</td><td bgcolor="#FAFAD2"><?=$row['fecha compra']?></td>
				</tr>
				<tr>
					<td bgcolor="#5DA2CE">Folio</td><td bgcolor="#FAFAD2"><?=$row['st_Folio']?></td>
					<td bgcolor="#5DA2CE">Sobre</td><td bgcolor="#FAFAD2"><?=$row['st_Sobre']?></td>
					<td bgcolor="#5DA2CE">Esfera Der.</td><td bgcolor="#FAFAD2"><?=$row['st_EsferaDer']?></td>
					<td bgcolor="#5DA2CE">Esfera Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_EsferaIzq']?></td>
				</tr>
				<tr>
					<td bgcolor="#5DA2CE">Cilindro Der.</td><td bgcolor="#FAFAD2"><?=$row['st_CilindroDer']?></td>
					<td bgcolor="#5DA2CE">Cilindro Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_CilindroIzq']?></td>
					<td bgcolor="#5DA2CE">Eje Der.</td><td bgcolor="#FAFAD2"><?=$row['st_EjeIzq']?></td>
					<td bgcolor="#5DA2CE">Eje Izq.</td><td bgcolor="#FAFAD2"><?=$row['st_AO']?></td>
				</tr>
				<tr>
					<td bgcolor="#5DA2CE">AO</td><td bgcolor="#FAFAD2"><?=$row['st_DI']?></td>
					<td bgcolor="#5DA2CE">DI</td><td bgcolor="#FAFAD2"><?=$row['st_EjeDer']?></td>
					<td bgcolor="#5DA2CE">ADD</td><td bgcolor="#FAFAD2"><?=$row['st_ADD']?></td>
					<td bgcolor="#5DA2CE">Material</><td bgcolor="#FAFAD2"><?=$row['st_Material']?></td>
				</tr>
				<tr>
					<td bgcolor="#5DA2CE">Armazon</td><td bgcolor="#FAFAD2"><?=$row['st_Armazon']?></td>
					<td bgcolor="#5DA2CE">Paquete</td><td bgcolor="#FAFAD2"><?=$row['st_Paquete']?></td>
					<td bgcolor="#5DA2CE">Status</td><td bgcolor="#FAFAD2"><?=$row['st_StatusPedidoOptica']?></td>
					<td bgcolor="#5DA2CE">Producto</td><td bgcolor="#FAFAD2"><?=$row['Producto']?></td>
				</tr>
			</table>
            <br/>
            <table width="780px">
            	<tr>
                	<td>
                        <table class="borde">
                            <tr>
                                <td>Ingresa un comentario:</td>
                                <td><textarea id="txt_comentario" name="txt_comentario" rows="10" cols="40" ></textarea></td>
                            </tr>
                        </table>
                    </td>
                    <td rowspan="2" align="left" valign="top">
                    	<table class="borde">
                            <th colspan="3">Comentarios</th> 
                            <tr>
                                <td bgcolor="#5DA2CE">Fecha:</td>
                                <td bgcolor="#5DA2CE">Usuario</td>
                                <td bgcolor="#5DA2CE">Comentario:</td>
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
                	</td>                  
                </tr>
                <tr>
                	<td>
                    	<table class="borde" width="392px">
                        	<tr>
                            	<td>Cambiar estatus al pedido:</td>
                                <td align="left">
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
                                </td>
                            </tr>
<?	
	$statusterminado;
	if($idStatus == 3){
		$statusterminado = "block";
	}else{
		$statusterminado = "none";
	}
?>		
                            <tr>
                            	<td colspan="2">
                                <div id="costos" style="display:<?=$statusterminado?>">
                                    <table>
                                        <tr><td>Costo del armazon:</td><td><input type="text" id="armazon" name="armazon" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoArmazon']?>"></td></tr>
                                        <tr><td>Bicel: </td><td><input type="text" id="bicel" name="bicel" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoBicel']?>"></td></tr>
                                        <tr><td>Material: </td><td><input type="text" id="material" name="material" onkeypress="return soloNumeros(event);" onkeyUp="sumaTotal();" value="<?=$row['st_CostoMaterial']?>"></td></tr>
                                        <tr><td>Total:</td><td><input type="text" id="total" name="total" readonly value="<?=floatval($row['st_CostoArmazon']+$row['st_CostoBicel']+$row['st_CostoMaterial'])?>"></td></tr>
                                    </table>
                                </div>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2" align="center"><input type="submit" id="btn_update" name="btn_update" value="Actualizar" class="botonAzul"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>	                    			
		</div>		
	</div>
	
	</form>
</body>
	
</html>


