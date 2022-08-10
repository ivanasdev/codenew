<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.PedidoInsumo.php");
$objPedidoInsumo = new PedidoInsumo();

session_start();

if( !isset($_POST["idPedidoInsumo"],$_POST["observaciones"],$_SESSION['id_Operador']) 
|| $_POST["idPedidoInsumo"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}

$idSucursal = $_SESSION['id_Sucursal'];
$idOperador = $_SESSION['id_Operador'];
$idPedidoInsumo = $_POST["idPedidoInsumo"];
$stObservaciones = utf8_decode($_POST["observaciones"]);

$query0 = "SELECT id_Sucursal, id_AreaInsumos FROM tbl_SUCInsumoPedido WHERE idPedidoInsumo = '".$idPedidoInsumo."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$idAreaInsumos = $arrayQuery0['id_AreaInsumos'];


if( !$objPedidoInsumo->statusValido($idPedidoInsumo) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'El folio de Pedido por Insumo: '.$idPedidoInsumo.' ya ha sido cerrado!!');
	echo json_encode($respuesta);
	exit;
}

if( $objPedidoInsumo->existeInventarioInsumosAbierto() ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'Existe un inventario de Insumos abierto, no se puede realizar el Pedido!!');
	echo json_encode($respuesta);
	exit;
}

if( !$objPedidoInsumo->existenProductosPedidoInsumos($idPedidoInsumo) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'No hay productos en el pedido!!');
	echo json_encode($respuesta);
	exit;
}


	
	//Cierra el Pedido de Insumos
	$objPedidoInsumo->cierraPedidoInsumos($idPedidoInsumo, $stObservaciones, $idOperador);



	$respuesta = array(
				'error' => '0',
				'mensaje' => 'Se complet&oacute; el pedido de insumos correctamente!');
	echo json_encode($respuesta);

?>