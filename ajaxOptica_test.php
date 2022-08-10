<style type="text/css">
<!--
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
-->
</style><br>
<? 
require ("../db.php");

header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

$empresa = $_GET['empresa'];
$token = $_GET['Id'];

$idUsuarioWeb = $_GET['idUsuarioWeb'];
require("porcentajeAplicado.php");

//#########################################################################################
// EMPLEADO INTERNO
if($idCliente == 18){
	echo "<br>Nombre: ".$stNombreCompleto;	
	echo "<br>ID Empleado Interno: ".$idNumeroEmpleado;	
	echo "<br><span style=\"color:green\">(TOTAL DE COMPRAS AL 50%) : ".$conteoLentes."</span><br><br>";	
	echo '<input type="hidden" id="id_Cliente" name="id_Cliente" value="'.$idCliente.'">';		
	//echo "<br>ID Usuario Web: ".$idUsuarioWeb."<br><br>";
}

//#########################################################################################
// SILAO
if($idCliente == 27){
	echo "<br><span style=\"color:#FF6600\">Programa: SILAO</span>";	
	echo "<br>Nombre: ".$stNombreCompleto;	
	echo "<br>Número de Tarjeta: ".$idNumeroEmpleado;	
	echo "<br><span style=\"color:green\">(DESCUENTO DEL 20%)</span><br><br>";	
	echo '<input type="hidden" id="id_Cliente" name="id_Cliente" value="'.$idCliente.'">';		
}
//#########################################################################################


///// CLIENTES
require_once("clases/class.OpticaSubrogado.php");
$objOpticaSubrogado = new OpticaSubrogado();



if( $_SESSION['id_TipoUsuario'] != 14 ){
	echo"
		<script>
			alert('Sesion expirada favor de cerrar sesion y volver a ingresar al modulo!');
			window.parent.location.href='logout.php';
		</script>
	";
}
$where = "";

/*if($_SESSION['id_Sucursal'] == 117 ){
	$complementoJOJUTLA="AND (st_Nombre NOT LIKE '%TRANSITIONS%')	 
	AND st_PrecioMiembro <2000
	AND st_Nombre NOT IN( 'SHAMIR Bifocal Invisible Poly Plus AR fotocromatico Café','SHAMIR Bifocal Invisible Poly Plus AR fotocromatico Gris', 
	'SHAMIR Bifocal Invisible Poly Plus fotocromatico Café','SHAMIR Bifocal Invisible Poly Plus fotocromatico Gris', 'SHAMIR PROGRESIVO AUTOGRAPH III CR-39',
	'SHAMIR PROGRESIVO AUTOGRAPH III POLY PLUS','SHAMIR PROGRESIVO AUTOGRAPH PLUS CR 39 W', 'SHAMIR Progresivo Digital Poly Plus AR fotocromatico Café',
	'SHAMIR Progresivo Digital Poly Plus AR fotocromatico Gris', 'SHAMIR Progresivo Digital Poly Plus fotocromatico Café','SHAMIR Progresivo Digital Poly Plus fotocromatico Gris', 'SHAMIR TALLADO CR TRANSITIONS','SHAMIR TALLADO CR TRANSITIONS DUO','SHAMIR TALLADO FLAT TOP CR TRANSITIONS','SHAMIR TALLADO FLAT TOP POLY AR', 'SHAMIR TALLADO POLY AR','SHAMIR TALLADO POLY TRANSITIONS AR','SHAMIR TALLADO PROGRESIVO PLUS POLY AR', 'SHAMIR TALLADO PROGRESIVO SAGITA POLY TRANSITIONS','SHAMIR TALLADO PROGRESIVO SAGITA TRANSITIONS', 'SHAMIR V. Sencillo CR-39 -AR Transitions Terminado (PROMOCION)','SHAMIR V. Sencillo CR-39 Transitions Terminado PROMOCION', 'SHAMIR V. Sencillo Poly Plus - AR fotocromatico Café','SHAMIR V. Sencillo Poly Plus AR fotocromatico Gris', 'SHAMIR V. Sencillo Poly Plus fotocromatico Café','SHAMIR V. Sencillo Poly Plus fotocromatico Gris', 'PROVEEDORA TALLADO FLAT TOP CR FOTOCROMATICO','PROVEEDORA TALLADO FLAT TOP CR FOTOCROMATICO AR','PROVEEDORA TALLADO POLY FOTOCROMATICO', 'PROVEEDORA TALLADO POLY FOTOCROMATICO AR','PROVEEDORA TALLADO PROGRESIVO CR FOTOCROMATICO','PROVEEDORA TALLADO PROGRESIVO CR FOTOCROMATICO AR', 'PROVEEDORA TALLADO PROGRESIVO POLY FOTOCROMATICO','PROVEEDORA TALLADO PROGRESIVO POLY FOTOCROMATICO AR', 'PROVEEDORA TALLADO YOUNGER CR FOTOCROMATICO','PROVEEDORA TALLADO YOUNGER CR FOTOCROMATICO AR','PROVEEDORA TERMINADO FLAT TOP CR FOTOCROMATICO', 'PROVEEDORA TERMINADO FLAT TOP CR FOTOCROMATICO AR','PROVEEDORA TERMINADO POLY FOTOCROMATICO AR','PROVEEDORATERMINADO POLY FOTOCROMATICO', 'PROVEEDORA TERMINADO PROGRESIVO CR AR FOTOCROMATICO','PROVEEDORA TERMINADO YOUNGER CR FOTOCROMATICO','PROVEEDORA TERMINADO YOUNGER CR FOTOCROMATICO AR' )
";	
//$where .= $complementoJOJUTLA;

}*/


//Unidad movil, sólo apareceran los ligados a esa sucursal
if($_SESSION['id_Sucursal'] == 82 ){
	$where .= " AND id_Sucursal = '82'";
}else{
	$where .= " AND id_Sucursal = '0'";
}
//$where .= " AND id_Sucursal = '0'";

$querypharma = "SELECT top(20) id_PaqueteOptica, st_Nombre, st_Codigo, st_PrecioMiembro, st_PrecioNoMiembro, id_Operador, 
dt_FechaRegistro, i_Activo, st_Descripcion, i_LenteContacto FROM tbl_PaqueteOptica 
WHERE i_Activo = 1 and (st_Nombre LIKE '%".$token."%') ".$where;

 $rquery = mssql_query($querypharma);
while($rowCiTas = mssql_fetch_array($rquery)){
	if($_GET['empresa'] == 0)  $precio = 0;
	else   $precio =$rowCiTas['nt_Costo'];
	$rowCiTas['disponibles'];
	if($rowCiTas['disponibles'] > 0){
		$stock = $rowCiTas['disponibles'];
	 	$txtdis = ""; 
	}else  { 
	 $txtdis = "disabled";
	 $stock = 0;
	}
	//PARA LENTES DE CONTACTO SE COBRA EL 100% empleados internos array(1193,1188,1165,1164,1166,1148,1149,1146,1147,1145,1150)
	if($idCliente == 18 && $rowCiTas['i_LenteContacto'] == 1 ){
		$precioMiembro = ($rowCiTas['st_PrecioMiembro'] * ($i_Porcentaje + 0.5)) + $rowCiTas['st_PrecioMiembro'];
		$precioNoMiembro = ($rowCiTas['st_PrecioNoMiembro'] * ($i_Porcentaje + 0.5)) + $rowCiTas['st_PrecioNoMiembro'];
	}
	else{
		$precioMiembro = ($rowCiTas['st_PrecioMiembro'] * $i_Porcentaje) + $rowCiTas['st_PrecioMiembro'];
		$precioNoMiembro = ($rowCiTas['st_PrecioNoMiembro'] * $i_Porcentaje) + $rowCiTas['st_PrecioNoMiembro'];
	}

	//////////////#########################################################################################################
	///////////////////////  AJALPAN - Si es Paquete 1 mica CR-39 al año
	if( $objOpticaSubrogado->esPacienteAJA($idUsuarioWeb) && in_array($rowCiTas['id_PaqueteOptica'],array(631,1044)) ):
		//Total de Paquete 1 mica CR-39 al año pone en CERO el precio
		$totalOpticaAJA = $objOpticaSubrogado->obtieneTotalTicketsOpticaAJA($idUsuarioWeb);
		if($totalOpticaAJA < 4){
			$precioMiembro = 0;
			$precioNoMiembro = 0;
		}
	endif;
	//////////////#########################################################################################################

	//////////////#########################################################################################################
	///////////////////////  CAMPECHE - Si es PAQUETE 1 y MICA TERMINADO CR W 1 al año
	if( $objOpticaSubrogado->esPacienteCAM($idUsuarioWeb) && in_array($rowCiTas['id_PaqueteOptica'],array(631,1044)) ):
		//Total de Paquete 1 mica CR-39 al año pone en CERO el precio
		$totalOpticaAJA = $objOpticaSubrogado->obtieneTotalTicketsOpticaCAM($idUsuarioWeb);
		if($totalOpticaAJA < 1){
			$precioMiembro = 0;
			$precioNoMiembro = 0;
		}
	endif;
	//////////////#########################################################################################################

	//////////////#########################################################################################################
	///////////////////////  PUEBLA - Si es PAQUETE 1 y MICA TERMINADO CR W 1 al año
	if( $objOpticaSubrogado->esPacientePUEBLA($idUsuarioWeb) && in_array($rowCiTas['id_PaqueteOptica'],array(631,1044)) ):
		//Total de Paquete 1 mica CR-39 al año pone en CERO el precio
		$totalOpticaPUEBLA = $objOpticaSubrogado->obtieneTotalTicketsOpticaPUEBLA($idUsuarioWeb);
		if($totalOpticaPUEBLA < 1){
			$precioMiembro = 0;
			$precioNoMiembro = 0;
		}
	endif;
	//////////////#########################################################################################################
	?>
	<input type="checkbox" class="listadoPaqueteOptica" id="Producto_<?=$rowCiTas['id_PaqueteOptica']?>"  name="Producto_<?=$rowCiTas['id_PaqueteOptica']?>"  value="<?=$rowCiTas['id_PaqueteOptica']?>" onclick="javascript:changeAjax('ServiciosOptica.php?stock=<?=$stock?>&empresa=<?=$rowCiTas['id_PaqueteOptica']?>', 'Producto_<?=$rowCiTas['id_PaqueteOptica']?>', 'Div_SubEstados_<?=$rowCiTas['id_PaqueteOptica']?>');" />
	<strong> 
	<?=$rowCiTas['st_Nombre']?>
	<!--<br>Almacen: 
	<?=$stock?>-->

	<br>
	Precio Miembro $ 
	<?=number_format($precioMiembro,2)?>
	<br>
	Precio no miembro $ 
	<?=number_format($precioNoMiembro,2)?>

	</strong><br> Detalle de paquete:<br>
	<?

	$querydetalle = "SELECT fernandoruiz.cat_ServicioOptica.st_ServicioOptica
	FROM tbl_PaqueteOpticaServicios 
	INNER JOIN fernandoruiz.cat_ServicioOptica ON tbl_PaqueteOpticaServicios.id_ServicioOptica = fernandoruiz.cat_ServicioOptica.id_ServicioOptica
	WHERE (tbl_PaqueteOpticaServicios.id_PaqueteOptica = ".$rowCiTas['id_PaqueteOptica'].")
	ORDER BY fernandoruiz.cat_ServicioOptica.st_ServicioOptica";
	$rquerydetalle = mssql_query($querydetalle);
	while($rowCiTasdetail = mssql_fetch_array($rquerydetalle))
	{ 
		?><br>
		
		<?=utf8_encode($rowCiTasdetail['st_ServicioOptica'])?>
		<? } ?>
		
		<div id="Div_SubEstados_<?=$rowCiTas['id_PaqueteOptica']?>" ></div>      
		
		<br><hr>
<? 
	}
//}
?>

<?php mssql_close(); ?>
