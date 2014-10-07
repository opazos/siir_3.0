<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Buscamos los datos del concurso
$sql="SELECT org_ficha_organizacion.nombre, 
  cif_bd_concurso.n_concurso, 
  cif_bd_concurso.f_concurso, 
  cif_bd_concurso.actividad_1, 
  cif_bd_concurso.actividad_2, 
  cif_bd_concurso.actividad_3, 
  act1.descripcion AS describe1, 
  act1.unidad AS unidad1, 
  act2.descripcion AS describe2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS describe3, 
  act3.unidad AS unidad3
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   LEFT JOIN sys_bd_actividad_mrn act1 ON act1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
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
</head>
<body>
<? include("menu.php");?>
<!-- Verificamos actividad por actividad -->

<div class="row">
<div class="twelve columns" align="center"><h6>FICHA DE REGISTRO Y VALORIZACIÓN DE ACTIVOS CONCURSO INTERFAMILIAR N. <? echo numeracion($r1['n_concurso']);?> - <? echo periodo($r1['f_concurso']);?><br/> DEL PGRN: <? echo $r1['nombre'];?></h6></div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"> 

<!-- Generamos las TAB's o pestañas -->
<dl class="tabs">
<?php
if($r1['actividad_1']<>0)
{
?>
<dd class="active"><a href="#simple1"><small>I.-<? echo $r1['describe1'];?></small></a></dd>
<?
}
if($r1['actividad_2']<>0)
{
?>
<dd><a href="#simple2"><small>II.-<? echo $r1['describe2'];?></small></a></dd>
<?
}
if($r1['actividad_3']<>0)
{
?>
<dd><a href="#simple3"><small>III.-<? echo $r1['describe3'];?></small></a></dd>
<?
}
?>
</dl>

<!-- ========================================== Pestaña para la Primera Actividad ========================================== -->
<ul class="tabs-content">
<?php
if($r1['actividad_1']<>0)
{
  $sql="SELECT cif_bd_ficha_cif.cod_ficha_cif
  FROM cif_bd_ficha_cif
  WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
  cif_bd_ficha_cif.cod_actividad='".$r1['actividad_1']."'";
  $result=mysql_query($sql) or die (mysql_error());
  $total_1=mysql_num_rows($result);
?>
<li class="active" id="simple1Tab">
<div class="seven columns"><h6>Actividad de concurso: <? echo $r1['describe1'];?></h6></div>
<div class="five columns"><h6>Unidad de medida: <? echo $r1['unidad1'];?></h6></div>
<div class="twelve columns"><h6>Fecha del concurso: <? echo traducefecha($r1['f_concurso']);?></h6> </div>
<div class="twelve columns"><hr/></div>
<? include("../plugins/buscar/buscador.html");?>
<form id="form5" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_FICHA_1" onsubmit="return checkSubmit();">
<table id="lista">
<thead>
    <tr>
      <td rowspan="2"><small>N.</small></td>
      <td rowspan="2"><small>Nombre del participante</small></td>
      <td colspan="2"><small>Activo Físico antes del CIF</small></td>
      <td colspan="2"><small>Activo Físico logrado con el CIF</small></td>
      <td rowspan="2"><small>Puntaje</small></td>
      <td rowspan="2"><small>Puesto ocupado</small></td>
      <td colspan="2"><small>Premio recibido (S/.)</small></td>
      <td rowspan="2"><br/></td>
    </tr>
    <tr>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td> 
      <td><small>PDSS II</small></td>
      <td><small>Otros</small></td>     
    </tr>
</thead>

<tbody>

<!-- Aqui coloco los datos de las personas que ya registraron información -->
<?php

  $num=0;
  $sql="SELECT org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_ficha_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_actividad='".$r1['actividad_1']."'
ORDER BY cif_bd_ficha_cif.puntaje DESC,cif_bd_ficha_cif.puesto ASC, org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $cad=$f1['cod_ficha_cif'];
    $num++
?>
   <tr>
    <td><small><? echo $num;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small></td>
    <td><input type="text" name="cantidad1as[<? echo $cad;?>]" value="<? echo $f1['meta_1'];?>"></td>
    <td><input type="text" name="valor1as[<? echo $cad;?>]" value="<? echo $f1['valor_1'];?>"></td>
    <td><input type="text" name="cantidad2as[<? echo $cad;?>]" value="<? echo $f1['meta_2'];?>"></td>
    <td><input type="text" name="valor2as[<? echo $cad;?>]" value="<? echo $f1['valor_2'];?>"></td>
    <td><input type="text" name="puntajeas[<? echo $cad;?>]" value="<? echo $f1['puntaje'];?>"></td>
    <td><input type="text" name="puestoas[<? echo $cad;?>]" value="<? echo $f1['puesto'];?>"></td>
    <td><input type="text" name="premio_pdssas[<? echo $cad;?>]" value="<? echo $f1['premio_pdss'];?>"></td>
    <td><input type="text" name="premio_otroas[<? echo $cad;?>]" value="<? echo $f1['premio_otro'];?>"></td>
    <td><a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['cod_ficha_cif'];?>&tipo=1&action=DELETE_CALIF"><img src="../images/Delete.png" border="0" width="48" height="48"></a></td>
  </tr>
<?php
  }
?>
<tr>
  <td colspan="11"><hr/></td>
</tr>
<?php
  $na=0;
  $sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_1
WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_ficha_cif IS NULL
ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $na++
?>
  <tr>
    <td><small><? echo $na;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small><input type="hidden" name="dni[]" value="<? echo $f1['n_documento'];?>"></td>
    <td><input type="text" name="cantidad1a[]"></td>
    <td><input type="text" name="valor1a[]"></td>
    <td><input type="text" name="cantidad2a[]"></td>
    <td><input type="text" name="valor2a[]"></td>
    <td><input type="text" name="puntajea[]"></td>
    <td><input type="text" name="puestoa[]"></td>
    <td><input type="text" name="premio_pdssa[]"></td>
    <td><input type="text" name="premio_otroa[]"><input type="hidden" name="actividad[]" value="<? echo $r1['actividad_1'];?>"></td>
    <td><br/></td>
  </tr>
<?
}
?>
</tbody>
</table>

<!-- Campos Ocultos -->
<input type="hidden" name="registroa" value="<? echo $na;?>">
<input type="hidden" name="tab" value="#simple1">

<div class="twelve columns">
<input name="Submit2" type="button" class="primary button" value="Guardar cambios" onClick="this.form.action='gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&registros=<? echo $na;?>&tipo=1&action=ADD_FICHA_1'; this.form.submit()" id="btn_envia"/>
<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif" class="secondary button">Finalizar</a>
</div>
</form>
</li>

<!-- ========================================== Pestaña para la Segunda Actividad ========================================== -->
<?
}
if($r1['actividad_2']<>0)
{

  $sql="SELECT cif_bd_ficha_cif.cod_ficha_cif
  FROM cif_bd_ficha_cif
  WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
  cif_bd_ficha_cif.cod_actividad='".$r1['actividad_2']."'";
  $result=mysql_query($sql) or die (mysql_error());
  $total_2=mysql_num_rows($result);
?>
<li id="simple2Tab">
<div class="seven columns"><h6>Actividad de concurso: <? echo $r1['describe2'];?></h6></div>
<div class="five columns"><h6>Unidad de medida: <? echo $r1['unidad2'];?></h6></div>
<div class="twelve columns"><h6>Fecha del concurso: <? echo traducefecha($r1['f_concurso']);?></h6></div>
<div class="twelve columns"><hr/></div>
<? include("../plugins/buscar/buscador2.html");?>
<form id="form6" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_FICHA" onsubmit="return checkSubmit();">
<table id="lista2">
<thead>
    <tr>
      <td rowspan="2"><small>N.</small></td>
      <td rowspan="2"><small>Nombre del participante</small></td>
      <td colspan="2"><small>Activo Físico antes del CIF</small></td>
      <td colspan="2"><small>Activo Físico logrado con el CIF</small></td>
      <td rowspan="2"><small>Puntaje</small></td>
      <td rowspan="2"><small>Puesto ocupado</small></td>
      <td colspan="2"><small>Premio recibido (S/.)</small></td>
      <td rowspan="2"><br/></td>
    </tr>
    <tr>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td> 
      <td><small>PDSS II</small></td>
      <td><small>Otros</small></td>     
    </tr>
</thead>

<tbody>
<?
  $num=0;
  $sql="SELECT org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_ficha_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_actividad='".$r1['actividad_2']."'
ORDER BY cif_bd_ficha_cif.puntaje DESC,cif_bd_ficha_cif.puesto ASC, org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $cad=$f1['cod_ficha_cif'];
    $num++
?>
   <tr>
    <td><small><? echo $num;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small></td>
    <td><input type="text" name="cantidad1as[<? echo $cad;?>]" value="<? echo $f1['meta_1'];?>"></td>
    <td><input type="text" name="valor1as[<? echo $cad;?>]" value="<? echo $f1['valor_1'];?>"></td>
    <td><input type="text" name="cantidad2as[<? echo $cad;?>]" value="<? echo $f1['meta_2'];?>"></td>
    <td><input type="text" name="valor2as[<? echo $cad;?>]" value="<? echo $f1['valor_2'];?>"></td>
    <td><input type="text" name="puntajeas[<? echo $cad;?>]" value="<? echo $f1['puntaje'];?>"></td>
    <td><input type="text" name="puestoas[<? echo $cad;?>]" value="<? echo $f1['puesto'];?>"></td>
    <td><input type="text" name="premio_pdssas[<? echo $cad;?>]" value="<? echo $f1['premio_pdss'];?>"></td>
    <td><input type="text" name="premio_otroas[<? echo $cad;?>]" value="<? echo $f1['premio_otro'];?>"></td>
    <td><a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['cod_ficha_cif'];?>&tipo=2&action=DELETE_CALIF"><img src="../images/Delete.png" border="0" width="48" height="48"></a></td>
  </tr>
<? 
  }
?>
<tr>
  <td colspan="11"><hr/></td>
</tr>
<?
  $nb=0;
  $sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_2
WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_ficha_cif IS NULL
ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $nb++
?>
  <tr>
    <td><small><? echo $nb;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small><input type="hidden" name="dni[<? echo $nb;?>]" value="<? echo $f1['n_documento'];?>"></td>
    <td><input type="text" name="cantidad1a[<? echo $nb;?>]"></td>
    <td><input type="text" name="valor1a[<? echo $nb;?>]"></td>
    <td><input type="text" name="cantidad2a[<? echo $nb;?>]"></td>
    <td><input type="text" name="valor2a[<? echo $nb;?>]"></td>
    <td><input type="text" name="puntajea[<? echo $nb;?>]"></td>
    <td><input type="text" name="puestoa[<? echo $nb;?>]"></td>
    <td><input type="text" name="premio_pdssa[<? echo $nb;?>]"></td>
    <td><input type="text" name="premio_otroa[<? echo $nb;?>]"><input type="hidden" name="actividad[<? echo $nb;?>]" value="<? echo $r1['actividad_2'];?>"></td>
    <td><br/></td>
  </tr>
<?
  }
?>  
</tbody>
</table>


<!-- Campos Ocultos -->
<input type="hidden" name="registrob" value="<? echo $nb;?>">
<input type="hidden" name="tab" value="#simple2">

<div class="twelve columns">
<input name="Submit2" type="button" class="primary button" value="Guardar cambios" onClick="this.form.action='gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&registros=<? echo $nb;?>&tipo=2&action=ADD_FICHA_2'; this.form.submit()"/>
<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif" class="secondary button">Finalizar</a>
</div>    
</form>
</li>
<!-- ========================================== Pestaña para la Tercera Actividad ========================================== -->
<?
}
if($r1['actividad_3']<>0)
{
  $sql="SELECT cif_bd_ficha_cif.cod_ficha_cif
  FROM cif_bd_ficha_cif
  WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
  cif_bd_ficha_cif.cod_actividad='".$r1['actividad_3']."'";
  $result=mysql_query($sql) or die (mysql_error());
  $total_3=mysql_num_rows($result);  
?>
<li id="simple3Tab">
<div class="seven columns"><h6>Actividad de concurso: <? echo $r1['describe3'];?></h6></div>
<div class="five columns"><h6>Unidad de medida: <? echo $r1['unidad3'];?></h6></div>
<div class="twelve columns"><h6>Fecha del concurso: <? echo traducefecha($r1['f_concurso']);?></h6></div>
<div class="twelve columns"><hr/></div>
<? include("../plugins/buscar/buscador3.html");?>
<form id="form7" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_FICHA" onsubmit="return checkSubmit();">
<table id="lista3">
<thead>
    <tr>
      <td rowspan="2"><small>N.</small></td>
      <td rowspan="2"><small>Nombre del participante</small></td>
      <td colspan="2"><small>Activo Físico antes del CIF</small></td>
      <td colspan="2"><small>Activo Físico logrado con el CIF</small></td>
      <td rowspan="2"><small>Puntaje</small></td>
      <td rowspan="2"><small>Puesto ocupado</small></td>
      <td colspan="2"><small>Premio recibido (S/.)</small></td>
      <td rowspan="2"><br/></td>
    </tr>
    <tr>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td> 
      <td><small>PDSS II</small></td>
      <td><small>Otros</small></td>     
    </tr>
</thead>

<tbody>
<?
  $num=0;
  $sql="SELECT org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_ficha_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_actividad='".$r1['actividad_3']."'
ORDER BY cif_bd_ficha_cif.puntaje DESC,cif_bd_ficha_cif.puesto ASC, org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $cad=$f1['cod_ficha_cif'];
    $num++
?>
   <tr>
    <td><small><? echo $num;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small></td>
    <td><input type="text" name="cantidad1as[<? echo $cad;?>]" value="<? echo $f1['meta_1'];?>"></td>
    <td><input type="text" name="valor1as[<? echo $cad;?>]" value="<? echo $f1['valor_1'];?>"></td>
    <td><input type="text" name="cantidad2as[<? echo $cad;?>]" value="<? echo $f1['meta_2'];?>"></td>
    <td><input type="text" name="valor2as[<? echo $cad;?>]" value="<? echo $f1['valor_2'];?>"></td>
    <td><input type="text" name="puntajeas[<? echo $cad;?>]" value="<? echo $f1['puntaje'];?>"></td>
    <td><input type="text" name="puestoas[<? echo $cad;?>]" value="<? echo $f1['puesto'];?>"></td>
    <td><input type="text" name="premio_pdssas[<? echo $cad;?>]" value="<? echo $f1['premio_pdss'];?>"></td>
    <td><input type="text" name="premio_otroas[<? echo $cad;?>]" value="<? echo $f1['premio_otro'];?>"></td>
    <td><a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['cod_ficha_cif'];?>&tipo=3&action=DELETE_CALIF"><img src="../images/Delete.png" border="0" width="48" height="48"></a></td>
  </tr>
<? 
  }
?>
<tr>
  <td colspan="11"><hr/></td>
</tr>
<?
  $nc=0;
  $sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento AND cif_bd_ficha_cif.cod_actividad = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_ficha_cif IS NULL
ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $nc++
?>
  <tr>
    <td><small><? echo $nc;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small><input type="hidden" name="dni[]" value="<? echo $f1['n_documento'];?>"></td>
    <td><input type="text" name="cantidad1a[]"></td>
    <td><input type="text" name="valor1a[]"></td>
    <td><input type="text" name="cantidad2a[]"></td>
    <td><input type="text" name="valor2a[]"></td>
    <td><input type="text" name="puntajea[]"></td>
    <td><input type="text" name="puestoa[]"></td>
    <td><input type="text" name="premio_pdssa[]"></td>
    <td><input type="text" name="premio_otroa[]"><input type="hidden" name="actividad[]" value="<? echo $r1['actividad_3'];?>"></td>
    <td><br/></td>
  </tr>
<?
  }
?>  
</tbody>
</table>


<!-- Campos Ocultos -->
<input type="hidden" name="registroc" value="<? echo $nc;?>">
<input type="hidden" name="tab" value="#simple3">

<div class="twelve columns">
<input name="Submit2" type="button" class="primary button" value="Guardar cambios" onClick="this.form.action='gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&registros=<? echo $nc;?>&tipo=3&action=ADD_FICHA_3'; this.form.submit()"/>
<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif" class="secondary button">Finalizar</a>
</div>   
</form>
</li>
<?
}
?>
</ul>
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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla-2.js"></script>
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla-3.js"></script>
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
