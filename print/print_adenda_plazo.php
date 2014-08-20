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
	pit_bd_ficha_adenda.contenido
FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 LEFT JOIN org_ficha_directiva_taz presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
	 LEFT JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_adenda.cod_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

echo $row['cod_pit'];
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

<!-- -->
<div class="capa txt_titulo centrado"><u>ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?></u><br/>  AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?> DE DONACIÓN SUJETO A CARGO DEL PLAN DE INVERSIÓN TERRITORIAL (PIT)<br/> DE LA ORGANIZACIÓN "<? echo $row['nombre'];?>"</div>
<p><br/></p>
<!-- Inicio del contenido -->
<div class="capa">
<? echo $row['contenido'];?>
</div>
<!-- Termino del contenido -->
<p>&nbsp;</p>
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
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_organizacion.nombre AS org
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit = '".$row['cod_pit']."' AND
org_ficha_directivo.cod_cargo = 1 AND
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
<b>PRESIDENTE DEL PLAN DE NEGOCIO</b> <BR></td>

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
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit = '".$row['cod_pit']."' AND
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
<b>TESORERO DEL PLAN DE NEGOCIO</b> <BR></td>

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
org_ficha_directivo.cod_cargo = 1 AND
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


<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_plazo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>