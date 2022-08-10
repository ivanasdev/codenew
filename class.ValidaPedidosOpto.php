<?php 
require("../db.php");

$id_User="16926";


class BloqueoOpto{
   function Getbloqueo($id_User){
      $queryblock='';
      $query0="
      SELECT PP.id_PedidoOpticaPrevio,
      PP.id_PedidoOpticaProveedor,
      co.id_cotizacionOptica,
      PP.st_Folio AS folio,
      vo.id_VentaOptica id_EventoConcepto,
      us.id_UsuarioWeb,
      us.st_Nombre + ' ' + us.st_ApellidoPaterno + ' ' + us.st_ApellidoMaterno Nombre, convert(varchar(15),vo.dt_FechaRegistro,111) [fecha compra], cs.st_Nombre Sucursal, po.st_Nombre Producto, po.st_Descripcion Descripcion, po.st_Codigo Codigo, co.i_Total total, (sum(pu.i_Cantidad) - co.i_Total) saldo, sum(pu.i_Cantidad) abono, tg.st_BarcodeVenta ticket, tg.st_Barcode preticket, usv.st_User UsuarioCaja,
      CASE
      WHEN sta.st_StatusPedidoOptica is NULL THEN
'Sin Estatus'
ELSE sta.st_StatusPedidoOptica
END st_StatusPedidoOptica, sta.id_StatusPedidoOptica, sta.st_BackColor, dt_FechaEnvioLaboratorio, dt_FechaRecibidoSucursal, dt_FechaEntregadoCliente, id_OperadorEnviaLaboratorio, id_OperadorRecibidoSucursal, id_OperadorEntregadoCliente
FROM tbl_EvVentaOptica vo
INNER JOIN tbl_CotizacionOptica co
ON vo.id_VentaOptica = co.id_EventoVenta
INNER JOIN tbl_paqueteOptica po
ON po.id_PaqueteOptica = co.id_PaqueteOptica
INNER JOIN cat_SucursalClinica cs
ON cs.id_SucursalClinica = vo.id_Sucursal
INNER JOIN tbl_PagosUsuarioWeb pu
ON pu.id_EventoConcepto = vo.id_VentaOptica
 AND pu.id_Concepto IN (8,9)
INNER JOIN tbl_UsuariosWebCac us
ON us.id_UsuarioWeb = vo.id_usuarioWeb
INNER JOIN tbl_TicketGeneral tg
ON tg.id_TicketGeneral = co.st_key
INNER JOIN tbl_UsuarioSistemaWeb usv
ON usv.id_UsuarioSistemaWeb = tg.id_OperadorVendio
LEFT JOIN tbl_pedidosOpticaProveedor pp
ON pp.id_EventoConcepto = vo.id_VentaOptica
LEFT JOIN cat_StatusPedidoOptica sta
ON sta.id_StatusPedidoOptica = pp.id_Status
WHERE sta.id_StatusPedidoOptica=1
 AND tg.id_Operador='$id_User'
 AND cs.id_SucursalClinica IN 
(SELECT DISTINCT id_Sucursal
FROM tbl_EvVentaOptica )
 --AND vo.dt_FechaRegistro >= @fechaIni -1
GROUP BY  vo.id_VentaOptica,us.st_Nombre,us.st_ApellidoPaterno, us.st_ApellidoMaterno,vo.dt_FechaRegistro, cs.st_Nombre, po.st_Nombre, po.st_Descripcion, po.st_Codigo, co.i_Total, tg.st_BarcodeVenta, tg.st_Barcode,usv.st_User, sta.st_StatusPedidoOptica, sta.st_BackColor,PP.id_PedidoOpticaProveedor,us.id_UsuarioWeb,co.id_cotizacionOptica,PP.st_Folio, sta.id_StatusPedidoOptica,dt_FechaEnvioLaboratorio, dt_FechaRecibidoSucursal, dt_FechaEntregadoCliente, id_OperadorEnviaLaboratorio, id_OperadorRecibidoSucursal, id_OperadorEntregadoCliente, PP.id_PedidoOpticaPrevio, pu.id_TipoPago
HAVING (sum(pu.i_Cantidad) >= (co.i_Total * 0.30))
 OR pu.id_TipoPago = '13'
ORDER BY  vo.dt_FechaRegistro DESC ";

$resqu=mssql_query($query0);
$countrows=mssql_num_rows($resqu);
if($countrows > 0 ){
  $queryblock=1;
  $arrayQuery0=mssql_fetch_array($resqu);
  $folio = $arrayQuery0['folio'];
  
  $queryblock="INSERT INTO tbl_BloqueosOperadoresOptica(id_Operador,st_Folio,i_FlagBloqueo) VALUES(".$id_User.",'".$folio."', ".$queryblock.")";
  $reqblock=mssql_query($queryblock);

  if($reqblock){
      echo "BLOCK";
      echo $queryblock;
  }else{
      echo "NOBLOCK <br>";
      echo $queryblock;
  }
}
else{
  $queryblock=0;    
  echo "LIBRE";
}


   }

}




//OTRWA QUERY 

$query0="
	SELECT PP.id_PedidoOpticaPrevio,
	PP.id_PedidoOpticaProveedor,
	co.id_cotizacionOptica,
	PP.st_Folio AS folio,
	vo.id_VentaOptica id_EventoConcepto,
	us.id_UsuarioWeb,
	us.st_Nombre + ' ' + us.st_ApellidoPaterno + ' ' + us.st_ApellidoMaterno Nombre, convert(varchar(15),vo.dt_FechaRegistro,111) [fecha compra], cs.st_Nombre Sucursal, po.st_Nombre Producto, po.st_Descripcion Descripcion, po.st_Codigo Codigo, co.i_Total total, (sum(pu.i_Cantidad) - co.i_Total) saldo, sum(pu.i_Cantidad) abono, tg.st_BarcodeVenta ticket, tg.st_Barcode preticket, usv.st_User UsuarioCaja,
	CASE
	WHEN sta.st_StatusPedidoOptica is NULL THEN
'Sin Estatus'
ELSE sta.st_StatusPedidoOptica
END st_StatusPedidoOptica, sta.id_StatusPedidoOptica, sta.st_BackColor, dt_FechaEnvioLaboratorio, dt_FechaRecibidoSucursal, dt_FechaEntregadoCliente, id_OperadorEnviaLaboratorio, id_OperadorRecibidoSucursal, id_OperadorEntregadoCliente
FROM tbl_EvVentaOptica vo
INNER JOIN tbl_CotizacionOptica co
ON vo.id_VentaOptica = co.id_EventoVenta
INNER JOIN tbl_paqueteOptica po
ON po.id_PaqueteOptica = co.id_PaqueteOptica
INNER JOIN cat_SucursalClinica cs
ON cs.id_SucursalClinica = vo.id_Sucursal
INNER JOIN tbl_PagosUsuarioWeb pu
ON pu.id_EventoConcepto = vo.id_VentaOptica
AND pu.id_Concepto IN (8,9)
INNER JOIN tbl_UsuariosWebCac us
ON us.id_UsuarioWeb = vo.id_usuarioWeb
INNER JOIN tbl_TicketGeneral tg
ON tg.id_TicketGeneral = co.st_key
INNER JOIN tbl_UsuarioSistemaWeb usv
ON usv.id_UsuarioSistemaWeb = tg.id_OperadorVendio
LEFT JOIN tbl_pedidosOpticaProveedor pp
ON pp.id_EventoConcepto = vo.id_VentaOptica
LEFT JOIN cat_StatusPedidoOptica sta
ON sta.id_StatusPedidoOptica = pp.id_Status
WHERE sta.id_StatusPedidoOptica=1
AND tg.id_Operador='$idUsuario'
AND cs.id_SucursalClinica IN 
(SELECT DISTINCT id_Sucursal
FROM tbl_EvVentaOptica )
--AND vo.dt_FechaRegistro >= @fechaIni -1
GROUP BY  vo.id_VentaOptica,us.st_Nombre,us.st_ApellidoPaterno, us.st_ApellidoMaterno,vo.dt_FechaRegistro, cs.st_Nombre, po.st_Nombre, po.st_Descripcion, po.st_Codigo, co.i_Total, tg.st_BarcodeVenta, tg.st_Barcode,usv.st_User, sta.st_StatusPedidoOptica, sta.st_BackColor,PP.id_PedidoOpticaProveedor,us.id_UsuarioWeb,co.id_cotizacionOptica,PP.st_Folio, sta.id_StatusPedidoOptica,dt_FechaEnvioLaboratorio, dt_FechaRecibidoSucursal, dt_FechaEntregadoCliente, id_OperadorEnviaLaboratorio, id_OperadorRecibidoSucursal, id_OperadorEntregadoCliente, PP.id_PedidoOpticaPrevio, pu.id_TipoPago
HAVING (sum(pu.i_Cantidad) >= (co.i_Total * 0.30))
OR pu.id_TipoPago = '13'
ORDER BY  vo.dt_FechaRegistro DESC ";

$resqu=mssql_query($query0);
$countrows=mssql_num_rows($resqu);

if($countrows > 0 ){

$block=1;
$arrayQuery0=mssql_fetch_array($resqu);
$folio = $arrayQuery0['folio'];

$queryblock="INSERT INTO tbl_BloqueosOperadoresOptica(id_Operador,st_Folio,i_FlagBloqueo) VALUES(".$idUsuario.",'".$folio."', ".$queryblock.")";
$reqblock=mssql_query($queryblock);


//VALIDA BLOQUEOS NO REPRTIDOS
$querysel="SELECT id_Operador,st_Folio,i_FlagBloqueo FROM tbl_BloqueosOperadoresOptica WHERE id_Operador=".$id_Usuario." AND st_Folio=".$folio."  ";
$ressel=mssql_query($querysel);
$arraysel=mmsql_fetch_array($ressel);

$Operador=$arraysel['st_Operador'];
$foliouser=$arraysel['st_Folio'];
$bloqueo=$arraysel['i_FlagBloqueo'];


if($folio == $foliouser ){


}



}


