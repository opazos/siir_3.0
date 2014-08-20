<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT sys_bd_dependencia.nombre AS oficina, 
  sys_bd_personal.n_documento AS dni, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  mh_bd_desembolso.n_solicitud, 
  mh_bd_desembolso.f_solicitud, 
  sys_bd_subactividad_poa.codigo, 
  sys_bd_subactividad_poa.nombre AS poa, 
  sys_bd_categoria_poa.codigo AS categoria, 
  sys_bd_componente_poa.codigo AS componente, 
  mh_bd_desembolso.fte_fto, 
  mh_bd_desembolso.cod_tipo_incentivo
FROM sys_bd_dependencia INNER JOIN mh_bd_desembolso ON sys_bd_dependencia.cod_dependencia = mh_bd_desembolso.cod_dependencia
   INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = mh_bd_desembolso.cod_poa
   INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
   INNER JOIN sys_bd_actividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
   INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
   INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE mh_bd_desembolso.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($row['cod_tipo_incentivo']==1) 
{
  $incentivo="INCENTIVO AL DEPOSITO DE APERTURA";
}
elseif($row['cod_tipo_incentivo']==2)
{
  $incentivo="INCENTIVO AL MANTENIMIENTO Y CRECIMIENTO";
}
elseif($row['cod_tipo_incentivo']==3)
{
  $incentivo="INCENTIVO AL RETIRO";
}

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
<!-- Inicio contenido -->
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%"><? echo $incentivo;?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_solicitud']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al Subcomponente de apoyo a la intermediación financiera por el <? echo $incentivo;?> de la cuenta de ahorro de las siguientes organizaciones:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
  <td>N. de documento</td>
  <td>Nombre de la organización</td>
  <td>ATF N.</td>
  <td>Institución Financiera</td>
  <td>Número de ahorristas</td>
  <td>Monto a Transferir(S/.)</td>
  </tr>
<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	mh_bd_grupo.n_atf, 
	sys_bd_ifi.descripcion AS ifi, 
	mh_bd_grupo.n_ahorristas, 
	mh_bd_grupo.monto
FROM org_ficha_organizacion INNER JOIN mh_bd_grupo ON org_ficha_organizacion.cod_tipo_doc = mh_bd_grupo.cod_tipo_doc AND org_ficha_organizacion.n_documento = mh_bd_grupo.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = mh_bd_grupo.cod_ifi
WHERE mh_bd_grupo.cod_desembolso='$cod'
ORDER BY mh_bd_grupo.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$mujer=$f1['n_ahorristas'];
	$total_mujer=$total_mujer+$mujer;

	$monto=$f1['monto'];
	$total_monto=$total_monto+$monto;
?>
  <tr>
  	<td class="centrado"><? echo $f1['n_documento'];?></td>
  	<td><? echo $f1['nombre'];?></td>
  	<td class="centrado"><? echo numeracion($f1['n_atf']);?></td>
  	<td><? echo $f1['ifi'];?></td>
  	<td class="centrado"><? echo number_format($f1['n_ahorristas']);?></td>
  	<td class="derecha"><? echo number_format($f1['monto'],2);?></td>
  </tr>
<?
}
?>
 	<tr class="txt_titulo">
		<td colspan="4" class="centrado">TOTAL</td>
		<td class="centrado"><? echo number_format($total_mujer);?></td>
		<td class="derecha"><? echo number_format($total_monto,2);?></td>	
	</tr>
</table>

<div class="capa">
	<p>Se adjunta a la presente las autorizaciones de transferencia de fondos de cada una de las organizaciones.</p>
	<p>Atentamente,</p>
</div>

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

<?
	$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_ifi.descripcion AS ifi, 
	mh_bd_grupo.monto, 
	mh_bd_grupo.n_atf
FROM org_ficha_organizacion INNER JOIN mh_bd_grupo ON org_ficha_organizacion.cod_tipo_doc = mh_bd_grupo.cod_tipo_doc AND org_ficha_organizacion.n_documento = mh_bd_grupo.n_documento
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = mh_bd_grupo.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE mh_bd_grupo.cod_desembolso='$cod'
ORDER BY mh_bd_grupo.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($f2['n_atf']);?> –  <? echo periodo($row['f_solicitud']);?> - <? echo $row['oficina'];?><BR>
A "<? echo $f2['nombre'];?>" PARA EL APOYO A LA INTERMEDIACION FINANCIERA RURAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($f2['monto'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Nombre de la organización</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f2['nombre'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Tipo de organización</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f2['tipo_org'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Número de <? echo $f2['tipo_doc'];?></td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f2['n_documento'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Código POA</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['codigo']." - ".$row['poa'];?></td>
  </tr>  

  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f2['ifi'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Categoria de gasto</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['categoria'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? if ($row['fte_fto']==1) echo "FIDA : 100% :: RO : 0%"; elseif($row['fte_fto']==2) echo "FIDA : 0% :: RO : 100%"; elseif($row['fte_fto']==3) echo "FIDA : 50% :: RO : 50%"; else echo "100% DONACION FIDA";?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="82%"><? echo $incentivo;?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" class="derecha"><? echo number_format($f2['monto'],2);?></td>
  </tr>
</table>
<p>&nbsp;</p>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<?
if($row['cod_tipo_incentivo']==1)
{
?>
  <tr>
    <td width="82%">Solicitud de la organización para participar en el programa de ahorro.</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo para trabajar con Sierra Sur II en el programa de ahorro</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>  
  <tr>
    <td>Copia de voucher de depósito de apertura</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNI de las ahorristas</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>    
<?
}
elseif($row['cod_tipo_incentivo']==2)
{
?>
  <tr>
    <td>Reporte de la IFI de cálculo de incentivos al mantenimiento y crecimiento de las cuentas de ahorro, revisados por la Oficina Local.</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
<?  
}
elseif($row['cod_tipo_incentivo']==3)
{
?>
  <tr>
    <td>Reporte de la IFI de cálculo de incentivos al retiro de las cuentas de ahorro, revisados por la Oficina Local.</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
<?
}
?>
</table>
<p>&nbsp;</p>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_solicitud']);?></div>
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
<?
}
?>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    </td>
  </tr>
</table>
<!-- Fin contenido -->
</body>
</html>
