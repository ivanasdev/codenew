<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");
$operador= $idoperador;
$id_UsuarioWeb = $_POST['id_UsuarioWeb'];



$queryinsertcita = "INSERT   
INTO            tbl_ConsultaNotasMedicas(id_UsuarioWeb, id_Evento, st_Comentarios, id_Medico, i_dental)
VALUES    
('".$_POST['idusuarioweb']."','".$_POST['idevento']."','".$_POST['remision']."','".$id_Medico."',1)";

$rqueryinsertcita  = mssql_query($queryinsertcita);
//exit();
?>
<?php mssql_close(); ?>
<script>
alert('haz completado la nota medica  con exito ')
window.opener.location.reload();
window.close();


</script>