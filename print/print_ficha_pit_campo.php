<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Buscamos los usuarios
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
<!-- Aca comienza el contenido -->
<?
$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre AS evento_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS rrnn, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_evento_clar INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_jurado_evento_clar.calif_campo=1 AND
org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_evento_clar.cod_clar ='$cod'
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($fila=mysql_fetch_array($result))
{
	$num++
?>
<? include("encabezado.php");?>
<div align="center" class="txt_titulo">OFICINA LOCAL DE <? echo $fila['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE INVERSION TERRITORIAL - PIT</div>
<br>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
<BR>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="mini">NOMBRE DEL PLAN DE INVERSION TERRITORIAL</td>
    <td width="4%" class="mini">:</td>
    <td width="61%" class="mini"><? echo $fila['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="19%" rowspan="2" align="center">EJES</td>
    <td width="17%" rowspan="2" align="center">CRITERIOS</td>
    <td width="44%" rowspan="2" align="center">DESCRIPCION</td>
    <td colspan="2" align="center">PUNTAJES</td>
  </tr>
  <tr class="txt_titulo">
    <td width="9%" align="center">MAXIMO</td>
    <td width="11%" align="center">ASIGNADO</td>
  </tr>
  <tr>
    <td rowspan="4">I. Capacidad Organizacional (20 puntos)</td>
    <td>1.1.- Junta Directiva</td>
    <td class="txt_parrafo">Se evidencia que los directivos tienen la documentación en orden y vigente (Partida registral, estatuto, padrón de socios).</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.2.- Socios</td>
    <td class="txt_parrafo">Los directivos y socios incluyendo mujeres y jóvenes conocen sus funciones y muestran interes en el desarrollo del PIT.</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.3.- Capacidad de gestión</td>
    <td class="txt_parrafo">Logros obtenidos con el apoyo de otras instituciones, participación en otras instancias vinculadas con el desarrollo del territorio.</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.4.- Planificación y gestión</td>
    <td class="txt_parrafo">Disponibilidad de instrumentos de planificación (planes, proyectos, otros) relacionados con la gestión del territorio.</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Capacidad Organizacional</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="2">II.- Mapa Cultural (10 puntos)</td>
    <td>2.1.- Organización</td>
    <td class="txt_parrafo">Se evidencia como los directivos y socios se organizan para la construcción del mapa cultural con la participación de jovenes, adultos y personas de la tercera edad.</td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>2.2.- Nivel de avance y coherencia con el PIT</td>
    <td class="txt_parrafo">Se evidencia el avance en la construcción del mapa cultural y la coherencia con las iniciativas propuestas en el territorio (PGRN, PDN).</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Mapa Cultural</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="2">III.- Coherencia del Plan de Inversión Territorial - PIT (20 puntos)</td>
    <td>3.1.- Iniciativas del PIT</td>
    <td class="txt_parrafo">Los directivos y representantes sustentan la pertinencia y las potencialidades del territorio consideradas en el PIT (PGRN y PDN)</td>
    <td align="center">12</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>3.2.- Sostenibilidad del PIT</td>
    <td class="txt_parrafo">Las actividades consideradas en el PIT, reflejan a la posibilidad de continuidad.</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal coherencia del PIT</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_parrafo"><strong>SUBTOTAL EVALUACION CAMPO</strong></td>
    <td align="center"><strong>50</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<br>
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
    <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $fila['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $fila['lugar'].", ".traducefecha($fila['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>


<?
}
?>


<?
$num1=$num;

$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre AS evento_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS rrnn, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_evento_clar INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_evento_clar.cod_clar ='$cod'  AND
org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_jurado_evento_clar.calif_campo=1 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
$num1++
?>
<? include("encabezado.php");?>
<div align="center" class="txt_titulo">OFICINA LOCAL DE <? echo $fila1['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE INVERSION TERRITORIAL - PIT - SEGUNDOS DESEMBOLSOS</div>
<br>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
<BR>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="mini">NOMBRE DEL PLAN DE INVERSION TERRITORIAL</td>
    <td width="4%" class="mini">:</td>
    <td width="61%" class="mini"><? echo $fila1['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>


  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="txt_titulo centrado">
      <td width="19%" rowspan="2">EJES</td>
      <td width="17%" rowspan="2">CRITERIOS</td>
      <td width="44%" rowspan="2">DESCRIPCION</td>
      <td colspan="2">PUNTAJE</td>
    </tr>
    <tr class="txt_titulo centrado">
      <td width="9%">MAXIMO</td>
      <td width="11%">ASIGNADO</td>
    </tr>
    <tr>
      <td rowspan="5">I.- Capacidad Organizacional (20 puntos) </td>
      <td>1.1 Junta Directiva </td>
      <td>Se evidencia que los  directivos tienen la documentación en orden, vigente y/o actualizada (Partida registral, estatuto,padrón de socios, Informes, documentos de pago) </td>
      <td align="center">5</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.2 Manejo de Fondos </td>
      <td>Los responsables del PIT demuestran conocimiento y capacidad para el manejo de fondos. Los socios conocen sobre el uso de los fondos transferidos por Sierra Sur II y de sus aportes</td>
      <td align="center">4</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.3 Animador Territorial </td>
      <td>Se evidencia que el Animador Territorial es activo y dinámico; acompaña con dedicación a los socios del PIT. Ha sido bien designado</td>
      <td align="center">3</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.4 Capacidad de gestión </td>
      <td>Los directivos han hecho esfuerzos por lograr apoyo de otras instituciones, han logrado concertar el apoyo en favor de su territorio o están en vías de lograrlo. </td>
      <td align="center">5</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.5 Planificación y gestión </td>
      <td>Han generado y/o mejorado instrumentos de planificación (planes, proyectos, otros) relacionados con la gestion del territorio.</td>
      <td align="center">3</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Subtotal Capacidad Organizacional </td>
      <td align="center">20</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2">II.- Mapa Cultural (10 puntos) </td>
      <td>2.1 Organización </td>
      <td>El Mapa Cultural se utiliza como instrumento de aplicación para registrar avances. Se evidencia interés por mejorarlo y darle valor de uso </td>
      <td align="center">6</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>2.2 Nivel de avance y coherencia con el PIT </td>
      <td>Se evidencia choherencia del Mapa Culural con los avances de las iniciativas en el territorio (PGRN, PDN).</td>
      <td align="center">4</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Subtotal Mapa Cultural </td>
      <td align="center">10</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td rowspan="2">III.- Coherencia del Plan de Inversión Territorial - PIT (20 puntos) </td>
      <td>3.1 Iniciativas del PIT </td>
      <td>Los directivos y representantes sustentan de modo coherente la situación general de las iniciativas consideradas en el PIT (PGRN y PDN).</td>
      <td align="center">12</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>3.3 Sostenibilidad del PIT </td>
      <td>Las iniciativas consideradas en el PIT, reflejan coherencia en sus avances y buenas perspectivas de conclusión</td>
      <td align="center">8</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Subtotal Coherencia del PIT </td>
      <td align="center">20</td>
      <td>&nbsp;</td>
    </tr>
  </table>
<br>
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
    <td><? echo $fila1['nombre']." ".$fila1['paterno']." ".$fila1['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $fila1['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $fila1['lugar'].", ".traducefecha($fila1['f_evento']);?></td>
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
	 <a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_campo" class="secondary button oculto">Finalizar</a>
 </div>


<!-- Fin del contenido -->
</body>
</html>
