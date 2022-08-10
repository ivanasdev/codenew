<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");


///  insertamos  los  datos  de la cita

 $queryinsertcita = "INSERT    INTO            tbl_RecetasComentarios(id_EventoCita, st_Cometarios, id_Medico)
VALUES     ('".$_POST['idevento']."','".$_POST['comentarios']."','".$id_Medico."')";
$rqueryinsertcita  = mssql_query($queryinsertcita);

?>
<?php mssql_close(); ?>
<script>
alert('Se adicionaron con exito los  comentarios ')

location='listaproductosrecetavieja.php?idevento=<?=$_POST['ideventouno']?>&ideventocita=<?=$_POST['idevento']?>'
</script>