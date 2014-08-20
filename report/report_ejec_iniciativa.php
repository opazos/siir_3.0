<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_Ejecucion_PDN.xls");
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

<table border="1" cellspacing="1" cellpadding="1" class="mini">
	<thead>
		<tr class="txt_titulo">
			<td colspan="10" class="centrado">Datos Generales</td>
			<td colspan="4" class="centrado">Presupuesto Solicitado</td>
			<td colspan="4" class="centrado">Presupuesto Desembolsado</td>
			<td colspan="3" class="centrado">Desembolsado Asistencia Tecnica</td>
			<td colspan="3" class="centrado">Desembolsado Visita Guiada</td>
			<td colspan="3" class="centrado">Desembolsado Participacion en Ferias</td>
			<td colspan="3" class="centrado">Desembolsado Apoyo a la Gestión</td>
			<td colspan="4" class="centrado">Presupuesto Ejecutado</td>
		</tr>
		<tr class="txt_titulo">
			<td class="centrado">N.</td>
			<td class="centrado">N. Documento</td>
			<td class="centrado">Tipo de Iniciativa</td>
			<td class="centrado">Desembolso</td>
			<td>Nombre de la Organizacion</td>
			<td>Denominacion</td>
			<td class="centrado">Oficina</td>
			<td class="centrado">N. Cuenta</td>
			<td class="centrado">Banco</td>
			<td class="centrado">Estado</td>
			<td class="centrado">Asistencia Tecnica</td>
			<td class="centrado">Visita Guiada</td>
			<td class="centrado">Participacion en Ferias</td>
			<td class="centrado">Apoyo a la Gestion</td>
			<td class="centrado">N. ATF</td>
			<td class="centrado">Fecha de desembolso</td>
			<td class="centrado">N. Cheque/CO</td>
			<td class="centrado">Componente</td>	
			<td class="centrado">Codigo POA</td>
			<td class="centrado">Categoria de Gasto</td>
			<td class="centrado">Monto desembolsado</td>	
			<td class="centrado">Codigo POA</td>
			<td class="centrado">Categoria de Gasto</td>
			<td class="centrado">Monto desembolsado</td>	
			<td class="centrado">Codigo POA</td>
			<td class="centrado">Categoria de Gasto</td>
			<td class="centrado">Monto desembolsado</td>	
			<td class="centrado">Codigo POA</td>
			<td class="centrado">Categoria de Gasto</td>
			<td class="centrado">Monto desembolsado</td>	
			<td class="centrado">Asistencia Tecnica</td>
			<td class="centrado">Visita Guiada</td>
			<td class="centrado">Participacion en Ferias</td>
			<td class="centrado">Apoyo a la Gestión</td>
		</tr>
	</thead>


	<tbody>
	<?php
	$n1=0;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.total_apoyo, 
	clar_atf_pdn.n_atf, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	poa1.cod_categoria_poa AS cat1, 
	poa2.codigo AS poa2, 
	poa2.cod_categoria_poa AS cat2, 
	poa3.codigo AS poa3, 
	poa3.cod_categoria_poa AS cat3, 
	sys_bd_subactividad_poa.codigo AS poa4, 
	sys_bd_subactividad_poa.cod_categoria_poa AS cat4, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	poa1.periodo
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_atf_pdn.cod_tipo_atf_pdn=1 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$n1++
	?>
		<tr>
			<td class="centrado"><? echo $n1;?></td>
			<td class="centrado"><? echo $fila['n_documento'];?></td>
			<td class="centrado">PDN - PIT</td>
			<td class="centrado">PRIMER</td>
			<td><? echo $fila['nombre'];?></td>
			<td><? echo $fila['denominacion'];?></td>
			<td class="centrado"><? echo $fila['oficina'];?></td>
			<td class="centrado">.<? echo $fila['n_cuenta'];?>.</td>
			<td><? echo $fila['ifi'];?></td>
			<td class="centrado"><? echo $fila['estado'];?></td>
			<td class="derecha"><? echo number_format($fila['at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['fer_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['total_apoyo'],2);?></td>
			<td class="centrado"><? echo numeracion($fila['n_atf'])."-".$fila['periodo'];?></td>
			<td class="centrado"><? if ($fila['f_desembolso']<>NULL) echo fecha_normal($fila['f_desembolso']);?></td>
			<td class="centrado"><? if ($fila['n_cheque']<>NULL) echo $fila['n_cheque'];?></td>
			<td class="centrado"><? echo $fila['componente'];?></td>

			<td class="centrado"><? echo $fila['poa1'];?></td>
			<td class="centrado"><? echo $fila['cat1'];?></td>
			<td class="derecha"><? echo number_format($fila['monto_1'],2);?></td>
			<td class="centrado"><? echo $fila['poa2'];?></td>
			<td class="centrado"><? echo $fila['cat2'];?></td>
			<td class="derecha"><? echo number_format($fila['monto_2'],2);?></td>
			<td class="centrado"><? echo $fila['poa3'];?></td>
			<td class="centrado"><? echo $fila['cat3'];?></td>
			<td class="derecha"><? echo number_format($fila['monto_3'],2);?></td>
			<td class="centrado"><? echo $fila['poa4'];?></td>
			<td class="centrado"><? echo $fila['cat4'];?></td>
			<td class="derecha"><? echo number_format($fila['monto_4'],2);?></td>

			<td class="derecha"><? echo number_format($fila['ejec_at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['ejec_vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['ejec_pf_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila['ejec_ag_pdss'],2);?></td>
		</tr>
	<?
	}
	?>	

	<!-- PDN Ampliacion -->
		<?php
	$n2=$n1;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.total_apoyo, 
	clar_atf_pdn.n_atf, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	poa1.cod_categoria_poa AS cat1, 
	poa2.codigo AS poa2, 
	poa2.cod_categoria_poa AS cat2, 
	poa3.codigo AS poa3, 
	poa3.cod_categoria_poa AS cat3, 
	sys_bd_subactividad_poa.codigo AS poa4, 
	sys_bd_subactividad_poa.cod_categoria_poa AS cat4, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	poa1.periodo
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila1=mysql_fetch_array($result))
	{
		$n2++
	?>
		<tr>
			<td class="centrado"><? echo $n2;?></td>
			<td class="centrado"><? echo $fila1['n_documento'];?></td>
			<td class="centrado">PDN - AMPLIACION</td>
			<td class="centrado">PRIMER</td>
			<td><? echo $fila1['nombre'];?></td>
			<td><? echo $fila1['denominacion'];?></td>
			<td class="centrado"><? echo $fila1['oficina'];?></td>
			<td class="centrado">.<? echo $fila1['n_cuenta'];?>.</td>
			<td><? echo $fila1['ifi'];?></td>
			<td class="centrado"><? echo $fila1['estado'];?></td>
			<td class="derecha"><? echo number_format($fila1['at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['fer_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['total_apoyo'],2);?></td>
			<td class="centrado"><? echo numeracion($fila1['n_atf'])."-".$fila1['periodo'];?></td>
			<td class="centrado"><? if ($fila1['f_desembolso']<>NULL) echo fecha_normal($fila1['f_desembolso']);?></td>
			<td class="centrado"><? if ($fila1['n_cheque']<>NULL) echo $fila1['n_cheque'];?></td>
			<td class="centrado"><? echo $fila1['componente'];?></td>

			<td class="centrado"><? echo $fila1['poa1'];?></td>
			<td class="centrado"><? echo $fila1['cat1'];?></td>
			<td class="derecha"><? echo number_format($fila1['monto_1'],2);?></td>
			<td class="centrado"><? echo $fila1['poa2'];?></td>
			<td class="centrado"><? echo $fila1['cat2'];?></td>
			<td class="derecha"><? echo number_format($fila1['monto_2'],2);?></td>
			<td class="centrado"><? echo $fila1['poa3'];?></td>
			<td class="centrado"><? echo $fila1['cat3'];?></td>
			<td class="derecha"><? echo number_format($fila1['monto_3'],2);?></td>
			<td class="centrado"><? echo $fila1['poa4'];?></td>
			<td class="centrado"><? echo $fila1['cat4'];?></td>
			<td class="derecha"><? echo number_format($fila1['monto_4'],2);?></td>

			<td class="derecha"><? echo number_format($fila1['ejec_at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['ejec_vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['ejec_pf_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila1['ejec_ag_pdss'],2);?></td>
		</tr>
	<?
	}
	?>	
	<!-- Plan de negocio suelto -->
	<?php
	$n3=$n2;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.total_apoyo, 
	clar_atf_pdn.n_atf, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	poa1.cod_categoria_poa AS cat1, 
	poa2.codigo AS poa2, 
	poa2.cod_categoria_poa AS cat2, 
	poa3.codigo AS poa3, 
	poa3.cod_categoria_poa AS cat3, 
	sys_bd_subactividad_poa.codigo AS poa4, 
	sys_bd_subactividad_poa.cod_categoria_poa AS cat4, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	poa1.periodo
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_atf_pdn.cod_tipo_atf_pdn=4 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila2=mysql_fetch_array($result))
	{
		$n3++
	?>
		<tr>
			<td class="centrado"><? echo $n3;?></td>
			<td class="centrado"><? echo $fila2['n_documento'];?></td>
			<td class="centrado">PDN - INDEPENDIENTE</td>
			<td class="centrado">PRIMER</td>
			<td><? echo $fila2['nombre'];?></td>
			<td><? echo $fila2['denominacion'];?></td>
			<td class="centrado"><? echo $fila2['oficina'];?></td>
			<td class="centrado">.<? echo $fila2['n_cuenta'];?>.</td>
			<td><? echo $fila2['ifi'];?></td>
			<td class="centrado"><? echo $fila2['estado'];?></td>
			<td class="derecha"><? echo number_format($fila2['at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['fer_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['total_apoyo'],2);?></td>
			<td class="centrado"><? echo numeracion($fila2['n_atf'])."-".$fila2['periodo'];?></td>
			<td class="centrado"><? if ($fila2['f_desembolso']<>NULL) echo fecha_normal($fila2['f_desembolso']);?></td>
			<td class="centrado"><? if ($fila2['n_cheque']<>NULL) echo $fila2['n_cheque'];?></td>
			<td class="centrado"><? echo $fila2['componente'];?></td>

			<td class="centrado"><? echo $fila2['poa1'];?></td>
			<td class="centrado"><? echo $fila2['cat1'];?></td>
			<td class="derecha"><? echo number_format($fila2['monto_1'],2);?></td>
			<td class="centrado"><? echo $fila2['poa2'];?></td>
			<td class="centrado"><? echo $fila2['cat2'];?></td>
			<td class="derecha"><? echo number_format($fila2['monto_2'],2);?></td>
			<td class="centrado"><? echo $fila2['poa3'];?></td>
			<td class="centrado"><? echo $fila2['cat3'];?></td>
			<td class="derecha"><? echo number_format($fila2['monto_3'],2);?></td>
			<td class="centrado"><? echo $fila2['poa4'];?></td>
			<td class="centrado"><? echo $fila2['cat4'];?></td>
			<td class="derecha"><? echo number_format($fila2['monto_4'],2);?></td>

			<td class="derecha"><? echo number_format($fila2['ejec_at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['ejec_vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['ejec_pf_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila2['ejec_ag_pdss'],2);?></td>
		</tr>
	<?
	}
	?>	

<!-- Plan de Negocio Segundo desembolso -->
	<?php
	$n4=$n3;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.total_apoyo, 
	clar_atf_pdn.n_atf, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	poa1.cod_categoria_poa AS cat1, 
	poa2.codigo AS poa2, 
	poa2.cod_categoria_poa AS cat2, 
	poa3.codigo AS poa3, 
	poa3.cod_categoria_poa AS cat3, 
	sys_bd_subactividad_poa.codigo AS poa4, 
	sys_bd_subactividad_poa.cod_categoria_poa AS cat4, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	poa1.periodo
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_atf_pdn.cod_tipo_atf_pdn=2 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila3=mysql_fetch_array($result))
	{
		$n4++
	?>
		<tr>
			<td class="centrado"><? echo $n4;?></td>
			<td class="centrado"><? echo $fila3['n_documento'];?></td>
			<td class="centrado">PDN - PIT</td>
			<td class="centrado">SEGUNDO</td>
			<td><? echo $fila3['nombre'];?></td>
			<td><? echo $fila3['denominacion'];?></td>
			<td class="centrado"><? echo $fila3['oficina'];?></td>
			<td class="centrado">.<? echo $fila3['n_cuenta'];?>.</td>
			<td><? echo $fila3['ifi'];?></td>
			<td class="centrado"><? echo $fila3['estado'];?></td>
			<td class="derecha"><? echo number_format($fila3['at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['fer_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['total_apoyo'],2);?></td>
			<td class="centrado"><? echo numeracion($fila3['n_atf'])."-".$fila3['periodo'];?></td>
			<td class="centrado"><? if ($fila3['f_desembolso']<>NULL) echo fecha_normal($fila3['f_desembolso']);?></td>
			<td class="centrado"><? if ($fila3['n_cheque']<>NULL) echo $fila3['n_cheque'];?></td>
			<td class="centrado"><? echo $fila3['componente'];?></td>

			<td class="centrado"><? echo $fila3['poa1'];?></td>
			<td class="centrado"><? echo $fila3['cat1'];?></td>
			<td class="derecha"><? echo number_format($fila3['monto_1'],2);?></td>
			<td class="centrado"><? echo $fila3['poa2'];?></td>
			<td class="centrado"><? echo $fila3['cat2'];?></td>
			<td class="derecha"><? echo number_format($fila3['monto_2'],2);?></td>
			<td class="centrado"><? echo $fila3['poa3'];?></td>
			<td class="centrado"><? echo $fila3['cat3'];?></td>
			<td class="derecha"><? echo number_format($fila3['monto_3'],2);?></td>
			<td class="centrado"><? echo $fila3['poa4'];?></td>
			<td class="centrado"><? echo $fila3['cat4'];?></td>
			<td class="derecha"><? echo number_format($fila3['monto_4'],2);?></td>

			<td class="derecha"><? echo number_format($fila3['ejec_at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['ejec_vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['ejec_pf_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila3['ejec_ag_pdss'],2);?></td>
		</tr>
	<?
	}
	?>	
	<!-- Segundo Desembolso PDN suelto -->
		<?php
	$n5=$n4;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.total_apoyo, 
	clar_atf_pdn.n_atf, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	poa1.cod_categoria_poa AS cat1, 
	poa2.codigo AS poa2, 
	poa2.cod_categoria_poa AS cat2, 
	poa3.codigo AS poa3, 
	poa3.cod_categoria_poa AS cat3, 
	sys_bd_subactividad_poa.codigo AS poa4, 
	sys_bd_subactividad_poa.cod_categoria_poa AS cat4, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	poa1.periodo
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_atf_pdn.cod_tipo_atf_pdn=6 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila4=mysql_fetch_array($result))
	{
		$n5++
	?>
		<tr>
			<td class="centrado"><? echo $n5;?></td>
			<td class="centrado"><? echo $fila4['n_documento'];?></td>
			<td class="centrado">PDN - INDEPENDIENTE</td>
			<td class="centrado">SEGUNDO</td>
			<td><? echo $fila4['nombre'];?></td>
			<td><? echo $fila4['denominacion'];?></td>
			<td class="centrado"><? echo $fila4['oficina'];?></td>
			<td class="centrado">.<? echo $fila4['n_cuenta'];?>.</td>
			<td><? echo $fila4['ifi'];?></td>
			<td class="centrado"><? echo $fila4['estado'];?></td>
			<td class="derecha"><? echo number_format($fila4['at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['fer_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['total_apoyo'],2);?></td>
			<td class="centrado"><? echo numeracion($fila4['n_atf'])."-".$fila4['periodo'];?></td>
			<td class="centrado"><? if ($fila4['f_desembolso']<>NULL) echo fecha_normal($fila4['f_desembolso']);?></td>
			<td class="centrado"><? if ($fila4['n_cheque']<>NULL) echo $fila4['n_cheque'];?></td>
			<td class="centrado"><? echo $fila4['componente'];?></td>

			<td class="centrado"><? echo $fila4['poa1'];?></td>
			<td class="centrado"><? echo $fila4['cat1'];?></td>
			<td class="derecha"><? echo number_format($fila4['monto_1'],2);?></td>
			<td class="centrado"><? echo $fila4['poa2'];?></td>
			<td class="centrado"><? echo $fila4['cat2'];?></td>
			<td class="derecha"><? echo number_format($fila4['monto_2'],2);?></td>
			<td class="centrado"><? echo $fila4['poa3'];?></td>
			<td class="centrado"><? echo $fila4['cat3'];?></td>
			<td class="derecha"><? echo number_format($fila4['monto_3'],2);?></td>
			<td class="centrado"><? echo $fila4['poa4'];?></td>
			<td class="centrado"><? echo $fila4['cat4'];?></td>
			<td class="derecha"><? echo number_format($fila4['monto_4'],2);?></td>

			<td class="derecha"><? echo number_format($fila4['ejec_at_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['ejec_vg_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['ejec_pf_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($fila4['ejec_ag_pdss'],2);?></td>
		</tr>
	<?
	}
	?>	



	</tbody>

</table>

<?
if ($modo<>excell)
{
?>
<p><br/></p>

<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="report_ejec_iniciativa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>
</body>
</html>
