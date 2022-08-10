<?php
date_default_timezone_set('America/Mexico_City');

class Catalogos{
		
	public $conteoAreas = 0;

	//######################################################################################
	function obtieneSucursales($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_SucursalClinica NOT IN ('0','10','50','60','61','62','63','74','75','83') AND i_Activo = '1'
		AND id_TipoSucursal != '9'
		ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales
	
	//######################################################################################
	function obtieneSucursalesML($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_SucursalClinica NOT IN ('0','10','50','60','61','62','63','74','75','83') AND i_Activo = '1'
		AND id_TipoSucursal != '9' AND id_Cliente NOT IN ('3','9')
		ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		$resultado .= '<option value="10">(Sucursal de Prueba) CALL CENTER</option>';
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
	//######################################################################################
	function obtieneTodasSucursalesML($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_SucursalClinica NOT IN ('0','10','50','60','61','62','63','74','75','83')
		AND id_TipoSucursal != '9' AND id_Cliente NOT IN ('3','9')
		ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
		//######################################################################################
	function obtieneSucursalesMLCitasMedicas($id = ""){
		$query0 = "SELECT t2.id_SucursalClinica, UPPER(t2.st_Nombre) as nombreSucursal 
		FROM tbl_EventoLogCita t1
		INNER JOIN cat_SucursalClinica t2 ON t1.id_Sucursal = t2.id_SucursalClinica
		WHERE t2.id_SucursalClinica NOT IN ('0','10','50','60','61','62','63','74','75','83') AND t2.i_Activo = '1'
		AND t2.id_TipoSucursal != '9' AND t2.id_Cliente NOT IN ('3','9')
		GROUP BY t2.id_SucursalClinica, t2.st_Nombre
		ORDER BY t2.st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
	//######################################################################################
	function obtieneSucursalesPBI($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_Cliente = '3' AND i_Activo = '1' AND id_TipoSucursal != '9'
		ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales		
	
	
//######################################################################################
	function obtieneSucursalML($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_SucursalClinica = '".$id."'";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
	//######################################################################################
	function obtieneSucursalPBI($id = ""){
		$query0 = "SELECT id_SucursalClinica, UPPER(st_Nombre) as nombreSucursal FROM cat_SucursalClinica 
		WHERE id_SucursalClinica = '".$id."'";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_SucursalClinica'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_SucursalClinica']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreSucursal'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales		
	
	
	//######################################################################################
	function obtieneAreasInsumo($id = ""){
		$query0 = "SELECT id_AreaInsumos, UPPER(st_Nombre) as nombreArea FROM cat_AreaInsumos 
		WHERE i_Activo = '1' ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$selected = ($arrayQuery0['id_AreaInsumos'] == $id)? 'selected="selected"' : '';
			$resultado .= "<option value='".$arrayQuery0['id_AreaInsumos']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreArea'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
	
	//######################################################################################
	function obtieneAreasInsumoSucursal($idSucursal){
		
		$query1 = "SELECT TOP 1 st_Almacenes FROM cat_SucursalClinica WHERE id_SucursalClinica = '".$idSucursal."'";
		$rquery1 = mssql_query($query1);
		$arrayQuery1 = mssql_fetch_array($rquery1);
		$stAlmacenes = $arrayQuery1['st_Almacenes'];
		
		$query0 = "SELECT id_AreaInsumos, UPPER(st_Nombre) as nombreArea FROM cat_AreaInsumos 
		WHERE i_Activo = '1' AND id_AreaInsumos IN (".$stAlmacenes.") ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$resultado .= "<option value='".$arrayQuery0['id_AreaInsumos']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreArea'])."</option>";
		}
		return $resultado;
	}//Fin Metodo obtieneSucursales	
	
	//######################################################################################
	function obtieneAreasInsumoTipoUserSucursal($idSucursal,$idTipoUsuario){
		
		$whereAreaInsumo = "";
		switch($idTipoUsuario){
		
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
		
		$query1 = "SELECT TOP 1 st_AreasInsumos FROM tbl_RelSucursalInsumosA WHERE id_Sucursal='".$idSucursal."'";
		$rquery1 = mssql_query($query1);
		$arrayQuery1 = mssql_fetch_array($rquery1);

		$stAlmacenes = $arrayQuery1['st_AreasInsumos'];
		
		$query0 = "SELECT id_AreaInsumos, UPPER(st_Nombre) as nombreArea FROM cat_AreaInsumos 
		WHERE i_Activo = '1' AND id_AreaInsumos IN (".$stAlmacenes.") ".$whereAreaInsumo." ORDER BY st_Nombre";
		$rquery0 = mssql_query($query0);
		while( $arrayQuery0 = mssql_fetch_array($rquery0) ){		
			$resultado .= "<option value='".$arrayQuery0['id_AreaInsumos']."' ".$selected.">".$this->mostrar($arrayQuery0['nombreArea'])."</option>";
		}
		
		//Cuenta si tiene permitido un area de insumo
		$query2 = "SELECT count(*) as conteoAreas FROM cat_AreaInsumos 
		WHERE i_Activo = '1' AND id_AreaInsumos IN (".$stAlmacenes.") ".$whereAreaInsumo;
		$rquery2 = mssql_query($query2);
		$arrayQuery2 = mssql_fetch_array($rquery2);
		$this->conteoAreas = $arrayQuery2['conteoAreas'];
		
		
		return $resultado;
	}//Fin Metodo obtieneSucursales		
	
	
			
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
			 					
}//Fin Class Catalogos
?>