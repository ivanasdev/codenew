<?php
$ruta2index = "../../../../";
require($ruta2index.'dbConexion.php');

include($ruta2index."bionline/securitylayer/clases/class.Catalogos.php");
$objCatalogos = new Catalogos();


session_start();
$idSucursal = $_SESSION['id_Sucursal'];

$whereSucursal = "";
if(in_array($idSucursal,array(116,143))){
	$whereSucursal .= " AND t1.id_Sucursal = '".$idSucursal."'";
}else{
	//$whereSucursal .= " AND t1.id_Sucursal = '0'";
	$whereSucursal .= " AND (t1.st_Zona LIKE '".$idSucursal.",%' OR t1.st_Zona LIKE '%,".$idSucursal.",%')";
}
/*$_GET["id_Sucursal"] = 0;
if( !isset($_GET["id_Sucursal"]) ){	
	echo 'Parametros incorrectos vuelve a realizar la busqueda!!';
	exit;	
}else{
	$idSucursal = $_GET["id_Sucursal"];
}*/
$query0 = "SELECT 
	t1.id_Insumo,
	UPPER(t1.st_Nombre) as st_Nombre, 
	t2.st_Nombre as areaInsumos,
	t1.i_Activo 
FROM cat_CEDInsumos t1
INNER JOIN cat_AreaInsumos t2 ON t1.id_AreaInsumos = t2.id_AreaInsumos
WHERE 1=1 ".$whereSucursal;

$rquery0 = mssql_query($query0);

$listado = "";
$total = 0;
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 
	$idInsumo = $arrayQuery0['id_Insumo'];
	$stNombre = $objCatalogos->mostrar($arrayQuery0['st_Nombre']);
	$areaInsumos = $objCatalogos->mostrar($arrayQuery0['areaInsumos']);
	$iActivo = ($arrayQuery0['i_Activo'] == 1)? 'SI' : 'NO';
						
	$listado .= '
	<tr>
	<td align="center">'.$idInsumo.'</td>
	<td>'.$stNombre.'</td>
	<td align="center">'.$areaInsumos.'</td>
	<td><center>'.$iActivo.'</center></td>
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

<!--<table id="example" class="stripe row-border order-column" cellspacing="0" width="100%">-->
<table cellpadding="0" cellspacing="0" border="0" class="cell-border hover" id="example">
    <thead>
        <tr class="info">   
        	<th>ID</th>                            
            <th>Nombre</th>
            <th>Area de Insumo</th>
            <th>Activo</th>                                                               
        </tr>                           
    </thead>
    <tbody> 
   <?=$listado?>
    </tbody>
    <tfoot>
		 <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Area</th>
            <th>Columna</th>
        </tr>
    </tfoot>	
</table>
</div>

</body>
</html>