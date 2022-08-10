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



$where = "";

if( intval($folio) > 0 ){
	$where .= " AND t1.id_SalidaDirecta = '".$folio."'";
}

if( $idAreaInsumo != 0 ){

	$where .= " AND t0.id_AreaInsumos = '".$idAreaInsumo."'";
}

	$query0 = "SELECT 
		t0.id_CabeceraEntrada,
		t0.dt_FechaRegistro,
		t0.id_Status,
		t0.st_Observaciones,
		ISNULL(t1.id_SalidaDirecta,'0') as id_SalidaDirecta,
		t1.i_RecibidoSucursal,
		UPPER(t2.st_Nombre) as nombreSucursal,
		isnull(t3.st_Nombre,'N/A') as areaInsumos,
		t4.st_Nombre as nombreOperador 
	FROM tbl_SUCEntradaInsumo t0
	LEFT JOIN tbl_CEDCabeceraSalidaDirecta t1 ON t0.id_SalidaDirectaCedis = t1.id_SalidaDirecta AND t1.id_Status = '2' AND t1.id_Motivo = '6'
	LEFT JOIN cat_SucursalClinica t2 ON t0.id_Sucursal = t2.id_SucursalClinica
	LEFT JOIN cat_AreaInsumos t3 ON t0.id_AreaInsumos = t3.id_AreaInsumos
	LEFT JOIN tbl_UsuarioSistemaWeb t4 ON t0.id_Operador = t4.id_Operador
	WHERE t0.dt_FechaRegistro BETWEEN '".$fecha1." 00:00:00.000' AND '".$fecha2." 23:59:59.900'
	AND t0.id_Sucursal = '".$idSucursal."' ".$where." ORDER BY t0.dt_FechaRegistro DESC";
	
$rquery0 = mssql_query($query0);

$listado = "";
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 
	
	$idSalidaDirecta = $arrayQuery0['id_SalidaDirecta'];
	$idStatus = $arrayQuery0['id_Status'];
	$idCabeceraEntrada = $arrayQuery0['id_CabeceraEntrada'];
	$dtFechaRegistroSalidaCedis = $objCatalogos->mostrarFecha($arrayQuery0['dt_FechaRegistro']);
	$nombreSucursal = $objCatalogos->mostrar($arrayQuery0['nombreSucursal']);
	$areaInsumos = $objCatalogos->mostrar($arrayQuery0['areaInsumos']);
	$stObservaciones = $objCatalogos->mostrar($arrayQuery0['st_Observaciones']);
	$nombreOperador = $objCatalogos->mostrar($arrayQuery0['nombreOperador']);
	
	
	
	if($idSalidaDirecta == 0){
		$idSalidaDirecta = '-';
	}
	
	if($idStatus == 1){
		$acciones = '<a href="entradaInsumosPaso1.php?idCabeceraEntrada='.$idCabeceraEntrada.'" title="Continuar Entrada">Continuar</a>';
	}else{
		$acciones = '<a href="PDFEntradaInsumoSucursal.php?idCabeceraEntrada='.$idCabeceraEntrada.'" title="Ver Reporte en PDF">
		<img src="../../../cedis/images/PDF.png" border="0" width="32px">
		</a>';
	}
								
	$listado .= '
	<tr>
	<td><center>'.$idCabeceraEntrada.'</center></td>
	<td><center>'.$dtFechaRegistroSalidaCedis.'</center></td>
	<td><center>'.$idSalidaDirecta.'</center></td>
	<td>'.$nombreSucursal.'</td>
	<td><center>'.$areaInsumos.'</center></td>
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
        	<th>ID Entrada</th>
        	<th>Fecha de Registro</th>
        	<th>Folio Salida Directa</th>
            <th>Sucursal</th>
            <th>Area de Insumo</th>
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
            <th><input class="search_init" type="text" name="7" placeholder=""></input></th>
            <th></th>
        </tr>
    </tfoot>	
</table>
</div>


</body>
</html>