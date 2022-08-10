<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");

$querybb ="UPDATE  tbl_UsuariosWebCac
SET              st_Nombre ='".$_POST["nombre"]."', st_ApellidoPaterno ='".$_POST["apellidopaterno"]."', st_ApellidoMaterno ='".$_POST["apellidomaterno"]."', st_Documento ='".$_POST["documento"]."',st_Direccion = '".$_POST["direccion"]."', st_Email ='".$_POST["email"]."'
where  id_UsuarioWeb = '".$_POST["idusuarioweb"]."'" ;
$rquerybb = mssql_query($querybb);



// insertamos las preferencias de contacto 

//insertamos preferencias de contacto
$querydelet    = "DELETE  FROM         tbl_UsuariosWebPreferenciasContacto where   id_UsuarioWeb ='".$_POST["idusuarioweb"]."'";
$rqueryp= mssql_query($querydelet);


for($i=0;$i<=3;$i++){
if($_POST["pregunta_cat14_".$i]==$i+1){
$valor=$i+1;
$queryp= "INSERT INTO tbl_UsuariosWebPreferenciasContacto (id_UsuarioWeb, id_PreferenciaContacto) VALUES ('".$_POST["idusuarioweb"]."','".$valor."')";
$rqueryp= mssql_query($queryp);
}}




?><?php mssql_close(); ?>
 <script>
  alert("La  informacion fue actualizada  exitosamente");
  
  </script>

<?
echo "<meta http-equiv='REFRESH' content='0; url=detallepaciente.php?idusuarioweb=".$_POST["idusuarioweb"]."&idevento=".$_POST['idevento']."'>";
		exit();



?>