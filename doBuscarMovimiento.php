<?php  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
session_start();

$id_Movimiento = $_GET['Id'];
$fechaIni = $_GET['fechaIni'];
$fechaFin = $_GET['fechafin'];
$idSuc = $_GET['suc'];
//$id_Traspaso = $_SESSION['id_Sucursal'];

?>
 <link rel="stylesheet" href="../styles/style_tables.css" type="text/css">
 <script type="text/javascript" src="js/jquery.min.js"></script>	
<link rel="stylesheet" href="../styles/optica.css" type="text/css"> 

<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=508, height=800, top=85, left=140";
	window.open(pagina,"",opciones);	 
}
</script>

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
       element.innerHTML = '<img src="images/loading3.gif"/>';
       peticion.open("GET", fragment_url);
       peticion.onreadystatechange = function() {
       if (peticion.readyState == 4) {
	       element.innerHTML = peticion.responseText; 
	       }
       }
      peticion.send(null); 
      
   }
</script>     
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo1 {font-size: 9px}
-->
</style>   
  
<div id="listaproductospopup">
	   <?
		if ($idSuc == "99999") $idSuc = 0;
		if ($id_Movimiento =="") $id_Movimiento = 0;
  $query= "exec sp_getTraspasoProductoSucursal ".$id_Movimiento.",'".$fechaIni."','".$fechaFin."',".$idSuc;
  $rquery= mssql_query($query);
  $numrows= mssql_num_rows($rquery);
  ?> 
		  <table id="rounded-corner" summary="">
		    <thead>
		    	<tr>
		        	<th scope="col" class="rounded-company">Sucursal Origen</th>
		            <th scope="col" class="rounded-q1">Sucursal Destino</th>
		            <th scope="col" class="rounded-q2">Usuario Traspaso</th>
		            <th scope="col" class="rounded-q3">Fecha Traspaso</th>
		            <th scope="col" class="rounded-q3">Usuario Acepta</th>
		            <th scope="col" class="rounded-q3">Fecha Acepta</th>
		            <th scope="col" class="rounded-q4">Estatus</th>
		        </tr>
		    </thead>
		        <tfoot>
		    	<tr>
		        	<td colspan="6" class="rounded-foot-left"><em></em></td>
		        	<td class="rounded-foot-right">&nbsp;</td>
		        </tr>
		    </tfoot>
		    <tbody>
		    	<?php
				while ($rowCiTas = mssql_fetch_array($rquery)) {
				?>
				 <tr>
			    	<td><?=htmlentities($rowCiTas['SucOrigen'])?></td>
	            	<td><?=htmlentities($rowCiTas['SucDestino'])?></td>
	            	<td><?=htmlentities($rowCiTas['User1'])?></td>  
	            	<td><?=$rowCiTas['dt_FechaRegistro']?></td>
	            	<td><?=htmlentities($rowCiTas['User2'])?></td>
	            	<td><?=$rowCiTas['dt_FechaAcepta']?></td>
	            	<td><?=$rowCiTas['estatus']?></td>    
		    	</tr> 
				<?php } ?>    
		    </tbody>
		</table>
</div>
         