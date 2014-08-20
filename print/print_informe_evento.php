<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT epd_informe_evento.aut_var, 
	epd_informe_evento.aut_muj, 
	epd_informe_evento.dir_var, 
	epd_informe_evento.dir_muj, 
	epd_informe_evento.otr_var, 
	epd_informe_evento.otr_muj, 
	epd_informe_evento.pdss_var, 
	epd_informe_evento.pdss_muj, 
	epd_informe_evento.cod_dj_evento, 
	epd_informe_evento.aporte_recibido, 
	epd_informe_evento.monto_devuelto, 
	epd_informe_evento.resultado, 
	epd_informe_evento.comentario, 
	epd_informe_evento.f_informe, 
	sys_bd_personal.nombre, 
	sys_bd_personal.apellido, 
	sys_bd_personal.n_documento, 
	sys_bd_cargo.descripcion AS cargo, 
	epd_bd_demanda.cod_evento, 
	epd_bd_demanda.n_evento, 
	epd_bd_demanda.nombre AS taller, 
	epd_bd_demanda.f_evento, 
	epd_bd_demanda.objetivo, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	epd_bd_demanda.direccion, 
	sys_bd_dependencia.cod_dependencia, 
	sys_bd_dependencia.nombre AS dependencia, 
	sys_bd_dependencia.ubicacion, 
	sys_bd_componente_poa.codigo AS codigo_componente, 
	sys_bd_componente_poa.nombre AS nombre_componente, 
	sys_bd_subactividad_poa.codigo AS codigo_poa, 
	sys_bd_subactividad_poa.nombre AS nombre_poa, 
	epd_bd_demanda.cod_tipo_doc_solicitante, 
	epd_bd_demanda.n_doc_solicitante, 
	sys_bd_categoria_poa.codigo, 
	sys_bd_categoria_poa.nombre AS categoria
FROM epd_informe_evento INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = epd_informe_evento.persona_dirigido
	 INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
	 INNER JOIN epd_bd_demanda ON epd_bd_demanda.cod_evento = epd_informe_evento.cod_evento
	 LEFT JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = epd_bd_demanda.cod_categoria
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = epd_bd_demanda.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = epd_bd_demanda.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = epd_bd_demanda.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = epd_bd_demanda.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = epd_bd_demanda.cod_poa
WHERE epd_informe_evento.cod_rendicion ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//RESPONSABLE DEL EVENTO
$sql="SELECT
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion
FROM
sys_bd_personal
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
WHERE
sys_bd_personal.cod_tipo_doc='".$row['cod_tipo_doc_solicitante']."' AND
sys_bd_personal.n_documento='".$row['n_doc_solicitante']."'";
$result=mysql_query($sql) or die (mysql_error());
$f7=mysql_fetch_array($result);




//vemos los montos
//a.- aporte municipio
$sql="SELECT
Sum(epd_participante_evento.aporte) AS aporte
FROM
epd_participante_evento
WHERE
epd_participante_evento.tipo = 1 AND
epd_participante_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//b.- aporte mina
$sql="SELECT
Sum(epd_participante_evento.aporte) AS aporte
FROM
epd_participante_evento
WHERE
epd_participante_evento.tipo = 2 AND
epd_participante_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);

//c.- aporte otro
$sql="SELECT
Sum(epd_participante_evento.aporte) AS aporte
FROM
epd_participante_evento
WHERE
epd_participante_evento.tipo = 0 AND
epd_participante_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);




//1.- monto con documentacion
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS monto_pdss
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

//2.- monto de declaracion jurada
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS monto_dj
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$row2=mysql_fetch_array($result);


//Montos y porcentajes
$pdss=$row1['monto_pdss']+$row2['monto_dj'];
$total=$pdss+$f1['aporte']+$f2['aporte']+$f3['aporte'];
$otro_aporte=$f1['aporte']+$f2['aporte']+$f3['aporte'];

$ppdss=($pdss/$total)*100;
$pmun=($f1['aporte']/$total)*100;
$pmin=($f2['aporte']/$total)*100;
$potr=($f3['aporte']/$total)*100;



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

<div class="txt_titulo" align="center"><u>INFORME</u><br>EVENTO DE INTERCAMBIO Y SOCIALIZACION DE CONOCIMIENTOS<BR>
<? echo "N° ".numeracion($row['n_evento'])." - ".periodo($row['f_informe'])." - ".$row['dependencia'];?></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%">A</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['nombre']." ".$row['apellido']." - ".$row['cargo'];?></td>
  </tr>
  <tr>
    <td>Asunto </td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo "INFORME DE ACTIVIDADES DEL EVENTO";?></td>
  </tr>
  <tr>
    <td>Referencia</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo "PROPUESTA N° ".numeracion($row['n_evento'])." - ".periodo($row['f_evento'])." - ".$row['dependencia'];?></td>
  </tr>
  <tr>
    <td>Lugar y Fecha</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['ubicacion'];?>, <? echo traducefecha($row['f_informe']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACION GENERAL</td>
  </tr>
  <tr>
    <td>Nombre del Evento</td>
    <td align="center">:</td>
    <td><? echo $row['taller'];?></td>
  </tr>
  <tr>
    <td>Fecha de realización</td>
    <td align="center">:</td>
    <td><? echo traducefecha($row['f_evento']);?></td>
  </tr>
  <tr>
    <td>Lugar del evento</td>
    <td align="center">:</td>
    <td><? echo $row['departamento'];?> - <? echo $row['provincia'];?> 
<?
if ($row['cod_dependencia']<>001)
{
   
 echo " - ".$row['distrito'];
}
 ?>
     
      - <? echo $row['direccion'];?></td>
  </tr>
  <tr>
    <td>Oficina Responsable</td>
    <td align="center">:</td>
    <td><? echo $row['dependencia'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">II.- OBJETIVO DEL EVENTO</td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['objetivo'];?></td>
  </tr>
  <tr>
    <td colspan="3"><span class="txt_titulo">III.- RESULTADOS OBTENIDOS</span></td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['resultado'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">IV.- PARTICIPANTES</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="40%" align="center"><strong>ASISTENTES</strong></td>
    <td width="20%" align="center"><strong>VARONES</strong></td>
    <td width="21%" align="center"><strong>MUJERES</strong></td>
    <td width="19%" align="center"><strong>TOTAL</strong></td>
  </tr>
  <tr>
    <td>Autoridades Gubernamentales</td>
    <td align="right"><? echo $row['aut_var'];?></td>
    <td align="right"><? echo $row['aut_muj'];?></td>
    <td align="right"><? echo $row['aut_var']+$row['aut_muj'];?></td>
  </tr>
  <tr>
    <td>Representantes de Juntas Directivas</td>
    <td align="right"><? echo $row['dir_var'];?></td>
    <td align="right"><? echo $row['dir_muj'];?></td>
    <td align="right"><? echo $row['dir_var']+$row['dir_muj'];?></td>
  </tr>
  <tr>
    <td>Representantes del NEC PDSS II</td>
    <td align="right"><? echo $row['pdss_var'];?></td>
    <td align="right"><? echo $row['pdss_muj'];?></td>
    <td align="right"><? echo $row['pdss_var']+$row['dir_muj'];?></td>
  </tr>
  <tr>
    <td>Otros</td>
    <td align="right"><? echo $row['otr_var'];?></td>
    <td align="right"><? echo $row['otr_muj'];?></td>
    <td align="right"><? echo $row['otr_var']+$row['otr_muj'];?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo $row['aut_var']+$row['dir_var']+$row['otr_var']+$row['pdss_var'];?></td>
    <td align="right"><? echo $row['aut_muj']+$row['dir_muj']+$row['otr_muj']+$row['pdss_muj'];?></td>
    <td align="right"><? echo $row['aut_muj']+$row['dir_muj']+$row['otr_muj']+$row['aut_var']+$row['dir_var']+$row['otr_var']+$row['pdss_var']+$row['pdss_muj'];?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td><span class="txt_titulo">V.- PRESUPUESTO Y FINANCIAMIENTO EJECUTADO</span></td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="65%" align="center"><strong>ENTIDAD</strong></td>
    <td width="17%" align="center"><strong>Monto Ejecutado (S/.)</strong></td>
    <td width="18%" align="center"><strong>%</strong></td>
  </tr>

  <tr>
    <td>NEC PDSS</td>
    <td align="right"><? echo number_format($pdss,2);?></td>
    <td align="right"><? echo number_format($ppdss,2);?></td>
  </tr>
  <tr>
    <td>Municipio</td>
    <td align="right"><? echo number_format($f1['aporte'],2);?></td>
    <td align="right"><? echo number_format($pmun,2);?></td>
  </tr>
  <tr>
    <td>Minera</td>
    <td align="right"><? echo number_format($f2['aporte'],2);?></td>
    <td align="right"><? echo number_format($pmin,2);?></td>
  </tr>
  <tr>
    <td>Otros aportes</td>
    <td align="right"><? echo number_format($f3['aporte'],2);?></td>
    <td align="right"><? echo number_format($potr,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL EJECUTADO</strong></td>
    <td align="right"><? echo number_format($total,2);?></td>
    <td align="right"><? echo number_format($ppdss+$pmun+$pmin+$potr,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">IV.- COMENTARIOS/OBSERVACIONES</td>
  </tr>
  <tr>
    <td><? echo $row['comentario'];?></td>
  </tr>
</table>

<br>

<div class="capa">Atentamente, </div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="27%">&nbsp;</td>
    <td width="40%"><hr></td>
    <td width="33%">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $f7['nombre']." ".$f7['apellido']."<br> DNI N° ".$f7['n_documento'];?></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">RENDICIÓN DE CUENTAS<BR>
<span class="capa txt_titulo"><? echo "REFERENCIA : INFORME N° ".numeracion($row['n_evento'])." - ".periodo($row['f_informe'])." - ".$row['dependencia'];?></span></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%">Responsable del Evento</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $f7['nombre']." ".$f7['apellido']." - ".$f7['descripcion'];?></td>
  </tr>
  <tr>
    <td>Componente</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['codigo_componente']." - ".$row['nombre_componente'];?></td>
  </tr>
  <tr>
    <td>Categoria de Gasto</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['codigo']." - ".$row['categoria'];?></td>
  </tr>
  <tr>
    <td>Codigo POA</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['codigo_poa']." - ".$row['nombre_poa'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<BR>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr class="txt_titulo">
    <td width="7%" rowspan="2" align="center"><span class="mini">FECHA</span></td>
    <td colspan="3" align="center"><span class="mini">DOCUMENTOS</span></td>
    <td colspan="8" align="center"><span class="mini">IMPORTE</span></td>
  </tr>
  <tr class="txt_titulo">
    <td width="7%" align="center"><span class="mini">CLASE</span></td>
    <td width="7%" align="center"><span class="mini">N°</span></td>
    <td width="28%" align="center"><span class="mini">PROVEEDOR</span></td>
    <td width="8%" align="center"><span class="mini">Hosp.</span></td>
    <td width="7%" align="center"><span class="mini">Alim.</span></td>
    <td width="7%" align="center"><span class="mini">Pasaj.</span></td>
    <td width="7%" align="center" class="mini">Comb.</td>
    <td width="6%" align="center" class="mini">Serv.</td>
    <td width="7%" align="center" class="mini">Mater.</td>
    <td width="4%" align="center" class="mini">Alq.</td>
    <td width="5%" align="center"><span class="mini">Otro</span></td>
  </tr>
  <?
$sql="SELECT
epd_rendicion_evento.f_detalle,
epd_rendicion_evento.cod_tipo_gasto,
epd_rendicion_evento.serie,
epd_rendicion_evento.numero,
epd_rendicion_evento.proveedor,
epd_rendicion_evento.concepto,
sys_bd_tipo_importe.descripcion AS importe,
epd_rendicion_evento.monto
FROM
epd_rendicion_evento
INNER JOIN sys_bd_tipo_importe ON sys_bd_tipo_importe.cod_tipo_importe = epd_rendicion_evento.cod_tipo_importe
WHERE
epd_rendicion_evento.cod_rendicion='$cod'
ORDER BY
epd_rendicion_evento.f_detalle ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>
  <tr class="mini">
    <td align="center"><? echo fecha_normal($fila['f_detalle']);?></td>
    <td align="center"><? echo $fila['importe'];?></td>
    <td align="center"><? echo $fila['numero'];?></td>
    <td><? echo $fila['proveedor'];?></td>
    <td align="right"><? if ($fila['cod_tipo_gasto']==3) echo number_format($fila['monto'],2);?></td>
    <td align="right"><? if ($fila['cod_tipo_gasto']==1) echo number_format($fila['monto'],2);?></td>
    <td align="right"><? if ($fila['cod_tipo_gasto']==2) echo number_format($fila['monto'],2);?></td>
    <td align="right" class="mini"><? if ($fila['cod_tipo_gasto']==6) echo number_format($fila['monto'],2);?></td>
    <td align="right" class="mini"><? if ($fila['cod_tipo_gasto']==5) echo number_format($fila['monto'],2);?></td>
    <td align="right" class="mini"><? if ($fila['cod_tipo_gasto']==4) echo number_format($fila['monto'],2);?></td>
    <td align="right" class="mini"><? if ($fila['cod_tipo_gasto']==7) echo number_format($fila['monto'],2);?></td>
    <td align="right"><? if ($fila['cod_tipo_gasto']==0) echo number_format($fila['monto'],2);?></td>
  </tr>
  <?
}

?>
  <?
//Hospedaje
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=3 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//Alimentacion
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=1 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//Movilidad
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=2 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//Materiales
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=4 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);


//Servicios
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=5 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);

//Combustible
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=6 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r10=mysql_fetch_array($result);

//Otros
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=0 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r11=mysql_fetch_array($result);

//Alquiler
$sql="SELECT
Sum(epd_rendicion_evento.monto) AS total
FROM
epd_rendicion_evento
WHERE
epd_rendicion_evento.cod_tipo_gasto=7 AND
epd_rendicion_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r15=mysql_fetch_array($result);
?>
  <tr>
    <td colspan="4" align="center" class="mini"><strong>TOTAL</strong></td>
    <td align="right"><span class="mini"><? echo number_format($r1['total'],2);?></span></td>
    <td align="right"><span class="mini"><? echo number_format($r2['total'],2);?></span></td>
    <td align="right"><span class="mini"><? echo number_format($r3['total'],2);?></span></td>
    <td align="right" class="mini"><? echo number_format($r10['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($r9['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($r4['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($r15['total'],2);?></td>
    <td align="right"><span class="mini"><? echo number_format($r11['total'],2);?></span></td>
  </tr>
</table>
<?

//alimentacion
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 1 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//movilidad
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 2 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

//hospedaje
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 3 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

//otro
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 0 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r8=mysql_fetch_array($result);


//materiales
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 4 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r12=mysql_fetch_array($result);

//servicios
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 5 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r13=mysql_fetch_array($result);

//combustible
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 6 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj_evento']."'";
$result=mysql_query($sql) or die (mysql_error());
$r14=mysql_fetch_array($result);
?>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" align="center" class="txt_titulo">DECLARACION JURADA</td>
  </tr>
  <tr>
    <td width="60%"><span class="mini">Total en Hospedaje</span></td>
    <td width="21%" align="center" class="txt_titulo">S/.</td>
    <td width="19%" align="right" class="mini"><? echo number_format($r7['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Alimentación</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r5['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Movilidad local</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r6['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Combustible</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r14['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Materiales</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r12['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Servicios</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r13['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Otros</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r8['total'],2);?></td>
  </tr>
  <tr>
    <td class="txt_titulo"><span class="mini">TOTAL</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r5['total']+$r6['total']+$r7['total']+$r8['total'],2);?></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="txt_titulo"><span class="mini">SON : <? echo number_format($r5['total']+$r6['total']+$r7['total']+$r8['total'],2);?> NUEVOS SOLES</span></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="txt_titulo"><hr></td>
  </tr>
</table>
<table width="90%" border="0"  align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="60%" class="mini">TOTAL GASTADO EN HOSPEDAJE </td>
    <td width="21%" align="center" class="txt_titulo">S/.</td>
    <td width="19%" align="right" class="mini"><? echo number_format($r1['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN ALIMENTACIÓN </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r2['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN MOVILIDAD LOCAL </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r3['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN COMBUSTIBLE</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r10['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN MATERIALES</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r4['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN SERVICIOS</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r9['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN ALQUILERES</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r15['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN OTROS </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r11['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO SEGÚN DECLARACIÓN JURADA </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($row2['monto_dj'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($pdss,2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL ENTREGADO POR ADMINISTRACION</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($row['aporte_recibido'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL DEVUELTO SEGUN VOUCHER</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($row['monto_devuelto'],2);?></td>
  </tr>
  <tr>
    <td class="mini">DIFERENCIA (EN CONTRA) </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini">
	<? 
	$aporte_a=$row['monto_devuelto']+$pdss;
	$aporte_b=$row['aporte_recibido'];
	$diferencia=$aporte_b-$aporte_a;
	
	echo number_format($diferencia,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="33%">Lugar y Fecha</td>
    <td width="3%">:</td>
    <td width="64%"><? echo $row['ubicacion'];?>, <? echo traducefecha($row['f_informe']);?></td>
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
    <td align="center" valign="top"><? echo $f7['nombre']." ".$f7['apellido']."<br> DNI N° ".$f7['n_documento'];?></td>
    <td align="center">&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?
$sql="SELECT
epd_participante_evento.nombre
FROM
epd_participante_evento
WHERE
epd_participante_evento.cod_rendicion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<p></p>
<div class="minititulo" align="center">REGISTRO DE VALORIZACIÓN DE APORTES</div>
<BR>
<div class="capa txt_titulo" align="center">REFERENCIA: INFORME <? echo "N° ".numeracion($row['n_evento'])." - ".periodo($row['f_informe'])." - ".$row['dependencia'];?></div>
<br>
<div class="capa txt_titulo" align="center"><? echo $row['taller'];?></div>
<br>
<br>
<div class="capa txt_titulo">ENTIDAD: <? echo $f4['nombre'];?></div>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
  <tr class="txt_titulo">
    <td width="6%" align="center">N°</td>
    <td width="76%">CONCEPTO</td>
    <td width="18%" align="center">MONTO(S/.)</td>
  </tr>
  <tr>
    <td align="center">1</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">2</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">3</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">4</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">5</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">6</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">7</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">8</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">9</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">10</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">11</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">12</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">13</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">14</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">15</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">16</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">17</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">18</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">19</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">20</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr align="center">
    <td colspan="2">TOTAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;
</p>
<div class="capa" align="right"><? echo $row['dependencia'].", ".traducefecha($row['f_informe']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="25%"><hr></td>
    <td width="50%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">FIRMA Y SELLO DEL FUNCIONARIO COMPETENTE</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?
}
?>


<?
//por ultimo actualizamos nuestra ficha
$sql="SELECT cod_ficha_iniciativa FROM se_ficha_iniciativa WHERE cod_iniciativa='".$row['cod_evento']."' AND cod_tipo_iniciativa=1";
$result=mysql_query($sql) or die (mysql_error());
$f13=mysql_fetch_array($result);

$sql="UPDATE se_ficha_iniciativa SET monto_rendido='$pdss',monto_otros='$otro_aporte' WHERE cod_ficha_iniciativa='".$f13['cod_ficha_iniciativa']."'";
$result=mysql_query($sql) or die (mysql_error());

?>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=rinde" class="secondary button oculto">Retornar el menu principal</a>

    
    
    </td>
  </tr>
</table>
</body>
</html>