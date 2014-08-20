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
<table cellpadding="1" cellspacing="1" border="1" class="mini">
	<tr class="txt_titulo centrado">
		<td>DNI</td>
		<td>NOMBRES</td>
		<td>PATERNO</td>
		<td>MATERNO</td>
		<td>FECHA DE NACIMIENTO</td>
		<td>SEXO</td>
		<td>UBIGEO</td>
		<td>DIRECCION</td>
		<td>TIPO DE OFERENTE</td>
		<td>ESPECIALIDAD</td>
		<td>INICIO</td>
		<td>TERMINO</td>
		<td>TEMA DE ASISTENCIA TECNICA</td>
		<td>MUJERES CAPACITADAS</td>
		<td>VARONES CAPACITADOS</td>
		<td>CALIFICACION</td>
		<td>INICIATIVA</td>
		<td>NOMBRE DE LA ORGANIZACION</td>
		<td>DENOMINACION DEL PLAN DE NEGOCIO / LEMA DEL PLAN DE GESTION</td>
		<td>LINEA DE NEGOCIO</td>
		<td>OFICINA LOCAL</td>
	</tr>
<?
	if($row['cod_dependencia']==001)
	{
	$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.ubigeo, 
	ficha_ag_oferente.direccion, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	ficha_sat.tema, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_califica.descripcion AS calificacion
FROM ficha_sat INNER JOIN ficha_ag_oferente ON ficha_sat.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_sat.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
ORDER BY ficha_ag_oferente.nombre ASC, ficha_ag_oferente.paterno ASC, ficha_ag_oferente.materno ASC, organizacion ASC, ficha_sat.f_inicio ASC";
	}
	else
	{
	$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.ubigeo, 
	ficha_ag_oferente.direccion, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	ficha_sat.tema, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_califica.descripcion AS calificacion
FROM ficha_sat INNER JOIN ficha_ag_oferente ON ficha_sat.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_sat.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY ficha_ag_oferente.nombre ASC, ficha_ag_oferente.paterno ASC, ficha_ag_oferente.materno ASC, organizacion ASC, ficha_sat.f_inicio ASC";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
?>	
	<tr>
		<td class="centrado"><? echo $f1['n_documento'];?></td>
		<td><? echo $f1['nombre'];?></td>
		<td class="centrado"><? echo $f1['paterno'];?></td>
		<td class="centrado"><? echo $f1['materno'];?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_nacimiento']);?></td>
		<td class="centrado"><? if ($f1['sexo']==1) echo "M"; else echo "F";?></td>
		<td class="centrado"><? echo $f1['ubigeo'];?></td>
		<td><? echo $f1['direccion'];?></td>
		<td class="centrado"><? echo $f1['tipo_oferente'];?></td>
		<td><? echo $f1['especialidad'];?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_inicio']);?></td>
		<td class="centrado"><? echo fecha_normal($f1['f_termino']);?></td>
		<td><? echo $f1['tema'];?></td>
		<td class="derecha"><? echo number_format($f1['n_mujeres']);?></td>
		<td class="derecha"><? echo number_format($f1['n_varones']);?></td>
		<td class="centrado"><? echo $f1['calificacion'];?></td>
		<td class="centrado">PDN</td>
		<td><? echo $f1['organizacion'];?></td>
		<td><? echo $f1['denominacion'];?></td>
		<td class="centrado"><? echo $f1['linea'];?></td>
		<td class="centrado"><? echo $f1['oficina'];?></td>
	</tr>
<?
}
if ($row['cod_dependencia']==001)
{
	$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.ubigeo, 
	ficha_ag_oferente.direccion, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	sys_bd_actividad_mrn.descripcion AS tema, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	sys_bd_califica.descripcion AS calificacion, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_mrn.lema, 
	sys_bd_dependencia.nombre AS oficina
FROM sys_bd_tipo_oferente INNER JOIN ficha_ag_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN ficha_sat ON ficha_sat.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_sat.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = ficha_sat.tema
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
ORDER BY ficha_sat.f_inicio ASC";
}
else
{
	$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.ubigeo, 
	ficha_ag_oferente.direccion, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	sys_bd_actividad_mrn.descripcion AS tema, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	sys_bd_califica.descripcion AS calificacion, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_mrn.lema, 
	sys_bd_dependencia.nombre AS oficina
FROM sys_bd_tipo_oferente INNER JOIN ficha_ag_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN ficha_sat ON ficha_sat.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_sat.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = ficha_sat.tema
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY ficha_sat.f_inicio ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>	
	<tr>
		<td class="centrado"><? echo $f2['n_documento'];?></td>
		<td><? echo $f2['nombre'];?></td>
		<td class="centrado"><? echo $f2['paterno'];?></td>
		<td class="centrado"><? echo $f2['materno'];?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_nacimiento']);?></td>
		<td class="centrado"><? if ($f2['sexo']==1) echo "M"; else echo "F";?></td>
		<td class="centrado"><? echo $f2['ubigeo'];?></td>
		<td><? echo $f2['direccion'];?></td>
		<td class="centrado"><? echo $f2['tipo_oferente'];?></td>
		<td><? echo $f2['especialidad'];?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_inicio']);?></td>
		<td class="centrado"><? echo fecha_normal($f2['f_termino']);?></td>
		<td><? echo $f2['tema'];?></td>
		<td class="derecha"><? echo number_format($f2['n_mujeres']);?></td>
		<td class="derecha"><? echo number_format($f2['n_varones']);?></td>
		<td class="centrado"><? echo $f2['calificacion'];?></td>
		<td class="centrado">MRN</td>
		<td><? echo $f2['organizacion'];?></td>
		<td><? echo $f2['lema'];?></td>
		<td class="centrado">-</td>
		<td class="centrado"><? echo $f2['oficina'];?></td>
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
	<a href="report_oferente.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>	
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
<?
}
?>
</body>
</html>
