<?php    header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
 $id =  $_GET["Id"];
 $id_UsuarioWeb  =  $_GET["id_UsuarioWeb"];
 //si El pago es efectivo
 if($id==1){
?> <input name="concepto" type="hidden" id="concepto" value="3">
<input name="banco" type="hidden" id="banco" value="9999">
Abonar(+/-) 
<input name="abonar" type="text" id="abonar" value="<?=$_GET['tot']?>" size="7" maxlength="7">
<? }
//si es  tarjeta
 if($id==2){

 ?> <input name="concepto" type="hidden" id="concepto" value="3">
<br>
Banco 
<select name="select">
  <option value="1" selected>Banamex</option>
  <option value="2">Santander</option>
  <option value="3">Scotiabank</option>
  <option value="4">Azteca</option>
  <option value="99">Otro</option>
</select>
<br>
Aut. 
<input name="autorizabanco" type="text" id="autorizabanco"><br>
Abonar(+/-) 
<input name="abonar" type="text" id="abonar" value="<?=$_GET['tot']?>" size="7" maxlength="7">
<? }
//si es dinero electronico

 if($id==3){

 $queryelectronico = "SELECT     *
FROM         DE_Saldo
WHERE     (id_UsuarioWeb = ".$id_UsuarioWeb.")";
$rqueryelectrdnico =   mssql_query($queryelectronico);
$dineroe = mssql_fetch_array($rqueryelectrdnico);
 $saldo = $dineroe['saldoelectronico'];
 if($saldo>$_GET['tot']){
 ?><br> <input name="concepto" type="hidden" id="concepto" value="5">
Abonar(+/-) <input name="banco" type="hidden" id="banco" value="9999">

<input name="abonar" type="text" id="abonar3" size="7" maxlength="7"  value="<?=$_GET['tot']?>"  >
<br>
<font size="1" face="Verdana, Arial, Helvetica, sans-serif">*Saldo electronico es :
<?=$saldo?>
</font> 
<input name="tope" type="hidden" id="tope" value="<?=$saldo?>">
<? }  else   echo "No tiene  fondos   en su monedero<a href='registrarpagos.php?idusuarioweb=".$id_UsuarioWeb."&id_EventoVenta=NA' >  abonar dinero</a><br>Saldo actual : ".$saldo;
} ?>
