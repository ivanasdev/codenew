<?php
require("../db.php");
session_start();


$tipoBusqueda = $_POST['tipoBusqueda'];
$idUsuario = $_SESSION["id_Operador"];
$idfolio = $_POST['idfolio'];

//$folioget=$_GET['folio'];









//VALID DATES 
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

if($_POST['idfolio'] != '' && $tipoBusqueda!=1  ){
	$queryComplement = "and PP.st_Folio = '".trim($_POST['idfolio'])."' ";
}



else{
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
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 

	if($aux > 0){ $datos .= ','; }
		$aux++;
	
	$dt_FechaEnvioLaboratorio = date("Y/m/d",strtotime($arrayQuery0['dt_FechaEnvioLaboratorio']));
	$dt_FechaRecibidoSucursal = date("Y/m/d",strtotime($arrayQuery0['dt_FechaRecibidoSucursal']));
	$dt_FechaEntregadoCliente = date("Y/m/d",strtotime($arrayQuery0['dt_FechaEntregadoCliente']));
	$id_StatusPedidoOptica = $arrayQuery0['id_StatusPedidoOptica'];
	$id_PedidoOpticaProveedor = $arrayQuery0['id_PedidoOpticaProveedor'];
	$folio = $arrayQuery0['folio'];
	$Nombre = htmlentities($arrayQuery0['Nombre']);
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
		$enviarLaboratorio = '<div id="envio'.$id_PedidoOpticaProveedor.'"><table><tr><td valing="middle">NO</td><td><a href="'.$funcionCheckIn.'" title="Enviar a laboratorio"><img border="0" src="images/check.png"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 8 && $arrayQuery0['id_StatusPedidoOptica'] <= 16){
			$enviarLaboratorio = '<table><tr><td valing=\"middle\">SI</td><td><img src="images/checkGris.png"> '.$dt_FechaEnvioLaboratorio.'</td></tr></table>';
		}
		else{
			$enviarLaboratorio = 'Sin definir Pedido';
		}
		
	endif;
	
	if($i_incompleto == '1')
		$enviarLaboratorio = 'Sin definir Pedido';
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 11):
		
		$funcionCheckIn = "javascript:cambiarStatus('".$id_PedidoOpticaProveedor."',12)";
		$recibirEnSucursal = '<div id="recibo'.$id_PedidoOpticaProveedor.'"><table><tr><td valing="middle">NO<td><td><a href="'.$funcionCheckIn.'" title="Recibir pedido"><img border="0" src="images/check.png"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 12 && $arrayQuery0['id_StatusPedidoOptica'] <= 16 && $arrayQuery0['id_StatusPedidoOptica'] != 14){
			$recibirEnSucursal = '<table><tr><td valing="middle">SI</td><td><img src="images/checkGris.png"> '.$dt_FechaRecibidoSucursal.'</td></tr></table>';
		}
		else{
			$recibirEnSucursal = '--';
		}
		
	endif;
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 12):
		
		$funcionCheckIn = "javascript:cambiarStatus('".$id_PedidoOpticaProveedor."',13)";
		$entregarAcliente = '<div id="entrego'.$id_PedidoOpticaProveedor.'"><table><tr><td valing="middle">NO</td><td><a href="'.$funcionCheckIn.'" title="Entregar a cliente"><img border="0" src="images/check.png"></a></td></tr></table></div>';
		
	else:
		
		if($arrayQuery0['id_StatusPedidoOptica'] >= 13 && $arrayQuery0['id_StatusPedidoOptica'] != 14){
			$entregarAcliente = '<table><tr><td valing="middle">SI</td><td><img src="images/checkGris.png"> '.$dt_FechaEntregadoCliente.'</td></tr></table>';
		}
		else{
			$entregarAcliente = '--';
		}
		
	endif;
	
	
	if($arrayQuery0['id_StatusPedidoOptica'] == 13 || $arrayQuery0['id_StatusPedidoOptica'] == 12):
		
		$funcionCheckIn = "JavaScript:abrir_pop3('300','300','enviarAgarantia.php?id_PedidoOpticaProveedor=".$id_PedidoOpticaProveedor."')";
		$garantia = '<a href="'.$funcionCheckIn.'" title="Enviar a garantia">Enviar a garantia</a>';
		
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
					AND id_StatusCambio=1
				ORDER BY
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
	
	
	$arrayQuery0['folio'] != '--' ? $imprimeFolio = '<a href="imprimeFolio.php?idFolio='.$arrayQuery0['folio'].'&idEvento='.$arrayQuery0['id_EventoConcepto'].'&idUsuarioWeb='.$arrayQuery0['id_UsuarioWeb'].'&idF='.$arrayQuery0['id_PedidoOpticaProveedor'].'">Imprime Folio</a>' : $imprimeFolio = 'Sin folio';
	
	$ingresarPedido = '<a href="listaCotizacionesPaciente.php?idusuarioweb='.$arrayQuery0['id_UsuarioWeb'].'&idCotizacion='.$arrayQuery0['id_cotizacionOptica'].'&do=1">Ingresar Pedido</a>';



	//VALIDACION DE DIAS EN QUE SE LIBERA EL PEDIDO 
	$mensajefolioatraso="";
	$mensajeverde="";
	$mensajenviolab="";
	$compra=strtotime($fecha_compra);
	$hoytest=date("2022-08-03");
	$hoy=date("Y/m/d");
	$hoydate=strtotime($hoy);
	$flag_bloqueo="";


	if($st_StatusPedidoOptica == "Nuevo"){
		$header="";
		$diferenciadias=abs(($compra - $hoydate)/86400);
		if($compra == $hoydate){
			$header='<tr class="p-3 mb-2 bg-white text-white">';
		}
		elseif($diferenciadias < 10){
			$header='<tr class="p-3 mb-2 bg-warning text-white">';
			$mensajeverde='<h3>El Folio: '.$folio.' lleva: '.$diferenciadias.' de retraso</h3> ';
		}
		elseif($diferenciadias > 10) {
			$header='<tr class="p-3 mb-2 bg-danger " style="color:white">';
			$mensajefolioatraso='		<h3>El Folio: '.$folio.' lleva: '.$diferenciadias.' de retraso</h3> ';
			

		}
	}

	
	elseif($st_StatusPedidoOptica == "En Proceso Laboratorio" || $st_StatusPedidoOptica == "Enviado a Sucursal" || $st_StatusPedidoOptica == "En Proceso Laboratorio" || $st_StatusPedidoOptica == "Recibido en Sucursal" || $st_StatusPedidoOptica == "Recibido en Laboratorio" ){
		$header='<tr class="p-3 mb-2 bg-white text-white">';
		$envialab=strtotime($dt_FechaEnvioLaboratorio);
		$diaspasadosenvio=abs(($envialab - $compra)/86400);
	



	}

	elseif($st_StatusPedidoOptica == "Entregado a Cliente" || $st_StatusPedidoOptica == "Garantia Opt." || $st_StatusPedidoOptica == "Garantia Lab." ){
		$header='<tr class="p-3 mb-2 bg-info text-white">';
		$envialab=strtotime($dt_FechaEntregadoCliente);
		$diaspasadosenvio=abs(($envialab - $compra)/86400);

			$mensajenviolab='<h3>El pedido: '.$folio.' tardo: '.$diaspasadosenvio.' en entregarse al cliente  </h3> ';
	}


	
	




	$listado.= '

	'.$header.'
	<td><center><h6>'.$folio.'</h6></center></td>
	<td><center><h6>'.utf8_encode(trim($Nombre)).'</h6></center></td>
	<td><center><h6><h6>'.$fecha_compra.'</h6></h6></center></td>
	<td><center><h6>'.utf8_encode(trim($Sucursal)).'</h6></center></td>	
	<td><center><h6>'.$abono.'</h6></center></td>
	<td><center><h6>'.$total.'</h6></center></td>
	<td><center><h6>'.$saldo.'</h6></center></td>
	<td><center><h6>'.$Producto.'</h6></center></td>
	<td><center><h6>'.utf8_encode(trim($st_StatusPedidoOptica)).'</h6></center></td>
	<td><center><h6>'.$ticket.'</h6></center></td>
	<td><center><h6>'.utf8_encode(trim($UsuarioCaja)).'</h6></center></td>
	<td><center><h6>'.$imprimeFolio.'</h6></center></td>
	<td><center><h6>'.$ingresarPedido.'</h6></center></td>
	<td><center><h6>'.$enviarLaboratorio.'</h6></center></td>
	<td><center><h6>'.$recibirEnSucursal.'</h6></center></td>
	<td><center><h6>'.$entregarAcliente.'</h6></center></td>
	<td><center><h6>'.$garantia.'</h6></center></td>
	</tr>'

	;



}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
<body>
	<div>
	<p>	<?= $mensajenviolab ?>
</p>
		<table cellpadding="0" cellspacing="0" border="0" class="display cell-border hover" id="example" width="100%">
		    <thead>
		        <tr>
		            <th>Folio</th>
		            <th>Nombre</th>
		            <th>Fecha de Compra</th>
		            <th>Sucursal</th>
		            <th>Abono</th>
		            <th>Total</th>
		            <th>Saldo</th>
		            <th>Producto</th>
		            <th>Status</th>
		            <th>Ticket</th>
		            <th>Usuario Caja</th>
		            <th>Imprime Folio</th>
		            <th>Ingresa Pedido</th>
		            <th>Listo para envio</th>
		            <th>Recibir en Sucursal</th>
		            <th>Entregar a Cliente</th>
		            <th>Garantia</th>     
		        </tr>
		    </thead>
		    <tbody>
		    	<?=$listado?>
		    </tbody>
		    <tfoot>
		        <tr>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Folio"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Nombre"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Fecha_Compra"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Sucursal"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Abono"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Total"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Saldo"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Producto"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Status"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Ticket"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Usuario_Caja"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Imprime_Folio"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Ingresa_Pedido"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Listo_Para_Envio"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Recibir_en_Sucursal"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Entregar_a_Cliente"/>
		            </th>
		            <th rowspan="1" colspan="1">
		            	<input class="search_init" type="text" name="Garantia"/>
		            </th>
		            <th rowspan="1" colspan="1"></th>
		        </tr>
		    </tfoot>	
		</table>
	</div>
	<!--Contador de dias de atraso en liberar el pedido a laboratorio -->
<div class="card">
	<p>
		<?= $mensajefolioatraso ?>
		<?= $mensajeverde ?>
	
	</p>
	
</div>
</body>
</html>