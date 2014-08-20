<?
require("funciones/error_page.php");
?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
  
  <!-- Included CSS Files (Uncompressed) -->

  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/app.css">

  <script src="javascripts/modernizr.foundation.js"></script>
</head>
<body>
<div class="row">
  <div class="twelve columns">
 
    <!-- Navigation -->
 
      <nav class="top-bar contain-to-grid">
        <ul>
          <li class="name"><h1><a href="index.php">SIIR</a></h1></li>
          <li class="toggle-topbar"><a href="#"></a></li>
        </ul>
 
        <section>

 
          <ul class="right">
          <li class="search">
              <form>
                <input type="search">
              </form>
          </li>
 
            <li class="has-button">
            <a class="small button" href="#">Busqueda</a>
          </li>
          </ul>
        </section>
    </nav>
 
    <!-- End Navigation -->
 
    </div>
  </div>
 
 
  <div class="row">
    <div class="twelve columns">
 
    <!-- Desktop Slider -->
 
      <div class="hide-for-small">
        <div id="featured">
              <img src="http://www.sierrasur.gob.pe/inicio2.0/barner/1.jpg" alt="slide image">
              <img src="http://www.sierrasur.gob.pe/inicio2.0/barner/2.jpg" alt="slide image">
              <img src="http://www.sierrasur.gob.pe/inicio2.0/barner/3.jpg" alt="slide image">
          </div>
        </div>
 
    <!-- End Desktop Slider -->
 
 
    <!-- Mobile Header -->
 
 
    <div class="row">
      <div class="mobile-four show-for-small"><br>
        <img src="images/barner.jpg" />
      </div>
    </div>
 
 
  <!-- End Mobile Header -->
 
    </div>
  </div><br>
 

 
 
 
  <div class="row">
    <div class="twelve columns">
      <div class="row">
 
    <!-- Content -->
 
        <div class="eight columns">
          <div class="panel radius">
 
          <div class="row">
          <div class="six mobile-two columns">
 
            <h4>Bienvenido</h4><hr/>
            <h5 class="subheader">El SIIR-PDSS: Sistema de Información de Iniciativas Rurales del Proyecto de Desarrollo Sierra Sur,
            </h5>
 
          <div class="show-for-small" align="center">
          <!-- Formulario para celulares -->
          <form name="form1" method="post" class="custom" action="evaluar.php">
	          <div class="three columns">Usuario</div>
	          <div class="nine columns"><input type="text" name="usuario"></div>
	          <div class="three columns">Password</div>
	          <div class="nine columns"><input type="password" name="clave"></div>
	          <div class="three columns">Periodo</div>
	          <div class="nine columns">
		          <select name="periodo">
			          <option value="" selected="selected">Seleccionar</option>
			          <option value="2014">Periodo 2014</option>
			          <option value="2013">Periodo 2013</option>
			          <option value="2012">Periodo 2012</option>
			          <option value="2011">Periodo 2011</option>
		          </select>
	          </div>
	          <div class="twelve columns"><button type="submit" class="primary button">Iniciar sesion</button></div>
          </form>
           <!-- --> 
          </div>
 
          </div>
          <div class="six mobile-two columns">
 
            <p>Es un software desarrollado por el equipo técnico del Proyecto con el objeto de disponer información ordenada y útil para reportar indicadores de procesos y resultados del Proyecto, en concordancia con las líneas de actividad ejecutadas por organizaciones y familias campesinas enmarcadas en los componentes del Proyecto, cuya descripción se puede consultar en www.sierrasur.gob.pe 
          </p>
        </div>
 
        </div>
        </div>
        </div>
 
        <div class="four columns hide-for-small">
 
          <h4>Iniciar sesion</h4><hr/>
          
          <form name="form1" method="post" class="custom" action="evaluar.php">
	          <div class="three columns">Usuario</div>
	          <div class="nine columns"><input type="text" name="usuario"></div>
	          <div class="three columns">Password</div>
	          <div class="nine columns"><input type="password" name="clave"></div>
	          <div class="three columns">Periodo</div>
	          <div class="nine columns">
		          <select name="periodo">
			          <option value="" selected="selected">Seleccionar</option>
			          <option value="2014">Periodo 2014</option>
			          <option value="2013">Periodo 2013</option>
			          <option value="2012">Periodo 2012</option>
			          <option value="2011">Periodo 2011</option>
		          </select>
	          </div>
	          <div class="twelve columns"><button type="submit" class="primary button">Iniciar sesion</button></div>
          </form>
        </div>
 
    <!-- End Content -->
 
      </div>
    </div>
  </div>
 
 

 
    <!-- Footer -->
 
  <footer class="row">
    <div class="twelve columns"><hr />
      <div class="row">
 
        <div class="twelve columns">
          <p>&copy; SIIR - Sistema de Información de Iniciativas Rurales.</p>
        </div>

 
      </div>
    </div>
  </footer>
 
    <!-- End Footer -->
  <!-- Included JS Files -->
  <script src="../public/assets/templates.js"></script>
  <script type="text/javascript">
    $(window).load(function() {
      $('#featured').orbit({ fluid: '2x1' });
    });
  </script>  
  
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
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>

  
    <script>
    $(window).load(function(){
      $("#featured").orbit();
    });
    </script> 
  
</body>
</html>
