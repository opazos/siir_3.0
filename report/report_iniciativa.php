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

$sql="SELECT *, 
	sys_bd_dependencia.nombre AS oficina
FROM sys_bd_dependencia INNER JOIN sys_bd_personal ON sys_bd_dependencia.cod_dependencia = sys_bd_personal.cod_dependencia
WHERE md5(n_documento)='$SES'";
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
<table class="mini" cellpadding="1" cellspacing="1" border="1">
	<thead>
	<tr>
		<th colspan="24" class="centrado">REPORTE DE INICIATIVAS TERRITORIALES POR OFICINA LOCAL - <? echo $row['oficina'];?></th>
	</tr>
		<tr>
			<th>Codigo PIT</th>
			<th>Fecha Contrato</th>
			<th>Contrato-Año</th>
			<th>Tiempo de Ejecucion</th>
			<th>Fecha de Termino</th>
			<th>Nombre Organizacion PIT</th>
			<th>Tipo de Iniciativa</th>
			<th>Nº Documento</th>
			<th>Nombre de la organizacion</th>
			<th>Estado de la Iniciativa</th>
			<th>Linea de Negocio</th>
			<th>Denominacion</th>
			<th>Centro Poblado</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Oficina</th>
			<th>Monto PDSS II</th>
			<th>Monto Organizacion</th>
			<th>Monto Total</th>
			<th>N. CLAR</th>
			<th>Nombre CLAR</th>
			<th>Fecha CLAR</th>
			<th>Estado del Plazo</th>
		</tr>
	</thead>
	
	<tbody>
<?
//1.- Caso 1 - PGRN
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_mrn.cod_pit, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 LEFT JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 LEFT JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn.cod_clar
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_mrn.cod_pit, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 LEFT JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 LEFT JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn.cod_clar
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>	
		<tr>
			<td class="centrado"><? echo $f1['cod_pit'];?></td>
			<td class="centrado"><? echo fecha_normal($f1['f_contrato']);?></td>
			<td class="centrado"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></td>
			<td class="centrado"><? echo $f1['mes'];?></td>
			<td class="centrado">
			<? 
			$fecha = $f1['f_contrato'];
			$mes= $f1['mes'];
			$nuevafecha = strtotime ( "+ ".$mes." month" , strtotime ( $fecha ) ) ;
			$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
			echo fecha_normal($nuevafecha);
			?>
			</td>
			<td><? echo $f1['pit'];?></td>
			<td class="centrado"><? echo $f1['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['organizacion'];?></td>
			<td class="centrado"><? echo $f1['estado'];?></td>
			<td class="centrado"></td>
			<td></td>
			<td class="centrado"><? echo $f1['sector'];?></td>
			<td class="centrado"><? echo $f1['distrito'];?></td>
			<td class="centrado"><? echo $f1['provincia'];?></td>
			<td class="centrado"><? echo $f1['departamento'];?></td>
			<td class="centrado"><? echo $f1['oficina'];?></td>
			<td class="derecha"><? echo number_format($f1['aporte_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($f1['aporte_org'],2);?></td>
			<td class="derecha"><? echo number_format($f1['aporte_pdss']+$f1['aporte_org'],2);?></td>
			<td class="centrado"><? echo numeracion($f1['cod_clar']);?></td>
			<td><? echo $f1['nombre'];?></td>
			<td class="centrado"><? echo fecha_normal($f1['f_evento']);?></td>
			<td class="centrado"><? if ($fecha_hoy>$nuevafecha) echo "Plazo Vencido";?></td>
		</tr>
<?
}
?>		

<?
//2.- Caso 2 - PDN perteneciente a un PIT
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.cod_pit, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=1 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.cod_pit, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=1 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY pit_bd_ficha_pit.n_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>		
		<tr>
			<td class="centrado"><? echo $f2['cod_pit'];?></td>
			<td class="centrado"><? echo fecha_normal($f2['f_contrato']);?></td>
			<td class="centrado"><? echo numeracion($f2['n_contrato'])."-".periodo($f2['f_contrato']);?></td>
			<td class="centrado"><? echo $f2['mes'];?></td>
			<td class="centrado">
			<? 
			$fecha_1 = $f2['f_contrato'];
			$mes_1= $f2['mes'];
			$nuevafecha_1 = strtotime ( "+ ".$mes_1." month" , strtotime ( $fecha_1 ) ) ;
			$nuevafecha_1 = date ( 'Y-m-d' , $nuevafecha_1 );
			echo fecha_normal($nuevafecha_1);
			?>
			</td>
			<td><? echo $f2['pit'];?></td>
			<td class="centrado">PDN/PIT</td>
			<td class="centrado"><? echo $f2['n_documento'];?></td>
			<td><? echo $f2['organizacion'];?></td>
			<td class="centrado"><? echo $f2['estado'];?></td>
			<td class="centrado"><? echo $f2['linea'];?></td>
			<td><? echo $f2['denominacion'];?></td>
			<td class="centrado"><? echo $f2['sector'];?></td>
			<td class="centrado"><? echo $f2['distrito'];?></td>
			<td class="centrado"><? echo $f2['provincia'];?></td>
			<td class="centrado"><? echo $f2['departamento'];?></td>
			<td class="centrado"><? echo $f2['oficina'];?></td>
			<td class="derecha"><? echo number_format($f2['aporte_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($f2['aporte_org'],2);?></td>
			<td class="derecha"><? echo number_format($f2['aporte_pdss']+$f2['aporte_org'],2);?></td>
			<td class="centrado"><? echo numeracion($f2['cod_clar']);?></td>
			<td><? echo $f2['nombre'];?></td>
			<td class="centrado"><? echo fecha_normal($f2['f_evento']);?></td>
			<td class="centrado"><? if ($fecha_hoy>$nuevafecha_1) echo "Plazo Vencido";?></td>
		</tr>
<?
}
?>		

<?
//3.- Caso 3.- Planes de Negocio Sueltos
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.cod_pit, 
	pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.cod_pit, 
	pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.nombre
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
}
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>		
		<tr>
			<td class="centrado">N/A</td>
			<td class="centrado"><? echo fecha_normal($f3['f_contrato']);?></td>
			<td class="centrado"><? echo numeracion($f3['n_contrato'])."-".periodo($f3['f_contrato']);?></td>
			<td class="centrado"><? echo $f3['mes'];?></td>
			<td class="centrado">
			<? 
			$fecha_2 = $f3['f_contrato'];
			$mes_2= $f3['mes'];
			$nuevafecha_2 = strtotime ( "+ ".$mes_2." month" , strtotime ( $fecha_2) ) ;
			$nuevafecha_2 = date ( 'Y-m-d' , $nuevafecha_2 );
			echo fecha_normal($nuevafecha_2);
			?>
			</td>
			<td><? echo $f3['pit'];?></td>
			<td class="centrado">PDN/SUELTO</td>
			<td class="centrado"><? echo $f3['n_documento'];?></td>
			<td><? echo $f3['organizacion'];?></td>
			<td class="centrado"><? echo $f3['estado'];?></td>
			<td class="centrado"><? echo $f3['linea'];?></td>
			<td><? echo $f3['denominacion'];?></td>
			<td class="centrado"><? echo $f3['sector'];?></td>			
			<td class="centrado"><? echo $f3['distrito'];?></td>
			<td class="centrado"><? echo $f3['provincia'];?></td>
			<td class="centrado"><? echo $f3['departamento'];?></td>
			<td class="centrado"><? echo $f3['oficina'];?></td>
			<td class="derecha"><? echo number_format($f3['aporte_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($f3['aporte_org'],2);?></td>
			<td class="derecha"><? echo number_format($f3['aporte_pdss']+$f3['aporte_org'],2);?></td>
			<td class="centrado"><? echo numeracion($f3['cod_clar']);?></td>
			<td><? echo $f3['nombre'];?></td>
			<td class="centrado"><? echo fecha_normal($f3['f_evento']);?></td>
			<td class="centrado"><? if ($fecha_hoy>$nuevafecha_2) echo "Plazo Vencido";?></td>
		</tr>	
<?
}
?>		

<?
//Caso 4.- Plan de Negocio Perteneciente a una Ampliacion
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	clar_ampliacion_pit.n_ampliacion, 
	clar_ampliacion_pit.f_ampliacion, 
	pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit pit_nuevo INNER JOIN pit_bd_ficha_pdn ON pit_nuevo.cod_pit = pit_bd_ficha_pdn.cod_pit
	 LEFT JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 LEFT JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_nuevo.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_nuevo.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
}
else
{
$sql="SELECT pit_bd_ficha_pdn.mes, 
	org_ficha_taz.nombre AS pit, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	(pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	clar_ampliacion_pit.n_ampliacion, 
	clar_ampliacion_pit.f_ampliacion, 
	pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_cp.nombre AS sector, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_pit pit_nuevo INNER JOIN pit_bd_ficha_pdn ON pit_nuevo.cod_pit = pit_bd_ficha_pdn.cod_pit
	 LEFT JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 LEFT JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_nuevo.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_nuevo.n_documento_taz
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
}
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>		
		<tr>
			<td class="centrado"><? echo $f4['cod_pit'];?></td>
			<td class="centrado"><? echo fecha_normal($f4['f_ampliacion']);?></td>
			<td class="centrado"><? echo numeracion($f4['n_contrato'])."-".periodo($f4['f_contrato']);?></td>
			<td class="centrado"><? echo $f4['mes'];?></td>
			<td class="centrado">
			<? 
			$fecha_3 = $f4['f_ampliacion'];
			$mes_3= $f4['mes'];
			$nuevafecha_3 = strtotime ( "+ ".$mes_3." month" , strtotime ( $fecha_3) ) ;
			$nuevafecha_3 = date ( 'Y-m-d' , $nuevafecha_3 );
			echo fecha_normal($nuevafecha_3);
			?>
			</td>
			<td><? echo $f4['pit'];?></td>
			<td class="centrado">PDN/AMPLIACION</td>
			<td class="centrado"><? echo $f4['n_documento'];?></td>
			<td><? echo $f4['organizacion'];?></td>
			<td class="centrado"><? echo $f4['estado'];?></td>
			<td class="centrado"><? echo $f4['linea'];?></td>
			<td><? echo $f4['denominacion'];?></td>
			<td class="centrado"><? echo $f4['sector'];?></td>			
			<td class="centrado"><? echo $f4['distrito'];?></td>
			<td class="centrado"><? echo $f4['provincia'];?></td>
			<td class="centrado"><? echo $f4['departamento'];?></td>
			<td class="centrado"><? echo $f4['oficina'];?></td>
			<td class="derecha"><? echo number_format($f4['aporte_pdss'],2);?></td>
			<td class="derecha"><? echo number_format($f4['aporte_org'],2);?></td>
			<td class="derecha"><? echo number_format($f4['aporte_pdss']+$f4['aporte_org'],2);?></td>
			<td class="centrado"><? echo numeracion($f4['cod_clar']);?></td>
			<td><? echo $f4['nombre'];?></td>
			<td class="centrado"><? echo fecha_normal($f4['f_evento']);?></td>			
			<td class="centrado"><? if ($fecha_hoy>$nuevafecha_3) echo "Plazo Vencido";?></td>
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
	<a href="report_iniciativa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>


</body>
</html>
