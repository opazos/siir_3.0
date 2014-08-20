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


<table cellpadding="1" cellspacing="2" border="1" align="center" class="mini">
	
	<thead>
		<tr>
			<th>Nº Documento</th>
			<th>Tipo Documento</th>
			<th>Nombre de la Organizacion</th>
			<th>Tipo de Organizacion</th>
			<th>Oficina Local</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Departamento</th>
			<th>Tipo de Iniciativa</th>
			<th>Año</th>
			<th>Nº Desembolso</th>
			<th>Nº contrato/Nº Evento</th>
			<th>Fecha contrato/Fecha Evento</th>
			<th>Componente</th>
			<th>Subactividad POA</th>
			<th>Descripcion POA</th>
			<th>Categoria</th>
			<th>Monto (S/.)</th>
		</tr>
	</thead>
	
	<tbody>
<?
if ($row['cod_dependencia']==1)
{
$sql="SELECT epd_bd_demanda.f_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	epd_bd_demanda.n_evento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	SUM(epd_bd_presupuesto.monto) AS monto, 
	epd_bd_demanda.estado, 
	sys_bd_subactividad_poa.nombre AS descripcion
FROM sys_bd_dependencia INNER JOIN epd_bd_demanda ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
	 LEFT OUTER JOIN epd_bd_presupuesto ON epd_bd_presupuesto.cod_evento = epd_bd_demanda.cod_evento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = epd_bd_demanda.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = epd_bd_demanda.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = epd_bd_demanda.cod_dep
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = epd_bd_demanda.cod_tipo_iniciativa
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = epd_bd_demanda.cod_poa
	 INNER JOIN sys_bd_actividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE epd_bd_demanda.estado<>2
GROUP BY epd_bd_demanda.cod_evento
ORDER BY epd_bd_demanda.f_evento ASC";
}
else
{
$sql="SELECT epd_bd_demanda.f_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	epd_bd_demanda.n_evento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	SUM(epd_bd_presupuesto.monto) AS monto, 
	epd_bd_demanda.estado, 
	sys_bd_subactividad_poa.nombre AS descripcion
FROM sys_bd_dependencia INNER JOIN epd_bd_demanda ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
	 LEFT OUTER JOIN epd_bd_presupuesto ON epd_bd_presupuesto.cod_evento = epd_bd_demanda.cod_evento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = epd_bd_demanda.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = epd_bd_demanda.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = epd_bd_demanda.cod_dep
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = epd_bd_demanda.cod_tipo_iniciativa
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = epd_bd_demanda.cod_poa
	 INNER JOIN sys_bd_actividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE epd_bd_demanda.cod_dependencia='".$row['cod_dependencia']."' AND
epd_bd_demanda.estado<>2
GROUP BY epd_bd_demanda.cod_evento
ORDER BY epd_bd_demanda.f_evento ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>	
		<tr>
			<td class="centrado">N.A</td>
			<td class="centrado">N.A</td>
			<td>N.A</td>
			<td class="centrado">N.A</td>
			<td class="centrado"><? echo $f1['oficina'];?></td>
			<td class="centrado"><? echo $f1['distrito'];?></td>
			<td class="centrado"><? echo $f1['provincia'];?></td>
			<td class="centrado"><? echo $f1['departamento'];?></td>
			<td><? echo $f1['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo periodo($f1['f_evento']);?></td>
			<td class="centrado">PRIMERO</td>
			<td class="centrado"><? echo numeracion($f1['n_evento']);?></td>
			<td class="centrado"><? echo fecha_normal($f1['f_evento']);?></td>
			<td class="centrado"><? echo $f1['componente'];?></td>
			<td class="centrado"><? echo $f1['poa'];?></td>
			<td><? echo $f1['descripcion'];?></td>
			<td class="centrado"><? echo $f1['categoria'];?></td>
			<td class="derecha"><? echo number_format($f1['monto'],2);?></td>
		</tr>
<?
}
?>		
		
<?
if($row['cod_dependencia']==001)
{
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	ml_promocion_c.aporte_pdss AS monto, 
	ml_promocion_c.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_promocion_c.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_promocion_c.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE ml_promocion_c.cod_estado_iniciativa<>000
ORDER BY ml_promocion_c.f_contrato ASC";
}
else
{
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	ml_promocion_c.n_contrato, 
	ml_promocion_c.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	ml_promocion_c.aporte_pdss AS monto, 
	ml_promocion_c.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_promocion_c.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_promocion_c.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
ml_promocion_c.cod_estado_iniciativa<>000
ORDER BY ml_promocion_c.f_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>		
		<tr>
			<td class="centrado"><? echo $f2['n_documento'];?></td>
			<td class="centrado"><? echo $f2['tipo_doc'];?></td>
			<td><? echo $f2['organizacion'];?></td>
			<td class="centrado"><? echo $f2['tipo_org'];?></td>			
			<td class="centrado"><? echo $f2['oficina'];?></td>
			<td class="centrado"><? echo $f2['distrito'];?></td>
			<td class="centrado"><? echo $f2['provincia'];?></td>
			<td class="centrado"><? echo $f2['departamento'];?></td>
			<td><? echo $f2['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo periodo($f2['f_contrato']);?></td>
			<td class="centrado">PRIMERO</td>
			<td class="centrado"><? echo numeracion($f2['n_contrato']);?></td>
			<td class="centrado"><? echo fecha_normal($f2['f_contrato']);?></td>
			<td class="centrado"><? echo $f2['componente'];?></td>
			<td class="centrado"><? echo $f2['poa'];?></td>
			<td><? echo $f2['descripcion'];?></td>
			<td class="centrado"><? echo $f2['categoria'];?></td>
			<td class="derecha"><? echo number_format($f2['monto'],2);?></td>
		</tr>
<?
}
?>		
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	gcac_bd_evento_gc.aporte_pdss AS monto
FROM org_ficha_organizacion INNER JOIN gcac_bd_evento_gc ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = gcac_bd_evento_gc.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = gcac_bd_evento_gc.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = gcac_bd_evento_gc.cod_categoria
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE gcac_bd_evento_gc.cod_estado_iniciativa<>000
ORDER BY gcac_bd_evento_gc.f_contrato ASC";
}
else
{
$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	gcac_bd_evento_gc.n_contrato, 
	gcac_bd_evento_gc.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	gcac_bd_evento_gc.aporte_pdss AS monto
FROM org_ficha_organizacion INNER JOIN gcac_bd_evento_gc ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = gcac_bd_evento_gc.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = gcac_bd_evento_gc.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = gcac_bd_evento_gc.cod_categoria
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE gcac_bd_evento_gc.cod_estado_iniciativa<>000 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY gcac_bd_evento_gc.f_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>		
		<tr>
			<td class="centrado"><? echo $f3['n_documento'];?></td>
			<td class="centrado"><? echo $f3['tipo_doc'];?></td>
			<td><? echo $f3['nombre'];?></td>
			<td class="centrado"><? echo $f3['tipo_org'];?></td>
			<td class="centrado"><? echo $f3['oficina'];?></td>
			<td class="centrado"><? echo $f3['distrito'];?></td>
			<td class="centrado"><? echo $f3['provincia'];?></td>
			<td class="centrado"><? echo $f3['departamento'];?></td>
			<td><? echo $f3['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo periodo($f3['f_contrato']);?></td>
			<td class="centrado">PRIMERO</td>
			<td class="centrado"><? echo numeracion($f3['n_contrato']);?></td>
			<td class="centrado"><? echo fecha_normal($f3['f_contrato']);?></td>
			<td class="centrado"><? echo $f3['componente'];?></td>
			<td class="centrado"><? echo $f3['poa'];?></td>
			<td><? echo $f3['descripcion'];?></td>
			<td class="centrado"><? echo $f3['categoria'];?></td>
			<td class="derecha"><? echo number_format($f3['monto'],2);?></td>
		</tr>
<?
}
?>

<?
if ($row['cod_dependencia']==001)
{
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	ml_pf.aporte_pdss AS monto
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_pf.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_pf.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE ml_pf.cod_estado_iniciativa<>'000'
ORDER BY ml_pf.f_contrato ASC";
}
else
{
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_iniciativa, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS descripcion, 
	sys_bd_categoria_poa.codigo AS categoria, 
	ml_pf.aporte_pdss AS monto
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_pf.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_pf.cod_subactividad
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
WHERE ml_pf.cod_estado_iniciativa<>'000' AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY ml_pf.f_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<!-- Inicio Visitas Guiadas -->
		<tr>
			<td class="centrado"><? echo $f4['n_documento'];?></td>
			<td class="centrado"><? echo $f4['tipo_doc'];?></td>
			<td><? echo $f4['nombre'];?></td>
			<td class="centrado"><? echo $f4['tipo_org'];?></td>
			<td class="centrado"><? echo $f4['oficina'];?></td>
			<td class="centrado"><? echo $f4['distrito'];?></td>
			<td class="centrado"><? echo $f4['provincia'];?></td>
			<td class="centrado"><? echo $f4['departamento'];?></td>
			<td><? echo $f4['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo periodo($f4['f_contrato']);?></td>
			<td class="centrado">PRIMERO</td>
			<td class="centrado"><? echo numeracion($f4['n_contrato']);?></td>
			<td class="centrado"><? echo fecha_normal($f4['f_contrato']);?></td>
			<td class="centrado"><? echo $f4['componente'];?></td>
			<td class="centrado"><? echo $f4['poa'];?></td>
			<td><? echo $f4['descripcion'];?></td>
			<td class="centrado"><? echo $f4['categoria'];?></td>
			<td class="derecha"><? echo number_format($f4['monto'],2);?></td>
		</tr>	
<?
}
?>		
<!-- Fin Visitas Guiadas -->		
		
		
		<tr>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td class="centrado"></td>
			<td></td>
			<td class="centrado"></td>
			<td class="derecha"></td>
		</tr>			
		
	</tbody>
	
	
	
	
	
</table>

<p><br/></p>

<div class="capa">
	
	<a href="" class="secondary button">Imprimir</a>
	<a href="" class="success button">Exportar a Excell</a>
	<a href="../report/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button">Retornar al menú principal</a>
	
</div>



</body>
</html>
