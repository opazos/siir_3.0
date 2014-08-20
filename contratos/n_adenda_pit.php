<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


	

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
<dd  class="active"><a href="">
<?
if ($modo==atf)
{
echo "Paso 2 de 3.- Información de desembolso";
}
elseif($modo==edit)
{
echo "Paso 3 de 3.- Edición de Adenda";
}
else
{
echo "Paso 1 de 3.- Información de la adenda";
}
?>
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==atf)
{

$sql="SELECT pit_bd_ficha_adenda.cod_tipo_iniciativa, 
	(pit_bd_ficha_adenda.an_pdss+ 
	pit_bd_ficha_adenda.cif_pdss+ 
	pit_bd_ficha_adenda.at_pdss+ 
	pit_bd_ficha_adenda.vg_pdss+ 
	pit_bd_ficha_adenda.pf_pdss+ 
	pit_bd_ficha_adenda.idl_pdss) AS monto_pdss, 
	pit_bd_ficha_adenda.tipo_monto
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$tipo_iniciativa=$r1['cod_tipo_iniciativa'];
$tipo_monto=$r1['tipo_monto'];

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.periodo='$anio' AND
sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$n_atf=$r2['n_atf_iniciativa']+1;
$n_solicitud=$r2['n_solicitud_iniciativa']+1;

?>
<form name="form5" class="custom" method="post" action="gestor_adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_ATF" onsubmit="return checkSubmit();">
<div class="twelve columns">Seleccionar iniciativa</div>
<div class="twelve columns">
	<select name="iniciativa">
		<option value="" selected="selected">Seleccionar</option>
		<?
		if ($tipo_iniciativa==3)
		{
			$sql="SELECT org_ficha_taz.nombre, 
			pit_bd_ficha_pit.cod_pit AS cod
			FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
			INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
		}
		elseif($tipo_iniciativa==4)
		{
			$sql="SELECT pit_bd_ficha_pdn.denominacion, 
			org_ficha_organizacion.nombre, 
			pit_bd_ficha_pdn.cod_pdn AS cod
			FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
	 pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
	 pit_bd_ficha_pdn.cod_estado_iniciativa<>001";	
		}
		elseif($tipo_iniciativa==5)
		{
			$sql="SELECT org_ficha_organizacion.nombre, 
			pit_bd_ficha_mrn.cod_mrn AS cod
			FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_adenda.cod_pit
			INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
			WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";	
		}
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod'];?>"><? echo $f1['nombre']." ".$f1['denominacion'];?></option>
		<?
		}
		?>		
	</select>
</div>

<input type="hidden" name="cod_numera" value="<? echo $r2['cod'];?>">
<input type="hidden" name="tipo_iniciativa" value="<? echo $tipo_iniciativa;?>">
<input type="hidden" name="tipo_monto" value="<? echo $tipo_monto;?>">

<?
if ($tipo_monto<>0)
{
?>
<div class="two columns">Nº ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="required digits five" value="<? echo $n_atf;?>"></div>
<div class="two columns">Nº Solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $n_solicitud;?>">
</div>
<div class="two columns">Fecha de Solicitud</div>
<div class="four columns"><input type="date" name="f_solicitud" class="required date six" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>

<div class="twelve columns"><br/></div>

<table>
<thead>
	<tr>
		<th class="seven">Indique las cantidades a <? if ($tipo_monto==1) echo "Ampliar"; else echo "Reducir";?></th>
		<th>Aporte PDSS (S/.)</th>
		<th>Aporte Organizacion (S/.)</th>
	</tr>
</thead>
	
<?
if ($tipo_iniciativa==3)
{
?>	
	<tr>
		<td>Animador Territorial</td>
		<td><input type="text" name="pm1" class="number"></td>
		<td><input type="text" name="om1" class="number"></td>
	</tr>
<?
}
elseif($tipo_iniciativa==4)
{
?>
	<tr>
		<td>Asistencia Tecnica</td>
		<td><input type="text" name="pm3" class="number"></td>
		<td><input type="text" name="om3" class="number"></td>
	</tr>
	
	<tr>
		<td>Visita Guiada</td>
		<td><input type="text" name="pm4" class="number"></td>
		<td><input type="text" name="om4" class="number"></td>
	</tr>

	<tr>
		<td>Participacion en ferias</td>
		<td><input type="text" name="pm5" class="number"></td>
		<td><input type="text" name="om5" class="number"></td>
	</tr>
<?
}
elseif($tipo_iniciativa==5)
{
?>	
	<tr>
		<td>Concursos Interfamiliares</td>
		<td><input type="text" name="pm2" class="number"></td>
		<td><br/></td>
	</tr>
	
		<tr>
		<td>Asistencia Tecnica</td>
		<td><input type="text" name="pm3" class="number"></td>
		<td><input type="text" name="om3" class="number"></td>
	</tr>
	
		<tr>
		<td>Visita Guiada</td>
		<td><input type="text" name="pm4" class="number"></td>
		<td><input type="text" name="om4" class="number"></td>
	</tr>
<?
}
?>	
</table>
<?
}
?>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Terminar</a>
</div>


</form>
<?
}
elseif($modo==edit)
{
$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_adenda.cod_tipo_iniciativa, 
	pit_bd_ficha_adenda.cod_iniciativa, 
	pit_bd_ficha_adenda.referencia, 
	pit_bd_ficha_adenda.n_meses, 
	pit_bd_ficha_adenda.total_pdss, 
	pit_bd_ficha_adenda.total_org, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda, 
	pit_bd_ficha_adenda.n_atf, 
	pit_bd_ficha_adenda.n_solicitud, 
	pit_bd_ficha_adenda.f_desembolso, 
	pit_bd_ficha_adenda.comentario, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.sector, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	(pit_bd_ficha_adenda.an_pdss+ 
	pit_bd_ficha_adenda.cif_pdss+ 
	pit_bd_ficha_adenda.at_pdss+ 
	pit_bd_ficha_adenda.vg_pdss+ 
	pit_bd_ficha_adenda.pf_pdss+ 
	pit_bd_ficha_adenda.idl_pdss) AS aporte_pdss, 
	(pit_bd_ficha_adenda.an_org+ 
	pit_bd_ficha_adenda.at_org+ 
	pit_bd_ficha_adenda.vg_org+ 
	pit_bd_ficha_adenda.pf_org+ 
	pit_bd_ficha_adenda.idl_org) AS aporte_org, 
	pit_bd_ficha_adenda.an_pdss, 
	pit_bd_ficha_adenda.cif_pdss, 
	pit_bd_ficha_adenda.at_pdss, 
	pit_bd_ficha_adenda.vg_pdss, 
	pit_bd_ficha_adenda.pf_pdss, 
	pit_bd_ficha_adenda.idl_pdss, 
	pit_bd_ficha_adenda.an_org, 
	pit_bd_ficha_adenda.at_org, 
	pit_bd_ficha_adenda.vg_org, 
	pit_bd_ficha_adenda.pf_org, 
	pit_bd_ficha_adenda.idl_org
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

//2.- Obtengo los Prefijos
$proyecto="<strong>SIERRA SUR II</strong>";
$org="<strong>LA ORGANIZACION</strong>";
$orga="<strong>LAS ORGANIZACIONES</strong>";

//3.- Directiva del PIT
$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directiva_taz.cod_cargo_directivo=1 AND
org_ficha_directiva_taz.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directiva_taz.cod_cargo_directivo=3 AND
org_ficha_directiva_taz.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//4.- Obtengo los datos de la organizacion Hija *******************************************************************************************************************
if ($row1['cod_tipo_iniciativa']==3)
{
	//1.- datos de la iniciativa
	$sql="SELECT org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pit.cod_pit
	FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa AND pit_bd_ficha_adenda.cod_iniciativa = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

	//2.- datos de la directiva
	$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
	FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_directiva_taz.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
	 WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
	 org_ficha_directiva_taz.cod_cargo_directivo=1 AND
	 org_ficha_directiva_taz.vigente=1";
	 $result=mysql_query($sql) or die (mysql_error());
	 $f2=mysql_fetch_array($result);
	 
	$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
	FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_directiva_taz.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
	 WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
	 org_ficha_directiva_taz.cod_cargo_directivo=3 AND
	 org_ficha_directiva_taz.vigente=1";
	 $result=mysql_query($sql) or die (mysql_error());
	 $f3=mysql_fetch_array($result);	 
	 
//3.- Atf de primer desembolso
$sql="SELECT clar_atf_pit.n_atf, 
	clar_atf_pit.monto_desembolsado, 
	clar_atf_pit.saldo, 
	clar_atf_pit.n_voucher, 
	clar_atf_pit.monto_organizacion, 
	sys_bd_subcomponente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN clar_atf_pit ON clar_atf_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = clar_atf_pit.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.- Atf de segundo desembolso
$sql="SELECT clar_atf_pit_sd.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_pit_sd.monto_desembolsado, 
	clar_atf_pit_sd.saldo, 
	sys_bd_subcomponente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN clar_atf_pit_sd ON clar_atf_pit_sd.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = clar_atf_pit_sd.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit_sd.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pit_sd.cod_ficha_sd
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);	 
$total=mysql_num_rows($result);

}
elseif($row1['cod_tipo_iniciativa']==4)
{
//1.- obtengo los datos del PDN
$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_org
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//2.- Juntas directivas
$sql="SELECT org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);

$sql="SELECT org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);

//3.-ATF de primer desembolso
$sql="SELECT clar_atf_pdn.n_atf, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.-ATF de segundo desembolso
$sql="SELECT clar_atf_pdn.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
clar_atf_pdn.cod_tipo_atf_pdn=2";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);
$total=mysql_num_rows($result);

}
elseif($row1['cod_tipo_iniciativa']==5)
{
//1.- Obtengo los datos del PGRN
$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	pit_bd_ficha_mrn.cif_pdss, 
	pit_bd_ficha_mrn.at_pdss, 
	pit_bd_ficha_mrn.vg_pdss, 
	pit_bd_ficha_mrn.ag_pdss, 
	pit_bd_ficha_mrn.at_org, 
	pit_bd_ficha_mrn.vg_org
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//2.- Juntas directivas
$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);

$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);

//3.- ATF de primer desembolso
$sql="SELECT clar_atf_mrn.n_atf, 
	clar_atf_mrn.desembolso_1, 
	clar_atf_mrn.saldo_1, 
	clar_atf_mrn.desembolso_2, 
	clar_atf_mrn.saldo_2, 
	clar_atf_mrn.desembolso_3, 
	clar_atf_mrn.saldo_3, 
	clar_atf_mrn.desembolso_4, 
	clar_atf_mrn.saldo_4
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.- ATF de segundo desembolso
$sql="SELECT clar_atf_mrn_sd.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_mrn_sd.monto_1, 
	clar_atf_mrn_sd.saldo_1, 
	clar_atf_mrn_sd.monto_2, 
	clar_atf_mrn_sd.saldo_2, 
	clar_atf_mrn_sd.monto_3, 
	clar_atf_mrn_sd.saldo_3, 
	clar_atf_mrn_sd.monto_4, 
	clar_atf_mrn_sd.saldo_4
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_mrn_sd ON clar_atf_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_mrn_sd.cod_ficha_sd
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);
$total=mysql_num_rows($result);


}
?>
<form name="form5" class="custom" method="post" action="gestor_adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_CONTENIDO" onsubmit="return checkSubmit();">
<div class="twelve columns">
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<!-- aca colocamos el contenido de la adenda -->
<div class="capa justificado">
	<p>
		Conste por el presente documento la adenda al  Contrato de Donación sujeto a Cargo para la Ejecución del Plan de Inversión Territorial de la organización que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DEDESARROLLO SIERRA SUR II, con RUC Nº 20456188118, con domicilio legal en <? echo $row1['direccion'];?>, del Distrito de <? echo $row1['dist'];?>, Provincia de <? echo $row1['prov'];?> y Departamento de <? echo $row1['dep'];?>, en adelante denominado "<? echo $proyecto;?>" representado por el Jefe de la Oficina Local de <? echo $row1['oficina'];?>, <? echo $row1['nombres']." ".$row1['apellidos'];?>, con DNI. Nº <? echo $row1['dni'];?>; y de otra parte la Organización <? echo $row1['nombre'];?> con <? echo $row1['tipo_doc'];?> N° <? echo $row1['n_documento'];?>, con domicilio legal en <? echo $row1['sector'];?>,ubicada en el Distrito de <? echo $row1['distrito'];?>, Provincia de <? echo $row1['provincia'];?>, Departamento de <? echo $row1['departamento'];?>, en adelante denominada "<? echo $org;?>", representada por su Presidente(a), <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?> con DNI. N° <? echo $r1['n_documento'];?> y su Tesorero(a), <? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno'];?> con DNI. N° <? echo $r2['n_documento'];?>; Intervienen también en la suscripcion de la addenda, las siguientes organizaciones ubicadas en el territorio de "<? echo $org;?>", que en general se les denominan "<? echo $orga;?>" y específicamente son:
	</p>
</div>


<div class="capa justificado">
	<ul>
		<li><? echo $f1['nombre'];?>, con <? echo $f1['tipo_doc'];?> 
N° <? echo $f1['n_documento'];?>, representada por su Presidente(a) <? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?> con DNI N° <? echo $f2['n_documento'];?>
y su Tesorero(a) <? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?> con DNI N° <? echo $f3['n_documento'];?>.</li>
	</ul>
</div>






<div class="capa txt_titulo"><u>ANTECEDENTES:</u> </div>

<div class="capa justificado">
<p><strong>PRIMERO: </strong>  	Que con fecha <? echo traducefecha($row1['f_contrato']);?> se suscribió el Contrato de Donación  sujeto a Cargo para para la Ejecución del Plan de Inversión Territorial  Nº  <? echo numeracion($row1['n_contrato']);?> –<? echo periodo($row1['f_contrato']);?> – PIT – OL <? echo $row1['oficina'];?> entre <? echo $proyecto;?> y <? echo $org;?>, siendo su vigencia de  <? echo $row1['mes'];?>  meses </p>
</div>

<div class="capa justificado"><p><strong>SEGUNDO: </strong>  	Que la cláusula novena del contrato,  establece que en caso de ocurrir situaciones no previstas en dicho Contrato los acuerdos que se deriven posteriormente serán expresados en una Adenda.</p></div>

<div class="capa justificado"><p><strong>TERCERO:</strong>   	Que con fecha <? echo traducefecha($row1['f_adenda']);?>, <? echo $org;?>, ha presentado a <? echo $proyecto;?> una  carta solicitando la ampliación del presupuesto o vigencia del contrato <? echo numeracion($row1['n_contrato']);?> –<? echo periodo($row1['f_contrato']);?> – PIT – OL <? echo $row1['oficina'];?>,  según: <? echo $row1['referencia'];?></p></div>

<div class="capa justificado"><p><strong>CUARTO: </strong>  	 El  Jefe de la Oficina Local de <? echo $row1['oficina'];?>, analizando la situación de <? echo $org;?>  conforme al documento referido en la cláusula anterior, procede a suscribir la presente Adenda.</p></div>

<div class="capa txt_titulo"><u>OBJETO DEL CONTRATO:</u></div>

<div class="capa justificado"><p><strong>QUINTO:</strong>   	El objeto del presente contrato, es la Adenda en la ampliación de vigencia del Contrato Nº <? echo numeracion($row1['n_contrato']);?> –<? echo periodo($row1['f_contrato']);?> – PIT – OL <? echo $row1['oficina'];?>, con la finalidad de que dicho contrato no sea resuelto; permitiendo con ello que <? echo $org;?> continué ejecutando su Plan de Negocio y culmine con liquidación y  perfeccionamiento de la donación.</p></div>

<div class="capa justificado"><p><strong>SEXTO: </strong>  	El objeto del presente contrato, es la Adenda en la ampliación de  presupuesto  del Contrato Nº <? echo numeracion($row1['n_contrato']);?> –<? echo periodo($row1['f_contrato']);?> – PIT – OL <? echo $row1['oficina'];?>, con el siguiente detalle:</p></div>


<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini">
	<tr class="txt_titulo">
		<td width="70%">CONCEPTO</td>
		<td width="10%" class="centrado">APORTE DE <? echo $proyecto;?></td>
		<td width="10%" class="centrado">APORTE DE <? echo $orga;?></td>
		<td width="10%" class="centrado">TOTAL</td>
	</tr>
	<?
	if ($row1['cod_tipo_iniciativa']==3)
	{
	?>
	<tr>
	<td>ANIMADOR TERRITORIAL</td>
	<td class="derecha"><? echo number_format($row1['an_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['an_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['an_pdss']+$row1['an_org'],2);?></td>
	</tr>
	<?
	}
	elseif($row1['cod_tipo_iniciativa']==4)
	{
	?>
	<tr>
	<td>ASISTENCIA TECNICA</td>
	<td class="derecha"><? echo number_format($row1['at_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['at_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['at_pdss']+$row1['at_org'],2);?></td>	
	</tr>
	<tr>
	<td>VISITA GUIADA</td>
	<td class="derecha"><? echo number_format($row1['vg_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['vg_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['vg_pdss']+$row1['vg_org'],2);?></td>	
	</tr>
	<tr>
	<td>PARTICIPACION EN FERIAS</td>
	<td class="derecha"><? echo number_format($row1['pf_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['pf_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['pf_pdss']+$row1['pf_org'],2);?></td>	
	</tr>	
	<?
	}
	elseif($row1['cod_tipo_iniciativa']==5)
	{
	?>
	<tr>
	<td>CONCURSOS INTERFAMILIARES</td>
	<td class="derecha"><? echo number_format($row1['cif_pdss'],2);?></td>
	<td class="derecha">0.00</td>
	<td class="derecha"><? echo number_format($row1['cif_pdss'],2);?></td>	
	</tr>		
	<tr>
	<td>ASISTENCIA TECNICA</td>
	<td class="derecha"><? echo number_format($row1['at_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['at_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['at_pdss']+$row1['at_org'],2);?></td>	
	</tr>
	<tr>
	<td>VISITA GUIADA</td>
	<td class="derecha"><? echo number_format($row1['vg_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row1['vg_org'],2);?></td>
	<td class="derecha"><? echo number_format($row1['vg_pdss']+$row1['vg_org'],2);?></td>	
	</tr>	
	<?
	}
	?>
	<tr>
		<td>TOTAL</td>
		<td class="derecha"><? echo number_format($row1['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($row1['aporte_org'],2);?></td>
		<td class="derecha"><? echo number_format($row1['aporte_pdss']+$row1['aporte_org'],2);?></td>
	</tr>
	
</table>


<div class="capa justificado"><p>con la finalidad de que dicho contrato  cumpla con los objetivos previstos del  PLAN DE INVERSION TERRITORIAL (PIT) y no sea resuelto; permitiendo con ello que <? echo $org;?> continué ejecutando el  Plan de Negocio, componente del PIT y culmine con la  liquidación y  perfeccionamiento de la donación.</p></div>

<div class="capa txt_titulo"><u>PLAZO DEL CONTRATO:</u></div>

<div class="capa justificado"><p><strong>SÉPTIMO:</strong>   	La presente adenda amplia el plazo del Contrato,  en <? echo $row1['n_meses'];?> meses, contados a partir del término de su plazo, a partir de ello la fecha de vencimiento del contrato el <? echo traducefecha($row1['f_termino_adenda']);?></p></div>

<div class="capa txt_titulo"><u>OBLIGACIONES DE LAS PARTES:</u></div>
<div class="capa justificado"><p><strong>OCTAVO: </strong>  	Las cláusulas referentes a las obligaciones de las partes en el Contrato Nº <? echo numeracion($row1['n_contrato']);?> –<? echo periodo($row1['f_contrato']);?> – PIT – OL <? echo $row1['oficina'];?> son ratificadas por ambas partes.</p></div>

<div class="capa txt_titulo"><u>COMPETENCIA JURISDICCIONAL:</u></div>
<div class="capa justificado"><p><strong>NOVENO:</strong>   	En caso de surgir alguna controversia entre las partes, respecto a la aplicación del presente Contrato, éstas convienen en someterse a la competencia de los jueces y tribunales de la localidad de Arequipa, podrá recurrirse a la jurisdicción arbitral, dentro del ámbito del departamento de Arequipa.</p></div>

<div class="capa justificado">En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row1['dist'];?> siendo hoy <? echo traducefecha($row1['f_adenda']);?></div>



<!-- fin del contenido de la adenda -->
</textarea>	
</div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar e imprimir</button>
	<a href="adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
</div>

</form>

<?
}
else
{

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_adenda
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$n_adenda=$r1['n_adenda']+1;
?>
<form name="form5" class="custom" method="post" action="gestor_adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Nº de adenda</div>
<div class="ten columns"><input type="text" name="n_adenda" class="two required digits" readonly="readonly" value="<? echo $n_adenda;?>">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
</div>

<div class="two columns">Nº de contrato</div>
<div class="four columns">
	<select name="pit">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pit.cod_pit, 
		pit_bd_ficha_pit.n_contrato, 
		pit_bd_ficha_pit.f_contrato
		FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE pit_bd_ficha_pit.n_contrato<>0 AND
		org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY pit_bd_ficha_pit.f_contrato ASC,
		pit_bd_ficha_pit.n_contrato ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_pit'];?>"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></option>
		<?
			}
		?>
	</select>
</div>
<div class="two columns">Fecha de solicitud de adenda</div>
<div class="four columns"><input type="date" name="f_adenda" placeholder="aaaa-mm-dd" maxlength="10" class="required date six" value="<? echo $fecha_hoy;?>"></div>
<div class="two columns">Tipo de iniciativa a la que se le hara adenda</div>
<div class="four columns">
	<select name="tipo_iniciativa">
		<option value="" selected="selected">Seleccionar</option>
		<option value="3">PIT</option>
		<option value="4">PDN</option>
		<option value="5">PGRN</option>
	</select>
</div>
<div class="two columns">Documento de Referencia</div>
<div class="four columns"><input type="text" name="referencia" class="required nine"></div>




<div class="twelve columns"><h6>II.- Informacion de la Addenda (Llenar según sea el caso)</h6></div>
<div class="twelve columns"><h6>2.1 Modificación de Plazos</h6></div>
<div class="six columns">¿Se amplia o se reduce el plazo de la iniciativa segun contrato?</div>
<div class="six columns">
	<select name="adenda_plazo">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">En esta addenda se ampliará el plazo de duración de la iniciativa</option>
		<option value="2">En esta addenda se reducirá el plazo de duración de la iniciativa</option>
		<option value="0">No se modificará la duración de la iniciativa</option>
	</select>
</div>
<div class="twelve columns"></div>

<div class="two columns">Nº de meses a ampliar o reducir</div>
<div class="four columns"><input type="text" name="n_meses" class="digits five"></div>
<div class="two columns">Nueva fecha de termino del contrato</div>
<div class="four columns"><input type="date" name="f_termino" class="date six" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns"><h6>2.2 Modificación de Montos</h6></div>
<div class="six columns">¿Se amplia o se reduce el monto solicitado por la iniciativa?</div>
<div class="six columns">
	<select name="adenda_monto">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">En esta addenda se ampliará el monto solicitado por la iniciativa</option>
		<option value="2">En esta addenda se reducirá el monto solicitado por la iniciativa</option>
		<option value="0">No se modificarán los montos solicitados por la iniciativa</option>
	</select>
</div>
<div class="twelve columns"></div>

	

<div class="twelve columns"><h6>III.- Comentarios / Indicaciones adicionales</h6></div>
<div class="twelve columns">
	<textarea name="comentario" rows="5"></textarea>
</div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambio</button>
	<a href="adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar operacion</a>
</div>
</form>

<?
}
?>
</div>
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
