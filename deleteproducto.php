<?   header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$token = $_POST['token'];
$id_UsuarioWeb = $_POST["idusuarioweb"];
$idevento=$_POST['idevento'];
$idrecetaproducto=$_GET['idrecetaproducto'];

 $queryreceta = "delete  tbl_RecetaProductosUsuarioWeb where  id_RecetaProductosUsuarioWeb  =".$idrecetaproducto;

$rqueryreceta = mssql_query($queryreceta);
?><script>
alert('el producto fue eliminado con exito ')
window.opener.location.reload();

window.close();
</script>