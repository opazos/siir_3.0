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
  pit_bd_mrn_sd.f_desembolso, 
  pit_bd_mrn_sd.n_cheque, 
  pit_bd_mrn_sd.ejec_cif_pdss, 
  pit_bd_mrn_sd.ejec_at_pdss, 
  pit_bd_mrn_sd.ejec_at_org, 
  pit_bd_mrn_sd.ejec_vg_pdss, 
  pit_bd_mrn_sd.ejec_vg_org, 
  pit_bd_mrn_sd.ejec_ag_pdss, 
  pit_bd_mrn_sd.hc_soc, 
  pit_bd_mrn_sd.just_soc, 
  pit_bd_mrn_sd.hc_dir, 
  pit_bd_mrn_sd.just_dir, 
  pit_bd_mrn_sd.mes, 
  (pit_bd_ficha_mrn.cif_pdss*0.70) AS cif_pdss, 
  (pit_bd_ficha_mrn.at_pdss*0.70) AS at_pdss, 
  (pit_bd_ficha_mrn.vg_pdss*0.70) AS vg_pdss, 
  (pit_bd_ficha_mrn.ag_pdss*0.70) AS ag_pdss, 
  (pit_bd_ficha_mrn.at_org*0.50) AS at_org, 
  (pit_bd_ficha_mrn.vg_org*0.50) AS vg_org, 
  pit_bd_ficha_pit.mes AS mess
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
   LEFT OUTER JOIN pit_bd_mrn_sd ON pit_bd_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
   INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
WHERE pit_bd_ficha_mrn.cod_mrn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-25);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_25 = "'".$fecha."'";


$f_inicial=$row['f_contrato'];
$mes_duracion=$row['mess'];
$f_termino=dateadd1($f_inicial,0,$mes_duracion,0,0,0,0);


if($f_termino>'2014-09-15')
{
  $f_termino='2014-09-15';
}
else
{
  $f_termino=$f_termino;
}

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
<div class="capa centrado gran_titulo">PLAN DE GESTION DE RECURSOS NATURALES<br/>
INFORME DE AVANCE - SEGUNDO DESEMBOLSO</div>
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
    <td width="25%"><? echo fecha_normal($row['f_contrato']);?></td>
    <td width="25%">Fecha de termino</td>
    <td width="25%"><? echo fecha_normal($f_termino);?></td>
  </tr>
  <tr>
    <td>Fecha de informe</td>
    <td><? echo fecha_normal($row['f_presentacion_2']);?></td>
    <td>Nº de meses ejecutados a la presentacion del informe</td>
    <td><? echo $row['mes'];?> meses</td>
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
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$na++
?> 
  <tr>
    <td><? echo $na;?></td>
    <td><? echo $f1['n_documento'];?></td>
    <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
    <td><? echo $f1['cargo'];?></td>
    <td><? if ($f1['sexo']==1) echo "M"; else echo "F";?></td>
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
	 LEFT JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 LEFT JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
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
<div class="capa txt_titulo">VIII.- INFORME ADMINISTRATIVO</div>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="25%">Fecha de Primer desembolso Sierra Sur II</td>
    <td width="25%"><? echo fecha_normal($row['f_desembolso']);?></td>
    <td width="25%">Nº de Cheque / CO</td>
    <td width="25%"><? echo $row['n_cheque'];?></td>
  </tr>
</table>
<div class="capa txt_titulo">8.1 Avance en la ejecución de los fondos del Primer Desembolso</div>


<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="centrado txt_titulo">
    <td width="29%" rowspan="2">Concepto</td>
    <td colspan="3">SIERRA SUR II</td>
    <td colspan="3">ORGANIZACION</td>
  </tr>
  <tr class="centrado txt_titulo">
    <td width="12%">Monto Depositado (S/.)</td>
    <td width="12%">Monto Ejecutado (S/.)</td>
    <td width="11%">% de Ejecucion</td>
    <td width="12%">Monto Depositado (S/.)</td>
    <td width="12%">Monto Ejecutado (S/.)</td>
    <td width="12%">% de Ejecución</td>
  </tr>
  <tr>
    <td>CONCURSOS INTERFAMILIARES</td>
    <td class="derecha"><? echo number_format($row['cif_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_cif_pdss'],2);?></td>
    <td class="derecha"><? $p1=(($row['ejec_cif_pdss']/$row['cif_pdss'])*100); echo number_format($p1,2);?></td>
    <td class="derecha">-</td>
    <td class="derecha">-</td>
    <td class="derecha">-</td>
  </tr>
  <tr>
    <td>ASISTENCIA TECNICA</td>
    <td class="derecha"><? echo number_format($row['at_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_at_pdss'],2);?></td>
    <td class="derecha"><? $p2=(($row['ejec_at_pdss']/$row['at_pdss'])*100); echo number_format($p2,2);?></td>
    <td class="derecha"><? echo number_format($row['at_org'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_at_org'],2);?></td>
    <td class="derecha"><? $p3=(($row['ejec_at_org']/$row['at_org'])*100); echo number_format($p3,2);?></td>
  </tr>
  <tr>
    <td>VISITA GUIADA</td>
    <td class="derecha"><? echo number_format($row['vg_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_vg_pdss'],2);?></td>
    <td class="derecha"><? $p4=(($row['ejec_vg_pdss']/$row['vg_pdss'])*100); echo number_format($p4,2);?></td>
    <td class="derecha"><? echo number_format($row['vg_org'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_vg_org'],2);?></td>
    <td class="derecha"><? $p5=(($row['ejec_vg_org']/$row['vg_org'])*100); echo number_format($p5,2);?></td>
  </tr>
  <tr>
    <td>APOYO A LA GESTION</td>
    <td class="derecha"><? echo number_format($row['ag_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_ag_pdss'],2);?></td>
    <td class="derecha"><? $p6=(($row['ejec_ag_pdss']/$row['ag_pdss'])*100); echo number_format($p6,2);?></td>
    <td class="derecha">-</td>
    <td class="derecha">-</td>
    <td class="derecha">-</td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="derecha"><? $total_dep_pdss=$row['cif_pdss']+$row['at_pdss']+$row['vg_pdss']+$row['ag_pdss']; echo number_format($total_dep_pdss,2);?></td>
    <td class="derecha"><? $total_ejec_pdss=$row['ejec_cif_pdss']+$row['ejec_at_pdss']+$row['ejec_vg_pdss']+$row['ejec_ag_pdss']; echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? $p7=($total_ejec_pdss/$total_dep_pdss)*100; echo number_format($p7,2);?></td>
    <td class="derecha"><? $total_dep_org=$row['at_org']+$row['vg_org']; echo number_format($total_dep_org,2);?></td>
    <td class="derecha"><? $total_ejec_org=$row['ejec_at_org']+$row['ejec_vg_org']; echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? $p8=($total_ejec_org/$total_dep_org)*100; echo number_format($p8,2);?></td>
  </tr>
</table>

<br/>
<div class="capa">
	
<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
<?
if ($tipo==1)
{
?>
<a href="../seguimiento/mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_segundo" class="secondary button oculto">Finalizar</a>
<?
}
else
{
?>
<a href="../pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
<?
}
?>
	
</div>


</body>
</html>
