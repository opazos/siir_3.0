<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_liquidaciones.xls");
header("Pragma: no-cache");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="latin1">
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

	<table class="mini" border="1" cellpadding="1" cellspacing="1" align="center" width="90%">
		<tr class="txt_titulo centrado">
			<td>TIPO DE INICIATIVA</td>
			<td>N. CONTRATO</td>
			<td>FECHA DE CONTRATO</td>
			<td>NOMBRE DE LA ORGANIZACION</td>
			<td>OFICINA LOCAL</td>
			<td>N. DE DOCUMENTO</td>
			<td>TIPO DE DOCUMENTO</td>
			<td>FECHA</td>
			<td>ASUNTO</td>
			<td>FECHA DE RECEPCION</td>
			<td>N. DE ARCHIVADORES</td>
			<td>N. DE FOLIOS</td>
			<td>COMENTARIOS</td>
		</tr>
<?
	$sql="SELECT CONCAT(LPAD(gm_ficha_evento.n_contrato,4,'0'),' - ',DATE_FORMAT(gm_ficha_evento.f_presentacion,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	gm_ficha_evento.f_presentacion, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.nombre AS org
FROM gm_ficha_evento INNER JOIN bd_registra_liquida ON gm_ficha_evento.cod_ficha_gm = bd_registra_liquida.cod_iniciativa AND gm_ficha_evento.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gm_ficha_evento.cod_dependencia
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gm_ficha_evento.cod_tipo_iniciativa
	 INNER JOIN gm_ficha_contratante ON gm_ficha_contratante.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gm_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = gm_ficha_contratante.n_documento
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
?>		
	<tr>
		<td class="centrado"><? echo $f1['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f1['codigo_iniciativa'];?>/<? echo $f1['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_presentacion']);?></td>
		<td><? echo $f1['org'];?></td>
		<td class="centrado"><? echo $f1['oficina'];?></td>
		<td class="centrado"><? echo $f1['n_documento'];?></td>
		<td class="centrado"><? echo $f1['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_documento']);?></td>
		<td><? echo $f1['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f1['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f1['n_folio']);?></td>
		<td><? echo $f1['observaciones_de'];?></td>
	</tr>
<?
}

$sql="SELECT CONCAT(LPAD(pit_bd_ficha_pit.n_contrato,4,'0'),' - ',DATE_FORMAT(pit_bd_ficha_pit.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.observaciones_de, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_taz.nombre AS org, 
	bd_registra_liquida.f_recepcion_de
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f2['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f2['codigo_iniciativa'];?>/<? echo $f2['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_contrato']);?></td>
		<td><? echo $f2['org'];?></td>
		<td class="centrado"><? echo $f2['oficina'];?></td>
		<td class="centrado"><? echo $f2['n_documento'];?></td>
		<td class="centrado"><? echo $f2['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_documento']);?></td>
		<td><? echo $f2['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f2['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f2['n_folio']);?></td>
		<td><? echo $f2['observaciones_de'];?></td>
	</tr>
<?
}

$sql="SELECT CONCAT(LPAD(pit_bd_ficha_pdn.n_contrato,4,'0'),' - ',DATE_FORMAT(pit_bd_ficha_pdn.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.n_archivador, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	pit_bd_ficha_pdn.denominacion, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.observaciones_de, 
	org_ficha_organizacion.nombre AS org, 
	bd_registra_liquida.f_recepcion_de, 
	pit_bd_ficha_pdn.f_contrato, 
	sys_bd_tipo_iniciativa.codigo_iniciativa
FROM bd_registra_liquida INNER JOIN pit_bd_ficha_pdn ON bd_registra_liquida.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.n_contrato<>0 AND bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f3['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f3['codigo_iniciativa'];?>/<? echo $f3['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f3['f_contrato']);?></td>
		<td><? echo $f3['org'];?></td>
		<td class="centrado"><? echo $f3['oficina'];?></td>
		<td class="centrado"><? echo $f3['n_documento'];?></td>
		<td class="centrado"><? echo $f3['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f3['f_documento']);?></td>
		<td><? echo $f3['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f3['f_recepcion_de']);?></td>
		<td class="derecha"><? echo $f3['n_archivador'];?></td>
		<td class="derecha"><? echo $f3['n_folio'];?></td>
		<td><? echo $f3['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(pit_bd_ficha_pit.n_contrato,4,'0'),' - ',DATE_FORMAT(pit_bd_ficha_pit.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	pit_bd_ficha_pdn.denominacion, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.observaciones_de, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	bd_registra_liquida.f_recepcion_de, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_tipo_iniciativa.codigo_iniciativa
FROM bd_registra_liquida INNER JOIN pit_bd_ficha_pdn ON bd_registra_liquida.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.tipo=0 AND bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f4['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f4['codigo_iniciativa'];?>/<? echo $f4['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f4['f_contrato']);?></td>
		<td><? echo $f4['org'];?></td>
		<td class="centrado"><? echo $f4['oficina'];?></td>
		<td class="centrado"><? echo $f4['n_documento'];?></td>
		<td class="centrado"><? echo $f4['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f4['f_documento']);?></td>
		<td><? echo $f4['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f4['f_recepcion_de']);?></td>
		<td class="derecha"><? echo $f4['n_archivador'];?></td>
		<td class="derecha"><? echo $f4['n_folio'];?></td>
		<td><? echo $f4['observaciones'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(pit_bd_ficha_pit.n_contrato,4,'0'),' - ',DATE_FORMAT(pit_bd_ficha_pit.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.observaciones_de, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	bd_registra_liquida.f_recepcion_de, 
	org_ficha_organizacion.nombre AS org, 
	pit_bd_ficha_pit.f_contrato
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f5['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f5['codigo_iniciativa'];?>/<? echo $f5['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f5['f_contrato']);?></td>
		<td><? echo $f5['org'];?></td>
		<td class="centrado"><? echo $f5['oficina'];?></td>
		<td class="centrado"><? echo $f5['n_documento'];?></td>
		<td class="centrado"><? echo $f5['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f5['f_documento']);?></td>
		<td><? echo $f5['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f5['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f5['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f5['n_folio']);?></td>
		<td><? echo $f5['observaciones'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(pit_bd_ficha_idl.n_contrato,4,'0'),' - ',DATE_FORMAT(pit_bd_ficha_idl.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	pit_bd_ficha_idl.denominacion, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	pit_bd_ficha_idl.f_contrato
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = pit_bd_ficha_idl.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f6['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f6['codigo_iniciativa'];?>/<? echo $f6['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f6['f_contrato']);?></td>
		<td><? echo $f6['org'];?></td>
		<td class="centrado"><? echo $f6['oficina'];?></td>
		<td class="centrado"><? echo $f6['n_documento'];?></td>
		<td class="centrado"><? echo $f6['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f6['f_documento']);?></td>
		<td><? echo $f6['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f6['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f6['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f6['n_folio']);?></td>
		<td><? echo $f6['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(clar_bd_evento_clar.n_contrato,4,'0'),'-',DATE_FORMAT(clar_bd_evento_clar.f_presentacion,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.observaciones_de, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	clar_bd_evento_clar.f_presentacion, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa
FROM clar_bd_evento_clar INNER JOIN bd_registra_liquida ON clar_bd_evento_clar.cod_clar = bd_registra_liquida.cod_iniciativa AND clar_bd_evento_clar.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN clar_bd_ficha_contratante ON clar_bd_ficha_contratante.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = clar_bd_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = clar_bd_ficha_contratante.n_documento
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f7['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f7['codigo_iniciativa'];?>/<? echo $f7['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f7['f_presentacion']);?></td>
		<td><? echo $f7['org'];?></td>
		<td class="centrado"><? echo $f7['oficina'];?></td>
		<td class="centrado"><? echo $f7['n_documento'];?></td>
		<td class="centrado"><? echo $f7['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f7['f_documento']);?></td>
		<td><? echo $f7['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f7['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f7['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f7['n_folio']);?></td>
		<td><? echo $f7['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(ml_promocion_c.n_contrato,4,'0'),' - ',DATE_FORMAT(ml_promocion_c.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	ml_promocion_c.f_contrato
FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = ml_promocion_c.cod_evento
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f8['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f8['codigo_iniciativa'];?>/<? echo $f8['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f8['f_contrato']);?></td>
		<td><? echo $f8['org'];?></td>
		<td class="centrado"><? echo $f8['oficina'];?></td>
		<td class="centrado"><? echo $f8['n_documento'];?></td>
		<td class="centrado"><? echo $f8['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f8['f_documento']);?></td>
		<td><? echo $f8['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f8['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f8['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f8['n_folio']);?></td>
		<td><? echo $f8['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(ml_bd_contrato_vg.n_contrato,4,'0'),' - ',DATE_FORMAT(ml_bd_contrato_vg.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.nombre AS org, 
	ml_bd_contrato_vg.f_contrato
FROM org_ficha_organizacion INNER JOIN ml_bd_contrato_vg ON org_ficha_organizacion.cod_tipo_doc = ml_bd_contrato_vg.cod_tipo_doc AND org_ficha_organizacion.n_documento = ml_bd_contrato_vg.n_documento
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = ml_bd_contrato_vg.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = ml_bd_contrato_vg.cod_contrato
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f9=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f9['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f9['codigo_iniciativa'];?>/<? echo $f9['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($f9['f_contrato']);?></td>
		<td><? echo $f9['org'];?></td>
		<td class="centrado"><? echo $f9['oficina'];?></td>
		<td class="centrado"><? echo $f9['n_documento'];?></td>
		<td class="centrado"><? echo $f9['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($f9['f_documento']);?></td>
		<td><? echo $f9['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($f9['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($f9['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($f9['n_folio']);?></td>
		<td><? echo $f9['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(ml_pf.n_contrato,4,'0'),' - ',DATE_FORMAT(ml_pf.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	ml_pf.f_contrato, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN bd_registra_liquida ON bd_registra_liquida.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa AND bd_registra_liquida.cod_iniciativa = ml_pf.cod_evento
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $r1['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $r1['codigo_iniciativa'];?>/<? echo $r1['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_contrato']);?></td>
		<td><? echo $r1['org'];?></td>
		<td class="centrado"><? echo $r1['oficina'];?></td>
		<td class="centrado"><? echo $r1['n_documento'];?></td>
		<td class="centrado"><? echo $r1['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_documento']);?></td>
		<td><? echo $r1['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($r1['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($r1['n_folio']);?></td>
		<td><? echo $r1['observaciones_de'];?></td>
	</tr>
<?
}
$sql="SELECT CONCAT(LPAD(gcac_bd_evento_gc.n_contrato,4,'0'),' - ',DATE_FORMAT(gcac_bd_evento_gc.f_contrato,'%Y')) AS n_contrato, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_liquidacion.descripcion AS estado, 
	bd_registra_liquida.cod_liquida, 
	bd_registra_liquida.n_documento, 
	bd_registra_liquida.tipo_documento, 
	bd_registra_liquida.f_documento, 
	bd_registra_liquida.asunto, 
	bd_registra_liquida.f_recepcion_de, 
	bd_registra_liquida.n_archivador, 
	bd_registra_liquida.n_folio, 
	bd_registra_liquida.observaciones_de, 
	org_ficha_organizacion.nombre AS org, 
	gcac_bd_evento_gc.f_contrato, 
	sys_bd_tipo_iniciativa.codigo_iniciativa
FROM gcac_bd_evento_gc INNER JOIN bd_registra_liquida ON gcac_bd_evento_gc.cod_evento_gc = bd_registra_liquida.cod_iniciativa AND gcac_bd_evento_gc.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registra_liquida.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_liquidacion ON sys_bd_estado_liquidacion.cod = bd_registra_liquida.cod_estado_liquidacion
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_registra_liquida.cod_estado_liquidacion='001'
ORDER BY bd_registra_liquida.f_recepcion_de ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $r2['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $r2['codigo_iniciativa'];?>/<? echo $r2['n_contrato'];?></td>
		<td class="centrado"><? echo fecha_normal($r2['f_contrato']);?></td>
		<td><? echo $r2['org'];?></td>
		<td class="centrado"><? echo $r2['oficina'];?></td>
		<td class="centrado"><? echo $r2['n_documento'];?></td>
		<td class="centrado"><? echo $r2['tipo_documento'];?></td>
		<td class="centrado"><? echo fecha_normal($r2['f_documento']);?></td>
		<td><? echo $r2['asunto'];?></td>
		<td class="centrado"><? echo fecha_normal($r2['f_recepcion_de']);?></td>
		<td class="derecha"><? echo number_format($r2['n_archivador']);?></td>
		<td class="derecha"><? echo number_format($r2['n_folio']);?></td>
		<td><? echo $r2['observaciones_de'];?></td>
	</tr>
<?
}
?>		

	</table>

<?
if ($modo<>excell)
{
?>
<p><br/></p>

<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="report_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>

</body>
</html>
