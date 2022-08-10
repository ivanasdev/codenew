<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.PedidoInsumo.php");
$objPedidoInsumo = new PedidoInsumo();

session_start();
if( !isset($_POST["idPedidoInsumo"],$_POST["idPedidoDetalle"]) || $_POST["idPedidoDetalle"] == '' || $_POST["idPedidoInsumo"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}


$idPedidoInsumo = $_POST['idPedidoInsumo'];
$idPedidoDetalle = $_POST['idPedidoDetalle'];


$objPedidoInsumo->borraProductoPedidoInsumo($idPedidoInsumo, $idPedidoDetalle);
	
	$respuesta = array(
			'error' => '0',
			'mensaje' => 'Se borró el producto!');
	echo json_encode($respuesta);
	exit;	


?>