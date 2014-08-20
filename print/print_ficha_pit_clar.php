<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();
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
<?
$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre AS evento_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS rrnn, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_evento_clar INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_evento_clar.cod_clar ='$cod' AND
clar_bd_jurado_evento_clar.calif_clar=1
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
$num=0;
while($fila=mysql_fetch_array($result))
{
	$num++
?>

<? include("encabezado.php");?>


<div align="center" class="txt_titulo">OFICINA LOCAL DE <? echo $fila['oficina'];?></div>
<br>
<div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE INVERSION TERRITORIAL - PIT</div>
<BR>
<DIV class="capa txt_titulo" align="left"><u>SEGUNDA FASE</u>: <u>EVALUACION DE LA PRESENTACION PUBLICA</u></DIV>
<BR>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="mini">NOMBRE DEL PLAN DE INVERSION TERRITORIAL</td>
    <td width="4%" class="mini">:</td>
    <td width="61%" class="mini"><? echo $fila['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="16%" align="center">EJES</td>
    <td width="15%" align="center">CRITERIOS</td>
    <td width="46%" align="center">DESCRIPCION</td>
    <td width="11%" align="center">PUNTAJE MAXIMO</td>
    <td width="12%" align="center">PUNTAJE ASIGNADO</td>
  </tr>
  <tr>
    <td rowspan="5">Presentación pública en CLAR del PIT (50 puntos)</td>
    <td rowspan="2">1.1 Mapa Cultural</td>
    <td class="txt_parrafo"><br>La coherencia de las iniciativas con las potencialidades y perspectivas del desarrollo del territorio<br><br></td>
    <td align="center"><span class="txt_parrafo">10</span></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td class="txt_parrafo"><br>      Evidencia de construcción colectiva del Mapa Cultural del PIT<br><br></td>
    <td align="center">10</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="txt_parrafo">1.2 Claridad</span></td>
    <td class="txt_parrafo"><br>      Evalúa la claridad en la presentación del PIT<br><br></td>
    <td align="center"><span class="txt_parrafo">10</span></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="txt_parrafo">1.3 Identidad y Cultura</span></td>
    <td class="txt_parrafo"><br>      Evalúa los aspectos socioculturales que se reflejan en la presentación del Grupo (vestimenta, idioma, musica y danza, etc)<br><br></td>
    <td align="center"><span class="txt_parrafo">10</span></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td>1.4 Participación de los representantes del PIT</td>
    <td class="txt_parrafo">Evalúa el nivel de participación de los socios de la organización, mujeres, jovenes y niños en la presentación del PIT</td>
    <td align="center"><span class="txt_parrafo">10</span></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_parrafo"><strong>SUBTOTAL EVALUACION CLAR</strong></td>
    <td align="center"><strong>50</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_parrafo"><strong>TOTAL GENERAL</strong></td>
    <td align="center"><strong>100</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="23%">Observaciones</td>
    <td width="1%">:</td>
    <td width="76%"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Nombres y apellidos del Evaluador</td>
    <td>:</td>
    <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
  </tr>
  <tr>
    <td>DNI</td>
    <td>:</td>
    <td><? echo $fila['n_documento'];?></td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>:</td>
    <td><p>&nbsp;</p></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td>:</td>
    <td><? echo $fila['lugar'].", ".traducefecha($fila['f_evento']);?></td>
  </tr>
</table>
<br>
<div class="capa mini txt_titulo" align="center">-<? echo $num;?>-</div>
<H1 class=SaltoDePagina> </H1>
<?
}
?>

<?
$num1=$num;

$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre AS evento_clar, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_evento_clar.lugar, 
	clar_bd_miembro.n_documento, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS rrnn, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo
FROM clar_bd_evento_clar INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_evento_clar.cod_clar ='$cod'  AND
clar_bd_jurado_evento_clar.calif_clar=1 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001
ORDER BY clar_bd_jurado_evento_clar.cod_jurado ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
$num1++
?>
    

  <? include("encabezado.php");?>
  <div align="center" class="txt_titulo">OFICINA LOCAL DE <? echo $fila1['oficina'];?></div>
  <br>
  <div align="center" class="capa gran_titulo">EVALUACION DE LA PROPUESTA DE PLAN DE INVERSION TERRITORIAL - PIT - SEGUNDOS DESEMBOLSOS</div>
  <br>
  <DIV class="capa txt_titulo" align="left"><u>PRIMERA FASE</u>:<u>PRESENTACIÓN PÚBLICA </u></DIV>
  <BR>
  <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="35%" class="mini">NOMBRE DEL PLAN DE INVERSION TERRITORIAL</td>
      <td width="4%" class="mini">:</td>
      <td width="61%" class="mini"><? echo $fila1['organizacion'];?></td>
    </tr>
    <tr>
      <td colspan="3"><hr></td>
    </tr>
  </table>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="txt_titulo centrado">
      <td width="19%" rowspan="2">EJES</td>
      <td width="17%" rowspan="2">CRITERIOS</td>
      <td width="44%" rowspan="2">DESCRIPCION</td>
      <td colspan="2">PUNTAJE</td>
    </tr>
    <tr class="txt_titulo centrado">
      <td width="9%">MAXIMO</td>
      <td width="11%">ASIGNADO</td>
    </tr>
    <tr>
      <td rowspan="6">Presentación pública en CLAR del PIT (50 puntos) </td>
      <td>1.1Mapa Cultural </td>
      <td>Refleja adecuadamente los avances de las iniciativas en ejecucion (PGRN, PDN) en el territorio de la organización. Refleja buen valor de uso</td>
      <td align="center">10</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.2 Manejo de Fondos </td>
      <td>Los responsables del PIT demuestran conocimiento y capacidad para el manejo de fondos. Se interesan y conocen sobre la administración de fondos de PGRN y/o PDN que integran el PIT</td>
      <td align="center">10</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.3 Animador Territorial </td>
      <td>El Animador Territorial participa en la Presentación Pública y contribuye en la exposicion de avances</td>
      <td align="center">8</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.4 Claridad </td>
      <td>Evalúa la claridad en la presentación de los avances a cargo de los directivos y socios del PIT</td>
      <td align="center">7</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.5 Identidad y cultura </td>
      <td>Evalúa los aspectos socioculturales que se reflejan en la presentación del Grupo (vestimenta, idioma, música y danza, etc.)</td>
      <td align="center">5</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>1.6 Participación de los representantes del PIT </td>
      <td>Evalua el nivel de participación de los socios de la organización, mujeres, jovenes y niños en la presentación del PIT</td>
      <td align="center">10</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr>
      <td width="23%">Observaciones</td>
      <td width="1%">:</td>
      <td width="76%"><p>&nbsp;</p>
          <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td>Nombres y apellidos del Evaluador</td>
      <td>:</td>
      <td><? echo $fila1['nombre']." ".$fila1['paterno']." ".$fila1['materno'];?></td>
    </tr>
    <tr>
      <td>DNI</td>
      <td>:</td>
      <td><? echo $fila1['n_documento'];?></td>
    </tr>
    <tr>
      <td>Firma</td>
      <td>:</td>
      <td><p>&nbsp;</p></td>
    </tr>
    <tr>
      <td>Lugar y Fecha</td>
      <td>:</td>
      <td><? echo $fila1['lugar'].", ".traducefecha($fila1['f_evento']);?></td>
    </tr>
  </table>
  <br>
  <div class="capa mini txt_titulo" align="center">-<? echo $num1;?>-</div>
  <H1 class=SaltoDePagina></H1>
<?
}
?>

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit_clar" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>
