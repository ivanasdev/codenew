<?php
$ruta2index = "../../../../";
include($ruta2index.'dbConexion.php');
include("../../../Cedis/CEDDevolucion/class.Formateo.php");
$objFormateo = new Formateo();

session_start();

$token = $_POST['token'];
$idAreaInsumo = $_POST['idAreaInsumo'];
$idSucursal = $_SESSION['id_Sucursal'];

$query0 = "SELECT 	
	t2.id_Sucursal,
	t2.id_ProductoAlmacen,
	t2.dt_FechaCaducidad,
	t2.st_Lote,
	t2.st_Ubicacion,
	SUM(t2.i_Cantidad) as Stock,
	t1.id_upc, 
	t1.st_Nombre, 
	t1.st_SA,
	t3.st_Nombre as areaInsumos
FROM cat_ProductosAlmacenMaster t1
INNER JOIN tbl_SUCStockAlmacenInsumos t2 ON t1.id_ProductoAlmacen = t2.id_ProductoAlmacen
INNER JOIN cat_AreaInsumos t3 ON t2.id_AreaInsumos = t3.id_AreaInsumos
WHERE t2.id_Sucursal = '".$idSucursal."' AND (t1.id_upc = '".$token."' OR t1.st_SA LIKE '%".$token."%' OR t1.st_Nombre LIKE '%".$token."%')
AND t2.id_AreaInsumos = '".$idAreaInsumo."'
GROUP BY 
	t2.id_Sucursal,
	t2.id_ProductoAlmacen,
	t2.dt_FechaCaducidad,
	t2.st_Lote,
	t2.st_Ubicacion,
	t1.id_upc, 
	t1.st_Nombre, 
	t1.st_SA,
	t3.st_Nombre";

		
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
                    <th>Stock</th>
                    <th>Area</th>
					<th>Cant Salida</th>
					<th>Motivo Salida</th>
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
			$dtFechaCaducidad = $arrayQuery0["dt_FechaCaducidad"];
			$stLote = $arrayQuery0["st_Lote"];
			$stUbicacion = $arrayQuery0["st_Ubicacion"];
			$areaInsumos = $arrayQuery0["areaInsumos"];
			

			
			$botonSalvar = '<input type="button" value="+" onClick="JavaScript:agregaProducto('.$i.');"/>';
											
			$tabla .= '
				<tr>
					<td align="center" style="background-color:#CCC;">
					'.$i.'
					<input type="hidden" id="idProductoAlmacen_'.$i.'" name="idProductoAlmacen_'.$i.'" value="'.$idProductoAlmacen.'" />
					<input type="hidden" id="fechaCaducidad_'.$i.'" name="fechaCaducidad_'.$i.'" value="'.$dtFechaCaducidad.'" />
					<input type="hidden" id="lote_'.$i.'" name="lote_'.$i.'" value="'.$stLote.'" />					
					<input type="hidden" id="stock_'.$i.'" name="stock_'.$i.'" value="'.$existencia.'" />
					<input type="hidden" id="ubicacionSucursal_'.$i.'" name="ubicacionSucursal_'.$i.'" value="'.$stUbicacion.'" />			
					</td>
					
					<td align="center">'.$stBarcode.'</td>
					
					<td>
					'.$stNombre.'<br><br>
					<strong>Sustancia Activa:</strong> '.$stSA.'
					</td>
					
					<td>
					<label id="caducidad_'.$i.'" style="color:'.$colorCaducidad.'">'.Formateo::fecha("Y-m",$dtFechaCaducidad).'</label>				
					</td>
					
					<td>'.$stLote.'</td>											

					<td align="center">'.$existencia.'</td>																									
				
					<td align="center">'.$areaInsumos.'</td>
				
					<td align="center">
						<input type="text" size="4" id="cantidad_'.$i.'" name="cantidad_'.$i.'" class="numeroEntero" placeholder="0" />
					</td>
					
					<td>
					<center>
					<textarea id="motivo_'.$i.'" name="motivo_'.$i.'" rows="2"></textarea>
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


