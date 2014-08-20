<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT clar_bd_ficha_sd_idl.f_desembolso, 
	clar_bd_ficha_sd_idl.n_solicitud, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	clar_atf_idl.n_atf, 
	sys_bd_componente_poa.codigo AS codigo_componente, 
	sys_bd_componente_poa.nombre AS nombre_componente, 
	sys_bd_subactividad_poa.codigo AS codigo_poa, 
	sys_bd_subactividad_poa.nombre AS nombre_poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	clar_atf_idl.monto_desembolsado, 
	clar_atf_idl.saldo, 
	clar_bd_acta.n_acta, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_tipo_idl.descripcion AS tipo_idl, 
	pit_bd_ficha_idl.aporte_pdss, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_personal.n_documento AS dni_jefe,
		clar_bd_evento_clar.f_evento
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_sd_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_sd_idl.cod_idl
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_sd_idl.cod_clar
	 LEFT JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN clar_atf_idl ON clar_atf_idl.cod_ficha_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_idl.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_idl.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE clar_bd_ficha_sd_idl.cod_ficha_sd='$cod' AND
clar_atf_idl.tipo_atf=2";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

@$pp=$row['monto_desembolsado']/$row['aporte_pdss']*100;
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
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?><BR>
A "LA MUNICIPALIDAD" PARA LA EJECUCIÓN DE LA INVERSION DE DESARROLLO LOCAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['monto_desembolsado'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">&quot;LA MUNICIPALIDAD&quot;</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Acta de Sesión de CLAR de fecha </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2">Acta N° <? echo numeracion($row['n_acta']);?> del <? echo traducefecha($row['f_evento']);?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Referencia</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - IDL – OL <? echo $row['oficina'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Denominación de la IDL </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Tipo de IDL </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['tipo_idl'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° Desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">SEGUNDO DESEMBOLSO </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° Cuenta Bancaria </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format(0,2);?></td>
    <td width="31%">RO: <? echo number_format(100,2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="42%" align="center">ACTIVIDADES</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>COFINANCIAMIENTO DE IDL - SEGUNDO DESEMBOLSO</td>
    <td class="derecha"><?php  echo number_format(@$pp,2);?></td>
    <td align="right"><? echo number_format($row['monto_desembolsado'],2);?></td>
    <td align="center"><? echo $row['codigo_poa'];?></td>
    <td align="center"><? echo $row['categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>


<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($row['saldo'],2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right">0.00</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud para acceder al segundo desembolso - IDL, presentado por la <? echo $row['nombre'];?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>

  <tr>
    <td>Informe de avance de ejecución con cargo al primer desembolso</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>

  <tr>
    <td>Informe de pertinencia del Jefe de la Oficina Local para acceder al segundo desembolso</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>

  <tr>
    <td>Copia del acta del CLAR</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>

   <tr>
    <td>Copia del contrato de donación con cargo</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr> 
</table>
<BR>
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
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?></u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="23%" class="txt_titulo">A</td>
    <td width="1%">:</td>
    <td width="76%">C.P.C JUAN SALAS ACOSTA </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="76%">ADMINISTRADOR DEL NEC PDSS II </td>
  </tr>
  <tr>
    <td class="txt_titulo">CC</td>
    <td width="1%">:</td>
    <td width="76%">Responsable de Componentes </td>
  </tr>
  <tr>
    <td class="txt_titulo">ASUNTO</td>
    <td width="1%">:</td>
    <td width="76%">Segundo Desembolso  de Iniciativa de Inversión de Desarrollo Local</td>
  </tr>
  <tr>
    <td><? echo $org;?></td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - IDL – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_desembolso']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="19%">Nombre de LA MUNICIPALIDAD </td>
    <td width="20%">Nombre de la IDL </td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['nombre'];?></td>
    <td><? echo $row['denominacion'];?></td>
    <td class="centrado">IDL</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_desembolso']);?></td>
    <td><? echo $row['banco'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['monto_desembolsado'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="6">TOTAL</td>
    <td class="derecha"><? echo number_format($row['monto_desembolsado'],2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente la autorización de transferencia de fondos. </td>
  </tr>
  <tr>
    <td><br>
    Atentamente,</td>
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

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</td>
  </tr>
</table>


</body>
</html>