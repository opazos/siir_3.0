<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
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
<!-- Evaluacion  de campo -->
<?php
$num=0;
$sql="SELECT bd_ficha_cfinal.cod_participante, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento AS dni, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN bd_jurado_cfinal ON bd_jurado_cfinal.cod_concurso = bd_ficha_cfinal.cod_concurso
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_ficha_cfinal.cod_categoria=3 AND
bd_ficha_cfinal.cod_concurso='$cod'
ORDER BY org ASC, clar_bd_miembro.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
?>
<? include("encabezado.php");?>
<div class="capa txt_titulo centrado">
<p><u>EVALUACION DE CAMPO - CATEGORIA C (PDN)</u></p>
<p>OL - <? echo $f1['oficina'];?></p>
<p>ORGANIZACION PDN: <? echo $f1['org'];?></p>
</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
	<tr class="txt_titulo centrado">
		<td width="70%">Criterios</td>
		<td width="20%">Topes de puntaje</td>
		<td width="10%">Calificación</td>
	</tr>
	<tr class="txt_titulo">
		<td colspan="3">PLAN DE NEGOCIOS</td>
	</tr>
	<tr>
		<td>Nivel de resultados obtenidos por el PDN: calidad del producto o servicio, articulación a mercados (ventas), manejo de información (precios, costos, otros), % de participación de familias, otros    </td>
		<td class="centrado">01 a 15 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Nivel de coordinación entre los diferentes actores relacionados con el PDN: Junta Directiva,  Gestor, familias participantes, otros.</td>
		<td class="centrado">01 a 10 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Señales mostradas por la organización sobre gestiones desarrolladas que permitan la continuidad de PDN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
		<td class="centrado">01 a 10 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Evidencias sobre  innovaciones introducidas  en el PDN</td>
		<td class="centrado">01 a 08 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PDN</td>
		<td class="centrado">01 a 07 puntos</td>
		<td></td>
	</tr>				
			

	<tr class="txt_titulo">
		<td>TOTAL</td>
		<td class="centrado">50 PUNTOS</td>
		<td></td>
	</tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    </td>
  </tr>
  <tr>
    <td>Apellidos y nombres del Jurado</td>
    <td>:</td>
    <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno']."-".$f1['cargo'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $f1['dni'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
$numa=$num;
$sql="SELECT bd_ficha_cfinal.cod_participante, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento AS dni, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN bd_jurado_cfinal ON bd_jurado_cfinal.cod_concurso = bd_ficha_cfinal.cod_concurso
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_ficha_cfinal.cod_categoria=3 AND
bd_ficha_cfinal.cod_concurso='$cod'
ORDER BY org ASC, clar_bd_miembro.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$numa++
?>
<!-- Evaluación Publica -->

<? include("encabezado.php");?>
<div class="capa txt_titulo centrado">
<p><u>PRESENTACION PUBLICA - CATEGORIA C (PDN)</u></p>
<p>OL - <? echo $f2['oficina'];?></p>
<p>ORGANIZACION PDN: <? echo $f2['org'];?></p>
</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
	<tr class="txt_titulo centrado">
		<td width="70%">Criterios</td>
		<td width="20%">Topes de puntaje</td>
		<td width="10%">Calificación</td>
	</tr>
			
	<tr class="txt_titulo">
		<td colspan="3">PLAN DE NEGOCIOS</td>
	</tr>
	<tr>
		<td>Presentación de resultados obtenidos por el (los) PDN: cambios logrados en su producto o servicio al acceder a servicios de asistencia técnica,  beneficios de la visita guiada y de la  participación en ferias, nivel de conocimiento sobre precios, costos, articulación a mercados (ventas),  paneles fotográficos, muestra de productos.  </td>
		<td class="centrado">01 a 15 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Capacidad expositiva, claridad, coherencia, participación de la mujer, jóvenes, manejo de tiempos</td>
		<td class="centrado">01 a 15 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Evidencias presentadas por la organización sobre gestiones desarrolladas que permitan la continuidad de PDN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
		<td class="centrado">01 a 10 puntos</td>
		<td></td>
	</tr>
	<tr>
		<td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PDN</td>
		<td class="centrado">01 a 10 puntos</td>
		<td></td>
	</tr>			
			

	<tr class="txt_titulo">
		<td>TOTAL</td>
		<td class="centrado">50 PUNTOS</td>
		<td></td>
	</tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    </td>
  </tr>
  <tr>
    <td>Apellidos y nombres del Jurado</td>
    <td>:</td>
    <td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno']."-".$f2['cargo'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $f2['dni'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $numa;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_b" class="secondary button oculto">Finalizar</a>
</div>


</body>
</html>
