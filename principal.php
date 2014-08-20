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



     <div class="row">
    <div class="twelve columns">
      <div class="row">
 
    <!-- Thumbnails -->
 
        <div class="three mobile-two columns">
          <a href="clar/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>"><img src="images/foto1.png" /></a>
          <h6 class="panel" align="center"><a href="clar/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Modulo Concursos</a></h6>
        </div>
        
        <div class="three mobile-two columns">
           <a href="contratos/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>"><img src="images/foto2.png" /></a>
          <h6 class="panel" align="center"><a href="contratos/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Modulo Contratos</a></h6>
        </div>        
 
        <div class="three mobile-two columns">
           <a href="seguimiento/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>"><img src="images/foto3.png" /></a>
          <h6 class="panel" align="center"><a href="seguimiento/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Modulo S & E</a></h6>
        </div>
 
        <div class="three mobile-two columns">
         <a href="report/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>"><img src="images/foto4.png" /></a>
          <h6 class="panel" align="center"><a href="report/index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Modulo Reportes</a></h6>
        </div>
 
    <!-- End Thumbnails -->
 
      </div>
    </div>
  </div>

<!-- Iniciamos el contenido -->
<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="has-flyout">
    <a href="#">Panel de control</a>
    <a href="#" class="flyout-toggle"><span> </span></a>
    <ul class="flyout">
      <li><a href="user.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Modificar datos de usuario</a></li>
      <?
      if ($row['cod_tipo_usuario']=='S')
      {
      ?>
      <li><a href="poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Plan Operativo Anual</a></li>
      <li><a href="ml.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Marco Logico del Proyecto</a></li>
      <?
      }
      ?>
    </ul>
  </li>
  <li class="active">
    <a href="exit.php">Salir del sistema</a>
  </li>
</ul>
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <h5>Bienvenido:</h5>
	        
	        <p><? echo $row['nombre']." ".$row['apellido'];?>, usted se encuentra en el panel principal del sistema.</p>
	        <hr/>
	        <p>El SIIR-PDSS: Sistema de Información de Iniciativas Rurales del Proyecto de Desarrollo Sierra Sur,  es un software desarrollado por el equipo técnico del Proyecto con el objeto de disponer información ordenada y útil para reportar indicadores de procesos y resultados del Proyecto, en concordancia con las líneas de actividad ejecutadas por organizaciones y familias campesinas enmarcadas en los componentes del Proyecto, cuya descripción se puede consultar en www.sierrasur.gob.pe </p>
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
