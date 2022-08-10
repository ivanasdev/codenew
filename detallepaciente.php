<?php
header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
require("../db.php");
session_start();
if ($_SESSION['id_TipoUsuario'] != 14) :
  echo "
		<script>
			alert('Sesion expirada favor de cerrar sesion y volver a ingresar al modulo!');
			location.href='logout.php';
		</script>
	";
endif;


/****************************************	Registrando Tracking	********************************************************************************/
  include("../Tracking.class.php");
  $objTracking = new Tracking();
  $objTracking->setTracking($_SESSION["id_Operador"], $_GET["idusuarioweb"], $_SESSION["id_Sucursal"], "/system/optica/detallePaciente.php", "Optica");
/***************************************************************************************************************************************************/

// Caducamos las sessiones viejas
  $querycad = "UPDATE tbl_TicketGeneral SET id_Impreso = 1
    WHERE (DATEDIFF(dd, GETDATE(), dt_FechaRegistro) < 0)  and  id_Impreso = 0 ";
  $rquerycad = mssql_query($querycad);
/* ---- */

$id_UsuarioWeb = $_GET["idusuarioweb"];
$idevento = $_GET['idevento'];
$varcontrol = $_GET['varcontrol'];
$idtipocita = $_GET["idtipocita"];
include("../Clases2021/class.BloqueCitas.php");
$objCitas = new citas();
$bloqueCitas = $objCitas->bloqueCitas($id_UsuarioWeb,'5', '');

//obtenemos  el dinero electronico
  $queryd = "SELECT saldoelectronico FROM DE_Saldo WHERE (id_UsuarioWeb = " . $id_UsuarioWeb . ")";
  $rqueryd = mssql_query($queryd);
  $rowsaldo  = mssql_fetch_array($rqueryd);
  $saldoelectornico = $rowsaldo["saldoelectronico"];
/* ---- */

//OBTENEMOS LA DEUDA DE TREAPIAS
  $querydeu = "SELECT isnull(SUM(t1.i_Saldo),0) AS i_Saldo
    FROM tbl_SaldoFinalTerapias t1
    INNER JOIN tbl_EvVentaProductosRecetaUsuariosWeb t2 ON t1.id_VentaProductosUsuarioWeb = t2.id_VentaProductosUsuarioWeb
    WHERE (t2.id_UsuarioWeb = ".$id_UsuarioWeb.")";

  $querydeu = "SELECT sum(deudas) i_Saldo
  FROM (
    SELECT co.i_Total - sum(pu.i_Cantidad) deudas FROM tbl_PagosUsuarioWeb pu
    INNER JOIN tbl_TicketGeneral tg on tg.id_TicketGeneral = pu.id_session
    INNER JOIN tbl_UsuariosWebCac us on us.id_UsuarioWeb = pu.id_UsuarioWeb
    INNER JOIN tbl_evVentaOptica vo on convert(varchar(20),vo.id_VentaOptica) = pu.id_EventoConcepto
    INNER JOIN tbl_CotizacionOptica co on co.id_CotizacionOptica= vo.id_CotizacionOptica
    WHERE pu.id_Concepto = 8 AND pu.id_UsuarioWeb = '".$id_UsuarioWeb."'
    GROUP BY co.id_CotizacionOptica, co.i_Total
  ) tabla1";
  $rqueryd = mssql_query($querydeu);
  $rowsaldo = mssql_fetch_array($rqueryd);
  $i_Saldo = $rowsaldo["i_Saldo"];

  $queryselect = "SELECT * FROM tbl_UsuariosWeb WHERE (id_UsuarioWeb = '".$id_UsuarioWeb."')";
  $rqueryselect = mssql_query($queryselect);
  $rowdata = mssql_fetch_array($rqueryselect);
  $st_Documento = (trim($rowdata['st_Documento']) == "" || $rowdata['st_Documento'] == null || $rowdata['st_Documento'] == 'SIN MEMEBRESIA') ? "Sin Membresia" : $rowdata['st_Documento'];

  $_SESSION['st_NombrePaciente'] = $rowdata['st_Nombre']." ".$rowdata['st_ApellidoPaterno']." ".$rowdata['st_ApellidoMaterno']." (".$st_Documento.")";
/* ---- */

//GENERAMOS LA SESION  DE  COMPRA
  $query = "SELECT id_TicketGeneral,st_Barcode FROM tbl_TicketGeneral
    WHERE (id_UsuarioWeb = '".$id_UsuarioWeb."') AND id_Impreso = 0
    AND (DATEDIFF(dd, GETDATE(), dt_FechaRegistro) = 0) AND i_ImpresoPreticket = 0
    ORDER BY id_TicketGeneral DESC";
  $rquery = mssql_query($query);
  $numrows = mssql_num_rows($rquery);
  if ($numrows == 0) :
    $st_Key = md5($id_UsuarioWeb . date('ymis'));
    $querysession = "INSERT INTO tbl_TicketGeneral (id_UsuarioWeb, st_Key, id_Impreso, id_Operador)
    VALUES ('" . $id_UsuarioWeb . "','" . $st_Key . "',0,'" . $operador . "')";
    $rquerysession =  mssql_query($querysession);
    $rquery = mssql_query($query);
    $noexisita = 0;
  endif;
  $rowsesion = mssql_fetch_array($rquery);
  $id_TicketGeneral = $rowsesion["id_TicketGeneral"];
  $_SESSION["id_TicketGeneral"] = $id_TicketGeneral;
  $_SESSION["varcode"] = $rowsesion["st_Barcode"];
/* ---- */

// Generamos el código de barras del ticket en caso de que la session no exisita
  if ($noexisita == 0) :
    require("varcodesession.php");
    //actualizmos el campo de codigo de barras
    $queyvarcode = "UPDATE tbl_TicketGeneral SET st_Barcode ='".$_SESSION["varcodedigit"]."',
      st_BarcodeClean = '".$varcode."', st_Digit = '".$digit."'
      WHERE (id_TicketGeneral = ".$id_TicketGeneral.")";
    $rqueyvarcode = mssql_query($queyvarcode);
  endif;
/* ---- */

// Abrimos ventana emergente de cotización
  $bodyLoad = ((isset($_GET['muestraCotizacion'])) ? "onload=\"javascript:Abrir_ventana('detallepacienteOpticaDo.php?i_Consultorio=0&idusuarioweb=" . $id_UsuarioWeb . "');\"" : "");
/* ---- */

// Codigo descuento
  $membresiaLink = "(Sin Membresia) - <a href='addMembresia.php?idusuarioweb=".$id_UsuarioWeb."'>Adquirir Membresía</a>";
  $membresiaLink = ($st_Documento == "Sin Membresia") ? $membresiaLink : 'Membresía: '.$st_Documento;

  $_SESSION["descuento"] =  ($st_Documento == "Sin Membresia") ? 0:1;
    
  //DESCUENTO FORZADO 
  $aplicaSucursal = array(9, 11, 12, 14, 15);
  if (in_array($_SESSION["id_Sucursal"], $aplicaSucursal) ) :
    $_SESSION["descuento"] = 1;
  endif;

  //OBTENEMOS LA CANTIDAD DE DINERO EN EL TICEKT DE SESSION
  require("moneysession.php");
/* --- */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="refresh" content="600;URL=detallepaciente.php?idusuarioweb=<?= $id_UsuarioWeb ?>">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle Paciente Optica</title>

  <link href="../styles/style.css" rel="stylesheet" type="text/css">
  <link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
  <link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
  <script src="../../utils/jquery-1.11.1.min.js" language="javascript" type="text/javascript"></script>
  <script src="../../utils/jqueryAlerts11/jquery.alerts.js" language="javascript" type="text/javascript"></script>
  <link href="../../utils/jqueryAlerts11/jquery.alerts.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="../../utils/tinybox/tinybox.js"></script>
  <link href="../../utils/tinybox/style_tiny.css" rel="stylesheet" type="text/css">
  <script src="../../system/RegistroPaciente/updatePaciente.js"></script>
  <script type="text/javascript">
    function generaPreticket() {
      var idTicketGeneral = $('#idTicketGeneral').val();
      jConfirm('Se generar&aacute; el preticket y ya no podr&aacute; modificar la cotizaci&oacute;n, &iquest;Desea continuar?', 'Cuadro de Confirmacion', 5, function(result) {
        if (result) {
          $.ajax({
            url: "ajaxActualizaPreticket.php",
            type: "POST",
            dataType: "json",
            data: {
              id_TicketGeneral: idTicketGeneral,
              flag: 1
            },
            beforeSend: function() {
              $('.linkPreticket').hide();
            },
            success: function(data) {
              if (data.error == 1) {
                jAlert(data.mensaje, 'Cuadro de Dialogo', 2, function() {
                  $('.linkPreticket').show();
                });
              } else {
                //jAlert(data.mensaje, 'Cuadro de Dialogo', 1, function(){
                //Abrir_ventana('../../preticketgeneral.php?idsession='+idTicketGeneral+'&preticketOptica=1');
                abrirPopPreticket('400', '400', '../../preticketgeneral.php?idsession=' + idTicketGeneral + '&preticketOptica=1')
                //});	
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              jAlert('Status: ' + textStatus + ' - Error: ' + errorThrown, 'Cuadro de Dialogo', 2, function() {
                $('.linkPreticket').show();
              });
            }
          });
        }
      });
    }

    var peticion = false;
    var testPasado = false;
    try {
      peticion = new XMLHttpRequest();
    } catch (trymicrosoft) {
      try {
        peticion = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (othermicrosoft) {
        try {
          peticion = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (failed) {
          peticion = false;
        }
      }
    }
    if (!peticion)
      alert("ERROR AL INICIALIZAR!");

    function changeAjax(url, comboAnterior, element_id) {
      var element = document.getElementById(element_id);
      var valordepende = document.getElementById(comboAnterior)
      var x = valordepende.value
      if (url.indexOf('?') != -1) {
        var fragment_url = url + '&Id=' + x;
      } else {
        var fragment_url = url + '?Id=' + x;
      }
      element.innerHTML = 'Cargando...<!--<img src="Imagenes/loading.gif" />-->';
      peticion.open("GET", fragment_url);
      peticion.onreadystatechange = function() {
        if (peticion.readyState == 4) {
          element.innerHTML = peticion.responseText;
        }
      }
      peticion.send(null);
    }
  </script>
  <script>
    function Enviar() {
      document.encuesta.action = "../../carnet.php";
      document.forms[0].submit();
    }

    function EnviarVer() {
      document.encuesta.action = "../../previewcarnet.php";
      document.forms[0].submit();
    }

    function abrirPop3(ancho, alto, php) {
      TINY.box.show({
        iframe: php,
        boxid: 'frameless',
        width: ancho,
        height: alto,
        fixed: false,
        maskid: 'graymask',
        maskopacity: 40,
        closejs: function() {
          console.log("hola");
        }
      });
    }
  </script>
  <script language="JavaScript">
    function Abrir_ventana(pagina) {
      var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=568, height=765, top=85, left=140";
      window.open(pagina, "", opciones);
    }

    function Abrir_ventanas(pagina) {
      var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=850, height=565, top=85, left=140";
      window.open(pagina, "", opciones);
    }
  </script>

  <!-- <script type="text/javascript" src="scripts.js"></script> -->
  <script type="text/javascript" src="expansor.js"></script>

  <script type="text/javascript">
    /////////// TinyBox Receta ///////////
    function abrirPopPreticket(ancho, alto, php) {
      TINY.box.show({
        iframe: php,
        boxid: 'frameless',
        width: ancho,
        height: alto,
        fixed: false,
        maskid: 'graymask',
        maskopacity: 40
      });
    }

    function checkIn(idUsuario, idEvento){
      $.ajax({
        url: "../updateCita.php",
        type: "GET",
        dataType: "json",
        data: { eventoCita: idEvento },
        beforeSend: function() {
        },
        success: function(data) {
          alert(data.mensaje);
          window.location.reload();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          jAlert('Status: ' + textStatus + ' - Error: ' + errorThrown, 'Cuadro de Dialogo', 2, function() {});
        }
      });
    }
    function cancelarCita(idUsuario, idEvento){
      $.ajax({
        url: "../cancelacita.php",
        type: "GET",
        dataType: "json",
        data: { eventoCita: idEvento },
        beforeSend: function() {
        },
        success: function(data) {
          alert(data.mensaje);
          window.location.reload();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          jAlert('Status: ' + textStatus + ' - Error: ' + errorThrown, 'Cuadro de Dialogo', 2, function() {});
        }
      });
    }

    $(document).ready(function() {
      cargaDatosPaciente(<?= intval($idevento) ?>, <?= $id_UsuarioWeb ?>, '../../', 'optica');
    });
  </script>

  <style type="text/css">
    body {
      margin-left: 0px;
      margin-top: 0px;
      margin-right: 0px;
      margin-bottom: 0px;
    }

    .Estilo7 {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 10px;
    }

    .Estilo8 {
      font-size: 12px;
    }

    .cnt {
      width: 850px;
      background-color: #DDAADD;
      margin: 0px;
      padding: 15px;
      font-weight: bold;
    }

    .trans {
      background-color: #E9E9E9;
      color: #CC0000;
      position: relative;
      text-align: center;
      top: 100px;
      left: 68px;
      padding: 65px;
      font-size: 25px;
      font-weight: bold;
      width: 852px;
      height: 1405px;
    }

        .tbox{
      position: fixed !important; top: 17px !important;
    }
  </style>
</head>

<body <?= $bodyLoad ?> style="background-color:transparent">
  <br>
  <form name="encuesta" method="post" action="../../carnet.php" id="encuesta" target="encuesta" onsubmit="window.open('', 'encuesta', 'width=10,height=12')">
    <input type="hidden" name="refreshDatosUpdate" id="refreshDatosUpdate" value="0" />
    <input type="hidden" name="idEventoCitaUpdate" id="idEventoCitaUpdate" value="<?= intval($idevento) ?>">
    <input type="hidden" name="idUsuarioWebUpdate" id="idUsuarioWebUpdate" value="<?= $id_UsuarioWeb ?>">
    <input type="hidden" name="ruta2indexUpdate" id="ruta2indexUpdate" value="../../">
    <input type="hidden" id="idTicketGeneral" name="idTicketGeneral" value="<?= $id_TicketGeneral ?>" />
    <input type="hidden" name="id_UsuarioWeb" id="id_UsuarioWeb" value="<?= $id_UsuarioWeb ?>" />

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr> <!-- Encabezado datos generales del usuario -->
        <td width="97%">
          <table>
            <tr>
              <td>
                <div align="center" class="telefonosOutbound">
                  <div align="left"><a href="content.php"><img src="../images/icosapps/exit-32.png" border="0" /></a>
                    <?= $_SESSION['st_NombrePaciente'] ?>
                    <a href="detallepaciente.php?idusuarioweb=<?= $id_UsuarioWeb ?>"> 
                     <img src="../images/icRefresh.gif" width="13" height="16" border="0" />
                    </a>
                    <div id="refreshsession"> <img src="../images/icosapps/Login-32.png" /> 
                      ID SESSION: <?= $_SESSION["id_TicketGeneral"] ?> / <img src="../images/icosapps/People-32.png"/> GOLD
                    </div>
                    Saldo electronico: $e <?= number_format($saldoelectornico, 2) ?> / Deuda Óptica: $<?= number_format($i_Saldo, 2) ?>
                      <a href="registrarpagos.php?idusuarioweb=<?= $id_UsuarioWeb ?>&id_EventoVenta=NA"> << Ver</a>
                  </div>
                </div>
              </td>
            </tr>
          </table>
        </td>
        <td width="3%">&nbsp;</td>
      </tr>
      <tr> <!-- Espacio en blanco -->
        <td>
          <table width="100%" border="0">
            <tr>
              <td width="15%">&nbsp;</td>
              <td width="85%">
                <div align="center" class="telefonosOutbound"><div align="left"></div></div>
              </td>
            </tr>
          </table>
        </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
          <div align="center">
            <div class="wrapper">
              <div class="DIVleft"> <!-- LEFT/IZQUIERDA -->

                <div class="DIVmod_header_border"><img src="images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                <div class="DIVmod_header"> <!-- Encabezado Datos Generales -->
                  <div class="DIVmod_header_text"><img src="../images/icosapps/Personal-information-48.png" width="48" height="48" />
                    Datos Generales <input name="v1" type="checkbox" id="v1" value="1" checked="checked" />
                    <input name="preview" type="hidden" id="preview" value="0" />
                  </div>
                </div>
                <div class="DIVmod_body"> <!-- Datos usuario y Examen de la vista -->
                  <div class="DIVpadding">
                    <div id="datosPaciente"></div>
                    <br><br>
                    <a href="historia0.php?idusuarioweb=<?= $id_UsuarioWeb ?>">
                      <img src="../images/icosapps/Medical-invoice-information-48.png" width="48" height="48" border="0" />
                      EXAMEN DE LA VISTA
                    </a><br>
                  </div>
                </div>
                <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>

                <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                <div class="DIVmod_header"> <!-- Bloque pagos y abonos -->
                  <div class="DIVmod_header_text"><img src="../images/icosapps/Secure-payment-48.png" width="48" height="48" />
                    Pagos/Abonos <input name="v2" type="checkbox" id="v2" value="1" checked="checked" />
                  </div>
                </div>
                <div class="DIVmod"> <!-- Consulta de pagos y abonos -->
                  <div class="DIVpadding">
                    <div align="right"></div>
                    <div align="right"></div>
                    <a href="historialpagos.php?idusuarioweb=<?= $id_UsuarioWeb ?>&id_EventoVenta=NA">
                      Ver historial de pagos
                    </a><br />
                    <a href="registrarpagos.php?idusuarioweb=<?= $id_UsuarioWeb ?>&id_EventoVenta=NA">
                      Ingresar nuevo pago
                    </a><br />
                    <? 
                      $query = "SELECT top 3 t1.id_PagoUsuarioWeb, t2.st_Concepto, 
                          t1.dt_FechaRegistro, t1.id_EventoConcepto, 
                          t1.i_Cantidad, t2.id_Concepto
                        FROM tbl_PagosUsuarioWeb t1
                        INNER JOIN cat_ConceptosPagos t2 ON t1.id_Concepto = t2.id_Concepto
                        WHERE (t1.id_UsuarioWeb = ".$id_UsuarioWeb.") AND t2.id_Concepto in (8,9)
                        ORDER BY t1.dt_FechaRegistro DESC";
                      $rquery =  mssql_query($query);
                      $numrows = mssql_num_rows($rquery);
                      if ($numrows > 0) echo "<br>Ultimos ".$numrows." pagos<br><ul>";
                      while ($rowcompras = mssql_fetch_array($rquery)) {
                        echo '<ul>';
                          echo '<li><a href="historialpagos.php?idusuarioweb='.$id_UsuarioWeb.'&id_PagoUsuarioWeb='.$rowcompras['id_PagoUsuarioWeb'].'">';
                            echo $rowcompras['dt_FechaRegistro'].'<br>';
                            echo $rowcompras['st_Concepto'].'$ '.$rowcompras['i_Cantidad'];
                          echo '</a></li>';
                        echo '</ul>';
                      }
                      if ($numrows > 0) echo "</ul>";
                    ?>
                  </div>
                  <div class="DIVmod_footer">
                    <div style="display:none;" id="div8" class="DIVmod_footer_hide">
                      La busqueda puede ser por nombre de cliente, apellidos, email, por numero de cedula o numero de cita. 
                    </div>
                  </div>
                </div>
               
                <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
                <span class="Estilo7"><img src="../images/icosapps/Gnome-Document-Print-Preview-48.png" width="48" height="48" />
                  <a href="#NONE" onclick="EnviarVer()">VISTA PREVIA CARNET GENERAL</a><br />
                  <img src="../images/icosapps/Gnome-Document-Print-48.png" width="48" height="48" />
                  <a href="#NONE" onclick="Enviar()">IMPRIMIR CARNET GENERAL</a>
                </span>

              </div>

              <div class="DIVcenter">
                <!-- CENTER/MEDIO -->
                <br>
                <div id="dinerosession"> <!-- Dinero ticket sesión -->
                  <img src="../images/icosapps/Create-ticket-64.png"/> <a class="linkPreticket" href="#NONE" onclick="javascript:generaPreticket();">
                    TICKET UNICO SESION ($ <?= number_format($dinerosession, 2, '.', ','); ?> )
                  </a> 
                  <img src="../images/icRefresh.gif" width="13" height="16" border="0" onclick="javascript:changeAjax('ajaxmoney.php', 'sucursal', 'dinerosession');" id="sucursal" />
                </div> <br>
                <!-- Mandamos a llamar el bloque de citas  -->
                <?=$bloqueCitas;?>
                
                <!--new    -->
                <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
                <div class="DIVmod_header"> <!-- Bloque de servicios -->
                  <div class="DIVmod_header_text"><img src="../images/optica.png" height="48" />
                    SERVICIOS <input name="v6" type="checkbox" id="v6" value="1" checked="checked" />
                  </div>
                </div>
                <div class="DIVmod"> <!-- Servicios Optica -->
                  <div class="DIVpadding">
                    <div align="left"> <br />
                      <strong>OPTICA</strong>
                    </div>
                  </div>
                  <a href="javascript:Abrir_ventana('detallepacienteOpticaDo.php?i_Consultorio=0&idusuarioweb=<?= $id_UsuarioWeb ?>')">
                    <img src="../images/iCheck.gif" width="14" height="18" /> Generar nuevo presupuesto
                  </a><br> 
                  <a href="detallecomprasoptica.php?idusuarioweb=<?= $id_UsuarioWeb ?>&id_VentaProductosUsuarioWeb=NA">
                    Ver todas las compras
                  </a> <br />
                  <? 
                    if ($operador == 1127) :
                      echo '<a href="listaCotizacionesPaciente.php?idusuarioweb='.$id_UsuarioWeb.'">Consultar cotizaciones</a><br />';
                    endif;
                  ?>
                  <br>
                  <div class="DIVmod_footer">
                    <div class="DIVmod_footer_text"><a href="#third" onclick="expansor('first', 'expansor1');"></a></div>
                    <div id="div">
                      <div style="display:none;" id="div2" class="DIVmod_footer_hide">
                        La busqueda puede ser por nombre de cliente, apellidos, email, por numero de cedula ó número de cita. 
                      </div>
                    </div>
                  </div>
                </div>
                <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_consultas.gif" class="IMGmod_footer" alt="footer" /></div>
                <span class="Estilo7"></span><br />
              </div>
            </div>
          </div>
        </td>
        <td>&nbsp;</td>
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
  </form>
</body>

</html>
<?php mssql_close(); ?>