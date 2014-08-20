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

$sql="SELECT org_ficha_organizacion.n_documento AS partida, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_idl.descripcion AS tipo_idl, 
	pit_bd_ficha_idl.denominacion, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
	 INNER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl.cod_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
WHERE clar_bd_ficha_idl.cod_clar='$cod' AND
clar_bd_jurado_evento_clar.calif_clar=1
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($row=mysql_fetch_array($result))
{
	$num++
?>

<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA PARA REALIZAR UNA INVERSION DE DESARROLLO LOCAL </div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>EVALUACION DE LA PRESENTACION PUBLICA</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">MUNICIPALIDAD</td>
    <td width="1%" align="center">:</td>
    <td width="76%"><b><? echo $row['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row['distrito'];?></td>
  </tr>
  <tr>
    <td>DENOMINACION DE LA INVERSION </td>
    <td align="center">:</td>
    <td><b><? echo $row['denominacion'];?></b></td>
  </tr>
  <tr>
    <td>TIPO DE IDL </td>
    <td align="center">:</td>
    <td><? echo $row['tipo_idl'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="24%" align="center"><strong>EJES</strong></td>
    <td width="53%" align="center"><strong>CRITERIOS</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="3">I.- Pertinencia (30 Puntos)</td>
    <td >1.1.- Tiene caracterisiticas de uso comunitario</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2.-. El plazo de ejecucion es razonable</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3.- No existen conflictos para la ejecucion (propiedad entre familias, etc.)</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="4">II. Importancia (40 puntos)</td>
    <td >2.1- Atiende a un amplio sector de la poblacion de la zona.</td>
    <td align="center">12</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2.- Se orienta a resolver algun problema de interés común.</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3.- Las caracteristicas tecnicas son de ejecucion sencilla</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.4. Contribuye favorablemente la protección y cuidado del al medio ambiente</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="4">III. Compromisos (30 puntos)</td>
    <td >3.1.- Cuenta con expediente tecnico</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.2. Esta claramente definido la instancia de ejecucion, operación y mantenimiento</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.3. La inversion y el financiamiento estan claramente propuestos</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.4..- La poblacion esta comprometida con el proyecto</td>
    <td align="center">7</td>
    <td align="center">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center"><strong>100</strong></td>
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
    <td><p>&nbsp;</p>
    </td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $row['lugar']."</b>, ".traducefecha($row['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>



<?

$sql="SELECT org_ficha_organizacion.n_documento AS partida, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_idl.descripcion AS tipo_idl, 
	pit_bd_ficha_idl.denominacion, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
	 INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl_2.cod_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
WHERE clar_bd_ficha_idl_2.cod_clar='$cod' AND
clar_bd_jurado_evento_clar.calif_clar=1
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
$num1=$num;
while($row1=mysql_fetch_array($result))
{
	$num1++
?>

<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row1['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA PARA REALIZAR UNA INVERSION DE DESARROLLO LOCAL - SEGUNDO DESEMBOLSO</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>EVALUACION DE LA PRESENTACION PUBLICA</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">MUNICIPALIDAD</td>
    <td width="1%" align="center">:</td>
    <td width="76%"><b><? echo $row1['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row1['distrito'];?></td>
  </tr>
  <tr>
    <td>DENOMINACION DE LA INVERSION </td>
    <td align="center">:</td>
    <td><b><? echo $row1['denominacion'];?></b></td>
  </tr>
  <tr>
    <td>TIPO DE IDL </td>
    <td align="center">:</td>
    <td><? echo $row1['tipo_idl'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="24%" align="center"><strong>EJES</strong></td>
    <td width="53%" align="center"><strong>CRITERIOS</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="5">I.- Ejecucion Fisica (50 puntos)</td>
    <td >1.1.- Se evidencia que la IDL se ejecuta en coherencia con el diseño y las normas tecnicas</td>
    <td align="center">15</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2.- El avance logrado permitirá concluir la obra en plazo razonable</td>
    <td align="center">15</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3.- Se evidencia que existen condiciones tecnicas, administrativas y legales favorables</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.4.- Las condiciones climaticas y ambientales son favorables para la ejecucion</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.5.- Se evidencian condiciones favorables para la operación y mantenimiento de la IDL</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3">II. Ejecución Financiera (35 puntos)</td>
    <td >2.1- Los aportes financieros han sido cumplidos, guardan coherencia con el avance fisico</td>
    <td align="center">15</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2.- Se evidencia que no habrán limitaciones financieras para concluir la IDL satisfactoriamente</td>
    <td align="center">15</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3.- Se ha logrado apalancar recursos financieros para concluir o mejorar la IDL</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3">III. Presentación y Participación (15 puntos)</td>
    <td >3.1.- Presentación clara y entendible por parte de la entidad responsable</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.2.- Participan potenciales beneficiarios de la IDL y contribuyen a la presentacion</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.3.- Se muestra evidencias que la población beneficiaria participa en la ejecución de la IDL</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center"><strong>100</strong></td>
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
    <td><p>&nbsp;</p>
    </td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $row1['lugar']."</b>, ".traducefecha($row1['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num1;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=idl" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>
