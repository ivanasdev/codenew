<form name="form1" method="post" action="doregistrarpagosoptica.php">
<?php    header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
$responsable = $operador;
$id_UsuarioWeb = $_GET["idusuarioweb"];
$tipo =$_GET["tipo"];
?>
<br><div class="telefonosOutbound"> 
<img src="../images/icosapps/Dolar-64.png"> REGISTRA PAGO<br>
  </DIV>
  <BR><BR>
<table width="57%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr bgcolor="#003399"> 
    <td><font color="#FFFFFF"><br><strong><?=$rowdata["st_TerapiaPaquete"]?></strong></font><br><br></td>
  </tr>
  <tr> 
    <td>
    
    <input name="tipo" type="hidden" id="tipo" value="<?=$tipo?>">
    <input name="id_cotizacionOptica" type="hidden" id="id_cotizacionOptica" value="<?=$id_cotizacionOptica?>"> 
    <input name="id_UsuarioWeb" type="hidden" id="id_UsuarioWeb" value="<?=$id_UsuarioWeb?>">
    <input name="id_EventoVenta" type="hidden" value="<?=$id_EventoVenta?>">
    <input name="id_RecetaProductosUsuarioWeb" type="hidden" id="id_RecetaProductosUsuarioWeb" value="<?=$id_RecetaProductosUsuarioWeb?>">    
    <input type="hidden" name="tipop" id="tipop" value="1">
    
    
     <?php
			$readOnlyClienteSub = "";
			
			$idClienteSubrogado = $_GET['idCliente'];
			if( in_array($idClienteSubrogado,array(21,22,23)) ){
				echo '<br>Tipo de pago: <span style="color:#F60; font-weight:bold;">Cliente Subrogado</span>';
				$readOnlyClienteSub = 'readonly="readonly"';
			}
			elseif( in_array($idClienteSubrogado,array(27)) ){ /// SILAO
				echo '<br><span style="color:#F60; font-weight:bold;">Cliente SILAO</span>';
				$readOnlyClienteSub = 'readonly="readonly"';
			}
      elseif( in_array($idClienteSubrogado,array(36)) ){ /// Campeche
        echo '<br><span style="color:#F60; font-weight:bold;">Cliente CAMPECHE</span>';
        $readOnlyClienteSub = 'readonly="readonly"';
      }
      elseif( in_array($idClienteSubrogado,array(41)) ){ /// Puebla
        echo '<br><span style="color:#F60; font-weight:bold;">Cliente PUEBLA</span>';
        $readOnlyClienteSub = 'readonly="readonly"';
      }
			else{
				$idClienteSubrogado = 0;
			}
		?>
        <input type="hidden" name="idCliente" id="idCliente" value="<?=$idClienteSubrogado?>">
    
       <?php /*?> Tipo de pago 
        <select name="tipop" id="tipop" onChange="javascript:changeAjax('ajaxtipopago.php?tot=<?=$_GET['tot']?>&id_UsuarioWeb=<?=$id_UsuarioWeb?>', 'tipop', 'Div_SubEstados_3');">
          <option value="1" selected>Efectivo</option>
          <option value="2">Tarjeta Credito/Debito</option>
          <option value="3">Dinero electronico</option>
        </select><?php */?>
        
        <br>
     <div id="Div_SubEstados_3" ><input name="banco" type="hidden" id="banco" value="9999">
    Abonar(+/-) 
           
          <input name="abonar" type="text" id="abonar" value="<?=$_GET['tot']?>" size="7" maxlength="7">
          <input name="concepto" type="hidden" id="concepto" value="3">
        </div></td>
  </tr>
  <tr> 
    <td><textarea name="comentarios" cols="40" rows="6" id="comentarios"></textarea></td>
  </tr>
  <tr>
    <td><input type="submit" name="Submit" value="Registrar pago"></td>
  </tr>
</table>
</form>
