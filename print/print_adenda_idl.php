<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_adenda_idl.n_adenda, 
	pit_bd_ficha_adenda_idl.f_adenda, 
	pit_bd_ficha_adenda_idl.contenido, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_personal.n_documento AS dnis, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento AS dni, 
	sys_bd_cargo_directivo.descripcion AS cargo
FROM pit_bd_ficha_idl INNER JOIN pit_bd_ficha_adenda_idl ON pit_bd_ficha_idl.cod_ficha_idl = pit_bd_ficha_adenda_idl.cod_idl
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante AND org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_adenda_idl.cod_adenda='$cod'";
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

<!-- -->
<div class="capa txt_titulo centrado"><u>ADDENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?></u><br/>  AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – IDL – OL <? echo $row['oficina'];?> DE DONACIÓN SUJETO A CARGO PARA LA EJECUCION DE LA INVERSION PARA EL DESARROLLO LOCAL DE <br/>"<? echo $row['org'];?>"</div>
<p></p>
<!-- Inicio del contenido -->
<div>
<? echo $row['contenido'];?>
</div>
<!-- Termino del contenido -->
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="40%" align="center">_______________</td>
    <td width="20%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
  </tr>
  <tr class="txt_titulo">
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR><b>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></b></td>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><BR><b><? echo $row['cargo'];?> "<? echo $row['org'];?>"</b></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">



<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>