<?php
$ruta2index = "../../../../";
require($ruta2index."dbConexion.php");

include("class.SalidaInsumo.php");
$objSalidaInsumo = new SalidaInsumo();

session_start();

if( !isset($_POST["idCabeceraSalida"],$_POST["observacionesSalidaInsumo"],$_SESSION['id_Operador']) 
|| $_POST["idCabeceraSalida"] == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}

$idSucursal = $_SESSION['id_Sucursal'];
$idOperador = $_SESSION['id_Operador'];
$idCabeceraSalida = $_POST["idCabeceraSalida"];
$stObservaciones = utf8_decode($_POST["observacionesSalidaInsumo"]);

$query0 = "SELECT id_Sucursal, id_AreaInsumos FROM tbl_SUCInsumoSalida WHERE id_CabeceraSalida = '".$idCabeceraSalida."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$idAreaInsumos = $arrayQuery0['id_AreaInsumos'];
$idSucursal = $arrayQuery0['id_Sucursal'];



if( !$objSalidaInsumo->statusValido($idCabeceraSalida) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'El folio de Salida por Insumo: '.$idCabeceraSalida.' ya ha sido cerrado!!');
	echo json_encode($respuesta);
	exit;
}

if( $objSalidaInsumo->existeInventarioInsumosAbierto() ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'Existe un inventario de Insumos abierto, no se puede realizar la Salida!!');
	echo json_encode($respuesta);
	exit;
}

if( !$objSalidaInsumo->existenProductosSalidaInsumos($idCabeceraSalida) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'No hay productos a Ingresar!!');
	echo json_encode($respuesta);
	exit;
}

if( !$objSalidaInsumo->verificaExistenciaTotal($idCabeceraSalida) ){
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'Cantidad de salida supera el stock. Revise las cantidades!!!<br>'.$objSalidaInsumo->erroresLineas);
	echo json_encode($respuesta);
	exit;
}



	//Salida del Almacen de Insumos
	$objSalidaInsumo->realizaSalidaInsumos($idCabeceraSalida, $idOperador, $idSucursal, $idAreaInsumos);
	
	//Cierra la Salida de Insumos
	$objSalidaInsumo->cierraSalidaInsumos($idCabeceraSalida, $stObservaciones, $idOperador);



	$respuesta = array(
				'error' => '0',
				'mensaje' => 'Se complet&oacute; la salida de insumos correctamente!');
	echo json_encode($respuesta);

?>