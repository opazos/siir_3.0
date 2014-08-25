<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.- Obtengo la info del contrato
$sql="SELECT org_ficha_taz.nombre, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.f_contrato, 
  pit_bd_ficha_pit.aporte_pdss, 
  pit_bd_ficha_pit.aporte_org, 
  pit_bd_ficha_pit.cod_pit, 
  pit_bd_pit_liquida.ejec_an, 
  pit_bd_pit_liquida.f_liquidacion, 
  pit_bd_pit_liquida.f_desembolso, 
  pit_bd_pit_liquida.n_cheque, 
  pit_bd_pit_liquida.hc_dir, 
  pit_bd_pit_liquida.just_dir, 
  pit_bd_pit_liquida.resultado, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  sys_bd_cargo.descripcion AS cargo, 
  sys_bd_tipo_iniciativa.codigo_iniciativa, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  org_ficha_taz.sector, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  org_ficha_taz.n_documento, 
  org_ficha_taz.f_constitucion, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  pit_bd_pit_liquida.devolucion, 
  pit_bd_pit_liquida.comentario, 
  pit_bd_pit_liquida.aplicacion, 
  pit_bd_pit_liquida.uso, 
  pit_bd_pit_liquida.concurso, 
  pit_bd_pit_liquida.puesto, 
  pit_bd_pit_liquida.premio, 
  pit_bd_ficha_pit.mes, 
  pit_bd_ficha_pit.f_termino, 
  pit_bd_pit_liquida.cod_tipo
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
   INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
   INNER JOIN pit_bd_pit_liquida ON pit_bd_pit_liquida.cod_pit = pit_bd_ficha_pit.cod_pit
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
WHERE pit_bd_pit_liquida.cod_ficha_liq='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//2.- Numero de Planes de negocio
$sql="SELECT COUNT(pit_bd_ficha_pdn.cod_pdn) AS iniciativa
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Numero de Planes de gestion
$sql="SELECT COUNT(pit_bd_ficha_mrn.cod_mrn) AS iniciativa
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_mrn.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//4.- Numero de Ampliacion
$sql="SELECT Count(clar_atf_pdn.cod_atf_pdn) AS iniciativa
FROM clar_ampliacion_pit INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$iniciativa_pdn=$r1['iniciativa']+$r3['iniciativa'];

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-30);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_30 = "'".$fecha."'";

//Familias
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
org_ficha_usuario.titular = 1";
$result=mysql_query($sql) or die (mysql_error());
$total_familias=mysql_num_rows($result);

$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_participantes=mysql_num_rows($result);

//Mujeres
//Mayores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento < $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$muj_mayor=mysql_num_rows($result);


//Menores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento > $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$muj_menor=mysql_num_rows($result);

//Varones
//Mayores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento < $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$var_mayor=mysql_num_rows($result);


//Menores de 30
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento
FROM
org_ficha_organizacion
INNER JOIN pit_bd_ficha_pit ON org_ficha_organizacion.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento > $fecha_30";
$result=mysql_query($sql) or die(mysql_error());
$var_menor=mysql_num_rows($result);


//****** Busco Informacion de addendas
$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.f_inicio, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$total_adenda=mysql_num_rows($result);

//******* Busco Información de montos
$sql="SELECT pit_adenda_pit.aporte_pdss, 
  pit_adenda_pit.aporte_org
FROM pit_adenda_pit
WHERE pit_adenda_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//******* Busco informacion de desembolso
$sql="SELECT clar_atf_pit.monto_desembolsado
FROM clar_atf_pit
WHERE clar_atf_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

$sql="SELECT clar_atf_pit_sd.monto_desembolsado
FROM clar_atf_pit_sd
WHERE clar_atf_pit_sd.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

$total_des_pdss=$r6['monto_desembolsado']+$r7['monto_desembolsado']+$r4['aporte_pdss'];



$total_pdss=$total_des_pdss+$r4['aporte_pdss'];
$total_org=$row['aporte_org']+$r4['aporte_org'];


$total_contrato=$total_pdss+$total_org;



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
<?php
if($total_des_pdss<>0)
{
?>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>LIQUIDACION Y PERFECCIONAMIENTO DE CONTRATO DEL PLAN DE INVERSION TERRITORIAL</u></div>

<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">A</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%" class="txt_titulo">ING. JOSÉ MERCEDES SIALER PASCO</td>
  </tr>
  <tr>
    <td class="txt_titulo">&nbsp;</td>
    <td width="3%" class="txt_titulo">&nbsp;</td>
    <td width="75%" class="txt_titulo">DIRECTOR EJECUTIVO DEL NEC PROYECTO DE DESARROLLO SIERRA SUR II</td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td class="txt_titulo">:</td>
    <td>Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fecha</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['oficina'];?>, <? echo traducefecha($row['f_liquidacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>

<div class="capa justificado">
	
	<p>En relación al documento de la referencia, informo a su despacho, que la organización <strong><? echo $row['nombre'];?></strong>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan:</p>
	
	<ol>
		<li><strong>1</strong> Informe Final de Resultados y Liquidación del Plan de Inversión Territorial.</li>
		<li><strong><? echo number_format($r2['iniciativa']);?></strong> Informe Final de Resultados y Liquidación del Plan de Gestión de Recursos Naturales.</li>
		<li><strong><? echo number_format($iniciativa_pdn);?></strong> Informe Final de Resultados y Liquidación del Plan de Negocios.</li>
		<li> ........ Archivo con documentación en ........ folios.</li>
	</ol>

	<p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por <strong>LIQUIDADO</strong> el Contrato de la referencia por el monto total ejecutado de <strong>S/. <? echo number_format($row['ejec_an'],2);?>(<? echo vuelveletra($row['ejec_an']);?>)</strong>. El mismo que esta conformado de la siguiente manera:</p>

  <p>
    <table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="centrado txt_titulo">
    <td width="29%" rowspan="2">Concepto</td>
    <td colspan="3">SIERRA SUR II</td>
    <td colspan="3">ORGANIZACION</td>
  </tr>

  <tr class="centrado txt_titulo">
    <td>DESEMBOLSADO (S/.)</td>
    <td>EJECUTADO (S/.)</td>
    <td>SALDOS (S/.)</td>
    <td>DESEMBOLSADO (S/.)</td>
    <td>EJECUTADO (S/.)</td>
    <td>SALDOS (S/.)</td>
  </tr>

  <tr>
    <td>Animador Territorial</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_an'],2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$row['ejec_an'],2);?></td>

    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org - $total_org,2);?></td>
  </tr>

  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_an'],2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$row['ejec_an'],2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org - $total_org,2);?></td>
  </tr>
</table>
  </p>

	<p>Por lo expuesto, solicito a usted que, previa conformidad del Responsable del Componente y del Administrador, tenga a bien proceder al <strong>PERFECCIONAMIENTO</strong> de donación sujeto a cargo por el monto de <strong>S/. <? echo number_format($total_des_pdss,2);?> (<? echo vuelveletra($total_des_pdss);?>)</strong>, correspondientes al aporte del Proyecto de Desarrollo Sierra Sur II.</p>	
</div>

<br>
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
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br>".$row['cargo'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE  DE EJECUCION PARCIAL DEL PLAN DE INVERSION TERRITORIAL Y DE LA DONACION SUJETO A CARGO</div>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="center" class="txt_titulo"><U>PROVEIDO DE CONFORMIDAD</u></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="txt_titulo">&nbsp;</td>
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
  <tr>
    <td colspan="2" align="center"><strong><u>LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACION SUJETO A CARGO</u></strong></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO  EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACION CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DEL COMPONENTE Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION LA BAJA CONTABLE  POR EL MONTO PERFECCIONADO DEL PLAN DE NEGOCIO DEL CONTRATO DE LA  REFERENCIA.</div>

<p>&nbsp;</p>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>


<H1 class=SaltoDePagina> </H1>
<?
}
?>
<!-- Aqui comienza el informe final del PIT -->
<? include("encabezado.php");?>  
<p><br/></p>
<p><br/></p>

<div class="capa txt_titulo centrado">NOMBRE DE LA ORGANIZACION TERRITORIAL</div>
<div class="capa gran_titulo centrado"><? echo $row['nombre'];?></div>



<p><br/></p>
<p><br/></p>
<div class="capa gran_titulo" align="center">INFORME FINAL DEL PLAN DE INVERSIÓN TERRITORIAL</div>
<p>&nbsp;</p>
<div class="capa txt_titulo" align="center"><u>LISTA DE INICIATIVAS</u></div>
<br>
<table align="center" border="1" class="mini" width="90%">
  <thead>
    <tr class="txt_titulo centrado">
      <td>N.</td>
      <td>NOMBRE DE LA ORGANIZACION</td>
      <td>TIPO DE INICIATIVA</td>
      <td>LEMA DEL PGRN/DENOMINACION DEL PDN</td>
    </tr>
  </thead>  

  <tbody>
  <?
  //a.- Planes de Negocio
  $num=0;
  $sql="SELECT org_ficha_organizacion.nombre, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
  pit_bd_ficha_pdn.denominacion
  FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
  INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
  WHERE pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."' AND
  pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
  pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $num++
  ?>
    <tr>
      <td class="centrado"><? echo $num;?></td>
      <td><? echo $f1['nombre'];?></td>
      <td class="centrado"><? echo $f1['codigo'];?></td>
      <td><? echo $f1['denominacion'];?></td>
    </tr>
   <?
   }
   $numa=$num;
   $sql="SELECT clar_atf_pdn.cod_atf_pdn, 
    pit_bd_ficha_pdn.denominacion, 
    org_ficha_organizacion.nombre, 
    sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo
    FROM clar_ampliacion_pit INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
    INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
    INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
    WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
    clar_ampliacion_pit.cod_pit='".$row['cod_pit']."' AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
    $result=mysql_query($sql) or die (mysql_error());
    while($f5=mysql_fetch_array($result))
    {
      $numa++
   ?>
    <tr>
      <td class="centrado"><? echo $numa;?></td>
      <td><? echo $f5['nombre'];?></td>
      <td class="centrado"><? echo $f5['codigo']."-".AMPLIACION;?></td>
      <td><? echo $f5['denominacion'];?></td>
    </tr>
   <?php
    }
   //b.- Manejo de recursos naturales
    $numb=$numa;
    $sql="SELECT org_ficha_organizacion.nombre, 
    sys_bd_tipo_iniciativa.codigo_iniciativa AS codigo, 
    pit_bd_ficha_mrn.lema
    FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
    INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
    WHERE pit_bd_ficha_mrn.cod_pit='".$row['cod_pit']."' AND
    pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
    pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
    $result=mysql_query($sql) or die (mysql_error());
    while($f2=mysql_fetch_array($result))
    {
      $numb++
   ?> 
   <tr>
     <td class="centrado"><? echo $numb;?></td>
     <td><? echo $f2['nombre'];?></td>
     <td class="centrado"><? echo $f2['codigo'];?></td>
     <td><? echo $f2['lema'];?></td>
   </tr>
   <?
   }

   ?>
  </tbody>
</table>
    <p>&nbsp;</p>
    <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
      <tr>
        <td width="27%" class="txt_titulo">DEPARTAMENTO</td>
        <td width="2%" class="txt_titulo">:</td>
        <td width="71%"><? echo $row['departamento'];?></td>
      </tr>
      <tr>
        <td class="txt_titulo">PROVINCIA</td>
        <td width="2%" class="txt_titulo">:</td>
        <td width="71%"><? echo $row['provincia'];?></td>
      </tr>
      <tr>
        <td class="txt_titulo">DISTRITO</td>
        <td class="txt_titulo" width="2%">:</td>
        <td width="71%"><? echo $row['distrito'];?></td>
      </tr>
    </table>

  <p>&nbsp;</p>
  <p>&nbsp;</p>
<?php
if($total_des_pdss==0)
{
?>
  <div class="capa gran_titulo centrado">PIT SIN ANIMADOR TERRITORIAL (SOLO PLANES DE NEGOCIOS)</div>
<?php  
}
?>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div class="capa">LUGAR Y FECHA : <? echo $row['oficina'];?>, <? echo traducefecha($row['f_liquidacion']);?></div>

<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?> 
<div class="capa txt_titulo"><p>I.- DATOS DE LA ORGANIZACION TERRITORIAL</p></div>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="25%">1.1.- Nombre de la Organización</td>
    <td colspan="3" width="75%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>  
  <tr class="txt_titulo">
    <td colspan="4">1.2.- Situación Legal</td>
  </tr>

  <tr>
    <td>Tipo de documento</td>
    <td><? echo $row['tipo_doc'];?></td>
    <td>Número de documento</td>
    <td><? echo $row['n_documento'];?></td>
  </tr>
  <tr>
    <td>Tipo de organización</td>
    <td><? echo $row['tipo_org'];?></td>
    <td>Fecha de constitución</td>
    <td><? echo traducefecha($row['f_constitucion']);?></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="4">1.3.- Información contractual</td>
  </tr>
  <tr>
    <td>Número de contrato</td>
    <td><? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato'])."-PIT-".$row['oficina'];?></td>
    <td>Fecha de firma</td>
    <td><? echo traducefecha($row['f_contrato']);?></td>
  </tr>

  <tr>
    <td>Duración</td>
    <td><? echo $row['mes'];?> meses</td>
    <td>Fecha de termino</td>
    <td><? echo traducefecha($row['f_termino']);?></td>
  </tr>

  <?php
  if($total_adenda<>0)
  {
  ?>
  <tr>
    <td colspan="4"><br/></td>
  </tr> 
  <tr>
    <td>Tiene addenda?</td>
    <td><? if ($total_adenda<>0) echo "SI"; else echo "NO";?></td>
    <td>Fecha de firma</td>
    <td><? echo traducefecha($r3['f_adenda']);?></td>
  </tr>
  <tr>
    <td>Duración</td>
    <td><? echo $r3['meses'];?> meses</td>
    <td>Fecha de termino</td>
    <td><? echo traducefecha($r3['f_termino']);?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td colspan="4"><br/></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">1.4.- Del informe de liquidación</td>
  </tr>
  <tr>
    <td>Fecha de presentación del informe</td>
    <td colspan="3"><? echo traducefecha($row['f_liquidacion']);?></td>
  </tr>

  <tr>
    <td>Tipo de liquidación</td>
    <td colspan="3">
      <?php
        if ($row['cod_tipo']==1)
        {
          echo "Liquidación Total - Se liquida el monto total del PIT";
        } 
        else
        {
          echo "Liquidación Parcial - Se liquida el PIT parcialmente debido a que no se a ejecutado el total del presupuesto";
        }
      ?>
    </td>
  </tr>
  <tr>
    <td valign="top">Si se ha realizado una liquidación parcial, indicar los motivos:</td>
    <td colspan="3" class="justificado">
      <? echo $row['comentario'];?>
    </td>
  </tr>  
  <tr>
    <td valign="top">Resultados obtenidos</td>
    <td colspan="3" class="justificado">
      <? echo $row['resultado'];?>
    </td>
  </tr>

</table>
<p><br/></p>


<div class="capa txt_titulo">1.5.- Junta Directiva</div>
<div class="capa centrado"><hr/></div>
<table width="90%" cellspacing="1" cellspacing="1" border="1" align="center" class="mini">
  <thead>
    <tr class="centrado txt_titulo">
      <td>CARGO</td>
      <td>NOMBRES Y APELLIDOS</td>
      <td>DNI</td>
      <td>SEXO</td>
      <td>FECHA DE NACIMIENTO</td>
      <td>VIGENCIA HASTA</td>
    </tr>
  </thead>

  <tbody>
  <?
    $sql="SELECT org_ficha_directiva_taz.n_documento, 
    org_ficha_directiva_taz.nombre, 
    org_ficha_directiva_taz.paterno, 
    org_ficha_directiva_taz.materno, 
    org_ficha_directiva_taz.f_nacimiento, 
    org_ficha_directiva_taz.sexo, 
    sys_bd_cargo_directivo.descripcion AS cargo, 
    org_ficha_directiva_taz.f_termino
    FROM sys_bd_cargo_directivo INNER JOIN org_ficha_directiva_taz ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directiva_taz.cod_cargo_directivo
    INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_directiva_taz.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_directiva_taz.n_documento_taz
    WHERE org_ficha_directiva_taz.vigente=1 AND
    pit_bd_ficha_pit.cod_pit='".$row['cod_pit']."'";
    $result=mysql_query($sql) or die (mysql_error());
    while($f3=mysql_fetch_array($result))
    {
  ?>
    <tr>
      <td class="centrado"><? echo $f3['cargo'];?></td>
      <td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
      <td class="centrado"><? echo $f3['n_documento'];?></td>
      <td class="centrado"><? if ($f3['sexo']==1) echo "M"; else echo "F";?></td>
      <td class="centrado"><? echo fecha_normal($f3['f_nacimiento']);?></td>
      <td class="centrado"><? echo fecha_normal($f3['f_termino']);?></td>
    </tr>
   <?
   }
   ?> 
  </tbody>

</table>
<p><br/></p>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr class="txt_titulo">
    <td colspan="2">1.6.- Familias del Plan de Inversión Territorial</td>
  </tr>
  <tr>
    <td width="25%">Número de familias del Territorio</td>
    <td width="75%"><? echo number_format($total_familias);?> Familias</td>
  </tr>
  <tr>
    <td>Número de participantes del Territorio</td>
    <td><? echo number_format($muj_mayor+$var_mayor+$muj_menor+$var_menor);?> participantes</td>
  </tr>
 </table>   




<div class="capa txt_titulo"><p>Composición de las familias de la organizacion participantes en el PIT</p></div>
  <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini centrado">
    <tr class="txt_titulo">
      <td width="25%">SEXO</td>
      <td width="25%">Mayores de 30 años </td>
      <td width="25%">Menores de 30 años </td>
      <td width="25%">Total</td>
    </tr>
    <tr>
      <td>Mujeres</td>
      <td><?php  echo $muj_mayor;?></td>
      <td><?php  echo $muj_menor;?></td>
      <td><?php  echo $muj_mayor+$muj_menor;?></td>
    </tr>
    <tr>
      <td>Varones</td>
      <td><?php  echo $var_mayor;?></td>
      <td><?php  echo $var_menor;?></td>
      <td><?php  echo $var_mayor+$var_menor;?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td><?php  echo $muj_mayor+$var_mayor;?></td>
      <td><?php  echo $muj_menor+$var_menor;?></td>
      <td><?php  echo $muj_mayor+$var_mayor+$muj_menor+$var_menor;?></td>
    </tr>
  </table>

<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?> 
<div class="capa txt_titulo">II.- ANIMADORES TERRITORIALES</div>

<br/>
<div class="capa txt_titulo"></div>

<table width="90%" cellspacing="1" cellpadding="1" align="center" border="1">
  <thead>
    <tr class="txt_titulo centrado">
      <td>NOMBRES Y APELLIDOS</td>
      <td>DIRECCION</td>
      <td>DNI</td>
      <td>SEXO</td>
      <td>FECHA DE NAC.</td>
      <td>DESDE</td>
      <td>HASTA</td>
      <td>PDSS II</td>
      <td>ORG.</td>
      <td>OTRO</td>
    </tr>
  </thead>

  <tbody>
  <?
  $sql="SELECT ficha_ag_oferente.n_documento, 
  ficha_ag_oferente.nombre, 
  ficha_ag_oferente.paterno, 
  ficha_ag_oferente.materno, 
  ficha_ag_oferente.f_nacimiento, 
  ficha_ag_oferente.sexo, 
  ficha_ag_oferente.direccion, 
  ficha_animador.f_inicio, 
  ficha_animador.f_termino, 
  ficha_animador.aporte_pdss, 
  ficha_animador.aporte_org, 
  ficha_animador.aporte_otro
FROM ficha_animador INNER JOIN ficha_ag_oferente ON ficha_animador.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_animador.n_documento = ficha_ag_oferente.n_documento
   INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = ficha_animador.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = ficha_animador.cod_tipo_iniciativa
WHERE pit_bd_ficha_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
    $aporte_pdss=$f4['aporte_pdss'];
    $total_aporte_pdss=$total_aporte_pdss+$aporte_pdss;

    $aporte_org=$f4['aporte_org'];
    $total_aporte_org=$total_aporte_org+$aporte_org;

    $aporte_otro=$f4['aporte_otro'];
    $total_aporte_otro=$total_aporte_otro+$aporte_otro;        
  ?>
    <tr>
      <td><? echo $f4['nombre']." ".$f4['paterno']." ".$f4['materno'];?></td>
      <td><? echo $f4['direccion'];?></td>
      <td class="centrado"><? echo $f4['n_documento'];?></td>
      <td class="centrado"><? if ($f4['sexo']==1) echo "M"; else echo "F";?></td>
      <td class="centrado"><? echo fecha_normal($f4['f_nacimiento']);?></td>
      <td class="centrado"><? echo fecha_normal($f4['f_inicio']);?></td>
      <td class="centrado"><? echo fecha_normal($f4['f_termino']);?></td>
      <td class="derecha"><? echo number_format($f4['aporte_pdss'],2);?></td>
      <td class="derecha"><? echo number_format($f4['aporte_org'],2);?></td>
      <td class="derecha"><? echo number_format($f4['aporte_otro'],2);?></td>
    </tr>
<?
}
?>
    <tr class="txt_titulo">
      <td colspan="7">TOTALES</td>
      <td class="derecha"><? echo number_format($total_aporte_pdss,2);?></td>
      <td class="derecha"><? echo number_format($total_aporte_org,2);?></td>
      <td class="derecha"><? echo number_format($total_aporte_otro,2);?></td>
    </tr>    
  </tbody>

</table>

<div class="capa txt_titulo"><p>2.2. Actividades desarrolladas y resultados alcanzados</p></div>
<?
$sql="SELECT ficha_ag_oferente.n_documento, 
  ficha_ag_oferente.nombre, 
  ficha_ag_oferente.paterno, 
  ficha_ag_oferente.materno, 
  ficha_ag_oferente.f_nacimiento, 
  ficha_ag_oferente.sexo, 
  ficha_ag_oferente.direccion, 
  ficha_animador.f_inicio, 
  ficha_animador.f_termino, 
  ficha_animador.aporte_pdss, 
  ficha_animador.aporte_org, 
  ficha_animador.aporte_otro, 
  sys_bd_ubigeo_dist.descripcion AS distrito, 
  sys_bd_ubigeo_prov.descripcion AS provincia, 
  sys_bd_ubigeo_dep.descripcion AS departamento, 
  ficha_ag_oferente.especialidad, 
  sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
  sys_bd_tipo_designacion.descripcion AS tipo_designacion, 
  sys_bd_califica.descripcion AS calificacion, 
  sys_bd_estado_iniciativa.descripcion AS estado
FROM ficha_animador INNER JOIN ficha_ag_oferente ON ficha_animador.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_animador.n_documento = ficha_ag_oferente.n_documento
   INNER JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
   INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
   INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
   INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
   INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = ficha_animador.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = ficha_animador.cod_tipo_iniciativa
   INNER JOIN sys_bd_tipo_designacion ON sys_bd_tipo_designacion.cod = ficha_animador.cod_tipo_designacion
   INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_animador.cod_calificacion
   INNER JOIN sys_bd_estado_iniciativa ON ficha_animador.cod_estado_iniciativa = sys_bd_estado_iniciativa.cod_estado_iniciativa
WHERE pit_bd_ficha_pit.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="4">Nombres y apellidos del Especialista</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f5['nombre']." ".$f5['paterno']." ".$f5['materno'];?></td>
  </tr>
  <tr>
    <td width="20%">Nº DNI</td>
    <td width="30%"><? echo $f5['n_documento'];?></td>
    <td width="16%">Direccion</td>
    <td width="34%"><? echo $f5['direccion'];?></td>
  </tr>
  <tr>
    <td>Departamento</td>
    <td width="30%"><? echo $f5['departamento'];?></td>
    <td width="16%">Provincia</td>
    <td width="34%"><? echo $f5['provincia'];?></td>
  </tr>
  <tr>
    <td>Distrito</td>
    <td colspan="3"><? echo $f5['distrito'];?></td>
  </tr>
  <tr>
    <td>Tipo de especialista</td>
    <td width="30%"><? echo $f5['tipo_oferente'];?></td>
    <td width="16%">Especialidad/Profesion</td>
    <td width="34%"><? echo $f5['especialidad'];?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">2.2.1 Vigencia del contrato de Animador Territorial</td>
  </tr>
  <tr>
    <td>Desde</td>
    <td><? echo fecha_normal($f5['f_inicio']);?></td>
    <td>Hasta</td>
    <td><? echo fecha_normal($f5['f_termino']);?></td>
  </tr>
  <tr>
    <td>Como se designo el especialista</td>
    <td><? echo $f5['tipo_designacion'];?></td>
    <td>Calificación de desempeño del especialista</td>
    <td><? echo $f5['calificacion'];?></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>Estado del contrato</td>
    <td colspan="3"><? echo $f5['estado'];?></td>
  </tr>
  <tr>
    <td colspan="4"><hr></td>
  </tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?> 
<div class="capa txt_titulo"><p>III.- DEL MAPA TERRITORIAL</p></div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="txt_titulo">
    <td colspan="2">3.1.- Se aplicó y utilizó el Mapa Territorial ?</td>
    <td><? if ($row['aplicacion']==1) echo "SI  :  X"; else echo "<br/>";?></td>
    <td><? if ($row['aplicacion']==0) echo "NO  :  X"; else echo "<br/>";?></td>
  </tr>

  <tr>
    <td colspan="4"><br/></td>
  </tr>

  <tr>
    <td colspan="2">Si la respuesta es afirmativa. Qué uso se le dió al Mapa Territorial?</td>
    <td colspan="2">
      <?php 
      if ($row['uso']==1)
      {
        echo "SE UTILIZO PARA PRESENTAR AVANCES DEL TERRITORIO";
      } 
      elseif($row['uso']==2)
      {
        echo "SE UTILIZO PARA EVALUAR LOS RESULTADOS DEL TERRITORIO";
      }
      elseif($row['uso']==3)
      {
        echo "SE UTILIZO PARA DIVERSAS PRESENTACIONES";
      }
      elseif($row['uso']==4)
      {
        echo "SE LE DIÓ OTROS USOS";
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">3.2.- Ha ganado algún concurso de Mapas Territoriales</td>
    <td colspan="2"><? if ($row['concurso']==1) echo "SI"; else echo "NO";?></td>
  </tr>
  <tr>
    <td colspan="4"><br/></td>
  </tr>  
  <tr>
    <td colspan="4" class="txt_titulo">Si la respuesta es afirmativa</td>
  </tr>

  <tr>
    <td>Puesto ocupado</td>
    <td><? echo numeracion($row['puesto']);?></td>
    <td>Monto del premio (S/.)</td>
    <td><? echo number_format($row['premio'],2);?></td>
  </tr>
</table>
<p><br/></p>

<div class="capa txt_titulo">IV.- EJECUCIÓN FINANCIERA</div>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="25%">Fecha de segundo desembolso</td>
    <td width="25%"><? if ($row['f_desembolso']<>"0000-00-00") echo traducefecha($row['f_desembolso']); else echo "NO APLICA";?></td>
    <td width="25%">N. de Cheque / CO</td>
    <td width="25%"><? echo $row['n_cheque'];?></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">4.1.- Presupuesto ejecutado</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="centrado txt_titulo">
    <td width="29%" rowspan="2">Concepto</td>
    <td colspan="4">SIERRA SUR II</td>
    <td colspan="3">ORGANIZACION</td>
  </tr>

  <tr class="centrado txt_titulo">
    <td>Monto Depositado (S/.)</td>
    <td>Monto Ejecutado (S/.)</td>
    <td>Monto Devuelto</td>
    <td>% de Ejecucion</td>
    <td>Monto Programado (S/.)</td>
    <td>Monto Ejecutado (S/.)</td>
    <td>% de Ejecución</td>
  </tr>

  <tr>
    <td>Animador Territorial</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_an'],2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$row['ejec_an'],2);?></td>
    <td class="derecha"><? $ppdss=($row['ejec_an']/$total_des_pdss)*100; echo number_format(@$ppdss,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
  </tr>

  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_an'],2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$row['ejec_an'],2);?></td>
    <td class="derecha"><? $ppdss=($row['ejec_an']/$total_des_pdss)*100; echo number_format(@$ppdss,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>
    <td class="derecha"><? echo number_format($total_org,2);?></td>  
  </tr>
</table>
<br/>
<div class="capa txt_titulo">4.2.- Comentarios u observaciones</div>
<div class="capa justificado"><? echo $row['comentario'];?></div>


















<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../pit/pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>








</body>
</html>
