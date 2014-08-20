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

<table cellpadding="1" cellspacing="1" class="mini">
	<tr class="txt_titulo centrado">
		<td>DNI</td>
		<td>NOMBRES</td>
		<td>PATERNO</td>
		<td>MATERNO</td>
		<td>FECHA DE NACIMIENTO</td>
		<td>SEXO</td>
		<td>UBIGEO</td>
		<td>DIRECCION</td>
		<td>TIPO DE OFERENTE</td>
		<td>ESPECIALIDAD</td>
		<td>INICIO</td>
		<td>TERMINO</td>
		<td>TEMA DE ASISTENCIA TECNICA</td>
		<td>MUJERES CAPACITADAS</td>
		<td>VARONES CAPACITADOS</td>
		<td>CALIFICACION</td>
		<td>INICIATIVA</td>
		<td>NOMBRE DE LA ORGANIZACION</td>
		<td>DENOMINACION DEL PLAN DE NEGOCIO / LEMA DEL PLAN DE GESTION</td>
		<td>LINEA DE NEGOCIO</td>
		<td>OFICINA LOCAL</td>
	</tr>
</table>

</body>
</html>
