<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	pit_bd_ficha_mrn.mes AS meses, 
	pit_bd_ficha_mrn.f_termino, 
	pit_bd_ficha_mrn.f_presentacion_2, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_taz.nombre AS pit, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_mrn.cif_pdss, 
	pit_bd_ficha_mrn.at_pdss, 
	pit_bd_ficha_mrn.vg_pdss, 
	pit_bd_ficha_mrn.ag_pdss, 
	pit_bd_ficha_mrn.at_org, 
	pit_bd_ficha_mrn.vg_org, 
	pit_bd_mrn_liquida.f_desembolso, 
	pit_bd_mrn_liquida.n_cheque, 
	pit_bd_mrn_liquida.ejec_cif_pdss, 
	pit_bd_mrn_liquida.ejec_at_pdss, 
	pit_bd_mrn_liquida.ejec_at_org, 
	pit_bd_mrn_liquida.ejec_vg_pdss, 
	pit_bd_mrn_liquida.ejec_vg_org, 
	pit_bd_mrn_liquida.ejec_ag_pdss, 
	pit_bd_mrn_liquida.hc_soc, 
	pit_bd_mrn_liquida.just_soc, 
	pit_bd_mrn_liquida.hc_dir, 
	pit_bd_mrn_liquida.just_dir, 
	pit_bd_mrn_liquida.f_liquidacion, 
	sys_bd_califica.descripcion AS calificacion, 
	pit_bd_ficha_mrn.n_voucher_2, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_cp.nombre, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_mrn_liquida.observaciones, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_mrn.monto_organizacion, 
	pit_bd_ficha_mrn.monto_organizacion_2
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN pit_bd_mrn_liquida ON pit_bd_mrn_liquida.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = pit_bd_mrn_liquida.cod_calificacion
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);




//2.- Verificamos si este PGRN tiene adenda
$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_mrn.cod_pit
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'
ORDER BY pit_bd_ficha_adenda.f_termino DESC";
$result=mysql_query($sql) or die (mysql_error());
$f7=mysql_fetch_array($result);
$total_adenda=mysql_num_rows($result);


$sql="SELECT (pit_adenda_mrn.cif_pdss+ 
  pit_adenda_mrn.at_pdss+ 
  pit_adenda_mrn.ag_pdss) AS total_pdss, 
  pit_bd_ficha_adenda.cod_adenda, 
  pit_adenda_mrn.cod_iniciativa, 
  pit_adenda_mrn.cif_pdss, 
  pit_adenda_mrn.at_pdss, 
  pit_adenda_mrn.at_org, 
  pit_adenda_mrn.ag_pdss, 
  pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.f_inicio, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda INNER JOIN pit_adenda_mrn ON pit_bd_ficha_adenda.cod_adenda = pit_adenda_mrn.cod_adenda
WHERE pit_adenda_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f6=mysql_fetch_array($result);







//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-25);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_25 = "'".$fecha."'";

//***** desde aca cargo todos los aportes de otros
//SAT
$sql="SELECT sum(ficha_sat.aporte_otro) as aporte_otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//AG
$sql="SELECT SUM(ficha_aag.aporte_otro) AS aporte_otro, 
	SUM(ficha_aag.aporte_org) AS aporte_org
FROM pit_bd_ficha_mrn INNER JOIN ficha_aag ON pit_bd_ficha_mrn.cod_mrn = ficha_aag.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//VG
$sql="SELECT SUM(ficha_vg.aporte_otro) AS aporte_otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_vg ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$total_cif_pdss=$row['cif_pdss']+$f6['cif_pdss'];
$total_at_pdss=$row['at_pdss']+$f6['at_pdss'];
$total_ag_pdss=$row['ag_pdss']+$f6['ag_pdss'];

$total_at_org=$row['at_org']+$f6['at_org'];
$total_ag_org=$r4['aporte_org'];


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
<p><br/></p>
<div class="capa gran_titulo centrado">INFORME  FINAL DE RESULTADOS Y LIQUIDACION DEL PLAN DE GESTION DE RECURSOS NATURALES</div>
<p><br/></p>
<div class="capa gran_titulo centrado">NOMBRE DE LA ORGANIZACIÓN TERRITORIAL</div>
<div class="capa centrado"><? echo $row['pit'];?></div>
<p><br/></p>
<div class="capa gran_titulo centrado">NOMBRE DE LA ORGANIZACION</div>
<div class="capa centrado"><? echo $row['organizacion'];?></div>
<p><br/></p>
<div class="capa gran_titulo centrado">LEMA DEL PLAN DE GESTION</div>
<div class="capa centrado"><? echo $row['lema'];?></div>
<p><br/></p>
<p><br/></p>
<table width="80%" cellpadding="1" cellspacing="1" align="center">
	
	<tr>
		<td width="50%">CONTRATO Nº</td>
		<td width="50%"><? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato']);?></td>
	</tr>
	<tr>
		<td>FECHA DEL INFORME</td>
		<td><? echo traducefecha($row['f_liquidacion']);?></td>
	</tr>
	<tr>
		<td>DEPARTAMENTO</td>
		<td><? echo $row['departamento'];?></td>
	</tr>
	<tr>
		<td>PROVINCIA</td>
		<td><? echo $row['provincia'];?></td>
	</tr>	
	<tr>
		<td>DISTRITO</td>
		<td><? echo $row['distrito'];?></td>
	</tr>	
	<tr>
		<td>SECTOR</td>
		<td><? echo $row['cp'];?></td>
	</tr>	
</table>
<H1 class=SaltoDePagina></H1>

<? include("encabezado.php");?>
<div class="capa txt_titulo">I.- DATOS GENERALES</div>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="4">1.1.- Organizacion Responsable del PIT</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['pit'];?></td>
  </tr>
  <tr>
    <td colspan="4">1.2.- Organización Responsable del Plan de Gestión</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="4">1.3.- Lema del Plan de Gestión</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['lema'];?></td>
  </tr>
  <tr>
    <td width="25%">Fecha de Inicio (según contrato)</td>
    <td width="25%"><? echo traducefecha($row['f_contrato']);?></td>
    <td width="25%">Fecha de termino</td>
    <td width="25%"><? echo traducefecha($row['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4"><hr/></td>
  </tr>
  <!-- Aqui ingresamos la información de la adenda -->
  <?php
  if($total_adenda<>0)
  {
  ?>
  <tr>
    <td>¿Esta iniciativa tiene addenda?</td>
    <td><? if ($total_adenda<>0) echo "SI"; else echo "NO";?></td>
    <td>Fecha de Addenda</td>
    <td><? echo traducefecha($f7['f_adenda']);?></td>
  </tr>
  <tr>
    <td>Duración</td>
    <td><? echo $f7['meses'];?> meses</td>
    <td>Fecha de término</td>
    <td><? echo traducefecha($f7['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4"><hr/></td>
  </tr>
  <?
  }
  ?>  
  <tr>
    <td>Fecha de informe</td>
    <td><? echo traducefecha($row['f_liquidacion']);?></td>
    <td>Calificación de la Iniciativa</td>
    <td><? echo $row['calificacion'];?></td>
  </tr>
</table>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3" class="txt_titulo">II.- SITUACION ORGANIZACIONAL</td>
  </tr>
  <tr>
    <td colspan="3">2.1.- Junta Directiva</td>
  </tr>
  <tr>
    <td width="76%">¿Hubieron cambios?</td>
    <td width="12%">Si <input type="radio" name="cb1" <? if ($row['hc_dir']==1) echo "checked";?>></td>
    <td width="12%">No <input type="radio" name="cb1" <? if ($row['hc_dir']==0) echo "checked";?>></td>
  </tr>
  <tr>
    <td colspan="3">¿Por qué?</td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['just_dir'];?></td>
  </tr>
  <tr>
    <td colspan="3"> Junta directiva vigente</td>
  </tr>
</table>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
<thead>
  <tr>
    <th>Nº</th>
    <th>DNI</th>
    <th>Nombres y apellidos</th>
    <th>Cargo</th>
    <th>Sexo</th>
    <th>Fecha de nacimiento</th>
    <th>Vigencia hasta</th>
  </tr>
 </thead> 
 <tbody>
<?
$na=0;
$sql="SELECT org_ficha_directivo.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  sys_bd_cargo_directivo.descripcion AS cargo, 
  org_ficha_usuario.sexo, 
  org_ficha_usuario.f_nacimiento, 
  org_ficha_directivo.f_termino
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
   INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE pit_bd_ficha_mrn.cod_mrn='$cod' AND
org_ficha_directivo.vigente=1
ORDER BY org_ficha_directivo.f_inicio ASC, org_ficha_directivo.cod_cargo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$na++
?> 
  <tr>
    <td class="centrado"><? echo $na;?></td>
    <td class="centrado"><? echo $f1['n_documento'];?></td>
    <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
    <td><? echo $f1['cargo'];?></td>
    <td class="centrado"><? if ($f1['sexo']==1) echo "M"; else echo "F";?></td>
    <td class="centrado"><? echo fecha_normal($f1['f_nacimiento']);?></td>
    <td class="centrado"><? echo fecha_normal($f1['f_termino']);?></td>
  </tr>
<?
}
?>  
 </tbody>
</table>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="3">2.2.- Participantes ejecutando el Plan de Gestión</td>
  </tr>
  <tr>
    <td width="76%">¿Hubieron cambios?</td>
    <td width="12%">Si
      <input type="radio" name="cb2" <? if ($row['hc_soc']==1) echo "checked";?>></td>
    <td width="12%">No
      <input type="radio" name="cb2" <? if ($row['hc_soc']==0) echo "checked";?>></td>
  </tr>
  <tr>
    <td colspan="3">¿Por qué?</td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['just_soc'];?></td>
  </tr>
</table>
<br/>
<?php
//1.- Familias según contrato Varones
$sql="SELECT DISTINCT org_ficha_usuario.n_documento AS dni
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_mrn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_mrn='$cod' AND
org_ficha_usuario.titular=1 AND
org_ficha_usuario.sexo=1 AND
pit_bd_user_iniciativa.momento=1";
$result=mysql_query($sql) or die (mysql_error());
$total_fam_var=mysql_num_rows($result);

//2.- Familias según contrato Mujeres
$sql="SELECT DISTINCT org_ficha_usuario.n_documento AS dni
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_mrn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_mrn='$cod' AND
org_ficha_usuario.titular=1 AND
org_ficha_usuario.sexo=0 AND
pit_bd_user_iniciativa.momento=1";
$result=mysql_query($sql) or die (mysql_error());
$total_fam_muj=mysql_num_rows($result);

//3.1.-Participantes Var concurso 1
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=1 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$cif_1_var=mysql_num_rows($result);

//3.2.-Participantes Muj concurso 1
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=1 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$cif_1_muj=mysql_num_rows($result);

//4.1.-Participantes Var concurso 2
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=2 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$cif_2_var=mysql_num_rows($result);

//4.2.-Participantes Muj concurso 2
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=2 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$cif_2_muj=mysql_num_rows($result);

//4.1.-Participantes Var concurso 3
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=3 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$cif_3_var=mysql_num_rows($result);

//4.2.-Participantes Muj concurso 3
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=3 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$cif_3_muj=mysql_num_rows($result);

//4.1.-Participantes Var concurso 4
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=4 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$cif_4_var=mysql_num_rows($result);

//4.2.-Participantes Muj concurso 4
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=4 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$cif_4_muj=mysql_num_rows($result);

//4.1.-Participantes Var concurso 5
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=5 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$cif_5_var=mysql_num_rows($result);

//4.2.-Participantes Muj concurso 5
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=5 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$cif_5_muj=mysql_num_rows($result);
?>


<table align="center" width="90%" border="1" cellspacing="1" cellpadding="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%"></td>
    <td width="20%">Varones</td>
    <td width="20%">Mujeres</td>
    <td width="20%">Total</td>
  </tr>

  <tr>
    <td class="txt_titulo">N. de familias según contrato</td>
    <td class="derecha"><? echo number_format($total_fam_var);?></td>
    <td class="derecha"><? echo number_format($total_fam_muj);?></td>
    <td class="derecha"><? echo number_format($total_fam_var+$total_fam_muj);?></td>
  </tr>

  <tr>
    <td class="txt_titulo">N. de participantes primer concurso</td>
    <td class="derecha"><? echo number_format($cif_1_var);?></td>
    <td class="derecha"><? echo number_format($cif_1_muj);?></td>
    <td class="derecha"><? echo number_format($cif_1_var+$cif_1_muj);?></td>
  </tr>  

  <tr>
    <td class="txt_titulo">N. de participantes segundo concurso</td>
    <td class="derecha"><? echo number_format($cif_2_var);?></td>
    <td class="derecha"><? echo number_format($cif_2_muj);?></td>
    <td class="derecha"><? echo number_format($cif_2_var+$cif_2_muj);?></td>
  </tr>   

  <tr>
    <td class="txt_titulo">N. de participantes tercer concurso</td>
    <td class="derecha"><? echo number_format($cif_3_var);?></td>
    <td class="derecha"><? echo number_format($cif_3_muj);?></td>
    <td class="derecha"><? echo number_format($cif_3_var+$cif_3_muj);?></td>
  </tr> 

  <tr>
    <td class="txt_titulo">N. de participantes cuarto concurso</td>
    <td class="derecha"><? echo number_format($cif_4_var);?></td>
    <td class="derecha"><? echo number_format($cif_4_muj);?></td>
    <td class="derecha"><? echo number_format($cif_4_var+$cif_4_muj);?></td>
  </tr>

  <tr>
    <td class="txt_titulo">N. de participantes quinto concurso</td>
    <td class="derecha"><? echo number_format($cif_5_var);?></td>
    <td class="derecha"><? echo number_format($cif_5_muj);?></td>
    <td class="derecha"><? echo number_format($cif_5_var+$cif_5_muj);?></td>
  </tr>    

</table>




<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">III.- AVANCE DE LA ASISTENCIA TECNICA</div>



<br/>
<?
$sql="SELECT ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	ficha_sat.aporte_pdss, 
	ficha_sat.aporte_org, 
	ficha_sat.aporte_otro, 
	ficha_sat.resultado, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.direccion, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	sys_bd_actividad_mrn.descripcion AS tema, 
	pit_bd_ficha_mrn.cod_mrn, 
	ficha_sat.cod_sat, 
	sys_bd_tipo_designacion.descripcion AS tipo_designacion, 
	sys_bd_califica.descripcion AS calificacion, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM ficha_sat INNER JOIN pit_bd_ficha_mrn ON ficha_sat.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND ficha_sat.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = ficha_sat.tema
	 INNER JOIN sys_bd_tipo_designacion ON sys_bd_tipo_designacion.cod = ficha_sat.cod_tipo_designacion
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ficha_sat.cod_estado_iniciativa
	 LEFT JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'
ORDER BY ficha_sat.f_inicio ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr class="txt_titulo">
    <td colspan="4">3.1 Actividad en la que brindo Asistencia Tecnica</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f3['tema'];?></td>
  </tr>
  <tr>
    <td colspan="4">Nombres y apellidos del Especialista</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
  </tr>
  <tr>
    <td width="20%">Nº DNI</td>
    <td width="30%"><? echo $f3['n_documento'];?></td>
    <td width="16%">Direccion</td>
    <td width="34%"><? echo $f3['direccion'];?></td>
  </tr>
  <tr>
    <td>Departamento</td>
    <td width="30%"><? echo $f3['departamento'];?></td>
    <td width="16%">Provincia</td>
    <td width="34%"><? echo $f3['provincia'];?></td>
  </tr>
  <tr>
    <td>Distrito</td>
    <td colspan="3"><? echo $f3['distrito'];?></td>
  </tr>
  <tr>
    <td>Tipo de oferente</td>
    <td width="30%"><? echo $f3['tipo_oferente'];?></td>
    <td width="16%">Especialidad/Profesion</td>
    <td width="34%"><? echo $f3['especialidad'];?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">3.1.1 Vigencia del contrato de Asistencia Tecnica</td>
  </tr>
  <tr>
    <td>Desde</td>
    <td><? echo fecha_normal($f3['f_inicio']);?></td>
    <td>Hasta</td>
    <td><? echo fecha_normal($f3['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">3.1.2 Resultados o cambios logrados con esta Asistencia Tecnica</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f3['resultado'];?></td>
  </tr>
  <tr>
    <td colspan="4">- Nº de Varones a los que se brindo Asistencia tecnica: <? echo $f3['n_varones'];?> personas</td>
  </tr>
  <tr>
    <td colspan="4">- Nº de mujeres a las que se brindo Asistencia tecnica : <? echo $f3['n_mujeres'];?> personas</td>
  </tr>
  <tr>
    <td>Como se designo el asistente tecnico</td>
    <td><? echo $f3['tipo_designacion'];?></td>
    <td>Calificación de desempeño del Asistente Tecnico</td>
    <td><? echo $f3['calificacion'];?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">3.1.3 Costos y estado situacional del contrato de Asistencia Tecnica</td>
  </tr>
  <tr>
    <td>Financiamiento Sierra Sur II</td>
    <td colspan="3"><? echo number_format($f3['aporte_pdss'],2);?></td>
  </tr>
  <tr>
    <td>Financiamiento Organizacion</td>
    <td colspan="3"><? echo number_format($f3['aporte_org'],2);?></td>
  </tr>
  <tr>
    <td>Financiamiento Otros</td>
    <td colspan="3"><? echo number_format($f3['aporte_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>Estado del contrato</td>
    <td colspan="3"><? echo $f3['estado'];?></td>
  </tr>
  <tr>
    <td colspan="4"><hr></td>
  </tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">IV.- RESULTADOS DEL APOYO A LA GESTIÓN</div>

<br/>
<?
$sql="SELECT ficha_aag.f_inicio, 
	ficha_aag.f_termino, 
	ficha_aag.aporte_pdss, 
	ficha_aag.aporte_org, 
	ficha_aag.aporte_otro, 
	ficha_aag.resultado, 
	sys_bd_tipo_designacion.descripcion AS tipo_designacion, 
	sys_bd_califica.descripcion AS calificacion, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.direccion, 
	ficha_ag_oferente.especialidad, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento
FROM pit_bd_ficha_mrn INNER JOIN ficha_aag ON pit_bd_ficha_mrn.cod_mrn = ficha_aag.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_designacion ON sys_bd_tipo_designacion.cod = ficha_aag.cod_tipo_designacion
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_aag.cod_calificacion
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ficha_aag.cod_estado_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_aag.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_aag.n_documento
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 INNER JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
	 INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="4" class="txt_titulo">4.1 Apoyo a la gestión del Plan de Gestión de Recursos Naturales</td>
  </tr>
  <tr>
    <td colspan="4">Nombres y apellidos del especialista</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f4['nombre']." ".$f4['paterno']." ".$f4['materno'];?></td>
  </tr>
  <tr>
    <td width="23%">Nº DNI</td>
    <td width="27%"><? echo $f4['n_documento'];?></td>
    <td width="24%">Direccion</td>
    <td width="26%"><? echo $f4['direccion'];?></td>
  </tr>
  <tr>
    <td>Departamento</td>
    <td width="27%"><? echo $f4['departamento'];?></td>
    <td width="24%">Provincia</td>
    <td width="26%"><? echo $f4['provincia'];?></td>
  </tr>
  <tr>
    <td>Distrito</td>
    <td colspan="3"><? echo $f4['distrito'];?></td>
  </tr>
  <tr>
    <td>Tipo de especialista</td>
    <td width="27%"><? echo $f4['tipo_oferente'];?></td>
    <td width="24%">Especialidad /Profesion</td>
    <td width="26%"><? echo $f4['especialidad'];?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">4.1.1 Vigencia del contrato de Apoyo a la Gestión</td>
  </tr>
  <tr>
    <td>Inicio</td>
    <td><? echo fecha_normal($f4['f_inicio']);?></td>
    <td>Termino</td>
    <td><? echo fecha_normal($f4['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">4.1.2 Resultados alcanzados con el Apoyo a la Gestión</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f4['resultado'];?></td>
  </tr>
  <tr>
    <td>¿Como se asigno al Profesional?</td>
    <td><? echo $f4['tipo_designacion'];?></td>
    <td>Calificación de desempeño</td>
    <td><? echo $f4['calificacion'];?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">4.1.3 Costo y estado situacional del contrato de Apoyo a la Gestión</td>
  </tr>
  <tr>
    <td>Financiamiento Sierra Sur II</td>
    <td colspan="3"><? echo number_format($f4['aporte_pdss'],2);?></td>
  </tr>
  <tr>
    <td>Financiamiento Organización</td>
    <td colspan="3"><? echo number_format($f4['aporte_org'],2);?></td>
  </tr>
  <tr>
    <td>Financiamiento Otros</td>
    <td colspan="3"><? echo number_format($f4['aporte_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>Estado del contrato</td>
    <td colspan="3"><? echo $f4['estado'];?></td>
  </tr>
  <tr>
    <td colspan="4"><hr></td>
  </tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">V.- VISITA GUIADA</div>
<br/>
<?
$sql="SELECT ficha_vg.f_inicio, 
	ficha_vg.f_termino, 
	ficha_vg.lugar, 
	ficha_vg.objetivo, 
	ficha_vg.resultado, 
	ficha_vg.aporte_pdss, 
	ficha_vg.aporte_org, 
	ficha_vg.aporte_otro, 
	ficha_vg.cod_visita
FROM pit_bd_ficha_mrn INNER JOIN ficha_vg ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr class="txt_titulo">
    <td colspan="4">5.1 Lugar de la Visita Guiada</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f5['lugar'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="4">5.2 Objetivo de la Visita Guiada</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $f5['objetivo'];?></td>
  </tr>
  <tr>
    <td width="23%">Inicio</td>
    <td width="27%"><? echo fecha_normal($f5['f_inicio']);?></td>
    <td width="24%">Termino</td>
    <td width="26%"><? echo fecha_normal($f5['f_termino']);?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="4">5.3 Numero de participantes</td>
  </tr>
</table>
<br/>
<?
//1.- Participantes varones
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=1 AND 
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result1=mysql_query($sql) or die (mysql_error());
$t1=mysql_num_rows($result1);

//2.- Participantes mujeres
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=0 AND 
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result2=mysql_query($sql) or die (mysql_error());
$t2=mysql_num_rows($result2);

//3.- Mujeres mayores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=0 AND 
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result3=mysql_query($sql) or die (mysql_error());
$t3=mysql_num_rows($result3);

//4.- Hombres mayores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=1 AND 
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result4=mysql_query($sql) or die (mysql_error());
$t4=mysql_num_rows($result4);

//5.- Mujeres menores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=0 AND 
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result5=mysql_query($sql) or die (mysql_error());
$t5=mysql_num_rows($result5);

//6.- Hombres menores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=1 AND 
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result6=mysql_query($sql) or die (mysql_error());
$t6=mysql_num_rows($result6);
?>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td width="59%">Sexo</td>
    <td width="14%">Menores de 25 años</td>
    <td width="13%">Mayores de 25 años</td>
    <td width="14%">Total</td>
  </tr>
  <tr>
    <td>Mujeres</td>
    <td width="14%" class="derecha"><? echo $t5;?></td>
    <td width="13%" class="derecha"><? echo $t3;?></td>
    <td width="14%" class="derecha"><? echo $t2;?></td>
  </tr>
  <tr>
    <td>Varones</td>
    <td width="14%" class="derecha"><? echo $t6;?></td>
    <td width="13%" class="derecha"><? echo $t4;?></td>
    <td width="14%" class="derecha"><? echo $t1;?></td>
  </tr>
  <tr class="txt_titulo">
    <td>Total</td>
    <td width="14%" class="derecha"><? echo $t5+$t6;?></td>
    <td width="13%" class="derecha"><? echo $t3+$t4;?></td>
    <td width="14%" class="derecha"><? echo $t1+$t2;?></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">5.4 Monto ejecutados de cofinanciamiento de la visita guiada</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td width="59%">Financiamiento</td>
    <td width="21%">Monto Ejecutado (S/.)</td>
    <td width="20%">Asignacion por participante</td>
  </tr>
  <tr>
    <td>Sierra Sur II</td>
    <td width="21%" class="derecha"><? echo number_format($f5['aporte_pdss'],2);?></td>
    <td width="20%" class="derecha"><? echo number_format($f5['aporte_pdss']/($t1+$t2),2);?></td>
  </tr>
  <tr>
    <td>Organizacion</td>
    <td width="21%" class="derecha"><? echo number_format($f5['aporte_org'],2);?></td>
    <td width="20%" class="derecha"><? echo number_format($f5['aporte_org']/($t1+$t2),2);?></td>
  </tr>
  <tr>
    <td>Otros</td>
    <td width="21%" class="derecha"><? echo number_format($f5['aporte_otro'],2);?></td>
    <td width="20%" class="derecha"><? echo number_format($f5['aporte_otro']/($t1+$t2),2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td>Total</td>
    <td width="21%" class="derecha"><? echo number_format($f5['aporte_pdss']+$f5['aporte_org']+$f5['aporte_otro'],2);?></td>
    <td width="20%" class="derecha"><? echo number_format(($f5['aporte_pdss']+$f5['aporte_org']+$f5['aporte_otro'])/($t1+$t2),2);?></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">5.5 aprendizajes de los participantes, en la visita guiada</div>
<br/>
<div class="capa"><? echo $f5['resultado'];?></div>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">VI.- CONCURSOS INTERFAMILIARES</div>
<br/>
<!-- Data de Concursos Interfamiliares -->
<?php
//1.- N. participantes premiados varones
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=1 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_1_var=mysql_num_rows($result);

//2.- N. participantes premiados mujeres
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=1 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_1_muj=mysql_num_rows($result);

//3.- Monto total de premios
$sql="SELECT SUM(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=1";
$result=mysql_query($sql) or die (mysql_error());
$i=mysql_fetch_array($result);

//4.- Obtener el premio maximo
$sql="SELECT MAX(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=1";
$result=mysql_query($sql) or die (mysql_error());
$j=mysql_fetch_array($result);

//5.- Obtener el premio minimo
$sql="SELECT MIN(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=1";
$result=mysql_query($sql) or die (mysql_error());
$k=mysql_fetch_array($result);

//6.- Info Concurso
$sql="SELECT cif_bd_concurso.f_concurso, 
  cif_bd_concurso.costo,
  cif_bd_concurso.monto_premio,
  cif_bd_concurso.monto_otro, 
  act_1.descripcion AS act1, 
  act_1.unidad AS unidad1, 
  act2.descripcion AS act2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS act3, 
  act3.unidad AS unidad3
FROM sys_bd_actividad_mrn act_1 LEFT JOIN cif_bd_concurso ON act_1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=1";
$result=mysql_query($sql) or die (mysql_error());
$l=mysql_fetch_array($result);
$total_cif_1=mysql_num_rows($result);
if($total_cif_1<>0)
{
?>
<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td>Concurso</td>
    <td>N. Mujeres</td>
    <td>N. Varones</td>
    <td>Total</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">1er Concurso Interfamiliar</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">Temas en los que se trabajó</td>
  </tr>
  <tr>
    <td colspan="4">
    <ol>
      <?php if($l['act1']<>NULL) echo "<li>".$l['act1']."</li>";?>
      <?php if($l['act2']<>NULL) echo "<li>".$l['act2']."</li>";?>
      <?php if($l['act3']<>NULL) echo "<li>".$l['act3']."</li>";?>
    </ol>
    </td>
  </tr>

  <tr>
    <td colspan="3">Costo del concurso (S/.)</td>
    <td class="derecha"><? echo number_format($l['costo'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para premios (S/.)</td>
    <td class="derecha"><? echo number_format($l['monto_premio'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para otros fines (dietas, gastos, etc) (S/.)</td>
    <td class="derecha"><? echo number_format($l['monto_otro'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Fecha del concurso</td>
    <td class="derecha"><? echo fecha_normal($l['f_concurso']);?></td>
  </tr>  
  <tr>
    <td>N. de participantes</td>
    <td class="derecha"><? echo number_format($cif_1_muj);?></td>
    <td class="derecha"><? echo number_format($cif_1_var);?></td>
    <td class="derecha"><? echo number_format($cif_1_muj+$cif_1_var);?></td>
  </tr>

  <tr>
    <td>N. de participantes premiados</td>
    <td class="derecha"><? echo number_format($prem_cif_1_muj);?></td>
    <td class="derecha"><? echo number_format($prem_cif_1_var);?></td>
    <td class="derecha"><? echo number_format($prem_cif_1_muj+$prem_cif_1_var);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto total ejecutado en premios en (S/.)</td>
    <td class="derecha"><? echo number_format($i['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio mayor en (S/.)</td>
    <td class="derecha"><? echo number_format($j['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio menor en (S/.)</td>
    <td class="derecha"><? echo number_format($k['total'],2);?></td>
  </tr>  
</table>
<br/>
<?php
}
?>
<!-- Segundo concurso interfamiliar -->
<?php
//1.- N. participantes premiados varones
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=2 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_2_var=mysql_num_rows($result);

//2.- N. participantes premiados mujeres
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=2 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_2_muj=mysql_num_rows($result);

//3.- Monto total de premios
$sql="SELECT SUM(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=2";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);

//4.- Obtener el premio maximo
$sql="SELECT MAX(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=2";
$result=mysql_query($sql) or die (mysql_error());
$j2=mysql_fetch_array($result);

//5.- Obtener el premio minimo
$sql="SELECT MIN(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=2";
$result=mysql_query($sql) or die (mysql_error());
$k2=mysql_fetch_array($result);

//6.- Info Concurso
$sql="SELECT cif_bd_concurso.f_concurso, 
  cif_bd_concurso.costo, 
  cif_bd_concurso.monto_premio,
  cif_bd_concurso.monto_otro, 
  act_1.descripcion AS act1, 
  act_1.unidad AS unidad1, 
  act2.descripcion AS act2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS act3, 
  act3.unidad AS unidad3
FROM sys_bd_actividad_mrn act_1 LEFT JOIN cif_bd_concurso ON act_1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=2";
$result=mysql_query($sql) or die (mysql_error());
$l2=mysql_fetch_array($result);
$total_cif_2=mysql_num_rows($result);
if($total_cif_2<>0)
{
?>
<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td>Concurso</td>
    <td>N. Mujeres</td>
    <td>N. Varones</td>
    <td>Total</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">2do Concurso Interfamiliar</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">Temas en los que se trabajó</td>
  </tr>
  <tr>
    <td colspan="4">
    <ol>
      <?php if($l2['act1']<>NULL) echo "<li>".$l2['act1']."</li>";?>
      <?php if($l2['act2']<>NULL) echo "<li>".$l2['act2']."</li>";?>
      <?php if($l2['act3']<>NULL) echo "<li>".$l2['act3']."</li>";?>
    </ol>
    </td>
  </tr>  
  <tr>
    <td colspan="3">Costo del concurso (S/.)</td>
    <td class="derecha"><? echo number_format($l2['costo'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para premios (S/.)</td>
    <td class="derecha"><? echo number_format($l2['monto_premio'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para otros fines (dietas, gastos, etc) (S/.)</td>
    <td class="derecha"><? echo number_format($l2['monto_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Fecha del concurso</td>
    <td class="derecha"><? echo fecha_normal($l2['f_concurso']);?></td>
  </tr>  
  <tr>
    <td>N. de participantes</td>
    <td class="derecha"><? echo number_format($cif_2_muj);?></td>
    <td class="derecha"><? echo number_format($cif_2_var);?></td>
    <td class="derecha"><? echo number_format($cif_2_muj+$cif_2_var);?></td>
  </tr>

  <tr>
    <td>N. de participantes premiados</td>
    <td class="derecha"><? echo number_format($prem_cif_2_muj);?></td>
    <td class="derecha"><? echo number_format($prem_cif_2_var);?></td>
    <td class="derecha"><? echo number_format($prem_cif_2_muj+$prem_cif_2_var);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto total ejecutado en premios en (S/.)</td>
    <td class="derecha"><? echo number_format($i2['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio mayor en (S/.)</td>
    <td class="derecha"><? echo number_format($j2['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio menor en (S/.)</td>
    <td class="derecha"><? echo number_format($k2['total'],2);?></td>
  </tr>  
</table>
<br/>
<?php
}
?>
<!-- Tercer concurso interfamiliar -->
<?php
//1.- N. participantes premiados varones
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=3 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_3_var=mysql_num_rows($result);

//2.- N. participantes premiados mujeres
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=3 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_3_muj=mysql_num_rows($result);

//3.- Monto total de premios
$sql="SELECT SUM(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=3";
$result=mysql_query($sql) or die (mysql_error());
$i3=mysql_fetch_array($result);

//4.- Obtener el premio maximo
$sql="SELECT MAX(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=3";
$result=mysql_query($sql) or die (mysql_error());
$j3=mysql_fetch_array($result);

//5.- Obtener el premio minimo
$sql="SELECT MIN(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=3";
$result=mysql_query($sql) or die (mysql_error());
$k3=mysql_fetch_array($result);

//6.- Info Concurso
$sql="SELECT cif_bd_concurso.f_concurso, 
  cif_bd_concurso.costo,
  cif_bd_concurso.monto_premio,
  cif_bd_concurso.monto_otro,    
  act_1.descripcion AS act1, 
  act_1.unidad AS unidad1, 
  act2.descripcion AS act2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS act3, 
  act3.unidad AS unidad3
FROM sys_bd_actividad_mrn act_1 LEFT JOIN cif_bd_concurso ON act_1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=3";
$result=mysql_query($sql) or die (mysql_error());
$l3=mysql_fetch_array($result);
$total_cif_3=mysql_num_rows($result);
if($total_cif_3<>0)
{
?>
<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td>Concurso</td>
    <td>N. Mujeres</td>
    <td>N. Varones</td>
    <td>Total</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">3er Concurso Interfamiliar</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">Temas en los que se trabajó</td>
  </tr>
  <tr>
    <td colspan="4">
    <ol>
      <?php if($l3['act1']<>NULL) echo "<li>".$l3['act1']."</li>";?>
      <?php if($l3['act2']<>NULL) echo "<li>".$l3['act2']."</li>";?>
      <?php if($l3['act3']<>NULL) echo "<li>".$l3['act3']."</li>";?>
    </ol>
    </td>
  </tr>  
  <tr>
    <td colspan="3">Costo del concurso (S/.)</td>
    <td class="derecha"><? echo number_format($l3['costo'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para premios (S/.)</td>
    <td class="derecha"><? echo number_format($l3['monto_premio'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para otros fines (dietas, gastos, etc) (S/.)</td>
    <td class="derecha"><? echo number_format($l3['monto_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Fecha del concurso</td>
    <td class="derecha"><? echo fecha_normal($l3['f_concurso']);?></td>
  </tr>  
  <tr>
    <td>N. de participantes</td>
    <td class="derecha"><? echo number_format($cif_3_muj);?></td>
    <td class="derecha"><? echo number_format($cif_3_var);?></td>
    <td class="derecha"><? echo number_format($cif_3_muj+$cif_3_var);?></td>
  </tr>

  <tr>
    <td>N. de participantes premiados</td>
    <td class="derecha"><? echo number_format($prem_cif_3_muj);?></td>
    <td class="derecha"><? echo number_format($prem_cif_3_var);?></td>
    <td class="derecha"><? echo number_format($prem_cif_3_muj+$prem_cif_3_var);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto total ejecutado en premios en (S/.)</td>
    <td class="derecha"><? echo number_format($i3['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio mayor en (S/.)</td>
    <td class="derecha"><? echo number_format($j3['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio menor en (S/.)</td>
    <td class="derecha"><? echo number_format($k3['total'],2);?></td>
  </tr>  
</table>
<br/>
<?php
}
?>
<!-- Cuarto concurso interfamiliar -->
<?php
//1.- N. participantes premiados varones
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=4 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_4_var=mysql_num_rows($result);

//2.- N. participantes premiados mujeres
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=4 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_4_muj=mysql_num_rows($result);

//3.- Monto total de premios
$sql="SELECT SUM(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=4";
$result=mysql_query($sql) or die (mysql_error());
$i4=mysql_fetch_array($result);

//4.- Obtener el premio maximo
$sql="SELECT MAX(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=4";
$result=mysql_query($sql) or die (mysql_error());
$j4=mysql_fetch_array($result);

//5.- Obtener el premio minimo
$sql="SELECT MIN(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=4";
$result=mysql_query($sql) or die (mysql_error());
$k4=mysql_fetch_array($result);

//6.- Info Concurso
$sql="SELECT cif_bd_concurso.f_concurso, 
  cif_bd_concurso.costo,
  cif_bd_concurso.monto_premio,
  cif_bd_concurso.monto_otro,    
  act_1.descripcion AS act1, 
  act_1.unidad AS unidad1, 
  act2.descripcion AS act2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS act3, 
  act3.unidad AS unidad3
FROM sys_bd_actividad_mrn act_1 LEFT JOIN cif_bd_concurso ON act_1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=4";
$result=mysql_query($sql) or die (mysql_error());
$l4=mysql_fetch_array($result);
$total_cif_4=mysql_num_rows($result);
if($total_cif_4<>0)
{
?>
<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td>Concurso</td>
    <td>N. Mujeres</td>
    <td>N. Varones</td>
    <td>Total</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">4to Concurso Interfamiliar</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">Temas en los que se trabajó</td>
  </tr>
  <tr>
    <td colspan="4">
    <ol>
      <?php if($l4['act1']<>NULL) echo "<li>".$l4['act1']."</li>";?>
      <?php if($l4['act2']<>NULL) echo "<li>".$l4['act2']."</li>";?>
      <?php if($l4['act3']<>NULL) echo "<li>".$l4['act3']."</li>";?>
    </ol>
    </td>
  </tr>  
  <tr>
    <td colspan="3">Costo del concurso (S/.)</td>
    <td class="derecha"><? echo number_format($l4['costo'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para premios (S/.)</td>
    <td class="derecha"><? echo number_format($l4['monto_premio'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para otros fines (dietas, gastos, etc) (S/.)</td>
    <td class="derecha"><? echo number_format($l4['monto_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Fecha del concurso</td>
    <td class="derecha"><? echo fecha_normal($l4['f_concurso']);?></td>
  </tr>  
  <tr>
    <td>N. de participantes</td>
    <td class="derecha"><? echo number_format($cif_4_muj);?></td>
    <td class="derecha"><? echo number_format($cif_4_var);?></td>
    <td class="derecha"><? echo number_format($cif_4_muj+$cif_4_var);?></td>
  </tr>

  <tr>
    <td>N. de participantes premiados</td>
    <td class="derecha"><? echo number_format($prem_cif_4_muj);?></td>
    <td class="derecha"><? echo number_format($prem_cif_4_var);?></td>
    <td class="derecha"><? echo number_format($prem_cif_4_muj+$prem_cif_4_var);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto total ejecutado en premios en (S/.)</td>
    <td class="derecha"><? echo number_format($i4['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio mayor en (S/.)</td>
    <td class="derecha"><? echo number_format($j4['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio menor en (S/.)</td>
    <td class="derecha"><? echo number_format($k4['total'],2);?></td>
  </tr>  
</table>
<br/>
<?php
}
?>
<!-- Quinto concurso interfamiliar -->
<?php
//1.- N. participantes premiados varones
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=5 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_5_var=mysql_num_rows($result);

//2.- N. participantes premiados mujeres
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=5 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$prem_cif_5_muj=mysql_num_rows($result);

//3.- Monto total de premios
$sql="SELECT SUM(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=5";
$result=mysql_query($sql) or die (mysql_error());
$i5=mysql_fetch_array($result);

//4.- Obtener el premio maximo
$sql="SELECT MAX(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=5";
$result=mysql_query($sql) or die (mysql_error());
$j5=mysql_fetch_array($result);

//5.- Obtener el premio minimo
$sql="SELECT MIN(cif_bd_ficha_cif.premio_pdss) AS total
FROM cif_bd_participante_cif INNER JOIN cif_bd_concurso ON cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_ficha_cif.premio_pdss<>0 AND
cif_bd_concurso.n_concurso=5";
$result=mysql_query($sql) or die (mysql_error());
$k5=mysql_fetch_array($result);

//6.- Info Concurso
$sql="SELECT cif_bd_concurso.f_concurso, 
  cif_bd_concurso.costo,
  cif_bd_concurso.monto_premio,
  cif_bd_concurso.monto_otro,    
  act_1.descripcion AS act1, 
  act_1.unidad AS unidad1, 
  act2.descripcion AS act2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS act3, 
  act3.unidad AS unidad3
FROM sys_bd_actividad_mrn act_1 LEFT JOIN cif_bd_concurso ON act_1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
WHERE cif_bd_concurso.cod_mrn='$cod' AND
cif_bd_concurso.n_concurso=5";
$result=mysql_query($sql) or die (mysql_error());
$l5=mysql_fetch_array($result);
$total_cif_5=mysql_num_rows($result);
if($total_cif_5<>0)
{
?>
<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td>Concurso</td>
    <td>N. Mujeres</td>
    <td>N. Varones</td>
    <td>Total</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">5to Concurso Interfamiliar</td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="4">Temas en los que se trabajó</td>
  </tr>
  <tr>
    <td colspan="4">
    <ol>
      <?php if($l5['act1']<>NULL) echo "<li>".$l5['act1']."</li>";?>
      <?php if($l5['act2']<>NULL) echo "<li>".$l5['act2']."</li>";?>
      <?php if($l5['act3']<>NULL) echo "<li>".$l5['act3']."</li>";?>
    </ol>
    </td>
  </tr>  
  <tr>
    <td colspan="3">Costo del concurso (S/.)</td>
    <td class="derecha"><? echo number_format($l5['costo'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para premios (S/.)</td>
    <td class="derecha"><? echo number_format($l5['monto_premio'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Monto asignado para otros fines (dietas, gastos, etc) (S/.)</td>
    <td class="derecha"><? echo number_format($l5['monto_otro'],2);?></td>
  </tr>
  <tr>
    <td colspan="3">Fecha del concurso</td>
    <td class="derecha"><? echo fecha_normal($l5['f_concurso']);?></td>
  </tr>  
  <tr>
    <td>N. de participantes</td>
    <td class="derecha"><? echo number_format($cif_5_muj);?></td>
    <td class="derecha"><? echo number_format($cif_5_var);?></td>
    <td class="derecha"><? echo number_format($cif_5_muj+$cif_5_var);?></td>
  </tr>

  <tr>
    <td>N. de participantes premiados</td>
    <td class="derecha"><? echo number_format($prem_cif_5_muj);?></td>
    <td class="derecha"><? echo number_format($prem_cif_5_var);?></td>
    <td class="derecha"><? echo number_format($prem_cif_5_muj+$prem_cif_5_var);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto total ejecutado en premios en (S/.)</td>
    <td class="derecha"><? echo number_format($i5['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio mayor en (S/.)</td>
    <td class="derecha"><? echo number_format($j5['total'],2);?></td>
  </tr>

  <tr>
    <td colspan="3">Monto del premio menor en (S/.)</td>
    <td class="derecha"><? echo number_format($k5['total'],2);?></td>
  </tr>  
</table>
<?php
}
?>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">VII.- AVANCE EN ACTIVIDADES DE CONCURSOS INTERFAMILIARES</div>
<br/>

<table width="90%" cellspacing="1" cellpadding="1" border="1" align="center" class="mini">
  <tr class="txt_titulo centrado">
    <td rowspan="2">N.</td>
    <td rowspan="2">Nombre de la actividad</td>
    <td rowspan="2">Unidad de medida</td>
    <td colspan="2">Meta antes del CIF</td>
    <td colspan="2">Meta lograda con el CIF</td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>Meta lograda</td>
    <td>Valorización (S/.)</td>
    <td>Meta lograda</td>
    <td>Valorización (S/.)</td>
  </tr>
<?php
$num=0;
  $sql="SELECT cif_bd_ficha_cif.cod_actividad, 
  sys_bd_actividad_mrn.descripcion, 
  sys_bd_actividad_mrn.unidad, 
  SUM(cif_bd_ficha_cif.meta_1) AS meta_1, 
  SUM(cif_bd_ficha_cif.valor_1) AS valor_1, 
  SUM(cif_bd_ficha_cif.meta_2) AS meta_2, 
  SUM(cif_bd_ficha_cif.valor_2) AS valor_2
FROM sys_bd_actividad_mrn INNER JOIN cif_bd_ficha_cif ON sys_bd_actividad_mrn.cod = cif_bd_ficha_cif.cod_actividad
   INNER JOIN cif_bd_concurso ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
WHERE cif_bd_concurso.cod_mrn='$cod'
GROUP BY cif_bd_ficha_cif.cod_actividad
ORDER BY cif_bd_ficha_cif.cod_actividad ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($z1=mysql_fetch_array($result))
  {
    $num++
?>
  <tr>
    <td class="centrado"><? echo $num;?></td>
    <td><? echo $z1['descripcion'];?></td>
    <td class="centrado"><? echo $z1['unidad'];?></td>
    <td class="derecha"><? echo number_format($z1['meta_1'],2);?></td>
    <td class="derecha"><? echo number_format($z1['valor_1'],2);?></td>
    <td class="derecha"><? echo number_format($z1['meta_2'],2);?></td>
    <td class="derecha"><? echo number_format($z1['valor_2'],2);?></td>
  </tr>
<?
}
?>  
</table>
<p><br/></p>
<?php
//**** Ubico los montos desembolsados
//a.- Primer desembolso
$sql="SELECT (clar_atf_mrn.desembolso_1 + 
  clar_atf_mrn.desembolso_2+ 
  clar_atf_mrn.desembolso_3 + 
  clar_atf_mrn.desembolso_4) AS total_primero, 
  clar_atf_mrn.desembolso_1 AS total_cif, 
  clar_atf_mrn.desembolso_2 AS total_at, 
  clar_atf_mrn.desembolso_3 AS total_vg, 
  clar_atf_mrn.desembolso_4 AS total_ag
FROM clar_atf_mrn
WHERE clar_atf_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//b.- Segundo desembolso
$sql="SELECT (clar_atf_mrn_sd.monto_1+ 
  clar_atf_mrn_sd.monto_2+ 
  clar_atf_mrn_sd.monto_3+ 
  clar_atf_mrn_sd.monto_4) AS total_segundo, 
  clar_atf_mrn_sd.monto_1 AS total_cif, 
  clar_atf_mrn_sd.monto_2 AS total_at, 
  clar_atf_mrn_sd.monto_3 AS total_vg, 
  clar_atf_mrn_sd.monto_4 AS total_ag
FROM clar_atf_mrn_sd
WHERE clar_atf_mrn_sd.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);


$total_cif_des=$r1['total_cif']+$r2['total_cif']+$f6['cif_pdss'];
$total_at_des=$r1['total_at']+$r2['total_at']+$f6['at_pdss'];
$total_vg_des=$r1['total_vg']+$r2['total_vg'];
$total_ag_des=$r1['total_ag']+$r2['total_ag']+$f6['ag_pdss'];



?>
<div class="capa txt_titulo">VIII.- INFORME ADMINISTRATIVO</div>
<br/>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="25%">Fecha de Segundo desembolso Sierra Sur II</td>
    <td width="25%"><? echo fecha_normal($row['f_desembolso']);?></td>
    <td width="25%">Nº de Cheque / CO</td>
    <td width="25%"><? echo $row['n_cheque'];?></td>
  </tr>
</table>
<div class="capa txt_titulo">8.1 Ejecución presupuestal</div>
<br/>
<?php


//Monto Organizaciones
$total_prog_org=$total_at_org+$row['vg_org'];
$total_dep_org=$row['monto_organizacion']+$row['monto_organizacion_2']+$f6['at_org']+$r4['aporte_org'];
$total_ejec_org=$row['ejec_at_org']+$row['ejec_vg_org']+$r4['aporte_org'];

//Monto Proyectos
$total_dep_pdss=$total_cif_pdss+$total_at_pdss+$row['vg_pdss']+$total_ag_pdss;
$total_ejec_pdss=$row['ejec_cif_pdss']+$row['ejec_at_pdss']+$row['ejec_vg_pdss']+$row['ejec_ag_pdss'];
$total_mrn_des=$r1['total_primero']+$r2['total_segundo']+$f6['total_pdss'];

//Monto otros

$total_ejec_otro=$r3['aporte_otro']+$r4['aporte_otro']+$r5['aporte_otro'];


$devolucion=$total_mrn_des-$total_ejec_pdss;
$total_ejec_contrato=$total_ejec_pdss+$total_ejec_org+$total_ejec_otro;

?>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%">Entidad</td>
    <td width="15%">Programado (S/.)</td>
    <td width="15%">Desembolsado</td>
    <td width="15%">Ejecutado (S/.)</td>
    <td width="15%">Saldo (S/.)</td>
  </tr>
  <tr>
    <td>NEC PDSS II</td>
    <td class="derecha"><? echo number_format($total_dep_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_dep_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_mrn_des-$total_ejec_pdss,2);?></td>
  </tr>
  <tr>
    <td>Organización</td>
    <td class="derecha"><? echo number_format($total_prog_org,2);?></td>
    <td class="derecha"><? echo number_format($total_dep_org,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? echo number_format($total_dep_org-$total_ejec_org,2);?></td>
  </tr>
  <tr>
    <td>Otros aportes</td>
    <td class="derecha"><? echo number_format(0,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format(0,2);?></td>
  </tr>  
  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_dep_pdss+$total_prog_org,2);?></td>
    <td class="derecha"><? echo number_format($total_dep_pdss+$total_dep_org+$total_ejec_otro,2);?></td>    
    <td class="derecha"><? echo number_format($total_ejec_pdss+$total_ejec_org+$total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format($total_mrn_des-$total_ejec_pdss,2);?></td>
  </tr>    
</table>
<br/>
<div class="capa txt_titulo">8.2 Comentarios u observaciones</div>
<div class="capa justificado"><? echo $row['observaciones'];?></div>
<?
//*******Hoja de liquidación
  if ($total_ejec_pdss<$total_dep_pdss)
  {
    $excusa=PARCIAL;
    $excusa_1="EJECUCION PARCIAL DE";
    $excusa_2="parcialmente";
  }
?>
<!-- Liquidación de iniciativas -->
 <H1 class=SaltoDePagina></H1>
  <? include("encabezado.php");?>
  <div class="capa txt_titulo" align="center">
  <u>LIQUIDACION <? echo $excusa;?> Y PERFECCIONAMIENTO</u>
  <br/>
  <u>DE CONTRATO DE EJECUCION PARCIAL DEL PLAN DE INVERSION TERRITORIAL</u>

  </div>
  <br/>
  <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="22%" class="txt_titulo">A</td>
      <td width="3%" class="txt_titulo">:</td>
      <td width="75%"><strong>ING. JOSÉ SIALER PASCO</strong></td>
    </tr>
    <tr>
      <td class="txt_titulo">&nbsp;</td>
      <td width="3%" class="txt_titulo">&nbsp;</td>
      <td width="75%" class="txt_titulo">Director Ejecutivo del Proyecto de Desarrollo Sierra Sur II</td>
    </tr>
    <tr>
      <td class="txt_titulo">Referencia</td>
      <td class="txt_titulo">:</td>
      <td>Contrato N° <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato'])." - ".$row['oficina'];?></td>
    </tr>
    <tr>
      <td class="txt_titulo">Lugar y Fecha</td>
      <td class="txt_titulo">:</td>
      <td><? echo $row['oficina'].", ".traducefecha($row['f_liquidacion']);?></td>
    </tr>
    <tr>
      <td colspan="3"><hr></td>
    </tr>
  </table>
  <br/>
  <div class="capa justificado">
    <p>En relación al documento de la referencia, informo a su Despacho, que la organización <strong><? echo $row['organizacion'];?></strong>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan: </p>
    <ul>
      <li>Informe Final de Resultados del Plan de Gestión de Recursos Naturales.</li>
      <li>..... Archivo con documentación en ........... folios.</li>
    </ul>
<?
if ($total_ejec_pdss<$total_dep_pdss)
  {
?>
    <p>
      Es pertinente precisar que la ejecución parcial del Plan de Gestión de Recursos Naturales se debió a las siguientes consideraciones:
    </p>
    <p><? echo $row['observaciones'];?></p>
<?
}
?>
    <p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por <strong>LIQUIDADO</strong>  el Contrato de la referencia  por el monto total ejecutado de  <strong>S/. <? echo number_format($total_ejec_contrato,2);?> (<? echo vuelveletra($total_ejec_contrato);?> Nuevos Soles)</strong>. El mismo que esta conformado de la siguiente manera:</p>


<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%">Entidad</td>
    <td width="20%">Desembolsado (S/.)</td>
    <td width="20%">Ejecutado (S/.)</td>
    <td width="20%">Saldo (S/.)</td>
  </tr>
  <tr>
    <td>NEC PDSS II</td>
    <td class="derecha"><? echo number_format($total_mrn_des,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_mrn_des-$total_ejec_pdss,2);?></td>
  </tr>
  <tr>
    <td>Organización</td>
    <td class="derecha"><? echo number_format($total_dep_org,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? echo number_format($total_dep_org-$total_ejec_org,2);?></td>
  </tr>
    <tr>
    <td>Otros aportes</td>
    <td class="derecha"><? echo number_format($total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format(0,2);?></td>
  </tr> 
  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_mrn_des+$total_dep_org+$total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss+$total_ejec_org+$total_ejec_otro,2);?></td>
    <td class="derecha"><? echo number_format($total_mrn_des-$total_ejec_pdss,2);?></td>
  </tr>    
</table>

    <p>Por lo expuesto,
<?php
if($devolucion>0.9)
{
 echo "y luego de verificar la <strong>DEVOLUCION</strong> del monto de <strong>S/. ".number_format($devolucion,2)." (".vuelveletra($devolucion).")</strong>, ";
}
?>
     esta jefatura procede al <strong>PERFECCIONAMIENTO</strong> de la Donación Sujeto a Cargo por el monto de <strong>S/. <? echo number_format($total_ejec_pdss,2);?> (<? echo vuelveletra($total_ejec_pdss);?> Nuevos Soles)</strong> correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II.</p>
    <p>Por lo indicado, mucho estimare disponer la baja contable del contrato en referencia.</p>
  </div>

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
    <td align="center"><? echo $row['nombres']." ".$row['apellidos'];?><br/>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>


<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE DEL PLAN DE GESTION DE RECURSOS NATURALES Y DE LA DONACION SUJETO A CARGO</div>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato'])." - ".$row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['organizacion'];?></td>
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
    <td colspan="2" align="center"><strong><u>
<?
if ($total_ejec_pdss<$total_dep_pdss)
  {
  ?>
    LIQUIDACION  DEL CONTRATO Y PERFECCIONAMIENTO - PGRN  PARCIAL DE LA DONACION SUJETO A CARGO
 <?
  }
  else
  {
 ?>
    LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACION SUJETO A CARGO
 <?   
  }
 ?>
 </u></strong></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACIÓN CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DE COMPONENTES Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION  LA BAJA CONTABLE DE LA INICIATIVA DE LA REFERENCIA.</div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>






<br/>
<div class="capa">
<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
<a href="../pit/pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>	
</div>


</body>
</html>
