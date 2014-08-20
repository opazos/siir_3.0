<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM gcac_bd_ruta WHERE cod_ruta='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

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
<dd  class="active"><a href="">Liquidar Contrato de Ruta de Aprendizaje</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=LIQUIDA" onsubmit="return checkSubmit();">

<div class="row">
  <div class="two columns">N. contrato</div>
  <div class="four columns"><input type="text" name="n_contrato" disabled="disabled" class="seven" value="<? echo $r1['n_contrato'];?>"></div>
  <div class="two columns">Fecha de contrato</div>
  <div class="four columns"><input type="date" name="f_contrato" disabled="disabled" class="seven" value="<? echo $r1['f_contrato'];?>"></div>
  <div class="two columns">Fecha de termino</div>
  <div class="four columns"><input type="date" name="f_termino" disabled="disabled" class="seven" value="<? echo $r1['f_termino'];?>"></div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquida" value="<? echo $r1['f_liquidacion'];?>" class="seven required date"></div>

  <div class="twelve columns"><hr/></div>
  <div class="twelve columns"><h6>I.- Resultados alcanzados</h6></div>

  <div class="twelve columns">Descripción de las acciones ejecutadas</div>
  <div class="twelve columns"><textarea name="resultado"><? echo $r1['resultado'];?></textarea></div>

  <div class="twelve columns">Recomendaciones</div>
  <div class="twelve columns"><textarea name="recomendaciones"><? echo $r1['recomendaciones'];?></textarea></div>

  <table>
    <thead>
      <tr>
        <td>BALANCE DE LA ACTIVIDAD</td>
        <td class="six">ASPECTOS FAVORABLES</td>
        <td class="six">LIMITACIONES</td>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>DE LA PARTICIPACION</td>
        <td><input type="text" name="b1_fav" value="<? echo $r1['b1_fav'];?>"></td>
        <td><input type="text" name="b1_lim" value="<? echo $r1['b1_lim'];?>"></td>
      </tr>

      <tr>
        <td>DE LA METODOLOGIA UTILIZADA</td>
        <td><input type="text" name="b2_fav" value="<? echo $r1['b2_fav'];?>"></td>
        <td><input type="text" name="b2_lim" value="<? echo $r1['b2_lim'];?>"></td>
      </tr>

      <tr>
        <td>DE LOS TEMAS TRATADOS</td>
        <td><input type="text" name="b3_fav" value="<? echo $r1['b3_fav'];?>"></td>
        <td><input type="text" name="b3_lim" value="<? echo $r1['b3_lim'];?>"></td>
      </tr>  

      <tr>
        <td>OTROS TEMAS RELEVANTES</td>
        <td><input type="text" name="b4_fav" value="<? echo $r1['b4_fav'];?>"></td>
        <td><input type="text" name="b4_lim" value="<? echo $r1['b4_lim'];?>"></td>
      </tr>            

    </tbody>

  </table>

  <div class="twelve columns"><h6>II.- Ejecución presupuestal</h6></div>

  <div class="three columns">Monto ejecutado NEC PDSS II (S/.)</div>
  <div class="nine columns"><input type="text" name="ejec_pdss" class="required number three" value="<? echo $r1['ejec_pdss'];?>"></div>
  <div class="three columns">Monto ejecutado Organización (S/.)</div>
  <div class="nine columns"><input type="text" name="ejec_org" class="required number three" value="<? echo $r1['ejec_org'];?>"></div> 
  <div class="three columns">Monto ejecutado Otros (S/.)</div>
  <div class="nine columns"><input type="text" name="ejec_otro" class="required number three" value="<? echo $r1['ejec_otro'];?>"></div>

  <div class="twelve columns"><h6>III.- Observaciones/Comentarios adicionales</h6></div>
  <div class="twelve columns"><textarea name="comentario"><? echo $r1['observaciones'];?></textarea></div>   

  <div class="twelve columns">
    <button type="submit" class="primary button">Guardar cambios</button>
    <a href="contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=liquida" class="secondary button">Finalizar</a>
  </div>


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
