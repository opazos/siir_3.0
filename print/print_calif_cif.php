<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	cif_bd_concurso.f_concurso, 
	cif_bd_concurso.n_concurso, 
	actividad1.descripcion AS nombre1, 
	actividad1.unidad AS unidad1, 
	cif_bd_concurso.costo, 
	pit_bd_ficha_mrn.sector, 
	actividad2.descripcion AS nombre2, 
	actividad2.unidad AS unidad2, 
	actividad3.descripcion AS nombre3, 
	actividad3.unidad AS unidad3
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 LEFT JOIN sys_bd_actividad_mrn actividad1 ON actividad1.cod = cif_bd_concurso.actividad_1
	 LEFT JOIN sys_bd_actividad_mrn actividad2 ON actividad2.cod = cif_bd_concurso.actividad_2
	 LEFT JOIN sys_bd_actividad_mrn actividad3 ON actividad3.cod = cif_bd_concurso.actividad_3
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result); 

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
  
   @page { size: A4 landscape; }
</style>
<!-- Fin -->
</head>

<body>

<!-- Actividad Nº 1 -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td align="center">FICHA DE REGISTRO Y VALORIZACIÓN DE ACTIVOS DEL PGRN - CONCURSO INTERFAMILIAR Nº <? echo $r1['n_concurso'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="27%">Organización que ejecuta el PGRN</td>
    <td width="44%"><? echo $r1['nombre'];?></td>
    <td width="12%">Fecha</td>
    <td width="17%"><? echo traducefecha($r1['f_concurso']);?></td>
  </tr>
  <tr>
    <td>Actividad de Concurso</td>
    <td width="44%"><? echo $r1['nombre1'];?></td>
    <td width="12%">Unidad de Medida</td>
    <td width="17%"><? echo $r1['unidad1'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="2%" rowspan="2">Nº</td>
    <td width="23%" rowspan="2">Nombre del Participante</td>
    <td width="6%" rowspan="2">Nº DNI</td>
    <td width="5%" rowspan="2">Cantidad del Activo Físico registrada antes de este concurso</td>
    <td width="5%" rowspan="2">Valor del Activo Físico registrado antes de este Concurso</td>
    <td width="5%" rowspan="2">Meta Física Lograda en este concurso</td>
    <td width="5%" rowspan="2">Valor de la Meta Física Lograda en este Concurso<br>
    (S/.)</td>
    <td width="8%" rowspan="2">Puntaje (De 1 a 20)</td>
    <td width="8%" rowspan="2">Puesto</td>
    <td colspan="2">Premio Recibido (En S/.)</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="8%">PDSS II</td>
    <td width="8%">Municipio u Otra Entidad</td>
  </tr>
<?
$n1=0;
$sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_participante_cif.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND cif_bd_participante_cif.n_documento = cif_bd_ficha_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_1
WHERE cif_bd_participante_cif.cod_concurso_cif='$cod'
ORDER BY cif_bd_ficha_cif.premio_pdss DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$n1++
?>  
  <tr>
    <td class="centrado"><? echo $n1;?></td>
    <td><? echo $f1['nombre']." ".$f1['paterno']."" .$f1['materno'];?></td>
    <td class="centrado"><? echo $f1['n_documento'];?></td>
    <td class="derecha"><? echo number_format($f1['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($f1['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($f1['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($f1['valor_2'],2);?></td>
    <td class="derecha"><? echo number_format($f1['puntaje'],2);?></td>
    <td class="derecha"><? echo number_format($f1['puesto']);?></td>
    <td class="derecha"><? echo number_format($f1['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f1['premio_otro'],2);?></td>
  </tr>
<?
}
$sql="SELECT SUM(cif_bd_ficha_cif.meta_1) AS meta_1, 
  SUM(cif_bd_ficha_cif.valor_1) AS valor_1, 
  SUM(cif_bd_ficha_cif.meta_2) AS meta_2, 
  SUM(cif_bd_ficha_cif.valor_2) AS valor_2, 
  SUM(cif_bd_ficha_cif.premio_pdss) AS premio_pdss, 
  SUM(cif_bd_ficha_cif.premio_otro) AS premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif AND cif_bd_concurso.actividad_1 = cif_bd_ficha_cif.cod_actividad
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);
?>   
  <tr class="txt_titulo">
    <td colspan="3">TOTALES</td>
    <td class="derecha"><? echo number_format($i1['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($i1['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($i1['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($i1['valor_2'],2);?></td>
    <td class="centrado">-</td>
    <td class="centrado">-</td>
    <td class="derecha"><? echo number_format($i1['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($i1['premio_otro'],2);?></td>
  </tr>

</table>





<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="10%">Jurado 1</td>
    <td width="37%">&nbsp;</td>
    <td width="12%">DNI Nº</td>
    <td width="12%">&nbsp;</td>
    <td width="8%">Firma</td>
    <td width="21%">&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 2</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 3</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>

<!-- Actividad Nº 2 -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td align="center">FICHA DE REGISTRO Y VALORIZACIÓN DE ACTIVOS DEL PGRN - CONCURSO INTERFAMILIAR Nº <? echo $r1['n_concurso'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="27%">Organización que ejecuta el PGRN</td>
    <td width="44%"><? echo $r1['nombre'];?></td>
    <td width="12%">Fecha</td>
    <td width="17%"><? echo traducefecha($r1['f_concurso']);?></td>
  </tr>
  <tr>
    <td>Actividad de Concurso</td>
    <td width="44%"><? echo $r1['nombre2'];?></td>
    <td width="12%">Unidad de Medida</td>
    <td width="17%"><? echo $r1['unidad2'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="2%" rowspan="2">Nº</td>
    <td width="23%" rowspan="2">Nombre del Participante</td>
    <td width="6%" rowspan="2">Nº DNI</td>
    <td width="5%" rowspan="2">Cantidad del Activo Físico registrada antes de este concurso</td>
    <td width="5%" rowspan="2">Valor del Activo Físico registrado antes de este Concurso</td>
    <td width="5%" rowspan="2">Meta Física Lograda en este concurso</td>
    <td width="5%" rowspan="2">Valor de la Meta Física Lograda en este Concurso<br>
(S/.)</td>
    <td width="8%" rowspan="2">Puntaje (De 1 a 20)</td>
    <td width="8%" rowspan="2">Puesto</td>
    <td colspan="2">Premio Recibido (En S/.)</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="8%">PDSS II</td>
    <td width="8%">Municipio u Otra Entidad</td>
  </tr>
  <?
$n3=0;
$sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_participante_cif.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND cif_bd_participante_cif.n_documento = cif_bd_ficha_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_2
WHERE cif_bd_participante_cif.cod_concurso_cif='$cod'
ORDER BY cif_bd_ficha_cif.premio_pdss DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$n3++
?>
  <tr>
    <td><? echo $n3;?></td>
    <td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
    <td><? echo $f3['n_documento'];?></td>
    <td class="derecha"><? echo number_format($f3['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($f3['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($f3['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($f3['valor_2'],2);?></td>
    <td class="derecha"><? echo number_format($f3['puntaje'],2);?></td>
    <td class="derecha"><? echo number_format($f3['puesto']);?></td>
    <td class="derecha"><? echo number_format($f3['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f3['premio_otro'],2);?></td>
  </tr>
<?
}
$sql="SELECT SUM(cif_bd_ficha_cif.meta_1) AS meta_1, 
  SUM(cif_bd_ficha_cif.valor_1) AS valor_1, 
  SUM(cif_bd_ficha_cif.meta_2) AS meta_2, 
  SUM(cif_bd_ficha_cif.valor_2) AS valor_2, 
  SUM(cif_bd_ficha_cif.premio_pdss) AS premio_pdss, 
  SUM(cif_bd_ficha_cif.premio_otro) AS premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif AND cif_bd_concurso.actividad_2 = cif_bd_ficha_cif.cod_actividad
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);
?>   
  <tr class="txt_titulo">
    <td colspan="3">TOTALES</td>
    <td class="derecha"><? echo number_format($i2['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($i2['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($i2['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($i2['valor_2'],2);?></td>
    <td class="centrado">-</td>
    <td class="centrado">-</td>
    <td class="derecha"><? echo number_format($i2['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($i2['premio_otro'],2);?></td>
  </tr>

</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="10%">Jurado 1</td>
    <td width="37%">&nbsp;</td>
    <td width="12%">DNI Nº</td>
    <td width="12%">&nbsp;</td>
    <td width="8%">Firma</td>
    <td width="21%">&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 2</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 3</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>

<!-- Actividad Nº 3 -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td align="center">FICHA DE REGISTRO Y VALORIZACIÓN DE ACTIVOS DEL PGRN - CONCURSO INTERFAMILIAR Nº <? echo $r1['n_concurso'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="27%">Organización que ejecuta el PGRN</td>
    <td width="44%"><? echo $r1['nombre'];?></td>
    <td width="12%">Fecha</td>
    <td width="17%"><? echo traducefecha($r1['f_concurso']);?></td>
  </tr>
  <tr>
    <td>Actividad de Concurso</td>
    <td width="44%"><? echo $r1['nombre3'];?></td>
    <td width="12%">Unidad de Medida</td>
    <td width="17%"><? echo $r1['unidad3'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="2%" rowspan="2">Nº</td>
    <td width="23%" rowspan="2">Nombre del Participante</td>
    <td width="6%" rowspan="2">Nº DNI</td>
    <td width="5%" rowspan="2">Cantidad del Activo Físico registrada antes de este concurso</td>
    <td width="5%" rowspan="2">Valor del Activo Físico registrado antes de este Concurso</td>
    <td width="5%" rowspan="2">Meta Física Lograda en este concurso</td>
    <td width="5%" rowspan="2">Valor de la Meta Física Lograda en este Concurso<br>
(S/.)</td>
    <td width="8%" rowspan="2">Puntaje (De 1 a 20)</td>
    <td width="8%" rowspan="2">Puesto</td>
    <td colspan="2">Premio Recibido (En S/.)</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="8%">PDSS II</td>
    <td width="8%">Municipio u Otra Entidad</td>
  </tr>
  <?
$n5=0;
$sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_participante_cif.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND cif_bd_participante_cif.n_documento = cif_bd_ficha_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_3
WHERE cif_bd_participante_cif.cod_concurso_cif='$cod'
ORDER BY cif_bd_ficha_cif.premio_pdss DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$n5++
?>
  <tr>
    <td><? echo $n5;?></td>
    <td><? echo $f2['nombre']." ".$f2['paterno']."" .$f2['materno'];?></td>
    <td><? echo $f2['n_documento'];?></td>
    <td class="derecha"><? echo number_format($f2['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($f2['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($f2['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($f2['valor_2'],2);?></td>
    <td class="derecha"><? echo number_format($f2['puntaje'],2);?></td>
    <td class="derecha"><? echo number_format($f2['puesto']);?></td>
    <td class="derecha"><? echo number_format($f2['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f2['premio_otro'],2);?></td>
  </tr>
<?
}
$sql="SELECT SUM(cif_bd_ficha_cif.meta_1) AS meta_1, 
  SUM(cif_bd_ficha_cif.valor_1) AS valor_1, 
  SUM(cif_bd_ficha_cif.meta_2) AS meta_2, 
  SUM(cif_bd_ficha_cif.valor_2) AS valor_2, 
  SUM(cif_bd_ficha_cif.premio_pdss) AS premio_pdss, 
  SUM(cif_bd_ficha_cif.premio_otro) AS premio_otro
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif AND cif_bd_concurso.actividad_3 = cif_bd_ficha_cif.cod_actividad
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i3=mysql_fetch_array($result);
?>   
  <tr class="txt_titulo">
    <td colspan="3">TOTALES</td>
    <td class="derecha"><? echo number_format($i3['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($i3['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($i3['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($i3['valor_2'],2);?></td>
    <td class="centrado">-</td>
    <td class="centrado">-</td>
    <td class="derecha"><? echo number_format($i3['premio_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($i3['premio_otro'],2);?></td>
  </tr>

</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="10%">Jurado 1</td>
    <td width="37%">&nbsp;</td>
    <td width="12%">DNI Nº</td>
    <td width="12%">&nbsp;</td>
    <td width="8%">Firma</td>
    <td width="21%">&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 2</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Jurado 3</td>
    <td>&nbsp;</td>
    <td>DNI Nº</td>
    <td>&nbsp;</td>
    <td>Firma</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../seguimiento/cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_calif" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>
</body>
</html>
