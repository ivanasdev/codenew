<?php
$ruta2index = "../../../../";
include($ruta2index . 'dbConexion.php');
//session_start();
//$token = utf8_decode($_POST['token']);
//$idAreaInsumo = $_POST['idAreaInsumo'];
$idSucursal = 24;
/*VALIDAR PERMISOS INUSMOS */



$query="SELECT * FROM tbl_RelSucursalInsumosA WHERE id_Sucursal='".$idSucursal."'  ";
$resI=mssql_query($query);
$arrayinsumos=mssql_fetch_array($resI);

$a2=$arrayinsumos['st_InsumosA2'].",".$arrayinsumos['st_InsumosA3'].$arrayinsumos['st_InsumosA4'].$arrayinsumos['st_InsumosA5'].$arrayinsumos['st_InsumosA6'];
$areasInsumos=$arrayinsumos['st_AreasInsumos'];

$some=explode(",",$a2);

$someareas=explode(",",$areasInsumos);








//TMP TBL
$querytmp="
create table #insumostmp(
	id_Sucursal int,
	id_Insumo int,
	  id_AreaInsumo int
  )
";

$tmpres=mssql_query($querytmp);
if($tmpres){
	foreach($someareas as $index => $areas){



	echo "exito"."<br>";
	foreach($some as $iIndice=>$objCelda){
		$query1=" 
		INSERT INTO #insumostmp(id_Sucursal,id_Insumo,id_AreaInsumo) VALUES(".$idSucursal.", ".$objCelda.",".$areas.") "; 
		$resq=mssql_query($query1);
	
	}//END OF FOREACH	
}

		



}
$table="";

$query="
SELECT * FROM #insumostmp WHERE id_Sucursal=".$idSucursal."
";
$res=mssql_query($query);
while($arry=mssql_fetch_array($res)){
	$i++;
	$sucursal=$arry['id_Sucursal'];
	$id_Insumos=$arry['id_Insumo'];
	$stAreaInsumos=$arry['id_AreaInsumo'];
//$areas=explode(",",$stAreaInsumos);





	
$table.="
<table class='fancyTable' id='myTable01' cellpadding='0' cellspacing='0' width='100%'>
					<thead>
					<tr>
	                    <th>No</th>
						<th>Area de Insumo</th>
	                    <th width='300px'>Descripci&oacute;n</th>    
	                    <th>Cantidad</th>
						<th>Observaciones</th>
	                    <th>+</th>
	                </tr>
					</thead>
	    			<tbody>
				

";
$table.="
<tr>
<td align='center' style='background-color:#CCC;'>
' . $i . '
<input type='hidden' id='idInsumo_' . $i . '' name='idInsumo_' . $i . '' value='' . $idInsumo . '' />						
</td>
<td><center>' . $stAreaInsumos . '</center></td>					
<td>' . $stNombre . '</td>
																			
<td align='center'>
	<input type='text' size='4' id='cantidad_' . $i . '' name='cantidad_' . $i . '' class='numeroEntero' placeholder='0' />
</td>															
<td>
<center>
<textarea id='observaciones_' . $i . '' name='observaciones_' . $i . '' rows='2'></textarea>
</center>
</td>
<td align='center'>
	' . $botonSalvar . '
</td>
</tr>
";

}	












echo $table;

//echo $insumos."<br>";



//$query="INSERT INTO CATSOMETHING(id_Insumo) VALUES(".$a2.",".$a3."".$a4."".$a5.") 	 ";
/*
$arraya2=array();
array_push($arraya2,$a2);
foreach($arraya2 as $row => $value ){

		$queryI="INSERT INTO sometable(id_Sucursal, id_Insumo, id_AreaInsumo) VALUES (".$idSucursal.", '".$value."')  ";

	
}



foreach($arraya2 as $row => $value ){
	while($value){
		$queryI="INSERT INTO sometable(id_Sucursal, id_Insumo, id_AreaInsumo) VALUES (".$idSucursal.", '".$value."')  ";
	}
	
*/







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
