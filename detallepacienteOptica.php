<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$varcontrol=$_GET['varcontrol'];

//generar id_evento de la cotización
$querydb = "INSERT  INTO tbl_CotizacionOptica                       ( id_UsuarioWeb, st_Descripcion, id_operador)
VALUES     ('".$_GET['idusuarioweb']."','".$_GET['st_Descripcion']."','".$operador."')";
$rquerydb = mssql_query($querydb);


$querysel= "SELECT   top 1 * FROM  tbl_CotizacionOptica order by id_cotizacionOptica desc";
$rquerysel=  mssql_query($querysel);
$rowdate= mssql_fetch_array($rquerysel);

$idevento = $rowdate['id_cotizacionOptica'];
?>

<script>
location ='detallepacienteOpticaDo.php?idevento=<?=$idevento?>&idusuarioweb=<?=$id_UsuarioWeb?>';
</script>				

