<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
include("../../../../system/cedis/CEDDevolucion/class.Formateo.php");
$objFormateo = new Formateo();

$idPedidoInsumo = $_POST['idPedidoInsumo'];


	$query0 = "SELECT 
		t1.id_PedidoInsumoDetalle,
		UPPER(t2.st_Nombre) as nombreProducto,	
		t1.i_Cantidad,
		t2.id_Insumo,
		t2.i_Activo,
		t1.st_Observaciones
	FROM tbl_SUCInsumoPedidoDetalle t1 
	INNER JOIN cat_CEDInsumos t2 ON t1.id_Insumo = t2.id_Insumo
	WHERE t1.id_PedidoInsumo = '".$idPedidoInsumo."'
	ORDER BY id_PedidoInsumoDetalle";
		
	$rquery0 = mssql_query($query0);
	$totalProds = mssql_num_rows($rquery0);
	
	if($totalProds > 0){
		$i = 0;
		$tabla = ' 			
		<table class="fancyTable" id="myTable02" cellpadding="0" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>No</th>
				<th width="200px">Descripci&oacute;n</th>			
				<th>Cantidad</th>
				<th>Observaciones</th>
				<th>-</th>
			</tr>
			</thead>
			<tbody>
		';
		
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){
		$i++;	
	
	
		$boton = '<input type="button" value="-" onClick="JavaScript:eliminaProductoPedidoInsumo('.$idPedidoInsumo.','.$arrayQuery0["id_PedidoInsumoDetalle"].',\''.$objFormateo->mostrarCaracteresRaros($arrayQuery0["nombreProducto"]).'\');" class="botonRojo"';
										
		$tabla .= '
			<tr>
				<td align="center" style="background-color:#CCC;">'.$i.'</td>				
				<td>
				'.$objFormateo->mostrarCaracteresRaros($arrayQuery0["nombreProducto"]).'
				</td>							
				<td align="center">'.$arrayQuery0["i_Cantidad"].'</td>
				<td align="center">'.$objFormateo->mostrarCaracteresRaros($arrayQuery0["st_Observaciones"]).'</td>
				<td align="center">'.$boton.'</td>
			</tr>
		';

			
		}
		
		$tabla .= "
		</tbody>
		</table>
		";						
		
	}
	else{
		$tabla = "No se ha agregado ningún producto...";
	}


$query0 = "SELECT COUNT(id_PedidoInsumo) as productos, SUM(i_Cantidad) as piezas
FROM tbl_SUCInsumoPedidoDetalle WHERE id_PedidoInsumo = '".$idPedidoInsumo."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$productosPiezas = 'Productos: '.$arrayQuery0["productos"].' / Piezas: '.$arrayQuery0["piezas"];


$respuesta = array(
	'error' => '0',
	'tabla' => $tabla,
	'productosPiezas' => $productosPiezas
);
echo json_encode($respuesta);


?>

