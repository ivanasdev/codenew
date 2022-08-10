<?php header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
require ("../db.php");
require("moneysession.php");


?>
<div id="dinerosession">     <img src="../images/icosapps/Create-ticket-64.png" /> <a href="#NONE" onclick="javascript:Abrir_ventana('../../preticketgeneral.php?idsession=<?=$_SESSION["id_TicketGeneral"]?>')"> 
              TICKET UNICO SESION ($
              <?=number_format($dinerosession,2,'.',',');?>
              )</a> <img src="../images/icRefresh.gif" width="13" height="16" border="0" onclick="javascript:changeAjax('ajaxmoney.php', 'sucursal', 'dinerosession');" id="sucursal" />
            </div> 
			<?  mssql_close();
			?>