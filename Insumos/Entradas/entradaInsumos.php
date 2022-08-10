<?php
$ruta2index = "../../../../";
require($ruta2index.'dbConexion.php');

session_start();

////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(3,14,"INSUMOS - Realizar Entrada");
///////////////////////////////////////////////////////	

$idSucursal = $_SESSION["id_Sucursal"];

/////////////////////////////////////// Obtiene Sucursales ////////////
include("../class.CatalogosInsumos.php");
$objCatalogos = new Catalogos();
//////////////////////////////////////////////////////////////////////
$selectSucursales = $objCatalogos->obtieneSucursalML($idSucursal);
//$selectAreasInsumo = $objCatalogos->obtieneAreasInsumoSucursal($idSucursal);
$selectAreasInsumo = $objCatalogos->obtieneAreasInsumoTipoUserSucursal($idSucursal,$_SESSION["id_TipoUsuario"]);



/////////////////////////////// WHERE INSUMOS
session_start();
$whereAreaInsumo = "";
switch($_SESSION["id_TipoUsuario"]){

	//Dental
	case 10:
		$whereAreaInsumo = " AND id_AreaInsumos IN ('4')";
		break;
		
	//Medico
	case 3:
		$whereAreaInsumo = " AND id_AreaInsumos IN ('2','6','5')";
		break;
		
	//Farmacia
	case 15:
		$whereAreaInsumo = " AND id_AreaInsumos IN ('5')";
		break;
		
	//Optica
	case 14:
		$whereAreaInsumo = " AND id_AreaInsumos IN ('3')";
		break;

	default:
		$whereAreaInsumo = "";
		break;
		
}
////////////////////////////////////////////////////////

///////Obtiene Salidas de Cedis
$query0 = "SELECT id_SalidaDirecta FROM tbl_CEDCabeceraSalidaDirecta 
WHERE id_Status = '2' AND id_Motivo = '6' AND i_RecibidoSucursal = '0' 
AND id_AreaInsumos > '0' ".$whereAreaInsumo." AND id_Sucursal = '".$idSucursal."' ORDER BY id_SalidaDirecta";
$rquery0 = mssql_query($query0);
$selectFolioSalidaCedis = '';
while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
	$selectFolioSalidaCedis .= "<option value='".$arrayQuery0['id_SalidaDirecta']."' ".$selected.">".$arrayQuery0['id_SalidaDirecta']."</option>";
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery.ui.datepicker-es.js"></script> 
<link href="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
<!--    DATATABLES  -->
<?php include($ruta2index.'utils/datatables2018/headDatatable.php'); ?>

<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/styleTrasnparentBody.css" type="text/css">
<link href="<?=$ruta2index?>bionline/securitylayer/styles/style_botones.css" rel="stylesheet" type="text/css">

<script src="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.js"></script>
<link rel="stylesheet" href="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.css">
<script type="text/javascript">
var asInitVals = new Array();
function crearDataTable(){
    var fecha1 = $("#fecha1").val();
    var fecha2 = $("#fecha2").val();
    var sucursal = $("#idSucursal option:selected").text();
    var areaInsumo = $("#idAreaInsumo option:selected").text();
    var titulo = "Entrada_Insumos_Sucursal_"+sucursal+"_"+areaInsumo+"_"+fecha1+"_"+fecha2;
    var oTable=$('#example').dataTable( {
        "aaSorting": [], 
        "bProcessing": true,
        dom: 'lBfrtip',
        responsive: true,
        "oLanguage": { "sUrl": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"	},
        "buttons": [ 	
            {	
                className: 'green', 
                extend: 'print', 
                text: '<i class="fa fa-file-text-o"></i> IMPRIMIR',
                title:titulo,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {	
                className: 'green', 
                extend: 'copyHtml5',	
                text: '<i class="fa fa-file-text-o"></i> COPIAR',
                title:titulo,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {	
                className: 'green', 
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> EXCEL',
                title:titulo,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {   
                className: 'green',
                extend: 'pdfHtml5', 
                orientation: 'landscape', 
                pageSize: 'LEGAL', 
                text: '<i class="fa fa-file-pdf-o"></i> PDF',	
                title:titulo,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {   
                className: 'green',
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i> CSV',	
                title:titulo,
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            }
        ]
    } );
    $("tfoot input").keyup( function () {        
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );
    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "";
            this.value = "";
        }
    } );
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );
    $('#example tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );	
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
function generaReporte(){
	
	var fecha1 = $("#fecha1").val();
	var fecha2 = $("#fecha2").val();
	var idSucursal = $("#idSucursal").val();
	var folio = $("#folio").val();
	var idAreaInsumo = $("#idAreaInsumo").val();
	
	
	$.ajax({
		url : "ajaxBusquedaEntradaInsumos.php",
		dataType : "html",
		type : "GET",
		data : {
			'fecha1': fecha1,
			'fecha2': fecha2,
			'idSucursal': idSucursal,
			'folio': folio,
			'idAreaInsumo': idAreaInsumo
		},
		beforeSend: function(){
			$("#contenedorDatatable").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');
		},
		success : function(data) {  															
			$("#contenedorDatatable").html(data);
			crearDataTable();			
		},
		error: function (error) {
           $("#contenedorDatatable").html('Ocurrio un error');
        }
	});
}
//NUEVA ENTRADA POR INSUMO
function crearEntradaInsumos() {
	
	var idSalidaCedis = $("#idSalidaCedis").val();
	var stAreaInsumo = $( "#idAreaInsumosC option:selected" ).text();

	var mensaje = '';
	if(idSalidaCedis == 0){
		mensaje = '&iquest;Crear nueva entrada de Insumo '+stAreaInsumo+' sin Folio de Salida Cedis?';
	}else{
		mensaje = '&iquest;Crear nueva entrada de Insumo relacionada al folio de Salida de Cedis: '+idSalidaCedis+'?';	
	}
	

	jConfirm(mensaje, 'Confirmación', 3, function(r) {
		if(r){
			generarEntradaInsumo(idSalidaCedis);
		}
	});

}

function generarEntradaInsumo(idSalidaCedis){

	var idAreaInsumosC = $("#idAreaInsumosC").val();

	$.ajax({
			url : "ajaxAbreEntradaInsumo.php",
			dataType : "json",
			data:{
				'idSalidaCedis' : idSalidaCedis, 
				'idAreaInsumosC' : idAreaInsumosC		
			},
			type : "POST",
			beforeSend: function(){
				deshabilitaBotones();
			},
			success : function(data) {  
				if(data.error == 0){															
					location.href = "entradaInsumosPaso1.php?idCabeceraEntrada="+data.idCabeceraEntrada;
				}else{
					habilitaBotones();
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
			   jAlert("Status: " + textStatus + " - Error: " + errorThrown, 'Cuadro de Dialogo', 2, function(){habilitaBotones();});
			}
		});
		
}

function deshabilitaBotones(){
	$("#btnNuevaDevolucion, #btnCrear").attr("disabled","disabled").hide();
}

function habilitaBotones(){
	$("#btnNuevaDevolucion, #btnCrear").removeAttr("disabled").show(500);
}



/////////////////////////////// Configura el Calendario
function calendario(elemento){	
    elemento.datepicker({
		dateFormat: 'yy-mm-dd', 
		changeMonth: true, 
		changeYear: true, 
		yearRange: '-100:+50',
		showButtonPanel: true,
		maxDate: '0'
		}).datepicker("setDate", "0");
};

/////////////////////////////// Configura el Calendario
function calendarioMesAnterior(elemento){	
    elemento.datepicker({
		dateFormat: 'yy-mm-dd', 
		changeMonth: true, 
		changeYear: true, 
		yearRange: '-100:+50',
		showButtonPanel: true,
		maxDate: '0'
		}).datepicker("setDate", "-1 month");



};


function visualizaAreaInsumo(){
	
	var conteo = $("#conteoAreas").val();
	var idSalidaCedis = $("#idSalidaCedis").val();	
	if(idSalidaCedis == 0 && conteo > 0){ 
		$("#areaInsumosC").show();
		$("#btnCrear").show();
		 
	}
	else if(idSalidaCedis == 0 && conteo == 0){
		$("#areaInsumosC").hide();
		$("#btnCrear").hide();	
	}
	else{
		$("#areaInsumosC").hide();
		$("#btnCrear").show();
	}
	
	
}

$(document).ready(function() {// Handler for .ready() called.

	//Pinta Calendarios
	calendarioMesAnterior($('#fecha1'));
	calendario($('#fecha2'));
	generaReporte();
	
	/////////////////////////////// Valida Ninguna Area de Insumo
	var conteo = $("#conteoAreas").val();
	var idSalidaCedis = $("#idSalidaCedis").val();
	if(idSalidaCedis == 0 && conteo == 0){
		$("#areaInsumosC").hide();
		$("#btnCrear").hide();
	}
	
	$("#idSalidaCedis").on( 'change', function(){
		visualizaAreaInsumo()	
	});

});	
	
	
</script>

<style type="text/css">

.topdiv{
	background-color:transparent;
	min-height: 100px;
}

#espaciadoTitulo{
	min-height:100px;
	height:100px;
}

a.dt-button.red {
	color: red;
}

a.dt-button.orange {
	color: orange;
}

a.dt-button.green {
	color: green;
}

body{ 
	color: #333333; 
	font-family: Verdana, Arial, Helvetica, sans-serif; 
	font-size: 7.5pt; 
	/*background:url(content_bg.png);*/
}      

</style> 
</head>

<body>

<div class="topdiv"></div>

<div id="contenido">

<table>
<tr>
    <td>
    <a href="javascript:history.back();" title="Volver">
    <img src="<?=$ruta2index?>system/images/icosapps/Go-Back-48.png" width="48" height="48">
    </a>
    </td>
    <td><img src="<?=$ruta2index?>bionline/securitylayer/images/statistics.gif" width="48" height="48"></td>
    <td><strong>ENTRADA DE INSUMOS A SUCURSAL</strong></td>
    
</tr>
</table>

<input type="hidden" name="conteoAreas" id="conteoAreas" value="<?=$objCatalogos->conteoAreas?>">

<table>
<tr><td style="height:30px">Folio: </td><td><input type="text" name="folio" id="folio" placeholder="Folio Salida de Cedis"></td></tr>


<tr height="30px">
<td>Sucursal: </td>
<td>
<select id="idSucursal" name="idSucursal">
<?=$selectSucursales?>
</select>
</td>
</tr>

<tr height="30px">
<td>Area de Insumo: </td>
<td>
<select id="idAreaInsumo" name="idAreaInsumo">
<?=$selectAreasInsumo?>
</select>
</td>
</tr>

<tr><td>Fecha Inicial: </td><td><input type="text" name="fecha1" id="fecha1" readonly></td></tr>
<tr><td>Fecha Final: </td><td><input type="text" name="fecha2" id="fecha2" readonly></td></tr>

<tr>
<td colspan="100%">
<br>
<input type="button" onClick="generaReporte();" class="botonAzul" name="btnBuscar" id="btnBuscar" value="Enviar">
</td>
</tr>

</table>


<div id="contenedorDatatable"></div>

<br><br><br><br>
<div style="text-align:left; clear:both">
<table>
<tr>
<td>
    <select id="idSalidaCedis" name="idSalidaCedis">
    <option value="0">-- Seleccione Folio de Salida de Cedis --</option>
    <?=$selectFolioSalidaCedis?>
    </select>	
</td>

<td>
<div id="areaInsumosC">
<select id="idAreaInsumosC" name="idAreaInsumosC">
<?=$selectAreasInsumo?>
</select>
</div>
</td>

<td>
	<input type="button" onClick="crearEntradaInsumos();" class="botonAnaranjado" name="btnCrear" id="btnCrear" value="Nueva Entrada de Insumos">
</td>
</tr>
</table>
</div>


</div>

</body>
</html>
