<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.EntradaInsumo.php");
$objEntradaInsumo = new EntradaInsumo();

session_start();

if( !isset($_POST["idCabeceraEntrada"],$_POST["observacionesEntradaInsumo"],$_SESSION['id_Operador'],$_POST["idSalidaDirectaCedis"]) 
|| $_POST["idCabeceraEntrada"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}

$idSucursal = $_SESSION['id_Sucursal'];
$idOperador = $_SESSION['id_Operador'];
$idCabeceraEntrada = $_POST["idCabeceraEntrada"];
$stObservaciones = utf8_decode($_POST["observacionesEntradaInsumo"]);
$idSalidaDirectaCedis = $_POST["idSalidaDirectaCedis"];

$query0 = "SELECT id_Sucursal, id_AreaInsumos FROM tbl_SUCEntradaInsumo WHERE id_CabeceraEntrada = '".$idCabeceraEntrada."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$idAreaInsumos = $arrayQuery0['id_AreaInsumos'];



if( !$objEntradaInsumo->statusValido($idCabeceraEntrada) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'El folio de Entrada por Insumo: '.$idCabeceraEntrada.' ya ha sido cerrado!!');
	echo json_encode($respuesta);
	exit;
}

if( $objEntradaInsumo->existeInventarioInsumosAbierto() ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'Existe un inventario de Insumos abierto, no se puede realizar la Entrada!!');
	echo json_encode($respuesta);
	exit;
}

if( !$objEntradaInsumo->existenProductosEntradaInsumos($idCabeceraEntrada) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'No hay productos a Ingresar!!');
	echo json_encode($respuesta);
	exit;
}


	//Entrada al Almacen de Insumos
	$objEntradaInsumo->realizaEntradaInsumos($idCabeceraEntrada, $idOperador, $idSucursal, $idAreaInsumos);
	
	//Cierra la entrada de Insumos
	$objEntradaInsumo->cierraEntradaInsumos($idCabeceraEntrada, $stObservaciones, $idOperador, $idSalidaDirectaCedis);



	$respuesta = array(
				'error' => '0',
				'mensaje' => 'Se complet&oacute; la entrada de insumos correctamente!');
	echo json_encode($respuesta);

?>