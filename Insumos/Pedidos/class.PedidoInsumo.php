<?php

class PedidoInsumo{

	//Atributos
	public $idPedidoInsumo = 0;
	public $idStatusPedido = 0; 	//Estatus
	public $idOperador = 0;			//Operador que registro
	public $idAreaInsumo = 0;
	public $stAreaInsumo = '';
	public $idSucursal = 0;



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////// Abre Pedido 
function abrePedidoInsumos($idSucursal, $idOperador, $idAreaInsumos)
{
	
	$this->idAreaInsumo = $idAreaInsumos;

	$query0 = "INSERT INTO tbl_SUCInsumoPedido (id_Sucursal, id_AreaInsumos, id_Operador) 
	VALUES ('".$idSucursal."', '".$idAreaInsumos."', '".$idOperador."')";
	$rquery0 = mssql_query($query0);
	
	$query2 = "SELECT SCOPE_IDENTITY() as idPedidoInsumo";
	$rquery2 = mssql_query($query2);
	$arrayQuery2 = mssql_fetch_array($rquery2);
	$this->idPedidoInsumo = $arrayQuery2['idPedidoInsumo'];

}


//////////////////////// Existe Inventario Ciclico Abierto
function existeInventarioCiclicoAbierto()
{
	return false;
}

//////////////////////// Obtiene status del Pedido y operador que realizó
function setInfoPedidoInsumos($idPedidoInsumo)
{
	
	$query0 = "SELECT t1.*, ISNULL(t2.st_Nombre,'N/A') as stAreaInsumo, t1.id_Sucursal 
	FROM tbl_SUCInsumoPedido t1 
	LEFT JOIN cat_AreaInsumos t2 ON t1.id_AreaInsumos = t2.id_AreaInsumos
	WHERE t1.id_PedidoInsumo = '".$idPedidoInsumo."'";		
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$this->idStatusPedido = $arrayQuery0["id_Status"];
	$this->idOperador = $arrayQuery0["id_Operador"];
	$this->stAreaInsumo = $arrayQuery0["stAreaInsumo"];
	$this->idAreaInsumo = $arrayQuery0["id_AreaInsumos"];
	$this->idSucursal = $arrayQuery0["id_Sucursal"];
		
}

//Inserta el detalle del Pedido Insumo
function insertaPedidoInsumoDetalle($idInsumo, $cantidad, $idOperador, $idPedidoInsumo, $observaciones){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCInsumoPedidoDetalle
	WHERE id_Insumo = '".$idInsumo."' AND id_PedidoInsumo = '".$idPedidoInsumo."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	
	if($conteo > 0):
	
		$query0 = "UPDATE tbl_SUCInsumoPedidoDetalle SET i_Cantidad = '".$cantidad."', st_Observaciones = '".$observaciones."'
		WHERE id_Insumo = '".$idInsumo."' AND id_PedidoInsumo = '".$idPedidoInsumo."'";
		$rquery0 = mssql_query($query0);
	
	else:
			
	
	$query1 = "INSERT INTO tbl_SUCInsumoPedidoDetalle (
			id_Insumo, 
			id_PedidoInsumo, 
			i_Cantidad,
			id_Operador,
			st_Observaciones
		) 
		VALUES( 
			'".$idInsumo."', 
			'".$idPedidoInsumo."', 
			'".$cantidad."',
			'".$idOperador."',
			'".$observaciones."'
		)";
		$rquery1 = mssql_query($query1);
		
	endif;

	
	return true;
	
}

//Elimina linea de los productos del detalle del pedido
function borraProductoPedidoInsumo($idPedidoInsumo, $idPedidoDetalle){
	
	$query0 = "DELETE FROM tbl_SUCInsumoPedidoDetalle
	WHERE id_PedidoInsumoDetalle = '".$idPedidoDetalle."' AND id_PedidoInsumo = '".$idPedidoInsumo."'";
	$rquery0 = mssql_query($query0);	
	
}	

////////Verifica si no ha sido cerrada la entrada
function statusValido($idPedidoInsumo){

	$query0 = "SELECT id_Status FROM tbl_SUCInsumoPedido WHERE id_PedidoInsumo = '".$idPedidoInsumo."'";
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

//////////////////////// Existe Inventario Ciclico
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
function existenProductosPedidoInsumos($idPedidoInsumo){
	
	$query0 = "SELECT COUNT(*) as conteo FROM tbl_SUCInsumoPedidoDetalle 
	WHERE id_PedidoInsumo = '".$idPedidoInsumo."'";		
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



//////////////////////// Cierra Pedido Insumo
function cierraPedidoInsumos($idPedidoInsumo, $stObservaciones, $idOperador)
{
	
	$query0 = "UPDATE tbl_SUCInsumoPedido SET id_Status = 2, dt_FechaCierre = GETDATE(), 
	st_Observaciones = '".$stObservaciones."', id_Operador = '".$idOperador."'
	WHERE id_PedidoInsumo = '".$idPedidoInsumo."'";
	$rquery0 = mssql_query($query0);
	
}


		 					
}//Fin Class PedidoInsumo

?>