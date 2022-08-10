<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.SalidaInsumo.php");
$objSalidaInsumo = new SalidaInsumo();

session_start();
if( !isset($_POST["idCabeceraSalida"],$_POST["idDetalleSalida"]) || $_POST["idDetalleSalida"] == '' || $_POST["idCabeceraSalida"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}


$idCabeceraSalida = $_POST['idCabeceraSalida'];
$idDetalleSalida = $_POST['idDetalleSalida'];


$objSalidaInsumo->borraProductoSalidaInsumo($idCabeceraSalida, $idDetalleSalida);
	
	$respuesta = array(
			'error' => '0',
			'mensaje' => 'Se eliminó la linea!');
	echo json_encode($respuesta);
	exit;	


?>