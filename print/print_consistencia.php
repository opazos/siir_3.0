<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
clar_bd_evento_clar.cod_clar,
clar_bd_evento_clar.nombre,
clar_bd_evento_clar.f_evento,
sys_bd_dependencia.nombre AS oficina
FROM 
clar_bd_evento_clar
INNER JOIN
sys_bd_dependencia ON
clar_bd_evento_clar.cod_dependencia=sys_bd_dependencia.cod_dependencia
WHERE
clar_bd_evento_clar.cod_clar='$cod'";
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
    @page {
  size: A4 landscape; 
}
</style>
<!-- Fin -->
</head>

<body>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="18" class="centrado">PLANES DE INVERSION TERRITORIAL </td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        <td colspan="2">Cofinanciamiento</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Montto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
      </tr>
<?php 
$n1=0;

$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_taz.n_documento,
org_ficha_taz.nombre AS organizacion,
org_ficha_taz.f_creacion,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
pit_bd_ficha_pit.f_presentacion,
pit_bd_ficha_pit.n_animador,
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org,
pit_bd_ficha_pit.n_cuenta,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_pit.n_voucher,
pit_bd_ficha_pit.monto_organizacion,
sys_bd_estado_iniciativa.descripcion AS estado,
org_ficha_taz.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_taz.tesorero,
tesorero.nombre as nombre1,
tesorero.paterno as paterno1,
tesorero.materno as materno1
FROM
org_ficha_taz
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
LEFT JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
LEFT JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
LEFT JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
LEFT JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
LEFT JOIN clar_atf_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit.cod_pit
INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
LEFT JOIN org_ficha_directiva_taz AS presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
LEFT JOIN org_ficha_directiva_taz AS tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
WHERE
clar_bd_ficha_pit.cod_clar = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$n1++
?>      
      <tr>
      	<td class="centrado"><?php  echo $n1;?></td>
        <td class="centrado"><?php  echo $f1['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f1['n_documento'];?></td>
        <td><?php  echo $f1['organizacion'];?></td>
        <td class="centrado"><? echo $f1['presidente'];?></td>
        <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
        <td class="centrado"><? echo $f1['tesorero'];?></td>
        <td><? echo $f1['nombre1']." ".$f1['paterno1']." ".$f1['materno1'];?></td>
        <td class="centrado"><?php  echo $f1['departamento'];?></td>
        <td class="centrado"><?php  echo $f1['provincia'];?></td>
        <td class="centrado"><?php  echo $f1['distrito'];?></td>
        <td class="centrado"><?php  echo $f1['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f1['banco'];?></td>
   
        <td class="derecha"><?php  echo $f1['aporte_pdss'];?></td>
        <td class="derecha"><?php  echo $f1['aporte_org'];?></td>
        <td class="centrado"><?php  echo $f1['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f1['monto_organizacion'],2);?></td>
        <td class="centrado"><? echo $f1['estado'];?></td>
      </tr>
<?php 
}
?>     
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
	
	
	
<!-- PLANES DE NEGOCIO -->		
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="21" class="centrado">PLANES DE NEGOCIO </td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        <td colspan="2">Cofinanciamiento</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Montto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
      </tr>
<?php 
$n3=0;

$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.f_creacion,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
pit_bd_ficha_pdn.denominacion,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss,
(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org,
pit_bd_ficha_pdn.monto_organizacion,
pit_bd_ficha_pdn.n_voucher,
sys_bd_estado_iniciativa.descripcion AS estado,
org_ficha_organizacion.presidente,
org_ficha_organizacion.tesorero,
presidente.nombre,
presidente.paterno,
presidente.materno,
tesorero.nombre AS nombre1,
tesorero.paterno AS paterno1,
tesorero.materno AS materno1
FROM
org_ficha_organizacion
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
LEFT JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_bd_ficha_pdn.cod_clar ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$n3++
?>

      <tr>
        <td class="centrado"><?php  echo $n3;?></td>
        <td class="centrado"><?php  echo $f3['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f3['n_documento'];?></td>
        <td><?php  echo $f3['organizacion']." - ".$f3['denominacion'];?></td>
        <td class="centrado"><? echo $f3['presidente'];?></td>
        <td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
        <td class="centrado"><? echo $f3['tesorero'];?></td>
        <td><? echo $f3['nombre1']." ".$f3['paterno1']." ".$f3['materno1'];?></td>
        <td class="centrado"><?php  echo $f3['departamento'];?></td>
        <td class="centrado"><?php  echo $f3['provincia'];?></td>
        <td class="centrado"><?php  echo $f3['distrito'];?></td>
        <td class="centrado"><?php  echo $f3['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f3['banco'];?></td>
        <td class="derecha"><?php echo number_format($f3['aporte_pdss'],2);?></td>
        <td class="derecha"><?php  echo number_format($f3['aporte_org'],2);?></td>
        <td class="centrado"><?php  echo $f3['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f3['monto_organizacion'],2);?></td>
        <td class="centrado"><? echo $f3['estado'];?></td>
      </tr>
<?php 
}
?>
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<!-- FIN PDN -->	
	
	
<H1 class=SaltoDePagina> </H1>


<!-- PLANES DE NEGOCIO  INDEPENDIENTES-->		
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="21" class="centrado">PLANES DE NEGOCIO INDEPENDIENTES / JOVENES RURALES EMPRENDEDORES</td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        <td colspan="2">Cofinanciamiento</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Montto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
      </tr>
<?php 
$n5=0;

$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.f_creacion, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_voucher, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	org_ficha_organizacion.presidente, 
	org_ficha_organizacion.tesorero, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1
FROM org_ficha_organizacion INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());

while($f5=mysql_fetch_array($result))
{
	$n5++
?>

      <tr>
        <td class="centrado"><?php  echo $n5;?></td>
        <td class="centrado"><?php  echo $f5['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f5['n_documento'];?></td>
        <td><?php  echo $f5['organizacion']." - ".$f5['denominacion'];?></td>
        <td class="centrado"><? echo $f5['presidente'];?></td>
        <td><? echo $f5['nombre']." ".$f5['paterno']." ".$f5['materno'];?></td>
        <td class="centrado"><? echo $f5['tesorero'];?></td>
        <td><? echo $f5['nombre1']." ".$f5['paterno1']." ".$f5['materno1'];?></td>
        <td class="centrado"><?php  echo $f5['departamento'];?></td>
        <td class="centrado"><?php  echo $f5['provincia'];?></td>
        <td class="centrado"><?php  echo $f5['distrito'];?></td>
        <td class="centrado"><?php  echo $f5['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f5['banco'];?></td>
        <td class="derecha"><?php echo number_format($f5['aporte_pdss'],2);?></td>
        <td class="derecha"><?php  echo number_format($f5['aporte_org'],2);?></td>
        <td class="centrado"><?php  echo $f5['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f5['monto_organizacion'],2);?></td>
        <td class="centrado"><? echo $f5['estado'];?></td>
      </tr>
<?php 
}
?>
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<!-- FIN PDN -->	




<H1 class=SaltoDePagina> </H1>














<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="21" class="centrado">PLANES DE GESTIÓN DE RECURSOS NATURALES </td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        <td colspan="2">Cofinanciamiento</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Montto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
      </tr>
 <?php 
 $n2=0;
 
 $sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.f_creacion,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
pit_bd_ficha_mrn.sector,
pit_bd_ficha_mrn.n_cuenta,
(pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss,
(pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org) AS aporte_org,
pit_bd_ficha_mrn.monto_organizacion,
pit_bd_ficha_mrn.n_voucher,
sys_bd_ifi.descripcion AS banco,
sys_bd_estado_iniciativa.descripcion AS estado,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre1,
tesorero.paterno AS paterno1,
tesorero.materno AS materno1
FROM
org_ficha_organizacion
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
LEFT JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_bd_ficha_mrn.cod_clar = '$cod'";
 $result=mysql_query($sql) or die (mysql_error());
 while($f2=mysql_fetch_array($result))
 {
 	$n2++
 ?>     
      <tr>
        <td class="centrado"><?php  echo $n2;?></td>
        <td class="centrado"><?php  echo $f2['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f2['n_documento'];?></td>
        <td><?php  echo $f2['organizacion'];?></td>
        <td class="centrado"><? echo $f2['presidente'];?></td>
        <td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
        <td class="centrado"><? echo $f2['tesorero'];?></td>
        <td><? echo $f2['nombre1']." ".$f2['paterno1']." ".$f2['materno1'];?></td>
        <td class="centrado"><?php  echo $f2['departamento'];?></td>
        <td class="centrado"><?php  echo $f2['provincia'];?></td>
        <td class="centrado"><?php  echo $f2['distrito'];?></td>
        <td class="centrado"><?php  echo $f2['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f2['banco'];?></td>
        <td class="derecha"><?php  echo number_format($f2['aporte_pdss'],2);?></td>
        <td class="derecha"><?php  echo number_format($f2['aporte_org'],2);?></td>
        <td class="centrado"><?php  echo $f2['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f2['monto_organizacion'],2);?></td>
        <td class="centrado"><? echo $f2['estado'];?></td>
      </tr>
  <?php 
 }
 ?>    
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="17" class="centrado">INVERSIONES DE DESARROLLO LOCAL</td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="4%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="6%" rowspan="2">N° Documento </td>
        <td width="25%" rowspan="2">Nombre de la Organización </td>
        <td width="7%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="5%" rowspan="2">N° Cuenta </td>
        <td width="11%" rowspan="2">Banco</td>
        <td colspan="2">Cofinanciamiento</td>
        <td width="5%" rowspan="2">N° Voucher </td>
        <td width="3%" rowspan="2">Montto Depósito Organización </td>
        <td width="4%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="6%">Aporte NEC PDSS </td>
        <td width="8%">Aporte Organización</td>
      </tr>
<?php 

$n4=0;

$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.f_creacion,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,

pit_bd_ficha_idl.denominacion,
pit_bd_ficha_idl.n_cuenta,
pit_bd_ficha_idl.aporte_pdss,
pit_bd_ficha_idl.aporte_org,
sys_bd_estado_iniciativa.descripcion AS estado,
sys_bd_ifi.descripcion AS banco
FROM
org_ficha_organizacion
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN pit_bd_ficha_idl ON pit_bd_ficha_idl.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_idl.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
WHERE
clar_bd_ficha_idl.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
	$n4++
?>      
      <tr>
        <td class="centrado"><?php  echo $n4;?></td>
        <td class="centrado"><?php  echo $f4['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f4['n_documento'];?></td>
        <td><?php  echo $f4['organizacion'];?></td>
        <td class="centrado"><?php  echo $f4['departamento'];?></td>
        <td class="centrado"><?php  echo $f4['provincia'];?></td>
        <td class="centrado"><?php  echo $f4['distrito'];?></td>
        <td class="centrado"><?php  echo $f4['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f4['banco'];?></td>
        <td class="derecha"><?php  echo number_format($f4['aporte_pdss'],2);?></td>
        <td class="derecha"><?php  echo number_format($f4['aporte_org'],2);?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="centrado"><? echo $f4['estado'];?></td>
      </tr>
<?php 
}
?>      
    </table>
<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
	
	
<!-- CUADRO PLAN DE NEGOCIO DETALLADO	-->
	
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
<table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td colspan="20" align="center">CUADRO DE PLANES DE NEGOCIO DETALLADO </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="40" rowspan="2">N° Documento </td>
    <td width="222" rowspan="2">Nombre de la Organización </td>
    <td width="183" rowspan="2">Denominación del Plan de Negocios </td>
    <td width="53" rowspan="2">Departamento</td>
    <td width="56" rowspan="2">Provincia</td>
    <td width="57" rowspan="2">Distrito</td>
    <td width="101" rowspan="2">Sector</td>
    <td width="56" rowspan="2">N° Familias </td>
    <td colspan="2">Apoyo a la Gestión </td>
    <td colspan="2">ASISTENCIA TECNICA </td>
    <td colspan="2">VISITA GUIADA </td>
    <td colspan="2">PARTICIPACION EN FERIAS </td>
    <td width="83" rowspan="2">N° CUENTA </td>
    <td width="116" rowspan="2">BANCO</td>
    <td width="49" rowspan="2">N° VOUCHER </td>
    <td width="72" rowspan="2">MONTO DE LA ORGANIZACION </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="26">N° Apoyos </td>
    <td width="49">Monto total por Apoyo a la Gestión del PDN</td>
    <td width="39">NEC PDSS </td>
    <td width="40">ORG.</td>
    <td width="39">NEC PDSS </td>
    <td width="37">ORG.</td>
    <td width="39">NEC PDSS </td>
    <td width="40">ORG.</td>
  </tr>
<?
$sql="SELECT
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion AS linea,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
pit_bd_ficha_pdn.n_apoyo,
pit_bd_ficha_pdn.total_apoyo,
pit_bd_ficha_pdn.at_pdss,
pit_bd_ficha_pdn.at_org,
pit_bd_ficha_pdn.vg_pdss,
pit_bd_ficha_pdn.vg_org,
pit_bd_ficha_pdn.fer_pdss,
pit_bd_ficha_pdn.fer_org,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_pdn.n_voucher,
pit_bd_ficha_pdn.monto_organizacion,
Count(pit_bd_user_iniciativa.n_documento) AS socios
FROM
org_ficha_organizacion
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
LEFT JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
LEFT JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
LEFT JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_bd_ficha_pdn.cod_clar = '$cod' AND
org_ficha_usuario.titular = 1
GROUP BY
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
org_ficha_organizacion.sector,
pit_bd_ficha_pdn.n_apoyo,
pit_bd_ficha_pdn.total_apoyo,
pit_bd_ficha_pdn.at_pdss,
pit_bd_ficha_pdn.at_org,
pit_bd_ficha_pdn.vg_pdss,
pit_bd_ficha_pdn.vg_org,
pit_bd_ficha_pdn.fer_pdss,
pit_bd_ficha_pdn.fer_org,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion,
pit_bd_ficha_pdn.n_voucher,
pit_bd_ficha_pdn.monto_organizacion";
$result=mysql_query($sql) or die (mysql_error());
while($f11=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f11['n_documento'];?></td>
    <td><? echo $f11['organizacion'];?></td>
    <td><? echo $f11['denominacion'];?></td>
    <td class="centrado"><? echo $f11['departamento'];?></td>
    <td class="centrado"><? echo $f11['provincia'];?></td>
    <td class="centrado"><? echo $f11['distrito'];?></td>
    <td><? echo $f11['sector'];?></td>
    <td class="centrado"><? echo $f11['socios'];?></td>
    <td class="derecha"><? echo number_format($f11['n_apoyo'],2);?></td>
    <td class="derecha"><? echo number_format($f11['total_apoyo'],2);?></td>
    <td class="derecha"><? echo number_format($f11['at_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f11['at_org'],2);?></td>
    <td class="derecha"><? echo number_format($f11['vg_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f11['vg_org'],2);?></td>
    <td class="derecha"><? echo number_format($f11['fer_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f11['fer_org'],2);?></td>
    <td class="centrado"><? echo $f11['n_cuenta'];?></td>
    <td><? echo $f11['banco'];?></td>
    <td class="centrado"><? echo $f11['n_voucher'];?></td>
    <td class="derecha"><? echo number_format($f11['monto_organizacion'],2);?></td>
  </tr>
<?
}
?>  
</table>
<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<!-- TERMINO DE PDN DETALLADO -->	


<!-- CUADRO PLAN DE NEGOCIO DETALLADO	-->
	
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
<table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td colspan="20" align="center">CUADRO DE PLANES DE NEGOCIO DETALLADO / INDEPENDIENTE / JOVENES RURALES EMPRENDEDORES</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="40" rowspan="2">N° Documento </td>
    <td width="222" rowspan="2">Nombre de la Organización </td>
    <td width="183" rowspan="2">Denominación del Plan de Negocios </td>
    <td width="53" rowspan="2">Departamento</td>
    <td width="56" rowspan="2">Provincia</td>
    <td width="57" rowspan="2">Distrito</td>
    <td width="101" rowspan="2">Sector</td>
    <td width="56" rowspan="2">N° Familias </td>
    <td colspan="2">Apoyo a la Gestión </td>
    <td colspan="2">ASISTENCIA TECNICA </td>
    <td colspan="2">VISITA GUIADA </td>
    <td colspan="2">PARTICIPACION EN FERIAS </td>
    <td width="83" rowspan="2">N° CUENTA </td>
    <td width="116" rowspan="2">BANCO</td>
    <td width="49" rowspan="2">N° VOUCHER </td>
    <td width="72" rowspan="2">MONTO DE LA ORGANIZACION </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="26">N° Apoyos </td>
    <td width="49">Monto total por Apoyo a la Gestión del PDN</td>
    <td width="39">NEC PDSS </td>
    <td width="40">ORG.</td>
    <td width="39">NEC PDSS </td>
    <td width="37">ORG.</td>
    <td width="39">NEC PDSS </td>
    <td width="40">ORG.</td>
  </tr>
<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	pit_bd_ficha_pdn.n_apoyo, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.fer_org, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_pdn.n_voucher, 
	pit_bd_ficha_pdn.monto_organizacion, 
	Count(pit_bd_user_iniciativa.n_documento) AS socios
FROM org_ficha_organizacion INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 LEFT OUTER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE org_ficha_usuario.titular = 1 AND
clar_bd_ficha_pdn_suelto.cod_clar='$cod'
GROUP BY org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
org_ficha_organizacion.sector,
pit_bd_ficha_pdn.n_apoyo,
pit_bd_ficha_pdn.total_apoyo,
pit_bd_ficha_pdn.at_pdss,
pit_bd_ficha_pdn.at_org,
pit_bd_ficha_pdn.vg_pdss,
pit_bd_ficha_pdn.vg_org,
pit_bd_ficha_pdn.fer_pdss,
pit_bd_ficha_pdn.fer_org,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion,
pit_bd_ficha_pdn.n_voucher,
pit_bd_ficha_pdn.monto_organizacion";
$result=mysql_query($sql) or die (mysql_error());

while($f12=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f12['n_documento'];?></td>
    <td><? echo $f12['organizacion'];?></td>
    <td><? echo $f12['denominacion'];?></td>
    <td class="centrado"><? echo $f12['departamento'];?></td>
    <td class="centrado"><? echo $f12['provincia'];?></td>
    <td class="centrado"><? echo $f12['distrito'];?></td>
    <td><? echo $f12['sector'];?></td>
    <td class="centrado"><? echo $f12['socios'];?></td>
    <td class="derecha"><? echo number_format($f12['n_apoyo'],2);?></td>
    <td class="derecha"><? echo number_format($f12['total_apoyo'],2);?></td>
    <td class="derecha"><? echo number_format($f12['at_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f12['at_org'],2);?></td>
    <td class="derecha"><? echo number_format($f12['vg_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f12['vg_org'],2);?></td>
    <td class="derecha"><? echo number_format($f12['fer_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f12['fer_org'],2);?></td>
    <td class="centrado"><? echo $f12['n_cuenta'];?></td>
    <td><? echo $f12['banco'];?></td>
    <td class="centrado"><? echo $f12['n_voucher'];?></td>
    <td class="derecha"><? echo number_format($f12['monto_organizacion'],2);?></td>
  </tr>
<?
}
?>  
</table>
<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<!-- TERMINO DE PDN DETALLADO -->	

	

<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
<table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td colspan="18" align="center">CUADRO DE PLANES DE GESTION DE RECURSOS NATURALES DETALLADO </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="53" rowspan="2">N° Documento </td>
    <td width="206" rowspan="2">Nombre de la Organización </td>
    <td width="226" rowspan="2">Lema</td>
    <td width="73" rowspan="2">Departamento</td>
    <td width="61" rowspan="2">Provincia</td>
    <td width="60" rowspan="2">Distrito</td>
    <td width="103" rowspan="2">Sector</td>
    <td width="31" rowspan="2">N° Familias </td>
    <td width="51" rowspan="2">Monto de Apoyo a la gestión </td>
    <td colspan="2">Asistencia Técnica  </td>
    <td colspan="2">Visita Guiada </td>
    <td width="50" rowspan="2">Concursos Interfamiliares (CIF)  </td>
    <td width="94" rowspan="2">N° CUENTA </td>
    <td width="123" rowspan="2">BANCO</td>
    <td width="42" rowspan="2">N° VOUCHER </td>
    <td width="63" rowspan="2">MONTO DE LA ORGANIZACION </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="40">NEC PDSS </td>
    <td width="40">ORG.</td>
    <td width="45">NEC PDSS </td>
    <td width="46">ORG.</td>
  </tr>
  <?
$sql="SELECT
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
pit_bd_ficha_mrn.lema,
pit_bd_ficha_mrn.cif_pdss,
pit_bd_ficha_mrn.at_pdss,
pit_bd_ficha_mrn.at_org,
pit_bd_ficha_mrn.vg_pdss,
pit_bd_ficha_mrn.vg_org,
pit_bd_ficha_mrn.ag_pdss,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_mrn.n_voucher,
pit_bd_ficha_mrn.monto_organizacion,
Count(pit_bd_user_iniciativa.n_documento) AS socios
FROM
org_ficha_organizacion
LEFT JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
LEFT JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
LEFT JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
LEFT JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
LEFT JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
WHERE
clar_bd_ficha_mrn.cod_clar = '$cod' AND
org_ficha_usuario.titular = 1 AND
pit_bd_user_iniciativa.cod_tipo_iniciativa = 5
GROUP BY
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
org_ficha_organizacion.sector,
pit_bd_ficha_mrn.lema,
pit_bd_ficha_mrn.cif_pdss,
pit_bd_ficha_mrn.at_pdss,
pit_bd_ficha_mrn.at_org,
pit_bd_ficha_mrn.vg_pdss,
pit_bd_ficha_mrn.vg_org,
pit_bd_ficha_mrn.ag_pdss,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion,
pit_bd_ficha_mrn.n_voucher,
pit_bd_ficha_mrn.monto_organizacion";
$result=mysql_query($sql) or die (mysql_error());
while($f21=mysql_fetch_array($result))
{
?>
  <tr>
    <td class="centrado"><? echo $f21['n_documento'];?></td>
    <td><? echo $f21['organizacion'];?></td>
    <td><? echo $f21['lema'];?></td>
    <td class="centrado"><? echo $f21['departamento'];?></td>
    <td class="centrado"><? echo $f21['provincia'];?></td>
    <td class="centrado"><? echo $f21['distrito'];?></td>
    <td><? echo $f21['sector'];?></td>
    <td class="centrado"><? echo $f21['socios'];?></td>
    <td class="derecha"><? echo number_format($f21['ag_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f21['at_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f21['at_org'],2);?></td>
    <td class="derecha"><? echo number_format($f21['vg_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($f21['vg_org'],2);?></td>
    <td class="derecha"><? echo number_format($f21['cif_pdss'],2);?></td>
    <td class="centrado"><? echo $f21['n_cuenta'];?></td>
    <td><? echo $f21['banco'];?></td>
    <td class="centrado"><? echo $f21['n_voucher'];?></td>
    <td class="derecha"><? echo number_format($f21['monto_organizacion'],2);?></td>
  </tr>
  <?
}
?>
</table>
<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<br>	



<!-- Aqui empieza la informacion para segundos desembolsos -->
<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
 <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="24" class="centrado">PLANES DE INVERSION TERRITORIAL - SEGUNDOS DESEMBOLSOS</td>
      </tr>
       <tr class="centrado txt_titulo">
        <td  rowspan="2">N°</td>
        <td  rowspan="2">Tipo Documento </td>
        <td  rowspan="2">N° Documento </td>
        <td  rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td rowspan="2">Departamento</td>
        <td rowspan="2">Provincia</td>
        <td rowspan="2">Distrito</td>
        <td rowspan="2">N° Cuenta </td>
        <td rowspan="2">Banco</td>
        
        <td rowspan="2">Aporte PDSS</td>
        <td rowspan="2">Aporte Org.</td>
        
        
        <td colspan="2">Monto desembolsado</td>
        <td rowspan="2">N° Voucher </td>
        <td rowspan="2">Monto Depósito Organización</td>        
        <td colspan="2">Monto a desembolsar</td>
        <td rowspan="2">N° Voucher </td>
        <td rowspan="2">Monto Depósito Organización</td>
        <td rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td>DNI</td>
        <td>Presidente</td>
        <td>DNI</td>
        <td>Tesorero</td>
        <td>Aporte NEC PDSS </td>
        <td>Aporte Organización</td>
        <td>Aporte NEC PDSS </td>
        <td>Aporte Organización</td>
      </tr>
<?php 
$n1=0;

$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre AS organizacion, 
	org_ficha_taz.f_creacion, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	pit_bd_ficha_pit.n_animador, 
	
	pit_bd_ficha_pit.aporte_pdss AS monto_pdss,
	pit_bd_ficha_pit.aporte_org AS monto_org,

	(pit_bd_ficha_pit.aporte_pdss*0.70) AS aporte_pdss_1,
	(pit_bd_ficha_pit.aporte_pdss*0.30) AS aporte_pdss_2, 
	(pit_bd_ficha_pit.aporte_org*0.50) AS aporte_org_1,
	(pit_bd_ficha_pit.aporte_org*0.50) AS aporte_org_2, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	org_ficha_taz.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_taz.tesorero, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	pit_bd_ficha_pit.f_presentacion_2, 
	pit_bd_ficha_pit.n_voucher_2, 
	pit_bd_ficha_pit.monto_organizacion_2, 
	pit_bd_ficha_pit.n_voucher, 
	pit_bd_ficha_pit.monto_organizacion
FROM org_ficha_taz INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 LEFT OUTER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 LEFT OUTER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 LEFT OUTER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 LEFT OUTER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
	 LEFT OUTER JOIN org_ficha_directiva_taz presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
	 LEFT OUTER JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
WHERE clar_bd_ficha_pit_2.cod_clar ='$cod'
";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$n1++
?>      
      <tr>
      	<td class="centrado"><?php  echo $n1;?></td>
        <td class="centrado"><?php  echo $f1['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f1['n_documento'];?></td>
        <td><?php  echo $f1['organizacion'];?></td>
        <td class="centrado"><? echo $f1['presidente'];?></td>
        <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
        <td class="centrado"><? echo $f1['tesorero'];?></td>
        <td><? echo $f1['nombre1']." ".$f1['paterno1']." ".$f1['materno1'];?></td>
        <td class="centrado"><?php  echo $f1['departamento'];?></td>
        <td class="centrado"><?php  echo $f1['provincia'];?></td>
        <td class="centrado"><?php  echo $f1['distrito'];?></td>
        <td class="centrado"><?php  echo $f1['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f1['banco'];?></td>
   
        <td class="derecha"><?php  echo number_format($f1['monto_pdss'],2);?></td>
        <td class="derecha"><?php  echo number_format($f1['monto_org'],2);?></td>
   
        <td class="derecha"><?php  echo number_format($f1['aporte_pdss_1'],2);?></td>
        <td class="derecha"><?php  echo number_format($f1['aporte_org_1'],2);?></td>  
        <td class="centrado"><?php  echo $f1['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f1['monto_organizacion'],2);?></td>        
   
        <td class="derecha"><?php  echo number_format($f1['aporte_pdss_2'],2);?></td>
        <td class="derecha"><?php  echo number_format($f1['monto_org']-$f1['monto_organizacion'],2);?></td>
        <td class="centrado"><?php  echo $f1['n_voucher_2'];?></td>
        <td class="derecha"><?php  echo number_format($f1['monto_organizacion_2'],2);?></td>
        <td class="centrado"><? echo $f1['estado'];?></td>
      </tr>
<?php 
}
?>     
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
	


<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="24" class="centrado">PLANES DE NEGOCIO - SEGUNDOS DESEMBOLSOS</td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        
        
        <td rowspan="2">Aporte PDSS</td>
        <td rowspan="2">Aporte Org.</td>
        
        <td colspan="2">Monto desembolsado</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Monto Depósito Organización </td>        
        <td colspan="2">Monto por desembolsar</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Monto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>        
      </tr>
<?php 
$n3=0;

$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.f_creacion, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 

(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) as monto_pdss,

(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) as monto_org,

	((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss_1, 
	((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_pdss_2, 
	((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org_1, 
	((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org_2, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	org_ficha_organizacion.presidente, 
	org_ficha_organizacion.tesorero, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	pit_bd_ficha_pdn.n_voucher_2, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.n_voucher, 
	pit_bd_ficha_pdn.monto_organizacion, 
	clar_bd_ficha_pdn_2.cod_ficha_pdn_clar
FROM org_ficha_organizacion INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN clar_bd_ficha_pdn_2 ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE clar_bd_ficha_pdn_2.cod_clar ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$n3++
?>

      <tr>
        <td class="centrado"><?php  echo $n3;?></td>
        <td class="centrado"><?php  echo $f3['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f3['n_documento'];?></td>
        <td><?php  echo $f3['organizacion']." - ".$f3['denominacion'];?></td>
        <td class="centrado"><? echo $f3['presidente'];?></td>
        <td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
        <td class="centrado"><? echo $f3['tesorero'];?></td>
        <td><? echo $f3['nombre1']." ".$f3['paterno1']." ".$f3['materno1'];?></td>
        <td class="centrado"><?php  echo $f3['departamento'];?></td>
        <td class="centrado"><?php  echo $f3['provincia'];?></td>
        <td class="centrado"><?php  echo $f3['distrito'];?></td>
        <td class="centrado"><?php  echo $f3['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f3['banco'];?></td>
        
        <td class="derecha"><?php echo number_format($f3['monto_pdss'],2);?></td>
        <td class="derecha"><?php echo number_format($f3['monto_org'],2);?></td>
        
        
        <td class="derecha"><?php echo number_format($f3['aporte_pdss_1'],2);?></td>
        <td class="derecha"><?php  echo number_format($f3['aporte_org_1'],2);?></td>   
        <td class="centrado"><?php  echo $f3['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f3['monto_organizacion'],2);?></td>             
        <td class="derecha"><?php echo number_format($f3['aporte_pdss_2'],2);?></td>
        <td class="derecha"><?php  echo number_format($f3['monto_org']-$f3['monto_organizacion'],2);?></td>
        <td class="centrado"><?php  echo $f3['n_voucher_2'];?></td>
        <td class="derecha"><?php  echo number_format($f3['monto_organizacion_2'],2);?></td>
        <td class="centrado"><? echo $f3['estado'];?></td>
      </tr>
<?php 
}
?>
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>
<!-- FIN PDN -->	




<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="24" class="centrado">PLANES DE GESTIÓN DE RECURSOS NATURALES - SEGUNDOS DESEMBOLSOS</td>
      </tr>
       <tr class="centrado txt_titulo">
        <td width="2%" rowspan="2">N°</td>
        <td width="6%" rowspan="2">Tipo Documento </td>
        <td width="5%" rowspan="2">N° Documento </td>
        <td width="15%" rowspan="2">Nombre de la Organización </td>
        <td colspan="4">Directiva</td>
        <td width="4%" rowspan="2">Departamento</td>
        <td width="5%" rowspan="2">Provincia</td>
        <td width="5%" rowspan="2">Distrito</td>
        <td width="6%" rowspan="2">N° Cuenta </td>
        <td width="7%" rowspan="2">Banco</td>
        
        <td rowspan="2">Aporte PDSS</td>
        <td rowspan="2">Aporte Org.</td>        
        
        <td colspan="2">Monto desembolsado</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Monto Depósito Organización </td>        
        <td colspan="2">Monto por desembolsar</td>
        <td width="4%" rowspan="2">N° Voucher </td>
        <td width="4%" rowspan="2">Monto Depósito Organización </td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="3%">DNI</td>
        <td width="8%">Presidente</td>
        <td width="3%">DNI</td>
        <td width="8%">Tesorero</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>
        <td width="4%">Aporte NEC PDSS </td>
        <td width="5%">Aporte Organización</td>        
      </tr>
 <?php 
 $n2=0;
 
 $sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.f_creacion, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.n_cuenta, 
	(pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss) AS monto_pdss, 
	(pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org) AS monto_org, 
	((pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss)*0.70) AS aporte_pdss_1, 
	((pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_pdss_2, 
	((pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org_1, 
	((pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org)-pit_bd_ficha_mrn.monto_organizacion) AS aporte_org_2, 
	sys_bd_ifi.descripcion AS banco, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	pit_bd_ficha_mrn.n_voucher_2, 
	pit_bd_ficha_mrn.monto_organizacion_2, 
	pit_bd_ficha_mrn.n_voucher, 
	pit_bd_ficha_mrn.monto_organizacion
FROM org_ficha_organizacion INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
	 INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 LEFT OUTER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE clar_bd_ficha_mrn_2.cod_clar ='$cod'";
 $result=mysql_query($sql) or die (mysql_error());
 while($f2=mysql_fetch_array($result))
 {
 	$n2++
 ?>     
      <tr>
        <td class="centrado"><?php  echo $n2;?></td>
        <td class="centrado"><?php  echo $f2['tipo_doc'];?></td>
        <td class="centrado"><?php  echo $f2['n_documento'];?></td>
        <td><?php  echo $f2['organizacion'];?></td>
        <td class="centrado"><? echo $f2['presidente'];?></td>
        <td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
        <td class="centrado"><? echo $f2['tesorero'];?></td>
        <td><? echo $f2['nombre1']." ".$f2['paterno1']." ".$f2['materno1'];?></td>
        <td class="centrado"><?php  echo $f2['departamento'];?></td>
        <td class="centrado"><?php  echo $f2['provincia'];?></td>
        <td class="centrado"><?php  echo $f2['distrito'];?></td>
        <td class="centrado"><?php  echo $f2['n_cuenta'];?></td>
        <td class="centrado"><?php  echo $f2['banco'];?></td>
        
         <td class="derecha"><?php  echo number_format($f2['monto_pdss'],2);?></td>
          <td class="derecha"><?php  echo number_format($f2['monto_org'],2);?></td>
        
        <td class="derecha"><?php  echo number_format($f2['aporte_pdss_1'],2);?></td>
        <td class="derecha"><?php  echo number_format($f2['monto_organizacion'],2);?></td>   
        <td class="centrado"><?php  echo $f2['n_voucher'];?></td>
        <td class="derecha"><?php  echo number_format($f2['monto_organizacion'],2);?></td>             
        <td class="derecha"><?php  echo number_format($f2['aporte_pdss_2'],2);?></td>
        <td class="derecha"><?php  echo number_format($f2['monto_org']-$f2['monto_organizacion'],2);?></td>
        <td class="centrado"><?php  echo $f2['n_voucher_2'];?></td>
        <td class="derecha"><?php  echo number_format($f2['monto_organizacion_2'],2);?></td>
        <td class="centrado"><? echo $f2['estado'];?></td>
      </tr>
  <?php 
 }
 ?>    
    </table>
	<br>
	<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>


<H1 class=SaltoDePagina> </H1>
<div class="capa txt_titulo mini">NEC PROYECTO SIERRA SUR II - OFICINA LOCAL DE <?php  echo $row['oficina'];?> <br>REPORTE SITUACIONAL DE INICIATIVAS PARTICIPANTES EN EL CLAR N° <?php  echo numeracion($row['cod_clar']);?> - <?php  echo periodo($row['f_evento']);?> - OL  <?php  echo $row['oficina'];?>, "<?php  echo $row['nombre'];?>" <?php  if ($row['f_evento']<$fecha_hoy) echo "Realizado el día: "; else echo "a Realizarse el día: ";?> <? echo traducefecha($row['f_evento']);?></div>
<div class="break"></div>
    <table width="1500px" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
      <tr class="txt_titulo">
        <td colspan="19" class="centrado">INVERSIONES DE DESARROLLO LOCAL - SEGUNDOS DESEMBOLSOS</td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="1%" rowspan="2">N°</td>
        <td width="3%" rowspan="2">Tipo Documento </td>
        <td width="3%" rowspan="2">N° Documento </td>
        <td width="14%" rowspan="2">Nombre de la Organización </td>
        <td width="7%" rowspan="2">Departamento</td>
        <td width="7%" rowspan="2">Provincia</td>
        <td width="7%" rowspan="2">Distrito</td>
        <td width="7%" rowspan="2">N° Cuenta </td>
        <td width="11%" rowspan="2">Banco</td>
        <td height="26" colspan="2">Monto Contrato</td>
        <td width="10%">Monto Desembolsado</td>
       
        <td width="10%">Monto pendiente por desembolsar</td>
        <td width="6%" rowspan="2">Estado Situacional </td>
      </tr>
      <tr class="centrado txt_titulo">
        <td width="7%">Aporte NEC PDSS </td>
        <td width="7%">Aporte Organización</td>
        <td>NEC PDSS</td>
        <td>NEC PDSS</td>
      </tr>
<?php 
$n6=0;

$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.aporte_org, 
	((pit_bd_ficha_idl.aporte_pdss*pit_bd_ficha_idl.primer_pago)/100) AS primer_desembolso, 
	((pit_bd_ficha_idl.aporte_pdss*pit_bd_ficha_idl.segundo_pago)/100) AS segundo_desembolso, 
	pit_bd_ficha_idl.primer_pago, 
	pit_bd_ficha_idl.segundo_pago, 
	pit_bd_ficha_idl.contrapartida_2, 
	pit_bd_ficha_idl.denominacion, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE clar_bd_ficha_idl_2.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
	$n6++
?>      
      <tr>
        <td class="centrado"><? echo $n6;?></td>
        <td class="centrado"><? echo $f6['tipo_doc'];?></td>
        <td class="centrado"><? echo $f6['n_documento'];?></td>
        <td><? echo $f6['nombre'];?></td>
        <td class="centrado"><? echo $f6['departamento'];?></td>
        <td class="centrado"><? echo $f6['provincia'];?></td>
        <td class="centrado"><? echo $f6['distrito'];?></td>
        <td class="centrado"><? echo $f6['n_cuenta'];?></td>
        <td class="centrado"><? echo $f6['ifi'];?></td>
        <td class="derecha"><? echo number_format($f6['aporte_pdss'],2);?></td>
        <td class="derecha"><? echo number_format($f6['aporte_org'],2);?></td>
        <td class="derecha"><? echo number_format($f6['primer_desembolso'],2);?></td>
        <td class="derecha"><? echo number_format($f6['segundo_desembolso'],2);?></td>
        <td class="centrado"><? echo $f6['estado'];?></td>
      </tr>
<?php 
}
?>      
    </table>
<br>
<div class="capa mini txt_titulo">Fecha : <?php  echo traducefecha($fecha_hoy);?></div>

<p><br/></p>
<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_consistencia" class="secondary button oculto">Finalizar</a>
</div>
</body>
</html>
