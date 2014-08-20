<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
epd_dj_evento.n_dj_evento,
epd_dj_evento.f_inicio,
epd_dj_evento.f_termino,
epd_dj_evento.f_presentacion,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_personal.n_documento,
sys_bd_dependencia.nombre AS dependencia,
sys_bd_dependencia.ubicacion
FROM
epd_dj_evento
INNER JOIN sys_bd_personal ON sys_bd_personal.cod_tipo_doc = epd_dj_evento.cod_tipo_doc AND sys_bd_personal.n_documento = epd_dj_evento.n_documento
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = epd_dj_evento.cod_dependencia
WHERE
epd_dj_evento.cod_dj_evento='$cod'";
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
<br>
<div class="capa txt_titulo" align="center">DECLARACION JURADA Nº <? echo numeracion($row['n_dj_evento']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['dependencia'];?></div>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td align="justify"> Yo, <? echo $row['nombre']." ".$row['apellido'];?>
      
      , identificado con DNI N° <? echo $row['n_documento'];?>
      
      , DECLARO BAJO JURAMENTO, haber pagado gastos, desde el <? echo traducefecha($row['f_inicio']);?> al <? echo traducefecha($row['f_termino']);?> y de los cuales, los detallados lineas abajo, no han podido obtener los documentos sustentatorios correspondientes, de acuerdo al detalle siguiente: <br>
      <span class="txt_titulo">Nota: Las Declaraciones Individuales de las personas detalladas líneas abajo, obran en el archivo de nuestra oficina.
      </span></td>
  </tr>
</table>
<br>
<?
//tramos de dj: verificamos que existan

//1.- hospedaje
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_hospedaje
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 3 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//2.- alimentacion
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_alimentacion
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 1 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- movilidad
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_movilidad
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 2 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//0.- Otro
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_otro
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 0 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);


//4.- Materiales
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_otro
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 4 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//5.- Servicios
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_servicio
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 5 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

//6.- Combustible
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_combustible
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 6 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

//fin de tramos de dj

?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td width="10%" align="center">FECHA</td>
    <td width="63%">CONCEPTO</td>
    <td width="14%" align="center">MONTO PARCIAL</td>
    <td width="13%" align="center">MONTO TOTAL</td>
  </tr>
  <?
if ($r1['total_hospedaje']<>NULL)
{
?>
  <tr>
    <td colspan="3" class="txt_titulo">HOSPEDAJE</td>
    <td align="right"><? echo number_format($r1['total_hospedaje'],2);?></td>
  </tr>
  <?
$sql="SELECT
epd_detalle_dj_evento.f_declaracion,
epd_detalle_dj_evento.concepto,
epd_detalle_dj_evento.monto
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 3 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>
  <tr>
    <td align="center"><? echo fecha_normal($fila1['f_declaracion']);?></td>
    <td><? echo $fila1['concepto'];?></td>
    <td align="right"><? echo number_format($fila1['monto'],2);?></td>
    <td align="right">&nbsp;</td>
  </tr>
  <?
}
}
if ($r2['total_alimentacion']<>NULL)
{
?>
  <tr>
    <td colspan="3" class="txt_titulo">ALIMENTACION</td>
    <td align="right" class="txt_titulo"><? echo number_format($r2['total_alimentacion'],2);?></td>
  </tr>
  <?
$sql="SELECT
epd_detalle_dj_evento.f_declaracion,
epd_detalle_dj_evento.concepto,
epd_detalle_dj_evento.monto
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 1 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
?>
  <tr>
    <td align="center"><? echo fecha_normal($fila2['f_declaracion']);?></td>
    <td><? echo $fila2['concepto'];?></td>
    <td align="right"><? echo number_format($fila2['monto'],2);?></td>
    <td align="right">&nbsp;</td>
  </tr>
  <?
}
}
if ($r3['total_movilidad']<>NULL)
{
?>
  <tr>
    <td colspan="3" class="txt_titulo">MOVILIDAD LOCAL</td>
    <td align="right" class="txt_titulo"><? echo number_format($r3['total_movilidad'],2);?></td>
  </tr>
  <?
$sql="SELECT
epd_detalle_dj_evento.f_declaracion,
epd_detalle_dj_evento.concepto,
epd_detalle_dj_evento.monto
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 2 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
?>
  <tr>
    <td align="center"><? echo fecha_normal($fila3['f_declaracion']);?></td>
    <td><? echo $fila3['concepto'];?></td>
    <td align="right"><? echo number_format($fila3['monto'],2);?></td>
    <td align="right">&nbsp;</td>
  </tr>
  <?
}
}
if ($r4['total_otro']<>NULL)
{
?>
  <tr>
    <td colspan="3" class="txt_titulo">OTRO</td>
    <td align="right" class="txt_titulo"><? echo number_format($r4['total_otro'],2);?></td>
  </tr>
  <?
$sql="SELECT
epd_detalle_dj_evento.f_declaracion,
epd_detalle_dj_evento.concepto,
epd_detalle_dj_evento.monto
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 0 AND
epd_detalle_dj_evento.cod_dj_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila4=mysql_fetch_array($result))
{
?>
  <tr>
    <td align="center"><? echo fecha_normal($fila4['f_declaracion']);?></td>
    <td><? echo $fila4['concepto'];?></td>
    <td align="right"><? echo number_format($fila4['monto'],2);?></td>
    <td align="right">&nbsp;</td>
  </tr>
  <?
}
}
?>
  <tr>
    <td colspan="3" class="txt_titulo">TOTAL</td>
    <td align="right" class="txt_titulo"><? 
$total=$r1['total_hospedaje']+$r2['total_alimentacion']+$r3['total_movilidad']+$r4['total_otro'];
echo number_format($total,2);
?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td><? echo $row['ubicacion'];?>, <? echo traducefecha($row['f_presentacion']);?></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="31%"><hr></td>
    <td width="38%">&nbsp;</td>
    <td width="31%"><hr></td>
  </tr>
  <tr>
    <td align="center"><? echo $row['nombre']." ".$row['apellido'];?><br>
DNI : <? echo $row['n_documento'];?></td>
    <td>&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS</td>
  </tr>
</table>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/dj.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>
</body>
</html>