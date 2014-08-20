<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT gcac_concurso_clar.f_concurso, 
	gcac_concurso_clar.nombre, 
	gcac_concurso_clar.premio, 
	sys_bd_dependencia.nombre AS oficina, 
	gcac_concurso_clar.cod_tipo_concurso, 
	sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
	gcac_concurso_clar.departamento, 
	gcac_concurso_clar.provincia, 
	gcac_concurso_clar.distrito, 
	gcac_concurso_clar.lugar, 
	gcac_concurso_clar.incentivo, 
	gcac_concurso_clar.n_ganadores
FROM sys_bd_dependencia INNER JOIN gcac_concurso_clar ON sys_bd_dependencia.cod_dependencia = gcac_concurso_clar.cod_dependencia
	 INNER JOIN sys_bd_tipo_concurso_clar ON gcac_concurso_clar.cod_tipo_concurso = sys_bd_tipo_concurso_clar.codigo
WHERE gcac_concurso_clar.cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result)

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

<div class="capa txt_titulo centrado">ACTA DE RESULTADOS Y PREMIACIÓN DEL <? echo $r1['tipo_concurso'];?></div>
<p><br/></p>
<div class="capa justificado">
Siendo las 09:00 horas del día <? echo traducefecha($r1['f_concurso']);?> en <? echo $r1['lugar'];?> del distrito de <? echo $r1['distrito'];?>, Provincia de <? echo $r1['provincia'];?> en el Departamento de <? echo $r1['departamento'];?>, ámbito de la  Oficina de <? echo $r1['oficina'];?> del  Proyecto del Desarrollo Sierra Sur II, se dio inicio el "<? echo $r1['nombre'];?>", en el que participan  las  organizaciones de las oficinas locales:
</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td>Nº</td>
    <td>OL</td>
    <td>ORGANIZACION</td>
    <td>DNI REPRESENTANTE</td>
    <td>NOMBRE REPRESENTANTE</td>
  </tr>
<?
$num=0;




$sql="SELECT gcac_pit_participante_concurso.dni_rep, 
	gcac_pit_participante_concurso.nombre_rep, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pit INNER JOIN gcac_pit_participante_concurso ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE gcac_pit_participante_concurso.cod_concurso='$cod'
ORDER BY gcac_pit_participante_concurso.puntaje_total DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
?>  
  <tr>
    <td class="centrado"><? echo $num;?></td>
    <td class="centrado"><? echo $f1['oficina'];?></td>
    <td><? echo $f1['nombre'];?></td>
    <td class="centrado"><? echo $f1['dni_rep'];?></td>
    <td><? echo $f1['nombre_rep'];?></td>
  </tr>
<?
}
?>  
</table>
<br/>
<div class="capa"><p>Los miembros del jurado fueron:</p>

<ul>
<?
$sql="SELECT * FROM gcac_jurado_concurso WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?> 
	<li><? echo $f2['nombres']." ".$f2['apellidos'].", identificado con DNI Nº ".$f2['n_documento'];?></li>
<?
}
?>	
</ul>
Siendo ganadoras las siguientes organizaciones:

<ul>
<?
	$sql="SELECT gcac_pit_participante_concurso.dni_rep, 
	gcac_pit_participante_concurso.nombre_rep, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	gcac_pit_participante_concurso.puesto, 
	gcac_pit_participante_concurso.premio, 
	gcac_pit_participante_concurso.puntaje_total
FROM pit_bd_ficha_pit INNER JOIN gcac_pit_participante_concurso ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE gcac_pit_participante_concurso.cod_concurso='$cod' AND
gcac_pit_participante_concurso.premio<>0
ORDER BY gcac_pit_participante_concurso.puntaje_total DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
	<li><strong><? echo $f3['puesto'];?> Lugar</strong>: <? echo $f3['nombre'];?> con <? echo number_format($f3['puntaje_total'],2);?> puntos, a quien le corresponde un incentivo de S/. <? echo number_format($f3['premio'],2);?> (<? echo vuelveletra($f3['premio']);?>)</li>
<?
}
?>	
</ul>
</div>
<?
if($r1['incentivo']<>0)
{
?>
<div class="capa justificado">
Asimismo a las organizaciones participantes, se les otorga un incentivo de S/.<? echo number_format($r1['incentivo'],2);?> (<? echo vuelveletra($r1['incentivo']);?>) por su participación en el presente evento:

</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td>Nº</td>
    <td>OL</td>
    <td>ORGANIZACION</td>
    <td>PARTICIPA CON</td>
    <td>DNI REPRESENTANTE</td>
    <td>NOMBRE REPRESENTANTE</td>    
  </tr>
<?
$nu=0;
$sql="SELECT gcac_participante_concurso.puntaje, 
	gcac_participante_concurso.puesto, 
	gcac_participante_concurso.premio, 
	gcac_participante_concurso.dni_rep, 
	gcac_participante_concurso.nombre_rep, 
	gcac_participante_concurso.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina
FROM org_ficha_organizacion INNER JOIN gcac_participante_concurso ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE gcac_participante_concurso.premio=0 AND
gcac_participante_concurso.cod_concurso='$cod'
ORDER BY gcac_participante_concurso.puntaje DESC";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
	$nu++
?>  
  <tr>
    <td class="centrado"><? echo $nu;?></td>
    <td class="centrado"><? echo $f4['oficina'];?></td>
    <td><? echo $f4['nombre'];?></td>
    <td><? echo $f4['descripcion'];?></td>
    <td class="centrado"><? echo $f4['dni_rep'];?></td>
    <td><? echo $f4['nombre_rep'];?></td>
  </tr>
<?
}
?>  
</table>
<?
}
?>
<br/>
<div class="capa justificado">
Inmediatamente concluido el concurso se procedió a la premiación pública a cada uno de los ganadores, concluyéndose con el concurso siendo las 17:00 del mismo día.
</div>
<br/>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="18%" class="centrado">DNI</td>
    <td width="41%"><p>NOMBRE</p></td>
    <td width="41%" class="centrado">FIRMA</td>
  </tr>
<?
$sql="SELECT * FROM gcac_jurado_concurso WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f5['n_documento'];?></td>
    <td><p><? echo $f5['nombres']." ".$f5['apellidos'];?></p></td>
    <td class="centrado">__________________________________</td>
  </tr>
<?
}
?>  
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <?
    if ($modo==pdn)
    {
    ?>
    <a href="../clar/calif_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>    
    <?
    }
    elseif($modo==gastro)
    {
    ?>
    <a href="../clar/calif_gastro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==mapa)
    {
    ?>
    <a href="../clar/calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==danza)
    {
    ?>
    <a href="../clar/calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==joven)
    {
    ?>
    <a href="../clar/calif_joven.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==territorio)
    {
    ?>
    <a href="../clar/calif_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta" class="secondary button oculto">Finalizar</a>
    <?
    }
    ?>

    </td>
  </tr>
</table>



</body>
</html>
