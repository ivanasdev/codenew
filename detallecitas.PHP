<?php    header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");

$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,24,"PACIENTE - Ver Citas");
///////////////////////////////////////////////////////	

$responsable = $operador;
$id_UsuarioWeb = $_GET["idusuarioweb"];
$id_EventoVenta = $_GET["id_EventoVenta"];
if($id_EventoVenta == "NA") {
$morequery = " ";
$txtte= "GLOBAL";

}
else  {
$morequery = " AND (tbl_EvCitasUsuariosWebTotal.id_Evento = ".$id_EventoVenta.") ";
$txtte= "DETALLADO";

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=568, height=565, top=85, left=140";
window.open(pagina,"",opciones);
}</script>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
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
	height: 1405px;
    }

</style></head>

<body style="background-color:transparent "> 
<div align="center" class="trans" style="z-index:1;filter:alpha(opacity=100);float:left;-moz-opacity:.60;opacity:.60"> 
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="97%" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="47%"><div align="center" class="telefonosOutbound"> 
                <div align="left"><a href="detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>"><img src="../images/icosapps/Go-Back-48.png" border="0" /></a><img src="../images/icosapps/Calendar-64.png" />CITAS<br>
                  <?=$_SESSION['st_NombrePaciente']?>
                </div>
              </div></td>
            <td width="33%">&nbsp;</td>
          </tr>
        </table></td>
      <td width="3%" ></td>
    </tr>
    <tr> 
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td> <div class="wrapper"> <div class="DIVleft"> 
          <!-- LEFT/IZQUIERDA -->
        </div>
        <div class="DIVleft"> 
          <!-- CENTER/MEDIO -->
          <div class="DIVmod_header_border"><img src="images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
          <div class="DIVmod_header"> 
            <div class="DIVmod_header_text">LISTADO 
              <?=$txtte?>
              DE CITAS</div>
          </div>
          <table width="700" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
            <tr bgcolor="#003399"> 
              <td width="83"><font color="#FFFFFF"><strong>Status</strong></font></td>
              <td width="48"><font color="#FFFFFF"><strong>Fecha Cita</strong></font></td>
              <td width="49"><font color="#FFFFFF"><strong>Sucursal</strong></font></td>
              <td width="23"><font color="#FFFFFF"><strong>Tipo</strong></font></td>
              <td width="47"><font color="#FFFFFF"><strong>Recurso</strong></font></td>
              <td width="82"><font color="#FFFFFF"><strong>Fecha Registro</strong></font></td>
              <td width="32"></td>
            </tr>
            <?   	$queryselect = " SELECT     DATEDIFF(dd, GETDATE(), tbl_EvCitasUsuariosWebTotal.dt_FechaCita) AS discitas, DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWebTotal.dt_FechaCita) 
                      AS mincitas, fernandoruiz.cat_TipoCita.st_TipoCita, fernandoruiz.cat_SucursalClinica.st_Nombre, tbl_EvCitasUsuariosWebTotal.dt_FechaRegistro, 
                      tbl_EvCitasUsuariosWebTotal.dt_FechaCita, tbl_EvCitasUsuariosWebTotal.id_UsuarioWeb, tbl_EvCitasUsuariosWebTotal.id_Evento, 
                      tbl_EvCitasUsuariosWebTotal.id_StatusCita, fernandoruiz.cat_StatusCita.st_StatusCita, fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_StatusTerapia, 
                      fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_StatusPago, cat_TerapiaPaquete.st_TerapiaPaquete, ISNULL(fernandoruiz.cat_Medicos.st_Nombre, 'Sin asignación') 
                      AS st_NombreMedico, fernandoruiz.tbl_UsuarioSistemaWeb.st_Nombre AS operador,
					   fernandoruiz.cat_TipoCita.id_TipoCita, 
                      fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_VentaProductosUsuarioWeb
FROM         fernandoruiz.cat_Medicos RIGHT OUTER JOIN
                      tbl_EvCitasUsuariosWebTotal INNER JOIN
                      fernandoruiz.cat_TipoCita ON tbl_EvCitasUsuariosWebTotal.id_TipoCita = fernandoruiz.cat_TipoCita.id_TipoCita INNER JOIN
                      fernandoruiz.cat_SucursalClinica ON tbl_EvCitasUsuariosWebTotal.id_Sucursal = fernandoruiz.cat_SucursalClinica.id_SucursalClinica INNER JOIN
                      fernandoruiz.cat_StatusCita ON tbl_EvCitasUsuariosWebTotal.id_StatusCita = fernandoruiz.cat_StatusCita.id_StatusCita INNER JOIN
                      fernandoruiz.tbl_UsuarioSistemaWeb ON tbl_EvCitasUsuariosWebTotal.id_OperadorCC = fernandoruiz.tbl_UsuarioSistemaWeb.id_UsuarioSistemaWeb ON 
                      fernandoruiz.cat_Medicos.id_Medico = tbl_EvCitasUsuariosWebTotal.id_Medico LEFT OUTER JOIN
                      cat_TerapiaPaquete INNER JOIN
                      fernandoruiz.tbl_RecetasTerapiaUsuarioWeb ON cat_TerapiaPaquete.id_TerapiaPaquete = fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_TerapiaPaquete ON 
                      tbl_EvCitasUsuariosWebTotal.id_RecetaTerapiaUsuarioWeb = fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_RecetaTerapiaUsuarioWeb
					  
WHERE     (tbl_EvCitasUsuariosWebTotal.id_UsuarioWeb = ".$id_UsuarioWeb.") and fernandoruiz.cat_TipoCita.id_TipoCita = 5 ".$morequery."
ORDER BY tbl_EvCitasUsuariosWebTotal.dt_FechaCita DESC";
$rqueryselect = mssql_query($queryselect);
$numrows= mssql_num_rows($rqueryselect);
while($rowdatapharma = mssql_fetch_array($rqueryselect )){
					?>
            <tr> 
              <td> 
                <? if($rowdatapharma['discitas']==0 && ($rowdatapharma['id_StatusCita'] == 1 || $rowdatapharma['id_StatusCita'] == 2) ){ ?>
                <img src="../images/icosapps/Star-32.png" width="32" height="32" /> 
                <? } ?>
                <?=$rowdatapharma['st_StatusCita']?>
                <?
			  
				   if($rowdatapharma['id_StatusCita'] == 1) {?>
                <br> <input type="button" name="Submit3" value="cancelar"   onclick="javascript:Abrir_ventana('../cancelacita.php?idusuarioweb=<?=$rowdatapharma['id_UsuarioWeb']?>&idevento=<?=$rowdatapharma['id_Evento']?>')" /> 
                <?
				 
				
				 }?>
              </td>
              <td> 
                <?=$rowdatapharma['dt_FechaCita']?>
              </td>
              <td> 
                <?=$rowdatapharma['st_Nombre']?>
                <br> 
                <?=$rowdatapharma['operador']?>
              </td>
              <td> 
                <?=$rowdatapharma['st_TipoCita']?>
                <br> 
                <?  echo $rowdatapharma['st_TerapiaPaquete'];
				if($rowdatapharma['st_TerapiaPaquete']<>"")  echo "/<a href='historialpagosterapias.php?idusuarioweb=".$id_UsuarioWeb."&id_VentaProductosUsuarioWeb=".$rowdatapharma['id_VentaProductosUsuarioWeb']."'> *code ".$rowdatapharma['id_VentaProductosUsuarioWeb']."</a>";
				?>
              </td>
              <td> 
                <?=$rowdatapharma['st_NombreMedico']?>
              </td>
              <td> 
                <?=$rowdatapharma['dt_FechaRegistro']?>
              </td>
              <td> 
                <?
			
				   if(($rowdatapharma['id_TipoCita'] == 2 && $rowdatapharma['id_StatusPago'] == 1)&&
				   ($rowdatapharma['id_StatusCita'] == 1 || $rowdatapharma['id_StatusCita'] == 2)) {
				    if($rowdatapharma['id_VentaProductosUsuarioWeb'] > 0 ) {
				   
				   ?>
                <a href="historialpagosterapias.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_VentaProductosUsuarioWeb=<?=$rowdatapharma['id_VentaProductosUsuarioWeb']?>"><img src="../images/icosapps/Dolar-32.png" width="32" height="32" border="0" /></a> 
                <? }}
				
				   if(($rowdatapharma['id_TipoCita'] == 2 && $rowdatapharma['id_StatusPago'] == 2)&&
				   ($rowdatapharma['id_StatusCita'] == 1 || $rowdatapharma['id_StatusCita'] == 2)) {echo "Cubierta"; }
				   if($rowdatapharma['id_StatusPago'] == 2) echo "<br>Pagada";
				    ?>
              </td>
            </tr>
            <? } ?>
          </table>
          *Movimiento en el ciclo: 
          <?=$numrows?>
          <br />
          Reporte filtrado de: 
          <?=$fecha1?>
          a: 
          <?=$fecha2?>
          <br />
          Reporte generado al 
          <?=date('d-m-Y H:i')?>
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