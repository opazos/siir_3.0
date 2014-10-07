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

<table border="1" cellpadding="1" cellspacing="1" align="center" class="mini">
	<tr class="txt_titulo centrado">
		<td>N. de contrato</td>
		<td>Tipo de Iniciativa</td>
		<td>Nombre de la organización</td>
		<td>Rubro</td>
		<td>Monto de aporte(S/.)</td>
		<td>Oficina local</td>
	</tr>
<?php
	$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_organizacion.nombre, 
	SUM(cif_bd_ficha_cif.premio_otro) AS aporte_otro, 
	sys_bd_dependencia.nombre AS oficina
FROM cif_bd_ficha_cif INNER JOIN cif_bd_concurso ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='004'	 
GROUP BY pit_bd_ficha_mrn.cod_mrn
ORDER BY oficina ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{
?>	
	<tr>
		<td class="centrado"><? echo numeracion($r1['n_contrato'])." - ".periodo($r1['f_contrato']);?></td>
		<td class="centrado">MRN</td>
		<td><? echo $r1['nombre'];?></td>
		<td class="centrado">CIF</td>
		<td class="derecha"><? echo number_format($r1['aporte_otro'],2);?></td>
		<td class="centrado"><? echo $r1['oficina'];?></td>
	</tr>
<?
}
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_organizacion.nombre, 
	SUM(cif_bd_ficha_cif.valor_2-cif_bd_ficha_cif.valor_1) AS aporte_otro, 
	sys_bd_dependencia.nombre AS oficina
FROM cif_bd_ficha_cif INNER JOIN cif_bd_concurso ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='004'	 
GROUP BY pit_bd_ficha_mrn.cod_mrn
ORDER BY oficina ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>	
	<tr>
		<td class="centrado"><? echo numeracion($r2['n_contrato'])." - ".periodo($r2['f_contrato']);?></td>
		<td class="centrado">MRN</td>
		<td><? echo $r2['nombre'];?></td>
		<td class="centrado">INCREMENTO PATRIMONIAL</td>
		<td class="derecha"><? echo number_format($r2['aporte_otro'],2);?></td>
		<td class="centrado"><? echo $r2['oficina'];?></td>
	</tr>
<?
}
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	SUM(ficha_sat.aporte_otro) AS aporte_otro
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN ficha_sat ON ficha_sat.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND ficha_sat.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='004'
GROUP BY pit_bd_ficha_mrn.cod_mrn
ORDER BY pit_bd_ficha_pit.f_contrato ASC, org_ficha_organizacion.nombre ASC, pit_bd_ficha_pit.n_contrato ASC, pit_bd_ficha_pit.f_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
?>	
	<tr>
		<td class="centrado"><? echo numeracion($r3['n_contrato'])." - ".periodo($r3['f_contrato']);?></td>
		<td class="centrado">MRN</td>
		<td><? echo $r3['nombre'];?></td>
		<td class="centrado">SAT</td>
		<td class="derecha"><? echo number_format($r3['aporte_otro'],2);?></td>
		<td class="centrado"><? echo $r3['oficina'];?></td>
	</tr>
<?
}
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	SUM(ficha_aag.aporte_otro) AS aporte_otro
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN ficha_aag ON ficha_aag.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND ficha_aag.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='004'
GROUP BY pit_bd_ficha_mrn.cod_mrn
ORDER BY pit_bd_ficha_pit.f_contrato ASC, org_ficha_organizacion.nombre ASC, pit_bd_ficha_pit.n_contrato ASC, pit_bd_ficha_pit.f_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
?>	
	<tr>
		<td class="centrado"><? echo numeracion($r4['n_contrato'])." - ".periodo($r4['f_contrato']);?></td>
		<td class="centrado">MRN</td>
		<td><? echo $r4['nombre'];?></td>
		<td class="centrado">APOYO A LA GESTION</td>
		<td class="derecha"><? echo number_format($r4['aporte_otro'],2);?></td>
		<td class="centrado"><? echo $r4['oficina'];?></td>
	</tr>
<?
}
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	SUM(ficha_vg.aporte_otro) AS aporte_otro
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN ficha_vg ON ficha_vg.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND ficha_vg.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='004'
GROUP BY pit_bd_ficha_mrn.cod_mrn
ORDER BY pit_bd_ficha_pit.f_contrato ASC, org_ficha_organizacion.nombre ASC, pit_bd_ficha_pit.n_contrato ASC, pit_bd_ficha_pit.f_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r5=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo numeracion($r5['n_contrato'])." - ".periodo($r5['f_contrato']);?></td>
		<td class="centrado">MRN</td>
		<td><? echo $r5['nombre'];?></td>
		<td class="centrado">VISITA GUIADA</td>
		<td class="derecha"><? echo number_format($r5['aporte_otro'],2);?></td>
		<td class="centrado"><? echo $r5['oficina'];?></td>
	</tr>
<?
}
$sql="";
?>	
</table>


</body>
</html>
