<?php
$ruta2index = "../../";
session_start();
require($ruta2index."dbConexion.php");

////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,72,"PRESUPUESTO OPTICA - Genera Preticket Optica");
///////////////////////////////////////////////////////	

if( !isset($_POST["id_TicketGeneral"]) || trim($_POST["id_TicketGeneral"]) == ''){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'Parametros Invalidos, vuelve a iniciar el proceso');
	echo json_encode($respuesta);
	exit;
}



if(! isset($_SESSION["id_TicketGeneral"]))
	$idTicketGeneral = $_POST["id_TicketGeneral"];
else
	$idTicketGeneral = $_SESSION["id_TicketGeneral"];

//Verifica si existe Preticket
$query0 = "SELECT count(*) as conteo FROM tbl_TicketGeneral WHERE id_TicketGeneral = '".$idTicketGeneral."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$conteo = $arrayQuery0['conteo'];

if($conteo == 0){
	$respuesta = array(
			'error' => '1',
			'mensaje' => 'No existe el preticket '.$idTicketGeneral.' de la cotización, vuelva a iniciar la sesión!');
	echo json_encode($respuesta);
	exit;	
}

//Verificamos si la cotización tiene productos.
// $query2 = "
// SELECT
// 	COUNT(t2.id_CotizacionOpticaDetalle) AS conteo
// FROM
// 	tbl_CotizacionOptica t1
// INNER JOIN
// 	tbl_CotizacionOpticaDetalle t2 ON t1.id_cotizacionOptica = t2.id_CotizacionOptica
// WHERE
// 	t2.st_Key  = '".$idTicketGeneral."'
// ";

// $rquery2 = mssql_query($query2);
// $row2 = mssql_fetch_object($rquery2);
// $conteo = $row2->conteo;

// if(isset($_POST["flag"]))
// 	// $conteo = 1;

// if($conteo == 0){
// 	$respuesta = array(
// 			'error' => '1',
// 			'mensaje' => '[ERROR] Asegurese de que el presupuesto contenga al menos un producto!');
// 	echo json_encode($respuesta);
// 	exit;	
// }

/*
// Validar que este registrado el ticket en la tabla tbl_CuentasCobrarUsuario ya que asi se sabe si se genera un preticke o no
$query2 = "SELECT COUNT(t1.id_CuentaCobrarUsuario) conteo FROM tbl_CuentasCobrarUsuario t1
	INNER JOIN tbl_CotizacionOptica t2  ON t2.id_cotizacionOptica = t1.id_cotizacionOptica
	WHERE t1.st_Key = '".$idTicketGeneral."' AND t1.id_Concepto = 3
";
//hay ventas q pertenece al concepto 7 por eso ocmente la validacion 
$rquery2 = mssql_query($query2);
$row2 = mssql_fetch_object($rquery2);
$conteoCuentaCobrarUsuario = $row2->conteo;

if($conteoCuentaCobrarUsuario == 0){
	$respuesta = array(
			'error' => '1',
			'mensaje' => '[ERROR] Asegurese de que el presupuesto se proceso la compra a caja!');
	echo json_encode($respuesta);
	exit;	
}*/

///// CLIENTES
require_once("clases/class.OpticaSubrogado.php");
$objOpticaSubrogado = new OpticaSubrogado();
//////////////#########################################################################################################
//////////////#########################################################################################################
///////////////////////  AJALPAN - Si es Paquete 1 mica CR-39 al año
$query4 = "
SELECT 
	count(*) as conteo
FROM tbl_CotizacionOptica t1 
INNER JOIN tbl_CotizacionOpticaDetalle t2 ON t1.id_cotizacionOptica = t2.id_CotizacionOptica 
WHERE t2.id_ServicioOptica IN ('631','1044') AND t2.st_Key = '".$idTicketGeneral."'";
$rquery4 = mssql_query($query4);
$arrayQuery4 = mssql_fetch_array($rquery4);
$conteoAJA = $arrayQuery4['conteo'];

$query4 = "SELECT id_cotizacionOptica, id_UsuarioWeb FROM tbl_CotizacionOptica WHERE st_Key = '".$idTicketGeneral."'";
$rquery4 = mssql_query($query4);
$arrayQuery4 = mssql_fetch_array($rquery4);
$idUsuarioWeb = $arrayQuery4['id_UsuarioWeb'];
$idCotizacionOptica = $arrayQuery4['id_cotizacionOptica'];

//Total de Paquete 1 mica CR-39 al año pone el cliente 29
if( $objOpticaSubrogado->esPacienteAJA($idUsuarioWeb) && $conteoAJA >= 1 ):	

	$totalOpticaAJA = $objOpticaSubrogado->obtieneTotalTicketsOpticaAJA($idUsuarioWeb);
	if($totalOpticaAJA < 4){		
		//Actualiza el id_Cliente en CotizacionOptica
		$query4 = "UPDATE tbl_CotizacionOptica SET id_Cliente = '29' WHERE id_cotizacionOptica = '".$idCotizacionOptica."'";
		$rquery4 = mssql_query($query4);		
	}

endif;
//////////////#########################################################################################################

// $queryCam = "
// SELECT 
// 	count(*) as conteo
// FROM tbl_CotizacionOptica t1 
// INNER JOIN tbl_CotizacionOpticaDetalle t2 ON t1.id_cotizacionOptica = t2.id_CotizacionOptica 
// WHERE t2.id_ServicioOptica IN ('631','1044') AND t2.st_Key = '".$idTicketGeneral."'";
// $rqueryCam = mssql_query($queryCam);
// $arrayQueryCam = mssql_fetch_array($rqueryCam);
// $conteoCAM = $arrayQueryCam['conteo'];

//////////////#########################################################################################################

//Total de PAQUETE 1 y MICA TERMINADO CR W 1 al año al cliente 36
if( $objOpticaSubrogado->esPacienteCAM($idUsuarioWeb) && $conteoAJA >= 1):	

	$totalOpticaCAM = $objOpticaSubrogado->obtieneTotalTicketsOpticaCAM($idUsuarioWeb);
	if($totalOpticaCAM < 1){		
		//Actualiza el id_Cliente en CotizacionOptica
		$query4 = "UPDATE tbl_CotizacionOptica SET id_Cliente = '36' WHERE id_cotizacionOptica = '".$idCotizacionOptica."'";
		$rquery4 = mssql_query($query4);		
	}

endif;
//////////////#########################################################################################################
//////////////#########################################################################################################

// PUEBLA
//Total de PAQUETE 1 y MICA TERMINADO CR W 1 al año al cliente 41
if( $objOpticaSubrogado->esPacientePUEBLA($idUsuarioWeb) && $conteoAJA >= 1):	

	$totalOpticaPUEBLA = $objOpticaSubrogado->obtieneTotalTicketsOpticaPUEBLA($idUsuarioWeb);
	if($totalOpticaPUEBLA < 1){		
		//Actualiza el id_Cliente en CotizacionOptica
		$query4 = "UPDATE tbl_CotizacionOptica SET id_Cliente = '41' WHERE id_cotizacionOptica = '".$idCotizacionOptica."'";
		$rquery4 = mssql_query($query4);		
	}

endif;


$query3 = "SELECT TOP 1 id_Cliente FROM tbl_CotizacionOptica WHERE st_Key = '".$idTicketGeneral."'";
$rquery3 = mssql_query($query3);
$arrayQuery3 = mssql_fetch_array($rquery3);
$idCliente = $arrayQuery3['id_Cliente'];

if( in_array($idCliente,array(18,21,22,23,27,29,36)) ){
	$query3 = "UPDATE tbl_TicketGeneral SET id_Cliente = '".$idCliente."' WHERE id_TicketGeneral = '".$idTicketGeneral."'";
	$rquery3 = mssql_query($query3);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////// Pone el ID Cliente en el ticket SUBROGADO de la nueva estructura
$getDataClienteSubrogado = $objOpticaSubrogado->getDataClienteSubrogado($idCliente, 4);
$id_ReglaSubrogado = $getDataClienteSubrogado->id_ReglaSubrogado;

if ($id_ReglaSubrogado > 0) {
	$idCliente = $getDataClienteSubrogado->id_Cliente;	
	// $st_NombreCliente = $getDataClienteSubrogado->st_NombreCliente;
	$query3 = "UPDATE tbl_TicketGeneral SET id_Cliente = '".$idCliente."' WHERE id_TicketGeneral = '".$idTicketGeneral."'";
	$rquery3 = mssql_query($query3);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Hacer validacion de productos de Óptica que sean subrogados y esten el catalogo de productos subrogados
$getValidarCotizacionProductosOpticaSubrogados = $objOpticaSubrogado->getValidarCotizacionProductosOpticaSubrogados($idTicketGeneral, $idCliente);

// Pacientes subrogados en nueva interface de subrogados
//Total de PAQUETE 1 y MICA TERMINADO CR W 1
if( $objOpticaSubrogado->esPacienteSUBROGADO($idUsuarioWeb) && $getValidarCotizacionProductosOpticaSubrogados):	
	$totalOpticaPACIENTESUBROGADO = $objOpticaSubrogado->obtieneTotalTicketsOpticaPACIENTESUBROGADO($idUsuarioWeb);
	$i_cantidadPaquetesSubrogadosOptica = $getDataClienteSubrogado->i_cantidad;

	if($totalOpticaPACIENTESUBROGADO < $i_cantidadPaquetesSubrogadosOptica){		
		//Actualiza el id_Cliente en CotizacionOptica
		$query4 = "UPDATE tbl_CotizacionOptica SET id_Cliente = '".$idCliente."' WHERE id_cotizacionOptica = '".$idCotizacionOptica."'";
		$rquery4 = mssql_query($query4);		
	}
endif;

//Realiza Actualización
$query1 = "
UPDATE
	tbl_TicketGeneral
SET
	i_ImpresoPreticket = '1',
	id_Impreso = '1',
	id_Concepto = '8'
WHERE
	id_TicketGeneral = '".$idTicketGeneral."'
";

if($rquery1 = mssql_query($query1))
{
	$respuesta = array(
				'error' => '0',
				'mensaje' => 'Correcto!!!');
	echo json_encode($respuesta);
}
else{
	$respuesta = array(
				'error' => '1',
				'mensaje' => 'Error al ejecutar la consulta');
	echo json_encode($respuesta);

}
?>