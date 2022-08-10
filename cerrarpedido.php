<?php
require("../db.php");
$ruta2index = "../../";


$folio = $_GET['scannerinput'];



include("Clases/Class.Pedido.php");
$objpedido=new Pedido();
$tablascanner=$objpedido->GetScanner($folio);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="<?= $ruta2index ?>utils/jquery-1.11.1.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" href="<?=$ruta2index?>bionline/securitylayer/styles/botones.css" type="text/css">
	<link href="<?=$ruta2index?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
	<link href="<?=$ruta2index?>utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

	
	<script type="text/javascript" src="<?=$ruta2index?>utils/tinybox/tinybox.js"></script>
    

    <title>CERRAR PEDIDO </title>

</head>

<style>
    
.card {
    background: rgba( 0, 165, 241, 0.15 );
    box-shadow: 0 8px 32px 0  rgba( 0, 165, 241, 0.15 );
    backdrop-filter: blur( 20px );
    -webkit-backdrop-filter: blur( 20px );
    border-radius: 10px;
    border: 1px solid rgba( 255, 255, 255, 0.18 );
    }

.img1{
    width: 300px;
    height: 110px;
}

.inp {
    width: 650px;
    height: 50px;
    border-radius: 22px;
    background-color: rgba(255, 255, 255, 0.18);
    font-size: 38px;
    padding: 12px 20px;
}

.inp[type=text]:focus {
    background-color: transparent;
    text-align: center;
}

.inp:hover, select:hover {
        border-color: transparent;
        box-shadow: 0 0 0 5px deepskyblue;
    }

.inp[type=text] {
        transition: width 0.4s ease-in-out;
    }

.inp[type=text]:focus {
        width: 90%;
    }

.inp:hover {
        color: black;
    }
</style>

<script>

function abrirPopReload(ancho,alto,php){
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


        function detalle(idPedido){
			abrirPopReload(800,550,'detallepedido_test.php?idpedido='+idPedido);
		}
		function Ticket(idsession){
			abrirPopReload(400,600,'../../ticketgeneralfinal.php?idsession='+idsession);
		}
		function etiquetaSobre(idPedido){
            abrirPopReload(650,350,'imprimeEtiquetaSobreCodeBar.php?idPedido='+idPedido);
        }
        function surtirMicas(idPedido){
            abrirPopReload(650,350,'Inventario/surtirMicas.php?idPedido=');
        }
		//IMPRIME TODOS
		function imprimetodos(idPedido){
			abrirPopReload(650,350,'imprimeall.php');
		}




               
        function cambioEstatusRecibidoLabExt(id,folio,statusOld){
			if(confirm("¿ Seguro de recibir el folio "+folio+"?")){
				var parametros = { 'bandera': '2' ,'folio' : folio ,'id_EventoConcepto' : id , 'statusold' :statusOld };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidosLabExt.php',
					type:  'post',
					beforeSend: function () {
						$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	
					},
					success:  function (response) {							
						var a=response;	
						a=a.split("||");		
						if(a[0]==1){
							$("#RLE_"+id).html(a[1]);
                            $("#TLE_"+id).html(a[3]);
							$("#resultado").html(BienEcho);
						}else{
							$("#resultado").html(Error);
						}								
					},
					error: function (){	$("#resultado").html(Error);}
				});	
			}
		}


        function cambioEstatusTerminadoLabExt(id,folio,statusOld){
			if(confirm("¿ Seguro de terminar y enviar el folio "+folio+"?")){
				var parametros = { 'bandera': '3' ,'folio' : folio ,'id_EventoConcepto' : id , 'statusold' :statusOld };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidosLabExt.php',
					type:  'post',
					beforeSend: function () {
						$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');
					},
					success:  function (response) {
						var a=response;
						a=a.split("||");
						if(a[0]==1){
							$("#TLE_"+id).html(a[1]);
							//$("#Status_"+id).html(a[2]);
							$("#resultado").html(BienEcho);
							//$('#TipoTra_'+id).attr('disabled',false);
						}else{
							$("#resultado").html(Error);
						}
					},
					error: function (){	$("#resultado").html(Error);}
				});
			}
		}

		function selTipoTabajo(id_EventoConcepto){
			var id_TipoTrabajo=$('#TipoTra_'+id_EventoConcepto).val();
			console.log("id_TipoTrabajo: " + id_TipoTrabajo);
			
			if(confirm("¿ Seguro del tipo trabajo ?")){
				var parametros = { 'bandera': '3' ,'id_EventoConcepto' : id_EventoConcepto , 'id_TipoTrabajo' :id_TipoTrabajo };		
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',	
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {										
						var a=response;							
						a=a.split("||");	
						if(a[0]==1){								
							$("#resultado").html(BienEcho);
							$('#TipoTra_'+id_EventoConcepto).attr('disabled',true);					
							$('#laboratorio_'+id_EventoConcepto).removeAttr('disabled');						
						}else{
							$("#resultado").html(Error);
						}								
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}	
		}

		function selLaboratorio(id_EventoConcepto){
			var id_Laboratorio=$('#laboratorio_'+id_EventoConcepto).val();
			if(confirm("¿ Seguro del laboratorio al que envia ?")){
				var parametros = { 'bandera': '4' ,'id_EventoConcepto' : id_EventoConcepto , 'id_Laboratorio' :id_Laboratorio };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {												
						var a=response;				
						a=a.split("||");				
						if(a[0]==1){			
							if(a[1]!=""){ 
								$("#Status_"+id_EventoConcepto).html(a[1]); 
								$("#FechaEnvio_"+id_EventoConcepto).html(a[2]);
								$("#Terminado_"+id_EventoConcepto).html(a[3]);
							}
							$("#resultado").html(BienEcho);					
							$('#laboratorio_'+id_EventoConcepto).attr('disabled',true);
						}else{
							$("#resultado").html(Error);
						}															
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}	
		}

		function Terminado(id,folio){
			if(confirm("¿ Seguro de recibir de laboratorio el folio "+folio+"?")){
				var parametros = { 'bandera': '5' ,'folio' : folio ,'id_EventoConcepto' : id };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {
						var a=response;	
						a=a.split("||");		
						if(a[0]==1){
							$("#Status_"+id).html(a[1]);
							$("#Terminado_"+id).html(a[2]);
							$('#Costo_'+id).attr('disabled',false);
							$('#Gcosto_'+id).attr('disabled',false);
							$("#resultado").html(BienEcho);
						}else{
							$("#resultado").html(Error);
						}								
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}
		}

		function GuardaCM(id_EventoConcepto){
			var Costo=$('#Costo_'+id_EventoConcepto).val();
			if(Costo!=""){
				if(confirm("¿ Seguro del Costo Micas?")){
				var parametros = { 'bandera': '6' ,'Costo' : Costo ,'id_EventoConcepto' : id_EventoConcepto };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {								
						var a=response;	
						a=a.split("||");		
						if(a[0]==1){
							$("#resultado").html(BienEcho);	
							$('#Costo_'+id_EventoConcepto).attr('disabled',true);
							$('#Gcosto_'+id_EventoConcepto).attr('disabled',true);	
							$("#FechaES_"+id_EventoConcepto).html(a[1]);
								
						}else{
							$("#resultado").html(Error);	
						}																						
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}
					
			}else{
				alert("Ingrese el costo");
			}	
		}

		function cambioEstatusEnviadoAS(id,folio){
			if(confirm("¿ Seguro de enviar a Sucursal ")){
				var parametros = { 'bandera': '7' ,'id_EventoConcepto' : id };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {															
						var a=response;	
						a=a.split("||");		
						if(a[0]==1){
							$("#Status_"+id).html(a[1]);
							$("#FechaES_"+id).html(a[2]);										
							$("#resultado").html(BienEcho);					
						}else{
							$("#resultado").html(Error);
						}								
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}
		}

		function cambioEstatusRecibidoLabML(id,folio,idPedido){
			if(confirm("Aplicar Garantia y Regresar a Laboratorio ML el folio: "+folio+"?")){
				var parametros = { 'bandera': '9' ,'idPedido' : idPedido , 'folio' : folio ,'id_EventoConcepto' : id , 'statusold' :'14' };
				$.ajax({
					data:  parametros,
					url:   'ListaPrdidos/ajax_procesoListaPedidos.php',
					type:  'post',
					beforeSend: function () {	$("#resultado").html('Cargando, por favor espere... <img src="<?=$ruta2index?>bionline/securitylayer/images/cargando.gif" border="0"/>');	},
					success:  function (response) {							
						var a=response;	
						a=a.split("||");		
						if(a[0]==1){
							$("#R_"+id).html(a[1]);
							$("#Status_"+id).html(a[2]);					
							$("#resultado").html(BienEcho);
							$('#TipoTra_'+id).attr('disabled',false);
						}else{
							$("#resultado").html(Error);
						}								
					},
					error: function (){		$("#resultado").html(Error);}
				});	
			}
		}

 














</script>


<body class="justify-content-center align-items-center"  >


    <div style="margin-top:60px ;" class="container-fluid ">
        <div class="abs-center">
            <img src="images/anlLogo.jpg" alt="" class="img1" style="margin-bottom:18px;"  >
            <div class="card text-center ">
                <div class="card-title">
				<td><img src="<?= $ruta2index ?>bionline/securitylayer/images/statistics.gif" width="48" height="48"></td>
                    <p><strong>CERRAR PEDIDOS</strong></p>
                </div>
                <div class="card-bodie">
                    <form id="formscanner">
                        <div>
                            <label for="">
                                <p><strong>Escanear Etiqueta</strong></p>
                                <input type="text" style="margin-bottom:20px;" name="scannerinput" id="scannerinput" class="inp" autofocus>
                            </label>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid" style="margin-top: 9px; ">
        <div class="card-title text-center">
    
            <div class="card-bodie">
                <div id="contenedorDatatable"></div>
                <div>
                    <?php 
                    if($folio){
                        echo $tablascanner;

                    }
                    

                    
                    
                    ?>


                </div>


            </div>
        </div>



m
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

        <script>
            function getfocus() {
                $("#scannerinput").focus();
            }
             $(document).ready(function(){
               getfocus();
            })

        </script>
</body>

</html>