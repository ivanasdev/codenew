<style type="text/css">
<!--
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.ColorText{color:#663}
-->
</style>

<?php
	require ("../db.php");
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );

	$empresa=$_GET['empresa'];
	$token =$_GET['Id'];
  	$querypharma = "SELECT  * FROM  dbo.cat_ProductosAlmacenMaster WHERE (st_NombreComercial LIKE '%".$token ."%') AND (i_Activo = 1) OR
                      (i_Activo = 1) AND (st_Nombre LIKE '%".$token ."%') OR
                      (i_Activo = 1) AND (id_CodKeyProduct LIKE '%".$token ."%') OR
                      (i_Activo = 1) AND (st_ClaveANL LIKE '%".$token ."%') OR ( id_upc LIKE '%".$token ."%')";
	 $rquery = mssql_query($querypharma);
 	 $numrows = mssql_num_rows($rquery);
	if($numrows>0){
		while($rowCiTas = mssql_fetch_array($rquery)){
			if($_GET['empresa'] == 0)  $precio = 0;
				else $precio =$rowCiTas['i_Precio'];
					  $rowCiTas['disponibles'];
						if($rowCiTas['disponibles'] > 0)
														{ 
						 									 $stock = $rowCiTas['disponibles'];
															 $txtdis = ""; 
														 }else{ 
															 $txtdis = "disabled";
 															  $stock = 0;
															  }
 ?>
 <table width="100%"><tr><td width="3%">
<input type="checkbox" id="Producto_<?=$rowCiTas['id_ProductoAlmacen']?>"  name="Producto_<?=$rowCiTas['id_ProductoAlmacen']?>"  value="<?=$rowCiTas['id_ProductoAlmacen']?>"  onclick="javascript:changeAjaxCheck('Productos.php?stock=<?=$stock?>&empresa=<?=$rowCiTas['id_ProductoAlmacen']?>', 'Producto_<?=$rowCiTas['id_ProductoAlmacen']?>', 'Div_SubEstados_<?=$rowCiTas['id_ProductoAlmacen']?>',<?=$rowCiTas['id_ProductoAlmacen']?> );" /> 

</td><td width="97%" class="ColorText"><strong><?=$rowCiTas['st_Nombre']?>/<?=$rowCiTas['st_DenominacionDi']?></strong></td>
</tr>
</table>
 <table width="100%">
<tr>
<td width="4%">&nbsp;</td><td width="21%" align="right">Codigo Producto:</td><td width="75%" class="ColorText"><?=$rowCiTas['id_CodKeyProduct']?> </td></tr>
<tr>
<td width="4%">&nbsp;</td><td width="21%"  align="right">Clave ANL:</td><td width="75%" class="ColorText"><?=$rowCiTas['st_ClaveANL']?> </td></tr>
<tr>
<td width="4%">&nbsp;</td><td width="21%"  align="right">Descripción:</td><td width="75%" class="ColorText"><? if($rowCiTas['st_DescripcionCat']) echo $rowCiTas['st_DescripcionCat']; else echo "Sin Dato"; ?></td></tr>
<tr>
<td width="4%">&nbsp;</td><td width="21%"  align="right">Presentación:</td><td width="75%" class="ColorText"><? if($rowCiTas['st_Pres']) echo $rowCiTas['st_Pres']; else echo "Sin Dato"; ?></td></tr>
<tr>
<td width="4%">&nbsp;</td><td width="21%"  align="right">Sustancia Activa:</td><td width="75%" class="ColorText"><? if($rowCiTas['st_SA']) echo $rowCiTas['st_SA']; else echo "Sin Dato"; ?></td></tr>

</table>
<!--
<a href="javascript:Abrir_ventana('editcosteo.php?id_ProductoAlmacen=<?=$rowCiTas['id_ProductoAlmacen']?>')"> 
<img src="../images/editar.gif" width="32" height="32" border="0">Editar costeo 
manual por sucursal</a>--> 



<!--Almacen: 
<?=$rowCiTas['almacen']?>
<br>
Entradas: 
<?=$rowCiTas['entradas']?>
<br>
Salidas 
<?=$rowCiTas['salidas']?>-->
 </strong> 
<div id="Div_SubEstados_<?=$rowCiTas['id_ProductoAlmacen']?>" ></div>      

<? }} else {  ?>
<font color="#990000"><strong>NO SE ENCONTRO EL PRODUCTO : 
<?=$token?>
<BR>
<BR>
</strong>

<a href="javascript:Abrir_ventana('altaproductoalmacen.php?id_ProductoAlmacen=<?=$rowCiTas['id_ProductoAlmacen']?>')"> 
<font color="#000000"><img src="../images/icAddRight.gif" border="0"> 
Dar de alta producto</font></a><strong> </strong></font> 
<? } ?>
<?php mssql_close(); ?>
<br>
