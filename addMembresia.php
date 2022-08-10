<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuarioweb"];

 $queryselect =  "SELECT    * 
FROM        tbl_UsuariosWeb
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rqueryselect =  mssql_query($queryselect);
$rowdata= mssql_fetch_array($rqueryselect);



$queryenfermedad =  "SELECT * FROM cat_Enfermedades";
$rqueryenfermedad =  mssql_query($queryenfermedad);

$selecc="<select name='st_enfermedad' >
                        <option value='0'>- Seleccionar -</option>";

while ($rowdataenf = mssql_fetch_array($rqueryenfermedad)) {

$selecc.="<option value=".$rowdataenf ['id_Enfermedad'].">".$rowdataenf ['st_Enfermedad'];

}

$selecc .=" </select>";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts.js"></script>
<script type="text/javascript" src="expansor.js"></script>

<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }

.Estilo16 {color: #996633}
.Estilo17 {color: #0066FF}


 .cnt{
      width:850px;
      background-color:#DDAADD;
      margin:0px;
      padding:15px;
      font-weight:bold
    }
    .trans{
	background-color:#E9E9E9;
	
	position:relative;

	top:100px;
	left:68px;
	padding:65px;

	width:852px;
	height: 445px;
    }
-->
</style>


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
       element.innerHTML = 'Cargando...<!--<img src="Imagenes/loading.gif" />-->';
       peticion.open("GET", fragment_url);
       peticion.onreadystatechange = function() {
       if (peticion.readyState == 4) {
       element.innerHTML = peticion.responseText;
           }
       }
      peticion.send(null);
   }
   </script>
   <script language="Javascript">

 function filtro (input) {
                        s = input.value;
                        filteredValues = "1234567890";
                        var i;
                        var returnString = "";
                        for (i = 0; i < s.length; i++) {
                        var c = s.charAt(i);
                        if (filteredValues.indexOf(c) == -1) returnString += c;
                        }
                        input.value = returnString;
                    }

                    // VERIFICA : Mandamos un mensaje de error y el focus
                    function fixElement(element, message) {
                    alert(message);
                    element.focus();
                    }
                      function RevisaFormas(forma) {
                      var error = 0;



					if((forma.nombre.value == '') && (error == 0)) {
  						fixElement(forma.nombre, "Falta nombre.");
  						error = 1;
					}


					if((forma.apellidopaterno.value == '') && (error == 0)) {
  						fixElement(forma.apellidopaterno, "Falta apellido paterno.");
  						error = 1;
					}
	
					
					if((forma.telefono2.value == '') && (error == 0)) {
                        fixElement(forma.telefono2, "Telefono Fijo");
                        error = 1;
                    }	
				
					if((forma.telefono.value == '') && (error == 0)) {
                        fixElement(forma.telefono, "Telefono Celular");
                        error = 1;
                    }	
							
				if((forma.fechacita.value == '') && (error == 0)) {
                        fixElement(forma.fechacita, "Falta fecha de nacimiento");
                        error = 1;
                    }	
						
				
				var s = "no";
           
                for ( var i = 0; i < forma.radiobutton.length; i++ ) {
				  
                if ( forma.radiobutton[i].checked ) {
                    s= "si";
              
                 break;
                         }
               }
                if ( s == "no" && (error == 0) ){
				 error = 1;
               window.alert("Seleccione genero del miembro" ) ;
                 }
 
 
 
 	
 
             if((forma.edocivil.value == '0') && (error == 0)) {
                        fixElement(forma.edocivil, "Seleccione un estado Civil");
                        error = 1;
                    }	
						
						 if((forma.estado464.value == '0') && (error == 0)) {
                        fixElement(forma.estado464	, "Seleccione una Localidad de donde es procedente");
                        error = 1;
                    }	
							 if((forma.idmunicipios.value == '0') && (error == 0)) {
                        fixElement(forma.idmunicipios	, "Seleccione un municipio");
                        error = 1;
                    }	
						
						
						if((forma.email.value == '') && (error == 0)) {
                        fixElement(forma.email, "Ingrese Correo electronico del registrado");
                        error = 1;
                    }	
					
					
					
					var sa = "no";
           
                for ( var is = 0; is < forma.tipocosto.length; is++ ) {
				  
                if ( forma.tipocosto[is].checked ) {
                    sa= "si";
              
                 break;
                         }
               }
                if ( sa == "no" && (error == 0) ){
				 error = 1;
               window.alert("Seleccione el tipo de memebresia" ) ;
                 }
 
 
  if((forma.st_enfermedad.value == '0') && (error == 0)) {
                        fixElement(forma.st_enfermedad, "Seleccione una enfermedad por la cual ingreso a nuestras clinicas");
                        error = 1;
                    }	
 
							 var nelemni3="tipop"
	   var lbt3 =  document.getElementById(nelemni3);
       var lsp3=lbt3.value
	   
	  
	   if(lsp3==2){
	   
	   var nelemni4="autorizabanco"
	   var lbt4 =  document.getElementById(nelemni4);
       var lsp4=lbt4.value
	   
	   if((lsp4 == '') && (error == 0)) {
                      fixElement(forma.autorizabanco, "Campo de Autorización Vacio");
                        error = 1;
                    }	
	   
	   }
	   

							
					 if (error == 1) {
                       //return false;
                     }else{
                       if(confirm('Desea continuar con la transacción')) {
                            document.Guion.submit();
                       }else{
                          // return false;
                       }
                     }

                 }
				 
			
                 </script>
</head>


<body style="background-color:transparent ">
<div align="center" class="trans" style="z-index:1;filter:alpha(opacity=100);float:left;-moz-opacity:.80;opacity:.60"> 

<form  name="Guion" method="post" action="domembresia.php" onSubmit="return RevisaFormas(this);">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="67%"><div align="center" class="telefonosOutbound">
            <div align="left"> Membresias<br />
              <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']?>
			  <?php
			if(!$rowdata['st_Documento']||($rowdata['st_Documento']==' ') ||($rowdata['st_Documento']=='null') ||($rowdata['st_Documento']=='') ){
			
		       echo "(Sin Membresia)";
		        }
		     ?>
            </div>
          </div></td>
          <td width="33%">&nbsp;</td>
        </tr>
      </table></td>
    <td width="3%" background="images/headofice.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%"><div align="center" class="telefonosOutbound">
              <div align="left"></div>
          </div></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>

<div align="center">
    <div class="wrapper">
        
            <div class="DIVleft"> 
              <!-- LEFT/IZQUIERDA -->
              <div class="DIVmod_header_border"><img src="images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text">Datos Generales </div>
              </div>
              <div class="DIVmod_body"> 
                <div class="DIVpadding"> 
                  <div id="div3"><span class="Estilo7"> 
                    <input name="idusuarioweb" type="hidden" id="idusuarioweb" value="<?=$rowdata['id_UsuarioWeb']?>" />
                    <input name="idevento" type="hidden" id="idevento" value="<?=$idevento?>" />
                    <br />
                    <input  name="nombre"  class="styleP" id="nombre" style="WIDTH: 150px;" value="<?=$rowdata['st_Nombre']?>" />
                    / Nombre <br />
                    <input  name="apellidopaterno"  class="styleP" id="apellidopaterno" style="WIDTH: 150px;" value="<?=$rowdata['st_ApellidoPaterno']?>" />
                    / Apellido paterno <br />
                    <input  name="apellidomaterno"  class="styleP" id="apellidomaterno" style="WIDTH: 150px;" value="<?=$rowdata['st_ApellidoMaterno']?>" />
                    / Apellido materno <br />
                    <span class="DIVopt"> 
                    <input  name="direccion"  class="styleP" id="direccion" style="WIDTH: 150px;" value="<?=$rowdata['st_Direccion']?>" />
                    </span> / Direccion </span><br />
                    <!--Delegación , select"
                  <span class="DIVopt">
                  <input  name="email"  class="styleP" id="email" style="WIDTH: 150px;" value="<?=$rowdata['st_Email']?>" />
                  </span>/ <span class="Estilo7">Email</span> <span class="DIVopt"><br />-->
                    <? 
				  
		$queryfijo =		  "SELECT     id_UsuarioWebTelefono, id_UsuarioWeb, claveLADA, st_Telefono, id_TipoTelefono, extension
FROM         tbl_UsuariosWebTelefonos
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."') AND (id_TipoTelefono = 1)";
		$rqueryfijo = mssql_query($queryfijo);
		$rownum = mssql_num_rows($rqueryfijo);
		$rowdatafijo = mssql_fetch_array($rqueryfijo);
		if ($rownum >0 ) $valuefijo = $rowdatafijo['st_Telefono'];
		else  $valuefijo = "";
				  
				  
				  
		$queryfijo2 =		  "SELECT     id_UsuarioWebTelefono, id_UsuarioWeb, claveLADA, st_Telefono, id_TipoTelefono, extension
FROM         tbl_UsuariosWebTelefonos
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."') AND (id_TipoTelefono = 3)";
		$rqueryfijo2 = mssql_query($queryfijo2);
		$rownum2 = mssql_num_rows($rqueryfijo2);
		$rowdatafijo2 = mssql_fetch_array($rqueryfijo2);
		if ($rownum >0 ) $valuecel = $rowdatafijo2['st_Telefono'];
		else $valuecel = "";
				  
				  
				  ?>
                    <input name="telefono2" type="text" class="styleP" id="telefono2"  style="WIDTH: 150px;" value="<?=$valuefijo?>" /></span> 
                    / <span class="Estilo7">Telefono Fijo </span><span class="DIVopt"> 
                    <input name="telefono" type="text" class="styleP" id="telefono"  style="WIDTH: 150px;" value="<?=$valuecel?>" />
                    </span>/ <span class="Estilo7">Telefono celular 
                  <BR> <BR><input name="fechacita" type="text" id="fechacita" value="" readonly="readonly"  size="13" />
                        <input name="button" type="button" onclick="displayCalendar(document.Guion.fechacita,'yyyy/mm/dd',this)" value="Cal" /> Fecha de Nacimiento<br />  <input type="radio" name="radiobutton" value="1" />
                    Hombre 
                    <input type="radio" name="radiobutton" value="2" />
                    Mujer / Genero<br />
                    <select name="edocivil">
                      <option value="1"><span class="Estilo7">Soltero(a)</span></option>
                      <option value="2"><span class="Estilo7">Casado(a)</span></option>
                      <option value="3"><span class="Estilo7">Viudo(a)</span></option>
                    </select>
                    / Estado Civil<br />
                    <br />
                    <select name="estado464" onchange="javascript:changeAjax('ajaxcall/SubEstados.php?IdPregunta=464', 'Estados', 'Div_SubEstados');" id="Estados"  >
                      <option value="0"><span class="Estilo7">- Seleccionar -</span></option>
                      <option value="1" ><span class="Estilo7">AGUASCALIENTES</span></option>
                      <option value="2" ><span class="Estilo7">BAJA CALIFORNIA</span></option>
                      <option value="3" ><span class="Estilo7">BAJA CALIFORNIA 
                      SUR</span></option>
                      <option value="4" ><span class="Estilo7">CAMPECHE</span></option>
                      <option value="5" ><span class="Estilo7">COAHUILA</span></option>
                      <option value="6" ><span class="Estilo7">COLIMA</span></option>
                      <option value="7" ><span class="Estilo7">CHIAPAS</span></option>
                      <option value="8" ><span class="Estilo7">CHIHUAHUA</span></option>
                      <option value="9" ><span class="Estilo7">DISTRITO FEDERAL</span></option>
                      <option value="10" ><span class="Estilo7">DURANGO</span></option>
                      <option value="11" ><span class="Estilo7">GUANAJUATO</span></option>
                      <option value="12" ><span class="Estilo7">GUERRERO</span></option>
                      <option value="13" ><span class="Estilo7">HIDALGO</span></option>
                      <option value="14" ><span class="Estilo7">JALISCO</span></option>
                      <option value="15" ><span class="Estilo7">MEXICO</span></option>
                      <option value="16" ><span class="Estilo7">MICHOACAN</span></option>
                      <option value="17" ><span class="Estilo7">MORELOS</span></option>
                      <option value="18" ><span class="Estilo7">NAYARIT</span></option>
                      <option value="19" ><span class="Estilo7">NUEVO LEON</span></option>
                      <option value="20" ><span class="Estilo7">OAXACA</span></option>
                      <option value="21" ><span class="Estilo7">PUEBLA</span></option>
                      <option value="22" ><span class="Estilo7">QUERETARO</span></option>
                      <option value="23" ><span class="Estilo7">QUINTANA ROO</span></option>
                      <option value="24" ><span class="Estilo7">SAN LUIS POTOSI</span></option>
                      <option value="25" ><span class="Estilo7">SINALOA</span></option>
                      <option value="26" ><span class="Estilo7">SONORA</span></option>
                      <option value="27" ><span class="Estilo7">TABASCO</span></option>
                      <option value="28" ><span class="Estilo7">TAMAULIPAS</span></option>
                      <option value="29" ><span class="Estilo7">TLAXCALA</span></option>
                      <option value="30" ><span class="Estilo7">VERACRUZ</span></option>
                      <option value="31" ><span class="Estilo7">YUCATAN</span></option>
                      <option value="32" ><span class="Estilo7">ZACATECAS</span></option>
                    </select>
                    Estado <br />
                    <br />
                    </span>
                    <div id="Div_SubEstados"><span class="Estilo7"><font color="#000000" face="Verdana" size="1"> 
                      Esperando la selecci&oacute;n del Estado... </font></span></div>
                    <br />
                  </div>
                </div>
              </div>
              <div class="DIVmod_footer_border"><img src="images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>
            </div>
        <div class="DIVcenter">
            <!-- CENTER/MEDIO -->
            
            
            <div class="DIVmod_header_border"><img src="images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">&nbsp;</div>
            </div>
            <div class="DIVmod">
            	<div class="DIVpadding"><span class="Estilo7"> <br />
				<input  name="email"  class="styleP" id="email" style="WIDTH: 150px;" value="<?=$rowdata['st_Email']?>" />
                  </span>/ <span class="Estilo7">Email</span> <span class="DIVopt"><br />
				
				 <input  name="rfc"  class="styleP" id="rfc" style="WIDTH: 150px;" value="<?=$rowdata['st_RFC']?>" />
           	      / RFC <br />
                  <input name="tipocosto" type="radio" value="1" checked="checked" />
                  Normal
                  <input type="radio" name="tipocosto" value="2" />
                  INSEN
				  
				   <br /><br />
                  <?=$selecc?>
                  Enfermedad Principal <span class="Estilo7"><HR>INFORMACION DE PAGO<br />
                  Tipo de pago 
                  <select name="tipop" id="tipop" onchange="javascript:changeAjax('ajaxtipopagomemebresia.php?total=<?=($tot*$iva)+$tot?>&id_UsuarioWeb=<?=$id_UsuarioWeb?>', 'tipop', 'Div_SubEstados_3');">
                    <option value="1" selected="selected">Efectivo</option>
                    <option value="2">Tarjeta Credito/Debito</option>
                    <option value="3">Dinero electronico</option>
                  </select>
                  
                  <br />
                  Comentarios adicionales<br />
                  <textarea name="comentarios" cols="" rows="" id="comentarios"></textarea>
               <div id="Div_SubEstados_3" ><input type="button" name="Submit" value="Procesar compra"  onclick="javascript:document.Guion.onsubmit();" />
                    <input name="concepto" type="hidden" id="concepto" value="2" />
                  </div>   <hr />
                  </span><br />
                    <br />
						
						
						 </div>
           	  </div>
            	<div class="DIVmod_footer" >
            	  <div class="DIVmod_footer_text">
            	    <div align="right"><a  href="#third" onclick="expansor('first', 'expansor1');"></a>
            	     
            	    </div>
            	  </div>
                    <div id="expansor1">
                    <div style="display:none;" id="first" class="DIVmod_footer_hide">
                      La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita.                    </div>  
                    </div>              
                </div>            
            </div>
         </div>
       </div>
</div>
</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table></form>
</div>
</body>
</html>
