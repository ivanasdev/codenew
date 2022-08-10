<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$varcontrol=$_GET['varcontrol'];

if(($idevento > 0)&&($varcontrol == 1)){

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
<script type="text/javascript">
function Impresion()
{
if (window.print)
{
window.print();
window.opener.location.reload();
window.close();
}
else
{
alert("Este navegador no soporta esta opción.");
window.close();
}
}
</script>

<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>

<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=508, height=800, top=85, left=140";
window.open(pagina,"",opciones);
}
</script>
<script type="text/javascript" src="../cac/scripts.js"></script>
<script type="text/javascript" src="../cac/expansor.js"></script>

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
.ListAnswerListbox {	BACKGROUND: #95b2c3; COLOR: #555555
}
-->
</style></head>

<body onLoad="javascript:Impresion();"> 
	<form name="form1" method="post" action="dohistoria5php.php" id="form1">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%">

<div align="center">
    <div class="wrapper">
        
        <div class="DIVleft2">
            <!-- LEFT/IZQUIERDA --> 

          <img src="../images/icGuiones.gif" width="32" height="32" /><br />
           <BR><BR>
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">DATOS GENERALES </div>
            </div>
            <div class="DIVmod_body">
            	<div class="DIVpadding"><a href="checkinstep2actualiza.php?idevento=<?=$idevento?>&idusuario=<?=$id_UsuarioWeb?>">
            	
</a>
            	  <table width="66%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td colspan="2"><div>
                        <div><strong><?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$rowdata['st_Documento'].")"?></strong></div>
                      </div></td>
                    </tr>
                    <tr>
                      <td colspan="2">Nacimiento:
            	      <?=$rowdata['dt_FechaNacimiento']?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div>
                        Fecha Registro :
					     <?=$rowdata['dt_FechaRegistro']?>
                      </div></td>
                    </tr>
					<tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>PARACLINICOS</strong></div>
                        </div>
                      </div></td>
                    </tr><tr>  <td width="79%"> <p>
                      <? 

		 	$queryinsertcita = "	SELECT    *
FROM         tbl_ConsultaParaclinicos
where id_UsuarioWeb =  '".$id_UsuarioWeb."'
ORDER BY  dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    
                      Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
                      
                  
                  </p>
                      <?=$rowdata['st_Contenido']?>
                      <hr>

					  <? } } ?></td>
                      <td width="21%">&nbsp;</td>
                    </tr>
                  </table>
            	
            	</div>
            </div>
            </div>
    </div>
</div>
</td>
    <td width="3%">&nbsp;</td>
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
</form>
</body>
</html>
<?php mssql_close(); 
exit(); 
?>