<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.-Busco la información para la solicitud de pago
$sql="SELECT clar_bd_ficha_sd_pit.cod_clar, 
  clar_bd_ficha_sd_pit.f_desembolso, 
  clar_bd_ficha_sd_pit.n_solicitud, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.f_contrato, 
  sys_bd_dependencia.nombre AS oficina, 
  org_ficha_taz.nombre, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  clar_bd_ficha_sd_pit.fte_fida, 
  clar_bd_ficha_sd_pit.fte_ro
FROM clar_bd_ficha_sd_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE clar_bd_ficha_sd_pit.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//2.- Obtengo la información del CLAR
$sql="SELECT
clar_bd_ficha_sd_pit.cod_clar,
clar_bd_evento_clar.nombre AS clar,
clar_bd_evento_clar.f_evento,
clar_bd_evento_clar.lugar,
sys_bd_distrito.nombre AS distrito
FROM
clar_bd_ficha_sd_pit
INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_sd_pit.cod_clar
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
WHERE
clar_bd_ficha_sd_pit.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);


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
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?></u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="23%">A</td>
    <td width="1%">:</td>
    <td width="76%">C.P.C JUAN SALAS ACOSTA </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="76%">ADMINISTRADOR DEL NEC PDSS II </td>
  </tr>
  <tr>
    <td>CC</td>
    <td width="1%">:</td>
    <td width="76%">Responsable de Componentes </td>
  </tr>
  <tr>
    <td>ASUNTO</td>
    <td width="1%">:</td>
    <td width="76%"><b>SEGUNDO DESEMBOLSO </b> DE INICIATIVAS DEL PLAN DE INVERSION TERRITORIAL: <?php  echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_desembolso']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al segundo desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="26%">Nombre de la Organización </td>
    <td width="14%">Tipo de Iniciativa </td>
    <td width="12%">ATF N° </td>
    <td width="23%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
  
 <!-- CASO 1: PIT --> 
  <?php 
  $sql="SELECT
clar_atf_pit_sd.n_atf,
clar_atf_pit_sd.monto_desembolsado,
sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo,
sys_bd_ifi.descripcion AS ifi,
pit_bd_ficha_pit.n_cuenta,
org_ficha_taz.nombre
FROM
clar_atf_pit_sd
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit_sd.cod_pit
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE
clar_atf_pit_sd.cod_ficha_sd='$cod'";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
  	$pit=$f1['monto_desembolsado'];
  	$total_pit=$total_pit+$pit;
  ?>
  <tr>
    <td><?php  echo $f1['nombre'];?></td>
    <td class="centrado"><?php  echo $f1['codigo'];?></td>
    <td class="centrado"><?php  echo numeracion($f1['n_atf']);?></td>
    <td><?php  echo $f1['ifi'];?></td>
    <td class="centrado"><?php  echo $f1['n_cuenta'];?></td>
    <td class="derecha"><?php  echo number_format($f1['monto_desembolsado'],2);?></td>
  </tr>
  <?php 
  }
  ?>

  <!-- CASO 2: MRN -->
  <?php 
  $sql="SELECT
clar_atf_mrn_sd.n_atf,
(clar_atf_mrn_sd.monto_1+
clar_atf_mrn_sd.monto_2+
clar_atf_mrn_sd.monto_3+
clar_atf_mrn_sd.monto_4) AS monto_desembolsado,
sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS ifi,
org_ficha_organizacion.nombre
FROM
clar_atf_mrn_sd
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn_sd.cod_mrn
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE
clar_atf_mrn_sd.cod_ficha_sd='$cod'";
  $result=mysql_query($sql) or die (mysql_error());
  while($f2=mysql_fetch_array($result))
  {
  	$mrn=$f2['monto_desembolsado'];
  	$total_mrn=$total_mrn+$mrn;
  ?>
  <tr>
    <td><?php  echo $f2['nombre'];?></td>
    <td class="centrado"><?php  echo $f2['codigo'];?></td>
    <td class="centrado"><?php  echo numeracion($f2['n_atf']);?></td>
    <td><?php  echo $f2['ifi'];?></td>
    <td class="centrado"><?php  echo $f2['n_cuenta'];?></td>
    <td class="derecha"><?php  echo number_format($f2['monto_desembolsado'],2);?></td>
  </tr> 
  <?php 
  }
  ?>
  
<!-- CASO 3: PDN -->
  <?php 
  $sql="SELECT clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	org_ficha_organizacion.nombre
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
clar_atf_pdn.cod_relacionador='$cod' AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000";
  $result=mysql_query($sql) or die (mysql_error());
  while($f3=mysql_fetch_array($result))
  {
  	$pdn=$f3['monto_desembolsado'];
  	$total_pdn=$total_pdn+$pdn;
  ?>
  <tr>
    <td><?php  echo $f3['nombre'];?></td>
    <td class="centrado"><?php  echo $f3['codigo'];?></td>
    <td class="centrado"><?php  echo numeracion($f3['n_atf']);?></td>
    <td><?php  echo $f3['ifi'];?></td>
    <td class="centrado"><?php  echo $f3['n_cuenta'];?></td>
    <td class="derecha"><?php  echo number_format($f3['monto_desembolsado'],2);?></td>
  </tr>
<?php 
  }
  ?>
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha">
    <?php 
    $total_desembolso=$total_pit+$total_mrn+$total_pdn;
    echo number_format($total_desembolso,2);
    ?>
    </td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente las autorizaciones de transferencia de fondos de cada una de las organizaciones </td>
  </tr>
  <tr>
    <td><br>Atentamente,</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>

<!-- ANEXO EN CASO EXISTA PIT -->
<?php 
$na=0;
$sql="SELECT org_ficha_taz.nombre, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	pit_bd_ficha_pit.n_animador, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	presidente.nombre AS nombre1, 
	presidente.paterno AS paterno1, 
	presidente.materno AS materno1, 
	tesorero.nombre AS nombre2, 
	tesorero.paterno AS paterno2, 
	tesorero.materno AS materno2
FROM clar_atf_pit_sd INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit_sd.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
WHERE clar_atf_pit_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

while($f4=mysql_fetch_array($result))
{
	$na++
?>

<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $na;?></u> </td>
  </tr>
  <tr>
    <td class="txt_titulo centrado">Aportes de cofinanciamiento  de los Animadores Territoriales<br>SEGUNDO DESEMBOLSO </td>
  </tr>
  <tr>
    <td class="txt_titulo centrado"><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT:</strong> <? echo $f4['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización :</strong> <? echo $f4['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Número de animadores territoriales :</strong> <? echo $f4['n_animador'];?></td>
  </tr>
  <tr>
  <td><strong>Referencia :</strong>CONTRATO <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td><strong>Fecha de término :</strong><? echo traducefecha($f4['f_termino']);?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="215" align="center"><strong>CONCEPTO</strong></td>
    <td width="55" align="center"><strong>Aporte<br>
    SIERRA SUR  II</strong></td>
    <td width="22" align="center" valign="middle" nowrap class="mini"><strong>%</strong></td>
    <td width="30" align="center" valign="middle" nowrap class="mini"><strong>Aporte<br>
      SOCIOS</strong></td>
    <td width="20" align="center" valign="middle" nowrap class="mini"><strong>%</strong></td>
    <td width="26" align="center"><strong>TOTAL</strong></td>
    <td width="26" align="center"><strong>%</strong></td>
  </tr>
  <tr>
    <td nowrap valign="bottom">I.- <strong>Animadores Territoriales</strong> (<? echo $f4['n_animador'];?>) de EL PIT </td>
    <td align="right"><? echo number_format($f4['aporte_pdss'],2);?></td>
    <td align="right"><? @$pp_at=$f4['aporte_pdss']/$f4['aporte_pdss']+$f4['aporte_org']*100; echo number_format(@$pp_at,2);?></td>
    <td align="right"><? echo number_format($f4['aporte_org'],2);?></td>
    <td align="right">
    <?
	@$pp_an=($f4['aporte_org']/($f4['aporte_org']+$f4['aporte_pdss']))*100;
	echo number_format(@$pp_an,2);
	?>    </td>
    <td align="right"><? echo number_format($f4['aporte_pdss']+$f4['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($pp_at+$pp_an,2);?></td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($f4['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format(@$pp_at,2);?></td>
    <td align="right"><? echo number_format($f4['aporte_org'],2);?></td>
    <td align="right"><? echo number_format(@$pp_an,2);?></td>
    <td align="right" ><? echo number_format($f4['aporte_pdss']+$f4['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($pp_at+$pp_an,2);?></td>
  </tr>
  <tr>
    <td colspan="7" align="center" valign="bottom" nowrap><strong>N °  Desembolso del Animador Territorial</strong></td>
  </tr>
  <tr>
    <td nowrap valign="bottom"><strong>Primero</strong></td>
    <td align="right"><? echo number_format($f4['aporte_pdss']*0.70);?></td>
    <td align="right">70.00</td>
    <td align="right"><? echo number_format($f4['aporte_org']*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$atpdss1=$f4['aporte_pdss']*0.70;
	$atorg1=$f4['aporte_org']*0.50;
	$total_at1=$atpdss1+$atorg1;
	echo number_format($total_at1,2);
	?></td>
    <td align="right" ><? @$pp_1=($total_at1/$total_at)*100; echo number_format(@$pp_1,2);?></td>
  </tr>
  <tr>
    <td nowrap valign="bottom"><strong>Segundo</strong></td>
    <td align="right"><? echo number_format($f4['aporte_pdss']*0.30);?></td>
    <td align="right" >30.00</td>
    <td align="right"><? echo number_format($f4['aporte_org']*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$atpdss2=$f4['aporte_pdss']*0.30;
	$atorg2=$f4['aporte_org']*0.50;
	$total_at2=$atpdss2+$atorg2;
	echo number_format($total_at2,2);
	?></td>
    <td align="right"><? @$pp_2=($total_at2/$total_at)*100; echo number_format(@$pp_2,2);?></td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap><strong>TOTAL DESEMBOLSADO </strong></td>
    <td align="right"><? echo number_format($f4['aporte_pdss'],2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($f4['aporte_org'],2);?></td>
    <td align="right">100.00</td>
    <td align="right"><?  echo number_format($f4['aporte_pdss']+$f4['aporte_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap>SALDO POR DESEMBOLSAR </td>
    <td align="right">0.00</td>
    <td align="right">-</td>
    <td align="right">0.00</td>
    <td align="right">-</td>
    <td align="right">0.00</td>
    <td align="right">-</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR>
    <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?php 
}

?>
<!--  CASO 2: MRN -->
<?php 
$nb=$na;
$sql="SELECT
org_ficha_organizacion.nombre,
pit_bd_ficha_mrn.lema,
pit_bd_ficha_mrn.mes,
pit_bd_ficha_mrn.f_termino,
pit_bd_ficha_mrn.cif_pdss,
pit_bd_ficha_mrn.at_pdss,
pit_bd_ficha_mrn.vg_pdss,
pit_bd_ficha_mrn.ag_pdss,
pit_bd_ficha_mrn.at_org,
pit_bd_ficha_mrn.vg_org,
presidente.nombre AS nombre1,
presidente.paterno AS paterno1,
presidente.materno AS materno1,
tesorero.nombre AS nombre2,
tesorero.paterno AS paterno2,
tesorero.materno AS materno2
FROM
clar_atf_mrn_sd
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn_sd.cod_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_atf_mrn_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
$nb++
?>
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $nb;?></u></td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Gestión de Recursos Naturales <br> SEGUNDO DESEMBOLSO</td>
  </tr>
  <tr class="txt_titulo centrado">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT :</strong> <? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PGRN :</strong> <? echo $f5['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Lema del PGRN :</strong> <? echo $f5['lema'];?></td>
  </tr>
   <tr>
  <td><strong>Referencia :</strong>CONTRATO <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr> 
  <tr>
    <td><strong>Fecha de término :</strong> <? echo traducefecha($f5['f_termino']);?></td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>

<?php 
//Totales

$total_at=$f5['at_pdss']+$f5['at_org'];
$total_vg=$f5['vg_pdss']+$f5['vg_org'];

$total_pdss=$f5['cif_pdss']+$f5['at_pdss']+$f5['vg_pdss']+$f5['ag_pdss'];
$total_org=$f5['at_org']+$f5['vg_org'];

$total_mrn=$total_pdss+$total_org;
?>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="214" align="center" valign="middle" nowrap><strong>CONCEPTO</strong></td>
    <td width="54" align="center" valign="middle" nowrap><strong>Aporte<BR>  SIERRA SUR II</strong></td>
    <td width="22" align="center" valign="middle" nowrap>%</td>
    <td width="30" align="center" valign="middle" nowrap><strong>Aporte<BR>  SOCIOS</strong></td>
    <td width="23" align="center" valign="middle" nowrap>%</td>
    <td width="26" align="center" valign="middle" nowrap><strong>TOTAL</strong></td>
    <td width="25" align="center" valign="middle" nowrap><p align="center"><strong>%</strong></p></td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">I.- Premios para Concursos Inter  Familiares - CIF</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f5['cif_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap>0.00</td>
    <td width="23" align="right" valign="bottom" nowrap>0.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f5['cif_pdss'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">II.- Asistencia Técnica de  campesino a campesino</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f5['at_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format($f5['at_pdss']/$total_at*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($f5['at_org'],2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format($f5['at_org']/$total_at*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f5['at_pdss']+$f5['at_org'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">III.- Visitas Guiadas</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f5['vg_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format($f5['vg_pdss']/$total_vg*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($f5['vg_org'],2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format($f5['vg_org']/$total_vg*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f5['vg_pdss']+$f5['vg_org'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">IV.- Apoyo a la Gestión del PGRN</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f5['ag_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap>0.00</td>
    <td width="23" align="right" valign="bottom" nowrap>0.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f5['ag_pdss'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_pdss,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format($total_pdss/$total_mrn*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_org,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format($total_org/$total_mrn*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_mrn,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td colspan="7" align="center" valign="bottom" nowrap><strong>N ° Desembolso PGRN</strong></td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">Primero CH/ o C/O N° </td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_pdss*0.70,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>70.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_org*0.50,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>50.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_pdss*0.70+$total_org*0.50,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>&nbsp;</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">Segundo CH/ o C/O N° </td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_pdss*0.30,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>30.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_org*0.50,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>50.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_pdss*0.30+$total_org*0.50,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>&nbsp;</td>
  </tr>
  <tr>
    <td width="214" align="center" valign="bottom" nowrap><strong>TOTAL DESEMBOLSADO </strong></td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_pdss,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_org,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>100.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_mrn,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap>SALDO POR DESEMBOLSAR </td>
    <td width="54" align="right" valign="bottom" nowrap>0.00</td>
    <td width="22" align="right" valign="bottom" nowrap>-</td>
    <td width="30" align="right" valign="bottom" nowrap>0.00</td>
    <td width="23" align="right" valign="bottom" nowrap>-</td>
    <td width="26" align="right" valign="bottom" nowrap>0.00</td>
    <td width="25" align="right" valign="bottom" nowrap>-</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR>
        <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?php 
}
?>



<?
$nc=$nb;

$sql="SELECT org_ficha_organizacion.nombre, 
  pit_bd_ficha_pdn.denominacion, 
  pit_bd_ficha_pdn.total_apoyo, 
  pit_bd_ficha_pdn.at_pdss, 
  pit_bd_ficha_pdn.vg_pdss, 
  pit_bd_ficha_pdn.fer_pdss, 
  pit_bd_ficha_pdn.at_org, 
  pit_bd_ficha_pdn.vg_org, 
  pit_bd_ficha_pdn.fer_org, 
  pit_bd_ficha_pdn.f_presentacion, 
  presidente.nombre AS nombre11, 
  presidente.paterno, 
  presidente.materno, 
  tesorero.nombre AS nombre1, 
  tesorero.paterno AS paterno1, 
  tesorero.materno AS materno1, 
  pit_bd_ficha_pdn.mes, 
  pit_bd_ficha_pdn.f_termino, 
  pit_bd_ficha_pit.f_contrato
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
clar_atf_pdn.cod_relacionador='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f10=mysql_fetch_array($result))
{
	$nc++
?>
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $nc;?></u></td>
  </tr>
  <tr class="centrado txt_titulo">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Negocio<br>SEGUNDO DESEMBOLSO </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT :</strong> <? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PDN :</strong><? echo $f10['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre del Plan de Negocio :</strong> <? echo $f10['denominacion'];?></td>
  </tr>
   <tr>
  <td><strong>Referencia :</strong>CONTRATO <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr> 
  <tr>
    <td><strong>Fecha de término :</strong><? echo traducefecha($f10['f_termino']);?></td>
  </tr>

  <tr>
    <td><hr></td>
  </tr>
</table>
<?
$total_pdn_pdss=$f10['total_apoyo']+$f10['at_pdss']+$f10['vg_pdss']+$f10['fer_pdss'];

$total_pdn_org =$f10['at_org']+$f10['vg_org']+$f10['fer_org'];

$total_pdn_1=$total_pdn_pdss+$total_pdn_org;


?>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo centrado">
    <td>CONCEPTO</td>
    <td>Aporte<br>
    SIERRA SUR  II</td>
    <td>%</td>
    <td>Aporte<br>
      SOCIOS</td>
    <td>%</td>
    <td>TOTAL</td>
    <td>%</td>
  </tr>
  <tr>
    <td>I.- Asistencia  Técnica</td>
    <td align="right"><? echo number_format($f10['at_pdss'],2);?></td>
    <td align="right"><? echo number_format(($f10['at_pdss']/($f10['at_pdss']+$f10['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($f10['at_org'],2);?></td>
    <td align="right"><? echo number_format(($f10['at_org']/($f10['at_pdss']+$f10['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($f10['at_pdss']+$f10['at_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>II.- Visita  Guiada </td>
    <td align="right"><? echo number_format($f10['vg_pdss'],2);?></td>
    <td align="right"><? @$ppvisita=($f10['vg_pdss']/($f10['vg_pdss']+$f10['vg_org']))*100; echo number_format(@$ppvisita,2);?></td>
    <td align="right"><? echo number_format($f10['vg_org'],2);?></td>
    <td align="right">
	<? 
	@$ppvis1=($f10['vg_org']/($f10['vg_pdss']+$f10['vg_org']))*100; echo number_format(@$ppvis1,2);
	?></td>
    <td align="right"><? echo number_format($f10['vg_pdss']+$f10['vg_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>III.- Participación  en Ferias</td>
    <td align="right"><? echo number_format($f10['fer_pdss'],2);?></td>
    <td align="right"><? @$ppfer=($f10['fer_pdss']/($f10['fer_pdss']+$f10['fer_org']))*100; echo number_format(@$ppfer,2);?></td>
    <td align="right"><? echo number_format($f10['fer_org'],2);?></td>
    <td align="right"><? @$ppfer1=$f10['fer_org']/($f10['fer_pdss']+$f10['fer_org'])*100; echo number_format(@$ppfer1,2);?></td>
    <td align="right"><? echo number_format($f10['fer_pdss']+$f10['fer_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>IV.- Apoyo a  la Gestión del PDN</td>
    <td align="right"><? echo number_format($f10['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right"><? echo number_format($f10['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>V.- Inversiones  en Activos</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($total_pdn_pdss,2);?></td>
    <td align="right"><? echo number_format(($total_pdn_pdss/$total_pdn_1)*100,2);?></td>
    <td align="right"><? echo number_format($total_pdn_org,2);?></td>
    <td align="right"><? echo number_format(($total_pdn_org/$total_pdn_1)*100,2);?></td>
    <td align="right"><? echo number_format($total_pdn_1,2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td colspan="7" align="center"><strong>N °  Desembolso PDN</strong></td>
  </tr>
  <tr>
    <td>Primero  CH/ o C/O N° </td>
    <td align="right"><? echo number_format($total_pdn_pdss*0.70,2);?></td>
    <td align="right">70.00</td>
    <td align="right"><? echo number_format($total_pdn_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right">
	<?
	$pdnpdss1=$total_pdn_pdss*0.70;
	$pdnorg1=$total_pdn_org*0.50;
	$total_pdn1=$pdnpdss1+$pdnorg1;
	echo number_format($total_pdn1,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn1/$total_pdn_1)*100,2);?></td>
  </tr>
  <tr>
    <td>Segundo  CH/ o C/O N° </td>
    <td align="right"><? echo number_format($total_pdn_pdss*0.30,2);?></td>
    <td align="right">30.00</td>
    <td align="right"><? echo number_format($total_pdn_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$pdnpdss2=$total_pdn_pdss*0.30;
	$pdnorg2=$total_pdn_org*0.50;
	$total_pdn2=$pdnpdss2+$pdnorg2;
	echo number_format($total_pdn2,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn2/$total_pdn_1)*100,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL DESEMBOLSADO </strong></td>
    <td align="right"><? echo number_format($total_pdn_pdss,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total_pdn_org,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total_pdn_1,2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td align="center">SALDO POR DESEMBOLSAR </td>
    <td align="right">0.00</td>
    <td align="right">-</td>
    <td align="right">0.00</td>
    <td align="right">-</td>
    <td align="right">0.00</td>
    <td align="right">-</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR>
        <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<?
}
?>


<!-- ATF PARA PLANES DE INVERSION TERRITORIAL -->
<?php 
$sql="SELECT
clar_atf_pit_sd.n_atf,
sys_bd_componente_poa.codigo AS componente,
sys_bd_subactividad_poa.codigo AS poa,
sys_bd_categoria_poa.codigo AS categoria,
clar_atf_pit_sd.monto_desembolsado,
clar_atf_pit_sd.saldo,
org_ficha_taz.nombre,
sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo,
pit_bd_ficha_pit.fuente_fida,
pit_bd_ficha_pit.fuente_ro,
pit_bd_ficha_pit.n_voucher_2,
pit_bd_ficha_pit.monto_organizacion_2,
pit_bd_ficha_pit.n_cuenta,
sys_bd_ifi.descripcion AS ifi,
pit_bd_ficha_pit.aporte_org,
pit_bd_ficha_pit.monto_organizacion
FROM
clar_atf_pit_sd
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pit_sd.cod_componente
INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit_sd.cod_poa
INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit_sd.cod_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_tipo_iniciativa ON pit_bd_ficha_pit.cod_tipo_iniciativa = sys_bd_tipo_iniciativa.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON pit_bd_ficha_pit.cod_ifi = sys_bd_ifi.cod_ifi
WHERE
clar_atf_pit_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
?>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">
<strong><u>SEGUNDO DESEMBOLSO</u></strong>
<br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <?php  echo numeracion($f6['n_atf']);?> – PIT – <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?><br> 
PARA EL ANIMADOR TERRITORIAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <?php  echo number_format($f6['monto_desembolsado'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">
  En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle
</div>
<br>




	<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
		<tr>
			<td width="35%" class="txt_titulo">Organización PIT</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $row['nombre'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Organización a transferir</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><?php  echo $f6['nombre'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $f6['tipo_org'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Referencia</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?>
				- PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de desembolso</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">SEGUNDO  DESEMBOLSO</td>
		</tr>
		<tr>
			<td class="txt_titulo">Entidad financiera</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><?php  echo $f6['ifi'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de cuenta bancaria</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><?php  echo $f6['n_cuenta'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Fuente de Financiamiento</td>
			<td align="center" class="txt_titulo">:</td>
			<td width="30%">FIDA: <?php  echo number_format($row['fte_fida']);?></td>
			<td width="31%">RO:   <?php  echo number_format($row['fte_ro']);?></td>
		</tr>
	</table>
	<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>



<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="43%" align="center">ACTIVIDADES</td>
    <td width="6%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>

  <tr>
    <td>Segundo desembolso</td>
    <td class="centrado">30.00 </td>
    <td align="right" class="txt_titulo"><?php  echo number_format($f6['monto_desembolsado'],2);?></td>
    <td align="center"><?php  echo $f6['poa'];?></td>
    <td align="center"><?php  echo $f6['categoria'];?></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?php  echo number_format($f6['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<br>

<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><?php  echo $f6['n_voucher_2'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. <?php  echo number_format($f6['monto_organizacion_2'],2);?></strong></td>
  </tr>
  <?php 
  $saldo_organizacion_pit=$f6['aporte_org']-($f6['monto_organizacion']+$f6['monto_organizacion_2']);
  
  if ($saldo_organizacion_pit<0)
  {
	  $saldo_organizacion_pit=0;
  }
  else
  {
	  $saldo_organizacion_pit=$saldo_organizacion_pit;
  }
  
  @$p_org_pit=$saldo_organizacion/$f6['monto_organizacion_2']*100;
  
  
  ?>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. <?php  echo number_format($saldo_organizacion_pit,2);?></strong></td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right"><?php  echo number_format(@$p_org_pit,2);?></td>
  </tr>
</table>


<br>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia del voucher de Deposito del Aporte de La Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Informe de avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de aprobacion del informe economico y avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Relacion de familias actualizada</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_desembolso']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>


<!-- Ficha de ATF para Planes de Negocios -->
<?php 
$sql="SELECT clar_atf_pdn.n_atf, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	cat1.codigo AS categoria1, 
	poa2.codigo AS poa2, 
	cat2.codigo AS categoria2, 
	poa3.codigo AS poa3, 
	cat3.codigo AS categoria3, 
	poa4.codigo AS poa4, 
	cat4.codigo AS categoria4, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pdn.fuente_fida, 
	pit_bd_ficha_pdn.fuente_ro, 
	pit_bd_ficha_pdn.n_voucher_2, 
	pit_bd_ficha_pdn.monto_organizacion_2, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_org
FROM clar_atf_pdn INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_categoria_poa cat1 ON cat1.cod = poa1.cod_categoria_poa
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_categoria_poa cat2 ON cat2.cod = poa2.cod_categoria_poa
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_categoria_poa cat3 ON cat3.cod = poa3.cod_categoria_poa
	 INNER JOIN sys_bd_subactividad_poa poa4 ON poa4.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN sys_bd_categoria_poa cat4 ON cat4.cod = poa4.cod_categoria_poa
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN sys_bd_linea_pdn ON pit_bd_ficha_pdn.cod_linea_pdn = sys_bd_linea_pdn.cod_linea_pdn
	 INNER JOIN sys_bd_tipo_iniciativa ON pit_bd_ficha_pdn.cod_tipo_iniciativa = sys_bd_tipo_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
clar_atf_pdn.cod_relacionador='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>

<? include("encabezado.php");?>

<?php  
$monto_pdn=$f7['monto_1']+$f7['monto_2']+$f7['monto_3']+$f7['monto_4'];
$monto_org_pdn=$f7['at_org']+$f7['vg_org']+$f7['fer_org'];
$saldo_total_pdn=$monto_org_pdn-($f7['monto_organizacion']+$f7['monto_organizacion_2']);
if ($saldo_total_pdn<0)
{
$saldo_total_pdn=0;
}
else
{
$saldo_total_pdn=$saldo_total_pdn;
}



@$pp_org_pdn=$saldo_total_pdn/$f7['monto_organizacion_2']*100;

?>

<div class="capa txt_titulo" align="center">
<strong><u>SEGUNDO DESEMBOLSO</u></strong>
<br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <?php  echo numeracion($f7['n_atf']);?> – PIT – <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?><BR>
PARA EL COFINANCIAMIENTO DEL PLAN DE NEGOCIO</div>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <?php  echo number_format($monto_pdn,2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Organización PIT </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización a transferir </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><?php  echo $f7['nombre'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $f7['codigo'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Denominación del PDN </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><?php  echo $f7['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Linea de Negocio </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><?php echo $f7['linea'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">SEGUNDO DESEMBOLSO </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><?php  echo $f7['ifi'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><?php  echo $f7['n_cuenta'];?></td>
  </tr>
  <!--
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fte_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fte_ro'],2);?></td>
  </tr>
  -->
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="45%" align="center">ACTIVIDADES</td>
    <td width="10%" align="center">% A DESEMBOLSAR </td>
    <td width="15%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="10%" align="center">CODIGO POA</td>
    <td width="10%" align="center">CATEGORIA DE GASTO</td>
    <td width="10%" align="center">FTE. FTO.</td>
  </tr>
  <tr>
    <td>Asistencia Técnica</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f7['monto_1'],2);?></td>
    <td align="center"><?php  echo $f7['poa1'];?></td>
    <td align="center"><?php  echo $f7['categoria1'];?></td>
    <td align="center"><? if ($f7['categoria1']==I) echo "R.O."; elseif($f7['categoria1']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f7['monto_2'],2);?></td>
    <td align="center"><?php  echo $f7['poa2'];?></td>
    <td align="center"><?php  echo $f7['categoria2'];?></td>
    <td align="center"><? if ($f7['categoria2']==I) echo "R.O."; elseif($f7['categoria2']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f7['monto_3'],2);?></td>
    <td align="center"><?php  echo $f7['poa3'];?></td>
    <td align="center"><?php  echo $f7['categoria3'];?></td>
    <td align="center"><? if ($f7['categoria3']==I) echo "R.O."; elseif($f7['categoria3']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f7['monto_4'],2);?></td>
    <td align="center"><?php  echo $f7['poa4'];?></td>
    <td align="center"><?php  echo $f7['categoria4'];?></td>
    <td align="center"><? if ($f7['categoria4']==I) echo "R.O."; elseif($f7['categoria4']==II) echo "FIDA";?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?php  echo number_format($monto_pdn,2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>




<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><?php  echo $f7['n_voucher_2'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. <?php  echo number_format($f7['monto_organizacion_2'],2);?></strong></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. <?php  echo number_format($saldo_total_pdn,2);?></strong></td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right"><?php  echo number_format(@$pp_org_pdn,2);?></td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia del voucher de Deposito del Aporte de La Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Informe de avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de aprobacion del informe economico y avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Relacion de familias actualizada</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_desembolso']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>








<!-- AUTORIZACION DE TRANSFERENCIA DE FONDOS PARA PLAN DE GESTIÓN DE RECURSOS NATURALES -->

<?
$sql="SELECT clar_atf_mrn_sd.n_atf, 
	clar_atf_mrn_sd.monto_1, 
	clar_atf_mrn_sd.saldo_1, 
	clar_atf_mrn_sd.monto_2, 
	clar_atf_mrn_sd.saldo_2, 
	clar_atf_mrn_sd.monto_3, 
	clar_atf_mrn_sd.saldo_3, 
	clar_atf_mrn_sd.monto_4, 
	clar_atf_mrn_sd.saldo_4, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa_1, 
	categoria1.codigo AS categoria_1, 
	poa2.codigo AS poa_2, 
	categoria2.codigo AS categoria_2, 
	poa3.codigo AS poa_3, 
	categoria3.codigo AS categoria_3, 
	poa4.codigo AS poa_4, 
	categoria4.codigo AS categoria_4, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	pit_bd_ficha_mrn.f_inicio, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.f_termino, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.n_voucher_2, 
	pit_bd_ficha_mrn.monto_organizacion_2, 
	pit_bd_ficha_mrn.at_org, 
	pit_bd_ficha_mrn.vg_org, 
	pit_bd_ficha_mrn.monto_organizacion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_org.descripcion AS tipo_org
FROM sys_bd_componente_poa INNER JOIN clar_atf_mrn_sd ON sys_bd_componente_poa.cod = clar_atf_mrn_sd.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_mrn_sd.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_mrn_sd.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_mrn_sd.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa poa4 ON poa4.cod = clar_atf_mrn_sd.cod_poa_4
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn_sd.cod_mrn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_categoria_poa categoria4 ON categoria4.cod = poa4.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria3 ON categoria3.cod = poa3.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria2 ON categoria2.cod = poa2.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria1 ON categoria1.cod = poa1.cod_categoria_poa
WHERE clar_atf_mrn_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r11=mysql_fetch_array($result))
{
?>
<? include("encabezado.php");?>

<?php  

$monto_mrn=$r11['monto_1']+$r11['monto_2']+$r11['monto_3']+$r11['monto_4'];
$monto_org_mrn=$r11['at_org']+$r11['vg_org'];
$saldo_total_mrn=$monto_org_mrn-($r11['monto_organizacion']+$r11['monto_organizacion_2']);
if ($saldo_total_mrn<0)
{
$saldo_total_mrn=0;
}
else
{
$saldo_total_mrn=$saldo_total_mrn;
}

@$pp_org_mrn=$saldo_total_mrn/$r11['monto_organizacion_2']*100;

?>

<div class="capa txt_titulo" align="center">
<u>SEGUNDO DESEMBOLSO</u><br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r11['n_atf']);?> – PIT – <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?><BR>
  PARA EL COFINANCIAMIENTO DEL PLAN DE GESTION DE RECURSOS NATURALES</div>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($monto_mrn,2);?></td>
  </tr>
</table>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>






<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Organización PIT</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización a transferir</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['organizacion'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $r11['tipo_org'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Lema del PGRN</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['lema'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">SEGUNDO DESEMBOLSO</td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de financiamiento</td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fte_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fte_ro'],2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="41%" align="center">ACTIVIDADES</td>
    <td width="8%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Premio para Concursos Inter Familiares</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($r11['monto_1'],2);?></td>
    <td align="center"><? echo $r11['poa_1'];?></td>
    <td align="center"><? echo $r11['categoria_1'];?></td>
  </tr>
  <tr>
    <td>Asistencia Técnica (de campesino a campesino)</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($r11['monto_2'],2);?></td>
    <td align="center"><? echo $r11['poa_2'];?></td>
    <td align="center"><? echo $r11['categoria_2'];?></td>
  </tr>
  <tr>
    <td>Visitas guiada</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($r11['monto_3'],2);?></td>
    <td align="center"><? echo $r11['poa_3'];?></td>
    <td align="center"><? echo $r11['categoria_3'];?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PGRN</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($r11['monto_4'],2);?></td>
    <td align="center"><? echo $r11['poa_4'];?></td>
    <td align="center"><? echo $r11['categoria_4'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($monto_mrn,2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<br>
<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><? echo $r11['n_voucher_2'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($r11['monto_organizacion_2'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? echo number_format($saldo_total_mrn,2);?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
<? echo number_format(@$pp_saldo_mrn,2);?>
	</td>
  </tr>
</table>





<br>
<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>




<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia del voucher de Deposito del Aporte de La Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Informe de avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de aprobacion del informe economico y avance</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Relacion de familias actualizada</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_desembolso']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<?
}
?>
<!-- FIN DE ATF DE PLAN DE GESTIÓN DE RECURSOS NATURALES -->



















<!-- Generamos los recibos de recepcion de cheque -->
<?php 
$sql="SELECT
pit_bd_ficha_pit.n_cuenta,
org_ficha_taz.nombre AS org,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
sys_bd_ifi.descripcion AS banco,
org_ficha_taz.n_documento,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_directiva_taz.n_documento AS dni,
org_ficha_directiva_taz.nombre,
org_ficha_directiva_taz.paterno,
org_ficha_directiva_taz.materno,
clar_atf_pit_sd.n_atf,
clar_atf_pit_sd.monto_desembolsado
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.n_documento = org_ficha_taz.presidente AND org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
INNER JOIN clar_atf_pit_sd ON clar_atf_pit_sd.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE
clar_atf_pit_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php echo $fila1['org'];?> con <?php  echo $fila1['tipo_doc'];?> N° <?php  echo $fila1['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila1['nombre']." ".$fila1['paterno']." ".$fila1['materno'];?>, identificado con DNI N° <?php  echo $fila1['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_desembolso']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila1['monto_desembolsado'],2);?>(<?php  echo vuelveletra($fila1['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>, Relacionado con el <b>Segundo Desembolso</b>, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>. 
</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_desembolso']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>

<!--  Aqui ponemos los planes de negocio -->
<?php 
$sql="SELECT pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	sys_bd_tipo_iniciativa.descripcion AS tipo_ini, 
	org_ficha_organizacion.nombre AS org, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	clar_atf_pdn.n_atf, 
	(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado
FROM pit_bd_ficha_pdn INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
clar_atf_pdn.cod_relacionador='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $fila2['org'];?> con <?php  echo $fila2['tipo_doc'];?> N° <?php  echo $fila2['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila2['nombre']." ".$fila2['paterno']." ".$fila2['materno'];?>, identificado con DNI N° <?php  echo $fila2['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_desembolso']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila2['monto_desembolsado'],2);?> (<?php  echo vuelveletra($fila2['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>
, Relacionado con el <b>Segundo Desembolso</b>, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_desembolso']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>


<!--  Aca va el PGRN -->
<?php 
$sql="SELECT
org_ficha_organizacion.nombre AS org,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
org_ficha_organizacion.n_documento,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_usuario.n_documento AS dni,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
clar_atf_mrn_sd.n_atf,
(clar_atf_mrn_sd.monto_1+
clar_atf_mrn_sd.monto_2+
clar_atf_mrn_sd.monto_3+
clar_atf_mrn_sd.monto_4) AS monto_desembolsado
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN clar_atf_mrn_sd ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn_sd.cod_mrn
WHERE
clar_atf_mrn_sd.cod_ficha_sd='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $fila3['org'];?> con <?php  echo $fila3['tipo_doc'];?> N° <?php  echo $fila3['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila3['nombre']." ".$fila3['paterno']." ".$fila3['materno'];?>, identificado con DNI N° <?php  echo $fila3['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_desembolso']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila3['monto_desembolsado'],2);?> (<?php  echo vuelveletra($fila3['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>, Relacionado con el <b>Segundo Desembolso</b>, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_desembolso']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>





<br>


<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>
</body>
</html>