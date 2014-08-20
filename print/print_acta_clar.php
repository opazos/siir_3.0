<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Buscamos la info del acta
$sql="SELECT clar_bd_acta.cod_acta, 
	clar_bd_acta.n_acta, 
	clar_bd_acta.contenido, 
	clar_bd_acta.cod_clar, 
	sys_bd_dependencia.nombre, 
	sys_bd_personal.n_documento, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos
FROM clar_bd_acta INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_acta.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE clar_bd_acta.cod_acta='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//2.- verificamos si existe info en las fichas de calificacion de PDN
$sql="SELECT DISTINCT clar_bd_jurado_evento_clar.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion
FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN clar_calif_ficha_pdn_clar ON clar_calif_ficha_pdn_clar.cod_jurado = clar_bd_jurado_evento_clar.cod_jurado
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_jurado_evento_clar.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

$sql="SELECT DISTINCT clar_bd_jurado_evento_clar.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion
FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN clar_calif_ficha_pdn_clar_2 ON clar_calif_ficha_pdn_clar_2.cod_jurado = clar_bd_jurado_evento_clar.cod_jurado
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_jurado_evento_clar.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_2=mysql_num_rows($result);

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
<div class="capa txt_titulo" align="center"><u>ACTA Nº <? echo numeracion($row['n_acta']);?><br>REUNIÓN DEL COMITÉ LOCAL DE ASIGNACIÓN DE RECURSOS OFICINA LOCAL <? echo $row['nombre'];?></u></div>
<br>
<? echo $row['contenido'];?>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4" class="mini">
  <tr>
    <td width="11%" align="center"><strong>N°</strong></td>
    <td width="12%" align="center"><strong>DNI</strong></td>
    <td width="52%" align="center"><strong>NOMBRES Y APELLIDOS</strong></td>
    <td width="25%" align="center"><strong>FIRMA</strong></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><hr></td>
  </tr>
<?
$sql="SELECT clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_jurado_evento_clar INNER JOIN clar_bd_miembro ON clar_bd_jurado_evento_clar.cod_tipo_doc = clar_bd_miembro.cod_tipo_doc AND clar_bd_jurado_evento_clar.n_documento = clar_bd_miembro.n_documento
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_jurado_evento_clar.cod_clar
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_evento_clar.cod_clar='".$row['cod_clar']."' AND
clar_bd_jurado_evento_clar.calif_clar=1";
$result=mysql_query($sql) or die (mysql_error());

$total_jurado_clar=mysql_num_rows($result);

if($total_jurado_clar<>0)
{
$num3=0;
while($r4=mysql_fetch_array($result))
{
	$num3++
?>
  <tr>
    <td align="center"><? echo $num3;?></td>
    <td align="center"><? echo $r4['n_documento'];?></td>
    <td><? echo $r4['nombre']." ".$r4['paterno']." ".$r4['materno']." - ".$r4['cargo'];?></td>
    <td align="center">_____________________________________</td>
  </tr>
  <?
}
}
else
{
$sql="SELECT clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_jurado_evento_clar INNER JOIN clar_bd_miembro ON clar_bd_jurado_evento_clar.cod_tipo_doc = clar_bd_miembro.cod_tipo_doc AND clar_bd_jurado_evento_clar.n_documento = clar_bd_miembro.n_documento
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_jurado_evento_clar.cod_clar
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_evento_clar.cod_clar='".$row['cod_clar']."' AND
clar_bd_jurado_evento_clar.calif_campo=1";
$result=mysql_query($sql) or die (mysql_error());
$num4=0;	
while($r5=mysql_fetch_array($result))
{
	$num4++
?>
  <tr>
    <td align="center"><? echo $num4;?></td>
    <td align="center"><? echo $r5['n_documento'];?></td>
    <td><? echo $r5['nombre']." ".$r5['paterno']." ".$r5['materno']." - ".$r5['cargo'];?></td>
    <td align="center">_____________________________________</td>
  </tr>
<?php	
}
}
?>



</table>

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>
