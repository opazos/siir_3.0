<?
require("funciones/sesion.php");
include("funciones/funciones.php");
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  
  <script src="javascripts/modernizr.foundation.js"></script>
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="has-flyout"><a href="#">Polizas</a>
  
  <ul class="flyout">
	  <li><a href="pit/n_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar Poliza</a></li>
	  <li><a href="pit/poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edita">Modificar Poliza</a></li>
	  <li><a href="pit/poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=elimina">Eliminar Poliza</a></li>
  </ul>
  
  
  
  </li>
  <li class="has-flyout"><a href="#">Solicitud de Pago</a>
    <ul class="flyout">
	    <li><a href="pit/n_pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar solicitud de pago</a></li>
	    <li><a href="pit/pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edita">Modificar solicitud de pago</a></li>
	    <li><a href="pit/pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular solicitud de pago</a></li>
	    <li><a href="pit/pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir solicitud de pago</a></li>
    </ul>
  </li>
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <div class="twelve columns"><h6>SERVICIOS FINANCIEROS: MODULO DE SEGURO DE VIDA CAMPESINO</h6></div>
	        
	        <!-- fin del contenido -->
        </div>
        </div>
      </div>


    </div>

  </div>

  <!-- Footer -->
<? include("footer.php");?>


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
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
  <script type="text/javascript" src="plugins/buscar/js/buscar-en-tabla.js"></script>   
</body>
</html>
