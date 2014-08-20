<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Buscamos los usuarios
$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

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
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.nombre AS org_pit, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_miembro.n_documento
FROM clar_bd_ficha_mrn INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn.cod_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
WHERE clar_bd_jurado_evento_clar.calif_campo=1 AND
org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row1['cod_dependencia']."' AND
clar_bd_ficha_mrn.cod_clar ='$cod'
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($row=mysql_fetch_array($result))
{
	$num++
?>

<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE GESTION DE RECURSOS NATURALES - PGRN</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">ORGANIZACION TERRITORIAL</td>
    <td width="1%" align="center">:</td>
    <td width="76%"><b><? echo $row['org_pit'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row['distrito'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACION DEL PGRN</td>
    <td align="center">:</td>
    <td><b><? echo $row['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>LEMA</td>
    <td align="center">:</td>
    <td><? echo $row['lema'];?></td>
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
    <td rowspan="7">I.- Organización (CC, Anexo, Sector, Comite de Regantes, Grupo de Interés, otros) (20 puntos)</td>
    <td >1.1 La Junta Directiva</td>
    <td>Observa y percibe las relaciones existentes con los directivos de la organización territorial</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2 Capacidad de gestión</td>
    <td>Logros obtenidos con el apoyo de otras instituciones, participación en otras instancias vinculadas con el desarrollo, otros</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3" >1.3 Familias comprometidas</td>
    <td>a) mas del 75% de las familias que participan en el PGRN (4 a 5 puntos)</td>
    <td rowspan="3" align="center">5</td>
    <td rowspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>b) De 51% a 74% de las familias que participan en el PGRN (2 a 3 puntos)</td>
  </tr>
  <tr>
    <td>c) Menos del 50% de familias que participan en el PGRN (hasta 2 puntos)</td>
  </tr>
  <tr>
    <td >1.4 Mujer y Jóvenes</td>
    <td>Percepción del grado de interés y participación de mujeres y jóvenes en la propuesta del PGRN o como integrantes de la Junta Directiva</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.5 Planificación y gestión</td>
    <td>Disponibilidad de instrumentos de planificación (planes, proyectos, otros) relacionados con la gestión del PGRN</td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Organización</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="2">II.- Coherencia del Plan de Gestión (30 puntos)</td>
    <td >2.1 Coherencia de actividades</td>
    <td>Refleja de manera objetiva si las actividades propuestas en el PGRN guardan coherencia con las potencialidades de su territorio</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2 Recursos destinadas al PGRN</td>
    <td>Percepcion sobre la voluntad de destinar el máximo de recursos en el PGRN y posibilidades de incremento de sus activos naturales y financieros a partir de la ejecución del PGRN</td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Coherencia del Plan de Gestión</td>
    <td align="center">30</td>
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
    <td><p>&nbsp;</p>
    </td>
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

<?
$num1=$num;
$sql="SELECT org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.nombre AS org_pit, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_mrn_2.cod_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_ficha_mrn_2.cod_clar ='$cod'  AND
clar_bd_jurado_evento_clar.calif_campo=1 AND 
org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."' AND
clar_bd_miembro.cod_dependencia='".$row1['cod_dependencia']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa=006";
$result=mysql_query($sql) or die (mysql_error());
while($row1=mysql_fetch_array($result))
{
$num1++
?>
<div class="txt_titulo" align="center">OFICINA LOCAL DE <? echo $row1['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE GESTION DE RECURSOS NATURALES - PGRN - SEGUNDO DESEMBOLSO </div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>: <u>EVALUACION DE CAMPO</u></DIV>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">ORGANIZACION TERRITORIAL</td>
    <td width="1%" align="center">:</td>
    <td width="76%"><b><? echo $row1['org_pit'];?></b></td>
  </tr>
  <tr>
    <td>DISTRITO</td>
    <td align="center">:</td>
    <td><? echo $row1['distrito'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACION DEL PGRN</td>
    <td align="center">:</td>
    <td><b><? echo $row1['organizacion'];?></b></td>
  </tr>
  <tr>
    <td>LEMA</td>
    <td align="center">:</td>
    <td><? echo $row1['lema'];?></td>
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
    <td rowspan="7">I.- Organización Responsable del PGRN (20 puntos) </td>
    <td >1.1 La Junta Directiva </td>
    <td>Se evidencia que los directivos administran bien el Contrato suscrito con Sierra Sur II. Tienen la documentación en orden, vigente y/o actualizada (Partida registral, padrón de socios, Informes, documentos de pago)</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.2 Fortaleza organizacional </td>
    <td>Observa y percibe las relaciones de la Junta Directiva del PGRN con los directivos del PIT. Evalúa el relacionamiento con otra(s) institucion(es) y apoyo(s) logrado(s) en favor del PGRN</td>
    <td align="center">3</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >1.3 Manejo de Fondos </td>
    <td>Los responsables del PGRN demuestran conocimiento y capacidad para el manejo de fondos. Los socios conocen sobre el uso de los fondos transferidos por Sierra Sur II y de sus aportes</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3" >1.4 Familias comprometivas </td>
    <td>a) Mas del 75% de las familias registradas en el PGRN han participado en Concursos Inter Familiares - CIF (4 a 5 puntos)</td>
    <td rowspan="3" align="center">5</td>
    <td rowspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>b) Entre 51% a 74% de las familias registradas en el PGRN han participado en CIF (de 2 a 3  puntos)</td>
  </tr>
  <tr>
    <td>c) Menos de 50% de las familias registradas en el PGRN han participado en CIF (hasta 2 puntos)</td>
  </tr>
  <tr>
    <td >1.5 Mujer y Jovenes </td>
    <td>Percepción del grado de interés y participacion de mujeres y jóvenes en Concursos Inter Familiares y en la gestión del PGRN </td>
    <td align="center">4</td>
    <td align="center">&nbsp;</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="3">Subtotal Organización</td>
    <td align="center">20</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="5">II.- Avances del PGRN (30 puntos) </td>
    <td >2.1 Coherencia de Actividades </td>
    <td>Los temas eligidos para Concursos Inter Familiares fueron adecuados a las necesidades de las familias y/o potencialidades del territorio </td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.2 Avances en la ejecución del PGRN </td>
    <td>Se percibe que los avances logrados en MRN por las familias entrevistada guardan relacion con los fondos utilizados y con la propuesta del PGRN</td>
    <td align="center">8</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.3 Asistencia Tecnica </td>
    <td>Se evidencia que el (los) técnicos contratado(s) han contribuido en mejorar los conocimientos de las familias y en los avances logrados. Eligieron bien al (los) técnicos </td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.4 Visita Guiada </td>
    <td>La visita guiada contribuyó a motivar y comprometer la participacion de las familias en el PGRN y en los avances logrados</td>
    <td align="center">6</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td >2.5 Perspectivas del PGRN </td>
    <td>Se percibe que el PGRN marcha bien, pueden lograr buenos resultados a su conclusión.</td>
    <td align="center">5</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">Subtotal Coherencia del Plan de Gestión</td>
    <td align="center">30</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><strong>TOTAL PRESENTACION PUBLICA EN CLAR</strong></td>
    <td align="center">&nbsp;</td>
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
    <td><b><? echo $row1['lugar']."</b>, ".traducefecha($row1['f_evento']);?></td>
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
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn_campo" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>
