<?php   
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$ruta2index = "../../../../";
include($ruta2index."dbConexion.php");

session_start();
if( !isset($_GET["idCabeceraSalida"], $_SESSION["id_Operador"], $_SESSION["id_Sucursal"]) ){
	echo "Parametros incorrectos";
	exit;	
}

$idOperador = $_SESSION["id_Operador"];
$idCabeceraSalida = $_GET["idCabeceraSalida"];


$permiteSalidaDirecta = 1;
$mensajeError = "";
include("class.SalidaInsumo.php");
$objSalidaInsumo = new SalidaInsumo();

//ERRORES########################################################################################################################

//Valida que exista Session Operador 
if( !isset($_SESSION["id_Operador"]) ):
	
	$permiteSalidaDirecta = 0;
	$mensajeError = "Error! No existe sesión de usuario";

//No se puede hacer entrada si hay inventario ciclico abierto en cedis
elseif( $objSalidaInsumo->existeInventarioCiclicoAbierto() ):
	
	$permiteSalidaDirecta = 0;
	$mensajeError = "AVISO!! Por el momento no es posible realizar una salida debido a un inventario ciclico. Por favor intentelo m\u00e1s tarde.";
	
else:

	$objSalidaInsumo->setInfoSalidaInsumos($idCabeceraSalida);
 
endif;


if($objSalidaInsumo->idSucursal != $_SESSION["id_Sucursal"]){
	echo "EL FOLIO DE LA SALIDA DE INSUMO NO CORRESPONDE A LA SUCURSAL!!!";
	exit;	
}

//echo $objSalidaInsumo->idStatusDevolucion;
if($objSalidaInsumo->idStatusSalida != 1){
	echo "LA SALIDA POR INSUMO YA FUE CERRADA, FAVOR DE CREAR UN FOLIO NUEVO!!!";
	exit;	
}

if( !$objSalidaInsumo->idAreaInsumo > 0){
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
function listaProductosSalida(){
	
	var idCabeceraSalida = $('#idCabeceraSalida').val();
	
	$.ajax({
		url : "ajaxListaProductoSalidaInsumos.php",
		type : "POST",
		dataType : "json",
		data : {
			'idCabeceraSalida': idCabeceraSalida
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
	var idAreaInsumo = $('#idAreaInsumo').val();
	
	if(token.length <= 3){ return false; }
	
	$.ajax({
		url : "ajaxBuscaProductoCatalogo.php",
		type : "POST",
		dataType : "html",
		data : {
			'token': token,
			'idAreaInsumo': idAreaInsumo
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


//agrega a la salida el producto
function agregaProducto(idItem){
	
	var idCabeceraSalida = $('#idCabeceraSalida').val();
	
	var error = 0;
	var msjError = '';
	
	var fechaCaducidad = $("#fechaCaducidad_"+idItem).val();
	var lote = $("#lote_"+idItem).val().trim();
	var cantidad = $("#cantidad_"+idItem).val();
	var stock = $("#stock_"+idItem).val();
	var idProductoAlmacen = $("#idProductoAlmacen_"+idItem).val();
	var motivo = $("#motivo_"+idItem).val().trim();	
	var idAreaInsumo = $('#idAreaInsumo').val();



	//VALIDA CAMPOS
	//Valida fechaCaducidad
	if(	fechaCaducidad == ''){
		msjError += '- Fecha de Caducidad invalida<br>';
		error++;
	}
	
	//Valida lote
	if(	lote == ''){
		msjError += '- Lote Invalido<br>';
		error++;
	}


	
	//Valida Cantidad
	if(	parseInt(cantidad) <= 0 || cantidad == '' || (parseInt(cantidad) > parseInt(stock)) ){
		msjError += '- Cantidad nula o invalida<br>';
		error++;
	}	


	if(error > 0){
		jAlert(msjError, 'Mensaje de Error', 2);
		return false;	
	}	
	
	var datos = {
		'fechaCaducidad' : fechaCaducidad,
		'lote' : lote,
		'cantidad' : cantidad,
		'idProductoAlmacen' : idProductoAlmacen,
		'idCabeceraSalida' : idCabeceraSalida,
		'motivo' : motivo,
		'idAreaInsumo': idAreaInsumo
	};
	
	$.ajax({
		url : "ajaxAgregaProductoSalidaInsumos.php",
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
				
				listaProductosSalida();
				searchToken();
				habilitaBotonesForm();
																										
			}
			else{
				jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){
					listaProductosSalida();
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


function eliminaProductoSalidaInsumo(idCabeceraSalida, idDetalleSalida, idUPC){
	
	
	jConfirm('&iquest;Quitar el producto:'+idUPC+'?', 'Confirmation Dialog', 3, function(r) {
		if(r){
			
			
			$.ajax({
			url : "ajaxDeleteProductoSalidaInsumo.php",
			type : "POST",
			dataType : "json",
			data : {
				'idCabeceraSalida' : idCabeceraSalida,
				'idDetalleSalida' : idDetalleSalida
				},
			beforeSend: function(){
				deshabilitaBotonesForm();				
			},
			success : function(data) {  					
					
				if(data.error == 0){
					//alert(data.mensaje);
					$("#token").val(idUPC);
					searchToken();
					listaProductosSalida();
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


//Cerrar Salida
function cerrarSalidaInsumo(){
	
	var idCabeceraSalida = $("#idCabeceraSalida").val();
	var observacionesSalidaInsumo = $("#observacionesSalidaInsumo").val();
	var stAreaInsumo = $("#stAreaInsumo").val();
	
	jConfirm('&iquest;Desea cerrar la Salida de Insumo '+stAreaInsumo+' con Folio: '+idCabeceraSalida+'?', 'Confirmation Dialog', 3, function(r) {
		if(r){
			
			
			$.ajax({
			url : "ajaxCierraSalidaInsumo.php",
			type : "POST",
			dataType : "json",
			data : {
				'idCabeceraSalida' : idCabeceraSalida,
				'observacionesSalidaInsumo' : observacionesSalidaInsumo
				},
			beforeSend: function(){
				deshabilitaBotonesForm();				
			},
			success : function(data) {  					
					
				if(data.error == 0){
					jAlert(data.mensaje, 'Cuadro de Dialogo', 1, function(){
						$(".separador, #productosAgregados").hide();
						$("#resultadoBusqueda").html('Se hizo la salida de insumos correctamente!!! <a href="PDFSalidaInsumoSucursal.php?idCabeceraSalida='+idCabeceraSalida+'">Descargar Reporte Salida de Insumo PDF</a>');							
					});
				}
				else{					
					jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function(){
						listaProductosSalida();
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

	listaProductosSalida();

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
            <td><strong class="pageTitle">SALIDA DE INSUMOS</strong></td>
        </tr>
    </table>


<div id="container">

<table width="100%" align="left">
	<tr>
    	<td width="3%"></td>
        <td>

        
        <table width="100%">
            
            <tr>
                <td class="negritas" width="150px">Folio de Salida Insumos:</td>
                <td>
                	<?=$idCabeceraSalida?>
                    <input type="hidden" id="idCabeceraSalida" name="idCabeceraSalida" value="<?=$idCabeceraSalida?>">
                    <input type="hidden" id="idAreaInsumo" name="idAreaInsumo" value="<?=$objSalidaInsumo->idAreaInsumo?>">
                    <input type="hidden" id="idCabeceraSalida" name="idCabeceraSalida" value="<?=$idSucursal?>">
                    <input type="hidden" id="stAreaInsumo" name="stAreaInsumo" value="<?=$objSalidaInsumo->stAreaInsumo?>">
                </td>
                <td></td>
            </tr>
            
            <tr>
                <td class="negritas" width="150px">Area de Insumo:</td>
                <td>
                	<?=$objSalidaInsumo->stAreaInsumo?>
                </td>
                <td></td>
            </tr>                        
            
            <tr>
                <td class="negritas">Observaciones:</td>
                <td colspan="2">
                <textarea id="observacionesSalidaInsumo" name="observacionesSalidaInsumo" rows="2" cols="60"></textarea>
                </td>
            </tr>
            
            <tr>
                <td align="right" colspan="3">
                    <input id="btnCerrar" class="botonAzul" type="button" onClick="JavaScript:cerrarSalidaInsumo();" value="Cerrar Salida de Insumos" />
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
                    <input type="text" size="37" placeholder="Ingresa Codigo de barras &oacute; descripci&oacute;n" id="token" name="token" onChange="JavaScript:searchToken(0);" autocomplete='off' title="Ingresa el c&oacute;digo de barras o la descripci&oacute;n del producto y presiona la tecla ENTER"/>
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
                		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Productos a realizar salida
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
