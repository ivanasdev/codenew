<?php
require("../db.php");
session_start();

$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
if ( !class_exists('Tracking')) {
    include($ruta2index."class.Tracking.php");
}

$idPedido = $_POST['idPedido'];
$idStatus = $_POST['idStatus'];

if(!isset($_POST["idPedido"])){
	$respuesta = array(
		'error' => '1',
		'mensaje' => 'Ocurrio un error, contacte al administrador');
	echo json_encode($respuesta);
	exit;	
}


//Obtiene el status a cambiar
$query1 = "SELECT st_StatusPedidoOptica FROM cat_StatusPedidoOptica WHERE id_StatusPedidoOptica = '".$idStatus."'";
$rquery1 = mssql_query($query1);
$arrayQuery1 = mssql_fetch_array($rquery1);
$stStatusPedido = $arrayQuery1["st_StatusPedidoOptica"];

$objTracking = new Tracking(7,83,"PEDIDO - Cambio de status a ".$stStatusPedido); ////////////////////////////////////////


// Cambia a facturado
switch($idStatus){
	case '14': 
			$query0 = "
				UPDATE
					tbl_pedidosOpticaProveedor
				SET
					id_Status = '".$idStatus."',
					id_OperadorEnviaLaboratorio = '".$_SESSION["id_Operador"]."',
					dt_FechaEnvioLaboratorio = getdate()
				WHERE
					id_PedidoOpticaProveedor = '".$idPedido."'
			";
			
			$query1.=" 
				INSERT INTO tbl_EvCambioStatusPedidosOptica (
					id_StatusOld,
					id_StatusNew,
					id_Operador,
					id_PedidoOpticaProveedor
				)
				VALUES(
					'1',
					".$idStatus.",
					".$_SESSION["id_Operador"].",
					".$idPedido."
			)
			";
			
		break;
	case '13': 
			$query0 = "
				UPDATE
					tbl_pedidosOpticaProveedor
				SET
					id_Status = '".$idStatus."',
					id_OperadorEntregadoCliente = '".$_SESSION["id_Operador"]."',
					dt_FechaEntregadoCliente = getdate()
				WHERE
					id_PedidoOpticaProveedor = '".$idPedido."'
			";
			
			$query1.=" 
				INSERT INTO tbl_EvCambioStatusPedidosOptica (
					id_StatusOld,
					id_StatusNew,
					id_Operador,
					id_PedidoOpticaProveedor
				)
				VALUES(
					'12',
					".$idStatus.",
					".$_SESSION["id_Operador"].",
					".$idPedido."
			)
			";
			
		break;
	case '12':
			$query0 = "
				UPDATE
					tbl_pedidosOpticaProveedor
				SET
					id_Status = '".$idStatus."',
					id_OperadorRecibidoSucursal = '".$_SESSION["id_Operador"]."',
					dt_FechaRecibidoSucursal = getdate()
				WHERE
					id_PedidoOpticaProveedor = '".$idPedido."'
			";
			
			$query1.=" 
				INSERT INTO tbl_EvCambioStatusPedidosOptica (
					id_StatusOld,
					id_StatusNew,
					id_Operador,
					id_PedidoOpticaProveedor
				)
				VALUES(
					'11',
					".$idStatus.",
					".$_SESSION["id_Operador"].",
					".$idPedido."
			)
			";
		break;
}


if( mssql_query($query0) ){
		
	mssql_query($query1);

	$respuesta = array(
		'error' => '0',
		'fecha' => date('Y/m/d'),
		'mensaje' => 'Todo Correcto');
	echo json_encode($respuesta);
	exit;

}
else{
	
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'No se pudo cambiar el status del pedido, contacte al administrador'.$query0);	
	echo json_encode($respuesta);
	exit;		
	
}




	


?>