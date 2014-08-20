<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_adenda
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$n_adenda=$r1['n_adenda']+1;
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
  
  <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
  
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
<dd  class="active"><a href="">Nueva Adenda a Contrato PIT</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Nº de Contrato PIT</div>
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
		ORDER BY pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
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
<div class="two columns">Fecha de Adenda</div>
<div class="four columns"><input type="date" name="f_adenda" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>




<div class="two columns">Documento de Referencia</div>
<div class="ten columns"><input type="text" name="referencia" class="required ten"></div>
<div class="twelve columns"><h6>II.- Información de la Adenda (Llenar según sea el caso)</h6></div>
<div class="twelve columns"><h6>2.1 Concurso Interfamiliar Adicional</h6></div>
<div class="six columns">Solicita CIF Adicional</div>
<div class="six columns">
	<select name="cif">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Si</option>
		<option value="0">No</option>
	</select>
</div>
<div class="twelve columns"><h6>2.2 Modificación de Plazos</h6></div>
<div class="six columns">¿Se amplia o se reduce el plazo de la iniciativa segun contrato?</div>
<div class="six columns">
	<select name="adenda_plazo">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">En esta addenda se ampliará el plazo de duración de la iniciativa</option>
		<option value="0">No se modificará la duración de la iniciativa</option>
	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Nº de meses a ampliar o reducir</div>
<div class="four columns"><input type="text" name="n_meses" class="digits five"></div>
<div class="two columns">Nueva fecha de termino del contrato</div>
<div class="four columns"><input type="date" name="f_termino" class="date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns"><h6>2.2 Modificación de Montos</h6></div>
<div class="six columns">¿Se amplia o se reduce el monto solicitado por la iniciativa?</div>
<div class="six columns">
	<select name="adenda_monto">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">En esta addenda se ampliará el monto solicitado por la iniciativa</option>
		<option value="0">No se modificarán los montos solicitados por la iniciativa</option>
	</select>
</div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar Operacion</a>
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
