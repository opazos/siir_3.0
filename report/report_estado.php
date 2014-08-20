<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_iniciativas.xls");
header("Pragma: no-cache");
}
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

<table class="mini" align="center" cellspacing="1" cellspacing="1" border="1" width="2000px">
	<tr class="txt_titulo" align="center">
		<td>TIPO INICIATIVA</td>
		<td>TIPO DOCUMENTO</td>
		<td>NUMERO DOCUMENTO</td>
		<td>NOMBRE DE LA ORGANIZACION</td>
		<td>TIPO DE ORGANIZACION</td>
		<td>DEPARTAMENTO</td>
		<td>PROVINCIA</td>
		<td>DISTRITO</td>
		<td>OFICINA</td>
		<td>N. CONTRATO</td>
		<td>FECHA CONTRATO</td>
		<td>DURACION EN MESES</td>
		<td>FECHA DE TERMINO</td>
		<td>PLAZO VENCIDO CONTRATO</td>
		<td>APORTE PROYECTO</td>
		<td>APORTE ORGANIZACION</td>
		<td>ESTADO</td>
		<td>N. ADENDA</td>
		<td>FECHA ADENDA</td>
		<td>REFERENCIA</td>
		<td>TIPO DE ADENDA</td>
		<td>FECHA DE INICIO</td>
		<td>DURACION EN MESES</td>
		<td>FECHA DE TERMINO</td>
		<td>PLAZO VENCIDO ADENDA</td>
	</tr>

	<?
		$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_pit.mancomunidad, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	estado_1.descripcion AS estado, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.referencia, 
	pit_bd_ficha_adenda.cod_tipo_adenda, 
	pit_bd_ficha_adenda.f_inicio, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino AS f_liquida, 
	sys_bd_estado_iniciativa.descripcion AS estado_2, 
	estado_1.cod_estado_iniciativa
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pit ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_estado_iniciativa estado_1 ON estado_1.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_adenda.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE pit_bd_ficha_pit.n_contrato<>0
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
	?>
		<tr>
			<td class="centrado"><? echo $r1['codigo'];?> <? if ($r1['mancomunidad']==0) echo "- PGRN"; else echo "- PDN";?></td>
			<td class="centrado"><? echo $r1['tipo_doc'];?></td>
			<td class="centrado"><? echo $r1['n_documento'];?></td>
			<td><? echo $r1['nombre'];?></td>
			<td class="centrado"><? echo $r1['tipo_org'];?></td>
			<td class="centrado"><? echo $r1['departamento'];?></td>
			<td class="centrado"><? echo $r1['provincia'];?></td>
			<td class="centrado"><? echo $r1['distrito'];?></td>
			<td class="centrado"><? echo $r1['oficina'];?></td>
			<td class="centrado"><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato']);?></td>
			<td class="centrado"><? echo fecha_normal($r1['f_contrato']);?></td>
			<td class="centrado"><? echo $r1['mes'];?></td>
			<td class="centrado"><? echo fecha_normal($r1['f_termino']);?></td>
			<td class="centrado"><? if ($fecha_hoy>$r1['f_termino'] and $r1['f_termino']<>'0000-00-00' and $r1['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO";?></td>
			<td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($r1['aporte_org'],2);?></td>
			<td class="centrado"><? echo $r1['estado'];?></td>
			<td class="centrado"><? if ($r1['n_adenda']<>NULL) echo numeracion($r1['n_adenda']);?></td>
			<td class="centrado"><? echo fecha_normal($r1['f_adenda']);?></td>
			<td><? echo $r1['referencia'];?></td>
			<td class="centrado"><? if ($r1['cod_tipo_adenda']==1) echo "PLAZO"; else echo "PLAZO Y PRESUPUESTO";?></td>
			<td class="centrado"><? echo fecha_normal($r1['f_inicio']);?></td>
			<td class="centrado"><? echo $r1['meses'];?></td>
			<td class="centrado"><? echo fecha_normal($r1['f_liquida']);?></td>
			<td class="centrado"><? if ($fecha_hoy>$r1['f_liquida'] and $r1['f_liquida']<>'0000-00-00' and $r1['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO"; elseif($r1['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO";?></td>
		</tr>
	<?
		}
		$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.f_inicio, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	pit_bd_ficha_mrn.cod_estado_iniciativa, 
	estado_1.descripcion AS estado, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.cod_tipo_adenda, 
	pit_bd_ficha_adenda.f_inicio AS f_inicio_adenda, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino AS f_liquida, 
	sys_bd_estado_iniciativa.descripcion AS estado_liquida, 
	pit_bd_ficha_adenda.referencia
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_mrn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa estado_1 ON estado_1.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_adenda.cod_estado_iniciativa
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r2=mysql_fetch_array($result))
	{
	?>
	<tr>
	<td class="centrado"><? echo $r2['codigo'];?></td>
	<td class="centrado"><? echo $r2['tipo_doc'];?></td>
	<td class="centrado"><? echo $r2['n_documento'];?></td>
	<td><? echo $r2['nombre'];?></td>
	<td class="centrado"><? echo $r2['tipo_org'];?></td>
	<td class="centrado"><? echo $r2['departamento'];?></td>
	<td class="centrado"><? echo $r2['provincia'];?></td>
	<td class="centrado"><? echo $r2['distrito'];?></td>
	<td class="centrado"><? echo $r2['oficina'];?></td>
	<td class="centrado"><? echo numeracion($r2['n_contrato'])."-".periodo($r2['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($r2['f_contrato']);?></td>
	<td class="centrado"><? echo $r2['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($r2['f_termino']);?></td>
	<td class="centrado"><? if ($fecha_hoy>$r2['f_termino'] and $r2['f_termino']<>'0000-00-00' and $r2['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO";?></td>
	<td class="derecha"><? echo number_format($r2['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($r2['aporte_org'],2);?></td>
	<td class="centrado"><? echo $r2['estado'];?></td>
	<td class="centrado"><? if ($r2['n_adenda']<>NULL) echo numeracion($r2['n_adenda']);?></td>
	<td class="centrado"><? echo fecha_normal($r2['f_adenda']);?></td>
	<td><? echo $r2['referencia'];?></td>
	<td class="centrado"><? if ($r2['cod_tipo_adenda']==1) echo "PLAZO"; else echo "PLAZO Y PRESUPUESTO";?></td>
	<td class="centrado"><? echo fecha_normal($r2['f_inicio_adenda']);?></td>
	<td class="centrado"><? echo $r2['meses'];?></td>
	<td class="centrado"><? echo fecha_normal($r2['f_liquida']);?></td>
	<td class="centrado"><? if ($fecha_hoy>$r2['f_liquida'] and $r2['f_liquida']<>'0000-00-00' and $r2['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO"; elseif($r2['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO";?></td>
	</tr>
	<?	
	}
	$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_pdn.n_documento_org AS n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pdn.cod_estado_iniciativa, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.referencia, 
	pit_bd_ficha_adenda.cod_tipo_adenda, 
	pit_bd_ficha_adenda.f_inicio AS f_inicio_adenda, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino AS f_liquida
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_pdn.cod_tipo_atf_pdn=1
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r3=mysql_fetch_array($result))
		{
	?>
	<tr>
		<td class="centrado"><? echo $r3['codigo']."/PIT";?></td>
		<td class="centrado"><? echo $r3['tipo_doc'];?></td>
		<td class="centrado"><? echo $r3['n_documento'];?></td>
		<td><? echo $r3['nombre'];?></td>
		<td class="centrado"><? echo $r3['tipo_org'];?></td>
		<td class="centrado"><? echo $r3['departamento'];?></td>
		<td class="centrado"><? echo $r3['provincia'];?></td>
		<td class="centrado"><? echo $r3['distrito'];?></td>
		<td class="centrado"><? echo $r3['oficina'];?></td>
		<td class="centrado"><? echo numeracion($r3['n_contrato'])."-".periodo($r3['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($r3['f_contrato']);?></td>
		<td class="centrado"><? echo $r3['mes'];?></td>
		<td class="centrado">
			<? 
			$fecha_1 = $r3['f_contrato'];
			$mes_1= $r3['mes'];
			$nuevafecha_1 = strtotime ( "+ ".$mes_1." month" , strtotime ( $fecha_1 ) ) ;
			$nuevafecha_1 = date ( 'Y-m-d' , $nuevafecha_1 );
			echo fecha_normal($nuevafecha_1);
			?>
		</td>
		<td class="centrado"><? if ($fecha_hoy>$nuevafecha_1 and $nuevafecha_1<>'0000-00-00' and $r3['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO";?></td>
		<td class="derecha"><? echo number_format($r3['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($r3['aporte_org'],2);?></td>
		<td class="centrado"><? echo $r3['estado'];?></td>
		<td class="centrado"><? if ($r3['n_adenda']<>NULL) echo numeracion($r3['n_adenda']);?></td>
		<td class="centrado"><? if ($r3['n_adenda']<>NULL) echo fecha_normal($r3['f_adenda']);?></td>
		<td><? echo $r3['referencia'];?></td>
		<td class="centrado"><? if ($r3['cod_tipo_adenda']==1) echo "PLAZO"; elseif($r3['cod_tipo_iniciativa']==2) echo "PLAZO Y PRESUPUESTO";?></td>
		<td class="centrado"><?if ($r3['n_adenda']<>NULL) echo fecha_normal($r3['f_inicio_adenda']);?></td>
		<td class="centrado"><? echo $r3['meses'];?></td>
		<td class="centrado"><?if ($r3['n_adenda']<>NULL) echo fecha_normal($r3['f_liquida']);?></td>
		<td class="centrado"><? if ($fecha_hoy>$r3['f_liquida'] and $r3['f_liquida']<>'0000-00-00' and $r3['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO"; elseif($r3['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO";?></td>
	</tr>
	<?		
		}
		$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_pdn.n_documento_org AS n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pdn.cod_estado_iniciativa, 
	pit_bd_ficha_adenda_pdn.n_adenda, 
	pit_bd_ficha_adenda_pdn.f_adenda, 
	pit_bd_ficha_adenda_pdn.referencia, 
	pit_bd_ficha_adenda_pdn.f_inicio AS f_inicio_adenda, 
	pit_bd_ficha_adenda_pdn.meses, 
	pit_bd_ficha_adenda_pdn.f_termino AS f_liquida
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_adenda_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pdn.n_contrato ASC, pit_bd_ficha_pdn.f_contrato ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r4=mysql_fetch_array($result))
	{
	?>
	<tr>
		<td class="centrado"><? echo $r4['codigo']." SUELTO";?></td>
		<td class="centrado"><? echo $r4['tipo_doc'];?></td>
		<td class="centrado"><? echo $r4['n_documento'];?></td>
		<td><? echo $r4['nombre'];?></td>
		<td class="centrado"><? echo $r4['tipo_org'];?></td>
		<td class="centrado"><? echo $r4['departamento'];?></td>
		<td class="centrado"><? echo $r4['provincia'];?></td>
		<td class="centrado"><? echo $r4['distrito'];?></td>
		<td class="centrado"><? echo $r4['oficina'];?></td>
		<td class="centrado"><? echo numeracion($r4['n_contrato'])."-".periodo($r4['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($r4['f_contrato']);?></td>
		<td class="centrado"><? echo $r4['mes'];?></td>
		<td class="centrado"><? echo fecha_normal($r4['f_termino']);?></td>
		<td class="centrado"><? if ($fecha_hoy>$r4['f_termino'] and $r4['f_termino']<>'0000-00-00' and $r4['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO";?></td>
		<td class="derecha"><? echo number_format($r4['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($r4['aporte_org'],2);?></td>
		<td class="centrado"><? echo $r4['estado'];?></td>
		<td class="centrado"><? if ($r4['n_adenda']<>NULL) echo numeracion($r4['n_adenda']);?></td>
		<td class="centrado"><? if ($r4['n_adenda']<>NULL) echo fecha_normal($r4['f_adenda']);?></td>
		<td><? echo $r4['referencia'];?></td>
		<td class="centrado"><? if ($r4['n_adenda']<>NULL) echo "PLAZO";?></td>
		<td class="centrado"><? if ($r4['n_adenda']<>NULL) echo fecha_normal($r4['f_inicio_adenda']);?></td>
		<td class="centrado"><? echo $r4['meses'];?></td>
		<td class="centrado"><? if($r4['n_adenda']<>NULL) echo fecha_normal($r4['f_liquida']);?></td>
		<td><? if ($fecha_hoy>$r4['f_liquida'] and $r4['f_liquida']<>'0000-00-00' and $r4['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO"; elseif($r4['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO";?></td>
	</tr>
	<?	
	}
	$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_pdn.n_documento_org AS n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pdn.cod_estado_iniciativa, 
	clar_ampliacion_pit.f_ampliacion, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_adenda.n_adenda,
	pit_bd_ficha_adenda.cod_tipo_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.referencia, 
	pit_bd_ficha_adenda.f_inicio AS f_inicio_adenda, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino AS f_liquida
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC, pit_bd_ficha_pit.f_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r5=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $r5['codigo']."/AMPLIACION";?></td>
		<td class="centrado"><? echo $r5['tipo_doc'];?></td>
		<td class="centrado"><? echo $r5['n_documento'];?></td>
		<td><? echo $r5['nombre'];?></td>
		<td class="centrado"><? echo $r5['tipo_org'];?></td>
		<td class="centrado"><? echo $r5['departamento'];?></td>
		<td class="centrado"><? echo $r5['provincia'];?></td>
		<td class="centrado"><? echo $r5['distrito'];?></td>
		<td class="centrado"><? echo $r5['oficina'];?></td>
		<td class="centrado"><? echo numeracion($r5['n_contrato'])."-".periodo($r5['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($r5['f_ampliacion']);?></td>
		<td class="centrado"><? echo $r5['mes'];?></td>
		<td class="centrado"><? echo fecha_normal($r5['f_termino']);?></td>
		<td class="centrado"><? if ($fecha_hoy>$r5['f_termino'] and $r5['f_termino']<>'0000-00-00' and $r5['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO";?></td>
		<td class="derecha"><? echo number_format($r5['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($r5['aporte_org'],2);?></td>
		<td class="centrado"><? echo $r5['estado'];?></td>
		<td class="centrado"><? if ($r5['n_adenda']<>NULL) echo numeracion($r5['n_adenda']);?></td>
		<td class="centrado"><? if ($r5['n_adenda']<>NULL) echo fecha_normal($r5['f_adenda']);?></td>
		<td><? echo $r5['referencia'];?></td>
		<td class="centrado"><? if($r5['cod_tipo_adenda']==1) echo "PLAZO"; elseif($r5['cod_tipo_adenda']==2) echo "PLAZO Y PRESUPUESTO";?></td>
		<td class="centrado"><? if ($r5['n_adenda']<>NULL) echo fecha_normal($r5['f_inicio_adenda']);?></td>
		<td class="centrado"><? echo $r5['meses'];?></td>
		<td class="centrado"><? if ($r5['n_adenda']<>NULL) echo fecha_normal($r5['f_liquida']);?></td>
		<td class="centrado"><? if ($fecha_hoy>$r5['f_liquida'] and $r5['f_liquida']<>'0000-00-00' and $r5['cod_estado_iniciativa']<>004) echo "PLAZO VENCIDO"; elseif($r5['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO";?></td>
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
	<a href="report_estado.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>

</body>
</html>
