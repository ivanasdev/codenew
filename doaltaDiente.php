<?php 
require ("../db.php");



$id_UsuarioWeb = $_POST["idusuarioweb"];
$idCotizacion=$_POST['idcotizacion'];
$iddiente=$_POST['id_Diente'];
$idservcio=$_POST['idservicio'];
$querypharma =  "SELECT     id_ServicioDental, nt_Costo, nt_CostoNM  FROM   fernandoruiz.cat_ServicioDental";


$rquery = mssql_query($querypharma);
while($rowItems = mssql_fetch_array($rquery)){

$id_ItemProducto = $rowItems['id_ServicioDental'];

if($_POST['Producto_'.$id_ItemProducto]== $rowItems['id_ServicioDental']){
//echo "servicio ".$_POST['Producto_'.$id_ItemProducto];
//echo "<br>";
//echo "Cantidad ".$_POST['cantidad_'.$id_ItemProducto];
//echo "<br>";
//echo "comentarios ".$_POST['comentarios_'.$id_ItemProducto];
//echo "<br>";

for($i=1;$i<=$_POST['cantidad_'.$id_ItemProducto];$i++){
 $queryInserItems = "
 INSERT INTO tbl_CotizacionDentalDetalle
                      ( id_CotizacionDental, id_UsuarioWeb, id_ServicioDental, nt_Cantidad, id_diente,i_Precio,i_PrecioNoMiembro)
VALUES     ('".$idCotizacion."','".$id_UsuarioWeb."','".$_POST['Producto_'.$id_ItemProducto]."','1','".$iddiente."','".$rowItems['nt_Costo']."','".$rowItems['nt_CostoNM']."')


 
 ";


$rqueryInserItems  = mssql_query($queryInserItems);

 }

				}
				
				}
				

/*
//recorremos lugares
 $queryselect = " SELECT     id_TipoLugar, st_TipoLugar
FROM         cat_TipoLugar";
$rqueryselect = mssql_query($queryselect);
while($rowCiTas= mssql_fetch_array($rqueryselect)){
$_POST['servicio_'.$rowCiTas['id_TipoLugar']];
if($_POST['servicio_'.$rowCiTas['id_TipoLugar']] == $rowCiTas['id_TipoLugar'] ){
 $querydb = "INSERT INTO cat_TipoProducto
                      (st_NombreProducto, st_Presentacion, i_Precio,id_TipoLugar, id_CodigoSAP)
VALUES     ('".$_POST['st_Nombre']."','".$_POST['st_Presentacion']."','".$_POST['precio_'.$rowCiTas['id_TipoLugar']]."','".$_POST['servicio_'.$rowCiTas['id_TipoLugar']]."','".$_POST['sap']."')";
$rquerydb = mssql_query($querydb);


}

}
*/

//exit();
mssql_close();
?>
<script> window.open("dientes/dientes.php?id_cotizacion=<?=$idCotizacion?>&idusuarioweb=<?=$id_UsuarioWeb?>", "content")

alert("Se dio de alta el servicio");

location ='detallediente.php?id_Diente=<?=$iddiente?>&id_cotizacion=<?=$idCotizacion?>&id_UsuarioWeb=<?=$id_UsuarioWeb?>';

</script>				