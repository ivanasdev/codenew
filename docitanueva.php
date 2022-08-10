<?php  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");
$operador= $idoperador;
$id_UsuarioWeb = $_POST['id_UsuarioWeb'];

///  insertamos  los  datos  de la cita


$queryinsertcita = "INSERT  INTO tbl_EvCitasUsuariosWeb(id_UsuarioWeb, dt_FechaCita, st_HoraCita, id_Sucursal, id_Medico, id_OperadorCC,id_TipoCita)   VALUES   
('".$id_UsuarioWeb."','".$_POST['fechacita']." ".$_POST['horacita'].":".$_POST['minutoscita'].":00.000','".$_POST['horacita'].":".$_POST['minutoscita']."','".$idsucursal."','".$id_Medico."','".$operador."','".$_POST['cita']."')";

 
$rqueryinsertcita  = mssql_query($queryinsertcita);
//exit();
$queryselec="SELECT TOP 1 id_Evento FROM tbl_EvCitasUsuariosWeb  WHERE id_UsuarioWeb = '".$id_UsuarioWeb."' ORDER BY id_Evento DESC";
$rqueryselec=mssql_query($queryselec);
$rowselec=mssql_fetch_array($rqueryselec);
$id_Eventoc=$rowselec['id_Evento'];
$queryeventototal = "INSERT INTO tbl_EventosUsuariosWeb (id_UsuarioWeb, id_TipoEvento, id_Evento, id_TipoEventoOrigen, st_NombreEvento, st_IP,id_EventoOrigen) VALUES ('".$id_UsuarioWeb."', '3', '".$id_Eventoc."', '17', 'Cita (Agenda)', '".$_SERVER['REMOTE_ADDR']."','3')";
$rqueryeventototal = mssql_query($queryeventototal);
$folio = $id_Eventoc;
//exit();
//determinamos la  duracion de la  cita
$horas = 30;
if($_POST['cita']==3){
$horas = 15;
}
if($_POST['cita']==2){
$horas = 60;
}
$FEchaCaducidad = " dateadd(mi,".$horas.",dt_FechaCita)";
$queryselec="UPDATE   tbl_EvCitasUsuariosWeb
SET           dt_FechaSalida =".$FEchaCaducidad."  where id_Evento =".$id_Eventoc;
$rqueryselec= mssql_query($queryselec);

?>
<?php mssql_close(); ?>
<script>
alert('haz completado tu   registro de cita ')
window.close();
window.opener.location.reload();

location='detallepaciente.php?idusuarioweb=<?=$id_UsuarioWeb?>'
</script>