<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.EntradaInsumo.php");
$objEntradaInsumo = new EntradaInsumo();

session_start();
if( !isset($_POST["idCabeceraEntrada"],$_POST["idDetalleEntrada"]) || $_POST["idDetalleEntrada"] == '' || $_POST["idCabeceraEntrada"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}


$idCabeceraEntrada = $_POST['idCabeceraEntrada'];
$idDetalleEntrada = $_POST['idDetalleEntrada'];


$objEntradaInsumo->borraProductoEntradaInsumo($idCabeceraEntrada, $idDetalleEntrada);
	
	$respuesta = array(
			'error' => '0',
			'mensaje' => 'Se borró el producto!');
	echo json_encode($respuesta);
	exit;	


?>