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
  pit_bd_ficha_pdn.at_org, 
  pit_bd_ficha_pdn.vg_org, 
  pit_bd_ficha_pdn.fer_org, 
  pit_bd_ficha_pdn.f_termino, 
  pit_bd_ficha_pdn.f_contrato, 
  pit_bd_ficha_pdn.n_contrato, 
  pit_bd_pdn_liquida.f_desembolso, 
  pit_bd_pdn_liquida.n_cheque, 
  pit_bd_pdn_liquida.ejec_at_pdss, 
  pit_bd_pdn_liquida.ejec_at_org, 
  pit_bd_pdn_liquida.ejec_pf_pdss, 
  pit_bd_pdn_liquida.ejec_pf_org, 
  pit_bd_pdn_liquida.ejec_vg_pdss, 
  pit_bd_pdn_liquida.ejec_vg_org, 
  pit_bd_pdn_liquida.ejec_ag_pdss, 
  pit_bd_pdn_liquida.hc_soc, 
  pit_bd_pdn_liquida.just_soc, 
  pit_bd_pdn_liquida.hc_dir, 
  pit_bd_pdn_liquida.just_dir, 
  pit_bd_pdn_liquida.cod_calificacion, 
  pit_bd_pdn_liquida.f_liquidacion, 
  pit_bd_pdn_liquida.comentario, 
  sys_bd_califica.descripcion AS calificacion, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  pit_bd_ficha_pdn.total_apoyo AS ag_pdss, 
  pit_bd_ficha_pdn.at_pdss, 
  pit_bd_ficha_pdn.vg_pdss, 
  pit_bd_ficha_pdn.fer_pdss, 
  sys_bd_dependencia.nombre AS oficina, 
  pit_bd_ficha_pdn.cod_pit, 
  pit_bd_ficha_pit.n_contrato AS n_contrato_pit, 
  pit_bd_ficha_pit.f_contrato AS f_contrato_pit, 
  pit_bd_ficha_pdn.tipo, 
  pit_bd_ficha_pdn.monto_organizacion, 
  pit_bd_ficha_pdn.monto_organizacion_2
FROM sys_bd_linea_pdn INNER JOIN pit_bd_ficha_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
   LEFT JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
   LEFT JOIN pit_bd_pdn_liquida ON pit_bd_pdn_liquida.cod_pdn = pit_bd_ficha_pdn.cod_pdn
   LEFT JOIN sys_bd_califica ON sys_bd_califica.cod = pit_bd_pdn_liquida.cod_calificacion
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   LEFT JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   LEFT JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Obtengo el tipo de PDN (Tipo 1 y 2 para PDN sueltos/ Tipo 0 para PIT)
$tipo_pdn=$row['tipo'];

//2.- Busco el pit si lo hubiera
$sql="SELECT pit_bd_ficha_pit.f_contrato, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.cod_pit
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row2=mysql_fetch_array($result);

//3.- Busco el pit de la ampliacion
$sql="SELECT clar_atf_pdn.cod_pdn, 
  clar_ampliacion_pit.cod_ampliacion, 
  clar_ampliacion_pit.f_ampliacion, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.f_contrato, 
  pit_bd_ficha_pit.cod_pit
FROM clar_ampliacion_pit INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
   INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row3=mysql_fetch_array($result);
$total_ampliacion=mysql_num_rows($result);



//4.- Busco si este Plan de negocio tiene addenda

//******* SI EL PDN ES SUELTO
if ($tipo_pdn<>0)
{
  $sql="SELECT pit_bd_ficha_adenda_pdn.n_adenda, 
  pit_bd_ficha_adenda_pdn.f_adenda, 
  pit_bd_ficha_adenda_pdn.f_inicio, 
  pit_bd_ficha_adenda_pdn.meses, 
  pit_bd_ficha_adenda_pdn.f_termino
  FROM pit_bd_ficha_adenda_pdn
  WHERE pit_bd_ficha_adenda_pdn.cod_pdn='$cod'";  
}
//******* SI ES UN PDN QUE PERTENECE A UN PIT
elseif($tipo_pdn==0 and $total_ampliacion==0)
{
  $sql="SELECT pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.f_inicio, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_pit='".$row2['cod_pit']."'";
}
//******* SI ES UNA AMPLIACION
else
{
  $sql="SELECT pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.f_inicio, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_pit='".$row3['cod_pit']."'";  
}

$result=mysql_query($sql) or die (mysql_error());
$total_adenda=mysql_num_rows($result);
$r1=mysql_fetch_array($result);

$n_adenda=$r1['n_adenda'];
$f_adenda=$r1['f_adenda'];
$f_termino_adenda=$r1['f_termino'];




//5.- Ubico las fechas de termino
if($tipo_pdn<>0)
{
  $f_inicio=$row['f_contrato'];
  $n_contrato=$row['n_contrato'];
}
elseif($tipo_pdn==0 and $row2['f_contrato']=='0000-00-00')
{
  $f_inicio=$row3['f_contrato'];
  $n_contrato=$row3['n_contrato'];
}
else
{
  $f_inicio=$row2['f_contrato'];
  $n_contrato=$row2['n_contrato'];
}

//5.- Calculo la fecha de termino
if($total_ampliacion<>0)
{
  $fecha=$row3['f_ampliacion'];
}
else
{
 $fecha=$f_inicio; 
}  

$mes=$row['mes'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);  



$salto="<div class='twelve columns'><br/></div>	";

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-25);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_25 = "'".$fecha."'";



//Desde aca calculo los montos
//**** Obtengo el monto depositado tanto en primer como en segundo desembolso
$sql="SELECT SUM(clar_atf_pdn.monto_1) AS total_at, 
  SUM(clar_atf_pdn.monto_2) AS total_vg, 
  SUM(clar_atf_pdn.monto_3) AS total_fer, 
  SUM(clar_atf_pdn.monto_4) AS total_ag, 
  SUM(clar_atf_pdn.monto_1+ 
  clar_atf_pdn.monto_2+ 
  clar_atf_pdn.monto_3+ 
  clar_atf_pdn.monto_4) AS total_pdn
FROM clar_atf_pdn
WHERE clar_atf_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//Proyecto
$total_prog_pdss=$row['ag_pdss']+$row['at_pdss']+$row['vg_pdss']+$row['fer_pdss'];
$total_des_pdss=$r3['total_pdn'];
$total_ejec_pdss=number_format($row['ejec_at_pdss']+$row['ejec_vg_pdss']+$row['ejec_pf_pdss']+$row['ejec_ag_pdss'],2,'.','');

//Organizacion
$total_prog_org=$row['at_org']+$row['vg_org']+$row['fer_org'];
$total_des_org=$row['monto_organizacion']+$row['monto_organizacion_2'];
$total_ejec_org=$row['ejec_at_org']+$row['ejec_vg_org']+$row['ejec_pf_org'];


$total_programado=$total_prog_pdss+$total_prog_org;
$total_desembolsado=$total_des_pdss + $total_des_org;
$total_ejecutado=number_format($total_ejec_pdss+$total_ejec_org,2,'.','');
$devolucion=number_format($total_des_pdss-$total_ejec_pdss,2,'.','');


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
<div class="capa gran_titulo centrado">INFORME  FINAL DE RESULTADOS Y LIQUIDACION DEL PLAN DE NEGOCIOS</div>
<p><br/></p>
<div class="capa gran_titulo centrado">NOMBRE DE LA ORGANIZACIÓN TERRITORIAL</div>
<div class="capa centrado"><? echo $row['territorio'];?></div>
<p><br/></p>
<div class="capa gran_titulo centrado">NOMBRE DE LA ORGANIZACION</div>
<div class="capa centrado"><? echo $row['organizacion'];?></div>
<p><br/></p>
<div class="capa gran_titulo centrado">NOMBRE DEL PLAN DE NEGOCIO</div>
<div class="capa centrado"><? echo $row['denominacion'];?></div>
<p><br/></p>
<p><br/></p>
<table width="80%" cellpadding="1" cellspacing="1" align="center">
	
	<tr>
		<td width="50%">CONTRATO Nº</td>
		<td width="50%"><? echo numeracion($n_contrato)."-".periodo($f_inicio);?></td>
	</tr>
	<tr>
		<td>FECHA DE APROBACION DEL INFORME</td>
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
    <td>Fecha de Inicio</td>
    <td width="29%">
    <?
    if($total_ampliacion<>0)
    {
     echo traducefecha($row3['f_ampliacion']);
    }
    else
    {
    echo traducefecha($f_inicio);
    }
    ?>
    </td>
    <td width="23%">Fecha de termino</td>
    <td width="27%"><? echo traducefecha($f_termino);?></td>
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
    <td><? echo traducefecha($r1['f_adenda']);?></td>
  </tr>
  <tr>
    <td>Duración</td>
    <td><? echo $r1['meses'];?> meses</td>
    <td>Fecha de término</td>
    <td><? echo traducefecha($r1['f_termino']);?></td>
  </tr>
  <tr>
    <td colspan="4"><hr/></td>
  </tr>
  <?
  }
  ?>
  <tr>
    <td>Fecha de informe</td>
    <td width="29%"><? echo traducefecha($row['f_liquidacion']);?></td>
    <td width="23%">Calificacion de la Iniciativa</td>
    <td width="27%"><? echo $row['calificacion'];?></td>
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
    <td width="25%" colspan="3">Nº de participantes al inicio del Plan de Negocio</td>
    <td width="25%" colspan="3">Nº de participantes al Finalizar el Plan de Negocio</td>
  </tr>
  <tr class="txt_titulo">
    <td>Menores de 25 años</td>
    <td>Mayores de 25 años</td>
    <td>Total participantes</td>
    <td>Menores de 25 años</td>
    <td>Mayores de 25 años</td>
    <td>Total participantes</td>
  </tr>
<?
//Participantes

//a.- varones mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t13=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t14=mysql_num_rows($result);

//a.- varones mayores de 25 años vigencia 2
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t15=mysql_num_rows($result);

//b.- varones menores de 25 años vigencia 2
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t16=mysql_num_rows($result);



//a.- mujeres mayores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t17=mysql_num_rows($result);

//b.- mujeres menores de 25 años vigencia 1
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t18=mysql_num_rows($result);

//a.- mujeres mayores de 25 años vigencia 2
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t19=mysql_num_rows($result);

//b.- mujeres menores de 25 años vigencia 2
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$t20=mysql_num_rows($result);
?>
 
  <tr>
    <td width="50%">Varones</td>
	<td class="derecha"><? echo $t14;?></td>
	<td class="derecha"><? echo $t13;?></td>
  <td class="derecha"><? echo $t14+$t13;?></td>
	<td class="derecha"><? echo $t16;?></td>
	<td class="derecha"><? echo $t15;?></td>
  <td class="derecha"><? echo $t16+$t15;?></td>
  </tr>
  <tr>
    <td width="50%">Mujeres</td>
	<td class="derecha"><? echo $t18;?></td>
	<td class="derecha"><? echo $t17;?></td>
  <td class="derecha"><? echo $t17+$t18;?></td>
	<td class="derecha"><? echo $t20;?></td>
	<td class="derecha"><? echo $t19;?></td>
  <td class="derecha"><? echo $t19+$t20;?></td>
  </tr>
  <tr class="txt_titulo">
    <td width="50%">Total</td>
	<td class="derecha"><? echo $t14+$t18;?></td>
	<td class="derecha"><? echo $t13+$t17;?></td>
  <td class="derecha"><? echo $t13+$t14+$t17+$t18;?></td>
	<td class="derecha"><? echo $t16+$t20;?></td>
	<td class="derecha"><? echo $t15+$t19;?></td>
  <td class="derecha"><? echo$t15+$t16+$t19+$t20;?></td>
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
    <td><? echo number_format($f1['aporte_pdss']+$f1['aporte_org'],2);?></td>
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
    <td class="centrado"><? echo number_format(dias_transcurridos($f2['f_inicio'],$f2['f_termino'])/30);?></td>
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
	 LEFT JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 LEFT JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
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
	 LEFT JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 LEFT JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
WHERE pit_bd_ficha_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="4" class="txt_titulo">4.1 Apoyo a la gestión del Plan de Negocio</td>
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
<div class="capa txt_titulo">5.1.- Patrimonio del PDN (S/.)</div>


<br/>

<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo">
    <td>Nº</td>
    <td>Tipo de patrimonio del Negocio</td>
    <td>Descripcion del patrimonio</td>
    <td>Valor estimado de patrimonio el año anterior al PDN</td>
    <td>Valor de patrimonio logrado con el PDN</td>
    <td>Inversión propia en patrimonio</td>
    <td>Aporte de otros en patrimonio</td>
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
	$val_c=$f15['valor_a'];
  $val_d=$f15['valor_b'];
  $val_e=$f15['inversion_propia'];
  $val_f=$f15['aporte_otro'];

  $total_val_c=$total_val_c+$val_c;
  $total_val_d=$total_val_d+$val_d;
  $total_val_e=$total_val_e+$val_e;
  $total_val_f=$total_val_f+$val_f;

  $na++
?>  
  <tr>
    <td class="centrado"><? echo $na;?></td>
    <td><? echo $f15['tipo_activo'];?></td>
    <td><? echo $f15['descripcion'];?></td>
    <td class="derecha"><? echo number_format($f15['valor_a'],2);?></td>
    <td class="derecha"><? echo number_format($f15['valor_b'],2);?></td>
    <td class="derecha"><? echo number_format($f15['inversion_propia'],2);?></td>
    <td class="derecha"><? echo number_format($f15['aporte_otro'],2);?></td>
  </tr>
<?
}
?>  
  <tr class="txt_titulo">
    <td colspan="3">TOTALES</td>
    <td class="derecha"><? echo number_format($total_val_c,2);?></td>
    <td class="derecha"><? echo number_format($total_val_d,2);?></td>
    <td class="derecha"><? echo number_format($total_val_e,2);?></td>
    <td class="derecha"><? echo number_format($total_val_f,2);?></td>
  </tr>
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
	$cant_a=$f6['cantidad_a'];
  $cant_b=$f6['cantidad_b'];
  $val_a=$f6['valor_a'];
  $val_b=$f6['valor_b'];

  $tot_cant_a=$tot_cant_a+$cant_a;
  $tot_cant_b=$tot_cant_b+$cant_b;

  $tot_val_a=$tot_val_a+$val_a;
  $tot_val_b=$tot_val_b+$val_b;

  $nb++
?>  
  <tr>
    <td class="centrado"><? echo $nb;?></td>
    <td><? echo $f6['producto'];?></td>
    <td><? echo $f6['unidad'];?></td>
    <td class="derecha"><? echo number_format($f6['cantidad_a'],2);?></td>
    <td class="derecha"><? echo number_format($f6['valor_a'],2);?></td>
    <td class="derecha"><? echo number_format($f6['cantidad_b'],2);?></td>
    <td class="derecha"><? echo number_format($f6['valor_b'],2);?></td>
  </tr>
 <?
 }
 ?> 
  <tr class="txt_titulo">
    <td colspan="3">TOTALES</td>
    <td class="derecha"><? echo number_format($tot_cant_a,2);?></td>
    <td class="derecha"><? echo number_format($tot_val_a,2);?></td>
    <td class="derecha"><? echo number_format($tot_cant_b,2);?></td>
    <td class="derecha"><? echo number_format($tot_val_b,2);?></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">5.3.- Manejo de costos de producción (S/.)</div>
<br/>
<?
$num=0;
$sql="SELECT ficha_costo_produccion_pdn.producto, 
  ficha_costo_produccion_pdn.unidad, 
  ficha_costo_produccion_pdn.costo_a, 
  ficha_costo_produccion_pdn.costo_b, 
  ficha_costo_produccion_pdn.costo_c, 
  ficha_costo_produccion_pdn.costo_d, 
  ficha_costo_produccion_pdn.costo_e, 
  ficha_costo_produccion_pdn.costo_f, 
  ficha_costo_produccion_pdn.costo_g, 
  ficha_costo_produccion_pdn.costo_h, 
  ficha_costo_produccion_pdn.costo_i, 
  ficha_costo_produccion_pdn.costo_j
FROM ficha_costo_produccion_pdn
WHERE ficha_costo_produccion_pdn.cod_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f9=mysql_fetch_array($result))
{
  $num++
?>
<table width="90%" border="1" cellspacing="1" cellpadding="1" align="center" class="mini">
  <tr class="txt_titulo">
    <td colspan="4">PRODUCTO FINAL <? echo numeracion($num);?></td>
  </tr>
  <tr>
    <td width="25%">Producto</td>
    <td width="25%"><? echo $f9['producto'];?></td>
    <td width="25%">Unidad</td>
    <td width="25%"><? echo $f9['unidad'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2">Rubros</td>
    <td>Costo de producción antes del PDN</td>
    <td>Costo de producción con el PDN</td>
  </tr>

  <tr>
    <td colspan="4">I.- Mano de Obra</td>
  </tr>

  <tr>
    <td colspan="2"> - Mano de obra </td>
    <td class="derecha"><? echo number_format($f9['costo_a'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_b'],2);?></td>
  </tr>
  <tr>
    <td colspan="2"> - Mano de obra no calificada</td>
    <td class="derecha"><? echo number_format($f9['costo_c'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_d'],2);?></td>
  </tr>
  <tr>
    <td colspan="2">II.- Insumos/Materiales</td>
    <td class="derecha"><? echo number_format($f9['costo_e'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_f'],2);?></td>
  </tr>
  <tr>
    <td colspan="2">III.- Servicios</td>
    <td class="derecha"><? echo number_format($f9['costo_g'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_h'],2);?></td>
  </tr>
  <tr>
    <td colspan="2">IV.- Otros</td>
    <td class="derecha"><? echo number_format($f9['costo_i'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_j'],2);?></td>
  </tr> 

  <tr class="txt_titulo">
    <td colspan="2">TOTALES</td>
    <td class="derecha"><? echo number_format($f9['costo_a']+$f9['costo_c']+$f9['costo_e']+$f9['costo_g']+$f9['costo_i'],2);?></td>
    <td class="derecha"><? echo number_format($f9['costo_b']+$f9['costo_d']+$f9['costo_f']+$f9['costo_h']+$f9['costo_j'],2);?></td>
  </tr>

</table>
<p><br/></p>
<?  
}
?>
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
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result9=mysql_query($sql) or die (mysql_error());
$t9=mysql_num_rows($result9);

//4.- Mujeres mayores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento < $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result10=mysql_query($sql) or die (mysql_error());
$t10=mysql_num_rows($result10);

//5.- Hombres menores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=1 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result11=mysql_query($sql) or die (mysql_error());
$t11=mysql_num_rows($result11);

//6.- Mujeres menores de 25
$sql="SELECT DISTINCT ficha_participante_pf.n_documento
FROM org_ficha_usuario INNER JOIN ficha_participante_pf ON org_ficha_usuario.cod_tipo_doc = ficha_participante_pf.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_pf.n_documento
WHERE org_ficha_usuario.sexo=0 AND
org_ficha_usuario.f_nacimiento > $fecha_25 AND
ficha_participante_pf.cod_feria='".$f7['cod_feria']."'";
$result12=mysql_query($sql) or die (mysql_error());
$t12=mysql_num_rows($result12);

?>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
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
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
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
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
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
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
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
    <td width="25%">Fecha de Segundo Desembolso Sierra Sur II</td>
    <td width="25%"><? echo fecha_normal($row['f_desembolso']);?></td>
    <td width="25%">Nº de Cheque / CO</td>
    <td width="25%"><? echo $row['n_cheque'];?></td>
  </tr>
</table>
<div class="capa txt_titulo">8.1 Presupuesto Ejecutado</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%">Entidad</td>
    <td width="20%">Desembolsado (S/.)</td>
    <td width="20%">Ejecutado (S/.)</td>
    <td width="20%">Saldo (S/.)</td>
  </tr>
  <tr>
    <td>NEC PDSS II</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$total_ejec_pdss,2);?></td>
  </tr>
  <tr>
    <td>Organización</td>
    <td class="derecha"><? echo number_format($total_des_org,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? echo number_format($total_des_org-$total_ejec_org,2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_desembolsado,2);?></td>
    <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
    <td class="derecha"><? echo number_format($total_desembolsado-$total_ejecutado,2);?></td>
  </tr>    
</table>
<br/>

<p>
  <div class="capa txt_titulo">Comentarios u Observaciones</div>
  <div class="capa"><p><? echo $row['comentario'];?></p></div>
</p>

<?


  if ($total_ejecutado<$total_programado)
  {
    $excusa=PARCIAL;
    $excusa_1="EJECUCION PARCIAL DE";
    $excusa_2="parcialmente";
  }
/*aca colocamos la ficha de liquidacion
Buscamos el tipo de plan de negocio*/
if ($tipo_pdn<>0)
{
?>
  <H1 class=SaltoDePagina></H1>
  <? include("encabezado.php");?>
  <div class="capa txt_titulo" align="center">
  <u>LIQUIDACION <? echo $excusa;?> Y PERFECCIONAMIENTO</u>
  <br/>
  <u>CONTRATO DE EJECUCION DE SERVICIOS PARA LA ASISTENCIA TECNICA</u>

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
      <td>Contrato N° <? echo numeracion($n_contrato)."-".periodo($f_inicio)." - ".$row['oficina'];?></td>
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
    <p>En relación al documento de la referencia, informo a su Despacho, que la organización <strong><? echo $row['organizacion'];?></strong>, con su PDN denominado <strong><? echo $row['denominacion'];?></strong>, motivo del contrato de la referencia, la misma  que ha cumplido con sus obligaciones establecidas en el indicado Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan: </p>
    <ul>
      <li>Informe Final de Resultados del Plan de Negocio.</li>
      <li>..... Archivo con documentación en ........... folios.</li>
    </ul>
<?
if ($total_ejecutado<$total_programado)
  {
?>
    <p>
      Es pertinente precisar que la ejecución parcial del Plan de Negocio se debió a las siguientes consideraciones:
    </p>
    <p><? echo $row['comentario'];?></p>
<?
}
?>
    <p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por <strong>LIQUIDADO</strong>  el Contrato de la referencia  por el monto total ejecutado de  <strong>S/. <? echo number_format($total_ejecutado,2);?> (<? echo vuelveletra($total_ejecutado);?> Nuevos Soles)</strong>. El mismo que esta conformado de la siguiente manera:</p>

<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%">Entidad</td>
    <td width="20%">Desembolsado (S/.)</td>
    <td width="20%">Ejecutado (S/.)</td>
    <td width="20%">Saldo (S/.)</td>
  </tr>
  <tr>
    <td>NEC PDSS II</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$total_ejec_pdss,2);?></td>
  </tr>
  <tr>
    <td>Organización</td>
    <td class="derecha"><? echo number_format($total_des_org,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? echo number_format($total_des_org-$total_ejec_org,2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_desembolsado,2);?></td>
    <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
    <td class="derecha"><? echo number_format($total_desembolsado-$total_ejecutado,2);?></td>
  </tr>    
</table>


    <p>Por lo expuesto,
      <?php
      if($devolucion<>0 and $devolucion>0)
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
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE DE  SERVICIOS DE ASISTENCIA TECNICA Y DE LA DONACION SUJETO A CARGO</div>


<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($n_contrato)."-".periodo($f_inicio)." - ".$row['oficina'];?></td>
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
    <td colspan="2" align="center"><strong><u>LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACION SUJETO A CARGO</u></strong></td>
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


<?
}
else
{
?>
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
      <td>Contrato N° <? echo numeracion($n_contrato)."-".periodo($f_inicio)." - ".$row['oficina'];?></td>
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
    <p>En relación al documento de la referencia, informo a su Despacho, que la organización <strong><? echo $row['organizacion'];?></strong>, con su PDN denominado <strong><? echo $row['denominacion'];?></strong>, es conformante del PIT motivo del contrato de la referencia, la misma  que ha cumplido con sus obligaciones establecidas en el indicado Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan: </p>
    <ul>
      <li>Informe Final de Resultados del Plan de Negocio.</li>
      <li>..... Archivo con documentación en ........... folios.</li>
    </ul>
<?
if ($total_ejecutado<$total_programado)
  {
?>
    <p>
      Es pertinente precisar que la ejecución parcial del Plan de Negocio se debió a las siguientes consideraciones:
    </p>
    <p><? echo $row['comentario'];?></p>
<?
}
?>
    <p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por <strong>LIQUIDADO</strong>  como una acción parcial el PDN del Contrato de la referencia  por el monto total ejecutado de  <strong>S/. <? echo number_format($total_ejecutado,2);?> (<? echo vuelveletra($total_ejecutado);?> Nuevos Soles)</strong>. El mismo que esta conformado de la siguiente manera:</p>

<table width="90%" border="1" align="center" cellpadding="2" cellspacing="2" class="mini">
  <tr class="txt_titulo centrado">
    <td width="40%">Entidad</td>
    <td width="20%">Desembolsado (S/.)</td>
    <td width="20%">Ejecutado (S/.)</td>
    <td width="20%">Saldo (S/.)</td>
  </tr>
  <tr>
    <td>NEC PDSS II</td>
    <td class="derecha"><? echo number_format($total_des_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_pdss,2);?></td>
    <td class="derecha"><? echo number_format($total_des_pdss-$total_ejec_pdss,2);?></td>
  </tr>
  <tr>
    <td>Organización</td>
    <td class="derecha"><? echo number_format($total_des_org,2);?></td>
    <td class="derecha"><? echo number_format($total_ejec_org,2);?></td>
    <td class="derecha"><? echo number_format($total_des_org-$total_ejec_org,2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td>TOTALES</td>
    <td class="derecha"><? echo number_format($total_desembolsado,2);?></td>
    <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
    <td class="derecha"><? echo number_format($total_desembolsado-$total_ejecutado,2);?></td>
  </tr>    
</table>


    <p>Por lo expuesto,
      <?php
      if($devolucion<>0 and $devolucion>0)
      {
        echo "y luego de verificar la <strong>DEVOLUCION</strong> del monto de <strong>S/. ".number_format($devolucion,2)." (".vuelveletra($devolucion).")</strong>, ";
      }
      ?>
     esta jefatura procede al <strong>PERFECCIONAMIENTO</strong> de la Donación Sujeto a Cargo como una acción parcial el PDN por el monto de <strong>S/. <? echo number_format($total_ejec_pdss,2);?> (<? echo vuelveletra($total_ejec_pdss);?> Nuevos Soles)</strong> correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II.</p>
    <p>Por lo indicado, mucho estimaré disponer la baja contable  por el monto del PDN indicado en el contrato en referencia.  </p>
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
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE  DE EJECUCION PARCIAL DEL PLAN DE INVERSION TERRITORIAL- PLAN DE NEGOCIO Y DE LA DONACION SUJETO A CARGO</div>


<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($n_contrato)."-".periodo($f_inicio)." - ".$row['oficina'];?></td>
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
    <td colspan="2" align="center"><strong><u>LIQUIDACION  DEL CONTRATO Y PERFECCIONAMIENTO- PDN  PARCIAL DE LA DONACION SUJETO A CARGO</u></strong></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO  EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACION CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DEL COMPONENTE Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION LA BAJA CONTABLE  POR EL MONTO PERFECCIONADO DEL PLAN DE NEGOCIO DEL CONTRATO DE LA  REFERENCIA.</div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>
<?
}
?>

<p>&nbsp;</p>
<div class="capa">
<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>

<a href="../pit/pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

</div>
</body>
</html>
