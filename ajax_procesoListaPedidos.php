<?
/**
@ Autor: Juan Irvin Carmona Colin
@ Fecha: 12/05/2015
@ Lista de sucursales 
**/
require("../../db.php");
include("../Clases/Class.Pedido.php");
session_start();
//echo "<pre>"; var_dump($_POST); echo "<pre>";
//echo "<pre>"; var_dump($_SESSION); echo "<pre>";

if(isset($_POST['bandera'])){
	$pedido = new Pedido();
	switch($_POST['bandera']){
		case '1':	
			echo $pedido->ReportePedidosProveedor($_POST);			
		break;
		
		case '2':			
			echo $pedido->Recibir($_POST);	
		break;
		
		case '3':		
			echo $pedido->InsertaTipoTrabajo($_POST);			
		break;
		
		case '4':
			echo $pedido->InsertaLaboratorio($_POST);
		break;
		
		case '5':	
			echo $pedido->RecibeDelLaboratorio($_POST);
		break;
		
		case '6':
			echo $pedido->AltaCostoMica($_POST);
		break;
		
		case '7':	
			echo $pedido->EnviaSucursal($_POST);
		break;
		case 'scanner':
			echo $pedido->GetScanner($_POST);	
		break;
		
	}
}
?>