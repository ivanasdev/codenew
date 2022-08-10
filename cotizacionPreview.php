<?php  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
session_start();

$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index."class.Tracking.php");
$objTracking = new Tracking(7,72,"PRESUPUESTO OPTICA - Imprimir Cotización");
///////////////////////////////////////////////////////	

include ("class.PresupuestoOptica.php");
$objPresupuestoOptica = new PresupuestoOptica();



if( $_SESSION['id_TipoUsuario'] != 14 ){
	echo"
		<script>
			alert('Sesion expirada favor de cerrar sesion y volver a ingresar al modulo!');
			parent.location.href='logout.php';
		</script>
	";
}

$id_Sucursal=$idsucursal;
$id_Sucursal = $_SESSION['id_Sucursal'];
$id_UsuarioWeb = $_GET['id_UsuarioWeb'];

$idUsuarioWeb = $_GET['id_UsuarioWeb'];
require ("porcentajeAplicado.php");



$queryCantProds = "SELECT MAX(id_PaqueteOptica)+1 AS cant FROM tbl_PaqueteOptica";
$resCantProds = mssql_query($queryCantProds);
$rowCantProds = mssql_fetch_object($resCantProds);

$queryreest = "(";

for($i=0;$i<$rowCantProds->cant;$i++){
	if(isset($_GET['Producto_'.$i])){
		$queryreest .= $i.",";
	}
}

$queryreest .= ")";
$queryreest = str_replace(",)", ")", $queryreest);

$queryPaqCot = "SELECT id_PaqueteOptica, st_Nombre, st_Codigo, st_PrecioMiembro, st_PrecioNoMiembro, id_Operador, dt_FechaRegistro, i_Activo, st_Descripcion FROM
				tbl_PaqueteOptica WHERE id_PaqueteOptica IN ".$queryreest;

$resPaqCot = mssql_query($queryPaqCot);

//Metemos los datos de la cotizacion

$nuevofolio = getFolio();

$queryInsertCot = "INSERT INTO tbl_OpticaCotizacionPrevia(id_UsuarioWeb,dt_FechaRegistro,id_Operador,st_FolioCotizacion) VALUES(".$id_UsuarioWeb.",
				   GETDATE(),".$_SESSION['id_Operador'].",'".$nuevofolio."')";
$resInsertCot = mssql_query($queryInsertCot);

if($resInsertCot){

	$queryUltCot = "SELECT TOP(1) id_CotizacionOpticaP AS id FROM tbl_OpticaCotizacionPrevia ORDER BY id_CotizacionOpticaP DESC";
	$resUltCot = mssql_query($queryUltCot);
	$rowUltCot = mssql_fetch_object($resUltCot);
	$id_Cot = $rowUltCot->id;

	$queryPaqCot2 = "SELECT id_PaqueteOptica,st_PrecioMiembro,st_PrecioNoMiembro FROM tbl_PaqueteOptica WHERE id_PaqueteOptica IN ".$queryreest;
	$resPaqCot2 = mssql_query($queryPaqCot2);

	while($rowPaqCot2 = mssql_fetch_object($resPaqCot2)){
		$queryInsertCot2 = "INSERT INTO tbl_PaquetesOpticaCotizados(id_CotizacionOpticaP,id_PaqueteOptica,st_PrecioMiembro,st_PrecioNoMiembro,dt_FechaRegistro) VALUES
						(".$id_Cot.",".$rowPaqCot2->id_PaqueteOptica.",'".$rowPaqCot2->st_PrecioMiembro."','".$rowPaqCot2->st_PrecioNoMiembro."',GETDATE())";
		$resInsertCot2 = mssql_query($queryInsertCot2);
	}
}

//Obtiene el nombre del Paciente
$query0 = "SELECT UPPER(st_Nombre+' '+st_ApellidoPaterno+' '+st_ApellidoMaterno) as nombrePaciente 
FROM tbl_UsuariosWeb WHERE id_UsuarioWeb = '".$id_UsuarioWeb."'";
$rquery0 = mssql_query($query0);
$arrayQuery0 = mssql_fetch_array($rquery0);
$nombrePaciente = $arrayQuery0["nombrePaciente"];


?>
<html>
	<head>
		<title>Cotizacion</title>
	</head>
	<body onLoad="window.print();">
		<table  width="95%" align="left">
		  
          <?=$objPresupuestoOptica->getHeader($id_Sucursal)?>
          
          <tr><td colspan="3"><br></td></tr>
		  <tr> 
          	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>ATENDIO:</b> <?=strtoupper($_SESSION['username'])?></font></td>
          	<td align="right" colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>FECHA:</b> <? echo date("d-m-Y H:i:s");?></font></td>
    	  </tr>
          
<tr>
<td colspan="3"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>FOLIO COTIZACI&Oacute;N:</b> <?=$nuevofolio?></font></td>
</tr>

<tr>
<td colspan="3"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>PACIENTE:</b> <?=$nombrePaciente?></font></td>
</tr>

          <tr><td colspan="3"><br></td></tr>
		  <tr>
		  	<td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>PAQUETE</b></font></td>
            <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>DESCRIPCI&Oacute;N</b></font></td>
		  	<td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PRECIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
		  </tr>
<?
	$totalM = 0;
	$totalN = 0;

	while($rowPaqCot = mssql_fetch_object($resPaqCot)){
		$totalM = $totalM + ( ($rowPaqCot->st_PrecioMiembro * $i_Porcentaje) + $rowPaqCot->st_PrecioMiembro);
		$totalN = $totalN + ( ($rowPaqCot->st_PrecioNoMiembro * $i_Porcentaje) + $rowPaqCot->st_PrecioNoMiembro);
?>
		<tr>
		  	<td align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><?=$rowPaqCot->st_Nombre?></b></font></td>
            <td align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$rowPaqCot->st_Descripcion?></font></td>
		  	<td align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$ <? echo number_format((($rowPaqCot->st_PrecioMiembro * $i_Porcentaje) + $rowPaqCot->st_PrecioMiembro),2,'.',''); ?></font></td>
		 </tr>
<?
	}
?>
		<tr>
			<td colspan="3"><hr></td>
		</tr>
		<tr>
        	<td>&nbsp;</td>
		  	<td align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>TOTAL</b></font></td>
			<td align="right"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$ <? echo number_format( $totalM,2,'.','');?></font></b></td>
	  	</tr>
        <tr><td colspan="3"><br><br></td></tr>
<?
	$id_Leyenda = rand(1,12);

	$queryLeyendas =  "SELECT st_Leyenda, st_Nombre FROM cat_LeyendasPresupuestos WHERE id_Leyenda = ".$id_Leyenda;
	$resLeyendas = mssql_query($queryLeyendas);
	$rowLeyendas = mssql_fetch_object($resLeyendas);
?>

			<tr><td align="center" colspan="3"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><?=$rowLeyendas->st_Nombre?></b></font><br></td></tr>
			<tr><td align="center" colspan="3"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>(ESTE PRESUPUESTO NO ES V&Aacute;LIDO COMO COMPROBANTE DE PAGO) EXIJA SU TICKET AL REALIZAR LA COMPRA</strong></font></td></tr>
			<tr><td colspan="3"><br></td></tr>
			<tr>
				<td colspan="3" align="justify">
					<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
					Este presupuesto tiene validez durante los siguientes 15 d&iacute;as a partir de su expedici&oacute;n.<br>
					Presupuesto sujeto a cambio de precio por cambio de materiales, tratamientos adicionales o servicios extras.<br>
					Fecha de entrega de 5 a 9 d&iacute;as h&aacute;biles a partir de la fecha de compra.</td>
			</tr>
			 <tr>
				<td colspan="3">&nbsp;
					
				</td>
			</tr>
			<?=$objPresupuestoOptica->getFooter()?>
		</table>
        

		<?=$objPresupuestoOptica->getInfografia($idUsuarioWeb)?>        
        
	</body>
</html>
<?
	function getLeyenda($ley){
		$leyenda = "";
		switch($ley){
			case 1:
				$leyenda = "Incorpora frutas y verduras a tu dieta habitual. Son una importante fuente de

							antioxidantes que el cuerpo no genera por sí solo y necesita de su aporte en 

							alimentos para mantener unos valores básicos. Éstos ayudan a la prevención de 

							enfermedades como la Degeneración Macular o problemas vasculares como en 

							diabetes e hipertensión";
				break;
			case 2:
				$leyenda = "Evita exponer tus ojos al sol sin el debido filtro de protección UV. Utiliza gafas con

							filtros adecuados para estas radiaciones dañinas. El exceso de exposición al sol, 

							puede dañar la retina y acelerar la aparición de ciertas patologías oculares como

							cataratas, ya que los tejidos oculares tienen memoria y su efecto se acumula";
				break;
			case 3:
				$leyenda = "Cada hora de trabajo de lectura prolongada o de pasar frente a la pantalla del

							ordenador, hacer un descanso, levantarse de la silla y andar, mientras movemos 

							nuestros músculos y descansamos nuestros ojos. Cuando notes el cansancio en 

							los ojos, realizar parpadeos continuos durante algunos minutos para que estos se 

							humedezcan. Intenta limitar la cantidad de horas en el ordenador.";
				break;
			case 4:
				$leyenda = "Cuando te encuentres en situaciones como ambientes secos con calefacción, 

							aire acondicionado, en trabajos continuados con ordenadores o cuando vayas 

							a mantenerte despierto durante un largo período de tiempo, consulta a tu 

							optometrista acerca del uso de gotas humectantes. Ya que con ayuda de estas 

							gotas se produce un alivio ocular por la humectación de la superficie del ojo algo 

							seca por estas actividades";
				break;
			case 5:
				$leyenda = "A partir de esta edad, acude a revisar tu salud ocular, al menos una vez al año 

							para controlar tu presión ocular, o antes, si existe en tu familia antecedentes de 

							glaucoma. También para controlar el estado de tu retina, principalmente si eres 

							diabético o hipertenso, ya que una buena prevención puede evitar daños visuales 

							irreversibles";
				break;
			case 6:
				$leyenda = "Una de las causas del fracaso escolar son los déficits visuales no tratados o 

							disfunciones visuales no diagnosticadas, por lo que revisiones anuales en niños 

							puede ayudar a superar estas dificultades escolares. Además en la infancia es la 

							época de detectar disfunciones visuales que más tarde tendrán respuestas menos 

							satisfactorias a los posibles tratamientos.";
				break;
			case 7:
				$leyenda = "Si tienes una miopía alta y estás embarazada o piensas quedarte, sería 

							recomendable someterte a un examen visual con especial atención al fondo de 

							ojo, ya que en estos casos el ojo está más predispuesto a desprendimientos de 

							retina, de vítreo etc.";
				break;
			case 8:
				$leyenda = "En la práctica de algunos deportes es recomendable el uso de gafas de protección 

							ante agresiones químicas y físicas que pueden recibir nuestros ojos (natación,

							esquí, casa, squash…).fábricas o jardinería para evitar otro tipo de lesiones. Se

							recomienda el uso de gafas con lentes orgánicas (policarbonato) para que no se

							produzcan roturas en caso de traumatismos o entrada de cuerpos extraños";
				break;
			case 9:
				$leyenda = "Aunque pienses que es leve, cuando se produzca alguna lesión ya sea física o

							por algún componente químico, es muy recomendable acudir a una revisión del 

							responsable de la visión (ya sea optometrista, que en caso de lesión lo derivará al 

							oftalmólogo o acudir al oftalmólogo directamente) para que evalúe la lesión y haga 

							un diagnóstico correcto";
				break;
			case 10:
				$leyenda = "La higiene ocular es algo muy a tener en cuenta para evitar riesgos futuros. Una 

							limpieza periódica de párpados y anexos oculares es imprescindible para una 

							buena salud ocular, sobre todo en usuarios de lentillas ya que va a evitar futuras 

							complicaciones o infecciones oculares que pueden llegar a ser graves";
				break;
			case 11:
				$leyenda = "La falta de luz o su mala colocación provoca que el trabajador tenga que forzar la 

							vista, generando fatiga ocular y disminuyendo, por tanto, su productividad. Esta 

							afirmación la sacamos de un estudio que indica que la buena iluminación aumenta 

							en hasta un 20% la productividad y reduce las bajas laborales.";
				break;
			case 12:
				$leyenda = "Si tienes ojo rojo, picor, escozor o sufres cualquier tipo de traumatismo ocular, no 

							utilices colirios o fármacos sin la supervisión y diagnóstico correspondiente, ya que 

							el uso de un mal tratamiento o la no finalización del mismo puede ser aún más 

							perjudicial, pudiendo crear resistencias al fármaco y repetición de las infecciones 

							por un tratamiento incompleto..";
				break;
			default:
				$leyenda = "";
				break;
		}

		return $leyenda;
	}

	function getFolio(){

		$validado = false;
		$folionuevo = "";

		do{
			$s0 = rand(0,9);
			$s1 = rand(0,9);
			$s2 = rand(0,9);
			$s3 = rand(0,9);
			$s4 = rand(0,9);
			$s5 = rand(0,9);
			$s6 = rand(0,9);
			$s7 = rand(0,9);

			$folionuevo = "ML-".$s0.$s1.$s2.$s3.$s4.$s5.$s6.$s7;

			$queryBusca = "SELECT id_CotizacionOpticaP FROM tbl_OpticaCotizacionPrevia WHERE st_FolioCotizacion = '".$folionuevo."' ";
			$resBusca = mssql_query($queryBusca);

			if(mssql_num_rows($resBusca) > 0){
				$validado = true;
			}
			else{
				$validado = false;
			}
		}
		while($validado);

		return $folionuevo;
	}
?>