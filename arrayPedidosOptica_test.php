<?php
require("../db.php");
session_start();

$idUsuario = $_SESSION["id_Operador"];

$tipoBusqueda = $_POST['tipoBusqueda'];
$idfolio = $_POST['idfolio'];

if (isset($_POST['dt_FechaIni'])) {
	$fechaIni = $_POST['dt_FechaIni'];
	$fechaFin = $_POST['dt_FechaFin'].' 23:59:59.00';
} else {
	$fechaIni = '';
	$fechaFin = '';
}

$sucs = $_SESSION["id_Sucursal"];
if (isset($_POST['id_Suc'])){
	$sucursal = $_POST['id_Suc'];
} else {
	$sucursal = $sucs;
}

if($_POST['idfolio'] != ''){
	$queryComplement = "and PP.st_Folio = '".trim($_POST['idfolio'])."' ";
}else{
	if($sucursal=='all'){
		$queryComplement = " 
		AND cs.id_SucursalClinica IN (SELECT  DISTINCT id_Sucursal FROM tbl_EvVentaOptica )
		AND vo.dt_FechaRegistro between @fechaIni and @fechaFin";
	}else{
		$queryComplement = " 
		AND cs.id_SucursalClinica = '".$sucursal."'
		AND vo.dt_FechaRegistro between @fechaIni and @fechaFin";
	}
	// $compQSucursal="";
	// if($sucursal>0){
	// 	$compQSucursal="and cs.id_SucursalClinica = @idSucursal";
	// }
}

$query0 = "
	declare @fechaIni datetime = '".$fechaIni."'
	declare @fechaFin datetime = '".$fechaFin."'
	
	IF (@fechaIni = '') BEGIN
		SET @fechaIni = GETDATE()
		SET @fechaFin = GETDATE()
	END
	
	SELECT 
		PP.id_PedidoOpticaPrevio,
		PP.id_PedidoOpticaProveedor,
		co.id_cotizacionOptica,
		PP.st_Folio as folio, 
		vo.id_VentaOptica id_EventoConcepto,
		us.id_UsuarioWeb,
		us.st_Nombre + ' ' + us.st_ApellidoPaterno + ' ' + us.st_ApellidoMaterno Nombre, 
		convert(varchar(15),vo.dt_FechaRegistro,111) [fecha compra], 	
		cs.st_Nombre Sucursal, 
		po.st_Nombre Producto, 
		po.st_Descripcion Descripcion, 
		po.st_Codigo Codigo,  
		co.i_Total total, 
		(sum(pu.i_Cantidad) - co.i_Total) saldo, 
		sum(pu.i_Cantidad) abono, 
		tg.st_BarcodeVenta ticket, 
		tg.st_Barcode preticket,
		usv.st_User UsuarioCaja, 
		case when sta.st_StatusPedidoOptica is null then 'Sin Estatus' else sta.st_StatusPedidoOptica end st_StatusPedidoOptica, 
		sta.id_StatusPedidoOptica,
		sta.st_BackColor,
		dt_FechaEnvioLaboratorio,
		dt_FechaRecibidoSucursal,
		dt_FechaEntregadoCliente,
		id_OperadorEnviaLaboratorio,
		id_OperadorRecibidoSucursal,
		id_OperadorEntregadoCliente
	FROM tbl_EvVentaOptica vo 
		inner join tbl_CotizacionOptica co on vo.id_VentaOptica = co.id_EventoVenta
		inner join tbl_paqueteOptica po on po.id_PaqueteOptica = co.id_PaqueteOptica
		inner join cat_SucursalClinica cs on cs.id_SucursalClinica = vo.id_Sucursal
		inner join tbl_PagosUsuarioWeb pu on pu.id_EventoConcepto = vo.id_VentaOptica and pu.id_Concepto in (8,9)
		inner join tbl_UsuariosWebCac us on us.id_UsuarioWeb = vo.id_usuarioWeb
		inner join tbl_TicketGeneral tg on tg.id_TicketGeneral = co.st_key
		inner join tbl_UsuarioSistemaWeb usv on usv.id_UsuarioSistemaWeb = tg.id_OperadorVendio
		left join tbl_pedidosOpticaProveedor pp on pp.id_EventoConcepto = vo.id_VentaOptica
		left join cat_StatusPedidoOptica sta on sta.id_StatusPedidoOptica = pp.id_Status
	WHERE co.id_Status = 3 
		".$queryComplement."
		group by vo.id_VentaOptica,us.st_Nombre,us.st_ApellidoPaterno, us.st_ApellidoMaterno,vo.dt_FechaRegistro, cs.st_Nombre, po.st_Nombre, po.st_Descripcion, 
		po.st_Codigo, co.i_Total, tg.st_BarcodeVenta, tg.st_Barcode,usv.st_User, sta.st_StatusPedidoOptica, sta.st_BackColor,PP.id_PedidoOpticaProveedor,us.id_UsuarioWeb,co.id_cotizacionOptica,PP.st_Folio,
		sta.id_StatusPedidoOptica,dt_FechaEnvioLaboratorio,
		dt_FechaRecibidoSucursal,
		dt_FechaEntregadoCliente,
		id_OperadorEnviaLaboratorio,
		id_OperadorRecibidoSucursal,
		id_OperadorEntregadoCliente,
		PP.id_PedidoOpticaPrevio,
		pu.id_TipoPago
		having (sum(pu.i_Cantidad) >= (co.i_Total * 0.30)) OR pu.id_TipoPago = '13'
		order by vo.dt_FechaRegistro desc
";
 //echo $query0;
$rquery0 = mssql_query($query0); 

$aux = 0;
$datos = ' [';

while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 

	if($aux > 0){ $datos .= ','; }
		$aux++;
	
	$dt_FechaEnvioLaboratorio = date("Y/m/d",strtotime($arrayQuery0['dt_FechaEnvioLaboratorio']));
	$dt_FechaRecibidoSucursal = date("Y/m/d",strtotime($arrayQuery0['dt_FechaRecibidoSucursal']));
	$dt_FechaEntregadoCliente = date("Y/m/d",strtotime($arrayQuery0['dt_FechaEntregadoCliente']));
	$id_StatusPedidoOptica = $arrayQuery0['id_StatusPedidoOptica'];
	$id_PedidoOpticaProveedor = $arrayQuery0['id_PedidoOpticaProveedor'];
	$folio = $arrayQuery0['folio'];
	$Nombre = utf8_encode($arrayQuery0['Nombre']);
	$fecha_compra = $arrayQuery0['fecha compra'];
	$Sucursal = utf8_encode($arrayQuery0['Sucursal']);
	$abono = $arrayQuery0['abono'];
	$total = $arrayQuery0['total'];
	$saldo = $arrayQuery0['saldo'];
	$Producto = htmlentities($arrayQuery0['Producto']);
	$st_StatusPedidoOptica = $arrayQuery0['st_StatusPedidoOptica'];
	$ticket = $arrayQuery0['ticket'];
	$UsuarioCaja = $arrayQuery0['UsuarioCaja'];
	$id_PedidoOpticaPrevio = $arrayQuery0['id_PedidoOpticaPrevio'];
	
	
	$query1 = "
		select 
			i_incompleto
		from
			tbl_PedidosOpticaPrevio
		where
			id_PedidoOpticaPrevio = '".$id_PedidoOpticaPrevio."'
	";
	
	$rquery1 = mssql_query($query1);
	$row1 = mssql_fetch_object($rquery1);
	
	$i_incompleto = $row1->i_incompleto;
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 1):
		
		$funcionCheckIn = "javascript:cambiarStatus('".$id_PedidoOpticaProveedor."',14)";
		$enviarLaboratorio = '<div id=\"envio'.$id_PedidoOpticaProveedor.'\"><table><tr><td valing=\"middle\">NO</td><td><a href=\"'.$funcionCheckIn.'\" title=\"Enviar a laboratorio\"><img border=\"0\" src=\"images/check.png\"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 8 && $arrayQuery0['id_StatusPedidoOptica'] <= 16){
			$enviarLaboratorio = '<table><tr><td valing=\"middle\">SI</td><td><img src=\"images/checkGris.png\"> '.$dt_FechaEnvioLaboratorio.'</td></tr></table>';
		}
		else{
			$enviarLaboratorio = 'Sin definir Pedido';
		}
		
	endif;
	
	if($i_incompleto == '1')
		$enviarLaboratorio = 'Sin definir Pedido';
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 11):
		
		$funcionCheckIn = "javascript:cambiarStatus('".$id_PedidoOpticaProveedor."',12)";
		$recibirEnSucursal = '<div id=\"recibo'.$id_PedidoOpticaProveedor.'\"><table><tr><td valing=\"middle\">NO</td><td><a href=\"'.$funcionCheckIn.'\" title=\"Recibir pedido\"><img border=\"0\" src=\"images/check.png\"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 12 && $arrayQuery0['id_StatusPedidoOptica'] <= 16 && $arrayQuery0['id_StatusPedidoOptica'] != 14){
			$recibirEnSucursal = '<table><tr><td valing=\"middle\">SI</td><td><img src=\"images/checkGris.png\"> '.$dt_FechaRecibidoSucursal.'</td></tr></table>';
		}
		else{
			$recibirEnSucursal = '--';
		}
		
	endif;
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 12):
		
		$funcionCheckIn = "javascript:cambiarStatus('".$id_PedidoOpticaProveedor."',13)";
		$entregarAcliente = '<div id=\"entrego'.$id_PedidoOpticaProveedor.'\"><table><tr><td valing=\"middle\">NO</td><td><a href=\"'.$funcionCheckIn.'\" title=\"Entregar a cliente\"><img border=\"0\" src=\"images/check.png\"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 13 && $arrayQuery0['id_StatusPedidoOptica'] != 14){
			$entregarAcliente = '<table><tr><td valing=\"middle\">SI</td><td><img src=\"images/checkGris.png\"> '.$dt_FechaEntregadoCliente.'</td></tr></table>';
		}
		else{
			$entregarAcliente = '--';
		}
		
	endif;
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 13 || $arrayQuery0['id_StatusPedidoOptica'] == 12):
		
		$funcionCheckIn = "JavaScript:abrir_pop3('300','300','enviarAgarantia.php?id_PedidoOpticaProveedor=".$id_PedidoOpticaProveedor."')";
		$garantia = '<a href=\"'.$funcionCheckIn.'\" title=\"Enviar a garantia\">Enviar a garantia</a>';
		
	else:
		if($arrayQuery0['id_StatusPedidoOptica'] > 14){
			$query2 = "
				SELECT 
					CONVERT(VARCHAR(10),t1.dt_fecha,103) AS fecha,
					t2.st_StatusPedidoOptica
				from 
					tbl_EvCambioStatusPedidosOptica t1
				inner join
					cat_StatusPedidoOptica t2 on t1.id_StatusNew = t2.id_StatusPedidoOptica
				WHERE id_StatusNew > 14 
					AND id_PedidoOpticaProveedor = '".$id_PedidoOpticaProveedor."'
				order by
					t1.id_CambioStatus desc
			";
			
			$rquery2 = mssql_query($query2);
			$row2 = mssql_fetch_object($rquery2);
			
			$garantia = $row2->st_StatusPedidoOptica." ".$row2->fecha;
		}
		else{
			$garantia = '--';
		}
	endif;
	
	
	$arrayQuery0['folio'] != '--' ? $imprimeFolio = '<a href=\"imprimeFolio.php?idFolio='.$arrayQuery0['folio'].'&idEvento='.$arrayQuery0['id_EventoConcepto'].'&idUsuarioWeb='.$arrayQuery0['id_UsuarioWeb'].'&idF='.$arrayQuery0['id_PedidoOpticaProveedor'].'\">Imprime Folio</a>' : $imprimeFolio = 'Sin folio';
	
	$ingresarPedido = '<a href=\"listaCotizacionesPaciente.php?idusuarioweb='.$arrayQuery0['id_UsuarioWeb'].'&idCotizacion='.$arrayQuery0['id_cotizacionOptica'].'&do=1\">Ingresar Pedido</a>';
	
	$datos .= '[
	"<center>'.$folio.'</center>",
	"<center>'.utf8_encode(trim($Nombre)).'</center>",
	"<center>'.$fecha_compra.'</center>",
	"<center>'.utf8_encode(trim($Sucursal)).'</center>",
	"<center>'.$abono.'</center>",
	"<center>'.$total.'</center>",
	"<center>'.$saldo.'</center>",
	"<center>'.$Producto.'</center>",
	"<center>'.utf8_encode(trim($st_StatusPedidoOptica)).'</center>",
	"<center>'.$ticket.'</center>",
	"<center>'.utf8_encode(trim($UsuarioCaja)).'</center>",
	"<center>'.$imprimeFolio.'</center>",
	"<center>'.$ingresarPedido.'</center>",
	"<center>'.$enviarLaboratorio.'</center>",
	"<center>'.$recibirEnSucursal.'</center>",
	"<center>'.$entregarAcliente.'</center>",
	"<center>'.$garantia.'</center>"]';

}

$datos .= ']';

echo $datos;

?>