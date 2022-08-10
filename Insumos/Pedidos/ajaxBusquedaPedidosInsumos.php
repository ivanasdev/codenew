<?php
$ruta2index = "../../../../";
require($ruta2index.'dbConexion.php');

include($ruta2index."bionline/securitylayer/clases/class.Catalogos.php");
$objCatalogos = new Catalogos();


if( !isset($_GET["fecha1"], $_GET["fecha2"], $_GET["idSucursal"], $_GET["folio"], $_GET["idAreaInsumo"]) ){	
	echo 'Parametros incorrectos vuelve a realizar la busqueda!!';
	exit;
}
else{
	$fecha1 = $_GET["fecha1"];
	$fecha2 = $_GET["fecha2"];
	$idSucursal = $_GET["idSucursal"];
	$folio = $_GET["folio"];
	$idAreaInsumo = $_GET["idAreaInsumo"];
}


/////////////////////////////// WHERE INSUMOS
session_start();
$whereAreaInsumo = "";
switch($_SESSION["id_TipoUsuario"]){

	//Dental
	case 10:
		$whereAreaInsumo = " AND t1.id_AreaInsumos IN ('4')";
		break;
		
	//Medico
	case 3:
		$whereAreaInsumo = " AND t1.id_AreaInsumos IN ('2','6','5')";
		break;
		
	//Farmacia
	case 15:
		$whereAreaInsumo = " AND t1.id_AreaInsumos IN ('5')";
		break;
		
	//Optica
	case 14:
		$whereAreaInsumo = " AND t1.id_AreaInsumos IN ('3')";
		break;

	default:
		$whereAreaInsumo = "";
		break;
		
}
////////////////////////////////////////////////////////


$where = "t1.dt_FechaRegistro BETWEEN '".$fecha1." 00:00:00.000' AND '".$fecha2." 23:59:59.900'	
AND t1.id_Sucursal = '".$idSucursal."'";

if( $idAreaInsumo != 0 ){

	$where .= " AND t1.id_AreaInsumos = '".$idAreaInsumo."'";
}

if( intval($folio) > 0 ){
	$where = "t1.id_PedidoInsumo = '".$folio."'";
}
	
	$query0 = "SELECT 
		t1.id_PedidoInsumo,
		t1.dt_FechaRegistro,
		t1.id_Status,
		t1.st_Observaciones,
		UPPER(t2.st_Nombre) as nombreSucursal,
		isnull(t3.st_Nombre,'N/A') as areaInsumos,
		t4.st_Nombre as nombreOperador 
	FROM tbl_SUCInsumoPedido t1
	LEFT JOIN cat_SucursalClinica t2 ON t1.id_Sucursal = t2.id_SucursalClinica
	LEFT JOIN cat_AreaInsumos t3 ON t1.id_AreaInsumos = t3.id_AreaInsumos
	LEFT JOIN tbl_UsuarioSistemaWeb t4 ON t1.id_Operador = t4.id_Operador
	WHERE ".$where." ".$whereAreaInsumo;
	
	
$rquery0 = mssql_query($query0);

$listado = "";
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 
	
	$idPedidoInsumo = $arrayQuery0['id_PedidoInsumo'];
	$idStatus = $arrayQuery0['id_Status'];
	$dtFechaRegistro = $objCatalogos->mostrarFecha($arrayQuery0['dt_FechaRegistro']);
	$nombreSucursal = $objCatalogos->mostrar($arrayQuery0['nombreSucursal']);
	$areaInsumos = $objCatalogos->mostrar($arrayQuery0['areaInsumos']);
	$stObservaciones = $objCatalogos->mostrar($arrayQuery0['st_Observaciones']);
	$nombreOperador = $objCatalogos->mostrar($arrayQuery0['nombreOperador']);
		
	if($idStatus == 1){
		$acciones = '<a href="pedidoInsumosPaso1.php?idPedidoInsumo='.$idPedidoInsumo.'" title="Continuar Pedido">Continuar</a>';
	}else{
		$acciones = '<a href="PDFPedidoInsumoSucursal.php?idPedidoInsumo='.$idPedidoInsumo.'" title="Ver Pedido Insumos en PDF">
		<img src="../../../cedis/images/PDF.png" border="0" width="32px">
		</a>';
	}
	
	
	$salidas = '';
	$guion = '';
	$query1 = "SELECT id_SalidaDirecta FROM tbl_CEDCabeceraSalidaDirecta WHERE id_PedidoInsumo ='".$idPedidoInsumo."'";
	$rquery1 = mssql_query($query1);
	while( $arrayQuery1 = mssql_fetch_array($rquery1) ){ 
	
		$idSalidaDirecta = $arrayQuery1['id_SalidaDirecta'];
		$salidas .= $guion.'<a href="../../../Cedis/CEDSalidaDirecta/PDFSalidaInsumoCEDIS.php?id='.$idSalidaDirecta.'" title="Descargar Reporte Salida Cedis">'.$idSalidaDirecta.'</a>';
		$guion = ' - ';	
	}
	
								
	$listado .= '
	<tr>
	<td><center>'.$idPedidoInsumo.'</center></td>
	<td><center>'.$dtFechaRegistro.'</center></td>
	<td>'.$nombreSucursal.'</td>
	<td><center>'.$areaInsumos.'</center></td>
	<td><center>'.$salidas.'</center></td>
	<td>'.$nombreOperador.'</td>
	<td>'.$stObservaciones.'</td>
	<td><center>'.$acciones.'</center></td>
	</tr>';
	
}	
	

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>



<div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" align="left">
    <thead>
        <tr>
        	<th>ID Pedido Insumo</th>
        	<th>Fecha de Registro</th>
            <th>Sucursal</th>
            <th>Area de Insumo</th>
            <th>Surtido (Salida de Cedis)</th>
            <th>Operador</th>
            <th>Observaciones</th>
            <th>Acciones</th>
        </tr>                           
    </thead>
    <tbody> 
    <?=$listado?>
    </tbody>
    <tfoot>
		<tr>
            <th><input class="search_init" type="text" name="1" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="2" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="3" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="4" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="5" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="6" placeholder=""></input></th>
            <th><input class="search_init" type="text" name="6" placeholder=""></input></th>
            <th></th>
        </tr>
    </tfoot>	
</table>
</div>


</body>
</html>