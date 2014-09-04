<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_participantes.xls");
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


<table cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
	
	<thead>
		<tr class="txt_titulo centrado">
			<td colspan="25">REPORTE CONSOLIDADO DE PARTICIPANTES DE INICIATIVAS DE PLANES DE NEGOCIO Y PLANES DE GESTIÓN - OFICINA LOCAL DE <? echo $row['oficina'];?></td>
		</tr>
	
		<tr class="txt_titulo">
			<th>Nº</th>
			<th>DNI</th>
			<th>NOMBRES</th>
			<th>PATERNO</th>
			<th>MATERNO</th>
			<th>FECHA NAC.</th>
			<th>SEXO</th>
			<th>JEFE DE FAMILIA</th>
			<th>HIJOS >5 AÑOS</th>
			<th>VIGENTE</th>
			<th>TIPO DOC. ORGANIZACION</th>
			<th>Nº DOCUMENTO</th>
			<th>NOMBRE DE LA ORGANIZACION</th>
			<th>TIPO ORGANIZACION</th>
			<th>TIPO INICIATIVA</th>
			<th>LINEA DE NEGOCIO</th>
			<th>DEPARTAMENTO</th>
			<th>PROVINCIA</th>
			<th>DISTRITO</th>
			<th>QUINTIL</th>
			<th>UBIGEO</th>
			<th>CENTRO POBLADO</th>
			<th>CATEGORIA</th>
			<th>OFICINA LOCAL</th>
			<th>ESTADO INICIATIVA</th>
		</tr>
	</thead>
	
	<tbody>
<?
$num=0;
if ($row['cod_dependencia']==001)
{
	$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.titular, 
	org_ficha_usuario.n_hijo, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	pit_bd_user_iniciativa.estado, 
	sys_bd_estado_iniciativa.descripcion AS estado_iniciativa, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_distrito.nivel_pobreza, 
	sys_bd_distrito.ubigeo, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_cp.cod_categoria
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>002 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003
ORDER BY pit_bd_user_iniciativa.n_documento ASC";
}
else
{
	$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.titular, 
	org_ficha_usuario.n_hijo, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	pit_bd_user_iniciativa.estado, 
	sys_bd_estado_iniciativa.descripcion AS estado_iniciativa, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_distrito.nivel_pobreza, 
	sys_bd_distrito.ubigeo, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_cp.cod_categoria
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>002 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003
ORDER BY pit_bd_user_iniciativa.n_documento ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
?>	
		<tr>
			<td class="centrado"><? echo $num;?></td>
			<td class="centrado">'<? echo $f1['dni'];?></td>
			<td class="centrado"><? echo $f1['nombre'];?></td>
			<td class="centrado"><? echo $f1['paterno'];?></td>
			<td class="centrado"><? echo $f1['materno'];?></td>
			<td class="centrado"><? echo fecha_normal($f1['f_nacimiento']);?></td>
			<td class="centrado"><? if ($f1['sexo']==1) echo "M"; else echo "F";?></td>
			<td class="centrado"><? if ($f1['titular']==1) echo "SI"; else echo "NO";?></td>
			<td class="centrado"><? echo $f1['n_hijo'];?></td>
			<td class="centrado"><? if ($f1['estado']==1) echo "VIGENTE"; else echo "RETIRADO";?></td>
			<td class="centrado"><? echo $f1['tipo_doc'];?></td>
			<td class="centrado"><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['org'];?></td>
			<td class="centrado"><? echo $f1['tipo_org'];?></td>
			<td class="centrado"><? echo $f1['tipo_iniciativa'];?></td>
			<td class="centrado"></td>
			<td class="centrado"><? echo $f1['departamento'];?></td>
			<td class="centrado"><? echo $f1['provincia'];?></td>
			<td class="centrado"><? echo $f1['distrito'];?></td>
			<td class="centrado"><? echo $f1['nivel_pobreza'];?></td>
			<td class="centrado"><? echo $f1['ubigeo'];?></td>
			<td class="centrado"><? echo $f1['cp'];?></td>
			<td class="centrado"><? echo $f1['cod_categoria'];?></td>
			<td class="centrado"><? echo $f1['oficina'];?></td>
			<td><? echo $f1['estado_iniciativa'];?></td>
		</tr>
<?
}
?>	
<?
$numa=$num;
if ($row['cod_dependencia']==1)
{
	$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.titular, 
	org_ficha_usuario.n_hijo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_user_iniciativa.estado, 
	sys_bd_estado_iniciativa.descripcion AS estado_iniciativa, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_distrito.nivel_pobreza, 
	sys_bd_distrito.ubigeo, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_cp.cod_categoria
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003
ORDER BY pit_bd_user_iniciativa.n_documento ASC";
}
else
{
	$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.titular, 
	org_ficha_usuario.n_hijo, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_user_iniciativa.estado, 
	sys_bd_estado_iniciativa.descripcion AS estado_iniciativa, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_distrito.nivel_pobreza, 
	sys_bd_distrito.ubigeo, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_cp.cod_categoria
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003
ORDER BY pit_bd_user_iniciativa.n_documento ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$numa++
?>	
		<tr>
			<td class="centrado"><? echo $numa;?></td>
			<td class="centrado">'<? echo $f2['dni'];?></td>
			<td class="centrado"><? echo $f2['nombre'];?></td>
			<td class="centrado"><? echo $f2['paterno'];?></td>
			<td class="centrado"><? echo $f2['materno'];?></td>
			<td class="centrado"><? echo fecha_normal($f2['f_nacimiento']);?></td>
			<td class="centrado"><? if ($f2['sexo']==1) echo "M"; else echo "F";?></td>
			<td class="centrado"><? if ($f2['titular']==1) echo "SI"; else echo "NO";?></td>
			<td class="centrado"><? echo $f2['n_hijo'];?></td>
			<td class="centrado"><? if ($f2['estado']==1) echo "VIGENTE"; else echo "RETIRADO";?></td>
			<td class="centrado"><? echo $f2['tipo_doc'];?></td>
			<td class="centrado"><? echo $f2['n_documento'];?></td>
			<td><? echo $f2['org'];?></td>
			<td class="centrado"><? echo $f2['tipo_org'];?></td>
			<td class="centrado"><? echo $f2['tipo_iniciativa'];?></td>
			<td class="centrado"><? echo $f2['linea'];?></td>
			<td class="centrado"><? echo $f2['departamento'];?></td>
			<td class="centrado"><? echo $f2['provincia'];?></td>
			<td class="centrado"><? echo $f2['distrito'];?></td>
			<td class="centrado"><? echo $f2['nivel_pobreza'];?></td>
			<td class="centrado"><? echo $f2['ubigeo'];?></td>
			<td class="centrado"><? echo $f2['cp'];?></td>
			<td class="centrado"><? echo $f2['cod_categoria'];?></td>
			<td class="centrado"><? echo $f2['oficina'];?></td>
			<td><? echo $f2['estado_iniciativa'];?></td>
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


<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="report_participantes.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<p><br/></p>
<?
}
?>

</body>
</html>
