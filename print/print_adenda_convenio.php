<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_adenda_convenio.cod, 
	pit_bd_ficha_adenda_convenio.n_adenda, 
	pit_bd_ficha_adenda_convenio.f_adenda, 
	pit_bd_ficha_adenda_convenio.f_termino, 
	pit_bd_ficha_adenda_convenio.contenido, 
	gcac_bd_ficha_convenio.n_convenio, 
	gcac_bd_ficha_convenio.f_inicio, 
	gcac_bd_ficha_convenio.f_termino AS f_fin, 
	gcac_bd_ficha_convenio.f_presentacion, 
	gcac_bd_ficha_convenio.dni, 
	gcac_bd_ficha_convenio.representante, 
	gcac_bd_ficha_convenio.cargo, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_personal.n_documento AS dnis
FROM gcac_bd_ficha_convenio INNER JOIN pit_bd_ficha_adenda_convenio ON gcac_bd_ficha_convenio.cod_ficha = pit_bd_ficha_adenda_convenio.id_convenio
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_ficha_convenio.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_bd_ficha_convenio.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gcac_bd_ficha_convenio.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_adenda_convenio.cod='$cod'";
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

<!-- -->
<div class="capa txt_titulo centrado">ADENDA AL CONVENIO Nº <? echo numeracion($row['n_convenio'])."-".periodo($row['f_presentacion']);?> OL <? echo $row['oficina'];?> DE COOPERACIÓN INTERINSTITUCIONAL ENTRE EL PROYECTO “SIERRA SUR II” Y LA <? echo $row['nombre'];?>
</div>
<p></p>
<!-- Inicio del contenido -->
<div>
<? echo $row['contenido'];?>
</div>
<!-- Termino del contenido -->
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="40%" align="center">_______________</td>
    <td width="20%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
  </tr>
  <tr class="txt_titulo">
    <td align="center"><? echo $row['representante']."<br/>".$row['cargo'];?></td>
    <td align="center">&nbsp;</td>
    <td align="center">JOSE MERCEDES SIALER PASCO<br/>DIRECTOR EJECUTIVO DEL PROYECTO DE DESARROLLO SIERRA SUR </td>
  </tr>
</table>



<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>