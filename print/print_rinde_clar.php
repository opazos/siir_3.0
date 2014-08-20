<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==imprime)
{
$sql="SELECT clar_bd_rinde_clar.cod_rinde_clar
FROM clar_bd_rinde_clar
WHERE clar_bd_rinde_clar.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);

$cod=$i1['cod_rinde_clar'];
}
else
{
$cod=$cod;
}



//1.- Obtengo los datos del Evento CLAR
$sql="SELECT
clar_bd_evento_clar.cod_clar,
sys_bd_tipo_iniciativa.codigo_iniciativa,
clar_bd_evento_clar.nombre AS evento,
clar_bd_evento_clar.f_evento,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar,
sys_bd_dependencia.nombre AS oficina,
sys_bd_dependencia.cod_dependencia,
clar_bd_evento_clar.objetivo,
clar_bd_evento_clar.resultado AS resultado_esperado,
sys_bd_componente_poa.codigo AS codigo_componente,
sys_bd_componente_poa.nombre AS nombre_componente,
sys_bd_subactividad_poa.codigo AS codigo_poa,
sys_bd_subactividad_poa.nombre AS nombre_poa,
sys_bd_categoria_poa.codigo AS codigo_categoria,
sys_bd_categoria_poa.nombre AS nombre_categoria,
clar_bd_rinde_clar.f_rendicion,
clar_bd_rinde_clar.resultado,
clar_bd_rinde_clar.problema,
clar_bd_rinde_clar.cod_dj,
clar_bd_rinde_clar.otro_monto,
clar_bd_rinde_clar.devolucion
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = clar_bd_evento_clar.cod_tipo_iniciativa
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_bd_evento_clar.cod_componente
INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_bd_evento_clar.cod_subatividad
INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
INNER JOIN clar_bd_rinde_clar ON clar_bd_rinde_clar.cod_clar = clar_bd_evento_clar.cod_clar
WHERE
clar_bd_rinde_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//total solicitado
$sql="SELECT SUM(clar_bd_ficha_presupuesto.costo_total) AS costo_clar
FROM clar_bd_ficha_presupuesto
WHERE clar_bd_ficha_presupuesto.cod_clar='".$row['cod_clar']."' AND
clar_bd_ficha_presupuesto.requerido=1";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);




//2.- Obtengo al jefe de la oficina local
$sql="SELECT
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion
FROM
sys_bd_personal
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
WHERE
sys_bd_personal.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_personal.cod_cargo = 7";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Obtengo lo que el proyecto gasto con facturas y boletas
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS gasto_facturado
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error);
$r2=mysql_fetch_array($result);

//4.- Obtengo lo que el proyecto gasto con declaracion jurada (Si hubiera)
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total_dj
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$total_ejecutado_pdss=$r2['gasto_facturado']+$r3['total_dj'];

//5.- Obtengo lo que aporto el municipio
$sql="SELECT
Sum(clar_bd_cofi_clar.aporte) AS aporte_municipio
FROM
clar_bd_cofi_clar
WHERE
clar_bd_cofi_clar.cod_ente = 3 AND
clar_bd_cofi_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//6.- Obtengo los aporte de otras entidades
$sql="SELECT
Sum(clar_bd_cofi_clar.aporte) AS aporte_otro
FROM
clar_bd_cofi_clar
WHERE
clar_bd_cofi_clar.cod_ente = 4 AND
clar_bd_cofi_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//Sumo los aportes para obtener el total del costo del evento
$total_evento=$total_ejecutado_pdss+$r4['aporte_municipio']+$r5['aporte_otro'];
//Obtengo los porcentajes correspondientes a cada entida
//a.- Porcentaje NEC PDSS
@$ppdss=($total_ejecutado_pdss/$total_evento)*100;
//b.- Porcentaje Municipio
@$pmuni=($r4['aporte_municipio']/$total_evento)*100;
//c.- Porcentaje Otros
@$potro=($r5['aporte_otro']/$total_evento)*100;

/**************************************************************************************/

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


<div class="capa txt_titulo" align="center"><u>INFORME</u><br>
DE RENDICIÓN - EVENTO CLAR " <? echo $row['evento'];?> " <br>
N° <? echo numeracion($row['cod_clar']);?> - <? echo $row['codigo_iniciativa'];?> -<? echo periodo($row['f_rendicion']);?> - <? echo $row['oficina'];?>
</div>

<br>

<table style="text-align: left; width: 90%;" align="center" border="0" cellpadding="2" cellspacing="2">

  <tbody>
    <tr>
      <td style="vertical-align: top; text-align: left; width: 153px;">A<br>
      </td>
      <td style="vertical-align: top; width: 12px;">:<br>
      </td>
      <td style="vertical-align: top; width: 621px;">JUAN BENITO SALAS ACOSTA - ADMINISTRADOR DEL NEC PDSS II<br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Asunto<br>
      </td>
      <td style="vertical-align: top; width: 12px;">:<br>
      </td>
      <td style="vertical-align: top; width: 621px;">INFORME DE ACTIVIDADES DEL EVENTO : "<? echo $row['evento'];?>"<br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Referencia<br>
      </td>
      <td style="vertical-align: top; width: 12px;">:<br>
      </td>
      <td style="vertical-align: top; width: 621px;">CLAR N° <? echo numeracion($row['cod_clar']);?> - <? echo $row['codigo_iniciativa'];?> -<? echo periodo($row['f_rendicion']);?> - <? echo $row['oficina'];?><br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Lugar y Fecha<br>
      </td>
      <td style="vertical-align: top; width: 12px;">:<br>
      </td>
      <td style="vertical-align: top; width: 621px;"><? echo $row['oficina'];?>, <? echo traducefecha($row['f_rendicion']);?><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1" style="vertical-align: top;">
      <hr></td>
    </tr>
  </tbody>
</table>
<table align="center" border="0" cellpadding="2" cellspacing="2" width="90%">

  <tbody>
    <tr>
      <td colspan="3" rowspan="1" style="vertical-align: top; width: 619px;" class="txt_titulo">I.- INFORMACIÓN GENERAL<br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Nombre del Evento<br>
      </td>
      <td style="vertical-align: top; width: 14px;">:<br>
      </td>
      <td style="vertical-align: top; width: 619px;"><? echo $row['evento'];?><br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Fecha de
realización<br>
      </td>
      <td style="vertical-align: top; width: 14px;">:<br>
      </td>
      <td style="vertical-align: top; width: 619px;"><? echo traducefecha($row['f_evento']);?><br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top; width: 153px;">Lugar del evento<br>
      </td>
      <td style="vertical-align: top; width: 14px;">:<br>
      </td>
      <td style="vertical-align: top; width: 619px;"><? echo $row['departamento']." - ".$row['provincia']." - ".$row['distrito']." - ".$row['lugar'];?><br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">Oficina Responsable<br>
      </td>
      <td style="vertical-align: top;">:<br>
      </td>
      <td style="vertical-align: top;"><? echo $row['oficina'];?><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1" style="vertical-align: top;" class="txt_titulo">II.-
OBJETIVO DEL EVENTO<br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1"><? echo $row['objetivo'];?><br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1" style="vertical-align: top;" class="txt_titulo">III.-
RESULTADOS OBTENIDOS<br>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1"><? echo $row['resultado'];?><br>
      </td>
    </tr>
    <tr>
        <td colspan="3" rowspan="1" style="vertical-align: top;"><hr>
      </td>
    </tr>
    <tr>
      <td colspan="3" rowspan="1" style="vertical-align: top;" class="txt_titulo">IV.-
ENTIDADES PARTICIPANTES<br>
      </td>
    </tr>
  </tbody>
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
<br>
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
    <td align="right"><? echo number_format($total_ejecutado_pdss,2);?></td>
    <td align="right"><? echo number_format(@$ppdss,2);?></td>
  </tr>
  <tr>
    <td>Municipio</td>
    <td align="right"><? echo number_format($r4['aporte_municipio'],2);?></td>
    <td align="right"><? echo number_format(@$pmuni,2);?></td>
  </tr>
  <tr>
    <td>Otros aportes</td>
    <td align="right"><? echo number_format($r5['aporte_otro'],2);?></td>
    <td align="right"><? echo number_format(@$potro,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL EJECUTADO</strong></td>
    <td align="right"><? echo number_format($total_evento,2);?></td>
    <td align="right">100.00</td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">IV.- COMENTARIOS/OBSERVACIONES</td>
  </tr>
  <tr>
    <td><? echo $row['problema'];?><br></td>
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
    <td align="center"><? echo $r1['nombre']." ".$r1['apellido']."<br>".$r1['descripcion']." DE ".$row['oficina'];?></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="txt_titulo" align="center">RENDICIÓN DE CUENTAS<BR>
<span class="capa txt_titulo"><? echo "REFERENCIA : INFORME N° ".numeracion($row['cod_clar'])." - ".periodo($row['f_rendicion'])." - ".$row['oficina'];?></span></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%">Responsable del Evento</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $r1['nombre']." ".$r1['apellido']." - ".$r1['descripcion'];?></td>
  </tr>
  <tr>
    <td>Componente</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['codigo_componente']." - ".$row['nombre_componente'];?></td>
  </tr>
  <tr>
    <td>Categoria de Gasto</td>
    <td width="3%" align="center">:</td>
    <td width="75%"><? echo $row['codigo_categoria']." - ".$row['nombre_categoria'];?></td>
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
clar_bd_detalle_gasto_clar.cod_detalle_clar,
clar_bd_detalle_gasto_clar.f_detalle,
clar_bd_detalle_gasto_clar.cod_tipo_gasto,
clar_bd_detalle_gasto_clar.proveedor,
clar_bd_detalle_gasto_clar.ruc,
clar_bd_detalle_gasto_clar.serie,
clar_bd_detalle_gasto_clar.numero,
clar_bd_detalle_gasto_clar.concepto,
clar_bd_detalle_gasto_clar.cod_tipo_importe,
clar_bd_detalle_gasto_clar.monto,
clar_bd_detalle_gasto_clar.cod_rinde_clar,
sys_bd_tipo_importe.descripcion AS importe
FROM
clar_bd_detalle_gasto_clar
INNER JOIN sys_bd_tipo_importe ON sys_bd_tipo_importe.cod_tipo_importe = clar_bd_detalle_gasto_clar.cod_tipo_importe
WHERE
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'
ORDER BY
clar_bd_detalle_gasto_clar.f_detalle ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>
  <tr class="mini">
    <td align="center"><? echo fecha_normal($fila['f_detalle']);?></td>
    <td align="center"><? echo $fila['importe'];?></td>
    <td align="center"><? echo $fila['serie']." - ".$fila['numero'];?></td>
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
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 3 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=  mysql_fetch_array($result);

//Alimentacion
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 1 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f2=  mysql_fetch_array($result);

//Movilidad
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 2 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f3=  mysql_fetch_array($result);

//Materiales
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 4 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=  mysql_fetch_array($result);


//Servicios
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 5 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f5=  mysql_fetch_array($result);

//Combustible
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 6 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f6=  mysql_fetch_array($result);

//Otros
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 0 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f7=  mysql_fetch_array($result);

//Alquiler
$sql="SELECT
Sum(clar_bd_detalle_gasto_clar.monto) AS total
FROM
clar_bd_detalle_gasto_clar
WHERE
clar_bd_detalle_gasto_clar.cod_tipo_gasto = 7 AND
clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f8=  mysql_fetch_array($result);
?>
  <tr>
    <td colspan="4" align="center" class="mini"><strong>TOTAL</strong></td>
    <td align="right"><span class="mini"><? echo number_format($f1['total'],2);?></span></td>
    <td align="right"><span class="mini"><? echo number_format($f2['total'],2);?></span></td>
    <td align="right"><span class="mini"><? echo number_format($f3['total'],2);?></span></td>
    <td align="right" class="mini"><? echo number_format($f6['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($f5['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($f4['total'],2);?></td>
    <td align="right" class="mini"><? echo number_format($f8['total'],2);?></td>
    <td align="right"><span class="mini"><? echo number_format($f7['total'],2);?></span></td>
  </tr>
</table>
<?
//Gastos Declaracion 
//alimentacion
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 1 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f9=mysql_fetch_array($result);


//movilidad
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 2 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f10=mysql_fetch_array($result);

//hospedaje
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 3 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f11=mysql_fetch_array($result);

//otro
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 0 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f12=mysql_fetch_array($result);


//materiales
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 4 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f13=mysql_fetch_array($result);

//servicios
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 5 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f14=mysql_fetch_array($result);

//combustible
$sql="SELECT
Sum(epd_detalle_dj_evento.monto) AS total
FROM
epd_detalle_dj_evento
WHERE
epd_detalle_dj_evento.cod_tipo_gasto = 6 AND
epd_detalle_dj_evento.cod_dj_evento='".$row['cod_dj']."'";
$result=mysql_query($sql) or die (mysql_error());
$f15=mysql_fetch_array($result);
?>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" align="center" class="txt_titulo">DECLARACION JURADA</td>
  </tr>
  <tr>
    <td width="60%"><span class="mini">Total en Hospedaje</span></td>
    <td width="21%" align="center" class="txt_titulo">S/.</td>
    <td width="19%" align="right" class="mini"><? echo number_format($f11['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Alimentación</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f9['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Movilidad local</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f10['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Combustible</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f15['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Materiales</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f13['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Servicios</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f14['total'],2);?></td>
  </tr>
  <tr>
    <td><span class="mini">Total en Otros</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f12['total'],2);?></td>
  </tr>
  <tr>
    <td class="txt_titulo"><span class="mini">TOTAL</span></td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f9['total']+$f10['total']+$f11['total']+$f15['total']+$f13['total']+$f14['total']+$f12['total'],2);?></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="txt_titulo"><span class="mini">SON : <? echo number_format($f9['total']+$f10['total']+$f15['total']+$f13['total']+$f14['total']+$f12['total'],2);?> NUEVOS SOLES</span></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="txt_titulo"><hr></td>
  </tr>
</table>
<table width="90%" border="0"  align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="60%" class="mini">TOTAL GASTADO EN HOSPEDAJE </td>
    <td width="21%" align="center" class="txt_titulo">S/.</td>
    <td width="19%" align="right" class="mini"><? echo number_format($f1['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN ALIMENTACIÓN </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f2['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN MOVILIDAD LOCAL </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f3['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN COMBUSTIBLE</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f6['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN MATERIALES</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f4['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN SERVICIOS</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f5['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN ALQUILERES</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f8['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO EN OTROS </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($f7['total'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO SEGÚN DECLARACIÓN JURADA </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($r3['total_dj'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL GASTADO </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($total_ejecutado_pdss,2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL ENTREGADO POR ADMINISTRACION</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($i2['costo_clar'],2);?></td>
  </tr>
  <tr>
    <td class="mini">TOTAL DEVUELTO SEGUN VOUCHER</td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini"><? echo number_format($row['devolucion'],2);?></td>
  </tr>
  <tr>
    <td class="mini">DIFERENCIA (EN CONTRA) </td>
    <td align="center" class="txt_titulo">S/.</td>
    <td align="right" class="mini">
	<? 
	$aporte_a=$row['devolucion']+$total_ejecutado_pdss;
	$aporte_b=$i2['costo_clar'];
	$diferencia=$aporte_b-$aporte_a;
	
	echo number_format($diferencia,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="33%">Lugar y Fecha</td>
    <td width="3%">:</td>
    <td width="64%"><? echo $row['oficina'];?>, <? echo traducefecha($row['f_rendicion']);?></td>
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
    <td align="center" valign="top"><? echo $r1['nombre']." ".$r1['apellido']."<br> DNI N° ".$r1['n_documento'];?></td>
    <td align="center">&nbsp;</td>
    <td align="center">FIRMA Y SELLO DEL ADMINISTRADOR DEL NEC-PDSS</td>
  </tr>
</table>
<!---------------------------------------------------------------------------------------------------------------------------------------------->
<H1 class=SaltoDePagina> </H1>
<?
$sql="SELECT
clar_bd_cofi_clar.cod_cofi_clar,
clar_bd_cofi_clar.entidad,
clar_bd_cofi_clar.cod_ente,
clar_bd_cofi_clar.aporte,
clar_bd_cofi_clar.cod_rinde_clar
FROM
clar_bd_cofi_clar
WHERE
clar_bd_cofi_clar.cod_rinde_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>
<p></p>
<div class="minititulo" align="center">REGISTRO DE VALORIZACIÓN DE APORTES</div>
<BR>
<div class="capa txt_titulo" align="center">REFERENCIA: INFORME <? echo "N° ".numeracion($row['cod_clar'])." - ".periodo($row['f_rendicion'])." - ".$row['oficina'];?></div>
<br>
<div class="capa txt_titulo" align="center"><? echo $row['evento'];?></div>
<br>
<br>
<div class="capa txt_titulo">ENTIDAD: <? echo $fila1['entidad'];?></div>
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
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_rendicion']);?></div>
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

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../clar/clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_rinde" class="secondary button oculto">Finalizar</a>
</div>



</body>
</html>
