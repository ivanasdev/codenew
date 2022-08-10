<?php header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");       

session_start();


if( $_SESSION['id_TipoUsuario'] != 14 ){
	echo"
		<script>
			alert('Sesion expirada favor de cerrar sesion y volver a ingresar al modulo!');
			location.href='logout.php';
		</script>
	";
	
}


/****************************************	Registrando Tracking	********************************************************************************/
include("../Tracking.class.php");
$objTracking = new Tracking();
$objTracking->setTracking($_SESSION["id_Operador"],$_GET["idusuarioweb"],$_SESSION["id_Sucursal"],"/system/optica/detallePaciente.php","Optica");

/***************************************************************************************************************************************************/

    
//caducamos  las sessiones  viejas
$querycad = "UPDATE    tbl_TicketGeneral
SET              id_Impreso = 1
WHERE     (DATEDIFF(dd, GETDATE(), dt_FechaRegistro) < 0)  and  id_Impreso = 0 ";
$rquerycad = mssql_query($querycad);

$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$varcontrol=$_GET['varcontrol'];
$idtipocita= $_GET["idtipocita"];

//obtenemos  el dinero electronico
$queryd= "SELECT     saldoelectronico
FROM         DE_Saldo
WHERE     (id_UsuarioWeb = ".$id_UsuarioWeb.")";
$rqueryd = mssql_query($queryd);
$rowsaldo  = mssql_fetch_array($rqueryd);
$saldoelectornico = $rowsaldo["saldoelectronico"];

//OBTENEMOS LA DEUDA DE TREAPIAS
$querydeu = "SELECT     isnull(SUM(tbl_SaldoFinalTerapias.i_Saldo),0) AS i_Saldo
FROM         tbl_SaldoFinalTerapias INNER JOIN
                      tbl_EvVentaProductosRecetaUsuariosWeb ON 
                      tbl_SaldoFinalTerapias.id_VentaProductosUsuarioWeb = tbl_EvVentaProductosRecetaUsuariosWeb.id_VentaProductosUsuarioWeb
WHERE     (tbl_EvVentaProductosRecetaUsuariosWeb.id_UsuarioWeb = ".$id_UsuarioWeb.")";

$querydeu = "
select sum(deudas) i_Saldo
from (
select co.i_Total - sum(pu.i_Cantidad) deudas
from tbl_PagosUsuarioWeb pu
inner join tbl_TicketGeneral tg on tg.id_TicketGeneral = pu.id_session
inner join tbl_UsuariosWebCac us on us.id_UsuarioWeb = pu.id_UsuarioWeb
inner join tbl_evVentaOptica vo on convert(varchar(20),vo.id_VentaOptica) = pu.id_EventoConcepto
inner join tbl_CotizacionOptica co on co.id_CotizacionOptica= vo.id_CotizacionOptica
where pu.id_Concepto = 8 
and pu.id_UsuarioWeb = ".$id_UsuarioWeb."
group by co.id_CotizacionOptica, co.i_Total
)tabla1
";
$rqueryd = mssql_query($querydeu);
$rowsaldo  = mssql_fetch_array($rqueryd);
$i_Saldo = $rowsaldo["i_Saldo"];



$queryselect =  "SELECT   *
FROM        tbl_UsuariosWeb
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rqueryselect =  mssql_query($queryselect);
$rowdata= mssql_fetch_array($rqueryselect);
if( $rowdata['st_Documento']=="") $st_Documento = "Sin memebresia";
else    $st_Documento = $rowdata['st_Documento'];
 $_SESSION['st_NombrePaciente'] = $rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']."(".$st_Documento.")";

//GENERAMOS LA SESION  DE  COMPRA
 $query = "SELECT     id_TicketGeneral,st_Barcode
FROM         tbl_TicketGeneral
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')  and id_Impreso = 0
AND (DATEDIFF(dd, GETDATE(), dt_FechaRegistro) = 0) AND i_ImpresoPreticket = 0
ORDER BY id_TicketGeneral DESC";
$rquery = mssql_query($query);
 $numrows = mssql_num_rows($rquery);
if($numrows==0){
 $st_Key = md5($id_UsuarioWeb.date('ymis'));
 $querysession = "INSERT INTO tbl_TicketGeneral
                      (id_UsuarioWeb, st_Key, id_Impreso, id_Operador)
VALUES     ('".$id_UsuarioWeb."','".$st_Key."',0,'".$operador."')";
$rquerysession =  mssql_query($querysession);
$rquery = mssql_query($query);
$noexisita =0;
}
$rowsesion = mssql_fetch_array($rquery);
$id_TicketGeneral =$rowsesion["id_TicketGeneral"];
 $_SESSION["id_TicketGeneral"] = $id_TicketGeneral;
  $_SESSION["varcode"] = $rowsesion["st_Barcode"];

 //generamos  el codigo de  barras del  ticket en caso de que la session no exisita
 if($noexisita==0){
 require("varcodesession.php");
 //actualizmos el campo de codigo de barras
 $queyvarcode = "UPDATE    tbl_TicketGeneral
SET              st_Barcode ='".$_SESSION["varcodedigit"]."',
st_BarcodeClean  ='".$varcode."',
st_Digit  ='".$digit."'

WHERE     (id_TicketGeneral = ". $id_TicketGeneral.")";
 $rqueyvarcode = mssql_query($queyvarcode);
 
 }
$queryselectSt =  "SELECT     id_StatusCita
FROM         tbl_EvCitasUsuariosWeb
WHERE     (id_UsuarioWeb ='".$id_UsuarioWeb."') AND (id_TipoCita = 1)";
$rqueryselectSt =  mssql_query($queryselectSt);
$rowdataSt= mssql_fetch_array($rqueryselectSt);
$statuCita=$rowdataSt['id_StatusCita'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="600;URL=detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>" >
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<script src="../../utils/jquery-1.11.1.min.js" language="javascript" type="text/javascript"></script>
<script src="../../utils/jqueryAlerts11/jquery.alerts.js" language="javascript" type="text/javascript"></script>
<link href="../../utils/jqueryAlerts11/jquery.alerts.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function generaPreticket(){
	
	var idTicketGeneral = $('#idTicketGeneral').val();
	
	jConfirm('Se generar&aacute; el preticket y ya no podr&aacute; modificar la cotizaci&oacute;n, &iquest;Desea continuar?', 'Cuadro de Confirmacion', 5, function(result) {
    	if(result){
						
			$.ajax({
				url : "ajaxActualizaPreticket.php",
				type : "POST",
				dataType : "json",
				data : {
					id_TicketGeneral: idTicketGeneral,
					flag : 1
				},
				beforeSend: function(){
					$('.linkPreticket').hide();				
				},
				success : function(data) {  					
				
					if(data.error == 1){
						jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){$('.linkPreticket').show();});
					}
					else{
						//jAlert(data.mensaje, 'Cuadro de Dialogo', 1, function(){
							//Abrir_ventana('../../preticketgeneral.php?idsession='+idTicketGeneral+'&preticketOptica=1');
							abrirPopPreticket('400','400','../../preticketgeneral.php?idsession='+idTicketGeneral+'&preticketOptica=1')
						//});	
					}
														
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					jAlert('Status: '+textStatus+' - Error: '+errorThrown, 'Cuadro de Dialogo', 2, function(){
						$('.linkPreticket').show();
					});				
				}           
			});
			
		}
	});
}

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
<script>
  function Enviar(){
   
        document.encuesta.action="../../carnet.php";
   document.forms[0].submit();
      
}
function EnviarVer(){
   
        document.encuesta.action="../../previewcarnet.php";
   document.forms[0].submit();
      
}
</script>
<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=568, height=765, top=85, left=140";
window.open(pagina,"",opciones);
}
function Abrir_ventanas (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=850, height=565, top=85, left=140";
window.open(pagina,"",opciones);
}
</script>

<script type="text/javascript" src="scripts.js"></script>
<script type="text/javascript" src="expansor.js"></script>


<!-- Scripts para datos Paciente -->
<script type="text/javascript" src="../../utils/tinybox/tinybox.js"></script>
<link href="../../utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css">
<script src="../../system/RegistroPaciente/updatePaciente.js"></script>
<!-- Scripts para datos Paciente -->

<script type="text/javascript">

/////////// TinyBox Receta ///////////
function abrirPopPreticket(ancho,alto,php){
	TINY.box.show({iframe:php,boxid:'frameless',width:ancho,height:alto,fixed:false,maskid:'graymask',maskopacity:40});	
}

$(document).ready(function() {
	
	cargaDatosPaciente(<?=intval($idevento)?>,<?=$id_UsuarioWeb?>,'../../','optica');

});

</script>

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
.Estilo8 {font-size: 12px}
-->

 .cnt{
      width:850px;
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

<body <?=((isset($_GET['muestraCotizacion']))?"onload=\"javascript:Abrir_ventana('detallepacienteOpticaDo_Test.php?i_Consultorio=0&idusuarioweb=".$id_UsuarioWeb."');\"":"")?> style="background-color:transparent">

<input type="hidden" name="refreshDatosUpdate" id="refreshDatosUpdate" value="0"/>
<input type="hidden" name="idEventoCitaUpdate" id="idEventoCitaUpdate" value="<?=intval($idevento)?>">
<input type="hidden" name="idUsuarioWebUpdate" id="idUsuarioWebUpdate" value="<?=$id_UsuarioWeb?>">
<input type="hidden" name="ruta2indexUpdate" id="ruta2indexUpdate" value="../../">

<br>  
	<form name="encuesta" method="post"  action="../../carnet.php"   id="encuesta"  target="encuesta" onsubmit="window.open('', 'encuesta', 'width=10,height=12')">
 
<input type="hidden" id="idTicketGeneral" name="idTicketGeneral" value="<?=$id_TicketGeneral?>" />    
         
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" ><table><tr><td><div align="center" class="telefonosOutbound">
                <div align="left"><a href="content.php"><img src="../images/icosapps/exit-32.png" border="0" /></a> 
                  <?=$rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno'] ?>
			

                  <?
	  if(!$rowdata['st_Documento']||($rowdata['st_Documento']==' ') ||($rowdata['st_Documento']=='null') ||($rowdata['st_Documento']=='') ){
			echo "(Sin Membresia) ";
			
			 }else{
			 echo "( ".$rowdata['st_Documento']." )";
			 }
	  ?>	 <a href="detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>" >  <img src="../images/icRefresh.gif" width="13" height="16" border="0"  /></a>
               <div id="refreshsession"> <img src="../images/icosapps/Login-32.png" /> ID SESSION : 
                  <?=$_SESSION["id_TicketGeneral"]?>   /<img src="../images/icosapps/People-32.png" /> GOLD</div>
              
                  Saldo electronico: $e <?=number_format($saldoelectornico,2) ?>/Deuda &Oacute;ptica: $<?=number_format($i_Saldo,2)?><a href="registrarpagos.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=NA"> << Ver</a></div></div>
   </td></tr></table> </td>
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
              <div class="DIVmod_header_border"><img src="images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text"><img src="../images/icosapps/Personal-information-48.png" width="48" height="48" /> 
                  Datos Generales 
                  <input name="v1" type="checkbox" id="v1" value="1" checked="checked" />
                  <input name="preview" type="hidden" id="preview" value="0" />
                  <input name="id_UsuarioWeb" type="hidden" id="id_UsuarioWeb" value="<?=$id_UsuarioWeb?>" />
                </div>
              </div>
              <div class="DIVmod_body"> 
                <div class="DIVpadding">
                
                
<?php 

if( !$rowdata['st_Documento'] || ($rowdata['st_Documento']==' ') || ($rowdata['st_Documento']=='SIN MEMEBRESIA') || ($rowdata['st_Documento']=='null') || ($rowdata['st_Documento']=='') ){
	$membresiaLink = "(Sin Membresia) - <a href='addMembresia.php?idusuarioweb=".$id_UsuarioWeb."'> Adquirir Membresia</a>";
	$_SESSION["descuento"] = 0;
}else{
	$membresiaLink = "Membres&iacute;a: ( ".$rowdata['st_Documento']." )";	
	$_SESSION["descuento"] = 1;
}

//DESCUENTO FORZADO 
if( $_SESSION["id_Sucursal"] == 9 || $_SESSION["id_Sucursal"] == 11 || $_SESSION["id_Sucursal"] == 12 || $_SESSION["id_Sucursal"] == 14 || $_SESSION["id_Sucursal"] == 15 ){
	$_SESSION["descuento"] = 1;
}

//ONTENEMOS LA CANTIDAD  DE DINERO  EN EL TICEKT DE SESSION
require("moneysession.php");
//////////
///////////
?>                
                            

				<div id="datosPaciente"></div>
                
                
                	<br>
                    <br>
                    <a href="historia0.php?idusuarioweb=<?=$id_UsuarioWeb?>"><img src="../images/icosapps/Medical-invoice-information-48.png" width="48" height="48" border="0" /> 
                    EXAMEN DE LA VISTA</a><br>
                
                </div>
              </div>
              <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text"><img src="../images/icosapps/Secure-payment-48.png" width="48" height="48" /> 
                  Pagos/Abonos 
                  <input name="v2" type="checkbox" id="v2" value="1" checked="checked" />
                </div>
              </div>
              <div class="DIVmod"> 
                <div class="DIVpadding"> 
                  <div align="right"></div>
                  <div align="right"> </div>
                  <a href="historialpagos.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=NA"> 
                  Ver historial de pagos<br />
                  </a><a href="registrarpagos.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=NA">Ingresar 
                  nuevo pago <br />
                  </a> 
                  <?	$query = " SELECT top 3   tbl_PagosUsuarioWeb.id_PagoUsuarioWeb, cat_ConceptosPagos.st_Concepto, tbl_PagosUsuarioWeb.dt_FechaRegistro, tbl_PagosUsuarioWeb.id_EventoConcepto, 
                      tbl_PagosUsuarioWeb.i_Cantidad, cat_ConceptosPagos.id_Concepto
FROM         tbl_PagosUsuarioWeb INNER JOIN
                      cat_ConceptosPagos ON tbl_PagosUsuarioWeb.id_Concepto = cat_ConceptosPagos.id_Concepto
WHERE     (tbl_PagosUsuarioWeb.id_UsuarioWeb = ".$id_UsuarioWeb.") and cat_ConceptosPagos.id_Concepto in (8,9)
ORDER BY tbl_PagosUsuarioWeb.dt_FechaRegistro DESC";
	$rquery =  mssql_query($query);
	$numrows = mssql_num_rows($rquery );
	if($numrows > 0) echo "<br>Ultimos ".$numrows." pagos<br><ul>";
	while($rowcompras = mssql_fetch_array($rquery)) {
					?>
                  <ul>
                    <li> <a href="historialpagos.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_PagoUsuarioWeb=<?=$rowcompras['id_PagoUsuarioWeb']?>"> 
                      <?=$rowcompras['dt_FechaRegistro']?>
                      <br>
                      <?=$rowcompras['st_Concepto']?>
                      $ 
                      <?=$rowcompras['i_Cantidad']?>
                      </a></li>
                  </ul>
                  <? }
						if($numrows > 0) echo "</ul>";

					?>
                </div>
                <div class="DIVmod_footer" > 
                  <div style="display:none;" id="div8" class="DIVmod_footer_hide"> 
                    La busqueda puede ser por nombre de cliente , apellidos ,email 
                    ,por numero de cedula , o numero de cita. </div>
                </div>
              </div>
              
              <!-- Familiares Referidos
              <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text"><img src="../images/icosapps/Friendster-48.png" width="48" height="48" /> 
                  Familiares/Referidos </div>
              </div>
              <div class="DIVmod"> 
                <div class="DIVpadding"> 
                  <div align="left"> <BR>
                    <a href="javascript:Abrir_ventanas('checkinstep3.php?idusuarioweb=<?=$id_UsuarioWeb?>') "> 
                    <?php
			if(!$rowdata['st_Documento']||($rowdata['st_Documento']==' ') ||($rowdata['st_Documento']=='SIN MEMEBRESIA')||($rowdata['st_Documento']=='null') ||($rowdata['st_Documento']=='') ){
				}else{
			
			  ?>
                    Agregar familiar/referido a membresia</a><br />
                    <a href>Ver Familiares/referidos </a> 
                    <?
			 }
		     ?>
                  </div>
                </div>
                <div class="DIVmod_footer" > 
                  <div class="DIVmod_footer_text"><a  href="#third" onClick="expansor('first', 'expansor1');"></a></div>
                  <div id="div7"> 
                    <div style="display:none;" id="div8" class="DIVmod_footer_hide"> 
                      La busqueda puede ser por nombre de cliente , apellidos 
                      ,email ,por numero de cedula , o numero de cita. </div>
                  </div>
                </div>
              </div>
              
              -->
              
              
              <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
              <span class="Estilo7"><img src="../images/icosapps/Gnome-Document-Print-Preview-48.png" width="48" height="48" /> 
              <a href="#NONE" onclick="EnviarVer()">VISTA PREVIA CARNET GENERAL</a><br />
              <img src="../images/icosapps/Gnome-Document-Print-48.png" width="48" height="48" /> 
              <a href="#NONE" onclick="Enviar()">IMPRIMIR CARNET GENERAL</a></span> 
            </div>
        
            <div class="DIVcenter"> 
              <!-- CENTER/MEDIO -->
            <BR>
          <div id="dinerosession">     <img src="../images/icosapps/Create-ticket-64.png" /> <a class="linkPreticket" href="#NONE" onclick="javascript:generaPreticket();"> 
              TICKET UNICO SESION ($
              <?=number_format($dinerosession,2,'.',',');?>
              )</a> <img src="../images/icRefresh.gif" width="13" height="16" border="0" onclick="javascript:changeAjax('ajaxmoney.php', 'sucursal', 'dinerosession');" id="sucursal" />
            </div>  <BR>
            <!-- new -->
                 <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
                <div class="DIVmod_header_text">Historia Clinica Optica</div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                <div align="right"> <img src="../images/icUsers.gif" width="48" height="32" /></div>
				
				  <? if($idevento>0) {?>
                  <a href="indexHomeHistoria.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$idevento?>" target="contenedor"><img src="../images/iCheck.gif" width="14" height="18" />Iniciar 
                  consulta<br />
                   <br /> </a>
                <?php } else {?>  
                <?php /*?><a href="indexHomeHistoria2.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$idevento?>"><?php */?>
                
                <a href="detallepacienteHistoria.php?idusuarioweb=<?=$id_UsuarioWeb?>">
                <img src="../images/iCheck.gif" width="14" height="18" />Ver Informacion <br /> <br /> </a>  <?php }


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
            
        <!--new    -->
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
                <div class="DIVmod_header_text"><img src="../images/icosapps/Calendar-64.png" width="48" height="48" /> 
                  CITAS
                  <input name="v3" type="checkbox" id="v3" value="1" checked="checked" />
                </div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                  <div align="right"> </div>
            
                  <a href="javascript:Abrir_ventana('../cac/formCitaUsers.php?idusuarioweb=<?=$id_UsuarioWeb?>')"> 
                  Agendar cita<br>
                  </a>
                 <a href="detallecitas.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=NA">Ver todas</a><br />
                   <br />
			 
			 <?
  $querydelect = " SELECT   top 3   DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWebTotal.dt_FechaCita) AS mincitas, fernandoruiz.cat_TipoCita.st_TipoCita, fernandoruiz.cat_TipoCita.id_TipoCita,
                      fernandoruiz.cat_SucursalClinica.st_Nombre, tbl_EvCitasUsuariosWebTotal.dt_FechaRegistro, tbl_EvCitasUsuariosWebTotal.dt_FechaCita, 
                      tbl_EvCitasUsuariosWebTotal.id_UsuarioWeb, tbl_EvCitasUsuariosWebTotal.id_Evento, tbl_EvCitasUsuariosWebTotal.id_StatusCita, 
                      fernandoruiz.cat_StatusCita.st_StatusCita, fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_StatusTerapia, 
                      fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_StatusPago
FROM         tbl_EvCitasUsuariosWebTotal INNER JOIN
                      fernandoruiz.cat_TipoCita ON tbl_EvCitasUsuariosWebTotal.id_TipoCita = fernandoruiz.cat_TipoCita.id_TipoCita INNER JOIN
                      fernandoruiz.cat_SucursalClinica ON tbl_EvCitasUsuariosWebTotal.id_Sucursal = fernandoruiz.cat_SucursalClinica.id_SucursalClinica INNER JOIN
                      fernandoruiz.cat_StatusCita ON tbl_EvCitasUsuariosWebTotal.id_StatusCita = fernandoruiz.cat_StatusCita.id_StatusCita LEFT OUTER JOIN
                      fernandoruiz.tbl_RecetasTerapiaUsuarioWeb ON tbl_EvCitasUsuariosWebTotal.id_Evento = fernandoruiz.tbl_RecetasTerapiaUsuarioWeb.id_EventoConsulta

WHERE     (tbl_EvCitasUsuariosWebTotal.id_UsuarioWeb = ".$id_UsuarioWeb.") and tbl_EvCitasUsuariosWebTotal.id_tipoCita = 5
ORDER BY tbl_EvCitasUsuariosWebTotal.dt_FechaCita DESC";
$rquerydelect =    mssql_query($querydelect);
while($rowCiTas = mssql_fetch_array($rquerydelect)){
			 ?><br><br>
  <a href="../medicalcenter/detallepacienteHistoria.php?idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$rowCiTas['id_Evento']?>">
                <?=$rowCiTas['st_TipoCita']?>  /   <?=$rowCiTas['dt_FechaCita']?><br>
				<?=$rowCiTas['st_StatusCita']?><br>
				<?=$rowCiTas['dt_FechaRegistro']?>
				
                  </a><? 
			  
				  if ($rowCiTas['mincitas']  > 0) { ?>
				  
				  <? if($rowCiTas['id_StatusCita'] != 4) {
				   if($rowCiTas['id_StatusCita'] == 1) {?>
                  <input type="button" name="Button" value="Editar"    onclick="javascript:Abrir_ventana('../editarcita.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>&amp;idevento=<?=$rowCiTas['id_Evento']?>')" />
                   <? } 
				  
				  if($rowCiTas['id_StatusCita'] <> 7) {
				  
				  ?>
				  </span>
                  <input type="button" name="Submit3" value="cancelar"   onclick="javascript:Abrir_ventana('../cancelacita.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>&idevento=<?=$rowCiTas['id_Evento']?>')"  />
                  <?  
				
				}
				
				
				}elseif($rowCiTas['id_StatusCita'] == 4) {
				
				 if($rowCiTas['id_TipoCita'] == 1) {
				 ?>
				 
			<input type="button" name="Submit3" value="Reagendar"   onclick="javascript:Abrir_ventana('../cac/Reagendar.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>&idevento=<?=$rowCiTas['id_Evento']?>')"  />	
				 <? }
				 
				
				}
				
				 } if ($rowCiTas['mincitas']  < 0)  { 
				 if($rowCiTas['id_StatusCita'] == 1||$rowCiTas['id_StatusCita'] == 4||$rowCiTas['id_StatusCita']== 5) {
				 
				  if($rowCiTas['id_TipoCita'] == 1) {
				 ?>
				 <input type="button" name="Submit3" value="Reagendar"   onclick="javascript:Abrir_ventana('Reagendar.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>&idevento=<?=$rowCiTas['id_Evento']?>')"  />	
				 <?php
				 }
				 
				 }
				
				
					
				 }   
				 
				 if($rowCiTas['id_StatusCita'] <> 7) {
				  
				 if($rowCiTas['id_StatusCita'] <> 2) {
				 
				  if($rowCiTas['id_StatusCita'] <> 4) { 
				  ?>
                 
        
                  <?php }} }?>
                  <?  }
				
				   ?>
                </div>
              <div class="DIVmod_footer" >
                <div class="DIVmod_footer_text"><a  href="#third" onClick="expansor('first', 'expansor1');"></a></div>
                <div id="div">
                  <div style="display:none;" id="div2" class="DIVmod_footer_hide"> La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita. </div>
                </div>
              </div>
            </div>
            
            <!--Porductos
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
                <div class="DIVmod_header_text"><img src="../images/icosapps/medical_pot_pills-48.png" width="48" height="48" />Formulacion/ 
                  (Productos) 
                  <input name="v4" type="checkbox" id="v4" value="1" checked="checked" />
                </div>
            </div>
            <div class="DIVmod">
              <div class="DIVpadding">
                  <div align="left"> <a href="javascript:Abrir_ventana('detallepacienterecetar.php?i_Consultorio=0&idusuarioweb=<?=$id_UsuarioWeb?>')">Nueva 
                    compra</a> <br>
					<a href="detallecompraspharma.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=NA">
					Ver todas las compras</a><br>
					<?
			  $query = " SELECT     TOP (3) tbl_EvVentaRecetaUsuariosWeb.id_Evento, tbl_EvVentaRecetaUsuariosWeb.id_UsuarioWeb, tbl_EvVentaRecetaUsuariosWeb.id_Receta, 
                      tbl_EvVentaRecetaUsuariosWeb.id_Operador, tbl_EvVentaRecetaUsuariosWeb.dt_FechaRegistro, tbl_EvVentaRecetaUsuariosWeb.id_OrigenRegistro, 
                      tbl_EvVentaRecetaUsuariosWeb.id_Sucursal, tbl_PagosUsuarioWeb.i_Cantidad
FROM         tbl_EvVentaRecetaUsuariosWeb INNER JOIN
                      tbl_PagosUsuarioWeb ON tbl_EvVentaRecetaUsuariosWeb.id_Evento = tbl_PagosUsuarioWeb.id_EventoConcepto
WHERE     (tbl_EvVentaRecetaUsuariosWeb.id_UsuarioWeb = ".$id_UsuarioWeb.") and ( tbl_EvVentaRecetaUsuariosWeb.id_Tipo = 1)
ORDER BY tbl_EvVentaRecetaUsuariosWeb.id_Evento DESC ";
	$rquery =  mssql_query($query);
	$numrows = mssql_num_rows($rquery );
	if($numrows > 0) echo "<br>Ultimas ".$numrows." compras<br><ul>";
	while($rowcompras = mssql_fetch_array($rquery)) {
					?><li><a href="detallecompraspharma.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_EventoVenta=<?=$rowcompras['id_Evento']?>"> <?=$rowcompras['dt_FechaRegistro']?> / $ <?=$rowcompras['i_Cantidad']?></a></li>
					<? }
						if($numrows > 0) echo "</ul>";

					?>
					<br />
                  </div>
					 
                  <div align="right">  <br /><hr>
                    <strong>Productos recetados por medico</strong> <br>
                    <?php 

   $query = "	SELECT   top 3    tbl_EvCitasUsuariosWeb.id_Evento,tbl_RecetasUsuariosWeb.dt_FechaRegistro, tbl_ProductosReceta.productos, tbl_RecetasUsuariosWeb.id_UsuarioWeb, 
                      tbl_RecetasUsuariosWeb.id_Receta, ISNULL(tbl_ProductosRecetaVentas.productos, 0) AS productosventas, cat_TipoCita.st_TipoCita, 
                      isnull(tbl_ProductosRecetaPendientes.productos,0) AS productospendientes
FROM         tbl_RecetasUsuariosWeb INNER JOIN
                      tbl_EvCitasUsuariosWeb ON tbl_RecetasUsuariosWeb.id_EventoCita = tbl_EvCitasUsuariosWeb.id_Evento INNER JOIN
                      cat_TipoCita ON tbl_EvCitasUsuariosWeb.id_TipoCita = cat_TipoCita.id_TipoCita LEFT OUTER JOIN
                      tbl_ProductosRecetaPendientes ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosRecetaPendientes.id_Receta LEFT OUTER JOIN
                      tbl_ProductosRecetaVentas ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosRecetaVentas.id_Receta LEFT OUTER JOIN
                      tbl_ProductosReceta ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosReceta.id_Receta
WHERE     (tbl_RecetasUsuariosWeb.id_UsuarioWeb = '".$id_UsuarioWeb."')
AND (ISNULL(fernandoruiz.tbl_ProductosRecetaPendientes.productos, 0) > 0)
ORDER BY  tbl_RecetasUsuariosWeb.dt_FechaRegistro DESC ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){
// verificamos las unidades

  $queryunit = "Select SUM(i_CantidadVenta) AS  compras,sum(i_Cantidad) AS  recetados,sum(i_Precio) AS  roi from [tbl_RecetasUsuariosWeb] INNER JOIN
                   tbl_RecetaProductosUsuarioWeb  ON tbl_RecetasUsuariosWeb.id_Receta  = tbl_RecetaProductosUsuarioWeb.id_Receta
WHERE  id_EventoCita = ".$rowCiTas['id_Evento'];
$rqueryunit =  mssql_query($queryunit);
$rowdatacc = mssql_fetch_array($rqueryunit);
$faltan = $rowdatacc['recetados'] -$rowdatacc['compras'];
//calculmos  las $$
 $queryseldiner ="Select *  from tbl_RecetasUsuariosWeb INNER JOIN tbl_RecetaProductosUsuarioWeb ON tbl_RecetasUsuariosWeb.id_Receta = tbl_RecetaProductosUsuarioWeb.id_Receta WHERE id_EventoCita = ".$rowCiTas['id_Evento'];
$rqueryseldiner = mssql_query($queryseldiner);
$dinerorecetado = 0;
$dinerorecetadopre = 0;
$dinerocomprado =0;
$dinerocompradopre =0;
$dinerofaltante =0;
while($rowdiner = mssql_fetch_array($rqueryseldiner)){
$dinerorecetadopre = $rowdiner['i_Cantidad']  * $rowdiner['i_Precio'];
$dinerorecetado=$dinerorecetadopre+$dinerorecetado;
$dinerocompradopre = $rowdiner['i_CantidadVenta']  * $rowdiner['i_Precio'];
$dinerocomprado = $dinerocompradopre+$dinerocomprado;

}
$dinerofaltante = $dinerorecetado -$dinerocomprado;

?>
                    <br />
                    <img src="../images/iPassed.png" width="12" height="12" /> 
                    Tipo Cita : 
                    <?=$rowCiTas['st_TipoCita']?>
                    <?=$rowCiTas['dt_FechaRegistro']?>
						<? 
						$url = "detallepacienterecetalist.php?";
					
					if($faltan==0 ){ 
					
				$url = "detallerecetaproductos.php?";
						$queryupdate = "UPDATE    tbl_RecetasUsuariosWeb
SET              i_Terminado = 1
WHERE     (id_Receta = ".$rowCiTas['id_Receta'].") and    i_Terminado = 0";
$rqueryupdate = mssql_query($queryupdate);

					}	

					
					?>
                    <a href="javascript:Abrir_ventana('<?=$url?>idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$rowCiTas['id_Evento']?>&idreceta=<?=$rowCiTas['id_Receta']?>')"> 
                    <br />
                    ( 
                    <?=$rowCiTas['productos']?>
                    productos formulados <br />
                    <?=$rowdatacc['recetados']?>
                    unidades $ 
                    <?=$dinerorecetado?>
                    ) <br />
                    <br />
                    ( 
                    <?=$rowCiTas['productosventas']?>
                    productos comprados <br />
                    <?=$rowdatacc['compras']?>
                    unidades $ 
                    <?=$dinerocomprado?>
                    ) <br />
                    <br />
                    ( 
                    <?=$rowCiTas['productospendientes']?>
                    productos pendientes<br />
                    <?=$faltan ?>
                    unidades pendientes $ 
                    <?=$dinerofaltante?>
                    )</a> 
                    <hr />
                    <?
 } 
?>
                    <?php 

$query = "	SELECT    top 3   tbl_EvCitasUsuariosWebpast.id_Evento,tbl_RecetasUsuariosWeb.dt_FechaRegistro, tbl_ProductosReceta.productos, tbl_RecetasUsuariosWeb.id_UsuarioWeb, 
                      tbl_RecetasUsuariosWeb.id_Receta, ISNULL(tbl_ProductosRecetaVentas.productos, 0) AS productosventas, cat_TipoCita.st_TipoCita, 
                      isnull(tbl_ProductosRecetaPendientes.productos,0) AS productospendientes
FROM         tbl_RecetasUsuariosWeb INNER JOIN
                      tbl_EvCitasUsuariosWebpast ON tbl_RecetasUsuariosWeb.id_EventoCita = tbl_EvCitasUsuariosWebpast.id_Evento INNER JOIN
                      cat_TipoCita ON tbl_EvCitasUsuariosWebpast.id_TipoCita = cat_TipoCita.id_TipoCita LEFT OUTER JOIN
                      tbl_ProductosRecetaPendientes ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosRecetaPendientes.id_Receta LEFT OUTER JOIN
                      tbl_ProductosRecetaVentas ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosRecetaVentas.id_Receta LEFT OUTER JOIN
                      tbl_ProductosReceta ON tbl_RecetasUsuariosWeb.id_Receta = tbl_ProductosReceta.id_Receta
WHERE     (tbl_RecetasUsuariosWeb.id_UsuarioWeb = '".$id_UsuarioWeb."')
AND (ISNULL(fernandoruiz.tbl_ProductosRecetaPendientes.productos, 0) > 0)
ORDER BY  tbl_RecetasUsuariosWeb.dt_FechaRegistro DESC ";
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){
// verificamos las unidades

  $queryunit = "Select SUM(i_CantidadVenta) AS  compras,sum(i_Cantidad) AS  recetados,sum(i_Precio) AS  roi from [tbl_RecetasUsuariosWeb] INNER JOIN
                   tbl_RecetaProductosUsuarioWeb  ON tbl_RecetasUsuariosWeb.id_Receta  = tbl_RecetaProductosUsuarioWeb.id_Receta
WHERE  id_EventoCita = ".$rowCiTas['id_Evento'];
$rqueryunit =  mssql_query($queryunit);
$rowdatacc = mssql_fetch_array($rqueryunit);
$faltan = $rowdatacc['recetados'] -$rowdatacc['compras'];
//calculmos  las $$
 $queryseldiner ="Select *  from tbl_RecetasUsuariosWeb INNER JOIN tbl_RecetaProductosUsuarioWeb ON tbl_RecetasUsuariosWeb.id_Receta = tbl_RecetaProductosUsuarioWeb.id_Receta WHERE id_EventoCita = ".$rowCiTas['id_Evento'];
$rqueryseldiner = mssql_query($queryseldiner);
$dinerorecetado = 0;
$dinerorecetadopre = 0;
$dinerocomprado =0;
$dinerocompradopre =0;
$dinerofaltante =0;
while($rowdiner = mssql_fetch_array($rqueryseldiner)){
$dinerorecetadopre = $rowdiner['i_Cantidad']  * $rowdiner['i_Precio'];
$dinerorecetado=$dinerorecetadopre+$dinerorecetado;
$dinerocompradopre = $rowdiner['i_CantidadVenta']  * $rowdiner['i_Precio'];
$dinerocomprado = $dinerocompradopre+$dinerocomprado;

}
$dinerofaltante = $dinerorecetado -$dinerocomprado;

?>
                    <br />
                    <img src="../images/iPassed.png" width="12" height="12" /> 
                    Tipo Cita : 
                    <?=$rowCiTas['st_TipoCita']?>
                    <?=$rowCiTas['dt_FechaRegistro']?>
					<? 
						$url = "detallepacienterecetalist.php?";
					
					if($faltan==0 ){ 
					
				$url = "detallerecetaproductos.php?";
						$queryupdate = "UPDATE    tbl_RecetasUsuariosWeb
SET              i_Terminado = 1
WHERE     (id_Receta = ".$rowCiTas['id_Receta'].") and    i_Terminado = 0";
$rqueryupdate = mssql_query($queryupdate);

					}	

					
					?>
                    <a href="javascript:Abrir_ventana('<?=$url?>?idusuarioweb=<?=$id_UsuarioWeb?>&amp;idevento=<?=$rowCiTas['id_Evento']?>&amp;idreceta=<?=$rowCiTas['id_Receta']?>')"> 
                    <br />
                    ( 
                    <?=$rowCiTas['productos']?>
                    productos formulados <br />
                    <?=$rowdatacc['recetados']?>
                    unidades $ 
                    <?=$dinerorecetado?>
                    ) <br />
                    <br />
                    ( 
                    <?=$rowCiTas['productosventas']?>
                    productos comprados <br />
                    <?=$rowdatacc['compras']?>
                    unidades $ 
                    <?=$dinerocomprado?>
                    ) <br />
                    <br />
                    ( 
                    <?=$rowCiTas['productospendientes']?>
                    productos pendientes<br />
                    <?=$faltan ?>
                    unidades pendientes $ 
                    <?=$dinerofaltante?>
                    )</a> 
                    <hr />
                    <?
 } 
?>
                  </div>
				</div>
              <div class="DIVmod_footer" >
                <div class="DIVmod_footer_text"><a  href="#third" onClick="expansor('first', 'expansor1');"></a></div>
                <div id="div3">
                  <div style="display:none;" id="div4" class="DIVmod_footer_hide"> La busqueda  puede ser por  nombre de  cliente , apellidos ,email   ,por  numero de cedula , o  numero de cita. </div>
                </div>
              </div>
            </div>
            -->
            
            <!--
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>  
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text"><img src="../images/icosapps/Mallette-48.png" width="48" height="48" /> 
                  Terapias 
                  <input name="v5" type="checkbox" id="v5" value="1" checked="checked" />
                </div>
              </div>
              <div class="DIVmod"> 
                <div class="DIVpadding"> 
                  <div align="left"> 
				 <a href="javascript:Abrir_ventana('detallepacienterecetarterapias.php?i_Consultorio=0&idusuarioweb=<?=$id_UsuarioWeb?>')">Nueva 
                    compra</a><br />
                    <a href="detallecomprasterapias.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_VentaProductosUsuarioWeb=NA"> 
                    Ver todas las compras</a><br />
                    <?
				 	$query = "  SELECT  top  3   tbl_EvVentaProductosRecetaUsuariosWeb.dt_FechaRegistro, tbl_EvVentaProductosRecetaUsuariosWeb.i_Precio, 
                      tbl_EvVentaProductosRecetaUsuariosWeb.id_VentaProductosUsuarioWeb, 
					  tbl_SaldoFinalTerapias.i_Saldo
FROM         tbl_EvVentaRecetaUsuariosWeb INNER JOIN
                      tbl_EvVentaProductosRecetaUsuariosWeb ON tbl_EvVentaRecetaUsuariosWeb.id_Evento = tbl_EvVentaProductosRecetaUsuariosWeb.id_EventoVenta INNER JOIN
                      tbl_SaldoFinalTerapias ON 
                      tbl_EvVentaProductosRecetaUsuariosWeb.id_VentaProductosUsuarioWeb = tbl_SaldoFinalTerapias.id_VentaProductosUsuarioWeb

WHERE     (tbl_EvVentaRecetaUsuariosWeb.id_Tipo = 2) and
(terapiasfaltanreceta.restantes >0)
 AND (tbl_EvVentaRecetaUsuariosWeb.id_UsuarioWeb = ".$id_UsuarioWeb.")
ORDER BY tbl_EvVentaRecetaUsuariosWeb.dt_FechaRegistro DESC  ";
	$rquery =  mssql_query($query);
	$numrows = mssql_num_rows($rquery );
	if($numrows > 0) echo "<br>Ultimas ".$numrows." compras<br><ul>";
	while($rowcompras = mssql_fetch_array($rquery)) {
					?>
                    <li><a href="detallecomprasterapias.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_VentaProductosUsuarioWeb=<?=$rowcompras['id_VentaProductosUsuarioWeb']?>"> 
                     <? if($rowcompras['i_Saldo']>0.01) {
					 $saldo =$rowcompras['i_Saldo']
					  ?><font color="#990000"><? }
					  else   $saldo = 0;
					   ?> <?=$rowcompras['dt_FechaRegistro']?>
                      <br>
                      $ 
                      <?=$rowcompras['i_Precio']?>
                      / Saldo:  
                      <?=$saldo?>
                      </font> </a></li>
                    <? }
						if($numrows > 0) echo "</ul>";

					?>
                    <br /><hr>
                    <strong>Terapias recetadas por medico</strong> 
                    <?
				  $querypaq = "SELECT     TOP (3) COUNT(tbl_RecetaPaqueteTerapiasProductosUsuarioWeb.id_RecetaProductosUsuarioWeb) AS tot, tbl_RecetasPaquetesTerapiasUsuariosWeb.id_Receta, 
                      tbl_RecetasPaquetesTerapiasUsuariosWeb.id_EventoCita, tbl_RecetasPaquetesTerapiasUsuariosWeb.dt_FechaRegistro, terapiasfaltanreceta.restantes
FROM         tbl_RecetasPaquetesTerapiasUsuariosWeb INNER JOIN
                      tbl_RecetaPaqueteTerapiasProductosUsuarioWeb ON 
                      tbl_RecetasPaquetesTerapiasUsuariosWeb.id_Receta = tbl_RecetaPaqueteTerapiasProductosUsuarioWeb.id_Receta INNER JOIN
                      terapiasfaltanreceta ON tbl_RecetasPaquetesTerapiasUsuariosWeb.id_Receta = terapiasfaltanreceta.id_Receta

WHERE     (tbl_RecetasPaquetesTerapiasUsuariosWeb.id_UsuarioWeb = ".$id_UsuarioWeb.")
 AND (tbl_RecetasPaquetesTerapiasUsuariosWeb.i_Consultorio = 1) AND 
                      (tbl_RecetaPaqueteTerapiasProductosUsuarioWeb.i_Consultorio = 1)
GROUP BY tbl_RecetasPaquetesTerapiasUsuariosWeb.id_Receta, tbl_RecetasPaquetesTerapiasUsuariosWeb.id_EventoCita, 
                      tbl_RecetasPaquetesTerapiasUsuariosWeb.dt_FechaRegistro, terapiasfaltanreceta.restantes
ORDER BY tbl_RecetasPaquetesTerapiasUsuariosWeb.dt_FechaRegistro DESC  ";
					  $rquerypaq = mssql_query($querypaq);
					  while($rowdata = mssql_fetch_array($rquerypaq)){
					     $url = "detallepacienterecetalistterapias.php?";
						 
					  if($rowdata['restantes']==0){
					  $queryupdate = "UPDATE    tbl_RecetasPaquetesTerapiasUsuariosWeb
SET              i_Terminado = 1
WHERE     (id_Receta = ".$rowdata['id_Receta'].")  and  i_Terminado = 0";
$rqueryupdate = mssql_query($queryupdate);
					  $url = "detallerecetaterapia.php?";

					
					   
					    }
					  ?>
                    <br>
                  <br> <a href="javascript:Abrir_ventana('<?=$url?>idusuarioweb=<?=$id_UsuarioWeb?>&idevento=<?=$rowdata['id_EventoCita']?>&idreceta=<?=$rowdata['id_Receta']?>')"> 
				    <?=$rowdata['dt_FechaRegistro']?>  /Paquetes recetados:<?=$rowdata['tot']?> <br>
                    Faltan: 
                    <?=$rowdata['restantes']?>
                    </a> 
                    <? }
				  
				  ?>
                  </div>
                  <div align="right"> </div>
                </div>
                <div class="DIVmod_footer" > 
                  <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                  <div id="div3"> 
                    <div style="display:none;" id="div4" class="DIVmod_footer_hide"> 
                      La busqueda puede ser por nombre de cliente , apellidos 
                      ,email ,por numero de cedula , o numero de cita. </div>
                  </div>
                </div>
              </div>
              -->
              
              <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text"><img src="../images/optica.png" height="48" /> 
                  SERVICIOS 
                  <input name="v6" type="checkbox" id="v6" value="1" checked="checked" />
                </div>
              </div>
              <div class="DIVmod"> 
                <div class="DIVpadding"> 
                  <div align="left"> <br />
                    <!-- <a href="Optica/doOptica.php?idusuarioweb=<?=$id_UsuarioWeb?>"> 
                  <img src="../images/iCheck.gif" width="14" height="18" />Generar 
                  nueva presupuesto</a> <br />
                    <strong>OPTICA</strong><br>
                    <r> <a href="javascript:Abrir_ventana('detallepacienteOpticaDo.php?i_Consultorio=0&idusuarioweb=<?=$id_UsuarioWeb?>')"> 
                    Generar nuevo presupuesto<img src="../images/iCheck.gif" width="14" height="18" /></a> 
                    <br>
                     <a href="detallecomprasoptica.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_VentaProductosUsuarioWeb=NA"> 
                    Ver todas las compras</a>   <br />-->
                    
                    <strong>OPTICA</strong></div>
                </div>   
                <a href="javascript:Abrir_ventana('detallepacienteOpticaDo_Test.php?i_Consultorio=0&idusuarioweb=<?=$id_UsuarioWeb?>')">
                <img src="../images/iCheck.gif" width="14" height="18" />Generar 
                nuevo presupuesto</a><br> <a href="detallecomprasoptica.php?idusuarioweb=<?=$id_UsuarioWeb?>&id_VentaProductosUsuarioWeb=NA"> 
                    Ver todas las compras</a>   <br />
                
                <? if($operador == 1127){ ?>
                <a href="listaCotizacionesPaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>">Consultar cotizaciones</a>
                <br />
                <? }?>
                
                
            <br>    <div class="DIVmod_footer" > 
                  <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                  <div id="div"> 
                    <div style="display:none;" id="div2" class="DIVmod_footer_hide"> 
                      La busqueda puede ser por nombre de cliente , apellidos 
                      ,email ,por numero de cedula , o numero de cita. </div>
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
</table></form>
</body>
</html>
<?php mssql_close(); ?>
