<?php
date_default_timezone_set('America/Mexico_City');

class PresupuestoOptica{
	
	public $idSucursal = 0;
		
	function getHeader($idSucursal){

		$this->idSucursal = $idSucursal;
		$queryselectEv = "
		SELECT 
			t1.*, 
			t2.st_NombreLimpio, 
			t2.st_TipoSociedad,
			t2.id_Pagadoras
		FROM cat_SucursalClinica t1 
		LEFT JOIN cat_FZPagadoras t2 ON t1.st_RFC = t2.st_RFC
		WHERE t1.id_SucursalClinica = '".$idSucursal."'";
		$rqueryselectEv = mssql_query($queryselectEv);
		$rowdataEv = mssql_fetch_array($rqueryselectEv);	
		$Calle = $rowdataEv['st_Domicilio'];
		$colonia = $rowdataEv['st_Colonia'];
		$rfc = $rowdataEv['st_RFC'];
		$this->idPagadora = $rowdataEv['id_Pagadoras'];
		$telefonos = $rowdataEv['st_Telefonos'];
		$telefonos = '4165-5050';
		$stNombreLimpio = $rowdataEv['st_NombreLimpio'];
		$stTipoSociedad = $rowdataEv['st_TipoSociedad'];

		switch($this->idSucursal){
				case 116:
				case 143:
				case 170:
					$respuestaTitulo = '
					<tr> 
					<td colspan="4" align="center">
					<font size="1" face="Verdana, Arial, Helvetica, sans-serif">ANTENA SOCIAL</font>
					</td>
					</tr>
					';
					break;
				case 159:
				case 160:
					$respuestaTitulo = '
					<tr> 
					<td colspan="4" align="center">
					<font size="1" face="Verdana, Arial, Helvetica, sans-serif">SOY TU SALUD</font>
					</td>
					</tr>';
					break;
				default:
					$respuestaTitulo = '
					<tr> 
					<td colspan="3" align="center">
					<font size="1" face="Verdana, Arial, Helvetica, sans-serif">'.$stNombreLimpio.' '.$stTipoSociedad.'</font>
					</td>
					</tr>';
					
		}

		$respuesta = $respuestaTitulo.'	
			  <tr> 
			    <td colspan="3" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
			      '.$Calle.', Col. '.$colonia.'
			      </font></td>
			  </tr>
			  <tr> 
			    <td colspan="3" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">R.F.C 
			      '.$rfc.'
			      </font></td>
			  </tr>
			  <tr> 
			    <td colspan="3" align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Telefono: 
			      '.$telefonos.'
			      </font></td>
			  </tr>';

		return $respuesta;
		
	}

	function getFooter(){

		switch($this->idPagadora){
			case 1:
				$stPaginaWeb = "www.proveedoragi.com.mx";
				$stCorreoSugerencias = "sugerencias@proveedoragi.com.mx";
				$stCorreoFacturacion = "facturacion@proveedoragi.com.mx";
				$stAvisoPrivacidad = "http://proveedoragi.com.mx/docu<br>ments/AvisoPrivacidad.pdf";
				break;
			case 4:
				$stPaginaWeb = "www.metrohealth.com.mx";
				$stCorreoSugerencias = "sugerencias@metrohealth.com.mx";
				$stCorreoFacturacion = "facturacion@metrohealth.com.mx";
				$stAvisoPrivacidad = "http://metrohealth.com.mx/docu<br>ments/AvisoPrivacidad.pdf";
				break;
			case 8:
				$stPaginaWeb = "www.soytusalud.mx";
				$stCorreoSugerencias = "sugerencias@soytusalud.mx";
				$stCorreoFacturacion = "facturacion@soytusalud.mx";
				$stAvisoPrivacidad = "http://soytusalud.mx/docu<br>ments/AvisoPrivacidad.pdf";
				break;				
			default:
				$stPaginaWeb = "www.medicallife.com.mx";
				$stCorreoSugerencias = "sugerencias@medicallife.com.mx";
				$stCorreoFacturacion = "facturacion@medicallife.com.mx";
				$stAvisoPrivacidad = "http://medicallife.com.mx/docu<br>ments/AvisoPrivacidad.pdf";
		}


		switch($this->idSucursal){
				case 116:
				case 143:
				case 170:
				case 159:
				case 160:
					$respuesta = '';
					break;
				default:
					$respuesta = '
					<tr>
					<td colspan="3" align="center">
						<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
						Quejas y sugerencias al tel&eacute;fono 4165-5050 o al correo: '.$stCorreoSugerencias.'
						</font>
					</td>
					</tr>';
		}

		return $respuesta;
		
	}

	function getInfografia($idUsuarioWeb){
		
		switch($this->idSucursal){
				case 116:
				case 143:
				case 170:
					$respuesta = '';
					break;
				default:
				
					$query0 = "SELECT count(*) as conteoDiag 
					FROM tbl_OPTGraduacionDiagnosticos WHERE id_UsuarioWeb = '".$idUsuarioWeb."'";
					$rquery0 = mssql_query($query0);
					$arrayQuery0 = mssql_fetch_array($rquery0);
					$conteoDiag = $arrayQuery0["conteoDiag"];
				
					$faltantesDiag = 2-$conteoDiag;
					$infografias = array();
					
					//Si hay diagnosticos del paciente
					if( $conteoDiag > 0 ):
					
						$query0 = "SELECT id_Diagnostico FROM tbl_OPTGraduacionDiagnosticos 
						WHERE id_UsuarioWeb = '".$idUsuarioWeb."' ORDER BY id_Diagnostico";
						$rquery0 = mssql_query($query0);
						$listado = "";
						for($i=0; $i<$conteoDiag; $i++){
							
							$arrayQuery0 = mssql_fetch_array($rquery0);					
							if(trim($arrayQuery0["id_Diagnostico"]) != ""){
								$listado .= '
								<td align="center">
									<img src="../uploads/Optica/Cotizacion/'.$arrayQuery0["id_Diagnostico"].'.jpg" width="350px" heigth="450px">
								</td>';
								$infografias[] = $arrayQuery0["id_Diagnostico"];
							}
							
							
						}
						
					endif;
					
					//Si hay faltantes de diagnosticos por mostrar
					if( $faltantesDiag > 0 ):
					
						for($i=0; $i<$faltantesDiag; $i++){										
						
							$imagen = rand ( 1 , 5 );
							while( in_array($imagen, $infografias) ){
								$imagen = rand ( 1 , 5 );
							}				
							$infografias[] = $imagen;
											
							$arrayQuery0 = mssql_fetch_array($rquery0);									
							$listado .= '
							<td align="center">
								<img src="../uploads/Optica/Cotizacion/'.$imagen.'.jpg" width="350px" heigth="450px">
							</td>';				
						}
						
					endif;
					
								
					$respuesta = '
					<div style="min-height:10px; clear:both;"></div>
					<table width="100%">
					<tr>
						'.$listado.'
					</tr>
					</table>';
		}

		return $respuesta;
		
	}

	function mostrar($cadena){
		return utf8_encode(trim($cadena));
	}
	
	function poner($cadena){
		return utf8_decode(trim($cadena));
	}
	
	function mostrarFecha($cadena){
		
		if($cadena == '1900-01-01' || $cadena == NULL || trim($cadena) == "" )
			return "";
		else
			return utf8_decode( date('Y/m/d',strtotime($cadena)) );		
	}
}//Fin Class PresupuestoOptica
?>