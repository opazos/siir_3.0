<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_estado_iniciativa.xls");
header("Pragma: no-cache");
}


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
<? include("encabezado.php");?>


<table class="mini" align="center" cellpadding="1" cellspacing="1" border="1">
	<tr class="txt_titulo centrado">
		<td>TIPO INICIATIVA</td>
		<td>N. DOCUMENTO</td>
		<td>NOMBRE</td>
		<td>N. CONTRATO</td>
		<td>FECHA CONTRATO</td>
		<td>FECHA VENCIMIENTO</td>
		<td>MONTO PRESUPUESTADO</td>
		<td>MONTO EJECUTADO</td>
		<td>FECHA DE RENDICION</td>
		<td>OFICINA LOCAL</td>
		<td>ESTADO ACTUAL</td>
		<td>SITUACION CONTRACTUAL</td>
		<td>PLAZOS</td>
	</tr>
<!-- Giras del conocimiento -->
<?php
	if ($row['cod_dependencia']==001)
	{
	$sql="SELECT gcac_bd_evento_gc.cod_evento_gc, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_liquida_evento_gc.ejec_pdss, 
	gcac_bd_liquida_evento_gc.f_informe, 
	gcac_bd_liquida_evento_gc.cod_liquida_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gcac_bd_evento_gc.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN gcac_bd_liquida_evento_gc ON gcac_bd_liquida_evento_gc.cod_evento_gc = gcac_bd_evento_gc.cod_evento_gc
	WHERE gcac_bd_evento_gc.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.f_contrato ASC, gcac_bd_evento_gc.n_contrato ASC";
	}
	else
	{
	$sql="SELECT gcac_bd_evento_gc.cod_evento_gc, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_liquida_evento_gc.ejec_pdss, 
	gcac_bd_liquida_evento_gc.f_informe, 
	gcac_bd_liquida_evento_gc.cod_liquida_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gcac_bd_evento_gc.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN gcac_bd_liquida_evento_gc ON gcac_bd_liquida_evento_gc.cod_evento_gc = gcac_bd_evento_gc.cod_evento_gc
	WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
	gcac_bd_evento_gc.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.f_contrato ASC, gcac_bd_evento_gc.n_contrato ASC";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
		//Funcion para sumar 3 meses a la fecha
		$fecha_db1 = $f1['f_contrato'];
		$fecha_db1 = explode("-",$fecha_db1);

		$fecha_cambiada1 = mktime(0,0,0,$fecha_db1[1],$fecha_db1[2]+90,$fecha_db1[0]);
		$fecha1 = date("Y-m-d", $fecha_cambiada1);
		$fecha_termino1 = $fecha1;
?>
	<tr>
		<td class="centrado"><? echo $f1['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f1['n_documento'];?></td>
		<td><? echo $f1['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino1);?></td>
		<td class="derecha"><? echo number_format($f1['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f1['ejec_pdss'],2);?></td>
		<td class="centrado"><? if ($f1['f_informe']<>NULL) echo fecha_normal($f1['f_informe']);?></td>
		<td class="centrado"><? echo $f1['oficina'];?></td>
		<td class="centrado"><? echo $f1['estado'];?></td>
		<td class="centrado"><? if ($f1['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino1>$fecha_hoy and $f1['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino1<$fecha_hoy and $f1['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>
	</tr>
<?php
	}
//2.- Promocion Comercial
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT ml_promocion_c.cod_evento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	ml_promocion_c.aporte_pdss, 
	ml_liquida_pc.ejec_pdss, 
	ml_liquida_pc.f_rendicion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_promocion_c ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_promocion_c.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN ml_liquida_pc ON ml_liquida_pc.cod_evento = ml_promocion_c.cod_evento
	WHERE ml_promocion_c.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_promocion_c.f_contrato ASC, ml_promocion_c.n_contrato ASC";
	}	
	else
	{
	$sql="SELECT ml_promocion_c.cod_evento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	ml_promocion_c.aporte_pdss, 
	ml_liquida_pc.ejec_pdss, 
	ml_liquida_pc.f_rendicion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_promocion_c ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_promocion_c.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN ml_liquida_pc ON ml_liquida_pc.cod_evento = ml_promocion_c.cod_evento
	WHERE ml_promocion_c.cod_estado_iniciativa<>000 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_promocion_c.f_contrato ASC, ml_promocion_c.n_contrato ASC";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($f2=mysql_fetch_array($result))
	{
		//Funcion para sumar 3 meses a la fecha
		$fecha_db2 = $f2['f_contrato'];
		$fecha_db2 = explode("-",$fecha_db2);

		$fecha_cambiada2 = mktime(0,0,0,$fecha_db2[1],$fecha_db2[2]+90,$fecha_db2[0]);
		$fecha2 = date("Y-m-d", $fecha_cambiada2);
		$fecha_termino2 = $fecha2;		
?>
	<tr>
		<td class="centrado"><? echo $f2['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f2['n_documento'];?></td>
		<td><? echo $f2['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f2['n_contrato'])."-".periodo($f2['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino2);?></td>
		<td class="derecha"><? echo number_format($f2['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f2['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f2['f_rendicion']<>NULL) echo fecha_normal($f2['f_rendicion']);?></td>
		<td class="centrado"><? echo $f2['oficina'];?></td>
		<td class="centrado"><? echo $f2['estado'];?></td>
		<td class="centrado"><? if ($f2['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino2>$fecha_hoy and $f2['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino2<$fecha_hoy and $f2['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php		
	}
//3.- Participacion en Ferias
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT ml_pf.cod_evento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	ml_pf.aporte_pdss, 
	ml_liquida_pf.ejec_pdss, 
	ml_liquida_pf.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_pf ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_pf.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN ml_liquida_pf ON ml_liquida_pf.cod_evento = ml_pf.cod_evento
	WHERE ml_pf.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_pf.f_contrato ASC, ml_pf.n_contrato ASC";
	}
	else
	{
	$sql="SELECT ml_pf.cod_evento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	ml_pf.aporte_pdss, 
	ml_liquida_pf.ejec_pdss, 
	ml_liquida_pf.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_pf ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_pf.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN ml_liquida_pf ON ml_liquida_pf.cod_evento = ml_pf.cod_evento
	WHERE ml_pf.cod_estado_iniciativa<>000 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_pf.f_contrato ASC, ml_pf.n_contrato ASC";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($f3=mysql_fetch_array($result))
	{
		//Funcion para sumar 1 meses a la fecha
		$fecha_db3 = $f3['f_contrato'];
		$fecha_db3 = explode("-",$fecha_db3);

		$fecha_cambiada3 = mktime(0,0,0,$fecha_db3[1],$fecha_db3[2]+30,$fecha_db3[0]);
		$fecha3 = date("Y-m-d", $fecha_cambiada3);
		$fecha_termino3 = $fecha3;			
?>
	<tr>
		<td class="centrado"><? echo $f3['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f3['n_documento'];?></td>
		<td><? echo $f3['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f3['n_contrato'])."-".periodo($f3['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f3['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino3);?></td>
		<td class="derecha"><? echo number_format($f3['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f3['ejec_pdss'],2);?></td>
		<td class="centrado"><? echo fecha_normal($f3['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f3['oficina'];?></td>
		<td class="centrado"><? echo $f3['estado'];?></td>
		<td class="centrado"><? if ($f3['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino3>$fecha_hoy and $f3['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino3<$fecha_hoy and $f3['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php		
	}
//4.- Visitas Guiadas
if($row['cod_dependencia']==001)
{
	$sql="SELECT ml_bd_contrato_vg.cod_contrato, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_bd_contrato_vg.n_contrato, 
	ml_bd_contrato_vg.f_contrato, 
	ml_bd_contrato_vg.aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_bd_contrato_vg ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_bd_contrato_vg.cod_tipo_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_bd_contrato_vg.cod_tipo_doc AND org_ficha_organizacion.n_documento = ml_bd_contrato_vg.n_documento
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_bd_contrato_vg.cod_estado_iniciativa
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	WHERE ml_bd_contrato_vg.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_bd_contrato_vg.f_contrato ASC, ml_bd_contrato_vg.n_contrato ASC";
}	
else
{
	$sql="SELECT ml_bd_contrato_vg.cod_contrato, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	ml_bd_contrato_vg.n_contrato, 
	ml_bd_contrato_vg.f_contrato, 
	ml_bd_contrato_vg.aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN ml_bd_contrato_vg ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_bd_contrato_vg.cod_tipo_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_bd_contrato_vg.cod_tipo_doc AND org_ficha_organizacion.n_documento = ml_bd_contrato_vg.n_documento
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ml_bd_contrato_vg.cod_estado_iniciativa
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	WHERE ml_bd_contrato_vg.cod_estado_iniciativa<>000 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, ml_bd_contrato_vg.f_contrato ASC, ml_bd_contrato_vg.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
		//Funcion para sumar 45 dias a la fecha
		$fecha_db4 = $f4['f_contrato'];
		$fecha_db4 = explode("-",$fecha_db4);

		$fecha_cambiada4 = mktime(0,0,0,$fecha_db4[1],$fecha_db4[2]+45,$fecha_db4[0]);
		$fecha4 = date("Y-m-d", $fecha_cambiada4);
		$fecha_termino4 = $fecha4;		
?>
	<tr>
		<td class="centrado"><? echo $f4['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f4['n_documento'];?></td>
		<td><? echo $f4['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f4['n_contrato'])."-".periodo($f4['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f4['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino4);?></td>
		<td class="derecha"><? echo number_format($f4['aporte_pdss'],2);?></td>
		<td class="derecha">0.00</td>
		<td></td>
		<td class="centrado"><? echo $f4['oficina'];?></td>
		<td class="centrado"><? echo $f4['estado'];?></td>
		<td class="centrado"><? if ($f4['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino4>$fecha_hoy and $f4['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino4<$fecha_hoy and $f4['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php	
}
//5.- Eventos de gestión del conocimiento
if($row['cod_dependencia']==001)
{
	$sql="SELECT gcac_bd_evento_gc.cod_evento_gc, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_liquida_evento_gc.ejec_pdss, 
	gcac_bd_liquida_evento_gc.f_informe, 
	gcac_bd_liquida_evento_gc.cod_liquida_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gcac_bd_evento_gc.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN gcac_bd_liquida_evento_gc ON gcac_bd_liquida_evento_gc.cod_evento_gc = gcac_bd_evento_gc.cod_evento_gc
	WHERE gcac_bd_evento_gc.cod_estado_iniciativa<>000
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.f_contrato ASC, gcac_bd_evento_gc.n_contrato ASC";
}
else
{
	$sql="SELECT gcac_bd_evento_gc.cod_evento_gc, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	gcac_bd_evento_gc.aporte_pdss, 
	gcac_bd_liquida_evento_gc.ejec_pdss, 
	gcac_bd_liquida_evento_gc.f_informe, 
	gcac_bd_liquida_evento_gc.cod_liquida_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
	FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_evento_gc ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gcac_bd_evento_gc.cod_estado_iniciativa
	INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	LEFT OUTER JOIN gcac_bd_liquida_evento_gc ON gcac_bd_liquida_evento_gc.cod_evento_gc = gcac_bd_evento_gc.cod_evento_gc
	WHERE gcac_bd_evento_gc.cod_estado_iniciativa<>000 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	ORDER BY org_ficha_organizacion.cod_dependencia ASC, gcac_bd_evento_gc.f_contrato ASC, gcac_bd_evento_gc.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
		//Funcion para sumar 2 meses a la fecha
		$fecha_db5 = $f5['f_contrato'];
		$fecha_db5 = explode("-",$fecha_db5);

		$fecha_cambiada5 = mktime(0,0,0,$fecha_db5[1],$fecha_db5[2]+60,$fecha_db5[0]);
		$fecha5 = date("Y-m-d", $fecha_cambiada5);
		$fecha_termino5 = $fecha5;		
?>
	<tr>
		<td class="centrado"><? echo $f5['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f5['n_documento'];?></td>
		<td><? echo $f5['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f5['n_contrato'])."-".periodo($f5['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f5['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino5);?></td>
		<td class="derecha"><? echo number_format($f5['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f5['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f5['f_informe']<>NULL) echo fecha_normal($f5['f_informe']);?></td>
		<td class="centrado"><? echo $f5['oficina'];?></td>
		<td class="centrado"><? echo $f5['estado'];?></td>
		<td class="centrado"><? if ($f5['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino5>$fecha_hoy and $f5['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino5<$fecha_hoy and $f5['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>		
	</tr>
<?php	
}
//6.- Inversiones para el desarrollo local
if($row['cod_dependencia']==001)
{
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_idl.f_termino
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_idl ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_idl.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_idl.n_contrato<>0
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.f_contrato ASC, pit_bd_ficha_idl.n_contrato ASC";
}
else
{
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.aporte_pdss, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_idl.f_termino
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_idl ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_idl.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_idl.n_contrato<>0 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_idl.f_contrato ASC, pit_bd_ficha_idl.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f6['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f6['n_documento'];?></td>
		<td><? echo $f6['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f6['n_contrato'])."-".periodo($f6['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f6['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f6['f_termino']);?></td>
		<td class="derecha"><? echo number_format($f6['aporte_pdss'],2);?></td>
		<td></td>
		<td></td>
		<td class="centrado"><? echo $f6['oficina'];?></td>
		<td class="centrado"><? echo $f6['estado'];?></td>
		<td class="centrado"><? if ($f6['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($f6['f_termino']>$fecha_hoy and $f6['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($f6['f_termino']<$fecha_hoy and $f6['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>
	</tr>
<?php	
}
//7.- Animador Territorial
if($row['cod_dependencia']==001)
{
	$sql="SELECT DISTINCT pit_bd_ficha_pit.cod_pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.aporte_pdss, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_pit_liquida.cod_ficha_liq, 
	pit_bd_pit_liquida.ejec_an, 
	pit_bd_pit_liquida.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pit ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
	 LEFT OUTER JOIN pit_bd_pit_liquida ON pit_bd_pit_liquida.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_pit.n_contrato<>0
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
	$sql="SELECT DISTINCT pit_bd_ficha_pit.cod_pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.aporte_pdss, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_pit_liquida.cod_ficha_liq, 
	pit_bd_pit_liquida.ejec_an, 
	pit_bd_pit_liquida.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pit ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
	 LEFT OUTER JOIN pit_bd_pit_liquida ON pit_bd_pit_liquida.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_pit.n_contrato<>0 AND
	org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f7['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f7['n_documento'];?></td>
		<td><? echo $f7['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f7['n_contrato'])."-".periodo($f7['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f7['f_contrato']);?></td>
		<td class="centrado"><? if ($f7['f_termino_adenda']==NULL) $fecha_termino7=$f7['f_termino'];else $fecha_termino7=$f7['f_termino_adenda']; echo fecha_normal($fecha_termino7);?></td>
		<td class="derecha"><? echo number_format($f7['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f7['ejec_an'],2);?></td>
		<td class="centrado"><? if($f7['f_liquidacion']<>NULL) echo fecha_normal($f7['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f7['oficina'];?></td>
		<td class="centrado"><? echo $f7['estado'];?></td>
		<td class="centrado"><? if ($f7['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino7>$fecha_hoy and $f7['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino7<$fecha_hoy and $f7['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php	
}
//8.- Planes de gestión de recursos naturales
if ($row['cod_dependencia']==001)
{
	$sql="SELECT DISTINCT pit_bd_ficha_mrn.cod_mrn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	pit_bd_mrn_liquida.cod_ficha_liquida, 
	(pit_bd_mrn_liquida.ejec_cif_pdss+ 
	pit_bd_mrn_liquida.ejec_at_pdss+ 
	pit_bd_mrn_liquida.ejec_vg_pdss+ 
	pit_bd_mrn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_mrn_liquida.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_mrn.f_termino, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda, 
	pit_adenda_mrn.cif_pdss, 
	pit_adenda_mrn.at_pdss, 
	pit_adenda_mrn.ag_pdss, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_pit.mes
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_mrn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 LEFT OUTER JOIN pit_bd_mrn_liquida ON pit_bd_mrn_liquida.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 LEFT OUTER JOIN pit_adenda_mrn ON pit_adenda_mrn.cod_adenda = pit_bd_ficha_adenda.cod_adenda
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_mrn.cod_estado_iniciativa<>003
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
	$sql="SELECT DISTINCT pit_bd_ficha_mrn.cod_mrn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	pit_bd_mrn_liquida.cod_ficha_liquida, 
	(pit_bd_mrn_liquida.ejec_cif_pdss+ 
	pit_bd_mrn_liquida.ejec_at_pdss+ 
	pit_bd_mrn_liquida.ejec_vg_pdss+ 
	pit_bd_mrn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_mrn_liquida.f_liquidacion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_mrn.f_termino, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda, 
	pit_adenda_mrn.cif_pdss, 
	pit_adenda_mrn.at_pdss, 
	pit_adenda_mrn.ag_pdss, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_pit.mes
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_mrn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 LEFT OUTER JOIN pit_bd_mrn_liquida ON pit_bd_mrn_liquida.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 LEFT OUTER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
	 LEFT OUTER JOIN pit_adenda_mrn ON pit_adenda_mrn.cod_adenda = pit_bd_ficha_adenda.cod_adenda
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
		//Funcion para sumar n meses a la fecha
		$fecha_db8 = $f8['f_contrato'];
		$mes=$f8['mes']*30;
		$fecha_db8 = explode("-",$fecha_db8);

		$fecha_cambiada8 = mktime(0,0,0,$fecha_db8[1],$fecha_db8[2]+$mes,$fecha_db8[0]);
		$fecha8 = date("Y-m-d", $fecha_cambiada8);
		$fecha_termino8 = $fecha8;	
?>
	<tr>
		<td class="centrado"><? echo $f8['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f8['n_documento'];?></td>
		<td><? echo $f8['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f8['n_contrato'])."-".periodo($f8['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f8['f_contrato']);?></td>
		<td class="centrado"><? if ($f8['f_termino_adenda']==NULL) $f_termino8=$fecha_termino8; else $f_termino8=$f8['f_termino_adenda']; echo fecha_normal($f_termino8)?></td>
		<td class="derecha"><? echo number_format($f8['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f8['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f8['f_liquidacion']<>NULL) echo fecha_normal($f8['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f8['oficina'];?></td>
		<td class="centrado"><? echo $f8['estado'];?></td>
		<td class="centrado"><? if ($f8['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($f_termino8>$fecha_hoy and $f8['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($f_termino8<$fecha_hoy and $f8['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php	
}
//9.- Planes de negocio pertenecientes a un PIT
if ($row['cod_dependencia']==001)
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	pit_bd_ficha_pdn.mes
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	clar_atf_pdn.cod_tipo_atf_pdn=1
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	pit_bd_ficha_pdn.mes
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	clar_atf_pdn.cod_tipo_atf_pdn=1 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f9=mysql_fetch_array($result))
{
		//Funcion para sumar n meses a la fecha
		$fecha_db9 = $f9['f_contrato'];
		$mes1=$f9['mes']*30;
		$fecha_db9 = explode("-",$fecha_db9);

		$fecha_cambiada9 = mktime(0,0,0,$fecha_db9[1],$fecha_db9[2]+$mes1,$fecha_db9[0]);
		$fecha9 = date("Y-m-d", $fecha_cambiada9);
		$fecha_termino9 = $fecha9;		
?>
	<tr>
		<td class="centrado"><? echo $f9['codigo_iniciativa']."-PIT";?></td>
		<td class="centrado"><? echo $f9['n_documento'];?></td>
		<td><? echo $f9['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f9['n_contrato'])."-".periodo($f9['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f9['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino9);?></td>
		<td class="derecha"><? echo number_format($f9['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f9['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f9['f_liquidacion']<>NULL) echo fecha_normal($f9['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f9['oficina'];?></td>
		<td class="centrado"><? echo $f9['estado'];?></td>
		<td class="centrado"><? if ($f9['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino9>$fecha_hoy and $f9['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino9<$fecha_hoy and $f9['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php	
}
//10.- Ampliaciones de Planes de Negocio
if($row['cod_dependencia']==001)
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	clar_ampliacion_pit.n_ampliacion, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	clar_ampliacion_pit.f_ampliacion
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	clar_atf_pdn.cod_tipo_atf_pdn=3
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
else
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	clar_ampliacion_pit.n_ampliacion, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	clar_ampliacion_pit.f_ampliacion
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	clar_atf_pdn.cod_tipo_atf_pdn=3 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f10=mysql_fetch_array($result))
{
		//Funcion para sumar n meses a la fecha
		$fecha_db10 = $f10['f_ampliacion'];
		$mes2=$f10['mes']*30;
		$fecha_db10= explode("-",$fecha_db10);

		$fecha_cambiada10 = mktime(0,0,0,$fecha_db10[1],$fecha_db10[2]+$mes2,$fecha_db10[0]);
		$fecha10 = date("Y-m-d", $fecha_cambiada10);
		$fecha_termino10 = $fecha10;		
?>
	<tr>
		<td class="centrado"><? echo $f10['codigo_iniciativa']." - AMPLIACION";?></td>
		<td class="centrado"><? echo $f10['n_documento'];?></td>
		<td><? echo $f10['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f10['n_contrato'])."-".periodo($f10['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f10['f_ampliacion']);?></td>
		<td class="centrado"><? echo fecha_normal($fecha_termino10);?></td>
		<td class="derecha"><? echo number_format($f10['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f10['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f10['f_liquidacion']<>NULL) echo fecha_normal($f10['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f10['oficina'];?></td>
		<td class="centrado"><? echo $f10['estado'];?></td>
		<td class="centrado"><? if ($f10['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($fecha_termino10>$fecha_hoy and $f0['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($fecha_termino10<$fecha_hoy and $f10['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>
<?php	
}
//11.- Planes de negocio sueltos
if($row['cod_dependencia']==001)
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.f_termino
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_pdn.tipo<>0
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pdn.f_contrato ASC, pit_bd_ficha_pdn.n_contrato ASC";
}
else
{
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_pdn_liquida.ejec_at_pdss+ 
	pit_bd_pdn_liquida.ejec_pf_pdss+ 
	pit_bd_pdn_liquida.ejec_vg_pdss+ 
	pit_bd_pdn_liquida.ejec_ag_pdss) AS ejec_pdss, 
	pit_bd_pdn_liquida.f_liquidacion, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.f_termino
FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pdn ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 LEFT OUTER JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
	pit_bd_ficha_pdn.tipo<>0 AND
	org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.cod_dependencia ASC, pit_bd_ficha_pdn.f_contrato ASC, pit_bd_ficha_pdn.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f11=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f11['codigo_iniciativa'];?></td>
		<td class="centrado"><? echo $f11['n_documento'];?></td>
		<td><? echo $f11['nombre'];?></td>
		<td class="centrado"><? echo numeracion($f11['n_contrato'])."-".periodo($f11['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f11['f_contrato']);?></td>
		<td class="centrado"><? echo fecha_normal($f11['f_termino']);?></td>
		<td class="derecha"><? echo number_format($f11['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f11['ejec_pdss'],2);?></td>
		<td class="centrado"><? if($f11['f_liquidacion']<>NULL) echo fecha_normal($f11['f_liquidacion']);?></td>
		<td class="centrado"><? echo $f11['oficina'];?></td>
		<td class="centrado"><? echo $f11['estado'];?></td>
		<td class="centrado"><? if ($f11['estado']<>CONCLUIDO) echo "PENDIENTE"; else echo "LIQUIDADO";?></td>
		<td class="centrado"><? if ($f11['f_termino']>$fecha_hoy and $f11['estado']<>CONCLUIDO) echo "PLAZO VIGENTE"; elseif($f11['f_termino']<$fecha_hoy and $f11['estado']<>CONCLUIDO) echo "PLAZO VENCIDO";?></td>	
	</tr>	
<?php	
}
?>

</table>

<?
if ($modo<>excell)
{
?>
<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="report_iniciativas.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>

</body>
</html>
