<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');

include("class.PedidoInsumo.php");
$objPedidoInsumo = new PedidoInsumo();

session_start();
if( !isset($_POST["idPedidoInsumo"],$_POST["idInsumo"],$_SESSION['id_Operador']) || $_POST["idInsumo"] == '' || $_POST["idPedidoInsumo"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}


$idOperador = $_SESSION["id_Operador"];
$cantidad = $_POST['cantidad'];
$idInsumo = $_POST['idInsumo'];
$idPedidoInsumo = $_POST['idPedidoInsumo'];
$observaciones = utf8_decode($_POST['observaciones']);


//Inserta en tabla detalle Pedido Insumo
if( $objPedidoInsumo->insertaPedidoInsumoDetalle($idInsumo, $cantidad, $idOperador, $idPedidoInsumo, $observaciones) ){
	
	$respuesta = array(
				'error' => '0',
				'mensaje' => 'Todo OK!!!'
				);	
	echo json_encode($respuesta);
	exit;		
}
else{
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Error al agregar insumo'
			);
	echo json_encode($respuesta);
	exit;
}

			



?>

