<?   header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
session_start();


$idTraspaso = $_GET['idTraspaso'];
$idUsuarioWeb =$_GET['idUsuarioWeb'];

//$idFacturaTmp = $_SESSION['id_FacturaTmp'];
 
 $query = "exec sp_updTraspasoProductoSucursal ".$idTraspaso.",".$idUsuarioWeb;

$rquery = mssql_query($query);  
$rowCiTas = mssql_fetch_array($rquery);
 
if ($rowCiTas['error']==0) 
	$_SESSION['id_FacturaTmp'] = $rowCiTas['idNew'];
  
 mssql_close(); 
 
   header( 'Location: mistraspasos.php' ) ;
 
 ?>
	 
 
