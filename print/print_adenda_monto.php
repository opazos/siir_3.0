<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.cod_pit, 
	sys_bd_dependencia.nombre AS oficina, 
	presidente.n_documento AS dni1, 
	presidente.nombre AS nombre1, 
	presidente.paterno AS paterno1, 
	presidente.materno AS materno1, 
	tesorero.n_documento AS dni2, 
	tesorero.nombre AS nombre2, 
	tesorero.paterno AS paterno2, 
	tesorero.materno AS materno2, 
	sys_bd_personal.n_documento, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.contenido, 
	pit_bd_ficha_adenda.n_solicitud, 
	pit_bd_ficha_adenda.cod_adenda
FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 LEFT JOIN org_ficha_directiva_taz presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
	 LEFT JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_adenda.cod_adenda='$cod'";
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
<div class="capa txt_titulo centrado"><u>ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?></u><br/>  AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?> DE DONACIÓN SUJETO A CARGO DEL PLAN DE INVERSIÓN TERRITORIAL (PIT)<br/> DE LA ORGANIZACIÓN "<? echo $row['nombre'];?>"</div>
<p></p>
<!-- Inicio del contenido -->
<div class="capa">
<? echo $row['contenido'];?>
</div>
<!-- Termino del contenido -->
<p></p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="40%" align="center">_______________</td>
    <td width="20%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
  </tr>
  <tr class="mini">
    <td align="center"><? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?><br><? echo $row['nombre'];?><BR><b>PRESIDENTE PIT</b></td>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre2']." ".$row['paterno2']." ".$row['materno2'];?><br><? echo $row['nombre'];?><BR><b>TESORERO PIT</b></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="40%">
<?
$sql="SELECT DISTINCT org_ficha_usuario.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  org_ficha_organizacion.nombre AS org
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."'  AND
org_ficha_directivo.cod_cargo = 1 AND
org_ficha_directivo.vigente=1 AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f7['nombre']." ".$f7['paterno']." ".$f7['materno'];?> <BR>
<? echo $f7['org'];?><br>
<b>PRESIDENTE DEL PLAN DE NEGOCIO</b> <BR></td>
<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       
</tr>      
</table> 
<?
}
?>
</td>
<td width="20%">&nbsp;</td>
<td width="40%">
<?
$sql="SELECT DISTINCT org_ficha_usuario.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  org_ficha_organizacion.nombre AS org
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."'  AND
org_ficha_directivo.cod_cargo = 3 AND
org_ficha_directivo.vigente=1 AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?> <BR>
<? echo $f8['org'];?><br>
<b>TESORERO DEL PLAN DE NEGOCIO</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       
</tr>      
</table> 
<?
}
?>    
    
    </td>
  </tr>
</table>
<br/>
<!-- Jalamos la información de las ampliaciones -->
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="40%">
<?
$sql="SELECT org_ficha_organizacion.nombre AS org, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_cargo_directivo.descripcion AS directivo, 
	org_ficha_usuario.n_documento
FROM clar_atf_pdn INNER JOIN clar_ampliacion_pit ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
org_ficha_directivo.vigente=1 AND
org_ficha_directivo.cod_cargo=1 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_ampliacion_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f5['nombre']." ".$f5['paterno']." ".$f5['materno'];?> <BR>
<? echo $f5['org'];?><br>
<b>PRESIDENTE DEL PLAN DE NEGOCIO</b> <BR></td>
<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       
</tr>      
</table> 
<?
}
?>
</td>
<td width="20%">&nbsp;</td>
<td width="40%">
<?
$sql="SELECT org_ficha_organizacion.nombre AS org, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_cargo_directivo.descripcion AS directivo, 
	org_ficha_usuario.n_documento
FROM clar_atf_pdn INNER JOIN clar_ampliacion_pit ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
org_ficha_directivo.vigente=1 AND
org_ficha_directivo.cod_cargo=3 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
clar_ampliacion_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f6['nombre']." ".$f6['paterno']." ".$f6['materno'];?> <BR>
<? echo $f6['org'];?><br>
<b>TESORERO DEL PLAN DE NEGOCIO</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       
</tr>      
</table> 
<?
}
?>    
</td>
</tr>
</table>







<br/>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">

      

<tr>
<td width="40%">

<?
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_organizacion
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit = '".$row['cod_pit']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
org_ficha_directivo.cod_cargo = 1  AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f7['nombre']." ".$f7['paterno']." ".$f7['materno'];?> <BR>
<? echo $f7['org'];?><br>
<b>PRESIDENTE DEL PLAN DE GESTIÓN DE RECURSOS NATURALES</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?></td>
    <td width="20%">&nbsp;</td>
    <td width="40%">
        <?
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_organizacion
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit = '".$row['cod_pit']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
org_ficha_directivo.cod_cargo = 3 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?> <BR>
<? echo $f8['org'];?><br>
<b>TESORERO DEL PLAN DE GESTIÓN DE RECURSOS NATURALES</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?>    
    
    </td>
  </tr>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="30%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
    <td width="30%" align="center">&nbsp;</td>
  </tr>
  <tr class="mini">
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><BR><B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>



<!-- Solicitud de Desembolso 

<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_adenda']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso a Iniciativas para el Fortalecimiento de Plan de Inversión Teritorial </td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td>REFERENCIA </td>
    <td width="1%">:</td>
    <td width="76%">ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?> AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_adenda']);?></td>
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
<?
$sql="SELECT org_ficha_taz.nombre, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	pit_adenda_pit.n_atf, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_adenda_pit.aporte_pdss
FROM pit_bd_ficha_pit INNER JOIN pit_adenda_pit ON pit_bd_ficha_pit.cod_pit = pit_adenda_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE pit_adenda_pit.cod_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>  
  <tr>
	  <td><? echo $r1['nombre'];?></td>
	  <td class="centrado"><? echo $r1['codigo'];?></td>
	  <td class="centrado"><? echo numeracion($r1['n_atf']);?></td>
	  <td><? echo $r1['banco'];?></td>
	  <td class="centrado"><? echo $r1['n_cuenta'];?></td>
	  <td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
  </tr>
<?
}
?>

<?
$sql="SELECT org_ficha_organizacion.nombre, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	(pit_adenda_mrn.n_atf+ 
	pit_adenda_mrn.cif_pdss+ 
	pit_adenda_mrn.at_pdss+ 
	pit_adenda_mrn.ag_pdss) AS aporte_pdss, 
	pit_adenda_mrn.n_atf
FROM pit_bd_ficha_mrn INNER JOIN pit_adenda_mrn ON pit_bd_ficha_mrn.cod_mrn = pit_adenda_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE pit_adenda_mrn.cod_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
  <tr>
	  <td><? echo $r2['nombre'];?></td>
	  <td class="centrado"><? echo $r2['codigo'];?></td>
	  <td class="centrado"><? echo numeracion($r2['n_atf'],2);?></td>
	  <td><? echo $r2['banco'];?></td>
	  <td class="centrado"><? echo $r2['n_cuenta'];?></td>
	  <td class="derecha"><? echo number_format($r2['aporte_pdss'],2);?></td>
  </tr>
<?
}
?>
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
<H1 class=SaltoDePagina></H1>

<? include("encabezado.php");?>
<?
$sql="SELECT pit_adenda_pit.aporte_pdss, 
	pit_adenda_pit.n_atf, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	sys_bd_tipo_org.descripcion AS tipo_org
FROM sys_bd_componente_poa INNER JOIN pit_adenda_pit ON sys_bd_componente_poa.cod = pit_adenda_pit.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = pit_adenda_pit.cod_poa
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_adenda_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE pit_adenda_pit.cod_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
?>
<div class="capa txt_titulo" align="center">
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r3['n_atf']);?> – PIT – <? echo periodo($row['f_adenda']);?> - <? echo $row['oficina'];?><br> 
PARA EL ANIMADOR TERRITORIAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($r3['aporte_pdss'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">
  En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_adenda']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle
</div>

<br>
	<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
		<tr>
			<td width="35%" class="txt_titulo">Organización PIT</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $r3['nombre'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Organización a transferir</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $r3['nombre'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $r3['tipo_org'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Referencia</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?> AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de desembolso</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">PRIMER DESEMBOLSO</td>
		</tr>
		<tr>
			<td class="txt_titulo">Entidad financiera</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $r3['banco'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de cuenta bancaria</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $r3['n_cuenta'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Fuente de Financiamiento</td>
			<td align="center" class="txt_titulo">:</td>
			<td width="30%">FIDA: 100</td>
			<td width="31%">RO: 0</td>
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
    <td>Primer desembolso</td>
    <td class="centrado">100.00 </td>
    <td align="right" class="txt_titulo"><? echo number_format($r3['aporte_pdss'],2);?></td>
    <td align="center"><? echo $r3['poa'];?></td>
    <td align="center"><? echo $r3['categoria'];?></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($r3['aporte_pdss'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<br>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud de ampliación de plazo y presupuesto presentado por la Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Informe de pertinencia de la Oficina Local</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Addenda al Contrato de Donación sujeto a Cargo entre SIERRA SUR II y la Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>
<br/>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_adenda']);?></div>
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



<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	pit_bd_ficha_mrn.lema, 
	(pit_adenda_mrn.cif_pdss+ 
	pit_adenda_mrn.at_pdss+ 
	pit_adenda_mrn.ag_pdss) AS aporte_pdss, 
	pit_adenda_mrn.cif_pdss, 
	pit_adenda_mrn.at_pdss, 
	pit_adenda_mrn.ag_pdss, 
	pit_adenda_mrn.n_atf, 
	pit_adenda_mrn.n_voucher, 
	pit_adenda_mrn.deposito_org, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa1, 
	cat1.codigo AS cat1, 
	poa2.codigo AS poa2, 
	cat2.codigo AS cat2, 
	poa3.codigo AS poa3, 
	cat3.codigo AS cat3, 
	pit_adenda_mrn.at_org
FROM pit_bd_ficha_mrn INNER JOIN pit_adenda_mrn ON pit_bd_ficha_mrn.cod_mrn = pit_adenda_mrn.cod_mrn
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = pit_adenda_mrn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = pit_adenda_mrn.poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = pit_adenda_mrn.poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = pit_adenda_mrn.poa_4
	 INNER JOIN sys_bd_categoria_poa cat3 ON cat3.cod = poa3.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa cat2 ON cat2.cod = poa2.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa cat1 ON cat1.cod = poa1.cod_categoria_poa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE pit_adenda_mrn.cod_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r4['n_atf']);?> – PIT – <? echo periodo($row['f_adenda']);?> - <? echo $row['oficina'];?><BR>
  PARA EL COFINANCIAMIENTO DEL PLAN DE GESTION DE RECURSOS NATURALES</div>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($r4['aporte_pdss'],2);?></td>
  </tr>
</table>
<br/>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_adenda']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
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
    <td colspan="2"><? echo $r4['nombre'];?></td>
  </tr>
  <tr>
	<td class="txt_titulo">Tipo de Organización</td>
	<td class="txt_titulo centrado">:</td>
	<td colspan="2"><?php  echo $r4['tipo_org'];?></td>
 </tr>
  <tr>
    <td class="txt_titulo">Lema del PGRN</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r4['lema'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?> AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">PRIMER DESEMBOLSO</td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r4['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r4['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de financiamiento</td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: 100</td>
    <td width="31%">RO: 0</td>
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
    <td class="derecha">100.00</td>
    <td align="right"><? echo number_format($r4['cif_pdss'],2);?></td>
    <td align="center"><? echo $r4['poa1'];?></td>
    <td align="center"><? echo $r4['cat1'];?></td>
  </tr>
  <tr>
    <td>Asistencia Técnica (de campesino a campesino)</td>
    <td class="derecha">100.00</td>
    <td align="right"><? echo number_format($r4['at_pdss'],2);?></td>
    <td align="center"><? echo $r4['poa2'];?></td>
    <td align="center"><? echo $r4['cat2'];?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PGRN</td>
    <td class="derecha">100.00</td>
    <td align="right"><? echo number_format($r4['ag_pdss'],2);?></td>
    <td align="center"><? echo $r4['poa3'];?></td>
    <td align="center"><? echo $r4['cat3'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($r4['aporte_pdss'],2);?></td>
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
    <td width="61%" align="right"><? echo $r4['n_voucher'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($r4['deposito_org'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? 
	if ($r4['deposito_org']>$r4['at_org'])
	{
	$saldo_mrn=0;
	}
	else
	{
	$saldo_mrn=$r4['at_org']-$r4['deposito_org'];
	}
	echo number_format($saldo_mrn,2);
	?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	if ($r4['deposito_org']>$r4['at_org'])
	{
	$pp_saldo_mrn=0;
	}
	else
	{
	@$pp_saldo_mrn=$r4['deposito_org']/$r4['at_org']*100;
	}
	echo number_format(@$pp_saldo_mrn,2);
	?>
	</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>




<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud de ampliación de plazo y presupuesto presentado por la Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Informe de pertinencia de la Oficina Local</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Addenda al Contrato de Donación sujeto a Cargo entre SIERRA SUR II y la Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del voucher de depósito de la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_adenda']);?></div>
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

-->














<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>