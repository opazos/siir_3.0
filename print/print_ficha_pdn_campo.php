<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Buscamos los usuarios
$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row3=mysql_fetch_array($result);

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
WHERE clar_bd_jurado_evento_clar.calif_campo=1 AND
org_ficha_organizacion.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_ficha_pdn.cod_clar='$cod'
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error);
$num=0;
while($row=mysql_fetch_array($result))
{
	$num++
?>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row['oficina'];?></div>

<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS-PDN</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
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

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="20%" align="center"><strong>EJES</strong></td>
    <td width="18%" align="center"><strong>CRITERIOS</strong></td>
    <td width="39%" align="center"><strong>DESCRIPCION</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="2">I. Nivel de participación de las familias (10 puntos)</td>
    <td rowspan="2" >1.1 Comprueba en campo a las familias realmente comprometidas, comprobación de DNIs</td>
    <td>a) Menos del 50% de familias que participan en el PDN, presentes en la evaluación de campo (Descalifica)</td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td>b) De 51% a 100% de las familias que participan en el PDN (hasta 10 puntos)</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal de Nivel de participación de las familias</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3">II. Organización Responsable del PDN (10 puntos)</td>
    <td >1.1 Relación con los Directivos de la Organización territorial</td>
    <td>Observa y percibe las relaciones existentes con los directivos de la organización territorial</td>
    <td align="center">2</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2 Capacidad de gestión</td>
    <td>Logros obtenidos con el apoyo de otras instituciones, participación en otras instancias vinculadas con el desarrollo, otros</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3 Mujer y jóvenes</td>
    <td>Percepción del grado de interés y participación de mujeres y jóvenes en la propuesta del PDN o como integrantes de la Junta Directiva</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Organización responsable del PDN</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5">III. Coherencia del Plan de Negocio (30 puntos)</td>
    <td >2.1 Coherencia de actividades</td>
    <td>Refleja de manera objetiva si las actividades propuestas en el PDN guardan coherencia con las potencialidades de sus recursos</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2 Recursos destinados al PDN</td>
    <td>Percepción sobre la voluntad de destinar sus recursos en el PDN y posibilidades de incremento de sus ingresos</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3 Plazo de ejecución</td>
    <td>Se verifica si existen las condiciones para el cumplimiento de los resultados esperados en el plazo propuesto en el PDN, en el marco del horizonte del proyecto</td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.4 Actividades conexas al PDN</td>
    <td>Pertinencia de las visitas guiadas y participación en ferias con la propuesta del plan de negocio</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.5 Aportes de las familias</td>
    <td>Predisposición de las familias para cofinanciar el Plan de Negocio</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Coherencia del PDN</td>
    <td align="center">30</td>
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
    <td>&nbsp;</td>
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
clar_bd_jurado_evento_clar.calif_campo=1 AND
org_ficha_organizacion.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_ficha_pdn_suelto.cod_clar='$cod'
ORDER BY pit_bd_ficha_pdn.cod_pdn ASC, clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
while($row2=mysql_fetch_array($result))
{
$num1++
?>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row2['oficina'];?></div>

<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS-PDN</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
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

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_parrafo">
    <td width="20%" align="center"><strong>EJES</strong></td>
    <td width="18%" align="center"><strong>CRITERIOS</strong></td>
    <td width="39%" align="center"><strong>DESCRIPCION</strong></td>
    <td width="11%" align="center"><strong>PUNTAJE MAXIMO</strong></td>
    <td width="12%" align="center"><strong>PUNTAJE ASIGNADO</strong></td>
  </tr>
  <tr>
    <td rowspan="2">I. Nivel de participación de las familias (10 puntos)</td>
    <td rowspan="2" >1.1 Comprueba en campo a las familias realmente comprometidas, comprobación de DNIs</td>
    <td>a) Menos del 50% de familias que participan en el PDN, presentes en la evaluación de campo (Descalifica)</td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td>b) De 51% a 100% de las familias que participan en el PDN (hasta 10 puntos)</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal de Nivel de participación de las familias</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3">II. Organización Responsable del PDN (10 puntos)</td>
    <td >1.1 Relación con los Directivos de la Organización territorial</td>
    <td>Observa y percibe las relaciones existentes con los directivos de la organización territorial</td>
    <td align="center">2</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2 Capacidad de gestión</td>
    <td>Logros obtenidos con el apoyo de otras instituciones, participación en otras instancias vinculadas con el desarrollo, otros</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3 Mujer y jóvenes</td>
    <td>Percepción del grado de interés y participación de mujeres y jóvenes en la propuesta del PDN o como integrantes de la Junta Directiva</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Organización responsable del PDN</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5">III. Coherencia del Plan de Negocio (30 puntos)</td>
    <td >2.1 Coherencia de actividades</td>
    <td>Refleja de manera objetiva si las actividades propuestas en el PDN guardan coherencia con las potencialidades de sus recursos</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2 Recursos destinados al PDN</td>
    <td>Percepción sobre la voluntad de destinar sus recursos en el PDN y posibilidades de incremento de sus ingresos</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3 Plazo de ejecución</td>
    <td>Se verifica si existen las condiciones para el cumplimiento de los resultados esperados en el plazo propuesto en el PDN, en el marco del horizonte del proyecto</td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.4 Actividades conexas al PDN</td>
    <td>Pertinencia de las visitas guiadas y participación en ferias con la propuesta del plan de negocio</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.5 Aportes de las familias</td>
    <td>Predisposición de las familias para cofinanciar el Plan de Negocio</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Coherencia del PDN</td>
    <td align="center">30</td>
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
    <td>&nbsp;</td>
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
WHERE clar_bd_jurado_evento_clar.calif_campo=1 AND
org_ficha_organizacion.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row3['cod_dependencia']."' AND
clar_bd_ficha_pdn_2.cod_clar = '$cod'
ORDER BY organizacion ASC";
$result=mysql_query($sql) or die (mysql_error());
while($row1=mysql_fetch_array($result))
{
	$num2++
?>
<? include("encabezado.php");?>

<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row1['oficina'];?></div>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE NEGOCIOS-PDN - SEGUNDO DESEMBOLSO</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
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
    <td rowspan="2">I. Nivel de participación de las familias (10 puntos)</td>
    <td rowspan="2" >1.1 Comprueba en campo a las familias realmente comprometidas, comprobación de DNIs</td>
    <td>a) Menos del 50% de familias que participan en el PDN, presentes en la evaluación de campo (Descalifica)</td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
  <tr>
    <td>b) De 51% a 100% de las familias que participan en el PDN (hasta 10 puntos)</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal de Nivel de participación de las familias</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5">II. Organización Responsable del PDN (10 puntos)</td>
    <td >2.1 Junta Directiva </td>
    <td>Se evidencia que los  directivos tienen la documentación en orden, vigente y/o actualizada (Partida registral, estatuto,padrón de socios, Informes, documentos de pago)</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2 Fortaleza organizacional </td>
    <td>Observa y percibe las relaciones de la Junta Directiva del PDN con los directivos del PIT. Evalúa el relacionamiento con otra(s) institucion(es) y apoyo(s) logrado(s) en favor del PDN</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3 Manejo de Fondos </td>
    <td>Los responsables del PDN demuestran conocimiento y capacidad para el manejo de fondos. Los socios conocen sobre el uso de los fondos transferidos por Sierra Sur II y de sus aportes</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.4 Registros de información y Ventas </td>
    <td>La organización registra la información relevante del PDN. Los/as socios/as conocen y llevan registro de las ventas del negocio. </td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.5 Mujer y Jovenes </td>
    <td>Percepción del grado de interés y participacion de mujeres y jóvenes en la Asistencia Técnica y en la gestión del PDN</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Organización responsable del PDN</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5">III. Avances del Plan de Negocio (20 puntos) </td>
    <td >3.1 Coherencia de actividades </td>
    <td>Refleja de manera objetiva si las asistencia técnica contratada para el PDN guarda coherencia con lo establecido en el Contrato entre Sierra Sur II y la Organización</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.2 Avances y Perspectivas en la ejecución del PDN </td>
    <td>Se percibe que los avances logrados en en el desarrollo del negocio guardan relacion con los fondos utilizados y con la propuesta del PDN. Se evidencia buenas perspectivas de lograr mejores resultados</td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.3 Asistencia Tecnica </td>
    <td>Se evidencia que el (los) técnicos contratado(s) han contribuido en mejorar los conocimientos de las familias y en los avances logrados. Eligieron bien al (los) técnico(s) </td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.4 Visita Guiada </td>
    <td>La visita guiada contribuyó a motivar y comprometer la participacion de las familias en el PDN y en los avances logrados</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >3.5 Participación en ferias </td>
    <td>Las experiencias de participación en ferias comerciales ha contribuido en el desarrollo del negocio</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Avances del Plan de Negocio </td>
    <td align="center">20</td>
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
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pdn_campo" class="secondary button oculto">Finalizar</a>
</div>


</body>
</html>
