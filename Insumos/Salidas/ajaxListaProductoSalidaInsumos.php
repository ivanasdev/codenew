<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
include("../../../../system/cedis/CEDDevolucion/class.Formateo.php");
$objFormateo = new Formateo();

$idCabeceraSalida = $_POST['idCabeceraSalida'];

	$query0 = "SELECT 
		t1.id_DetalleSalida,
		t2.id_UPC as codigoBarras,  
		t2.st_Nombre as nombreProducto,
		t2.st_SA as sustanciaActiva,
		t1.dt_FechaCaducidad,
		t1.st_Lote,
		t1.st_Ubicacion,
		t1.i_Cantidad,
		t2.id_ProductoAlmacen,
		t2.i_Activo,
		t1.st_Observaciones
	FROM tbl_SUCInsumoSalidaDetalleTmp t1 
	INNER JOIN cat_ProductosAlmacenMaster t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
	WHERE t1.id_CabeceraSalida = '".$idCabeceraSalida."'";
		
	$rquery0 = mssql_query($query0);
	$totalProds = mssql_num_rows($rquery0);
	
	if($totalProds > 0){
		$i = 0;
		$tabla = ' 			
		<table class="fancyTable" id="myTable02" cellpadding="0" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>No</th>
				<th>UPC</th>
				<th width="200px">Descripci&oacute;n</th>
				<th>Caducidad</th>
				<th>Lote</th>
				<th>Cantidad</th>
				<th>Observaciones</th>
				<th>-</th>
			</tr>
			</thead>
			<tbody>
		';
		
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){
		$i++;	
	
	
		$boton = '<input type="button" value="-" onClick="JavaScript:eliminaProductoSalidaInsumo('.$idCabeceraSalida.','.$arrayQuery0["id_DetalleSalida"].',\''.$arrayQuery0["codigoBarras"].'\');" class="botonRojo"';
										
		$tabla .= '
			<tr>
				<td align="center" style="background-color:#CCC;">
				'.$i.'						
				</td>
				
				<td align="center">'.$arrayQuery0["codigoBarras"].'</td>
				<td>
				'.$objFormateo->mostrarCaracteresRaros($arrayQuery0["nombreProducto"]).'<br><br>
				<strong>Sustancia Activa:</strong> '.$objFormateo->mostrarCaracteresRaros($arrayQuery0["sustanciaActiva"]).'
				</td>			
				<td>
				<label>'.$objFormateo->fecha("Y-m",$arrayQuery0["dt_FechaCaducidad"]).'</label>			
				</td>
				<td>'.$arrayQuery0["st_Lote"].'</td>
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


$query0 = "SELECT COUNT(id_CabeceraSalida) as productos, SUM(i_Cantidad) as piezas
FROM tbl_SUCInsumoSalidaDetalleTmp WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
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

