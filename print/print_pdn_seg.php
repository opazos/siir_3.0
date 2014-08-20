<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	sys_bd_linea_pdn.descripcion AS linea, 
	pit_bd_ficha_pdn.f_presentacion_2, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_taz.nombre AS territorio, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.ejec_at_pdss, 
	pit_bd_pdn_sd.ejec_pf_pdss, 
	pit_bd_pdn_sd.ejec_vg_pdss, 
	pit_bd_pdn_sd.ejec_ag_pdss, 
	pit_bd_pdn_sd.hc_soc, 
	pit_bd_pdn_sd.just_soc, 
	pit_bd_pdn_sd.hc_dir, 
	pit_bd_pdn_sd.just_dir, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.monto_4, 
	pit_bd_pdn_sd.ejec_at_org, 
	pit_bd_pdn_sd.ejec_pf_org, 
	pit_bd_pdn_sd.ejec_vg_org, 
	pit_bd_pdn_sd.meses, 
	(pit_bd_ficha_pdn.at_org*0.50) AS at_org, 
	(pit_bd_ficha_pdn.vg_org*0.50) AS vg_org, 
	(pit_bd_ficha_pdn.fer_org*0.50) AS fer_org, 
	pit_bd_ficha_pdn.f_termino
FROM sys_bd_linea_pdn INNER JOIN pit_bd_ficha_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_pdn_sd ON pit_bd_pdn_sd.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
WHERE pit_bd_pdn_sd.cod_tipo=1 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$salto="<div class='twelve columns'><br/></div>	";

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-25);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_25 = "'".$fecha."'";
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
<div class="capa gran_titulo centrado">PLAN DE NEGOCIOS<br/>INFORME DE AVANCE - SEGUNDO DESEMBOLSO</div>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr class="txt_titulo">
    <td colspan="4">I.- DATOS GENERALES</td>
  </tr>
  <tr>
    <td colspan="4">1.1.- Organizacion Responsable del PIT</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['territorio'];?></td>
  </tr>
  <tr>
    <td colspan="4">1.2.- Organizacion Responsable del Plan de Negocio</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="4">1.3.- Denominación del Plan de Negocio</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td width="21%">Linea de Negocio</td>
    <td width="29%"><? echo $row['linea'];?></td>
    <td width="23%">Duración</td>
    <td width="27%"><? echo $row['mes'];?> meses</td>
  </tr>
  <tr>
    <td>Fecha de Inicio según contrato</td>
    <td width="29%"><? echo fecha_normal($row['f_inicio']);?></td>
    <td width="23%">Fecha de termino</td>
    <td width="27%"><? echo fecha_normal($row['f_termino']);?></td>
  </tr>
  <tr>
    <td>Fecha de informe</td>
    <td width="29%"><? echo fecha_normal($row['f_presentacion_2']);?></td>
    <td width="23%">Nº de meses ejecutados</td>
    <td width="27%"><? echo $row['meses'];?> meses</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="4">II.- SITUACION ORGANIZACIONAL</td>
  </tr>
  <tr>
    <td colspan="4">2.1.- Junta Directiva</td>
  </tr>
  <tr>
    <td colspan="2">¿Hubieron cambios?</td>
    <td>Si <input type="radio" name="rd1" <? if ($row['hc_dir']==1) echo "checked";?>></td>
    <td>No <input type="radio" name="rd1" <? if ($row['hc_dir']==0) echo "checked";?>></td>
  </tr>
  <tr>
    <td colspan="4">¿Por qué?</td>
  </tr>
  <tr>
    <td colspan="4"><? echo $row['just_dir'];?></td>
  </tr>
  <tr>
    <td colspan="4">Junta directiva Vigente</td>
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
$ne=0;
$sql="SELECT org_ficha_directivo.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_cargo_directivo.descripcion AS cargo, 
	org_ficha_usuario.sexo, 
	org_ficha_usuario.f_nacimiento, 
	org_ficha_directivo.f_termino
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_directivo.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = org_ficha_directivo.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE org_ficha_directivo.vigente=1 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
	$ne++
?>  
    <tr>
      <td><? echo $ne;?></td>
      <td><? echo $f8['n_documento'];?></td>
      <td><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?></td>
      <td><? echo $f8['cargo'];?></td>
      <td><? if ($f8['sexo']==1) echo "M"; else echo "F";?></td>
      <td class="centrado"><? echo fecha_normal($f8['f_nacimiento']);?></td>
      <td class="centrado"><? echo fecha_normal($f8['f_termino']);?></td>
    </tr>
<?
}
?>
  </tbody>
</table>
<br/>
<div class="capa">2.2.- Participantes ejecutando el Plan de Negocio</div>

<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td width="50%" rowspan="2">Sexo</td>
    <td width="25%" colspan="2">Nº de participantes al inicio de la Asistencia Tecnica</td>
    <td width="25%" colspan="2">Nº de participantes al momento del presente informe</td>
  </tr>
  <tr class="txt_titulo">
    <td>Menores de 25 años</td>
    <td>Mayores de 25 años</td>
    <td>Menores de 25 años</td>
    <td>Mayores de 25 años</td>
  </tr>
<?
//Participantes

//a.- varones mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t13=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t14=mysql_num_rows($result);

//a.- varones mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t15=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t16=mysql_num_rows($result);



//a.- varones mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t17=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t18=mysql_num_rows($result);

//a.- varones mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t19=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t20=mysql_num_rows($result);
?>
 
  <tr>
    <td width="50%">Varones</td>
	<td class="derecha"><? echo $t14;?></td>
	<td class="derecha"><? echo $t13;?></td>
	<td class="derecha"><? echo $t16;?></td>
	<td class="derecha"><? echo $t15;?></td>
  </tr>
  <tr>
    <td width="50%">Mujeres</td>
	<td class="derecha"><? echo $t18;?></td>
	<td class="derecha"><? echo $t17;?></td>
	<td class="derecha"><? echo $t20;?></td>
	<td class="derecha"><? echo $t19;?></td>
  </tr>
  <tr class="txt_titulo">
    <td width="50%">Total</td>
	<td class="derecha"><? echo $t14+$t18;?></td>
	<td class="derecha"><? echo $t13+$t17;?></td>
	<td class="derecha"><? echo $t16+$t20;?></td>
	<td class="derecha"><? echo $t15+$t19;?></td>
  </tr>
</table>
<br/>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="50%">¿Hubieron cambios?</td>
    <td width="21%">Si <input type="radio" name="rd2" <? if ($row['hc_soc']==1) echo "checked";?>></td>
    <td width="29%">No <input type="radio" name="rd2" <? if ($row['hc_soc']==0) echo "checked";?>></td>
  </tr>
  <tr>
    <td colspan="3">¿Por qué?</td>
  </tr>
  <tr>
    <td colspan="3"><? echo $row['just_soc'];?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">III.- AVANCE DE LA ASISTENCIA TECNICA</div>
<br/>
<div class="capa">3.1.- Asistencia Tecnica Solicitada</div>



<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td rowspan="2">TEMA DE ASISTENCIA TECNICA</td>
    <td rowspan="2">RESULTADO ESPERADO</td>
    <td rowspan="2">ESPECIALISTA SOLICITADO</td>
    <td colspan="4" class="centrado">TIEMPO Y COSTO</td>
    <td colspan="3">COFINANCIAMIENTO</td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>Nº dias a la semana</td>
    <td>Costo por dia</td>
    <td>Nº de semanas al mes</td>
    <td>Nº de meses</td>
    <td>SS II</td>
    <td>Socios</td>
    <td>Total</td>
  </tr>
<?
$sql="SELECT pit_bd_at_pdn.rubro, 
	pit_bd_at_pdn.resultado, 
	pit_bd_at_pdn.rubro_especialista, 
	pit_bd_at_pdn.n_dia, 
	pit_bd_at_pdn.costo_dia, 
	pit_bd_at_pdn.n_semana, 
	pit_bd_at_pdn.n_mes, 
	pit_bd_at_pdn.aporte_total, 
	pit_bd_at_pdn.aporte_pdss, 
	pit_bd_at_pdn.aporte_org
FROM pit_bd_at_pdn
WHERE pit_bd_at_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$pdss1=$f1['aporte_pdss'];
	$total_pdss1=$total_pdss1+$pdss1;

	$org1=$f1['aporte_org'];
	$total_org1=$total_org1+$org1;	
	
?>  
  <tr>
    <td><? echo $f1['rubro'];?></td>
    <td><? echo $f1['resultado'];?></td>
    <td><? echo $f1['rubro_especialista'];?></td>
    <td><? echo number_format($f1['n_dia']);?></td>
    <td><? echo number_format($f1['costo_dia'],2);?></td>
    <td><? echo number_format($f1['n_semana']);?></td>
    <td><? echo number_format($f1['n_mes']);?></td>
    <td><? echo number_format($f1['aporte_pdss'],2);?></td>
    <td><? echo number_format($f1['aporte_org'],2);?></td>
    <td><? echo number_format($f1['aporte_total'],2);?></td>
  </tr>
 <?
}
?>   
  <tr class="txt_titulo">
    <td colspan="7">TOTAL</td>
    <td><? echo number_format($total_pdss1,2);?></td>
    <td><? echo number_format($total_org1,2);?></td>
    <td><? echo number_format($total_pdss1+$total_org1,2);?></td>
  </tr>

</table>
<br/>
<div class="capa">3.2.- Asistencia Tecnica Ejecutada</div>
<br/>

<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td rowspan="2">TEMA DE ASISTENCIA TECNICA</td>
    <td rowspan="2">RESULTADO LOGRADO</td>
    <td rowspan="2">ESPECIALISTA SOLICITADO</td>
    <td class="centrado">TIEMPO Y COSTO</td>
    <td colspan="3">COFINANCIAMIENTO</td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>Nº de meses</td>
    <td>SS II</td>
    <td>Socios</td>
    <td>Total</td>
  </tr>
<?
$sql="SELECT ficha_sat.tema, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	ficha_sat.aporte_pdss, 
	ficha_sat.aporte_org, 
	ficha_sat.resultado, 
	ficha_ag_oferente.especialidad
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error);
while($f2=mysql_fetch_array($result))
{
	$pdss2=$f2['aporte_pdss'];
	$total_pdss2=$total_pdss2+$pdss2;
	
	$org2=$f2['aporte_org'];
	$total_org2=$total_org2+$org2;
?>
  <tr>
    <td><? echo $f2['tema'];?></td>
    <td><? echo str_replace("\n","<br/>",$f2['resultado']);?></td>
    <td><? echo $f2['especialidad'];?></td>
    <td class="centrado"><? echo meses($f2['f_inicio'], $f2['f_termino'])+1;?></td>
    <td><? echo number_format($f2['aporte_pdss'],2);?></td>
    <td><? echo number_format($f2['aporte_org'],2);?></td>
    <td><? echo number_format($f2['aporte_pdss']+$f2['aporte_org'],2);?></td>
  </tr>
<?
}
?> 
  <tr class="txt_titulo">
    <td colspan="4">TOTAL</td>
    <td><? echo number_format($total_pdss2,2);?></td>
    <td><? echo number_format($total_org2,2);?></td>
    <td><? echo number_format($total_pdss2+$total_org2,2);?></td>
  </tr>

</table>
<H1 class=SaltoDePagina></H1>
<div class="capa">3.3.- Detalle de la Asistencia Tecnica Ejecutada</div>
<?
$sql="SELECT ficha_sat.tema, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	ficha_sat.aporte_pdss, 
	ficha_sat.aporte_org, 
	ficha_sat.resultado, 
	ficha_ag_oferente.especialidad, 
	ficha_sat.n_mujeres, 
	ficha_sat.n_varones, 
	ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.direccion, 
	sys_bd_tipo_designacion.descripcion AS tipo_designacion, 
	sys_bd_califica.descripcion AS calificacion, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_designacion ON sys_bd_tipo_designacion.cod = ficha_sat.cod_tipo_designacion
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ficha_sat.cod_estado_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 LEFT JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
	 INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'
ORDER BY ficha_sat.f_inicio ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr class="txt_titulo">
    <td colspan="4">3.3.1 Tema de la Asistencia Tecnica</td>
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
    <td colspan="4" class="txt_titulo">3.3.2 Vigencia del contrato de Asistencia Tecnica</td>
  </tr>
  <tr>
    <td>Desde</td>
    <td><? echo fecha_normal($f3['f_inicio']);?></td>
    <td>Hasta</td>
    <td><? echo fecha_normal($f3['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4" class="txt_titulo">3.3.3 Resultados o cambios logrados con esta Asistencia Tecnica</td>
  </tr>
  <tr>
    <td colspan="4"><? echo str_replace("\n","<br/><br/>",$f3['resultado']);?></td>
  </tr>
  <tr>
    <td colspan="4">- Nº de Varones a los que se brindo Asistencia tecnica:  <? echo $f3['n_varones'];?> personas</td>
  </tr>
  <tr>
    <td colspan="4">- Nº de mujeres a las que se brindo Asistencia tecnica :  <? echo $f3['n_mujeres'];?> personas</td>
  </tr>
  <tr>
    <td>Como se designo el asistente tecnico</td>
    <td><? echo $f3['tipo_designacion'];?></td>
    <td>Calificación de desempeño del Asistente Tecnico</td>
    <td><? echo $f3['calificacion'];?></td>
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
<div class="txt_titulo capa">IV.- RESULTADOS DEL APOYO A LA GESTIÓN</div>
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
	ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.direccion, 
	ficha_ag_oferente.especialidad, 
	sys_bd_tipo_oferente.descripcion AS tipo_oferente, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento
FROM pit_bd_ficha_pdn INNER JOIN ficha_aag ON pit_bd_ficha_pdn.cod_pdn = ficha_aag.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
	 INNER JOIN sys_bd_tipo_designacion ON sys_bd_tipo_designacion.cod = ficha_aag.cod_tipo_designacion
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_aag.cod_calificacion
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ficha_aag.cod_estado_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_aag.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_aag.n_documento
	 INNER JOIN sys_bd_tipo_oferente ON sys_bd_tipo_oferente.cod = ficha_ag_oferente.cod_tipo_oferente
	 LEFT JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
	 INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
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
    <td colspan="4"><? echo str_replace("\n","<br/><br/>",$f4['resultado']);?></td>
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
<div class="txt_titulo capa">V.- AVANCES EN EL PLAN DE NEGOCIO</div>
<br/>
<div class="capa txt_titulo">5.1.- Activos Fisicos (S/.)</div>


<br/>

<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td>Nº</td>
    <td>Tipo de Activos del Negocio</td>
    <td>Descripcion del Activo</td>
    <td>Valor estimado de activos el año anterior al PDN</td>
    <td>Valor de activos logrados con el PDN</td>
    <td>Inversión propia en activos logrados</td>
    <td>Aporte de otros en activos logrados</td>
  </tr>
<?
$na=0;
$sql="SELECT sys_bd_tipo_activo.descripcion AS tipo_activo, 
	ficha_activo_pdn.descripcion, 
	ficha_activo_pdn.valor_a, 
	ficha_activo_pdn.valor_b, 
	ficha_activo_pdn.inversion_propia, 
	ficha_activo_pdn.aporte_otro
FROM sys_bd_tipo_activo INNER JOIN ficha_activo_pdn ON sys_bd_tipo_activo.cod = ficha_activo_pdn.cod_tipo_activo
WHERE ficha_activo_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f15=mysql_fetch_array($result))
{
	$na++
?>  
  <tr>
    <td><? echo $na;?></td>
    <td><? echo $f15['tipo_activo'];?></td>
    <td><? echo $f15['descripcion'];?></td>
    <td><? echo number_format($f15['valor_a'],2);?></td>
    <td><? echo number_format($f15['valor_b'],2);?></td>
    <td><? echo number_format($f15['inversion_propia'],2);?></td>
    <td><? echo number_format($f15['aporte_otro'],2);?></td>
  </tr>
<?
}
?>  
</table>
<br/>
<div class="capa txt_titulo">5.2.- Ventas(S/.)</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td>Nº</td>
    <td>Especificación del producto Vendido</td>
    <td>Unidad</td>
    <td>Cantidad de producto vendido al año anterior al PDN</td>
    <td>Valor estimado de ventas al año anterior al PDN</td>
    <td>Cantidad de producto vendido con el PDN</td>
    <td>valor estimado de ventas con el PDN</td>
  </tr>
<?
$nb=0;
$sql="SELECT ficha_ventas_pdn.producto, 
	ficha_ventas_pdn.unidad, 
	ficha_ventas_pdn.cantidad_a, 
	ficha_ventas_pdn.cantidad_b, 
	ficha_ventas_pdn.valor_a, 
	ficha_ventas_pdn.valor_b
FROM ficha_ventas_pdn
WHERE ficha_ventas_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
	$nb++
?>  
  <tr>
    <td><? echo $nb;?></td>
    <td><? echo $f6['producto'];?></td>
    <td><? echo $f6['unidad'];?></td>
    <td><? echo number_format($f6['cantidad_a'],2);?></td>
    <td><? echo number_format($f6['valor_a'],2);?>/td>
    <td><? echo number_format($f6['cantidad_b'],2);?></td>
    <td><? echo number_format($f6['valor_b'],2);?></td>
  </tr>
 <?
 }
 ?> 
</table>
<br/>
<div class="capa txt_titulo">5.3.- Costos de inversion (S/.)</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td width="61%">Tipo de costo o gasto</td>
    <td width="20%">Valor de compras o gastos estimados el año anterior al PDN</td>
    <td width="19%">Valor de compras o gastos realizados con el PDN</td>
  </tr>
 <?
 $sql="SELECT ficha_costo_produccion_pdn.costo_a, 
	ficha_costo_produccion_pdn.costo_b, 
	ficha_costo_produccion_pdn.costo_c, 
	ficha_costo_produccion_pdn.costo_d, 
	ficha_costo_produccion_pdn.costo_f, 
	ficha_costo_produccion_pdn.costo_g, 
	ficha_costo_produccion_pdn.costo_h, 
	ficha_costo_produccion_pdn.costo_i, 
	ficha_costo_produccion_pdn.costo_j, 
	ficha_costo_produccion_pdn.costo_k, 
	ficha_costo_produccion_pdn.costo_l, 
	ficha_costo_produccion_pdn.costo_m
FROM ficha_costo_produccion_pdn
WHERE ficha_costo_produccion_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
 ?> 
  <tr>
    <td>Compras de materias primas o insumos</td>
    <td class="derecha"><? echo number_format($r1['costo_a'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_b'],2);?></td>
  </tr>
  <tr>
    <td>Pago de personal - Jornales pagados</td>
    <td class="derecha"><? echo number_format($r1['costo_c'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_d'],2);?></td>
  </tr>
  <tr>
    <td>Pago de personal - Asistencia tecnica</td>
    <td class="derecha"><? echo number_format($r1['costo_f'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_g'],2);?></td>
  </tr>
  <tr>
    <td>Pago de servicios (Telefono, pasajes, luz, agua, etc)</td>
    <td class="derecha"><? echo number_format($r1['costo_h'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_i'],2);?></td>
  </tr>
  <tr>
    <td>Materiales de oficina y otros</td>
    <td class="derecha"><? echo number_format($r1['costo_j'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_k'],2);?></td>
  </tr>
  <tr>
    <td>Gastos diversos</td>
    <td class="derecha"><? echo number_format($r1['costo_l'],2);?></td>
    <td class="derecha"><? echo number_format($r1['costo_m'],2);?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">VI.- VISITA GUIADA</div>
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
FROM pit_bd_ficha_pdn INNER JOIN ficha_vg ON pit_bd_ficha_pdn.cod_pdn = ficha_vg.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
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
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result3=mysql_query($sql) or die (mysql_error());
$t3=mysql_num_rows($result3);

//4.- Hombres mayores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=1 AND 
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result4=mysql_query($sql) or die (mysql_error());
$t4=mysql_num_rows($result4);

//5.- Mujeres menores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=0 AND 
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_vg.cod_visita='".$f5['cod_visita']."'";
$result5=mysql_query($sql) or die (mysql_error());
$t5=mysql_num_rows($result5);

//6.- Hombres menores de 25
$sql="SELECT DISTINCT ficha_participante_vg.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_vg ON org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
WHERE org_ficha_usuario.sexo=1 AND 
org_ficha_usuario.f_nacimiento < $fecha_25 AND
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
<p>
  <?
}
?>
<H1 class=SaltoDePagina></H1>
<div class="capa txt_titulo">VII.- PARTICIPACION EN FERIAS</div>
<br/>
  <?
$sql="SELECT ficha_pf.cod_feria, 
	ficha_pf.denominacion, 
	ficha_pf.objetivo, 
	ficha_pf.f_inicio, 
	ficha_pf.f_termino, 
	ficha_pf.departamento, 
	ficha_pf.provincia, 
	ficha_pf.distrito, 
	ficha_pf.aporte_pdss, 
	ficha_pf.aporte_org, 
	ficha_pf.aporte_otro, 
	sys_bd_califica.descripcion AS calificacion
FROM sys_bd_califica INNER JOIN ficha_pf ON sys_bd_califica.cod = ficha_pf.cod_calificacion
WHERE ficha_pf.cod_tipo_iniciativa=4 AND
ficha_pf.cod_iniciativa='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>

<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2">7.1.- Denominación de la Feria</td>
  </tr>
  <tr>
    <td colspan="2"><span class="twelve columns"><? echo $f7['denominacion'];?></span></td>
  </tr>
  <tr>
    <td width="24%">Departamento</td>
    <td width="76%"><span class="four columns"><? echo $f7['departamento'];?></span></td>
  </tr>
  <tr>
    <td>Provincia</td>
    <td><span class="four columns"><? echo $f7['provincia'];?></span></td>
  </tr>
  <tr>
    <td>Distrito</td>
    <td><span class="ten columns"><? echo $f7['distrito'];?></span></td>
  </tr>
  <tr>
    <td colspan="2">7.2.- Objetivo del evento</td>
  </tr>
  <tr>
    <td colspan="2"><span class="twelve columns"><? echo $f7['objetivo'];?></span></td>
  </tr>
  <tr>
    <td>Inicio</td>
    <td><span class="four columns"><? echo fecha_normal($f7['f_inicio']);?></span></td>
  </tr>
  <tr>
    <td>Termino</td>
    <td><span class="four columns"><? echo fecha_normal($f7['f_termino']);?></span></td>
  </tr>
  <tr>
    <td colspan="2">7.3.- Numero de participantes</td>
  </tr>
</table>
<br/>
<?
//1.- Varones
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=1 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result7=mysql_query($sql) or die (mysql_error());
$t7=mysql_num_rows($result7);

//2.- Mujeres
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=0 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result8=mysql_query($sql) or die (mysql_error());
$t8=mysql_num_rows($result8);

//3.- Hombres mayores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result9=mysql_query($sql) or die (mysql_error());
$t9=mysql_num_rows($result9);

//4.- Mujeres mayores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result10=mysql_query($sql) or die (mysql_error());
$t10=mysql_num_rows($result10);

//5.- Hombres menores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result11=mysql_query($sql) or die (mysql_error());
$t11=mysql_num_rows($result11);

//6.- Mujeres menores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result12=mysql_query($sql) or die (mysql_error());
$t12=mysql_num_rows($result12);

?>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<th class="nine">Sexo</th>
		<th>Menores de 25 años</th>
		<th>Mayores de 25 años</th>
		<th>Total</th>
	</tr>
	<tr>
		<td>Mujeres</td>
		<td><? echo $t12;?></td>
		<td><? echo $t10;?></td>
		<td><? echo $t8;?></td>
	</tr>
	<tr>
		<td>Varones</td>
		<td><? echo $t11;?></td>
		<td><? echo $t9;?></td>
		<td><? echo $t7;?></td>
	</tr>
	<tr>
		<td>Total</td>
		<td><? echo $t11+$t12;?></td>
		<td><? echo $t9+$t10;?></td>
		<td><? echo $t7+$t8;?></td>
	</tr>
</table>
<br/>
<div class="capa txt_titulo">7.4 Gastos efectuados de cofinanciamiento de la feria</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<td class="nine">Financiamiento</td>
		<td>Monto Ejecutado(S/.)</td>
		<td>Asignacion por participante</td>
	</tr>
	<tr>
		<td>Sierra Sur II</td>
		<td><? echo number_format($f7['aporte_pdss'],2);?></td>
		<td><? echo number_format($f7['aporte_pdss']/($t7+$t8),2);?></td>
	</tr>
	<tr>
		<td>Organizacion</td>
		<td><? echo number_format($f7['aporte_org'],2);?></td>
		<td><? echo number_format($f7['aporte_org']/($t7+$t8),2);?></td>
	</tr>	
	<tr>
		<td>Otros</td>
		<td><? echo number_format($f7['aporte_otro'],2);?></td>
		<td><? echo number_format($f7['aporte_otro']/($t7+$t8),2);?></td>
	</tr>		
</table>
<br/>
<div class="txt_titulo capa">7.5 Logros alcanzados</div>
<br/>
<div class="capa">7.5.1 Ventas</div>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<th>Nº</th>
		<th class="seven">Productos o Servicios</th>
		<th>Unidad</th>
		<th>Cantidad</th>
		<th>Precio unitario (S/.)</th>
		<th>Precio total (S/.)</th>
	</tr>
<?
$nc=0;
$sql="SELECT ficha_ventas_pf.producto, 
	ficha_ventas_pf.unidad, 
	ficha_ventas_pf.cantidad, 
	ficha_ventas_pf.precio, 
	ficha_ventas_pf.total
FROM ficha_ventas_pf
WHERE ficha_ventas_pf.cod_feria='".$f7['cod_feria']."'";
$result11=mysql_query($sql) or die (mysql_error());
while($r11=mysql_fetch_array($result11))
{
	$nc++
?>	
	<tr>
		<td><? echo $nc;?></td>
		<td><? echo $r11['producto'];?></td>
		<td><? echo $r11['unidad'];?></td>
		<td><? echo $r11['cantidad'];?></td>
		<td><? echo number_format($r11['precio'],2);?></td>
		<td><? echo number_format($r11['total'],2);?></td>
	</tr>
<?
}
?>	
</table>
<br/>
<div class="capa">7.5.2 Contactos Comerciales</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2">
	<tr>
		<th>Nº</th>
		<th class="four">Nombre del contacto o empresa</th>
		<th>Mercado</th>
		<th class="four">Acuerdos</th>
		<th class="four">Productos</th>
	</tr>
<?
$nd=0;
$sql="SELECT ficha_contacto_pf.nombre, 
	ficha_contacto_pf.acuerdos, 
	ficha_contacto_pf.producto, 
	sys_bd_tipo_mercado.descripcion AS mercado
FROM sys_bd_tipo_mercado INNER JOIN ficha_contacto_pf ON sys_bd_tipo_mercado.cod = ficha_contacto_pf.cod_mercado
WHERE ficha_contacto_pf.cod_feria='".$f7['cod_feria']."'";
$result13=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result13))
{
	$nd++
?>	
	<tr>
		<td><? echo $nd;?></td>
		<td><? echo $r2['nombre'];?></td>
		<td><? echo $r2['mercado'];?></td>
		<td><? echo $r2['acuerdos'];?></td>
		<td><? echo $r2['producto'];?></td>
	</tr>
<?
}
?>	
</table>

<?
}
?>
<H1 class=SaltoDePagina></H1>
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
  <thead>
    <tr class="txt_titulo">
      <th width="44%">Concepto</th>
      <th width="9%">Monto Depositado Sierra Sur(S/.)</th>
      <th width="10%">Monto Ejecutado Sierra Sur(S/.)</th>
      <th width="10%">% de ejecucion Sierra Sur</th>
      <th width="9%">Monto Depositado Organizacion(S/.)</th>
      <th width="9%">Monto Ejecutado Organizacion (S/.)</th>
      <th width="9%">% de ejecucion Organizacion</th>
    </tr>
  </thead>
  <tr>
    <td>ASISTENCIA TECNICA</td>
    <td class="derecha"><? echo number_format($row['monto_1'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_at_pdss'],2);?></td>
    <td class="derecha"><?
		@$pp1=($row['ejec_at_pdss']/$row['monto_1'])*100;
		echo number_format(@$pp1,2);		
		?></td>
    <td class="derecha"><? echo number_format($row['at_org'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_at_org'],2);?></td>
    <td class="derecha"><?
			@$pp4=($row['ejec_at_org']/$row['at_org'])*100;
			echo number_format(@$pp4,2);
			?></td>
  </tr>
  <tr>
    <td>VISITA GUIADA</td>
    <td class="derecha"><? echo number_format($row['monto_2'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_vg_pdss'],2);?></td>
    <td class="derecha"><?
		@$pp2=($row['ejec_vg_pdss']/$row['monto_2'])*100;
		echo number_format(@$pp2,2);		
		?></td>
    <td class="derecha"><? echo number_format($row['vg_org'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_vg_org'],2);?></td>
    <td class="derecha"><?
			@$pp5=($row['ejec_vg_org']/$row['vg_org'])*100;
			echo number_format(@$pp5,2);
			?></td>
  </tr>
  <tr>
    <td>PARTICIPACION EN FERIAS</td>
    <td class="derecha"><? echo number_format($row['monto_3'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_pf_pdss'],2);?></td>
    <td class="derecha"><?
		@$pp3=($row['ejec_pf_pdss']/$row['monto_3'])*100;
		echo number_format(@$pp3,2);		
		?></td>
    <td class="derecha"><? echo number_format($row['fer_org'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_pf_org'],2);?></td>
    <td class="derecha"><?
			@$pp6=($row['ejec_pf_org']/$row['fer_org'])*100;
			echo number_format(@$pp6,2);
			?></td>
  </tr>
  <tr>
    <td>APOYO A LA GESTION</td>
    <td class="derecha"><? echo number_format($row['monto_4'],2);?></td>
    <td class="derecha"><? echo number_format($row['ejec_ag_pdss'],2);?></td>
    <td class="derecha"><?
		@$pp4=($row['ejec_ag_pdss']/$row['monto_4'])*100;
		echo number_format(@$pp4,2);		
		?></td>
    <td class="centrado">-</td>
    <td class="centrado">-</td>
    <td class="centrado">-</td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="derecha"><? $total_prog_pdss=$row['monto_1']+$row['monto_2']+$row['monto_3']+$row['monto_4']; echo number_format($total_prog_pdss,2);?></td>
    <td class="derecha"><? $total_ejec_pdss=$row['ejec_at_pdss']+$row['ejec_vg_pdss']+$row['ejec_pf_pdss']+$row['ejec_ag_pdss']; echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? @$pp7=($total_ejec_pdss/$total_prog_pdss)*100; echo number_format(@$pp7,2);?></td>
    <td class="derecha"><? $total_prog_org=$row['at_org']+$row['vg_org']+$row['fer_org']; echo number_format($total_prog_org,2);?></td>
    <td class="derecha"><? $total_ejec_org=$row['ejec_at_org']+$row['ejec_vg_org']+$row['ejec_pf_org']; echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? @$pp8=($total_ejec_org/$total_prog_org)*100; echo number_format(@$pp8,2);?></td>
  </tr>
</table>
<p>&nbsp;</p>
<div class="capa">
<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
<?
if ($tipo==1)
{
?>
<a href="../seguimiento/pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_segundo" class="secondary button oculto">Finalizar</a>
<?
}
else
{
?>
<a href="../pit/pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
<?
}
?>
</div>
</body>
</html>
