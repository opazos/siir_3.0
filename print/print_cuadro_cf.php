<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Busco los datos del concurso
$sql="SELECT bd_cfinal.nombre, 
	bd_cfinal.f_concurso, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_cfinal.provincia, 
	bd_cfinal.distrito, 
	sys_bd_nivel_cf.descripcion, 
	bd_cfinal.max_gan_a, 
	bd_cfinal.max_gan_b, 
	bd_cfinal.max_gan_c, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS describe_poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	bd_cfinal.n_solicitud_a, 
	sys_bd_fuente_fto.descripcion AS fte_fto, 
	bd_cfinal.departamento, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	bd_cfinal.n_solicitud_b, 
	bd_cfinal.n_solicitud_c
FROM sys_bd_dependencia INNER JOIN bd_cfinal ON sys_bd_dependencia.cod_dependencia = bd_cfinal.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_nivel_cf ON sys_bd_nivel_cf.cod_nivel = bd_cfinal.cod_nivel
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = bd_cfinal.cod_poa
	 INNER JOIN sys_bd_fuente_fto ON sys_bd_fuente_fto.cod = bd_cfinal.cod_fte_fto
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE bd_cfinal.cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$max_gan_a=$r1['max_gan_a'];
$max_gan_b=$r1['max_gan_b'];
$max_gan_c=$r1['max_gan_c'];

if ($tipo==1)
{
	$solicitud=$r1['n_solicitud_a'];
}
elseif($tipo==2)
{
	$solicitud=$r1['n_solicitud_b'];
}
elseif($tipo==3)
{
	$solicitud=$r1['n_solicitud_c'];
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
<? include("encabezado.php");?>
<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RESUMEN DE CALIFICACION DE GANADORES DEL <? echo $r1['nombre'];?></div>
<p>&nbsp;</p>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td>Nº</td>
    <td>OFICINA LOCAL</td>
    <td>ORGANIZACION</td>
    <td>CATEGORIA</td>
    <td>CALIFICACION</td>
    <td>REPRESENTANTE</td>
    <td>Nº DNI</td>
    <td>PREMIO POR CONCURSO</td>
    <td>MONTO DEL PREMIO EN LETRAS</td>
    <td>PUESTO</td>
  </tr>
<?
$num=0;
if($tipo==1)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento AS dni
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 LEFT JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_taz.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_taz.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=1
ORDER BY bd_ficha_cfinal.puntaje DESC";
}
elseif($tipo==2)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento AS dni
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 LEFT JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_taz.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_taz.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=2
ORDER BY bd_ficha_cfinal.puntaje DESC";
}
elseif($tipo==3)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento AS dni
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 LEFT JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY bd_ficha_cfinal.puntaje DESC";
}
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$premio=$f1['premio'];
	$total_premio=$total_premio+$premio;	
	$num++
?>
  <tr>
  	<td class="centrado"><? echo $num;?></td>
  	<td class="centrado"><? echo $f1['oficina'];?></td>
  	<td><? echo $f1['org'];?></td>
  	<td class="centrado"><? if ($tipo==1) echo "CATEGORIA A (PIT: PGRN + PDN)"; elseif($tipo==2) echo "CATEGORIA B (PIT: PDN)"; else echo "CATEGORIA C (PDN)";?></td>
  	<td class="derecha"><? echo number_format($f1['puntaje'],2);?></td>
  	<td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
  	<td class="centrado"><? echo $f1['dni'];?></td>
  	<td class="derecha"><? echo number_format($f1['premio'],2);?></td>
  	<td><? echo vuelveletra($f1['premio']);?></td>
  	<td class="centrado"><? echo numeracion($f1['puesto']);?></td>
  </tr>
<?php
}
?>  
  <tr class="txt_titulo">
    <td colspan="7">TOTALES</td>
    <td class="derecha"><? echo number_format($total_premio,2);?></td>
    <td class="derecha">&nbsp;</td>
    <td class="centrado">&nbsp;</td>
  </tr>
</table>  
<br/>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="18%" class="centrado">DNI</td>
    <td width="41%"><p>NOMBRE</p></td>
    <td width="41%" class="centrado">FIRMA</td>
  </tr>
<?
$sql="SELECT clar_bd_miembro.n_documento AS dni, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_cf.descripcion AS cargo
FROM clar_bd_miembro INNER JOIN bd_jurado_cfinal ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
	 INNER JOIN sys_bd_cargo_cf ON sys_bd_cargo_cf.cod_cargo = bd_jurado_cfinal.cod_cargo_cf
WHERE bd_jurado_cfinal.cod_concurso='$cod'
ORDER BY clar_bd_miembro.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f2['dni'];?></td>
    <td><p><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></p></td>
    <td class="centrado">__________________________________</td>
  </tr>
<?
}
?>  
</table>
<p>&nbsp;</p>
<div class="capa"><? echo $r1['oficina'].", ".traducefecha($r1['f_concurso']);?></div>

<!-- Solicitud de desembolso -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($solicitud);?> - <? echo periodo($r1['f_concurso']);?> / OL <? echo $r1['oficina'];?></u></div>
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
    <td width="76%">PREMIOS PARA EL <? echo $r1['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($r1['f_concurso']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>
<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes a premios del <? echo $r1['nombre'];?> a las siguientes organizaciones:</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
	<td>N. de documento</td>
	<td>Nombre de la organización</td>
	<td>ATF N.</td>
	<td>Institución Financiera</td>
	<td>N° de Cuenta </td>
    <td>Monto a Transferir (S/.) </td>
  </tr>
<?php
if ($tipo==1)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	org_ficha_taz.n_documento
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=1
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_a";
}
elseif($tipo==2)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	org_ficha_taz.n_documento
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=2
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_b";
}
elseif($tipo==3)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_organizacion.n_documento, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_c";
}
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
  <tr>
  	<td class="centrado"><? echo $f3['n_documento'];?></td>
  	<td><? echo $f3['org'];?></td>
  	<td class="centrado"><? echo numeracion($f3['n_atf']);?></td>
  	<td><? echo $f3['banco'];?></td>
  	<td class="centrado"><? echo $f3['n_cuenta'];?></td>
  	<td class="derecha"><? echo number_format($f3['premio'],2);?></td>
  </tr>
<?php
}
?>
  <tr class="txt_titulo">
    <td colspan="5">TOTALES</td>
    <td class="derecha"><? echo number_format($total_premio,2);?></td>
  </tr>
 </table> 

<div class="capa">
	<p>Se adjunta a la presente las autorizaciones de transferencia de fondos de cada una de las organizaciones.</p>
	<p>Atentamente,</p>
</div>
<p><br/></p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $r1['nombres']." ".$r1['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>

<!-- Aca empiezan las ATF -->
<?
if ($tipo==1)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	org_ficha_taz.n_documento
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=1
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_a";
}
elseif($tipo==2)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_taz.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	org_ficha_taz.n_documento
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=2
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_b";
}
elseif($tipo==3)
{
	$sql="SELECT bd_ficha_cfinal.cod_participante, 
	bd_ficha_cfinal.puntaje, 
	bd_ficha_cfinal.puesto, 
	bd_ficha_cfinal.premio, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_organizacion.n_documento, 
	bd_ficha_cfinal.n_atf, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY bd_ficha_cfinal.n_atf DESC LIMIT 0,$max_gan_c";
}
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($f4['n_atf']);?> –  <? echo periodo($r1['f_concurso']);?> - <? echo $r1['oficina'];?><br/>
A "<? echo $f4['org'];?>" POR HABER OBTENIDO EL <? echo numeracion($f4['puesto']);?> PUESTO EN EL <? echo $r1['nombre'];?></div>
<br/>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($f4['premio'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($solicitud);?> - <? echo periodo($r1['f_concurso']);?> / OL <? echo $r1['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $r1['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Nombre de la organización</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f4['org'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Número de documento</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f4['n_documento'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Código POA</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">
    <?php
    if($tipo==3)
    {
    	echo "2.1.2.6. - ASIGNACION PARA PREMIOS DE CONCURSOS DE RESULTADOS INTER PDN";
    }
    else
    {
    	echo "1.1.2.2. - ASIGNACION PARA PREMIOS DE CONCURSOS FINALES";
    }
    ?>
    </td>
  </tr>  

  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f4['banco'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Número de cuenta </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f4['n_cuenta'];?></td>
  </tr>

  <tr>
    <td class="txt_titulo">Categoria de gasto</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"></td>
  </tr>
</table>

<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="82%"><? echo numeracion($f4['puesto']);?> PUESTO EN EL <? echo $r1['nombre'];?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" class="derecha"><? echo number_format($f4['premio'],2);?></td>
  </tr>
</table>
<p>&nbsp;</p>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Acta de concurso</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Planilla de premiación</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de los DNI de los representantes de la organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>
<BR>
<div class="capa" align="right"><? echo $r1['oficina'].", ".traducefecha($r1['f_concurso']);?></div>
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
    <td align="center"><? echo $r1['nombres']." ".$r1['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>

<?
}
?>

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../clar/premia_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=acta" class="secondary button oculto">Finalizar</a>
</div>


</body>
</html>
