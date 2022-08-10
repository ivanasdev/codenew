<?php
$ruta2index = "../../../../";
require($ruta2index.'dbConexion.php');

session_start();

$idSucursal = $_SESSION["id_Sucursal"];

/////////////////////////////////////// Obtiene Sucursales ////////////
include($ruta2index."bionline/securitylayer/reportes/class.Catalogos.php");
$objCatalogos = new Catalogos();
//////////////////////////////////////////////////////////////////////
$selectSucursales = $objCatalogos->obtieneSucursalML($idSucursal);
$selectAreasInsumo = $objCatalogos->obtieneAreasInsumoSucursal($idSucursal);

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

<script type="text/javascript" language="javascript" src="<?=$ruta2index?>utils/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="<?=$ruta2index?>utils/datatables/media/js/dataTables.tableTools.min.js"></script>  

<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/styleTrasnparentBody.css" type="text/css">
<link href="<?=$ruta2index?>bionline/securitylayer/styles/style_botones.css" rel="stylesheet" type="text/css">

<script src="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.js"></script>
<link rel="stylesheet" href="<?=$ruta2index?>utils/jqueryAlerts11/jquery.alerts.css">



<script type="text/javascript">
var asInitVals = new Array();


function crearDataTable(){
	
	var oTable=$('#example').dataTable( {
		"sDom": 'TC<"clear">lfrtip',
		"aaSorting": [],
		"bProcessing": true,
		"oLanguage": { "sUrl": "//cdn.datatables.net/plug-ins/1.10.9/i18n/Spanish.json"	},
		tableTools: {
            "sSwfPath": "<?=$ruta2index?>utils/datatables/media/swf/copy_csv_xls_pdf.swf"
        }
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
		url : "ajaxBusquedaSalidaInsumos.php",
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


//NUEVA SALIDA POR INSUMO
function crearSalidaInsumos() {
	
	var stAreaInsumo = $( "#idAreaInsumosC option:selected" ).text();	
	mensaje = '&iquest;Crear salida de Insumos del área: '+stAreaInsumo+'?';	

	jConfirm(mensaje, 'CREAR SALIDA DE INSUMOS', 3, function(r) {
		if(r){
			generarSalidaInsumo();
		}
	});

}

function generarSalidaInsumo(){

	var idAreaInsumosC = $("#idAreaInsumosC").val();

	$.ajax({
			url : "ajaxAbreSalidaInsumo.php",
			dataType : "json",
			data:{
				'idAreaInsumosC' : idAreaInsumosC		
			},
			type : "POST",
			beforeSend: function(){
				deshabilitaBotones();
			},
			success : function(data) {  
				if(data.error == 0){															
					location.href = "salidaInsumosPaso1.php?idCabeceraSalida="+data.idCabeceraSalida;
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
	
	var idSalidaCedis = $("#idSalidaCedis").val();	
	if(idSalidaCedis == 0){ 
		$("#areaInsumosC").show(); 
	}else{
		$("#areaInsumosC").hide();
	}
	
	
}

$(document).ready(function() {// Handler for .ready() called.

	//Pinta Calendarios
	calendarioMesAnterior($('#fecha1'));
	calendario($('#fecha2'));
	generaReporte();
	
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

<style type="text/css" title="currentStyle">
	@import "<?=$ruta2index?>utils/datatables/media/css/demo_page.css";
	@import "<?=$ruta2index?>utils/datatables/media/css/demo_table.css";
	@import "<?=$ruta2index?>utils/datatables/media/css/dataTables.tableTools.min.css";
</style>
		


</head>

<body>

<div class="topdiv"></div>

<div id="contenido">

<table>
<tr>
    <td>
    <a href="../../MenuPedidos.php" title="Volver">
    <img src="<?=$ruta2index?>system/images/icosapps/Go-Back-48.png" width="48" height="48">
    </a>
    </td>
    <td><img src="<?=$ruta2index?>bionline/securitylayer/images/statistics.gif" width="48" height="48"></td>
    <td><strong>SALIDA DE INSUMOS</strong></td>
    
</tr>
</table>

<input type="hidden" name="idSucursal" id="idSucursal" value="10">

<table>

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
<option value="0">-- Todas --</option>
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
<div id="areaInsumosC">
<select id="idAreaInsumosC" name="idAreaInsumosC">
<?=$selectAreasInsumo?>
</select>
</div>
</td>

<td>
	<input type="button" onClick="crearSalidaInsumos();" class="botonAnaranjado" name="btnCrear" id="btnCrear" value="Nueva Salida de Insumos">
</td>
</tr>
</table>
</div>


</div>

</body>
</html>
