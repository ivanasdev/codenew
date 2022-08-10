<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
//include("class.Formateo.php");

include("class.SalidaInsumo.php");
$objSalidaInsumo = new SalidaInsumo();

session_start();
if( !isset($_POST["idCabeceraSalida"],$_POST["idProductoAlmacen"],$_POST["fechaCaducidad"],$_POST["lote"],$_POST['idAreaInsumo'],$_SESSION['id_Operador']) || $_POST["idProductoAlmacen"] == '' || $_POST["idCabeceraSalida"] == ''){
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
$idCabeceraSalida = $_POST['idCabeceraSalida'];
$observaciones = utf8_decode($_POST['motivo']);
$idAreaInsumo = $_POST['idAreaInsumo'];

//Verifica Stock en Area de Insumo
if( !$objSalidaInsumo->verificaExistenciaSalidaInsumo($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idCabeceraSalida) ){
	
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'No hay suficiente existencia!!!'
				);	
	echo json_encode($respuesta);
	exit;		
}


//Inserta en tabla devolucion temporal
if( $objSalidaInsumo->insertaSalidaInsumoDetalle($idProductoAlmacen, $cantidad, $dtCaducidad, $lote, $idOperador, $idCabeceraSalida, $observaciones) ){
	
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
			'mensaje' => 'Error al agregar producto'
			);
	echo json_encode($respuesta);
	exit;
}

?>

