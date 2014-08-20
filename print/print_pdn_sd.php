<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT clar_bd_ficha_sd_pdn.f_desembolso, 
  clar_bd_ficha_sd_pdn.n_solicitud, 
  pit_bd_ficha_pdn.denominacion, 
  pit_bd_ficha_pdn.n_contrato, 
  pit_bd_ficha_pdn.f_contrato, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  clar_atf_pdn.n_atf, 
  (clar_atf_pdn.monto_1+ 
  clar_atf_pdn.monto_2+ 
  clar_atf_pdn.monto_3+ 
  clar_atf_pdn.monto_4) AS desembolso, 
  sys_bd_ifi.descripcion AS ifi, 
  pit_bd_ficha_pdn.n_cuenta, 
  olp.nombre AS oficina, 
  sys_bd_linea_pdn.descripcion AS linea, 
  clar_atf_pdn.monto_1, 
  clar_atf_pdn.monto_2, 
  clar_atf_pdn.monto_3, 
  clar_atf_pdn.monto_4, 
  sys_bd_componente_poa.codigo AS codigo_componente, 
  poa1.codigo AS poa1, 
  categoria1.codigo AS categoria1, 
  poa2.codigo AS poa2, 
  categoria2.codigo AS categoria2, 
  poa3.codigo AS poa3, 
  categoria3.codigo AS categoria3, 
  poa4.codigo AS poa4, 
  categoria4.codigo AS categoria4, 
  pit_bd_ficha_pdn.n_voucher_2, 
  pit_bd_ficha_pdn.monto_organizacion_2, 
  pit_bd_ficha_pdn.monto_organizacion, 
  (pit_bd_ficha_pdn.at_org+ 
  pit_bd_ficha_pdn.vg_org+ 
  pit_bd_ficha_pdn.fer_org) AS monto_organizacion_total, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  pit_bd_ficha_pdn.total_apoyo, 
  pit_bd_ficha_pdn.at_pdss, 
  pit_bd_ficha_pdn.vg_pdss, 
  pit_bd_ficha_pdn.fer_pdss, 
  pit_bd_ficha_pdn.at_org, 
  pit_bd_ficha_pdn.vg_org, 
  pit_bd_ficha_pdn.fer_org, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  presidente.nombre AS nombre1, 
  presidente.paterno AS paterno1, 
  presidente.materno AS materno1, 
  org_ficha_organizacion.presidente, 
  org_ficha_organizacion.tesorero, 
  org_ficha_usuario.nombre AS nombre2, 
  org_ficha_usuario.paterno AS paterno2, 
  org_ficha_usuario.materno AS materno2, 
  clar_bd_evento_clar.nombre AS evento, 
  sys_bd_distrito.nombre AS dist, 
  sys_bd_dependencia.nombre AS olp, 
  clar_bd_ficha_sd_pdn.fte_fida, 
  clar_bd_ficha_sd_pdn.fte_ro
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_sd_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_sd_pdn.cod_pdn
   INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_sd_pdn.cod_clar
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
   INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_bd_ficha_sd_pdn.cod_ficha_sd
   INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
   INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
   INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
   INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
   INNER JOIN sys_bd_subactividad_poa poa4 ON poa4.cod = clar_atf_pdn.cod_poa_4
   INNER JOIN sys_bd_categoria_poa categoria4 ON categoria4.cod = poa4.cod_categoria_poa
   INNER JOIN sys_bd_categoria_poa categoria3 ON categoria3.cod = poa3.cod_categoria_poa
   INNER JOIN sys_bd_categoria_poa categoria2 ON categoria2.cod = poa2.cod_categoria_poa
   INNER JOIN sys_bd_categoria_poa categoria1 ON categoria1.cod = poa1.cod_categoria_poa
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.tesorero AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN sys_bd_dependencia olp ON olp.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = olp.dni_representante
   INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
   INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
WHERE clar_bd_ficha_sd_pdn.cod_ficha_sd='$cod' AND
clar_atf_pdn.cod_tipo_atf_pdn=6";
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
    <td width="76%">Segundo Desembolso  de CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_desembolso']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
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

  <tr>
    <td><? echo $row['nombre'];?></td>
    <td class="centrado">PDN</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])." - ".periodo($row['f_desembolso']);?></td>
    <td><? echo $row['ifi'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['desembolso'],2);?></td>
  </tr>
 
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha"><?
	
	echo number_format($row['desembolso'],2);
	?></td>
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
    <td align="center"><?php  echo $row['nombres']." ".$row['apellidos'];?><BR> JEFE DE OFICINA LOCAL </td>
  </tr>
</table>

<H1 class=SaltoDePagina> </H1>
<!-- Anexo -->
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° 1</u></td>
  </tr>
  <tr class="centrado txt_titulo">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Negocio<br>SEGUNDO DESEMBOLSO </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PDN :</strong><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre del Plan de Negocio :</strong> <? echo $row['denominacion'];?></td>
  </tr>
   <tr>
  <td><strong>Referencia :</strong>CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr> 
  <tr>
    <td><strong>Plazo de ejecución :</strong>Hasta 10  meses</td>
  </tr>

  <tr>
    <td><hr></td>
  </tr>
</table>
<?
$total_pdn_pdss=$row['total_apoyo']+$row['at_pdss']+$row['vg_pdss']+$row['fer_pdss'];

$total_pdn_org =$row['at_org']+$row['vg_org']+$row['fer_org'];

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
    <td align="right"><? echo number_format($row['at_pdss'],2);?></td>
    <td align="right"><? echo number_format(($row['at_pdss']/($row['at_pdss']+$row['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($row['at_org'],2);?></td>
    <td align="right"><? echo number_format(($row['at_org']/($row['at_pdss']+$row['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($row['at_pdss']+$row['at_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>II.- Visita  Guiada </td>
    <td align="right"><? echo number_format($row['vg_pdss'],2);?></td>
    <td align="right"><? @$ppvisita=($row['vg_pdss']/($row['vg_pdss']+$row['vg_org']))*100; echo number_format(@$ppvisita,2);?></td>
    <td align="right"><? echo number_format($row['vg_org'],2);?></td>
    <td align="right">
	<? 
	@$ppvis1=($row['vg_org']/($row['vg_pdss']+$row['vg_org']))*100; echo number_format(@$ppvis1,2);
	?></td>
    <td align="right"><? echo number_format($row['vg_pdss']+$row['vg_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>III.- Participación  en Ferias</td>
    <td align="right"><? echo number_format($row['fer_pdss'],2);?></td>
    <td align="right"><? @$ppfer=($row['fer_pdss']/($row['fer_pdss']+$row['fer_org']))*100; echo number_format(@$ppfer,2);?></td>
    <td align="right"><? echo number_format($row['fer_org'],2);?></td>
    <td align="right"><? @$ppfer1=$row['fer_org']/($row['fer_pdss']+$row['fer_org'])*100; echo number_format(@$ppfer1,2);?></td>
    <td align="right"><? echo number_format($row['fer_pdss']+$row['fer_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>IV.- Apoyo a  la Gestión del PDN</td>
    <td align="right"><? echo number_format($row['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right"><? echo number_format($row['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
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
<!-- fin de anexo -->



<H1 class=SaltoDePagina></H1>

<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">
<u>SEGUNDO DESEMBOLSO</u><br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?> <BR>
PARA EL COFINANCIAMIENTO DEL PLAN DE NEGOCIO</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['desembolso'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td class="txt_titulo">Organización a transferir </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Denominación del PDN </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Linea de Negocio </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['linea'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">Segundo Desembolso </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['ifi'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fte_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fte_ro'],2);?></td>
  </tr>
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
    <td align="right"><? echo number_format($row['monto_1'],2);?></td>
    <td align="center"><? echo $row['poa1'];?></td>
    <td align="center"><? echo $row['categoria1'];?></td>
    <td align="center"><? if ($row['categoria1']==I) echo "R.O."; elseif($row['categoria1']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($row['monto_2'],2);?></td>
    <td align="center"><? echo $row['poa2'];?></td>
    <td align="center"><? echo $row['categoria2'];?></td>
    <td align="center"><? if ($row['categoria2']==I) echo "R.O."; elseif($row['categoria2']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($row['monto_3'],2);?></td>
    <td align="center"><? echo $row['poa3'];?></td>
    <td align="center"><? echo $row['categoria3'];?></td>
    <td align="center"><? if ($row['categoria3']==I) echo "R.O."; elseif($row['categoria3']==II) echo "FIDA";?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($row['monto_4'],2);?></td>
    <td align="center"><? echo $row['poa4'];?></td>
    <td align="center"><? echo $row['categoria4'];?></td>
    <td align="center"><? if ($row['categoria4']==I) echo "R.O."; elseif($row['categoria4']==II) echo "FIDA";?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?  echo number_format($row['desembolso'],2);?></td>
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
    <td width="61%" align="right"><? echo $row['n_voucher_2'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($row['monto_organizacion_2'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? 
		echo number_format(0,2);
	?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	echo number_format(0,2);
	?>
	</td>
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


<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $row['nombre'];?> con <?php  echo $row['tipo_doc'];?> N° <?php  echo $row['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?>, identificado con DNI N° <?php  echo $row['presidente'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <? echo number_format($row['desembolso'],2);?> (<?php  echo vuelveletra($row['desembolso']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $row['evento'];?> de la Oficina Local de <?php  echo $row['olp'];?>
, Relacionado con el Segundo Desembolso, realizado en el Distrito de <?php  echo $row['dist'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PDN – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $row['oficina'];?>,<?php  echo traducefecha($row['f_desembolso']);?></div>





<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
  <td>
<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
<a href="../contratos/pdn_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
  </td>
  </tr>
</table>


</body>
