<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
include("../../../Cedis/CEDDevolucion/class.Formateo.php");
$objFormateo = new Formateo();

session_start();

$token = $_POST['token'];
$idAreaInsumo = $_POST['idAreaInsumo'];
$idSucursal = $_SESSION["id_Sucursal"];

/*$query0 = "SELECT 	
	t1.id_ProductoAlmacen,
	t1.id_upc, 
	t1.st_Nombre, 
	t1.st_SA,
	isnull(t2.Stock,0) as Stock
FROM cat_ProductosAlmacenMaster t1
LEFT JOIN(
	SELECT id_ProductoAlmacen, SUM(Stock) as Stock FROM view_CEDStockCedisCaducidad GROUP BY id_ProductoAlmacen
) t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
WHERE t1.i_Activo = '1' AND (t1.id_upc = '".$token."' OR t1.st_SA LIKE '%".$token."%' OR t1.st_Nombre LIKE '%".$token."%')";*/


$query0 = "SELECT 	
	t1.id_ProductoAlmacen,
	t1.id_upc, 
	t1.st_Nombre, 
	t1.st_SA,
	isnull(t2.Stock,0) as Stock
FROM cat_ProductosAlmacenMaster t1
LEFT JOIN(
	SELECT SUM(i_Cantidad) as Stock, id_ProductoAlmacen FROM tbl_SUCStockAlmacenInsumos 
	WHERE id_Sucursal = '".$idSucursal."' AND id_AreaInsumos = '".$idAreaInsumo."' GROUP BY id_ProductoAlmacen
) t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
WHERE t1.i_Activo = '1' AND (t1.id_upc = '".$token."' OR t1.st_SA LIKE '%".$token."%' OR t1.st_Nombre LIKE '%".$token."%')";

		
		$rquery0 = mssql_query($query0);
		$totalProds = mssql_num_rows($rquery0);
		
		if($totalProds > 0){
			$i = 0;
			$tabla = ' 			
			<table class="fancyTable" id="myTable01" cellpadding="0" cellspacing="0" width="100%">
				<thead>
				<tr>
                    <th>No</th>
                    <th>Codigo Barras</th>
                    <th width="300px">Descripci&oacute;n</th>
					<th>Caducidad</th>
                    <th>Lote</th>				                   
                    <th>Cantidad</th>
					<th>Stock</th>			
					<th>Observaciones</th>
                    <th>+</th>
                </tr>
				</thead>
    			<tbody>
			';
			
			while($arrayQuery0 = mssql_fetch_array($rquery0)){
			$i++;
			
			$stBarcode = $arrayQuery0["id_upc"];
			$stNombre = $arrayQuery0["st_Nombre"];
			$stSA = $arrayQuery0["st_SA"];
			$idProductoAlmacen = $arrayQuery0["id_ProductoAlmacen"];
			$existencia = $arrayQuery0["Stock"];

			
			$botonSalvar = '<input type="button" value="+" onClick="JavaScript:agregaProducto('.$i.');"/>';
											
			$tabla .= '
				<tr>
					<td align="center" style="background-color:#CCC;">
					'.$i.'
					<input type="hidden" id="idProductoAlmacen_'.$i.'" name="idProductoAlmacen_'.$i.'" value="'.$idProductoAlmacen.'" />						
					</td>
					
					<td align="center">'.$stBarcode.'</td>
					<td>
					'.$stNombre.'<br><br>
					<strong>Sustancia Activa:</strong> '.$stSA.'
					</td>
					
					<td align="center">
						<input type="text" size="8" id="fechaCaducidad_'.$i.'" name="fechaCaducidad_'.$i.'" class="fcaducidad" placeholder="Caducidad" readonly/>			
					</td>
					<td align="center">
						<input type="text" size="8" id="lote_'.$i.'" name="lote_'.$i.'" placeholder="Lote" />
					</td>																							
					<td align="center">
						<input type="text" size="4" id="cantidad_'.$i.'" name="cantidad_'.$i.'" class="numeroEntero" placeholder="0" />
					</td>
					<td align="center">'.$existencia.'</td>															
					<td>
					<center>
					<textarea id="observaciones_'.$i.'" name="observaciones_'.$i.'" rows="2"></textarea>
					</center>
					</td>
					<td align="center">
						'.$botonSalvar.'
					</td>
				</tr>
			';	
			}
			
			$tabla .= "
			</tbody>
			</table>
			";						
			
			echo $tabla;
		}
		else{
			echo "No se encontraron coincidencias de su búsqueda en el catálogo general...";
		}



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>

<script type="text/javascript">
$(document).ready(function() {
// Handler for .ready() called.


});
</script>

</head>

<body>
</body>
</html>


