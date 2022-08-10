<?  header( 'Expires: Sat, 01 Jan 2000 00:00:01 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link href="../styles/style.css" rel="stylesheet" type="text/css">
<link href="../cac/estilos/1024estilo_cuadrosvazulmarino_2col.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_encabezadosencillo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo_mmenupers.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/estilo.css" rel="stylesheet" type="text/css" />
<link href="../cac/estilos/master_consultas.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="dhtmlgoodies_calendar.js?random=20060118"></script>

<script language="JavaScript">
function Abrir_ventana (pagina) {
var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=508, height=800, top=85, left=140";
window.open(pagina,"",opciones);
}
 function fixElement(element, message) {
                    alert(message);
                    element.focus();
                    }
					
   function RevisaForma(forma) {
                      var error = 0;




if((forma.passold.value == '') && (error == 0)) {
                        fixElement(forma.passold, "Ingrese password anterior");
                        error = 1;
                    }
					
if((forma.pass.value == '') && (error == 0)) {
                        fixElement(forma.pass, "Ingrese nuevo  password ");
                        error = 1;
                    }					


if((forma.pass.value != forma.cpass.value ) && (error == 0)) {
  fixElement(forma.documento, "Los passwords no coinciden.");
  error = 1;
}

				
					


                     if (error == 1) {
                       //return false;
                     }else{
                       if(confirm('Desea contiunuar con el formulario')) {
                            document.Guion.submit();
                       }else{
                          // return false;
                       }
                     }

                 }
                 </script>

<script type="text/javascript" src="../cac/scripts.js"></script>
<script type="text/javascript" src="../cac/expansor.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo8 {
	color: #FFFFFF;
}
.Estilo9 {font-size: 12px}
-->
</style></head>

<body>	<form  method="post" action="docambipassword.php"  name="Guion"  onSubmit="return RevisaForma(this);" >

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="97%" background="../images/headofice.jpg"><div align="center" class="telefonosOutbound">
          <div align="left"></div>
    </div></td>
    <td width="3%" background="../images/headofice.jpg"><img src="../images/headofice.jpg" width="41" height="90" /></td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="85%"><div align="center" class="telefonosOutbound">
              <div align="left"></div>
          </div></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>

<div align="center">
    <div class="wrapper">
        
        <div class="DIVleft">
            <!-- LEFT/IZQUIERDA -->
            <div class="DIVmod_header_border"><img src="../cac/images/layout/div_mod_header_consultas.gif" class="IMGmod_header" alt="centro" /></div>
            <div class="DIVmod_header">
              
            <div class="DIVmod_header_text">CAMBIO DE PASSWORD<span class="Estilo8"> 
              <input name="idevento" type="hidden" id="idevento" value="<?=$idevento?>" />
                <input name="idusuarioweb" type="hidden" id="idusuarioweb" value="<?=$id_UsuarioWeb?>" />
                </span></div>
            </div>
            <div class="DIVmod_body">
            	<div class="DIVpadding">
            	  <div class="DIVopts"><span class="menuTop">
            	    </span>
            	    
                <div class="DIVopt"> 
                  <table width="75%" border="0">
                    <tr>
                      <td width="50%">&nbsp;</td>
                      <td width="50%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Contrasena anterior</td>
                      <td><input name="passold" type="password" id="passold" /></td>
                    </tr>
                    <tr>
                      <td>Nuevo password</td>
                      <td><input name="pass" type="password" id="pass" /></td>
                    </tr>
                    <tr>
                      <td>Repetir password</td>
                      <td><input name="cpass" type="password" id="cpass" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                  <input type="button" name="Submit" value="Enviar"  onclick="javascript:document.Guion.onsubmit();" />
                </div>
              </div>
              </div></div>
            <div class="DIVmod_footer_border"><img src="../cac/images/layout/div_mod_footer_alone.gif" class="IMGmod_footer" alt="footer" /></div>
        </div></td>
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
</table></form>
</body>
</html>