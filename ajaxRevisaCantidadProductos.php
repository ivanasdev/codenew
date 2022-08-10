<?php
include('../db.php');
$idTicketGeneral = $_POST['id_TicketGeneral'];
$respuesta = array("cantidad" => 0);
$query0 = " SELECT TOP 1 id_cotizacionOptica FROM tbl_CotizacionOptica WHERE st_Key = '".$idTicketGeneral."' ";
$rquery0 = mssql_query($query0);
if(mssql_num_rows($rquery0) > 0){
	$row0 = mssql_fetch_object($rquery0);
	$idCotizacionOptica = $row0->id_cotizacionOptica;
	
	$query1 = "SELECT COUNT(id_cotizacionOptica) AS cantidad FROM tbl_CotizacionOpticaDetalle WHERE id_cotizacionOptica = ".$idCotizacionOptica." ";
	$rquery1 = mssql_query($query1);
	$row1 = mssql_fetch_object($rquery1);
	$respuesta['cantidad'] = $row1->cantidad;
}
echo json_encode($respuesta);
mssql_close();
?>