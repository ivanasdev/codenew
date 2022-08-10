<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
//include("class.Formateo.php");

include("class.EntradaInsumo.php");
$objEntradaInsumo = new EntradaInsumo();

session_start();
if( !isset($_POST["idCabeceraEntrada"],$_POST["idProductoAlmacen"],$_POST["fechaCaducidad"],$_POST["lote"],$_SESSION['id_Operador']) || $_POST["idProductoAlmacen"] == '' || $_POST["idCabeceraEntrada"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}


$idOperador = $_SESSION["id_Operador"];
$dtCaducidad = $_POST['fechaCaducidad'];
$lote = $_POST['lote'];
$cantidad = $_POST['cantidad'];
$idProductoAlmacen = $_POST['idProductoAlmacen'];
$idCabeceraEntrada = $_POST['idCabeceraEntrada'];
$observaciones = utf8_decode($_POST['observaciones']);


//Inserta en tabla devolucion temporal
if( $objEntradaInsumo->insertaEntradaInsumoDetalle($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idOperador, $idCabeceraEntrada, $observaciones) ){
	
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
			'mensaje' => 'Error al generar la entrada'
			);
	echo json_encode($respuesta);
	exit;
}

			



?>

