<?php
require(".../db.php");
session_start();

$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,81,"ALMACEN - Stock Sucursal");
///////////////////////////////////////////////////////	 
 
$idUsuarioWeb = $_SESSION['id_Operador'];
//$id_Sucursal = $_SESSION['id_Sucursal'];
$idEvento = $_GET['id_Evento'];
$isAdmin = $_SESSION['b_Admin'];
  
?>
<!DOCTYPE HTML>
<html>
<head>
	
	 <link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" href="../styles/optica.css" type="text/css"> 
	<script type="text/javascript" src="js/jquery.min.js"></script>	
	<!--<script type="text/javascript" src="js/validateNewFactura.js"></script>-->

	<script language="JavaScript" type="text/javascript" src="Reportes/source/org/tool-man/core.js"></script>
	<script language="JavaScript" type="text/javascript" src="Reportes/source/org/tool-man/events.js"></script>
	<script language="JavaScript" type="text/javascript" src="Reportes/source/org/tool-man/css.js"></script>
	<script language="JavaScript" type="text/javascript" src="Reportes/source/org/tool-man/coordinates.js"></script>
	<script language="JavaScript" type="text/javascript" src="Reportes/source/org/tool-man/drag.js"></script>
	<SCRIPT type="text/javascript" src="Reportes/dhtmlgoodies_calendar.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="Reportes/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>

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
       console.log('url: '+fragment_url);
       console.log('element: '+element);
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
 
</head>
<body>
	<form name="newFact" id="newFact" action="" method="post">
		
	
	<div id="div_datosComple">
		 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">             
              <tr> 
                <td> 
                	 <div id="label">Sucursal:</div>
					<select id="id_Sucursal" name="id_Sucursal">
		                
			          <option value="99999">Selecciona</option>
			           <?php if ($isAdmin==1) { ?>
			          <option value="999999">Almacen Global</option>
					   <?php
					   }
					  $queryselec = "SELECT     st_Nombre, id_SucursalClinica
									 FROM         cat_SucursalClinica ".$querycomplement. " order by st_Nombre ";
						   $rqueryselec = mssql_query($queryselec);
					 while ($rowdata = mssql_fetch_array($rqueryselec)) { 
					 	if ($rowdata['id_SucursalClinica'] == $idsucursal) {
							if ($isAdmin==0){
								?>
									<option  value="<?=$rowdata['id_SucursalClinica']?>"><?=htmlentities($rowdata['st_Nombre'])?></option>
								<?
							}
							
						}
						if ($isAdmin==1){
							 
								?>
									<option  value="<?=$rowdata['id_SucursalClinica']?>"><?=htmlentities($rowdata['st_Nombre'])?></option>
								<?
							 
						} 
					  }
					  ?>   
					</select>
					             
                  
                  <input type="button" name="Button" value="Buscar" onclick="javascript:changeAjax('dobuscarStockSucursal.php', 'id_Sucursal', 'datosgeneral');" />
                  </label> 
                  <br> 
                  </td>                   
              </tr>             
            </table>  
	
		 <div id="datosgeneral" >
		 	
		 </div>
		<div id="lista">
			
		</div>  
		  
	</div>
	
	</form>
</body>
	
</html>