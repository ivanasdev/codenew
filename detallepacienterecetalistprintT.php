<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php"); 
$ideventocita  = $_GET["ideventocita"];
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$idreceta =$_GET['idreceta'];
$varcontrol=$_GET['varcontrol'];

 $queryselect =  "SELECT    * 
FROM        tbl_UsuariosWeb
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rqueryselect =  mssql_query($queryselect);
$rowdata= mssql_fetch_array($rqueryselect);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>

<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
window.open(pagina,"",opciones);
}
</script>
<script type="text/javascript" src="../cac/scripts.js"></script>
<script type="text/javascript" src="../cac/expansor.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle reporte</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo8 {
	color: #FFFFFF;
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

<script type="text/javascript">
function Impresion()
{
if (window.print)
{
window.print();
window.close();
}
else
{
alert("Este navegador no soporta esta opción.");
window.close();
}
}
</script>

</head>
<body onload="javascript:Impresion();"> 
<BR><BR>
<table width="453" height="546" border="0">
  <tr>
    <td height="28" colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td width="6" height="22">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td width="223" class="Estilo21"><?=dateadd(date('m/d/Y h:i:s'),0,0,0,0,$mindiferencia,0)?></td>
  </tr>
  <tr>
    <td height="21" valign="top"><p>&nbsp;</p>      </td>
    <td height="21" colspan="3" valign="top">  <?=$rowdata['st_Nombre']?>      <?=$rowdata['st_ApellidoPaterno']?></td>
  </tr>
  <tr>
    <td height="419">&nbsp;</td>
    <td width="4" height="419" valign="top">&nbsp;</td>
    <td height="419" colspan="2" valign="top"><span class="Estilo9"></span> <span class="Estilo23"><br>
      <span class="Estilo19"><strong><font size="-3" face="Verdana, Arial, Helvetica, sans-serif">FORMULA 
      DE TERAPIAS </font></strong> <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"> 
      <? 

	  $query = "SELECT     tbl_RecetasTerapiaUsuarioWeb.id_RecetaTerapiaUsuarioWeb, tbl_RecetasTerapiaUsuarioWeb.id_EventoConsulta, 
                      tbl_RecetasTerapiaUsuarioWeb.id_UsuarioWeb, tbl_RecetasTerapiaUsuarioWeb.id_Terapia, tbl_RecetasTerapiaUsuarioWeb.id_EventoTerapia, 
                      tbl_RecetasTerapiaUsuarioWeb.dt_FechaRegistro, tbl_RecetasTerapiaUsuarioWeb.id_Medico, tbl_RecetasTerapiaUsuarioWeb.id_Sucursal, 
                      tbl_RecetasTerapiaUsuarioWeb.id_StatusTerapia, cat_Terapias.st_Terapia, cat_Terapias.i_Precio, tbl_RecetasTerapiaUsuarioWeb.i_Cantidad, 
                      tbl_RecetasTerapiaUsuarioWeb.st_Comentarios
FROM         tbl_RecetasTerapiaUsuarioWeb INNER JOIN
                      cat_Terapias ON tbl_RecetasTerapiaUsuarioWeb.id_Terapia = cat_Terapias.id_Terapia
WHERE     (tbl_RecetasTerapiaUsuarioWeb.id_EventoConsulta =  '".$ideventocita."')";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
      <br />
      <br />
      <img src="../images/iPassed.png" width="12" height="12" /> <a href="detalleprodcutoterapia.php?idusuarioweb=<?=$rowCiTas['id_Terapia']?>"> 
      <?=$rowCiTas['i_Cantidad']?>
      / 
      <?=$rowCiTas['st_Terapia']?>
      </a> <br />
      Indicaciones 
      <?=$rowCiTas['st_Comentarios']?>
      <?
 } 
?>
      <br />
      </font></span></span> <link href="../styles/style.css" rel="stylesheet" type="text/css"> 
      <link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" /> 
      <link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" /> 
      <link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" /> 
      <link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" /> 
      <link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" /> 
      <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"> 
      <font size="-3" face="Verdana, Arial, Helvetica, sans-serif"><span class="Estilo24"> </LINK> 
      <style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
              </style>
      <br />
      <br>
      <br>
      <?

 $querycita ="Select top 1 datediff(mi,getdate(),dt_FechaCita) as time ,* from tbl_EvCitasUsuariosWeb where id_UsuarioWeb =".$id_UsuarioWeb."  and id_TipoCita > 1 and datediff(mi,getdate(),dt_FechaCita)-$mindiferencia> 0 order by  id_Evento ";
 $rquerycita = mssql_query($querycita);
 $rowdatacita = mssql_fetch_array($rquerycita)
?>
      Proxima cita<br>
      <?=$rowdatacita['dt_FechaCita']?>
      <?php mssql_close(); ?>
      </span></font><span class="Estilo24"> </span></td>
  </tr>
  <tr>
    <td height="44">&nbsp;</td>
    <td height="44" valign="top">&nbsp;</td>
    <td width="202" valign="top">&nbsp;</td>
    <td height="44" class="Estilo21"><div align="center"></div></td>
  </tr>
</table>

</body>
</html>
<?php mssql_close(); ?>