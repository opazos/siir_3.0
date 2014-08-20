<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();
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
<?
$sql="SELECT org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_taz.nombre AS org_pit, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	sys_bd_dependencia.nombre AS oficina
FROM clar_bd_ficha_pdn INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
WHERE clar_bd_ficha_pdn.cod_clar ='$cod' AND
clar_bd_jurado_evento_clar.calif_clar=1
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error);
$num=0;
while($row=mysql_fetch_array($result))
{
	$num++
?>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS - PDN</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>SEGUNDA FASE</u>: <u>EVALUACION DE LA PRESENTACION PUBLICA</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="24%">ORGANIZACION TERRITORIAL</td>
    <td width="1%" align="center">:</td>
    <td width="75%"><b><? echo $row['org_pit'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row['distrito'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACION DEL PDN</td>
    <td align="center">:</td>
    <td><b><? echo $row['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>DENOMINACION DEL PLAN DE NEGOCIO</td>
    <td align="center">:</td>
    <td><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="20%" align="center"><strong>EJES</strong></td>
    <td width="18%" align="center"><strong>CRITERIOS</strong></td>
    <td width="39%" align="center"><strong>DESCRIPCION</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="5">Presentación del PDN (50 puntos)</td>
    <td rowspan="2" >1.1 Mapa Cultural</td>
    <td><br>Se evidencia la incorporación de la iniciativa en el Mapa Cultural del PIT<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><br>Participación en la costrucción del Mapa Cultural del PIT<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.2 Claridad</td>
    <td><br>Evalúa la claridad en la presentación del PDN, utilizando los medios que son accesibles o ingeniados por el grupo (fotos, mapas, productos, sociodrama, etc)<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.3 Identidad y cultura</td>
    <td><br>Evalúa los aspectos socioculturales que se reflejan en la presentación del Grupo (vestimenta, idioma, música y danza, etc)<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.4 Participación de los socios, mujeres, jovenes y niños</td>
    <td><br>Evalúa el nivel de participación de los socios de la organización, mujeres, jovenes y niños en la presentación del PDN.<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center"><strong>50</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nombres y apellidos del Evaluador</td>
    <td>:</td>
    <td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $row['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><b><? echo $row['lugar']."</b>, ".traducefecha($row['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>

<!-- PDN sueltos -->
<?
$num1=$num;
$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	clar_bd_miembro.n_documento, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar
FROM clar_bd_ficha_pdn_suelto INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_miembro.cod_dependencia
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
WHERE pit_bd_ficha_pdn.tipo=1 AND
clar_bd_jurado_evento_clar.calif_clar=1 AND
clar_bd_ficha_pdn_suelto.cod_clar='$cod'
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
while($row2=mysql_fetch_array($result))
{
$num1++
?>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row2['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS - PDN</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>SEGUNDA FASE</u>: <u>EVALUACION DE LA PRESENTACION PUBLICA</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">

  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row2['distrito'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACION DEL PDN</td>
    <td align="center">:</td>
    <td><b><? echo $row2['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>DENOMINACION DEL PLAN DE NEGOCIO</td>
    <td align="center">:</td>
    <td><? echo $row2['denominacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="20%" align="center"><strong>EJES</strong></td>
    <td width="18%" align="center"><strong>CRITERIOS</strong></td>
    <td width="39%" align="center"><strong>DESCRIPCION</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="5">Presentación del PDN (50 puntos)</td>
    <td rowspan="2" >1.1 Mapa Cultural</td>
    <td><br>Se evidencia la incorporación de la iniciativa en el Mapa Cultural del PIT<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><br>Participación en la costrucción del Mapa Cultural del PIT<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.2 Claridad</td>
    <td><br>Evalúa la claridad en la presentación del PDN, utilizando los medios que son accesibles o ingeniados por el grupo (fotos, mapas, productos, sociodrama, etc)<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.3 Identidad y cultura</td>
    <td><br>Evalúa los aspectos socioculturales que se reflejan en la presentación del Grupo (vestimenta, idioma, música y danza, etc)<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.4 Participación de los socios, mujeres, jovenes y niños</td>
    <td><br>Evalúa el nivel de participación de los socios de la organización, mujeres, jovenes y niños en la presentación del PDN.<br><br></td>
    <td align="center"><strong>10</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center"><strong>50</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nombres y apellidos del Evaluador</td>
    <td>:</td>
    <td><? echo $row2['nombre']." ".$row2['paterno']." ".$row2['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $row2['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><b><? echo $row2['lugar']."</b>, ".traducefecha($row2['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num1;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>










<?
$num2=$num1;
$sql="SELECT org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_taz.nombre AS org_pit, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 LEFT JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_2.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_ficha_pdn_2.cod_clar = '$cod'   AND
clar_bd_jurado_evento_clar.calif_clar=1
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
while($row1=mysql_fetch_array($result))
{
	$num2++
?>
<? include("encabezado.php");?>

<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row1['oficina'];?></div>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS-PDN - SEGUNDO DESEMBOLSO</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>PRESENTACIÓN PÚBLICA </u></DIV>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="24%">ORGANIZACION TERRITORIAL</td>
    <td width="1%" align="center">:</td>
    <td width="75%"><b><? echo $row1['org_pit'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row1['distrito'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACION DEL PDN</td>
    <td align="center">:</td>
    <td><b><? echo $row1['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>DENOMINACION DEL PLAN DE NEGOCIO</td>
    <td align="center">:</td>
    <td><? echo $row1['denominacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="20%" align="center"><strong>EJES</strong></td>
    <td width="18%" align="center"><strong>CRITERIOS</strong></td>
    <td width="39%" align="center"><strong>DESCRIPCION</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="6">I.- Presentación del PDN (50 puntos) </td>
    <td >1.1 Enfoque Territorial </td>
    <td>Se evidencia que el PDN y sus avances contribuyen al enfoque de desarrollo Territorial</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2 Manejo de Fondos </td>
    <td>Los responsables del PDN demuestran conocimiento y capacidad para el manejo de fondos. Se demuestra que los fondos se administran bien</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3 Avances en la ejecución del PGRN </td>
    <td>Se percibe que los avances logrados en el desarrollo del negocio guardan relacion con los fondos utilizados y con la propuesta del PDN (Asistencia Técnica, Visita Guiada, Ferias, etc.)</td>
    <td align="center">12</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.4 Claridad </td>
    <td>Evalúa la claridad en la presentación de los avances a cargo de los directivos y socios del PDN (fotos, productos, registros, etc.) </td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.5 Identidad y cultura </td>
    <td>Evalúa los aspectos socioculturales que se reflejan en la presentación del Grupo (vestimenta, idioma, música y danza, etc.)</td>
    <td align="center">7</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.6 Participación de los socios, mujeres jovenes y niños </td>
    <td>Evalua el nivel de participación de los socios de la organización, mujeres y jovenes en la presentación del PDN</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center"><strong>50</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nombres y apellidos del Evaluador</td>
    <td>:</td>
    <td><? echo $row1['nombre']." ".$row1['paterno']." ".$row1['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $row1['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><b><? echo $row1['lugar']."</b>, ".traducefecha($row1['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num2;?>-</div>
<H1 class=SaltoDePagina></H1>
<?
}
?>
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_clar" class="secondary button oculto">Finalizar</a>
</div>
</body>
</html>
