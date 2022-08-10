<?php
date_default_timezone_set('America/Mexico_City');

class PacienteINVI{
	
	
function obtenSaldoMonederoINVI($idUsuarioWeb){

	$query0 = "SELECT i_Saldo FROM tbl_MonederoUsuarioWeb WHERE id_Monedero = '1' AND id_UsuarioWeb = '".$idUsuarioWeb."'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$saldo = $this->truncateFloat($arrayQuery0["i_Saldo"],2);	
	return $saldo;
	
}

function esPacienteINVI($idUsuarioWeb){
	
	$query0 = "SELECT COUNT(*) as conteo FROM cat_INVIEmpleados 
	WHERE id_UsuarioWeb != '0' AND id_UsuarioWeb = '".$idUsuarioWeb."' AND i_Activo = '1'";
	$rquery0 = mssql_query($query0);
	$arrayQuery0 = mssql_fetch_array($rquery0);
	$conteo = $arrayQuery0["conteo"];
	
	if($conteo == 0){
		return false;	
	}else{
		return true;	
	}
	
	
}

function truncateFloat($number, $digitos)
{
    $raiz = 10;
    $multiplicador = pow ($raiz,$digitos);
    $resultado = ((int)($number * $multiplicador)) / $multiplicador;
    return $resultado;
 
}


			
	function mostrar($cadena){
		return utf8_encode(trim($cadena));
	}
	
	function poner($cadena){
		return utf8_decode(trim($cadena));
	}
	
	function mostrarFecha($cadena){
		
		if($cadena == '1900-01-01' || $cadena == NULL || trim($cadena) == "" )
			return "";
		else
			return utf8_decode( date('Y/m/d',strtotime($cadena)) );
			
	}


}//Fin Class PacienteINVI
?>