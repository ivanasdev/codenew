
<table width="100%" border="0">
  <tr>
    <td width="9%"><span class="Estilo8">Fecha Cita </span></td>
    <td width="91%"><label>
      <input name="fechacita" type="text" id="fechacita" value="" readonly="readonly" />
      <input name="button" type="button" onclick="displayCalendar(document.Guion.fechacita,'yyyy/mm/dd',this)" value="Cal" />
    </label></td>
  </tr>
  <tr>
    <td>Hora</td>
    <td><select name="horacita" id="horacita">
        <?php
for($i=9;$i<=18;$i++){
?>
<option value="<?=$i?>">  <?=$i?></option><?
}

?>

    </select>
    :
    <select name="minutoscita" id="minutoscita">
       <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
					<option value="25">25</option>
					<option value="30">30</option>
					<option value="35">35</option>
                    <option value="40">40</option>
					<option value="45">45</option>
                    <option value="50">50</option>
					   <option value="55">55</option>
            </select> 
    <a href="#NONE">Verificar Disponibilidad </a></td>
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
