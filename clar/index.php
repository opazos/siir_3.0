<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//Busco los eventos CLAR por rendir
$sql="SELECT clar_bd_evento_clar.cod_clar
FROM clar_bd_evento_clar
WHERE clar_bd_evento_clar.estado=0 AND
clar_bd_evento_clar.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_evento_clar.n_contrato=0";
$result=mysql_query($sql) or die (mysql_error());
$total1=mysql_num_rows($result);

//Busco los eventos CLAR por Liquidar
$sql="SELECT clar_bd_evento_clar.cod_clar
FROM clar_bd_evento_clar
WHERE clar_bd_evento_clar.estado=0 AND
clar_bd_evento_clar.cod_dependencia='".$row['cod_dependencia']."' AND
clar_bd_evento_clar.n_contrato<>0";
$result=mysql_query($sql) or die (mysql_error());
$total2=mysql_num_rows($result);


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
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">

    <div class="twelve columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        
	        <div class="twelve columns"><h5>Modulo CLAR</h5></div>
	        <div class="twelve columns"><hr/></div>
<div class="twelve columns">
<p>El módulo CLAR, nos permite realizar todo el proceso de calificación de iniciativas en base al mecanismo CLAR, sistematizando asi todo el proceso y por tanto facilitandonos la generación de Instrumentos que se generan en este tipo de procesos.</p>

<p>Adicionalmente a ello, este módulo nos permite llevar a cabo la sistematización de los diferentes concursos que pueden llegar a realizarse en este tipo de eventos (CLAR e INTERCON). Gracias a esto podemos tener acceso a diferente información que surge a raiz de estos eventos.</p>

	<?
	if($total1<>0)
	{
	?>
	<a href="n_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="alert button">ATENCION: Tiene <? echo $total1;?> eventos CLAR por rendir</a> 
	<?	
	}
	?>
	<p><br/></p>
	<?
	if($total2<>0)
	{
	?>
	<a href="n_liquida_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="alert button">ATENCION: Tiene <? echo $total2;?> Contratos CLAR por liquidar</a> 
	<?	
	}
	?>
</div>

	
</div>
	        
	        <!-- fin del contenido -->
        </div>
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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
