<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
$fechainicio=date("m/d/Y H:i:s");
require ("../db.php");
$Id = $_GET['Id'];
$IdP=$_GET['IdP'];

if($IdP == 1)   $querytxtfer ="id_DiagnosticoUno";
if($IdP == 2)   $querytxtfer ="id_DiagnosticoDos";
if($IdP == 3)   $querytxtfer ="id_DiagnosticoTres";


 $queryselc ="SELECT     MAX(id_Diagnostico) AS id_Diagnostico, st_Diagnostico, st_ClaveAfinity
FROM         cat_Diagnostico
WHERE     (st_Diagnostico LIKE '%".$Id."%') OR ( st_ClaveAfinity  LIKE '%".$Id."%') 
GROUP BY st_Diagnostico, st_ClaveAfinity
ORDER BY id_Diagnostico DESC";
$rqueryselc = mssql_query($queryselc);

?>
<span class="Estilo7">
<select 
      name="<?=$querytxtfer?>" size="1" class="AnswerListboxRW" id="<?=$querytxtfer?>">
	  <?
	  
	  while($rowdata=mssql_fetch_array($rqueryselc)) {
	  ?>
  <option value="<?=$rowdata['id_Diagnostico']?>"> <?=$rowdata['st_ClaveAfinity'] ?>/ <?=$rowdata['st_Diagnostico']?></option>
    <?
	  
	}
	  ?>
  
</select>
</span>
