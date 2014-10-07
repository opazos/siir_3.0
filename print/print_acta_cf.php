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
    bd_cfinal.n_solicitud_b,
    bd_cfinal.n_solicitud_c,  
    sys_bd_fuente_fto.descripcion AS fte_fto, 
    bd_cfinal.departamento, 
    sys_bd_personal.nombre AS nombres, 
    sys_bd_personal.apellido AS apellidos
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

<div class="capa txt_titulo centrado">ACTA DE RESULTADOS Y PREMIACIÓN DEL <? echo $r1['nombre'];?></div>
<p><br/></p>
<div class="capa justificado">
Siendo las 09:00 horas del día <? echo traducefecha($r1['f_concurso']);?> en la Plaza Principal del distrito de <? echo $r1['distrito'];?>, Provincia de <? echo $r1['provincia'];?> en el Departamento de <? echo $r1['departamento'];?>, ámbito de la  Oficina de <? echo $r1['oficina'];?> del  Proyecto del Desarrollo Sierra Sur II, se dio inicio el "<? echo $r1['nombre'];?>", en el que participan  las  organizaciones de las oficinas locales:
</div>
<br/>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">

     <tr class="txt_titulo">
         <td colspan="5">CATEGORIA A: PIT (PGRN + PDN)</td>
     </tr>
    <tr class="centrado txt_titulo">
        <td>Nº</td>
        <td>OL</td>
        <td>ORGANIZACION</td>
        <td>DNI REPRESENTANTE</td>
        <td>NOMBRE REPRESENTANTE</td>
     </tr>     
    <?php
    $num=0;
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
    $result=mysql_query($sql) or die (mysql_error());
    while($f1=mysql_fetch_array($result))
    {
        $num++
    ?>
     <tr>
         <td class="centrado"><? echo $num;?></td>
         <td class="centrado"><? echo $f1['oficina'];?></td>
         <td><? echo $f1['org'];?></td>
         <td class="centrado"><? echo $f1['dni'];?></td>
         <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
     </tr>
    <?php
    }
    ?> 
     <tr class="txt_titulo">
         <td colspan="5">CATEGORIA B: PIT (PDN)</td>
     </tr> 
    <tr class="centrado txt_titulo">
        <td>Nº</td>
        <td>OL</td>
        <td>ORGANIZACION</td>
        <td>DNI REPRESENTANTE</td>
        <td>NOMBRE REPRESENTANTE</td>
     </tr>        
    <?php
    $numa=0;
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
     INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_taz.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_taz.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_taz.n_documento
     INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=2
ORDER BY bd_ficha_cfinal.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($f2=mysql_fetch_array($result))
    {
        $numa++
    ?>
     <tr>
         <td class="centrado"><? echo $numa;?></td>
         <td class="centrado"><? echo $f2['oficina'];?></td>
         <td><? echo $f2['org'];?></td>
         <td class="centrado"><? echo $f2['dni'];?></td>
         <td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
     </tr>
    <?php
    }
    ?> 
    <tr class="txt_titulo">
         <td colspan="5">CATEGORIA C: PDN</td>
    </tr> 
    <tr class="centrado txt_titulo">
        <td>Nº</td>
        <td>OL</td>
        <td>ORGANIZACION</td>
        <td>DNI REPRESENTANTE</td>
        <td>NOMBRE REPRESENTANTE</td>
     </tr>    
<?php
    $numb=0;
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
     INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
     INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY bd_ficha_cfinal.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($f3=mysql_fetch_array($result))
    {
        $numb++
?>    
    <tr>
        <td class="centrado"><? echo $numb;?></td>
        <td class="centrado"><? echo $f3['oficina'];?></td>
        <td><? echo $f3['org'];?></td>
        <td class="centrado"><? echo $f3['dni'];?></td>
        <td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
    </tr> 
<?
    }
?>    
</table>




<br/>
<div class="capa"><p>Los miembros del jurado fueron:</p>

<ul>
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
while($f4=mysql_fetch_array($result))
{
?> 
    <li><? echo $f4['nombre']." ".$f4['paterno']." ".$f4['materno'].", identificado con DNI N. ".$f4['dni'];?></li>
<?
}
?>
</ul>
<p>Siendo ganadoras las siguientes organizaciones:</p>

<p class="txt_titulo">EN LA CATEGORIA A : PIT (PGRN + PDN)</p>
<ul>
<?php
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
bd_ficha_cfinal.cod_categoria=1 AND
bd_ficha_cfinal.puesto<>0
ORDER BY bd_ficha_cfinal.puesto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
    echo "<li><strong>".numeracion($f5['puesto'])." Lugar</strong>: ".$f5['org']." con <strong>".number_format($f5['puntaje'],2)." puntos</strong>, a quien le corresponde un incentivo de <strong>S/. ".number_format($f5['premio'],2)." (".vuelveletra($f5['premio'])." Nuevos Soles)</strong>.";
}
?>
</ul>

<p class="txt_titulo">EN LA CATEGORIA B : PIT (PDN)</p>
<ul>
<?php
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
bd_ficha_cfinal.cod_categoria=2 AND
bd_ficha_cfinal.puesto<>0
ORDER BY bd_ficha_cfinal.puesto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
    echo "<li><strong>".numeracion($f6['puesto'])." Lugar</strong>: ".$f6['org']." con <strong>".number_format($f6['puntaje'],2)." puntos</strong>, a quien le corresponde un incentivo de <strong>S/. ".number_format($f6['premio'],2)." (".vuelveletra($f6['premio'])." Nuevos Soles)</strong>.";
}
?>
</ul>

<p class="txt_titulo">EN LA CATEGORIA C : PDN</p>
<ul>
<?php
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
bd_ficha_cfinal.cod_categoria=3 AND
bd_ficha_cfinal.puesto<>0
ORDER BY bd_ficha_cfinal.puesto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
    echo "<li><strong>".numeracion($f7['puesto'])." Lugar</strong>: ".$f7['org']." con <strong>".number_format($f7['puntaje'],2)." puntos</strong>, a quien le corresponde un incentivo de <strong>S/. ".number_format($f7['premio'],2)." (".vuelveletra($f7['premio'])." Nuevos Soles)</strong>.";
}
?>
</ul>


</div>

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
while($f8=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f8['dni'];?></td>
    <td><p><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?></p></td>
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
    <a href="../clar/premia_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=acta" class="secondary button oculto">Finalizar</a>
    </td>
  </tr>
</table>



</body>
</html>
