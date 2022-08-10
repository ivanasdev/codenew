<?php
require("global.php");
$responsable = $operador;
$nuevos =  countreportes($responsable,0);
$listaresumereportnews =  listaresumereport($top,0,$responsable);
$listaresumerecriticos  =  listaresumerecriticos($top,$timeconfig,$responsable,1);
$countresumerecriticos  =  listaresumerecriticos($top,$timeconfig,$responsable,0);
$listaresumelist = listaresume($top,$responsable,1);
$listaresumecount = listaresume($top,$responsable,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
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
	height: 405px;
    }
</style></head>

<body style="background-color:transparent "> 
<div align="center" class="trans" style="z-index:1;filter:alpha(opacity=100);float:left;-moz-opacity:.60;opacity:.60"> 
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="97%" ><div align="center" class="telefonosOutbound"> 
          <div align="left">CITAS CHECK IN'S </div>
        </div></td>
      <td width="3%" >&nbsp;</td>
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
      <td> <div align="center"> 
          <div class="wrapper"> 
            <div class="DIVleft"> 
              <!-- CENTER/MEDIO -->
              <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
              <div class="DIVmod_header"> 
                <div class="DIVmod_header_text">CONSULTAS</div>
              </div>
              <div class="DIVmod"> 
                <div class="DIVpadding"> 
                  <table width="100%">
                    <tr> 
                      <td width="6%">&nbsp;</td>
                      <td><font size="3">Busqueda de citas (Turno nombre)</font></td>
                    </tr>
                    <tr> 
                      <td width="6%">&nbsp;</td>
                      <td> <form id="form1" name="form1" method="post" action="searchuser2.php">
                          <input name="searchtoken" type="text" id="searchtoken" size="40" />
                          <input type="submit" name="Submit2" value="Enviar" />
                          <input name="idtipo2" type="hidden" id="idtipo2" value="7" />
                        </form></td>
                    </tr>
                  </table>
                  <table width="100%" border="0">
                    <tr> 
                      <td width="83%"><strong> PROXIMO TURNO 
                        <script language="JavaScript1.2">

//Neon Lights Text II by G.P.F. (gpf@beta-cc.de)
//visit http://www.beta-cc.de
//Visit http://www.dynamicdrive.com for this script 

var message=">>>>!"
var neonbasecolor="gray"
var neontextcolor="red"
var neontextcolor2="#FFFFA8"
var flashspeed=200					// speed of flashing in milliseconds
var flashingletters=3						// number of letters flashing in neontextcolor
var flashingletters2=1						// number of letters flashing in neontextcolor2 (0 to disable)
var flashpause=0						// the pause between flash-cycles in milliseconds

///No need to edit below this line/////

var n=0
if (document.all||document.getElementById){
document.write('<font color="'+neonbasecolor+'">')
for (m=0;m<message.length;m++)
document.write('<span id="neonlight'+m+'">'+message.charAt(m)+'</span>')
document.write('</font>')
}
else
document.write(message)

function crossref(number){
var crossobj=document.all? eval("document.all.neonlight"+number) : document.getElementById("neonlight"+number)
return crossobj
}

function neon(){

//Change all letters to base color
if (n==0){
for (m=0;m<message.length;m++)
crossref(m).style.color=neonbasecolor
}

//cycle through and change individual letters to neon color
crossref(n).style.color=neontextcolor

if (n>flashingletters-1) crossref(n-flashingletters).style.color=neontextcolor2 
if (n>(flashingletters+flashingletters2)-1) crossref(n-flashingletters-flashingletters2).style.color=neonbasecolor


if (n<message.length-1)
n++
else{
n=0
clearInterval(flashing)
setTimeout("beginneon()",flashpause)
return
}
}

function beginneon(){
if (document.all||document.getElementById)
flashing=setInterval("neon()",flashspeed)
}
beginneon()


</script>
                        </strong></td>
                      <td width="17%"><div align="right"><img src="../images/icSecInicio.gif" width="56" height="40" /></div></td>
                    </tr>
                    <tr> 
                      <td colspan="2"> 
                        <?php




  $query = "	 SELECT   TOP 1   tbl_UsuariosWeb.id_UsuarioWeb, tbl_UsuariosWeb.st_Nombre, tbl_UsuariosWeb.st_ApellidoPaterno, tbl_EvCitasUsuariosWeb.dt_FechaCita, 
                      tbl_EvCitasUsuariosWeb.st_HoraCita, cat_StatusCita.st_StatusCita, tbl_EvCitasUsuariosWeb.id_Medico, tbl_EvCitasUsuariosWeb.id_Sucursal, 
                      tbl_EvCitasUsuariosWeb.dt_FechaRegistro, DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita)-$mindiferencia AS mincitas, 
                      cat_StatusCita.id_StatusCita  , tbl_EvCitasUsuariosWeb.id_Evento, cat_TipoCita.st_TipoCita

FROM         tbl_EvCitasUsuariosWeb INNER JOIN
                    cat_StatusCita ON tbl_EvCitasUsuariosWeb.id_StatusCita = cat_StatusCita.id_StatusCita INNER JOIN
                      tbl_UsuariosWeb ON tbl_EvCitasUsuariosWeb.id_UsuarioWeb = tbl_UsuariosWeb.id_UsuarioWeb INNER JOIN
                      cat_TipoCita ON tbl_EvCitasUsuariosWeb.id_TipoCita = cat_TipoCita.id_TipoCita
WHERE     (tbl_EvCitasUsuariosWeb.id_Medico = '".$id_Medico."') AND ((DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita)-$mindiferencia) <= 60) AND ((DATEDIFF(mi, 
                      GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita)-$mindiferencia) > - 90) AND  ( cat_StatusCita.id_StatusCita = 2)
ORDER BY tbl_EvCitasUsuariosWeb.dt_FechaCita ";
$rquery =  mssql_query($query);
$numrows = mssql_num_rows($rquery);
if($numrows >  0){
while($rowCiTas = mssql_fetch_array($rquery)){

?>
                        <div class="DIVmod_title"> <br>
                          <? if ($rowCiTas['id_StatusCita']  > 1) { ?>
                          <img src="../images/iPassed.png" width="12" height="12" />	
                          <? } else  { ?>
                          <img src="../images/iNoPassed.png" width="12" height="12" /> 
                          <? }  ?>
                          <a href="javascript:Abrir_ventana('../cac/detallecita.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>')"> 
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
                          minutos para la cita)</a> </div>
                        <a href="detallepaciente.php?varcontrol=1&idevento=<?=$rowCiTas['id_Evento']?>&idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>"><img src="../images/icGuiones.gif" width="32" height="32" />COMENZAR 
                        CONSULTA </a> 
                        <?
 }}else  { ?>
                        <img src="../images/respFail.gif" width="30" height="30" /><strong> 
                        NO HAY PACIENTES CON TURNO VIGENTE </strong> 
                        <?
 }?>
                      </td>
                    </tr>
                  </table>
                  <hr>
                  <br>
                  Listado de citas (todo el dia) 
                  <?php




 $query = "	 SELECT    DISTINCT  tbl_UsuariosWeb.id_UsuarioWeb, tbl_UsuariosWeb.st_Nombre, tbl_UsuariosWeb.st_ApellidoPaterno, tbl_EvCitasUsuariosWeb.dt_FechaCita, 
                      tbl_EvCitasUsuariosWeb.st_HoraCita, cat_StatusCita.st_StatusCita, tbl_EvCitasUsuariosWeb.id_Medico, tbl_EvCitasUsuariosWeb.id_Sucursal, 
                      tbl_EvCitasUsuariosWeb.dt_FechaRegistro, DATEDIFF(mi, GETDATE(), tbl_EvCitasUsuariosWeb.dt_FechaCita)-$mindiferencia AS mincitas, 
                      cat_StatusCita.id_StatusCita, tbl_EvCitasUsuariosWeb.id_Evento, cat_TipoCita.st_TipoCita

FROM         tbl_EvCitasUsuariosWeb INNER JOIN
                    cat_StatusCita ON tbl_EvCitasUsuariosWeb.id_StatusCita = cat_StatusCita.id_StatusCita INNER JOIN
                      tbl_UsuariosWeb ON tbl_EvCitasUsuariosWeb.id_UsuarioWeb = tbl_UsuariosWeb.id_UsuarioWeb INNER JOIN
                      cat_TipoCita ON tbl_EvCitasUsuariosWeb.id_TipoCita = cat_TipoCita.id_TipoCita
WHERE     (tbl_EvCitasUsuariosWeb.id_Medico = '".$id_Medico."') 
AND (DAY(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".date('d')."')
AND (MONTH(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".date('m')."')
AND (YEAR(tbl_EvCitasUsuariosWeb.dt_FechaCita) =  '".date('Y')."')


ORDER BY tbl_EvCitasUsuariosWeb.dt_FechaCita ";

//echo $query;
$rquery =  mssql_query($query);

while($rowCiTas = mssql_fetch_array($rquery)){

?>
                  <div class="DIVmod_title"> <br>
                    <? if ($rowCiTas['id_StatusCita']  > 1) { ?>
                    <img src="../images/iPassed.png" width="12" height="12" />	
                    <? } else  { ?>
                    <img src="../images/iNoPassed.png" width="12" height="12" /> 
                    <? }  ?>
                    <a href="javascript:Abrir_ventana('../cac/detallecita.php?idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>')"> 
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
                    minutos para la cita)</a><a href="detallepaciente.php?varcontrol=1&idevento=<?=$rowCiTas['id_Evento']?>&idusuarioweb=<?=$rowCiTas['id_UsuarioWeb']?>"><img src="../images/right_over.gif" alt="Comenzar consulta" width="16" height="16" /></a></div>
                  <?
 } 
?>
                </div>
                <div align="right"><a href="content.php"><img src="../images/iCheck.gif" width="14" height="18" />Ver 
                  Citas de <span class="DIVpadding">ultima hora y proximas 2 horas</span> 
                  </a> </div>
                <div class="DIVmod_footer" > 
                  <div class="DIVmod_footer_text"><a  href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                  <div id="expansor1"> 
                    <div style="display:none;" id="first" class="DIVmod_footer_hide"> 
                      La busqueda puede ser por nombre de cliente , apellidos 
                      ,email ,por numero de cedula , o numero de cita. </div>
                  </div>
                </div>
              </div>
              <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
              <span class="DIVmod_title"><img src="../images/iPassed.png" width="12" height="12" /></span><span class="Estilo7">Check 
              In <br>
              <img src="../images/iNoPassed.png" width="12" height="12" /></span><span class="Estilo7">Sin 
              confirmar </span><span class="DIVpadding"><br />
              <a href="javascript:Abrir_ventana('nuevacita.php?idusuarioweb=<?=$id_UsuarioWeb?>')"></a></span></div>
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
</div>
</body>
</html>
<?php mssql_close(); ?>

