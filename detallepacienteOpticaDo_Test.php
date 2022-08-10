<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
setlocale(LC_ALL,"es_ES");
require ("../db.php");

session_start();

if( $_SESSION['id_TipoUsuario'] != 14 ){
	echo"
		<script>
			alert('Sesion expirada favor de cerrar sesion y volver a ingresar al modulo!');
			window.parent.location.href='logout.php';
		</script>
	";
}
$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento=$_GET['idevento'];
$varcontrol=$_GET['varcontrol'];
		
$queryselect =  "SELECT * FROM tbl_UsuariosWeb WHERE (id_UsuarioWeb = '".$id_UsuarioWeb."')";
$rqueryselect =  mssql_query($queryselect);
$rowdata= mssql_fetch_array($rqueryselect);

$queryValida = "SELECT id_Impreso FROM tbl_TicketGeneral WHERE id_TicketGeneral = '".$_SESSION["id_TicketGeneral"]."' AND id_Impreso = 1";
$rqueryValida = mssql_query($queryValida);
if(mssql_num_rows($rqueryValida)){ ?>
	<script language="javascript" type="text/javascript">
		alert("[ERROR] El ticket de este presupuesto ya ha sido impreso, vuelve a buscar el cliente!!");
		window.opener.location.href = "content.php";
		window.close();
    </script>
<?  exit;
}

$queryCantProds = "SELECT MAX(id_PaqueteOptica)+1 AS cant FROM tbl_PaqueteOptica";
$resCantProds = mssql_query($queryCantProds);
$rowCantProds = mssql_fetch_object($resCantProds);

/////////////////////////////////////////////////////////////////////////////////////
////// OBTIENE EL SALDO DEL MONEDERO JOJUTLA O INVI
include("class.PacienteMonedero.php");
$objPacienteMonedero = new PacienteMonedero();	
$leyendaSaldoMonedero = '';
$saldoMonedero = 0;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////// JOJUTLA
/*if( in_array($_SESSION["id_Sucursal"], array(117,10)) ){
	// Solo aplica a Sucursal JOJUTLA y Callcenter (Prueba)
	if( $objPacienteMonedero->esPacienteJOJUTLA($id_UsuarioWeb) ){
		$saldoMonedero = $objPacienteMonedero->obtenSaldoMonederoJOJUTLA($id_UsuarioWeb);
		$leyendaSaldoMonedero = '<p style="background-color:#3399CC; font-weight:bold; font-size:16px; padding:3px; color:white;">PACIENTE JOJUTLA!! SALDO_MONEDERO($'.number_format($saldoMonedero,2).')</p>';
	}

}*/
/////////////////////////////////////////////////////////////	
$idSucursal = $_SESSION["id_Sucursal"];

require_once("clases/Class.Paciente.php");
$objPaciente= new Paciente();
$vecTP=$objPaciente->tipoPacienteOptica($id_UsuarioWeb,1);
//var_dump($vecTP);
$tipoPaciente=$vecTP['pacienteCliente']; /* INDICA Si es paciente surogado y particiipa para otyica*/ 
$VecConteo=$objPaciente->conteoPacienteSub($id_UsuarioWeb,$tipoPaciente);
$totalOpticaCAM=$VecConteo['conteo'];
$totalOpticaquery=$VecConteo['query'];
$getDataClienteSubrogado = $objPaciente->getDataClienteSubrogado($tipoPaciente, 4);
$id_ReglaSubrogado = $getDataClienteSubrogado->id_ReglaSubrogado;	
$id_Cliente = $getDataClienteSubrogado->id_Cliente;	
$st_NombreCliente = $getDataClienteSubrogado->st_NombreCliente;	
// $i_CantidadPaquetesOptica = $getDataClienteSubrogado->i_CantidadMedicamentos;
// $id_Intervalo = $getDataClienteSubrogado->id_Intervalo;	
// $st_NombreIntervalo = $getDataClienteSubrogado->st_NombreIntervalo;	
// $id_Validacion = $getDataClienteSubrogado->id_Validacion;	
// $st_NombreTipoValidacion = $getDataClienteSubrogado->st_NombreTipoValidacion;	

///// CLIENTES
require_once("clases/class.OpticaSubrogado.php");
$objOpticaSubrogado = new OpticaSubrogado();

$leyendaSubrogado = '';
//echo $tipoPaciente;
if($tipoPaciente>0){ /*Es subrogado */	
	$textAux='Tiene derecho a unos lentes (ARMAZON PAQUETE 1 y MICA TERMINADO CR W Terminado) al año';	 /*Puede estar en cada caso definido*/
	switch ($tipoPaciente) {
		case '11': /*CUAJIMALPA - Si es Paquete 1 mica CR-39 al año*/
			// Total de Paquete 1: ARMAZON PAQUETE 1 y MICA TERMINADO CR W 1 al año
			$nomClientePaciente='CUAJIMALPA';
		break;
		// case '27': /*SILAO */		break; NO APLICA VALIDACION 
		case '29': /*AJALPAN - Si es Paquete 1 mica CR-39 al año*/
			//Total de Paquete 1 mica CR-39 al año
			$nomClientePaciente='AJALPAN';	
			$textAux='Tiene derecho a 4 lentes (PAQUETE 1 Mica CR-39 Terminado)';
		break;
		case '36': /*CAMPECHE - Si es PAQUETE 1 y MICA TERMINADO CR W 1 al año*/
			// Total de Paquete 1: ARMAZON PAQUETE 1 y MICA TERMINADO CR W 1 al año
			$nomClientePaciente='CAMPECHE';	
		break;
		case '41': /*PUEBLA - Si es PAQUETE 1 y MICA TERMINADO CR W 1 al año */
			// Total de Paquete 1: ARMAZON PAQUETE 1 y MICA TERMINADO CR W 1 al año
			$nomClientePaciente='PUEBLA';
		break;		
	}

	 // AND !in_array($tipoPaciente,array(46,48))

	if ($id_ReglaSubrogado > 0) {
		$id_ClienteSubInterface = $getDataClienteSubrogado->id_Cliente;
		$nomClientePaciente = $st_NombreCliente;
	}

	$leyendaSubrogado = '
	<br><br><strong style="color:green;">PACIENTE '.$nomClientePaciente.' </strong>
	<br><span style="color:green;">'.$textAux.'</span>
	<br><span style="color:green;">Adquiridos: '.$totalOpticaCAM.'</span><br>
	';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link href="../styles/style.css" rel="stylesheet" type="text/css">
	<script>
		function sf(ID){
			document.getElementById(ID).focus();
			console.log(<?=$_SESSION['id_TipoUsuario']?>);
		}
	</script>
	<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
	<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
	<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
	<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
	<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>
	<script language="JavaScript">
		function Abrir_ventana (pagina) {
			var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=508, height=365, top=85, left=140";
			window.open(pagina,"",opciones);
		}
		function validar(e) { // 1
			tecla = (document.all) ? e.keyCode : e.which; // 2
			if (tecla==8) return true; // 3
			patron = /[0123456789]/; // 4
			te = String.fromCharCode(tecla); // 5
			return patron.test(te); // 6
		} 
		function vCCat(d,cT) { 
			var nelemni4="cantidad_"+d
			var lbt4 =  document.getElementById(nelemni4);
			var lsp4=lbt4.value
			if( lsp4>cT){
				alert('No pude incluir mas productos de los que hay en almacen')
				lbt4.value='1'
			}
		} 
	</script>
	<!--<script type="text/javascript" src="../cac/scripts.js"></script>-->
	<script type="text/javascript" src="../cac/expansor.js"></script>
	<script src ="js/jquery-1.6.4.min.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Documento sin t&iacute;tulo</title>
	<style type="text/css">
		body {
			margin-left: 0px;
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
		.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
		.Estilo8 {
			color: #FFFFFF;
		}
		.botonAzul {
		    background: #0D98FB;
		    background-image: -webkit-linear-gradient(top,#0D98FB,#1A5DB3);
		    background-image: -moz-linear-gradient(top,#0D98FB,#1A5DB3);
		    background-image: -o-linear-gradient(top,#0D98FB,#1A5DB3);
		    background-image: linear-gradient(to bottom,#0D98FB,#1A5DB3);
		    border: 1px solid #125CB5;
		    -moz-border-radius: 5px;
		    -webkit-border-radius: 5px;
		    -o-border-radius: 5px;
		    border-radius: 5px;
		    -moz-box-shadow: 0 1px 1px #71C0FD inset;
		    -webkit-box-shadow: 0 1px 1px #71C0FD inset;
		    -o-box-shadow: 0 1px 1px #71C0FD inset;
		    box-shadow: 0 1px 1px #71C0FD inset;
		    padding: .5em .4em;
		    color: white;
		    font-weight: normal;
		}
	</style>
	<script src="../../utils/jqueryAlerts11/jquery.alerts.js" language="javascript" type="text/javascript"></script>
	<link href="../../utils/jqueryAlerts11/jquery.alerts.css" rel="stylesheet" type="text/css" />
	<script src="../../utils/jqueryPlugins/jquery.numeric.js" language="javascript" type="text/javascript"></script>
	<script type="text/javascript">
		//console.log('<? //echo $totalOpticaquery; ?>');
		var peticion = false;
	   	var  testPasado = false;
	   	try {
	     	peticion = new XMLHttpRequest();
	    } catch (trymicrosoft) {
	   		try {
	   			peticion = new ActiveXObject("Msxml2.XMLHTTP");
	   		} catch (othermicrosoft) {
	  			try {
	  				peticion = new ActiveXObject("Microsoft.XMLHTTP");
	  			} catch (failed) {
	  				peticion = false;
	  			}
	  		}
	  	}
		if (!peticion)
		alert("ERROR AL INICIALIZAR!");

	    function changeAjax (url, comboAnterior, element_id) {
	       	var element =  document.getElementById(element_id);
	       	var valordepende = document.getElementById(comboAnterior)
	       	var x = valordepende.value
	       	if(url.indexOf('?') != -1) {
	           var fragment_url = url+'&Id='+x;
	       	}else{
	           var fragment_url = url+'?Id='+x;
	       	}
	       	element.innerHTML = 'Cargando...<!--<img src="Imagenes/loading.gif" />-->';
	       	peticion.open("GET", fragment_url);
	       	peticion.onreadystatechange = function() {
	       		if (peticion.readyState == 4) {
	       			element.innerHTML = peticion.responseText;
	           	}
	       	}
	      	peticion.send(null);
	   	}
		function generaCotizacion(){
			var existe = 0;
			for(i=1;i<<?=$rowCantProds->cant?>;i++){
				if($("#Producto_"+i).length){
					existe = 1;
				}
				if(existe>0){
					break;
				}
			}
			if(existe>0){
				var gets = "?";
				var checado = 0;
				for(i=1;i<<?=$rowCantProds->cant?>;i++){
					if($("#Producto_"+i).attr('checked')){
						if(i == <?=((int)$rowCantProds->cant)-1?>){
							gets += "Producto_"+i+"="+$("#Producto_"+i).val();
						}else{
							gets += "Producto_"+i+"="+$("#Producto_"+i).val()+"&";
						}
						checado++;
					}
				}
				if(checado == 0){
					alert("Seleccione un paquete para hacer la cotizacion");
				} else{
					var confirma = confirm("\u00BFImprimir cotizacion?");
					if(confirma){
						location.href="cotizacionPreview.php"+gets+"&id_UsuarioWeb=<?=$id_UsuarioWeb?>";
					}
				}
			}else{
				alert("Buscar un producto en el catalogo");
			}
		}
		function agregarProductos(){
			var id_TicketGeneral = "<?=$_SESSION['id_TicketGeneral']?>";
			console.log("Revisando proceso agregarProductos()...");
			console.debug("id_TicketGeneral: %s",id_TicketGeneral);
			var idSucursal = $("#idSucursal").val();
			var cantidadMaxima = 3;
			if(idSucursal == 82){cantidadMaxima=4;}
			//if(in_array($idSucursal,array($idCall,194,195,196,197,198,199,200,201,202))){cantidadMaxima=1;}
			if(id_TicketGeneral != "")
			{
			
				var datos = {
					"id_TicketGeneral" : id_TicketGeneral
				};
				
				$.ajax({
					url:"ajaxRevisaCantidadProductos.php",
					data:datos,
					dataType:"JSON",
					type:"POST",
					beforeSend:function(){
						$("button[name=Submit2]").attr("disabled",true);
					},
					success:function(res){
						var cantidad = parseInt(res.cantidad);
						console.debug("cantidad = %d",cantidad);	
						if( cantidad < cantidadMaxima)
						{
							console.debug("Todo correcto! Cantidad de productos = %d",cantidad);
							$('#form2').submit();
						}
						else
						{
							console.debug("La cotización ya contiene "+cantidadMaxima+" productos!");
							jAlert("El presupuesto solo puede contener como máximo "+cantidadMaxima+" productos, si necesitas agregar más productos deberas realizar otro ticket!","Cuadro de Diálogo",0,function(){
								$("button[name=Submit2]").attr("disabled",false);
							});
						}
					},
					error:function(jqXHR,textStatus,errorThrown ){
						console.error("jqXHR : %s \ntextStatus : %s \nerrorThrown: %s",jqXHR,textStatus,errorThrown);
						$("button[name=Submit2]").attr("disabled",false);
					}
				});
			}
			else
			{
				console.warn("Sesión expirada al agregar productos!");
				alert("Sesion expirada, favor de reingresar al sistema!");
				window.parent.location.href='logout.php';
			}
		}
		function procesarCompraCaja(){
			var id_TicketGeneral = "<?=$_SESSION['id_TicketGeneral']?>";
			//console.log("Revisando proceso ProcesarCompraCaja()...");
			//console.debug("id_TicketGeneral: %s",id_TicketGeneral);
			
			var acuenta = $("#frame1").contents().find("#acuenta").val();
			var i_Total = $("#frame1").contents().find("#i_Total").val();
			var iSaldoMonedero = $("#saldoMonedero").val();
			//console.log("acuenta: "+acuenta);
			
			if(acuenta == "" || (parseFloat(acuenta) <= 0 && parseFloat(i_Total) > 0))
			{
				jAlert("Verifique que la cantidad a cuenta sea correcta!","Cuadro de Diálogo",0,function(){
					$("#frame1").contents().find("#acuenta").focus();
				});
			}
			else if( parseFloat(acuenta) < ( parseFloat(i_Total) * 0.3 )  ){
				jAlert("Verifique que la cantidad a cuenta cubra por lo menos el 30% del monto total!","Cuadro de Diálogo",0,function(){
					$("#frame1").contents().find("#acuenta").focus();
				});
			}
			else if ( (parseFloat(iSaldoMonedero) >= parseFloat(i_Total)) && (parseFloat(acuenta) < parseFloat(i_Total)) ){
				jAlert("Debe poner el monto total de lo cotizado, ya que cuenta con el saldo suficiente en el Monedero!!","Cuadro de Diálogo",0,function(){
					$("#frame1").contents().find("#acuenta").focus();
				});
			}
			else if ( (parseFloat(iSaldoMonedero) < parseFloat(i_Total)) && (parseFloat(acuenta) < parseFloat(iSaldoMonedero)) ){
				jAlert("Debe poner como monto minimo, todo el saldo del Monedero!!","Cuadro de Diálogo",0,function(){
					$("#frame1").contents().find("#acuenta").focus();
				});
			}
			else if( parseFloat(acuenta) > ( parseFloat(i_Total) )  ){
				jAlert("El monto a cuenta excede el total de la venta!!","Cuadro de Diálogo",0,function(){
					$("#frame1").contents().find("#acuenta").focus();
				});
			}
			else
			{
				if(id_TicketGeneral != "")
				{
					var datos = {
						"id_TicketGeneral" : id_TicketGeneral
					};
					
					$.ajax({
						url:"ajaxRevisaCantidadProductos.php",
						data:datos,
						dataType:"JSON",
						type:"POST",
						beforeSend:function(){
							$("button[name=procesaAcaja]").attr("disabled",true);
						},
						success:function(res){
							var cantidad = parseInt(res.cantidad);
							
							if( cantidad > 0)
							{
								//console.debug("Todo correcto! Cantidad de productos = %d",cantidad);
								if(confirm('Desea continuar con la transacción'))
									$("#frame1").contents().find('#form3').submit();
							}
							else
							{
								//console.debug("Para procesar la compra a caja por lo menos debe seleccionar un producto!");
								jAlert("Para procesar la cotización a caja por lo menos debe seleccionar un producto!","Cuadro de Diálogo",0,function(){
									$("button[name=procesaAcaja]").attr("disabled",false);
								});
							}
						},
						error:function(jqXHR,textStatus,errorThrown ){
							//console.error("jqXHR : %s \ntextStatus : %s \nerrorThrown: %s",jqXHR,textStatus,errorThrown);
							$("button[name=procesaAcaja]").attr("disabled",false);
						}
					});
				}
				else
				{
					//console.warn("Sesión expirada al procesar compra a caja!");
					alert("Sesion expirada, favor de reingresar al sistema!");
					window.parent.location.href='logout.php';
				}
			}
		}
		function soloNumeros(elemento){
		    $(elemento).numeric('.');
		}
	</script>
</head>
<body onLoad="sf('token');">	
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		    <td>
		      <table width="100%" border="0">
		        <tr>
		          <td width="15%">&nbsp;</td>
		          <td width="85%"><div align="center" class="telefonosOutbound"> </div></td>
		        </tr>
		      </table>
		  	</td>
		    <td>&nbsp;</td>
		</tr>
	  	<tr>
	    	<td>
				<div align="center">
				    <div class="wrapper">
	          			<div class="DIVleft">  <!-- CENTER/MEDIO -->
	            			<div class="DIVmod_header_border">
	            				<img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" />
	            			</div>
				            <div class="DIVmod_header"> 
				              <div class="DIVmod_header_text">
				              	<img src="../images/icosapps/optica.png" width="48" height="48" /> 
				                Cotización de 
				                <?=$rowdata['st_Nombre']?>
				              </div>
				            </div>
	            			<div class="DIVmod"> 
	              				<div class="DIVpadding"> 
	              					<input type="hidden" name="idSucursal" id="idSucursal" value="<?=$idSucursal?>" />
	              					<input type="hidden" name="saldoMonedero" id="saldoMonedero" value="<?=$saldoMonedero?>" />
	              					<?=$leyendaSaldoMonedero?>
	                				<div align="right"> 
	        						<? 	$numrowsdel = mssql_num_rows($rqueryselect2);
										$idrec = $rowdata2['id_Receta'];
										if($numrowsdel >  0  ){	?>
						                <!-- <a href="detallepacienterecetalistprintP.php?ideventocita=<?=$idevento?>&idevento=<?=$idrec?>&idusuarioweb=<?=$id_UsuarioWeb?>">Imprimir 
						                  ticket</a> -->
						            <? 	}	?>
	                  					<img src="../images/icGuiones.gif" width="32" height="32" /></div>
										<?php /*?> <a href="detallepacientepatologia.php?idusuarioweb=<?=$id_UsuarioWeb?>&amp;idevento=<?=$idevento?>"><br /> </a><br /><?php */?>
						                <form name="form2" method="post" action="doregistroOptica.php" id="form2">
						                  	<img src="../images/iPassed.png" width="12" height="12" />Paquete:
						                  	<label> 
						                  		<input name="token" type="text" id="token" placeholder="Paq..." />
						                  	</label>
						                  	<input type="button"name="sendSearch" value="Buscar" id="sendSearch" class="botonAzul" />
						                  	<input name="idtipo" type="hidden" id="idtipo" value="1" />
						                  	<input type="button" onClick="JavaScript:agregarProductos();" name="Submit2" value="Agregar" />
											<input name="idusuarioweb" type="hidden" id="idusuarioweb" value="<?=$id_UsuarioWeb?>" />
											<input name="idevento" type="hidden" id="idevento" value="<?=$idevento?>" />
											<input name="i_Consultorio" type="hidden" value="<?=$i_Consultorio?>" />
						                  	<?=$leyendaSubrogado?>
						                  	<strong><div id="xDiv_SubEstados_2" >..</div></strong>
						                </form>
		                				<!--<input type="button" name="imprime" value="Imprimir cotizacion" onclick="javascript:generaCotizacion();">-->
		              				</div>
	              					<iframe id="frame1" src="listaServiciosOptica.php?i_Consultorio=<?=$i_Consultorio?>&idevento=<?=$idevento?>&id_UsuarioWeb=<?=$id_UsuarioWeb?>" width="95%" height="400" scrolling="auto" frameborder="0" transparency> 
	              						<p>Texto alternativo para navegadores que no aceptan iframes.</p>
	              					</iframe>
					              	<div class="DIVmod_footer" > 
					                	<div id="div5"> 
					                  		
					                	</div>
					              	</div>
					            </div>
					            <div class="DIVmod_footer_border">
					            	<img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" />
					            </div>
					            <span class="Estilo7"><br /></span>
					        </div>
	        			</div>
					</div>
				</div>
			</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<script type="text/javascript">
		$(document).ready(function(){ 
			// $('#token').keyup(function(){
			// 	if($(this).val().length > 3){
			// 		var idUsu=<?=$id_UsuarioWeb?>;
			// 		changeAjax('ajaxOptica_testi.php?empresa=1&idUsuarioWeb='+idUsu+'&typoP='+<?=$tipoPaciente?>, 'token', 'xDiv_SubEstados_2'); 
			// 	}
			// });
			$("#sendSearch").click(function(){
				if($('#token').val().length > 3){
					var idUsu=<?=$id_UsuarioWeb?>;
					changeAjax('ajaxOptica.php?empresa=1&idUsuarioWeb='+idUsu+'&typoP='+<?=$tipoPaciente?>, 'token', 'xDiv_SubEstados_2'); 
				}else{
					alert('Indica un nombre de mas de 3 caracteres');
				}
			});
		});
	</script>
</body>
</html>
<?php mssql_close(); ?>
