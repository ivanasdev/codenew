<?php
session_start();
require(".../db.php");
 
$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,81,"ALMACEN - Buscar Movimientos Traspasos");
///////////////////////////////////////////////////////	 
 
$idUsuarioWeb = $_SESSION['id_Operador'];
$idSucursal = $_SESSION['id_Sucursal'];
$idEvento = $_GET['id_Evento'];
$isAdmin = $_SESSION['b_Admin'];
 
$querycomplement = "";
 if ($isAdmin == 0) $querycomplement = " where id_SucursalClinica = ".$idSucursal;

 
?>
<!DOCTYPE HTML>
<html>
<head>
	
	 <link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" href="../styles/optica.css" type="text/css"> 
	<script type="text/javascript" src="js/jquery.min.js"></script>	
	<script type="text/javascript" src="js/validateNewFactura.js"></script>

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
      var fi = document.getElementById('dt_FechaIni').value;
      var ff = document.getElementById('dt_FechaFin').value;
      var suc = document.getElementById('id_Sucursal').value;
      console.log('fi: '+fi);
      console.log('ff: '+ff);
      console.log('suc: '+suc);
       if(url.indexOf('?') != -1) {
           var fragment_url = url+'&Id='+x+'&fechaIni='+fi+'&fechafin='+ff+'&suc='+suc;
       }else{
           var fragment_url = url+'?Id='+x+'&fechaIni='+fi+'&fechafin='+ff+'&suc='+suc;
       }
       console.log('url: '+fragment_url);
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
<script language="JavaScript">
window.onload = function() {
	var group
	var coordinates = ToolMan.coordinates()
	var drag = ToolMan.drag()

	var boxDrag = document.getElementById("boxDrag")
	drag.createSimpleGroup(boxDrag)

	var boxVerticalOnly = document.getElementById("boxVerticalOnly")
	group = drag.createSimpleGroup(boxVerticalOnly)
	group.verticalOnly()

	var boxHorizontalOnly = document.getElementById("boxHorizontalOnly")
	group = drag.createSimpleGroup(boxHorizontalOnly)
	group.horizontalOnly()

	var boxRegionConstraint = document.getElementById("boxRegionConstraint")
	group = drag.createSimpleGroup(boxRegionConstraint)
	var origin = coordinates.create(0, 0)
	group.addTransform(function(coordinate, dragEvent) {
		var originalTopLeftOffset = 
				dragEvent.topLeftOffset.minus(dragEvent.topLeftPosition)
		return coordinate.constrainTo(origin, originalTopLeftOffset)
	})

	var boxThreshold = document.getElementById("boxThreshold")
	group = drag.createSimpleGroup(boxThreshold)
	group.setThreshold(25)

	var boxThreshold2 = document.getElementById("boxThreshold2")
	group = drag.createSimpleGroup(boxThreshold2)
	group.setThreshold(25)
		var boxThreshold3 = document.getElementById("boxThreshold3")
	group = drag.createSimpleGroup(boxThreshold3)
	group.setThreshold(25)


	var boxHandle = document.getElementById("boxHandle")
	group = drag.createSimpleGroup(boxHandle, document.getElementById("handle"))

	var boxAbsolute = document.getElementById("boxAbsolute")
	group = drag.createSimpleGroup(boxAbsolute)
	group.verticalOnly()
	group.addTransform(function(coordinate, dragEvent) {
		var scrollOffset = coordinates.scrollOffset()
		if (coordinate.y < scrollOffset.y)
			return coordinates.create(coordinate.x, scrollOffset.y)

		var clientHeight = coordinates.clientSize().y
		var boxHeight = coordinates._size(boxAbsolute).y
		if ((coordinate.y + boxHeight) > (scrollOffset.y + clientHeight))
			return coordinates.create(coordinate.x, 
					(scrollOffset.y + clientHeight) - boxHeight)

		return coordinate
	})
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
					   <?php
					  $queryselec = "SELECT     st_Nombre, id_SucursalClinica
									 FROM         cat_SucursalClinica ".$querycomplement. " order by st_Nombre ";
						   $rqueryselec = mssql_query($queryselec);
					 while ($rowdata = mssql_fetch_array($rqueryselec)) { 
					 	?>
			            <option value="<?=$rowdata['id_SucursalClinica']?>"><?=htmlentities($rowdata['st_Nombre'])?></option>
						<?
					  }
					  ?>   
					</select>
					<div id="group">
						<div id="label">Fecha Inicial:</div>
						<div id="input">
						<input type="text" id="dt_FechaIni" name="dt_FechaIni" readonly value="" class="width:40px"/>
					 
						<input name="button" type="button" onClick="displayCalendar(document.newFact.dt_FechaIni,'yyyy/mm/dd',this)" value="Cal" />	
						</div>
					</div>
					<div id="group">
						<div id="label">Fecha Final:</div>
						<div id="input">
						<input type="text" id="dt_FechaFin" name="dt_FechaFin" readonly value="" class="width:40px"/>
					 
						<input name="button" type="button" onClick="displayCalendar(document.newFact.dt_FechaFin,'yyyy/mm/dd',this)" value="Cal" />	
						</div>
					</div>
                  <br />&nbsp;&nbsp;<img src="../images/iPassed.png" width="12" height="12" />&nbsp;Movimiento: 
                  <label>                 
                  <input name="token2" type="text" id="token2" autocomplete="off" onchange="javascript:changeAjax('doBuscarMovimiento.php', 'token2', 'datosgeneral');" />
                  <input type="button" name="Button" value="Buscar" onclick="javascript:changeAjax('doBuscarMovimiento.php', 'token2', 'datosgeneral');" />
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