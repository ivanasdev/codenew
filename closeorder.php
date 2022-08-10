<?php
require("../db.php"); 
session_start();

$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,83,"PEDIDO - Lista Pedidos Optica");
///////////////////////////////////////////////////////	

if (isset($_POST['dt_FechaIni'])) {
	$fechaIni = $_POST['dt_FechaIni'];
	$fechaFin = $_POST['dt_FechaFin'];
} else {
	$fechaIni = '';
	$fechaFin = '';
}

$sucs = $_SESSION["id_Sucursal"];
if (isset($_POST['id_Suc'])){
	$sucursal = $_POST['id_Suc'];
} else {
	$sucursal = $sucs;
}

if ($_SESSION["id_TipoUsuario"] == 16)  { //538 en pruebas
	echo "<meta http-equiv='REFRESH' content='0; url=listapedidosproveedor.php'>";
	exit();
} 

$compAdmin="";
if ($_SESSION['b_Admin'] == 1){
	$compAdmin.='<div id="group">';
	$compAdmin.='<div id="label">Sucursal</div>';
	$compAdmin.='<div id="input">';
	$compAdmin.='<select name="id_Suc" id="id_Suc"> ';
	$compAdmin.='<option selected value="0">Selecciona...</option>';
	$queryselec = "SELECT st_Nombre, id_SucursalClinica 
	FROM cat_SucursalClinica 
	WHERE i_Activo=1 AND id_TipoSucursalOperacion IN (0,1)
		AND id_SucursalClinica IN (SELECT  DISTINCT id_Sucursal FROM tbl_EvVentaOptica ) 
		".$querycomplement. " 
	ORDER BY st_Nombre ";
	$rqueryselec = mssql_query($queryselec);
 	while ($rowsel = mssql_fetch_array($rqueryselec)) {	
	 	if($_SESSION['id_Sucursal'] == $rowsel['id_SucursalClinica']){
	 		$comP="selected";
	 	}else{
	 		$comP="";
	 	} 
 		$compAdmin.='<option '.$comP.' value="'.$rowsel['id_SucursalClinica'].'">'.htmlentities($rowsel['st_Nombre']).'</option>';
  	} 
	$compAdmin.='<option value="all">Todas</option>';
  	$compAdmin.='</select>';
	$compAdmin.='</div>';
	$compAdmin.='</div>';			
} 
?>  
<!DOCTYPE HTML>
<html>
<head>
	<title>Lista de pedidos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="<?=$ruta2index?>utils/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/jquery.ui.datepicker-es.js"></script> 
	<link href="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<?php include($ruta2index.'utils/datatables2018/headDatatable.php'); ?>
	
	<link href="../../utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../utils/tinybox/tinybox.js"></script>
	<script type="text/javascript">
		/////////// TinyBox ///////////
		function abrir_pop3(ancho,alto,php){
			TINY.box.show({
				iframe:php,
				boxid:'frameless',
				width:ancho,
				height:alto,
				fixed:false,
				maskid:'graymask',
				maskopacity:40,
				closejs:function() {
					var refreshPagina = $('#refreshPagina').val();
					if(refreshPagina == 1){location.reload(true); }
				}
			});	
		}
		/////////////////////////////// Configura el Calendario
		function calendario(elemento){	
		    elemento.datepicker({
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true, 
				//minDate: 0,
				showButtonPanel: true
				}).datepicker("setDate", "0");
		};
		$(document).ready(function(e) {
			calendario($('#dt_FechaIni'));
			calendario($('#dt_FechaFin'));
			filtrarDatos();
		});
		function filtrarDatos(){
			var tipoBusqueda = $('#tipoBusqueda').val();
			var idfolio = $('#idfolio').val();
			var dt_FechaIni = $('#dt_FechaIni').val();
			var dt_FechaFin = $('#dt_FechaFin').val();
			var id_Suc = $('#id_Suc').val();
			var filtros = {
				'tipoBusqueda' : tipoBusqueda,
				'idfolio' : idfolio,
				'dt_FechaIni' : dt_FechaIni,
				'dt_FechaFin' : dt_FechaFin,
				'id_Suc' : id_Suc
			};
			$.ajax({   
				url : 'arrayPedidosOptica.php',
				type : 'post',
				data : filtros,
				dataType : "html",
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
		function cambiarStatus(idPedido,idStatus){
			if(confirm('Desea cambiar el estatus del pedido?')){
				var tipo = "";
				switch(idStatus){
					case 14: tipo = "envio";
						break;
					case 13: tipo = "entrego";
						break;
					case 12: tipo = "recibo";
						break;
				}
				$.ajax({
					url : "ajaxCambiarStatusPedido.php",
					type : "POST",
					dataType : "json",
					data : {
						"idPedido": idPedido,
						"idStatus": idStatus
					},
					beforeSend: function() {
						$("#"+tipo+idPedido).html('Cargando...');
					},
					success : function(data) {  					
							
						if(data.error == 0){
							/*$("#"+tipo+idPedido).html('<table><tr><td valing=\"middle\">SI</td><td><img src=\"images/checkGris.png\"> '+data.fecha+'</td></tr></table>');*/
							filtrarDatos();
						}
						else if(data.error == 1){
							alert(data.mensaje);
							$("#"+tipo+idPedido).html('<div id="'+tipo+idPedido+'"><table><tr><td valing="middle">NO</td><td><a href="javascript:cambiarStatus('+idPedido+')"><img border="0" src="images/check.png"></a></td></tr></table></div>');			
						}										
					
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						alert("Status: " + textStatus); alert("Error: " + errorThrown); 
						$("#"+tipo+idPedido).html('<div id="'+tipo+idPedido+'"><table><tr><td valing="middle">NO</td><td><a href="javascript:cambiarStatus('+idPedido+')"><img border="0" src="images/check.png"></a></td></tr></table></div>');
					}           
				});
			}//Fin confirm
		}
		var asInitVals = new Array();
		function crearDataTable(){
			var nombre=" Lista Pedidos";
			var oTable=$('#example').dataTable( {
				//dom: 'lBfrtip',
				"sDom": 'TC<"clear">lBfrtip',
		        "aaSorting": [], 
				//"bProcessing": true,
				//responsive: true,
				"oLanguage": { "sUrl": "http://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"	},
				"bDestroy" :  true , 
				"bDeferRender" :  true ,
				"BFilter" : true,	
				"buttons": [ 	
					{	
						className: 'green', 
						extend: 'print', 
						text: '<i class="fa fa-file-text-o"></i> IMPRIMIR'
					},
					{	
						className: 'green', 
						extend: 'copyHtml5',	
						text: '<i class="fa fa-file-text-o"></i> COPIAR'
		            },
					{	
						className: 'green', 
						extend: 'excelHtml5',
						text: '<i class="fa fa-file-excel-o"></i> EXCEL',
						title:nombre
					},
					{   
						className: 'green',
						extend: 'pdfHtml5', 
						orientation: 'landscape', 
						pageSize: 'LEGAL', 
						text: '<i class="fa fa-file-pdf-o"></i> PDF',	
						title:nombre
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
	</script>
	<script type="text/javascript" src="Reportes/dhtmlgoodies_calendar.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="Reportes/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript">
		function abrir(url) { 
		open(url,'','top=100,left=100,width=300,height=600,scrollbar=yes'); 
		} 
		
		function abrir2(idPedido) { 
			var url = "detallepedido.php?idpedido="+idPedido;
			open(url,'','top=100,left=100,width=800,height=500, scrollbars=yes'); 
		} 
	</script>
	<script language="JavaScript">
		$(function(){
			$('#tipoBusqueda').change(function(){
				if(this.value == 1) {
					$('#contenedor').show(5);
					$('#contenedor1').hide(5);
				} else {
					$('#contenedor').hide(5);
					$('#contenedor1').attr('style','display: block;');
				}
			});
		});
	</script>	
</head>
<body> 
	<form name="newFact" id="newFact" action="JavaScript:filtrarDatos();" method="post">
		<div id="div_global">

        
    <div style="margin-top:60px ;" class="container-fluid ">
        <div class="abs-center">
            <div class="card text-center ">
                <div class="card-title">
                    <p><strong>CERRAR PEDIDOS</strong></p>
                </div>
                <div class="card-bodie">
                    <form id="formscanner">
                        <div>
                            <label for="">
                                <p><strong>Escanear Etiqueta</strong></p>
                                <input type="text" style="margin-bottom:20px ;" name="folioscanner" id="scannerinput" autofocus>
                            </label>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

		</div>
		<br>
		<div id="cargando"></div>
		<div id="contenedorDatatable"></div>       
		<input type="hidden" value="0" id="refreshPagina" />
	</form>
</body>
</html>
