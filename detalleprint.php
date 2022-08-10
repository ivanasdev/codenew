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
<title>Historia clinica</title>
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
        
            <div class="DIVleft2"><img src="../images/logo_farmacias_clinicas.jpg" width="600" height="100" /> <br>
              <!-- LEFT/IZQUIERDA -->
              <img src="../images/icGuiones.gif" width="32" height="32" /> HISTORIA 
              CLINICA <br />
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
                    </tr><tr>
                      <td width="79%">&nbsp;</td>
                      <td width="21%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text">ANTECEDENTES <strong>PATOLOGICOS, GINECO-OBSTETRICOS,FAMILIARES,POSITIVOS</strong></div>
                        </div>
                      </div></td>
                    </tr>

                   
                    <tr>
                      <td><? 

				$queryinsertcita = "	  SELECT     id_ConsultaPatologicos, id_Evento, id_UsuarioWeb, i_Hipertencion, i_Cardiopatia, i_Cancer, i_Artritis, i_Diabetes, st_OtrosAntecedentes, 
                      st_Gestacion, st_P, st_A, st_C, st_Fur, st_Otros, st_Quiru, st_Farma, st_Tox, st_Trans, st_Aler, st_Familiar, st_Inmunizaciones, st_Otros2, 
                      dt_FechaRegistro, id_Medico, id_Sucursal
FROM         tbl_ConsultaPatologicos
where id_UsuarioWeb = '".$id_UsuarioWeb."'
ORDER BY dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                        Fecha de Registro:
                        <?=$rowdata['dt_FechaRegistro']?>
<br>
                        <strong>Patologicos</strong>                        :<br><div>
                        <ul>
<? if($rowdata['i_Hipertencion'] == 1) echo "<li>Hipertensión</li>";  ?>
<? if($rowdata['i_Cardiopatia'] == 1) echo "<li>Cardiopatia</li>";  ?>
<? if($rowdata['i_Cancer'] == 1) echo "<li>Cancer</li>";  ?>
<? if($rowdata['i_Artritis'] == 1) echo "<li>Artritis</li>";  ?>

<? if($rowdata['i_Diabetes'] == 1) echo "<li>Diabetes</li> ";  ?>
</ul>
Otros antecedentes:  <?=$rowdata['st_OtrosAntecedentes']?>
<br><br>
<strong>Gineco-Obstetricos</strong>    <ul>
<? if($rowdata['st_Gestacion'] <> "") echo "<li>Gestacion: ".$rowdata['st_Gestacion']."</li>";  ?>
<? if($rowdata['st_P'] <> "") echo "<li>P: ".$rowdata['st_P']."</li>";  ?>
<? if($rowdata['st_A'] <> "") echo "<li>A: ".$rowdata['st_A']."</li>";  ?>
<? if($rowdata['st_C'] <> "") echo "<li>C: ".$rowdata['st_C']."</li>";  ?>

<? if($rowdata['st_Fur'] <> "") echo "<li>FUR: ".$rowdata['st_Fur']."</li> ";  ?>
<? if($rowdata['st_Otros'] <> "") echo "<li>Otros: ".$rowdata['st_Otros']."</li> ";  ?>

</ul>
<br><br>
<strong>Positivos</strong>    <ul>
<? if($rowdata['st_Quiru'] <> "") echo "<li>Quirúrgicos: ".$rowdata['st_Quiru']."</li>";  ?>
<? if($rowdata['st_Farma'] <> "") echo "<li>Farmacológicos: ".$rowdata['st_Farma']."</li>";  ?>
<? if($rowdata['st_Tox'] <> "") echo "<li>Tóxicos: ".$rowdata['st_Tox']."</li>";  ?>
<? if($rowdata['st_Trans'] <> "") echo "<li>Transfuncionales: ".$rowdata['st_Trans']."</li>";  ?>
<? if($rowdata['st_Aler'] <> "") echo "<li>Alérgicos: ".$rowdata['st_Aler']."</li> ";  ?>
<? if($rowdata['st_Familiar'] <> "") echo "<li>Familiares: ".$rowdata['st_Familiar']."</li> ";  ?>
<? if($rowdata['st_Inmunizaciones'] <> "") echo "<li>Inmunizaciones: ".$rowdata['st_Inmunizaciones']."</li> ";  ?>
<? if($rowdata['st_Otros2'] <> "") echo "<li>Otros: ".$rowdata['st_Otros2']."</li> ";  ?>


</ul>

<hr>
<? }}  ?>					  </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>EXAMENES FISICOS </strong></div>
                      </div></td>
                    </tr>
                    <tr>
                      <td> <? 

				$queryinsertcita = "	SELECT    id_ConsultaExamenMedico, id_UsuarioWeb, id_Evento, st_Fc, st_Fr, st_To, st_Talla, st_Peso, st_Ta, id_EstadoGeneral, st_EstadoGeneral, 
                      id_CraneoCuello, st_CraneoCuello, id_Torax, st_Torax, id_Toxicos, st_Toxicos, id_Pulmones, st_Pulmones, id_Corazon, st_Corazon, id_Abdomen, 
                      st_Abdomen, id_Genitales, st_Genitales, id_Oste, st_Oste, id_Extremidades, st_Extremidadces, id_Neuro, st_Neuro, id_Piel, st_Piel, 
                      st_Observaciones, dt_FechaRegistro, id_Medico, id_Sucursal
FROM         tbl_ConsultaExamenMedico
where id_UsuarioWeb = '".$id_UsuarioWeb."'
ORDER BY dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
					  
					  <br>  <ul>
<? if($rowdata['st_Fc'] <> "") echo "<li>FC: ".$rowdata['st_Fc']."</li>";  ?>
<? if($rowdata['st_Fr'] <> "") echo "<li>FR: ".$rowdata['st_Fr']."</li>";  ?>
<? if($rowdata['st_To'] <> "") echo "<li>To: ".$rowdata['st_To']."</li>";  ?>
<? if($rowdata['st_Talla'] <> "") echo "<li>Talla: ".$rowdata['st_Talla']."</li>";  ?>
<? if($rowdata['st_Peso'] <> "") echo "<li>Peso: ".$rowdata['st_Peso']."</li> ";  ?>
<? if($rowdata['st_Ta'] <> "") echo "<li>TA: ".$rowdata['st_Ta']."</li> ";  ?>


</ul>

	  <br>  <ul>
<? if($rowdata['st_EstadoGeneral'] <> "") {
 $tipo ="Anormal";
if($rowdata['id_EstadoGeneral'] == "1") $tipo ="Normal";
echo "<li>Estado General: ".$tipo."  ".$rowdata['st_EstadoGeneral']."</li>";  }

?>
<? if($rowdata['st_CraneoCuello'] <> "")
 {
 $tipo ="Anormal";
if($rowdata['id_CraneoCuello'] == "1") $tipo ="Normal";
 echo "<li>Craneo y Cuello: ".$tipo."  ".$rowdata['st_CraneoCuello']."</li>";  
  }
 ?>
 
<? if($rowdata['st_Torax'] <> "") 
{
 $tipo ="Anormal";
if($rowdata['id_Torax'] == "1") $tipo ="Normal";

echo "<li>Torax:  ".$tipo."  ".$rowdata['st_Torax']."</li>"; 
}  ?>

<? if($rowdata['st_Toxicos'] <> "")
{
 $tipo ="Anormal";
if($rowdata['id_Toxicos'] == "1") $tipo ="Normal";
 echo "<li>Tóxicos:  ".$tipo."  ".$rowdata['st_Toxicos']."</li>"; 
 } ?>
<? if($rowdata['st_Pulmones'] <> "") 
{
 $tipo ="Anormal";
if($rowdata['id_Pulmones'] == "1") $tipo ="Normal";
echo "<li>Pulmones:  ".$tipo."  ".$rowdata['st_Pulmones']."</li> "; 
} ?>

<? if($rowdata['st_Corazon'] <> "") 
{
 $tipo ="Anormal";
if($rowdata['id_Corazon'] == "1") $tipo ="Normal";
echo "<li>Corazón: ".$tipo."  ".$rowdata['st_Corazon']."</li> "; 
}
 ?>
<? if($rowdata['st_Abdomen'] <> "")
{
 $tipo ="Anormal";
if($rowdata['id_Abdomen'] == "1") $tipo ="Normal";
 echo "<li>Abdomen: ".$tipo."  ".$rowdata['st_Abdomen']."</li> "; 
 }
  ?>
<? if($rowdata['st_Genitales'] <> "") 
{
 $tipo ="Anormal";
if($rowdata['id_Genitales'] == "1") $tipo ="Normal";
echo "<li>Genitales: ".$tipo."  ".$rowdata['st_Genitales']."</li> ";  
}
?>
<? if($rowdata['st_Oste'] <> "") 
{
 $tipo ="Anormal";
if($rowdata['id_Oste'] == "1") $tipo ="Normal";
echo "<li>Osterarticulares: ".$tipo."  ".$rowdata['st_Oste']."</li> ";  
}
?>
<? if($rowdata['st_Extremidadces'] <> "") {
 $tipo ="Anormal";
if($rowdata['id_Extremidadces'] == "1") $tipo ="Normal";
echo "<li>Extremidades: ".$tipo."  ".$rowdata['st_Extremidadces']."</li> ";  
}?>

<? if($rowdata['st_Neuro'] <> "") {
 $tipo ="Anormal";
if($rowdata['id_Neuro'] == "1") $tipo ="Normal";
 echo "<li>Neurológico y reflejos: ".$tipo."  ".$rowdata['st_Neuro']."</li> ";  
 }
 ?>
 
<? if($rowdata['st_Piel'] <> "")
{
 $tipo ="Anormal";
if($rowdata['id_Piel'] == "1") $tipo ="Normal";
 echo "<li>Piel: ".$tipo."  ".$rowdata['st_Piel']."</li> "; 
 } ?>
<? if($rowdata['st_Observaciones'] <> "") echo "<li>Observaciones: ".$rowdata['st_Observaciones']."</li> ";  ?>




</ul>

<hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr>                    <tr>
                      <td colspan="2"><div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>ANAMNESIS</strong></div>
                      </div></td>
                    </tr><tr>
                      <td> <? 

				$queryinsertcita = "	SELECT   id_ConsultaAnemesis, id_Evento, id_UsuarioWeb, st_MotivoConsulta, st_EnfermedadActual, id_Cabeza, st_Cabeza, id_Orl, st_Orl, id_Cr, 
                      st_Cr, id_Gi, st_Gi, id_Neuromuscular, st_Neuromuscular, id_Gu, st_Gu, id_Psiquiatrico, st_Psiquiatrico, id_Piel, st_Piel, id_Medico, dt_FechaRegistro,
                       id_Sucursal
FROM         tbl_ConsultaAnemesis
where id_UsuarioWeb = '".$id_UsuarioWeb."'
ORDER BY dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
					  
					  <br>  <ul>
<? if($rowdata['st_MotivoConsulta'] <> "") echo "<li>Motivo de la consulta : ".$rowdata['st_MotivoConsulta']."</li>";  ?>
<? if($rowdata['st_EnfermedadActual'] <> "") echo "<li>Enfermedad Actual : ".$rowdata['st_EnfermedadActual']."</li>";  ?>
<? if($rowdata['st_Cabeza'] <> "") {
 $tipo ="Negativo";
if($rowdata['id_Cabeza'] == "1") $tipo ="Positivo";
echo "<li>Cabeza: ".$tipo."  ".$rowdata['st_Cabeza']."</li>";  }

?>
<? if($rowdata['st_Orl'] <> "")
 {
 $tipo ="Negativo";
if($rowdata['id_Orl'] == "1") $tipo ="Positivo";
 echo "<li>ORL: ".$tipo."  ".$rowdata['st_Orl']."</li>";  
  }
 ?>
 
<? if($rowdata['st_Cr'] <> "") 
{
 $tipo ="Negativo";
if($rowdata['id_Cr'] == "1") $tipo ="Positivo";
echo "<li>CR:  ".$tipo."  ".$rowdata['st_Cr']."</li>"; 
}  ?>

<? if($rowdata['st_Gi'] <> "")
{
 $tipo ="Negativo";
if($rowdata['id_Gi'] == "1") $tipo ="Positivo";
 echo "<li>GI:  ".$tipo."  ".$rowdata['st_Gi']."</li>"; 
 } ?>
<? if($rowdata['st_Neuromuscular'] <> "") 
{
 $tipo ="Negativo";
if($rowdata['id_Neuromuscular'] == "1") $tipo ="Positivo";
echo "<li>Neuromuscular: ".$tipo."  ".$rowdata['st_Neuromuscular']."</li> "; 
} ?>

<? if($rowdata['st_Gu'] <> "") 
{
 $tipo ="Negativo";
if($rowdata['id_Gu'] == "1") $tipo ="Positivo";
echo "<li>GU:".$tipo."  ".$rowdata['st_Gu']."</li> "; 
}
 ?>
<? if($rowdata['st_Psiquiatrico'] <> "") 
{
 $tipo ="Negativo";
if($rowdata['id_Psiquiatrico'] == "1") $tipo ="Positivo";
echo "<li>Psiquiatrico:".$tipo."  ".$rowdata['st_Psiquiatrico']."</li> "; 
}
 ?>
<? if($rowdata['st_Piel'] <> "") 
{
 $tipo ="Negativo";
if($rowdata['id_Piel'] == "1") $tipo ="Positivo";
echo "<li>Piel y Anexos:".$tipo."  ".$rowdata['st_Piel']."</li> "; 
}
 ?>

</ul>

	 
<hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr>  <tr>
                      <td colspan="2"><div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>HISTORIA HOMEOPATICO </strong></div>
                      </div></td>
                    </tr><tr>
                      <td> <? 

				$queryinsertcita = "	SELECT   *
FROM         tbl_ConsultaHomeopatico
where id_UsuarioWeb = '".$id_UsuarioWeb."'
ORDER BY dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
					  
					  <br> 
<? if($rowdata['id_Bio'] == 1) $bio="Carbónico: linfático - endomórfico";  ?>
<? if($rowdata['id_Bio'] == 2) $bio="Sulfúrico: bilioso - mesomófico";  ?>
<? if($rowdata['id_Bio'] == 3) $bio="Fosfórico: nervioso - ectomórfico";  ?>
<? if($rowdata['id_Bio'] == 4) $bio="Fluórico: sanguíneo - asimétrico";  ?>
<?  echo "BIOTIPOLOGIA  : ".$bio." ";  ?> <ul>Sintomas Locales (motivo de la consulta)
<? if($rowdata['st_Sintoma'] <> "") echo "<li>Sintoma: ".$rowdata['st_Sintoma']."</li>";  ?>
<? if($rowdata['st_Localizacion'] <> "") echo "<li>Localización: ".$rowdata['st_Localizacion']."</li>";  ?>
<? if($rowdata['st_Sensacion'] <> "") echo "<li>Sensación ( `como si` ): ".$rowdata['st_Sensacion']."</li>";  ?>
<? if($rowdata['st_Modalidad'] <> "") echo "<li>Modalidad < ó >: ".$rowdata['st_Modalidad']."</li>";  ?>
<? if($rowdata['st_Sincon'] <> "") echo "<li>Sin. concomitantes: ".$rowdata['st_Sincon']."</li>";  ?>
<? if($rowdata['st_Alternancia'] <> "") echo "<li>ALTERNANCIA DE SINTOMAS: ".$rowdata['st_Alternancia']."</li>";  ?>
<? if($rowdata['st_SintomasMentales'] <> "") echo "<li>SINTOMAS MENTALES: ".$rowdata['st_SintomasMentales']."</li>";  ?>
<? if($rowdata['st_SintomasGenerales'] <> "") echo "<li>SINTOMAS GENERALES: ".$rowdata['st_SintomasGenerales']."</li>";  ?>

</ul>


	 
<hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr>  <tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>DIAGNOSTICOS</strong></div>
                        </div>
                      </div></td>
                    </tr><tr>
                      <td> <? 

		 	$queryinsertcita = "	SELECT     tbl_ConsultaDiagnostico.id_ConsultaDiagnostico, tbl_ConsultaDiagnostico.id_UsuarioWeb,tbl_ConsultaDiagnostico.st_Calificacion, tbl_ConsultaDiagnostico.id_Evento, 
                      tbl_ConsultaDiagnostico.id_DiagnosticoUno, tbl_ConsultaDiagnostico.id_DiagnosticoDos, tbl_ConsultaDiagnostico.id_DiagnosticoTres, 
                      tbl_ConsultaDiagnostico.id_Procedimiento, tbl_ConsultaDiagnostico.dt_FechaRegistro, tbl_ConsultaDiagnostico.id_Medico, 
                      tbl_ConsultaDiagnostico.id_Sucursal, cat_Diagnostico.st_Diagnostico AS uno, cat_Diagnostico_2.st_Diagnostico AS dos, 
                      cat_Diagnostico_1.st_Diagnostico AS tres
FROM         tbl_ConsultaDiagnostico LEFT OUTER JOIN
                      cat_Diagnostico AS cat_Diagnostico_2 ON tbl_ConsultaDiagnostico.id_DiagnosticoDos = cat_Diagnostico_2.id_Diagnostico LEFT OUTER JOIN
                      cat_Diagnostico AS cat_Diagnostico_1 ON tbl_ConsultaDiagnostico.id_DiagnosticoTres = cat_Diagnostico_1.id_Diagnostico LEFT OUTER JOIN
                      cat_Diagnostico ON tbl_ConsultaDiagnostico.id_DiagnosticoUno = cat_Diagnostico.id_Diagnostico
where tbl_ConsultaDiagnostico.id_UsuarioWeb = '".$id_UsuarioWeb."'
ORDER BY tbl_ConsultaDiagnostico.dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    
                      Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
					  
					  /
					  <strong>
					  <?=$rowdata['st_Calificacion']?></strong><br>
					  <br>
<? echo " <br>Diagnostico 1: ".$rowdata['uno'];  ?>
<?  echo "<br>Diagnostico 2: ".$rowdata['dos'];  ?>
<?  echo  "<br>Diagnostico 3: ".$rowdata['tres'];  ?>
  <div><strong>PROCEDIMIENTO CUPS</strong></div>Procedimientos<br>
  <? if($rowdata['id_Procedimiento'] == "1") $txtproce = "864106 RESECCION DE TUMOR MALIGNO DE PIEL Y/O TEJIDO CELULAR SUBCUTANEO";  ?>
  <? if($rowdata['id_Procedimiento'] == "2") $txtproce = "876612 BRONCOGRAFIA BILATERAL";  ?>
  <? if($rowdata['id_Procedimiento'] == "3") $txtproce = "898303 ESTUDIOS ANATOMOPATOLOGICOS POSMORTEM DE ORGANOS O TEJIDOS";  ?>
  <? if($rowdata['id_Procedimiento'] == "4") $txtproce = "906621 CALCITONINA +"; 
  
  echo $txtproce; 
   ?>


	 
<br>
<img src="../images/icUsers.gif" width="48" height="32" /> / Nombre medico: <br> 
Nombre del registro: <br> 
Especialidad: <br>
Universidad: 
<hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr> <tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>NOTAS MEDICAS </strong></div>
                        </div>
                      </div></td>
                    </tr><tr>  <td> <? 

		 	$queryinsertcita = "	SELECT   *
FROM         tbl_ConsultaNotaMedica
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')
ORDER BY  dt_FechaRegistro DESC";
$rqueryinsertcita = mssql_query($queryinsertcita);
$numrows = mssql_num_rows($rqueryinsertcita);

if($numrows  > 0 ){
while ($rowdata= mssql_fetch_array($rqueryinsertcita)){

?>
                      <br>    Fecha de Registro:
                      <?=$rowdata['dt_FechaRegistro']?>
					  
					  <br>
					  <strong>
					  <?=$rowdata['st_Uno']?> </strong> 
					  INGRESA PACIENTE A TERAPIA BIOLOGICA DE 
					  <strong>
					  <?=$rowdata['st_Dos']?> 
					  </strong> 
					  , SE LE EXPLICA EL PROCEDIMIENTO, SE TOMAN SIGNOS VITALES T.A <strong>
					  <?=$rowdata['st_Tres']?> 
					  </strong>  F.C <strong>
					  <?=$rowdata['st_Cuatro']?> </strong>  F.R <strong>
					  <?=$rowdata['st_Cinco']?> </strong>  Y SE PREPARA 250 CC DE <strong>
					  <?=$rowdata['st_Seis']?></strong>  , MAS <strong>
					  <?=$rowdata['st_Siete']?></strong>  ;<strong>
					  <?=$rowdata['st_Ocho']?></strong>  ; <strong>
					  <?=$rowdata['st_Nueve']?></strong>   PARA PASAR EN 60 MINUTOS SEGÚN ORDEN MEDICA DEL DOCTOR(A) <strong>
					  <?=$rowdata['st_Dies']?></strong>. SE CANALIZA VENA EN <strong>
					  <?=$rowdata['st_Once']?></strong>   CON PERICRANEAL Nº 21.<strong>
					  <?=$rowdata['st_Doce']?></strong>  SE RETIRAN LIQUIDOS A LA PACIENTE, SE TOMA NUEVAMENTE T.A. <strong>
					  <?=$rowdata['st_Trece']?> 
					  .
					  <?=$rowdata['st_Catorce']?>
					  </strong>  SALE PACIENTE SIN NOVEDAD, ALERTA, CONCIENTE  Y ORIENTADA.
					  <br>
					  <hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr><tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>ORGANOMETRO</strong></div>
                        </div>
                      </div></td>
                    </tr><tr>  <td> <p>
                      <? 

		 	$queryinsertcita = "	SELECT   *
FROM         tbl_ConsultaOrganometro
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
                      Region Lumbar:
                      <?=$rowdata['st_Lumbar']?>
                      <br>
                      Sistema Oseo alto  :
                      <?=$rowdata['st_Oseo']?>
                      <br> <br> <b>SISTEMA CIRCULATORIO </b> 
 <br>
                     Alergias
					  :
                      <?=$rowdata['st_Alergias']?>
                      <br>
                   Corazon
				     :
                      <?=$rowdata['st_Corazon']?>
                      <br>
                     Arterias
					 :
                      <?=$rowdata['st_Arterias']?>
                    <br> <br><b>OTROS </b>
 <br>
					 Organos de la cabeza 
					 :
                      <?=$rowdata['st_Cabeza']?>
                      <br>
					 Bronquios
:
                      <?=$rowdata['st_Bronquios']?>
                      <hr>

					  <? } } ?></td>
                      <td>&nbsp;</td>
                    </tr>
					<tr>
                      <td colspan="2"><div>
                        <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                        <div class="DIVmod_header">
                          <div class="DIVmod_header_text"><strong>PARACLINICOS</strong></div>
                        </div>
                      </div></td>
                    </tr><tr>  <td> <p>
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
                      <td>&nbsp;</td>
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