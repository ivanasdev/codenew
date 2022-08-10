<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
session_start();

$token = $_POST['token'];
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idCotizacionDetalle=$_GET['iddetalle'];
$idCotizacion = (isset($_GET['idCotizacion']))? $_GET['idCotizacion'] : 0;



if($idCotizacion == 0):

	$query0 = "SELECT id_CotizacionOptica FROM tbl_CotizacionOpticaDetalle WHERE id_CotizacionOpticaDetalle = '".$idCotizacionDetalle."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$idCotizacion = $arrayQuery0["id_CotizacionOptica"];


endif;

	///////////////////////////////////////////////////////////
	///Verifica si el ticket ya fue vendido
	$queryVendido = "SELECT COUNT(*) as iConteoVenta FROM tbl_CotizacionOptica 
	WHERE id_cotizacionOptica = '".$idCotizacion."' AND id_Status = '3'"; 
	$rqueryVendido = mssql_query($queryVendido);
	$arrayVendido = mssql_fetch_array($rqueryVendido);
	if($arrayVendido["iConteoVenta"] > 0){
		echo "Parametros Incorrectos! La Venta ya ha sido Procesada. Vuelva a iniciar la sesi&oacute;n!";
		exit;
	}
	///////////////////////////////////////////////////////////	


$queryValida = "SELECT id_Impreso FROM tbl_TicketGeneral WHERE id_TicketGeneral = '".$_SESSION["id_TicketGeneral"]."' AND id_Impreso = 1";
$rqueryValida = mssql_query($queryValida);
if(mssql_num_rows($rqueryValida)){
?>
	<script language="javascript" type="text/javascript">
		alert("[ERROR] El ticket de este presupuesto ya ha sido impreso, vuelve a buscar el cliente!!");
		window.opener.location.href = "content.php";
		window.close();
    </script>
<?
	exit;
}

$queryreceta = "DELETE FROM tbl_CotizacionOpticaDetalle where  id_CotizacionOpticaDetalle  =".$idCotizacionDetalle;


$rqueryreceta = mssql_query($queryreceta);
?><script> 
//window.open("detallepacienteOpticaDo.php?i_Consultorio=0&idusuarioweb=<?=$id_UsuarioWeb?>", "content")

alert('el servicio  fue eliminado con exito ')
window.opener.location.reload();

window.close();
</script>