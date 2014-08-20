<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT * FROM pit_bd_ficha_adenda_idl WHERE cod_adenda='$cod'";
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
<dd  class="active"><a href="">Modificar addenda de plazo</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	
	<div class="two columns">Fecha de firma</div>
	<div class="ten columns"><input type="date" name="f_adenda" class="three date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_adenda'];?>">
	
	<input type="hidden" name="codigo" value="<? echo $row['cod_adenda'];?>">
	</div>
	<div class="twelve columns"><h6>Seleccionar Contrato al que se le realizará Adenda</h6></div>
	<div class="twelve columns">
		
		<select name="idl">
			<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
		pit_bd_ficha_idl.n_contrato, 
		pit_bd_ficha_idl.f_contrato
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
		WHERE pit_bd_ficha_idl.n_contrato<>0 AND
		org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
		ORDER BY pit_bd_ficha_idl.f_contrato ASC, pit_bd_ficha_idl.n_contrato ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_ficha_idl'];?>" <? if ($f1['cod_ficha_idl']==$row['cod_idl']) echo "selected";?>><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></option>
		<?
		}
		?>
		</select>
		
	</div>
	
	<div class="twelve columns"><br/></div>
	
	<div class="two columns">Documento de referencia</div>
	<div class="ten columns"><input type="text" name="referencia" class="ten" value="<? echo $row['referencia'];?>"></div>
	<div class="twelve columns"><br/></div>
	<div class="two columns">Fecha de inicio</div>
	<div class="four columns"><input type="date" name="f_inicio" class="date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_inicio'];?>"></div>
	<div class="two columns">Nº de meses a ampliar</div>
	<div class="four columns"><input type="text" name="mes" class="required digits five" value="<? echo $row['meses'];?>"></div>
	
	
	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="primary button">Guardar cambios</button>
		<a href="adenda_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_avance" class="secondary button">Cancelar</a>
	</div>
	
</form>
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
