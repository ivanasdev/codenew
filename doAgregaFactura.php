<?php
require("../db.php");

$id_Pedido = $_GET['id_Pedido'];
$i_Factura = $_GET['i_Factura'];

$query= "UPDATE tbl_pedidosOpticaProveedor SET i_FacturaProveedor = '".$i_Factura."' WHERE id_PedidoOpticaProveedor = ".$id_Pedido;
$res = mssql_query($query);
	
?>
