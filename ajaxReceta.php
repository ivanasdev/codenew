<style type="text/css">
<!--
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
-->
</style><br>
<? require ("../db.php");
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

$empresa=$_GET['empresa'];
$token =$_GET['Id'];
require("../globalquerys.php");
 $rquery = mssql_query($querypharma);
while($rowCiTas = mssql_fetch_array($rquery)){
if($_GET['empresa'] == 0)  $precio = 0;
else   $precio =$rowCiTas['i_Precio'];
if($rowCiTas['disponibles'] > 0)
{ $stock = $rowCiTas['disponibles'];
 $txtdis = ""; }
else  { 
 $txtdis = "disabled";
 $stock = 0;
}

 ?>
<input type="checkbox" id="Producto_<?=$rowCiTas['id_TipoProducto']?>"  name="Producto_<?=$rowCiTas['id_TipoProducto']?>"  value="<?=$rowCiTas['id_TipoProducto']?>"  onclick="javascript:changeAjax('Productos.php?stock=<?=$stock?>&empresa=<?=$rowCiTas['id_TipoProducto']?>', 'Producto_<?=$rowCiTas['id_TipoProducto']?>', 'Div_SubEstados_<?=$rowCiTas['id_TipoProducto']?>');" <?=$txtdis?>  />
<strong> 
<?=$rowCiTas['st_NombreProducto']?>
<br>
Almacen: 
<?=$stock?>
<br>
Precio miembro
<?=number_format($rowCiTas['i_Precio'],2)?>
<br>
Precio no miembro
<?=number_format($rowCiTas['i_PrecioNomiembro'],2)?>
</strong> 
<div id="Div_SubEstados_<?=$rowCiTas['id_TipoProducto']?>" ></div>      

<br>
<? }?>
<?php mssql_close(); ?>