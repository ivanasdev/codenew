 	<link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" href="../styles/optica.css" type="text/css"> 
	
<?php
	require ("../db.php");
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	session_start();
	 
	
	 //$idFacturaTmp = $_SESSION['id_FacturaTmp'];
	 $idFacturaTmp = $_SESSION['id_FacturaTmp'];
	 
	 $idTipo = $_GET['idTipo'];
 	 $idSucursal = $_GET['idSucursal'];
	 
	 
	 
	 
	 $idUsuarioWeb=$_GET['id_UsuarioWeb'];
	 $token =$_GET['Id'];
	 
	 if ($idTipo == 1){
	 	$querypharma = "exec sp_getServiciosOptica '".$token."',".$idTipo.",".$idSucursal;
	 }else{
	 	$querypharma = "exec sp_getServiciosOptica '".$token."'";	
	 }
  	 
	 
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
 <script type="text/javascript">
var peticion = false;
   var  testPasado = false;
   try {
     peticion = new XMLHttpRequest();
     } catch (trymicrosoft) {
   try {
   peticion = new ActiveXObject("Msxml2.XMLHTTP");
   } catch (othermicrosoft) {
  try {
  peticion = new ActiveXObject("Microsoft.XMLHTTP");
  } catch (failed) {
  peticion = false;
  }
  }
  }
  if (!peticion)
  alert("ERROR AL INICIALIZAR!");

     function changeAjax (url, comboAnterior, element_id) {
       var element =  document.getElementById(element_id);
       var valordepende = document.getElementById(comboAnterior)
       var x = valordepende.value
       if(url.indexOf('?') != -1) {
           var fragment_url = url+'&Id='+x;
       }else{
           var fragment_url = url+'?Id='+x;
       }
        element.innerHTML = '<img src="images/loading.gif" />';
       peticion.open("GET", fragment_url);
       peticion.onreadystatechange = function() {
       if (peticion.readyState == 4) {
       element.innerHTML = peticion.responseText;
       
           }
       }
      peticion.send(null);
      
      
   }
    
   function changeAjaxCheck (url, comboAnterior, element_id,idprod) {
     
	   var element =  document.getElementById(element_id);
       var valordepende = document.getElementById(comboAnterior)
       var x = valordepende.value

	   
	    var bc;
		var scCheck=0;
				bc='#Producto_' + idprod ;
				
				
				if($(bc).is(':checked')==false){
				 scCheck=1;
				}
				
       if(url.indexOf('?') != -1) {
           var fragment_url = url+'&Id='+x;
       }else{
           var fragment_url = url+'?Id='+x;
       }
       element.innerHTML = '<img src="images/loading.gif" />';
	   if($(bc).is(':checked')==false){
		    element.innerHTML = ''; 
		   }else{
	   
       peticion.open("GET", fragment_url);
       peticion.onreadystatechange = function() {
       if (peticion.readyState == 4) {
       element.innerHTML = peticion.responseText;
       
           }
       }
      peticion.send(null);
		   }
   }
   
   
  </script>
 
  	 
 <table width="100%"> 
      
 	<tr>
 	<td width="3%">
 	<?
		if (($idTipo == 1 && $rowCiTas['total'] > 0) || ($idTipo==0)) {
	?>	
	<a href="javascript:agrega(<?=$idFacturaTmp?>,<?=$rowCiTas['id_ServicioOptica']?>,<?=$idUsuarioWeb?>)">
<img src="images/add.jpg" border="0" height="20px" height="20px"></a>
	<? } ?>
	</td>
	<td width="97%" class="ColorText"><strong><?=htmlentities($rowCiTas['st_Descripcion'])?>/<?=$rowCiTas['st_DenominacionDi']?></strong></td>
	</tr>
</table>
 <table width="50%">
 	  
	<tr>
	<td width="4%">&nbsp;</td>
	<td width="21%" align="left">Codigo Producto:</td>
	<td width="75%" class="ColorText"><?=htmlentities($rowCiTas['st_Codigo'])?> </td>
	</tr> 
	<tr>
	<td width="4%">&nbsp;</td>
	<td width="21%"  align="left">Descripción:</td>
	<td width="75%" class="ColorText"><? if($rowCiTas['st_Descripcion']) echo htmlentities($rowCiTas['st_Descripcion']); else echo "Sin Dato"; ?></td>
	</tr>
	<?
		if ($idTipo == 1){
	?>
	<tr>
	<td width="4%">&nbsp;</td>
	<td width="21%"  align="left">Stock:</td>
	<td width="75%" class="ColorText"><?=$rowCiTas['total']?> </td>	
	</tr>
	 	<? } ?>
	<tr>
	<td width="4%">&nbsp;</td>
	<td width="21%"  align="left">Cantidad:</td>
	<td width="75%" class="ColorText"><input type="text" id="cantidad_<?=$rowCiTas['id_ServicioOptica']?>" name="cantidad_<?=$rowCiTas['id_ServicioOptica']?>" style="width: 30px" value="1"/></td>	
	</tr>

	
</table>
 
<div id="Div_SubEstados_<?=$rowCiTas['id_ServicioOptica']?>" ></div>      

<? } 
} else {  ?>
<font color="#990000"><strong>NO SE ENCONTRO EL PRODUCTO : 
<?=$token?>
<BR>
<BR>
</strong>

<a href="javascript:Abrir_ventana('altaproductoalmacen.php?id_ProductoAlmacen=<?=$rowCiTas['id_ServicioOptica']?>')"> 
<font color="#000000"><img src="../images/icAddRight.gif" border="0"> 
Dar de alta producto</font></a><strong> </strong></font> 
<? } ?>
<?php mssql_close(); ?>
<br>
