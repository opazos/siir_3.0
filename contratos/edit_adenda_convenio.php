<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Buscamos la info
$sql="SELECT pit_bd_ficha_adenda_convenio.cod, 
	pit_bd_ficha_adenda_convenio.n_adenda, 
	pit_bd_ficha_adenda_convenio.f_adenda, 
	pit_bd_ficha_adenda_convenio.f_termino, 
	pit_bd_ficha_adenda_convenio.contenido, 
	gcac_bd_ficha_convenio.n_convenio, 
	gcac_bd_ficha_convenio.f_inicio, 
	gcac_bd_ficha_convenio.f_termino AS f_fin, 
	gcac_bd_ficha_convenio.f_presentacion, 
	gcac_bd_ficha_convenio.dni, 
	gcac_bd_ficha_convenio.representante, 
	gcac_bd_ficha_convenio.cargo, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_personal.n_documento AS dnis
FROM gcac_bd_ficha_convenio INNER JOIN pit_bd_ficha_adenda_convenio ON gcac_bd_ficha_convenio.cod_ficha = pit_bd_ficha_adenda_convenio.id_convenio
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_ficha_convenio.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_bd_ficha_convenio.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gcac_bd_ficha_convenio.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_adenda_convenio.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


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
<form name="form5" method="post" action="gestor_adenda_convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=EDIT" onsubmit="return checkSubmit();">

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

<p>Conste por el presente documento, la adenda al Convenio de Cooperación Interinstitucional que celebran, de una parte el NUCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II”, con RUC Nº20456188118, con domicilio legal en la Plaza de Armas S/N del distrito de Quequeña, provincia de Arequipa, departamento de Arequipa, en adelante denominado “SIERRA SUR II”, representado por su Director Ejecutivo, Sr. José Mercedes Sialer Pasco, con DNI Nº 29280211; y de otra parte la <? echo $row1['nombre'];?>, con <? echo $row1['tipo_doc'];?> Nº <? echo $row1['n_documento'];?>, con domicilio legal en <? echo $row1['sector'];?>, del distrito de <? echo $row1['distrito'];?>, provincia de <? echo $row1['provincia'];?> y departamento de <? echo $row1['departamento'];?>, en adelante denominada "LA MUNICIPALIDAD", representada por su <? echo $row1['cargo'];?>, Sr(a). <? echo $row1['representante'];?>, identificado con DNI Nº <? echo $row1['dni'];?>, en los términos y condiciones siguientes</p>
<p><strong>ANTECEDENTES: </strong></p>
<p><strong>PRIMERO:</strong>    Con fecha <? echo traducefecha($row1['f_presentacion']);?> se suscribió el  CONVENIO Nº <? echo numeracion($row1['n_convenio'])."-".periodo($row1['f_presentacion'])."-".$row1['oficina'];?> DE COOPERACIÓN INTERINSTITUCIONAL ENTRE EL PROYECTO “SIERRA SUR II” Y LA <? echo $row1['nombre'];?>  con  una vigencia que  concluye el <? echo traducefecha($row1['f_fin']);?>.</p>
<p><strong>SEGUNDO:</strong>   	Con fecha 31 de octubre del año 2013 y 15 de noviembre del 2013,  el Fondo Internacional de Desarrollo Agrícola FIDA y el Gobierno Peruano suscribieron una Enmienda al Contrato de Préstamo N° 799-PE y Donación N° 1158-PE en las cual se prorrogan las fechas de terminación y cierre de financiación  del Proyecto al 31 de diciembre del 2014 y 30 de Junio del 2015 respectivamente, con el objeto de complementar la ejecución de las inversiones para fortalecer el desarrollo de las organizaciones beneficiarias y de sus  territorios</p>
<p><strong>TERCERO:</strong>   	La Oficina Local <? echo $row1['oficina'];?>  ha considerado pertinente solicitar  la ampliación de vigencia  del convenio, para la continuación de la COLABORACION CONJUNTA DE TRABAJO  que constituye el sustento para la suscripción de la presente adenda.</p>
<p><strong>OBJETO DE LA ADENDA:</strong></p>
<p><strong>CUARTO:</strong>   	El objeto de la presente Adenda al Convenio  es modificar la CLAUSULA QUINTA del CONVENIO Nº <? echo numeracion($row1['n_convenio'])."-".periodo($row1['f_presentacion'])."-".$row1['oficina'];?>, en los términos siguientes:</p>
<p>
	<ul>
		<li><strong>CLAUSULA QUINTA :  DE LA VIGENCIA. </strong>El presente Convenio entra en vigencia desde la fecha de su suscripción hasta el <? echo traducefecha($row1['f_termino']);?>, fecha en que está prevista la terminación de actividades de “SIERRA SUR II”.
</li>
	</ul>
</p>
<p><strong>OBLIGACIONES DE LAS PARTES.</strong></p>
<p><strong>QUINTO:</strong>   	Las demás clausulas establecidas en el Convenio Nº <? echo numeracion($row1['n_convenio'])."-".periodo($row1['f_presentacion'])."-".$row1['oficina'];?>, se mantienen vigentes.</p>
<p>En fe de lo acordado, suscribimos el presente convenio en tres ejemplares, en la localidad de <? echo $row1['distrito'];?> el <? echo traducefecha($row1['f_adenda']);?>.</p>

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
	<a href="adenda_convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar operacion</a>
	
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
