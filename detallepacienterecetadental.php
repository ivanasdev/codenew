<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$id_UsuarioWeb = $_GET["idusuarioweb"];
  $id_cotizacionDental=$_GET['id_cotizacionDental'];


//obtenemos el detalle  de  la  cotizacion
$query="SELECT     fernandoruiz.tbl_CotizacionDental.id_cotizacionDental,
 fernandoruiz.tbl_CotizacionDental.id_UsuarioWeb, fernandoruiz.tbl_CotizacionDental.st_Descripcion, 
                      fernandoruiz.tbl_CotizacionDental.dt_FechaRegistro,
					   fernandoruiz.tbl_CotizacionDental.id_operador,
					    fernandoruiz.tbl_CotizacionDental.id_Status, 
                      fernandoruiz.tbl_CotizacionDental.id_EventoCita,
					   fernandoruiz.tbl_CotizacionDental.i_Total, 
					   fernandoruiz.tbl_CotizacionDental.i_SubTotal, 
                      fernandoruiz.tbl_CotizacionDental.i_Iva, 
					  fernandoruiz.tbl_CotizacionDental.i_Sesiones, 
					  cat_StatusCotizacionDental.st_StatusCotizacionDental
FROM         fernandoruiz.tbl_CotizacionDental INNER JOIN
                      cat_StatusCotizacionDental ON fernandoruiz.tbl_CotizacionDental.id_Status = cat_StatusCotizacionDental.id_StatusCotizacionDental
WHERE     (fernandoruiz.tbl_CotizacionDental.id_cotizacionDental = ".$id_cotizacionDental.")";
$rquery= mssql_query($query);
$rowdata =  mssql_fetch_array($rquery);
 $i_Sesiones =$rowdata['i_Sesiones'];
   $id_Status =$rowdata['id_Status'];
   $st_StatusCotizacionDental=$rowdata['st_StatusCotizacionDental'];


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
function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron = /[0123456789]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
function validar2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron = /[.0123456789]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
window.open(pagina,"",opciones);
}
</script>
<script type="text/javascript" src="../cac/scripts.js"></script>
<script type="text/javascript" src="../cac/expansor.js"></script>

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


</head>

<body>	
	<form name="encuesta" method="post"  action="dorecetadiente.php"   id="encuesta" >

<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <br>
  <tr>
    <td>

<div align="center">
    <div class="wrapper">
        
          <div class="DIVleft"> 
            <!-- CENTER/MEDIO -->
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header"> 
              <div class="DIVmod_header_text"> <img src="../images/icosapps/Temporary-tooth-48.png" width="48" height="48" /> 
                Compra de 
                <?=$rowdata['st_Nombre']." (".$rowdata['st_Documento'].")";?>
              </div>
            </div>
            <div class="DIVmod"> 
              <div class="DIVpadding"> 
              
              
			<br>
                Sesiones:
                <?=$i_Sesiones?>
                <input name="i_Sesiones" type="hidden" value="<?=$i_Sesiones?>" />
                <br>Status:<?=$st_StatusCotizacionDental?>
			<? 
			for($i=1;$i<=$i_Sesiones;$i++){
		 $query  = "SELECT     isnull(SUM(i_Precio),0) AS i_Precio, SUM(i_PrecioNoMiembro) AS i_PrecioNoMiembro
FROM         fernandoruiz.tbl_CotizacionDentalDetalle
WHERE     (id_CotizacionDental = ".$id_cotizacionDental.") AND (no_Sesion = ".$i.")
GROUP BY no_Sesion order by no_Sesion  "; 
$rquery  =  mssql_query($query);
$rowddd = mssql_fetch_array($rquery);
 $precio = $rowddd['i_Precio'];
?><hr>
                Sesion :
                <?=$i?>
             <br>   Precio miembro : $
            <?=number_format($rowddd['i_Precio'],2,'.',',');?>
			    <?  if($_SESSION["descuento"]==0) { ?>
 <br>Precio no miembro: $     <?=number_format($rowddd['i_PrecioNoMiembro'],2,'.',',');?>  
     <?   $precio = $rowddd['i_PrecioNoMiembro'];
	  }      ?>

                <?
				if($i==1)   $minimo =  $precio;
				$subtotal =   $precio + $subtotal;

}

 ?>
                <hr>
                <hr />
                <?
			 $queryselect =  "SELECT     tbl_CotizacionDentalDetalle.nt_Cantidad AS cantidad, cat_ServicioDental.st_ServicioDental, cat_ServicioDental.nt_Costo, cat_ServicioDental.nt_CostoNM, 
                      tbl_CotizacionDentalDetalle.id_diente,
					  tbl_CotizacionDentalDetalle.id_CotizacionDentalDetalle,
					   fernandoruiz.tbl_CotizacionDentalDetalle.no_Sesion
FROM         tbl_CotizacionDentalDetalle INNER JOIN
                      cat_ServicioDental ON tbl_CotizacionDentalDetalle.id_ServicioDental = cat_ServicioDental.id_ServicioDental
WHERE     (tbl_CotizacionDentalDetalle.id_CotizacionDental = ".$id_cotizacionDental.")
 order by   fernandoruiz.tbl_CotizacionDentalDetalle.no_Sesion
";
$rqueryselect =  mssql_query($queryselect);
echo "<ul>";
while($rowdata= mssql_fetch_array($rqueryselect)){
			echo "<li>Pieza :".$rowdata['id_diente']."<br>
Servicio:".$rowdata['st_ServicioDental']." <br>Sesion:".$rowdata['no_Sesion']."</li>";
 } 
 echo "</ul>";

  ?>
      <hr>
                <br />
                SUBTOTAL: $ 
                <?=number_format($subtotal-($subtotal*$iva),2,'.',',')?>
                <input name="i_SubTotal" type="hidden" id="i_SubTotal" value="<?=$subtotal-($subtotal*$iva)?>" />
                <input name="idusuarioweb" type="hidden" id="idusuarioweb" value="<?=$id_UsuarioWeb?>" />
                <input name="id_cotizacionDental" type="hidden" id="id_cotizacionDental" value="<?=$id_cotizacionDental?>" />
                <br />
                IVA: $  <?=number_format($subtotal*$iva,2,'.',',')?>
             
                <input name="i_Iva" type="hidden" id="i_Iva" value="<?=$subtotal*$iva?>" />
                <br />
                <strong>TOTAL: $ 
              <?=number_format($subtotal,2,'.',',')?>
                <input name="i_Total" type="hidden" id="i_Total" value="<?=$subtotal?>" />
                <br />
                <strong>A CUENTA: $ 
                <?=$sumasiva?>
                <input name="acuenta" type="text" id="acuenta" value="<?=$minimo?>"   onkeypress="return validar2(event)" />
                <input type="submit" name="Submit" value="Procesar a caja" />
                </strong> </strong></div>
             
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
</body></form>
</html>
<?php mssql_close(); ?>