<?
/*header('Content-type: application/vnd.ms-word');
header("Content-Disposition: attachment; filename=preview_vist.doc");
header("Pragma: no-cache");
header("Expires: 0");*/

require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$cod2=$cod;


$org="<strong>LA ORGANIZACIÓN</strong>";
$orga="<strong>LAS ORGANIZACIONES</strong>";
$proyecto ="<strong>SIERRA SUR II</strong>";
$pit="<strong>EL PIT</strong>";

//1.- busco los datos del PIT
$sql="SELECT
pit_bd_ficha_pit.n_contrato,
pit_bd_ficha_pit.f_contrato,
pit_bd_ficha_pit.mes,
pit_bd_ficha_pit.f_termino,
pit_bd_ficha_pit.n_solicitud,
org_ficha_taz.n_documento AS ficha_pit,
org_ficha_taz.nombre AS nombre_pit,
org_ficha_taz.sector,
sys_bd_dependencia.nombre AS oficina,
sys_bd_dependencia.departamento,
sys_bd_dependencia.provincia,
sys_bd_dependencia.ubicacion,
sys_bd_dependencia.direccion,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion,
pit_bd_ficha_pit.n_animador,
pit_bd_ficha_pit.incentivo_animador,
pit_bd_ficha_pit.n_mes,
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org,
pit_bd_ficha_pit.fuente_fida,
pit_bd_ficha_pit.fuente_ro,
pit_bd_ficha_pit.n_cuenta,
sys_bd_ifi.descripcion AS banco,
sys_bd_tipo_doc.descripcion AS tipo_doc,
sys_bd_departamento.nombre AS dep,
sys_bd_provincia.nombre AS prov,
sys_bd_distrito.nombre AS dist,
sys_bd_tipo_org.descripcion AS tipo_org,
clar_bd_ficha_pit.cod_clar,
pit_bd_ficha_pit.cod_estado_iniciativa
FROM pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
LEFT JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
LEFT JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
LEFT JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
LEFT JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
INNER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE
pit_bd_ficha_pit.cod_pit ='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//Funcion para sumar 15 meses a la fecha
$fecha_db = $row['f_contrato'];
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1]+3,$fecha_db[2]-1,$fecha_db[0]+1);
$fecha = date("Y-m-d", $fecha_cambiada);
if ($fecha>"2013-08-31")
$fecha_termino="2013-08-31";
else
$fecha_termino = $fecha;


$total_at=$row['aporte_pdss']+$row['aporte_org'];

//Funcion sumo 13 meses a la fecha para animador territorial
$fecha_cambiada1= mktime(0,0,0,$fecha_db[1]+1,$fecha_db[2]-1,$fecha_db[0]+1);
$fecha1=date("Y-m-d", $fecha_cambiada1);
if ($fecha1>"2013-08-31")
	$fecha_termino1="2013-08-31";
else
$fecha_termino1 = $fecha1;





//datos del presidente del pit
$sql="SELECT
org_ficha_directiva_taz.nombre,
org_ficha_directiva_taz.paterno,
org_ficha_directiva_taz.materno,
org_ficha_directiva_taz.n_documento
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE
pit_bd_ficha_pit.cod_pit='$cod2' AND
org_ficha_directiva_taz.cod_cargo_directivo = 1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//datos del presidente del pit
$sql="SELECT
org_ficha_directiva_taz.nombre,
org_ficha_directiva_taz.paterno,
org_ficha_directiva_taz.materno,
org_ficha_directiva_taz.n_documento
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE
pit_bd_ficha_pit.cod_pit='$cod2' AND
org_ficha_directiva_taz.cod_cargo_directivo = 3";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//obtenemos los datos del evento CLAR
$sql="SELECT
clar_bd_evento_clar.cod_clar,
clar_bd_evento_clar.f_evento,
sys_bd_dependencia.nombre,
clar_bd_acta.n_acta,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar,
clar_bd_evento_clar.nombre AS clar
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
LEFT JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
WHERE
clar_bd_evento_clar.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//obtengo el numero de MRN
$sql="SELECT Count(pit_bd_ficha_mrn.cod_mrn) AS mrn
FROM clar_atf_mrn INNER JOIN pit_bd_ficha_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit ='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//obtengo el numero de PDN
$sql="SELECT Count(pit_bd_ficha_pdn.cod_pdn) AS pdn
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit ='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);


//obtengo los montos a cofinanciar
//1.- montos PIT
$sql="SELECT
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org
FROM
pit_bd_ficha_pit
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

//2.- montos pgrn
$sql="SELECT Sum(pit_bd_ficha_mrn.cif_pdss+pit_bd_ficha_mrn.at_pdss+pit_bd_ficha_mrn.vg_pdss+pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	   Sum(pit_bd_ficha_mrn.at_org+pit_bd_ficha_mrn.vg_org) AS aporte_org
FROM clar_atf_mrn INNER JOIN pit_bd_ficha_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit = '$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

//3.- montos pdn
$sql="SELECT SUM(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	SUM(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit = '$cod2' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$r8=mysql_fetch_array($result);

//ahora aobtengo los montos

$aporte_pdss=$r6['aporte_pdss']+$r7['aporte_pdss']+$r8['aporte_pdss'];

$aporte_org=$r7['aporte_org']+$r8['aporte_org']+$r6['aporte_org'];

$aporte_total=$aporte_pdss+$aporte_org;

$pp_pdss=($aporte_pdss/$aporte_total)*100;
$pp_org=($aporte_org/$aporte_total)*100;


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
<?php 
if ($r3['n_acta']==0)
{
	echo "<div class='gran_titulo capa centrado'>ERROR: EL EVENTO CLAR DONDE PARTICIPO ESTA INICIATIVA NO CUENTA CON ACTA</div>";
}
else
{
?>

<div class="capa txt_titulo" align="center">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?><br>
DE DONACIÓN SUJETO A CARGO PARA LA EJECUCIÓN DEL PLAN DE INVERSIÓN TERRITORIAL DE LA ORGANIZACIÓN : "<? echo $row['nombre_pit'];?>"</div>
<br>

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
<br>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="justify">Conste por el presente documento el Contrato de Donación sujeto a Cargo para la Ejecución del Plan de Inversión
Territorial que celebran, de una parte <strong>EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO
SIERRA SUR II</strong>, con RUC No 20456188118,en adelante denominado "<? echo $proyecto;?>"
representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombre']." ".$row['apellido'];?> con DNI N° <? echo $row['n_documento'];?>, con domicilio legal en <? echo $row['direccion'];?>, del Distrito de <? echo $row['ubicacion'];?>, Provincia de <? echo $row['provincia'];?> y Departamento de <? echo $row['departamento'];?> ;
y de otra parte la Organización: <? echo $row['nombre_pit'];?> con <? echo $row['tipo_doc'];?> N° <? echo $row['ficha_pit'];?> con domicilio legal en <? echo $row['sector'];?>, ubicada
en el Distrito de <? echo $row['dist'];?>, Provincia de <? echo $row['prov'];?>, Departamento de <? echo $row['dep'];?>, en adelante denominada "<? echo $org;?>", representada por su Presidente(a), <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?> con DNI N°
<? echo $r1['n_documento'];?> y su Tesorero(a), <? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno'];?> con DNI N° <? echo $r2['n_documento'];?>. Intervienen también en el presente
contrato las siguientes organizaciones del territorio de influencia socioeconómica de "<? echo $org;?>", que en
general se les denominan "<? echo $orga;?>" y específicamente son:</td>
  </tr>
</table>

<div class="capa justificado">

<!-- Buscamos la Lista -->
<ol>
<?
//Buscamos las Organizaciones de Plan de Negocio
$sql="SELECT sys_bd_tipo_doc.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS nombre_org, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre_1, 
	tesorero.paterno AS paterno_1, 
	tesorero.materno AS materno_1, 
	cargo1.descripcion AS cargo_1, 
	cargo2.descripcion AS cargo_2, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM org_ficha_organizacion INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo dir1 ON dir1.cod_tipo_doc = presidente.cod_tipo_doc AND dir1.n_documento = presidente.n_documento AND dir1.cod_tipo_doc_org = presidente.cod_tipo_doc_org AND dir1.n_documento_org = presidente.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo cargo1 ON cargo1.cod_cargo = dir1.cod_cargo
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_directivo dir2 ON dir2.cod_tipo_doc = tesorero.cod_tipo_doc AND dir2.n_documento = tesorero.n_documento AND dir2.cod_tipo_doc_org = tesorero.cod_tipo_doc_org AND dir2.n_documento_org = tesorero.n_documento_org
	 LEFT OUTER JOIN sys_bd_cargo_directivo cargo2 ON cargo2.cod_cargo = dir2.cod_cargo
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
clar_atf_pdn.cod_tipo_atf_pdn=1 AND
pit_bd_ficha_pdn.cod_pit='$cod2'";
$result=mysql_query($sql) or die (mysql_error());

while($f11=mysql_fetch_array($result))
{
echo "<li>".$f11['nombre_org'].", con ".$f11['descripcion']." N° ".$f11['n_documento'].", representada por su ".$f11['cargo_1']." ".$f11['nombre']." ".$f11['paterno']." ".$f11['materno']." con DNI N° ".$f11['presidente']." y su ".$f11['cargo_2']." ".$f11['nombre_1']." ".$f11['paterno_1']." ".$f11['materno_1']." con DNI N° ".$f11['tesorero'].".</li>";
}
//Buscamos los PGRN

$sql="SELECT sys_bd_tipo_doc.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS nombre_org, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre_1, 
	tesorero.paterno AS paterno_1, 
	tesorero.materno AS materno_1, 
	cargo1.descripcion AS cargo1, 
	cargo2.descripcion AS cargo2
FROM org_ficha_organizacion INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo dir1 ON dir1.cod_tipo_doc = presidente.cod_tipo_doc AND dir1.n_documento = presidente.n_documento AND dir1.cod_tipo_doc_org = presidente.cod_tipo_doc_org AND dir1.n_documento_org = presidente.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo cargo1 ON cargo1.cod_cargo = dir1.cod_cargo
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT JOIN org_ficha_directivo dir2 ON dir2.cod_tipo_doc = tesorero.cod_tipo_doc AND dir2.n_documento = tesorero.n_documento AND dir2.cod_tipo_doc_org = tesorero.cod_tipo_doc_org AND dir2.n_documento_org = tesorero.n_documento_org
	 LEFT JOIN sys_bd_cargo_directivo cargo2 ON cargo2.cod_cargo = dir2.cod_cargo
	 LEFT JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_mrn.cod_pit='$cod2'";

$result=mysql_query($sql) or die (mysql_error());
$total2=mysql_num_rows($result);

while($f12=mysql_fetch_array($result))
{	
echo "<li>".$f12['nombre_org'].", con ".$f12['descripcion']." N° ".$f12['n_documento'].", representada por su ".$f12['cargo1']." ".$f12['nombre']." ".$f12['paterno']." ".$f12['materno']." con DNI N° ".$f12['presidente']." y su ".$f12['cargo2']." ".$f12['nombre_1']." ".$f12['paterno_1']." ".$f12['materno_1']." con DNI N° ".$f12['tesorero'].".</li>";
}
?>

</ol>
</div>

<div class="capa justificado">
Los representantes de "<? echo $org;?>" y de "<? echo $orga;?>" se constituyen como responsables solidarios de este Contrato, el cual se suscribe en los términos y condiciones establecidas en las cláusulas siguientes:
</div>
<table width="90%" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA PRIMERA:</u>  ANTECEDENTES</strong></td>
  </tr>
  <tr>
    <td width="5%" align="justify" valign="top">1.1.</td>
    <td width="95%" align="justify">"<? echo $proyecto;?>", es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA; dichos recursos son transferidos a "<? echo $proyecto;?>" a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
  </tr>
  <tr>
    <td align="justify" valign="top">1.2.</td>
    <td width="95%" align="justify">En el marco de la estrategia de ejecución de "<? echo $proyecto;?>", se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, se promueve que las organizaciones rurales presenten un Plan de Inversión Territorial, en adelante "<? echo $pit;?>".</td>
  </tr>
  <tr>
    <td align="justify" valign="top">1.3.</td>
    <td width="95%" align="justify">"<? echo $pit;?>" es un conjunto de iniciativas de inversión para el desarrollo de territorios rurales, compuesto principalmente por Planes de Gestión de Recursos Naturales - PGRN, Planes de Negocio - PDN, y otras que se ejecutan en un espacio socioeconómico de influencia directa y de interes común a  "<? echo $org;?>" y "<? echo $orga;?>"</td>
  </tr>
  <tr>
    <td align="justify" valign="top">1.4.</td>
    <td width="95%" align="justify">"<? echo $org;?>" es una  persona jurídica que promueve el mejoramiento de las condiciones de vida de las familias que la integran. "<? echo $orga;?>" son también  personas  jurídicas ubicadas dentro del territorio de influencia de "<? echo $org;?>", que promueven iniciativas socio-económicas específicas. "<? echo $org;?>" ha presentado a "<? echo $proyecto;?>" su propuesta de PIT compuesta por  PGRNs y/o PDNs. </td>
  </tr>
  <tr>
    <td align="justify" valign="top">1.5.</td>
    <td width="95%" align="justify">El Comité Local de Asignación de Recursos – CLAR de la Oficina Local de <? echo $r3['nombre'];?> de "<? echo $proyecto;?>" ha seleccionado, conforme consta en el Acta N° <strong><? echo numeracion($r3['n_acta']);?></strong> del <? echo traducefecha($r3['f_evento']);?>, "<? echo $pit;?>" propuesto por la "<? echo $org;?>"  y "<? echo $orga;?>", compuesto por  <?php  echo litera($r4['mrn']);?> (<? echo $r4['mrn'];?>) <strong>PGRN</strong> y/o <?php  echo litera($r5['pdn']);?>(<? echo $r5['pdn'];?>) <strong>PDN</strong>. Corresponde a "<? echo $org;?>" liderar la gestión de "<? echo $pit;?>" y a "<? echo $orga;?>" la ejecución de cada iniciativa que compone "<? echo $pit;?>".</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA SEGUNDA:</u>  OBJETO DEL CONTRATO</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">
    Por el presente  contrato "<? echo $proyecto;?>" transfiere en donación sujeto a cargo, el monto total de S/. <? echo number_format($aporte_pdss,2);?>  (<? echo vuelveletra($aporte_pdss);?> Nuevos Soles) a "<? echo $org;?>" y a "<? echo $orga;?>"; las mismas que       se comprometen  a aportar el monto de S/. <? echo number_format($aporte_org,2);?>  (<? echo vuelveletra($aporte_org);?> Nuevos Soles). Ambos montos serán destinados  para financiar la ejecución de "<? echo $pit;?>", según se resume en el siguiente cuadro:</td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="70%"><strong>PLAN DE INVERSION TERRITORIAL</strong></td>
    <td width="10%" align="center"><strong>APORTE SIERRA SUR II (S/.)</strong></td>
    <td width="10%" align="center"><strong>APORTES &quot;LA ORG.&quot; (S/.)</strong></td>
    <td width="10%" align="center"><strong>TOTAL (S/.)</strong></td>
  </tr>
<?
$sql="SELECT
pit_bd_ficha_pit.n_animador,
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org,
org_ficha_taz.nombre
FROM
pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2'";
$result=mysql_query($sql) or die (mysql_error());
while($f6=mysql_fetch_array($result))
{
?>
   <tr>
    <td><strong>Animadores Territoriales </strong>(<? echo $f6['n_animador'];?>) a  cargo de  "<? echo $f6['nombre'];?>", según las especificaciones del <strong>Anexo N° 1</strong> </td>
    <td align="right"><? echo number_format($f6['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format($f6['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($f6['aporte_pdss']+$f6['aporte_org'],2);?></td>
  </tr> 
<?
}
?>   
<?
//PGRN
$sql="SELECT
(pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss) as aporte_pdss,
org_ficha_organizacion.nombre,
(pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org) as aporte_org
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE
pit_bd_ficha_mrn.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> '001' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> '003'
";
$result=mysql_query($sql) or die (mysql_error());
$num=1;
while($f4=mysql_fetch_array($result))
{
	$num++
?>  
  <tr>
    <td><strong>PGRN</strong> a cargo de <? echo $f4['nombre'];?>, según las especificaciones del <strong>Anexo  N° <? echo $num;?></strong></td>
    <td align="right"><? echo number_format($f4['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format($f4['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($f4['aporte_pdss']+$f4['aporte_org'],2);?></td>
  </tr>
<?
}
?>
<?
$sql="SELECT
org_ficha_organizacion.nombre,
(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) as aporte_pdss,
(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) as aporte_org,
pit_bd_ficha_pdn.denominacion
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE
pit_bd_ficha_pdn.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$num1=$r4['mrn']+1;
while($f5=mysql_fetch_array($result))
{
	$num1++
?>
  <tr>
    <td><strong>PDN</strong> denominado <? echo $f5['denominacion'];?>, a cargo de <? echo $f5['nombre'];?>, según las especificaciones del <strong>Anexo  N° <? echo $num1;?></strong></td>
    <td align="right"><? echo number_format($f5['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format($f5['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($f5['aporte_pdss']+$f5['aporte_org'],2);?></td>
  </tr>
<?
}
?>

  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($aporte_pdss,2);?></td>
    <td align="right"><? echo number_format($aporte_org,2);?></td>
    <td align="right"><? echo number_format($aporte_total,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>%</strong></td>
    <td align="right"><? echo number_format(@$pp_pdss,2);?></td>
    <td align="right"><? echo number_format(@$pp_org,2);?></td>
    <td align="right"><? echo number_format(@$pp_pdss+@$pp_org,2);?></td>
  </tr>
</table>
<br>
<div class="capa">El documento de "<? echo $pit;?>", aprobado por el CLAR, compuesto por  
  <?php  echo litera($r4['mrn']);?>
(<? echo $r4['mrn'];?>) <strong>PGRN</strong> y/o 
<?php  echo litera($r5['pdn']);?>
(<? echo $r5['pdn'];?>) <strong>PDN</strong>,   suscrito por las partes,  forman parte  de  este contrato.</div>
<br>
  <table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA TERCERA  </u></strong><strong>: PLAZO DEL  CONTRATO:</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">
	<p>El plazo establecido por las partes para la ejecución del presente contrato es hasta de <? echo number_format($row['mes']);?> meses; se inicia el <? echo traducefecha($row['f_contrato']);?>  y culmina el  <? echo traducefecha($row['f_termino']);?>. Este  plazo incluye  las acciones de liquidación del Contrato y perfeccionamiento de la donación a "<? echo $org;?>".</p>
	<p>El plazo de vencimiento de cada iniciativa integrante de "<? echo $pit;?>", será el que se especifica en el respectivo anexo. Este  plazo incluye  las acciones de liquidación del Contrato y perfeccionamiento de la donación de cada una de "<? echo $orga;?>", que serán consideradas como acciones parciales.</p></td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA CUARTA </u></strong><strong>: DE LA  TRANSFERENCIA Y DESEMBOLSO  DE LOS  FONDOS</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">Los aportes de "<? echo $proyecto;?>" serán transferidos a "<? echo $org;?>" y a cada una de "<? echo $orga;?>" en dos desembolsos:</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><p><u><strong>Primer Desembolso:</strong></u> <br>
        Una vez suscrito el presente Contrato "<? echo $proyecto;?>", depositará como máximo:<br>
      -  El 70% de cada Plan de Gestión de Recursos Naturales y;<br>
      -  El 70% de cada Plan de Negocio; <br>
    -  El 70% del monto asignado para Animadores Territoriales </p>
      <p>"<? echo $orga;?>", aportarán como mínimo:<br>
      -  El 50% de cada Plan de Gestión de Recursos Naturales y;<br>
    -  El 50% de cada Plan de Negocio</p></td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><u><strong>Segundo Desembolso:</strong></u> Éste se otorgará por los saldos pendientes  de desembolso, previa aprobación por parte del CLAR de los avances de cada PGRN y PDN que serán sustentados por "<? echo $org;?>" y cada una de "<? echo $orga;?>" al medio tiempo de ejecución de "<? echo $pit;?>". Para la solicitud del Segundo Desembolso y sustentación de avances ante el CLAR, "<? echo $org;?>" y cada una de "<? echo $orga;?>" debe demostrar ante "<? echo $proyecto;?>", en forma documentada, haber utilizado debidamente al menos el 70% de los fondos transferidos del Primer Desembolso.</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA QUINTA </u></strong><strong>:  OBLIGACIONES DE LAS PARTES     </strong></td>
  </tr>
  <tr>
    <td width="5%" align="justify" valign="top">5.1.</td>
    <td width="95%" align="justify" valign="top">"<? echo $org;?>" y  cada  una de "<? echo $orga;?>" se obligan a :
      <ol type="a">
	    <li>Abrir una cuenta bancaria en una entidad financiera regulada y supervisada directamente por la Superintendencia de Banca y Seguros (SBS), con la finalidad de administrar los aportes señalados en la Cláusula Segunda.</li>
        <li>Realizar los aportes de cofinanciamiento de conformidad a lo establecido en la Cláusula Segunda  y presentar a "<? echo $proyecto;?>" las fotocopias de los vouchers de depósitos y/o estados de cuenta.</li>
        <li>Contratar a cada proveedor de asistencia técnica, entregando copia de dichos contratos a "<? echo $proyecto;?>", así como supervisar su desempeño en concordancia con el plan de trabajo  de cada uno de ellos y demás condiciones  establecidas en el respectivo contrato de locación de servicios. </li>
        <li>Realizar las actividades de los PGRN y PDN, conforme a los plazos y especificaciones contenidas en la propuesta de "<? echo $pit;?>" aprobado por el CLAR.</li>
        <li>Permitir el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato, especialmente por parte del personal de "<? echo $proyecto;?>", AGRORURAL, MINAG, MEF o FIDA.</li>
        <li>No utilizar y no contar con la participación de menores de edad, como mano de obra en el desarrollo de los PGRN y PDN, de conformidad a la legislación nacional vigente y los Convenios Internacionales existentes sobre la materia.</li>
        <li>Presentar el informe de avance de "<? echo $pit;?>" y adjuntar a éste las fotocopias de los comprobantes de pago y el estado de la cuenta que "<? echo $org;?>"  y "<? echo $orga;?>" mantienen en la  entidad financiera, para que "<? echo $proyecto;?>" pueda transferir y/o depositar el segundo desembolso; siempre y cuando se cuente con la opinión favorable de la Oficina Local de <? echo $row['oficina'];?> y/o la aprobación del CLAR.</li>
        <li>Seleccionar y designar  a los Animadores Territoriales que acompañen el desarrollo de los PGRN y PDN.</li>
        <li>Presentar a "<? echo $proyecto;?>", el Informe de Avance de Medio Tiempo y el Informe Final de Resultados, por cada PGRN y PDN que se ejecutan en mérito al presente contrato. </li>
        <li>Constituirse en depositarios y responsables de la documentación original generada por la ejecución del presente contrato por un plazo de hasta 5 años de perfeccionada la donación.</li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify" valign="top">5.2.</td>
    <td align="justify" valign="top">"<? echo $proyecto;?>" se obliga a:
      <ol type="a">
        <li>Efectuar los desembolsos referidos en la Cláusula Cuarta, en observancia de su disponibilidad de recursos económicos.</li>
<li>Emitir opinión sobre la información presentada en el ítem g. del punto 5.1. , previa a la realización del CLAR del Segundo Desembolso.</li>
<li>Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el perfeccionamiento de la donación.</li>
    </ol>   </td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA SEXTA </u></strong><strong>:  DEL CARGO, LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACIÓN</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">"<? echo $proyecto;?>" establece  a "<? echo $org;?>" y a cada una de  "<? echo $orga;?>" como cargo de la presente donación el cumplimiento del Objeto del presente contrato, la responsabilidad y transparencia en el buen manejo de los fondos transferidos, demostrando su cumplimiento a través de la presentación por parte de : 
	<br></br>
	6.1. "<? echo $orga;?>"
      <ol type="1">
	  <li>Informe Final del PGRN o PDN, según corresponda, incluyendo la rendición documentada, en fotocopia, de los fondos transferidos.</li>
      <li>Acta de Aprobación de Cierre del PGRN o PDN, por parte de cada una de  "<? echo $orga;?>".</li>
     <li>El estado de cuenta bancaria y la hoja del libro de bancos de cada una de "<? echo $orga;?>".</li>
      </ol>
    <p>6.2. "<? echo $org;?>"
	<ol>
      <li>Informe Final de "<? echo $pit;?>" que resuma los resultados de cada una de las iniciativas que lo integran, incluyendo la rendición documentada, en fotocopia, de los fondos transferidos.</li>
      <li>El estado de cuenta bancaria y la hoja del libro de bancos de "<? echo $org;?>".</li>
	  </ol>
    <p>El Contrato quedará liquidado y la Donación perfeccionada con el informe favorable de "<? echo $proyecto;?>", al que se adjuntará los documentos mencionados en los items de esta Cláusula, por cada uno de los PGRN o PDN de  "<? echo $orga;?>" y de "<? echo $pit;?>".</p></td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA SEPTIMA </u></strong><strong>:  RESOLUCIÓN DEL CONTRATO</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">El presente Contrato, se resolverá  automáticamente, por:
      <ol>
        <li>Incumplimiento de alguna de las partes en los términos y  condiciones establecidos en el presente Contrato.</li>
        <li>Mutuo acuerdo de las partes. </li>
        <li>Disolución de "<? echo $org;?>" o de alguna de "<? echo $orga;?>", en cuyo caso los  firmantes del presente Contrato por parte de "<? echo $org;?>", asumirán responsabilidad solidaria ante "<? echo $proyecto;?>"</li>
        <li>Presentación de información falsa ante "<? echo $proyecto;?>" por parte de "<? echo $org;?>" o de "<? echo $orga;?>". </li>
      </ol></td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA OCTAVA </u></strong><strong>: DE LAS SANCIONES</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">Conllevan sanciones en la aplicación  del presente Contrato:
      <ol>
        <li>En caso de resolución por incumplimiento de alguna de las partes pudiendo la parte agraviada iniciar las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es "<? echo $proyecto;?>", éste se reserva el derecho de comunicar por cualquier medio este hecho a la sociedad civil del ámbito de su acción. </li>
        <li>En caso de que "<? echo $org;?>" o alguna de "<? echo $orga;?>" efectúe un uso inapropiado o desvío de dichos fondos para otros fines no previstos en el presente Contrato o presente información falsa sobre sus antecedentes y/o en la ejecución del presente contrato, "<? echo $proyecto;?>" suspenderá automáticamente los desembolsos pendientes. Para levantar esta medida "<? echo $org;?>" deberá comunicar y acreditar a "<? echo $proyecto;?>" que ha implementado las medidas correctivas y aplicado las sanciones a los responsables si el caso lo amerita.</li>
        <li>En caso de disolución  de "<? echo $org;?>" o de alguna de "<? echo $orga;?>", los representantes solidarios  devolverán a "<? echo $proyecto;?>" los fondos no utilizados y aquellos gastos no sustentados, acompañado de un informe justificatorio a satisfacción de "<? echo $proyecto;?>".</li>
      </ol></td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA NOVENA:
    </u></strong><strong>  SITUACIONES NO PREVISTAS</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">En caso de ocurrir situaciones no  previstas en el presente Contrato o que, estando previstas, escapen al control  directo de alguna de las partes; mediante acuerdo mutuo, se determinarán las  medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de  esta naturaleza, serán expresados en un Acta, Adenda u otro instrumento, según el caso lo  amerite.</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA 
    DECIMA</u>: COMPETENCIA  TERRITORIAL y JURISDICCIONAL</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón a que la Unidad Ejecutora de "<? echo $proyecto;?>" se encuentra ubicada en el distrito de Quequeña de la provincia de Arequipa.</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA  
    DECIMO PRIMERA </u>: DOMICILIO</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">Para la validez de todas las  comunicaciones y notificaciones a las partes, con motivo de la ejecución de  este contrato, ambas señalan como sus respectivos domicilios los indicados en  la introducción de este documento. El cambio de domicilio de cualquiera de las  partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra  parte, por cualquier medio escrito.</td>
  </tr>
  <tr>
    <td colspan="2" align="justify"><strong><u>CLAUSULA  
    DECIMO SEGUNDA </u>: APLICACIÓN SUPLETORIA DE  LA LEY</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="justify">En lo no previsto por las partes en  el presente contrato, ambas se someten a lo establecido por las normas del  Código Civil y demás del sistema jurídico que resulten aplicables.</td>
  </tr>
</table>
  <?
$sql="SELECT
pit_bd_cofi_mrn.aporte
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_mrn ON pit_bd_cofi_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$total_adicional_1=mysql_num_rows($result);

$sql="SELECT
pit_bd_cofi_pdn.aporte
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_pdn ON pit_bd_cofi_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$total_adicional_2=mysql_num_rows($result);



if ($total_adicional_1<>0 or $total_adicional_2<>0)
{
?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td><strong><u>CLAUSULA DECIMO TERCERA:</u> COFINANCIAMIENTO ADICIONAL</strong></td>
  </tr>
  <tr>
    <td>En el CLAR de fecha <? echo traducefecha($r3['f_evento']);?>, "<? echo $org;?>" manifestó que ha gestionado el cofinanciamiento adicional de la entidades y  montos referenciales que se indican a continuación:</td>
  </tr>

</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="34%">Componentes del Plan de Inversion Territorial </td>
    <td width="27%">Entidad</td>
    <td width="21%">Tipo de Entidad </td>
    <td width="18%">Monto Cofinanciamiento Adicional (S/.) </td>
  </tr>
<?
$sql="SELECT DISTINCT
pit_bd_cofi_pdn.nombre AS entidad,
pit_bd_cofi_pdn.aporte,
org_ficha_organizacion.nombre as organizacion,
pit_bd_ficha_pdn.denominacion,
sys_bd_ente_cofinanciador.descripcion as tipo_ente
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_pdn ON pit_bd_cofi_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = pit_bd_cofi_pdn.cod_tipo_ente
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
while($e1=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><strong>PDN</strong> <? echo $e1['denominación'];?>, a cargo de <? echo $e1['organizacion'];?></td>
    <td class="centrado"><? echo $e1['entidad'];?></td>
    <td class="centrado"><? echo $e1['tipo_ente'];?></td>
    <td class="derecha"><? echo number_format($e1['aporte'],2);?></td>
  </tr>
<?
}
?>  
<?
$sql="SELECT DISTINCT
pit_bd_ficha_mrn.lema,
org_ficha_organizacion.nombre AS organizacion,
pit_bd_cofi_mrn.nombre AS entidad,
pit_bd_cofi_mrn.aporte,
sys_bd_ente_cofinanciador.descripcion AS tipo_ente
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_mrn ON pit_bd_cofi_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_ente_cofinanciador ON sys_bd_ente_cofinanciador.cod_ente = pit_bd_cofi_mrn.cod_tipo_ente
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003
ORDER BY
pit_bd_ficha_mrn.cod_mrn ASC";
$result=mysql_query($sql) or die (mysql_error());
while($e2=mysql_fetch_array($result))
{
?>
  <tr>
    <td><strong>PGRN</strong> de <? echo $e2['organizacion'];?>, "<? echo $e2['lema'];?>" </td>
    <td class="centrado"><? echo $e2['entidad'];?></td>
    <td class="centrado"><? echo $e2['tipo_ente'];?></td>
    <td class="derecha"><? echo number_format($e2['aporte'],2);?></td>
  </tr>
<?
}
?>  

<?
//calculo los montos de cofinanciamiento
$sql="SELECT
Sum(pit_bd_cofi_mrn.aporte) AS aporte_mrn
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_mrn ON pit_bd_cofi_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$e3=mysql_fetch_array($result);

$sql="SELECT
Sum(pit_bd_cofi_pdn.aporte) AS aporte_pdn
FROM
pit_bd_ficha_pit
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
INNER JOIN pit_bd_cofi_pdn ON pit_bd_cofi_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$e4=mysql_fetch_array($result);

$total_cofinanciamiento=$e3['aporte_mrn']+$e4['aporte_pdn'];

?>
  <tr class="txt_titulo">
    <td colspan="3">TOTAL</td>
    <td class="derecha"><? echo number_format($total_cofinanciamiento,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>"<? echo $org;?>" se encargará de gestionar la efectivización de los recursos adicionales referidos en la presente  cláusula.</td>
  </tr>
</table>
<?
}
?>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>En fe de lo acordado, suscribimos el  presente contrato en tres ejemplares, en la localidad de <? echo $row['ubicacion'];?>, el <? echo traducefecha($row['f_contrato']);?>. </td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="40%" align="center">_______________</td>
    <td width="20%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
  </tr>
  <tr class="mini">
    <td align="center"><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?><br><? echo $row['nombre_pit'];?><BR><b>PRESIDENTE PIT</b></td>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno'];?><br><? echo $row['nombre_pit'];?><BR><b>TESORERO PIT</b></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">

      

<tr>
<td width="40%">

<?
$sql="SELECT DISTINCT org_ficha_organizacion.nombre AS org, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_cargo_directivo.descripcion AS cargo
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = org_ficha_usuario.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
clar_atf_pdn.cod_tipo_atf_pdn=1 AND
pit_bd_ficha_pdn.cod_pit='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f7['nombre']." ".$f7['paterno']." ".$f7['materno'];?> <BR>
<? echo $f7['org'];?><br>
<b><? echo $f7['cargo'];?> DEL PLAN DE NEGOCIO</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?></td>
    <td width="20%">&nbsp;</td>
    <td width="40%">
        <?
$sql="SELECT DISTINCT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_organizacion.nombre AS org
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit ='$cod2' AND
clar_atf_pdn.cod_tipo_atf_pdn=1 AND
org_ficha_directivo.cod_cargo = 3";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?> <BR>
<? echo $f8['org'];?><br>
<b>TESORERO DEL PLAN DE NEGOCIO</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?>    
    
    </td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">

      

<tr>
<td width="40%">

<?
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_organizacion
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
org_ficha_directivo.cod_cargo = 1";
$result=mysql_query($sql) or die (mysql_error());
while($f7=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f7['nombre']." ".$f7['paterno']." ".$f7['materno'];?> <BR>
<? echo $f7['org'];?><br>
<b>PRESIDENTE DEL PLAN DE GESTIÓN DE RECURSOS NATURALES</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?></td>
    <td width="20%">&nbsp;</td>
    <td width="40%">
        <?
$sql="SELECT DISTINCT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_organizacion
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
org_ficha_directivo.cod_cargo = 3";
$result=mysql_query($sql) or die (mysql_error());
while($f8=mysql_fetch_array($result))
{
?>
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td align="center">_______________</td>
</tr>
<tr class="mini">
<td align="center"><? echo $f8['nombre']." ".$f8['paterno']." ".$f8['materno'];?> <BR>
<? echo $f8['org'];?><br>
<b>TESORERO DEL PLAN DE GESTIÓN DE RECURSOS NATURALES</b> <BR></td>

<tr class="txt_titulo">
  <td align="center">&nbsp;</td>
</tr>       

</tr>      
    </table> <?
}
?>    
    
    </td>
  </tr>
</table>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="30%" align="center">&nbsp;</td>
    <td width="40%" align="center">_______________</td>
    <td width="30%" align="center">&nbsp;</td>
  </tr>
  <tr class="mini">
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre']." ".$row['apellido'];?><BR><B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° 1</u> </td>
  </tr>
  <tr>
    <td class="txt_titulo centrado">Aportes de cofinanciamiento de  desembolsos de los Animadores Territoriales </td>
  </tr>
  <tr>
    <td class="txt_titulo centrado"><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT:</strong> <? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización :</strong> <? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td><strong>Número de animadores territoriales :</strong> <? echo $row['n_animador'];?></td>
  </tr>
  <tr>
  <td><strong>Referencia :</strong> CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?> con fecha <?php  echo traducefecha($row['f_contrato']);?></td>
  </tr>
  
  <tr>
    <td><strong>Plazo de ejecución :</strong> Hasta <? echo $row['mes'];?> meses</td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="215" align="center"><strong>CONCEPTO</strong></td>
    <td width="55" align="center"><strong>Aporte<br>
    SIERRA SUR  II</strong></td>
    <td width="22" align="center" valign="middle" nowrap class="mini"><strong>%</strong></td>
    <td width="30" align="center" valign="middle" nowrap class="mini"><strong>Aporte<br>
      SOCIOS</strong></td>
    <td width="20" align="center" valign="middle" nowrap class="mini"><strong>%</strong></td>
    <td width="26" align="center"><strong>TOTAL</strong></td>
    <td width="26" align="center"><strong>%</strong></td>
  </tr>
  <tr>
    <td nowrap valign="bottom">I.- <strong>Animadores Territoriales</strong> (<? echo $row['n_animador'];?>) de EL PIT </td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="right"><? @$pp_at=$row['aporte_pdss']/$row['aporte_pdss']+$row['aporte_org']*100; echo number_format(@$pp_at,2);?></td>
    <td align="right"><? echo number_format($row['aporte_org'],2);?></td>
    <td align="right">
    <?
	@$pp_an=($row['aporte_org']/($row['aporte_org']+$row['aporte_pdss']))*100;
	echo number_format(@$pp_an,2);
	?>
    </td>
    <td align="right"><? echo number_format($row['aporte_pdss']+$row['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($pp_at+$pp_an,2);?></td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format(@$pp_at,2);?></td>
    <td align="right"><? echo number_format($row['aporte_org'],2);?></td>
    <td align="right"><? echo number_format(@$pp_an,2);?></td>
    <td align="right" ><? echo number_format($row['aporte_pdss']+$row['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($pp_at+$pp_an,2);?></td>
  </tr>
  <tr>
    <td colspan="7" align="center" valign="bottom" nowrap><strong>N °  Desembolso del Animador Territorial</strong></td>
  </tr>
  <tr>
    <td nowrap valign="bottom"><strong>Primero</strong></td>
    <td align="right"><? echo number_format($row['aporte_pdss']*0.70,2);?></td>
    <td align="right">70.00</td>
    <td align="right"><? echo number_format($row['aporte_org']*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$atpdss1=$row['aporte_pdss']*0.70;
	$atorg1=$row['aporte_org']*0.50;
	$total_at1=$atpdss1+$atorg1;
	echo number_format($total_at1,2);
	?></td>
    <td align="right" ><? @$pp_1=($total_at1/$total_at)*100; echo number_format(@$pp_1,2);?></td>
  </tr>
  <tr>
    <td nowrap valign="bottom"><strong>Segundo</strong></td>
    <td align="right"><? echo number_format($row['aporte_pdss']*0.30,2);?></td>
    <td align="right" >30.00</td>
    <td align="right"><? echo number_format($row['aporte_org']*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$atpdss2=$row['aporte_pdss']*0.30;
	$atorg2=$row['aporte_org']*0.50;
	$total_at2=$atpdss2+$atorg2;
	echo number_format($total_at2,2);
	?></td>
    <td align="right"><? @$pp_2=($total_at2/$total_at)*100; echo number_format(@$pp_2,2);?></td>
  </tr>
  <tr>
    <td align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($row['aporte_org'],2);?></td>
    <td align="right">100.00</td>
    <td align="right"><?  echo number_format($total_at,2);?></td>
    <td align="right">100.00</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">

  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre']." ".$row['apellido'];?><BR>
    <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>






<?
$sql="SELECT
pit_bd_ficha_mrn.cif_pdss,
pit_bd_ficha_mrn.at_pdss,
pit_bd_ficha_mrn.at_org,
pit_bd_ficha_mrn.vg_pdss,
pit_bd_ficha_mrn.vg_org,
pit_bd_ficha_mrn.ag_pdss,
org_ficha_organizacion.nombre,
pit_bd_ficha_mrn.f_presentacion,
pit_bd_ficha_mrn.f_inicio,
pit_bd_ficha_mrn.mes,
pit_bd_ficha_mrn.f_termino,
pit_bd_ficha_mrn.lema,
presidente.nombre AS nombre11,
presidente.paterno,
presidente.materno,
tesorero.nombre AS nombre1,
tesorero.paterno AS paterno1,
tesorero.materno AS materno1
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit ='$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());

$num2=1;
while($f9=mysql_fetch_array($result))
{	
	$num2++
?>
<? 
$total_mrn_pdss=$f9['cif_pdss']+$f9['at_pdss']+$f9['vg_pdss']+$f9['ag_pdss'];
$total_mrn_org=$f9['at_org']+$f9['vg_org'];
$total_mrn=$total_mrn_pdss+$total_mrn_org;
?>
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $num2;?></u></td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Gestión de Recursos Naturales </td>
  </tr>
  <tr class="txt_titulo centrado">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT :</strong> <? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PGRN :</strong> <? echo $f9['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Lema del PGRN :</strong> <? echo $f9['lema'];?></td>
  </tr>
    <tr>
  <td><strong>Referencia :</strong> CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?> con fecha <?php  echo traducefecha($row['f_contrato']);?></td>
  </tr>
  <tr>
    <td><strong>Plazo de ejecución :</strong> Hasta <? echo $f9['mes'];?> meses </td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="214" align="center" valign="middle" nowrap><strong>CONCEPTO</strong></td>
    <td width="54" align="center" valign="middle" nowrap><strong>Aporte<BR>  SIERRA SUR II</strong></td>
    <td width="22" align="center" valign="middle" nowrap>%</td>
    <td width="30" align="center" valign="middle" nowrap><strong>Aporte<BR>  SOCIOS</strong></td>
    <td width="23" align="center" valign="middle" nowrap>%</td>
    <td width="26" align="center" valign="middle" nowrap><strong>TOTAL</strong></td>
    <td width="25" align="center" valign="middle" nowrap><p align="center"><strong>%</strong></p></td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">I.- Premios para Concursos Inter  Familiares - CIF</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f9['cif_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap>0.00</td>
    <td width="23" align="right" valign="bottom" nowrap>0.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f9['cif_pdss'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">II.- Asistencia Técnica de  campesino a campesino</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f9['at_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format(($f9['at_pdss']/($f9['at_pdss']+$f9['at_org']))*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($f9['at_org'],2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format(($f9['at_org']/($f9['at_pdss']+$f9['at_org']))*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f9['at_pdss']+$f9['at_org'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">III.- Visitas Guiadas</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f9['vg_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format(($f9['vg_pdss']/($f9['vg_pdss']+$f9['vg_org']))*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($f9['vg_org'],2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format(($f9['vg_org']/($f9['vg_pdss']+$f9['vg_org']))*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f9['vg_pdss']+$f9['vg_org'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">IV.- Apoyo a la Gestión del PGRN</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($f9['ag_pdss'],2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap>0.00</td>
    <td width="23" align="right" valign="bottom" nowrap>0.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($f9['ag_pdss'],2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td width="214" align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td width="54" align="right" valign="bottom" nowrap>
 <?

 echo number_format($total_mrn_pdss,2);
 ?>   
    </td>
    <td width="22" align="right" valign="bottom" nowrap><? echo number_format(($total_mrn_pdss/$total_mrn)*100,2);?></td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_org,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap><? echo number_format(($total_mrn_org/$total_mrn)*100,2);?></td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_mrn,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
  <tr>
    <td colspan="7" align="center" valign="bottom" nowrap><strong>N ° Desembolso PGRN</strong></td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">Primero</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_pdss*0.70,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>70.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_org*0.50,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>50.00</td>
    <td width="26" align="right" valign="bottom" nowrap>
	<?
	$mrnpdss1=$total_mrn_pdss*0.70;
	$mrnorg1=$total_mrn_org*0.50;
	$total_mrn1=$mrnpdss1+$mrnorg1;
	echo number_format($total_mrn1,2);

	?>
    </td>
    <td width="25" align="right" valign="bottom" nowrap><? echo number_format(($total_mrn1/$total_mrn)*100,2);?></td>
  </tr>
  <tr>
    <td width="214" nowrap valign="bottom">Segundo</td>
    <td width="54" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_pdss*0.30,2);?></td>
    <td width="22" align="right" valign="bottom" nowrap>30.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_org*0.50,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>50.00</td>
    <td width="26" align="right" valign="bottom" nowrap>
	<?
	$mrnpdss2=$total_mrn_pdss*0.30;
	$mrnorg2=$total_mrn_org*0.50;
	$total_mrn2=$mrnpdss2+$mrnorg2;
	echo number_format($total_mrn2,2);
	?>
    </td>
    <td width="25" align="right" valign="bottom" nowrap><? echo number_format(($total_mrn2/$total_mrn)*100,2);?></td>
  </tr>
  <tr>
    <td width="214" align="center" valign="bottom" nowrap><strong>TOTAL</strong></td>
    <td width="54" align="right" valign="bottom" nowrap><?

 echo number_format($total_mrn_pdss,2);
 ?></td>
    <td width="22" align="right" valign="bottom" nowrap>100.00</td>
    <td width="30" align="right" valign="bottom" nowrap><? echo number_format($total_mrn_org,2);?></td>
    <td width="23" align="right" valign="bottom" nowrap>100.00</td>
    <td width="26" align="right" valign="bottom" nowrap><? echo number_format($total_mrn,2);?></td>
    <td width="25" align="right" valign="bottom" nowrap>100.00</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre']." ".$row['apellido'];?><BR>
        <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?
}
?>

<?
$sql="SELECT
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
pit_bd_ficha_pdn.total_apoyo,
pit_bd_ficha_pdn.at_pdss,
pit_bd_ficha_pdn.vg_pdss,
pit_bd_ficha_pdn.fer_pdss,
pit_bd_ficha_pdn.at_org,
pit_bd_ficha_pdn.vg_org,
pit_bd_ficha_pdn.fer_org,
pit_bd_ficha_pdn.f_presentacion,
pit_bd_ficha_pdn.f_inicio,
pit_bd_ficha_pdn.mes,
pit_bd_ficha_pdn.f_termino,
presidente.nombre AS nombre11,
presidente.paterno,
presidente.materno,
tesorero.nombre AS nombre1,
tesorero.paterno AS paterno1,
tesorero.materno AS materno1
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pdn.cod_pit ='$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$num33=$r4['mrn']+1;
while($f10=mysql_fetch_array($result))
{
	$num33++
?>
<? 
$total_pdn_pdss=$f10['total_apoyo']+$f10['at_pdss']+$f10['vg_pdss']+$f10['fer_pdss'];
$total_pdn_org=$f10['at_org']+$f10['vg_org']+$f10['fer_org'];
$total_pdn=$total_pdn_pdss+$total_pdn_org;
?>
<? include("encabezado.php");?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $num33;?></u></td>
  </tr>
  <tr class="centrado txt_titulo">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Negocio </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT :</strong> <? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PDN :</strong> <? echo $f10['nombre'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre del Plan de Negocio :</strong> <? echo $f10['denominacion'];?></td>
  </tr>
    <tr>
  <td><strong>Referencia :</strong> CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?> con fecha <?php  echo traducefecha($row['f_contrato']);?></td>
  </tr>
  <tr>
    <td><strong>Plazo de ejecución :</strong> Hasta <? echo $f10['mes'];?> meses.</td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo centrado">
    <td>CONCEPTO</td>
    <td>Aporte<br>
    SIERRA SUR  II</td>
    <td>%</td>
    <td>Aporte<br>
      SOCIOS</td>
    <td>%</td>
    <td>TOTAL</td>
    <td>%</td>
  </tr>
  <tr>
    <td>I.- Asistencia  Técnica</td>
    <td align="right"><? echo number_format($f10['at_pdss'],2);?></td>
    <td align="right"><? echo number_format(($f10['at_pdss']/($f10['at_pdss']+$f10['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($f10['at_org'],2);?></td>
    <td align="right"><? echo number_format(($f10['at_org']/($f10['at_pdss']+$f10['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($f10['at_pdss']+$f10['at_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>II.- Visita  Guiada </td>
    <td align="right"><? echo number_format($f10['vg_pdss'],2);?></td>
    <td align="right"><? @$ppvisita=($f10['vg_pdss']/($f10['vg_pdss']+$f10['vg_org']))*100; echo number_format(@$ppvisita,2);?></td>
    <td align="right"><? echo number_format($f10['vg_org'],2);?></td>
    <td align="right">
	<? 
	@$ppvis1=($f10['vg_org']/($f10['vg_pdss']+$f10['vg_org']))*100; echo number_format(@$ppvis1,2);
	?></td>
    <td align="right"><? echo number_format($f10['vg_pdss']+$f10['vg_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>III.- Participación  en Ferias</td>
    <td align="right"><? echo number_format($f10['fer_pdss'],2);?></td>
    <td align="right"><? @$ppfer=($f10['fer_pdss']/($f10['fer_pdss']+$f10['fer_org']))*100; echo number_format(@$ppfer,2);?></td>
    <td align="right"><? echo number_format($f10['fer_org'],2);?></td>
    <td align="right"><? @$ppfer1=$f10['fer_org']/($f10['fer_pdss']+$f10['fer_org'])*100; echo number_format(@$ppfer1,2);?></td>
    <td align="right"><? echo number_format($f10['fer_pdss']+$f10['fer_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>IV.- Apoyo a  la Gestión del PDN</td>
    <td align="right"><? echo number_format($f10['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right"><? echo number_format($f10['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>V.- Inversiones  en Activos</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($total_pdn_pdss,2);?></td>
    <td align="right"><? echo number_format(($total_pdn_pdss/$total_pdn)*100,2);?></td>
    <td align="right"><? echo number_format($total_pdn_org,2);?></td>
    <td align="right"><? echo number_format(($total_pdn_org/$total_pdn)*100,2);?></td>
    <td align="right"><? echo number_format($total_pdn,2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td colspan="7" align="center"><strong>N °  Desembolso PDN</strong></td>
  </tr>
  <tr>
    <td>Primero</td>
    <td align="right"><? echo number_format($total_pdn_pdss*0.70,2);?></td>
    <td align="right">70.00</td>
    <td align="right"><? echo number_format($total_pdn_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right">
	<?
	$pdnpdss1=$total_pdn_pdss*0.70;
	$pdnorg1=$total_pdn_org*0.50;
	$total_pdn1=$pdnpdss1+$pdnorg1;
	echo number_format($total_pdn1,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn1/$total_pdn)*100,2);?></td>
  </tr>
  <tr>
    <td>Segundo</td>
    <td align="right"><? echo number_format($total_pdn_pdss*0.30,2);?></td>
    <td align="right">30.00</td>
    <td align="right"><? echo number_format($total_pdn_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$pdnpdss2=$total_pdn_pdss*0.30;
	$pdnorg2=$total_pdn_org*0.50;
	$total_pdn2=$pdnpdss2+$pdnorg2;
	echo number_format($total_pdn2,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn2/$total_pdn)*100,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($total_pdn_pdss,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total_pdn_org,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total_pdn,2);?></td>
    <td align="right">100.00</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center">___________________________</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><? echo $row['nombre']." ".$row['apellido'];?><BR>
        <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<?
}
?>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">RESUMEN DE DESEMBOLSOS</div>
<div class="break" align="center"></div>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="215" align="center"><strong>CONCEPTO</strong></td>
    <td width="55" align="center"><strong>Aporte<br>
    SIERRA SUR  II</strong></td>
    <td width="22" align="center" valign="middle" nowrap><strong>%</strong></td>
    <td width="30" align="center" valign="middle" nowrap><strong>Aporte<br>
      SOCIOS</strong></td>
    <td width="20" align="center" valign="middle" nowrap><strong>%</strong></td>
    <td width="26" align="center"><strong>TOTAL</strong></td>
    <td width="26" align="center"><strong>%</strong></td>
  </tr>
  <tr>
    <td align="center">PRIMER DESEMBOLSO </td>
    <td width="55" align="right">
	<?
	$pdss70=$aporte_pdss*0.70;
	echo number_format($pdss70,2);
	?>
	</td>
    <td width="22" align="right" valign="middle" nowrap>70.00</td>
    <td width="30" align="right" valign="middle" nowrap><?
	$org1=$aporte_org*0.50;
	echo number_format($org1,2);
	?></td>
    <td width="20" align="right" valign="middle" nowrap>50.00</td>
    <td width="26" align="right"><? echo number_format($pdss70+$org1,2);?></td>
    <td width="26" align="right">-</td>
  </tr>
  <tr>
    <td align="center">SEGUNDO DESEMBOLSO </td>
    <td width="55" align="right"><?
	$pdss30=$aporte_pdss*0.30;
	echo number_format($pdss30,2);
	?></td>
    <td width="22" align="right" valign="middle" nowrap>30.00</td>
    <td width="30" align="right" valign="middle" nowrap><?
	$org1=$aporte_org*0.50;
	echo number_format($org1,2);
	?></td>
    <td width="20" align="right" valign="middle" nowrap>50.00</td>
    <td width="26" align="right"><? echo number_format($pdss30+$org1,2);?></td>
    <td width="26" align="right">-</td>
  </tr>
  <tr>
    <td width="53%" align="center">TOTAL GENERAL </td>
    <td width="14%" align="right"><? echo number_format($aporte_pdss,2);?></td>
    <td width="6%" align="right"><? echo number_format(@$pp_pdss,2);?></td>
    <td width="8%" align="right"><? echo number_format($aporte_org,2);?></td>
    <td width="5%" align="right"><? echo number_format(@$pp_org,2);?></td>
    <td width="7%" align="right"><? echo number_format($aporte_total,2);?></td>
    <td width="7%" align="right"><? echo number_format(@$pp_pdss+@$pp_org,2);?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<?
$sql="SELECT
clar_atf_pit.n_atf,
sys_bd_componente_poa.codigo AS codigo_componente,
sys_bd_componente_poa.nombre AS nombre_componente,
sys_bd_subactividad_poa.codigo AS codigo_poa,
sys_bd_subactividad_poa.nombre AS nombre_poa,
sys_bd_categoria_poa.codigo AS categoria_poa,
clar_atf_pit.monto_desembolsado,
clar_atf_pit.saldo,
pit_bd_ficha_pit.n_voucher,
pit_bd_ficha_pit.monto_organizacion
FROM
clar_atf_pit
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pit.cod_componente
INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit.cod_poa
INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit.cod_pit
WHERE
clar_atf_pit.cod_pit ='$cod2'";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);
?>



<div class="capa txt_titulo" align="center">
<u>PRIMER DESEMBOLSO</u>
<br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r9['n_atf']);?> – PIT – <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><br> 
PARA EL ANIMADOR TERRITORIAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($r9['monto_desembolsado'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">
  En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle
</div>
<br>




	<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
		<tr>
			<td width="35%" class="txt_titulo">Organización PIT</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $row['nombre_pit'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Organización a transferir</td>
			<td width="4%" align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $row['nombre_pit'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $row['tipo_org'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Referencia</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?>
				- PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de desembolso</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2">PRIMER DESEMBOLSO</td>
		</tr>
		<tr>
			<td class="txt_titulo">Entidad financiera</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $row['banco'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">N° de cuenta bancaria</td>
			<td align="center" class="txt_titulo">:</td>
			<td colspan="2"><? echo $row['n_cuenta'];?></td>
		</tr>
		<tr>
			<td class="txt_titulo">Fuente de Financiamiento</td>
			<td align="center" class="txt_titulo">:</td>
			<td width="30%">FIDA: <? echo number_format($row['fuente_fida'],2);?></td>
			<td width="31%">RO: <? echo number_format($row['fuente_ro'],2);?></td>
		</tr>
	</table>
	<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>



<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo">
    <td width="43%" align="center">ACTIVIDADES</td>
    <td width="6%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>

  <tr>
    <td>Primer desembolso</td>
    <td class="centrado">70.00 </td>
    <td align="right" class="txt_titulo"><? echo number_format($r9['monto_desembolsado'],2);?></td>
    <td align="center"><? echo $r9['codigo_poa'];?></td>
    <td align="center"><? echo $r9['categoria_poa'];?></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($r9['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($r9['saldo'],2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right">30.00</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><? echo $r9['n_voucher'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($r9['monto_organizacion'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong>
	 <? 
	 if ($r9['monto_organizacion']>$row['aporte_org'])
	 {
	 $saldo_animador=0;
	 }
	 else
	 {
	 $saldo_animador=$row['aporte_org']-$r9['monto_organizacion'];
	 }
	 echo number_format($saldo_animador,2);
	 ?>
    </td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	if ($r9['monto_organizacion']>$row['aporte_org'])
	{
	$pp_saldo_animador=0;
	}
	else
	{
	@$pp_saldo_animador=($r9['monto_organizacion']/$row['aporte_org'])*100;
	}
	echo number_format(@$pp_saldo_animador,2);
	?>
	</td>
  </tr>
</table>


<br>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud de cofinanciamiento del PIT presentado por la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo para trabajar con SIERRA SUR II y aportes de cofinanciamiento de la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de la Ficha de Incripción en la SUNARP </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNIs de los directivos de la Organización responsable del PIT </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de aprobación del CLAR </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>PIT aprobado por el CLAR y suscrito por las partes </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del voucher de depósito de la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_contrato']);?></div>
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
$sql="SELECT
clar_atf_pdn.n_atf,
poa_1.codigo AS poa_1,
poa_2.codigo AS poa_2,
poa_3.codigo AS poa_3,
poa_4.codigo AS poa_4,
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
cat_1.codigo AS categoria_1,
cat_2.codigo AS categoria_2,
cat_4.codigo AS categoria_3,
cat_3.codigo AS categoria_4,
pit_bd_ficha_pdn.n_voucher,
pit_bd_ficha_pdn.monto_organizacion,
(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org,
clar_atf_pdn.monto_1,
clar_atf_pdn.saldo_1,
clar_atf_pdn.monto_2,
clar_atf_pdn.saldo_2,
clar_atf_pdn.monto_3,
clar_atf_pdn.saldo_3,
clar_atf_pdn.monto_4,
clar_atf_pdn.saldo_4,
sys_bd_linea_pdn.descripcion AS linea,
sys_bd_tipo_org.descripcion AS tipo_org
FROM
clar_atf_pdn
INNER JOIN sys_bd_subactividad_poa AS poa_1 ON poa_1.cod = clar_atf_pdn.cod_poa_1 AND poa_1.cod = clar_atf_pdn.cod_poa_1
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN sys_bd_categoria_poa AS cat_1 ON cat_1.cod = poa_1.cod_categoria_poa
INNER JOIN sys_bd_subactividad_poa AS poa_2 ON poa_2.cod = clar_atf_pdn.cod_poa_2
INNER JOIN sys_bd_subactividad_poa AS poa_3 ON poa_3.cod = clar_atf_pdn.cod_poa_3
INNER JOIN sys_bd_subactividad_poa AS poa_4 ON poa_4.cod = clar_atf_pdn.cod_poa_4
INNER JOIN sys_bd_categoria_poa AS cat_2 ON cat_2.cod = poa_2.cod_categoria_poa
INNER JOIN sys_bd_categoria_poa AS cat_3 ON cat_3.cod = poa_4.cod_categoria_poa
INNER JOIN sys_bd_categoria_poa AS cat_4 ON cat_4.cod = poa_3.cod_categoria_poa
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE
pit_bd_ficha_pdn.cod_pit ='$cod2' AND
clar_atf_pdn.cod_tipo_atf_pdn = 1
ORDER BY
clar_atf_pdn.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r10=mysql_fetch_array($result))
{
	$total_pdn_1=$r10['monto_1']+$r10['monto_2']+$r10['monto_3']+$r10['monto_4'];
	
	$total_saldo_1=$r10['saldo_1']+$r10['saldo_2']+$r10['saldo_3']+$r10['saldo_4'];
?>

<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center">
<u>PRIMER DESEMBOLSO</u>
<br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r10['n_atf']);?> – PIT – <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><BR>
  PARA EL COFINANCIAMIENTO DEL PLAN DE NEGOCIO</div>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($total_pdn_1,2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Organización PIT </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización a transferir </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['nombre'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $r10['tipo_org'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Denominación del PDN </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Linea de Negocio </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['linea'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">Primer Desembolso </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fuente_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fuente_ro'],2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="42%" align="center">ACTIVIDADES</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Asistencia Técnica</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r10['monto_1'],2);?></td>
    <td align="center"><? echo $r10['poa_1'];?></td>
    <td align="center"><? echo $r10['categoria_1'];?></td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r10['monto_2'],2);?></td>
    <td align="center"><? echo $r10['poa_2'];?></td>
    <td align="center"><? echo $r10['categoria_2'];?></td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r10['monto_3'],2);?></td>
    <td align="center"><? echo $r10['poa_3'];?></td>
    <td align="center"><? echo $r10['categoria_3'];?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r10['monto_4'],2);?></td>
    <td align="center"><? echo $r10['poa_4'];?></td>
    <td align="center"><? echo $r10['categoria_4'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?  echo number_format($total_pdn_1,2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>


<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($total_saldo_1,2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right">30.00</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><? echo $r10['n_voucher'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($r10['monto_organizacion'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? 
	if ($r10['monto_organizacion']>$r10['aporte_org'])
	{
	$saldo_pdn=0;
	}
	else
	{
	$saldo_pdn=$r10['aporte_org']-$r10['monto_organizacion'];
	}
	
	echo number_format($saldo_pdn,2);
	?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	if ($r10['monto_organizacion']>$r10['aporte_org'])
	{
	$pp_saldo_pdn=0;
	}
	else
	{
	@$pp_saldo_pdn=$r10['monto_organizacion']/$r10['aporte_org']*100;
	}
	echo number_format(@$pp_saldo_pdn,2);
	?>
	</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia de la Ficha de Inscripción en la SUNARP </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNIs de los directivos de la Organización responsable del PDN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo para trabajar con SIERRA SUR II y aportes de cofinanciamiento de la Organización responsable del PDN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>PDN aprobado por el CLAR y suscrito por las partes </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del Voucher de Depósito del Aporte de La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_contrato']);?></div>
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
?>
</p>

<?
$sql="SELECT
clar_atf_mrn.n_atf,
poa1.codigo AS cod_poa_1,
clar_atf_mrn.desembolso_1,
clar_atf_mrn.saldo_1,
poa2.codigo AS cod_poa_2,
clar_atf_mrn.desembolso_2,
clar_atf_mrn.saldo_2,
poa3.codigo AS cod_poa_3,
clar_atf_mrn.desembolso_3,
clar_atf_mrn.saldo_3,
poa4.codigo AS cod_poa_4,
clar_atf_mrn.desembolso_4,
clar_atf_mrn.saldo_4,
org_ficha_organizacion.nombre,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
cat1.codigo AS categoria_1,
cat2.codigo AS categoria_2,
cat3.codigo AS categoria_3,
cat4.codigo AS categoria_4,
pit_bd_ficha_mrn.lema,
pit_bd_ficha_mrn.n_voucher,
pit_bd_ficha_mrn.monto_organizacion,
(pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org) AS aporte_org,
sys_bd_tipo_org.descripcion AS tipo_org
FROM
clar_atf_mrn
INNER JOIN sys_bd_subactividad_poa AS poa1 ON poa1.cod = clar_atf_mrn.cod_poa_1
INNER JOIN sys_bd_subactividad_poa AS poa2 ON poa2.cod = clar_atf_mrn.cod_poa_2
INNER JOIN sys_bd_subactividad_poa AS poa3 ON poa3.cod = clar_atf_mrn.cod_poa_3
INNER JOIN sys_bd_subactividad_poa AS poa4 ON poa4.cod = clar_atf_mrn.cod_poa_4
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
INNER JOIN sys_bd_categoria_poa AS cat1 ON cat1.cod = poa1.cod_categoria_poa
INNER JOIN sys_bd_categoria_poa AS cat2 ON cat2.cod = poa2.cod_categoria_poa
INNER JOIN sys_bd_categoria_poa AS cat3 ON cat3.cod = poa3.cod_categoria_poa
INNER JOIN sys_bd_categoria_poa AS cat4 ON cat4.cod = poa4.cod_categoria_poa
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE
pit_bd_ficha_mrn.cod_pit ='$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
while($r11=mysql_fetch_array($result))
{
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center">
<u>PRIMER DESEMBOLSO</u>
<br/>
AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r11['n_atf']);?> – PIT – <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><BR>
  PARA EL COFINANCIAMIENTO DEL PLAN DE GESTION DE RECURSOS NATURALES</div>

<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($r11['desembolso_1']+$r11['desembolso_2']+$r11['desembolso_3']+$r11['desembolso_4'],2);?></td>
  </tr>
</table>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>






<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Organización PIT</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización a transferir</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['nombre'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Tipo de Organización</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><?php  echo $r11['tipo_org'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Lema del PGRN</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['lema'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">PRIMER DESEMBOLSO</td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r11['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de financiamiento</td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fuente_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fuente_ro'],2);?></td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="41%" align="center">ACTIVIDADES</td>
    <td width="8%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Premio para Concursos Inter Familiares</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r11['desembolso_1'],2);?></td>
    <td align="center"><? echo $r11['cod_poa_1'];?></td>
    <td align="center"><? echo $r11['categoria_1'];?></td>
  </tr>
  <tr>
    <td>Asistencia Técnica (de campesino a campesino)</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r11['desembolso_2'],2);?></td>
    <td align="center"><? echo $r11['cod_poa_2'];?></td>
    <td align="center"><? echo $r11['categoria_2'];?></td>
  </tr>
  <tr>
    <td>Visitas guiada</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r11['desembolso_3'],2);?></td>
    <td align="center"><? echo $r11['cod_poa_3'];?></td>
    <td align="center"><? echo $r11['categoria_3'];?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PGRN</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($r11['desembolso_4'],2);?></td>
    <td align="center"><? echo $r11['cod_poa_4'];?></td>
    <td align="center"><? echo $r11['categoria_4'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($r11['desembolso_1']+$r11['desembolso_2']+$r11['desembolso_3']+$r11['desembolso_4'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($r11['saldo_1']+$r11['saldo_2']+$r11['saldo_3']+$r11['saldo_4'],2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right">30.00</td>
  </tr>
</table>
<br>
<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><? echo $r11['n_voucher'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($r11['monto_organizacion'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? 
	if ($r11['monto_organizacion']>$r11['aporte_org'])
	{
	$saldo_mrn=0;
	}
	else
	{
	$saldo_mrn=$r11['aporte_org']-$r11['monto_organizacion'];
	}
	echo number_format($saldo_mrn,2);
	?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	if ($r11['monto_organizacion']>$r11['aporte_org'])
	{
	$pp_saldo_mrn=0;
	}
	else
	{
	@$pp_saldo_mrn=$r11['monto_organizacion']/$r11['aporte_org']*100;
	}
	echo number_format(@$pp_saldo_mrn,2);
	?>
	</td>
  </tr>
</table>





<br>
<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>




<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia de la Ficha de Inscripción en la SUNARP </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNIs de los directivos de la Organización responsable del PGRN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo para trabajar con SIERRA SUR II y aportes de cofinanciamiento de la Organización responsable del PGRN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>PGRN aprobado por el CLAR y suscrito por las partes </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y la Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del Voucher de Depósito de Aporte de LA ORGANIZACION </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_contrato']);?></div>
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
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso de Iniciativas de Plan de Inversión Teritorial </td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre_pit'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_contrato']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
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
<?
$sql="SELECT
clar_atf_pit.n_atf,
clar_atf_pit.monto_desembolsado,
pit_bd_ficha_pit.n_cuenta,
org_ficha_taz.nombre,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
sys_bd_ifi.descripcion AS banco
FROM
clar_atf_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit.cod_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
WHERE
pit_bd_ficha_pit.cod_pit = '$cod2' AND
pit_bd_ficha_pit.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
while($u1=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><? echo $u1['nombre'];?></td>
    <td class="centrado"><? echo $u1['tipo_ini'];?></td>
    <td class="centrado"><? echo numeracion($u1['n_atf'])." - ".periodo($row['f_contrato']);?></td>
    <td><? echo $u1['banco'];?></td>
    <td class="centrado"><? echo $u1['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($u1['monto_desembolsado'],2);?></td>
  </tr>
<?
}
?>  
<?
$sql="SELECT
clar_atf_mrn.n_atf,
(clar_atf_mrn.desembolso_1+
clar_atf_mrn.desembolso_2+
clar_atf_mrn.desembolso_3+
clar_atf_mrn.desembolso_4) as monto_desembolsado,
org_ficha_organizacion.nombre,
sys_bd_tipo_iniciativa.descripcion as tipo_ini,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion as banco
FROM
clar_atf_mrn
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE
pit_bd_ficha_mrn.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003
ORDER BY
pit_bd_ficha_mrn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
while($u2=mysql_fetch_array($result))
{
?>
  <tr>
    <td><? echo $u2['nombre'];?></td>
    <td class="centrado"><? echo $u2['tipo_ini'];?></td>
    <td class="centrado"><? echo numeracion($u2['n_atf'])." - ".periodo($row['f_contrato']);?></td>
    <td><? echo $u2['banco'];?></td>
    <td class="centrado"><? echo $u2['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($u2['monto_desembolsado'],2);?></td>
  </tr>
<?
}
?>
<?
$sql="SELECT
clar_atf_pdn.n_atf,
(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
org_ficha_organizacion.nombre
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE
pit_bd_ficha_pdn.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
clar_atf_pdn.cod_tipo_atf_pdn = 1
ORDER BY
pit_bd_ficha_pdn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
while($u3=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><? echo $u3['nombre'];?></td>
    <td class="centrado"><? echo $u3['tipo_ini'];?></td>
    <td class="centrado"><? echo numeracion($u3['n_atf'])." - ".periodo($row['f_contrato']);?></td>
    <td><? echo $u3['banco'];?></td>
    <td class="centrado"><? echo $u3['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($u3['monto_desembolsado'],2);?></td>
  </tr>
<?
}


//Calculo los montos
//1.- Monto animador
$sql="SELECT SUM(clar_atf_pit.monto_desembolsado) AS monto_desembolsado
FROM clar_atf_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit.cod_pit
WHERE pit_bd_ficha_pit.cod_pit = '$cod' AND
pit_bd_ficha_pit.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
$r12=mysql_fetch_array($result);

//2.- Monto PGRN
$sql="SELECT SUM(clar_atf_mrn.desembolso_1+
clar_atf_mrn.desembolso_2+
clar_atf_mrn.desembolso_3+
clar_atf_mrn.desembolso_4) AS monto_desembolsado
FROM clar_atf_mrn INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit = '$cod' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003
ORDER BY pit_bd_ficha_mrn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
$r13=mysql_fetch_array($result);

//3.- Monto PDN
$sql="SELECT SUM(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit = '$cod' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
clar_atf_pdn.cod_tipo_atf_pdn = 1
ORDER BY pit_bd_ficha_pdn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
$r14=mysql_fetch_array($result);




?>  
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha">
    <?
	$pdss70=$r12['monto_desembolsado']+$r13['monto_desembolsado']+$r14['monto_desembolsado'];
	echo number_format($pdss70,2);
	?></td>
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
<H1 class=SaltoDePagina></H1>
<!-- Generamos los recibos de recepcion de cheque -->
<?php 
$sql="SELECT
clar_atf_pit.n_atf,
clar_atf_pit.monto_desembolsado,
pit_bd_ficha_pit.n_cuenta,
org_ficha_taz.nombre AS org,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
sys_bd_ifi.descripcion AS banco,
org_ficha_taz.n_documento,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_directiva_taz.n_documento AS dni,
org_ficha_directiva_taz.nombre,
org_ficha_directiva_taz.paterno,
org_ficha_directiva_taz.materno
FROM
clar_atf_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_atf_pit.cod_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.n_documento = org_ficha_taz.presidente AND org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE
pit_bd_ficha_pit.cod_pit ='$cod2' AND
pit_bd_ficha_pit.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
while($fila1=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php echo $fila1['org'];?> con <?php  echo $fila1['tipo_doc'];?> N° <?php  echo $fila1['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila1['nombre']." ".$fila1['paterno']." ".$fila1['materno'];?>, identificado con DNI N° <?php  echo $fila1['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila1['monto_desembolsado'],2);?>(<?php  echo vuelveletra($fila1['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>, Relacionado con el Primer Desembolso, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>. 
</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_contrato']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>
<!--  Aqui ponemos los planes de negocio -->
<?php 
$sql="SELECT
clar_atf_pdn.n_atf,
(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
org_ficha_organizacion.nombre AS org,
org_ficha_organizacion.n_documento,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_usuario.n_documento AS dni,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pdn.cod_pit = '$cod2' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
clar_atf_pdn.cod_tipo_atf_pdn = 1
ORDER BY
pit_bd_ficha_pdn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
 <?php  echo $fila2['org'];?> con <?php  echo $fila2['tipo_doc'];?> N° <?php  echo $fila2['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila2['nombre']." ".$fila2['paterno']." ".$fila2['materno'];?>, identificado con DNI N° <?php  echo $fila2['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila2['monto_desembolsado'],2);?> (<?php  echo vuelveletra($fila2['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>
, Relacionado con el Primer Desembolso, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_contrato']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>
<!--  Aca va el PGRN -->
<?php 
$sql="SELECT
clar_atf_mrn.n_atf,
(clar_atf_mrn.desembolso_1+
clar_atf_mrn.desembolso_2+
clar_atf_mrn.desembolso_3+
clar_atf_mrn.desembolso_4) AS monto_desembolsado,
org_ficha_organizacion.nombre AS org,
sys_bd_tipo_iniciativa.descripcion AS tipo_ini,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
org_ficha_organizacion.n_documento,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_usuario.n_documento AS dni,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno
FROM
clar_atf_mrn
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN org_ficha_usuario ON org_ficha_usuario.n_documento = org_ficha_organizacion.presidente AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_pit = '$cod2' AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003
ORDER BY
pit_bd_ficha_mrn.cod_ifi ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $fila3['org'];?> con <?php  echo $fila3['tipo_doc'];?> N° <?php  echo $fila3['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila3['nombre']." ".$fila3['paterno']." ".$fila3['materno'];?>, identificado con DNI N° <?php  echo $fila3['dni'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila3['monto_desembolsado'],2);?> (<?php  echo vuelveletra($fila3['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>, Relacionado con el Primer Desembolso, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_contrato']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>


<?php 
}
?>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>    
    
    </td>
  </tr>
</table>
</body>
</html>