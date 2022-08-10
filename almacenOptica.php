<?php
 require("../db.php");
 session_start();
 
 $query = "exec sp_getFacturasOptica ".$_SESSION["id_Sucursal"];
  $rowdata = mssql_query($query); 
 
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Almacén Optica</title>
	<link rel="stylesheet" href="../styles/style_tables.css" type="text/css">
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" href="../styles/optica.css" type="text/css">
	<script type="text/javascript">

		function abrir(url) { 
		open(url,'','top=100,left=100,width=400,height=450'); 
		} 
	</script>	
	<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=580, height=280, top=85, left=140";
window.open(pagina,"",opciones);
 
	 
}
</script>
</head>
<body>
	
	<div id="div_globalAlmacen">
		<h1>Almac&eacute;n &Oacute;ptica</h1> 
		
		<div id="div_ligas">
			<menu id="menuLigasAlmacen">
				<?php if ($_SESSION['b_Admin']== 1) { ?>
					<li id="nuevaFact"><a href="newFactura.php" target="popup" onClick="window.open(this.href, this.target, 'width=800,height=400'); return false;">Nueva Factura</a></li>
					<li id="traspasos"><a href="traspasoProductoSucursal.php" target="popup" onclick="window.open(this.href, this.target, 'width=550,height=500,scrollbars=yes'); return false;">Traspaso a sucursales</a></li>
					<li id="movimientos"><a href="buscarMovimientoProducto.php" target="popup" onclick="window.open(this.href,this.target,'width=700,height=300,scrollbars=yes'); return false;">Buscar Movimientos Traspasos</a></li>
					<li id="aceptarTraspasos"><a href="mistraspasos.php" target="popup" onclick="window.open(this.href,this.target, 'width=750, height=550,scrollbars=yes'); return false;">Mis traspasos pendientes</a></li>
					<li id="Stock"><a href="buscarStockSucursal.php" target="popup" onclick="window.open(this.href,this.target, 'width=750, height=550,scrollbars=yes'); return false;">Stock Sucursal</a></li>
					<li id="nuevoCliente"><a href="newClienteOptica.php" tarjet="popup" onclick="window.open(this.href,this.target, 'width=800,height=400,scrollbars=yes'); return false;">Nuevo Cliente</a></li>
					<li id="ListaClientesOptica"><a href="listaClientesOptica.php" tarjet="popup" onclick="window.open(this.href,this.target, 'width=800,height=400,scrollbars=yes'); return false;">Lista Clientes</a></li>			
				<?php }else { ?>
					<li id="traspasos"><a href="traspasoProductoSucursal.php" target="popup" onclick="window.open(this.href, this.target, 'width=550,height=500'); return false;">Traspaso a sucursales</a></li>
					<li id="movimientos"><a href="buscarMovimientoProducto.php" target="popup" onclick="window.open(this.href,this.target,'width=700,height=300,scrollbars=yes'); return false;">Buscar Movimientos Traspasos</a></li>
					<li id="aceptarTraspasos"><a href="mistraspasos.php" target="popup" onclick="window.open(this.href,this.target, 'width=750, height=550,scrollbars=yes'); return false;">Mis traspasos pendientes</a></li>
					<li id="Stock"><a href="buscarStockSucursal.php" target="popup" onclick="window.open(this.href,this.target, 'width=750, height=550,scrollbars=yes'); return false;">Stock Sucursal</a></li>			
				<?php
				} 
				?>
				
			</menu>
		</div>
		<hr>
		<div id="div_lista">
			<h2>Facturas del día:</h2>
			 
			<table id="rounded-corner" summary="2007 Major IT Companies' Profit">
		    <thead>
		    	<tr>
		        	<th scope="col" class="rounded-company">Cliente</th>
		            <th scope="col" class="rounded-q1">No.Factura</th>
		            <th scope="col" class="rounded-q2">Sucursal</th>
		            <th scope="col" class="rounded-q3">Fecha Captura</th>
		            <th scope="col" class="rounded-q3">Subtotal</th>
		            <th scope="col" class="rounded-q3">Iva</th>
		            <th scope="col" class="rounded-q3">Total</th>
		            <th scope="col" class="rounded-q3">Concepto</th>
		            <th scope="col" class="rounded-q4">Ver Productos</th>
		        </tr>
		    </thead>
		        <tfoot>
		    	<tr>
		        	<td colspan="8" class="rounded-foot-left"><em>Estas son las facturas que se han capturado durante el día.</em></td>
		        	<td class="rounded-foot-right">&nbsp;</td>
		        </tr>
		    </tfoot>
		    <tbody>
		    	<?php
				while ($row = mssql_fetch_array($rowdata)) {
				?>
				<tr>
					<td><?=$row['id_Cliente']?></td>
					<td><?=$row['st_NoFactura']?></td>
					<td><?=$row['id_SucursalFactura']?></td>
					<td><?=$row['dt_FechaFactura']?></td>
					<td><?=$row['i_Subtotal']?></td>
					<td><?=$row['i_Iva']?></td>
					<td><?=$row['i_Total']?></td>
					<td><?=$row['st_Concepto']?></td>
					<td> <a  href="javascript:Abrir_ventana('productosFacturaOptica.php?idFactura=<?=$row['id_FacturaOptica']?>');">Ver productos...</a> </td>
				</tr>
				<?php } ?>    
		    </tbody>
		</table>
		</div>
	</div>
	
</body>
</html>
