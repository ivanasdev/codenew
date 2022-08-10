<?php
session_start();
require(".../db.php");


$idUsuarioWeb = $_SESSION['id_Operador'];
$idCotizacion = $_GET['idCotizacion'];
$idPaquete = $_GET['idPaquete'];
$control = $_GET['control'];
$do = $_GET['do'];
 
if (intval($control) > 1){
	$disabled = 'disabled';
}else{
	$disabled = '';
}

$porveedor=0;

$query1 = "
	select
		isnull(id_PedidoOpticaPrevio,0) as id_PedidoOpticaPrevio, id_CotizacionOptica, st_EsferaIzq, st_EsferaDer, st_CilindroDer,
		st_CilindroIzq, st_EjeIzq, st_EjeDer, st_AO, st_DI, st_ADD, id_Armazon, id_Modelo, id_Color, id_Material, id_Lente, id_LenteContacto,
		id_Tipo, id_Paquete, st_Observaciones, id_UsuarioSistemaWeb, id_Proveedor, st_Sobre,i_incompleto
	from
		tbl_PedidosOpticaPrevio
	where
		id_CotizacionOptica = '".$idCotizacion."'
		and id_Paquete = '".$idPaquete."'
";

$rquery1 = mssql_query($query1);
$row1 = mssql_fetch_array($rquery1);
$idPedidoOpticaPrevio = $row1['id_PedidoOpticaPrevio'];
$i_incompleto = $row1['i_incompleto'];

if($row1['id_Proveedor']!="" && $row1['id_Proveedor']!=" "){//if para proveedor
	$porveedor=$row1['id_Proveedor'];
}
$camposOcultosAuxiliares = '';
if($idPedidoOpticaPrevio != 0){
	$camposOcultosAuxiliares = '
		<input type="hidden" id="esfeIzq" value="'.$row1['st_EsferaIzq'].'" />
		<input type="hidden" id="esfeDer" value="'.$row1['st_EsferaDer'].'" />
		<input type="hidden" id="cilIzq" value="'.$row1['st_CilindroIzq'].'" />
		<input type="hidden" id="cilDer" value="'.$row1['st_CilindroDer'].'" />
		<input type="hidden" id="ejeIzq" value="'.$row1['st_EjeIzq'].'" />
		<input type="hidden" id="ejeDer" value="'.$row1['st_EjeDer'].'" />
		<input type="hidden" id="ao" value="'.$row1['st_AO'].'" />
		<input type="hidden" id="di" value="'.$row1['st_DI'].'" />
		<input type="hidden" id="add" value="'.$row1['st_ADD'].'" />
		<input type="hidden" id="armazonid" value="'.$row1['id_Armazon'].'" />
		<input type="hidden" id="modeloid" value="'.$row1['id_Modelo'].'" />
		<input type="hidden" id="colorid" value="'.$row1['id_Color'].'" />
		<input type="hidden" id="materialid" value="'.$row1['id_Material'].'" />
		<input type="hidden" id="lenteid" value="'.$row1['id_Lente'].'" />
		<input type="hidden" id="lcid" value="'.$row1['id_LenteContacto'].'" />
		<input type="hidden" id="tipoid" value="'.$row1['id_Tipo'].'" />
		<input type="hidden" id="observaciones" value="'.$row1['st_Observaciones'].'" />
	';
	
	$tratamientos = "";
	$query2 = "select id_TratamientoOptica,st_Tratamiento from cat_TratamientosOptica where id_Status = 1";
	$rquery2 = mssql_query($query2);
	
	$idsTratamientos = array();
	$i=0;
	$query3= "select id_Tratamiento from tbl_TratamientosPedidoPrevio where id_PedidoOpticaPrevio = ".$idPedidoOpticaPrevio;
	$rquery3 = mssql_query($query3);
	while($row3 = mssql_fetch_object($rquery3)){
		$idsTratamientos[$i] = $row3->id_Tratamiento;
		$i++;
	}
	
	while($row2 = mssql_fetch_object($rquery2)){
		if(in_array($row2->id_TratamientoOptica,$idsTratamientos)){
			$tratamientos .= $row2->st_Tratamiento." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' checked id='idTratamiento_".$row2->id_TratamientoOptica."' name='idTratamiento_".$row2->id_TratamientoOptica."'/><br>";
		}else{
			$tratamientos .= $row2->st_Tratamiento." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='idTratamiento_".$row2->id_TratamientoOptica."' name='idTratamiento_".$row2->id_TratamientoOptica."'/><br>";
		}
	}
	//echo var_dump($tratamientos);
	$camposOcultosAuxiliares.="<div id='tratamientos' style='display:none;'>".$tratamientos."</div>";
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="../styles/style.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="estilos/styles.Validate.css" media="screen" />
    <style>
		.graduacion {	border-collapse: collapse;		}
		.graduacion {	border: 1px solid black;		}
	</style>
<script type="text/javascript" src="js/jquery.min.js"></script>	
<script type="text/javascript" language="javascript">
var url = "modeloOptica.php";
$(function(){
	
	$('#selectLente').change(function(){
		
		var tabla = "";
		var idCotizacion = $('#idCotizacion').val();
		var deshabilitado = $('#deshabilitado').val();
		var nomBoton = "Guardar Pedido Previo";
		
		if($('#doPedidoProveedor').val() == 1)
			nomBoton = "Guardar y Finalizar Pedido";
					
		tabla += "<table border='0'>";
		tabla += "<tr><td></td><td></td></tr>";
		
		if(this.value == 1){
			$.ajax({
				type:'POST',
				url:url,
				data:{accion:'getArmazones'},
				success:function(data){
					var armazonid = 'this.value';
					var esfeIzq = '';
					var esfeDer = '';
					var cilIzq = '';
					var cilDer = '';
					var ejeIzq = '';
					var ejeDer = '';
					var ao = '';
					var di ='';
					var add = '';
					var observaciones = '';
					if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1){
						armazonid = $('#armazonid').val();
						var esfeIzq = $('#esfeIzq').val();
						var esfeDer = $('#esfeDer').val();
						var cilIzq = $('#cilIzq').val();
						var cilDer = $('#cilDer').val();
						var ejeIzq = $('#ejeIzq').val();
						var ejeDer = $('#ejeDer').val();
						var ao = $('#ao').val();
						var di = $('#di').val();
						var add = $('#add').val();
						var observaciones = $('#observaciones').val();
					}
					
					tabla += "<tr><td><b>Armazon:</b></td><td><select "+deshabilitado+" id='id_Armazon' name='id_Armazon' onchange='JavaScript:getModelos("+armazonid+");' required><option disabled value='0' selected >--Selecciona Armazon--</option>"+data+"</select></td></tr>"; 
					tabla += "<tr><td><b>Modelo:</b></td><td><div id='contenedorModelo'>Esperando selecci&oacute;n...</div></td></tr>";
					tabla += "<tr><td><b>Color:</b></td><td><div id='contenedorColor'>Esperando selecci&oacute;n...</div></td></tr>";
					tabla += "<tr><td><b>Material:</b></td><td><div id='contenedorMaterial'>Esperando selecci&oacute;n...</div></td></tr>";
					tabla += "<tr><td><b>Lente:</b></td><td><div id='contenedorLente'>Esperando selecci&oacute;n...</div></td></tr>";
					tabla += "<tr><td><b>Tratamientos:</b></td><td><div id='contenedorTratamientos'>Esperando selecci&oacute;n...</div></td></tr>";
					tabla += "<tr>";
					tabla += "		<td colspan='2'>";
					
					
					
					/***INICIO MASCARA DE DATOS ARMAZON***/
					
tabla +='<table>';
tabla +='	<tr>';
tabla +='        <td>';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th>A.V.</th>';
tabla +='                    <th>CON RX</th>';
tabla +='                    <th>SIN RX</th>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="od_crx" name="od_crx" size="12px" placeholder="20/40"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="od_srx" name="od_srx" size="12px" placeholder="20/70"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oi_crx" name="oi_crx" size="12px" placeholder="20/40"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oi_srx" name="oi_srx" size="12px" placeholder="20/70"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">A.O.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="ao_crx" name="ao_crx" size="12px" placeholder="20/30"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="ao_srx" name="ao_srx" size="12px" placeholder="20/40"/></td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='        </td>';
tabla +='        <td valign="top">';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4" align="center">Autorefract&oacute;metro</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_esfera" name="oda_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_cilindro" name="oda_cilindro" size="7px" placeholder="-0.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_eje" name="oda_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_esfera" name="oia_esfera" size="7px" placeholder="-1.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_cilindro" name="oia_cilindro" size="7px" placeholder="-0.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_eje" name="oia_eje" size="7px" placeholder="170"/></td>';
tabla +='                </tr>';                    
tabla +='            </table>';
tabla +='        </td>';                 			 		 
tabla +='    </tr>';
tabla +='    <tr>';
tabla +='    	<td colspan="2"><br /></td>';
tabla +='    </tr>';
tabla +='	<tr>';
tabla +='    	<td>';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4" align="center">RX Anterior</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_esfera" name="rxad_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_cilindro" name="rxad_cilindro" size="7px" placeholder="-0.75"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_eje" name="rxad_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_esfera" name="rxai_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_cilindro" name="rxai_cilindro" size="7px" placeholder="-0.75"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_eje" name="rxai_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';                    
tabla +='            </table>';
tabla +='        </td>';
tabla +='    	<td>';
tabla +='            <table border="0" class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4">RX Actual</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEsferaDer" name="stEsferaDer" value=""+esfeDer+"" placeholder="-1.00"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stCilindroDer" name="stCilindroDer" value=""+cilDer+"" placeholder="-0.75"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEjeDer" name="stEjeDer" value=""+ejeDer+"" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEsferaIzq" name="stEsferaIzq" value=""+esfeIzq+"" placeholder="-1.00"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stCilindroIzq" name="stCilindroIzq" value=""+cilIzq+"" placeholder="-0.75"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEjeIzq" name="stEjeIzq" value=""+ejeIzq+"" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='		</td>';
tabla +='	</tr>';
tabla +='    <tr>';
tabla +='    	<td colspan="2"><br /></td>';
tabla +='    </tr>';
tabla +='    <tr>';
tabla +='        <td valign="top">';                            
tabla +='            <table border="0" class="graduacion">';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td align="center"><b>ADD</b></td>';
tabla +='                    <td align="center"><b>AO</b></td>';
tabla +='                    <td align="center"><b>DI</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td><input type="text" size="8px" '+deshabilitado+' id="stADD" name="stADD" value=""+add+"" placeholder="+0.75"/></td>';
tabla +='                    <td><input type="text" size="8px" '+deshabilitado+' id="stAO" name="stAO" value=""+ao+"" placeholder="13"/></td>';
tabla +='                    <td><input type="text" size="10px" '+deshabilitado+' id="stDI" name="stDI" value=""+di+"" placeholder="61/63"/></td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='        </td>';
tabla +='        <td>';
tabla +='            <table>';
tabla +='                <tr>';
tabla +='                    <td rowspan="2" valign="top"><b>Tinte:</b></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  name="tinte" id="tinte" placeholder="Ingresa el tinte"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td align="center">';
tabla +='                        &nbsp;1<input type="radio" '+deshabilitado+'  name="tipoTinte[]" id="tipoTinte[]" />';
tabla +='                        &nbsp;2<input type="radio" '+deshabilitado+'  name="tipoTinte[]" id="tipoTinte[]" />';
tabla +='                        &nbsp;3<input type="radio" '+deshabilitado+'  name="tipoTinte[]" id="tipoTinte[]" />';
tabla +='                    </td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='        </td>';
tabla +='    </tr>';
tabla +='</table>';
					
					/***FIN    MASCARA DE DATOS ARMAZON***/
					
					tabla += "		</td>";
					tabla += "</tr>";
					tabla += "<tr><td><b>Observaciones:</b></td><td><textarea placeholder='Ingresa las observaciones correspondientes al pedido' rows='4' cols='50' "+deshabilitado+" id='stObservaciones' name='stObservaciones'>"+observaciones+"</textarea></td></tr>";
					tabla += "<tr><td colspan='2' align='center'><br><br><input type='button' "+deshabilitado+" value='"+nomBoton+"' onclick='JavaScript:enviar();' id='boton'/></td></tr></table>";
				
					$('#contenedorPrincipal').html(tabla);
					if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1){
						$('#id_Armazon').change();
						$('#id_Armazon').val(armazonid);
					}
				}
			});
		}else{
			$.ajax({
				type:'POST',
				url:url,
				data:{accion:"getLentesContacto"},
				success:function(data){
					data = data.split(",,,");
					var esfeIzq = '';
					var esfeDer = '';
					var cilIzq = '';
					var cilDer = '';
					var ejeIzq = '';
					var ejeDer = '';
					var observaciones = '';
					if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
					{
						var esfeIzq = $('#esfeIzq').val();
						var esfeDer = $('#esfeDer').val();
						var cilIzq = $('#cilIzq').val();
						var cilDer = $('#cilDer').val();
						var ejeIzq = $('#ejeIzq').val();
						var ejeDer = $('#ejeDer').val();
						var observaciones = $('#observaciones').val();
					}
					tabla += "<tr><td><b>Lente de Contacto:</b></td><td>"+data[0]+"</td></tr>";
					tabla += "<tr><td><b>Tipo:</td><td>"+data[1]+"</b></td></tr>";
					tabla += "<tr>";
					tabla += "			<td colspan='2'>";
					
					
					
/***INICIO MASCARA DE DATOS LENTES DE CONTACTO***/
					
tabla +='<table>';
tabla +='	<tr>';
tabla +='        <td>';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th>A.V.</th>';
tabla +='                    <th>CON RX</th>';
tabla +='                    <th>SIN RX</th>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="od_crx" name="od_crx" size="12px" placeholder="20/40"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="od_srx" name="od_srx" size="12px" placeholder="20/70"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oi_crx" name="oi_crx" size="12px" placeholder="20/40"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oi_srx" name="oi_srx" size="12px" placeholder="20/70"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">A.O.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="ao_crx" name="ao_crx" size="12px" placeholder="20/30"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="ao_srx" name="ao_srx" size="12px" placeholder="20/40"/></td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='        </td>';
tabla +='        <td valign="top">';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4" align="center">Autorefract&oacute;metro</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_esfera" name="oda_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_cilindro" name="oda_cilindro" size="7px" placeholder="-0.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oda_eje" name="oda_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_esfera" name="oia_esfera" size="7px" placeholder="-1.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_cilindro" name="oia_cilindro" size="7px" placeholder="-0.50"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="oia_eje" name="oia_eje" size="7px" placeholder="170"/></td>';
tabla +='                </tr>';                    
tabla +='            </table>';
tabla +='        </td>';                 			 		 
tabla +='    </tr>';
tabla +='    <tr>';
tabla +='    	<td colspan="2"><br /></td>';
tabla +='    </tr>';
tabla +='	<tr>';
tabla +='    	<td>';
tabla +='            <table class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4" align="center">RX Anterior</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_esfera" name="rxad_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_cilindro" name="rxad_cilindro" size="7px" placeholder="-0.75"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxad_eje" name="rxad_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td  bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_esfera" name="rxai_esfera" size="7px" placeholder="-1.00"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_cilindro" name="rxai_cilindro" size="7px" placeholder="-0.75"/></td>';
tabla +='                    <td><input  type="text" '+deshabilitado+'  id="rxai_eje" name="rxai_eje" size="7px" placeholder="180"/></td>';
tabla +='                </tr>';                    
tabla +='            </table>';
tabla +='        </td>';
tabla +='    	<td>';
tabla +='            <table border="0" class="graduacion">';
tabla +='                <tr bgcolor="#666666" style="color:#FFFFFF;">';
tabla +='                    <th colspan="4">RX Actual</th>';
tabla +='                </tr>';
tabla +='                <tr bgcolor="#CCCCCC" style="color:#009;">';
tabla +='                    <td>&nbsp;</td>';
tabla +='                    <td align="center"><b>Esfera</b></td>';
tabla +='                    <td align="center"><b>Cilindro</b></td>';
tabla +='                    <td align="center"><b>Eje</b></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.D.</td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEsferaDer" name="stEsferaDer" value=""+esfeDer+"" placeholder="-1.00"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stCilindroDer" name="stCilindroDer" value=""+cilDer+"" placeholder="-0.75"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEjeDer" name="stEjeDer" value=""+ejeDer+"" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='                <tr>';
tabla +='                    <td bgcolor="#CCCCCC" style="color:#009;">O.I.</td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEsferaIzq" name="stEsferaIzq" value=""+esfeIzq+"" placeholder="-1.00"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stCilindroIzq" name="stCilindroIzq" value=""+cilIzq+"" placeholder="-0.75"/></td>';
tabla +='                    <td><input type="text" size="7px" '+deshabilitado+' id="stEjeIzq" name="stEjeIzq" value=""+ejeIzq+"" placeholder="180"/></td>';
tabla +='                </tr>';
tabla +='            </table>';
tabla +='		</td>';
tabla +='	</tr>';
tabla +='    <tr>';
tabla +='    	<td colspan="2"><br /></td>';
tabla +='    </tr>';
tabla +='</table>';
					
					/***FIN    MASCARA DE DATOS LENTES DE CONTACTO***/
					
					
					
					tabla +="			</td>";
					tabla += "		</tr>";
					tabla += "<tr><td><b>Observaciones:</b></td><td><textarea placeholder='Ingresa las observaciones correspondientes al pedido' rows='4' cols='50' "+deshabilitado+" id='stObservaciones' name='stObservaciones'>"+observaciones+"</textarea></td></tr>"
					tabla += "<tr><td colspan='2' align='center'><br><br><input type='button' "+deshabilitado+" value='"+nomBoton+"' onclick='JavaScript:enviar();' id='boton'/></td></tr></table>";
					$('#contenedorPrincipal').html(tabla);
					if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
					{
						var lcid = $('#lcid').val();
						var tipoid = $('#tipoid').val();
						$('#idLc').val(lcid);
						$('#idTipo').val(tipoid);
					}
				}
			});
		}
	});
	
});


function enviar()
{
	if(confirm("Estas seguro de querer guardar los cambios?"))
	{
		$('#boton').attr('disabled',true);
		$('#accion').val('enviar');
		$.ajax({
			type:'POST',
			url:url,
			data:$('#datosComple').serialize(),
			success:function(data)
			{
				alert(data.trim());
				window.opener.location.reload();
				window.close();	
			}
		});
	}
}


function getModelos(id){
	$.ajax({
	  type: "POST",
	  url: url,
	  data: {accion:"getModelos",idArmazon:id},
	  success: function(data)
	  {
			$('#contenedorModelo').html(data);
			if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
			{
				var modeloid = $('#modeloid').val();
				$('#idModelo').attr("onchange","JavaScript:getColores("+modeloid+")");
				$('#idModelo').change();
				$('#idModelo').val(modeloid);
			}
	  }
	});
}

function getColores(id){
	$.ajax({
	  type: "POST",
	  url: url,
	  data: {accion:"getColores",idModelo:id},
	  success: function(data)
	  {
		  	$('#contenedorColor').html(data);
		  	if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
			{
				var colorid = $('#colorid').val();
				$('#idColor').change();
				$('#idColor').val(colorid);
			}
	  }
	});
}

function getMascaraDeDatos()
{
	var deshabilitado = $('#deshabilitado').val();
	var accion = "getMascaraDeDatos";
	
	var datos = {
		"deshabilitado" : deshabilitado,
		"accion" : accion
	};
	
	$.ajax({
	  type: "POST",
	  url: url,
	  data: datos,
	  success: function(data)
	  {
		  	if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
			{
				$('#contenedorTratamientos').html($('#tratamientos').html());
				var materialid = $('#materialid').val();
				var lenteid = $('#lenteid').val();
				$('#idMaterial').val(materialid);
				$('#idLente').val(lenteid);	
				if($('#deshabilitado').val() == 'disabled')
					$('input,select').attr('disabled',true);
			}
			else
			{
				$('#contenedorTratamientos').html(data[2]);
			}
	  }
	});
	
}

function getMas(){
	$('#accion').val('enviar');
	$.ajax({
	  type: "POST",
	  url: url,
	  data: {accion:"getMas"},
	  success: function(data)
	  {
			data = data.split(",,,");
			$('#contenedorMaterial').html(data[0]);
			$('#contenedorLente').html(data[1]);
		  	if($('#idPedidoOpticaPrevio').val() != 0 && $('#i_incompleto').val() != 1)
			{
				$('#contenedorTratamientos').html($('#tratamientos').html());
				var materialid = $('#materialid').val();
				var lenteid = $('#lenteid').val();
				$('#idMaterial').val(materialid);
				$('#idLente').val(lenteid);	
				if($('#deshabilitado').val() == 'disabled')
					$('input,select').attr('disabled',true);
			}
			else
			{
				$('#contenedorTratamientos').html(data[2]);
			}
	  }
	});
}

$(document).ready(function(e) {
	var idPedidoOpticaPrevio = $('#idPedidoOpticaPrevio').val();
	var i_incompleto = $('#i_incompleto').val();
	console.log("idPedidoOpticaPrevio : " + idPedidoOpticaPrevio);
	console.log("i_incompleto : " + i_incompleto);
    if(idPedidoOpticaPrevio != 0 && i_incompleto != 1)
	{
		if($('#armazonid').val() != 0)
		{
			$('#selectLente').val(1);
		}
		else
		{
			$('#selectLente').val(0);
		}
		$('#selectLente').change();
	}
	
	$('#ocupacion').focus();
	
});

</script>

</head>
<body>
	<?php echo $camposOcultosAuxiliares; ?>
	<div style="background-color:#B9C9FF; width:430px; height:30px" align="center"><h1 style="color:#0047C3; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif">Registro de Pedido</h1></div>
	<form name="datosComple" id="datosComple" action="JavaScript:enviar();" method="post">
	
    <input type="hidden" id="deshabilitado" value="<?=$disabled?>"/>
    <input type="hidden" id="accion" name="accion"/>
    <input type="hidden" id="idOperador" name="idOperador" value="<?=$idUsuarioWeb?>" />
    <input type="hidden" id="idPedidoOpticaPrevio" name="idPedidoOpticaPrevio" value="<?=$idPedidoOpticaPrevio?>" />
    <input type="hidden" id="idPaquete" name="idPaquete" value="<?=$idPaquete?>" />
    <input type="hidden" id="i_incompleto" name="i_incompleto" value="<?=$i_incompleto?>"/>
    
    <?php
		if($do == 1) echo '<input type="hidden" value="1" id="doPedidoProveedor" name="doPedidoProveedor" />';
	?>
        
	<table border="0">
    	<tr>
        	<td>
            	<!--INICIO CABECERA-->
            	<table border="0">
                    <tr>
                        <td><b>No. Cotizaci&oacute;n:</b></td>
                        <td>
                            <input <?=$disabled?> type="text" id='idCotizacion' name='idCotizacion' readonly  value='<?=$_GET['idCotizacion']?>'/>		
                        </td>
                    </tr>
                    <tr>
                        <td><b>Producto:</b></td>
                        <td>
                            <input <?=$disabled?> type="text" id='stNombrePaquete' name='stNombrePaquete' readonly size="50"  value='<?=$_GET['stPaquete']?>'/>		
                        </td>
                    </tr>
                    <tr>
                        <td><b>Proveedor:</b></td>
                        <td><select disabled id='id_Cliente' name='id_Cliente'>
                                <option value='99999'>Selecciona</option>
                                <?=Proveedor($porveedor);?>
                                  <?
                                  /*
                                   
                                      $queryselec = "
                                        select id_ClienteOptica, st_Cliente 
                                        from tbl_ProveedorOptica 
                                        where id_Status = 1
                                        order by 1";
                                   $rqueryselec = mssql_query($queryselec);
                                 while ($rowdata = mssql_fetch_array($rqueryselec)) {
                                    if ($rowdata['id_ClienteOptica'] == $row1['id_Proveedor']){ ?>
                                         <option selected value='<?=$rowdata['id_ClienteOptica']?>'><?=htmlentities($rowdata['st_Cliente'])?></option>
                                        
                                    <?php }else { ?>
                                        <option value='<?=$rowdata['id_ClienteOptica']?>'><?=htmlentities($rowdata['st_Cliente'])?></option>			  				
                                    <?php }
                                   
                                  }*/ 
                                  ?>   
                            </select> 		
                        </td>
                    </tr>
                    <tr>
                        <td><b>Sobre:</b></td>
                        <td>
                            <input readonly type='text' id='st_Sobre' name='st_Sobre' size="50" placeholder='Ingresa el no. de sobre' value='<?=$row1['st_Sobre']?>'/>		
                        </td>
                    </tr>
                    <tr>
                        <td><b>Ocupacion:</b></td>
                        <td><input type="text" id="ocupacion" size="50" name="ocupacion" placeholder="Ingresa la ocupaci&oacute;n del paciente" /></td>
                    </tr>
                    <tr>
                        <td><b>Padecimiento:</b></td>
                        <td><input type="text" placeholder="Ingresa el padecimiento del paciente" id="padecimiento" size="50" name="padecimiento" /></td>
                    </tr> 
                    
                    <tr>
                        <td><b>Filtrar:</b></td>
                        <td>
                            <select <?=$disabled?> id="selectLente" name="selectLente">
                                <option disabled value="" selected>--Selecciona--</option>
                                <option value="1">Por Armaz&oacute;n</option>
                                <option value="0">Por Lente de Contacto</option>
                            </select>
                        </td>
                    </tr>
        	  	</table>
            </td>
        	<td>&nbsp;</td>
            <!--FIN CABECERA-->
        <tr>
        	<td colspan="2">
            	<div id="contenedorPrincipal" style="background-color:#E8EDFF; width:430px; height:620px;">
                Esperando selecci&oacute;n...
                </div>
           </td>
        </tr>
        <!--<tr>
        	<td>
            	<table class='graduacion'>
                	<tr bgcolor='#666666' style='color:#FFFFFF;'>
                    	<td>A.V.</td>
                        <td>CON RX</td>
                        <td>SIN RX</td>
                    </tr>
                    <tr>
                    	<td bgcolor='#CCCCCC' style='color:#009;'>O. D.</td>
                        <td><input type="text" id="od_crx" name="od_crx" size="20px"/></td>
                        <td><input type="text" id="od_srx" name="od_srx" size="20px"/></td>
                    </tr>
                    <tr>
                    	<td bgcolor='#CCCCCC' style='color:#009;'>O. I.</td>
                        <td><input type="text" id="oi_crx" name="oi_crx" size="20px"/></td>
                        <td><input type="text" id="oi_srx" name="oi_srx" size="20px"/></td>
                    </tr>
                    <tr>
                    	<td bgcolor='#CCCCCC' style='color:#009;'>A. O.</td>
                        <td><input type="text" id="ao_crx" name="ao_crx" size="20px"/></td>
                        <td><input type="text" id="ao_srx" name="ao_srx" size="20px"/></td>
                    </tr>
                </table>
            </td>
            <td>
            	<table class='graduacion'>
                	<tr bgcolor='#666666' style='color:#FFFFFF;'>
                    	<td colspan="4" align="center">Autorefactamiento</td>
                    </tr>
                    <tr bgcolor='#CCCCCC' style='color:#009;'>
                    	<td>&nbsp;</td>
                    	<td align='center'><b>Esfera</b></td>
						<td align='center'><b>Cilindro</b></td>
						<td align='center'><b>Eje</b></td>
                    </tr>
                    <tr>
                    	<td  bgcolor='#CCCCCC' style='color:#009;'>O. D.</td>
                    	<td><input type="text" id="oda_esfera" name="oda_esfera" size="7px"/></td>
                        <td><input type="text" id="oda_cilindro" name="oda_cilindro" size="7px"/></td>
                        <td><input type="text" id="oda_eje" name="oda_eje" size="7px"/></td>
                    </tr> 
                    <tr>
                    	<td  bgcolor='#CCCCCC' style='color:#009;'>O. I.</td>
                    	<td><input type="text" id="oia_esfera" name="oia_esfera" size="7px"/></td>
                        <td><input type="text" id="oia_cilindro" name="oia_cilindro" size="7px"/></td>
                        <td><input type="text" id="oia_eje" name="oia_eje" size="7px"/></td>
                    </tr>                    
                </table>
            </td>                 			 		 
        </tr>
        <tr>
        	<td>
            	<table class='graduacion'>
                	<tr bgcolor='#666666' style='color:#FFFFFF;'>
                    	<td colspan="4" align="center">RX Anterior</td>
                    </tr>
                    <tr bgcolor='#CCCCCC' style='color:#009;'>
                    	<td>&nbsp;</td>
                    	<td align='center'><b>Esfera</b></td>
						<td align='center'><b>Cilindro</b></td>
						<td align='center'><b>Eje</b></td>
                    </tr>
                    <tr>
                    	<td  bgcolor='#CCCCCC' style='color:#009;'>O. D.</td>
                    	<td><input type="text" id="rxad_esfera" name="rxad_esfera" size="7px"/></td>
                        <td><input type="text" id="rxad_cilindro" name="rxad_cilindro" size="7px"/></td>
                        <td><input type="text" id="rxad_eje" name="rxad_eje" size="7px"/></td>
                    </tr> 
                    <tr>
                    	<td  bgcolor='#CCCCCC' style='color:#009;'>O. I.</td>
                    	<td><input type="text" id="rxai_esfera" name="rxai_esfera" size="7px"/></td>
                        <td><input type="text" id="rxai_cilindro" name="rxai_cilindro" size="7px"/></td>
                        <td><input type="text" id="rxai_eje" name="rxai_eje" size="7px"/></td>
                    </tr>                    
                </table>
            </td>
            <td>
            	<table>
                	<tr>
                        <td rowspan="2" valign="top">Tinte:</td>
                        <td><input type="text" name="tinte" id="tinte"/></td>
                    </tr>
                    <tr>
                        <td align="center">
                            &nbsp;1<input type="radio" name="tipoTinte[]" id="tipoTinte[]" />
                            &nbsp;2<input type="radio" name="tipoTinte[]" id="tipoTinte[]" />
                            &nbsp;3<input type="radio" name="tipoTinte[]" id="tipoTinte[]" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            	<table>
                	<tr>
                    	<th>Tratamiento</th>
                    	<th align="left"><? // echo Tratamiento(0); ?></th>
                    </tr>
                                     
                </table>
            </td>
        </tr>-->
	</table>
	
	</form>
</body>	
</html>
<?
function Tratamiento($id_PedidoPrevio){
	$salida="";
	if($id_PedidoPrevio==0){
		$query="SELECT id_TratamientoOptica,st_Tratamiento, 0 AS Activo FROM cat_TratamientosOptica WHERE id_Status = 1";
	}else{
		$query="SELECT id_TratamientoOptica,st_Tratamiento,(CASE WHEN id_PedidoOpticaPrevio IS NULL THEN 0 ELSE 1 END)AS Activo FROM  cat_TratamientosOptica AS t1 
LEFT JOIN tbl_TratamientosPedidoPrevio AS t2 ON t1.id_TratamientoOptica=t2.id_Tratamiento AND id_PedidoOpticaPrevio=".$id_PedidoPrevio." WHERE id_Status = 1 ";	
	}
	$eje=mssql_query($query);
	if($eje){
		while($vec=mssql_fetch_array($eje)){
			if($vec["Activo"]==0){ $che=""; } else{ $che="checked"; }
			$salida.="&nbsp;<input type='checkbox' id='Mat_Tra[]' name='Mat_Tra[]' value=".$vec["id_TratamientoOptica"]." ".$che." />&nbsp;".$vec["st_Tratamiento"]."<br>";
		}
	}
	echo $salida;
}

function Proveedor($id_PedidoPrevio){
	$salida="";	
	$queryselec = "SELECT id_ClienteOptica, st_Cliente FROM tbl_ProveedorOptica WHERE id_Status = 1 ORDER BY 1";
   	$rqueryselec = mssql_query($queryselec);
	if($id_PedidoPrevio == 0){		
		while ($rowdata = mssql_fetch_array($rqueryselec)) {
			if ($rowdata['id_ClienteOptica'] == 9){ 
				$salida.="<option selected value='".$rowdata['id_ClienteOptica']."'>".htmlentities($rowdata['st_Cliente'])."</option>";
			}else { 
				$salida.="<option value='".$rowdata['id_ClienteOptica']."'>".htmlentities($rowdata['st_Cliente'])."</option>";		  				
			}   
		}
	}else{
		while ($rowdata = mssql_fetch_array($rqueryselec)) {
			if ($rowdata['id_ClienteOptica'] == $id_PedidoPrevio){ 
				$salida.="<option selected value='".$rowdata['id_ClienteOptica']."'>".htmlentities($rowdata['st_Cliente'])."</option>";
			}else { 
				$salida.="<option value='".$rowdata['id_ClienteOptica']."'>".htmlentities($rowdata['st_Cliente'])."</option>";		  				
			}   
		}
	}
	return $salida;
}

?>