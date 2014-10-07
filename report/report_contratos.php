<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();


if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_contratos.xls");
header("Pragma: no-cache");
}


//1.- Buscamos la oficina local
$sql="SELECT sys_bd_personal.cod_dependencia, 
	sys_bd_personal.sesion, 
	sys_bd_personal.clave, 
	sys_bd_personal.correo, 
	sys_bd_personal.usuario, 
	sys_bd_personal.telefono, 
	sys_bd_personal.cod_tipo_usuario, 
	sys_bd_personal.cod_cargo, 
	sys_bd_personal.apellido, 
	sys_bd_personal.nombre, 
	sys_bd_personal.n_documento, 
	sys_bd_personal.cod_tipo_doc, 
	sys_bd_dependencia.nombre AS oficina
FROM sys_bd_dependencia INNER JOIN sys_bd_personal ON sys_bd_dependencia.cod_dependencia = sys_bd_personal.cod_dependencia
WHERE md5(sys_bd_personal.n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


?>
<!DOCTYPE html><html>
  <head>
    <meta content="text/html;charset=UTF-8" http-equiv="Content-Type">
    <meta charset="utf-8">
    <title>::Vista Preeliminar::</title>
    <!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
    <style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
   @page { size: A3 landscape; }
</style>
<!-- Fin -->
  </head>
  <body>

  
<table width="2000px" border="1" cellpadding="1" cellspacing="1" align="center" class="mini">
	
	<tr class="txt_titulo centrado">
		<td colspan="27">REPORTE DE CONTRATOS AÑO <? echo $anio;?> - OFICINA LOCAL <? echo $row['oficina'];?></td>
	</tr>
	
	<tr class="txt_titulo centrado">
		<td>TIPO INICIATIVA</td>
		<td>Nº DESEMBOLSO</td>
		<td>Nº CONTRATO</td>
		<td>FECHA DE CONTRATO</td>
		<td>DURACIÓN EN MESES</td>
		<td>FECHA DE TERMINO</td>
		<td>APORTE PDSS (S/.)</td>
		<td>APORTE ORG (S/.)</td>
		<td>MONTO TOTAL CONTRATO (S/.)</td>
		<td>TIPO DE DOCUMENTO</td>
		<td>Nº DOCUMENTO</td>
		<td>NOMBRE DE LA ORGANIZACION</td>
		<td>OFICINA LOCAL</td>
		<td>Nº SOLICITUD</td>
		<td>Nº ATF</td>
		<td>MONTO<br> DESEMBOLSADO <br>PDSS (S/.)</td>
		<td>APORTE<br> ORGANIZACION (S/.)</td>
		<td>Nº CUENTA</td>
		<td>BANCO</td>
		<td>Nº VOUCHER</td>
		<td>EVENTO CLAR</td>
		<td>FECHA DE CALIFICACION DE CAMPO</td>
		<td>FECHA DEL EVENTO CLAR</td>
		<td>NOMBRE DEL EVENTO</td>
		<td>Nº ACTA</td>
		<td>OFICINA LOCAL</td>
		<td>ESTADO</td>
	</tr>
	
	
<!-- Eventos CLAR -->	
<?php
if ($row['cod_dependencia']==001)
{
$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.n_contrato, 
	clar_bd_evento_clar.f_presentacion, 
	SUM(clar_bd_ficha_presupuesto.costo_total) AS aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.n_atf, 
	clar_bd_evento_clar.estado, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	clar_bd_ficha_contratante.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi
FROM clar_bd_ficha_presupuesto INNER JOIN clar_bd_evento_clar ON clar_bd_ficha_presupuesto.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_contratante ON clar_bd_ficha_contratante.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = clar_bd_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = clar_bd_ficha_contratante.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = clar_bd_ficha_contratante.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE clar_bd_evento_clar.n_contrato<>0 AND
clar_bd_evento_clar.f_presentacion  BETWEEN '$anio-01-01'AND '$anio-12-31' 
GROUP BY clar_bd_evento_clar.cod_clar
ORDER BY clar_bd_evento_clar.cod_dependencia ASC, clar_bd_evento_clar.f_presentacion ASC, clar_bd_evento_clar.n_contrato ASC";
}
else
{
$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.n_contrato, 
	clar_bd_evento_clar.f_presentacion, 
	SUM(clar_bd_ficha_presupuesto.costo_total) AS aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.n_atf, 
	clar_bd_evento_clar.estado, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	clar_bd_ficha_contratante.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi
FROM clar_bd_ficha_presupuesto INNER JOIN clar_bd_evento_clar ON clar_bd_ficha_presupuesto.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_contratante ON clar_bd_ficha_contratante.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = clar_bd_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = clar_bd_ficha_contratante.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = clar_bd_ficha_contratante.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE clar_bd_evento_clar.n_contrato<>0 AND
clar_bd_evento_clar.f_presentacion  BETWEEN '$anio-01-01'AND '$anio-12-31' AND
clar_bd_evento_clar.cod_dependencia='".$row['cod_dependencia']."'
GROUP BY clar_bd_evento_clar.cod_clar
ORDER BY clar_bd_evento_clar.cod_dependencia ASC, clar_bd_evento_clar.f_presentacion ASC, clar_bd_evento_clar.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado">CLAR</td>
		<td class="centrado">PRIMERO</td>
		<td class="centrado"><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_presentacion']);?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_presentacion']);?></td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
		<td class="derecha"><? echo number_format($r1[aporte_pdss],2);?></td>
		<td class="derecha">0.00</td>
		<td class="derecha"><? echo number_format($r1[aporte_pdss],2);?></td>
		<td class="centrado"><? echo $r1['tipo_doc'];?></td>
		<td class="centrado"><? echo $r1['n_documento'];?></td>
		<td><? echo $r1['nombre'];?></td>
		<td class="centrado"><? echo $r1['oficina'];?></td>
		<td class="centrado"><? echo numeracion($r1['n_atf']);?></td>
		<td class="centrado"><?echo numeracion($r1['n_atf']);?></td>
		<td class="derecha"><? echo number_format($r1[aporte_pdss],2);?></td>
		<td class="derecha">0.00</td>
		<td class="centrado"><? echo $r1['n_cuenta'];?></td>
		<td class="centrado"><? echo $r1['ifi'];?></td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
		<td>-</td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
		<td class="centrado">-</td>
	</tr>
<?php
}
?>	
<!-- PIT - Primer desembolso -->	
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	olp.nombre AS oficina_1, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_pit.n_atf, 
	clar_atf_pit.monto_desembolsado, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	pit_bd_ficha_pit.monto_organizacion, 
	pit_bd_ficha_pit.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_acta.n_acta, 
	clar_bd_evento_clar.f_campo1, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pit.cod_estado_iniciativa
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN clar_atf_pit ON clar_atf_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pit.cod_clar
	 LEFT OUTER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia olp ON olp.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.f_contrato BETWEEN '$anio-01-01'AND '$anio-12-31'
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	olp.nombre AS oficina_1, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_pit.n_atf, 
	clar_atf_pit.monto_desembolsado, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	pit_bd_ficha_pit.monto_organizacion, 
	pit_bd_ficha_pit.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_acta.n_acta, 
	clar_bd_evento_clar.f_campo1, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pit.cod_estado_iniciativa
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN clar_atf_pit ON clar_atf_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pit.cod_clar
	 LEFT OUTER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia olp ON olp.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
olp.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_pit.f_contrato BETWEEN '$anio-01-01'AND '$anio-12-31'
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado">PIT</td>
		<td class="centrado">PRIMERO</td>
		<td class="centrado"><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fila['f_contrato']);?></td>
		<td class="centrado"><? echo $fila['mes'];?></td>
		<td class="centrado"><? echo fecha_normal($fila['f_termino']);?></td>
		<td class="derecha"><? echo number_format($fila['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($fila['aporte_org'],2);?></td>
		<td class="derecha"><? echo number_format($fila['aporte_pdss']+$fila['aporte_org'],2);?></td>
		<td class="centrado"><? echo $fila['tipo_doc'];?></td>
		<td class="centrado"><? echo $fila['n_documento'];?></td>
		<td><? echo $fila['nombre'];?></td>
		<td class="centrado"><? echo $fila['oficina_1'];?></td>
		<td class="centrado"><? echo numeracion($fila['n_solicitud']);?></td>
		<td class="centrado"><? if ($fila['n_atf']==0) echo "-"; else echo numeracion($fila['n_atf']);?></td>
		<td class="derecha"><? echo number_format($fila['monto_desembolsado'],2);?></td>
		<td class="derecha"><? echo number_format($fila['monto_organizacion'],2);?></td>
		<td class="centrado"><? echo "Nº ".$fila['n_cuenta'];?></td>
		<td class="centrado"><? echo $fila['banco'];?></td>
		<td class="centrado"><? echo $fila['n_voucher'];?></td>
		<td class="centrado"><? echo numeracion($fila['cod_clar'])."-".periodo($fila['f_evento']);?></td>
		<td class="centrado"><? echo fecha_normal($fila['f_campo1']);?></td>
		<td class="centrado"><? echo fecha_normal($fila['f_evento']);?></td>
		<td><? echo $fila['clar'];?></td>
		<td class="centrado"><? echo numeracion($fila['n_acta']);?></td>
		<td class="centrado"><? echo $fila['oficina_2'];?></td>
		<td class="centrado"><? if ($fila['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>
	</tr>
<?
}
?>
	
<! -- PIT segundo desembolso -->	
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pit_sd.n_atf, 
	clar_atf_pit_sd.monto_desembolsado, 
	pit_bd_ficha_pit.monto_organizacion_2, 
	pit_bd_ficha_pit.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	pit_bd_ficha_pit.cod_estado_iniciativa
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN clar_atf_pit_sd ON clar_atf_pit_sd.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pit_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_taz.cod_dependencia AND clar_atf_pit_sd.cod_ficha_sd = clar_bd_ficha_sd_pit.cod_ficha_sd
WHERE clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pit_sd.n_atf, 
	clar_atf_pit_sd.monto_desembolsado, 
	pit_bd_ficha_pit.monto_organizacion_2, 
	pit_bd_ficha_pit.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	pit_bd_ficha_pit.cod_estado_iniciativa
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN clar_atf_pit_sd ON clar_atf_pit_sd.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pit_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_taz.cod_dependencia AND clar_atf_pit_sd.cod_ficha_sd = clar_bd_ficha_sd_pit.cod_ficha_sd
WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PIT</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila1['n_contrato'])."-".periodo($fila1['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila1['f_contrato']);?></td>
	<td class="centrado"><? echo $fila1['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila1['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila1['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila1['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila1['aporte_pdss']+$fila1['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila1['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila1['n_documento'];?></td>
	<td><? echo $fila1['nombre'];?></td>
	<td class="centrado"><? echo $fila1['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila1['n_solicitud']);?></td>
	<td class="centrado"><? if ($fila1['n_atf']==0) echo "-"; else echo numeracion($fila1['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila1['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila1['monto_organizacion_2'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila1['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila1['banco'];?></td>
	<td class="centrado"><? echo $fila1['n_voucher_2'];?></td>
	<td class="centrado"><? echo numeracion($fila1['cod_clar'])."-".periodo($fila1['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila1['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila1['f_evento']);?></td>
	<td><? echo $fila1['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila1['n_acta']);?></td>
	<td class="centrado"><? echo $fila1['oficina_2'];?></td>
	<td class="centrado"><? if ($fila1['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila1['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>
</tr>	
<?
}
?>	
	
<!-- PDN PERTENECIENTES A UN PIT - Primer desembolso -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.total_apoyo) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	olp.nombre AS oficina, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS evento, 
	sys_bd_dependencia.nombre AS olp, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia olp ON olp.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
WHERE pit_bd_ficha_pit.f_contrato  BETWEEN '$anio-01-01' AND '$anio-12-31' AND
clar_atf_pdn.cod_tipo_atf_pdn=1
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.total_apoyo) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	olp.nombre AS oficina, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS evento, 
	sys_bd_dependencia.nombre AS olp, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia olp ON olp.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
WHERE pit_bd_ficha_pit.f_contrato  BETWEEN '$anio-01-01' AND '$anio-12-31' AND
clar_atf_pdn.cod_tipo_atf_pdn=1 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/PIT</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila2['n_contrato'])."-".periodo($fila2['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila2['f_contrato']);?></td>
	<td class="centrado"><? echo $fila2['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila2['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila2['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila2['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila2['aporte_pdss']+$fila2['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila2['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila2['n_documento'];?></td>
	<td><? echo $fila2['nombre'];?></td>
	<td class="centrado"><? echo $fila2['oficina'];?></td>
	<td class="centrado"><? echo numeracion($fila2['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila2['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila2['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila2['monto_organizacion'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila2['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila2['ifi'];?></td>
	<td class="centrado"><? echo $fila2['n_voucher'];?></td>
	<td class="centrado"><? echo numeracion($fila2['cod_clar'])."-".periodo($fila2['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila2['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila2['f_evento']);?></td>
	<td><? echo $fila2['evento'];?></td>
	<td class="centrado"><? echo numeracion($fila2['n_acta']);?></td>
	<td class="centrado"><? echo $fila2['olp'];?></td>
	<td class="centrado"><? if ($fila2['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila2['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>	
	
<!-- PDN AMPLIACIONES A UN PIT - PRIMER DESEMBOLSO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_ampliacion_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pit ON clar_ampliacion_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 LEFT OUTER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
	 LEFT OUTER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 LEFT OUTER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 LEFT OUTER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.f_ampliacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_ampliacion_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pit ON clar_ampliacion_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 LEFT OUTER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
	 LEFT OUTER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 LEFT OUTER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 LEFT OUTER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_ampliacion_pit.f_ampliacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/AMPLIACION</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila3['n_contrato'])."-".periodo($fila3['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila3['f_contrato']);?></td>
	<td class="centrado"><? echo $fila3['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila3['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila3['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila3['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila3['aporte_pdss']+$fila3['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila3['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila3['n_documento'];?></td>
	<td><? echo $fila3['nombre'];?></td>
	<td class="centrado"><? echo $fila3['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila3['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila3['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila3['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila3['monto_organizacion'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila3['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila3['banco'];?></td>
	<td class="centrado"><? echo $fila3['n_voucher'];?></td>
	<td class="centrado"><? echo numeracion($fila3['cod_clar'])."-".periodo($fila3['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila3['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila3['f_evento']);?></td>
	<td><? echo $fila3['clar'];?></td>
	<td><? echo numeracion($fila3['n_acta']);?></td>
	<td class="centrado"><? echo $fila3['oficina_2'];?></td>
	<td class="centrado"><? if ($fila3['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila3['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>	


<!-- PDN CONTRATOS SUELTOS - PRIMER DESEMBOLSO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pdn.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4 AND
pit_bd_ficha_pdn.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pdn.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_pdn.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila4=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/SUELTO</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila4['n_contrato'])."-".periodo($fila4['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila4['f_contrato']);?></td>
	<td class="centrado"><? echo $fila4['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila4['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila4['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila4['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila4['aporte_pdss']+$fila4['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila4['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila4['n_documento'];?></td>
	<td><? echo $fila4['nombre'];?></td>
	<td class="centrado"><? echo $fila4['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila4['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila4['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila4['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila4['monto_organizacion'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila4['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila4['banco'];?></td>
	<td class="centrado"><? echo $fila4['n_voucher'];?></td>
	<td class="centrado"><? echo numeracion($fila4['cod_clar'])."-".periodo($fila4['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila4['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila4['f_evento']);?></td>
	<td><? echo $fila4['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila4['n_acta']);?></td>
	<td class="centrado"><? echo $fila4['oficina_2'];?></td>
	<td class="centrado"><? if ($fila4['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila4['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>	

<!-- PDN SEGUNDO DESEMBOLSOS -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_sd_pit.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia AND clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit_bd_ficha_pit.n_contrato<>0 AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";

}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_sd_pit.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia AND clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit_bd_ficha_pit.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila5=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/PIT</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila5['n_contrato'])."-".periodo($fila5['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila5['f_contrato']);?></td>
	<td class="centrado"><? echo $fila5['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila5['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila5['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila5['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila5['aporte_pdss']+$fila5['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila5['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila5['n_documento'];?></td>
	<td><? echo $fila5['nombre'];?></td>
	<td class="centrado"><? echo $fila5['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila5['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila5['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila5['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila5['monto_organizacion_2'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila5['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila5['banco'];?></td>
	<td class="centrado"><? echo $fila5['n_voucher_2'];?></td>
	<td class="centrado"><? echo numeracion($fila5['cod_clar'])."-".periodo($fila5['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila5['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila5['f_evento']);?></td>
	<td><? echo $fila5['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila5['n_acta']);?></td>
	<td class="centrado"><? echo $fila5['oficina_2'];?></td>
	<td class="centrado"><? if ($fila5['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila5['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>

<!-- PDN AMPLIACIONES A UN PIT - SEGUNDO DESEMBOLSOS -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_pit.cod_pit, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_pit pit1 ON pit_bd_ficha_pdn.cod_pit = pit1.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit1.n_contrato=0 AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_pit.cod_pit, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_pit pit1 ON pit_bd_ficha_pdn.cod_pit = pit1.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit1.n_contrato=0 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila13=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/AMPLIACION</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila13['n_contrato'])."-".periodo($fila13['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila13['f_contrato']);?></td>
	<td class="centrado"><? echo $fila13['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila13['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila13['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila13['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila13['aporte_pdss']+$fila13['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila13['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila13['n_documento'];?></td>
	<td><? echo $fila13['nombre'];?></td>
	<td class="centrado"><? echo $fila13['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila13['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila13['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila13['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila13['monto_organizacion_2'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila13['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila13['banco'];?></td>
	<td class="centrado"><? echo $fila13['n_voucher_2'];?></td>
	<td class="centrado"><? echo numeracion($fila13['cod_clar'])."-".periodo($fila13['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila13['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila13['f_evento']);?></td>
	<td><? echo $fila13['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila13['n_acta']);?></td>
	<td class="centrado"><? echo $fila13['oficina_2'];?></td>
	<td class="centrado"><? if ($fila13['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila13['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>


<?
//PDN SUELTOS JOVENES SEGUNDOS DESEMBOLSOS
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	clar_bd_ficha_sd_pdn.n_solicitud, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_bd_ficha_sd_pdn ON clar_bd_ficha_sd_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_atf_pdn.cod_tipo_atf_pdn=6 AND
clar_bd_ficha_sd_pdn.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+ 
	clar_atf_pdn.monto_2+ 
	clar_atf_pdn.monto_3+ 
	clar_atf_pdn.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	clar_bd_ficha_sd_pdn.n_solicitud, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_bd_ficha_sd_pdn ON clar_bd_ficha_sd_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_atf_pdn.cod_tipo_atf_pdn=6 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_pdn.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila14=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PDN/SUELTO</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila14['n_contrato'])."-".periodo($fila14['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila14['f_contrato']);?></td>
	<td class="centrado"><? echo $fila14['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila14['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila14['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila14['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila14['aporte_pdss']+$fila14['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila14['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila14['n_documento'];?></td>
	<td><? echo $fila14['nombre'];?></td>
	<td class="centrado"><? echo $fila14['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila14['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila14['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila14['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila14['monto_organizacion'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila14['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila14['banco'];?></td>
	<td class="centrado"><? echo $fila14['n_voucher'];?></td>
	<td class="centrado"><? echo numeracion($fila14['cod_clar'])."-".periodo($fila14['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila14['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila14['f_evento']);?></td>
	<td><? echo $fila14['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila14['n_acta']);?></td>
	<td class="centrado"><? echo $fila14['oficina_2'];?></td>
	<td class="centrado"><? if ($fila14['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila14['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>









<!-- MRN Primer desembolso -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_mrn.n_atf, 
	(clar_atf_mrn.desembolso_1+ 
	clar_atf_mrn.desembolso_2+
	clar_atf_mrn.desembolso_3+ 
	clar_atf_mrn.desembolso_4) AS monto_desembolsado, 
	pit_bd_ficha_mrn.monto_organizacion, 
	pit_bd_ficha_mrn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.cod_acta, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.cod_estado_iniciativa
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE
pit_bd_ficha_pit.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	pit_bd_ficha_pit.n_solicitud, 
	clar_atf_mrn.n_atf, 
	(clar_atf_mrn.desembolso_1+ 
	clar_atf_mrn.desembolso_2+
	clar_atf_mrn.desembolso_3+ 
	clar_atf_mrn.desembolso_4) AS monto_desembolsado, 
	pit_bd_ficha_mrn.monto_organizacion, 
	pit_bd_ficha_mrn.n_voucher, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.cod_acta, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.cod_estado_iniciativa
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_pit.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila6=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PGRN</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila6['n_contrato'])."-".periodo($fila6['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila6['f_contrato']);?></td>
	<td class="centrado"><? echo $fila6['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila6['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila6['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila6['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila6['aporte_pdss']+$fila6['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila6['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila6['n_documento'];?></td>
	<td><? echo $fila6['nombre'];?></td>
	<td class="centrado"><? echo $fila6['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila6['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila6['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila6['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila6['monto_organizacion'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila6['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila6['banco'];?></td>
	<td class="centrado"><? echo $fila6['n_voucher'];?></td>
	<td class="centrado"><? echo numeracion($fila6['cod_clar'])."-".periodo($fila6['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila6['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila6['f_evento']);?></td>
	<td><? echo $fila6['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila6['n_acta']);?></td>
	<td class="centrado"><? echo $fila6['oficina_2'];?></td>
	<td class="centrado"><? if ($fila6['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila6['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>	

<!-- PGRN SEGUNDO DESEMBOLSO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_mrn_sd.n_atf, 
	(clar_atf_mrn_sd.monto_1+ 
	clar_atf_mrn_sd.monto_2+ 
	clar_atf_mrn_sd.monto_3+ 
	clar_atf_mrn_sd.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_mrn.monto_organizacion_2, 
	pit_bd_ficha_mrn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.cod_estado_iniciativa
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN clar_atf_mrn_sd ON clar_atf_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia AND clar_atf_mrn_sd.cod_ficha_sd = clar_bd_ficha_sd_pit.cod_ficha_sd
	 INNER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_bd_ficha_sd_pit.n_solicitud, 
	clar_atf_mrn_sd.n_atf, 
	(clar_atf_mrn_sd.monto_1+ 
	clar_atf_mrn_sd.monto_2+ 
	clar_atf_mrn_sd.monto_3+ 
	clar_atf_mrn_sd.monto_4) AS monto_desembolsado, 
	pit_bd_ficha_mrn.monto_organizacion_2, 
	pit_bd_ficha_mrn.n_voucher_2, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.cod_estado_iniciativa
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN clar_atf_mrn_sd ON clar_atf_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia AND clar_atf_mrn_sd.cod_ficha_sd = clar_bd_ficha_sd_pit.cod_ficha_sd
	 INNER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_pit.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila7=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PGRN</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila7['n_contrato'])."-".periodo($fila7['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila7['f_contrato']);?></td>
	<td class="centrado"><? echo $fila7['mes'];?></td>
	<td class="centrado"><? echo fecha_normal($fila7['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila7['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila7['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila7['aporte_pdss']+$fila7['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila7['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila7['n_documento'];?></td>
	<td><? echo $fila7['nombre'];?></td>
	<td class="centrado"><? echo $fila7['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila7['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila7['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila7['monto_desembolsado'],2);?></td>
	<td class="derecha"><? echo number_format($fila7['monto_organizacion_2'],2);?></td>
	<td class="centrado"><? echo "Nº ".$fila7['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila7['banco'];?></td>
	<td class="centrado"><? echo $fila7['n_voucher_2'];?></td>
	<td class="centrado"><? echo numeracion($fila7['cod_clar'])."-".periodo($fila7['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila7['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila7['f_evento']);?></td>
	<td><? echo $fila7['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila7['n_acta']);?></td>
	<td class="centrado"><? echo $fila7['oficina_2'];?></td>
	<td class="centrado"><? if ($fila7['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila7['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>

<!-- IDL - PRIMER DESEMBOLSO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	pit_bd_ficha_idl.n_solicitud, 
	clar_atf_idl.n_atf, 
	clar_atf_idl.monto_desembolsado, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_idl.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN clar_atf_idl ON clar_atf_idl.cod_ficha_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_idl.tipo_atf=1 AND
pit_bd_ficha_idl.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	pit_bd_ficha_idl.n_solicitud, 
	clar_atf_idl.n_atf, 
	clar_atf_idl.monto_desembolsado, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_idl.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN clar_atf_idl ON clar_atf_idl.cod_ficha_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_idl.tipo_atf=1 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_idl.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila8=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">IDL</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila8['n_contrato'])."-".periodo($fila8['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila8['f_contrato']);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo fecha_normal($fila8['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila8['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila8['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila8['aporte_pdss']+$fila8['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila8['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila8['n_documento'];?></td>
	<td><? echo $fila8['nombre'];?></td>
	<td class="centrado"><? echo $fila8['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila8['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila8['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila8['monto_desembolsado'],2);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo "Nº ".$fila8['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila8['banco'];?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo numeracion($fila8['cod_clar'])."-".periodo($fila8['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila8['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila8['f_evento']);?></td>
	<td><? echo $fila8['clar'];?></td>
	<td class="centrado"><? echo numeracion($fila8['n_acta']);?></td>
	<td class="centrado"><? echo $fila8['oficina_2'];?></td>
	<td class="centrado"><? if ($fila8['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila8['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>

<!-- IDL - SEGUNDO DESEMBOLSO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_idl.n_atf, 
	clar_atf_idl.monto_desembolsado, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_idl.n_solicitud, 
	pit_bd_ficha_idl.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN clar_bd_ficha_sd_idl ON clar_bd_ficha_sd_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN clar_atf_idl ON clar_atf_idl.cod_ficha_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_idl.tipo_atf=2 AND
clar_bd_ficha_sd_idl.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_1, 
	clar_atf_idl.n_atf, 
	clar_atf_idl.monto_desembolsado, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre AS clar, 
	sys_bd_dependencia.nombre AS oficina_2, 
	clar_bd_evento_clar.f_campo1, 
	clar_bd_acta.n_acta, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	clar_bd_ficha_sd_idl.n_solicitud, 
	pit_bd_ficha_idl.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN clar_bd_ficha_sd_idl ON clar_bd_ficha_sd_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN clar_atf_idl ON clar_atf_idl.cod_ficha_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl_2.cod_clar
	 INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_atf_idl.tipo_atf=2 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_ficha_sd_idl.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila9=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">IDL</td>
	<td class="centrado">SEGUNDO</td>
	<td class="centrado"><? echo numeracion($fila9['n_contrato'])."-".periodo($fila9['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila9['f_contrato']);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo fecha_normal($fila9['f_termino']);?></td>
	<td class="derecha"><? echo number_format($fila9['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila9['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila9['aporte_pdss']+$fila9['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila9['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila9['n_documento'];?></td>
	<td><? echo $fila9['nombre'];?></td>
	<td class="centrado"><? echo $fila9['oficina_1'];?></td>
	<td class="centrado"><? echo numeracion($fila9['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila9['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila9['monto_desembolsado'],2);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo "Nº ".$fila9['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila9['banco'];?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo numeracion($fila9['cod_clar'])."-".periodo($fila9['f_evento']);?></td>
	<td class="centrado"><? echo fecha_normal($fila9['f_campo1']);?></td>
	<td class="centrado"><? echo fecha_normal($fila9['f_evento']);?></td>
	<td><? echo $fila9['clar'];?></td>
	<td class="centrado"><? echo $fila9['n_acta'];?></td>
	<td class="centrado"><? echo $fila9['oficina_2'];?></td>
	<td class="centrado"><? if ($fila9['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila9['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>		
</tr>
<?
}
?>


<!-- EVENTOS DE GESTION DEL CONOCIMIENTO -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_evento_gc.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_2, 
	gcac_bd_evento_gc.n_solicitud, 
	gcac_bd_evento_gc.n_atf, 
	gcac_bd_evento_gc.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	gcac_bd_evento_gc.cod_estado_iniciativa
FROM sys_bd_tipo_doc INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_doc.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = gcac_bd_evento_gc.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE
gcac_bd_evento_gc.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.n_contrato ASC";
}
else
{
$sql="SELECT gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_evento_gc.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ol1.nombre AS oficina_2, 
	gcac_bd_evento_gc.n_solicitud, 
	gcac_bd_evento_gc.n_atf, 
	gcac_bd_evento_gc.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	gcac_bd_evento_gc.cod_estado_iniciativa
FROM sys_bd_tipo_doc INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_doc.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = gcac_bd_evento_gc.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	 INNER JOIN sys_bd_dependencia ol1 ON ol1.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
gcac_bd_evento_gc.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila10=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">EGC</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila10['n_contrato'])."-".periodo($fila10['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila10['f_contrato']);?></td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="derecha"><? echo number_format($fila10['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila10['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila10['aporte_pdss']+$fila10['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila10['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila10['n_documento'];?></td>
	<td><? echo $fila10['nombre'];?></td>
	<td class="centrado"><? echo $fila10['oficina_2'];?></td>
	<td class="centrado"><? echo numeracion($fila10['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila10['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila10['aporte_pdss'],2);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo "Nº ".$fila10['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila10['banco'];?></td>
	<td class="centrado">-</td>
	<td class="centrado">NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td>NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="centrado"><? if ($fila10['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila10['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>	
</tr>
<?
}
?>

<!-- PROMOCION COMERCIAL -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	ml_promocion_c.aporte_pdss, 
	ml_promocion_c.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina_2, 
	ml_promocion_c.n_solicitud, 
	ml_promocion_c.n_atf, 
	ml_promocion_c.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	ml_promocion_c.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_promocion_c.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE
ml_promocion_c.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
else
{
$sql="SELECT ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	ml_promocion_c.aporte_pdss, 
	ml_promocion_c.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina_2, 
	ml_promocion_c.n_solicitud, 
	ml_promocion_c.n_atf, 
	ml_promocion_c.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	ml_promocion_c.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_promocion_c.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
ml_promocion_c.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila11=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PROMOCION COMERCIAL</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila11['n_contrato'])."-".periodo($fila11['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila11['f_contrato']);?></td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="derecha"><? echo number_format($fila11['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila11['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila11['aporte_pdss']+$fila11['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila11['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila11['n_documento'];?></td>
	<td><? echo $fila11['nombre'];?></td>
	<td class="centrado"><? echo $fila11['oficina_2'];?></td>
	<td class="centrado"><? echo numeracion($fila11['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila11['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila11['aporte_pdss'],2);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo "Nº ".$fila11['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila11['banco'];?></td>
	<td class="centrado">-</td>
	<td class="centrado">NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td>NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="centrado"><? if ($fila11['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila11['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>		
</tr>
<?
}
?>

<!-- PARTICIPACION EN FERIAS -->
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	ml_pf.aporte_pdss, 
	ml_pf.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina_2, 
	ml_pf.n_solicitud, 
	ml_pf.n_atf, 
	ml_pf.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	ml_pf.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_pf.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE
ml_pf.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_pf.n_contrato ASC";
}
else
{
$sql="SELECT ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	ml_pf.aporte_pdss, 
	ml_pf.aporte_org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina_2, 
	ml_pf.n_solicitud, 
	ml_pf.n_atf, 
	ml_pf.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	ml_pf.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_pf.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
ml_pf.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_pf.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila12=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado">PROMOCION COMERCIAL</td>
	<td class="centrado">PRIMERO</td>
	<td class="centrado"><? echo numeracion($fila12['n_contrato'])."-".periodo($fila12['f_contrato']);?></td>
	<td class="centrado"><? echo fecha_normal($fila12['f_contrato']);?></td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="derecha"><? echo number_format($fila12['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($fila12['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($fila12['aporte_pdss']+$fila12['aporte_org'],2);?></td>
	<td class="centrado"><? echo $fila12['tipo_doc'];?></td>
	<td class="centrado"><? echo $fila12['n_documento'];?></td>
	<td><? echo $fila12['nombre'];?></td>
	<td class="centrado"><? echo $fila12['oficina_2'];?></td>
	<td class="centrado"><? echo numeracion($fila12['n_solicitud']);?></td>
	<td class="centrado"><? echo numeracion($fila12['n_atf']);?></td>
	<td class="derecha"><? echo number_format($fila12['aporte_pdss'],2);?></td>
	<td class="centrado">-</td>
	<td class="centrado"><? echo "Nº ".$fila12['n_cuenta'];?></td>
	<td class="centrado"><? echo $fila12['banco'];?></td>
	<td class="centrado">-</td>
	<td class="centrado">NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td>NO APLICA</td>
	<td class="centrado">-</td>
	<td class="centrado">-</td>
	<td class="centrado"><? if ($fila12['cod_estado_iniciativa']==000) echo "CONTRATO ANULADO"; elseif($fila12['cod_estado_iniciativa']==004) echo "CONTRATO LIQUIDADO"; else echo "EN EJECUCION";?></td>		
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
	<a href="report_contratos.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>


  </body>
</html>
