<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>

<body>
<? include("encabezado.php");?>
<p>&nbsp;</p>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="19%">Tipo de documento</td>
    <td width="34%">&nbsp;</td>
    <td width="21%">Nº de documento</td>
    <td width="26%">&nbsp;</td>
  </tr>
  <tr>
    <td>Nombre de la Organizacion</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
