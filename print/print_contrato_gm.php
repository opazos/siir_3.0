<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.- Jalamos el dato de la gira
$sql="SELECT gm_ficha_evento.n_ficha_gm, 
	gm_ficha_evento.tema, 
	gm_ficha_evento.f_inicio, 
	gm_ficha_evento.f_termino, 
	gm_ficha_evento.dias, 
	gm_ficha_evento.f_propuesta, 
	gm_ficha_evento.f_conformidad, 
	gm_ficha_evento.objetivo, 
	gm_ficha_evento.resultado, 
	gm_ficha_evento.participantes, 
	sys_bd_componente_poa.codigo AS codigo_componente, 
	sys_bd_componente_poa.nombre AS nombre_componente, 
	sys_bd_subactividad_poa.codigo AS codigo_subactividad, 
	sys_bd_subactividad_poa.nombre AS nombre_subactividad, 
	gm_ficha_evento.n_contrato, 
	gm_ficha_evento.n_atf, 
	gm_ficha_evento.cof_fida, 
	gm_ficha_evento.cof_ro, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.ubicacion, 
	sys_bd_personal.n_documento, 
	sys_bd_personal.nombre, 
	sys_bd_personal.apellido, 
	sys_bd_cargo.descripcion AS cargo, 
	gm_ficha_evento.f_presentacion, 
	Sum(gm_ficha_presupuesto.costo_total) AS monto_total, 
	gm_ficha_contratante.dni_1, 
	gm_ficha_contratante.representante_1, 
	gm_ficha_contratante.cargo_1, 
	gm_ficha_contratante.dni_2, 
	gm_ficha_contratante.representante_2, 
	gm_ficha_contratante.cargo_2, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.n_documento AS rrpp, 
	org_ficha_organizacion.cod_tipo_org, 
	org_ficha_organizacion.cod_tipo_doc, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	gm_ficha_contratante.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	sys_bd_tipo_iniciativa.codigo_iniciativa AS tipo_iniciativa, 
	sys_bd_dependencia.departamento AS depa, 
	sys_bd_dependencia.provincia AS provi, 
	sys_bd_dependencia.direccion AS dir, 
	gm_ficha_evento.cod_estado_iniciativa, 
	sys_bd_categoria_poa.codigo, 
	sys_bd_categoria_poa.nombre AS categoria
FROM gm_ficha_evento INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = gm_ficha_evento.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = gm_ficha_evento.cod_subactividad
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gm_ficha_evento.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
	 INNER JOIN gm_ficha_presupuesto ON gm_ficha_presupuesto.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
	 INNER JOIN gm_ficha_contratante ON gm_ficha_contratante.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gm_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = gm_ficha_contratante.n_documento
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = gm_ficha_contratante.cod_ifi
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gm_ficha_evento.cod_tipo_iniciativa
	 LEFT JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = gm_ficha_evento.cod_categoria
WHERE gm_ficha_presupuesto.cod_ficha_gm='$cod' AND
gm_ficha_contratante.contratante = 1
GROUP BY gm_ficha_evento.n_ficha_gm,
gm_ficha_evento.tema,
gm_ficha_evento.f_inicio,
gm_ficha_evento.f_termino,
gm_ficha_evento.dias,
gm_ficha_evento.objetivo,
gm_ficha_evento.resultado,
gm_ficha_evento.participantes,
sys_bd_componente_poa.codigo,
sys_bd_componente_poa.nombre,
sys_bd_subactividad_poa.codigo,
sys_bd_subactividad_poa.nombre,
sys_bd_categoria_poa.codigo,
sys_bd_categoria_poa.nombre,
gm_ficha_evento.n_contrato,
gm_ficha_evento.n_atf,
sys_bd_dependencia.nombre,
sys_bd_dependencia.ubicacion,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion,
gm_ficha_evento.f_presentacion";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//monto pdss
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS pdss
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod' AND
gm_ficha_presupuesto.cod_entidad = 1";
$result=mysql_query($sql) or die (mysql_error());
$row_1=mysql_fetch_array($result);

//monto organizacion
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS org
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod' AND
gm_ficha_presupuesto.cod_entidad = 2";
$result=mysql_query($sql) or die (mysql_error());
$row_2=mysql_fetch_array($result);

//monto otro
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS otro
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod' AND
gm_ficha_presupuesto.cod_entidad = 3 OR
gm_ficha_presupuesto.cod_entidad = 4";
$result=mysql_query($sql) or die (mysql_error());
$row_3=mysql_fetch_array($result);


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
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE COFINANCIAMIENTO</u><br>
    GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS<br>
    N° <? echo numeracion($row['n_ficha_gm']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACIÓN GENERAL</td>
  </tr>
  <tr>
    <td width="28%">Tema de la Gira</td>
    <td width="2%">:</td>
    <td width="70%"><? echo $row['tema'];?></td>
  </tr>
  <tr>
    <td>Fecha de Salida</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_inicio']);?></td>
  </tr>
  <tr>
    <td>Fecha de Retorno</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_termino']);?></td>
  </tr>
  <tr>
    <td>N° de días de duración</td>
    <td>:</td>
    <td><?  if ($row['dias']==1) echo $row['dias']." día"; else echo $row['dias']." días";?></td>
  </tr>
  <tr>
    <td>N° de Asistentes</td>
    <td>:</td>
    <td><? echo $row['participantes'];?> (Ver relación adjunta)</td>
  </tr>
  <tr>
    <td>Responsable</td>
    <td>:</td>
    <td><? echo $row['nombre']." ".$row['apellido'];?></td>
  </tr>
  <tr>
    <td>Oficina</td>
    <td>:</td>
    <td><? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">II.- INFORMACIÓN DE LA(s) ORGANIZACION(es)</td>
  </tr>
</table>
<?
$sql="SELECT
gm_ficha_contratante.dni_1,
gm_ficha_contratante.representante_1,
gm_ficha_contratante.cargo_1,
gm_ficha_contratante.dni_2,
gm_ficha_contratante.representante_2,
gm_ficha_contratante.cargo_2,

sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
gm_ficha_contratante.n_cuenta,
sys_bd_ifi.descripcion AS ifi
FROM
gm_ficha_contratante
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gm_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = gm_ficha_contratante.n_documento
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = gm_ficha_contratante.cod_ifi
WHERE
gm_ficha_contratante.cod_ficha_gm='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">N° y Tipo de documento  de identidad</td>
    <td width="2%" align="center">:</td>
    <td width="70%"><? echo $r4['n_documento']." (".$r4['tipo_doc'].")";?></td>
  </tr>
  <tr>
    <td>Nombre de la Organización</td>
    <td align="center">:</td>
    <td><? echo $r4['nombre'];?></td>
  </tr>
  <tr>
    <td>Tipo de Organización</td>
    <td align="center">:</td>
    <td><? echo $r4['tipo_org'];?></td>
  </tr>
  <tr>
    <td>Ubicación Geográfica</td>
    <td align="center">:</td>
    <td><? echo $r4['departamento']." / ".$r4['provincia']." / ".$r4['distrito'];?></td>
  </tr>
  <tr>
    <td>Direccion</td>
    <td align="center">:</td>
    <td><? echo $r4['sector'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">DATOS DE (LOS) REPRESENTANTE(S) LEGAL(ES)</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">PRIMER  REPRESENTANTE LEGAL</td>
  </tr>
  <tr>
    <td>DNI</td>
    <td align="center">:</td>
    <td><? echo $r4['dni_1'];?></td>
  </tr>
  <tr>
    <td>Nombres y apellidos</td>
    <td align="center">:</td>
    <td><? echo $r4['representante_1'];?></td>
  </tr>
  <tr>
    <td>Cargo</td>
    <td align="center">:</td>
    <td><? echo $r4['cargo_1'];?></td>
  </tr>
<?
if ($r4['dni_2']<>NULL)
{
?>  
  <tr>
    <td colspan="3" class="txt_titulo">SEGUNDO REPRESENTANTE LEGAL</td>
  </tr>
  <tr>
    <td>DNI</td>
    <td align="center">:</td>
    <td><? echo $r4['dni_2'];?></td>
  </tr>
  <tr>
    <td>Nombres y apellidos</td>
    <td align="center">:</td>
    <td><? echo $r4['representante_2'];?></td>
  </tr>
  <tr>
    <td>Cargo</td>
    <td align="center">:</td>
    <td><? echo $r4['cargo_2'];?></td>
  </tr>
<?
}
?>  
  <tr>
    <td colspan="3"><hr></td>
  </tr>

</table>

  <?
}
?>
  <br>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">III.- OBJETIVOS Y RESULTADOS ESPERADOS</td>
  </tr>
  <tr>
    <td class="txt_titulo">3.1. OBJETIVOS</td>
  </tr>
  <tr>
    <td><? echo $row['objetivo'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">3.2. RESULTADOS ESPERADOS</td>
  </tr>
  <tr>
    <td><? echo $row['resultado'];?></td>
  </tr>
</table>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">IV.- ITINERARIO DE LA GIRA</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="5%" align="center">N°</td>
    <td width="10%" align="center">FECHA</td>
    <td width="21%">INSTITUCION</td>
    <td width="21%" align="center">UBICACION GEOGRAFICA</td>
    <td width="19%" align="center">TEMATICA</td>
    <td width="24%" align="center">ACTIVIDADES A DESARROLLAR</td>
  </tr>
  <?
$n=0;
$sql="SELECT
gm_ficha_itinerario.f_itinerario,
gm_ficha_itinerario.departamento,
gm_ficha_itinerario.provincia,
gm_ficha_itinerario.distrito,
gm_ficha_itinerario.lugar,
gm_ficha_itinerario.tematica,
gm_ficha_itinerario.actividades
FROM
gm_ficha_itinerario
WHERE
gm_ficha_itinerario.cod_ficha_gm='$cod'
ORDER BY
gm_ficha_itinerario.f_itinerario ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
	$n++;
?>
  <tr>
    <td align="center"><? echo $n;?></td>
    <td align="center"><? echo fecha_normal($r2['f_itinerario']);?></td>
    <td><? echo $r2['lugar'];?></td>
    <td align="center"><? echo $r2['departamento']."/".$r2['provincia']."/".$r2['distrito'];?></td>
    <td><? echo $r2['tematica'];?></td>
    <td><? echo $r2['actividades'];?></td>
  </tr>
  <?
}
?>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" class="txt_titulo">V.- COFINANCIAMIENTO SOLICITADO (Ver hoja de presupuesto adjunta)</td>
    <td>&nbsp;</td>
  </tr>
</table>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td width="62%" align="center">ENTIDAD</td>
    <td width="20%" align="center">MONTO(S/.)</td>
    <td width="18%" align="center">%</td>
  </tr>
<?
$sql="SELECT
sys_bd_ente_cofinanciador.descripcion,
Sum(gm_ficha_presupuesto.costo_total) AS monto
FROM
gm_ficha_presupuesto
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = gm_ficha_presupuesto.cod_entidad
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod'
GROUP BY
sys_bd_ente_cofinanciador.descripcion";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
	$total=$r1['monto'];
	$total_monto=$total_monto+$total;
	
?>
  <tr>
    <td><? echo $r1['descripcion'];?></td>
    <td align="right"><? echo number_format($r1['monto'],2);?></td>
    <td align="right">
    <?
	@$pp=($r1['monto']/$row['monto_total'])*100;
	echo number_format(@$pp,2);
	?>
    </td>
  </tr>
<?
}
?>
  <tr class="txt_titulo">
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($total_monto,2);?></td>
    <td align="right">	
	<? 
	@$pp_total=($total_monto/$row['monto_total'])*100;
	echo number_format(@$pp_total,2);
	?>
    </td>
  </tr>
</table>
<br>
<div class="capa" align="right"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" align="center"><strong>Solicitante</strong></td>
    <td width="29%" align="center">&nbsp;</td>
    <td width="36%" align="center"><strong>Autorización</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%">&nbsp;</td>
  </tr>
  <tr>
    <td><hr></td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>".$row['cargo'];?></td>
    <td>&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="minititulo" align="center">HOJA DE PRESUPUESTO</div>
<BR>
<div class="capa txt_titulo" align="center"> Ref. GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS<br>N° <? echo numeracion($row['n_ficha_gm']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></div>

<br>
<div class="capa txt_titulo" align="center"><? echo $row['evento'];?></div>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="3%" align="center"><strong>N°</strong></td>
    <td width="6%" align="center"><strong>CONCEPTO</strong></td>
    <td width="8%" align="center"><strong>ENTE COFINANCIADOR</strong></td>
    <td width="38%" align="center"><strong>DETALLE</strong></td>
    <td width="11%" align="center"><strong>UNIDAD</strong></td>
    <td width="10%" align="center"><strong>CANTIDAD</strong></td>
    <td width="12%" align="center"><strong>PRECIO UNITARIO (S/.)</strong></td>
    <td width="12%" align="center"><strong>COSTO TOTAL (S/.)</strong></td>
  </tr>
<?
$n1=0;
$sql="SELECT
sys_bd_tipo_gasto.descripcion AS tipo_gasto,
sys_bd_ente_cofinanciador.descripcion AS entidad,
gm_ficha_presupuesto.detalle,
gm_ficha_presupuesto.unidad,
gm_ficha_presupuesto.cantidad,
gm_ficha_presupuesto.costo_unitario,
gm_ficha_presupuesto.costo_total
FROM
gm_ficha_presupuesto
INNER JOIN sys_bd_tipo_gasto ON sys_bd_tipo_gasto.cod_tipo_gasto = gm_ficha_presupuesto.cod_tipo_gasto
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = gm_ficha_presupuesto.cod_entidad
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod'
ORDER BY
gm_ficha_presupuesto.cod_tipo_gasto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
	$n1++;
	$presupuesto=$r3['costo_total'];
	$t_pres=$t_pres+$presupuesto;
?>
  <tr>
    <td align="center"><? echo $n1;?></td>
    <td align="center"><? echo $r3['tipo_gasto'];?></td>
    <td align="center"><? echo $r3['entidad'];?></td>
    <td><? echo $r3['detalle'];?></td>
    <td align="center"><? echo $r3['unidad'];?></td>
    <td align="right"><? echo number_format($r3['cantidad'],2);?></td>
    <td align="right"><? echo number_format($r3['costo_unitario'],2);?></td>
    <td align="right"><? echo number_format($r3['costo_total'],2);?></td>
  </tr>
 <?
}
?>
  <tr>
    <td colspan="7"><strong>TOTAL</strong></td>
    <td align="right"><strong><? echo number_format($t_pres,2);?></strong></td>
  </tr>
</table>
<br>
<div class="capa" align="right"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="minititulo" align="center">RELACION DE PARTICIPANTES</div>
<br>
<div class="capa txt_titulo" align="center"> Ref. GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS<br>N° <? echo numeracion($row['n_ficha_gm']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></div>

<br>
<div class="capa txt_titulo" align="center"><? echo $row['evento'];?></div>
<br>
<div class="capa" >
Fecha del evento: <? echo traducefecha($row['f_inicio']);?>
</div>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr class="txt_titulo">
    <td width="4%" align="center">N°</td>
    <td width="29%" align="center">NOMBRES Y APELLIDOS</td>
    <td width="22%" align="center">INSTITUCION</td>
    <td width="13%" align="center">CARGO</td>
    <td width="8%" align="center">DNI</td>
    <td width="8%" align="center">EDAD</td>
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
    <td>&nbsp;</td>
  </tr>
</table>
<BR>
<DIV class="capa mini"><strong>CUADRO RESUMEN DE ASISTENTES A LA GIRA MOTIVACIONAL</strong></DIV>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="47%" align="center"><strong>ASISTENTES</strong></td>
    <td width="15%" align="center"><strong>VARONES</strong></td>
    <td width="14%" align="center"><strong>MUJERES</strong></td>
    <td width="12%" align="center"><strong>TOTAL</strong></td>
    <td width="12%" align="center"><strong>TOTAL MENORES DE 30 AÑOS</strong></td>
  </tr>
  <tr>
    <td>Autoridades Gubernamentales</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>Representantes de Juntas Directivas</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>Otros</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<br>
<div class="capa" align="right"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>



<div class="capa txt_titulo" align="center">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo $row['tipo_iniciativa'];?> – <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?><br>
DE DONACIÓN CON CARGO, ENTRE EL NEC-PROYECTO DE DESARROLLO SIERRA SUR Y LA  <? echo $row['organizacion'];?>, PARA REALIZAR UNA GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS.
</div>


<?php 
if ($row['cod_estado_iniciativa']==000)
{
?>
<center>
<div class="capa_borde centrado error gran_titulo" >CONTRATO ANULADO</div>
</center>
<?php 
}
?>



<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="justify"><p>Conste por el presente  documento, el Contrato de Donación con Cargo para realizar una Gira de Aprendizaje e Intercambio de Conocimientos,<strong></strong>que  celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO  SIERRA SUR II<strong>, </strong>con RUC Nº 20456188118,<strong> </strong>con domicilio legal en <? echo $row['dir'];?> en el  Distrito de <? echo $row['ubicacion'];?>, Provincia de  <? echo $row['provi'];?> y  Departamento de <? echo $row['depa'];?>, a quien en  adelante se le denominará <strong>SIERRA SUR II </strong> representado por el <? echo $row['cargo'];?>, <? echo $row['nombre']." ".$row['apellido'];?>, con DNI. Nº <? echo $row['n_documento'];?>, y de otra  parte la  <? echo $row['organizacion'];?>, con <? echo $row['tipo_doc'];?> N° <? echo $row['rrpp'];?>, con  domicilio real en <? echo $row['sector'];?>, del Distrito  de <? echo $row['distrito'];?>, Provincia de  <? echo $row['provincia'];?>, Departamento  de <? echo $row['departamento'];?>, a quien en  adelante se le denominará <strong>LA  ORGANIZACIÓN, </strong>representada por su <? echo $row['cargo_1'];?>, <? echo $row['representante_1'];?><strong> </strong>identificado  con DNI  Nº <? echo $row['dni_1'];?>  
<?
if ($row['cargo_2']<>NULL)
{
?>    
    y su <? echo $row['cargo_2'];?>, <? echo $row['representante_2'];?> identificado con DNI. N°<? echo $row['dni_2'];?>
, quienes se  constituyen como responsables solidarios; 
<?
}
else
{
?>
, quien se constituye como responsable solidario;
<?
}
?>
en los términos y condiciones  establecidos en las cláusulas siguientes: </p></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>ANTECEDENTES</u></strong><strong>:</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">PRIMERO</td>
  </tr>
  <tr>
    <td align="justify"><strong>SIERRA  SUR II</strong>, es un ente  colectivo de naturaleza temporal cuya meta es reducir los niveles de pobreza en  15,911 familias pobres rurales de la Sierra Sur con un aumento sostenido de sus  activos humanos, naturales, físicos, financieros, culturales y sociales;  administra los recursos económicos provenientes del Convenio de Préstamo N°  799-PE, firmado entre el Fondo Internacional de Desarrollo Agrícola – FIDA y la  República del Perú.  Dichos recursos son  transferidos a SIERRA SUR, a través de Agrorural del Ministerio de Agricultura  – MINAG, en virtud del Decreto Supremo N° 014-2008-AG.</td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">SEGUNDO</td>
  </tr>
  <tr>
    <td align="justify"><strong>LA ORGANIZACIÓN</strong>, es una  persona jurídica, que con fecha <? echo traducefecha($row['f_propuesta']);?>, ha presentado a la Oficina Local de <? echo $row['oficina'];?> su solicitud de  cofinanciamiento de una propuesta para la realización de una Gira de Aprendizaje e Intercambio de Conocimientos a: </td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td width="36%" align="center">INSTITUCION</td>
    <td width="24%" align="center">DEPARTAMENTO</td>
    <td width="20%" align="center">PROVINCIA</td>
    <td width="20%" align="center">DISTRITO</td>
  </tr>
<?
$sql="SELECT
gm_ficha_itinerario.lugar,
gm_ficha_itinerario.departamento,
gm_ficha_itinerario.provincia,
gm_ficha_itinerario.distrito
FROM
gm_ficha_itinerario
WHERE
gm_ficha_itinerario.cod_ficha_gm='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r5=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><? echo $r5['lugar'];?></td>
    <td align="center"><? echo $r5['departamento'];?></td>
    <td align="center"><? echo $r5['provincia'];?></td>
    <td align="center"><? echo $r5['distrito'];?></td>
  </tr>
<?
}
?>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="justify">la misma  cuenta con la  aceptación de la(s) entidad(es) a visitar.  <strong>LA ORGANIZACIÓN</strong> participará con <? echo $row['participantes'];?>  socios.</td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">TERCERO</td>
  </tr>
  <tr>
    <td align="justify">La   Oficina Local de <? echo $row['oficina'];?>, con fecha <? echo traducefecha($row['f_conformidad']);?> ha emitido  opinión favorable respecto a la solicitud y propuesta de cofinanciamiento  presentada por <strong>LA ORGANIZACIÓN.</strong><strong><u></u></strong></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>OBJETO  DEL CONTRATO</u></strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">CUARTO</td>
  </tr>
  <tr>
    <td align="justify">El Objeto del presente contrato es el  cofinanciamiento entre las partes de la Gira de Aprendizaje e Intercambio de Conocimientos, para que <strong>LA   ORGANIZACIÓN</strong> a través   de sus socios participe y adquiera experiencias en <? echo $row['tema'];?>  y de esta manera dinamizar las actividades de  su organización, promoviendo, impulsando y replicando el intercambio de  experiencias entre sus socios. El cofinanciamiento otorgado por <strong>SIERRA SUR II</strong> tiene carácter de donación  con cargo.</td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>VIGENCIA DEL  CONTRATO.</u></strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">QUINTO</td>
  </tr>
  <tr>
    <td align="justify">La vigencia del presente contrato es de 30 días; rige a partir del  desembolso realizado por <strong>SIERRA SUR II</strong>,  y culmina con la presentación del Informe Técnico - Administrativo por parte de <strong>LA ORGANIZACIÓN</strong></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>DEL  COFINANCIAMIENTO.</u></strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">SEXTO</td>
  </tr>
  <tr>
    <td align="justify">El cofinanciamiento se regirá por el siguiente  programa de desembolso:</td>
  </tr>
</table>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td colspan="3" align="center">PROGRAMA DE DESEMBOLSOS</td>
  </tr>
  <tr class="txt_titulo">
    <td width="62%" align="center">DESEMBOLSO</td>
    <td width="20%" align="center">APORTE PORCENTAJE (%)</td>
    <td width="18%" align="center">APORTE EN MONEDA NACIONAL S/.</td>
  </tr>
  <?
$sql="SELECT
sys_bd_ente_cofinanciador.descripcion,
Sum(gm_ficha_presupuesto.costo_total) AS monto
FROM
gm_ficha_presupuesto
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = gm_ficha_presupuesto.cod_entidad
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod'
GROUP BY
sys_bd_ente_cofinanciador.descripcion";
$result=mysql_query($sql) or die (mysql_error());
while($r6=mysql_fetch_array($result))
{
	$total1=$r6['monto'];
	$total_monto1=$total_monto1+$total1;
	
?>
  <tr>
    <td><? echo $r6['descripcion'];?></td>
    <td align="right"><?
	@$pp=($r6['monto']/$row['monto_total'])*100;
	echo number_format(@$pp,2);
	?></td>
    <td align="right"><? echo number_format($r6['monto'],2);?></td>
  </tr>
  <?
}
?>
  <tr class="txt_titulo">
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? 

	echo number_format(@$pp_total,2);
	?></td>
    <td align="right"><? echo number_format($total_monto1,2);?></td>
  </tr>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td><p><strong><u>COMPROMISOS  DE LAS PARTES</u></strong></p></td>
  </tr>
  <tr>
    <td class="txt_titulo">SEPTIMO</td>
  </tr>
  <tr>
    <td align="justify"><strong>SIERRA SUR II</strong>, se  compromete a efectuar el desembolso, que le corresponde, referido en la Cláusula Sexta,  dependiendo de su disponibilidad de recursos económicos</td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">OCTAVO</td>
  </tr>
  <tr>
    <td align="justify"><strong>LA ORGANIZACIÓN</strong> se  compromete a:</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>Presentar un Informe Técnico -  Administrativo de la Gira de Aprendizaje, en un  plazo que no exceda los veinte (20) días hábiles posteriores a la mencionada  Gira de Aprendizaje, el que estará acompañado de la rendición  de cuentas, en forma documentada con los  comprobantes de pago de los gastos realizados por <strong>LA   ORGANIZACIÓN</strong> con los fondos transferidos por <strong>SIERRA SUR II</strong> y el aporte de <strong>LA ORGANIZACIÓN.  </strong>Los  comprobantes de pago de los gastos realizados deberán estar a nombre de<strong> LA ORGANIZACIÓN.</strong></li>
      <li><strong>LA ORGANIZACIÓN</strong> se  compromete a realizar una réplica del aprendizaje obtenido por los  participantes en la Gira de Aprendizaje a favor de sus asociados</li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>DEL  CARGO Y PERFECCIONAMIENTO DE LA   DONACIÓN</u></strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">NOVENO</td>
  </tr>
  <tr>
    <td align="justify">El cargo del presente contrato es el cumplimiento  de su objeto y de las obligaciones de <strong>LA   ORGANIZACIÓN</strong>, establecidas en la Cláusula Octava.  La donación quedará perfeccionada con el  Informe favorable de el <? echo $row['cargo'];?>  de la Oficina Local  de <? echo $row['oficina'];?> respecto de los documentos  indicados en la Cláusula  anterior</td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>RESOLUCIÓN  DEL CONTRATO</u></strong><strong>.</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">DECIMO</td>
  </tr>
  <tr>
    <td align="justify">Son causales de resolución del presente Contrato de  Donación con Cargo, las siguientes:</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>Incumplimiento de las obligaciones establecidas  en el presente contrato por las partes. </li>
      <li>Incumplimiento de <strong>LA   ORGANIZACIÓN</strong> en la ejecución de su Gira de Aprendizaje.</li>
      <li>Disolución de <strong>LA   ORGANIZACIÓN.</strong></li>
      <li>Presentación de información falsa ante <strong>SIERRA SUR II</strong> por parte de <strong>LA   ORGANIZACIÓN</strong></li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>DE LAS  SANCIONES</u></strong><strong>.</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">UNDÉCIMO</td>
  </tr>
  <tr>
    <td align="justify">Conllevan sanciones en la aplicación del presente  Contrato:</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>En caso de resolución por  incumplimiento de las partes, la parte agraviada iniciará las acciones penales  y civiles a que hubieren lugar. Si la parte agraviada es <strong>SIERRA SUR II</strong>, éste se reserva el derecho de comunicar por cualquier  medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
      <li>En caso <strong>LA   ORGANIZACIÓN</strong> haya efectuado un uso inapropiado o desvío  de fondos para otros fines no previstos en el presente Contrato o presente  información falsa, <strong>SIERRA SUR II</strong> exigirá  la devolución de los fondos desembolsados a favor de la organización. Para  levantar esta medida <strong>LA ORGANIZACIÓN</strong> deberá comunicar y acreditar a <strong>SIERRA  SUR II</strong> que ha implementado las medidas correctivas  y aplicado las sanciones a los responsables,  si el caso lo amerita.</li>
      <li>En caso de disolución de <strong>LA   ORGANIZACIÓN</strong>, ésta devolverá a <strong>SIERRA SUR II</strong> los fondos no utilizados y presentará los documentos que  acrediten los gastos realizados.</li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>SITUACIONES NO  PREVISTAS</u></strong><strong>.</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">DUODÉCIMO</td>
  </tr>
  <tr>
    <td align="justify">En caso de ocurrir situaciones no previstas en el  presente Contrato, las partes, mediante acuerdo mutuo, determinarán las medidas  correctivas; que serán expresados en una Adenda</td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>COMPETENCIA  JURISDICCIONAL</u></strong><strong>.</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">DECIMOTERCERO</td>
  </tr>
  <tr>
    <td align="justify">En caso de surgir alguna controversia entre las  partes, respecto a la aplicación del presente Contrato, éstas convienen en  someterse a la competencia de los jueces o tribunales de la localidad de <? echo $row['ubicacion'];?> y podrá recurrirse a la  jurisdicción arbitral, dentro del ámbito del departamento de <? echo $row['depa'];?></td>
  </tr>
  <tr>
    <td align="justify">&nbsp;</td>
  </tr>
  <tr>
    <td align="justify"><p>En fe  de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row['ubicacion'];?> con fecha <? echo traducefecha($row['f_presentacion']);?>. </p></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="35%" align="center">___________________</td>
    <td width="30%" align="center">___________________</td>
 <?
 if ($row['cargo_2']<>NULL)
 {
 ?>   
    <td width="35%" align="center">___________________</td>
<?
 }
?>
  </tr>
  <tr>
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>".$row['cargo']."<br>SIERRASUR";?></td>
    <td align="center"><? echo $row['representante_1']."<br>".$row['cargo_1']."<br>LA ORGANIZACIÓN";?></td>
  <?
 if ($row['cargo_2']<>NULL)
 {
 ?>     
    <td align="center"><? echo $row['representante_2']."<br>".$row['cargo_2']."<br>LA ORGANIZACIÓN";?></td>
<?
 }
?> 
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_contrato']);?> – <? echo $row['tipo_iniciativa'];?> – <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?><br> PARA GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS</div>





<br>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row_1['pdss'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">Por intermedio del presente, habiéndose cumplido los requisitos de norma, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos:</div>

<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="txt_titulo">ORGANIZACIÓN</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td width="61%"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">ENTIDAD FINANCIERA</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo $row['ifi'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° CUENTA</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo $row['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">REFERENCIA</td>
    <td align="center" class="txt_titulo">:</td>
    <td>Solicitud de Cofinanciamiento N° <? echo numeracion($row['n_ficha_gm']);?> - <? echo periodo($row['f_presentacion']);?> - <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N°</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_presentacion']);?> – <? echo $row['tipo_iniciativa'];?> - <? echo $row['oficina'];?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="center">GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="txt_titulo">MONTO</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2">S/. <? echo number_format($row_1['pdss'],2);?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CODIGO POA</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['codigo_subactividad']." - ".$row['nombre_subactividad'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CATEGORIA DE GASTO</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['codigo']." - ".$row['categoria'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FUENTE DE FINANCIAMIENTO (%)</td>
    <td align="center" class="txt_titulo">:</td>
    <td width="27%">FIDA: <? echo number_format($row['cof_fida']);?></td>
    <td width="34%">RO: <? echo number_format($row['cof_ro']);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="center">DESEMBOLSO</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td width="58%" align="center">APORTES</td>
    <td width="21%" align="center">MONTO TOTAL APROBADO (S/.)</td>
    <td width="21%" align="center">DESEMBOLSO EN LA FECHA (S/.)</td>
  </tr>
  <?
$sql="SELECT
sys_bd_ente_cofinanciador.descripcion,
Sum(gm_ficha_presupuesto.costo_total) AS monto
FROM
gm_ficha_presupuesto
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = gm_ficha_presupuesto.cod_entidad
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$cod'
GROUP BY
sys_bd_ente_cofinanciador.descripcion";
$result=mysql_query($sql) or die (mysql_error());
while($r8=mysql_fetch_array($result))
{
	$total2=$r8['monto'];
	$total_monto2=$total_monto2+$total2;
	
?>
  <tr>
    <td><? echo $r8['descripcion'];?></td>
    <td align="right"><? echo number_format($r8['monto'],2);?></td>
    <td align="right"><? echo number_format($r8['monto'],2);?></td>
  </tr>
  <?
}
?>
  <tr class="txt_titulo">
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($total_monto2,2);?></td>
    <td align="right"><? echo number_format($total_monto2,2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">Documentos que Sustentan este  Desembolso;  archivados en la OLP</div>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="82%">Solicitud y Propuesta de Gira de Aprendizaje presentado por la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia fotostática de la  personería jurídica</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia fotostática de los  DNI de los directivos responsables solidarios</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Voucher de depósito de  contrapartida de la organización y/o compromiso del aporte de la Municipalidad</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación con Cargo para la Visita Guiada</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>
<BR>
<div class="capa" align="right"><? echo $row['ubicacion'].", ".traducefecha($row['f_presentacion']);?></div>
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
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>".$row['cargo'];?></td>
  </tr>
</table>

<!-- Solicitud de desembolso -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_presentacion']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso  para Gira de Aprendizaje e Intercambio de Conocimientos</td>
  </tr>
  <tr>
    <td class="txt_titulo">ORGANIZACION</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_presentacion']);?> - GDC – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_presentacion']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="19%">Nombre de la Organizacion</td>
    <td width="20%">Nombre de la Iniciativa </td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['organizacion'];?></td>
    <td><? echo $row['tema'];?></td>
    <td class="centrado">GDC</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_presentacion']);?></td>
    <td><? echo $row['ifi'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row_1['pdss'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="6">TOTAL</td>
    <td class="derecha"><? echo number_format($row_1['pdss'],2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente la autorización de transferencia de fondos. </td>
  </tr>
  <tr>
    <td><br>
    Atentamente,</td>
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
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>










<?
//Actualizamos la info de la ficha de seguimiento
$sql="SELECT cod_ficha_iniciativa FROM se_ficha_iniciativa WHERE cod_iniciativa='$cod' AND cod_tipo_iniciativa=2";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);

$sql="UPDATE se_ficha_iniciativa SET monto_solicitado='".$row_1['pdss']."',monto_desembolsado='".$row_1['pdss']."',monto_org='".$row_2['org']."',monto_otros='".$row_3['otro']."',cod_tipo_doc='".$row['cod_tipo_doc']."',n_documento='".$row['rrpp']."',nombre_org='".$row['organizacion']."' WHERE cod_ficha_iniciativa='".$r9['cod_ficha_iniciativa']."'";
$result=mysql_query($sql) or die (mysql_error());


?>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>
</body>
</html>