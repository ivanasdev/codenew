<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="300">
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
window.open(pagina,"",opciones);
}
</script>

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
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
-->
</style></head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" background="../images/headofice.jpg"><div align="center" class="telefonosOutbound">
      <div align="left">CITAS CHECK IN'S  </div>
    </div></td>
    <td width="3%" background="../images/headofice.jpg"><img src="../images/headofice.jpg" width="41" height="90" /></td>
  </tr>
  <tr>
    <td><table width="100%" border="0">
      <tr>
        <td width="15%">&nbsp;</td>
        <td width="85%">&nbsp;</td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">

  
  <tr>
    <td>

<div align="center">
    <div class="wrapper">
      <div class="DIVleft">
            <!-- CENTER/MEDIO -->
            
            
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">CONSULTAS</div>
            </div>
            <div class="DIVmod">
            	<div class="DIVpadding">
            	  <table width="100%" border="0">
                    <tr>
                      <td width="83%">&nbsp;</td>
                      <td width="17%"><div align="right"><img src="../images/icSecInicio.gif" width="56" height="40" /></div></td>
                    </tr>
                  </table>  
            	  <hr>
            	  <br>
            	  Listado de citas (todo el dia)
				<?php
$query = "	SELECT     DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita) -$mindiferencia AS mincitas, cat_StatusCita.st_StatusCita, tbl_EvCitasUsuariosWeb.id_Evento, 
                      tbl_EvCitasUsuariosWeb.id_UsuarioWeb, tbl_EvCitasUsuariosWeb.dt_FechaCita, tbl_EvCitasUsuariosWeb.st_HoraCita, 
                      tbl_EvCitasUsuariosWeb.id_Sucursal, tbl_EvCitasUsuariosWeb.id_Medico, tbl_EvCitasUsuariosWeb.id_OperadorCC, 
                      tbl_EvCitasUsuariosWeb.dt_FechaRegistro, tbl_EvCitasUsuariosWeb.id_StatusCita, tbl_EvCitasUsuariosWeb.id_TipoCita, 
                      tbl_EvCitasUsuariosWeb.id_OrigenRegistro, tbl_EvCitasUsuariosWeb.dt_FechaSalida, tbl_UsuariosWeb.st_Nombre, 
                      tbl_UsuariosWeb.st_ApellidoPaterno, cat_TipoCita.st_TipoCita
FROM         tbl_EvCitasUsuariosWeb INNER JOIN
                      cat_StatusCita ON tbl_EvCitasUsuariosWeb.id_StatusCita = cat_StatusCita.id_StatusCita INNER JOIN
                      tbl_UsuariosWeb ON tbl_EvCitasUsuariosWeb.id_UsuarioWeb = tbl_UsuariosWeb.id_UsuarioWeb INNER JOIN
                      cat_TipoCita ON tbl_EvCitasUsuariosWeb.id_TipoCita = cat_TipoCita.id_TipoCita
WHERE     (tbl_EvCitasUsuariosWeb.id_Medico = '".$id_Medico."') 
AND (DAY(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".$_GET['dia']."')
AND (MONTH(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".$_GET['mes']."')
AND (YEAR(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".$_GET['anio']."')


ORDER BY tbl_EvCitasUsuariosWeb.dt_FechaCita ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
				<div class="DIVmod_title">	<br>
                  <? if ($rowCiTas['id_StatusCita']  > 1) { ?>
                  <img src="../images/iPassed.png" width="12" height="12" /> 
                  <? } else  { ?>
                  <img src="../images/iNoPassed.png" width="12" height="12" /> 
                  <? }  ?>
                  <a href="../cac/detallecita.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>" target="mainFrame">/ 
                  <?=$rowCiTas['st_TipoCita']?>
                  / 
                  <?=$rowCiTas['st_HoraCita']?>
                  / Status 
                  <?=$rowCiTas['st_StatusCita']?>
                  <br>
                  <?=$rowCiTas['st_Nombre']?>
                  <?=$rowCiTas['st_ApellidoPaterno']?>
                  ( 
                  <?=$rowCiTas['mincitas']?>
                  minutos para la cita)</a><br>
                  //<a href="detallepaciente.php?varcontrol=1&idevento=<?=$rowCiTas['id_Evento']?>&idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>" target="mainFrame"><img src="../images/right_over.gif" alt="Comenzar consulta" width="16" height="16" />Check in</a></div>
                <?
 } 
?>

                
              </div>
            	<div align="right"><a href="content.php"></a>          	  </div>
            	<div class="DIVmod_footer" >
            	  <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                    <div id="expansor1">
                    <div style="display:none;" id="first" class="DIVmod_footer_hide">
                      La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita.                    </div>  
                    </div>              
                </div>            
            </div>
        <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
        <span class="DIVmod_title"><img src="../images/iPassed.png" width="12" height="12" /></span><span class="Estilo7">Check In <br>
       <img src="../images/iNoPassed.png" width="12" height="12" /></span><span class="Estilo7">Sin confirmar </span><span class="DIVpadding"><br />
       <a href="javascript:Abrir_ventana('nuevacita.php?idusuarioweb=<?=$id_UsuarioWeb?>')"></a></span></div>
        <div class="DIVfooter">
            <a href="#">Aviso Legal</a> | <a href="#">Seguridad</a>  | <a href="#">Mapa del Sitio</a> | <span class="copyright">© <em>Copyright</em> 2009</span></div> 
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