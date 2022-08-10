<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php"); 


$receta  = $_GET["receta"];

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

$Nombre=$rowdata['st_Nombre'];
$ApellidoP=$rowdata['st_ApellidoPaterno'];
$st_docto=$rowdata['st_Documento'];
$queryselectEv =  "SELECT  *  FROM   tbl_ConsultaExamenMedico WHERE (id_Evento = '".$ideventocita."')";
$rqueryselectEv =  mssql_query($queryselectEv);
$rowdataEv= mssql_fetch_array($rqueryselectEv);

$Peso=$rowdataEv['st_Peso'];
$talla=$rowdataEv['st_Talla'];
$ta=$rowdataEv['st_Ta'];
$fc=$rowdataEv['st_Fc'];
$fr=$rowdataEv['st_Fr'];
$temp=$rowdataEv['st_To'];
$alergia=$rowdataEv['st_Observaciones'];
$idx=$rowdataEv['st_idx'];

$queryselectcita =  "SELECT     dt_ProximaCita
FROM    tbl_RecetaProductosUsuarioWeb
WHERE     (id_Receta = '".$idevento."')";
$rquerycita =  mssql_query($queryselectcita);
$rowdataCita= mssql_fetch_array($rquerycita);
$cita=$rowdataCita['dt_ProximaCita'];
 $eventoCuenta =  "SELECT     tbl_EvCitasUsuariosWeb.id_Medico, cat_Medicos.st_Nombre as nombreMEdico, cat_Medicos.st_RegistroMedico as registro
FROM         tbl_EvCitasUsuariosWeb INNER JOIN
                      cat_Medicos ON tbl_EvCitasUsuariosWeb.id_Medico = cat_Medicos.id_Medico
WHERE     (tbl_EvCitasUsuariosWeb.id_Evento =".$ideventocita.")  ";
$rqueryevento =  mssql_query($eventoCuenta);
$rowEvto= mssql_fetch_array($rqueryevento);
$medico= $rowEvto['nombreMEdico'];
$registro= $rowEvto['registro'];

?>
<html>
<head>
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />

<style type="text/css">

<!--
.Estilo9 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-size: 19px;
}
.Estilo18 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo19 {font-size: 11px}
.Estilo21 {font-size: 11px; font-weight: bold; }
.Estilo23 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo24 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
-->
</style>
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:692px;
	top:54px;
	width:640px;
	height:66px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	left:186px;
	top:266px;
	width:564px;
	height:24px;
	z-index:1;
}
#apDiv3 {
	position:absolute;
	left:1000px;
	top:198px;
	width:206px;
	height:22px;
	z-index:2;
}
#apDiv4 {
	position:absolute;
	left:179px;
	top:198px;
	width:290px;
	height:23px;
	z-index:3;
}
#apDiv5 {
	position:absolute;
	left:1000px;
	top:165px;
	width:205px;
	height:22px;
	z-index:4;
}
#apDiv6 {
	position:absolute;
	left:53px;
	top:316px;
	width:905px;
	height:317px;
	z-index:5;
}
#apDiv7 {
	position:absolute;
	left:1098px;
	top:318px;
	width:259px;
	height:19px;
	z-index:6;
}


#apDiv8 {
	position:absolute;
	left:127px;
	top:647px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv9 {
	position:absolute;
	left:1122px;
	top:561px;
	width:259px;
	height:19px;
	z-index:6;
}
#apDiv10 {
	position:absolute;
	left:1103px;
	top:528px;
	width:259px;
	height:19px;
	z-index:6;
}
#apDiv11 {
	position:absolute;
	left:1103px;
	top:495px;
	width:259px;
	height:19px;
	z-index:6;
}
#apDiv12 {
	position:absolute;
	left:1103px;
	top:460px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv13 {
	position:absolute;
	left:1103px;
	top:425px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv14 {
	position:absolute;
	left:1102px;
	top:389px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv15 {
	position:absolute;
	left:1099px;
	top:353px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv16 {
	position:absolute;
	left:1099px;
	top:282px;
	width:259px;
	height:19px;
	z-index:6;
}

#apDiv17 {
	position:absolute;
	left:821px;
	top:647px;
	width:190px;
	height:21px;
	z-index:6;
}
</style>

<!--<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />-->
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle reporte</title>

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
<?php
if($receta==1){

?>
<body > 

<input type="button" onClick="javascript:Impresion();" value="Imprimir Receta">
<?
}else{




?>
<body onLoad="javascript:Impresion();"> 

<?php

}
//aqui debe ir
$idevento=$_GET['idevento'];
$querys = "SELECT     id_TipoCita
FROM         tbl_EvCitasUsuariosWeb
WHERE     (id_Evento = '".$ideventocita."')";
$rquerys = mssql_query($querys);
$rowdata = mssql_fetch_array($rquerys); 
$rowdata['id_TipoCita'];


//if( $rowdata['id_TipoCita'] == 1 ){
$eventoCuenta =  "SELECT     COUNT(*) AS total
FROM         tbl_RecetaProductosUsuarioWeb INNER JOIN
                      dbo.cat_TipoProducto ON tbl_RecetaProductosUsuarioWeb.id_TipoProducto = dbo.cat_TipoProducto.id_TipoProducto INNER JOIN
                      tbl_RecetasUsuariosWeb ON tbl_RecetaProductosUsuarioWeb.id_Receta = tbl_RecetasUsuariosWeb.id_Receta 
					  
					  WHERE     (tbl_RecetasUsuariosWeb.id_Receta=  '".$idevento."')  ";
$rqueryevento =  mssql_query($eventoCuenta);
$rowEvto= mssql_fetch_array($rqueryevento);
$totalReparte= $rowEvto['total'];
$tablass=$totalReparte/5;
$tablas1x= round($tablass);
$tablasxZd=$tablas1x*5;
if($totalReparte>$tablasxZd){
$tablas=$tablas1x+1;
}else{
$tablas=round($tablass);
}
$cont=0;
$simple="";
for($i=1;$i<=$tablas;$i++){
$cont=$cont+1;
if($tablas==$cont){
$top1=$totalReparte-$top;
$top=$totalReparte;
}else{
$top=$top+5;
$top1=5;
}
?>

<div id="apDiv2"><font size="4" face="Arial, Helvetica, sans-serif"><?=$Nombre." ".$ApellidoP?> </font></div>


<div id="apDiv6"><font size="4" face="Arial, Helvetica, sans-serif">
   
    <?php 
$idevento=$_GET['idevento'];
$querys = "SELECT     id_TipoCita
FROM         tbl_EvCitasUsuariosWeb
WHERE     (id_Evento = '".$ideventocita."')";
$rquerys = mssql_query($querys);
$rowdata = mssql_fetch_array($rquerys); 






if($simple<>''||$simple<>' '){
			$total= "and dbo.cat_TipoProducto.st_NombreProducto not in (".$simple."'0')";
			}
	   $query = "SELECT     st_NombreProducto, st_Indicaciones, id_Receta, id_EventoCita, id_TipoProducto, id_RecetaProductosUsuarioWeb, i_Cantidad, i_Precio,Precio2,nombreMEdico,registro
FROM  (SELECT     TOP (".$top1.") st_NombreProducto, st_Indicaciones, id_Receta, id_EventoCita, id_TipoProducto, id_RecetaProductosUsuarioWeb, i_Cantidad, i_Precio,Precio2,nombreMEdico,registro
                       FROM   (SELECT     TOP (".$top.") dbo.cat_TipoProducto.st_NombreProducto, tbl_RecetaProductosUsuarioWeb.st_Indicaciones, dbo.tbl_RecetasUsuariosWeb.id_Receta, 
                      dbo.tbl_RecetasUsuariosWeb.id_EventoCita, dbo.cat_TipoProducto.id_TipoProducto, tbl_RecetaProductosUsuarioWeb.id_RecetaProductosUsuarioWeb, 
                      tbl_RecetaProductosUsuarioWeb.i_Cantidad, tbl_RecetaProductosUsuarioWeb.i_Precio, 
                      CASE tbl_RecetaProductosUsuarioWeb.i_Precio WHEN 0 THEN tbl_RecetaProductosUsuarioWeb.i_Precio + 1000000 ELSE tbl_RecetaProductosUsuarioWeb.i_Precio END
                       AS Precio2, tbl_RecetaProductosUsuarioWeb.id_Medico, cat_Medicos.st_Nombre as nombreMEdico, cat_Medicos.st_RegistroMedico as registro
FROM         tbl_RecetaProductosUsuarioWeb INNER JOIN
                      dbo.cat_TipoProducto ON tbl_RecetaProductosUsuarioWeb.id_TipoProducto = dbo.cat_TipoProducto.id_TipoProducto INNER JOIN
                      dbo.tbl_RecetasUsuariosWeb ON tbl_RecetaProductosUsuarioWeb.id_Receta = dbo.tbl_RecetasUsuariosWeb.id_Receta INNER JOIN
                      cat_Medicos ON tbl_RecetaProductosUsuarioWeb.id_Medico = cat_Medicos.id_Medico

                                               WHERE      (tbl_RecetasUsuariosWeb.id_Receta = '".$idevento."') ".$total."
                                               ORDER BY  Precio2 desc) AS X
                       ORDER BY  Precio2 ) AS t2
ORDER BY  Precio2 desc";
//echo $query;

$rquery =  mssql_query($query);
$total= 0;
echo "<table width='100%'>";
$bandera=0;
$bandera1=0;
$cuadre="";
$cuadre1="";

for($id=1;$id<=5;$id++){
//while($rowCiTas = mssql_fetch_array($rquery)){
$rowCiTas = mssql_fetch_array($rquery);
if($rowCiTas['st_NombreProducto']==''){
$precioPEP=1;
}else{
$precioPEP=$rowCiTas['i_Precio'];}

$subtotal = $rowCiTas['i_Cantidad'] * $rowCiTas['i_Precio'];
$total =  $total + $subtotal;
?><tr><td COLSPAN="2">
            <span class="Estilo24">
            <strong><?php
			if($precioPEP==0){
			if($bandera==0){
			echo "<br>MEDICAMENTOS PARA LA ENFERMEDAD PRIMARIA<br><br><br>";
			}
			$bandera=$bandera+1;
			}else{
			if($bandera1==0){
			echo "<br>MEDICAMENTOS PARA OTRAS ENFERMEDADES<br><br><br>";
			$bandera1=$bandera1+1;
			}
			}
			?></strong></span></td></tr>
<?php if($rowCiTas['st_NombreProducto']<>''){?>
			<tr><td>  <span class="Estilo24">
            <strong><img src="../images/iPassed.png" width="12" height="12" /></td><td><font size="2"> Cantidad :
              <?=$rowCiTas['i_Cantidad']?>
              / <a href="detalleprodcuto.php?idusuarioweb=<?=$rowCiTas['id_TipoProducto']?>">
                <?=$rowCiTas['st_NombreProducto']?>
                </a> </font><br /><font size='1'>
              Indicaciones
              <?=$rowCiTas['st_Indicaciones']?></font>
              <?
    echo "</strong></span></td></tr>";
}
$simple .="'".$rowCiTas['st_NombreProducto']."',";

 }
 
 
 
  


 
echo "</table>";
 ?>
 
 
 </font></div>
 
 
 <div id="apDiv3"><font size="4" face="Arial, Helvetica, sans-serif"><?=$registro?></font></div>
<div id="apDiv4"><font size="4" face="Arial, Helvetica, sans-serif"><?=$medico?></font></div>
<div id="apDiv5"><font size="4" face="Arial, Helvetica, sans-serif">&nbsp;</font></div>

 <div id="apDiv7"><font size="4" face="Arial, Helvetica, sans-serif">27</font></div>


<div id="apDiv8"><font size="4" face="Arial, Helvetica, sans-serif"><?=$idx?></font></div>
<div id="apDiv9"><font size="4" face="Arial, Helvetica, sans-serif"><?=$alergias?></font></div>
<div id="apDiv10"><font size="4" face="Arial, Helvetica, sans-serif"><?=$temp?></font></div>
<div id="apDiv11"><font size="4" face="Arial, Helvetica, sans-serif"><?=$fr?></font></div>
<div id="apDiv12"><font size="4" face="Arial, Helvetica, sans-serif"><?=$fc?></font></div>
<div id="apDiv13"><font size="4" face="Arial, Helvetica, sans-serif"><?=$ta?></font></div>
<div id="apDiv14"><font size="4" face="Arial, Helvetica, sans-serif"><?=$talla?></font></div>
<div id="apDiv15"><font size="4" face="Arial, Helvetica, sans-serif"><?=$peso?></font> </div>
<div id="apDiv16"><font size="4" face="Arial, Helvetica, sans-serif"><?=dateadd(date('m/d/Y h:i:s'),0,0,0,0,$mindiferencia,0)?></font></div>

<div id="apDiv17"><font size="4" face="Arial, Helvetica, sans-serif">09/02/2011</font></div>
 
<img src="images/recetario.jpg" width="1280" height="786" />

<?php

if($cont<>$tablas){

?>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

<?php

}
//}
}
?>


</body>
</html>
