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
	 <a href="">CONTRATO PIT</a>
	 <ul class="flyout">
		 <li><a href="pit/n_liquida_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Contrato</a></li>
		 <li><a href="pit/pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Liquidación</a></li>
     <li><a href="pit/pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li>
 
 
 <li class="has-flyout">
	 <a href="">INIC. PGRN</a>
	 <ul class="flyout">
	 	<li><a href="pit/n_liquida_pgrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="pit/pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="pit/pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li> 
 
 
 <li class="has-flyout">
	 <a href="">INIC. PDN</a>
	 <ul class="flyout"> 
	 	<li><a href="pit/n_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="pit/pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="pit/pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li> 
 
  <li class="has-flyout">
	 <a href="">INIC. IDL</a>
	 <ul class="flyout">
	 	<li><a href="pit/n_liquida_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="pit/idl_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="pit/idl_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li>
<? 
if ($row['cod_dependencia']==001)
{
?> 
 <li class="has-flyout">
   <a href="">LIQUIDACIONES</a>
   <ul class="flyout">
     <li><a href="pit/n_registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar liquidacion</a></li>
     <li><a href="pit/registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Editar informacion</a></li>
   </ul>
 </li>
<?
}
?>
</ul>
<!-- fin del menu vertical -->
<hr>
</div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	     <p><h5> Modulo para la liquidación de iniciativas</h5></p>

	     <ol>
		     <li>En este Módulo se procederá a realizar la liquidación de las distintas iniciativas que han sido atendidas por el NEC PDSS II.</li>
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
