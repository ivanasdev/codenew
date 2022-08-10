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

if(($idevento > 0)&&(varcontrol == 1)){

 $query = " UPDATE    tbl_EvCitasUsuariosWeb
SET             id_StatusCita = 3
where  id_Evento ='".$idevento."'";
$rquery = mssql_query($query);
 
$queryevento="INSERT INTO tbl_EvCitaAsistenciaUsuariosWeb (id_EventoCita,id_UsuarioWeb,id_OperadorVentanilla) VALUES ('".$idevento."','".$id_UsuarioWeb."','".$idoperador."') ";
$rqueryevento=mssql_query($queryevento);
 $queryselev="SELECT TOP 1 id_Evento FROM  tbl_EvCitaAsistenciaUsuariosWeb WHERE id_UsuarioWeb = '".$id_UsuarioWeb."' ORDER BY id_Evento DESC";
$rqueryselev=mssql_query($queryselev);
$rowselev=mssql_fetch_array($rqueryselev);
$id_Evento=$rowselev['id_Evento'];
$queryeventototal = "INSERT INTO tbl_EventosUsuariosWeb (id_UsuarioWeb, id_TipoEvento, id_Evento, id_TipoEventoOrigen, st_NombreEvento, st_IP,id_EventoOrigen) VALUES ('".$id_UsuarioWeb."', '19', '".$id_Evento."', '2', 'Cita  (Asistencia Consultorio)', '".$_SERVER['REMOTE_ADDR']."','2')";
$rqueryeventototal = mssql_query($queryeventototal);

}


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

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" background="../images/headofice.jpg"><div align="center" class="telefonosOutbound">
      <div align="left"> FORMULACI&Oacute;N MEDICA  <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$rowdata['st_Documento'].")"?></div>
    </div></td>
    <td width="3%" background="../images/headofice.jpg"><img src="../images/headofice.jpg" width="41" height="90" /></td>
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
            <!-- CENTER/MEDIO -->
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text"><?=$rowdata['st_Nombre']?> / <?=date('d/m/Y H : i')?></div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                <div align="right"><img src="../images/icGuiones.gif" width="32" height="32" /></div>
                <a href="detallepacientepatologia.php?idusuarioweb=<?=$id_UsuarioWeb?>&amp;idevento=<?=$idevento?>">
                	
<br />
                </a><br />
              </div>
              <br />
              <strong>FORMULA DE TERAPIAS  </strong>
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
           <?=$rowCiTas['i_Cantidad']?> /    <?=$rowCiTas['st_Terapia']?>
              </a>      <br /> 
       Indicaciones
                      <?=$rowCiTas['st_Comentarios']?>
              <?
 } 
?>

              <br />
              <link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<div class="DIVmod_footer" ></div>
            </div><div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div><!--
          ________________________________<br>Firma Medico<br>--></div>
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
</body>
</html>
<?php mssql_close(); ?>