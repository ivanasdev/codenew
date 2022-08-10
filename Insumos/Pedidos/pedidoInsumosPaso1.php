<?php   
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$ruta2index = "../../../../";
include($ruta2index."dbConexion.php");

session_start();
if( !isset($_GET["idPedidoInsumo"], $_SESSION["id_Operador"]) ){
	echo "Parametros incorrectos";
	exit;	
}

$idOperador = $_SESSION["id_Operador"];
$idPedidoInsumo = $_GET["idPedidoInsumo"];
$idSucursal = $_GET["idSucursal"];

$permiteAccionModulo = 1;
$mensajeError = "";
include("class.PedidoInsumo.php");
$objPedidoInsumo = new PedidoInsumo();

//ERRORES########################################################################################################################

//Valida que exista Session Operador 
if( !isset($_SESSION["id_Operador"]) ):
	
	$permiteAccionModulo = 0;
	$mensajeError = "Error! No existe sesión de usuario";

//No se puede tener realizar acciones si hay inventario ciclico abierto
elseif( $objPedidoInsumo->existeInventarioCiclicoAbierto() ):
	
	$permiteAccionModulo = 0;
	$mensajeError = "AVISO!! Por el momento no es posible realizar una entrada debido a un inventario ciclico. Por favor intentelo m\u00e1s tarde.";
	
else:

	$objPedidoInsumo->setInfoPedidoInsumos($idPedidoInsumo);
 
endif;

if($objPedidoInsumo->idSucursal != $_SESSION["id_Sucursal"]){
	echo "EL FOLIO DEL PEDIDO DE INSUMO NO CORRESPONDE A LA SUCURSAL!!!";
	exit;	
}

if($objPedidoInsumo->idStatusPedido != 1){
	echo "EL PEDIDO DE INSUMO YA FUE CERRADO, FAVOR DE CREAR UN FOLIO NUEVO!!!";
	exit;	
}

if( !$objPedidoInsumo->idAreaInsumo > 0){
	echo "No se ha asignado un &aacute;rea de insumo. Debe consultarlo con el administrador del sistema para que asigne un &aacute;rea!!!";
	exit;	
}


mssql_close();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>

<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$ruta2index?>utils/jqueryPlugins/jquery.numeric.js"></script>

<script src="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.js"></script>
<link rel="stylesheet" href="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.css">

<script src="<?=$ruta2index?>utils/FixedHeaderTableMaster/jquery.fixedheadertable.js"></script>
<link href="<?=$ruta2index?>utils/FixedHeaderTableMaster/css/defaultTheme.css" rel="stylesheet" media="screen" />
<link href="<?=$ruta2index?>utils/FixedHeaderTableMaster/demo/css/myTheme.css" rel="stylesheet" media="screen" />

<script src="<?=$ruta2index?>utils/mascaraInputText/jquery.maskedinput.min.js"></script>

<script type="text/javascript" src="<?=$ruta2index?>utils/monthpicker/jquery.mtz.monthpicker.js"></script>

<style type="text/css">
	.height200 {
		height: 200px;
		overflow-x: auto;
		overflow-y: auto;
	}
	
	.height250 {
		height: 250px;
		overflow-x: auto;
		overflow-y: auto;
	}
	
	.height350 {
		height: 350px;
		overflow-x: auto;
		overflow-y: auto;
	}
	
	body {
		font-family: "Helvetica Neue", arial, sans-serif;
		background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAGBAMAAAAS4vJ7AAAAG1BMVEXS4erj7PHf6O3g6O729vby8vLx8fHh6u%2Fz8%2FOBUUhCAAAAIElEQVQIHWNgFFJkMAlJYShzaWMQS3Nh0EhzY1BxSQMAM84Ew1msm%2BsAAAAASUVORK5CYII%3D");
	}
	
</style>

<link href="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/style.css" type="text/css">
<link type="text/css" href="<?=$ruta2index?>system/farmacia/css/botones.css" rel="stylesheet" />
<link type="text/css" href="estiloGeneral.css" rel="stylesheet" />


<script type="text/javascript">


//Lista los productos traspasados
function listaProductosPedidoInsumo(){
	
	var idPedidoInsumo = $('#idPedidoInsumo').val();
	
	$.ajax({
		url : "ajaxListaProductoPedidoInsumos.php",
		type : "POST",
		dataType : "json",
		data : {
			'idPedidoInsumo': idPedidoInsumo
		},
		beforeSend: function(){
			deshabilitaBotonesForm();
			$('#productosAgregados').html("<center><img src='../../../images/loading02.gif' />cargando..</center>");
		},
		success : function(data) {  					
		
			if(!data){							
				jAlert("Error", 'Cuadro de Dialogo', 4, function(){habilitaBotonesForm();});									
			}
			else{
				$("#productosAgregados").html(data.tabla);
				$("#noPiezas").html(data.productosPiezas);
				$('#myTable02').fixedHeaderTable({ altClass: 'odd' });
				habilitaBotonesForm();					
			}			
		
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			jAlert('Status: '+textStatus+' - Error: '+errorThrown, 'Cuadro de Dialogo', 2, function(){habilitaBotonesForm();});
		}           
	});	
	
}



//Obtiene busqueda de producto en CEDIS
function searchToken(){
	
	var token = $('#token').val().trim();
	var idAreaInsumo = $('#idAreaInsumo').val().trim();
	var id_Sucursal='<?= $idSucursal ?>'
	
	if(token.length <= 2){ return false; }
	
	$.ajax({
		url : "ajaxBuscaProductoCatalogo.php",
		type : "POST",
		dataType : "html",
		data : {
			'token': token,
			'idAreaInsumo': idAreaInsumo,
			'id_Sucursal':id_Sucursal

		},
		beforeSend: function(){
			deshabilitaBotonesForm();
			$('#resultadoBusqueda').html("<center><img src='../../../images/loading02.gif' />cargando..</center>");
		},
		success : function(data) {  					
		
			if(!data){							
				jAlert("Error", 'Cuadro de Dialogo', 4, function(){habilitaBotonesForm();});									
			}
			else{
				$("#resultadoBusqueda").html(data);									
				$('#myTable01').fixedHeaderTable({ altClass: 'odd' });	
				habilitaBotonesForm();
				aplicarMascaraUbicacion($('.productoUbicacion'));
				calendarioCaducidad($(".fcaducidad"));
				$('#token').focus().select();			
			}
			
		
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			jAlert('Status: '+textStatus+' - Error: '+errorThrown, 'Cuadro de Dialogo', 2, function(){habilitaBotonesForm();});
			//alert("Status: " + textStatus); alert("Error: " + errorThrown); 
		}           
	});	
	
}


//Obtiene busqueda de producto en CEDIS
function agregaProducto(idItem){
	
	var idPedidoInsumo = $('#idPedidoInsumo').val();
	
	var error = 0;
	var msjError = '';
	
	var cantidad = $("#cantidad_"+idItem).val();
	var idInsumo = $("#idInsumo_"+idItem).val();
	var observaciones = $("#observaciones_"+idItem).val().trim();



	//VALIDA CAMPOS
	//Valida Cantidad
	if(	parseInt(cantidad) <= 0 || cantidad == ''){
		msjError += '- Cantidad nula o invalida<br>';
		error++;
	}	


	if(error > 0){
		jAlert(msjError, 'Mensaje de Error', 2);
		return false;	
	}	
	
	var datos = {
		'cantidad' : cantidad,
		'idInsumo' : idInsumo,
		'idPedidoInsumo' : idPedidoInsumo,
		'observaciones' : observaciones
	};
	
	$.ajax({
		url : "ajaxAgregaProductoPedidoInsumos.php",
		type : "POST",
		dataType : "json",
		data : datos,
		beforeSend: function(){
			deshabilitaBotonesForm();
			$('#resultadoBusqueda').html("<center><img src='../../../images/loading02.gif' />cargando..</center>");
			$('#productosAgregados').html("<center><img src='../../../images/loading02.gif' />cargando..</center>");
		},
		success : function(data) {  					
		
			if(data.error == 0){	
				
				listaProductosPedidoInsumo();
				searchToken();
				habilitaBotonesForm();
																										
			}
			else{
				jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){
					listaProductosPedidoInsumo();
					searchToken();
					habilitaBotonesForm();
				});												
			}			
		
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			jAlert('Status: '+textStatus+' - Error: '+errorThrown, 'Cuadro de Dialogo', 2, function(){habilitaBotonesForm();});
		}           
	});	
	
}


function eliminaProductoPedidoInsumo(idPedidoInsumo, idPedidoDetalle, stNombre){
	
	
	jConfirm('&iquest;Quitar el producto: '+stNombre+'?', 'Confirmation Dialog', 3, function(r) {
		if(r){
			
			
			$.ajax({
			url : "ajaxDeleteProductoPedidoInsumo.php",
			type : "POST",
			dataType : "json",
			data : {
				'idPedidoInsumo' : idPedidoInsumo,
				'idPedidoDetalle' : idPedidoDetalle
				},
			beforeSend: function(){
				deshabilitaBotonesForm();				
			},
			success : function(data) {  					
					
				if(data.error == 0){
					$("#token").val(stNombre);
					searchToken();
					listaProductosPedidoInsumo();
				}
				else{					
					jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){habilitaBotonesForm();});						
				}											
			
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				jAlert("Status: " + textStatus + " - Error: " + errorThrown, 'Cuadro de Dialogo', 2, function(){habilitaBotonesForm();});
			}           
		});		
			
		}
		else{
			return false;	
		}
	});
	
	
 

	 

}


//Cerrar Pedido
function cerrarPedidoInsumo(){
	
	var idPedidoInsumo = $("#idPedidoInsumo").val();
	var observaciones = $("#observaciones").val();
	
	jConfirm('&iquest;Desea cerrar el PEDIDO de Insumo con Folio: '+idPedidoInsumo+'?', 'Confirmation Dialog', 3, function(r) {
		if(r){
			
			
			$.ajax({
			url : "ajaxCierraPedidoInsumo.php",
			type : "POST",
			dataType : "json",
			data : {
				'idPedidoInsumo' : idPedidoInsumo,
				'observaciones' : observaciones
				},
			beforeSend: function(){
				deshabilitaBotonesForm();				
			},
			success : function(data) {  					
					
				if(data.error == 0){
					jAlert(data.mensaje, 'Cuadro de Dialogo', 1, function(){
						$(".separador, #productosAgregados").hide();
						$("#resultadoBusqueda").html('Se hizo el pedido de insumos correctamente!!! <a href="PDFPedidoInsumoSucursal.php?idPedidoInsumo='+idPedidoInsumo+'">Descargar Reporte Pedido de Insumo PDF</a>');							
					});
				}
				else{					
					jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){
						listaProductosPedidoInsumo();
					});						
				}														
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
			}           
			});	
			
		}
		else{
			return false;	
		}
	}); 

}


/////////////////////////////////////////////////////////////////////////////Aplicar Mascara
function aplicarMascaraUbicacion(elemento){
	$(elemento).mask("99-99-99");
}

/////////////////////////////////////////////////////////////////////////////Aplicar Mascara
function aplicarMascaraUbicacion2(defaultValue,elemento){
	MaskedInput({
	  elm: elemento,
	  format: defaultValue,
	  separator: '-',
	  typeon: '0123456789'
	});	
}


function deshabilitaBotonesForm(){
	$("#btnCerrar").attr("disabled","disabled").hide();
}

function habilitaBotonesForm(){
	$("#btnCerrar").removeAttr("disabled").show(500);
	$(".numeroEntero").numeric(false);
	$(".numeroDecimal").numeric();
}

/////////////////////////////// Configura el Calendario de Caducidad
function calendarioCaducidad(elemento){	
  
  options = {
    pattern: 'yyyy-mm', // Default is 'mm/yyyy' and separator char is not mandatory
    monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
	};
  
  $(elemento).monthpicker(options);
  
};

$(document).ready(function() {
// Handler for .ready() called.

	listaProductosPedidoInsumo();

});

</script>

</head>

<body style="background-color:transparent ">

<?php /*?><div class="topdiv"></div><?php */?>


    <table border="0">
        <tr>
        	<td>
            <a href="JavaScript:history.back();">
            <img src="<?=$ruta2index?>bionline/securitylayer/images/regresar.png" width="30" height="30">
            </a>
            </td>
            <td><img src="<?=$ruta2index?>bionline/securitylayer/images/clients.gif" width="48" height="48"></td>
            <td><strong class="pageTitle">PEDIDO DE INSUMOS</strong></td>
        </tr>
    </table>


<div id="container">

<table width="100%" align="left">
	<tr>
    	<td width="3%"></td>
        <td>

        
        <table width="100%">
            
            <tr>
                <td class="negritas" width="150px">Folio Pedido de Insumos:</td>
                <td>
                	<?=$idPedidoInsumo?>
                    <input type="hidden" id="idPedidoInsumo" name="idPedidoInsumo" value="<?=$idPedidoInsumo?>">
                    <input type="hidden" id="idAreaInsumo" name="idAreaInsumo" value="<?=$objPedidoInsumo->idAreaInsumo?>">
                    <input type="hidden" id="sucursal" name="sucursal" value="<?=$_SESSION["id_Sucursal"]?>">
                </td>
                <td></td>
            </tr>
            
            <tr>
                <td class="negritas" width="150px">Area de Insumo:</td>
                <td>
                	<?=$objPedidoInsumo->stAreaInsumo?>
                </td>
                <td></td>
            </tr>                        
            
            <tr>
                <td class="negritas">Observaciones:</td>
                <td colspan="2">
                <textarea id="observaciones" name="observaciones" rows="2" cols="60"></textarea>
                </td>
            </tr>
            
            <tr>
                <td align="right" colspan="3">
                    <input id="btnCerrar" class="botonAzul" type="button" onClick="JavaScript:cerrarPedidoInsumo();" value="Cerrar Pedido de Insumos" />
                </td>
            </tr>
        </table>
        <!--FIN CABECERA SALIDA-->
        
        <br>

        <!--BUSQUEDA-->
        <div class="separador" align="center">
        <table width="100%" style="border-collapse:collapse;">
            <tr height="30px">
                <td class="negritas" width="30px" style="color:#FFF; font-size:14px;">
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Busqueda:
                </td>
                <td align="left" colspan="7">
                    <input type="text" size="37" placeholder="Ingresa descripci&oacute;n" id="token" name="token" onChange="JavaScript:searchToken();" autocomplete='off' title="Ingresa el c&oacute;digo de barras o la descripci&oacute;n del producto y presiona la tecla ENTER"/>
                </td>
            </tr>
            	
        </table>
        </div>
        <!--FIN BUSQUEDA-->
        
        <!-- RESULTADO BUSQURDA-->
        <div id="resultadoBusqueda" class="height200">
            resultado busqueda...
        </div>
        <!-- FIN RESULTADO BUSQURDA-->
        
        <!--NO PIEZAS-->
        <div class="separador" align="center">
            <table border="0" width="100%" style="border-collapse:collapse;">
            	<tr height="30px">
                	<td colspan="4" class="negritas" width="30px" style="color:#FFF; font-size:14px;">
                		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Listado del Pedido
                	</td>
                    <td colspan="4" align="right">
                    	<label style="color:#FFF; font-weight:bolder; font-size:14px" id="noPiezas">
                			Productos: 0 / Piezas: 0
            			</label>
                    </td>
                </tr>            	
        	</table>
        </div>
        <!--FIN NO PIEZAS-->
        
        <!--PRODUCTOS AGREGADOS-->
        <div id="productosAgregados" class="height200">
            productos salvados...
        </div>
        <!--FIN PRODUCTOS AGREGADOS-->
        
        <br>

		</td>
      	<td width="3%"></td>
     </tr>
</table>
<br><br><br>
</div>


</body>
</html>
