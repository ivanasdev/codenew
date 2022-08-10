<?php
require("../db.php");
if($_GET['tipo']==1){

$query = "SELECT   *  FROM   dbo.tbl_HoraCitaExpress WHERE   (i_activo <> 0)";
$rquery =   mssql_query($query);
$inputType="";

?>
<table width="100%" border="0">
<tr>
    <td width="122"><span class="Estilo8"><?=utf8_encode("D�a:")?></span></td>
    <td width="843"><label><input name="fechacita" type="text" id="fechacita" value="" readonly="readonly"   />
      <input name="button" type="button" onclick="displayCalendar(document.Guion.fechacita,'yyyy/mm/dd',this)" value="Cal" />
    </label></td>
  </tr>
<tr>
    <td width="122"><span class="Estilo8">Horario:</span></td>
    <td width="843"><label>
<?	
while($rowinscritos =  mssql_fetch_array($rquery)){
?>
<br/><input type="radio" name="horario" value="<?=$rowinscritos["st_HorarioCitaExpress"]?>"  /><?=$rowinscritos["st_HorarioCitaExpress"]."( 0 citas)"?>
<?
}    
  ?>  </label></td>
  </tr>
  <tr>
    <td colspan="2"><div id="Div_SubEstados_22" ></div></td>
  </tr>
</table>

<?
}
if($_GET['tipo']==3){ ?>
	
<table width="100%" border="0">
  <tr>
    <td width="122"><span class="Estilo8">Medicos</span></td>
    <td width="843"><label><select name="idmedicocita" id="idmedicocita"  onchange="javascript:changeAjax('ajaxmedico2.php?idmedico=2&origen=1&empresa=1&tipol=2', 'idmedicocita', 'Div_SubEstados_22222');">
	 <option value="0">Seleccione Medico </option> 
	<? 
	$grupo = 20;
	 $idajax = $_GET['sucursal'];
			  $queryde = " SELECT      fernandoruiz.cat_Medicos.*
FROM         fernandoruiz.cat_Medicos INNER JOIN
                      fernandoruiz.tbl_UsuarioSistemaWeb ON fernandoruiz.cat_Medicos.id_Medico = fernandoruiz.tbl_UsuarioSistemaWeb.id_Medico
WHERE     (fernandoruiz.cat_Medicos.id_SucursalClinica =  ".$idajax.") and
                      (fernandoruiz.tbl_UsuarioSistemaWeb.i_Activo = 1)  
					   AND (fernandoruiz.cat_Medicos.i_tipo   not in (3) )
					   order by fernandoruiz.cat_Medicos.st_Nombre";
 
$rqueryde = mssql_query($queryde);
while($rowdeptos=mssql_fetch_array($rqueryde)){
					  ?>
  <option value="<?=$rowdeptos['id_Medico']?>">
  <?=$rowdeptos['st_Nombre']?>  </option>
  <?
					  }
					  ?>
      </select>   
	
    </label></td>
  </tr>
  <tr>
    <td colspan="2"><div id="Div_SubEstados_22222" ></div></td>
  </tr>
</table>
<?  } ?>