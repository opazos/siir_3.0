<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- obtengo la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_acta_clar
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_acta=$r1['n_acta_clar']+1;

//2.- Obtengo los datos del CLAR
$sql="SELECT
clar_bd_evento_clar.nombre,
clar_bd_evento_clar.f_evento,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar,
sys_bd_dependencia.nombre AS oficina,
clar_bd_evento_clar.objetivo,
clar_bd_evento_clar.resultado,
sys_bd_personal.nombre AS nombres,
sys_bd_personal.apellido,
clar_bd_evento_clar.f_campo1,
clar_bd_evento_clar.f_campo2,
clar_bd_evento_clar.premio,
clar_bd_evento_clar.ganadores
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
clar_bd_evento_clar.cod_clar = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Obtengo el acta
$sql="SELECT clar_bd_acta.contenido, 
	clar_bd_acta.n_acta, 
	clar_bd_acta.cod_acta
FROM clar_bd_acta
WHERE clar_bd_acta.cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

if ($modo==nuevo)
{
	$action=ADD;
}
else
{
	$action=UPDATE;
}

?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../javascripts/btn_envia.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
  <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Generar Acta del CLAR</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=<? echo $action;?>" onsubmit="return checkSubmit();">
	
	
<div class="two columns">Nº de Acta</div>
<div class="ten columns">
<input type="text" name="n_acta" class="two digits required" value="<? if ($modo==nuevo) echo $n_acta; else echo $r3['n_acta'];?>">

<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
<input type="hidden" name="cod_acta" value="<? echo $r3['cod_acta'];?>">

</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<!-- Inicio del contenido -->
<?
if ($modo==nuevo or $modo==reseteo)
{
?>
<div class="capa justificado">En <? echo $r2['lugar'];?> del Distrito de <? echo $r2['distrito'];?>, Provincia de <? echo $r2['provincia'];?>, Departamento de <? echo $r2['departamento'];?>, siendo las __.00 horas del día <? echo traducefecha($r2['f_evento']);?>, se inició la ____ sesión del CLAR – Comité Local de Asignación de Recursos – de la Oficina Local <? echo $r2['oficina'];?> del Proyecto de Desarrollo Sierra Sur II, el cual se desarrolló conforme a lo siguiente:</div>

<div class="capa justificado">
<p><strong>1.- PARTICIPANTES</strong></p>

<p><strong>A.- MIEMBROS DEL CLAR</strong></p>

<ul>
<?
$sql="SELECT clar_bd_miembro.n_documento AS dni, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_jurado_evento_clar.calif_clar=1 AND
clar_bd_jurado_evento_clar.cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>
<li><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno']." - ".$f1['cargo'];?></li>
<?
}
?>
</ul>
	
</div>

<div class="capa justificado">
	<p><strong>B.- OTROS PARTICIPANTES</strong> Participan también en este evento, las siguientes personas:</p>
	
	<ul>
		<li>-</li>
		<li>-</li>
		<li>-</li>
	</ul>	
</div>

<div class="capa justificado"><p><strong>2.- AGENDA DE LA REUNIÓN</strong></p>

<ul>
	<li>Bienvenida a los participantes y delegaciones</li> 
	<li>Participación del Director Ejecutivo del Proyecto Sierra Sur II</li> 
	<li>Informe Técnico</li>
	<li>Evaluación y calificación de Iniciativas Rurales</li> 
</ul>
</div>

<div class="capa justificado">
	<p><strong>3.- DESARROLLO DE LA AGENDA:</strong></p>
	
	<ol type="a">
		
		<li>La Sr(a). ________________, Alcalde de la Municipalidad Distrital de <? echo $r2['distrito'];?>, dio la bienvenida a las Autoridades, a los participantes del Concurso de Iniciativas Rurales, a los representantes del Proyecto de Desarrollo Sierra Sur II, así como también a las diferentes delegaciones de visitantes y asistentes al evento, dando por inaugurado el evento con la expectativa de que las organizaciones participantes expongan satisfactoriamente sus propuestas.</li>
		<li>El Sr(a). ________________, Director Ejecutivo del NEC PROYECTO SIERRA SUR II, manifestó su agradecimiento al Sr Alcalde y autoridades locales por su esfuerzo colaborativo en la organización de este CLAR y expresó el reconocimiento a las organizaciones participantes por su interés y compromiso asumido en el desarrollo de sus iniciativas orientadas a mejorar sus condiciones de vida, capitalizando de las oportunidades de acceder a fondos públicos que les brinda el Proyecto Sierra Sur II de AGRORURAL – MINAG, que se ejecuta con apoyo financiero del FIDA – Fondo Internacional de Desarrollo Agrícola.</li>
		
		<li>La Sr(a). ________________, en su condición de Jefe de la Oficina Local de <? echo $r2['oficina'];?> del Proyecto SIERRA SUR II, presentó un Informe Técnico respecto del proceso de preparación de las diferentes organizaciones locales que participan del Concurso, destacando los siguientes aspectos:
		<p>&nbsp;</p>		
			<ul>
				<li>Énfasis en la etapa de promoción y difusión, para dar a conocer la filosofía del Proyecto SIERRA SUR II y generar la demanda de iniciativas rurales, habiéndose realizado diferentes Jornadas Informativas, eventos de capacitación.</li> 
				<li>La evaluación de campo realizada del día <? echo traducefecha($r2['f_campo1']);?> al día <? echo traducefecha($r2['f_campo2']);?> al 100% de las organizaciones postulantes. 
				<li>Presento a cada uno de los miembros del CLAR, quienes se encargaran de evaluar los Planes de Inversión Territorial (PIT), que contienen los Planes de Gestión de Recursos Naturales y Planes de Negocio y finalmente.</li> 
				<li>Presento a cada una de las organizaciones postulantes y solicito autorización al presidente del CLAR para que procedan a exponer sus propuestas de Planes de Inversión Territorial, compuestos por iniciativas de Planes de Gestión de Recursos Naturales y/o Planes de Negocio.</li> 
			</ul>
<p>
<!-- TABLA DE PITS CONSOLIDADO -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
<td align="center"><strong>ORGANIZACIÓN TERRITORIAL</strong></td>
<td align="center"><strong>ORGANIZACIÓN RESPONSABLE DEL PLAN DE GESTION DE RECURSOS NATURALES</strong></td>
<td align="center"><strong>ORGANIZACIÓN RESPONSABLE DEL PLAN DE NEGOCIO</strong></td>
<td align="center"><strong>DENOMINACION DEL PLAN DE NEGOCIO</strong></td>
</tr>
<?
$sql="SELECT
org_ficha_taz.nombre AS pit,
org2.nombre AS mrn,
org1.nombre AS pdn,
pit_bd_ficha_pdn.denominacion
FROM
pit_bd_ficha_pit
LEFT JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
LEFT JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
LEFT JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
LEFT JOIN org_ficha_organizacion AS org1 ON org1.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org1.n_documento = pit_bd_ficha_pdn.n_documento_org
LEFT JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
LEFT JOIN org_ficha_organizacion AS org2 ON org2.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org2.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE
clar_bd_ficha_pit.cod_clar = '$id'
ORDER BY
org_ficha_taz.nombre ASC,
org2.nombre ASC,
org1.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>
<tr>
<td><? echo $f1['pit'];?></td>
<td><? echo $f1['mrn'];?></td>
<td><? echo $f1['pdn'];?></td>
<td><? echo $f1['denominacion'];?></td>
</tr>
<?
}
?>
<!-- ahora jalo los planes de negocio sueltos -->
<?
$sql="SELECT pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.tipo<>0 AND 
clar_bd_ficha_pdn_suelto.cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>
<tr>
	<td align="center">-</td>
	<td align="center">-</td>
	<td><? echo $f2['nombre'];?></td>
	<td><? echo $f2['denominacion'];?></td>
</tr>
<?
}
?>
<!-- fin -->
</table>
<!-- FIN DE PITS CONSOLIDADOS -->
</p>			

<p class="capa">Adicionalmente las siguientes iniciativas se presentan para el segundo desembolso:</p>		
			
<p><!-- PLANES DE INVERSION TERRITORIAL -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
<td colspan="2" align="center"><strong>PLANES DE INVERSION TERRITORIAL</strong></td>
</tr>
<tr>
<td align="center"><strong>N° DOCUMENTO</strong></td>
<td><strong>NOMBRE DE LA ORGANIZACION</strong></td>
</tr>
<?php 
$sql="SELECT
org_ficha_taz.n_documento,
org_ficha_taz.nombre
FROM
clar_bd_ficha_pit_2
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE
clar_bd_ficha_pit_2.cod_clar = '$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
<tr>
<td align="center"><?php  echo $f3['n_documento'];?></td>
<td><?php  echo $f3['nombre'];?></td>
</tr>
<?php 
}
?>
</table>
</p>

<p><!-- PLANES DE GESTION DE RECURSOS NATURALES -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
<td colspan="2" align="center"><STRONG>PLANES DE GESTIÓN DE RECURSOS NATURALES</STRONG></td>
</tr>
<tr>
<td align="center"><strong>N° DOCUMENTO</strong></td>
<td><strong>NOMBRE DE LA ORGANIZACION</strong></td>
</tr>
<?php 
$sql="SELECT
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre
FROM
clar_bd_ficha_mrn_2
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_bd_ficha_mrn_2.cod_clar ='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<tr>
<td align="center"><?php  echo $f4['n_documento'];?></td>
<td><?php  echo $f4['nombre'];?></td>
</tr>
<?php 
}
?>
</table>	
</p>

<p><!--  PLANES DE NEGOCIO -->
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
<td colspan="3" align="center"><strong>PLANES DE NEGOCIO</strong></td>
</tr>
<tr class="txt_titulo">
<td align="center"><strong>N° DOCUMENTO</strong></td>
<td><strong>NOMBRE DE LA ORGANIZACION</strong></td>
<td><strong>DENOMINACION DEL PLAN DE NEGOCIO</strong></td>
</tr>
<?php 
$sql="SELECT
clar_bd_ficha_pdn_2.cod_ficha_pdn_clar,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion
FROM
clar_bd_ficha_pdn_2
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE
clar_bd_ficha_pdn_2.cod_clar = '$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
<tr>
<td align="center"><?php  echo $f5['n_documento'];?></td>
<td><?php  echo $f5['nombre'];?></td>
<td><?php  echo $f5['denominacion'];?></td>
</tr>
<?php 
}
?>
</table>	
</p>
<p>&nbsp;</p>
</li>	
		
<li>La Secretaria Técnica del CLAR, hizo entrega de los resultados de la Evaluación de campo a cada miembro del CLAR, aclarando que el puntaje máximo en la 1ra fase de campo es de 50 puntos y que en la 2da fase de presentación pública, realizada en la fecha, el puntaje máximo es de 50 puntos, acumulable.</li>
</ol>	
</div>	

<div class="capa justificado">Luego de culminada las presentaciones realizadas por cada una de las organizaciones participantes ante el CLAR, los resultados obtenidos se presentan conforme a los que sigue: 
	
<ol type="a">
	<li>Los resultados y premiaciones de los Planes de Inversión Territorial por la presentación de sus Mapas Culturales, según orden de merito, es conforme a lo que se indica en el cuadro siguiente:
<p>&nbsp;</p>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td colspan="5" align="center"><strong>PLANES DE INVERSION TERRITORIAL</strong></td>
  </tr>
  <tr>
    <td align="center"><strong>PUESTO OCUPADO</strong></td>
    <td align="center"><strong>N° DOCUMENTO</strong></td>
    <td align="center"><strong>NOMBRE DE LA ORGANIZACION TERRITORIAL QUE GANA</strong></td>
    <td align="center"><strong>N° DE CUENTA</strong></td>
    <td align="center"><strong>PREMIO (S/.)</strong></td>
  </tr>
<?
$sql="SELECT
pit_bd_ficha_pit.puesto,
pit_bd_ficha_pit.monto_concurso,
pit_bd_ficha_pit.n_cuenta,
org_ficha_taz.n_documento,
org_ficha_taz.nombre,
sys_bd_ifi.descripcion as banco,
clar_bd_ficha_pit.cod_clar
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON pit_bd_ficha_pit.cod_tipo_doc_taz=org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz=org_ficha_taz.n_documento
INNER JOIN sys_bd_ifi ON pit_bd_ficha_pit.cod_ifi = sys_bd_ifi.cod_ifi
INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_pit.puesto <> 0 AND clar_bd_ficha_pit.cod_clar='$id'
ORDER BY pit_bd_ficha_pit.puesto ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($f6=mysql_fetch_array($result))
{

	$premio_mapa=$f6['monto_concurso'];
	$total_premio_mapa=$total_premio_mapa+$premio_mapa;
	$num++	
?>
  <tr>
    <td class="centrado"><? echo $f6['puesto'];?></td>
    <td class="centrado"><? echo $f6['n_documento'];?></td>
    <td><? echo $f6['nombre'];?></td>
    <td class="centrado"><? echo $f6['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($f6['monto_concurso'],2);?></td>
  </tr>
<?
}
?>
  <tr>
    <td colspan="4" class="centrado"><strong>TOTAL (S/.)</strong></td>
    <td class="derecha"><strong><? echo number_format($total_premio_mapa,2);?></strong></td>
  </tr>
</table>
<p>&nbsp;</p>
</li>
	
<li>Seguidamente se da cuenta de los resultados de la calificación de cada una de las iniciativas de PGRN y PDN, según el cuadro siguiente:		
<p>&nbsp;</p>
<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE INVERSION TERRITORIAL - PRIMER DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO SEGUN CONTRATO (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$num=1;
	$sql="SELECT org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.calificacion, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	(pit_bd_ficha_pit.aporte_pdss+
	pit_bd_ficha_pit.aporte_org) AS aporte_total, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE clar_bd_ficha_pit.cod_clar='$id'
ORDER BY pit_bd_ficha_pit.calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>		
			<tr>
				<td align="center"><? echo $num;?></td>
				<td align="center"><? echo $f7['n_documento'];?></td>
				<td><? echo $f7['nombre'];?></td>
				<td align="right"><? echo number_format($f7['calificacion'],2);?></td>
				<td align="center"><? echo $f7['oficina'];?></td>
				<td align="right"><? echo number_format($f7['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f7['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f7['aporte_total'],2);?></td>
				<td align="center"><? if ($f7['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>
<p>&nbsp;</p>
	<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE INVERSION TERRITORIAL - SEGUNDO DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$numa=0;
$sql="SELECT org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.calificacion_2 AS calificacion, 
	((pit_bd_ficha_pit.aporte_pdss*0.30)+ 
	(pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_total, 
	(pit_bd_ficha_pit.aporte_pdss*0.30) AS aporte_pdss, 
	(pit_bd_ficha_pit.aporte_org*0.50) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit_2 ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE clar_bd_ficha_pit_2.cod_clar='$id'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
	$numa++
?>		
			<tr>
				<td align="center"><? echo $numa;?></td>
				<td align="center"><? echo $f8['n_documento'];?></td>
				<td><? echo $f8['nombre'];?></td>
				<td align="right"><? echo number_format($f8['calificacion'],2);?></td>
				<td align="center"><? echo $f8['oficina'];?></td>
				<td align="right"><? echo number_format($f8['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f8['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f8['aporte_total'],2);?></td>
				<td align="center"><? if ($f8['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>
<p>&nbsp;</p>
	<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE GESTION DE RECURSOS NATURALES - PRIMER DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>				
				<td colspan="3" align="center"><strong>MONTO SEGUN CONTRATO (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$nume=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.calificacion, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss+ 
	pit_bd_ficha_mrn.at_org+
	pit_bd_ficha_mrn.vg_org) AS aporte_total, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_mrn.cod_clar='$id'
ORDER BY pit_bd_ficha_mrn.calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f9=mysql_fetch_array($result))
{
	$nume++
?>		
			<tr>
				<td align="center"><? echo $nume;?></td>
				<td align="center"><? echo $f9['n_documento'];?></td>
				<td><? echo $f9['nombre'];?></td>
				<td align="right"><? echo number_format($f9['calificacion'],2);?></td>
				<td align="center"><? echo $f9['oficina'];?></td>
				<td align="right"><? echo number_format($f9['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f9['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f9['aporte_total'],2);?></td>
				<td align="center"><? if ($f9['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>

<p>&nbsp;</p>

<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE GESTION DE RECURSOS NATURALES - SEGUNDO DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$numi=0;
	$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.calificacion_2 AS calificacion, 
	(((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)*0.30)+ 
	((pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org)*0.50)) AS aporte_total, 
	((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_pdss, 
	((pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_mrn_2.cod_clar='$id'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f10=mysql_fetch_array($result))
{
$numi++
?>		
			<tr>
				<td align="center"><? echo $numi;?></td>
				<td align="center"><? echo $f10['n_documento'];?></td>
				<td><? echo $f10['nombre'];?></td>
				<td align="right"><? echo number_format($f10['calificacion'],2);?></td>
				<td align="center"><? echo $f10['oficina'];?></td>
				<td align="right"><? echo number_format($f10['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f10['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f10['aporte_total'],2);?></td>
				<td align="center"><? if ($f10['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>
<p>&nbsp;</p>
	<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE NEGOCIO - PRIMER DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO SEGUN CONTRATO (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$numo=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.calificacion, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.denominacion
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_pdn.cod_clar='$id'
ORDER BY pit_bd_ficha_pdn.calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f11=mysql_fetch_array($result))
{
$numo++
?>		
			<tr>
				<td align="center"><? echo $numo;?></td>
				<td align="center"><? echo $f11['n_documento'];?></td>
				<td><? echo $f11['nombre']." / ".$f11['denominacion'];?></td>
				<td align="right"><? echo number_format($f11['calificacion'],2);?></td>
				<td align="center"><? echo $f11['oficina'];?></td>
				<td align="right"><? echo number_format($f11['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f11['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f11['aporte_total'],2);?></td>
				<td align="center"><? if ($f11['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>

<p>&nbsp;</p>
	<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE NEGOCIO SUELTO - PRIMER DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO SEGUN CONTRATO (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$num1=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.calificacion, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.denominacion
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$id'
ORDER BY pit_bd_ficha_pdn.calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f12=mysql_fetch_array($result))
{
$num1++
?>		
			<tr>
				<td align="center"><? echo $num1;?></td>
				<td align="center"><? echo $f12['n_documento'];?></td>
				<td><? echo $f12['nombre']." / ".$f12['denominacion'];?></td>
				<td align="right"><? echo number_format($f12['calificacion'],2);?></td>
				<td align="center"><? echo $f12['oficina'];?></td>
				<td align="right"><? echo number_format($f12['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f12['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f12['aporte_total'],2);?></td>
				<td align="center"><? if ($f12['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>

<p>&nbsp;</p>
<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>PLANES DE NEGOCIO - SEGUNDO DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$num2=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	(((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)*0.30)+ 
	((pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org)*0.50)) AS aporte_total, 
	((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_pdss, 
	((pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+
	pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.calificacion_2 AS calificacion
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn_2.cod_clar='$id'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f13=mysql_fetch_array($result))
{
	$num2++
?>		
			<tr>
				<td align="center"><? echo $num2;?></td>
				<td align="center"><? echo $f13['n_documento'];?></td>
				<td><? echo $f13['nombre']." / ".$f13['denominacion'];?></td>
				<td align="right"><? echo number_format($f13['calificacion'],2);?></td>
				<td align="center"><? echo $f13['oficina'];?></td>
				<td align="right"><? echo number_format($f13['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f13['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f13['aporte_total'],2);?></td>
				<td align="center"><? if ($f13['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>			
		</tbody>		
	</table>
<p>&nbsp;</p>
	<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>INVERSIONES PARA EL DESARROLLO LOCAL - PRIMER DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO SEGUN CONTRATO (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$num3=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.calificacion, 
	sys_bd_dependencia.nombre AS oficina, 
	(pit_bd_ficha_idl.aporte_pdss+ 
	pit_bd_ficha_idl.aporte_org) AS aporte_total, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	pit_bd_ficha_idl.denominacion
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_idl.cod_clar='$id'
ORDER BY pit_bd_ficha_idl.calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f14=mysql_fetch_array($result))
{
	$num3++
?>		
			<tr>
				<td align="center"><? echo $num3;?></td>
				<td align="center"><? echo $f14['n_documento'];?></td>
				<td><? echo $f14['nombre']." / ".$f14['denominacion'];?></td>
				<td align="right"><? echo number_format($f14['calificacion'],2);?></td>
				<td align="center"><? echo $f14['oficina'];?></td>
				<td align="right"><? echo number_format($f14['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f14['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f14['aporte_total'],2);?></td>
				<td align="center"><? if ($f14['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>	
		</tbody>		
	</table>

<p>&nbsp;</p>

<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
		
		<thead>
			<tr>
				<td colspan="9" align="center"><strong>INVERSIONES PARA EL DESARROLLO LOCAL - SEGUNDO DESEMBOLSO</strong></td>
			</tr>
			
			<tr>
				<td rowspan="2" align="center"><strong>N°</strong></td>
				<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
				<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
				<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
				<td rowspan="2" align="center"><strong>OFICINA</strong></td>
				<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
				<td rowspan="2" align="center"><strong>ESTADO</strong></td>
			</tr>
			
			<tr>
				<td align="center"><strong>PDSS</strong></td>
				<td align="center"><strong>ORG</strong></td>
				<td align="center"><strong>TOTAL</strong></td>
			</tr>
		</thead>
		
		
		<tbody>
<?
$num4=0;
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.calificacion_2 AS calificacion, 
	(((pit_bd_ficha_idl.aporte_pdss* 
	pit_bd_ficha_idl.segundo_pago)/100)+ 
	(pit_bd_ficha_idl.aporte_org*0.50)) AS aporte_total, 
	((pit_bd_ficha_idl.aporte_pdss* 
	pit_bd_ficha_idl.segundo_pago)/100) AS aporte_pdss, 
	(pit_bd_ficha_idl.aporte_org*0.50) AS aporte_org
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
WHERE clar_bd_ficha_idl_2.cod_clar='$id'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f15=mysql_fetch_array($result))
{
$num4++
?>	
			<tr>
				<td align="center"><? echo $num4;?></td>
				<td align="center"><? echo $f15['n_documento'];?></td>
				<td><? echo $f15['nombre']." / ".$f15['denominacion'];?></td>
				<td align="right"><? echo number_format($f15['calificacion'],2);?></td>
				<td align="center"><? echo $f15['oficina'];?></td>
				<td align="right"><? echo number_format($f15['aporte_pdss'],2);?></td>
				<td align="right"><? echo number_format($f15['aporte_org'],2);?></td>
				<td align="right"><? echo number_format($f15['aporte_total'],2);?></td>
				<td align="center"><? if ($f15['calificacion']>69) echo "APROBADO"; else echo "DESAPROBADO";?></td>
			</tr>
<?
}
?>		
		</tbody>		
	</table>

<p>&nbsp;</p>
	
</li>
</ol>
</div>



<div class="capa justificado">
	
<p>Se deja constancia que los documentos de calificación de los jurados de cada una de las propuestas, tanto en la Fase de Evaluación de Campo como en la Fase de Evaluación Pública, obran en poder de la Oficina Local de <? echo $r2['oficina'];?>, para los fines a que hubiera lugar.</p>

<p>Se aclara que, en el caso de los emprendimientos que no alcanzaron calificación aprobatoria, éstos podrán presentarse hasta en dos oportunidades más, sea en un CLAR de la Oficina Local <? echo $r2['oficina'];?> o del cualquiera otra Oficina Local del Proyecto Sierra Sur II, para cuyo efecto coordinará las acciones necesarias con el Jefe de La Oficina Local. </p>	

<p>No habiendo otros asuntos más que tratar, siendo las <? echo $hora_actual;?> horas, se dio por concluida la reunión, firmando los participantes, en señal de conformidad.</p>
	
</div>
<?
}
else
{
echo $r3['contenido'];
}
?>





<!-- Fin del contenido -->		
</textarea>
</div>
	
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	
	<button type="submit" class="success button" id="btn_envia">Guardar e Imprimir</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=new_acta" class="secondary button">Cancelar</a>
	
</div>

	
</form>
</div>
</li>
</ul>
</div>
</div>





    </div>

  </div>

  <!-- Footer -->
<? include("../footer.php");?>


  <!-- Included JS Files (Uncompressed) -->
  <!--
  
  <script src="javascripts/jquery.js"></script>
  
  <script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
  
  <script src="javascripts/jquery.foundation.forms.js"></script>
  
  <script src="javascripts/jquery.event.move.js"></script>
  
  <script src="javascripts/jquery.event.swipe.js"></script>
  
  <script src="javascripts/jquery.foundation.reveal.js"></script>
  
  <script src="javascripts/jquery.foundation.orbit.js"></script>
  
  <script src="javascripts/jquery.foundation.navigation.js"></script>
  
  <script src="javascripts/jquery.foundation.buttons.js"></script>
  
  <script src="javascripts/jquery.foundation.tabs.js"></script>
  
  <script src="javascripts/jquery.foundation.tooltips.js"></script>
  
  <script src="javascripts/jquery.foundation.accordion.js"></script>
  
  <script src="javascripts/jquery.placeholder.js"></script>
  
  <script src="javascripts/jquery.foundation.alerts.js"></script>
  
  <script src="javascripts/jquery.foundation.topbar.js"></script>
  
  <script src="javascripts/jquery.foundation.joyride.js"></script>
  
  <script src="javascripts/jquery.foundation.clearing.js"></script>
  
  <script src="javascripts/jquery.foundation.magellan.js"></script>
  
  -->
  
  <!-- Included JS Files (Compressed) -->
  <script src="../javascripts/jquery.js"></script>
  <script src="../javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="../javascripts/app.js"></script>
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
