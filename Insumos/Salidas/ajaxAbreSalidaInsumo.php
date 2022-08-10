<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.SalidaInsumo.php");
$objSalidaInsumo = new SalidaInsumo();

session_start();

if( !isset($_SESSION['id_Operador'],$_SESSION['id_Sucursal'],$_POST['idAreaInsumosC'])){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}

$idOperador = $_SESSION['id_Operador'];
$idSucursal = $_SESSION['id_Sucursal'];
$idAreaInsumos = $_POST['idAreaInsumosC'];

$objSalidaInsumo->abreSalidaInsumos($idSucursal, $idOperador, $idAreaInsumos);
$idCabeceraSalida = $objSalidaInsumo->idCabeceraSalida;

$respuesta = array(
			'error' => '0',
			'mensaje' => 'Se gener&oacute; correctamente el folio: '.$idCabeceraSalida,
			'idCabeceraSalida' => $idCabeceraSalida );
echo json_encode($respuesta);

?>