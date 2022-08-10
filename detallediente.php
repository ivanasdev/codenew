<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["id_UsuarioWeb"];
$id_Diente = $_GET["id_Diente"];
$cotizacion= $_GET["id_cotizacion"];


$id_Dientejpg = 18;
$idevento=$_GET['idevento'];
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

$queryselect2 =  "SELECT     *
FROM         tbl_RecetasUsuariosWeb
WHERE     (id_EventoCita = '".$idevento."')";
$rqueryselect2 =  mssql_query($queryselect2);
$rowdata2= mssql_fetch_array($rqueryselect2);
$numrowsdel = mssql_num_rows($rqueryselect2);
 $idrec = $rowdata2['id_Receta'];


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

<script language="JavaScript">
 function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron = /[0123456789]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 


function vCCat(d,cT) { 

       var nelemni4="cantidad_"+d
	   var lbt4 =  document.getElementById(nelemni4);
       var lsp4=lbt4.value
	  if( lsp4>cT){
	  
	  alert('No pude incluir mas productos de los que hay en almacen')
	  lbt4.value='1'
	  }
	
	
} 


</script>

</head>

<body>	

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" background="../images/headofice.jpg"><div align="center" class="telefonosOutbound">
      <div align="left"> DENTAL :: <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$rowdata['st_Documento'].")"?></div>
    </div></td>
    <td width="3%" background="../images/headofice.jpg"><img src="../images/headofice.jpg" width="41" height="90" /></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%"><div align="center" class="telefonosOutbound">
                <? if($rowdata['RegistroMedico']== 0)   { ?>

<?  }?>
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
              <div class="DIVmod_header_text"> 
                <?=$rowdata['st_Nombre']?>
              </div>
            </div>
            <div class="DIVmod"> 
              <div class="DIVpadding"> 
                <div align="right"></div>
                <a href="detallepacientepatologia.php?idusuarioweb=<?=$id_UsuarioWeb?>&amp;idevento=<?=$idevento?>"> 
                <br />
                </a> 
                <table width="100%">
                  <tr> 
                    <td width="17%"><img src="dientes/images/<?=$id_Dientejpg?>.jpg" width="59" height="185" /></td>
                    <td width="83%"><table width="100%">
                        <tr> 
                          <td width="4%">&nbsp;</td>
                          <td width="96%">
						  <form action="dientes/docerrar.php">
						      <input type="submit" name="Submit" value="Terminar cerrar" /> 
						  </form>
                            <br>
                            LISTA DE SERVICIOS A REALIZAR EN LA PIEZA<strong> 
                            <?=$id_Diente?>
                            </strong> : </td>
                        </tr>
                        <tr> 
                          <td colspan="2"> 
                            <?php

$queryselect =  "SELECT     tbl_CotizacionDentalDetalle.nt_Cantidad AS cantidad, cat_ServicioDental.st_ServicioDental, cat_ServicioDental.nt_Costo, cat_ServicioDental.nt_CostoNM, 
                      tbl_CotizacionDentalDetalle.id_diente,tbl_CotizacionDentalDetalle.id_CotizacionDentalDetalle
FROM         tbl_CotizacionDentalDetalle INNER JOIN
                      cat_ServicioDental ON tbl_CotizacionDentalDetalle.id_ServicioDental = cat_ServicioDental.id_ServicioDental
WHERE     (tbl_CotizacionDentalDetalle.id_CotizacionDental = ".$cotizacion.") AND (tbl_CotizacionDentalDetalle.id_diente = ".$id_Diente.")
";
$rqueryselect =  mssql_query($queryselect);

echo "<table border='0'>";
while($rowdata= mssql_fetch_array($rqueryselect)){

echo "<tr><td valign='top'>";

?>
                            <a href="javascript:Abrir_ventana('dientes/deletediente.php?idCotizacion=<?=$cotizacion?>&idusuarioweb=<?=$id_UsuarioWeb?>&iddetalle=<?=$rowdata['id_CotizacionDentalDetalle']?>')"><img src="dientes/icWrong.gif"  border="0" /> 
                            </a> 
                            <?php

echo "</td><td class='cnt' >
Servicio ".$rowdata['st_ServicioDental']." <br>
Costo: $".$rowdata['nt_Costo']."

</td></tr>";
//echo "<tr><td class='cnt'>Servicio: ".$rowdata['st_ServicioDental']."</td><td class='cnt'> Costo: $".$rowdata['nt_Costo']."</td></tr>";

}
echo "</table>";
?>
                          </td>
                        </tr>
                        <tr> 
                          <td>&nbsp;</td>
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
                        <tr> 
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
                <br />
                <form name="form2" method="post" action="doaltaDiente.php" id="form2">
                  <img src="../images/iPassed.png" width="12" height="12" />Servicios 
                  : 
                  <label> 
                  <input name="token" type="text" id="token2"  onkeyup="javascript:changeAjax('ajaxRecetadientes.php?empresa=1', 'token2', 'xDiv_SubEstados_2');"/>
                  </label>
                  <input name="id_Diente" type="hidden" id="id_Diente" value="<?=$id_Diente?>" /><strong> 
                  <input type="submit" name="Submit2" value="Agregar" />
                  <input name="idusuarioweb" type="hidden" id="idusuarioweb" value="<?=$id_UsuarioWeb?>" />
                  <input name="idcotizacion" type="hidden" id="idcotizacion" value="<?=$cotizacion?>" />
                  <br>
                  <div id="xDiv_SubEstados_2" >..</div>
                </form>
              </div>
              <div class="DIVmod_footer" > 
                <div id="div5"> 
                  <div style="display:none;" id="div6" class="DIVmod_footer_hide"> 
                    La busqueda puede ser por nombre de cliente , apellidos ,email 
                    ,por numero de cedula , o numero de cita. </div>
                </div>
              </div>
            </div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
            <span class="Estilo7"><br />
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
</table>
</body>
</html>
<?php mssql_close(); ?>