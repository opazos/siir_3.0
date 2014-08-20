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
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Registrar Contrato</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Nº contrato SIGLO  (Formato Número-Año)</div>
<div class="four columns"><input type="text" name="n_contrato" class="required seven" placeholder="0000-0000" maxlength="9"></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" class="required seven date" placeholder="aaaa-mm-dd" maxlength="10"></div>


<div class="two columns">Nº del Requerimiento con el que solicito el servicio SIGLO (Formato Número-Año)</div>
<div class="four columns"><input type="text" name="n_requerimiento" class="required seven" placeholder="0000-0000" maxlength="9"></div>

<div class="two columns">Fecha de presentacion de esta solicitud de desembolso</div>
<div class="four columns"><input type="date" name="f_demanda" class="required seven date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>




<div class="twelve columns"><h6>Justificación del Servicio</h6></div>
<div class="twelve columns">
	<textarea name="objeto" rows="5"></textarea>
</div>

<div class="two columns">Ente contratante</div>
<div class="ten columns"><input type="text" name="contratante" class="ten required"></div>

<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required nine"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_ifi'];?>"><? echo $r1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>III.- Conceptos de pago</h6></div>

<div class="two columns">Monto correspondiente a formalizacion de Organizaciones(S/.)</div>
<div class="two columns"><input type="text" name="monto1" class="required number seven"></div>

<div class="two columns">Monto correspondiente a otros conceptos (Pagos varios, tramites, etc) (S/.)</div>
<div class="two columns"><input type="text" name="monto2" class="required number seven"></div>

<div class="two columns">Monto correspondiente a la contrapartida de las Organizaciones (S/.)</div>
<div class="two columns"><input type="text" name="monto3" class="required number seven"></div>


<div class="twelve columns"><br/></div>	
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="formalicer.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
