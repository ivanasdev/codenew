<?php
require("global.php");
$responsable = $operador;
$nuevos =  countreportes($responsable,0);
$listaresumereportnews =  listaresumereport($top,0,$responsable);
$listaresumerecriticos  =  listaresumerecriticos($top,$timeconfig,$responsable,1);
$countresumerecriticos  =  listaresumerecriticos($top,$timeconfig,$responsable,0);
$listaresumelist = listaresume($top,$responsable,1);
$listaresumecount = listaresume($top,$responsable,0);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" ><div align="center" class="telefonosOutbound">
      <div align="left">PACIENTES </div>
    </div></td>
    <td width="3%" >&nbsp;</td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%">&nbsp;</td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>

<div align="center">
    <div class="wrapper">
      <div class="DIVleft">
            <!-- CENTER/MEDIO -->
             <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Busqueda</div>
            </div>
            <div class="DIVmod">
           	  <div class="DIVpadding"></div>
            	<div class="DIVmod_title">Pacientes</div>
            	<form id="form1" name="form1" method="post" action="searchuser.php">
                  <input name="searchtoken" type="text" id="searchtoken" size="40" />
                  <input type="submit" name="Submit2" value="Enviar" />
                  <input name="idtipo2" type="hidden" id="idtipo2" value="7" />
                </form>
            	<div class="DIVmod_footer" >
                	<div class="DIVmod_footer_img"><a  href="#third" onclick="expansor('first', 'expansor1');"><img src="../cac/images/icons/arrow_right.png" name="imgfirst" alt="arrow" /></a></div>
                    <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"><span class="staa">Mas</span></a></div>
                    <div id="expansor1">
                    <div style="display:none;" id="first" class="DIVmod_footer_hide">
                      La busqueda  puede ser por  nombre de  paciente , apellidos ,email   ,por  numero de cedula .                    </div>  
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
</table>
</div>
</body>
</html>
<?php mssql_close(); ?>