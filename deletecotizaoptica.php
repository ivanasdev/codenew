<?   header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$token = $_POST['token'];
$id_CuentaCobrarUsuario=$_GET['id_CuentaCobrarUsuario'];
$query = " DELETE
FROM         tbl_CuentasCobrarUsuario
WHERE     (id_CuentaCobrarUsuario = ".$id_CuentaCobrarUsuario.")";
$rquery = mssql_query($query);


mssql_close();
?><script>

alert('el item  fue eliminado el ticket general ')

window.opener.location.reload();


window.close();
</script>