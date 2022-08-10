


$query0 = "SELECT TOP 100 vo.id_VentaOptica id_EventoConcepto,
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
OR pu.id_TipoPago = '13'";

$res0 = mssql_query($query0);

while ($arraypedido = mssql_fetch_array($res0)) {



        $nombre = $arraypedido['Nombre'];
    $fechacompra = $arraypedido['fecha_compra'];
    $fechaenvio_opto = $arraypedido['fecha_envio_optometrista'];
    $sucursal = $arraypedido['Sucursal'];
    $producto = $arraypedido['Producto'];
    $descripcion = $arraypedido['Descripcion'];
    $codigo = $arraypedido['Codigo'];
    $st_folio = $arraypedido['Folio'];
    $sobre = $arraypedido['Sobre'];
    $esferaizq = $arraypedido['EIzq'];
    $esferader = $arraypedido['EDer'];
    $cilindroizq = $arraypedido['CIzq'];
    $cilindroder = $arraypedido['CDer'];
    $ejeizq = $arraypedido['EjeIz'];
    $ejeder = $arraypedido['EjeDer'];
    $AO = $arraypedido['AO'];
    $DI = $arraypedido['DI'];
    $ADD = $arraypedido['A_DD'];
    $Material = $arraypedido['material'];
    $Armazon = $arraypedido['armazon'];
    $Paquete = $arraypedido['pack'];
    $observaciones = $arraypedido['observaciones'];
    $costo_armazon = $arraypedido['costo_arma'];
    $costo_Bicel = $arraypedido['costo_bicel'];
    $costo_Material = $arraypedido['costo_material'];
    $total = $arraypedido['total'];
    $saldo = $arraypedido['saldo'];
    $abono = $arraypedido['abono'];
    $ticket = $arraypedido['ticket'];
    $preticket = $arraypedido['preticket'];
    //STATUS 
    $statusPedidoOptica = $arraypedido['statusOptica'];
    $color = $arraypedido['COLOR'];
    $key = $arraypedido['K_ey'];
    $telcasa = $arraypedido['TelefonoCasa'];
    $cel = $arraypedido['TelefonoCelular'];
    $tipo_pago = $arraypedido['t_pago'];
    $int_garantia = $arraypedido['i_garantia'];
    $st_garantia = $arraypedido['garantia'];
    $fecha_garantia = $arraypedido['f_garantia'];
    $statusLab = $arraypedido['status_lab'];
}


<div class="card1" style="margin-top: 27px;">
<div class="panel-body">
    <div id="dynamic">'
            <div class="container-fluid">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">SUCURSAL</th>
                            <th scope="col">TICKET</th>
                            <th scope="col">ETIQUETA SOBRE</th>
                            <th scope="col">TIPO PAGO</th>
                            <th scope="col">FECHA DE COMPRA</th>
                            <th scope="col">FECHA DE ENVIO OPTOMETRISTA</th>
                            <th scope="col">ESTATUS </th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">TEL CASA</th>
                            <th scope="col">CELULAR </th>
                            <th scope="col">FOLIO</th>
                            <th scope="col">PRODUCTO</th>
                            <th scope="col">MATERIAL </th>
                            <th scope="col">ARMAZON</th>
                            <th scope="col">PAQUETE </th>
                            <th scope="col">ESF. DER.</th>
                            <th scope="col">CIL. DER.</th>
                            <th scope="col">EJE. DER.</th>
                            <th scope="col">ESF. IZQ.</th>
                            <th scope="col">CIL. IZQ.</th>
                            <th scope="col">EJE. IZQ.</th>
                            <th scope="col">ADD.</th>
                            <th scope="col">AO.</th>
                            <th scope="col">DI.</th>
                            <th scope="col">OBSERVACIONES</th>
                            <th scope="col">COSTO</th>
                            <th scope="col">ABONO</th>
                            <th scope="col">SALDO</th>
                            <th scope="col">RECIBIDO</th>
                            <th scope="col">TIPO TRABAJO</th>
                            <th scope="col">LABORATORIO</th>
                            <th scope="col">FECHA ENVIO LABORATORIO</th>
                            <th scope="col">ESTATUS LABORATORIO</th>
                            <th scope="col">ENTREGADO LABORATORIO</th>
                            <th scope="col">COSTO TRABAJO</th>
                            <th scope="col">COSTO 1</th>
                            <th scope="col">COSTO 2</th>
                            <th scope="col">COSTO 3</th>
                            <th scope="col">FECHA ENVIO SUCURSAL </th>
                            <th scope="col">FECHA RECIBIO SUCURSAL </th>
                            <th scope="col">FECHA ENTREGO SUCURSAL</th>
                            <th scope="col">GARANTIA </th>            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"><?= $sucursal ?></th>
                            <td><strong><?= $ticket?></strong></td>
                            <td><a href="javascript:etiquetaSobre('.$row['id_EventoConcepto']. ') ">Imprimir etiqueta</a></td>
                            <td><?= $tipo_pago ?></td>

                            <td><strong><?= $fechacompra?></strong></td>
                            <td><strong><?= $fechaenvio_opto?></strong></td>
                            <td><strong><?= $statusPedidoOptica?></strong></td>
                            <td><strong><?= $nombre?></strong></td>
                            <td><strong><?= $telcasa?></strong></td>
                            <td><strong><?= $cel ?></strong></td>
                            <td><strong><?= $st_folio?></strong></td>
                            <td><strong><?= $producto?></strong></td>
                            <td><strong><?= $Material ?></strong></td>
                            <td><strong><?= $Armazon ?></strong></td>
                            <td><strong><?= $Paquete ?></strong></td>
                            <td><strong><?= $esferader ?></strong></td>
                            <td><strong><?= $cilindroder ?></strong></td>
                            <td><strong><?= $ejeder ?></strong></td>
                            <td><strong><?= $esferaizq?></strong></td>
                            <td><strong><?= $cilindroizq ?></strong></td>
                            <td><strong><?= $ejeizq ?></strong></td>
                            <td><strong><?= $ADD?></strong></td>
                            <td><strong><?= $AO?></strong></td>
                            <td><strong><?= $DI?></strong></td>
                            <td><strong><?= $observaciones ?></strong></td>
                            <td><strong><?= $costo_armazon ?></strong></td>
                            <td><strong><?= $abono?></strong></td>
                            <td><strong><?= $saldo?></strong></td>
                            <td><?= $recibo ?> </td>
                            <td><?= $kindawork ?></td>
                            <td><?= $lab ?></td>
                            <td><?= $fechaenviolab ?></td>



                 
           
                            



                        </tr>
               
                
                    </tbody>
                </table>
            </div>
            </div>


