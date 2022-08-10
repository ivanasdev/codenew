<?php 
require("../db.php");
$ruta2index = "../../";

include("Clases/Class.Pedido.php");

$objpedido=new Pedido();







$folio=$_POST['scannerinput'];





    $query = "
    SELECT TOP 100 vo.id_VentaOptica id_EventoConcepto,
us.st_Nombre + ' ' + us.st_ApellidoPaterno + ' ' + us.st_ApellidoMaterno Nombre,
convert(varchar(15),vo.dt_FechaRegistro,111) fecha_compra,
convert(varchar(15),pp.dt_FechaEnvioLaboratorio,111) fecha_envio_optometrista,
cs.st_Nombre Sucursal,
po.st_Nombre Producto,
 po.st_Descripcion Descripcion,
  po.st_Codigo Codigo,
   pp.st_Folio  Folio,
    pp.st_Sobre Sobre,
    pp.st_EsferaIzq EIzq,
     pp.st_EsferaDer EDer,
      pp.st_CilindroIzq CIzq,
       pp.st_CilindroDer CDer,
        pp.st_EjeIzq EjeIz,
         pp.st_EjeDer EjeDer,
          pp.st_AO AO,
           pp.st_DI DI,
            pp.st_ADD A_DD ,
             pp.st_Material material,
              pp.st_Armazon armazon,
               pp.st_Paquete pack,
                pp.st_Observaciones observaciones,
                 pp.st_CostoArmazon costo_arma,
                  pp.st_CostoBicel costo_bicel,
                   pp.st_CostoMaterial costo_material,
                    co.i_Total total,
                     (sum(pu.i_Cantidad) - co.i_Total) saldo,
                      sum(pu.i_Cantidad) abono,
                       tg.st_BarcodeVenta ticket,
                        tg.st_Barcode preticket,
                         sta.st_StatusPedidoOptica statusOptica,
                          sta.st_BackColor COLOR,
                           co.st_key K_ey,
                           isnull(t3.TelefonoCasa,'S/D') AS TelefonoCasa,
                            isnull(t3.TelefonoCelular,'S/D') AS TelefonoCelular,
                             ta1.st_TipoPago t_pago,
                              pp.i_Garantia i_garantia,
                               pp.st_Garantia garantia,
                                pp.dt_FechaGarantia f_garantia,
                                pp.id_StatusLaboratorio status_lab
FROM tbl_EvVentaOptica vo
INNER JOIN tbl_CotizacionOptica co
ON vo.id_VentaOptica = co.id_EventoVenta
INNER JOIN tbl_paqueteOptica po
ON po.id_PaqueteOptica = co.id_PaqueteOptica
INNER JOIN cat_SucursalClinica cs
ON cs.id_SucursalClinica = vo.id_Sucursal
INNER JOIN tbl_PagosUsuarioWeb pu
ON pu.id_EventoConcepto = vo.id_VentaOptica
    AND pu.id_Concepto IN (8,9)
INNER JOIN tbl_UsuariosWebCac us
ON us.id_UsuarioWeb = vo.id_usuarioWeb
INNER JOIN tbl_TicketGeneral tg
ON tg.id_TicketGeneral = co.st_key
INNER JOIN tbl_UsuarioSistemaWeb usv
ON usv.id_UsuarioSistemaWeb = tg.id_OperadorVendio
INNER JOIN tbl_pedidosOpticaProveedor pp
ON pp.id_EventoConcepto = vo.id_VentaOptica
INNER JOIN cat_StatusPedidoOptica sta
ON sta.id_StatusPedidoOptica = pp.id_Status
LEFT JOIN tbl_UsuariosWeb t3
ON us.id_UsuarioWeb= t3.id_UsuarioWeb
LEFT JOIN cat_TipoPagos ta1
ON pu.id_TipoPago = ta1.id_TipoPago
WHERE co.id_Status = 3
AND pp.id_Status IN (14,8,9,10,11,12,13,2,3,15,16)
AND pp.st_Folio='" . $folio . "'
GROUP BY  vo.id_VentaOptica, us.st_Nombre, us.st_ApellidoPaterno, us.st_ApellidoMaterno, vo.dt_FechaRegistro, pp.dt_FechaEnvioLaboratorio, cs.st_Nombre, po.st_Nombre, po.st_Descripcion, po.st_Codigo, co.i_Total, tg.st_BarcodeVenta, tg.st_Barcode, usv.st_User, sta.st_StatusPedidoOptica, sta.st_BackColor, pp.id_PedidoOpticaProveedor, us.id_UsuarioWeb, co.id_cotizacionOptica, pp.st_Folio, pp.st_Sobre, pp.st_EsferaIzq, pp.st_EsferaDer, pp.st_CilindroIzq, pp.st_CilindroDer, pp.st_EjeIzq, pp.st_EjeDer, pp.st_AO, pp.st_DI, pp.st_ADD, pp.st_Material, pp.st_Armazon, pp.st_Paquete, pp.st_Observaciones, pp.st_CostoArmazon, pp.st_CostoBicel, pp.st_CostoMaterial, co.st_key, t3.TelefonoCasa, t3.TelefonoCelular, pu.id_TipoPago, ta1.st_TipoPago, pp.i_Garantia, pp.st_Garantia, pp.dt_FechaGarantia, pp.id_StatusLaboratorio
HAVING (sum(pu.i_Cantidad) >= (co.i_Total * 0.30))
OR pu.id_TipoPago = '13'
        
    ";
    $rowdata = mssql_query($query);
    $numRow=mssql_num_rows($rowdata);
    if($numRow > 0){
        $resultado.= '<div class="panel-body"><div id="dynamic">';
        $resultado.= '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
        $resultado.= "<thead>";
        $resultado.= "<tr>";
        $resultado.= '<th>Sucursales</th>';
        $resultado.= '<th>Ticket</th>';
        $resultado.= '<th>Etiqueta Sobre</th>';
        $resultado.= '<th>Tipo Pago</th>';
        $resultado.= '<th>Fecha de Compra</th>';
    
        $resultado.= '<th>Fecha Envío de Optometrista</th>';
        $resultado.= '<th>Escanear Sobres </th>';
            $resultado.= '<th>Estatus&nbsp;&nbsp;</th>';
        $resultado.= '<th>Nombre</th>';
        $resultado.= '<th>Telefono Casa</th>';
        $resultado.= '<th>Telefono cel.</th>';
        $resultado.= '<th>Folio&nbsp</th>';
        $resultado.= '<th>Producto</th>';
        $resultado.= '<th>Material&nbsp;&nbsp;</th>';
        $resultado.= '<th>Armazon&nbsp;&nbsp;</th>';
        $resultado.= '<th>Paquete&nbsp;&nbsp;</th>';
    
        $resultado.= '<th>Esf. Der</th>';
        $resultado.= '<th>Cil. Der.</th>';
        $resultado.= '<th>Eje Der.</th>';
        $resultado.= '<th>Esf. Izq.</th>';
        $resultado.= '<th>Cil. Izq.</th>';
        $resultado.= '<th>Eje Izq.</th>';
        $resultado.= '<th>ADD.</th>';
        $resultado.= '<th>AO.</th>';
        $resultado.= '<th>DI.</th>';
        $resultado.= '<th>Observaciones</th>';					
        $resultado.= '<th>Costo</th>';
        $resultado.= '<th>Abono</th>';
        $resultado.= '<th>Saldo</th>';					
        $resultado.= '<th>Recibido</th>';
        $resultado.= '<th>Tipo Trabajo</th>';	
        $resultado.= '<th>Laboratorio</th>';	
        $resultado.= '<th>Fecha Envio Lab.</th>';
        $resultado.= '<th>Estatus Laboratorio</th>';
        $resultado.= '<th>Entregado Laboratorio</th>';										
        $resultado.= '<th>Costo Trabajo</th>';
        $resultado.= '<th>Costo 1</th>';
        $resultado.= '<th>Costo 2</th>';
        $resultado.= '<th>Costo 3</th>';
        $resultado.= '<th>Fecha Envio Suc.</th>';
        $resultado.= '<th>Fecha Recibio Suc.</th>';
        $resultado.= '<th>Fecha Entrgo Pac.</th>';
        $resultado.= '<th>Garant&iacute;a</th>';
        //$resultado.= '<th class="borde">Editar</th>';	
        $resultado.= "</tr>";
        $resultado.= "</thead><tbody>";
        $id_Eventoactual = "0";
        while ($row = mssql_fetch_array($rowdata)){
            if($id_Eventoactual != $row['id_EventoConcepto']){
                $id_Eventoactual = $row['id_EventoConcepto'];
                $stGarantia = ($row['i_Garantia'] > 0)? $row['st_Garantia'].'<br>'.$row['dt_FechaGarantia'] : 'No Aplica';
                unset($arrayQuery0);
                $nombreCosto0 = $nombreCosto1 = $nombreCosto2 = "";
                //Costos Productos
                $query0 = "
                    SELECT ta2.st_Nombre, ta1.Cantidad, ta2.costoPaquete FROM
                        (
                            SELECT t2.id_ServicioOptica as id_PaqueteOptica, SUM(nt_Cantidad) as Cantidad
                            FROM tbl_CotizacionOptica t1
                            INNER JOIN tbl_CotizacionOpticaDetalle t2 ON t1.id_CotizacionOptica = t2.id_CotizacionOptica
                            INNER JOIN tbl_pedidosOpticaProveedor t3 ON t1.id_EventoVenta = t3.id_EventoConcepto
                            WHERE t3.id_EventoConcepto = '".$id_Eventoactual."'
                            GROUP BY t2.id_ServicioOptica
                        ) ta1
                        INNER JOIN
                        (
                            SELECT t1.id_PaqueteOptica, SUM(i_CostoUnitario) as costoPaquete, t1.st_Nombre
                            FROM tbl_PaqueteOptica t1
                            INNER JOIN tbl_PaqueteOpticaServicios t2 ON t1.id_PaqueteOptica = t2.id_PaqueteOptica
                            INNER JOIN cat_ServicioOptica t3 ON t2.id_ServicioOptica = t3.id_ServicioOptica
                            WHERE t1.i_Activo = '1'
                            GROUP BY t1.id_PaqueteOptica, t1.st_Nombre
                        ) ta2 ON ta1.id_PaqueteOptica = ta2.id_PaqueteOptica
                    ORDER BY ta2.st_Nombre";
                $rquery0 = mssql_query($query0);
                for($i=0; $i<3; $i++){
                    $arrayQuery0 = mssql_fetch_array($rquery0);
                    $nombre[$i] = $arrayQuery0["st_Nombre"];
                    $cantidad[$i] = $arrayQuery0["Cantidad"];
                    $costo[$i] = $arrayQuery0["costoPaquete"];
                }
                $qryStatusLE="SELECT st_StatusPedidoOptica
                    FROM cat_StatusPedidoOptica
                    WHERE id_StatusPedidoOptica = ".$row['id_StatusLaboratorio'];
                $rqryStatusLE=mssql_query($qryStatusLE);
                $fStatusLE=mssql_fetch_object($rqryStatusLE);
                $nombreCosto0 = ($cantidad[0] == "")? "" : $cantidad[0].'<br>'.$nombre[0].'<br>$ '.$costo[0].'';
                $nombreCosto1 = ($cantidad[1] == "")? "" : $cantidad[1].'<br>'.$nombre[1].'<br>$ '.$costo[1].'';
                $nombreCosto2 = ($cantidad[2] == "")? "" : $cantidad[2].'<br>'.$nombre[2].'<br>$ '.$costo[2].'';
                $resultado.= '<tr>';
                $resultado.= '<td>'.htmlentities($row['Sucursal']).'</td>';
                $resultado.= '<td><a href="javascript:Ticket('.$row['st_key'].')">'.$row['ticket'].'</a></td>';
                $resultado.= '<td><a href="javascript:etiquetaSobre('.$row['id_EventoConcepto'].')">Imprimir etiqueta</a></td>';
                $resultado.= '<td>'.$row['st_TipoPago'].'</td>';
                $resultado.= '<td>'.$row['fecha compra'].'</td>';
                $resultado.= '<td>'.$row['fecha envio de optometrista'].'</td>';
                $resultado.= '<td>'.htmlentities($row['Nombre']).'</td>';
                $resultado.= '<td>'.$row['TelefonoCasa'].'</td>';
                $resultado.= '<td>'.$row['TelefonoCelular'].'</td>';
                $resultado.= '<td>'.$row['st_Folio'].'</td>';
                $queryProductos = "SELECT po.st_Nombre from tbl_EvVentaOptica vo
                    INNER JOIN fernandoruiz.tbl_CotizacionOptica co on vo.id_VentaOptica = co.id_EventoVenta
                    INNER JOIN fernandoruiz.tbl_CotizacionOpticaDetalle cod on cod.st_Key = co.st_Key
                    INNER JOIN tbl_paqueteOptica po on po.id_PaqueteOptica = cod.id_ServicioOptica
                    INNER JOIN tbl_pedidosOpticaProveedor pp on pp.id_EventoConcepto = vo.id_VentaOptica
                    WHERE pp.id_EventoConcepto =".$row['id_EventoConcepto'];			
                $resProductos = mssql_query($queryProductos);
                $productos = "";
                while($rowProductos = mssql_fetch_object($resProductos)){
                    $productos .= $rowProductos->st_Nombre."<br>";
                }
                $resultado.= '<td>'.$productos.'</td>';
                $resultado.= '<td>'.htmlentities($row['st_Material']).'</td>';
                $resultado.= '<td>'.htmlentities($row['st_Armazon']).'</td>';
                $resultado.= '<td>&nbsp;&nbsp;'.htmlentities($row['st_Paquete']).'</td>';
                $resultado.= '<td id="Status_'.$row['id_EventoConcepto'].'"><font style="color:'.$row['st_BackColor'].'; font-weight:bolder; font-size:12px;">'.$row['st_StatusPedidoOptica'].'<font>'.$this->RegresaRecibidoLabML($row['id_EventoConcepto']).'</td>';
                $resultado.= '<td>'.$row['st_EsferaDer'].'</td>';
                $resultado.= '<td>'.$row['st_CilindroDer'].'</td>';
                $resultado.= '<td>'.$row['st_EjeDer'].'</td>';
                $resultado.= '<td>'.$row['st_EsferaIzq'].'</td>';
                $resultado.= '<td>'.$row['st_CilindroIzq'].'</td>';
                $resultado.= '<td>'.$row['st_EjeIzq'].'</td>';
                $resultado.= '<td>'.$row['st_ADD'].'</td>';
                $resultado.= '<td>'.$row['st_AO'].'</td>';
                $resultado.= '<td>'.$row['st_DI'].'</td>';
                $resultado.= '<td>'.htmlentities($row['st_Observaciones']).'</td>';
                $resultado.= '<td>$'.$row['total'].'</td>';
                $resultado.= '<td>$'.$row['abono'].'</td>';
                $resultado.= '<td>$'.$row['saldo'].'</td>';
                $resultado.= '<td>'.$this->RecibidoLaboratorioANL($row['id_EventoConcepto'],$row['st_Folio']).'</td>';
                $resultado.= '<td>'.$this->TipoTrabajo($row['id_EventoConcepto'],$row['st_Folio']).'</td>';
                $resultado.= '<td>'.$this->Laboratorio($row['id_EventoConcepto']).'</td>';
                $resultado.= '<td><div id="FechaEnvio_'.$row['id_EventoConcepto'].'">'.$this->Fecha($row['id_EventoConcepto'],9).'</div></td>';
                $resultado.= '<td>'.$fStatusLE->st_StatusPedidoOptica.'
                    <div id="SurtidoMicas_'.$row['id_EventoConcepto'].'">'.$this->SurtidoMicas($row['id_EventoConcepto']).'</div></td>';
                $resultado.= '<td><div id="Terminado_'.$row['id_EventoConcepto'].'">'.$this->RecibidoDelLaboratorio($row['id_EventoConcepto'],$row['st_Folio']).'</div></td>';
                $resultado.= '<td><div id="CostoMicas_'.$row['id_EventoConcepto'].'">'.$this->FormCostomicas($row['id_EventoConcepto']).'</div></td>';
                $resultado.= '<td><center>'.$nombreCosto0.'</center></td>';
                $resultado.= '<td><center>'.$nombreCosto1.'</center></td>';
                $resultado.= '<td><center>'.$nombreCosto2.'</center></td>';
                $resultado.= '<td><div id="FechaES_'.$row['id_EventoConcepto'].'">'.$this->FormFechaEnvioSucursal($row['id_EventoConcepto'],$row['st_Folio']).'<div></td>';
                $resultado.= '<td><div id="FechaRS_'.$row['id_EventoConcepto'].'">'.$this->Fecha($row['id_EventoConcepto'],12).'<div></td>';
                $resultado.= '<td><div id="FechaEP_'.$row['id_EventoConcepto'].'">'.$this->Fecha($row['id_EventoConcepto'],13).'<div></td>';
                $resultado.= '<td>'.$stGarantia.'</td>';
                //$resultado.= '<td class="borde"><a href="javascript:detalle('.$row['id_EventoConcepto'].')"><img src="images/icons/Pencil3.ico" width="15px" height="15px" title="Editar"></a></td>';
                $resultado.='</tr>';
            }
        }
        $resultado.= "<tfoot>";	
        $resultado.= "<tr >";
        $resultado.= '<th><input class="search_init" type="text" name="Sucursal" placeholder="Sucursal" align="left"></input></th>';					
        $resultado.= '<th><input class="search_init" type="text" name="Ticket" placeholder="Ticket" align="left"></input></th>';	
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Fecha de Compra" placeholder="Fecha de Compra" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Fecha Envío de Optometrista" placeholder="Fecha Envío de Optometrista" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Nombre" placeholder="Nombre" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="TelefonoCasa" placeholder="Telefono Casa" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="TelefonoCelular" placeholder="Telefono Celular" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Folio" placeholder="Folio" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Producto" placeholder="Producto" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Material" placeholder="Material" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Armazon" placeholder="Armazon" align="left"></input></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Paquete" placeholder="Paquete" align="left"></input></th>';					
        $resultado.= '<th><input class="search_init" type="text" name="Estatus" placeholder="Estatus" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';					
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';	
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';	
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';				
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';					
        $resultado.= '<th><input class="search_init" type="text" name="Recibido" placeholder="Recibido" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Tipo_Trabajo" placeholder="Tipo Trabajo" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Laboratorio" placeholder="Laboratorio" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="FechaENL" placeholder="Envio Laboratorio" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="FechaEL" placeholder="Entrego Laboratorio" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="Estatus Laboratorio" placeholder="Estatus Laboratorio" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="hidden" name="x"/></th>';
        $resultado.= '<th><input class="search_init" type="text" name="FechaES" placeholder="Envio Sucursal" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="FechaRS" placeholder="Recibido Sucursal" align="left" /></th>';
        $resultado.= '<th><input class="search_init" type="text" name="FechaEP" placeholder="Entrega Paciente" align="left" /></th>';
        $resultado.= "</tr>";
        $resultado.= "<tfoot>";
        $resultado.= '</tbody></table>';
        $resultado.='</div></div>';
    }else{
        $resultado.='<div class="alert alert-danger alert-error"><strong>No hay resultados en estas fechas!</strong> Intente con otras fechas.</div>';
    }
    return $resultado;






?>

