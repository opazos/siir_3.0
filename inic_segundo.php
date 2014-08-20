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
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
<!-- Menu vertical -->
 <ul class="nav-bar vertical">
 <li class="has-flyout">
	 <a href="">PIT</a>
	 <ul class="flyout">
		 <li><a href="pit/n_pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pit/pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>		 
		 <li><a href="pit/pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit/pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li>
 
 <li class="has-flyout">
	 <a href="">PGRN</a>
	 <ul class="flyout">
		 <li><a href="pit/n_pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>
		 <li><a href="pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir relacion de Participantes</a></li>		 
		 <li><a href="pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit/pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li> 
 
 
 <li class="has-flyout">
	 <a href="">PDN</a>
	 <ul class="flyout">
		 <li><a href="pit/n_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pit/pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>	
		 <li><a href="pit/pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir relacion de Participantes</a></li>	 
		 <li><a href="pit/pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit/pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li> 
 
  <li class="has-flyout">
	 <a href="">IDL</a>
	 <ul class="flyout">
		 <li><a href="pit/n_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pit/idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>		 
		 <li><a href="pit/idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit/idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li>
</ul>
<!-- fin del menu vertical -->
<hr>
</div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	     <p><h5> Modulo para el registro de Iniciativas que se presentan a un CLAR de Segundo Desembolso</h5></p>

	     <ol>
		     <li>En este módulo se registra la informacion correspondiente a las propuestas que se presentan para acceder a su segundo desembolso.</li>
	     </ol>
	     
	     
	        
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
</body>
</html>
