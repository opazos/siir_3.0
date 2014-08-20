<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_adenda_pdn.n_adenda, 
	pit_bd_ficha_adenda_pdn.f_adenda, 
	pit_bd_ficha_adenda_pdn.referencia, 
	pit_bd_ficha_adenda_pdn.f_inicio, 
	pit_bd_ficha_adenda_pdn.meses, 
	pit_bd_ficha_adenda_pdn.f_termino, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	org_ficha_organizacion.cod_tipo_doc, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_adenda_pdn.contenido
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Presidente
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN org_ficha_organizacion ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_adenda_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1 AND
pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//Tesorero
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN org_ficha_organizacion ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_adenda_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1 AND
pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);


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
<div class="capa txt_titulo centrado"><u>ADDENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?></u><br/>  DE DONACIÓN SUJETO A  CARGO ENTRE EL NEC-PROYECTO DE DESARROLLO SIERRA SUR II  Y LA ORGANIZACION <? echo $row['org'];?>, PARA COFINANCIAMIENTO DE SERVICIOS  ASISTENCIA TECNICA</div>
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
    <td align="center"><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno']."<br/>DNI N.".$r1['dni']."<br/> PRESIDENTE ";?></td>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno']."<br/>DNI N.".$r2['dni']."<br/> TESORERO ";?></td>
  </tr>
</table>


<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="40%" align="center">&nbsp;</td>
    <td width="20%" align="center">_______________</td>
    <td width="40%" align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR><b>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></b></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>