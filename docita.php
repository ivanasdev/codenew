<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");
$operador= $idoperador;
$citaTipo=$_POST['cita'];


$tipo = 1;
if($_POST['cita']==5) $tipo = 2;



$queryinsert = "INSERT INTO tbl_UsuariosWebCac (st_Email, st_Nombre, st_ApellidoPaterno, st_ApellidoMaterno,st_Direccion,st_Documento,id_Departamento,id_Ciudad,id_TipoDocumento,id_Tipo,id_sexo,dt_FechaNacimiento) 


VALUES ('".$_POST['email']."','".$_POST['nombre']."','".$_POST['apellidopaterno']."','".$_POST['apellidomaterno']."','".$_POST['direccion']."','".$_POST['documento']."','".$_POST['departamento']."','".$_POST['ciudad']."','".$_POST['iddocumento']."','".$tipo."',".$_POST['sexo'].",CONVERT(DATETIME,'".$_POST['fecnac']."',102)) ";

$rqueryinsert = mssql_query($queryinsert);
$querysel="select top 1 id_UsuarioWeb from tbl_UsuariosWebCac WHERE st_Documento = '".$_POST['documento']."'  ORDER by id_UsuarioWeb DESC";
$rquerysel = mssql_query($querysel);
$rwowtar=mssql_fetch_array($rquerysel);
$id_UsuarioWeb=$rwowtar['id_UsuarioWeb'];



//insertamos  los  telefonos
if($_POST['telefono']<>""){
$querytelcasa = "INSERT INTO tbl_UsuariosWebTelefonos (id_UsuarioWeb,st_Telefono,id_TipoTelefono,claveLADA) VALUES ('".$id_UsuarioWeb."','".$_POST['telefono']."','1','".$_POST['ladacasa63']."')";
$rquerytelcasa = mssql_query($querytelcasa);
}
if($_POST['celular']<>""){
$querytelcel = "INSERT INTO tbl_UsuariosWebTelefonos (id_UsuarioWeb,st_Telefono,id_TipoTelefono,claveLADA) VALUES ('".$id_UsuarioWeb."','".$_POST['celular']."','3','".$_POST['ladacel63']."')";
$querytelcel = mssql_query($querytelcel);
}


$quercompleta  = " INSERT     INTO            tbl_UsuariosWebClinicas(id_UsuarioWeb)  VALUES  ('".$id_UsuarioWeb."')";
$rquercompleta  = mssql_query($quercompleta);

//insertamos preferencias de contacto
for($i=0;$i<=3;$i++){
if($_POST["pregunta_cat14_".$i]==$i+1){
$valor=$i+1;
$queryp= "INSERT INTO tbl_UsuariosWebPreferenciasContacto (id_UsuarioWeb, id_PreferenciaContacto) VALUES ('".$id_UsuarioWeb."','".$valor."')";
$rqueryp= mssql_query($queryp);
}}




$queryevento="INSERT INTO tbl_EvRegistroUsuarioWeb (id_UsuarioWeb,id_TipoRegistro) VALUES ('".$id_UsuarioWeb."','2') ";
$rqueryevento=mssql_query($queryevento);
 $queryselev="SELECT TOP 1 id_Evento FROM tbl_EvRegistroUsuarioWeb WHERE id_UsuarioWeb = '".$id_UsuarioWeb."' ORDER BY id_Evento DESC";
$rqueryselev=mssql_query($queryselev);
$rowselev=mssql_fetch_array($rqueryselev);
$id_Evento=$rowselev['id_Evento'];
$queryeventototal = "INSERT INTO tbl_EventosUsuariosWeb (id_UsuarioWeb, id_TipoEvento, id_Evento, id_TipoEventoOrigen, st_NombreEvento, st_IP,id_EventoOrigen) VALUES ('".$id_UsuarioWeb."', '3', '".$id_Evento."', '3', 'INSCRIPCI�N (Registro)', '".$_SERVER['REMOTE_ADDR']."','2')";
$rqueryeventototal = mssql_query($queryeventototal);


///  insertamos  los  datos  de la cita
//determinamos la  duracion de la  cita
$horas = 30;
if($_POST['cita']==3){
$horas = 15;
}
if($_POST['cita']==2){
$horas = 60;
}
$FEchaCaducidad = " dateadd(mi,".$horas.",dt_FechaCita)";


echo $queryinsertcita = "INSERT  INTO tbl_EvCitasUsuariosWeb(id_UsuarioWeb, dt_FechaCita, st_HoraCita, id_Sucursal, id_Medico, id_OperadorCC,id_TipoCita)   VALUES   
('".$id_UsuarioWeb."','".$_POST['fechacita']."','".$_POST['horario']."','".$_POST['sucursal']."','".$_POST['idmedicocita']."','".$operador."','".$_POST['cita']."')";

$rqueryinsertcita  = mssql_query($queryinsertcita);


$queryselec="SELECT TOP 1 id_Evento FROM tbl_EvCitasUsuariosWeb  WHERE id_UsuarioWeb = '".$id_UsuarioWeb."' ORDER BY id_Evento DESC";
$rqueryselec=mssql_query($queryselec);
$rowselec=mssql_fetch_array($rqueryselec);
$id_Eventoc=$rowselec['id_Evento'];


$queryselec="UPDATE   tbl_EvCitasUsuariosWeb
SET           dt_FechaSalida =".$FEchaCaducidad."  where id_Evento =".$id_Eventoc;
$rqueryselec= mssql_query($queryselec);



$queryeventototal = "INSERT INTO tbl_EventosUsuariosWeb (id_UsuarioWeb, id_TipoEvento, id_Evento, id_TipoEventoOrigen, st_NombreEvento, st_IP,id_EventoOrigen) VALUES ('".$id_UsuarioWeb."', '3', '".$id_Eventoc."', '17', 'Cita (Agenda)', '".$_SERVER['REMOTE_ADDR']."','2')";
$rqueryeventototal = mssql_query($queryeventototal);
$folio = $id_Eventoc;

// insertamos  el tipo de cita
$querydelect ="INSERT INTO tbl_CitasExtemporanea  (id_Evento, id_Operador, id_Tipo)   VALUES  
   ('".$folio."','".$operador."','".$_POST['idmedicocita22']."')";
$rquerydelect = mssql_query($querydelect);




if($_POST['cita']==2){

///insertamos  las  terap�as asociadas
//////////////////////////////////////////////
//////////////////////////////////////////////
 $query = "	SELECT   *
FROM         cat_Terapias";
 $rquery = mssql_query($query);
while($rowItems = mssql_fetch_array($rquery)){

$id_ItemProducto = $rowItems['id_Terapia'];

if($_POST['terapia_'.$id_ItemProducto]== $rowItems['id_Terapia']){

 $queryinsert  = "INSERT    
INTO            tbl_RecetasTerapiaUsuarioWeb(id_EventoConsulta, id_UsuarioWeb, id_Terapia, 
                      id_Medico, id_Sucursal,i_Cantidad,st_Comentarios)
VALUES     ('".$folio."','".$id_UsuarioWeb."','".$_POST['terapia_'.$id_ItemProducto]."','".$id_Medico."','".$_POST['sucursal']."','1','".$_POST['comentarios_'.$id_ItemProducto]."')";
$rqueryinsert  =  mssql_query($queryinsert);
$i=0;

$queryid =   " SELECT     TOP (1) id_RecetaTerapiaUsuarioWeb
FROM         tbl_RecetasTerapiaUsuarioWeb
WHERE     (id_UsuarioWeb = '".$id_UsuarioWeb."')
ORDER BY id_RecetaTerapiaUsuarioWeb DESC";
$rqueryid = mssql_query($queryid);
$rowid = mssql_fetch_array($rqueryid);
 $ideventrec = $rowid['id_RecetaTerapiaUsuarioWeb'];




 $queryinsertItems = "INSERT    
INTO            tbl_RecetasDetalleCitaTerapia(id_EventoConsulta,id_StatusTerapia,id_UsuarioWeb, id_RecetaTerapiaUsuarioWeb, id_MedicoAlta, 
                      id_SucursalAlta, id_TipoTerapia, i_Tomado,id_TipoRegistro)
VALUES     ('".$folio."','2','".$id_UsuarioWeb."','".$ideventrec."','".$id_Medico."','".$_POST['sucursal']."','".$_POST['terapia_'.$id_ItemProducto]."','0',2)";
$rqueryinsertItems =  mssql_query($queryinsertItems);




			}	}
													
			

//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////////

}




//exit();
?>
<?php mssql_close(); ?>
<script>
alert('haz completado tu registro de cita   tu numero de folio es  <?=$folio?>')
location='detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>&muestraCotizacion=1'
</script>
