<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.- Ubico la informacion de pago
$sql="SELECT sf_bd_pago_poliza.f_solicitud, 
	sf_bd_pago_poliza.n_solicitud, 
	sf_bd_pago_poliza.n_atf, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_personal.n_documento, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS describe_poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	sf_bd_pago_poliza.n_cuenta, 
	sys_bd_aseguradora.descripcion AS aseguradora, 
	sys_bd_ifi.descripcion AS ifi
FROM sys_bd_dependencia INNER JOIN sf_bd_pago_poliza ON sys_bd_dependencia.cod_dependencia = sf_bd_pago_poliza.cod_dependencia
	 INNER JOIN sys_bd_aseguradora ON sys_bd_aseguradora.cod = sf_bd_pago_poliza.cod_aseguradora
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = sf_bd_pago_poliza.cod_ifi
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = sf_bd_pago_poliza.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE sf_bd_pago_poliza.cod_pago='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//2.- totalizamos
$sql="SELECT DISTINCT SUM(sf_bd_poliza.aporte_pdss) AS aporte_pdss, 
	SUM(sf_bd_poliza.aporte_org) AS aporte_org
FROM sf_bd_usuario_seguro INNER JOIN sf_bd_poliza ON sf_bd_poliza.cod_poliza = sf_bd_usuario_seguro.cod_poliza
WHERE sf_bd_usuario_seguro.cod_pago='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Guardamos el monto del pago
$sql="UPDATE sf_bd_pago_poliza SET monto_solicitud='".$r1['aporte_pdss']."' WHERE cod_pago='$cod'";
$result=mysql_query($sql) or die (mysql_error());

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
    <td width="76%">Desembolso  por: Seguro de Vida Campesino</td>
  </tr>
  
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['oficina'].", ".traducefecha($row['f_solicitud']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondiente al sub-componente de  Apoyo a la Intermediación Financiera  Rural por: Seguro de Vida Campesino  de las siguientes personas:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr class="centrado txt_titulo">
	<td>N</td>
	<td>Nombres y Apellidos</td>
	<td>DNI</td>
	<td>Tipo de Seguro</td>
	<td>No de Poliza</td>
	<td>Monto a Tranferir (S/.)</td>
</tr>
<?
$num=0;
$sql="SELECT DISTINCT org_ficha_usuario.n_documento, 
	sf_bd_poliza.cod_poliza, 
	sf_bd_poliza.aporte_pdss, 
	sf_bd_poliza.aporte_org, 
	sys_bd_tipo_seguro.descripcion AS tipo_seguro, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM sf_bd_usuario_seguro INNER JOIN sf_bd_pago_poliza ON sf_bd_usuario_seguro.cod_pago = sf_bd_pago_poliza.cod_pago
	 INNER JOIN sf_bd_poliza ON sf_bd_poliza.cod_poliza = sf_bd_usuario_seguro.cod_poliza
	 INNER JOIN sys_bd_tipo_seguro ON sys_bd_tipo_seguro.cod = sf_bd_poliza.cod_tipo_seguro
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = sf_bd_poliza.cod_tipo_doc AND org_ficha_usuario.n_documento = sf_bd_poliza.n_documento
WHERE sf_bd_pago_poliza.cod_pago='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$num++
?>
<tr>
	<td class="centrado"><? echo $num;?></td>
	<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
	<td class="centrado"><? echo $fila['n_documento'];?></td>
	<td class="centrado"><? echo $fila['tipo_seguro'];?></td>
	<td class="centrado"><? echo $fila['cod_poliza'];?></td>
	<td class="derecha"><? echo number_format($fila['aporte_pdss'],2);?></td>
</tr>
<?
}
?>  
  <tr class="txt_titulo">
    <td colspan="5">TOTAL</td>
    <td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
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
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_solicitud']);?> - <? echo $row['oficina'];?><BR>
PARA EL  SEGURO DE VIDA CAMPESINO</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($r1['aporte_pdss'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?>-<? echo periodo($row['f_solicitud']);?>/<? echo $row['oficina'];?>; por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de Paucartambo ,autoriza la transferencia de fondos, de acuerdo al siguiente detalle :</div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['ifi'];?></td>
  </tr>
  <tr>
	<td class="txt_titulo">N° de cuenta </td>
	<td class="txt_titulo centrado">:</td>
	<td colspan="2"><? echo $row['n_cuenta'];?></td>
 </tr>

 <tr>
	 <td class="txt_titulo">Compañia Aseguradora</td>
	 <td class="txt_titulo centrado">:</td>
	 <td colspan="2"><? echo $row['aseguradora'];?></td>
 </tr>


  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: 100%</td>
    <td width="31%">RO: 0%</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">

<tr class="txt_titulo centrado">
	<td>INICIATIVA</td>
	<td>% A DESEMBOLSAR</td>
	<td>MONTO A TRANSFERIR (S/.)</td>
	<td>CODIGO POA</td>
	<td>CATEGORIA DE GASTO</td>
</tr>

<tr>
	<td>SEGURO DE VIDA CAMPESINO</td>
	<td class="centrado">100</td>
	<td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
	<td class="centrado"><? echo $row['poa'];?></td>
	<td class="centrado"><? echo $row['categoria'];?></td>
</tr>

</table>

<br/>
<div class="capa txt_titulo">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
	<td>MONTO DE APORTE</td>
	<td class="derecha"><? echo number_format($r1['aporte_org'],2);?></td>
</tr>

<tr>
	<td>%</td>
	<td class="derecha">100</td>
</tr>

</table>
<br></br>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud de la organización de participación en el Programa de Seguros de Vida</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copias de las Pólizas simplificadas Microseguro de Vida Agrario</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del Voucher de Deposito de contrapartida de los Seguros de Vida</td>
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
    <a href="../pit/pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>











</body>
</html>
