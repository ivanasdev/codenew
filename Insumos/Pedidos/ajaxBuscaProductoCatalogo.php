<?php
$ruta2index = "../../../../";
include($ruta2index . 'dbConexion.php');
//include("../../../Cedis/CEDDevolucion/class.Formateo.php");
//include("../class.CatalogosInsumos.php");
//$objFormateo = new Formateo();

$idAreaInsumo = $_POST['idAreaInsumo'];

session_start();
$idSucursal = $_SESSION['id_Sucursal'];
//$idSucursal = $_SESSION["id_Sucursal"];
$token = utf8_decode($_POST['token']);




$errores="
<script>
alert('Insumo no asignado');
</script>

";

	//Busca todos los id´s asignados a la sucursal. 
	$query="SELECT * FROM tbl_RelSucursalInsumosA WHERE id_Sucursal='".$idSucursal."'  ";
	$resI=mssql_query($query);
	$arrayinsumos=mssql_fetch_array($resI);
	
	//Cadena con todos los id´s de insumos 
	$insumosasignados="";
	$area="";
	switch($idAreaInsumo){
		case 2;
		//ARE AMEDICA
		$insumosasignados=$arrayinsumos['st_InsumosA2'];
		$area=2;
		break;
		//OPTICA
		case 3;
		$insumosasignados=$arrayinsumos['st_InsumosA3'];
		$area=3;
		break;
        //DENTAL
		case 4;
		$insumosasignados=$arrayinsumos['st_InsumosA4'];
		$area=4;
		break;
        //LAB
		case 5;
		$insumosasignados=$arrayinsumos['st_InsumosA5'];
		$area=5;
		break;
        //GIN
		case 6;
		$insumosasignados=$arrayinsumos['st_InsumosA6'];
		$area=6;
		break;

	}

	$cadenai_explode=explode(",",$insumosasignados);
	
	//Tabla temporal para generar vista 
	$querytmp="
	CREATE TABLE #insumostmp(
		id_Sucursal int,
		id_Insumo int,
		id_AreaInsumo int
		)
		";
		$restmp=mssql_query($querytmp);
		//ciclo pàra conusltar nombre de las id´s 
		foreach($cadenai_explode as $indexi => $insumo){
			$queryloop="
			INSERT INTO #insumostmp(id_Sucursal,id_Insumo,id_AreaInsumo) VALUES(".$idSucursal.", ".$insumo.",".$area.") ";
			$resq=mssql_query($queryloop);
			//echo $queryloop;
		}


	//Crear tabla crear tabla
	$querycatalogo="

			
	SELECT t1.id_Sucursal, t1.id_Insumo, t2.st_Nombre AS INSUMO, t1.id_AreaInsumo, t3.st_Nombre as AREA FROM #insumostmp t1
	INNER JOIN cat_CEDInsumos t2 on t1.id_Insumo=t2.id_Insumo 
	INNER JOIN cat_AreaInsumos t3 on t1.id_AreaInsumo=t3.id_AreaInsumos
	WHERE t1.id_Sucursal=".$idSucursal." 
	 AND t2.st_Nombre COLLATE Latin1_General_CI_AI LIKE '%" . $token . "%' COLLATE Latin1_General_CI_AI 
	 


	
	";
	$rescat=mssql_query($querycatalogo);

	$i=0;
	while($arrayselec=mssql_fetch_array($rescat)){
		
		$i++;
		$id_Insumo=$arrayselec['id_Insumo'];
		$NombreInsumo=$arrayselec['INSUMO'];
		$areaNombre=$arrayselec['AREA'];
		$tabla="";
		
		$tabla.= ' 			
		<table class="fancyTable" id="myTable01" cellpadding="0" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>No</th>
				<th>Area de Insumo</th>
				<th width="300px">Descripci&oacute;n</th>    
				<th>Cantidad</th>
				<th>Observaciones</th>
				<th>+</th>
			</tr>
			</thead>
			<tbody>
		';

		$botonSalvar = '<input type="button" value="+" onClick="JavaScript:agregaProducto(' . $i . ');"/>';
		$tabla .= '
					<tr>
						<td align="center" style="background-color:#CCC;">
						' . $i . '
						<input type="hidden" id="idInsumo_' . $i . '" name="idInsumo_' . $i . '" value="' . $id_Insumo . '" />						
						</td>
						<td><center>' . utf8_encode($areaNombre) . '</center></td>					
						<td>' .utf8_encode($NombreInsumo) . '</td>
																									
						<td align="center">
							<input type="text" size="4" id="cantidad_' . $i . '" name="cantidad_' . $i . '" class="numeroEntero" placeholder="0" />
						</td>															
						<td>
						<center>
						<textarea id="observaciones_' . $i . '" name="observaciones_' . $i . '" rows="2"></textarea>
						</center>
						</td>
						<td align="center">
							' . $botonSalvar . '
						</td>
					</tr>
				';
				
	$tabla .= "
	</tbody>
	</table>
	";

	//		DROP TABLE #insumostmp

	
	echo $tabla;

}
if(!$rescat){
	echo $errores;
}
else{
	$querydrop="DROP TABLE #insumostmp";
	//$resdrop=mssql_query($querydrop);
}


?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>PEDIDO DE INSUMOS</title>

	<script type="text/javascript">
		$(document).ready(function() {
			// Handler for .ready() called.


		});
	</script>


</head>

<body>
</body>

</html>