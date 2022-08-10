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
$querydent = "SELECT     id_ServicioDental, st_ServicioDental, i_status, nt_Costo, nt_CostoNM, st_Descripcion, dt_FechaRegistro, st_Duracion, st_Codigo
FROM         fernandoruiz.cat_ServicioDental
WHERE     (st_ServicioDental LIKE '%".$token."%') AND (i_status = 1)";
 $rquery = mssql_query($querydent);
 while($rowCiTas = mssql_fetch_array($rquery))  {
 ?>
<input type="checkbox" id="Producto_<?=$rowCiTas['id_ServicioDental']?>"  name="Producto_<?=$rowCiTas['id_ServicioDental']?>"  value="<?=$rowCiTas['id_ServicioDental']?>"  onclick="javascript:changeAjax('Productos.php?stock=<?=$stock?>&empresa=<?=$rowCiTas['id_ServicioDental']?>', 'Producto_<?=$rowCiTas['id_ServicioDental']?>', 'Div_SubEstados_<?=$rowCiTas['id_ServicioDental']?>');"   />
<strong> 
<?=$rowCiTas['st_ServicioDental']?>
<br>
Precio miembro
<?=number_format($rowCiTas['nt_Costo'],2)?>
<br>
Precio no miembro
<?=number_format($rowCiTas['nt_CostoNM'],2)?>
</strong> 
<div id="Div_SubEstados_<?=$rowCiTas['id_ServicioDental']?>" >..</div>      

<br>
<? }?>
<?php mssql_close(); ?>