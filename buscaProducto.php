<?php
 $idUsuarioWeb = $_GET['id_UsuarioWeb'];
 $idTipo = $_GET['idTipo'];
 $idSucursal = $_GET['idSucursal'];
?>
<table width="85%" border="0" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">             
  <tr> 
    <td> <br />&nbsp;&nbsp;<img src="../images/iPassed.png" width="12" height="12" />&nbsp;Producto 
      <label> 
      <input name="token2" type="text" id="token2" autocomplete="off" onchange="javascript:changeAjax('ajaxShowProductosOptica.php?id_UsuarioWeb=<?=$idUsuarioWeb?>&idSucursal=<?=$idSucursal?>&idTipo=<?=$idTipo?>', 'token2', 'xDiv_SubEstados_2');" />
      <input type="button" name="Button" value="Buscar" onclick="javascript:changeAjax('ajaxShowProductosOptica.php?id_UsuarioWeb=<?=$idUsuarioWeb?>&idSucursal=<?=$idSucursal?>&idTipo=<?=$idTipo?>', 'token2', 'xDiv_SubEstados_2');" />
      </label> 
      <br> <div id="xDiv_SubEstados_2" >..</div></td>                   
  </tr>             
</table>