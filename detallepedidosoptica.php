<?php    header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$responsable = $operador;
$id_UsuarioWeb = $_GET["idusuarioweb"];
$id_VentaProductosUsuarioWeb = $_GET["id_VentaProductosUsuarioWeb"];
if($id_VentaProductosUsuarioWeb == "NA") {
$morequery = " ";
$txtte= "GLOBAL";

}
else  {
$morequery = " AND         (tbl_EvVentaProductosRecetaUsuariosWeb.id_VentaProductosUsuarioWeb = ".$id_VentaProductosUsuarioWeb.") ";
$txtte= "DETALLADO";

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript">
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
<br><br><br><br><br>  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="97%" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="47%"><div align="center" class="telefonosOutbound"> 
                <div align="left"><a href="detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>"><img src="../images/icosapps/Go-Back-48.png" border="0" /></a><img src="../images/icosapps/optica.png" width="48" height="48" />OPTICA<br>
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
              DE VENTAS DE OPTICA</div>
          </div>
          <table width="700" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
            <tr bgcolor="#003399"> 
              <td><font color="#FFFFFF"><strong></strong></font></td>
              <td><font color="#FFFFFF"><strong>Fecha</strong></font></td>
              <td><font color="#FFFFFF"><strong>Sucursal</strong></font></td>
              <td><font color="#FFFFFF"><strong>Costo</strong></font></td>
              <td><font color="#FFFFFF"><strong>Abonado</strong></font></td>
              <td><font color="#FFFFFF"><strong>Saldo</strong></font></td>
            </tr>
            <?
  	$queryselect = "  SELECT     OPTICA_GlobalCompras.abonado, OPTICA_GlobalCompras.i_Total, OPTICA_GlobalCompras.saldo, OPTICA_GlobalCompras.id_EventoVenta, 
                      fernandoruiz.cat_SucursalClinica.st_Nombre, fernandoruiz.tbl_UsuarioSistemaWeb.st_Nombre AS operador, tbl_EvVentaOptica.dt_FechaRegistro, 
                      tbl_EvVentaOptica.id_UsuarioWeb,  tbl_CotizacionOptica.st_Key
FROM         OPTICA_GlobalCompras INNER JOIN
                      tbl_EvVentaOptica ON OPTICA_GlobalCompras.id_EventoVenta = tbl_EvVentaOptica.id_VentaOptica INNER JOIN
                      fernandoruiz.cat_SucursalClinica ON tbl_EvVentaOptica.id_Sucursal = fernandoruiz.cat_SucursalClinica.id_SucursalClinica INNER JOIN
                      fernandoruiz.tbl_UsuarioSistemaWeb ON tbl_EvVentaOptica.id_Operador = fernandoruiz.tbl_UsuarioSistemaWeb.id_UsuarioSistemaWeb INNER JOIN
          				fernandoruiz.tbl_CotizacionOptica ON tbl_EvVentaOptica.id_VentaOptica = fernandoruiz.tbl_CotizacionOptica.id_EventoVenta
WHERE     (tbl_EvVentaOptica.id_UsuarioWeb = ".$id_UsuarioWeb.") ".$morequery."
ORDER BY tbl_EvVentaOptica.dt_FechaRegistro DESC  ";
$rqueryselect = mssql_query($queryselect);
$numrows = mssql_num_rows($rqueryselect);
while($rowdatapharma = mssql_fetch_array($rqueryselect )){
					?>
            <tr> 
              <td> <a href="javascript:Abrir_ventana('../../ticketgeneralfinal.php?idsession=<?=$rowdatapharma['st_Key']?>')"> 
                <img src="../images/icosapps/Ticket-32.png" /></a> </td>
              <td> 
                <?=$rowdatapharma['dt_FechaRegistro']?>
              </td>
              <td> 
                <?=$rowdatapharma['st_Nombre']?>
                <br> 
                <?=$rowdatapharma['operador']?>
              </td>
              <td> 
                <?=$rowdatapharma['i_Total']?>
              </td>
              <td> <a href="historialpagosoptica.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=<?=$rowdatapharma['id_EventoVenta']?>"> 
                <?=$rowdatapharma['abonado']?>
                </a> </td>
              <td> 
                <?=number_format($rowdatapharma['saldo'])?>
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
</body>
</html>
<?php mssql_close(); ?>