<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();


if ($modo==imprime)
{
$sql="SELECT clar_bd_liquida_clar.cod_liquida
FROM clar_bd_liquida_clar
WHERE clar_bd_liquida_clar.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);

$cod=$i1['cod_liquida'];
}
else
{
$cod=$cod;
}



$sql="SELECT
clar_bd_liquida_clar.cod_liquida,
clar_bd_evento_clar.cod_clar,
clar_bd_evento_clar.n_contrato,
clar_bd_evento_clar.nombre,
sys_bd_dependencia.nombre AS oficina,
clar_bd_liquida_clar.f_liquidacion,
clar_bd_liquida_clar.resultados,
clar_bd_liquida_clar.problemas,
clar_bd_liquida_clar.ejec_pdss,
clar_bd_liquida_clar.ejec_org,
clar_bd_liquida_clar.ejec_mun,
clar_bd_liquida_clar.ejec_otro,
clar_bd_evento_clar.objetivo,
clar_bd_evento_clar.resultado,
clar_bd_evento_clar.f_presentacion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS org,
clar_bd_ficha_contratante.dni_1,
clar_bd_ficha_contratante.representante_1,
clar_bd_ficha_contratante.cargo_1,
clar_bd_evento_clar.cod_dependencia,
clar_bd_evento_clar.f_campo1,
clar_bd_evento_clar.f_evento,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar
FROM
clar_bd_liquida_clar
LEFT JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_liquida_clar.cod_clar
LEFT JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
LEFT JOIN clar_bd_ficha_contratante ON clar_bd_ficha_contratante.cod_clar = clar_bd_evento_clar.cod_clar
LEFT JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = clar_bd_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = clar_bd_ficha_contratante.n_documento
LEFT JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
LEFT JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
LEFT JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
WHERE
clar_bd_liquida_clar.cod_liquida='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_ejecutado=$row['ejec_pdss']+$row['ejec_org']+$row['ejec_mun']+$row['ejec_otro'];

//Busco los datos del jefe de Oficina Local
$sql="SELECT
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//Obtengo los datos del cofinanciamiento

//a.- Organizacion
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS aporte
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 2 AND
clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//b.- Municipio
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS aporte
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad = 3 AND
clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//c.- Otro
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS aporte
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad  = 4 AND
clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//d.- NEC PDSS
$sql="SELECT
Sum(clar_bd_ficha_presupuesto.costo_total) AS aporte
FROM
clar_bd_ficha_presupuesto
WHERE
clar_bd_ficha_presupuesto.cod_entidad= 1 AND
clar_bd_ficha_presupuesto.requerido = 1 AND
clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$total_presupuesto=$r2['aporte']+$r3['aporte']+$r4['aporte']+$r5['aporte'];

//7.- Sacamos la Informacion Referente a los PITs
//a1.- PIT presentados
$sql="SELECT
Count(clar_bd_ficha_pit.cod_ficha_pit_clar) AS pit_presentados
FROM
clar_bd_ficha_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
WHERE
clar_bd_ficha_pit.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

//a2.- PIT presentados segundo desembolso
$sql="SELECT
Count(clar_bd_ficha_pit_2.cod_ficha_pit_clar) AS pit_presentados
FROM
clar_bd_ficha_pit_2
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
WHERE
clar_bd_ficha_pit_2.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r16=mysql_fetch_array($result);

$pit_presentado=$r6['pit_presentados']+$r16['pit_presentados'];


//b1.- PIT ganadores
$sql="SELECT Count(clar_bd_ficha_pit.cod_ficha_pit_clar) AS pit_ganadores
FROM clar_bd_ficha_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
WHERE clar_bd_ficha_pit.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);


//b2.- PIT ganadores segundo desembolso
$sql="SELECT Count(clar_bd_ficha_pit_2.cod_ficha_pit_clar) AS pit_ganadores
FROM clar_bd_ficha_pit_2 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
WHERE clar_bd_ficha_pit_2.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pit.cod_estado_iniciativa=008";
$result=mysql_query($sql) or die (mysql_error());
$r17=mysql_fetch_array($result);

$pit_ganador=$r7['pit_ganadores']+$r17['pit_ganadores'];




//8.- Sacamos la Información Referente a los PGRN
//a2.- PGRN presentados
$sql="SELECT
Count(clar_bd_ficha_mrn.cod_ficha_mrn_clar) AS mrn_presentado
FROM
clar_bd_ficha_mrn
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
WHERE
clar_bd_ficha_mrn.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die(mysql_error());
$r8=mysql_fetch_array($result);


//a3.- PGRN ganadores segundo desembolso
$sql="SELECT
Count(clar_bd_ficha_mrn_2.cod_ficha_mrn_clar) AS mrn_presentado
FROM
clar_bd_ficha_mrn_2
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
WHERE
clar_bd_ficha_mrn_2.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die(mysql_error());
$r18=mysql_fetch_array($result);

$pgrn_presentado=$r8['mrn_presentado']+$r18['mrn_presentado'];


//b2.- PGRN ganadores
$sql="SELECT
Count(clar_bd_ficha_mrn.cod_ficha_mrn_clar) AS mrn_ganador
FROM
clar_bd_ficha_mrn
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
WHERE
clar_bd_ficha_mrn.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);

//b3.- PGRN ganadores segundo desembolso
$sql="SELECT
Count(clar_bd_ficha_mrn_2.cod_ficha_mrn_clar) AS mrn_ganador
FROM
clar_bd_ficha_mrn_2
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
WHERE
clar_bd_ficha_mrn_2.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa = 008";
$result=mysql_query($sql) or die (mysql_error());
$r19=mysql_fetch_array($result);

$pgrn_ganador=$r9['mrn_ganador']+$r19['mrn_ganador'];


//c2.- Monto PGRN
$sql="SELECT
Sum((pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss)) AS cof_mrn
FROM
clar_bd_ficha_mrn
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
WHERE
clar_bd_ficha_mrn.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$r10=mysql_fetch_array($result);

//9.- Sacamos la Informacion de PDN
//a3.- PDN concursante
$sql="SELECT
Count(clar_bd_ficha_pdn.cod_ficha_pdn_clar) AS pdn_presentado
FROM
clar_bd_ficha_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
WHERE
clar_bd_ficha_pdn.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r11=mysql_fetch_array($result);

//a4.- PDN concursante segundo desembolso
$sql="SELECT
Count(clar_bd_ficha_pdn_2.cod_ficha_pdn_clar) AS pdn_presentado
FROM
clar_bd_ficha_pdn_2
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
WHERE
clar_bd_ficha_pdn_2.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r20=mysql_fetch_array($result);

//a5.- PDN concursante suelto
$sql="SELECT COUNT(clar_bd_ficha_pdn_suelto.cod_pdn) AS pdn_presentado
FROM clar_bd_ficha_pdn_suelto
WHERE clar_bd_ficha_pdn_suelto.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r23=mysql_fetch_array($result);

$pdn_presentado=$r11['pdn_presentado']+$r20['pdn_presentado']+$r23['pdn_presentado'];






//b3.- PDN ganador
$sql="SELECT
Count(clar_bd_ficha_pdn.cod_ficha_pdn_clar) AS pdn_ganador
FROM
clar_bd_ficha_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
WHERE
clar_bd_ficha_pdn.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$r12=mysql_fetch_array($result);

//b4.- PDN ganador segundo desembolso
$sql="SELECT
Count(clar_bd_ficha_pdn_2.cod_ficha_pdn_clar) AS pdn_ganador
FROM
clar_bd_ficha_pdn_2
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
WHERE
clar_bd_ficha_pdn_2.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa = 008";
$result=mysql_query($sql) or die (mysql_error());
$r21=mysql_fetch_array($result);

//ganador PDN suelto
$sql="SELECT
Count(clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar) AS pdn_ganador
FROM
clar_bd_ficha_pdn_suelto
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_suelto.cod_pdn
WHERE
clar_bd_ficha_pdn_suelto.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$r24=mysql_fetch_array($result);

$pdn_ganador=$r20['pdn_presentado']+$r12['pdn_ganador']+$r24['pdn_ganador'];




//c3.- Monto PDN
$sql="SELECT
Sum((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS monto_pdn
FROM
clar_bd_ficha_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
WHERE
clar_bd_ficha_pdn.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die(mysql_error());
$r13=mysql_fetch_array($result);

//IDL Presentado
$sql="SELECT COUNT(clar_bd_ficha_idl.cod_ficha_idl_clar) AS presentado
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
WHERE clar_bd_ficha_idl.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r14=mysql_fetch_array($result);

//IDL APROBADO
$sql="SELECT COUNT(clar_bd_ficha_idl.cod_ficha_idl_clar) AS ganador
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
WHERE clar_bd_ficha_idl.cod_clar='".$row['cod_clar']."' AND
pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$r15=mysql_fetch_array($result);

//idl segundo desembolso
$sql="SELECT COUNT(clar_bd_ficha_idl_2.cod_ficha_idl_clar) AS presentado
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl_2 ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl_2.cod_idl
WHERE clar_bd_ficha_idl_2.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r22=mysql_fetch_array($result);


//Saldos
$diferencia_pdss=$r5['aporte']-$row['ejec_pdss']; 
 $diferencia_org=$r2['aporte']-$row['ejec_org'];
 $diferencia_mun=$r3['aporte']-$row['ejec_mun'];
 $diferencia_otr=$r4['aporte']-$row['ejec_otro'];
 $diferencia_total =$total_presupuesto - $total_ejecutado;

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
<div class="capa txt_titulo" align="center"><u>INFORME FINAL</u><BR>
  DE EJECUCIÓN DEL EVENTO CLAR <BR>
</div>


<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">N° Contrato</td>
    <td width="2%">:</td>
    <td width="70%">Contrato N° <? echo numeracion($row['n_contrato'])."- CLAR -".periodo($row['f_presentacion']);?></td>
  </tr>
  <tr>
    <td>Fecha de Contrato</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_presentacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACION GENERAL</td>
  </tr>
  <tr>
    <td>Nombre del evento </td>
    <td>:</td>
    <td><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td>Fecha de Inicio</td>
    <td>:</td>
    <td><? echo fecha_normal($row['f_campo1']);?></td>
  </tr>
  <tr>
    <td>Fecha de término</td>
    <td>:</td>
    <td><? echo fecha_normal($row['f_evento']);?></td>
  </tr>
  <tr>
    <td>Lugar de realización del evento </td>
    <td>:</td>
    <td><? echo $row['departamento']." / ".$row['provincia']." / ".$row['distrito']." / ".$row['lugar'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3" align="justify">II.- PRESUPUESTO TOTAL DEL EVENTO </td>
  </tr>
</table>
<BR>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini" bordercolor="#000000">
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
clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."'
ORDER BY
clar_bd_ficha_presupuesto.cod_entidad ASC,
clar_bd_ficha_presupuesto.cod_tipo_gasto ASC";
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
    <td align="right" class="txt_titulo"><? echo number_format($total_presupuesto,2);?></td>
    <td align="right" class="txt_titulo"><? echo number_format($r5['aporte'],2);?></td>
    <td align="center" class="txt_titulo">-</td>
  </tr>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td>III.- ESQUEMA DE COFINANCIAMIENTO </td>
  </tr>
</table>
<br>

 <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1"  class="mini">
	<tr class="centrado txt_titulo">
		<td width="40%">ENTIDAD</td>
		<td width="20%">DESEMBOLSADO (S/.)</td>
		<td width="20%">EJECUTADO (S/.)</td>
		<td width="20%">SALDO (S/.)</td>
	</tr>
  <tr>
    <td>SIERRA SUR II</td>
    <td class="derecha"><? echo number_format($r5['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_pdss'],2);?></td>
    <td class="derecha"><?  if ($diferencia_pdss>0) echo number_format($diferencia_pdss,2); else echo "0.00";?></td>
    
  </tr>
  <tr>
    <td>ORGANIZACION</td>
    <td class="derecha"><? echo number_format($r2['aporte'],2);?></td>
   <td class="derecha"><? echo number_format($row['ejec_org'],2);?></td>
   <td class="derecha"><?  if ($diferencia_org>0)  echo number_format($diferencia_org,2); else echo "0.00";?></td>
  
  </tr>
  <tr>
    <td>MUNICIPIO</td>
    <td class="derecha"><? echo number_format($r3['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_mun'],2);?></td>
   <td class="derecha"><?   if ($diferencia_mun>0) echo number_format($diferencia_mun,2); else echo "0.00";?></td>
  
  </tr>
  <tr>
    <td>OTRO</td>
    <td class="derecha"><? echo number_format($r4['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_otro'],2);?></td>
    <td class="derecha"><?  if ($diferencia_otr>0) echo number_format($diferencia_otr,2); else echo "0.00";?></td>

  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="derecha"><? echo number_format($total_presupuesto,2);?></td>
    <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
    <td class="derecha"><? if ($diferencia_total>0) echo number_format($diferencia_total,2); else echo "0.00";?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td colspan="3">IV.- N° DE INICIATIVAS PARTICIPANTES EN EL EVENTO </td>
  </tr>
</table>

<table style="width: 90%;" align="center" border="1" cellpadding="1" cellspacing="1">

  <tbody>
    <tr class="mini txt_titulo">
      <td style="width: 389px;" align="center">TIPO DE INICIATIVAS<br>
      </td>
      <td style="width: 130px;" align="center">PRESENTADAS<br>
      </td>
      <td style="width: 130px;" align="center">GANADORAS<br>
      </td>
    </tr>
    <tr class="mini">
      <td>PLANES DE
INVERSION TERRITORIAL<br>
      </td>
      <td align="right"><? echo number_format($pit_presentado);?>
      </td>
      <td align="right"><? echo number_format($pit_ganador);?>
      </td>
    </tr>
    <tr class="mini">
      <td>PLANES DE GESTION
DE RECURSOS NATURALES<br>
      </td>
      <td align="right"><? echo number_format($pgrn_presentado);?>
      </td>
      <td align="right"><? echo number_format($pgrn_ganador);?>
      </td>
    </tr>
    <tr class="mini">
      <td>PLANES DE NEGOCIO<br>
      </td>
      <td align="right"><? echo number_format($pdn_presentado);?>
      </td>
      <td align="right"><? echo number_format($pdn_ganador);?>
      </td>
    </tr>
    <tr class="mini">
      <td>INVERSIONES DE DESARROLLO LOCAL<br>
      </td>
      <td align="right"><? echo $r14['presentado']+$r22['presentado'];?>
      </td>
      <td align="right"><? echo $r15['ganador']+$r22['presentado'];?>
      </td>
    </tr>
  </tbody>
</table>



<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">V.- RESULTADOS Y COMENTARIOS </td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">5.1 Resultados alcanzados </td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['resultados'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="3">5.2 Comentarios u observaciones </td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['problemas'];?></td>
  </tr>
</table>
<p>&nbsp;</p>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_liquidacion']);?></div>
<H1 class=SaltoDePagina> </H1>

<? include("encabezado.php");?>
<div class="capa txt_titulo centrado"><u>LIQUIDACION Y PERFECCIONAMIENTO DEL CONTRATO PARA REALIZACIÓN DE EVENTOS CLAR</u><br></div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="4">
  <tr>
    <td width="17%">A</td>
    <td width="1%">:</td>
    <td width="82%">JOSÉ MERCEDES SIALER PASCO </td>
  </tr>
  <tr class="txt_titulo">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>DIRECTOR EJECUTIVO </td>
  </tr>
  <tr>
    <td>Referencia</td>
    <td>:</td>
    <td>Contrato N° <? echo numeracion($row['n_contrato'])."- CLAR -".periodo($row['f_presentacion']);?></td>
  </tr>
  <tr>
    <td>Lugar y Fecha </td>
    <td>:</td>
    <td><? echo $row['oficina'].", ".traducefecha($row['f_liquidacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<div class="capa justificado">
<p>En relación al documento de la referencia, informo a su despacho, que la <strong><? echo $row['org'];?></strong>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan:</p>
  <ol>
    <li>Informe final de ejecución del evento CLAR</li>
    <li>........... archivo con documentación en ........... folios.</li>
  </ol>
  <p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por <strong>LIQUIDADO</strong> el Contrato de la referencia por el monto total ejecutado de  <strong>S/ <? echo number_format($total_ejecutado,2);?> (<? echo vuelveletra($total_ejecutado);?>)</strong>. El mismo que esta conformado de la siguiente manera:</p>
  <p>
	  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1"  class="mini">
	<tr class="centrado txt_titulo">
		<td width="40%">ENTIDAD</td>
		<td width="20%">DESEMBOLSADO (S/.)</td>
		<td width="20%">EJECUTADO (S/.)</td>
		<td width="20%">SALDO (S/.)</td>
	</tr>
  <tr>
    <td>SIERRA SUR II</td>
    <td class="derecha"><? echo number_format($r5['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_pdss'],2);?></td>
    <td class="derecha"><?  if ($diferencia_pdss>0) echo number_format($diferencia_pdss,2); else echo "0.00";?></td>
    
  </tr>
  <tr>
    <td>ORGANIZACION</td>
    <td class="derecha"><? echo number_format($r2['aporte'],2);?></td>
   <td class="derecha"><? echo number_format($row['ejec_org'],2);?></td>
   <td class="derecha"><?  if ($diferencia_org>0)  echo number_format($diferencia_org,2); else echo "0.00";?></td>
  
  </tr>
  <tr>
    <td>MUNICIPIO</td>
    <td class="derecha"><? echo number_format($r3['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_mun'],2);?></td>
   <td class="derecha"><?   if ($diferencia_mun>0) echo number_format($diferencia_mun,2); else echo "0.00";?></td>
  
  </tr>
  <tr>
    <td>OTRO</td>
    <td class="derecha"><? echo number_format($r4['aporte'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_otro'],2);?></td>
    <td class="derecha"><?  if ($diferencia_otr>0) echo number_format($diferencia_otr,2); else echo "0.00";?></td>

  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="derecha"><? echo number_format($total_presupuesto,2);?></td>
    <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
    <td class="derecha"><? if ($diferencia_total>0) echo number_format($diferencia_total,2); else echo "0.00";?></td>
  </tr>
</table>
  </p>
  <p>Por lo expuesto, 
  <?php
   if($diferencia_pdss>0)
   {
    echo "y luego de verificar la <strong>DEVOLUCION</strong> del monto de <strong>S/. ".number_format($diferencia_pdss,2)." (".vuelveletra($diferencia_pdss).")</strong>, ";
   }
?>
  
  esta jefatura procede al <strong>PERFECCIONAMIENTO</strong> de la Donación Sujeto a Cargo por el monto de <strong>S/. <? echo number_format($row['ejec_pdss'],2);?>. (<? echo vuelveletra($row['ejec_pdss']);?>)</strong> correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II </p>
  <p>Por lo indicado, mucho estimaré disponer la baja contable del contrato en referencia.</p>

  <p><br>
  </p>
</div>
<br></br>
<div class="capa">Atentamente,</div>



<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%" align="center">___________________</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><? echo $r1['nombre']." ".$r1['apellido'];?><br>JEFE DE OFICINA LOCAL</td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>

<? include("encabezado.php");?>
<div class="capa centrado txt_titulo">CONFORMIDAD PARA LA BAJA CONTABLE DEL CONTRATO PARA LA REALIZACIÓN DE EVENTO CLAR</div>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="5"><hr></td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">RESPONSABLE DEL COMPONENTE</td>
  </tr>
  <tr>
    <td width="52%">Es conforme</td>
    <td width="48%"><input type="checkbox" name="checkbox" id="checkbox"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox2" id="checkbox2"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">ADMINISTRADOR</td>
  </tr>
  <tr>
    <td>Es conforme</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox3"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox4"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td colspan="5">LIQUIDACIÓN Y CIERRE DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACIÓN CON CARGO </td>
  </tr>
  <tr>
    <td colspan="5"><div class="capa" align="justify">VISTO EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACIÓN CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DE COMPONENTES Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION  LA BAJA CONTABLE DE LA INICIATIVA DE LA REFERENCIA.</div></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>FIRMA</td>
    <td>:</td>
    <td><div align="right">FECHA</div></td>
    <td>:</td>
    <td>&nbsp;</td>
  </tr>
</table>

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_liquida" class="secondary button oculto">Finalizar</a>
</div>


</body>
</html>
