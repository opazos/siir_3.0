<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT (pit_bd_idl_sd.ejec_idl+ 
  pit_bd_idl_sd.ejec_org) AS monto_ejecutado, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  pit_bd_idl_sd.f_presentacion, 
  pit_bd_ficha_idl.denominacion, 
  pit_bd_ficha_idl.n_contrato, 
  pit_bd_ficha_idl.f_contrato, 
  (pit_bd_ficha_idl.aporte_pdss+ 
  pit_bd_ficha_idl.aporte_org+ 
  pit_bd_ficha_idl.aporte_otro) AS monto_contrato, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  sys_bd_personal.n_documento AS dni, 
  (pit_bd_idl_sd.ejec_idl+ 
  pit_bd_idl_sd.ejec_org) AS ejec_contrato, 
  pit_bd_idl_sd.ejec_idl, 
  pit_bd_idl_sd.ejec_org, 
  pit_bd_idl_sd.f_desembolso, 
  pit_bd_idl_sd.n_cheque, 
  pit_bd_idl_sd.pp_avance, 
  pit_bd_idl_sd.cumple_plazo, 
  pit_bd_idl_sd.just_plazo, 
  pit_bd_idl_sd.just_ejec
FROM pit_bd_ficha_idl INNER JOIN pit_bd_idl_sd ON pit_bd_ficha_idl.cod_ficha_idl = pit_bd_idl_sd.cod_idl
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_idl_sd.cod_ficha_sd='$cod'";
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
<?php
$sql="SELECT pit_bd_ficha_idl.denominacion, 
  org_ficha_organizacion.nombre, 
  org_ficha_organizacion.n_documento, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  org_ficha_organizacion.sector, 
  pit_bd_ficha_idl.objetivo, 
  sys_bd_tipo_idl.descripcion AS tipo_idl, 
  pit_bd_ficha_idl.familias, 
  pit_bd_ficha_idl.f_inicio, 
  pit_bd_ficha_idl.f_termino, 
  pit_bd_ficha_idl.n_contrato, 
  pit_bd_ficha_idl.f_contrato, 
  sys_bd_dependencia.nombre AS oficina, 
  clar_bd_evento_clar.f_evento, 
  pit_bd_idl_sd.pp_avance, 
  pit_bd_idl_sd.cumple_plazo, 
  pit_bd_idl_sd.just_plazo, 
  pit_bd_idl_sd.calif_1, 
  pit_bd_idl_sd.calif_2, 
  pit_bd_idl_sd.calif_3, 
  pit_bd_idl_sd.just_ejec, 
  pit_bd_idl_sd.f_presentacion, 
  pit_bd_idl_sd.cod_ficha_sd, 
  (pit_bd_ficha_idl.aporte_pdss+ 
  pit_bd_ficha_idl.aporte_org+ 
  pit_bd_ficha_idl.aporte_otro) AS total_programado, 
  (pit_bd_idl_sd.ejec_idl+ 
  pit_bd_idl_sd.ejec_org) AS total_ejecutado, 
  pit_bd_ficha_idl.aporte_pdss, 
  pit_bd_ficha_idl.aporte_org, 
  pit_bd_ficha_idl.aporte_otro, 
  pit_bd_idl_sd.ejec_idl, 
  pit_bd_idl_sd.ejec_org
FROM pit_bd_ficha_idl INNER JOIN pit_bd_idl_sd ON pit_bd_ficha_idl.cod_ficha_idl = pit_bd_idl_sd.cod_idl
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
   INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
   INNER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
   INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_idl.cod_clar
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_idl_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
?>
<? include("encabezado.php");?>
<div class="centrado txt_titulo capa">INVERSION DE DESARROLLO LOCAL<br/>INFORME DE AVANCE PARA SEGUNDO DESEMBOLSO</div>

<div class="capa">
  <p class="txt_titulo">1.- Nombre del Perfil de la IDL presentado en el CLAR</p>
  <p><? echo $r1['denominacion'];?></p>
</div>

<table width="90%" border="0" align="center" cellspacing="1" cellpadding="1">
  <tr>
    <td width="25%">Fecha de aprobación del CLAR</td>
    <td width="25%"><? echo traducefecha($r1['f_evento']);?></td>
    <td width="25%">Oficina Local</td>
    <td width="25%"><? echo $r1['oficina'];?></td>
  </tr>
</table>

<div class="capa">
  <p class="txt_titulo">2.- Objetivo de la IDL</p>
  <p><? echo $r1['objetivo'];?></p>

  <p class="txt_titulo">3.- Número de familias beneficiadas</p>
  <p><? echo number_format($r1['familias']);?> Familias aproximadamente.</p>

  <p class="txt_titulo">4.- Entidad u organismo ejecutor</p>
  <p><? echo $r1['nombre'];?></p>
</div>
<table width="90%" border="0" align="center" cellspacing="1" cellpadding="1">
  <tr>
    <td width="25%">Tipo de documento</td>
    <td width="25%"><? echo $r1['tipo_doc'];?></td>
    <td width="25%">Número de documento</td>
    <td width="25%"><? echo $r1['n_documento'];?></td>
  </tr>
  <tr>
    <td>Número de contrato</td>
    <td><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato']);?></td>
    <td>Fecha de firma de contrato</td>
    <td><? echo traducefecha($r1['f_contrato']);?></td>
  </tr>
</table>

<div class="capa txt_titulo"><p>5.- Datos de Ubicación de la IDL</p></div>

<table width="90%" border="0" align="center" cellspacing="1" cellpadding="1">
  <tr>
    <td width="25%">Departamento/Región</td>
    <td width="25%"><? echo $r1['departamento'];?></td>
    <td width="25%">Provincia</td>
    <td width="25%"><? echo $r1['provincia'];?></td>
  </tr>
  <tr>
    <td>Distrito</td>
    <td><? echo $r1['distrito'];?></td>
    <td>Sector</td>
    <td><? echo $r1['sector'];?></td>
  </tr>
</table>

<div class="capa txt_titulo"><p>6.- Periodo programado</p></div>
<table width="90%" border="0" align="center" cellspacing="1" cellpadding="1">
  <tr>
    <td width="25%">Fecha de inicio</td>
    <td width="25%"><? echo traducefecha($r1['f_inicio']);?></td>
    <td width="25%">Fecha de termino</td>
    <td width="25%"><? echo traducefecha($r1['f_termino']);?></td>
  </tr>

  <tr>
    <td>Nivel de avance de la obra</td>
    <td><? echo number_format($row['pp_avance'],2);?> %</td>
  </tr>
  </table>


<div class="capa">
<p class="txt_titulo">7.- Detalles de la ejecución de la Inversion de Desarrollo Local</p>
<p>La ejecución de la IDL se desarrollo en el Plazo previsto?</p>
<p><? if ($r1['cumple_plazo']==1) echo "Si, la IDL se desarrollo dentro del plazo previsto."; else echo "No, no se cumplieron los plazos.";?></p>
<?
if ($r1['cumple_plazo']<>1)
{
?>
<p class="txt_titulo">En caso de presentar retrasos en su ejecución, indicar los motivos</p>
<p><? echo $r1['just_plazo'];?></p>
<?
}
?>
</div>

<div class="capa txt_titulo"><p>8.- Descripción de las actividades desarrolladas para la ejecución de la Inversión de Desarrollo Local</p></div>

<table width="90%" border="1" align="center" cellspacing="1" cellpadding="1" class="mini">
<tr class="centrado txt_titulo">
  <td>N.</td>
  <td>DESCRIPCION DE LA ACTIVIDAD</td>
  <td>MESES PROGRAMADOS</td>
  <td>ESTADO</td>
  <td>SE CUMPLEN LOS PLAZOS?</td>
  <td>% DE AVANCE</td>
  <td>COMENTARIOS</td>
</tr>
<?
  $nam=0;
  $sql="SELECT idl_actividad_idl.descripcion, 
  idl_actividad_idl.cod_estado, 
  idl_actividad_idl.cumple_plazo, 
  idl_actividad_idl.avance, 
  idl_actividad_idl.comentario, 
  (idl_actividad_idl.m1+ 
  idl_actividad_idl.m2+ 
  idl_actividad_idl.m3+ 
  idl_actividad_idl.m4+ 
  idl_actividad_idl.m5+ 
  idl_actividad_idl.m6+ 
  idl_actividad_idl.m7+ 
  idl_actividad_idl.m8+ 
  idl_actividad_idl.m9+ 
  idl_actividad_idl.m10+ 
  idl_actividad_idl.m11+ 
  idl_actividad_idl.m12) as meses
FROM pit_bd_idl_sd INNER JOIN idl_actividad_idl ON pit_bd_idl_sd.cod_idl = idl_actividad_idl.cod_ficha_idl
WHERE pit_bd_idl_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
  $nam++
?>
<tr>
  <td><? echo $nam;?></td>
  <td><? echo $f2['descripcion'];?></td>
  <td class="derecha"><? echo $f2['meses'];?></td>
  <td class="centrado"><? if ($f2['cod_estado']==1) echo "SIN INICIAR"; elseif($f2['cod_estado']==2) echo "EN EJECUCION"; elseif($f2['cod_estado']==3) echo "CONCLUIDA";?></td>
  <td class="centrado"><? if ($f2['cumple_plazo']==1) echo "SI"; else echo "NO";?></td>
  <td class="derecha"><? echo number_format($f2['avance'],2);?></td>
  <td><? echo $f2['comentario'];?></td>
</tr>
<?
}
?>
</table>


<div class="capa txt_titulo"><p>9.- Descripción de Metas Físicas Ejecutadas</p></div>

<table width="90%" border="1" align="center" cellspacing="1" cellpadding="1" class="mini">
  <tr class="txt_titulo centrado">
   <td>N.</td>
   <td>METAS FISICAS</td>
   <td>UNIDAD</td>
   <td>PROGRAMADO</td>
   <td>EJECUTADO</td>
  </tr>
<?
$num=0;
$sql="SELECT idl_meta_fisica.descripcion, 
  idl_meta_fisica.unidad, 
  idl_meta_fisica.meta, 
  idl_meta_fisica.avance_fn
FROM pit_bd_idl_sd INNER JOIN idl_meta_fisica ON pit_bd_idl_sd.cod_idl = idl_meta_fisica.cod_ficha_idl
WHERE pit_bd_idl_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
    $num++
?>
  <tr>
    <td class="centrado"><? echo $num;?></td>
    <td><? echo $f1['descripcion'];?></td>
    <td class="centrado"><? echo $f1['unidad'];?></td>
    <td class="derecha"><? echo number_format($f1['meta'],2);?></td>
    <td class="derecha"><? echo number_format($f1['avance_fn'],2);?></td>
  </tr>
 <?
 }
 ?> 
</table>
<div class="capa txt_titulo"><p>10.- Rendición de cuentas</p></div>

<table width="90%" border="0" align="center" cellspacing="1" cellpadding="1">
  <tr>
    <td width="25%">Fecha de desembolso</td>
    <td width="25%"><? echo traducefecha($row['f_desembolso']);?></td>
    <td width="25%">N. de cheque con el que se desembolso</td>
    <td width="25%"><? echo $row['n_cheque'];?></td>
  </tr>
</table>

<div class="capa"><hr/></div>

<table width="90%" border="1" align="center" cellspacing="1" cellpadding="1" class="mini">
<tr class="centrado txt_titulo">
  <td>-</td>
  <td>MONTO APROBADO</td>
  <td>MONTO EJECUTADO</td>
  <td>%</td>
</tr>

<tr class="centrado txt_titulo">
  <td>APORTES</td>
  <td>S/.</td>
  <td>S/.</td>
  <td>%</td>
</tr>

<tr>
  <td class="centrado">PDSS</td>
  <td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
  <td class="derecha"><? echo number_format($r1['ejec_idl'],2);?></td>
  <td class="derecha"><? echo number_format(($r1['ejec_idl']/$r1['aporte_pdss'])*100,2);?></td>
</tr>

<tr>
  <td class="centrado">ENTIDAD</td>
  <td class="derecha"><? echo number_format($r1['aporte_org'],2);?></td>
  <td class="derecha"><? echo number_format($r1['ejec_org'],2);?></td>
  <td class="derecha"><? echo number_format(($r1['ejec_org']/$r1['aporte_org'])*100,2);?></td>
</tr>

<tr>
  <td class="centrado">OTRO</td>
  <td class="derecha"><? echo number_format($r1['aporte_otro'],2);?></td>
  <td class="derecha">0</td>
  <td class="derecha">0.00</td>
</tr>

<tr class="txt_titulo">
  <td class="centrado">TOTAL</td>
  <td class="derecha"><? echo number_format($r1['total_programado'],2);?></td>
  <td class="derecha"><? echo number_format($r1['total_ejecutado'],2);?></td>
  <td class="derecha"><? echo number_format($r1['total_ejecutado']/$r1['total_programado']*100,2);?></td>
</tr>
</table>




<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../pit/idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>





</body>
</html>
