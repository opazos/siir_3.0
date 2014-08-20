<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
clar_bd_evento_clar.cod_clar,
clar_bd_evento_clar.nombre AS evento,
clar_bd_evento_clar.f_campo1,
clar_bd_evento_clar.f_campo2,
clar_bd_evento_clar.f_evento,
clar_bd_evento_clar.objetivo,
clar_bd_evento_clar.resultado,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar,
sys_bd_dependencia.nombre AS oficina,
clar_bd_evento_clar.cod_tipo_clar,
sys_bd_componente_poa.codigo AS codigo_componente,
sys_bd_componente_poa.nombre AS nombre_componente,
sys_bd_subactividad_poa.codigo AS codigo_poa,
sys_bd_subactividad_poa.nombre AS nombre_poa,
sys_bd_categoria_poa.codigo AS codigo_categoria,
sys_bd_categoria_poa.nombre AS nombre_categoria,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion,
clar_bd_evento_clar.n_contrato,
clar_bd_evento_clar.n_atf,
clar_bd_evento_clar.f_presentacion,
clar_bd_evento_clar.premio,
sys_bd_dependencia.departamento AS dep_oficina,
sys_bd_dependencia.provincia AS prov_oficina,
sys_bd_dependencia.ubicacion AS dist_oficina,
sys_bd_dependencia.direccion, 
clar_bd_evento_clar.fte_fida, 
clar_bd_evento_clar.fte_ro
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_bd_evento_clar.cod_componente
INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_bd_evento_clar.cod_subatividad
INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
WHERE
clar_bd_evento_clar.cod_clar = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$tipo_clar=$row['cod_tipo_clar'];

if ($tipo_clar==1)
{
	$tipo="NEC PDSS II";
}
elseif($tipo_clar==2)
{
	$tipo="MUNICIPIO";
}
elseif($tipo_clar==3)
{
	$tipo="OTRA ENTIDAD";
}

//presupuestos
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS total
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS total
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod' AND
clar_bd_ficha_presupuesto.cod_entidad = 1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS total
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod' AND
clar_bd_ficha_presupuesto.cod_entidad = 1 AND
clar_bd_ficha_presupuesto.requerido = 1";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

// N° PIT a evaluar
$sql="SELECT
Count(clar_bd_ficha_pit.cod_ficha_pit_clar) AS pit
FROM
clar_bd_ficha_pit
WHERE
clar_bd_ficha_pit.cod_clar ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);


$proyecto= "<b>SIERRA SUR II</b>";

$tipo_org=$fila1['cod_tipo_org'];

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

<div class="capa txt_titulo" align="center"><U>PROPUESTA</U><br>
PARA LA REALIZACION DE CONCURSO CLAR : "<? echo $row['evento'];?>"
<br>
N° <? echo numeracion($row['cod_clar'])." - ".periodo($row['f_evento'])." - ".$row['oficina'];?>
</div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACION GENERAL</td>
  </tr>
  <tr>
    <td width="27%">Nombre del Evento</td>
    <td width="4%">:</td>
    <td width="69%"><? echo $row['evento'];?></td>
  </tr>
  <tr>
    <td>Tipo de Evento</td>
    <td>:</td>
    <td>CONCURSO</td>
  </tr>
  <tr>
    <td>Entidad Responsable</td>
    <td>:</td>
    <td><? echo $tipo;?></td>
  </tr>
  <tr>
    <td>Periodo de Evaluacion de Campo</td>
    <td>:</td>
    <td>Inicio: <? echo traducefecha($row['f_campo1']);?>  - Termino: <? echo traducefecha($row['f_campo2']);?></td>
  </tr>
  <tr>
    <td>Fecha de Presentación Pública</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_evento']);?></td>
  </tr>
  <tr>
    <td>Lugar de realizacion</td>
    <td>:</td>
    <td><? echo $row['departamento']." / ".$row['provincia']." / ".$row['distrito']." / ".$row['lugar'];?></td>
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
    <td colspan="3" class="txt_titulo">II.- OBJETIVO Y RESULTADOS ESPERADOS</td>
  </tr>
  <tr>
    <td colspan="3">2.1 Objetivos</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['objetivo'];?></td>
  </tr>
  <tr>
    <td colspan="3">2.2 Resultados Esperados</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['resultado'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">III.- PRESUPUESTO Y AFECTACION</td>
  </tr>
  <tr>
    <td>Presupuesto Total del Evento (S/.)</td>
    <td>:</td>
    <td><? echo number_format($r1['total'],2);?> (Ver hoja de presupuesto adjunta)</td>
  </tr>
  <tr>
    <td>Presupuesto Total NEC PDSS II (S/.)</td>
    <td>:</td>
    <td><b><? echo number_format($r2['total'] + $row['premio'],2);?></b></td>
  </tr>
  <tr>
    <td>Monto Solicitado para la Realización del Evento(S/.)</td>
    <td>:</td>
    <td><b><? echo number_format($r3['total'],2);?></b></td>
  </tr>
  <tr>
    <td>Afectación Presupuestal (%)</td>
    <td>:</td>
    <td>Fuente FIDA: <? echo number_format($row['fte_fida'],2);?>  | Fuente RO: <? echo number_format($row['fte_ro'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_presentacion']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="33%"><hr></td>
    <td width="32%">&nbsp;</td>
    <td width="35%"><hr></td>
  </tr>
  <tr>
    <td align="center">(Solicitante)<br>
      <? echo $row['nombre']." ".$row['apellido']."<br>  DNI N° ".$row['n_documento'];?></td>
    <td>&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS </td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>

<? include("encabezado.php");?>
<div class="minititulo" align="center">HOJA DE PRESUPUESTO</div>
<div class="capa txt_titulo" align="center">Ref. PROPUESTA 
  PARA LA REALIZACION DE CONCURSO CLAR<br>  N° <? echo numeracion($row['cod_clar'])." - ".periodo($row['f_evento'])." - ".$row['oficina'];?> </div>
<br>  
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="4%" align="center">N°</td>
    <td width="13%" align="center">CONCEPTO</td>
    <td width="30%" align="center">DETALLE</td>
    <td width="13%" align="center">UNIDAD</td>
    <td width="11%" align="center">CANTIDAD</td>
    <td width="7%" align="center">Presupuesto Total (S/.)</td>
    <td width="7%" align="center">Presupuesto Solicitado (S/.)</td>
     <td width="15%" align="center">COFINANCIADOR</td>
  </tr>
  <?
$count=0;

$sql="SELECT
sys_bd_tipo_gasto.descripcion AS tipo_gasto,
sys_bd_ente_cofinanciador.descripcion AS entidad,
clar_bd_ficha_presupuesto.detalle,
clar_bd_ficha_presupuesto.unidad,
clar_bd_ficha_presupuesto.cantidad,
clar_bd_ficha_presupuesto.costo_unitario,
clar_bd_ficha_presupuesto.costo_total,
clar_bd_ficha_presupuesto.requerido
FROM
clar_bd_ficha_presupuesto
INNER JOIN sys_bd_tipo_gasto ON sys_bd_tipo_gasto.cod_tipo_gasto = clar_bd_ficha_presupuesto.cod_tipo_gasto
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = clar_bd_ficha_presupuesto.cod_entidad
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod'
ORDER BY clar_bd_ficha_presupuesto.cod_tipo_gasto DESC, clar_bd_ficha_presupuesto.cod_entidad ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$count++;
?>
  <tr>
    <td align="center"><? echo $count;?></td>
    <td align="center"><? echo $fila['tipo_gasto'];?></td>
    <td><? echo $fila['detalle'];?></td>
    <td align="center"><? echo $fila['unidad'];?></td>
    <td align="right"><? echo number_format($fila['cantidad'],2);?></td>
    <td align="right"><? echo number_format($fila['costo_total'],2);?></td>
    <td align="right"><? if ($fila['requerido']==1) echo number_format($fila['costo_total'],2);?></td>
        <td align="center"><? echo $fila['entidad'];?></td>
  </tr>
  <?
}
?>
  <tr>
    <td colspan="5" align="center" class="txt_titulo">TOTAL</td>
    <td align="right" class="txt_titulo"><? echo number_format($r1['total'],2);?></td>
    <td align="right" class="txt_titulo"><? echo number_format($r3['total'],2);?></td>
    <td align="center" class="txt_titulo">-</td>
  </tr>
</table>
<br>

<div class="capa justificado"><spam class="txt_titulo">NOTA:</spam> Los aportes correspondientes al Municipio podran realizarse utilizando su infraestructura fisica y/o administrativa.</div>
<p><br/></p>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_presentacion']);?></div>


<?
//Verifico que haya contrato
$sql="SELECT
count(clar_bd_ficha_contratante.cod_ficha_contratante) as total
FROM
clar_bd_ficha_contratante
WHERE
clar_bd_ficha_contratante.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);

if ($i1['total']<>0)
{
?>
<H1 class=SaltoDePagina> </H1>
<? 
include("encabezado.php");

//Busco los datos del contratante
$sql="SELECT
org_ficha_organizacion.cod_tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
clar_bd_ficha_contratante.dni_1,
clar_bd_ficha_contratante.representante_1,
clar_bd_ficha_contratante.cargo_1,
clar_bd_ficha_contratante.dni_2,
clar_bd_ficha_contratante.representante_2,
clar_bd_ficha_contratante.cargo_2,
clar_bd_ficha_contratante.n_cuenta,
sys_bd_ifi.descripcion AS banco,
sys_bd_tipo_doc.descripcion AS tipo_doc,
sys_bd_tipo_org.descripcion AS tipo_org,
org_ficha_organizacion.cod_tipo_org
FROM
clar_bd_ficha_contratante
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = clar_bd_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = clar_bd_ficha_contratante.n_documento
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = clar_bd_ficha_contratante.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE
clar_bd_ficha_contratante.contratante=1 AND
clar_bd_ficha_contratante.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$fila1=mysql_fetch_array($result);



//Obtengo los aportes

//nec pdss
$sql="SELECT
SUM(clar_bd_ficha_presupuesto.costo_total) as monto
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod' AND
clar_bd_ficha_presupuesto.cod_entidad=1 AND
clar_bd_ficha_presupuesto.requerido=1";
$result=mysql_query($sql) or die (mysql_error());
$fila2=mysql_fetch_array($result);

//municipio
$sql="SELECT
SUM(clar_bd_ficha_presupuesto.costo_total) as monto
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_clar='$cod' AND
clar_bd_ficha_presupuesto.cod_entidad=3";
$result=mysql_query($sql) or die (mysql_error());
$fila3=mysql_fetch_array($result);

//Busco los convenios
$sql="SELECT
gcac_bd_ficha_convenio.n_convenio,
gcac_bd_ficha_convenio.f_presentacion
FROM
gcac_bd_ficha_convenio
WHERE
gcac_bd_ficha_convenio.cod_tipo_doc='".$fila1['cod_tipo_doc']."' AND
gcac_bd_ficha_convenio.n_documento='".$fila1['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$fila4=mysql_fetch_array($result);


$pp1=$fila2['monto']/$r1['total']*100;
$pp2=$fila3['monto']/$r1['total']*100;

$total_aporte_pdss=$fila2['monto'];

$total_aporte_clar=$total_aporte_pdss+$fila3['monto'];

$pp3=$total_aporte_pdss/$total_aporte_clar*100;
$pp4=$fila3['monto']/$total_aporte_clar*100;

?>



<div class="capa txt_titulo" align="center">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –  <? echo periodo($row['f_presentacion']);?> - CLAR -  <? echo $row['oficina'];?><br>
DE DONACIÓN CON CARGO, ENTRE EL NEC-PROYECTO DE DESARROLLO SIERRA SUR II Y LA  "<? echo $fila1['nombre'];?>", PARA REALIZAR EVENTO DEL CLAR.
</div>












<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr align="justify">
    <td colspan="2">Conste por el presente documento, el contrato para la realizar el evento del Comité Local de Asignación de Recursos -CLAR que celebran, de una parte la Oficina Local de <? echo $row['oficina'];?>  del &quot;NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II”, con RUC Nº 20456188118, en adelante denominado “SIERRA SUR II”, con domicilio legal en <? echo $row['direccion'];?>, del distrito de <? echo $row['dist_oficina'];?>, provincia de <? echo $row['prov_oficina'];?>, departamento del <? echo $row['dep_oficina'];?>, representado por el Jefe de la Oficina Local, Señor(a)  <? echo $row['nombre']." ".$row['apellido'];?>, con DNI Nº <? echo $row['n_documento'];?>; y de  otra parte la <? echo $fila1['nombre'];?>, con <? echo $fila1['tipo_doc'];?> Nº <? echo $fila1['n_documento'];?>, con domicilio legal en <? echo $fila1['sector'];?>, del distrito de <? echo $fila1['distrito'];?>, provincia de <? echo $fila1['provincia'];?>,  departamento del <? echo $fila1['departamento'];?>, en adelante denominada “LA <? echo $fila1['tipo_org'];?>”, representada por su <? echo $fila1['cargo_1'];?>, Señor(a)  <? echo $fila1['representante_1'];?>, identificado con DNI Nº <? echo $fila1['dni_1'];?>
	
<?
if ($fila1['cargo_2']<>NULL)
{
?>    
, y su <? echo $fila1['cargo_2'];?>, Señor(a) <? echo $fila1['representante_2'];?> identificado con DNI. N°<? echo $fila1['dni_2'];?>
<?
}
?>	
	, en los términos y condiciones siguientes:</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA PRIMERA:	ANTECEDENTES</td>
  </tr>
  <tr>
    <td width="5%" valign="top">1.1</td>
    <td width="95%" align="justify">
<?php 
if ($tipo_org<>6)
{
?>
"<?php  echo $proyecto;?>" es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799-PE y la Donación N° 1158 - PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola - FIDA, dichos recursos son transferidos a "<?php  echo $proyecto;?>" a través del Programa AGRORURAL del Ministerio de Agricultura - MINAG.
<?php 
}
else
{
?>
	
	Mediante Convenio N° <? echo numeracion($fila4['n_convenio'])."-".periodo($fila4['f_presentacion']);?> OL <? echo $row['oficina'];?> , de fecha <? echo traducefecha($fila4['f_presentacion']);?>, “SIERRA SUR II” y “LA <? echo $fila1['tipo_org'];?>” suscribieron un Convenio de Cooperación Interinstitucional para desarrollar acciones conjuntas en apoyo a las familias rurales del ámbito de acción de “LA <? echo $fila1['tipo_org'];?>”, bajo un enfoque de inclusión y desarrollo territorial, mediante el apoyo a iniciativas rurales, considerando principalmente como instrumento base el Plan de Inversión Territorial -PIT que “SIERRA SUR II” promueve en favor de organizaciones campesinas a través de la Oficina Local de <? echo $row['oficina'];?>.
<?php 
}
?>	
	</td>
  </tr>
  <tr>
    <td valign="top">1.2</td>
    <td width="95%" align="justify">En concordancia con las estrategias generales que orientan la ejecución de “SIERRA SUR II”,las propuestas de PITs son evaluadas por el Comité Local de Asignación de Recursos-CLAR que es la instancia colegiada competente en la estructura  de “SIERRA SUR II” para aprobar la selección de propuestas y transferencia de fondos a las organizaciones que obtengan calificación aprobatoria; así como la premiación de Mapas Culturales.</td>
  </tr>
  <tr>
    <td valign="top">1.3</td>
    <td width="95%" align="justify">La Oficina Local de <? echo $row['oficina'];?> de “SIERRA SUR II”  
    <?php 
    if ($tipo_org<>6)
    {
 echo "en concordancia con la ".$fila1['tipo_org'];
    }
    ?>
    
    ha programado realizar el evento del CLAR entre el <? echo traducefecha($row['f_campo1']);?>  y el <? echo traducefecha($row['f_evento']);?>, el cual comprende dos fases:<br> 1) Evaluación de Campo, prevista del día <? echo traducefecha($row['f_campo1']);?> al día <? echo traducefecha($row['f_campo2']);?>. <br>
      2) Evaluación Pública, a realizarse el día <? echo traducefecha($row['f_evento']);?> en <? echo $row['distrito'];?>, provincia de <? echo $row['provincia'];?> 
     <?php 
     if ($tipo_org==6)
     {
     ?> 
      , sede de “LA <? echo $fila1['tipo_org'];?>”
      <?php 
     }
     ?>
      . En dicho CLAR se evaluarán hasta <?php  echo number_format($r9['pit']);?> propuestas de Planes de Inversión Territorial-PITs, pertenecientes a organizaciones rurales del ámbito de 
      <?php 
      if ($tipo_org==6)
      {
      ?>
      “LA <? echo $fila1['tipo_org'];?>” y de otros distritos del ámbito de
      <?php 
      }
      ?>
       acción de “SIERRA SUR II”.</td>
  </tr>
  <?php 
  if ($tipo_org==6)
  {
  ?>
  <tr>
    <td valign="top">1.4</td>
    <td width="95%" align="justify">En el marco de la estrategia de empoderamiento de los Gobiernos Locales de la propuesta metodológica de “SIERRA SUR II”,  “LA <? echo $fila1['tipo_org'];?>”  ha decidido realizar el CLAR a que se hace referencia en el numeral 1.3, con asesoramiento y apoyo de “SIERRA SUR II”.</td>
  </tr>
  <?php 
  }
  ?>
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA SEGUNDA:	OBJETO DEL CONTRATO</td>
  </tr>
  <tr align="justify">
    <td colspan="2">
      El objeto del presente contrato es el cofinanciamiento entre las partes, para  la realización del CLAR  a desarrollarse en el distrito de <? echo $row['distrito'];?> entre el <? echo traducefecha($row['f_campo1']);?> y el <? echo traducefecha($row['f_evento']);?>.
	    <br>
    “SIERRA SUR II” transferirá a “LA <? echo $fila1['tipo_org'];?>” el monto de S/. <? echo number_format($total_aporte_pdss,2);?> (<? echo vuelveletra($total_aporte_pdss);?> Nuevos Soles), destinados única y exclusivamente para financiar la realización del evento CLAR referido en la Cláusula Primera. Por su parte, “LA <? echo $fila1['tipo_org'];?>” se compromete a aportar el monto de S/. <? echo number_format($fila3['monto'],2);?> (<? echo vuelveletra($fila3['monto']);?> Nuevos Soles). Ambos montos serán ejecutados según el siguiente programa de desembolsos.</td>
  </tr>
</table>

<BR>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td colspan="6" align="center" class="txt_titulo">PROGRAMA DE DESEMBOLSOS </td>
  </tr>
  <tr>
    <td width="25%" align="center" class="txt_titulo">DETALLE</td>
    <td width="15%" align="center" class="txt_titulo">SIERRASUR II </td>
    <td width="15%" align="center" class="txt_titulo">%</td>
    <td width="15%" align="center" class="txt_titulo"><?php  echo $fila1['tipo_org'];?></td>
    <td width="15%" align="center" class="txt_titulo">%</td>
    <td width="15%" align="center" class="txt_titulo">TOTAL</td>
  </tr>
  <tr>
    <td>REALIZACIÓN DEL CLAR (Ver Hoja de Presupuesto Adjunta) </td>
    <td align="right"><? echo number_format($fila2['monto'],2);?></td>
    <td align="right"><? echo number_format($pp1,2);?></td>
    <td align="right"><? echo number_format($fila3['monto'],2);?></td>
    <td align="right"><? echo number_format($pp2,2);?></td>
    <td align="right"><? echo number_format($total_aporte_clar,2);?></td>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td align="right"><? echo number_format($total_aporte_pdss,2);?></td>
    <td align="right"><? echo number_format($pp3,2);?></td>
    <td align="right"><? echo number_format($fila3['monto'],2);?></td>
    <td align="right"><? echo number_format($pp4,2);?></td>
    <td align="right"><? echo number_format($total_aporte_clar,2);?></td>
  </tr>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA TERCERA:	PLAZO DEL CONTRATO</td>
  </tr>
  <tr valign="top">
    <td colspan="2" align="justify">El plazo establecido por las partes para la ejecución del presente contrato es de cuarenta  días, contados a partir de la fecha de suscripción.</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA CUARTA:		DE LA TRANSFERENCIA Y DESEMBOLSO  DE FONDOS</td>
  </tr>
  <tr>
    <td colspan="2" align="justify">Los aportes de “SIERRA SUR II” serán transferidos calidad de Donación con Cargo, a “LA <? echo $fila1['tipo_org'];?>”, en un solo desembolso, a la cuenta bancaria que ésta comunique a “SIERRA SUR II” y que mantenga debidamente abierta en una entidad financiera regulada por la Superintendencia de Banca y Seguros (SBS).</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA QUINTA:		OBLIGACIONES DE LAS PARTES:</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">5.1.-  De “LA <? echo $fila1['tipo_org'];?>&quot;:</td>
  </tr>
  <tr>
    <td width="5%" valign="top">a)</td>
    <td width="95%" align="justify">Realizar los aportes de cofinanciamiento de conformidad a lo establecido en la Cláusula Segunda del  presente contrato.</td>
  </tr>
  <tr>
    <td valign="top">b)</td>
    <td width="95%" align="justify">Mantener estrecha coordinación con la Oficina Local de <? echo $row['oficina'];?>  de “SIERRA SUR II” para la ejecución del presente contrato.</td>
  </tr>
  <tr>
    <td valign="top">c)</td>
    <td width="95%" align="justify">Presentar el informe de ejecución del CLAR y adjuntar a éste las fotocopias de los comprobantes de pago de los gastos realizados, emitidos a nombre de “LA <? echo $fila1['tipo_org'];?>”, tanto de los aportes de “SIERRA SUR” como de “LA <? echo $fila1['tipo_org'];?>”. Sólo en caso de no lograr la obtención de comprobantes de pago, “LA <? echo $fila1['tipo_org'];?>” podrá sustentar la ejecución de gastos mediante documento de valorización de gastos realizados, debidamente firmado por la(s) instancias(s) competente(s), el mismo que tendrá carácter de declaración jurada.</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">5.2.- De “SIERRA SUR II”:</td>
  </tr>
  <tr>
    <td valign="top">a)</td>
    <td align="justify">Efectuar el desembolso de su aporte referido en la Cláusula Cuarta, en observancia de su disponibilidad de recursos económicos.</td>
  </tr>
  <tr>
    <td valign="top">b)</td>
    <td align="justify">Apoyar a la “LA <? echo $fila1['tipo_org'];?>” en la implementación de las actividades del CLAR que permitan la buena ejecución del presente Contrato.</td>
  </tr>
  <tr>
    <td valign="top">c)</td>
    <td align="justify">Brindar asesoramiento continuo a la “LA <? echo $fila1['tipo_org'];?>” para la correcta aplicación de los instrumentos técnicos y metodológicos relacionados con la realización del CLAR, a fin de contribuir al buen cumplimiento del objeto del presente Contrato.</td>
  </tr>
  <tr>
    <td valign="top">d)</td>
    <td align="justify">Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el perfeccionamiento de la donación.</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA SEXTA:		DEL CARGO Y PERFECCIONAMIENTO DE LA DONACIÓN</td>
  </tr>
  <tr>
    <td colspan="2" align="justify">“SIERRA SUR II” establece  a “LA <? echo $fila1['tipo_org'];?>” como cargo de la presente donación el logro de la realización del CLAR, la responsabilidad y transparencia en el buen manejo de los fondos transferidos por “SIERRA SUR II”, demostrando su cumplimiento a través de la presentación de:</td>
  </tr>
  <tr>
    <td valign="top">a)</td>
    <td>El Informe referido en el literal c), numeral 5.1 de la Cláusula Quinta que incluye el Acta del CLAR, debidamente suscrita por los miembros integrantes del CLAR.</td>
  </tr>
  <tr>
    <td valign="top">b)</td>
    <td>Fotocopias de los comprobantes de pago y rendición de gastos.</td>
  </tr>
  <tr>
    <td colspan="2">Una vez admitidos los documentos establecidos en esta Cláusula por parte de “SIERRA SUR II”, se dará por perfeccionada la donación. </td>
  </tr>
<?
if ($tipo_org==6)
{
?>  
  <tr>
    <td colspan="2" class="txt_titulo">CLAUSULA SÉPTIMA:		PREVALENCIA  DEL CONVENIO N° <? echo numeracion($fila4['n_convenio'])."-".periodo($fila4['f_presentacion']);?> OL <? echo $row['oficina'];?> </td>
  </tr>
  <tr>
    <td colspan="2">En todos los demás aspectos que atañen al presente Contrato prevalecen las Cláusulas establecidas en el Convenio N° <? echo numeracion($fila4['n_convenio'])."-".periodo($fila4['f_presentacion']);?> OL <? echo $row['oficina'];?>  de Cooperación Interinstitucional suscrito entre “SIERRA SUR II” y “LA <? echo $fila1['tipo_org'];?>”</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">En señal de aceptación, las partes suscriben el presente Contrato, en tres ejemplares de igual tenor y efecto legal, en la localidad de <? echo $row['oficina'];?>, con fecha <? echo traducefecha($row['f_presentacion']);?>.</td>
  </tr>
 <?php 
}
?> 
</table>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado txt_titulo">
    <td>CLAUSULA SEPTIMA: RESOLUCIÓN DEL CONTRATO </td>
  </tr>
  <tr class="justificado">
    <td>El presente Contrato se resolverá automaticamente, por: </td>
  </tr>
  <tr class="justificado">
    <td>
	<ol type="a">
	<li>Incumplimiento de las obligaciones establecidas en el presente contrato por alguna de las partes</li>
	<li>Mutuo acuerdo de las partes</li>
	<li>Presentación de información falsa ante "<? echo $proyecto;?>" por parte de "LA ORGANIZACIÓN"</li>
	</ol>	</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA OCTAVA: DE LAS SANCIONES </td>
  </tr>
  <tr class="justificado">
    <td>Conllevan sanciones en la aplicación del presente Contrato: </td>
  </tr>
  <tr class="justificado">
    <td>
	<ol type="a">
	<li>En caso de resolución por incumplimiento de alguna de las partes, la parte agravada iniciará las acciones penales y civiles a que hubiesen lugar. Si la parte agraviada es "<? echo $proyecto;?>", este se reserva el derecho de comunicar por cualquier medion de tal hecho a la sociedad civil del ámbito de su acción</li>
	<li>En caso que "LA ORGANIZACIÓN" haya efectuado un uso inapropiado o desvío de fondos para otros fines no fondos desembolsados a favor de "LA ORGANIZACIÓN". Para levantar esta medida "LA ORGANIZACIÓN" deberá comunicar y acreditar a "<? echo $proyecto;?>" que ha implementado las medidas correctivas y aplicado las sanciones a los responsables, si el caso lo amerita</li>
	<li>En caso de no realizarse el evento, "LA ORGANIZACIÓN" devolverá a "<? echo $proyecto;?>" los fondos transferidos.</li>
	</ol>	</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA NOVENA: SITUACIONES NO PREVISTAS </td>
  </tr>
  <tr class="justificado">
    <td>En caso de ocurrir situaciones no previstas en el presente Contrato o que estando previstas, escapen al control directo de alguna de las partes, mediante acuerdo mutuo se determinarán las medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de esta naturaleza, serán expresados en un Acta, Adenda u otro Instrumento, según el caso lo amerite. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA DECIMA: COMPETENCIA TERRITORIAL Y JURIDISCCIONAL </td>
  </tr>
  <tr class="justificado">
    <td>Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contratb, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón a que la Unidad Ejecutora de &quot;<? echo $proyecto;?>&quot; se encuentra ubicada en el distrito de Quequeña de la provincia de Arequipa. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA DECIMO PRIMERA: DOMICILIO </td>
  </tr>
  <tr class="justificado">
    <td>Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la instroducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a otra parte, por cualquier medio escrito. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA DECIMO SEGUNDA: APLICACIÓN SUPLETORIA DE LA LEY </td>
  </tr>
  <tr class="justificado">
    <td>En lo no previsto por las partes en el presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicable. </td>
  </tr>
  <tr class="justificado">
    <td>&nbsp;</td>
  </tr>
  <tr class="justificado">
    <td>En señal de aceptación, las partes suscriben el presente Contrato, en tres ejemplares de igual tenor y efecto legal, en la localidad de <? echo $row['oficina'];?>, con fecha <? echo traducefecha($row['f_presentacion']);?>.</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="27%" align="center">___________________</td>
    <td width="49%" align="center">&nbsp;</td>

    <td width="24%" align="center">___________________</td>

  </tr>
  <tr>
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>".$row['descripcion']."<br>SIERRASUR II";?></td>
    <td align="center">&nbsp;</td>


    <td align="center"><? echo $fila1['representante_1']."<br>".$fila1['cargo_1']."<br>LA ".$fila1['tipo_org'];?></td>


  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? 
include("encabezado.php");
?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> – <? echo periodo($row['f_presentacion']);?> - CLAR - <? echo $row['oficina'];?><br> PARA REALIZACIÓN DE EVENTO CLAR</div>
<br>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($total_aporte_pdss,2);?></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">Por intermedio del presente, habiéndose cumplido los requisitos de norma, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos:</div>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%" class="txt_titulo">ORGANIZACIÓN</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td width="61%"><? echo $fila1['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">ENTIDAD FINANCIERA</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo $fila1['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° CUENTA</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo $fila1['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">REFERENCIA</td>
    <td align="center" class="txt_titulo">:</td>
    <td>Propuesta N° <? echo numeracion($row['cod_clar'])." - ".periodo($row['f_evento'])." - ".$row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N°</td>
    <td align="center" class="txt_titulo">:</td>
    <td><? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_presentacion']);?> – CLAR - <? echo $row['oficina'];?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="center">DETALLE DEL DESEMBOLSO </div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="49%" align="center">ACTIVIDADES</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>ASIGNACION A ENTIDADES/ORGANIZACIONES PARA DESARROLLAR EL CLAR Y OTROS CONCURSOS</td>
    <td align="right"><? echo number_format($fila2['monto'],2);?></td>
    <td align="center"><? echo $row['codigo_poa'];?></td>
    <td align="center"><? echo $row['codigo_categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($total_aporte_pdss,2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
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
    <tr>
    <td>NEC PDSS II </td>
    <td align="right"><? echo number_format($total_aporte_pdss,2);?></td>
    <td align="right"><? echo number_format($total_aporte_pdss,2);?></td>
  </tr>
    <tr>
      <td>LA <? echo $fila1['tipo_org'];?></td>
      <td align="right"><? echo number_format($fila3['monto'],2);?></td>
      <td align="right"><? echo number_format($fila3['monto'],2);?></td>
    </tr>
    <tr class="txt_titulo">
    <td align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($total_aporte_clar,2);?></td>
    <td align="right"><? echo number_format($total_aporte_clar,2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">Documentos que Sustentan este  Desembolso;  archivados en la OLP</div>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="82%">Solicitud y Propuesta para la realización de Evento CLAR </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación con Cargo para la realización de Evento CLAR </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  
  <tr>
    <td>Voucher de depósito de contrapartida de la organización y/o compromiso de aporte de la Municipalidad y/u Organización</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>
<BR>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_presentacion']);?></div>
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
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>".$row['descripcion'];?></td>
  </tr>
</table>
 
 <!--  Genero la solicitud de desembolso -->
 <H1 class=SaltoDePagina> </H1>
<? 
include("encabezado.php");
?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_presentacion']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso de Fondos para Evento CLAR</td>
  </tr>
  <tr>
    <td>ORGANIZACION/ENTIDAD</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $fila1['nombre'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> - CLAR – <? echo periodo($row['f_presentacion']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_presentacion']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes a las siguientes organizaciones que en resumen son :</div>
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
    <tr>
    <td><?php  echo $fila1['nombre'];?></td>
    <td class="centrado">CLAR</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])." - ".periodo($row['f_presentacion']);?></td>
    <td><? echo $fila1['banco'];?></td>
    <td class="centrado"><? echo $fila1['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($total_aporte_pdss,2);?></td>
  </tr>
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
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
 
 
 
  
  
  
  
  <?
}

/**************************************************************************************************************************/
//AREA DE ACTUALIZACION DE INFO
//1.- SACO EL MONTO REQUERIDO POR EL NEC PDSS
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS costo
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 1 AND
clar_bd_ficha_presupuesto.requerido = 1 AND
clar_bd_ficha_presupuesto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//2.- SACO EL MONTO SOLICITADO POR LA ORGANIZACION (OPCIONAL)
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS costo
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 2 AND
clar_bd_ficha_presupuesto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//3.- SACO EL MONTO COFINANCIADO POR MUNICIPIO
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS costo
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 3 AND
clar_bd_ficha_presupuesto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

//4.- SACO EL MONTO COFINANCIADO POR OTROS ENTES
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS costo
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 4 AND
clar_bd_ficha_presupuesto.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r8=mysql_fetch_array($result);

$otro=$r6['costo']+$r8['costo'];



//4.- BUSCO LA FICHA CORRESPONDIENTE A ESTE EVENTO
$sql="SELECT
se_ficha_iniciativa.cod_ficha_iniciativa
FROM
se_ficha_iniciativa
WHERE
se_ficha_iniciativa.cod_tipo_iniciativa = 7 AND
se_ficha_iniciativa.cod_iniciativa='$cod' AND 
se_ficha_iniciativa.cod_poa=54";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

//5.- ACTUALIZO LA INFO DE LA FICHA
$sql="UPDATE se_ficha_iniciativa SET monto_solicitado='".$r4['costo']."',n_contrato='".$row['n_contrato']."',n_atf='".$row['n_atf']."',f_contrato='".$row['f_presentacion']."',monto_desembolsado='".$r4['costo']."',monto_org='".$r5['costo']."',monto_otros='$otro' ,cod_tipo_doc='".$fila1['cod_tipo_doc']."',n_documento='".$fila1['n_documento']."',nombre_org='".$fila1['nombre']."'    WHERE cod_ficha_iniciativa='".$r7['cod_ficha_iniciativa']."'";
$result=mysql_query($sql) or die (mysql_error());

//6.- BUSCO LA FICHA CORRESPONDIENTE A ESTE EVENTO
$sql="SELECT
se_ficha_iniciativa.cod_ficha_iniciativa
FROM
se_ficha_iniciativa
WHERE
se_ficha_iniciativa.cod_tipo_iniciativa = 7 AND
se_ficha_iniciativa.cod_iniciativa='$cod' AND 
se_ficha_iniciativa.cod_poa=53";
$result=mysql_query($sql) or die (mysql_error());
$r12=mysql_fetch_array($result);

//5.- ACTUALIZO LA INFO DE LA FICHA
$sql="UPDATE se_ficha_iniciativa SET n_contrato='".$row['n_contrato']."',n_atf='".$row['n_atf']."',f_contrato='".$row['f_presentacion']."',monto_desembolsado='".$row['premio']."',cod_tipo_doc='".$fila1['cod_tipo_doc']."',n_documento='".$fila1['n_documento']."',nombre_org='".$fila1['nombre']."' WHERE cod_ficha_iniciativa='".$r12['cod_ficha_iniciativa']."'";
$result=mysql_query($sql) or die (mysql_error());


//FIN DE LA ACTUALIZACION

?>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td><form name="form1" method="post" action="">
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    </form></td>
  </tr>
</table>

</body>
</html>
