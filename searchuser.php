<?php
$ruta2index = "../../";
require($ruta2index.'dbConexion.php');

////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,26,"PACIENTES - Buscador de Pacientes Venta Público");
///////////////////////////////////////////////////////	

if(isset($_POST["searchtoken"]) && trim($_POST["searchtoken"]) != ''):

	$token = utf8_encode(trim($_POST["searchtoken"]));

	if( !is_numeric($token) ){
		$nombrePaciente = $token;
		$idUsuarioWeb = '';
	}else{
		$nombrePaciente = '';
		$idUsuarioWeb = $token;
	}

else:
	
	$nombrePaciente = 'NO DEFINIDO';
	$idUsuarioWeb = '';

endif;

if ($_POST['chk_optica'] == 1)
	$opticaCheck = 1;
else
	$opticaCheck = 0;


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

<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/styleTrasnparentBody.css" type="text/css">
<link href="<?=$ruta2index?>bionline/securitylayer/styles/style_botones.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$ruta2index?>utils/tinybox/tinybox.js"></script>
<link href="<?=$ruta2index?>utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css">  

<script type="text/javascript" src="<?=$ruta2index?>utils/jqueryPlugins/jquery.numeric.js"></script>


<script type="text/javascript">


/////////// TinyBox ///////////
function abrirPop(ancho,alto,php){
	TINY.box.show({iframe:php,boxid:'frameless',width:ancho,height:alto,fixed:false,maskid:'graymask',maskopacity:40,closejs:function(){
		
		var refreshPagina = $('#refreshPagina').val();
		if(refreshPagina == 1){
			//location.reload(true);
			//buscaPx();
			}
		
	}});	
}

/////////// TinyBox ///////////
function abrirPopTurno(ancho,alto,php){
	TINY.box.show({iframe:php,boxid:'frameless',width:ancho,height:alto,fixed:false,maskid:'graymask',maskopacity:40,closejs:function(){
		
		//location.reload(true);
		
	}});	
}



var asInitVals = new Array();


function crearDataTable(){
	
	var oTable=$('#example').dataTable( {
		"sDom": 'TC<"clear">lfrtip',
		"aaSorting": [],
		"bProcessing": true,
		"oLanguage": { "sUrl": "//cdn.datatables.net/plug-ins/1.10.9/i18n/Spanish.json"	}		
	});
	
	
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
function buscaPx(){
	
	datastring = $("#formBusqueda").serialize();
	
	$.ajax({
		url : "ajaxBusquedaPxOptica.php",
		dataType : "html",
		type : "POST",
		data: datastring,
		beforeSend: function(){
			$("#contenedorDatatable").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');
		},
		success : function(data) {  															
			$("#contenedorDatatable").html(data);
			crearDataTable();			
		}
	});
	
	
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

$(document).ready(function() {// Handler for .ready() called.

	buscaPx();
	$("#idUsuarioWeb").numeric(false);

});	
	
	
</script>

<style type="text/css">

.topDiv{
	min-height:100px;
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
	background:url(../mostrador/images/content_bg.png);
}   

.tituloSeccion{
	font-family:"Arial Black", Gadget, sans-serif;
	font-size:20px;
	color:#184882;
}

.linkGrande{
	font-size:11px;
}

</style> 

<style type="text/css" title="currentStyle">
	@import "<?=$ruta2index?>utils/datatables/media/css/demo_page.css";
	@import "<?=$ruta2index?>utils/datatables/media/css/demo_table.css";
</style>
		


</head>

<body>

<div class="topDiv"></div>

<div id="contenido">

<input type="hidden" name="refreshPagina" id="refreshPagina" value="0">

<table>
<tr>
    <td><img src="<?=$ruta2index?>bionline/securitylayer/images/statistics.gif" width="48" height="48"></td>
    <td><span class="tituloSeccion">BUSQUEDA PACIENTES</span></td>  
</tr>
</table>
<form id="formBusqueda">
<table>

<tr>
<td height="25px">Nombre Paciente: </td>
<td>
<input type="text" name="nombrePaciente" id="nombrePaciente" size="35" value="<?=$nombrePaciente?>">
<input type="hidden" name="optica" id="optica" value="<?=$opticaCheck?>">
</td>
<td rowspan="3" style="padding-left:50px;" valign="middle"><a href="../RegistroPaciente/formCita.php?moduloRegistro=optica"> 
<img src="../images/icOperatorC3on.gif"  border="0"/> 
Registrar Paciente Nuevo</a> </td>
</tr>
<tr><td>ID Paciente: </td><td><input type="text" name="idUsuarioWeb" id="idUsuarioWeb" size="10" value="<?=$idUsuarioWeb?>"></td></tr>

<tr>
<td colspan="100%">
<input type="button" onClick="buscaPx();" class="botonAzul" name="btnBuscaPx" id="btnBuscaPx" value="Buscar">
</td>
</tr>

</table>
</form>

<br>

<div id="contenedorDatatable"></div>


<br><br><br>

</div>

</body>
</html>
