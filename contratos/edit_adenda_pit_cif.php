<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_adenda_pit.n_adenda, 
	pit_bd_ficha_adenda_pit.f_adenda, 
	pit_bd_ficha_adenda_pit.referencia, 
	pit_bd_ficha_adenda_pit.tipo_plazo, 
	pit_bd_ficha_adenda_pit.tipo_monto, 
	pit_bd_ficha_adenda_pit.concurso, 
	pit_bd_ficha_adenda_pit.n_mes, 
	pit_bd_ficha_adenda_pit.f_termino, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre AS organizacion, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.cod_dependencia, 
	presidente.nombre AS nombre1, 
	presidente.paterno AS paterno1, 
	presidente.materno AS materno1, 
	presidente.n_documento AS dni1, 
	tesorero.n_documento AS dni2, 
	tesorero.nombre AS nombre2, 
	tesorero.paterno AS paterno2, 
	tesorero.materno AS materno2, 
	org_ficha_taz.sector, 
	pit_bd_ficha_pit.cod_pit, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni_jefe, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	pit_bd_ficha_adenda_pit.aporte_pdss, 
	pit_bd_ficha_adenda_pit.aporte_org, 
	pit_bd_ficha_pit.n_mes, 
	pit_bd_ficha_adenda_pit.contenido
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN org_ficha_directiva_taz presidente ON presidente.n_documento = org_ficha_taz.presidente AND presidente.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND presidente.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN org_ficha_directiva_taz tesorero ON tesorero.n_documento = org_ficha_taz.tesorero AND tesorero.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND tesorero.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_ficha_adenda_pit.cod_adenda='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//obtengo el numero de MRN
$sql="SELECT Count(pit_bd_ficha_mrn.cod_mrn) AS mrn
FROM clar_atf_mrn INNER JOIN pit_bd_ficha_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit ='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//obtengo el numero de PDN
$sql="SELECT count(pit_bd_ficha_pdn.cod_pdn) as pdn
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);


/****** OBTENGO LOS MONTOS A COFINANCIAR *******************************************************/
//1.- montos PIT
$sql="SELECT
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org
FROM
pit_bd_ficha_pit
WHERE
pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//2.- montos pgrn
$sql="SELECT Sum(pit_bd_ficha_mrn.cif_pdss+pit_bd_ficha_mrn.at_pdss+pit_bd_ficha_mrn.vg_pdss+pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	   Sum(pit_bd_ficha_mrn.at_org+pit_bd_ficha_mrn.vg_org) AS aporte_org
FROM clar_atf_mrn INNER JOIN pit_bd_ficha_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit= '".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

//3.- montos pdn
$sql="SELECT SUM(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	SUM(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org
FROM clar_atf_pdn INNER JOIN pit_bd_ficha_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit = '".$row['cod_pit']."' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

//4.- Monto adendas
$sql="SELECT SUM(pit_bd_iniciativa_adenda.aporte_pdss) AS aporte_pdss, 
	SUM(pit_bd_iniciativa_adenda.aporte_org) AS aporte_org
FROM pit_bd_iniciativa_adenda
WHERE pit_bd_iniciativa_adenda.cod_ficha_adenda='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);


//ahora aobtengo los montos

$aporte_pdss=$r3['aporte_pdss']+$r4['aporte_pdss']+$r5['aporte_pdss']+$r6['aporte_pdss'];

$aporte_org=$r4['aporte_org']+$r5['aporte_org']+$r6['aporte_org'];

$aporte_total=$aporte_pdss+$aporte_org;

$pp_pdss=($aporte_pdss/$aporte_total)*100;
$pp_org=($aporte_org/$aporte_total)*100;

?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../javascripts/btn_envia.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
  
    <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
  
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Edicion de Contenido</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=EDIT" onsubmit="return checkSubmit();">

<div class="twelve columns">
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<?
if ($modo==edit)
{
echo $row['contenido'];
}
else
{
?>
<!-- aca colocamos el contenido de la adenda -->
<div class="capa justificado">
Conste por el presente documento, la adenda al  Contrato de Donación sujeto a Cargo para la Ejecución del Plan de Inversión Territorial que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II, con RUC Nº 20456188118, con domicilio legal en <? echo $row['direccion'];?>, del Distrito de <? echo $row['dist'];?>, Provincia de <? echo $row['prov'];?> y Departamento de <? echo $row['dep'];?>, en adelante denominado "SIERRA SUR II" representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombres']." ".$row['apellidos'];?>, identificado con DNI. Nº <? echo $row['dni_jefe'];?>; y de otra parte la Organización: <? echo $row['organizacion'];?> con <? echo $row['tipo_doc'];?> N° <? echo $row['n_documento'];?>, con domicilio legal en <? echo $row['sector'];?>, ubicada en el Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?>, Departamento de <? echo $row['departamento'];?>, en adelante denominada "LA ORGANIZACIÓN", representada por su Presidente(a), <? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?> con DNI. N° <? echo $row['dni1'];?> y su Tesorero(a), <? echo $row['nombre2']." ".$row['paterno2']." ".$row['materno2'];?> con DNI. N° <? echo $row['dni2'];?> Intervienen también en el presente contrato, las siguientes organizaciones ubicadas en el territorio de "LA ORGANIZACIÓN", que en general se les denominan "LAS ORGANIZACIONES" y específicamente son:
</div>
<div class="capa">
<ul>
<?
//Buscamos las Organizaciones de Plan de Negocio
$sql="SELECT
sys_bd_tipo_doc.descripcion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS nombre_org,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre_1,
tesorero.paterno AS paterno_1,
tesorero.materno AS materno_1
FROM
org_ficha_organizacion
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());

while($f11=mysql_fetch_array($result))
{
echo "<li>".$f11['nombre_org'].", con ".$f11['descripcion']." N° ".$f11['n_documento'].", representada por su Presidente(a) ".$f11['nombre']." ".$f11['paterno']." ".$f11['materno']." con DNI N° ".$f11['presidente']." y su Tesorero(a) ".$f11['nombre_1']." ".$f11['paterno_1']." ".$f11['materno_1']." con DNI N° ".$f11['tesorero'].".</li>";
}
//Buscamos los PGRN

$sql="SELECT

sys_bd_tipo_doc.descripcion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre AS nombre_org,
org_ficha_organizacion.presidente,
presidente.nombre,
presidente.paterno,
presidente.materno,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre_1,
tesorero.paterno AS paterno_1,
tesorero.materno AS materno_1
FROM org_ficha_organizacion
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE
pit_bd_ficha_mrn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_mrn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_mrn.cod_pit='".$row['cod_pit']."'";

$result=mysql_query($sql) or die (mysql_error());
$total2=mysql_num_rows($result);

while($f12=mysql_fetch_array($result))
{	
echo "<li>".$f12['nombre_org'].", con ".$f12['descripcion']." N° ".$f12['n_documento'].", representada por su Presidente(a) ".$f12['nombre']." ".$f12['paterno']." ".$f12['materno']." con DNI N° ".$f12['presidente']." y su Tesorero(a) ".$f12['nombre_1']." ".$f12['paterno_1']." ".$f12['materno_1']." con DNI N° ".$f12['tesorero'].".</li>";
}
?>
</ul>
</div>

 
<div class="capa justificado">Los representantes de "LA ORGANIZACIÓN" y de "LAS ORGANIZACIONES" se constituyen como responsables
solidarios de esta adenda, la cual se suscribe en los términos y condiciones establecidas en las cláusulas siguientes:</div>

<div class="capa justificado">
<p><strong>ANTECEDENTES:</strong></p> 

<p><strong>PRIMERO:</strong>   	Con fecha <? echo traducefecha($row['f_contrato']);?> se suscribió el CONTRATO Nº <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato']);?>-PIT–OL <? echo $row['oficina'];?>. de Donación  sujeto a Cargo entre SIERRA SUR II y LA ORGANIZACIÓN para  la Ejecución del Plan de Inversión Territorial por un monto total de S/. <? echo number_format($aporte_total,2);?>(<? echo vuelveletra($aporte_total);?>) y una vigencia de  <? echo $row['n_mes'];?>  meses.</p></div>
 
<div class="capa justificado">
<p><strong>SEGUNDO:</strong>   	La cláusula novena del contrato,  establece que en caso de ocurrir situaciones no previstas en dicho Contrato los acuerdos que se deriven posteriormente serán expresados en una Adenda</p></div>

<div class="capa justificado">
<p><strong>TERCERO:</strong>   	Con fecha 15 de octubre del año 2012, el Comité Directivo del SIERRA SUR II, mediante Acuerdo formalizado en el Acta Nro. 002-2012, aprueba la propuesta de ampliación de plazo por 12 meses e incremento presupuestal del  Proyecto; en cuyo marco se faculta la ampliación de los contratos suscritos con las organizaciones que ejecutan los Planes de Inversión Territorial (PIT), hasta por 9 meses, con el objeto de complementar inversiones para fortalecer el desarrollo territorial.</p></div>

<div class="capa justificado">
<p><strong>CUARTO:</strong>   	Con fecha <? echo traducefecha($row['f_adenda']);?>, LA ORGANIZACIÓN, ha solicitado a SIERRA SUR II acceder a los recursos provenientes de la ampliación presupuestal precisados en el numeral TERCERO de la presente.</p></div>

<div class="capa justificado">
<p><strong>QUINTO:</strong>   	Mediante <? echo $row['antecedente'];?>, la Oficina Local ha considerado pertinente la solicitud presentada por la ORGANIZACIÓN,  precisando los alcances de la misma, que constituye el sustento para la suscripción de la presente adenda.</p></div>


<div class="capa justificado">
<p><strong>OBJETO DE LA ADENDA:</strong></p>

<p><strong>SEXTO:</strong>   	El objeto de la presente Adenda al Contrato   es modificar 

<?
if ($row['tipo_monto']==1)
{
?>
las CLAUSULAS SEGUNDA y TERCERA 
<?
}
else
{
?>
la CLAUSULA TERCERA
<?
}
?>


del Contrato  <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato']);?>-PIT–OL <? echo $row['oficina'];?>, en los términos siguientes:</p></div>

<?
if ($row['tipo_monto']==1)
{
?>
<div class="capa justificado">
<p><strong>CLAUSULA SEGUNDA: OBJETO DEL CONTRATO</strong></p>

<p>Por el presente contrato "SIERRA SUR II" transfiere en donación sujeto a cargo, el monto total de S/. <? echo number_format($row['aporte_pdss'],2);?> (<? echo vuelveletra($row['aporte_pdss']);?>) a "LA ORGANIZACIÓN" y a "LAS ORGANIZACIONES"; las mismas que se comprometen a aportar el monto de S/. <? echo number_format($row['aporte_org'],2);?>  (<? echo vuelveletra($row['aporte_org']);?>). Ambos montos serán destinados para financiar la  ejecución de "EL PIT", según se resume en el siguiente cuadro:</p></div>


<p>
	<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="70%"><strong>PLAN DE INVERSION TERRITORIAL</strong></td>
    <td width="10%" align="center"><strong>APORTE SIERRA SUR II (S/.)</strong></td>
    <td width="10%" align="center"><strong>APORTES &quot;LA ORG.&quot; (S/.)</strong></td>
    <td width="10%" align="center"><strong>TOTAL (S/.)</strong></td>
  </tr>
<?
$sql="SELECT pit_bd_ficha_pit.n_animador, 
	(pit_bd_ficha_pit.aporte_pdss+
	pit_bd_iniciativa_adenda.aporte_pdss) AS aporte_pdss,  
	(pit_bd_ficha_pit.aporte_org+ 
	pit_bd_iniciativa_adenda.aporte_org) AS aporte_org,
	org_ficha_taz.nombre
FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN pit_bd_iniciativa_adenda ON pit_bd_iniciativa_adenda.cod_iniciativa = pit_bd_ficha_pit.cod_pit AND pit_bd_iniciativa_adenda.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
WHERE pit_bd_ficha_pit.cod_pit = '".$row['cod_pit']."'";
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
$sql="SELECT (pit_bd_ficha_mrn.cif_pdss+
pit_bd_ficha_mrn.at_pdss+
pit_bd_ficha_mrn.vg_pdss+
pit_bd_ficha_mrn.ag_pdss+pit_bd_iniciativa_adenda.aporte_pdss) AS aporte_pdss, 
	org_ficha_organizacion.nombre, 
	(pit_bd_ficha_mrn.at_org+
pit_bd_ficha_mrn.vg_org+pit_bd_iniciativa_adenda.aporte_org) AS aporte_org 	
FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN pit_bd_iniciativa_adenda ON pit_bd_iniciativa_adenda.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_iniciativa_adenda.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_pit='".$row['cod_pit']."'  AND
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
$sql="SELECT org_ficha_organizacion.nombre, 
	(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org+
pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_iniciativa_adenda.aporte_pdss AS aporte_pdss_adenda, 
	pit_bd_iniciativa_adenda.aporte_org AS aporte_org_adenda
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 LEFT JOIN pit_bd_iniciativa_adenda ON pit_bd_iniciativa_adenda.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_iniciativa_adenda.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_pit='".$row['cod_pit']."' AND
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
    <td align="right"><? echo number_format($f5['aporte_pdss']+$f5['aporte_pdss_adenda'],2);?></td>
    <td align="right"><? echo number_format($f5['aporte_org']+$f5['aporte_org_adenda'],2);?></td>
    <td align="right"><? echo number_format($f5['aporte_pdss']+$f5['aporte_org']+$f5['aporte_pdss_adenda']+$f5['aporte_org_adenda'],2);?></td>
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
</p>

	

<div class="capa justificado">
<p>
El documento de "EL PIT", aprobado por el CLAR, compuesto por Uno <?php  echo litera($r1['mrn']);?>
(<? echo $r1['mrn'];?>) <strong>PGRN</strong> y/o 
<?php  echo litera($r2['pdn']);?>
(<? echo $r2['pdn'];?>) <strong>PDN</strong>, suscrito por las partes, forman parte de este contrato, así como las propuestas de ampliación presentadas por la ORGANIZACIÓN con opinión favorable de la Oficina Local.</p></div>

<?
}
?>

<?
if ($row['tipo_plazo']==1)
{
?>
<div class="capa justificado">
<p><strong>CLAUSULA TERCERA : PLAZO DEL CONTRATO:</strong></p>
<p>El plazo establecido por las partes para la ejecución del presente contrato es hasta de 24 meses a partir de la suscripción del presente contrato. Este plazo incluye las acciones de liquidación del Contrato y perfeccionamiento de la donación a "LA ORGANIZACIÓN".
El plazo de vencimiento de cada iniciativa integrante de "EL PIT", será el que se especifica en el respectivo anexo. Este plazo incluye las acciones de liquidación del Contrato y perfeccionamiento de la donación de cada una de "LAS ORGANIZACIONES", que serán consideradas como acciones parciales.</p></div>
<?
}
?>

<div class="capa justificado">
<p><strong>OBLIGACIONES DE LAS PARTES.</strong></p>
<p><strong>SÉPTIMO:</strong>   	Las demás clausulas establecidas en el Contrato Nº <? echo numeracion($row['n_contrato'])."-".periodo($row['f_contrato']);?>-PIT–OL <? echo $row['oficina'];?>, se mantienen vigentes.</p>
</div>

<div class="capa justificado">
<p>En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row['dist'];?> siendo hoy <? echo traducefecha($row['f_adenda']);?></p>
</div>
<?
}
?>
<!-- Fin contenido -->
</textarea>
</div>
	

</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
	
</div>
</form>

</li>
</ul>
</div>
</div>





    </div>

  </div>

  <!-- Footer -->
<? include("../footer.php");?>


  <!-- Included JS Files (Uncompressed) -->
  <!--
  
  <script src="javascripts/jquery.js"></script>
  
  <script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
  
  <script src="javascripts/jquery.foundation.forms.js"></script>
  
  <script src="javascripts/jquery.event.move.js"></script>
  
  <script src="javascripts/jquery.event.swipe.js"></script>
  
  <script src="javascripts/jquery.foundation.reveal.js"></script>
  
  <script src="javascripts/jquery.foundation.orbit.js"></script>
  
  <script src="javascripts/jquery.foundation.navigation.js"></script>
  
  <script src="javascripts/jquery.foundation.buttons.js"></script>
  
  <script src="javascripts/jquery.foundation.tabs.js"></script>
  
  <script src="javascripts/jquery.foundation.tooltips.js"></script>
  
  <script src="javascripts/jquery.foundation.accordion.js"></script>
  
  <script src="javascripts/jquery.placeholder.js"></script>
  
  <script src="javascripts/jquery.foundation.alerts.js"></script>
  
  <script src="javascripts/jquery.foundation.topbar.js"></script>
  
  <script src="javascripts/jquery.foundation.joyride.js"></script>
  
  <script src="javascripts/jquery.foundation.clearing.js"></script>
  
  <script src="javascripts/jquery.foundation.magellan.js"></script>
  
  -->
  
  <!-- Included JS Files (Compressed) -->
  <script src="../javascripts/jquery.js"></script>
  <script src="../javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="../javascripts/app.js"></script>
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
