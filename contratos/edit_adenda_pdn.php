<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Buscamos la info
$sql="SELECT pit_bd_ficha_adenda_pdn.n_adenda, 
	pit_bd_ficha_adenda_pdn.f_adenda, 
	pit_bd_ficha_adenda_pdn.referencia, 
	pit_bd_ficha_adenda_pdn.f_inicio, 
	pit_bd_ficha_adenda_pdn.meses, 
	pit_bd_ficha_adenda_pdn.f_termino, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	org_ficha_organizacion.cod_tipo_doc, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_adenda_pdn.contenido
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

//Presidente
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN org_ficha_organizacion ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_adenda_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1 AND
pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//Tesorero
$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN org_ficha_organizacion ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_adenda_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1 AND
pit_bd_ficha_adenda_pdn.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

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
<form name="form5" method="post" action="gestor_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=EDIT" onsubmit="return checkSubmit();">

<div class="twelve columns">
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<?
if ($modo==edit)
{
echo $row1['contenido'];
}
else
{
?>
<div class="capa justificado">
<p>Conste por el presente documento la adenda al Contrato N° <? echo numeracion($row1['n_contrato']);?> - PDN - <? echo periodo($row1['f_contrato']);?> - OL <? echo $row1['oficina'];?> de Donación sujeto a Cargo que celebran, de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II con RUC Nº 20456188118, en adelante denominado "SIERRA SUR II", representado por el Jefe de la Oficina Local de <? echo $row1['oficina'];?>. Sr.(a) <? echo $row1['nombres']." ".$row1['apellidos'];?>, identificado(a) con DNI Nº <? echo $row1['dni'];?>, con domicilio legal en <? echo $row1['direccion'];?>  del Distrito de <? echo $row1['dist'];?>, de la Provincia de <? echo $row1['prov'];?>. y Departamento de <? echo $row1['dep'];?>; y de la otra parte la Organización  <? echo $row1['org'];?> con <? echo $row1['tipo_doc'];?>  Nº <? echo $row1['n_documento'];?>, con domicilio legal en <? echo $row1['sector'];?> del Distrito de <? echo $row1['distrito'];?>, Provincia de <? echo $row1['provincia'];?> y Departamento de <? echo $row1['departamento'];?>, a quien en adelante se le denominará "LA ORGANIZACION", representada por su Presidente (a), <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?> Identificado(a) con DNI. N°. <? echo $r1['dni'];?> y su Tesorero(a), <? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno'];?> Identificado(a) con DNI. N° <? echo $r2['dni'];?>, en los términos y condiciones  siguientes: </p>
<p><strong>ANTECEDENTES: </strong></p>
<p><strong>PRIMERO.-</strong> Que con fecha <? echo traducefecha($row1['f_contrato']);?> se suscribió el Contrato de Donación sujeto a Cargo N° <? echo numeracion($row1['n_contrato']);?> - PDN - <? echo periodo($row1['f_contrato']);?> - OL <? echo $row1['oficina'];?> para el cofinanciamiento de Asistencia Técnica  entre “SIERRA SUR II” y “LA "LA ORGANIZACION”, el mismo que establecía que la vigencia del contrato es de <? echo $row1['mes'];?> meses  incluyendo el período de ejecución de la Asistencia Técnica  la liquidación  y el perfeccionamiento de la donación. </p>
<p><strong>SEGUNDO.-</strong>  Que la cláusula novena .del contrato,  establece que en caso de ocurrir situaciones no previstas en dicho Contrato los acuerdos que se deriven posteriormente serán expresados en una Addenda.</p>	
<p><strong>TERCERO.-</strong>  Con fecha <? echo traducefecha($row1['f_adenda']);?>, "LA ORGANIZACION", ha presentado a “SIERRA SUR II” una solicitud indicando  las dificultades  en la  culminación de los servicios de Asistencia Técnica  debido a: <? echo $row1['referencia'];?>. por  lo que ha solicitado una ampliación de vigencia del contrato mencionado.</p>
<p><strong>CUARTO.-</strong>  El  Jefe de la Oficina Local de <? echo $row1['oficina'];?>, analizando la solicitud  presentado  por , "LA ORGANIZACION"  conforme al documento referido en la cláusula anterior, procede a suscribir la presente Addenda.</p>
<p><strong>OBJETO DEL CONTRATO:</strong></p>
<p><strong>QUINTO.-</strong>  El objeto del presente documento es la ampliación de la vigencia del Contrato Nº <? echo numeracion($row1['n_contrato']);?> - PDN - <? echo periodo($row1['f_contrato']);?> - OL <? echo $row1['oficina'];?>, con la finalidad de que no sea resuelto; permitiendo con ello que "LA ORGANIZACION" culmine con la ejecución de los servicios  de Asistencia Técnica, la liquidación y perfeccionamiento de la donación.</p>
<p><strong>PLAZO DEL CONTRATO:</strong></p>
<p><strong>SEXTO.-</strong> La vigencia del Contrato de Donación sujeto a Cargo de cofinanciamiento de servicios de Asistencia Técnica  Nº <? echo numeracion($row1['n_contrato']);?> - PDN - <? echo periodo($row1['f_contrato']);?> - OL <? echo $row1['oficina'];?> se prorroga en <? echo $row1['meses'];?> meses, contados a partir de la suscripción del presente documento. Dicho plazo implica culminar con la ejecución de los servicios de Asistencia Técnica, entregar  el informe  final y documentos requeridos para el perfeccionamiento respectivo. </p>
<p><strong>OBLIGACIONES DE LAS PARTES</strong></p>
<p><strong>SEPTIMO.-</strong> Las cláusulas referentes a las obligaciones y demás partes en el Contrato de Donación sujeto a Cargo de cofinanciamiento de Servicios de Asistencia Técnica Nº <? echo numeracion($row1['n_contrato']);?> - PDN - <? echo periodo($row1['f_contrato']);?> - OL <? echo $row1['oficina'];?> son ratificadas por ambas partes.</p>
<p><strong>COMPETENCIA JURISDICCIONAL:</strong></p>
<p><strong>OCTAVO.-</strong> En caso de surgir alguna controversia entre las partes, respecto a la ejecución del presente Contrato, las partes convienen en someterse a la competencia de los jueces y tribunales de la ciudad de Arequipa. Podrá recurrirse a la jurisdicción arbitral dentro del ámbito del departamento de Arequipa.</p>
<p>En fe de lo acordado, suscribimos el presente documento en tres ejemplares en la ciudad de <? echo $row1['dist'];?> el día <? echo traducefecha($row1['f_adenda']);?>.</p>
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
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar operacion</a>
	
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
