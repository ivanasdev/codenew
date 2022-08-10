<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuario"];
$idevento=$_GET['idevento'];


 $queryselect =  "SELECT    * 
FROM        tbl_UsuariosWeb
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rqueryselect =  mssql_query($queryselect);
$rowdata= mssql_fetch_array($rqueryselect);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts.js"></script>
<script type="text/javascript" src="expansor.js"></script>
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
-->
 .cnt{
      /*width:850px;*/
      background-color:#DDAADD;
      margin:0px;
      padding:15px;
      font-weight:bold
    }
    .trans{
	background-color:#E9E9E9;
	color:#CC0000;
	position:relative;
	text-align:center;
	top:100px;
	left:68px;
	padding:65px;
	font-size:25px;
	font-weight:bold;
	width:852px;
	height: 405px;
    }
</style></head>

<body style="background-color:transparent "> 
<div align="center" class="trans" style="z-index:1;filter:alpha(opacity=100);float:left;-moz-opacity:.60;opacity:.60"> 


<form id="form1" name="form1" method="post" action="doactualizauser2.php">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" ><div align="center" class="telefonosOutbound">
      <div align="left"> Actualizar datos  <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']?>
	  
	   <?php
		if(!$rowdata['st_Documento']||($rowdata['st_Documento']==' ') ||($rowdata['st_Documento']=='null') ||($rowdata['st_Documento']=='')){
		echo "(Sin Membresia)";
		}else{
		echo "( ".$rowdata['st_Documento']." )";
		}
		
		?></div>
    </div></td>
    <td width="3%" >&nbsp;</td>
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
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Datos Generales </div>
            </div>
            <div class="DIVmod_body">
            	<div class="DIVpadding">
            	  <div id="div3"><span class="Estilo7">
                    <input  name="documento"  class="styleP" id="documento" style="WIDTH: 150px;" value="<?=$rowdata['st_Documento']?>" />
/ Documento 
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
  </span> /  Direccion           	      </span><br />
                  <span class="DIVopt">
                  <input  name="email"  class="styleP" id="email" style="WIDTH: 150px;" value="<?=$rowdata['st_Email']?>" />
                  </span>/ <span class="Estilo7">Email</span> <span class="DIVopt"><br />
				  
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
                  <input name="telefono2" type="text" class="styleP" id="telefono2"  style="WIDTH: 150px;" value="<?=$valuefijo?>" />
                  </span>/ <span class="Estilo7">Telefono Fijo </span><span class="DIVopt">
                  <input name="telefono" type="text" class="styleP" id="telefono"  style="WIDTH: 150px;" value="<?=$valuecel?>" />
                  </span>/ <span class="Estilo7">Telefono celular </span></div>
           	  </div>
            </div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>
          </div>
        <div class="DIVcenter">
            <!-- CENTER/MEDIO -->
            
            
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Informacion complementaria </div>
            </div>
            <div class="DIVmod">
            	<div class="DIVpadding"><span class="Estilo7">&iquest;Deseas que 
                  te enviemos informaci&oacute;n de Ciclo Natural?</span> 
                  <div id="div"><span class="Estilo7">
                    <input name="permiso" type="radio" value="1" />
            	    Si<br />
  <input name="permiso" type="radio" value="0" />
            	    No</span></div>
            	  <span class="Estilo7">Por qu&eacute; medios adicionales al e-mail quieres que nos comuniquemos contigo</span>
<div id="div2"><span class="Estilo7">
                <input name="pregunta_cat14_0" type="checkbox" id="pregunta_cat14_0" value="1" />
           	    Tel&eacute;fono<br />
  <input name="pregunta_cat14_1" type="checkbox" id="pregunta_cat14_1" value="2" />
           	    E-mail<br />
  <input name="pregunta_cat14_2" type="checkbox" id="pregunta_cat14_2" value="3" />
           	    Correo Postal<br />
  <input name="pregunta_cat14_3" type="checkbox" id="pregunta_cat14_3" value="4" />
           	    Mensaje a celular sin costo (SMS)</span><br />
              </div>
           	  </div>
            	<div class="DIVmod_footer" >
            	  <div class="DIVmod_footer_text">
            	    <div align="right"><a  href="#third" onclick="expansor('first', 'expansor1');"></a>
            	      <input type="submit" name="Submit" value="Enviar" />
            	    </div>
            	  </div>
                    <div id="expansor1">
                    <div style="display:none;" id="first" class="DIVmod_footer_hide">
                      La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita.                    </div>  
                    </div>              
                </div>            
            </div>
        <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
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
<?php mssql_close(); ?>