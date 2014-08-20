<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT ficha_contrato_ls.n_contrato, 
	ficha_contrato_ls.f_contrato, 
	ficha_contrato_ls.contenido, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno
FROM sys_bd_dependencia INNER JOIN ficha_contrato_ls ON sys_bd_dependencia.cod_dependencia = ficha_contrato_ls.cod_dependencia
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_contrato_ls.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_contrato_ls.n_documento
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE ficha_contrato_ls.cod_contrato='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


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

<div class="txt_titulo capa centrado">CONTRATO DE LOCACION DE SERVICIOS Nº <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato'])."-".$row['oficina'];?></div>
<br/>
<? echo $row['contenido'];?>
<p><br/></p>

<table width="90%" cellpadding="2" cellspacing="2">
	
	<tr class="centrado">
		<td width="35%">_______________________</td>
		<td width="30%"></td>
		<td width="35%">_______________________</td>
	</tr>
	
	<tr class="centrado">
		<td><? echo $row['nombres']." ".$row['apellidos'];?><br/><br/><strong>Jefe de la Oficina Local de <? echo $row['oficina'];?></strong></td>
		<td></td>
		<td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><br/><br/><strong>Locador</strong></td>
	</tr>
	
</table>

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>
</body>
</html>
