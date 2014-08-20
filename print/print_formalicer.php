<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT fm_formalizacion.cod, 
	fm_formalizacion.n_contrato, 
	fm_formalizacion.f_firma, 
	fm_formalizacion.f_solicitud, 
	fm_formalizacion.n_requerimiento, 
	fm_formalizacion.monto_form, 
	fm_formalizacion.monto_otro, 
	fm_formalizacion.monto_org, 
	fm_formalizacion.justificacion, 
	fm_formalizacion.contratante, 
	fm_formalizacion.n_cuenta, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_ifi.descripcion AS banco, 
	fm_formalizacion.n_solicitud_1, 
	fm_formalizacion.n_atf_1, 
	fm_formalizacion.n_solicitud_2, 
	fm_formalizacion.n_atf_2
FROM sys_bd_dependencia INNER JOIN fm_formalizacion ON sys_bd_dependencia.cod_dependencia = fm_formalizacion.cod_dependencia
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = fm_formalizacion.cod_poa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = fm_formalizacion.cod_ifi
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_actividad_poa ON sys_bd_subactividad_poa.relacion = sys_bd_actividad_poa.cod
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE fm_formalizacion.cod='$cod'";
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


<!-- Solicitud de Desembolso -->
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud_1']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso de Servicios de Formalización de Organizaciones</td>
  </tr>
  <tr>
    <td class="txt_titulo">REQUERIMIENTO N°</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['n_requerimiento'];?> OLP <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_solicitud']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al pago por servicio de Formalizacion de las siguientes Organizaciones:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">

<thead>
	<tr>
		<th>Nº Documento</th>
		<th>Nombre de la Organización</th>
		<th>Tipo de organización</th>
		<th>Departamento</th>
		<th>Provincia</th>
		<th>Distrito</th>
		<th>Fecha de formalización</th>
	</tr>
</thead>

<tbody>
<?
$sql="SELECT fm_org_formalizada.costo, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	org_ficha_organizacion.f_formalizacion, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito
FROM org_ficha_organizacion INNER JOIN fm_org_formalizada ON org_ficha_organizacion.cod_tipo_doc = fm_org_formalizada.cod_tipo_doc AND org_ficha_organizacion.n_documento = fm_org_formalizada.n_documento_org
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE fm_org_formalizada.cod_formalizador='$cod'
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $r1['n_documento'];?></td>
		<td><? echo $r1['nombre'];?></td>
		<td class="centrado"><? echo $r1['tipo_org'];?></td>
		<td class="centrado"><? echo $r1['departamento'];?></td>
		<td class="centrado"><? echo $r1['provincia'];?></td>
		<td class="centrado"><? echo $r1['distrito'];?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_formalizacion']);?></td>
	</tr>
<?
}
?>	
</tbody>
</table>
<br/>
<div class="capa justificado">El pago de los servicios prestados se compone de los siguientes conceptos:</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<thead>
	<tr>
		<th>Nº</th>
		<th>CONCEPTO</th>
		<th>MONTO A PAGAR (S/.)</th>
	</tr>
</thead>

<tbody>
	<tr>
		<td class="centrado">1</td>
		<td>SERVICIO DE CONSTITUCION E INSCRIPCION DE PERSONERIA JURIDICA PARA ORGANIZACIONES</td>
		<td class="derecha"><? echo number_format($row['monto_form'],2);?></td>
	</tr>
	
	<tr class="txt_titulo">
		<td class="centrado" colspan="2">TOTAL</td>
		<td class="derecha"><? echo number_format($row['monto_form'],2);?></td>
	</tr>	
	
</tbody>
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
<H1 class=SaltoDePagina></H1>



<!-- Empezamos con el ATF -->
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf_1']);?> –  <? echo periodo($row['f_solicitud']);?> - <? echo $row['oficina'];?><BR>
POR CONCEPTO DE FORMALIZACION A ORGANIZACIONES</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['monto_form'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud_1']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Proveedor(a)</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['contratante'];?></td>
  </tr>

  <tr>
	<td class="txt_titulo">Referencia</td>
	<td class="txt_titulo centrado">:</td>
	<td colspan="2">
	<br/>
	- CONTRATO DE LOCACION DE SERVICIOS Nº <? echo $row['n_contrato'];?> - <? echo periodo($row['f_firma']);?> – OL <? echo $row['oficina'];?><br/>
	- REQUERIMIENTO DE SERVICIOS Nº <? echo $row['n_requerimiento'];?> - <? echo periodo($row['f_firma']);?> – OL <? echo $row['oficina'];?>
	<br/>
	
	</td>
 </tr>
  <tr>
    <td class="txt_titulo">Justificación</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['justificacion'];?></td>
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
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="42%" align="center">CONCEPTO</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td><? echo $row['justificacion'];?></td>
    <td class="derecha">100%</td>
    <td align="right"><? echo number_format($row['monto_form'],2);?></td>
    <td align="center"><? echo $row['poa'];?></td>
    <td align="center"><? echo $row['categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['monto_form'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td>-----</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  
  <tr>
    <td>-----</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  
  
</table>
<BR>
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

<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud_2']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso por concepto de gastos notariales varios</td>
  </tr>
  <tr>
    <td class="txt_titulo">REQUERIMIENTO N°</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['n_requerimiento'];?> OLP <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_solicitud']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al pago por servicio de Formalizacion de las siguientes Organizaciones:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">

<thead>
	<tr>
		<th>Nº Documento</th>
		<th>Nombre de la Organización</th>
		<th>Tipo de organización</th>
		<th>Departamento</th>
		<th>Provincia</th>
		<th>Distrito</th>
		<th>Fecha de formalización</th>
	</tr>
</thead>

<tbody>
<?
$sql="SELECT fm_org_formalizada.costo, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	org_ficha_organizacion.f_formalizacion, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito
FROM org_ficha_organizacion INNER JOIN fm_org_formalizada ON org_ficha_organizacion.cod_tipo_doc = fm_org_formalizada.cod_tipo_doc AND org_ficha_organizacion.n_documento = fm_org_formalizada.n_documento_org
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE fm_org_formalizada.cod_formalizador='$cod'
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $r1['n_documento'];?></td>
		<td><? echo $r1['nombre'];?></td>
		<td class="centrado"><? echo $r1['tipo_org'];?></td>
		<td class="centrado"><? echo $r1['departamento'];?></td>
		<td class="centrado"><? echo $r1['provincia'];?></td>
		<td class="centrado"><? echo $r1['distrito'];?></td>
		<td class="centrado"><? echo fecha_normal($r1['f_formalizacion']);?></td>
	</tr>
<?
}
?>	
</tbody>
</table>
<br/>
<div class="capa justificado">El pago de los servicios prestados se compone de los siguientes conceptos:</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<thead>
	<tr>
		<th>Nº</th>
		<th>CONCEPTO</th>
		<th>MONTO A PAGAR (S/.)</th>
	</tr>
</thead>

<tbody>
	
	<tr>
		<td class="centrado">2</td>
		<td>OTROS GASTOS VARIOS NOTARIALES</td>
		<td class="derecha"><? echo number_format($row['monto_otro'],2);?></td>
	</tr>
	
	<tr class="txt_titulo">
		<td class="centrado" colspan="2">TOTAL</td>
		<td class="derecha"><? echo number_format($row['monto_otro'],2);?></td>
	</tr>	
	
</tbody>
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
<H1 class=SaltoDePagina></H1>


<!-- Empezamos con el ATF -->
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf_2']);?> –  <? echo periodo($row['f_solicitud']);?> - <? echo $row['oficina'];?><BR>
POR CONCEPTO DE GASTOS VARIOS NOTARIALES</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['monto_otro'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud_2']);?> - <? echo periodo($row['f_solicitud']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Proveedor(a)</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['contratante'];?></td>
  </tr>

  <tr>
	<td class="txt_titulo">Referencia</td>
	<td class="txt_titulo centrado">:</td>
	<td colspan="2">
	<br/>
	- CONTRATO DE LOCACION DE SERVICIOS Nº <? echo $row['n_contrato'];?> - <? echo periodo($row['f_firma']);?> – OL <? echo $row['oficina'];?><br/>
	- REQUERIMIENTO DE SERVICIOS Nº <? echo $row['n_requerimiento'];?> - <? echo periodo($row['f_firma']);?> – OL <? echo $row['oficina'];?>
	<br/>
	
	</td>
 </tr>
  <tr>
    <td class="txt_titulo">Justificación</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['justificacion'];?></td>
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
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="42%" align="center">CONCEPTO</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td><? echo $row['justificacion'];?></td>
    <td class="derecha">100%</td>
    <td align="right"><? echo number_format($row['monto_otro'],2);?></td>
    <td align="center"><? echo $row['poa'];?></td>
    <td align="center"><? echo $row['categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['monto_otro'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td>-----</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  
  <tr>
    <td>-----</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  
  
</table>
<BR>
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















<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    
    </td>
  </tr>
</table>











</body>
</html>
