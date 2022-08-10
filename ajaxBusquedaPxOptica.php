<?php
$ruta2index = "../../";
require($ruta2index.'dbConexion.php');
session_start();

include("../mostrador/clases/class.CatalogosMostrador.php");
include("../class.TicketGeneral.php");
include("../class.PacienteGeneral.php");
$objTicketGeneral = new TicketGeneral();
$getDatosSucursal = $objTicketGeneral->getDatosSucursal();
$idClienteDB = $getDatosSucursal->idClienteDB;
$objPacienteGeneral = new PacienteGeneral();
$objCatalogosMostrador = new CatalogosMostrador();

$where = "";
if( isset($_POST["nombrePaciente"]) && trim($_POST["nombrePaciente"]) != "" ){

	$token = utf8_decode(trim($_POST["nombrePaciente"]));
	$where = " AND ((RTRIM(LTRIM(st_Nombre)) + ' ' + RTRIM(LTRIM(st_ApellidoPaterno)) like  '%".$token."%') OR
	(RTRIM(LTRIM(st_Nombre)) + ' ' + RTRIM(LTRIM(st_ApellidoPaterno))+ ' ' + RTRIM(LTRIM(st_ApellidoMaterno)) COLLATE Latin1_General_CI_AI like  '%".$token."%') OR
	(RTRIM(LTRIM(st_Nombre)) + ' ' + RTRIM(LTRIM(st_ApellidoMaterno)) like  '%".$token."%') OR
	(st_Nombre like  '%".$token."%') OR (st_ApellidoPaterno like '%".$token."%') OR 
	(st_ApellidoMaterno like  '%".$token."%') OR (st_Documento like '%".$token."%'))";

	if( $_POST["optica"] == 1 )
	$where .= " AND (st_Nombre like '%optica%')";

}

if( isset($_POST["idUsuarioWeb"]) && trim($_POST["idUsuarioWeb"]) != ""){
	$where = " AND id_UsuarioWeb = '".intval($_POST["idUsuarioWeb"])."'";
} 

$query0 = "
SELECT TOP 1000
	id_UsuarioWeb,
	UPPER(st_Nombre+' '+st_ApellidoPaterno+' '+st_ApellidoMaterno) as nombrePaciente,
	st_Documento,
	st_Direccion,
	dt_FechaRegistro
FROM tbl_UsuariosWebCac t1 WHERE i_Activo = '1' ".$where." ORDER BY t1.dt_FechaRegistro DESC";	


//Para las unidades moviles Antena Social solo aparecen los pacientes registrados en esas unidades
session_start();
$query2 = "SELECT id_RhModeloNegocio FROM cat_SucursalClinica WHERE id_SucursalClinica = '".$_SESSION["id_Sucursal"]."'";
$rquery2 = mssql_query($query2);
$arrayQuery2 = mssql_fetch_array($rquery2);
$idModeloNegocio = $arrayQuery2["id_RhModeloNegocio"];
if($idModeloNegocio == 6){
	$query0 = "
	SELECT TOP 1000
		id_UsuarioWeb,
		UPPER(st_Nombre+' '+st_ApellidoPaterno+' '+st_ApellidoMaterno) as nombrePaciente,
		st_Documento,
		st_Direccion,
		dt_FechaRegistro
	FROM tbl_UsuariosWebCac t1 WHERE i_Activo = '1' ".$where." 
	AND t1.id_Sucursal = '".$_SESSION["id_Sucursal"]."'
	ORDER BY t1.dt_FechaRegistro DESC";			
}

	
$rquery0 = mssql_query($query0);
$listado = "";
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){ 
	
	$idUsuarioweb = $arrayQuery0['id_UsuarioWeb'];
	$nombrePaciente = $objCatalogosMostrador->mostrar($arrayQuery0['nombrePaciente']);
	$stDocumento = $arrayQuery0['st_Documento'];
	$stDireccion = $objCatalogosMostrador->mostrar($arrayQuery0['st_Direccion']);
	$dtFechaRegistro = $objCatalogosMostrador->mostrarFecha($arrayQuery0['dt_FechaRegistro']);
	
	/*if( !$stDocumento || trim($stDocumento) == '' || $stDocumento == 'null' ){
            $stDocumento = "(Sin Membresia)";
        }else{
            $stDocumento = "(".$stDocumento.")";
        }*/
		
	if( $stDocumento > 0 ){
		$stDocumento = '<img style="vertical-align:middle" width="16px"  src="../mostrador/images/CMembresia.png" title="Con Membresia ('.$stDocumento.')" />';
	}else{
		$stDocumento = '';	
	}	
	/***********************************************************************************************************************/
	//// Pone solo lectura el nombre si es Cliente Emp Interno
	///////////////////////////////////////////////
	$leyendaEmpleado = "";
	$query1 = "SELECT count(*) as conteo FROM cat_EMPEmpleados WHERE id_UsuarioWeb = '".$idUsuarioweb."'";
	$rquery1 = mssql_query($query1);
	$arrayQuery1 = mssql_fetch_array($rquery1);
	$conteo = $arrayQuery1["conteo"];
	if($conteo > 0 && $idUsuarioweb > 0){		
		$leyendaEmpleado = '<img style="vertical-align:middle" width="16px" src="../mostrador/images/empleado.png" title="Empleado Interno ML" />';
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	/***********************************************************************************************************************/
	//// LEYENDA SUBROGADO
	///////////////////////////////////////////////
	$leyendaSubrogado = "";	
	
	//###########################################################################################################
	////// CLIENTES SUBROGADOS
	$idCall = 10;
	$idCall2 = 100000;
	$idSucursal = $_SESSION["id_Sucursal"];

	$getTipoPacienteOptica = $objPacienteGeneral->tipoPaciente($idUsuarioweb,1);
	$tipoPacienteOptica = $getTipoPacienteOptica['pacienteCliente']; /* INDICA Si es paciente surogado y particiipa para otyica*/ 

	$getDataClienteSubrogado = $objPacienteGeneral->getDataClienteSubrogado($tipoPacienteOptica, 4);
	// $id_ReglaSubrogado = $getDataClienteSubrogado->id_ReglaSubrogado;	
	$id_ClienteSubrogado = $getDataClienteSubrogado->id_Cliente;	
	$st_NombreCliente = $getDataClienteSubrogado->st_NombreCliente;	

	$whereComplemento = '';

	if ($id_ClienteSubrogado > 0) {
		$whereComplemento = "
		UNION ALL
		SELECT id_UsuarioWeb, '".$st_NombreCliente."' FROM cat_SUBEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = 1 AND id_Cliente =  '".$id_ClienteSubrogado."' ";
	}
	// $i_CantidadPaquetesOptica = $getDataClienteSubrogado->i_CantidadMedicamentos;	
	// $id_Intervalo = $getDataClienteSubrogado->id_Intervalo;	
	// $st_NombreIntervalo = $getDataClienteSubrogado->st_NombreIntervalo;	
	// $id_Validacion = $getDataClienteSubrogado->id_Validacion;	
	// $st_NombreTipoValidacion = $getDataClienteSubrogado->st_NombreTipoValidacion;
	
	
	$flagSMA = in_array($idSucursal,array($idCall,111,113))? 1 : 0;
	$flagCUA = in_array($idSucursal,array($idCall,131,132,157))? 1 : 0;
	$flagMH = in_array($idSucursal,array($idCall,64))? 1 : 0;
	$flagDSC = in_array($idSucursal,array($idCall,111,113))? 1 : 0;
	$flagCHI = in_array($idSucursal,array($idCall,167,168,169))? 1 : 0;
	$flagZIN = in_array($idSucursal,array($idCall,183,184))? 1 : 0;
	$flagSIL = in_array($idSucursal,array($idCall,185))? 1 : 0;
	$flagCAM = in_array($idSucursal,array($idCall,194,195,196,197,198,199,200,201,202))? 1 : 0;
	$flagPUE = in_array($idClienteDB,array(39,41))? 1 : 0;	
	
	if($idUsuarioweb > 0):
		$query1 = "
		SELECT id_UsuarioWeb,'San Miguel de Allende' as clienteSubrogado FROM cat_SMAEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagSMA."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Cuajimalpa' FROM cat_CUAEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagCUA."' = 1 
		UNION ALL
		SELECT id_UsuarioWeb,'Miguel Hidalgo' FROM cat_MHPacientes 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagMH."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Organismo Descentralizado' FROM cat_DSCEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagDSC."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Chicoloapan' FROM cat_CHIEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagCHI."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Zinacantepec' FROM cat_ZINEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagZIN."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Silao' FROM cat_SILEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = '1' AND '".$flagSIL."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Campeche' FROM cat_CAMEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND id_TipoEmpleado IN (32,34) AND i_Activo = 1 AND '".$flagCAM."' = 1
		UNION ALL
		SELECT id_UsuarioWeb,'Puebla' FROM cat_PUEEmpleados 
		WHERE id_UsuarioWeb = '".$idUsuarioweb."' AND i_Activo = 1 AND '".$flagPUE."' = 1
		".$whereComplemento."
		";
		$rquery1 = mssql_query($query1);
		// var_dump($query1);
		$conteo = mssql_num_rows($rquery1);
		$arrayQuery1 = mssql_fetch_array($rquery1);
		if($conteo > 0){
			
			$leyendaSubrogado = '<img style="vertical-align:middle" width="16px" 
			src="../mostrador/images/tarjetaSubrogado.png" title="'.$arrayQuery1["clienteSubrogado"].'" />';			
		}
	endif;
	//###########################################################################################################
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
	$listado .= '
	<tr>
	<td><center>'.$idUsuarioweb.'</center></td>	
	<td>
	<a class="linkGrande" href="detallepaciente.php?idusuarioweb='.$idUsuarioweb.'">
	<strong>'.$nombrePaciente.'</strong>
	</a>
	'.$stDocumento.' '.$leyendaEmpleado.' '.$leyendaSubrogado.'
	</td>
	<td>'.$stDireccion.'</td>
	<td><center>'.$dtFechaRegistro.'</center></td>
	</tr>';
}

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
        <tr>                                
            <th>ID Paciente</th>            
            <th>Nombre Paciente</th>
            <th>Direcci&oacute;n</th> 
            <th>Fecha Registro</th>                                                                     
        </tr>                           
    </thead>
    <tbody> 
    <?=$listado?>
    </tbody>
    <tfoot>
		<tr>
            <th><input class="search_init" type="text" name="1" placeholder="ID"></input></th>            
            <th><input class="search_init" type="text" name="2" placeholder="Paciente"></input></th>
            <th><input class="search_init" type="text" name="3" placeholder="direccion"></input></th>
            <th><input class="search_init" type="text" name="4" placeholder="fecha"></input></th>
        </tr>
    </tfoot>	
</table>


</body>
</html>