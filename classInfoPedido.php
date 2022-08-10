<?php


class InfoPedido{
    function getInfo($idPedido){
        $query="
                SELECT 
                PP.id_PedidoOpticaPrevio,
                PP.id_PedidoOpticaProveedor,
                co.id_cotizacionOptica,
                PP.st_Folio as folio, 
                vo.id_VentaOptica id_EventoConcepto,
                us.id_UsuarioWeb,
                us.st_Nombre + ' ' + us.st_ApellidoPaterno + ' ' + us.st_ApellidoMaterno Nombre, 
                convert(varchar(15),vo.dt_FechaRegistro,111) [fecha compra], 	
                cs.st_Nombre Sucursal, 
                po.st_Nombre Producto, 
                po.st_Descripcion Descripcion, 
                po.st_Codigo Codigo,  
                co.i_Total total, 
                (sum(pu.i_Cantidad) - co.i_Total) saldo, 
                sum(pu.i_Cantidad) abono, 
                tg.st_BarcodeVenta ticket, 
                tg.st_Barcode preticket,
                usv.st_User UsuarioCaja, 
                case when sta.st_StatusPedidoOptica is null then 'Sin Estatus' else sta.st_StatusPedidoOptica end st_StatusPedidoOptica, 
                sta.id_StatusPedidoOptica,
                sta.st_BackColor,
                dt_FechaEnvioLaboratorio,
                dt_FechaRecibidoSucursal,
                dt_FechaEntregadoCliente,
                id_OperadorEnviaLaboratorio,
                id_OperadorRecibidoSucursal,
                id_OperadorEntregadoCliente
                FROM tbl_EvVentaOptica vo 
                inner join tbl_CotizacionOptica co on vo.id_VentaOptica = co.id_EventoVenta
                inner join tbl_paqueteOptica po on po.id_PaqueteOptica = co.id_PaqueteOptica
                inner join cat_SucursalClinica cs on cs.id_SucursalClinica = vo.id_Sucursal
                inner join tbl_PagosUsuarioWeb pu on pu.id_EventoConcepto = vo.id_VentaOptica and pu.id_Concepto in (8,9)
                inner join tbl_UsuariosWebCac us on us.id_UsuarioWeb = vo.id_usuarioWeb
                inner join tbl_TicketGeneral tg on tg.id_TicketGeneral = co.st_key
                inner join tbl_UsuarioSistemaWeb usv on usv.id_UsuarioSistemaWeb = tg.id_OperadorVendio
                left join tbl_pedidosOpticaProveedor pp on pp.id_EventoConcepto = vo.id_VentaOptica
                left join cat_StatusPedidoOptica sta on sta.id_StatusPedidoOptica = pp.id_Status
                WHERE co.id_Status = 3 AND 	PP.id_PedidoOpticaProveedor=".$idPedido."
                
                group by vo.id_VentaOptica,us.st_Nombre,us.st_ApellidoPaterno, us.st_ApellidoMaterno,vo.dt_FechaRegistro, cs.st_Nombre, po.st_Nombre, po.st_Descripcion, 
                po.st_Codigo, co.i_Total, tg.st_BarcodeVenta, tg.st_Barcode,usv.st_User, sta.st_StatusPedidoOptica, sta.st_BackColor,PP.id_PedidoOpticaProveedor,us.id_UsuarioWeb,co.id_cotizacionOptica,PP.st_Folio,
                sta.id_StatusPedidoOptica,dt_FechaEnvioLaboratorio,
                dt_FechaRecibidoSucursal,
                dt_FechaEntregadoCliente,
                id_OperadorEnviaLaboratorio,
                id_OperadorRecibidoSucursal,
                id_OperadorEntregadoCliente,
                PP.id_PedidoOpticaPrevio,
                pu.id_TipoPago
                having (sum(pu.i_Cantidad) >= (co.i_Total * 0.30)) OR pu.id_TipoPago = '13'
                order by vo.dt_FechaRegistro desc   ";

                $resq=mssql_query($query);
                $arraypedido=mssql_fetch_array($resq);

                    

                    return $arraypedido;
                



    }//END FUCNTION 





}//END OF CLASSS 




?>