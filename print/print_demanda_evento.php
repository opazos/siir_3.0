<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.- buscamos los datos del evento
$sql="SELECT epd_bd_demanda.cod_evento, 
  epd_bd_demanda.n_evento, 
  epd_bd_demanda.nombre AS taller, 
  epd_bd_demanda.cod_tipo_evento, 
  epd_bd_demanda.especificar AS otro_evento, 
  epd_bd_demanda.f_presentacion, 
  epd_bd_demanda.f_evento, 
  epd_bd_demanda.objetivo, 
  epd_bd_demanda.resultado, 
  epd_bd_demanda.participantes, 
  epd_bd_demanda.oficial, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  epd_bd_demanda.direccion, 
  epd_bd_tipo_evento.descripcion AS tipo_evento, 
  sys_bd_componente_poa.codigo AS codigo_componente, 
  sys_bd_componente_poa.nombre AS nombre_componente, 
  sys_bd_personal.n_documento, 
  sys_bd_personal.nombre, 
  sys_bd_personal.apellido, 
  sys_bd_cargo.descripcion AS cargo, 
  Sum(epd_bd_presupuesto.monto) AS monto_solicitado, 
  sys_bd_subactividad_poa.codigo AS codigo_poa, 
  sys_bd_subactividad_poa.nombre AS nombre_poa, 
  sys_bd_dependencia.cod_dependencia, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_dependencia.ubicacion, 
  sys_bd_categoria_poa.codigo, 
  sys_bd_categoria_poa.nombre AS categoria, 
  epd_bd_demanda.fte_fto, 
  epd_bd_demanda.cod_tipo_iniciativa_evento, 
  epd_bd_demanda.codigo_evento, 
  epd_bd_demanda.olp_evento
FROM epd_bd_demanda INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = epd_bd_demanda.cod_dep
   INNER JOIN sys_bd_provincia ON epd_bd_demanda.cod_prov = sys_bd_provincia.cod
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = epd_bd_demanda.cod_dist
   INNER JOIN epd_bd_tipo_evento ON epd_bd_tipo_evento.cod_tipo_evento = epd_bd_demanda.cod_tipo_evento
   INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = epd_bd_demanda.cod_componente
   INNER JOIN sys_bd_personal ON sys_bd_personal.cod_tipo_doc = epd_bd_demanda.cod_tipo_doc_solicitante AND sys_bd_personal.n_documento = epd_bd_demanda.n_doc_solicitante
   INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
   LEFT OUTER JOIN epd_bd_presupuesto ON epd_bd_presupuesto.cod_evento = epd_bd_demanda.cod_evento
   INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = epd_bd_demanda.cod_poa
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
   LEFT OUTER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = epd_bd_demanda.cod_categoria
WHERE epd_bd_demanda.cod_evento='$cod' AND
epd_bd_presupuesto.monto_solicitado = 1
GROUP BY epd_bd_demanda.cod_evento,
epd_bd_demanda.n_evento,
epd_bd_demanda.nombre,
epd_bd_demanda.especificar,
epd_bd_demanda.f_presentacion,
epd_bd_demanda.f_evento,
epd_bd_demanda.objetivo,
epd_bd_demanda.resultado,
epd_bd_demanda.participantes,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
epd_bd_demanda.direccion,
epd_bd_tipo_evento.descripcion,
sys_bd_componente_poa.codigo,
sys_bd_componente_poa.nombre,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//buscamos el costo total del evento
$sql="SELECT
Sum(epd_bd_presupuesto.monto) AS monto_total
FROM
epd_bd_presupuesto
WHERE
epd_bd_presupuesto.cod_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//obtenemos los codigos de evento
  if($row['cod_tipo_iniciativa_evento']==1)
  {
    $sql="SELECT epd_bd_demanda.cod_evento AS cod_evento, 
  epd_bd_demanda.cod_tipo_iniciativa,
  epd_bd_demanda.n_evento, 
  epd_bd_demanda.nombre
  FROM epd_bd_demanda
  WHERE
  epd_bd_demanda.cod_evento='".$row['codigo_evento']."' AND
  epd_bd_demanda.cod_dependencia='".$row['olp_evento']."' AND
  epd_bd_demanda.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
  }
  else
  {
    $sql="SELECT clar_bd_evento_clar.cod_clar AS n_evento, 
  clar_bd_evento_clar.cod_tipo_iniciativa,
  clar_bd_evento_clar.nombre
  FROM clar_bd_evento_clar
  WHERE clar_bd_evento_clar.cod_clar='".$row['codigo_evento']."' AND
  clar_bd_evento_clar.cod_dependencia='".$row['olp_evento']."' AND
  clar_bd_evento_clar.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'";
  }
  $result=mysql_query($sql) or die (mysql_error());
  $l1=mysql_fetch_array($result);
  $total_registro=mysql_num_rows($result);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<div class="txt_titulo" align="center"><u>PROPUESTA</u><br>
EVENTO DE INTERCAMBIO Y SOCIALIZACION DE CONOCIMIENTOS<BR>
  N° <? echo numeracion($row['n_evento']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></div>
<br>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACIÓN GENERAL</td>
  </tr>
  <tr>
    <td width="36%">Nombre del evento</td>
    <td width="2%">:</td>
    <td width="62%"><? echo $row['taller'];?></td>
  </tr>
  <tr>
    <td>Tipo de evento</td>
    <td>:</td>
    <td><? if ($row['cod_tipo_evento']==0) echo $row['otro_evento']; else echo $row['tipo_evento'];?></td>
  </tr>
  <tr>
    <td>Fecha programada</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_evento']);?></td>
  </tr>
  <tr>
    <td>Lugar</td>
    <td>:</td>
    <td><? echo $row['departamento'];?> - <? echo $row['provincia'];?> 
    
<? 
if ($row['cod_dependencia']<>001)
{
?>    
    - <? echo $row['distrito'];?> 
<?
}
?>
    
    
    - <? echo $row['direccion'];?></td>
  </tr>
  <tr>
    <td>Responsable</td>
    <td>:</td>
    <td><? echo $row['nombre']." ".$row['apellido']." - ".$row['cargo'];?></td>
  </tr>
  <tr>
    <td>Oficina</td>
    <td>:</td>
    <td><? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>N° Estimado de asistentes</td>
    <td>:</td>
    <td><? echo $row['participantes'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">II.- OBJETIVOS Y RESULTADOS</td>
  </tr>
  <tr>
    <td>Objetivo del evento</td>
    <td>:</td>
    <td>
	<? echo $row['objetivo'];?>
    
    </td>
  </tr>
  <tr>
    <td>Resultados esperados</td>
    <td>:</td>
    <td>
	<? echo $row['resultado'];?>
    
    </td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">III.- PRESUPUESTO Y AFECTACION </td>
  </tr>
  <tr>
    <td>Presupuesto Total (S/.)</td>
    <td>:</td>
    <td><? echo number_format($r3['monto_total'],2);?> (Ver hoja de presupuesto adjunta)</td>
  </tr>
  <tr>
    <td>Presupuesto Solicitado (S/.)</td>
    <td>:</td>
    <td><? echo number_format($row['monto_solicitado'],2);?></td>
  </tr>
  <tr>
    <td>Componente</td>
    <td>:</td>
    <td><? echo $row['codigo_componente']." - ".$row['nombre_componente'];?></td>
  </tr>
  <tr>
    <td>Categoría de Gasto</td>
    <td>:</td>
    <td><? echo $row['codigo']." - ".$row['categoria'];?></td>
  </tr>
  <tr>
    <td>Actividad / POA</td>
    <td>:</td>
    <td><? echo $row['codigo_poa']." - ".$row['nombre_poa'];?></td>
  </tr>
   <tr>
    <td>Fte. Fto.</td>
    <td>:</td>
    <td><? if ($row['fte_fto']==1) echo "100% FIDA"; elseif($row['fte_fto']==2) echo "100% R.O."; elseif($row['fte_fto']==3) echo "50% FIDA / 50% R.O."; elseif($row['fte_fto']==4) echo "DONACION"; else echo "-";?></td>
  </tr> 
  <tr>
    <td>Referencia a evento Particular</td>
    <td>:</td>
    <td>
    <? 
    if ($total_registro<>0) 
    {
      echo "Codigo de evento N. ".numeracion($l1['n_evento'])." denominado: ".$l1['nombre'];
    }
    ?>
    </td>
  </tr> 
  
</table>
<br>
<div class="capa" align="right"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
<br>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="33%"><hr></td>
    <td width="32%">&nbsp;</td>
    <td width="35%"><hr></td>
  </tr>
  <tr>
    <td align="center">(Solicitante)<br><? echo $row['nombre']." ".$row['apellido']."<br>  DNI N° ".$row['n_documento'];?></td>
    <td>&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS </td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>

<? include("encabezado.php");?>
<div class="minititulo" align="center">HOJA DE PRESUPUESTO</div>
<BR>
<div class="capa txt_titulo" align="center">Ref. EVENTO DE INTERCAMBIO Y SOCIALIZACION DE CONOCIMIENTOS<BR><span class="txt_titulo">N° <? echo numeracion($row['n_evento']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></span></div>
<br>
<div class="capa txt_titulo" align="center"><? echo $row['taller'];?></div>

<BR>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="10%" align="center">N°</td>
    <td width="16%" align="center">TIPO DE GASTO</td>
    <td width="42%">DESCRIPCION</td>
    <td width="16%" align="center">Presupuesto Total (S/.)</td>
    <td width="16%" align="center">Presupuesto Solicitado (S/.)</td>
  </tr>
<?
$count=0;

$sql="SELECT
epd_bd_presupuesto.descripcion AS tipo_gasto,
epd_bd_presupuesto.monto,
epd_bd_presupuesto.monto_solicitado,
sys_bd_tipo_gasto.descripcion
FROM
epd_bd_presupuesto
INNER JOIN sys_bd_tipo_gasto ON sys_bd_tipo_gasto.cod_tipo_gasto = epd_bd_presupuesto.cod_tipo_gasto
WHERE
epd_bd_presupuesto.cod_evento='$cod'
ORDER BY
epd_bd_presupuesto.cod_tipo_gasto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$count++;
	$monto=$fila['monto'];
	$m_total=$m_total+$monto;
?>  
  <tr>
    <td align="center"><? echo $count;?></td>
    <td align="center"><? echo $fila['descripcion'];?></td>
    <td><? echo $fila['tipo_gasto'];?></td>
    <td align="right"><? echo number_format($fila['monto'],2);?></td>
    <td align="right"><? if ($fila['monto_solicitado']==1) echo number_format($fila['monto'],2);?></td>
  </tr>
<?
}
?>
  <tr>
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="center" class="txt_titulo">&nbsp;</td>
    <td align="center" class="txt_titulo">&nbsp;</td>
    <td align="right" class="txt_titulo"><? echo number_format($m_total,2);?></td>
    <td align="right" class="txt_titulo"><? echo number_format($row['monto_solicitado'],2);?></td>
  </tr>
</table>
<br>
<div class="capa"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>

<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="minititulo" align="center">REGISTRO DE PARTICIPANTES</div>
<br>
<div class="capa txt_titulo" align="center"><? echo $row['taller'];?></div>
<BR>
<div class="capa" >
Lugar del Evento : <? echo $row['direccion'];?>
<br>
Fecha : <? echo traducefecha($row['f_evento']);?>
</div>
<br>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr class="txt_titulo">
    <td width="4%" align="center">N°</td>
    <td width="29%" align="center">NOMBRES Y APELLIDOS</td>
    <td width="22%" align="center">INSTITUCION</td>
    <td width="17%" align="center">CARGO</td>
    <td width="12%" align="center">DNI</td>
    <td width="7%" align="center">SEXO (F/M)</td>
    <td width="9%" align="center">FIRMA</td>
  </tr>
  <tr>
    <td align="center">1</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">2</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">3</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">4</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">5</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">6</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">7</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">8</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">9</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">10</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">11</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">12</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">13</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">14</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">15</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">16</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">17</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">18</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">19</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">20</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<BR>
<DIV class="capa mini"><strong>CUADRO RESUMEN DE ASISTENTES AL EVENTO</strong></DIV>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="40%" align="center"><strong>ASISTENTES</strong></td>
    <td width="20%" align="center"><strong>VARONES</strong></td>
    <td width="21%" align="center"><strong>MUJERES</strong></td>
    <td width="19%" align="center"><strong>TOTAL</strong></td>
  </tr>
  <tr>
    <td>Autoridades Gubernamentales</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>Representantes de Juntas Directivas</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>NEC PDSS</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>Otros</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<br>
<div class="capa"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>

<?
if ($row['oficial']==1)
{
?>

<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa minititulo" align="center">
SOLICITUD Y AUTORIZACIÓN PARA EL USO DE UNIDADES DE TRANSPORTE OFICIAL 
<br>
N° <? echo numeracion($row['n_evento']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></div>
<br>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="20%">Lugar</td>
    <td width="2%">:</td>
    <td width="78%"><? echo $row['departamento'];?> - <? echo $row['provincia'];?>
      <? 
if ($row['cod_dependencia']<>001)
{
?>
- <? echo $row['distrito'];?>
<?
}
?>
- <? echo $row['direccion'];?></td>
  </tr>
  <tr>
    <td>Dependencia del Solicitante</td>
    <td>:</td>
    <td><? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>Fecha de Salida</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_evento']);?></td>
  </tr>
  <tr>
    <td>Referencia</td>
    <td>:</td>
    <td>EVENTO DE INTERCAMBIO Y SOCIALIZACION DE CONOCIMIENTOS 
N° <? echo numeracion($row['n_evento']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>Responsable</td>
    <td>:</td>
    <td><? echo $row['nombre']." ".$row['apellido']." - ".$row['cargo'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="32%"><hr></td>
    <td width="34%">&nbsp;</td>
    <td width="34%"><hr></td>
  </tr>
  <tr>
    <td align="center" valign="top">.(Sello y Firma del Solicitante) <br>
      <? echo $row['nombre']." ".$row['apellido']." - ".$row['cargo'];?></td>
    <td align="center">&nbsp;</td>
    <td align="center">AUTORIZADO<br>
      ADMINISTRACIÓN NEC-PDSS </td>
  </tr>
</table>
<br>
  <?
$sql="SELECT
sys_bd_unidad_vehiculo.cod_unidad,
sys_bd_unidad_vehiculo.unidad,
sys_bd_unidad_vehiculo.placa,
sys_bd_unidad_vehiculo.cod_dependencia,
sys_bd_personal.nombre,
sys_bd_personal.apellido
FROM
sys_bd_unidad_vehiculo
INNER JOIN sys_bd_personal ON sys_bd_personal.cod_dependencia = sys_bd_unidad_vehiculo.cod_dependencia
WHERE
sys_bd_unidad_vehiculo.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_personal.cod_cargo = 8";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
?>
</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td align="justify">SE AUTORIZA LA SALIDA DE LA UNIDAD DE TRANSPORTE: <? echo $r5['unidad'];?>, DE   IDENTIFICACIÓN: <? echo $r5['placa'];?>, PROPIEDAD DEL NEC - PROYECTO DE DESARROLLO   SIERRA SUR, EN COMISIÓN DE SERVICIO OFICIAL OPERADA POR EL CONDUCTOR: <? echo $r5['nombre']." ".$r5['apellido'];?></td>
  </tr>
</table>
<br> 
<br>
<div class="capa"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
  <?
}
?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <?
//al final de todo generamos una consulta para actualizar la ficha

	//Si es el caso actualizamos la info de la ficha de iniciativas
	$sql="SELECT cod_ficha_iniciativa FROM se_ficha_iniciativa WHERE cod_iniciativa='$cod' AND cod_tipo_iniciativa=1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	$sql="UPDATE se_ficha_iniciativa SET monto_solicitado='".$row['monto_solicitado']."',monto_desembolsado='".$row['monto_solicitado']."' WHERE cod_ficha_iniciativa='".$r2['cod_ficha_iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());


?>
  
</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    <button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
    <a href="../contratos/evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Retornar al panel principal</a>
    

    </td>
  </tr>
</table>
</body>
</html>