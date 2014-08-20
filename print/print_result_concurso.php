<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT gcac_concurso_clar.f_concurso, 
	gcac_concurso_clar.nombre, 
	gcac_concurso_clar.premio, 
	sys_bd_dependencia.nombre AS oficina
FROM sys_bd_dependencia INNER JOIN gcac_concurso_clar ON sys_bd_dependencia.cod_dependencia = gcac_concurso_clar.cod_dependencia
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
<p>&nbsp;</p>

<div class="capa txt_titulo centrado">RESUMEN DE CALIFICACION DE GANADORES DEL <? echo $r1['nombre'];?></div>

<p>&nbsp;</p>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td>Nº</td>
    <td>OFICINA LOCAL</td>
    <td>ORGANIZACION</td>
    <td>PARTICIPA CON</td>
    <td>CALIFICACION</td>
    <td>REPRESENTANTE</td>
    <td>Nº DNI</td>
    <td>PREMIO POR CONCURSO</td>
    <td>MONTO DEL PREMIO EN LETRAS</td>
    <td>PUESTO</td>
  </tr>
<?
	$num=0;
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
WHERE gcac_participante_concurso.cod_concurso='$cod'
ORDER BY gcac_participante_concurso.puntaje DESC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$premio=$fila['premio'];
	$total_premio=$total_premio+$premio;
	$num++
?>  
  <tr>
    <td><? echo $num;?></td>
    <td class="centrado"><? echo $fila['oficina'];?></td>
    <td><? echo $fila['nombre'];?></td>
    <td><? echo $fila['descripcion'];?></td>
    <td class="derecha"><? echo number_format($fila['puntaje'],2);?></td>
    <td><? echo $fila['nombre_rep'];?></td>
    <td><? echo $fila['dni_rep'];?></td>
    <td class="derecha"><? echo number_format($fila['premio'],2);?></td>
    <td class="derecha"><? echo vuelveletra($fila['premio']);?></td>
    <td class="centrado"><? echo numeracion($fila['puesto']);?></td>
  </tr>
 <?
}
?>   
  <tr class="txt_titulo">
    <td colspan="7">&nbsp;</td>
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
$sql="SELECT * FROM gcac_jurado_concurso WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo $f2['n_documento'];?></td>
    <td><p><? echo $f2['nombres']." ".$f2['apellidos'];?></p></td>
    <td class="centrado">__________________________________</td>
  </tr>
<?
}
?>  
</table>
<p>&nbsp;</p>
<div class="capa"><? echo $r1['oficina'].", ".traducefecha($r1['f_concurso']);?></div>



<?
	$sql="SELECT gcac_participante_concurso.puntaje, 
	gcac_participante_concurso.puesto, 
	gcac_participante_concurso.premio, 
	gcac_participante_concurso.dni_rep, 
	gcac_participante_concurso.nombre_rep, 
	gcac_participante_concurso.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	gcac_concurso_clar.nombre AS nombre_concurso, 
	gcac_concurso_clar.f_concurso, 
	gcac_concurso_clar.departamento, 
	gcac_concurso_clar.provincia, 
	gcac_concurso_clar.distrito, 
	gcac_concurso_clar.lugar
FROM org_ficha_organizacion INNER JOIN gcac_participante_concurso ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
	 INNER JOIN gcac_concurso_clar ON gcac_concurso_clar.cod_concurso = gcac_participante_concurso.cod_concurso
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE gcac_participante_concurso.premio<>0 AND
gcac_participante_concurso.cod_concurso='$cod'
ORDER BY gcac_participante_concurso.puntaje DESC";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa centrado gran_titulo">RECIBO</div>
<p><br/></p>
<div class="capa derecha gran_titulo"><? echo number_format($fila1['premio'],2);?></div>
<p><br/></p>
<div class="capa">Yo, <? echo $fila1['nombre_rep'];?> identificada(o) con DNI N° <? echo $fila1['dni_rep'];?> en representación de  la <? echo $fila1['nombre'];?> del ámbito de la  Oficina Local <? echo $fila1['oficina'];?>, recibí del NEC–PROYECTO DE DESARROLLO SIERRA SUR II, la suma de S/. <? echo number_format($fila1['premio'],2);?> (<? echo vuelveletra($fila1['premio']);?> Nuevos Soles) por concepto de haber participado en el <? echo $fila1['nombre_concurso'];?>,  realizado en el Distrito de <? echo $fila1['distrito'];?>, Provincia de <? echo $fila1['provincia'];?>, Departamento de <? echo $fila1['departamento'];?>.</div>
<p><br/></p>
<div class="capa derecha"><? echo $fila1['distrito'];?>, <? echo traducefecha($fila1['f_concurso']);?></div>
<p><br/></p>
<p><br/></p>

<div class="capa centrado txt_titulo">______________________<br/>RECIBI CONFORME</div>

<?
}
?>




<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    
    <?
    if ($modo==pdn)
    {
    ?>
    <a href="../clar/calif_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result" class="secondary button oculto">Finalizar</a>    
    <?
    }
    elseif($modo==gastro)
    {
    ?>
    <a href="../clar/calif_gastro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==mapa)
    {
    ?>
    <a href="../clar/calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==danza)
    {
    ?>
    <a href="../clar/calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result" class="secondary button oculto">Finalizar</a>
    <?
    }
    elseif($modo==joven)
    {
    ?>
    <a href="../clar/calif_joven.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result" class="secondary button oculto">Finalizar</a>
    <?
    }
    ?>

    </td>
  </tr>
</table>

</body>
</html>
