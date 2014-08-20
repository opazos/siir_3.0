<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$org="<strong>LA ORGANIZACIÓN</strong>";
$orga="<strong>ORGANIZACION ADICIONADA</strong>";
$proyecto ="<strong>SIERRA SUR II</strong>";
$pit="<strong>EL PIT</strong>";

//1. Busco la data del PIT
$sql="SELECT clar_ampliacion_pit.n_ampliacion, 
	clar_ampliacion_pit.cod_pit, 
	clar_ampliacion_pit.f_ampliacion, 
	clar_ampliacion_pit.n_solicitud, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_taz.tesorero, 
	tesorero.nombre AS nombre_1, 
	tesorero.paterno AS paterno_1, 
	tesorero.materno AS materno_1, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_dependencia.cod_dependencia, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	org_ficha_taz.sector, 
	clar_ampliacion_pit.cod_clar, 
	pit_bd_ficha_pit.f_termino, 
	clar_ampliacion_pit.fte_fida, 
	clar_ampliacion_pit.fte_ro
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN org_ficha_directiva_taz presidente ON org_ficha_taz.presidente = presidente.n_documento
	 INNER JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 LEFT OUTER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
WHERE clar_ampliacion_pit.cod_ampliacion = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Obtenemos el nombre de la ampliacion
$ampliacion=$row['n_ampliacion'];

if ($ampliacion==1)
{
$encabezado="PRIMERA AMPLIACIÓN DEL CONTRATO";
}
elseif($ampliacion==2)
{
$encabezado="SEGUNDA AMPLIACIÓN DEL CONTRATO";
}
elseif($ampliacion==3)
{
$encabezado="TERCERA AMPLIACIÓN DEL CONTRATO";
}
elseif($ampliacion==4)
{
	$encabezado="CUARTA AMPLIACIÓN DEL CONTRATO";
}
elseif($ampliacion==5)
{
	$encabezado="QUINTA AMPLIACIÓN DEL CONTRATO";
}





//Obtengo el dato del Jefe de la Oficina Local
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


$sql="SELECT
clar_bd_evento_clar.cod_clar,
clar_bd_evento_clar.f_evento,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_acta.n_acta,
clar_bd_evento_clar.lugar,
clar_bd_evento_clar.nombre AS clar
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
LEFT JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
WHERE
clar_bd_evento_clar.cod_clar='".$row['cod_clar']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);




//Ubicamos los Montos para PGRN y PDN
$sql="SELECT
pit_bd_ficha_pdn.cod_pit,
Sum((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
Sum((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_relacionador = '$cod' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003
GROUP BY
pit_bd_ficha_pdn.cod_pit";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);


//Ubicamos los montos de PGRN del evento CLAR
$sql="SELECT Sum((pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+pit_bd_ficha_mrn.ag_pdss)) AS aporte_pdss, 
	Sum((pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org)) AS aporte_org
FROM pit_bd_ficha_mrn INNER JOIN clar_ampliacion_pit ON pit_bd_ficha_mrn.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE clar_ampliacion_pit.cod_ampliacion = '$cod' AND
pit_bd_ficha_mrn.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//Ubicamos los montos de PDN del evento CLAR
$sql="SELECT Sum((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss, 
	Sum((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_ampliacion_pit.cod_ampliacion = '$cod' AND
clar_ampliacion_pit.cod_pit='".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

$aporte_pdss=$r5['aporte_pdss']+$r6['aporte_pdss']+$row['aporte_pdss'];
$aporte_org=$r5['aporte_org']+$r6['aporte_org']+$row['aporte_org'];

//N° de MRN en el anterior contrato
$sql="SELECT
Count(clar_atf_mrn.cod_atf_mrn) AS mrn
FROM
clar_atf_pit
INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_atf_pit = clar_atf_pit.cod_atf_pit
INNER JOIN clar_ampliacion_pit ON clar_atf_pit.cod_pit = clar_ampliacion_pit.cod_pit
WHERE
clar_ampliacion_pit.cod_ampliacion = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

//N° DE PDN en el anterior contrato
$sql="SELECT
Count(clar_atf_pdn.cod_atf_pdn) AS pdn
FROM
clar_atf_pit
INNER JOIN clar_ampliacion_pit ON clar_atf_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_atf_pit.cod_atf_pit
WHERE
clar_ampliacion_pit.cod_ampliacion = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r8=mysql_fetch_array($result);


//Verificamos que esta ampliación tenga adenda
$sql="SELECT pit_bd_ficha_adenda.cod_adenda, 
	pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);
$total_adenda=mysql_num_rows($result);


if($total_adenda<>0)
{
	$f_final=$r9['f_termino'];
}
else
{
	$f_final=$row['f_termino'];
}


//Funcion para sumar 15 meses a la fecha
$fecha_db = $row['f_contrato'];
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1]+3,$fecha_db[2]-1,$fecha_db[0]+1);
$fecha = date("Y-m-d", $fecha_cambiada);

$fecha_termino_pit = $fecha;
$fecha_termino_pdn = $row['f_ampliacion'];

if ($fecha_termino_pit>$fecha_termino_pdn)
{
	$fecha_ampliacion=$fecha_termino_pit;
}
else
{
	$fecha_ampliacion=$fecha_termino_pdn;
}


//Obtengo el numero de iniciativas que corresponden a esta ampliacion
$sql="SELECT
Count(clar_atf_pdn.cod_atf_pdn) AS planes
FROM
clar_ampliacion_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.cod_ampliacion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$u4=mysql_fetch_array($result);

$plan=$u4['planes'];


$numero_ampliacion=$row['n_ampliacion']-1;

//calculo el monto total de las ampliaciones
$sql="SELECT
Sum((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
Sum((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r9=mysql_fetch_array($result);


//Calculo de Montos de Ampliaciones
//1.- Monto Primera Ampliacion
$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
SUM((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
SUM((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.n_ampliacion = 1";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);

//2.- Monto Segunda Ampliacion
$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
SUM((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
SUM((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.n_ampliacion = 2";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);

//3.- Monto Tercera Ampliación
$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
SUM((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
SUM((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.n_ampliacion = 3";
$result=mysql_query($sql) or die (mysql_error());
$i3=mysql_fetch_array($result);

//4.- Monto Cuarta Ampliación
$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
SUM((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
SUM((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['COD_PIT']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.n_ampliacion = 4";
$result=mysql_query($sql) or die (mysql_error());
$i4=mysql_fetch_array($result);

//1.- Monto Quinta Ampliacion
$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
SUM((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
SUM((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.n_ampliacion = 5";
$result=mysql_query($sql) or die (mysql_error());
$i5=mysql_fetch_array($result);

// Ahora las condiciones
$ampliaciones=$row['n_ampliacion'];
if($ampliaciones==2)
{
	$aporte_proyecto=$i1['aporte_pdss']+$i2['aporte_pdss'];
	$aporte_organizacion=$i1['aporte_org']+$i2['aporte_org'];
}
elseif($ampliaciones==3)
{
	$aporte_proyecto=$i1['aporte_pdss']+$i2['aporte_pdss']+$i3['aporte_pdss'];
	$aporte_organizacion=$i1['aporte_org']+$i2['aporte_org']+$i3['aporte_org'];
}
elseif($ampliaciones==4)
{
	$aporte_proyecto=$i1['aporte_pdss']+$i2['aporte_pdss']+$i3['aporte_pdss']+$i4['aporte_pdss'];
	$aporte_organizacion=$i1['aporte_org']+$i2['aporte_org']+$i3['aporte_org']+$i4['aporte_org'];
}
elseif($ampliaciones==5)
{
	$aporte_proyecto=$i1['aporte_pdss']+$i2['aporte_pdss']+$i3['aporte_pdss']+$i4['aporte_pdss']+$i5['aporte_pdss'];
	$aporte_organizacion=$i1['aporte_org']+$i2['aporte_org']+$i3['aporte_org']+$i4['aporte_org']+$i5['aporte_org'];
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
<div class="capa txt_titulo" align="center"><? echo $encabezado;?> Nº <?php  echo numeracion($row['n_contrato']);?> - <?php  echo periodo($row['f_contrato']);?> - PIT-  OL <?php  echo $row['oficina'];?> <br>DE DONACIÓN SUJETO A CARGO PARA LA EJECUCION DEL PLAN DE INVERSIÓN TERRITORIAL  DE LA ORGANIZACIÓN: "<?php  echo $row['organizacion'];?>" </div>

<br>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado">
    <td colspan="2">
	Conste por el presente documento la ampliación del Contrato de donación sujeto a Cargo N° 
        <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?> 
que celebran, de una parte <strong>EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II</strong>, con RUC Nº 20456188118, en adelante denominado “<? echo $proyecto;?>”, representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, 
<?php  echo $r1['nombre']." ".$r1['apellido'];?>, con DNI. Nº 
<?php  echo $r1['n_documento'];?>
, con domicilio legal en 
<?php  echo $row['direccion'];?>
, del distrito de
<?php  echo $row['dist'];?>
de la Provincia de
<?php  echo $row['prov'];?>
y Departamento de
<?php  echo $row['dep'];?>
; y de otra parte la organización: 
<?php  echo $row['organizacion'];?> 
con 
<?php  echo $row['tipo_doc'];?> 
N° 
<?php  echo $row['n_documento'];?>
, con domicilio legal en 
<?php  echo $row['sector'];?>
, ubicada en el Distrito de 
<?php  echo $row['distrito'];?>
, Provincia de 
<?php  echo $row['provincia'];?>
, Departamento de 
<?php  echo $row['departamento'];?>
, en adelante denominada “<? echo $org;?>”, representada por su Presidente(a), 
<?php  echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?> 
con DNI. N° 
<?php  echo $row['presidente'];?>  
y su Tesorero(a), 
<?php  echo $row['nombre_1']." ".$row['paterno_1']." ".$row['materno_1'];?> 
con DNI. N° 
<?php  echo $row['tesorero'];?>
. <? if ($plan==1) echo "Interviene"; else echo "Intervienen";?>  también en el presente contrato <? if ($plan==1) echo "la"; else echo "las";?>  <? if ($plan==1) echo "siguiente"; else echo "siguientes";?> <? if ($plan==1) echo "Organización"; else echo "Organizaciones";?>, <? if ($plan==1) echo "ubicada"; else echo "ubicadas";?> en el territorio de influencia socioeconómica de “LA ORGANIZACIÓN”, <? if ($plan<>1) echo "cada una de las cuales es";?> en adelante  denominada “ORGANIZACIÓN ADICIONADA”, y específicamente <? if ($plan==1) echo "es"; else echo "son";?>:    </td>
  </tr>
 
  <tr class="justificado">
    <td colspan="2">
  <?php 
$sql="SELECT DISTINCT
clar_atf_pdn.cod_atf_pdn,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre_1,
tesorero.paterno AS paterno_1,
tesorero.materno AS materno_1
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_relacionador = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
echo "<ul>";
echo "<li>";
echo $r2['organizacion'].", con ".$r2['tipo_doc']." N° ".$r2['n_documento'].", representada por su Presidente(a) ".$r2['nombre']." ".$r2['paterno']." ".$r2['materno']." con DNI N° ".$r2['presidente']." y Tesorero(a) ".$r2['nombre_1']." ".$r2['paterno_1']." ".$r2['materno_1']." con DNI N° ".$r2['tesorero'];
echo "</li>";
echo "</ul>";
}
?>   
    
    </td>
  </tr>

  <tr class="justificado">
    <td colspan="2">Los representantes de “<? echo $org;?>” y de <? if ($plan==1) echo "la"; else echo "cada";?> “<? echo $orga;?>” se constituyen como responsables solidarios de este Contrato, el cual se suscribe en los términos y condiciones establecidas en las cláusulas siguientes:</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA PRIMERA: ANTECEDENTES.</td>
  </tr>
  <tr class="justificado">
    <td width="5%" valign="top">1.1</td>
    <td width="95%">Con fecha <?php  echo traducefecha($row['f_contrato']);?>  “<? echo $proyecto;?>”  y “<? echo $org;?>”, suscribieron el Contrato N° <?php  echo numeracion($row['n_contrato']);?> - <?php  echo periodo($row['f_contrato']);?> - PIT-  OL <?php  echo $row['oficina'];?> con la intervención de tres  Organizaciones integrantes de  "<? echo $pit;?>" para la ejecución de  <?php  echo litera($r7['mrn']);?> (<?php  echo $r7['mrn'];?>) PGRN y <?php  echo litera($r8['pdn']);?> (<?php  echo $r8['pdn'];?>) PDN.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.2</td>
    <td width="95%">Previa solicitud expresa de “<? echo $org;?>”, el Comité Local de Asignación de Recursos - CLAR de la Oficina Local de <?php  echo $row['oficina'];?> realizado el <?php  echo traducefecha($r3['f_evento']);?>, en la localidad de <?php  echo $r3['distrito'];?>, ha seleccionado mediante concurso <? if ($plan==1) echo "a la"; else echo "cada";?>  “<? echo $orga;?>” para la ejecución de la<? if ($plan<>1) echo "s";?> iniciativa<? if ($plan<>1) echo "s";?> detallada<? if ($plan<>1) echo "s";?> en el cuadro inserto en la  Cláusula Segunda, conforme consta en el Acta N° <?php  echo numeracion($r3['n_acta']);?> del CLAR de la mencionada Oficina Local</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA SEGUNDA: OBJETO DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Mediante  el presente contrato ampliatorio “<? echo $proyecto;?>” transfiere el monto adicional de S/. <?php  echo number_format($r4['aporte_pdss'],2);?> (<?php  echo vuelveletra($r4['aporte_pdss']);?> Nuevos Soles), para la ejecución de  la<? if ($plan<>1) echo "s";?>  iniciativa<? if ($plan<>1) echo "s";?> correspondiente<? if ($plan<>1) echo "s";?> <? if ($plan==1) echo "a la"; else echo "cada";?> “<? echo $orga;?>”, conforme a lo que se especifica en el cuadro inserto en la presente Cláusula.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Corresponde <? if ($plan==1) echo "a la"; else echo "cada";?> “<? echo $orga;?>” asumir el aporte de cofinanciamiento por el valor de S/. <?php  echo number_format($r4['aporte_org'],2);?> (<?php  echo vuelveletra($r4['aporte_org']);?> Nuevos Soles).</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Ambos montos serán destinados  a financiar la ejecución de la<? if ($plan<>1) echo "s";?> iniciativa<? if ($plan<>1) echo "s";?> que corresponde<? if ($plan<>1) echo "n";?> <? if ($plan==1) echo "a la"; else echo "cada";?> “<? echo $orga;?>”, la<? if ($plan<>1) echo "s";?> misma<? if ($plan<>1) echo "s";?> que se incorpora<? if ($plan<>1) echo "n";?> a "<? echo $pit;?>" de “<? echo $org;?>”, según lo que fue establecido en el Contrato N° 
      <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?>
. Los aportes otorgados por las partes se  detallan  en el siguiente cuadro:</td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="58%">INICIATIVAS ADICIONADAS AL PLAN DE INVERSION TERRITORIAL</td>
    <td width="15%">APORTE "<? echo $proyecto;?>"
(S/.)</td>
    <td width="14%">APORTES “ORGs.”
(S/.)</td>
    <td width="13%">TOTAL (S/.) </td>
  </tr>
<?php 
$num=0;
$sql="SELECT
pit_bd_ficha_pdn.cod_pdn,
(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss,
(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org,
pit_bd_ficha_pdn.denominacion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_relacionador = '$cod' AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$monto_org=$f1['aporte_org'];
	$total_monto_org=$total_monto_org+$monto_org;
	
	$monto_pdss=$f1['aporte_pdss'];
	$total_monto_pdss=$total_monto_pdss+$monto_pdss;
	
	
	$num++
?>  
  <tr>
    <td>PDN: (<?php  echo $f1['denominacion'];?>) - <?php  echo $f1['nombre'];?>, según las especificaciones del Anexo N° <?php  echo $num;?></td>
    <td class="derecha"><?php  echo number_format($f1['aporte_pdss'],2);?></td>
    <td class="derecha"><?php  echo number_format($f1['aporte_org'],2);?></td>
    <td class="derecha"><?php echo number_format($f1['aporte_pdss']+$f1['aporte_org'],2);?></td>
  </tr>
<?php 
}
?>  
  <tr class="txt_titulo">
    <td class="centrado">TOTAL</td>
    <td class="derecha"><?php  echo number_format($total_monto_pdss,2);?></td>
    <td class="derecha"><?php  echo number_format($total_monto_org,2);?></td>
    <td class="derecha"><?php  $total_sol=$total_monto_pdss+$total_monto_org; echo number_format($total_sol,2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td class="centrado">%</td>
    <td class="derecha"><?php  echo number_format($total_monto_pdss/$total_sol*100,2);?></td>
    <td class="derecha"><?php  echo number_format($total_monto_org/$total_sol*100,2);?></td>
    <td class="derecha">100.00</td>
  </tr>
</table>
<br>
<div class="capa justificado">En consecuencia los nuevos montos del contrato N° 
      <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?> 
quedan establecidos en  el cuadro siguiente.</div>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="58%">INICIATIVAS ADICIONADAS AL PLAN DE INVERSION TERRITORIAL</td>
    <td width="15%">APORTE "<? echo $proyecto;?>"
      (S/.)</td>
    <td width="14%">APORTES "<? echo $orga;?>"
      (S/.)</td>
    <td width="13%">TOTAL (S/.) </td>
  </tr>
  <tr>
    <td>Contrato N° 
      <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?></td>
    <td class="derecha"><?php  echo number_format($aporte_pdss,2);?></td>
    <td class="derecha"><?php  echo number_format($aporte_org,2);?></td>
    <td class="derecha"><?php  echo number_format($aporte_pdss+$aporte_org,2);?></td>
  </tr>
<?
//Importante: calculo de monto acumulable para ampliaciones

$sql="SELECT
clar_ampliacion_pit.n_ampliacion,
Sum((pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss,
Sum((pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org)) AS aporte_org
FROM
clar_atf_pdn
INNER JOIN clar_ampliacion_pit ON clar_ampliacion_pit.cod_ampliacion = clar_atf_pdn.cod_relacionador
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
pit_bd_ficha_pit.cod_pit='".$row['cod_pit']."'
GROUP BY
clar_ampliacion_pit.n_ampliacion
ORDER BY
clar_ampliacion_pit.n_ampliacion ASC
LIMIT 0, $numero_ampliacion";

$result=mysql_query($sql) or die (mysql_error());
$t_am=mysql_num_rows($result);
while($f2=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><?php if ($f2['n_ampliacion']==1) echo "Primera"; elseif($f2['n_ampliacion']==2) echo "Segunda"; elseif($f2['n_ampliacion']==3) echo "Tercera"; elseif($f2['n_ampliacion']==4) echo "Cuarta"; elseif($f2['n_ampliacion']==5) echo "Quinta";?> ampliación del contrato</td>
    <td class="derecha"><?php echo number_format($f2['aporte_pdss'],2);?></td>
    <td class="derecha"><?php  echo number_format($f2['aporte_org'],2);?></td>
    <td class="derecha"><?php  echo number_format($f2['aporte_pdss']+$f2['aporte_org'],2);?></td>
  </tr>
<?
}
?>  
  <tr>
    <td>Monto de la presente ampliación de contrato</td>
    <td class="derecha"><?php  echo number_format($r4['aporte_pdss'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['aporte_org'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['aporte_pdss']+$r4['aporte_org'],2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td class="centrado">TOTAL</td>
    <td class="derecha">
    <?php 
    if($t_am==0)
    {
    	$total_pdss=$aporte_pdss+$r4['aporte_pdss'];
    }
    else 
    {
    $total_pdss=$aporte_pdss+$aporte_proyecto;
    }
    echo number_format($total_pdss,2);
    
    ?></td>
    <td class="derecha">
    <?php
    if($t_am==0)
    {
    	$total_org=$aporte_org+$r4['aporte_org'];
    } 
    else 
    {
    $total_org=$aporte_org+$aporte_organizacion;
    }
    echo number_format($total_org,2);
    ?></td>
    <td class="derecha"><?php $total_contrato=$total_pdss+$total_org; echo number_format($total_contrato,2);?></td>
  </tr>
  <tr class="txt_titulo">
    <td class="centrado">%</td>
    <td class="derecha"><?php  echo number_format($total_pdss/$total_contrato*100,2);?></td>
    <td class="derecha"><?php  echo number_format($total_org/$total_contrato*100,2);?></td>
    <td class="derecha">100.00</td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado txt_titulo">
    <td>CLAUSULA TERCERA: PLAZO DEL CONTRATO</td>
  </tr>
  <tr class="justificado">
    <td>El  plazo se inicia con la suscripción del presente contrato y su  vencimiento correspondiente a <? if ($plan==1) echo "la"; else echo "cada";?> “<? echo $orga;?>”, será el que se especifica en el respectivo anexo. Este  plazo incluye  las acciones de liquidación del Contrato y perfeccionamiento de la donación de  <? if ($plan==1) echo "la"; else echo "cada";?> “<? echo $orga;?>”, que serán consideradas como acciones  parciales.  
En consecuencia, el  plazo de vencimiento del Contrato N° 
      <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?>  
es hasta  el <?php  echo traducefecha($f_final);?></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td>CLAUSULA CUARTA: DE LAS DEMAS CLAUSULAS DEL CONTRATO</td>
  </tr>
  <tr class="justificado">
    <td>Para la ejecución del presente Contrato, se establece<? if ($plan==1) echo "a la"; else echo "a cada";?> “<? echo $orga;?>” la aplicación de las demás cláusulas establecidas en el Contrato N° 
      <?php  echo numeracion($row['n_contrato']);?>
-
<?php  echo periodo($row['f_contrato']);?>
- PIT-  OL
<?php  echo $row['oficina'];?>.</td>
  </tr>
  <tr class="justificado">
     <td>En fe de lo acordado, suscribimos la presente ampliación en tres ejemplares, en la localidad de <? echo $row['dist'];?>, el <? echo traducefecha($row['f_ampliacion']);?>. </td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="34%" class="centrado">______________________________</td>
    <td width="33%">&nbsp;</td>
    <td width="33%" class="centrado">______________________________</td>
  </tr>
  <tr>
    <td class="centrado mini"><?php  echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><br><? echo $row['organizacion'];?><br>
    <strong>PRESIDENTE <? echo $org;?></strong></td>
    <td>&nbsp;</td>
    <td class="centrado mini"><?php  echo $row['nombre_1']." ".$row['paterno_1']." ".$row['materno_1'];?><br><? echo $row['organizacion'];?><br>
   <strong>TESORERO <? echo $org;?></strong></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?
$sql="SELECT
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre_1,
tesorero.paterno AS paterno_1,
tesorero.materno AS materno_1
FROM
clar_atf_pdn
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN org_ficha_usuario AS presidente ON org_ficha_organizacion.presidente = presidente.n_documento AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_relacionador = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="34%" class="centrado">______________________________</td>
    <td width="33%">&nbsp;</td>
    <td width="33%" class="centrado">______________________________</td>
  </tr>
  <tr>
    <td class="centrado mini"><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?><br>
	<? echo $f3['organizacion'];?><br>
        <strong>PRESIDENTE <? echo $orga;?></strong></td>
    <td>&nbsp;</td>
    <td class="centrado mini"><? echo $f3['nombre_1']." ".$f3['paterno_1']." ".$f3['materno_1'];?><br>
	<? echo $f3['organizacion'];?><br>
        <strong>TESORERO <? echo $orga;?></strong></td>
  </tr>
</table>

  <?
}
?>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="30%" align="center">&nbsp;</td>
    <td width="40%" align="center">______________________________</td>
    <td width="30%" align="center">&nbsp;</td>
  </tr>
  <tr class="mini">
    <td align="center">&nbsp;</td>
    <td align="center"><?php  echo $r1['nombre']." ".$r1['apellido'];?>
      <BR>
    <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?><br>SIERRA SUR II</B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<?
$num3=0;

$sql="SELECT pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.f_termino, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.fer_org, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	pit_bd_ficha_pdn.mes, 
	clar_ampliacion_pit.f_ampliacion
FROM clar_ampliacion_pit INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.cod_ampliacion='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f10=mysql_fetch_array($result))
{
$num3++
?>

<H1 class=SaltoDePagina> </H1>
<? 
$total_pdn_pdss=$f10['total_apoyo']+$f10['at_pdss']+$f10['vg_pdss']+$f10['fer_pdss'];
$total_pdn_org=$f10['at_org']+$f10['vg_org']+$f10['fer_org'];
$total_pdn=$total_pdn_pdss+$total_pdn_org;
?>
<? include("encabezado.php");?>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° <? echo $num3;?></u></td>
  </tr>
  <tr class="centrado txt_titulo">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Negocio </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PIT :</strong> <? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PDN :</strong> <? echo $f10['organizacion'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre del Plan de Negocio :</strong> <? echo $f10['denominacion'];?></td>
  </tr>
  <tr>
  <td><strong>Referencia :</strong>CONTRATO Nº <?php  echo numeracion($row['n_contrato']);?> - <?php  echo periodo($row['f_contrato']);?> - PIT-  OL <?php  echo $row['oficina'];?> con fecha <?php  echo traducefecha($row['f_contrato']);?></td>
  </tr>
  <tr>
    <td><strong>Plazo de ejecución :</strong> Hasta <? echo $f10['mes'];?> meses</td>
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
    <td align="right"><? 
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
    <td align="right"><?
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
    <td align="center"><? echo $r1['nombre']." ".$r1['apellido'];?><BR>
        <B>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></B></td>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<?
}
?>

<?
$sql="SELECT
clar_atf_pdn.n_atf,
sys_bd_componente_poa.codigo AS componente,
poa1.codigo AS poa_1,
cat1.codigo AS categoria_1,
poa2.codigo AS poa_2,
cat2.codigo AS categoria_2,
poa3.codigo AS poa_3,
cat3.codigo AS categoria_3,
poa4.codigo AS poa_4,
cat4.codigo AS categoria_4,
clar_atf_pdn.monto_1,
clar_atf_pdn.saldo_1,
clar_atf_pdn.monto_2,
clar_atf_pdn.saldo_2,
clar_atf_pdn.monto_3,
clar_atf_pdn.saldo_3,
clar_atf_pdn.monto_4,
clar_atf_pdn.saldo_4,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion AS linea,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_pdn.n_voucher,
pit_bd_ficha_pdn.monto_organizacion,
pit_bd_ficha_pdn.f_termino,
org_ficha_organizacion.nombre AS organizacion,
sys_bd_tipo_org.descripcion AS tipo_org,
pit_bd_ficha_pdn.fuente_fida,
pit_bd_ficha_pdn.fuente_ro,
(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org
FROM
clar_ampliacion_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
INNER JOIN sys_bd_subactividad_poa AS poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
INNER JOIN sys_bd_categoria_poa AS cat1 ON cat1.cod = poa1.cod_categoria_poa
INNER JOIN sys_bd_subactividad_poa AS poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
INNER JOIN sys_bd_categoria_poa AS cat2 ON cat2.cod = poa2.cod_categoria_poa
INNER JOIN sys_bd_subactividad_poa AS poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
INNER JOIN sys_bd_categoria_poa AS cat3 ON cat3.cod = poa3.cod_categoria_poa
INNER JOIN sys_bd_subactividad_poa AS poa4 ON poa4.cod = clar_atf_pdn.cod_poa_4
INNER JOIN sys_bd_categoria_poa AS cat4 ON cat4.cod = poa4.cod_categoria_poa
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.cod_ampliacion = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($r10=mysql_fetch_array($result))
{

	$total_pdn_1=$r10['monto_1']+$r10['monto_2']+$r10['monto_3']+$r10['monto_4'];
	
	$total_saldo_1=$r10['saldo_1']+$r10['saldo_2']+$r10['saldo_3']+$r10['saldo_4'];

?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($r10['n_atf']);?> –  <? echo periodo($row['f_ampliacion']);?> - <? echo $row['oficina'];?> <BR>
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
    <td colspan="2"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización a transferir </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $r10['organizacion'];?></td>
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
    <td colspan="2"><? echo $encabezado;?></td>
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
    <td width="30%">FIDA: <? echo number_format($row['fte_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fte_ro'],2);?></td>
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

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_ampliacion']);?></div>
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
    <td align="center"><? echo $r1['nombre']." ".$r1['apellido']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_ampliacion']);?> / OL <? echo $row['oficina'];?></u></div>
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
    <td width="76%">Desembolso  de <? echo $encabezado;?></td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> - PIT – <? echo periodo($row['f_contrato']);?> – <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_ampliacion']);?></td>
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
(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto_desembolsado,
clar_atf_pdn.n_atf,
sys_bd_tipo_iniciativa.codigo_iniciativa,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco,
org_ficha_organizacion.nombre
FROM
clar_ampliacion_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE
clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_ampliacion_pit.cod_ampliacion = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($u3=mysql_fetch_array($result))
{
?>  
  <tr>
    <td><? echo $u3['nombre'];?></td>
    <td class="centrado"><? echo $u3['codigo_iniciativa'];?></td>
    <td class="centrado"><? echo numeracion($u3['n_atf'])." - ".periodo($row['f_ampliacion']);?></td>
    <td><? echo $u3['banco'];?></td>
    <td class="centrado"><? echo $u3['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($u3['monto_desembolsado'],2);?></td>
  </tr>
<?
}
?>  
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha"><?
	$pdss70=$r4['aporte_pdss']*0.70;
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
    <td align="center"><?php  echo $r1['nombre']." ".$r1['apellido'];?><BR> JEFE DE OFICINA LOCAL </td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>





<!--  Aqui ponemos los planes de negocio -->
<?php 

$sql="SELECT
clar_ampliacion_pit.cod_ampliacion,
clar_atf_pdn.n_atf,
(clar_atf_pdn.monto_1+
clar_atf_pdn.monto_2+
clar_atf_pdn.monto_3+
clar_atf_pdn.monto_4) AS monto,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre1,
tesorero.paterno AS paterno1,
tesorero.materno AS materno1
FROM
clar_ampliacion_pit
INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_relacionador = clar_ampliacion_pit.cod_ampliacion
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
WHERE
clar_ampliacion_pit.cod_ampliacion = '$cod' AND
clar_ampliacion_pit.n_ampliacion = 3 AND
clar_atf_pdn.cod_tipo_atf_pdn = 3";
$result=mysql_query($sql) or die (mysql_error());
while($fila2=mysql_fetch_array($result))
{
?>

<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $fila2['org'];?> con <?php  echo $fila2['tipo_doc'];?> N° <?php  echo $fila2['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $fila2['nombre']." ".$fila2['paterno']." ".$fila2['materno'];?>, identificado con DNI N° <?php  echo $fila2['presidente'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($fila2['monto_desembolsado'],2);?> (<?php  echo vuelveletra($fila2['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $r3['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>
, Relacionado con el Primer Desembolso, realizado en el Distrito de <?php  echo $r3['distrito'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato PIT N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $r3['distrito'];?>,<?php  echo traducefecha($row['f_ampliacion']);?></div>
<H1 class=SaltoDePagina></H1>
<?php 
}
?>










<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>
</body>
</html>