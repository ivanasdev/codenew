<?php
header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
session_start();

$sucBusqueda = $_GET['Id'];
 

?>
 <link rel="stylesheet" href="../styles/style_tables.css" type="text/css">
 <script type="text/javascript" src="js/jquery.min.js"></script>	
<link rel="stylesheet" href="../styles/optica.css" type="text/css"> 
 
<style type="text/css">
 
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo1 {font-size: 9px}

</style>   
  
<div id="listaproductospopup">
 <?
		 
  $query= "exec sp_get_StockSucursalOptica ".$sucBusqueda;
  $rquery= mssql_query($query);
  $numrows= mssql_num_rows($rquery);
  ?> 
		  <table id="rounded-corner" summary="">
		    <thead>
		    	<tr>
		        	<th scope="col" class="rounded-company">Sucursal</th>
		            <th scope="col" class="rounded-q1">Codigo</th>
		            <th scope="col" class="rounded-q2">Descripci&oacute;n</th> 
		            <th scope="col" class="rounded-q4">Total</th>
		        </tr>
		    </thead>
		        <tfoot>
		    	<tr>
		        	<td colspan="3" class="rounded-foot-left"><em></em></td>
		        	<td class="rounded-foot-right">&nbsp;</td>
		        </tr>
		    </tfoot>
		    <tbody>
		    	<?php
				while ($rowCiTas = mssql_fetch_array($rquery)) {
				?>
				 <tr>
			    	<td><?=htmlentities($rowCiTas['st_Nombre'])?></td>
	            	<td><?=htmlentities($rowCiTas['st_Codigo'])?></td>
	            	<td><?=htmlentities($rowCiTas['st_Descripcion'])?></td>  
	            	<td><?=$rowCiTas['total']?></td> 
				<?php } ?>    
		    </tbody>
		</table>
</div>
         