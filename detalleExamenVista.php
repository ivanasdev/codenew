<?
require("../db.php");
session_start();
// var_dump($_SESSION);
$ruta2index = "../../";
////////////////////////////// TRACKING ////////////////
include($ruta2index . "class.Tracking.php");
$objTracking = new Tracking(5, 33, "CITAS - Agendar Cita");
///////////////////////////////////////////////////////	
////////////////////////////// TRACKING ////////////////
include("../RegistroPaciente/ZonaHoraria.php");
$fechaActualCompleta = date("Y-m-d H:i",time());

///////////////////////////////////////////////////////	

/* Validamos que nuestra cita tenga un evento cita */
  if(!isset($_GET["idusuarioweb"], $_GET["idevento"])) :
    echo "Petición no generada";
    exit;
  endif;
/* Función para pintar solo información de la cita */
  function ImprimeDetalleExamen($idEventoCita, $idUsuarioWeb) {
    $qry0 = "SELECT TOP 1 CONCAT(t2.st_Nombre,' ',t2.st_ApellidoPaterno,' ',t2.st_ApellidoMaterno) as st_Paciente, 
      --
        t1.dt_FechaActualizacion, t1.st_Ocupacion, t1.st_Padecimientos, t1.st_Observaciones, t1.st_ImpresionDiagnostica, t1.st_PlanTratamiento,
        t1.st_OD_CRX, t1.st_OD_SRX, t1.st_OI_CRX, t1.st_OI_SRX, t1.st_AO_CRX, t1.st_AO_SRX, t1.st_AVC_OD_CRX, t1.st_AVC_OD_SRX, 
        t1.st_AVC_OI_CRX, t1.st_AVC_OI_SRX, t1.st_AVC_AO_CRX, t1.st_AVC_AO_SRX, t1.st_AO,  t1.st_ACO,  t1.st_DI,  t1.st_ADD, t1.st_CV_ODLejos, t1.st_CV_ODCerca,
        t1.st_CV_OILejos, t1.st_CV_OICerca, t1.st_CV_AOLejos, t1.st_CV_AOCerca, t1.st_oda_esfera, t1.st_oda_cilindro, 
        t1.st_oda_eje, t1.st_oia_esfera, t1.st_oia_cilindro, t1.st_oia_eje, t1.st_rxad_esfera, t1.st_rxad_cilindro, 
        t1.st_rxad_eje, t1.st_rxai_esfera, t1.st_rxai_cilindro, t1.st_rxai_eje, t1.st_rxd_esfera, t1.st_rxd_cilindro,
        t1.st_rxd_eje, t1.st_rxi_esfera, t1.st_rxi_cilindro, t1.st_rxi_eje, t1.id_GraduacionUsuario  
      FROM tbl_OptGraducionUsuarioHistory t1
      INNER JOIN tbl_UsuariosWebCac t2 ON t2.id_UsuarioWeb = t1.id_UsuarioWeb 
      INNER JOIN tbl_EvCitasUsuariosWeb t3 ON CONVERT(date,t3.dt_FechaCita) = CONVERT(date,t1.dt_FechaActualizacion)
      WHERE t1.id_UsuarioWeb = '".$idUsuarioWeb."' AND t3.id_Evento = '".$idEventoCita."'";
    $eje = mssql_query($qry0);
    $conteo = mssql_num_rows($eje);
    $salida = 'Sin Examen de la Vista.';
    if($conteo > 0 ):
      $row = mssql_fetch_array($eje);
      //A.V.L.            
        $stOD_CRX = trim($row["st_OD_CRX"]);
        $stOD_SRX = trim($row["st_OD_SRX"]);
        $stOI_CRX = trim($row["st_OI_CRX"]);
        $stOI_SRX = trim($row["st_OI_SRX"]);
        $stAO_CRX = trim($row["st_AO_CRX"]);
        $stAO_SRX = trim($row["st_AO_SRX"]);
      //A.V.C.            
        $stAVC_OD_CRX = trim($row["st_AVC_OD_CRX"]);
        $stAVC_OD_SRX = trim($row["st_AVC_OD_SRX"]);
        $stAVC_OI_CRX = trim($row["st_AVC_OI_CRX"]);
        $stAVC_OI_SRX = trim($row["st_AVC_OI_SRX"]);
        $stAVC_AO_CRX = trim($row["st_AVC_AO_CRX"]);
        $stAVC_AO_SRX = trim($row["st_AVC_AO_SRX"]);
      //Autorefractometro
        $stOda_esfera = trim($row["st_oda_esfera"]);
        $stOda_cilindro = trim($row["st_oda_cilindro"]);
        $st_oda_eje = trim($row["st_oda_eje"]);
        $stOiaEsfera = trim($row["st_oia_esfera"]);
        $stOia_cilindro = trim($row["st_oia_cilindro"]);
        $stOia_eje = trim($row["st_oia_eje"]);
      //RX Anterior
        $stRxad_esfera = trim($row["st_rxad_esfera"]);
        $stRxad_cilindro = trim($row["st_rxad_cilindro"]);
        $stRxad_eje = trim($row["st_rxad_eje"]);
        $stRxai_esfera = trim($row["st_rxai_esfera"]);
        $stRxai_cilindro = trim($row["st_rxai_cilindro"]);
        $stRxai_eje = trim($row["st_rxai_eje"]);
      //RX Actual
        $stRxd_esfera = trim($row["st_rxd_esfera"]);
        $stRxd_cilindro = trim($row["st_rxd_cilindro"]);
        $stRxd_eje = trim($row["st_rxd_eje"]);
        $stRxi_esfera = trim($row["st_rxi_esfera"]);
        $stRxi_cilindro = trim($row["st_rxi_cilindro"]);
        $stRxi_eje = trim($row["st_rxi_eje"]);
      //CAPACIDAD VISUAL           
        $stCV_ODLejos = trim($row["st_CV_ODLejos"]);
        $stCV_ODCerca = trim($row["st_CV_ODCerca"]);
        $stCV_OILejos = trim($row["st_CV_OILejos"]);
        $stCV_OICerca = trim($row["st_CV_OICerca"]);
        $stCV_AOLejos = trim($row["st_CV_AOLejos"]);
        $stCV_AOCerca = trim($row["st_CV_AOCerca"]);
      //ADD, AO, DI
        $add = trim($row["st_ADD"]);
        $aco = trim($row["st_ACO"]);
        $ao = trim($row["st_AO"]);
        $di = trim($row["st_DI"]);

      $salida = '<div class="DIVpadding">';
        $salida .= '<div class="DIVmod_title">';
          $salida .= '<br>';
          $salida .= '<table align ="center" width="70%">';
            $salida .= '<tr>';
              $salida .= '<td><b>Nombre Paciente: </b><span class="Estilo7">'.utf8_encode($row["st_Paciente"]).'</span></td>';
              $salida .= '<td><b>Fecha Actualización: </b><span class="Estilo7">'.$row["dt_FechaActualizacion"].'</span></td>';
            $salida .= '</tr>';
            $salida .= '<tr>';
              $salida .= '<td><b>Ocupación: </b><span class="Estilo7">'.utf8_encode($row["st_Ocupacion"]).'</span></td>';
              $salida .= '<td><b>Padecimiento: </b><span class="Estilo7">'.utf8_encode($row["st_Padecimientos"]).'</span></td>';
            $salida .= '</tr>';
            $salida .= '<tr>';
              $salida .= '<td colspan="2"><b>Observaciones: </b><span class="Estilo7">'.utf8_encode($row["st_Observaciones"]).'</span></td>';
            $salida .= '</tr>';
            $salida .= '<tr>';
              $salida .= '<td colspan="2"><b>Impresión Diagnóstica: </b><span class="Estilo7">'.utf8_encode($row["st_ImpresionDiagnostica"]).'</span></td>';
            $salida .= '</tr>';
            $salida .= '<tr>';
              $salida .= '<td colspan="2"><b>Plan Tratamiento: </b><span class="Estilo7">'.utf8_encode($row["st_PlanTratamiento"]).'</span></td>';
            $salida .= '</tr>';
          $salida .= '</table>';

          $salida .= '<table align ="center">';
            $salida .= ' <tr>'; // AGUDEZA VISUAL, GRADUACIÓN ANTERIOR Y ACTUAL
              $salida .= '<td>'; // GRADUACION AGUDEZA VISUAL LEJANA
                $salida .= '<table class="graduacion">';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th align="center" width="40px">A.V.L.</th>';
                    $salida .= '<th align="center">CON RX</th>';
                    $salida .= '<th align="center">SIN RX</th>';
                    // $salida .= '<th align="center" class="avTxt" '.$auxAGV = ($id_HCAgudezaVisualOD == 0 && $id_HCAgudezaVisualOI == 0) ? 'style="display: none;"':'';}.'>A.V.</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stOD_CRX.'</td>';
                    $salida .= '<td>'.$stOD_SRX.'</td>';
                    // $salida .= '<input type="text" id="od_estenoL" name="od_estenoL" size="12px" placeholder="20/70" class="diagonal2" value="$stOD_estenoL" />';
                    // $salida .= '<td class="avTxtOD"'.$aux = ($id_HCAgudezaVisualOD == 0) ? 'style="display: none;"': ''.'>';
                    //   $salida .= '<select id="od_txtAV" name="od_txtAV">'.catAV().'</select>';
                    // $salida .= '</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stOI_CRX.'</td>';
                    $salida .= '<td>'.$stOI_SRX.'</td>';
                    // $salida .= '<td class="avTxtOI" '.$aux = ($id_HCAgudezaVisualOI == 0) ? 'style="display: none;"':''.'>';
                      // $salida .= '<select id="oi_txtAV" name="oi_txtAV">'.catAV().'</select>';
                    // $salida .= '</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">A.O.</td>';
                    $salida .= '<td>'.$stAO_CRX.'</td>';
                    $salida .= '<td>'.$stAO_SRX.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';

              $salida .= '<td>'; // GRADUACION AGUDEZA VISUAL CERCANA
                $salida .= '<table class="graduacion">';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th align="center" width="40px">A.V.C.</th>';
                    $salida .= '<th align="center">CON RX</th>';
                    $salida .= '<th align="center">SIN RX</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stAVC_OD_CRX.'</td>';
                    $salida .= '<td>'.$stAVC_OD_SRX.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stAVC_OI_CRX.'</td>';
                    $salida .= '<td>'.$stAVC_OI_SRX.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">A.O.</td>';
                    $salida .= '<td>'.$stAVC_AO_CRX.'</td>';
                    $salida .= '<td>'.$stAVC_AO_SRX.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';

              $salida .= '<td>'; // GRADUACION ANTERIOR
                $salida .= '<table class="graduacion">';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th colspan="4" align="center">RX ANTERIOR</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr bgcolor="#CCCCCC" style="color:#009;">';
                    $salida .= '<td width="40px">&nbsp;</td>';
                    $salida .= '<td align="center"><b>Esfera</b></td>';
                    $salida .= '<td align="center"><b>Cilindro</b></td>';
                    $salida .= '<td align="center"><b>Eje</b></td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stRxad_esfera.'</td>';
                    $salida .= '<td>'.$stRxad_cilindro.'</td>';
                    $salida .= '<td>'.$stRxad_eje.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stRxai_esfera.'</td>';
                    $salida .= '<td>'.$stRxai_cilindro.'</td>';
                    $salida .= '<td>'.$stRxai_eje.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';

              $salida .= '<td>'; // GRADUACION ACTUAL
                $salida .= '<table border="0" class="graduacion"> ';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th colspan="4" align="center">RX ACTUAL</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr bgcolor="#CCCCCC" style="color:#009;">';
                    $salida .= '<td width="40px">&nbsp;</td>';
                    $salida .= '<td align="center"><b>Esfera</b></td>';
                    $salida .= '<td align="center"><b>Cilindro</b></td>';
                    $salida .= '<td align="center"><b>Eje</b></td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stRxd_esfera.'</td>';
                    $salida .= '<td>'.$stRxd_cilindro.'</td>';
                    $salida .= '<td>'.$stRxd_eje.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stRxi_esfera.'</td>';
                    $salida .= '<td>'.$stRxi_cilindro.'</td>';
                    $salida .= '<td>'.$stRxi_eje.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';
            $salida .= '</tr>';

            $salida .= '<tr>'; // AUTOREFRACTOMETRO, IINFORMACIÓN Y CAPACIDAD VISUAL
              $salida .= '<td valign="top">'; // AUTOREFRACTOMETRO
                $salida .= '<table class="graduacion">';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th colspan="4" align="center">AUTOREFRACTOMETRO</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr bgcolor="#CCCCCC" style="color:#009;">';
                    $salida .= '<td width="40px">&nbsp;</td>';
                    $salida .= '<td align="center"><b>Esfera</b></td>';
                    $salida .= '<td align="center"><b>Cilindro</b></td>';
                    $salida .= '<td align="center"><b>Eje</b></td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stOda_esfera.'</td>';
                    $salida .= '<td>'.$stOda_cilindro.'</td>';
                    $salida .= '<td>'.$st_oda_eje.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stOiaEsfera.'</td>';
                    $salida .= '<td>'.$stOia_cilindro.'</td>';
                    $salida .= '<td>'.$stOia_eje.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';

              $salida .= '<td>'; // Capacidad Visual
                $salida .= '<table class="graduacion">';
                  $salida .= '<tr bgcolor="#666666" style="color:#FFFFFF;">';
                    $salida .= '<th colspan="3" align="center">CAPACIDAD VISUAL</th>';
                  $salida .= '</tr>';
                  $salida .= '<tr bgcolor="#CCCCCC" style="color:#009;">';
                    $salida .= '<td width="40px">&nbsp;</td>';
                    $salida .= '<td align="center"><b>Lejos</b></td>';
                    $salida .= '<td align="center"><b>Cerca</b></td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.D.</td>';
                    $salida .= '<td>'.$stCV_ODLejos.'</td>';
                    $salida .= '<td>'.$stCV_ODCerca.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">O.I.</td>';
                    $salida .= '<td>'.$stCV_OILejos.'</td>';
                    $salida .= '<td>'.$stCV_OICerca.'</td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                  $salida .= '<td bgcolor="#CCCCCC" style="color:#009;" align="center">A.O.</td>';
                    $salida .= '<td>'.$stCV_AOLejos.'</td>';
                    $salida .= '<td>'.$stCV_AOCerca.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';

              $salida .= '<td valign="top" colspan="2">'; // Informacion
                $salida .= '<table border="0" class="graduacion">';
                  $salida .= '<tr><td colspan="4" style="padding-bottom: 10.5%;"></td></tr>';
                  $salida .= '<tr bgcolor="#CCCCCC" style="color:#009;">';
                    $salida .= '<td align="center"><b>ADD</b></td>';
                    $salida .= '<td align="center"><b>ACO (mm)</b></td>';
                    $salida .= '<td align="center"><b>AO (mm)</b></td>';
                    $salida .= '<td align="center"><b>DI</b></td>';
                  $salida .= '</tr>';
                  $salida .= '<tr>';
                    $salida .= '<td>'.$add.'</td>';
                    $salida .= '<td>'.$aco.'</td>';
                    $salida .= '<td>'.$ao.'</td>';
                    $salida .= '<td>'.$di.'</td>';
                  $salida .= '</tr>';
                $salida .= '</table>';
              $salida .= '</td>';
            $salida .= '</tr>';
          $salida .= '</table>';
        $salida .= '</div>';
      $salida .= '</div>';
    endif;
    return $salida;
    exit;
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../styles/style.css" rel="stylesheet" type="text/css" />
  <link href="estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
  <link href="estilos/estilo.css" rel="stylesheet" type="text/css" />
  <link href="estilos/master_consultas.css" rel="stylesheet" type="text/css" />
  <link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen" />
  <script type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>
  <script type="text/javascript" src="expansor.js"></script>
  <script type="text/javascript" src="<?= $ruta2index ?>utils/jquery-1.11.1.min.js"></script>
  <script src="<?= $ruta2index ?>utils/jqueryAlerts11/jquery.alerts.js"></script>
	<link rel="stylesheet" href="<?= $ruta2index ?>utils/jqueryAlerts11/jquery.alerts.css"/>

  <script type="text/javascript" src="<?= $ruta2index ?>utils/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
  <link href="<?= $ruta2index ?>utils/jquery-ui-1.11.0.custom/css/jquery_ui/redmond/jquery-ui-1.10.3.custom.css" rel="stylesheet">
  <script type="text/javascript" src="<?= $ruta2index ?>utils/jquery-ui-1.11.0.custom/jquery.ui.datepicker-es.js"></script>

  <script language="JavaScript">
    function VerificarDispo() {
      var opciones = "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=508, height=800, top=85, left=140";
      var pagina = "../validacita.php?tipo=" + document.Guion.cita.value + "&min=" + document.Guion.minutoscita.value +
        "&hora=" + document.Guion.horacita.value + "&fecha=" + document.Guion.fechacita.value + "&medico=" + document.Guion.idmedicocita.value
      window.open(pagina, "", opciones);
    }

    function calendario(elemento) {
			elemento.datepicker({
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				minDate: 0,
				showButtonPanel: true
			}).datepicker("setDate", "0");
		};
  </script>

  <script language="Javascript">
    function filtro(input) {
      s = input.value;
      filteredValues = "1234567890";
      var i;
      var returnString = "";
      for (i = 0; i < s.length; i++) {
        var c = s.charAt(i);
        if (filteredValues.indexOf(c) == -1) returnString += c;
      }
      input.value = returnString;
    }

    $(document).ready(function() {
    });
  </script>

  <title>Detalle Examen Vista</title>
  <style type="text/css">
    body {
      margin-left: 0px;
      margin-top: 0px;
      margin-right: 0px;
      margin-bottom: 0px;
    }

    .Estilo16 {
      color: #996633
    }

    .Estilo17 {
      color: #0066FF
    }

    .Estilo7 {
      font-family: Verdana, Arial, Helvetica, sans-serif;
      font-size: 10px;
    }
    .quitaMargin{
      display: inline-block;
      margin: 0.2rem 0 0.2rem 1.2rem;
      padding: 0 0.4rem;
      border-left: 1px solid var(--border-primary);
      border-right: 1px solid var(--border-primary);
      color: var(--text-time);
      margin-top: -25%;
      margin-left: 50%;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%" style="height: 97px;" background="../images/headofice.jpg">
        <div align="center" class="telefonosOutbound">
          <div align="left">Detalle Examén de la Vista </div>
        </div>
      </td>
    </tr>
  </table>
  <?= ImprimeDetalleExamen($_GET["idevento"], $_GET["idusuarioweb"]);?>
  <form method="post" action="docitanueva.php" name="Guion" id="Guion">
    <input type="hidden" name="go" value="1" />
    <input type="hidden" name="idGuion" value="1" />
    <input type="hidden" name="claveGuion" value="794235967397762999" />
    <input type="hidden" name="idusr" value="" />
    <input type="hidden" name="iMedico" id="iMedico" value="<?=$_SESSION["id_Medico"]?>" />
    <input name="validacita" type="hidden" id="validacita" value="0" />
    <input name="id_UsuarioWeb" type="hidden" id="id_UsuarioWeb" value="<?= $_GET['idusuarioweb'] ?>" />
    <input name="idEventoCita" type="hidden" id="idEventoCita" value="<?= $_GET['idevento'] ?>" />

  </form>
</body>

</html>
<? mssql_close(); ?>