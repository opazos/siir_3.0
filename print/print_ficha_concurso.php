<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT gcac_concurso_clar.f_concurso, 
	gcac_concurso_clar.nombre, 
	gcac_concurso_clar.departamento, 
	gcac_concurso_clar.provincia, 
	gcac_concurso_clar.distrito, 
	gcac_concurso_clar.lugar, 
	gcac_concurso_clar.premio, 
	gcac_concurso_clar.incentivo, 
	gcac_concurso_clar.n_ganadores, 
	gcac_concurso_clar.cod_tipo_concurso, 
	gcac_concurso_clar.cod_dependencia, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso
FROM sys_bd_dependencia INNER JOIN gcac_concurso_clar ON sys_bd_dependencia.cod_dependencia = gcac_concurso_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
WHERE gcac_concurso_clar.cod_concurso='$cod'";
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
<? 
if($row['cod_tipo_concurso']==6)
{

  $num=0;
  $sql="SELECT gcac_pit_participante_concurso.cod_participante, 
  org_ficha_taz.n_documento, 
  org_ficha_taz.nombre, 
  gcac_pit_participante_concurso.dni_rep, 
  gcac_pit_participante_concurso.nombre_rep, 
  gcac_jurado_concurso.n_documento, 
  gcac_jurado_concurso.nombres, 
  gcac_jurado_concurso.apellidos
FROM pit_bd_ficha_pit INNER JOIN gcac_pit_participante_concurso ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
   INNER JOIN gcac_jurado_concurso ON gcac_jurado_concurso.cod_concurso = gcac_pit_participante_concurso.cod_concurso
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE gcac_pit_participante_concurso.cod_concurso='$cod'
ORDER BY org_ficha_taz.nombre ASC, gcac_jurado_concurso.nombres ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($fila=mysql_fetch_array($result))
  {
    $num++

?>
<? include("encabezado.php"); ?>
<div class="capa txt_titulo centrado"><? echo $row['nombre'];?></div>

<div class="capa txt_titulo centrado"><? echo $row['tipo_concurso'];?> - CARTILLA DE CALIFICACION</div>
<br/>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" >
  <tr>
    <td width="30%">Plan de inversion Territorial</td>
    <td width="70%"><? echo $fila['nombre'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini" >
  <tr class="txt_titulo centrado">
    <td>CRITERIO</td>
    <td>MAX. PUNTAJE</td>
    <td>PUNTAJE</td>
  </tr>

  <tr class="txt_titulo"><td colspan="3">PLAN DE INVERSION TERRITORIAL</td></tr>

  <tr>
    <td>Presentación del Stand: Diversidad de instrumentos para representar sus mapas territoriales: maquetas, paneles fotograficos u otros elementos innovativos.</td>
    <td class="centrado">1 a 10 ptos.</td>
    <td><br/></td>
  </tr>
  <tr>
    <td>El representante del PIT presenta las caracteristicas principales del territorio y señala los avances y resultados producto de la ejecución de las iniciativas (PGRN, PDN) utilizando su mapa territorial.</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>El animador territorial señala las principales actividades realizadas en el territorio y la forma como las familias se organizaron para la ejecución de sus iniciativas de PGRN, PDN.</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>

  <tr class="txt_titulo"><td colspan="3">PLAN DE GESTION DE RECURSOS NATURALES</td></tr>

  <tr>
    <td>Muestra sus avances de resultados y conocimientos adquiridos con la ejecución de PGRN N. de familias, concursos interfamiliares, actividades, valorización.</td>
    <td class="centrado">1 a 10 ptos.</td>
    <td></td>
  </tr>
   <tr>
    <td>Claridad, coherencia y participación de los integrantes para presentar y sustentar su PGRN (Mujeres, jovenes, hombres)</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Evidencias sobre innovaciones introducidas en el PGRN</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Acciones, gestiones que permitan la continuidad de las actividades del PGRN</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Muestra su archivador con su documentación organizada</td>
    <td class="centrado">1 a 6 ptos</td>
    <td></td>
  </tr>       

  <tr class="txt_titulo"><td colspan="3">PLAN DE NEGOCIO</td></tr>
  <tr>
    <td>Muestra sus avances, resultados y conocimientos adquiridos con la ejecución de familias, ventas, contactos comerciales</td>
    <td class="centrado">1 a 10 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Claridad, coherencia y participación de los integrantes para presentar y sustentar su PDN (Mujeres, jóvenes, hombres)</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Evidencias sobre innovaciones introducidas en el PDN</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Acciones, gestiones que permitan la continuidad de las actividades del PDN</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Muestra su archivador con su documentación organizada</td>
    <td class="centrado">1 a 6 ptos.</td>
    <td></td>
  </tr>

  <tr class="txt_titulo"><td colspan="3">ACTIVOS CULTURALES</td></tr>
  <tr>
    <td>Comidas Típicas: Presentación, capacidad para explicar el origen del plato típico y como se relaciona con las actividades del territorio: ingredientes y forma de preparación</td>
    <td class="centrado">1 a 6 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Danza Típica: Referencia del origen, significado de la danza, originalidad de la danza y vestimenta, despliegue y su articulacion con las actividades del territorio</td>
    <td class="centrado">1 a 7 ptos.</td>
    <td></td>
  </tr>
  <tr>
    <td>Es muy probable que en caso de ganar, parte del premio se invierta en la iniciativa.</td>
    <td class="centrado">1 a 5 ptos.</td>
    <td></td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="centrado">100 ptos.</td>
    <td></td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="30%">Observaciones</td>
    <td width="70%"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nº DNI</td>
    <td><? echo $fila['dni'];?></td>
  </tr>
  <tr>
    <td>Nombres</td>
    <td><? echo $fila['nombres']." ".$fila['apellidos'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo centrado">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
  }

}
//aca van los demas concursos
else
{
  $num=0;
  $sql="SELECT gcac_participante_concurso.descripcion, 
  org_ficha_organizacion.nombre, 
  org_ficha_organizacion.n_documento, 
  gcac_jurado_concurso.n_documento AS dni, 
  gcac_jurado_concurso.nombres, 
  gcac_jurado_concurso.apellidos
FROM org_ficha_organizacion INNER JOIN gcac_participante_concurso ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
   INNER JOIN gcac_jurado_concurso ON gcac_jurado_concurso.cod_concurso = gcac_participante_concurso.cod_concurso
WHERE gcac_participante_concurso.cod_concurso='$cod'
ORDER BY gcac_jurado_concurso.nombres ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $num++
?>
<? include("encabezado.php"); ?>
<div class="capa txt_titulo centrado"><? echo $row['nombre'];?></div>

<div class="capa txt_titulo centrado"><? echo $row['tipo_concurso'];?></div>

<div class="capa txt_titulo centrado">CARTILLA DE CALIFICACION</div>
<br/>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="30%">Organizacion</td>
    <td width="70%"><? echo $f1['nombre'];?></td>
  </tr>
  <tr>
    <td>Participa con</td>
    <td><? echo $f1['descripcion'];?></td>
  </tr>
</table>
<br/>
<?
if ($row['cod_tipo_concurso']==1)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="63%">CRITERIOS DE EVALUACION</td>
    <td width="19%">VALOR REFERENCiAL</td>
    <td width="18%">PUNTAJE ASIGNADO</td>
  </tr>
  <tr>
    <td>Originalidad y riqueza de los contenidos de los mapas territoriales.</td>
    <td class="centrado">1 a 15 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Calidad, imaginaciÃ³n, creatividad y arte para la construcciÃ³n de los mapas territoriales</td>
    <td class="centrado">1 a 14 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Diversidad de instrumentos para representar sus mapas territoriales: maquetas, paneles fotogrÃ¡ficos u otros elementos innovativos.</td>
    <td class="centrado">1 a 12 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Claridad, coherencia y participaciÃ³n de los integrantes para presentar y sustentar sus mapas territoriales</td>
    <td class="centrado">1 a 9 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_concurso']==2)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="63%">CRITERIOS DE EVALUACION</td>
    <td width="19%">VALOR REFERENCiAL</td>
    <td width="18%">PUNTAJE ASIGNADO</td>
  </tr>
  <tr>
    <td>PresentaciÃ³n del Stand</td>
    <td class="centrado">1 A 8 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Muestrasus experiencias y conocimientos adquiridos en la ejecucion de su PDN y presenta su archivador.</td>
    <td class="centrado">1 a 10 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Evidencia sobre innovaciones introducidas con el PDN a la iniciativa.</td>
    <td class="centrado">1 a 7 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Se evidencia una diversificacion en la produccion en comparaciÃ³n a la situacion anterior al PDN</td>
    <td class="centrado">1 a 6 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Es muy probable que, en caso de ganar, el premio se invierta parte de la iniciativa.</td>
    <td class="centrado">1 a 6 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>La participacion de muejres y jovenes contribuiran en la continuidad del PDN</td>
    <td class="centrado">1 a 7 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Tienen pensado o estÃ¡n en proceso de convertirse  en una micro empresa</td>
    <td class="centrado">1 a 6 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_concurso']==3)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="63%">CRITERIOS DE EVALUACION</td>
    <td width="19%">VALOR REFERENCiAL</td>
    <td width="18%">PUNTAJE ASIGNADO</td>
  </tr>
  <tr>
    <td>Originalidad de la danza y vestimenta</td>
    <td class="centrado">1 a 16 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Despliegue y presentaciÃ³n en el escenario</td>
    <td class="centrado">1 a 12 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Coreografia</td>
    <td class="centrado">1 a 13 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Referencia del origen y significado de la danza</td>
    <td class="centrado">1 a 9 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_concurso']==4)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="63%">CRITERIOS DE EVALUACION</td>
    <td width="19%">VALOR REFERENCiAL</td>
    <td width="18%">PUNTAJE ASIGNADO</td>
  </tr>
  <tr>
    <td>Originalidad del plato tipico</td>
    <td class="centrado">1 a 15 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Presentacion del plato</td>
    <td class="centrado">1 a 12 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Calidad del plato (sabor)</td>
    <td class="centrado">1 a 13 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Presentacion de (los) participante (s) y capacidad para explicar el origen, ingredientes y forma de preparaciÃ³n.</td>
    <td class="centrado">1 a 10 puntos</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_concurso']==5)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="63%">CRITERIOS DE EVALUACION</td>
    <td width="19%">VALOR REFERENCiAL</td>
    <td width="18%">PUNTAJE ASIGNADO</td>
  </tr>
  
  
  <tr>
    <td>Presentación del Stand</td>
    <td class="centrado">01 a 08 puntos</td>
    <td>&nbsp;</td>
  </tr> 
  
  <tr>
    <td>Muestra sus experiencias y conocimientos adquiridos en la ejecución de su iniciativa de negocio PDN y presenta su   archivador </td>
    <td class="centrado">01 a 08 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>Muestra los aprendizajes adquiridos y la puesta en práctica  del “Territorio de Aprendizaje: Formación de Gerentes de Pequeñas Empresas Campesinas y Rurales” en Belén de Umbría en Colombia en su iniciativa.<br/>Experiencias  de desarrollo organizacional, sistema financiero (sostenibilidad de la asociación).</td>
    <td class="centrado">01 a 08 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
   <tr>
    <td>Evidencias sobre  innovaciones introducidas con  su iniciativa  a la iniciativa</td>
    <td class="centrado">01 a 08 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>Se evidencia una diversificación en la producciónen comparación a la situación anterior al PDN</td>
    <td class="centrado">01 a 07 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>Es muy probable que, en caso de ganar, el premiose invierta parte en la iniciativa</td>
    <td class="centrado">01 a 07 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>La participación de mujeres  en la continuidad del PDN</td>
    <td class="centrado">01 a 07 puntos</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>Tienen pensado o están en proceso de convertirse en  micro empresa.
Como están Planificando  la obtención del capital económico.</td>
    <td class="centrado">01 a 07 puntos</td>
    <td>&nbsp;</td>
  </tr>             

  <tr class="txt_titulo">
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
?>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="30%">Observaciones</td>
    <td width="70%"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>NÂº DNI</td>
    <td><? echo $f1['dni'];?></td>
  </tr>
  <tr>
    <td>Nombres</td>
    <td><? echo $f1['nombres']." ".$f1['apellidos'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo centrado">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}

}
?>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../clar/concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>






</body>
</html>
