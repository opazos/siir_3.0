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
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  

  <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Liquidacion de Contratos CLAR</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD_LIQUIDA" onsubmit="return checkSubmit();">

<div class="two columns">Nº contrato</div>
<div class="four columns">
	<select name="cod_clar">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_evento_clar.cod_clar, 
		clar_bd_evento_clar.n_contrato, 
		clar_bd_evento_clar.f_evento
		FROM clar_bd_evento_clar
		WHERE clar_bd_evento_clar.n_contrato<>0 AND
		clar_bd_evento_clar.estado=0 AND
		clar_bd_evento_clar.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY clar_bd_evento_clar.f_evento ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_clar'];?>"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_evento']);?></option>
		<?
		}
		?>
	</select>
</div>

<div class="two columns">Fecha de rendicion</div>
<div class="four columns"><input type="date" name="f_rendicion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="twelve columns"><h6>II.- Resultados alcanzados</h6></div>
<div class="twelve columns">
	<textarea id="elm1" name="resultado" rows="15" cols="80" style="width: 100%"></textarea>
</div>
<div class="twelve columns"><h6>III.- Problemas u observaciones</h6></div>
<div class="twelve columns">
	<textarea id="elm2" name="problema" rows="15" cols="80" style="width: 100%"></textarea>
</div>	

<div class="twelve columns"><h6>IV.- Presupuesto Ejecutado</h6></div>

<table class="responsive">
	<thead>
		<tr>
			<th colspan="4">Monto Ejecutado (S/.)</th>
		</tr>
		<tr>
			<th>NEC PDSS II</th>
			<th>Organización</th>
			<th>Municipalidad</th>
			<th>Otro</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td><input type="text" name="ejec_pdss" class="number"></td>
			<td><input type="text" name="ejec_org" class="number"></td>
			<td><input type="text" name="ejec_mun" class="number"></td>
			<td><input type="text" name="ejec_otro" class="number"></td>
		</tr>
	</tbody>
	
</table>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_liquida" class="secondary button">Finalizar</a>
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
