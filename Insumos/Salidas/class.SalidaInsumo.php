<?php

class SalidaInsumo{

	//Atributos
	public $idCabeceraSalida = 0;
	public $idStatusSalida = 0; 	//Estatus
	public $idOperador = 0;			//Operador que registro
	public $idAreaInsumo = 0;
	public $stAreaInsumo = '';
	public $idSucursal = 0;
	public $erroresLineas = ""; // Aqui mostrará los errores por no cubrir el inventario

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////// Abre Salida 
function abreSalidaInsumos($idSucursal, $idOperador, $idAreaInsumos)
{
	
	$this->idAreaInsumo = $idAreaInsumos;

	$query0 = "INSERT INTO tbl_SUCInsumoSalida (id_Sucursal, id_AreaInsumos, id_Operador) 
	VALUES ('".$idSucursal."', '".$idAreaInsumos."', '".$idOperador."')";
	$rquery0 = mssql_query($query0);
	
	$query2 = "SELECT SCOPE_IDENTITY() as idCabeceraSalida";
	$rquery2 = mssql_query($query2);
	$arrayQuery2 = mssql_fetch_array($rquery2);
	$this->idCabeceraSalida = $arrayQuery2['idCabeceraSalida'];

}


//////////////////////// Existe Inventario Ciclico Abierto
function existeInventarioCiclicoAbierto()
{
	return false;
}

//////////////////////// Obtiene status de la entrada directa y operador que realizó
function setInfoSalidaInsumos($idCabeceraSalida)
{
	
	$query0 = "SELECT t1.*, ISNULL(t2.st_Nombre,'N/A') as stAreaInsumo, id_Sucursal FROM tbl_SUCInsumoSalida t1 
	LEFT JOIN cat_AreaInsumos t2 ON t1.id_AreaInsumos = t2.id_AreaInsumos
	WHERE t1.id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$this->idStatusSalida = $arrayQuery0["id_Status"];
	$this->idOperador = $arrayQuery0["id_Operador"];
	$this->stAreaInsumo = $arrayQuery0["stAreaInsumo"];
	$this->idAreaInsumo = $arrayQuery0["id_AreaInsumos"];
	$this->idSucursal = $arrayQuery0["id_Sucursal"];
		
	
}

//Valida existencia de ese producto en el area de insumos
function verificaExistenciaSalidaInsumo($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idCabeceraSalida){
	
	$query0 = "SELECT id_Sucursal, id_AreaInsumos FROM tbl_SUCInsumoSalida WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$idSucursal = $arrayQuery0["id_Sucursal"];
	$idAreaInsumos = $arrayQuery0["id_AreaInsumos"];
	
	$query1 = "SELECT id_Sucursal, id_AreaInsumos, id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion, SUM(i_Cantidad) as stockTotal 
	FROM tbl_SUCStockAlmacenInsumos WHERE id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumos."' 
	AND id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
	AND st_Ubicacion = '99-99-99'
	GROUP BY id_Sucursal, id_AreaInsumos, id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion";
	$rquery1 = mssql_query($query1);
	$arrayQuery1 = mssql_fetch_array($rquery1);
	$stockTotal = $arrayQuery1["stockTotal"];
	
	if($cantidad <= $stockTotal){
		return true;	
	}else{
		return false;	
	}
	
	
	
	
}

//Inserta el detalle de la Salida Insumo
function insertaSalidaInsumoDetalle($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idOperador, $idCabeceraSalida, $observaciones){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCInsumoSalidaDetalleTmp
	WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
	AND st_Ubicacion = '99-99-99' AND id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	
	if($conteo > 0):
	
		$query0 = "UPDATE tbl_SUCInsumoSalidaDetalleTmp SET i_Cantidad = '".$cantidad."', st_Observaciones = '".$observaciones."'
		WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
		AND st_Ubicacion = '99-99-99' AND id_CabeceraSalida = '".$idCabeceraSalida."'";
		$rquery0 = mssql_query($query0);
	
	else:
			
	
	$query1 = "INSERT INTO tbl_SUCInsumoSalidaDetalleTmp (
			id_ProductoAlmacen, 
			id_CabeceraSalida, 
			i_Cantidad, 
			dt_FechaCaducidad, 
			st_Lote,
			st_Ubicacion, 
			id_Operador,
			st_Observaciones
		) 
		VALUES( 
			'".$idProductoAlmacen."', 
			'".$idCabeceraSalida."', 
			'".$cantidad."', 
			'".$dtCaducidad."', 
			'".$lote."',
			'99-99-99',
			'".$idOperador."',
			'".$observaciones."'
		)";
		$rquery1 = mssql_query($query1);
		
	endif;

	
	return true;
	
}

//Elimina linea de los productos Temporales a realizar salida Insumo
function borraProductoSalidaInsumo($idCabeceraSalida, $idDetalleSalida){
	
	$query0 = "DELETE FROM tbl_SUCInsumoSalidaDetalleTmp
	WHERE id_DetalleSalida = '".$idDetalleSalida."' AND id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);	
	
}	

////////Verifica si no ha sido cerrada la salida
function statusValido($idCabeceraSalida){

	$query0 = "SELECT id_Status FROM tbl_SUCInsumoSalida WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$idStatus = $arrayQuery0["id_Status"];
	
	if($idStatus == 1){
		return true;
	}
	else{
		return false;	
	}
	
}

//////////////////////// Existe Inventario Ciclico Abierto en Cedis
function existeInventarioInsumosAbierto()
{
	
	/*$query0 = "SELECT COUNT(*) as conteo FROM tbl_CEDInventarioCiclico WHERE id_Status = '1'";		
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	if($conteo > 0){
		return true;
	}
	else{
		return false;
	}*/
	
	return false;
	
}

/// Verifica si hay productos a sacar
function existenProductosSalidaInsumos($idCabeceraSalida){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCInsumoSalidaDetalleTmp 
	WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";		
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	
	if($conteo == 0){
		return false;
	}
	else{
		return true;	
	}

}




//Valida existencia de TODOS los productos en el area de insumos
function verificaExistenciaTotal($idCabeceraSalida){

	$this->erroresLineas = "";
	$error = 0;	
	
	$query0 = "SELECT id_Sucursal, id_AreaInsumos FROM tbl_SUCInsumoSalida WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$idSucursal = $arrayQuery0["id_Sucursal"];
	$idAreaInsumos = $arrayQuery0["id_AreaInsumos"];
		
	$query1 = "SELECT id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion, i_Cantidad 
	FROM tbl_SUCInsumoSalidaDetalleTmp WHERE id_CabeceraSalida = '".$idCabeceraSalida."' ORDER BY dt_FechaRegistro";
	$rquery1 = mssql_query($query1);
	
	while( $arrayQuery1 = mssql_fetch_array($rquery1) ){
	
		$idProductoAlmacen = $arrayQuery1["id_ProductoAlmacen"];
		$dtCaducidad = $arrayQuery1["dt_FechaCaducidad"];
		$lote = $arrayQuery1["st_Lote"];
		$iCantidad = $arrayQuery1["i_Cantidad"];
	
		$query2 = "SELECT id_Sucursal, id_AreaInsumos, id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion, SUM(i_Cantidad) as stockTotal 
		FROM tbl_SUCStockAlmacenInsumos WHERE id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumos."' 
		AND id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
		AND st_Ubicacion = '99-99-99'
		GROUP BY id_Sucursal, id_AreaInsumos, id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion";
		$rquery2 = mssql_query($query2);
		$arrayQuery2 = mssql_fetch_array($rquery2);
		$stockTotal = $arrayQuery2["stockTotal"];
		
		if($iCantidad > $stockTotal){
			
			$query3 = "SELECT id_UPC, st_Nombre FROM cat_ProductosAlmacenMaster WHERE id_ProductoAlmacen = '".$idProductoAlmacen."'";
			$rquery3 = mssql_query($query3);
			$arrayQuery3 = mssql_fetch_array($rquery3);
			$idUPC = $arrayQuery3["id_UPC"];
			$stNombre = $arrayQuery3["st_Nombre"];
									
			$error++;
			$this->erroresLineas .= '<br>UPC:'.$idUPC.' &nbsp;&nbsp; Caducidad:'.$dtCaducidad.' &nbsp;&nbsp; Lote:'.$lote.' &nbsp;&nbsp; Cantidad:'.$iCantidad.'';	
			
		}	
	
	}
	
	if($error > 0){
		return false;	
	}else{
		return true;	
	}


}


//Realiza la salida del Almacen de Insumo
function realizaSalidaInsumos($idCabeceraSalida, $idOperador, $idSucursal, $idAreaInsumos){
	
	
	//Insertamos la salida de Insumos Sucursal
	$query0 = "INSERT INTO tbl_SUCInsumoSalidaDetalle (id_CabeceraSalida, id_ProductoAlmacen, i_Cantidad, dt_FechaCaducidad, 
	st_Lote, st_Ubicacion, id_Operador, st_Observaciones) 
	SELECT id_CabeceraSalida, id_ProductoAlmacen, i_Cantidad, dt_FechaCaducidad, 
	st_Lote, st_Ubicacion, id_Operador, st_Observaciones FROM tbl_SUCInsumoSalidaDetalleTmp 
	WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
			
	$query1 = "SELECT id_ProductoAlmacen, SUM(i_Cantidad) as i_Cantidad, dt_FechaCaducidad, st_Lote, st_Ubicacion 
	FROM tbl_SUCInsumoSalidaDetalleTmp 
	WHERE id_CabeceraSalida = '".$idCabeceraSalida."'
	GROUP BY id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion";
	$rquery1 = mssql_query($query1);
	
	while( $arrayQuery1 = mssql_fetch_array($rquery1) ){
		
		$idProductoAlmacen = $arrayQuery1['id_ProductoAlmacen'];
		$iCantidad = $arrayQuery1['i_Cantidad'];
		$dtFechaCaducidad = $arrayQuery1['dt_FechaCaducidad'];
		$stLote = $arrayQuery1['st_Lote'];
		$stUbicacion = $arrayQuery1['st_Ubicacion'];
			
		//Actualiza Cantidad del Producto en Stock		
		$query4 = "UPDATE tbl_SUCStockAlmacenInsumos SET i_Cantidad = (i_Cantidad-".$iCantidad.") 
		WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND st_Lote = '".$stLote."' 
		AND dt_FechaCaducidad = '".$dtFechaCaducidad."' AND st_Ubicacion = '".$stUbicacion."' 
		AND id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumos."'";
		$rquery4 = mssql_query($query4);					
		
	}
	
	//Borra los que quedaron negativos o en cero
	$query2 = "DELETE FROM tbl_SUCStockAlmacenInsumos WHERE i_Cantidad <= '0'";
	$rquery2 = mssql_query($query2);
	
	//Actualiza los costos de cada linea
	$query5 = "UPDATE tbl_SUCInsumoSalidaDetalle SET tbl_SUCInsumoSalidaDetalle.i_CostoIVA = datos.i_CostoIVA
	FROM
	(
		SELECT t1.id_DetalleSalida, t2.i_CostoIVA FROM tbl_SUCInsumoSalidaDetalle t1
		LEFT JOIN view_CEDProductoAlmacenCosto t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
		WHERE t1.id_CabeceraSalida = '".$idCabeceraSalida."'
	) as datos
	WHERE tbl_SUCInsumoSalidaDetalle.id_DetalleSalida = datos.id_DetalleSalida
	AND tbl_SUCInsumoSalidaDetalle.id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery5 = mssql_query($query5);
	
	
}

//////////////////////// Cierra SalidaInsumo
function cierraSalidaInsumos($idCabeceraSalida, $stObservaciones, $idOperador)
{
	
	$query0 = "UPDATE tbl_SUCInsumoSalida SET id_Status = 2, dt_FechaCierre = GETDATE(), 
	st_Observaciones = '".$stObservaciones."', id_Operador = '".$idOperador."'
	WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
	$rquery0 = mssql_query($query0);
	
}


		 					
}//Fin Class EntradaInsumo

?>