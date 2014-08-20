<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_adenda_idl.n_adenda, 
	pit_bd_ficha_adenda_idl.f_adenda, 
	pit_bd_ficha_adenda_idl.referencia, 
	pit_bd_ficha_adenda_idl.f_inicio AS inicio, 
	pit_bd_ficha_adenda_idl.meses, 
	pit_bd_ficha_adenda_idl.f_termino AS termino, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_inicio, 
	pit_bd_ficha_idl.f_termino, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	org_ficha_directivo.cod_directivo, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	org_ficha_usuario.nombre AS nombrea, 
	org_ficha_usuario.paterno AS paternoa, 
	org_ficha_usuario.materno AS maternoa, 
	org_ficha_usuario.n_documento AS dnia, 
	pit_bd_ficha_adenda_idl.contenido
FROM pit_bd_ficha_idl INNER JOIN pit_bd_ficha_adenda_idl ON pit_bd_ficha_idl.cod_ficha_idl = pit_bd_ficha_adenda_idl.cod_idl
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_usuario ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
WHERE pit_bd_ficha_adenda_idl.cod_adenda='$cod'";
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
<dd  class="active"><a href="">Edicion de Contenido</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=EDIT" onsubmit="return checkSubmit();">

<div class="twelve columns">
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<!-- Inicio Contenido -->
<?
if ($modo==edit)
{
echo $row['contenido'];
}
else
{
?>
<div class="capa justificado">
	<p>Conste por el presente documento la adenda al Contrato Nº <? echo numeracion($row['n_contrato']);?>-<? echo periodo($row['f_contrato']);?>-OL <? echo $row['oficina'];?> de Donación sujeto a Cargo de Inversión para el Desarrollo Local que celebran, de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II con RUC Nº 20456188118, en adelante denominado "SIERRA SUR II", representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?> Sr.(a) <? echo $row['nombres']." ".$row['apellidos'];?>, identificado(a) con DNI Nº <? echo $row['dni'];?>, con domicilio legal en <? echo $row['direccion'];?>  del Distrito de <? echo $row['dist'];?>, de la Provincia de <? echo $row['prov'];?> y Departamento de <? echo $row['dep'];?>; y de otra parte la <? echo $row['nombre'];?> con R.U.C Nº <? echo $row['n_documento'];?>, con domicilio legal en <? echo $row['sector'];?> del Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?> y Departamento de <? echo $row['departamento'];?>, a quien en adelante se le denominará "LA MUNICIPALIDAD", representada por su Alcalde Sr. <? echo $row['nombrea']." ".$row['paternoa']." ".$row['maternoa'];?>, identificado(a) con DNI. Nº <? echo $row['dnia'];?>, en los términos y condiciones establecidos en las cláusulas siguientes:</p>
	
	<p><strong>ANTECEDENTES:</strong> </p>
	
	<p><strong>PRIMERO.-</strong> Que con fecha <? echo traducefecha($row['f_contrato']);?> se suscribió el Contrato de Donación sujeto a Cargo N° <? echo numeracion($row['n_contrato']);?>-<? echo periodo($row['f_contrato']);?>-OL <? echo $row['oficina'];?> para el cofinanciamiento de Inversiones para el Desarrollo Local entre “SIERRA SUR II” y “LA MUNICIPALIDAD”, el mismo que establecía que la vigencia del contrato es de <? echo meses($row['f_inicio'],$row['f_termino']);?> meses  incluyendo el período de ejecución de la inversión para el desarrollo local la liquidación  y el perfeccionamiento de la donación.</p>
	
	<p><strong>SEGUNDO.-</strong>  Que la CLAUSULA NOVENA del contrato,  establece que en caso de ocurrir situaciones no previstas en dicho Contrato los acuerdos que se deriven posteriormente serán expresados en una Adenda.</p>
	

	<p><strong>TERCERO.-</strong>  Con fecha <? echo traducefecha($row['f_adenda']);?>, “LA MUNICIPALIDAD”, ha presentado a “SIERRA SUR II” la(el) <? echo $row['referencia'];?>, solicitando la ampliación de vigencia por <? echo $row['meses'];?> meses, con la finalidad de cumplir con la ejecución de la Inversión para el Desarrollo Local denominada "<? echo $row['denominacion'];?>".</p>
	
	<p><strong>CUARTO.-</strong>  El  Jefe de la Oficina Local de <? echo $row['oficina'];?>, analizando el informe presentado  por, “LA MUNICIPALIDAD”  conforme al documento referido en la cláusula anterior, procede a suscribir la presente Addenda.</p>
	
	<p><strong>OBJETO DEL CONTRATO:</strong></p>
	
	<p><strong>QUINTO.-</strong>  El objeto del presente documento es la ampliación de la vigencia del Contrato Nº <? echo numeracion($row['n_contrato']);?>-<? echo periodo($row['f_contrato']);?>-OL <? echo $row['oficina'];?>, con la finalidad de que no sea resuelto; permitiendo con ello que LA MUNICIPALIDAD culmine con la ejecución de la inversión y la liquidación correspondiente.</p>
	
	<p><strong>PLAZO DEL CONTRATO:</strong></p>
	
	<p><strong>SEXTO.-</strong> La vigencia del Contrato de Donación con Cargo de Inversiones para el Desarrollo Local Nº <? echo numeracion($row['n_contrato']);?>-<? echo periodo($row['f_contrato']);?>-OL <? echo $row['oficina'];?> se prorroga en <? echo $row['meses'];?> meses, contados a partir de la suscripción del presente documento. Dicho plazo implica culminar con la ejecución de la inversión y entregar la liquidación de obra, para el perfeccionamiento respectivo. </p>
	
	<p><strong>OBLIGACIONES DE LAS PARTES.</strong></p>
	
	<p><strong>SEPTIMO.-</strong> Las cláusulas referentes a las obligaciones y demás partes en el Contrato de Donación sujeto a Cargo de Inversiones para el Desarrollo Local Nº <? echo numeracion($row['n_contrato']);?>-<? echo periodo($row['f_contrato']);?>-OL <? echo $row['oficina'];?> son ratificadas por ambas partes.</p>
	
	<p><strong>COMPETENCIA JURISDICCIONAL:</strong></p>
	
	<p><strong>OCTAVO.-</strong> En caso de surgir alguna controversia entre las partes, respecto a la ejecución del presente Contrato, las partes convienen en someterse a la competencia de los jueces y tribunales de la ciudad de Arequipa. Podrá recurrirse a la jurisdicción arbitral dentro del ámbito del departamento de Arequipa.</p>
	
	<p>En fe de lo acordado, suscribimos el presente documento en tres ejemplares en la ciudad de <? echo $row['dist'];?> el <? echo traducefecha($row['f_adenda']);?>.</p>
	
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
	<a href="adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
	
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
