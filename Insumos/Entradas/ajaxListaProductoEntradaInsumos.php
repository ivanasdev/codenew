<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
include("../../../../system/cedis/CEDDevolucion/class.Formateo.php");
$objFormateo = new Formateo();

session_start();

$idCabeceraEntrada = $_POST['idCabeceraEntrada'];
$idAreaInsumo = $_POST['idAreaInsumo'];
$idSalidaDirectaCedis = $_POST['idSalidaDirectaCedis'];
$idSucursal = $_SESSION["id_Sucursal"];

	$query0 = "SELECT 
		t1.id_DetalleEntrada,
		t2.id_UPC as codigoBarras,  
		t2.st_Nombre as nombreProducto,
		t2.st_SA as sustanciaActiva,
		t1.dt_FechaCaducidad,
		t1.st_Lote,
		t1.st_Ubicacion,
		t1.i_Cantidad,
		t2.id_ProductoAlmacen,
		t2.i_Activo,
		t1.st_Observaciones,
		t1.id_SalidaDirectaCedis,
		isnull(t3.Stock,0) as Stock
	FROM tbl_SUCEntradaInsumoDetalleTmp t1 
	INNER JOIN cat_ProductosAlmacenMaster t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
	LEFT JOIN(
		SELECT SUM(i_Cantidad) as Stock, id_ProductoAlmacen FROM tbl_SUCStockAlmacenInsumos 
		WHERE id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumo."' GROUP BY id_ProductoAlmacen
	) t3 ON t1.id_ProductoAlmacen = t3.id_ProductoAlmacen
	WHERE t1.id_CabeceraEntrada = '".$idCabeceraEntrada."'";
		
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
				<th>Ubicaci&oacute;n</th>
				<th>Cantidad</th>
				<th>Stock</th>
				<th>Observaciones</th>
				<th>-</th>
			</tr>
			</thead>
			<tbody>
		';
		
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){
		$i++;	
			
		if(!$idSalidaDirectaCedis > 0){	
			$boton = '<input type="button" value="-" onClick="JavaScript:eliminaProductoEntradaInsumo('.$idCabeceraEntrada.','.$arrayQuery0["id_DetalleEntrada"].',\''.$arrayQuery0["codigoBarras"].'\');" class="botonRojo"';
		}else{
			$boton = "-";	
		}
										
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
				<td>'.$arrayQuery0["st_Ubicacion"].'</td>
				<td align="center">'.$arrayQuery0["i_Cantidad"].'</td>
				<td align="center">'.$arrayQuery0["Stock"].'</td>
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


$query0 = "SELECT COUNT(id_CabeceraEntrada) as productos, SUM(i_Cantidad) as piezas
FROM tbl_SUCEntradaInsumoDetalleTmp WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";
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

