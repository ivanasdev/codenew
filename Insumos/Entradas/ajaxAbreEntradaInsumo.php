<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.EntradaInsumo.php");
$objEntradaInsumo = new EntradaInsumo();

session_start();

if( !isset($_SESSION['id_Operador'],$_SESSION['id_Sucursal'],$_POST['idSalidaCedis'],$_POST['idAreaInsumosC'])){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}

$idOperador = $_SESSION['id_Operador'];
$idSucursal = $_SESSION['id_Sucursal'];
$idSalidaCedis = $_POST['idSalidaCedis'];
$idAreaInsumos = $_POST['idAreaInsumosC'];

$objEntradaInsumo->abreEntradaInsumos($idSucursal, $idOperador, $idSalidaCedis, $idAreaInsumos);
$idCabeceraEntrada = $objEntradaInsumo->idCabeceraEntrada;

$respuesta = array(
			'error' => '0',
			'mensaje' => 'Se gener&oacute; correctamente el folio: '.$idCabeceraEntrada,
			'idCabeceraEntrada' => $idCabeceraEntrada );
echo json_encode($respuesta);

?>