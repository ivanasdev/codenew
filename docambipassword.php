<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");
$operador= $idoperador;
$id_UsuarioWeb = $_POST['id_UsuarioWeb'];

///  insertamos  los  datos  de la cita

  $queryinsertcita = "Select * from [tbl_UsuarioSistemaWeb] WHERE  id_UsuarioSistemaWeb = '".$_SESSION["id_Operador"]."'   AND  st_Password ='".$_POST['passold']."' ";
    $rqueryinsertcita = mssql_query($queryinsertcita);
	$numrows = mssql_num_rows($rqueryinsertcita);
	if($numrows > 0) {
	$control =1;
	
	$queryselect =" update tbl_UsuarioSistemaWeb
set st_Password = '".$_POST['pass']."'  WHERE  id_UsuarioSistemaWeb ='".$_SESSION["id_Operador"]."'  "; 
$queryselect = mssql_query($queryselect); 
	?>
	<script type="text/javascript">
alert('El password  fue actualizado correctamente');
location ='cambiopassword.php';
</script>
	
	<?php
	}
	else {?>
	
		<script type="text/javascript">
alert('El password  no pudo ser cambiado porque no coincide el password anterior');
location ='cambiopassword.php';
</script>
	
<?php }
	
	


?>
<?php mssql_close(); ?>
<script type="text/javascript">
function Impresion()
{
alert('Se agrego con  exito  la remision a urgencias');
window.opener.location.reload();
window.close();
}
</script>
<body onLoad="javascript:Impresion();"> </body> 