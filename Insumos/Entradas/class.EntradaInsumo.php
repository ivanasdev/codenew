<?php

class EntradaInsumo{

	//Atributos
	public $idCabeceraEntrada = 0;
	public $idStatusEntrada = 0; 	//Estatus
	public $idOperador = 0;			//Operador que registro
	public $idAreaInsumo = 0;
	public $stAreaInsumo = '';
	public $idSucursal = 0;
	public $idSalidaDirectaCedis = 0;



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////// Abre Entrada 
function abreEntradaInsumos($idSucursal, $idOperador, $idSalidaDirectaCedis = 0, $idAreaInsumos)
{
	
	
	if( $idSalidaDirectaCedis > 0 ){
		$query1 = "SELECT id_AreaInsumos FROM tbl_CEDCabeceraSalidaDirecta WHERE id_SalidaDirecta = '".$idSalidaDirectaCedis."'";
		$rquery1 = mssql_query($query1);
		$arrayQuery1 = mssql_fetch_array($rquery1);
		$idAreaInsumos = $arrayQuery1['id_AreaInsumos'];
	}
	$this->idAreaInsumo = $idAreaInsumos;


	$query0 = "INSERT INTO tbl_SUCEntradaInsumo (id_Sucursal, id_AreaInsumos, id_SalidaDirectaCedis, id_Operador) 
	VALUES ('".$idSucursal."', '".$idAreaInsumos."', '".$idSalidaDirectaCedis."', '".$idOperador."')";
	$rquery0 = mssql_query($query0);
	
	$query2 = "SELECT SCOPE_IDENTITY() as idCabeceraEntrada";
	$rquery2 = mssql_query($query2);
	$arrayQuery2 = mssql_fetch_array($rquery2);
	$this->idCabeceraEntrada = $arrayQuery2['idCabeceraEntrada'];

	
	if( $idSalidaDirectaCedis > 0 ){		
		
		//Inserta los productos en la tabla Tmp
		$query4 = "INSERT INTO tbl_SUCEntradaInsumoDetalleTmp (id_CabeceraEntrada, id_ProductoAlmacen, i_Cantidad, 
		dt_FechaCaducidad, st_Lote, st_Ubicacion, id_Operador, id_SalidaDirectaCedis)
		SELECT '".$this->idCabeceraEntrada."',id_ProductoAlmacen, i_Cantidad, dt_FechaCaducidad, st_Lote, 
		'99-99-99', '".$idOperador."', '".$idSalidaDirectaCedis."' 
		FROM tbl_CEDSalidaDirectaDetalle WHERE id_SalidaDirecta = '".$idSalidaDirectaCedis."'
		ORDER BY id_SalidaDirectaDetalle";
		$rquery4 = mssql_query($query4);
		
		$query5 = "UPDATE tbl_CEDCabeceraSalidaDirecta SET i_RecibidoSucursal = '1' WHERE id_SalidaDirecta = '".$idSalidaDirectaCedis."'";
		$rquery5 = mssql_query($query5);
	
	}
	
	

}


//////////////////////// Existe Inventario Ciclico Abierto en Cedis
function existeInventarioCiclicoAbierto()
{
	return false;
}

//////////////////////// Obtiene status de la entrada directa y operador que realizó
function setInfoEntradaInsumos($idCabeceraEntrada)
{
	
	$query0 = "SELECT t1.*, ISNULL(t2.st_Nombre,'N/A') as stAreaInsumo, id_Sucursal FROM tbl_SUCEntradaInsumo t1 
	LEFT JOIN cat_AreaInsumos t2 ON t1.id_AreaInsumos = t2.id_AreaInsumos
	WHERE t1.id_CabeceraEntrada = '".$idCabeceraEntrada."'";		
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$this->idStatusEntrada = $arrayQuery0["id_Status"];
	$this->idOperador = $arrayQuery0["id_Operador"];
	$this->idSalidaDirectaCedis = $arrayQuery0["id_SalidaDirectaCedis"];
	$this->stAreaInsumo = $arrayQuery0["stAreaInsumo"];
	$this->idAreaInsumo = $arrayQuery0["id_AreaInsumos"];
	$this->idSucursal = $arrayQuery0["id_Sucursal"];
		
	
}

//Inserta el detalle de la Entrada Insumo
function insertaEntradaInsumoDetalle($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idOperador, $idCabeceraEntrada, $observaciones){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCEntradaInsumoDetalleTmp
	WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
	AND id_CabeceraEntrada = '".$idCabeceraEntrada."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	
	if($conteo > 0):
	
		$query0 = "UPDATE tbl_SUCEntradaInsumoDetalleTmp SET i_Cantidad = '".$cantidad."', st_Observaciones = '".$observaciones."'
		WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND dt_FechaCaducidad = '".$dtCaducidad."' AND st_Lote = '".$lote."' 
		AND id_CabeceraEntrada = '".$idCabeceraEntrada."'";
		$rquery0 = mssql_query($query0);
	
	else:
			
	
	$query1 = "INSERT INTO tbl_SUCEntradaInsumoDetalleTmp (
			id_ProductoAlmacen, 
			id_CabeceraEntrada, 
			i_Cantidad, 
			dt_FechaCaducidad, 
			st_Lote, 
			id_Operador,
			st_Observaciones
		) 
		VALUES( 
			'".$idProductoAlmacen."', 
			'".$idCabeceraEntrada."', 
			'".$cantidad."', 
			'".$dtCaducidad."', 
			'".$lote."',
			'".$idOperador."',
			'".$observaciones."'
		)";
		$rquery1 = mssql_query($query1);
		
	endif;

/*		//Actualiza Costos
		$query2 = "UPDATE tbl_CEDEntradaDirectaDetalle SET 
			tbl_CEDEntradaDirectaDetalle.i_Costo = datos.i_Costo, 
			tbl_CEDEntradaDirectaDetalle.i_CostoIVA = datos.i_CostoIVA, 
			tbl_CEDEntradaDirectaDetalle.i_IVA = datos.IVA, 
			tbl_CEDEntradaDirectaDetalle.i_PrecioMaximo = datos.i_PrecioMaximo,
			tbl_CEDEntradaDirectaDetalle.i_PrecioMaximoTotal = datos.i_PrecioMaximoTotal,
			tbl_CEDEntradaDirectaDetalle.id_AlmacenIngresos = datos.id_AlmacenIngresos
		FROM
		(
			SELECT i_Costo, 
					i_CostoIVA, 
					IVA, 
					i_PrecioMaximo,
					i_PrecioMaximoTotal,
					id_AlmacenIngresos,
					id_ProductoAlmacen
			FROM view_CEDProductoAlmacenCosto WHERE id_ProductoAlmacen = '".$idProductoAlmacen."'
		) as datos
		WHERE tbl_CEDEntradaDirectaDetalle.id_ProductoAlmacen = datos.id_ProductoAlmacen
		AND tbl_CEDEntradaDirectaDetalle.id_EntradaDirecta = '".$idEntradaDirecta."'";
		$rquery2 = mssql_query($query2);*/
	
	return true;
	
}

//Elimina linea de los productos Temporales a realizar entrada directa
function borraProductoEntradaInsumo($idCabeceraEntrada, $idDetalleEntrada){
	
	$query0 = "DELETE FROM tbl_SUCEntradaInsumoDetalleTmp
	WHERE id_DetalleEntrada = '".$idDetalleEntrada."' AND id_CabeceraEntrada = '".$idCabeceraEntrada."'";
	$rquery0 = mssql_query($query0);	
	
}	

////////Verifica si no ha sido cerrada la entrada
function statusValido($idCabeceraEntrada){

	$query0 = "SELECT id_Status FROM tbl_SUCEntradaInsumo WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";
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

/// Verifica si hay productos a registrar
function existenProductosEntradaInsumos($idCabeceraEntrada){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCEntradaInsumoDetalleTmp 
	WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";		
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


//Realiza la entrada al Almacen de Insumo
function realizaEntradaInsumos($idCabeceraEntrada, $idOperador, $idSucursal, $idAreaInsumos){
	
	
	//Insertamos la entrada al Cedis
	$query0 = "INSERT INTO tbl_SUCEntradaInsumoDetalle (id_CabeceraEntrada, id_ProductoAlmacen, i_Cantidad, dt_FechaCaducidad, 
	st_Lote, st_Ubicacion, id_Operador, id_SalidaDirectaCedis, st_Observaciones) 
	SELECT id_CabeceraEntrada, id_ProductoAlmacen, i_Cantidad, dt_FechaCaducidad, 
	st_Lote, st_Ubicacion, id_Operador, id_SalidaDirectaCedis, st_Observaciones FROM tbl_SUCEntradaInsumoDetalleTmp 
	WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";
	$rquery0 = mssql_query($query0);
			
	$query1 = "SELECT id_ProductoAlmacen, SUM(i_Cantidad) as i_Cantidad, dt_FechaCaducidad, st_Lote, st_Ubicacion 
	FROM tbl_SUCEntradaInsumoDetalleTmp 
	WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'
	GROUP BY id_ProductoAlmacen, dt_FechaCaducidad, st_Lote, st_Ubicacion";
	$rquery1 = mssql_query($query1);
	
	while( $arrayQuery1 = mssql_fetch_array($rquery1) ){
		
		$idProductoAlmacen = $arrayQuery1['id_ProductoAlmacen'];
		$iCantidad = $arrayQuery1['i_Cantidad'];
		$dtFechaCaducidad = $arrayQuery1['dt_FechaCaducidad'];
		$stLote = $arrayQuery1['st_Lote'];
		$stUbicacion = $arrayQuery1['st_Ubicacion'];
	
		//Verifica si existe producto en Stock para actualizar o Insertar
		$query2 = "SELECT count(*) as conteo FROM tbl_SUCStockAlmacenInsumos 
		WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND st_Lote = '".$stLote."' 
		AND dt_FechaCaducidad = '".$dtFechaCaducidad."' AND st_Ubicacion = '".$stUbicacion."' 
		AND id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumos."'";
		$rquery2 = mssql_query($query2);
		$arrayQuery2 = mssql_fetch_array($rquery2);
		$conteo = $arrayQuery2['conteo'];
		
		//Inserta Producto en Stock
		if($conteo == 0) {
			
			$query3 = "INSERT INTO tbl_SUCStockAlmacenInsumos (id_ProductoAlmacen, i_Cantidad, st_Lote, dt_FechaCaducidad, st_Ubicacion, 
			id_Sucursal, id_AreaInsumos)
			VALUES ('".$idProductoAlmacen."', '".$iCantidad."','".$stLote."','".$dtFechaCaducidad."', '".$stUbicacion."', 
			'".$idSucursal."','".$idAreaInsumos."')";
			$rquery3 = mssql_query($query3);
		
		}
		else{ //Actualiza Cantidad del Producto en Stock
		
			$query4 = "UPDATE tbl_SUCStockAlmacenInsumos SET i_Cantidad = i_Cantidad+'".$i_Cantidad."' 
			WHERE id_ProductoAlmacen = '".$idProductoAlmacen."' AND st_Lote = '".$stLote."' 
			AND dt_FechaCaducidad = '".$dtFechaCaducidad."' AND st_Ubicacion = '".$stUbicacion."' 
			AND id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumos."'";
			$rquery4 = mssql_query($query4);
			
		}
		
	}
	
	//Actualiza los costos de cada linea
	$query5 = "UPDATE tbl_SUCEntradaInsumoDetalle SET tbl_SUCEntradaInsumoDetalle.i_CostoIVA = datos.i_CostoIVA
	FROM
	(
		SELECT t1.id_DetalleEntrada, t2.i_CostoIVA FROM tbl_SUCEntradaInsumoDetalle t1
		LEFT JOIN view_CEDProductoAlmacenCosto t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
		WHERE t1.id_CabeceraEntrada = '".$idCabeceraEntrada."'
	) as datos
	WHERE tbl_SUCEntradaInsumoDetalle.id_DetalleEntrada = datos.id_DetalleEntrada
	AND tbl_SUCEntradaInsumoDetalle.id_CabeceraEntrada = '".$idCabeceraEntrada."'";
	$rquery5 = mssql_query($query5);
	
	
}

//////////////////////// Cierra EntradaInsumo
function cierraEntradaInsumos($idCabeceraEntrada, $stObservaciones, $idOperador, $idSalidaDirectaCedis)
{
	
	$query0 = "UPDATE tbl_SUCEntradaInsumo SET id_Status = 2, dt_FechaCierre = GETDATE(), 
	st_Observaciones = '".$stObservaciones."', id_Operador = '".$idOperador."'
	WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";
	$rquery0 = mssql_query($query0);
	
}


		 					
}//Fin Class EntradaInsumo

?>