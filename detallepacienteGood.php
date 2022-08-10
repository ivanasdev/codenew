<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$varcontrol=$_GET['varcontrol'];

 $query = " UPDATE    tbl_EvCitasUsuariosWeb
SET             id_StatusCita = 3
where  id_Evento ='".$idevento."'";
$rquery = mssql_query($query);

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
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo8 {
	color: #FFFFFF;
}
-->
</style></head>

<body>	<form name="form1" method="post" action="doregistromedico.php" id="form1">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" background="../images/headofice.jpg"><div align="center" class="telefonosOutbound">
      <div align="left"> CONSULTA  <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$rowdata['st_Documento'].")"?></div>
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
            <!-- LEFT/IZQUIERDA -->
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Datos Generales </div>
            </div>
            <div class="DIVmod_body">
            	<div class="DIVpadding"><a href="checkinstep2actualiza.php?idevento=<?=$idevento?>&idusuario=<?=$id_UsuarioWeb?>">
            	  <? if($rowdata['RegistroMedico']== 0)   { ?>
                  <script>alert('Antes de  continuar con la  cita  complete la informacion general requerida del paciente')			</script>
</a>
            	  <table width="97%" border="0" bgcolor="#996633">
                    <tr>
                      <td><span class="Estilo8">Fecha de Nacimiento </span></td>
                      <td><label>
                        <input type="text" value="" readonly name="theDate">

<input type="button" value="Cal" onClick="displayCalendar(document.form1.theDate,'yyyy/mm/dd',this)">


                      </label></td>
                    </tr>
                    <tr>
                      <td><span class="Estilo8">Ocupacion
                        <input name="idevento" type="hidden" id="idevento" value=" <?=$idevento?>" />
                        <input name="idusuarioweb" type="hidden" id="idusuarioweb" value=" <?=$id_UsuarioWeb?>" />
                      </span></td>
                      <td><select name="ocupacion" id="ocupacion">
                        <option value="1">ocupacion 1</option>
                        <option value="2">oucpacion 2</option>
                                              </select>                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="submit" name="Submit" value="Enviar" /></td>
                    </tr>
                  </table>
            	  <?  }?>
            	  <a href="checkinstep2actualiza.php?idevento=<?=$idevento?>&idusuario=<?=$id_UsuarioWeb?>"><img src="../images/icRefresh.gif" width="13" height="16" /> Actualizar datos </a><br>
            	<?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$rowdata['st_Documento'].")"?>
            	  <div class="DIVopts"><span class="menuTop">
            	    </span>
            	    <div class="DIVopt">Nacimiento:
            	      <?=$rowdata['dt_FechaNacimiento']?>
          	      </div>
            	    <div class="DIVopt">Ocupacion:
            	      <?=$rowdata['id_Ocupacion']?>
          	      </div>
            	    <span class="menuTop">
            	    <div class="DIVopt">Direccion:  <?=$rowdata['st_Direccion']?>
          	      </div>
            	    <div class="DIVopt">Email : <?=$rowdata['st_Email']?>
          	      </div>
            	    Telefonos:
				    <?	 
					$querytelefons = "SELECT     tbl_UsuariosWebTelefonos.st_Telefono, cat_TipoTelefono.st_TipoTelefono
FROM         tbl_UsuariosWebTelefonos INNER JOIN
                      cat_TipoTelefono ON tbl_UsuariosWebTelefonos.id_TipoTelefono = cat_TipoTelefono.id_TipoTelefono
WHERE     (tbl_UsuariosWebTelefonos.id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rquerytelefons = mssql_query($querytelefons);
while ($rowtels = mssql_fetch_array($rquerytelefons)) {

?>
					  <div class="DIVopt"><?=$rowtels ['st_TipoTelefono']?> / <?=$rowtels ['st_Telefono']?> 
					  </div>
					  <?  } ?>
					   <div class="DIVopt">Fecha Registro :
					     <?=$rowdata['dt_FechaRegistro']?>
					   </div>
					   <br>
					   Status del Registro
				     
                   
				   

		            </span>     
            	    <? if($rowdata['RegistroMedico'] > 0)  {
?>

           <img src="../images/respOK.gif" width="30" height="30" /> <strong>Completo</strong>  <?  }  else  { ?>  <img src="../images/respFail.gif" width="30" height="30" /> <strong>Incompleto</strong> <? }  ?><br>
           <br>
         <a href="javascript:Abrir_ventana('nuevacita.php?idusuarioweb=<?=$id_UsuarioWeb?>')"> <img src="../images/iLada.gif" width="16" height="13" /> Ver registro telefonico </a> </div>
              </div></div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Citas</div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                <div align="right"> <img src="../images/icSecInicio.gif" width="56" height="40" /></div>
                <a href="javascript:Abrir_ventana('formCitaUsers.php?idusuarioweb=<?=$id_UsuarioWeb?>')"><img src="../images/iCheck.gif" width="14" height="18" />Cita Nueva</a><ul>
                <?php

	 	$query = "	SELECT     DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita) AS mincitas, cat_StatusCita.st_StatusCita, tbl_EvCitasUsuariosWeb.*
FROM         tbl_EvCitasUsuariosWeb INNER JOIN
                      cat_StatusCita ON tbl_EvCitasUsuariosWeb.id_StatusCita = cat_StatusCita.id_StatusCita
WHERE     (tbl_EvCitasUsuariosWeb.id_UsuarioWeb =  '".$id_UsuarioWeb."')
ORDER BY tbl_EvCitasUsuariosWeb.dt_FechaCita DESC ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
                <div class="DIVmod_title"> <br />
                 
              <li>   <a href="javascript:Abrir_ventana('detallecita.php?ievento=<?=$rowCiTas['id_Evento']?>')">
                    <?=$rowCiTas['dt_FechaCita']?>
                    <br />
                    Status
                    <?=$rowCiTas['st_StatusCita']?>
                    </a> </li></div>
                <?
 } 
?>
</ul>              </div>
              <div class="DIVmod_footer" >
                <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                <div id="expansor1">
                  <div style="display:none;" id="first" class="DIVmod_footer_hide"> La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita. </div>
                </div>
              </div>
            </div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
          </div>
        <div class="DIVcenter">
            <!-- CENTER/MEDIO -->
            
            
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Historia Clinica </div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                <div align="right"> <img src="../images/icUsers.gif" width="48" height="32" /></div>
				
				<? if($idevento>0) {?>
                <a href="indexHomeHistoria.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$idevento?>"><img src="../images/iCheck.gif" width="14" height="18" />Agregar Informacion <br /> <br /> </a>
                <?php }

	 	$query = "	SELECT     cat_Patologias.st_Patologia, tbl_PatologiasUsuariosWeb.dt_FechaPatologia, tbl_PatologiasUsuariosWeb.st_Comentarios, 
                      tbl_PatologiasUsuariosWeb.dt_FechaRegistro, tbl_PatologiasUsuariosWeb.id_UsuarioWeb
FROM         cat_Patologias INNER JOIN
                      tbl_PatologiasUsuariosWeb ON cat_Patologias.id_Patologia = tbl_PatologiasUsuariosWeb.id_Patologia
WHERE     (tbl_PatologiasUsuariosWeb.id_UsuarioWeb = '".$id_UsuarioWeb."')  order  by tbl_PatologiasUsuariosWeb.dt_FechaPatologia desc ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
                 <br />
                   
                    <img src="../images/iPassed.png" width="12" height="12" /> 
                    
                    <span class="Estilo7">
                  
                      <strong>Patologia
                      <?=$rowCiTas['st_Patologia']?></strong> <br />
                   Diagnosticado el  <?=$rowCiTas['dt_FechaPatologia']?>
                   <br>
                      ( Registrado el 
                      <?=$rowCiTas['dt_FechaRegistro']?>
                     ) <br>
                     <?=$rowCiTas['st_Comentarios']?> <br />
                  </span>
                <?
 } 
?>
              </div>
              <div class="DIVmod_footer" >
                <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                <div id="div">
                  <div style="display:none;" id="div2" class="DIVmod_footer_hide"> La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita. </div>
                </div>
              </div>
            </div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              <div class="DIVmod_header_text">Recetas/Compras</div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                <div align="right"></div>
             	<? if($idevento>0) {?>  <a href="javascript:Abrir_ventana('detallepacienterecetar.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$idevento?>')">
			<img src="../images/iCheck.gif" width="14" height="18" />Recetar productos </a>
           <br />
                         <?php }

	 	$query = "	SELECT     tbl_RecetasUsuariosWeb.dt_FechaRegistro, tbl_ProductosReceta.productos, tbl_RecetasUsuariosWeb.id_UsuarioWeb,  tbl_RecetasUsuariosWeb.id_Receta,
                      isnull(tbl_ProductosRecetaVentas.productos,0) AS productosventas
FROM         tbl_RecetasUsuariosWeb left outer JOIN
                      tbl_ProductosRecetaVentas ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosRecetaVentas.id_Receta LEFT OUTER JOIN
                      tbl_ProductosReceta ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosReceta.id_Receta
WHERE     (tbl_RecetasUsuariosWeb.id_UsuarioWeb = '".$id_UsuarioWeb."')
ORDER BY  tbl_RecetasUsuariosWeb.dt_FechaRegistro DESC ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
               <br />
                   
                    <img src="../images/iPassed.png" width="12" height="12" /> 
                  <a href="javascript:Abrir_ventana('detallepacienterecetalist.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$idevento?>&idreceta=<?=$rowCiTas['id_Receta']?>')">
                    <?=$rowCiTas['dt_FechaRegistro']?>
                    <br />
                    
                      (
                      <?=$rowCiTas['productos']?>
                     productos recetados )
					 <br />
                    
                      (
                      <?=$rowCiTas['productosventas']?>
                     productos comprados )</a>
                <?
 } 
?>
              </div>
              <div class="DIVmod_footer" >
                <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                <div id="div3">
                  <div style="display:none;" id="div4" class="DIVmod_footer_hide"> La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita. </div>
                </div>
              </div>
            </div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
            <span class="Estilo7"></span><span class="Estilo7"><br />
          </span></div>
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
</body>
</html>
<?php mssql_close(); ?>