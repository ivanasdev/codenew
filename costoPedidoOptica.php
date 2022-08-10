<?php 
require ("../../dbConexion.php");

$idEventoConcepto = $_GET['idEventoConcepto'];
	
	$query = "SELECT ta2.st_Nombre, ta1.Cantidad, ta2.costoPaquete FROM
	(
		SELECT t2.id_ServicioOptica as id_PaqueteOptica, SUM(nt_Cantidad) as Cantidad
		FROM tbl_CotizacionOptica t1
		INNER JOIN tbl_CotizacionOpticaDetalle t2 ON t1.id_CotizacionOptica = t2.id_CotizacionOptica
		INNER JOIN tbl_pedidosOpticaProveedor t3 ON t1.id_EventoVenta = t3.id_EventoConcepto
		WHERE t3.id_EventoConcepto = '".$idEventoConcepto."'
		GROUP BY t2.id_ServicioOptica
	) ta1
	INNER JOIN
	(
		SELECT t1.id_PaqueteOptica, SUM(i_CostoUnitario) as costoPaquete, t1.st_Nombre
		FROM tbl_PaqueteOptica t1
		INNER JOIN tbl_PaqueteOpticaServicios t2 ON t1.id_PaqueteOptica = t2.id_PaqueteOptica
		INNER JOIN cat_ServicioOptica t3 ON t2.id_ServicioOptica = t3.id_ServicioOptica
		WHERE t1.i_Activo = '1'
		GROUP BY t1.id_PaqueteOptica, t1.st_Nombre
	) ta2 ON ta1.id_PaqueteOptica = ta2.id_PaqueteOptica
	ORDER BY ta2.st_Nombre";
	
	
 $rquery = mssql_query($query);

$listado = '<table style="font-size:10; color:#333;">';

while($row = mssql_fetch_array($rquery)){
	
$cantidad = $row['Cantidad'];
$nombre = $row['st_Nombre'];
$costoPaquete = $row['costoPaquete'];
$listado .= '<tr><td>- '.$cantidad.' '.$nombre.' ($ '.number_format($costoPaquete,2).')</td></tr>';


}
$listado .= '</table>';

echo $listado;

mssql_close(); ?>
