<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Busco la información de la Ficha PIT
$sql="SELECT
org_ficha_taz.n_documento,
org_ficha_taz.nombre AS organizacion,
pit_bd_ficha_pit.f_presentacion,
org_ficha_taz.f_creacion,
org_ficha_taz.f_constitucion,
sys_bd_tipo_doc.descripcion AS tipo_doc,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_taz.sector,
sys_bd_dependencia.nombre AS oficina,
pit_bd_ficha_pit.n_animador,
pit_bd_ficha_pit.incentivo_animador,
pit_bd_ficha_pit.n_mes,
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE
pit_bd_ficha_pit.cod_pit ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$id=$cod;

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-30);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_30 = "'".$fecha."'";

//Familias
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id' AND
org_ficha_usuario.titular = 1";
$result=mysql_query($sql) or die (mysql_error());
$total_familias=mysql_num_rows($result);

$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$total_participantes=mysql_num_rows($result);

//Mujeres
//Mayores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id' AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento < $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$muj_mayor=mysql_num_rows($result);


//Menores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id' AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento > $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$muj_menor=mysql_num_rows($result);

//Varones
//Mayores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id' AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento < $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$var_mayor=mysql_num_rows($result);


//Menores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '$id' AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento > $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$var_menor=mysql_num_rows($result);
?>
<!DOCTYPE html><html>
  <head>
    <meta content="text/html;charset=UTF-8" http-equiv="Content-Type">
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
  </head>
  <body>
    <? include("encabezado.php");?>

    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
      <tr class="txt_titulo">
        <td width="34%">NOMBRE DE LA ORGANIZACIÓN TERRITORIAL </td>
        <td width="1%">:</td>
        <td width="65%" class="justificado"><? echo $row['organizacion'];?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
<p>&nbsp;</p>
    <div class="capa gran_titulo" align="center">PLAN DE INVERSIÓN TERRITORIAL</div>
	<p>&nbsp;</p>
	<div class="capa txt_titulo" align="center"><u>LISTA DE PROPUESTAS</u></div>
	<br>
    <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="centrado txt_titulo">
        <td width="4%">N°</td>
        <td width="40%">Nombre de la Organización / Sector </td>
        <td width="16%">Tipo de Iniciativa </td>
        <td width="40%">Lema del PGRN / Denominación del PDN</td>
      </tr>
<?
$n1=0;

$sql="SELECT
org_ficha_organizacion.nombre,
pit_bd_ficha_mrn.sector,
pit_bd_ficha_mrn.lema,
sys_bd_tipo_iniciativa.codigo_iniciativa
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
WHERE
pit_bd_ficha_mrn.cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
$n1++
?>	  
      <tr>
        <td class="centrado"><? echo $n1;?></td>
        <td><? echo $fila1['nombre'];?></td>
        <td class="centrado"><? echo $fila1['codigo_iniciativa'];?></td>
        <td><? echo $fila1['lema'];?></td>
      </tr>
<?
}
?>	 

<?
$n2=$n1;

$sql="SELECT
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
sys_bd_tipo_iniciativa.codigo_iniciativa
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
WHERE
pit_bd_ficha_pdn.cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
$n2++
?> 
      <tr>
        <td class="centrado"><? echo $n2;?></td>
        <td><? echo $fila2['nombre'];?></td>
        <td class="centrado"><? echo $fila2['codigo_iniciativa'];?></td>
        <td><? echo $fila2['denominacion'];?></td>
      </tr>
<?
}
?>	  
    </table>
    <p>&nbsp;</p>
    <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td width="27%" class="txt_titulo">DEPARTAMENTO</td>
        <td width="2%" class="txt_titulo">:</td>
        <td width="71%"><? echo $row['departamento'];?></td>
      </tr>
      <tr>
        <td class="txt_titulo">PROVINCIA</td>
        <td width="2%" class="txt_titulo">:</td>
        <td width="71%"><? echo $row['provincia'];?></td>
      </tr>
      <tr>
        <td class="txt_titulo">DISTRITO</td>
        <td class="txt_titulo" width="2%">:</td>
        <td width="71%"><? echo $row['distrito'];?></td>
      </tr>
    </table>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<div class="capa">LUGAR Y FECHA : <? echo $row['oficina'];?>, <? echo traducefecha($row['f_presentacion']);?></div>
<H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td colspan="3">I.- DATOS DE LA ORGANIZACION TERRITORIAL </td>
    </tr>
    <tr>
      <td width="25%">Nombre de la Organización </td>
      <td width="2%">:</td>
      <td width="73%" class="justificado"><? echo $row['organizacion'];?></td>
    </tr>
    <tr>
      <td>Tipo de Documento </td>
      <td>:</td>
      <td><? echo $row['tipo_doc'];?></td>
    </tr>
    <tr>
      <td>N° de Documento </td>
      <td>:</td>
      <td><? echo $row['n_documento'];?></td>
    </tr>
    <tr>
      <td>Fecha de Inscripción a Registros Públicos</td>
      <td>:</td>
      <td><? echo traducefecha($row['f_constitucion']);?></td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Relación de Directivos </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="centrado txt_titulo">
      <td width="11%">Cargo</td>
      <td width="28%">Nombres y Apellidos </td>
      <td width="9%">DNI</td>
      <td width="8%">Sexo</td>
      <td width="14%">Fecha de Nacimiento </td>
      <td width="15%">Vigencia hasta</td>
    </tr>
<?
$sql="SELECT
org_ficha_directiva_taz.n_documento,
org_ficha_directiva_taz.nombre,
org_ficha_directiva_taz.paterno,
org_ficha_directiva_taz.materno,
org_ficha_directiva_taz.f_nacimiento,
org_ficha_directiva_taz.sexo,
org_ficha_directiva_taz.f_inicio,
org_ficha_directiva_taz.f_termino,
sys_bd_cargo_directivo.descripcion AS cargo
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directiva_taz.cod_cargo_directivo
WHERE
pit_bd_ficha_pit.cod_pit = '$id'
ORDER BY
org_ficha_directiva_taz.cod_cargo_directivo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
?>	
    <tr>
      <td class="centrado"><? echo $fila3['cargo'];?></td>
      <td><? echo $fila3['nombre']." ".$fila3['paterno']." ".$fila3['materno'];?></td>
      <td class="centrado"><? echo $fila3['n_documento'];?></td>
      <td class="centrado"><? if ($fila3['sexo']==1) echo "Masculino"; else echo "Femenino";?></td>
      <td class="centrado"><? echo fecha_normal($fila3['f_nacimiento']);?></td>
      <td class="centrado"><? echo fecha_normal($fila3['f_termino']);?></td>
    </tr>
<?
}
?>	
  </table>
  <br>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td width="25%">N° de Familias del  Plan de Inversión Territorial - PIT </td>
      <td width="2%" valign="top">:</td>
      <td width="73%" valign="top"><? echo number_format($total_familias);?> Familias</td>
    </tr>
    <tr>
      <td>N° de Participantes del Plan de Inversión Territorial - PIT </td>
      <td width="2%" valign="top">:</td>
      <td width="73%" valign="top"><? echo number_format($total_participantes);?> Participantes</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Composición de los Participantes del Plan de Inversión Territorial - PIT </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini centrado">
    <tr class="txt_titulo">
      <td width="34%">SEXO</td>
      <td width="22%">Mayores de 30 años </td>
      <td width="22%">Menores de 30 años </td>
      <td width="22%">Total</td>
    </tr>
    <tr>
      <td>Mujeres</td>
      <td><?php  echo $muj_mayor;?></td>
      <td><?php  echo $muj_menor;?></td>
      <td><?php  echo $muj_mayor+$muj_menor;?></td>
    </tr>
    <tr>
      <td>Varones</td>
      <td><?php  echo $var_mayor;?></td>
      <td><?php  echo $var_menor;?></td>
      <td><?php  echo $var_mayor+$var_menor;?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><?php  echo $muj_mayor+$var_mayor;?></td>
      <td><?php  echo $muj_menor+$var_menor;?></td>
      <td><?php  echo $muj_mayor+$var_mayor+$muj_menor+$var_menor;?></td>
    </tr>
  </table>
  <br>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">

    <tr class="txt_titulo">
      <td width="100%">Apoyo recibido durante el último año </td>
    </tr>
    <tr class="justificado">
      <td><? echo $row['apoyo'];?></td>
    </tr>
  </table>
  
  
  
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>  

<div class="txt_titulo capa">II.- EL TERRITORIO DE LA ORGANIZACIÓN</div>
<br>
<div class="capa txt_titulo">2.1 Principales Cultivos</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="centrado txt_titulo">
<td width="20%">N°</td>
<td width="40%">TIPO DE CULTIVO</td>
<td width="40%">DESCRIPCION</td>
</tr>
<?php
$num=0; 
$sql="SELECT * FROM pit_tipo_cultivo WHERE cod_pit='$id' ORDER BY tipo_cultivo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
	$num++
?>
<tr>
<td class="centrado"><?php  echo $num;?></td>
<td class="centrado"><?php  if ($r1['tipo_cultivo']==1) echo "CULTIVOS AGRICOLAS"; elseif($r1['tipo_cultivo']==2) echo "PASTOS Y FORRAJES"; elseif($r1['tipo_cultivo']==3) echo "FRUTALES"; else echo "PLANTACIONES FORESTALES";?></td>
<td><?php  echo $r1['descripcion'];?></td>
</tr>
<?php 
}
?>
</table>
<br>
<div class="capa txt_titulo">2.2 Principales crianzas de ganado</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="centrado txt_titulo">
<td width="20%">N°</td>
<td width="40%">TIPO DE GANADO</td>
<td width="40%">DESCRIPCION</td>
</tr>
<?php 
$num2=0;
$sql="SELECT * FROM pit_ganado_pit WHERE cod_pit='$id' ORDER BY tipo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
	$num2++
?>
<tr>
<td class="centrado"><?php  echo $num2;?></td>
<td class="centrado"><?php  if ($r2['tipo']==1) echo "ANIMALES MAYORES"; else echo "ANIMALES MENORES";?></td>
<td><?php  echo $r2['descripcion'];?></td>
</tr>
<?php 
}
?>
</table>
<br>
<div class="capa txt_titulo">2.3 Areas del Territorio (en Hectáreas)</div>
<br>
<?php 
$sql="SELECT * FROM pit_area_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
?>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr>
<td width="50%">AREA TOTAL</td>
<td colspan="3" width="50%" class="derecha"><?php  echo number_format($r3['a1'],2);?></td>
</tr>
<tr class="centrado txt_titulo">
<td>AREAS DE CULTIVOS</td>
<td>CULTIVOS AGRICOLAS</td>
<td>PASTOS</td>
<td>FORESTALES</td>
</tr>
<tr>
<td>- CULTIVOS EN RIEGO</td>
<td class="derecha"><?php  echo number_format($r3['a2'],2);?></td>
<td class="derecha"><?php  echo number_format($r3['a3'],2);?></td>
<td class="derecha"><?php  echo number_format($r3['a4'],2);?></td>
</tr>
<tr>
<td>- CULTIVOS EN SECANO</td>
<td class="derecha"><?php  echo number_format($r3['a5'],2);?></td>
<td class="derecha"><?php  echo number_format($r3['a6'],2);?></td>
<td class="derecha"><?php  echo number_format($r3['a7'],2);?></td>
</tr>
<tr>
<td>AREAS DE PASTOS NATURALES</td>
<td colspan="3" class="derecha"><?php  echo number_format($r3['a8'],2);?></td>
</tr>
<tr>
<td>OTRAS AREAS</td>
<td colspan="3" class="derecha"><?php  echo number_format($r3['a9'],2);?></td>
</tr>
</table>
<br>
<div class="capa txt_titulo">2.4 Principales actividades de transformación y servicios</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="txt_titulo centrado">
<td width="20%">N°</td>
<td width="40%">ACTIVIDAD</td>
<td width="40%">DESCRIPCION</td>
</tr>
<?php 
$num3=0;
$sql="SELECT * FROM pit_actividad_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
	$num3++
?>
<tr>
<td class="centrado"><?php  echo $num3;?></td>
<td><?php  echo $r4['tipo'];?></td>
<td><?php  echo $r4['descripcion'];?></td>
</tr>
<?php 
}
?>
</table>
<br>
<div class="capa txt_titulo">2.5 Principales Festividades del Territorio</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="centrado txt_titulo">
<td>N°</td>
<td>DIA</td>
<td>MES</td>
<td>NOMBRE DE LA FESTIVIDAD</td>
</tr>
<?php 
$num4=0;
$sql="SELECT * FROM pit_festividad_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r5=mysql_fetch_array($result))
{
	$num4++
?>
<tr>
<td class="centrado"><?php  echo $num4;?></td>
<td class="centrado"><?php  echo $r5['dia'];?></td>
<td class="centrado"><?php  echo $r5['mes'];?></td>
<td><?php  echo $r5['descripcion'];?></td>
</tr>
<?php 
}
?>
</table>
<br></br>
<div class="capa txt_titulo">2.6 Patrimonio y manifestaciones culturales</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="centrado txt_titulo">
<td width="20%">N°</td>
<td width="40%">TIPO</td>
<td width="40%">DESCRIPCION</td>
</tr>
<?php 
$num5=0;
$sql="SELECT * FROM pit_patrimonio_pit WHERE cod_pit='$id' ORDER BY cod_tipo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r6=mysql_fetch_array($result))
{
	$num5++
?>
<tr>
<td class="centrado"><?php  echo $num5;?></td>
<td class="centrado"><?php  if ($r6['cod_tipo']==1) echo "PATRIMONIO"; else echo "MANIFESTACIONES CULTURALES";?></td>
<td><?php  echo $r6['descripcion'];?></td>
</tr>
<?php 
}
?>
</table>
<br>
<div class="txt_titulo capa">2.7 Recursos Hidricos</div>
<br>
<table class="capa mini" cellspacing="1" cellpadding="1" border="1">
<tr class="centrado txt_titulo">
<td>N°</td>
<td>PRINCIPALES FUENTES DE AGUA</td>
<td>USO ACTUAL</td>
<td>LIMITACIONES DE USO DEL AGUA</td>
</tr>
<?php 
$num6=0;
$sql="SELECT * FROM pit_agua_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r7=mysql_fetch_array($result))
{
	$num6++
?>
<tr>
<td class="centrado"><?php  echo $num6;?></td>
<td><?php  echo $r7['descripcion'];?></td>
<td><?php  echo $r7['uso'];?></td>
<td><?php  echo $r7['limitaciones'];?></td>
</tr>
<?php 
}
?>
</table>


<H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>   
  

    <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
      <tr class="txt_titulo">
        <td colspan="3">IV.- DATOS DE LOS ANIMADORES TERRITORIALES </td>
      </tr>
      <tr class="txt_titulo">
        <td colspan="3">Asignación de Animadores Territoriales </td>
      </tr>
      <tr>
        <td width="33%">N° de Familias que conforman el PIT </td>
        <td width="2%">:</td>
        <td width="65%"><? echo number_format($total_familias);?></td>
      </tr>
      <tr>
        <td>N° de Animadores Territoriales Asignados </td>
        <td>:</td>
        <td><? echo number_format($row['n_animador']);?></td>
      </tr>
      <tr class="txt_titulo">
        <td colspan="3">Animadores Territoriales Propuestos </td>
      </tr>
    </table>
    <br>
    <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
      <tr class="centrado txt_titulo">
        <td width="44%">NOMBRES Y APELLIDOS </td>
        <td width="12%">DNI</td>
        <td width="12%">SEXO</td>
        <td width="9%">FECHA DE NACIMIENTO </td>
        <td width="23%">NIVEL ACADEMICO </td>
      </tr>
<?
$sql="SELECT
pit_bd_animador.n_documento,
pit_bd_animador.nombres,
pit_bd_animador.paterno,
pit_bd_animador.materno,
pit_bd_animador.f_nacimiento,
pit_bd_animador.sexo,
sys_bd_instruccion_academica.descripcion
FROM
pit_bd_animador
INNER JOIN sys_bd_instruccion_academica ON sys_bd_instruccion_academica.cod_tipo_instruccion = pit_bd_animador.cod_grado_instruccion
WHERE
pit_bd_animador.cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila4=mysql_fetch_array($result))
{
?>	  
      <tr>
        <td><? echo $fila4['nombres']." ".$fila4['paterno']." ".$fila4['materno'];?></td>
        <td class="centrado"><? echo $fila4['n_documento'];?></td>
        <td class="centrado"><? if ($fila4['sexo']==1) echo "MASCULINO"; else echo "FEMENINO";?></td>
        <td class="centrado"><? echo fecha_normal($fila4['f_nacimiento']);?></td>
        <td class="centrado"><? echo $fila4['descripcion'];?></td>
      </tr>
<?
}
?>	  
  </table>
    <br>
    <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
      <tr class="txt_titulo">
        <td colspan="3">Incentivos y financiamiento para Animadores Territoriales </td>
      </tr>
      <tr>
        <td width="33%">Incentivo Mensual por Animador Territorial (S/.) </td>
        <td width="2%">:</td>
        <td width="65%"><? echo number_format($row['incentivo_animador'],2);?></td>
      </tr>
      <tr>
        <td>N° de Animadores Territoriales asignados </td>
        <td>:</td>
        <td><? echo $row['n_animador'];?></td>
      </tr>
      <tr>
        <td>Plazo de ejecución del PIT </td>
        <td>:</td>
        <td><? echo $row['n_mes'];?> meses</td>
      </tr>
      <tr>
        <td>Total Incentivo para Animador Territorial (S/.) </td>
        <td>:</td>
        <td><? $total_incentivo=$row['aporte_pdss']+$row['aporte_org']; echo number_format($total_incentivo,2);?></td>
      </tr>
      <tr class="txt_titulo">
        <td colspan="3">Cofinanciamiento (S/.) </td>
      </tr>
      <tr>
        <td>Aporte SIERRA SUR II </td>
        <td>:</td>
        <td><? echo number_format($row['aporte_pdss'],2);?></td>
      </tr>
      <tr>
        <td>Aporte Organización </td>
        <td>:</td>
        <td><? echo number_format($row['aporte_org'],2);?></td>
      </tr>
    </table>
    <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td align="right">
    <form name="form1" method="post" action="">
    <button type="submit" class="button secondary oculto" onclick="window.print()">Imprimir</button>
    <?
    if ($modo==1)
    {
	 ?>
    <a href="../seguimiento/pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="button secondary oculto">Retornar al menu principal</a>	    
   <?
	  }
	    else
	    {
    ?>
    <a href="../pit/pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="button secondary oculto">Retornar al menu principal</a>
    <?
    }
    ?>


    </form>
    </td>
  </tr>
</table>
  </body>
</html>
